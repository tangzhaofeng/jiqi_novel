<?php
echo '<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'style/about.css" type="text/css"  />
<!--wrap begin-->
<div class="wrap fix bg4">
  <!--sidebar3 begin-->
  <div class="sidebar3 sidebar32 fl">
	<ul class="limenu">
	  <li>
		<h3><em class="author"></em>作者帮助</h3>
		<div>
		  <a id="hp0" href="javascript:;">·作品管理</a>
		  <a id="hp1" href="javascript:;">·签约</a>
		  <a id="hp2" href="javascript:;">·上架</a>
		  <a id="hp3" href="javascript:;">·其他问题</a>
		</div>
	  </li>
      <li>
		<h3><em class="reader"></em>读者帮助 </h3>
		<div>
		  <a id="hp4" href="javascript:;">·会员账号</a>
      	  <a id="hp5" href="javascript:;">·充值</a>
      	  <a id="hp6" href="javascript:;">·订阅</a>
      	  <a id="hp7" href="javascript:;">·月票</a>
      	  <a id="hp8" href="javascript:;">·书券</a>
     	</div>
	  </li>   
	</ul>
    <div class="kf"></div>
  </div><!--sidebar3 end-->
  <!--article6 begin-->
  <div class="article6 fix mt20">
	<div class="adorn1"></div>
	<div class="adorn2"></div>
	<ul id="conbox" class="about">
	  <!--Work Management begin-->
	  <li class="fix">
		<div class="boxtit rel">
		  <p class="zi">作品管理</p><p class="zi2">Work Management</p>
		  <span class="adorn"></span>
		</div>
		<div class="faq">
		  <h6 id="q1">如何创建作品？</h6>
		  <div class="txt">首先，申请成为本站作者，可进入“个人中心”，左侧导航栏上有“申请作者”。成为作者后，可在个人中心左侧导航栏上找到“作品管理”-“创建新书”，点击并按照提示进行下一步，完成创建。</div>
		  <h6 id="q2">如何发布章节？</h6>
		  <div class="txt">
			<dl class="pt10">有以下两种方法：
			  <dd>1、在“个人中心”左侧导航栏上找到“作品管理”，点击之后，选择“我的作品库”。可选择某一本书，点击“管理”，你可以根据自己的需求进行操作。</dd>
			  <dd>2、点击“作品管理”后，可直接选择“快速增加章节”，可直接增加分卷及章节。</dd>
			</dl>
		  </div>
		  <h6 id="q3">如何修改章节？</h6>
		  <div class="txt">进入“我的作品库”，选择作品，进入“管理”，点击修改对应章节，编辑完成后提交即可（注：章节仅在发文之时起7天内可以修改，且修改后的内容需经过审核才对外显示）。</div>
      	  <h6 id="q4">如何调整章节顺序？</h6>
      	  <div class="txt">进入“我的作品库”，选择作品，进入“管理”，页面下方“章节排序”。第一行选择被移动章节或分卷，第二行选择移动的位置，被移动章节或卷将移到下方章节（或分卷）以后，别忘记点击“确定”保存。</div>
      	  <h6 id="q5">如何删除卷？</h6>
      	  <div class="txt">从“我的作品库”进入某一本书的章节管理，可自由操作卷和章节。卷删除后，卷下级的章节自动移动到上一卷。</div>
      	  <h6 id="q6">如何修改作品信息？</h6>
      	  <div class="txt">进入“我的作品库”，选择某一本书，点击“编辑”，可以修改作品相关信息。</div>
      	  <h6 id="q7">如何使用定时发布功能？</h6>
      	  <div class="txt">章节和分卷均有定时发布的功能，点击“定时发布”右侧的输入框，在弹出的小日历上选择发布的具体日期和时间。注意，只能选择明天以后的时间进行发布，然后点击保存。（注：创建后，您可以通过修改本章节重新选择定时时间或取消定时）。</div>
		</div>
	  </li>
      <!--Work Management end-->
	  <!--signing begin-->
      <li class="fix" style="display:none;">
		<div class="boxtit rel"><p class="zi">签约</p><p class="zi2">signing</p></div>
		<div class="faq">
		  <h6 id="q8">如何申请签约？</h6>
		  <div class="txt">进入作者中心，在作者中心导航条上找到“申请签约”，点击后，请认真阅读签约要求。只要符合基本的字数要求，即可在下方选择自己的作品并提交申请。</div>
		  <h6 id="q9">为什么签约不成功？</h6>
		  <div class="txt">可能是以下原因：
			<dl class="pt10">
			  <dd>1、作品存在涉黄、涉政、凑字数等违规内容；</dd>
			  <dd>2、作品存在版权隐患，如一书多签或已授权给其他网站；</dd>
			  <dd>3、你在创作作品的道路上还需继续努力。</dd>
			</dl>
		  </div>
		  <h6 id="q10">签约后，作品是否可由'.$this->_tpl_vars['jieqi_sitename'].'网优先推荐出版？</h6>
		  <div class="txt">对于质量高、受读者欢迎的优秀作品，我们会优先推荐出版。</div>
		</div>
	  </li>
	  <!--signing end-->
	  <!--Added begin-->
	  <li class="fix" style="display:none;">
		<div class="boxtit rel">
		  <p class="zi">上架</p><p class="zi2">Added</p>
		  <span class="adorn"></span>
		</div>
		<div class="faq">
		  <h6 id="q11">如何申请上架？</h6>
		  <div class="txt">进入作者中心，在作者中心导航条上找到“申请上架”，点击后，请认真阅读上架要求及上架建议。只要符合基本的已签约和字数要求，即可在下方选择自己的作品并提交申请。</div>
		  <h6 id="q12">为什么上架不成功？</h6>
		  <div class="txt">可能是以下原因：
			<dl class="pt10">
			  <dd>1、作品存在涉黄、涉政、凑字数等违规内容；</dd>
       		  <dd>2、作品存在版权隐患，如一书多签或已授权给其他网站；</dd>
       		  <dd>3、你的作品在人气的累积上还不充分，暂不上架可以累积更多的人气，对未来上架有利。</dd>
			</dl>
		  </div>
		</div>
	  </li>
	  <!--Added end-->
	  <!--Other questions begin-->
      <li class="fix" style="display:none;">
		<div class="boxtit rel">
          <p class="zi">其他问题</p><p class="zi2">Other questions</p>
          <span class="adorn"></span>
     	</div>
		<div class="faq">
		  <h6 id="q13">如何查看稿酬？</h6>
		  <div class="txt">在作者中心导航条上找到“结算中心”，点击进入后，可以通过选择作品和时间段来查看（注：稿酬满百元，每月自动发放。未满百元自动累积）。</div>
		  <h6 id="q14">如何申请福利？</h6>
		  <div class="txt">福利在每月初的1-3日内向指定邮箱发送邮件申请，详细请阅读<a href="/fuli/" class="f_blue5">福利规则</a>。</div>
		  <h6 id="q15">为什么站内搜索不到我的作品？</h6>
		  <div class="txt">可能是以下原因：
      		<dl class="pt10">
       		  <dd>1、你的作品刚刚建立，还未通过审核，请耐心等待；</dd>
       		  <dd>2、你的作品涉嫌违规，已被管理员屏蔽，请与你的责编联系。</dd>
      		</dl>
		  </div>
      	  <h6 id="q16">为什么更新的章节未显示出来？</h6>
      	  <div class="txt">可能是以下原因：
      		<dl class="pt10">
       		  <dd>1、你的作品还未通过审核，待通过审核后即会显示；</dd>
       		  <dd>2、该章节还未通过审核，待审核无违规后即会显示；</dd>
       		  <dd>3、该章节由于涉嫌违规，已被管理员屏蔽，请查看个人信箱，按管理员的提示修改本章节后重新上传；</dd>
       		  <dd>4、如不是以上原因，那么可能是网站问题，请及时向本站客服反映，谢谢！</dd>
      		</dl>
      	  </div>
      	  <h6 id="q17">为什么自己不能传封面？</h6>
      	  <div class="txt">在作品签约后，封面将由本站美工设计并上传。如有自主上传封面的需求，请联系您的责编代为上传。</div>
      	  <h6 id="q18">其他作者问题</h6>
      	  <div class="txt">请及时向本站客服反映，我们会以最快的速度，尽最大的努力去解决，谢谢！</div>
		</div>
	  </li>
	  <!--Other questions end-->
	  <!--Member account begin-->
      <li class="fix" style="display:none;">
     	<div class="boxtit rel">
       	  <p class="zi">会员账号</p><p class="zi2">Member account</p>
       	  <span class="adorn"></span>
     	</div>
     	<div class="faq">
      	  <h6 id="q19">成为VIP会员有什么用？</h6>
      	  <div class="txt">充值成为VIP会员之后，可以订阅VIP章节。非VIP会员无法阅读VIP章节。</div>
      	  <h6 id="q20">为什么无法下载VIP章节？</h6>
      	  <div class="txt">您好，为保护作者权益，所有VIP章节不支持下载，仅支持在线阅读。请理解。</div>
      	  <h6 id="q21">账号和密码忘记怎么办？</h6>
      	  <div class="txt">如记得账号，请联系本站客服，客服查实后将为您重设密码；如已忘记账号，该账号将彻底丢失。微信公众号 pinshunet，客服QQ：724171887。</div>
      	  <h6 id="q22">账号防盗指南</h6>
      	  <div class="txt">为了更好的保护您的账号及您的账号信息安全，我们强烈建议您：
      		<dl class="pt10">
       		  <dd>1、请不要设置过于简单的密码，诸如：</dd>
       		  <dd>① “123456”这样过于简单连贯的数字或字母；</dd>
       		  <dd>② 与用户名相似度太高或完全相同的数字、字母、符号做为密码。</dd>
       		  <dd>2、请进行手机认证，方便随时找回密码、修改密码。</dd>
       		  <dd>3、定期对密码进行修改。</dd>
      		</dl>
      	  </div>
     	</div>
	  </li>
	  <!--Member account end-->
	  <!--Recharge begin-->
      <li class="fix" style="display:none;">
     	<div class="boxtit rel">
       	  <p class="zi">充值</p><p class="zi2">Recharge</p>
          <span class="adorn"></span>
     	</div>
     	<div class="faq">
      	  <h6 id="q23">在哪里充值？</h6>
      	  <div class="txt">
      		<dl class="pt10">
       		  <dd>1、本站对充值进行了优化，当需要充值或您金币不足时，“充值”按钮会自动呈现在您的面前；</dd>
       		  <dd>2、固定充值窗口如下图所示：</dd>
       		  <dd><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/chongzhi.jpg" /></dd>
      		</dl>
      	  </div>
      	  <h6 id="q24">充值后不到账怎么办？</h6>
      	  <div class="txt">如果30分钟内没有到账，请立即联系本站客服。微信公众号 pinshunet，客服QQ：724171887。<br/>'.$this->_tpl_vars['jieqi_sitename'].'币暂未到账可能是以下原因造成的：
      		<dl class="pt10">
       		  <dd>1、由于充值人数较多，所以充值通道拥挤，充值成功速度就会稍慢；</dd>
       		  <dd>2、数据提交上去之后，系统要对数据进行核对才可以给出成功或者失败的结果。</dd>
      		</dl>
      	  </div>
      	  <h6 id="q25">不会使用充值系统怎么办？</h6>
      	  <div class="txt">请联系本站客服指导您进行充值。微信公众号 pinshunet，客服QQ：724171887。</div>
      	  <h6 id="q26">充值成功，但剩余'.$this->_tpl_vars['jieqi_sitename'].'币数量没有变化？</h6>
      	  <div class="txt">解决办法：
      		<dl class="pt10">
       		  <dd>1、刷新当前页面，最好同时按下“Ctrl”键和“F5”键；</dd>
       		  <dd>2、退出后重新登录；</dd>
       		  <dd>3、如果以上2种方法无效，那么可能没有充值成功，请联系本站客服。微信公众号 pinshunet，客服QQ：724171887。</dd>
      		</dl>
      	  </div>
     	</div>
	  </li>
	  <!--Recharge end-->
	  <!--Subscription begin-->
      <li class="fix" style="display:none;">
     	<div class="boxtit rel">
       	  <p class="zi">订阅</p><p class="zi2">Subscription</p>
       	  <span class="adorn"></span>
     	</div>
     	<div class="faq">
      	  <h6 id="q27">为什么我无法阅读VIP章节？</h6>
      	  <div class="txt">VIP章节需要订阅后才能阅读。充值后，选中您要看的VIP章节并按提示支付'.$this->_tpl_vars['jieqi_sitename'].'币，随后即可阅读。</div>
      	  <h6 id="q28">VIP书籍怎么收费的？</h6>
      	  <div class="txt">本站VIP内容按照300字1个'.$this->_tpl_vars['jieqi_sitename'].'币进行收费,即每300字您需要支付0.01元,越高等级的VIP订阅折扣越大(<a href="/user/uservip" class="f_blue5">详情</a>)</div>
      	  <h6 id="q29">是不是充值后就可以免费阅读？</h6>
      	  <div class="txt">不是。VIP章节需要您订阅后才能阅读，且按照300字1'.$this->_tpl_vars['jieqi_sitename'].'币进行计费。</div>
      	  <h6 id="q30">购买的VIP章节无法阅读怎么办？</h6>
      	  <div class="txt">您可能遇到了系统问题，请联系本站客服解决。微信公众号 pinshunet，客服QQ：724171887。</div>
      	  <h6 id="q31">VIP阅读需要重新付费吗？</h6>
      	  <div class="txt">不需要。已经购买的VIP章节您可以随时免费阅读。</div>
      	  <h6 id="q32">如何查找我的订阅记录？</h6>
      	  <div class="txt">按以下步骤操作可以查到您的订阅记录。步骤为：个人中心 - 账务中心 - 我的消费记录。</div>
     	</div>
	  </li>
	  <!--Subscription end-->
	  <!--Vipvote begin-->
      <li class="fix" style="display:none;">
     	<div class="boxtit rel">
       	  <p class="zi">月票</p><p class="zi2">Vipvote</p>
       	  <span class="adorn"></span>
     	</div>
     	<div class="faq">
      	  <h6 id="q33">什么是月票？</h6>
      	  <div class="txt">月票是'.$this->_tpl_vars['jieqi_sitename'].'VIP会员特有的票种，用来评选'.$this->_tpl_vars['jieqi_sitename'].'签约作品。</div>
      	  <h6 id="q34">如何获得月票？</h6>
      	  <div class="txt">目前月票的获得方式有以下两种：
      		<dl class="pt10">
       		  <dd>1、VIP会员通过订阅章节消费，达到指定标准后根据VIP等级赠送月票（具体见<a href="'.geturl('system','userhub','SYS=method=uservip').'" class="f_blue5 b"> VIP专区</a>）；</dd>
       		  <dd>2、VIP会员单次打赏作品1000'.$this->_tpl_vars['jieqi_sitename'].'币，即获得1张消费月票，无上限。</dd>
       		  <dd>注：本月获得的消费月票将于下一个月增加到用户账户中。</dd>
      		</dl>
      	  </div>
      	  <h6 id="q35">月票的使用方法？</h6>
      	  <div class="txt">
      		<dl class="pt10">
       		  <dd>1、只能对'.$this->_tpl_vars['jieqi_sitename'].'网已签约的作品投月票；</dd>
       		  <dd>2、可从作品详情页面对作品投月票，投票位置如下图所示：</dd>
       		  <dd><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/yuepiao.png" width="600" /></dd>
       		  <dd>3、投月票时可选择需要投的票数进行投票，目前VIP会员对单本作品每月最多可投2张月票；</dd>
       		  <dd>4、所有月票（包含保底和消费月票）一律当月有效，过期作废，不可以延期使用。</dd>
      		</dl>
      	  </div>
      	  <h6 id="q36">月票的评选？</h6>
      	  <div class="txt">参加评选的作品为'.$this->_tpl_vars['jieqi_sitename'].'网所有已签约作品，每月的月票榜前10名作品会获得'.$this->_tpl_vars['jieqi_sitename'].'网的奖励。详情参见：<a href="/fuli/" class="f_blue5 b">作者福利</a>——月票奖。</div>
      	  <h6 id="q37">月票的监管规则？</h6>
      	  <div class="txt">
      		<dl class="pt10">
       		  <dd>1、为了维护'.$this->_tpl_vars['jieqi_sitename'].'网榜单的公平与公正，'.$this->_tpl_vars['jieqi_sitename'].'严禁对作品违规刷数据的行为，其中包括不允许对作品刷月票。'.$this->_tpl_vars['jieqi_sitename'].'会对榜单作品的数据进行程序和人工结合的方式进行判断分析是否有刷数据的行为。</dd>
       		  <dd>2、如果'.$this->_tpl_vars['jieqi_sitename'].'发现某部作品存在刷月票现象，则取消该作品参与当月月票榜奖励评选的权利，并对刷票的账号予以封禁投月票权限，甚至封禁登录权限。如果用户存在疑问，可联系客服进行咨询。</dd>
      		</dl>
      	  </div>
     	</div>
	  </li>
	  <!--Vipvote end-->
	  <!--Coupon book begin-->
	  <li class="fix" style="display:none;">
        <div class="boxtit rel">
          <p class="zi">书券</p><p class="zi2">Coupon book</p>
          <span class="adorn"></span>
        </div>
      	<div class="faq">
          <h6 id="q38">什么是书券？</h6>
      	  <div class="txt">书券是'.$this->_tpl_vars['jieqi_sitename'].'网新增的一种虚拟币，与'.$this->_tpl_vars['jieqi_sitename'].'币等值，可用于VIP章节订阅。</div>
      	  <h6 id="q39">书券的使用范围</h6>
      	  <div class="txt">书券在'.$this->_tpl_vars['jieqi_sitename'].'全站通用（包括'.$this->_tpl_vars['jieqi_sitename'].'网、Wap站和手机客户端），但受以下使用限制：
        	<dl class="pt10">
          	  <dd>1.书券仅能用于章节订阅，不支持作品打赏等消费功能；</dd>
          	  <dd>2.使用书券订阅将无法获得订阅月票；</dd>
          	  <dd>3.使用书券购买VIP章节，将不会获得会员积分。</dd>
        	</dl>
      	  </div>
      	  <h6 id="q40">如何获得书券？</h6>
      	  <div class="txt">目前，'.$this->_tpl_vars['jieqi_sitename'].'网用户可以通过以下途径来获得书券：
        	<dl class="pt10">
              <dd>1.VIP会员升级赠送书券</dd>
          	  <dd class="pl15">VIP0级升级为VIP1级，赠送50书券；</dd>
          	  <dd class="pl15">VIP1级升级为VIP2级，赠送250书券；</dd>
          	  <dd class="pl15">VIP2级升级为VIP3级，赠送1000书券；</dd>
          	  <dd class="pl15">VIP3级升级为VIP4级，赠送2000书券；</dd>
          	  <dd class="pl15">VIP4级升级为VIP5级，赠送3500书券；</dd>
          	  <dd class="pl15">VIP5级升级为VIP6级，赠送5000书券；</dd>
          	  <dd>2.根据上月的'.$this->_tpl_vars['jieqi_sitename'].'币消费额度，下一月返还书券</dd>
          	  <dd class="pl15">计算公式：本月返还书券数额s=上月'.$this->_tpl_vars['jieqi_sitename'].'币消费额度m*5%</dd>
          	  <dd class="pl15">注：消费包括订阅和打赏。</dd>
          	  <dd>3.每月签到奖励书券</dd>
          	  <dd class="pl15">每个月在个人中心签到，累计一定的签到天数即可获得书券奖励。</dd>
          	  <dd class="pl15">累计签到满7天，奖励20书券；累计签到满15天，奖励30书券；累计签到一个月，奖励50书券。</dd>
          	  <dd>4.成为'.$this->_tpl_vars['jieqi_sitename'].'网签约作者，赠送5000书券</dd>
          	  <dd class="pl15">只要您申请成为'.$this->_tpl_vars['jieqi_sitename'].'网的签约作者，就可以获得5000书券的奖励。</dd>
          	  <dd>5.参加特定活动获得书券</dd>
          	  <dd class="pl15">'.$this->_tpl_vars['jieqi_sitename'].'网会定期举行各种内容丰富的活动，只要参加这些活动，就有机会获得丰厚的书券奖励。</dd>
          	  <dd class="pl15">书券可通过完成任务的形式来获取，达成任务完成条件后，在“个人中心—任务专区”点击按钮完成任务，就可以获得相应的书券奖励。</dd>
        	</dl>
      	  </div>
      	  <h6 id="q41">书券的使用方法</h6>
      	  <div class="txt">书券仅可用于VIP章节订阅，规则如下：
      		<dl class="pt10">
      	  	  <dd>1.订阅前10章VIP章节，默认优先使用书券抵扣支付，若书券余额不足，则差额用'.$this->_tpl_vars['jieqi_sitename'].'币补齐；</dd>
      	  	  <dd>2.前10章以外的VIP章节，在订阅时，'.$this->_tpl_vars['jieqi_sitename'].'币和书券按照一定比例扣除；若书券余额不足，则用'.$this->_tpl_vars['jieqi_sitename'].'币购买。</dd>
      		</dl>
      	  </div>
        </div>
	  </li>
	  <!--Coupon book end-->
	</ul>
  </div><!--article6 end-->
</div><!--wrap end-->
<script type="text/javascript">
  if("'.$this->_tpl_vars['_REQUEST']['wz'].'"!=""){
	$("div.faq h6").each(function(){
	  if($(this).attr("id")=="'.$this->_tpl_vars['_REQUEST']['wz'].'"){
		$(this).next().show().siblings("div.txt").hide();
		$("#conbox li").hide();
		var activeli = $(this).parents("li");
		$(activeli).show().siblings().hide();
		var hp_id = "hp"+$(activeli).index();
		$("ul.limenu li div a").each(function(){
		  if($(this).attr("id")==hp_id){
			$(this).addClass("on").parents().show();
		  }
		});
	  }
	});
  }else{
	$("ul.limenu li:first").find("div").show();
	$("#hp0").addClass("on");
	$("div.faq div.txt").hide();
	$("div.faq div.txt:first").show();
  }
  
  $("ul.limenu li h3").click(function(){
	$(this).next().toggle();
  });
  
  $("a[id^=\'hp\']").click(function(){
	$("a[id^=\'hp\']").removeClass("on");
	$(this).addClass("on"); 
	var activeindex = $(this).attr("id").substring(2);
	var active = $(\'#conbox\').children().eq(activeindex);
	$(active).show().siblings().hide();
	$(active).find("div.txt:first").show();
  });
  
  $("div.faq h6").click(function(){
	$(this).next().toggle();
  });
</script>';
?>