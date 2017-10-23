<?php
/**
 * 
 * 评论区版主的业务类
 * @copyright Copyright(c) 2014
 * @author chengyuan
 * @version 1.0
 */
class MyModerator extends Model {
	/**
	 * 指定用户是否是版主
	 * @author chengyuan 2015-6-10 上午10:38:07
	 * @param unknown $aid
	 * @param unknown $uid
	 * @return boolean
	 */
	public function isModerator($aid,$uid){
		$this->db->init('moderator', 'mid', 'article' );
		$this->db->setCriteria(new Criteria('articleid',$aid));
		$this->db->criteria->add(new Criteria('uid',$uid));
		$this->db->criteria->add(new Criteria('state',0));
		return $this->db->getCount($this->db->criteria) ? true : false;
	}
	
	/**
	 * 移除版主
	 * @author chengyuan 2015-6-9 下午5:24:42
	 * @param unknown $mid
	 */
	public function removeModerator($mid){
		$this->db->init('moderator', 'mid', 'article');
		return $this->db->delete($mid);
	}
	/**
	 * 设置为版主业务流程
	 * <p>
	 * 版主数量、是否是版主
	 * <p>
	 * 更新auditdate,state=0
	 * @author chengyuan 2015-6-9 下午3:37:14
	 * @param unknown $mid
	 */
	public function setModerator($aid,$mid){
		if($mid){
			$moderatorInfo = $this->moderatorInfo($aid);
			if(count($moderatorInfo) == 2){
				$this->printfail('每本书限定2名版主。');
			}else{
				$this->db->init('moderator', 'mid', 'article' );
				$apply = $this->db->get($mid);
				if($apply){
					if($apply['uid'] == $moderatorInfo['uid']){
						$this->printfail('当前申请人已经是版主。');
					}else{
						return $this->db->edit($mid,array("auditdate"=>JIEQI_NOW_TIME,"state"=>0));
					}
				}
			}
		}
		return false;
	}
	/**
	 * 批量删除，根据ID数组
	 * @author chengyuan 2015-6-9 下午3:08:09
	 * @param unknown $idsArray
	 * @return boolean
	 */
	public function delByIds($idsArray){
		if(!is_array($idsArray)){
			$idsArray = array($idsArray);
		}
		if(count($idsArray) > 0){
			$this->db->init('moderator', 'mid', 'article' );
			$this->db->setCriteria(new Criteria('mid', '('.implode(',',$idsArray).')', 'IN'));
			return $this->db->delete($this->db->criteria);
		}
		return true;
	}
	/**
	 * 获取书籍的版主信息
	 * @author chengyuan 2015-6-9 上午9:31:00
	 * @param unknown $aid
	 * @return moderatorInfoArr
	 */
	public function moderatorInfo($aid){
		$this->db->init('moderator', 'mid', 'article' );
		$this->db->setCriteria(new Criteria('articleid',$aid));
		$this->db->criteria->add(new Criteria('state',0));
		$moderatorInfoArr = $this->db->lists(2);
		return $moderatorInfoArr;
	}
	public function applyModeratorInfo($aid,$page=0){
		$this->db->init('moderator', 'mid', 'article' );
		$this->db->setCriteria(new Criteria('articleid',$aid));
		$this->db->criteria->add(new Criteria('state',1));
		$data = array();
		$data['applyModerators'] = $this->db->lists(10,$page,JIEQI_PAGE_TAG);
		$data['url_jumppage']=$this->db->getPage();
		return $data;
	}
	/**
	 * 申请版主的业务流程
	 * <p>
	 * 申请版主至针对书海自有的书籍（permission=4）
	 * @author chengyuan 2015-6-8 下午2:13:15
	 * @param unknown $aid
	 */
	public function applyModerator($aid){
		$auth = $this->getAuth();
		if($auth['uid']>0){
			$articleLib = $this->load ( 'article','article' );
			$article = $articleLib->isExists($aid);
			if($article['permission'] == 4){
				$moderatorInfoArr = $this->moderatorInfo($aid);
				//版主数量2名
				if(count($moderatorInfoArr) < 2){
					if(count($moderatorInfoArr) == 1 && $moderatorInfoArr[0]['uid'] == $auth['uid']){
						$this->msgbox('','当前申请人已经是版主。');//当前登录用户是否已经申请了版主
					}else{
						//三天之内只能申请一次版主
						$this->db->init('moderator', 'mid', 'article' );
						$this->db->setCriteria(new Criteria('articleid',$article['articleid']));
						$this->db->criteria->add(new Criteria('uid',$auth['uid']));
						$this->db->criteria->add(new Criteria('applydate',strtotime("-3 days"),'>='));//三天内的申请记录
						$this->db->criteria->add(new Criteria('state',1));
						if($this->db->getCount($this->db->criteria)){
							$this->msgbox('','每3天只能申请一次版主。');
						}else{
							//修改applydate
							$applydateCriteria = $this->db->criteria->criteriaElements[2];
							$applydateCriteria->operator='<';
							$result = false;
							if($this->db->getCount($this->db->criteria)){
								//有三天前的申请记录，更新修改时间
								$this->db->queryObjects($this->db->criteria);
								$applyInfo = $this->db->getObject();
								$result = $this->db->edit($applyInfo->getVar('mid'),array("applydate"=>JIEQI_NOW_TIME));
							}else{
								$moderator = array();
								$moderator['articleid'] = $article['articleid'];
								$moderator['uid'] = $auth['uid'];
								$moderator['uname'] = $auth['username'];
								$moderator['applydate'] = JIEQI_NOW_TIME;
								$moderator['auditdate'] = 0;
								$moderator['state'] = 1;
								$result = $this->db->add($moderator);
							}
							if($result){
								$this->msgbox('','已经通知作者，审核结果将会发送到个人中心站内短息中。');
							}else{
								$this->printfail(LANG_DO_FAILURE);
							}
						}
					}
				}else{
					$this->msgbox('','每本书限定2名版主。');
				}
			}else{
				$this->msgbox('','申请版主，仅针对书海自有书籍。');
			}
		}else{
			$this->printfail('',LANG_NEED_LOGIN);
		}
	}
}
?>