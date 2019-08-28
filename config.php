<?php
if (defined('Login_go')) {
    //正常
}else{
    //非法调用
    header('HTTP/1.1 404 Not Found');
    exit;
}

global $app_config;//给你返回的数据

//-------------配置内容------------------

$app_config['headers'] = [
                'Accept'=> 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Encoding'=> 'gzip, deflate, compress',
                'Accept-Language'=> 'zh-CN;q=0.5,en;q=0.3',
                'Cache-Control'=> 'max-age=0',
                'Connection'=> 'keep-alive',
                'Referer' => '',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            ];
$app_config['User-Agent'] = [
                'wz_pc' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
                'wz_wap'=> 'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
            ];

//友情链接
$app_config['link'][] = array(
    'key' => 'Acgice',
    'pic' => 'https://tva1.sinaimg.cn/mw690/007OS56Yly1g4tdfk0jojj30c20c23yq.jpg'
    );
$app_config['link'][] = array(
    'key' => 'Orzice',
    'pic' => 'https://tva1.sinaimg.cn/mw690/007OS56Yly1g4tdfk0jojj30c20c23yq.jpg'
    );
$app_config['link'][] = array(
    'key' => 'Baidu',
    'pic' => 'https://tva4.sinaimg.cn/mw690/007OS56Yly1g4te44q5b4j30do0dojto.jpg'
    );