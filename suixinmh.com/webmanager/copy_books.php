<?php
include_once("db.php");
//if (!$_SESSION['jieqiAdminLogin']) {
//  $host=$_SERVER['HTTP_HOST'];
//  $script=$_SERVER['SCRIPT_NAME'];
//  $url=urlencode("http://$host/$script");
//  header("location:/admin/?controller=login&jumpurl=$url");
//  exit();
//}



function query($sql) {
  global $db;
  $r=$db->query($sql);
  if (!$r) {
    die("mysql error:".$db->error());
  }
  return $db->fetchArray($r);
}

function queryall($sql) {
  global $db;
  $r=$db->query($sql);
  if (!$r) {
    die("mysql error:".$db->error());
  }
  $data=array();
  while ($d=$db->fetchArray($r)) {
	  $data[]=$d;
  }
  return $data;
}

function copy_article($article_id)
{
  $sql = "select * from  jieqi_article_article where articleid=$article_id";
  $article = query($sql);
  if (!$article) {
    return false;
  }
  $sql = "insert into jieqi_article_article set ";
  $sets = array();
  foreach ($article as $field => $value) {
    if ($field == 'articleid') {
      $insert_value = "''";
    } else {
      $insert_value = "'" . addslashes($value) . "'";
    }
    $sets[] = "$field = $insert_value";
  }
  $sql .= implode(',',$sets);
  if (mysql_query($sql)) {
    return mysql_insert_id();
  } else {
    echo $sql . "\n";
    return false;
  }
}

function copy_chapter($article_id,$new_id) {
  $sql = "select * from jieqi_article_chapter where articleid='$article_id'";
  $chapter_id_list=array();
  $chapters = queryall($sql);
  foreach($chapters as $chapter) {
    $chapter_id = $chapter['chapterid'];
    $sql="insert into jieqi_article_chapter set ";
    $sets = array();
    foreach($chapter as $field=>$value) {
      if ($field == 'articleid') {
        $insert_value = "'".$new_id."'";
      }
      elseif ($field == 'chapterid') {
        $insert_value = "''";
      }
      else {
        $insert_value = "'".addslashes($value)."'";
      }
      $sets[] = "$field = $insert_value";
    }
    $sql .= implode(',',$sets);
    if (!mysql_query($sql)) {
      return false;
    }
    else {
      $new_chapter_id = mysql_insert_id();
      $chapter_id_list[$chapter_id]=$new_chapter_id;
    }
  }
  return $chapter_id_list;
}


$msg = "";
if ($_POST['step']) {
  $article_id = intval($_POST['article_id']);
  $new_id = copy_article($article_id);
  if ($new_id) {
    $chapter_result = copy_chapter($article_id,$new_id);
    if ($chapter_result) {
      $root_path=dirname(__FILE__)."/../files/article/c_txt_shuhai/";
      $image_path=dirname(__FILE__)."/../files/article/image/";
      $new_dir = floor($new_id/1000)."/".$new_id;
      $old_dir = floor($article_id/1000)."/".$article_id;
      $new_txt_path = $root_path.$new_dir."/";
      $old_txt_path = $root_path.$old_dir."/";

      $new_image_path = $image_path.$new_dir."/";
      $old_image_path = $image_path.$old_dir."/";

      if (!is_dir($new_image_path)) {
        mkdir($new_image_path);
      }
      copy($old_image_path."{$article_id}l.jpg",$new_image_path."{$new_id}l.jpg");
      copy($old_image_path."{$article_id}s.jpg",$new_image_path."{$new_id}s.jpg");
      if (!is_dir($new_txt_path)) {
        mkdir($new_txt_path);
      }
      $index = file_get_contents($old_txt_path."index.opf");
      $index = str_replace("<dc:Articleid>$article_id</dc:Articleid>","<dc:Articleid>$new_id</dc:Articleid>",$index);
      foreach($chapter_result as $chapterid=>$new_chapterid) {
        copy($old_txt_path.$chapterid.".txt",$new_txt_path.$new_chapterid.".txt");
        $index = str_replace($chapterid.".txt",$new_chapterid.".txt",$index);
      }
      file_put_contents($new_txt_path."index.opf",$index);
      $msg = "书籍复制成功，新的ID是 <font color=#FF0000>$new_id</font>";
    }
  }
}
?>
<!doctype html>
<html class="no-js">
<head>
  <meta charset="GBK">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ishufun.net后台管理系统</title>
  <meta name="description" content="这是一个 index 页面">
  <meta name="keywords" content="index">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="icon" type="image/png" href="assets/i/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="assets/i/app-icon72x72@2x.png">
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="stylesheet" href="assets/css/amazeui.min.css"/>
  <link rel="stylesheet" href="assets/css/admin.css">
  <style type="text/css">
  body,td,th {
	font-size: 10px;
}
  </style>
  <link href="jQueryAssets/jquery.ui.core.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.theme.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.datepicker.min.css" rel="stylesheet" type="text/css">
  <link href="jQueryAssets/jquery.ui.button.min.css" rel="stylesheet" type="text/css">
<script src="jQueryAssets/jquery-1.11.1.min.js" type="text/javascript"></script>
<script src="jQueryAssets/jquery.ui-1.10.4.datepicker.min.js" type="text/javascript"></script>
<script src="jQueryAssets/jquery.ui-1.10.4.button.min.js" type="text/javascript"></script>
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://browsehappy.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar admin-header">
  <div class="am-topbar-brand"><strong>Pinshu</strong><small>后台管理</small></div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
      <li></li>
      <li class="am-dropdown" data-am-dropdown>
      </li>
      <li></li>
    </ul>
  </div>
</header>

<div class="am-cf admin-main">
<?
include("menu.php");
?>

  <!-- content start -->
  <div class="admin-content">
<div class="am-g">

    </div>

    <div class="am-g"></div>
    <?php
    if ($msg) {
      echo $msg;
    }
    else {
    ?>
    <form action="copy_books.php" method="post">
    <table width="600px" height="60">
    <tr>
      <td width="171" height="54">原书籍ID&nbsp;</td>
      <td width="417"><input name="article_id" type="text" size="10" /> &nbsp;
        <input type="submit" name="submit" id="submit" value="复制"><input type="hidden" name="step" value="1"></td>
    </tr>
    </table>
    
    </form>
    <?php
    }
    ?>
  </div>
  <!-- content end -->

</div>

<footer>
  <hr>
  <p class="am-padding-left">&copy; 2014 AllMobilize, Inc. Licensed under MIT license.</p>
</footer>

<!--[if lt IE 9]>
<script src="http://libs.baidu.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/polyfill/rem.min.js"></script>
<script src="assets/js/polyfill/respond.min.js"></script>
<script src="assets/js/amazeui.legacy.js"></script>
<![endif]-->

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<!--<![endif]-->
<script src="assets/js/app.js"></script>
<script type="text/javascript">
$(function() {
	$( "#tdate1" ).datepicker(); 
});
$(function() {
	$( "#Button1" ).button(); 
});
$(function() {
	$( "#tdate2" ).datepicker(
		$.extend(
		$.datepicker.regional['zh-CN'],
		{
		dateFormat:"yy-mm-dd"
		}
		)
		); 
});
</script>
</body>
</html>
