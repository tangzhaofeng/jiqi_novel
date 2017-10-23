<?php
//namespace publicAccount;
/**
 * Created by IntelliJ IDEA.
 * User: wjr
 * Date: 16-6-14
 * Time: 下午4:21
 */
require('../libs/Utils.class.php');
require('../libs/Props.php');
require('../libs/RequestHandler.class.php');
require('../libs/ResponseHandler.class.php');
require('../libs/HttpClient.php');
require('../libs/Log.php');

class Handler {
    private $resHandler = null;
    private $reqHandler = null;
    private $pay = null;
    private $props = null;
    private $url = null;

    public function __construct(){
        $this->Request();
    }

    public function Request(){
        $this->resHandler = new ResponseHandler();
        $this->reqHandler = new RequestHandler();
        $this->pay = new HttpClient();
        $this->props = new Props();
        $this->reqHandler->setKey($this->props->K('SIGN_KEY'));
    }

    public function preHandler(){
        $this->reqHandler->setReqParams($_POST,array('method'));
        $this->reqHandler->setParameter('mch_id',$this->props->K('MCH_ID'));//必填项，商户号，由梓微兴分配
        $this->reqHandler->setParameter('nonce_str',mt_rand(time(),time()+rand()));//随机字符串，必填项，不长于 32 位
    }

    public function afterHandler(){
        if($this->url == null){
            echo json_encode(array('status'=>500,'msg'=> '没有设置url'));
            exit();
        }
        $this->reqHandler->createSign();
        sysdebug($this->reqHandler->getAllParameters());
        $data = Utils::to($this->reqHandler->getAllParameters());
        $this->pay->setReqContent($this->url,$data);
        if($this->pay->invoke()){
            sysdebug($this->pay->getResContent());
            $xml = new SimpleXMLElement($this->pay->getResContent());
            echo $xml->asXML();
        }else{
            echo json_encode(array('status'=>500,'msg'=>'Response Code:'.$this->pay->getResponseCode().' Error Info:'.$this->pay->getErrInfo()));
        }
    }

    public function index(){
        $method = isset($_REQUEST['method'])?$_REQUEST['method']:'notFound';
        $this->preHandler();
        call_user_func(array('Handler',$method));
        $this->afterHandler();
    }
    public function notFound(){
        sysdebug('notFound');
    }

    //公众帐号支付测试
    public function payTest(){
        $notify_url = 'http://'.$_SERVER['HTTP_HOST'];
        $this->reqHandler->setParameter('notify_url',$notify_url.'/common/notify.php');
        $this->reqHandler->setParameter('trade_type', $_POST['trade_type']);
        $this->url = $this->props->K('PAY_URL');
    }



    //支付查询
    public function payQuery(){
        $this->url = $this->props->K('QUERY_URL');
        $this->reqHandler->setParameter('out_trade_no', $_POST['out_trade_no']);
        sysdebug('payQuery');
    }

    //退款
    public function payRefund(){
        $this->url = $this->props->K('REFUND_URL');
    }

    //退款查询
    public function payRefundQuery(){
        $this->url = $this->props->K('QUERY_REFUND_URL');
    }
}
sysdebug("main index..");
$handler = new Handler();
$handler->index();