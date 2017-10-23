<?php 
/** 
 * уб╫зд©б╪ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class indexController extends Controller { 

        public $template_name = 'index'; 
		public $caching = false;
		public $theme_dir = false;

        public function main($params = array()) {
		     header('Cache-Control:max-age='.JIEQI_CACHE_LIFETIME);
		     $data = array();
			 $dataObj = $this->model('index');
             return $this->display($dataObj->main($params)); 
        } 
} 
?>