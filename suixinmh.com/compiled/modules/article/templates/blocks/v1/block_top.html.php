<?php
echo '  <dl class="list_t4d f_black">
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
    ';
if($this->_tpl_vars['i']['order']==1){
echo '
	<dt>
	 <div class="first">'.$this->_tpl_vars['i']['order'].'</div>
	 <a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" class="img"><img src="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_image'].'" width="84" height="112" /></a>
	 <div class="intro">
	  <span class="name"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'" class="f_blue3">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></span>
	  <span class="author">作者：<em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</em></span>
	  <p class="txt" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['intro'].'">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['intro'].'</p>
	 </div>
	</dt>
	';
}else{
echo '
	<dd><span class="numb">'.$this->_tpl_vars['i']['order'].'</span><p><span class="name"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'">'.truncate($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'],'18').'</a></span><span class="click">';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['visitnum'] < 10000){
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['visitnum'];
}else{
echo $this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['visitnum_w'].'万';
}
echo '</span></p></dd>
	';
}
echo '
	';
}
echo '
  </dl>
 <div class="renew f_blue"><span class="date">更新时间:';
echo date('Y-m-d H:i:s',time()); 
echo '</span><a href="'.$this->_tpl_vars['url_more'].'">更多&gt;&gt;</a></div>  ';
?>