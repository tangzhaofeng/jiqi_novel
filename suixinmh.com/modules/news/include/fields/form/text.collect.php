<tr valign="middle" align="left">
     <th style="cursor:hand" onClick="ShowLabel('f_<?php echo $field;?>');"><?php if($star) echo '<font color="red">*</font>';?>
		 <img src="<?php echo $_SGLOBAL['news']['url'];?>/images/<?php if($data['adopt']=='' && !$star) echo 'close'; else echo 'open';?>.gif" width="18" height="18" id="f_<?php echo $field;?>img"><strong  id='rule1name'><?php echo $name;?></strong>: [点击打开/隐藏标签]
	  </th>
     <th><?php echo $name;?></th>
 </tr>
<tr id="f_<?php echo $field;?>" style="display:<?php if($data['adopt']=='' && !$star) echo 'none'?>">
    <th>
	<?php if($star) echo '<font color="red">*</font>';?><font color=blue>文章<?php echo $name;?>采集规则</font>
	</th>
    <td><textarea name="setting[fields][<?php echo $field;?>][adopt]" id="f_<?php echo $field;?>" rows="4" cols="60" <?php if($star>0){?> require="true" datatype="limit" msg="请填写文章<?php echo $name;?>采集规则"<?php }?>><?php echo $data['adopt'];?></textarea>
	</td>
</tr>
<tr id="f_<?php echo $field;?>trim" style="display:<?php if($data['adopt']=='' && !$star) echo 'none'?>">
    <th><font color=blue>文章<?php echo $name;?>信息替换删除</font><br>可多个过滤规则，每个规则必须一行，可使用替换标签，如：&lt;div>!&lt;/div>|替换内容</th>
    <td><textarea name="setting[fields][<?php echo $field;?>][filter]" id="f_<?php echo $field;?>filter" rows="4" cols="60"><?php echo $data['filter'];?></textarea></td>
</tr>