<?php
/**
 * 数据池章节模型
 * @author chengyuan  2014-9-12
 *
 */
class chapterModel extends Model{
	/**
	 * 默认
	 * @param unknown $params
	 * 2014-8-27 下午3:24:40
	 */
	public function main($params = array()){}
	/**
	 * 底层逐行输出
	 * @param unknown $msg
	 */
	private function msg($msg){
		$sapi = php_sapi_name();
		if($sapi == 'cgi-fcgi'){
			echo str_pad($msg,1024*64);
		}else{
			if($this->first_out){
				echo str_repeat(' ',4096);
				$this->first_out = false;
			}
			echo $msg;
		}
		ob_flush();
		flush();
	}
	/**
	 * 使用数据池作为渠道数据源情况，同步书海审核通过的章节至渠道数据池。
	 * <p>
	 * 渠道接口按照类型分类：推送，展示，采集。
	 * <p>
	 * 其中推送和采集会使用my_xxx自定义类，区别处理展示渠道。
	 * @param unknown $channleid	渠道id
	 * @param unknown $aid			渠道文章id数组
	 * 2014-8-27 下午3:25:48
	 */
	public function synchronization($channleid,$paidArray){
		define ( 'JIEQI_USE_GZIP', '0' );
		ini_set ( 'zlib.output_compression', 0 );
		ini_set ( 'implicit_flush', 1 );
		ob_start ();
		ob_end_flush ();
		ob_implicit_flush ();
		@set_time_limit ( 0 );
		@session_write_close ();
		$channelLib = $this->load('channel', 'pooling');
		$channel = $channelLib->get($channleid,true);
		if($channel['type'] != 1){//展示 不加载自定义类
			$apiLib = $this->load($channel['url'],'pooling');
		}
		if(!interface_exists('iSynchronization')){
			include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iSynchronization.php');//同步接口
		}
// 		$implements = class_implements($apiLib);//api自定义类实现的接口
		$articleLib = $this->load ( 'article', 'article' );
		//获取数据池文章，
		//循环，同步章节，章节内容存储在数据库中
		$paids = implode(",", $paidArray);
		$plArts = $this->db->selectsql ('select * from ' . jieqi_dbprefix ( "pooling_article" ) . " where  paid in (" .$paids.')');
		//同步章节
		foreach($plArts as $k=>$v){
			$synchronization  = false;
			if($v['setting']){
				eval('$setting = '.$v['setting'].';');//字符串转数组,上次同步记录信息
			}else{
				$setting = array();
			}
			$plChapters = $this->db->selectsql ('select * from ' . jieqi_dbprefix ( "pooling_chapter" ) . " where  paid = " .$v["paid"]);//渠道书的同步章节
			if(!$setting['lastchapterid'] && $plChapters && count($plChapters)>0){
				//异常，没有上次的同步位置
				$this->msg("无法同步，".$v["articlename"]."没有上次的同步位置，请联系管理员！");
				continue;
			}
			if(!$plChapters){
				$synchronization  = true;
			}
			$this->msg("<table border=1><caption>{$v["articlename"]}->同步中</caption><tr><th>序</th><th>名称</th><th>类型</th><th>同步结果</th></tr>");
			$articleLib->instantPackage ( $v['articleid'] );
			$this->db->init('chapter', 'chapterid', 'article');
			//查询章节
			$this->db->setCriteria ( new Criteria ( 'articleid', $v['articleid'] ) );
			$this->db->criteria->add ( new Criteria ( 'display',0) );
// 			$this->db->criteria->add ( new Criteria ( 'size',0,'>') );
			$this->db->criteria->setSort ( 'chapterorder' );
			$this->db->criteria->setOrder ( 'ASC' );
			$this->db->queryObjects ();
			$i = 0;
			while ( $chapter = $this->db->getObject () ) {
				//同步至jieqi_pooling_chapter
				if($synchronization){
					$i++;
					$plchapter = array();
					$plchapter['paid'] = $v['paid'];
					$plchapter['articleid'] = $chapter->getVar ( 'articleid' );
					$plchapter['chapterid'] = $chapter->getVar ( 'chapterid' );
					$plchapter['channelid'] = $channleid;
					$plchapter['chaptername'] = trim($chapter->getVar ('chaptername','n'));
					$plchapter['chapterorder'] = $chapter->getVar ( 'chapterorder' );
					$plchapter['content'] = @$articleLib->getContent ( $chapter->getVar ( 'chapterid' ) );//章节内容
					$plchapter['size'] = $chapter->getVar ( 'size' );
					$plchapter['chaptertype'] = $chapter->getVar ( 'chaptertype' );
					$plchapter['adddate'] = JIEQI_NOW_TIME;//章节同步时间的时间点
					// 				$plchapter['pushdate'] = 0;//推送的时间
					$plchapter['isvip'] = $chapter->getVar ( 'isvip' );
					$plchapter['split'] = 0;
					$plchapter['description'] = '';
					if($apiLib instanceof iSynchronization) {//是否实现iSynchronization interface
						$apiLib->handlePoolChapter($plchapter);
					}
					$this->db->init('chapter', 'pcid', 'pooling');
					$this->db->add($plchapter);

					//同步一章，jieqi_pooling_article 更新一次状态
					$this->db->init('article', 'paid', 'pooling');
					//setting是记录同步的属性
					//lastvolumeid，lastvolume，lastchapterid，lastchapter 记录推送的状态
					//同步状态
					$newSetting = array();
					$newSetting['lastchapterid'] = $chapter->getVar ( 'chapterid' );//标记同步位置
					if($chapter->getVar ( 'chaptertype' ) == 1){//volume
						$newSetting['lastvolumeid'] = $chapter->getVar ( 'chapterid' );
						$newSetting['lastvolume'] = $chapter->getVar ( 'chaptername' );
					}else{//chapter
// 						$newSetting['lastchapterid'] = $chapter->getVar ( 'chapterid' );
						$newSetting['lastchapter'] = $chapter->getVar ( 'chaptername' );
					}
					if($setting['daychapter']){
						$newSetting['daychapter'] = $setting['daychapter'];
					}
					$v['setting'] = $this->arrayeval($newSetting);
					$this->db->edit($v['paid'],$v);
					$type = $plchapter['chaptertype'] ? '卷' : '章节';
					$this->msg("<tr><td>{$i}</td><td>{$plchapter['chaptername']}</td><td>{$type}</td><td>ok</td></tr>");
				}else{
					if($setting['lastchapterid'] && $setting['lastchapterid'] == $chapter->getVar ( 'chapterid' )){
						$synchronization = true;//定位上次同步的位置，从下一个章节开始同步
					}
					continue;
				}
			}
			if($i == 0){
				$this->msg('<tr><td colspan=4>已经是最新</td></tr>');
			}
			$this->msg("</table>同步结束");
		}
	}
	/**
	 * 数据池文章，章节列表
	 * @param unknown $channleid	渠道Id
	 * @param unknown $paid			数据池文章Id
	 * @return multitype:string NULL unknown [article,channel,chapters]
	 * 2014-9-12 上午11:37:36
	 */
	public function chapterList($channleid,$paid){
		$data = array();
		$chapters = $this->db->selectsql ('select pcid,chaptertype,chaptername,adddate from ' . jieqi_dbprefix ( "pooling_chapter" ) . " where  paid = " .$paid.' order by chapterorder');
		$data['chapters'] = $chapters;
		$this->db->init('article', 'paid', 'pooling');
		$data['article'] = $this->db->get ($paid);
		$this->db->init('channel', 'channelid', 'pooling');
		$data['channel'] = $this->db->get ($channleid);
		$data['JIEQI_NOW_TIME'] = JIEQI_NOW_TIME;
		return $data;
	}
	/**
	 * 获取章节信息，msgbox会根据提交类型（ajax）解析$chapter为json格式输出至前台。
	 * @param unknown $cid	数据池章节Id
	 * 2014-9-12 上午11:42:32
	 */
	public function getChapter($cid){
		$this->db->init('chapter', 'pcid', 'pooling');
		$chapter = $this->db->get ($cid);
		$this->msgbox('',$chapter);
	}
	/**
	 * 插入一个章节，插入$pcid指定的章节后面
	 * @param unknown $pcid				数据池章节Id
	 * @param unknown $chaptername		数据池章节名称
	 * @param unknown $content			数据池章节内容
	 * @param unknown $insertChapterName	插入的章节名称
	 * @param unknown $insertContent		插入的章节内容
	 * 2014-9-12 下午2:29:07
	 */
	public function insertChapter($pcid,$chaptername,$content,$insertChapterName,$insertContent){
		$this->db->init('chapter', 'pcid', 'pooling');
		//1保存修改的章节
		$this->editChapter($pcid, $chaptername, $content,false);
		//2批量更新chapterorder，插入新章节。
		//批量更新chapterorder+1
		$chapter = $this->db->get ($pcid);
		$this->db->updatetable ( 'pooling_chapter', array (
				'chapterorder' => '++'
		), 'paid = ' . $chapter ['paid'] . ' and chapterorder > ' . $chapter['chapterorder'] );
		$chapter['pcid'] = '';
		$chapter['chapterid'] = 0;
		$chapter['chaptername'] = $insertChapterName;
		$chapter['content'] = $insertContent;
		$chapter['chapterorder'] =intval($chapter['chapterorder'])+1;
		$chapter['adddate'] = JIEQI_NOW_TIME;
		$chapter['size'] = jieqi_strlen ($insertContent);
		$this->db->add($chapter);
		$this->jumppage('','','');
	}
	/**
	 * 修改章节
	 * @param unknown $pcid
	 * @param unknown $chaptername
	 * @param unknown $content
	 * @param string $jump
	 * 2014-9-12 下午2:27:09
	 */
	public function editChapter($pcid,$chaptername,$content,$jump=true){
		$this->db->init('chapter', 'pcid', 'pooling');
		if($content){
			$this->db->edit ($pcid,array("chaptername"=>$chaptername,'content'=>$content,'size'=>jieqi_strlen ($content)));
		}else{
			$this->db->edit ($pcid,array("chaptername"=>$chaptername));
		}
		if($jump == true){
			$this->jumppage('','','');
		}
	}
	/**
	 * 删除章节
	 * @param unknown $pcid
	 * 2014-9-12 下午2:33:23
	 */
	public function delete($pcid){
		$this->db->init('chapter', 'pcid', 'pooling');
		if($chapter = $this->db->get ($pcid)){
			$this->db->delete($pcid);
			$this->db->updatetable ( 'pooling_chapter', array (
					'chapterorder' => '--'
			), 'paid = ' . $chapter ['paid'] . ' and chapterorder > ' . $chapter['chapterorder'] );
			//更新排序
			$this->jumppage('','','');
		}
	}

























}
?>