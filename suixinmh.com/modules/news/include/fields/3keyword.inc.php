 <input type="button" value="重新选择" onClick="javascript:$('#<?=$this->field?>').val('');"/> 
<br>
游戏特征：<select id='temp9<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load9_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num9" value="0"> 
<br>
游戏分类：<select id='temp8<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load8_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num8" value="0"> 
 <br>
游戏国籍：<select id='temp<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num" value="0"> 
<br>
游戏厂商：<select id='temp1<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load1_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num1" value="0"> 
<br>
游戏难度：<select id='temp2<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load2_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num2" value="0"> 
<br>
游戏安装：<select id='temp3<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load3_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num3" value="0"> 
<br>
游戏年龄：<select id='temp4<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load4_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num4" value="0"> 
<br>
游戏语言：<select id='temp5<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load5_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num5" value="0"> 
<br>
游戏大小：<select id='temp6<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load6_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num6" value="0"> 
<br>
游戏类型：<select id='temp7<?=$this->field?>_s_0' style='width:85px'>
<option>常用关键词</option>
<?php foreach($_PAGE['rows'] as $k=>$v){?>
<option value='<?php echo $v['id']?>'><?php echo $v['tag']?></option>
<?php }?>
</select>
<span id="load7_<?=$this->field?>"></span>
<input type="hidden" id="<?=$this->field?>_num7" value="0"> 

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
				if($("#temp1<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List1($("#temp1<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num1").val())
			 }
		);
	   $("#temp2<?=$this->field?>_s_0").change(
			 function(){
			    $("#load2_<?=$this->field?>").html('');
				if($("#temp2<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List2($("#temp2<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num2").val())
			 }
		);
	   $("#temp3<?=$this->field?>_s_0").change(
			 function(){
			    $("#load3_<?=$this->field?>").html('');
				if($("#temp3<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List3($("#temp3<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num3").val())
			 }
		);
	   $("#temp4<?=$this->field?>_s_0").change(
			 function(){
			    $("#load4_<?=$this->field?>").html('');
				if($("#temp4<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List4($("#temp4<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num4").val())
			 }
		);
	   $("#temp5<?=$this->field?>_s_0").change(
			 function(){
			    $("#load5_<?=$this->field?>").html('');
				if($("#temp5<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List5($("#temp5<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num5").val())
			 }
		);
	   $("#temp6<?=$this->field?>_s_0").change(
			 function(){
			    $("#load6_<?=$this->field?>").html('');
				if($("#temp6<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List6($("#temp6<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num6").val())
			 }
		);
	   $("#temp7<?=$this->field?>_s_0").change(
			 function(){
			    $("#load7_<?=$this->field?>").html('');
				if($("#temp7<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List7($("#temp7<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num7").val())
			 }
		);
	   $("#temp8<?=$this->field?>_s_0").change(
			 function(){
			    $("#load8_<?=$this->field?>").html('');
				if($("#temp8<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List8($("#temp8<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num8").val())
			 }
		);
	   $("#temp9<?=$this->field?>_s_0").change(
			 function(){
			    $("#load9_<?=$this->field?>").html('');
				if($("#temp9<?=$this->field?>_s_0").val()>0) get<?=$this->field?>List9($("#temp9<?=$this->field?>_s_0").val(),$("#<?=$this->field?>_num9").val())
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
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
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
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp1<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num1").val(parseInt($("#<?=$this->field?>_num1").val())+1);
			var n = parseInt($("#<?=$this->field?>_num1").val());
			var str = "<span id='temp1<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List1(this.value,"+n+");' id='temp1<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load1_<?=$this->field?>").append(str);
		}
	});
}

function get<?=$this->field?>List2(parentid,nn){
    $("span[id*='temp2<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp2<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num2").val(parseInt($("#<?=$this->field?>_num2").val())+1);
			var n = parseInt($("#<?=$this->field?>_num2").val());
			var str = "<span id='temp2<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List2(this.value,"+n+");' id='temp2<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load2_<?=$this->field?>").append(str);
		}
	});
	
}
function get<?=$this->field?>List3(parentid,nn){
    $("span[id*='temp3<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp3<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num3").val(parseInt($("#<?=$this->field?>_num3").val())+1);
			var n = parseInt($("#<?=$this->field?>_num3").val());
			var str = "<span id='temp3<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List3(this.value,"+n+");' id='temp3<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load3_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List4(parentid,nn){
    $("span[id*='temp4<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp4<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num4").val(parseInt($("#<?=$this->field?>_num4").val())+1);
			var n = parseInt($("#<?=$this->field?>_num4").val());
			var str = "<span id='temp4<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List4(this.value,"+n+");' id='temp4<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load4_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List5(parentid,nn){
    $("span[id*='temp5<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp5<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num5").val(parseInt($("#<?=$this->field?>_num5").val())+1);
			var n = parseInt($("#<?=$this->field?>_num5").val());
			var str = "<span id='temp5<?=$this->field?>_"+n+"'><select style='style='width:85px'' onChange='javascript:get<?=$this->field?>List5(this.value,"+n+");' id='temp5<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load5_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List6(parentid,nn){
    $("span[id*='temp6<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp6<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num6").val(parseInt($("#<?=$this->field?>_num6").val())+1);
			var n = parseInt($("#<?=$this->field?>_num6").val());
			var str = "<span id='temp6<?=$this->field?>_"+n+"'><select style='style='width:86px'' onChange='javascript:get<?=$this->field?>List6(this.value,"+n+");' id='temp6<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load6_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List7(parentid,nn){
    $("span[id*='temp7<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp7<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num7").val(parseInt($("#<?=$this->field?>_num7").val())+1);
			var n = parseInt($("#<?=$this->field?>_num7").val());
			var str = "<span id='temp7<?=$this->field?>_"+n+"'><select style='style='width:87px'' onChange='javascript:get<?=$this->field?>List7(this.value,"+n+");' id='temp7<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load7_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List8(parentid,nn){
    $("span[id*='temp8<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp8<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num8").val(parseInt($("#<?=$this->field?>_num8").val())+1);
			var n = parseInt($("#<?=$this->field?>_num8").val());
			var str = "<span id='temp8<?=$this->field?>_"+n+"'><select style='style='width:88px'' onChange='javascript:get<?=$this->field?>List8(this.value,"+n+");' id='temp8<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load8_<?=$this->field?>").append(str);
		}
	});
}
function get<?=$this->field?>List9(parentid,nn){
    $("span[id*='temp9<?=$this->field?>_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(t[1]>nn) this.innerHTML = '';
	});
	//$('#<?=$this->field?>').val('');
    $("select[id*='temp9<?=$this->field?>_s_']").each(function(id){
		var ids = this.id;
		var t = ids.split('_');
		if(this.value<1) return false;
		if($('#<?=$this->field?>').val()=='') $('#<?=$this->field?>').val(this[this.selectedIndex].innerText);
		else{
		    var keyword = $('#<?=$this->field?>').val();
			var keywords = keyword.split(' ');
			var statu = true;
			for(var i=0;i<=keywords.length;i++){
			    if(keywords[i]==this[this.selectedIndex].innerText || keywords[i]==''){
				    statu = false;
					continue;
				}
			}
			if(statu) $('#<?=$this->field?>').val($('#<?=$this->field?>').val()+' '+this[this.selectedIndex].innerText);
		}
	});
	if(parentid<1) return false;
	$.getJSON('<?php echo $attachurl;?>/modules/news/load.php?field=keyword&parentid='+parentid, function(data){
		var len = getJson<?=$this->field?>Length(data)-2;
		if(len>0){
		    $("#<?=$this->field?>_num9").val(parseInt($("#<?=$this->field?>_num9").val())+1);
			var n = parseInt($("#<?=$this->field?>_num9").val());
			var str = "<span id='temp9<?=$this->field?>_"+n+"'><select style='style='width:99px'' onChange='javascript:get<?=$this->field?>List9(this.value,"+n+");' id='temp9<?=$this->field?>_s_"+n+"'>";
			str+="<option value='0' selected>(选择关键词)</option>";
			for( var i=0; i<len;i++){
				str+="<option value="+data[i].id+">"+data[i].tag+"</option>";
			}
			str+="</select></span>";//alert(str);
			$("#load9_<?=$this->field?>").append(str);
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
