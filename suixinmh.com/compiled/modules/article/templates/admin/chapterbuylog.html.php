<?php
echo '<!--<form name="frmsearch" method="post" action="?controller=salestat">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">关键字：
            <input name="keyword" type="text" id="keyword" class="text" size="0" maxlength="50" value="'.$this->_tpl_vars['keyword'].'"> <input name="keytype" type="radio" class="radio" value="0" ';
if($this->_tpl_vars['keytype']!=1){
echo ' checked="checked" ';
}
echo '>
            文章名称
            <input type="radio" name="keytype" class="radio" value="1" ';
if($this->_tpl_vars['keytype']==1){
echo ' checked="checked" ';
}
echo '>
            文章作者 &nbsp;&nbsp;
            <input type="submit" name="btnsearch" class="button" value="搜 索">         
        </td>
    </tr>
</table>
</form>
<br />
-->
<table><tr><td><button onclick="history.back()">返回</button></td></tr></table>
<table class="grid" width="100%" align="center">
<caption>章节销售记录</caption>
  <tr align="center" valign="middle">
  	<th width="16%">书名</th>
    <th width="16%">章节</th>
    <th width="8%">价格(书海币)</th>
    <th width="8%">购买人</th>
	<th width="8%">购买日期</th>
  </tr>
  ';
if (empty($this->_tpl_vars['pay'])) $this->_tpl_vars['pay'] = array();
elseif (!is_array($this->_tpl_vars['pay'])) $this->_tpl_vars['pay'] = (array)$this->_tpl_vars['pay'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['pay']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['pay']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['pay']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['pay']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['pay']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr valign="middle">
  	<td align="left" class="even"><a href="'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['articlename'].'</a></td>
    <td align="left" class="odd"><a href="'.geturl('article','reader','aid='.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['articleid'].'','cid='.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['chapterid'].'').'" target="_blank">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['chaptername'].'</a></td>
	<td align="center" class="odd">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['saleprice'].'</td>
	<td align="center" class="odd"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['accountid'].'').'" target="_blank">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['account'].'</a></td>
	<td align="center" class="odd">'.date('Y-m-d H:i:s',$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['buytime']).'</td>
  </tr>
  ';
}
echo '
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="right">'.$this->_tpl_vars['url_jumppage'].'</td>
  </tr>
</table>
<br /><br />
';
?>