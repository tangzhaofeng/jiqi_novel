<?php
/**
 * 阅读控制器
 * @author chengyuan  2014-8-8
 *
 */
class readerController extends chief_controller {

//	public $template_name = 'read';
// 	public $caching = false;
	public $theme_dir = false;
	//public $cacheid = 'fff';
	//public $cachetime=5;
	/**
	 * 缺省，阅读章节
	 * @param unknown $params
	 * 2014-8-8 下午3:16:23
	 */

	public function main($params = array()) {
        define('CHAPTER_READER_CACHE_TIMEOUT',3600);
		define("PLATFORM",'mobile');

		$dataObj = $this->model('readerr', '3g');
		$data = $dataObj->reader($params['aid'], $params['cid'], -11, $params['page']);

		$cookiestr = array(
			'aid'			=> $data['article']['articleid'],
			'cid'			=> $data['chapter']['chapterid'],
			'aname'			=> urlencode($data['article']['articlename']),
			'autname'		=> urlencode($data['article']['author']),
			'asort'			=> urlencode($data['article']['sort']),
			'cname'			=> urlencode($data['chapter']['title']),
			'siteid'		=> $data['article']['siteid'],
			'sortid'		=> $data['article']['sortid']
		);
		if($data['chapter']['isvip']){//print_r($data);
		    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
			//vip template
			//没有章节内容，不需要风格模板
			$this->theme_dir = false;
			$this->template_name = 'readvip';
		}
		// 根据文章是否为VIP判断是否写入
		$user = $this->getAuth();
		if (!$data['chapter']['isvip'] || isset($user['uid'])&&(''!=$data['chapter']['content'])) {
			$this->wCookie($cookiestr);
		}
		//点击量
		$dataObj = $this->model('article','article');
		$dataObj->statisticsVisit($params['aid']);
        $url = $this->geturl('3g', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
        $_SESSION['jieqi_readurl'] = $url;
		return $this->display($data);
	}
	/**
	 * 单篇购买vip章节
	 * @param unknown $params
	 * 2014-8-12 下午4:40:56
	 */
	public function buychapter($params = array()){
	    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
		$dataObj = $this->model('readerr','3g');
		//购买成功的回调URL
		$url = $this->geturl('3g', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
		$_SESSION['jieqi_readurl'] = $url;
		$dataObj->buychapter($params['aid'],$params['cid'],$url);
	}
	public function xbuychapter($params = array()){
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$dataObj = $this->model('readerr','3g');
		//购买成功的回调URL
		$url = $this->geturl('3g', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
		$_SESSION['jieqi_readurl'] = $url;
		$dataObj->xbuychapter($params['aid'],$params['cid'],$url);
	}

	public function readnext($params = array()) {
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		$dataObj = $this->model('readerr','3g');
        $dataObj->readnext($params);
	}
}
?>