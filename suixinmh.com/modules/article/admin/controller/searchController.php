<?php 
/** 
 * ср╡Юsysinfo * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class searchController extends Admin_controller {
		public $template_name = 'searchcache';

        public function main($params = array()) {
		$modelObj = $this->model('search');
		$data = $modelObj->main($params);
		$this->display($data);   
        } 
} 

?>