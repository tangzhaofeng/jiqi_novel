<?php
echo '  <!--top_mini begin-->
  <div class="top_mini">
    <div class="wrap fix">
      <div class="userbar" id="userbar">      
        	';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'templates/loginheader.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
      </div>
      <div class="mini_r f_gray3">
       <a href="http://www.shuhai.com/" onClick="window.external.addFavorite(this.href,this.title);return false;" title=\'书海网\' rel="sidebar" class="addfav">加入收藏</a>
       |<a href="javascript:;"  onclick=this.style.behavior="url(#default#homepage)";this.setHomePage("http://www.ishufun.net/");  class="sethome"  title="品书小说网" >设为首页</a>
       |<a href="javascript:void(0)" id="StranLink" name="StranLink" class="fan"  title="繁体">繁体</a>
      </div>
    </div>
  </div><!--top_mini end-->
  <script type="text/javascript">loadheader();setInterval(loadheader,30000);</script>
  <div class="wrap">
    <div class="site">
     <a href="';
echo(JIEQI_URL); 
echo '" target="_blank" title="书海小说网" class="logo">书海小说网</a>
     <ul class="substation">
       <li>┠<a href="javascript:alert(\'建设中\');" title="言情小说网">言情网</a></li>
       <li>┠<a href="javascript:alert(\'建设中\');" title="手机网">手机网</a></li>
       <li>┠<a href="javascript:alert(\'建设中\');" title="文学网">文学网</a></li>
     </ul>     
    </div>
'.jieqi_get_block(array('bid'=>'278', 'module'=>'system', 'filename'=>'', 'classname'=>'BlockSystemCustom', 'side'=>'', 'title'=>'右上角头部文字推', 'vars'=>'', 'template'=>'', 'contenttype'=>'1', 'custom'=>'1', 'publish'=>'3', 'hasvars'=>'0'), 1).'
  </div>
  <!--nav begin-->
  <div class="nav">
   <div class="mainav">
    <ul>
     <li id="home" class="on" onclick="location.href=\''.$this->_tpl_vars['jieqi_url'].'/\'" style="cursor:pointer" title="首页">首页</li>
     <li id="top"><a href="'.geturl('article','top').'" target="_blank" title="排行榜">排行榜<em>|</em></a></li>
     <li id="search"><a href="/search/" title="搜小说" target="_blank">搜小说<em>|</em></a></li>
     <li><a href="/fuli/" title="作者福利" target="_blank">作者福利<em>|</em></a></li>
     <li id="masterPage"><a href="'.geturl('article','article','SYS=method=masterPage').'" title="作者专区" target="_blank">作者专区<em>|</em></a></li>
<!--     <li><a href="'.geturl('article','sort','SYS=sort=0&size=0&fullflag=2&operate=0&free=0&page=1').'">完本<em>|</em></a></li>-->
     <li id="news"><a href="'.$this->_tpl_vars['jieqi_local_url'].'/news/"  target="_blank"title="资讯">资讯<em>|</em></a></li>
     <li><a href="/wap/html5.html" target="_blank" title="wap">wap</a><span class="fl">&nbsp;&nbsp;·</span><a class="client" href="/wap/anzhuo.html" target="_blank" title="客户端">客户端<img class="newapp" src="'.$this->_tpl_vars['jieqi_themeurl'].'images/new.png" alt="最新客户端" /><em>|</em></a></li><!--手机阅读-->
     <li id="pay"><a class="f_red" href="'.geturl('pay','home').'" title="充值" target="_blank">充值<em>|</em></a></li>
     <!--<li id="help"><a href="/help" target="_blank" title="帮助">帮助<em>|</em></a></li>-->
     <li><a href="tencent://message/?uin=724171887" title="客服">客服<em>|</em></a></li>
<!--     <li><a href="javascript:void(0)">论坛<em>|</em></a></li>
     <li><a href="javascript:void(0)">听书<em>|</em></a></li>
     <li><a href="javascript:void(0)">出版精品<em>|</em></a></li>
     <li><a href="javascript:void(0)">投诉与建议<em>|</em></a></li>-->
    </ul>
   </div>
   <div class="sortnav">
    <dl class="f_white">
     <dd class="one"><a href="/shuku/" title="书库">书库</a></dd><!--'.geturl('article','sort','SYS=sort=0&size=0&fullflag=0&operate=0&free=0&page=1').'-->
     <dd><a href="'.geturl('article','channel','class=dushi').'" title="都市小说">都市</a>&middot;<a href="'.geturl('article','channel','class=yanqing').'" title="言情小说">言情</a></dd>
     <dd><a href="'.geturl('article','channel','class=xuanhuan').'" title="玄幻小说">玄幻</a>&middot;<a href="'.geturl('article','channel','class=xiuzhen').'" title="修真小说">修真</a></dd>
     <dd><a href="'.geturl('article','channel','class=lishi').'" title="历史小说">历史</a>&middot;<a href="'.geturl('article','channel','class=wuxia').'" title="武侠小说">武侠</a></dd>
     <dd><a href="'.geturl('article','channel','class=wangyou').'" title="网游小说">网游</a>&middot;<a href="'.geturl('article','channel','class=jingji').'" title="竞技">竞技</a></dd>
     <dd><a href="'.geturl('article','channel','class=junshi').'" title="军事小说">军事</a>&middot;<a href="'.geturl('article','channel','class=kehuan').'" title="科幻小说">科幻</a></dd>
     <dd><a href="'.geturl('article','channel','class=kongbu').'" title="恐怖小说">恐怖</a>&middot;<a href="'.geturl('article','channel','class=tongren').'" title="同人小说">同人</a></dd>
     <dd><a href="'.$this->_tpl_vars['jieqi_local_url'].'/quanben/" title="全本小说">全本</a>&middot;<a href="'.geturl('article','top','method=toplist','SYS=type=postdate&sortid=0&page=1').'" title="最新小说">新书</a></dd>
    </dl>
   </div>
  </div><!--nav end-->
';
?>