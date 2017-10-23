<?php
echo '<!--<table class="grid" width="100%" align="center">
  <tr>
    <td class="odd">&nbsp;&nbsp;&nbsp; 配置文件写法是指用户在某些页面用户可以用户可以直接选择要显示的区块并制作配置文件。每个区块在配置文件中的写法如下，其中各个参数含义为：\'bid\' - 区块的序号, \'blockname\' - \'区块名称\', \'module\' - 模块名称, \'filename\' - 区块处理程序名称，不含后缀, \'classname\' - 区块类的名称, \'side\' - 区块显示的位置(0:左边，1:右边，2:中左，3:中又，4:中上，5:中中，6:中下，7:顶部，8:底部), \'title\'- 区块标题, \'vars\' - 区块的参数, \'template\' - 区块的模板文件名, \'contenttype\' - 区块类型, \'custom\' - 是否自定义区块, \'publish\' - 是否显示（0:不显示，1:登陆前显示，2:登陆后显示，3:都显示）, \'hasvars\' - 是否允许参数调用。（一般需要调整的就是区块位置和是否显示，其他请参考下面的不变）</td>
  </tr>
</table>--><br />
<div class="gridtop">区块管理：
  <select class="select" name="modules" id="modules" onchange="document.location=\'?controller=blocks&modules=\'+this.options[this.selectedIndex].value;">
     <option value="">自定义区块</option>
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
if($this->_tpl_vars['modules'] == $this->_tpl_vars['i']['key']){
echo ' selected';
}
echo '>'.$this->_tpl_vars['jieqi_modules'][$this->_tpl_vars['i']['key']]['caption'].'</option>
	';
}
echo '  
  </select> | <a href="#addblock">增加自定义区块</a></div>
<table class="grid" width="100%" align="center">
  <tr align="center">
    <th width="5%">序号</th>
    <th width="14%">区块名</th>
    <th width="10%">模块名</th>
    <th width="7%">位置</th>
    <th width="7%">排序</th>
    <th width="9%">显示类型</th>
    <th width="17%">配置文件写法</th>
    <th width="17%">远程调用js</th>
    <th width="14%">操作</th>
  </tr>
  ';
if (empty($this->_tpl_vars['blocks'])) $this->_tpl_vars['blocks'] = array();
elseif (!is_array($this->_tpl_vars['blocks'])) $this->_tpl_vars['blocks'] = (array)$this->_tpl_vars['blocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['blocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['blocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['blocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['blocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['blocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['bid'].'</td>
    <td class="even" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['blockname'].'</td>
    <td class="odd" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['modname'].'</td>
    <td class="even" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['side'].'</td>
    <td class="odd" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['weight'].'</td>
    <td class="even" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['publish'].'</td>
    <td class="odd" align="center"><input name="txtconfig'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['bid'].'" type="text" size="20" value="'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['configtext'].'" onFocus="this.select()"></td>
    <td class="even" align="center"><input name="txtjs'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['bid'].'" type="text" size="20" value="'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['jstext'].'" onFocus="this.select()"></td>
    <td class="even" align="center">'.$this->_tpl_vars['blocks'][$this->_tpl_vars['i']['key']]['action'].'</td>
  </tr>
  ';
}
echo '
  <tr>
    <td colspan="9" class="foot">&nbsp;</td>
  </tr>
</table>
<br /><a name="addblock"></a>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">'.$this->_tpl_vars['form_addblock'].'</td>
  </tr>
</table>
';
?>