<?php
$this->_tpl_vars['jieqi_pagetitle'] = '小说排行榜,网络小说排行榜-书海小说网';
echo '
';
$this->_tpl_vars['meta_keywords'] = '小说排行榜,网络小说排行榜';
echo '
';
$this->_tpl_vars['meta_description'] = '小说排行榜由书海小说网广大小说读者投票结果排名的,包含小说排行榜2013前十名、小说排行榜2014前十名,是最权威的网络小说排行榜,我们将最新最热的小说排行榜展现给大家!';
echo '
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/rank.css" type="text/css" rel="stylesheet" />
<style type="text/css">
.tabox5{ height:470px;}
</style>
<!--wrap begin-->
<div class="wrap fix">
<div class="ad2"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/bd2.js"></script></div>
  <!--sidebar2 begin-->
  <div class="sidebar2 fl">
   <!--box_side2 begin-->
    <div class="box_side2">
      <div class="t"><h3>小说排行榜</h3></div>
      <ul class="list_b f_gray3">
       <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvipvote&sortid=0&page=1').'">月票榜</a></li>
       <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=0&page=1').'">点击榜</a></li>
       <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvote&sortid=0&page=1').'">推荐榜</a></li>
       <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalgoodnum&sortid=0&page=1').'">收藏榜</a></li>
      </ul>
    </div><!--box_side2 end-->
   <!--box_side2 begin-->
    <div class="box_side2">
      <div class="t"><h3>分类榜</h3></div>
      <ul class="list_b f_gray3">
       '.jieqi_geturl('system', 'tags', array('id'=>1, 'name'=>'%C8%AB%D5%BE%B7%D6%C0%E0%B5%BC%BA%BD%3C%7B%7D%3E%3C%7Bv1%2Fblock_sorttop.html%7D%3E')).'
      </ul>
    </div><!--box_side2 end-->
<!--box_side2 begin-->
    <div class="box_side2">
      <div class="t"><h3>特色榜</h3></div>
      <ul class="list_b f_gray3">
       <li><a href="'.geturl('article','top','method=toplist','SYS=type=daysale&sortid=0&page=1').'">24小时销售榜</a></li>
       <li><a href="'.geturl('article','top','method=toplist','SYS=type=daysize&sortid=0&page=1').'">24小时更新榜</a></li>
      </ul>
    </div><!--box_side2 end-->

  </div><!--sidebar2 end-->
  <!--article3 begin-->
  <div class="article3 fr">
    <!--tabox5 begin-->
    <div class="tabox5">
      <div class="t">
       <h2>月票榜</h2>
<!--       <ul class="tabs5" id="tabs1">
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvipvote&sortid=0&page=1').'">本月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=premonthvipvote&sortid=0&page=1').'">上月</a></li>
       </ul>
-->      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox1">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvipvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <!--<li class="tab_con3" style="display:none;">
          </li>       
          <li class="tab_con3" style="display:none;">
          </li>-->
        </ul>
      </div><!--box_dwn end-->
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5">
      <div class="t">
       <h2>点击榜</h2>
       <ul class="tabs5" id="tabs2">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid=0&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=0&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvisit&sortid=0&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox2">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvisit%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvisit%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5">
      <div class="t">
       <h2>推荐榜</h2>
       <ul class="tabs5" id="tabs3">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weekvote&sortid=0&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvote&sortid=0&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvote&sortid=0&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox3">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvote%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->
    
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5 mt10">
      <div class="t">
       <h2>销售榜</h2>
       <ul class="tabs5" id="tabs4">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weeksale&sortid=0&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthsale&sortid=0&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalsale&sortid=0&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox4">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweeksale%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthsale%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalsale%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->    
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5 mt10">
      <div class="t">
       <h2>字数榜</h2>
       <ul class="tabs5" id="tabs5">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weeksize&sortid=0&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthsize&sortid=0&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=size&sortid=0&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox5">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweeksize%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthsize%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bsize%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->    
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5 mt10">
      <div class="t">
       <h2>收藏榜</h2>
       <ul class="tabs5" id="tabs6">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weekgoodnum&sortid=0&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthgoodnum&sortid=0&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalgoodnum&sortid=0&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox6">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekgoodnum%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthgoodnum%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalgoodnum%2C10%2C0%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->    
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5 mt10">
      <div class="t">
       <h2>都市小说</h2>
       <ul class="tabs5" id="tabs7">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid=2&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=2&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvisit&sortid=2&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox7">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvisit%2C10%2C2%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvisit%2C10%2C2%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C10%2C2%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->    
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5 mt10">
      <div class="t">
       <h2>修真小说</h2>
       <ul class="tabs5" id="tabs8">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid=3&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=3&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvisit&sortid=3&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox8">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvisit%2C10%2C3%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvisit%2C10%2C3%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C10%2C3%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->    
    </div><!--tabox5 end-->
    <!--tabox5 begin-->
    <div class="tabox5 mt10">
      <div class="t">
       <h2>玄幻小说</h2>
       <ul class="tabs5" id="tabs10">
        <li class="thistab"><a href="'.geturl('article','top','method=toplist','SYS=type=weekvisit&sortid=1&page=1').'">周</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=monthvisit&sortid=1&page=1').'">月</a></li>
        <li><a href="'.geturl('article','top','method=toplist','SYS=type=totalvisit&sortid=1&page=1').'">总</a></li>
       </ul>
      </div>
      <!--box_dwn begin-->
      <div class="box_dwn">
        <ul id="tab_conbox10">       
          <li class="tab_con3">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bweekvisit%2C10%2C1%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Bmonthvisit%2C10%2C1%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>       
          <li class="tab_con3" style="display:none;">
			'.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C10%2C1%2C0%2C0%2C0%7D%3E%3C%7Bv1%2Fblock_top.html%7D%3E')).' 
          </li>
        </ul>
      </div><!--box_dwn end-->    
    </div><!--tabox5 end-->
  </div><!--article3 end-->
</div><!--wrap end-->
<div class="ad"><script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/js/tb1.js"></script></div>
';
?>