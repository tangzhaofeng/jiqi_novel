<?php
/*
    *推荐位/TAG处理类
	[Cms News] (C) 2009-2012 Cms Inc.
	$Id: position.class.php 12398 2010-07-07 18:36:38Z huliming $
*/
if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class Position extends GlobalData{

	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function Position($position = array()){
         if(!$position){
			 $this->GlobalData('position', 'posid','listorder');
		 }else {
		     $this->data = $position;
		 }
	}

	/**
	 * 检查一条数据是否存在
	 * 
	 * @access     public
	 * @return     bool
	 */
	function checkdata($posid, $isreture = false){
	    //判断数据是否存在
	    $cachefile = _ROOT_.'/configs/'.$this->module."/data_position_{$posid}_field.php";
		if(!is_file($cachefile)) {
		    if(!$this->cacheOne($posid)){
			    if(!$isreture)  jieqi_printfail(lang_replace('data_not_exists'));
				return false;
			}
		}
	}
	
	/**
	 * 获取一个标签
	 * 
	 * @access     public
	 * @return     array
	 */
	function get($posid, $isreture = false){
	    global $_SGLOBAL,$positionSetting;
	    $cachefile = _ROOT_.'/configs/'.$this->module."/data_position_{$posid}_field.php";
		if(!is_file($cachefile)) {
		    if(!$this->cacheOne($posid)){
			    if(!$isreture)  jieqi_printfail(lang_replace('data_not_exists'));
				return false;
			}
		}
		include_once($cachefile);
		$data = $_SGLOBAL['position_'.$posid.'_field'][$posid];
		if($data['setting'] && !$positionSetting[$posid]){
			eval('$positionSetting['.$posid.'] = '.$data['setting'].';');
			$data['setting'] = $positionSetting[$posid];
		}else{
		    $data['setting'] = $positionSetting[$posid];
		}
		return $data;
	}
			
	/**
	 * 获取一个标签
	 * 
	 * @access     public
	 * @return     array
	 */
	function getOne($posid, $isreture = false){
	    //判断栏目是否存在
	    global $positionSetting;//不必重复解析
		
		//从数据库获取
		$where = " where posid = ".$posid;
		$sql = 'select * from '.jieqi_dbprefix("{$this->table}")." {$where}";
		$data = selectsql($sql);
		if(!$data){
			if(!$isreture) jieqi_printfail(lang_replace('data_not_exists'));
			else return false;
		}
		$data = $data[0];
		if($data['setting'] && !$positionSetting[$posid]){
			eval('$positionSetting['.$posid.'] = '.$data['setting'].';');
			$data['setting'] = $positionSetting[$posid];
		}else{
		    $data['setting'] = $positionSetting[$posid];
		}
        //返回栏目内容
		return $data;
	}	
	
	/**
	 * 删除一条数据
	 * 
	 * @access     public
	 * @return     bool
	 */
	function delete($posid, $cache = true){
	    //判断模型是否存在
		$this->checkdata($posid);
		if(deletesql($this->table, array("{$this->idfield}"=>"{$posid}"))){
		    jieqi_delfile(_ROOT_.'/configs/'.$this->module."/data_position_{$posid}_field.php");
		    $this->cache();
			return true;
		}else{
		    return false;
		}
	}
	
	/**
	 * 增删改推荐位
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function updatePosid($contentid, $value, $ac = 'add'){
		if(!is_array($value) || $ac == 'delete'){//删除推荐位
		    if($ac == 'add') return true;
		    $temparr = array();
			$ids = array();
			foreach($this->data as $k=>$v){
			    if($v['data']){
					$temparr = explode(',', $v['data']);
					if(in_array($contentid, $temparr)){
						foreach($temparr as $t=>$tv){
							if($contentid == $tv){
							   unset($temparr[$t]);
							}
						}
						$this->edit($k, array('data' => implode(',', $temparr)) );
						$ids[] = $k;
					}
				}
			}
		}elseif($ac == 'add'){//添加
			foreach($value as $k=>$v){
			    $pos = $this->get($v);
				if($pos['data']){
					if( !in_array($contentid, explode(',', $pos['data'])) ){
						$this->edit($v, array('data' => $contentid.','.$pos['data']) );
						$ids[] = $v;
					}
				}else{
					$this->edit($v, array('data' => $contentid) );
					$ids[] = $v;
				}
			}
		}else{//修改
		    $temparr = array();
			$oldarr = array();
			foreach($this->data as $k=>$v){
			    //if(!$v['data']) continue;
			    $temparr = explode(',', $v['data']);
				if(in_array($contentid, $temparr)){//顺序检索修改前存在的推荐位
				   $oldarr[$k] = $temparr;
				}else{//添加
				    if(in_array($k, $value)){
						if($v['data']){
							if( !in_array($contentid, explode(',', $v['data'])) ){
								$this->edit($k, array('data' => $contentid.','.$v['data']) );
								$ids[] = $k;
							}
						}else{
							$this->edit($k, array('data' => $contentid) );
							$ids[] = $k;
						}
					}
				}
			}
			if($oldarr){//修改前存在的推荐位
			    foreach($oldarr as $j=>$jv){
				   if(!in_array($j, $value)){//删除未选中
					   foreach($jv as $t=>$tv){
						  if($contentid == $tv){
							 unset($jv[$t]);
						  }
					   }
					   $this->edit($j, array('data' => implode(',', $jv)) );
					   $ids[] = $j;
				   }
				}
			}
		}
		if($ids){
		    $ids = array_values(array_unique($ids));
			foreach($ids as $k=>$id){
			    $this->cacheOne($id);
			}
			//更新缓存
			$this->cache();
		}
	 }
	 	
	/**
	 * 重新排序
	 * 
	 * @access     public
	 * @return     bool
	 */	
	function order($order){
	      if(!is_array($order)) return false;
		  foreach($order as $id=>$value){
		      $value = intval($value);
			  updatetable($this->table, array('listorder'=>$value), "{$this->idfield}={$id}");
			  $this->cacheOne($id);//更新单个缓存
		  }
		  $this->cache();//更新缓存
		  return true;
	}
	
	/**
	 * 列表一条缓存
	 * 
	 * @access     public
	 * @return     empty
	 */
	 function cacheOne($posid){
	     if($data  = selectsql('select * from '.jieqi_dbprefix($this->table)." WHERE posid={$posid}")){
		     $file = _ROOT_.'/configs/'.$this->module."/data_position_{$posid}_field.php";
			 cache_write("position_{$posid}_field", "_SGLOBAL['position_{$posid}_field']", $data, $this->idfield, $file);
			 return true;
		 }else return false;
	 }
	 	
	/**
	 * 推荐列表更新缓存
	 * 
	 * @access     public
	 * @return     empty
	 */
	function cache(){
		global $_SGLOBAL;
		$table = str_replace($this->tablepre, '', $this->table);
		$_SGLOBAL[$table] = array();
		//从数据库获取
		if($this->order) $where = " where type=0 order by ".$this->order." ASC";
		else $where = ' where type=0';
		$data = selectsql('select posid,name,data,type,listorder from '.jieqi_dbprefix("{$this->table}")." {$where}");
		cache_write($table, "_SGLOBAL['".$table."']", $data, $this->idfield, $this->cachefile);
		include($this->cachefile);
	}
}
?>