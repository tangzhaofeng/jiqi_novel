<?php 
/** 
 * 章节阅读 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class readerController extends Controller { 

        public $template_name = 'reader'; 
		public $caching = false;
		public $theme_dir = false;

        public function main($params = array()) {
			 //$REFERER = preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']);
			 //if($REFERER){
				 //$HOST = preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']);//if($_SESSION['jieqiUserId']==2299){echo $_SERVER['HTTP_REFERER'].'gg';}
				 //if(get_host($REFERER)!=get_host($HOST)) $this->printfail(LANG_ERROR_PARAMETER);
			 //}
		     $data = array();
			 $dataObj = $this->model('reader');
			 return $dataObj->main($params);
             //$this->display($dataObj->main($params,$this)); 
        } 
		//阅读VIP内容
		public function readvip($params = array()) {
		    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
		    /*$data = array();
			$autobuy = 0;
		    $auth = $this->getAuth();//print_r($auth);
			if(isset($auth['uid']) && $auth['uid']){
				 $REFERER = preg_replace("/https?:\/\/([^\:\/]+).*\/i", "\\1", $_SERVER['HTTP_REFERER']);

				 $HOST = preg_replace("/([^\:]+).*\/", "\\1", $_SERVER['HTTP_HOST']);
				 if(get_host($REFERER)!=get_host($HOST)) $this->printfail(LANG_ERROR_PARAMETER);

			     $dataObj = $this->model('reader');
				 define('RETURN_READER_DATA', true);
				 if($dataObj->checkChapterIsBuy($params)){
				     define('RETURN_READER_CONTENT', true);
				 }else{
				   $autobuy  = $dataObj->checkAutoBuy($params);
				   //echo $autobuy;
				   $vip = $dataObj->getAllVip($params);	
				 }
				 $data = $dataObj->main($params);
				 $data['autobuy'] = $autobuy;
				 $data['vip'] = $vip; 
				 //查询折扣
				 $this->addConfig('system','vipgrade');
				 $jieqiVipgrade = $this->getconfig('system', 'vipgrade');
				 $data['vipgrade'] = jieqi_gethonorarray($auth['vip'], $jieqiVipgrade);//VIP等级数组
				 
			}
		    $this->display($data, 'readvip'); */
			$REFERER = preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']);
			$HOST = preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']);
			if(get_host($REFERER)!=get_host($HOST)) $this->printfail(LANG_ERROR_PARAMETER);
			define('RETURN_READER_DATA', true);
			$dataObj = $this->model('reader');//print_r($dataObj->main($params));
			$this->display($dataObj->main($params), 'readvip');
		}
		//购买VIP章节
		public function buychapter($params = array()){
		    $this->checklogin();//购买前先做登陆验证
			$dataObj = $this->model('reader');
			$dataObj->buychapter($params);
		}
		//批量 多选 购买章节
/*		public function severalchapter($params=array()){
			$dataObj = $this->model('reader');
			$dataObj->severalchapter($params);
		}*/
		//批量订阅VIP章节
		public function batchbuychapter($params=array())
		{
			$this->checklogin();
			$dataObj = $this->model('reader');
			$dataObj->batchbuychapter($params);
		}
		//批量订阅VIP章节
		public function batchbuychapterqixi($params=array())
		{
			$msg = array();
			if($params['fid'] && $params['sid']){
				$this->checklogin();
				$dataObj = $this->model('reader');

				$params['aid'] = $params['fid'];
				$data = $dataObj->batchbuychapterqixi($params);
				if($data && $data['articlename']){
					$msg['msg'] = '《'.$data['articlename'].'》购买成功！<br/>';
				}else{
					$msg['msg'] = '《'.$data['articlename'].'》购买失败！<br/>';
				}
				$params['aid'] = $params['sid'];
				$data = $dataObj->batchbuychapterqixi($params);
				if($data && $data['articlename']){
					$msg['msg'] .= '《'.$data['articlename'].'》购买成功！';
				}else{
					$msg['msg'] .= '《'.$data['articlename'].'》购买失败！';
				}
				$msg['msg'] .= '<br><a href="'.JIEQI_URL.'/user/xfView" target="_blank">查看详情</a>';
			}else{
				$msg['msg'] = '参数异常！';
			}
			exit($this->json_encode($msg));
		}
		
		public function autobuy($params=array())
		{
			$this->checklogin();
			$dataObj = $this->model('reader');
			$dataObj->autobuy($params);
		}
		
		public function closebuy($params=array()){
			$this->checklogin();
			$this->addLang ( 'article', 'article' );
			$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
			$dataObj = $this->model('reader');
			if (!$dataObj->closebuy($params)){
				$this->printfail();
			}else{
				$readurl = $this->geturl('article', 'reader', 'aid='.$params['aid'],'cid='.$params['cid']);
			    $this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_close_buy_success']);
			}
		}
		//加入限免
		public function addFree($params=array()){
			$this->checklogin();
			$dataObj = $this->model('reader');
			$dataObj->addFree($params);
		}
		//书卷 测试
		public function shujuanbuy($params=array()){
			$params['aid'] = 42; 
			$params['cid'] = 11;
			$dataObj = $this->model('reader');
			$data = $dataObj->shujuanbuy($params);//print_r($data);
		}
		/*
		 * 
		 */
		public function test(){
			$params['aid'] = 1162;
			$dataObj = $this->model('reader');
			$data = $dataObj->getAllVip($params);//print_r($data);
		}
} 
?>