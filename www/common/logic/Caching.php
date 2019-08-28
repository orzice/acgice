<?php
/**
* Redis缓存模型
*
*     $model_hx = model('Cache','logic');
*     $model_hx->cache_set(true,'2333','6666',24*60*60);
*     $model_hx->cache_get(true,'2333');
*
*/
namespace app\common\logic;

use think\Model;
use think\Cache;

class Caching extends Model
{
	//读取缓存数据
	public function Cache_get($cahce,$name)
	{
		if(!$cahce) return false;
		
		//判断数据是否存在
		$ls_cache = Cache::get($name);
		return $ls_cache?$ls_cache:false;
	}
	//设定缓存数据
	public function Cache_set($cahce,$name,$value,$time)
	{
		if(!$cahce) return false;
		//空数据不会储存  [允许储存]
		//if($value == '' || $value == null || count($value) == 0) return false;

		//永不过期那就设置缓存30天
		if($time === 0){
			$time = 30*24*60*60;
		}

		return Cache::set($name,$value,$time);
	}
	//删除缓存数据
	public function Cache_rm($name)
	{
		return Cache::rm($name);
	}


}



?>