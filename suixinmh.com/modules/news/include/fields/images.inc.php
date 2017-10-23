<script type="text/javascript" src="/modules/news/images/js/ajaxfileupload.js"></script>
<script>
var <?php echo $this->field;?>suburl = '/modules/news/attachment.php?module=<?php echo $this->formobj->model['tablename'];?>&catid=<?=$catid?>&uploadtext=<?php echo $this->field?>';
var <?php echo $this->field;?>enabledescription = <?php if($this->setting['enabledescription']) echo 1; else echo 0;?>;
</script>
<DIV class="ManagerForm" id="FormDiv">
<div class="subManager">

	<FIELDSET>
	<LEGEND style="BACKGROUND: url(/modules/news/images/tool.jpg) no-repeat 6px 50%; border-color:#FFFFFF; padding-left:33px;">上传图片</LEGEND>
				<table align="center" cellpadding="0" cellspacing="1" class="grid" width="98%" style="margin-bottom:5px;<?php if(!$rows){?>display:none;<?php }?>" id="<?php echo $this->field?>_Tablelist">
				<tr>
				<th width="7%"><input type="checkbox" onClick="javascript:if(this.checked==true) $('input[name*=<?php echo $this->field?>_delete]').attr('checked', true); else $('input[name*=<?php echo $this->field?>_delete]').attr('checked', false);">删除</th>
				<th width="7%">排序</th>
				<th width="*">说明</th>
				<th width="22%">文件</th>
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
						<td><input type="text" name="<?php echo $this->field?>_description[<?php echo $k;?>]" value="<?php echo $v['description'];?>" title="修改图片说明" style="height:22px; width:90%">  </td>
						<td><a href='###' onMouseOut='javascript:FilePreview("<?php echo $v['fileurl'];?>", 0);' onMouseOver='javascript:FilePreview("<?php echo $v['fileurl'];?>", 1);'><?php echo basename($v['fileurl']);?></a>
						<input type="hidden" name="<?php echo $this->field?>_fileurl[<?php echo $k;?>]" value="<?php echo $v['fileurl'];?>">
						</td>
					</tr>
				<?php
				    } 
				 }?>
				</table>
			<table align="center" cellpadding="2" cellspacing="1" class="grid" width="98%" style="margin-bottom:5px;" id="<?php echo $this->field?>_imgTable">				
					<tr>
						<td colspan="2" style="text-align:left;">
							<input type="button" id="<?php echo $this->field?>_SubUpload" value="确定上传">&nbsp;
							<input type="button" id="<?php echo $this->field?>_AddUpload" value="增加">
					</td>
					</tr>
					<?php
					for($i=0;$i<=$this->setting['fileformnum']-1;$i++){
					?>
					<tr id="<?php echo $this->field?>_row<?php echo $i?>">
						<th width="7%">图片<?php echo $i+1?>：</th>
						<th style="text-align:left;">
<input id="<?php echo $this->field?>_uploadImg<?php echo $i?>" name="<?php echo $this->field?>_uploadImg<?php echo $i?>" type="file" style="height:22px;"/>&nbsp;备注：<input type="text" id="<?php echo $this->field?>_description<?php echo $i?>" style="height:22px; width:38%" title="备注" value=""/>&nbsp;<input type="button" onClick="DelTableRow('<?php echo $this->field?>_row<?php echo $i?>');" value="删除">&nbsp;<span id="<?php echo $this->field?>_uploadImgState<?php echo $i?>"></span>
						</th>
					</tr>
					<?php
						}
					?>
				</table>

	</FIELDSET> 
	</div>
</DIV>
<script>
$(document).ready(function() {
 var <?php echo $this->field?>_hang = <?php echo $this->setting['fileformnum'];?>;
 $("#<?php echo $this->field?>_AddUpload").bind("click", function(){
  $("#<?php echo $this->field?>_imgTable").append('<tr id="<?php echo $this->field?>_row'+(<?php echo $this->field?>_hang)+'"><th width="7%">图片'+(<?php echo $this->field?>_hang+1)+'：</th><th style="text-align:left;"><input name="<?php echo $this->field?>_uploadImg'+<?php echo $this->field?>_hang+'" id="<?php echo $this->field?>_uploadImg'+<?php echo $this->field?>_hang+'" type="file" style="height:22px;"/>&nbsp;备注：<input type="text" id="<?php echo $this->field?>_description'+<?php echo $this->field?>_hang+'" style="height:22px; width:38%" title="备注" value=""/>&nbsp;<input type="button" onClick="DelTableRow(\'<?php echo $this->field?>_row'+<?php echo $this->field?>_hang+'\');" value="删除">&nbsp;<span id="<?php echo $this->field?>_uploadImgState'+<?php echo $this->field?>_hang+'"></span></th></tr>');
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