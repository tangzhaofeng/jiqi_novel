<?php
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/iDisplayJson.php');
include_once ($GLOBALS ['jieqiModules'] ['pooling'] ['path'] . '/class/base.php');
// include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
/**
 * 渠道百度云内容接口，通过章节正文提供给第三方内容
 * <p>
 * 实现iDisplayJson接口的方法，articleInfo书详情信息方法中章节地址chapter_url提供章节正文接口（chapterContent）地址，
 * <p>
 * 渠道浏览，json格式。
 * @author chengyuan  2014-7-3
 *
 */
class MyDisplayJsonBaiducloud extends base implements iDisplayJson {
	/* (non-PHPdoc)
	 * @see iDisplayJson::articlePage()
	 */
	public function articlePage($date) {
		// TODO Auto-generated method stub
		if($date['totalpage']){
			for ($i = 1; $i <= $date['totalpage']; $i++) {
				$resutl['page_list'][] = "http://{$date['HTTP_HOST']}?ac=page&page={$i}";
			}
		}
		return $resutl;
	}

	/* (non-PHPdoc)
	 * @see iDisplayJson::articleList()
	 */
	public function articleList($date) {
		// TODO Auto-generated method stub
		foreach ($date['rows'] as $key => $row) {
			$resutl[] = array('book_id'=>$row['articleid'],'update_time'=>$row['lastupdate'],'link'=> "http://{$date['HTTP_HOST']}?ac=chapters&aid={$row['articleid']}");
		}
		return $resutl;
	}

	/* (non-PHPdoc)
	 * @see iDisplayJson::articleInfo()
	 */
	public function articleInfo($date) {
		// TODO Auto-generated method stub
		$item = $date['article']['channel'].'_*_';
		if($date['article']['keywords']){
			foreach (explode(" ", $date['article']['keywords']) as $key => $value) {
				$tag .= ','.$item.$value;
			}
		}else{
			$tag = $item.'*';
		}
		$result = array(
				"book_id"=>$date['article']['articleid'],
				"book_name"=>$date['article']['articlename'],
				"author"=>$date['article']['author'],
				"channel"=>$date['article']['channel'],
				"category"=>$date['article']['channel'].'_'.$date['article']['shortcaption'].'_*',
				"tag"=>$tag,
				"book_status"=>$date['article']['fullflag']?'完结':'连载',
				"description"=>$date['article']['intro'],
				"log"=>$date['article']['url_image_l'],
				"first_published"=>$date['article']['firstflag']==0?1:0,//1-在本站首发，0-非在本站首发
				"origin_provider"=>JIEQI_URL,
				"price_status"=>$date['article']['articletype'],//0 免费，1 收费
				"price_pattern"=>0,// 0 按章，1 按本
				"price"=>0,
				"chapter_price"=>0,
				"chapter_number"=>$date['article']['chapters'],
				"save_content"=>1
		);
		foreach ($date['chapters'] as $key => $chapter) {
			$result['group'][] = array(
					'chapter_index'=>$chapter['chapterorder'],
					'chapter_title'=>$chapter['chaptername'],
					'chapter_url'=>$chapter['url'],
					'price_status'=>$chapter['isvip'],
					'price'=>bcdiv($chapter['saleprice'],100,2),
					'public_time'=>$chapter['lastupdate'],
			);
		}
		return $result;
	}
	/* (non-PHPdoc)
	 * @see iDisplayJson::chapterList()
	*/
	public function chapterList($date){
		//todo 不需要实现,articleInfo中有章节列表
		return $this->articleInfo($date);
	}
	/* (non-PHPdoc)
	 * @see iDisplayJson::chapterContent()
	 */
	public function chapterContent($chapter) {
		// TODO Auto-generated method stub
		$result = array(
				'book_id'=>$chapter['articleid'],
				'chapter_index'=>$chapter['chapterorder'],
				'chapter_title'=>$chapter['chaptername'],
				'content'=>htmlspecialchars_array($chapter['content']?$chapter['content']:"")
		);
		return $result;
	}

	
}
?>