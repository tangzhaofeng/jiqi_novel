<?php
/**
 * 版权业务模型
 * @author chengyuan
 *
 */
class copyrightModel extends Model{
	/**
	 * 版权列表，支持查询功能
	 * @param unknown $params
	 * @return unknown
	 */
	public function main($params){
		$articleLib = $this->load('article','article');//加载文章处理类
		$this->db->init('copyright','copyrightid','article');
		$this->db->setCriteria();
		$this->db->criteria->setTables($this->dbprefix('article_article')." AS a RIGHT JOIN ".$this->dbprefix('article_copyright')." AS c ON a.articleid=c.articleid");
		$this->db->criteria->setFields("c.*,a.articlename,a.authorid,a.author,a.agent");
// 		echo $this->db->returnsql($this->db->criteria);
		if(trim($params["keyword"])){
			if($params["keytype"] == 0){
				$this->db->criteria->add(new Criteria('a.articlename',trim($params["keyword"])));
			}elseif ($params["keytype"] == 1){
				$this->db->criteria->add(new Criteria('a.articleid',trim($params["keyword"])));
			}elseif ($params["keytype"] == 2){
				$this->db->criteria->add(new Criteria('a.author',trim($params["keyword"])));
			}
		}
		if($params["nowagent"]){
			$this->db->criteria->add(new Criteria('a.agentid',$params["nowagent"]));
		}
		if($params["nowauthorize"]){
			$this->db->criteria->add(new Criteria('authorize',$params["nowauthorize"]));
		}
		$this->db->criteria->setSort('copyrightid');
		$this->db->criteria->setOrder('DESC');
		$data ['rows'] = $this->db->lists (30, $params['page']);
		$data ['url_jumppage'] = $this->db->getPage ();
		$data['manageallarticle']=$this->checkpower($articleLib->jieqiPower['article']['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true);
		$data['agents'] = $articleLib->getAgents();
		$data['nowagent'] = $params["nowagent"];
		$data['nowauthorize'] = $params["nowauthorize"];
		$data['keytype'] = $params['keytype'];
		$data['keyword'] = $params['keyword'];
		return $data;
	}
	/**
	 * 修改版权信息
	 * <p>
	 * 编辑功能只有主编以上权限用户才可使用
	 * @param unknown $copyright
	 */
	public function editContract($params){
		$articleLib = $this->load('article','article');//加载文章处理类
		if($this->checkpower($articleLib->jieqiPower['article']['manageallarticle'], $this->getUsersStatus (), $this->getUsersGroup (), true)){
			$this->db->init('copyright','copyrightid','article');
			if($this->db->get ( $params["id"] )){
				$copryright = array(
						"contract"=>$params['contract'],
						"articleid"=>$params['articleid'],
						"realname"=>$params['realname'],
						"authorize"=>$params['authorize'],
						"signagent"=>$params['signagent'],
						"signup"=>$params['signup']
				);
				//授权类型[书海签约1，合作书2，出版物采购3]
				if(!$params['begindate']){
					$this->printfail(sprintf(LANG_NEED_ENTER,"合同开始时间"));
				}
				$copryright["begindate"]=strtotime($params['begindate']);
				if($copryright["authorize"] != 1 && !$params['enddate']){
					$this->printfail(sprintf(LANG_NEED_ENTER,"合同结束时间"));
				}elseif($copryright["authorize"] != 1){
					$copryright["enddate"]=strtotime($params['enddate']);//合作书，出版物采购 合同结束时间必填
				}
				//签约价格明细
				if($copryright['signup'] == 1){//买断签约
					if(!$params['param1'] || !$params['param2'] || !$params['param3']){
						$this->printfail(sprintf(LANG_NEED_ENTER,"签约价格"));
					}
					$copryright["signupprice"]=$params['param1'].'&'.$params['param2'].'&'.$params['param3'];
				}elseif ($copryright['signup'] == 2){//买断全本
					if(!$params['param1']){
						$this->printfail(sprintf(LANG_NEED_ENTER,"签约价格"));
					}
					$copryright["signupprice"]=$params['param1'];
				}elseif ($copryright['signup'] == 3){//分成低保
					if(!$params['param1'] || !$params['param2']){
						$this->printfail(sprintf(LANG_NEED_ENTER,"签约价格"));
					}
					$copryright["signupprice"]=$params['param1'].'&'.$params['param2'];
				}else{
					$copryright["signupprice"]="";
				}
				if($this->db->edit($params["id"],$copryright)){
					$this->jumppage($this->getAdminurl( 'reward'), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
				}else{
					$this->printfail(LANG_DO_FAILURE);
				}
			}else{
				$this->printfail(LANG_ERROR_PARAMETER);
			}
		}else{
			$this->printfail(LANG_NO_PERMISSION);
		}
	}
	/**
	 * 删除版权信息
	 * <p>
	 * 只有管理员才有资格删除权限
	 * @param unknown $params
	 */
	public function deleteContract($copyrightid){
		if(JIEQI_IS_ADMIN){
			if($copyrightid){
				$this->db->init('copyright','copyrightid','article');
				$this->db->delete($copyrightid);
				$this->jumppage('');
			}else{
				$this->printfail(LANG_ERROR_PARAMETER);
			}
		}else{
			$this->printfail(LANG_NO_PERMISSION);
		}

	}
	/**
	 * 保存版权信息
	 * @param unknown $params
	 */
	public function addContract($params){
		$copryright = array(
				"contract"=>$params['contract'],
				"articleid"=>$params['articleid'],
// 				"begindate"=>$params['begindate'],
// 				"enddate"=>$params['enddate'],
				"realname"=>$params['realname'],
				"authorize"=>$params['authorize'],
				"signagent"=>$params['signagent'],
				"signup"=>$params['signup']
// 				"signupprice"=>$params['signupprice'],
		);
		//授权类型[书海签约，合作书，出版物采购]
		if(!$params['begindate']){
			$this->printfail(sprintf(LANG_NEED_ENTER,"合同开始时间"));
		}
		$copryright["begindate"]=strtotime($params['begindate']);
		if($copryright["authorize"] != 1 && !$params['enddate']){
			$this->printfail(sprintf(LANG_NEED_ENTER,"合同结束时间"));
		}elseif($copryright["authorize"] != 1){
			$copryright["enddate"]=strtotime($params['enddate']);//合作书，出版物采购 合同结束时间必填
		}
		//签约价格明细
		if($copryright['signup'] == 1){//买断签约
			if(!$params['param1'] || !$params['param2'] || !$params['param3']){
				$this->printfail(sprintf(LANG_NEED_ENTER,"签约价格"));
			}
			$copryright["signupprice"]=$params['param1'].'&'.$params['param2'].'&'.$params['param3'];
		}elseif ($copryright['signup'] == 2){//买断全本
			if(!$params['param1']){
				$this->printfail(sprintf(LANG_NEED_ENTER,"签约价格"));
			}
			$copryright["signupprice"]=$params['param1'];
		}elseif ($copryright['signup'] == 3){//分成低保
			if(!$params['param1'] || !$params['param2']){
				$this->printfail(sprintf(LANG_NEED_ENTER,"签约价格"));
			}
			$copryright["signupprice"]=$params['param1'].'&'.$params['param2'];
		}else{//分成
			$copryright["signupprice"]="";
		}
		
// 		print_r($copryright);
// 		exit;
		$this->db->init('copyright','copyrightid','article');
		if($this->db->add($copryright)){
			$this->jumppage($this->getAdminurl('reward'), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
		}else{
			$this->printfail(LANG_DO_FAILURE);
		}
	}
	/**
	 * 版权详情
	 * @param unknown $copyrightid 
	 */
	public function getCopyright($copyrightid){
		if($copyrightid){
			$resutl = $this->db->selectsql ("select c.*,a.articlename,a.author,a.agent from ".$this->dbprefix('article_article')." AS a RIGHT JOIN ".$this->dbprefix('article_copyright')." AS c ON a.articleid=c.articleid where c.copyrightid=".$copyrightid);
			if(!$resutl)$this->printfail(LANG_ERROR_PARAMETER);
			$copyright = $resutl[0];
			if($copyright){
				$copyright['authorize_code']=$copyright['authorize'];
				if($copyright['authorize']==1){
					$copyright['authorize']="书海签约";
				}else if($copyright['authorize']==2){
					$copyright['authorize']="合作书";
				}else{
					$copyright['authorize']="出版物采购";
				}
				$copyright['banknumber']=$copyright['banknumber']?$copyright['banknumber']:"";
				$copyright['begindate ']=date("Y-m-d", $copyright['begindate']);
				$copyright['enddate']=$copyright['enddate']?date("Y-m-d", $copyright['enddate']):"";
				$copyright['signup_code']=$copyright['signup'];
				$params = explode("&", $copyright['signupprice']);
				$copyright['signupprice1']=$params[0];
				$copyright['signupprice2']=$params[1];
				$copyright['signupprice3']=$params[2];
				if($copyright['signup']==1){
					$copyright['signup']="买断签约";
					$copyright['signupprice']=sprintf("%s元%s千字%s无线分成",$params[0],$params[1],$params[2]);
				}elseif ($copyright['signup']==2){
					$copyright['signup']="买断全本";
					$copyright['signupprice']=sprintf("%s元每千字",$params[0]);
				}elseif($copyright['signup']==3){
					$copyright['signup']="分成低保";
					$copyright['signupprice']=sprintf("%s万字%s元低保",$params[0],$params[1]);
				}else{
					$copyright['signup']="分成";
					$copyright['signupprice']="";
				}
				$this->msgbox('',$copyright);
			}else{
				$this->addLang('article','article');
				$this->printfail($this->getLang('article','article_not_exists'));
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
	/**
	 * 获取文章信息
	 * @param unknown $aid 文章id
	 */
	public function getArticle($aid){
		if($aid){
			$this->db->init('article','articleid','article');
			$article = $this->db->get ( $aid );
			if($article){
				$this->msgbox('',$article);
			}else{
				$this->addLang('article','article');
				$this->printfail($this->getLang('article','article_not_exists'));
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
}
?>