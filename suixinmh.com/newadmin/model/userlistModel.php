<?php 
/** 
 * 菜单 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class userlistModel extends Model{

    function main($param){
        $uid=intval($param['uid']);
        if ($param['action']=='del') {
            if ($uid) {
                $this->db->init("users","uid","system");
                $userinfo=$this->db->get($uid);
                if ($userinfo['groupid']!=JIEQI_GROUP_CPS) {
                    $this->fail("该账号不是子渠道账号，不可删除");
                    exit();
                }
                if ($this->db->selectsql("select * from jieqi_system_qdlist where cps_id='$uid'")) {
                    $this->fail("该账号还有下属的渠道存在，不可删除");
                    exit();
                }
                if ($this->db->selectsql("select * from jieqi_pay_paylog where source like '$uid-%'")) {
                    $this->fail("该账号已产生充值数据，不可删除");
                    exit();
                }
                $this->db->delete($uid);
            }
        }

        $sql="select a.uname,b.* from jieqi_system_users a,jieqi_system_usersext b where a.uid=b.uid and a.groupid=".JIEQI_GROUP_CPS;
        if ($uid && $param['action']!='del') {
            $sql.=" and a.uid=$uid";
        }
        if ($param['key']) {
            $key=trim($param['key']);
            $sql.=" and (a.uname like '%$key%' or b.name like '%$key%') ";
        }
        if(!$param['page']) $param['page'] = 1;

        $pagesize=50;

        $start=($param['page']-1) * $pagesize;
        $sql.=" limit $start,$pagesize";


        $res=$this->db->query($sql);
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $row['uname'] = iconv("gbk","utf-8",$row['uname']);
            $row['name'] = iconv("gbk","utf-8",$row['name']);
            $result[]=$row;
        }



        $param['qd_rows'] = $result;
        return $param;
    }

    function addview($param) {
        $uid=intval($param['uid']);
        if ($uid) {
            $sql="select a.uname,b.* from jieqi_system_users a,jieqi_system_usersext b where a.uid=b.uid and a.uid=$uid and a.groupid=".JIEQI_GROUP_CPS;
            $res=$this->db->query($sql);
            $userinfo=$this->db->getRow($res);


            $param['uname'] = iconv("GBK","UTF-8",$userinfo['uname']);
            $param['name'] = iconv("GBK","UTF-8",$userinfo['name']);

            $param['action'] = "update";
        }
        else {
            $param['action'] = "insert";
        }
        return $param;
    }


    function add($params) {
        global $jieqiLang, $jieqiConfigs, $jieqiModules;
        define('JIEQI_NEED_SESSION', 1);
        //检查密码
        if (strlen($params['password'])==0 || (strlen($params['repassword'])==0 && !$params['norepsw']))  {
            $this->fail( $jieqiLang['system']['need_pass_repass']);
        }
        elseif ($params['password'] != $params['repassword'] && $params['repassword']) {
            $this->fail( $jieqiLang['system']['password_not_equal']);
        }


        $params['username'] = iconv("UTF-8","GBK",trim($params['username']));
        $params['password'] = trim($params['password']);
        $params['repassword'] = trim($params['repassword']);

        if(empty($params['checkcode'])) $params['checkcode']='';
        else $params['checkcode'] = trim($params['checkcode']);

        $errtext='';
        include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
        include_once(JIEQI_ROOT_PATH.'/class/users.php');
        $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
        $errtext .= $this->checkUser($params, true);


        if(empty($errtext)) {
            $newUser = $users_handler->create();
            $newUser->setVar('siteid', $jieqiModules[JIEQI_MODULE_NAME]['siteid']);
            $newUser->setVar('uname', $params['username']);
            $newUser->setVar('name', $params['username']);
            $newUser->setVar('pass', $users_handler->encryptPass($params['password']));
            $newUser->setVar('groupid', 5);
            $newUser->setVar('regdate', JIEQI_NOW_TIME);
            $newUser->setVar('initial', jieqi_getinitial($params['username']));
            $newUser->setVar('sex', $params['sex']);
            if(!$params['wap_email']){$newUser->setVar('email', $params['email']);}
            $newUser->setVar('email', $params['email']);
            $newUser->setVar('url', $params['url']);
            $newUser->setVar('avatar', 0);
            $newUser->setVar('workid', 0);
            $newUser->setVar('qq', $params['qq']);
            $newUser->setVar('icq', '');
            $newUser->setVar('msn', $params['msn']);
            $newUser->setVar('mobile', '');
            $newUser->setVar('sign', '');
            $newUser->setVar('intro', '');
            $userset = array();
            $userset['logindate'] = date('Y-m-d H:i:s',JIEQI_NOW_TIME);
            $userset['lastip'] = $this->getIp();
            $userset['source'] = $_SESSION['SOURCE_SITE'] ? $_SESSION['SOURCE_SITE'] : $_COOKIE['SOURCE_SITE'] ;
            if (strpos($userset['source'],'_')!==false) {
                $source_x=explode('_',$userset['source']);
                $source=$source_x[0];
                $book_id=$source_x[1];
            }
            else {
                $source=$userset['source'];
                $book_id = 0;
            }
            $newUser->setVar('setting', serialize($userset));
            $newUser->setVar('badges', '');
            $newUser->setVar('lastlogin', JIEQI_NOW_TIME);
            $newUser->setVar('showsign', 0);
            $newUser->setVar('viewemail', $params['viewemail']);
            $newUser->setVar('notifymode', 0);
            $newUser->setVar('adminemail', $params['adminemail']);
            $newUser->setVar('monthscore', 0);
            $newUser->setVar('experience', $jieqiConfigs['system']['scoreregister']);
            $newUser->setVar('score', $jieqiConfigs['system']['scoreregister']);
            $newUser->setVar('source', $source);
            $newUser->setVar('book_id', $book_id);
            $newUser->setVar('egold', 0);
            $newUser->setVar('esilver', 0);
            $newUser->setVar('credit', 0);
            $newUser->setVar('goodnum', 0);
            $newUser->setVar('badnum', 0);
            $newUser->setVar('isvip', 0);
            $newUser->setVar('overtime', 0);
            $newUser->setVar('state', 0);
            if (!$users_handler->insert($newUser)) $this->fail($jieqiLang['system']['register_failure']);
            else {
                $this->db->init("usersext","uid","system");
                $usersext=array(
                    'uid'=>$newUser->getVar('uid'),
                    'qq'=>trim($params['qq']),
                    'wechat'=>trim($params['wechat']),
                    'mobile'=>trim($params['mobile']),
                    'payee'=>iconv("UTF-8","GBK",trim($params['payee'])),
                    'bankaddress'=>iconv("UTF-8","GBK",trim($params['bankaddress'])),
                    'banknumber'=>iconv("UTF-8","GBK",trim($params['banknumber']))
                );
                $this->db->add($usersext);
                header("location:index.php?controller=userlist");
            }
        } else {
            $this->fail($errtext);
        }
    }

    function editview($param) {
        $uid=intval($param['uid']);
        if ($uid) {
            $sql="select a.uname,b.* from jieqi_system_users a,jieqi_system_usersext b where a.uid=b.uid and a.uid=$uid and a.groupid=".JIEQI_GROUP_CPS;
            $res=$this->db->query($sql);
            $userinfo=$this->db->getRow($res);



            $param['username'] = iconv("GBK","UTF-8",$userinfo['uname']);
            $param['name'] = iconv("GBK","UTF-8",$userinfo['name']);
            $param['bankaddress'] = iconv("GBK","UTF-8",$userinfo['bankaddress']);
            $param['banknumber'] = iconv("GBK","UTF-8",$userinfo['banknumber']);
            $param['payee'] = iconv("GBK","UTF-8",$userinfo['payee']);
            $param['qq'] = iconv("GBK","UTF-8",$userinfo['qq']);
            $param['mobile'] = iconv("GBK","UTF-8",$userinfo['mobile']);
            $param['wechat'] = iconv("GBK","UTF-8",$userinfo['wechat']);
            $param['uid'] = iconv("GBK","UTF-8",$uid);
            $param['action'] = "update";
        }
        else {
            $param['action'] = "insert";
        }
        return $param;
    }


    function edit($params=array()) {
        global $jieqiLang, $jieqiConfigs, $jieqiModules;
        define('JIEQI_NEED_SESSION', 1);
        //检查密码
        if ($params['password'] != $params['repassword'] && $params['repassword']) {
            $this->fail( $jieqiLang['system']['password_not_equal']);
        }
        if ($params['password']) {
            include_once(JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
            include_once(JIEQI_ROOT_PATH . '/class/users.php');
            $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
            $password = $users_handler->encryptPass($params['password']);
            $sql="update jieqi_system_users set pass='$password' where uid=".$params['uid'];
            $this->db->query($sql);

        }
        $this->db->init("usersext","uid","system");
        $usersext=array(
            'qq'=>iconv("UTF-8","GBK",trim($params['qq'])),
            'wechat'=>iconv("UTF-8","GBK",trim($params['wechat'])),
            'mobile'=>iconv("UTF-8","GBK",trim($params['mobile'])),
            'name'=> iconv("UTF-8","GBK",$params['name'])
        );


        $this->db->edit($params['uid'],$usersext);



        header("location:index.php?controller=userlist");
    }

    function checkUser($params = array(),$isreturn = false){
        global  $jieqiLang;
        //载入语言
        $this->addLang('system', 'users');
        $retmsg = array();
        $errtext = '';
        include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
        include_once(JIEQI_ROOT_PATH.'/class/users.php');
        $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
        //$_REQUEST['ajax_request'] = 1;
        if (strlen($params['username'])==0) $this->printfail($jieqiLang['system']['need_username']);
        if (strlen($params['username'])>30 || strlen($params['username'])<1) $this->printfail($jieqiLang['system']['register_user_length']);
        elseif (!jieqi_safestring($params['username'])) $this->printfail( $jieqiLang['system']['error_user_format']);
        elseif(!preg_match('/^[\x7f-\xffa-zA-Z0-9_\.\@\-]{2,30}$/is', $params['username'])) $this->printfail( $jieqiLang['system']['error_user_format']);
        elseif(preg_match('/^\s*$|^c:\\con\\con$|[%,;\|\*\"\'\\\\\/\s\t\<\>\&]/is', $params['username'])) $this->printfail( $jieqiLang['system']['error_user_format']);
        elseif (strpos($params['username'], '　') !== false) $this->printfail( $jieqiLang['system']['error_user_format']);
        elseif($jieqiConfigs['system']['usernamelimit']==1 && !preg_match('/^[A-Za-z0-9]+$/',$params['username'])) $this->printfail( $jieqiLang['system']['username_need_engnum']);
        elseif($users_handler->getByname($params['username'], 3)>0){//是否已注册
            $errtext = $jieqiLang['system']['user_has_registered'];
        }
        if($errtext){
            include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
            $retmsg['error'] = $errtext;
        }else  $retmsg['ok'] = '';
        if(!$isreturn) exit($this->json_encode($retmsg));
        else return $errtext;
    }

    function succ($url = '', $title = '', $content = '', $ext = array())
    {

        if (!$url) $url = JIEQI_REFER_URL;
        if (!$title) $title = LANG_NOTICE;
        if (!$content) $content = LANG_DO_SUCCESS;
        if (empty($_REQUEST['ajax_request'])) {
            if (JIEQI_VERSION_TYPE != '' && JIEQI_VERSION_TYPE != 'Free') {
                include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
                $url = jieqi_htmlstr($url);
                $title = jieqi_htmlstr($title);
                $jieqiTpl =& JieqiTpl::getInstance();
                $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'pagetitle' => $title, 'title' => $title, 'content' => $content, 'url' => $url, 'ext' => $ext));
                $jieqiTpl->setCaching(0);
                $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/jumppage_utf8.html');
            } else {
                echo '<html><head><meta http-equiv="content-type" content="text/html; charset=' . JIEQI_CHAR_SET . '" /><meta http-equiv="refresh" content="3; url=' . $url . '">
<title>' . jieqi_htmlstr($title) . '</title><link rel="stylesheet" type="text/css" media="all" href="' . JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style.css" /></head><body><div id="msgboard" style="position:absolute; left:210px; top:150px; width:350px; height:100px; z-index:1;"><table class="grid" width="100%" border="0" cellspacing="1" cellpadding="6" ><caption>' . jieqi_htmlstr($title) . '</caption><tr><td class="even"><br />' . $content . '<br /><br />如不能自动跳转，<a href="' . $url . '">点击这里直接进入！</a><br /><br />程序设计：<a href="#">QQ329222795</a><br /><br /></td></tr></table></div></body></html>';
            }
        } else {
            header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
            include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
            $data = array('status' => 'OK', 'msg' => jieqi_gb2utf8($content), 'jumpurl' => urldecode($url));
            if ($_REQUEST['CALLBACK']) {
                echo($_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');');
            } else echo(json_encode($data));
        }
        jieqi_freeresource();
        exit;
    }

    function fail($errorinfo = '', $ext = array())
    {
        $errorinfo = iconv("gbk","utf-8",$errorinfo);
        if (!$errorinfo) $errorinfo = LANG_DO_FAILURE;
        if (defined('JIEQI_NOCONVERT_CHAR') && !empty($GLOBALS['charset_convert_out'])) @ob_start($GLOBALS['charset_convert_out']);
        $debuginfo = '';
        if (defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) {
            $trace = debug_backtrace();
            $debuginfo = 'FILE: ' . jieqi_htmlstr($trace[0]['file']) . ' LINE:' . jieqi_htmlstr($trace[0]['line']);
        }
        if (!$errorinfo) $errorinfo = LANG_DO_FAILURE;
        if (empty($_REQUEST['ajax_request'])) {
            include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
            $jieqiTpl =& JieqiTpl::getInstance();
            $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'errorinfo' => $errorinfo, 'debuginfo' => $debuginfo, 'jieqi_sitename' => JIEQI_SITE_NAME, 'ext' => $ext));
            $jieqiTpl->setCaching(0);
            $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/msgerr_utf8.html');
        } else {
            header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
            include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
            $data = array('status' => 'NO', 'msg' => jieqi_gb2utf8($errorinfo), 'jumpurl' => '');
            if ($_REQUEST['CALLBACK']) {
                echo $_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');';
            } else echo(json_encode($data));
        }
        jieqi_freeresource();
        exit;
    }

} 

?>