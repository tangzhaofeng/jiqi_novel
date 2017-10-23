<?php
class JieqiClient_CookieManager extends JieqiObject
{
var $_cookies = array();
function JieqiClient_CookieManager()
{
$this->JieqiObject();
}
function passCookies(&$request)
{
if (!empty($this->_cookies)) {
$url =&$request->_url;
$cookies = array();
foreach ($this->_cookies as $cookie) {
if ($this->_domainMatch($url->host,$cookie['domain']) &&(0 === strpos($url->path,$cookie['path']))
&&(empty($cookie['secure']) ||$url->protocol == 'https')) {
$cookies[$cookie['name']][strlen($cookie['path'])] = $cookie['value'];
}
}
foreach ($cookies as $name =>$values) {
krsort($values);
foreach ($values as $value) {
$request->addCookie($name,$value);
}
}
}
return true;
}
function addCookie($cookie)
{
$hash = $this->_makeHash($cookie['name'],$cookie['domain'],$cookie['path']);
$this->_cookies[$hash] = $cookie;
}
function setCookies($cookies)
{
$this->_cookies=$cookies;
}
function getCookies()
{
return $this->_cookies;
}
function updateCookies(&$request)
{
if (false !== ($cookies = $request->getResponseCookies())) {
$url =&$request->_url;
foreach ($cookies as $cookie) {
if (!isset($cookie['domain'])) {
$cookie['domain'] = $url->host;
}
if (!isset($cookie['path'])) {
$cookie['path'] = DIRECTORY_SEPARATOR == dirname($url->path)?'/': dirname($url->path);
}
if ($this->_domainMatch($url->host,$cookie['domain'])) {
$hash = $this->_makeHash($cookie['name'],$cookie['domain'],$cookie['path']);
if (strlen($cookie['value'])
&&(!isset($cookie['expires']) ||(strtotime($cookie['expires']) >JIEQI_NOW_TIME))) {
$this->_cookies[$hash] = $cookie;
}elseif (isset($this->_cookies[$hash])) {
unset($this->_cookies[$hash]);
}
}
}
}
}
function _makeHash($name,$domain,$path)
{
return md5($name ."\r\n".$domain ."\r\n".$path);
}
function _domainMatch($requestHost,$cookieDomain)
{
if ('.'!= $cookieDomain{0}) {
return $requestHost == $cookieDomain;
}elseif (substr_count($cookieDomain,'.') <2) {
return false;
}else {
return substr('.'.$requestHost,-strlen($cookieDomain)) == $cookieDomain;
}
}
function reset()
{
$this->_cookies = array();
}
}
