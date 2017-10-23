<?php
define('XML_TYPE_NODE',1);
define('XML_TYPE_TEXT',3);
define('XML_TYPE_CDATA',4);
class XMLText {
var $nodeValue;
var $nodeType;
var $entities = array ( "&"=>"&amp;","<"=>"&lt;",">"=>"&gt;",
"'"=>"&apos;",'"'=>"&quot;");
function XMLText() {
$this->nodeValue = NULL;
$this->nodeType = XML_TYPE_TEXT;
}
}
class XMLNode extends XMLText {
var $attributes;
var $childNodes;
var $firstChild;
var $lastChild;
var $previousSibling;
var $nextSibling;
var $nodeName;
var $parentNode;
var $nodeType;
function XMLNode() {
$this->attributes = NULL;
$this->childNodes = NULL;
$this->firstChild = NULL;
$this->lastChild = NULL;
$this->previousSibling = NULL;
$this->nextSibling = NULL;
$this->nodeName = NULL;
$this->parentNode = NULL;
$this->nodeType = XML_TYPE_NODE;
}
function _xml_get_children($vals,&$i) {
$children = array();
if (isset($vals[$i]['value'])) {
$tmp = new XMLText();
$tmp->nodeValue = $vals[$i]['value'];
$tmp->nodeType = XML_TYPE_CDATA;
$children[] = $tmp;
}
$lastelm = '';
$nChildren = count($vals);
while (++$i <$nChildren) {
switch ($vals[$i]['type']) {
case 'cdata':
if ($lastelm != 'cdata') {
$tmp = new XMLText();
$tmp->nodeValue = $vals[$i]['value'];
$tmp->nodeType = XML_TYPE_CDATA;
$children[] = $tmp;
}else {
$children[count($children)-1]->nodeValue .= $vals[$i]['value'];
}
break;
case 'complete':
$tmp = new XMLNode();
$tmp->nodeName = $vals[$i]['tag'];
$tmp->attributes = isset($vals[$i]['attributes'])?$vals[$i]['attributes']:NULL;
if (isset($vals[$i]['value'])) {
$tmp->appendChild(XMLNode::createTextNode($vals[$i]['value']));
}
$tmp->parentNode = $this;
$children[] = $tmp;
break;
case 'open':
$tmp = new XMLNode();
$tmp->nodeName = $vals[$i]['tag'];
$tmp->attributes = isset($vals[$i]['attributes'])?$vals[$i]['attributes']:NULL;
$tmp->parentNode = $this;
$tmp->childNodes = $tmp->_xml_get_children($vals,$i);
$children[] = $tmp;
break;
case 'close':
$nThisChildren = count($children);
if ($nThisChildren >1) {
for ($j = $nThisChildren-2;$j >= 0;$j--)
$children[$j]->nextSibling =&$children[$j+1];
for ($j = 1;$j <$nThisChildren;$j++)
$children[$j]->previousSibling =&$children[$j-1];
}
$this->firstChild =&$children[0];
$this->lastChild =&$children[($nThisChildren-1) %$nThisChildren];
return $children;
break;
}
$lastelm = $vals[$i]['type'];
}
}
function appendChild(&$child) {
$child->parentNode =&$this;
$this->childNodes[] =&$child;
if ($child->nodeType == XML_TYPE_NODE) {
$child->previousSibling =&$this->lastChild;
}
if ($this->nodeType == XML_TYPE_NODE) {
if (!is_null($this->lastChild)) {
$this->lastChild->nextSibling =&$child;
}
$this->firstChild =&$this->childNodes[0];
$this->lastChild =&$child;
}
}
function createElement($name) {
$tmp = new XMLNode();
$tmp->nodeName = $name;
return $tmp;
}
function createTextNode($value) {
$tmp = new XMLText();
$tmp->nodeValue = trim($value);
return $tmp;
}
function hasChildNodes() {
return !is_null($this->childNodes);
}
function insertBefore(&$child,$refChild = NULL) {
}
function removeChild() {
}
function toString() {
$tagOpen = "<";
$tagClose = ">";
$tagBreak = "\n";
$retVal = "";
if (is_null($this->parentNode)) {
if(!empty($this->xmlDecl)) $retVal .= $this->xmlDecl.$tagBreak;
if(!empty($this->docTypeDecl)) $retVal .= $this->docTypeDecl.$tagBreak;
}
$sAttr = "";
if (isset($this->attributes)) {
foreach ($this->attributes as $key=>$val)
$sAttr .= " $key=\"".strtr($val,$this->entities)."\"";
}
if (isset($this->nodeName)) {
if ($this->hasChildNodes()) {
$retVal .= $tagOpen.$this->nodeName.$sAttr.$tagClose;
if($this->firstChild->nodeType != XML_TYPE_TEXT &&$this->firstChild->nodeType != XML_TYPE_CDATA) $retVal .= $tagBreak;
}elseif (isset($this->firstChild->nodeValue)) {
$retVal .= $tagOpen.$this->nodeName.$sAttr.$tagClose.strtr($this->firstChild->nodeValue,$this->entities).$tagOpen."/".$this->nodeName.$tagClose.$tagBreak;
}else {
$retVal .= $tagOpen.$this->nodeName.$sAttr." /".$tagClose.$tagBreak;
}
}
if ($this->hasChildNodes()) {
foreach ($this->childNodes as $child) {
switch ($child->nodeType) {
case XML_TYPE_NODE: 
default:
$retVal .= $child->toString();
break;
case XML_TYPE_TEXT: 
$retVal .= strtr($child->nodeValue,$this->entities);
break;
case XML_TYPE_CDATA: 
$retVal .= "<![CDATA[".$child->nodeValue."]]>";
break;
}
}
}
if ($this->hasChildNodes() &&isset($this->nodeName)) {
$retVal .= $tagOpen."/".$this->nodeName.$tagClose.$tagBreak;
}
return $retVal;
}
}
class XML extends XMLNode {
var $status;
var $error;
var $version;
var $encoding;
var $contentType;
var $docTypeDecl;
var $xmlDecl;
function XML($url = '') {
parent::XMLNode();
$this->status = 0;
$this->error = '';
$this->version = '1.0';
$this->encoding = 'ISO-8859-1';
$this->contentType = 'text/xml';
$this->docTypeDecl = '';
$this->xmlDecl = '';
$this->load($url);
}
function load($url) {
if (empty($url)) return false;
$this->parseXML(@file_get_contents($url));
}
function parseXML($source) {
if (preg_match("/<?xml\ (.*?)\?>/i",$source,$matches)) {
$this->xmlDecl = "<?xml ".$matches[1]."?>";
if (preg_match("/version=\"(.*?)\"/i",$matches[1],$versionInfo)) {
$this->version = $versionInfo[1];
}
if (preg_match("/encoding=\"(.*?)\"/i",$matches[1],$encodingInfo)) {
$this->encoding = $encodingInfo[1];
}
}
if (preg_match("/<!doctype\ (.*?)>/i",$source,$matches)) {
$this->docTypeDecl = "<!DOCTYPE ".$matches[1].">";
}
$source = preg_replace("/>\s+</i","><",$source);
$parser = xml_parser_create('ISO-8859-1');
xml_parser_set_option($parser,XML_OPTION_CASE_FOLDING,0);
$ret=xml_parse_into_struct($parser,$source,$vals);
xml_parser_free($parser);
if (!empty($vals)) {
$root = XMLNode::createElement($vals[0]['tag']);
$root->attributes = isset($vals[0]['attributes'])?$vals[0]['attributes']:NULL;
$root->childNodes = $root->_xml_get_children($vals,$i = 0);
$this->appendChild($root);
}
return $ret;
}
}
