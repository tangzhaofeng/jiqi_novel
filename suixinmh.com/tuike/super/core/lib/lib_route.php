<?php
final class Route extends JieqiObject
{
  public $url_query;
  public $url_type;
  public $route_url = array();
  public function __construct() 
  {
  }
  public function setUrlType($url_type = 2) 
  {
    if ($url_type > 0 && $url_type < 3){
      $this->url_type = $url_type;
    }else{
      trigger_error("指定的URL模式不存在！");
    }
  }
  public function getUrlArray() 
  {
    $this->makeUrl();
    $this->paramsHandle();
    return $this->route_url;
  }
  
  public function paramsHandle() {
    GLOBAL $G_FILED_Y;
    $G_FILED_Y=array(

      'tkuid'=>'u.uid',
      'tkunn'=>'u.uname',
      'tkpq'=>'u.p_qq',
      'tktyp'=>'u.groupid',
      'tkman'=>'u1.uid',
      'tkshtk'=>'u.is_tuike',
      'tknot'=>'u.notes',

      'qid'=>'q.id',
      'qmun'=>'q.qd',
      'qn'=>'q.name',
      'qch'=>'q.fee',
      'qtime'=>'q.pdate',

      'pbtime'=>'p.rettime',
      'psou'=>'p.source',
      'pid'=>'p.payid',
      'pbn'=>'p.buyname',
      'pmon'=>'p.money',
      'pdate'=>'p.date',
      'ptype'=>'p.paytype',
      'pinfo'=>'p.info',
      'pord'=>'p.ordernumber',

      
      'rmoon'=>'almoney',



      'manqdmain'=>'qid',
      'manqdqdpaylist'=>'pid',
      
      'manusermain'=>'tkuid',
      'manusertkulist'=>'tkuid',
      'manuserpaylist'=>'pbtime',
      'manuserqdlist'=>'qid',
      'manuserpaydaylist'=>'pdate',
      'manuserpaysettle'=>'pid',


      'paylogmain'=>'pdate',
      'manusersmain'=>'tkuid',
      'manusermoon'=>'rmoon',

      );
    $arSort=array(
      'manqdmain'=>'DESC',
      'manqdqdpaylist'=>'DESC',
      'manusertkulist'=>'DESC',
      'manuserpaylist'=>'DESC',
      'manuserqdlist'=>'DESC',
      'manuserpaysettle'=>'DESC',

      'manusermain'=>'DESC',
      
      'manuserpaydaylist'=>'DESC',
      'paylogmain'=>'DESC',
      );


    if( isset($_REQUEST['ajax_request']) && $_REQUEST['ajax_request'] )$_REQUEST['ajax_gets']='jieqi_contents';

    $conMet=$this->route_url['controller'].(isset($this->route_url['method'])?$this->route_url['method']:'main');
    $params=$this->route_url['params'];
    $params['order']=isset($params['order']) && $G_FILED_Y[$params['order']]?$params['order']:$G_FILED_Y[$conMet];
    $params['orderS']=$G_FILED_Y[$params['order']];
    
    $params['sort']=!isset($params['sort']) && isset($arSort[$conMet])?$arSort[$conMet]:$params['sort'];
    $params['sort']=isset($params['sort']) && $params['sort']==='ASC'?'ASC':'DESC';
    $params['limit']=!isset($params['limit'])||!is_numeric($params['limit'])?10:intval($params['limit']);
    $params['pageShow']=true;
    $_REQUEST=array_merge($_REQUEST,$params);
    $this->route_url['params']=$params;
  }

  public function makeUrl() 
  {
    switch ($this->url_type) 
    {
    case 1:
      $this->querytToArray();
      break;
    case 2:
      $this->pathinfoToArray();
      break;
    }
  }
  public function querytToArray() 
  {
    $array = $tmp = array();
    $array = $this->getRequest();
    if (count($array) > 0) 
    {
      if (isset($array['app'])) 
      {
        $this->route_url['app'] = $array['app'];
        unset($array['app']);
      }
      if (isset($array['controller'])) 
      {
        $this->route_url['controller'] = htmlspecialchars(str_replace(array('\/', '\'', '"', '.', 'http:', 'ftp:'), '', $array['controller']));
        unset($array['controller']);
      }
      if (isset($array['method'])) 
      {
        $this->route_url['method'] = htmlspecialchars(str_replace(array('\/', '\'', '"', '.', 'http:', 'ftp:'), '', $array['method']));
        unset($array['method']);
      }
    } 
    else
    {
      $this->route_url = array();
    }
    if (count($array) > 0) 
    {
      $this->route_url['params'] = $array;
    }
  }
  public function pathinfoToArray() 
  {
    global $jieqiUrl;
    $array = array();
    $array = $this->getRequest();

    if( isset( $_SERVER["REQUEST_URI"] ) ){

      jieqi_getconfigs(JIEQI_MODULE_NAME, 'url', 'jieqiUrl');
      $requestUrl=$_SERVER["REQUEST_URI"];
      $requestUrl=str_replace('/'.JIEQI_MODULE_NAME.'/','/',$requestUrl);

      $requestUrl=substr($requestUrl,1);

      $pos=strpos( $requestUrl,'?');
      if($pos !== false )$requestUrl=substr($requestUrl,0,$pos);
      $pos=stripos( $requestUrl,'.html');
      if($pos !== false )$requestUrl=substr($requestUrl,0,$pos);
      $pos=stripos( $requestUrl,'.htm');
      if($pos !== false )$requestUrl=substr($requestUrl,0,$pos);

      $urlAr=explode('/',$requestUrl);


      // 这些是没有写入规则的...
      if( $urlAr['0'] === 'book' )$urlAr['0']='articleinfo';
      if( $urlAr['0'] === 'read' )$urlAr['0']='reader';
      if( $urlAr['0'] === 'chapter' )$urlAr['0']='catalog';

      if( isset($jieqiUrl[JIEQI_MODULE_NAME][$urlAr['0'].'_main']) ){
        $urlR=$jieqiUrl[JIEQI_MODULE_NAME][$urlAr['0'].'_main'];
        $array['controller']=$_REQUEST['controller']=$urlR['controller'];
        if( strlen($urlR['method']) > 1 )$array['method']=$_REQUEST['method']=$urlR['method'];
        if( strlen($urlR['params']) > 2 ){

          // 书库新增的规则
          if( $urlAr['0'] === 'shuku' ){
            $urlAr=explode('_',$urlAr['1']);
          }else{
            array_splice($urlAr,0,1);
          }

          // 书签新增的规则
          if( in_array($urlAr['0'],array('bookcase','bcView','comic','guoqing')) ){
            $array['method']=$urlAr['0'];
          }else{
            $parAr=explode('&',$urlR['params']);
            foreach($urlAr as $k=>$v){
              if(empty($v))continue;
              $vv=substr($parAr[$k],0,stripos( $parAr[$k],'='));
              $array[$vv]=$_REQUEST[$vv]=$v;
            }
          }

        }
      }
    }   



    if (count($array) > 0){
      if (isset($array['app'])){
        $this->route_url['app'] = $array['app'];
        unset($array['app']);
      }
      if (isset($array['controller'])){
        $this->route_url['controller'] = htmlspecialchars(str_replace(array('\/', '\'', '"', '.', 'http:', 'ftp:'), '', $array['controller']));
        unset($array['controller']);
      }
      if (isset($array['method'])){
        $this->route_url['method'] = htmlspecialchars(str_replace(array('\/', '\'', '"', '.', 'http:', 'ftp:'), '', $array['method']));
        unset($array['method']);
      }
    }else{
      $this->route_url = array();
    }


    if (count($array) > 0){
      $this->route_url['params'] = $array;
    }

  }
}
