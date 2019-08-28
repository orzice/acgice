<?php
namespace app\index\controller;

use think\Exception;
use think\Controller;
use think\Url;
use think\Cache;


use app\common\model\Sousuo;//聚合搜索模型
use app\common\model\Keys;//Key模型



//首页版块控制器

class Index  extends Controller
{
    public function _empty($name){
        //空操作返回404
         return _404();
    }
    public function _initialize()
    {
        
        if(isset($_GET['lang'])){
            cookie('acgice_lang',$_GET['lang'], 7*86400);
        }

    }
    // 优雅的 ( ﹁ ﹁ ) ~→ 弱鸡代码
    public function index_function($q='',$page=1,$state=0,$l='web')
    {
        global $app_config;
        $platform = is_platform();
        $platform = 'pc';//默认全PC
        $q = security_string($q);
        $state = security_number($state);

        if($q == ''){
            //首页
$data = array();

            header("Cache-Control: public, max-age=3600");//可以让页面进行缓存 全部缓存 缓存10分钟
            $html_data = [
               'STATIC_PATH' => CSS_URL,//CSS目录.
               'platform' => $platform,
               'app_config' => $app_config,
               'data' => $data,
               'l' => $l
            ];
            return view('pc/home',$html_data);
        }

        $page = security_number($page);
        if($page < 1){
            $page = 1;
        }

        $key = $q;//搜索词

        $Key = new Keys;//Keys 命令序列
        $key = $Key->Key($key);
        if($Key->get_sl()){
            return $Key->get_sl();
        }

        //Cache::clear(); //关闭缓存

        $sy = array();//搜索渠道
        $cache_time = false;//缓存时间

        if($platform == 'pc'){
            $app_config['headers']['User-Agent'] = $app_config['User-Agent']['wz_pc'];//PC伪装访问蜘蛛

        }else{
            $app_config['headers']['User-Agent'] = $app_config['User-Agent']['wz_wap'];//WAP伪装访问蜘蛛
        }


switch ($l) {
    case 'video':
        //视频搜索
        $sy[] = 'Baidu';
        $cache_time = 3600;//缓存时间 1小时
        break;
    default:
        # 默认就是WEB 网页 缓存用默认的
        $l='web';
        $platform = 'pc';
        if($platform == 'pc'){
            //电脑搜索
            $sy[] = 'Baidu';// 6.6 S  最慢  0.47 S
        }else{
            //手机搜索
            $sy[] = 'Baidu';// 最慢 
        }

        break;
}

        
        $data = array();
if($state !== 0){
//print_r($sy);
//echo '<br>';
//debug('qq_qq');
        $Sosuo = new Sousuo;//搜索聚合对象 By：小涛
        $Sosuo->Initialize($sy,$key,$app_config['headers'],$page);//初始化任务
//debug('qq_set');
        $Sosuo->get_sosuo($platform,$cache_time);//开始请求
//debug('qq_end');
        $data = $Sosuo->get_sort_V1();//排序算法
//debug('sf_end');

//echo '<br>总请求耗时：'.debug('qq_set','qq_end').'s<br>';
//echo '算法耗时：'.debug('qq_end','sf_end').'s<br>';
//echo '总耗时：'.debug('qq_qq','sf_end').'s<br>';
//exit;
}


        $html_data = [
           'app_config' => $app_config,
           'STATIC_PATH' => CSS_URL,//CSS目录.
           'data' => $data,//页面数据
           'key' => $key,//页面数据
           'page' => $page,//页面数据
           'l' => $l,//页面数据
        ];
        if($state !== 0){
            return view('pc/data_s',$html_data);
        }else{
            return view('pc/data',$html_data);
        }
    }
}

