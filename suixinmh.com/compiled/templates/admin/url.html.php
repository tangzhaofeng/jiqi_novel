<?php
echo '<div class="gridtop">URL管理：
  <select class="select" name="modules" id="modules" onchange="document.location=\''.$this->_tpl_vars['url_mod'].'&mod=\'+this.options[this.selectedIndex].value;">
	';
if (empty($this->_tpl_vars['jieqi_modules'])) $this->_tpl_vars['jieqi_modules'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_modules'])) $this->_tpl_vars['jieqi_modules'] = (array)$this->_tpl_vars['jieqi_modules'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_modules']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_modules']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_modules']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_modules']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_modules']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
      <option value="'.$this->_tpl_vars['i']['key'].'"';
if($this->_tpl_vars['mod'] == $this->_tpl_vars['i']['key']){
echo ' selected';
}
echo '>'.$this->_tpl_vars['jieqi_modules'][$this->_tpl_vars['i']['key']]['caption'].'</option>
	';
}
echo '  
  </select>
</div>

<form action="'.$this->_tpl_vars['url_action'].'" method="post">
  <table class="grid" width="100%" align="center">

    <tr>
	<td width="8%" align="center">序</td>
	  <td width="10%" align="center">功能名称</td>
      <td width="25%" align="center">URL路径规则</td>
	  <td width="7%" align="center">生成HTML</td>
	  <td width="10%" align="center">控制器</td>
      <td width="10%" align="center">方法</td>
	  <td width="10%" align="center">参数</td>
	  <td align="center">说明</td>
    </tr> <!--
		<td >	
			<select name=\'urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][modname]\'>
			  ';
if (empty($this->_tpl_vars['modules'])) $this->_tpl_vars['modules'] = array();
elseif (!is_array($this->_tpl_vars['modules'])) $this->_tpl_vars['modules'] = (array)$this->_tpl_vars['modules'];
$this->_tpl_vars['a']=array();
$this->_tpl_vars['a']['columns'] = 1;
$this->_tpl_vars['a']['count'] = count($this->_tpl_vars['modules']);
$this->_tpl_vars['a']['addrows'] = count($this->_tpl_vars['modules']) % $this->_tpl_vars['a']['columns'] == 0 ? 0 : $this->_tpl_vars['a']['columns'] - count($this->_tpl_vars['modules']) % $this->_tpl_vars['a']['columns'];
$this->_tpl_vars['a']['loops'] = $this->_tpl_vars['a']['count'] + $this->_tpl_vars['a']['addrows'];
reset($this->_tpl_vars['modules']);
for($this->_tpl_vars['a']['index'] = 0; $this->_tpl_vars['a']['index'] < $this->_tpl_vars['a']['loops']; $this->_tpl_vars['a']['index']++){
	$this->_tpl_vars['a']['order'] = $this->_tpl_vars['a']['index'] + 1;
	$this->_tpl_vars['a']['row'] = ceil($this->_tpl_vars['a']['order'] / $this->_tpl_vars['a']['columns']);
	$this->_tpl_vars['a']['column'] = $this->_tpl_vars['a']['order'] % $this->_tpl_vars['a']['columns'];
	if($this->_tpl_vars['a']['column'] == 0) $this->_tpl_vars['a']['column'] = $this->_tpl_vars['a']['columns'];
	if($this->_tpl_vars['a']['index'] < $this->_tpl_vars['a']['count']){
		list($this->_tpl_vars['a']['key'], $this->_tpl_vars['a']['value']) = each($this->_tpl_vars['modules']);
		$this->_tpl_vars['a']['append'] = 0;
	}else{
		$this->_tpl_vars['a']['key'] = '';
		$this->_tpl_vars['a']['value'] = '';
		$this->_tpl_vars['a']['append'] = 1;
	}
	echo '
			  <option value="'.$this->_tpl_vars['a']['key'].'" ';
if($this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['modname']==$this->_tpl_vars['a']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['modules'][$this->_tpl_vars['a']['key']]['caption'].'</option>
			   ';
}
echo '
			</select>
			'.$this->_tpl_vars['modules'][$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['modname']]['caption'].'
		</td>
		-->
    ';
if (empty($this->_tpl_vars['urls'])) $this->_tpl_vars['urls'] = array();
elseif (!is_array($this->_tpl_vars['urls'])) $this->_tpl_vars['urls'] = (array)$this->_tpl_vars['urls'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['urls']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['urls']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['urls']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['urls']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['urls']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	  <tr>
	  	<td align="center">
			'.$this->_tpl_vars['i']['order'].'
		 </td>
		
		<td>
			<input name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][caption]" type="text" value="'.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['caption'].'" size="20">
		 </td>
		<td >
			<input name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][rule]" type="text" value="'.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['rule'].'" size="50">
		</td>
		<td align="center">';
if($this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['ishtml']!=99){
echo '<input type="checkbox" name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][ishtml]" value="1"';
if($this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['ishtml']==1){
echo ' checked';
}
echo ' />
		';
}else{
echo '<input type="hidden" name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][ishtml]" value="99" />';
}
echo '
		</td>
		<td >
			<input name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][controller]" type="text" value="'.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['controller'].'" size="20"';
if($this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['system']>0){
echo ' disabled';
}
echo '>
		</td>
		<td >
			<input name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][method]" type="text" value="'.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['method'].'" size="20"';
if($this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['system']>0){
echo ' disabled';
}
echo '>
		</td>
		<td >
			<input name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][params]" type="text" value="'.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['params'].'" size="70" maxlength="100" ';
if($this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['system']>0){
echo ' disabled';
}
echo '>
		</td>
		<td>
			<input name="urls['.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['id'].'][description]" type="text" value="'.$this->_tpl_vars['urls'][$this->_tpl_vars['i']['key']]['description'].'" size="30">
		</td>
	  </tr>
    ';
}
echo '
  <tr>
    <td colspan="8" align="center"><input type="submit" class="button" name="dosubmit" id="dosubmit" value="更新" title="批量更新"/></td>
  </tr>
  </table>
</form>
<br />
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">'.$this->_tpl_vars['addForm'].'</td>
  </tr>
</table>';
?>