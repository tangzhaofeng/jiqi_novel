
function DrawImage(ImgD,iwidth,iheight){
    //参数(图片,允许的宽度,允许的高度)
    var image=new Image();
    image.src=ImgD.src;
    if(image.width>0 && image.height>0){
    if(image.width/image.height>= iwidth/iheight){
        if(image.width>iwidth){  
        ImgD.width=iwidth;
        ImgD.height=(image.height*iwidth)/image.width;
        }else{
        ImgD.width=image.width;  
        ImgD.height=image.height;
        }
        }
    else{
        if(image.height>iheight){  
        ImgD.height=iheight;
        ImgD.width=(image.width*iheight)/image.height;        
        }else{
        ImgD.width=image.width;  
        ImgD.height=image.height;
        }
        }
    }
} 
 
 
function xsbortsImageBrowser(
		arrBigImgDesc, //1大图url及description数组
		idBigImg,      //2显示大图的地方id（td id)
		idDescBigImg,  //3显示大图描述的地方id(td id)
		idOuterDiv,    //4缩略图外面的DIV的id
		idInnerDiv,    //5缩略图里面的DIV的id
		urlLoading,    //6图片载入时显示的loading的图片URL地址
		cssLeftArrow,  //7向左箭头的css样式，类似于{'cursor':'url(http://www.xsborts.com/hbcms/image/left.cur),auto'}
		cssRightArrow  //8向右箭头的css样式					 
	)
 {
   this.arrBigImgDesc=arrBigImgDesc;
   this.jqBigImg="#"+idBigImg;
   this.jqDescBigImg="#"+idDescBigImg;
   this.jqOuterDiv="#"+idOuterDiv;
   this.jqInnerDiv="#"+idInnerDiv;
   this.urlLoading=urlLoading;
   this.cssLeftArrow=cssLeftArrow;
   this.cssRightArrow=cssRightArrow;
   this.currentImgNum=-1;
   this.currentCursor=""; //该值为left则鼠标显示左箭头，为right则显示为右箭头
   //得到小图数组
   
   this.getSmallImgDesc= function() {
	   var arrSmallImgDesc=new Array();
	   var strBigImgUrl;
	   var strSmallImgUrl;
	   for(imgNumX=0;imgNumX<this.arrBigImgDesc.length;imgNumX++){
		   strBigImgUrl=imgBigDesc[imgNumX][1];
		   strSmallImgUrl=strBigImgUrl.replace(/.(jpg|gif|png|bmp|jpeg)/,'_78_65.$1');  //此处根据实际情况做些修改
		   arrSmallImgDesc.push([this.arrBigImgDesc[imgNumX][0],strSmallImgUrl]);
	   }
	   return arrSmallImgDesc;
     }
	 
	 
	//插入小图到innerDiv,参数objNameOfImageBrowser是您定义的图片浏览器对象的名字
   this.insertSmallImg= function(objNameOfImageBrowser) {
	   var arrSmallImgDesc=[];
	   var hrefImgChangeToBig="";
 	   var arrSmallImgDesc=this.getSmallImgDesc();
	   var txtSmallImgHtml="<table><tr>";	
      $( this.jqOuterDiv).addClass("scrBar");
	   for(imgNumX=0;imgNumX<arrSmallImgDesc.length;imgNumX++){
		    hrefImgChangeToBig="javascript:"+objNameOfImageBrowser+".imgChangeToBig("+String.fromCharCode(34)+this.arrBigImgDesc[imgNumX][1]+String.fromCharCode(34)+","+String.fromCharCode(34)+this.arrBigImgDesc[imgNumX][0]+String.fromCharCode(34)+","+imgNumX+","+String.fromCharCode(34)+objNameOfImageBrowser+String.fromCharCode(34)+");";  //34为引号的ASCII码
/* onMouseOver=\"$(this).attr('style','border:1px solid yellow');\" onMouseOut=\"$(this).attr('style','');\"*/
			
		   txtSmallImgHtml+="<td class='box_img_td' onMouseOver=\"$(this).attr('style','width:78px; height:65px;border:1px solid red;text-align:center;background:#ffffff;');\" onMouseOut=\"$(this).attr('style','');\"><div class='box_img_div' id='"+ imgNumX+"SmallImgT' style='width:78px; height:65px;border:1px solid #000000;text-align:center;background:#ffffff;'><a href='"+hrefImgChangeToBig+"'>";		
		   txtSmallImgHtml+="<img class='box_img' onload='javascript:DrawImage(this,78,65);this.style.marginTop=((65-this.offsetHeight)/2);' style='border:0px solid #000000;";
		   txtSmallImgHtml+=" id='"+ imgNumX+"SmallImgX' src='"+arrSmallImgDesc[imgNumX][1];
		   txtSmallImgHtml+="' alt='"+arrSmallImgDesc[imgNumX][0]+"' title='"+ arrSmallImgDesc[imgNumX][0]+"'></a></div>";
		   if($.browser.msie){
		   txtSmallImgHtml+="<span class='box_span_titles' id='"+ imgNumX+"spanX'><em id='"+ imgNumX+"emX' class='span_em'><p>&nbsp;</em></span></td>";	/*<span class='box_span_titles' id='"+ imgNumX+"spanX'>"+(imgNumX+1)+"<em id='"+ imgNumX+"emX' class='span_em'>&nbsp;/ "+arrSmallImgDesc.length+"</em></span>*/
		   }else{
			   txtSmallImgHtml+="<span class='box_span_titles' id='"+ imgNumX+"spanX'><em id='"+ imgNumX+"emX' class='span_em'></em></span></td>";
		   }
	   }
	   txtSmallImgHtml+="</tr></table>";
	   $(this.jqInnerDiv).append(txtSmallImgHtml);
	   document.write("<div id='testWH' style='display:none;width:100%;height:100%;'></div>");
	   document.write("<div id='blackDiv' class='blackDiv' onclick='javascript:$("+String.fromCharCode(34)+"#blackDiv"+String.fromCharCode(34)+").toggle();$("+String.fromCharCode(34)+"#bigImageDiv"+String.fromCharCode(34)+").toggle();'></div>");
	    document.write("<div id='bigImageDiv' class='bigImageDiv' onclick='javascript:$("+String.fromCharCode(34)+"#blackDiv"+String.fromCharCode(34)+").toggle();$("+String.fromCharCode(34)+"#bigImageDiv"+String.fromCharCode(34)+").toggle();'></div>");
	   
   }
   
   this.loadImageTimeX,this.loadImageX; //载入的图像的setInterval的返回值，以及载入的图像对象 
   this.isFirst=true;
   //图片切换,参数objNameOfImageBrowser是您定义的图片浏览器对象的名字
   this.imgChangeToBig=function(imgUrlX,imgDescX,imgNumDisplay,objNameOfImageBrowser) {	
           $(this.jqDescBigImg).css({"color":"#000","font-size":"14px"});
           //$(this.jqBigImg).css({"background-color":"#333366"});
	   if(imgNumDisplay!=this.currentImgNum && imgNumDisplay>=0 && imgNumDisplay < this.arrBigImgDesc.length) {
		   loadImageX=new Image();
		 　loadImageX.src=imgUrlX;
		   $(this.jqBigImg).html("<div><img id='loadingImg' src='" +this.urlLoading+"' /></div>");
		   loadImageTimeX=setInterval(objNameOfImageBrowser+".checkImgLoading('"+imgUrlX+"','"+imgDescX+"')",20);
		   var htmlDesc="<span style='vertical-align:middle;'> "+imgDescX;/*+String.fromCharCode(40)+(imgNumDisplay+1)+"/"+this.arrBigImgDesc.length<img id='vwBigImgButton' class='img_txtarea' src='/hbcms/image/view_big.jpg'  alt='浏览大图'";
		   htmlDesc+="onclick='javascript:createBlackAndBigImageDiv("+String.fromCharCode(34)+imgUrlX+String.fromCharCode(34)+");'  "
		   htmlDesc+="onmouseover='javascript:$("+String.fromCharCode(34)+"#vwBigImgButton"+String.fromCharCode(34);
		   htmlDesc+=").css({cursor:"+String.fromCharCode(34)+"pointer"+String.fromCharCode(34)+"});'>String.fromCharCode(41)+*/
		   htmlDesc+="</span>";
		  $('#openBigPic').attr("url",imgUrlX);
		  
		  var preImgurl = imgNumDisplay >0 ? this.arrBigImgDesc[imgNumDisplay-1][1] : '';
		  var preDesc = imgNumDisplay >0 ? this.arrBigImgDesc[imgNumDisplay-1][0] : '';
		  $('#img_prepage').unbind('click');
		  $('#img_prepage').bind('click',function(){eval(objNameOfImageBrowser).imgChangeToBig(preImgurl,preDesc,imgNumDisplay-1,objNameOfImageBrowser)}); 
		 
		  var nextImgurl = imgNumDisplay < this.arrBigImgDesc.length-1 ? this.arrBigImgDesc[imgNumDisplay+1][1] : '';
		  var nextDesc = imgNumDisplay < this.arrBigImgDesc.length-1 ? this.arrBigImgDesc[imgNumDisplay+1][0] : '';
		  $('#img_nextpage').unbind('click');
		  $('#img_nextpage').bind('click',function(){eval(objNameOfImageBrowser).imgChangeToBig(nextImgurl,nextDesc,imgNumDisplay+1,objNameOfImageBrowser)}); 
		  
		  //$(this.jqDescBigImg).html(htmlDesc);
		  $('#currentIndex').html(imgNumDisplay+1);
		  var jqSmallImg="#"+imgNumDisplay+"SmallImgX";
		 
		  var jqCurrentSpanX="#"+this.currentImgNum+"spanX";
		  var jqSpanX="#"+imgNumDisplay+"spanX";
		  var jqCurrentEmX="#"+this.currentImgNum+"emX";
		  var jqEmX="#"+imgNumDisplay+"emX";
		  if(this.currentImgNum!=-1) {
			  //$(jqCurrentSpanX).removeClass("select_span_titles");
			  //$(jqCurrentSpanX).addClass("box_span_titles");
			 
			  //$(jqSpanX).removeClass("box_span_titles");
			  //$(jqSpanX).addClass("select_span_titles");
			  $("div[id*='SmallImgT']").attr("style",'width:78px; height:65px;border:1px solid #000000;text-align:center;background:#ffffff;');
			  $("#"+imgNumDisplay+"SmallImgT").attr("style",'width:78px; height:65px;border:1px solid red;text-align:center;background:#ffffff;');
			  //$(jqCurrentEmX).toggle();
			  //$(jqEmX).toggle();
			  this.isFirst=false;
		   }
		   else 
		    {
			  

			  $("#"+imgNumDisplay+"SmallImgT").attr("style",'width:78px; height:65px;border:1px solid red;text-align:center;background:#ffffff;');
			  //$(jqSpanX).removeClass("box_span_titles");solid #FF6600
			  //$(jqSpanX).addClass("select_span_titles");
		      //$(jqEmX).toggle();  
			  //$(jqEmX).toggle();  
		    }
		  this.currentImgNum=imgNumDisplay;//alert(imgNumDisplay);
		  //$(this.jqOuterDiv).attr("scrollLeft",($(jqSmallImg).position().left-$(this.jqOuterDiv).width()/2+$(jqSmallImg).width()/2));
		  $(this.jqOuterDiv).attr("scrollLeft",(imgNumDisplay*84-$(jqSmallImg).width()));
      }
	  
   }
   
    //检查大图载入状态,参数objNameOfImageBrowser是您定义的图片浏览器对象的名字
   this.checkImgLoading=function(urlImgCheck,descImgCheck) {
       if($("#loadingImg").html()===null) {$(this.jqBigImg).html("<div><img id='loadingImg' src='" +this.urlLoading+"' /></div>");}
	   if(loadImageX.complete){
		   
		   $(this.jqBigImg).html("<img id='imgBigX'style='margin:1px;border:2px solid #DDDDDD; border-width:1px;' src='"+urlImgCheck+"'>");
		   //if(this.currentImgNum==4) document.write('<script language="javascript" type="text\/javascript" src="http://www.ganyou.com/modules/news/?ac=blockshow&id=6"><\/script>');此处放置广告
		   //if(this.currentImgNum==4) $(this.jqBigImg).append('<p><a href="http://www.ganyou.com/pic/9533.html"><img src="http://p.ganyou.com/attachment/image/2011/02/17/112522943.jpg" border="0" alt="宅男的钱真好骗 日本黄金周限定手办锦集" /></a></p><p><a href="http://www.ganyou.com/pic/9533.html">宅男的钱真好骗 日本黄金周限定手办锦集</a></p>');
	　　　  $("#imgBigX").hide();
	         loadImageX=null;
	         clearInterval(loadImageTimeX);
			var scl=$("#imgBigX").width()/$("#imgBigX").height();
	        if($("#imgBigX").width()>690)  {
				$("#vwBigImgButton").toggle();
				/* 
				 $("#hrefBigImgSrc").fancybox({
					   'titleShow'		: false,
					   'transitionIn'	: 'elastic',
					   'transitionOut'	: 'elastic'
				  });*/
				
                  /*if(690/scl<550) {
		              $("#imgBigX").width(690);
				      $("#imgBigX").height(600/scl);
				   }
				   else
				   {
		              $("#imgBigX").height(550);
				      $("#imgBigX").width(550*scl);	
					   
				   }*/
				   $("#imgBigX").width(690);
				   $("#imgBigX").height($("#imgBigX").height()/scl);
		  
	         } 
		   else 
		     {
		      /*if($("#imgBigX").height()>450) 
		        {
		            $("#imgBigX").height(450);
				    $("#imgBigX").width(450*scl);
					$("#vwBigImgButton").toggle();
					
					$("#hrefBigImgSrc").fancybox({
				           'titleShow'		: false,
				           'transitionIn'	: 'elastic',
				           'transitionOut'	: 'elastic'
			         });
					
	            }*/
			 }
	　　　　 $("#imgBigX").fadeIn("slow");  //设置图片显示方式为淡入方式
	        // if(this.isFirst) {
				 this.downloadImage(this.currentImgNum+1);
			//}
 　　　}    
    }
	
	//xk:最靠近左边的那张图片在所有图片中排在第几位。从1开始算。
	this.downloadImage=function(xk) {
	   if(xk<this.arrBigImgDesc.length) {
				for(i=xk;i<((this.arrBigImgDesc.length>(xk+5))?(xk+5):this.arrBigImgDesc.length);i++) {
					var downImg=new Image();
					downImg.src=this.arrBigImgDesc[i][1];
					downImg=null;
				}
	   }
		
	}

   //为每个缩略图生成hover事件函数
  
   this.createAllSmallImgHover=function(objNameOfImageBrowser) {
	   var txtImgSmallHover="";
       for(imgNumX=0;imgNumX<this.arrBigImgDesc.length;imgNumX++){
          strImgSmallID=String.fromCharCode(34)+"#"+imgNumX+"SmallImgX"+String.fromCharCode(34);
		 // strSpanID=String.fromCharCode(34)+"#"+imgNumX+"spanX"+String.fromCharCode(34);
          txtImgSmallHover+="$("+strImgSmallID+").hover(  ";
          txtImgSmallHover+="function () {  ";
    
	
	      txtImgSmallHover+=";";
         // txtImgSmallHover+="$("+strImgSmallID+").attr('border',1);  ";
         // txtImgSmallHover+="$("+strImgSmallID+").css('opacity',1);  ";
		 // txtImgSmallHover+="var thisO=document.getElementById('"+imgNumX+"SmallImgX');";
		 // txtImgSmallHover+="if("+strImgSmallID+".substr(1,1)!="+objNameOfImageBrowser+".currentImgNum)  "; 
		 // txtImgSmallHover+="$("+strSpanID+").removeClass('box_span_titles');";
		//  txtImgSmallHover+="else ";
		//  txtImgSmallHover+="$("+strSpanID+").removeClass('select_span_titles');";
		//  txtImgSmallHover+="$("+strSpanID+").addClass('span_titles_none');";
        //  txtImgSmallHover+="imgChangeBig(thisO);";
           txtImgSmallHover+=" },  ";
         txtImgSmallHover+="function () {  ";
		 txtImgSmallHover+=";";
		//  txtImgSmallHover+="var thisO=document.getElementById('"+imgNumX+"SmallImgX');";
		//  txtImgSmallHover+="$("+strSpanID+").removeClass('span_none');";
		//  txtImgSmallHover+="if("+strImgSmallID+".substr(1,1)!="+objNameOfImageBrowser+".currentImgNum)  "; 
		 // txtImgSmallHover+="$("+strSpanID+").addClass('box_span_titles');";
		 // txtImgSmallHover+="else ";
		 // txtImgSmallHover+="$("+strSpanID+").addClass('select_span_titles');";
		  
		//  txtImgSmallHover+="imgChangeWD(thisO,80,80);";
         // txtImgSmallHover+="$("+strImgSmallID+").attr('border',0);  ";
         // txtImgSmallHover+="if("+strImgSmallID+".substr(1,1)!="+objNameOfImageBrowser+".currentImgNum)  ";  //this.currentImgNum这里可能有问题
         // txtImgSmallHover+="{$("+strImgSmallID+").css('opacity',0.4);}  ";    
    
    
         txtImgSmallHover+=" }  ";
         txtImgSmallHover+=" ); "; 
		  //滚动条滚动时将下载看得见的图片的大图
		  txtImgSmallHover+="$("+objNameOfImageBrowser+".jqOuterDiv).scroll(function(){ ";
		  txtImgSmallHover+="newLeftNum=Math.ceil(Math.abs($("+objNameOfImageBrowser+".jqInnerDiv).position().left/110));  ";　//每缩略图占110px
		  txtImgSmallHover+="if(oldLeftNum!=newLeftNum) {";
		  txtImgSmallHover+=objNameOfImageBrowser+".downloadImage(newLeftNum);  ";
		//  txtImgSmallHover+="$('#scrollPost').html($("+objNameOfImageBrowser+".jqInnerDiv).position().left+'|||'+newLeftNum);";  //测试所用
		  txtImgSmallHover+="oldLeftNum=newLeftNum;}  ";
		  
		  txtImgSmallHover+="});";
		  
	   }
	   return txtImgSmallHover;
   } 
   //鼠标放在图片上显示向左向右箭头
   this.createBigImgMouseMove=function(objNameOfImageBrowser) {
	   var txtBigImgMouseMove="";
	   txtBigImgMouseMove+="$("+objNameOfImageBrowser+".jqBigImg).mousemove(function(e){  ";
       txtBigImgMouseMove+="var positionX=e.originalEvent.x-$("+objNameOfImageBrowser+".jqBigImg).offset().left||e.originalEvent.layerX-$("+objNameOfImageBrowser+".jqBigImg).offset().left||0; ";
       txtBigImgMouseMove+="if(positionX < $("+objNameOfImageBrowser+".jqBigImg).width()/2)  ";
	   txtBigImgMouseMove+="{ if("+objNameOfImageBrowser+".currentCursor!='left')" ;
       txtBigImgMouseMove+="{$("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssLeftArrow);   ";
	   txtBigImgMouseMove+=objNameOfImageBrowser+".currentCursor='left';}}  ";	   
       txtBigImgMouseMove+="else  ";
	   txtBigImgMouseMove+="{ if("+objNameOfImageBrowser+".currentCursor!='right')" ;
       txtBigImgMouseMove+="{ $("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssRightArrow);  ";
	   txtBigImgMouseMove+=objNameOfImageBrowser+".currentCursor='right';}}  ";
       txtBigImgMouseMove+="});  ";
	   return txtBigImgMouseMove;
   }
   //鼠标按上下时显示手形
  this.createBigImgMouseDown=function(objNameOfImageBrowser) {
	   var txtBigImgMouseDown="";
	   txtBigImgMouseDown+="$("+objNameOfImageBrowser+".jqBigImg).mousedown(function(){  ";
       txtBigImgMouseDown+="$("+objNameOfImageBrowser+".jqBigImg).css({'cursor':'pointer'}); ";
       txtBigImgMouseDown+="}); ";
	   return txtBigImgMouseDown;
   }
   //鼠标松开时显示左右箭头
   this.createBigImgMouseUp=function(objNameOfImageBrowser) {
	    var txtBigImgMouseUp="";
	    txtBigImgMouseUp+="$("+objNameOfImageBrowser+".jqBigImg).mouseup(function(e){  ";
        txtBigImgMouseUp+="var positionX=e.originalEvent.x-$("+objNameOfImageBrowser+".jqBigImg).offset().left||e.originalEvent.layerX-$("+objNameOfImageBrowser+".jqBigImg).offset().left||0; ";
        txtBigImgMouseUp+="if(positionX < $("+objNameOfImageBrowser+".jqBigImg).width()/2)  ";
        txtBigImgMouseUp+="{$("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssLeftArrow);   ";
	    txtBigImgMouseUp+=objNameOfImageBrowser+".currentCursor='left';}  ";	   
        txtBigImgMouseUp+="else  ";
        txtBigImgMouseUp+="{ $("+objNameOfImageBrowser+".jqBigImg).css('cursor',"+objNameOfImageBrowser+".cssRightArrow);  ";
	    txtBigImgMouseUp+=objNameOfImageBrowser+".currentCursor='right';}  ";
        txtBigImgMouseUp+="});  ";
		return txtBigImgMouseUp;
   }
   //点击大图时切换图片
   this.createBigImgClick=function(objNameOfImageBrowser) {
	    var txtBigImgClick="";
	    txtBigImgClick+="$("+objNameOfImageBrowser+".jqBigImg).click(function(e){ 　";
　      txtBigImgClick+="var positionX=e.originalEvent.x-$("+objNameOfImageBrowser+".jqBigImg).offset().left||e.originalEvent.layerX-$("+objNameOfImageBrowser+".jqBigImg).offset().left||0;  ";
        txtBigImgClick+="var numPreImg=("+objNameOfImageBrowser+".currentImgNum-1<0)?0:("+objNameOfImageBrowser+".currentImgNum-1);  ";
        txtBigImgClick+="var numNextImg=("+objNameOfImageBrowser+".currentImgNum+1>imgBigDesc.length-1)?(imgBigDesc.length-1):("+objNameOfImageBrowser+".currentImgNum+1);  ";
        txtBigImgClick+="if(positionX < $("+objNameOfImageBrowser+".jqBigImg).width()/2) {  ";
        txtBigImgClick+=objNameOfImageBrowser+".imgChangeToBig("+objNameOfImageBrowser+".arrBigImgDesc[numPreImg][1],"+objNameOfImageBrowser+".arrBigImgDesc[numPreImg][0],numPreImg,'"+objNameOfImageBrowser+"');} ";
        txtBigImgClick+=" else {  ";
        txtBigImgClick+=objNameOfImageBrowser+".imgChangeToBig("+objNameOfImageBrowser+".arrBigImgDesc[numNextImg][1],"+objNameOfImageBrowser+".arrBigImgDesc[numNextImg][0],numNextImg,'"+objNameOfImageBrowser+"'); }  "
        txtBigImgClick+=" });  ";
		return txtBigImgClick;
   }
   this.createScriptHead=function(){
      var txtScriptHead=""; 
      txtScriptHead+=String.fromCharCode(60)+"script language='javascript'"+String.fromCharCode(62)+"  " ;
      txtScriptHead+="$(function(){  ";		
	  txtScriptHead+="var oldLeftNum=0; ";						 
      return txtScriptHead;
   }
   this.createScriptTail=function(){
      var txtScriptTail="";
      txtScriptTail+="});  ";
      txtScriptTail+=String.fromCharCode(60)+"/script"+String.fromCharCode(62)+"  ";
      return txtScriptTail;
   }
}

//获取元素的纵坐标
function getTop(e){
var offset=e.offsetTop;
if(e.offsetParent!=null) offset+=getTop(e.offsetParent);
return offset;
}
//获取元素的横坐标
function getLeft(e){
var offset=e.offsetLeft;
if(e.offsetParent!=null) offset+=getLeft(e.offsetParent);
return offset;
}

function createBlackAndBigImageDiv(imgUrlX) {


   
   var bigImage=new Image();
   bigImage.src=imgUrlX;
   var imgWidth;
   var imgHeight;
   if(bigImage.complete) {
	   scl=bigImage.width/bigImage.height;
	   if(bigImage.width>document.body.clientWidth)
		  {
			 if(document.body.clientWidth/scl<document.body.clientHeight) 
				 {
					 imgWidth=document.body.clientWidth;
					 imgHeight=document.body.clientWidth/scl;
				
				 }
			  else
				 {
					imgWidth=document.body.clientHeight*scl;
					imgHeight=document.body.clientHeight;
				 }
		   }
		 else
		   {
			 if(bigImage.height>document.body.clientHeight)
				{
					imgWidth=document.body.clientHeight*scl;
					imgHeight=document.body.clientHeight;
				}
				
			 else
				{
					imgWidth=bigImage.width;
					imgHeight=bigImage.height;
				}
		   }
   }
	//imgWidth=imgWidth-40;$("body").scrollLeft()+document.body.clientWidth/2
   // imgHeight=imgHeight-40;document.body.scrollWidth
   $("#blackDiv").css({width:$("body").scrollLeft()+document.body.clientWidth,height:document.body.scrollHeight});
   $("#blackDiv").css({'opacity':0.6});
   $("#blackDiv").css({'left':490-document.body.clientWidth/2-$("body").scrollLeft()/2});
   $("#bigImageDiv").css({left:490-imgWidth/2,top:$("body").scrollTop()+document.body.clientHeight/2-imgHeight/2,width:imgWidth,height:imgHeight});
  // alert(imgWidth+"|"+imgHeight);
   $("#bigImageDiv").html("<img src='"+imgUrlX+"' width="+imgWidth+" height="+imgHeight+" >")
   $("#blackDiv").toggle();
   $("#bigImageDiv").toggle();
  // alert(getLeft(document.getElementById("bigImageDiv")));alert(getLeft(document.getElementById("blackDiv")));
  // $("#bigImageDiv").animate({width:imgWidth},{height:imgHeight},1000);
   //$("#bigImageDiv").animate({height:imgHeight},500);
}
