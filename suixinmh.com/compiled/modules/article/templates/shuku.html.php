<?php
$this->_tpl_vars['jieqi_pagetitle'] = '小说书库-最新原创小说大全-书海小说网';
echo '
';
$this->_tpl_vars['meta_keywords'] = '最新小说,原创小说,小说大全,小说库';
echo '
';
$this->_tpl_vars['meta_description'] = '书海小说书库提供最新的原创小说大全，提供各类网络小说在线阅读，希望大家支持书海原创小说库!';
echo '
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/sort.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'/js/jquery.cookie.js"></script>
<!--wrap begin-->
<div class="wrap fix">
<div class="ad2"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/bd2.js"></script></div>
   <!--term begin-->
   
   <div class="term fix mb10">
   <h3>书海文学汇聚最优秀的海量作品，无限精彩任您挑选</h3>
     <div class="box_term fix">
       <span class="t">作品类别：</span>
			<div class="sort">
				 ';
if (empty($this->_tpl_vars['sort'])) $this->_tpl_vars['sort'] = array();
elseif (!is_array($this->_tpl_vars['sort'])) $this->_tpl_vars['sort'] = (array)$this->_tpl_vars['sort'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['sort']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['sort']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['sort']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['sort']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['sort']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
					<a href="'.geturl('article','shuku','SYS=sort='.$this->_tpl_vars['i']['key'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'" ';
if($this->_tpl_vars['sel_sort'] == $this->_tpl_vars['i']['key']){
echo 'class="on"';
}
echo ' >'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['shortcaption'].'</a>
				';
}
echo ' 
			</div>
		</div>
		<div class="box_term fix">
			<span class="t">作品字数：</span>
			<p class="tag f_gray3">
				';
if (empty($this->_tpl_vars['size'])) $this->_tpl_vars['size'] = array();
elseif (!is_array($this->_tpl_vars['size'])) $this->_tpl_vars['size'] = (array)$this->_tpl_vars['size'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['size']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['size']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['size']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['size']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['size']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
					<a href="'.geturl('article','shuku','SYS=sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['i']['key'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'" ';
if($this->_tpl_vars['sel_size'] == $this->_tpl_vars['i']['key']){
echo 'class="on"';
}
echo '>'.$this->_tpl_vars['size'][$this->_tpl_vars['i']['key']]['text'].'</a>
				';
}
echo '
			</p>
		</div>
		<div class="box_term fix">
			<span class="t">写作进度：</span>
			<p class="tag f_gray3">
				';
if (empty($this->_tpl_vars['fullflag'])) $this->_tpl_vars['fullflag'] = array();
elseif (!is_array($this->_tpl_vars['fullflag'])) $this->_tpl_vars['fullflag'] = (array)$this->_tpl_vars['fullflag'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['fullflag']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['fullflag']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['fullflag']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['fullflag']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['fullflag']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
					<a href="'.geturl('article','shuku','SYS=sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['i']['key'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'" ';
if($this->_tpl_vars['sel_fullflag'] == $this->_tpl_vars['i']['key']){
echo 'class="on"';
}
echo '>'.$this->_tpl_vars['fullflag'][$this->_tpl_vars['i']['key']]['text'].'</a>
				';
}
echo '
			</p>
		</div>
		<div class="box_term fix">
			<span class="t">排序方式：</span>
			<p class="tag f_gray3">
				';
if (empty($this->_tpl_vars['operate'])) $this->_tpl_vars['operate'] = array();
elseif (!is_array($this->_tpl_vars['operate'])) $this->_tpl_vars['operate'] = (array)$this->_tpl_vars['operate'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['operate']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['operate']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['operate']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['operate']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['operate']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
					<a href="'.geturl('article','shuku','SYS=sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['i']['key'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'" ';
if($this->_tpl_vars['sel_operate'] == $this->_tpl_vars['i']['key']){
echo 'class="on"';
}
echo '>'.$this->_tpl_vars['operate'][$this->_tpl_vars['i']['key']]['text'].'</a>
				';
}
echo '
			</p>
		</div>
		<div class="box_term fix">
			<span class="t">作品状态：</span>
			<p class="tag f_gray3">
				';
if (empty($this->_tpl_vars['free'])) $this->_tpl_vars['free'] = array();
elseif (!is_array($this->_tpl_vars['free'])) $this->_tpl_vars['free'] = (array)$this->_tpl_vars['free'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['free']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['free']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['free']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['free']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['free']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
					<a href="'.geturl('article','shuku','SYS=sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['i']['key'].'&page=1').'" ';
if($this->_tpl_vars['sel_free'] == $this->_tpl_vars['i']['key']){
echo 'class="on"';
}
echo '>'.$this->_tpl_vars['free'][$this->_tpl_vars['i']['key']]['text'].'</a>
				';
}
echo '
			</p>
		</div>
	</div><!--term end-->
  <!--article2 begin-->
  <div class="article2 fl fix">
   <!--tabox begin-->
   <div class="tabox">
     <div class="t">
      <h2>每页显示50本小说 — 默认按更新时间排序</h2>
      <div class="show">
<!--       <select name="listnum" size="1">
	        <option value="50">每页显示50条</option>
	        <option value="100">每页显示100条</option>
       </select>-->
       <a href="javascript:;" class="'.$this->_tpl_vars['topview'].'" title="切换显示" id="switch"><span class="s_img';
if($this->_tpl_vars['topview'] == 'on_img'){
echo ' org';
}
echo '">图</span>|<span class="s_list';
if($this->_tpl_vars['topview'] == 'on_list'){
echo ' org';
}
echo '">文</span></a>
      </div>
     </div>
     <!--show_li begin-->
    <div class="show_li fix p5">
		';
if($this->_tpl_vars['topview'] == 'on_list'){
echo '
			<div class="tt">
				<span class="sort">类别</span>
				<span class="name">书名/章节</span>
				<span class="author">作者</span>
				<span class="date">更新时间</span>
			</div>
			<dl class="list_td2">
				';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
				<dd';
if($this->_tpl_vars['i']['order'] % 2 == 0){
echo ' class="fix dbg"';
}else{
echo ' class="fix"';
}
echo '>
					<span class="sort">
						<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_class'].'" target="_blank" class="f_gray9">['.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['shortcaption'].']</a>
					</span>
					<p class="name">
						<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="f_gray54 f14 mr5">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a>
						';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['permission'] > 3){
echo '<em class="q"></em>';
}
echo '
						<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_lastchapter'].'" target="_blank" class="f_gray9 ml5">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchapter'].'</a>
						';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchaptervip']==1){
echo '<em class="v"></em>';
}
echo '
					</p>
					<span class="author2">
						';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid']>0){
echo '
						<a href="'.geturl('system','userhub','method=zuozhe','SYS=uid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'').'" ajaxhover="true" uid="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'" class="f_gray6" target="_blank">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</a>
						';
}else{
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'];
}
echo '
					</span>
					<span class="date2">'.date('m-d H:i',$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastupdate']).'</span>
				</dd>
				';
}
echo '
			</dl>
		';
}else{
echo '
			<dl class="list_td6">
				';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
				<dd class="fix">
					<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" title="书名" class="img">
						<img src="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_image'].'" />
					</a>
					<div class="name">
						<h6>
							<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="f_blue4">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a>
						</h6>
						<div class="opt">
							<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleindex'].'" target="_blank" class="read">立即阅读</a>
							<a href="javascript:;" class="collect" onclick="GPage.addbook(\''.geturl('article','huodong','SYS=method=addBookCase&aid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'\');">收藏本书</a>
						</div>
					</div>
					<div class="info">
						<p class="it">
							<em>作者：</em>
							';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid']>0){
echo '
							<a href="'.geturl('system','userhub','method=userinfo','uid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'').'" ajaxhover="true" uid="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'" class="f_gray6" target="_blank">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</a>
							';
}else{
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'];
}
echo '
						</p>
						<p class="it">
							<em>分类：</em>
							<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_class'].'" target="_blank" class="f_gray2">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'</a>
						</p>
						<p class="it">
							<em>状态：</em>
							'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['fullflag_tag'].'
						</p>
						<p class="it">
							<em>点击：</em>
							30317
						</p>
						<p class="it">
							<em>更新：</em>
							'.date('Y-m-d',$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastupdate']).'
						</p>
						<p class="it">
							<em>字数：</em>
							'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['size_c'].'
						</p>
					</div>
					<div class="txt">
						<em>简介：</em>
						<p>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['intro'].'</p>
					</div>
				</dd>
				';
}
echo '
			</dl>
		';
}
echo '
		<div class="page3">'.$this->_tpl_vars['url_jumppage'].'</div>       
     </div><!--show_li end-->
   </div><!--tabox end-->
  </div><!--article2 end-->
  <!--sidebar begin-->
  <div class="sidebar fr">
    <!--box_side begin-->
    <div class="box_side mt10">
    <div class="t1"><h2>百万风云榜</h2></div>
   <div class="dwn2">
      <dl class="ldt p10">      
        '.jieqi_geturl('system', 'tags', array('id'=>128, 'name'=>'%5B%CA%D7%D2%B3%5D%D3%D2%C9%CF%BD%C7-%B0%D9%CD%F2%B7%E7%D4%C6%B0%F1')).'
      </dl>
    </div>
  </div><!--box_side end-->
    <!--box_side begin-->
    <div class="box_side mt10">
      <div class="t2"><h2>推荐榜</h2>
       <ul class="tabm21" id="tabs4">
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=weekvote&sortid=0&page=1').'" target="_blank">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvote&sortid=0&page=1').'" target="_blank">月</a></li>
		<li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvote&sortid=0&page=1').'" target="_blank">总</a></li>
       </ul>
      </div>
      <ul class="dwn1" id="tab_conbox4">
       <li class="fix">
        <dl class="ldt2 p10 f_black">
        '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_topli.html%7D%3E')).'
      </dl>
        </li>       
        <li class="fix" style="display:none;"><dl class="ldt2 p10 f_black">
		'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_topli.html%7D%3E')).' </dl>
        </li>      
        <li class="fix" style="display:none;">
		<dl class="ldt2 p10 f_black">
		'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_topli.html%7D%3E')).' </dl>
        </li>
      </ul>
    </div><!--box_side end-->
    <!--box_side begin-->
    <div class="box_side mt10">
      <div class="t2"><h2>点击榜</h2>
       <ul class="tabm21" id="tabs5">
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid=0&page=1').'" target="_blank">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=0&page=1').'" target="_blank">月</a></li>
		<li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvisit&sortid=0&page=1').'" target="_blank">总</a></li>
       </ul>
      </div>
      <ul class="dwn1" id="tab_conbox5">
       <li class="fix">
        <dl class="ldt2 p10 f_black">
        '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvisit%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_topli.html%7D%3E')).'
      </dl>
        </li>       
        <li class="fix" style="display:none;"><dl class="ldt2 p10 f_black">
		'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvisit%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_topli.html%7D%3E')).' </dl>
        </li>      
        <li class="fix" style="display:none;">
		<dl class="ldt2 p10 f_black">
		'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_topli.html%7D%3E')).' </dl>
        </li>
      </ul>
    </div><!--box_side end-->
    
  </div><!--sidebar end-->
</div><!--wrap end-->
<div class="ad"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/tb1.js"></script></div>

<script>
$("select[name=\'listnum\']").change(function(){
    $.cookie("listnum",$(this).val(),{expires:1, path: \'/\'});
	window.location.reload();
//	alert($(this).val());
});
$("#switch").click(function(){
//	var view;
//	if($(this).attr("class")==\'on_list\') view=\'on_img\';
//	else view=\'on_list\';
//	$.cookie("topview",view,{expires:1, path: \'/\'});
//	window.location.reload(true);
});
$("#switch span.s_img").click(function(){
	if(\''.$this->_tpl_vars['topview'].'\'==\'on_list\'){
		$.cookie("topview","on_img",{expires:1, path: \'/\'});
		window.location.reload(true);						   
	}
});
$("#switch span.s_list").click(function(){
	if(\''.$this->_tpl_vars['topview'].'\'==\'on_img\'){
		$.cookie("topview","on_list",{expires:1, path: \'/\'});
		window.location.reload(true);						   
	}
});
$(function(){
	if($.cookie("listnum")){
		var listnum = $.cookie("listnum");
		$("select[name=\'listnum\']").find("option[value=\'"+listnum+"\']").attr("selected",true);
	}
});


</script>
';
?>