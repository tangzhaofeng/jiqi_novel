<?php

     
ini_set('display_errors', 'On'); // Off
error_reporting(E_ALL^E_NOTICE);
// error_reporting(E_ALL^E_NOTICE);


if(!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME', 'server');

require dirname(__FILE__).'/global.php';
Application::run();


?>