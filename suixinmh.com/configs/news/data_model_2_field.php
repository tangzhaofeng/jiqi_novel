<?php
if(!defined('IN_JQNEWS')) exit('Access Denied');
$_SGLOBAL['model_2_field']=Array
	(
	'contentid' => Array
		(
		'fieldid' => 50,
		'modelid' => 2,
		'field' => 'contentid',
		'name' => 'ID',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'number',
		'setting' => '',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => 1,
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => 1,
		'isorder' => 1,
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => '0',
		'disabled' => '0'
		),
	'catid' => Array
		(
		'fieldid' => 51,
		'modelid' => 2,
		'field' => 'catid',
		'name' => '栏目',
		'tips' => '',
		'css' => '',
		'minlength' => 1,
		'maxlength' => 6,
		'pattern' => '/^[0-9]{1,6}$/',
		'errortips' => '请选择栏目',
		'formtype' => 'catid',
		'setting' => 'Array
	(
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => '0',
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 1,
		'disabled' => '0'
		),
	'typeid' => Array
		(
		'fieldid' => 52,
		'modelid' => 2,
		'field' => 'typeid',
		'name' => '类别',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'typeid',
		'setting' => 'Array
	(
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => '0',
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 2,
		'disabled' => 1
		),
	'areaid' => Array
		(
		'fieldid' => 53,
		'modelid' => 2,
		'field' => 'areaid',
		'name' => '地区',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'areaid',
		'setting' => 'Array
	(
	\'items\' => \'\',
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => '0',
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 3,
		'disabled' => 1
		),
	'title' => Array
		(
		'fieldid' => 54,
		'modelid' => 2,
		'field' => 'title',
		'name' => '标题',
		'tips' => '',
		'css' => 'inputtitle',
		'minlength' => 1,
		'maxlength' => 80,
		'pattern' => '',
		'errortips' => '标题字符长度必须为1到80位！',
		'formtype' => 'text',
		'setting' => 'Array
	(
	\'size\' => 80,
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => ' onBlur="$.post(\'/modules/news/admin/?ac=content&ajax_request=1\', { op : \'dict_word\',data:$(\'#title\').val()}, function(data){if(data && $(\'#keywords\').val()==\'\') $(\'#keywords\').val(data); })" ',
		'forminfo' => '  <input type="button" value="检测标题是否已存在" onclick="$.post(\'/modules/news/admin/?ac=content&ajax_request=1\', { op : \'check_title\', title:$(\'#title\').val(),catid:$(\'#catid\').val()}, function(data){ $(\'#t_msg\').html(data); })">&nbsp;<span style="color:\'#ff0000\'" id=\'t_msg\'></span>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => 1,
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => 1,
		'listorder' => 4,
		'disabled' => '0'
		),
	'style' => Array
		(
		'fieldid' => 55,
		'modelid' => 2,
		'field' => 'style',
		'name' => '颜色和字型',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'hidden',
		'setting' => 'Array
	(
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => ' <select name="style_color1" id="style_color1" onchange="document.all.style.value=document.all.style_color1.value;if(document.all.style_strong1.checked)document.all.style.value +=  \' \'+document.all.style_strong1.value;"><option value="">颜色</option><option value="c1"  class="bg1"></option><option value="c2"  class="bg2"></option><option value="c3"  class="bg3"></option><option value="c4"  class="bg4"></option><option value="c5"  class="bg5"></option><option value="c6"  class="bg6"></option><option value="c7"  class="bg7"></option><option value="c8"  class="bg8"></option><option value="c9"  class="bg9"></option><option value="c10"  class="bg10"></option><option value="c11"  class="bg11"></option><option value="c12"  class="bg12"></option><option value="c13"  class="bg13"></option><option value="c14"  class="bg14"></option><option value="c15"  class="bg15"></option></select><label><input type="checkbox" name="style_strong1" id="style_strong1" value="b" onclick="document.all.style.value=document.all.style_color1.value;if(document.all.style_strong1.checked)document.all.style.value += \' \'+document.all.style_strong1.value;"> 加粗</label><script>if(document.all.style.value!=\'\'){var temp=document.all.style.value;if(temp.indexOf("b")!=-1){document.all.style_strong1.checked=true;}var sint=temp.replace(/[^\\d]*/ig,"");if(sint!=\'\'){document.all.style_color1.selectedIndex = sint;}}</script>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => '0',
		'islist' => '0',
		'isshow' => 1,
		'isadd' => '0',
		'isfulltext' => '0',
		'listorder' => 5,
		'disabled' => '0'
		),
	'thumb' => Array
		(
		'fieldid' => 56,
		'modelid' => 2,
		'field' => 'thumb',
		'name' => '缩略图',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 100,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'image',
		'setting' => 'Array
	(
	\'size\' => 50,
	\'defaultvalue\' => \'\',
	\'maxsize\' => 1024,
	\'fileextname\' => \'jpg,jpeg,gif,png,bmp\',
	\'isselectimage\' => 1,
	\'enablesaveimage\' => \'0\',
	\'thumb_enable\' => 1,
	\'thumb_width\' => 161,
	\'thumb_height\' => 111,
	\'attachwater\' => \'0\',
	\'attachwimage\' => \'watermark.gif\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => 1,
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 6,
		'disabled' => '0'
		),
	'keywords' => Array
		(
		'fieldid' => 57,
		'modelid' => 2,
		'field' => 'keywords',
		'name' => '关键词',
		'tips' => '多个关键词之间用空格隔开',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 40,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'text',
		'setting' => 'Array
	(
	\'size\' => 50,
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '<select name="" onchange="$(\'#keywords\').val($(\'#keywords\').val()+this.value+\' \')" style="width:85px"><option>常用关键词</option><option value=黎明>黎明</option><option value=王爱红>王爱红</option></select>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => 1,
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => 1,
		'listorder' => 7,
		'disabled' => '0'
		),
	'author' => Array
		(
		'fieldid' => 58,
		'modelid' => 2,
		'field' => 'author',
		'name' => '作者',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 30,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'text',
		'setting' => 'Array
	(
	\'size\' => 30,
	\'defaultvalue\' => \'91wan《盘龙神墓记》\'
	)',
		'formattribute' => '',
		'forminfo' => '<select name="" onchange="$(\'#author\').val(this.value)" style="width:85px"><option>常用作者</option><option value=91wan《盘龙神墓记》>91wan《盘龙神墓记》</option>select>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => '0',
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => 1,
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => 1,
		'listorder' => 8,
		'disabled' => '0'
		),
	'copyfrom' => Array
		(
		'fieldid' => 59,
		'modelid' => 2,
		'field' => 'copyfrom',
		'name' => '来源',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 100,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'text',
		'setting' => 'Array
	(
	\'size\' => 30,
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '<select name="select_copyfrom" onchange="$(\'#copyfrom\').val(this.value)" style="width:85px"><option>常用来源</option><option value=17173游戏网>17173游戏网</option><option value=盛大游戏>盛大游戏</option></select>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => '0',
		'isunique' => '0',
		'isbase' => '0',
		'issearch' => '0',
		'isselect' => 1,
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => 1,
		'listorder' => 9,
		'disabled' => '0'
		),
	'description' => Array
		(
		'fieldid' => 60,
		'modelid' => 2,
		'field' => 'description',
		'name' => '摘要',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 255,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'textarea',
		'setting' => 'Array
	(
	\'rows\' => 4,
	\'cols\' => 50,
	\'defaultvalue\' => \'\',
	\'enablehtml\' => 1
	)',
		'formattribute' => 'style="width:80%" onkeyup="checkLength(this, \'description\', \'255\');"',
		'forminfo' => ' <br /><img src="/modules/news/images/icon.gif" width="12"> 还可以输入 <font id="ls_description" color="#ff0000;">255</font> 个字符！',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => 1,
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => 1,
		'listorder' => 10,
		'disabled' => '0'
		),
	'userid' => Array
		(
		'fieldid' => 61,
		'modelid' => 2,
		'field' => 'userid',
		'name' => '发布人',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'userid',
		'setting' => '',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => 1,
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => 1,
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 11,
		'disabled' => '0'
		),
	'updatetime' => Array
		(
		'fieldid' => 62,
		'modelid' => 2,
		'field' => 'updatetime',
		'name' => '更新时间',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'datetime',
		'setting' => 'array (
  \'dateformat\' => \'int\',
  \'format\' => \'Y-m-d H:i:s\',
  \'defaulttype\' => \'1\',
  \'defaultvalue\' => \'\',
)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => 1,
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => 1,
		'isorder' => 1,
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 12,
		'disabled' => '0'
		),
	'content' => Array
		(
		'fieldid' => 63,
		'modelid' => 2,
		'field' => 'content',
		'name' => '描述',
		'tips' => '<label><input name="add_introduce" type="checkbox"  value="1" checked>是否截取内容</label><br><input type="text" name="introcude_length" value="200" size="3">字符至内容摘要
<br/><br/>
<label><input type=\'checkbox\' name=\'auto_thumb\' value="1" checked>是否获取内容第</label><br><input type="text" name="auto_thumb_no" value="1" size="2" class="">张图片作为标题图片',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 999999,
		'pattern' => '',
		'errortips' => '请填写文章内容！',
		'formtype' => 'editor',
		'setting' => 'Array
	(
	\'toolbar\' => \'basic\',
	\'width\' => \'100%\',
	\'height\' => 350,
	\'defaultvalue\' => \'\',
	\'storage\' => \'database\',
	\'thumb_enable\' => -1,
	\'thumb_width\' => \'\',
	\'thumb_height\' => \'\',
	\'attachwater\' => -1,
	\'attachwimage\' => \'\',
	\'enablesaveimage\' => 1,
	\'enablesaveflash\' => \'0\',
	\'savefileext\' => \'mp3|rar|zip\',
	\'forbidwords\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => '0',
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => 1,
		'isselect' => 1,
		'isorder' => '0',
		'islist' => '0',
		'isshow' => 1,
		'isadd' => 1,
		'isfulltext' => 1,
		'listorder' => 13,
		'disabled' => '0'
		),
	'inputtime' => Array
		(
		'fieldid' => 64,
		'modelid' => 2,
		'field' => 'inputtime',
		'name' => '发布时间',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'datetime',
		'setting' => 'Array
	(
	\'format\' => \'Y-m-d H:i:s\',
	\'defaulttype\' => 1,
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => '0',
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => 1,
		'islist' => '0',
		'isshow' => '0',
		'isadd' => '0',
		'isfulltext' => '0',
		'listorder' => 14,
		'disabled' => '0'
		),
	'url' => Array
		(
		'fieldid' => 65,
		'modelid' => 2,
		'field' => 'url',
		'name' => '转向链接',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'text',
		'setting' => 'Array
	(
	\'size\' => 50,
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => 'readonly',
		'forminfo' => '  <font color="#FF0000"><label><input type="checkbox" id="linkurl" value="1" onclick="javascript:if(this.checked==true) $(\'#url\').attr(\'readonly\',\'\');else $(\'#url\').attr(\'readonly\',\'true\');" > 转向链接</label></font><br/><font color="#FF0000">如果使用转向链接则点击标题就直接跳转而内容设置无效</font>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => '0',
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => '0',
		'islist' => 1,
		'isshow' => 1,
		'isadd' => '0',
		'isfulltext' => '0',
		'listorder' => 15,
		'disabled' => '0'
		),
	'posids' => Array
		(
		'fieldid' => 66,
		'modelid' => 2,
		'field' => 'posids',
		'name' => '推荐位',
		'tips' => '全选<input boxid=\'posids\' type=\'checkbox\' onclick="checkall(\'posids\')" >',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'posid',
		'setting' => 'Array
	(

	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => '0',
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => '0',
		'isfulltext' => '0',
		'listorder' => 16,
		'disabled' => '0'
		),
	'pictureurls' => Array
		(
		'fieldid' => 67,
		'modelid' => 2,
		'field' => 'pictureurls',
		'name' => '组图',
		'tips' => '多图片上传<br>
<label><input type=\'checkbox\' name=\'auto_thumb\' value="1" checked>是否设置第</label><br><input type="text" name="auto_thumb_no" value="1" size="2" class="">张图片作为标题图片',
		'css' => '',
		'minlength' => '0',
		'maxlength' => '0',
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'images',
		'setting' => 'Array
	(

	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => '0',
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => 1,
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 17,
		'disabled' => '0'
		),
	'prefix' => Array
		(
		'fieldid' => 68,
		'modelid' => 2,
		'field' => 'prefix',
		'name' => 'html文件名',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 20,
		'pattern' => '',
		'errortips' => '字符长度必须为0到20位',
		'formtype' => 'text',
		'setting' => 'Array
	(
	\'size\' => 20,
	\'defaultvalue\' => \'\'
	)',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => -99,
		'iscore' => '0',
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => '0',
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => '0',
		'isfulltext' => '0',
		'listorder' => 18,
		'disabled' => '0'
		),
	'listorder' => Array
		(
		'fieldid' => 70,
		'modelid' => 2,
		'field' => 'listorder',
		'name' => '排序',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 6,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'number',
		'setting' => '',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => 1,
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => 1,
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 97,
		'disabled' => '0'
		),
	'status' => Array
		(
		'fieldid' => 71,
		'modelid' => 2,
		'field' => 'status',
		'name' => '状态',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 2,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'number',
		'setting' => '',
		'formattribute' => '',
		'forminfo' => '',
		'unsetgroupids' => '',
		'iscore' => 1,
		'issystem' => 1,
		'isunique' => '0',
		'isbase' => 1,
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => 1,
		'isfulltext' => '0',
		'listorder' => 98,
		'disabled' => '0'
		),
	'template' => Array
		(
		'fieldid' => 72,
		'modelid' => 2,
		'field' => 'template',
		'name' => '内容页模板',
		'tips' => '',
		'css' => '',
		'minlength' => '0',
		'maxlength' => 30,
		'pattern' => '',
		'errortips' => '',
		'formtype' => 'template',
		'setting' => 'Array
	(
	\'items\' => \'\',
	\'size\' => \'\',
	\'defaultvalue\' => \'\',
	\'multiple\' => \'0\',
	\'fieldtype\' => \'CHAR\'
	)',
		'formattribute' => '',
		'forminfo' => '<font color="#FF0000">直接填模板文件名，例如：show</font>',
		'unsetgroupids' => '',
		'iscore' => '0',
		'issystem' => '0',
		'isunique' => '0',
		'isbase' => '0',
		'issearch' => '0',
		'isselect' => '0',
		'isorder' => '0',
		'islist' => '0',
		'isshow' => '0',
		'isadd' => '0',
		'isfulltext' => '0',
		'listorder' => 99,
		'disabled' => '0'
		)
	)
?>