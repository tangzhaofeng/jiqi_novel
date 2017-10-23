<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class qdusersModel extends Model{
  //login form
  public function main($params){
    
    $data = array();
    return $data;
  }
  /**
   * 获取用户的消费记录
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function userPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    
    /* 条件 */ 
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('q.uid',$_SESSION['jieqiUserId']));
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

    $q =  jieqi_dbprefix('system_users').' u LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p  ON u.uid=p.buyid AND p.payflag=1 LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON u.source=q.qd';

    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("u.uname,u.name,head,regdate,u.source,u.uid,count(distinct p.buyid) as payusers,round(sum(p.money)/100) as qdpay");
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

      $this->db->setCriteria();
      $this->db->criteria->add(new Criteria('q.uid',$_SESSION['jieqiUserId']));
      // 搜索
      if(isset($params['keyword']) && strlen($params['keyword']) > 0){
        $this->db->criteria->add(new Criteria('u.uname', trim($params['keyword']))); 
      }
      $q = jieqi_dbprefix('system_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON u.source=q.qd';
      $this->db->criteria->setTables($q);

      $this->setVar('totalcount', $this->db->getCount($this->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

    return $ar;
  }






} 
?>