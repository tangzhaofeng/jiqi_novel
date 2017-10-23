<?php
/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/8/14
 * Time: ÏÂÎç10:26
 */
include("config.php");
include("db.php");

include("../../configs/article/sort.php");

$count=count($config['sort_c']);

$cate_arr=array();



foreach($jieqiSort['article'] as $id=>$s) {
    $name=$s['caption'];
    $cate_arr[]=array(
        'cate_id'=>$id,
        'cate_name'=>iconv("GBK","utf-8",$name)
    );

}


echo json_encode($cate_arr);
