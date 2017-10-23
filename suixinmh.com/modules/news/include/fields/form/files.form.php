<table cellpadding="0" cellspacing="0" class="grid">
				<tr> 
				  <th>文本域行数</th>
				  <td><input type="text" name="setting[rows]" value="<?=$rows?>" size="10"></td>
				</tr>
				<tr> 
				  <th>文本域列数</th>
				  <td><input type="text" name="setting[cols]" value="<?=$cols?>" size="10"></td>
				</tr>
				<tr> 
				  <th>默认值</th>
				  <td><textarea name="setting[defaultvalue]" rows="2" cols="20" id="defaultvalue" style="height:60px;width:250px;"><?=$defaultvalue?></textarea></td>
				</tr>
				<tr> 
				  <th>是否允许Html</th>
				  <td><input type="radio" name="setting[enablehtml]" value="1"> 是 <input type="radio" name="setting[enablehtml]" value="0"> 否</td>
				</tr>
<tr> 
			  <th>允许上传的文件大小</th>
			  <td><input type="text" name="setting[maxsize]" value="<?=$maxsize?>" size="5">KB 提示：1KB=1024Byte，1MB=1024KB *</td>
			</tr>
			<tr> 
			  <th>允许上传的文件扩展</th>
			  <td><input type="text" name="setting[fileextname]" value="<?=$fileextname?>" size="40"></td>
			</tr>
			<tr> 
			  <th>图片文件是否启用缩略图：</th>
			  <td><select class="select" size="1" name="setting[thumb_enable]" id="thumb_enable">
<option value="-1">系统默认</option>
<option value="0">不启用缩略图</option>
<option value="1">启用缩略图</option>
</select> 缩略图大小 <input name="setting[thumb_width]" type="text" id="thumb_width" value="<?=$thumb_width?>" size="5" maxlength="5"> X <input name="setting[thumb_height]" type="text" id="thumb_height" value="<?=$thumb_height?>" size="5" maxlength="5"> px  <p><span style="color:#ff0000">对本字段中的图片上传是否开启缩略图功能[此设置将覆盖栏目及<a href="/admin/configs.php?mod=news#catorder3">参数设置</a>里的配置]</span></td>
			</tr>
			<tr> 
			  <th>图片文件是否启用印：</th>
			  <td><select class="select" size="1" name="setting[attachwater]" id="attachwater">
<option value="-1">系统默认</option>
<option value="0">不加水印</option>
<option value="1">顶部居左</option>
<option value="2">顶部居中</option>
<option value="3">顶部居右</option>
<option value="4">中部居左</option>
<option value="5">中部居中</option>
<option value="6">中部居右</option>
<option value="7">底部居左</option>
<option value="8">底部居中</option>
<option value="9">底部居右</option>
<option value="10">随机位置</option>
</select> 水印图片文件 <input type="text" name="setting[attachwimage]" value='<?=$attachwimage?>' size='30' maxlength='100'> <p><span style="color:#ff0000">允许 JPG/PNG/GIF 格式，默认只需填文件名，放在 /modules/news/images 目录下</span></td>
			</tr>
		</table>
<script language="javascript">
$("input[name='setting[enablehtml]']").val(["<?=$enablehtml?>"]);
$("select[name='setting[thumb_enable]']").val(["<?=$thumb_enable?>"]);
$("select[name='setting[attachwater]']").val(["<?=$attachwater?>"]);
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val('');<? } ?>
</script>