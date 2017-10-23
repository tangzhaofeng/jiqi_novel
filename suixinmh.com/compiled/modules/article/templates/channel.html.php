<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<title>'.$this->_tpl_vars['sort'].'小说,好看的'.$this->_tpl_vars['sort'].'小说,'.$this->_tpl_vars['sort'].'小说排行榜,'.$this->_tpl_vars['sort'].'小说排行榜完本</title>
<meta name="keywords" content="'.$this->_tpl_vars['sort'].'小说,'.$this->_tpl_vars['sort'].'小说小说排行榜,'.$this->_tpl_vars['sort'].'小说小说排行榜完本,好看的'.$this->_tpl_vars['sort'].'小说">
<meta name="description" content="书海'.$this->_tpl_vars['sort'].'小说网提供最好看的'.$this->_tpl_vars['sort'].'小说在线阅读与'.$this->_tpl_vars['sort'].'小说TXT电子书免费下载,我们的'.$this->_tpl_vars['sort'].'小说小说排行榜上有着好看的'.$this->_tpl_vars['sort'].'小说推荐,希望您在'.$this->_tpl_vars['sort'].'小说小说排行榜完本里边找到您喜欢的那本'.$this->_tpl_vars['sort'].'小说。">
<meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
<meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/channel.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/layer/layer.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/page.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/channel.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/scripts/jquery-ui.min.js"></script>
<!--[if lt IE 10]>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'js/PIE.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/DD_belatedPNG.js" type="text/javascript"></script>
<script type="text/javascript">
DD_belatedPNG.fix(\'div, ul, img, li, dl, dd, input,span,h3,h2,a,em\');
</script>
<![endif]-->

</head>

<body>
<!--top begin-->
<div class="top fix">
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/v1/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
  <!--box_ns begin-->
  <div class="box_ns">
    ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/v1/search.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
  </div><!--box_ns end-->
</div><!--top end-->
<div class="wrap fix">
<div class="ad2"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/bd2.js"></script></div>
'.jieqi_geturl('system', 'tags', array('id'=>239, 'name'=>'%5B%C6%B5%B5%C0%5D%D7%F3%C9%CF%BD%C7-%B7%E2%C3%E6%CD%C6%BC%F6')).'
'.jieqi_geturl('system', 'tags', array('id'=>240, 'name'=>'%5B%C6%B5%B5%C0%5D%D6%D0%C9%CF-15%CD%C6%BC%F6')).'
  <div class="box_side fr">
    <div class="t1"><h2>本周强推</h2></div>
    <div class="dwn2 qh">
      <dl class="ldt p10">      
        '.jieqi_geturl('system', 'tags', array('id'=>128, 'name'=>'%5B%CA%D7%D2%B3%5D%D3%D2%C9%CF%BD%C7-%B0%D9%CD%F2%B7%E7%D4%C6%B0%F1')).'
      </dl>
    </div>
  </div>
  <div class="cl"></div>
  
  <!--box begin-->
  <div class="box pt10">  
   <div class="box_mid3">
    <div class="down fix"> 
     <div class="t"><h3>'.$this->_tpl_vars['sort'].'小说精品推荐</h3><span class="orn"></span></div>
     ';
$this->_tpl_vars['tuisorid'] = $this->_tpl_vars['sortid']+164; 
echo '
     '.jieqi_geturl('system', 'tags', array('id'=>$this->_tpl_vars['tuisorid'], 'name'=>'%5B%C6%B5%B5%C0%5D%D0%FE%BB%C3%D0%A1%CB%B5%BE%AB%C6%B7%CD%C6%BC%F6%3C%7B%7D%3E%3C%7Bv1%2Fblock_jingpintuijian.html%7D%3E')).'
    </div>
   </div>    
  </div><!--box end-->
  <!--sidebar begin-->
  <div class="sidebar fl mt10">
    <!--box_side begin-->
    <div class="box_side">
      <div class="t1"><h2>'.$this->_tpl_vars['sort'].'小说完本推荐</h2></div>
      <div class="box_dwn11">
        <ul class="list_t11 f_black">
		  ';
$this->_tpl_vars['wanbensorid'] = $this->_tpl_vars['sortid']+176; 
echo '
		  '.jieqi_geturl('system', 'tags', array('id'=>$this->_tpl_vars['wanbensorid'], 'name'=>'%5B%C6%B5%B5%C0%5D%D0%FE%BB%C3%D0%A1%CB%B5%CD%EA%B1%BE%CD%C6%BC%F6%3C%7B%7D%3E%3C%7Bv1%2Fblock_spanli.html%7D%3E')).'
          </ul>
        </div>
    </div><!--box_side end-->
    
    <!--box_side begin-->
   <!-- <div class="box_side fl mt10">
      <div class="t1"><h2>编辑推荐</h2></div>
      <div class="box_dwn11">
        <ul class="list_t11 f_black">
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="霸上天降小萌妃">霸上天降小萌妃</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="九婴邪仙">九婴邪仙</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="采花到乡村">采花到乡村</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="大罗金仙都市销魂记">大罗金仙都市销魂记大罗金仙都市销魂记大罗金仙都市销魂记大罗金仙都市销魂记</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="女神养成计划">女神养成计划</a></span></li>
          
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="超牛都市兵神">超牛都市兵神</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="女人，快到碗里来！">女人，快到碗里来！</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="冷情CEO的">冷情CEO的</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="大罗金仙都市销魂记">大罗金仙都市销魂记</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="玩转娱乐">玩转娱乐</a></span></li>
          
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="女人，快到碗里来！">女人，快到碗里来！</a></span></li>
          <li><span class="sort">[都市]</span><span class="name"><a href="javascript:void(0)" target="_blank" title="大罗金仙都市销魂记">大罗金仙都市销魂记</a></span></li>
          </ul>
        </div>
    </div>--><!--box_side end-->
  </div><!--sidebar end-->
  
<!--boxm begin-->
  <div class="boxm3 mt10">
    <!--box_mid4 begin-->
	     ';
$this->_tpl_vars['zhuantisorid'] = $this->_tpl_vars['sortid']+188; 
echo '
		  '.jieqi_geturl('system', 'tags', array('id'=>$this->_tpl_vars['zhuantisorid'], 'name'=>'%5B%C6%B5%B5%C0%5D%D0%A1%CB%B5%C3%BF%D4%C2%D7%A8%CC%E2')).'
   <!--box_mid4 end-->
  </div> <!--boxm end-->
    
  <!--sidebar begin-->
  <div class="sidebar fr mt10">
    <!--box_side begin-->
    <div class="box_side">
      <div class="t1"><h2>'.$this->_tpl_vars['sort'].'小说新书精选</h2></div>
      <div class="box_dwn11">
        <ul class="list_t11 f_black">
         ';
$this->_tpl_vars['xinshusorid'] = $this->_tpl_vars['sortid']+200; 
echo '
		  '.jieqi_geturl('system', 'tags', array('id'=>$this->_tpl_vars['xinshusorid'], 'name'=>'%5B%C6%B5%B5%C0%5D%D0%FE%BB%C3%D0%A1%CB%B5%CD%EA%B1%BE%CD%C6%BC%F6%3C%7B%7D%3E%3C%7Bv1%2Fblock_spanli.html%7D%3E')).'
          </ul>
        </div>
    </div><!--box_side end-->
  </div><!--box_side end-->
  <!--article2 begin-->
  <div class="article2 fl mt10">
     <div class="update">
       <div class="tt">
         <ul class="tabs2" id="tabs5">
           <li><a href="javascript:void(0)">最新更新小说</a></li>
           <li><a href="javascript:void(0)">最新签约小说</a></li>
         </ul>
       </div>
       <ul class="tab_conbox22" id="tab_conbox5">
        <li class="tab_con2 fix">
          <div class="t">
           <span class="sort">类别</span><span class="name">书名/章节</span><span class="author">作者</span><span class="date">更新时间</span>
          </div>
     	 	'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{lastupdate,21,'.$this->_tpl_vars['sortid'].',0,0,0}><{block_channellist.html}>')).'
          <p class="more"><a href="'.geturl('article','top','method=toplist','SYS=type=lastupdate&sortid='.$this->_tpl_vars['sortid'].'&page=1').'" target="_blank" class="f_blue">更多&gt;&gt;</a></p>
        </li>
        <li class="tab_con2">
          <div class="t">
           <span class="sort">类别</span><span class="name">书名/章节</span><span class="author">作者</span><span class="date">签约时间</span>
          </div>
		  '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{signdate,21,'.$this->_tpl_vars['sortid'].',0,0,0}><{block_channellist.html}>')).'
          <p class="more"><a href="'.geturl('article','top','method=toplist','SYS=type=lastupdate&sortid='.$this->_tpl_vars['sortid'].'&page=1').'" target="_blank" class="f_blue">更多&gt;&gt;</a></p>
        </li>
       </ul>
     </div>
  </div><!--article2 end-->
  <!--box_side begin-->
  <div class="sidebar fr mt10">
   <div class="tabbox3">
      <div class="t"><h2>推荐榜</h2>
       <ul class="tabs3" id="tabs3">
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=weekvote&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvote&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvote&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">总</a></li>
       </ul>
      </div>
      <ul class="tab_conbox4" id="tab_conbox3">       
         <li class="tab_con3">
          <div class="box_dwn21">
            <dl class="list_t3d f_black">
               '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{weekvote,10,'.$this->_tpl_vars['sortid'].',0,0,0}><{v1/block_visit2.html}>')).'
            </dl>
          </div>
        </li>       
        <li class="tab_con3" style="display:none">
          <div class="box_dwn21">
            <dl class="list_t3d f_black">
             '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{monthvote,10,'.$this->_tpl_vars['sortid'].',0,0,0}><{v1/block_visit2.html}>')).'
            </dl>
          </div>
        </li>       
        <li class="tab_con3" style="display:none">
          <div class="box_dwn21">
            <dl class="list_t3d f_black">
              '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{totalvote,10,'.$this->_tpl_vars['sortid'].',0,0,0}><{v1/block_visit2.html}>')).'
            </dl>
          </div>
        </li>
      </ul>
    </div>    
    
    <div class="tabbox3 mt10">
      <div class="t"><h2>点击榜</h2>
       <ul class="tabs3" id="tabs4">
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvisit&sortid='.$this->_tpl_vars['sortid'].'&page=1').'">总</a></li>
       </ul>
      </div>
      <ul class="tab_conbox4" id="tab_conbox4">       
        <li class="tab_con3"><div class="box_dwn21">
            <dl class="list_t3d f_black">
               '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{weekvisit,10,'.$this->_tpl_vars['sortid'].',0,0,0}><{v1/block_visit2.html}>')).'
            </dl></div>
        </li>       
        <li class="tab_con3" style="display:none"><div class="box_dwn21">
            <dl class="list_t3d f_black">
             '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{monthvisit,10,'.$this->_tpl_vars['sortid'].',0,0,0}><{v1/block_visit2.html}>')).'
            </dl></div>
        </li>       
        <li class="tab_con3" style="display:none"><div class="box_dwn21">
            <dl class="list_t3d f_black">
              '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'通用查询<{totalvisit,10,'.$this->_tpl_vars['sortid'].',0,0,0}><{v1/block_visit2.html}>')).'
            </dl></div>
        </li>
      </ul>
    </div> 
  </div><!--box_side end-->
  <div class="cl"></div>
<div class="ad4"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/tb1.js"></script></div>
  <div class="fdlink mt10">
   <h3 class="t">友情链接</h3>
   <div class="txt f_gray6">';
if($this->_tpl_vars['sortid']==2){
echo jieqi_get_block(array('bid'=>'280', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[都市]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==1){
echo jieqi_get_block(array('bid'=>'281', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[玄幻]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==3){
echo jieqi_get_block(array('bid'=>'282', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[修真]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==4){
echo jieqi_get_block(array('bid'=>'283', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[网游]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==5){
echo jieqi_get_block(array('bid'=>'284', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[科幻]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==6){
echo jieqi_get_block(array('bid'=>'285', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[武侠]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==7){
echo jieqi_get_block(array('bid'=>'286', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[竞技]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==9){
echo jieqi_get_block(array('bid'=>'287', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[军事]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==8){
echo jieqi_get_block(array('bid'=>'288', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[历史]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==10){
echo jieqi_get_block(array('bid'=>'289', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[恐怖]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else if($this->_tpl_vars['sortid']==11){
echo jieqi_get_block(array('bid'=>'290', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[同人]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}else{
echo jieqi_get_block(array('bid'=>'291', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'[言情]友链', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
';
}
echo '</div>
  </div>
</div><!--wrap end-->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/v1/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
</body>
</html>';
?>