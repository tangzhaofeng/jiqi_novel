<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class qdlist_wbModel extends Model{
  //login form
   public function main($params){
    $data = array();
    return $data;
  }
  /**
   * 经理的推客的所有渠道列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
   public function manQdList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    GLOBAL $G_FILED_Y;
    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria();
    $this->db->criteria->add(new Criteria('q.uid',$_SESSION['jieqiUserId']));

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



    // 查询同名渠道
    if(isset($params['qqd'],$params['id']) && $params['id']>0 && strlen($params['qqd']) > 0 ){
      $this->db->criteria->add(new Criteria('q.qd', $params['qqd'])); 
      $this->db->criteria->add(new Criteria('q.id', $params['id'],'!=')); 
    }


    /* 日期筛选 */
    if( isset($params['t1'],$params['t2']) && strlen($params['t1']) === 10 && strlen($params['t2']) === 10 ){
      $this->db->criteria->add(new Criteria('q.pdate', $params['t1'] , ">="));
      $this->db->criteria->add(new Criteria('q.pdate', $params['t2'], "<="));
    }

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder(($params['sort']));
    
    $q =  jieqi_dbprefix('tuike_qdlistwb').' q LEFT JOIN '.jieqi_dbprefix('tuike_orderqd').' o ON q.orderqdid=o.id';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields("q.*,o.ordersn,o.id oid");
    $this->criteria=$this->db->criteria;

    // define('JIEQI_DEBUG_MODE',1);

    $sqlres= $this->db->queryObjects();

    // var_dump( $this->db->sqllog('ret') );
    // die();
         
        

    $ar=array();
    while($row=$this->db->getRow($sqlres)){


      // 同名渠道数
      $sql='SELECT count(*)  FROM '.jieqi_dbprefix('tuike_qdlistwb'). ' WHERE ( qd="'.$row['qd'].'" )';
      $row['qdNameNum']=$this->db->getField($this->db->query($sql));


      // pay
      $timeW=' AND rettime>="'.$row['statime'].'" AND rettime<="'.$row['endtime'].'" ';
      $sql='SELECT count(*) payusers,sum(round(money/100,2)) qdpay FROM '.jieqi_dbprefix('pay_paylog').
        ' WHERE ( payflag="1" AND source="'.$row['qd'].'" '.$timeW.' )';
      $tAr=$this->db->getRow($this->db->query($sql));
      $row['qdpay']=$tAr['qdpay']?$tAr['qdpay']:0;
      $row['payusers']=$tAr['payusers'];

      // regi
      $timeW=' AND regdate>="'.$row['statime'].'" AND regdate<="'.$row['endtime'].'" ';
      $sql='SELECT count(*) FROM '.jieqi_dbprefix('system_users').
        ' WHERE ( source="'.$row['qd'].'" '.$timeW.' )';
      $row['qdreg']=$this->db->getField($this->db->query($sql));


      // click
      $timeW=' AND time>="'.date('YmdH',$row['statime']).'" AND time<"'.date('YmdH',$row['endtime']).'" ';
      $sql='SELECT sum(click) FROM '.jieqi_dbprefix('system_qddata').
        ' WHERE ( qd="'.$row['qd'].'" '.$timeW.' )';
      $row['qdclick']=$this->db->getField($this->db->query($sql));
      if(!$row['qdclick'])$row['qdclick']=0;


      $row['hb']=intval($row['fee'])===0?$row['qdpay']:number_format(($row['qdpay']/$row['fee'])*100,2,'.','');
      $row['params']=strlen($row['params'])>2?$row['params']:'0,0';
      list($aid,$cid)=explode(',',$row['params']);
      $row['qdUrl']=YOUYUEBOOK_URL_M.'/read/'.$aid.'/'.$cid.'.html?qd='.$row['qd'];
      $row['arInfo']=$row['articlename'];
      $row['name']=empty($row['name'])?$row['wxn']:$row['name'];
      $ar[]=$row;
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
   * 添加渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function qdAdd($params=array()){

    $error='';
    $dataS=date('Y-m-d',JIEQI_NOW_TIME);
    $orderqdid=isset($params['orderid'])?intval($params['orderid']):0;
    if($orderqdid === 0)$this->printfail('信息不正确！');

    $this->db->init('orderqd','id','tuike');
    $this->db->setCriteria(new criteria('id',$orderqdid));
    $this->db->criteria->add(new criteria('uid',$_SESSION['jieqiUserId']));
    $order=$this->db->getRow( $this->db->queryObjects() );
    if( !$order )$this->printfail('信息不正确！');

    $editN=0;
    foreach( $params['qdorder'] as $k=>$v ){
      $data=array('uid'=>$_SESSION['jieqiUserId'],'orderqdid'=>$orderqdid);
      $data['qd']=isset($params['qdorder'][$k])?trim($params['qdorder'][$k]):'';
      $data['pdate']=isset($params['qdtime'][$k])?trim($params['qdtime'][$k]):$dataS;
      $data['wxh']=isset($params['qdwxh'][$k])?trim($params['qdwxh'][$k]):'';
      $data['wxn']=isset($params['qdwxn'][$k])?trim($params['qdwxn'][$k]):'';
      $data['fans']=isset($params['qdfan'][$k])?$params['qdfan'][$k]:'';
      $data['read']=isset($params['read'][$k])?intval($params['read'][$k]):'';
      $timeRstart=strtotime($data['pdate']);;


      if( $order['feetype'] === '1' ){ // 1万粉价
        $data['fee']=round($order['feelence']*$data['fans'],2);
      }elseif( $order['feetype'] ==='2' ){ // 2单价
        $data['fee']=isset($params['qdfee'][$k])?trim($params['qdfee'][$k]):'';
      }

      $data['articlename']=isset($params['qdarname'][$k])?trim($params['qdarname'][$k]):'';

      // 是否存在
      $sql='SELECT * FROM '.jieqi_dbprefix('system_qdlist').
        ' WHERE ( qd="'.$data['qd'].'" )';
      $oldQd=$this->db->getRow($this->db->query($sql));

      if( $oldQd !== false && $oldQd['uid'] != $_SESSION['jieqiUserId'] ){
        $error.='添加渠道('.$data['qd'].')失败, 渠道号已经存在！'."<br />"; 
        continue;
      }
      
      // 没有结束的微博渠道
      $sql='SELECT id,statime,endtime FROM '.jieqi_dbprefix('tuike_qdlistwb').
        ' WHERE ( qd="'.$data['qd'].'" AND uid="'.$_SESSION['jieqiUserId'].'" ) ORDER BY endtime DESC';

      $lastQdwe=$this->db->getRow($this->db->query($sql));
      if( $lastQdwe['statime'] >= $timeRstart ){
        $error.='添加渠道('.$data['qd'].')失败, 渠道的上线时间必须大于原有的渠道！'."<br />"; 
        continue;
      }

      $notEndId=$lastQdwe['endtime']==4294967295?$lastQdwe['id']:false;
      $notEndId=$this->db->getField($this->db->query($sql));


      // 如果有渠道记录添加一条微博渠道记录
      if( $oldQd !== false && $notEndId === false ){
        unset($oldQd['id']);
        $oldQd['statime']=strtotime($oldQd['pdate']);
        $oldQd['endtime']=$timeRstart-1;
        $this->db->init("qdlistwb", "id", "tuike");
        $res = $this->db->add( $oldQd );
      }
      // 设置原微博渠道的结束时时
      if( $notEndId ){
        $this->db->init("qdlistwb", "id", "tuike");
        $this->db->edit($notEndId,array('endtime'=>$timeRstart-1));
      }
      // 添加微博渠道表
      $this->db->init("qdlistwb", "id", "tuike");
      $res = $this->db->add( array_merge($data,array('statime'=>$timeRstart,'endtime'=>4294967295)) );


      // 添加源渠道表
      if($oldQd === false){
        $this->db->init("qdlist", "id", "system");
        $res = $this->db->add($data);
      }

      $editN++;
    }

    // '0打包价,1万粉价,2单价',
    if( $editN >0 && ( $order['feetype'] === '1' ||  $order['feetype'] ==='2' ) ){ // 1万粉价 // 2单价
      /* 修改订单的粉丝的成本 */
      $selectSonP='(SELECT sum(fee) FROM '.jieqi_dbprefix('tuike_qdlistwb').
          ' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" ) )';

      $selectSonF='(SELECT sum(fans) FROM '.jieqi_dbprefix('tuike_qdlistwb').
          ' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" ) )';

      $sql="UPDATE ".jieqi_dbprefix('tuike_orderqd').' SET `fee`='.$selectSonP.',`fans`='.$selectSonF.
        ' WHERE ( id="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'")';
     
      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){$error.='订单的总价和粉丝没有变化'."<br />"; }
 
    }elseif( $editN >0 && $order['feetype'] ==='0' ){ // '0打包价

      /* 修改订单下每个渠道的成本 */
      $sql='SELECT sum(fans) fans FROM '.jieqi_dbprefix('tuike_qdlistwb').' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" )';
      $fansAll=$this->db->getField($this->db->query($sql));
      $uprice=$fansAll == 0?0:round($order['fee']/$fansAll,4);

      $sql="UPDATE ".jieqi_dbprefix('tuike_qdlistwb').' SET `fee`=round(`fans`*'.$uprice.',2) WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'")';

      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){$error.='渠道的成本没有变化'."<br />"; }

      /* 修改粉丝数 */
      $selectSonF='(SELECT sum(fans) FROM '.jieqi_dbprefix('tuike_qdlistwb').
          ' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" ) )';
      $sql="UPDATE ".jieqi_dbprefix('tuike_orderqd').' SET `fans`='.$selectSonF.
        ' WHERE ( id="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'")';
      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){$error.='订单的粉丝没有变化'."<br />"; }

    }

    if( $error === '' ){
      $this->msgwin('添加成功！');
    }else{
      $this->printfail($error);
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
   * 修改订单下的渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function editOrderQdList($params=array(),$return=false){
    $success=0;
    $error='';
    $dataS=date('Y-m-d',JIEQI_NOW_TIME);
    $orderqdid=isset($params['oid'])?intval($params['oid']):0;
    if($orderqdid === 0)$this->printfail('信息不正确！');

    $this->db->init('orderqd','id','tuike');
    $this->db->setCriteria(new criteria('id',$orderqdid));
    $this->db->criteria->add(new criteria('uid',$_SESSION['jieqiUserId']));
    $order=$this->db->getRow( $this->db->queryObjects() );
    if( !$order )$this->printfail('信息不正确！');

    $this->db->init("qdlistwb", "id", "tuike");

    foreach( $params['qdtime'] as $k=>$v ){
      $timeRstart=strtotime(date('Y-m-d H',JIEQI_NOW_TIME).':00:00');
      $data=array();
      // $data['qd']=isset($params['qdorder'][$k])?trim($params['qdorder'][$k]):'';
      // $data['pdate']=isset($params['qdtime'][$k])?trim($params['qdtime'][$k]):$dataS;
      $data['wxh']=isset($params['qdwxh'][$k])?trim($params['qdwxh'][$k]):'';
      $data['wxn']=isset($params['qdwxn'][$k])?trim($params['qdwxn'][$k]):'';
      $data['fans']=isset($params['qdfan'][$k])?$params['qdfan'][$k]+0:'';
      $data['read']=isset($params['read'][$k])?trim($params['read'][$k]):'';

      if( $order['feetype'] === '1' ){ // 1万粉价
        $data['fee']=round($order['feelence']*$data['fans'],2);
      }elseif( $order['feetype'] ==='2' ){ // 2单价
        $data['fee']=isset($params['qdfee'][$k])?trim($params['qdfee'][$k]):'';
      }

      $data['articlename']=isset($params['qdarname'][$k])?trim($params['qdarname'][$k]):'';

      $this->db->edit($k,$data,true,' AND uid=\''.$_SESSION['jieqiUserId'].'\' AND orderqdid=\''.$orderqdid.'\'');
      // 修改是否成功
      if( $this->db->getAffectedRows() > 0 ){
        $success++;
      }
    }


    // '0打包价,1万粉价,2单价',
    if( $success >0 && ( $order['feetype'] === '1' ||  $order['feetype'] ==='2' ) ){ // 1万粉价 // 2单价
      /* 修改订单的粉丝的成本 */
      $selectSonP='(SELECT sum(fee) FROM '.jieqi_dbprefix('tuike_qdlistwb').
          ' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" ) )';

      $selectSonF='(SELECT sum(fans) FROM '.jieqi_dbprefix('tuike_qdlistwb').
          ' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" ) )';

      $sql="UPDATE ".jieqi_dbprefix('tuike_orderqd').' SET `fee`='.$selectSonP.',`fans`='.$selectSonF.
        ' WHERE ( id="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'")';
     
      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){$error.='订单的总价和粉丝没有变化'."<br />"; }
 
    }elseif( $success >0 && $order['feetype'] ==='0' ){ // '0打包价

      /* 修改订单下每个渠道的成本 */
      $sql='SELECT sum(fans) fans FROM '.jieqi_dbprefix('tuike_qdlistwb').' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" )';
      $fansAll=$this->db->getField($this->db->query($sql));
      $uprice=$fansAll == 0?0:round($order['fee']/$fansAll,4);

      $sql="UPDATE ".jieqi_dbprefix('tuike_qdlistwb').' SET `fee`=round(`fans`*'.$uprice.',2) WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'")';

      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){$error.='渠道的成本没有变化'."<br />"; }

      /* 修改粉丝数 */
      $selectSonF='(SELECT sum(fans) FROM '.jieqi_dbprefix('tuike_qdlistwb').
          ' WHERE ( orderqdid="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'" ) )';
      $sql="UPDATE ".jieqi_dbprefix('tuike_orderqd').' SET `fans`='.$selectSonF.
        ' WHERE ( id="'.$orderqdid.'" AND uid="'.$_SESSION['jieqiUserId'].'")';
      $this->db->query($sql);
      if( $this->db->getAffectedRows() <= 0 ){$error.='订单的粉丝没有变化'."<br />"; }

    }

    if( $success > 0 ){
      $this->msgwin('修改成功！');
    }else{
      $this->printfail($error===''?'修改失败！':$error);
    }
  }


  /**
   * 订单下的渠道
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function getOrderQdList($params=array(),$return=false){
    $this->db->init("qdlistwb", "id", "tuike");
    $this->db->setCriteria(new Criteria('orderqdid',$params['oid']));
    $this->db->criteria->add(new Criteria('uid', $_SESSION['jieqiUserId']));
    $this->db->queryObjects();
    if( isset($params['returnHtml']) ){
      $back='';

      // <td><input type="text" name="qdtime['.$row['id'].']" value="'.date('Y-m-d H:i',$row['statime']).'" onclick="datetimepickerRun(this)"/><br></td>
      while($row=$this->db->getRow()){
        $back.='<tr>
          <td>'.$row['qd'].'</td>
          <td>'.date('Y-m-d H:i',$row['statime']).'</td>
          <td>'.($row['endtime']==4294967295?'到现在':date('Y-m-d H:i',$row['endtime'])).'</td>
          <td><input type="text" name="qdwxh['.$row['id'].']" value="'.$row['wxh'].'"></td>
          <td><input type="text" name="qdwxn['.$row['id'].']" value="'.$row['wxn'].'"></td>
          <td class="uprice"><input type="text" name="qdfee['.$row['id'].']" value="'.$row['fee'].'"></td>
          <td><input type="text" name="qdfan['.$row['id'].']" value="'.$row['fans'].'"></td>
          <td><input type="text" name="qdarname['.$row['id'].']" value="'.$row['articlename'].'"></td>
          <td><input type="text" name="read['.$row['id'].']" value="'.$row['read'].'"></td>
          <td><a class="u-btn u-btn-success u-btn-large deleteQd" _id="'.$row['id'].'">删除</a></td>
        </tr>';
      }
    }else{
      $back=array();
      while($row=$this->db->getRow()){
        $back[]=$row;
      }
    }
    return $back;
  }
  /**
   * 一周数据
   * @return [type] [description]
   */
  function getWeekDataList(){
    global $cache_redis;
    if( $cache_redis->isExists(JIEQI_REDIS_FIX.'weekdatalist_'.$_SESSION['jieqiUserId']) ){
      $data=unserialize( $cache_redis->get(JIEQI_REDIS_FIX.'weekdatalist_'.$_SESSION['jieqiUserId']) );
      if(isset($data['0']['orderFee']))return $data;
    }

    $data=array();
    // 循环7记录
    $dateS=date('Y-m-d',JIEQI_NOW_TIME);
    for($i=1;$i<=7;$i++){
      $ar=array();
      $dateS=date('Y-m-d',strtotime($dateS.' -1 day'));
      $t1=strtotime($dateS);
      $t2=strtotime($dateS.' 23:59:59');
      $ar['dateS']=$dateS;


      //派单数 订单信息
      $sql='SELECT count(*) count,sum(fee) fees FROM '.jieqi_dbprefix('tuike_orderqd').
        ' WHERE ( uid="'.$_SESSION['jieqiUserId'].'" AND addtime>="'.$t1.'" AND addtime<="'.$t2.'" ) LIMIT 1';
      $arTmp=$this->db->getROW($this->db->query($sql));
      $ar['orderN']=$arTmp['count'];
      $ar['orderFee']=$arTmp['fees']?$arTmp['fees']:0;


      //  每日总充值
      $sql='SELECT sum(round(p.money/100,2)) FROM '.jieqi_dbprefix('pay_paylog').
        ' p LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.qd=p.source '.
        'WHERE ( p.payflag="1" AND q.uid="'.$_SESSION['jieqiUserId'].
        '" AND p.rettime <="'.$t2.'" AND p.rettime>="'.$t1.'" )';
      $ar['payall']=$this->db->getField($this->db->query($sql));

      //  新增渠道总充值 $t2
      $timeW=' AND p.rettime >= q.statime AND p.rettime<="'.$t2.'" ';
      $sql='SELECT sum(round(p.money/100,2)) FROM '.jieqi_dbprefix('pay_paylog').
        ' p LEFT JOIN '.jieqi_dbprefix('tuike_qdlistwb').' q ON q.qd=p.source '.
        'WHERE ( p.payflag="1" AND q.uid="'.$_SESSION['jieqiUserId'].
        '" AND q.pdate="'.$dateS.'" '.$timeW.' )';
      $ar['payAllQd']=$this->db->getField($this->db->query($sql));

      // 渠道支付
      $sql='SELECT sum(q.fee) FROM '.jieqi_dbprefix('tuike_qdlistwb').
        ' q WHERE ( uid="'.$_SESSION['jieqiUserId'].
        '" AND q.pdate="'.$dateS.'" )';
      $ar['feeAll']=$this->db->getField($this->db->query($sql));

      // 数据处理
      if( !$ar['feeAll'] )$ar['feeAll']=0;
      if( !$ar['payall'] )$ar['payall']=0;
      if( !$ar['payAllQd'] )$ar['payAllQd']=0;
      $ar['hix']=$ar['feeAll'] === 0?0:round($ar['payAllQd']/$ar['feeAll']*100,2);
      $data[]=$ar;
    }
  
    $time=strtotime(date('Y-m-d H',JIEQI_NOW_TIME).':00:00')+3600-JIEQI_NOW_TIME;
    $cache_redis->set(JIEQI_REDIS_FIX.'weekdatalist_'.$_SESSION['jieqiUserId'],serialize($data),$time);
    return $data;
  }
  /**
   * 昨日渠道收入
   * @return [type] [description]
   */
  function getYesterdayQdList(){
    global $cache_redis;
    if( $cache_redis->isExists(JIEQI_REDIS_FIX.'yesterdayqdlist'.$_SESSION['jieqiUserId']) ){
      return unserialize( $cache_redis->get(JIEQI_REDIS_FIX.'yesterdayqdlist'.$_SESSION['jieqiUserId']) );
    }

    $dateS=date('Y-m-d',JIEQI_NOW_TIME);
    $dateS=date('Y-m-d',strtotime($dateS.' -1 day'));
    // 渠道支付
    $sql='SELECT q.qd,q.fee,sum(round(p.money/100,2)) payAll FROM '.jieqi_dbprefix('tuike_qdlistwb').
      ' q LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd '.
      'WHERE ( p.payflag="1" AND q.uid="'.$_SESSION['jieqiUserId'].
      '" AND q.pdate="'.$dateS.'" AND p.rettime >= q.statime AND p.rettime < q.endtime ) GROUP BY q.id';
    $result=$this->db->query($sql);
    $ar=array();
    while($row=$this->db->getRow($result)){
      $row['hix']=$row['fee']>0?round($row['payAll']/$row['fee']*100,2):0;
      $ar[]=$row;
    }

    $time=strtotime(date('Y-m-d H',JIEQI_NOW_TIME).':00:00')+3600-JIEQI_NOW_TIME;
    $cache_redis->set(JIEQI_REDIS_FIX.'yesterdayqdlist'.$_SESSION['jieqiUserId'],serialize($ar),$time);
    return $ar;
  }
  /**
   * 一周前7日
   * @return [type] [description]
   */
  function getPreWeekDataList(){
    global $cache_redis;
    if( $cache_redis->isExists(JIEQI_REDIS_FIX.'preweekdatalist'.$_SESSION['jieqiUserId']) ){
      return unserialize( $cache_redis->get(JIEQI_REDIS_FIX.'preweekdatalist'.$_SESSION['jieqiUserId']) );
    }

    $data=array();
    // 循环7记录
    $dateS=date('Y-m-d',JIEQI_NOW_TIME);
    $dateS=date('Y-m-d',strtotime($dateS.' -7 day'));
    for($i=1;$i<=7;$i++){
      $ar=array();
      $dateS=date('Y-m-d',strtotime($dateS.' -1 day'));
      $ar['dateS']=$dateS;

      // 充值
      $sql='SELECT sum(round(p.money/100,2)) FROM '.jieqi_dbprefix('pay_paylog').
        ' p LEFT JOIN '.jieqi_dbprefix('tuike_qdlistwb').' q ON q.qd=p.source '.
        'WHERE ( p.payflag="1" AND q.uid="'.$_SESSION['jieqiUserId'].
        '" AND q.pdate="'.$dateS.'" AND p.rettime >= q.statime AND p.rettime < q.endtime )';
      $ar['payall']=$this->db->getField($this->db->query($sql));

      // 三天充值
      $t2=strtotime($dateS.' +3 day')-1;
      $sql='SELECT sum(round(p.money/100,2)) FROM '.jieqi_dbprefix('pay_paylog').
        ' p LEFT JOIN '.jieqi_dbprefix('tuike_qdlistwb').' q ON q.qd=p.source '.
        'WHERE ( p.payflag="1" AND q.uid="'.$_SESSION['jieqiUserId'].
        '" AND p.rettime<="'.$t2.'" AND q.pdate="'.$dateS.'" AND p.rettime >= q.statime AND p.rettime < q.endtime )';
      $ar['thdayPay']=$this->db->getField($this->db->query($sql));

      // 七天充值
      $t2=strtotime($dateS.' +7 day')-1;
      $sql='SELECT sum(round(p.money/100,2)) FROM '.jieqi_dbprefix('pay_paylog').
        ' p LEFT JOIN '.jieqi_dbprefix('tuike_qdlistwb').' q ON q.qd=p.source '.
        'WHERE ( p.payflag="1" AND q.uid="'.$_SESSION['jieqiUserId'].
        '" AND p.rettime<="'.$t2.'" AND q.pdate="'.$dateS.'" AND p.rettime >= q.statime AND p.rettime < q.endtime )';
      $ar['wedayPay']=$this->db->getField($this->db->query($sql));


      // 成本
      $sql='SELECT sum(q.fee) FROM '.jieqi_dbprefix('tuike_qdlistwb').
        ' q WHERE ( uid="'.$_SESSION['jieqiUserId'].
        '" AND q.pdate="'.$dateS.'" )';
      $ar['feeAll']=$this->db->getField($this->db->query($sql));

      // 数据处理
      if( !$ar['payall'] )$ar['payall']=0;
      if( !$ar['thdayPay'] )$ar['thdayPay']=0;
      if( !$ar['wedayPay'] )$ar['wedayPay']=0;
      if( !$ar['feeAll'] )$ar['feeAll']=0;
      if( $ar['feeAll'] === 0 ){
        $ar['thHix']=0;
        $ar['weHix']=0;
      }else{
        $ar['thHix']=round($ar['thdayPay']/$ar['feeAll']*100,2);
        $ar['weHix']=round($ar['wedayPay']/$ar['feeAll']*100,2);
      }

      $data[]=$ar;
    }
  
    $time=strtotime(date('Y-m-d H',JIEQI_NOW_TIME).':00:00')+3600-JIEQI_NOW_TIME;
    $cache_redis->set(JIEQI_REDIS_FIX.'preweekdatalist'.$_SESSION['jieqiUserId'],serialize($data),$time);
    return $data;
  }

  /**
   * 删除渠道
   * @return [type] [description]
   */
  function deleteQd($params=array()){

    $sql='SELECT qd,uid FROM '.jieqi_dbprefix('tuike_qdlistwb').' WHERE ( id="'.$params['qid'].'" )';
    $oldQd=$this->db->getRow($this->db->query($sql));
    if( !$oldQd || $oldQd['uid']!=$_SESSION['jieqiUserId'] )$this->printfail('信息不正确！');

    $this->db->init("qdlistwb", "id", "tuike");
    $this->db->setCriteria(new Criteria('id',$params['qid']));
    $this->db->delete($this->db->criteria);
    
    // 修改是否成功
    if( $this->db->getAffectedRows() > 0 ){

      $sql='SELECT endtime FROM '.jieqi_dbprefix('tuike_qdlistwb').
        ' WHERE ( qd="'.$oldQd['qd'].'" ) ORDER BY endtime DESC LIMIT 1';
      $endtime=$this->db->getField($this->db->query($sql));

      if( $endtime === false ){
        $this->db->init("qdlist", "id", "system");
        $this->db->setCriteria(new Criteria('qd',$oldQd['qd']));
        $this->db->delete($this->db->criteria);
      }else if( $endtime !== '4294967295' ){

        $sql='UPDATE '.jieqi_dbprefix('tuike_qdlistwb').' SET `endtime`="4294967295" ORDER BY statime DESC LIMIT 1';
        $this->db->query($sql);
      }

      $this->msgwin('删除成功！');
    }else{
      $this->printfail('删除失败！');
    }

  } 



/*----newRun-----------------------------------------------------------------------------------------------------------------------------*/








} 
?>