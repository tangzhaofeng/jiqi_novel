<?php
class JieqiFormText extends JieqiFormElement {
var $_size;
var $_maxlength;
var $_value;
function JieqiFormText($caption,$name,$size,$maxlength,$value=""){
$this->setCaption($caption);
$this->setName($name);
$this->_size = intval($size);
$this->_maxlength = intval($maxlength);
$this->_value = $value;
}
function getSize(){
return $this->_size;
}
function getMaxlength(){
return $this->_maxlength;
}
function getValue(){
return $this->_value;
}
function render(){
return "<input type=\"text\" class=\"text\" name=\"".$this->getName()."\" id=\"".$this->getName()."\" size=\"".$this->getSize()."\" maxlength=\"".$this->getMaxlength()."\" value=\"".$this->getValue()."\"".$this->getExtra()." />";
}
}
