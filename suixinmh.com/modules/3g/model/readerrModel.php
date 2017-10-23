<?php
/**
 * 目录业务模型
 * @author chengyuan  2014-8-6
 *
 */
include_once(JIEQI_ROOT_PATH."/include/funsystem.php");
class readerrModel extends Model {
	/**
	 * 章节内容分页阅读
	 * @param unknown $articleid	aid
	 * @param unknown $cid			cid
	 * @param unknown $psize		分页字数：1000,2000，-11（不分页）
	 * @param unknown $page			页数
	 * @return multitype:NULL unknown number Ambigous <string, string>
	 * 2014-8-12 上午9:53:00
	 */
	public function reader($articleid,$cid,$psize,$page) {
		if(!$page || !is_numeric($page) || $page < 1){
			$page = 1;//默认第一页
		}

		if (get_user_agent() == 1) {
			$auth = $this->getAuth();
            if (!$auth['uid']) {
                $readurl = $this->geturl('3g', 'reader', 'aid='.$articleid,'cid='.$cid)."?".time();
                if (is_numeric($_GET['block']))
                    $readurl.="&block=".$_GET['block'];

                if (is_numeric($_GET['qd']))
                    $readurl.="&qd=".$_GET['qd'];

                $readurl = urlencode($readurl);
                header("location:/wxlogin/?jumpurl=$readurl");
                exit();
            }
		}

		$package = $this->load('articleWap', '3g');
		if (!$package->loadOPF($articleid)) {
			$this->addLang('article', 'article');
			$jieqiLang ['article'] = $this->getLang('article'); // 所有语言包配置赋值
			$this->printfail($jieqiLang ['article'] ['article_not_exists']);
		}

		$dataObj = $this->model('reader', 'article');
		define('RETURN_READER_DATA', true);

		$data = $dataObj->main(array('aid' => $articleid, 'cid' => $cid));

        if ($_SESSION['buyonechapter']) {
            if (($data['chapter']['shubi'] || $data['chapter']['shuquan']) && $data['chapter']['isvip'] && !$data['chapter']['content']) {//没有购买
                $auth = $this->getAuth();
                if ($auth['egolds'] >= $data['chapter']['shubi'] || $auth['esilvers'] >= $data['chapter']['shuquan'] ) {
                    define('NOT_PUT_DATA', true);
                    $url = $this->geturl('3g', 'reader', 'aid=' . $articleid, 'cid=' . $cid);
                    $buyresult = $this->buychapter($articleid, $cid, $url);
                }
            }
        }



		//content分页处理
		$attach = array();
		if ($psize)
			$pagesize = intval($psize);
		elseif (!empty($_SESSION['jieqiUserSet']['wappsize']))
			$pagesize = intval($_SESSION['jieqiUserSet']['wappsize']);
		elseif (!empty($_COOKIE['jieqiWapPsize']))
			$pagesize = intval($_COOKIE['jieqiWapPsize']);
		else
			$pagesize = 1000;//默认一千字
 		if(!in_array($pagesize, array(1000,2000,-11))){
 			$pagesize = 1000;//默认1000字
 		}
		if($pagesize  > 1){
			$startPosition=($page - 1) * $pagesize * 2;
			$pagecontent=jieqi_substr($data['chapter']['content'], $startPosition, $pagesize * 2);
			$attach['content'] = $pagecontent;
			$attach['pagesize'] = $pagesize;
			//分页代码
			if($pagesize*2<strlen($data['chapter']['content'])){
				//分页
				$pageCode='[prepage]<a href="{$prepage}" target="_self">上页</a>[/prepage] [nextpage]<a href="{$nextpage}" target="_self">下页</a>[/nextpage] <em class="on">{$page}</em>/<em class="px3">{$totalpage}</em>';
				$jumppage = new GlobalPage($pageCode,strlen($data['chapter']['content']),$pagesize*2, $page);
				$jumppage->emptyonepage = false;
				$attach['url_jumppage'] = $jumppage->getPage($this->geturl('wap','reader','evalpage=0','SYS=aid='.$articleid.'&cid='.$cid));
			}
		}else{
			//全部显示
			$attach['pagesize'] = $pagesize;
			$attach['content'] = $data['chapter']['content'];
		}
		$data['attach'] = $attach;
		$data['jumpurl'] = $_SERVER['REQUEST_URI'];//登陆后的回调地址
		$data['url_checkcode'] = JIEQI_USER_URL.'/checkcode.php';
		$this->savePageSize($pagesize);

		if (!$data['chapter']['isvip']) {
//			$data['show_qrcode'] = 0;
			$read_pagenum = intval($_COOKIE['read_pagenum']);
			if ($read_pagenum <3) {
				setcookie("read_pagenum",$read_pagenum+1);
				$data['show_qrcode'] = 0;
			}
			else {
				$data['show_qrcode'] = 1;
			}
		}
		else {
			$data['show_qrcode'] = 1;
		}
        include_once(dirname(__FILE__).'/../../../configs/article/blocks.php');
        if ($article_block_list[$articleid]) {
            if (isset($_GET['block'])) {
                $_SESSION['block'] = intval($_GET['block']);
                $block = intval($_GET['block']);
            } else {
                if ($_GET['qd']) {
                    $block = 0;
                } else {
                    $block = intval($_SESSION['block']);
                }
            }
            $data['blockpic'] = '';
            if ($block) {
                if ($article_block_list[$articleid]['chapterid'] == $cid) {
                    $data['blockpic'] = $article_block_list[$articleid]['blockpic'];
                }
            }
            $data['block'] = $block;
        }
        $this->save_history($articleid,$cid);

        if (is_numeric($_GET['qd']) && $data['block']==0) {
            $_SESSION['from_gzh']=0;
        }

        if ($_SESSION['from_gzh']) {
            $data['show_qrcode'] = 0;
        }

		return $data;
	}



    /**
     * 保存每个用户的阅读记录
     */
    private function save_history($articleid,$cid) {
        $auth = $this->getAuth();
        if (!$auth['uid']) {
            return 0;
        }

        $sql="update jieqi_article_chapterstat set visit=visit+1 where articleid=$articleid and chapterid=$cid";
        $this->db->query($sql);
        if (!$this->db->getAffectedRows()) {
            $this->db->init("chapterstat","id","article");
            $this->db->add(array(
                    'articleid'=>$articleid,
                    'chapterid'=>$cid,
                    'visit'=>1
                )
            );
        }

        $this->db->init("history","id","article");
        $this->db->setCriteria(new Criteria( 'uid', $auth['uid']));
        $this->db->criteria->add ( new Criteria ( 'articleid', $articleid ));
        $result=$this->db->queryObjects();

        $h=$this->db->getRow($result);

        $history_arr=array(
            "uid"=>$auth['uid'],
            'chapterid'=>$cid,
            'articleid'=>$articleid,
            'readtime'=>time(),
            'qd'=>$auth['source']
        );
        if (!$h['id']) {
            return $this->db->add($history_arr);
        }
        else {
            return $this->db->edit($h['id'],$history_arr);
        }

    }


    /**
	 * 保存阅读页设置的显示字数
	 * <p>
	 * cookie优先存储，如果是登陆用户则同步至session中，并且同步数据库登陆用户设置中。
	 * @param unknown $pagesize	字数
	 * 2014-8-12 上午9:14:46
	 */
	private function savePageSize($pagesize){
		$auth = $this->getAuth();
		@setcookie('jieqiWapPsize', $pagesize, JIEQI_NOW_TIME+(3600 * 24 * 365), '/');
		if(!empty($auth['uid']) && $_SESSION['jieqiUserSet']['wappsize'] != $pagesize){
			$_SESSION['jieqiUserSet']['wappsize']=$pagesize;
// 			include_once(JIEQI_ROOT_PATH.'/class/users.php');
// 			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
// 			$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
			$users_handler = $this->getUserObject();
			$jieqiUsers = $users_handler->get($auth['uid']);
			if(is_object($jieqiUsers)){
				$userset=unserialize($jieqiUsers->getVar('setting','n'));
				if(!is_array($userset)) $userset=array();
				$userset['wappsize']=$pagesize;
				$jieqiUsers->setVar('setting', serialize($userset));
				$users_handler->insert($jieqiUsers);
			}
		}
	}
	/**
	 * 单章购买
	 * @param unknown $aid		articleid
	 * @param unknown $cid		chapterid
	 * @param unknown $readurl	购买成功的回调URL
	 * 2014-8-13 上午9:33:44
	 */
	public function buychapter($aid,$cid,$readurl){
		if(!$aid || !$cid || !$readurl){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$dataObj = $this->model('reader','article');
		$dataObj->buychapter(array('aid'=>$aid,'cid'=>$cid,'readurl'=>$readurl));
	}

	public function xbuychapter($aid,$cid,$readurl){
		if(!$aid || !$cid || !$readurl){
			$this->printfail(LANG_ERROR_PARAMETER);
		}
		$dataObj = $this->model('reader','article');
		$dataObj->batchbuychapter(array('aid'=>$aid,'cid'=>$cid,'readurl'=>$readurl));
	}



    public function readnext($param) {
        $auth=$this->getAuth();
        $uid=$auth['uid'];


        if (get_user_agent() == 1) {
            if (!$auth['uid']) {
                $readurl = '/read/next';
                $readurl = urlencode($readurl);
                header("location:/wxlogin/?jumpurl=$readurl");
                exit();
            }
        }

        if (!$uid) {
            $url=$this->geturl('3g', 'article', 'method=bcView');
            header("location:$url");
        }
        $_SESSION['from_gzh']=1;
        $readinfo=$this->get_reader_history($uid);
        if (!$readinfo) {
            header("location:/");
        }
        else {
            $aid=$readinfo['articleid'];
            $cid=$readinfo['chapterid'];
            $url=$this->geturl('3g', 'reader', 'aid='.$aid,'cid='.$cid);
            if (strpos($url,'?')!==false) {
                $url.="&block=0";
            }
            else {
                $url.="?block=0";
            }
            header("location:$url");
        }
        exit();
    }

    private function get_reader_history($uid) {
        $this->db->init("history","id","article");
        $this->db->setCriteria(new Criteria('uid', $uid));
        $this->db->criteria->setSort('readtime');
        $this->db->criteria->setOrder('DESC');
        $this->db->criteria->setLimit(1);
        $res=$this->db->queryObjects();
        $row=$this->db->getRow($res);
        if ($row) {
            $next_chapter_id=$this->get_next_chapter($row['articleid'],$row['chapterid']);

            if ($next_chapter_id) {
                return array("articleid"=>$row['articleid'],'chapterid'=>$next_chapter_id);
            }
            else {
                return array("articleid" => $row['articleid'], 'chapterid' => $row['chapterid']);
            }
        }
        else{
            return false;
        }
    }

    private  function get_next_chapter($aid,$cid) {
        $package = $this->load('article', 'article');
        if (!$package->loadOPF($aid)) {
            $this->addLang('article', 'article');
            $jieqiLang['article'] = $this->getLang('article');//所有语言包配置赋值
            $this->printfail($jieqiLang['article']['article_not_exists']);
        }
        $findchapter=0;
        for($i=0;$i<count($package->chapters);$i++) {
            $chapter=$package->chapters[$i];
            $checkhref=$cid.'.txt';
            if ($checkhref == $chapter['href']) {
                $findchapter=$i;
                break;
            }
        }
        if ($findchapter && $package->chapters[$findchapter+1]) {
            $nexthref=$package->chapters[$findchapter+1]['href'];
            $nextchapter=substr($nexthref,0,strlen($nexthref)-4);
            return $nextchapter;
        }
        return false;
    }

    function load_reader_cache($aid,$cid) {

    }

    function store_reader_cache($aid) {

    }
}

?>