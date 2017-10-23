<?php
include 'config.php';
include 'lib/WxPay.Api.php';
include 'lib/WxPay.Notify.php';
//include 'log.php';

//初始化日志
//$logHandler=new CLogFileHandler('/www/logs/fy.pay.log');
//$log=Log::Init($logHandler,15);

class PayNotifyCallBack extends WxPayNotify
{
    //查询订单
    public function Queryorder($transaction_id)
    {
        $input=new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result=WxPayApi::orderQuery($input);

//        Log::DEBUG("query:".json_encode($result));
//        file_put_contents('a.txt',json_encode($result));

        if($result['appid'] != WxPayConfig::APPID){
            return FALSE;
        }
        if($result['mch_id'] != WxPayConfig::MCHID){
            return FALSE;
        }
        if(array_key_exists("return_code",$result) && array_key_exists("result_code",$result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS"){
            $result['fy_sub']=md5($result['openid'].'fyeshuku.com,');
            $rs=@file_get_contents(JIEQI_WXPAY_NOTIFY.'?'.http_build_query($result));
            if(!empty($rs)){
                if($rs=='succ'){
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    //重写回调处理函数
    public function NotifyProcess($data,&$msg)
    {
//        Log::DEBUG("call back:".json_encode($data));
//        file_put_contents('a.txt',json_encode($data));
        $notfiyOutput=array();
        if(!array_key_exists("transaction_id",$data)){
            $msg="输入参数不正确";
            return FALSE;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg="订单查询失败";
            return FALSE;
        }
        return TRUE;
    }
}
//Log::DEBUG("begin notify");
$notify=new PayNotifyCallBack();
$notify->Handle(FALSE);