<?php
/*
    *通用文章处理类
	[Cms News] (C) 2010-2012 Cms Inc.
	$Id: content.class.php 12398 2010-06-03 15:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class Content extends View{
    var $tablepre = 'news_';
	var $table = 'content';  //表名对象
	var $idfield = 'contentid';//表主键字段
	var $table_count; //点击统计表名/附属表
	var $table_digg; //DIGG表名/附属表
	var $table_digglog; //DIGG日志表名/附属表
	var $table_mood; //MOOD表名/附属表
	var $datadir;
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
	function Content(){
		 $this->table = $this->tablepre.$this->table;
		 $this->table_count = $this->tablepre.'content_count';
		 $this->table_digg = $this->tablepre.'digg';
		 $this->table_digglog = $this->tablepre.'digg_log';
		 $this->table_mood = $this->tablepre.'mood_data';
		 $this->datadir = CACHE_PATH."/modules/"._MODULE_.'/info/';
	}
	
	/**
	 * 初始化查询对象
	 * 
	 * @access     public
	 * @return     empty
	 */	
	function setHandler($tag = ''){
	    global $_SGLOBAL;
		if(!$tag){
			include_once($_SGLOBAL['news']['path'].'/class/content.php');
			if(!is_object($this->handler)) $this->handler =& JieqiContentHandler::getInstance('JieqiContentHandler');
		}else{
			//链接数据库
			dbconnect();
			if(!is_object($this->handler)) $this->handler =& JieqiQueryHandler::getInstance('JieqiQueryHandler');
		}
		if(!is_object($this->criteria)) $this->criteria=new CriteriaCompo();
	}
	
	/**
	 * 获取一条数据实例
	 * 
	 * @access     public
	 * @return     empty
	 */	
	function get($contentid, $readall = true)
	{
	    global $_SGLOBAL,$_OBJ;
	    if(!$contentid) return true;
		if($readall) $cachefile = $this->getCacheFile($contentid);
		if(1 && is_file($cachefile) && $readall && filesize($cachefile)>0 && _NOW_ - filemtime($cachefile) < CACHE_LIFETIME){
		    include_once($cachefile);
			$ret = $_SGLOBAL["{$contentid}"][$contentid];
		} else {
			$this->setHandler();
			$content=$this->handler->get($contentid);
			if(!$content) return true;
			$ret = $this->get_news_vars($content);
			//获取内容
			if($readall){
				if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
				$table = $_SGLOBAL['model'][$ret['modelid']]['tablename'];
				if(!$table) jieqi_printfail(LANG_ERROR_PARAMETER);
				$table = $this->tablepre.'c_'.$table;
				$sql = 'select * from '.jieqi_dbprefix("{$table}")." where {$this->idfield} = {$contentid}";
				if($c = selectsql($sql)) $ret = array_merge($ret, $c[0]);
			}
			if(1 && $readall){//USE_CACHE
				$data[] = $ret;
				cache_write("{$contentid}", "_SGLOBAL['{$contentid}']", $data, 'contentid', $cachefile);
			}
		}
		return $ret;
	}

	/**
	 * 传入文章实例对象，返回适合模板赋值的文章信息数组
	 * 
	 * @param      object      $news 文章实例
	 * @access     public
	 * @return     array
	 */
	function get_news_vars($news, $output = false){
		global $_SGLOBAL,$_OBJ;
		if(!is_object($_SGLOBAL['category'])) $_OBJ['category'] = new Category();
		if(!is_object($_SGLOBAL['model'])) $_OBJ['model'] =new Model();
		//加载content类，用与判断当前文章是否生成静态
		//if(!is_object($this)) $this = new Content();
		$i = 0;
		$ret = array();
		foreach($news->vars as $k=>$v){
			if($i && array_key_exists($k, $ret)) continue;
			$ret[$k]=$news->getVar($k,'n');
			$i++;
		}
		if($output && $ret['thumb'] && substr(strtolower($ret['thumb']),0,7)!='http://'){
			$attachurl = $_OBJ['category']->getAttachurl($ret['catid']);
			$ret['thumb'] = $attachurl.$ret['thumb'];
		}
		if($this->isHtml($ret)) $ret['ishtml'] = true;
		else  $ret['ishtml'] = false;
		$ret['alltitle'] = $this->setStyle($ret['title'], $ret['style'], $ret['thumb']);
		$ret['catname'] = $_SGLOBAL['category'][$ret['catid']]['catname'];
		$ret['modelid'] = $_SGLOBAL['category'][$ret['catid']]['modelid'];
		$ret['modelname'] = $_SGLOBAL['model'][$_SGLOBAL['category'][$ret['catid']]['modelid']]['name'];
		$ret['url_articleinfo'] = $this->getUrl($ret, 1);//jieqi_geturl('news', 'show', $ret, 1);
		$ret['url_catelist'] = $_OBJ['category']->getUrl($ret['catid']);
		return $ret;
	}

    //修饰标题
	function setStyle($str, $style, $thumb = ''){
		if(!$style && !$thumb) return $str;
		$str = $style ?"<span class=\"{$style}\">{$str}</span>" :$str;
		return "$str".($thumb ?' <font color="red">图</font>' :'');
	}
	
	/**
	 * 获取缓存文件路径
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getCacheFile($id){
	     $dir = $this->datadir;
		 if(!is_dir($dir)) if(!jieqi_createdir($dir, 0777, true)) return false;
		 if($this->module) $dir = $dir."{$this->module}/";
		 if(!is_dir($dir)) if(!jieqi_createdir($dir, 0777, true)) return false;
		 return $dir.$id.'.php';
	 }
	 
	/**
	 * 清除一条缓存
	 * 
	 * @access     public
	 * @return     empty
	 */
	 function delCache($id){
	     $cachefile = $this->getCacheFile($id);
		 if(is_file($cachefile)) @unlink($cachefile);
	 }
	 	 	
	/**
	 * 检查一条文章是否存在
	 * 
	 * @access     public
	 * @return     empty
	 */	
	 function checkTitle($title, $param = array(), $level = 0){
	      global $_SGLOBAL,$_SCONFIG;
		  $where = '';
		  $level = $level ? $level : $_SCONFIG['samearticlename'];
		  switch($level){
		      case 1:
			      if($param['modelid']){
					  if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
					  $catids = $_OBJ['model']->getMCatids($param['modelid']);
					  if($catids){
					      $where = '`catid` in ('.implode(',', $catids).') AND ';
					  }
				  }
			  break;
		  }
		  $title = shtmlspecialchars($title);
	      $info = selectsql("SELECT * FROM `".jieqi_dbprefix($this->table)."` WHERE $where `title`='$title'");
		  if($info[0]['contentid']) return TRUE;
		  else return FALSE;
	 }
	 
	/**
	 * 按给定字段条件检查文章是否存在
	 * 
	 * @access     public
	 * @return     empty
	 */	
	 function checkFields($data,$table = '', $param = array(), $level = 0){
	      global $_SGLOBAL,$_SCONFIG;
		  if(!is_array($data)) return FALSE;
		  $where = '';
		  $level = $level ? $level : $_SCONFIG['samearticlename'];
		  switch($level){
		      case 1:
			      if($param['modelid']){
					  if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
					  $catids = $_OBJ['model']->getMCatids($param['modelid']);
					  if($catids){
					      $where = '`catid` in ('.implode(',', $catids).') AND ';
					  }
				  }
			  break;
		  }
		  $sql = $comma = $co = '';
		  foreach ($data as $key => $value) {
			  $sql .= $comma.'`'.$key.'`';
			  $sql .= '=\''.shtmlspecialchars($value).'\'';
			  $comma = ' AND ';
		  }
		  if($table) $co = " as a left join `".jieqi_dbprefix($table)."` as b on a.contentid=b.contentid ";
	      $info = selectsql("SELECT * FROM `".jieqi_dbprefix($this->table)."` {$co} WHERE {$where} {$sql}");
		  if($info[0]['contentid']) return count($info);
		  else return FALSE;
	 }
	 	 
	/**
	 * 获取一个栏目的文件名
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getUrlrule($data, $page = 1, $evalpage = true){
	     global $_SGLOBAL,$_OBJ;
		 if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		 $cate=$_OBJ['category']->get($data['catid']);
		 if($cate['type']>0) return false;//单网页//外链
		 if(!$data['contentid'] || !is_array($data)) return false;//数据不完整
		 extract($data);//
		 $isrule = false;//地址是否需要解析
		 
	     if($prefix){//命名文件名
			 $urlrule = $prefix;//!substr_count($prefix,'.') ? $prefix.'.html' : $prefix;//默认
			 $isrule = true;
		 }else{
			 $urlrule = 'show-{$contentid}-{$page}';//默认
			 if($cate['setting']['show_urlrule']){
				 $urlrule = $cate['setting']['show_urlrule'];
			 }else{
				 if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
				 if($_SGLOBAL['model'][$cate['modelid']]['show_urlrule']){
					 $urlrule = $_SGLOBAL['model'][$cate['modelid']]['show_urlrule'];
				 }
			 }
			 if($evalpage){
			     if($page<2) $urlrule_tmp = $urlrule;
				 if(!substr_count($urlrule, '/')){
					 if($page<2) $urlrule = $contentid;//默认
					 else $isrule = true;
				 }else{
					 if($page<2) $urlrule = substr($urlrule, 0, strrpos($urlrule, '/')+1).'index';
					 $isrule = true;
				 }
				 if($page<2 && substr_count($urlrule_tmp,'.')>0){
				     $urlrule = $urlrule.'.'.fileext($urlrule_tmp);
				 }
			 }else{
			     $page = '<{$page}>';
				 $isrule = true;
			 }
		 }
		 if($isrule) eval('$urlrule = "'.saddslashes($urlrule).'";');
		 $urlrule = !substr_count($urlrule,'.') ? $urlrule.'.html' : $urlrule;
		 return $urlrule;
	 }
	 
	/**
	 * 获取一条文章是否生成文件
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function isHtml($data){
	     global $_SGLOBAL,$_OBJ;
		 if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		 $cate=$_OBJ['category']->get($data['catid']);
		 if($cate['type']>0 || $data['url']) return false;//单网页//外链||转向链接
		 $ishtml = false;//默认
		 //如果是内部栏目
		 if($cate['setting']['show_ishtml']>=0 && isset($cate['setting']['show_ishtml'])){
			 $ishtml = $cate['setting']['show_ishtml'];
		 }else{
			 if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
			 if($_SGLOBAL['model'][$cate['modelid']]['disabled']) return false;
			 $ishtml = $_SGLOBAL['model'][$cate['modelid']]['show_ishtml'];
		 }
		 if($ishtml<2) return false;
		 return $ishtml;
	 }
	 
	/**
	 * 获取一条文章显示方式
	 * 
	 * @access     public
	 * @return     int
	 */	
	 function showType($data){
	     global $_SGLOBAL,$_OBJ;
		 if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		 $cate=$_OBJ['category']->get($data['catid']);
		 $tyle = 0;//默认
		 //如果是内部栏目
		 if($cate['setting']['show_ishtml']>=0 && isset($cate['setting']['show_ishtml'])){
			 $tyle = $cate['setting']['show_ishtml'];
		 }else{
			 if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
			 if($_SGLOBAL['model'][$cate['modelid']]['disabled']) return false;
			 $tyle = $_SGLOBAL['model'][$cate['modelid']]['show_ishtml'];
		 }
		 return $tyle;
	 }	 
	 
	/**
	 * 获取一条文章的存放目录
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function getDir($data){
	     global $_OBJ;
		 if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		 //if($data['catid']!=96){
		 $dir = str_replace(_ROOT_.'/', '', $_OBJ['category']->getDir($data['catid']));
		 $dirs = explode('/', $dir);
		 return _ROOT_.'/'.$dirs[0].'/';
		 //} else return $_OBJ['category']->getDir($data['catid']);
	 }
	 
	/**
	 * 获取一个文章的URL
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getUrl($data, $page = 1, $evalpage = true){
	     return jieqi_geturl('news', 'show', $data, $page, $evalpage);
	 }
	 	 	
	/**
	 * 增加一条数据
	 * 
	 * @access     public
	 * @return     int
	 */
	function add($tdata, $cdata){
	    global $_SGLOBAL,$_OBJ;
		if(!is_array($tdata) || !is_array($cdata) || !$tdata['catid']) return false;
		if($conentid = inserttable($this->table, $tdata, true)){
		
		   if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();

		   if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();

		   $table = $_SGLOBAL['model'][$_SGLOBAL['category'][$tdata['catid']]['modelid']]['tablename'];
		   $cdata['contentid'] = $conentid;
		   inserttable("{$this->tablepre}c_{$table}", $cdata);
		   //栏目数据统计
		   updatetable('news_category', array('items'=>'++'),"catid={$tdata['catid']}");
		   //模型数据统计
		   //updatetable('news_category', array('items'=>'++'),"catid={$tdata['catid']}");
		   //判断是否更新栏目数据
		   $cache = false;
		   if(defined('UPDATE_CATEGORY_CACHE')){
		       if(UPDATE_CATEGORY_CACHE) $cache = true;
		   }else $cache = $_SGLOBAL['model'][$_SGLOBAL['category'][$tdata['catid']]['modelid']]['isrelated'];
		   if($cache) $_OBJ['category']->cache();
		   
		   return $conentid;
		}else{
		   return false;
		}
	}	
	
	/**
	 * 编辑一条数据
	 * 
	 * @access     public
	 * @return     int
	 */
	function edit($tdata, $cdata = array()){
	    global $_SGLOBAL, $_OBJ, $_PAGE;
		if(!is_array($tdata) || !$tdata['catid'] || !$tdata['contentid']) return false;
		$conentid = $tdata['contentid'];
		unset($tdata['contentid']);
		unset($cdata['contentid']);
		if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		$cate = $_OBJ['category']->get($tdata['catid']);
		//如果改变了栏目
		if($_PAGE['_GET']['catid'] && $_PAGE['_GET']['catid']!=$tdata['catid']){
		    $oldcate = $_OBJ['category']->get($_PAGE['_GET']['catid']);
		    //$oldmodel = $_SGLOBAL['category'][$_PAGE['_GET']['catid']]['modelid'];
			//$newmodel = $cate['modelid'];
			if(!$oldcate['modelid'] || !$cate['modelid']) jieqi_printfail(lang_replace('model_not_exists'));
			if($oldcate['modelid']!=$cate['modelid']) return false;
			updatetable('news_category', array('items'=>'--'),"catid={$_PAGE['_GET']['catid']}");
			updatetable('news_category', array('items'=>'++'),"catid={$tdata['catid']}");
			$_OBJ['category']->cache();
		}
		if(updatetable($this->table, $tdata, "{$this->idfield}={$conentid}")){
		    $model = $_OBJ['model']->get($cate['modelid'], true);
		    $table = $model['tablename'];
		    if($cdata) updatetable("{$this->tablepre}c_{$table}", $cdata, "{$this->idfield}={$conentid}");
			$this->delCache($conentid);
			return true;
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
	function delete($data){
	    global $_SCONFIG, $_SGLOBAL, $_OBJ;
	    $conentid = $data['contentid'];
		if(deletesql($this->table, array("{$this->idfield}"=>"{$conentid}"))){
		    if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		    if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
			$cate = $_OBJ['category']->get($data['catid']);
		    $table = $_SGLOBAL['model'][$cate['modelid']]['tablename'];
		    deletesql("{$this->tablepre}c_{$table}", "{$this->idfield}={$conentid}");
			//删除文章相关评论
			deletesql('news_comment', "{$this->idfield}={$conentid}");
			//删除点击统计
			deletesql($this->table_count, "{$this->idfield}={$conentid}");
			//删除DIGG数据
			deletesql($this->table_digg, "{$this->idfield}={$conentid}");
			deletesql($this->table_digglog, "{$this->idfield}={$conentid}");
			//删除心情
			deletesql($this->table_mood, "{$this->idfield}={$conentid}");
			//修改栏目统计
			updatetable('news_category', array('items'=>'--'),"catid={$data['catid']}");
			$_OBJ['category']->cache();
			$this->delCache($conentid);
			//if($this->isHtml($data)) jieqi_delfile($this->getDir($data).$this->getUrlrule($data, 1).$_SCONFIG['htmlfile']);
			return true;
		}else{
		    return false;
		}
	}
	
	/**
	 * 增加点击
	 * 
	 * @access     public
	 * @return     array
	 */
	function hits($contentid, $num = 1, $data = array())
	{
	    global $_SGLOBAL;
		$contentid = intval($contentid);
		$sql = 'select * from '.jieqi_dbprefix($this->table_count)." WHERE `contentid`=$contentid";
		
		if($r = selectsql($sql)){ //如果存在则自加
		    if(!$data){
				$data = $r[0];
				$data['hits'] = $data['hits'] + $num;
				$data['hits_day'] = (date('Ymd', $data['hits_time']) == date('Ymd', $_SGLOBAL['timestamp'])) ? ($data['hits_day'] + $num) : $num;
				$data['hits_week'] = (date('YW', $data['hits_time']) == date('YW', $_SGLOBAL['timestamp'])) ? ($data['hits_week'] + $num) : $num;
				$data['hits_month'] = (date('Ym', $data['hits_time']) == date('Ym', $_SGLOBAL['timestamp'])) ? ($data['hits_month'] + $num) : $num;
				$data['hits_time'] = $_SGLOBAL['timestamp'];
			}else{
			    $data['comments'] = $r[0]['comments']+$data['comments'];
				$data['comments_checked'] = $r[0]['comments_checked']+$data['comments_checked'];
			}
			if(!updatetable($this->table_count, $data,"contentid={$contentid}")) return false;
		}else{
		    if(!$data){
				$data['comments'] = $data['comments_checked'] = 0;
			}
		    $data['hits'] = $data['hits_day'] = $data['hits_week'] = $data['hits_month'] = $num;
			$data['contentid'] = $contentid;
			$data['hits_time'] = $_SGLOBAL['timestamp'];
			
		    if(!inserttable($this->table_count, $data)) return false;
		}
		return $data;
	}
	
	/**
	 * 获取一条DIGG数据
	 * 
	 * @access     public
	 * @return     array
	 */
	 function getDigg($contentid){
	     $contentid = intval($contentid);
		 $sql = 'select * from '.jieqi_dbprefix($this->table_digg)." WHERE `contentid`=$contentid";
		 if($r = selectsql($sql)) return $r[0];
		 else return false;
	 }
	 
	/**
	 * 增加digg
	 * 
	 * @access     public
	 * @return     array
	 */
	 function digg($contentid, $flag = 1){ //$flag 为1表示支持
	     global $_SGLOBAL, $_SN;
		 $contentid = intval($contentid);
		 $flag = $flag == 1 ? 1 : 0;
		 $where = $_SGLOBAL['supe_uid'] ? "`userid`={$_SGLOBAL['supe_uid']} AND `contentid`=$contentid" : "`ip`='".jieqi_userip()."' AND `contentid`=$contentid";
		 $sql = "SELECT count(*) as num FROM `".jieqi_dbprefix($this->table_digglog)."` WHERE $where";
		 if($ret = selectsql($sql)){
		    if($ret[0]['num']>0) return false;//如果已经投票则返回
		 }
		 
		 if($r = $this->getDigg($contentid)){ //如果存在则自加
		     $data = $r;
			 if($flag){
			     $data['supports'] = $data['supports'] + 1;
				 $data['supports_day'] = (date('Ymd', $data['updatetime']) == date('Ymd', $_SGLOBAL['timestamp'])) ? ($data['supports_day'] + 1) : 1;
				 $data['supports_week'] = (date('YW', $data['updatetime']) == date('YW', $_SGLOBAL['timestamp'])) ? ($data['supports_week'] + 1) : 1;
				 $data['supports_month'] = (date('Ym', $data['updatetime']) == date('Ym', $_SGLOBAL['timestamp'])) ? ($data['supports_month'] + 1) : 1;
				 $data['updatetime'] = $_SGLOBAL['timestamp'];
			 }else{
			     $data['againsts'] = $data['againsts'] + 1;
				 $data['againsts_day'] = (date('Ymd', $data['updatetime']) == date('Ymd', $_SGLOBAL['timestamp'])) ? ($data['againsts_day'] + 1) : 1;
				 $data['againsts_week'] = (date('YW', $data['updatetime']) == date('YW', $_SGLOBAL['timestamp'])) ? ($data['againsts_week'] + 1) : 1;
				 $data['againsts_month'] = (date('Ym', $data['updatetime']) == date('Ym', $_SGLOBAL['timestamp'])) ? ($data['againsts_month'] + 1) : 1;
				 $data['updatetime'] = $_SGLOBAL['timestamp'];
			 }
			 if(!updatetable($this->table_digg, $data,"contentid={$contentid}")) return false;
		 }else{
		     if($flag){
			     $data = array('contentid'=>$contentid,'supports'=>1,'supports_day'=>1,'supports_week'=>1,'supports_month'=>1,'againsts'=>0,'againsts_day'=>0,'againsts_week'=>0,'againsts_month'=>0,'updatetime'=>$_SGLOBAL['timestamp']);
			 }else{
			     $data = array('contentid'=>$contentid,'againsts'=>1,'againsts_day'=>1,'againsts_week'=>1,'againsts_month'=>1,'supports'=>0,'supports_day'=>0,'supports_week'=>0,'supports_month'=>0,'updatetime'=>$_SGLOBAL['timestamp']);
			 }
			 if(!inserttable($this->table_digg, $data)) return false;
		 }
		 $log = array('contentid'=>$contentid, 'flag'=>$flag, 'userid'=>$_SGLOBAL['supe_uid'], 'username'=>$_SN[$_SGLOBAL['supe_uid']], 'ip'=>jieqi_userip(), 'time'=>$_SGLOBAL['timestamp']);
		 inserttable($this->table_digglog, $log);
		 return array_merge($data, $log);
	 }

	
	/**
	 * 获取一条mood数据
	 * 
	 * @access     public
	 * @return     array
	 */
	 function getMood($moodid, $contentid){
	     $moodid = intval($moodid);
	     $contentid = intval($contentid);
		 $sql = 'select * from '.jieqi_dbprefix($this->table_mood)." WHERE `moodid`=$moodid AND `contentid`=$contentid";
		 if($r = selectsql($sql)) return $r[0];
		 else return false;
	 }
	 
	/**
	 * 增加mood
	 * 
	 * @access     public
	 * @return     array
	 */
	 function mood($moodid, $contentid, $vote_id){ 
	     global $_SGLOBAL;
		 
		 //加载配置参数/判断配置是否存在
		 $mood = new GlobalData('mood', 'moodid');
		 if(!($fields = $mood->get($moodid))) return false;
		 //超出范围
		 if($vote_id<1 || $vote_id>$fields['number']) return false;
		 
		 $field = 'n'.$vote_id;
	 	 if($r = $this->getMood($moodid, $contentid)){ //如果存在则自加
		     $data = $r;
			  $data[$field] = $data[$field] + 1;
			  $data['total'] = $data['total'] + 1;
			  $data['updatetime'] = $_SGLOBAL['timestamp'];
			  //计算平均分
			  if($fields['isavg']){
			      $data['scores'] = $data['scores'] + $vote_id;
				  $data['avg'] = number_format($data['scores']/$data['total'],1);//sprintf("%.1f",$data['scores']/$data['total']);
			  }
			  if(!updatetable($this->table_mood, $data," `moodid`=$moodid AND contentid={$contentid}")) return false;
		 }else{
		      $data['moodid'] = $moodid;
			  $data['contentid'] = $contentid;
		      $data[$field] = 1;
			  $data['total'] = 1;
			  //计算平均分
			  if($fields['isavg']){
			      $data['scores'] = $vote_id;
				  $data['avg'] = number_format($data['scores'],1);//sprintf("%.1f",$data['scores']);
			  }
			  $data['updatetime'] = $_SGLOBAL['timestamp'];
			  if(!inserttable($this->table_mood, $data)) return false;
		 }
		 return $data;
	 }
	/**
	 * 查询记录列表
	 * 
	 * @access     public
	 * @return     array
	 */
	 function lists($pagenum = 0, $page = 0, $custompage = '', $emptyonepage = false){
	    global $_SCONFIG;
		if($pagenum){
			$this->criteria->setLimit($pagenum);
			if(!$page) $page=1;
			$this->criteria->setStart(($page-1) * $pagenum);
		}
		$this->handler->queryObjects($this->criteria);
		$rows = array();
		$k=0;
		while($v = $this->handler->getObject()){
			$rows[$k] = $this->get_news_vars($v, true);
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
    
	function getData($param, $reterror = false, $emptyonepage = false){
	    global $_SGLOBAL, $_SCONFIG, $_OBJ, $_PAGE;
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
		if($param['tag']){
			$param['tag'] = urldecode($param['tag']);
			$keywords = explode(' ',$param['tag']);
		}
		if($param['catid']){
		    $param['catids'] = explode('|',$param['catid']);
			if(!($_SGLOBAL['cate'] = $cate = $_OBJ['category']->get($param['catids'][0], true))){
			    if(!$reterror) jieqi_printfail(lang_replace('category_not_exists'));
				else return false;
			}
			//$param['catids'] = array($param['catid']);
			$param['mid'] = $_SGLOBAL['cate']['modelid'];
		}else{
			if($param['mid']){
				$param['model'] = $_OBJ['model']->get($param['mid']);
				$param['m'] = $_PAGE['model']['tablename'];
			}elseif($param['m']){
				foreach($_OBJ['model']->data as $v){
					if(array_search($param['m'],$v)){
					   $param['mid'] = $v['modelid'];
					   break;
					}
				}
				if(!$param['mid']){
				    if(!$reterror) jieqi_printfail(LANG_ERROR_PARAMETER);
					else return false;
				}
			}
			foreach($_SGLOBAL['category'] as $k=>$v){
				if($v['modelid']==$param['mid'] && $_OBJ['category']->islist($v['catid'])) $param['catids'][] = $v['catid'];
			}
			$cate = $_OBJ['category']->get($param['catids'][0], true);
		}
		
		//构造查询表
		$tables = array();
		if($_SCONFIG['showarticlelists'] & 1 && $param['catids']) $tables[$this->tablepre.'c_'.$_SGLOBAL['model'][$cate['modelid']]['tablename']] = '*';  //文章附加表表名
		if($_SCONFIG['showarticlelists'] & 2) $tables[$this->table_count] = 'hits,hits_day,hits_week,hits_month,hits_time,comments,comments_checked'; //文章统计表表名
		if($_SCONFIG['showarticlelists'] & 4) $tables[$this->table_digg] = 'supports,againsts,supports_day,againsts_day,supports_week,againsts_week,supports_month,againsts_month';
		$tag = $tables ? 'tables' : NULL;
		$tabletag = ($tag ?'news_content.' :'');
		unset($this->handler);
		unset($this->criteria);
		$this->setHandler($tag);
		if(!$param['order']){
		   $order = "{$tabletag}inputtime";
		   $param['order'] = 'inputtime';
		}else $order = $param['order'];
					
		if($tables){
			$tablestr = $fields = '';
			foreach($tables as $table=>$field){
				$tablestr.= " LEFT JOIN ".jieqi_dbprefix($table)." AS {$table} ON {$tabletag}contentid={$table}.contentid ";
				$fields.= ",{$table}.{$field}";
			}
			$this->criteria->setFields("{$tabletag}*{$fields}");
			$this->criteria->setTables(jieqi_dbprefix('news_content')." AS news_content {$tablestr}");
		}
		
		if($param['catids']){
			if(count($param['catids'])>1){
				$catids = implode(',',$param['catids']);
				$this->criteria->add(new Criteria('catid', "($catids)", 'in'));
			}elseif(count($param['catids'])==1){
				if($_SGLOBAL['cate']['child']){
					$catids = $_SGLOBAL['cate']['arrchildid'];
					$this->criteria->add(new Criteria('catid', "($catids)", 'in'));
				} else $this->criteria->add(new Criteria('catid', $param['catids'][0]));
			}
		}
		
		if($keywords){
			if(count($keywords)>1){
				foreach($keywords as $k=>$v){
					$left = !$k ? '(' : '';
					$this->criteria->add(new Criteria($left.'keywords', '%'.$v.'%', 'like'), 'AND');
				}
			}elseif(count($keywords)==1){
				$this->criteria->add(new Criteria('keywords', '%'.$keywords[0].'%', 'like'));
			}
		}
		if($param['title']) $this->criteria->add(new Criteria('title', '%'.$param['title'].'%', 'like'), $keywords ? 'OR' :'AND');
		$this->criteria->add(new Criteria('status', 99), count($keywords)>1 ? ') AND' : 'AND');
		$this->criteria->setSort($order);
		$this->criteria->setOrder('DESC');
		$pagesize = $param['pagesize'] ? $param['pagesize'] : ($_SCONFIG['pagenum'] ?$_SCONFIG['pagenum'] :30);
		$_PAGE['articlerows'] = $this->lists($pagesize, $param['page'], $param['pagestr'], $emptyonepage);
		return $_PAGE;
	}
	
	/**
	 * 获取网页内容
	 * 
	 * @access     public
	 * @return     string|array
	 */
	function getContent($contentid, $page = 1){
	    global $_SGLOBAL, $_SCONFIG, $_PAGE, $_OBJ;
		//初始化栏目操作对像和加载栏目数据列表
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		//获取内容
		if(!is_array($_PAGE['data'] = $this->get($contentid))) return false;
		//获取栏目内容
		$_SGLOBAL['cate'] = $_OBJ['category']->get($_PAGE['data']['catid']);
		//加载表单对象类
		include_once($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
		//加载表单数据对象类
		$elements = new FormElements($_SGLOBAL['cate']['modelid'], $_PAGE['data']['catid']);
		if(!$_PAGE['data'] = $elements->show($_PAGE['data'])) return false;
		$_PAGE['data']['___content'] = $elements->getVar('___content');
		$_PAGE['data']['__content'] = $_PAGE['data']['___content']['content'][$page-1];
		return $_PAGE['data'];
	} 
	
	/**
	 * 输出网页内容
	 * 
	 * @access     public
	 * @return     string|array
	 */
	function fetch($contentid, $page = 1, $createhtml = false){
		global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiTpl, $_PAGE, $_OBJ;
		//初始化栏目操作对像和加载栏目数据列表
		if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		//加载模型
		if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
		//加载表单对象类
		///include_once($_SGLOBAL['news']['path'].'/include/fields/formelements.class.php');
		//获取内容
		///if(!is_array($_PAGE['data'] = $this->get($contentid))) return false;
		if(!is_array($_PAGE['data'] = $this->getContent($contentid,$page ))) return false;
		//获取栏目内容
		///$_SGLOBAL['cate'] = $_OBJ['category']->get($_PAGE['data']['catid']);
		
		//加载表单数据对象类
		///$elements = new FormElements($_SGLOBAL['cate']['modelid'], $_PAGE['data']['catid']);
		///if(!$_PAGE['data'] = $elements->show($_PAGE['data'])) return false;
		
		///$_PAGE['data']['___content'] = $elements->getVar('___content');
		$_PAGE['pagetatol'] = count($_PAGE['data']['___content']['content']);
		if($page>$_PAGE['pagetatol']) return false; //当前页码大于总页数
		
		//如果是生成静态
		if($createhtml){
			$ret = array();
			foreach($_PAGE['data']['___content']['content'] as $page=>$value){
				$page++;//页码从1开始
				$_PAGE['data']['__content'] = $value;
				
				if($_PAGE['pagetatol']>1){
					$jumppage = new GlobalPage($_SCONFIG['articlepages'],$_PAGE['pagetatol'],1,$page);
					$linkurl = '';
					if(!$this->showType($_PAGE['data']['catid'])){
						$linkurl = $this->getUrl($_PAGE['data'], 1, false);
						$urlrule = $this->getUrlrule($_PAGE['data'], 1, false);
						if(!substr_count($urlrule, '/')){
							$jumppage->emptyonepage = $contentid.'.'.fileext($linkurl);
						}else $jumppage->emptyonepage = '';
					}
					$_PAGE['url_jumppage'] = $jumppage->getPage($linkurl);
				}
				template($_PAGE['data']['_showtemplate']);
				$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
				if(!is_file($jieqiTset['jieqi_page_template']) && strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
				$ret[$page] = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
				unset($jieqiTset['jieqi_contents_template']);
				unset($jumppage);
				unset($_PAGE['url_jumppage']);
			}
		}else{
			$ret = '';
			$_PAGE['data']['__content'] = $_PAGE['data']['___content']['content'][$page-1];
			
			if($_PAGE['pagetatol']>1){
				$jumppage = new GlobalPage($_SCONFIG['articlepages'],$_PAGE['pagetatol'],1,$page);
				$linkurl = '';
				if($this->showType($_PAGE['data'])){
					$linkurl = $this->getUrl($_PAGE['data'], 1, false);
					$urlrule = $this->getUrlrule($_PAGE['data'], 1, false);
					if(!substr_count($urlrule, '/')){
						$jumppage->emptyonepage = $contentid.'.'.fileext($linkurl);
					}else $jumppage->emptyonepage = '';
				}
				$_PAGE['url_jumppage'] = $jumppage->getPage($linkurl);
			}
			$_PAGE['ac'] = 'show';
			template($_PAGE['data']['_showtemplate']);global $jieqiTset;
			$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
			if(!is_file($jieqiTset['jieqi_page_template']) && strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
			$ret = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
		}
		jieqi_freeresource();
		return $ret;
	}
	
	/**
	 * 获取分页
	 * 
	 * @access     public
	 * @return     string
	 */
/*	 function getPage($setlink = ''){
		 if($setlink) $this->jumppage->setlink($setlink, false, true);
		 return $this->jumppage->whole_bar();
	 }*/
}
?>