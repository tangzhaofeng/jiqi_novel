<?php
/**
 * 小说连载->章节更新记录 * @copyright   Copyright(c) 2014
 * @author      zhangxue* @version     1.0
 */
class chapterController extends Admin_controller {
		public $template_name = 'chapterlist';

		public function __construct() {
              parent::__construct();
			  $this->checkpower('manageallarticle');//权限验证
        }

        public function main($params = array()) {
			 $dataObj = $this->model('chapter');
             $this->display($dataObj->main($params));
        }
        /**
         * 更新章节月统计
         * @param unknown $params
         */
        public function chapterStatistics($params = array()) {
        	$dataObj = $this->model('chapter');
        	$data = $dataObj->chapterStatistics($params);
        	$this->display($data,'chapterStatistics');
        }
        /**
         * json批量获取章节信息
         * @param unknown $params
         */
        public function getChapters($params = array()){
        	$dataObj = $this->model('chapter');
        	$dataObj->getChapters($params['aid'],$params['cids']);
        }
        /**
         * @param unknown $params
         */
        public function getMonthChapters($params = array()){
        	$dataObj = $this->model('chapter');
        	$data = $dataObj->getMonthChapters($params['aid'],$params['year'],$params['month']);
        	$this->display($data,'month_chapter');
        }
        /**
         * ajax添加批注
         * @param unknown $params
         */
        public function addComment($params = array()){
        	$dataObj = $this->model('chapter');
        	$dataObj->addComment($params['cid'],$params['comment']);
        }
}

?>