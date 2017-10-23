<?php
include_once(JIEQI_ROOT_PATH.'/lib/mail/mail.php');
class MyMail extends JieqiMail {
function MyMail(){
}
function sendmail($to,$subject,$content,$params=array()){
if(!$params){
$this->addConfig('system','configs');
$jieqiConfigs['system'] = $this->getConfig('system','configs');
$params['mailtype'] = $jieqiConfigs['system']['mailtype'];
$params['maildelimiter'] = $jieqiConfigs['system']['maildelimiter'];
$params['mailfrom'] = $jieqiConfigs['system']['mailfrom'];
$params['mailserver'] = $jieqiConfigs['system']['mailserver'];
$params['mailport'] = $jieqiConfigs['system']['mailport'];
$params['mailauth'] = $jieqiConfigs['system']['mailauth'];
$params['mailuser'] = $jieqiConfigs['system']['mailuser'];
$params['mailpassword'] = $jieqiConfigs['system']['mailpassword'];
}
$this->JieqiMail($to,$subject,$content,$params);
parent::sendmail();
}
}
