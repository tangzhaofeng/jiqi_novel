// JavaScript Document
$(document).ready(function(){
	$(".select_ul").remove(); //清除多余option list
//	$(".jNice select").hide();
	$(".jNice select").each(function(h,k){
		var o = $(k);
		//var p = $('<div class="ml_select"><p></p></div>');
		var p = $('<div class="ml_sel" ><input type="text" readonly="readonly"/><div class="down_sel"></div></div>')
		var ul = $('<ul class="select_ul"></ul>').hide();
		o.find('option').each(function(n,c){
			var op = $(c);
			var li = $('<li>' + op.text() + '</li>');
			//prop jQuery1.6以上 attr jQuery 1.6以下
			if(op.prop('selected') === true || op.attr('selected') === 'selected'){
				//p.children().append(op.text());
				p.children("input").val(op.text());
			}
			li.click(function(){
				var text = li.text();
				//p.children().text(text);
				p.children("input").val(op.text());
				o.find('option').each(function(m,a){
					if($(a).text() == text){
						//$(a).attr('selected','selected');//jQuery 1.6以下
						$(a).attr('selected', true); //jQuery 1.6以上
					} else {
						$(a).prop('selected', false);
						//$(a).removeAttr('selected');
					}
				});
				ul.parent().hide();
				o.trigger('change');
			}).hover(
			  function () {
				$(this).addClass("hover");
			  },
			  function () {
				$(this).removeClass("hover");
			  }
			);
			ul.append(li);
			//if(o.css('display') == 'none'){
			//	p.hide();
			//}
		});
		o.after(p);	
		$("<div class='ul_con'></div>").append(ul).appendTo($('body'));		
		
		//parseInt(ul.width())<=110 ? ul.width(110) : ul.width(200);
		ul.width(p.parent().width());
		
		if(parseInt(ul.height())>=300){
			ul.parent().height(300);	
			ul.parent().css({
				"overflow":"scroll",
				"overflow-x":"hidden",
				"display":"none"
			});
		}
		
		ul.parent().hover(function(){},function(){ul.hide(); ul.parent().hide();});
		
		p.width(p.parent().width());
		p.children("input").width(p.parent().width()-40);
		p.click(function(){
			$(".select_ul").hide();
			ul.parent().css("z-index","10");//新建分卷
			ul.parent().toggle();
			ul.parent().css({
				left:p.offset().left,
				top: p.offset().top	+28,
				width: ul.width() + 2
			});
			ul.slideToggle();
		});	
	});
	
//	$("a[setchart=1]").click(function(){
//		$(".nodis").hide();
//		m = $(this).next(".nodis");
//		f = $(this).attr("data");
//		m.css({
//			left:$(this).offset().left,
//			top: $(this).offset().top+17	
//		})
//		m.slideToggle();
//		m.children("div").hover(
//			function(){
//				$(this).addClass("hover");
//				m.show();	
//			},
//			function(){
//				$(this).removeClass("hover");	
//			}
//		)
//		m.mouseout(function(){
//			m.hide();	
//		})
//		m.children("div").click(function(){
//			v = $(this).text();	
//			k = $(this).attr("data");
//			$("#"+f).val(k);
//			$(this).parent().prev().text(v);
//			m.hide();
//		})
//	})
});

/**
 * 重新绑定select下拉列表，由于ready是在页面渲染时加载document动态绑定select的，但是如果在局部刷新时整体页面没有重新加载document，从而动态绑定的select会失效，这个方法就是解决局部刷新后需要在重新绑定select以显示实时数据。
 */
function bindselect(){
	$(".select_ul").remove(); //清除多余option list
	$(".jNice select").each(function(h,k){
		var o = $(k);//o:obj
		//var p = $('<div class="ml_select"><p></p></div>');
		var p = $('<div class="ml_sel"><input type="text" readonly="readonly"/><div class="down_sel"></div></div>')
		var ul = $('<ul class="select_ul"></ul>').hide();
		o.find('option').each(function(n,c){
			var op = $(c);
			var li = $('<li>' + op.text() + '</li>');
			//prop jQuery1.6以上 attr jQuery 1.6以下
			if(op.prop('selected') === true || op.attr('selected') === 'selected'){
				//p.children().append(op.text());
				p.children("input").val(op.text());
			}
			li.click(function(){
				var text = li.text();
				//p.children().text(text);
				p.children("input").val(op.text());
				o.find('option').each(function(m,a){
					if($(a).text() == text){
						//$(a).attr('selected','selected');//jQuery 1.6以下
						$(a).attr('selected', true); //jQuery 1.6以上
					} else {
						$(a).prop('selected', false);
						//$(a).removeAttr('selected');
					}
				});
				ul.parent().hide();
				o.trigger('change');
			}).hover(
			  function () {
				$(this).addClass("hover");
			  },
			  function () {
				$(this).removeClass("hover");
			  }
			);
			ul.append(li);
			//if(o.css('display') == 'none'){
			//	p.hide();
			//}
		});
		o.after(p);	
		$("<div class='ul_con'></div>").append(ul).appendTo($('body'));		
		
		//parseInt(ul.width())<=110 ? ul.width(110) : ul.width(200);
		ul.width(p.parent().width());
		
		if(parseInt(ul.height())>=300){
			ul.parent().height(300);	
			ul.parent().css({
				"overflow":"scroll",
				"overflow-x":"hidden",
				"display":"none"
			});
		}
		
		ul.parent().hover(function(){},function(){ul.hide(); ul.parent().hide();});
		
		p.width(p.parent().width());
		p.children("input").width(p.parent().width()-40);
		p.click(function(){
			$(".select_ul").hide();
			ul.parent().css("z-index","10");//新建分卷
			ul.parent().toggle();
			ul.parent().css({
				left:p.offset().left,
				top: p.offset().top	+28,
				width: ul.width() + 2
			});
			ul.slideToggle();
		});	
	});
}
/**
 * 二级下拉列表重新填充数据，需要局部重新绑定刷新。
 */
function bindselectOnId(ssid){
//	$(".select_ul").remove(); //清除多余option list
	$(".jNice select").each(function(h,k){
		if(k.id == ssid){
			var o = $(k);//o:obj
			//var p = $('<div class="ml_select"><p></p></div>');
			var p = $('<div class="ml_sel"><input type="text" readonly="readonly"/><div class="down_sel"></div></div>')
			var ul = $('<ul class="select_ul"></ul>').hide();
			o.find('option').each(function(n,c){
				var op = $(c);
				var li = $('<li>' + op.text() + '</li>');
				//prop jQuery1.6以上 attr jQuery 1.6以下
				if(op.prop('selected') === true || op.attr('selected') === 'selected'){
					//p.children().append(op.text());
					p.children("input").val(op.text());
				}
				li.click(function(){
					var text = li.text();
					//p.children().text(text);
					p.children("input").val(op.text());
					o.find('option').each(function(m,a){
						if($(a).text() == text){
							//$(a).attr('selected','selected');//jQuery 1.6以下
							$(a).attr('selected', true); //jQuery 1.6以上
						} else {
							$(a).prop('selected', false);
							//$(a).removeAttr('selected');
						}
					});
					ul.parent().hide();
					o.trigger('change');
				}).hover(
				  function () {
					$(this).addClass("hover");
				  },
				  function () {
					$(this).removeClass("hover");
				  }
				);
				ul.append(li);
				//if(o.css('display') == 'none'){
				//	p.hide();
				//}
			});
			//移除div元素
			o.next().remove();
			o.after(p);	
			$("<div class='ul_con'></div>").append(ul).appendTo($('body'));		
			
			//parseInt(ul.width())<=110 ? ul.width(110) : ul.width(200);
			ul.width(p.parent().width());
			
			if(parseInt(ul.height())>=300){
				ul.parent().height(300);	
				ul.parent().css({
					"overflow":"scroll",
					"overflow-x":"hidden",
					"display":"none"
				});
			}
			ul.parent().hover(function(){},function(){ul.hide(); ul.parent().hide();});
			
			p.width(p.parent().width());
			p.children("input").width(p.parent().width()-40);
			p.click(function(){
				$(".select_ul").hide();
				ul.parent().css("z-index","10");//新建分卷
				ul.parent().toggle();
				ul.parent().css({
					left:p.offset().left,
					top: p.offset().top	+28,
					width: ul.width() + 2
				});
				ul.slideToggle();
			});	
		}
	});
}