<?php 
/** 
 * ฒหตฅ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class profileModel extends Model{
	function main($param=array()) {
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=5) {
            header("location:/");
            exit();
        }

        $this->db->init("usersext","ueid","system");
        $this->db->setCriteria(new Criteria('uid', $uid));
        $res=$this->db->queryObjects();
        $row=$this->db->getRow($res);

        $param['username']=iconv("GBK","utf-8",$auth['useruname']);
        $param['bankaddress']=iconv("GBK","utf-8",$row['bankaddress']);
        $param['banknumber']=iconv("GBK","utf-8",$row['banknumber']);
        $param['payee']=iconv("GBK","utf-8",$row['payee']);
        $param['qq']=iconv("GBK","utf-8",$row['qq']);
        $param['wechat']=iconv("GBK","utf-8",$row['wechat']);
        $param['mobile']=iconv("GBK","utf-8",$row['mobile']);
        return $param;
	}

    function update($param) {
        global $jieqiLang, $jieqiConfigs, $jieqiModules;
        $auth=$this->getAuth();
        $uid=$auth['uid'];
        if ($auth['groupid']!=5) {
            header("location:/");
            exit();
        }

        $param['password']=trim($param['password']);
        if ($param['password']) {
            if ($param['password'] != $param['repassword'] && $param['repassword']) {
                $this->printfail( $jieqiLang['system']['password_not_equal']);
            }
            include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
            include_once(JIEQI_ROOT_PATH.'/class/users.php');
            $users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
            $pass=$users_handler->encryptPass($param['password']);

            $this->db->init("users","uid","system");
            $this->db->edit($uid,array("pass"=>$pass));

        }

        $this->db->init("usersext","uid","system");
        $extinfo=$this->db->get($uid);

        $usersext=array(
            'qq'=>trim($param['qq']),
            'wechat'=>trim($param['wechat']),
            'mobile'=>trim($param['mobile'])
            //'payee'=>iconv("UTF-8","GBK",trim($params['payee'])),
            //'bankaddress'=>iconv("UTF-8","GBK",trim($params['bankaddress'])),
            //'banknumber'=>iconv("UTF-8","GBK",trim($params['banknumber']))
        );

        $this->db->edit($uid,$usersext);

        if (!$extinfo['payee'] && trim($param['payee'])) {
            $this->db->edit($uid,array('payee'=>iconv("UTF-8","GBK",trim($param['payee']))));
        }
        if (!$extinfo['bankaddress'] && trim($param['bankaddress'])) {
            $this->db->edit($uid,array('bankaddress'=>iconv("UTF-8","GBK",trim($param['bankaddress']))));
        }
        if (!$extinfo['banknumber'] && trim($param['banknumber'])) {
            $this->db->edit($uid,array('banknumber'=>iconv("UTF-8","GBK",trim($param['banknumber']))));
        }

        header("location:index.php?controller=profile");
        exit();

    }


} 

?>