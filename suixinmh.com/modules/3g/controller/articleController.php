<?php
/**
 * article控制器 *
 * @copyright   Copyright(c) 2014
 * @author      chengyuan
 * @version     1.0
 */
 header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
 class articleController extends chief_controller {

//      public $template_name = 'index';
        public $caching = false;
		public $theme_dir = false;
		//public $cacheid = 'fff';
		//public $cachetime=5;

		/**
		 * 书架视图
		 */
		public function bcView($param){
			$dataObj = $this->model('article','article');
			$data = $dataObj->bcList($param);//print_r($data);
			// 读取阅读记录
			$ucookie = $this->rCookie();
			if (''!=$ucookie) {
				$data['read_count'] = count($ucookie);
			} else {
				$data['read_count'] = 0;
			}
			$data['read_history'] = $ucookie;
//			$this->dump($data);
			$this->display($data);
		}
		/**
		 * 书架移除一本书
		 * @param  $param
		 */
// 		public function bcRem($param){
// 			$dataObj = $this->model('article');
// 			$dataObj->bcDel($param);
// 		}
		/**
		 * 书架批量操作
		 */
		public function bcBatch($param){
			$dataObj = $this->model('article','article');
//			$this->dump($param);
			$dataObj->bcHandle($param);
		}
}

//testController 继承我们的核心控制器，其实在以后的每个控制器中都要继承的，现在我们通过浏览器访问 http://localhost/myapp/index.php?controller=test ,哈哈，可以输出 test 字符串了

?>