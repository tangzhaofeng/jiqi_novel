<?php
echo '<style>
.layer_notice{float:left; height:75px; width:170px;  overflow:hidden; display:none;  background:#78BA32; padding:10px; border:1px solid #628C1C;}
</style>
<form name="frmsearch" method="post" action="?controller=salestat">
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
			<select name="nowagent" id="nowagent"><option value="0">-选择责编-</option>';
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
	echo '<option value="'.$this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uid'].'" ';
if($this->_tpl_vars['nowagent']==$this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uid']){
echo 'selected';
}
echo '>';
echo ($this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['name'] ? $this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['name'] : $this->_tpl_vars['agents'][$this->_tpl_vars['j']['key']]['uname']); 
echo '</option>';
}
echo '</select>
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
            <input type="submit" name="btnsearch" class="button" value="搜 索">
			<a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/admin/?controller=salestat&method=main&type=daysale&sortid=0&page=1">日榜</a>
			<a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/admin/?controller=salestat&method=main&type=weeksale&sortid=0&page=1">周榜</a>
			<a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/admin/?controller=salestat&method=main&type=monthsale&sortid=0&page=1">月榜</a>
			<a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/admin/?controller=salestat&method=main&type=totalsale&sortid=0&page=1">总榜</a>         
        </td>
    </tr>
</table>
</form>
<br />
<table class="grid" width="100%" align="center">
<caption>文章销售统计(销售额单位:书海币)</caption>
  <tr align="center" valign="middle">
  	<th width="16%">文章名称</th>
    <th width="8%">文章作者</th>
	<th width="8%">责编</th>
	<th width="5%">来源</th>
    <th width="8%">日销售('.$this->_tpl_vars['days'].')</th>
    <th width="8%">周销售('.$this->_tpl_vars['weeks'].')</th>
	<th width="8%">月销售('.$this->_tpl_vars['months'].')</th>
	<th width="8%">总销售额</th>
	<th >上架时间/最后更新</th>
	<th width="6%">状态</th>
	<th width="6%">连载状态</th>
	<th width="8%">章节销售</th>
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
  	<td align="left" class="even"><a href="'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank">';
echo str_replace($this->_tpl_vars['keyword'],'<span class="blue">'.$this->_tpl_vars['keyword'].'</span>' ,$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['articlename']); 
echo '</a></td>
    <td align="left" class="odd"><a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['authorid'].'').'" target="_blank">';
echo str_replace($this->_tpl_vars['keyword'],'<span class="blue">'.$this->_tpl_vars['keyword'].'</span>' ,$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['author']); 
echo '</a></td>
	<td align="center" class="even">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['agent'].'</td>
	<td align="center" class="even">'.$this->_tpl_vars['soruce'][$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['firstflag']].'</td>
    <td align="right" class="even">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['day'].'</td>
    <td align="right" class="odd">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['week'].'</td>
    <td align="right" class="odd">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['month'].'</td>
	<td align="right" class="odd">'.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['total'].'</td>
	<td align="center" class="odd">'.date('Y-m-d H:i:s',$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['postdate']).'<br/>'.date('Y-m-d H:i:s',$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['lastupdate']).'</td>
	<td align="center" class="odd">';
if($this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['display'] == 0){
echo '销售中';
}else if($this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['display'] == 1){
echo '待审';
}else if($this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['display'] == 2){
echo '定时';
}else if($this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['display'] == 9){
echo '定时待审';
}
echo '</td>
	<td align="center" class="odd">';
if($this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['fullflag'] ==1){
echo '完本';
}else{
echo '连载中';
}
echo '</td>
	<td align="center" class="odd"><a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/admin/?controller=salestat&method=chapterstat&aid='.$this->_tpl_vars['pay'][$this->_tpl_vars['i']['key']]['articleid'].'">章节销售</a></td>
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
function selectAgent(_this, articleid){
	var s = layer.tips($(\'.layer_notice\').html(), _this, {
        guide: 1,
		maxWidth:190,
		closeBtn:[0,true], //显示关闭按钮
        style: [\'background-color:#FFF8ED; color:#000; width:190;border:1px solid #FF9900\', \'#FF9900\']//[\'background-color:#FFF8ED; color:#000; border:1px solid #FF9900\', \'#FF9900\']
    });
	$(\'#tmpid\').val(articleid);
}
layer.ready(function(){
	$("[agentclick]").live(\'click\',function(event){
		event.preventDefault();
		var id = $(\'#tmpid\').val();
		var i = layer.load();//layer.load(0);
		GPage.getJson(urlParams(this.href, \'id=\'+id),
			function(data){
			    if(data.status==\'OK\'){			    	
					GPage.loadpage(\'content\', data.jumpurl, true, false);
					layer.closeAll();
				}else{
					layer.alert(data.msg, 8, !1);
				}
			}
		);
	});			 
});
</script>';
?>