<?php 
/**
 * 分类文章模型
 * @author zhangxue  
 *
 */
class channelModel extends Model{

	public function main($params = array())
	{
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig ( 'article', 'sort' );	//文章分类配置

		 $this->db->init('article','articleid','article');
		 $rows = $this->sort($params['sortid']);
		 $sortid = $params['sortid'];
		 $sort = $jieqiSort['article'][$sortid]['shortcaption'];
		 return array(
		 	 'rows'=>$rows,
			 'sortid'=>$sortid,
			 'sort'=>$sort
		 );
	} 
	public function sort($sortid){	//按签约时间排序
		 $this->db->setCriteria();
		 $this->db->criteria->add(new Criteria('sortid',$sortid, '='));
		 $this->db->criteria->setSort('signdate');
		 $this->db->criteria->setOrder('DESC');
		 $this->db->criteria->setLimit(21);
		 $this->db->queryObjects($this->db->criteria);
		 $package = $this->load('article',false);//加载文章处理类
		 
		 $k=0;
		 while($v = $this->db->getObject()){
		      $articlerows[$k] = $package->article_vars($v);
			  $k++;
		 }
		 return $articlerows;
	}
}
?>
