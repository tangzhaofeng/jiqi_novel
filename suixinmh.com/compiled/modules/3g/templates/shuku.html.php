<?php
echo '<!DOCTYPE html>
<html>
    <head>
        <meta charset="'.$this->_tpl_vars['jieqi_charset'].'">
        <title>书库</title>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
        <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
        <style>
            .screening li {
                line-height: 25px;
            }
            
            .screening li a.current {
              
                color: #1fb3b6;
            }
            
            .screening li span {
                font-size: 15px;
            }
            
            .screening li a {
                padding: 0 5px;
                float: left;
                font-size: 15px;
            }
            .txt{
                padding:2px 0;
            }
        </style>
    </head>

    <body>
        <!--热门推荐-->
        <div class="mt10  bgcfff">
            <div class="mlf10 ptb10 f16" style="color: #1fb3b6;border-bottom: 1px solid #dad9d9;">书库</div>
            <ul class="screening clearfix plf10">
            <li class="clearfix ptb10 bb bcddd">
                    <span class="fl">频道：</span>
                      <a href="'.geturl('3g','shuku','SYS=siteid=0&sort=0&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'"';
if($this->_tpl_vars['siteid'] == "0" || !isset($this->_tpl_vars['_REQUEST']['siteid'])==1){
echo ' class="current"';
}
echo '>男频</a>
                <a href="'.geturl('3g','shuku','SYS=siteid=100&sort=0&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'"';
if($this->_tpl_vars['siteid'] == "100"){
echo ' class="current"';
}
echo '>女频</a>
                </li>
                <li class="clearfix ptb10 bb bcddd">
                    <span class="fl">分类：</span>
                    <a  href="'.geturl('3g','shuku','SYS=siteid='.$this->_tpl_vars['siteid'].'&sort=0&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1').'" ';
if(!isset($this->_tpl_vars['_REQUEST']['sort'])==1 || $this->_tpl_vars['sel_sort']==0){
echo 'class="current"';
}
echo '>不限</a>
                    ';
if (empty($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort'])) $this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort'] = array();
elseif (!is_array($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort'])) $this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort'] = (array)$this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['channel'][$this->_tpl_vars['siteid']]['sort']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
                    <a href="'.geturl('3g','shuku','SYS=siteid='.$this->_tpl_vars['siteid'].'&sort='.$this->_tpl_vars['i']['key'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1','').'" ';
if($this->_tpl_vars['sel_sort'] == $this->_tpl_vars['i']['key']){
echo 'class="current"';
}
echo ' >'.$this->_tpl_vars['sort'][$this->_tpl_vars['i']['key']]['shortcaption'].'</a>
                ';
}
echo '
                </li>
                <li class="clearfix ptb10 bb bcddd">
                    <span class="fl">字数：</span>
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
                    <a href="'.geturl('3g','shuku','SYS=siteid='.$this->_tpl_vars['siteid'].'&sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['i']['key'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1','').'" ';
if($this->_tpl_vars['sel_size'] == $this->_tpl_vars['i']['key']){
echo 'class="current"';
}
echo '>'.$this->_tpl_vars['size'][$this->_tpl_vars['i']['key']]['text'].'</a>
                ';
}
echo '
                </li>
                <li class="clearfix ptb10 bb bcddd">
                    <span class="fl">排序：</span>
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
                    <a href="'.geturl('3g','shuku','SYS=siteid='.$this->_tpl_vars['siteid'].'&sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['i']['key'].'&free='.$this->_tpl_vars['sel_free'].'&page=1','').'" ';
if($this->_tpl_vars['sel_operate'] == $this->_tpl_vars['i']['key']){
echo 'class="current"';
}
echo '>'.$this->_tpl_vars['operate'][$this->_tpl_vars['i']['key']]['text'].'</a>
                ';
}
echo '
                </li>
                <li class="clearfix ptb10 bb bcddd">
                    <span class="fl">进度：</span>
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
                    <a href="'.geturl('3g','shuku','SYS=siteid='.$this->_tpl_vars['siteid'].'&sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['i']['key'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['sel_free'].'&page=1','').'" ';
if($this->_tpl_vars['sel_fullflag'] == $this->_tpl_vars['i']['key']){
echo 'class="current"';
}
echo '>'.$this->_tpl_vars['fullflag'][$this->_tpl_vars['i']['key']]['text'].'</a>
                   ';
}
echo '
                </li>
                <li class="clearfix ptb10 bb bcddd">
                    <span class="fl">类型：</span>
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
                    <a href="'.geturl('3g','shuku','SYS=siteid='.$this->_tpl_vars['siteid'].'&sort='.$this->_tpl_vars['sel_sort'].'&size='.$this->_tpl_vars['sel_size'].'&fullflag='.$this->_tpl_vars['sel_fullflag'].'&operate='.$this->_tpl_vars['sel_operate'].'&free='.$this->_tpl_vars['i']['key'].'&page=1','').'" ';
if($this->_tpl_vars['sel_free'] == $this->_tpl_vars['i']['key']){
echo 'class="current"';
}
echo '>'.$this->_tpl_vars['free'][$this->_tpl_vars['i']['key']]['text'].'</a>
                ';
}
echo '
                </li>
            </ul>
        </div>
        <!--都市婚姻-->
        <div class="mt10  bgcfff">
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
            <div class="p10 pr pl90 bb bcddd lastbb" style="min-height: 90px;">
                <a href="'.geturl('3g','articleinfo','SYS=aid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'"><img class="w80 h110 pa left10" src="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_image'].'" alt="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'"></a>
                <div class="ml10">
                    <h2 class="f16 fwn"><a href="'.geturl('3g','articleinfo','SYS=aid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'" class="cOrange1">';
echo str_replace($this->_tpl_vars['searchkey'],'<span>'.$this->_tpl_vars['searchkey'].'</span>' ,$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename']);  
echo '</a></h2>
                    <h2 class="f14 c333 fwn txt">分类：'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'</h2>
                    <h2 class="f14 c333 fwn txt">字数：'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['size_w'].'万</h2>

                    <div class="lh25 c888 f14 overMore">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['intro'].'</div>
                </div>
                <div class="clearfix"></div>
            </div>
             ';
}
echo '
        </div>
         <div class="skip">
            <form method="post" action="">
                <!--page-->
                <div class="ptb10 tc">
                   <a  class="dib f12" href="'.$this->_tpl_vars['prevpath'].'">上一页</a>
                    <input type="number" name="page" placeholder="1" min="1" class="w30 tc b bcddd br0" style="padding: 6px;"  value="'.$this->_tpl_vars['page'].'">
                    <input type="submit" value="跳转" class="w45 tc p5 b bcddd br0 c666">
                    <span class="plf5 dib">'.$this->_tpl_vars['page'].'/'.$this->_tpl_vars['maxpage'].'</span>
                    <a class="dib f12" href="'.$this->_tpl_vars['nextpath'].'">下一页</a>
                </div>
            </form>
        </div>
    </body>
    <script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/jquery.min.js"></script>

</html>';
?>