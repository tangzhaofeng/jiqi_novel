<?php
header("Content-type: text/html; charset=utf-8");
xhprof_enable(XHPROF_FLAGS_CPU|XHPROF_FLAGS_NO_BUILTINS);
@define('JIEQI_WAP_PAGE', '1');
@define('JIEQI_CHARSET_CONVERT', '0');
@define('JIEQI_CHAR_SET', 'utf-8');
@define('JIEQI_SESSION_NAME', session_name());
define('JIEQI_MODULE_NAME', '3g');
@define('JIEQI_PAGE_TAG','<div class="but"><a href="$prepage">上一页</a>[option]<option value="$optionurl">$option</option>[/option]</div><div class="inbut"><select onchange="window.location.href=this.value;">{$select}</select></div><div class="but"><a href="$nextpage">下一页</a></div>');
require '../../index.php';

$xhprof_data = xhprof_disable();

$XHPROF_ROOT = "/www/debug.ishufun.net/";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_testing");
?>
