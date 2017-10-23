<?php
class JieqiFormButton extends JieqiFormElement {
var $_value;
var $_type;
function JieqiFormButton($caption,$name,$value="",$type="button"){
$this->setCaption($caption);
$this->setName($name);
$this->_type = $type;
$this->_value = $value;
}
function getValue(){
return $this->_value;
}
function getType(){
return $this->_type;
}
function render(){
return "<input type=\"".$this->getType()."\" class=\"button\" name=\"".$this->getName()."\"  id=\"".$this->getName()."\" value=\"".$this->getValue()."\"".$this->getExtra()." />";
}
}
