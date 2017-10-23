try {
	if(document.documentElement.addEventListener) {
		document.documentElement.addEventListener('keydown', parent.resetf5, false);
	} else if(document.documentElement.attachEvent) {
		document.documentElement.attachEvent("onkeydown", parent.resetf5);
	}
}catch(e){}

var tID=0;
function ShowTabs(ID)
{
	var tTabTitle=document.getElementById("TabTitle"+tID);
	var tTabs=document.getElementById("Tabs"+tID);
	var TabTitle=document.getElementById("TabTitle"+ID);
	var Tabs=document.getElementById("Tabs"+ID);
	if(ID!=tID)
	{
		tTabTitle.className='';
		TabTitle.className='selected';
		tTabs.style.display='none';
		Tabs.style.display='';
		tID=ID;
	}
}

function ChangeInput (objSelect,objInput,s)
{
	if (!objInput) return;
	var str = objInput.value;
	var arr = str.split(",");
	for (var i=0; i<arr.length; i++){
	  if(objSelect.value==arr[i])return;
	}
	if(objInput.value=='' || objInput.value==0 || objSelect.value==0){
	   objInput.value=objSelect.value
	}else{
	   objInput.value+=s+objSelect.value
	}
}

function file_select(textid, filetype, url)
{
	var arr=Dialog(url+'?ac=selectfile&filetype='+filetype, '', 700, 500);
	if(arr!=null)
	{
		var s=arr.split('|');
		$('#'+textid).val(s[0]);
		//try {$(textid+'size').value=s[1];}catch(e){};
	}
}
function AddMorePic(textid)
{
	var arr=Dialog('#', '', 700, 500);
	if(arr!=null)
	{
		var select = '';
		$.get('?mod=phpcms&file=more_pic_select&action=getdata&currentdir='+arr, function(data){
		    if(data !=null)
			{
				var arr_var=data.split('|');
				$.each(arr_var, function(n){
					var val = arr_var[n];
					select += "<input type='hidden' name='"+textid+"[]' value='"+val+"'><div id='file_uploaded_1'><span style='width:30px'><input type='checkbox' name='"+textid+"_delete[]' value='"+val+"' title='删除'></span><span style='width:60px'><input type='text' name='"+textid+"_description[]' value='' size='20' title='图片说明'></span><a href='###' onMouseOut='javascript:FilePreview(\""+val+"\", 0);' onMouseOver='javascript:FilePreview(\""+val+"\", 1);'>"+val+"</a></div>";
				});
				$('#'+textid).html(select);
			}
		});
	}
}
function DelTableRow(id){
	$('#'+id).remove(); 
}
function ajaxFileUpload(url,pre,id,enabledescription)
{
		var f = pre.split('_');
		$("#"+pre+"uploadImgState"+id).html('<img id="'+pre+'loading'+id+'" src="/modules/news/images/loading.gif">');
/*		$("#"+pre+"loading"+id)
		.ajaxStart(function(){
			$(this).html('<img id="'+pre+'loading'+id+'" src="/modules/news/images/loading.gif">');
		})
		.ajaxComplete(function(){
			$(this).html('');
		});*/
		$.ajaxFileUpload
		(
			{
				url:url+'&file='+pre+'uploadImg'+id+'&file_description='+$('#'+pre+'description'+id).val()+'&action=upload&dosubmit=1&from=uploadimages',/*&filetype=image*/
				secureuri:false,
				fileElementId:pre+'uploadImg'+id,
				dataType: 'json',
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							$('#'+pre+'uploadImgState'+id).html('<font color=red>'+data.error+'</font>');
						}else{
							var i = $("tr[id*='"+pre+"list']").length;
							$('#'+pre+'uploadImgState'+id).html(data.msg);
							$('#'+pre+'Tablelist').show();
							var description = $('#'+pre+'description'+id).val();
							if(description=='' && enabledescription) description = data.filename;
						    $('#'+pre+'Tablelist').append('<tr id="'+pre+'list'+id+'"><td style="text-align:center"><input type=\'checkbox\' name=\''+pre+'delete['+i+']\' value=\''+i+'\' title=\'删除\'></td><td style="text-align:center"><input type=\'text\' name=\''+pre+'listorder['+i+']\' value=\''+(i+1)+'\' size=\'5\' title=\'排序\' style="text-align:center"></td><td><input type="text" name="'+pre+'description['+i+']" title="修改文件说明" style="height:22px; width:90%" value="'+description+'"></td><td><a href=\'#\' onMouseOut=\'javascript:FilePreview("'+data.fileurl+'", 0);\' onMouseOver=\'javascript:FilePreview("'+data.fileurl+'", 1);\'>'+data.filename+'</a><input type="hidden" name="'+pre+'fileurl['+i+']" value="'+data.fileurl+'"></td></tr>');
							$('#'+f[0]).val($('#'+f[0]).val()+description+'|'+data.fileurl+"[page]\n");
							$('#'+pre+'description'+id).val('');
						}
					}
					//alert($('#'+f[0]).val());
				},
				error: function (data, status, e)
				{
					$('#'+pre+'uploadImgState'+id).html(e);
				}
			}
		)
		
	return true;

}

function AddVideoData(pre,description,fileurl){
	var f = pre.split('_');
	var i = $("tr[id*='"+pre+"list']").length;
	//var description = $('#'+pre+'desc').val();
	//var fileurl = $('#'+pre+'playurl').val();
	$('#'+pre+'Tablelist').show();
	$('#'+pre+'Tablelist').append('<tr id="'+pre+'list'+i+'"><td style="text-align:center"><input type=\'checkbox\' name=\''+pre+'delete['+i+']\' value=\''+i+'\' title=\'删除\'></td><td style="text-align:center"><input type=\'text\' name=\''+pre+'listorder['+i+']\' value=\''+(i+1)+'\' size=\'5\' title=\'排序\' style="text-align:center"></td><td><input type="text" name="'+pre+'description['+i+']" title="修改文件说明" style="height:22px; width:90%" value="'+description+'"></td><td><input type="text" name="'+pre+'fileurl['+i+']" value="'+fileurl+'" style="height:22px; width:90%"></td></tr>');
	$('#'+f[0]).val($('#'+f[0]).val()+description+'|'+fileurl+"[page]\n");
}

function setvideo(pre)
{
	var i = 0;
	var data = '';
	var startnum = parseInt($('#'+pre+'startvideo').val());
	var endnum = parseInt($('#'+pre+'endvideo').val());
	var videourl = $('#'+pre+'playurl').val();
	var videoext = $('#'+pre+'vext').val();
	for(i=startnum; i<=endnum; i++)
	{
		AddVideoData(pre,i,videourl + i +videoext);
	}
}

function ShowVarsLabel(objname)
{
	var obj = document.getElementById(objname);
	var objimg = document.getElementById(objname+"img");
	//var objtrimhtml = document.getElementById(objname+"trimhtml");
	if(obj.style.display=="none")
	{
		obj.style.display = "block";
		objimg.src="/modules/news/images/open.gif";
	}
	else
	{
		obj.style.display="none";
		objimg.src="/modules/news/images/close.gif";
	}
}