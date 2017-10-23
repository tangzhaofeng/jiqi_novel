<table cellpadding="0" cellspacing="0" class="grid">
			<tr> 
			  <th>编辑器样式：</th>
			  <td><input type="radio" name="setting[toolbar]" value="basic"> 简洁型 <input type="radio" name="setting[toolbar]" value="standard"> 标准型 <input type="radio" name="setting[toolbar]" value="full"> 全功能</td>
			</tr>
			<tr> 
			  <th>编辑器大小：</th>
			  <td>宽 <input type="text" name="setting[width]" value="<?=$width?>" size="4"> px 高 <input type="text" name="setting[height]" value="<?=$height?>" size="4"> px</td>
			</tr>
			<tr> 
			  <th>默认值：</th>
			  <td><textarea name="setting[defaultvalue]" rows="2" cols="20" id="defaultvalue" style="height:100px;width:250px;"><?=$defaultvalue?></textarea></td>
			</tr>
			<tr> 
			  <th>分页按纽：</th>
			  <td><input type="radio" name="setting[openpagetag]" value="1"> 打开 <input type="radio" name="setting[openpagetag]" value="0"> 关闭</td>
			</tr>
			<tr> 
			  <th>数据存储方式：</th>
			  <td><input type="radio" name="setting[storage]" value="database" checked> 数据库存储<!-- <input type="radio" name="setting[storage]" value="file"> 文本存储--></td>
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
			<tr> 
			  <th>是否过滤它站超链接：</th>
			<td><input type="radio" name="setting[enablereplaceurls]" value="1"> 是 <input type="radio" name="setting[enablereplaceurls]" value="0"> 否 <p><span style="color:#ff0000">过滤内容中的非本站超链接</span></td>
			</tr>
			<tr> 
			  <th>是否保存远程图片：</th>
			<td><input type="radio" name="setting[enablesaveimage]" value="1"> 是 <input type="radio" name="setting[enablesaveimage]" value="0"> 否 <p><span style="color:#ff0000">下载内容中的远程图片资源，自动保存到本地服务器</span></td>
			</tr>
			<tr> 
			  <th>是否保存远程Flash：</th>
			<td><input type="radio" name="setting[enablesaveflash]" value="1"> 是 <input type="radio" name="setting[enablesaveflash]" value="0"> 否 <p><span style="color:#ff0000">下载内容中的Flash资源 ，自动保存到本地服务器</span></td>
			</tr>
			<tr>
			<th>保存其他远程文件：</th>
			<td>是否下载<input type="checkbox" name="setting[enablesavefile]" value="1">  文件后缀形式:<input type="text" name="setting[savefileext]" value="<?=$savefileext?>">
			  <span style="color:#ff0000">只适用于下载显示真实地址的文件，文件不宜过大</span></td>
		  </tr>
			<tr>
			<th>禁用的HTML标签：</th>
			<td><input type="text" name="setting[forbidwords]" value='<?=$forbidwords?>' size='60' maxlength='200'><br />
			  <span style="color:#ff0000">禁用内容字段中的部分HTML标签，防止网页变形。多个标签用"|"分隔。例如：script|div|iframe</span></td>
		  </tr>
		</table>
<script language="javascript">
$("input[name='setting[toolbar]']").val(["<?=$toolbar?>"]);
$("input[name='setting[openpagetag]']").val(["<?=$openpagetag?>"]);
$("select[name='setting[thumb_enable]']").val(["<?=$thumb_enable?>"]);
$("select[name='setting[attachwater]']").val(["<?=$attachwater?>"]);
$("input[name='setting[enablereplaceurls]']").val(["<?=$enablereplaceurls?>"]);
$("input[name='setting[enablesaveimage]']").val(["<?=$enablesaveimage?>"]);
$("input[name='setting[enablesaveflash]']").val(["<?=$enablesaveflash?>"]);
<?php if($enablesavefile>0){?>$("input[name='setting[enablesavefile]']").attr('checked','checked');<? } ?>
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val('');<? } ?>
</script>