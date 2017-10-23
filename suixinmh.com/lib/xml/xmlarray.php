<?php
class XMLArray {
var $text;
var $arrays,$keys,$node_flag,$depth,$xml_parser;
var $encoding = 'ISO-8859-1';
var $entities = array ( "&"=>"&amp;","<"=>"&lt;",">"=>"&gt;",
"'"=>"&apos;",'"'=>"&quot;");
function array2xml($array) {
$this->text="<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n<array>\n";
$this->text.= $this->array_transform($array);
$this->text .="</array>";
return $this->text;
}
function array_transform($array){
foreach($array as $key =>$value){
if(!is_array($value)){
if(preg_match('/(&|<|>)/is',$value)) $value = '<![CDATA['.$value.']]>';
$this->text .= "<key name=\"".$key."\">".$value."</key>\n";
}else {
$this->text.="<key name=\"".$key."\">\n";
$this->array_transform($value);
$this->text.="</key>\n";
}
}
return $array_text;
}
function array_transform1($array){
foreach($array as $key =>$value){
if(!is_array($value)){
if(preg_match('/(&|<|>)/is',$value)) $value = '<![CDATA['.$value.']]>';
$this->text .= "<$key>$value</$key>\n";
}else {
$this->text.="<$key>\n";
$this->array_transform($value);
$this->text.="</$key>\n";
}
}
return $array_text;
}
function xml2array($xml){
$this->depth=-1;
$this->xml_parser = xml_parser_create($this->encoding);
xml_set_object($this->xml_parser,$this);
xml_parser_set_option($this->xml_parser,XML_OPTION_CASE_FOLDING,0);
xml_set_element_handler($this->xml_parser,"startElement","endElement");
xml_set_character_data_handler($this->xml_parser,"characterData");
xml_parse($this->xml_parser,$xml,true);
xml_parser_free($this->xml_parser);
return $this->arrays[0]['array'];
}
function startElement($parser,$name,$attrs){
$key = isset($attrs['name']) ?$attrs['name'] : $name;
$this->keys[]=$key;
$this->node_flag=1;
$this->depth++;
}
function characterData($parser,$data){
if($this->node_flag == 1){
$key=end($this->keys);
$this->arrays[$this->depth][$key]=$data;
$this->node_flag=0;
}
}
function endElement($parser,$name){
$key=array_pop($this->keys);
if($this->node_flag >0){
$this->arrays[$this->depth][$key]=$this->arrays[$this->depth+1];
unset($this->arrays[$this->depth+1]);
}
$this->node_flag = 2;
$this->depth--;
}
}
