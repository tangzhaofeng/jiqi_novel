<?php 
/** 
 * 我的好友模型 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class myfriendsModel extends Model{ 
	//我的关注
	function main($params){
		$auth = $this->getAuth ();
		$jieqiConfigs['system']['friendspnum']=10;
		$flag = true;
		if(empty($params['page'])) $params['page'] = 1;
		global $jieqiHonors;
		if(!isset($jieqiHonors)) jieqi_getconfigs('system', 'honors', 'jieqiHonors');
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$this->db->init('friends','friendsid','system');
		if($params['mid'])
		{
			switch($params['mid']){
				case 'whole':
				  	$this->db->init('users','uid','system');
					$this->db->setCriteria();
					$this->db->criteria->setTables($this->dbprefix('system_users').' as u ');
					$this->db->criteria->setFields('*,(select count(*) from '.$this->dbprefix('system_friends').' where yourid = u.uid ) as num ');			
					$this->db->criteria->add(new Criteria('u.uid','(select f.yourid from jieqi_system_friends AS f where f.myid ='.$auth['uid'].')','IN'));									
					break;
				case 'add':
					$yourid = $this->getNotMyFri($auth['uid'],'=');
					$this->db->init('users','uid','system');
					$this->db->setCriteria();
					$this->db->criteria->setTables($this->dbprefix('system_users').' as u ');
					$this->db->criteria->setFields('*,(select count(*) from '.$this->dbprefix('system_friends').' where yourid = u.uid ) as num ');
					$this->db->criteria->add(new Criteria('groupid', $auth['groupid']));
					if($yourid){
						$this->db->criteria->add(new Criteria('','uid NOT IN ('.$yourid.')',''));
						$this->db->criteria->add(new Criteria('uid',$auth['uid'],'!='));
					}
					break;
				case 'eachother':
					$yourid = $this->getNotMyFri($auth['uid'],'=');		//当前用户关注人的uid
					if($yourid)	$friendsid = $this->getNotMyFri('('.$yourid.')','IN',true,$auth['uid']);
					if($friendsid)
					{
						$this->db->init('users','uid','system');
						$this->db->setCriteria();
						$this->db->criteria->setTables($this->dbprefix('system_users').' as u ');
						$this->db->criteria->setFields('*,(select count(*) from '.$this->dbprefix('system_friends').' where yourid = u.uid ) as num ');
						$this->db->criteria->add(new Criteria('f.friendsid', '('.$friendsid.')','IN'));
						$this->db->criteria->setTables($this->dbprefix('system_friends')." AS f LEFT JOIN ".$this->dbprefix('system_users')." AS u ON u.uid = f.myid");
					}				
					break;
				case 'author':
					$yourid = $this->getNotMyFri($auth['uid'],'=');
					$this->db->init('users','uid','system');
					$this->db->setCriteria();
					$this->db->criteria->setTables($this->dbprefix('system_users').' as u ');
					$this->db->criteria->setFields('*,(select count(*) from '.$this->dbprefix('system_friends').' where yourid = u.uid ) as num ');
					$this->db->criteria->add(new Criteria('groupid', 6));
					if($yourid){
						$this->db->criteria->add(new Criteria('','uid NOT IN ('.$yourid.')',''));
						$this->db->criteria->add(new Criteria('uid',$auth['uid'],'!='));
					}
					break;
				case 'fans':
					$yourid = $this->getNotMyFri($auth['uid'],'=');
					if($yourid) $friendsid = $this->getNotMyFri('('.$yourid.')','IN',true,$auth['uid']);
					$this->db->init('friends','friendsid','system');
					$this->db->setCriteria(new Criteria('yourid', $auth['uid']));	//我的粉丝
					$this->db->criteria->setTables($this->dbprefix('system_users')." AS u right JOIN ".$this->dbprefix('system_friends')." AS f ON u.uid = f.myid");
					$data['nowfriends'] = $this->db->getCount($this->db->criteria);
					$this->db->criteria->setLimit($jieqiConfigs['system']['friendspnum']);
		            $this->db->criteria->setStart(($params['page']-1)*$jieqiConfigs['system']['friendspnum']);
				    $this->db->queryObjects();
	                $i = 0;
					$friendsrows = array();
					while($v = $this->db->getObject()){
						$friendsrows[$i]['name'] = $v->getVar('name');
						$friendsrows[$i]['uid'] = $v->getVar('uid');
						$friendsrows[$i]['myid'] = $v->getVar('myid');
						$friendsrows[$i]['yourid'] = $v->getVar('yourid');
						$friendsrows[$i]['friendsid'] = $v->getVar('friendsid');
						$friendsrows[$i]['sex'] = $v->getVar('sex');
						$friendsrows[$i]['avatar'] = $v->getVar('avatar');
						$friendsrows[$i]['honorid'] = jieqi_gethonorid($v->getVar('score','n'), $jieqiHonors);
						$friendsrows[$i]['honorphoto'] = $jieqiHonors[$friendsrows[$i]['honorid']]['photo'];
						$friendsrows[$i]['vipgradeid'] = jieqi_gethonorid( $v->getVar('isvip','n'), $jieqiVipgrade);
						$friendsrows[$i]['vipphoto'] = $jieqiVipgrade[$friendsrows[$i]['vipgradeid']]['photo'];
						$friendsrows[$i]['sign'] = $v->getVar('sign');						
						$this->db->init('friends','friendsid','system');
						$this->db->setCriteria(new Criteria('yourid', $v->getVar('uid'),'='));
						$friendsrows[$i]['num']= $this->db->getCount($this->db->criteria);									
						$i++;
					}
					$this->db->init('friends','friendsid','system');
					foreach($friendsrows as $k=>$v){
						$this->db->setCriteria(new Criteria('myid', $auth['uid'],'='));
						$this->db->criteria->add(new Criteria('yourid',$v['myid'],'='));
						$this->db->queryObjects();
						if($this->db->getObject()){
							$friendsrows[$k]['e'] = 'yes';
						}else{
							$friendsrows[$k]['e'] = 'no';
						}
					}
					$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showfriend(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showfriend(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showfriend(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage] <em class="pr10">共{$page}/{$totalpage}页</em>';
					$jumppage = new GlobalPage($p,$data['nowfriends'],$jieqiConfigs['system']['friendspnum'],$params['page']);
					$data['friendsrows'] = $friendsrows;
					$data['mid'] = $params['mid'];
					$data['uid'] = $auth['uid'];
					$data['url_jumppage']=$jumppage->getPage($this->geturl('system','userhub','method=getfriend','evalpage=0','SYS=mid='.$params['mid'].'&page='.$params['page']));
					$flag = false;
					$objLib = $this->load( 'article', 'article');	
					$objLib->setSetting($auth['uid'],'jieqiNewFriend',true);
					break;
				default:
					break;
			}
		}
		
		if ($flag){
			$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showfriend(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showfriend(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showfriend(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage] <em class="pr10">共{$page}/{$totalpage}页</em>';
			$data['friendsrows'] = $this->db->lists($jieqiConfigs['system']['friendspnum'],$params['page'],$p);
			foreach($data['friendsrows'] as $k=>$v){
				$v['honorid'] = jieqi_gethonorid($v['score'], $jieqiHonors);
			    $v['honorphoto'] = $jieqiHonors[$v['honorid']]['photo'];
				$v['honor'] = $jieqiHonors[$v['honorid']]['caption'];
				$v['vipgradeid'] = jieqi_gethonorid($v['isvip'], $jieqiVipgrade);
				$v['vipgrade'] = $jieqiVipgrade[$v['vipgradeid']]['caption'];
				$v['vipphoto'] = $jieqiVipgrade[$v['vipgradeid']]['photo'];
				$data['friendsrows'][$k] = $v;
			}
			$data['url_jumppage'] = $this->db->getPage($this->getUrl('system','userhub','method=getfriend','evalpage=0','SYS=mid='.$params['mid']));
			$data['nowfriends'] = $this->db->getVar('totalcount');
			$data['mid'] = $params['mid'];
			$data['uid'] = $auth['uid'];
		}
		return $data;
	}
	
	function getNotMyFri($uid,$type,$flag=false,$yourid=0)
	{
		$this->db->init('friends','friendsid','system');
		$this->db->setCriteria(new Criteria('myid',$uid,$type));
		if ($flag){
			$this->db->criteria->add(new Criteria('yourid',$yourid,'='));
		}
		$this->db->queryObjects();
		$i = 0;
		$yourid = '';
		while($v = $this->db->getObject())
		{
			if($yourid) $yourid.=',';
			if ($flag){
				$yourid.=$v->getVar('friendsid');
			}else{
				$yourid.=$v->getVar('yourid');
			}
		    
			$i++;
		}
		return $yourid ;
	}
	/**
	 * 好友信息
	 * @param unknown $uid
	 */
	function friendSpace($uid){
		//加载配置区块
		jieqi_getconfigs ( 'system', 'spaceblocks', 'jieqiBlocks' );
	}
	
	function listsUsers($params){	//添加关注的查询user表
		$auth = $this->getAuth ();
		$this->addConfig('system','configs');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$this->db->init('users','uid','system');
		$sql = 'select * from '.jieqi_dbprefix('system_users').' where uid not in (select yourid from '.jieqi_dbprefix('system_friends').' where myid ='.$auth['uid'].')';
		$res = $this->db->query($sql);
		$i=0;
		while ($row = $this->db->getRow ($res)){
			$data[] = array('name'=>$row['name'],'uid'=>$row['uid']);
			$i++;
			if($i == 6){
				break;
			}
		}
		return $data;			
	}
	
	function addAttention($params){		//加关注
		$auth = $this->getAuth ();
		$this->addLang('article', 'review');
		$this->addLang('system', 'users');
		$jieqiLang['article'] = $this->getLang('article');
		$jieqiLang['system'] = $this->getLang('system');
		$this->db->init('users','uid','system');	//初始化
		$youUser = $this->db->get($params['yuid']);
		$this->addConfig('system','configs');
		$this->addConfig('system','right');
		$this->addConfig('system','honors');
		$jieqiHonors = $this->getconfig('system', 'honors');
		$jieqiRight['system'] = $this->getConfig('system','right');
		$jieqiConfigs['system'] = $this->getConfig('system','configs');
		$maxfriendsnum=intval($jieqiConfigs['system']['maxfriends']); //默认好友数
		$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
		$this->db->init('friends','friendsid','system');
		if($honorid && isset($jieqiRight['system']['maxfriends']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxfriends']['honors'][$honorid])) {
			$maxfriendsnum = intval($jieqiRight['system']['maxfriends']['honors'][$honorid]); //根据头衔设置的好友数
		}
	    if(is_numeric($maxfriendsnum)){
		    $this->db->setCriteria(new Criteria('myid', $auth['uid']));
			
			$friendsnum=$this->db->getCount($this->db->criteria);
			if($friendsnum>=$maxfriendsnum) $this->printfail(sprintf($jieqiLang['system']['too_manay_friends'], $maxfriendsnum));
	    }
		$newAttention = array();
		$newAttention['adddate'] = JIEQI_NOW_TIME;
		$newAttention['myid'] = $auth['uid'];//$params['yuid'];
		$newAttention['myname'] = $auth['username'];//$youUser['name'];
		$newAttention['yourid'] = $params['yuid'];//$auth['uid'];
		$newAttention['yourname'] = $youUser['name'];//$auth['username'];
		$newAttention['teamid'] = 0;
		$newAttention['state'] = 0;
		$newAttention['flag'] = 0;
		if ($this->db->add($newAttention)){
			$objLib = $this->load( 'article', 'article');	
			$objLib->setSetting($params['yuid'],'jieqiNewFriend',false);
			$rul = $this->getUrl('system'.'userhub','SYS=method=getUser');
			$this->jumppage($rul,LANG_DO_SUCCESS, $jieqiLang['article']['attention_add_success']);
		}else{
			$rul = $this->getUrl('system'.'userhub','SYS=method=getUser');
			$this->jumppage($rul,LANG_DO_SUCCESS, $jieqiLang['article']['attention_add_failure']);
		}
	}
	
	function searchF($params){
		$auth = $this->getAuth ();
		$jieqiConfigs['system']['friendspnum']=10;
		$yourid = $this->getNotMyFri($auth['uid'],'=');
		global $jieqiHonors;
		if(!isset($jieqiHonors)) jieqi_getconfigs('system', 'honors', 'jieqiHonors');		
		$this->addConfig('system','vipgrade');
		$jieqiVipgrade = $this->getconfig('system', 'vipgrade');
		$this->db->init('users','uid','system');
		$this->db->setCriteria();
		$this->db->criteria->setFields('*,(select count(*) from '.$this->dbprefix('system_friends').' where yourid=u.uid) as num');
		$this->db->criteria->setTables($this->dbprefix('system_users').' as u');
		if($yourid) $this->db->criteria->add(new Criteria('','uid NOT IN ('.$yourid.')',''));	//已关注的人不在搜索结果中显示,如果没有已关注的人跳过
		$this->db->criteria->add(new Criteria('name',$params['smid']));
		$this->db->criteria->add(new Criteria('uid',$auth['uid'],'!='));
		$this->db->queryObjects();
		$i = 0;
		$friendsrows = array();
		while($v = $this->db->getObject()){
			$friendsrows[$i]['name'] = $v->getVar('name');
			$friendsrows[$i]['uid'] = $v->getVar('uid');
			$friendsrows[$i]['myid'] = $v->getVar('myid');
			$friendsrows[$i]['yourid'] = $v->getVar('yourid');
			$friendsrows[$i]['friendsid'] = $v->getVar('friendsid');
			$friendsrows[$i]['sex'] = $v->getVar('sex');
			$friendsrows[$i]['fans'] = $v->getVar('fans');								
			$i++;
		}
		$p='[prepage]<a rel="nofollow" href="javascript:;" onclick="return showfriend(this,\'{$prepage}\',1)" id="'.$params['mid'].'">上一页</a>[/prepage][pages][pnum]6[/pnum][pnumchar] <em class="b">{$page}</em>[/pnumchar][pnumurl]<A href="javascript:;" onclick="return showfriend(this,\'{$pnumurl}\',1)" id="'.$params['mid'].'">{$pagenum}</A>[/pnumurl]{$pages}[/pages][nextpage]<a href="javascript:;" onclick="return showfriend(this,\'{$nextpage}\',1)" id="'.$params['mid'].'">下一页</a>[/nextpage] <em class="pr10">共{$page}/{$totalpage}页</em>';
		$data['friendsrows'] = $this->db->lists($jieqiConfigs['system']['friendspnum'],$params['page'],$p);
		foreach($data['friendsrows'] as $k=>$v){
				$v['honorid'] = jieqi_gethonorid($v['score'], $jieqiHonors);
			    $v['honorphoto'] = $jieqiHonors[$v['honorid']]['photo'];
				$v['honor'] = $jieqiHonors[$v['honorid']]['caption'];
				$v['vipgradeid'] = jieqi_gethonorid($v['isvip'], $jieqiVipgrade);
				$v['vipgrade'] = $jieqiVipgrade[$v['vipgradeid']]['caption'];
				$v['vipphoto'] = $jieqiVipgrade[$v['vipgradeid']]['photo'];
				$data['friendsrows'][$k] = $v;
			}
		$data['nowfriends'] = $this->db->getVar('totalcount');
		$data['uid'] = $auth['uid'];
		$jumppage = new GlobalPage($p,$data['nowfriends'],$jieqiConfigs['system']['friendspnum'],$params['page']);
		$data['url_jumppage']=$jumppage->getPage($this->geturl('system','userhub','method=searchF','evalpage=0','SYS=smid='.$params['smid'].'&page='.$params['page']));
		return $data;
	}
	
	function delAtten($params){	//处理删除
		$auth = $this->getAuth ();
		$this->addLang('system', 'message');
		$jieqiLang['system'] = $this->getLang('system');
		$this->db->init('friends','friendsid','system');
		$this->db->setCriteria(new Criteria('myid', $params['myid'],'='));
		$this->db->criteria->add(new Criteria('yourid', $params['yid'],'='));
		if($params['myid'] && is_numeric($params['yid']) && $params['yid']>0){
			if ($this->db->delete($this->db->criteria))
			{
				$this->jumppage ($this->geturl ( 'system', 'userhub', 'SYS=method=getfriend&mid='.$params['mid']), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
			}
		}
	}	
} 
?>