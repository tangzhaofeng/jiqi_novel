<?php 
/** 
 * 右侧sysinfo * @copyright   Copyright(c) 2015 
 * @author      zhangxue* @version     1.0 
 */ 
class homeController extends Admin_controller {
		/**
		 * 主方法：显示列表，包括搜索
		 */
        public function main($params = array()) {
			 $dataObj = $this->model('home');
			 $data = $dataObj->main($params);
             $this->display($data);
        } 
		public function total($params = array()){
			 $dataObj = $this->model('home');
			 $data = $dataObj->total($params);
             $this->display($data);
		}
		
		/**
		 * 异步：编辑
		 * 返回：成功/失败 标记
		 */
		public function asynEdit($params = array()) {
			$dataObj = $this->model('home');
			$params['action'] = 'confirm';
			$dataObj->main($params);
		}
		
		/**
		 * 异步：删除
		 * 返回：成功/失败 标记
		 */
		public function asynDel($params = array()) {
			$dataObj = $this->model('home');
			$params['action'] = 'del';
			$dataObj->main($params);
		}
} 

?>