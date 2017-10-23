<?php 
define('HLM_RUN_PATH', str_replace('\\','/',dirname(__FILE__)));
define('HLM_VIEW_PATH', dirname(HLM_RUN_PATH).'/templates/admindemo'); //存放视图模板文件
define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAGE_TAG','<ul class="pagination">[prepage]<li class="prev disabled"><a href="{$prepage}"><i class="icon-double-angle-left"></i></a></li>[/prepage][pages][pnum]6[/pnum][pnumchar]<li class="active"><a href="javascript:;">{$page}</a></li>[/pnumchar][pnumurl]<li><a href="{$pnumurl}">{$pagenum}</a></li>[/pnumurl]{$pages}[/pages][nextpage]<li class="next"><a href="{$nextpage}"><i class="icon-double-angle-right"></i></a></li>[/nextpage]</ul>');
require '../../../index.php'; 
?>