<?php
echo '<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/calendar/WdatePicker.js"></script>
  <table width="100%" align="center" cellpadding="0" cellspacing="1" class="grid">
  <form name="frmsearch" method="post" action="'.$this->_tpl_vars['adminprefix'].'">  <tr>
      <td class="odd">
		  选择开始月份：<input name="start" id="start" onclick="WdatePicker({dateFmt:\'yyyy-MM\',minDate:\'2015-06\',maxDate:\'%y-%M\'})" autocomplete=\'off\' value="'.$this->_tpl_vars['start'].'" />
		  &nbsp;&nbsp;
        <input type="submit" name="search" class="button" value="确 定" /> 
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
  <caption>推广营收（未显示的日期，当天没有充值记录）</caption>
  <tr align="center" valign="middle">
    <th width="10%">时间</th>
    <th width="8%">账单收入（单位：元）</th>
    <th width="8%">公司收入（单位：元）</th>
  </tr>
  ';
$this->_tpl_vars['total_revenues'] = $this->_tpl_vars['total_realincome'] = '0.00'; 
echo '
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
    <td align="center" class="odd">'.$this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['f_buytime'].'</td>
    <td align="center" class="odd">';
echo number_format($this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['revenues'],2); 
echo '</td>
    <td align="center" class="even">';
echo number_format($this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['realincome'],2); 
echo '</td>
</tr>
					    		';
$this->_tpl_vars['total_revenues'] += $this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['revenues']; 
echo '
					    		';
$this->_tpl_vars['total_realincome'] += $this->_tpl_vars['payrows'][$this->_tpl_vars['i']['key']]['realincome']; 
echo '
  ';
}
echo '
 <tr valign="middle">
    <td align="center" class="odd">合计</td>
    
    <td align="center" class="odd">';
echo number_format($this->_tpl_vars['total_revenues'],2); 
echo '</td>
    <td align="center" class="even">';
echo number_format($this->_tpl_vars['total_realincome'],2); 
echo '</td>
</tr>
</table>
<br /><br />
';
?>