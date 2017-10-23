<?php
echo '<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/calendar/WdatePicker.js"></script>
<form name="frmquery" method="post" action="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=users">
<table class="grid" width="100%" align="center">
  <tr class="odd" align="center">
	<td>
	<a href="?controller=users">全部会员</a>';
if (empty($this->_tpl_vars['grouprows'])) $this->_tpl_vars['grouprows'] = array();
elseif (!is_array($this->_tpl_vars['grouprows'])) $this->_tpl_vars['grouprows'] = (array)$this->_tpl_vars['grouprows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['grouprows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['grouprows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['grouprows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['grouprows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['grouprows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo ' | <a href="?controller=users&groupid='.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['groupid'].'">'.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['groupname'].'</a>';
}
echo '
	</td>
  </tr>
  <tr>
    <td class="even">
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="hide">
      <tr>
        <td width="5%" valign="middle">会员数：'.$this->_tpl_vars['rowcount'].'</td>
        <td width="80%" valign="middle">
		起始注册时间：<input name="start" id="start" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" value="'.$this->_tpl_vars['start'].'" />
		  &nbsp;&nbsp;&nbsp;&nbsp;结束注册时间：<input name="end" id="end" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" value="'.$this->_tpl_vars['end'].'" />&nbsp;&nbsp;&nbsp;
		            来源：
        <select name="sel_site" id="sel_site">
                    <option value="-1">-全部来源-</option>
                    ';
if (empty($this->_tpl_vars['sites'])) $this->_tpl_vars['sites'] = array();
elseif (!is_array($this->_tpl_vars['sites'])) $this->_tpl_vars['sites'] = (array)$this->_tpl_vars['sites'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['sites']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['sites']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['sites']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['sites']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['sites']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
                    <option value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['sel_site'] != "" && $this->_tpl_vars['sel_site']==$this->_tpl_vars['i']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['i']['value'].'</option>
                    ';
}
echo '
                </select>
		用户名称：
        <input name="keyword" type="text" class="text" id="keyword" size="20" maxlength="50">
	    <input name="keytype" type="radio" value="uname" checked="checked" />用户名 
		<input name="keytype" type="radio" value="name" />昵称
          <input name="keytype" type="radio" value="uid" />UID
          <input type="submit" name="Submit" value="搜 索" class="button">&nbsp;&nbsp;
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
</form>
<br />
<form action="" method="post" name="checkform" id="checkform">
<table class="grid" width="100%" align="center">
<caption>用户列表</caption>
  <tr align="center" class="head">
    <td width="10%" valign="middle">用户名</td>
	<td width="10%" valign="middle">昵称</td>
    <td width="10%" valign="middle">注册日期</td>
	<td width="10%" valign="middle">最后登陆</td>
	<td width="10%" valign="middle">最后登陆IP</td>
    <td width="5%" valign="middle">等级</td>
    <td width="5%" valign="middle">来源</td>
    <td width="6%" valign="middle">经验值</td>
    <td width="5%" valign="middle">积分</td>
	<td width="6%" valign="middle">VIP积分</td>
    <td width="10%" valign="middle">'.$this->_tpl_vars['jieqi_sitename'].'币/银币</td>
<td width="6%" valign="middle">渠道</td>
    <td valign="middle">操作</td>
  </tr>
  ';
if (empty($this->_tpl_vars['userrows'])) $this->_tpl_vars['userrows'] = array();
elseif (!is_array($this->_tpl_vars['userrows'])) $this->_tpl_vars['userrows'] = (array)$this->_tpl_vars['userrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['userrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['userrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['userrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['userrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['userrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr valign="middle">
    <td class="even"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['userid'].'').'" target="_blank">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['username'].'</a></td>
	<td align="center" class="odd"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['userid'].'').'" target="_blank">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['name'].'</a></td>
    <td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['regdate'].'</td>
	<td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['setting']['logindate'].'</td>
	<td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['setting']['lastip'].'</td>
    <td class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['group'].'</td>
    <td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['from'].'</td>
    <td align="center" class="odd">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['experience'].'</td>
    <td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['score'].'</td>
	<td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['isvip'].'</td>
    <td align="center" class="odd">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['egold'].'/'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['esilver'].'</td><td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['source'].'</td>
    <td align="center" class="even"><a href="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=usermanage&id='.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['userid'].'">管理</a> <a href="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=users&action=login&id='.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['userid'].'" target="_blank">登陆</a></td>
  </tr>
  ';
}
echo '
</table>
</form>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">'.$this->_tpl_vars['url_jumppage'].'</td>
  </tr>
</table>

';
?>