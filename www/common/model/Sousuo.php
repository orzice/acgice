<?php
/**
* Sousuo 聚合搜索模型 算法
*  
*/
namespace app\common\model;

use think\Model;
use think\Db;

use QL\QueryList;

class Sousuo extends Model
{
	public $ql = array();//待处理数据
	public $sy = array();//搜索引擎索引
	public $key = '';//关键字
	public $page = 1;//页码
	public $so_data = array();//搜索的数据
	public $header = array();//搜索头

	public $new_ls = array();//用来判断出现次数
	public $new_array = array();//用来存档数据
	public $new_array2 = array();//用来存档要返回的排序过的数据

    public $Cache;//业务缓存模型
    public $Cache_mr;//是否进行缓存 0或1

/** 初始化模型参数  索引数组 和 搜索词 **/  
	public function Initialize($sy=false,$key=false,$header=false,$page=false,$Cache_mr=false)
	{
		$this->sy = $sy?$sy:array();
		$this->key = $key?$key:'';
		$this->header = $header?$header:array();
		$this->page = $page?$page:1;

		$this->ql = QueryList::getInstance();

		$this->Cache = model('Caching','logic');//缓存模型
		$this->Cache_mr = $Cache_mr?$Cache_mr:dz_cahce;//全局是否缓存开关
	}

/** 搜索开始！**/  
	public function get_sosuo($platform,$cache_time=false){
		if(!$cache_time){
			$cache_time = 30*86400;
		}
		for ($i=0; $i < count($this->sy); $i++) { 
		//debug('qq_set');
			$Cache = $this->Cache_mr;
			$cache_name = $platform.'_'.$this->sy[$i].'_'.$this->key.'_p'.$this->page;
			$ls_cache = $this->Cache->Cache_get($Cache,$cache_name);
			if(!$ls_cache){
			    eval('$this->ql->use(QL\Ext\\'.$platform.'\\'.$this->sy[$i].'::class,"'.$this->sy[$i].'");');//没办法... 不支持动态类调用 只能这样咯
			    eval('$ls = $this->ql->'.$this->sy[$i].'();');
			    $ls->setHttpOpt($this->header);
			    $ls_searcher = $ls->search($this->key);
			    $datas = $ls_searcher->page($this->page,true);
			    if (count($datas) == 0) {$datas = array('l');}
			    $this->Cache->Cache_set($Cache,$cache_name,$datas,$cache_time);//设置缓存 30天
			}else{$datas = $ls_cache;}
			if($datas[0] == 'l'){$datas=array();}

		//debug('qq_end');
		//echo $platform.'_'.$this->sy[$i].'_'.$this->key.'_p'.$this->page.'<br>';
		//echo $this->sy[$i].'-'.count($datas).'条-请求耗时：'.debug('qq_set','qq_end').'s<br>';

		    $this->so_data[$this->sy[$i]] = $datas;
		}
	}

/**  排序算法V1 按照出现的次数排序 **/  
	public function get_sort_V1(){
	for ($i=0; $i < count($this->sy); $i++) { 
	    for ($s=0; $s < count($this->so_data[$this->sy[$i]]); $s++) {
	    	//没有URL就跳出
	    	if($this->so_data[$this->sy[$i]][$s]['link'] == ''){continue;}

	    	$this->so_data[$this->sy[$i]][$s]['ico'] = 1;//ICO图标

	        $ls_url = str_replace(array('https://','http://'), '', $this->so_data[$this->sy[$i]][$s]['link']);//不想区分是否为HTTPS
	        if (isset($this->new_array[$ls_url])) {
	            $this->new_ls['s'][$ls_url] += 1;
	            $this->new_array[$ls_url]['c'] += 1;
	            $this->new_array[$ls_url]['a'][] = array(
	                'name' => $this->sy[$i],
	                'id' => $s
	                );
	            //排名只取最低的
	            if($this->new_ls['t'][$ls_url] > $s){
	            	$this->new_ls['t'][$ls_url] = $s;
	            }
	            //如果是https那就重新赋值
	            if(strpos($this->so_data[$this->sy[$i]][$s]['link'], 'https://') !== false){
	           		 $this->new_array[$ls_url]['link'] = $this->so_data[$this->sy[$i]][$s]['link'];
	            }
	        }else{
	            $this->new_ls['s'][$ls_url] = 1;
	            $this->new_ls['t'][$ls_url] = $s;
	            $this->new_array[$ls_url] = $this->so_data[$this->sy[$i]][$s];
	            $this->new_array[$ls_url]['c'] = 1;
	            $this->new_array[$ls_url]['a'][] = array(
	                'name' => $this->sy[$i],
	                'id' => $s
	                );
	        }
	    }
	}
	if(count($this->new_ls) == 0){
		return array();
	}
	arsort($this->new_ls['s']);//对出现次数进行排序
	asort($this->new_ls['t']);//对排名进行排序（取最低值）
	$array_key = array_keys($this->new_ls['s']);//获取排序后的键值

	for ($i=0; $i < count($array_key); $i++) { 
		if($this->new_ls['s'][$array_key[$i]] == 1){
			unset($this->new_ls['s'][$array_key[$i]]);
		}else{
			unset($this->new_ls['t'][$array_key[$i]]);
		}
	}
	$this->new_ls = array_merge($this->new_ls['s'],$this->new_ls['t']);//合并数组

	//arsort($this->new_ls);//对出现次数进行排序 【不需要排序了】
	$array_key = array_keys($this->new_ls);//获取排序后的键值
	//给要返回的数组赋值啊
	for ($i=0; $i < count($array_key); $i++) { 
	    $this->new_array2[] = $this->new_array[$array_key[$i]];
	}
        return $this->new_array2;
	}

/**  排序算法V2 按照综合排序权重排序 **/  
	public function get_sort_V2(){

	for ($i=0; $i < count($this->sy); $i++) { 
	    for ($s=0; $s < count($this->so_data[$this->sy[$i]]); $s++) {

	        $ls_url = str_replace(array('https://','http://'), '', $this->so_data[$this->sy[$i]][$s]['link']);//不想区分是否为HTTPS
	        if (isset($this->new_array[$ls_url])) {
	            $this->new_array[$ls_url]['c'] += 1;
	            $this->new_array[$ls_url]['s'] += $s;
	            $this->new_array[$ls_url]['a'][] = array(
	                'name' => $this->sy[$i],
	                'id' => $s
	                );
	            $this->new_ls[$ls_url] = $s/$this->new_array[$ls_url]['c'];
	            //如果是https那就重新赋值
	            if(strpos($this->so_data[$this->sy[$i]][$s]['link'], 'https://') !== false){
	           		 $this->new_array[$ls_url]['link'] = $this->so_data[$this->sy[$i]][$s]['link'];
	            }
	        }else{
	            $this->new_array[$ls_url] = $this->so_data[$this->sy[$i]][$s];
	            $this->new_array[$ls_url]['c'] = 1;
	            $this->new_array[$ls_url]['s'] = $s;
	            $this->new_array[$ls_url]['a'][] = array(
	                'name' => $this->sy[$i],
	                'id' => $s
	                );
	            $this->new_ls[$ls_url] = $s/1;
	        }
	    }
	}
	asort($this->new_ls);//对出现次数进行排序
	$array_key = array_keys($this->new_ls);//获取排序后的键值
	//给要返回的数组赋值啊
	for ($i=0; $i < count($array_key); $i++) { 
	    $this->new_array2[] = $this->new_array[$array_key[$i]];
	}

        return $this->new_array2;
	}
}


?>