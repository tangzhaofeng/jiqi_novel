layer.ready(function(){
	$("[ajaxsubmit]").on('submit',function(event){
		event.preventDefault();
		GPage.getJson(this.action,
			function(data){
			    if(data.status=='OK'){
					if($(this).attr("retruemsg")=='true') layer.msg(data.msg, 1, 1);//alert(data.jumpurl);
					GPage.loadpage('content', data.jumpurl);
				}else{
					layer.alert(data.msg, 8, !1);
				}
			}
		);
	});			 
}); 
