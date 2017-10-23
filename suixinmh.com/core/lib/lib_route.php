<?php 
final class Route extends JieqiObject{
public $url_query;
public $url_type;
public $route_url = array();
public function __construct() {
}
public function setUrlType($url_type = 2){
if($url_type >0 &&$url_type <3){
$this->url_type = $url_type;
}else{
trigger_error("指定的URL模式不存在！");
}
}
public function getUrlArray(){
$this->makeUrl();
return $this->route_url;
}
public function makeUrl(){
switch ($this->url_type){
case 1: 
$this->querytToArray();
break;
case 2: 
$this->pathinfoToArray();
break;
}
}
public function querytToArray(){
$array = $tmp = array();
$array = $this->getRequest();
if (count($array) >0) {
if (isset($array['app'])) {
$this->route_url['app'] = $array['app'];
unset($array['app']);
}
if (isset($array['controller'])) {
$this->route_url['controller'] = htmlspecialchars(str_replace(array('\/','\'','"','.','http:','ftp:'),'',$array['controller']));
unset($array['controller']);
}
if (isset($array['method'])) {
$this->route_url['method'] = htmlspecialchars(str_replace(array('\/','\'','"','.','http:','ftp:'),'',$array['method']));
unset($array['method']);
}
}else{
$this->route_url = array();
}
if(count($array) >0){
$this->route_url['params'] = $array;
}
}
public function pathinfoToArray(){
}
}
