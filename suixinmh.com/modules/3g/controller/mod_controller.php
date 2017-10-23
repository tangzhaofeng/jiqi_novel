<?php
/**
 * 3g模块base控制器
 * @author chengyuan  2014-8-6
 *
 */
class chief_controller extends Controller {
	/**
	 * 默认模板
	 * @var unknown
	 */
//	public $template_name = 'index';
	public $caching = false;

	public function __construct() {
	    global $jieqiModules;
		parent::__construct ();
		$this->assign('SITE_WAP_URL',$jieqiModules['3g']['url']);//3g 
		$this->assign('SITE_3GWAP_URL',$jieqiModules['3gwap']['url']);//触屏，qq登陆使用
		//$this->assign('APP_DOWNLOAD_URL','http://appdata.shuhai.com/shuhai_server/servlet/DownloadServlet?apptype=1&appbookid=0');
		//判断来源网址
		if(!defined('JIEQI_NEED_SOURCE')) define('JIEQI_NEED_SOURCE',true);
		if(defined('JIEQI_NEED_SOURCE') && $_SESSION['SOURCE_SITE']=='' && $_REQUEST['qd']){
			$refer = $_GET['qd'].'_'.$_REQUEST['aid'];
			if($refer){
                setcookie('qd_id',$_GET['qd'],time()+3600*360,'/');
				setcookie('SOURCE_SITE',$refer,time()+3600*360,'/');
				$_SESSION['SOURCE_SITE'] = $refer;
			}
		}

		if (isset($_GET['qd'])) {
			unset($_SESSION['block']);
		}

		if (ctype_alnum($_GET['qd'])) {
			$_SESSION['qd']=$_GET['qd'];
		}
		if (ctype_alnum($_GET['aid'])) {
			$_SESSION['aid']=$_GET['aid'];
		}

        $ref=$_SERVER['HTTP_REFERER'];
        if ($ref) {
            $a = parse_url($ref);
            if ($a['host']!='le.m.ishufun.net') {
                $_SESSION['referer']=addslashes($ref);
                $_SESSION['firsturl']=addslashes($_SERVER['SCRIPT_NAME'].'?'.$_SERVER['QUERY_STRING']);
            }
        }

		if ($_GET['qd'] && intval($_GET['qd'])>0) {
			setcookie("click_from_qd",intval($_GET['qd']),time()+3600*360,'/');  //充值表需要记录最近点击的推广渠道
		}

        include_once(JIEQI_ROOT_PATH."/include/total_service_funcs.php");
        if ($_GET['qd']) {
            qd_stat($_GET['qd'],'click');
        }
        if ($_COOKIE['qd_id']) {
            qd_stat($_COOKIE['qd_id'],'pv');
        }
	}
	
	/**
	 * 写入阅读记录
	 */
	public function wCookie($readInfo, $isUser = false) {
		if ($isUser) {
			$user = $this->getAuth();
			$uid = !isset($user['uid']) ? 0 : $user['uid'];
		} else {
			$uid = '';
		}
		if (empty($_COOKIE['shuhai_history_'.$uid])) {
			// 初始化cookie
			$cookiestr[] = $readInfo;
			setCookie('shuhai_history_'.$uid, json_encode($cookiestr), time()+2592000, '/');
		} else {
			// 判断是否添加cookie
			$this_cookie = json_decode($_COOKIE['shuhai_history_'.$uid], true);
			$have_add = true;
			$diff_sum = 0;
			foreach ($this_cookie as $k=>$v) {
				if ($readInfo['aid']==$v['aid'] && $readInfo['cid']==$v['cid']) {
					// 相同的阅读记录不记录
					$have_add = false;
				} elseif ($readInfo['aid']==$v['aid'] && $readInfo['cid']!=$v['cid']) {
					// 阅读章节不同则更新阅读记录
					unset($this_cookie[$k]);
					$this_cookie[$k] = $readInfo;
				} else {
					// 完全不同则添加阅读记录
					$diff_sum++;
				}
			}
			// 如果有任何一条相同记录则不更新
			if ($have_add) {
				// 如果当前阅读章节与所有阅读记录都不同则加入新阅读记录
				if (count($this_cookie)==$diff_sum) {
					// 显示最多缓存6条阅读记录
					if (count($this_cookie)>6) unset($this_cookie[0]);
					$this_cookie[] = $readInfo;
				}
				setCookie('shuhai_history_'.$uid, '', 0, '/');
				setCookie('shuhai_history_'.$uid, json_encode($this_cookie), time()+2592000, '/');
			}
		}
		return true;
	}
	
	/**
	 * 获得阅读记录
	 */
	public function rCookie($isUser = false) {
		$reData = array();
		if ($isUse) {
			$user = $this->getAuth();
			$uid = !isset($user['uid']) ? 0 : $user['uid'];
		} else {
			$uid = '';
		}
		if (empty($_COOKIE['shuhai_history_'.$uid])) {
			$reData = '';
		} else {
			$temp = json_decode($_COOKIE['shuhai_history_'.$uid], true);
			$temp = array_reverse($temp);
			foreach ($temp as $k=>$v) {
				$reData[$k]['aid'] = $v['aid'];
				$reData[$k]['cid'] = $v['cid'];
				$reData[$k]['aname'] = urldecode($v['aname']);
				$reData[$k]['autname'] = urldecode($v['autname']);
				$reData[$k]['asort'] = urldecode($v['asort']);
				$reData[$k]['cname'] = urldecode($v['cname']);
			}
		}
		return $reData;
	}
}
?>