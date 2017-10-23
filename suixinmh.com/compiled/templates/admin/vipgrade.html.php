<?php
echo '<table class="grid" width="100%" align="center">
  <caption>VIP级别管理</caption>
  <tr align="center">
    <th width="50">序号</th>
	<th width="50">图片标识</th>
    <th width="140">VIP级别名称</th>
    <th width="130">积分大于</th>
	<th width="130">积分小于</th>
    <th width="90">操作</th>
  </tr>
  ';
if (empty($this->_tpl_vars['vipgrade'])) $this->_tpl_vars['vipgrade'] = array();
elseif (!is_array($this->_tpl_vars['vipgrade'])) $this->_tpl_vars['vipgrade'] = (array)$this->_tpl_vars['vipgrade'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['vipgrade']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['vipgrade']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['vipgrade']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['vipgrade']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['vipgrade']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd" align="center">'.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['vipgradeid'].'</td>
	<td class="odd" align="center">'.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['photo'].'</td>
    <td class="even" align="center">'.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['caption'].'</td>
    <td class="odd" align="left">'.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['minscore'].'</td>
	<td class="odd" align="left">'.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['maxscore'].'</td>
    <td class="even" align="center"><a href="'.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['url_edit'].'">编辑</a>';
if($this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['vipgradetype'] == 0){
echo '  <a href="javascript:if(confirm(\'确实要删除该VIP级别么？\')) document.location=\''.$this->_tpl_vars['vipgrade'][$this->_tpl_vars['i']['key']]['url_del'].'\';">删除</a>';
}
echo '</td>
  </tr>
  ';
}
echo '
  <tr>
    <td colspan="6" class="foot">&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">'.$this->_tpl_vars['form_addvipgrade'].'</td>
  </tr>
</table>
';
?>