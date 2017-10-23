$(function() {
  //下拉菜单
  $('.m-nav').find('li').hover(function() {
    $(this).find('.m-pulldown').stop(true,true).slideDown();
  }, function() {
    $(this).find('.m-pulldown').stop(true,true).slideUp();
  });

  //左侧菜单
  var q_run_on = $('.g-head .on').parent();
  var q_main_left = $('#main_left');
  var obj_tmp = {};
  if(q_run_on.find('ul').size() > 0) {
    q_main_left.html(q_run_on.find('ul').html());
  } else {
    q_main_left.html(q_run_on.clone());
  }
  q_main_left.find('a').each(function() {
    var q_this = $(this);
    if(location.href.indexOf(q_this.prop('href')) !== -1) {
      if(q_this.prop('href').length < obj_tmp.l) return true;
      obj_tmp.l = q_this.prop('href').length;
      obj_tmp.q = q_this;
    }
  }); // js
  if(obj_tmp.q) obj_tmp.q.addClass('on');
  //客户经理悬浮窗
  $("#suspensionFrame").hover(function() {
    $("#contactContent").show();
    $("#contactContent").stop().animate({
      "height": "200px"
    }, "normal", function() {});
  }, function() {
    $("#contactContent").stop().animate({
      "height": 0
    }, "normal", function() {
      $("#contactContent").hide();
    });
  });
  $('.m_menu_toggle').click(function(){ 
    if($(this).hasClass('on')){
      $('#main-nav').slideUp()
      $(this).removeClass('on') 
    }else{
      $('#main-nav').slideDown()
      $(this).addClass('on')
    } 
  })
  $('.m_submenu_toggle').click(function(){ 
    if($(this).hasClass('on')){
    }else{
      $(this).siblings().slideDown()
      $('.m_submenu_toggle').removeClass('on') 
      $(this).addClass('on')
    } 
  })
  $('.g-main').mousedown(function(){
    // console.log($('.m_menu_toggle')[0])
    if($('.m_menu_toggle').hasClass('on')){
      $('#main-nav').slideUp()
      $('.m_menu_toggle').removeClass('on')
    }
  })
  $(window).scroll(function(){
    // console.log(12)
    $('#main-nav').slideUp()
    $('.m_menu_toggle').removeClass('on')
  })





  // 不同版本上的兼容
  if( $(window).width() < 410 ){
    $("#page_totalcount_span").hide();
  }else{
    $("#page_totalcount_span").show();
  }
  $(".one_nav_m").click(function(){
    if( $("#m_menu_toggle_sun:visible").size()>0 )return false;
  });


  
});

