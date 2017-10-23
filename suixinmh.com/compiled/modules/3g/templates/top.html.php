<?php
echo '
        <style>
        .rankLI li{padding: 10px 0;border-bottom: #ddd 1px solid;}
        .rankLI li:last-child{border-bottom: 0;}
        .rankLI li a{display: block;}
        .rankLI li span{width: 18px;height:18px;line-height: 18px;text-align: center;overflow: hidden;color: #fff;display: inline-block;margin-right: 5px;border-radius: 50%;vertical-align: middle;}
        </style>
        ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/search.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
        <!--男生榜-->
        <div class="mt10 bgcfff plf10">
            <h1 class="ptb10 f16 clearfix" style="color: #1fb3b6;border-bottom:#dad9d9 1px solid;">男生榜</h1>
            <ul class="rankLI">
            '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C3%2C%2C0%2C0%2C0%2C0%2C%2C0%7D%3E%3C%7B3g%2Fblock_toplist.html%7D%3E')).'
               <!--  <li><a class="tEllipsis" href="details.html"><span class="bgcOrange">1</span>婚色撩人：权少诱妻成瘾</a></li>
                <li><a class="tEllipsis" href="details.html"><span class="bgcOrange">2</span>婚色撩人：权少诱妻成瘾</a></li>
                <li><a class="tEllipsis" href="details.html"><span class="bgcOrange">3</span>婚色撩人：权少诱妻成瘾</a></li>
                <li><a class="tEllipsis" href="details.html"><span class="bgcddd">4</span>婚色撩人：权少诱妻成瘾</a></li>
                <li><a class="tEllipsis" href="details.html"><span class="bgcddd">5</span>婚色撩人：权少诱妻成瘾</a></li>
                <li><a class="tEllipsis" href="details.html"><span class="bgcddd">6</span>婚色撩人：权少诱妻成瘾</a></li>
                <li><a class="tEllipsis" href="details.html"><span class="bgcddd">7</span>婚色撩人：权少诱妻成瘾</a></li> -->
            </ul>
        </div>
        <!--女生榜-->
        <div class="mt10 bgcfff plf10">
            <h1 class="ptb10 f16 clearfix" style="color: #1fb3b6;border-bottom:#dad9d9 1px solid;">女生榜</h1>
            <ul class="rankLI">
                 '.jieqi_geturl('system', 'tags', array('id'=>2, 'name'=>'%CD%A8%D3%C3%B2%E9%D1%AF%3C%7Btotalvisit%2C3%2C%2C0%2C0%2C0%2C0%2C%2C100%7D%3E%3C%7B3g%2Fblock_toplist.html%7D%3E')).'
            </ul>
        </div>
    
 
</html>';
?>