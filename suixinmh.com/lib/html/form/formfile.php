<?php
class JieqiFormFile extends JieqiFormElement {
var $_size;
function JieqiFormFile($caption,$name,$size){
$this->setCaption($caption);
$this->setName($name);
$this->_size = intval($size);;
}
function getSize(){
return $this->_size;
}
function render(){
return "<input type=\"file\" class=\"text\" size=\"".$this->getSize()."\" name=\"".$this->getName()."\" id=\"".$this->getName()."\"".$this->getExtra()." />";
}
}
