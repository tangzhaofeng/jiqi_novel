<?php
echo '<table align="center" cellpadding="2" cellspacing="1" class="grid" width="100%">
  <caption>标签管理</caption>
  <tr>
    <td><a href=\''.$this->_tpl_vars['adminprefix'].'&method=add&step=one\'><font color="red">添加标签</font></a> | <a href=\''.$this->_tpl_vars['adminprefix'].'\'>返回标签列表</a></td>
  </tr>
</table>
<table cellpadding="0" cellspacing="1" class="grid" width="100%">
<form action="'.$this->_tpl_vars['adminprefix'].'&method=add&posid='.$this->_tpl_vars['_SGLOBAL']['position']['posid'].'" method="post" name="myform" onSubmit="return CheckForm();">
    <caption>';
if($this->_tpl_vars['_SGLOBAL']['position']['posid']>0){
echo '编辑';
}else{
echo '添加';
}
if($this->_tpl_vars['_SGLOBAL']['position']['type']=='0'){
echo '推荐位';
}elseif($this->_tpl_vars['_SGLOBAL']['position']['type']=='1'){
echo '查询区块';
}else{
echo '自定义内容';
}
echo '</caption>
	<tr> 
      <th width=\'15%\'><font color="red">*</font> <strong>';
if($this->_tpl_vars['_SGLOBAL']['position']['type']=='0'){
echo '推荐位';
}elseif($this->_tpl_vars['_SGLOBAL']['position']['type']=='1'){
echo '查询区块';
}else{
echo '自定义内容';
}
echo '名称</strong></th>
      <td><input type="text" name="info[name]" id="name" value="'.$this->_tpl_vars['_SGLOBAL']['position']['name'].'" size="30" require="true" datatype="limit" min="2" max="30" msg="不得少于2个字符超过30个字符"></td>
    </tr>
    <tr> 
      <th width=\'15%\'><font color="red">*</font> <strong>标签分类</strong></th>
      <td>
		<select name="ptypeid" id="j_ptypeid">
			<option value="0" ';
if($this->_tpl_vars['_SGLOBAL']['position']['ptypeid']==0){
echo ' selected="selected" ';
}
echo ' style="color: red;">未定义</option>
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
if($this->_tpl_vars['_SGLOBAL']['position']['ptypeid']==$this->_tpl_vars['i']['key']){
echo ' selected="selected"';
}
echo '>'.$this->_tpl_vars['ptypes'][$this->_tpl_vars['i']['key']]['module'].'&nbsp;|&nbsp;'.$this->_tpl_vars['ptypes'][$this->_tpl_vars['i']['key']]['name'].'</option>
			';
}
echo '
		</select>
      </td>
    </tr>
	<tr> 
      <th><font color="red">*</font> <strong>排序权值</strong></th>
      <td><input type="text" name="info[listorder]" id="listorder"  value="'.$this->_tpl_vars['_SGLOBAL']['position']['listorder'].'" size="10" require="true" datatype="number" msg="请输入数字"></td>
    </tr>
	
	';
if($this->_tpl_vars['_SGLOBAL']['position']['type']=='2'){
echo '
		<tr> 
      <th><strong>区块内容</strong></th>
      <td>
	<textarea name="setting[content]" id="content" rows="20" cols="130">'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['content'].'</textarea></td>
    </tr>	
	';
}
echo '
	
	';
if($this->_tpl_vars['_SGLOBAL']['position']['type']=='2'){
echo '
	<tr> 
      <th><strong>模板文件说明</strong></th>
      <td>区块默认模板文件为“block_content.html”，在/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。</td>
    </tr>
	';
}
echo '
	';
if($this->_tpl_vars['_SGLOBAL']['position']['type']=='1'){
echo '
	<tr> 
      <th><strong>区块文件</strong></th>
      <td>';
if($this->_tpl_vars['_SGLOBAL']['position']['setting']['filename']!=''){
echo $this->_tpl_vars['_SGLOBAL']['position']['setting']['filename'].'.php';
}else{
echo '自定义区块';
}
echo '</td>
    </tr>
	';
if($this->_tpl_vars['_SGLOBAL']['position']['setting']['description']!=''){
echo '
	<tr> 
      <th><strong>区块描述</strong></th>
      <td>'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['description'].'</td>
    </tr>
	';
}
echo '
	';
if($this->_tpl_vars['_SGLOBAL']['position']['setting']['filename']!='' && $this->_tpl_vars['_SGLOBAL']['position']['setting']['hasvars']>0){
echo '
	<tr> 
      <th><strong>区块参数</strong></th>
      <td><textarea  name="setting[vars]" id="vars" rows="3" cols="60">'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['vars'].'</textarea>
	  </td>
    </tr>
	';
}
echo '
	';
if($this->_tpl_vars['_SGLOBAL']['position']['setting']['filename']==''){
echo '
		<tr> 
      <th><strong>区块内容</strong></th>
      <td>
	<textarea name="setting[content]" id="content" rows="15" cols="80">'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['content'].'</textarea></td>
    </tr>	
	';
}
echo '
	<input type="hidden" name="setting[bid]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['bid'].'"> 
	<input type="hidden" name="setting[module]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['module'].'"> 
	<input type="hidden" name="setting[filename]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['filename'].'"> 
	<input type="hidden" name="setting[classname]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['classname'].'">
	<input type="hidden" name="setting[contenttype]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['contenttype'].'"> 
	<input type="hidden" name="setting[custom]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['custom'].'"> 
	<input type="hidden" name="setting[publish]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['publish'].'"> 
	<input type="hidden" name="setting[hasvars]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['hasvars'].'"> 
	';
}
echo '
	
	<tr> 
      <th><font color="red">*</font> <strong>模板文件</strong></th>
      <td><input type="text" name="setting[template]" id="template" value="'.$this->_tpl_vars['_SGLOBAL']['position']['setting']['template'].'" size="25" require="true"></td>
    </tr>

	
    <tr> 
      <th></th>
      <td> 
	  <input type="hidden" name="info[type]" value="'.$this->_tpl_vars['_SGLOBAL']['position']['type'].'"> 
	  <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" />
	  <input type="submit" name="dosubmit" value=" 确定 "> 
      &nbsp; <input type="reset" name="reset" value=" 清除 ">
	  </td>
    </tr>
	</form>
</table>
<script language=\'JavaScript\' type=\'text/JavaScript\'>
function CheckForm(){
	if(document.myform.name.value==\'\'){
		alert(\'';
if($this->_tpl_vars['_SGLOBAL']['position']['type']=='0'){
echo '推荐位';
}elseif($this->_tpl_vars['_SGLOBAL']['position']['type']=='1'){
echo '查询区块';
}else{
echo '自定义内容';
}
echo '名称！\');
		document.myform.name.focus();
		return false;
	}
	if(document.myform.listorder.value==\'\'){
		alert(\'请输入排序权值！\');
		document.myform.listorder.focus();
		return false;
	}
	if(document.myform.template.value==\'\'){
		alert(\'请输入模板文件！\');
		document.myform.template.focus();
		return false;
	}
	if(document.getElementById("j_ptypeid").value==0){
		alert(\'必须为该标签设置一个分类\');
		return false;
	}
}
</script>';
?>