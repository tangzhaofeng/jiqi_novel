function openwinx(url,name,w,h)
{
    window.open(url,name,"top=100,left=400,width=" + w + ",height=" + h + ",toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=no");
}

function Dialog(url,name,w,h)
{
	return showModalDialog(url, name, 'dialogWidth:'+w+'px; dialogHeight:'+h+'px; help: no; scroll: yes; status: no;');
}
function redirect(url)
{
	if(url.lastIndexOf('/.') > 0)
	{
		url = url.replace(/\/(\.[a-zA-Z]+)([0-9]+)$/g, "/$2$1");
	}
	else if(url.match(/\/([a-z]+).html([0-9]+)$/)) {
		url = url.replace(/\/([a-z]+).html([0-9]+)$/, "/$1-$2.html");
	}
	else if(url.match(/-.html([0-9]+)$/)) {
		url = url.replace(/-.html([0-9]+)$/, "-$1.html");
	}

	if(url.indexOf('://') == -1 && url.substr(0, 1) != '/' && url.substr(0, 1) != '?') url = $('base').attr('href')+url;
	location.href = url;
}
//添加收藏夹
function myAddPanel(title,url)
{
    if ((typeof window.sidebar == 'object') && (typeof window.sidebar.addPanel == 'function'))
    {
        window.sidebar.addPanel(title,url,"");
    }
    else
    {
        window.external.AddFavorite(url,title);
    }
}
function confirmurl(url,message)
{
	if(confirm(message)) redirect(url);
}

function confirmform(form,message)
{
	if(confirm(message)) form.submit();
}


function checkall(fieldid)
{
	if(fieldid==null)
	{
		if($('#checkbox').attr('checked')==false)
		{
			$('input[type=checkbox]').attr('checked',true);
		}
		else
		{
			$('input[type=checkbox]').attr('checked',false);
		}
	}
	else
	{
		var fieldids = '#'+fieldid;
		var inputfieldids = 'input[boxid='+fieldid+']';
		if($(fieldids).attr('checked')==false)
		{
			$(inputfieldids).attr('checked',true);
		}
		else
		{
			$(inputfieldids).attr('checked',false);
		}
	}
}
function CheckedRev(){
	var arr = $(':checkbox');
	for(var i=0;i<arr.length;i++)
	{
		if (arr[i].checked = ! arr[i].checked)
		{
			$("#chkall").val("取消全选");
		}else
		{
			$("#chkall").val("全选");
		}
	}
}
function checkradio(radio)
{
	var result = false;
	for(var i=0; i<radio.length; i++)
	{
		if(radio[i].checked)
		{
			result = true;
			break;
		}
	}
    return result;
}

function checkselect(select)
{
	var result = false;
	for(var i=0;i<select.length;i++)
	{
		if(select[i].selected && select[i].value!='' && select[i].value!=0)
		{
			result = true;
			break;
		}
	}
    return result;
}

var flag=false;
function setpicWH(ImgD,w,h)
{
	var image=new Image();
	image.src=ImgD.src;
	if(image.width>0 && image.height>0)
	{
		flag=true;
		if(image.width/image.height>= w/h)
		{
			if(image.width>w)
			{
				ImgD.width=w;
				ImgD.height=(image.height*w)/image.width;
				ImgD.style.display="block";
			}else{
				ImgD.width=image.width;
				ImgD.height=image.height;
				ImgD.style.display="block";
			}
		}else{
			if(image.height>h)
			{
				ImgD.height=h;
				ImgD.width=(image.width*h)/image.height;
				ImgD.style.display="block";
			}else{
				ImgD.width=image.width;
				ImgD.height=image.height;
				ImgD.style.display="block";
			}
		}
	}
}

var Browser = new Object();
Browser.isMozilla = (typeof document.implementation != 'undefined') && (typeof document.implementation.createDocument != 'undefined') && (typeof HTMLDocument!='undefined');
Browser.isIE = window.ActiveXObject ? true : false;
Browser.isFirefox = (navigator.userAgent.toLowerCase().indexOf("firefox")!=-1);
Browser.isSafari = (navigator.userAgent.toLowerCase().indexOf("safari")!=-1);
Browser.isOpera = (navigator.userAgent.toLowerCase().indexOf("opera")!=-1);

var Common = new Object();
Common.htmlEncode = function(str)
{
	return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

Common.trim = function(str)
{
	return str.replace(/(^\s*)|(\s*$)/g, "");
}

Common.strlen = function (str)
{
	if(Browser.isFirefox)
	{
		Charset = document.characterSet;
	}
	else
	{
		Charset = document.charset;
	}
	if(Charset.toLowerCase() == 'utf-8')
	{
		return str.replace(/[\u4e00-\u9fa5]/g, "***").length;
	}
	else
	{
		return str.replace(/[^\x00-\xff]/g, "**").length;
	}
}

Common.isdate = function (str)
{
   var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
   if(result==null) return false;
   var d=new Date(result[1], result[3]-1, result[4]);
   return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]);
}

Common.isnumber = function(val)
{
    var reg = /[\d|\.|,]+/;
    return reg.test(val);
}

Common.isalphanumber = function (str)
{
	var result=str.match(/^[a-zA-Z0-9]+$/);
	if(result==null) return false;
	return true;
}

Common.isint = function(val)
{
    var reg = /\d+/;
    return reg.test(val);
}

Common.isemail = function(email)
{
    var reg = /([\w|_|\.|\+]+)@([-|\w]+)\.([A-Za-z]{2,4})/;
    return reg.test( email );
}

Common.fixeventargs = function(e)
{
    var evt = (typeof e == "undefined") ? window.event : e;
    return evt;
}

Common.srcelement = function(e)
{
    if (typeof e == "undefined") e = window.event;
    var src = document.all ? e.srcElement : e.target;
    return src;
}

Common.isdatetime = function(val)
{
	var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/);
	if(result==null) return false;
	var d= new Date(result[1], result[3]-1, result[4], result[5], result[6], result[7]);
	return (d.getFullYear()==result[1]&&(d.getMonth()+1)==result[3]&&d.getDate()==result[4]&&d.getHours()==result[5]&&d.getMinutes()==result[6]&&d.getSeconds()==result[7]);
}

var FileNum = 1;
function AddInputFile(Field)
{
    FileNum++;
	var fileTag = "<div id='file_"+FileNum+"'><input type='file' name='"+Field+"["+FileNum+"]' size='20' onchange='javascript:AddInputFile(\""+Field+"\")'> <input type='text' name='"+Field+"_description["+FileNum+"]' size='20' title='名称'> <input type='button' value='删除' name='Del' onClick='DelInputFile("+FileNum+");'></div>";
	var fileObj = document.createElement("div");
	fileObj.id = 'file_'+FileNum;
	fileObj.innerHTML = fileTag;
	document.getElementById("file_div").appendChild(fileObj);
}

function DelInputFile(FileNum)
{
    var DelObj = document.getElementById("file_"+FileNum);
    document.getElementById("file_div").removeChild(DelObj);
}

function FilePreview(Url, IsShow)
{
	Obj = document.getElementById('FilePreview');
	if(IsShow)
	{
		Obj.style.left =event.clientX+document.body.scrollLeft-340;
		Obj.style.top = event.clientY+document.body.scrollTop+5;
		Obj.innerHTML = "<img src='"+Url+"'>";
		Obj.style.display = 'block';
	}
	else
	{
		Obj.style.display = 'none';
	}
}

function setEditorSize(editorID,flag)
{
	var minHeight = 400;
	var step = 150;
	var e=$('#'+editorID);
	var h =parseInt(e.height());
	if(!flag && h<minHeight)
	{
		e.height(200);
		return ;
	}
	return flag?(e.height(h+step)):(e.height(h-step));
}

function EditorSize(editorID)
{
	$('a[action]').parent('div').css({'text-align':'right'});
	$('a[action]').css({'font-size':'24px','font-weight':700,display:'block',float:'right',width:'28px','text-align':'center'});
	$('a[action]').click(function(){
		var flag= parseInt($(this).attr('action'));
		setEditorSize(editorID,flag);
	});
}

function loginCheck(form)
{
	var username = form.username;
	var password = form.password;
	var cookietime = form.cookietime;
	if(username.value == ''){alert("请输入用户名");username.focus();return false;}
	if(password.value == ''){alert("请输入密码");password.focus();return false;}
	var days = cookietime.value == 0 ? 1 : cookietime.value/86400;
	setcookie('username', username.value, days);
	return true;
}

function modal(url, triggerid, id, type)
{
	id = '#' + id;
	triggerid = '#' + triggerid;
	switch(type)
	{
		case 'ajax':
			$(id).jqm({ajax: url, modal:false, trigger: triggerid});
		break;
		default:
			$(id).jqm();
		break;
	}
	$(id).html('');
	$(id).hide();
}

function menu_selected(id)
{
	$('#menu_'+id).addClass('selected');
}

function CutPic(textid,thumbUrl){
  var thumb= $('#'+textid).val();
	if(thumb=='')
	{
		alert('请先上传标题图片');
		$('#'+textid).focus();
		return false;
	}
	else
	{
		//if(thumb.indexOf('://') == -1) thumb = thumbUrl+thumb;
	}
  var arr=Dialog(thumbUrl + '/modules/news/admin/?ac=cutimage&thumb='+thumb,'',700,510);
  if(arr!=null){
    $('#'+textid).val(arr);
  }
}

function is_ie()
{
	if(!$.browser.msie)
	{
		$("body").prepend('<div id="MM_msie" style="border:#FF7300 solid 1px;padding:10px;color:#FF0000">本功能只支持IE浏览器，请用IE浏览器打开。<div>');
	}
}

function select_catids()
{
	$('#addbutton').attr('disabled','');

}

function transact(update,fromfiled,tofiled)
{
	if(update=='delete')
	{
		var fieldvalue = $('#'+tofiled).val();

		$("select[@id="+tofiled+"] option").each(function()
		{
		   if($(this).val() == fieldvalue){
			$(this).remove();
		   }
		});
	}
	else
	{
		var fieldvalue = $('#'+fromfiled).val();
		var have_exists = 0;
		var len = $("select[@id="+tofiled+"] option").length;
		if(len>5)
		{
			alert('最多添加 6 项');
			return false;
		}
		$("select[@id="+tofiled+"] option").each(function()
		{
		   if($(this).val() == fieldvalue){
			have_exists = 1;
			alert('已经添加到列表中');
			return false;
		   }
		});
		if(have_exists==0)
		{
			fieldvalue = "<option value='"+fieldvalue+"'>"+fieldvalue+"</option>"
			$('#'+tofiled).append(fieldvalue);
			$('#deletebutton').attr('disabled','');
		}
		
	}
}
var set_show = false;

function base64encode(str) { 
str = utf16to8(str); 
var out, i, len;    
var c1, c2, c3;    
var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"; 
len = str.length;    
i = 0;    
out = "";    
while(i < len) { 
c1 = str.charCodeAt(i++) & 0xff;    
if(i == len){ 
out += base64EncodeChars.charAt(c1 >> 2); 
out += base64EncodeChars.charAt((c1 & 0x3) << 4); 
out += "==";       
break;    
}    
c2 = str.charCodeAt(i++);    
if(i == len)    {        
out += base64EncodeChars.charAt(c1 >> 2);        
out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));        
out += base64EncodeChars.charAt((c2 & 0xF) << 2);        
out += "=";        
break;    
} 
c3 = str.charCodeAt(i++);    
out += base64EncodeChars.charAt(c1 >> 2);    
out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));    
out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));    
out += base64EncodeChars.charAt(c3 & 0x3F);    
}   
return out; 
} 
function base64decode(str){ 
var c1, c2, c3, c4;    
var i, len, out;   
var base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 
-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 
52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, 
-1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14, 
15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, 
-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 
41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1); 
len = str.length;    
i = 0;    
out = "";    
while(i < len) {    
do {        
c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];    
} 
while(i < len && c1 == -1);    
if(c1 == -1) break;   
do {        
c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];    
} 
while(i < len && c2 == -1);    
if(c2 == -1)  break;    
out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));    
do {        
c3 = str.charCodeAt(i++) & 0xff;        
if(c3 == 61) return out;        
c3 = base64DecodeChars[c3];    
} 
while(i < len && c3 == -1);    
if(c3 == -1) break;    
out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));    
do {        
c4 = str.charCodeAt(i++) & 0xff;        
if(c4 == 61) return out;        
c4 = base64DecodeChars[c4];    
} 
while(i < len && c4 == -1);    
if(c4 == -1) break;    
out += String.fromCharCode(((c3 & 0x03) << 6) | c4);    
} 
out = utf8to16(out); 
return out; 
} 
function utf16to8(str) {    
var out, i, len, c;    
out = "";    len = str.length;    
for(i = 0; i < len; i++) {    
c = str.charCodeAt(i);    
if ((c >= 0x0001) && (c <= 0x007F)) {        
out += str.charAt(i);    
} else if (c > 0x07FF) {        
out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));        
out += String.fromCharCode(0x80 | ((c >>  6) & 0x3F));        
out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));    
} else {        
out += String.fromCharCode(0xC0 | ((c >>  6) & 0x1F));        
out += String.fromCharCode(0x80 | ((c >>  0) & 0x3F));    
}    
}    
return out; 
} 

function utf8to16(str) {    
var out, i, len, c;    
var char2, char3;    
out = "";    
len = str.length;    
i = 0;    
while(i < len) {    
c = str.charCodeAt(i++);    
switch(c >> 4){       
case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:            
out += str.charAt(i-1);        
break;      
case 12: case 13:              
char2 = str.charCodeAt(i++);        
out += String.fromCharCode(((c & 0x1F) << 6)|(char2 & 0x3F));        
break;      
case 14:               
char2 = str.charCodeAt(i++);        
char3 = str.charCodeAt(i++);        
out += String.fromCharCode(((c & 0x0F) << 12)|((char2 & 0x3F) << 6)|((char3 & 0x3F) << 0)); 
break;    
} 
}   
return out; 
} 
