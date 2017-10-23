<?php
/**
 * 用户中心->收入月报
 * @author zhuyunlong  2014-6-19
 *
 */
class incomeModel extends Model{

	/**
	 * 新增财务信息修改申请
	 * @param unknown $ueid
	 */
	public function addEditApply($ueid){
		//申请时间，申请人uid，审核时间，审核状态，
		$this->db->init('usersext','ueid','system');
		if($userext = $this->db->get($ueid)){
			$auth = $this->getAuth();
			$usersextapply = array();
			$usersextapply['applyuid']=$auth['uid'];
			$usersextapply['applyuname']=$auth['username'];
			$usersextapply['applydate']=JIEQI_NOW_TIME;
			// 			$usersextapply['auditdate']='';
			// 			$usersextapply['audituid']='';
			// 			$usersextapply['audituname']='';
			$usersextapply['state']=0;//0待审，1通过，2拒绝
			$this->db->init('usersextapply','ueaid','system');
			if($ueaid = $this->db->add($usersextapply)){
				//userext 同步申请记录id
				$this->db->init('usersext','ueid','system');
				$this->db->edit($ueid,array("ueaid"=>$ueaid));
				$this->msgbox();
			}else{
				$this->printfail(LANG_DO_FAILURE);
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	
	}
	/**
	 * 修改用户财务信息
	 * @param unknown $params
	 */
	public function editFinance($params){
		$this->db->init('usersext','ueid','system');
		if($userext = $this->db->get($params['ueid'])){
			$userext["payee"]=$params['payee'];
			$userext["sid"]=$params['sid'];
			$userext["address"]=$params['address'];
			$userext["communication"]=$params['communication'];
			$userext["bankaddress"]=$params['bankaddress'];
			$userext["banknumber"]=$params['banknumber'];
			$userext["ueaid"]=0;//申请记录
			if($this->db->edit($params['ueid'],$userext)){
				$this->jumppage($this->geturl("article","article","SYS=method=finance"), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
			}else{
				$this->printfail(LANG_DO_FAILURE);
			}
		}else{
			$this->printfail(LANG_DO_FAILURE);
		}
	}
	/**
	 * 新增登陆用户财务信息
	 * @param unknown $params
	 */
	public function addFinance($params){
		$auth = $this->getAuth();
		$userext = array(
				"uid"=>$auth['uid'],
				// 				"uname"=>$auth['uname'],
				"payee"=>$params['payee'],
				"sid"=>$params['sid'],
				"address"=>$params['address'],
				"communication"=>$params['communication'],
				"bankaddress"=>$params['bankaddress'],
				"banknumber"=>$params['banknumber'],
		);
		$this->db->init('usersext','ueid','system');
		if($this->db->add($userext)){
			$this->jumppage($this->geturl("article","article","SYS=method=finance"), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
		}else{
			$this->printfail(LANG_DO_FAILURE);
		}
	}
	/**
	 * 财务信息
	 * <p>
	 * uersext中记录作者财务信息
	 * @param unknown $params
	 */
	public function finance(){
		$date = array();
		$auth = $this->getAuth();
		if($auth['uid']>0){
			$this->db->init('usersext','ueid','system');
			$this->db->setCriteria(new Criteria('uid',$auth['uid']));
			$this->db->queryObjects();
			$userext=$this->db->getObject();
			if(is_object($userext)){
				//对象转数组
				foreach($userext->getVars() as $k=>$v){
					$date['userext'][$k] = $userext->getVar($k,'n');
				}
				//申请修改记录
				if($date['userext']['ueaid']){//有申请修改记录
					$this->db->init('usersextapply','ueaid','system');
					$edit_apply = $this->db->get($date['userext']['ueaid']);
					$date['userext']['apply']=$edit_apply;
				}
			}
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		return $date;
	}
	/*
	*收入月报
	*/
	public function income($params){
		$auth = $this->getAuth();
		if (!$params['page']) $params['page'] = 1;
		$daystart = $this->getTime();
		$weekstart = $this->getTime('week');
		$monthstart = $this->getTime('month');
		$data= array();
		$book =  $this->load('article','article');
		$data['articlerows'] =  $book->articleByAuthorid($auth['uid']);
		$this->db->init('stat','statid','article');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('s.mid','sale'));
		$this->db->criteria->add(new Criteria('a.authorid',$auth['uid']));
		$this->db->criteria->setTables($this->dbprefix('article_stat')." AS s left JOIN ".$this->dbprefix('article_article')." AS a ON a.articleid = s.articleid");
		$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showfriend(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showfriend(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showfriend(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage] <em class="pr10">共{$page}/{$totalpage}页</em>';
		$data['incomerows'] = $this->db->lists(10,$params['page'],$p);
		$k=0;
		foreach($data['incomerows'] as $k=>$v)
		{
			if($daystart >= $v['lasttime']) $data['incomerows'][$k]['day'] = 0;
			if($weekstart >= $v['lasttime']) $data['incomerows'][$k]['week'] = 0;
			if($monthstart >= $v['lasttime']) $data['incomerows'][$k]['month'] = 0;
			$k++;
		}
		$data['totalcount'] = $this->db->getVar('totalcount');
		$data['articlecount'] = count($data['articlerows']);
		$data['incomecount'] = count($data['incomerows']);
		$jumppage = new GlobalPage($p,$data['totalcount'],10,$params['page']);
		$data['url_jumppage'] = $this->db->getPage($this->getUrl('article','article','method=income','evalpage=0','SYS=mid='.$params['mid']));
		$data['months'] = $params['months'];
		$data['years'] = $params['years'];
		return $data;
	}
	
	/*
	*奖励保障
	*/
	public function rewards($params){
		$auth = $this->getAuth();
	}
	
	/*
	*打赏明细
	*/
	public function exceptional($params){
		$auth = $this->getAuth();
		if(!$params['page']) $params['page'] = 1;
		if(!$params['months'])$params['months'] = 0;
		if(!$params['years']) $params['years'] = 2014;
		$data= array();
		$book =  $this->load('article','article');
		$data['articlerows'] =  $book->articleByAuthorid($auth['uid']);
		if(!empty($data['articlerows'])){
			if(!$params['cars']) $params['cars'] = $data['articlerows'][0]['articleid'];
		}
		if(count($data['articlerows']) > 0){	//她的作品数小于1，不执行，否则执行查询
			if($params['months'] > 0){
				$lastday = $this->get_cmonth_lastday($params['years'],$params['months']);	//当前月最后一天的日期
				$mktfirst = mktime(0,0,0,$params['months'],1,$params['years']);			//把当前月第一天的日期转时间戳
				$mktlast = mktime(0,0,0,$params['months'],$lastday,$params['years']);		//把当前月最后一天的日期转时间戳
			}else{
				$lastday = $this->get_cmonth_lastday($params['years'],12);	//当前年最后一天的日期
				$mktfirst = mktime(0,0,0,1,1,$params['years']);			//把当前年第一天的日期转时间戳
				$mktlast = mktime(0,0,0,12,$lastday,$params['years']);		//把当前年最后一天的日期转时间戳
			}
			$this->db->init('statlog','statlogid','article');	//查询当前书的打赏信息
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('s.mid','reward'));
			$this->db->criteria->add(new Criteria('s.articleid',$params['cars'] ? $params['cars'] : $data['articlerows'][0]['articleid']));
			$this->db->criteria->add(new Criteria('addtime',$mktfirst,'>='));
			$this->db->criteria->add(new Criteria('addtime',$mktlast,'<='));
			$this->db->criteria->setTables($this->dbprefix('article_statlog')." AS s left JOIN ".$this->dbprefix('article_article')." AS a ON a.articleid = s.articleid");
			$sql='SELECT SUM(stat) as stat FROM jieqi_article_statlog where articleid = '.$params['cars'].' AND mid = "reward"';
			$res = $this->db->query($sql);
			$row = $this->db->getRow ($res);
			$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showfriend(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showfriend(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showfriend(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage] <em class="pr10">共{$page}/{$totalpage}页</em>';
			$data['queryrows'] = $this->db->lists(10,$params['page'],$p);
			$data['totalcount'] = $this->db->getVar('totalcount');
			$data['articlecount'] = count($data['articlerows']);
			$data['querycount'] = count($data['queryrows']);
			$jumppage = new GlobalPage($p,$data['totalcount'],10,$params['page']);
			$data['url_jumppage'] = $this->db->getPage($this->getUrl('article','article','method=exceptional','evalpage=0','SYS=mid='.$params['mid']));
			$data['months'] = $params['months'];
			$data['years'] = $params['years'];
			$data['stat'] = $row['stat'];
		}
		return $data;
	}
		
	/*
	*渠道收入
	*/
	public function channelIncome($params){
		$auth = $this->getAuth();
	}
	
	public function queryex($params){
		$auth = $this->getAuth ();
		
	}
	
	public function get_cmonth_lastday($year,$month){
		$days_in_month	= array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
		
		if ($month < 1 OR $month > 12)
		{
		return 0;
		}
		
		// Is the year a leap year?
		if ($month == 2)
		{
		if ($year%400 == 0 OR ($year%4 == 0 AND $year%100 != 0))
		{
		return $year.'-'.$month.'-29';
		}
		}
		
		return $days_in_month[$month - 1];
	}
	
	public function incomedetail($params){
		$auth = $this->getAuth ();
		if(!$params['months'])$params['months'] = 0;
		if(!$params['years']) $params['years'] = 2014;

		if($params['months'] > 0){
			$lastday = $this->get_cmonth_lastday($params['years'],$params['months']);	//当前月最后一天的日期
			$mktfirst = mktime(0,0,0,$params['months'],1,$params['years']);			//把当前月第一天的日期转时间戳
			$mktlast = mktime(0,0,0,$params['months'],$lastday,$params['years']);		//把当前月最后一天的日期转时间戳
		}else{
			$lastday = $this->get_cmonth_lastday($params['years'],12);	//当前年最后一天的日期
			$mktfirst = mktime(0,0,0,1,1,$params['years']);			//把当前年第一天的日期转时间戳
			$mktlast = mktime(0,0,0,12,$lastday,$params['years']);		//把当前年最后一天的日期转时间戳
		}
		$data= array();
		$book =  $this->load('article','article');
		$data['articlerows'] =  $book->articleByAuthorid($auth['uid']);
		if(!empty($data['articlerows'])){
			if(!$params['articleid']) $params['articleid'] = $data['articlerows'][0]['articleid'];
		}
		$this->db->init('sale','saleid','article');	//查询当前书的打赏信息
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('articleid',$params['articleid']));
		$this->db->criteria->add(new Criteria('buytime',$mktfirst,'>='));
		$this->db->criteria->add(new Criteria('buytime',$mktlast,'<='));
		$sql='SELECT SUM(saleprice) as saleprice FROM jieqi_article_sale where articleid = '.$params['articleid'];
		$res = $this->db->query($sql);
		$row = $this->db->getRow ($res);
		$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showfriend(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showfriend(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showfriend(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage] <em class="pr10">共{$page}/{$totalpage}页</em>';
		$data['incomerows'] = $this->db->lists(10,$params['page'],$p);
		$data['totalcount'] = $this->db->getVar('totalcount');
		$data['articlecount'] = count($data['articlerows']);
		$data['incomecount'] = count($data['incomerows']);
		$jumppage = new GlobalPage($p,$data['totalcount'],10,$params['page']);
		$data['url_jumppage'] = $this->db->getPage($this->getUrl('article','article','method=incomedetail','evalpage=0','SYS=mid='.$params['mid']));
		$data['months'] = $params['months'];
		$data['years'] = $params['years'];
		$data['articleid'] = $params['articleid'];
		$data['saleprice'] = sprintf("%1\$.2f",$row['saleprice']);
		return $data;
	}
	
}
?>