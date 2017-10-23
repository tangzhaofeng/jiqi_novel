<?php
echo '<script type="text/javascript">
function showReplies(id,pid){//alert(id);
	$("#hidden").load(\''.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=reviews&method=showReplies&rid=\'+id+\'&nowid=\'+pid+\' #content\');
	$.layer({
		type: 1,
		area: [\'896px\', \'500px\'],
		title: false,
		border: [0],
		page: {dom : \'#hidden\'},
		moveType: 1,
		title:"可拖动"
	});
//'.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=reviews&method=showReplies&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'
}
function delReply(id){//alert(\''.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=reviews&method=delReply&pid=\'+id);return false;
	jumpurl = \''.$this->_tpl_vars['adminprefix'].'\';
	var page = "'.$this->_tpl_vars['_REQUEST']['page'].'";
	if(page>1) jumpurl +=(\'&page=\'+page);
	$.ajax({
		cache:false,
		url:\''.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=reviews&method=delReply&pid=\'+id,
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
<style type="text/css">
.xubox_layer{ overflow:scroll; border:5px solid gray; top:150px; _top:30px;}
</style>
<form name="frmsearch" method="post" action="?controller=replies">
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
<form name="frmsearch" method="post" action="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=replies&method=batchDel" onSubmit="return check();">
<table class="grid" width="100%" align="center">
<caption>回帖管理</caption>
  <tr align="center">
  	<td width="5%" class="title"><input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }"></td>
    <td width="20%" class="title">主题</td>
    <td width="30%" class="title">回复内容</td>
    <td width="11%" class="title">发表人</td>
    <td width="11%" class="title">书名</td>
    <td width="10%" class="title">发表时间</td>
	<td width="16%" class="title">操作</td>
  </tr>
  ';
if (empty($this->_tpl_vars['replyrows'])) $this->_tpl_vars['replyrows'] = array();
elseif (!is_array($this->_tpl_vars['replyrows'])) $this->_tpl_vars['replyrows'] = (array)$this->_tpl_vars['replyrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['replyrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['replyrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['replyrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['replyrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['replyrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
  	<td class="even" align="center"><input type="checkbox" id="checkid[]" name="checkid[]" value="'.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['postid'].'"></td>
    <td class="odd"><a href="javascript:showReplies('.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['topicid'].','.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['postid'].');" title="管理回复">'.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['title'].'</a></td>
    <td class="even">'.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['posttext'].'</td>
    <td align="center" class="odd">';
if($this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['posterid'] > 0){
echo '<a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['posterid'].'').'" target="_blank">'.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['poster'].'</a>';
}else{
echo $this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['poster'];
}
echo '</td>
    <td class="even"><a href="'.geturl('article','articleinfo','SYS=aid='.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['articleid'].'').'">'.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></td>
    <td align="center" class="odd">'.date('Y-m-d H:i:s',$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['posttime']).'</td>
	<td align="center" class="even">[<a href="javascript:if(confirm(\'确实要删除该书评么？\')) document.location=\''.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=replies&method=delReply&pid='.$this->_tpl_vars['replyrows'][$this->_tpl_vars['i']['key']]['postid'].'\';">删除</a>]</td>
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
	  	  if (confirm("确实要批量删除回帖么？"))
		  {
		  	return true;
		  }
	  }else{
	  	alert("请选择要删除的回帖哦");
		return false;
	  }
	  return false;
}
</script>';
?>