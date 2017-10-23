<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iapi.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/baseApi.php');
include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * 苏宁推送接口类，继承baseApi抽象类，实现iApi接口
 * @author chengyuan  2014-7-3
 *
 */
class MySuning extends baseApi implements iApi {
	/**
	 * 测试环境
	 * @var unknown
	 */
	const HOST = '58.240.86.161';//test
	/**
	 * 生产环境
	 */
// 	const HOST = 'openesb.suning.com';//produce

	/**
	 *品牌编码
	 * @var unknown
	 */
	const SUPPLYID = "Z688";//测试
// 	const SUPPLYID = "Y549";//生产

	var $errmsg = array (
			0 => "成功",
			1 => "书籍章节信息或图片已存在",
			2 => "书籍创建失败",
			3 => "书籍信息未同步",
			4 => "章节未同步",
			5 => "有断章",
			6 => "部分章节未同步",
			7 => "缺少封面",
			8 => "商品编码申请失败，请联系苏宁运维处理",
			9 => "图片推送失败",
			10 => "书籍已同步至第x章，请提交至第x章",
			11 => "此书籍新创建未同步任何章节",
			12 => "未注册晚上的供应商，请先进行注册，完善信息",
			13 => "鉴权信息数字签名认证失败",
			14 => "供应商尚未审核，请联系苏宁运维进行审核",
			15 => "内容重要信息数字签名认证失败",
			16 => "内容重要信息数字签名认证失败",
			17 => "书籍被驳回，请联系苏宁运维",
			18 => "提交的章节数无效",
			19 => "批量同步的内容不得超过限制的上限"
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
		$this->KEY = 'GTV8Sj40327i5WT0CP8e';//测试
// 		$this->KEY = '57B316WuiI27q68QkSb9';//生产
	}
	/**
	 * 获取上次推送记录
	 * @param unknown $cpbid
	 * @return multitype:unknown |boolean
	 * 2014-9-9 上午11:16:12
	 */
	function get_lastupdate($cpbid){
		$digitalSignature = sha1($cpbid.MySuning::SUPPLYID.'0'.$this->KEY);
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
				<Resource>\r\n
					<Book>\r\n
						<bookId>{$cpbid}</bookId>\r\n
						<supplyId>".MySuning::SUPPLYID."</supplyId>\r\n
						<chapterNums>0</chapterNums>\r\n
						<digitalSignature>{$digitalSignature}</digitalSignature>\r\n
					</Book>\r\n
				</Resource>";
		//这里的返回结果又两种情况
		$responseData =  $this->fsockPost('/esbadapter/EbookArticleContentMgmt_ALL/submitEbookChapter',MySuning::HOST,$xml);
		$responseData = jieqi_utf82gb($responseData);
		if($responseData){
			preg_match ( "/(.+)\<respCode\>(.+)\<\/respCode\>/isU", $responseData, $matches );
			if(count($matches) == 0){
				//上次推送的记录
				preg_match ( "/(.+)\<chapterNo\>(.+)\<\/chapterNo\>/isU", $responseData, $matches );
				$chapterNo = $matches[2];
				preg_match ( "/(.+)\<chapterName\>(.+)\<\/chapterName\>/isU", $responseData, $matches );
				$chapterName = $matches[2];
				preg_match ( "/(.+)\<upDate\>(.+)\<\/upDate\>/isU", $responseData, $matches );
				$upDate = $matches[2];
				return array('chapterNo'=>$chapterNo,'chapterName'=>$chapterName,'upDate'=>$upDate);
			}elseif(count($matches) == 3){
				//此书籍新创建未同步任何章节
				$respCode = intval($matches[2]);
				preg_match ( "/(.+)\<respDes\>(.+)\<\/respDes\>/isU", $responseData, $matches );
				$respDes = $matches[2];
				return array('code'=>$respCode);
			}else{
				$this->out_msg_err('---->推送记录出错，错误编号：'.$respCode.'错误信息：'.$respDes);
				return false;
			}
		}else{
			$this->out_msg_err ( '---->last update socket response data null');
			exit;
		}

	}
	/**
	 *	推送章节
	 * @param unknown $data	[articleid,chapterNo,chaptername,content]
	 * @return unknown
	 * 2014-9-10 下午4:56:04
	 */
	function addChapter($data){
		extract($data);
		$chaptername = $this->safeStrXml ( $chaptername);
		$digitalSignature = sha1(jieqi_gb2utf8($articleid.$chaptername.MySuning::SUPPLYID.$chapterNo.$this->KEY));
// 		echo jieqi_gb2utf8($articleid.$chaptername.MySuning::SUPPLYID.$chapterNo.$this->KEY).'<br>'.$digitalSignature;
// 		exit;
		//处理content首行缩进2个全角空格
		//1去掉段落与段落之间的换行,4个半角空格替换为2个全角空格以控制段落缩进
		//2第一行添加章节名称不缩进
		$content = str_replace(array(PHP_EOL.PHP_EOL,'    '), array(PHP_EOL,'　　'), $content);
		$addStr = '　　'.$chaptername;
		if(substr($content,0,strlen($addStr)) ==  $addStr){//第一行有章节名称，处理：第一行不缩进
			$content = substr($content,4);//一个全角2个长度
		}else{//第一行没有章节名称，添加章节名称（不缩进），换行
			$content = $chaptername.PHP_EOL.$content;
		}
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
		<Resource>\r\n
			<Book>\r\n
				<bookId>{$articleid}</bookId>\r\n
				<supplyId>".MySuning::SUPPLYID."</supplyId>\r\n
				<chapterNo>{$chapterNo}</chapterNo>\r\n
				<chapterName><![CDATA[{$chaptername}]]></chapterName>\r\n
				<content><![CDATA[{$content}]]></content>\r\n
				<digitalSignature>{$digitalSignature}</digitalSignature>\r\n
			</Book>\r\n
		</Resource>";
		$responseData =  $this->fsockPost('/esbadapter/EbookArticleContentMgmt_ALL/synEbookChapterContent',MySuning::HOST,jieqi_gb2utf8 ($this->saleXml($xml)));
		$responseData = jieqi_utf82gb($responseData);
		if($responseData){
			preg_match ( "/(.+)\<respCode\>(.+)\<\/respCode\>/isU", $responseData, $matches );
			$respCode = intval($matches[2]);
			preg_match ( "/(.+)\<respDes\>(.+)\<\/respDes\>/isU", $responseData, $matches );
			$respDes = $matches[2];
			return array('code'=>$respCode,'msg'=>$respDes);
		}else{
			$this->out_msg_err ( '---->add chapter socket response data null');
			exit;
		}
	}
	/**
	 * 提交推送的章节
	 * @param unknown $data
	 * 2014-9-10 下午3:32:28
	 */
	function submit($data){
		extract($data);
		$digitalSignature = sha1(jieqi_gb2utf8($articleid.MySuning::SUPPLYID.$chapterNums.$this->KEY));
		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
		<Resource>\r\n
		<Book>\r\n
		<bookId>{$articleid}</bookId>\r\n
		<supplyId>".MySuning::SUPPLYID."</supplyId>\r\n
		<chapterNums>{$chapterNums}</chapterNums>\r\n
		<digitalSignature>{$digitalSignature}</digitalSignature>\r\n
		</Book>\r\n
		</Resource>";
		$responseData =  $this->fsockPost('/esbadapter/EbookArticleContentMgmt_ALL/submitEbookChapter',MySuning::HOST,jieqi_gb2utf8 ($xml));
		$responseData = jieqi_utf82gb($responseData);
		if($responseData){
			preg_match ( "/(.+)\<respCode\>(.+)\<\/respCode\>/isU", $responseData, $matches );
			$respCode = intval($matches[2]);
			preg_match ( "/(.+)\<respDes\>(.+)\<\/respDes\>/isU", $responseData, $matches );
			$respDes = $matches[2];
			// 		return $respCode;
			return array('code'=>$respCode,'msg'=>$respDes);
		}else{
			$this->out_msg_err ( '---->submit socket response data null');
			exit;
		}

	}
	/**
	 * (non-PHPdoc)
	 * @see iApi::addBook()
	 */
	function addBook($article,$data){
			// 新推送
		$responseData = $this->fsockPost ( '/esbadapter/EbookArticleContentMgmt_ALL/synEbookBooksInfo', MySuning::HOST, $data );
		$responseData = jieqi_utf82gb ( $responseData );
		if ($responseData) {
			preg_match ( "/(.+)\<respCode\>(.+)\<\/respCode\>/isU", $responseData, $matches );
			$respCode = intval ( $matches [2] );
			preg_match ( "/(.+)\<respDes\>(.+)\<\/respDes\>/isU", $responseData, $matches );
			$respDes = $matches[2];
			if ($matches && ($respCode === 0 || $respCode === 1)) {
				if ($respCode === 0) {
					// 添加封面
					$digitalSignature = sha1 ( jieqi_gb2utf8 ( MySuning::SUPPLYID . $this->KEY ) );
					$coverStream = jieqi_readfile ( JIEQI_ROOT_PATH . "/api/api_image" . $article ['image'] );
					$imgContent = base64_encode ( $coverStream );
					$xmlCover = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
					<Resource>\r\n
					<Book>\r\n
					<bookId>{$article['articleid']}</bookId>\r\n
					<supplyId>" . MySuning::SUPPLYID . "</supplyId>\r\n
					<imgContent>{$imgContent}</imgContent>\r\n
					<digitalSignature>{$digitalSignature}</digitalSignature>\r\n
					</Book>\r\n
					</Resource>";
					$responseData = $this->fsockPost ( '/esbadapter/EbookArticleContentMgmt_ALL/synEbookBooksCover', MySuning::HOST, $xmlCover );
					$responseData = jieqi_utf82gb ( $responseData );
					if($responseData){
						preg_match ( "/(.+)\<respCode\>(.+)\<\/respCode\>/isU", $responseData, $matches );
						$respCode = intval ( $matches [2] );
						preg_match ( "/(.+)\<respDes\>(.+)\<\/respDes\>/isU", $responseData, $matches );
						$respDes = $matches [2];
						if ($respCode === 0 || $respCode === 1) {
							return 0;
						} else {
							$this->out_msg_err ( '---->封面出错，错误编号：' . $respCode . '错误信息：' . $respDes );
							return false;
						}
					}else{
						$this->out_msg_err ( '---->add cover socket response data null');
						return false;
					}
				}
				return 1;
			} else {
				$this->out_msg_err ( '---->加书出错，错误编号：' . $respCode . '，错误信息：' . $respDes );
				return false;
			}
		} else {
			$this->out_msg_err ( '---->add book socket response data null');
			return false;
		}
	}
	//不需要实现，可通过父类baseApi提供的fsockPost实现
	function request($url, $mode, $params=array(), $header = 'Content-Type: text/plain; charset=utf-8;'){
	}

	/**
	 * 获取本站文章分类映射的苏宁采购分类，默认：苏宁R9000155分类
	 * @param unknown $sortid		本站分类Id
	 * @return Ambigous <string>	对应的苏宁采购分类
	 * 2014-9-9 下午3:11:37
	 */
	private function getCateg($sortid){
		$category = array(
				1=>'R9000155',
				2=>'R9000159',
				3=>'R9000158',
				4=>'R9000164',
				5=>'R9000165',
				6=>'R9000157',
				7=>'R9000166',
				8=>'R9000163',
				9=>'R9000161',
				10=>'R9000162',
				11=>'R9000167',
				12=>'R9000160'
		);
		if(!$sortid || !array_key_exists($sortid, $category)){
			$sortid = 1;//默认：R9000155分类
		}
		return $category[$sortid];

	}
	/**
	 * 获取本站文章分类映射的苏宁采编分类，默认：苏宁110100采编分类
	 * @param unknown $sortid		本站分类Id
	 * @return Ambigous <string>	对应的苏宁采编分类
	 * 2014-9-9 下午3:13:39
	 */
	private function getNewCateg($sortid){
		$category = array(
				1=>'110100',
				2=>'150400',
				3=>'140300',
				4=>'230100',
				5=>'220200',
				6=>'130100',
				7=>'240100',
				8=>'170200',
				9=>'160200',
				10=>'210200',
				11=>'250100',
				12=>'260200'
		);
		if(!$sortid || !array_key_exists($sortid, $category)){
			$sortid = 1;//默认：110100
		}
		return $category[$sortid];
	}
	/**
	 * 实现接口定义的推送方法
	 * @param unknown $channleid
	 * @param unknown $article
	 * 2014-9-10 上午9:19:27
	 */
	function push($channleid, $article) {
		$setting = $article ['setting'];//数组
		if (!$article ['image']) {
			$this->out_msg_err ( '---->请上传大封面，封面要求如下：' );
			$this->out_msg_err ( '---->1.评价封面质量的主要标尺：图片像素，原则上要求封面原图像素为600x800及以上；' );
			$this->out_msg_err ( '---->2.若无法提供600x800及以上的封面，可视清晰度接受300x400以上的封面，此类封面原则为放大到600*800仍然清楚；' );
			$this->out_msg_err ( '---->3.由低像素图片拉伸为600x800的封面不接受；' );
			$this->out_msg_err ( '---->4.300x400以下的封面不接受；' );
			$this->out_msg_err ( '---->5.如有其它公司LOGO等，要去除；' );
			$this->out_msg_err ( '---->6.拍照、扫描出来的封面会存在晦暗、失真、变形的情况，不接受（高清扫描仪扫描新书封面可视情况接受）;' );
			$this->out_msg_err ( '---->7.封面不能为立体封面；' );
		} else {
			$coverUrl = JIEQI_LOCAL_URL . "/api/api_image" . $article ['image'];
			$freeChapter = $this->getChapters ( $article ['articleid'], 0, 0 );
			$freeCont = count ( $freeChapter );
			$categ = $this->getCateg ( $article ['sortid'] );
			$newCateg = $this->getNewCateg ( $article ['sortid'] );
			$digitalSignature = sha1 ( jieqi_gb2utf8 ( $article ['articleid'] . $article ['articlename'] . MySuning::SUPPLYID . $this->KEY ) );
			$xmldata = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n
				<Resource>\r\n
					<Book>\r\n
						<bookId>{$article ['articleid']}</bookId>\r\n
						<bookName><![CDATA[{$article ['articlename']}]]></bookName>\r\n
						<author><![CDATA[{$article ['author']}]]></author>\r\n
						<bookState>2</bookState>\r\n
						<description><![CDATA[{$article ['intro']}]]></description>\r\n
						<coverUrl><![CDATA[{$coverUrl}]]></coverUrl>\r\n
						<supplyId>" . MySuning::SUPPLYID . "</supplyId>\r\n
						<categ>{$categ}</categ>\r\n
						<newCateg>{$newCateg}</newCateg>\r\n
						<local>1</local>\r\n
						<pricingMode>1</pricingMode>\r\n
						<freeChapter>{$freeCont}</freeChapter>\r\n
						<paperPrice>0.03</paperPrice>\r\n
						<deadline>2020-01-01</deadline>\r\n
						<authorSummary></authorSummary>\r\n
						<mediaComment></mediaComment>\r\n
						<digitalSignature>{$digitalSignature}</digitalSignature>\r\n
					</Book>\r\n
				</Resource>";
			// xml格式转换utf-8编码
			$chapters_ch = $this->getChapters ( $article ['articleid']);//章节
			$result = $this->addBook ( $article, jieqi_gb2utf8 ( $xmldata ) );
			$startupdate = false; // 开始推送标识
			if ($result === 0) { // 新建
				$this->out_msg ( '---->新建成功，共'.count($chapters_ch).'章节需要推送。' );
				$startupdate = true;
			} elseif ($result === 1) {// 已经存在
				$data = $this->get_lastupdate ( $article ['articleid'] );
				if ($data ['code'] && $data ['code'] == 11) {
					$this->out_msg ( '---->此书籍新创建未同步任何章节，共'.count($chapters_ch).'章节需要推送。' );
					$startupdate = true;
				} else {
					$this->out_msg ( '---->上次推送信息：' );
					$this->out_msg ( '---->章节ID：' . $data ['chapterNo'] );
					$this->out_msg ( '---->章节名称：' . $data ['chapterName'] );
					$this->out_msg ( '---->更新时间：' . $data ['upDate'] );
					// 更新到的章节位置
					if (!$article ['lastchapterid']) {
						$this->out_msg_err ( '---->手动维护最后推送的章节Id' );
						return;
					}
				}
			} else {
				$this->out_msg_err ( '---->向API推送《' . $article ['articlename'] . '》时出错，跳过本书！' );
				return;
			}
			$chapters = $this->getChapters ( $article ['articleid'], 2 );//章节+卷
			// 开始推送
			$totalchapter = count ( $chapters_ch ); // 总章节数
			$lastvolumeid = 0;
			//$k:累计推送章节数
			//$s:当次推送的章节数
			//$a:$chapters（章节+卷）的位置标识
			//$v:卷位置标识
			$k = $s = $a = $v = 0;
			$lastvolume = '';
			foreach ( $chapters as $c ) {
				$a++;
				if ($c ['chaptertype'] == 1) { // 记录推送章节所在的卷
					$lastvolumeid = $c ['cpchapterid'];
					$lastvolume = $c ['chaptername'];
					$v = $a;
// 					continue;//bug，如果上次推送的最后一章是卷，会导致无法定位节点
				}
				$k ++;
				if (! $startupdate) {
					// 定位推送位置
					if ($article ['lastchapterid'] != $c ['cpchapterid']) {
						continue;
					} else {
						$startupdate = true;
						if (($totalchapter - $k) > 0) {
							$this->out_msg ( '---->《' . $article ['articlename'] . '》上次更新到[' . $c['chaptername'] . '],还有<b>' . ($totalchapter - $k) . '</b>篇需要推送' );
						} else {
							$this->out_msg ( '---->无章节需要推送!' );
						}
					}
				} else {
					$s++;
					if($s === 1){
						$this->out_msg ( '<table border=1><tr><th>序</th><th>章节名称</th><th>推送</th><th>提交</th></tr>',false);
					}
					//每个卷的第一个章节名称格式：第XX卷 XXX 第一章 XXX
					if($lastvolumeid && $lastvolume && $v+1 == $a){
						$c['chaptername'] = $lastvolume.' '.$c['chaptername'];
					}
					$result = $this->addChapter(array('articleid'=>$article ['articleid'],'chapterNo'=>$k,'chaptername'=>$c['chaptername'],'content'=>$c['content']));
					if($result['code'] === 0){
						$submitResutl = $this->submit(array('articleid'=>$article ['articleid'],'chapterNums'=>$k));
						if($submitResutl['code'] === 0){
							$submit = '成功';
						}else{
							$submit = '失败：'.$submitResutl['msg'];
						}
						$this->out_msg ( "<tr><td>{$s}</td><td>{$c['chaptername']}</td><td>成功</td><td>{$submit}</td></tr>",false);
						if($s == $totalchapter){//最后一个
							$this->out_msg( "</table>",false);
						}
						// 同步数据池的最后更新的
						$article ['lastvolumeid'] = $lastvolumeid;
						$article ['lastvolume'] = $lastvolume;
						$article ['lastchapterid'] = $c ['cpchapterid'];
						$article ['lastchapter'] = $c ['chaptername'];
						$article ['outchapters'] = $k;//累计推送的章节总数（章节序号）
						$article ['fullflag'] = $article ['full'];
						$article ['lastdate'] = JIEQI_NOW_TIME; // 推送时间
						$article ['setting'] =  $this->arrayeval($setting);
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
							$this->out_msg_err ( '---->数据池文章《' . $article ['articlename'] . '》，推送记录同步失败！' );
						}
						if($setting['daychapter']
						&& is_numeric($setting['daychapter'])
						&& $setting['daychapter'] > 0
						&& $setting['daychapter'] == $s){
							$this->out_msg ('</table>---->每次只推送'.$s.'章' );
							break;
						}
					}else{
						if($result['code'] === 1){
							//已经推送的重新提交
							$submitResutl = $this->submit(array('articleid'=>$article ['articleid'],'chapterNums'=>$k));
							if($submitResutl['code'] === 0){
								$submit = '重新提交成功';
								$this->out_msg ( "<tr><td>{$s}</td><td>{$c['chaptername']}</td><td><font color='red'>失败:".$result['msg']."</font></td><td>{$submit}</td></tr>",false);
							}else{
								$submit = '重新提交失败：'.$submitResutl['msg'];
								$this->out_msg ( "<tr><td>{$s}</td><td>{$c['chaptername']}</td><td><font color='red'>失败:".$result['msg']."</font></td><td>{$submit}</td></tr></table>",false);
								return;
							}
						}else{
							$this->out_msg ( "<tr><td>{$s}</td><td>{$c['chaptername']}</td><td><font color='red'>失败:".$result['msg']."</font></td><td>未提交</td></tr></table>",false);
							return;
						}
						
					}
				}
			}
			if (!$startupdate) {
				$this->out_msg_err ( '---->未匹配最后推送的章节，手动维护最后推送的章节ID' );
			}
		}
	}
}
?>