<?php
echo '<div class="gridtop">
	<a href="'.$this->_tpl_vars['adminprefix'].'">';
if($this->_tpl_vars['_REQUEST']['method'] == "" || $this->_tpl_vars['_REQUEST']['method'] == "finance"){
echo '【版权管理】';
}else{
echo '版权管理';
}
echo '</a> 
	| <a href="'.$this->_tpl_vars['adminprefix'].'&method=reward">';
if($this->_tpl_vars['_REQUEST']['method'] == "reward"){
echo '【稿费管理】';
}else{
echo '稿费管理';
}
echo '</a>
	| <a href="javascript:alert(\'建设中...\')">已审记录</a>
	| <a href="javascript:alert(\'建设中...\')">被拒记录</a>
</div>';
?>