<table cellpadding="0" cellspacing="0" class="grid">
	<tr> 
		<th>区块显示数据条数</th>
		<td><input type="text" name="setting[blocknum]" value="<?=$blocknum?>" size="7"> 提示：留空或为0则表示不读取</td>
	</tr>
	<tr> 
		<th>列表每页数据条数</th>
		<td><input type="text" name="setting[pagesize]" value="<?=$pagesize?>" size="7"> 提示：留空或为0则表示不读取</td>
	</tr>
	<tr> 
		<th>列表页最大更新页数</th>
		<td><input type="text" name="setting[maxpage]" value="<?=$maxpage?>" size="7"> <font color="red">设置过大会影响服务器负载</font></td>
	</tr>
	<tr> 
		<th>列表页模板</th>
		<td><input type="text" name="setting[listtemplate]" value="<?=$listtemplate?>" size="25"></td>
	</tr>
	<tr> 
		<th>生成文件URL规则</th>
		<td><input type="text" name="setting[urlrule]" value="<?=$urlrule?>" size="25"></td>
	</tr>
	<tr> <th colspan=2 style="text-align:center; font-size:14">条件字段</th></tr>
	<tr> 
		<th>频 道：<input type="hidden" name="setting[fields][modelid][name]" value="频 道"></th>
		<td><select name="setting[fields][modelid][where]" id="setting[fields][modelid][where]">
<option value=""></option><?php foreach($_SGLOBAL['model'] as $v){?>
<option value="<?php echo $v['modelid'];?>"><?php echo $v['name'];?></option><?php } ?>
</select> </td>
	</tr>
	<tr> 
		<th>栏 目：<input type="hidden" name="setting[fields][catid][name]" value="栏 目"></th>
		<td><input type="text" name="setting[fields][catid][where]" value="<?=$this->setting['fields']['catid']['where']?>" size="25"> 格式:<font color="red">条件符号|值</font></td>
	</tr>
	<tr> 
		<th>标 题：<input type="hidden" name="setting[fields][title][name]" value="标 题"></th>
		<td><input type="text" name="setting[fields][title][where]" value="" size="25"> 格式:<font color="red">条件符号|值</font></td>
	</tr>
	<tr> 
		<th>关键词：<input type="hidden" name="setting[fields][keywords][name]" value="关键词"></th>
		<td><input type="text" name="setting[fields][keywords][where]" value="" size="25"> 格式:<font color="red">条件符号|值</font></td>
	</tr>	
</table>
<script language="javascript">
$("select[name='setting[fields][modelid][where]']").val(["<?=$this->setting['fields']['modelid']['where']?>"]);
<?php if($page_action=='add'){?>$('#minlength').val(0);$('#maxlength').val(255);<? } ?>
</script>