<?php
echo '<ul class="ulitem">
  <li><a href="'.geturl('article','article','SYS=method=newArticleView').'">发表新作</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/newdraft.php">新建草稿</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/draft.php">草 稿 箱</a></li>
  <li><a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?oid=self">会 客 室</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/authorpage.php">我的专栏</a></li>
  <li><a href="'.geturl('article','article','SYS=method=masterPage&display=all').'">我的文章列表</a></li>
  <li><a href="'.geturl('article','yl','method=yl').'">yl的文章列表</a></li>
  <li><a href="'.geturl('article','article','SYS=method=bcView').'">我的书架1.0</a></li>
  ';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
  <li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/newobook.php">新建电子书</a></li>
  <li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/masterpage.php">我的电子书</a></li>
  ';
}
echo '
</ul>';
?>