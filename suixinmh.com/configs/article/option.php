<?php
/**
 * 数据表里面可选项和值的对应关系
 * multiple 0 单选 1 对选
 * default 默认值
 * items 选项列表
*/
//管理授权
$jieqiOption['article']['authorflag'] = array('multiple' => 0, 'default' => 0, 'items' => array(0 => '暂时不予授权', 1 => '授权给该作者'));
//授权级别
$jieqiOption['article']['permission'] = array('multiple' => 0, 'default' => 2, 'items' => array(0 => '暂未授权', 1 => '授权作品', 2 => '驻站作品',3 => '专属作品', 4 => 'A级签约'));
//首发状态
$jieqiOption['article']['firstflag'] = array('multiple' => 0, 'default' => 0, 'items' => array(0 => '本站首发',1=>'趣阅网',2=>'悦读坊',3=>'南泽文化',4=>'看书网',5=>'凤栖梧',6=>'凤鸣轩',7=>'网易云阅读',8=>'天涯',9=>'阅庭书院',10=>'锦瑟文学',11=>'创酷中文网'));
//连载状态
$jieqiOption['article']['fullflag'] = array('multiple' => 0, 'default' => 0, 'items' => array(0 => '连载中', 1 => '已完成'));
?>