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
		$this->db->init('paylog','payid','pay');
		$this->db->setCriteria(new Criteria('buyid', $auth['uid']));
		if($param['flag'] == 'success'){
			$this->db->criteria->add(new Criteria('payflag', 1));
		}
		if($param['start']){
			$this->db->criteria->add(new Criteria('buytime',$param['start'],'>='));
		}
		if($param['end']){
			$this->db->criteria->add(new Criteria('buytime',$param['end'],'<'));
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
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage($this->getUrl('system','userhub','evalpage=0','SYS=method=czView&flag='.$param['flag']));
		$data['flag'] = $param['flag'];
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
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria(new Criteria('accountid', $auth['uid']));
// 		$this->db->criteria->add ( new Criteria ( 'mid', '(\'sale\',\'reward\')', 'IN' ));
// 		$this->db->criteria->add ( new Criteria ( 'mid', 'reward', '=' ), 'OR' );

		
// 		$this->db->criteria->setTables(jieqi_dbprefix('article_statlog').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid');
// 		$this->db->criteria->setFields('c.*,a.lastchapterid,a.lastchapter');
		
		
		
		//$p='[prepage]<a rel="nofollow" href="\'{$prepage}\'" >上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="\'{$pnumurl}\'" >{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="\'{$nextpage}\'" >下一页</a>[/nextpage]';
// 		$p='[prepage]<a rel="nofollow" href="{$prepage}" >上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="{$pnumurl}" >{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="{$nextpage}">下一页</a>[/nextpage]';
		
		
		
		$this->db->criteria->setSort('saleid');
		$this->db->criteria->setOrder('DESC');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
// 		$jieqiConfigs['system']['messagepnum'] = 1;
		$data['xfnum1'] = $this->db->getCount();
		$data['page1'] = $param['page'];
		$data ['maxpage1'] = ceil($data['xfnum1']/$jieqiConfigs['system']['messagepnum']);
		$data ['pay'] = $this->db->lists ($jieqiConfigs['system']['messagepnum'], $param['page'],JIEQI_PAGE_TAG);
		// 处理页面跳转
		$data ['url_jumppage'] = $this->db->getPage($this->getUrl('system','userhub','evalpage=0','SYS=method=xfView'));
		$articleLib = $this->load('article','article');
		foreach($data ['pay'] as $k=>$v){
			$data ['pay'][$k] = $articleLib->article_vars($v);
		}
		return $data;
	}
	/*
	 * 查询总消费金额及次数，用于触屏版
	 */
	function xiaofei(){
		$auth = $this->getAuth();
		$this->db->init('sale','saleid','article');
		$this->db->setCriteria(new Criteria('accountid', $auth['uid']));
		$data['xfnum'] = $this->db->getCount();
		$data['xfegold'] = $this->db->getSum('saleprice');
		return $data;
	}
}
?>