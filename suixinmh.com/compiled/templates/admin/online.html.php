<?php
echo '<form name="frmquery" method="post" action="?controller=online">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="hide">
          <tr>
            <td width="30%" valign="middle">在线人数：'.$this->_tpl_vars['rowcount'].'</td>
            <td width="70%" align="right" valign="middle">用户名称：
            <input name="username" type="text" class="text" id="username" size="20" maxlength="30">
            <input type="submit" name="Submit" value="搜 索" class="button">&nbsp;&nbsp;</td>
          </tr>
        </table></td>
    </tr>
</table>
</form>
<br />
<table class="grid" width="100%" align="center">
<caption>用户列表</caption>
  <tr align="center" class="head">
    <td width="15%" valign="middle">用户名</td>
    <td width="10%" valign="middle">登陆时间</td>
    <td width="10%" valign="middle">活动时间</td>
    <td width="10%" valign="middle">等级</td>
    <td width="15%" valign="middle">IP</td>
    <td width="25%" valign="middle">来路</td>
    <td width="15%" valign="middle">操作</td>
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
    <td class="even"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['userid'].'').'" target="_blank">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['name'].'</a></td>
    <td align="center" class="odd">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['logintime'].'</td>
    <td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['updatetime'].'</td>
    <td align="center" class="odd">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['group'].'</td>
    <td align="center" class="even">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['ip'].'</td>
    <td align="left" class="odd">'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['browser'].'</td><!--'.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['location'].'-->
    <td align="center" class="even"><a href="/web_admin/?controller=online&action=del&sid='.$this->_tpl_vars['userrows'][$this->_tpl_vars['i']['key']]['sid'].'">强制下线</a></td>
  </tr>
  ';
}
echo '
</table>
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