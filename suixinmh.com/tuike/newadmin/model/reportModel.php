<?php 
/** 
 * 系统管理->用户组管理 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class reportModel extends Model{
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
  public function mainList($params=array(),$custompage=JIEQI_PAGE_TAG,$emptyonepage = false){

    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];

    /* 条件 */ 
    $where='(q.uid="'.$_SESSION['jieqiUserId'].'" ';
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){// 搜索 
      $where.=' AND q.qd="'.trim($params['keyword']).'"';
    }

    /* 日期筛选 */
    if( isset($params['t1'],$params['t2']) && strlen($params['t1']) === 10 && strlen($params['t2']) === 10 ){
      $where.=' AND q.pdate>="'.trim($params['t1']).'" AND q.pdate<="'.trim($params['t2']).'" ';
    }

    $where.=')';
    /* 获取数量 */
    $limit=' LIMIT '.$params['start'].','.$params['limit'];
    /* 排序 */
    $order=' ORDER BY '.$params['orderS'].' '.$params['sort'];
    /* 分组 */
    $groupby=' GROUP BY q.id';

    $sql ='SELECT q.pdate,q.qd,q.wxh,q.wxn,q.fans,q.fee,q.articlename,q.read,sum(d.click) as click,sum(d.pv) pv,o.company,o.feetype,o.feelence FROM '.jieqi_dbprefix('system_qdlist').' q '.
      'LEFT JOIN '.jieqi_dbprefix('tuike_orderqd').' o ON o.id=q.orderqdid '.
      'LEFT JOIN '.jieqi_dbprefix('system_qddata').' d ON d.qd=q.qd '.
      ' WHERE '.$where.$groupby.$order.$limit;
         
    $sqlres= $this->db->query($sql);
    $ar=array();
    while($row=$this->db->getRow($sqlres)){
      $row['click']=$row['click']?$row['click']:0;
      $row['pv']=$row['pv']?$row['pv']:0;
      /* 计算单价 */
      if( $row['feetype'] === '0' ){
        $row['uprice']=$row['fans']==0?0:round($row['fee']/$row['fans'],2);
      }elseif( $row['feetype'] === '1' ){
        $row['uprice']=$row['feelence'];
      }elseif( $row['feetype'] === '2' ){
        $row['uprice']=$row['fee'];
      }

      $row['uvIx']=$row['fans']==0?0:round($row['read']/$row['fans']/10000*100,2); 
      $row['readIx']=$row['read']==0?0:round($row['click']/$row['read']*100,2);
    

      /* 注册用户 */
      $sql='SELECT count(*) FROM '.jieqi_dbprefix('system_users').' WHERE ( source="'.$row['qd'].'" )';
      $row['regi']=$this->db->getField($this->db->query($sql));
      $row['regiIx']=$row['click']==0?0:round($row['regi']/$row['click']*100,2);

      // 充值
      $sql='SELECT sum(round(money/100,2)) FROM '.jieqi_dbprefix('pay_paylog'). 
          ' WHERE ( payflag="1" AND source="'.$row['qd'].'"  )';
      $row['payall']=$this->db->getField($this->db->query($sql));
      $row['huiIx']=$row['fee']==0?0:round($row['payall']/$row['fee']*100,2); 


      // 三天充值
      $t2=strtotime($row['pdate'].' +3 day')-1;
      $sql='SELECT sum(round(money/100,2)) FROM '.jieqi_dbprefix('pay_paylog'). 
          ' WHERE ( payflag="1" AND source="'.$row['qd'].'" AND rettime<="'.$t2.'"  )';
      $row['payallTh']=$this->db->getField($this->db->query($sql));
      $row['huiThIx']=$row['fee']==0?0:round($row['payallTh']/$row['fee']*100,2); 


      // 七天充值
      $t2=strtotime($row['pdate'].' +7 day')-1;
      $sql='SELECT sum(round(money/100,2)) FROM '.jieqi_dbprefix('pay_paylog'). 
          ' WHERE ( payflag="1" AND source="'.$row['qd'].'" AND rettime<="'.$t2.'"  )';
      $row['payallWe']=$this->db->getField($this->db->query($sql));
      $row['huiWeIx']=$row['fee']==0?0:round($row['payallWe']/$row['fee']*100,2); 

      $ar[]=$row;
    }         
    if ($params['pageShow']){
     
      $sql ='SELECT count(*) FROM '.jieqi_dbprefix('system_qdlist').' q '.
        ' WHERE '.$where.' LIMIT 1 ';
      $this->setVar('totalcount', $this->db->getField($this->db->query($sql)));
      $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $params['limit'], $params['page']);

      $this->jumppage->emptyonepage = $emptyonepage;
      if ($custompage) $this->setVar('custompage', $custompage);
    }
    return $ar;
  }

  /**
   * 下载列表
   * @return [type] [description]
   */
  public function downQdloadList($params){

    $params['orderS']='q.id';
    $params['page']=isset($params['page'])?intval($params['page']):1;
    $params['start']=($params['page'] - 1) * $params['limit'];

    /* 条件 */ 
    $where='(q.uid="'.$_SESSION['jieqiUserId'].'" ';
    if(isset($params['keyword']) && strlen($params['keyword']) > 0){// 搜索 
      $where.=' AND q.qd="'.trim($params['keyword']).'"';
    }

    /* 日期筛选 */
    if( isset($params['t1'],$params['t2']) && strlen($params['t1']) === 10 && strlen($params['t2']) === 10 ){
      $where.=' AND q.pdate>="'.trim($params['t1']).'" AND q.pdate<="'.trim($params['t2']).'" ';
    }

    $where.=')';
    /* 获取数量 */
    $limit=' LIMIT 0,1000';
    /* 排序 */
    $order=' ORDER BY '.$params['orderS'].' '.$params['sort'];
    /* 分组 */
    $groupby=' GROUP BY q.id';

    $sql ='SELECT q.pdate,q.qd,q.wxh,q.wxn,q.fans,q.fee,q.articlename,q.read,sum(d.click) as click,sum(d.pv) pv,o.company,o.feetype,o.feelence FROM '.jieqi_dbprefix('system_qdlist').' q '.
      'LEFT JOIN '.jieqi_dbprefix('tuike_orderqd').' o ON o.id=q.orderqdid '.
      'LEFT JOIN '.jieqi_dbprefix('system_qddata').' d ON d.qd=q.qd '.
      ' WHERE '.$where.$groupby.$order.$limit;
         
    /* 处理数据 */ 
    $str='<table class="tb" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr style="height: 40px"> 
                  <th class="all_border" style="font-size: 16pt; font-family: 宋体;" colspan="21"> 渠道报表 </th> 
                </tr> 
                <tr class="th">
                  <td rowspan="2">时间</td>
                  <td rowspan="2">渠道号</td>
                  <td colspan="4" class="addwidth">账号信息</td>
                  <td colspan="2">成本</td>
                  <td rowspan="2">书名</td>
                  <td colspan="4" class="addwidth">基本参数</td>
                  <td rowspan="2">阅读<br>转化率</td>
                  <td rowspan="2">注册</td>
                  <td colspan="2">总收入</td>
                  <td colspan="2">3天收入</td>
                  <td colspan="2" class="rightborder">7天收入</td>
                </tr>
                <tr class="th">
                  <td>微信名</td>
                  <td>微信号</td>
                  <td>公司</td>
                  <td>粉丝</td>
                  <td>单价</td>
                  <td>总价</td>
                  <td>阅读</td>
                  <td>UV</td>
                  <td>点击</td>
                  <td>PV</td>
                  <td>收入</td>
                  <td>回报比</td>
                  <td>收入</td>
                  <td>回报比</td>
                  <td>收入</td>
                  <td class="rightborder">回报比</td>
                </tr>';

    $sqlres= $this->db->query($sql);
    $ar=array();
    $numRu=0;
    while($row=$this->db->getRow($sqlres)){

      $row['click']=$row['click']?$row['click']:0;
      $row['pv']=$row['pv']?$row['pv']:0;
      $row['uprice']=$row['fans']==0?0:round($row['fee']/$row['fans'],2); // 万粉价

      $row['uvIx']=$row['fans']==0?0:round($row['read']/$row['fans']/10000*100,2); 
      $row['readIx']=$row['read']==0?0:round($row['click']/$row['read']*100,2);
    
      /* 注册用户 */
      $sql='SELECT count(*) FROM '.jieqi_dbprefix('system_users').' WHERE ( source="'.$row['qd'].'" )';
      $row['regi']=$this->db->getField($this->db->query($sql));
      $row['regiIx']=$row['click']==0?0:round($row['regi']/$row['click']*100,2);

      // 充值
      $sql='SELECT sum(round(money/100,2)) FROM '.jieqi_dbprefix('pay_paylog'). 
          ' WHERE ( payflag="1" AND source="'.$row['qd'].'"  )';
      $row['payall']=$this->db->getField($this->db->query($sql));
      $row['huiIx']=$row['fee']==0?0:round($row['payall']/$row['fee']*100,2); 


      // 三天充值
      $t2=strtotime($row['pdate'].' +3 day')-1;
      $sql='SELECT sum(round(money/100,2)) FROM '.jieqi_dbprefix('pay_paylog'). 
          ' WHERE ( payflag="1" AND source="'.$row['qd'].'" AND rettime<="'.$t2.'"  )';
      $row['payallTh']=$this->db->getField($this->db->query($sql));
      $row['huiThIx']=$row['fee']==0?0:round($row['payallTh']/$row['fee']*100,2); 


      // 七天充值
      $t2=strtotime($row['pdate'].' +7 day')-1;
      $sql='SELECT sum(round(money/100,2)) FROM '.jieqi_dbprefix('pay_paylog'). 
          ' WHERE ( payflag="1" AND source="'.$row['qd'].'" AND rettime<="'.$t2.'"  )';
      $row['payallWe']=$this->db->getField($this->db->query($sql));
      $row['huiWeIx']=$row['fee']==0?0:round($row['payallWe']/$row['fee']*100,2); 

      $str.='<tr>
          <td>'.$row['pdate'].'</td>
          <td>'.$row['qd'].'</td>
          <td>'.$row['wxn'].'</td>
          <td>'.$row['wxh'].'</td>
          <td>'.$row['company'].'</td>
          <td>'.$row['fans'].'</td>
          <td>￥'.$row['uprice'].'</td>
          <td>￥'.$row['fee'].'</td>
          <td>'.$row['articlename'].'</td>
          <td>'.$row['read'].'</td>
          <td>'.$row['uvIx'].'</td>
          <td>'.$row['click'].'</td>
          <td>'.$row['pv'].'</td>
          <td>'.$row['readIx'].'%</td>
          <td>'.$row['regi'].'</td>
          <td>'.$row['payall'].'</td>
          <td>'.$row['huiIx'].'%</td>
          <td>'.$row['payallTh'].'</td>
          <td>'.$row['huiThIx'].'%</td>
          <td>'.$row['payallWe'].'</td>
          <td class="rightborder">'.$row['huiWeIx'].'%</td>
        </tr>';
      $numRu++;
    } 
    if( $numRu === 0 )$this->printfail('没有要下载的渠道！');

         
    $str.='</tbody></table>';
    die( '{"status":"OK","msg":"","jumpurl":""}aj||ax'.$str );
    // die( '{"status":"OK","msg":"","jumpurl":""}aj||ax'.iconv('GBK', 'UTF-8//IGNORE',$str) );
    
  }


/*----newRun-----------------------------------------------------------------------------------------------------------------------------*/





















} 
?>