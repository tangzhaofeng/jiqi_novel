var GPage = new PageLoad();
var HOST_URL = 'http://' + document.domain + '/';
HOST_URL = HOST_URL.replace('wenxue','www');
var ContentTag = 'jieqi_contents';//内容块

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
		alert('wwww');
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
			}, 50);
			
			$('.pop3').mouseenter(function(){
				clearTimeout(start);
			});
			$('.pop3').mouseleave(function(){
				layer.closeTips();
			});
		}
	});
	
	$("form[ajaxform]").live('submit',function(event){
		if(this.J_search_suggest.value=='键入书名、作者名开始搜索') this.J_search_suggest.value='';
		if(this.J_search_suggest){
			var path = window.location.pathname;
			if(path.indexOf("search") > 0 )
			{
				location.href=this.action+'/'+encodeURIComponent(this.J_search_suggest.value);
			}else{
				window.open(this.action+'/'+encodeURIComponent(this.J_search_suggest.value));
			}
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

// 单行滚动
function AutoScroll(obj){
$(obj).find("ul:first").animate({
marginTop:"-25px"
},500,function(){
$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
});
}
$(function(){
setInterval('AutoScroll("#scrollDiv")',5000)
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
	//alert(jieqiUserInfo);
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

function get_cookie_value(Name) { 
  var returnvalue = ""; 
  var strCookie=document.cookie;
	//将多cookie切割为多个名/值对 
	var arrCookie=strCookie.split("; ");
	var userId; 
	//遍历cookie数组，处理每个cookie对 
	for(var i=0;i<arrCookie.length;i++){ 
	  var arr=arrCookie[i].split("="); 
	  //找到名称为userId的cookie，并返回它的值 
	  if(arr[0] === Name){
		  returnvalue=unescape(arr[1]); 
	    break; 
	  } 
	}
	//alert(returnvalue);
  return returnvalue; 
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

function userLogin(jpurl,hash)//url
{   //var cntUrl = base64encode(window.location.href);
	if(HOST_URL == "http://3gwap.shuhai.com/") HOST_URL = 'http://www.shuhai.com/';
	var host = window.location.host;
	var msg = "";
	msg +=("<div class=\"mask\" id=\"mask\">");
	msg +=("<section class=\"userLogin\" id=\"login\">");
	msg +=("<form id=\"loginFo\" action=\"http://"+host+"/login\" method=\"post\">");
	msg +=("<div class=\"header\">");
	msg +=("<span class=\"tit\">用户登录</span><em class=\"close\" id=\"login_close\"></em>");
	msg +=("</div>");
	msg +=("<div class=\"input\"><span class=\"tit\">用户名:</span><input type=\"text\"  maxlength=\"18\" name=\"username\" value=\"\" id=\"login_username\"/></div>");
	msg +=("<div class=\"input\"><span class=\"tit\">密码:</span><input type=\"password\"  maxlength=\"18\" name=\"password\" value=\"\" id=\"login_password\"/></div>");
	msg +=("<div class=\"input\"><span class=\"tit\">验证码:</span><input type=\"text\"  maxlength=\"18\" name=\"checkcode\" value=\"\" id=\"login_password\"/></div>");
	msg +=("<img onclick=\"document.getElementById('checkcode').getAttribute('src','"+HOST_URL+"checkcode.php?rand='+Math.random());\" src=\""+HOST_URL+"checkcode.php\" class=\"col-xs-4\" id=\"checkcode\" style=\"height:30px;margin-top:5px;\" alt=\"验证码\"/>");
	msg +=("<div class=\"btnn\">");
	msg +=("<input type=\"button\" class=\"cancel\" id=\"goto_register\" value=\"注册\"/>");
	msg +=("<input type=\"submit\" class=\"ok\" id=\"login_submit\" value=\"登录\"/>");
	msg +=("</div>");
	msg +=("<input type=\"hidden\" name=\"jumpurl\"  id=\"jumpurl\" value=\""+jpurl+"\">");
	msg +=("<input type=\"hidden\" name=\"formhash\" value=\""+hash+"\">");
	msg +=("</form>");
	msg +=("</section>");
	msg +=("</div>");
	msg +=("<script>");
	msg +=("$('.close').click(function(){");
	msg +=("$('.mask').hide();");	
	msg +=("});");
	msg +=("$('#goto_register').click(function(){");
	msg +=("jumpurl('/register');");		
	msg +=("});");
	msg +=("$('#loginFo').bind('submit',function(e){");
	msg +=("e.preventDefault();e.stopPropagation();");
	msg +=("var action = $(this).attr('action'),");
	msg +=("name = $(\"#loginFo [name='username']\"),");
	msg +=("pwd = $(\"#loginFo [name='password']\"),");
	msg +=("check = $(\"#loginFo [name='checkcode']\");");
	msg +=("if(name.val() == ''){");
	msg +=("alert('用户名不能为空');");
	msg +=("name.focus();return;");
	msg +=("}else if(pwd.val() == ''){");
	msg +=("alert('密码不能为空');");
	msg +=("pwd.focus();return;");
	msg +=("}else if(check.val() == ''){");
	msg +=("alert('验证码不能为空');");
	msg +=("check.focus();return;");
	msg +=("}");
	msg +=("$.ajax({");
	msg +=("type : 'POST',");
	msg +=("url : urlParams(action,'ajax_request=1&date'+Math.random()),");
	msg +=("data: $('#loginFo').serialize(),");
	msg +=("dataType : 'json',");
	msg +=("success : function(json){");
	msg +=("$('.mask').hide();");
	msg +=("$('.toBookshelf').click();");
	msg +=("}");
	msg +=("});");
	msg +=("});");
	
	msg +=("</script>");
	return msg;
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
		 if(false){   
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
	
	this.loadpage = function(tag,url,date,showloading){
		 //var ContentTag = this.ContentTag;
		 //var rc = tag.replace(/show/,"rcontent");
		 if(url.indexOf("ajax_gets")=='-1') gurl = urlParams(url,'ajax_gets='+ContentTag);
		 GPage.getJson(gurl,function(date){
					$("#"+tag).html(date);
					//$("#"+rc).focus();
		 });
		
   }
}

function urlParams(url, params){
	 var vrul = url;
     if(url.indexOf("?")!='-1') {
    	 vrul = url+'&'+params;
     }else{
    	 vrul = url+'?'+params;
     }
     //alert(vrul);
     return vrul;
     
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
