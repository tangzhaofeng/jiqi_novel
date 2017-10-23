<?php 
/** 
 * article控制器 * 
 * @copyright   Copyright(c) 2014 
 * @author      chengyuan 
 * @version     1.0 
 */ 
class articleController extends chief_controller { 
		/**
		 * 申请作者视图
		 */
		public function appwV($param){
			$this->display($data,'applywriter');
		}
		/**
		 * 申请作者
		 * @param unknown $param
		 */
		public function appw($param){
			if($this->submitcheck()){
				$dataObj = $this->model('article');
				$dataObj->applyWriter($param);
			}
			//$this->display($data,'applywriter');
		}
		/**
		 * 第一步，创建新书视图
		 */
		public function step1View($param){
			header('Content-Type:text/html;charset=gbk');
			$dataObj = $this->model('article','article');
			$data = $dataObj->newArticleView();
			$this->display($data,'newarticle'); 
		}
		
		/**
		 * 第一步，保存新书
		 */
        public function step1($param) { 
        	//验证表单的合法性
        	if($this->submitcheck()){
        		$dataObj = $this->model('article','article');
				$dataObj->newArticle($param);
        	}
        } 
        /**
         * 第三步，审核视图
         */
        public function step3View($param){
        	$dataObj = $this->model('article','article');
        	$data = $dataObj->step3($param);
        	$this->display($data,'step3');
        }
		/**
		 * 我的作品库
		 */
		public function masterPage($param) { 
			$dataObj = $this->model('article','article');
			$data = $dataObj->myArticleList($param);
			$this->display($data,'masterpage');
		}
		/**
		 * 作品信息管理视图
		 */
		public function editArticleView($param){
			$dataObj = $this->model('article','article');
			$data = $dataObj->editArticleView($param);
			$this->display($data,'articleedit');
		}
		/**
		 * 文章编辑
		 */
		public function editArticle($param){
			if($this->submitcheck()){
				$dataObj = $this->model('article');
				$dataObj->editArticle($param);
			}
		}
		/**
		 * 清空文章，删除所有章节
		 */
		public function articleClean($param){
			$dataObj = $this->model('article');
			$dataObj->articleClean($param['aid'], $param['jumpurl'] );
		}
		/**
		 * 删除一篇文章
		 */
		public function articleDelete($param){
			$dataObj = $this->model('article');
			$dataObj->articleDelete($param['aid']);
		}
		/**
		 * 重新生成静态文件
		 */
		public function repack($param){
			if($this->submitcheck()){
				$dataObj = $this->model('article');
				$dataObj->repack($param['aid'],$param['packflag']);
			}
		}
		/**
		 * 书架视图
		 */
		public function bcView($param){
			$dataObj = $this->model('article');
			$data = $dataObj->bcList($param);
			$this->display($data,'bookcase');
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
			$dataObj = $this->model('article');
			$dataObj->bcHandle($param);
		}
		/**
		 * 七天更新的作品
		 * @param unknown $param
		 */
		public function sevenday($param){
			$this->display($data,'bookcase');
		}
		//文章管理
		public function articleManage() { 
			$dataObj = $this->model('article');
			$data = $dataObj->articleManage($this->getRequest('aid'));
			$this->display($data,'articlemanage');
		}
		
		//文章信息展示
		public function articleInfo(){
		$dataObj = $this->model('article');
		//$dataObj->getArticleInfo($this->getRequest('id'));
		$this->display($data,'articlemanage');
		
		}
		//登录条 小型书架
//		public function miniList($param){
//			$dataObj = $this->model('article');
//			$data = $dataObj->miniList($param);
//			print_r($data);
//			$this->display($data,'miniList');
//		}
/*		//个人信息 作品列表
		public function userlist($param){
			$this->theme_dir = false;
			header('Content-Type:text/html;charset=gbk');
			$dataObj = $this->model('article');
			$data = $dataObj->userList($param);
//			print_r($data);
			$this->display($data,'userList');
		}
		//个人信息 收藏列表
		public function userbook($param){
		$data=array();
			$this->theme_dir = false;
			header('Content-Type:text/html;charset=gbk');
			$dataObj = $this->model('article');
			$data = $dataObj->userbook($param);
//			print_r($data);
			$this->display($data,'user_book');
		}
		//个人信息 书评列表
		public function userreview($param){
		$data=array();
		$this->theme_dir = false;
		header('Content-Type:text/html;charset=gbk');
		$reviewsLib = $this->load ( 'reviews', 'article' );
//		$auth = $this->getAuth();
		$data = $reviewsLib->queryReviews(array('uid'=>$param['uid'],'page'=>$param['page'],'ispage'=>1));
//		print_r($data);
			$this->display($data,'user_review');
		}*/
		/**
		 * 统计点击量
		 * @param unknown $param
		 */
		public function statisticsVisit($param){
			$dataObj = $this->model('article');
			$dataObj->statisticsVisit($param['aid']);
			return;
		}
		
		/**
		 * 重新生成opf，txtfull，umd，txtfull，jar等文件，适用于将同步生成转换为异步生成
		 * @param unknown $param
		 */
		public function synchronousMakePack($param){
			$articleObj = $this->model ( 'article');
			$articleObj->synchronousMakePack($param);
		}
		/**
		 * ajax 验证agent有效性
		 * @param unknown $param
		 */
		public function checkAuthor($param){
			$articleObj = $this->model ( 'article');
			//ajax只支持utf-8
			$articleObj->checkAuthor(iconv('utf-8',JIEQI_DB_CHARSET,$param['author']));
		}
		/**
		 * ajax 验证agent有效性
		 * @param unknown $param
		 */
		public function checkAgent($param){
			$articleObj = $this->model ( 'article');
			//ajax只支持utf-8
			$articleObj->checkAgent(iconv('utf-8',JIEQI_DB_CHARSET,$param['agent']));
		}
		/**
		 * ajax 验证简介有效性
		 * @param unknown $param
		 */
		public function checkIntro($param){
			$articleObj = $this->model ( 'article');
			//ajax只支持utf-8
			$articleObj->checkIntro(iconv('utf-8',JIEQI_DB_CHARSET,$param['intro']));
		}
		/**
		 * ajax 验证书名有效性，可以过滤掉aid文章的书名
		 * @param unknown $param
		 */
		public function checkArticlename($param){
			$articleObj = $this->model ( 'article');
			//ajax只支持utf-8
			$articleObj->checkArticlename($param['aid'],iconv('utf-8',JIEQI_DB_CHARSET,$param['articlename']));
		}
		

		/**
		 * 申请修改财务信息
		 * @param unknown $params
		 */
		public function addEditApply($params){
			$dataObj = $this->model('income');
			$dataObj->addEditApply($params['ueid']);
		}
		/**
		 * 财务信息
		 * @param unknown $params
		 */
		public function finance($params){
			header('Content-Type:text/html;charset=gbk');
			$dataObj = $this->model('income');
			$data = $dataObj->finance();
			$this->display($data,'finance');
		}
		/**
		 * 新增作者财务信息
		 * @param unknown $params
		 */
		public function addFinance($params){
			if($this->submitcheck()){
				$dataObj = $this->model('income');
				$dataObj->addFinance($params);
			}
		}
		/**
		 * 修改作者财务信息
		 * @param unknown $params
		 */
		public function editFinance($params){
			if($this->submitcheck()){
				$dataObj = $this->model('income');
				$dataObj->editFinance($params);
			}
		}
		
		
		//收入月报
		public function income($params){
			$dataObj = $this->model('income');
			$data = $dataObj->income($params);
			$this->display($data,'income');
		}
		//奖励保障
		public function rewards($params){
			$dataObj = $this->model('income');
			$data = $dataObj->rewards($params);
			$this->display($data,'rewards');
		}
		//打赏明细
		public function exceptional($params){
			$dataObj = $this->model('income');
			$data = $dataObj->exceptional($params);
			$this->display($data,'exceptional');
		}
		
		//查询打赏
		public function params($param){
			$dataObj = $this->model('income');
			$data = $dataObj->queryex($params);
			$this->display($data,'channelincome');
		}
		//渠道收入
		public function channelIncome($params){
			$dataObj = $this->model('income');
			$data = $dataObj->channelIncome($params);
			$this->display($data,'channelincome');
		}
		
		public function incomedetail($params){
			$dataObj = $this->model('income');
			$data = $dataObj->incomedetail($params);
			$this->display($data,'incomedetail');
		}
		public function getincome($params){
			$dataObj = $this->model('income');
			$data = $dataObj->incomedetail($params);
			$this->display($data,'getincome');
		}
		/**
		 * 定时审核，处理定时的章节和卷，需要验证key
		 * <br>md5(JIEQI_DB_USER.JIEQI_DB_PASS.JIEQI_DB_NAME.JIEQI_SITE_KEY)
		 * @param unknown $param
		 * 2014-7-4 上午7:13:45
		 */
		public function regularAudits($param){
			$dataObj = $this->model('article');
			$dataObj->regularAudits($param);
		}
		
} 

//testController 继承我们的核心控制器，其实在以后的每个控制器中都要继承的，现在我们通过浏览器访问 http://localhost/myapp/index.php?controller=test ,哈哈，可以输出 test 字符串了

?>