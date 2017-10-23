<?php
include_once (JIEQI_ROOT_PATH.'/connect/lib/my_connect.php');
/**
 * article业务类继承了老版本的JieqiPackage类
 * 
 * @copyright Copyright(c) 2014
 * @author chengyuan
 * @version 1.0
 */
class MyWeixin extends MyConnect
{
    function auth($appid, $redirect_uri)
    {
        $redirect_uri='http://m.suixinmh.com/wxlogin/login/';
        //$url1 = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx73a2e606e8fae039&redirect_uri=http://m.ishufun.net/wxlogin/login&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";

        //echo "$url1\n\n$url2\n\n";
        //exit();

        header("location:$url");
    }

    function access_token($appid, $secret, $code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $content = file_get_contents($url);
        return json_decode($content);
    }


    function user_info($openid, $access_token, $authapi="snsapi_base")
    {
        if ($authapi == 'snsapi_base') {
            $nickname = iconv("gbk","utf-8","读者" . date("ymdHis") . rand(100, 999));
            $user = array('nickname' => $nickname);
            return json_decode(json_encode($user));
        }
        else {
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
            $content = file_get_contents($url);
            return json_decode($content);
        }
    }

    function userregister($params,$update_nick=false){



        global   $jieqiModules;
        //载入语言
        $this->addLang('system', 'users');
        $this->addConfig('system','configs');
        $jieqiLang['system'] = $this->getLang('system');
        $jieqiConfigs['system'] = $this->getConfig('system','configs');

        //include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
        include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
        $errtext='';
        $users_handler = $this->getUserObject();

        if ($_SESSION['jumpurl']) {
            $params['jumpurl'] = $_SESSION['jumpurl'];
        }


        $this->db->init('connect', 'connectid', 'system');
        $this->db->setCriteria(new Criteria('openid', $params['openid']));
        $this->db->queryObjects($this->db->criteria);
        $connObj = $this->db->getObject();
        if (is_object($connObj) && $connObj->getVar('uid','n')) {
            include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
            $jieqiUsers = $users_handler->get($connObj->getVar('uid','n'));
            $islogin = jieqi_loginprocess($jieqiUsers, 100000000);
            include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
            if (isset($params['jumpurl'])){
                $params['url'] = JIEQI_LOCAL_URL.'/wxlogin/jumpurl?param='.$params['jumpurl'];
            }else{

                $params['url'] = JIEQI_LOCAL_URL.'/wxlogin/jumpurl';
            }
            unset($_SESSION['openid_'.$params['type']]);

            if(in_array(JIEQI_MODULE_NAME,array('3gwap','wap','3g'))){
                header('Location: '.$params['url']);exit;
            }else jieqi_logindo($params['url']);
        }

        //$params['username'] = urldecode($params['username']);
        $params['password'] = trim($params['password']);
        //$this->printfail($jieqiLang['system']['register_failure'].'gg');
        if($params['username'] && $users_handler->getByname($params['username'], 3) != false) $params['username'] = $params['username'].jieqi_random(6);
        if(!$params['username']) $params['username'] = jieqi_random(8);
        $newUser['siteid'] = $jieqiModules[JIEQI_MODULE_NAME]['siteid'];
        $newUser['uname'] = $params['username'];
        $newUser['name'] =$params['username'];
        //$newUser['pass'] =$users_handler->encryptPass($params['password']);
        $newUser['groupid'] =JIEQI_GROUP_USER;
        $newUser['regdate'] =JIEQI_NOW_TIME;
        $newUser['initial'] =jieqi_getinitial($params['username']);
        $newUser['sex'] = $params['sex'];
        $newUser['email'] = $params['email'];
        $newUser['url'] =$params['url'];
        $newUser['avatar'] =0;
        $newUser['workid'] =$params['workid'];
        $newUser['qq'] = $params['qq'];
        $newUser['icq']= '';
        $newUser['msn'] =$params['msn'];
        $newUser['mobile'] ='';
        $newUser['sign'] =$params['sign'];
        $newUser['intro'] =$params['intro'];
        $userset['logindate'] = date('Y-m-d H:i:s',JIEQI_NOW_TIME);
        $userset['lastip'] = $this->getIp();
        $userset['source'] = $_SESSION['SOURCE_SITE'] ? $_SESSION['SOURCE_SITE'] : $_COOKIE['SOURCE_SITE'];
        $userset['referer']=$_SESSION['referer'];
        $userset['firsturl']=$_SESSION['firsturl'];
        if (strpos($userset['source'],'_')!==false) {
            $source_x=explode('_',$userset['source']);
            $source=$source_x[0];
            $book_id=$source_x[1];
        }
        else {
            $source=$userset['source'];
            $book_id = 0;
        }
        $newUser['setting']= serialize($userset);
        $newUser['badges']= '';
        $newUser['lastlogin']= JIEQI_NOW_TIME;
        $newUser['showsign'] =0;
        $newUser['viewemail']= 0;
        $newUser['notifymode']= 0;
        $newUser['adminemail']= 0;
        $newUser['monthscore'] =0;
        $newUser['experience'] =$jieqiConfigs['system']['scoreregister'];
        $newUser['score'] =$jieqiConfigs['system']['scoreregister'];
        $newUser['egold'] =0;
        $newUser['esilver']= 0;
        $newUser['credit']=0;
        $newUser['goodnum'] =0;
        $newUser['badnum']= 0;
        $newUser['isvip'] =0;
        $newUser['overtime'] =0;
        $newUser['state']=0;
        //$newUser['source'] =$_SESSION['SOURCE_SITE'] ? $_SESSION['SOURCE_SITE'] : $_COOKIE['SOURCE_SITE'];
        $newUser['source'] =$_SESSION['qd']?$_SESSION['qd']:$source;
        $newUser['book_id']=$_SESSION['aid']?$_SESSION['aid']:$book_id;

        if (!$newUser['source']) {
            $url=$_SESSION['firsturl'];
            $pos1=strpos($url,'jumpurl=');
            $jumpurl=substr($url,$pos1+8);
            $jumpurl=urldecode($jumpurl);
            $pos2=strpos($jumpurl,'qd=');
            $qd=addslashes(substr($jumpurl,$pos2+3));
            $newUser['source']=$qd;

            $urlx=parse_url($jumpurl);
            $path=$urlx['path'];
            $pathx=explode('/',$path);
            if ($pathx[1]=='read') {
                $newUser['book_id']=intval($pathx[2]);
            }

        }

        $this->db->init('users','uid','system');
        if (!$uid = $this->db->add($newUser)) $this->printfail($jieqiLang['system']['register_failure']);
        else {
            if ($update_nick) {
                $username="读者".$uid;
                $up_arr=array(
                    "uname"=>$username,
                    "name"=>$username
                    );
                $this->db->edit($uid,$up_arr);
            }

            $this->db->init('connect','connectid','system');
            $userobj['type'] = $params['type'];
            $userobj['accesstoken']=$params['accesstoken'];
            $userobj['openid']=$params['openid'];
            $userobj['bindtime'] = JIEQI_NOW_TIME;
            $userobj['uid'] = $uid;
            $this->db->add($userobj);

            //同一个IP重复注册时间限制
            if (!$this->redis) {
                include_once(JIEQI_ROOT_PATH . '/lib/database/redis.php');
                $this->redis = new MyRedis(JIEQI_REDIS_HOST, JIEQI_REDIS_PORT);
            }
            $user_ip = $this->getIp();
            $jieqiConfigs['system']['regtimelimit'] = intval($jieqiConfigs['system']['regtimelimit']);
            $this->redis->set("reg_{$user_ip}",time(),$jieqiConfigs['system']['regtimelimit']);



            //更新在线用户表
            include_once(JIEQI_ROOT_PATH.'/class/online.php');
            $online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
            include_once(JIEQI_ROOT_PATH.'/include/visitorinfo.php');
            $online = $online_handler->create();
            $this->db->init('users','uid','system');
            $this->db->setCriteria(new Criteria('uid', $uid));
            $newUser = $this->db->get($this->db->criteria);
            $online->setVar('uid', $newUser->getVar('uid', 'n'));
            $online->setVar('siteid', JIEQI_SITE_ID);
            $online->setVar('sid', session_id());
            $online->setVar('uname', $newUser->getVar('uname', 'n'));
            $tmpvar = strlen($newUser->getVar('name', 'n')) > 0 ? $newUser->getVar('name', 'n') : $newUser->getVar('uname', 'n');
            $online->setVar('name', $tmpvar);
            $online->setVar('pass', $newUser->getVar('pass', 'n'));
            $online->setVar('email', $newUser->getVar('email', 'n'));
            $online->setVar('groupid', $newUser->getVar('groupid', 'n'));
            $tmpvar=JIEQI_NOW_TIME;
            $online->setVar('logintime', $tmpvar);
            $online->setVar('updatetime', $tmpvar);
            $online->setVar('operate', '');
            $tmpvar=VisitorInfo::getIp();
            $online->setVar('ip', $tmpvar);
            $online->setVar('browser', VisitorInfo::getBrowser());
            $online->setVar('os', VisitorInfo::getOS());
            $location=VisitorInfo::getIpLocation($tmpvar);
            if(JIEQI_SYSTEM_CHARSET == 'big5'){
                include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
                $location=jieqi_gb2big5($location);
            }
            $online->setVar('location', $location);
            $online->setVar('state', '0');
            $online->setVar('flag', '0');
            $online_handler->insert($online);

            //设置SESSION
            jieqi_setusersession($newUser);

            //设置COOKIE
            $jieqi_user_info['jieqiUserId']=$_SESSION['jieqiUserId'];
            $jieqi_user_info['jieqiUserName']=$_SESSION['jieqiUserName'];
            $jieqi_user_info['jieqiUserGroup']=$_SESSION['jieqiUserGroup'];
            ///
            include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
            $jieqi_user_info['jieqiUserVip']=$_SESSION['jieqiUserVip'];
            if(JIEQI_SYSTEM_CHARSET == 'gbk'){
                $jieqi_user_info['jieqiUserHonor_un']=jieqi_gb2unicode($_SESSION['jieqiUserHonor']);
                $jieqi_user_info['jieqiUserGroupName_un']=jieqi_gb2unicode($jieqiGroups[$_SESSION['jieqiUserGroup']]);
            }else{
                $jieqi_user_info['jieqiUserHonor_un']=jieqi_big52unicode($_SESSION['jieqiUserHonor']);
                $jieqi_user_info['jieqiUserGroupName_un']=jieqi_gb2unicode($jieqiGroups[$_SESSION['jieqiUserGroup']]);
            }
            ///
            include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
            if(JIEQI_SYSTEM_CHARSET == 'gbk') $jieqi_user_info['jieqiUserName_un']=jieqi_gb2unicode($_SESSION['jieqiUserName']);
            else $jieqi_user_info['jieqiUserName_un']=jieqi_big52unicode($_SESSION['jieqiUserName']);
            $jieqi_user_info['jieqiUserLogin']=JIEQI_NOW_TIME;
            $cookietime=JIEQI_NOW_TIME+3600;
            @setcookie('jieqiUserInfo', jieqi_sarytostr($jieqi_user_info), $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
            $jieqi_visit_info['jieqiUserLogin']=$jieqi_user_info['jieqiUserLogin'];
            $jieqi_visit_info['jieqiUserId']=$jieqi_user_info['jieqiUserId'];
            @setcookie('jieqiVisitInfo', jieqi_sarytostr($jieqi_visit_info), JIEQI_NOW_TIME+99999999, '/',  JIEQI_COOKIE_DOMAIN, 0);

            //推广积分
            if(JIEQI_PROMOTION_REGISTER > 0 && !empty($_COOKIE['jieqiPromotion'])){
                $users_handler->changeCredit(intval($_COOKIE['jieqiPromotion']), intval(JIEQI_PROMOTION_REGISTER), true);
                setcookie('jieqiPromotion', '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
            }

            include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
            if (isset($params['jumpurl'])){
                $params['url'] = JIEQI_LOCAL_URL.'/wxlogin/jumpurl?param='.urlencode($params['jumpurl']);
            }else{

                $params['url'] = JIEQI_LOCAL_URL.'/wxlogin/jumpurl';
            }
            unset($_SESSION['openid_'.$params['type']]);

            if(in_array(JIEQI_MODULE_NAME,array('3gwap','wap','3g'))){
                header('Location: '.$params['url']);exit;
            }
            else
                jieqi_logindo($params['url']);
        }
    }
}
