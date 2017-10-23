<?php
echo '      <div class="t2">
       <h2>会员专区</h2>
       <ul class="tabs62">
        <li';
if($this->_tpl_vars['_REQUEST']['method']==usermember){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','userhub','SYS=method=usermember').'">会员专区</a></li>
        <li';
if($this->_tpl_vars['_REQUEST']['method']==uservip){
echo ' class="thistab"';
}
echo '><a href="'.geturl('system','userhub','SYS=method=uservip').'">VIP专区</a></li>
       </ul>       
      </div>';
?>