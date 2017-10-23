<table cellpadding="0" cellspacing="0" class="grid">
			<tr> 
			  <th>选项列表</th>
			  <td><textarea name="setting[items]" rows="2" cols="20" id="items" style="height:100px;width:200px;"><?=$items?></textarea></td>
			</tr>
			<tr> 
			  <th>高度</th>
			  <td><input type="text" name="setting[size]" value="<?=$size?>" size="5"> 行</td>
			</tr>
			<tr> 
			  <th>默认值</th>
			  <td><input type="text" name="setting[defaultvalue]" size="40" value="<?=$defaultvalue?>"></td>
			</tr>
			<tr> 
				  <th>多选列表框</th>
				  <td><input type="radio" name="setting[multiple]" value="1" onclick="javascript:<?php if($page_action=='add'){?>$('#maxlength').val(255);<? } ?>$('#setcols').hide();"> 是 <input type="radio" name="setting[multiple]" value="0" onclick="javascript:<?php if($page_action=='add'){?>$('#maxlength').val(50);<? } ?>$('#setcols').show();"> 否</td>
			</tr>
			<tbody id="setcols" style="display:block">
				<tr> 
				  <th>字段类型</th>
				  <td>
				  <select name="setting[fieldtype]">
				  <option value="CHAR">定长字符 CHAR</option>
				  <option value="VARCHAR">变长字符 VARCHAR</option>
				  <option value="TINYINT">整数 TINYINT(3)</option>
				  <option value="SMALLINT">整数 SMALLINT(5)</option>
				  <option value="MEDIUMINT">整数 MEDIUMINT(8)</option>
				  <option value="INT">整数 INT(10)</option>
				  </select>
				  </td>
				</tr>
			</tbody>
		</table>
<script language="javascript">
$("input[name='setting[multiple]']").val(["<?=$multiple?>"]);
$("select[name='setting[fieldtype]']").val(["<?=$fieldtype?>"]);
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val(50);<? } ?>
</script>