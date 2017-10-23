<?php
echo '<form name="frmsearch" method="post" action="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=userlog">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">关键字：
            <input name="keyword" type="text" id="keyword" class="text" size="0" maxlength="50"> <input name="keytype" type="radio" class="radio" value="0" checked>
            操作人员
            <input type="radio" name="keytype" class="radio" value="1">
            操作对象 &nbsp;&nbsp;
            <input type="submit" name="btnsearch" class="button" value="搜 索">         
        </td>
    </tr>
</table>
</form>
<br />
<table class="grid" width="100%" align="center">
<caption>用户操作记录</caption>
  <tr align="center" valign="middle">
    <th width="12%">日期</th>
    <th width="12%">时间</th>
    <th width="14%">操作用户</th>
    <th width="14%">操作对象</th>
    <th width="20%">操作原因</th>
    <th width="28%">操作描述</th>
  </tr>
  ';
if (empty($this->_tpl_vars['logrows'])) $this->_tpl_vars['logrows'] = array();
elseif (!is_array($this->_tpl_vars['logrows'])) $this->_tpl_vars['logrows'] = (array)$this->_tpl_vars['logrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['logrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['logrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['logrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['logrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['logrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr valign="middle">
    <td align="center" class="odd">'.date('Y-m-d',$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['logtime']).'</td>
    <td align="center" class="even">'.date('H:i:s',$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['logtime']).'</td>
    <td align="center" class="odd"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['fromid'].'').'" target="_blank">'.$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['fromname'].'</a></td>
    <td align="center" class="even"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['toid'].'').'" target="_blank">'.$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['toname'].'</a></td>
    <td align="left" class="odd">'.$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['reason'].'</td>
    <td align="left" class="even">'.$this->_tpl_vars['logrows'][$this->_tpl_vars['i']['key']]['chginfo'].'</td>
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
<br /><br />
';
?>