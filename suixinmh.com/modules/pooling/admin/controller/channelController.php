<?php
/**
 * 渠道管理控制器
 * @author chengyuan  2014-6-11
 *
 */
class channelController extends Admin_controller {
		public $template_name = 'channel';
		public $caching = false;
// 		public function __construct() {
//               parent::__construct();
// 			  $this->checkpower('manageallarticle');//权限验证
// 			  //检查审核作者权限
// 			  $this->checkpower('setwriter');
//         }
		/**
		 * 默认入口
		 * @param unknown $params
		 */
        public function main($params = array()) {
        	$data = array();
        	$dataObj = $this->model('channel');
        	$this->display($dataObj->main($params));
        }
        /**
         * 渠道任务分配
         * @param unknown $params
         */
        public function schedule($params = array()){
        	$dataObj = $this->model('channel');
        	$dataObj->schedule($params['channelid'],$params['uids']);
        }
        /**
         * 新增，修改
         * @param unknown $params
         */
        public function add($params = array()){
        	$dataObj = $this->model('channel');
        	$this->display($dataObj->add($params), $this->template_name.'_add'.$params['step']);
        }
        /**
         * id删除渠道
         * @param unknown $params
         */
        public function del($params = array()){
        	$dataObj = $this->model('channel');
        	$dataObj->del($params);
        }
        /**
         * 排序
         * @param unknown $params
         */
        public function order($params = array()){
        	$dataObj = $this->model('channel');
        	$dataObj->order($params);
        }
        /**
         * 批量推送文章
         * @param unknown $params
         * 2014-6-12 下午3:00:34
         */
        public function pushArticles($params = array()){
        	if($this->submitcheck()){
        		$dataObj = $this->model('channel');
        		$data = $dataObj->pushArticles($params['cid'],$params['articleids'],$params['statu']);
        	}
        }
		/**
		 * 渠道推送视图
		 * @param unknown $params
		 * 2014-6-12 下午2:17:40
		 */
        public function pushView($params = array()){
        	$dataObj = $this->model('channel');
        	$data = $dataObj->pushView($params);
        	$this->template_name = 'article_list';
        	$this->display($data);
        }
        /**
         * 渠道采集视图
         * @param unknown $params
         * 2014-8-19 下午1:46:01
         */
        public function collectView($params = array()){
        	$dataObj = $this->model('channel');
        	$data = $dataObj->collectList($params['cid'],$params['page']);
        	$this->template_name = 'article_collect_list';
        	$this->display($data);
        }
        /**
         * 批量采集入库，新增|更新
         * @param unknown $params
         * 2014-8-19 下午4:35:07
         */
        public function newArticle($params = array()){
        	$dataObj = $this->model('channel');
        	$id = $params['aid'] ? array($params['aid']) : $params['aids'];
        	$data = $dataObj->newArticle($params['cid'],$id,$page);
        }
        /**
         * 删除推送的文章
         * @param unknown $params
         * 2014-6-13 下午1:35:42
         */
        public function delArticle($params = array()){
        	$dataObj = $this->model('channel');
        	$id = $params['paid'] ? $params['paid'] : $params['paids'];
        	$data = $dataObj->delArticle($params['cid'],$id);
        }
        /**
         * 批量解禁或者封禁
         * @param unknown $params
         * 2014-6-13 下午2:25:48
         */
        public function editStatu($params = array()){
        	$dataObj = $this->model('channel');
        	$id = $params['paid'] ? $params['paid'] : $params['paids'];
        	$data = $dataObj->editStatu($params['cid'],$id,$params['value']);
        }

        public function  editArticle($params = array()){
        	$dataObj = $this->model('channel');
        	$data = $dataObj->editArticle($params);
        	$this->display($data,'article_edit');
        }
		/**
		 * 推送
		 * @param unknown $params
		 * 2014-6-27 下午2:12:34
		 */
        public function push($params = array()){
        	$dataObj = $this->model('channel');
        	$data = $dataObj->push($params);
        }
        /**
         * 单篇同步/批量同步书海章节
         * <p>
         * 将书海主站的文章章节复制一份到api的数据池中，以供api的二次编辑。
         * @param unknown $params
         * 2014-8-27 下午2:42:14
         */
        public function synchronization($params = array()){
        	$dataObj = $this->model('chapter');
        	$id = $params['paid'] ? array($params['paid']) : $params['paids'];
        	$dataObj->synchronization($params['cid'],$id);
        }
        /**
         * 数据池章节列表（同步后）
         * @param unknown $params
         * 2014-8-28 下午3:28:41
         */
        public function chapterList($params = array()){
        	$dataObj = $this->model('chapter');
        	$data =  $dataObj->chapterList($params['cid'],$params['paid']);
        	$this->display($data,'chapter_list');
        }
        /**
         * ajax获取数据池章节信息，返回json格式
         * @param unknown $params
         * 2014-9-1 下午1:41:33
         */
        public function getChapter($params = array()){
        	$dataObj = $this->model('chapter');
        	$dataObj->getChapter($params['cid']);
        }
        /**
         * 修改章节
         * @param unknown $params
         * 2014-9-2 上午9:09:17
         */
        public function editChapter($params = array()){
        	$dataObj = $this->model('chapter');
        	$dataObj->editChapter($params['pcid'],$params['chaptername'],$params['content'],true);
        }
        /**
         * 新增/插入章节
         * @param unknown $params
         * 2014-9-12 下午2:28:05
         */
        public function insertChapter($params = array()){
        	$dataObj = $this->model('chapter');
        	$dataObj->insertChapter($params['pcid'],$params['chaptername'],$params['content'],$params['insertChapterName'],$params['insertContent']);
        }
        /**
         * 删除一个章节
         * @param unknown $params
         * 2014-9-12 下午2:32:46
         */
        public function delete($params = array()){
        	$dataObj = $this->model('chapter');
        	$dataObj->delete($params['pcid']);
        }
}

?>