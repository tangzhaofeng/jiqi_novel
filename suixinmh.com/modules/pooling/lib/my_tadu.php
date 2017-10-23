<?php
//3GAPI操作类
include_once ($GLOBALS['jieqiModules']['pooling']['path'] . '/class/iapi.php');
include_once ($GLOBALS['jieqiModules']['pooling']['path'] . '/class/baseApi.php');
include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
class MyTadu extends baseApi implements iApi{
	public $COPYRIGHTID = '55';
	public $SECRE = '3487aafa50e307b50f3e7f603c42d635';
	public $GET_LASTUPDATE_CHAPTER = 'http://openapi.tadu.com/api/getUpdateInfo';
	public $ADD_BOOK_URL = 'http://openapi.tadu.com/api/addBook';
	public $ADD_CHAPTER_URL = 'http://openapi.tadu.com/api/addChapter';
// 	var $KEY;
	//构造函数
	function MyTadu(){
		parent::initDB();
		$this->setKey();
	}
	/**
	 * 设置tadu密匙
	 * @see baseApi::setKey()
	 */
	function setKey(){
		$this->KEY = sha1($this->COPYRIGHTID.$this->SECRE);
	}
	/////////////定义添加书籍接口/////////////
	/*接口地址：http://183.61.112.45:88/PartnersBookInsert/BookInfo.aspx
	 请求方式：POST
	固定参数：
	参数名称	说明	类型	是否必要参数
	username	合作方用户名	字符串	是
	sign	签名参数	字符串	是
	bookname	书籍名称	字符串	是
	author	作者名称	字符串	是
	cpbookid	合作方书ID	数值	是
	detail	书籍简介	字符串	否
	category	分类Id(详见3G书城分类表)	数值	否(默认0)
	status	连载状态(1为连载 2为完结)	数值	否(默认连载)
	返回值以json格式返回结果，格式如下：
	成功：{"result":true,"errorCode":""}
	失败：{"result":false,"errorCode":"001"}
	*/
	function addBook($cpid,$data){//发送的数组
		if($data){ 
			extract($data);
		}else{return false;}
		//判断文章是否存在
		if(!$retBook = $this->get_lastupdate($cpid)) {//当查询更新状态出错，直接返回错误，程序跳过这本书
			return false;
		}
		if($retBook->result->bookid) return $retBook;//如果书存在，我们就直接返回整个书的更新状态对象

		if(!$bookname || !$authorname || !$cpid || !$coverimage || !$url){
			$this->outMessage('---->数据不完整！');
			return false;//对必填项进行验证
		}else{
			$message = array(
					'key' => $this->KEY,
					'cpid' => $cpid,
					'copyrightid' => $this->COPYRIGHTID,
					'coverimage' => $coverimage,
					'bookname' => $bookname,
					'authorname' => $authorname,
					'intro' => $intro,
					'classid' => $this->getCategory($classid),
					'serial' => $serial,
					'isvip' => $isvip,
					'url' => $url
			);
			if($ret = $this->request($this->ADD_BOOK_URL, 'POST', $message)){ //加书籍接口发送指令
				$ret = json_decode($ret);
				if($ret->code=='0'){//执行成功
					return $ret;
				}else{
					$this->outMessage('---->加书出错，错误编号：'.$ret->code.'.错误信息：'.$ret->message);
					return false;
				}
			}else{
				$this->outMessage('---->加书出错!网络错误');
				return false;
			}
		}
		//}
	}

	function addChapter($data){//发送的数组
		
		$rpfrom = array("", "&nbsp;", "", "",">","<","	");
		$tofrom = array(" ", " ", "", "","","","");

		if($data) extract($data);
		else return false;
		if(!$content) return true;
		if(!$bookid || !$title || !$content || !$chapterid || !$updatemode) return false;//对必填项进行验证
		else{
			$message = array(
					'key' => $this->KEY,
					'bookid' => $bookid,
					'copyrightid' => $this->COPYRIGHTID,
					'title' => str_replace($rpfrom, $tofrom, $title),
					'content' => $content,
					'chapternum' => $chapternum,
					'isvip' => $isvip,
					'chapterid' => $chapterid,
					'updatemode' => $updatemode

			);
			if($ret = $this->request($this->ADD_CHAPTER_URL, 'POST', $message)){//print_r($ret);exit;
				//if($isvip){//echo $ret;
				//print_r($message);exit;
				//}
				$ret = json_decode($ret);
				if($ret->code=='0'){//执行成功
					return true;
				}else{//print_r($ret);print_r($message);exit;
					$this->outMessage('---->章节出错，错误编号：'.$ret->code.'错误信息：'.$ret->message);
					return false;
				}
			}else return false;
		}
	}
	
	function outMessage($message = '网络错误！'){
		echo $message.'<br>';
	}

	//获取书的最新章节
	function get_lastupdate($cpid){
		if(!$cpid) {return false;}
		$message = array(
				'key' => $this->KEY,
				'cpid' => $cpid,
				'copyrightid' => $this->COPYRIGHTID
		);
		//$message = array('key'=>'79ae07826f9b22d0c42aaba2a13e4d8d7dd8f8a9','cpid'=>'1416','copyrightid'=>'55');
		if($ret = $this->request($this->GET_LASTUPDATE_CHAPTER, 'POST', $message)){//echo $ret;exit;
			$ret = str_replace('	', '', $ret);//ASCII=09的水平制表符在json_decode引起一场，转换失败
			$ret = json_decode($ret);
			return $ret;
		}else{
			return false;
		}
	}

	/////////////定义修改接口/////////////
	function updateBook($data){//发送的数组
		if($data) extract($data);
		$message = array(
				'key' => $this->KEY,
				'cpid' => $cpid,
				'copyrightid' => $this->COPYRIGHTID,
				'classid' => $this->getCategory($classid),
				'serial' => $serial

		);
		if($ret = $this->request(UPDATE_BOOK_URL, 'POST', $message)){ //加书籍接口发送指令
			$ret = json_decode($ret);
			if($ret->code=='0'){//执行成功
				return true;
			}else{
				$this->outMessage('---->修改完本状态出错，错误编号：'.$ret->code.'.错误信息：'.$ret->message);
				return false;
			}
		}else{
			$this->outMessage('---->修改完本状态出错!网络故障！');
			return false;
		}
	}
	/////////获取分类/////////
	/*	分类Id	短名称	长名称	频道
	 1	玄幻	奇幻大陆	男频
	2	情感	都市情感	畅销
	3	武侠	仗剑江湖	男频
	4	言情	浪漫言情	言情
	5	同人	同人续篇	女频
	6	科幻	科幻时空	男频
	7	修真	仙侠修真	男频
	8	网游	网游竞技	男频
	9	悬疑	惊悚悬疑	男频
	10	都市	激情都市	男频
	11	青春	青春校园	女频
	12	经典	文学经典	畅销
	13	生活	生活百科	畅销
	16	历史	历史传奇	男频
	17	军事	铁血军事	男频
	18	悬疑	悬疑	畅销
	19	名人	名人传记	畅销
	23	青春	青春	畅销
	26	古代	古代	女频
	27	现代	现代	女频
	28	纯爱	纯爱	女频
	29	悬疑	悬疑	女频
	30	架空	架空	女频
	31	穿越	穿越	女频
	32	网游	网游	女频
	33	奇幻	奇幻	男频
	34	竞技	竞技	男频
	35	经管	经管	畅销
	36	社科	社科	畅销
	37	都市	都市	畅销
	38	励志	励志	畅销
	39	仙侠	仙侠	女频
	41	校园	校园	女频*/
	function getCategory($category=0){
		//格式：array(本站分类=>3G分类);
		$sort = array(
				1=>99,
				2=>103,
				3=>107,
				4=>112,
				5=>111,
				6=>109,
				7=>112,
				8=>108,
				9=>113,
				10=>128,
				11=>109,
				12=>104
		);
		if(isset($sort[$category])) return $sort[$category];
		else return 103;
	}
	function request($url, $mode, $params=array(), $header = 'Content-Type: text/plain; charset=utf-8;') {
		if($params) $this->arrayRecursive($params, 'urlencode', true);
		$query = http_build_query($params);
		$urlarr = parse_url($url);
		$responseText = $this->fsockPost($urlarr["path"], $urlarr["host"],$query);
// 		$this->out_msg ( '---->返回值len:'.strlen($responseText));
		$start = strpos($responseText, '{', 1);
		$len = strrpos($responseText, '}', 1) - $start + 1;
		$responseText = substr($responseText, $start, $len);
		return $responseText;
		// 		}
	}
// 	function request($url, $mode, $params=array(), $header = 'Content-Type: text/plain; charset=utf-8;') {
// 		$time_out = "60";
// 		if($params) $this->arrayRecursive($params, 'urlencode', true);
// 		$query = http_build_query($params);
// 		$urlarr     = parse_url($url);
// 		$errno      = "";
// 		$errstr     = "";
// 		$transports = "";
// 		$responseText = "";
// 		if($urlarr["scheme"] == "https") {
// 			$transports = "ssl://";
// 			$urlarr["port"] = "443";
// 		} else {
// 			$transports = "tcp://";
// 			if(!isset($urlarr['port'])){$urlarr['port'] = "80";}
// 		}
// // 		$fp=@fsockopen($urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
// // 		if(!$fp) {
// // 			die("ERROR: $errno - $errstr<br />\n");
// // 		}else{
// 			// 			fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
// 			// 			fputs($fp, "Host: ".$urlarr["host"].':'.$urlarr["port"]."\r\n");
// 			// 			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
// 			// 			fputs($fp, "Content-length: ".strlen($query)."\r\n");
// 			// 			fputs($fp, "Connection: close\r\n\r\n");
// 			// // 			fputs($fp, "Connection: keep-alive\r\n\r\n");
// 			// 			fputs($fp, $query . "\r\n");
// 			// 			while(!feof($fp)) {
// 			// 				$responseText .= @fgets($fp, 1024);
// 			// 			}
// 			// 			fclose($fp);
// 			print_r($urlarr);
// 			echo $url;
// 			exit;
// 			$responseText = $this->fsockPost($urlarr["path"], $urlarr["host"],$query);
				
				
// 			$start = strpos($responseText, '{', 1);
// 			$len = strrpos($responseText, '}', 1) - $start + 1;
// 			$responseText = substr($responseText, $start, $len);
// 			return $responseText;
// // 		}
// 	}
	/**
	 * 塔读推送
	 * <br>实现iapi接口中定义的推送方法
	 *
	 * @param unknown $channleid
	 * @param unknown $article
	 *        	2014-7-1 上午8:58:18
	 */
	function push($channleid, $article) {
		$articleLib = $this->load ( 'article', 'article' );
		$channleLib = $this->load ( 'channel', 'pooling' ); // 加载channel自定义类
		$channle = $channleLib->get ( $channleid );
		$rpfrom = array (
				"",
				"&nbsp;",
				"",
				"",
				">",
				"<",
				" "
		);
		$tofrom = array (
				" ",
				"&",
				"",
				"",
				"",
				"",
				""
		);
		$startupdate = false; // 初始化更新标记

		$fullflag = $cpcid = $chapternum = $i = $k = $kk = 0;
		$chapters = array ();
// 		$setting = array ();
// 		// 推送配置
// 		if ($article ['setting']) {
// 			eval ( '$article[\'setting\'] = ' . $article ['setting'] . ';' ); // 单本书的配置
// 			$setting = $article['setting'];
// 		} else
// 			$setting = $channle['setting'];
		// 开始更新VIP章节
		$isvip = 0;
		$articletype = intval ( $article ['articletype'] );
		if ($articletype > 0) {
			$isvip = 1;
		}
		if ($article['image']) {
			$cover = JIEQI_URL . "/api/api_image" . $article['image'];
		} else {
			$cover = jieqi_geturl ( 'article', 'cover', $article ['articleid'], 's', $article ['imgflag'] );
		}

		$message = array (
				'cpid' => $article ['articleid'],
				'coverimage' => $cover,
				'bookname' => jieqi_gb2utf8 ( $article ['articlename'] ),
				'authorname' => jieqi_gb2utf8 ( $article ['author'] ),
				'intro' => jieqi_gb2utf8 ( $article ['intro'] ),
				'classid' => $article ['sortid'],
				'serial' => 1,
				'isvip' => $isvip,
				'url' => JIEQI_URL . '/book/' . $article ['articleid'] . '.htm'
		);
		if (!$retArticle = $this->addBook ( $article ['articleid'], $message )) {
			$this->out_msg_err ( '---->向API推送《' . $article ['articlename'] . '》时出错，跳过本书！' );
			return;
		} else { // 更新章节开始
			$startupdate = false;
			if($retArticle->result->chapterid && $retArticle->result->chapternum){
				$this->out_msg ( '---->上次推送信息：' );
				$this->out_msg ( '---->章节名称：' . jieqi_utf82gb ( $retArticle->result->chaptername ) );
				$this->out_msg ( '---->章节ID：' . $retArticle->result->chapterid );
				// 更新到的章节位置
				if (!$article ['lastchapterid']) {
					$this->out_msg_err ( '---->手动维护最后推送的章节Id' );
					return;
				}
			}else{
				$this->out_msg ( '---->新书第一次推送' );
				$startupdate = true;
			}
			$chapternum = $retArticle->result->chapternum ? $retArticle->result->chapternum : 1;
		} // 更新章节结束
		$chapters = $this->getChapters ( $article ['articleid'] );
		$totalchapter = count ( $chapters );
		// 开始更新啦
// 		if (! $cpcid)
// 			$startupdate = true;
// 		else
// 			$startupdate = false; // 初始化更新标记
				                      // $chapterid = $retArticle->chapterid ? $retArticle->chapterid :0;
				                      
		foreach ( $chapters as $c ) {
			$fullflag = 0;
			$k ++;
			if (! $startupdate) {
				if ($article ['lastchapterid'] == $c ['cpchapterid']) {
					$startupdate = true;
					if (($totalchapter - $k) > 0) {
						$this->out_msg ( '---->《' . $article ['articlename'] . '》推送到[' . $c ['chaptername'] . '],还有<b>' . ($totalchapter - $k) . '</b>篇需要推送' );
					} else{
						$this->out_msg ( '---->无章节需要推送!' );
						return;
					}
				} else {
					continue;
				}
			} else { // 开始更新章节
				if ($k == 1) {
					$this->out_msg( '---->《' . $article ['articlename'] . '》有<b>' . ($totalchapter) . '</b>篇章节需要推送');
				}
				// 限制更新章节
				// if($setting['open']){
				// if($setting['data']!='allchapter'){
				// //日更新章节数
				// if(date('Ymd',$article['lastupdate'])==date('Ymd',time()) && $article['daychapters']>0){//先前有更新
				// $daychapters = $article['daychapters']+$kk+1;
				// }else{
				// $daychapters = $kk+1;
				// }
				// if($setting['daychapter']>0 && $daychapters>$setting['daychapter']){
				// $ApiCP->out_msg('<font color=red>更新停止！系统设置每天限制更新'.$setting['daychapter'].'章</font>');
				// break;
				// }
				// }else{
// 				$daychapters = $kk + 1;
				// }
				// 落后本站更新设置
				// if($setting['sleepchapter']>0){
				// if(($totalchapter-$k)<$setting['sleepchapter'] && !$article['full']){
				// $ApiCP->out_msg('<font color=red>更新停止！系统设置始终落后本站'.$setting['sleepchapter'].'章</font>');
				// break;
				// }
				// }
				// }
				// $chapterid++;
				$message = array (
						'bookid' => $article ['articleid'] . '0' . $this->COPYRIGHTID,
						'title' => jieqi_gb2utf8 ( str_replace ( $rpfrom, $tofrom, $c ['chaptername'] ) ),
						'content' => jieqi_gb2utf8 ( $c ['content'] ),
						'chapternum' => $chapternum,
						'isvip' => $c ['isvip'],
						'chapterid' => $c ['cpchapterid'],
						'updatemode' => 1
				);
				if ($this->addChapter ( $message )) {
					$kk ++;
					$chapternum ++;
					$lastchapterid = $c ['cpchapterid'];
					$lastchapter = $c ['chaptername'];
					//full=1 且 cpchapterid是最后一个章节
					if ($article ['full'] && $chapters [$totalchapter - 1] ['cpchapterid'] == $c ['cpchapterid']) {
						$fullflag = 1;
						//没有更新的url
// 						$this->updateBook ( array (
// 								'cpid' => $article ['articleid'],
// 								'classid' => $article ['sortid'],
// 								'serial' => 0
// 						) );
					}
					if (! $kk)
						$outchapters = $article ['outchapters'];
					else
						$outchapters = $article ['outchapters'] + $kk;
// 					$this->db->init ( 'article', 'paid', 'pooling' );
// 					$this->db->edit ( $article['paid'], array (
// 							'lastchapterid' => $lastchapterid,
// 							'lastchapter' => $lastchapter,
// 							'outchapters' => $outchapters,
// 							'fullflag' => $fullflag,
// 							'lastdate' => JIEQI_NOW_TIME
// 					) );
					
					$this->out_msg ('---->'. $kk . '、' . $c ['chaptername'] . '...ok' );
					// 同步数据池的最后更新的
					$article ['lastvolumeid'] = 0;
					$article ['lastvolume'] = '';
					$article ['lastchapterid'] = $lastchapterid;
					$article ['lastchapter'] = $lastchapter;
					$article ['outchapters'] = $outchapters;
					$article ['fullflag'] = $fullflag;
					$article ['lastdate'] = JIEQI_NOW_TIME; // 推送时间
					if(is_array($article ['setting'])){//第一次同步时，数组转字符串
						$article ['setting'] =  $this->arrayeval($article ['setting']);
					}
					unset ( $article ['author'] );
					unset ( $article ['sortid'] );
					unset ( $article ['keywords'] );
					unset ( $article ['siteid'] );
					unset ( $article ['size'] );
					unset ( $article ['articletype'] );
					unset ( $article ['full'] );
					unset ( $article ['imgflag'] );
					$this->db->init ( 'article', 'paid', 'pooling' );
					if (!$this->db->edit ( $article ['paid'], $article )) {
						$this->out_msg_err ( '---->数据池文章《' . $article ['articlename'] . '》，状态同步失败！' );
					}
				} else {
					$kk ++;
					$this->out_msg_err ( '---->'.$kk . '、 ' . $c ['chaptername'] . '。推送失败，跳过本书继续推送！' );
					return;
				}
			}
		}
		if (! $startupdate) {
			$this->out_msg_err ( '---->未匹配最后推送的章节，手动维护最后推送的章节ID' );
		}
// 		$daychapters = 0;
// 		$lastchapterid = $lastchapter = '';
	}
}
?>