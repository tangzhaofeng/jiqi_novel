<?php
echo '<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/calendar/WdatePicker.js"></script>
  <table width="100%" align="center" cellpadding="0" cellspacing="1" class="grid">
  <form name="frmsearch" method="post" action="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/admin/?method=pay">  <tr>
      <td class="odd">关键字：
        <input name="keyword" type="text" id="keyword" class="text" size="0" maxlength="50" value="'.$this->_tpl_vars['keyword'].'" />
          <input type="radio" name="keytype" class="radio" value="0"';
if($this->_tpl_vars['keytype']!=1){
echo ' checked="checked" ';
}
echo '/>
        交易序号
        <input name="keytype" type="radio" class="radio" value="1"';
if($this->_tpl_vars['keytype']==1){
echo ' checked="checked" ';
}
echo ' />
        用户名
<!--        <input type="radio" name="keytype" class="radio" value="2" />
        交易状态
        <input type="radio" name="keytype" class="radio" value="3" />
        手机号-->
<!--      </td>
        <td class="odd">-->
        
            来源:
        <select name="sel_site" id="sel_site">
                    <option value="-1">-全部来源-</option>
                    ';
if (empty($this->_tpl_vars['sites'])) $this->_tpl_vars['sites'] = array();
elseif (!is_array($this->_tpl_vars['sites'])) $this->_tpl_vars['sites'] = (array)$this->_tpl_vars['sites'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['sites']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['sites']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['sites']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['sites']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['sites']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
                    <option value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['sel_site'] != "" && $this->_tpl_vars['sel_site']==$this->_tpl_vars['i']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['i']['value'].'</option>
                    ';
}
echo '
                </select>    
        交易状态：
		  <select name="payflag">
		    <option value="all" ';
if($this->_tpl_vars['payflag']=='all'){
echo 'selected';
}
echo '>-未选择-</option>
		    <option value="3" ';
if($this->_tpl_vars['payflag']==3){
echo 'selected';
}
echo '>未确认</option>
		    <option value="1" ';
if($this->_tpl_vars['payflag']==1){
echo 'selected';
}
echo '>支付成功</option>
		    <option value="2" ';
if($this->_tpl_vars['payflag']==2){
echo 'selected';
}
echo '>手工确认</option>
		  </select>
<!--		<input type="radio" name="payflag" class="radio" value="0" ';
if($this->_tpl_vars['payflag']==0||$this->_tpl_vars['payflag']==''){
echo 'checked';
}
echo '>未确定
		<input type="radio" name="payflag" class="radio" value="1" ';
if($this->_tpl_vars['payflag']==1){
echo 'checked';
}
echo '>支付成功
		<input type="radio" name="payflag" class="radio" value="2" ';
if($this->_tpl_vars['payflag']==2){
echo 'checked';
}
echo '>手工确认-->
<!--		<input name="action" type="hidden" value="submit" />-->
<!--            <input type="submit" name="btnsearch" class="button" value="按购买量排序">         -->
<!--        </td>
		<td>-->&nbsp;&nbsp;支付方式：
		  <select name="paytype">
		    <option value="all">-未选择-</option>
		    ';
if (empty($this->_tpl_vars['paytyperows'])) $this->_tpl_vars['paytyperows'] = array();
elseif (!is_array($this->_tpl_vars['paytyperows'])) $this->_tpl_vars['paytyperows'] = (array)$this->_tpl_vars['paytyperows'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['paytyperows']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['paytyperows']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['paytyperows']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['paytyperows']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['paytyperows']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
		    	<option value="'.$this->_tpl_vars['j']['key'].'" ';
if($this->_tpl_vars['paytype']==$this->_tpl_vars['j']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['paytyperows'][$this->_tpl_vars['j']['key']]['name'].'</option>
			';
}
echo '
		  </select>&nbsp;&nbsp;
		  起始时间：<input name="start" id="start" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" value="'.$this->_tpl_vars['start'].'" />
		  &nbsp;&nbsp;结束时间：<input name="end" id="end" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\'})" value="'.$this->_tpl_vars['end'].'" />&nbsp;
        <input type="submit" name="search" class="button" value="确 定" /> <input type="submit" name="download" class="button" value="下 载" /> <a href="'.$this->_tpl_vars['jieqi_url'].'/modules/pay/admin/?start=';
echo date('Y-m',time()); 
echo '">综合统计</a>
		</td>
    </tr>
<!--    <tr>
      <td colspan="3" class="odd"><a href="'.$this->_tpl_vars['jieqi_url'].'/modules/pay/admin/paylog2.php">分类显示</a>成功支付：';
if (empty($this->_tpl_vars['paytyperows'])) $this->_tpl_vars['paytyperows'] = array();
elseif (!is_array($this->_tpl_vars['paytyperows'])) $this->_tpl_vars['paytyperows'] = (array)$this->_tpl_vars['paytyperows'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['paytyperows']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['paytyperows']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['paytyperows']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['paytyperows']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['paytyperows']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '<a href="'.$this->_tpl_vars['jieqi_url'].'/modules/pay/admin/paylog2.php?paytype='.$this->_tpl_vars['paytyperows'][$this->_tpl_vars['j']['key']]['type'].'"> '.$this->_tpl_vars['paytyperows'][$this->_tpl_vars['j']['key']]['name'].' </a>';
}
echo '<a href="'.$this->_tpl_vars['jieqi_url'].'/modules/pay/admin/paylogquery.php" target="_blank"> 日期统计 </a></td>
    </tr>--></form>
  </table>

<br />
<table class="grid" width="100%" align="center">
  <caption>在线支付记录 （总金额：'.$this->_tpl_vars['sum'].'） （'.$this->_tpl_vars['egoldname'].'：'.$this->_tpl_vars['totalegold'].'）（共'.$this->_tpl_vars['totalnum'].'条记录）</caption>
  <tr align="center" valign="middle">
    <th width="10%">序号</th>
    <th width="8%">日期</th>
    <th width="8%">时间</th>
    <th width="12%">用户名</th>
	<th width="10%">金额</th>
    <th width="12%">购买点数</th>
    <th width="7%">来源</th>
    <th width="12%">支付方式</th>
    <th width="5%">交易状态</th>
    <th width="">操作</th>
  </tr>
  ';
if (empty($this->_tpl_vars['payrows'])) $this->_tpl_vars['payrows'] = array();
elseif (!is_array($this->_tpl_vars['payrows'])) $this->_tpl_vars['payrows'] = (array)$this->_tpl_vars['payrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['payrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['payrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['payrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['payrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['payrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr valign="middle">
    <td align="center" class="odd">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['payid'].'</td>
    <td align="center" class="odd">'.date('Y-m-d',$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['buytime']).'</td>
    <td align="center" class="even">'.date('H:i:s',$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['buytime']).'</td>
    <td align="center" class="odd">';
if($this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['buyname'] == ''){
echo $this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['buyinfo'];
}else{
echo '<a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['buyid'].'').'" target="_blank">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['buyname'].'</a>';
}
echo '</td>
	<td align="center" class="even">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['money'].' ';
if($this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['moneytype']==1){
echo '美元';
}else{
echo '元';
}
echo '</td>
    <td align="center" class="even">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['egold'].'</td>
    <td align="center">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['from'].'</td>
    <td align="center" class="odd">';
if($this->_tpl_vars['paytyperows'][$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['paytype']] != null){
echo $this->_tpl_vars['paytyperows'][$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['paytype']]['name'];
}else{
echo $this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['paytype'];
}
echo '</td>
    <td class="even">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['payflag_c'].'</td>
    <td align="center" class="odd">';
if($this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['payflag'] == 0){
echo '<a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/admin/?method=pay&action=confirm&id='.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['payid'].'" ajaxclick="true" confirm="确定手工处理该条记录？" retruemsg="false">手工处理</a> | <a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/admin/?method=pay&action=del&id='.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['payid'].'" ajaxclick="true" confirm="确定删除该条记录？" retruemsg="false">删除</a>';
}
echo '</td>
  </tr>
  ';
}
echo '
</table>
<table class="hide" width="100%"  border="0" cellspacing="0" cellpadding="0">
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