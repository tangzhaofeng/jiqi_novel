<?php
echo '  <div class="left fl ov">
    <div class="left_h"><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/myarticle.php"><img src="'.$this->_tpl_vars['jieqi_local_url'].'/modules/article/templates/style/images/left_bg_h.jpg" width="195" height="45" /></a></div>
    <div class="left_m">
      <div class="menu_zzjs_1">
        <div class="tit_zzjs_net">
          <h3><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/masterPage">作品管理</a></h3>
        </div>
        <div class="txt_zzjs_net">
          <ul>
            <li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/step1View">发表作品</a></li>
            <li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/masterPage">作品管理</a></li>
            <!--<li><a href="'.$this->_tpl_vars['article_static_url'].'/ytuijian.php">申请推荐</a></li>-->
            <!--<li><a href="'.$this->_tpl_vars['article_static_url'].'/yqiany.php">申请签约</a></li>-->
            <!--<li><a href="'.$this->_tpl_vars['article_static_url'].'/ymyapply.php">我的申请</a></li>-->
            <!--<li><a href="'.$this->_tpl_vars['article_static_url'].'/newdraft.php">定时发布</a></li>  -->
          </ul>
        </div>
      </div>
      <!--menu_zzjs_1-->
      <div class="menu_zzjs_1">
        <div class="tit_zzjs_net">
          <h3><a href="javascript:showmenu_zzjs(3);">我的作品</a></h3>
        </div>
        <div class="txt_zzjs_net">
          <ul>
            ';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
            <li><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></li>
            ';
}
echo '
          </ul>
        </div>
      </div>
      <!--menu_zzjs_1-->
      <div class="menu_zzjs_1">
        <div class="tit_zzjs_net">
          <h3><a href="#">管理员通道</a></h3>
        </div>
        <div class="txt_zzjs_net">
          <ul>
            <li><a href="#">我的短信箱</a></li>
            <li><a href="#">联系管理员</a></li>
          </ul>
        </div>
      </div>
      <!--menu_zzjs_1-->
      <div class="menu_zzjs_1">
        <div class="tit_zzjs_net">
          <h3><a href="#">作者相关</a></h3>
        </div>
        <div class="txt_zzjs_net">
          <ul>
            <!--<li><a href="'.$this->_tpl_vars['article_static_url'].'/writerinfo.php">签约作者信息</a></li>-->
            <li><a href="#">道具统计</a></li>
            <li><a href="#">稿酬收入查询</a></li>
          </ul>
        </div>
      </div>
      <!--menu_zzjs_1-->
      <div class="menu_zzjs_1">
        <div class="tit_zzjs_net">
          <h3><a href="#">网站信息</a></h3>
        </div>
        <div class="txt_zzjs_net">
          <ul>
            <li><a href="#">网站公告</a></li>
            <li><a href="'.$this->_tpl_vars['jieqi_local_url'].'">返回网站首页</a></li>
            <li><a href="'.$this->_tpl_vars['jieqi_user_url'].'/logout">退出登陆</a></li>
          </ul>
        </div>
      </div>
      <!--menu_zzjs_1--> 
    </div>
    <div class="left_f"><img src="'.$this->_tpl_vars['jieqi_local_url'].'/modules/article/templates/style/images/left_bg_f.jpg" width="223" height="66" /></div>
  </div>';
?>