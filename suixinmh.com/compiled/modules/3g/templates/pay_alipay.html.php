<?php
echo '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
    <title>支付宝充值</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
    <meta http-equiv="Cache-Control" content="no-transform " />
    <meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
    <meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
    <meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
    <meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'"/>
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css"/>
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/extend.css"/>
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css"/>
    ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/js.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
</head>
<body>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
    ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/paylogin.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
    <div class="bgcfff">
        <div class="p10 clearfix bb bceee">
            选择充值金额<a href="/user/"><span class="fr cPink">个人中心</span></a>
        </div>
        <div class="checkbox mt10  ptb10 clearfix pay_alipay_money">
            <form action="'.geturl('3g','pay','SYS=method=alipay').'" method="post" id="payform">
                ';
if (empty($this->_tpl_vars['paylimit'])) $this->_tpl_vars['paylimit'] = array();
elseif (!is_array($this->_tpl_vars['paylimit'])) $this->_tpl_vars['paylimit'] = (array)$this->_tpl_vars['paylimit'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['paylimit']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['paylimit']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['paylimit']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['paylimit']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['paylimit']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	if($this->_tpl_vars['i']['key']>$this->_tpl_vars['paylimit'][$this->_tpl_vars['i']['key']]*100){$this->_tpl_vars['duo']=$this->_tpl_vars['i']['key']-$this->_tpl_vars['paylimit'][$this->_tpl_vars['i']['key']]*100;}else{$this->_tpl_vars['duo']=0;} 
echo '
                ';
if($this->_tpl_vars['paylimit'][$this->_tpl_vars['i']['key']] == 365){
echo '
                <a data-egold="'.$this->_tpl_vars['i']['key'].'"  href="javascript:;" onclick="pay('.$this->_tpl_vars['i']['key'].')">
                    <p><span>包年VIP '.$this->_tpl_vars['paylimit'][$this->_tpl_vars['i']['key']].' 元</span></p>
                    <p class="f12">有效期365天，全站书籍不限量阅读</p>
                </a>
                ';
}else{
echo '
                <a data-egold="'.$this->_tpl_vars['i']['key'].'"  href="javascript:;" onClick="pay('.$this->_tpl_vars['i']['key'].')">
                    <p><span>'.$this->_tpl_vars['paylimit'][$this->_tpl_vars['i']['key']].'元</span></p>
                    <p class="f12">';
echo $this->_tpl_vars['paylimit'][$this->_tpl_vars['i']['key']]*100+$this->_tpl_vars['duo']; 
echo '书币</p>
                    <!--<img class="pa right0 bottom0 w15" src="'.$this->_tpl_vars['jieqi_themeurl'].'images/sright.png" />-->
                </a>
                ';
}
echo '
                ';
}
echo '
                <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '">
                <input type="hidden" name="egold" id="egold" value="" />
            </form>
            </div>
            ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/3g/templates/pay_notify.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
        </div>




';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<script type="text/javascript">
function pay(gold){
    document.getElementById(\'egold\').value=gold;
    document.getElementById(\'payform\').submit();
}
</script>
<body>';
?>