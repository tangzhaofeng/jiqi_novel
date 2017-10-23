<?php
/**
 * api接口抽象基类
 * @author chengyuan  2014-6-27
 *
 */
include_once ($GLOBALS['jieqiModules']['pooling']['path'] . '/class/base.php');
include_once(JIEQI_ROOT_PATH.'/lib/net/socket.php');
abstract class  baseApi extends base
{
	/**
	 * api推送的钥匙
	 * @var unknown
	 */
	var $KEY;
	/**
	 * 设置api的密匙
	 * 2014-7-2 上午9:30:22
	 */
	abstract function setKey();
	
	function Array2Json($array) {
		arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		$json = urldecode($json);
		// ext需要不带引号的bool类型
		$json = str_replace("\"false\"","false",$json);
		$json = str_replace("\"true\"","true",$json);
		return $json;
	}
	function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
	{
		static $recursive_counter = 0;
		if (++$recursive_counter > 1000) {
			die('possible deep recursion attack');
		}
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				arrayRecursive($array[$key], $function, $apply_to_keys_also);
			} else {
				//$array[$key] = $function(iconv('gbk','utf-8',$value));
				//$array[$key] = iconv('gbk','utf-8',$value);
			}

			if ($apply_to_keys_also && is_string($key)) {
				$new_key = $function($key);
				if ($new_key != $key) {
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
		$recursive_counter--;
	}
	/**
	 * 安全的xml
	 * @param unknown $str
	 * @return mixed
	 * 2014-7-8 上午11:19:30
	 */
	function safeStrXml($str){
		$str = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/","",$str);
		$arr_search = array('<','>','&','\'','"','');
		$arr_replace = array('&lt;','&gt;','&amp;','&apos;','&quot;','');
		return str_ireplace($arr_search,$arr_replace,$str);
	}
	/**
	 * 联合查询获取渠道内数据池的文章信息
	 * @param unknown $channleid	渠道Id
	 * @param unknown $ids			数据池paid数组，如果空读取当前渠道数据池内所有的文章
	 * 2014-7-4 上午9:38:57
	 */
	function poolArticle($channleid,$ids = array()){
		$this->db->init ( 'article', 'paid', 'pooling');
		$this->db->setCriteria ( new Criteria ( 'm.channelid', $channleid ) );
		$this->db->criteria->setTables ( jieqi_dbprefix ( 'pooling_article' ) . "  AS m LEFT JOIN " . jieqi_dbprefix ( 'article_article' ) . " AS a ON m.articleid=a.articleid" );
		$this->db->criteria->setFields ( 'm.*,a.author,a.sortid,a.keywords,a.siteid,a.size,a.articletype,a.imgflag,a.fullflag as full' );
		if ($ids && is_array($ids)){
			$this->db->criteria->add ( new Criteria ( 'm.paid', '(' . implode ( ',', $ids ) . ')', 'in' ) );
		}
		$this->db->criteria->add ( new Criteria ( 'm.pushflag', 1 ) );
		$this->db->criteria->setSort ( 'm.lastdate' );
		$this->db->criteria->setOrder ( 'DESC' );
		return $articles = $this->db->lists ();
	}
	function fsockPost($url,$host,$string='',$port=80){
		$header = "POST ".$url." HTTP/1.1\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-length: ".strlen($string)."\r\n";
		$header .= "Accept: */*\r\n";
		$header .= "\r\n";
		$header .= $string."\r\n";
		$header .= "\r\n";
		$header .= "Connection: close\r\n\r\n";		// POST表单数据
		$socket = new JieqiSocket();
		$socket->connect($host,$port,true,30);
// 		$socket->setBlocking(false);
		$socket->write($header);
		$res = $socket->readAll();
		$socket->disconnect();
		return $res;
	}
	/**
	 * sockPost请求，返回请求结果，固定端口：80，超时时间是30s
	 * @param unknown $url		请求url
	 * @param unknown $host		地址
	 * @param unknown $string	post的字符串
	 * @return string
	 * 2014-7-11 下午3:58:34
	 */
	function fsockPost123($url,$host,$string=''){
		$header = "POST ".$url." HTTP/1.1\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-length: ".strlen($string)."\r\n";
		$header .= "Accept: */*\r\n";
		$header .= "\r\n";
		$header .= $string."\r\n";
		$header .= "\r\n";
		$header .= "Connection: close\r\n\r\n";		// POST表单数据
		$fp = @fsockopen($host, 80, $errno, $errstr, 30);
		if($fp){
			if(fwrite($fp, $header)){//返回写入的字符长
				$responseData = '';
				while (!feof($fp)) {
					$responseData .= fgets($fp, 4096);
				}
				fclose($fp);
				if(!$responseData){
					$this->out_msg_err ('response is null') ;
					exit;
				}
				return $responseData;
			}else{
				fclose($fp);
				$this->out_msg_err ('fwrite fail') ;
				exit;
			}
			fclose($fp);
		}else{
			$this->out_msg_err ('socket open fail') ;
			exit;
		}
	}
	/**
	 * 获取数据池内同步的需要推送的章节
	 * @param unknown $paid			数据池文章id
	 * @param number $chaptertype	章节类型 		0章节，1卷，2全部，默认：0
	 * @param number $isvip			免费|vip 		0免费，1vip，2全部，默认：2
	 * @return multitype:multitype:NULL	章节数组
	 */
	protected function getChaptersByPooling($paid,$chaptertype=0,$isvip=2){
		if($paid){
			$this->db->init ( 'chapter', 'pcid', 'pooling' );
			$this->db->setCriteria ();
			$this->db->criteria->add ( new Criteria ( 'paid', $paid ) );
// 			$this->db->criteria->add ( new Criteria ( 'channelid', $channelid) );
// 			$this->db->criteria->add ( new Criteria ( 'articleid', $articleid) );
			if($chaptertype != 2)
				$this->db->criteria->add ( new Criteria ( 'chaptertype', $chaptertype) );
			if($isvip != 2)
				$this->db->criteria->add ( new Criteria ( 'isvip', $chaptertype) );
			$this->db->criteria->setSort ( 'chapterorder' );
			$this->db->criteria->setOrder ( 'ASC' );
			$this->db->queryObjects ();
			$chapters = array();
			while ( $chapter = $this->db->getObject () ) {
				$chapters [] = array (
						'isvip' => $chapter->getVar ( 'isvip' ),
						'cpchapterid' => $chapter->getVar ( 'chapterid' ),
						'chaptertype' => $chapter->getVar ( 'chaptertype' ),
						'chaptername' => $chapter->getVar ( 'chaptername', 'n' ),
						'size' => $chapter->getVar ( 'size', 'n' ),
						'content' =>$chapter->getVar ( 'content', 'n' ),
				);
			}
			return $chapters;
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		
	}
	/**
	 * 获取推送文章下审核通过的章节，默认只查询章节
	 * @param unknown $articleid	推送的aid
	 * @param number $chaptertype	章节类型 		0章节，1卷，2全部，默认：0
	 * @param number $isvip			免费|vip 		0免费，1vip，2全部，默认：2
	 * @return multitype:multitype:NULL	章节数组
	 * 2014-7-15 下午1:33:10
	 */
	protected  function getChapters($articleid,$chaptertype=0,$isvip=2){
		if($articleid){
			$articleLib = $this->load ( 'article', 'article' );
			$articleLib->instantPackage ( $articleid );
			$this->db->init ( 'chapter', 'chapterid', 'article' );
			$this->db->setCriteria ( new Criteria ( 'articleid', $articleid ) );
			$this->db->criteria->add ( new Criteria ( 'display', 0, '=' ) );
			if($chaptertype != 2)
				$this->db->criteria->add ( new Criteria ( 'chaptertype', $chaptertype) );
			if($isvip != 2)
				$this->db->criteria->add ( new Criteria ( 'isvip', $chaptertype) );
			$this->db->criteria->setSort ( 'chapterorder' );
			$this->db->criteria->setOrder ( 'ASC' );
			$this->db->queryObjects ();
			$chapters = array();
			while ( $chapter = $this->db->getObject () ) {
				$txtpath = $articleLib->getDir ( 'txtdir', true, false ) . '/' . $chapter->getVar ( 'chapterid' ) . '.txt';
				$chapters [] = array (
						'isvip' => $chapter->getVar ( 'isvip' ),
						'cpchapterid' => $chapter->getVar ( 'chapterid' ),
						'chaptertype' => $chapter->getVar ( 'chaptertype' ),
						'chaptername' => $chapter->getVar ( 'chaptername', 'n' ),
						'size' => $chapter->getVar ( 'size', 'n' ),
						'content' =>@jieqi_readfile ( $txtpath )
				);
			}
			return $chapters;
		}else{
			$this->printfail(LANG_ERROR_PARAMETER);
		}
	}
}
?>