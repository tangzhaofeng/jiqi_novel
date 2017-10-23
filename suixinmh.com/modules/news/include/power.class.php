<?php
/*
    *通用权限处理类
	[Cms News] (C) 2009-2010 Cms Inc.
	$Id: power.class.php 12398 2010-05-6 09:55:38Z huliming $
*/

if(!defined('IN_JQNEWS')) {
	exit('Access Denied');
}

class Power extends JieqiObject{
	//保存所有变量
	var $vars = array();
	var $power = array(); //返回的权限结果数组
	
	function Power($key = '', $value = '', $isreturn=true){
	    if($key){
		    $this->addPower($key, $value);
			$this->checkPower($key, $isreturn);
		}
	}
	
	/**
	 * 检查一条权限
	 * 
	 * @param      string     $key 权限标识
	 * @access     public
	 * @return     bool
	 */
    function checkPower($key, $isreturn = true, $isadmin = false){
	     global $_SGLOBAL, $jieqiUsersStatus, $jieqiUsersGroup;
		 $this->power[$key] = 0;
		 $groups = array();
		 $isadmin = false;
		 if(JIEQI_GROUP_ADMIN!=$jieqiUsersStatus){
			 $powers = $this->getPower($key, 'a');
			 if(!isset($powers['groups'])) $groups['groups'] = $powers;
			 else $groups = $powers;
			 unset($powers);
		 }
		 if((defined('IN_ADMIN') && IN_ADMIN) || $isadmin) $isadmin = true;
		 $this->power[$key] = jieqi_checkpower($groups, $jieqiUsersStatus, $jieqiUsersGroup, $isreturn, $isadmin);
		 return $this->power[$key];
	}
	
	/**
	 * 检查所有权限
	 * 
	 * @param      array      $array 所有权限数据
	 * @access     public
	 * @return     array
	 */
	function checkPowers($array = array()){
	     if($array){
			 foreach($array as $k=>$v){
				 $this->addPower($k, $v, 'a');
				 $this->checkPower($k, true);
			 }
		 }
		 return $this->power;
	}
	
	/**
	 * 添加一条权限数组
	 * 
	 * @param      string     $key 权限标识
	 * @param      mixed      $value  权限值
	 * @param      array      $format 格式化权限值[a=array,s=string]
	 * @param      string     $split  格式化权限值分隔符
	 * @access     public
	 * @return     void
	 */
	function addPower($key, $value, $format = 'a', $split = ','){
	     if(isset($key)){
		     switch (strtolower($format)) {
			      case 's':
				       if(is_array($value)) $value = implode($split, $value);
				  default:
				       if(!is_array($value)) $value = explode($split, $value);
				       
			 }
		 }
	     $this->setVar($key, $value);
	}
	
	/**
	 * 取得一条权限
	 * 
	 * @param      string     $key 权限标识
	 * @param      array      $format 格式化权限值[a=array,s=string]
	 * @access     public
	 * @return     bool
	 */
	function getPower($key, $format = 'a', $split = ','){
		if (isset($this->vars[$key])) {
			if($format){
				switch (strtolower($format)) {
					case 's':
						if(!is_array($this->vars[$key])) return $this->vars[$key];
						else return implode($split, $this->vars[$key]);
					default:
						if(is_array($this->vars[$key])) return $this->vars[$key];
						else return explode($split, $this->vars[$key]);
				}
			}else return $this->vars[$key];
		}else{
			return false;
		}
	}	

	/**
	 * 取得所有权限列表
	 * 
	 * @param      void
	 * @access     public
	 * @return     array
	 */
	function getVars(){
		return $this->vars;
	}
		
	/**
	 * 取消权限标识
	 * 
	 * @param      string     $key 权限标识
	 * @access     public
	 * @return     void
	 */	
	function clearPower($key = ''){
		if(!$key) $this->vars=array();
		else $this->vars[$key]=array();
	}
}
?>