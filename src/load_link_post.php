<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/functions.php';
$config = include_once __DIR__ . '/config.php';

use QL\QueryList;
use QL\Ext\AbsoluteUrl;

load_link_post($config);

function load_link_post($config)
{
    $inputs = $config['categories'];
    $fullUrl = $config['fullUrl'];

    $ql = QueryList::getInstance();
    $ql->use(AbsoluteUrl::class);
    print_out('Start get link post', '---------- %s ----------');
    $total_post = 0;
    foreach ($inputs as $option) {
        $file = fopen('data/post_links/' . $option['category_slug'] . '.csv', 'w+');
        $total_post_cat = 0;
        for ($page = $option['page_start']; $page <= $option['page_end']; $page++) {
            $url = sprintf($option['url_source'], $page);
            $post_urls = $ql->get($url)
                    ->absoluteUrl($fullUrl)
                    ->find($option['elem_post_url'])
                    ->attrs('href');
            foreach ($post_urls as $post_url) {
                if (str_ignores($post_url, $option['url_ignores']) && str_contains($post_url, $option['url_contains'])) {
                    $total_post++;
                    $total_post_cat++;
                    fputcsv($file, [$post_url, 'new', date('Y-m-d H:i:s')]);
                }
            }
        }
        print_out($option['category_slug'], '==========> %s ..Done');
        print_out($total_post_cat, 'Posts Count: %s');
        fclose($file);
    }

    print_out($total_post, 'Total posts: %s');
    print_out('End get link post', '---------- %s ----------');
}
