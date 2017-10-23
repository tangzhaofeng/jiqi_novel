<?php
include_once (JIEQI_ROOT_PATH .'/lib/my_cachetable.php');
class MyPosition extends Mycachetable{
function MyPosition(){
$this->init('position','posid','system','listorder');
}
function cache(){
global $_SGLOBAL;
$_SGLOBAL[$this->table] = array();
$where= ' where siteid='.JIEQI_SITE_ID;
if($this->order) $where.= " order by ".$this->order." ASC";
$data = $this->selectsql('select posid,name,data,type,listorder from '.$this->dbprefix("{$this->table}")." {$where}");
$this->cache_write($this->table,"_SGLOBAL['".$this->table."']",$data,$this->idfield,$this->cachefile);
include($this->cachefile);
}
}
