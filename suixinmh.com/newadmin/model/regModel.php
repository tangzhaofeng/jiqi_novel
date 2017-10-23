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
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
        }
        $regcount = $this->db->getCount();
        return $regcount;
    }

    function get_pv($t1,$t2) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);
        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
        $this->db->criteria->add(new Criteria('time', $time2, "<"));
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('qd', "$uid-%" , "like"));
        }
        $pv = $this->db->getSum("pv");
        return $pv;
    }

    function get_click($t1,$t2) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);
        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
        $this->db->criteria->add(new Criteria('time', $time2, "<"));
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('qd', "$uid-%" , "like"));
        }
        $click = $this->db->getSum("click");
        return $click;
    }

    function total_qd_click_pv($t1,$t2,$query_cps_id=0) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);

        $this->db->init('qddata','id','system');
        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
        $this->db->criteria->add(new Criteria('time', $time2, "<="));
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('qd', "$uid-%" , "like"));
        }
        if ($query_cps_id) {
            $this->db->criteria->add(new Criteria('qd', "$query_cps_id-%" , "like"));
        }
        $this->db->criteria->setFields("qd, sum(click) as qdclick,sum(pv) as qdpv");
        $this->db->criteria->setGroupby("qd");
        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $result[]=$row;
        }
        return $result;
    }


    function total_uid_click_pv($t1,$t2) {
        $time1 = date("YmdH",$t1);
        $time2 = date("YmdH",$t2);


        $sql="select qd,sum(click) as qdclick,sum(pv) as qdpv from jieqi_system_qddata where time>='$time1' and time<='$time2' group by qd";

        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $sql.=" and qd like '$uid-%'";
        }

        $r=$this->db->query($sql);
        $data=array();
        while ($row=$this->db->getRow($r)) {
            $sql="select * from jieqi_system_qdlist where qd='".$row['qd']."'";
            $r2=$this->db->query($sql);
            $q=$this->db->getRow($r2);
            if ($q) {
                $cps_id=$q['cps_id'];
            }
            else {
                $cps_id=8888;
            }
            $data[$cps_id]['qdclick']+=$row['qdclick'];
            $data[$cps_id]['qdpv']+=$row['qdpv'];
            $data[$cps_id]['cps_id']=$cps_id;
        }

//        $sql="select b.cps_id,sum(click) as qdclick,sum(pv) as qdpv from jieqi_system_qddata a,jieqi_system_qdlist b where a.qd=b.qd and a.time>='$time1' and a.time<='$time2' group by cps_id";
//        $auth=$this->getAuth();
//        $uid=$auth['uid'];
//        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
//            $sql.=" and b.cps_id='$uid'";
//        }
//        $res=$this->db->query($sql);
//        $result=array();
//        while ($row=$this->db->getRow($res)) {
//            $result[]=$row;
//        }
//
//        $this->db->init('qddata','id','system');
//        $this->db->setCriteria(new Criteria('time', $time1 , ">="));
//        $this->db->criteria->add(new Criteria('time', $time2, "<="));
//        $auth=$this->getAuth();
//        $uid=$auth['uid'];
//        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
//            $this->db->criteria->add(new Criteria('qd', "$uid-%" , "like"));
//        }
//        $this->db->criteria->setFields("cps_id, sum(click) as qdclick,sum(pv) as qdpv");
//        $this->db->criteria->setGroupby("cps_id");
//        $res=$this->db->queryObjects();
//        $result=array();
//        while ($row=$this->db->getRow($res)) {
//            $result[]=$row;
//        }
        return $data;
    }

    function total_uid_reg($t1,$t2) {

        $sql="select source,count(*) as regcount from jieqi_system_users where regdate>=$t1 and regdate<=$t2 ";
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $sql.=" and source like '$uid-%'";
        }
        $sql.=" group by source";
        $data=array();
        $r=$this->db->query($sql);
        while ($row=$this->db->getRow($r)) {
            $sql="select * from jieqi_system_qdlist where qd='".$row['source']."'";
            //echo $sql."\n";
            $r2=$this->db->query($sql);
            $q=$this->db->getRow($r2);
            if ($q) {
                $cps_id=$q['cps_id'];
            }
            else {
                $cps_id=8888;
            }
            $data[$cps_id]['cps_id']=$cps_id;
            $data[$cps_id]['regcount']+=$row['regcount'];
        }

//
//        $sql="select a.cps_id,c.name,count(*) as regcount from jieqi_system_qdlist a,jieqi_system_users b,jieqi_system_usersext c where a.cps_id=c.uid and b.source=a.qd and b.regdate>=$t1 and b.regdate<=$t2 ";
//
//
//        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
//            $sql.=" and a.cps_id=$uid";
//        }
//        $sql.=" group by a.cps_id";
//        //echo "\n".$sql."\n";
//        $res=$this->db->query($sql);
//        while ($row=$this->db->getRow($res)) {
//            $result[]=$row;
//        }

        return $data;


//        $this->db->init('users','uid','system');
//        $this->db->setCriteria(new Criteria('regdate', $t1 , ">="));
//        $this->db->criteria->add(new Criteria('regdate', $t2, "<="));
//        $auth=$this->getAuth();
//        $uid=$auth['uid'];
//        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
//            $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
//        }
//        $this->db->criteria->setFields("source,count(*) as qdreg");
//        $this->db->criteria->setGroupby("source");
//        $res=$this->db->queryObjects();
//        $result=array();
//        while ($row=$this->db->getRow($res)) {
//            $result[]=$row;
//        }
//        return $result;
    }

    function total_qd_reg($t1,$t2,$query_cps_id=0) {
        $this->db->init('users','uid','system');
        $this->db->setCriteria(new Criteria('regdate', $t1 , ">="));
        $this->db->criteria->add(new Criteria('regdate', $t2, "<="));
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
        }

        if ($query_cps_id) {
            $this->db->criteria->add(new Criteria('source', "$query_cps_id-%" , "like"));
        }

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