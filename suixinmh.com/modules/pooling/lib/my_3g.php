<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iapi.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseApi.php');
include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * 3g推送接口类，继承baseApi抽象类，实现iApi接口
 * @author chengyuan  2014-7-3
 *
 */
class My3g extends baseApi implements iApi {



	const HOST = 'book.interface.3gsc.com.cn';
// 	const USERNAME = "testuser";
// 	const PASSWORD = "C7FA8E2E-8BBE-4128-A28F-5DAC4725C081";
	const USERNAME = "shuhai";
	const PASSWORD = "1964EDD5-3747-4659-B514-A590B8832427";
// 	const SIGN = md5("shuhai1964EDD5-3747-4659-B514-A590B8832427");

	var $errmsg = array (
			"001" => "帐号或密码错误",
			"002" => "IP地址未登记",
			"003" => "书或作者名为空",
			"004" => "书籍已存在",
			"005" => "合作方书籍Id为0",
			"006" => "添加数据失败",
			"007" => "提交内容存在空数据",
			"008" => "章节已存在",
			"009" => "书籍不存在",
			"010" => "章节名重名",
			"011" => "作品状态不能为空",
			"012" => "大分类不存在",
			"013" => "小分类错误",
			"014" => "书籍id已存在",
			"015" => "提交的书籍id不在限制范围"
	);


	function __construct() {
		parent::initDB();
		$this->setKey();
	}
	/**
	 * 设置3g密匙
	 * @see baseApi::setKey()
	 */
	function setKey(){
		$this->KEY = md5(My3g::USERNAME.My3g::PASSWORD);
	}
	/**
	 * 将数组转化为用&连接的字符串,并且使用urlencode编码。
	 * <br>key=value&key=value
	 * @param unknown $arr
	 * 2014-7-11 下午3:43:37
	 */
	private function flat($arr) {
		if (is_array($arr)){
			$sets = array();
			foreach ($arr AS $key => $val)
				$sets[] = $key . '=' . urlencode($val);
			return implode("&",$sets);
		}
	}
	function get_lastupdate($cpbid){
		$reqStr = array('username'=>My3g::USERNAME,'sign'=>$this->KEY,'cpbookid'=>$cpbid);
		$responseData =  $this->fsockPost('/index.php?m=PartnerBookInsert&a=getlastmenu',My3g::HOST,$this->flat($reqStr));
		$responseData = strstr($responseData, '{');
		$patton = strstr($responseData, '}');
		$responseData = str_replace($patton,"",$responseData)."}";
		$de_json = json_decode($responseData, true);
		if($de_json["result"] == true){
			return $de_json;//有上次推送信息
		}else if($de_json["errorCode"] == "009"){
			return true;//无推送记录
		}else{
			$this->out_msg('---->取更新信息出错,原因：'.$this->errmsg[$de_json["errorCode"]]);
			return false;//没有上次推送的信息
		}
	}
	function addChapter($data){
		extract($data);
// 		global $username;
// 		global $password;
// 		global $sign;
// 		global $host;
// 		//$cpbookid 合作方书ID;$cpmenuid 合作方章节ID;$menuName 章节名称;$isvip 是否收费(0为免费,1为收费);$username 章节内容
// 		$num_args=func_num_args();
// 		if($num_args>0){
// 			$args=func_get_args();
// 			$cpbookid=$args[0];
// 			$cpmenuid=$args[1];
// 			$menuName=$args[2];
// 			$isvip=$args[3];
// 			$content=$args[4];
// 		}

		$reqStr = array('username'=>My3g::USERNAME,'sign'=>$this->KEY,'cpbookid'=>$cpbookid,'cpmenuid'=>$cpmenuid,'menuName'=>$menuName,'isvip'=>$isvip, 'content'=>$content);
// 		$header = "POST /index.php?m=PartnerBookInsert&a=menuinfo HTTP/1.1\r\n";
// 		$header .= "Host: ".My3g::HOST.":80\r\n";
// 		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
// 		$header .= "Content-length: ".strlen($this->flat($reqStr))."\r\n";
// 		$header .= "Connection: Close\r\n\r\n";		// POST表单数据
// 		$header .= $this->flat($reqStr)."\r\n";
// 		$fp = fsockopen(My3g::HOST , 80, $errno, $errstr, 30);
// 		fwrite($fp, $header);
// 		$responseData = '';
// 		while (!feof($fp)) {
// 			$responseData .= fgets($fp, 1024);
// 		}
// 		fclose($fp);
		$responseData = $this->fsockPost('/index.php?m=PartnerBookInsert&a=menuinfo', My3g::HOST, $this->flat($reqStr));
		$responseData = strstr($responseData, '{');
		$patton = strstr($responseData, '}');
		$responseData = str_replace($patton,"",$responseData)."}";
		$de_json = json_decode($responseData, true);
		if($de_json["errorCode"]=="" && $de_json["result"]==true){
			return true;
		}else{
			$this->out_msg ( '---->错误信息：'.$this->errmsg[$de_json["errorCode"]]);
			return false;
		}
	}
	/**
	 * 存在推送记录的书返回{"result":true,"CPMenuId":"11111","MenuName":"XXX","IsVip":"1"}格式数组,
	 * <br>新书推送成功，返回true
	 * <br>错误，返回false
	 * (non-PHPdoc)
	 * @see iApi::addBook()
	 */
	function addBook($cpBid,$data){
		if($book = $this->get_lastupdate($cpBid)){
			if($book['result']) return $book;//上次推送信息
			//新推送
			$responseData =  $this->fsockPost('/index.php?m=PartnerBookInsert&a=bookinfo',My3g::HOST,$this->flat($data));
			$responseData = strstr($responseData, '{');
			$patton = strstr($responseData, '}');
			$responseData = str_replace($patton,"",$responseData)."}";
			$de_json = json_decode($responseData, true);
			if($de_json["errorCode"]=="" && $de_json["result"]==true){
				return true;
			}else{
				$this->out_msg('---->加书出错，错误编号：'.$de_json["errorCode"].'错误信息：'.$this->errmsg[$de_json["errorCode"]]);
				return false;
			}
		}else{
			$this->out_msg('---->推送出错');
			return false;
		}







// 		return $responseData;
	}
// 	function addBook($bookname, $author, $cpbookid, $detail, $category, $stype, $status, $cover, $tag){
// // 		$header = "POST /index.php?m=PartnerBookInsert&a=bookinfo HTTP/1.1\r\n";
// // 		$header .= "Host: {$host}:80\r\n";
// // 		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
// // 		$header .= "Content-length: ".strlen(flat($reqStr))."\r\n";
// // 		$header .= "Connection: close\r\n\r\n";		// POST表单数据
// // 		$header .= flat($reqStr)."\r\n";//echo $header;exit;
// // 		$fp = fsockopen($host , 80, $errno, $errstr, 30);
// // 		fwrite($fp, $header);
// // 		$responseData = '';
// // 		while (!feof($fp)) {
// // 			$responseData .= fgets($fp, 1024);
// // 		}
// // 		fclose($fp);


// 		$responseData =  $this->fsockPost('/index.php?m=PartnerBookInsert&a=bookinfo',$this->HOST,$this->flat($reqStr));


// 		$responseData = strstr($responseData, '{');
// 		$patton = strstr($responseData, '}');
// 		$responseData = str_replace($patton,"",$responseData)."}";

// 		return $responseData;
// 	}
	function request($url, $mode, $params=array(), $header = 'Content-Type: text/plain; charset=utf-8;'){
	}
// 	//获取安全密匙
// 	private function getSecurityid($mcpBookId){
// 		if(!$mcpBookId)
// 			$this->printfail(LANG_ERROR_PARAMETER);
// 		return strtoupper ( sha1 ( MyMobile::MCPID . $mcpBookId . MyMobile::PASSWORD ) );
// 	}
	/**
	 * 3g推送
	 * <br>实现iapi接口中定义的推送方法
	 *
	 * @param unknown $channleid
	 * @param unknown $paid			paid数组
	 * 2014-7-1 上午8:58:18
	 */
	function push($channleid, $article) {
			// include_once($jieqiModules['article']['path'].'/class/article.php');

		// include_once($jieqiModules['obook']['path'].'/class/obook.php');
			// $obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
			// include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
			// $ochapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');

		// $jqdb=new JieqiDatabase();
			// $dbcon=$jqdb->getInstance();
			// include_once($jieqiModules['article']['path'].'/api/g3/function.php');
			// $errmsg = array("001"=>"帐号或密码错误", "002"=>"IP地址未登记", "003"=>"书或作者名为空", "004"=>"书籍已存在", "005"=>"合作方书籍Id为0", "006"=>"添加数据失败", "007"=>"提交内容存在空数据", "008"=>"章节已存在", "009"=>"书籍不存在", "010"=>"章节名重名");

		// $usebook = "(".$_REQUEST['id'].")";//禁止书单
			// $sql="SELECT id,articleid,articlename,process,start FROM ".jieqi_dbprefix('article_api')." WHERE articleid in ".$usebook." AND type=7 and process=0";
		$articleid = $article ['articleid'];
		$setting = $article ['setting'];
		// $article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
		// $article=$article_handler->get($articleid);
		// if(!is_object($article)){$errorMessage = "返回错误代码信息== &gt请仔细检查书籍";$latestchap= "";continue;}
		$articlename = preg_replace ( '/&#(\d+);|&amp;nbsp;|<br\s\/>|&nbsp;/', '', $article ['articlename'] );
		$bookname = jieqi_gb2utf8 ( $articlename );
		$author = preg_replace ( '/&#(\d+);|&amp;nbsp;|<br\s\/>|&nbsp;/', '', $article ['author'] );
		$author = jieqi_gb2utf8 ( $author );
		$intro = preg_replace ( '/&#(\d+);|&amp;nbsp;|<br\s\/>|&nbsp;/', '', $article ['intro'] );
		$detail = jieqi_gb2utf8 ( $intro );
		// $bookCategory=array(0, 1, 10, 7, 32, 6, 7, 10, 16, 16, 9, 5, 4);
		//书海分类1-12
		$bookCategory = array (
				0,//占位
				1,
				10,
				7,
				8,
				6,
				7,
				8,
				16,
				16,
				9,
				30,
				26
		);
		$sbookCategory = array (
				0,//占位
				33,
				60,
				49,
				53,
				40,
				153,
				55,
				77,
				171,
				56,
				222,
				92
		);
		// $shuhai_cata= $article->getVar('sortid');
		$category = $bookCategory [$article ['sortid']]?$bookCategory [$article ['sortid']]:26;
		$stype = $sbookCategory [$article ['sortid']]?$sbookCategory [$article ['sortid']]:92;
		if ($article ['full'] == 0) {
			$status = 1;//连载
		} else {
			$status = 2;//完本
		} // echo $articlename."<hr>".$author."<hr>".$articleid."<hr>".$intro."<hr>".$category."<hr>".$stype."<hr>".$status."<hr>".$cover;exit;
		$cover = jieqi_geturl ( 'article', 'cover', $articleid, 's', $article ['imgflag'] );
		// $tag = "";
		$tag = $article ['keywords'];
		if (! $tag) {
			$tag = $article ['articlename'];
		}
		$tag = jieqi_gb2utf8 ( str_replace ( " ", ',', $tag ) );
		$data = array (
				'username' => My3G::USERNAME,
				'sign' => $this->KEY,
				'bookname' => $bookname,
				'author' => $author,
				'cpbookid' => $articleid,
				'detail' => $detail,
				'category' => $category,
				'stype' => $stype,
				'status' => $status,
				'cover' => $cover,
				'tag' => $tag
		);
		$ret = $this->addBook ( $articleid, $data );
		if (! $ret) {
			$this->out_msg_err ( '---->向API推送《' . $article ['articlename'] . '》时出错，跳过本书！' );
			return;
		} else {
			$startupdate = false; // 开始推送标识
			if ($ret ['CPMenuId']) {
				$this->out_msg ( '---->上次推送信息' );
				$this->out_msg ( '---->CPMenuId：' . $ret ['CPMenuId'] );
				$this->out_msg ( '---->shuhaiId：' . $articleid );
				$this->out_msg ( '---->章节名称：' . jieqi_utf82gb ( $ret ['MenuName'] ) );
				$this->out_msg ( '---->IsVip：' . $ret ['IsVip'] );
				// 更新到的章节位置
				if (! $article ['lastchapterid']) {
					$this->out_msg_err ( '---->手动维护最后推送的章节Id' );
					return;
				}
			} else {
				$this->out_msg ( '---->新书第一次推送' );
				$startupdate = true;
			}
		}
		$chapters = $this->getChapters ( $articleid );
		$totalchapter = count ( $chapters );
		for($i = 0, $order = 1, $ps = 0; $i < $totalchapter; $i ++, $order ++) {
			$c = $chapters [$i];
			if (! $startupdate) {
				// 定位上次推送的章节
				if ($article ['lastchapterid'] != $c ['cpchapterid']) {
					continue;
				} else {
					$startupdate = true;
					if (($totalchapter - $order) > 0) {
						$this->out_msg ( '---->《' . $article ['articlename'] . '》推送到[' . $c ['chaptername'] . '],还有<b>' . ($totalchapter - $order) . '</b>篇需要推送' );
					} else {
						$this->out_msg ( '---->无章节需要更新。' );
						return;
					}
				}
			} else {
				$ps ++; // 推送章节计数器
				       // 开始推送
				if ($order == 1) {
					// 新书，第一章开始推送
					$this->out_msg ( '---->《' . $article ['articlename'] . '》有<b>' . ($totalchapter) . '</b>篇章节需要推送' );
				}
				$data = array (
						'cpbookid' => $articleid,
						'cpmenuid' => $c ['cpchapterid'],
						'menuName' => jieqi_gb2utf8 ( $c ['chaptername'] ),
						'isvip' => $c ['isvip'],
						'content' => jieqi_gb2utf8 ( $c ['content'] )
				);
				// $responseData = addChapter($articleid, $av->getVar('chapterid'), iconv("GBK","UTF-8//IGNORE",$chaptername), 0, iconv("GBK","UTF-8//IGNORE",$content));
				if ($this->addChapter ($data)) {
					$outchapters = $article ['outchapters'] + $ps;
					$this->db->init ( 'article', 'paid', 'pooling' );
					$this->db->edit ( $article ['paid'], array (
							'lastchapterid' => $c ['cpchapterid'],
							'lastchapter' => $c ['chaptername'],
							'outchapters' => $outchapters,
							'fullflag' => $article ['full'],
							'lastdate' => JIEQI_NOW_TIME
					) );
					$this->out_msg ( '---->' . $ps . '、' . $c ['chaptername'] . '...ok' );
					if ($setting ['daychapter'] && is_numeric ( $setting ['daychapter'] ) && $setting ['daychapter'] > 0 && $setting ['daychapter'] == $ps) {
						$this->out_msg ( '---->每天只推送' . $ps . '章' );
						break;
					}
				} else {
					$this->out_msg_err ( '---->' . $ps . '、' . $c ['chaptername'] . '推送失败，跳过本书继续推送！' );
					return;
				}
				// 指定章节或者第一个章节
			}
		}
		if (! $startupdate) {
			$this->out_msg_err ( '---->未匹配最后推送的章节，通过章节名称手动维护最后推送的章节ID' );
		}
	}
}
?>