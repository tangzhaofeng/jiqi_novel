<?php 
/**
 * 用户充值记录
 * @author zhangxue  2014-6-13
 *
 */
class homeModel extends Model{

	public function main($params = array()){
		$this->addLang('pay', 'pay');
		$jieqiLang['pay'] = $this->getLang('pay');
		$this->addConfig('pay','paytype');
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$this->db->init('paylog','payid','pay');
		$this->action($params);//当页面有动作的时候，调用执行
		
		$this->db->setCriteria();
		if($params['payflag']==1||$params['payflag']==2){
			$this->db->criteria->add(new Criteria('payflag', $params['payflag']));
		}
		if($params['payflag']==3){
			$this->db->criteria->add(new Criteria('payflag', 0));
		}
		if($params['start']){
			$params['start'] = urldecode($params['start']);
			$start = strtotime($params['start']);
			$this->db->criteria->add(new Criteria('buytime', $start,'>='));
		}
		if($params['end']){
			$params['end'] = urldecode($params['end']);
			$end = strtotime($params['end']);
			$this->db->criteria->add(new Criteria('buytime', $end,'<='));
		}
		if($params['paytype']&&$params['paytype']!='all'){
			$this->db->criteria->add(new Criteria('paytype', $params['paytype']));
		}
		if($params['keyword']){
			if($params['keytype']==0){
				$this->db->criteria->add(new Criteria('payid', '%'.$params['keyword'].'%','LIKE'));
			}else if($params['keytype']==1){
				$this->db->criteria->add(new Criteria('buyname', '%'.$params['keyword'].'%','LIKE'));
			}
		}
			//todo 添加来源查询 2015-3-16 chengyuan
		if(isset($params['sel_site']) && $params['sel_site'] != -1){
			if(is_numeric($params['sel_site'])){
				$this->db->criteria->add(new Criteria('siteid', $params['sel_site']));
			}else{
				$this->db->criteria->add(new Criteria('siteid', '','in('.$params['sel_site'].')'));
			}
		}
		$this->db->criteria->setSort('buytime DESC,payid');
		$this->db->criteria->setOrder('DESC');
		$articleLib =  $this->load('article','article');
		if($params['download']){
		    $this->db->queryObjects($this->db->criteria);
			  $createexcel = $this->load('createexcel','system');
			  $titlearr=array(
				  'payid'=>'序号',
				  'buytime'=>'充值时间',
				  'buyname'=>'用户名',
				  'money'=>'金额',
				  'egold'=>'购买点数',
				  'from'=>'来源',
				  'paytype'=>'支付方式',
				  'payflag_c'=>'交易状态'
			  );
		    $data = array();
			$k=0;
			$paytyperows = $this->getConfig('pay','paytype');
			while($v = $this->db->getObject()){
				if($v->getVar('payflag','n')==0){
					$payflag_c = $jieqiLang['pay']['state_unconfirm'];
				}elseif($v->getVar('payflag','n')==1){
					$payflag_c = $jieqiLang['pay']['state_paysuccess'];
				}elseif($v->getVar('payflag','n')==2){
					$payflag_c = $jieqiLang['pay']['state_handconfirm'];
				}
			   $data[$k] = array(
					  'payid'=>$v->getVar('payid','n'),
					  'buytime'=>date('Y-m-d H:i:s', $v->getVar('buytime','n')),
					  'buyname'=>$v->getVar('buyname','n'),
					  'money'=>sprintf('%0.2f',$v->getVar('money','n')/100). ($v->getVar('moneytype','n') ? '美元' : '元'),
					  'egold'=>$v->getVar('egold','n'),
					  'from'=>$articleLib->getFromBySiteid($v->getVar('siteid','n')),
					  'paytype'=>isset($paytyperows[$v->getVar('paytype','n')]) ? $paytyperows[$v->getVar('paytype','n')]['name'] : $v->getVar('paytype','n'),
					  'payflag_c'=>$payflag_c
			   );
			   $k++;
			}
			$createexcel->getExcel($titlearr,$data,'充值记录日志');
			exit;
		}else{
			$payrows = $this->db->lists($jieqiConfigs['system']['messagepnum'], $params['page']);
			$sum = $this->db->getSum('money');
			$sum = sprintf('%0.2f',$sum/100);//echo $sum;
			$totalegold = $this->db->getSum('egold');
			foreach($payrows as $k=>$v)
			{
				if($v['payflag']==0){
					$v['payflag_c'] = $jieqiLang['pay']['state_unconfirm'];
				}elseif($v['payflag']==1){
					$v['payflag_c'] = $jieqiLang['pay']['state_paysuccess'];
				}elseif($v['payflag']==2){
					$v['payflag_c'] = $jieqiLang['pay']['state_handconfirm'];
				}
				$v['money'] = sprintf('%0.2f',$v['money']/100);
				$v['from'] = $articleLib->getFromBySiteid($v['siteid']);
				$payrows[$k] = $v;
				$k++;
			}
			// 处理页面跳转
			//$data['url_jumppage'] = $this->db->getPage();
			//$data['payflag'] = $params['payflag'];
			//$data['paytype'] = $params['paytype'];
			//支付类型，配置文件
			//$data['paytyperows'] = $this->getConfig('pay','paytype');
			//return $data;
			return array(
				'sel_site'=>$params['sel_site'],
				'sites'=>$articleLib->getSitesCombine(),
				'payrows'=>$payrows,
				'sum'=>$sum,
				'totalegold'=>$totalegold,
				'start'=>$params['start'],
				'end'=>$params['end'],
				'keyword'=>$params['keyword'],
				'keytype'=>$params['keytype'],
				'payflag'=>$params['payflag'],
				'paytype'=>$params['paytype'],
				'paytyperows'=>$this->getConfig('pay','paytype'),
				'url_jumppage'=>$this->db->getPage(),
				'totalnum'=>$this->db->getCount()
			);
		}

	}
	public function action($params){
		 if(isset($params['action']) && !empty($params['id'])){
		      switch($params['action']){
			       case 'confirm'://手工处理
//				       $query = $this->db->edit($params['id'],array('payflag'=>2));
						$this->db->setCriteria(new Criteria( 'payid', $params['id'], '=' ));
						$paylog=$this->db->get($this->db->criteria);
						if(is_object($paylog)){
							$buyid=$paylog->getVar('buyid');
							$payflag=$paylog->getVar('payflag');
							$egold=$paylog->getVar('egold');
							$money=$paylog->getVar('money');
							$moneytype = $paylog->getVar('moneytype');
							if($moneytype == 1){
								$score = $money*3;
								$experience = $money*6;
							}else{
								$score = $money/2;
								$experience = $money;
							}
							if($payflag == 0){
								$users_handler =  $this->getUserObject();
							   if($users_handler->income($buyid, $egold, 0, $score, $experience))  $query = $this->db->edit($params['id'],array('payflag'=>2));
							   else $query = false;
							}else $query = false;
						}else $query = false;
				   break;
			       case 'del'://删除
				       $query = $this->db->delete($params['id']);
				   break;
			  }
		      if($query) jieqi_jumppage();
			  else jieqi_printfail();
		 }	
	}
	public function total($params = array()){
	     /*$pnum = 500;$params['page'] = $params['page'] ? $params['page'] :1;
	     $this->db->init('paylog','payid','pay');
		 $this->db->setCriteria(new Criteria('payflag',0, '>'));
		 $this->db->criteria->setFields('`buyid` , sum( `money` ) AS M');
		 $this->db->criteria->setGroupby('buyid');
		 $this->db->criteria->setSort('M');
	     $this->db->criteria->setOrder('DESC');
		 $payrows = $this->db->lists($pnum, $params['page']);
		 $totalpage  = ceil($this->db->getCount($this->db->criteria)/$pnum);
		 
		 foreach($payrows as $k => $v){
		     $this->db->init('users','uid','system');
			 if($this->db->get($v['buyid'])){
			      $this->db->edit($v['buyid'], array('isvip'=>$v['M']));
			 }
		 }
		 
		 if($params['page']>=$totalpage) exit('全文处理完成！');
		 jieqi_jumppage('?controller=home&method=total&page='.($params['page']+1),'处理中...','第('.$params['page'].')页处理完毕！共('.$totalpage.')页!');*/
		 //print_r($payrows);exit($totalpage);
		 
	}
	public function promotingRevenue($date){
        if (!$date) {
            $date=date("Y-m-d");
        }
		$data = array();
		if(!$date){
			return $data;
		}
		//payflag=1 充值成功的
		//充值时间格式化
		//按照年月日分组
		//计算日总计
		//时间大于等于date
		$start_time = strtotime($date.'-01 00:00:00');//月的开始时间,1433088000 = 2015-6-1
		if($start_time < 1433088000){
			exit('日期非法');
		}
		$sql = 'SELECT TRUNCATE(sum(t0.money)/100,2) AS revenues,TRUNCATE(sum(t0.egold)/100,2) as realincome,FROM_UNIXTIME( t0.buytime, \'%Y-%m-%d\' ) as f_buytime FROM `jieqi_pay_paylog` as t0 WHERE t0.payflag= 1 and t0.buytime>= '.$start_time.' group by f_buytime order by f_buytime desc';
		echo $sql;
		$resutl = $this->db->selectsql ($sql);
		$data['payrows'] =$resutl;
		$data['start'] =$date;
		return $data;
	}
} 
?>