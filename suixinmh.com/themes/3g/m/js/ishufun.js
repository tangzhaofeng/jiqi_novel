/**
 * Created by Administrator on 2016/7/8.
 */
//angular.module('youyue',['ng']) 
//    .controller('parentCtrl',function($scope,$location,$http){
//    })
//    .controller('startCtrl',function($scope,$location){
//    })
$(function(){
    if(localStorage.biaoti==$('.chapter-title h2').html()){
        if(localStorage.scroll!==undefined){
            $('body')[0].scrollTop=localStorage.scroll;
        }
    }
});
$(window).scroll(function(){
    localStorage.setItem('scroll', $('body')[0].scrollTop);
    localStorage.setItem('biaoti', $('.chapter-title h2').html());
    // console.log(localStorage.biaoti);
    // console.log($('body')[0].scrollTop);
});
$('[data-role="mode"]').click(function (event) {
    event.preventDefault();
    if ($('body').hasClass('nightbg')) {
        $('body').removeClass().addClass('read_style_1');
        localStorage.setItem('beijing', 'read_style_1');
        //sessionStorage.getItem(key)
        $('[data-role="mode"] i')[0].style.backgroundPosition = '0% 100%';
    } else {
        $('body').removeClass().addClass('nightbg');
        localStorage.setItem('beijing', 'nightbg');
        $('[data-role="mode"] i')[0].style.backgroundPosition = '0% 0%';
        $('.bg_bar a').css('border', 'none');
        $('.bg_bar li:first-child a')[0].style.border = '1px solid #666';
    }
});
$('.bg_bar').on('click', 'a', function () {
    if (this.nodeName == 'A') {
        $('[data-role="mode"] i')[0].style.backgroundPosition = '0% 100%';
        $('.bg_bar a').css('border', 'none');
        this.style.border = '1px solid #666';
    }
});
$('.aadd').click(function (event) {
    event.preventDefault();
    // console.log(1);
    if ($('.chapter-content').hasClass('font-normal')) {
        $('.font-normal').removeClass('font-normal').addClass('font-large');
        localStorage.setItem('ziti', 'font-large');
    } else if ($('.chapter-content').hasClass('font-large')) {
        $('.font-large').removeClass('font-large').addClass('font-xlarge');
        localStorage.setItem('ziti', 'font-xlarge');
    } else if ($('.chapter-content').hasClass('font-xlarge')) {
        $('.font-xlarge').removeClass('font-xlarge').addClass('font-xxlarge');
        localStorage.setItem('ziti', 'font-xxlarge');
    } else if ($('.chapter-content').hasClass('font-xxlarge')) {
        $('.font-xxlarge').removeClass('font-xxlarge').addClass('font-xxxlarge');
        localStorage.setItem('ziti', 'font-xxxlarge');
    }else if ($('.chapter-content').hasClass('font-xxxlarge')) {
        $('.font-xxxlarge').removeClass('font-xxxlarge').addClass('font-xxxxlarge');
        localStorage.setItem('ziti', 'font-xxxxlarge');
    }
    
});
$('.aminus').click(function (event) {
    event.preventDefault();
    if ($('.chapter-content').hasClass('font-xxxxlarge')) {
        $('.font-xxxxlarge').removeClass('font-xxxxlarge').addClass('font-xxxlarge');
        localStorage.setItem('ziti', 'font-xxlarge');
    } else if ($('.chapter-content').hasClass('font-xxxlarge')) {
        $('.font-xxxlarge').removeClass('font-xxxlarge').addClass('font-xxlarge');
        localStorage.setItem('ziti', 'font-xxlarge');
    } else if ($('.chapter-content').hasClass('font-xxlarge')) {
        $('.font-xxlarge').removeClass('font-xxlarge').addClass('font-xlarge');
        localStorage.setItem('ziti', 'font-xlarge');
    } else if ($('.chapter-content').hasClass('font-xlarge')) {
        $('.font-xlarge').removeClass('font-xlarge').addClass('font-large');
        localStorage.setItem('ziti', 'font-large');
    } else if ($('.chapter-content').hasClass('font-large')) {
        $('.font-large').removeClass('font-large').addClass('font-normal');
        localStorage.setItem('ziti', 'font-normal');
    }
});
/*book*/
$('#bookNav li a').click(function (event) {
    event.preventDefault();
    $(this).attr('class', 'current').siblings('.current').removeAttr('class');
});
/*nav*/
$('.nav a').click(function (event) {
    // event.preventDefault();
    $(this).attr('class', 'current').siblings('.current').removeAttr('class');
});




// if( readCookie('SHOWWATCHFOLLOWV') && $('meta[http-equiv=refresh]').size() === 0 ){
//     showWatchFollowMess();
// }
// /**
//  * 提示会员关注
//  * @return {[type]} [description]
//  */
// function showWatchFollowMess(){
//    layer.open({
//         title: [
//             '关注提示',
//             'background-color: #FF4351; color:#fff;'
//         ],
//         contentPosiRelative: true,
//         shadeClose: false,
//         content: '关注微信公众号，领取更多书卷。<br /><img src="'+imgBaseUrl+'img/qrcodeShowWatchFollowMess.jpg" style="width: auto; max-width: 200px; "><span style="position: absolute;bottom: 4px;right: 1px;width: 126px;font-weight: 100;"><label style=" font-weight: 100;font-size:11px;"><input type="checkbox" id="notShowWatchFollowMess" value="1" style=" display: inline-block; width: auto; margin-right: 3px; margin-top: 0; position: relative; top: 4px; ">今日不再提示</label></span> ',
//         btn: ['现在关注'],
//         yes: function(index){
//             var q_check=$('#notShowWatchFollowMess');
//             if( q_check.size() > 0 && $('#notShowWatchFollowMess').attr('checked')  ){
//                 eraseCookie('SHOWWATCHFOLLOWV');
//                 eraseCookie('SHOWWATCHFOLLOWV',';domain='+location.host.replace(/(\w*\.)?(\w+\.\w+)/,'$2'));
//             }
//             // location.href='http://mp.weixin.qq.com/s?__biz=MzI0NzUxODAwMA==&mid=100000004&idx=1&sn=2d396b73091e6f9c979fb514e02fbf1c&mpshare=1&scene=1&srcid=09237CBW0jlgcKhTqIyGEQ8U#rd';
//             layer.close(index);
//         },
//         close:function(index){
//             var q_check=$('#notShowWatchFollowMess');
//             if( q_check.size() > 0 && $('#notShowWatchFollowMess').attr('checked')  ){
//                 eraseCookie('SHOWWATCHFOLLOWV');
//                 eraseCookie('SHOWWATCHFOLLOWV',';domain='+location.host.replace(/(\w*\.)?(\w+\.\w+)/,'$2'));
//             }
//             layer.close(index);                                 
//         }
//     });
// } 
