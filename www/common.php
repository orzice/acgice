<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
function http_https($url){
  //http 转 https
  if(strstr($url,'https://')){
    return $url;
  }
  $url = str_replace('http', 'https', $url);//不想区分是否为HTTPS
  if(strstr($url,'https://')){
    return $url;
  }
  $url = str_replace('//', 'https://', $url);//不想区分是否为HTTPS
  if(strstr($url,'https://')){
    return $url;
  }
  $url = str_replace('https:https', 'https', $url);//不想区分是否为HTTPS
  return $url;
}
/**
* @author Orzice
* 把数字1-7换成汉字日期，如：7 日
* @param [num] $num [数字]
* @return [string] [string]
*/
function numToDate($num)
{
$chiNum = array('零', '一', '二', '三', '四', '五', '六', '日');
$chiStr = '';
$num_str = (string)$num;
$chiStr = $chiNum[$num_str[0]]; 

return $chiStr;
}


function set_html_gl($key,$b,$s=250){
    $b = esub($b,$s);//限制字符
    $ss_array = explode(' ', $key);//分割多搜索
    $ss_array = array_filter($ss_array);  // 删除空元素  
    sort($ss_array);//重新制作数组
    for ($i=0; $i < count($ss_array); $i++) { 
      $b = str_replace($ss_array[$i],'<span class="highlight">'.$ss_array[$i].'</span>',$b);
    }

    return $b;
}
/** 
* 压缩html : 清除换行符,清除制表符,去掉注释标记 
* @param $string 
* @return压缩后的$string 
* */ 
function compress_html($string){ 
$string=str_replace("\r\n",'',$string);//清除换行符 
$string=str_replace("\n",'',$string);//清除换行符 
$string=str_replace("\t",'',$string);//清除制表符 
$pattern=array( 
"/> *([^ ]*) *</",//去掉注释标记 
"/[\s]+/", 
"/<!--[^!]*-->/", 
"/\" /", 
"/ \"/", 
"'/\*[^*]*\*/'" 
); 
$replace=array ( 
">\\1<", 
" ", 
"", 
"\"", 
"\"", 
"" 
); 
return preg_replace($pattern, $replace, $string); 
} 

// 获取根域名
function getDomain($url=''){
  if(!$url){
    return $url;
  }
  #列举域名中固定元素
  $state_domain = array(
    'al','dz','af','ar','ae','aw','om','az','eg','et','ie','ee','ad','ao','ai','ag','at','au','mo','bb','pg','bs','pk','py','ps','bh','pa','br','by','bm','bg','mp','bj','be','is','pr','ba','pl','bo','bz','bw','bt','bf','bi','bv','kp','gq','dk','de','tl','tp','tg','dm','do','ru','ec','er','fr','fo','pf','gf','tf','va','ph','fj','fi','cv','fk','gm','cg','cd','co','cr','gg','gd','gl','ge','cu','gp','gu','gy','kz','ht','kr','nl','an','hm','hn','ki','dj','kg','gn','gw','ca','gh','ga','kh','cz','zw','cm','qa','ky','km','ci','kw','cc','hr','ke','ck','lv','ls','la','lb','lt','lr','ly','li','re','lu','rw','ro','mg','im','mv','mt','mw','my','ml','mk','mh','mq','yt','mu','mr','us','um','as','vi','mn','ms','bd','pe','fm','mm','md','ma','mc','mz','mx','nr','np','ni','ne','ng','nu','no','nf','na','za','aq','gs','eu','pw','pn','pt','jp','se','ch','sv','ws','yu','sl','sn','cy','sc','sa','cx','st','sh','kn','lc','sm','pm','vc','lk','sk','si','sj','sz','sd','sr','sb','so','tj','tw','th','tz','to','tc','tt','tn','tv','tr','tm','tk','wf','vu','gt','ve','bn','ug','ua','uy','uz','es','eh','gr','hk','sg','nc','nz','hu','sy','jm','am','ac','ye','iq','ir','il','it','in','id','uk','vg','io','jo','vn','zm','je','td','gi','cl','cf','cn','yr','com','arpa','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','me','mobi','asia','ax','bl','bq','cat','cw','gb','jobs','mf','rs','su','sx','tel','travel','accountant','app','art','auction','audio','auto','band','bar','beer','best','bid','bike','black','blog','blue','business','cab','cafe','camera','car','cards','cars','cash','center','ceo','chat','city','click','clothing','club','cn.com','coffee','com.co','com.hk','com.tw','company','cool','credit','cricket','date','design','dev','diet','dog','domains','download','email','equipment','estate','expert','faith','family','fans','feedback','fish','fit','flowers','fun','fund','fyi','game','games','gift','gives','gold','group','guru','haus','help','holiday','host','hosting','house','ink','kim','land','life','link','live','loan','lol','love','ltd','market','marketing','mba','media','men','mom','money','net.co','network','news','one','online','ooo','party','pet','photo','photography','photos','pics','pink','plus','press','property','pub','racing','red','ren','rent','review','rip','run','sale','school','science','services','sex','sexy','shop','show','site','social','software','solutions','space','store','studio','style','support','taipei','tax','team','tech','test','tips','today','tools','top','town','toys','trade','ventures','vet','video','vin','vip','wang','watch','webcam','website','wiki','win','wine','work','works','world','wtf','xin','xyz','yoga','zone','net.cn','org.cn'
  );
    
  if(!preg_match("/^http/is", $url)){
    $url="http://".$url;
  }
  $res = null;

  $url_parse = parse_url(strtolower($url));
  $urlarr = explode(".", $url_parse['host']);
  $count = count($urlarr);
  if($count <= 2){
    #当域名直接根形式不存在host部分直接输出
    $res = $url_parse['host'];
  }elseif($count > 2){
    $last = array_pop($urlarr);
    $last_1 = array_pop($urlarr);
    $last_2 = array_pop($urlarr);

    $res = $last_2.'.'.$last_1.'.'.$last;
  }
  return $res;
}
// 获取根域名
function getBaseDomain($url=''){
  if(!$url){
    return $url;
  }
  #列举域名中固定元素
  $state_domain = array(
    'al','dz','af','ar','ae','aw','om','az','eg','et','ie','ee','ad','ao','ai','ag','at','au','mo','bb','pg','bs','pk','py','ps','bh','pa','br','by','bm','bg','mp','bj','be','is','pr','ba','pl','bo','bz','bw','bt','bf','bi','bv','kp','gq','dk','de','tl','tp','tg','dm','do','ru','ec','er','fr','fo','pf','gf','tf','va','ph','fj','fi','cv','fk','gm','cg','cd','co','cr','gg','gd','gl','ge','cu','gp','gu','gy','kz','ht','kr','nl','an','hm','hn','ki','dj','kg','gn','gw','ca','gh','ga','kh','cz','zw','cm','qa','ky','km','ci','kw','cc','hr','ke','ck','lv','ls','la','lb','lt','lr','ly','li','re','lu','rw','ro','mg','im','mv','mt','mw','my','ml','mk','mh','mq','yt','mu','mr','us','um','as','vi','mn','ms','bd','pe','fm','mm','md','ma','mc','mz','mx','nr','np','ni','ne','ng','nu','no','nf','na','za','aq','gs','eu','pw','pn','pt','jp','se','ch','sv','ws','yu','sl','sn','cy','sc','sa','cx','st','sh','kn','lc','sm','pm','vc','lk','sk','si','sj','sz','sd','sr','sb','so','tj','tw','th','tz','to','tc','tt','tn','tv','tr','tm','tk','wf','vu','gt','ve','bn','ug','ua','uy','uz','es','eh','gr','hk','sg','nc','nz','hu','sy','jm','am','ac','ye','iq','ir','il','it','in','id','uk','vg','io','jo','vn','zm','je','td','gi','cl','cf','cn','yr','com','arpa','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','me','mobi','asia','ax','bl','bq','cat','cw','gb','jobs','mf','rs','su','sx','tel','travel','accountant','app','art','auction','audio','auto','band','bar','beer','best','bid','bike','black','blog','blue','business','cab','cafe','camera','car','cards','cars','cash','center','ceo','chat','city','click','clothing','club','cn.com','coffee','com.co','com.hk','com.tw','company','cool','credit','cricket','date','design','dev','diet','dog','domains','download','email','equipment','estate','expert','faith','family','fans','feedback','fish','fit','flowers','fun','fund','fyi','game','games','gift','gives','gold','group','guru','haus','help','holiday','host','hosting','house','ink','kim','land','life','link','live','loan','lol','love','ltd','market','marketing','mba','media','men','mom','money','net.co','network','news','one','online','ooo','party','pet','photo','photography','photos','pics','pink','plus','press','property','pub','racing','red','ren','rent','review','rip','run','sale','school','science','services','sex','sexy','shop','show','site','social','software','solutions','space','store','studio','style','support','taipei','tax','team','tech','test','tips','today','tools','top','town','toys','trade','ventures','vet','video','vin','vip','wang','watch','webcam','website','wiki','win','wine','work','works','world','wtf','xin','xyz','yoga','zone','net.cn','org.cn'
  );
    
  if(!preg_match("/^http/is", $url)){
    $url="http://".$url;
  }
  $res = null;
  $res['domain'] = null;
  $res['host'] = null;
  $url_parse = parse_url(strtolower($url));
  $urlarr = explode(".", $url_parse['host']);
  $count = count($urlarr);
  if($count <= 2){
    #当域名直接根形式不存在host部分直接输出
    $res['domain'] = $url_parse['host'];
  }elseif($count > 2){
    $last = array_pop($urlarr);
    $last_1 = array_pop($urlarr);
    $last_2 = array_pop($urlarr);
      
    $res['domain'] = $last_1.'.'.$last;
    $res['host'] = $last_2;
      
    if(in_array($last, $state_domain)){
      $res['domain']=$last_1.'.'.$last;
      $res['host']=implode('.', $urlarr);
    }
      
    if(in_array($last_1, $state_domain)){
      $res['domain'] = $last_2.'.'.$last_1.'.'.$last;
      $res['host'] = implode('.', $urlarr);
    }
    #print_r(get_defined_vars());die;
  }
  return $res;
}

//判断来源
function is_platform(){
  //判断来源
  if(isMobile()){
    //WAP版上
    return 'wap';
   }else{
    //PC版
    return 'pc';
   }
}


/**
 * 数组 转 对象
 *
 * @param array $arr 数组
 * @return object
 */
function array_to_object($arr) {
    if (gettype($arr) != 'array') {
        return;
    }
    foreach ($arr as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object') {
            $arr[$k] = (object)array_to_object($v);
        }
    }
    return (object)$arr;
}
 
/**
 * 对象 转 数组
 *
 * @param object $obj 对象
 * @return array
 */
function object_to_array($obj) {
    $obj = (array)$obj;
    foreach ($obj as $k => $v) {
        if (gettype($v) == 'resource') {
            return;
        }
        if (gettype($v) == 'object' || gettype($v) == 'array') {
            $obj[$k] = (array)object_to_array($v);
        }
    }
 
    return $obj;
}

//数组转XML
function arrayToXml($arr)
{
    $xml = "<xml>";
    foreach ($arr as $key=>$val)
    {
        if (is_numeric($val)){
            $xml.="<".$key.">".$val."</".$key.">";
        }else{
             $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
    }
    $xml.="</xml>";
    return $xml;
}

//将XML转为array
function xmlToArray($xml)
{    
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
    return $values;
}
    

//判断是否手机版
function isMobile()
{ 
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 
if (isMobile()==true) {
    return true;
}else{
    return false;
}
//安全策略 数字  失败弹出404 成功返回数组
function security_numeric($id){
    if(!is_numeric($id)){
        //id不是全数字 抛出404 抛出0
        //_404();
        return 0;
    }
    return $id;
}
//安全策略 数字 且到整数 失败弹出404 成功返回数组
function security_number($id){
    if(!is_numeric($id)){
        //id不是全数字 抛出404 抛出0
        //_404();
        return 0;
    }
    $id = intval($id);//到整数
    //if($id==0){
        //id不存在则抛出404错误
        //_404();
    //}
    return $id;
}
//安全策略 字符 失败弹出404 成功返回数组
function security_string($text){
    //安全策略 防止SQL注入
    /*
    if(!get_magic_quotes_gpc()){
      $text = addslashes($text);
    }
    $text = str_replace("_", "\_",$text);// 吧 '_'过滤
    $text = str_replace("%", "\%",$text);// 吧 '%'过滤
    //$text = nl2br($text);// 回车转换
    */
    //安全策略 HTML实体化
    $text = htmlspecialchars($text);//html格式化
    return $text;
}

//跳转页面
function _urljamp($url){
  header('Location:'.$url);
    exit;
}
//跳转301页面
function _301($data){
    Header("HTTP/1.1 301 Moved Permanently");
    Header("Location: ".$data);
    exit();
}
//跳转302页面
function _302($data){
    Header("Location: ".$data);
    exit();
}
//传入HTML代码 删除所有的标签
function html_text($message){
  $message = preg_replace("/<style[^>]*>(.*?)<\/style>/is", "", $message);//去掉CSS
  $message = preg_replace("/<script[^>]*>(.*?)<\/script>/is", "", $message);//去掉CSS

  $message = strip_tags($message);//去除html标签
  $message =  preg_replace('/\s/', '', $message);  //去除空白$message=  preg_replace("/<a[^>]*>/i", "", $message);  

    return $message;
}

/**
*截取字符串,汉字占两个字节，字母占一个字节
*页面编码必须为utf-8
*/
function esub($str, $length = 0,$ext = "..."){

    if($length < 1){
        return $str;
    }

    //计算字符串长度
    $strlen = (strlen($str) + mb_strlen($str,"UTF-8")) / 2;
    if($strlen < $length){
        return $str;
    }

    if(mb_check_encoding($str,"UTF-8")){
        //$str = mb_strcut(mb_convert_encoding($str, "GBK","UTF-8"), 0, $length, "GBK");
        //$str = mb_convert_encoding($str, "UTF-8", "GBK");
        $str = mb_strcut($str, 0, $length);//, "UTF-8");

    }else{
        return "不支持的文档编码";
    }
    
    //$str = rtrim($str," ,.。，-——（【、；‘“??《<@");
    return $str.$ext;
}
/*
$str:要截取的字符串
$start=0：开始位置，默认从0开始
$length：截取长度
$charset=”utf-8″：字符编码，默认UTF－8
$suffix=true：是否在截取后的字符后面显示省略号，默认true显示，false为不显示
*/
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) 
{ 
 if(function_exists("mb_substr")){ 
 if($suffix) 
  return mb_substr($str, $start, $length, $charset)."..."; 
 else
  return mb_substr($str, $start, $length, $charset); 
 } 
 elseif(function_exists('iconv_substr')) { 
 if($suffix) 
  return iconv_substr($str,$start,$length,$charset)."..."; 
 else
  return iconv_substr($str,$start,$length,$charset); 
 } 
 $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/"; 
 $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/"; 
 $re['gbk']  = "/[x01-x7f]|[x81-xfe][x40-xfe]/"; 
 $re['big5']  = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/"; 
 preg_match_all($re[$charset], $str, $match); 
 $slice = join("",array_slice($match[0], $start, $length)); 
 if($suffix) return $slice."…"; 
 return $slice;
}
/*以下是取中间文本的函数 
getSubstr=调用名称
$str=预取全文本 
$leftStr=左边文本
$rightStr=右边文本
*/
function getSubstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}


