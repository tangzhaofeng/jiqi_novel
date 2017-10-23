<?php
class JieqiForm {
var $_action;
var $_method;
var $_name;
var $_title;
var $_elements = array();
var $_extra;
var $_required = array();
function JieqiForm($title,$name,$action,$method="post"){
$this->_title = $title;
$this->_name = $name;
$this->_action = $action;
$this->_method = $method;
}
function getTitle(){
return $this->_title;
}
function getName(){
return $this->_name;
}
function getAction(){
if(strstr($this->_action,'?'))	return $this->_action.'&do=submit';
else return $this->_action.'?do=submit';
}
function getMethod(){
return $this->_method;
}
function addElement(&$formElement,$required=false){
$this->_elements[] =&$formElement;
if ($required) {
$this->_required[] =&$formElement;
}
}
function getElements(){
return $this->_elements;
}
function setExtra($extra){
$this->_extra = " ".$extra;
}
function getExtra(){
if (isset($this->_extra)) {
return $this->_extra;
}
}
function setRequired(&$formElement){
$this->_required[] =&$formElement;
}
function getRequired(){
return $this->_required;
}
function insertBreak($extra = NULL){
}
function render(){
}
function display(){
echo $this->render();
}
function assign(&$tpl){
$i = 0;
foreach ( $this->getElements() as $ele ) {
if ( !$ele->isHidden() ) {
$elements[$i]['caption'] = $ele->getCaption();
$elements[$i]['body'] = $ele->render();
$elements[$i]['hidden'] = false;
}else {
$elements[$i]['caption'] = '';
$elements[$i]['body'] = $ele->render();
$elements[$i]['hidden'] = true;
}
$i++;
}
$js = "
		<!-- Start Form Vaidation JavaScript //-->
		<script type='text/javascript'>
		<!--//
		function jieqiFormValidate_".$this->getName()."(){
		";
$required =&$this->getRequired();
$reqcount = count($required);
for ($i = 0;$i <$reqcount;$i++) {
$js .= "if ( window.document.".$this->getName().".".$required[$i]->getName().".value == \"\" ) {alert( \"".sprintf(LANG_PLEASE_ENTER,$required[$i]->getCaption())."\" );window.document.".$this->getName().".".$required[$i]->getName().".focus();return false;\n}
				";
}
$js .= "}
		//-->
		</script>
		<!-- End Form Vaidation JavaScript //-->
		";
$tpl->assign($this->getName(),array('title'=>$this->getTitle(),'name'=>$this->getName(),'action'=>$this->getAction(),'method'=>$this->getMethod(),'extra'=>'onsubmit="return jieqiFormValidate_'.$this->getName().'();"'.$this->getExtra(),'javascript'=>$js,'elements'=>$elements));
}
}
