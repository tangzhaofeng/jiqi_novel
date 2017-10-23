<?php 
/** 
 * 后台首页控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
class profileController extends Admin_controller {
        //public $template_name = 'profile';
		public $theme_dir = false; 
        public function main() {	
		     $dataObj = $this->model('profile');
			 $data=$dataObj->main($_REQUEST);
             $this->display($data,"profile");
        }
    public function update($param) {
        $dataObj = $this->model('profile');
        $dataObj->update($param);
    }
}
?>