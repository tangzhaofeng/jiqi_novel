<?php
echo '<script type="text/javascript">
function showReplies(id){//alert(id);
	$("#hidden").load(\''.$this->_tpl_vars['adminprefix'].'&method=showReplies&rid=\'+id+\' #content\');
	$.layer({
		type: 1,
		area: [\'880px\', \'auto\'],
		title: false,
		border: [0],
		page: {dom : \'#hidden\'}
	});
//'.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=reviews&method=showReplies&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'
}
function delReply(id){//alert(\''.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=reviews&method=delReply&pid=\'+id);return false;
	jumpurl = \''.$this->_tpl_vars['adminprefix'].'\';
	var page = "'.$this->_tpl_vars['_REQUEST']['page'].'";
	if(page>1) jumpurl +=(\'&page=\'+page);
	$.ajax({
		cache:false,
		url:\''.$this->_tpl_vars['adminprefix'].'&method=delReply&pid=\'+id,
		success: function(result){
			if(result == \'success\'){
				layer.msg(\'删除成功！\',2,1,function(){location.href=jumpurl});
			}else{
				layer.msg(\'删除失败\',2,8,function(){location.href=jumpurl});
			}
		}
	});
}
</script>
<form name="frmsearch" method="post" action="?controller=reviews">
<table class="grid" width="100%" align="center">
    <tr>
<!--      <td class="odd">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
         <tr>-->
           <td width="65%">关键字：
            <input name="keyword" type="text" id="keyword" class="text" size="0" maxlength="50"> <input name="keytype" type="radio" class="radio" value="1" checked>
            文章名称 
            <input type="radio" name="keytype" class="radio" value="0">
            发表人 &nbsp;&nbsp;
       <input type="submit" name="btnsearch" class="button" value="搜 索"></td>
           <td width="35%" align="right">[<a href="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=reviews&display=all">全部书评</a>] &nbsp;&nbsp; [<a href="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=reviews&display=isgood">精华书评</a>]&nbsp;</td>
         </tr>
<!--       </table></td>
    </tr>-->
</table>
</form>
<br />
<form name="frmsearch" method="post" action="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=reviews&method=batchDel" onSubmit="return check()">
<table class="grid" width="100%" align="center">
<caption>书评管理|<a href="'.$this->_tpl_vars['adminprefix'].'&method=mute_list">禁言记录</a></caption>
  <tr align="center">
  	<td width="5%" class="title"><input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }"></th>
    <td width="20%" class="title">主题</td>
    <td width="11%" class="title">书名</td>
    <td width="11%" class="title">点击/回复</td>
    <td width="11%" class="title">发表人</td>
    <td width="10%" class="title">发表时间</td>
	<td width="16%" class="title">操作</td>
  </tr>
  ';
if (empty($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = array();
elseif (!is_array($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = (array)$this->_tpl_vars['reviewrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['reviewrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['reviewrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['reviewrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
  	<td class="even" align="center"><input type="checkbox" id="checkid[]" name="checkid[]" value="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'"></td>
    <td class="odd">';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['istop'] == 1){
echo '<span class="hottext">[顶]</span>';
}
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['isgood'] == 1){
echo '<span class="hottext">[精]</span>';
}
echo '<a href="javascript:showReplies('.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].');" title="管理回复">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['title'].'</a></td>
    <td class="even"><a href="'.geturl('article','articleinfo','SYS=aid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['articleid'].'').'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></td>
    <td align="center" class="odd">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['views'].'/'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replies'].'</td>
    <td class="even"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posterid'].'').'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['poster'].'</a></td>
    <td align="center" class="odd">'.date('Y-m-d H:i:s',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posttime']).'</td>
	<td align="center" class="even">';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['istop'] == 0){
echo '[<a href="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_top'].'" ajaxclick="true" retruemsg="false">置顶</a>]';
}else{
echo '[<a href="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_untop'].'" ajaxclick="true" retruemsg="false">置后</a>]';
}
echo ' ';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['isgood'] == 0){
echo '[<a href="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_good'].'" ajaxclick="true" retruemsg="false">加精</a>]';
}else{
echo '[<a href="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_normal'].'" ajaxclick="true" retruemsg="false">去精</a>]';
}
echo ' [<a href="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_del'].'" ajaxclick="true" confirm="确实要删除该书评吗？" retruemsg="false">删除</a>] [';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_mute'] !=""){
echo '<a href="javascript:;" name="mute" url="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_mute'].'">禁言</a>';
}else{
echo '<a href="javascript:;">已禁言</a>';
}
echo ']</td>
  </tr>
  ';
}
echo '
</table>
<div style="width:15%;float:left;text-align:left;padding:3px;"><input type="submit" name="Submit" value="批量删除" class="button"><input name="batchdel" type="hidden" value="1"></div>
</form>
<div style="width:84%;float:right;text-align:right;padding:3px;">'.$this->_tpl_vars['url_jumppage'].'</div>
<div style="display:none" id="hidden"></div>
<script language="javascript" type="text/javascript">
function check()
{	 var k = -1;
	 $("input:checkbox[name=\'checkid[]\']:checked").each(function(i){
				k = i;
      });

	  if (k >= 0)
	  {
	  	  if (confirm("确实要批量删除书评么？"))
		  {
		  	return true;
		  }
	  }else{
	  	alert("请选择要删除的评论哦");
		return false;
	  }
	  return false;
}
</script>
<!-- mute layer begin -->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/article/templates/reviews_mute_layer.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<!-- mute layer end -->';
?>