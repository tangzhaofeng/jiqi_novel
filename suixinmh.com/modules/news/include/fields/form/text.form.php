<table cellpadding="0" cellspacing="0" class="grid">
	<tr> 
		<th>�ı��򳤶�</th>
		<td><input type="text" name="setting[size]" value="<?=$size?>" size="10"></td>
	</tr>
	<tr> 
		<th>Ĭ��ֵ</th>
		<td><input type="text" name="setting[defaultvalue]" value="<?=$defaultvalue?>" size="40"></td>
	</tr>
</table>
<script language="javascript">
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val(255);<? } ?>
</script>