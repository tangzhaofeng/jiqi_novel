/**
 * $description	: 任务系统的所有javascript内容
 * 				: *需要jQuery支持
 * $copyright	: shuhai@2015-01-13
 * $createtime	: 2015-01-13
 */
$(function(){
	// ***前台管理类
	// 用户点击完成按钮
	$(".J_set_finish").on("click", function(){
		var taskid = $(this).attr("data-id");
		var _this = $(this);
		GPage.getJson(urlParams(sendUrl,"tid="+taskid), function(data){
			if (data.status=="OK") {
				layer.msg('您已完成该任务，请再接再厉！',1,1);
				_this.removeClass();
				_this.addClass("taked");
				_this.html("");
				_this.html("已完成");
				_this.off("click");
			} else {
				layer.msg(data.msg);
			}
		})
	})
	

})
