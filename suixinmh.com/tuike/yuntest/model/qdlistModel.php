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
    $this->db->criteria->add(new Criteria('uid',$_SESSION['jieqiUserId']));
    // 搜索
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){
      $this->db->criteria->add(new Criteria('name', trim($params['keyword']))); 
    }

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']===1?'asc':'desc'));

    $q =  jieqi_dbprefix('system_qdlist');
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("*");
    $this->db->queryObjects();

    $qdAr=array();
    $qdIn='';
    while($row=$this->db->getRow()){
      $qdIn.=$qdIn===''?'\''.$row['qd'].'\'':',\''.$row['qd'].'\'';
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


    if ($params['pageShow']) {
      /* 条件 */ 
      $this->db->setCriteria(new Criteria('uid',$_SESSION['jieqiUserId']));

      // 搜索
      if(isset($params['keyword']) && strlen($params['keyword']) > 0){
        $this->db->criteria->add(new Criteria('name', trim($params['keyword']))); 
      }
      $q =  jieqi_dbprefix('system_qdlist');
      $this->db->criteria->setTables($q);

      $this->setVar('totalcount', $this->db->getCount($this->criteria));

      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }

     
    return $ar;
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



  /**
   * 渠道-修改渠道名
   * @return [type] [description]
   */
  function ajax_qd_n_m($params){

    $id=isset($params['field_i'])?intval($params['field_i']):0;
    $data=array();
    $data['name']=isset($params['field_v'])?trim($params['field_v']):'';

    $sql='SELECT count(*) FROM '.jieqi_dbprefix('system_qdlist').' WHERE ( id="'.$id.'" AND uid="'.$_SESSION['jieqiUserId'].'" )';
    $count=$this->db->getField($this->db->query($sql));
    if( $count<1 )$this->printfail('信息不正确！');

    $this->db->init('qdlist','id','system');
    $this->db->edit($id, $data);

    if( $this->db->getAffectedRows() > 0 ){
        $this->msgwin('修改成功！');
    }else{
      $this->printfail('修改失败！');
    }
  }



  /**
   * 渠道-删除
   * @return [type] [description]
   */
  function delete_qd_m($params){

    $id=isset($params['id'])?intval($params['id']):0;

    $sql='SELECT qd FROM '.jieqi_dbprefix('system_qdlist').' WHERE ( id="'.$id.'" AND uid="'.$_SESSION['jieqiUserId'].'" )';
    $qd=$this->db->getField($this->db->query($sql));
    if( !$qd )$this->printfail('信息不正确！');

    $sql='SELECT money FROM '.jieqi_dbprefix('pay_paylog').' WHERE ( payflag=1 AND source="'.$qd.'" ) LIMIT 1';
    $money=$this->db->getField($this->db->query($sql));

    if( $money && $money>0 )$this->printfail('有支付记录的渠道不能删除，请联系客户经理！');

    $this->db->init('qdlist','id','system');
    $this->db->delete($id);
    if( $this->db->getAffectedRows() > 0 ){
        $this->msgwin('删除成功！');
    }else{
      $this->printfail('删除失败！');
    }

  }



  /**
   * 添加渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function qdAdd($params=array()){

    $aid=intval($params['aid']);
    $cid=intval($params['chapterid']);
    $fee=intval($params['fee']);
    $fans=trim($params['fans']);
    $name=trim($params['name']);
    $pingtai=trim($params['pingtai']);
   
    $platAr=array('weixin'=>1, 'weibo'=>1, 'other'=>1);
    if(!isset($platAr[$pingtai]))$this->printfail("不存在该平台");
    if( $aid <=0 || $cid <= 0 )$this->printfail('文章不存在！');


    $this->db->init("qdlist", "id", "system");
    // 生成随机的 qd
    $this->db->setCriteria(new Criteria('uid',$_SESSION['jieqiUserId']));
    $count=$this->db->getCount()+1;

    if( strlen($_SESSION['jieqiUserId']) > 4 ){
      $qd='6'.$_SESSION['jieqiUserId'].'q';
    }else{
      $qd='6'.str_repeat(0,4-strlen($_SESSION['jieqiUserId'])).$_SESSION['jieqiUserId'];
    }
    if( strlen($count) > 4 ){
      $qd.=$count;
    }else{
      $qd.=str_repeat(0,4-strlen($count)).$count;
    }

    $runN=8;
    for($i=1;$i<=$runN;$i++){
      if( $this->qdCheck(array('qd'=>$qd),true) === 0 )break;  
      $count++;
      if( strlen($_SESSION['jieqiUserId']) > 4 ){
        $qd='6'.$_SESSION['jieqiUserId'].'q';
      }else{
        $qd='6'.str_repeat(0,4-strlen($_SESSION['jieqiUserId'])).$_SESSION['jieqiUserId'];
      }
      if( strlen($count) > 4 ){
        $qd.=$count;
      }else{
        $qd.=str_repeat(0,4-strlen($count)).$count;
      }
    }

    if( $i > $runN )$this->printfail('添加失败！');

    $data_arr=array(
      "qd"=>$qd,
      "uid"=>$_SESSION['jieqiUserId'],
      'url'=>'',
      'plat'=>$pingtai,
      'name'=>$name,
      'fee'=>$fee,
      'pdate'=>date('Y-m-d',JIEQI_NOW_TIME),
      'fans'=>$fans,
      'type'=>1,
      'params'=>$aid.','.$cid
    );

    $res = $this->db->add($data_arr);
    if ($res){
      die(json_encode(array(
        'url'=>$v['qdUrl']=YOUYUEBOOK_URL_M.'/read/'.$aid.'/'.$cid.'.html?qd='.$qd,
        'msg'=>'','status'=>"OK")));
    }else{
      $this->printfail("添加渠道失败");
    }

  }


  /**
   * 检查渠道号
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function qdCheck($params=array(),$return=false){

    $qd=isset($params['qd'])?trim($params['qd']):'';
    if($qd == ''){
      if(!$return){
        $this->printfail('请输入渠道号');
      }else{
        return 1;
      }
    }
    /* 原来渠道表 */
    $this->db->init("qdlist", "id", "system");
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('qd', $qd, "="));
    if ($this->db->getCount()>0){
      if(!$return){
        $this->printfail("渠道编号已经存在");
      }else{
        return 1;
      }
    }

    if(!$return){
      $this->msgwin('检查渠道号',"可以使用");
    }else{
      return 0;
    }
  }


  /**
   * 免费的章节列表
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function freeChapter($aid,$limitMax=50){

    $this->db->init('chapter','chapterid','article');
    $this->db->setCriteria(new Criteria('articleid',$aid));
    $this->db->criteria->add(new Criteria('isvip',0));
    $this->db->criteria->setFields('chapterid');
    $this->db->criteria->setSort('chapterorder');
    $this->db->criteria->setOrder('asc');
    $this->db->criteria->setLimit($limitMax);
    $this->db->queryObjects();

    $arr=array();
    $i=1;
    while($v=$this->db->getRow()){
      $arr[]=array('id'=>$v['chapterid'],'name'=>'第 '.$i.' 章','index'=>$i);
      $i++;
    }
    return $arr;
  }










} 
?>