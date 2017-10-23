<?php 
/** 
 * 右侧sysinfo * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class homeController extends Admin_controller {
		public $template_name = 'paylog';
		/**
		 * 推广营收
		 * @author chengyuan 2015-6-18 下午2:42:40
		 * @param unknown $params
		 */
        public function main($params = array()) {
			 $dataObj = $this->model('home');
			 $data = $dataObj->promotingRevenue($params['start']);
             print_r($data);
			 $this->display($data,'promotingRevenue');
        } 
		public function total($params = array()){
			 $dataObj = $this->model('home');
			 $data = $dataObj->total($params);
             $this->display($data);
		}
		/**
		 * 充值记录
		 * @author chengyuan 2015-6-18 下午1:16:18
		 * @param unknown $params
		 */
		public function pay($params = array()){
			$dataObj = $this->model('home');
            if ($params['keyword'] || $params['id']) {
                $data = $dataObj->main($params);
            }
            else {
                $data=array();
            }
			$this->display($data);
		}
} 

?>
