<?php
echo '<table class="grid" width="100%" align="center">
  <caption>用户组管理</caption>
  <tr align="center">
    <th width="30">序号</th>
    <th width="100">名称</th>
    <th width="360">描述</th>
    <th width="70">操作</th>
  </tr>
  ';
if (empty($this->_tpl_vars['groups'])) $this->_tpl_vars['groups'] = array();
elseif (!is_array($this->_tpl_vars['groups'])) $this->_tpl_vars['groups'] = (array)$this->_tpl_vars['groups'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['groups']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['groups']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['groups']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['groups']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['groups']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd" align="center">'.$this->_tpl_vars['groups'][$this->_tpl_vars['i']['key']]['groupid'].'</td>
    <td class="even" align="center">'.$this->_tpl_vars['groups'][$this->_tpl_vars['i']['key']]['name'].'</td>
    <td class="odd" align="left">'.$this->_tpl_vars['groups'][$this->_tpl_vars['i']['key']]['description'].'</td>
    <td class="even" align="center"><a href="'.$this->_tpl_vars['groups'][$this->_tpl_vars['i']['key']]['url_edit'].'">编辑</a>';
if($this->_tpl_vars['groups'][$this->_tpl_vars['i']['key']]['grouptype'] == 0){
echo '  <a href="javascript:if(confirm(\'确实要删除该用户组么？\')) document.location=\''.$this->_tpl_vars['groups'][$this->_tpl_vars['i']['key']]['url_del'].'\';">删除</a>';
}
echo '</td>
  </tr>
  ';
}
echo '
  <tr>
    <td colspan="4" class="foot">&nbsp;</td>
  </tr>
</table>
<br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">'.$this->_tpl_vars['form_addgroup'].'</td>
  </tr>
</table>
';
?>