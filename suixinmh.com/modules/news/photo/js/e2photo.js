// JavaScript Document

var currentpos=0;
var currentthumbpos=0;
var moveamount=106;
var thumbnailnum;
var maxthumbvisible = 5; //Define how many thumbnails will be visible at one time in the thumbbox--for now this should only be 3 since it's actually the css that controls the size of the viewable box
var current_imgid=0;
var moving = false;
var finishedsizing=0;
var preloadimg="no"//Preload images ("yes" or "no"):
var myloadedimage = new Array();
     myloadedimage[0]=1;
var imggallery=new Array()
var firstimagestart=1;
var nextorprev=0;
var fadeout=1;

function loadinfo(){
	if(fadeout==0){
		var fademe = new Fx.Style('iptc_info','opacity', {duration:transspeed });
		fademe.set(0);
		fadeout=1;
	}else{
		var fademe = new Fx.Style('iptc_info','opacity', {duration:transspeed });
		fademe.set(.75,0);
		fadeout=0;
	}
	
}
function areweready(loadarea, imgindex, img_id){
	if ( finishedsizing++ ) { 
		modifyimage(loadarea, imgindex, img_id);
		finishedsizing = 0;
	}
}

function modifyimage(loadarea, imgindex, img_id ){
	function loadimagenow(){
		//alert("Load Image Now Called");
		if (document.getElementById) {
			
				if(current_imgid!=img_id){
					
					var imgobj=document.getElementById(loadarea);
					var iptch=document.getElementById('iptc_info');
					var photonum=document.getElementById('photocount')
					var phototitle=document.getElementById('imgtitle')
					imgobj.innerHTML=returnimgcode(imggallery[imgindex]);
					iptch.innerHTML=returniptc(imggallery[imgindex]);
					photonum.innerHTML=(Number(imgindex)+1)+" of "+imggallery.length+" Photos";
					phototitle.innerHTML="<strong>"+tempgallery[img_id][4]+"</strong> "+tempgallery[img_id][7];
					initImage(loadarea);
					current_imgid=img_id;
					myloadedimage[imgindex]=1;
				}
			}
		return false
	}
	if(myloadedimage[imgindex]==null){	
		new Asset.image(imggallery[imgindex][0], {onload: loadimagenow});
		
	}else{
		loadimagenow();
	}
	
}

function returnimgcode(theimg){
	var imghtml=""
	if (theimg[1]!="")
		imghtml=''
	imghtml+='<img src="'+theimg[0]+'" border="0" id="'+theimg[8]+'" />'
	if (theimg[1]!="")
		imghtml+=''
	return imghtml
}
function returniptc(theimg){
	var iptchtml = ''+
	'<div class=\'iptc_left\'><br/>'+
			'	Title <br/>'+
			'	Author <br/>'+
			'	Copyright <br/><br/>'+
			'	Description <br/><br/>'+
			'</div>'+
			'<div class=\'iptc_right\'><br/>'+
			'	'+theimg[4]+'<br/>'+ //title
			'	'+theimg[5]+'<br/>'+ // author
			'	'+theimg[6]+'<br/><br/>'+ //copyright
			'	'+theimg[7]+'<br/><br/>'+ // description
			'</div>';
	return iptchtml
}

function initImage(imageId) {
	var fader = new Fx.Style(imageId,'opacity', {duration:fadespeed});
	fader.set(0);
	fader.start(0,1);
	var titlefade = new Fx.Style('imgtitle','opacity', {duration:transspeed });
		titlefade.set(0);
		titlefade.start(0,1);
	var fadeiptc = new Fx.Style('iptc_btn','opacity', {duration:transspeed });
		fadeiptc.set(0);
		fadeiptc.start(0,1);
	if(fadeout==0){
		var fademe = new Fx.Style('iptc_info','opacity', {duration:transspeed });
		fademe.set(0);
		fadeout=0;
	}else{
		var fademe = new Fx.Style('iptc_info','opacity', {duration:transspeed });
		fademe.start(0,1);
		fadeout=1
	}
}

function checkbutton(mynum){
	if ( mynum == 0 ) {
		mm_shl('back','hidden');
		mm_shl('more','visible');
	} else if ( mynum < thumbnailnum - maxthumbvisible ) {
		mm_shl('back','visible');
		mm_shl('more','visible');
	} else {
		mm_shl('back','visible');
		mm_shl('more','hidden');
	}
}
function checknext(mynum){
	thumbmax=(Number(thumbnailnum)-1);
	if ( mynum < 1 ) {
		mm_shl('prev','hidden');
		mm_shl('next','visible');
	} else if ( mynum <  thumbmax ) {
		mm_shl('prev','visible');
		mm_shl('next','visible');
	} else {
		mm_shl('prev','visible');
		mm_shl('next','hidden');
	}
}

function mm_shl() { //v6.0
	var obj,args=arguments;
	if ((obj=MM_findObj(args[0]))!=null) {
		if (obj.style) {
			obj=obj.style;
		}
		obj.visibility=args[1];
	}
}


function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function addposition(addwidth){
	if ( !moving ) {
		 // if animagic is still moving the image..don't update the current position till it's done
		if(addwidth=="minus"){
			currentpos-=1;
		}else if(addwidth=="plus"){
			currentpos+=1;
		}
	}
	return currentpos;
}
function movethumbs(way){
	if(way=='plus'){
		move=(currentthumbpos+moveamount);
		var movethumbs = new Fx.Styles('thumbgall', {duration: transspeed, transition: Fx.Transitions.quadOut});
		movethumbs.start({ left: [currentthumbpos, move]});
		currentthumbpos+=moveamount;
	
	}else if(way=='minus'){
		move=(currentthumbpos-moveamount);
		var movethumbs = new Fx.Styles('thumbgall', {duration: transspeed, transition: Fx.Transitions.quadOut});
		movethumbs.start({ left: [currentthumbpos, move]});
		currentthumbpos-=moveamount;		
	}
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
var thumbopen=0
	function thumbs(){
		var resizethumb = new Fx.Styles('thumbhide',{duration:transspeed, transition: Fx.Transitions.quadOut});
		var movethumbs = new Fx.Styles('thumbbox', {duration: transspeed, transition: Fx.Transitions.quadOut});
		if(thumbopen==1){	
			
			resizethumb.start({'height': 119});
			movethumbs.start({ 'top': [-120, 0]});
			thumbopen=0
		}else{
			resizethumb.start({'height': 0});
			movethumbs.start({ 'top': [0, -120]});
			thumbopen=1
		}
	}