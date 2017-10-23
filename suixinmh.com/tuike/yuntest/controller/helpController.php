<?php
/**
 * 默认控制器
 * @author chengyuan  2014-8-6
 *
 */
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
class helpController extends Tuike_controller {
  public $theme_dir = false;
  public function __construct() { 
    parent::__construct();

    if( application::$DU_METHOD === 'notes' ){
      $this->assign('nav',8);
    }else{
      $this->assign('nav',7);
    }
  } 
  /**
   * 
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function main($params = array()) {

    $daa=array();
    $this->display($data,'help_main');
  }
  /**
   * 平台公告
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  public function notes($params = array()) {
    $params['id']=isset($params['id'])?intval($params['id']):0;

    $dataObj=$this->model('article');
    $daa=array();
    if( $params['id'] === 0 ){
      // $SYS='SYS=method=notes';
      // $params['limit']=10;
      // $params['pageShow']=true;
      // $data['list']=$dataObj->notesList($params);
      // $data['page']=$dataObj->getPage($this->getUrl(JIEQI_MODULE_NAME,'help','evalpage=0',$SYS));
      $this->display($data,'help_notes_list');
    }else{
      // $data['info']=$dataObj->getNotes($params);
      $this->display($data,'help_notes_info_'.$params['id']);
    }
  }




}

?>