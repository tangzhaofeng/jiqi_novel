<?php
echo '<form action="'.$this->_tpl_vars['url_action'].'" method="post" name="checkform" id="checkform">
<table class="grid" width="100%" align="center">
<caption>'.$this->_tpl_vars['boxname'].'</caption>
  <tr>
    <th width="5%">'.$this->_tpl_vars['checkall'].'</th>
    <th width="20%">'.$this->_tpl_vars['usertitle'].'</th>
    <th width="45%">标题</th>
    <th width="20%">日期</th>
    <th width="10%">状态</th>
  </tr>
';
if (empty($this->_tpl_vars['messagerows'])) $this->_tpl_vars['messagerows'] = array();
elseif (!is_array($this->_tpl_vars['messagerows'])) $this->_tpl_vars['messagerows'] = (array)$this->_tpl_vars['messagerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['messagerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['messagerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['messagerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['messagerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['messagerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd" align="center">'.$this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['checkbox'].'</td>
    <td class="even">';
if($this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['userid'] > 0){
echo '<a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['userid'].'').'" target="_blank">'.$this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['username'].'</a>';
}else{
echo '<span class="hottext">网站管理员</span>';
}
echo '</td>
    <td class="odd"><a href="'.$this->_tpl_vars['jieqi_url'].'/admin/?controller=messagedetail&id='.$this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['messageid'].'">'.$this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['title'].'</a></td>
    <td class="even">'.date('Y-m-d',$this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['postdate']).'</td>
    <td class="odd">';
if($this->_tpl_vars['messagerows'][$this->_tpl_vars['i']['key']]['isread'] == 0){
echo '<span class="hottext">未读</a>';
}else{
echo '已读';
}
echo '</td>
  </tr>
';
}
echo '
  <tr>
    <td colspan="5" class="foot">&nbsp;<input type="button" name="allcheck" value="全部选中" class="button" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ this.form.elements[i].checked = true; }">&nbsp;&nbsp;<input type="button" name="nocheck" value="全部取消" class="button" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ this.form.elements[i].checked = false; }">&nbsp;&nbsp;<input type="button" name="delcheck" value="删除选中记录" class="button" onclick="javascript:if(confirm(\'确实要删除选中记录么？\')){ this.form.checkaction.value=\'1\'; this.form.submit();}">&nbsp;&nbsp;<input type="button" name="delall" value="清空所有记录" class="button" onclick="javascript:if(confirm(\'确实要清空所有记录么？\'))document.location=\''.$this->_tpl_vars['url_delete'].'\'"><input name="checkaction" type="hidden" id="checkaction" value="0"></td>
  </tr>
</table>
</form>
<div class="pages">'.$this->_tpl_vars['url_jumppage'].'</div>

';
?>