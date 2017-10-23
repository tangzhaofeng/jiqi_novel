<?php 
/**
 * 用户中心->财务记录
 * @author chengyuan  2014-5-6
 *
 */
class financeModel extends Model{
	/**
	 * 获取当前登录用户的充值记录
	 */
	public function rechargeLog($param){
		if(empty($param['flag'])){
			$param['flag'] = 'all';
		}
		$this->addConfig('pay','paytype');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$data = array();
		$auth = $this->getAuth();
		if($param['flag'] == 'shuquan'){
			$this->db->init('shuquan','sid','system');
			$this->db->setCriteria(new Criteria('uid', $auth['uid']));
			$data['rows'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $param['page'],JIEQI_PAGE_TAG);
			foreach($data['rows'] as $k=>$v){
				$data['rows'][$k] = $v;
			}
		}else{
			$this->db->init('paylog','payid','pay');
			$this->db->setCriteria(new Criteria('buyid', $auth['uid']));
			if($param['flag'] == 'success'){
				$this->db->criteria->add(new Criteria('payflag', 1));
			}
			$this->db->criteria->setSort('payid');
			$this->db->criteria->setOrder('DESC');
			$data['nowmsgnum'] = $this->db->getCount();
			$data['totalegold'] = $this->db->getSum('egold');
			$data ['paylog'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $param['page'],JIEQI_PAGE_TAG);
			foreach($data['paylog'] as $k=>$v){
				$v['money'] = sprintf('%0.2f',$v['money']/100);
				$data['paylog'][$k] = $v;
			}
		}
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,'userhub','evalpage=0','SYS=method=czView&flag='.$param['flag']));
		$data['flag'] = $param['flag'];
		$data['source'] = array(0 => 'VIP升级奖励',1=> '连续签到奖励',2 => '上月消费返还',3=> '参加指定活动',4 => '作者签约赠送');
		$data['maxmsgnum'] = $jieqiConfigs['system']['messagepnum'];
		//支付类型，配置文件
		$data['paytype'] = $this->getConfig('pay','paytype');
		return $data;
	}
	/**
	 * 消费记录，书海币使用记录
	 * @param unknown $param
	 */
    public function pay($param){
        $auth = $this->getAuth();
        $sale_table=sprintf("sale_%02d",$auth['uid']%100);
        $this->db->init($sale_table,'saleid','article');
        $this->db->setCriteria(new Criteria('accountid', $auth['uid']));
// 		$this->db->criteria->add ( new Criteria ( 'mid', '(\'sale\',\'reward\')', 'IN' ));
// 		$this->db->criteria->add ( new Criteria ( 'mid', 'reward', '=' ), 'OR' );

        $q = jieqi_dbprefix('article_'.$sale_table).' c LEFT JOIN '.jieqi_dbprefix('article_chapter').' a ON c.chapterid=a.chapterid';
        $this->db->criteria->setTables($q);
		$this->db->criteria->setFields('c.*,a.articlename,a.chaptername');



        //$p='[prepage]<a rel="nofollow" href="\'{$prepage}\'" >上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="\'{$pnumurl}\'" >{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="\'{$nextpage}\'" >下一页</a>[/nextpage]';
// 		$p='[prepage]<a rel="nofollow" href="{$prepage}" >上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="{$pnumurl}" >{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="{$nextpage}">下一页</a>[/nextpage]';



        $this->db->criteria->setSort('saleid');
        $this->db->criteria->setOrder('DESC');
        $this->addConfig('system','configs');
        $jieqiConfigs['system'] = $this->getConfig('system','configs');
// 		$jieqiConfigs['system']['messagepnum'] = 1;
        $data ['pay'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $param['page'],JIEQI_PAGE_TAG);
        // 处理页面跳转
        $data ['url_jumppage'] = $this->db->getPage($this->getUrl(JIEQI_MODULE_NAME,'userhub','evalpage=0','SYS=method=xfView'));
        $articleLib = $this->load('article','article');
        foreach($data ['pay'] as $k=>$v){
            $data ['pay'][$k] = $articleLib->article_vars($v);
        }
        return $data;
    }
	/*
	 * 账务中心：领取书券列表视图
	 */
	public function getShuquanView($param){
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$auth = $this->getAuth();
		$this->db->init('shuquan','sid','system');
		$this->db->setCriteria(new Criteria('uid', $auth['uid']));
		$data['rownum'] = $this->db->getCount($this->db->criteria); //查询书券个数
		$data['rows'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $param['page'],JIEQI_PAGE_TAG); //列表
		$data ['url_jumppage'] = $this->db->getPage($this->getUrl('system','userhub','evalpage=0','SYS=method=receiveBookC')); //分页代码
		$data['source'] = array(0 => 'VIP升级奖励',1=> '连续签到奖励',2 => '上月消费返还',3=> '参加指定活动',4 => '作者签约赠送'); //书券来源，用于列表显示
		
		//查询待领取书券
		unset($this->db->criteria);
		$this->db->setCriteria(new Criteria('uid', $auth['uid']));
		$this->db->criteria->add(new Criteria ( 'getflag', 0));
		$data['flagcount'] = $this->db->getCount($this->db->criteria);
		//print_r($data);
		return $data;
	}
	/*
	 * 领取书券操作
	 */
	public function getShuquan($param){
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay'); //所有语言包配置赋值
		$auth = $this->getAuth();
		$this->db->init('shuquan','sid','system');
		$this->db->setCriteria(new Criteria('sid', $param['sid']));
		$shuquan=$this->db->get($this->db->criteria);
		if(is_object($shuquan)){//查询到一条数据
			$stat=$shuquan->getVar('stat');
			$flag=$shuquan->getVar('getflag');
			if($flag == 0){//未领取
				if(!$this->db->edit($param['sid'], array('getflag'=>1))){//更新flag不成功
					$this->printfail('领取失败');
				}else{//更新flag成功
					$this->db->init('users','uid','system');
					$this->db->setCriteria(new Criteria('uid', $auth['uid']));
					$user=$this->db->get($this->db->criteria);
					$esilver = $user->getVar('esilver') + $stat;
					if(!$this->db->edit($auth['uid'], array('esilver'=>$esilver))){//更新书券不成功
						$this->printfail('更新失败');
					}else{//更新书券成功
						$this->msgwin(LANG_DO_SUCCESS,'领取成功');
					}
				}
			}else{//已领取
				$this->printfail('已领取');
			}
		}else{//没有数据
			$this->printfail('发生错误');
		}
	}
	//书卷余额
/* 	public function shujuan($param){
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$auth = $this->getAuth();
		$this->db->init('users','uid','system');
		$this->db->setCriteria(new Criteria('uid', $auth['uid']));
		$data['rows'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $param['page'],JIEQI_PAGE_TAG);
		$data['esilver'] = $data['rows'][0]['esilver'];
		return $data;
	} */
	
	/*
	 * 查询总消费金额及次数，用于触屏版
	 */
    function xiaofei(){
        $auth = $this->getAuth();
        $sale_table=sprintf("sale_%02d",$auth['uid']%100);
        $this->db->init($sale_table,'saleid','article');
        $this->db->setCriteria(new Criteria('accountid', $auth['uid']));
        $data['xfnum'] = $this->db->getCount();
        $data['xfegold'] = $this->db->getSum('saleprice');
        $data['xfegold'] = sprintf("%1\$.2f",$data['xfegold']);
        return $data;
    }
}
?>