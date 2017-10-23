<?php
/**
 * 分类管理模型
 * @author zhangxue  2014-9-24
 *
 */
class sortmanageModel extends Model{

	public function main($params = array()){
		$data['sort'] = $this->getsort($params);
		$data['channel'] = $params['channel']?$params['channel']:0;
		return $data;
	}
	//增加分类
	public function addsort($params = array()){//print_r($params);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');//print_r($jieqiSort['article']);
		$this->addLang('article','sort');
		$jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		
		$sort = $this->getsort($params);
		//$siteid = -1;
		$sortid = 0;
		if(!isset($sort) || !is_array($sort)) $sort=array();
		else reset($sort);
		while(list($k, $v) = each($sort)) {
			if($k > $sortid) $sortid = $k;
//			if($v['caption']==$params['caption']){
//				$this->printfail($jieqiLang['article']['caption_repeat']);
//				//$siteid=$k;
//				//break;
//			}
		}
		$sortid++;
		$jieqiSort['article'][$sortid]=array('siteid' =>$params['siteid'], 'layer' =>$params['layer'], 'caption' =>$params['caption'], 'shortname' =>$params['shortname'], 'description'=>$params['description'], 'imgurl' =>$params['imgurl'], 'publish' =>$params['publish'],'shortcaption' =>$params['shortcaption'],'class' =>$params['class']);
		jieqi_setconfigs('sort', "jieqiSort['article']", $jieqiSort['article'], 'article');
		
		$jumpurl = $this->getAdminurl();
		if($params['channel']) $jumpurl =$jumpurl.'&channel='.$params['channel'];
		$this->jumppage($jumpurl, LANG_DO_SUCCESS, $jieqiLang['article']['add_sort_success']);
	}
	//修改分类视图
	public function editsortview($params = array()){//print_r($params);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');//print_r($jieqiSort['article'][$params['sortid']]);
		$data['sort'] = $jieqiSort['article'][$params['sortid']];
		$data['sortid'] = $params['sortid'];
		return $data;
	}
	//修改分类
	public function editsort($params = array()){//print_r($params);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');//print_r($jieqiSort['article']);
		$this->addLang('article','sort');
		$jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
		if(!empty($params['sortid'])){
			$jieqiSort['article'][$params['sortid']]=array('siteid' =>$params['siteid'], 'layer' =>$params['layer'], 'caption' =>$params['caption'], 'shortname' =>$params['shortname'], 'description'=>$params['description'], 'imgurl' =>$params['imgurl'], 'publish' =>$params['publish'],'shortcaption' =>$params['shortcaption'],'class' =>$params['class']);
			jieqi_setconfigs('sort', "jieqiSort['article']", $jieqiSort['article'], 'article');
			$jumpurl = $this->getAdminurl();
			if($params['sortid']>100&&$params['sortid']<=200) $jumpurl .='&channel=100';
			if($params['sortid']>200&&$params['sortid']<=300) $jumpurl .='&channel=200';
			$this->jumppage($jumpurl, LANG_DO_SUCCESS, $jieqiLang['article']['edit_sort_success']);
		}else{
			$this->printfail($jieqiLang['article']['sort_not_exists']);
		}
	}
	//删除分类
	public function delsort($params = array()){//print_r($params);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');//print_r($jieqiSort['article']);
		$this->addLang('article','sort');
		$jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值

		if(!empty($params['sortid'])){
			foreach($jieqiSort['article'] as $k=>$v){
				if($k==$params['sortid']){
					unset($jieqiSort['article'][$k]);
					break;
				}
			}
			jieqi_setconfigs('sort', "jieqiSort['article']", $jieqiSort['article'], 'article');
			$jumpurl = $this->getAdminurl();
			if($params['sortid']>100&&$params['sortid']<=200) $jumpurl .='&channel=100';
			if($params['sortid']>200&&$params['sortid']<=300) $jumpurl .='&channel=200';
			$this->jumppage($jumpurl, LANG_DO_SUCCESS, $jieqiLang['article']['sort_delete_success']);
		}else{
			$this->printfail($jieqiLang['article']['sort_not_exists']);
		}
		
	}
	//获取频道分类
	function getsort($params = array()){//print_r($params);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');//print_r($jieqiSort['article']);
		
		if($params['channel']==100){	//女频
			$start = 100;
			$end = 200;
		}elseif($params['channel']==200){	//文学
			$start = 200;
			$end = 300;
		}else{	//主站
			$start = 0;
			$end = 100;
		}
		for($i=$start; $i<=$end; $i++){
			if($jieqiSort['article'][$i]) $sort[$i] = $jieqiSort['article'][$i];
		}
		return $sort;
	}
}
?>