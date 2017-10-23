<?php
echo '<table class="grid" width="100%" align="center">
  <caption>标签分类管理</caption>
  <tr>
    <td>
    	<form action="'.$this->_tpl_vars['adminprefix'].'" method="post">
    		<label for="">按名称搜索</label>
    		<input type="text" name="name" placeholder="支持模糊查询" />
    		<input type="submit" class="button" value="搜索" />
    		<a href="'.$this->_tpl_vars['adminprefix'].'" class="button">搜索全部</a>
    	</form>
    </td>
  </tr>
</table>
<form method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=order">
<table class="grid" width="100%" align="center">
    <caption>标签分类列表&nbsp;[<a href=\''.$this->_tpl_vars['adminprefix'].'&method=add\'>+<span>添加新的标签</span></a>]</caption>
    <tr>
        <th width="4%">ID</th>
        <th width="20%">分类名称</th>
		<th width="15%">对应模块</th>
		<th width="35%">描述</th>
		<th width="16%">创建时间</th>
        <th width="10%">管理操作</th>
    </tr>
    ';
if (empty($this->_tpl_vars['lists'])) $this->_tpl_vars['lists'] = array();
elseif (!is_array($this->_tpl_vars['lists'])) $this->_tpl_vars['lists'] = (array)$this->_tpl_vars['lists'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['lists']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['lists']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['lists']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['lists']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['lists']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
    	<tr onmouseover="this.style.backgroundColor=\'#EAF8FF\'" onmouseout="this.style.backgroundColor=\'#ffffff\'">
	        <td class="align_c">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['id'].'</td>
	        <td class="align_c">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['name'].'</td>
			<td class="align_c">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['module'].'</td>
			<td class="align_c">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['description'].'</td>
			<td class="align_c">'.date('Y-m-d H:i:s',$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['createtime']).'</td>
	        <td class="align_c"><a href="'.$this->_tpl_vars['adminprefix'].'&method=edit&ptid='.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['id'].'">修改</a>  | <a href="javascript:confirmurl(\''.$this->_tpl_vars['adminprefix'].'&method=del&ptid='.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['id'].'\', \'是否删除该推荐位\')">删除</a> </td>
    	</tr>
    ';
}
echo '  
</table>
'.$this->_tpl_vars['url_jumppage'].'
</form>';
?>