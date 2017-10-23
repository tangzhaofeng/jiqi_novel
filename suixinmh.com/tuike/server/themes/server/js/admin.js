$(function() {
	//导航菜单
	$('.m-nav').find('li').hover(function() {
    	$(this).find('.m-pulldown').show();
  	},function() {
		$(this).find('.m-pulldown').hide();
  	});
	
	
});


function tobank(e) {
	if(e.value=='bank'){
		bankh.style.display='block';
        wehcatD.style.display='block';
	}else if(e.value=='wehcat'){
        bankh.style.display='none';
        wehcatD.style.display='none';
    }else{
		bankh.style.display='none';
        wehcatD.style.display='block';
	}
    if(e.value=='wehcat'){
        $('.conQQ').css({'background':'#fff'});
    }else{
        $('.conQQ').css({'background':'#fafafa'});
    }
}