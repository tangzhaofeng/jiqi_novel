<?php 
/** 
 * 菜单 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class homeModel extends Model{


  function main($param=array()) {
     
  }

  /**
   * 所属经理
   * @return [type] [description]
   */
  function getManager(){
    $defaultMa=37;// 37 14
    $maid=$_SESSION['jieqiUserMauid']>0?$_SESSION['jieqiUserMauid']: $defaultMa;
    $this->db->init('users','uid','tuike');
    $mainfo=$this->db->get( $maid );
    if( !$mainfo ){
      $mainfo=$this->db->get(  $defaultMa );
    }

    $setting=unserialize($mainfo['setting']);
    if($setting){
      $_SESSION['co_name']=$setting['name'];
      $_SESSION['co_qq']=$setting['qq'];
      $_SESSION['co_phone']=$setting['phone'];
      $_SESSION['co_img']='/super'.$setting['img'];
    }else{
      $_SESSION['co_name']='';
      $_SESSION['co_qq']='';
      $_SESSION['co_phone']='';
      $_SESSION['co_img']='';
    }
  }

  /**
   * 编辑推客信息
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function editPass($params=array()){
    $params['oldpass']=isset($params['oldpass'])?trim($params['oldpass']):'';
    $params['oldpass1']=isset($params['oldpass1'])?trim($params['oldpass1']):'';
    $params['oldpass2']=isset($params['oldpass2'])?trim($params['oldpass2']):'';
    if( $params['oldpass'] === '' ||
      $params['oldpass1'] === '' ||
      $params['oldpass2'] === '' 
     )$this->printfail('信息不正确！');
    if( $params['oldpass1'] !== $params['oldpass2'] )$this->printfail('再个新密码不一样！');


    $this->db->init('users','uid','tuike');
    $users=$this->db->get($_SESSION['jieqiUserId']);
    if( md5($params['oldpass']) !== $users['pass'] )$this->printfail('旧密码错误，请联系您的客户经理！');

    $data=array('pass'=>md5($params['oldpass2']));
    $this->db->edit($_SESSION['jieqiUserId'],$data);

    if( $this->db->getAffectedRows() > 0 ){
      $this->msgwin('修改成功！');
      die(json_encode(array('status'=>'OK','jumpurl'=>$this->geturl(JIEQI_MODULE_NAME,'server','home').'?payflag=2')));
    }else{
      $this->printfail('修改失败！');
    }
  }

  /**
   * 编辑推客信息
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function setUserUpdate(){
    if( !isset($_SESSION['jieqiUserId']) || $_SESSION['jieqiUserId'] <=0 )return false;

    $this->db->init('users','uid','tuike');
    $this->db->setCriteria(new Criteria('uid', $_SESSION['jieqiUserId']));
    $this->db->queryObjects();
    $user=$this->db->getObject();

    jieqi_setusersession($user);
    $_SESSION['jieqiUserSet']['updateTime']=JIEQI_NOW_TIME;

  }




}






?>