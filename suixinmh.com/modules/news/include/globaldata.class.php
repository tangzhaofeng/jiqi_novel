<?php
/*
    *通用数据处理类[通用的缓存数据操作]
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: globaldata.class.php 12398 2010-05-25 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class GlobalData extends JieqiObject{
    var $module = "news";
    var $tablepre = "news_";
	var $table;  //表名对象
	var $idfield;//表主键字段
	var $order = 'listorder'; //排序字段
	var $data = array();  //数据容器
	var $cachefile;//缓存文件
	var $handler;
	var $criteria;
	var $jumppage;
	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function GlobalData($table, $idfield, $order = '', $module = '', $data = array()){
	     global $_SGLOBAL;
		 //if(!$this->module) $this->module = _MODULE_;
		 if($module) $this->module = $module;
		 elseif(!$this->module) $this->module = _MODULE_;
		 $this->tablepre = $this->module."_";
		 $this->table = $this->tablepre.$table;
		 $this->idfield = $idfield;
		 $this->order = $order;
         if(!$data){
			 if(!$this->cachefile){
			     if($this->module!='system') $this->cachefile = _ROOT_.'/configs/'.$this->module.'/data_'.$table.'.php';
				 else $this->cachefile = _ROOT_.'/configs/data_'.$table.'.php';
			 }
			 if(is_file($this->cachefile)){
				 include_once($this->cachefile);
			 }else{
			     $this->cache();
			 }
		     $this->data = $_SGLOBAL[$table];
		 }else {
		     $this->data = $data;
		 }
	}

	/**
	 * 检查一条数据是否存在
	 * 
	 * @access     public
	 * @return     bool
	 */
	function checkdata($id, $isreture = false){
	    //判断数据是否存在
	    if(!array_key_exists($id, $this->data)){
			if(!$isreture) jieqi_printfail(lang_replace('data_not_exists'));
			else return false;
		}else{
			return true;
		}
	}
		
	/**
	 * 获取一条数据实例
	 * 
	 * @access     public
	 * @return     empty
	 */	
	function get($id, $isreture = false)
	{
	    //判断数据是否存在
		$this->checkdata($id, $isreture);
		return $this->data[$id];
	}
	
	/**
	 * 增加一条数据
	 * 
	 * @access     public
	 * @return     int
	 */
	function add($data, $cache = true, $ishtml = true){
		if(!is_array($data)) return false;
		//不允许HTML
		if(!$ishtml) $data = shtmlspecialchars($data);
		
		if($id = inserttable($this->table, $data, true)){
		    if($cache) $this->cache();
			return $id;
		}else{
		    return false;
		}
	}	
	
	/**
	 * 删除一条数据
	 * 
	 * @access     public
	 * @return     bool
	 */
	function delete($id, $cache = true){
		if(deletesql($this->table, array("{$this->idfield}"=>"'{$id}'"))){
		    if($cache) $this->cache();
			return true;
		}else{
		    return false;
		}
	}
		
	/**
	 * 修改
	 * 
	 * @access     public
	 * @return     bool
	 */
	function edit($id, $data, $cache = true, $ishtml = true){
		//判断模型是否存在
		//$this->checkdata($id);
		//不允许HTML
		if(!$ishtml) $data = shtmlspecialchars($data);
		if(updatetable($this->table, $data,"{$this->idfield}='{$id}'")){
		    if($cache) $this->cache();//更新缓存
		    return true;
		}else {
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
			  updatetable($this->table, array($this->order=>$value), "{$this->idfield}='{$id}'");
		  }
		  $this->cache();//更新缓存
		  return true;
	}
		
	/**
	 * 初始化查询对象
	 * 
	 * @access     public
	 * @return     empty
	 */	
/*	function setHandler(){
	    global $_SGLOBAL;
		dbconnect();
		$this->handler = $_SGLOBAL['db'];
		//if(is_object($obj))  
		if(!is_object($this->criteria)) $this->criteria=new CriteriaCompo($obj);
		$this->criteria->setTables(jieqi_dbprefix($this->table));
	}*/
	function setHandler($tag = ''){
	    global $_SGLOBAL;
		//链接数据库
		dbconnect();
		if(!$tag){
			$this->handler = $_SGLOBAL['db'];
			if(!is_object($this->criteria)) $this->criteria=new CriteriaCompo();
			$this->criteria->setTables(jieqi_dbprefix($this->table));
		}else{
			if(!is_object($this->handler)) $this->handler =& JieqiQueryHandler::getInstance('JieqiQueryHandler');
			if(!is_object($this->criteria)) $this->criteria=new CriteriaCompo();
		}
	}
	 
	/**
	 * 按给定字段条件检查文章是否存在
	 * 
	 * @access     public
	 * @return     empty
	 */	
	 function checkFields($data,$table = ''){
	      global $_SGLOBAL;
		  if(!is_array($data)) return FALSE;
		  $sql = $comma = $co = '';
		  foreach ($data as $key => $value) {
			  $sql .= $comma.'`'.$key.'`';
			  $sql .= '=\''.$value.'\'';
			  $comma = ' AND ';
		  }
		  if($table) $co = " as a left join `".jieqi_dbprefix($table)."` as b on a.contentid=b.contentid ";
	      $info = selectsql("SELECT * FROM `".jieqi_dbprefix($this->table)."` {$co} WHERE {$sql}");
		  if($info) return count($info);
		  else return FALSE;
	 }
	 	
	/**
	 * 重置查询对象
	 * 
	 * @access     public
	 * @return     empty
	 */	
	function resetHandler(){
	    global $_SGLOBAL;
		if(!is_object($this->handler)){
			dbconnect();
			$this->handler = $_SGLOBAL['db'];
		}
		$this->criteria=new CriteriaCompo();
		$this->criteria->setTables(jieqi_dbprefix($this->table));
	}
		 
	/**
	 * 查询记录列表
	 * 
	 * @access     public
	 * @return     array
	 */
	 function lists($pagenum = 0, $page = 0, $custompage = '', $emptyonepage = false){
		if($pagenum){
			$this->criteria->setLimit($pagenum);
			if(!$page) $page = 1;
			//else $currentpage = $page;
			$this->criteria->setStart(($page-1) * $pagenum);
		}
		$this->handler->queryObjects($this->criteria);
		$rows = array();
		$k=0;
		while($v = $this->handler->getObject()){
			$ret = array();
			foreach($v->vars as $i=>$j){
				$ret[$i]=$v->getVar($i,'n');
			}
			$rows[$k] = $ret;
			$k++;
		}
		if($page){
			 $this->setVar('totalcount', $this->handler->getCount($this->criteria));
			 if(!$custompage){
				 //处理页面跳转
				 include_once(_ROOT_.'/lib/html/page.php');
				 $this->jumppage = new JieqiPage($this->getVar('totalcount'),$pagenum,$page);
			 }else{
			     $this->jumppage = new GlobalPage($custompage,$this->getVar('totalcount'),$pagenum,$page);
				 $this->jumppage->emptyonepage = $emptyonepage;
				 if($custompage) $this->setVar('custompage', $custompage);
			 }
		}else{
			 $this->setVar('totalcount', count($rows));
		}
		return $rows;
	 }
	
	/**
	 * 获取分页
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getPage($setlink = ''){
	     if(!$this->getVar('custompage')){
			 if($setlink) $this->jumppage->setlink($setlink, false, true);
			 return $this->jumppage->whole_bar();
		 } else return $this->jumppage->getPage($setlink);
	 }
	
	/**
	 * 更新缓存
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
		else $where = '';
		$data = selectsql('select * from '.jieqi_dbprefix("{$this->table}")." {$where}");
		if($data) cache_write($table, "_SGLOBAL['".$table."']", $data, $this->idfield, $this->cachefile);
		include($this->cachefile);
	}
}

//自定义分页
class GlobalPage extends JieqiObject{
    var $pagestr; //自定义分页代码
	var $linkhead;    //连接地址
	var $pagevar;  //页码变量名
	var $emptyonepage = false; //第一页的路径
/*  var $page; //当前页
	var $firstpage; //第一页标记
	var $prepage; //上一页标记
	var $nextpage; //下一页标记
	var $lastpage; //最后一页标记
	var $numpage; //下一页标记*/
	
	function GlobalPage($pagestr, $totalcount, $pagesize, $page = 1, $pagevar = 'page', $pageajax = 0){
	    $this->pagestr =& $pagestr;
		if(!$this->pagestr) return false;
		$this->setVar('totalcount', $totalcount);
        $this->setVar('pagesize', $pagesize);
		$this->setVar('page', $page);
		$totalpage = @ceil($totalcount/$pagesize);
		if($totalpage <= 1) $totalpage=1;
		$this->setVar('totalpage', $totalpage);
		$this->pagevar = $pagevar;
		if($pageajax > 0 || (defined('JIEQI_AJAX_PAGE') && JIEQI_AJAX_PAGE > 0)) $this->useajax=1;
        else $this->useajax=0;
	}

	function setlink($link='', $addget=true, $addpost=false){
		if(!empty($link)){
			$this->linkhead = $link;
		}else{
			$this->linkhead = jieqi_addurlvars(array($this->pagevar => ''), $addget, $addpost);
			$this->linkempty = true;
		}
	}
	
	function pageurl($page){
	    $linkhead = $this->linkhead;
	    if($page<2){
		   if($this->emptyonepage===true) $linkhead = str_replace(basename($linkhead), '', $linkhead);
		   elseif($this->emptyonepage!==false) $linkhead = str_replace(basename($linkhead), '', $linkhead).$this->emptyonepage;
		}
		if(strpos($linkhead, '<{$page') === false && $this->linkempty) $url = $linkhead.$page;
		else $url = str_replace(array('<{$page|subdirectory}>', '<{$page}>'), array(jieqi_getsubdir($page), $page), $linkhead);
		if($this->useajax == 1) $url = 'javascript:Ajax.Update(\''.urldecode($url).'\','.$this->ajax_parm.');';
		return $url;
	}
	
	function pagelink($page){
	    if($page==1 && $this->getVar('firstpage')) $link = $this->getVar('firstpage');
	    else $link = $this->pageurl($page);
        return $link;
	}
	
	//第一页
	function firstpage(){
	    if($this->getVar('page')<2){
			 if($firststr = exechars('[firstpage]****[/firstpage]',$this->pagestr)){
				 $this->pagestr = str_replace("[firstpage]{$firststr}[/firstpage]", '', $this->pagestr);
			 } else {
			     $ret = $this->pagelink(1);
			 }
		}else{
			 if($firststr = exechars('[firstpage]****[/firstpage]',$this->pagestr)){
			     $this->pagestr = str_replace("[firstpage]{$firststr}[/firstpage]", $firststr, $this->pagestr);
			 }
			 $ret = $this->pagelink(1);
		}
		return $ret;
	}
	
	//上一页
	function prepage(){
	    $ret = '';
        if($this->getVar('page')<2){
			 if($prestr = exechars('[prepage]****[/prepage]',$this->pagestr)){
				 $this->pagestr = str_replace("[prepage]{$prestr}[/prepage]", '', $this->pagestr);
			 } else {
			     $ret = $this->pagelink(1);
			 }
		}else{
			 if($prestr = exechars('[prepage]****[/prepage]',$this->pagestr)){
			     $this->pagestr = str_replace("[prepage]{$prestr}[/prepage]", $prestr, $this->pagestr);
			 }
			 $ret = $this->pagelink($this->getVar('page')-1);
		}
		return $ret;
	}
	
	//下一页
	function nextpage(){
	    $ret = '';
		if($this->getVar('page')<$this->getVar('totalpage')){
			if($nextstr = exechars('[nextpage]****[/nextpage]',$this->pagestr)){
			    $this->pagestr = str_replace("[nextpage]{$nextstr}[/nextpage]", $nextstr, $this->pagestr);
			}
			$ret = $this->pagelink($this->getVar('page')+1);
		}else{
			if($nextstr = exechars('[nextpage]****[/nextpage]',$this->pagestr)){
				$this->pagestr = str_replace("[nextpage]{$nextstr}[/nextpage]", '', $this->pagestr);
			} else {
				$ret = $this->pagelink($this->getVar('totalpage'));
		    }
		}
		return $ret;
	}
	
	//最后一页
	function lastpage(){
	    if($this->getVar('page')>=$this->getVar('totalpage')){
			 if($laststr = exechars('[lastpage]****[/lastpage]',$this->pagestr)){
				 $this->pagestr = str_replace("[lastpage]{$laststr}[/lastpage]", '', $this->pagestr);
			 } else {
			     $ret = $this->pagelink($this->getVar('totalpage'));
			 }
		}else{
			 if($laststr = exechars('[lastpage]****[/lastpage]',$this->pagestr)){
			     $this->pagestr = str_replace("[lastpage]{$laststr}[/lastpage]", $laststr, $this->pagestr);
			 }
			 $ret = $this->pagelink($this->getVar('totalpage'));
		}
		return $ret;
	}
	
	//数字分页
	function pages(){
	    $page  = $this->getVar('page');
	    if($this->getVar('totalpage')>1){//如果总页数大于1
			if($pages = exechars('[pages]****[/pages]',$this->pagestr)){
			    $this->pagestr = str_replace("[pages]{$pages}[/pages]", $pages, $this->pagestr);
			    //设置显示多少分页数字
				if(!$pnum = exechars('[pnum]$$$$[/pnum]',$pages)) $pnum = 5;
				else $this->pagestr = str_replace("[pnum]{$pnum}[/pnum]", '', $this->pagestr);
				//设置当前链接的风格
				if(!$pnumchar = exechars('[pnumchar]****[/pnumchar]',$pages)) $pnumchar = "<strong>{$this->getVar('page')}</strong>";
				else{
				    $this->pagestr = str_replace("[pnumchar]{$pnumchar}[/pnumchar]", '', $this->pagestr);
				    eval('$pnumchar = "'.saddslashes($pnumchar).'";');
				}
				//设置数字链接的风格
				if(!$pnumurlchar = exechars('[pnumurl]****[/pnumurl]',$pages)) $pnumurlchar = "<A href='{$pnumurl}'>[{$pagenum}]</A>";
				else $this->pagestr = str_replace("[pnumurl]{$pnumurlchar}[/pnumurl]", '', $this->pagestr);
				
				$num       = $pnum;
				$mid       =  floor($num/2);
				$last      =  $num - 1; 
				//$page      =& $this->getVar('page');
				$totalpage =& $this->getVar('totalpage');
				$linkhead  =& $this->linkhead;       
				$minpage   =  ($page-$mid)<1 ? 1 : $page-$mid;
				$maxpage   =  $minpage + $last;
				if ($maxpage>$totalpage){
					$maxpage =& $totalpage;
					$minpage =  $maxpage - $last;
					$minpage =  $minpage<1 ? 1 : $minpage;
				}
				$linkbar='';
				for ($i=$minpage; $i<=$maxpage; $i++){
					$char = $i;
					if ($i==$page){
						$linkchar = $pnumchar;
					}else{
						$pnumurl = $this->pagelink($i);
						$pagenum = $i;
						eval('$linkchar = "'.saddslashes($pnumurlchar).'";');
					}
					$linkbar .= $linkchar;
				}
			}
		}else{
		    if($pages = exechars('[pages]****[/pages]',$this->pagestr)){//过滤数字分页自定义代码
				$this->pagestr = str_replace("[pages]{$pages}[/pages]", '', $this->pagestr);
			}
		}
		return $linkbar;
	}
	
	function getPage($link=''){
	    if($link || !$this->linkhead) $this->setlink($link);
		//第一页
		if(strpos($this->pagestr, '$firstpage')){
			if($firstpage = $this->firstpage()) $this->setVar('firstpage', $firstpage);
		}
				
		//上一页
		if(strpos($this->pagestr, '$prepage')){
			if($prepage = $this->prepage()) $this->setVar('prepage', $prepage);
		}	
		
		//下一页
		if(strpos($this->pagestr, '$nextpage')){
			if($nextpage = $this->nextpage()) $this->setVar('nextpage', $nextpage);
		}   
		
		//最后一页
		if(strpos($this->pagestr, '$lastpage')){
			if($lastpage = $this->lastpage()) $this->setVar('lastpage', $lastpage);
		}
		
	    //数字分页
		if(strpos($this->pagestr, '$pages')){
		    if($pages = $this->pages()) $this->setVar('pages', $pages);
		}
		
		if($vars = $this->getVars()) extract($vars);
		eval('$this->pagestr = "'.saddslashes($this->pagestr).'";');
		return $this->pagestr;
	}
	
}
?>