<?php 
/**
 * 改造自article下的一个书评模型
 * @author liuxiangbin 
 * @create 2015-03-30 15:24:15
 */
class reviews3gModel extends Model{

	//查询书评
	public function main($params, $pageNum = 20)
	{
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		$this->addConfig('article', 'power');
		$jieqiPower['article'] = $this->getConfig('article','power');
		$canedit = $this->checkpower($jieqiPower['article']['manageallreview'], $this->getUsersStatus(), $this->getUsersGroup(), true );
		$reviewnewLib = $this->load ( 'reviews', 'article');
		$params['limit'] = $pageNum;
		$params['ispage'] = false;
		$temp = $reviewnewLib->queryReviews($params);
		// 重组头像相关的avatar参数
		$data = array();
		$userModel = $this->db->init('users', 'uid', 'system');
		foreach ($temp['reviewrows'] as $k=>$v) {
			// 重组有用参数
			$data[$k]['rid'] = $v['topicid'];
			$data[$k]['posttext'] = $v['posttext'];$data[$k]['isvip'] = $v['isvip'];
			$data[$k]['poster'] = $v['poster'];
			$data[$k]['posttime'] = $v['posttime'];
			$data[$k]['posterid'] = $v['posterid'];
            $data[$k]['replies'] = $v['replies'];
			// 重组头像相关参数
			$userModel->setCriteria();
			$userModel->criteria->add(new Criteria('uid', $v['posterid'], '='));
			$userModel->criteria->setFields('avatar');
			$res = $userModel->lists();
			$data[$k]['avatar'] = $res[0]['avatar'];
		}
		return $data;
	}

	/**
	 * 重组头像参数
	 */
	public function addFace($params, $twoArr = true) {
		$data = array();
		$userModel = $this->db->init('users', 'uid', 'system');
		if ($twoArr) {
			foreach ($params as $k=>$v) {
				// 重组有用参数
				foreach ($v as $key=>$val) {
					$data[$k][$key] = $val;
				}
				// 重组头像相关参数
				$userModel->setCriteria();
				$userModel->criteria->add(new Criteria('uid', $v['posterid'], '='));
				$userModel->criteria->setFields('avatar');
				$res = $userModel->lists();
				$data[$k]['avatar'] = $res[0]['avatar'];
			}
		} else {
			$data = $params;
			$userModel->setCriteria();
			$userModel->criteria->add(new Criteria('uid', $params['posterid'], '='));
			$userModel->criteria->setFields('avatar');
			$res = $userModel->lists();
			$data['avatar'] = $res[0]['avatar'];
		}
		return $data;
	}
	/**
	 * 单个评论及回复页：查询评论
	 */
	public function reviewbyid($params){
		$review = array();
		$this->db->init ( 'replies', 'postid', 'article' );
		$this->db->setCriteria(new Criteria('r.topicid', $params['rid']));
		$this->db->criteria->add(new Criteria('istopic', 1));
		$sqlStr = $this->dbprefix('article_replies')." AS r INNER JOIN ".$this->dbprefix('article_reviews')." AS ar ON r.topicid = ar.topicid LEFT JOIN ".$this->dbprefix('system_users')." AS u ON r.posterid = u.uid";
		$this->db->criteria->setTables($sqlStr);
		$this->db->criteria->setFields("r.*,ar.*,u.avatar");
		$this->db->queryObjects();
		while($v = $this->db->getObject()){//print_r($v);
			$review['poster']=$v->getVar('poster');
			$review['posterid']=$v->getVar('posterid');
			$review['title']=$v->getVar('title');
			$review['posttime']=$v->getVar('posttime');
			$review['posttext']=$v->getVar('posttext');
			$review['articleid']=$v->getVar('ownerid');
			$review['avatar']=$v->getVar('avatar');
		}
		return $review;
	}
}