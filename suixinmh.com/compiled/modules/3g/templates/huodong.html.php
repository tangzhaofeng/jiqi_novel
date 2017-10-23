<?php
$this->_tpl_vars['articleid']=$this->_tpl_vars['article']['articleid']; 
echo '
';
if($this->_tpl_vars['_REQUEST']['method']=='vipvote' || $this->_tpl_vars['_REQUEST']['method']=='main'){
echo '
  <div class="yuep">
	<p class="surplus">账户余额:<em class="b"> '.$this->_tpl_vars['egolds'].' </em>';
echo JIEQI_EGOLD_NAME; 
echo '<a href="'.geturl('3g','pay').'" class="fr f-org2">充值</a></p>
	<p class="t">这本书写的实在太好了，我决定投月票</p>
	<form name="vipvoteform" id="vipvoteform" action="'.geturl('3g','huodong','SYS=method=vipvote&aid='.$this->_tpl_vars['articleid'].'').'" method="post">
	  <dl class="group3-6 clearfix">
	    <dd class="col-x-4 current"><a href="javascript:;" role="button" media="handheld" onclick="act(\'\',this)" id="1" >1张月票</a></dd>
	    <dd class="col-x-4"><a href="javascript:;" role="button" media="handheld" onclick="act(\'\',this)" id="2" >2张月票</a></dd>
	    <dd class="dwn"><button type="submit" class="btn01 btn-blue2">确认投票</button></dd>
	  </dl>
	  <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '">
	  <input type="hidden" name="stat" id="current" value="1"/>
	</form>
	<dl class="li-txt">
	  <dt>温馨提示：</dt>
	  <dd>您当前的总月票数<em class="series"> '.$this->_tpl_vars['maxvote'].' </em>张。本月您已使用<em class="series"> '.$this->_tpl_vars['pollnum'].' </em>张月票，还可投<em class="series"> ';
echo $this->_tpl_vars['maxvote']-$this->_tpl_vars['pollnum']; 
echo ' </em>张</dd>
	  <dd>对单本作品投月票，投票上限为<em class="series"> 2 </em>张；</dd>
	  <dd>本月您已对当前作品投过<em class="series"> '.$this->_tpl_vars['yitou'].' </em>张月票，还可投<em class="series"> ';
echo 2-$this->_tpl_vars['yitou']; 
echo ' </em>张；</dd>
	  <dd>每投1张月票，您即可获得<em class="series"> '.$this->_tpl_vars['getscore'].' </em>积分。</dd>
	  <dd>每一次性打赏本书<em class="series"> 1000 </em>'.$this->_tpl_vars['egoldname'].'，即增加1张消费月票，没有上限。</dd>
	  <dd><a href="'.geturl('3g','help').'" target="_blank" class="f_blue5">查看月票获得方法与使用规则>> </a></dd>
	  <!-- <dd>您当前的总月票数<em class="red"> '.$this->_tpl_vars['maxvote'].' </em>张</dd>
	  <dd>对单本作品投月票，投票上限为<em class="red"> ';
if($this->_tpl_vars['maxvote'] > 0){
echo $this->_tpl_vars['maxvote'].' ';
}else{
echo ' 2';
}
echo '</em>张</dd>
	  <dd>本月您已使用<em class="red"> '.$this->_tpl_vars['pollnum'].' </em>张月票，还可投<em class="red"> ';
echo $this->_tpl_vars['maxvote']-$this->_tpl_vars['pollnum']; 
echo ' </em>张</dd>
	  <dd>每投1张月票，您即可获得<em class="red"> '.$this->_tpl_vars['getscore'].' </em>积分。</dd>
	  <dd>每打赏本书<em class="red"> 1000 </em>'.$this->_tpl_vars['egoldname'].'，可获得1张消费月票，没有上限。</dd> -->
	</dl>
  </div><!-- 订阅结束 -->
';
}elseif($this->_tpl_vars['_REQUEST']['method']=='vote'){
echo '
  <div class="tuij">
	<p class="surplus">账户余额:<em class="b"> '.$this->_tpl_vars['egolds'].' </em>';
echo JIEQI_EGOLD_NAME; 
echo '<a href="/pay" class="fr f-org2">充值</a></p>
	<form name="voteform" id="voteform" action="'.geturl('3g','huodong','SYS=method=vote&aid='.$this->_tpl_vars['articleid'].'').'" method="post">
	  <dl class="group3-6 clearfix">
	    <dt class="t">这本书写的实在太好了，我决定投推荐票</dt>
	    <dd class="col-x-4 current2"><a href="javascript:;" role="button" media="handheld" onclick="act(2,this)" id="1">1张票</a></dd>
		<dd class="col-x-4"><a href="javascript:;" role="button" media="handheld" onclick="act(2,this)" id="2">2张票</a></dd>
		<dd class="col-x-4"><a href="javascript:;" role="button" media="handheld" onclick="act(2,this)" id="3">3张票</a></dd>
		<dd class="col-x-4"><a href="javascript:;" role="button" media="handheld" onclick="act(2,this)" id="4">4张票</a></dd>
		<dd class="col-x-4"><a href="javascript:;" role="button" media="handheld" onclick="act(2,this)" id="5">5张票</a></dd>
		<dd class="col-x-4"><a href="javascript:;" role="button" media="handheld" onclick="act(2,this)" id="all">全部票</a></dd>
	  	<dd class="dwn"><button type="submit" class="btn01 btn-org3">确认投票</button></dd>
	  </dl>
	  <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" />
	  <input type="hidden" name="stat" id="current2" value="1"/>
	</form>
	<dl class="li-txt">
	  <dt>温馨提示：</dt>
	  <dd>您每天能投推荐票为<em class="series"> '.$this->_tpl_vars['maxvote'].' </em>张；今天已经使用<em class="series"> '.$this->_tpl_vars['pollnum'].' </em>张；</dd>
	  ';
if(($this->_tpl_vars['maxvote']-$this->_tpl_vars['pollnum'])>0) { 
echo '<dd>您今天剩余推荐票数为<em class="series"> ';
echo $this->_tpl_vars['maxvote']-$this->_tpl_vars['pollnum']; 
echo ' </em>张</dd>';
}else{ 
echo '
	 <dd class="series">抱歉，您今天的推荐票已用完，明天再来吧</dd>';
} 
echo '
	  <dd>每次1张推荐票，您即可获得<em class="series"> '.$this->_tpl_vars['getscore'].' </em>积分。</dd>
	  <dd class="series"><a href="'.geturl('3g','help').'" target="_blank" class="f_blue4">如何获得推荐票？</a></dd>
	  <!-- <dd>每天投票上限为<em class="red"> '.$this->_tpl_vars['maxvote'].' </em>张</dd>
	  <dd>您今天已经使用<em class="red"> '.$this->_tpl_vars['pollnum'].' </em>张</dd>
	  ';
$this->_tpl_vars['num'] = $this->_tpl_vars['maxvote']-$this->_tpl_vars['pollnum']; 
echo '
	  ';
if($this->_tpl_vars['num'] > 0){
echo '
	  <dd>您今天剩余推荐票数为<em class="red"> '.$this->_tpl_vars['num'].' </em>张</dd>
	  ';
}else{
echo '
	  <dd class="red">抱歉，您今天的推荐票已用完，明天再来吧！</dd>
	  ';
}
echo '
	  <dd>每次1张推荐票，您即可获得<em class="red"> '.$this->_tpl_vars['getscore'].' </em>积分。</dd> -->
	</dl>
  </div><!-- 推荐结束 -->
';
}elseif($this->_tpl_vars['_REQUEST']['method']=='reward'){
echo '
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/3g/templates/reward.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
}
echo '
<script>
  $(function(){
    $(\'form\').on(\'submit\', function(e){
   	  e.preventDefault();
	  GPage.postForm(this.name, this.action,function(data){
		$("#tab_box1 *").remove();
  		layer.open({
		    content: data.msg,
		    btn: [\'OK\'],
//		    shadeClose: function(){
//		    	window.location.reload();
//		    },
		    yes: function(){
		    	window.location.reload();
		    }
		});
	  });
 	});
  });
</script>';
?>