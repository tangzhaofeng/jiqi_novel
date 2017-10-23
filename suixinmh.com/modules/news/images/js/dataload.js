//封装远程跨域获取数据的方法
var GData = new DataLoad();

function DataLoad() {
    this.MyMethod = null;//AJAX处理URL回调函数的中转容器
	this.CommentUrl = siteurl+'/modules/news/?ac=comment&contentid='+articleid;//获取留言URL
	this.CountUrl = siteurl+'/modules/news/count.php?contentid='+articleid;//统计数据URL
	this.DiggUrl = siteurl+"/modules/news/digg.php?id="+articleid;//统计数据URL
	this.MoodUrl = siteurl+'/modules/news/mood.php';
	this.ContentTag = 'jieqi_contents';//内容块
	
	this.getpage = function(url, myFun)
	{
		$.ajax({
				type : "GET",
				url : url+'&ajax_request=1&ajax_gets='+this.ContentTag,
				dataType : "jsonp",
				jsonp: 'CALLBACK',
				success : function(json){
					this.MyMethod = myFun;
					if(this.MyMethod!=null){
					   this.MyMethod(json);
					}
				}
		});	
	}
	
    //处理页面加载时调用留言和统计
	this.pageLoad = function(){
		//留言列表加载完毕再加载统计[防止出错]
		//if(this.getComment()) this.getCount();
                this.getCount();
	}
	
	this.getComment = function(){
		this.getpage(this.CommentUrl, function(data){
			$('#comment').html(data);
		});	
		return true;
	}
	
	this.getCount = function(){
		this.getpage(this.CountUrl, function(data){
			//if(data.comments_checked<1) data.comments_checked = '0';
			//$("span[id^='comments']").html(data.comments_checked);
			//$('#hits').html(data.hits);
			//$('#hitss').html(data.hits);
			//$('#hits_month').html(data.hits_month);
			//$('#hits_week').html(data.hits_week);
			//$('#hits_day').html(data.hits_day);
		});	
	}
	
	this.digg = function(type, flag)
	{
		GData.getpage(this.DiggUrl+"&flag="+flag+"&type="+type,
			function(data){
					$('#click_supports').unbind('click');
					$('#click_supports').bind('click',function(){GData.digg('digg',1);}); 
					$('#click_againsts').unbind('click');
					$('#click_againsts').bind('click',function(){GData.digg('digg',0);}); 
				if(data.supports!=undefined){
					var supports = parseFloat(data.supports);
					var againsts = parseInt(data.againsts);
					var supports_bb = Math.round((supports/(supports+againsts))*100);
					var againsts_bb = Math.round((againsts/(supports+againsts))*100);
					
					if(supports>0){
						$('#supports').html(supports);
						$('#supports_bb').html(supports_bb)
						$('#supports_b').attr("width", supports_bb);
					}
					if(againsts>0){
						$('#againsts').html(againsts);
						$('#againsts_bb').html(againsts_bb)
						$('#againsts_b').attr("width",againsts_bb);
					}
				}else alert('您已经顶过该贴,请不要重复顶贴!');document.write.end
			}
		);
	}

	this.moodVote = function(moodid, contentid, vote_id){
		var url = this.MoodUrl+'?moodid='+moodid+'&contentid='+contentid+'&vote_id='+vote_id;
		GData.getpage(url, function(data){
			if(data.total!=undefined){
				var s = parseFloat(eval('data.n'+vote_id));
				var total = parseFloat(data.total);
				$("span[id='mood_all']").html(data.total);
				var h = Math.round(s/total*100);
				$("#mood_"+vote_id+'num').html(s);
				$("#mood_"+vote_id+'height').css("height",h);
				$("span[id^='mood_submit_']").html('');
			}else alert('您已经发表过观点!');
		});
	}

}

var jumpurl="";
if(location.href.indexOf("jumpurl") == -1){
   jumpurl=location.href;
   $('jumpurl').value = jumpurl;
}

