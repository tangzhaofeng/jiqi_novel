<?php 
/** 
 * ²Ëµ¥ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class regModel extends Model{

    function main($param){
        return $param;
    }
    

    function get_regcount($t1,$t2) {
        $this->db->init('users','uid','system');
        $this->db->setCriteria(new Criteria('regdate', $t1 , ">="));
        $this->db->criteria->add(new Criteria('regdate', $t2, "<"));
        $regcount = $this->db->getCount();
        return $regcount;
    }

    function get_pv($t1,$t2) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);
        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
        $this->db->criteria->add(new Criteria('time', $time2, "<"));
        $pv = $this->db->getSum("pv");
        return $pv;
    }

    function get_click($t1,$t2) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);
        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
        $this->db->criteria->add(new Criteria('time', $time2, "<"));
        $click = $this->db->getSum("click");
        return $click;
    }

    function total_qd_click_pv($t1,$t2) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);
        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
        $this->db->criteria->add(new Criteria('time', $time2, "<="));
        $this->db->criteria->setFields("qd, sum(click) as qdclick,sum(pv) as qdpv");
        $this->db->criteria->setGroupby("qd");
        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $result[]=$row;
        }
        return $result;
    }

    function total_qd_reg($t1,$t2) {
        $this->db->init('users','uid','system');
        $this->db->setCriteria(new Criteria('regdate', $t1 , ">="));
        $this->db->criteria->add(new Criteria('regdate', $t2, "<="));
        $this->db->criteria->setFields("source,count(*) as qdreg");
        $this->db->criteria->setGroupby("source");
        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $result[]=$row;
        }
        return $result;
    }

    function total_qd_reg_ext($qd) {
        $this->db->init('users','uid','system');
        $this->db->setCriteria(new Criteria('source', $qd , "="));
        return $this->db->getCount();
    }

    function total_qd_click_pv_ext($qd) {
        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('qd', $qd , "="));
        $this->db->criteria->setFields("sum(click) as qdclick,sum(pv) as qdpv");
        $this->db->queryObjects();
        $result = $this->db->getRow();
        return $result;
    }
 
} 

?>