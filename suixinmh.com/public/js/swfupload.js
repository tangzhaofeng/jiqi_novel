
function clean_thumb(inputid){
		$('#'+inputid+'_pic').attr('src',PUBLIC+'/Images/admin_upload_thumb.png');
		var aid = $('#'+inputid).val();

		$('#'+inputid).val('');
		$('#'+inputid+'_aid_box').html('');
}

function swfupload(id,inputid,title,yesdo,nodo,url){ 
	$.ajax({
		type:'POST',
		url:url+"?ajax=1",
		data:'',
		dataType:"json",
		async:false,
		success:function(msg){		
			if(msg.status == 1){
				url = url;	
				art.dialog.open(url, {
				id: id,
				title: title,
				lock: 'true',
				window: 'top',
				width: 610,
				height: 545,
				ok: function(){
					var iframeWin = this.iframe.contentWindow;
					var topWin = art.dialog.top;
					yesdo.call(this,iframeWin, topWin,url,inputid); 
					},
				cancel: true
				});
				
				
			}else{
				alert(msg.info);
			}
		 }
	});								




		
}

function yesdo(iframeWin, topWin,url,inputid){

	var content = iframeWin.$('#applytext').attr("value");
 	if(content == '') alert('申请内容不能为空');
	$.ajax({
		type:'POST',
		url:url+"?action=applywriter&applytext="+content,
		data:'',
		dataType:"json",
		async:false,
		success:function(msg){		
			if(msg.status == 1){
				alert('信息提交成功');
			}else{
				alert('信息保存错误 请联系管理员');
			}
		 }
	});			
}

function up_image(iframeWin, topWin,id,inputid){ 
		var num = iframeWin.$('#myuploadform > div').length;
		if(num){
			var aids = iframeWin.$('#myuploadform #aids').attr("value");
			var status = iframeWin.$('#myuploadform #status').attr("value");
			var filedata = iframeWin.$('#myuploadform #filedata').attr("value");
			var namedata = iframeWin.$('#myuploadform #filedata').attr("value");

			if(filedata){
				$('#'+inputid+'_pic').attr('src',filedata);
				$('#'+inputid).val(filedata);
				if(status==0) $('#'+inputid+'_aid_box').html('<input type="hidden"  name="aid[]" value="'+aids+'"  />');				
			}
		}
}

function up_images(iframeWin, topWin,id,inputid){ 
		var data = '';
		var aidinput='';
		var num = iframeWin.$('#myuploadform > div').length;
		if(num){
			iframeWin.$('#myuploadform  div ').each(function(){
					var status =  $(this).find('#status').val();
					var aid = $(this).find('#aids').val();
					var src = $(this).find('#filedata').val();
					var name = $(this).find('#namedata').val();
					if(status==0) aidinput = '<input type="hidden" name="aid[]" value="'+aid+'"/>';
					data += '<div id="uplist_'+aid+'">'+aidinput+'<input type="text" size="50" class="input-text" name="'+inputid+'[]" value="'+src+'"  />  <input type="text" class="input-text" name="'+inputid+'_name[]" value="'+name+'" size="30" /> &nbsp;<a href="javascript:remove_this(\'uplist_'+aid+'\');">移除</a> </div>';
			});			
			$('#'+inputid+'_images').append(data);
		}
}

function insert2editor(iframeWin, topWin,id,inputid){
		var img = '';
		var data = '';
		var num = iframeWin.$('#myuploadform > div').length;
		if(num){
				iframeWin.$('#myuploadform   div').each(function(){
					var status =  $(this).find('#status').val();
					var aid = $(this).find('#aids').val();
					var src = $(this).find('#filedata').val();
					var name = $(this).find('#namedata').val();
					if(status==0) data += '<input type="text" name="aid[]" value="'+aid+'"/>';
					img += IsImg(src) ?  '<img src="'+src+'" /><br />' :  (IsSwf(src) ? '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="quality" value="high" /><param name="movie" value="'+src+'" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'+src+'" type="application/x-shockwave-flash" width="460"></embed></object>' :'<a href="'+src+'" />'+src+'</a><br />') ;
			   });

			   $('#'+inputid+'_aid_box').append(data);
		}
		CKEDITOR.instances[inputid].insertHtml(img);
}


function upokis(arrMsg){
	//$('#'+arrMsg[0].editorid+'_aid_box').show();
	var i,msg;
	for(i=0;i<arrMsg.length;i++)
	{
		msg=arrMsg[i];
		if(msg.id>0)$('#'+msg.editorid+'_aid_box').append('<input type="text" name="aid[]" value="'+msg.id+'"/>');
		//$("#uploadList").append('<option value="'+msg.id+'">'+msg.localname+'</option>');
	}

}

function upok(id,data){
	alert(id);
		$('#'+id+'_aid_box').append('ddddddddddddddddd');
		$('#'+id+'_aid_box').show();
}
function nodo(iframeWin, topWin){
	art.dialog.close();
}


function IsImg(url){
	  var sTemp;
	  var b=false;
	  var opt="jpg|gif|png|bmp|jpeg";
	  var s=opt.toUpperCase().split("|");
	  for (var i=0;i<s.length ;i++ ){
		sTemp=url.substr(url.length-s[i].length-1);
		sTemp=sTemp.toUpperCase();
		s[i]="."+s[i];
		if (s[i]==sTemp){
		  b=true;
		  break;
		}
	  }
	  return b;
}

function IsSwf(url){
	  var sTemp;
	  var b=false;
	  var opt="swf";
	  var s=opt.toUpperCase().split("|");
	  for (var i=0;i<s.length ;i++ ){
	    sTemp=url.substr(url.length-s[i].length-1);
	    sTemp=sTemp.toUpperCase();
	    s[i]="."+s[i];
	    if (s[i]==sTemp){
	      b=true;
	      break;
	    }
	  }
	  return b;
}


