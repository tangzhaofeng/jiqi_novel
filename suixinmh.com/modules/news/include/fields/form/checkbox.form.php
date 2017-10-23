<table cellpadding="0" cellspacing="0" class="grid"<? if($page_action=='add'){?> onclick="javascript:$('#minlength').val(0);$('#maxlength').val(255);"<? } ?>>
<tr> 
			  <th>选项列表</th>
			  <td><textarea name="setting[items]" rows="2" cols="20" id="items" style="height:100px;width:200px;"><?=$items?></textarea></td>
			</tr>
			<tr> 
			  <th>默认值</th>
			  <td><input type="text" name="setting[defaultvalue]" size="40" value="<?=$defaultvalue?>"></td>
			</tr>
			<tbody id="setcols" style="display:block">
				<tr> 
				  <th>字段类型</th>
				  <td>
				  <select name="setting[fieldtype]">
				  <option value="CHAR">定长字符 CHAR</option>
				  <option value="VARCHAR">变长字符 VARCHAR</option>
				  </select>
				  </td>
				</tr>
			</tbody>
		</table>
<script language="javascript">
$("select[name='setting[fieldtype]']").val(["<?=$fieldtype?>"]);
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val(255);<? } ?>
</script>