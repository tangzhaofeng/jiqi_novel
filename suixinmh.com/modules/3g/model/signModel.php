<?php

class signModel extends Model {

	public function main($params) {
        $auth=$this->getAuth();
        $last_date=date("Y-m-d",time()-86400*7);

        $this->db->init("sign","id","system");
        $this->db->setCriteria(new Criteria( 'uid', $auth['uid']));
        $this->db->criteria->add ( new Criteria ( 'sign_date', $last_date,">=" ));
        $this->db->criteria->setSort('sign_date');
        $this->db->criteria->setOrder('desc');

        $sign_list=$this->db->lists();

        $sign_days=0;
        $total_award = 0;

        $s1=$sign_list[0];

        $signed = 0;
        $today=date("Y-m-d");
        if (!$s1 || $s1['sign_date']!=$today) {
            $sign_data[$today]=array(
                'sign_date'=>$today,
                'award'=>'随机',
                'total_award'=>'-',
                'status'=>'可签到'
            );
        }
        else {
            $sign_data=array();
        }

        $sign_info=array();
        foreach($sign_list as $sign) {
            if ($sign['sign_date']==$today) {
                $signed = 1;
            }
            if (!$sign_days) {
                $sign_days=$sign['sign_days'];
                $total_award=$sign['total_award'];
            }
            $sign_info[$sign['sign_date']]=$sign;
        }

        $time=time();
        for ($d=0;$d<4;$d++) {
            $date=date("Y-m-d",$time);
            $time-=86400;
            if ($sign_data[$date]) {
                continue;
            }
            if ($sign_info[$date]) {
                $sign=$sign_info[$date];
                $sign_data[$date]=array(
                    'sign_date'=>$date,
                    'award'=>$sign['award'],
                    'total_award'=>$sign['total_award'],
                    'status'=>'已签到'
                );
            }
            else {
                $sign_data[$date]=array(
                    'sign_date'=>$date,
                    'award'=>'-',
                    'total_award'=>'-',
                    'status'=>'未签到'
                );
            }
        }
        $params['my_sliver']=$auth['esilvers'];
        $params['sign_days']=$sign_days;
        $params['total_award']=$total_award;
        $params['sign_data']=$sign_data;
        $params['signed']=$signed;
        return $params;
    }

    public function sign($params) {
        $auth=$this->getAuth();
        $today=date("Y-m-d");

        $this->db->init("sign","id","system");
        $this->db->setCriteria(new Criteria( 'uid', $auth['uid']));
        $this->db->criteria->setSort('sign_date');
        $this->db->criteria->setOrder('desc');
        $signlist=$this->db->lists();

        $last_sign=$signlist[0];
        if (!$last_sign) {
            $total_award=0;
            $sign_days=0;
        }
        else {
            $total_award=$last_sign['total_award'];
            $sign_days=$last_sign['sign_days'];
        }

        if ($last_sign && $last_sign['sign_date']==$today) {
            $this->printfail('亲，您今天已经签到过了，每天只能签到一次哦');
            exit();
        }

        $award=rand(8,15);
        $users_handler =  $this->getUserObject();
        $ret=$users_handler->income($auth['uid'], $award, 1, 0, 0);
        if (!$ret) {
            $this->printfail('发放书券失败');
            exit();
        }
        $insert_arr=array(
            'uid'=>$auth['uid'],
            'sign_date'=>$today,
            'award'=>$award,
            'total_award'=>$total_award+$award,
            'sign_days'=>$sign_days+1
        );
        $this->db->add($insert_arr);
        header("location:/sign/mysign");
        exit();

    }
}

?>