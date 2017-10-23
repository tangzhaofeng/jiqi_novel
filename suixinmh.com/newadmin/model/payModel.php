<?php 
/** 
 * 菜单 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class payModel extends Model{

    function main($param){
        return $param;
    }

    function pay_weekly_total() {
        $r = array();
        $date=date("Y-m-d");
        $t1 = strtotime($date);
        for ($i=1;$i<=7;$i++) {
            $t = $t1 - $i * 86400;
            $result = $this->pay_daily_total($t,$t+86400);
            $r[]=$result;
        }
        return $r;
    }

    function pay_daily_total($t1,$t2) {
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        $date = date("Y-m-d",$t1);
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $date=$uid."@@".$date;
        }
        $result = $this->redis->hget("pay_daily_total",$date);
        if ($result) {
            return unserialize($result);
        }

        $result = $this->get_pay($t1,$t2);
        $result['date'] = $date;
        $regmod=$this->model("reg");
        $result['regcount'] = $regmod->get_regcount($t1,$t2);
        $result['pv'] = (round($regmod->get_pv($t1,$t2)*100/10000)/100)."万";
        if (time()>$t2+3600) {
            $this->redis->hset('pay_daily_total',$date,serialize($result));
            //echo $date.", ".$result['money']."<br>\n";
        }

        return $result;
    }


    function get_pay($t1,$t2) {
        $this->db->init('paylog','payid','pay');
        $this->db->setCriteria(new Criteria('buytime', $t1 , ">="));
        $this->db->criteria->add(new Criteria('buytime', $t2, "<"));
        $this->db->criteria->add(new Criteria('payflag', 1));

        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
        }

        $this->db->criteria->setFields("round(sum(money)/100) as smoney,count(*) as paycount,count(distinct buyid) as payusers");
        $this->db->queryObjects();
        $result = $this->db->getRow();
        $money=$result['smoney'];
        $paycount=$result['paycount'];
        $payusers=$result['payusers'];

        return array('money'=>$money,'paycount'=>$paycount,'payusers'=>$payusers);
    }


    function get_pay_hlist($date) {
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        $paylist=array();

        for ($i=0;$i<24;$i++) {
            if ($date==date("Y-m-d") && $i > date('G')) {
                break;
            }
            //$paysum_hour="";
            $key = "sumpay_hour";
            if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
                $key .= "@@".$uid;
            }
            $paysum_hour=$this->redis->hget($key,$date."-$i");
            if (!$paysum_hour) {
                $t1 = strtotime($date." $i:00:00");
                $t2 = strtotime($date." $i:59:59");
                $this->db->init('paylog','payid','pay');
                $this->db->setCriteria(new Criteria('buytime', $t1 , ">="));
                $this->db->criteria->add(new Criteria('buytime', $t2, "<="));
                $this->db->criteria->add(new Criteria('payflag', 1));



                if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
                    $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
                }

                $paysum_hour = $this->db->getSum("money")/100;
                if ($date.sprintf(" %02d",$i) < date("Y-m-d H")) {
                    $key=$date."-$i";
                    $this->redis->hset($key,$key,$paysum_hour);
                }
            }
            $paylist[] = $paysum_hour;
        }
        return implode(',',$paylist);
    }



    function total_qd_pay($t1,$t2,$query_cps_id=0) {
        $this->db->init('paylog','payid','pay');
        $this->db->setCriteria(new Criteria('buytime', $t1 , ">="));
        $this->db->criteria->add(new Criteria('buytime', $t2, "<="));
        $this->db->criteria->add(new Criteria('payflag', 1));
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
        }
        if ($query_cps_id) {
            $this->db->criteria->add(new Criteria('source', "$query_cps_id-%" , "like"));
        }
        $this->db->criteria->setFields("source,count(distinct buyid) as payusers,round(sum(money)/100) as qdpay");
        $this->db->criteria->setGroupby("source");
        $this->db->criteria->setSort("qdpay");
        $this->db->criteria->setOrder("DESC");
        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $result[]=$row;
        }
        return $result;
    }

    function total_uid_pay($t1,$t2) {
        $sql="select count(distinct buyid) as payusers,round(sum(money)/100) as qdpay,source  from jieqi_pay_paylog where buytime>=$t1 and buytime<=$t2 and payflag=1 group by source";
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $sql.=" and source like '$uid-%'";
        }

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
            $data[$cps_id]['payusers']+=$row['payusers'];
            $data[$cps_id]['qdpay']+=$row['qdpay'];
        }


//        $sql="select a.cps_id,c.name,count(distinct b.buyid) as payusers,round(sum(b.money)/100) as qdpay from jieqi_system_qdlist a,jieqi_pay_paylog b,jieqi_system_usersext c where b.source=a.qd and a.cps_id=c.uid and b.buytime>='$t1' and b.buytime<='$t2' and b.payflag = 1 group by a.cps_id";
//        $auth=$this->getAuth();
//        $uid=$auth['uid'];
//        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
//            $sql.=" and a.cps_id=$uid";
//        }
//        $res=$this->db->query($sql);
//        while ($row=$this->db->getRow($res)) {
//            $result[]=$row;
//        }


//        $this->db->init('paylog','payid','pay');
//        $this->db->setCriteria(new Criteria('buytime', $t1 , ">="));
//        $this->db->criteria->add(new Criteria('buytime', $t2, "<="));
//        $this->db->criteria->add(new Criteria('payflag', 1));
//
//        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
//            $this->db->criteria->add(new Criteria('source', "$uid-%" , "like"));
//        }
//        $this->db->criteria->setFields("source,count(distinct buyid) as payusers,round(sum(money)/100) as qdpay");
//        $this->db->criteria->setGroupby("source");
//        $this->db->criteria->setSort("qdpay");
//        $this->db->criteria->setOrder("DESC");
//        $res=$this->db->queryObjects();
//        $result=array();
//        while ($row=$this->db->getRow($res)) {
//            $result[]=$row;
//        }
        return $data;
    }

    function qdpay_total_daily($param) {
        if (!$param['date'])
            $param['date']=date("Y-m-d");
        if (!$param['tdate'])
            $param['tdate']=date("Y-m-d");
        $t1=strtotime($param['date']);
        $t2=strtotime($param['tdate'].' 23:59:59');


        $query_cps_id=intval($param['cps_id']);
        $qd1=intval($param['qd1']);
        $qd2=intval($param['qd2']);

        if ($qd1) {
            $qdmod=$this->model("qd");
            $qdlist=$qdmod->get_qdlist($qd1,$qd2);
        }
        else {
            $qdlist=array();
        }


        $qd_paylist=$this->total_qd_pay($t1,$t2,$query_cps_id);
        $regmod=$this->model('reg');

        $qd_click=$regmod->total_qd_click_pv($t1,$t2,$query_cps_id);
        $qd_reglist=$regmod->total_qd_reg($t1,$t2,$query_cps_id);

        $result=array();
        $data = array();
        $total_click=0;
        $total_pv=0;
        $total_reg=0;
        $total_payusers=0;
        $total_pay=0;
        foreach($qd_paylist as $qdpay) {
            $source=$qdpay['source']?$qdpay['source']:0;
            if ($qdlist && !in_array($source,$qdlist)) {
                continue;
            }
            $payusers=$qdpay['payusers'];
            $paymoney=$qdpay['qdpay'];

            $total_payusers += $payusers;
            $total_pay += $paymoney;

            $data[$qdpay['source']]=array('source'=>$source,'payusers'=>$payusers,'qdpay'=>$paymoney);
        }
        foreach($qd_click as $qdc) {
            $source=$qdc['qd']?$qdc['qd']:0;
            if ($qdlist && !in_array($source,$qdlist)) {
                continue;
            }
            $click=$qdc['qdclick'];
            $pv=$qdc['qdpv'];
            $data[$source]['click']=$click;
            $data[$source]['pv']=$pv;
            $data[$source]['source']=$source;
            $total_click += $click;
            $total_pv += $pv;
        }
        foreach($qd_reglist as $qdr) {
            $source=$qdr['source']?$qdr['source']:0;
            if ($qdlist && !in_array($source,$qdlist)) {
                continue;
            }
            $regcount=$qdr['qdreg'];
            $total_reg += $regcount;
            $data[$source]['regcount']=$regcount;
        }

        $sql="select a.*,b.qd from jieqi_system_usersext a,jieqi_system_qdlist b where a.uid=b.cps_id";
        $auth=$this->getAuth();
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $sql.=" and a.uid=".$auth['uid'];
        }
        $res=$this->db->query($sql);

        while ($u=$this->db->getRow($res)) {
            $data[$u['qd']]['name']=iconv("gbk","utf-8",$u['name']);
        }

        foreach($data as $d) {
            if ($d['source']) {
                $result[] = $d;
            }
        }

        $param['qd_data_rows']=$result;
        //print_r($result);
        $param['total_click']=$total_click;
        $param['total_pv']=$total_pv;
        $param['total_reg']=$total_reg;
        $param['total_payusers']=$total_payusers;
        $param['total_pay']=$total_pay;
        return $param;
    }


    function qdtotal($param) {
        if (!$param['date'])
            $param['date']=date("Y-m-d");
        if (!$param['tdate'])
            $param['tdate']=date("Y-m-d");
        $t1=strtotime($param['date']);
        $t2=strtotime($param['tdate'].' 23:59:59');

        $qd_paylist=$this->total_uid_pay($t1,$t2);



        $regmod=$this->model('reg');
        $qd_click=$regmod->total_uid_click_pv($t1,$t2);
        $qd_reglist=$regmod->total_uid_reg($t1,$t2);

//        print_r($qd_click);
//        print_r($qd_reglist);

        $result=array();
        $data = array();
        $total_pv=0;
        $total_click=0;
        $total_regcount=0;
        $total_payusers=0;
        $total_pay=0;
        //echo "1";
        foreach($qd_paylist as $qdpay) {
            $cps_id=$qdpay['cps_id']?$qdpay['cps_id']:0;
            $payusers=$qdpay['payusers'];
            $paymoney=$qdpay['qdpay'];
            $data[$qdpay['cps_id']]=array('cps_id'=>$cps_id,'payusers'=>$payusers,'qdpay'=>$paymoney);
            $total_pay += $qdpay['qdpay'];
            $total_payusers += $qdpay['payusers'];
        }
        //echo "2";

        foreach($qd_click as $qdc) {
            $cps_id=$qdc['cps_id']?$qdc['cps_id']:0;
            $click=$qdc['qdclick'];
            $pv=$qdc['qdpv'];
            $data[$cps_id]['click']=$click;
            $data[$cps_id]['pv']=$pv;
            $data[$cps_id]['cps_id']=$cps_id;
            $total_pv += $pv;
            $total_click += $click;
        }
        //echo "3";
        foreach($qd_reglist as $qdr) {
            $cps_id=$qdr['cps_id']?$qdr['cps_id']:0;
            $regcount=$qdr['regcount'];
            $data[$cps_id]['regcount']=$regcount;
            $total_regcount += $regcount;
            //echo "$cps_id,$regcount\n";
        }
        //echo "4";
        $sql="select * from jieqi_system_usersext";
        $auth=$this->getAuth();
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $sql.=" and uid=".$auth['uid'];
        }
        $res=$this->db->query($sql);

        while ($u=$this->db->getRow($res)) {
            $data[$u['uid']]['name']=iconv("gbk","utf-8",$u['name']);
        }
        //print_r($data);
        //echo "5";
        foreach($data as $d) {
            if ($d['cps_id']) {
                $result[] = $d;
            }
        }
        //echo "6";
        $param['qd_data_rows']=$result;
        $param['total_pv'] = $total_pv;
        $param['total_click'] = $total_click;
        $param['total_regcount'] = $total_regcount;
        $param['total_pay'] = $total_pay;
        $param['total_payusers'] = $total_payusers;
        //print_r($result);
        return $param;
    }

    function total_qd_pay_ext($qd) {
        $this->db->init('paylog','payid','pay');
        $this->db->setCriteria(new Criteria('`source`', $qd , "like"));
        $this->db->criteria->add(new Criteria('payflag', 1));
        $this->db->criteria->setFields("round(sum(money)/100) as smoney,count(*) as paycount,count(distinct buyid) as payusers");
        $this->db->queryObjects();
        $result = $this->db->getRow();
        return $result;
    }

    function pay_total_daily($param) {
        $auth=$this->getAuth();

        $uid=$auth['uid'];

        if (!$param['begintime'])
            $param['begintime']=date("Y-m-01");
        if (!$param['endtime'])
            $param['endtime']=date("Y-m-d");

        //echo "date1=".$param['begintime'];
        //exit();
        $t1=strtotime($param['begintime']);
        $t2=strtotime($param['endtime']." 23:59:59");
        $t=$t1;
        $paytotal=array();
        $totalmoney=0;
        while ($t<$t2) {
            $tx=$t+86400;
            $date=date("Y-m-d",$t);
            $dateh1=date("Ymd00",$t);
            $dateh2=date("Ymd23",$t);
            $this->db->init("paylog","payid","pay");



            $sql="select count(distinct buyid) as payusers,round(sum(money)/100) as qdpay,count(*) as paycount from jieqi_pay_paylog where ";
            $sql.="buytime>=$t and buytime<=$tx and payflag=1 ";

            if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
                $sql.=" and source like '$uid-%'";
            }


            //$res=$this->db->queryObjects();
            $res=$this->db->query($sql);
            $row=$this->db->getRow($res);

            $paydata=array(
                "payusers"=>$row['payusers'],
                'paymoney'=>$row['qdpay'],
                'paycount'=>$row['paycount'],
                'date'=>$date
            );
            $totalmoney+=$row['qdpay'];

            $this->db->init("qddata","id","system");

            $sql="select sum(pv) as totalpv,sum(click) as totalclick,sum(reg) as totalreg from jieqi_system_qddata where";
            $sql.=" time>=$dateh1 and time<=$dateh2 ";
            if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
                $sql.=" and qd like '$uid-%'";
            }
            $res=$this->db->query($sql);
            if (!$res) {
                echo "error";
                exit();
            }
            $row=$this->db->getRow($res);
            $paydata['pv']=$row['totalpv'];
            $paydata['click']=$row['totalclick'];
            $paydata['reg']=$row['totalreg'];
            if ($paydata[pv] || $paydata['click'] || $paydata['reg'] || $paydata['paymoney']) {
                $paytotal[] = $paydata;
            }
            $t+=86400;
        }
        $param['paytotal']=$paytotal;
        $param['totalmoney']=$totalmoney;
        return $param;
    }

    function paylist($param=array()) {
        if (!$param['begintime']) {
            $param['begintime'] = date("Y-m-d");
        }
        if (!$param['endtime']) {
            $param['endtime'] = date("Y-m-d");
        }
        $auth=$this->getAuth();
        $uid=$auth['uid'];

        $sql="select a.*,b.qd from jieqi_system_usersext a,jieqi_system_qdlist b where a.uid=b.cps_id";
        $auth=$this->getAuth();
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $sql.=" and a.uid=".$auth['uid'];
        }
        $userlist=array();
        $res=$this->db->query($sql);
        while ($u=$this->db->getRow($res)) {
            $userlist[$u['qd']]['name']=iconv("gbk","utf-8",$u['name']);
        }

        $this->db->init("paylog","payid","pay");
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->setCriteria(new Criteria("source", "$uid-%", "like"));
            $this->db->criteria->add(new Criteria('payflag', 1));
        }
        else {
            $this->db->setCriteria(new Criteria("payflag", 1));
        }

        if ($param['begintime']) {
            $t1=strtotime($param['begintime']);
            $this->db->criteria->add(new Criteria('buytime', $t1, ">="));
        }
        if ($param['endtime']) {
            $t2=strtotime($param['endtime']." 23:59:59");
            $this->db->criteria->add(new Criteria('buytime', $t2, "<"));
        }

        if(!$param['page']) $param['page'] = 1;
        $this->db->criteria->setSort('buytime');
        $this->db->criteria->setOrder('DESC');
        $pagesize=20;
        $this->db->criteria->setLimit($pagesize);
        $this->db->criteria->setStart(($param['page']-1) * $pagesize);

        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $row['paymoney'] = round($row['money']/100);
            $row['paytime'] = date("Y-m-d H:i:s",$row['buytime']);
            $row['buyname'] = iconv("GBK","UTF-8",$row['buyname']);
            $row['name'] = $userlist[$row['source']]['name'];
            $result[]=$row;
        }
        include_once(HLM_ROOT_PATH.'/lib/html/page.php');
        $jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$pagesize,$param['page']);
        $jumppage->setlink('', true, true);
        $param['url_jumppage'] = $jumppage->whole_bar();

        $param['pay_rows'] = $result;
        $param['url_jumppage'] = $jumppage->whole_bar();
        return $param;
    }
} 

?>