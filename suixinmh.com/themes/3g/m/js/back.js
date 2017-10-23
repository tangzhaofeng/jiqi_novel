/**
 * Created by admin on 2016/8/16.
 */
var nStartX,nChangX;
$('body')[0].addEventListener('touchstart', function (e) {
    nStartX = e.targetTouches[0].pageX;
});
$('body')[0].addEventListener('touchend', function (e) {
    nChangX = e.changedTouches[0].pageX;
    if(nStartX<nChangX-35) {
        location.href=$('#prev').attr('href');
    }else if(nStartX>nChangX+35){
        location.href=$('#next').attr('href');
    }
});