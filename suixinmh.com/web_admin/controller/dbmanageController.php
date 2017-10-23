<?php 
/** 
 * 系统管理->数据维护 * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */ 
class dbmanageController extends Admin_controller {
		//public $template_name = 'main';
		//public $theme_dir = false;
		
		public function __construct() { 
              parent::__construct();
			  //管理参数设置 权限
			  $this->checkpower('admin');
        }
		/** 
		 * goto export view
		 */ 
        public function export_view() {
			$dataObj = $this->model('dbmanage');
			$data = $dataObj->exportForm();
			$this->display($data,'dbmanage');
        } 
		/** 
		 * do backup
		 */
		public function export() {
			$dataObj = $this->model('dbmanage');
			$data = $dataObj->backup();
			$this->display($data,'dbmanage');
        }
		
		/** 
		 * goto import view
		 */ 
		public function import_view() {
			$dataObj = $this->model('dbmanage');
			$data = $dataObj->importForm();
			$this->display($data,'dbmanage');
        } 
		
		/** 
		 * do import
		 */
		public function import() {
			$dataObj = $this->model('dbmanage');
			$dataObj->import();
			//$this->display($data,'dbmanage');
        }
		
		/** 
		 * goto optimize view
		 */
		public function optimize_view() {
			$dataObj = $this->model('dbmanage');
			$data = $dataObj->optimizeView();
			$data['option'] ='optimize';
			$this->display($data,'dboptimize');
        }
		
		/** 
		 * goto repair view
		 */
		public function repair_view() {
			$dataObj = $this->model('dbmanage');
			$data = $dataObj->optimizeView();
			$data['option'] ='repair';
			$this->display($data,'dboptimize');
        }
		
		/** 
		 * optimize/repair
		 */
		public function optimize() {
			$dataObj = $this->model('dbmanage');
			$dataObj->optimize();
			//$data['option'] ='repair';
			//$this->display($data,'dboptimize');
        }
		
		/** 
		 * goto upgrade view
		 */
		public function upgrade_view() {
			$data = array();
			$data['url_action'] = $this->getAdminurl('dbmanage','method=upgrade');
			$this->display($data,'dbupgrade');
        }
		
		/** 
		 * goto upgrade view
		 */
		public function upgrade() {
			$dataObj = $this->model('dbmanage');
			$data = $dataObj->upgrade();
			$this->display($data,'dbupgrade');
        }
}
?>