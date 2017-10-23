<?php
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
	<dd class="fix focus im"';
if($this->_tpl_vars['i']['order']!=1){
echo ' style="display:none;"';
}
echo '>
		<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="img"><img src="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_image'].'" alt="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'"/></a>
		<div class="info">
			<p class="name2"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'" class="f_black b f13">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></p>
			<p class="author">作者：';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'] > 0){
echo '<a href="'.geturl('system','userhub','method=zuozhe','uid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'').'" target="_blank" class="f_gray6" uid="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['authorid'].'" ajaxhover="true">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</a>';
}else{
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'];
}
echo '</p>
			<p class="author">类型：<a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_class'].'" target="_blank" class="f_gray6" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'</a></p>          
			<p class="lnk"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="f_blue">[阅读]</a><a href="javascript:void(0)" class="f_blue" onclick="GPage.addbook(\''.geturl('article','huodong','SYS=method=addBookCase&aid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'].'').'\');">[收藏]</a></p>
		</div>
	</dd>
	<dd class="tt"';
if($this->_tpl_vars['i']['order']==1){
echo ' style="display:none;"';
}
echo '><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_class'].'" class="sort" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'">['.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['shortcaption'].']</a><span class="name"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'" class="f_black">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></span></dd>
';
}

?>