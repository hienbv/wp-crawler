<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/functions.php';
$config = include_once __DIR__ . '/config.php';

use QL\QueryList;
use QL\Ext\AbsoluteUrl;
use Curl\Curl;

$curl = new Curl();
$curl->post('http://wp.local/wp-login.php', array(
    'log' => 'admin',
    'pwd' => 'qatest123',
    'rememberme' => 'forever',
));
$config['account']['cookies'] = $curl->getResponseCookies();

// https://www.example.com/search?q=keyword
load_posts($config);

function load_posts($config)
{
    $inputs = $config['categories'];
    $fullUrl = $config['fullUrl'];

    $ql = QueryList::getInstance();
    $ql->use(AbsoluteUrl::class);
    print_out('Start load post content', '---------- %s ----------');
    $total_post = 0;
    foreach ($inputs as $option) {
        $file_post_link = __DIR__ . '/../data/post_links/' . $option['category_slug'] . '.csv';
        $file_handle = fopen($file_post_link, 'aw+');
        $total_post_cat = 0;
        $post_urls_data = [];
        while ($post_urls = fgetcsv($file_handle)) {
            $post_url = $post_urls[0];
            if (str_ignores($post_url, $option['url_ignores']) && str_contains($post_url, $option['url_contains'])) {
                $post_title = $ql->get($post_url)
                        ->find($option['post']['title'])
                        ->text();

                $post_content = $ql->get($post_url)
                        ->absoluteUrl($fullUrl)
                        ->rules([
                            'content' => [$option['post']['content'], 'html', $option['post']['trip_tags_content']]
                        ])
                        ->query();

                $data = $ql->getData(function($item) {
                    $content = QueryList::html($item['content']);
                    // Remove text Sưu tầm
                    $content->find('p')->filter(':contains(Sưu tầm)')->map(function($elem) {
                        $elem->html('<span style="color:#000000"><span style="font-size:14px"><span style="font-family:arial,helvetica,sans-serif"><em><strong>Sưu tầm</strong></em></span></span></span>');
                    });

                    // Change path image
                    if ($GLOBALS['config']['is_copy_image']) {
                        $content->find('img')->map(function($img) {
                            $src = $img->src;

                            $pathinfo = pathinfo($src);
                            $filename = $pathinfo['filename'] . '_' . time() . '.' . $pathinfo['extension'];
                            $localSrc = __DIR__ . '/../image/' . $filename;
                            if ($stream = file_get_contents($src)) {
                                file_put_contents($localSrc, $stream);
                                //http://wp.local/wp-content/uploads/2018/03/phong-khach1-mau-nha-pho-mat-tien-6x20m.jpg
                                $rs = insert_img($GLOBALS['config'], $localSrc, $filename);
                                if ($rs && $rs['success']) {
                                    $img->attr('src', $rs['data']['url']);
                                }
                            }
                        });
                    }

                    // http://wp.local/mau-nha-pho-5-tang-60m2/ (fix)
                    $content->find('p > span')->filter(':contains(iic@nuce.edu.vn)')->map(function($elem) {
                        //tracco86@gmail.com
                        $elem->text('Chúng tôi sẵn lòng hỗ trợ bạn đọc, hãy email về cho chúng tôi theo địa chỉ tracco86@gmail.com');
                    });

                    $item['content'] = $content->find('')->html();
                    return $item;
                });

                $total_post++;
                $total_post_cat++;

                $post_content = $data->all()[0]['content'];
                $post_content = htmlentities($post_content, null, 'utf-8');
                foreach ($config['str_replace'] as $search => $replace) {
                    $post_content = str_replace($search, $replace, htmlspecialchars_decode($post_content));
                }

                // InsertDB
                $categoryIds = [0, $option['category_id']];
                insert_post($config, $post_title, $post_content, '', $categoryIds);
                print_out($post_title, $total_post . '====================> %s ..Done');
                $post_urls[1] = 'completed';
                $post_urls[2] = date('Y-m-d H:i:s');
                $post_urls_data[] = $post_urls;
            }
        }

        fclose($file_handle);
        write_csv($file_post_link, $post_urls_data);

        print_out($option['category_slug'], '==========> %s ..Done');
        print_out($total_post_cat, 'Posts Count: %s');
        print_out('', '(.)(.)  (.)(.)  (.)(.)  (.)(.)  (.)(.)  (.)(.)  (.)(.)' . PHP_EOL);
    }

    print_out($total_post, 'Total posts: %s');
    print_out('End load post content', '---------- %s ----------');
}

function insert_post($config, $title, $content, $thumbnail_id, $categoryIds)
{
    $post_default = [
        '_wpnonce' => '37b124b3a6', // not use
        '_wp_http_referer' => '/wp-admin/post-new.php',
        'user_ID' => $config['account']['user_ID'],
        'action' => 'post',
        'originalaction' => 'post',
        'post_author' => $config['account']['user_ID'],
        'post_type' => 'post',
        'original_post_status' => 'auto-draft',
        'referredby' => 'http://wp.local/wp-admin/post-new.php?wp-post-new-reload=true',
        '_wp_original_http_referer' => 'http://wp.local/wp-admin/post-new.php?wp-post-new-reload=true',
        'auto_draft' => '',
//        'post_ID' => '305', // Not use
        'meta-box-order-nonce' => '046277543b',
        'closedpostboxesnonce' => '7211858d8e',
        'post_title' => $title,
        'samplepermalinknonce' => '6a6593b603',
        'content' => $content,
        'wp-preview' => '',
        'hidden_post_status' => 'draft',
        'post_status' => 'draft',
        'hidden_post_password' => '',
        'hidden_post_visibility' => 'public',
        'visibility' => 'public',
        'post_password' => '',
        // <<<TIME
        'jj' => '19',
        'mm' => '05',
        'aa' => '2018',
        'hh' => '17',
        'mn' => '09',
        'ss' => '53',
        'hidden_mm' => '05',
        'cur_mm' => '05',
        'hidden_jj' => '19',
        'cur_jj' => '19',
        'hidden_aa' => '2018',
        'cur_aa' => '2018',
        'hidden_hh' => '17',
        'cur_hh' => '17',
        'hidden_mn' => '09',
        'cur_mn' => '09',
        // TIME>>>
        'original_publish' => 'Đăng bài viết',
        'publish' => 'Đăng bài viết',
        'post_category' => $categoryIds,
        'newcategory' => 'Tên danh mục mới',
        'newcategory_parent' => '-1',
        '_ajax_nonce-add-category' => 'ff8a133602',
        'tax_input' => [
            'post_tag' => '',
        ],
        'newtag' => [
            'post_tag' => '',
        ],
        'custom_meta_box_nonce' => '6c1c9d7dd3',
        'estore_page_specific_layout' => 'default_layout',
        '_thumbnail_id' => $thumbnail_id,
        'excerpt' => '',
        'trackback_url' => '',
        'metakeyselect' => '#NONE#',
        'metakeyinput' => '',
        'metavalue' => '',
        '_ajax_nonce-add-meta' => 'd37c75d23a',
        'advanced_view' => '1',
        'post_name' => '',
        'post_author_override' => '1',
    ];

    $curl = new Curl();
    $curl->setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36');
    $curl->setCookies($config['account']['cookies']);

    $curl->post($config['submit_post_url'], $post_default);
    if ($curl->error) {
        echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    } else {
        echo 'Response:' . "\n";
        var_dump($curl->response);
    }

    return 0;
}

function insert_img($config, $localSrc, $filename)
{
    $post_default = [
        'name' => $filename,
        'action' => 'upload-attachment',
        'async-upload' => new CURLFile($localSrc),
    ];

    $curl = new Curl();
    $curl->setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36');
    $curl->setCookies($config['account']['cookies']);

    $curl->post($config['submit_media_url'], $post_default);
    if ($curl->error) {
        echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
    } else {
        $rs = json_decode($curl->response, true);
        echo 'Successfully Uploaded Image:' . $rs['data']['title'] . "\n";
        return $rs;
    }

    return 0;
}

function write_csv($file, $datas, $mode = 'w+')
{
    if (is_file($file)) {
        $file_handle = fopen($file, $mode);
        foreach ($datas as $data) {
            fputcsv($file_handle, $data);
        }
        fclose($file_handle);
        return true;
    }

    return false;
}
