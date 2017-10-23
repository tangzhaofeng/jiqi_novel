<?php
echo '<form name="frmreportlist" method="post" action="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=report">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="even" align="right">关键字：
            <input name="keyword" type="text" class="text" id="keyword" size="20" maxlength="50">
			<input name="keytype" type="radio" value="reportname" checked="checked" />提交人
			<input name="keytype" type="radio" value="authname" />处理人
			<input name="keytype" type="radio" value="reporttitle" />标题
            <input type="submit" name="Submit" value="搜 索" class="button">&nbsp;&nbsp;<a href="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=report">显示全部</a>&nbsp;&nbsp;</td>
    </tr>
</table>
</form>
<br />
<form action="'.$this->_tpl_vars['jieqi_url'].'/web_admin/?controller=report" method="post" name="checkform" id="checkform">
<table class="grid" width="100%" align="center">
<caption>
用户提交信息列表
</caption>
  <tr>
    <th width="5%"><input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }"></th>
    <th width="13%">提交人</th>
    <th width="15%">内容类型</th>
    <th width="40%">标题</th>
    <th width="10%">日期</th>
	<th width="5%">状态</th>
    <th width="12%">操作</th>
  </tr>
';
if (empty($this->_tpl_vars['reportrows'])) $this->_tpl_vars['reportrows'] = array();
elseif (!is_array($this->_tpl_vars['reportrows'])) $this->_tpl_vars['reportrows'] = (array)$this->_tpl_vars['reportrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['reportrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['reportrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['reportrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['reportrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['reportrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd" align="center" valign="top"><input type="checkbox" id="checkid[]" name="checkid[]" value="'.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reportid'].'"></td>
    <td class="even" valign="top"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reportuid'].'').'" target="_blank">'.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reportname'].'</a></td>
    <td class="odd" valign="top">'.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['typename'].'</td>
    <td class="odd" valign="top"><a href="/web_admin/?controller=report&method=detail&id='.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reportid'].'">'.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reporttitle'].'</a>
	</td>
    <td class="even" valign="top" align="center">'.date('Y-m-d',$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reporttime']).'</td>
	<td class="odd" valign="top" align="center">';
if($this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['authflag'] == 0){
echo '<span class="hottext">未阅</span>';
}else{
echo '已阅';
}
echo '</td>
    <td class="even" valign="top" align="center"><a href="javascript:if(confirm(\'确实要删除该报告么？\')) document.location=\'?controller=report&checkaction=1&checkid[]='.$this->_tpl_vars['reportrows'][$this->_tpl_vars['i']['key']]['reportid'].'\';">删除</a></td>
  </tr>
';
}
echo '
  <tr>
    <td colspan="7" class="foot">&nbsp;<input type="button" name="allcheck" value="全部选中" class="button" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ this.form.elements[i].checked = true; }">&nbsp;&nbsp;<input type="button" name="nocheck" value="全部取消" class="button" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ this.form.elements[i].checked = false; }">&nbsp;&nbsp;<input type="button" name="delcheck" value="删除选中记录" class="button" onclick="javascript:if(confirm(\'确实要删除选中记录么？\')){ this.form.checkaction.value=\'1\'; this.form.submit();}">&nbsp;&nbsp;<input name="checkaction" type="hidden" id="checkaction" value="0"></td>
  </tr>
</table>
</form>
<div class="pages">'.$this->_tpl_vars['url_jumppage'].'</div>

';
?>