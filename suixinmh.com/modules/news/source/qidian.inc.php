<?php
$_OBJ['qidian'] = new View('qidian','articleid','article');
$_OBJ['qidian']->setHandler();
$_PAGE['rows'] = $_OBJ['qidian']->lists();
foreach($_PAGE['rows'] as $rows){
   if($rows['articleid']!=$_PAGE['rows'][count($_PAGE['rows'])-1]['articleid']) echo $rows['articleid'].',';
   else echo $rows['articleid'];
}exit;
/*getparameter('start');
$start = $_PAGE['start'] ? $_PAGE['start'] : 24;
$end = 54;
$c = file_get_contents('http://all.qidian.com/book/bookstore.aspx?ChannelId=-1&SubCategoryId=-1&Tag=all&Size=-1&Action=5&OrderId=5&P=all&PageIndex='.$start.'&update=-1&Vip=0&Boutique=-1&SignStatus=-1');
$articleids = exechars('<span class="swbt"><a href="/Book/$$$$.aspx" target="_blank">!</a> </span>',$c,true);//书号
$articlenames = exechars('<span class="swbt"><a href="/Book/$.aspx" target="_blank">!!!!</a> </span>',$c,true);//书名
$authors = exechars('<div class="swd"><a href="http://me.qidian.com/authorIndex.aspx?id=$" target="_blank" class="black">!!!!</a></div>',$c,true);//笔名
$classs = exechars('<div class="swa">[<a href="http://all.qidian.com/book/bookstore.aspx?ChannelId=$" class="hui2">!!!!</a>/<a href="http://all.qidian.com/book/bookstore.aspx?ChannelId=$&SubCategoryId=$"   class="hui2">!</a>]</div>',$c,true);//分类
$sizes = exechars('<div class="swc">!!!!</div>',$c,true);//字数
$num = $i = 0;
if(is_array($articleids)){
    $_OBJ['qidian'] = new View('qidian','articleid','article');
    foreach($articleids as $articleid){
	     if($_OBJ['qidian']->get($articleid,true)) continue;
	     $data = array(
		      'articleid'=>$articleid,
			  'articlename'=>iconv('utf-8','gbk',$articlenames[$i]),
			  'author'=>iconv('utf-8','gbk',$authors[$i]),
			  'class'=>iconv('utf-8','gbk',$classs[$i]),
			  'size'=>$sizes[$i]
		 );
		 $i++;
	     if($_OBJ['qidian']->add($data,true)) $num++;
	}
}
echo '更新'.$num.'条信息';
if($start>=$end) exit('更新完成！');
header('location:http://www.news.com/modules/news/?ac=qidian&start='.($start+1));
exit;*/
?>