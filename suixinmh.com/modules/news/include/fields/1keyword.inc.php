 <input type="button" value="重新选择" onClick="javascript:$('#<?=$this->field?>').val('');"/> <br>
<select id='temp<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select><input type="hidden" id="<?=$this->field?>_num" value="0"> 
<span id="load_<?=$this->field?>"></span>
<br>
<select id='temp1<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select><input type="hidden" id="<?=$this->field?>_num1" value="0"> 
<span id="load1_<?=$this->field?>"></span>
<script language='JavaScript' type='text/JavaScript'>
$(
	function(){
	   $("#temp<?=$this->field?>_s_0").change(
			 function(){
			    $("#load_<?=$this->field?>").html('');
				if($("#temp<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List($("#temp<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num").val())
			 }
		);
	   $("#temp1<?=$this->field?>_s_0").change(
			 function(){
			    $("#load1_<?=$this->field?>").html('');
				if($("#temp1<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List($("#temp1<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num").val())
			 }
		);
	}
);
function get<?=$this->field?>List(parentid,nn){
    $("span[id*='temp<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	$('#<?=$this->field?>').val('');
    $("select[id*='temp<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else  $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num").val(parseInt($("#<?=$this->field?>_num").val())+1);
			var n = parseInt($("#<?=$this->field?>_num").val());
			var str = "<span id='temp<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List(this.value,"+n+");' id='temp<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List1(parentid,nn){
    $("span[id*='temp1<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	$('#<?=$this->field?>').val('');
    $("select[id*='temp1<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else  $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num1").val(parseInt($("#<?=$this->field?>_num1").val())+1);
			var n = parseInt($("#<?=$this->field?>_num1").val());
			var str = "<span id='temp1<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List(this.value,"+n+");' id='temp<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load1_<?=$this->field?>").append(str);
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