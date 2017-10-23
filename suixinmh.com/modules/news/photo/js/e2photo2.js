// JavaScript Document
function initGallery ( tempgallery, count, first_id, startwidth, startheight ) {
	imggallery = tempgallery;
	if (preloadimg=="yes"){
		for (x=0; x<imggallery.length; x++){
			var myimage=new Image()
			myimage.src=imggallery[x][0]
		}
	}
	thumbnailnum = imggallery.length;
	current_imgid = first_id;
	currentwidth=startwidth;
	currentheight=startheight;
	window.addEvent('load', function() {
		if(thumbnailnum>maxthumbvisible){
			lefthtml = "<div id='leftmore'><ul><li><a href=\"javascript:checkbutton(addposition('minus'));movethumbs('plus');\"><img src='rsrc/buttonblank.gif' width='15' height='115' border='0' /></a></li></ul></div>";
			righthtml = "<div id='rightmore'><ul><li><a href=\"javascript:checkbutton(addposition('plus'));movethumbs('minus');\"><img src='rsrc/buttonblank.gif' width='15' height='115' border='0' /></a></li></ul></div>";
			$('back').setHTML(lefthtml);
			$('more').setHTML(righthtml);
		}
		var setloadersize = new Fx.Styles('main_image_wrapper',{duration:transspeed,onComplete: function(){loadfirstimage(currentwidth,currentheight)}});
		setloadersize.start({
			'width':imggallery[0][1],
			'height':imggallery[0][2]	
		});
	});
	
	
}
window.addEvent('domready', function() {
	initGallery( tempgallery, tempgallery.length, tempgallery[0][8], tempgallery[0][1], tempgallery[0][2], 0 );
});

function getstarted(width, height, loadarea, imgindex, img_id, current_imgid){
	checknext(img_id);
	if(current_imgid!=img_id){
		if(firstimagestart==1){
			currentwidth=firstimagewidth;
			currentheight=firstimageheight;
			firstimagestart=0;		
		}
		if(nextorprev==1){
			currentwidth=cwidth;
			currentheight=cheight;
			nextorprev=0;		
		}
		
		var resizeDivHeight = new Fx.Styles('main_image_wrapper',{duration:transspeed, onComplete: function(){modifyimage(loadarea, imgindex, img_id);currentheight=height;currentwidth=width;} });
		var fader = new Fx.Style('imgloader','opacity', {duration:fadespeed, onComplete: function(){	resizeDivHeight.start({'height': [currentheight,height],'width': [currentwidth,width]});} });
		fader.start(1,0);	
		var fadeiptc = new Fx.Style('iptc_btn','opacity', {duration:transspeed });
		fadeiptc.set(0);
		var titlefade = new Fx.Style('imgtitle','opacity', {duration:transspeed });
		titlefade.set(0);
		fadeout=0
		if(fadeout==0){
		var fademe = new Fx.Style('iptc_info','opacity', {duration:transspeed });
		fademe.set(0);
		fadeout=0;
		}else{
			var fademe = new Fx.Style('iptc_info','opacity', {duration:transspeed });
			fademe.start(1,0);
			fadeout=0
		}
	}
}
function loadfirstimage(currentwidth,currentheight){
	var fadefirst = new Fx.Style('imgloader','opacity', {duration:fadespeed });
	fadefirst.set(0);
	var firsttitlefade = new Fx.Style('imgtitle','opacity', {duration:transspeed });
	firsttitlefade.set(0);
	function setfirstimage(){
		var newHTML = "<img src='"+tempgallery[0][0]+"' />";
		$('imgloader').setHTML(newHTML);
	
		var firsttitle=document.getElementById('imgtitle');
		firsttitle.innerHTML="<strong>"+tempgallery[0][4]+"</strong> "+tempgallery[0][7];
		fadefirst.start(0,1);
		//firsttitlefade.start(0,1);
		currentheight=imggallery[0][2];
		currentwidth=imggallery[0][1];
	}
	new Asset.image(imggallery[0][0], {onload: setfirstimage});
}
function nextimage(current_imgid){
	
	newimgid = Number(current_imgid)+1;
	newwidth =imggallery[newimgid][1]
	newheight =imggallery[newimgid][2]
	newimgindex =imggallery[newimgid][8]
	newimgid = imggallery[newimgid][8]
	cwidth=imggallery[current_imgid][1]
	cheight=imggallery[current_imgid][2]
	checknext(newimgid);
	nextorprev=1;
	getstarted(Number(newwidth), Number(newheight), 'imgloader',Number(newimgindex) ,Number(newimgid) , Number(current_imgid), Number(cwidth), Number(cheight))
}

function previmage(current_imgid){
	newimgid = Number(current_imgid)-1;
	newwidth =imggallery[newimgid][1]
	newheight =imggallery[newimgid][2]
	newimgindex =imggallery[newimgid][8]
	newimgid = imggallery[newimgid][8]
	cwidth=imggallery[current_imgid][1]
	cheight=imggallery[current_imgid][2]
	checknext(newimgid);
	nextorprev=1;
	getstarted(Number(newwidth), Number(newheight), 'imgloader',Number(newimgindex) ,Number(newimgid) , Number(current_imgid), Number(cwidth), Number(cheight))
}
