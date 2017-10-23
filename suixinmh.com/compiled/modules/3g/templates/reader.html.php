<?php
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="'.$this->_tpl_vars['jieqi_charset'].'">
    <title>'.$this->_tpl_vars['chapter']['title'].'-'.$this->_tpl_vars['article']['articlename'].'</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
    <meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
    <meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
    <meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
    <meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/extend.css">
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'fonts/iconfont.css"/>
    <link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/animate.min.css">
    <style>
        .attention{
            width:95%;
            margin:0 auto;
            background: #fff;
            border-radius: 10px;
            margin-top: 10px;
            height:80px;
            overflow: hidden;
        }
        .attention_l,.attention_r{
            float: left;
        }
        .attention_l{
            width:20%;
            height: 100%;
        }
        .attention_r{
            width:80%;
            height: 100%;
            position: relative;
        }
        .attention_l img{
            display: block;
            width: 75px;
            height: 75px;
            margin: 0 auto;
            border-radius: 50%;
            margin-top: 5px;
        }
        .attention_r p{
            width:75%;
            padding-top:21px;
            height:100%;
        }
        .attention_r a{
            position: absolute;
            top: 22px;
            right: 10px;
            /* padding: 10px; */
            height: 35px;
            line-height: 35px;
            text-align: center;
            width: 18%;
            border-radius: 5px;
            background: #fff;
            font-size: 16px;
            color: #14ed56;
            border: 1px solid #14ed56;
        }
        .qr{

            display: block;

            text-align: center;
        }
        .qr p {
            color: #3a3838;
            font-size: 16px;
            font-weight: bold;
            height: 30px;
            line-height: 30px;
        }
        .qr p.p2{
            color: #FF5722;
            cursor: pointer;
        }
        .qr span{
            color: #080808;
            font-weight: 700;
        }
        .qr i{
            font-style: normal;
            color:#F44336;
        }
        /*二维码样式*/
        .weix{
            width: 200px;
            height: 200px;
            background: #fff;
            border-radius: 10px;
            position: fixed;
            z-index: 999999999999;
            left: 50%;
            top: 50%;
            margin-left: -100px;
            margin-top: -100px;
            border: 1px solid #cecbd1;
        }
        .weix i{
            position: absolute;
            font-size:16px;
            height:30px;
            width:30px;
            top:-10px;
            background:rgba(0,0,0,0.8);
            border-radius: 50%;
            color:#fff;
            right:-10px;
            text-align:center;
            line-height: 30px;
        }
        .weix img{
            margin: 0 auto;
            display: block;
            margin-top: 20px;
        }
        .weix p{
            color:#504d4d;
            font-size:14px;
            text-align: center;
        }
    </style>
</head>
<body oncontextmenu="return false" onselectstart="return false" ondragstart="return false" onbeforecopy="return false" oncopy=document.selection.empty() onselect=document.selection.empty()>
<div class="mlf10 pb20">
    <!--the name of artical-->
    <div class="clearfix lh40 bb bcddd read_title"><b class="f18 cRed">'.truncate($this->_tpl_vars['chapter']['chaptername'],'26','…').'</b>
    </div>
    <div class="config">
        <ul class="bg_bar clearfix">
            <li class="li_1 active"><a href="javascript:void(0)" title="浅灰"></a>
            </li>
            <li class="li_2"><a href="javascript:void(0)" title="护眼"></a>
            </li>
            <li class="li_3"><a href="javascript:void(0)" title="粉色"></a>
            </li>
            <li class="li_4"><a href="javascript:void(0)" title="淡黄"></a>
            </li>
            <li class="night">
                <span><i style="background-position: 0% 100%;"></i></span>
            </li>
            <li>
                <span class="role-inc aadd">+A</span>
            </li>
            <li>
                <span class="role-des aminus">-A</span>
            </li>
        </ul>
    </div>
    <div class="clearfix lh30">
        <div class="fl c999 f12 read_title">发布:'.date('m-d H:i',$this->_tpl_vars['chapter']['postdate']).' | ';
echo (ceil($this->_tpl_vars['chapter']['size']/2)); 
echo '字</div>
    </div>
    <!--con-->
    <div id="content" class="lh25 c333 f14">

        ';
echo preg_replace("/<p>\r\n<p>/",'<p>',str_replace('&nbsp;&nbsp;&nbsp;&nbsp;','<p>',preg_replace("/<br \/>\r\n<br \/>/","</p>\r\n<p>",$this->_tpl_vars['chapter']['content']))); 
echo '</p>

        ';
if($this->_tpl_vars['block']==0){
echo '
        ';
if($this->_tpl_vars['article']['sortid']<100){
echo ' '.jieqi_geturl('system', 'tags', array('id'=>345, 'name'=>'%5B3g%5D%5B%D4%C4%B6%C1%D2%B3%5D%CD%C6%BC%F6%D4%C4%B6%C1-%C4%D0%C6%B5')).'
        ';
}else if($this->_tpl_vars['article']['sortid']>100 && $this->_tpl_vars['article']['sortid']<200){
echo ' '.jieqi_geturl('system', 'tags', array('id'=>347, 'name'=>'%5B3g%5D%5B%D4%C4%B6%C1%D2%B3%5D%CD%C6%BC%F6%D4%C4%B6%C1-%C5%AE%C6%B5')).'
        ';
}else{
echo ' '.jieqi_geturl('system', 'tags', array('id'=>345, 'name'=>'%5B3g%5D%5B%D4%C4%B6%C1%D2%B3%5D%CD%C6%BC%F6%D4%C4%B6%C1-%C4%D0%C6%B5')).' '.jieqi_geturl('system', 'tags', array('id'=>347, 'name'=>'%5B3g%5D%5B%D4%C4%B6%C1%D2%B3%5D%CD%C6%BC%F6%D4%C4%B6%C1-%C5%AE%C6%B5'));
}
echo '
        ';
}
echo '
    </div>
    ';
if($this->_tpl_vars['block']==0){
echo '
    <div class="weix animated fadeIn" style="display:none;">
        <i class="colse">X</i>
        <img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/weixin.jpg" alt="">
        <p>微信内可长按识别</p>
    </div>
    ';
}
echo '

    ';
if($this->_tpl_vars['block']==0){
echo '
    ';
if($this->_tpl_vars['show_qrcode']==1){
echo '
    <div class="c999 tc f14 mt15">↓关注官方微信，方便下次阅读↓</div>
    <div class="tc mt10"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'images/weixin.jpg" alt="关注我们" style="max-width:60%;height:auto;"></div>
    <div class="c999 tc f12 mt5">微信内可长按识别</div>
    ';
}
echo '
    ';
if($this->_tpl_vars['show_qrcode']==2){
echo '
    <div class="qr">
        <p><span>【继续阅读】</span><i>↓</i>亲，为了方便后续阅读请<i>↓</i></p>
        <p class="p2"><strong>&gt;&gt;</strong>点击这里关注我们的微信公众号<strong>&lt;&lt;</strong></p>
    </div>
    ';
}
echo '
    ';
}else{
echo '
    ';
if($this->_tpl_vars['blockpic']<>''){
echo '
    <div class="c999 tc f14 mt15">由于篇幅限制，本页面只能更新到这！</div>
    <div class="c999 tc f12 mt5"><span style="color: red;text-align:center;display:block;">↓长按识别二维码关注公众号，继续查看后续精彩内容↓</span></div>
    <div class="tc mt10"><img src="'.$this->_tpl_vars['blockpic'].'" alt="关注我们" style="max-width:60%;height:auto;"></div>
    <div class="c999 tc f12 mt5"><span style="color: red;text-align:center;display:block;">长按识别关注公众号，继续查看后续精彩内容</span></div>
    ';
}
echo '
    ';
}
echo '

    ';
if($this->_tpl_vars['blockpic']==''){
echo '
    <div class="ptb10 clearfix">
        <ul>
            ';
if($this->_tpl_vars['block']==0){
echo '
            <li class="wp40 tc fl">
                <a class="dib wp60 p7 br5 b bcaaa  c666" href="'.$this->_tpl_vars['chapter']['preview_page'].'">
                    上一章</a>
            </li>
            <li class="wp20 tc fl">
                <a class="dib wp60 p7 br5 b bcddd c666" href="'.geturl('3g','huodong','SYS=method=addBookCase&aid='.$this->_tpl_vars['article']['articleid'].'&cid='.$this->_tpl_vars['chapter']['chapterid'].'').'">
                    收藏</a>
            </li>
            <li class="wp40 tc fl">
                <a class="dib wp60 p7 br5 b bcaaa c666"  href="'.$this->_tpl_vars['chapter']['next_page'].'">下一章 </a>
            </li>
            ';
}else{
echo '
            <li class="wp50 tc fl">
                <a class="dib wp60 p7 br5 b bcaaa  c666" href="'.$this->_tpl_vars['chapter']['preview_page'].'">
                    上一章</a>
            </li>
            <li class="wp50 tc fl">
                <a class="dib wp60 p7 br5 b bcaaa c666"  href="'.$this->_tpl_vars['chapter']['next_page'].'">下一章 </a>
            </li>
            ';
}
echo '
        </ul>
    </div>
    ';
}
echo '
    ';
if($this->_tpl_vars['block']==0){
echo '
    <div class="f14 bt bb bcddd ptb10">
        <ul class="clearfix tc">
            <li class="fl wpT30"><a href="/" class="db">首页</a></li>
            <li class="fl wpT30 bl br bcddd"><a href="'.geturl('3g','catalog','SYS=aid='.$this->_tpl_vars['article']['articleid'].'').'" class="db">目录</a></li>
            <li class="fl wpT30"><a href="'.geturl('3g','articleinfo','SYS=aid='.$this->_tpl_vars['article']['articleid'].'').'" class="db">书页</a></li>
        </ul>
    </div>
    '.jieqi_geturl('system', 'tags', array('id'=>371, 'name'=>'%5B3g%5D%5B%D4%C4%B6%C1%D2%B3%5D%CD%C6%BC%F6%D4%C4%B6%C1')).'
    ';
}
echo '
</div>


';
require_once './../../cs.php';echo '<img src="'._cnzzTrackPageView(1258827042).'" width="0" height="0"/>'; 
echo '
</body>
<script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/jquery.min.js"></script>
<script>
    $(function(){
        //设置之前的状态
        var beijing=localStorage.getItem(\'beijing\');
        if(beijing!=null&&beijing!=""&&beijing!=undefined){
            $(\'body\').addClass(beijing);
            var arr=beijing.split("_");
            if(arr[2]==5){
                $(".bg_bar li").eq(4).removeClass().addClass("dark").find("i").css("background-position","0 0");
            }
            else{
                $(".bg_bar .li_"+arr[2]).siblings().removeClass("active");
                $(".bg_bar .li_"+arr[2]).addClass("active");
            }
        }
        //初始化字体大小
        var fontSize=localStorage.getItem(\'fontSize\')||14;
        setfontSize(fontSize);
        //切换颜色和字体大小的调整
        $(".bg_bar li").click(function(){
            var index=$(this).index()+1;
            switch(index){
                case 5:
                    var className=$(this).prop("className");
                    if(className=="night"){
                        $(this).find("i").css("background-position","0 0");
                        $(this).removeClass().addClass("dark");
                    }
                    else{
                        $(this).find("i").css("background-position","0 100%");
                        $(this).removeClass().addClass("night");
                        index=1;
                    }
                    $(this).siblings().removeClass("active");
                    setBackgrond(index);
                    break;
                case 6:
                    fontSize++;
                    if(fontSize<=28)
                    {
                        setfontSize(fontSize);
                    }
                    else{
                        fontSize=28;
                    }
                    break;
                case 7:
                    fontSize--;
                    if(fontSize>=14)
                    {
                        setfontSize(fontSize);
                    }
                    else{
                        fontSize=14;
                    }
                    break;
                default:
                    $(this).siblings().removeClass("active");
                    $(this).addClass("active");
                    setBackgrond(index);
            }
            return false;
        });
        function setfontSize(fontSize){
            $("#content").removeClass().addClass("lh"+(5+fontSize)+" c333 f"+fontSize);
            localStorage.setItem(\'fontSize\',fontSize);
        }
        function setBackgrond(index){
            $(\'body\').removeClass().addClass(\'read_style_\'+index);
            localStorage.setItem(\'beijing\', \'read_style_\'+index);
        }
        //二维码
        $(".p2").on("click",function(){

            $(".weix").show();
            return false;
        });
        $(".weix .colse").on("click",function(){
            $(".weix").hide();
        });
    })
</script>
<div style="display: none">
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id=\'cnzz_stat_icon_1262122372\'%3E%3C/span%3E%3Cscript src=\'" + cnzz_protocol + "s13.cnzz.com/z_stat.php%3Fid%3D1262122372\' type=\'text/javascript\'%3E%3C/script%3E"));</script>
</div>
</html>';
?>