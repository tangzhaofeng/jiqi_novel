<?php
class JieqiFormLabel extends JieqiFormElement {
var $_value;
function JieqiFormLabel($caption="",$value=""){
$this->setCaption($caption);
$this->_value = $value;
}
function getValue(){
return $this->_value;
}
function render(){
return $this->getValue();
}
}
