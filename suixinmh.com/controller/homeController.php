<?php 
/** 
 * ²âÊÔ¿ØÖÆÆ÷ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class homeController extends Controller { 
        public $caching = false;
        public $template_name = '/modules/article/templates/channel';
        public $theme_dir = false;
        public function main() {
		     if(is_mobile()){
			     header('location:http://m.ishufun.net/');exit;
			 }
		     $data = array();//global $jieqiConfigs,$jieqiBlocks;
             $this->display($data); 
        } 
} 

?>