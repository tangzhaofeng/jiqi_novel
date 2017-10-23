var GPage = new PageLoad();
var HOST_URL = 'http://' + document.domain + '/';
HOST_URL = HOST_URL.replace('wen','w');
var ContentTag = 'jieqi_contents';//内容块
//function get_cookie_value(Name) { 
//  var search = Name + "=";
//　var returnvalue = ""; 
//　if (document.cookie.length > 0) { 
//　  offset = document.cookie.indexOf(search) 
//　　if (offset != -1) { 
//　　  offset += search.length 
//　　  end = document.cookie.indexOf(";", offset); 
//　　  if (end == -1) 
//　　  end = document.cookie.length; 
//　　  returnvalue=unescape(document.cookie.substring(offset, end));
//　　} 
//　} 
//　return returnvalue; 
//}
// jquery1.7.2.min.js 滑动门

$(function() {
					   
	jQuery.jqtab = function(tabtit,tab_conbox,shijian) {
		
		$(tab_conbox).find("li").hide();
		$(tab_conbox).find(".st").find("li").show();		
		$(tabtit).find("li:first").addClass("thistab").show(); 
		$(tab_conbox).find("li:first").show();
		$('#tabs12 li').show();
	
		$(tabtit).find("li").bind(shijian,function(){
		  $(this).addClass("thistab").siblings("li").removeClass("thistab"); 
			var activeindex = $(tabtit).find("li").index(this);
			$(tab_conbox).children().eq(activeindex).show().siblings().hide();
			return false;
		});
	
	};
	//document.write("<script language='javascript' src='commpage.js'></script>");
	/*调用方法如下：*/
	$.jqtab("#tabs","#tab_conbox","click");
	
	$.jqtab("#tabs1","#tab_conbox1","mouseenter");
	
	$.jqtab("#tabs2","#tab_conbox2","mouseenter");
	
	$.jqtab("#tabs3","#tab_conbox3","mouseenter");
	
	$.jqtab("#tabs4","#tab_conbox4","mouseenter");
	
	$.jqtab("#tabs5","#tab_conbox5","mouseenter");
	
	$.jqtab("#tabs6","#tab_conbox6","mouseenter");
	
	$.jqtab("#tabs7","#tab_conbox7","mouseenter");
	
	$.jqtab("#tabs8","#tab_conbox8","mouseenter");
	
	$.jqtab("#tabs9","#tab_conbox9","mouseenter");
	
	$.jqtab("#tabs10","#tab_conbox10","mouseenter");
	
	$.jqtab("#tabs11","#tab_con11","click");
	
	$.jqtab("#tabs12","#tab_con12","click");
	//公用点击
	$("[ajaxclick]").live('click',function(event){
		event.preventDefault();
		var retruemsg = $(this).attr("retruemsg");
		var confirm_msg = $(this).attr("confirm");
		var targetid = $(this).attr("targetid");
		if(confirm_msg){
		     if(!confirm(confirm_msg)) return false;
		}
		var i = layer.load(0);//layer.alert('加载中...',-1, !1);
		if(!targetid) var targetid = 'content';
		GPage.getJson(this.href,function(data){
				layer.close(i);
			    if(data.status=='OK'){
					if(retruemsg!='false' &&  retruemsg) layer.msg(data.msg, 1, 1);//alert(data.jumpurl);
					GPage.loadpage(targetid, data.jumpurl, true,false);
				}else{
					layer.alert(data.msg, 8, !1);
				}
		});
	});	
	//post方法提交ajax
	$("[ajaxpost]").bind('valid.form',function(event){
		event.preventDefault();
		var retruemsg = $(this).attr("retruemsg");
		var formid = $(this).attr("id");
		var formaction = $(this).attr("action");
			var i = layer.load(0);//layer.alert('加载中...',-1, !1);
			GPage.postForm(formid, formaction,
				function(data){
					layer.close(i);
					if(data.status=='OK'){
						if(retruemsg!='false' &&  retruemsg) layer.msg(data.msg, 1, 1);
						/*layer.msg(data.msg,1,{type:1,shade:false},function(){
							jumpurl(data.jumpurl);
						});*/
						jumpurl(data.jumpurl,0);
					}else{
						layer.alert(data.msg, 8, !1);
					}
				}
			);
	});
	//post方法提交不带验证
	$("[ajaxsubmit]").bind('submit',function(event){
		event.preventDefault();
		var retruemsg = $(this).attr("retruemsg");
		var formid = $(this).attr("id");
		var formaction = $(this).attr("action");
			var i = layer.load(0);//layer.alert('加载中...',-1, !1);
			GPage.postForm(formid, formaction,
				function(data){
					layer.close(i);
					if(data.status=='OK'){
						if(retruemsg!='false' &&  retruemsg) layer.msg(data.msg, 1, 1);
						/*layer.msg(data.msg,1,{type:1,shade:false},function(){
							jumpurl(data.jumpurl);
						});*/
						jumpurl(data.jumpurl,0);
					}else{
						layer.alert(data.msg, 8, !1);
					}
				}
			);
	});
	//删除ajax提交
	$("[delajax]").live('submit', function(event){
		event.preventDefault();
		var formid = $(this).attr("id");
		var confirm_msg = $(this).attr("confirm");
		var formaction = $(this).attr("action");
		var targetid = $(this).attr("targetid");
		if(!targetid) var targetid = 'content';
		var checkform = document.getElementById(formid);
		var checknum = 0;
		for (var i=0; i < checkform.elements.length; i++){
		 if (checkform.elements[i].name == 'checkid[]' && checkform.elements[i].checked == true) checknum++; 
		}
		if(checknum == 0){
				layer.msg('请先选择要操作的书目!',2,{type:3,shade:false});
		}else{
			$.layer({
				shade : [1], //不显示遮罩
				area : ['auto','auto'],
				title:'确定操作',
				dialog : {
					msg:confirm_msg,
					btns : 2, 
					type : 4,
					btn : ['确定','取消'],
					yes : function(){
						var i = layer.load(0);//layer.alert('加载中...',-1, !1);
						GPage.postForm(formid, formaction,
							function(data){
								layer.close(i);
								if(data.status=='OK'){
									$.ajaxSetup ({ cache: false });
									layer.msg(data.msg,1,{type:1,shade:false},function(){
										$('#'+targetid).load(location.href+ ' #'+targetid+'>*');
									});
								}else{
									layer.alert(data.msg, 8, !1);
								}
							}
						);
					},
					no : function(){
						//$(".xubox_close").click();
						layer.closeAll();
						checkform.reset();
					}
				}
			});
		}
	});
	//显示用户信息层
	$("[ajaxhover]").live({mouseenter:function(){
											   
			if((layer.index-1)>0) layer.close(layer.index-1);
			var uid = $(this).attr("uid");
			var _this = this;
			$('<div id="jia_'+uid+'" style="display:none;"></div>').appendTo($('body'));
			$("#jia_"+uid).load(HOST_URL+"user/popuser?uid="+uid+'&date='+Math.random()+" #content>*",function(){
				 layer.tips($("#jia_"+uid).html(), _this, {
					guide: 1,
					closeBtn: false,
					style: ['width:356px; padding:0;', 'gray']
				});
			});
		},mouseleave:function(){
			var uid = $(this).attr("uid");
			$("#jia_"+uid).remove();
			
			var start = setTimeout(function(){
				layer.close(layer.index);
				//$("#jia_"+uid).remove();
			}, 50);
			
			$('.pop3').mouseenter(function(){
				clearTimeout(start);
			});
			$('.pop3').mouseleave(function(){
				layer.closeTips();
				//$("#jia_"+uid).remove();
			});
		}
	});
	
	$("form[ajaxform]").live('submit',function(event){
		if(this.J_search_suggest.value=='键入书名、作者名开始搜索') this.J_search_suggest.value='';
		if(this.J_search_suggest){
			location.href=this.action+'/'+encodeURIComponent(this.J_search_suggest.value);
		}
	});
	$("#J_search_suggest").on('focus',function(event){
		 if(this.value==$("#J_search_suggest").attr('data-placeholder')) this.value='';
	});
	$("#J_search_suggest").on('blur',function(event){
		 if(this.value=='') this.value=$("#J_search_suggest").attr('data-placeholder');
	});
	if($("#J_search_suggest").val()=='') $("#J_search_suggest").val($("#J_search_suggest").attr('data-placeholder'));
	//首页图文切换
	$("div.qh dl").each(function(){
		var con = $(this);
		var img = con.find("dd.im");
		var txt = con.find("dd.tt");
		con.find("dd.tt").each(function(){
			$(this).bind("mouseover",function(){
				var index = $(this).index(txt);
				img.hide();
				txt.show();
				$(this).prev().show();//alert("1");
				$(this).hide();
			});
		});
	});
});

/*	$("#pcontent").on('focus',function(event){
		 if(this.value==$("#pcontent").attr('data-placeholder')) this.value='';
	});
	$("#pcontent").on('blur',function(event){
		 if(this.value=='') this.value=$("#pcontent").attr('data-placeholder');
	});
	if($("#pcontent").val()=='') $("#pcontent").val($("#pcontent").attr('data-placeholder'));
});*/

// jquery1.7.2.min.js 滑动门2
//$(document).ready(function() {
//	jQuery.jqtabb = function(tabtit,tab_con,shijian) {
//		$(tab_con).find("li").hide();
//		$(tabtit).find("li:first").addClass("thistab").show(); 
//		$(tab_con).find("li:first").show();
//	
//		$(tabtit).find("li").bind(shijian,function(){
//		  $(this).addClass("thistab").siblings("li").removeClass("thistab"); 
//			var activeindex = $(tabtit).find("li").index(this);
//			$(tab_con).children().eq(activeindex).show().siblings().hide();
//			return false;
//		});
//	
//	};
	/*调用方法如下：*/

	
// 单行滚动
function AutoScroll(obj){
$(obj).find("ul:first").animate({
marginTop:"-25px"
},500,function(){
$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
});
}
$(function(){
setInterval('AutoScroll("#scrollDiv")',3000)
});
//多行滚动
$(function(){
	//多行应用@Mr.Think
	var _wrap=$('dl.mulitline');//定义滚动区域
	var _ddnum=_wrap.find('dd').length;
	if(_ddnum < 9)return;
	var _interval=3000;//定义滚动间隙时间
	var _moving;//需要清除的动画
	_wrap.hover(function(){
		clearInterval(_moving);//当鼠标在滚动区域中时,停止滚动
	},function(){
		_moving=setInterval(function(){
			var _field=_wrap.find('dd:first');//此变量不可放置于函数起始处，li:first取值是变化的
			var _h=_field.height();//取得每次滚动高度
			_field.animate({marginTop:-_h+'px'},600,function(){//通过取负margin值，隐藏第一行
				_field.css('marginTop',0).appendTo(_wrap);//隐藏后，将该行的margin值置零，并插入到最后，实现无缝滚动
			})
		},_interval)//滚动间隔时间取决于_interval
	}).trigger('mouseleave');//函数载入时，模拟执行mouseleave，即自动滚动
});

//支持CSS3
$(function() {
    if (window.PIE) {
        $('.rounded').each(function() {
            PIE.attach(this);
        });
    }
});

//幻灯片
$(function() {
	var sWidth = $("#focus").width(); //获取焦点图的宽度（显示面积）
	var len = $("#focus ul li").length; //获取焦点图个数
	var index = 0;
	var picTimer;
	
	//以下代码添加数字按钮和按钮后的半透明长条
	var btn = "<div class='btnBg'></div><div class='bbtn'>";
	for(var i=0; i < len; i++) {
		btn += "<span>" + (i+1) + "</span>";
	}
	btn += "</div>"
	$("#focus").append(btn);
	$("#focus .btnBg").css("opacity",0.5);
	
	//为数字按钮添加鼠标滑入事件，以显示相应的内容
	$("#focus .bbtn span").mouseenter(function() {
		index = $("#focus .bbtn span").index(this);
		showPics(index);
	}).eq(0).trigger("mouseenter");
	
	//本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
	$("#focus ul").css("width",sWidth * (len + 1));
	
	//鼠标滑入某li中的某div里，调整其同辈div元素的透明度，由于li的背景为黑色，所以会有变暗的效果
	$("#focus ul li div").hover(function() {
		$(this).siblings().css("opacity",0.7);
	},function() {
		$("#focus ul li div").css("opacity",1);
	});
	
	//鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
	$("#focus").hover(function() {
		clearInterval(picTimer);
	},function() {
		picTimer = setInterval(function() {
			if(index == len) { //如果索引值等于li元素个数，说明最后一张图播放完毕，接下来要显示第一张图，即调用showFirPic()，然后将索引值清零
				showFirPic();
				index = 0;
			} else { //如果索引值不等于li元素个数，按普通状态切换，调用showPics()
				showPics(index);
			}
			index++;
		},3000); //此3000代表自动播放的间隔，单位：毫秒
	}).trigger("mouseleave");
	
	//显示图片函数，根据接收的index值显示相应的内容
	function showPics(index) { //普通切换
		var nowLeft = -index*sWidth; //根据index值计算ul元素的left值
		$("#focus ul").stop(true,false).animate({"left":nowLeft},500); //通过animate()调整ul元素滚动到计算出的position
		$("#focus .bbtn span").removeClass("on").eq(index).addClass("on"); //为当前的按钮切换到选中的效果
	}
	
	function showFirPic() { //最后一张图自动切换到第一张图时专用
		$("#focus ul").append($("#focus ul li:first").clone());
		var nowLeft = -len*sWidth; //通过li元素个数计算ul元素的left值，也就是最后一个li元素的右边
		$("#focus ul").stop(true,false).animate({"left":nowLeft},500,function() {
			//通过callback，在动画结束后把ul元素重新定位到起点，然后删除最后一个复制过去的元素
			$("#focus ul").css("left","0");
			$("#focus ul li:last").remove();
		}); 
		$("#focus .bbtn span").removeClass("on").eq(0).addClass("on"); //为第一个按钮添加选中的效果
	}
});

//头部加载
function loadheader(vip)
{
	if(getUserId()>0)
	{       
		try{
			if(vip){
				var gurl = urlParams(HOST_URL+"login/viplogined", 'ajax_request=1');
			}else{
				var gurl = urlParams(HOST_URL+"login/logined", 'ajax_request=1');
			}
			
			if(gurl.indexOf("ajax_gets")=='-1') gurl = urlParams(gurl, 'ajax_gets='+ContentTag);
			gurl = urlParams(gurl, 'date='+Math.random());

			GPage.getJson(gurl,function(data){
					$("#userbar").html(data);
				}
			);
		}catch(error){
		}
	}
}
function adtest(url){
	location.href=url;
}
function getUserId(){
	var jieqiUserInfo = get_cookie_value("jieqiUserInfo");
	var jieqiUserId = 0;
	if(jieqiUserInfo!="")
	{
		try{
			 start = 0;
			 offset = jieqiUserInfo.indexOf(',', start); 
			 while(offset > 0){
				tmpval = jieqiUserInfo.substring(start, offset);
				tmpidx = tmpval.indexOf('=');
				if(tmpidx > 0){
				   tmpname = tmpval.substring(0, tmpidx);
				   tmpval = tmpval.substring(tmpidx+1, tmpval.length);
				   if(tmpname == 'jieqiUserId'){
					   jieqiUserId = tmpval;
					   break;
				   }
				}
				start = offset+1;
				if(offset < jieqiUserInfo.length){
				  offset = jieqiUserInfo.indexOf(',', start); 
				  if(offset == -1) offset =  jieqiUserInfo.length;
				}else{
				  offset = -1;
				}
			 }
		}catch(error){
		
		}
	}
	return jieqiUserId;
}
function huodong(url){
	if(getUserId()<1){
	     userLogin(url);
	}else{
		var gurl = urlParams(url, 'date='+Math.random());
		var pagei = $.layer({
			type:2,
			shade : [0.6 , '#000' , true],
			border : [10 , 0.3 , '#000', true],
			area: ['730px', '500px'],
			title: false,
			closeBtn: false,
			iframe:{src: gurl}
		});
	}
}


function otherlogin(url){
		var pagei = $.layer({
			type:2,
			shade : [0.6 , '#000' , true],
			border : [10 , 0.3 , '#000', true],
			area: ['920px', '510px'],
			title: false,
			closeBtn: [0,true],
			//closeBtn: false,
			iframe:{src: url}
		});
}

/*function ddd(url){ alert('hhhh');
	var pagei = $.layer({
		type:2,
		shade : [0.6 , '#000' , true],
		border : [10 , 0.3 , '#000', true],
		area: ['920px', '510px'],
		title: false,
		closeBtn: [0,true],
		//closeBtn: false,
		iframe:{src: url}
	});
}*/

function userLogin(url)
{   //var cntUrl = base64encode(window.location.href);
	var host = window.location.host;
	var msg =("<style>.logn_l,.logn_r { float:left;}.logn_l{ width:600px; border-right:1px solid #d4e9f4; padding-left:30px; padding-bottom:20px;}.logn_r { width:300px; font-size:14px; color:#666; padding-left:50px; position:absolute; top:0px; _top:0px; left:427px; height:400px; background-color:#EAF8FF;}.logn_l h3,.logn_r h3{ color: #575757;font-family: \"微软雅黑\",\"黑体\";font-size: 24px;font-weight: normal; padding: 30px 0px;}.tip-ok{text-align:left;padding: 5px 0px 5px 10px; width:370px;background: #e2f7c4;color: #558212;}</style><!--logn begin-->");
	msg +=("    <div class=\"logn fix\">");
	msg +=("     <!--logn_l begin-->");
	msg +=("     <div class=\"logn_l fix\">      ");
	msg +=("     <h3>用户登录</h3>");
	msg +=("	 <div id=\"result_14\" style=\"display:none\" class=\"tip-ok\">余下的操作需要登陆才能进行！</div>");
	msg +=("<form id=\"poplogin_form\" class=\"signup\" action=\""+HOST_URL+"login\"  method='post'>");
	msg +=("<fieldset>");
	msg +=("    <div class=\"form-item\">");
	msg +=("        <div class=\"field-name\">用户名：</div>");
	msg +=("        <div class=\"field-input\">");
	msg +=("          <input type=\"text\" maxlength=\"20\" name=\"username\" id=\"username\"/>");
	msg +=("        </div>");
	msg +=("    </div>");
	msg +=("    <div class=\"form-item\">");
	msg +=("        <div class=\"field-name\">密码：</div>");
	msg +=("        <div class=\"field-input\">");
	msg +=("          <input type=\"password\" name=\"password\" id=\"password\"/>");
	msg +=("        </div>");
	msg +=("    </div>");
	msg +=("    <div class=\"form-item\">");
	msg +=("        <div class=\"field-name\">验证码：</div>");
	msg +=("        <div class=\"field-input\">");
	msg +=("           <input type=\"text\" name=\"checkcode\" id=\"checkcode\" class=\"yzm\">");
	msg +=("         <img src=\""+HOST_URL+"checkcode.php\" width=\"90\" height=\"35\" class=\"pic\" id=\"checkcode3\" /><a class=\"f_org2 pl10\" id=\"recode3\" onclick=\"$(\'#checkcode3\').attr(\'src\',\'"+HOST_URL+"checkcode.php?rand=\'+Math.random());\">换一张</a>");
	msg +=("          <p><input name=\"usecookie\" type=\"checkbox\" value=\"1\" checked=\"checked\" class=\"check\" />记住我(1个月免登录)</p>");
	msg +=("        </div>");
	msg +=("    </div>");
	msg +=("</fieldset>");
	//msg +=("    <input type=\"hidden\" id=\"clickurl\" value=\""+url+"\">");
	msg +=("    <input type=\"hidden\" name=\"formjs\" value=\"1\">");
	msg +=("    <input type=\"hidden\" name=\"host\" value=\""+host+"\">");
	msg +=("<button id=\"btn-submit\" class=\"btn-submit2\" type=\"submit\">登录</button>");
	msg +=("</form>");
	msg +=("     </div><!--logn_l end-->");
	msg +=("    <div class=\"logn_r\">");
	msg +=("     <h3>用户注册</h3>");
	msg +=("     还没有书海小说网账号？");
	msg +=("     <a href=\""+HOST_URL+"register\" title=\"立即注册\" class=\"reg\"></a>");
	msg +=("     你也可以用站外账号登录:");
	msg +=("     <p class=\"o_login pt10\"><a href=\"javascript:;\" onclick=\"otherlogin(\'"+HOST_URL+"qqlogin\');\" title=\"腾讯QQ\" class=\"qq\"></a><!--<a href=\"javascript:;\" title=\"新浪微博\" onclick=\"alert(\'即将推送，敬请期待！\');\" class=\"sina\"></a>--></p>");
	msg +=("    </div>");
	msg +=("    </div><!--logn end-->");
	
	var i = $.layer({
		type : 1,
		title : false,
		closeBtn : [1 , true],
		border : [10 , 0.3 , '#000', true],
		area : ['780px','400px'],
		page : {html : msg}
	});
    $('#poplogin_form').live('submit',function(event){
		var form = this;
		if(form.username.value==''){
			form.username.focus();alert('请填写用户名！');return false;
		}else if(form.password.value==''){
			form.password.focus();alert('请填写密码！');return false;
		}else if(form.checkcode.value==''){
			form.checkcode.focus();alert('请填写验证码！');return false;
		}
		GPage.postForm('poplogin_form', form.action,
			   function(data){
					if(data.status=='OK'){
						loadheader();
						layer.closeAll();
						if(url) huodong(url);
						if(url=='reload') location.reload();
					}else{
						$('#result_14').html(data.msg).fadeIn(300).delay(2000).fadeOut(1000);
						if(data.msg == '对不起，校验码错误！'){
							$("[name='checkcode']").focus();
							$('#recode3').click();
						}else if(data.msg == '密码错误，请注意字母大小写是否输入正确！！'){
							$("[name='password']").focus();
						}else if(data.msg =='该用户不存在，请注意字母大小写是否输入正确！'){
							$("[name='username']").focus();
						}
					}
		});
		return false;
	});
}

//ContentTag = 'jieqi_contents';//内容块
function PageLoad() {
    this.MyMethod = null;//AJAX处理URL回调函数的中转容器
	//this.ContentTag = 'jieqi_contents';//内容块
	
	this.getJson = function(url, myFun)
	{
		$.ajax({
				type : "GET",
				url : urlParams(url,'ajax_request=1&date='+Math.random()),//+'&ajax_request=1&ajax_gets='+this.ContentTag,
				dataType : "jsonp",
				jsonp: 'CALLBACK',
				success : function(json){
					if(isExitsFunction(myFun)) myFun(json);
					else{
						this.MyMethod = myFun;
						if(this.MyMethod!=null){
						   this.MyMethod(json);
						}
					}
				}
		});	
	}

	this.postForm = function(form, url, myFun)
	{
		$.ajax({
				type : "POST",
				url : urlParams(url,'ajax_request=1&date'+Math.random()),//+'&ajax_request=1&ajax_gets='+this.ContentTag,
				data: $('#'+form).serialize(),
				dataType : "jsonp",
				jsonp: 'CALLBACK',
				success : function(json){
					if(isExitsFunction(myFun)) myFun(json);
					else{
						this.MyMethod = myFun;
						if(this.MyMethod!=null){
						   this.MyMethod(json);
						}
					}
				}
		});	
	}
	
	this.buychapter = function(url)
	{   //var ContentTag = this.ContentTag;
		GPage.getJson(url,
			function(data){//alert(url);
			    if(data.status=='OK'){
					layer.msg(data.msg, 1, 1);
					//var loadi = layer.load('文章内容加载中…');
					//var gurl = urlParams(vurl, 'ajax_request=1&date='+Math.random());
					//if(gurl.indexOf("ajax_gets")=='-1') gurl = urlParams(gurl, 'ajax_gets='+ContentTag);
					//$("#content").load(gurl);
					GPage.loadpage('content', vurl, true);
					//layer.close(loadi);
				}else{
					//layer.msg(data.msg, 1, 8);
					layer.alert(data.msg, 8, !1);
				}
			}
		);
	}
	
	
	this.addbook = function(url, id, obj)
	{  
	   if(getUserId()<1){   
	     userLogin();

	    }else{
			//alert(id);
			GPage.getJson(url,function(data){
			    if(data.status=='OK'){
					if (id == "vip") {
						loadheader('vip');
						layer.msg(data.msg, 1, 1);
					}else{
						if (id=='display_type'){
							//$('#'+id).html("<a href='javascript:;' class='abtn4'" +"disabled >" + "已关注</a>");
							loadheader();
							$('#add_gz').hide();
							$('#cancel_gz').show();
						}else if(id=='display'){
							//$('#display_type').html("<a href='javascript:;' class='abtn4'>+加关注</a>");
							$('#cancel_gz').hide();
							$('#add_gz').show();
						}else if(id=='dianzan'){
							var num = $(obj).text();
							var iLen = num.length;
							var num = parseInt(num.substring(1,iLen-1))+1;
							$(obj).text('('+num+')');
						}else{
							layer.msg(data.msg, 1, 1);
						}
					}
					
				}else{
					//layer.msg(data.msg, 1, 8);
					layer.alert(data.msg, 8, !1);
				}
			});
		}
	}
	
	this.loadpage = function(tag,url,date,showloading){//alert('fff');
		 var ContentTag = this.ContentTag;
		 var rc = tag.replace(/show/,"rcontent");//alert(showloading);
		 if(showloading || showloading==undefined){
			 $("#"+tag).html("<div style='text-align:center;'><img width='250px' height='190px' src='"+HOST_URL+"images/loading2.gif'></div>");		
		 }
		 var gurl = urlParams(url,'ajax_request=1');
		 if(gurl.indexOf("ajax_gets")=='-1') gurl = urlParams(gurl,'ajax_gets='+ContentTag);
		 if(date) gurl = urlParams(gurl,'date='+Math.random());
		 GPage.getJson(gurl,function(date){
					$("#"+tag).html(date);
					$("#"+rc).focus();
				}
			);
		 
		/* $("#"+tag).load(gurl ,function(){
		 	$("#"+rc).focus();
		 });*/
    }

}

function urlParams(url, params){
     if(url.indexOf("?")!='-1') return url+'&'+params;
	 return url+'?'+params;
}

function jumpurl(url, count) {    
    if(count <1 ) location.href=url;    
	window.setTimeout(function(){    
		count--;    
		if(count > 0) {    
			 if($('#jumpnum')) $('#jumpnum').attr('innerHTML', count);    
			 jumpurl(url, count);    
		} else {    
			 location.href=url;    
		}    
	 }, 1000);    
}    
//是否存在指定函数 
function isExitsFunction(funcName) {
    try {
        if (typeof(eval(funcName)) == "function") {
            return true;
        }
    } catch(e) {}
    return false;
}
//是否存在指定变量 
function isExitsVariable(variableName) {
    try {
        if (typeof(variableName) == "undefined") {
            return false;
        } else {
            return true;
        }
    } catch(e) {}
    return false;
}

function confirmurl(url,message)
{
	if(confirm(message)) jumpurl(url,1);
}

function confirmform(form,message)
{
	if(confirm(message)) form.submit();
}


function checkall(fieldid)
{
	if(fieldid==null)
	{
		if($('#checkbox').attr('checked')==false)
		{
			$('input[type=checkbox]').attr('checked',true);
		}
		else
		{
			$('input[type=checkbox]').attr('checked',false);
		}
	}
	else
	{
		var fieldids = '#'+fieldid;
		var inputfieldids = 'input[boxid='+fieldid+']';
		if($(fieldids).attr('checked')==false)
		{
			$(inputfieldids).attr('checked',true);
		}
		else
		{
			$(inputfieldids).attr('checked',false);
		}
	}
}
