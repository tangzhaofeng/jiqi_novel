<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class qdlistModel extends Model{
  //login form
   public function main($params){
    
    $data = array();
    return $data;
  }
  /**
   * 渠道列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
   public function qdlist($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('uid',$params['uid']));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('name', trim($params['keyword']))); 
    }
    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder($params['sort']);
    $q =  jieqi_dbprefix('system_qdlist').' q';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("*");

    $this->db->queryObjects();

    $qdAr=array();
    $qdIn='';
    while($row=$this->db->getRow()){
      $qdIn.=$qdIn===''?'\''.$row['qd'].'\'':',\''.$row['qd'].'\'';
      $row['arInfo']='';
      if(strlen($row['params']) > 4){
        $temAr=explode(',',$row['params']);
        $sql='SELECT a.articlename,c.chaptername FROM '.jieqi_dbprefix('article_article').' a LEFT JOIN '.jieqi_dbprefix('article_chapter').
        ' c ON a.articleid=c.articleid AND c.chapterid='.$temAr['1'].' WHERE a.articleid='.$temAr['0'].' limit 1 ';
        $temAr=$this->db->getRow( $this->db->query($sql) );
        if( $temAr ) $row['arInfo']=$temAr['articlename'].'('.$temAr['chaptername'].')';
      }
      $qdAr[$row['qd']]=$row;
    }
    // pay
    $this->db->init('paylog','payid','pay');
    $this->db->setCriteria(new Criteria('source', '('.$qdIn.')' , "in"));
    $this->db->criteria->add(new Criteria('payflag', 1));
    $this->db->criteria->setFields("source,count(*) as payusers,round(sum(money)/100,2) as qdpay");
    $this->db->criteria->setGroupby("source");
    $this->db->criteria->setSort("qdpay");
    $this->db->criteria->setOrder("DESC");
    $res=$this->db->queryObjects();
    while($row=$this->db->getRow()){
      if( isset($qdAr[$row['source']]) ){
        $qdAr[$row['source']]['qdpay']=$row['qdpay'];
        $qdAr[$row['source']]['payusers']=$row['payusers'];
      }
    }
    // regi
    $this->db->init('users','uid','system');
    $this->db->setCriteria(new Criteria('source', '('.$qdIn.')' , "in"));
    $this->db->criteria->setFields("source,count(*) as qdreg");
    $this->db->criteria->setGroupby("source");
    $res=$this->db->queryObjects();
    while($row=$this->db->getRow()){
      if( isset($qdAr[$row['source']]) ){
        $qdAr[$row['source']]['qdreg']=$row['qdreg'];
      }
    }
    // click
    $this->db->init('qddata','id','system');
    $this->db->setCriteria(new Criteria('qd', '('.$qdIn.')' , "in"));
    $this->db->criteria->setFields("sum(click) as qdclick,sum(pv) as qdpv,qd");
    $this->db->criteria->setGroupby("qd");
    $this->db->queryObjects();
    while($row=$this->db->getRow()){
      if( isset($qdAr[$row['qd']]) ){
        $qdAr[$row['qd']]['qdclick']=$row['qdclick'];
        $qdAr[$row['qd']]['qdpv']=$row['qdpv'];
      }
    }
    // 重新处理数据 
    $ar=array();
    foreach($qdAr as $v){
      $v['qdclick']=isset($v['qdclick'])?$v['qdclick']:0;
      $v['qdpv']=isset($v['qdpv'])?$v['qdpv']:0;
      $v['qdreg']=isset($v['qdreg'])?$v['qdreg']:0;
      $v['qdpay']=isset($v['qdpay'])?$v['qdpay']:0;
      $v['payusers']=isset($v['payusers'])?$v['payusers']:0;
      $v['hb']=intval($v['fee'])===0?$v['qdpay']:number_format(($v['qdpay']/$v['fee'])*100,2,'.','');
      list($aid,$cid)=explode(',',$v['params']);
      $v['qdUrl']=YOUYUEBOOK_URL_M.'/read/'.$aid.'/'.$cid.'.html?qd='.$v['qd'];
      $ar[]=$v;
    }
    if ($params['pageShow']){
      /* 条件 */ 
      $this->db->setCriteria(new Criteria('uid',$params['uid']));
      // 搜索
      if(isset($params['keyword']) && strlen($params['keyword']) > 0){
        $this->db->criteria->add(new Criteria('name', trim($params['keyword']))); 
      }
      $q =  jieqi_dbprefix('system_qdlist');
      $this->db->criteria->setTables($q);
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }
  /**
   * 平台的所有渠道列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
   public function manQdList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    GLOBAL $G_FILED_Y;
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria(new Criteria('q.type',1));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('u.uname', trim($params['keyword']))); 
    }
    // 搜索
    if(isset($params['keywordq']) && strlen($params['keywordq']) > 0){
      $this->db->criteria->add(new Criteria('q.qd', trim($params['keywordq']))); 
    }


    // 筛选
    if( isset($params['screen_sort'],$G_FILED_Y[$params['screen_sort']]) ){
      $scr_sort=$G_FILED_Y[$params['screen_sort']];

      if( strpos($params['screen_sort'],'time') !== false && isset($params['screen_t1'],$params['screen_t2']) && strlen($params['screen_t1']) >3 ){

        if( $scr_sort !== 'q.pdate' ){
          $params['screen_t1']=strtotime($params['screen_t1']);
          $params['screen_t2']=strtotime($params['screen_t2']);
        }

        $this->db->criteria->add(new Criteria($scr_sort, $params['screen_t1'],'>=' )); 
        $this->db->criteria->add(new Criteria($scr_sort, $params['screen_t2'],'<=' )); 

      }elseif( isset($params['screen_text']) && strlen( $params['screen_text'] ) >= 1 ){

        if( $params['screen_sort']==='tkuid' && ( strlen( $params['screen_text'] ) === 11 || !is_numeric($params['screen_text']) ) ){
          $sql='SELECT uid FROM '.jieqi_dbprefix('tuike_users').' WHERE ( uname="'.trim($params['screen_text']).'" )';
          $params['screen_text']=$this->db->getField($this->db->query($sql));
        }

        if($params['screen_text'] !== false){

          $this->db->criteria->add(new Criteria($scr_sort, trim($params['screen_text']) )); 
        }
      }

    }



    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']));
    $q =  jieqi_dbprefix('system_qdlist').' q LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON q.uid=u.uid';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("q.*,u.uname");
    $this->criteria=$this->db->criteria;

    // define('JIEQI_DEBUG_MODE',1);

    $sqlres= $this->db->queryObjects();


    // var_dump( $this->db->sqllog('ret') );
    // die();
         


    $qdAr=array();
    $qdIn='';

    while($row=$this->db->getRow($sqlres)){
      $qdIn.=$qdIn===''?'\''.$row['qd'].'\'':',\''.$row['qd'].'\'';
      $row['arInfo']='';
      if(strlen($row['params']) > 4){
        $temAr=explode(',',$row['params']);
        $sql='SELECT a.articlename,c.chaptername FROM '.jieqi_dbprefix('article_article').' a LEFT JOIN '.jieqi_dbprefix('article_chapter').
        ' c ON a.articleid=c.articleid AND c.chapterid='.$temAr['1'].' WHERE a.articleid='.$temAr['0'].' limit 1 ';
        $temAr=$this->db->getRow( $this->db->query($sql) );
        if( $temAr ) $row['arInfo']=$temAr['articlename'].'('.$temAr['chaptername'].')';
      }
      $qdAr[$row['qd']]=$row;
    }
    // pay
    $this->db->init('paylog','payid','pay');
    $this->db->setCriteria(new Criteria('source', '('.$qdIn.')' , "in"));
    $this->db->criteria->add(new Criteria('payflag', 1));
    $this->db->criteria->setFields("source,count(*) as payusers,round(sum(money)/100,2) as qdpay");
    $this->db->criteria->setGroupby("source");
    $this->db->criteria->setSort("qdpay");
    $this->db->criteria->setOrder("DESC");
    $res=$this->db->queryObjects();
    while($row=$this->db->getRow()){
      if( isset($qdAr[$row['source']]) ){
        $qdAr[$row['source']]['qdpay']=$row['qdpay'];
        $qdAr[$row['source']]['payusers']=$row['payusers'];
      }
    }
    // regi
    $this->db->init('users','uid','system');
    $this->db->setCriteria(new Criteria('source', '('.$qdIn.')' , "in"));
    $this->db->criteria->setFields("source,count(*) as qdreg");
    $this->db->criteria->setGroupby("source");
    $res=$this->db->queryObjects();
    while($row=$this->db->getRow()){
      if( isset($qdAr[$row['source']]) ){
        $qdAr[$row['source']]['qdreg']=$row['qdreg'];
      }
    }
    // click
    $this->db->init('qddata','id','system');
    $this->db->setCriteria(new Criteria('qd', '('.$qdIn.')' , "in"));
    $this->db->criteria->setFields("sum(click) as qdclick,sum(pv) as qdpv,qd");
    $this->db->criteria->setGroupby("qd");
    $this->db->queryObjects();
    while($row=$this->db->getRow()){
      if( isset($qdAr[$row['qd']]) ){
        $qdAr[$row['qd']]['qdclick']=$row['qdclick'];
        $qdAr[$row['qd']]['qdpv']=$row['qdpv'];
      }
    }
    // 重新处理数据 
    $ar=array();
    foreach($qdAr as $v){
      $v['qdclick']=isset($v['qdclick'])?$v['qdclick']:0;
      $v['qdpv']=isset($v['qdpv'])?$v['qdpv']:0;
      $v['qdreg']=isset($v['qdreg'])?$v['qdreg']:0;
      $v['qdpay']=isset($v['qdpay'])?$v['qdpay']:0;
      $v['payusers']=isset($v['payusers'])?$v['payusers']:0;
      $v['hb']=intval($v['fee'])===0?$v['qdpay']:number_format(($v['qdpay']/$v['fee'])*100,2,'.','');
      list($aid,$cid)=explode(',',$v['params']);
      $v['qdUrl']=YOUYUEBOOK_URL_M.'/read/'.$aid.'/'.$cid.'.html?qd='.$v['qd'];
      $ar[]=$v;
    }
    if ($params['pageShow']){

      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }
  /**
   * 推客详细
   * @return [type] [description]
   */
  function qddetail($params=array()){
    $this->db->init('qdlist','id','system');
    return $this->db->get($params['qdId']);
  }
  /**
   * 云平台推客的每日统计
   * @return [type] [description]
   */
  function getManTkListAll(){
    global $cache_redis;
    if( $cache_redis->isExists(JIEQI_REDIS_FIX.'ManTkListAll'.$_SESSION['jieqiUserId']) ){
      return unserialize( $cache_redis->get(JIEQI_REDIS_FIX.'ManTkListAll'.$_SESSION['jieqiUserId']) );
    }
     
    $data=array();
    // 循环30记录
    $dateS=date('Y-m-d',JIEQI_NOW_TIME);
    for($i=1;$i<=30;$i++){
      $ar=array('tkMoneyG'=>0,'tkMoneyT'=>0,'allMoney'=>0);
      $dateS=date('Y-m-d',strtotime($dateS.' -1 day'));
      $t1=strtotime($dateS);
      $t2=strtotime($dateS.' 23:59:59');
      $ar['dateS']=$dateS;
      // 推广、广告
      $q = jieqi_dbprefix('tuike_paylog').' p LEFT JOIN '.jieqi_dbprefix('tuike_users').' u ON p.uid=u.uid'; 
      $this->db->setCriteria(new Criteria('p.type',3,'!='));
      $this->db->criteria->add(new Criteria('p.date',$dateS));
      $this->db->criteria->setTables($q);
      $this->db->criteria->setFields("type,round(sum(money)) as money");
      $this->db->criteria->setGroupby("p.type");
      $this->db->queryObjects();
      while($row=$this->db->getRow()){
        if( $row['type'] === '2' ){
          $ar['tkMoneyG']=$row['money'];
        }elseif( $row['type'] === '1' ){
          $ar['tkMoneyT']=$row['money'];
        }
      }
      // 总的充值金额
      $q = jieqi_dbprefix('pay_paylog').' p LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.qd=p.source'; 
      $this->db->setCriteria(new Criteria('q.type',1));
      $this->db->criteria->add(new Criteria('p.rettime',$t1,'>='));
      $this->db->criteria->add(new Criteria('p.rettime',$t2,'<'));
      $this->db->criteria->add(new Criteria('p.payflag',1));
      $this->db->criteria->setTables($q);
      $this->db->criteria->setFields("round(sum(money/100),2) as money");
      $this->db->queryObjects();
      $row=$this->db->getRow();
      if($row && $row['money'])$ar['allMoney']=$row['money'];
      $data[]=$ar;
    }

    $time=86400-JIEQI_NOW_TIME+strtotime(date('Y-m-d',JIEQI_NOW_TIME));
    $cache_redis->set(JIEQI_REDIS_FIX.'ManTkListAll'.$_SESSION['jieqiUserId'],serialize($data),$time);
    return $data;
  }
  /**
   * 每日推客的收入统计
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function tuDayList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];


    /* 条件 */ 
    $this->db->setCriteria(new Criteria('p.type',3,'!='));
    $this->db->criteria->add(new Criteria('p.date', trim($params['date']))); 
    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('tuike_paylog').' p ON p.uid=u.uid ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("DISTINCT u.uid");   
    $this->criteria=$this->db->criteria;
    $inU='';
    $this->db->queryObjects();
    while($row=$this->db->getRow($sqlres)){
      $inU.=$inU===''?$row['uid']:','.$row['uid'];
    }

    if( $inU==='' )return array();

    /* 条件 */ 
    $this->db->setCriteria(new Criteria('p.type',3,'!='));
    $this->db->criteria->add(new Criteria('p.date', trim($params['date']))); 
    $this->db->criteria->add(new Criteria('u.uid', '('.$inU.')' , "in"));
    /* 排序 */
    $this->db->criteria->setSort('u.uid');
    $this->db->criteria->setOrder('desc');
    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('tuike_paylog').' p ON p.uid=u.uid ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("u.uid,u.uname,p.money,p.type,p.paymoney,p.payid");

    $this->db->queryObjects();
    
    $qdAr=array();
    $uAr=array();
    $i=0;
    while($row=$this->db->getRow()){
      $row['qdpay']=0;
      $row['tkMoneyG']=0;
      $row['tkMoneyT']=0;

      if( $row['type'] === '2' ){
        $row['tkMoneyG']=$row['money'];
        $row['qdpay']=$row['paymoney'];
      }elseif( $row['type'] === '1' ){
        $row['tkMoneyT']=$row['money'];
      }
      if( isset($uAr[$row['uid']]) ){
        if( $row['type'] === '2' ){
          $qdAr[$uAr[$row['uid']]['index']]['tkMoneyG']=$row['money'];
          $qdAr[$uAr[$row['uid']]['index']]['qdpay']=$row['paymoney'];
        }elseif( $row['type'] === '1' ){
          $qdAr[$uAr[$row['uid']]['index']]['tkMoneyT']=$row['money'];
        }
      }else{
        $uAr[$row['uid']]=array('index'=>$i);
        $qdAr[$i]=$row;
        $i++;
      }
    }

        

    if ($params['pageShow']){
     
      $sql='SELECT COUNT(DISTINCT u.uid) count FROM jieqi_tuike_users u LEFT JOIN jieqi_tuike_paylog p ON p.uid=u.uid WHERE (p.type != "3" AND p.date = "'.trim($params['date']).'")';
      $row=$this->db->getRow( $this->db->query( $sql ) );
      $this->setVar('totalcount', $row['count']);
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $qdAr;
  }


































  /**
   * 推客下的所有渠道
   * @return [type] [description]
   */
  function qdAll(){
    $this->db->init('qdlist','id','system');
    $this->db->setCriteria(new Criteria('uid',$_SESSION['jieqiUserId'] ));
    $this->db->criteria->setFields('qd,name,id');
    $this->db->queryObjects();
    $ar=array();
    while($row=$this->db->getRow()){
      $ar[]=$row;
    }
    return $ar;
  }








} 
?>