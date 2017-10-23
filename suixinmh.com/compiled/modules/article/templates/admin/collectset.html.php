<?php
echo '<table class="grid" width="100%" align="center">
    <caption>配置说明</caption>
    <tr>
        <td class="odd">
		<ul>
		<li>采集网站列表的配置文件为：configs/article/collectsite.php</li>
		<li>某个网站的采集规则配置文件为：configs/article/site_网站英文标识.php</li>
		<li>网站英文标识不可以重复</li>
		</ul>
		</td>
    </tr>
</table>

<br />
<div class="gridtop">采集规则配置 | <a href="'.$this->_tpl_vars['article_static_url'].'/admin/?controller=collectset&method=collectnewview">添加新的采集规则</a></div>
<table class="grid" width="100%" align="center">
  <tr align="center">
    <th width="40%">网站名称</th>
    <th width="20%">网站英文标识</th>
    <th width="20%">单篇采集规则</th>
    <th width="20%">批量采集规则</th>
  </tr>
  ';
if (empty($this->_tpl_vars['siterows'])) $this->_tpl_vars['siterows'] = array();
elseif (!is_array($this->_tpl_vars['siterows'])) $this->_tpl_vars['siterows'] = (array)$this->_tpl_vars['siterows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['siterows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['siterows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['siterows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['siterows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['siterows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd" align="center"><a href="'.$this->_tpl_vars['siterows'][$this->_tpl_vars['i']['key']]['url'].'" target="_blank">'.$this->_tpl_vars['siterows'][$this->_tpl_vars['i']['key']]['name'].'</a></td>
    <td align="center" class="even">'.$this->_tpl_vars['siterows'][$this->_tpl_vars['i']['key']]['config'].'</td>
    <td align="center" class="odd"><a href="'.$this->_tpl_vars['article_static_url'].'/web_admin/?controller=collectset&method=collecteditview&config='.$this->_tpl_vars['siterows'][$this->_tpl_vars['i']['key']]['config'].'">编辑</a> | <a href="javascript:if(confirm(\'确实要删除该采集规则么？\')) document.location=\''.$this->_tpl_vars['article_static_url'].'/admin/?controller=collectset&action=del&config='.$this->_tpl_vars['siterows'][$this->_tpl_vars['i']['key']]['config'].'\'">删除</a></td>
    <td align="center" class="odd"><a href="'.$this->_tpl_vars['article_static_url'].'/web_admin/?controller=collectset&method=collectpage&config='.$this->_tpl_vars['siterows'][$this->_tpl_vars['i']['key']]['config'].'">进入管理</a></td>
  </tr>
  ';
}
echo '
  <tr>
    <td colspan="4" align="right" class="even"><a href="'.$this->_tpl_vars['article_static_url'].'/web_admin/?controller=collectset&method=collectnewview">添加新的采集规则</a>&nbsp;</td>
  </tr>
</table>
';
?>