<?php
echo '<style type="text/css">
.layer_notice{float:left; height:75px; width:170px;  overflow:hidden; display:none;  background:#78BA32; padding:10px; border:1px solid #628C1C;}
</style>
<!--<form action="'.geturl('article','chapter','SYS=method=delChapters').'" method="post" name="checkform" id="checkform">-->
<table class="grid" width="100%" align="center">
<caption>《'.$this->_tpl_vars['articlename'].'》</caption>
<tr><td colspan="7">说明：<br />1、功能：可以将之前的章节设为vip（文章入V后发布的章节自动入V）。<br />2、点击设置vip，则该章节<span class="red">之后</span>的<span class="red">所有</span>章节（包括本章，不包括已经入V的章节），都成为vip章节，章节价格按字数自动生成，同时该<span class="red">文章入V</span>（不包括已经入V的文章）。<br />3、分卷、已经入V章节不能设置。</td></tr>
  <tr align="center">
    <th width="10%">设置</th>
    <th width="10%">章节序号</th>
    <th width="30%">章节名称</th>
    <th width="10%">字数</th>
    <th width="10%">价格</th>
	<th width="10%">状态</th>
    <th width="20%">更新/发布</th>
  </tr>
  ';
if (empty($this->_tpl_vars['chapterrows'])) $this->_tpl_vars['chapterrows'] = array();
elseif (!is_array($this->_tpl_vars['chapterrows'])) $this->_tpl_vars['chapterrows'] = (array)$this->_tpl_vars['chapterrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['chapterrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['chapterrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['chapterrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['chapterrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['chapterrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr';
if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chaptertype'] >0){
echo ' style="background-color:#eee;"';
}else{
echo ' onmouseover="this.style.backgroundColor=\'#DDF2FF\';" onmouseout="this.style.backgroundColor=\'#ffffff\';"';
}
echo '>
    <td align="center">';
if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chaptertype']<1&&$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['isvip']<1){
echo '<a href="'.$this->_tpl_vars['article_dynamic_url'].'/admin/?controller=article&method=setvip&cid='.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chapterid'].'" target="_blank" class="set">设置vip</a>';
}
echo '</td>
    <td align="center">'.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chapterorder'].'</td>
    <td>';
if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chaptertype'] == 0){
echo '<a href="'.geturl('article','reader','SYS=aid='.$this->_tpl_vars['aid'].'&cid='.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chapterid'].'').'" target="_blank">'.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chaptername'].'</a>';
}else{
echo '<b>'.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['chaptername'].'</b>';
}
if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['isvip']>0){
echo '<img src="'.$this->_tpl_vars['jieqi_local_url'].'/images/vip.gif" border="0" />';
}
echo '</td>
    <td align="center">'.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['size_c'].'</td>
    <td align="center">'.$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['saleprice'].'</td>
	<td align="center">';
if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['display'] == 0){
echo '正常';
}else if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['display'] == 1){
echo '<span class="blue">待审</span>';
}else if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['display'] == 2){
echo '<span class="green">定时</span>';
}else if($this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['display'] == 9){
echo '<span class="org">定时待审</span>';
}
echo '</td>
    <td align="center">'.date('Y-m-d H:i:s',$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['lastupdate']).'<br>'.date('Y-m-d H:i:s',$this->_tpl_vars['chapterrows'][$this->_tpl_vars['i']['key']]['postdate']).'</td>
  </tr>
  ';
}
echo '
</table>
<!--</form>-->
<script language="javascript">
layer.ready(function(){
//	$(".set").click(function(){
//		event.preventDefault();
//		var i = layer.load();//layer.load(0);
//		GPage.getJson(urlParams(this.href),
//			function(data){
//			    if(data.status==\'OK\'){			    	
//					GPage.loadpage(\'content\', data.jumpurl, true, false);
//					layer.closeAll();
//				}else{
//					layer.alert(data.msg, 8, !1);
//				}
//			}
//		);
//	});
//	$("[agentclick]").live(\'click\',function(event){
//		event.preventDefault();
//		var id = $(\'#tmpid\').val();
//		var i = layer.load();//layer.load(0);
//		GPage.getJson(urlParams(this.href, \'id=\'+id),
//			function(data){
//			    if(data.status==\'OK\'){			    	
//					GPage.loadpage(\'content\', data.jumpurl, true, false);
//					layer.closeAll();
//				}else{
//					layer.alert(data.msg, 8, !1);
//				}
//			}
//		);
//	});			 
});
</script>';
?>