<?php
echo '<script>
function tmpjiage(v){	
	$("dd[id^=\'v_\']").removeClass();
	$(\'#v_\'+v).addClass("hover");
	$(\'#egold\').val(v);
	$(\'#money_yuan\').val($(\'#v_\'+v).attr(\'money_yuan\'));
}
$(document).ready(function(){
	var id = \''.$this->_tpl_vars['_REQUEST']["method"].'\';
	if(id){
		$("#p_yeepay").removeClass();
		$(\'#p_\'+id).addClass("thistab");
	}
});
</script>

	<ul class="st fix" >
	  <li id="p_yeepay"><a href="'.geturl('pay','home','SYS=method=yeepay').'">网银支付</a></li>
	  <li id="p_alipay"><a href="'.geturl('pay','home','SYS=method=alipay').'">支付宝支付</a></li>
	  <li id="p_txfpay"><a href="'.geturl('pay','home','SYS=method=txfpay').'">短信充值</a></li>
	  <li id="p_cardpay"><a href="'.geturl('pay','home','SYS=method=cardpay').'">手机充值卡</a></li>
	  <li id="p_gcardpay"><a href="'.geturl('pay','home','SYS=method=gcardpay').'">游戏点卡</a></li>
	  <li id="p_qcardpay"><a href="'.geturl('pay','home','SYS=method=qcardpay').'">Q币支付</a></li>
	  <li id="p_paypal"><a href="'.geturl('pay','home','SYS=method=paypal').'">PayPal支付</a></li>
	  <li id="p_wftpay"><a href="'.geturl('pay','home','SYS=method=wftpay').'">微信支付</a></li>
	</ul>
';
?>