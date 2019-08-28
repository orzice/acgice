<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
// 定义应用目录
define('APP_PATH',__DIR__. '/www/');
// 定义配置文件目录和应用目录同级
define('CONF_PATH', __DIR__.'/www/');
// 定义是否使用缓存
define('dz_cahce', false);

// 定义网址网站后缀标题
define('S_TITLE', 'AcgIce');
// 定义网址网站默认关键字
define('S_Keywords', 'AcgIce,acg,ice');
// 定义网址网站默认说明
define('S_Description', '');

// 定义根目录
define('G_URL', '/tp/Acgice开源版/');//tp/Acgice开源版/
// 定义css等的URL
define('CSS_URL', G_URL.'static/');


$app_config = array();//全局部分变量

//登录账号
define("Login_go","1");//检验是否为本地调用
$login_data = array();//返回核心数据

$app_config['login'] = array();
include 'config.php';//给全局添加配置项


// 加载框架引导文件
require __DIR__ .'/../tp2/thinkphp/base.php';
// 添加额外的代码

// 执行应用
\think\App::run()->send();


?>