<DIV class="ManagerForm" id="FormDiv">
<div class="subManager">
	<FIELDSET>
	<LEGEND style="BACKGROUND: url(/modules/news/images/icon.gif) no-repeat 6px 50%; border-color:#FFFFFF; padding-left:25px;">动态区块列表</LEGEND>
		<table align="center" cellpadding="0" cellspacing="1" class="grid" width="98%" style="margin-bottom:5px;">
	<tr> 
		<th width='15%'>区块显示数据条数</th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][blocknum]" value="<?=$set['blocknum']?>" size="7"> 提示：留空或为0则表示不读取</td>
	</tr>
	<tr> 
		<th>列表每页数据条数</th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][pagesize]" value="<?=$set['pagesize']?>" size="7"> 提示：留空或为0则表示不读取</td>
	</tr>
	<tr> 
		<th>列表页最大更新页数</th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][maxpage]" value="<?=$set['maxpage']?>" size="7"> <font color="red">设置过大会影响服务器负载</font></td>
	</tr>
	<tr> 
		<th>列表页模板</th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][listtemplate]" value="<?=$set['listtemplate']?>" size="25"></td>
	</tr>
	<tr> 
		<th>生成文件URL规则</th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][urlrule]" value="<?=$set['urlrule']?>" size="25"></td>
	</tr>
	<tr> <th colspan=2 style="text-align:center; font-size:14">条件字段</th></tr>
	<tr> 
		<th>频 道：<input type="hidden" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][modelid][name]" value="频 道"></th>
		<td><select name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][modelid][where]">
<option value=""></option><?php foreach($_SGLOBAL['model'] as $v){?>
<option value="<?php echo $v['modelid'];?>" <?php if($set['fields']['modelid']['where']==$v['modelid']) echo 'selected';?>><?php echo $v['name'];?></option><?php } ?>
</select> </td>
	</tr>
	<tr> 
		<th>栏 目：<input type="hidden" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][catid][name]" value="栏 目"></th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][catid][where]" value="<?=$set['fields']['catid']['where']?>" size="25"> <font color="red">填写栏目编号</font></td>
	</tr>
	<tr> 
		<th>标 题：<input type="hidden" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][title][name]" value="标 题"></th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][title][where]" value="<?=$set['fields']['title']['where']?>" size="25"> </td>
	</tr>
	<tr> 
		<th>关键词：<input type="hidden" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][keywords][name]" value="关键词"></th>
		<td><input type="text" name="<?=$this->fieldpre?>[<?php echo $this->field;?>][fields][keywords][where]" value="<?=$set['fields']['keywords']['where']?>" size="25"> </td>
	</tr>	
	</table>
	</FIELDSET> 
	</div>
</DIV>
