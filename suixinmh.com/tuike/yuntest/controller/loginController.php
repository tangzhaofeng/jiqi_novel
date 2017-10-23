<?php 
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class loginController extends Tuike_controller { 
  public $template_name = 'login'; 
  public $caching = false;
  public $theme_dir = false;

  public function main($params = array()){
    if($this->checklogin(true)) {
      ecs_header('location:'.$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']);exit;
    }
    $dataObj = $this->model('login');
    $data = $dataObj->main($params);
    $data['ujumpurl'] = urlencode($data['jumpurl']);
    $this->display($data);
  }


  /**
   * 查看协议
   * @return [type] [description]
   */
  public function agreement(){
    $this->display($data,'register_agreement');
  }

  /**
   * 注册_获取手机验证码
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function registerCo($params = array()){
    $params['phone']=isset($_POST['phone'])?trim($_POST['phone']):'';
    if( empty($params['phone']) )$this->printfail('手机号码不正确！!');

    //检查手机
    $chars = '/(^(13[0-9]|14[57]|15[012356789]|17[1678]|18[0-9])\d{8}$)|(^170[059]\d{7}$)/';
    if(empty($params['phone']) || !preg_match($chars, $params['phone']))$this->printfail('手机号码不正确！!');

    if($this->submitcheck()) {
      //检查验证码
      if(empty($params['checkcode']) || $params['checkcode'] != $_SESSION['jieqiCheckCode'])$this->printfail('图形验证码错误！');
      //发送验证码
      $this->model('login')->send_phone_reginster(array('phone'=>$params['phone']));
      $this->msgwin('发送成功！');
    }else{
      $this->printfail('手机号码不正确！!');
    }
  }

  /**
   * 注册_注册
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function registerNa($params = array()){
    if($this->submitcheck()) {
      //注册
      $dataObj=$this->model('login')->register($params);
    }else{
      $this->printfail('信息不正确！');
    }
  }

  /**
   * 自动执行每天的结算任务
   * @return [type] [description]
   */
  public function _runPayOurAuthCron($params){
    global $cache_redis;
    ini_set("max_execution_time", "50");
    ini_set("memory_limit", "908M");
    $PayOur= array(
      'name'=>'每天结算',
      'url'=>'http://tuike.youyuet.com/login?method=_runPayOurAuthCron',
      'maxTime'=>10,
      'redisName'=>'fdLe4FdfdeFQF44FEdd',
      'REDIS_HOST'=>'127.0.0.1',
      'runPayOurEnd'=>'runPayOurEnd',
      'REDIS_PORT'=>6379,
    );
    if( !function_exists('redis_conn') )include_once(JIEQI_ROOT_PATH."/include/total_service_funcs.php");
    if( !$cache_redis )redis_conn();
    if( !isset($params['name']) || strlen($params['name']) !== 13 || $cache_redis->get($PayOur['redisName'])!==$params['name'] ){
      die(json_encode(array('code'=>200,'message'=>iconv('GBK', 'UTF-8//IGNORE','链接超时'),'status'=>0))); 
    }
    $editNum=$this->model('login')->_runPayOurAuthCron();

    die(json_encode(array(
      'code'=>200,'message'=>iconv('GBK', 'UTF-8//IGNORE','运行结束'),
      'editNum'=>$editNum,'status'=>1
    ))); 

  }

  // 找回密码_发送短信验证码
  public function sendsms($params = array()){
    $this->model('login')->sendsms_editpass($params);
  }

  /**
   * 修改密码（未登陆的）
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function getpass($params = array()){
    $data = array();
    if($this->submitcheck()){
      $this->model('login')->edit_pass_login($params);
    }else{
      $this->printfail('信息不正确！');
    }
  }


}  

?>