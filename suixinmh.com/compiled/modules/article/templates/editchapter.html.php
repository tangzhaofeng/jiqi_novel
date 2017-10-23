<?php
echo '<link href="'.$this->_tpl_vars['jieqi_themeurl'].'style/user.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.css" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'js/jquery.jNice.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/calendar/WdatePicker.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/local/zh_CN.js"></script>
<!--wrap2 begin-->
<div class="wrap2">
  ';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'modules/article/templates/bookFunction.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
  <!--article2 begin-->
  <div class="article3 fr bg5">
   <!--tabox begin-->
    <div class="tabox">
     <div class="t2 rel">
       <h2>修改章节</h2>
	 </div>
       <dl class="box_form pt20">
<form id="editchapter" name="editchapter" method="post" action="'.geturl('article','chapter','SYS=method=editChapter').'" ajaxpost="true" class="jNice">
		 <dd class="fix">
	          <em class="tt2">文章名称：</em>
	          <div class="int">
	           <p>'.$this->_tpl_vars['article']['articlename'].'</p>
	 		  </div>
         </dd>
         <dd class="fix">
			  <em class="tt2">章节名：</em>
			  <div class="int">
			 <!-- 
			  ';
if($this->_tpl_vars['chapter']['chaptername_prefix'] != ""){
echo '
          		<div class="selt4" style="width:80px;">
	              	<select id="chaptername_prefix" name="chaptername_prefix" style="display:none;">
	              		';
if (empty($this->_tpl_vars['chapter']['chaptername_prefix'])) $this->_tpl_vars['chapter']['chaptername_prefix'] = array();
elseif (!is_array($this->_tpl_vars['chapter']['chaptername_prefix'])) $this->_tpl_vars['chapter']['chaptername_prefix'] = (array)$this->_tpl_vars['chapter']['chaptername_prefix'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['chapter']['chaptername_prefix']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['chapter']['chaptername_prefix']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['chapter']['chaptername_prefix']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['chapter']['chaptername_prefix']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['chapter']['chaptername_prefix']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	              			<option value="'.$this->_tpl_vars['i']['value'].'">'.$this->_tpl_vars['i']['value'].'</option>
	              		';
}
echo '
	               </select>
	         	</div>
          	';
}
echo '-->
			  
			   <input name="chaptername" id="chaptername" type="text" class="input3 fl"  value="'.$this->_tpl_vars['chapter']['chaptername'].'" data-rule="章节名: required;length[~20];chaptername;remote['.geturl('article','chapter','SYS=method=checkChapterName&aid='.$this->_tpl_vars['article']['articleid'].'&cid='.$this->_tpl_vars['chapter']['chapterid'].'').']"/>
			   <span class="hint cl">章节命名不能超过20个字，且不能出现涉黄、涉政、涉暴等敏感词。</span>
			  </div>
         </dd>
         <dd class="fix">
			  <em class="tt2">作品状态：</em>
			  <div class="int">
			   <p class="rdo fl">
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
if($this->_tpl_vars['article']['fullflag']=='1'){
echo 'disabled';
}
echo ' ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['article']['fullflag']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['fullflag']['items'][$this->_tpl_vars['i']['key']].'</label>
			   ';
}
echo '
			   </p>
			   <span class="hint">选定‘已完成’后，不能再修改。</span>
			  </div>
         </dd>
         ';
if($this->_tpl_vars['chapter']['isvip'] == '1'){
echo '
		     <dd class="fix">
		          <em class="tt2">收费：</em>
		          <div class="int">
		          	<p class="rdo fl">
		             	<label>
		             		<input name="isvip" type="radio" ';
if($this->_tpl_vars['chapter']['isvip'] == 0){
echo 'checked="checked"';
}
echo '  value="0" />免费
						</label>
		             	<label>
		             		<input name="isvip" type="radio" ';
if($this->_tpl_vars['chapter']['isvip'] == 1){
echo 'checked="checked"';
}
echo ' value="1" />收费
		             	</label>
		             </p>
		             <span class="hint">系统定价：1';
echo(JIEQI_EGOLD_NAME); 
echo $this->_tpl_vars['wordsperegold'].'字。</span>
		          </div>
		      </dd>
		      ';
if($this->_tpl_vars['article']['customprice'] == 1){
echo '
				   <dd class="fix">
			          <em class="tt2">定价：</em>
			          <div class="int">
			           <input name="saleprice" maxlength="2" id="saleprice" type="text" value="'.$this->_tpl_vars['chapter']['saleprice'].'" class="input3 fl" data-rule="价格: required;digits" data-rule-digits="[/^\\d{1,2}$/, \'请输入0-99的数字\']" />
			           <span class="hint cl">300字一单位，不填写默认使用系统定价。</span>
			          </div>
		          </dd>
		       ';
}
echo '
         ';
}
echo '
         ';
if($this->_tpl_vars['chapter']['attachary'] != null){
echo '
         	<dd class="fix">
	          	<em class="tt2">已上传的附件：</em>
	            <div class="int">
	             <p class="rdo fl">
	             <span class="hint cl">*取消打?幢硎旧境酶郊?/span>
	             <p class="count">
	             	';
if (empty($this->_tpl_vars['chapter']['attachary'])) $this->_tpl_vars['chapter']['attachary'] = array();
elseif (!is_array($this->_tpl_vars['chapter']['attachary'])) $this->_tpl_vars['chapter']['attachary'] = (array)$this->_tpl_vars['chapter']['attachary'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['chapter']['attachary']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['chapter']['attachary']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['chapter']['attachary']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['chapter']['attachary']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['chapter']['attachary']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	             		<label><input name="oldattach[]" type="checkbox" value="'.$this->_tpl_vars['chapter']['attachary'][$this->_tpl_vars['i']['key']]['attachid'].'" checked="checked"/>'.$this->_tpl_vars['chapter']['attachary'][$this->_tpl_vars['i']['key']]['name'].'</label>
	             	';
}
echo '
	             	</p>
	             </p>
	            </div>    
         	</dd>
         ';
}
echo '
         <dd class="fix">
	          <em class="tt2">题外话：</em>
	          <div class="int">
	           <span class="hint cl">【作者有话说】不计入正文字数，100字以内。</span>
	           <textarea  class="inp32"   maxlength="200" name="manual" id="manual">'.$this->_tpl_vars['chapter']['manual'].'</textarea>
	          </div>
         </dd>
          <dd class="fix">
	          <em class="tt2">章节内容：</em>
	          <div class="int">
		           	';
if($this->_tpl_vars['authtypeset'] == 1){
echo '
		           		<p class="rdo fl">
			             		<input name="typeset" type="radio" value="1" checked="checked"/>
			             	<label>自动排版</label>
			             	
			             		<input name="typeset" type="radio" value="2" />
			             	<label>无需排版</label>
		             	</p>
		             ';
}
echo '
	           <textarea name="chaptercontent" id="chaptercontent" class="inp4" data-rule="章节内容: required;length[~12000]">'.$this->_tpl_vars['chapter']['context'].'</textarea>
			   <span class="msg-box" style="margin:5px 0 0 0;" for="chaptercontent"></span>
	           <span class="hint cl">* 不能出现涉黄、涉政、涉暴、危害社会稳定、违反法律法规等有害信息的内容。</span>
	           <p class="count">
		           	';
if($this->_tpl_vars['maxfilenum'] != 0){
echo '
			           	';
$this->_tpl_vars['amax'] = range(1,$this->_tpl_vars['maxfilenum']); 
echo '
		           		';
if (empty($this->_tpl_vars['amax'])) $this->_tpl_vars['amax'] = array();
elseif (!is_array($this->_tpl_vars['amax'])) $this->_tpl_vars['amax'] = (array)$this->_tpl_vars['amax'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['amax']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['amax']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['amax']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['amax']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['amax']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
		           			<br><input type="file" class="text" size="60" name="attachfile" id="attachfile"/>
		           		';
}
echo '
		           	';
}
echo '
	           </p>
	          </div>
         </dd>
         <dd class="fix">
         	';
if($this->_tpl_vars['chapter']['display'] == 9 || $this->_tpl_vars['chapter']['display'] == 2){
echo '
				 <em class="tt2">定时：</em>
				  <div class="int">
				   <input name="postdate" id="postdate" autocomplete="off" value="'.date('Y-m-d H:i:s',$this->_tpl_vars['chapter']['postdate']).'" readonly="readonly" type="text" class="input3 fl" onClick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:00\',minDate:\'%y-%M-{%d+1}\'})"/>
				   <span class="hint cl">定时发布，只能选择最近15天以内的时间进行发布。</span>
				  </div>
			';
}
echo '
			  <div class="int2">
			  <p class="cl">
				<button class="btn" type="submit">修改章节</button>
			  </p>
			  <input type="hidden" name="aid" value="'.$this->_tpl_vars['article']['articleid'].'">
			  <input type="hidden" name="cid" value="'.$this->_tpl_vars['chapter']['chapterid'].'">
			  <input type="hidden" name="formhash" id="formhash" value="';
echo form_hash(); 
echo '" />
			  </div>
         </dd>
        </form>
		<iframe name="upload" id="upload" style="display:none"></iframe>
       </dl>
    </div><!--tabox end-->
  </div><!--article2 end-->
</div><!--wrap2 end-->';
?>