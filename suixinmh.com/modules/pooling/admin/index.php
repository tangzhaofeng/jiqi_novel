<?php
define('HLM_RUN_PATH', str_replace('\\','/',dirname(__FILE__)));
define('HLM_VIEW_PATH', dirname(HLM_RUN_PATH).'/templates/admin'); //存放视图模板文件
define('JIEQI_MODULE_NAME', 'pooling');
require '../../../index.php';
?>