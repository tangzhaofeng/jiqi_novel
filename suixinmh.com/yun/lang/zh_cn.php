<?php
 /**===============================
  * 简体中文语言包
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ==================================*/
global $_GLOBALLANG;
$_GLOBALLANG = array(
//错误提示
	'Error:LoginError_0' => '帐号被封、请联系管理员！',
	'Error:LoginError_2' => '验证码错误',
    'Error:LoginError_3' => '用户名不存在或密码错误',
    'Error:LoginTimeOut' => '登录超时',
    'Error:AccessError' => '执行出错，请刷新重试',
    'Error:LoginRequestModeError' => '登录请求模式有误',
    'Error:PassNoSame' =>'两次密码不一致！',
    'Error:404' => '访问的内容不存在！',
	'Error:WuFaQuFen' => '手游无法拆分！',
	'Error:DataError' => '数据异常！',
	'Error:NotData' => '无结果返回,建议放宽检索条件。',
	'Error:CheckError' => '校验失败！',
	'Error:UserAgeRepeat' => '用户已存在！',
	'Error:confirm' => '请勿重复操作！',
	'Error:warning' => '这样做好玩？',
	'Error:confirmdata' => '存在数据尚未确认、无法进行导出！请选择单个游戏试试！',
	'Error:MustSeletGameidAgentid' => '前置要求需选择游戏和平台！',
	'Error:MustSeletGameid' => '请选择游戏！',
	'Error:InsufficientPermissions' => '权限不够！',
	'Error:DelGroupError' => '当前分组有用户在使用中、无法删除！',
    'Error:AccountUnnormal' => '此账号数据异常，请联系系统维护人员，谢谢',
	'Error:ToMuch' => '数据缺少过多，此方式不适宜！',
	'Error:MoreThan25' => '批量同步操作，一次不能超过25个！建议使用其它方式！',
	'Error:DateEmpty' => '日期不能为空！',
	'Error:CantChange' => '此数据已确认，不可改动，如有需求，请联系管理员！',
	'Error:pwdpreg' => '密码必须包含大小写字母和数字的组合，长度在6-16之间！',
	'Error:DateSelect' => '开始时间不能大于结束时间！',
	'Error:DateMax' => '日期不能超过',
	
   
//操作成功提示
    'Ok:Login' => '登录成功',
    'Ok:SubChangePwd' =>'密码修改成功',
    'Ok:DeleteSub' => '删除成功',
    'Ok:Update' => '更新成功',
	'Ok:OpenSucc' => '开启成功',
	'Ok:Insert' => '添加成功',
	'Ok:confirm' => '确认成功',
	'Ok:SendSucc' => '发送成功',
	'Ok:NohupSucc' => '已在后台执行',
	'Ok:RepairSuccess' => '修复成功',
	'Ok:WriteOk' => '写入成功，等待执行！',
	'Ok:RunOver' => '执行结束！请重新进行检索查看数据是否完整正确！'
);