<?php 
/**
 * 标签业务模型
 * @author chengyuan  2014-4-24
 *
 */
class tagModel extends Model{
	public function main($params = array()){
		$sel_channel = -1;
		$data = array();
		if(isset($params['channel']) && $params['channel'] > $sel_channel){
			$sel_channel = $params['channel'];
		}
		$articleLib = $this->load('article','article');
		
		$tagCache = $this->load('tag', 'article');
// 		$this->db->init('tag','tagid','article');
		$tagCache->setCriteria();
		$tagCache->criteria->setSort('tagid');
		$tagCache->criteria->setOrder('DESC');
		if($sel_channel > -1){
			$tagCache->criteria->add(new Criteria('FIND_IN_SET('.$sel_channel, '',',siteid)'));
		}
		$data ['rows'] = $tagCache->lists (30, $params['page']);
		$data ['url_jumppage'] = $tagCache->getPage();
		
		$source = $articleLib->getSources();
		$data['channel']=$source['channel'];
		$data['sel_channel']=$sel_channel;
		return $data;
	}
	/**
	 * 获取标签对象
	 * @param unknown $tagid
	 */
	public function getTag($tagid){
		if($tagid){
			$tagCache = $this->load('tag', 'article');
// 			$this->db->init('tag','tagid','article');
			$tag = $tagCache->get($tagid);
			$this->msgbox('',$tag);
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
	
	/**
	 * 获取$module指定站的标签
	 * <p>
	 * 如果$module为空则取当请求的模块
	 * @param unknown $siteid
	 */
	public function getTagByModule($module=null){
		global $jieqiModules;
		if($module == null){
			$module = JIEQI_MODULE_NAME; 
		}
		if($jieqiModules[$module]){
			$siteid = $jieqiModules[$module]['siteid'];
		}
		return array();
	}
	/**
	 * 修改标签
	 * @param unknown $array
	 */
	public function editTag($param=array()){
		$tagid = $param['id'];
		$name = $param['tname'];
		$siteid = $param['channel'];
		if(!$tagid || !$name || empty($siteid)){
			$this->printfail(LANG_DO_FAILURE);
		}
// 		$this->db->init('tag','tagid','article');
		$tagCache = $this->load('tag', 'article');
		if($tag = $tagCache->get($tagid)){
			$tagCache->edit($tagid,array("name"=>$name,"siteid"=>implode(",", $siteid)));
			$this->jumppage($this->getAdminurl('tag'), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
		}else{
			$this->printfail(LANG_DO_FAILURE);
		}
	}
	/**
	 * 添加新标签，支持以|分割标签的批量处理
	 * <p>
	 * 自动过滤掉重复的标签
	 * @param unknown $tag		tag name
	 * @param unknown $channel siteid array
	 */
	public function addTag($tag,$channel=array()){
		if(!$tag || empty($channel)){
			$this->printfail(LANG_DO_FAILURE);
		}
		$tagArr = strpos($tag, "|") ? explode("|",$tag) : array($tag);
		$tagArr = array_unique($tagArr);//去除重复的标签
		$channelStr = implode(",", $channel);
		$tag = array("siteid"=>$channelStr);
// 		$this->db->init('tag','tagid','article');
		$tagCache = $this->load('tag', 'article');
		foreach($tagArr as $k=>$v){
			//确定tag的唯一性
			$tagCache->setCriteria(new Criteria('name', $v));
			$tagCache->queryObjects();
			$tabObj=$tagCache->getObject();
			if(!is_object($tabObj)){
				$tag['name']=trim($v);
				$tagCache->add($tag);
			}
		}
		$this->jumppage($this->getAdminurl('tag'), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
	}
} 
?>