<?php
//xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
/**
 * 应用入口文件 * @copyright   Copyright(c) 2014
 * @author      huliming* @version     1.0
 */
if(!defined('JIEQI_MODULE_NAME')) define('JIEQI_MODULE_NAME', 'system');
require dirname(__FILE__).'/global.php';
//require 'E:/wwwroot/system/app.php';
Application::run();
//入口文件主要做了2件事，第一引入系统的驱动类，第二是引入配置文件，然后运行run（）方法，传入配置作为参数，具体这2个文件是什么内容，我们接下来继续看。

//$data = xhprof_disable();   //返回运行数据

// xhprof_lib在下载的包里存在这个目录,记得将目录包含到运行的php代码中
//include_once "xhprof_lib/utils/xhprof_lib.php";
//include_once "xhprof_lib/utils/xhprof_runs.php";
//
//$objXhprofRun = new XHProfRuns_Default();
//$run_id = $objXhprofRun->save_run($data, "xhprof");
?>