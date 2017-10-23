<?php
 /**================================
  *系统管理模块（sysmanage）
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ==================================*/
ACCESS_GRANT != true && exit("forbiden!");
class sysmanageAction extends Action{
	
    //管理中心
    public function index(){
    	
    }
    
    public function ajaxnowtime(){
    	echo date("Y/m/d - H:i:s",time());
    }
    
    
    //资料
    public function cmdata(){
    	if(!empty($_REQUEST['old']) && !empty($_REQUEST['password']) ){
    		$field['uid'] = $this ->adm['uid'];
    		$field['account'] = $this ->adm['account'];
    	
    		$this->model->setTable ( ADMIN );
    		$this->model->setKey ( 'account' );
    		$uInfo = $this->model->getOneRecordByKey ( $field ['account'] );
    		$ip = $_SERVER["REMOTE_ADDR"];
    		if(sha1( $_REQUEST['old'] .$uInfo['createtime']) == $uInfo ['password']){
    			$field['password'] = sha1( $_REQUEST['password'] .$uInfo['createtime']);
    			$field['uptime'] = 	date("Y-m-d H:i:s",time());
    			$this ->model ->setKey('account');
    			$this ->model ->upRecordByKey($field);
    			 
    			$this->model->adminLogs($field['uid'],"修改成功 IP:".$ip,$field['account']."修改密码",$_REQUEST ['action'],$_REQUEST ['opt']);
    			echo "<script>alert('".$_REQUEST['password']."');location.href='".URL_INDEX."?action=public&opt=loginOut';document.onmousedown=click</script>";
    		}else{
    			$this->model->adminLogs($field['uid'],"原密码错误 IP:".$ip,$field['account']."修改密码",$_REQUEST ['action'],$_REQUEST ['opt']);
    			echo "<script>alert('Old Password Error!');location.href='".URL_INDEX."';document.onmousedown=click</script>";
    		}
    	}else{
    		echo "<script>alert('Error!');location.href='".URL_INDEX."';document.onmousedown=click</script>";
    	}
    }
    
    
    /**
     * @密码认证
     * @param string $str
     * @return boolean
     */
    public function pwdpreg($str){
    	$preg = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/';
    	if(preg_match($preg,$str)){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    
   public function notes(){
   		$uid = $this ->adm['uid'];
   		if($_REQUEST['content']){
   			$field['uid'] = $uid;
            $field['content'] = $_REQUEST['content'];
            $this ->model ->setTable(ADMINNOTES);
            $this ->model ->setKey('uid');
            $this ->model ->upRecordByKey($field);
            ajaxReturn(C('Ok:Update'), 200);
   		}
        $this ->model ->setTable(ADMINNOTES);
        $this ->model ->setKey('uid');
        $data = $this ->model ->getOneRecordByKey($uid);
        if(empty($data)){
        	$field['uid'] = $uid;
            $field['content'] = '个人记事本';
            $this ->model ->setTable(ADMINNOTES);
            $this ->model ->addRecord($field);
            $data['content'] = $field['content'];
        }
        $this ->smarty ->assign('info', $data);
   }
    
    
    //加载左边栏
    public function leftMenu(){
        global $getData;
        $topMenu = $this ->getUserTopMenu();
        $leftMenu = array();
        if(count($topMenu) > 0){
            $topInfo = array();
            if(isset($getData['tid'])){
                 foreach($topMenu as $val){
                     if($val['key'] == $getData['tid'])
                        $topInfo = $val;
                 }              
            }else{
                 $topInfo = $topMenu[0];
            }
            if(isset($topInfo['key'])){
                $leftMenu = $this ->getUserLeftMenu($topInfo);
                $groupid = $_SESSION ['manager']['groupid'];
                
                if($groupid != 1){
                	$sql = "select `grant` from ". ADMINGROUP ." where id = ".$groupid;
                	$info = $this->model->getOne($sql);
					$grant = unserialize($info['grant']);
					//var_dump($grant);
                	foreach ($leftMenu as $k => $v){
                		if(!array_key_exists($v['module'],$grant)){//是否在第一层
                			unset($leftMenu[$k]);
                		}
                	 }
					
                	 foreach ($leftMenu as $k => $v){
                	 	foreach ($v['sonfunc'] as $f => $u ){//echo $u['option']."<br>";
                	 		if(!in_array($u['option'], $grant[$v['module']])){
                	 			unset($leftMenu[$k]['sonfunc'][$f]);
                	 		}
                    	}
                	 }

                }
               
            }else{
                if(isAjax())
                    ajaxReturn( C('Error:AccountUnnormal'), 300 );
            }
        }//var_dump($leftMenu);exit;
        $_SESSION ['manager']['menu'] = $leftMenu;
        $this ->smarty ->assign('theModule', $topInfo);
        $this ->smarty ->assign('topMenu', $topMenu);
        $this ->smarty ->assign('leftMenu', $leftMenu);
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
    
    //获取用户左边菜单栏
    private function getUserLeftMenu($topInfo){
        include(DATA_DIR . 'grantlist.php');
        $leftMenu = array();
        foreach($allGrants as $val){;
            if(floor(($val['key'] - $topInfo['key'])/3000) == 0 && $val['key'] != $topInfo['key'])
                array_push($leftMenu, $val);
        }
        $userLeftMenu = $userLeftMenu2 = $leftMenu; //得到用户权限
        //划分等级
        $data = $this ->divideGrade($userLeftMenu);
        return $data;
    }
    //划分权限等级
    private function divideGrade($userLeftMenu, $data = array()){
        $data = array();
         foreach($userLeftMenu as $val){
            if(fmod(($val['key']), 100) == 0){
                foreach($userLeftMenu as $v){
                    $c = $v['key'] - $val['key'];
                    if(($c) >0 && ($c) <100)
                       $val['sonfunc'][$v['key']] = $v;
                    
                }
                $data[$val['key']] = $val;
            }
        }
        return $data;
    }
    //获取用户顶部导航
    private function getUserTopMenu(){
        include(DATA_DIR . 'grantlist.php');
        $topMenu = array();
        foreach($allGrants as $val){
            if(fmod($val['key'], 1000)==0)
                array_push($topMenu, $val);
        }
        $userTopMenu = $topMenu; //得到用户权限
        return $userTopMenu;
    }
}