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
	
	//$.jqtab("#tabs1","#tab_conbox1","mouseenter");
	
	//$.jqtab("#tabs2","#tab_conbox2","mouseenter");
	
	$.jqtab("#tabs3","#tab_conbox3","mouseenter");
	
	//$.jqtab("#tabs4","#tab_conbox4","mouseenter");
	
	$.jqtab("#tabs5","#tab_conbox5","mouseenter");
	
	$.jqtab("#tabs6","#tab_conbox6","mouseenter");
	
	$.jqtab("#tabs7","#tab_conbox7","mouseenter");
	
	$.jqtab("#tabs8","#tab_conbox8","mouseenter");
	
	$.jqtab("#tabs9","#tab_conbox9","mouseenter");
	
	$.jqtab("#tabs10","#tab_conbox10","mouseenter");
	
	$.jqtab("#tabs11","#tab_con11","click");
	
	$.jqtab("#tabs12","#tab_con12","click");
	//公用点击
	$("[ajaxclick]").live('click',function(e){
		e.preventDefault();
		var retruemsg = $(this).attr("retruemsg");
		var confirm_msg = $(this).attr("confirm");
		var targetid = $(this).attr("targetid");
		if(confirm_msg){
		     if(!confirm(confirm_msg)) return false;
		}
		var i = layer.load(0);
		if(!targetid) var targetid = 'content';
		GPage.getJson(this.href,function(data){
			layer.close(i);
		    if(data.status=='OK'){
				if(retruemsg!='false' &&  retruemsg) layer.msg(data.msg, 1, 1);
				GPage.loadpage(targetid, data.jumpurl, true,false);
			}else{
				layer.alert(data.msg, 8, !1);
			}
		});
	});	
	//post方法提交ajax
	$("[ajaxpost]").bind('valid.form',function(e){
		e.preventDefault();
		var retruemsg = $(this).attr("retruemsg");
		var formid = $(this).attr("id");
		var formaction = $(this).attr("action");
		var i = layer.load(0);
		GPage.postForm(formid, formaction,function(data){
			layer.close(i);
			if(data.status=='OK'){
				if(retruemsg!='false' &&  retruemsg) layer.msg(data.msg, 1, 1);
				jumpurl(data.jumpurl,0);
			}else{
				layer.alert(data.msg, 8, !1);
			}
		});
	});
	//post方法提交不带验证
	$("[ajaxsubmit]").bind('submit',function(e){
		e.preventDefault();
		var retruemsg = $(this).attr("retruemsg");
		var formid = $(this).attr("id");
		var formaction = $(this).attr("action");
		var i = layer.load(0);
		GPage.postForm(formid, formaction,function(data){
			layer.close(i);
			if(data.status=='OK'){
				if(retruemsg!='false' &&  retruemsg) layer.msg(data.msg, 1, 1);
				jumpurl(data.jumpurl,0);
			}else{
				layer.alert(data.msg, 8, !1);
			}
		});
	});
	//删除ajax提交
	/*$("[delajax]").live('submit', function(e){
		e.preventDefault();
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
						var i = layer.load(0);
						GPage.postForm(formid, formaction,function(data){
							layer.close(i);
							if(data.status=='OK'){
								$.ajaxSetup ({ cache: false });
								layer.msg(data.msg,1,{type:1,shade:false},function(){
									$('#'+targetid).load(location.href+ ' #'+targetid+'>*');
								});
							}else{
								layer.alert(data.msg, 8, !1);
							}
						});
					},
					no : function(){
						layer.closeAll();
						checkform.reset();
					}
				}
			});
		}
	});*/
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
	//模态框 
	var tipFn=function(){
		var tip,_init,
			isIos=!!navigator.userAgent.match(/iPhone|iPad|iPod/i);
			//构成确认框
		function confirmFn(config){
				var tpl='<div class="cfShadow"><div class="confirmBox">\
						 <div class="title">'+config.title+'</div>\
						 <div class="con">'+config.con+'</div>\
						 <div class="bn">\
						 <span class="no">'+(config.noTxt||'取消')+'</span><span class="ok">'+(config.okTxt||'确定')+'</span>\
						 </div>\
						 </div></div>';
				var o=this;

				this.target= $(config.tpl||tpl);
				this.ok=config.ok;
				this.no=config.no;

				if(!config.title) this.target.find('.title').remove();

				this.target.appendTo($(document.body)).on('touchstart touchmove touchend click',function(e){

					e.stopPropagation();
					return false;

				}).delegate('.bn>span','touchend',function() {

					if($(this).hasClass('ok')){
						o.ok();
					}else if($(this).hasClass('no')){
						o.no();
					}

					o.remove();

				}).find('.confirmBox').css({

					marginTop:(window.innerHeight-o.target.find('.confirmBox').height())/2

				})
		}
		confirmFn.prototype.ok=function(){};
		confirmFn.prototype.no=function(){};
		confirmFn.prototype.remove=function(){this.target.remove()};

		return{
			init:function() {
				if(_init)return;
				_init=true;
				tip=$('<span style="position:fixed;padding:10px;background-color:rgba(0,0,0,.8);color:#fff;border-radius:3px;top:50%;left:50%;text-align:center;z-index:-1;visibility:hidden;'+(isIos?'-webkit-transition:opacity .5s ease-out':'')+'"></span>');
				$(document.body).append(tip);
				this._isShown=false;
			},
			show:function(s,interval) {
				this._tid&&clearTimeout(this._tid);
				tip.text(s).css({
					visibility:'visible',
					opacity:1,
					margin:-tip.height()/2+"px 0 0 -"+tip.width()/2+"px",
					zIndex:2000
				});

				if(interval)
					this._tid=setTimeout(function() {
						tipFn.hide();
					},interval);

				this._isShown=true;
			},
			hide:function() {
				if(!this._isShown)return;
				this._isShown=false;
				this._tid&&clearTimeout(this._tid);
				if(isIos){
					tip.css({
						opacity:0,
						zIndex:-1
					});
				} else {
					tip.css({
						visibility:'hidden',
						zIndex:-1
					});
				}
			},
			confirm:function(config){
				return new confirmFn(config);
			}
		};
	}();
	tipFn.init();
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
				$(this).prev().show();
				$(this).hide();
			});
		});
	});
/*// 单行滚动
function AutoScroll(obj){
	
$(obj).find("ul:first").animate({
marginTop:"-25px"
},500,function(){
$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
});
}
$(function(){
setInterval('AutoScroll("#scrollDiv")',5000);
});*/
//多行滚动
	//多行应用@Mr.Think
	var _wrap=$('div.trend');//定义滚动区域
	var _ddnum=_wrap.find('div').length;
	
	if(_ddnum < 0)return;
	
	var _interval=3000;//定义滚动间隙时间
	var _moving;//需要清除的动画
	_wrap.hover(function(){
		clearInterval(_moving);//当鼠标在滚动区域中时,停止滚动
	},function(){
		_moving=setInterval(function(){
			var _field=_wrap.find('div:first');//此变量不可放置于函数起始处，li:first取值是变化的
			var _h=_field.height();//取得每次滚动高度
			_field.animate({marginTop:-_h+'px'},600,function(){//通过取负margin值，隐藏第一行
				_field.css('marginTop',0).appendTo(_wrap);//隐藏后，将该行的margin值置零，并插入到最后，实现无缝滚动
			})
		},_interval)//滚动间隔时间取决于_interval
	}).trigger('mouseleave');//函数载入时，模拟执行mouseleave，即自动滚动
	//支持CSS3	
	if (window.PIE) {
        $('.rounded').each(function() {
            PIE.attach(this);
        });
    }
	//幻灯片
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
	//隐藏回到顶部
	$("#back-to-top").hide();
	//滚动滚动条时触发
	$(window).scroll(function(){
	  if($(window).scrollTop()>100){
		$("#back-to-top").fadeIn(1500);
	  }else{
		$("#back-to-top").fadeOut(1500);
	  }
	});
	//当点击跳转链接后，回到页面顶部位置
	$("#back-to-top").click(function(){
	  $('body,html').animate({scrollTop:0},1000);
	  return false;
    });
});

//头部加载
function loadheader(vip){
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
				$(".logon").html(data);
			});
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
  return returnvalue; 
}

function huodong(url,id,target){
	if(getUserId()<1){
	 	userLogin(id);
	}else{
		GPage.loadpage(target,url);
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
			iframe:{src: url}
		});
}

/*function userLogin(id){
	if(HOST_URL == "http://3gwap.shuhai.com/") HOST_URL = 'http://www.shuhai.com/';
	var host = window.location.host,
		pathname = window.location.pathname;
	var pagei = layer.open({
	    type: 1, //1代表页面层
	    content: '<div class="padbar bg-e">'
	             +'<header class="head-navbar head-navbar0">'
	             +'<div class="col-x-4"><a href="javascript:;" class="i-back"><img src='+HOST_URL+'themes/3gwap/images/i-back.png></a></div>'
	             +'<div class="col-x-4"><h2>用户登录</h2></div>'
	             +'<div class="col-x-2"><a href="/" class="i-home"><img src='+HOST_URL+'themes/3gwap/images/i-home.png></a></div>'
				 +'</header>'
				 +'<section class="signbox signbox0">'
				 +'<form action=http://'+host+'/login id="loginFo" method="post">'
				 +'<div class="row cardbox">'
				 +'<div class="row form-group">'
				 +'<div class="col-x-2"><label class="i-user"></label></div>'
				 +'<div class="col-x-10"><input class="inp-t" name="username" type="text" placeholder="用户名"></div>'
				 +'</div>'
				 +'<div class="col-x-12"></div>'
				 +'<div class="row form-group">'
				 +'<div class="col-x-2"><label class="i-sn"></label></div>'
				 +'<div class="col-x-10"><input class="inp-t" name="password" type="password" placeholder="密码"></div>'
				 +'</div>'
				 +'<div class="col-x-12"></div>'
				 +'<div class="row form-group">'
				 +'<div class="col-x-2"><label class="i-sn"></label></div>'
				 +'<div class="col-x-4"><input class="inp-t" name="checkcode" type="text" placeholder="验证码"></div>'
                 +'<img src='+HOST_URL+'checkcode.php class="col-xs-4" id="checkcode" style="height:45px;padding-top:5px;" alt="验证码" />'
                 +'</div>'
                 +'</div>'
				 +'<div class="row0 auto clearfix">'
				 +'<div class="col-x-6"><label class="n"><input name="usecookie" type="checkbox" value="1" checked="checked"> 自动登录 </label></div>'
				 +'<div class="col-x-4 pull-right"><a href="/getpass" target="_blank" class="text-right f-blue2">忘记密码?</a></div>'
				 +'</div>'
				 +'<div class="row0 form-group2 auto px0">'
				 +'<input name="" type="submit" class="btn0 btn-block btnblue" value="登 录">'
				 +'</div>'
				 +'<div class="row0 form-group2 auto px0">'
				 +'<a href="/register" target="_blank" class="btn0 btn-block btngreen">注册</a>'
				 +'</div>'
				 +'<div class="row0 form-group2 auto px0">'
				 +'<a href="javascript:;" id="qqlogin" class="btn0 btn-block btnorg">QQ登录</a>'
				 +'</div>'
				 +'<input type="hidden" name="formjs"  id="jumpurl" value="1">'
				 +'<input type="hidden" name="host" value='+host+'>'
				 +'</form>'
				 +'</section>'
				 +'</div>',	     
	    style: 'position:absolute;top:0;left:0;width:101%;height:100%;font-size:1.5em;',
	    success: function(olayer){
	      $(".i-back").click(function(){
	    	layer.close(pagei);     	
	      });
	      if(pathname.indexOf("read") > -1){
	    	$(".layermchild").css("font-size","0.8em");
	      }
	      
	      $("#checkcode").click(function(){
	    	$(this).attr("src",HOST_URL+"checkcode.php?rand="+Math.random());
	      });
	      $("#qqlogin").click(function(){
	    	  location.href="/qqlogin/?jumpurl="+"";
	      });
	      $("#loginFo").on("submit",function(e){
	    	e.preventDefault();
	     	e.stopPropagation();
	     	var action = $(this).attr('action');
	     	$.ajax({
	     	  type : 'POST',
	     	  url : urlParams(action,'ajax_request=1&date'+Math.random()),
	          data: $('#loginFo').serialize(),
	          dataType : 'json',   
	          success : function(json){
	            if(json.status == 'OK'){
	            	if(pathname.indexOf("read") > -1){
	            		jumpurl(json.jumpurl);
	            	}else{
	            		$(".layermcont .i-back").click();
	  	                $("#"+id).click();
	            	}
	            }else{
	              layer.open({content: json.msg,style: 'border:none; background-color:black; color:#fff;',time: 2});           	
	            }	            	   
	          }            
	        });
	      });
	    }
	});
}*/

function userLogin(id){
	  
	if(HOST_URL == "http://3gwap.shuhai.com/") HOST_URL = 'http://www.shuhai.com/';
	var host = window.location.host,
		pathname = window.location.pathname;
	var pagei = layer.open({
	    type: 1, //1代表页面层
	    content: '<div class="bdbar">'
	             +'<header class="head-navbar3 clearfix">'
	             +'<div class="col-x-4"><a href="javascript:;" class="i-back" media="handheld"><img src='+HOST_URL+'themes/3gwap/images/i-back2.png></a></div>'
	             +'<div class="col-x-4"><h2>用户登录</h2></div>'
	             +'<div class="col-x-2 fr"><a href="/" media="handheld"><img src='+HOST_URL+'themes/3gwap/images/i-home2.png></a></div>'
	             +'</header>'
	             +'<section id="signin">'
	             +'<div class="signbox p12">'
	             +'<form method="post" action=http://'+host+'/login id="loginFo" class="nice-validator n-simple" novalidate="novalidate">'
	             +'<div class="card03 mb12">'
	             +'<ul>'
	             +'<li>'
	             +'<span class="i-user"></span>'
	             +'<input type="text" placeholder="用户名" name="username" class="inp-t n-invalid" aria-required="true" aria-invalid="true"/>'
	             +'</li>'
	             +'<li>'
	             +'<span class="i-sn"></span>'
	             +'<input type="password" placeholder="密码" name="password" class="inp-t" aria-required="true"/>'
	             +'</li>'
	             +'<li class="yzm">'
	             +'<span class="i-yzm"></span>'
	             +'<input placeholder="验证码" name="checkcode" class="inp-t" aria-required="true"/>'
	             +'<img alt="验证码" id="checkcode" src='+HOST_URL+'checkcode.php>'
	             +'</li>'
	             +'</ul>'
	             +'</div>'
	             +'<dl>'
	             +'<dd class="getpass clearfix">'
	             +'<label class="col-x-8"><input type="checkbox" checked="checked" value="1" name="usecookie"> 自动登录 </label>'
	             +'<a class="col-x-3 f-blue2" href="/getpass">忘记密码?</a>'
	             +'</dd>'
	             +'<dd class="mt6"><input type="submit" value="登 录" class="btn01 btn-block btn-blue2" name=""></dd>'
	             +'<dd class="mt6"><a class="btn01 btn-block btn-green2" href="/register">注册</a></dd>'
	             +'<dd class="mt6"><a class="btn01 btn-block btn-org1" id="qqlogin" href="javascript:;">QQ登录</a></dd>'
	             +'<input type="hidden" name="formjs"  id="jumpurl" value="1">'
				 +'<input type="hidden" name="host" value='+host+'>'
	             +'</dl>'
	             +'</form>'
	             +'</div>'
	             +'</section>'
				 +'</div>',
		style: 'positon:static;display:block;height:100%;',
	    success: function(olayer){
	      $(".i-back").click(function(){
	    	  pathname.indexOf("read") > -1 ? window.history.back() : layer.close(pagei);
	    	  //layer.close(pagei);     	
	      });
	      $(".layermcont").css("height","100%");
	      if(pathname.indexOf("read") > -1){
	    	$(".layermchild").css("font-size","0.8em");
	      }
	      $("#checkcode").click(function(){
	    	$(this).attr("src",HOST_URL+"checkcode.php?rand="+Math.random());
	      });
	      $("#qqlogin").click(function(){
	    	  location.href="/qqlogin/?jumpurl="+"";
	      });
	      $("#loginFo").on("submit",function(e){
	    	e.preventDefault();
	     	e.stopPropagation();
	     	var action = $(this).attr('action');
	     	$.ajax({
	     	  type : 'POST',
	     	  url : urlParams(action,'ajax_request=1&date'+Math.random()),
	          data: $('#loginFo').serialize(),
	          dataType : 'json',   
	          success : function(json){
	            if(json.status == 'OK'){
	            	if(pathname.indexOf("read") > -1){
	            		jumpurl(json.jumpurl);
	            	}else{
	            		$(".layermcont .i-back").click();
	  	                $("#"+id).click();
	            	}
	            }else{
	              layer.open({content: json.msg,style: 'border:none; background-color:black; color:#fff;',time: 2});           	
	            }	            	   
	          }            
	        });
	      });
	    }
	});
}

function PageLoad() {
    this.MyMethod = null;//AJAX处理URL回调函数的中转容器
	
	this.getJson = function(url, myFun)
	{  
		$.ajax({
			type : "GET",
			url : urlParams(url,'ajax_request=1&date='+Math.random()),
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
				url : urlParams(url,'ajax_request=1&date'+Math.random()),
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
				},
				error: function(XMLHttpRequest,textStatus,errorThrown){
					alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
				}
		});	
	}
	
	this.buychapter = function(url)
	{   
		GPage.getJson(url,
			function(data){
			    if(data.status=='OK'){
			    	layer.open({
					    content: data.msg,
					    style: 'border:none; background-color:black; color:#fff;',
					    time: 2
					}); 
					GPage.loadpage('content', vurl, true);
				}else{
					layer.open({
					    title: '提示',
					    content: data.msg
					});
				}
			}
		);
	}
	
	
	this.addbook = function(url, id, obj)
	{  
		 if(getUserId()<1){   
		     userLogin(id); 
		 }else{
			GPage.getJson(url,function(data){
			    if(data.status=='OK'){
					if (id == "vip") {
						loadheader('vip');
						layer.msg(data.msg, 1, 1);
					}else{
						if (id=='display_type'){
							loadheader();
							$('#add_gz').hide();
							$('#cancel_gz').show();
						}else if(id=='display'){
							$('#cancel_gz').hide();
							$('#add_gz').show();
						}else if(id=='dianzan'){
							var num = $(obj).text();
							var iLen = num.length;
							var num = parseInt(num.substring(1,iLen-1))+1;
							$(obj).text('('+num+')');
						}else{
							layer.open({
							    content: data.msg,
							    style: 'border:none; background-color:black; color:#fff;',
							    time: 2
							});
						}
					}
					
				}else{
					layer.open({
					    content: data.msg,
					    style: 'border:none; background-color:black; color:#fff;',
					    time: 2
					}); 
				}
			});
		}
	}
	
	this.loadpage = function(tag,url,date,showloading){
	   if(url.indexOf("ajax_gets")=='-1') gurl = urlParams(url,'ajax_gets='+ContentTag);
	   GPage.getJson(gurl,function(date){
	     $("#"+tag).html(date);
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

function addLoad(abelId,ContainerId,issearch,maxpage){
  $('#'+abelId).live('click',function(event){
    event.stopPropagation();
    event.preventDefault();
    var loadurl = this.href,
    	urlarr = loadurl.split('?');
    loadurl = urlarr[0];
    if(issearch && issearch === 'search'){
	  loadurl = decodeURI(loadurl);
	  var start = loadurl.lastIndexOf("/"),
	  	  page = parseInt(loadurl.substring(start+1));
	  page = page + 1;
      loadurl = loadurl.substring(0, start+1);
      loadurl = loadurl+page;
    }else{
    	var start = loadurl.lastIndexOf("_"),
            end = loadurl.lastIndexOf("."),
            page = parseInt(loadurl.substring(start+1, end));
        page = page + 1;
        loadurl = loadurl.substring(0, start+1);
        loadurl = loadurl+page+".html";
    }
    
    if(urlarr[1])
    {
    	loadurl = loadurl + "?"+urlarr[1];
    }
    this.href = loadurl;
    var tips = layer.open({
        type: 2,
        content: '加载中……'
    });
    
    GPage.getJson(urlParams(loadurl,'ajax_gets='+ContentTag),function(data){
	  if($.trim(data) != "")
	  {	  
	      if(page == maxpage)  
	      {
	    	 $('#'+ContainerId).html($('#'+ContainerId).html()+data);
			 history.replaceState({},'', loadurl);
			 $('#cont').html("<a href='javascript:;' class='card_btn_long'>亲，最后一页喽</a>");
		  }else if (page < maxpage){
			  $('#'+ContainerId).html($('#'+ContainerId).html()+data);
			  history.replaceState({},'', loadurl);
			  $('#cont').html("<a href='" +loadurl+ "' class='card_btn_long' id='"+abelId+"'>查看更多...</a>");
		  }else{
			  $('#cont').html("<a href='javascript:;' class='card_btn_long' >亲，最后一页喽</a>");
		  }
	      layer.close(tips);
	  }else{
		  $('#cont').html("<a href='javascript:;' class='card_btn_long'>亲，最后一页喽</a>");
		  layer.close(tips);
	  }
    });
  });
}

function addSortLoad(abelId,ContainerId,issearch,maxpage,clickid){
	
  $('#'+clickid).on('click',function(e){
    e.stopPropagation();
    e.preventDefault();
    var loadurl = this.href;
    if(issearch && issearch === 'search'){
	  var loadnurl = decodeURI(loadurl),
	  	  start = loadnurl.lastIndexOf("/"),
	  	  page = parseInt(loadnurl.substring(start+1));
    }
    
    GPage.getJson(urlParams(loadurl,'ajax_gets='+ContentTag),function(data){
    	
      if($.trim(data) != "")
  	  {	  
  		  $('#'+ContainerId).html(data);
  		  $('#cont').children('a').attr('href',loadurl);
  		  history.replaceState({},'', loadurl);
  		  if(page == maxpage)
	      {
  			 $('#cont').children('a').text('查看更多...');
		     $('#'+abelId).attr("disabled",true);
		  }else if(page < maxpage && $('#cont').children('a').text() == '亲，最后一页喽'){
			 $('#cont').children('a').text('查看更多...');
		  }
  	  }else{
  		  $('#'+abelId).text("亲，最后一页喽");
  		  $('#'+abelId).attr("disabled",true);
  	  }
    });
  });
}
//移动版弹出层登陆，注册等用
function openMsg(msg){
  layer.open({
    content: msg,
    style: 'border:none; background-color:black; color:#fff;',
    time: 2
  }); 
}
//document.writeln("<script>");
//document.writeln("var _hmt = _hmt || [];");
//document.writeln("(function() {");
//document.writeln("  var hm = document.createElement(\"script\");");
//document.writeln("  hm.src = \"//hm.baidu.com/hm.js?d70003f2295f13088ff4b2b52000f830\";");
//document.writeln("  var s = document.getElementsByTagName(\"script\")[0]; ");
//document.writeln("  s.parentNode.insertBefore(hm, s);");
//document.writeln("})();");
//document.writeln("</script>");