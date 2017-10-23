<?php
/**
 * 章节目录模型 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class indexModel extends Model{

	public function main($params = array(),$obj = NULL){
		 $package = $this->load('article','article');
		 if(!$package->loadOPF($params['aid'])){
			 $this->addLang('article','article');
			 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
			 $this->printfail($jieqiLang['article']['article_not_exists']);
		 }
		 return $package->showIndex($obj);
	}
/*   function main($params = array()){
		 $this->addConfig('article','configs');
		 $this->addLang('article','article');
		 $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		 $jieqiConfigs['article'] = $this->getConfig('article','configs');
		 $package = $this->load('article','article');//加载文章处理类
		 $package->instantPackage($params['aid']);
		 if(!$package->loadOPF()){
			  if(!$package->article_repack($params['aid'], array('makeopf'=>1))) $this->printfail($jieqiLang['article']['article_not_exists']);
			  //else $package->loadOPF();
		 }
		 $data = $tmp = array();
		 foreach($package->metas as $k=>$v){
			  if($k){
				   $tmp = explode(':',$k);
				   $data['article'][strtolower($tmp[1])] = jieqi_htmlstr($v);
			  }
		 }
		 $data['article'] = $package->article_vars($data['article']);
		 if(isset($jieqiConfigs['article']['indexcols']) && $jieqiConfigs['article']['indexcols']>0) $cols=intval($jieqiConfigs['article']['indexcols']);
		 else $cols=4;
		 $indexrows=array();
		 $i=0;
		 $idx=0;
		 $package->preid=0; //前一章
		 $package->nextid=0; //下一章
		 $preview_page='';
		 $next_page='';
		 $lastvolume='';
		 $lastchapter='';
		 $lastchapterid = $start = $isvip = $lastvolumeorder = 0;
		foreach($package->chapters as $k => $chapter){//处理章节开始
		  if(!$chapter['display']){
				$start++;
				//分卷
				if($chapter['content-type']=='volume' && $start>=1){
						if($i>0) $idx++;
						$i=0;
						$indexrows[$idx]['ctype']='volume';
						$indexrows[$idx]['vurl']='';
						$indexrows[$idx]['vname']=@jieqi_htmlstr($chapter['id']);
						$indexrows[$idx]['vid']=$package->getCid($chapter['href']);
						$indexrows[$idx]['intro'] = @jieqi_htmlstr($chapter['intro']);
						$lastvolume=$chapter['id'];
						$lastvolumeorder = $idx;
						$idx++;
				}else{
						if($start==1){
							 $i=0;
							 $indexrows[$idx]['ctype']='volume';
							 $indexrows[$idx]['vurl']='';
							 $indexrows[$idx]['vname']='正文';
							 $indexrows[$idx]['vid']=0;
							 $indexrows[$idx]['intro'] = '';
							 $lastvolume = $lastvolumeorder = 0;
							 $idx++;
						}
						$k=$k+1;
						if($k < $package->nowid) $package->preid=$k;
						elseif($k > $package->nowid && $package->nextid==0) $package->nextid=$k;
						$tmpvar=$package->getCid($chapter['href']);
						$i++;
						$indexrows[$idx]['ctype']='chapter';
						$indexrows[$idx]['cname'.$i]=jieqi_htmlstr($chapter['id']);
						$indexrows[$idx]['cid'.$i]=$tmpvar;
						if($chapter['isvip'] && !$isvip){
							$isvip = 1;
							$indexrows[$lastvolumeorder]['isvip'] = 1;
						}
						$indexrows[$idx]['isvip'.$i]=$chapter['isvip'];

						if(true){
							$indexrows[$idx]['time'.$i] = $chapter['postdate'];
							$indexrows[$idx]['lastupdate'.$i] = $chapter['lastupdate'];
							$indexrows[$idx]['size'.$i] = $chapter['size'];
							$indexrows[$idx]['size_c'.$i] = ceil($indexrows[$idx]['size'.$i]/2);
						}

						$lastchapter=$chapter['id'];
						$lastchapterid=$tmpvar;
						$indexrows[$idx]['curl'.$i]=$this->geturl('article', 'reader', 'aid='.$package->id,'cid='.$tmpvar);
						if(empty($next_page)) $next_page=$tmpvar;
						$preview_page=$tmpvar;
						if($i==$cols){
							$idx++;
							$i=0;
						}

				  }
			}
		}//处理章节结束
		$data['article']['preview_page'] = $this->geturl('article', 'reader', 'aid='.$package->id,'cid='.$preview_page);
		$data['article']['next_page'] = $this->geturl('article', 'reader', 'aid='.$package->id,'cid='.$next_page);
		$data['indexrows'] = $indexrows;//print_r($indexrows);
		return $data;
	} */
}

?>