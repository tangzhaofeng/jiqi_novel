<?php
/**
 * 财务信息业务模型
 * @author chengyuan
 *
 */
class financeModel extends Model{
	/**
	 * uersext中记录作者财务信息
	 * @param unknown $params
	 * @return multitype:Ambigous <number, unknown>
	 */
	public function main($params){
		$data = array();
		$this->db->init('usersextapply','ueaid','system');
		$this->db->setCriteria();
		if(isset($params['flag'])){
			//0待审 1通过 2拒绝
			$this->db->criteria->add(new Criteria('state',$params['flag']));
		}
		$this->db->criteria->setSort('applydate');
		$this->db->criteria->setOrder('DESC');
		$data['rows'] = $this->db->lists (30, $params['page']);
		$data ['url_jumppage'] = $this->db->getPage ();
		$data['flag']=isset($params['flag'])?$params['flag']:'';
		//财务审核权限
		$this->addConfig('article','power');
		$data['authfinance']=$this->checkpower($this->getConfig('article','power','authfinance'), $this->getUsersStatus (), $this->getUsersGroup (), true);
		return $data;
	}
	/**
	 * 获取指定用户的财务信息
	 * @param unknown $copyrightid
	 */
	public function getFinance($ueid){
		if($ueid){
			$this->db->init('usersext','ueid','system');
			$this->db->setCriteria(new Criteria('uid',$ueid));
			$this->db->queryObjects();
			$userext=$this->db->getObject();
			if(is_object($userext)){
				//对象转数组
				foreach($userext->getVars() as $k=>$v){
					$finance[$k] = $userext->getVar($k,'n');
				}
				$this->msgbox('',$finance);
			}else{
				$this->printfail('该用户未登记财务信息');
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
	/**
	 * 审核作者财务信息修改申请
	 * @param unknown $ueaid 申请记录id
	 * @param unknown $state 状态：1通过，2拒绝
	 */
	public function audit($ueaid,$state){
		//财务审核权限
		$this->addConfig('article','power');
		if($this->checkpower($this->getConfig('article','power','authfinance'), $this->getUsersStatus (), $this->getUsersGroup (), true)){
			if($state == 1 || $state == 2){
				$this->db->init('usersextapply','ueaid','system');
				if($apply = $this->db->get($ueaid)){
					$auth = $this->getAuth();
					$apply['auditdate']=JIEQI_NOW_TIME;
					$apply['audituid']=$auth['uid'];
					$apply['audituname']=$auth['username'];
					$apply['state']=$state;
					if($this->db->edit($ueaid,$apply)){
						//在这里给作者发站内信，通知审核结果
						if($state==1){
							$result = '审核通过';
							// 						$content = '审核通过,您的财务信息修改请求于'.date('Y-m-d H:i',JIEQI_NOW_TIME).'审核通过，请于12小时之内修改财务信息。超过12小时则需要重新申请。';
							$content = '审核通过,您的财务信息修改请求于'.date('Y-m-d H:i',JIEQI_NOW_TIME).'审核通过，请尽快修改财务信息以保证交易正常。';
						}else{
							$result = '审核拒绝';
							$content = '审核失败，拒绝修改';
						}
						$messageModel = $this->model('message','system');
						$messageModel->auditApproval($apply['applyuid'],$apply['applyuname'],'财务信息修改申请结果',$content);
						$this->jumppage('','',$result.'，已发送站内信通知申请人。');
					}else{
						$this->printfail(LANG_DO_FAILURE);
					}
				}else{
					$this->printfail(LANG_ERROR_PARAMETER);
				}
			}else{
				$this->printfail(LANG_ERROR_PARAMETER);
			}
		}else{
			$this->printfail(LANG_NO_PERMISSION);
		}
	}
}
?>