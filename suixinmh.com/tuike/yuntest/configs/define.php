<?php
@define('JIEQI_URL','http://yun.mmd6666.com');
@define('YOUYUEBOOK_URL','http://www.mmd6666.com');
@define('YOUYUEBOOK_URL_M','http://m.mmd6666.com');

@define('JIEQI_SITE_NAME','youyue小说');
@define('JIEQI_CONTACT_EMAIL','');
@define('JIEQI_MAIN_SERVER','');
@define('JIEQI_USER_ENTRY','');
@define('JIEQI_SITE_KEY','');
@define('JIEQI_META_KEYWORDS','优阅, 优阅网, 优阅小说网, 好看的小说，言情小说, 悬疑小说，历史小说，都市小说，网络小说，原创网络文学');
@define('JIEQI_META_DESCRIPTION','优阅小说网提供言情、悬疑、校园、历史、都市、乡村、灵异、军事、玄幻、奇幻、仙侠、武侠、科幻、游戏、同人等网络小说在线阅读，优阅小说网-www.mmd6666.com');
@define('JIEQI_META_COPYRIGHT','Copyright @ 2014-2016 mmd6666.com 优阅阅读网 Allrights Reserved 版权所有');
@define('JIEQI_BANNER','');
@define('JIEQI_LICENSE_KEY','');
@define('JIEQI_DB_TYPE','mysql');
@define('JIEQI_DB_CHARSET','gbk');
@define('JIEQI_DB_PREFIX','jieqi');
@define('JIEQI_DB_HOST','10.24.33.222');
@define('JIEQI_DB_USER','xiaoshuo');
@define('JIEQI_DB_PASS','p7p9bVQ3fvJWezuC');
@define('JIEQI_DB_NAME','youyuebook');
@define('JIEQI_DB_PCONNECT','1');
@define('JIEQI_IS_OPEN','1');
@define('JIEQI_CLOSE_INFO','网站系统维护中，预计3：00恢复，请稍后访问......');
@define('JIEQI_PROXY_DENIED','1');
@define('JIEQI_THEME_SET','v1');
@define('JIEQI_TOP_BAR','');
@define('JIEQI_BOTTOM_BAR','<a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:#FFFFFF">粤ICP备16032081号-1</a> 广州九跃网络科技有限公司版权所有');

@define('JIEQI_PAGE_TAG','<div> <span id="page_totalcount_span" style="width:auto;padding:0 6px;">{$totalcount} 条记录 {$page}/{$totalpage} 页</span> [firstpage]<a class="end" href="{$firstpage}">1</a>[/firstpage][prepage] <a class="pre" href="{$prepage}"></a> [/prepage] [pages] [pnumchar] <span class="current">{$page}</span> [/pnumchar] [pnumurl] <a class="num" href="{$pnumurl}">{$pagenum}</a> [/pnumurl] {$pages} [/pages] [nextpage] <a class="next" href="{$nextpage}"></a> [/nextpage] [lastpage] <a class="end" href="{$lastpage}">{$totalpage}</a> [/lastpage] </div>');


@define('JIEQI_ERROR_MODE','0');
@define('JIEQI_ALLOW_REGISTER','1');
@define('JIEQI_DENY_RELOGIN','0');
@define('JIEQI_DATE_FORMAT','Y-m-d');
@define('JIEQI_TIME_FORMAT','H:i:s');

@define('JIEQI_REDIS_HOST','10.24.33.222');
@define('JIEQI_REDIS_PORT',6666);
@define('JIEQI_SESSION_EXPRIE','86400');
@define('JIEQI_SESSION_STORAGE','redis');
@define('JIEQI_SESSION_SAVEPATH','tcp://'.JIEQI_REDIS_HOST.':'.JIEQI_REDIS_PORT."?persistent=2");


@define('JIEQI_COOKIE_DOMAIN','yun.mmd6666.com'); 
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
@define('WECHAT_PAY_TYPE_WEB',2); // 1为代理支付，2为自己的微信支付
@define('MINIMUM_MONEY',100); // 最低的提现金额
@define('PAY_SYN_MONEY_QD',0.85); // 广告的转换现金比例
@define('PAY_SYN_MONEY_TK',0.10); // 推广的转换现金比例
@define('JIEQI_REDIS_FIX','NEWADMIN_'); // 


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

$jieqiGroups['1'] = '财务';
$jieqiGroups['2'] = '经理';
$jieqiGroups['3'] = '管理';
$jieqiGroups['6'] = '推客';
$jieqiGroups['7'] = '自家渠道';


$jieqiGroups['8'] = '签约编辑';
$jieqiGroups['9'] = '责任编辑';
$jieqiGroups['10'] = '主编';
$jieqiGroups['11'] = '兼职网编';
$jieqiGroups['12'] = '网站渠道管理员';
$jieqiGroups['13'] = '风起客服';
$jieqiGroups['14'] = '渠道专员';


$feeTypeAr=array('0'=>'打包价','1'=>'万粉价','2'=>'单价');

?>