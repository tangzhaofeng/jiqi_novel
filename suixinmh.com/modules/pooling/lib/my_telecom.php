<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iapi.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iSynchronization.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseApi.php');
include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * 中国电信天翼阅读，推送接口类，
 * <p>
 * 继承baseApi抽象类
 * <p>
 * 实现iApi接口
 * <p>
 * 本接口只提供对天翼平台的章节更新功能，因为上书是在天翼cpm平台操作。
 * 后续章节更新通过天翼cmp平台提供的api接口推送。
 * @author chengyuan
 *
 */
class MyTelecom extends baseApi implements iApi,iSynchronization {
	/**
	 * 环境
	 * <p>
	 * test(测试)|produce(生产)
	 * @var unknown
	 */
	const ENVIRONMENT = 'test';
	/**
	 * 查询记录URL
	 * @var unknown
	 */
	const QUERY_URL = '/ReadPlatformClient_Content/servlet/getContentIdByContentNameAuthor';
	/**
	 * 更新URL
	 * @var unknown
	 */
	const UPDATE_URL = '/ReadPlatformClient_Content/servlet/updateContentInterface';
	
	/**
	 * 1000002629854
	 * @var unknown
	 */
	const CPID = '1000002629854';
	const PASSWORD = 'ytxt@123';
	/**
	 * 错误码枚举数组
	 * @var unknown
	 */
	var $msg =  array(
			'001'=>'token错误，无权限',
			'002'=>'zip包格式错误',
			'003'=>'章节格式错误',
			'004'=>'完本不能上传',
			'005'=>'字节数 >20000 或 < 1000',
			'006'=>'连载章节未审核（按本连载）',
			'007'=>'连载model参数错误 0：1',
			'008'=>'finishFlag 参数错误',
			'009'=>'cp未授权',
			'010'=>'书籍未授权',
			'011'=>'书籍id和xml中的书籍id不对应',
			'012'=>'书籍章节名称已经存在',
			'013'=>'上传书籍章节不连续',
			'014'=>'上传卷不连续'
	);

	function __construct() {
		parent::initDB();
		$this->setKey();
	}
	/**
	 * 根据运行环境返回
	 * @return string
	 */
	private function getHost(){
		$host = '115.239.135.2';
		if(MyTelecom::ENVIRONMENT == 'produce'){
			$host = '61.130.247.180';
		}
		return $host;
	}
	/**
	 * 根据运行环境返回
	 * @return string
	 */
	private function getPort(){
		$port = 8104;
		if(MyTelecom::ENVIRONMENT == 'produce'){
			$port = 6001;
		}
		return $port;
	}
	/**
	 * @param $format 缺省：Y-m-d
	 * @return date($format)
	 */
	private function getTimestamp($format="Y-m-d"){
		return date($format);
	}
	/**
	 * 设置密匙
	 * @see baseApi::setKey()
	 */
	function setKey(){
	}
	function get_lastupdate($params){
		$query = http_build_query($params);
		$res = $this->fsockPost(MyTelecom::QUERY_URL,$this->getHost(),$query,$this->getPort());	
		if($res){
			$json = $this->parseJson($res);
			if(is_object($json)){
				return $json;
			}else{
				return false;
			}
		}else{
			$this->out_msg_err('response is null');
			return false;
		}

	}
	/**
	 * 提取socket响应的json格式内容，并且解析为json对象
	 * @return json object
	 */
	private function parseJson($res){
		$start = strpos($res, '{', 1);
		$len = strrpos($res, '}', 1) - $start+1;
		$jsontxt = substr($res, $start, $len);
		$jsontxt = str_replace(array('	','true','false'),array('','"true"','"false"'), $jsontxt);//ASCII=09的水平制表符在json_decode引起异常，转换失败
		$jsontxt = str_replace('{', '{"', $jsontxt);
		$jsontxt = str_replace(',', ',"', $jsontxt);
		$jsontxt = str_replace(':"', '":"', $jsontxt);//避免，章节名称中有:符号，报错。
		//字符串类型true,false转换boolean类型 true,false
		$jsontxt = str_replace(array('"true"','"false"'),array('true','false'), $jsontxt);
		$json = json_decode($jsontxt);
		return $json;
	}
	function addChapter($data){
		$res = $this->fsockPost(MyTelecom::UPDATE_URL,$this->getHost(),$data,$this->getPort());
		$json = $this->parseJson($res);
		if(is_object($json)){
			return $json;
		}else{
			return false;
		}
// 		include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/SingleFileSender.php');
// 		$sfs=new SingleFileSender("http://".$this->getHost().MyTelecom::UPDATE_URL);
// 		//set sfs prot(default prot 80)
// 		$sfs->setPort($this->getPort());
// 		if($ret = $sfs->post($data,true)){
			
// 			echo $ret;
			
// 			$json = $this->parseJson($ret);
// 			if(is_object($json)){
// 				return $json;
// 			}else{
// 				return false;
// 			}
// 		}
	}
	function addBook($cpBid,$data){
		//不实现此方法
	}
	function request($url, $mode, $params=array(), $header = 'Content-Type: text/plain; charset=utf-8;'){
	}
	/**
	 * 制作推送的章节压缩包（$filename）
	 * @param unknown $contentId
	 * @param unknown $chapters
	 * @param unknown $filename
	 * @return boolean
	 */
	private function makeZip($contentId,$chapters=array(),$filename){
		include_once (JIEQI_ROOT_PATH . '/lib/compress/zip.php');
		include_once $GLOBALS ['jieqiModules'] [JIEQI_MODULE_NAME] ['path'] . '/admin/controller/channelController.php';
		$controller = new channelController ();
		$controller->theme_dir = false;
		
		$old_display = Application::$_DISPLAY;
		$old_runpath = Application::$_HLM_RUN_PATH;
		$old_viewpath = Application::$_HLM_VIEW_PATH;
		Application::$_DISPLAY = false;
		Application::$_HLM_RUN_PATH = $GLOBALS ['jieqiModules'] [JIEQI_MODULE_NAME] ['path'];
		Application::$_HLM_VIEW_PATH = Application::$_HLM_RUN_PATH . '/templates/telecom';
		
		
		$zip = new JieqiZip ();
		if (! $zip->zipstart ( $filename )){
			return false;
		}
		
		$book_content = $controller->display ( array (
				'contentId' => $contentId,
				'chapters' => $chapters
		), 'book' );echo $book_content;exit('stop');
		$zip->zipadd ( 'book.xml', '<?xml version="1.0" encoding="UTF-8"?>' . jieqi_gb2utf8 ( $book_content ) );// book模板使用html格式解析的，所以这里要加上头xml标识，内容转码
		foreach ( $chapters as $c ) {// chapters/chapter.html文件处理
			if($c['chaptertype'] == 0){//章节
				$chaptername = 'chapters/' . $c ['cpchapterid'] . '.html'; // 相对位置，文件名称会自动处理中文编码
				$c['content'] = jieqi_htmlstr($c['content']);
				$chapter_content = $controller->display ( array ('chapter' => $c ), 'chapter' );
				$zip->zipadd ( $chaptername, str_replace('#&nbsp;#','　',jieqi_gb2utf8($chapter_content)));
			}
			
		}
		$zip->setComment ( "Content Provider by Shuhai" );
		if ($zip->zipend ()) {
			@chmod ( $filename, 0777 );
		}
		if(!is_file($filename)){
			return false;
		}
		Application::$_DISPLAY = $old_display;
		Application::$_HLM_RUN_PATH = $old_runpath;
		Application::$_HLM_VIEW_PATH = $old_viewpath;
		return true;
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
		$articleLib = $this->load ( 'article', 'article' );
		$articleLib->instantPackage ( $article ['articleid'] );
		$channleLib = $this->load ( 'channel', 'pooling' ); // 加载channel自定义类
		$channle = $channleLib->get ( $channleid );
		$cpname = "CP" . MyTelecom::CPID;
		$token = strtolower ( md5 ( jieqi_gb2utf8 ( $cpname . MyTelecom::PASSWORD . $this->getTimestamp () . $article ['articlename'] . $article ['author'] ) ) );
		$params = array (
				'cpName' => $cpname,
				'timestamp' => $this->getTimestamp (),
				'token' => $token,
				'bookName' => jieqi_gb2utf8 ( $article ['articlename'] ),
				'bookAuthor' => jieqi_gb2utf8 ( $article ['author'] ) 
		);
		$date = $this->get_lastupdate ( $params );
		if (!$date || !$date->success) {
			$this->out_msg_err ( '---->MCP平台无有效更新记录。' );
		} else {
			$this->out_msg ( "---->《" . $article ['articlename'] . "》上次推送记录" );
			$this->out_msg ( "<table border=1><tr><th>内容编号</th><th>最后章节名称</th><th>最后章节编号（卷+章节）</th><th>最后卷编号</th></tr>", false );
			$this->out_msg ( "<tr><td>{$date->contentId}</td><td>" . jieqi_utf82gb ( $date->latestChapter ) . "</td><td>{$date->lastId}</td><td>{$date->lastColumnId}</td></tr></table>", false );
			if (! $article ['lastchapterid'] || ! is_numeric ( $article ['lastchapterid'] )) {
				$this->out_msg_err ( '---->请维护《' . $article ['articlename'] . '》的上次推送的章节ID' );
			} else {
				// all chapter
				// shuhai->0 or pooling->1
				if (intval ( $channle ['setting'] ['getdata'] ['chaptersource'] ) === 1) {
					$chapters = $this->getChaptersByPooling($article['paid'], 2 );
				} else {
					$chapters = $this->getChapters ( $article ['articleid'], 2 );
				}
				if(empty($chapters)){
					$this->out_msg_err ( '---->没有推送章节' );
					return;
				}
				$startupdate = false; // 更新标记
				$pass = true; // 验证通过标识
// 				$lastvolumeid = 0;
// 				$lastvolume = '';
				$k = $pc = $cc = 0;
				$split = false;
				$first_chapter_type = 0;//推送的第一个章节类型
				$first_volume_index = -1;//第一个卷位置
				$pushChapters = array (); // 提取需要推送的章节
				foreach ( $chapters as $c ) {
// 					if ($c ['chaptertype'] == 1) { // 记录推送章节所在的卷
// 						$lastvolumeid = $c ['cpchapterid'];
// 						$lastvolume = $c ['chaptername'];
// 						continue;
// 					}
					$k ++;
					if (! $startupdate) {
						// 定位推送位置
						if ($article ['lastchapterid'] != $c ['cpchapterid']) {
							continue;
						} else {
							$startupdate = true;
						}
					} else {
						//卷+章节数
						$pc ++;
						if($pc == 1){
							$first_chapter_type = $c ['chaptertype'];//记录第一个章节类型
						}
						if($c['chaptertype'] == 1 && $first_volume_index == -1){//第一个卷位置,从1开始，索引-1
							$first_volume_index = $pc;
						}
						if($c['chaptertype'] == 0){//章节数
							$cc++;
						}
						//第一个是章节，后面有新卷
						if($first_chapter_type == 0 && $c ['chaptertype'] == 1){
							$split = true;//此标识计算，需不需要拆分推送
						}
						if ($pc === 1) {
							$this->out_msg ( '---->开始：提取需要推送的章节' );
							$this->out_msg ( "<table border=1><tr><th>序</th><th>章节ID</th><th>章节名称</th><th>章节类型</th><th>章节编号</th><th>字节（1000<一个章节内容的字节数<20000）</th><th>验证</th></tr>", false );
						}
						$bytesize = $c ['size'];
						$chk = '通过';
						if ((1000 >= $bytesize || $bytesize >= 20000) && $c ['chaptertype'] == 0) {
							$chk = '<font color="red">失败</font>';
							$pass = false;
						}
						$c ['order'] = $date->lastId + $pc; // 新编号
						$chaptertype = $c ['chaptertype'] == 1 ? '<font color="green">卷</font>' : '章节';
						$this->out_msg ( "<tr><td>{$pc}</td><td>{$c ['cpchapterid']}</td><td>{$c ['chaptername']}</td><td>{$chaptertype}</td><td>{$c['order']}</td><td>{$bytesize}</td><td>{$chk}</td></tr>", false );
						if ($article ['setting'] ['daychapter'] && is_numeric ( $article ['setting'] ['daychapter'] ) && $article ['setting'] ['daychapter'] > 0) {
							$pushChapters [] = $c;
							if ($article ['setting'] ['daychapter'] == $cc) {
								break;
							}
						} else {
							$pushChapters [] = $c;
						}
					}
				}
				$this->out_msg ( "</table>", false );
				$this->out_msg ( '---->结束：提取需要推送的章节' );
				if ($pass) {
					if ($startupdate) {
						if (! empty ( $pushChapters )) {
							//拆分$pushChapters
							if($split){
								$this->out_msg ( '---->包含新卷，需要拆分两部分推送' );
								$first = array_slice($pushChapters,0,$first_volume_index-1);
								$second = array_slice($pushChapters,$first_volume_index-1);
								if(!empty($first) && !empty($second)){
									$this->out_msg ( '---->拆分成功' );
									$this->out_msg ( '---->开始推送第一部分' );
									$this->pushChapters($article,$date,array_slice($pushChapters,0,$first_volume_index-1));
									$this->out_msg ( '---->结束推送第一部分' );
									$this->out_msg ( '---->----------华丽丽的分割线------------' );
									$this->out_msg ( '---->开始推送第二部分' );
									$this->pushChapters($article,$date,array_slice($pushChapters,$first_volume_index-1));
									$this->out_msg ( '---->结束推送第二部分' );
								}else{
									$this->out_msg_err ( '---->拆分失败' );
								}
							}else{
								$this->pushChapters($article,$date,$pushChapters);
							}
						} else {
							$this->out_msg ( '---->无章节需要推送' );
						}
					} else {
						$this->out_msg_err ( '---->未匹配最后推送的章节，手动维护最后推送的章节ID' );
					}
				} else {
					$this->out_msg_err ( '---->请修改不合格的章节' );
				}
			}
		}
	}
	
	private function pushChapters($article,$date,$pushChapters){
		$this->out_msg ( '---->开始：制作推送章节zip包' );
		$fileName = jieqi_uploadpath ( 'zip', JIEQI_MODULE_NAME ) . '/telecom.zip';
		if (! $this->makeZip ( $date->contentId, $pushChapters, $fileName )) {
			$this->out_msg_err ( '---->zip包异常' );
			return;
		}
		$this->out_msg ( '---->结束：章节zip包制作成功' );
		$zipFile = jieqi_readfile ( $fileName );
		$this->out_msg ( '---->开始：推送章节压缩包' );
		$token = strtolower ( md5 ( MyTelecom::CPID . MyTelecom::PASSWORD . strval ( $this->getTimestamp () ) ) );
		$data = pack ( 'a17a19a32a20aa', MyTelecom::CPID, $this->getTimestamp ( "Y-m-d H:i:s" ), $token, $date->contentId, '0', '1' );
		$data .= $zipFile;
		$date = $this->addChapter ( $data );
		if ($date && $date->success) {
			$this->out_msg ( '---->结束：章节压缩包推送成功' );
			$this->out_msg ( '---->开始：更新推送位置' );
			$lastChapter = end ( $pushChapters );
			$this->db->init ( 'article', 'paid', 'pooling' );
			if (! $this->db->edit ( $article ['paid'], array (
					'lastchapterid' => $lastChapter ['cpchapterid'],
					'lastchapter' => $lastChapter ['chaptername'],
					'outchapters' => $k,
					'fullflag' => $article ['full'],
					'lastdate' => JIEQI_NOW_TIME
			) )) {
				$this->out_msg_err ( '---->结束：记录推送位置失败' );
			}else{
				$this->out_msg ( '---->结束：记录推送位置成功' );
			}
		} else {
			$this->out_msg_err ( '---->章节压缩包推送失败，原因：' . $this->msg [$date->errorCode] );
		}
	}
	
	function handlePoolChapter(&$poolChapter){
		if($poolChapter && $poolChapter['chaptername'] && $poolChapter['content']){
			$poolChapter['chaptername'] = str_replace('第两百','第二百',$poolChapter['chaptername']);//处理换行
		}
	}
}
?>