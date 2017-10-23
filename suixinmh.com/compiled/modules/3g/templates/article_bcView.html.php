<?php
$this->_tpl_vars['jieqi_pagetitle'] = '我的书架';
echo '
';
$this->_tpl_vars['meta_keywords'] = '最新小说,原创小说,小说大全,小说库';
echo '
';
$this->_tpl_vars['meta_description'] = '';
echo '
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>'.$this->_tpl_vars['jieqi_pagetitle'].'</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
		
		<meta http-equiv="Cache-Control" content="no-transform " /> 
		<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
		<meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
		<meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
		<meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
		<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/common.css"/>
		<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'css/jquery.alertable.css"/>
		<style>
			.current {
				color: #b90000;
			}
			
			 .shanchu{
                outline:none;
                -webkit-tap-highlight-color: rgba(0,0,0,0); 
                background:#f0234e;
                height:25px;
                width:60px;
                border:none;
                border-radius: 5px;
                color:#fff;
                display: block;
                margin :0 auto;
                margin-top:5px;

            }
      .dib {
			    display: inline-block;
			    padding: 6px;
			    background: #00b8ac;
			    color: #fff;
			    border-radius: 4px;
			}
			.tz{
				  display: inline-block;
          vertical-align: middle;
			}
			.pageN{
				   display: inline-block;
           vertical-align: middle;
		}
		</style>
		
	</head>

	<body>
		<!--nav-->
		';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
		<div class="mt10  bgcfff pb10">
				<h1 id="tabs1" class="mlf10 ptb10 f16 clearfix" style="color: #1fb3b6;border-bottom:#dad9d9 1px solid;">我在看的书<span class="fr"><a class="f12 mr10 current" href="javascript:;">最近阅读('.$this->_tpl_vars['read_count'].')</a><a class="f12" href="javascript:;">已收藏的(<span data-name="my_collect">'.$this->_tpl_vars['nowbookcase'].'</span>)</a></span>
				</h1>
			<table class="wp100 f13 plf10 lh25"   id="list2">
					';
if(intval($this->_tpl_vars['read_count'])==0){
echo '
		            <tr>
		            	<td colspan="2">
						>_<当前还没有阅读记录哦~
						</td>
		            </tr>
	            ';
}else{
echo '
	            ';
if (empty($this->_tpl_vars['read_history'])) $this->_tpl_vars['read_history'] = array();
elseif (!is_array($this->_tpl_vars['read_history'])) $this->_tpl_vars['read_history'] = (array)$this->_tpl_vars['read_history'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['read_history']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['read_history']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['read_history']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['read_history']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['read_history']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	$this->_tpl_vars['bg']=$this->_tpl_vars['i']['order']%2; 
echo '
				<tr ';
if($this->_tpl_vars['bg']<1){
echo ' class="line"';
}
echo '>
					<td class="wp80 bb bceee">
						<p>
							<a class="cOrange1" href="javascript:;">'.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['aname'].'</a>
						</p>
					
						<p>
							<a class="c333" href="javascript:;">'.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['asort'].' | '.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['autname'].'</a>
						</p>
						<p>
							<a class="c333" href="'.geturl('3g','reader','SYS=aid='.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['aid'].'&cid='.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['cid'].'').'"><em>连载中：</em>'.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['cname'].'</a>
						</p>
			
					</td>

					<td class="wp20 tc bb bceee">
						<a href="'.geturl('3g','articleinfo','SYS=aid='.$this->_tpl_vars['read_history'][$this->_tpl_vars['i']['key']]['aid'].'').'" class="cRed">继续阅读</a>
					</td>
				</tr>
				';
}
echo '
				';
}
echo '
				
			</table>
			<table class="wp100 f13 plf10 lh25" style="display:none;" data-name="collect_ul">
					';
if(intval($this->_tpl_vars['read_count'])==0){
echo '
		            <tr>
		            	<td colspan="2">
						您的书架没有书，赶紧去加书架吧
						</td>
		            </tr>
	            ';
}else{
echo '
	           ';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	$this->_tpl_vars['aid']=$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articleid'];$this->_tpl_vars['cid']=$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchapterid'];$this->_tpl_vars['bg'] = $this->_tpl_vars['i']['order']%2; 
echo '
				<tr ';
if($this->_tpl_vars['bg']<1){
echo ' class="line"';
}
echo ' data-id="'.$this->_tpl_vars['aid'].'">
					<td class="wp80 bb bceee">
						<p>
							  <a class="cOrange1" href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_module_articleinfo'].'" class="name">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a>
						</p>
					
						<p>
							 <span class="lei"><a href="'.geturl('3g','shuku','SYS=sort='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sortid'].'&size=0&fullflag=0&operate=0&free=0&page=1&siteid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['siteid'].'').'">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['sort'].'</a> | '.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'].'</span>
						</p>
						<p>
							  <a href="'.geturl('3g','reader','SYS=aid='.$this->_tpl_vars['aid'].'&cid='.$this->_tpl_vars['cid'].'').'" class="lzz"><em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['fullflag_tag'].'：</em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchapter'].'</a>
                    ';
if($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['chaptername'] != ''){
echo '<a href="'.geturl('3g','reader','SYS=aid='.$this->_tpl_vars['aid'].'&cid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['chapterid'].'').'" class="sq">书   签：'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['chaptername'].'</a>';
}
echo '
						</p>
					</td>
					<td class="wp20 tc bb bceee">
					 <a href="'.geturl('3g','article','SYS=method=bcBatch&aid='.$this->_tpl_vars['aid'].'&checkid='.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['caseid'].'').'" class="shanchu" data-act="j_del_bookcase">删除</a>
					</td>
				</tr>
				';
}
echo '
				';
}
echo '
			</table>
		</div>
		 <div class="mt10 bgceee tc ptb5">
        <form action="#" id="jumppage">
          
            <a href="#" class="dib">上一页</a>
        
        <input type="text" class="bgcfff b bcddd w40 lh25 tc" name="page" value="1" />
        <a href="javascript:" onclick="document.getElementById(\'jumppage\').submit()" class="w40 lh25 bgcfff  ml5 b bcddd tz">跳转</a>
        <span class="lh25 pageN">1/21</span>
          
        <a href="'.$this->_tpl_vars['url_3g_next'].'" class="dib">下一页</a>
       
        </form>
    </div>
		<div class="p10 mb10">
			<a href="'.geturl('3g','userhub').'" class="cRed f14">返回个人中心</a>
		</div>
		';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/3g/bottom.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
	 	<script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/jquery.min.js"></script>
	 	<script src="'.$this->_tpl_vars['jieqi_themeurl'].'js/share/jquery.alertable.min.js"></script>
		<script type="text/javascript">
		$(function(){
				$("#tabs1 a").on("click",function(){
					var index=$(this).index();
					$(this).siblings().removeClass("current");
					$(this).addClass("current");
					$("table").hide();
					$("table").eq(index).show();
				})
			// 个人中心-收藏页删除
				$("[data-act=j_del_bookcase]").on("click", function(event){
					event.preventDefault();
					var parent=$(this).parents("tr");
					var _this_id=$(this).parent().attr("data-id");
					var _collect_sum=$("[data-name=my_collect]").html();
					var _url = $(this).attr("href");
					$.alertable.confirm(\'确定要删除么?\').then(function() {

						$.ajax({
						     type: \'GET\',
						     url: _url ,
						     // dataType: "json",
						    success: function(data){
						    	
						    $.alertable.alert(\'删除成功！\');
						    var num=$("[data-name=my_collect]").text();
						  	 $("[data-name=my_collect]").text(num-1);
						    	parent.remove();
						  
						    } 
						   
						});		
					
					}, function() {
					     
					});

			
				})
		})
		</script>	
	</body>

</html>';
?>