<?php

/**
 * Created by IntelliJ IDEA.
 * User: wjr
 * Date: 16-7-27
 * Time: 上午10:25
 */
class Props{
    private $cfg = array(
        'PAY_URL'=>'https://api.zwxpay.com/pay/unifiedorder',//提交订单URL
        'QUERY_URL'=>'https://api.zwxpay.com/pay/orderquery',//查询订单URL
        'REFUND_URL'=>'https://api.zwxpay.com/secapi/pay/refund',//退款URL
        'QUERY_REFUND_URL'=>'https://api.zwxpay.com/pay/refundquery',//查询退款URL
        'MCH_ID'=>'15124229',
        'SIGN_KEY'=>'7ff9318e7d0cd6238ae999d08a11446a'
    );

    public function K($cfgName){
        return $this->cfg[$cfgName];
    }
}