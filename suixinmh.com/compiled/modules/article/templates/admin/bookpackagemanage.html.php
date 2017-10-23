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
<form method="post" action="'.$this->_tpl_vars['adminprefix'].'">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">
            关键字：<input name="keyword" type="text" value="'.$this->_tpl_vars['_REQUEST']['keyword'].'" class="text" size="15" maxlength="50">&nbsp;<input type="radio" name="searchkey" value="name" checked="checked" />&nbsp;书包名称&nbsp;<input type="radio" name="searchkey" value="articleid" />&nbsp;文章编号
            &nbsp;&nbsp;所属频道：
            <select name="siteid" >
            	<option value="-1">-选择频道-</option>
            	';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
            		<option value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['siteid'] != "" && $this->_tpl_vars['siteid']==$this->_tpl_vars['i']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['name'].'</option>
            	';
}
echo '
            </select>
            &nbsp;&nbsp;销售状态：
            <select name="showbookpackage" >
                <option value="0" ';
if($this->_tpl_vars['_REQUEST']['showbookpackage']==0){
echo 'selected="selected"';
}
echo '>全部书包</option>
                <option value="1" ';
if($this->_tpl_vars['_REQUEST']['showbookpackage']==1){
echo 'selected="selected"';
}
echo '>正在销售书包</option>
                <option value="2" ';
if($this->_tpl_vars['_REQUEST']['showbookpackage']==2){
echo 'selected="selected"';
}
echo '>暂停销售书包</option>
            </select>
            <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" /><input type="submit" name="dosubmit" class="button" value="搜 索">  
        </td>
    </tr>
</table>
</form>
<br />
<form action="'.$this->_tpl_vars['url_batchdel'].'" method="post" name="checkform" id="checkform" onSubmit="javascript:if(confirm(\'确实要批量删除文章么？\')) return true; else return false;">
<table class="grid" width="100%" align="center">
    <caption>书包管理（合计'.$this->_tpl_vars['bpcount'].'个书包）&nbsp;<a href="'.$this->_tpl_vars['adminprefix'].'&method=add_bp">[+添加新书包]</a></caption>
    <tr align="center">
        <th width="5%">书包编号</th>
        <th width="17%">书包名称</th>
        <th width="8%">规格</th>
        <th width="8%">包月价格</th>
        <th width="10%">所属频道</th>
        <th width="10%">书包详情</th>
        <th width="6%">销售状态</th>
        <!--<th width="6%">上架状态</th>-->
        <th width="12%">创建时间</th>
        <th width="12%">暂停销售时间</th>
        <th width="6%">状态操作</th>
    </tr>
  <!--'.$this->_tpl_vars['adminprefix'].'-->
  ';
if (empty($this->_tpl_vars['bplist'])) $this->_tpl_vars['bplist'] = array();
elseif (!is_array($this->_tpl_vars['bplist'])) $this->_tpl_vars['bplist'] = (array)$this->_tpl_vars['bplist'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['bplist']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['bplist']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['bplist']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['bplist']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['bplist']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr bpid="'.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['id'].'">
    <td class="odd" align="center">'.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['id'].'</td>
    <td class="even" align="center">'.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['name'].'</td>
    <td class="odd" align="center">'.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['booknumber'].'本书</td>
    <td class="even" align="center">'.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['price'];
if($this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['pricetype']==1){
echo '书海币';
}elseif($this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['pricetype']==2){
echo '银元';
}
echo '</td>
    <td class="odd" align="center">'.$this->_tpl_vars['channel'][$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['siteid']]['name'].'</td>
    <td class="even" align="center"><a class="j_bpdetails_jump" href="javascript:;" bpname="'.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['name'].'">[显示详情]</a></td>
    <td class="odd" align="center">';
if($this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['showbookpackage']==1){
echo '正在销售';
}else{
echo '<p style="color:#aaaaaa">暂停销售</p>';
}
echo '</td>
    <!--<td class="even" align="center">';
if($this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['putaway']==1){
echo '在售';
}else{
echo '<p style="color:#aaaaaa">下架</p>';
}
echo '</td>-->
    <td class="odd" align="center">'.date('Y-m-d H:i:s',$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['createtime']).'</td>
    <td class="even" align="center">';
if($this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['updatetime']<=$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['createtime']){
echo '最新';
}else{
echo date('Y-m-d H:i:s',$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['updatetime']);
}
echo '</td>
    <td class="odd" align="center"><a href="'.$this->_tpl_vars['adminprefix'].'&method=edit_bp&bpid='.$this->_tpl_vars['bplist'][$this->_tpl_vars['i']['key']]['id'].'" title="编辑"><img src="'.$this->_tpl_vars['jieqi_local_url'].'/images/editor.gif" border="0" /></a>&nbsp;&nbsp;<a class="j_bpdel_sub" href="javascript:;"><img src="'.$this->_tpl_vars['jieqi_local_url'].'/images/delete_on.gif" border="0" /></a></td>
  </tr>
  ';
}
echo '
<!--  <tr>
    <td width="3%" class="odd" align="center"><input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }"></td>
    <td colspan="6" align="left" class="odd"><input type="submit" name="Submit" value="批量删除" class="button"><input name="batchdel" type="hidden" value="1"><input name="url_jump" type="hidden" value="'.$this->_tpl_vars['url_jump'].'"><input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" /><strong></strong></td>
  </tr>-->
</table>
</form>
<div class="pagelink">'.$this->_tpl_vars['url_jumppage'].'</div>
<!--<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/jquery-1.8.3.min.js"></script>-->
<script language="javascript" type="text/javascript">
$(function(){
    var bpdetails = $(".j_bpdetails_jump");
    bpdetails.on("click", function(){
        var bpid = $(this).parent().parent("tr").attr("bpid");
        var bpname = $(this).attr("bpname");
        var jumpurl = "'.$this->_tpl_vars['adminprefix'].'&method=show_on_bp";
        $.ajax({
            type:"POST",
            url:jumpurl,
            data:{\'id\':bpid},
            dataType:"json",
            success:function(data){
                var htmls = "";
                if (data.status == \'200\') {
                    htmls += \'<div class="d6"><table width="100%" border="1" bordercolor="#eeeeee" cellspacing="0" cellpadding="0"><tr><th width="15%" align="center" valign="middle" scope="col" class="sort">类别</th><th width="20%" align="left" valign="middle" scope="col" class="name">文章名称</th><th width="15%" align="left" valign="middle" scope="col" class="author">作者</th><th width="15%" align="left" valign="middle" scope="col" class="money">原售价</th><th width="15%" align="left" valign="middle" scope="col" class="numb">字数</th><th width="20%" align="left" valign="middle" scope="col" class="date">加入书包时间</th></tr>\';
                    $.each(data.list, function(i, n){
                        htmls += \'<tr><td width="15%" align="center" valign="middle" class="sort">\'+n.sortname+\'</td>\';
                        htmls += \'<td width="20%" align="left" valign="middle" class="name">\'+n.articlename+\'</td>\';
                        htmls += \'<td width="15%" align="left" valign="middle" class="author">\'+n.author+\'</td>\';
                        htmls += \'<td width="15%" align="left" valign="middle" class="money">\'+n.saleprice+\'书海币</td>\';
                        htmls += \'<td width="15%" align="left" valign="middle" class="numb">\'+n.size+\'</td>\';
                        htmls += \'<td width="20%" align="left" valign="middle" class="date">\'+n.createtime+\'</td>\';
                    });
                    htmls += "</table></div>";
//                    alert(htmls);
                } else {
                    htmls = "<div style=\'width:400px\'>没有数据...</div>";
                }
                $.layer({
                    type:1,
//                    shade:[0.8, \'#cccccc\'],
                    area:["auto", "auto"],
                    title:bpname,
//                    border:[0],
                    page:{
                        html:htmls
                    }
                });
            }
        });
    })
    $(".j_bpdel_sub").on("click", function(){
        var bpid = $(this).parent().parent("tr").attr("bpid");
        var jumpurl = "'.$this->_tpl_vars['adminprefix'].'&method=del_on_bp";
//        alert(111);
        $.layer({
            area:[\'auto\', \'auto\'],
            dialog:{
                msg:\'本书包删除后将无法恢复，您确认继续删除？\',
                btns:2,
                type:3,
                btn:[\'删除\', \'取消\'],
                yes:function() {
                    $.ajax({
                        type:"POST",
                        url:jumpurl,
                        data:{\'id\':bpid},
                        dataType:"json",
                        success:function(data) {
                            if (data.status==\'200\') {
                                //
                                layer.msg(\'删除成功\', 3, 1);
                                location.reload();
                            } else if (data.status==\'300\') {
                                // 
                                layer.msg(data.msg, 3, 3);
                            } else {
                                layer.msg(\'网络不给力，请稍后重试\', 3, 3);
                            }
                        }
                    })
                }
            }
        })
    })
})
</script>';
?>