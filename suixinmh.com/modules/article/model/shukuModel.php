<?php
/**
 * 分类列表模型
 * @author zhangxue
 *
 */
class shukuModel extends Model{
	/**
	 * 查询条件的默认值0，则表示不添加此查询条件。
	 * @var unknown
	 */
	public $default_v = 0;
	/**
	 * 装载查询条件,统一数据格式,支持五种查询条件分别是：分类，字数，进度，方式，状态
	 * @param unknown $data
	 */
	private function container(&$data){
		$size = array(
				$this->default_v=>array('text'=>'全部'),
				1=>array('text'=>'30万以下'),
				2=>array('text'=>'30万-50万'),
				3=>array('text'=>'50万-100万'),
				4=>array('text'=>'100万-200万'),
				5=>array('text'=>'200万以上'),
		);
		$operate = array(
				$this->default_v=>array('text'=>'默认'),
				1=>array('text'=>'总字数'),
				2=>array('text'=>'周点击'),
				3=>array('text'=>'月点击'),
				4=>array('text'=>'总点击'),
				5=>array('text'=>'周收藏'),
				6=>array('text'=>'月收藏'),
				7=>array('text'=>'总收藏'),
				8=>array('text'=>'总销售')
		);
		$free = array(
				$this->default_v=>array('text'=>'不限'),
				1=>array('text'=>'只看免费'),
				2=>array('text'=>'只看VIP')
		);
		$articleLib = $this->load('article','article');//加载文章处理类
		$sdata = $articleLib->getSources();
		array_unshift($sdata['fullflag']['items'],'全部');//0全部 1连载中 2已完成
		//重新组装fullflag格式
		foreach($sdata['fullflag']['items'] as $k=>$item){
			$data['fullflag'][$k] = array('text'=>$item);
		}
// 		array_unshift($sdata['sortrows'],array('shortcaption'=>'全部'));//0全部 1连载中 2已完成
// 		$sdata['sortrows'] = array(0=>array('shortcaption'=>'全部'))+$sdata['sortrows'];
		$sdata['sortrows'] = array(0=>array('shortcaption'=>'全部','caption'=>'全部'))+$sdata['sortrows'];
		$data['sort'] = $sdata['sortrows'];
		$data['channel'] = $sdata['channel']; 
		$data['size'] =$size;
		$data['operate'] =$operate;
		$data['free'] =$free;
		
		//女频添加标签查询功能
		if(JIEQI_MODULE_NAME == 'mm'){
			$tagObj = $this->model('tag','article');
			$data['tag'] = array(0=>array('id'=>'0','name'=>'全部'))+$tagObj->getTagByModule();
		}
	}
	/**
	 * 验证查询参数的有效性
	 * @param unknown $params	引用传递
	 * 2014-7-18 上午9:47:56
	 */
	private function validate(&$params = array()){
		$default_v = 0;
		$query_data = array();
		$this->container($query_data);//装载合法的查询条件

		if(!array_key_exists($params['sort'],$query_data['sort'])){
			$params['sort'] = $default_v;
		}
		if(JIEQI_MODULE_NAME == 'mm' && !array_key_exists($params['tag'],$query_data['tag'])){
			$params['tag'] = $default_v;
		}
		if(!array_key_exists($params['size'],$query_data['size'])){
			$params['size'] = $default_v;
		}
		if(!array_key_exists($params['fullflag'],$query_data['fullflag'])){
			$params['fullflag'] = $default_v;
		}
		if(!array_key_exists($params['operate'],$query_data['operate'])){
			$params['operate'] = $default_v;
		}
		if(!array_key_exists($params['free'],$query_data['free'])){
			$params['free'] = $default_v;
		}
		if(!$params['listnum'] || ($params['listnum'] != 40 && $params['listnum'] != 50)){
			if (JIEQI_MODULE_NAME == 'wap' || JIEQI_MODULE_NAME == '3gwap' || JIEQI_MODULE_NAME == '3g'){
				$params['listnum'] = 15;
			} else {
				$params['listnum'] = 50;
			}
		}
		if(!$params['page'] || $params['page'] < 1) $params['page'] = 1;
		//on_list on_img
		if(!$params['topview'])$params['topview'] = 'on_list';
		return $query_data;
	}

	public function query($params = array()){
		global $jieqiModules;
		$data = $this->validate($params);
// 		$data = array();
// 		$this->container($data);
		$this->addConfig ( 'article', 'configs' );
		$jieqiConfigs ['article'] = $this->getConfig ( 'article', 'configs' );	//文章配置

		$package = $this->load('article','article');
		$this->db->init('article','articleid','article');
// 		$this->db->setCriteria(new Criteria ( 'a.siteid',JIEQI_SITE_ID, '=' ));
		$this->db->setCriteria();
		$this->db->criteria->add ( new Criteria ( 'a.display',0 ));
		$this->db->criteria->add ( new Criteria ( 'a.firstflag',13, '<>' ));
		$this->db->criteria->setSort('a.lastupdate');
		if($params['operate'] > 1){
			//联合查询
			$this->db->criteria->setTables($this->dbprefix('article_stat').' s RIGHT JOIN '.$this->dbprefix('article_article').' a ON s.articleid=a.articleid');
			$this->db->criteria->setFields('a.*,s.mid,s.total,s.month,s.week,s.day,s.lasttime');
		}else{
			//单表查询
			$this->db->criteria->setTables( $this->dbprefix('article_article').' a ');
			$this->db->criteria->setFields('a.*');
		}
		if($params['sort']){
			//指定分类，不同的频道显示当前频道的分类
			$this->db->criteria->add(new Criteria('a.sortid',$params['sort'], '='));
		}
		if(JIEQI_MODULE_NAME == 'mm' && $params['tag']){
			//女频指定标签
			$this->db->criteria->add(new Criteria('FIND_IN_SET('.$params['tag'], '',',tag)'));
		}
		//通过JIEQI_MODULE_NAME指定频道，如果没有则使用main主站频道
		//类别
		if(array_key_exists(JIEQI_MODULE_NAME,$jieqiModules)){
			if (JIEQI_MODULE_NAME != 'wap' && JIEQI_MODULE_NAME != '3gwap' && JIEQI_MODULE_NAME != '3g' && JIEQI_MODULE_NAME != 'overseas'){
				$siteid = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
				//$this->db->criteria->add(new Criteria('a.siteid',$jieqiModules[JIEQI_MODULE_NAME]['siteid']));
			} else{
				if (isset($params['siteid'])){
					$siteid = $params['siteid'];
					//$this->db->criteria->add(new Criteria('a.siteid',$params['siteid']));
				}
			}
		}else{
			$siteid = $jieqiModules['system']['siteid'];
			//$this->db->criteria->add(new Criteria('a.siteid',$jieqiModules['system']['siteid']));
		}

		if (isset($siteid)){
			$this->db->criteria->add(new Criteria ( 'siteid',$siteid, '=' ));
		}

// 		if(JIEQI_MODULE_NAME == 'wenxue'){
// 			//文学频道
// 			$this->db->criteria->add(new Criteria('a.siteid','200'));
// 		}elseif(JIEQI_MODULE_NAME == 'girl'){
// 			//女生频道
// 			$this->db->criteria->add(new Criteria('a.siteid','100'));
// 		}else{
// 			//男生频道-主站
// 			$this->db->criteria->add(new Criteria('a.siteid',$channels['main']['siteid']));
// 		}
// 		if($params['sort']) $this->db->criteria->add(new Criteria('a.sortid',$params['sort'], '='));
		//字数
		if($params['size']){
			if($params['size'] == 1){
				$this->db->criteria->add(new Criteria('a.size',300000*2, '<'));
			}elseif($params['size'] == 2){
				$this->db->criteria->add(new Criteria('a.size',300000*2, '>='));
				$this->db->criteria->add(new Criteria('a.size',500000*2, '<='));
			}elseif($params['size'] == 3){
				$this->db->criteria->add(new Criteria('a.size',500000*2, '>='));
				$this->db->criteria->add(new Criteria('a.size',1000000*2, '<='));
			}elseif($params['size'] == 4){
				$this->db->criteria->add(new Criteria('a.size',1000000*2, '>='));
				$this->db->criteria->add(new Criteria('a.size',2000000*2, '<='));
			}elseif($params['size'] == 5){
				$this->db->criteria->add(new Criteria('a.size',2000000*2, '>'));
			}
		}else{
			$this->db->criteria->add(new Criteria('a.size',0, '>'));
		}
		//进度
		if($params['fullflag'] == 1)$this->db->criteria->add(new Criteria('a.fullflag',0, '='));//连载
		elseif($params['fullflag'] == 2)$this->db->criteria->add(new Criteria('a.fullflag',1, '='));//已完成
		//排序方式
		if($params['operate'] == 1)$this->db->criteria->setSort('a.size');
		elseif ($params['operate'] == 2 ){
			$this->db->criteria->add(new Criteria('s.mid','visit', '='));
			$this->db->criteria->add ( new Criteria ( 's.lasttime',$this->getTime('week'), '>=' ));
			$this->db->criteria->setSort('s.week');
		}elseif ($params['operate'] == 3 ){
			$this->db->criteria->add(new Criteria('s.mid','visit', '='));
			$this->db->criteria->add ( new Criteria ( 's.lasttime',$this->getTime('month'), '>=' ));
			$this->db->criteria->setSort('s.month');
		}elseif ($params['operate'] == 4 ){
			$this->db->criteria->add(new Criteria('s.mid','visit', '='));
			$this->db->criteria->setSort('s.total');
		}elseif ($params['operate'] == 5 ){
			$this->db->criteria->add(new Criteria('s.mid','goodnum', '='));
			$this->db->criteria->add ( new Criteria ( 's.lasttime',$this->getTime('week'), '>=' ));
			$this->db->criteria->setSort('s.week');
		}elseif ($params['operate'] == 6 ){
			$this->db->criteria->add(new Criteria('s.mid','goodnum', '='));
			$this->db->criteria->add ( new Criteria ( 's.lasttime',$this->getTime('month'), '>=' ));
			$this->db->criteria->setSort('s.month');
		}elseif ($params['operate'] == 7 ){
			$this->db->criteria->add(new Criteria('s.mid','goodnum', '='));
			$this->db->criteria->setSort('s.total');
		}elseif ($params['operate'] == 8 ){
			$this->db->criteria->add(new Criteria('s.mid','sale', '='));
			$this->db->criteria->setSort('s.total');
		}
		//状态
		if($params['free'] == 1)$this->db->criteria->add(new Criteria('a.articletype',0, '='));
		elseif($params['free'] == 2)$this->db->criteria->add(new Criteria('a.articletype',1, '='));
		$this->db->criteria->setOrder('DESC');


// 		$package = $this->load('article',false);//加载文章处理类
		$data ['articlerows'] = $this->db->lists ($params['listnum'], $params['page'],JIEQI_PAGE_TAG);
		$data ['maxpage'] = ceil($this->db->getVar('totalcount')/$params['listnum']);
		foreach($data ['articlerows'] as $k=>$v){
			$data ['articlerows'][$k] = $package->article_vars($v);
		}
		$prevpage=$params['page']>1?$params['page']-1:1;
		$nextpage=$params['page']<$data['maxpage']?$params['page']+1:$data['maxpage'];
		//url使用相对路径
		if (isset($params['siteid'])){
			$url =  parse_url($this->getUrl(JIEQI_MODULE_NAME,'shuku','evalpage=0','tag='.$params['tag'],'SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$params['page'].'&siteid='.$siteid));
			$url3gwap = parse_url($this->getUrl('article','shuku','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$params['page'].'&siteid='.$siteid));
			$url3gwap_prev = parse_url($this->getUrl('article','shuku','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$prevpage.'&siteid='.$siteid));
			$url3gwap_next = parse_url($this->getUrl('article','shuku','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$nextpage.'&siteid='.$siteid));
		}else{
			$url =  parse_url($this->getUrl(JIEQI_MODULE_NAME,'shuku','tag='.$params['tag'],'evalpage=0','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$params['page']));
			$url3gwap =      parse_url($this->getUrl('article','shuku','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$params['page']));
			$url3gwap_prev = parse_url($this->getUrl('article','shuku','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$prevpage));
			$url3gwap_next = parse_url($this->getUrl('article','shuku','SYS=sort='.$params['sort'].'&size='.$params['size'].'&fullflag='.$params['fullflag'].'&operate='.$params['operate'].'&free='.$params['free'].'&page='.$nextpage));
		}



// 		$data['chapter']['index_page'] = ($this->geturl('article', 'index', 'aid='.$this->id));//basename
		$data ['url_jumppage'] = $this->db->getPage ($url['path']);
		$data['sel_sort'] = $params['sort'];
		$data['sel_tag'] = $params['tag'];
		$data['sel_size'] = $params['size'];
		$data['sel_fullflag'] = $params['fullflag'];
		$data['sel_operate'] = $params['operate'];
		$data['sel_free'] = $params['free'];
		$data['topview'] = $params['topview'];
		$data['path'] = $url3gwap['path'];
		$data['prevpath'] = $url3gwap_prev['path'];
		$data['nextpath'] = $url3gwap_next['path'];
		$data['siteid'] = $params['siteid'];
		$data['page'] = $params['page'];
		//$isnextpage=$params['page'] - ceil($this->db->getVar('totalcount')/$params['listnum']) < 0? "true":"false";
		//选中的类别名称,不包含：全部
		if($data['sel_sort'] != 0){
			$data['sel_sort_name'] =$data['sort'][$data['sel_sort']]['shortcaption'];
			$data['sort_name'] =$data['sort'][$data['sel_sort']]['caption'];
		}
		return $data;
	}
}
?>