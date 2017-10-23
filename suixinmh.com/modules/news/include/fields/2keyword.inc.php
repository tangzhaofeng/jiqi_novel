 <input type="button" value="重新选择" onClick="javascript:$('#<?=$this->field?>').val('');"/> <br>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<?php echo $v['tag']?>：<span id="load_<?=$this->field?>_<?=$k?>"><a href="javascript:getkeywordsData('load_<?=$this->field?>_<?=$k?>',<?php echo $v['id']?>,1);">获取数据</a></span><br>
<?php }?>
<input type="hidden" id="<?=$this->field?>_num" value="0"> 
<input type="hidden" id="<?=$this->field?>_current" value="0"> 
<span id="load_<?=$this->field?>"></span>
<script language='JavaScript' type='text/JavaScript'>
function get<?=$this->field?>Data(tagget,v,n){
	$("#"+tagget).html('');
	$("#<?=$this->field?>_current").val(tagget);
	get<?=$this->field?>List(v,n,tagget)
}
function get<?=$this->field?>List(parentid,nn,target){
    $("span[id*='temp<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//var target = $("#<?=$this->field?>_current").val();
	$('#<?=$this->field?>').val('');
    $("select[id*='temp<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else  $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
	});
	if(parentid<1) return false;
	alert(target);
	setSData(parentid,target);
}
function setSData(parentid,target){
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num").val(parseInt($("#<?=$this->field?>_num").val())+1);
			var n = parseInt($("#<?=$this->field?>_num").val());
			var str = "<span id='temp<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List(this.value,"+n+",target);' id='temp<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";
			$("#"+target).append(str);
		}
	});
}
function getJson<?=$this->field?>Length(jsonData){
   var jsonLength = 0;
   for(var item in jsonData) 
      jsonLength++;
   return jsonLength;
}

</script>
<!-- onchange="if($('#<?=$this->field?>').val()==''){ $('#<?=$this->field?>').val(this.value);}else if($('#<?=$this->field?>').val().indexOf(this.value)==-1){ $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this.value);}"-->