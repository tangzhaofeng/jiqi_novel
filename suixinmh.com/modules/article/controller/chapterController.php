<?php 
/**
 * 章节控制器
 * @author chengyuan  2014-4-4
 *
 */
class chapterController extends chief_controller { 

//         public $template_name = 'index'; 
// 		public $caching = false;
		//public $theme_dir = false;
		//public $cacheid = 'fff';
		//public $cachetime=5;
		   
/*        public function __construct() { 
              parent::__construct(); 
        } */
		
		/**
		 * 章节卷管理
		 */
		public function cmView($param){
			$dataObj = $this->model('chapter','article');
			$data = $dataObj->volumeManage($param);
			$this->display($data,'chaptermanage');
		}
		/**
		 * 保存新卷
		 */
		public function saveVolume($param){
			if($this->submitcheck()){
				//检查验证码
				if(empty($param['checkcode']) || $param['checkcode'] != $_SESSION['jieqiCheckCode']){
					$this->addLang('system', 'users');
					$jieqiLang['system'] = $this->getLang('system');
					$this->printfail($jieqiLang['system']['error_checkcode']);
				}
				$dataObj = $this->model('volume');
				$dataObj->saveVolume($param['aid'],$param['previous_volume'],$param['chaptername'],$param['manual'],$param['postdate']);
			}
		}
		/**
		 * 第二步，上传内容视图
		 */
		public function step2View($param){
			$dataObj = $this->model('chapter','article');
			$data = $dataObj->set2View($param);
			$this->display($data,'newchapter_step');
		}
		/**
		 * 第二步，保存上传内容
		 */
		public function step2($param){
			if($this->submitcheck()){
				$dataObj = $this->model('chapter','article');
				$newChapter = $dataObj->saveChapter($param);
				//重定向第三步视图
				$this->jumppage ($this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid='.$param['aid']), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
			}
		}
		/**
		 * 快速增加章节视图
		 */
		public function newChapterView($param){
			$chapterObj = $this->model('chapter','article');
			$data = $chapterObj->addChapterView($param);
			$data['ip'] = $this->getIp();
			$this->display($data,'newchapter');
		}
		/**
		 * 保存章节
		 */
		public function newChapter($param=array()){
			if($this->submitcheck()){
				$dataObj = $this->model('chapter');
				$newChapter = $dataObj->saveChapter($param);
				if(!empty($newChapter) && in_array($newChapter['display'],array('0','1','2','9'))){
					$this->addLang('article','article');
					$txt =  $this->getLang('article','chapter_display_'.$newChapter['display']);
					$this->jumppage($this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid='.$param['aid']), LANG_DO_SUCCESS,LANG_DO_SUCCESS.'<br>'.$txt);
				}else{
					$this->printfail();
				}
			}
			
		}
		/**
		 * 批量操作
		 */
		public function batchHandle($param=array()){
			if($this->submitcheck()){
				$dataObj = $this->model('chapter');
				$aid = $param['aid'];
				$chapterids = $param['checkid'];
				if($param['op'] == 1){
					//批量显示
					$dataObj->hideChapter($aid,$chapterids,0);
				}else if($param['op'] == 2){
					//批量隐藏
					$dataObj->hideChapter($aid,$chapterids,1);
				}else if($param['op'] == 3){
					//批量删除
					$dataObj->batchDelChapter($aid,$chapterids);
				}
				else if($param['op'] == 4){
					//批量删除
					$dataObj->vipChapter($aid,$chapterids,1);
				}
				else if($param['op'] == 5){
					//批量删除
					$dataObj->vipChapter($aid,$chapterids,0);
				}
				$this->jumppage ($this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid='.$param['aid']), LANG_DO_SUCCESS,LANG_DO_SUCCESS);
			}
		}
		/**
		 * 章节排序
		 */
		public function sortChapter($param=array()){
			$dataObj = $this->model('chapter');
			$data = $dataObj->chapterSort($param);
		}
		/**
		 * 章节、卷修改视图
		 */
		public function editChapterView($param=array()){
			$dataObj = $this->model('chapter');
			$data = $dataObj->editChapterView($param);
			if($data['chapter']['chaptertype'] == 0){
				$this->display($data,'editchapter');//章节
			}elseif($data['chapter']['chaptertype'] == 1){
				$this->display($data,'editvolume');//卷
			}else{
				$this->jumppage ($this->geturl ( 'article', 'chapter', 'SYS=method=cmView&aid='.$param['aid']), LANG_DO_SUCCESS,LANG_UNKNOWN);
			}
			
		}
		/**
		 * 修改章节
		 */
		public function editChapter($param){
			if($this->submitcheck()){
				$dataObj = $this->model('chapter');
				$data = $dataObj->editChapter($param);
			}
		}
		/**
		 * 修改卷
		 */
		public function editVolume($param){
			if($this->submitcheck()){
				$dataObj = $this->model('volume','article');
				$volume = array();
				$volume['chapterid'] = $param['cid'];
				$volume['articleid'] = $param['aid'];
				$volume['chaptername'] = trim($param['chaptername']);
				$volume['manual'] = trim($param['manual']);
				$volume['postdate'] = trim($param['postdate']);
				$data = $dataObj->editVolume($volume);
			}
				
		}
		//隐藏章节
		public function hideChapter(){
			$dataObj = $this->model('chapter');
			$dataObj->hideChapter();
		}
		/**
		 * 删除单个章节
		 * @param unknown $param
		 */
		public function delChapter($param=array()){
			$dataObj = $this->model('chapter');
			$dataObj->delChapter($param);
		}
		/**
		 * 检查章节字数
		 * @param unknown $params
		 */
		public function checkChapter($params = array()){
			 $dataObj = $this->model('chapter');
			 $dataObj->checkChapter($params);
		}
		
		/**
		 * ajax 检查章节名称有效性
		 * @author chengyuan 2015-5-6 上午10:40:58
		 * @param unknown $param
		 */
		public function checkChapterName($param){
			$chapterModel = $this->model ( 'chapter');
			//ajax只支持utf-8
			$chapterModel->checkChapterName($param['aid'],$param['cid'],iconv('utf-8',JIEQI_DB_CHARSET,$param['chaptername']));
		}
} 

//testController 继承我们的核心控制器，其实在以后的每个控制器中都要继承的，现在我们通过浏览器访问 http://localhost/myapp/index.php?controller=test ,哈哈，可以输出 test 字符串了

?>