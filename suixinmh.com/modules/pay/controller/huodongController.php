<?php 
/** 
 * 充值中心控制器 * @copyright   Copyright(c) 2014 
 * @author      zhangxue* @version     1.0 
 */ 
class huodongController extends pay_controller { 

		//书海卡
        public function main($params = array()) {
			$this->theme_dir = false;
//		    global $jieqiPayset;
//	        jieqi_getconfigs('pay', 'alipay', 'jieqiPayset');
			//提交数据
		    if($this->submitcheck()){
			    if($this->checklogin(true)){
					$dataObj = $this->model('huodong');
					$dataObj->main($params);
				}else{
					$this->printfail('请先登录！');
				}
			}
			$this->display($jieqiPayset['alipay'],'shcard');
        } 
} 
?>