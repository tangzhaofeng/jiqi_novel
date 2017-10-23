<?php
/*
    *目录处理类[目录的增删改转移]
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: category.class.php 12398 2010-05-20 18:36:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class Category extends GlobalData{

	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function Category($category = array()){
         if(!$category){
			 $this->GlobalData('category', 'catid','listorder');
		 }else {
		     $this->data = $category;
		 }
	}

	/**
	 * 检查一个栏目是否存在
	 * 
	 * @access     public
	 * @return     bool
	 */
	function checkdata($id, $isreture = false){
	    //判断数据是否存在
	    if(!array_key_exists($id, $this->data)){
			if(!$isreture) jieqi_printfail(lang_replace('category_not_exists'));
			else return false;
		}else{
			return true;
		}
	}
	
	/**
	 * 获取一个栏目
	 * 
	 * @access     public
	 * @return     array
	 */
	function get($catid, $isreture = true){
	    //判断栏目是否存在
		if(!$this->checkdata($catid, $isreture)) return false;
	    global $categorySetting;//不必重复解析
		if($this->data[$catid]['setting'] && !$categorySetting[$catid]){
			eval('$categorySetting['.$catid.'] = '.$this->data[$catid]['setting'].';');
			$this->data[$catid]['setting'] = $categorySetting[$catid];
		}else{
		    $this->data[$catid]['setting'] = $categorySetting[$catid];
		}
        //返回栏目内容
		//return shtmlspecialchars($this->data[$catid]);
		return ($this->data[$catid]);
	}
	
	/**
	 * 查检一个栏目是否是列表页
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function islist($catid){
		 $cate=$this->get($catid);
		 if($cate['type']>0) return false; //外链与单网页都没有列表
		 if($cate['child']) return false;
		 else return true;
	 }
	/**
	 * 获取一个栏目的生成/访问URL
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getCateurl($catid = 0){
	     global $_SCONFIG;
		 return $_SCONFIG['cateurl'] ?$_SCONFIG['cateurl'] :JIEQI_URL;
	 }
	 
	/**
	 * 获取一个栏目列表页每页信息条数
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getPagenum($catid = 0){
	     global $_SCONFIG;
		 $retval = '';
		 if($catid){
			 $cate=$this->get($catid);
			 $retval = $cate['setting']['pagenum'];
		 }
		 if(!$retval) $retval = $_SCONFIG['pagenum'] ?$_SCONFIG['pagenum'] :30;
		 return $retval;
	 }
	 	 	 	
	/**
	 * 获取一个栏目文章的发布URL
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getPosturl($catid = 0){
	     global $_SCONFIG;
		 return $_SCONFIG['staticurl'] ?$_SCONFIG['staticurl'] :JIEQI_URL;
	 }
	 	
	/**
	 * 获取一个栏目的附件URL服务器
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getAttachurl($catid = 0){
	     global $_SCONFIG,$_SGLOBAL,$_OBJ,$_PAGE;
		 $attachurl = $_SCONFIG['attachurl'] ?$_SCONFIG['attachurl'] :JIEQI_URL;
		 $cate=$this->get($catid);
		 if($cate['type']==0 && $cate['setting']['attachurl']){//如果是内部栏目
		      $attachurl = $cate['setting']['attachurl'];
		 }
		 return $attachurl;
	 }	 
	/**
	 * 获取一个栏目的模板
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getTemplate($catid){
	     global $_SGLOBAL,$_OBJ,$_PAGE;
	     $cate=$this->get($catid);
		 if($cate['type']>1) return false;
		 if($cate['type']==0){//如果是内部栏目
		     if($cate['child']){//如果有子目录，使用频道模板
			     if($cate['setting']['template_category']){
				     return $cate['setting']['template_category'];
				 }else{
				     if(!is_object($_OBJ['model'])) new Model();
				     if($_SGLOBAL['model'][$cate['modelid']]['template_category']){
					     return $_SGLOBAL['model'][$cate['modelid']]['template_category'];
					 }else return 'category';
			     }
			 }else{
			     $template = $cate['setting']['template_list'];
			     if(!$template){
				     if(!is_object($_OBJ['model'])) new Model();
					 $template = $_SGLOBAL['model'][$cate['modelid']]['template_list'];
				     if(!$template) $template = 'list';
				 }
				 if(substr_count($template,'|')){
					 $templates = explode('|',$template);
					 if(getparameter('page')>1) return $templates[1];
					 else return $templates[0];
				 }
				 return $template;
			 }
		 }else{//单网页
		     	 if($cate['setting']['template_category']){
				     return $cate['setting']['template_category'];
				 }else return 'page';
		 }
	 }
	
	/**
	 * 获取一个栏目的文件名
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getUrlrule($catid, $page = 1, $evalpage = true){
	     global $_SGLOBAL,$_OBJ;
		 $cate=$this->get($catid);
		 if($cate['type']>1) return false;//外链
		 if(!$evalpage) $page = '<{$page}>';
		 $isrule = false;//地址是否需要解析
		 $urlrule = 'index';//默认
		 //$urlrule = "list-{$catid}";//默认
		 if($cate['type']<1){
			 if(!is_object($_OBJ['model'])) new Model();
			 if($cate['setting']['ishtml']>=0 && isset($cate['setting']['ishtml'])){
				 $ishtml = $cate['setting']['ishtml'];
			 }else{
				 $ishtml = $_SGLOBAL['model'][$cate['modelid']]['ishtml'];
			 }
		 }
		 if($cate['type']>0){//单网页
			 if($cate['setting']['category_urlrule']){
			    $urlrule = $cate['setting']['category_urlrule'];
				$isrule = true;
			 }
		 }elseif($cate['child']){//有子栏目
			 if($cate['setting']['category_urlrule']){
				 $urlrule_tmp = $cate['setting']['category_urlrule'];
			 }else{
				 if($_SGLOBAL['model'][$cate['modelid']]['category_urlrule']){
					 $urlrule_tmp = $_SGLOBAL['model'][$cate['modelid']]['category_urlrule'];
				 }
			 }
			 if($ishtml!=1){
			     if($urlrule_tmp && substr_count($urlrule_tmp,'.')>0) $urlrule = $urlrule.'.'.fileext($urlrule_tmp);
			 }else{
			     $urlrule = $urlrule_tmp;
				 if($evalpage) $isrule = true;
				 else{
					 $page = '<{$page}>';
					 $isrule = true;
				 }
			 }
		 }else{//如果是内部栏目
		     //if($page>1 || !$evalpage){//页码等于1的时候，文件名为index.html
				 $urlrule = 'list-{$catid}-{$page}';//默认
				 if($cate['setting']['category_urlrule']){
					 $urlrule = $cate['setting']['category_urlrule'];
				 }else{
					 //if(!is_object($_OBJ['model'])) new Model();
					 if($_SGLOBAL['model'][$cate['modelid']]['category_urlrule']){
						  $urlrule = $_SGLOBAL['model'][$cate['modelid']]['category_urlrule'];
					 }
				 }
				 $isrule = true;
			 //}
			 
			 if($evalpage){
			     if($page<2) $urlrule_tmp = $urlrule;
				 if(!substr_count($urlrule, '/')){
					 if($page<2 && $ishtml!=1) $urlrule = 'index';//默认
					 else $isrule = true;
				 }else{
					 if($page<2 && $ishtml!=1) $urlrule = substr($urlrule, 0, strrpos($urlrule, '/')+1).'index';
					 $isrule = true;
				 }
				 if($page<2 && substr_count($urlrule_tmp,'.')>0 && $ishtml!=1){
				     $urlrule = $urlrule.'.'.fileext($urlrule_tmp);
				 }
			 }else{
			     $page = '<{$page}>';
				 $isrule = true;
			 }
		 }
		 if($isrule) eval('$urlrule = "'.saddslashes($urlrule).'";');
		 $urlrule = substr_count($urlrule,'.')<1 ? $urlrule.'.html' : $urlrule;
		 return $urlrule;
	 }
	 
	/**
	 * 获取一个栏目是否生成静态
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function isHtml($catid){
	     global $_SGLOBAL,$_OBJ;
		 $cate=$this->get($catid);
		 if($cate['type']>1) return false;//外链
		 $ishtml = false;//默认
		 if($cate['setting']['ishtml']>=0 && isset($cate['setting']['ishtml'])){
			 $ishtml = $cate['setting']['ishtml'];
		 }else{
		     if($cate['type']==0){//如果是内部栏目
				 if(!is_object($_OBJ['model'])) new Model();
				 if($_SGLOBAL['model'][$cate['modelid']]['disabled']) return false;
				 $ishtml = $_SGLOBAL['model'][$cate['modelid']]['ishtml'];
			 }
		 }
		 if($ishtml<2) return false;
		 return $ishtml;
	 }
	 
	/**
	 * 是否启用全文搜索
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function isSearch($catid){
	     global $_SGLOBAL,$_OBJ;
		 $cate=$this->get($catid);
		 if($cate['type']>1) return false;//外链
		 if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
		 if(!($model = $_OBJ['model']->get($cate['modelid'], true))) return false;
		 return $model['enablesearch'];
	 }
	 
	/**
	 * 获取一个栏目显示方式
	 * 
	 * @access     public
	 * @return     int
	 */	
	 function showType($catid){
	     global $_SGLOBAL,$_OBJ;
		 //if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		 $cate=$this->get($catid);
		 $tyle = 0;//默认
		 //如果是内部栏目
		 if($cate['setting']['ishtml']>=0 && isset($cate['setting']['ishtml'])){
			 $tyle = $cate['setting']['ishtml'];
		 }else{
			 if(!is_object($_OBJ['model'])) new Model();
			 if($_SGLOBAL['model'][$cate['modelid']]['disabled']) return false;
			 $tyle = $_SGLOBAL['model'][$cate['modelid']]['ishtml'];
		 }
		 return $tyle;
	 }	
	 
	/**
	 * 获取一个栏目的附加配置
	 * 
	 * @access     public
	 * @return     array
	 */
	 function getCateSet($catid){
	      global $_SGLOBAL,$_SCONFIG;
		  if(!($cate=$this->get($catid))) return false;
		  $ret = array();
		  //允许上传附件的类型
		  $ret['attachmime'] = $cate['setting']['attachmime'] ?$cate['setting']['attachmime'] :$_SCONFIG['attachmime'];
		  //缩略图
		  $ret['thumb_enable'] = $cate['setting']['thumb_enable']>=0 ?$cate['setting']['thumb_enable'] :$_SCONFIG['thumb_enable'];
		  //缩略图大小
		  if($cate['setting']['thumb_width'] && $cate['setting']['thumb_height']){
		      $ret['thumb_width'] = $cate['setting']['thumb_width'];
			  $ret['thumb_height'] = $cate['setting']['thumb_height'];
		  }else{
			  $thumb_size = $_SCONFIG['thumb_size'];
			  $thumb_size = @explode('*', $thumb_size );
			  $ret['thumb_width'] = $thumb_size[0];
			  $ret['thumb_height'] = $thumb_size[1];
		  }
		  //水印
		  $ret['attachwater'] = $cate['setting']['attachwater']>=0 ?$cate['setting']['attachwater'] :$_SCONFIG['attachwater'];
		  //水印图片文件
		  $ret['attachwimage'] = $cate['setting']['attachwimage'] ?$cate['setting']['attachwimage'] :$_SCONFIG['attachwimage'];
		  return $ret;
	 }
	 
	/**
	 * 获取一个栏目的目录
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function getDir($catid){
	     global $_SGLOBAL,$_OBJ;
		 $cate=$this->get($catid);
		 if($cate['type']>1) return false;//外链
		 //$dirs = explode('/', $cate['parentdir'].$cate['catdir']);
		 //$dir = $dirs[0];
		 //return JIEQI_ROOT_PATH.'/'.$dir.'/';
		 return JIEQI_ROOT_PATH.'/'.$cate['parentdir'].$cate['catdir'].'/';
	 }
	 
	/**
	 * 获取一个栏目的URL
	 * 
	 * @access     public
	 * @return     string
	 */
	 function getUrl($catid, $page = 1, $evalpage = true){
	     return jieqi_geturl('news', 'lists', $catid, $page, $evalpage);
	 }
	 
	/**
	 * 清空一个栏目
	 * 
	 * @access     public
	 * @return     int
	 */
	 function recycle($catid, $cache = true, $deleleall = false){
	     global $_SGLOBAL, $_OBJ;
	     if(!$catid || !$this->checkdata($catid, false)) return false;
		 if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
		 $tname = jieqi_dbprefix('news_content');
		 $cname = jieqi_dbprefix('news_c_'.$_SGLOBAL['model'][$this->data[$catid]['modelid']]['tablename']);
		 dbconnect();
		 $_SGLOBAL['db']->db->query("DELETE `".$tname."`,`".$cname."` FROM `".$tname."`,`".$cname."` WHERE `".$tname."`.contentid=`".$cname."`.contentid AND `".$tname."`.catid='$catid'");
		 if(!$deleleall) updatetable('news_category', array('items'=>0),"catid={$catid}");
		 if($cache) $this->cache();//更新缓存
		 return true;
	 }
	 	 
	/**
	 * 增加一个栏目
	 * 
	 * @access     public
	 * @return     int
	 */
	function add($data){
	    if(!is_array($data)) return false;
		$catid = inserttable($this->table, $data, true);
		if(!$catid) jieqi_printfail(LANG_DO_FAILURE);
		if($data['parentid']){
		    $data['arrparentid'] = $this->data[$data['parentid']]['arrparentid'].','.$data['parentid'];
			$tdir = $this->data[$data['parentid']]['parentdir'].$this->data[$data['parentid']]['catdir'];
			$data['parentdir'] = $tdir ? $tdir.'/' : '';
			$data['parenturl'] = $this->data[$data['parentid']]['url'];
			$parentids = explode(',', $data['arrparentid']);
			foreach($parentids as $parentid){
			    if($parentid){
				    $arrchildid = $this->data[$parentid]['arrchildid'].','.$catid;
					updatetable($this->table, array('child'=>1, 'arrchildid'=>"{$arrchildid}"), "catid={$parentid}");
				}
			}
		}else{
			$data['arrparentid'] = '0';
			$data['parentdir'] = '';
			$data['parenturl'] = '';
		}
		$arrparentid = $data['arrparentid'];
		$parentdir = $data['parentdir'];
		//构造完整的URL
		$url = "{$data['parenturl']}{$data['catdir']}";
		if(empty($data['url'])) $url = $url ? $url."/" :'';
		else $url = $data['url'];
		updatetable($this->table, array('arrchildid'=>"$catid", 'listorder'=>$catid, 'arrparentid'=>"$arrparentid", 'parentdir'=>"$parentdir", 'url'=>"$url"), "catid=$catid");
		$this->cache();//更新缓存
		return $catid;
	}
	
	/**
	 * 删除一个栏目
	 * 
	 * @access     public
	 * @return     bool
	 */
	 function delete($catid, $cache =true, $deleleall = false){
	     global $_SGLOBAL, $_OBJ;
	     if(!$catid || !$this->checkdata($catid, true)) return false;
		 //如果存在子目录，则停止执行
		 $cate=$this->get($catid, false);
		 if(!$deleleall && ($cate['child'] || substr_count($cate['arrchildid'], ','))) jieqi_printfail(lang_replace('category_exists_arrchild'));
		 //重新构建该目录相关所有旧目录的arrchildid设置
		 if($cate['arrparentid']){
			 $oldparentids = explode(',', $cate['arrparentid']);//所有上级目录数组
			 $arrchildids = explode(',', $cate['arrchildid']);//取得该目录所有子目录数组
			 foreach($oldparentids as $oid){
			     if(!$oid || !$this->checkdata($oid, true)) continue;
				 $oldarrchildid = implode(',', array_diff(explode(',', $this->data[$oid]['arrchildid']), $arrchildids));
				 if(!substr_count($oldarrchildid, ',')) $child = 0;
				 else  $child = 1;
				 updatetable($this->table, array('arrchildid'=>"{$this->get_asort($oldarrchildid)}", 'child'=>$child), "catid={$oid}");
			 }
		 }
		 if(deletesql($this->table, array('catid'=>$catid))){
		     $this->recycle($catid, $cache, true);
			 return true;
		 }else{
		     return false;
		 }
	 }
	 	
	/**
	 * 修改一个栏目
	 * 
	 * @access     public
	 * @return     bool
	 */
	function edit($catid, $data){
	    if(!is_array($data)) return false;
		//$cate=$this->get($catid);
		$this->checkdata($catid, false);
		$isupdatecate = false;
		if($data['catdir'] != $this->data[$catid]['catdir']){
		    $isupdatecate = true;
			$this->data[$catid]['catdir'] = $data['catdir'];
		}
		if($data['parentid'] != $this->data[$catid]['parentid']){
		    $this->move($catid, $data['parentid'], $this->data[$catid]['parentid']);
		}elseif($isupdatecate && $this->data[$catid]['child']){//如果修改了栏目目录并且没有修改上级目录且有子目录
		    //修改该目录的URL，以方便下级目录套用
			$turl = $this->data[$this->data[$catid]['parentid']]['url'].$this->data[$catid]['catdir'];
			$this->data[$catid]['url'] = $turl ? $turl.'/' : '';
		    $arrchildids = explode(',', $this->data[$catid]['arrchildid']);
			foreach($arrchildids as $cid){
			  if($cid==$catid || !$this->checkdata($cid, true)) continue;
			  $tdir = $this->data[$this->data[$cid]['parentid']]['parentdir'].$this->data[$this->data[$cid]['parentid']]['catdir'];
			  $this->data[$cid]['parentdir']= $tdir ? $tdir.'/' : '';
			  $turl = $this->data[$this->data[$cid]['parentid']]['url'].$this->data[$cid]['catdir'];
			  $this->data[$cid]['url'] = $turl ? $turl.'/' : '';
			  updatetable($this->table, array('parentdir'=>"{$this->data[$cid]['parentdir']}",'url'=>"{$this->data[$cid]['url']}"), "catid={$cid}");
			}//echo '如果修改了栏目目录并且没有修改上级目录且有子目录';
		}
		if(empty($data['url'])){
		   $url = "{$this->data[$catid]['parentdir']}{$data['catdir']}";
		   $data['url'] = $url ? $url."/" :'';
		}
		if(updatetable($this->table, $data,"catid={$catid}")){
		    $this->cache();//更新缓存
		    return true;
		}else {
		    return false;
		}
		
	}
	
	/**
	 * 移动一个栏目到另一个栏目下
	 * 
	 * @access     public
	 * @return     bool
	 */
	function move($catid, $parentid, $oldparentid){
	    //检查并获取目标栏目数据
		$cate = $this->get($catid, false);
		$parentcate = $parentid ?$this->data[$parentid] :array();
		$oldparentcate = $oldparentid ?$this->data[$oldparentid] :array();
		//修改该目录的URL，以方便下级目录套用
		if(!substr_count($this->data[$catid]['url'], 'http://')) $this->data[$catid]['url'] = ($parentid ?$parentcate['url'] :'').$this->data[$catid]['catdir'].'/';
		$this->data[$catid]['parentdir'] = $parentid ?$parentcate['parentdir']."{$parentcate['catdir']}/" :'';
		//重新构建该目录下子目录相关所有父目录配置
		$arrchildids = explode(',', $cate['arrchildid']);//print_r($arrchildids);
		if(in_array($parentid, $arrchildids)) jieqi_printfail(lang_replace('category_not_do'));
		if($cate['child']){
			foreach($arrchildids as $cid){
			  if($cid==$catid || !$this->checkdata($cid, true)) continue;
			  $this->data[$cid]['arrparentid'] = ($parentid ?$parentcate['arrparentid'].',' :'')."$parentid,".$this->get_arrparentid($cid,$catid);
			  //去掉老目录
			  if($oldparentid){
			     $this->data[$cid]['arrparentid'] = str_replace(",{$oldparentid}", '', $this->data[$cid]['arrparentid']);
			  }
			  //exit($this->data[$cid]['arrparentid'].'hh'.$oldparentid);
			  ///$this->data[$cid]['parentdir'] = $this->data[$catid]['parentdir'].$this->get_parentdir($cid,$this->data[$catid]['catdir']);
			  $this->data[$cid]['parentdir']=$this->data[$this->data[$cid]['parentid']]['parentdir'].$this->data[$this->data[$cid]['parentid']]['catdir'].'/';
			  if(!substr_count($this->data[$cid]['url'], 'http://')) $this->data[$cid]['url'] = $this->data[$this->data[$cid]['parentid']]['url'].$this->data[$cid]['catdir'].'/';
			  updatetable($this->table, array('arrparentid'=>"{$this->get_asort($this->data[$cid]['arrparentid'])}",'parentdir'=>"{$this->data[$cid]['parentdir']}",'url'=>"{$this->data[$cid]['url']}"), "catid={$cid}");
			}
		}//echo '重新构建该目录下子目录相关所有父目录配置<br><br>';
		//重新构建该目录相关所有旧目录的arrchildid设置
		if($oldparentid){
			$oldparentids = explode(',', $cate['arrparentid']);
			//$arrchildids = explode(',', $cate['arrchildid']);
			$arrparentids = explode(',', $parentcate['arrparentid'].",$parentid");//新栏目的上级目录
			foreach($oldparentids as $oid){
			    if(!$oid || !$this->checkdata($oid, true)) continue;
				//如果要转移的目录本来就存在于新栏目的上级目录中，则不必去掉
				if(in_array($oid,$arrparentids)){ //$oldarrchildid = implode(',', explode(',', $this->data[$oid]['arrchildid']));
				}else{ $oldarrchildid = implode(',', array_diff(explode(',', $this->data[$oid]['arrchildid']), $arrchildids));
				
				if(!substr_count($oldarrchildid, ',')) $child = 0;
				else  $child = 1;

				updatetable($this->table, array('arrchildid'=>"{$this->get_asort($oldarrchildid)}", 'child'=>$child), "catid={$oid}");}
			}
		}//echo '重新构建该目录相关所有旧目录的arrchildid设置<br><br>';
		//重新构建该目录相关所有新目录的arrchildid设置
		if($parentid){
		    $this->data[$catid]['arrparentid'] = $parentcate['arrparentid'].",$parentid";
		    $parentids = explode(',', $this->data[$catid]['arrparentid']);
			$arrparentids = explode(',', $cate['arrparentid']);//旧栏目的上级目录
			foreach($parentids as $pid){
			    if(!$pid || !$this->checkdata($pid, true)) continue;
				//如果要转移的目录本来就存在于旧栏目的上级目录中，则不必增加
				if(in_array($pid,$arrparentids)){ //$newarrchildid = $this->data[$pid]['arrchildid'];
				}else{ $newarrchildid = $this->data[$pid]['arrchildid'].','.$cate['arrchildid'];
				updatetable($this->table, array('arrchildid'=>"{$this->get_asort($newarrchildid)}", 'child'=>1), "catid={$pid}");}
			}
		}else{
				$this->data[$catid]['parentdir'] = '';
				$this->data[$catid]['arrparentid'] = 0;
		}
		updatetable($this->table, array('arrparentid'=>"{$this->data[$catid]['arrparentid']}", 'parentdir'=>"{$this->data[$catid]['parentdir']}", 'url'=>"{$this->data[$catid]['url']}"), "catid={$catid}");
		//echo '重新构建该目录相关所有新目录的arrchildid设置<br><br>';
	}
	
	/**
	 * 栏目重新排序
	 * 
	 * @access     public
	 * @return     bool
	 */	
	function order($order){
	      if(!is_array($order)) return false;
		  foreach($order as $catid=>$value){
		      $value = intval($value);
			  updatetable($this->table, array('listorder'=>$value), "catid={$catid}");
		  }
		  $this->cache();//更新缓存
		  return true;
	}
	
	/**
	 * 格式化栏目数据
	 * 
	 * @access     public
	 * @return     array
	 */	
	 function get_format($catid = 0){
			global $_SGLOBAL;
			include_once($GLOBALS['jieqiModules']['news']['path'].'/include/tree.class.php');
			if($this->data){
				$Tree = new Tree(NULL);
				foreach($this->data as $k=>$value){
					$Tree->setNode($value['catid'], $value['parentid'], $value['catname']);
				}
				$category = $Tree->getChilds($catid);
				array_splice($category,0,1);
				foreach ($category as $key=>$id){
					$_SGLOBAL['catelist'][$id] = $this->get($id);
					$_SGLOBAL['catelist'][$id]['layer'] = $Tree->getLayer($id, array('','├','└','└','└','└','└','└','└','└','└'));
					$_SGLOBAL['catelist'][$id]['url_catelist'] = $this->getUrl($_SGLOBAL['catelist'][$id]['catid']);
				}
			}else $_SGLOBAL['catelist'] = array();
			return $_SGLOBAL['catelist'];
	 }
	
	/**
	 * 格式化栏目数据
	 * 
	 * @access     public
	 * @return     array
	 */	
	 function getParentCat($parentid = 0){
	     if($this->data){
		     $ret = array();
		     foreach($this->data as $k=>$value){
			     if($value['parentid']==$parentid){
				     $ret[$k] = $value;
					 if($value['child']){
					    $ret[$k]['currentlist'] = $this->getParentCat($value['catid']);
					 }
				 }
			 }
			 return $ret;
		 }else return array();
	 }
	 	 	
	/**
	 * 字符串排序
	 * 
	 * @access     public
	 * @return     string
	 */	
	 function get_asort($string){
			$strarray = explode(',', $string);
			asort($strarray);
			return implode(',', $strarray);
	 }
	 
	/**
	 * 在某个栏目上级节点中获取某一个节点下的所有上级节点
	 * 
	 * @access     public
	 * @return     string
	 */
	function get_arrparentid($cid, $splitcatid = '')
	{
		return strchr($this->data[$cid]['arrparentid'],$splitcatid);
	}
	
	/**
	 * 在某个栏目上级节点中获取某一个目录下的所有上级目录
	 * 
	 * @access     public
	 * @return     string
	 */
	function get_parentdir($cid, $splitcatdir = '')
	{
		return strchr($this->data[$cid]['parentdir'],$splitcatdir);
	}
	
	/**
	 * 输出栏目内容
	 * 
	 * @access     public
	 * @return     string
	 */
	function fetch($catid, $page = 1){
		global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiTpl, $_PAGE, $_OBJ;
		//初始化栏目操作对像和加载栏目数据列表
		//if(!is_object($_OBJ['category'])) $_OBJ['category'] = new Category();
		//获取栏目内容
		if(!($_SGLOBAL['cate'] = $this->get($catid, true))) return false;
		//加载模型
		if(!is_object($_OBJ['model'])) $_OBJ['model'] = new Model();
		if($_SGLOBAL['model'][$_SGLOBAL['cate']['modelid']]['disabled']) return false;
		
		if($this->islist($catid)){
			//获取分类文章列表
			$content = & new Content();
			
			//构造查询表
			$tables = array();
			//if($_SCONFIG['showarticlelists'] & 1) $tables[$content->tablepre.'c_'.$_SGLOBAL['model'][$_SGLOBAL['cate']['modelid']]['tablename']] = '*';  //文章附加表表名
			if($_SCONFIG['showarticlelists'] & 2) $tables[$content->table_count] = 'hits,hits_day,hits_week,hits_month,hits_time,comments,comments_checked'; //文章统计表表名
			if($_SCONFIG['showarticlelists'] & 4) $tables[$content->table_digg] = 'supports,againsts,supports_day,againsts_day,supports_week,againsts_week,supports_month,againsts_month'; //文章DIGG表表名
			
			$tag = $tables ? 'tables' : NULL;
			$tabletag = ($tag ?'news_content.' :'');
			$content->setHandler($tag);
			
			if($tables){
				$tablestr = $fields = '';
				foreach($tables as $table=>$field){
					$tablestr.= " LEFT JOIN ".jieqi_dbprefix($table)." AS {$table} ON {$tabletag}contentid={$table}.contentid ";
					$fields.= ",{$table}.{$field}";
				}
				$content->criteria->setFields("{$tabletag}*{$fields}");
				$content->criteria->setTables(jieqi_dbprefix('news_content')." AS news_content {$tablestr}");
			}
			
			$content->criteria->add(new Criteria('catid', $catid));
			$content->criteria->add(new Criteria('status', 99));
			$content->criteria->setSort("{$tabletag}contentid");
			$content->criteria->setOrder('DESC');
			$pagesize = $this->getPagenum($catid);
			$page = $page<1 ? 1 : $page;
			
			$emptyonepage = true;
			$showtype = $this->showType($catid);
			if(!$showtype){
			    $link = '';
			    $emptyonepage = false;
			}else{
			    if($showtype==1) $emptyonepage = false;
			    $link = $this->getUrl($catid, 1, false);
			}
			$_PAGE['articlerows'] = $content->lists($pagesize, $page, $_SCONFIG['categorypages'], $emptyonepage);//print_r($_PAGE['articlerows']);
			//在生成静态的时候，如果设置了最大更新页数
			$totalcount = $content->getVar('totalcount');
			$totalpage = ceil($totalcount/$pagesize);
			if($this->isHtml($catid) && $_SCONFIG['maxpage'] && $totalpage>=$_SCONFIG['maxpage']){
			   $content->jumppage->setVar('totalpage', $_SCONFIG['maxpage']);
			}
			$_PAGE['url_jumppage'] = $content->getPage($link);
		} else $_SGLOBAL['subcate'] = $this->get_format($catid);//子栏目
		$_PAGE['ac'] = 'list';
		template($this->getTemplate($catid));
		$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']));
		if(!is_file($jieqiTset['jieqi_page_template']) && strpos($jieqiTset['jieqi_page_template'], '/') == 0) $jieqiTset['jieqi_page_template']=_ROOT_.$jieqiTset['jieqi_page_template'];
		$str = $jieqiTpl->fetch($jieqiTset['jieqi_page_template']);
		unset($jieqiTset['jieqi_contents_template']);
		jieqi_freeresource();
		return $str;
	}
}
?>