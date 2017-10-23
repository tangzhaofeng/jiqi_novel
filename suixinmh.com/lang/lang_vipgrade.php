<?php
/**
 * 语言包-VIP级别管理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: lang_honors.php 193 2008-11-25 02:52:44Z juny $
 */
global $jieqiConfigs;
if(!$jieqiConfigs['article']) jieqi_getConfigs('article', 'configs');
$jieqiLang['system']['vipgrade']=1; //表示本语言包已经包含
//admin/honors
$jieqiLang['system']['need_vipgrade_caption']='请输入VIP级别名称！';
$jieqiLang['system']['need_minscore_num']='积分大于必须为数字！';
$jieqiLang['system']['need_maxscore_num']='积分小于必须为数字！';
$jieqiLang['system']['max_than_min']='积分小于的值必须大于等于积分大于的值！';
$jieqiLang['system']['add_vipgrade_failure']='VIP级别添加失败，请与管理员联系！';
$jieqiLang['system']['edit_vipgrade_failure']='更新VIP级别失败，请与管理员联系！';
$jieqiLang['system']['edit_vipgrade']='修改VIP级别';
$jieqiLang['system']['add_vipgrade']='增加VIP级别';
$jieqiLang['system']['add_vipgrade_succ']='增加VIP级别成功';

//table field
$jieqiLang['system']['table_vipgrade_caption']='VIP级别名称';
$jieqiLang['system']['table_vipgrade_minscore']='积分大于';
$jieqiLang['system']['table_vipgrade_maxscore']='积分小于';
$jieqiLang['system']['table_vipgrade_photo']='图片标识';
$jieqiLang['system']['table_vipgrade_setting']='设置';
$jieqiLang['system']['table_vipgrade_head1']='积分加速';
$jieqiLang['system']['table_vipgrade_head2']='订阅折扣';
$jieqiLang['system']['table_vipgrade_head3']='保底月票（上月订阅满'.ceil($jieqiConfigs['article']['vipvotes']/100).'元可获得，1次/月）';
$jieqiLang['system']['table_vipgrade_head4']='消费月票（每订阅满'.ceil($jieqiConfigs['article']['vipvotes']/100).'元可得，无上限）';
$jieqiLang['system']['table_vipgrade_head5']='赠送推荐票';

?>