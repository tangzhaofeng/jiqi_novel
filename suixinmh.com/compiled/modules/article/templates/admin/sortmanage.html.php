<?php
echo '<!--<script type="text/javascript">
$(document).ready(function(){
	$("select[name=\'channel\']").change(function(){
		alert($(this).children(\'option:selected\').val());
	});
});
</script>-->
<!--<form action="?controller=managemodules" method="post">-->
  <table class="grid" width="100%" align="center">
    <caption>
    分类管理：<select name="channel" onChange="document.location=\'?controller=sortmanage&channel=\'+this.options[this.selectedIndex].value;"><option value="0"';
if($this->_tpl_vars['channel']==0){
echo 'selected';
}
echo '>书海主站</option><option value="100"';
if($this->_tpl_vars['channel']==100){
echo 'selected';
}
echo '>女生频道</option><option value="200"';
if($this->_tpl_vars['channel']==200){
echo 'selected';
}
echo '>文学频道</option></select>
    </caption>
    <tr>
	  <td width="5%" align="center" class="title">分类ID</td>
      <td width="5%" align="center" class="title">渠道</td>
      <td width="5%" align="center" class="title">层</td>
      <td width="10%" align="center" class="title">标题</td>
      <td width="10%" align="center" class="title">名称</td>
      <td width="" align="center" class="title">描述</td>
      <td width="" align="center" class="title">图片地址</td>
      <td width="5%" align="center" class="title">简称</td>
      <td width="10%" align="center" class="title">类</td>
      <td width="5%" align="center" class="title">是否显示</td>
	  <td width="10%" align="center" class="title">管理</td>
    </tr>
    ';
if (empty($this->_tpl_vars['sort'])) $this->_tpl_vars['sort'] = array();
elseif (!is_array($this->_tpl_vars['sort'])) $this->_tpl_vars['sort'] = (array)$this->_tpl_vars['sort'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['sort']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['sort']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['sort']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['sort']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['sort']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td align="center">'.$this->_tpl_vars['i']['key'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['siteid'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['layer'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['caption'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['shortname'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['description'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['imgurl'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['shortcaption'].'</td>
    <td align="center">'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['class'].'</td>
    <td align="center"><select name="jieqiModules['.$this->_tpl_vars['i']['key'].'][publish]">
      <option value="1" ';
if($this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['publish']==1){
echo 'selected';
}
echo '>是</option>
      <option value="0" ';
if($this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['publish']==0){
echo 'selected';
}
echo '>否</option>
    </select>
    </td>
	<td align="center"><a href="'.$this->_tpl_vars['adminprefix'].'&method=editsortview&sortid='.$this->_tpl_vars['i']['key'].'">[修改]</a> <a href="'.$this->_tpl_vars['adminprefix'].'&method=delsort&sortid='.$this->_tpl_vars['i']['key'].'" ajaxclick="true" confirm="确定删除该分类？">[删除]</a></td>
  </tr>
    ';
}
echo '
<!--  <tr>
    <td colspan="9" align="center"><input type="submit" class="button" name="dosubmit" id="dosubmit" value="确定" /></td>
  </tr>-->
  </table>
<!--</form>-->
<br><br>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">
		<form name="blocksnew" id="blocksnew" action="'.$this->_tpl_vars['adminprefix'].'&method=addsort" method="post" onsubmit="return jieqiFormValidate_blocksnew();">
		<table width="650" class="grid" cellspacing="1" align="center">
		<caption>增加分类</caption>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">渠道</td>
		  <td class="even"><input type="text" class="text" name="siteid" id="siteid" size="4" maxlength="50" value="'.$this->_tpl_vars['channel'].'"></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">层</td>
		  <td class="even"><input type="text" class="text" name="layer" id="layer" size="4" maxlength="50" value="0"></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">标题</td>
		  <td class="even"><input type="text" class="text" name="caption" id="caption" size="20" maxlength="50" value=""></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">名称</td>
		  <td class="even"><input type="text" class="text" name="shortname" id="shortname" size="20" maxlength="50" value=""></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">描述</td>
		  <td class="even"><input type="text" class="text" name="description" id="description" size="40" maxlength="50" value=""></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">图片地址</td>
		  <td class="even"><input type="text" class="text" name="imgurl" id="imgurl" size="40" maxlength="50" value=""></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">简称</td>
		  <td class="even"><input type="text" class="text" name="shortcaption" id="shortcaption" size="20" maxlength="50" value=""></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">类</td>
		  <td class="even"><input type="text" class="text" name="class" id="class" size="20" maxlength="50" value=""></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%">是否显示</td>
		  <td class="even"><select name="publish"><option value="1">是</option><option value="0">否</option></select></td>
		</tr>
		<tr valign="middle" align="left">
		  <td class="odd" width="25%"><input type="hidden" name="channel" value="'.$this->_tpl_vars['channel'].'"></td>
		  <td class="even"><input type="submit" class="button" name="submit" id="submit" value="增加分类"></td>
		</tr>
		</table>
		</form>

<script language="javascript" type="text/javascript">
<!--
function jieqiFormValidate_blocksnew(){
  if(document.blocksnew.caption.value == ""){
    alert("请输入分类标题");
    document.blocksnew.caption.focus();
    return false;
  }
  if(document.blocksnew.shortname.value == ""){
    alert("请输入分类名称");
    document.blocksnew.shortname.focus();
    return false;
  }
  if(document.blocksnew.shortcaption.value == ""){
    alert("请输入分类简称");
    document.blocksnew.shortcaption.focus();
    return false;
  }
}
//-->
</script>
	</td>
  </tr>
</table>';
?>