<?php
include_once ($GLOBALS['jieqiModules']['article']['path'] . '/lib/my_article.php');
/**
 * wap模块article自定义类继承自article模块的article类,新增了适用于wap模块的函数
 * @author chengyuan  2014-8-7
 *
 */
class MyArticleWap extends MyArticle {
	/**
	 * 加载目录信息，确保已经调用loadOPF ( $articleid )方法，加载OPF文档
	 * @return Ambigous <multitype:, string>
	 * 2014-8-7 上午11:10:20
	 */
	function getCatalog($order,$page,$pagenum = 23){
		$data = array();
		$data['article'] = $this->formatOPF();
//		$pagenum = 23;

		$totalpage = @ceil($data['article']['chapters']/$pagenum);
		if($totalpage <= 1) $totalpage=1;

		
		$order = strtolower($order);
		if(!$order || !in_array($order, array('asc','desc'))){
			$order = 'asc';//默认正序
		}
		if(!$page || !is_numeric($page) || $page < 1 || $page > $totalpage){
			$page = 1;//默认第一页
		}
		//支持 排序 分页 跳转
		 global $jieqiConfigs,$jieqiLang;
		 if(!isset($jieqiConfigs)){
			 $this->addConfig('article','configs');
			 $jieqiConfigs['article'] = $this->getConfig('article','configs');
		 }
		 if(!isset($jieqiLang)){
			 $this->addLang('article','article');
			 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		 }
// 		 if(isset($jieqiConfigs['article']['indexcols']) && $jieqiConfigs['article']['indexcols']>0) $cols=intval($jieqiConfigs['article']['indexcols']);
// 		 else $cols=4;
		 $cols=1;
		 $indexrows=array();
		 $i=0;
		 $idx=0;
		 $this->preid=0; //前一章
		 $this->nextid=0; //下一章
		 $preview_page='';
		 $next_page='';
		 $lastvolume='';
		 $lastchapter='';
		 $lastchapterid = $start = $isvip = $lastvolumeorder = 0;
		if($order == 'desc'){
			krsort($this->chapters);
		}
		//计算当前页的第一条数据的索引
		$newChapters = array_slice($this->chapters,($page-1)*$pagenum,$pagenum,true);
		foreach($newChapters as $k => $chapter){//处理章节开始
		  if(!$chapter['display']){
				$start++;
				//分卷
				if($chapter['content-type']=='volume' && $start>=1){
						if($i>0) $idx++;
						$i=0;
						$indexrows[$idx]['ctype']='volume';
						$indexrows[$idx]['vurl']='';
						$indexrows[$idx]['vname']=@jieqi_htmlstr($chapter['id']);
						$indexrows[$idx]['vid']=$this->getCid($chapter['href']);
						$indexrows[$idx]['intro'] = @jieqi_htmlstr($chapter['intro']);
						$lastvolume=$chapter['id'];
						$lastvolumeorder = $idx;
						$idx++;
				}else{//章节
// 						if($start==1){
// 							//默认卷-正文
// 							 $i=0;
// 							 $indexrows[$idx]['ctype']='volume';
// 							 $indexrows[$idx]['vurl']='';
// 							 $indexrows[$idx]['vname']='正文';
// 							 $indexrows[$idx]['vid']=0;
// 							 $indexrows[$idx]['intro'] = '';
// 							 $lastvolume = $lastvolumeorder = 0;
// 							 $idx++;
// 						}
// 						$k=$k+1;
// 						if($k < $this->nowid) $this->preid=$k;
// 						elseif($k > $this->nowid && $this->nextid==0) $this->nextid=$k;
						$tmpvar=$this->getCid($chapter['href']);
						$i++;
						$indexrows[$idx]['ctype']='chapter';
						$indexrows[$idx]['cname']=jieqi_htmlstr(trim(preg_replace("/\s+/",'  ',$chapter['id'])));
						$indexrows[$idx]['cid']=$tmpvar;
// 						if($chapter['isvip'] && !$isvip){
// 							$isvip = 1;
// 							$indexrows[$lastvolumeorder]['isvip'] = 1;
// 						}
						$indexrows[$idx]['isvip']=$chapter['isvip'];

						if(true){
							$indexrows[$idx]['time'] = $chapter['postdate'];
							$indexrows[$idx]['lastupdate'] = $chapter['lastupdate'];
							$indexrows[$idx]['size'] = $chapter['size'];
							$indexrows[$idx]['size_c'] = ceil($indexrows[$idx]['size']/2);
						}

						$lastchapter=$chapter['id'];
						$lastchapterid=$tmpvar;
						$indexrows[$idx]['curl']=basename($this->geturl('article', 'reader', 'aid='.$this->id,'cid='.$tmpvar));
						if(empty($next_page)) $next_page=$tmpvar;
						$preview_page=$tmpvar;
// 						if($i==$cols){
							$idx++;
// 							$i=0;
// 						}

				  }
			}
		}//处理章节结束
//		$pagetag = '[prepage]<a href="{$prepage}" target="_self">上页</a>[/prepage][pages][pnum]4[/pnum][pnumchar]<em class="on">{$page}</em>[/pnumchar][pnumurl]<A href="{$pnumurl}" target="_self">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]...<a href="{$nextpage}" target="_self">下页</a>[/nextpage]共<em class="on">{$page}</em>/<em class="px3">{$totalpage}</em>页';
		$jumppage = new GlobalPage(JIEQI_PAGE_TAG,$data['article']['chapters'],$pagenum, $page);
// 		$jumppage->emptyonepage = false;
// 		$totalpage = $jumppage->getVar('totalpage');
		
		$data['url_jumppage'] = $jumppage->getPage($this->geturl(JIEQI_MODULE_NAME,'catalog','evalpage=0','SYS=aid='.$this->id.($order=='desc' ? '&order='.$order : '')));
//		print_r($data);die;
		$data['article']['preview_page'] = basename($this->geturl(JIEQI_MODULE_NAME, 'reader', 'aid='.$this->id,'cid='.$preview_page));
		$data['article']['next_page'] = basename($this->geturl(JIEQI_MODULE_NAME, 'reader', 'aid='.$this->id,'cid='.$next_page));
		$data['indexrows'] = $indexrows;
		$data['order'] = $order;
		return $data;
	}
	/**
	 * 加载章节内容
	 * @param unknown $cid
	 * @return Ambigous <boolean, number>|boolean
	 * 2014-8-12 上午9:51:53
	 */
	function reader($cid){
		define(RETURN_READER_DATA, true);//makeHtml返回标识
		$i=0;//echo $cid;
		$num=count($this->chapters);
		while($i<$num){
			$tmpvar=$this->getCid($this->chapters[$i]['href']);
			if($tmpvar==$cid){
				return $this->makeHtml($i+1, false, true, null);
			}
			$i++;
		}
		return false;
	}
}
?>