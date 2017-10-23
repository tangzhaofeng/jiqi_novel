<table cellpadding="0" cellspacing="0" class="grid">
	<tr> 
		<th>д╛хож╣</th>
		<td><input type="text" name="setting[defaultvalue]" value="<?=$defaultvalue?>" size="10"></td>
	</tr>
</table>
<script language="javascript">
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val(30);<? } ?>
</script>