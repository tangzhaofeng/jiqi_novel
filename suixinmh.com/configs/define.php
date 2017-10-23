<?php
@define('JIEQI_URL','http://www.suixinmh.com');
@define('JIEQI_SITE_NAME','随心小说');
@define('JIEQI_CONTACT_EMAIL','');
@define('JIEQI_MAIN_SERVER','');
@define('JIEQI_USER_ENTRY','');
@define('JIEQI_SITE_KEY','');
@define('JIEQI_META_KEYWORDS','随心小说，随心小说网，随心小说阅读网，好看的小说，悬疑小说，历史小说，都市小说，军事小说，玄幻小说，网络小说，原创网络文学');
@define('JIEQI_META_DESCRIPTION','随心小说阅读网提供悬疑、校园、历史、都市、乡村、官场、灵异、军事、玄幻、奇幻、仙侠、武侠、科幻、游戏、同人等网络小说在线阅读，随心小说阅读网-www.ishufun.net');
@define('JIEQI_META_COPYRIGHT','Copyright @ 2014-2015 ishufun.net 随心小说阅读网 Allrights Reserved 版权所有');
@define('JIEQI_BANNER','');
@define('JIEQI_LICENSE_KEY','');
@define('JIEQI_DB_TYPE','mysql');
@define('JIEQI_DB_CHARSET','gbk');
@define('JIEQI_DB_PREFIX','jieqi');
@define('JIEQI_DB_HOST','localhost');
//@define('JIEQI_DB_HOST','rm-bp1p6i6e32l9fzo2q.mysql.rds.aliyuncs.com');
@define('JIEQI_DB_USER','book');
@define('JIEQI_DB_PASS','OJoKFQ0QpPz317ir');
@define('JIEQI_DB_NAME','book');
@define('JIEQI_DB_PCONNECT','1');
@define('JIEQI_IS_OPEN','1');
@define('JIEQI_CLOSE_INFO','网站系统维护中，预计3：00恢复，请稍后访问......');
@define('JIEQI_PROXY_DENIED','1');
@define('JIEQI_THEME_SET','v1');
@define('JIEQI_TOP_BAR','');
@define('JIEQI_BOTTOM_BAR','<a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:#FFFFFF">粤ICP备16032081号-1</a> 广州九跃网络科技有限公司版权所有');
@define('JIEQI_PAGE_TAG','<ul>[prepage]<li><a href="{$prepage}" class="null">上一页</a></li>[/prepage][pages][pnum]6[/pnum][pnumchar]<li><a href="javascript:;" class="before">{$page}</a></li>[/pnumchar][pnumurl]<li><a href="{$pnumurl}">{$pagenum}</a></li>[/pnumurl]{$pages}[/pages][nextpage]<li><a href="{$nextpage}">下一页</a></li>[/nextpage]<em class="pr10">共{$page}/{$totalpage}页</em></ul>');
@define('JIEQI_ERROR_MODE','0');
@define('JIEQI_ALLOW_REGISTER','1');
@define('JIEQI_DENY_RELOGIN','0');
@define('JIEQI_DATE_FORMAT','Y-m-d');
@define('JIEQI_TIME_FORMAT','H:i:s');
@define('JIEQI_REDIS_HOST','127.0.0.1');
@define('JIEQI_REDIS_PORT',6666);
@define('JIEQI_SESSION_EXPRIE','86400');
@define('JIEQI_SESSION_STORAGE','redis');
@define('JIEQI_SESSION_SAVEPATH','tcp://'.JIEQI_REDIS_HOST.':'.JIEQI_REDIS_PORT."?persistent=1");
@define('JIEQI_COOKIE_DOMAIN','suixinmh.com');
@define('JIEQI_CUSTOM_INCLUDE','0');
@define('JIEQI_ENABLE_CACHE','0');
@define('JIEQI_CACHE_LIFETIME','600');
@define('JIEQI_CACHE_DIR','cache');
@define('JIEQI_COMPILED_DIR','compiled');
@define('JIEQI_USE_GZIP','0');
@define('JIEQI_SILVER_USAGE','1');
@define('JIEQI_CHARSET_CONVERT','0');
@define('JIEQI_AJAX_PAGE','0');
@define('JIEQI_EGOLD_NAME','书币');
@define('JIEQI_FORM_MAX','100%');
@define('JIEQI_FORM_MIDDLE','580');
@define('JIEQI_FORM_MIN','400');
@define('JIEQI_MAX_PAGES','0');
@define('JIEQI_PROMOTION_VISIT','0');
@define('JIEQI_PROMOTION_REGISTER','0');


/**
 * 语言包-系统通用语言变量定义
 *
 * 语言包-系统通用语言变量定义
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) QQ329222795
 * @author     $Author: huliming $
 * @version    $Id: lang_system.php 193 2014-07-25 02:52:44Z huliming $
 */

define('LANG_NO_PERMISSION', '对不起，您无权访问该页面！'); 
define('LANG_NEED_ADMIN', '对不起，只有系统管理员才能使用本功能！');
define('LANG_NEED_LOGIN', '对不起，您需要登录才能使用本功能！');
define('LANG_NO_USER', '对不起，该用户不存在！');
define('LANG_DO_SUCCESS', '处理成功');
define('LANG_DO_FAILURE', '处理失败');
define('LANG_NOTICE', '提 示');
define('LANG_SUBMIT', '提 交');
define('LANG_RESET', '清 除');
define('LANG_SAVE', '保 存');
define('LANG_YES', '是');
define('LANG_NO', '否');
define('LANG_UNKNOWN', '未知');
define('LANG_PLEASE_ENTER', '请输入%s');
define('LANG_NEED_ENTER', '%s必须输入');
define('LANG_ERROR_PARAMETER', '对不起，参数错误！');
define('LANG_DENY_PROXY', '对不起，本站禁止通过代理访问！');
define('LANG_DENY_POST', '系统系统维护中，暂时不允许登陆和发表...');
define('LANG_MODULE_SYSTEM', '系统管理');
define('LANG_VERSION_FREE', '免费版');
define('LANG_VERSION_POPULAR', '普及版');
define('LANG_VERSION_STANDARD', '标准版');
define('LANG_VERSION_PROFESSION', '专业版');
define('LANG_VERSION_ENTERPRISE', '企业版');
define('LANG_VERSION_DELUXE', '豪华版');
define('LANG_VERSION_CUSTOM', '定制版');

$jieqiGroups['1'] = '游客';
$jieqiGroups['2'] = '系统管理员';
$jieqiGroups['3'] = '普通会员';
$jieqiGroups['4'] = '高级会员';
$jieqiGroups['5'] = '数据合作';
$jieqiGroups['6'] = '专栏作家';
$jieqiGroups['7'] = '专职作家';
$jieqiGroups['8'] = '签约编辑';
$jieqiGroups['9'] = '责任编辑';
$jieqiGroups['10'] = '主编';
$jieqiGroups['11'] = '兼职网编';
$jieqiGroups['12'] = '网站渠道管理员';
$jieqiGroups['13'] = '风起客服';
$jieqiGroups['14'] = '渠道专员';

?>