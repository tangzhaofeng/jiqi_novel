<?php
echo '<table class="grid" width="100%" align="center">
  <caption>标签管理</caption>
  <tr>
    <td style="width: 20%;"><a href=\''.$this->_tpl_vars['adminprefix'].'&method=add&step=one\'><font color="red">添加标签</font></a></td>
    <td style="width: 80%;overflow: hidden;">
    	<form action="'.$this->_tpl_vars['adminprefix'].'" method="post" style="float: right;">
			<label for="">标签ID：</label>
			<input type="text" name="search_id" placeholder="按标签id查询" value="'.$this->_tpl_vars['_REQUEST']['search_id'].'" />
    		<label for="">查询名称：</label>
    		<input type="text" name="search_name" placeholder="按标签名称查询" value="'.$this->_tpl_vars['_REQUEST']['search_name'].'" />
    		<label for="">分类名称：</label>
    		<select name="search_ptype">
    			<option value="0" ';
if($this->_tpl_vars['_REQUEST']['search_ptype']==0){
echo ' selected="selected"';
}
echo '>全部分类</option>
    			';
if (empty($this->_tpl_vars['ptypes'])) $this->_tpl_vars['ptypes'] = array();
elseif (!is_array($this->_tpl_vars['ptypes'])) $this->_tpl_vars['ptypes'] = (array)$this->_tpl_vars['ptypes'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['ptypes']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['ptypes']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['ptypes']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['ptypes']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['ptypes']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
    			<option value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['_REQUEST']['search_ptype']==$this->_tpl_vars['i']['key']){
echo ' selected="selected"';
}
echo '>'.$this->_tpl_vars['ptypes'][$this->_tpl_vars['i']['key']]['module'].'&nbsp;|&nbsp;'.$this->_tpl_vars['ptypes'][$this->_tpl_vars['i']['key']]['name'].'</option>
    			';
}
echo '
    		</select>
    		<input type="submit" value="按条件查询" class="button" style="cursor: pointer;" />
    		<a href="'.$this->_tpl_vars['adminprefix'].'">查询全部</a>
    	</form>
    </td>
  </tr>
</table>
<form method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=order">
<table class="grid" width="100%" align="center">
    <caption>标签列表</caption>
    <tr>
        <th width="4%">排序</th>
        <th width="4%">ID</th>
        <th width="12%">标签名称</th>
        <th width="5%">标签分类</th>
		<th width="13%">模板调用标签</th>
		<th width="25%">远程调用js</th>
		<th width="7%">标签类型</th>
        <th width="7%">管理操作</th>
    </tr>';
if (empty($this->_tpl_vars['_PAGE']['rows'])) $this->_tpl_vars['_PAGE']['rows'] = array();
elseif (!is_array($this->_tpl_vars['_PAGE']['rows'])) $this->_tpl_vars['_PAGE']['rows'] = (array)$this->_tpl_vars['_PAGE']['rows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['_PAGE']['rows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['_PAGE']['rows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['_PAGE']['rows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['_PAGE']['rows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['_PAGE']['rows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	$this->_tpl_vars['posid']=$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid']; 
echo '
    <tr onmouseover="this.style.backgroundColor=\'#EAF8FF\'" onmouseout="this.style.backgroundColor=\'#ffffff\'">
      <td class="align_c"><input type="text" name="order['.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].']" value="'.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['listorder'].'" size="5"></td>
        <td class="align_c">'.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].'</td>
        <td class="align_c"><a href="?ac=position&op=view&posid='.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].'">'.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['name'].'</a></td>
        <td style="text-align: center;">';
if($this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['ptypeid']!=0){
echo $this->_tpl_vars['ptypes'][$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['ptypeid']]['module'].'|'.$this->_tpl_vars['ptypes'][$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['ptypeid']]['name'];
}else{
echo 'empty|未定义';
}
echo '</td>
		<td class="align_c"><input name="tagconfig" type="text" size="35" value="'.$this->_tpl_vars['_PAGE']['ltag'].'tag_system_'.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].'_'.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['name'].$this->_tpl_vars['_PAGE']['rtag'].'" onFocus="this.select()"></td>
		<td class="align_c"><input name="tagconfig" type="text" size="55" value="'.jieqi_geturl('system','tags',''.$this->_tpl_vars['posid'].'','js').'" onFocus="this.select()"></td>
		<td class="align_c">';
if($this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['type']=='0'){
echo '推荐位';
}elseif($this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['type']=='1'){
echo '查询区块';
}else{
echo '自定义内容';
}
echo '</td>
        <td class="align_c"><a href="'.$this->_tpl_vars['adminprefix'].'&method=view&posid='.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].'">预览</a>  | <a href="'.$this->_tpl_vars['adminprefix'].'&method=add&posid='.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].'">修改</a>  | <a href="javascript:confirmurl(\''.$this->_tpl_vars['adminprefix'].'&method=del&posid='.$this->_tpl_vars['_PAGE']['rows'][$this->_tpl_vars['i']['key']]['posid'].'\', \'是否删除该推荐位\')">删除</a> </td>
    </tr>';
}
echo '  
</table>
<div class="button_box"><input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" /><input name="dosubmit" type="submit" value=" 更新排序 " class="text"/></div>'.$this->_tpl_vars['_PAGE']['url_jumppage'].'
</form>
<table cellpadding="0" cellspacing="1" class="grid" width="100%">
  <caption>提示信息</caption>
  <tr>
    <td>标签功能支持完全手动更新，并且可以搜索内容，常用于区块、专题制作和首页频繁推荐更新的内容。<br />
	您可以在模板中直接插入"<font color="red">'.$this->_tpl_vars['_PAGE']['ltag'].'tag_system_1_栏目页图片文章'.$this->_tpl_vars['_PAGE']['rtag'].'</font>"格式的标签，即可显示标签设置的相应内容。<br />
	标签格式说明：<br />
	1、第一个部分"<font color="red">tag</font>"是系统定义标识，不能修改；<br />
	2、第二个部分"<font color="red">system</font>"是当前模块的名称，系统根据此来调用相应模块下的处理程序。<br />
	4、第三个部分"<font color="red">1</font>"是当前标签的编号ID，保证该编号存在即可。<br />
	4、第四个部分"<font color="red">栏目页图片文章</font>"是标签名称，对页面标签的一个说明。
	</td>
  </tr>
</table>';
?>