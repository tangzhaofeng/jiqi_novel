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
<form name="frmsearch" method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=bpsalecount">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">关键字：
            <input name="keyword" type="text" id="keyword" value="'.$this->_tpl_vars['_REQUEST']['keyword'].'" class="text" size="15" maxlength="50">&nbsp;<input type="radio" name="searchkey" value="bpname" ';
if($this->_tpl_vars['_REQUEST']['searchkey']=='bpname' || !isset($this->_tpl_vars['_REQUEST']['searchkey'])){
echo 'checked="checked"';
}
echo ' />&nbsp;书包名称&nbsp;<input type="radio" name="searchkey" value="account" ';
if($this->_tpl_vars['_REQUEST']['searchkey']=='account'){
echo 'checked="checked"';
}
echo ' />&nbsp;购买用户&nbsp;<input type="radio" name="searchkey" value="articleid" ';
if($this->_tpl_vars['_REQUEST']['searchkey']=='articleid'){
echo 'checked="checked"';
}
echo ' />&nbsp;书籍编号
            &nbsp;&nbsp;起始时间：<input onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" type="text" name="start" value="'.$this->_tpl_vars['_REQUEST']['start'].'" />
            &nbsp;&nbsp;结束时间：<input onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" type="text" name="end" value="'.$this->_tpl_vars['_REQUEST']['end'].'" />
            &nbsp;&nbsp;销售状态：
            <select name="overtime" >
                <option value="0" ';
if($this->_tpl_vars['_REQUEST']['overtime']==0 ||!$this->_tpl_vars['_REQUEST']['overtime']){
echo 'selected="selected"';
}
echo '>全部书包</option>
                <option value="1" ';
if($this->_tpl_vars['_REQUEST']['overtime']==1){
echo 'selected="selected"';
}
echo '>未过期书包</option>
                <option value="2" ';
if($this->_tpl_vars['_REQUEST']['overtime']==2){
echo 'selected="selected"';
}
echo '>已过期书包</option>
            </select>
            <input type="submit" name="dosubmit" class="button" style="cursor:pointer" value="搜 索">
        </td>
    </tr>
</table>
</form>
<br />
<table class="grid" width="100%" align="center">
    <caption>书包销售统计（销售总额：'.$this->_tpl_vars['totalprice'].'书海币）（共'.$this->_tpl_vars['count'].'条记录）</caption>
    <tr align="center">
        <th width="5%">序号</th>
        <th width="9%">购买日期</th>
        <th width="9%">购买时间</th>
        <th width="15%">书包名称</th>
        <!--<th width="13%">书籍名称</th>-->
        <th width="10%">书包销售单价</th>
        <th width="13%">购买用户</th>
        <th width="16%">阅读量</th>
        <th width="15%">过期时间</th>
        <th width="8%">过期状态</th>
    </tr>
    ';
if (empty($this->_tpl_vars['salelist'])) $this->_tpl_vars['salelist'] = array();
elseif (!is_array($this->_tpl_vars['salelist'])) $this->_tpl_vars['salelist'] = (array)$this->_tpl_vars['salelist'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['salelist']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['salelist']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['salelist']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['salelist']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['salelist']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
        <tr>
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['number'].'</td>
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['days'].'</td>
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['times'].'</td>
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['bpname'].'</td>
            <!--<td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['bookname'].'</td>-->
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['saleprice'].'书海币</td>
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['account'].'</td>
            <td align="center"><a id="j_bpsale_list" bpsale-id="'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['id'].'" bpname="'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['bpname'].'" href="javascript:;">[点击查看明细]</a></td>
            <td align="center">'.$this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['end_time'].'</td>
            <td align="center">';
if($this->_tpl_vars['salelist'][$this->_tpl_vars['i']['key']]['isend']==1){
echo '<p style="color:#66ff00">未过期</p>';
}else{
echo '<p style="color:#cc6666">过期</p>';
}
echo '</td>
        </tr>
    ';
}
echo '
</table>
<div class="pagelink">'.$this->_tpl_vars['url_jumppage'].'</div>
<script language="javascript" type="text/javascript">
$(function(){
   $("#j_bpsale_list").live("click",function(){
       var saleid=$(this).attr("bpsale-id");
       var bpname=$(this).attr("bpname");
       var htmls=\'\';
       GPage.getJson("'.$this->_tpl_vars['adminprefix'].'&method=search_click&saleid="+saleid,function(data){
           if(data.status=="OK"){
//                alert(111);
                htmls += \'<div class="d6"><table width="100%" border="1" bordercolor="#eeeeee" cellspacing="0" cellpadding="0"><tr><th width="15%" align="center" valign="middle" scope="col" class="sort">书籍编号</th><th width="30%" align="left" valign="middle" scope="col" class="name">书籍名称</th><th width="20%" align="left" valign="middle" scope="col" class="author">作者</th><th width="35%" align="center" valign="middle" scope="col" class="money">点击量（书籍/书包）</th></tr>\';
                $.each(data.msg, function(i, n){
                    if(!n.articleid){return false}
                    htmls += \'<tr><td align="center" valign="middle" class="sort">\'+n.articleid+\'</td>\';
                    htmls += \'<td align="left" valign="middle" class="name">\'+n.articlename+\'</td>\';
                    htmls += \'<td align="left" valign="middle" class="author">\'+n.author+\'</td>\';
                    htmls += \'<td align="center" valign="middle" class="sort">\'+n.clicks+\'/\'+data.msg.total+\'</td>\';
                });
                htmls += "</table></div>";
           }else{
//               alert(222);
                htmls=\'当前书包无数据...\';
           }
           $.layer({
                type:1,
                area:["auto", "auto"],
                title:bpname,
                page:{
                    html:htmls
            }
        });
       })
   }) 
})
</script>';
?>