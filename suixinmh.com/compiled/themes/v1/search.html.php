<?php
echo '<div class="wrap">
      <!--notice begin-->
      <div class="notice">
        <span class="nt"></span>
        <div id="scrollDiv">
          <ul class="f_gray3">
            '.jieqi_get_block(array('bid'=>'279', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[公共]头部轮播小喇叭', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
          </ul>
        </div> 
      </div><!--notice end-->
      <!--so begin-->
      <div class="sor">
         <form action="/search" method="get" ajaxform="true" onsubmit=\'return false\'>
		   <input type="text" name="searchkey"  id="J_search_suggest" value="'.$this->_tpl_vars['searchkey'].'" data-placeholder="键入书名、作者名开始搜索" class="intxt"/>
		   <input name="dosubmit" type="submit" value="搜 索" class="btn_so" /> 
		 </form>
      </div><!--so end-->      
    </div>
';
?>