<?php
/*
    *文章采集类
	[Cms News] (C) 2009-2012 Cms Inc.
	$Id: collect.class.php 12398 2010-08-02 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class Collect extends GlobalData{

	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function Collect($collect = array()){
         if(!$collect){
			 $this->GlobalData('collect', 'collectid','listorder');
		 }else {
		     $this->data = $collect;
		 }
	}

	/**
	 * 检查一条数据是否存在
	 * 
	 * @access     public
	 * @return     bool
	 */
	function checkdata($collectid, $isreture = false){
	    //判断数据是否存在
	    $cachefile = _ROOT_.'/configs/'.$this->module."/data_collect_{$collectid}_field.php";
		if(!is_file($cachefile)) {
		    if(!$this->cacheOne($collectid)){
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
	function get($collectid, $isreture = false){
	    global $_SGLOBAL,$collectSetting,$collectTask;//不必重复解析;
	    $cachefile = _ROOT_.'/configs/'.$this->module."/data_collect_{$collectid}_field.php";
		if(!is_file($cachefile)) {
		    if(!$this->cacheOne($collectid)){
			    if(!$isreture)  jieqi_printfail(lang_replace('data_not_exists'));
				return false;
			}
		}
		include_once($cachefile);
		$data = $_SGLOBAL['collect_'.$collectid.'_field'][$collectid];
		if($data['setting'] && !$collectSetting[$collectid]){
			eval('$collectSetting['.$collectid.'] = '.$data['setting'].';');
			$data['setting'] = $collectSetting[$collectid];
		}else{
		    $data['setting'] = $collectSetting[$collectid];
		}
		if($data['task'] && !$collectTask[$collectid]){
			eval('$collectTask['.$collectid.'] = '.$data['task'].';');
			$data['task'] = $collectTask[$collectid];
		}else{
		    $data['task'] = $collectTask[$collectid];
		}
		return $data;
	}

	/**
	 * 获取一个标签
	 * 
	 * @access     public
	 * @return     array
	 */
	function getOne($collectid, $isreture = false){
	    //判断栏目是否存在
	    global $collectSetting,$collectTask;//不必重复解析
		
		//从数据库获取
		$where = " where collectid = ".$collectid;
		$data = selectsql('select * from '.jieqi_dbprefix("{$this->table}")." {$where}");
		if(!$data){
			if(!$isreture) jieqi_printfail(lang_replace('data_not_exists'));
			else return false;
		}
		$data = $data[0];
		if($data['setting'] && !$collectSetting[$collectid]){
			eval('$collectSetting['.$collectid.'] = '.$data['setting'].';');
			$data['setting'] = $collectSetting[$collectid];
		}else{
		    $data['setting'] = $collectSetting[$collectid];
		}
		if($data['task'] && !$collectTask[$collectid]){
			eval('$collectTask['.$collectid.'] = '.$data['task'].';');
			$data['task'] = $collectTask[$collectid];
		}else{
		    $data['task'] = $collectTask[$collectid];
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
	function delete($collectid, $cache = true){
	    //判断模型是否存在
		$this->checkdata($collectid);
		if(deletesql($this->table, array("{$this->idfield}"=>"{$collectid}"))){
		    jieqi_delfile(_ROOT_.'/configs/'.$this->module."/data_collect_{$collectid}_field.php");
		    $this->cache();
			return true;
		}else{
		    return false;
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
	 function cacheOne($collectid){
	     if($data  = selectsql('select * from '.jieqi_dbprefix($this->table)." WHERE collectid={$collectid}")){
		     $file = _ROOT_.'/configs/'.$this->module."/data_collect_{$collectid}_field.php";
			 cache_write("collect_{$collectid}_field", "_SGLOBAL['collect_{$collectid}_field']", $data, $this->idfield, $file);
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
		if($this->order) $where = " order by ".$this->order." ASC";
		$data = selectsql('select collectid,modelid,sitename,siteurl,description,inputtime,updatetime,disabled,listorder from '.jieqi_dbprefix("{$this->table}")." {$where}");
		cache_write($table, "_SGLOBAL['".$table."']", $data, $this->idfield, $this->cachefile);
		include($this->cachefile);
	}
}

class CollectPage extends JieqiObject{
    
	var $collect = array();   //网址采集规则
	var $urlpram = array();   //请求的URL参数
	var $task = array();      //采集任务
	var $index = '';         //采集任务索引
	var $source;              //远程页面内容
	/**
	 * 构造函数
	 * 
	 * @param      taskcollect   
	 * @param      filedcollect     
	 * @access     private
	 * @return     void
	 */
	function CollectPage($collect, $index=''){
        global $_SGLOBAL;
		include_once(_ROOT_.'/lib/text/textfunction.php');
		include_once($_SGLOBAL['news']['path'].'/include/collectfunction.php');
		$this->collect = $collect;
		//构造参数
		$colary=array(
					'repeat'=>$collect['setting']['senior']['threadrequest'],
					'referer'=>$collect['setting']['senior']['referer'],
					'wget'=>$collect['setting']['senior']['wget'], 
					'proxy_host'=>$collect['setting']['senior']['proxy_host'], 
					'proxy_port'=>$collect['setting']['senior']['proxy_port'], 
					'proxy_user'=>$collect['setting']['senior']['proxy_user'], 
					'proxy_pass'=>$collect['setting']['senior']['proxy_pass']
				);
		if(!empty($collect['setting']['senior']['pagecharset'])) $colary['charset']=$collect['setting']['senior']['pagecharset'];
		$this->urlpram = $colary;
		$this->index = $index;
		$this->setTask($index);
		if($index=='') $this->fields = $this->collect['setting']['fields'];
		else{
			if($this->task['fields']) $this->fields = $this->task['fields'];
			else $this->fields = $this->collect['setting']['fields'];
		}		
	}
	
	/**
	 * 获取URL内容
	 * 
	 * @param      url   
	 * @access     public
	 * @return     str
	 */
	 function getSource($url, $isreturn = false){
	     if(!$url) return false;
		 $this->source = jieqi_urlcontents($url,$this->urlpram);
		 if(empty($this->source)){
		    if($isreturn) return false;
			else jieqi_printfail(lang_replace('collect_url_failure',array($url,$url)));
		 }
		 return $this->source;
	 }
	 
	/**
	 * 将采集代码转换成可执行的正则
	 * 
	 * @param      str   
	 * @access     public
	 * @return     str
	 */
	 function collectstoe($str){
	     if(!$str) return false;
		 return jieqi_collectstoe(jieqi_collectptos($str));
	 }
	 
	/**
	 * 匹配一个结果
	 * 
	 * @param      str   
	 * @access     public
	 * @return     array
	 */
	 function cmatchone($pregstr, $source){
	     return jieqi_cmatchone($pregstr, $source);
	 }
	 
	/**
	 * 匹配多个结果
	 * 
	 * @param      str   
	 * @access     public
	 * @return     array
	 */
	 function cmatchall($pregstr, $source){
	     return jieqi_cmatchall($pregstr, $source);
	 }
	 
	/**
	 * 过滤内容
	 * 
	 * @param      string   
	 * @access     public
	 * @return     string
	 */
	 function filtercontent($content, $filter){
	     if(!$filter) return $content;
		 $filterary=explode("\n", $filter);
		 $repfrom = $repto = array();   //存放查找/替换规则的容器
		 foreach($filterary as $filterstr){
		     $filterstr=trim($filterstr);
			 if(!empty($filterstr)){
			     $filters = explode('|', $filterstr);
			     if(preg_match('/^\/[^\/\\\\]*(?:\\\\.[^\/\\\\]*)*\/[imsu]*$/is', $filters[0])) $repfrom[]=$filters[0];
				 else $repfrom[] = '/'.jieqi_pregconvert($filters[0]).'/is';
				 $repto[] = str_replace("\r\n","\n",str_replace("\s"," ",$filters[1]));
			 }
		 }
		 if(count($repfrom) > 0) $content = preg_replace($repfrom, $repto, $content);
		 return $content;
	 }
	  
	/**
	 * 设置采集任务
	 * 
	 * @param      int   
	 * @access     public
	 * @return     array
	 */
	 function setTask($index){
	     if(!isset($index)) return false;
		 if(!array_key_exists($index, $this->collect['task'])) jieqi_printfail(LANG_ERROR_PARAMETER); 
		 $this->task = $this->collect['task'][$index];
	 }
	 
	/**
	 * 获取任务采集文章的URL列表
	 * 
	 * @param      int   
	 * @access     public
	 * @return     array
	 */
	 function getArticleUrls($urls = '', $isreturn = true){
	     //if(!isset($index) || !$urls) return false;
		 if(!$urls) return false;
		 //$this->setTask($index);
		 //if(strpos($startpageid, 'http://')) $this->task['urlpage'] = $startpageid;
		 //else $this->task['urlpage'] = str_replace('<{pageid}>', $startpageid, $this->task['urlpage']);
		 $this->task['urlpage'] = $urls;
		 if(!($source = $this->getSource($this->task['urlpage'], $isreturn))) return false;
		 //判断是文章编号还是文章URL
		 $urls = array();
		 if(strpos($this->task['urlarticle'], '<{articleid}>')){
		     $pregstr = $this->collectstoe($this->task['articleid']);
			 $matchvar = $this->cmatchall($pregstr, $source);
			 if(empty($matchvar)) return false;
			 foreach($matchvar as $k=>$key){
			     if($key=='') continue;
			     $urls[$key] = str_replace('<{articleid}>', $key, $this->task['urlarticle']);
			 }
		 }elseif(strpos($this->task['urlarticle'], '<{articleurl}>')){
		     $pregstr = $this->collectstoe($this->task['articleurl']);
			 $matchvar = $this->cmatchall($pregstr, $source);
			 if(empty($matchvar)) return false;
			 foreach($matchvar as $k=>$key){
			     if($key=='') continue;
			     $urls[$key] = str_replace('<{articleurl}>', $key, $this->task['urlarticle']);
			 }
		 }else return false;
		 return $urls;
	 }
	 
	/**
	 * 获取字段列表
	 * 
	 * @param      str   
	 * @access     public
	 * @return     array
	 */
	 function getFields($url, $fields = ''){
	      if(!$fields){
/*			  if($index=='') $fields = $this->collect['setting']['fields'];
			  else{
				  $this->setTask($index);
				  if($this->task['fields']) $fields = $this->task['fields'];
				  else $fields = $this->collect['setting']['fields'];
			  }*/
			  $fields = $this->fields;
			  if(!$fields) return false;
		  }
		  if(!($source = $this->getSource($url, true))) return false;
		  $temp = array();
		  foreach($fields as $field => $v){
		      if(!$v['adopt']) continue;
			  $pregstr = $this->collectstoe($v['adopt']);
			  $matchvar = $this->cmatchone($pregstr, $source);
			  if(empty($matchvar)) continue;
			  if($v['filter']){ //如果设置了替换规则
			      //$filterstr = $this->collectstoe($v['filter']);
				  $matchvar = $this->filtercontent($matchvar, $v['filter']);
			  }
			  //内容中的图片相对路径改成绝对路径
			  $temp[$field] = relative_to_absolute($matchvar, $url);
			  if($v['nextpage']){//如果设定了下一页采集
			      $nfields[$field] = $fields[$field];
			      //$nfields[$field]['filter'] = $v['nextpage'];
				  $nextpage = relative_to_absolute($this->cmatchone($this->collectstoe($v['nextpage']), $source), $url, true);
				  if($nextpage && $nextpage!=$url){
				      $t = $this->getFields($nextpage, $index, $nfields);
					  if($t[$field]){
						  $temp[$field] = $temp[$field]."[page]\n".$t[$field];
					  }
				  }
			  }
			  //如果是图片组图
/*			  if($field=='pictureurls'){
				  preg_match_all("/((https?|ftp|http):\/\/)([^\s\r\n\t\f<>]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp))/i", $temp[$field], $matches);
				  $temp[$field] = $matches[0];
				  //print_r($matches[0]);exit;
			  }*/
			  
		  }
		  return $temp;
	 }
}
?>