<?php
class JieqiFormHidden extends JieqiFormElement {
var $_value;
function JieqiFormHidden($name,$value){
$this->setName($name);
$this->setHidden();
$this->_value = $value;
$this->setCaption("");
}
function getValue(){
return $this->_value;
}
function render(){
return "<input type=\"hidden\" name=\"".$this->getName()."\" id=\"".$this->getName()."\" value=\"".$this->getValue()."\" />";
}
}
