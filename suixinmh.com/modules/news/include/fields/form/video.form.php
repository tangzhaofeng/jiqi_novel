<table cellpadding="0" cellspacing="0" class="grid">
				<tr> 
				  <th>是否允许Html</th>
				<td><input type="radio" name="setting[enablehtml]" value="1"> 是 <input type="radio" name="setting[enablehtml]" value="0"> 否</td>
				</tr>
			<tr> 
			  <th>是否保存远程视频文件：</th>
			<td><input type="radio" name="setting[enablesaveimage]" value="1"> 是 <input type="radio" name="setting[enablesaveimage]" value="0"> 否 <span style="color:#ff0000">下载远程视频资源，自动保存到本地服务器!大文件就选择否</span></td>
			</tr>
			<tr> 
			  <th>默认显示多少个上传位：</th>
			  <td><select class="select" size="1" name="setting[fileformnum]" id="fileformnum">
<option value="1">1个</option>
<option value="2">2个</option>
<option value="3">3个</option>
<option value="4">4个</option>
<option value="5">5个</option>
<option value="6">6个</option>
<option value="7">7个</option>
<option value="8">8个</option>
<option value="9">9个</option>
<option value="10">10个</option>
</select></td>
			</tr>
			<tr> 
			  <th>允许上传的视频大小</th>
			  <td><input type="text" name="setting[maxsize]" value="<?=$maxsize?>" size="7"> KB 提示：1KB=1024Byte，1MB=1024KB *<br />服务器允许的最大上传附件为<font color="red"><?=ini_get('upload_max_filesize')?></font>，你所设的值需小于或等于<font color="red"><?=ini_get('upload_max_filesize')?></font></td>
			</tr>
			<tr> 
			  <th>允许上传视频类型</th>
			  <td><input type="text" name="setting[fileextname]" value="<?=$fileextname?>" size="40"></td>
			</tr>
		<tr>
		  <th>视频服务器</th>
		  <td><textarea name="setting[servers]" rows="3" cols="60"  style="height:80px;width:400px;"><?=$servers?></textarea></td>
		</tr>
				<tr> 
				  <th>上传视频时自动补充备注</th>
				<td><input type="radio" name="setting[enabledescription]" value="1"> 是 <input type="radio" name="setting[enabledescription]" value="0"> 否</td>
				</tr>
		</table>
<script language="javascript">
$("input[name='setting[enablehtml]']").val(["<?=$enablehtml?>"]);
$("select[name='setting[fileformnum]']").val(["<?=$fileformnum?>"]);
$("input[name='setting[enabledescription]']").val(["<?=$enabledescription?>"]);
$("input[name='setting[enablesaveimage]']").val(["<?=$enablesaveimage?>"]);
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val('');<? } ?>
</script>