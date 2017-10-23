<?php
if($this->_tpl_vars['jieqi_userid'] == 0){
echo '<!-- Î´µÇÂ¼ -->';
$this->_tpl_vars['title']=$this->_tpl_vars['chapter']['title'];$this->_tpl_vars['articlename']=$this->_tpl_vars['article']['articlename']; 
echo '
	';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/3g/templates/login.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
}elseif($this->_tpl_vars['chapter']['content']!=''){
echo '<!-- ÒÑ¾­¹ºÂò -->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/3g/templates/reader.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
}else{
echo '<!-- µÇÂ½Î´¹ºÂò -->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/3g/templates/buychapter.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
}

?>