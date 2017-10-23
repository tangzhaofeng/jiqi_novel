<?php
/*
	[JQ NEWS] (C) 2007-2008 CMS Inc.
	$Id: lang_showmessage.php 10870 2010-04-14 18:30:21Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

$jieqiLang['news'] = array(

    //global
	'message_notice' => '信息提示',
    'users_do_not_exists' => '此用户不存在',
	'data_not_exists' => '指定的数据不存在',
	'data_is_exists' => '数据已经存在',
	'add_success' =>'发布成功!',
	'edit_success' =>'修改成功!',
	'please_select_area' => '请选择地区',
	'please_select_type' => '请选择类别',
	'please_select_catid' => '请选择栏目',
	'form_data_error' => '请将表单数据填写完整!',
    'to_login' => '您需要先登录才能继续本操作',
	'system_is_default' => '系统默认',
	'submit_invalid' => '您的请求来路不正确或表单验证串不符，无法提交。请尝试使用标准的web浏览器进行操作。',
	//attachment.php
	'not_upload_admin' =>'<font color=red>对不起,您没有文件上传权限！</font>',
	'image_size_failure' =>'<font color=red>图片上传失败,图片大小不能超过 \\1KB !</font>',
	'attach_size_failure' =>'文件上传失败,附件大小不能超过 \\1KB !',
	'file_mime_failure' =>'系统禁止上传 [\\1] MIME类型文件!',
	'file_extname_failure' =>'系统禁止扩展名为 \\1 的文件上传!',
	'upload_max_filesize' =>'文件大小超过了系统设置的允许上传最大值!',
	'file_upload_success' =>'文件上传成功!',
	'cutfile_exists' =>'图片已经被裁减过,请不要重复操作，系统会自动选择已裁减过图片!',
	'file_other_failure' =>'未知错误!',
	//comment.inc.php
	'review_post_success' =>'留言提交成功！',
	'review_post_check' =>'留言提交成功！等待管理员审核！',
	'review_post_failure' =>'写入数据时发生错误，请重试一次！',
	'review_minsize_limit' =>'对不起，书评内容不得少于 \\1 字节！',
	'review_maxsize_limit' =>'对不起，书评内容不得多于 \\1 字节！',
	//admin/content.inc.php
	'article_not_pages' =>'不分页',
	'article_auto_pages' =>'自动分页',
	'article_trigger_pages' =>'手动分页',
	'article_post_success' =>'发布成功',
	'article_update_success' =>'修改成功',
	'article_title_exists' =>'文章标题存在！',
	'article_title_noexists' =>'文章标题不存在！',
	'article_delete_success' =>'成功删除 <font color=\'blue\'>\\1</font> 条文章！',
	'article_recycle_success' =>'成功转移 <font color=\'blue\'>\\1</font> 条文章至回收站！',
	'article_is_errors' => '请求的文章不存在或未通过审核！',
	//admin/selectfile.php
	'file_dir' =>'文件夹',
	'file_dirtype' =>'<目录>',
	//admin/model.inc.php
	'model_default_items' => '选项值1|选项名称1',
	'model_not_exists' => '对不起，请求的模型不存在！',
	'modelfield_not_exists' => '请求的字段不存在！',
	'scorefield_not_edit' => '核心字段不允许编辑！',
	'modelfield_is_exists' => '字段名已存在！',
	'modelfield_error' => ' 字段名只能由英文字母、数字和下划线组成，必须以字母开头，不以下划线结尾',
	'modeltable_error' => ' 表名只能由英文字母、数字组成，必须以字母开头',
	'modeltable_is_exists' => '表名名已存在！',
	//admin/create.inc.php
	'index_upload_success' => '网站首页更新成功！<br>大小： <font color=\'blue\'>\\1</font>',
	'index_upload_failure' => '网站首页更新失败,请检查目录 <font color=\'blue\'>\\1</font> 是否有可写权限！',
	'start_upload_category' => '开始更新栏目页...',
	'not_upload_content' => '<font color=\'red\'>根据设定的条件没有查询到需要生成的文章内容！</font>',
	'start_upload_next' => '<font color=\'blue\'>\\1</font> 栏目不需要更新，开始跳过...',
	'category_upload_success' => '<font color=\'blue\'>\\1</font> 栏目更新完成！',
	'category_page_upload_success' => '<font color=\'blue\'>\\1</font> 栏目 <font color=\'red\'>\\2</font> 页更新完成！',
	'category_upload_failure' => '<font color=\'blue\'>\\1</font> 栏目更新失败,数据不存在或者目录没有写权限！',
	'show_upload_success' => '共需更新 <font color=\'red\'>\\1</font> 条信息<br />已完成 <font color=\'red\'>\\2</font> 条（<font color=\'red\'>\\3</font>）,错误 <font color=\'red\'>\\4</font> 条',
	'all_upload_success' => '更新完成！',
	//admin/collect.php
	'all_collect_task' => '全部采集任务',
	'collect_article_success' => '《<font color=blue>\\1</font>》采集成功！',
	'collect_article_failure' => '《<font color=blue>\\1</font>》写入数据时失败！',
	'collect_url_failure' => '读取对方网站失败，可能是对方无法访问或者本服务器禁止远程读取网页！<br />URL: <a href="\\1" target="_blank">\\2</a>',
	'collect_ajaxurl_failure' => '读取对方网站内容失败！【<a href="javascript:getPage(\\1);"><font color=blue>重试</font></a>】<br />URL: <a href="\\2" target="_blank">\\3</a>',
	'parse_articleid_failure' => '文章URL采集失败，可能是采集规则错误或者对方网站暂时无法打开！<br />URL: <a href="\\1" target="_blank">\\2</a>',
	'pageid_collect_next' => '本页采集完成，继续采集下一页的文章。<br /><br />本页是第 \\1 页，本次最多采集 \\2 页',
	'pageurl_collect_next' => '本页采集完成，继续采集下一页的文章。<br /><br />下一页：<a href="\\1">\\2</a>',
	'collect_title_exists' => '文章【<font color=blue>\\1</font>】已经存在，自动跳过。',
	'collect_fields_error' => '【\\1】解析失败，可能是对方网页格式变动导致采集规则错误！<br />URL：<a href="\\2" target="_blank">\\3</a><br />',
	//admin/category.php
	'category_add_success' =>'栏目添加成功!',
	'category_not_exists' => '对不起!系统不存在此目录！',
	'category_not_do' => '对不起!系统禁止将父栏目转移到子栏目！',
	'category_exists_arrchild' => '警告：此栏目下存在子目录，为了系统运行稳定，禁止此操作！'
);
?>