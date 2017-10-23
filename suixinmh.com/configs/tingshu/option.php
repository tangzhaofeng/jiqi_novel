<?php
/**
 * 数据表里面可选项和值的对应关系
 * multiple 0 单选 1 对选
 * default 默认值
 * items 选项列表
*/
//管理授权
$jieqiOption['tingshu']['authorflag'] = array('multiple' => 0, 'default' => 0, 'items' => array(0 => '暂时不予授权', 1 => '授权给该作者'));
//授权级别
$jieqiOption['tingshu']['permission'] = array('multiple' => 0, 'default' => 2, 'items' => array(0 => '暂未授权', 1 => '授权作品', 2 => '驻站作品',3 => '专属作品', 4 => 'A级签约'));
//首发状态
$jieqiOption['tingshu']['firstflag'] = array('multiple' => 0, 'default' => 0, 'items' => array(0 => '本站首发',1 => '香港文学城',2 => '3G书城',3 => '陕西数字出版基地',4 => '得书网',5 => '飞象出版社',6 => '编辑邀稿',7 => '断天小说网',8 => '原创书殿',9 => '言情书殿',10 => '江西新媒体'));
//连载状态
$jieqiOption['tingshu']['fullflag'] = array('multiple' => 0, 'default' => 0, 'items' => array(0 => '连载中', 1 => '已完成'));
?>