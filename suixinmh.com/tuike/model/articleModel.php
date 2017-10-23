<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class articleModel extends Model{
  //login form
  public function main($params){
    
    $data = array();
    return $data;
  }
  /**
   * 获取书本列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function articleList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    global $jieqiSort;


    jieqi_getConfigs('article','sort');
         
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria( new Criteria('a.display', 0) );
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('a.articlename', '%'.trim($params['keyword']).'%', 'LIKE')); 
    }

    // 分类
    if(isset($params['siteid']) && $params['siteid'] > 0){
      $this->db->criteria->add( new Criteria('a.sortid', $params['siteid']) ); 
    }


    // 分类类型
    if(isset($params['siteidT']) && strlen($params['siteidT']) > 0){
      if( $params['siteidT'] ==1 ){ // 男频

        $this->db->criteria->add( new Criteria('a.sortid', 1, '>=') );
        $this->db->criteria->add( new Criteria('a.sortid', 99, '<=') );
      }elseif( $params['siteidT'] ==2 ){// 女频

        $this->db->criteria->add( new Criteria('a.sortid', 101, '>=') );
        $this->db->criteria->add( new Criteria('a.sortid', 199, '<=') );
      }elseif( $params['siteidT'] ==3 ){

        $this->db->criteria->add( new Criteria('a.stars', 5) );
      }

 
    }
    // define('JIEQI_DEBUG_MODE',1);


    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */

    if( $params['orderS'] === 'default' ){
      $this->db->criteria->setSort('stars DESC,size');
      $this->db->criteria->setOrder('desc');
    }else{
      $this->db->criteria->setSort($params['orderS']);
      $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));
    }


    $q = jieqi_dbprefix('article_article').' a ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("a.articleid,a.articlename,a.author,a.sortid,a.fullflag,a.size,a.postdate,a.chapters,a.arcaseurl,a.stars");
    $this->db->queryObjects();

     
    $ar=array();
    while($row=$this->db->getRow()){
        $row['typeName']=$jieqiSort['article'][$row['sortid']]?$jieqiSort['article'][$row['sortid']]['shortcaption']:'无分类';
        $row['img']=YOUYUEBOOK_URL.'/files/article/image'.jieqi_getsubdir($row['articleid']).'/'.$row['articleid'].'/'.$row['articleid'].'s.jpg';

        $row['sortidS']=$row['sortid']>=1 && $row['sortid']<=99?'男频':($row['sortid']>=101 && $row['sortid']<=199?'女频':'其它');

        $row['fullflagS']=$row['fullflag']==1?'完本':'连载';
        $row['arCaseUrl']=strlen($row['arcaseurl'])>8?$row['arcaseurl']:0;
        $row['starStr']=str_repeat('<i class="star"></i>',$row['stars']).str_repeat('<i></i>',5-$row['stars']);
        $ar[]=$row;
    }





    // var_dump( $this->db->sqllog('ret') );
    // die();


    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }

  /**
   * 获取不同的分类（频道）
   * @return [type] [description]
   */
  public function getCategory($category=array()){
    global $jieqiSort;
    if( count($category) ===0 )return false;
    jieqi_getConfigs('article','sort');
    foreach ( $category as $k => $v ) {
      $sort = array();
      $min_sortid = $v['minsortid'];
      $max_sortid = $v['maxsortid'];
      foreach ( $jieqiSort['article'] as $kk => $vv ) {
        if($kk >= $min_sortid && $kk <= $max_sortid){
          $sort[] = array('siteid'=>$kk,'name'=>$vv['caption']);
        }
      }
      $category[$k]['sort']=$sort;
    }
    return $category;
  }





  /**
   * 获取书本列表+推广链接
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function articleTg($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    global $jieqiSort;


    jieqi_getConfigs('article','sort');
         
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria();
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('a.articlename', trim($params['keyword']))); 
    }

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));

    $getTgUrl=false;
    if( isset($params['chapter']) && isset($params['qdselect']) && $params['chapter'] > 0 && strlen($params['qdselect']) > 0 ){
      $q = jieqi_dbprefix('article_article').' a LEFT JOIN '.jieqi_dbprefix('article_chapter').' ch ON ch.articleid=a.articleid AND ch.chapterorder='.intval($params['chapter']);
      $this->db->criteria->setTables($q);
      $this->db->criteria->setFields("a.articleid,a.articlename,a.author,a.sortid,a.fullflag,a.size,a.postdate,a.chapters,ch.chapterid");
      $getTgUrl=true;
    }else{
      $q = jieqi_dbprefix('article_article').' a ';
      $this->db->criteria->setTables($q);
      $this->db->criteria->setFields("a.articleid,a.articlename,a.author,a.sortid,a.fullflag,a.size,a.postdate,a.chapters");
    }
    $this->db->queryObjects();

    $ar=array();
    while($row=$this->db->getRow()){
        $row['typeName']=$jieqiSort['article'][$row['sortid']]?$jieqiSort['article'][$row['sortid']]['shortcaption']:'无分类';
        $row['img']=YOUYUEBOOK_URL.'/files/article/image/10/'.$row['articleid'].'/'.$row['articleid'].'s.jpg';

        if( $getTgUrl ){
          if($row['chapterid'] > 0){
            $row['qdUrl']=YOUYUEBOOK_URL_M.'/read/'.$row['articleid'].'/'.$row['chapterid'].'.html?qd='.$params['qdselect'];
          }else{
            $row['qdUrl']=YOUYUEBOOK_URL_M.'/read/'.$row['articleid'].'htm';
          }
        }else{
          $row['qdUrl']='请点击右上角选择渠道';
        }

        $row['fullflagS']=$row['fullflag']==1?'完本':'连载';
        $ar[]=$row;
    }


    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }




  /**
   * 获取图片素材列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function mattermList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
         
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria();
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('img_titile', trim($params['keyword']))); 
    }
    
    if( isset($params['cat_id']) && $params['cat_id'] > 0 ){
      $this->db->criteria->add(new Criteria('cat_id', intval($params['cat_id']))); 
    }

    $params['orderS']='matterm_id';
    $params['sort']=0;


    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));

    $q = jieqi_dbprefix('tuike_matterm');
    $this->db->criteria->setTables($q);
    $this->db->queryObjects();


    $ar=array();
    while($row=$this->db->getRow()){
      $ar[]=$row;
    }

    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }



  /**
   * 获取标题素材列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function mattertList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
         
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria();
    // 搜索
    if(strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('title', '%'.trim($params['keyword']).'%', 'LIKE'));
    }

    $params['orderS']='mattert_id';

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));

    $q = jieqi_dbprefix('tuike_mattert');
    $this->db->criteria->setTables($q);
    $this->db->queryObjects();

    $ar=array();
    while($row=$this->db->getRow()){
      $ar[]=$row;
    }

    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }

  /**
   * 第一个章节
   * @return [type] [description]
   */
  function readerFirse($params){

    $this->db->init('chapter','chapterid','article');
    $this->db->setCriteria(new Criteria('articleid',$params['aid']));
    $this->db->criteria->setSort('chapterid');
    $this->db->criteria->setOrder('asc');
    $this->db->criteria->setFields("chapterid");
    $this->db->queryObjects();
    $v=$this->db->getRow();
    if($v){
      return $v['chapterid'];
    }else{
      return 0;
    }
  }



  /**
   * 平台公告列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function notesList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    global $jieqiSort;

    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria(new Criteria('a.cat_id', 1));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('a.title', trim($params['keyword']))); 
    }

    $params['orderS']='a.is_new';
    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));

    $q = jieqi_dbprefix('tuike_article').' a ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("a.title,a.articleid,a.is_new,a.add_time");
    $this->db->queryObjects();

    $ar=array();
    while($row=$this->db->getRow()){
      $ar[]=$row;
    }

    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }

  /**
   * 公司详细
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function getNotes($params=array()){
    $this->db->init('article','articleid','tuike');
    return $this->db->get($params['id']);
  }


  /**
   * 方案风格样式
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function wenStyle($params=array()){
    global $jieqiConfigs,$jieqi_file_postfix;
    $this->addConfig('article','configs');
    $jieqiConfigs['article'] = $this->getConfig('article','configs');

    $params['styleN']=isset($params['styleN'])?trim($params['styleN']):'';
    $params['cid']=isset($params['chpater'])?intval($params['chpater']):0;
    $params['aid']=isset($params['aid'])?intval($params['aid']):0;
    $params['styleN']=isset($params['styleN'])?intval($params['styleN']):0;
    $params['chpaterN']=isset($params['chpaterN'])?intval($params['chpaterN'])-1:0;
    if($params['chpaterN'] === 0)$this->printfail('一键生成文案,最少要选择两个章节！');

    header('Content-Type:text/html;charset=gbk');


    switch($params['styleN']){
      case 1:
        $chapterLine='<section class=""><fieldset style="border: 0px; margin-top: 1em; margin-bottom: 2em;" class=""><section style="text-align: center; font-size: 1em; font-family: inherit; font-weight: inherit; text-decoration: inherit; color: rgb(255, 255, 255); border-color: rgb(249, 110, 87);"><section class="" style="width: 2em; height: 2em; margin-right: auto; margin-left: auto; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; background-color: rgb(249, 110, 87);"><section style="display: inline-block; padding-right: 0.5em; padding-left: 0.5em; font-size: 1em; line-height: 2; font-family: inherit;"><strong>1</strong></section></section><section style="margin-top: -1em; margin-bottom: 1em;"><section class="" style="border-top-width: 1px; border-top-style: solid; border-color: rgb(249, 110, 87); width: 35%; float: left;" data-width="35%"></section><section class="" style="border-top-width: 1px; border-top-style: solid; border-color: rgb(249, 110, 87); width: 35%; float: right;" data-width="35%"></section></section></section></fieldset></section>';
        $chapterBu='<section class="" style="max-width: 100%;overflow-y: hidden; overflow-x: hidden; color: rgb(62, 62, 62); line-height: 25.6000003814697px; white-space: normal; box-sizing: border-box !important; word-wrap: break-word !important;"><section class="" style="max-width: 100%; border: 0px none; box-sizing: border-box !important; word-wrap: break-word !important;"><section style="margin: auto; max-width: 100%; display: inline-block; box-sizing: border-box !important; word-wrap: break-word !important;"><section style="max-width: 100%; width: 280px; box-sizing: border-box !important; word-wrap: break-word !important;"><section style="margin-top: 10px; margin-right: auto; margin-left: auto; padding: 5px 10px; max-width: 100%; border-top-left-radius: 0.3em; border-top-right-radius: 0.3em; border-bottom-right-radius: 0.3em; border-bottom-left-radius: 0.3em; border: 1px solid rgb(204, 204, 204); color: rgb(51, 51, 51); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(238, 238, 238);"><p>未完待续......后续故事更加精彩！后续全文可以点击下方的“<strong><span style="color: rgb(255, 76, 0);">阅读原文</span></strong>”先睹为快！</p></section><blockquote style="padding-left: 0px; max-width: 100%; border: 0px none; box-sizing: border-box !important; word-wrap: break-word !important;"><p style="margin: 11px auto 5px 8px; max-width: 100%; min-height: 1em; width: 0px; height: 0px; border-width: 20px; border-style: solid; border-color: rgb(255, 121, 0) transparent transparent; z-index: 1; box-sizing: border-box !important; word-wrap: break-word !important;"><br style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"></p><p style="margin: -44px auto 5px 8px; max-width: 100%; min-height: 1em; width: 0px; height: 0px; border-width: 20px; border-style: solid; border-color: rgb(221, 221, 221) transparent transparent; z-index: 2; box-sizing: border-box !important; word-wrap: break-word !important;"><br style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"></p><p style="margin: -44px auto 5px 8px; max-width: 100%; min-height: 1em; width: 0px; height: 0px; border-width: 20px; border-style: solid; border-color: rgb(255, 121, 0) transparent transparent; z-index: 3; box-sizing: border-box !important; word-wrap: break-word !important;"><br style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"></p><p style="margin: -44px auto 5px 8px; max-width: 100%; min-height: 1em; width: 0px; height: 0px; border-width: 20px; border-style: solid; border-color: rgb(221, 221, 221) transparent transparent; z-index: 4; box-sizing: border-box !important; word-wrap: break-word !important;"><br style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"></p><p style="margin: -36px auto 5px 16px; max-width: 100%; min-height: 1em; width: 0px; height: 0px; border-width: 12px; border-style: solid; border-color: rgb(255, 121, 0) transparent transparent; z-index: 5; box-sizing: border-box !important; word-wrap: break-word !important;"><br style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"></p><p style="margin: -21px auto -32px 23px; max-width: 100%; min-height: 1em; width: 0px; height: 0px; border-width: 5px; border-style: solid; border-color: rgb(221, 221, 221) transparent transparent; z-index: 6; box-sizing: border-box !important; word-wrap: break-word !important;"><br></p></blockquote></section></section></section></section>';
        break;
      case 2:
        $chapterLine='<section class="" style="box-sizing: border-box;"><p style="text-align: center; white-space: normal;"><img data-ratio="1" src="'. JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/img/wen_t_2.gif" data-w="256" style="width: 32px !important; height: auto !important; visibility: visible !important;" title="分割线" _width="32px"  data-fail="0"></p></section>';
        $chapterBu='<section class="" data-tools="135编辑器" data-id="101" style="box-sizing: border-box;"><section class="" style="   box-sizing: border-box; " data-id="47131"><section class="" style="margin: 10px auto; box-sizing: border-box;"><section style="line-height: 10px; color: inherit; border: 1px solid rgb(157, 180, 194); display: inline-block; width: 100%; box-sizing: border-box;" data-width="100%"><section style="color: inherit; height: 8px; margin-top: -8px; margin-bottom: -8px; box-sizing: border-box;"><section style="margin-right: auto; margin-bottom: -2px; margin-left: -4px; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; line-height: 1; box-sizing: border-box; text-decoration: inherit; border-color: rgb(157, 180, 194); display: inline-block; height: 8px; width: 8px; color: rgb(255, 255, 255); background-color: rgb(157, 180, 194);"></section><section style="margin: 4px -4px -4px; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; line-height: 1; box-sizing: border-box; text-decoration: inherit; border-color: rgb(157, 180, 194); display: inline-block; height: 8px; width: 8px; color: rgb(255, 255, 255); float: right; background-color: rgb(157, 180, 194);"></section><section class="" data-style="text-indent: 2em;" style="padding: 5px; line-height: 2em; color: rgb(62, 62, 62); font-size: 14px; margin: 10px; box-sizing: border-box;"><p style="white-space: normal;"><span style="font-family: 微软雅黑; color: rgb(127, 127, 127);">未完待续……</span></p><p style="white-space: normal;"><span style="font-family: 微软雅黑; color: rgb(127, 127, 127);">后续故事将更加精彩！由于篇幅限制，本次只能连载到这里，后续全文可以点击左下角的<span style="font-family: 微软雅黑; color: rgb(255, 41, 65); font-size: 16px;">“阅读原文”</span>先睹为快！</span></p></section><section style="margin-right: auto; margin-bottom: -2px; margin-left: -4px; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; line-height: 1; box-sizing: border-box; text-decoration: inherit; border-color: rgb(157, 180, 194); display: inline-block; height: 8px; width: 8px; color: rgb(255, 255, 255); background-color: rgb(157, 180, 194);"></section><section style="margin: 6px -4px -4px; border-top-left-radius: 100%; border-top-right-radius: 100%; border-bottom-right-radius: 100%; border-bottom-left-radius: 100%; line-height: 1; box-sizing: border-box; text-decoration: inherit; border-color: rgb(157, 180, 194); display: inline-block; height: 8px; width: 8px; color: rgb(255, 255, 255); float: right; background-color: rgb(157, 180, 194);"></section></section></section></section></section><p style="text-align: center; white-space: normal;"><img class="" data-ratio="0.13125" data-type="gif" data-w="640" style="line-height: 1.6; text-indent: 24px; white-space: pre-wrap; display: inline; width: auto !important; height: auto !important; visibility: visible !important;" title="粉兔子猛戳阅读原文" src="'. JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/img/wen_t_2_2.gif" data-fail="0"><br></p></section>';
        break;
      case 3:
        $chapterLine='<section label="" style="width:100%; margin:1em auto; text-align: center;" donone="shifuMouseDownPayStyle("shifu_t_046")"><section class="" style="width: 0; height: 0; border-left:9px solid transparent; border-right: 9px solid transparent; border-bottom: 15px solid #82c1ea; display: inline-block; "></section><section class="" style="width: 0; height: 0; border-left:9px solid transparent; border-right: 9px solid transparent; border-top: 15px solid #fedb86; display: inline-block; margin-left: -6px; "></section><section class="" style="width: 0; height: 0; border-left:9px solid transparent; border-right: 9px solid transparent; border-bottom: 15px solid #85cbbf; display: inline-block; margin-left: -6px; "></section><section class="" style="width: 0; height: 0; border-left:9px solid transparent; border-right: 9px solid transparent; border-top: 15px solid #82c1ea; display: inline-block; margin-left: -6px; "></section><section class="" style="width: 0; height: 0; border-left:9px solid transparent; border-right: 9px solid transparent; border-bottom: 15px solid #fac285; display: inline-block; margin-left: -6px; "></section><section class="" style="width: 0; height: 0; border-left:9px solid transparent; border-right: 9px solid transparent; border-top: 15px solid #fea097; display: inline-block; margin-left: -6px; "></section></section>';
        $chapterBu='<table data-sort="sortDisabled" width="670"><tbody style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><tr style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><td valign="top" colspan="5" style="word-break: break-all; border-color: rgb(255, 41, 65); max-width: 100%; word-wrap: break-word !important; box-sizing: border-box !important;"><p style="margin-top: 5px; max-width: 100%; min-height: 1em; line-height: 1.5em; box-sizing: border-box !important; word-wrap: break-word !important;"><span style="max-width: 100%; font-family: 微软雅黑; color: rgb(136, 136, 136); letter-spacing: 0pt; font-size: 14px; box-sizing: border-box !important; word-wrap: break-word !important;">未完待续……</span></p><p style="margin-top: 5px; max-width: 100%; min-height: 1em; line-height: 1.5em; box-sizing: border-box !important; word-wrap: break-word !important;"><span style="max-width: 100%; font-family: 微软雅黑; color: rgb(136, 136, 136); letter-spacing: 0pt; font-size: 14px; box-sizing: border-box !important; word-wrap: break-word !important;">后续故事将更加精彩！由于篇幅限制，本次只能连载到这里，后续全文可以点击左下角的“阅读原文”先睹为快！</span></p></td></tr></tbody></table><p style="max-width: 100%; min-height: 1em; color: rgb(62, 62, 62); line-height: 25.6px; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);"><img style="white-space: normal; line-height: 24px; box-sizing: border-box !important; word-wrap: break-word !important; visibility: visible !important; width: auto !important; height: auto !important;" _width="auto" src="'. JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/img/wen_t_3_1.gif" data-fail="0"></p>';
        break;
      case 4:
        $chapterLine='<section class="" data-tools="135编辑器" data-id="86267" style="box-sizing: border-box;"><section style="margin-right: auto; margin-left: auto; width: 68%; box-sizing: border-box;" class="" data-width="68%"><section style="clear: both; box-sizing: border-box; color: inherit;"><section style="color: inherit; float: right; width: 11px; height: 11px !important; box-sizing: border-box; background-color: rgb(248, 122, 122);"></section><section style="color: inherit; float: left; width: 11px; height: 11px !important; box-sizing: border-box; background-color: rgb(248, 122, 122);"></section></section><section style="padding-top: 10px; color: inherit; border-color: rgb(248, 122, 122); box-sizing: border-box;"><section style="color: rgb(192, 0, 0); float: right; width: 12px; margin-right: -1px; height: 11px !important; box-sizing: border-box; background-color: rgb(252, 195, 195);" data-bgless="lighten" data-bglessp="15"></section><section style="color: rgb(192, 0, 0); float: left; width: 12px; margin-left: -1px; height: 11px !important; box-sizing: border-box; background-color: rgb(252, 195, 195);" data-bgless="lighten" data-bglessp="15"></section></section><section style="padding-top: 11px; color: inherit; border-color: rgb(248, 122, 122); box-sizing: border-box;"><section style="color: inherit; float: right; width: 12px; margin-right: -1px; margin-top: -1px; height: 11px !important; box-sizing: border-box; background-color: rgb(248, 122, 122);"></section><section style="color: inherit; float: left; width: 12px; margin-left: -1px; margin-top: -1px; height: 11px !important; box-sizing: border-box; background-color: rgb(248, 122, 122);"></section></section></section><section style="margin-top: -1em; text-align: center; padding-left: 20px; padding-right: 20px; box-sizing: border-box;" class="" data-brushtype="text"><span style="text-shadow: #D99694 2px 2px 10px; color: #953734;"><strong>1</strong></span></section></section>';
        $chapterBu='<p style="white-space: normal; text-align: center;"><img class="" data-ratio="0.075" data-type="jpeg" data-w="400" style="display: inline; width: 320px !important; height: auto !important; visibility: visible !important;" title="彩色石头分割线" _width="320px" src="'. JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/img/wen_t_4_1.gif" data-fail="0">&nbsp;</p><section data-role="paragraph" class="" style="box-sizing: border-box;"><p style="white-space: normal;"><strong style="color: rgb(62, 62, 62); line-height: 1.6; white-space: pre-wrap; font-family: "Helvetica Neue", Helvetica, "Hiragino Sans GB", "Microsoft YaHei", Arial, sans-serif; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;">微信篇幅有限</strong><br></p></section><p style="max-width: 100%; min-height: 1em; color: rgb(62, 62, 62); font-size: 16px; line-height: 25.6px; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);"><strong style="max-width: 100%; line-height: 1.6; box-sizing: border-box !important; word-wrap: break-word !important;">更多高潮内容请点击下方【<span style="max-width: 100%; color: rgb(255, 41, 65); box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 251, 0);">阅读原文</span>】</strong></p><p style="max-width: 100%; min-height: 1em; color: rgb(62, 62, 62); font-size: 16px; line-height: 25.6px; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 255, 255);"><span style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important; background-color: rgb(255, 251, 0);"><strong style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><strong style="max-width: 100%; color: rgb(255, 41, 65); font-size: 20px; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;">↓↓↓<strong style="max-width: 100%; line-height: 25.6px; box-sizing: border-box !important; word-wrap: break-word !important;"><strong style="max-width: 100%; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;">↓</strong></strong><strong style="max-width: 100%; line-height: 25.6px; box-sizing: border-box !important; word-wrap: break-word !important;"><strong style="max-width: 100%; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;">↓</strong></strong><strong style="max-width: 100%; line-height: 25.6px; box-sizing: border-box !important; word-wrap: break-word !important;"><strong style="max-width: 100%; line-height: 32px; box-sizing: border-box !important; word-wrap: break-word !important;">↓</strong></strong></strong></strong></span></p>';
        break;
      case 5:
         $chapterLine='<section class="" data-tools="135编辑器" data-id="87458" style=""><section style="text-align: center;"><section style="display: inline-block;"><section style="float: right; width: 40px; -webkit-transform: rotate(0deg);"><img src="'. JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/img/wen_t_1.gif" data-width="100%" class="" data-type="other" data-ratio="1.3388429752066116" data-w="121" style="display: block; visibility: visible !important; width: 40px !important; height: auto !important;" _width="40px" data-fail="0"></section><section style="margin-top: 20px; margin-right: -16px; padding-right: 25px; padding-left: 15px; box-sizing: border-box; float: left; line-height: 40px; border-top-left-radius: 10px; border-top-right-radius: 10px; border-bottom-right-radius: 10px; border-bottom-left-radius: 10px; border: 1px solid rgb(52, 157, 117);"><section class="" data-brushtype="text" style="color: rgb(55, 154, 55);"><strong>1</strong></section></section></section></section></section>';
         $chapterBu='<table data-sort="sortDisabled" width="670" style=""><tbody style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><tr class="" style="max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><td valign="top" colspan="5" style="border-color: rgb(255, 41, 65); word-break: break-all; max-width: 100%; word-wrap: break-word !important; box-sizing: border-box !important;"><p style="margin-top: 5px; min-height: 1em; line-height: 1.5em; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><span style="line-height: 1.5em; max-width: 100%; font-family: 微软雅黑; color: rgb(136, 136, 136); letter-spacing: 0pt; font-size: 14px; box-sizing: border-box !important; word-wrap: break-word !important;">未完待续……</span></p><p style="margin-top: 5px; min-height: 1em; line-height: 1.5em; max-width: 100%; box-sizing: border-box !important; word-wrap: break-word !important;"><span style="line-height: 1.5em; max-width: 100%; font-family: 微软雅黑; color: rgb(136, 136, 136); letter-spacing: 0pt; font-size: 14px; box-sizing: border-box !important; word-wrap: break-word !important;">后续故事将更加精彩！由于篇幅限制，本次只能连载到这里，后续全文可以点击左下角的“<span style="line-height: 1.5em; max-width: 100%; color: rgb(255, 41, 65); letter-spacing: 0pt;"><strong style="">阅读原文</strong></span>”先睹为快！</span></p></td></tr></tbody></table><p style="min-height: 1em; line-height: 25.6px; max-width: 100%; color: rgb(62, 62, 62); font-family: "Hiragino Sans GB", "Microsoft YaHei", Arial, sans-serif; widows: 1; background-color: rgb(255, 255, 255); box-sizing: border-box !important; word-wrap: break-word !important;"><img data-type="gif" data-ratio="0.12083333333333333" data-w="720" style="white-space: normal; line-height: 24px; box-sizing: border-box !important; word-wrap: break-word !important; visibility: visible !important; width: auto !important; height: auto !important;" _width="auto" src="'. JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/img/wen_t_5_2.gif" data-fail="0"></p>';
         break;
      default:
        $this->printfail('不存在该方案！');

    }


    $txtdir=jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article').jieqi_getsubdir($params['aid']).'/'.$params['aid'].'/';



    $data=$this->model('qdlist')->freeChapter($params['aid'],$params['chpaterN']);
    $html='';

    $p1='<p style=" text-indent:32px;text-autospace:ideograph-numeric;text-autospace:ideograph-other;text-align:justify;line-height:150% "><span style=";font-size:16px;font-family:微软雅黑">';
    $p2='</span></p>';
    $line=$p1.'&nbsp;'.$p2;
   
    foreach($data as $v){

      $str="\r\n".file_get_contents($txtdir.$v['id'].'.txt')."\r\n";
      $str=str_replace('  ','',$str);
      // $str=str_replace('　','',$str);
      $str=preg_replace('/[\r\n]([ \s]+)?([^\r\n ]+)[\r\n]/i',$p1.'$2'.$p2.$line, $str);
      $str=str_replace("\n",'',$str);
      $str=str_replace("\r",'',$str);
      $str=str_replace('<strong>1</strong>','<strong>'.$v['index'].'</strong>',$chapterLine).$str;
      $html.=$str;

    }
    $html.=$chapterBu;
    echo $html;
    die();


  }





} 
?>