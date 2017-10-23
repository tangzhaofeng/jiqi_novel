$(function(){
//******任务管理模块：START******
	// 封装一个编辑图层函数
	var formTips = function(showUrl, id, type) {
		GPage.getJson(showOneUrl+"&type="+type+"&tid="+id, function(data){
			if (data.status=="OK") {
				var _form = $($('#J_add_form')[0]);
				_form.find("input[name=taskid]").val(data.msg.taskid);
				_form.find("input[name=taskname]").val(data.msg.taskname);
				_form.find("textarea[name=description]").val(data.msg.description);
				_form.find("select[name=type]").val(data.msg.type);
				_form.find("tr").eq(2).after(data.msg.ruleForm);
				if(_form.find(".sign_option")!='') {
					_form.find("tr").eq(2).after(data.msg.rewardsForm);
				} else {
					_form.find(".sign_option").after(data.msg.rewardsForm);
				}
				if(data.msg.isshow == 0){
					_form.find("input[name=isshow]").eq(1).attr('checked','checked');
					_form.find("input[name=isshow]").eq(0).removeAttr('checked');
				}else{
					_form.find("input[name=isshow]").eq(0).attr('checked','checked');
					_form.find("input[name=isshow]").eq(1).removeAttr('checked');
				}
				layer.autoArea(0);
			} else {
				layer.msg(data.msg);
			}
		})
	}
	
	var this_layer;
	
	// 添加任务按钮
	$("#add_task").on("click", function(){
		$('#J_add_form')[0].reset();
		$(".sign_option").remove();
		$("#J_task_submit").attr("data-url", addFormUrl);
		this_layer = $.layer({
			type : 1,
			area : ['600px', 'auto'],
			title : '添加一个新的任务',
			offset : ['30px' , '50%'],
//			zIndex : 1,
			page : {dom : '#J_add_task'},
			close : function(index){
				layer.close(index);
				$('.ul_con').hide();
			}
		});
	});
	// 编辑任务按钮
	$(".J_edit_task").on("click", function(){
		$('#J_add_form')[0].reset();
		$(".sign_option").remove();
		var tid = $(this).parent().attr("data-id");
		var type = $(this).parent().attr("data-type");
		formTips(showOneUrl, tid, type);
		$("#J_task_submit").attr("data-url", editFormUrl);
		$.layer({
			type : 1,
			area : ['600px', 'auto'],
			title : '编辑一个任务',
			offset : ['30px' , '50%'],
//			zIndex : 1,
			page : {dom : '#J_add_task'},
			close : function(index){
				layer.close(index);
				$('.ul_con').hide();
			}
		});
	});
	// 删除任务按钮
	$(".J_del_task").on("click", function(){
		var tid = $(this).parent().attr("data-id");
		var _this_tr = $(this).parent().parent();
		layer.confirm('任务一旦删除则不可恢复，确认删除？', function(){
		   	GPage.getJson(delFormUrl+"&taskid="+tid, function(data){
				if (data.status=="OK") {
					layer.closeAll();
					layer.msg('操作成功', 1, 1);
					_this_tr.remove();
				} else {
					layer.closeAll();
					layer.msg(data.msg);
				}
			});
		}, '确认删除', function(){
			layer.closeAll();
		});
//		$.layer({
//		    shade: [0],
//		    area: ['auto','auto'],
//		    dialog: {
//		        msg: '任务一旦删除则不可恢复，确认删除？',
//		        btns: 2,                    
//		        type: 4,
//		        btn: ['确认','取消'],
//		        yes: function(){
//			        	GPage.getJson(delFormUrl+"&taskid="+tid, function(data){
//						if (data.status=="OK") {
//							layer.closeAll();
//							layer.msg('操作成功', 1, 1);
//							_this_tr.remove();
//						} else {
//							layer.closeAll();
//							layer.msg(data.msg);
//						}
//					})
////		            layer.msg('重要', 1, 1);
//		        }, no: function(){
//		          	layer.closeAll();
//		        }
//		    }
//		});
	})

	
	// 异步获取任务类型列表
	$("#J_task_type").on("change", function(){
		var typeName = $(this).val();
		var _this = $(this);
		console.log(this_layer);
		if (typeName == 0) {
			$(".sign_option").remove();
			layer.autoArea(this_layer);
			return false;
		}
		GPage.getJson(typeUrl+"&type="+typeName, function(data){
			if (data.status=="OK") {
				$(".sign_option").remove();
				_this.parent().parent().after(data.msg);
				layer.autoArea(this_layer);
			} else {
				layer.msg(data.msg);
			}
		})
	})
	
/*	var checkForm = function (selor,name){
	  var task = $(""+selor+"[name='"+name+"']");
	  var this_length = arguments[2] ? arguments[2] : 0;
	  if(name == 'type' && task.val() == 0){
		alert('请选择任务类型');
		return false;
	  }else if(task.val() == "" || task.val().length > this_length && name != 'type'){
		alert('不得为空或超过'+this_length+'个字');
		task.focus();
		return false;
	  }
	  return true;
	}*/
	
	function check_form(){
		this.check = function(selor,name){
			this.task = $(""+selor+"[name='"+name+"']");
			this.this_length = arguments[2] ? arguments[2] : 0;
		  if(name == 'type' && this.task.val() == 0){
			alert('请选择任务类型');
			return false;
		  }else if(this.task.val() == "" || this.task.val().length > this.this_length && name != 'type'){
			alert('不得为空或超过'+this.this_length+'个字');
			this.task.focus();
			return false;
		  }
		  return true;
		}
	}
	
	// 提交一个任务处理：新加/编辑
	$("#J_task_submit").on("click", function(){
		var thisUrl = $(this).attr("data-url");
		if($("#grade_type").length>0){
			if($("#grade_type").val()=='isvip'){
				$("#score input").val('').remove();
			}else if($("#grade_type").val()=='score'){
				$("#isvip input").val('').remove();
			}
		}
	  /*var task_name = $("input[name='taskname']");
		var task_description = $("textarea[name='description']");
		var task_type = $("select[name='type']");*/
		var check = new check_form();
		//alert(check.this_length);
		if(!check.check("input","taskname",10)) return false;
		if(!check.check("textarea","description",300)) return false;
		if(!check.check("select","type")) return false;
		/*if (!checkForm("input","taskname",10)) return false;
		if (!checkForm("textarea","description",300)) return false;
		if (!checkForm("select","type")) return false;*/
		
		GPage.postForm('J_add_form', thisUrl, function(data){
			
			if (data.status=="OK") {
//				layer.closeAll();
//				layer.msg('操作成功', 1, 1);
				location.reload();
			} else {
				layer.msg(data.msg);
			}
		})
	})
	
	// 等级上升判断依据
	$("#grade_type").live("change", function(){
		var typeName = $(this).val();
		var _this = $(this);
		if (typeName == 'score') {
			$("#score").show();
			$("#isvip").hide();
//			$(".sign_option").remove();
			layer.autoArea(this_layer);//this_
			return false;
		}else if(typeName == 'isvip'){
			$("#score").hide();
			$("#isvip").show();
			layer.autoArea(this_layer);//this_
			return false;
		}
	})
//******任务管理模块：END******

//******题库管理模块：START******
	// 添加任务按钮
	$("a[data-act=add_question]").on("click", function(){
		$('#J_add_form')[0].reset();
		$("p[data-p=articlename]").val("");
		$("#J_question_submit").attr("data-url", addFormUrl);
		this_layer = $.layer({
			type : 1,
			area : ['auto', 'auto'],
			title : '添加新题目',
			offset : ['30px' , '50%'],
			page : {dom : '#J_add_question'},
			close : function(index){
				layer.close(index);
				$('.ul_con').hide();
			}
		});
	});
	
	// 编辑任务按钮
	$("a[data-act=edit]").on("click", function(){
		var _id = $(this).parent().parent("tr").attr("data-id");
		var _form = $('#J_add_form')[0];
		$("#J_question_submit").attr("data-url", editFormUrl);
		_form.reset();
		// 封装编辑页面
		GPage.getJson(urlParams(showOneUrl, "qid="+_id), function(data){
			if (data.status=="OK") {
				// 重建编辑列表
//				var _questionnumber = 0;
//				for (key in data.msg.options) {
//					_questionnumber++;
//				}
//				var _htmls = "";
				$($(_form).find("textarea[name=question]")).val(data.msg.question);
//				$(_form).find("select[name=questionnumber]").val(_questionnumber);
//				if (_questionnumber>=3 && $("tr[data-option=3]").length<=0) {
//					_htmls += '<tr data-option="3"><th class="td_title">选项C</th><td class="td_contents"><input class="text" style="width: 200px" type="text" name="options[3]"/>&nbsp;&nbsp;<input type="radio" name="rightoption" value="3" /></td><td class="td_span"><span></span></td></tr>';
//					$("tr[data-option=4]").remove();
//				}
//				if (_questionnumber==4 && $("tr[data-option=4]").length<=0) {
//					_htmls += '<tr data-option="4"><th class="td_title">选项D</th><td class="td_contents"><input class="text" style="width: 200px" type="text" name="options[4]"/>&nbsp;&nbsp;<input type="radio" name="rightoption" value="4" /></td><td class="td_span"><span></span></td></tr>';
//				}
//				if (_questionnumber==2) {
//					$("tr[data-option=3]").remove();
//					$("tr[data-option=4]").remove();
//				}
//				$("tr[data-option=2]").after(_htmls);
				$.each(data.msg.options, function(i, n){
					$(_form).find("tr[data-option="+i+"]").find("input:text").val(n);
				})
				$(_form).find("input[name=rightoption][value="+data.msg.rightoption+"]").attr("checked", true);
				$(_form).find("input[name=aid]").val(data.msg.aid);
				$(_form).find("p[data-p=articlename]").html("《"+data.msg.articlename+"》");
				$(_form).find("input[name=qid]").val(_id);
			} else {
				layer.msg(data.msg);
				return false;
			}
		})
		$("input[name=aid]").val(_id);
		
		this_layer = $.layer({
			type : 1,
			area : ['auto', 'auto'],
			title : '编辑题库问题',
			offset : ['30px' , '50%'],
			page : {dom : '#J_add_question'},
			close : function(index){
				layer.close(index);
				$('.ul_con').hide();
			}
		});
		layer.autoArea(this_layer);
	});
	
	// 预览功能
	$("a[data-act=showone]").on("click", function(){
		var _id = $(this).parent().parent("tr").attr("data-id");
		var _htmls = "";
		GPage.getJson(urlParams(showOneUrl, "qid="+_id), function(data){
			if (data.status=="OK") {
				_htmls += "<p style='font-weight: 700'>"+data.msg.question+"</p><hr />";
				$.each(data.msg.options, function(i, n){
					if (data.msg.rightoption == i) {
						_htmls += "<p style='color:green'>选项"+i+"：";
					} else {
						_htmls += "<p>选项"+i+"：";
					}
					_htmls += n+"</p>";
				})
				layer.alert(_htmls, 4, !1);
			} else {
				layer.msg(data.msg);
			}
		})
	})
	
	// 删除功能
	$("a[data-act=del]").on("click", function(){
		var _this_tr = $(this).parent().parent("tr");
		var _id = _this_tr.attr("data-id");
			layer.confirm('任务一旦删除则不可恢复，确认删除？', function(){
				GPage.getJson(urlParams(delFormUrl, "qid="+_id), function(data){
				if (data.status=="OK") {
					_this_tr.remove();
					layer.msg("删除成功", 1, 1);
				} else {
					layer.msg(data.msg);
				}
			})
		})
	})
	
	// 选项验证
	$("input[name=rightoption]").on("change", function(){
		if ($.trim($(this).prev("input").val())=='') {
			layer.alert("该选项没有内容");
			$(this).removeAttr("checked");
		}
	})
	
	// 变更选项数量
//	$("select[data-act=questionnumber_change]").on("change", function(){
//		var _this = $(this);
//		var _htmls_options = $("tr[data-option]");
//		var _htmls = "";
//		if (_this.val()=="2") {
//			$("tr[data-option=3]").children().children("input:text").attr("readonly", "readonly").removeAttr("name");
//			$("tr[data-option=4]").children().children("input:text").attr("readonly", "readonly").removeAttr("name");
//			$("tr[data-option=3]").children().children("input:text").attr("placeholder", "不可选").val("");
//			$("tr[data-option=4]").children().children("input:text").attr("placeholder", "不可选").val("");
//		} else if (_this.val()=="3") {
//			if ($("tr[data-option=3]").length<=0) {
//				$("tr[data-option=3]").children().children("input:text").attr("name", "options[3]").removeAttr("readonly");
//			}
//			$("tr[data-option=4]").children().children("input:text").attr("readonly", "readonly").removeAttr("name");
//			$("tr[data-option=4]").children().children("input:text").attr("placeholder", "不可选").val("");
//		} else if (_this.val()=="4") {
//			if ($("tr[data-option=3]").length<=0) {
//				$("tr[data-option=3]").children().children("input:text").attr("name", "options[3]").removeAttr("readonly");
//			}
//			if ($("tr[data-option=4]").length<=0) {
//				$("tr[data-option=4]").children().children("input:text").attr("name", "options[3]").removeAttr("readonly");
//			}
//		}
////		_htmls_options.last().after(_htmls);
////		layer.autoArea(this_layer);
//	})
	
	// 变更书籍ID的返回方法
	$("input[data-act=articleid_change]").on("focusout", function(){
		var _aid = $(this).val();
		if (_aid!="") {
			GPage.getJson(urlParams(checkUrl,"aid="+_aid), function(data){
				if (data.status=="OK") {
					$("p[data-p=articlename]").html("《"+data.msg+"》");
				} else {
					$("p[data-p=articlename]").html("该书籍ID不存在");
				}
			})
		}
	})
	
	// 增加/编辑操作
	$("#J_question_submit").on("click", function(){
		var _url = $(this).attr("data-url");
//		var _form = $("#J_add_form");
		GPage.postForm('J_add_form', _url, function(data){
			if (data.status=="OK") {
//				layer.msg('处理成功', 1, 1);
				location.reload();
			} else {
				layer.msg(data.msg);
			}
		})
	})
//******题库管理模块：END******
})





















