<?php
/**
 * 后台系统管理->模板标签控制器 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
class positionModel extends Model{

	//列表
	public function main($params = array()){
	    //初始化标签对象
		$position = $this->load('position', 'system');
		$_PAGE['ltag'] = '{?';
		$_PAGE['rtag'] = '?}';
		//$this->db->init( 'position', 'posid', 'system' );
		//$position->Database();
		$position->setCriteria();
		$position->criteria->add(new Criteria('siteid', JIEQI_SITE_ID));
		// 初始化查询条件
		$s_name = isset($params['search_name']) ? htmlspecialchars(trim($params['search_name'])) : '';
		$s_type = isset($params['search_ptype']) ? intval($params['search_ptype']) : 0;
		$s_id = isset($params['search_id']) ? intval($params['search_id']) : 0;
		if (''!==$s_name) 
			$position->criteria->add(new Criteria('name', '%'.$s_name.'%', 'LIKE'));
		if (0!==$s_type) 
			$position->criteria->add(new Criteria('ptypeid', $s_type, '='));
        if (0!==$s_id)
            $position->criteria->add(new Criteria('posid', $s_id, '='));
		$position->criteria->setSort('listorder');
		$position->criteria->setOrder('ASC');
		$_PAGE['rows'] = $position->lists(30, $params['page']);
		$_PAGE['url_jumppage'] = $position->getPage();
		return array('_PAGE'=>$_PAGE);
	}
	//删除
	public function del($params = array()){
	    $_OBJ['position'] = $this->load('position', 'system');
		if($_OBJ['position']->delete($params['posid'])) jieqi_jumppage($this->getAdminurl('position'));
		else jieqi_printfail();
	}
	//排序
	public function order($params = array()){
	    $_OBJ['position'] = $this->load('position', 'system');
		if($this->submitcheck()){
			 if($_OBJ['position']->order($_OBJ['position']->order, $params['order'])){
			     //$_OBJ['position']->cache();//更新缓存
			     jieqi_jumppage($this->getAdminurl('position'));
			 }else jieqi_printfail();
		}
	}
	//添加
	public function add($params = array()){
		//include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
		//$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
		$this->db->init( 'blocks', 'bid', 'system' );
		$_OBJ['position'] = $this->load('position', 'system');
		//提交数据
		if($this->submitcheck()){
		//print_r($params);exit;
			 //如果是自定义区块，则优先处理
			 /*if($_REQUEST['setting']['custom']){

				 if($block=$blocks_handler->get($_REQUEST['setting']['bid'])){
					 //自定义内容
					 if($block->getVar('canedit')==1){
						 $block->setVar('content', $_REQUEST['setting']['content']);
					 }
				 }
				 if($blocks_handler->insert($block)){
				   $blocks_handler->saveContent($block->getVar('bid'), $block->getVar('modname'), JIEQI_CONTENT_HTML, $_REQUEST['setting']['content']);
				 }
				 $_REQUEST['setting']['content'] = '';
			 }*/
			 $data = $params['info'];
			 $data['setting'] = ($this->arrayeval($_REQUEST['setting']));
			 $data['ptypeid'] = intval($params['ptypeid']);
			 //addslashes_array
			 //更新数据
			 if($params['posid']){
				 $statu = $_OBJ['position']->edit($params['posid'],$data); //修改
				 $posid = $params['posid'];
			 }else{
				 $data['siteid'] = JIEQI_SITE_ID;
				 $statu = $_OBJ['position']->add($data);//增加
				 $posid = $statu;
			 }
			 //消息
			 if($statu){
				//$_OBJ['position']->cacheOne($posid);
				jieqi_jumppage($this->getAdminurl('position'));
			 } else jieqi_printfail();
		}

		////////////////////////////构造表单//////////////////////////////
		//如果修改状态
		if($params['posid']){
			 //获取修改栏目内容
			 $_SGLOBAL['position'] = $_OBJ['position']->get($params['posid']);
			 //print_r($_SGLOBAL['position']);exit;
		}else{//添加状态
			$_SGLOBAL['position']['type'] = $params['type'];
			if($_SGLOBAL['position']['type']!=2) $_SGLOBAL['position']['setting']['bid'] = $params['bid'];
		}
		if($params['step']){
			//添加数据表单
			//取得设置
			$this->db->setCriteria(new Criteria('custom',0,'='));
			$this->db->criteria->setSort('weight');
			$this->db->criteria->setOrder('ASC');
			$this->db->queryObjects($criteria);
			$blockary = array();
			while($v = $this->db->getObject()){
				$blockary[$k]['bid']=$v->getVar('bid');
				$blockary[$k]['blockname']=$v->getVar('blockname');
				$blockary[$k]['modname']=$v->getVar('modname', 'n');
				//$blockary[$k]['side']=$blocks_handler->getSide($v->getVar('side', 'n'));
				$blockary[$k]['weight']=$v->getVar('weight');
				//$blockary[$k]['weight']=$v->getVar('weight');
				//$blockary[$k]['template']=$blocks_handler->getPublish($v->getVar('template', 'n'));
				$k++;
			}
			$_PAGE['block'] = $blockary;
		}
			if($_SGLOBAL['position']['type']==1){//查询区块
			     $this->db->setCriteria(new Criteria('bid', $_SGLOBAL['position']['setting']['bid']));
				 if(($block = $this->db->get($this->db->criteria))){//echo $_SGLOBAL['position']['setting']['bid'];
					 //$_SGLOBAL['position']['setting'] = array();
					 foreach($block->vars as $k=>$v){
						 if(in_array($k,array('template', 'vars')) && $params['posid']) continue;
						 $_SGLOBAL['position']['setting'][$k] = $block->getVar($k,'n');
					 }
					 //$_SGLOBAL['position']['setting']['filename'] = $block->getVar('filename','n');
					 //$_SGLOBAL['position']['setting']['description'] = $block->getVar('description','n');
					 $_SGLOBAL['position']['setting']['module'] = $block->getVar('modname','n');
				 }
			}

		//设置默认排序权值
		$_SGLOBAL['position']['listorder'] = $_SGLOBAL['position']['listorder'] ?$_SGLOBAL['position']['listorder'] :'0';
		//设置默认模板
		if(!$_SGLOBAL['position']['setting']['template']){
			switch($_SGLOBAL['position']['type']){
				case '2':
					 $_SGLOBAL['position']['setting']['template'] = 'block_content.html';
				break;
			}
		}
		return array('_PAGE'=>$_PAGE,'_SGLOBAL'=>$_SGLOBAL);
	}
}
?>