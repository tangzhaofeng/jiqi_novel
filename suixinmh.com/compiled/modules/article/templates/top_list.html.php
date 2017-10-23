<?php
echo '<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/rank.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'/js/jquery.cookie.js"></script> 
<!--wrap begin-->
<div class="wrap fix">
<div class="ad2"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/bd2.js"></script></div>
  <!--sidebar2 begin-->
  <div class="sidebar2 fl">
   <!--box_side2 begin-->
    <div class="box_side2">
      <div class="t"><h3>小说排行榜</h3></div>
      <ul class="list_b f_gray3">
       <li><a ';
if($this->_tpl_vars['midname'] == '月票'){
echo 'class="on"';
}
echo ' href="'.geturl('article','top','method=toplist','SYS=type=monthvipvote&sortid=0&page=1').'">月票榜</a></li>
       <li><a ';
if($this->_tpl_vars['midname'] == '点击'){
echo 'class="on"';
}
echo ' href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=0&page=1').'">点击榜</a></li>
       <li><a ';
if($this->_tpl_vars['midname'] == '推荐'){
echo 'class="on"';
}
echo ' href="'.geturl('article','top','method=toplist','SYS=type=monthvote&sortid=0&page=1').'">推荐榜</a></li>
       <li><a ';
if($this->_tpl_vars['midname'] == '收藏'){
echo 'class="on"';
}
echo ' href="'.geturl('article','top','method=toplist','SYS=type=totalgoodnum&sortid=0&page=1').'">收藏榜</a></li>
      </ul>
    </div><!--box_side2 end-->
   <!--box_side2 begin-->
    <div class="box_side2">
      <div class="t"><h3>分类榜</h3></div>
      <ul class="list_b f_gray3">
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
	echo '<li><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid='.$this->_tpl_vars['i']['key'].'&page=1').'"';
if($this->_tpl_vars['sortid']==$this->_tpl_vars['i']['key']){
echo ' class="on"';
}
echo '>'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['shortcaption'].'小说排行榜</a></li>';
}
echo '
       <!--'.jieqi_geturl('system', 'tags', array('id'=>1, 'name'=>'%C8%AB%D5%BE%B7%D6%C0%E0%B5%BC%BA%BD%3C%7B%7D%3E%3C%7Bv1%2Fblock_sorttop.html%7D%3E')).'-->
      </ul>
    </div><!--box_side2 end-->
<!--box_side2 begin-->
    <div class="box_side2">
      <div class="t"><h3>特色榜</h3></div>
      <ul class="list_b f_gray3">
       <li><a ';
if($this->_tpl_vars['midname'] == '销售'){
echo 'class="on"';
}
echo ' href="'.geturl('article','top','method=toplist','SYS=type=daysale&sortid=0&page=1').'">24小时销售榜</a></li>
       <li><a ';
if($this->_tpl_vars['midname'] == '日更新字数'){
echo 'class="on"';
}
echo ' href="'.geturl('article','top','method=toplist','SYS=type=daysize&sortid=0&page=1').'">24小时更新榜</a></li>
      </ul>
    </div><!--box_side2 end-->
  </div><!--sidebar2 end-->
  <!--article3 begin-->
  <div class="article3 fr">
   <!--tabox begin-->
   <div class="tabox pl20">
     <div class="t">
      <h2>小说'.$this->_tpl_vars['midname'].'榜</h2>
      ';
if($this->_tpl_vars['havastat']>0){
echo '<ul class="tabs6">
       <li';
if($this->_tpl_vars['order']=='day'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('article','top','method=toplist','SYS=type=day'.$this->_tpl_vars['mid'].'&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">日榜</a></li>
       <li';
if($this->_tpl_vars['order']=='week'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('article','top','method=toplist','SYS=type=week'.$this->_tpl_vars['mid'].'&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">周榜</a></li>
       <li';
if($this->_tpl_vars['order']=='month'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('article','top','method=toplist','SYS=type=month'.$this->_tpl_vars['mid'].'&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">月榜</a></li>
       <li';
if($this->_tpl_vars['order']=='total'){
echo ' class="thistab"';
}
echo '><a href="'.geturl('article','top','method=toplist','SYS=type=total'.$this->_tpl_vars['mid'].'&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">总榜</a></li>
      </ul>';
}
echo '
      <div class="show">
       <select name="listnum" size="1">
        <option value="30">每页显示30条</option>
        <option value="20">每页显示20条</option>
       </select>
       <a href="javascript:;" class="';
if($this->_tpl_vars['topview']=='on_list'){
echo 'on_list';
}else{
echo 'on_img';
}
echo '" title="图文显示" id="switch"><span class="s_img';
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
     <!--box begin-->
     <div class="box">
       <div class="tag_sort">
        <a href="'.geturl('article','top','method=toplist','SYS=type='.$this->_tpl_vars['type'].'&sortid=0&page=1').'"';
if($this->_tpl_vars['sortid']<1){
echo ' class="on_sort"';
}
echo '>全部</a>
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
	echo '<a href="'.geturl('article','top','method=toplist','SYS=type='.$this->_tpl_vars['type'].'&sortid='.$this->_tpl_vars['i']['key'].'&page=1').'"';
if($this->_tpl_vars['sortid']==$this->_tpl_vars['i']['key']){
echo ' class="on_sort"';
}
echo '>'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['shortcaption'].'</a>';
}
echo '
       </div>
       <ul id="tab_conbox" class="f0">
        <li>
	';
if($this->_tpl_vars['topview']=='on_img'){
echo '
     <!--show_img begin-->
     <div class="show_img fix p5">
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
			<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" title="书名" class="img"><img src="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_image'].'" onerror="this.src=\'/modules/article/images/nophoto.jpg\'" /></a>
			<div class="name"><h6><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="f_blue4">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></h6>
			  <div class="opt"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleindex'].'" class="read">立即阅读</a><a href="javascript:;" class="collect" onclick="GPage.addbook(\''.geturl('article','huodong','SYS=method=addBookCase&aid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'\');">收藏本书</a></div>
			</div>
			<div class="info">
			 <p class="it"><em>作者：</em>';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid']>0){
echo '<a href="'.geturl('system','userhub','method=zuozhe','SYS=uid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'').'" ajaxhover="true" uid="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'" class="f_gray6" target="_blank">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</a>';
}else{
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'];
}
echo '</p>
			 <p class="it"><em>分类：</em><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_class'].'" class="f_gray2">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'</a></p>
			 <p class="it"><em>状态：</em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['fullflag_tag'].'</p>
			 <p class="it"><em>点击：</em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['total'].'</p>
			 <p class="it"><em>更新：</em>'.date('Y-m-d',$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastupdate']).'</p>
			 <p class="it"><em>字数：</em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['size_c'].'</p>
			</div>
			<div class="txt"><em>简介：</em><p>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['intro'].'</p></div>
		   </dd>
		   ';
}
echo '
		  </dl>
		  <div class="page3">'.$this->_tpl_vars['url_jumppage'].'</div>
         </div><!--show_img end-->
		 ';
}else if(($this->_tpl_vars['topview']=='on_list'||$this->_tpl_vars['topview']=='')){
echo '
        <!--show_li begin-->
        <div class="show_li">
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
         <dd>
           <em class="numb">'.$this->_tpl_vars['i']['order'].'</em>
           <span class="sort"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_class'].'" target="_blank" class="f_gray5">['.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['shortcaption'].']</a></span>
           <p class="name"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="f_blue f14">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a>';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['permission'] > 3){
echo '<em class="q"></em>';
}
echo '<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_lastchapter'].'" target="_blank" class="f_gray3 pl5">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchapter'].'</a>';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchaptervip']==1){
echo '<em class="v"></em>';
}
echo '</p>
           <span class="author">';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid']>0){
echo '<a href="'.geturl('system','userhub','method=zuozhe','SYS=uid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'').'" ajaxhover="true" uid="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'" class="f_gray6" target="_blank">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</a>';
}else{
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'];
}
echo '</span>
           <span class="date">'.date('m-d H:i',$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastupdate']).'</span>
           </dd>
         ';
}
echo '
       </dl>
	  <div class="page3">'.$this->_tpl_vars['url_jumppage'].'</div>
     </div><!--show_li end-->	  ';
}
echo '

         </li>       
        </ul>
     </div><!--box end-->
   </div><!--tabox end-->
  </div><!--article3 end-->
</div><!--wrap end-->
<div class="ad"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/tb1.js"></script></div>
<script>
$("select[name=\'listnum\']").change(function(){
    $.cookie("listnum",$(this).val(),{expires:1, path: \'/\'});
	window.location.reload(true);
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
</script>';
?>