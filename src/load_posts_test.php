<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/functions.php';
$config = include_once __DIR__ . '/config.php';

use QL\QueryList;
use QL\Ext\AbsoluteUrl;

$ql = QueryList::getInstance();
$ql->use(AbsoluteUrl::class);
$inputs = $config['categories'];
$fullUrl = $config['fullUrl'];
$post_title = $ql->get('http://nhadep.xaydungso.vn/so-do-thiet-ke/noi-that-dep/noi-that-nha-o/noi-that-phong-ngu-dep-hien-dai.html')
        ->find('article.box_content > header.title > h1')
        ->text();

$trip_tags_content = ['script', 'ul.social', 'div#fb-root', 'section.related'];
$post_content = $ql->get('http://nhadep.xaydungso.vn/so-do-thiet-ke/noi-that-dep/noi-that-nha-o/noi-that-phong-ngu-dep-hien-dai.html')
        ->absoluteUrl($fullUrl)
        ->rules([
            'content' => ['.des', 'html', '-div#fb-root -ul.social -script -section']
        ])
        ->range('.box_content')
        ->query();

$data = $ql->getData(function($item) {
    $content = QueryList::html($item['content']);
    // Remove text Sưu tầm
    $content->find('p')->filter(':contains(Sưu tầm)')->map(function($elem) {
        echo $elem->text() . PHP_EOL;
    });

    // Change path image
    $content->find('img')->map(function($img) {
        // TODO
//        $src = 'http://cms.querylist.cc' . $img->src;
//        $localSrc = 'image/' . md5($src) . '.jpg';
//        $stream = file_get_contents($src);
//        file_put_contents($localSrc, $stream);
//        $img->attr('src', $localSrc);
    });

    $item['content'] = $content->find('')->html();
    return $item;
});

$catDir = __DIR__ . '/../data/posts';
$file = fopen($catDir . '/' . $post_title . '.html', 'w+');
fwrite($file, $data->all()[0]['content']);
fclose($file);
