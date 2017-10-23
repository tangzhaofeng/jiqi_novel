<script type="text/javascript" src="/modules/news/images/js/ajaxfileupload.js"></script>
<script>
var <?php echo $this->field;?>suburl = '/modules/news/attachment.php?module=<?php echo $this->formobj->model['tablename'];?>&catid=<?=$catid?>&uploadtext=<?php echo $this->field?>';
var <?php echo $this->field;?>enabledescription = <?php if($this->setting['enabledescription']) echo 1; else echo 0;?>;
</script>
<DIV class="ManagerForm" id="FormDiv">
<div class="subManager">

	<FIELDSET>
	<LEGEND style="BACKGROUND: url(/modules/news/images/tool.jpg) no-repeat 6px 50%; border-color:#FFFFFF; padding-left:33px;">视频管理</LEGEND>
			<table align="center" cellpadding="2" cellspacing="1" class="grid" width="98%" style="margin-bottom:5px;" id="<?php echo $this->field?>_imgTable">				
					<tr>
						<td colspan="2" style="text-align:left;">
						播放地址：<input type="text" name="<?php echo $this->field?>_playurl" id="<?php echo $this->field?>_playurl" size='55'> 
						<input type="radio" checked name="<?php echo $this->field?>_model" value="1" onClick="javascript:$('#<?php echo $this->field?>_one').show();$('#<?php echo $this->field?>_more').hide();">单条 
						<input type="radio" name="<?php echo $this->field?>_model" value="0" onClick="javascript:$('#<?php echo $this->field?>_one').hide();$('#<?php echo $this->field?>_more').show();">批量 
						<br />
						<span id='<?php echo $this->field?>_one'>
						视频说明：<input type="text" id="<?php echo $this->field?>_desc" style="width:36%" title="说明" value="1"/>&nbsp;
						<input type="button" value="设定" onclick="AddVideoData('<?php echo $this->field?>_',$('#<?php echo $this->field?>_desc').val(),$('#<?php echo $this->field?>_playurl').val())"></span>
						<span id='<?php echo $this->field?>_more' style="display:none;">
						开始集数：<input type="text" name="<?php echo $this->field?>_startvideo" id="<?php echo $this->field?>_startvideo" value="1" size='9'> 
						结束：<input type="text" name="<?php echo $this->field?>_endvideo" id="<?php echo $this->field?>_endvideo" value="1" size='9'> 
						视频格式：<input type="text" name="<?php echo $this->field?>_vext" id="<?php echo $this->field?>_vext" value=".rm" size='11'>&nbsp;
						<input type="button" value="设定" onclick="setvideo('<?php echo $this->field?>_')"></span>
					</td>
					</tr>
				</table>
				<table align="center" cellpadding="0" cellspacing="1" class="grid" width="98%" style="margin-bottom:5px;<?php if(!$rows){?>display:none;<?php }?>" id="<?php echo $this->field?>_Tablelist">
				<tr>
				<th width="7%"><input type="checkbox" onClick="javascript:if(this.checked==true) $('input[name*=<?php echo $this->field?>_delete]').attr('checked', true); else $('input[name*=<?php echo $this->field?>_delete]').attr('checked', false);">删除</th>
				<th width="7%">排序</th>
				<th width="22%">说明</th>
				<th width="*">文件</th>
				</tr>
				<?php
				if(count($rows)>0){ 
				     foreach($rows as $k=>$v){
				?>
					<tr id="<?php echo $this->field?>_list<?php echo $k;?>">
						<td style="text-align:center"><input type='checkbox' name='<?php echo $this->field?>_delete[<?php echo $k;?>]' value='<?php echo $k;?>' title='删除'></td>
						<td style="text-align:center">
						<input type='text' name='<?php echo $this->field?>_listorder[<?php echo $k;?>]' value='<?php echo $k+1;?>' size='5' title='排序' style="text-align:center">
						</td>
						<td><input type="text" name="<?php echo $this->field?>_description[<?php echo $k;?>]" value="<?php echo $v['description'];?>" title="修改文件说明" style="height:22px; width:90%">  </td>
						<td>
						<input type="text" name="<?php echo $this->field?>_fileurl[<?php echo $k;?>]" value="<?php echo $v['fileurl'];?>" style="height:22px; width:90%">
						</td>
					</tr>
				<?php
				    } 
				 }?>
				</table>
	</FIELDSET> 
	</div>
</DIV>
<script>
$(document).ready(function() {
 var <?php echo $this->field?>_hang = <?php echo $this->setting['fileformnum'];?>;
 $("#<?php echo $this->field?>_AddUpload").bind("click", function(){
  $("#<?php echo $this->field?>_imgTable").append('<tr id="<?php echo $this->field?>_row'+(<?php echo $this->field?>_hang)+'"><th width="7%">文件'+(<?php echo $this->field?>_hang+1)+'：</th><th style="text-align:left;"><input name="<?php echo $this->field?>_uploadImg'+<?php echo $this->field?>_hang+'" id="<?php echo $this->field?>_uploadImg'+<?php echo $this->field?>_hang+'" type="file" style="height:22px;"/>&nbsp;备注：<input type="text" id="<?php echo $this->field?>_description'+<?php echo $this->field?>_hang+'" style="height:22px; width:38%" title="备注" value=""/>&nbsp;<input type="button" onClick="DelTableRow(\'<?php echo $this->field?>_row'+<?php echo $this->field?>_hang+'\');" value="删除">&nbsp;<span id="<?php echo $this->field?>_uploadImgState'+<?php echo $this->field?>_hang+'"></span></th></tr>');
  $("input[type='button']").addClass('button_style');
  <?php echo $this->field?>_hang++;
 }); 
 $("#<?php echo $this->field?>_SubUpload").bind("click", function(){
     this.disabled="disabled";
     $("input[id*='<?php echo $this->field?>_uploadImg']").each(function(id){
	    var i=0;
	    if(this.value!=''){
			var ids = this.id;
			var tempa = ids.split('uploadImg');
			ajaxFileUpload(<?php echo $this->field?>suburl,tempa[0],tempa[1],<?php echo $this->field?>enabledescription);
			i++;
		}
	});
	this.disabled="";
 }); 
});

</script>