<?php
class JieqiFormElement {
var $_name;
var $_caption;
var $_hidden = false;
var $_extra;
var $_required = false;
var $_description = "";
var $_intro = "";
function JieqiFormElement(){
}
function setName($name) {
$this->_name = trim($name);
}
function getName($encode=true) {
if (false != $encode) {
return str_replace("&amp;","&",str_replace("'","&#039;",htmlspecialchars($this->_name)));
}
return $this->_name;
}
function setCaption($caption) {
$this->_caption = trim($caption);
}
function getCaption() {
return $this->_caption;
}
function setDescription($description) {
$this->_description = trim($description);
}
function getDescription() {
return $this->_description;
}
function setIntro($intro) {
$this->_intro = trim($intro);
}
function getIntro() {
return $this->_intro;
}
function setHidden() {
$this->_hidden = true;
}
function isHidden() {
return $this->_hidden;
}
function setExtra($extra){
$this->_extra = " ".trim($extra);
}
function getExtra(){
if (isset($this->_extra)) {
return $this->_extra;
}
}
function render(){
}
}
