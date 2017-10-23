<?php
echo '<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/pop.css" type="text/css" rel="stylesheet" />
<!--pop4 begin-->
<div class="pop4" style="display:none;">
 <dl>
  <dt>充值遇到问题怎么办？</dt>
  <dd class="g6">充值完成前请不要关闭此窗口。完成充值后请根据你的情况点击下面的按钮：</dd>
  <dd class="b g3">请在新开网页上完成付款后再选择。</dd>
  <dd class="p_btn"><a href="'.geturl('system','userhub','SYS=method=czView').'">已完成充值</a><a href="/help/?wz=q23">充值遇到问题</a></dd>
  <dd><a href="'.geturl('pay','home').'" class="f_blue1">返回重新选择充值方式</a></dd>
 </dl>
</div><!--pop4 end-->
<script language="javascript">
function checkForm(form, openpop){
	if(getUserId()>0)
	{
		if(typeof($("dd.other input").val())!=\'undefined\'){
			var input_val = $("dd.other input").val();
			if($(\'dd.other input\').attr("disabled")==\'disabled\'){
			}else{
				if(input_val!=\'\'&&input_val<20){
					layer.msg(\'自定义金额最少20元\');
					return false;
				}
			}
		}
		if(openpop>0){
			$.layer({
//				shade : [0.5 , \'#000\' , true],
				type : 1,
				area : [\'550px\',\'auto\'],
				title : false,
				closeBtn: [0, true],
				border : [10 , 0.3 , \'#000\', true],
				zIndex : 1,
				page : {dom : \'.pop4\'}
			});
		}
	}else{
	    userLogin();
		return false;
	}
}
</script>';
?>