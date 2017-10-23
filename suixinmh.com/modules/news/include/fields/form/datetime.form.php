<table cellpadding="0" cellspacing="0" class="grid" bgcolor="#ffffff">
			<tr> 
			  <th>时间格式：</th>
			  <td>
			  <select name="setting[format]">
			  <option value="Y-m-d H:i:s"><?=date('Y-m-d H:i:s')?></option>
			  <option value="Y-m-d H:i"><?=date('Y-m-d H:i')?></option>
			  <option value="Y-m-d"><?=date('Y-m-d')?></option>
			  <option value="m-d"><?=date('m-d')?></option>
			  </select>
			  </td>
			</tr>
			<tr> 
			  <th>默认值：</th>
			  <td>
			  <input type="radio" name="setting[defaulttype]" value="0"/>无<br />
			  <input type="radio" name="setting[defaulttype]" value="1"/>当前时间<br />
			  <input type="radio" name="setting[defaulttype]" value="2"/>指定时间：<input type="text" name="setting[defaultvalue]" value="<?=$defaultvalue?>" size="22"></td>
			</tr>
		</table>
<script language="javascript">
$("select[name='setting[format]']").val(["<?=$format?>"]);
$("input[name='setting[defaulttype]']").val(["<?=$defaulttype?>"]);
</script>