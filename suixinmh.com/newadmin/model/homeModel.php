<?php 
/** 
 * ²Ëµ¥ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class homeModel extends Model{


	function main($param=array()) {
        $t1=strtotime(date("Y-m-d"));
        $t2=time();
        $paymod = $this->model("pay");
        $paydata=$paymod->get_pay($t1,$t2);
        $paydata_y=$paymod->get_pay($t1-86400,$t2-86400);


        $regmod = $this->model("reg");
        $regcount=$regmod->get_regcount($t1,$t2);
        $regcount_y=$regmod->get_regcount($t1-86400,$t2-86400);

        $param['total_pay']=$paydata['money'];
        $param['paycount']=$paydata['paycount'];
        $param['total_pay_users']=$paydata['payusers'];
        $param['total_reg']=$regcount;

        $param['total_pay_y']=$paydata_y['money'];
        $param['paycount_y']=$paydata_y['paycount'];
        $param['total_pay_users_y']=$paydata_y['payusers'];
        $param['total_reg_y']=$regcount_y;

        $param['pv'] = $regmod->get_pv($t1,$t2);
        $param['pv_y'] = $regmod->get_pv($t1-86400,$t2-86400);

        $param['click'] = $regmod->get_click($t1,$t2);
        $param['click_y'] = $regmod->get_click($t1-86400,$t2-86400);

        $param['today_paylist'] = $paymod->get_pay_hlist(date("Y-m-d"));
        $param['yesterday_paylist'] = $paymod->get_pay_hlist(date("Y-m-d",time()-86400));

        $param['weekly_data_rows'] = $paymod->pay_weekly_total();


        if ($param['total_pay_users'])
            $param['arpu'] = round($param['total_pay'] * 100 / $param['total_pay_users']) / 100;
        else
            $param['arpu'] = 0;

        if ($param['click'])
            $param['click_money'] = round($param['total_pay'] * 100 / $param['click']) / 100;
        else
            $param['click_money'] = 0;

        if ($param['pv'])
            $param['pv_money'] = round($param['total_pay'] * 1000 / $param['pv']) / 1000;
        else
            $param['pv_money'] = 0;

        if ($param['total_pay_y'])
            $param['money_percent'] = round($param['total_pay'] * 10000 / $param['total_pay_y']) / 100;
        else
            $param['money_percent'] = 0;

        if ($param['total_reg_y']>0)
            $param['reg_percent'] = round($param['total_reg']*10000/$param['total_reg_y'])/100;
        else
            $param['reg_percent'] = 0;

        if ($param['total_pay_users_y']>0)
            $param['payusers_percent'] = round($param['total_pay_users']*10000/$param['total_pay_users_y'])/100;
        else
            $param['payusers_percent'] = 0;

        if ($param['pv_y']>0)
            $param['pv_percent'] = round($param['pv']*10000/$param['pv_y'])/100;
        else
            $param['pv_percent'] = 0;

        if ($param['click_y']>0)
            $param['click_percent'] = round($param['click']*10000/$param['click_y'])/100;
        else
            $param['click_percent'] = 0;
        return $param;
	}


    function get_pay($t1,$t2) {
        $this->db->init('paylog','payid','pay');
        $this->db->setCriteria(new Criteria('buytime', $t1 , ">="));
        $this->db->criteria->add(new Criteria('buytime', $t2, "<"));
        $this->db->criteria->add(new Criteria('payflag', 1));

        $this->db->criteria->setFields("round(sum(money)/100) as smoney,count(*) as paycount,count(distinct buyid) as payusers");
        $this->db->queryObjects();
        $result = $this->db->getRow();
        $money=$result['smoney'];
        $paycount=$result['paycount'];
        $payusers=$result['payusers'];

        return array('money'=>$money,'paycount'=>$paycount,'payusers'=>$payusers);
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

    function get_pay_hlist($date) {
        //$this->init_redis();
        $paylist=array();

        for ($i=0;$i<24;$i++) {
            if ($date==date("Y-m-d") && $i > date('G')) {
                break;
            }
            $paysum_hour=$this->redis->hget('sumpay_hour',$date."-$i");
            if (!$paysum_hour) {
                $t1 = strtotime($date." $i:00:00");
                $t2 = strtotime($date." $i:59:59");
                $this->db->init('paylog','payid','pay');
                $this->db->setCriteria(new Criteria('buytime', $t1 , ">="));
                $this->db->criteria->add(new Criteria('buytime', $t2, "<="));
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

//    function init_redis() {
//        if (!$this->redis) {
//            include_once(JIEQI_ROOT_PATH . '/lib/database/redis.php');
//            $this->redis = new MyRedis(JIEQI_REDIS_HOST, JIEQI_REDIS_PORT);
//        }
//    }
} 

?>