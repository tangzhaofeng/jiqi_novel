<?php
echo '<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.css" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/local/zh_CN.js"></script>
<table class="grid" width="100%" align="center" id="list">
	<caption>
    频道：<select name="channel" onChange="document.location=\''.$this->_tpl_vars['adminprefix'].'&channel=\'+this.options[this.selectedIndex].value;">
    		<option value="-1">-全部-</option>
    		';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
    			';
if($this->_tpl_vars['j']['key'] != 400){
echo '
	    			<option value="'.$this->_tpl_vars['j']['key'].'" ';
if($this->_tpl_vars['sel_channel']==$this->_tpl_vars['j']['key']){
echo 'selected';
}
echo '>
	 					'.$this->_tpl_vars['channel'][$this->_tpl_vars['j']['key']]['name'].'
	 				</option>
    			';
}
echo '
 			';
}
echo '
    	</select>
    	<a id="add"  title="点击添加">+添加新标签</a>
	</caption>
  <tr align="center">
  	<th width="2%"></th>
  	<th width="2%"></th>
    <th width="30%">标签名称</th>
    <th width="40%">应用站点</th>
    <th width="">操作</th>
  </tr>
  ';
if (empty($this->_tpl_vars['rows'])) $this->_tpl_vars['rows'] = array();
elseif (!is_array($this->_tpl_vars['rows'])) $this->_tpl_vars['rows'] = (array)$this->_tpl_vars['rows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['rows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['rows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['rows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['rows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['rows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
      <td  align="center"><input type="checkbox" name="selbox"/>
    </td>
      <td  align="center">'.$this->_tpl_vars['i']['order'].'
    </td>
    <td  align="center">'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['name'].'
    </td>
    <td >
    	';
$this->_tpl_vars['siteid'] = explode(",",$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['siteid']); 
echo '
    	';
if (empty($this->_tpl_vars['siteid'])) $this->_tpl_vars['siteid'] = array();
elseif (!is_array($this->_tpl_vars['siteid'])) $this->_tpl_vars['siteid'] = (array)$this->_tpl_vars['siteid'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['siteid']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['siteid']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['siteid']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['siteid']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['siteid']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
    		'.$this->_tpl_vars['channel'][$this->_tpl_vars['j']['value']]['name'].'
    	';
}
echo '
    </td>
    <td  align="center"><a href="javascript:;" name="edit_tag" value="'.$this->_tpl_vars['rows'][$this->_tpl_vars['i']['key']]['tagid'].'">修改</a></td>
  ';
}
echo '
</table>
<div class="pages">'.$this->_tpl_vars['url_jumppage'].'</div>
<!-- <form name="add_tag" id="add_tag" method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=addTag" style="display:none" ajaxpost="true" retruemsg="true"> -->
<form name="add_tag" id="add_tag" method="post" action="'.$this->_tpl_vars['adminprefix'].'&method=addTag" style="display:none" ajaxpost="true" retruemsg="true">
	<table class="grid" width="400px" align="center" id="list">
	<caption>添加新标签</caption>
	<tr>
		<td>标签名称<br/>(自动去除重复的标签<br/>支持批量录入<br/>如：打怪|升级)</td>
		<td><textarea name="tname" id="tname" cols="30" rows="8" style="border:1px #268BC2 solid;" data-rule="required;"></textarea><font color="red">*</font></td>
	</tr>
	<tr>
		<td>站点</td>
		<td>
			';
if (empty($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = array();
elseif (!is_array($this->_tpl_vars['channel'])) $this->_tpl_vars['channel'] = (array)$this->_tpl_vars['channel'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['channel']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['channel']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['channel']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['channel']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['channel']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
    			';
if($this->_tpl_vars['j']['key'] != 400){
echo '
    				<label><input name="channel[]" type="checkbox" value="'.$this->_tpl_vars['j']['key'].'" data-rule="checked"/>'.$this->_tpl_vars['channel'][$this->_tpl_vars['j']['key']]['name'].' </label> 
    			';
}
echo '
 			';
}
echo '
		<font color="red">*</font></td> 
	</tr>
	<tr>
		<td colspan="2" align="center"><button type="submit" >提交</button><input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '" /></td>
	</tr> 
	</table>
</form>
<script>

$(document).ready(function(){
	$("#add_tag").hide();
	$("#add").click(function(){
		$(\'#add_tag\')[0].reset();
// 		$(\'#articleid\').attr("readonly",false);
		$(\'#add_tag caption\').text("添加标签");
		$(\'#add_tag\').attr(\'action\', \''.$this->_tpl_vars['adminprefix'].'&method=addTag\');
		 $.layer({
				shade : [0.5 , \'#000\' , true],
				type : 1,
//				area : [\'60%\',\'560px\'],
				area : [\'400px\',\'230px\'],
				title : false,
				offset : [\'60px\' , \'50%\'],
				border : [10 , 0.3 , \'#000\', true],
				zIndex : 1,
				page: {
			 	       dom: \'#add_tag\'
			 	    },
				close : function(index){
					layer.close(index);
					$(\'.ul_con\').hide();
				}
			});	
// 		 $("#articleid").focus();
	});
	//修改
	$("a[name=\'edit_tag\']").click(function(){
		var id = $(this).attr("value");
		GPage.getJson(\''.$this->_tpl_vars['adminprefix'].'&method=getTag&id=\'+id,function(data){
			if(data.status==\'OK\'){
				$("#tname").val(data.msg["name"]);
				var siteid = data.msg["siteid"];
				siteid = siteid.split(",");
				$("[name=\'channel[]\']").attr("checked",false);//清空
				for(i=0;i<siteid.length;i++){
					$("[name=\'channel[]\'][value=\'"+siteid[i]+"\']").attr("checked",\'true\');
				}
				$(\'#add_tag caption\').text("修改标签");
				$(\'#add_tag\').attr(\'action\', \''.$this->_tpl_vars['adminprefix'].'&method=editTag&id=\'+id);
				$.layer({
					shade : [0.5 , \'#000\' , true],
					type : 1,
//					area : [\'60%\',\'560px\'],
					area : [\'400px\',\'230px\'],
					title : false,
					offset : [\'60px\' , \'50%\'],
					border : [10 , 0.3 , \'#000\', true],
					zIndex : 1,
					page: {
				 	       dom: \'#add_tag\'
				 	    },
					close : function(index){
						layer.close(index);
						$(\'.ul_con\').hide();
					}
				});	
			}else{
				layer.alert(data.msg, 8, !1);
			}
		})
	});
	$("#list tr:not(\':first\')").bind("mouseover",function(){
		 $(this).css("background","#DDF2FF");
	});
	$("#list tr:not(\':first\')").bind("mouseout",function(){
		if($(this).find("input[name=\'selbox\']").attr("checked")){
			$(this).css("background","#C3E8FF");
		}else{
			$(this).css("background","#ffffff");
		}
	});
})
</script>';
?>