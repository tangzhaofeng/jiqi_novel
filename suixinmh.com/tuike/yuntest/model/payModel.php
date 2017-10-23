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
        $date = date("Y-m-d",$t1);
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
        $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
        $this->db->criteria->add(new Criteria('rettime', $t2, "<"));
        $this->db->criteria->add(new Criteria('payflag', 1));

        $this->db->criteria->setFields("round(sum(money)/100) as smoney,count(*) as paycount,count(distinct buyid) as payusers");
        $this->db->queryObjects();
        $result = $this->db->getRow();
        $money=$result['smoney'];
        $paycount=$result['paycount'];
        $payusers=$result['payusers'];

        return array('money'=>$money,'paycount'=>$paycount,'payusers'=>$payusers);
    }


    function get_pay_hlist($date) {
        $paylist=array();

        for ($i=0;$i<24;$i++) {
            if ($date==date("Y-m-d") && $i > date('G')) {
                break;
            }
            //$paysum_hour="";
            $paysum_hour=$this->redis->hget('sumpay_hour',$date."-$i");
            if (!$paysum_hour) {
                $t1 = strtotime($date." $i:00:00");
                $t2 = strtotime($date." $i:59:59");
                $this->db->init('paylog','payid','pay');
                $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
                $this->db->criteria->add(new Criteria('rettime', $t2, "<="));
                $this->db->criteria->add(new Criteria('payflag', 1));
                $paysum_hour = $this->db->getSum("money")/100;
                if ($date.sprintf(" %02d",$i) < date("Y-m-d H")) {
                    $key=$date."-$i";
                    $this->redis->hset('sumpay_hour',$key,$paysum_hour);
                }
            }
            $paylist[] = $paysum_hour;
        }
        return implode(',',$paylist);
    }



    function total_qd_pay($t1,$t2) {
        $this->db->init('paylog','payid','pay');
        $this->db->setCriteria(new Criteria('rettime', $t1 , ">="));
        $this->db->criteria->add(new Criteria('rettime', $t2, "<="));
        $this->db->criteria->add(new Criteria('payflag', 1));
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

    function qdpay_total_daily($param) {
        if (!$param['date'])
            $param['date']=date("Y-m-d");
        if (!$param['tdate'])
            $param['tdate']=date("Y-m-d");
        $t1=strtotime($param['date']);
        $t2=strtotime($param['tdate'].' 23:59:59');

        $qd_paylist=$this->total_qd_pay($t1,$t2);
        $regmod=$this->model('reg');

        $qd_click=$regmod->total_qd_click_pv($t1,$t2);
        $qd_reglist=$regmod->total_qd_reg($t1,$t2);
        //print_r($qd_reglist);

        $result=array();
        foreach($qd_paylist as $qdpay) {
            $source=$qdpay['source']?$qdpay['source']:0;
            $payusers=$qdpay['payusers'];
            $paymoney=$qdpay['qdpay'];
            $data[$qdpay['source']]=array('source'=>$source,'payusers'=>$payusers,'qdpay'=>$paymoney);
        }
        foreach($qd_click as $qdc) {
            $source=$qdc['qd']?$qdc['qd']:0;
            $click=$qdc['qdclick'];
            $pv=$qdc['qdpv'];
            $data[$source]['click']=$click;
            $data[$source]['pv']=$pv;
            $data[$source]['source']=$source;
        }

        foreach($qd_reglist as $qdr) {
            $source=$qdr['source']?$qdr['source']:0;
            $regcount=$qdr['qdreg'];
            $data[$source]['regcount']=$regcount;
        }

        foreach($data as $d) {
            $result[]=$d;
        }
        $param['qd_data_rows']=$result;
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
} 

?>