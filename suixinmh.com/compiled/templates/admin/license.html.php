<?php
echo '<br />
<table class="grid" width="580" align="center">
  <caption>系统授权信息</caption>
  <tr>
    <td width="200" class="odd">授权域名</td>
    <td width="380" class="even">'.$this->_tpl_vars['license_domain'].'</td>
  </tr>
  <tr>
    <td class="odd">系统版本</td>
    <td class="even">'.$this->_tpl_vars['jieqi_version'].' '.$this->_tpl_vars['jieqi_vtype'].'</td>
  </tr>
  <tr>
    <td colspan="2" class="title" align="center">模块版本</td>
  </tr>
  ';
if (empty($this->_tpl_vars['licenses'])) $this->_tpl_vars['licenses'] = array();
elseif (!is_array($this->_tpl_vars['licenses'])) $this->_tpl_vars['licenses'] = (array)$this->_tpl_vars['licenses'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['licenses']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['licenses']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['licenses']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['licenses']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['licenses']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd">'.$this->_tpl_vars['licenses'][$this->_tpl_vars['i']['key']]['modname'].'</td>
    <td class="even">'.$this->_tpl_vars['licenses'][$this->_tpl_vars['i']['key']]['modversion'].' '.$this->_tpl_vars['licenses'][$this->_tpl_vars['i']['key']]['modvtype'].'</td>
  </tr>
  ';
}
echo '
</table>
<br />'.geturl('system','user','uid='.$this->_tpl_vars['uid'].'').'<br />
<a href="'.geturl('system','login','method=main').'" target="_blank">登陆</a><br />
'.geturl('article','article','method=masterPage').'<br />'.geturl('article','article','method=articleManage','id='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'
';
?>