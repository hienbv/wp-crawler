<?php

require 'vendor/autoload.php';

use QL\QueryList;


//需要采集的目标页面
$page = 'http://cms.querylist.cc/news/566.html';
//采集规则
$reg = [
    //采集文章标题
    'title' => ['h1','text'],
    //采集文章发布日期,这里用到了QueryList的过滤功能，过滤掉span标签和a标签
    'date' => ['.pt_info','text','-span -a',function($content){
        //用回调函数进一步过滤出日期
        $arr = explode(' ',$content);
        return $arr[0];
    }],
    //采集文章正文内容,利用过滤功能去掉文章中的超链接，但保留超链接的文字，并去掉版权、JS代码等无用信息
    'content' => ['.post_content','html','-p -.content_copyright -script']
];
$rang = '.content';
$ql = QueryList::get($page)->rules($reg)->range($rang)->query();

$data = $ql->getData(function($item){
  //利用回调函数下载文章中的图片并替换图片路径为本地路径
  //使用本例请确保当前目录下有image文件夹，并有写入权限
  $content = QueryList::html($item['content']);
  $content->find('img')->map(function($img){
      $src = 'http://cms.querylist.cc'.$img->src;
      $localSrc = 'image/'.md5($src).'.jpg';
      $stream = file_get_contents($src);
      file_put_contents($localSrc,$stream);
      $img->attr('src',$localSrc);
  });
  $item['content'] = $content->find('')->html();
  return $item;
});

//打印结果
print_r($data->all());
