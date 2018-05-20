<?php
include_once __DIR__ . '/../vendor/autoload.php';

use Curl\Curl;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$curl = new Curl();
//$curl->setBasicAuthentication('username', 'password');
$curl->setUserAgent('MyUserAgent/0.0.1 (+https://www.example.com/bot.html)');
//$curl->setReferrer('https://www.example.com/url?url=https%3A%2F%2Fwww.example.com%2F');
//$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
$curl->setCookies([
    'wordpress_760b44726ec4e18691594516f06e693f' => 'admin%7C1527929460%7CcpbUN4py5eKfjwwV7DmPAEqWdsFfoQfk5O3VEN03iS1%7Ca581ea7ae633cd290425746504f6c2bbeeadebe88d878e1e7f1357ac75545282',
]);
$curl->get('http://wp.local/wp-admin/media-new.php');

if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    echo 'Response:' . "\n";
    print_r($curl->response);
}

//var_dump($curl->requestHeaders);
//var_dump($curl->responseHeaders);