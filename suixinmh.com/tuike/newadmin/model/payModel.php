<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class payModel extends Model{
  //login form
   public function main($params){
    $data = array();
    return $data;
  }
 

 
  /**
   * 某个推客下的充值记录
   * @param  [type]  $uid    [description]
   * @param  integer $qd     [description]
   * @param  array   $params [description]
   * @return [type]          [description]
   */
  public function getPayList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];

    // 渠道id
    if( $params['qdId']>0 ){
      $this->db->init('qdlist','id','system');
      $qdAr=$this->db->get($params['qdId']);
      if( !$qdAr ){
      jump_fail('渠道号不正确！',geturl(JIEQI_MODULE_NAME,'qdlist'));
      }
    }

    /* 条件 */ 
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('p.payflag', 1));

    if( isset($params['t1'],$params['t2']) && $params['t1']>0 && $params['t2']>0 ){
      $this->db->criteria->add(new Criteria('p.rettime', strtotime($params['t1']) , ">="));
      $this->db->criteria->add(new Criteria('p.rettime', strtotime($params['t2'].' 23:59:59') , "<"));
    }

    if( isset($params['qd']) && $params['qd'] !== ''){
      $this->db->criteria->add(new Criteria('p.source', $params['qd']));
    }
    // 会员id
    if( isset($params['uid']) && $params['uid']>0 ){
      $this->db->criteria->add(new Criteria('q.uid', $params['uid']));
    }

    // 渠道号
    if( isset($qdAr) ){
      $this->db->criteria->add(new Criteria('p.source', $qdAr['qd']));
    }

    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('p.buyname', trim($params['keyword']))); 
    }

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);

    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']));

    $q = jieqi_dbprefix('pay_paylog').' p LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON p.source=q.qd';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('p.*');

    // define('JIEQI_DEBUG_MODE',1);

    $this->db->queryObjects();

    $ar=array();
    while($row=$this->db->getRow()){
      $row['money']=$row['money']/100;
      $ar[]=$row;
    }  

    // var_dump( $this->db->sqllog('ret') );
    // die();
    // 总充值数
    $totalMoney=0;
    if( count($ar) > 0 ){
      $sql = 'SELECT round(sum(p.money)/100,2) FROM '.$this->db->criteria->getTables().' '.$this->db->criteria->renderWhere();
      $totalMoney=$this->db->getField($this->db->query($sql));
    }
    $this->setVar('totalMoney', $totalMoney);

    if ($params['pageShow']) {
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }








/*------newRun---------------------------------------------------------------------------------------------------------------------------*/






  /**
   * 所有下级的广告充值记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function getPayListTk($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];

    /* 条件 */ 
    $this->db->setCriteria( new Criteria('p.payflag', 1) );
    $this->db->criteria->add(new Criteria('p.rettime', $params['t1'] , ">="));
    $this->db->criteria->add(new Criteria('p.rettime', $params['t2'] , "<"));
    $this->db->criteria->add(new Criteria('u.tkuid', $params['uid']));

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);

    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']));

    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON u.uid=q.uid LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('p.*');

    $this->db->queryObjects();
    $ar=array();
    while($row=$this->db->getRow()){
      $row['money']=$row['money']/100;
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
   * 提现结算详情
   * @param  [type] $payId [description]
   * @return [type]        [description]
   */
  function getPaylog($payId){
    $this->db->init('paylog','payid','tuike');
    $this->db->setCriteria(new Criteria('payid',$payId));
    $this->db->queryObjects();
    $paylog=$this->db->getRow();
    if(!$paylog)$this->printfail('查询不到该数据！');

    if( $paylog['type'] != 3 ){
      //该结算的总金额

      $paylog['t1']=strtotime($paylog['date']);
      $paylog['t2']=strtotime($paylog['date'].' 23:59:59');

    }
    return $paylog;
  }








} 
?>