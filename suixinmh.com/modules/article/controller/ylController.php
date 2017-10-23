<?php 
/** 
 * 测试控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class ylController extends Controller { 

        public $template_name = 'index'; 
		public $caching = false;
		//public $theme_dir = false;
		//public $cacheid = 'fff';
		//public $cachetime=5;
		   
/*        public function __construct() { 
              parent::__construct(); 
        } */
		
		//作家工具视图
		public function myArticleView(){
			 $this->display($data,'myarticle'); 
		}
		
		//上书视图
		public function newArticleView(){
			$dataObj = $this->model('yl');
			$data = $dataObj->newArticleView();
			
			$this->display($data,'newarticle'); 
		}
		
		//保存新书
        public function newArticle() { 
			$dataObj = $this->model('yl');
			$dataObj->newArticle();
			//exit;
//		     $data = array();
//			 if(isset($_REQUEST['page'])) $page = $_REQUEST['page'];
//		     else $page= 1;
//			 $this->setCacheid($page);
//             if(!$this->is_cached()){echo ' 123执行了缓存中的数据查询代码';
//			     //$data['title'] = '这里是首页测试页';
//				 
//				 $C = $dataObj->getArticles();
//				 $data['articlerows'] = $C['data'];
//				 $data['url_jumppage'] = $C['jumppage'];
//				 //$data['test'] = "这里是首页!!！".date('Y-m-d H:i:s',time()); 
//				 $this->load('aa','system');
//				 //$this->addConfig('system', 'configs');
//				 //$this->addConfig('system', 'blocks');//print_r($jieqiBlocks);
//				 //print_r($this->getConfig('system'));
//				 //$this->addLang('system', 'users');
//				 //$this->addLang('system', 'cache');
//				 //$this->tpl->setCaching(0);
//				 //$this->tpl->setCacheid('index');
//				 //print_r($this->getLang());
//			 }
//             $this->display($data); 
        } 
		
		//我的文章列表
		public function yl() { 
			//参考article1/masterpage.php
			$dataObj = $this->model('yl');
			$dataObj->test();
		}
		
		//文章管理
		public function ylarticleManage() { 
			$dataObj = $this->model('yl');
			$data = $dataObj->articleManage($this->getRequest('id'));
			$this->display($data,'articlemanage');
		}
		
		//文章信息展示
		public function articleInfoShow(){
		$dataObj = $this->model('article');
		$dataObj->getArticleInfo($this->getRequest('id'));
		
		}
		
		//文章信息展示
		public function articleInfoEdit(){
		
		}
		
} 

//testController 继承我们的核心控制器，其实在以后的每个控制器中都要继承的，现在我们通过浏览器访问 http://localhost/myapp/index.php?controller=test ,哈哈，可以输出 test 字符串了

?>