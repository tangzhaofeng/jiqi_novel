<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class loginModel extends Model{
  //login form
  public function main($params){
    global $jieqiConfigs;
    jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

    if($this->submitcheck()){
      $this->loginDo($params);
    }
    $data = array();
    return $data;
  }
  
  /**
   * 登陆
   * @param  [type] $params [description]
   * @return [type]         [description]
   */
  public function loginDo($params){
    global $jieqiConfigs, $jieqiLang;
    $this->addLang('system', 'users');
    $jieqiLang['system'] = $this->getLang('system');

    jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
    // 已登陆的中转到
    if(isset($_SESSION['jieqiUserId']) && $_SESSION['jieqiUserId'] >0 ){
      ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'home'));
    }
    if(empty($params['username'])||empty($params['password']))$this->printfail('请输入帐号!');
    

    //未登录情况，输入帐号登录
    $params['username']=trim($params['username']);
         
    include_once(JIEQI_ROOT_PATH_APP.'/include/checklogin.php');

    if(isset($params['usecookie'])&&is_numeric($params['usecookie']))$params['usecookie']=intval($params['usecookie']);
    else $params['usecookie']=0;
    if(empty($params['checkcode'])) $params['checkcode']='';


    $islogin=jieqi_logincheck($params['username'], $params['password'], $params['checkcode'], $params['usecookie']);
    if($islogin==0){
      if (empty($params['jumpurl'])) {
        $params['jumpurl']=$this->geturl(JIEQI_MODULE_NAME, 'home');
      }
      ecs_header('Location: '.$this->geturl(JIEQI_MODULE_NAME, 'home'));
      // jieqi_jumppage($params['jumpurl'], LANG_DO_SUCCESS, sprintf($jieqiLang['system']['login_success'], jieqi_htmlstr($params['username'])));
    }else{
      //返回 0 正常, -1 用户名为空 -2 密码为空 -3 用户名或者密码为空
      //-4 用户名不存在 -5 密码错误 -6 用户名或密码错误 -7 校验码错误 -8 帐号已经有人登陆
      switch($islogin){
        case -99:
          jieqi_printfail( '你无法登陆该页面！' );
          break;
        case -1:
          jieqi_printfail($jieqiLang['system']['need_username']);
          break;
        case -2:
          jieqi_printfail($jieqiLang['system']['need_password']);
          break;
        case -3:
          jieqi_printfail($jieqiLang['system']['need_userpass']);
          break;
        case -4:
          jieqi_printfail($jieqiLang['system']['no_this_user']);
          break;
        case -5:
          jieqi_printfail($jieqiLang['system']['error_password']);
          break;
        case -6:
          jieqi_printfail($jieqiLang['system']['error_userpass']);
          break;
        case -7:
          jieqi_printfail($jieqiLang['system']['error_checkcode']);
          break;
        case -8:
          jieqi_printfail($jieqiLang['system']['other_has_login']);
          break;
        default:
          jieqi_printfail($jieqiLang['system']['login_failure']);
          break;
      }
    }
    
  }
  

  /**
   * 发短信
   * @param  [type] $phone_id [description]
   * @param  [type] $msg      [description]
   * @return [type]           [description]
   */
  private function send_phone($phone_id,$msg){   

    // @setcookie('Msm',$phone_id.' '.' '.$_SESSION['sms_n'].' '.' '.$msg , $_SESSION['q_time']+9999, '/',  JIEQI_COOKIE_DOMAIN, 0);
    // return 1;

    // $msg = iconv('utf-8','gb2312',$msg."\r\n");
    $URL = "http://sms3.mobset.com/SDK/Sms_Send.asp?CorpID=303014&LoginName=Admin&Passwd=Rb105112&send_no=".$phone_id."&Timer=&LongSms=1&msg=" .rawurlencode($msg);
     
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $URL);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $back = curl_exec($curl);
    curl_close($curl);

    $back_arr = explode(',', $back);
    if( $back_arr['0'] == 1 ){
      return true;
    }else{
      return false;
    }   
  }


} 
?>