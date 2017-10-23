<?php
class JieqiFormElementTray extends JieqiFormElement {
var $_elements = array();
var $_delimeter;
function JieqiFormElementTray($caption,$delimeter="&nbsp;"){
$this->setCaption($caption);
$this->_delimeter = $delimeter;
}
function addElement($element){
$this->_elements[] = $element;
}
function getElements(){
return $this->_elements;
}
function getDelimeter(){
return $this->_delimeter;
}
function render(){
$count = 0;
$ret = "";
foreach ( $this->getElements() as $ele ) {
if ($count >0) {
$ret .= $this->getDelimeter();
}
$ret .= $ele->render()."\n";
if (!$ele->isHidden()) {
$count++;
}
}
return $ret;
}
}
