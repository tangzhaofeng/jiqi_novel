<?php
echo '<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/calendar/WdatePicker.js"></script>
<style type="text/css">
.layer_notice{float:left; height:75px; width:170px;  overflow:hidden; display:none;  background:#78BA32; padding:10px; border:1px solid #628C1C;}
#tabs13 .thistab{background:#DDF2FF};
</style>
<form name="frmsearch" method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=chapterStatistics">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">关键字：
            <input name="keyword" type="text" id="keyword" class="text" size="0" maxlength="50" value="'.$this->_tpl_vars['keyword'].'"> <input name="keytype" type="radio" class="radio" value="0"';
if($this->_tpl_vars['keytype']==0){
echo ' checked';
}
echo '>
            文章名称
			<input type="radio" name="keytype" class="radio" value="1"';
if($this->_tpl_vars['keytype']==1){
echo ' checked';
}
echo '>
            作者 &nbsp;&nbsp;
            ';
if($this->_tpl_vars['agents'] != null){
echo '
             <select name="nowagent" id="nowagent">
 				<option value="0">-选择责编-</option>
 				';
if (empty($this->_tpl_vars['agents'])) $this->_tpl_vars['agents'] = array();
elseif (!is_array($this->_tpl_vars['agents'])) $this->_tpl_vars['agents'] = (array)$this->_tpl_vars['agents'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['agents']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['agents']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['agents']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['agents']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['agents']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
 				<option value="'.$this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uid'].'" ';
if($this->_tpl_vars['nowagent']==$this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uid']){
echo 'selected';
}
echo '>
 					';
echo ($this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['name'] ? $this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['name'] : $this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uname']); 
echo '
 				</option>
 				';
}
echo '
 			</select>
            ';
}
echo '
            <select name="firstflag" id="firstflag">
            	<option value="">-选择来源-</option>
            	';
if (empty($this->_tpl_vars['soruce'])) $this->_tpl_vars['soruce'] = array();
elseif (!is_array($this->_tpl_vars['soruce'])) $this->_tpl_vars['soruce'] = (array)$this->_tpl_vars['soruce'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['soruce']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['soruce']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['soruce']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['soruce']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['soruce']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
            		<option value="'.$this->_tpl_vars['j']['key'].'" ';
if($this->_tpl_vars['firstflag'] != ""  && $this->_tpl_vars['firstflag']==$this->_tpl_vars['j']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['j']['value'].'</option>
            	';
}
echo '
            </select>

		  选择日期：<input name="ym" id="ym" onclick="WdatePicker({dateFmt:\'yyyy-MM\',minDate:\'2008-01\',maxDate:\'%y-%M\'})" autocomplete=\'off\' value="'.$this->_tpl_vars['year'].'-'.$this->_tpl_vars['month'].'" />
            <input type="submit" name="btnsearch" class="button" value="搜 索"><input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" />
<!--             &nbsp;&nbsp;&nbsp; <a href="'.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=chapter">分卷及章节</a> -->
<!-- 			&nbsp;&nbsp;&nbsp; <a href="'.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=chapter&display=volume">所有分卷</a> -->
<!-- 			&nbsp;&nbsp;&nbsp; <a href="'.$this->_tpl_vars['article_dynamic_url'].'/web_admin/?controller=chapter&display=chapter">所有章节</a></td> -->
    </tr>
</table>
</form>
<br />
<form action="'.geturl('article','chapter','SYS=method=delChapters').'" method="post" name="checkform" id="checkform">
<table class="grid" width="2000px" id="list">
<caption>'.$this->_tpl_vars['articletitle'].'</caption>
  <tr align="center">
  	<th width="10px"></th>
  	<th width="10px"></th>
    <th width="160px">文章名称</th>
    <th width="50px">月统计</th>
    <th width="50px">作者</th>
    <th width="50px">状态</th>
    <th width="50px">责编</th>
    ';
$this->_tpl_vars['day_num'] = range(1,$this->_tpl_vars['day_num']); 
echo '
	';
if (empty($this->_tpl_vars['day_num'])) $this->_tpl_vars['day_num'] = array();
elseif (!is_array($this->_tpl_vars['day_num'])) $this->_tpl_vars['day_num'] = (array)$this->_tpl_vars['day_num'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['day_num']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['day_num']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['day_num']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['day_num']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['day_num']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
		<th>'.$this->_tpl_vars['month'].'/'.$this->_tpl_vars['i']['value'].'</th>
	';
}
echo '
	<th width="50px">合计</th>
  </tr>
<!--   <tr onmouseover="this.style.backgroundColor=\'#DDF2FF\';" onmouseout="this.style.backgroundColor=\'#ffffff\';"> -->
  ';
if (empty($this->_tpl_vars['rows'])) $this->_tpl_vars['rows'] = array();
elseif (!is_array($this->_tpl_vars['rows'])) $this->_tpl_vars['rows'] = (array)$this->_tpl_vars['rows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['rows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['rows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['rows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['rows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['rows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
  	<td >'.$this->_tpl_vars['i']['order'].'</td>
  	<td ><input type="checkbox" name="selbox"/></td>
    <td ><a href="'.geturl('article','index','aid='.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['articleid'].'').'" target="_blank">'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['articlename'].'</a>';
if($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['articletype']>0){
echo '<img src="'.$this->_tpl_vars['jieqi_local_url'].'/images/vip.gif" border="0" />';
}
echo '</td>
    <td  align="center"><button onclick="monthChapterList('.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['articleid'].')" type="button">月计</button></td>
    <td  align="center">'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['author'].'</td>
    <td  align="center">'.$this->_tpl_vars['state'][$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['fullflag']].'</td>
    <td  align="center">'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['agent'].'</td>
    ';
$this->_tpl_vars['total_num'] = $this->_tpl_vars['total_size'] = 0; 
echo '
    ';
if (empty($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'])) $this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'] = array();
elseif (!is_array($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'])) $this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'] = (array)$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
    	';
if($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['t_num'] == 0 && $this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['t_size'] == 0){
echo ' 
    		<td >-</td>
    	';
}else{
echo '
    		';
$this->_tpl_vars['total_num'] += $this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['t_num']; 
echo '
    		';
$this->_tpl_vars['total_size'] += $this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['t_size']; 
echo '
    		<td ondblclick="dayChapterList('.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['articleid'].','.$this->_tpl_vars['j']['order'].');" ';
if($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['has_comment'] == 1){
echo 'style="border-bottom:1pt double #F30;"';
}elseif($this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['has_comment'] > 1){
echo 'style="border-bottom:1pt double blue;"';
}
echo '>'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['t_num'].'/'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['items'][$this->_tpl_vars['j']['key']]['t_size'].'</td>
    	';
}
echo '
    ';
}
echo '
    <td  align="center">'.$this->_tpl_vars['total_num'].'/'.$this->_tpl_vars['total_size'].'</td>
  </tr>
  ';
}
echo '
<!--   <tr> -->
<!--     <td width="3%" class="odd" align="center"><input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }"></td> -->
<!--     <td colspan="6" align="left" class="odd"><input type="button" name="Submit" value="批量删除" class="button" onclick="javascript:if(confirm(\'确实要批量删除章节么？\')) this.form.submit();"><input name="batchdel" type="hidden" value="1"></td> -->
<!--   </tr> -->
</table>
</form>
<table width="100%"  border="0" cellspacing="2" cellpadding="3">
  <tr>
    <td width="12%" align="right"><!--<input type="submit" name="Submit" value="批量删除" class="button">
                <input name="batchdel" type="hidden" value="1">--></td>
    <td width="88%" align="right">'.$this->_tpl_vars['url_jumppage'].'</td>
  </tr>
</table>
<div>
        <ul class="layer_notice"><li><b>[选择责编]</b><a href="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=article&action=setagent&uid=-1" agentclick="true">清空</a></li>';
if (empty($this->_tpl_vars['agents'])) $this->_tpl_vars['agents'] = array();
elseif (!is_array($this->_tpl_vars['agents'])) $this->_tpl_vars['agents'] = (array)$this->_tpl_vars['agents'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['agents']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['agents']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['agents']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['agents']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['agents']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
        	<li style="height:25px; line-height:25px;">['.$this->_tpl_vars['groups'][$this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['groupid']].']<a href="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=article&action=setagent&uid='.$this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uid'].'" agentclick="true">';
echo ($this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['name'] ? $this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['name'] : $this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uname']); 
echo '</a></li>
            ';
}
echo '<input type="hidden" value="0" id=\'tmpid\'>
        </ul>
</div>
<script language="javascript">
	var cellEvent = null;
	var rowIndex = 0;
	function addComment(cid){
		var comment = $("#comment"+cid).val();
		if(!comment){
			layer.msg(\'批注不能为空！\',1,{type:8,shade:false});
			return;
		}
		GPage.getJson(\''.$this->_tpl_vars['adminprefix'].'&method=addComment&cid=\'+cid+\'&comment=\'+encodeURIComponent(comment),function(data){
			if(data.status==\'OK\'){
				var id = layer.msg(data.msg[\'msg\'],1,{type:1,shade:false},function(obj){
					$("#comment_list_"+cid).prepend(\'<font color="red">@\'+data.msg[\'username\']+\'</font>  \'+data.msg[\'date\']+\'  \'+data.msg[\'comment\']+"  ");
					$("#comment"+cid).empty();
					var td=cellEvent.srcElement;
					if(td.tagName!="TD"){//如果事件不是发生在单元格上，退出函数
						return;
					}
// 					tb=td.parentElement.parentElement  //单元格的上两级对象为表格
					makerCell(td.cellIndex,td.parentNode.rowIndex,\'marker\');
					//layer.close(id);
				});
			}else{
				 layer.alert(data.msg, 8, !1);
			 }
		});
	}
	//标记|选中 指定的单元格
	function makerCell(cellIndex,rowIndex,active){
		tb = document.getElementById("list");
		for(var i=1;i<tb.rows.length;i++){
			for(var j=7;j<tb.rows[i].cells.length;j++){
				if(active == \'marker\'){//marker
					if(j==cellIndex && i==rowIndex){
						if(tb.rows[i].cells[j].style.borderBottomStyle){
// 							tb.rows[i].cells[j].style.borderBottom=\'1pt double blue\';//标记
							tb.rows[i].cells[j].style.borderBottom=\'1pt double #F30\';//标记
						}else{
							tb.rows[i].cells[j].style.borderBottom=\'1pt double #F30\';//标记
						}
						
						break;
					}
				}else{//select
					if(j==cellIndex && i==rowIndex){
						tb.rows[i].cells[j].bgColor=\'#069DD5\';//选择
					}else{
						tb.rows[i].cells[j].bgColor=\'\';
					}
				}

			}
		}
	}
	//月统计
	function monthChapterList(aid){
		//选中所在行的多选按钮
		var event = $.event.fix(arguments.callee.caller.arguments[0] || window.event);//jquery维护event统一
		rowIndex = $(event.target).parent().parent().index();
		
		var url = \''.$this->_tpl_vars['adminprefix'].'&method=getMonthChapters&aid=\'+aid+\'&year='.$this->_tpl_vars['year'].'&month='.$this->_tpl_vars['month'].'\';
		 $.layer({
				shade : [0.5 , \'#000\' , true],
				type : 1,
				area : [\'780px\',\'560px\'],
				title : false,
				offset : [\'30px\' , \'50%\'],
				border : [10 , 0.3 , \'#000\', true],
				zIndex : 1,
				page : {html : \'<div id="month" style="width:780px;height:560px;OVERFLOW-Y: auto; OVERFLOW-X:auto;"></div>\'},
				close : function(index){
					layer.close(index);
					$(\'.ul_con\').hide();
				}
			});	
		
 	    var i = layer.load(0);
		//加载模版
		GPage.loadpage(\'month\', url);
  		layer.close(i); 
	}
	function dayChapterList(aid,day){
		var i = layer.load(0);
		var event = $.event.fix(arguments.callee.caller.arguments[0] || window.event);//jquery维护event统一
		var td = event.target;
		if(td.tagName!="TD"){//如果事件不是发生在单元格上，退出函数
			return;
		}
		cellEvent = event;
		//选中单元格
		makerCell(td.cellIndex,td.parentNode.rowIndex);
		GPage.getJson(\''.$this->_tpl_vars['adminprefix'].'&method=getChapters&aid=\'+aid+\'&year='.$this->_tpl_vars['year'].'&month='.$this->_tpl_vars['month'].'&day=\'+day,function(data){
			layer.close(i);
			 if(data.status==\'OK\'){
				 html_dl = html_li = \'\';
				 for(i=0;i<data.msg.length;i++){
					 var cid = data.msg[i][\'chapterid\'];
					 var commentid = \'comment\'+cid;
					 var comment_list_id = \'comment_list_\'+cid;
					 var comment = data.msg[i][\'comment\'];
					 var content = \'\';
					 var style = \'\';//var type = \'（卷）\';
					 if(data.msg[i][\'chaptertype\'] == 0){//type = \'（章节）\';
						 content = data.msg[i][\'content\'];
					 }
					 if(comment.length == 1){
						 style = \'style="border-bottom:1pt double #F30"\';
					 }else if(comment.length > 1){
						 //style = \'style="border-bottom:1pt double blue"\';
						 style = \'style="border-bottom:1pt double #F30"\';
					 }
					 html_li += \'<li ><a href="javascript:void(0)" \'+style+\'>\'+data.msg[i][\'chaptername\']+\'</a></li>\';
					 html_dl += \'<li ><dl><textarea id="content" name="content" rows="20" cols="70" readonly="readonly">\'+content+\'</textarea></dl>\';
					 html_dl += \'<div style="margin-top:3px;" id="\'+comment_list_id+\'">\'; 
					 for(j=0;j<comment.length;j++){
						 html_dl += \'<font color="red">@\'+comment[j][\'username\']+\'</font>  \'+comment[j][\'date\']+\'  \'+comment[j][\'comment\']+\'  \'; 
					 }
					 html_dl += \'</div>\'; 
					 html_dl += \'<div style="margin-top:3px;"><div style="float:left;"><textarea id="\'+commentid+\'" name="content" maxlength="170" rows="7" cols="50"></textarea></div><div style="text-align:center;padding-top:15px;"><button style="width:80px;height:80px;" onclick="addComment(\'+cid+\')">批注</button></div></div>\';
					 html_dl += \'</li>\';
				 }
				 html = \'<div style="width:783px;padding:10px 5px;"><div style="float:left;width:30%;height:450px;clear:none;overflow-y:auto"><ul id="tabs13">\';
				 html += html_li;
				 html += \'</ul></div><ul id="tab_conbox13" style="float:left;width:68%;clear:none">\';
				 html += html_dl;
				 html += \'</ul></div>\';
				 $.layer({
						shade : [0.5 , \'#000\' , true],
						type : 1,
// 						area : [\'60%\',\'560px\'],
						area : [\'60%\',\'500px\'],
						title : false,
						offset : [\'30px\' , \'50%\'],
						border : [10 , 0.3 , \'#000\', true],
						zIndex : 1,
						page : {html : html},
						close : function(index){
							layer.close(index);
							$(\'.ul_con\').hide();
						}
					});	
				 $.jqtab("#tabs13","#tab_conbox13","click");
			 }else{
				 layer.alert(data.msg, 8, !1);
			 }
		});
	}
	$(document).ready(function(){
		$("#list tr:not(\':first\')").bind("mouseover",function(){
			 $(this).css("background","#DDF2FF");
		});
		$("#list tr:not(\':first\')").bind("mouseout",function(){
			if($(this).find("input[name=\'selbox\']").attr("checked")){
				$(this).css("background","#C3E8FF");
			}else{
				$(this).css("background","#ffffff");
			}
		});
	})
</script>













';
?>