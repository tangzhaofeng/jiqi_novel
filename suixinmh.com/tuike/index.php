<?php


ini_set('display_errors', 'On'); // Off
error_reporting(E_ALL^E_NOTICE);
// error_reporting(E_ALL^E_NOTICE);

if(!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME', 'tuike');

if( strpos($_SERVER['HTTP_HOST'],'www.') !== false ){
    if(!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME', 'article');
}else{
    if(!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME', '3g');
}

     
require dirname(__FILE__).'/global.php';
Application::run();


?>