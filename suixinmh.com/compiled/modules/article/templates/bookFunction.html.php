<?php
echo '<script type="text/javascript">
$(function(){
	';
if($this->_tpl_vars['_REQUEST']['controller']=='home'){$this->_tpl_vars['_REQUEST']['method']='main';} 
echo '
  var ss = \''.$this->_tpl_vars['_REQUEST']['controller'].'\'+\'_\'+\''.$this->_tpl_vars['_REQUEST']['method'].'\';
  if(ss == \'userhub_inbox\' || ss == \'userhub_outbox\' || ss == \'userhub_draft\' || ss == \'userhub_toSysView\' || ss == \'userhub_messagedetail\'){
      $(\'#userhub_newmessage\').parent("dl.list_menu").show();
	  $(\'#userhub_newmessage\').children("a").addClass("focus");
  }
  if(ss == \'chapter_cmView\'){
      $(\'#article_masterPage\').parent("dl.list_menu").show();
	  $(\'#article_masterPage\').children("a").addClass("focus");
  }
//  if(\''.$this->_tpl_vars['_REQUEST']['method'].'\' == \'upaView\'){
//      $(\'#userhub_usereditView\').parent("dl.list_menu").show();
//	  $(\'#userhub_usereditView\').children("a").addClass("focus");
//  }
  if(\''.$this->_tpl_vars['_REQUEST']['method'].'\' == \'hotcomment\'){
      $(\'#userhub_comment\').parent("dl.list_menu").show();
	  $(\'#userhub_comment\').children("a").addClass("focus");
  }
  if(\''.$this->_tpl_vars['_REQUEST']['method'].'\' == \'uservip\'){
      $(\'#userhub_usermember\').parent("dl.list_menu").show();
	  $(\'#userhub_usermember\').children("a").addClass("focus");
  }
  if(\''.$this->_tpl_vars['_REQUEST']['method'].'\' == \'moderatorView\'){
      $(\'#userhub_review_view\').parent("dl.list_menu").show();
	  $(\'#userhub_review_view\').children("a").addClass("focus");
  }
  $(\'#\'+ss).parent("dl.list_menu").show();
  $(\'#\'+ss).children("a").addClass("focus");
  $("li#row em").click(function(){
  $(this).parent().parent().children("dl.list_menu").toggle(300);
  });
});

</script>
<!--sidebar2 begin-->
  <div class="sidebar2 fl bg4 fix">
	';
if($this->_tpl_vars['_USER']['uid']>0){
echo '
		';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/article/templates/chongzhi.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '	<!--';
if($this->_tpl_vars['_REQUEST']['method']=='' || $this->_tpl_vars['_REQUEST']['method']=='avatarView'){
echo '
		';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/article/templates/touxiang.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
	';
}else{
}
echo '-->
	';
}
echo '
    <ul class="column">
     <li><a href="'.geturl('system','userhub').'"><em class="def b">首页</em></a></li>
     <li id="row"><a href="javascript:void(0)"><em class="account" >帐号管理</em></a>
	 <dl class="list_menu" style="display:none">
       <dd id="userhub_usereditView"><a href="'.geturl('system','userhub','SYS=method=usereditView').'"><i>&middot;</i>基本资料</a></dd>
       <dd id="userhub_upaView"><a href="'.geturl('system','userhub','SYS=method=upaView').'"><i>&middot;</i>修改头像</a></dd>
       <dd id="userhub_pwdview"><a href="'.geturl('system','userhub','SYS=method=pwdview').'"><i>&middot;</i>密码及安全</a></dd>
      </dl>
     </li>
     <li id="row"><a href="javascript:void(0)"><em class="trend" >我的'.$this->_tpl_vars['jieqi_sitename'].'</em></a>
      <dl class="list_menu" style="display:none">
       <dd id="userhub_comment"><a href="'.geturl('system','userhub','SYS=method=comment').'"><i>&middot;</i>我的书评</a></dd>
       <dd id="userhub_friend"><a href="'.geturl('system','userhub','SYS=method=friend').'"><i>&middot;</i>我的关注</a></dd>
       <dd id="article_bcView"><a href="'.geturl('article','article','SYS=method=bcView').'"><i>&middot;</i>我的书架</a></dd>
       <dd id="userhub_newmessage"><a href="'.geturl('system','userhub','SYS=method=inbox').'"><i>&middot;</i>短消息</a></dd>
       <dd id="userhub_usermember"><a href="'.geturl('system','userhub','SYS=method=usermember').'"><i>&middot;</i>会员特权</a></dd>
      </dl>
     </li>
     <li id="row"><a href="javascript:void(0)"><em class="fiscal" >账务中心</em></a>
      <dl class="list_menu" style="display:none">
       <dd id="home_main"><a href="'.geturl('pay','home').'"><i>&middot;</i>充值</a></dd>
       <dd id="userhub_czView"><a href="'.geturl('system','userhub','SYS=method=czView').'"><i>&middot;</i>我的充值记录</a></dd>
       <dd id="userhub_xfView"><a href="'.geturl('system','userhub','SYS=method=xfView').'"><i>&middot;</i>我的消费记录</a></dd>
       <dd id="userhub_dyView"><a href="'.geturl('system','userhub','SYS=method=dyView').'"><i>&middot;</i>自动订阅设置</a></dd>
      </dl>
     </li>
     <li id="row"><a href="javascript:;"><em class="task">任务专区</em></a>
      <dl class="list_menu" style="display:none">
       <dd id="task_main"><a href="'.geturl('task','task','SYS=method=main').'"><i>&middot;</i>所有任务</a></dd>
       <dd id="task_czView"><a href="'.geturl('task','task','SYS=method=userfinished').'"><i>&middot;</i>已完成任务</a></dd>
      </dl>
     </li>     
<!--     <li id="row"><a href="javascript:void(0)"><em class="vip" >会员专区</em></a>
      <dl class="list_menu" style="display:none">
       <dd id="userhub_usermember"><a href="'.geturl('system','userhub','SYS=method=usermember').'"><i>&middot;</i>会员专区</a></dd>
       <dd id="userhub_uservip"><a href="'.geturl('system','userhub','SYS=method=uservip').'"><i>&middot;</i>VIP专区</a></dd>
      </dl>
     </li>-->
<!--     <li id="row"><a href="javascript:;"><em class="review" >我的书评</em></a>
      <dl class="list_menu" style="display:none;">
       <dd id="userhub_comment"><a href="'.geturl('system','userhub','SYS=method=comment').'"><i>&middot;</i>发表的书评</a></dd>
       <dd id="userhub_hotcomment"><a href="'.geturl('system','userhub','SYS=method=hotcomment').'"><i>&middot;</i>回复的书评</a></dd>
      </dl>
     </li>-->
	 ';
if($this->_tpl_vars['iswriter'] <= 0){
echo '
     	<li id="row"><a href="'.geturl('article','article','SYS=method=appwV').'"><em class="apply" >申请作者</em></a></li>
	  ';
}
echo '
	   <!--作品管理只对有权限的开放--> 
	  ';
if($this->_tpl_vars['iswriter'] > 0){
echo '<!--&& ($_REQUEST[\'controller\'] == \'userhub\' || $_REQUEST[\'controller\'] == \'article\' || $_REQUEST[\'controller\'] == \'chapter\')-->
	     <li id="row"><a href="javascript:void(0)"><em class="works">作品管理</em></a>
	      <dl class="list_menu" style="display:none">
	       <dd id="article_step1View"><a href="'.geturl('article','article','SYS=method=step1View').'"><i>&middot;</i>创建新书</a></dd>
	       		<!-- 如果当前作者没有创建的新书，此功能点不予显示 -->
	       		<dd id="article_masterPage"><a href="'.geturl('article','article','SYS=method=masterPage').'"><i>&middot;</i>我的作品库</a></dd>
	       		<dd id="chapter_newChapterView"><a href="'.geturl('article','chapter','SYS=method=newChapterView').'"><i>&middot;</i>快速增加章节</a></dd>
	       		<dd id="userhub_review_view"><a href="'.geturl('system','userhub','SYS=method=review_view').'"><i>&middot;</i>书评管理</a></dd>
	      </dl>
	     </li>
		 <li id="row"><a href="javascript:void(0)"><em class="income">收入管理</em></a>
	      <dl class="list_menu" style="display:none">
	      	<dd id="article_finance"><a href="'.geturl('article','article','SYS=method=finance').'"><i>&middot;</i>财务信息</a></dd>
			<dd id="article_income"><a href="'.geturl('article','article','SYS=method=income').'"><i>&middot;</i>收入月报</a></dd>
			<dd id="article_incomedetail"><a href="'.geturl('article','article','SYS=method=incomedetail').'"><i>&middot;</i>收入详情</a></dd>
			<!--<dd id="article_rewards"><a href="'.geturl('article','article','SYS=method=rewards').'"><i>&middot;</i>奖励保障</a></dd>-->
			<dd id="article_exceptional"><a href="'.geturl('article','article','SYS=method=exceptional').'"><i>&middot;</i>打赏明细</a></dd>
			<!--<dd id="article_channelIncome"><a href="'.geturl('article','article','SYS=method=channelIncome').'"><i>&middot;</i>渠道收入</a></dd>-->
	      </dl>
	     </li>
     ';
}
echo '
  </ul>
  <div class="kf"></div>
  </div><!--sidebar2 end-->';
?>