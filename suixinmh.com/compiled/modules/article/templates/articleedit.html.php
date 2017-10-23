<?php
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'templates/autor_head.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
$GLOBALS['jieqiTset']['jieqi_blocks_module'] = 'article';
echo '
';
$GLOBALS['jieqiTset']['jieqi_blocks_config'] = 'authorblocks';
echo '
<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/user.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.css" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'js/jquery.jNice.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/local/zh_CN.js"></script>


<div id="main">
	';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'templates/autor_left.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
	<div class="right fl ov">
		<div class="right_nav ov"> <span class="fl black">发表作品</span> <span class="fr"><a href="http://www.deishu.com/modules/pay/">充 值>></a> <a href="http://www.deishu.com/welfare/welfare.html">查看作家福利>></a></span> </div>
		<div class="right_c">
			<div class="right_c_menu ov"><span class="span_nav"><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/newarticle.php">发表作品</a></span><span><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/masterpage.php">作品管理</a></span><span><a href="'.$this->_tpl_vars['article_static_url'].'/ytuijian.php">申请推荐</a></span><span><a href="'.$this->_tpl_vars['article_static_url'].'/yqiany.php">申请签约</a></span><span><a href="'.$this->_tpl_vars['article_static_url'].'/ymyapply.php">我的申请</a></span><span ><a href="'.$this->_tpl_vars['article_static_url'].'/newdraft.php">草稿箱定时发布</a></span></div>
			<div class="right_c_list">
				<div class="introdu">
					<div class="bg"></div>
					<div class="text">
						<div>说明：</div>
						<ul>
							<li>1、新作品建立后30天之内如未能上传5000字，我们将做删除处理。</li>
							<li>2、如已有1万字以下未完成作品，不能再建立新作品,如果需要删除作品请联系客服或编辑。</li>
							<li>3、新建作品总字数超过3000字后，请点击"提交审核"。</li>
							<li>4、新作品提交审核后，我们将在24小时内进行审核。通过的作品可以在页面上显示。 </li>
						</ul>
					</div>
				</div>
				<form id="signup_form" name="" action="'.geturl('article','article','SYS=method=editArticle').'" method="post" class="jNice" enctype="multipart/form-data">
					<dd class="fix">
						<em class="tt3">选择作品：</em>
						<div class="int">
							<div class="selt4">
								<select name="aid" id="now_place1" style="display: none;" onchange="location.href=\''.geturl('article','article','SYS=method=editArticleView&aid=').'\'+this.value">
									';
if (empty($this->_tpl_vars['articles'])) $this->_tpl_vars['articles'] = array();
elseif (!is_array($this->_tpl_vars['articles'])) $this->_tpl_vars['articles'] = (array)$this->_tpl_vars['articles'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articles']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articles']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articles']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articles']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articles']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
									<option value="'.$this->_tpl_vars['articles'][$this->_tpl_vars['i']['key']]['articleid'].'" ';
if($this->_tpl_vars['articles'][$this->_tpl_vars['i']['key']]['articleid'] == $this->_tpl_vars['article']['articleid']){
echo ' selected ';
}
echo '>'.$this->_tpl_vars['articles'][$this->_tpl_vars['i']['key']]['articlename'].'</option>
									';
}
echo '
								</select>
							</div><!--selt4 end-->
							<span class="hint">请选择您要编辑的作品</span>
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">所属类别：</em>
						';
if($this->_tpl_vars['manageallarticle'] == 1){
echo '
						<div class="selt3">
							<select id="siteid" name="siteid" onchange="showtypes(this)" style="display:none;">
								';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
								<option value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['article']['siteid'] == $this->_tpl_vars['i']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['name'].'</option>
								';
}
echo '
							</select>
						</div>
						<!-- 频道对应的分类 --><!--
		         <div class="selt3" >
		           <select id="sortid" name="sortid" style="display:none;">
		           </select>
	           	</div>-->

						<div class="selt3">
							<select id="sortid" name="sortid" style="display:none;">
								';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
								';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['siteid']){
echo '
								';
if (empty($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'])) $this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'] = array();
elseif (!is_array($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'])) $this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'] = (array)$this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'];
$this->_tpl_vars['k']=array();
$this->_tpl_vars['k']['columns'] = 1;
$this->_tpl_vars['k']['count'] = count($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']);
$this->_tpl_vars['k']['addrows'] = count($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']) % $this->_tpl_vars['k']['columns'] == 0 ? 0 : $this->_tpl_vars['k']['columns'] - count($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']) % $this->_tpl_vars['k']['columns'];
$this->_tpl_vars['k']['loops'] = $this->_tpl_vars['k']['count'] + $this->_tpl_vars['k']['addrows'];
reset($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']);
for($this->_tpl_vars['k']['index'] = 0; $this->_tpl_vars['k']['index'] < $this->_tpl_vars['k']['loops']; $this->_tpl_vars['k']['index']++){
	$this->_tpl_vars['k']['order'] = $this->_tpl_vars['k']['index'] + 1;
	$this->_tpl_vars['k']['row'] = ceil($this->_tpl_vars['k']['order'] / $this->_tpl_vars['k']['columns']);
	$this->_tpl_vars['k']['column'] = $this->_tpl_vars['k']['order'] % $this->_tpl_vars['k']['columns'];
	if($this->_tpl_vars['k']['column'] == 0) $this->_tpl_vars['k']['column'] = $this->_tpl_vars['k']['columns'];
	if($this->_tpl_vars['k']['index'] < $this->_tpl_vars['k']['count']){
		list($this->_tpl_vars['k']['key'], $this->_tpl_vars['k']['value']) = each($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']);
		$this->_tpl_vars['k']['append'] = 0;
	}else{
		$this->_tpl_vars['k']['key'] = '';
		$this->_tpl_vars['k']['value'] = '';
		$this->_tpl_vars['k']['append'] = 1;
	}
	echo '
								<option value="'.$this->_tpl_vars['k']['key'].'" ';
if($this->_tpl_vars['article']['sortid'] == $this->_tpl_vars['k']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'][$this->_tpl_vars['k']['key']]['caption'].'</option>
								';
}
echo '
								';
}
echo '
								';
}
echo '
							</select>
						</div>
						';
}else{
echo '
						<div class="int">
							<input name="siteid" value="'.$this->_tpl_vars['article']['siteid'].'" type="hidden"/>
							<input name="sortid" value="'.$this->_tpl_vars['article']['sortid'].'" type="hidden"/>
							<span class="tit">';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	if($this->_tpl_vars['article']['siteid'] == $this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['siteid']){
echo $this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['name'];
}
}
echo '-'.$this->_tpl_vars['article']['sort'].'</span>【<a href="javascript:alert(\'建设中...\')" class="f_blue5">修改所属类别</a>】 (30天内仅可向管理员申请修改所属分类1次)
						</div>
						';
}
echo '
					</dd>
					<dd class="fix">
						<em class="tt3">作品书名：</em>
						';
if($this->_tpl_vars['manageallarticle'] == 1){
echo '
						<div class="int">
							<input name="articlename" id="articlename" type="text" class="input3 fl" value="'.$this->_tpl_vars['article']['articlename'].'" maxlength="20" data-rule="书名: required;articlename;remote['.geturl('article','article','SYS=method=checkArticlename&aid='.$this->_tpl_vars['article']['articleid'].'').']"/>
						</div>
						';
}else{
echo '
						<div class="int">
							<input name="articlename" value="'.$this->_tpl_vars['article']['articlename'].'" type="hidden"/>
							<span class="tit">'.$this->_tpl_vars['article']['articlename'].'</span>【<a href="javascript:alert(\'建设中...\')" class="f_blue5">申请修改书名</a>】 (30天内仅可向管理员申请修改所属分类1次)
						</div>
						';
}
echo '
					</dd>
					<dd class="fix">
						<em class="tt3">关键字：</em>
						<div class="int">
							<input name="keywords" id="keywords" type="text" class="input3 fl" value="'.$this->_tpl_vars['article']['keywords'].'" /><span class="hint">关键字之间用空格隔开,最多十组</span>
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">标签：</em>
						<div class="int">
							<div id="tags">
								';
if (empty($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']])) $this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']] = array();
elseif (!is_array($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']])) $this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']] = (array)$this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']]);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']]) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']]) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']]);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']]);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
								<label><input name="tag[]" type="checkbox" value="'.$this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']][$this->_tpl_vars['i']['key']]['id'].'" data-rule="checked[1~4]"/>'.$this->_tpl_vars['tags'][$this->_tpl_vars['article']['siteid']][$this->_tpl_vars['i']['key']]['name'].'</label>&nbsp;
								';
}
echo '
							</div>
							<span class="hint cl">最少选择一个标签，最多选择4个标签。 </span>
						</div>
					</dd>
					<script>
						var tagid = \''.$this->_tpl_vars['article']['tag'].'\';
						tagid = tagid.split(",");
						$("[name=\'tag[]\']").attr("checked",false);//清空
						for(i=0;i<tagid.length;i++){
							$("[name=\'tag[]\'][value=\'"+tagid[i]+"\']").attr("checked",\'true\');
						}
					</script>
					<dd class="fix">
						<em class="tt3">编辑：</em>
						';
if($this->_tpl_vars['manageallarticle'] == 1){
echo '
						<div class="int">
							<input name="agent" id="agent" type="text" class="input3 fl" value="'.$this->_tpl_vars['article']['agent'].'" maxlength="20" data-rule="agent;remote['.geturl('article','article','SYS=method=checkAgent').']"/><span class="hint">指定一个本站现有用户作为管理员</span>
						</div>
						';
}else{
echo '
						<div class="int">
							<input name="agent" value="'.$this->_tpl_vars['article']['agent'].'" type="hidden"/>
							<span class="tit">'.$this->_tpl_vars['article']['agent'].'</span>
						</div>
						';
}
echo '
					</dd>
					<dd class="fix">
						<em class="tt3">作者：</em>
						';
if($this->_tpl_vars['transarticle'] == 1){
echo '
						<div class="int">
							<input name="author" id="author" type="text" class="input3 fl" value="'.$this->_tpl_vars['article']['author'].'"  maxlength="20" /><span class="hint">发表自己作品请留空</span>
							<!--   <input name="author" id="author" type="text" class="input3 fl" value="'.$this->_tpl_vars['article']['author'].'"  maxlength="10" data-rule="author;remote['.geturl('article','article','SYS=method=checkAuthor').']"/><span class="hint">发表自己作品请留空</span>
                             --></div>
						';
}else{
echo '
						<div class="int">
							<input name="author" value="'.$this->_tpl_vars['article']['author'].'" type="hidden"/>
							<span class="tit">'.$this->_tpl_vars['article']['author'].'</span>
						</div>
						';
}
echo '
					</dd>';
if($this->_tpl_vars['transarticle'] == 1){
echo '
					<dd class="fix">
						<em class="tt3">管理授权：</em>
						<div class="int">
							';
if (empty($this->_tpl_vars['authorflag']['items'])) $this->_tpl_vars['authorflag']['items'] = array();
elseif (!is_array($this->_tpl_vars['authorflag']['items'])) $this->_tpl_vars['authorflag']['items'] = (array)$this->_tpl_vars['authorflag']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['authorflag']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['authorflag']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['authorflag']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['authorflag']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['authorflag']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
							<input type="radio" class="radio" name="authorflag" value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['authorflag']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['authorflag']['items'][$this->_tpl_vars['i']['key']].'
							';
}
echo '
						</div>
					</dd>';
}
echo '
					<dd class="fix">
						<em class="tt3">授权级别：</em>
						<div class="int">
							';
if($this->_tpl_vars['transarticle'] == 1){
echo '
							';
if (empty($this->_tpl_vars['permission']['items'])) $this->_tpl_vars['permission']['items'] = array();
elseif (!is_array($this->_tpl_vars['permission']['items'])) $this->_tpl_vars['permission']['items'] = (array)$this->_tpl_vars['permission']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['permission']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['permission']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['permission']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['permission']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['permission']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
							<p class="rdo fl">
								<input type="radio" name="permission" value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['permission']){
echo 'checked="checked" ';
}
echo ' /><label>'.$this->_tpl_vars['permission']['items'][$this->_tpl_vars['i']['key']].'</label>
							</p>
							';
}
echo '
							';
}else{
echo '
							<input name="permission" value="'.$this->_tpl_vars['article']['permission'].'" type="hidden"/><span class="tit fl">'.$this->_tpl_vars['article']['permission_tag'].'</span><!--<span class="hint">授权性质请详见 <a href="javascript:alert(\'建设中...\')" class="f_blue5">投稿须知</a> 一经选定，不能再更改</span>-->
							';
}
echo '
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">作品状态：</em>
						<div class="int">
							<p class="rdo fl">
								';
if($this->_tpl_vars['manageallarticle'] == 1){
echo '
								';
if (empty($this->_tpl_vars['fullflag']['items'])) $this->_tpl_vars['fullflag']['items'] = array();
elseif (!is_array($this->_tpl_vars['fullflag']['items'])) $this->_tpl_vars['fullflag']['items'] = (array)$this->_tpl_vars['fullflag']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['fullflag']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['fullflag']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['fullflag']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['fullflag']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['fullflag']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
								<label><input name="fullflag" type="radio"  value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['fullflag']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['fullflag']['items'][$this->_tpl_vars['i']['key']].'</label>
								';
}
echo '
								';
}else{
echo '
								';
if($this->_tpl_vars['article']['fullflag']=='1'){
echo '
								<!-- 已完成 不能更改 -->
								<input name="fullflag" value="'.$this->_tpl_vars['article']['fullflag'].'" type="hidden"/><span class="tit fl">'.$this->_tpl_vars['article']['fullflag_tag'].'</span><span class="hint">一经选定，不能再更改</span>
								';
}else{
echo '
								';
if (empty($this->_tpl_vars['fullflag']['items'])) $this->_tpl_vars['fullflag']['items'] = array();
elseif (!is_array($this->_tpl_vars['fullflag']['items'])) $this->_tpl_vars['fullflag']['items'] = (array)$this->_tpl_vars['fullflag']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['fullflag']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['fullflag']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['fullflag']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['fullflag']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['fullflag']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
								<label><input name="fullflag" type="radio"  value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['fullflag']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['fullflag']['items'][$this->_tpl_vars['i']['key']].'</label>
								';
}
echo '
								';
}
echo '
								';
}
echo '
							</p>
							<span class="hint">写作中的作品请选择"连载中",如选择"已完成"作品状态不可再次更改</span>
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">首发状态：</em>
						<div class="int">
							';
if($this->_tpl_vars['transarticle'] == 1){
echo '
							';
if (empty($this->_tpl_vars['firstflag']['items'])) $this->_tpl_vars['firstflag']['items'] = array();
elseif (!is_array($this->_tpl_vars['firstflag']['items'])) $this->_tpl_vars['firstflag']['items'] = (array)$this->_tpl_vars['firstflag']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['firstflag']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['firstflag']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['firstflag']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['firstflag']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['firstflag']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
							<p class="rdo fl">
								<input type="radio" name="firstflag" value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['firstflag']){
echo 'checked="checked" ';
}
echo ' /><label>'.$this->_tpl_vars['firstflag']['items'][$this->_tpl_vars['i']['key']].'</label>
							</p>
							';
}
echo '
							';
}else{
echo '
							<input name="firstflag" value="'.$this->_tpl_vars['article']['firstflag'].'" type="hidden"/><span class="tit fl">'.$this->_tpl_vars['article']['firstflag_tag'].'</span><span class="hint">一经选定，不能再更改</span>
							';
}
echo '
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">内容简介：</em>
						<div class="int">
							<span class="hint">此处填写的是作品的简介，不是正文内容，请不要超过400字。</span>
							<textarea name="intro" id="intro" class="inp31"   maxlength="400" data-rule="简介: required;intro;remote['.geturl('article','article','SYS=method=checkIntro').']">'.$this->_tpl_vars['article']['intro'].'</textarea>
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">作者公告：</em>
						<div class="int">
							<span class="hint">此处填写的是作品的公告内容，之后会显示在新作品首页的作品公告版块中。请不要超过140字。</span>
							<textarea name="notice" id="notice" class="inp31" maxlength="140">'.$this->_tpl_vars['article']['notice'].'</textarea>
						</div>
					</dd>
					';
if($this->_tpl_vars['manageallarticle'] == 1){
echo '
					<dd class="fix">
						<em class="tt3">当前封面：</em>
						<div class="int">
							<div class="bk_pic fix">
								<div class="box">
									<img src="'.$this->_tpl_vars['article']['url_image_l'].'"/>大封面
								</div>
								<div class="box">
									<img src="'.$this->_tpl_vars['article']['url_image'].'"/>小封面
								</div>
							</div>
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">上传大封面：</em>
						<div class="int">
							<input type="file" class="text" size="60" name="articlelpic" id="articlelpic"/><span class="hint cl">大封面用于PC端，可在文章管理中修改。 </span>
						</div>
					</dd>
					<dd class="fix">
						<em class="tt3">上传小封面：</em>
						<div class="int">
							<input type="file" class="text" size="60" name="articlespic" id="articlespic"/><span class="hint cl">小封面用于移动端，默认使用大封面缩小图。 </span>
						</div>
					</dd>
					';
}
echo '
					<dd class="fix">
						<em class="tt3"></em>
						<div class="int">
							<span class="hint"></span>
							<p class="pt20 pb20"><button type="submit" class="btn">提交</button></p>
							<input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" />
						</div>
					</dd>
				</form>
			</div>
		</div>
		<div class="right_f"><img src="'.$this->_tpl_vars['jieqi_local_url'].'/modules/article/templates/style/images/right_f.jpg" width="733" height="42" /></div>
	</div>
</div>
<script language="javascript">
	function showtypes(obj){
		var temp,tag_html = \'\';
		';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
		if(obj.options[obj.selectedIndex].value == '.$this->_tpl_vars['i']['key'].'){
			';
if (empty($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'])) $this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'] = array();
elseif (!is_array($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'])) $this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'] = (array)$this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'];
$this->_tpl_vars['k']=array();
$this->_tpl_vars['k']['columns'] = 1;
$this->_tpl_vars['k']['count'] = count($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']);
$this->_tpl_vars['k']['addrows'] = count($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']) % $this->_tpl_vars['k']['columns'] == 0 ? 0 : $this->_tpl_vars['k']['columns'] - count($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']) % $this->_tpl_vars['k']['columns'];
$this->_tpl_vars['k']['loops'] = $this->_tpl_vars['k']['count'] + $this->_tpl_vars['k']['addrows'];
reset($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']);
for($this->_tpl_vars['k']['index'] = 0; $this->_tpl_vars['k']['index'] < $this->_tpl_vars['k']['loops']; $this->_tpl_vars['k']['index']++){
	$this->_tpl_vars['k']['order'] = $this->_tpl_vars['k']['index'] + 1;
	$this->_tpl_vars['k']['row'] = ceil($this->_tpl_vars['k']['order'] / $this->_tpl_vars['k']['columns']);
	$this->_tpl_vars['k']['column'] = $this->_tpl_vars['k']['order'] % $this->_tpl_vars['k']['columns'];
	if($this->_tpl_vars['k']['column'] == 0) $this->_tpl_vars['k']['column'] = $this->_tpl_vars['k']['columns'];
	if($this->_tpl_vars['k']['index'] < $this->_tpl_vars['k']['count']){
		list($this->_tpl_vars['k']['key'], $this->_tpl_vars['k']['value']) = each($this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort']);
		$this->_tpl_vars['k']['append'] = 0;
	}else{
		$this->_tpl_vars['k']['key'] = '';
		$this->_tpl_vars['k']['value'] = '';
		$this->_tpl_vars['k']['append'] = 1;
	}
	echo '
			temp +=\'<option value="'.$this->_tpl_vars['k']['key'].'" ';
if($this->_tpl_vars['article']['sortid'] == $this->_tpl_vars['k']['key']){
echo 'selected';
}
echo '>'.$this->_tpl_vars['channel'][$this->_tpl_vars['i']['key']]['sort'][$this->_tpl_vars['k']['key']]['caption'].'</option>\';
			';
}
echo '

			';
if (empty($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']])) $this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']] = array();
elseif (!is_array($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']])) $this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']] = (array)$this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']];
$this->_tpl_vars['l']=array();
$this->_tpl_vars['l']['columns'] = 1;
$this->_tpl_vars['l']['count'] = count($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']]);
$this->_tpl_vars['l']['addrows'] = count($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']]) % $this->_tpl_vars['l']['columns'] == 0 ? 0 : $this->_tpl_vars['l']['columns'] - count($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']]) % $this->_tpl_vars['l']['columns'];
$this->_tpl_vars['l']['loops'] = $this->_tpl_vars['l']['count'] + $this->_tpl_vars['l']['addrows'];
reset($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']]);
for($this->_tpl_vars['l']['index'] = 0; $this->_tpl_vars['l']['index'] < $this->_tpl_vars['l']['loops']; $this->_tpl_vars['l']['index']++){
	$this->_tpl_vars['l']['order'] = $this->_tpl_vars['l']['index'] + 1;
	$this->_tpl_vars['l']['row'] = ceil($this->_tpl_vars['l']['order'] / $this->_tpl_vars['l']['columns']);
	$this->_tpl_vars['l']['column'] = $this->_tpl_vars['l']['order'] % $this->_tpl_vars['l']['columns'];
	if($this->_tpl_vars['l']['column'] == 0) $this->_tpl_vars['l']['column'] = $this->_tpl_vars['l']['columns'];
	if($this->_tpl_vars['l']['index'] < $this->_tpl_vars['l']['count']){
		list($this->_tpl_vars['l']['key'], $this->_tpl_vars['l']['value']) = each($this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']]);
		$this->_tpl_vars['l']['append'] = 0;
	}else{
		$this->_tpl_vars['l']['key'] = '';
		$this->_tpl_vars['l']['value'] = '';
		$this->_tpl_vars['l']['append'] = 1;
	}
	echo '
			tag_html += \'<label><input name="tag[]" type="checkbox" value="'.$this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']][$this->_tpl_vars['l']['key']]['id'].'" data-rule="checked[1~4]"/>'.$this->_tpl_vars['tags'][$this->_tpl_vars['i']['key']][$this->_tpl_vars['l']['key']]["name"].'</label>&nbsp;\';
			';
}
echo '
		}
		';
}
echo '
		$("#sortid").html(temp);
		$("#tags").html(tag_html);
		bindselectOnId(\'sortid\');
	}
</script>
<!--header end-->
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'templates/autor_footer.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);

?>