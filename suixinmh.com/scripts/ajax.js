var GPage = new PageLoad();
var ContentTag = 'jieqi_contents';//内容块
function PageLoad() {
    this.MyMethod = null;//AJAX处理URL回调函数的中转容器
	//this.ContentTag = 'jieqi_contents';//内容块
	
	this.getJson = function(url, myFun)
	{
		$.ajax({
			url : urlParams(url,'ajax_request=1&date='+Math.random()),//+'&ajax_request=1&ajax_gets='+this.ContentTag,
			type : "GET",
			dataType : "jsonp",
			jsonp: "CALLBACK",
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
}

function urlParams(url, params){
    if(url.indexOf("?")!='-1') return url+"&"+params;
	return url+"?"+params;
}

function isExitsFunction(funcName) {
    try {
        if (typeof(eval(funcName)) == "function") {
            return true;
        }
    } catch(e) {}
    return false;
}