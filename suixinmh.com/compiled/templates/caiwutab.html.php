<?php
echo '      <div class="t2">
       <h2>账务中心</h2>
       <ul class="tabs62">
        <li id="t_home_main"><a href="'.geturl('pay','home').'">充值</a></li>
        <li id="t_userhub_czView"><a href="'.geturl('system','userhub','SYS=method=czView').'">充值记录</a></li>
        <li id="t_userhub_xfView"><a href="'.geturl('system','userhub','SYS=method=xfView').'">消费记录</a></li>
        <li id="t_userhub_dyView"><a href="'.geturl('system','userhub','SYS=method=dyView').'">自动订阅设置</a></li>
       </ul>       
      </div>
      
      <script>
	//防止tab ID和accordion id 冲突，tabID加t_
	var id =\''.$this->_tpl_vars['_REQUEST']['controller'].'\'+\'_\'+\''.$this->_tpl_vars['_REQUEST']['method'].'\';
	 $(\'#t_\'+id).addClass("thistab");
	 if(\''.$this->_tpl_vars['_REQUEST']['controller'].'\'==\'home\')
	 	$(\'#t_home_main\').addClass("thistab");
	</script>
';
?>