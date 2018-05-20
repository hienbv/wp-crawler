<?php

return [
    'fullUrl' => 'http://source.local/',
    'submit_post_url' => 'http://wp.local/wp-admin/post.php',
    'submit_media_url' => 'http://wp.local/wp-admin/async-upload.php',
    'is_copy_image' => true,
    'account' => [
        'user_ID' => 1,
        'log' => '',
        'pwd' => '',
        'rememberme' => 'forever',
    ],
    'str_replace' => [
        PHP_EOL => '',
        '<p style="text-align:center">&nbsp;</p>' => '',
        '<p style="text-align:center"> </p>' => '',
        '<p> </p>' => '',
        '<p>&nbsp;</p>' => '',
        '<h1>' => '<p>',
        '</h1>' => '</p>',
    ],
    'categories' => [
        [
            'category_slug' => 'nha-pho-hien-dai-nha-mau-dep',
            'category_id' => 7,
            'url_source' => 'http://source.local/mau-nha-dep/mau-nha-pho-dep/page-%s',
            'page_start' => 1,
            'page_end' => 14,
            'url_ignores' => ['co-dien'],
            'url_contains' => [],
            'elem_post_url' => 'div.text-center > a.name',
            'post' => [
                'title' => 'article.box_content > header.title > h1',
                'content' => 'article.box_content > div.des',
                'trip_tags_content' => '-div#fb-root -ul.social -script -section -h2:first -div.tags -div.tag'
            ]
        ],
//        [
//            'category_slug' => 'nha-pho-tan-co-dien',
//            'category_id' => 8,
//            'url_source' => 'http://source.local/mau-nha-dep/mau-nha-pho-dep/page-%s',
//            'page_start' => 1,
//            'page_end' => 14,
//            'url_ignores' => [],
//            'url_contains' => ['co-dien'],
//            'elem_post_url' => 'div.text-center > a.name',
//            'post' => [
//                'title' => 'article.box_content > header.title > h1',
//                'content' => 'article.box_content > div.des',
//                'trip_tags_content' => '-div#fb-root -ul.social -script -section -h2:first -div.tags'
//            ]
//        ],
//        [
//            'category_slug' => 'biet-thu-hien-dai',
//            'category_id' => 10,
//            'url_source' => 'http://source.local/mau-nha-dep/mau-biet-thu-dep/page-%s',
//            'page_start' => 1,
//            'page_end' => 12,
//            'url_ignores' => ['co-dien'],
//            'url_contains' => [],
//            'elem_post_url' => 'div.text-center > a.name',
//            'post' => [
//                'title' => 'article.box_content > header.title > h1',
//                'content' => 'article.box_content > div.des',
//                'trip_tags_content' => '-div#fb-root -ul.social -script -section -h2:first -div.tags'
//            ]
//        ],
//        [
//            'category_slug' => 'biet-thu-co-dien',
//            'category_id' => 11,
//            'url_source' => 'http://source.local/mau-nha-dep/mau-biet-thu-dep/page-%s',
//            'page_start' => 1,
//            'page_end' => 12,
//            'url_ignores' => [],
//            'url_contains' => ['co-dien'],
//            'elem_post_url' => 'div.text-center > a.name',
//            'post' => [
//                'title' => 'article.box_content > header.title > h1',
//                'content' => 'article.box_content > div.des',
//                'trip_tags_content' => '-div#fb-root -ul.social -script -section -h2:first -div.tags'
//            ]
//        ],
//        [
//            'category_slug' => 'nha-cap-4-hien-dai-nha-mau-dep',
//            'category_id' => 9,
//            'url_source' => 'http://source.local/mau-nha-dep/mau-nha-cap-4-dep/page-%s',
//            'page_start' => 1,
//            'page_end' => 4,
//            'url_ignores' => [],
//            'url_contains' => [],
//            'elem_post_url' => 'div.text-center > a.name',
//            'post' => [
//                'title' => 'article.box_content > header.title > h1',
//                'content' => 'article.box_content > div.des',
//                'trip_tags_content' => '-div#fb-root -ul.social -script -section -h2:first -div.tags'
//            ]
//        ]
    ]
];
