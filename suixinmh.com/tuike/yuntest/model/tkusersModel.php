<?php 
/** 
 * 下级推客明细模块
 * @author      gaoli* @version     1.0 
 */ 
class tkusersModel extends Model{
  //login form
  public function main($params){
    
    $data = array();
    return $data;
  }
  /**
   * 获取推客的消费记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function ktUserPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('u.tkuid',$_SESSION['jieqiUserId']));
    $this->db->criteria->add(new Criteria('u.uid',$_SESSION['jieqiUserId'],'!='));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('u.uname', trim($params['keyword']))); 
    }

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));

    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '. jieqi_dbprefix('system_qdlist').' q ON u.uid=q.uid LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON q.qd=p.source AND p.payflag=1';

    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("u.uid,u.name,u.reg_time,count(distinct p.buyid) as payusers,round(sum(p.money)/100) as qdpay");
    $this->db->criteria->setGroupby("u.uid");
    $this->db->queryObjects();


    $ar=array();
    while($row=$this->db->getRow()){
      $row['qdpay']=isset($row['qdpay'])?$row['qdpay']:0;
      if(strlen($row['head']) < 6 ){
        $row['head']=YOUYUEBOOK_URL.'/images/noavatarl.jpg';
      }elseif(strpos($row['head'],'http://') === false){
        $row['head']=YOUYUEBOOK_URL.'/'.$row['head'];
      }
      $ar[]=$row;
    }


    if ($params['pageShow']) {

      $this->db->setCriteria(new Criteria('u.tkuid',$_SESSION['jieqiUserId']));
      $this->db->criteria->add(new Criteria('u.uid',$_SESSION['jieqiUserId'],'!='));
      // 搜索
      if(isset($params['keyword']) && strlen($params['keyword']) > 0){
        $this->db->criteria->add(new Criteria('u.name', trim($params['keyword']))); 
      }
      $q = jieqi_dbprefix('tuike_users').' u';
      $this->db->criteria->setTables($q);
      $this->setVar('totalcount', $this->db->getCount($this->criteria));


      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }



  /**
   * 获取某个推客的消费记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function ktUserPayOne($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    // $this->db->setCriteria();
    
    $this->db->setCriteria(new Criteria('p.payflag',1 ));
    $this->db->criteria->add(new Criteria('q.uid',$params['tkId'] ));

    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('p.buyname', trim($params['keyword']))); 
    }


    // define('JIEQI_DEBUG_MODE',1);


    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));
    /* 联表和执行 */
    $q = jieqi_dbprefix('pay_paylog').' p LEFT JOIN '. jieqi_dbprefix('system_qdlist').' q ON p.source=q.qd ';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("p.*,round(p.money/100) as money");
    $this->db->queryObjects();

    /* 处理数据 */
    $ar=array();
    while($row=$this->db->getRow()){
      $row['qdpay']=isset($row['qdpay'])?$row['qdpay']:0;
      if(strlen($row['head']) < 6 ){
        $row['head']=YOUYUEBOOK_URL.'/images/noavatarl.jpg';
      }elseif(strpos($row['head'],'http://') === false){
        $row['head']=YOUYUEBOOK_URL.'/'.$row['head'];
      }
      $ar[]=$row;
    }

    // var_dump( $this->db->sqllog('ret') );
    // die();



    /* 分页 */
    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }


  /**
   * 推客明细
   * @param  [type] $tkId [description]
   * @return [type]       [description]
   */
  function getTkInfo($tkId){

    $this->db->init('users','uid','tuike');
    $this->db->setCriteria(new Criteria('tkuid',$_SESSION['jieqiUserId']));
    $this->db->setCriteria(new Criteria('uid',$tkId));
    $this->db->criteria->setFields("name");
    $this->db->queryObjects();
    return $this->db->getRow();
  }







} 
?>