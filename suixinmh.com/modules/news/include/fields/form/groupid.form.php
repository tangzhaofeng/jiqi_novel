<table cellpadding="0" cellspacing="0" class="grid">
			<tr> 
			  <th>会员组权限标识：</th>
			  <td><input type="text" name="setting[rolepriv]" value="<?=$rolepriv?>" size="15" readonly> </td>
			</tr>
			<tr> 
			  <th>全选<input boxid='defaultvalue' type='checkbox' onclick="checkall('defaultvalue')" ><br><strong>默认值：</strong></th>
			  <td><?=$group?></td>
			</tr>
		   </table>
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