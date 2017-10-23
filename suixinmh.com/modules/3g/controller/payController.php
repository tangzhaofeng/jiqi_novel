<?php
/**
 * 支付控制器 *
 * @copyright   Copyright(c) 2014
 * @author      zhangxue
 * @version     1.0
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
include_once(JIEQI_ROOT_PATH."/include/funsystem.php");
class payController extends chief_controller {

        public $caching = false;
		public $theme_dir = false;
		//public $cacheid = 'fff';
		//public $cachetime=5;
		//充值首页
		public function main($params){
            $params['in_weixin'] = 1;
            switch(get_user_agent()) {
                case 0:
                    $pay_wechat = 1;
                    $pay_alipay=1;
                    break;
                case 1:
                    $pay_wechat = 1;
                    $pay_alipay = 0;
                    break;
                case 2:
                    $pay_wechat = 0;
                    $pay_alipay = 1;
                    break;
            }

            if ($pay_wechat == 1 && $pay_alipay == 0) {
                header("location:/pay/wechat/");
                exit();
            }
            $params['pay_wechat'] = $pay_wechat;
            $params['pay_alipay'] = $pay_alipay;
			$this->display($params);
		}
		//畅天游 联通
		public function unicom($params){
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'unicom', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					$data = $dataObj->unicom($params, $jieqiPayset);
					$this->display($data,'unicomcheck');
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			$this->display($jieqiPayset['unicom']);
		}
		//畅天游验证 联通
		public function yanunicom($params){
			$this->theme_dir = false;
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'unicom', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    $this->checklogin();
				$dataObj = $this->model('pay');
				$data = $dataObj->yanunicom($params, $jieqiPayset);
				$this->display($data,'paypop');
			}
		}
		//畅天游通知 联通
		public function checkunicom($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'unicom', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$dataObj->checkunicom($params, $jieqiPayset);
		}
		//电信
		public function telecom($params){
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'telecom', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
				$this->theme_dir = false;
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					$data = $dataObj->telecom($params, $jieqiPayset);
					$this->display($data,'paypop');
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			$this->display($jieqiPayset['telecom']);
		}
		/**
		 * 悦蓝 H5版：创建支付
		 */
		public function mobile2($params){
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'mobile', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					$data = $dataObj->mobile($params, $jieqiPayset);
//					$this->display($data,'mobilecodeyan');
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			$this->display($jieqiPayset['mobile'],'mobile');
		}
		/**
		 * 悦蓝 H5版：回调接口
		 */
		public function checkmobile($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'mobile', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$dataObj->checkmobile($params, $jieqiPayset);
		}
		//天下付
        public function txfpay($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'txfpay_wap', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    $this->checklogin();
				$dataObj = $this->model('pay');
				$data = $dataObj->txfpay($params, $jieqiPayset);
				$this->display($data,'confirm');
			}
			$this->display($jieqiPayset['txfpay'],'txfpay');
        } 
		public function checktxfpay($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'txfpay_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$dataObj->checktxfpay($params, $jieqiPayset);
		}
//		
//		//支付宝
//      public function alipay($params = array()) {
//		    global $jieqiPayset;
//	        jieqi_getconfigs('pay', 'alipay_wap', 'jieqiPayset');
//			//提交数据
//		    if($this->submitcheck()){
//			    $this->checklogin();
//				$dataObj = $this->model('pay');
//				//$data = 
//				$dataObj->alipay($params, $jieqiPayset);
//				//$this->display($data,'confirm');
//			}
//			$this->display($jieqiPayset['alipay'],'alipay');
//      } 
//		public function checkalipayw($params = array()) {
//		    global $jieqiPayset;
//	        jieqi_getconfigs('pay', 'alipay', 'jieqiPayset');
//			$dataObj = $this->model('pay');
//			$dataObj->checkalipayw($params, $jieqiPayset);
//		}
		//支付宝
        public function alipay($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'alipay_wap', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					$dataObj->alipay($params, $jieqiPayset);
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			$auth = $this->getAuth();
			/*if($auth['uid'] && $auth['vip']>0){
			    unset($jieqiPayset['alipay_wap']['paylimit']['900']);
				unset($jieqiPayset['alipay_wap']['paylimit']['2000']);
			}else unset($jieqiPayset['alipay_wap']['paylimit']['3000']);*/
			$this->display($jieqiPayset['alipay_wap']);
        } 
		public function checkalipay($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'alipay_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$data = $dataObj->checkalipay($params, $jieqiPayset);
            if ($_SESSION['jieqi_readurl']) {
                $data['readurl'] = $_SESSION['jieqi_readurl'];
            }
            else {
                $data['readurl'] = JIEQI_HTTP_HOST;
            }
			$this->display($data,'paypop');
		}
		public function alipay_notify($params = array()){
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'alipay_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$dataObj->alipay_notify($params, $jieqiPayset);
		}
		/**
		 * 掌维
		 */
        public function mobile($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'zhangwei', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					$dataObj->zhangwei($params, $jieqiPayset);
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			$this->display($jieqiPayset['zhangwei']);
        } 
		/**
		 * 掌维同步跳转
		 */
		public function checkzhangwei($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'zhangwei', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$data = $dataObj->checkzhangwei($params, $jieqiPayset);
			$this->display($data,'paypop');
		}
		/**
		 * 掌维异步通知
		 */
		public function zhangwei_notify($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'zhangwei', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$dataObj->zhangwei_notify($params, $jieqiPayset);
		}
		//网银
        public function yeepay($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'yeepay_wap', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					$dataObj->yeepay($params, $jieqiPayset);
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			$this->display($jieqiPayset['yeepay_wap']);
        } 
		public function checkyeepay($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'yeepay_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$data = $dataObj->checkyeepay($params, $jieqiPayset);
			$this->display($data,'paypop');
		}
		public function yeepay_notify($params = array()){
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'yeepay_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$dataObj->yeepay_notify($params, $jieqiPayset);
		}
		/**
		 * 星启天：微信
		 */
		public function wechat($params = array()){
			//print_r($params);
            //echo 'money='.$params['money'];
			$user = $this->getAuth();
			if (!$user['uid'] && is_weixin()) {
				header("location:".$GLOBALS['jieqiModules'][JIEQI_MODULE_NAME]['url']."/wxlogin/?jumpurl=/pay/wechat/");
				exit();
			}
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'wechat_wap', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
				//echo "---------0----------<br>";
			    if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					//echo "---------1--------<br>";
					if(isset($_SERVER['ESHUKU_SUB'])){
						//echo "---------2--------<br>";
						$jieqiPayset['wechat_wap']['wx_result'] = $dataObj->wechat_sub($params, $jieqiPayset);
					}else{
						$dataObj->wechat($params, $jieqiPayset);
					}
				}else{
					header('Location: '.$this->geturl('3g', 'login'));
				}
			}
			foreach($jieqiPayset['wechat_wap']['paylimit'] as $money=>$gold) {
				$z=$gold-$money*100;
				$jieqiPayset['wechat_wap']['zk'][$money] = round($money*100/$gold*100)/10;
			}
			if ($params['baonian'])
				$this->display($jieqiPayset, 'pay_wechat_baonian');
			else
				$this->display($jieqiPayset['wechat_wap']);
        }

        /*
         * 梓微兴支付
         */
        public function wechat_zwx($params = array()){
            global $jieqiPayset;
            jieqi_getconfigs('pay', 'wechat_zwx', 'jieqiPayset');
            //提交数据
            if($this->submitcheck()){
                if($this->checklogin(true)){
					$dataObj = $this->model('pay');
					if(isset($_SERVER['ESHUKU_SUB'])){
						$jieqiPayset['wechat_wap']['wx_result'] = $dataObj->wechat_sub($params, $jieqiPayset);
					}else{
						$dataObj->wechat($params, $jieqiPayset);
					}
                }else{
                    header('Location: '.$this->geturl('3g', 'login'));
                }
            }
            $this->display($jieqiPayset['wechat_zwx'],'pay_wechat_zwx');
        }
		/**
		 * 微信 异步
		 */
		public function wechat_notify($params = array()){
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'wechat_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			if(isset($_SERVER['ESHUKU_SUB'])){
				$dataObj->wechat_sub_notify($params, $jieqiPayset);
			}else{
				$dataObj->wechat_notify($params, $jieqiPayset);
			}
		}
		/**
		 * 微信 同步跳转
		 */
		public function checkwechat($params = array()) {
		    global $jieqiPayset;
	        jieqi_getconfigs('pay', 'wechat_wap', 'jieqiPayset');
			$dataObj = $this->model('pay');
			$data = $dataObj->checkwechat($params, $jieqiPayset);
			if ($_SESSION['jieqi_readurl']) {
				$data['readurl'] = $_SESSION['jieqi_readurl'];
			}
			else {
				$data['readurl'] = "http://".JIEQI_HTTP_HOST;
			}
			$this->display($data,'paypop');
		}


	/**
	 * 微信 异步
	 */
	public function wechat_zwx_notify($params = array()){
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'wechat_zwx', 'jieqiPayset');
		$dataObj = $this->model('pay');
		$dataObj->wechat_zwx_notify($params, $jieqiPayset);
	}
	/**
	 * 微信 同步跳转
	 */
	public function checkwechat_zwx($params = array()) {
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'wechat_wap', 'jieqiPayset');
		$dataObj = $this->model('pay');
		$data = $dataObj->checkwechat($params, $jieqiPayset);
		if ($_SESSION['jieqi_readurl']) {
			$data['readurl'] = $_SESSION['jieqi_readurl'];
		}
		else {
			$data['readurl'] = "http://".JIEQI_HTTP_HOST;
		}
		$this->display($data,'paypop');
	}

	/**
	 * 星启天：QQ
	 */
	public function qq($params = array()){
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'qq_wap', 'jieqiPayset');
		//提交数据
		if($this->submitcheck()){
			if($this->checklogin(true)){
				$dataObj = $this->model('pay');
				$dataObj->qq($params, $jieqiPayset);
			}else{
				header('Location: '.$this->geturl('3g', 'login'));
			}
		}

		/*$auth = $this->getAuth();
        if($auth['uid'] && $auth['vip']>0){
            unset($jieqiPayset['wechat_wap']['paylimit']['900']);
            unset($jieqiPayset['wechat_wap']['paylimit']['2000']);
        }else unset($jieqiPayset['wechat_wap']['paylimit']['3000']);*/
		$this->display($jieqiPayset['qq_wap']);
	}
	/**
	 * QQ 异步
	 */
	public function qq_notify($params = array()){
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'qq_wap', 'jieqiPayset');
		$dataObj = $this->model('pay');
		$dataObj->qq_notify($params, $jieqiPayset);
	}
	/**
	 * QQ 同步跳转
	 */
	public function checkqq($params = array()) {
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'qq_wap', 'jieqiPayset');
		$dataObj = $this->model('pay');
		$data = $dataObj->checkqq($params, $jieqiPayset);
		$this->display($data,'paypop');
	}


	/**
	 * rdo
	 */
	public function rdo($params = array()){
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'rdo_wap', 'jieqiPayset');
		//提交数据
		if($this->submitcheck()){
			if($this->checklogin(true)){
				$dataObj = $this->model('pay');
				$dataObj->rdo($params, $jieqiPayset);
			}else{
				header('Location: '.$this->geturl('3g', 'login'));
			}
		}

		/*$auth = $this->getAuth();
        if($auth['uid'] && $auth['vip']>0){
            unset($jieqiPayset['wechat_wap']['paylimit']['900']);
            unset($jieqiPayset['wechat_wap']['paylimit']['2000']);
        }else unset($jieqiPayset['wechat_wap']['paylimit']['3000']);*/
		$this->display($jieqiPayset['rdo_wap']);
	}
	/**
	 * RDO 异步
	 */
	public function rdo_notify($params = array()){
		global $jieqiPayset;
		jieqi_getconfigs('pay', 'rdo_wap', 'jieqiPayset');
		$dataObj = $this->model('pay');
		$dataObj->rdo_notify($params, $jieqiPayset);
	}


}

?>