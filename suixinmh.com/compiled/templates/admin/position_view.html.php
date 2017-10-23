<?php
echo '<table align="center" cellpadding="2" cellspacing="1" class="grid" width="100%">
  <caption>标签管理</caption>
  <tr>
    <td><a href=\''.$this->_tpl_vars['adminprefix'].'&method=add&step=one\'><font color="red">添加标签</font></a> | <a href=\''.$this->_tpl_vars['adminprefix'].'\'>返回标签列表</a></td>
  </tr>
</table>
<table cellpadding="0" cellspacing="1" class="grid" width="100%">
    <caption>标签内容预览</caption>
    <tr> 
      <td>'.$this->_tpl_vars['_PAGE']['content'].'</td>
    </tr>
</table>
';
?>