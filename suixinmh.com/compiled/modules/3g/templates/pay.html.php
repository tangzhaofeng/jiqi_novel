<?php
echo '<!DOCTYPE html>
<html>

    <head>
        <meta charset="'.$this->_tpl_vars['jieqi_charset'].'">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <title>充值中心</title>
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
        <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/extend.css">
        <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css">
        <style>
            .cardtype ul{width: 90%;margin: 0 auto;
                display: -webkit-flex;
                display: flex;
                justify-content:center;
                
            }
            .cardtype ul li {
                width: 33.33333%;
                text-align: center;
                
            }
            
            .cardtype ul li a {
                display:block;
                width: 90%;
                max-width:70px;
                max-height: 70px;
                margin: 0 auto;
                border-radius: 5px;
                border: #ddd 1px solid;
                overflow: hidden;
            }
            
            .cardtype ul li a img {
                width: 100%;
            }
            .cardtype ul li p {
                margin-top: 5px;
                margin-bottom: 10px;
            }
        </style>
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
        <div class="clearfix cardtype bgcfff m10 ptb10 br5">
            <h2 class="fwn f14 mb15 tc lh35">请点击以下图标选择支付方式</h2>
            <ul class="clearfix">
                <li>
                    <a href="'.geturl('3g','pay','SYS=method=alipay').'"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/pay1.jpg" alt="" /></a>
                    <p>支付宝</p>
                </li>
                <li>
                    <a href="'.geturl('3g','pay','SYS=method=wechat').'"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/pay2.jpg" alt="" /></a>
                    <p>微信支付</p>
                </li>
             
            </ul>
        </div>
        <div class="b bcddd bgcfff m10 br5 p10 lh25 f14 c888">
            <b class="db f15">温馨提示：</b>
            <p>支付失败怎么办？
             长按下方二维码关注我们的公众号，截图并留言，我们会有专门的客服人员为您解答</p>
        </div>
        <div class="c999 tc f14 mt15">↓关注官方微信，方便下次阅读↓</div>
        <div class="tc mt10"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'img/weixin.jpg" alt="关注我们" style="max-width:60%;height:auto;"></div>
        <div class="c999 tc f12 mt5 mb15">微信内可长按识别</div>
        <!--footer-->
      ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
    </body>

</html>';
?>