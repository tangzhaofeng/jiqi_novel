<?php
 /**================================
  *系统管理模块(admin)
  * @author Listen
  * @email listen828@vip.qq.com
  * @version 1.0 data
  * @package 游戏大数据分析总后台
 ==================================*/
ACCESS_GRANT != true && exit("forbiden!");
class adminAction extends Action{
	
	public function __construct(){
        global $mUser, $smarty;
        $this ->model = new comModel;
        $this ->smarty = $smarty;
        $this ->adm = $mUser;

		if($_SESSION ['manager']['groupid'] != 1){
	        header("Location: ".URL_INDEX."?action=public&opt=error404");
	        exit;
	    }
	    $this->sqlWhere = " where 1 = 1 ";
	    
	    include DATA_DIR.'config.php';
	    
	    $mUser['module'] = $_REQUEST['action'];
	    $mUser['option'] = $_REQUEST['opt'];
	    
	    $this->smarty->assign ( 'pagekey', $pagekey );
	    $this->smarty->assign ( 'maxDate', date("Y-m-d") );
	    
	    $this->smarty->assign ( 'request', $_REQUEST );
	    $this ->smarty->assign('mUser', $mUser);
    }  
    
    //用户组
    public function groupList(){
    	$where = $this->sqlWhere;
        $sql = "select * from " . ADMINGROUP.$where;
        $res = $this ->model ->getAll($sql);

        $allGrantsArray = $this ->model ->allGrantsArray();
        
        foreach ($res as $k => $v){
        	$sql = "select `name` from " . ADMIN.$where ." and groupid = ".$v['id'];
        	$result= $this ->model ->getAll($sql);
        	$users = "";
        	foreach ($result as $val){
        		$users .= $val['name'].",";
        	}
        	$res[$k]['users'] = substr($users,0,-1);
        	$grant = unserialize($v['grant']);
        	
        	if(is_array($grant)){
        		$userGrants = $allGrantsArray;//默认所有
        		foreach ($userGrants as $q=> $w){
        			if(array_key_exists($w['module'],$grant)){//是否在第一层
        				foreach ($w['option'] as $e => $r){//是否在第二层
        					if(in_array($r['action'],$grant[$w['module']])){
        						$userGrants[$q]['option'][$e]['yes'] = 1;//加标识
        					}
        				}
        			}
        		}
        		$res[$k]['userGrants'] = $userGrants;//赋予
        	}
        	
        }
        //var_dump($res);
        $this ->smarty ->assign('allGrantsArray', $allGrantsArray);
        $this ->smarty ->assign('data', $res);
    }
    //用户
    public function userList(){
    	$where = $this->sqlWhere;
        $sql = "select * from " . ADMIN .$where." order by groupid,uid ASC";
        $res = $this ->model ->getAll($sql);
        foreach ($res as $k => $v){
        	$sql = "select title from ". ADMINGROUP .$where." and id = ".$v['groupid'];
        	$resinfo = $this->model->getOne($sql);
        	$res[$k]['title'] = $resinfo['title'];
        }
        
        $sql = "select * from ". ADMINGROUP.$where;
        $result = $this->model->getAll($sql);
        $this ->smarty ->assign('grouplist', $result);
        
        $this ->smarty ->assign('data', $res);
    }
    
    /**
     * @添加用户界面
     */
    public function adduser(){
    	$where = $this->sqlWhere;
    	$sql = "select * from ". ADMINGROUP.$where;
        $result = $this->model->getAll($sql);
        $this ->smarty ->assign('data', $result);
    }
    
    /**
     * @添加用户
     */
    public function insertuser(){
    	$where = $this->sqlWhere;
    	$getData = $_REQUEST;
        $sql = "select `uid` from ". ADMIN .$where." and account = '".$getData['account']."'";
        $checkinfo = $this->model->getOne($sql);
    	if(empty($checkinfo) && !empty($getData['account'])){
    		$field = array();
	        $field['account'] = $getData['account'];
	        $field['createtime'] = date("Y-m-d H:i:s",time());
	        $field['password'] =sha1( $getData ['password'] .$field['createtime']);
	        $field['groupid'] = $getData['groupid'] > 1 ?$getData['groupid'] : 0 ;
	        $field['name'] = $getData['name'];
	        $field['phone'] = $getData['phone'];
	        $field['email'] = $getData['email'];
	        $field['webid'] = $_SESSION ['manager']['webid'];
	        $this->model->setTable(ADMIN);
	        $res = $this->model ->addRecord($field);
	        $this->model->adminLogs($_SESSION ['manager'] ['uid'],"新增用户:".$field['account']."用户组:".$field['groupid'],"新增:".$field['name'],$_REQUEST ['action'],$_REQUEST ['opt']);
	        header("Location: ".URL_INDEX."?action=admin&opt=userList");
    	}else{
    		header("Location: ".URL_INDEX."?action=admin&opt=userList");
    	}
        
    }
    
    /**
     * @编辑用户
     */
    public function edituser(){
    	$where = $this->sqlWhere;
    	$getData = $_POST;
        if($getData['account']){
        	$sql = "select `uid` from ". ADMIN .$where." and uid = ".$getData['uid'];
        	$info = $this->model->getOne($sql);
        	if($info['uid'] == $getData['uid']){
	            $field = array();
	            $field['uid'] = $getData['uid'];
	            if(!empty($getData['password'])){
	                $field['password'] = sha1( $getData ['password'] .$getData['createtime']);
	            }
	            $field['name'] = $getData['name'];
	            $field['groupid'] = $getData['groupid'];
	            $field['uptime'] = 	date("Y-m-d H:i:s",time());
	            $this ->model ->setTable(ADMIN);
	            $this ->model ->setKey('uid');
	            $this ->model ->upRecordByKey($field);
	            $this->model->adminLogs($_SESSION ['manager'] ['uid'],"编辑用户:".$field['uid']." ".$field['account']."用户组:".$field['groupid'],md5($getData['password']),$_REQUEST ['action'],$_REQUEST ['opt']);
	        }
            
            header("Location: ".URL_INDEX."?action=admin&opt=userList");
        }else{
        	header("Location: ".URL_INDEX."?action=admin&opt=userList");
        }
    }
    
    /**
     * @删除用户
     */
    public function deluser(){
    	$where = $this->sqlWhere;
    	if($_REQUEST['uid']>1){
            $sql = "DELETE FROM ".ADMIN.$where." and `uid` = ".$_REQUEST['uid'];
            $this ->model ->query($sql);
            $this->model->adminLogs($_SESSION ['manager'] ['uid'],"删除用户:".$_REQUEST['uid'],"",$_REQUEST ['action'],$_REQUEST ['opt']);
            header("Location: ".URL_INDEX."?action=admin&opt=userList");
    	}else{
    		header("Location: ".URL_INDEX."?action=admin&opt=userList");
    	}
    }
    
    /**
     * @添加权限组界面
     */
    public function addgroup(){
    	include 'data/grantlist.php';

    	$this ->smarty ->assign('data', $allGrants);
    }
    
    /**
     * @添加权限组
     */
    public function insertgroup(){
    	$array = $_REQUEST;
    	if(!empty($array['title'])){
	    	$field['title'] = $array['title'];
	    	unset($array['action']);
	    	unset($array['opt']);
	    	unset($array['title']);
	    	$field['grant'] = serialize($array);
	    	$field['webid'] = $_SESSION ['manager']['webid'];
	    	$this->model->setTable(ADMINGROUP);
	    	$res = $this->model ->addRecord($field);
	    	$this->model->adminLogs($_SESSION ['manager'] ['uid'],"添加权限组".$field['title'],"成功",$_REQUEST ['action'],$_REQUEST ['opt']);
	    	header("Location: ".URL_INDEX."?action=admin&opt=groupList");
   		}else{
   			header("Location: ".URL_INDEX."?action=admin&opt=groupList");
   		}
    }
    
    
	function isarray($array){
	    static $result=array();
	    for($i=0;$i<count($array);$i++){
		    if(is_array($array[$i])){
		    	$this->isarray($array[$i]);
	    	}else{ 
	    		$result[]=$array[$i];
	    	}	
	    }
	    return $result;
	}
	    
    
    /**
     * @编辑权限组
     */
    public function editgroup(){
    	$where = $this->sqlWhere;
    	$array = $_REQUEST;
    	if($array['id'] > 1){
    		$sql = "select `id` from ". ADMINGROUP .$where." and id = ".$array['id'];
    		$info = $this->model->getOne($sql);
    		if($info['id'] == $array['id']){
	    		$field['id'] = $array['id'];
	    		$field['title'] = $array['title'];
	    		unset($array['action']);
	    		unset($array['opt']);
	    		unset($array['id']);
	    		unset($array['title']);
	    		$field['grant'] = serialize($array);
	    		$this ->model ->setTable(ADMINGROUP);
	    		$this ->model ->setKey('id');
	    		$this ->model ->upRecordByKey($field);
	    		$this->model->adminLogs($_SESSION ['manager'] ['uid'],"修改权限组".$field['id'],$field['title'],$_REQUEST ['action'],$_REQUEST ['opt']);
    		}
    		header("Location: ".URL_INDEX."?action=admin&opt=groupList");
    	}else{
    		header("Location: ".URL_INDEX."?action=admin&opt=groupList");
    	}   	
    	
    }
    
    /**
     * @删除权限组
     */
    public function delgroup(){
    	$where = $this->sqlWhere;
    	if($_REQUEST['id']>1){
    		$this ->model ->setTable(ADMIN);
            $this ->model ->setKey('groupid');
            $info = $this ->model ->getOneRecordByKey($_REQUEST['id']);
    		
            if(empty($info)){
            	$sql = "DELETE FROM ".ADMINGROUP.$where." and `id` = ".$_REQUEST['id'];
            	$this ->model ->query($sql);
	            $this->model->adminLogs($_SESSION ['manager'] ['uid'],"删除权限组".$_REQUEST['id'],"成功",$_REQUEST ['action'],$_REQUEST ['opt']);
	            header("Location: ".URL_INDEX."?action=admin&opt=groupList");
            }else{
            	header("Location: ".URL_INDEX."?action=admin&opt=groupList");
            }
    	}
    }
    

    public function handleLogs(){
    	$where = $this->sqlWhere;
    	$startime = isset ( $_REQUEST ['from'] ) ? $_REQUEST ['from'] : '';
    	$endtime = isset ( $_REQUEST ['to'] ) ? $_REQUEST ['to'] : '';
    	 
    	$uid = isset ( $_REQUEST ['uid'] ) ? $_REQUEST ['uid'] : '';
    	$module = isset ( $_REQUEST ['module'] ) ? $_REQUEST ['module'] : '';
    	$option = isset ( $_REQUEST ['option'] ) ? $_REQUEST ['option'] : '';
    	$ip = isset ( $_REQUEST ['ip'] ) ? $_REQUEST ['ip'] : '';
    	$numPerPage = isset ( $_REQUEST ['numPerPage'] ) ? $_REQUEST ['numPerPage'] : '20';
    	
    	if($startime){
    		$stime = strtotime ( $startime . " 00:00:00" );
    		$etime = strtotime ( $endtime . " 23:59:59" );
    		$where .= " and time >= " . $stime . " and time <= " . $etime;
    	}
    	 
    	if($uid >= 1){
    		$where .= " and uid = ".$uid;
    	}
    	 
    	if($module){
    		$where .= " and module = '".$module."'";
    	}
    	
    	if($option){
    		$where .= " and option = '".$option."'";
    	}
    	 
    	if($ip){
    		$where .= " and ip = '".$ip."'";
    	}
    	 
    	$page ['totalcount'] = $this->model->getCount ( "select count(*) from `".ADMINLOGS."` " . $where );
    	$page ['page'] = intval ( $_REQUEST ['pageNum'] ) ? $_REQUEST ['pageNum'] : 1;
    	$page ['totalpage'] = ceil ( $page ['totalcount'] / $numPerPage );
    	$page ['totalpage'] < 1 && $page ['totalpage'] = 1;
    	$page ['page'] > $page ['totalpage'] && $page ['page'] = $page ['totalpage'];
    	 
    	$sql = "select * from `".ADMINLOGS."` " . $where . " order by `addtime` desc LIMIT " . ($page ['page'] - 1) * $numPerPage . ', ' . $numPerPage;
    	$data = $this->model->getAll ( $sql );//echo $sql;exit;
    	 
    	foreach ( $data as $key => $v ) {
    		$sql = "select * from ". ADMIN ." where uid = ".$v['uid'];
    		$resinfo = $this->model->getOne($sql);
    		$data [$key] ['uid'] = $v['uid']."-".$resinfo['account']."-".$resinfo['name'];
    		$data [$key] ['addtime'] = date("m-d H:i:s",$v['addtime']);
    	}
    	 
    	$this->smarty->assign ( 'startime', $startime );
    	$this->smarty->assign ( 'endtime', $endtime );
    	$this->smarty->assign ( 'data', $data );
    	$this->smarty->assign ( 'page', $page );
    	$this->smarty->assign ( 'numPerPage', $numPerPage );
    }

    
    public function loginLogs(){
    	$where = $this->sqlWhere;
    	$startime = !empty ( $_REQUEST ['from'] ) ? $_REQUEST ['from'] : date("Y-m-d",strtotime('-3 day')). " 00:00:00" ;
    	$endtime = !empty ( $_REQUEST ['to'] ) ? $_REQUEST ['to'] : date("Y-m-d"). " 23:59:59" ;
    	
    	$account = isset ( $_REQUEST ['account'] ) ? $_REQUEST ['account'] : '';
    	$ip = isset ( $_REQUEST ['ip'] ) ? $_REQUEST ['ip'] : '';
    	
    	$stime = strtotime ( $startime);
    	$etime = strtotime ( $endtime);
    	$where .= " and addtime >= " . $stime . " and addtime <= " . $etime;

    	if($account){
    		$where .= " and account = '".$account."'";
    	}
    	
    	if($ip){
    		$where .= " and ip = '".$ip."'";
    	}
    	
    	$page['count'] = $this->model->getCount ( "select count(*) from `".ADMINLOGIN."` " . $where );
    	if($page['count']>0){
	    	$page['pageNum'] = $_REQUEST['pageNum'] > 1 ? intval($_REQUEST['pageNum']) : 1;
	    	$page['numPerPage'] = $_REQUEST['numPerPage'] > 1 ? intval($_REQUEST['numPerPage']) : 20;
	    	
	    	$page['total'] = ceil($page['count']/$page['numPerPage']);
	    	$page['total'] < 1 && $page['total'] = 1;
	    	$page['pageNum'] > $page['total'] && $page['pageNum'] = $page['total'];
	    	
	    	$sql = "select * from `".ADMINLOGIN."` " . $where . " order by `addtime` desc limit " . ($page['pageNum'] - 1) * $page['numPerPage'] . ', ' . $page['numPerPage'];
	    	$data = $this->model->getAll ( $sql );//echo $sql;exit;
	    	include(ROOT . 'crontab/IP.class.php');
	    	$ip_fun = new IP();
	    	foreach ( $data as $key => $v ) {
	    		$data [$key] ['addtime'] = date("m-d H:i:s",$v['addtime']);
    			$address= $ip_fun->find($v['ip']);
    			$data[$key]['address'] = $address[1];
	    	}
    	}
    	$this->smarty->assign ( 'startime', $startime );
    	$this->smarty->assign ( 'endtime', $endtime );
    	$this->smarty->assign ( 'data', $data );
    	$this->smarty->assign ( 'page', $page );
    }
    
    
}