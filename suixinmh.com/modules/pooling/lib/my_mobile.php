<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iapi.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iSynchronization.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseApi.php');
include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * mobile推送接口类，继承baseApi抽象类，实现iApi接口
 * @author chengyuan  2014-7-3
 *
 */
class MyMobile extends baseApi implements iApi,iSynchronization {
	//移动平台MCP标识，类似于登陆用户名
	const MCPID = 'szcb0514';
	const HOST = '202.91.242.108';
// 	const QQ_URL = 'http://open.book.qq.com/';
// 	const USERNAME = 'shuhaihuyajie';
	const PASSWORD = 'szcb#0514$20120313289';
	const GET_LOGIN_URL = 'http://open.book.qq.com/push/login?username=shuhaihuyajie&password=shuhai2012';
	const GET_LASTUPDATE_CHAPTER = 'http://open.book.qq.com/push/getUpdateInfo';
	const ADD_BOOK_URL = 'http://open.book.qq.com/push/addBook';
	const ADD_CHAPTER_URL = 'http://open.book.qq.com/push/addChapter';
	var $msg =  array(
			'0'=>'移动平台接收到书海网 MCP平台的内容更新同步请求，已加入处理队列',
			'1000'=>'参数非法',
			'1001'=>'安全校验失败',
			'2001'=>'非法的内容标识',
			'2002'=>'非法的卷顺序号',
			'2003'=>'非法的章节顺序号',
			'2004'=>'章节重复',
			'100'=>'更新章节成功，提交审核失败',
			'101'=>'章节段落过大',
			'102'=>'书名、卷名或章名超过字数限制',
			'103'=>'卷名重复[新增返回码]，新增加的卷卷名称同存在的卷名称重复',
			'2997'=>'XML报文读超时',
			'2998'=>'XML报文解析失败',
			'2999'=>'其他错误',
			'3999'=>'图书正在更新中',
			'9999'=>'系统升级准备中。注：只有在升级准备中阶段才会返回，如果已经进入升级阶段，则CP侧调用时发生为网络错误而非9999错误。增加该错误码主要为了保证在升级时不要存在待处理章节时冲起造成数据不完整性。'
	);


	function __construct() {
		parent::initDB();
		$this->setKey();
	}
	/**
	 * 设置qq密匙
	 * @see baseApi::setKey()
	 */
	function setKey(){
	}
	function get_lastupdate($cpbid){
	}
	function addChapter($message){
	}
	function addBook($cpBid,$data){
		//移动平台不实现此方法，通过移动MCP平台上书，返回移动端ID
	}
	function request($url, $mode, $params=array(), $header = 'Content-Type: text/plain; charset=utf-8;'){
	}
	//获取安全密匙
	private function getSecurityid($mcpBookId){
		if(!$mcpBookId)
			$this->printfail(LANG_ERROR_PARAMETER);
		return strtoupper ( sha1 ( MyMobile::MCPID . $mcpBookId . MyMobile::PASSWORD ) );
	}
	protected  function commendBookInfoUpdate($mcpBookId,$articleId){
// 		$host = '211.140.7.155'; // <!-- host变量没有写 -->
// 		$securityid = strtoupper ( sha1 ( MyMobile::MCPID . $mcpBookId . MyMobile::PASSWORD ) );
		// <!-- 查询公众章节-->
// 		$this->db->init ( 'article', 'articleid', 'article');
// 		$this->db->setCriteria ( new Criteria ( 's.articleid', $articleId ) );
// 		$this->db->criteria->setTables ( jieqi_dbprefix ( 'article_article' ) . "  AS a LEFT JOIN " . jieqi_dbprefix ( 'article_statamout' ) . " AS s ON a.articleid=s.articleid" );
// 		$this->db->criteria->setFields ( 'a.size,s.visit,s.vote,s.goodnum');

		$setarticle = $this->db->selectsql ('SELECT a.size,s.visit,s.vote,s.goodnum FROM '.jieqi_dbprefix ( 'article_article' ).' a left join '.jieqi_dbprefix ( 'article_statamout' ).' s on a.articleid = s.articleid where  s.articleid = '.$articleId);
		if(!$setarticle[0]){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$size = ceil($setarticle[0]['size']/2); // <!-- 总字数 -->
		$allvisit = $setarticle[0]['visit'] ? $setarticle[0]['visit'] : 0; // <!-- 点击数 -->
		$allvote = $setarticle[0][ 'vote' ] ? $setarticle[0]['vote'] : 0;// <!-- 推荐数 -->
		$goodnum = $setarticle[0][ 'goodnum' ] ? $setarticle[0]['goodnum'] : 0; // <!-- 收藏数 -->
		                                        // <!-- /查询公众章节-->

		$datas = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
		<commendBookInfoUpdate>\r\n
		<mcpId>".MyMobile::MCPID."</mcpId>\r\n
		<books>\r\n
		<book>\r\n
		<bookId>{$mcpBookId}</bookId>\r\n
		<wordsCount>{$size}</wordsCount>\r\n
		<stowCount>{$goodnum}</stowCount>\r\n
		<viewCount>{$allvisit}</viewCount>\r\n
		<commendCount>{$allvote}</commendCount>\r\n
		<securityId>".$this->getSecurityid($mcpBookId)."</securityId>\r\n
			</book>\r\n
			</books>\r\n
			</commendBookInfoUpdate>";
		$datas = jieqi_gb2utf8 ( $datas );
		$length = strlen ( $datas ); // 参数长度
		                          // <!-- 创建socket连接 -->
		$fp = fsockopen ( MyMobile::HOST, 80, $errno, $errstr, 30 );
		if(!$fp){
			echo "ERROR: $errno - $errstr<br />\n";
			break;
		}
		$header = "POST /CP/commendBookInfoUpdate HTTP/1.1\r\n";
		$header .= "Host:".MyMobile::HOST.":80\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: {$length}\r\n";
		$header .= "Connection: Close\r\n\r\n";
		$header .= $datas . "\r\n";
		fwrite ( $fp, $header );
		$backdata = '';
		while ( ! feof ( $fp ) ) {
			$backdata .= fgets ( $fp, 1024 );
		}
		fclose ( $fp );
		preg_match ( '/.*(close)\s*(\d*)\s*/is', $backdata, $match );
		$backinfoNo = $match [2];
		switch ($backinfoNo) {
			case 0 :
				$backinfo = "平台接收到MCP平台的更新消息并处理成功!";
				break;
			case 1000 :
				$backinfo = "参数非法";
				break;
			case 1001 :
				$backinfo = "安全校验失败";
				break;
			case 2999 :
				$backinfo = "其他错误";
				break;
		}
		$this->out_msg('---->同步图书信息结果：'.$backinfo);
// 		if ($backinfoNo == 0) {
// // 			jieqi_jumppage ( 'http://www.shuhai.com/modules/api/admin/articles.php?api=mobile', LANG_DO_SUCCESS, $backinfo );
// 		} else {
// // 			jieqi_jumppage ( 'http://www.shuhai.com/modules/api/admin/articles.php?api=mobile', '书籍信息更新失败！', $backinfo );
// 		}

	}
	/**
	 * mobile推送
	 * <br>实现iapi接口中定义的推送方法
	 *
	 * @param unknown $channleid
	 * @param unknown $article			推送的数据池文章
	 *        	2014-7-1 上午8:58:18
	 */
	function push($channleid, $article) {
		$setting = $article ['setting'];//数组
			// header('Content-Type:text/xml;');//测试使用
			// mobile:395908003
			// shuhai:4248
			// 章节序号，章节名称和章节id号必须修改
			// $host = '211.140.7.155';
			// $host = '211.140.7.155'; //(联调平台)**
			// $host = '211.140.17.94'; //(移动机房)**
			// $host = '202.91.242.108'; // (电信机房)**
			// $mcpid = "szcb0514";
			// $bookId = $_POST ['bookMobileId']; // <!-- 书籍移动平台ID-->
			// $bookShuhaiId = $_POST ['bookShuhaiId'];
			// 370250019 317
			// $again = 0;//再一次尝试
		if (! $article ['apiId']) {
			$this->out_msg_err ( '---->请维护《' . $article ['articlename'] . '》移动MCP上的ID' );
		} else {
			$articleLib = $this->load ( 'article', 'article' );
			$articleLib->instantPackage ( $article ['articleid'] );
			$bookShuhaiId = $article ['articleid'];
			$bookId = $article ['apiId']; // <!-- 书籍移动平台ID-->
			$securityid = $this->getSecurityid ( $bookId );




			// <!-- 获取图书信息-->
// 			$header = "GET /CP/queryContent?mcpid=" . MyMobile::MCPID . "&bookid=$bookId&securityid=$securityid HTTP/1.1\r\n";
// 			$header .= "Host: " . MyMobile::HOST . ":80\r\n";
// 			$header .= "Connection: keep-alive\r\n\r\n";
// 			$fp = fsockopen ( MyMobile::HOST, 80, $errno, $errstr, 30 );
// 			fwrite ( $fp, $header );
// 			$backdata = '';
// 			while ( ! feof ( $fp ) ) {
// 				$backdata .= fgets ( $fp, 128 );
// 			}
// 			fclose ( $fp );



			$backdata = $this->fsockPost("/CP/queryContent?mcpid=" . MyMobile::MCPID . "&bookid=$bookId&securityid=$securityid", MyMobile::HOST);






			$backdata = jieqi_utf82gb ( $backdata );
			// <!-- 处理图书信息-->
			preg_match ( "/(.+)\<bookId\>(.+)\<\/bookId\>/isU", $backdata, $matches );
			$bookId = $matches [2]; // 手机阅读平台书籍ID

			preg_match ( "/(.+)\<chapterId\>(.+)\<\/chapterId\>/isU", $backdata, $matches );
			$mcp_chapterId = $matches [2]; // 最新章节ID
			preg_match ( "/(.+)\<chapterIdx\>(.+)\<\/chapterIdx\>/isU", $backdata, $matches );
			$chapterIdx = $matches [2]; // 最新章节顺序号
			preg_match ( "/(.+)\<name\>(.*?)\<\/name\>/isU", $backdata, $matches );
			$name = $matches [2]; // 最新章节名称
			preg_match ( "/(.+)\<volumeIdx\>(.+)\<\/volumeIdx\>/isU", $backdata, $matches );
			$volumeIdx = $matches [2]; // 最新卷序号
			preg_match ( "/(.+)\<volumeName\>(.+)\<\/volumeName\>/isU", $backdata, $matches );
			$volumeName = $matches [2]; // 最新卷名称

			$this->out_msg ( "---->《" . $article ['articlename'] . "》上次推送记录" );
			$this->out_msg ( "---->MCPID=>{$bookId}" );
			$this->out_msg ( "---->卷序号=>{$volumeIdx}" );
			$this->out_msg ( "---->卷名称=>{$volumeName}" );
			$this->out_msg ( "---->章节ID=>{$mcp_chapterId}" );
			$this->out_msg ( "---->章节序号=>{$chapterIdx}" );
			$this->out_msg ( "---->章节名称=>" . $name );
			if (! $name || ! $chapterIdx || ! $bookId) {
				$this->out_msg_err ( '---->MCP平台返回无效数据,MCPID='.$article ['apiId'].'，请仔细核对MCPID' );
			} elseif (! $article ['lastchapterid']) {
				$this->out_msg_err ( '---->请维护《' . $article ['articlename'] . '》的最后推送的章节ID' );
			} else {
				// 获取数据池文章所有章节 jieqi_pooling_chapter
				$this->db->init ( 'chapter', 'pcid', 'pooling' );
				$this->db->setCriteria ( new Criteria ( 'paid', $article ['paid'] ) );
				$this->db->criteria->add ( new Criteria ( 'channelid', $channleid ) );
				// $this->db->criteria->add ( new Criteria ( 'chaptertype', 0 ) );//章节
				$this->db->criteria->setSort ( 'chapterorder' );
				$this->db->criteria->setOrder ( 'ASC' );
				$this->db->queryObjects ();
				$chapters = array ();
				$av = $i = $k = $pc = 0;
				while ( $chapter = $this->db->getObject ()) {
					if ($chapter->getVar ( 'chaptertype' ) == 1) {
						// 总卷数
						$av ++;
					}
// 					$txtpath = $articleLib->getDir ( 'txtdir', true, false ) . '/' . $chapter->getVar ( 'chapterid' ) . '.txt';
					$chapters [$i] = array (
							'isvip' => $chapter->getVar ( 'isvip' ),
							'cpchapterid' => $chapter->getVar ( 'pcid' ),
							'lastupdate' => $chapter->getVar ( 'adddate' ),
							'chaptertype' => $chapter->getVar ( 'chaptertype' ),
							'chaptername' => $chapter->getVar ( 'chaptername', 'n' ),
							'content' => $chapter->getVar ( 'content', 'n' )
// 							'content' => @jieqi_readfile ( $txtpath )
					);
					$i ++;
				}
				$totalchapter = count ( $chapters ) - $av; // 总章节数
				                                           // 定位上次推送的章节位置
				if (! $article ['lastchapterid'])
					$startupdate = true;
				else
					$startupdate = false; // 初始化更新标记
						                      // 推送下一章节值mcp平台
				$lastvolumeid = 0;
				$lastvolume = '';
				foreach ( $chapters as $c ) {
					if ($c ['chaptertype'] == 1) { // 记录推送章节所在的卷
						$lastvolumeid = $c ['cpchapterid'];
						$lastvolume = $c ['chaptername'];
						continue;
					}
					$fullflag = 0;
					$k ++;
					if (! $startupdate) {
						// 定位推送位置
						if ($article ['lastchapterid'] != $c ['cpchapterid']) {
							continue;
						} else {
							$startupdate = true;
							if (($totalchapter - $k) > 0) {
								$this->out_msg ( '---->《' . $article ['articlename'] . '》上次更新到[' . $name . '],还有<b>' . ($totalchapter - $k) . '</b>篇需要推送' );
							} else {
								$this->out_msg ( '---->无章节需要推送!' );
							}
						}
					} else {
						$chapterIdx = $chapterIdx + 1;
						// 组织推送数据
						$securityid = strtoupper ( sha1 ( MyMobile::MCPID . $bookId . $c ['cpchapterid'] . 'true' . MyMobile::PASSWORD ) );
						$isFree = $c ['isvip'] == 0 ? 1 : 0; // mcp平台 1免费
						$updateTime = date ( "Y-m-d H:i:s", $c ['lastupdate'] );
						// 卷以移动返回信息比对
						if ($lastvolume && $volumeName && $lastvolume != $volumeName) {
							$volumeIdx = $volumeIdx + 1;
							$volumeName = $lastvolume;
						}
						$datas = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
						<AddNewChapter>\r\n
							<mcpId>" . MyMobile::MCPID . "</mcpId>\r\n
											<bookId>{$bookId}</bookId>\r\n
											<submit>true</submit>\r\n
											<securityId>{$securityid}</securityId>\r\n
											<ChapterInfo>\r\n
											<volumeIdx>{$volumeIdx}</volumeIdx>\r\n
											<volumeName>{$volumeName}</volumeName>\r\n
											<chapterIdx>{$chapterIdx}</chapterIdx>\r\n
											<chapterId>{$c['cpchapterid']}</chapterId>\r\n
													<chapterName>" . $this->safeStrXml ( $c ['chaptername'] ) . "</chapterName>\r\n
													<isFree>{$isFree}</isFree>\r\n
													<updateTime>{$updateTime}</updateTime>\r\n
													<content><![CDATA[{$c['content']}]]></content>\r\n
													</ChapterInfo>\r\n
													</AddNewChapter>";
						$datas = jieqi_gb2utf8 ( $datas );
// 						$length = strlen ( $datas ); // 参数长度
// 						$header = "POST /CP/addNewChapter HTTP/1.1\r\n";
// 						$header .= "Host:" . MyMobile::HOST . ":80\r\n";
// 						$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
// 						$header .= "Content-Length: {$length}\r\n";
// 						$header .= "Connection: Close\r\n\r\n";
// 						$header .= $datas . "\r\n";
// 						unset ( $fp );
// 						$fp = fsockopen ( MyMobile::HOST, 80, $errno, $errstr, 30 );
// 						fwrite ( $fp, $header );
// 						$backdata = '';
// 						while ( ! feof ( $fp ) ) {
// 							$backdata .= fgets ( $fp, 1024 );
// 						}
// 						fclose ( $fp );


						$backdata = $this->fsockPost("/CP/addNewChapter", MyMobile::HOST,$datas);
						
						





						preg_match ( '/appendinfo:(.*)Content-Type/is', $backdata, $match );
						$appendinfo = $match [1];
						
						// echo jieqi_utf82gb(urldecode ( $appendinfo ));
						preg_match ( '/Connection: close(.*)/is', $backdata, $match );
						$lastline = $match [1];
						preg_match ( '/\s*(\d*)\s*(\d*)\s*(\d*)\s*/is', $lastline, $match );
						$backinfono = $match [2];
						if(is_numeric($backinfono)){
							if ($backinfono == 0 || $backinfono == 100) {
								$pc ++; // 推送的章节数
								$this->out_msg ( '---->' . $c ['chaptername'] . '...推送成功' );
								// 同步数据池的最后更新的
								$article ['lastvolumeid'] = $lastvolumeid;
								$article ['lastvolume'] = $lastvolume;
								$article ['lastchapterid'] = $c ['cpchapterid'];
								$article ['lastchapter'] = $c ['chaptername'];
								$article ['outchapters'] = $k;
								$article ['fullflag'] = $article ['full'];
								$article ['lastdate'] = JIEQI_NOW_TIME; // 推送时间
								$article ['setting'] = $this->arrayeval ( $setting );
								unset ( $article ['author'] );
								unset ( $article ['sortid'] );
								unset ( $article ['keywords'] );
								unset ( $article ['siteid'] );
								unset ( $article ['size'] );
								unset ( $article ['articletype'] );
								unset ( $article ['full'] );
								unset ( $article ['imgflag'] );
								$this->db->init ( 'article', 'paid', 'pooling' );
								if (! $this->db->edit ( $article ['paid'], $article )) {
									$this->out_msg_err ( '---->数据池文章《' . $article ['articlename'] . '》，状态同步失败！' );
								}
								if ($setting ['daychapter'] && is_numeric ( $setting ['daychapter'] ) && $setting ['daychapter'] > 0 && $setting ['daychapter'] == $pc) {
									$this->out_msg ( '---->每天只推送' . $pc . '章' );
									break;
								}
							} else {
								// 同步处理成功返回即返回值为0 or 100，再发送下一章，否则暂停本书续传后续章节，进行问题排除后再进行同步后一章，防止章节错乱（缺章、重复等）
								$this->out_msg_err ( '---->' . $c ['chaptername'] . '->推送失败。' . $this->msg [$backinfono] );
								$this->out_msg_err ( '---->原因：' . $this->msg [$backinfono] );
								break;
								// if($again == 0){
								// $a--;//again
								// $this->out_msg ( '再次请求一次...');
								// $again == 1;
								// }else{
								// $again == 0;
								// break;//下一本书
								// }
							}
						}else{
							$this->out_msg_err ( '---->原因：'.jieqi_utf82gb(urldecode($appendinfo)) );
							break;
						}
					}
				}
				if (! $startupdate) {
					$this->out_msg_err ( '---->未匹配最后推送的章节，手动维护最后推送的章节ID' );
				}
				$this->out_msg ( '---->开始同步图书信息:总字数、收藏数、点击数、推荐数' );
				$this->commendBookInfoUpdate ( $bookId, $bookShuhaiId );
			}
		}
	}
	
	function handlePoolChapter(&$poolChapter){
		if($poolChapter && $poolChapter['chaptername'] && $poolChapter['content']){
			//移动api去除章节内的章节名称
			$tmp = '    '.$poolChapter['chaptername'];
			if(substr($poolChapter['content'],0,strlen($tmp)) ==  $tmp){//第一行是章节名称
				$poolChapter['content'] = substr($poolChapter['content'],strlen($tmp.PHP_EOL));//处理换行
			}
		}
	}
}
?>