$(function(){
	// 全局初始化
	var description_size = $("[data-name=size_p] b") ? $("[data-name=size_p] b").html() : 100;
	var less_size = $("[data-name=size_p] span");
	// 初始编辑中的字数
	var init_size = $("textarea[name=description]").val().length;
	less_size.html(description_size-init_size);
	// 分类表单前端验证
	$("form[name=myform]").live("submit", function(){
		var _name = $("input[name=name]").val();
		var _module = $("input[name=module]").val();
		var _description = $("textarea[name=description]").val();
		var en_preg = new RegExp("^[a-zA-Z1-9]{1,20}$");
		if (_name.length<=0 || _name.length>20) {
			layer.alert("分类名称必须在1~20个字之间");
			return false;
		}
		if (!en_preg.test(_module)) {
			layer.alert("所述模块只能使用1~20个英文");
			return false;
		}
		if (_description.length>80) {
			layer.alert("分类描述文字不能超过80个字");
			return false;
		}
	})
	// 计算描述文字输入字数
	$("textarea[name=description]").live("keydown", function(){
		var _this_size = $("textarea[name=description]").val().length;
		var _less = description_size - _this_size;
		less_size.html(_less);
	})
})
