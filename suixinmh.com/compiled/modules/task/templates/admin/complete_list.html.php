<?php
echo '<style>
.layer_notice{float:left; height:75px; width:170px;  overflow:hidden; display:none;  background:#78BA32; padding:10px; border:1px solid #628C1C;}
/*layer_notice li{ height:30px; line-height:30px;}
.layer_notice li a{ display:block; color:#fff;}
.layer_notice li a:hover{ background:#fff; color:#78BA32;}*/
.d6{ width:600px; height:auto; margin:0 auto;}
.d6 table{ background:#fff; border-collapse:collapse;table-layout: fixed}
.d6 table tr:hover{ background:#FC9}
.d6 table th{ color:#333; background:#f8f8f8;}
.d6 table th,table td{ height:30px; line-height:30px; padding:0 10px; white-space:nowrap; overflow:hidden;word-break: break-all}
.sort,.author,.money,.numb{ width:15%;}
.sort{ padding:0;}
.name,.date{ width:20%; height:30px; white-space:nowrap; overflow:hidden;text-overflow: ellipsis;}
.date{ text-align:right; color:#999;}
</style>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/calendar/WdatePicker.js"></script>
<style>
.layer_notice{float:left; height:75px; width:170px;  overflow:hidden; display:none;  background:#78BA32; padding:10px; border:1px solid #628C1C;}
/*layer_notice li{ height:30px; line-height:30px;}
.layer_notice li a{ display:block; color:#fff;}
.layer_notice li a:hover{ background:#fff; color:#78BA32;}*/
</style>
<form name="frmsearch" method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=main">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">关键字：
            <input name="keyword" type="text" id="keyword" value="'.$this->_tpl_vars['_REQUEST']['keyword'].'" class="text" size="15" maxlength="50">&nbsp;<input type="radio" name="searchkey" value="uname" ';
if($this->_tpl_vars['_REQUEST']['searchkey']=='uname' || !isset($this->_tpl_vars['_REQUEST']['searchkey'])){
echo 'checked="checked"';
}
echo ' />&nbsp;用户名&nbsp;<input type="radio" name="searchkey" value="name" ';
if($this->_tpl_vars['_REQUEST']['searchkey']=='name'){
echo 'checked="checked"';
}
echo ' />&nbsp;用户昵称&nbsp;<input type="radio" name="searchkey" value="taskname" ';
if($this->_tpl_vars['_REQUEST']['taskname']=='articleid'){
echo 'checked="checked"';
}
echo ' />&nbsp;任务名
            &nbsp;&nbsp;任务类型：
            <select name="tasktype" >
            		<option value="all">全部任务</option>
            		';
if (empty($this->_tpl_vars['types'])) $this->_tpl_vars['types'] = array();
elseif (!is_array($this->_tpl_vars['types'])) $this->_tpl_vars['types'] = (array)$this->_tpl_vars['types'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['types']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['types']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['types']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['types']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['types']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
                <option value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['_REQUEST']['tasktype']==$this->_tpl_vars['i']['key']){
echo ' selected="selected"';
}
echo '>'.$this->_tpl_vars['types'][$this->_tpl_vars['i']['key']].'</option>
                ';
}
echo '
            </select>
            &nbsp;&nbsp;起始时间：<input onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" type="text" name="start" value="'.$this->_tpl_vars['_REQUEST']['start'].'" />
            &nbsp;&nbsp;结束时间：<input onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" type="text" name="end" value="'.$this->_tpl_vars['_REQUEST']['end'].'" />
            <input type="submit" name="dosubmit" class="button" style="cursor:pointer" value="搜 索">
        </td>
    </tr>
</table>
</form>
<br />
<table class="grid" width="100%" align="center">
    <caption>用户任务完成列表</caption>
    <tr align="center">
        <th width="5%">序号</th>
        <th width="10%">用户昵称</th>
        <th width="13%">任务名称</th>
        <th width="13%">任务类型</th>
        <th width="40%">描述</th>
        <th width="14%">完成时间</th>
        <th width="5%">任务状态</th>
        <!--<th width="10%">操作</th>-->
    </tr>
    ';
if (empty($this->_tpl_vars['lists'])) $this->_tpl_vars['lists'] = array();
elseif (!is_array($this->_tpl_vars['lists'])) $this->_tpl_vars['lists'] = (array)$this->_tpl_vars['lists'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['lists']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['lists']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['lists']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['lists']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['lists']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
        <tr>
            <td align="center">'.$this->_tpl_vars['i']['order'].'</td>
            <td align="center">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['uname'].'</td>
            <td align="center">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['taskname'].'</td>
            <td align="center">'.$this->_tpl_vars['types'][$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['type']].'</td>
            <td align="left">'.$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['records'].'</td>
            <td align="center">'.date('Y-m-d H:i:s',$this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['createtime']).'</td>
            <td align="center">';
if($this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['isshow']==1){
echo '<p style="color:#66ff00">显示</p>';
}else if($this->_tpl_vars['lists'][$this->_tpl_vars['i']['key']]['isshow']==0){
echo '<p style="color:#cc6666">隐藏</p>';
}else{
echo '<p style="color:#dddddd">已删除</p>';
}
echo '</td>
            <!--<td align="center">操作</td>-->
        </tr>
    ';
}
echo '
</table>
<div class="pagelink">'.$this->_tpl_vars['url_jumppage'].'</div>
<script language="javascript" type="text/javascript"></script>';
?>