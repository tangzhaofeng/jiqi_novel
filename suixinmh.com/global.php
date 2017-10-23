<?php
$tmpvar = explode(' ', microtime());
define('JIEQI_START_TIME', $tmpvar[1] + $tmpvar[0]);
if (defined('JIEQI_PHP_CLI')) exit('error defined JIEQI_PHP_CLI');
if ((!empty($_SERVER['SCRIPT_FILENAME']) && $_SERVER['SCRIPT_FILENAME'] == $_SERVER['argv'][0]) || (empty($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['argv'][0]))) define('JIEQI_PHP_CLI', 1);
else define('JIEQI_PHP_CLI', 0);
if (defined('JIEQI_SCRIPT_FILENAME')) exit('error defined JIEQI_SCRIPT_FILENAME');
if (!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME', 'system');
$tmpvar = (!empty($_SERVER['PATH_TRANSLATED']) && substr($_SERVER['PATH_TRANSLATED'], -4) == '.php') ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
define('JIEQI_SCRIPT_FILENAME', str_replace(array('\\\\', '\\'), '/', $tmpvar));
include_once('configs/define.php');
@define('JIEQI_NEED_SESSION', true);
@set_magic_quotes_runtime(0);
if (JIEQI_ERROR_MODE == 0) {
    @ini_set('$lines_configs', 0);
    @error_reporting(0);
} elseif (JIEQI_ERROR_MODE == 1) {
    @ini_set('$lines_configs', 1);
    @error_reporting(E_ALL & ~E_NOTICE);
} elseif (JIEQI_ERROR_MODE == 2) {
    @ini_set('$lines_configs', 1);
    @error_reporting(E_ALL);
}
set_error_handler("jieqi_customerror");
if (!defined('JIEQI_SITE_ID')) define('JIEQI_SITE_ID', 0);
if (defined('JIEQI_LOCAL_HOST')) exit('error defined JIEQI_LOCAL_HOST');
if ($_SERVER['HTTP_HOST'] == '' && JIEQI_URL != '') define('JIEQI_LOCAL_HOST', str_replace(array('http://', 'https://'), '', JIEQI_URL));
else define('JIEQI_LOCAL_HOST', $_SERVER['HTTP_HOST']);
$_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES);
define("JIEQI_NOW_TIME", time());
define("JIEQI_VERSION", "3.0");
define('JIEQI_GLOBAL_INCLUDE', true);
if (!defined('JIEQI_ROOT_PATH')) @define('JIEQI_ROOT_PATH', str_replace(array('\\\\', '\\'), '/', dirname(__FILE__)));
if (!defined('JIEQI_COOKIE_DOMAIN')) define('JIEQI_COOKIE_DOMAIN', strval(@ini_get('session.cookie_domain')));
elseif (JIEQI_COOKIE_DOMAIN != '') @ini_set('session.cookie_domain', JIEQI_COOKIE_DOMAIN);
define('JIEQI_SYSTEM_CHARSET', 'gbk');
if (JIEQI_URL == '') define('JIEQI_LOCAL_URL', 'http://' . $_SERVER['HTTP_HOST']);
else define('JIEQI_LOCAL_URL', JIEQI_URL);
if (!defined('JIEQI_MAIN_SERVER') || JIEQI_MAIN_SERVER == '') define('JIEQI_MAIN_URL', JIEQI_LOCAL_URL);
else define('JIEQI_MAIN_URL', JIEQI_MAIN_SERVER);
if (!defined('JIEQI_USER_ENTRY') || JIEQI_USER_ENTRY == '') define('JIEQI_USER_URL', JIEQI_LOCAL_URL);
else define('JIEQI_USER_URL', JIEQI_USER_ENTRY);
define('JIEQI_CURRENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"]);
define('JIEQI_REFER_URL', empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER']);
define('JIEQI_HTTP_HOST', $_SERVER['HTTP_HOST']);
define('JIEQI_ERROR_RETURN', 1);
define('JIEQI_ERROR_PRINT', 2);
define('JIEQI_ERROR_DIE', 4);
define('JIEQI_GROUP_USER', 3);
define('JIEQI_GROUP_ADMIN', 2);
define('JIEQI_GROUP_GUEST', 1);
define("JIEQI_SIDEBLOCK_CUSTOM", -1);
define("JIEQI_SIDEBLOCK_LEFT", 0);
define("JIEQI_SIDEBLOCK_RIGHT", 1);
define("JIEQI_CENTERBLOCK_LEFT", 2);
define("JIEQI_CENTERBLOCK_RIGHT", 3);
define("JIEQI_CENTERBLOCK_TOP", 4);
define("JIEQI_CENTERBLOCK_MIDDLE", 5);
define("JIEQI_CENTERBLOCK_BOTTOM", 6);
define("JIEQI_TOPBLOCK_ALL", 7);
define("JIEQI_BOTTOMBLOCK_ALL", 8);
define('JIEQI_TYPE_TXTBOX', 1);
define('JIEQI_TYPE_TXTAREA', 2);
define('JIEQI_TYPE_INT', 3);
define('JIEQI_TYPE_NUM', 4);
define('JIEQI_TYPE_PASSWORD', 5);
define('JIEQI_TYPE_HIDDEN', 6);
define('JIEQI_TYPE_SELECT', 7);
define('JIEQI_TYPE_MULSELECT', 8);
define('JIEQI_TYPE_RADIO', 9);
define('JIEQI_TYPE_CHECKBOX', 10);
define('JIEQI_TYPE_LABEL', 11);
define('JIEQI_TYPE_FILE', 12);
define('JIEQI_TYPE_DATE', 13);
define('JIEQI_TYPE_UBB', 14);
define('JIEQI_TYPE_HTML', 15);
define('JIEQI_TYPE_CODE', 16);
define('JIEQI_TYPE_SCRIPT', 17);
define('JIEQI_TYPE_OTHER', 20);
define('JIEQI_TARGET_SELF', 'self');
define('JIEQI_TARGET_NEW', 'blank');
define('JIEQI_TARGET_TOP', 'top');
define('JIEQI_CONTENT_TXT', 0);
define('JIEQI_CONTENT_HTML', 1);
define('JIEQI_CONTENT_JS', 2);
define('JIEQI_CONTENT_MIX', 3);
define('JIEQI_CONTENT_PHP', 4);
$jieqi_image_type = array(1 => '.gif', 2 => '.jpg', 3 => '.jpeg', 4 => '.png', 5 => '.bmp');
$jieqi_file_postfix = array('txt' => '.txt', 'html' => '.html', 'htm' => '.htm', 'xml' => '.xml', 'php' => '.php', 'js' => '.js', 'css' => '.css', 'zip' => '.zip', 'jar' => '.jar', 'jad' => '.jad', 'umd' => '.umd', 'opf' => '.opf');
$jieqi_charset_type = array('gb' => 'gbk', 'gbk' => 'gbk', 'gb2312' => 'gbk', 'big5' => 'big5', 'utf8' => 'utf-8', 'utf-8' => 'utf-8');
if (defined('JIEQI_MODULE_VTYPE')) exit('error defined JIEQI_MODULE_VTYPE');
$jieqi_license_ary = jieqi_funtoarray('base64_decode', explode('@', JIEQI_LICENSE_KEY));
if (!empty($jieqi_license_ary[1]) && !empty($jieqi_license_ary[2])) $jieqi_license_modules = jieqi_strtosary($jieqi_license_ary[2], '=', '|');
else $jieqi_license_modules = array();
$matchs = array();
if (empty($jieqi_license_modules) || (JIEQI_LOCAL_HOST == '' && JIEQI_PHP_CLI == 1 && ALLOW_PHP_CLI == 1) || preg_match('/^' . preg_quote(str_replace(array('\\\\', '\\'), '/', JIEQI_ROOT_PATH), '/') . '\/(admin|install|logout\.php)/is', JIEQI_SCRIPT_FILENAME) || preg_match('/^(http:\/\/|https:\/\/)?[^\/\?]*(localhost|127.0.0.1|shuhai.com|mvc.com)/i', JIEQI_LOCAL_HOST, $matchs)) {
} else {
    $site_is_licensed = false;
    if (!empty($jieqi_license_ary[1]) && preg_match('/^(http:\/\/|https:\/\/)?[^\/\?]*(' . $jieqi_license_ary[1] . ')/i', JIEQI_LOCAL_HOST, $matchs)) {
        $jieqi_license_domain = $jieqi_license_ary[1];
        $tmpvar = md5($jieqi_license_ary[1] . $jieqi_license_ary[2] . 'jieqi16020091001abcdefg');
        if ($tmpvar[0] == $jieqi_license_ary[0][0] && $tmpvar[9] == $jieqi_license_ary[0][9] && $tmpvar[2] == $jieqi_license_ary[0][2] && $tmpvar[11] == $jieqi_license_ary[0][11]) $site_is_licensed = true;
    }

//if(!$site_is_licensed){
//header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
//if(defined('JIEQI_IS_OPEN') &&JIEQI_IS_OPEN == 0) echo JIEQI_CLOSE_INFO;
//else echo 'License check error!<br />Domain: '.JIEQI_LOCAL_HOST.'<br />Module: '.JIEQI_MODULE_NAME.'<br /><br />Powered by <a href="http://www.shuhai.com" target="_blank">书海网</a>';
//exit;
//}
}
$site_is_licensed = true;
$Version160 = 'CUSTOM';
if (isset($jieqi_license_modules[JIEQI_MODULE_NAME]) && isset($jieqi_license_modules['system'])) {
    @define('JIEQI_VERSION_TYPE', $jieqi_license_modules['system']);
    @define('JIEQI_MODULE_VTYPE', $jieqi_license_modules[JIEQI_MODULE_NAME]);
} else {
    @define('JIEQI_MODULE_ISDEFINE', true);
    $Vurl = strchr(JIEQI_MAIN_URL, '.');
    if (in_array($Vurl, array('.shuhai.com', '.mvc.com'))) {
        $Version160 = 'CUSTOM';
    }
    @define('JIEQI_VERSION_TYPE', $Version160);
    @define('JIEQI_MODULE_VTYPE', $Version160);
}
if (isset($_SERVER['PATH_INFO']) && defined('JIEQI_PATH_INFO') && JIEQI_PATH_INFO > 0) {
    $tmpary = explode('/', str_replace(array("'", '"', '.htm', '.html'), '', substr($_SERVER['PATH_INFO'], 1)));
    $tmpcot = count($tmpary);
    for ($i = 0; $i < $tmpcot; $i += 2) {
        if (isset($tmpary[$i + 1]) && !is_numeric($tmpary[$i])) {
            $_GET[$tmpary[$i]] = $tmpary[$i + 1];
            $_REQUEST[$tmpary[$i]] = $tmpary[$i + 1];
        }
    }
}
$jieqiModules = array();
include_once('configs/modules.php');
if (isset($jieqiModules[JIEQI_MODULE_NAME]['publish']) && $jieqiModules[JIEQI_MODULE_NAME]['publish'] == 0) {
    header('Content-type:text/html;charset=' . JIEQI_SYSTEM_CHARSET);
    echo 'This function is not valid!';
    jieqi_freeresource();
    exit;
}
foreach ($jieqiModules as $k => $v) {
    if ($v['dir'] == '') $jieqiModules[$k]['dir'] = (($k == 'system') ? '' : '/modules/' . $k);
    if ($v['path'] == '') $jieqiModules[$k]['path'] = JIEQI_ROOT_PATH . $jieqiModules[$k]['dir'];
    if ($v['url'] == '') $jieqiModules[$k]['url'] = JIEQI_LOCAL_URL . $jieqiModules[$k]['dir'];
    if ($v['theme'] == '') $jieqiModules[$k]['theme'] = JIEQI_THEME_SET;
    if (defined('JIEQI_MODULE_NAME') && JIEQI_MODULE_NAME == $k) {
        if (!empty($jieqiModules[$k]['theme'])) @define('JIEQI_THEME_NAME', $jieqiModules[$k]['theme']);
    }
}
if (!defined('JIEQI_THEME_NAME')) define('JIEQI_THEME_NAME', JIEQI_THEME_SET);
if (isset($jieqiModules['wap']['path'])) define('JIEQI_WAP_PATH', $jieqiModules['wap']['path']);
else define('JIEQI_WAP_PATH', JIEQI_ROOT_PATH . '/wap');
if (isset($jieqiModules['wap']['url'])) define('JIEQI_WAP_URL', $jieqiModules['wap']['url']);
else define('JIEQI_WAP_URL', JIEQI_LOCAL_URL . '/wap');
if (defined('JIEQI_CHARSET_CONVERT') && JIEQI_CHARSET_CONVERT == 1 && JIEQI_VERSION_TYPE != '' && JIEQI_VERSION_TYPE != 'Free') {
    if (isset($_GET['charset'])) $_GET['charset'] = strtolower($_GET['charset']);
    if (isset($_GET['charset']) && isset($jieqi_charset_type[$_GET['charset']])) @define('JIEQI_CHAR_SET', $jieqi_charset_type[$_GET['charset']]);
    elseif (isset($_COOKIE['jieqiUserCharset']) && isset($jieqi_charset_type[$_COOKIE['jieqiUserCharset']])) @define('JIEQI_CHAR_SET', $jieqi_charset_type[$_COOKIE['jieqiUserCharset']]);
    else @define('JIEQI_CHAR_SET', JIEQI_SYSTEM_CHARSET);
} else {
    if (!defined('JIEQI_CHAR_SET')) @define('JIEQI_CHAR_SET', JIEQI_SYSTEM_CHARSET);
}
if (JIEQI_ENABLE_CACHE) define('JIEQI_USE_CACHE', true);
else define('JIEQI_USE_CACHE', false);
if (!defined('JIEQI_CACHE_DIR') || JIEQI_CACHE_DIR == '' || strtolower(substr(trim(JIEQI_CACHE_DIR), 0, 12)) == 'memcached://') $tmpvar = JIEQI_ROOT_PATH . '/cache';
elseif (strpos(JIEQI_CACHE_DIR, '/') === false && strpos(JIEQI_CACHE_DIR, '\\') === false) $tmpvar = JIEQI_ROOT_PATH . '/' . JIEQI_CACHE_DIR;
else $tmpvar = JIEQI_CACHE_DIR;
if (!is_dir($tmpvar)) jieqi_createdir($tmpvar);
define('JIEQI_CACHE_PATH', $tmpvar);
if (!defined('JIEQI_COMPILED_DIR') || JIEQI_COMPILED_DIR == '') define('JIEQI_COMPILED_PATH', JIEQI_ROOT_PATH . '/compiled');
elseif (strpos(JIEQI_COMPILED_DIR, '/') === false && strpos(JIEQI_COMPILED_DIR, '\\') === false) define('JIEQI_COMPILED_PATH', JIEQI_ROOT_PATH . '/' . JIEQI_COMPILED_DIR);
else define('JIEQI_COMPILED_PATH', JIEQI_COMPILED_DIR);
if (isset($_COOKIE[session_name()]) && strlen($_COOKIE[session_name()]) < 16) unset($_COOKIE[session_name()]);
if (JIEQI_USE_GZIP && !(boolean)@ini_get('zlib.output_compression')) @ob_start("ob_gzhandler");
if (!empty($_COOKIE[session_name()]) || defined('JIEQI_NEED_SESSION')) {
    if (JIEQI_SESSION_EXPRIE > 0) @ini_set('session.gc_maxlifetime', JIEQI_SESSION_EXPRIE);
    @session_cache_limiter('private, must-revalidate');
    if (JIEQI_SESSION_STORAGE == 'db') {
        include_once(JIEQI_ROOT_PATH . '/include/session.php');
        $sess_handler =& JieqiSessionHandler::getInstance('JieqiSessionHandler');
        @session_set_save_handler(array(&$sess_handler, 'open'), array(&$sess_handler, 'close'), array(&$sess_handler, 'read'), array(&$sess_handler, 'write'), array(&$sess_handler, 'destroy'), array(&$sess_handler, 'gc'));
    }
    elseif (JIEQI_SESSION_STORAGE == 'redis') {
        ini_set("session.save_handler","redis");
        ini_set("session.save_path",JIEQI_SESSION_SAVEPATH);
    }
    else {
        if (JIEQI_SESSION_SAVEPATH != '' && is_dir(JIEQI_SESSION_SAVEPATH)) session_save_path(JIEQI_SESSION_SAVEPATH);
    }
    if (!empty($_COOKIE[session_name()])) session_id($_COOKIE[session_name()]);
    @session_start();
    if (!empty($_COOKIE[session_name()]) && !empty($_COOKIE['jieqiUserInfo']) && count($_SESSION) == 0) {
        include_once(JIEQI_ROOT_PATH . '/class/online.php');
        $online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
        $criteria = new CriteriaCompo(new Criteria('sid', $_COOKIE[session_name()], '='));
        $result = $online_handler->queryObjects($criteria);
        $srow = $online_handler->getRow($result);
        if (!empty($srow['uid'])) {
            include_once(JIEQI_ROOT_PATH . '/class/users.php');
            $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
            $jieqiUsers = $users_handler->get($srow['uid']);
            if (is_object($jieqiUsers)) {
                jieqi_setusersession($jieqiUsers);
            }
        }
    }
}
$magic_quotes_gpc = get_magic_quotes_gpc();
$register_globals = @ini_get('register_globals');
if ($magic_quotes_gpc) {
    $_GET = jieqi_funtoarray('stripslashes', $_GET);
    $_POST = jieqi_funtoarray('stripslashes', $_POST);
    $_COOKIE = jieqi_funtoarray('stripslashes', $_COOKIE);
}
new StopAttack();
$charsetary = array('gb2312' => 'gb', 'gbk' => 'gb', 'gb' => 'gb', 'big5' => 'big5', 'utf-8' => 'utf8', 'utf8' => 'utf8');
if (JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET || (!empty($_REQUEST['ajax_request']) && $charsetary[JIEQI_CHAR_SET] != 'utf8')) {
    include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
}
if (!empty($_REQUEST['ajax_request']) && $charsetary[JIEQI_CHAR_SET] != 'utf8') {
    $charset_convert_ajax = 'jieqi_' . $charsetary['utf8'] . '2' . $charsetary[JIEQI_CHAR_SET];
    $_POST =& jieqi_funtoarray($charset_convert_ajax, $_POST);
}
$charset_convert_out = '';
if (JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET) {
    $charset_convert_out = 'jieqi_' . $charsetary[JIEQI_SYSTEM_CHARSET] . '2' . $charsetary[JIEQI_CHAR_SET];
    if (!defined('JIEQI_NOCONVERT_CHAR')) @ob_start($charset_convert_out);
    $charset_convert_in = 'jieqi_' . $charsetary[JIEQI_CHAR_SET] . '2' . $charsetary[JIEQI_SYSTEM_CHARSET];
    $_GET =& jieqi_funtoarray($charset_convert_in, $_GET);
    $_POST =& jieqi_funtoarray($charset_convert_in, $_POST);
    $_COOKIE =& jieqi_funtoarray($charset_convert_in, $_COOKIE);
}
if ($magic_quotes_gpc || JIEQI_SYSTEM_CHARSET != JIEQI_CHAR_SET || (!empty($_REQUEST['ajax_request']) && $charsetary[JIEQI_CHAR_SET] != 'utf8')) $_REQUEST = array_merge($_REQUEST, $_GET, $_POST, $_COOKIE);
if (defined('JIEQI_MAX_PAGES') && JIEQI_MAX_PAGES > 0 && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > JIEQI_MAX_PAGES) $_REQUEST['page'] = intval(JIEQI_MAX_PAGES);
$jieqiUsersStatus = JIEQI_GROUP_GUEST;
$jieqiUsersGroup = JIEQI_GROUP_GUEST;
$jieqiCache =& jieqi_initcache();
if (empty($_SESSION['jieqiUserId'])) {
    if (!empty($_REQUEST['system_username']) && !empty($_REQUEST['system_userpassword'])) {
        @session_start();
        include_once(JIEQI_ROOT_PATH . '/include/checklogin.php');
        $urllogin = jieqi_logincheck($_REQUEST['system_username'], $_REQUEST['system_userpassword'], '', false, false, false);
        if ($urllogin == 0) $_SESSION['jieqiAdminLogin'] = 1;
    } elseif (!empty($_COOKIE['jieqiUserInfo'])) {
        $jieqi_user_info = jieqi_strtosary($_COOKIE['jieqiUserInfo']);
        if (!empty($jieqi_user_info['jieqiUserUname']) && !empty($jieqi_user_info['jieqiUserPassword'])) {
            @session_start();
            include_once(JIEQI_ROOT_PATH . '/include/checklogin.php');
            jieqi_logincheck($jieqi_user_info['jieqiUserUname'], $jieqi_user_info['jieqiUserPassword'], '', true, true, false);
        } else @setcookie('jieqiUserInfo', '', time() - 100, '/', JIEQI_COOKIE_DOMAIN, 0);
    }
}
if (!empty($_SESSION['jieqiUserGroup'])) {
    $jieqiUsersGroup = $_SESSION['jieqiUserGroup'];
    switch ($_SESSION['jieqiUserGroup']) {
        case JIEQI_GROUP_GUEST:
            $jieqiUsersStatus = JIEQI_GROUP_GUEST;
            break;
        case JIEQI_GROUP_ADMIN:
            $jieqiUsersStatus = JIEQI_GROUP_ADMIN;
            define('JIEQI_IS_ADMIN', 1);
            break;
        default:
            $jieqiUsersStatus = JIEQI_GROUP_USER;
            break;
    }
}
if (defined('JIEQI_IS_OPEN') && JIEQI_IS_OPEN == 0 && !defined('JIEQI_ADMIN_LOGIN') && $jieqiUsersStatus != JIEQI_GROUP_ADMIN) {
    header('Content-type:text/html;charset=' . JIEQI_SYSTEM_CHARSET);
    echo JIEQI_CLOSE_INFO;
    jieqi_freeresource();
    exit;
} elseif (defined('JIEQI_IS_OPEN') && JIEQI_IS_OPEN == 2 && !defined('JIEQI_ADMIN_LOGIN') && $jieqiUsersStatus != JIEQI_GROUP_ADMIN && count($_POST) > 0) {
    header('Content-type:text/html;charset=' . JIEQI_SYSTEM_CHARSET);
    echo LANG_DENY_POST;
    jieqi_freeresource();
    exit;
}
if (defined('JIEQI_PROXY_DENIED') && JIEQI_PROXY_DENIED != 1) {
    if ($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
        header('Content-type:text/html;charset=' . JIEQI_SYSTEM_CHARSET);
        echo LANG_DENY_PROXY;
        jieqi_freeresource();
        exit;
    }
}
if (defined('JIEQI_CUSTOM_INCLUDE') && JIEQI_CUSTOM_INCLUDE == 1) {
    $tmpstr = $_SERVER['PHP_SELF'] ? basename($_SERVER['PHP_SELF']) : basename($_SERVER['SCRIPT_NAME']);
    if (preg_match('/\.php$/i', $tmpstr)) {
        $tmpstr = @realpath(substr($tmpstr, 0, -4)) . '.inc.php';
        if (is_file($tmpstr) && preg_match('/\.inc\.php$/i', $tmpstr)) include_once($tmpstr);
    }
}
function jieqi_customerror($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        return;
    }
    switch ($errno) {
        case E_USER_ERROR:
            echo "<b>My ERROR</b> [$errno] $errstr<br/>";
            echo "错误行：{$errline} 在文件：{$errfile} 之中<br/>";
            echo " PHP版本： " . PHP_VERSION . " (" . PHP_OS . ")<br/>";
            break;
        case E_USER_WARNING:
            echo "<b>My WARNING</b> [$errno] $errstr<br/>";
            break;
        case E_USER_NOTICE:
            echo "<b>My NOTICE</b> [$errno] $errstr<br />";
            break;
        default:
            echo "Unknown error type: [$errno] $errstr<br />";
            break;
    }
    return true;
}

function jieqi_jumppage($url = '', $title = '', $content = '', $ext = array())
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
            $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/jumppage.html');
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

function jieqi_msgbox($title = '', $content = '', $ext = array())
{
    if (!$title) $title = LANG_NOTICE;
    if (!$content) $content = LANG_DO_SUCCESS;
    if (empty($_REQUEST['ajax_request'])) {
        include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
        $title = jieqi_htmlstr($title);
        $jieqiTpl =& JieqiTpl::getInstance();
        $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'pagetitle' => $title, 'title' => $title, 'content' => $content, 'url' => $url, 'ext' => $ext));
        $jieqiTpl->setCaching(0);
        $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/msgbox.html');
    } else {
        header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
        include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
        $data = array('status' => 'OK', 'msg' => jieqi_gb2utf8($content), 'jumpurl' => '');
        if ($_REQUEST['CALLBACK']) {
            echo($_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');');
        } else echo(json_encode($data));
    }
}

function jieqi_msgwin($title = '', $content = '', $ext = array())
{
    if (!$title) $title = LANG_NOTICE;
    if (!$content) $content = LANG_DO_SUCCESS;
    if (defined('JIEQI_NOCONVERT_CHAR') && !empty($GLOBALS['charset_convert_out'])) @ob_start($GLOBALS['charset_convert_out']);
    if (empty($_REQUEST['ajax_request'])) {
        include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
        $title = jieqi_htmlstr($title);
        $jieqiTpl =& JieqiTpl::getInstance();
        $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'title' => $title, 'content' => $content, 'jieqi_sitename' => JIEQI_SITE_NAME, 'ext' => $ext));
        $jieqiTpl->setCaching(0);
        $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/msgwin.html');
    } else {
        header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
        include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
        $data = array('status' => 'OK', 'msg' => jieqi_gb2utf8($content), 'jumpurl' => '');
        if ($_REQUEST['CALLBACK']) {
            echo($_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');');
        } else echo(json_encode($data));
    }
    jieqi_freeresource();
    exit;
}

function jieqi_printfail($errorinfo = '', $ext = array())
{
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
        $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/msgerr.html');
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

function jieqi_userip()
{
    $ip = jieqi_userip_ext();
    if ($ip != '' && $ip != '0.0.0.0') {
        return $ip;
    }
    else {
        if ($HTTP_SERVER_VARS["REMOTE_ADDR"]) {
            $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
        } elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"]) {
            $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } elseif ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"]) {
            $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else {
            $ip = "Unknown";
        }
        $ip = trim($ip);
        if (!is_numeric(str_replace('.', '', $ip))) $ip = '';
        return $ip;
    }
}

function jieqi_userip_ext()
{
    $ip = trim($_SERVER['REMOTE_ADDR']);
    $fowward_ip = trim($_SERVER['HTTP_X_FORWARDED_FOR']);

    if (!is_numeric(str_replace('.', '', $ip))) $ip = '';
    if ($fowward_ip && !is_numeric(str_replace(array('.',','), '', $fowward_ip))) $fowward_ip = '';
    if ($fowward_ip && $fowward_ip!=$ip) {
        $ip.='|'.$fowward_ip;
    }
    return $ip;
}

function get_salle_table($uid) {
    $sale_tableid=sprintf("%02d",$uid%100);
    $sale_table="sale_$sale_tableid";
    return $sale_table;
}

function jieqi_getsubdir($id, $divnum = 1000)
{
    return '/' . floor(intval($id) / $divnum);
}

function jieqi_geturl($module, $target)
{
    global $jieqiModules;
    if (!isset($jieqiModules[$module])) $module = 'system';
    $funname = 'jieqi_url_' . $module . '_' . $target;
    if (!function_exists($funname) && isset($jieqiModules[$module]['path']) && is_file($jieqiModules[$module]['path'] . '/include/funurl.php')) {
        include_once($jieqiModules[$module]['path'] . '/include/funurl.php');
    }
    if (function_exists($funname)) {
        $numargs = func_num_args();
        $args = func_get_args();
        switch ($numargs) {
            case 0:
            case 1:
            case 2:
                return $funname();
                break;
            case 3:
                return $funname($args[2]);
                break;
            case 4:
                return $funname($args[2], $args[3]);
                break;
            case 5:
                return $funname($args[2], $args[3], $args[4]);
                break;
            case 6:
            default:
                return $funname($args[2], $args[3], $args[4], $args[5]);
                break;
        }
    } else {
        return false;
    }
}

function geturl($module, $controller)
{
    global $jieqiModules, $jieqiUrl;
    jieqi_getConfigs($module, 'url', 'jieqiUrl');
    if (!isset($jieqiModules[$module])) $module = 'system';
    $args = func_get_args();
    for ($i = 2; $i <= count($args) - 1; $i++) {
        if (substr_count($args[$i], '=')) {
            $tmp = explode('=', $args[$i]);
            $fname = $tmp[0];
            if ($fname != 'SYS') $$fname = $tmp[1];
            else {
                unset($tmp[0]);
                $$fname = implode('=', $tmp);
            }
        }
    }
    $key = '';
    if (!isset($method) && !$method) $method = 'main';
    $key = $controller . '_' . $method;
    if (isset($jieqiUrl[$module][$key])) $P = $jieqiUrl[$module][$key];
    else return '';
    $urlrule = '';
    if (!$P['rule']) $P['rule'] = "?controller={$P['controller']}" . ($P['method'] ? '&method=' . $P['method'] : '') . ($P['params'] ? '&' . $P['params'] : '');
    if ($SYS) {
        $tmp_a = explode('&', $SYS);
        $key_a = array();
        foreach ($tmp_a as $k => $v) {
            $tmp = explode('=', $v);
            $fname = $tmp[0];
            $$fname = $tmp[1];
            if (!substr_count($P['rule'], "$fname=") && !substr_count($P['rule'], '$' . $fname)) {
                $key_a[] = $fname . '=' . $tmp[1];
            }
        }
        $SYS = implode('&', $key_a);
    }
    if ($method == 'main') $method = '';
    if (isset($evalpage) && !$evalpage) {
        $page = '<{$page}>';
        if (!substr_count($P['rule'], '$page')) {
            if (!substr_count($P['rule'], '?')) $P['rule'] = $P['rule'] . '?page={$page}';
            else $P['rule'] = $P['rule'] . '&page={$page}';
        }
    }
    if ($P['rule']) {
        if (strpos($P['rule'], '(') !== false) {
            preg_match_all("/\w+\(.*\)/", $P['rule'], $matches, PREG_SET_ORDER);
            if ($matches[0]) {
                $fromval = $toval = array();
                foreach ($matches[0] as $k => $v) {
                    $tmpstr = '$vv = ' . $v . ';';
                    eval($tmpstr);
                    $fromval[] = $v;
                    $toval[] = $vv;
                }
                $P['rule'] = str_replace($fromval, $toval, $P['rule']);
            }
        }
        eval('$urlrule = "' . addslashes_array($P['rule']) . '";');
    }
    if (strpos($urlrule, '://') === false) {
        if (substr($urlrule, 0, 1) != '/') {
            if ($module != 'system') $url = $jieqiModules[$module]['url'] . '/';
            else $url = JIEQI_LOCAL_URL . '/';
        } else {
            $url = JIEQI_LOCAL_URL;
        }
        $urlrule = $url . $urlrule;
    }
    if ($SYS) {
        if (strpos($urlrule, '?') !== false) $urlrule .= '&' . $SYS;
        else $urlrule .= '?' . $SYS;
    }
    return $urlrule;
}

function jieqi_uploadpath($path, $module = '', $systempath = '')
{
    if (strpos($path, '/') === false && strpos($path, '\\') === false) {
        if ($module == '' && defined('JIEQI_MODULE_NAME')) $module = JIEQI_MODULE_NAME;
        if ($systempath == '') $systempath = JIEQI_ROOT_PATH;
        if ($path == '') return $systempath . '/files/' . $module;
        else return $systempath . '/files/' . $module . '/' . $path;
    } else {
        return $path;
    }
}

function jieqi_uploadurl($path, $url = '', $module = '', $systemurl = '')
{
    if (!empty($url)) {
        return $url;
    } else {
        if ($module == '' && defined('JIEQI_MODULE_NAME')) $module = JIEQI_MODULE_NAME;
        if ($systemurl == '') $systemurl = JIEQI_URL;
        elseif (strpos($systemurl, '/modules') > 0) $systemurl = substr($systemurl, 0, strpos($systemurl, '/modules'));
        if ($path == '') return $systemurl . '/files/' . $module;
        else return $systemurl . '/files/' . $module . '/' . $path;
    }
}

function jieqi_checkpower($powerset = array(), $ustatus = '0', $ugroup = '0', $isreturn = false, $isadmin = false)
{
    if (empty($_POST)) {
        $local_domain_url = (empty($_SERVER['HTTP_HOST'])) ? '' : 'http://' . $_SERVER['HTTP_HOST'];
        $jumpurl = $local_domain_url . jieqi_addurlvars(array());
    } elseif (!empty($_SERVER['HTTP_REFERER'])) {
        $jumpurl = $_SERVER['HTTP_REFERER'];
    } else {
        $jumpurl = JIEQI_MAIN_URL;
    }
    if ((!isset($_SESSION['jieqiAdminLogin']) || $_SESSION['jieqiAdminLogin'] != 1) && !empty($_COOKIE['jieqiOnlineInfo'])) {
        $jieqi_online_info = jieqi_strtosary($_COOKIE['jieqiOnlineInfo']);
        if ($jieqi_online_info['jieqiAdminLogin'] == 1) $_SESSION['jieqiAdminLogin'] = 1;
    }
    if ($ustatus == JIEQI_GROUP_ADMIN) {
        if ($isadmin && empty($_SESSION['jieqiAdminLogin'])) {
            if ($isreturn) {
                return false;
            } else {
                header('Location: ' . JIEQI_LOCAL_URL . '/web_admin/login.php?jumpurl=' . urlencode($jumpurl));
                exit;
            }
        } else {
            return true;
        }
    } else {
        if (is_array($powerset['groups']) && (in_array($ugroup, $powerset['groups'], false) || in_array('0', $powerset['groups'], false))) {
            if ($isadmin && empty($_SESSION['jieqiAdminLogin'])) {
                if ($isreturn) {
                    return false;
                } else {
                    header('Location: ' . JIEQI_LOCAL_URL . '/web_admin/login.php?jumpurl=' . urlencode($jumpurl));
                    exit;
                }
            } else {
                return true;
            }
        } else {
            if ($isreturn) {
                return false;
            } else {
                if ($ugroup == JIEQI_GROUP_GUEST) {
                    if ($isadmin) {
                        header('Location: ' . JIEQI_USER_URL . '/web_admin/login.php?jumpurl=' . urlencode($jumpurl));
                    } else {
                        header('Location: ' . JIEQI_USER_URL . '/login.php?jumpurl=' . urlencode($jumpurl));
                    }
                    exit;
                } else {
                    jieqi_printfail(LANG_NO_PERMISSION);
                }
            }
        }
    }
}

function jieqi_checklogin($isreturn = false, $isadmin = false)
{
    global $jieqiUsersGroup;
    if ($jieqiUsersGroup == JIEQI_GROUP_GUEST) $ret = false;
    else $ret = true;
    if ($isreturn) return $ret;
    elseif (!$ret) {
        if (empty($_REQUEST['ajax_request'])) {
            if (empty($_POST)) {
                $local_domain_url = (empty($_SERVER['HTTP_HOST'])) ? '' : 'http://' . $_SERVER['HTTP_HOST'];
                $jumpurl = $local_domain_url . jieqi_addurlvars(array());
            } elseif (!empty($_SERVER['HTTP_REFERER'])) {
                $jumpurl = $_SERVER['HTTP_REFERER'];
            } else {
                $jumpurl = JIEQI_MAIN_URL;
            }
            if ($isadmin) header('Location: ' . JIEQI_USER_URL . '/web_admin/?controller=login&jumpurl=' . urlencode($jumpurl));
            else header('Location: ' . $this->geturl('system', 'login', 'SYS=jumpurl=' . urlencode($jumpurl)));
        } else {
            header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
            header("Cache-Control:no-cache");
            echo LANG_NEED_LOGIN;
        }
        exit;
    }
}

function jieqi_setusersession($user)
{
    global $jieqiHonors;
    global $jieqiVipgrade;
    global $jieqiModules;
    $_SESSION['jieqiUserId'] = $user->getVar('uid', 'n');
    $_SESSION['jieqiUserUname'] = $user->getVar('uname', 'n');
    $_SESSION['jieqiUserName'] = (strlen($user->getVar('name', 'n')) > 0) ? $user->getVar('name', 'n') : $user->getVar('uname', 'n');
    $_SESSION['jieqiRealName'] = $user->getVar('realName', 'n');
    $_SESSION['jieqiUserGroup'] = $user->getVar('groupid', 'n');
    $_SESSION['jieqiUserEmail'] = $user->getVar('email', 'n');
    $_SESSION['jieqiUserAvatar'] = $user->getVar('avatar', 'n');
    $_SESSION['jieqiUserScore'] = $user->getVar('score', 'n');
    $_SESSION['jieqiUserExperience'] = $user->getVar('experience', 'n');
    $_SESSION['jieqiUserVip'] = $user->getVar('isvip', 'n');
    $_SESSION['jieqiUserEgold'] = ($user->getVar('egold', 'n') > 0 || $user->getVar('esilver', 'n') > 0) ? 1 : 0;
    $_SESSION['jieqiUserEgolds'] = $user->getVar('egold', 'n');
    $_SESSION['jieqiUserEsilvers'] = $user->getVar('esilver', 'n');
    $_SESSION['jieqiUserSource'] = $user->getVar('source', 'n');
    jieqi_getConfigs('system', 'honors');
    jieqi_getConfigs('system', 'vipgrade');
    $honorid = intval(jieqi_gethonorid($user->getVar('score'), $jieqiHonors));
    $_SESSION['jieqiUserHonorid'] = $honorid;
    $_SESSION['jieqiUserHonor'] = isset($jieqiHonors[$honorid]['name'][intval($user->getVar('workid', 'n'))]) ? $jieqiHonors[$honorid]['name'][intval($user->getVar('workid', 'n'))] : $jieqiHonors[$honorid]['caption'];
    $_SESSION['jieqiUserVipgrade'] = jieqi_gethonorarray($user->getVar('isvip'), $jieqiVipgrade);

    if ($user->getVar('groupid', 'n') == 5 && $user->getVar('overtime', 'n') >= time()) {
        $_SESSION['jieqiUserVipgrade']['photo'] = 'goldvip';
        $_SESSION['jieqiUserOvertime'] = $user->getVar('overtime', 'n');
    }
    else {
        $_SESSION['jieqiUserVipgrade']['photo'] = $jieqiVipgrade[$vipgradeid]['photo'];
    }
    $_SESSION['jieqiUserVipgradePhoto'] = $jieqiVipgrade[$vipgradeid]['photo'];
    $_SESSION['jieqiUserGrade'] = isset($jieqiHonors[$honorid]['grade'][intval($user->getVar('workid', 'n'))]) ? $jieqiHonors[$honorid]['grade'][intval($user->getVar('workid', 'n'))] : $jieqiHonors[$honorid]['dgrade'];
    if (!empty($jieqiModules['badge']['publish'])) {
        $_SESSION['jieqiUserBadges'] = $user->getVar('badges', 'n');
    }
    $_SESSION['jieqiUserSet'] = unserialize($user->getVar('setting', 'n'));
}

function jieqi_addurlvars($varary, $addget = true, $addpost = false, $filter = '')
{
    if (!empty($_SERVER['PHP_SELF'])) $ret = $_SERVER['PHP_SELF'];
    elseif (!empty($_SERVER['SCRIPT_NAME']) && substr($_SERVER['SCRIPT_NAME'], -4) == '.php') $ret = $_SERVER['SCRIPT_NAME'];
    else $ret = '';
    $start = 0;
    if (!is_array($filter)) $filter = array();
    if ($addget) {
        foreach ($_GET as $k => $v) {
            if (!array_key_exists($k, $varary) && !in_array($k, $filter) && is_string($v)) {
                $ret .= ($start++ > 0) ? '&' . $k . '=' . urlencode($v) : '?' . $k . '=' . urlencode($v);
            }
        }
    }
    if ($addpost) {
        foreach ($_POST as $k => $v) {
            if (!array_key_exists($k, $varary) && !in_array($k, $filter) && is_string($v)) {
                $ret .= ($start++ > 0) ? '&' . $k . '=' . urlencode($v) : '?' . $k . '=' . urlencode($v);
            }
        }
    }
    if (is_array($varary)) {
        foreach ($varary as $k => $v) {
            $ret .= ($start++ > 0) ? '&' . $k . '=' . $v : '?' . $k . '=' . $v;
        }
    }
    return $ret;
}

function jieqi_includedb()
{
    if (!defined('JIEQI_DBCLASS_INCLUDE')) {
        include_once(JIEQI_ROOT_PATH . '/lib/database/database.php');
        define('JIEQI_DBCLASS_INCLUDE', true);
    }
}

function jieqi_closedb($db = NULL)
{
    if (defined('JIEQI_DB_CONNECTED') && !defined('JIEQI_DB_NOTCLOSE') && JIEQI_DB_PCONNECT == false) JieqiDatabase::close($db);
}

function jieqi_closeftp($ftp = NULL)
{
    if (defined('JIEQI_FTP_CONNECTED') && !defined('JIEQI_FTP_NOTCLOSE')) JieqiFTP::close($ftp);
}

function &jieqi_initcache()
{
    if (strtolower(substr(trim(JIEQI_CACHE_DIR), 0, 12)) != 'memcached://') {
        $jieqiCache =& JieqiCache::getInstance('file');
    } else {
        $params = @parse_url(trim(JIEQI_CACHE_DIR));
        $jieqiCache =& JieqiCache::getInstance('memcached', array('host' => strval($params['host']), 'port' => intval($params['port'])));
    }
    return $jieqiCache;
}

function jieqi_closecache($cache = NULL)
{
    if (defined('JIEQI_CACHE_CONNECTED') && !defined('JIEQI_CACHE_NOTCLOSE')) JieqiCache::close($cache);
}

function jieqi_freeresource()
{
    jieqi_closedb();
    jieqi_closeftp();
    jieqi_closecache();
}

function jieqi_loadlang($fname, $module = 'system')
{
    global $jieqiLang;
    global $jieqiModules;
    if (empty($jieqiLang[$module][$fname])) {
        if ($module == 'system' || $module == '') $file = JIEQI_ROOT_PATH . '/lang/lang_' . $fname . '.php';
        else $file = $jieqiModules[$module]['path'] . '/lang/lang_' . $fname . '.php';
        $file = @realpath($file);
        if (is_file($file) && preg_match('/\.php$/i', $file)) {
            include_once($file);
            return true;
        } else return false;
    }
}

function jieqi_gethonorid($userscore = 0, $jieqiHonors = array())
{
    if (is_array($jieqiHonors)) {
        foreach ($jieqiHonors as $k => $v) {
            if ($userscore >= $v['minscore'] && $userscore < $v['maxscore']) return $k;
        }
    }
    return false;
}

function jieqi_gethonorarray($userscore = 0, $jieqiHonors = array())
{
    if (!$vipgradeid = jieqi_gethonorid($userscore, $jieqiHonors)) return false;
    $vipgrade = $jieqiHonors[intval($vipgradeid)];
    $vipgrade['setting'] = unserialize(htmlspecialchars_decode($vipgrade['setting']));
    return $vipgrade;
}

function jieqi_htmlstr($str, $quote_style = ENT_QUOTES)
{
    $str = htmlspecialchars($str, $quote_style);
    $str = nl2br($str);
    $str = str_replace("  ", "&nbsp;&nbsp;", $str);
    $str = preg_replace("/&amp;#(\d+);/isU", "&#\\1;", $str);
    return $str;
}

function jieqi_substr($str, $start, $length, $trimmarker = '...')
{
    $strlen = $start + $length - strlen($trimmarker);
    $len = strlen($str);
    if ($strlen > $len) $strlen = $len;
    $tmpstr = "";
    for ($i = 0; $i < $strlen; $i++) {
        if (ord($str[$i]) > 0x80) {
            if ($i >= $start) $tmpstr .= $str[$i] . $str[$i + 1];
            $i++;
        } else if ($i >= $start) $tmpstr .= $str[$i];
    }
    if ($strlen < $len) $tmpstr .= $trimmarker;
    return $tmpstr;
}

function jieqi_funtoarray($funname, $ary)
{
    if (is_array($ary)) {
        foreach ($ary as $k => $v) {
            if (is_string($v)) {
                $ary[$k] = $funname($v);
            } elseif (is_array($v)) {
                $ary[$k] = jieqi_funtoarray($funname, $v);
            }
        }
    } else {
        $ary = $funname($ary);
    }
    return $ary;
}

function jieqi_dbprefix($tbname, $fullname = false)
{
    if (JIEQI_DB_PREFIX == '' || $fullname) return $tbname;
    else return JIEQI_DB_PREFIX . '_' . $tbname;
}

function jieqi_setslashes($str, $filter = '')
{
    if ($filter == '"') return str_replace(array('\\', '\''), array('\\\\', '\\\''), $str);
    elseif ($filter == '\'') return str_replace(array('\\', '"'), array('\\\\', '\\"'), $str);
    else return addslashes($str);
}

function jieqi_dbslashes($str, $use_slashes = false)
{
    if ($use_slashes) return $str;
    else {
        if (JIEQI_SYSTEM_CHARSET == 'big5' && JIEQI_DB_CHARSET != 'big5') {
            $str = strval($str);
            $l = strlen($str);
            $ret = '';
            for ($i = 0; $i < $l; $i++) {
                $o = ord($str[$i]);
                if ($o > 0x80) {
                    $ret .= $str[$i] . $str[$i + 1];
                    $i++;
                } elseif ($o == 0 || $o == 34 || $o == 39 || $o == 92) {
                    $ret .= chr(92) . $str[$i];
                } else {
                    $ret .= $str[$i];
                }
            }
            return $ret;
        } else {
            return addslashes($str);
        }
    }
}

function jieqi_sarytostr($ary, $equal = '=', $split = ',')
{
    $ret = '';
    foreach ($ary as $k => $v) {
        if (!empty($ret)) $ret .= $split;
        $ret .= $k . $equal . $v;
    }
    return $ret;
}

function jieqi_strtosary($str, $equal = '=', $split = ',')
{
    $ret = array();
    $tmpary = explode($split, $str);
    foreach ($tmpary as $v) {
        $idx = strpos($v, $equal);
        if ($idx > 0) $ret[substr($v, 0, $idx)] = substr($v, $idx + 1);
    }
    return $ret;
}

function jieqi_strlen($str, $needle = false)
{
    global $article;
    if (!$str) return 0;
    $str = preg_replace("@&#(\d+);@is", "", $str);
    if ($needle) $str = str_replace(array(".", "．", "，", ",", "。", "!", "?", "！", "？", "：", ":", "、", "）", "（", "-"), "", $str);
    $str = preg_replace("@&nbsp;|\r\n|=|—|| |♂|♀|【|】|\n|-|&#|<script(.*?)</script>@is", "", $str);
    $str = preg_replace("@<iframe(.*?)</iframe>@is", "", $str);
    $str = preg_replace("@<style(.*?)</style>@is", "", $str);
    $str = preg_replace("@<(.*?)>@is", "", $str);
    include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
    $pattern = "/[" . chr(0xa1) . "-" . chr(0xff) . "|\w]+/";
    preg_match_all($pattern, $str, $matches);
    $tempstr = '';
    foreach ($matches[0] as $v) {
        $tempstr .= trim($v);
    }
    return strlen($tempstr);
}

function htmlspecialchars_array($string)
{
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = htmlspecialchars_array($val);
        }
    } else {
        $string = htmlspecialchars($string);
    }
    return $string;
}

function addslashes_array($string)
{
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = addslashes_array($val);
        }
    } else {
        $string = addslashes($string);
    }
    return $string;
}

if (!function_exists('cal_days_in_month')) {
    function cal_days_in_month($calendar, $month, $year)
    {
        return date('t', mktime(0, 0, 0, $month, 1, $year));
    }
}
function form_hash($uid = 0)
{
    if ($uid == -1) $uid = '';
    else $uid = $uid ? $uid : $_SESSION['jieqiUserId'];
    $hashadd = defined('IN_ADMINCP') ? 'Only For HLMCMS AdminCP' : '';
    $formhash = substr(md5(substr(JIEQI_NOW_TIME, 0, -7) . '|' . $uid . '|' . md5(JIEQI_SITE_KEY) . '|' . $hashadd), 8, 8);
    return $formhash;
}

function get_host($host = '-1')
{
    if ($host == '-1') $host = $_SERVER['HTTP_HOST'];
    else $host = preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $host);
    $fcount = substr_count($host, '.');
    switch ($fcount) {
        case '0':
            return false;
            break;
        case '1':
            return $host;
            break;
        default:
            $arr = explode('.', $host);
            return $arr[$fcount - 1] . '.' . $arr[$fcount];
            break;
    }
}

function submit_check($var = 'formhash')
{
    if (!empty($_REQUEST[$var])) {
        $REFERER = preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']);
        $HOST = preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']);
        if (get_host($REFERER) == get_host($HOST) && ($_REQUEST[$var] == form_hash() || $_REQUEST[$var] == form_hash(-1))) {
            return true;
        } else {
            jieqi_printfail(LANG_ERROR_PARAMETER);
        }
    } else {
        return false;
    }
}

function jieqi_readfile($file_name)
{
    if (function_exists("file_get_contents")) {
        return file_get_contents($file_name);
    } else {
        $filenum = @fopen($file_name, "rb");
        @flock($filenum, LOCK_SH);
        $file_data = @fread($filenum, @filesize($file_name));
        @flock($filenum, LOCK_UN);
        @fclose($filenum);
        return $file_data;
    }
}

function jieqi_writefile($file_name, &$data, $method = "wb")
{
    $filenum = @fopen($file_name, $method);
    if (!$filenum) return false;
    @flock($filenum, LOCK_EX);
    $ret = @fwrite($filenum, $data);
    @flock($filenum, LOCK_UN);
    @fclose($filenum);
    @chmod($file_name, 0777);
    return $ret;
}

function jieqi_delfile($file_name)
{
    $file_name = trim($file_name);
    $matches = array();
    if (!preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $file_name, $matches)) {
        return @unlink($file_name);
    } else {
        include_once(JIEQI_ROOT_PATH . '/lib/ftp/ftp.php');
        $ftpssl = (strtolower($matches[1]) == 'ftps') ? 1 : 0;
        $matches[6] = intval(trim($matches[6]));
        $ftpport = ($matches[6] > 0) ? $matches[6] : 21;
        $ftp =& JieqiFTP::getInstance($matches[4], $matches[2], $matches[3], '.', $ftpport, 0, $ftpssl);
        if (!$ftp) return false;
        $matches[7] = trim($matches[7]);
        return $ftp->ftp_delete($matches[7]);
    }
}

function jieqi_delfolder($dirname, $flag = true)
{
    $dirname = trim($dirname);
    $matches = array();
    if (!preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $dirname, $matches)) {
        $handle = @opendir($dirname);
        while (($file = @readdir($handle)) !== false) {
            if ($file != '.' && $file != '..') {
                if (is_dir($dirname . DIRECTORY_SEPARATOR . $file)) {
                    jieqi_delfolder($dirname . DIRECTORY_SEPARATOR . $file, true);
                } else {
                    @unlink($dirname . DIRECTORY_SEPARATOR . $file);
                }
            }
        }
        @closedir($handle);
        if ($flag) @rmdir($dirname);
        return true;
    } else {
        include_once(JIEQI_ROOT_PATH . '/lib/ftp/ftp.php');
        $ftpssl = (strtolower($matches[1]) == 'ftps') ? 1 : 0;
        $matches[6] = intval(trim($matches[6]));
        $ftpport = ($matches[6] > 0) ? $matches[6] : 21;
        $ftp =& JieqiFTP::getInstance($matches[4], $matches[2], $matches[3], '.', $ftpport, 0, $ftpssl);
        if (!$ftp) return false;
        $matches[7] = trim($matches[7]);
        return $ftp->ftp_delfolder($matches[7], $flag);
    }
}

function jieqi_createdir($dirname, $mode = 0777, $recursive = false)
{
    if (!$recursive) {
        $ret = @mkdir($dirname, $mode);
        if ($ret) @chmod($dirname, $mode);
        return $ret;
    }
    if (is_dir($dirname)) {
        return true;
    } elseif (jieqi_createdir(dirname($dirname), $mode, true)) {
        $ret = @mkdir($dirname, $mode);
        if ($ret) @chmod($dirname, $mode);
        return $ret;
    } else {
        return false;
    }
}

function jieqi_checkdir($dirname, $autocreate = false)
{
    if (is_dir($dirname)) {
        return true;
    } else {
        if (empty($autocreate)) return false;
        else return jieqi_createdir($dirname, 0777, true);
    }
}

function jieqi_downfile($filename, $contenttype = 'application/octet-stream')
{
    if (file_exists($filename)) {
        header("Content-type: " . $contenttype);
        header("Accept-Ranges: bytes");
        header("Content-Disposition: attachment; filename=" . basename($filename));
        echo jieqi_readfile($filename);
        return true;
    } else {
        return false;
    }
}

function jieqi_copyfile($from_file, $to_file, $mode = 0777, $move = false)
{
    $from_file = trim($from_file);
    $to_file = trim($to_file);
    $matches = array();
    if (preg_match('/^(http:\/\/|https:\/\/)/', $from_file, $matches)) {
        include_once(JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
        if ($source = jieqi_urlcontents($from_file, array('repeat' => 2))) {
            jieqi_checkdir(dirname($to_file), true);
            return jieqi_writefile($to_file, $source);
        }
        return false;
    } elseif (!preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $to_file, $matches)) {
        if (!is_file($from_file)) return false;
        jieqi_checkdir(dirname($to_file), true);
        if (is_file($to_file)) @unlink($to_file);
        if ($move) $ret = rename($from_file, $to_file);
        else $ret = copy($from_file, $to_file);
        if ($ret && $mode) @chmod($to_file, $mode);
        return $ret;
    } else {
        include_once(JIEQI_ROOT_PATH . '/lib/ftp/ftp.php');
        $ftpssl = (strtolower($matches[1]) == 'ftps') ? 1 : 0;
        $matches[6] = intval(trim($matches[6]));
        $ftpport = ($matches[6] > 0) ? $matches[6] : 21;
        $ftp =& JieqiFTP::getInstance($matches[4], $matches[2], $matches[3], '.', $ftpport, 0, $ftpssl);
        if (!$ftp) return false;
        $matches[7] = trim($matches[7]);
        if (!$ftp->ftp_chdir(dirname($matches[7]))) {
            if (substr($matches[7], 0, 1) == '/') $matches[7] = substr($matches[7], 1);
            $pathary = explode('/', dirname($matches[7]));
            foreach ($pathary as $v) {
                $v = trim($v);
                if (strlen($v) > 0) {
                    if ($ftp->ftp_mkdir($v) !== false && $mode) $ftp->ftp_chmod($mode, $v);
                    $ftp->ftp_chdir($v);
                }
            }
        }
        $ret = $ftp->ftp_put(basename($matches[7]), $from_file);
        if ($ret && $mode) $ftp->ftp_chmod($mode, basename($matches[7]));
        if ($move) @unlink($from_file);
        return $ret;
    }
}

function jieqi_extractvars($varname, &$vars)
{
    $extract_array_str = '';
    if (is_array($vars)) {
        foreach ($vars as $key => $val) {
            if (is_array($val)) {
                $extract_array_str .= jieqi_extractvars($varname . "['" . jieqi_setslashes($key, '"') . "']", $vars[$key]);
            } else {
                $extract_array_str .= '$' . $varname . "['" . jieqi_setslashes($key, '"') . "'] = '" . jieqi_setslashes($val, '"') . "';\n";
            }
        }
    } else {
        $extract_array_str .= '$' . $varname . " = '" . jieqi_setslashes($vars, '"') . "';\n";
    }
    return $extract_array_str;
}

function gb2utf8XHSafeStr($str, $safe = true, $type = 'html')
{
    include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
    $str = jieqi_gb2utf8($str);
    if ($safe) {
        if ($type == 'html') {
            return jieqi_htmlstr($str);
        } elseif ($type == 'xml') {
            $str = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", "", $str);
            $arr_search = array('<', '>', '&', '\'', '"', '');
            $arr_replace = array('&lt;', '&gt;', '&amp;', '&apos;', '&quot;', '');
            return str_ireplace($arr_search, $arr_replace, $str);
        }
    }
    return $str;
}

function jieqi_setconfigs($fname = '', $varname, &$vars, $module = 'system')
{
    global $jieqiModules;
    if (strlen($fname) > 0 && strlen($varname) > 0) {
        $dir = JIEQI_ROOT_PATH . '/configs';
        if (!file_exists($dir)) @mkdir($dir, 0777);
        @chmod($dir, 0777);
        if ($module != 'system' && isset($jieqiModules[$module])) {
            $dir .= '/' . $module;
            if (!file_exists($dir)) @mkdir($dir, 0777);
            @chmod($dir, 0777);
        }
        $dir .= '/' . $fname . '.php';
        if (file_exists($dir)) @chmod($dir, 0777);
        $varstring = "<?php\n" . jieqi_extractvars($varname, $vars) . "\n?>";
        return jieqi_writefile($dir, $varstring);
    }
    return false;
}

function jieqi_setcachevars($fname = '', $varname, &$vars, $module = 'system', $cacheid = 0)
{
    global $jieqiModules;
    global $jieqiCache;
    if (empty($fname) || empty($varname)) return false;
    $cachefile = JIEQI_CACHE_PATH . '/cachevars';
    if (isset($jieqiModules[$module])) $cachefile .= '/' . $module;
    if (empty($cacheid)) {
        $cachefile .= '/' . $fname . '.php';
    } else {
        $cacheid = intval($cacheid);
        $cachefile .= '/' . $fname . jieqi_getsubdir($cacheid) . '/' . $cacheid . '.php';
    }
    if (is_a($jieqiCache, 'JieqiCacheMemcached')) {
        return $jieqiCache->set($cachefile, $vars);
    } else {
        $varstring = "<?php\n" . jieqi_extractvars($varname, $vars) . "\n?>";
        return $jieqiCache->set($cachefile, $varstring);
    }
}

function jieqi_getConfigs($module, $fname, $vname = '')
{
    if ($vname !== false) {
        if ($vname == '') $vname = 'jieqi' . ucfirst($fname);
        global ${$vname};
    }
    if ($vname == 'jieqiBlocks' && isset($jieqiBlocks)) {
        return true;
    } else {
        if ($module == 'system' || $module == '') $file = JIEQI_ROOT_PATH . '/configs/' . $fname . '.php';
        else $file = JIEQI_ROOT_PATH . '/configs/' . $module . '/' . $fname . '.php';
        $file = @realpath($file);

        if (preg_match('/\.php$/i', $file)) {
            include_once($file);
            return true;
        } else return false;
    }
}

function jieqi_getcachevars($module, $fname, $vname = '', $cacheid = 0)
{
    global $jieqiModules;
    global $jieqiCache;
    if (empty($module) || empty($fname)) return false;
    if ($vname !== false) {
        if ($vname == '') $vname = 'jieqi' . ucfirst($fname);
        global ${$vname};
    }
    $cachefile = JIEQI_CACHE_PATH . '/cachevars';
    if (isset($jieqiModules[$module])) $cachefile .= '/' . $module;
    if (empty($cacheid)) {
        $cachefile .= '/' . $fname . '.php';
    } else {
        $cacheid = intval($cacheid);
        $cachefile .= '/' . $fname . jieqi_getsubdir($cacheid) . '/' . $cacheid . '.php';
    }
    if (is_a($jieqiCache, 'JieqiCacheMemcached')) {
        ${$vname} = $jieqiCache->get($cachefile);
    } else {
        $cachefile = @realpath($cachefile);
        if (is_file($cachefile) && preg_match('/\.php$/i', $cachefile)) include_once($cachefile);
    }
}

function jieqi_strexists($haystack, $needle)
{
    return !(strpos($haystack, $needle) === FALSE);
}

function jieqi_random($length, $numeric = 0)
{
    PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    if ($numeric) {
        $hash = sprintf('%0' . $length . 'd', mt_rand(0, pow(10, $length) - 1));
    } else {
        $hash = '';
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
    }
    return $hash;
}

function arraytoJson($array)
{
    arrayRecursive($array, 'urlencode', true);
    $json = json_encode($array);
    $json = urldecode($json);
    $json = str_replace("\"false\"", "false", $json);
    $json = str_replace("\"true\"", "true", $json);
    return $json;
}

function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
    static $recursive_counter = 0;
    if (++$recursive_counter > 1000) {
        die('possible deep recursion attack');
    }
    include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            arrayRecursive($array[$key], $function, $apply_to_keys_also);
        } else {
            $array[$key] = (jieqi_gb2utf8($value));
        }
        if ($apply_to_keys_also && is_string($key)) {
            $new_key = $function($key);
            if ($new_key != $key) {
                $array[$new_key] = $array[$key];
                unset($array[$key]);
            }
        }
    }
    $recursive_counter--;
}

function jieqi_exechars($preg, $str = '', $getall = false)
{
    include_once(JIEQI_ROOT_PATH . '/lib/text/textfunction.php');
    $preg = collectstoe(collectptos($preg));
    if ($getall) return cmatchall($preg, $str);
    else return cmatchone($preg, $str);
}

function replace_specialChar($strParam){
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\=|\\\|\|/";
    return preg_replace($regex,"",$strParam);
}

if (JIEQI_MODULE_NAME != 'news') {
    include_once(JIEQI_ROOT_PATH . '/core/app.php');
    Application::init();
    Application::autoload();
}

class JieqiObject
{
    var $vars = array();
    var $errors = array();
    var $_PAGE = array();
    public static $_config = null;
    public static $_lang = null;

    function JieqiObject()
    {
    }

    final protected function load($lib, $auto = TRUE, $dir = '')
    {
        if (empty($lib)) {
            trigger_error('加载类库名不能为空');
        } elseif ($auto === TRUE) {
            return Application::$_lib[$lib];
        } else {
            return Application::newLib($lib, $auto, $dir);
        }
    }

    function getFormat($string, $format = 's')
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = $this->getFormat($val, $format);
            }
        } else {
            if (is_string($string)) {
                switch (strtolower($format)) {
                    case 's':
                        $string = jieqi_htmlstr($string);
                    case 'e':
                        $string = preg_replace("/&amp;#(\d+);/isU", "&#\\1;", htmlspecialchars($string, ENT_QUOTES));
                    case 'q':
                        $string = jieqi_dbslashes($string);
                    case 'n':
                    default:
                }
            }
        }
        return $string;
    }

    function getTime($format = 'day')
    {
        if ($format == 'day') {
            $tmpvar = explode('-', date('Y-m-d', JIEQI_NOW_TIME));
            return mktime(0, 0, 0, (int)$tmpvar[1], (int)$tmpvar[2], (int)$tmpvar[0]);
        } elseif ($format == 'week') {
            $tmpvar = date('w', JIEQI_NOW_TIME);
            if ($tmpvar == 0) $tmpvar = 7;
            $weekstart = $this->getTime('day');
            if ($tmpvar > 1) $weekstart -= ($tmpvar - 1) * 86400;
            return $weekstart;
        } elseif ($format == 'month') {
            $tmpvar = explode('-', date('Y-m-d', JIEQI_NOW_TIME));
            return mktime(0, 0, 0, (int)$tmpvar[1], 1, (int)$tmpvar[0]);
        } elseif ($format == 'premonth') {
            if ((int)date('m') == 1) {
                $premonthday = cal_days_in_month(CAL_GREGORIAN, 12, date('Y') - 1);
            } else {
                $premonthday = cal_days_in_month(CAL_GREGORIAN, date('n') - 1, date('Y'));
            }
            return ($this->getTime('month') - $premonthday * 86400);
        } else return JIEQI_NOW_TIME;
    }

    function getRequest($name = '', $method = '')
    {
        if ($name) {
            if ($method == 'get') $this->_PAGE[$name] = $_GET[$name];
            elseif ($method == 'post') $this->_PAGE[$name] = $_POST[$name];
            else $this->_PAGE[$name] = isset($_GET[$name]) ? $_GET[$name] : $_POST[$name];
            return $this->_PAGE[$name];
        } else {
            if ($method == 'get') $this->_PAGE = $_GET;
            elseif ($method == 'post') $this->_PAGE = $_POST;
            else $this->_PAGE = $_REQUEST;
            return $this->_PAGE;
        }
    }

    function submitcheck($var = 'formhash')
    {
        $ip = $this->getIp();
        if (!$this->checkisadmin() && !empty($_REQUEST[$var]) && $ip != '113.140.9.50') {
            $ip = $ip ? $ip : 'allip';
            $cookiename = 'closeip_' . str_replace('.', '_', $ip);
            $deniedip = JIEQI_ROOT_PATH . '/deniedip.txt';
            if ($ip_arrays = @file($deniedip)) {
                $ip_arrays = array_unique($ip_arrays);
                $a_tmp = array();
                foreach ($ip_arrays as $v) {
                    $a_tmp[] = trim($v);
                }
                if (in_array($ip, $a_tmp)) $this->printfail('你已被系统限制操作！');
            }
            $submitmins = date('YmdHi');
            if (!isset($_SESSION['submitcount'])) $_SESSION['submitcount'] = 0;
            if (!isset($_SESSION['submitmins'])) $_SESSION['submitmins'] = $submitmins;
            if ($_SESSION['submitmins'] !== $submitmins) {
                $_SESSION['submitcount'] = 0;
                $_SESSION['submitmins'] = $submitmins;
            }
            if ((int)date('H') >= 8 && (int)date('H') <= 22) $count = 15;
            else $count = 10;
            if ($_SESSION['submitcount'] <= $count) {
                $_SESSION['submitcount'] = $_SESSION['submitcount'] + 1;
            } else {
                if (!is_object($db) && class_exists('Application')) {
                    $db = $this->load('database');
                    $db->init('userlog', 'logid', 'system');
                    $data = array();
                    $data['siteid'] = JIEQI_SITE_ID;
                    $data['logtime'] = JIEQI_NOW_TIME;
                    $data['fromid'] = $_SESSION['jieqiUserId'];
                    $data['fromname'] = $_SESSION['jieqiUserName'];
                    $data['toid'] = 0;
                    $data['toname'] = '';
                    $data['reason'] = '恶意数据活动检测';
                    $data['chginfo'] = $ip . "在一分钟内向服务器（控制器：" . $_REQUEST['controller'] . " 方法：" . $_REQUEST['method'] . "）请求了至少 " . ($_SESSION['submitcount']) . " 次表单数据!判定为恶意用户，该用户已经被" . ($_SESSION['jieqiUserId'] ? '降为游客' : '禁止提交表单') . "。";
                    $data['chglog'] = '';
                    $data['isdel'] = '0';
                    $data['userlog'] = '';
                    $db->add($data);
                    $auth = $this->getAuth();
                    if ($auth['uid']) {
                        $users_handler = $this->getUserObject();
                        $criteria = new CriteriaCompo(new Criteria('uid', $auth['uid']));
                        $users_handler->updatefields(array('groupid' => 1), $criteria);
                        $tmpuser = $users_handler->get($auth['uid']);
                        $tmpuser->saveToSession();
                        include_once(JIEQI_ROOT_PATH . '/include/dologout.php');
                        jieqi_dologout();
                    }
                }
                @setcookie($cookiename, date("Y-m-d H:i:s", JIEQI_NOW_TIME), JIEQI_NOW_TIME + 86400, '/', JIEQI_COOKIE_DOMAIN, 0);
                $this->swritefile($deniedip, $ip . "\r\n", "a+");
                $this->printfail('系统检测到非法数据来源！你的IP:' . $ip . '我们已经记录，请立即离开！');
            }
        }
        return submit_check($var);
    }

    function getAdminurl($controller = '', $p = '', $module = JIEQI_MODULE_NAME)
    {
        if ($module == 'system' || $controller == 'login') {
            if ($controller) return JIEQI_URL . '/web_admin/?controller=' . $controller . ($p ? '&' . $p : '');
            else return JIEQI_URL . '/web_admin/';
        } else {
            global $jieqiModules;
            $controller = $controller ? $controller : $_REQUEST['controller'];
            if ($controller) return $jieqiModules[$module]['url'] . '/web_admin/?controller=' . $controller . ($p ? '&' . $p : '');
            else return $jieqiModules[$module]['url'] . '/web_admin/';
        }
    }

    function getAuth()
    {
        global $jieqiGroups;
        $_USER = array();
        if ($_SESSION['jieqiUserId']) {
            $_USER['uid'] = intval($_SESSION['jieqiUserId']);
            $_USER['useruname'] = addslashes($_SESSION['jieqiUserUname']);
            $_USER['username'] = addslashes($_SESSION['jieqiUserName']);
            $_USER['realname'] = addslashes($_SESSION['jieqiRealName']);
            $_USER['avatar'] = intval($_SESSION['jieqiUserAvatar']);
            $_USER['vip'] = $_SESSION['jieqiUserVip'];
            $_USER['groupid'] = intval($_SESSION['jieqiUserGroup']);
            $_USER['usergroup'] = $jieqiGroups[$_USER['groupid']];
            $_USER['honorid'] = addslashes($_SESSION['jieqiUserHonorid']);
            $_USER['honor'] = addslashes($_SESSION['jieqiUserHonor']);
            $_USER['vipgrade'] = addslashes($_SESSION['jieqiUserVipgrade']['caption']);

            $_USER['vipphoto'] = $_SESSION['jieqiUserVipgrade']['photo'];

            $_USER['grade'] = addslashes($_SESSION['jieqiUserGrade']);
            $_USER['egolds'] = $_SESSION['jieqiUserEgolds'];
            $_USER['esilvers'] = $_SESSION['jieqiUserEsilvers'];
            $_USER['score'] = ($_SESSION['jieqiUserScore']);
            $_USER['source'] = replace_specialChar($_SESSION['jieqiUserSource']);
            $_USER['setting'] = $_SESSION['jieqiUserSet'];
            $_USER['overtime'] = $_SESSION['jieqiUserOvertime'];
        } else {
            $_USER['groupid'] = JIEQI_GROUP_GUEST;
            $_USER['usergroup'] = $jieqiGroups[JIEQI_GROUP_GUEST];
            $_USER['honorid'] = JIEQI_GROUP_GUEST;
            $_USER['honor'] = $jieqiGroups[JIEQI_GROUP_GUEST];
            $_USER['vipgradeid'] = JIEQI_GROUP_GUEST;
            $_USER['vipgrade'] = $jieqiGroups[JIEQI_GROUP_GUEST];
            $_USER['score'] = 0;
            $_USER['vip'] = 0;
            $_USER['vipphoto'] = 'v0';
        }
        return $_USER;
    }

    function checkisadmin()
    {
        if ($this->getUsersStatus() != JIEQI_GROUP_ADMIN) return false;
        else return true;
    }

    function exechars($preg, $str = '', $getall = false)
    {
        return jieqi_exechars($preg, $str, $getall);
    }

    function geturl($module, $controller, $p1 = '', $p2 = '', $p3 = '', $p4 = '', $p5 = '', $p6 = '', $p7 = '', $p8 = '', $p9 = '', $p10 = '')
    {
        $ret = geturl($module, $controller, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10);
        return $ret;
    }

    function checklogin($isreturn = false, $isadmin = false, $module = 'system')
    {
        global $jieqiUsersGroup;
        if ($jieqiUsersGroup == JIEQI_GROUP_GUEST) $ret = false;
        else $ret = true;
        if ($isreturn) return $ret;
        elseif (!$ret) {
            if (empty($_REQUEST['ajax_request'])) {
                if (empty($_POST)) {
                    $jumpurl = JIEQI_CURRENT_URL;
                } elseif (!empty($_SERVER['HTTP_REFERER'])) {
                    $jumpurl = $_SERVER['HTTP_REFERER'];
                } else {
                    $jumpurl = JIEQI_MAIN_URL;
                }
                if ($isadmin) header('Location: ' . $this->getAdminurl('login', 'jumpurl=' . urlencode($jumpurl)));
                else header('Location: ' . $this->geturl($module, 'login', 'SYS=jumpurl=' . urlencode($jumpurl)));
            } else {
                header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
                header("Cache-Control:no-cache");
                if ($_REQUEST['CALLBACK']) {
                    include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
                    echo $_REQUEST['CALLBACK'] . '(' . json_encode(jieqi_gb2utf8(LANG_NEED_LOGIN)) . ');';
                } else echo LANG_NEED_LOGIN;
            }
            exit;
        }
    }

    function checkpower($powerset = array(), $ustatus = '0', $ugroup = '0', $isreturn = false, $isadmin = false)
    {
        if (empty($_POST)) {
            $local_domain_url = (empty($_SERVER['HTTP_HOST'])) ? '' : 'http://' . $_SERVER['HTTP_HOST'];
            $jumpurl = $local_domain_url . jieqi_addurlvars(array());
        } elseif (!empty($_SERVER['HTTP_REFERER'])) {
            $jumpurl = $_SERVER['HTTP_REFERER'];
        } else {
            $jumpurl = JIEQI_MAIN_URL;
        }
        if ((!isset($_SESSION['jieqiAdminLogin']) || $_SESSION['jieqiAdminLogin'] != 1) && !empty($_COOKIE['jieqiOnlineInfo'])) {
            $jieqi_online_info = jieqi_strtosary($_COOKIE['jieqiOnlineInfo']);
            if ($jieqi_online_info['jieqiAdminLogin'] == 1) $_SESSION['jieqiAdminLogin'] = 1;
        }
        if ($ustatus == JIEQI_GROUP_ADMIN) {
            if ($isadmin && empty($_SESSION['jieqiAdminLogin'])) {
                if ($isreturn) {
                    return false;
                } else {
                    header('Location: ' . $this->getAdminurl('login', 'jumpurl=' . urlencode($jumpurl)));
                    exit;
                }
            } else {
                return true;
            }
        } else {
            if (is_array($powerset['groups']) && (in_array($ugroup, $powerset['groups'], false) || in_array('0', $powerset['groups'], false))) {
                if ($isadmin && empty($_SESSION['jieqiAdminLogin'])) {
                    if ($isreturn) {
                        return false;
                    } else {
                        header('Location: ' . $this->getAdminurl('login', 'jumpurl=' . urlencode($jumpurl)));
                        exit;
                    }
                } else {
                    return true;
                }
            } else {
                if ($isreturn) {
                    return false;
                } else {
                    if ($ugroup == JIEQI_GROUP_GUEST) {
                        if ($isadmin) {
                            header('Location: ' . $this->getAdminurl('login', 'jumpurl=' . urlencode($jumpurl)));
                        } else {
                            header('Location: ' . $this->geturl('system', 'login', 'SYS=jumpurl=' . urlencode($jumpurl)));
                        }
                        exit;
                    } else {
                        $this->printfail(LANG_NO_PERMISSION);
                    }
                }
            }
        }
    }

    function dbprefix($tbname, $fullname = false)
    {
        return jieqi_dbprefix($tbname, $fullname);
    }

    function json_encode($data)
    {
        return arraytoJson($data);
    }

    function cache_write($module = 'system', $name, $values, $field = 0, $cachefile = '')
    {
        if (!$cachefile) {
            if ($module == 'system') $cachefile = JIEQI_ROOT_PATH . '/configs/' . $name . '.php';
            else $cachefile = JIEQI_ROOT_PATH . '/configs/' . $module . '/' . $name . '.php';
            $var = 'jieqi' . ucfirst($name) . '[\'' . $module . '\']';
        } else {
            $var = $name;
        }
        $cachetext = "<?php\r\n" .
            '$' . $var . '=' . $this->arrayeval($values, 0, $field) .
            "\r\n?>";
        if (!$this->swritefile($cachefile, $cachetext)) {
            exit("File: $cachefile write error.");
        }
    }

    function arrayeval($array, $level = 0, $field = 0)
    {
        $space = '';
        for ($i = 0; $i <= $level; $i++) {
            $space .= "\t";
        }
        $evaluate = "Array\n$space(\n";
        $comma = $space;
        if ($array) {
            foreach ($array as $key => $val) {
                if (empty($field)) {
                    $key = is_string($key) ? '\'' . addcslashes($key, '\'\\') . '\'' : $key;
                } else {
                    if (substr_count($field, ',') > 0) {
                        $tmp = explode(',', $field);
                        foreach ($tmp as $k => $v) {
                            if ($val[$v]) $tmp[$k] = $val[$v];
                            else $tmp[$k] = 'main';
                        }
                        $key = '\'' . addcslashes(implode('_', $tmp), '\'\\') . '\'';
                    } else $key = '\'' . addcslashes($val[$field], '\'\\') . '\'';
                }
                $val = !is_array($val) && (!preg_match("/^\-?\d+$/", $val) || strlen($val) > 12 || substr($val, 0, 1) == '0') ? '\'' . addcslashes($val, '\'\\') . '\'' : $val;
                if (is_array($val)) {
                    $evaluate .= "$comma$key => " . $this->arrayeval($val, $level + 1);
                } else {
                    $evaluate .= "$comma$key => $val";
                }
                $comma = ",\n$space";
            }
        }
        $evaluate .= "\n$space)";
        return $evaluate;
    }

    function swritefile($filename, $writetext, $openmod = 'w')
    {
        $dir = dirname($filename) . '/';
        if (!is_dir($dir)) if (!jieqi_createdir($dir, 0777, true)) return false;
        if (@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            @chmod($filename, 0777);
            return true;
        } else {
            return false;
        }
    }

    function getUsersStatus()
    {
        global $jieqiUsersStatus;
        return $jieqiUsersStatus;
    }

    function getUsersGroup()
    {
        global $jieqiUsersGroup;
        return $jieqiUsersGroup;
    }

    function getIp()
    {
        return jieqi_userip();
    }

    function subStr($str, $start, $length, $trimmarker = '...')
    {
        return jieqi_substr($str, $start, $length, $trimmarker);
    }

    public static function arrayToStr($arr = array(), $separator = ',')
    {
        $ids = '';
        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (!empty($ids)) $ids .= $separator;
                $ids .= $v;
            }
        } elseif (is_string($arr)) {
            $ids = $arr;
        }
        return $ids;
    }

    public function getUserObject()
    {
        include_once(HLM_ROOT_PATH . '/class/users.php');
        $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
        return $users_handler;
    }

    public static function addConfig($module = 'system', $fname = 'configs')
    {
        $vname = 'jieqi' . ucfirst($fname);
        global ${$vname};
        if (!isset(self::$_config[$module][$vname])) {
            if (jieqi_getConfigs($module, $fname, $vname)) {
                if (isset(${$vname}[$module])) self::$_config[$module][$vname] = ${$vname}[$module];
                else self::$_config[$module][$vname] = ${$vname};
                return true;
            } else {
                return false;
            }
        } else return true;
    }

    public static function getConfig($module = '', $fname = '', $config = '')
    {
        if ($fname != '') $fname = 'jieqi' . ucfirst($fname);
        if ($module) {
            if ($config) {
                return self::$_config[$module][$fname][$config];
            } elseif ($fname) {
                return self::$_config[$module][$fname];
            } else {
                return self::$_config[$module];
            }
        } else return self::$_config;
    }

    public static function addlang($module, $fname)
    {
        global $jieqiLang;
        global $jieqiModules;
        if (jieqi_loadlang($fname, $module)) {
            if (!isset(self::$_lang[$module])) {
                self::$_lang[$module] = $jieqiLang[$module];
            } else {
                self::$_lang[$module] = array_merge(self::$_lang[$module], $jieqiLang[$module]);
            }
            return true;
        } else {
            return false;
        }
    }

    public static function getLang($mondle = '', $config = '')
    {
        if ($mondle) {
            if ($config) {
                return self::$_lang[$mondle][$config];
            } else {
                return self::$_lang[$mondle];
            }
        } else return self::$_lang;
    }

    function jumppage($url, $title = '', $content = '', $ext = array())
    {
        jieqi_jumppage($url, $title, $content, $ext);
    }

    function msgbox($title = '', $content = '', $ext = array())
    {
        jieqi_msgbox($title, $content, $ext);
    }

    function msgwin($title = '', $content = '', $ext = array())
    {
        jieqi_msgwin($title, $content, $ext);
    }

    function printfail($errorinfo = '', $ext = array())
    {
        jieqi_printfail($errorinfo, $ext);
    }

    function &getInstance($classname, $valarray = '')
    {
        static $instance;
        if (!isset($instance)) {
            if (class_exists($classname)) {
                if ($valarray == '') {
                    $instance = new $classname();
                } else {
                    if (is_array($valarray)) {
                        $instance = new $classname(implode(', ', $valarray));
                    } else {
                        $instance = new $classname($valarray);
                    }
                }
            } else {
                return false;
            }
        }
        return $instance;
    }

    function getVar($key, $format = 's')
    {
        if (isset($this->vars[$key])) {
            if (is_string($this->vars[$key])) {
                switch (strtolower($format)) {
                    case 's':
                        return jieqi_htmlstr($this->vars[$key]);
                    case 'e':
                        return htmlspecialchars($this->vars[$key], ENT_QUOTES);
                    case 'q':
                        return jieqi_dbslashes($this->vars[$key]);
                    case 'n':
                    default:
                        return $this->vars[$key];
                }
            } else return $this->vars[$key];
        } else {
            return false;
        }
    }

    function getVars()
    {
        return $this->vars;
    }

    function setVar($key, $value)
    {
        $this->vars[$key] = $value;
    }

    function setVars($var_arr)
    {
        foreach ($var_arr as $key => $value) {
            $this->setVar($key, $value);
        }
    }

    function clearVars()
    {
        $this->vars = array();
    }

    function raiseError($message = 'unknown error!', $mode = JIEQI_ERROR_DIE)
    {
        switch ($mode) {
            case JIEQI_ERROR_DIE:
                jieqi_printfail($message);
                break;
            case JIEQI_ERROR_RETURN:
            case JIEQI_ERROR_PRINT:
                $this->errors[$mode][] = $message;
                break;
            default:
                $this->errors[JIEQI_ERROR_RETURN][] = $message;
                break;
        }
    }

    function isError($mode = 0)
    {
        if (empty($mode)) return count($this->errors);
        elseif (isset($this->errors[$mode])) return count($this->errors[$mode]);
        else return 0;
    }

    function getErrors($mode = '')
    {
        if (empty($mode)) return $this->errors;
        return $this->errors[$mode];
    }

    function clearErrors($mode = '')
    {
        if (empty($mode)) $this->errors = array();
        else $this->errors[$mode] = array();
    }

    function dump($params, $isdie = 1)
    {
        echo '<pre>';
        print_r($params);
        echo '</pre>';
        if ($isdie == 1)
            die;
    }
}

class StopAttack extends JieqiObject
{
    private $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    private $postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    private $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";

    public function __construct()
    {
        if (!$_SESSION['jieqiAdminLogin']) {
            foreach ($_GET as $key => $value) {
                $this->checkparams($key, $value, $this->getfilter);
            }
            foreach ($_POST as $key => $value) {
                $this->checkparams($key, $value, $this->postfilter);
            }
            foreach ($_COOKIE as $key => $value) {
                $this->checkparams($key, $value, $this->cookiefilter);
            }
        }
    }

    public function checkparams($StrFiltKey, $StrFiltValue, $ArrFiltReq)
    {
        if (is_array($StrFiltValue)) $StrFiltValue = implode($StrFiltValue);
        if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
            $this->swritefile(JIEQI_ROOT_PATH . '/files/stopattack_log.html', "<br><hr>操作IP: " . $_SERVER["REMOTE_ADDR"] . "<br>操作时间: " . strftime("%Y-%m-%d %H:%M:%S") . "<br>操作页面:" . $_SERVER["PHP_SELF"] . "<br>提交方式: " . $_SERVER["REQUEST_METHOD"] . "<br>提交参数: " . $StrFiltKey . "<br>提交数据: " . $StrFiltValue . "\r\n", "a+");
            $this->printfail('您的提交带有不合法参数,谢谢合作!');
        }
        if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
            $this->swritefile(JIEQI_ROOT_PATH . '/files/stopattack_log.html', "<br><hr>操作IP: " . $_SERVER["REMOTE_ADDR"] . "<br>操作时间: " . strftime("%Y-%m-%d %H:%M:%S") . "<br>操作页面:" . $_SERVER["PHP_SELF"] . "<br>提交方式: " . $_SERVER["REQUEST_METHOD"] . "<br>提交参数: " . $StrFiltKey . "<br>提交数据: " . $StrFiltValue . "\r\n", "a+");
            $this->printfail('您的提交带有不合法参数,谢谢合作!');
        }
    }
}

class JieqiBlock extends JieqiObject
{
    var $db = null;
    var $blockvars = array();
    var $module = '';
    var $template = '';
    var $cachetime = JIEQI_CACHE_LIFETIME;

    function JieqiBlock(&$vars)
    {
        global $jieqiModules;
        global $jieqiTpl;
        $this->blockvars = $vars;
        if (empty($this->module)) $this->module = (empty($this->blockvars['module'])) ? 'system' : $this->blockvars['module'];
        if (empty($this->blockvars['template'])) $this->blockvars['template'] = $this->template;
        if (!empty($this->blockvars['template'])) {
            $this->blockvars['tlpfile'] = $jieqiModules[$this->module]['path'] . '/templates/blocks/' . $this->blockvars['template'];
        } else $this->blockvars['tlpfile'] = '';
        if ($this->cachetime == 0) $this->cachetime = JIEQI_CACHE_LIFETIME;
        if (empty($this->blockvars['cachetime'])) $this->blockvars['cachetime'] = $this->cachetime;
        if (empty($this->blockvars['overtime'])) $this->blockvars['overtime'] = 0;
        if (empty($this->blockvars['cacheid'])) $this->blockvars['cacheid'] = NULL;
        if (empty($this->blockvars['compileid'])) $this->blockvars['compileid'] = NULL;
        if (!empty($this->blockvars['template'])) $this->template = $this->blockvars['template'];
        if (!is_object($jieqiTpl) && !empty($this->blockvars['tlpfile'])) {
            include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
            $jieqiTpl =& JieqiTpl::getInstance();
        }
        if (!is_object($this->db) && class_exists('Application')) {
            $this->db = $this->load('database');
        }
    }

    function getTitle()
    {
        return isset($this->blockvars['title']) ? $this->blockvars['title'] : '';
    }

    function getContent()
    {
        global $jieqiTpl;
        if (JIEQI_USE_CACHE && !empty($this->blockvars['tlpfile']) && $this->blockvars['cachetime'] > 0 && $jieqiTpl->is_cached($this->blockvars['tlpfile'], $this->blockvars['cacheid'], $this->blockvars['compileid'], $this->blockvars['cachetime'], $this->blockvars['overtime'])) {
            $jieqiTpl->setCaching(1);
            return $jieqiTpl->fetch($this->blockvars['tlpfile'], $this->blockvars['cacheid'], $this->blockvars['compileid'], $this->blockvars['cachetime'], $this->blockvars['overtime'], false);
        } else {
            return $this->updateContent(true);
        }
    }

    function updateContent($isreturn = false)
    {
        global $jieqiTpl;
        $this->setContent();
        if (!empty($this->blockvars['tlpfile'])) {
            if (JIEQI_USE_CACHE && $this->blockvars['cachetime'] > 0) {
                $jieqiTpl->setCaching(2);
            } else {
                $jieqiTpl->setCaching(0);
            }
            $tmpvar = $jieqiTpl->fetch($this->blockvars['tlpfile'], $this->blockvars['cacheid'], $this->blockvars['compileid'], $this->blockvars['cachetime'], $this->blockvars['overtime'], false);
            if ($isreturn) return $tmpvar;
        }
    }

    function setContent($isreturn = false)
    {
    }
}

class JieqiCache extends JieqiObject
{
    function &retInstance()
    {
        static $instance = array();
        return $instance;
    }

    function close($cache = NULL)
    {
        if (is_object($cache)) {
            $cache->close();
        } else {
            $instance =& JieqiCache::retInstance();
            if (!empty($instance)) {
                foreach ($instance as $cache) {
                    $cache->close();
                }
            }
        }
    }

    function &getInstance($type = false, $options = array())
    {
        if (in_array(strtolower($type), array('file', 'memcached'))) $type = strtolower($type);
        else $type = 'file';
        if (JIEQI_VERSION_TYPE == '' || JIEQI_VERSION_TYPE == 'Free') $type = 'file';
        $class = 'JieqiCache' . ucfirst($type);
        $instance =& JieqiCache::retInstance();
        $inskey = md5($class . '::' . serialize($options));
        if (!isset($instance[$inskey])) {
            $instance[$inskey] = new $class($options);
            if ($type != 'file' && $instance[$inskey] === false) $instance[$inskey] = new JieqiCacheFile($options);
        }
        if (!defined('JIEQI_CACHE_CONNECTED')) @define('JIEQI_CACHE_CONNECTED', true);
        return $instance[$inskey];
    }
}

class JieqiCacheFile extends JieqiCache
{
    function JieqiCacheFile()
    {
        return true;
    }

    function close($cache = NULL)
    {
        return true;
    }

    function iscached($name, $ttl = 0, $over = 0)
    {
        if (empty($ttl) && empty($over)) {
            return is_file($name);
        } else {
            $ftime = @filemtime($name);
            if (!$ftime) return false;
            if (($ttl > 0 && JIEQI_NOW_TIME - $ftime > $ttl) || ($over > 0 && $over > $ftime)) {
                jieqi_delfile($name);
                return false;
            } else {
                return true;
            }
        }
    }

    function cachedtime($name)
    {
        if (file_exists($name)) return filemtime($name);
        else return 0;
    }

    function uptime($name)
    {
        @touch($name, time());
        @clearstatcache();
    }

    function get($name, $ttl = 0, $over = 0)
    {
        if (empty($ttl) && empty($over)) {
            return jieqi_readfile($name);
        } else {
            $ftime = @filemtime($name);
            if (!$ftime) return false;
            if (($ttl > 0 && JIEQI_NOW_TIME - $ftime > $ttl) || ($over > 0 && $over > $ftime)) {
                jieqi_delfile($name);
                return false;
            } else {
                return jieqi_readfile($name);
            }
        }
    }

    function set($name, $value, $ttl = 0, $over = 0)
    {
        if (jieqi_checkdir(dirname($name), true)) return jieqi_writefile($name, $value);
        else return false;
    }

    function delete($name)
    {
        return jieqi_delfile($name);
    }

    function clear($path = '')
    {
        if (!empty($path) && is_dir($path)) jieqi_delfolder($path);
    }
}

class JieqiCacheMemcached extends JieqiCache
{
    var $_connected;
    var $_mc;
    var $_md5key = true;
    var $_keyext = '.mt';

    function JieqiCacheMemcached($options)
    {
        if (!class_exists('Memcache')) exit('Memcache class not exists');
        if (!isset($options['host'])) $options['host'] = '127.0.0.1';
        if (!isset($options['port'])) $options['port'] = 11211;
        if (!isset($options['timeout'])) $options['timeout'] = false;
        if (!isset($options['persistent'])) $options['persistent'] = false;
        $func = $options['persistent'] ? 'pconnect' : 'connect';
        $this->_mc = &new Memcache;
        $this->_connected = ($options['timeout'] === false) ? @$this->_mc->$func($options['host'], $options['port']) : @$this->_mc->func($options['host'], $options['port'], $options['timeout']);
        if (!$this->_connected && JIEQI_ERROR_MODE > 0) echo 'Could not connect to memcache and try to use file cache now!<br />';
        return $this->_connected;
    }

    function close($cache = NULL)
    {
        if (is_object($this->_mc)) return $this->_mc->close();
        else return true;
    }

    function iscached($name, $ttl = 0, $over = 0)
    {
        return ($this->get($name, $ttl, $over) === false) ? false : true;
    }

    function cachedtime($name)
    {
        if ($this->_md5key) $name = md5($name);
        return intval($this->_mc->get($name . $this->_keyext));
    }

    function uptime($name)
    {
        if ($this->_md5key) $name = md5($name);
        return $this->_mc->set($name . $this->_keyext, time(), 0, 0);
    }

    function get($name, $ttl = 0, $over = 0)
    {
        $key = ($this->_md5key == true) ? md5($name) : $name;
        $ret = $this->_mc->get($key);
        if ($ret === false || (empty($ttl) && empty($over))) return $ret;
        else {
            $ctime = $this->cachedtime($name);
            if (($ttl > 0 && JIEQI_NOW_TIME - $ctime > $ttl) || ($over > 0 && $over > $ctime)) {
                $this->delete($name);
                return false;
            } else {
                return $ret;
            }
        }
    }

    function set($name, $value, $ttl = 0, $over = 0)
    {
        if ($ttl > 2592000) $ttl = 0;
        if ($this->_md5key) $name = md5($name);
        if ($over > JIEQI_NOW_TIME && $over - JIEQI_NOW_TIME < $ttl) $ttl = $over - JIEQI_NOW_TIME;
        return ($this->_mc->set($name . $this->_keyext, time(), 0, $ttl) && $this->_mc->set($name, $value, 0, $ttl));
    }

    function delete($name)
    {
        if ($this->_md5key) $name = md5($name);
        return ($this->_mc->delete($name . $this->_keyext) && $this->_mc->delete($name));
    }

    function clear()
    {
        return $this->_mc->flush();
    }
}
