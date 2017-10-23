<table cellpadding="0" cellspacing="0" class="grid">
			<tr> 
			  <th>文本框长度</th>
			  <td><input type="text" name="setting[size]" value="<?=$size?>" size="10"></td>
			</tr>
			<tr> 
			  <th>默认值</th>
			  <td><input type="text" name="setting[defaultvalue]" value="<?=$defaultvalue?>" size="40"></td>
			</tr>
			<tr> 
			  <th>允许上传的图片大小</th>
			  <td><input type="text" name="setting[maxsize]" value="<?=$maxsize?>" size="5">KB 提示：1KB=1024Byte，1MB=1024KB *</td>
			</tr>
			<tr> 
			  <th>允许上传的图片扩展</th>
			  <td><input type="text" name="setting[fileextname]" value="<?=$fileextname?>" size="40"></td>
			</tr>
			<tr> 
			  <th>是否从已上传中选择</th>
			  <td><input type="radio" name="setting[isselectimage]" value="1"> 是 <input type="radio" name="setting[isselectimage]" value="0"> 否</td>
			</tr>
			<tr> 
			  <th>是否保存远程图片：</th>
			<td><input type="radio" name="setting[enablesaveimage]" value="1"> 是 <input type="radio" name="setting[enablesaveimage]" value="0"> 否 <p><span style="color:#ff0000">下载内容中的远程图片资源，自动保存到本地服务器</span></td>
			</tr>
			<tr> 
			  <th>是否启用缩略图：</th>
			  <td><select class="select" size="1" name="setting[thumb_enable]" id="thumb_enable">
<option value="-1">系统默认</option>
<option value="0">不启用缩略图</option>
<option value="1">启用缩略图</option>
</select> 缩略图大小 <input name="setting[thumb_width]" type="text" id="thumb_width" value="<?=$thumb_width?>" size="5" maxlength="5"> X <input name="setting[thumb_height]" type="text" id="thumb_height" value="<?=$thumb_height?>" size="5" maxlength="5"> px  <p><span style="color:#ff0000">对本字段中的图片上传是否开启缩略图功能[此设置将覆盖栏目及<a href="/admin/configs.php?mod=news#catorder3">参数设置</a>里的配置]</span></td>
			</tr>
			<tr> 
			  <th>是否启用水印：</th>
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
$("input[name='setting[isselectimage]']").val(["<?=$isselectimage?>"]);
$("select[name='setting[thumb_enable]']").val(["<?=$thumb_enable?>"]);
$("select[name='setting[attachwater]']").val(["<?=$attachwater?>"]);
$("input[name='setting[enablesaveimage]']").val(["<?=$enablesaveimage?>"]);
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val(100);<? } ?>
</script>