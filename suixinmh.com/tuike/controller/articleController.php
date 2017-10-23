<?php
/**
 * 默认控制器
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class articleController extends Tuike_controller {
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();
    $this->assign('nav',3);
  } 
  /**
   * 图片管理
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function main($params = array()) {
    $data=array();
    $params['siteid']=isset($params['siteid'])?intval($params['siteid']):0;
    $params['siteidT']=isset($params['siteidT'])?intval($params['siteidT']):0;
    $orderAr=array(
      'default'=>'default',
      'articlename'=>'a.articlename',
      'author'=>'a.author',
      'sortid'=>'a.sortid',
      'fullflag'=>'a.fullflag',
      'size'=>'a.size',
      'chapters'=>'a.chapters',
      'postdate'=>'a.postdate'
      );

    $params['keyword']=isset($params['keyword'])?urldecode($params['keyword']):'';
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'default';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=sort='.$params['sort'];
    $SYS.='&order='.$params['order'].'&siteid='.$params['siteid'];
    if($params['siteidT']>0)$SYS.='&siteidT='.$params['siteidT'];
    if( $params['keyword'] != '' ) $SYS.='&keyword='.urlencode($params['keyword']);

    $dataObj=$this->model('article');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['articleList']=$dataObj->articleList($params);

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['imgDefault']=YOUYUEBOOK_URL.'/images/bookpackagedefault.jpg';
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'article','evalpage=0',$SYS));
    
    $data['getcategory']=$dataObj->getCategory(array(
        '1'=>array('name'=>'男频','minsortid'=>1,'maxsortid'=>99),
        '0'=>array('name'=>'女频','minsortid'=>101,'maxsortid'=>199)
      ));

    $data['siteid']=$params['siteid'];
    $this->display($data,'articleList');
  }

  /**
   * 图片生成推广链接
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function articleTg($params = array()) {
    $orderAr=array(
      'articleid'=>'a.articleid',
      'articlename'=>'a.articlename',
      'author'=>'a.author',
      'sortid'=>'a.sortid',
      'fullflag'=>'a.fullflag',
      'size'=>'a.size',
      'chapters'=>'a.chapters',
      'postdate'=>'a.postdate'
      );
    $params['order']=isset($params['order']) && $orderAr[$params['order']]?$params['order']:'articleid';
    $params['orderS']=$orderAr[$params['order']];
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=method=articleTg&sort='.$params['sort'];
    $SYS.='&order='.$params['order'];

    if(isset($params['chapter'])) $SYS.='&chapter='.$params['chapter'];
    if(isset($params['qdselect'])) $SYS.='&qdselect='.$params['qdselect'];


    $dataObj=$this->model('article');
    $params['limit']=10;
    $params['pageShow']=true;
    $data['articleList']=$dataObj->articleTg($params);

    $data['order']=$params['order'];
    $data['sort']=$params['sort'];
    $data['imgDefault']=YOUYUEBOOK_URL.'/images/bookpackagedefault.jpg';
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'article','evalpage=0',$SYS));

    $data['qdList']=$this->model('qdlist')->qdAll();

    $this->display($data,'articleTg');
  }



  /**
   * 图片素材列表
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function picture($params = array()) {
    $SYS='SYS=method=picture&sort='.$params['sort'].'&cat_id='.$params['cat_id'];

    $dataObj=$this->model('article');
    $params['limit']=18;
    $params['pageShow']=true;
    $data['articleList']=$dataObj->mattermList($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'article','evalpage=0',$SYS));

    $data['cat_id']=$params['cat_id'];
    $this->display($data,'article_picture');
  }


  /**
   * 标题素材列表
   * @param  array  $params [description]
   * @return [type]         [description]  encden url
   */
  public function caption($params = array()) {
    $params['keyword']=isset($params['keyword'])?urldecode($params['keyword']):'';
    $params['sort']=isset($params['sort']) && $params['sort']==1?1:0;
    $SYS='SYS=method=caption&sort='.$params['sort'];
    if( $params['keyword'] != '' ) $SYS.='&keyword='.urlencode($params['keyword']);

    $dataObj=$this->model('article');
    $params['limit']=14;
    $params['pageShow']=true;
    $data['articleList']=$dataObj->mattertList($params);
    $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'article','evalpage=0',$SYS));

    $this->display($data,'article_caption');
  }


  /**
   * 文章阅读
   * @param  array  $params [description]
   * @return [type]         [description]  encden url
   */
  public function reader($params = array()) {

    $params['aid']=isset($params['aid'])?intval($params['aid']):0;
    $params['cid']=isset($params['cid'])?intval($params['cid']):0;
    if( $params['aid'] === 0 )$this->printfail('不存在该文章！');
    if( $params['cid'] === 0 )$params['cid']=$this->model('article')->readerFirse($params);
    if( $params['cid'] === 0 )$this->printfail('不存在该文章！');
    $dataObj = $this->model('readerr', '3g');

    $data = $dataObj->reader($params['aid'], $params['cid'], -11,1);
    $data['article']['img']=YOUYUEBOOK_URL.'/files/article/image'.jieqi_getsubdir($data['article']['articleid']).'/'.$data['article']['articleid'].'/'.$data['article']['articleid'].'s.jpg';
    if( $data['chapter']['isvip'] === '1' )$this->printfail('不能阅读vip章节！');
    $data['baseUrl']=$this->geturl(JIEQI_MODULE_NAME,'article','SYS=method=reader&aid='.$data['article']['articleid']);
    
    $data['imgDefault']=YOUYUEBOOK_URL.'/images/bookpackagedefault.jpg';
    $this->display($data,'article_reader');
  }


  /**
   * 文章的异步请求
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function ajax($params=array()){
    switch($params['type']){
      case 'wenStyle':
        $this->model('article')->wenStyle($params);
        break;
      default:
        $this->printfail('不存在该类型');
    }
  }




}

?>