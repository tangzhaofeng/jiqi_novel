<?php
/**
 * 用户中心控制器，集成了用户设置，短消息，工具箱所有功能。
 * <br>继承chief控制器，父控制器处理登陆验证，默认模板（main）等
 * @author chengyuan  2014-4-21
 *
 */
class userhubController extends chief_controller {

	/**
	 * 用户信息
	 */
	public function userinfo($params = array()) {
		$this->theme_dir = false;
		header('Content-Type:text/html;charset=gbk');
		if($params['flag']!=''){
			$dataObj = $this->model('article','article');
			if($params['flag']=='article'){
				$data = $dataObj->userList($params);
				$this->display($data,'user_article');
			}elseif($params['flag']=='bookcase'){
				$data = $dataObj->userbook($params);
				$this->display($data,'user_book');
			}elseif($params['flag']=='review'){
				$reviewsLib = $this->load ( 'reviews', 'article' );
				$data = $reviewsLib->queryReviews(array('uid'=>$params['uid'],'page'=>$params['page'],'ispage'=>1));
				$this->display($data,'user_review');
			}
		}else{
			$dataObj = $this->model('userinfo');
			$data = $dataObj->main($params);
			$this->display($data,'userinfo');
		}
	}
	public function zuozhe($params = array()) {
	    $this->userinfo($params);
	}
	/**
	 * 修改用户信息
	 * @param unknown $param
	 */
	public function useredit($param){
		if($this->submitcheck()){
			$dataObj = $this->model('useredit');
			$data = $dataObj->useredit($param);
			$this->display($data, 'useredit');
		}

	}
	/**
	 * 信息修改视图
	 */
	public function usereditView($param){
		$dataObj = $this->model('useredit');
		$data = $dataObj->usereditView($param);
		$this->display($data,'userupdate');
	}

	/**
	 * ajax保存头像，使用flase插件，上传的头像分为4种：原图|162*162|48*48|20*20
	 * @param unknown $param
	 */
	public function saveAvatar($param){
		$dataObj = $this->model('setavatar');
		$data = $dataObj->saveAvatar($param);
		echo $this->json_encode($data);
	}
	/**
	 * upload avatar view
	 */
	public function avatarView(){
		$dataObj = $this->model('setavatar');
		$data = $dataObj->setavatarView();
		$this->display($data,'setavatar');
	}
	/**
	 * update avatar view
	 */
	public function upaView(){
		$dataObj = $this->model('setavatar');
		$data = $dataObj->setavatarView();
		$this->display($data,'setavatar');
	}

	/**
	 * 更新密码
	 * @param unknown $param
	 */
	public function updatePwd($param){
		if($this->submitcheck()){
			$dataObj = $this->model('passedit');
			$dataObj->passedit($param);
		}
	}

	/**
	 * 密码修改视图
	 * @param unknown $param
	 */
	public function pwdview($param){
		$dataObj = $this->model('passedit');
		$data = $dataObj->passeditView($param);
		$this->display($data,'passedit');
	}
	/**
	 * 退出登录
	 */
	public function logout(){
		$dataObj = $this->model('logout');
		$dataObj->logout();
	}
	/**
	 * 删除消息（收件箱|发件箱）
	 * @param unknown $param
	 */
	public function delMsg($param){
		$dataObj = $this->model('message','system');
//		echo 'aaa';
//		exit;
		$dataObj->main($param);
	}
	/**
	 * 收件箱
	 */
	public function inbox($param){
		$dataObj = $this->model('message','system');
		$data = $dataObj->inbox($param);
		$this->display($data, 'inbox');
	}

	/**
	 * 发件箱
	 */
	public function outbox($param){
		$dataObj = $this->model('message','system');
		$data = $dataObj->outbox($param);
		$this->display($data, 'outbox');
	}
	/**
	 * 草稿箱
	 * @param unknown $param
	 */
	public function draft($param){
		$dataObj = $this->model('message','system');
		$data = $dataObj->draft($param);
		$this->display($data, 'draft');
	}
	/**
	 * 信息详细
	 */
	public function messagedetail($param){
		$dataObj = $this->model('message');
		$data = $dataObj->messagedetail($param);
		$this->display($data, 'messagedetail');
	}

	/**
	 * 写消息
	 */
	public function newmessage($param){
		$dataObj = $this->model('message');
		$param['receiver'] = urldecode($param['receiver']);
		$data = $dataObj->newmessage($param);
		$this->display($data, 'newmessage');
	}
	/**
	 * 写给管理员
	 * @param unknown $param
	 */
	public function toSysView($param){
		$dataObj = $this->model('message');
		$data = $dataObj->newmessage($param);
		$data['tosys'] = 1;
		$this->display($data, 'newmessage');
	}
	/**
	 * 发送消息
	 * @param unknown $param
	 */
	public function sendMsg($param){
		if($this->submitcheck()){
			$dataObj = $this->model('message');
			$data = $dataObj->sendMsg($param);
			//$this->display($data, 'newmessage');
		}
	}
	//工具箱
	/**
	 * 好友列表视图
	 */
	public function friend($param){
		$this->display('','myfriends');
	}

	public function getfriend($params){
		$dataObj = $this->model('myfriends');
		$data = $dataObj->main($params);
		$this->display($data,'wholeatten');
	}

	public function searchF($params){
		$params['smid'] = iconv("utf-8","gb2312",urldecode($params['smid']));
		$dataObj = $this->model('myfriends');
		$data = $dataObj->searchF($params);
		$data['seach'] = 'add';
		$data['smid'] = $params['smid'];
		$this->display($data,'wholeatten');
	}

	public function addAttention($params){
		$dataObj = $this->model('myfriends');
		$data = $dataObj->addAttention($params);
		$this->display($data,'wholeatten');
	}


	public function delAtten($params){
		$dataObj = $this->model('myfriends');
		$data = $dataObj->delAtten($params);
//		$this->display($data,'wholeatten');
	}

	/**
	 * 好友空间视图
	 */
	public function friendSpace($param){
		$dataObj = $this->model('myfriends');
		$data = $dataObj->friendSpace($param['uid']);
		//好友空间模板
		$this->display($data,'empty');
	}
	/**
	 * 我的发表的书评
	 */
	public function comment($param){
		$reviewsLib = $this->load ( 'reviews', 'article' );
		$auth = $this->getAuth();
		$url = $this->getUrl('article','userhub','SYS=method=comment');
		$data = $reviewsLib->queryReviews(array('uid'=>$auth['uid'],'page'=>$param['page'],'ispage'=>1,'url'=>$url));
		$this->display($data,'comment');
	}
	/**
	 * 回复的书评
	 */
	public function hotcomment($param){
		$reviewsLib = $this->load ( 'reviews', 'article' );
		$auth = $this->getAuth();
// 		$data = $reviewsLib->queryReviews(array('uid'=>$auth['uid'],'ispage'=>1,'limit'=>4,'display'=>'isgood'));
		$param['uid'] = $auth['uid'];
		$data = $reviewsLib->showRepliesByUid($param);
		$this->display($data,'commentreplies');
	}
	/**
	 * 用户中心首页
	 */
	public function main($param){
		/*$data = array();
		$articleMod = $this->model('article','article');
		$bc = $articleMod->bcList($param);
		//书架取最新的5条
		foreach($bc['articlerows'] as $k=>$v){
			if($k < 5){
				$data['bc'][$k] = $v;
			}else{
				break;
			}
		}
		$sour = $articleMod->getSources();
		$data['sort'] = $sour['sortrows'];*/
		$this->addConfig('article', 'sort');
		$data['sort'] = $this->getConfig('article','sort');
		$auth = $this->getAuth();
		$this->display(array('uid'=>$auth['uid'],'sort'=>$data['sort']),'user_index');
	}
	/**
	 * 隐私设置
	 */
	public function ysView($param){
		$data = array();
		$this->display($data,'yinsi');
	}
	/**
	 * 财务中心-充值
	 */
/*	public function cwView($param){
		$data = array();
		$this->display($data,'caiwuhub');
	}*/
	/**
	 * 充值记录
	 */
	public function czView($param){
		$dataObj = $this->model('finance');
		$data = $dataObj->rechargeLog($param);
		$this->display($data,'caiwu');
	}
	/**
	 * 消费记录
	 */
	public function xfView($param){
		$dataObj = $this->model('finance');
		$data = $dataObj->pay($param);
		$this->display($data,'xiaofei');
	}

	/**
	 * 自动订阅
	 */
	public function dyView($params){
		$dataObj = $this->model( 'home');
		$auth = $this->getAuth();
		$params['uid'] = $auth['uid'];
		$data = $dataObj->getDingyue($params);
		$this->display($data,'dingyue');
	}

	public function canseDingyue($params){
		    $this->addLang( 'article', 'article');
			$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
			$dataObj = $this->model('reader','article');
			if (!$dataObj->closebuy($params)){
				$this->printfail();
			}else{
				$readurl = $this->geturl('system', 'userhub','method=dyView');
			    $this->jumppage($readurl, LANG_DO_SUCCESS, $jieqiLang['article']['batch_close_buy_success']);
			}
	}


	/**
	 * 我的动态数据
	 */
	public function myDynamic($params = array()){
		//第一个参数是模型（去掉my_后的自定义类的名字），第二个参数是模块的名字
		$dataObj = $this->model( 'home');
		$auth = $this->getAuth();
		$params['uid'] = $auth['uid'];
		$data = $dataObj->getDynamic($params);
		/*if (empty($data['dynamicrows']))
		{
			if(!empty($params['mid'])){
				switch ($params['mid']){
				case 'reward':
					$data['defaultdata'] = "亲，没有打赏信息哦……";
					break;
					case 'goodnum':
					$data['defaultdata'] = "亲，没有收藏信息哦……";
					break;
					case 'sale':
					$data['defaultdata'] = "亲，没有订阅信息哦……";
					break;
					case 'vote':
					$data['defaultdata'] = "亲，没有推荐信息哦……";
					break;
					case 'my':
					$data['defaultdata'] = "亲，您还没有动态信息哦……";
					break;
			   }
		   }

		  $this->display($data,'dyndefault');
		}else{
			$this->display($data,'dynamic');
		}*/
		$this->display($data,'dynamic');
	}
	/**
	 * 个人信息 弹出层
	 */
	public function popuser($params = array()){
		header('Content-Type:text/html;charset=gbk');//用于刷新时消除乱码
		$dataObj = $this->model('userinfo');
		$data = $dataObj->popuser($params);
		$this->display($data,'pop_user');
	}

	//处理头像
	public function handleAvatar(){
		global $jieqiConfigs, $jieqiLang, $jieqi_image_type;
		jieqi_getconfigs('system', 'configs');
		$this->addLang('system', 'users');
		$auth = $this->getAuth ();
		$users_handler = $this->getUserObject ();
		$jieqiUsers = $users_handler->get ( $auth ['uid'] );
		$subdir = $auth ['uid'];
		$dir=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system');
		/***********************
		第二种实现办法：用readdir()函数
		************************/
			if(is_dir($dir))
		   	{
		     	if ($dh = opendir($dir))
				{
		        	while (($file = readdir($dh)) !== false)
					{
		     			if((is_dir($dir."/".$file))    && $file!="." && $file!="..")
						{
		     				//echo "<b><font color='red'>文件名：</font></b>",$file,"<br><hr>";
		     				//listDir($dir."/".$file."/");
		     			}
						else
						{
		         			if($file!="." && $file!=".." && $file != 'Thumbs.db')
							{
								echo '开始处理文件：'.$dir."/".$file.'<br>';
								if('i.jpg' == strrchr ( trim ( strtolower ($file) ), "i" )){
									echo '删除'.'<br>';
									jieqi_delfile($dir."/".$file);
								}elseif ('s.jpg' == strrchr ( trim ( strtolower ($file) ), "s" )){
									$basedir = $dir.jieqi_getsubdir(substr($file,0,strpos($file,'s')));
									jieqi_checkdir($basedir,true);
									$basedir .= '/'.$file;
									jieqi_copyfile($dir."/".$file,$basedir,0777,true);
									echo '移动至：'.$basedir.'<br>';
								}elseif(is_numeric(basename($file, '.jpg'))){
									$basedir = $dir.jieqi_getsubdir(basename($file, '.jpg'));
									jieqi_checkdir($basedir,true);
									$o = $basedir.'/'.$file;
									$l = $basedir.'/'.basename($file, '.jpg').'l.jpg';
									$m = $basedir.'/'.basename($file, '.jpg').'m.jpg';;
									jieqi_copyfile($dir."/".$file,$o,0777,false);//原图
									echo '复制至：'.$o.'<br>';
									jieqi_copyfile($dir."/".$file,$l,0777,false);//l
									echo '复制至：'.$l.'<br>';
									jieqi_copyfile($dir."/".$file,$m,0777,true);//m
									echo '移动至：'.$m.'<br>';
								}else{
									$basedir = $dir.jieqi_getsubdir(basename($file, '.png'));
									jieqi_checkdir($basedir,true);
									$o = $basedir.'/'.basename($file, '.png').'.jpg';
									$l = $basedir.'/'.basename($file, '.png').'l.jpg';
									$m = $basedir.'/'.basename($file, '.png').'m.jpg';
									$s = $basedir.'/'.basename($file, '.png').'s.jpg';
									jieqi_copyfile($dir."/".$file,$o,0777,false);//原图
									echo '复制至：'.$o.'<br>';
									jieqi_copyfile($dir."/".$file,$l,0777,false);//l
									echo '复制至：'.$l.'<br>';
									jieqi_copyfile($dir."/".$file,$m,0777,false);//m
									echo '复制至：'.$m.'<br>';

									//小图默认使用大图的缩小版,缩小百分之50
									 include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
									$imgresize = new ImageResize();
									$imgresize->loadImg($dir."/".$file);
									$imgresize->resize(null,null,0.5);
									$imgresize->save($s,true);

// 									jieqi_copyfile($dir."/".$file,$s,0777,true);//s
									echo '移动至：'.$s.'缩小50%<br>';
								}
								echo '<br>';
								//$basedir = $dir.jieqi_getsubdir($uid);
								//处理图片
// 								if(jieqi_checkdir($basedir,true)){

// 								}
// 		         				echo $file."<br>";
		      				}
		     			}
		        	}
		        	closedir($dh);
		     	}
		   	}
		//开始运行
		echo '处理完成';
		exit;
	}

	public function usermember($param){
		$dataObj = $this->model('usermember');
		$data = $dataObj->usermember($param);
		$this->display($data,'usermember');
	}

	public function uservip($param){
		$dataObj = $this->model('usermember');
		$data = $dataObj->uservip($param);
		$this->display($data,'viparea');
	}
}
?>