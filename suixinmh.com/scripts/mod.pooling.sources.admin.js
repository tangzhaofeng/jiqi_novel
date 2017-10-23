$(function(){
	// 提交事件
	$("#j_submit").on("click", function(){
		var _back_url = $("a[data-act=act]").attr("href");
		var _form = $("#j_form");
		var _url = _form.attr("data-url");
		var _sname = $("input[name=sname]");
		var _markname = $("input[name=markname]");
		var _name = $("input[name=name]");
		var _compos = $("input[name=compos]");
		var _locked = $("input[name=locked]");
		// 表单内容验证
		if (_sname.val()=="" || _sname.val().length>25) {
			layer.msg("渠道名称字数不能为空或超过25个字");
			_sname.focus();
			return false;
		} else if (_markname.val()=="" || _markname.val().length>25) {
			layer.msg("标识字符不能为空或超过25个字");
			_markname.focus();
			return false;
		} else if (_name.val()=="" || _name.val().length>25) {
			layer.msg("登录名不能为空或超过25个字");
			_name.focus();
			return false;
		} else if (_compos.val()=="" || _compos.val().length>2) {
			layer.msg("排序只能使用0~99的数字");
			_markname.focus();
			return false;
		} else {
			// 验证通过则提交数据
			GPage.postForm('j_form', _url, function(data){
				if (data.status=="OK") {
					// TODO::成功跳转
					 window.location.href = _back_url;
				} else {
					// TODO::失败提示
					layer.msg(data.msg,1,1);
				}
			})
		}
	})
	
	// 显示密码
	$("span[data-pw-act=act]").on("click", function(){
		var _pw = $(this).attr("data-pw");
		$(this).removeClass().html(_pw);
	})
	
	// 删除记录
	$("a[data-act=delete]").on("click", function(){
		var _this_tr = $(this).parent().parent();
		var _url = $(this).attr("data-url");
		var _id = _this_tr.attr("data-id");
		GPage.getJson(_url+"&sid="+_id, function(data){
			if (data.status=="OK") {
				_this_tr.remove();
				layer.msg("删除成功");
			} else {
				layer.alert(data.msg);
			}
		})
	})
	
	// 排序变动设置
	$("input[name=byorder]").on("change", function(){
		var _value = $(this).val();
		var _ovalue = $(this).attr("data-ori");
		if (_value != _ovalue) {
			$(this).attr("data-act", "change");
		} else {
			$(this).attr("data-act", "none");
		}
	})
	
	// 批量修改排序
	$("a[data-act=order]").on("click", function(){
		var _url = $(this).attr("data-url");
		var _params = "";
		// 如果没有更改则终止本次事件
		if ($("input[name=byorder][data-act=change]").index() < 0) {
			return false;
		}
		$.each($("input[name=byorder][data-act=change]"), function(i,n){
			var _id = $(n).parent().parent().attr("data-id");
			var _val = $(n).val();
			if (_val<0){_val=0}
			if (_val>99){_val=99}
			_params += "&sid_"+_id+"="+_val;
		})
		GPage.getJson(_url+_params, function(data){
			if (data.status=="OK") {
				location.reload();
			} else {
				layer.msg(data.msg);
			}
		})
	})
	layer.ready(function(){
		$('#login_form').bind('valid.form', function(e){
			e.preventDefault();
			GPage.postForm('login_form', this.action,function(data){
					if(data.status=='OK'){
						jumpurl(data.jumpurl);
					}else{
						layer.msg(data.msg);
						if(data.msg == '对不起，校验码错误！'){
							$("[name='checkcode']").focus();
						}else if(data.msg == '密码错误，请注意字母大小写是否输入正确！！'){
							$("[name='password']").focus();
						}else if(data.msg =='该用户不存在，请注意字母大小写是否输入正确！'){
							$("[name='username']").focus();
						}
					}
			   });
		});
	});
})
















