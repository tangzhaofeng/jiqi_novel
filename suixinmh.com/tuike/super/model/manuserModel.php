<?php 
/** 
 * 下级推客明细模块
 * @author      gaoli* @version     1.0 
 */ 
class manuserModel extends Model{
  //login form
  public function main($params){
    
    $data = array();
    return $data;
  }
  /**
   * 经理所属推客
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function maUserPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    global $jieqiGroups,$G_FILED_Y;
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];

    /* 条件 */ 
    if( isset($params['uid']) && $params['uid'] > 0 ){
      $where='( u.uid = "'.$params['uid'].'"';
    }elseif( isset($params['tkId']) && $params['tkId'] > 0 ){
      $where='( u.tkuid = "'.$params['tkId'].'"';
    }else{
      $where='( 1 ';
    }
    $where.=' AND u.groupid="6"';

    if(isset($params['keyword']) && strlen($params['keyword']) > 0){// 搜索 
      $where.=' AND u.uname="'.trim($params['keyword']).'"';
    }



    // 筛选
    if( isset($params['screen_sort'],$G_FILED_Y[$params['screen_sort']]) ){
      $scr_sort=$G_FILED_Y[$params['screen_sort']];

      if( strpos($params['screen_sort'],'time') !== false && isset($params['screen_t1'],$params['screen_t2']) && strlen($params['screen_t1']) >3 ){

        // if( $scr_sort !== 'q.pdate' ){
        //   $params['screen_t1']=strtotime($params['screen_t1']);
        //   $params['screen_t2']=strtotime($params['screen_t2']);
        // }

        // $this->db->criteria->add(new Criteria($scr_sort, $params['screen_t1'],'>=' )); 
        // $this->db->criteria->add(new Criteria($scr_sort, $params['screen_t2'],'<=' )); 

      }elseif( isset($params['screen_text']) && strlen( $params['screen_text'] ) >= 1 ){


        if( $params['screen_sort']==='tkman' && ( strlen( $params['screen_text'] ) === 11 || !is_numeric($params['screen_text']) ) ){
          $sql='SELECT uid FROM '.jieqi_dbprefix('tuike_users').' WHERE ( uname="'.trim($params['screen_text']).'" )';
          $params['screen_text']=$this->db->getField($this->db->query($sql));
        }


        if($params['screen_text'] !== false){
          $where.=' AND '.$scr_sort.'="'.trim($params['screen_text']).'"';
        }


      }

    }








    $where.=')';
    /* 获取数量 */
    $limit=' LIMIT '.$params['start'].','.$params['limit'];
    /* 排序 */
    // $order=' ORDER BY u.groupid ASC, '.$params['orderS'].' '.$params['sort'];
    $order=' ORDER BY '.$params['orderS'].' '.$params['sort'];
    /* 分组 */
    $groupby=' GROUP BY u.uid';

    // define('JIEQI_DEBUG_MODE',1);

    $sql ='SELECT u.notes,u.uid,u.uname,u.reg_time,u.groupid,u.is_tuike,round(sum(p.money)/100,2) as money,u1.uname mauname FROM '.
      jieqi_dbprefix('tuike_users').' u LEFT JOIN '.
      jieqi_dbprefix('tuike_users').' u1 ON u.mauid=u1.uid LEFT JOIN '.
      jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1 '.
      ' WHERE '.$where.$groupby.$order.$limit;

    $sqlres=$this->db->query($sql);;

    // var_dump( $this->db->sqllog('ret') );
    // die();

    $dayT1 = strtotime( date("Y-m-d") ); // 获取当日充值
    $monthT1=strtotime( date('Y-m-01').' 00:00:00' );  // 获取当月充值

    $ar=array();
    while($row=$this->db->getRow($sqlres)){
      if( !$row['money'] )$row['money']=0;
      if( strlen($row['mauname'])<=1 )$row['mauname']='(无)';

      $row['p_qq']=empty($row['p_qq'])?'(无)':$row['p_qq'];
      $row['typeS']=isset($jieqiGroups[$row['groupid']])?$jieqiGroups[$row['groupid']]:'(无)';
      $row['payDay']=0;
      $row['payMonth']=0;

      //充值昨日
      $sql ='SELECT round(sum(p.money)/100,2) as money  FROM '.
      jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1 '.
      ' WHERE u.uid="'.$row['uid'].'" AND p.rettime > "'.$dayT1.'"';
      $ro=$ro=$this->db->getRow($this->db->query($sql));
      if( $ro && $ro['money'] > 0 )$row['payDay']=$ro['money'];

      //充值本月
      $sql ='SELECT round(sum(p.money)/100,2) as money  FROM '.
      jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1 '.
      ' WHERE u.uid="'.$row['uid'].'" AND p.rettime > "'.$monthT1.'"';
      $ro=$ro=$this->db->getRow($this->db->query($sql));
      if( $ro && $ro['money']>0 )$row['payMonth']=$ro['money'];


      //广告分成
      $row['payT']=0;
      $row['payTn']=0;
      $row['payQ']=0;
      $row['payQn']=0;
      $this->db->init('paylog','payid','tuike');
      $this->db->setCriteria(new Criteria('type',3,'!='));
      $this->db->criteria->add(new Criteria('uid',$row['uid'] ));
      $this->db->criteria->setFields("count(distinct payid) as payusers,round(sum(money)) as money,type");
      $this->db->criteria->setGroupby('type');
      $this->db->queryObjects();
      while( $ro=$this->db->getRow() ){
        if($ro['type'] === '1'){
          $row['payT']=$ro['money'];
          $row['payTn']=$ro['payusers'];
        }elseif($ro['type'] === '2'){
          $row['payQ']=$ro['money'];
          $row['payQn']=$ro['payusers'];
        }
      }
      $row['payAlDay']=$row['payT']+$row['payQ'];
      $row['payAlDayn']=$row['payTn']+$row['payQn'];



      //推广分成
      $this->db->init('users','uid','tuike');
      $this->db->setCriteria(new Criteria('tkuid',$row['uid']));
      $this->db->criteria->add(new Criteria('uid',$row['uid'],'!='));
      $row['payTNum']=$this->db->getCount($this->db->criteria);

      //渠道管理
      $this->db->init('qdlist','id','system');
      $this->db->setCriteria(new Criteria('uid',$row['uid']));
      $row['qdNum']=$this->db->getCount($this->db->criteria);
      $ar[]=$row;
    }


    if ($params['pageShow']){
      $sql ='SELECT count(*) cou FROM '.
        jieqi_dbprefix('tuike_users').' u LEFT JOIN '.
        jieqi_dbprefix('tuike_users').' u1 ON u.mauid=u1.uid '.
        ' WHERE '.$where;
      $row=$this->db->getRow($this->db->query($sql));
      $this->setVar('totalcount', $row['cou']);
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }
  /**
   * 推客总的充值记录
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
    $this->db->criteria->add(new Criteria('u.tkuid',$params['uid']));
    $this->db->criteria->add(new Criteria('u.uid',$params['uid'],'!='));
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
    if ($params['pageShow']){
      $this->db->setCriteria(new Criteria('u.tkuid',$params['uid']));
      $this->db->criteria->add(new Criteria('u.uid',$params['uid'],'!='));
      // 搜索
      if(isset($params['keyword']) && strlen($params['keyword']) > 0){
        $this->db->criteria->add(new Criteria('u.name', trim($params['keyword']))); 
      }
      $q = jieqi_dbprefix('tuike_users').' u';
      $this->db->criteria->setTables($q);
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }


  /**
   * 推客详细(更多信息)
   * @return [type] [description]
   */
  function tkInfo($params=array()){
    // 推客
    $info=$this->qdinfo($params);
    // 上级推客
    $info['tkuidname']='(无)';
    if( $info['tkuid'] >0 ){
      $row=$this->qdinfo(array('uid'=>$info['tkuid']));
      if( $row )$info['tkuidname']=$row['uname'];
    }
    // // 昨日（广告，推广）
    // $info['paylogG']='0';
    // $info['paylogT']='0';
    // $dateS=date('Y-m-d',strtotime(' -1 day',JIEQI_NOW_TIME));
    // $this->db->init('paylog','payid','tuike');
    // $this->db->setCriteria(new Criteria('uid',$info['uid'] ));
    // $this->db->criteria->add(new Criteria('date',$dateS ));
    // $this->db->criteria->setFields('type,money');
    // $this->db->queryObjects();
    // while($row=$this->db->getRow()){
    //   if($row['type'] === '1')$info['paylogT']=$row['money'];
    //   if($row['type'] === '2')$info['paylogG']=$row['money'];
    // };
    return $info;
  }

  /**
   * 编辑推客
   * @param  array  $params [description]
   * @return [type]         [description]
   */
  function tkInfoEdit($params=array()){
    $data=array();
    $params['uid']=isset($params['uid'])?intval($params['uid']):0;
    $params['paychannel']=isset($params['paychannel'])?trim($params['paychannel']):'';
    $params['pay_account']=isset($params['pay_account'])?trim($params['pay_account']):'';

    $data['p_uname']=isset($params['pay_realname'])?trim($params['pay_realname']):'';
    $data['p_wechat']=isset($params['pay_weixin'])?trim($params['pay_weixin']):'';
    $data['p_qq']=isset($params['pay_qq'])?trim($params['pay_qq']):'';
    $data['p_bankn']=isset($params['pay_bankh'])?trim($params['pay_bankh']):'';

    // $data['notes']=isset($params['notes'])?trim($params['notes']):'';

    if( $data['p_uname']==='' )$this->printfail('请输入真实姓名！');
    if($params['uid'] === 0)$this->printfail('信息不正确！');
    switch($params['paychannel']){
      case 'alipay':
          $data['p_ali']=$params['pay_account'];
          $data['p_type']=1;
        break;
      case 'bank':
          $data['p_bank']=$params['pay_account'];
          $data['p_type']=3;
          if( $data['p_bankn']==='' )$this->printfail('请输入开启行！');
        break;
      default:
        $this->printfail('不存在该支付方式！');
    }

    $this->db->init('users','uid','tuike');
    $this->db->edit($params['uid'],$data);
    if( $this->db->getAffectedRows() > 0 ){
      $this->msgwin('修改成功！');
    }else{
      $this->printfail('修改失败！');
    }
  }


  /**
   * 推客详细
   * @return [type] [description]
   */
  function qdinfo($params=array()){
    $this->db->init('users','uid','tuike');
    $this->db->setCriteria(new Criteria('uid',$params['uid'] ));
    $this->db->queryObjects();
    return $this->db->getRow();
  }
  /**
   * 经理所属推客
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function maUserTkPay($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    if(!isset($params['limit']))$params['limit']=10;
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];
    /* 条件 */ 
    $this->db->setCriteria(new Criteria('u.tkuid',$params['uid']));
    $this->db->criteria->add(new Criteria('u.uid',$params['uid'],'!='));
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
    $q = jieqi_dbprefix('tuike_users').' u LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON q.uid=u.uid LEFT JOIN '.
      jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.payflag=1';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('u.uid,u.uname,u.reg_time,round(sum(p.money)/100) as money ');
    $this->db->criteria->setGroupby("u.uid");
    $sqlres=$this->db->queryObjects();
    $ar=array();
    while($row=$this->db->getRow($sqlres)){
      if( !$row['money'] )$row['money']=0;

      //广告分成
      $row['payT']=0;
      $row['payG']=0;
      $this->db->init('paylog','payid','tuike');
      $this->db->setCriteria(new Criteria('type',3,'!='));
      $this->db->criteria->add(new Criteria('uid',$row['uid'] ));
      $this->db->criteria->setFields("count(distinct payid) as payusers,round(sum(money)) as money,type");
      $this->db->criteria->setGroupby('type');
      $this->db->queryObjects();
      while( $ro=$this->db->getRow() ){
        if($ro['type'] === '1'){
          $row['payT']=$ro['money'];
        }elseif($ro['type'] === '2'){
          $row['payG']=$ro['money'];
        }
      }

      //推广分成
      $this->db->init('users','uid','tuike');
      $this->db->setCriteria(new Criteria('tkuid',$row['uid']));
      $this->db->criteria->add(new Criteria('uid',$row['uid'],'!='));
      $row['payTNum']=$this->db->getCount($this->db->criteria);

      //渠道管理
      $this->db->init('qdlist','id','system');
      $this->db->setCriteria(new Criteria('uid',$row['uid']));
      $row['qdNum']=$this->db->getCount($this->db->criteria);
      $ar[]=$row;
    }

    if ($params['pageShow']){
      $this->db->init('users','uid','tuike');
      $this->db->setCriteria(new Criteria('tkuid',$params['uid']));
      $this->db->criteria->add(new Criteria('uid',$params['uid'],'!='));
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }



  /**
   * 所有推客的每月充值列表
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function moon_list($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    if (!$params['page']) $params['page'] = 1;
    $params['start']=($params['page'] - 1) * $params['limit'];

  
    /* 条件 */ 
    $this->db->setCriteria(new Criteria('u.groupid',6));
   
    if( !isset($params['moon']) )$this->printfail('不存在该请求！');


    $today = getdate(strtotime($params['moon']));
    $t1=mktime(0, 0 , 0,$today['mon'],1,$today['year']);//获取月头时间
    $t2=mktime(23,59,59,$today['mon']+1 ,0,$today['year']); //获取月尾时间

    /* 获取数量 */
    $this->db->criteria->setLimit($params['limit']);
    $this->db->criteria->setStart($params['start']);
    /* 排序 */
    $this->db->criteria->setSort($params['orderS']);
    $this->db->criteria->setOrder( $params['sort'] );
   
    $q = jieqi_dbprefix('tuike_users').' u '.
      'LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON u.uid=q.uid '.
      'LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.rettime >="'.$t1.'"
        AND p.rettime <="'.$t2.'"';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('u.uid,u.uname,notes,u.reg_time,round(sum(p.money)/100,2) almoney');
    $this->db->criteria->setGroupby("u.uid");

    // var_dump( $this->db->returnSql($this->db->criteria) );
    // die();


    $sqlres=$this->db->queryObjects();
    $ar=array();

         
    while($row=$this->db->getRow($sqlres)){
      if( !$row['almoney'] )$row['almoney']=0;
      $ar[]=$row;
    }


    if ($params['pageShow']){
      $this->setVar('totalcount', $this->db->getCount($this->db->criteria));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);
      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }


  /**
   * 所有推客的每月充值列表下载
   * @param  array   $params       [description]
   * @param  [type]  $custompage   [description]
   * @param  boolean $emptyonepage [description]
   * @return [type]                [description]
   */
  public function moon_download($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){
    /* 条件 */ 
    $this->db->setCriteria(new Criteria('u.groupid',6));
   
    $today = getdate(strtotime($params['moon']));
    $t1=mktime(0, 0 , 0,$today['mon'],1,$today['year']);//获取月头时间
    $t2=mktime(23,59,59,$today['mon']+1 ,0,$today['year']); //获取月尾时间

    /* 排序 */
    $this->db->criteria->setSort('almoney');
    $this->db->criteria->setOrder( 'DESC' );
   
    $q = jieqi_dbprefix('tuike_users').' u '.
      'LEFT JOIN '.jieqi_dbprefix('system_qdlist').' q ON u.uid=q.uid '.
      'LEFT JOIN '.jieqi_dbprefix('pay_paylog').' p ON p.source=q.qd AND p.rettime >="'.$t1.'"
        AND p.rettime <="'.$t2.'"';
    $this->db->criteria->setTables($q);
    $this->db->criteria->setFields('u.uid,u.uname,notes,u.reg_time,round(sum(p.money)/100,2) almoney');
    $this->db->criteria->setGroupby("u.uid");

    $sqlres=$this->db->queryObjects();
    $ar=array();

    // var_dump( $this->db->returnSql($this->db->criteria) );
    // die();


    /* 处理数据 */
    $str='';

    while($row=$this->db->getRow($sqlres)){
      if( !$row['almoney'] )$row['almoney']=0;
      $str.='<tr>
          <td>'.$row['uid'].'</td>
          <td>'.$row['uname'].' </td>
          <td>'.$row['notes'].' </td>
          <td class="rightborder">'.$row['almoney'].' </td>
        </tr>
      </tr>';
    }

    if( $str==='' )jump_fail('信息不正确！');


    $str='<table class="tb" >
                <thead>
                  <tr style="height: 40px"> 
                      <th class="all_border" style="font-size: 16pt; font-family: 宋体;" colspan="4"> 推客 '.$params['moon'].' 充值 </th> 
                  </tr> 
                  <tr>
                    <th>推客id</th>
                    <th>推客姓名</th>
                    <th>经理备注</th>
                    <th class="rightborder">月充值</th>
                  </tr>
                </thead>
                <tbody>'.$str;
    $str.= '<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"> <head> <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> <!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name></x:Name><x:WorksheetOptions><x:Selected/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--> <style type="text/css"> .td{width: 120px; } .gdtjContainer .tb tr{text-align: center; vertical-align: middle; } .gdtjContainer .tb th{border-left: 0.5pt solid #000; border-bottom: 0.5pt solid #000; text-align: center; font-weight: normal; font-size: 10pt; middle: ;; height:30px; } .gdtjContainer .header th {font-size: 12pt; } .gdtjContainer .tb tr .noleftborder {border-left: none; } .gdtjContainer .tb tr .rightborder {border-right: 0.5pt solid #000; } .gdtjContainer .tb td{border-left: 0.5pt solid #000; border-bottom: 0.5pt solid #000; text-align: center; font-weight: normal; font-size: 10pt; middle: ;; height:30px; }.all_border{border: 0.5pt solid #000; } </style> </head> <body> <div class="gdtjContainer"> '.$str.'</tbody></table> </div> </body> </html>';

    $str=iconv('GBK', 'UTF-8//IGNORE',$str);
    $base='/themes/' . JIEQI_THEME_NAME . '/file/'.$params['moon'].'.xls';

 

    $back=file_put_contents(JIEQI_ROOT_PATH_APP.$base,$str);
    if( $back ){

      jump_success('','',JIEQI_URL.$base );
    }else{
      jump_fail('信息不正确！');
    }
  }



  /**
   * 每次的公共调用
   * @return [type] [description]
   */
  function common(){
    $data=array();
    // 查询推客数
    $this->db->init('users','uid','tuike');
    $this->db->setCriteria( new Criteria('groupid',6) );
    $data['tkNu']=$this->db->getCount($this->db->criteria);
    // 查询待审核金额
    $data['payusers']=0;
    $data['money']=0;
    $this->db->init('paylog','payid','tuike');
    $this->db->setCriteria(new Criteria('type',3 ));
    $this->db->criteria->add(new Criteria('payflag',1 ));
    $this->db->criteria->setFields("count(distinct payid) as payusers,round(sum(money)) as money");

    $this->db->queryObjects();

    $row=$this->db->getRow();
    if($row && $row['money']){
      $data['payusers']=$row['payusers'];
      $data['money']=$row['money'];
    }
    return $data;
  }

  /**
   * 设置经理
   * @param array $params [description]
   */
  function setMan($params=array()){

    $uid=$params['uid'];
    $mauid=$params['mauid'];
    $this->db->init('users','uid','tuike');
    $mainfo=$this->db->get($mauid);


    if( !$mainfo || $mainfo['groupid'] != 2 )$this->printfail('不存在该经理');
    $this->db->edit($uid,array("mauid"=>$mauid));
    // 修改是否成功
    if( $this->db->getAffectedRows() > 0 ){
      die(json_encode(array('status'=>'OK','msg'=>'','mauname'=>iconv('GBK', 'UTF-8//IGNORE',$mainfo['uname']))));
    }else{
      $this->printfail('设置失败！');
    }

  }


 /**
   * 设置提现请求的状态(审核成功，审核失败)
   * @param array $params [description]
   */
  public function setPa($params=array()){
    $params['field_i']=isset($params['field_i'])?intval($params['field_i']):0;
    $parasm['field_v']=isset($params['field_v'])?trim($params['field_v']):'';
    $params['field_y']=isset($params['field_y'])?trim($params['field_y']):'';     
    if( $params['field_i']===0 || 
        $params['field_v']==='' ||
        $params['field_y']==='' )$this->printfail('信息不正确！');
    switch($params['field_y']){
      case 'istuike':
        $sql="UPDATE ".jieqi_dbprefix('tuike_users').' SET `is_tuike`="'.$params['field_v'].'" WHERE uid="'.$params['field_i'].'"';
        break;
      default:
        $this->printfail('不存在该设置！');
    }
    $this->db->query($sql);
    // 修改是否成功
    if( $this->db->getAffectedRows() > 0 ){

      $this->msgwin('修改成功！');
    }else{
      $this->printfail('设置失败！');
    }
   }









} 
?>