<?php
/**
* Keys 特殊命令操作
*/
namespace app\common\model;

use think\Model;
use think\Db;
use think\Controller;

use QL\QueryList;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;//多线程
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;

class Keys extends Model
{
	public $key = '';//返回的字符串
	public $ml = array();//命令
	public $sl = false;//命令

/** 初始化模型参数   **/ 
	public function Key($key=false){
		if(!$key){return $key;}
		$this->key = $key;
		$this->ml_set();
		$this->key_set();
		return $this->key;
	}
	public function get_sl(){
		return $this->sl;
	}
/**  注册命令参数 (触发任意一个即跳出)
 *  数组分为3个  0是命令  1是转换为的字符 2是处理方式：1 全等 ，2模糊 3是调用的函数
 */
	public function ml_set(){
	}
	/* 注册命令参数 */
	public function key_set(){
		if(count($this->ml) == 0){return $this->key;}
		for ($i=0; $i < count($this->ml); $i++) { 
			$lx = false;
			if($this->ml[$i][2] == 1){
				if($this->key == $this->ml[$i][0]){$lx = true;}
			}else{
				if(strstr($this->key,$this->ml[$i][0]) !== false){$lx = true;}
			}
			if(!$lx){continue;}
			if($this->ml[$i][3] !== ''){
				$sl = $this->ml[$i][3];
				//判断操作是否存在
				if(!method_exists($this,$sl)){
					return $this->key;
				}
				$this->$sl();
			}
			$this->key = $this->ml[$i][1];
		}
	}
/**
 *  ================================【key】=========================================
 */


}


?>