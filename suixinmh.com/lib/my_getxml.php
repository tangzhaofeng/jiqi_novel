<?php
class MyGetxml extends XmlParser{
var $xmlobj;
function MyGetxml(){
$this->xmlobj = new XmlParser();
}
function getData($xmlurl,$param=array(),$mode='GET'){
if(!$xmlurl) return false;
if(!$this->xmlobj->parseFile($xmlurl,$param,$mode)) return false;
return $this->xmlobj->getTree();
}
function array_iconv($in_charset,$out_charset,$arr){
return eval('return '.iconv($in_charset,$out_charset,var_export($arr,true).';'));
}
}
class XmlParser {
var $charset = 'GBK//IGNORE';
var $source;
var $parser;
var $srcenc;
var $dstenc;
var $_struct = array();
function XmlParser($srcenc = null,$dstenc = null) {
$this->srcenc = $srcenc;
$this->dstenc = $dstenc;
$this->parser = null;
$this->_struct = array();
}
function free() {
if (isset($this->parser) &&is_resource($this->parser)) {
xml_parser_free($this->parser);
unset($this->parser);
}
}
function parseFile($file,$params=array(),$mode='GET') {
$this->source = $this->request($file,$mode,$params) or die("Can't open file $file for reading!");
if(!$this->source) return false;
$this->parseString($this->source);
return true;
}
function arrayRecursive(&$array,$function,$apply_to_keys_also = false) 
{
static $recursive_counter = 0;
if (++$recursive_counter >1000) {
die('possible deep recursion attack');
}
foreach ($array as $key =>$value) {
if (is_array($value)) {
arrayRecursive($array[$key],$function,$apply_to_keys_also);
}else {
$array[$key] = iconv('gbk','utf-8',$value);
}
if ($apply_to_keys_also &&is_string($key)) {
$new_key = $function($key);
if ($new_key != $key) {
$array[$new_key] = $array[$key];
unset($array[$key]);
}
}
}
$recursive_counter--;
}
function request($url,$mode,$params=array()){
if($params) $this->arrayRecursive($params,'urlencode',true);
$curlHandle = curl_init();
curl_setopt($curlHandle,CURLOPT_TIMEOUT,30);
curl_setopt($curlHandle,CURLOPT_RETURNTRANSFER,true);
if($mode=='POST'){
curl_setopt($curlHandle,CURLOPT_HTTPHEADER,array('Expect:'));
curl_setopt($curlHandle,CURLOPT_POST,true);
curl_setopt($curlHandle,CURLOPT_POSTFIELDS,http_build_query($params));
}else{
if($params) $url .= (strpos($url,'?') === false ?'?': '&') .http_build_query($params);
}
curl_setopt($curlHandle,CURLOPT_URL,$url);
if(substr($url,0,5) == 'https'){
curl_setopt($curlHandle,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($curlHandle,CURLOPT_SSL_VERIFYHOST,false);
}
$result = curl_exec($curlHandle);
curl_close($curlHandle);
return $result;
}
function parseString($data) {
if ($this->srcenc === null) {
$this->parser = @xml_parser_create() or die('Unable to create XML parser resource.');
}else {
$this->parser = @xml_parser_create($this->srcenc) or die('Unable to create XML parser resource with '.$this->srcenc .' encoding.');
}
if ($this->dstenc !== null) {
@xml_parser_set_option($this->parser,XML_OPTION_TARGET_ENCODING,$this->dstenc) or die('Invalid target encoding');
}
xml_parser_set_option($this->parser,XML_OPTION_CASE_FOLDING,0);
xml_parser_set_option($this->parser,XML_OPTION_SKIP_WHITE,1);
if (!xml_parse_into_struct($this->parser,$data,$this->_struct)) {
printf("XML error: %s at line %d",
xml_error_string(xml_get_error_code($this->parser)),
xml_get_current_line_number($this->parser) 
);
$this->free();
exit();
}
$this->_count = count($this->_struct);
$this->free();
}
function getTree() {
$i = 0;
$tree = array();
$tree = $this->addNode( 
$tree,
$this->_struct[$i]['tag'],
(isset($this->_struct[$i]['value'])) ?iconv('utf-8',$this->charset,$this->_struct[$i]['value']) : '',
(isset($this->_struct[$i]['attributes'])) ?$this->_struct[$i]['attributes'] : '',
$this->getChild($i) 
);
unset($this->_struct);
return ($tree);
}
function getChild(&$i) {
$children = array();
while (++$i <$this->_count) {
$tagname = $this->_struct[$i]['tag'];
$value = isset($this->_struct[$i]['value']) ?iconv('utf-8',$this->charset,$this->_struct[$i]['value']) : '';
$attributes = isset($this->_struct[$i]['attributes']) ?$this->_struct[$i]['attributes'] : '';
switch ($this->_struct[$i]['type']) {
case 'open': 
$child = $this->getChild($i);
$children = $this->addNode($children,$tagname,$value,$attributes,$child);
break;
case 'complete': 
$children = $this->addNode($children,$tagname,$value,$attributes);
break;
case 'cdata': 
$children['value'] .= $value;
break;
case 'close': 
return $children;
break;
}
}
}
function addNode($target,$key,$value = '',$attributes = '',$child = '') {
if (!isset($target[$key]['value']) &&!isset($target[$key][0])) {
if ($child != '') {
$target[$key] = $child;
}
if ($attributes != '') {
foreach ($attributes as $k =>$v) {
$target[$key][$k] = $v;
}
}
$target[$key]['value'] = $value;
}else {
if (!isset($target[$key][0])) {
$oldvalue = $target[$key];
$target[$key] = array();
$target[$key][0] = $oldvalue;
$index = 1;
}else {
$index = count($target[$key]);
}
if ($child != '') {
$target[$key][$index] = $child;
}
if ($attributes != '') {
foreach ($attributes as $k =>$v) {
$target[$key][$index][$k] = $v;
}
}
$target[$key][$index]['value'] = $value;
}
return $target;
}
function doIconv($str,$type = 'input'){
if(!$str) return $str;
if($type == 'input'){
$find = array('¡ª');
$value = array('&#8212;');
}else{
$find = array('&#8212;');
$value = array('¡ª');
}
return str_replace($find,$value,$str);
}
}
