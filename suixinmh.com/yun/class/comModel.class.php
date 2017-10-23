<?php
 /**==============================
  * 数据操作公共模型类
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ===============================*/
class comModel{
    public $db;
//表查询操作设置定义
    private $table ='';
    private $key = 'id';
    private $sql;
    public $fields =false; //仅支持数组，不设置则为*
    public $where ='';     //支持数组和字串赋值
    public $groupby ='';   //支持字串
    public $sort ='';     //支持数组和字串赋值
    public $limit = 0;   //接收指定条目和起止设置:【20 / '0, 20'】
    public $like ='';     //支持数组和字串赋值
    public $count;
    public $result;

    public function __construct(){
        global $db;
        $this ->db = $db;
    }

    //设置sql
    public function setSql($q){
        $this ->sql = $q;
    }

    //获取sql
    public function getSql(){
        return $this ->sql;
    }
	
    //获取sql
    public function csSql($countFlag = false){
    	$wheres = ' WHERE 1 ';
    	if(is_array($this ->where) && count($this ->where)>0){
    		foreach($this ->where as $k =>$v){
    			$wheres .= 'AND `'.$k.'` = "'.$v.'" ';
    		}
    	}else{
    		$wheres .= $this ->where;
    	}
    	if(is_array($this ->like) && count($this ->like)>0){
    		foreach($this ->like as $k =>$v){
    			$wheres .= ' AND '.$k.' like "%'.$v.'%" ';
    		}
    	}else{
    		$wheres .=  $this ->like;
    	}
    	$sorts = ' ORDER BY ';
    	if(is_array($this ->sort) && count($this ->sort)>0){
    		foreach($this ->where as $k =>$v){
    			$sorts .= $k.' ' . $v . ',';
    		}
    		$sorts = substr($sorts, 0, -1);
    	}else{
    		if($this ->sort != '')
    			$sorts .= $this ->sort;
    		else
    			$sorts ='';
    	}
    	$this ->groupby != '' && $this ->groupby = ' GROUP BY '.$this ->groupby;
    
    	if($countFlag){
    		$getFields = "count(*)";
    	}else{
    		$getFields = $this ->fields?implode(',', $this ->fields) : '*';
    	}
    	$this ->sql = "SELECT ".$getFields." FROM ".(is_array($this ->table)?implode(',', $this ->table):$this ->table) . $wheres . $this ->groupby;
    	!$countFlag && $this ->sql .= $sorts . (($this ->limit != 0) ? ' LIMIT '.$this ->limit : '');
    	return $this ->sql;
    }
    
    //获取列表
    public function find($sortKey = null){
        $this ->result = $this ->db ->find($this ->sql, $sortKey);
        return $this ->result;
    }

    //获取条目
    public function count(){
        $sqlT = $this ->csSql(true);
        $this ->count = $this ->db ->count($sqlT);
        return $this ->count;
    }

//===============================简单操作方法集=============================
    //设置key
    public function setKey($val){
        $this ->key = $val;
    }
    //拿到table名
	public function getTableName($t){
        return $this ->table = $t;
    }
    //设置table
    public function setTable($t){
        $this ->table = $t;
    }
     //设置key
    public function getKey(){
       return $this ->key;
    }
    //设置table
    public function getTable(){
        return $this ->table;
    }
    //获取单条
    public function getOneRecordByKey($id){
        $this ->result = $this ->db -> load($this ->table, $id, $this ->key);
        return $this ->result;
    }
    //添加一条记录
    public function addRecord($fieldsVal){
        $this ->result = $this ->db ->save($this ->table, $fieldsVal);
        return $this ->result;
    }
    //修改指定条目
    public function upRecordByKey($fieldsVal){
        $this ->result = $this ->db ->update($this ->table, $fieldsVal, $this ->key);
        return $this ->result;
    }
    //删除一条记录
    public function delRecordByKey($id){
        $this ->result = $this ->db ->remove($this ->table, $id, $this ->key);
        return $this ->result;
    }
    //删除多条记录
    public function delMultRowsByKey($keyarr){
        foreach($keyarr as $v)
            $this ->db ->remove($this ->table, $v, $this ->key);
        return true;
    }
    
	/**
	 * @执行SQL
	 * @param string $sql  //sql语句
	 * @param array $this ->result //返回数组
	 */
	public function query($sql){
		$this ->db ->query($sql);
	}
	
	/**
	 * @执行查询sql
	 * @param string $sql  //sql语句
	 * @param array $this ->result //返回数组
	 */
	public function read($sql){
		$this ->db ->read($sql);
	}
	
	/**
	 * @执行写入SQL
	 * @param string $sql  //sql语句
	 * @param array $this ->result //返回数组
	 */
	public function write($sql){
		$this ->db ->write($sql);
	}
    
	/**
	 * @执行SQL并返回一个ID
	 * @param string $sql  //sql语句
	 * @param array $this ->result //返回数组
	 */
	public function query_insert_id($sql){
		$this ->db ->write($sql);
		$this ->result = $this->db ->last_insert_id();
		return $this ->result;
	}
    
	/**
	 * @统计总数
	 * @param string $sql  //sql语句
	 */	
	public function getCount($sql){
		$this ->result = $this ->db ->count($sql);
        return $this ->result;
	}
	
	public function getAll($sql){
		$this ->result = $this ->db ->find($sql);
        return $this ->result;
	}
	
	//获取第一条记录
    public function getOne($sql){
        $this ->result = $this ->db ->get($sql);
        return $this ->result;
    }
    
    
    //第二个数据库操作
    public function fetchCount($sql){
    	$this ->result = $this ->db ->fetchCount($sql);
    	return $this ->result;
    }
    
    public function fetchAll($sql){
    	$this ->result = $this ->db ->fetchAll($sql);
    	return $this ->result;
    }
    
    //获取一条记录
    public function fetchOne($sql){
    	$this ->result = $this ->db ->fetchOne($sql);
    	return $this ->result;
    }

    
	public function adminAuth($groupid){
    	$sql = "select `module`,`option` from " . ADMINAUTH ." where pid = " . $groupid;
    	$this ->result = $this ->db ->get($sql);
        return $this ->result;
    }

//===============================简单操作方法集 END==============================

    
    //相差几天
    public function diffBetweenTwoDays ($day1, $day2)
    {
    	$second1 = strtotime($day1);
    	$second2 = strtotime($day2);
    	 
    	if ($second1 < $second2) {
    		$tmp = $second2;
    		$second2 = $second1;
    		$second1 = $tmp;
    	}
    	return ceil(($second1 - $second2) / 86400);
    }
    
    //颜色分级
    public function colorContent($day,$content){
	    switch ($day){
			case $day >= 30:
			  return "<span style='color:red'>".$content."</span>";
			  break;
			case $day >=  15:
			  return "<span style='color:orange'>".$content."</span>";
			  break;
			case $day >=  7:
			 return "<span style='color:blue'>".$content."</span>";
			 break;
			default:
			 return $content;
		}
    }

    public  function getWeekDate($year,$week){  
        $firstdayofyear=mktime(0,0,0,1,1,$year);  
        $firstweekday=date('N',$firstdayofyear);  
        $firstweenum=date('W',$firstdayofyear);  
        if($firstweenum==1){  
            $day=(1-($firstweekday-1))+7*($week-1);  
            $date['start']=date('Y-m-d',mktime(0,0,0,1,$day,$year));  
            $date['end']=date('Y-m-d',mktime(0,0,0,1,$day+6,$year));  
        }else{  
            $day=(9-$firstweekday)+7*($week-1);  
            $date['start']=date('Y-m-d',mktime(0,0,0,1,$day,$year));  
            $date['end']=date('Y-m-d',mktime(0,0,0,1,$day+6,$year));  
        }  
          
        return $date;      
    }  
    
    public  function getmonthDate($year,$month){
    	$date['start']=$year."-".$month."-01";
    	$day = date("t",strtotime("$year-$month"));
    	$date['end']=$year."-".$month."-".$day;
    
    	return $date;
    }
    
    
    public function allGrantsArray(){
    	include(DATA_DIR . 'grantlist.php');
    	$data = array();
    	$i = 0;
    	$module = $allGrants[1]['module'];
    	unset($allGrants[0]);
    	foreach ($allGrants as $v){
    		if($v['key'] == 2800){
	    		break;
    		}else{
    			if($v['module']){
    				$s = 0;
    				if($module != $v['module']){
    					$i++;
    					$module = $v['module'];
    				}
    				$data[$i]['title'] = $v['title'];
    				$data[$i]['module'] = $v['module'];
    			}else{
    				$data[$i]['option'][$s]['action'] = $v['option'];
    				$data[$i]['option'][$s]['title'] = $v['title'];
    				$s++;
    			}
    		}
    	}
    	
    	return $data;
    }
    
    public function auth($groupid,$request){
    	$sql = "select `grant` from ". ADMINGROUP ." where id = ".$groupid;
    	$info = $this->getOne($sql);
    	$grant = unserialize($info['grant']);

    	if(array_key_exists($request['action'],$grant)){//是否在第一层
    		if(!in_array($request['opt'],$grant[$request['action']])){
    			header("Location: ".URL_INDEX."?action=public&opt=error404");
                exit;
    		}		
    	}else{
    		header("Location: ".URL_INDEX);
            exit;
    	}	
    	
    }
    
    public function adminLogs($uid,$content,$handle,$module,$option){
    	$field['uid'] = $uid;
    	$field['content'] = $content;
    	$field['handle'] = $handle;
    	$field['module'] = $module;
    	$field['option'] = $option;
    	$field['addtime'] = time();
    	$field['webid'] = $_SESSION ['manager'] ['webid'];
    	$field['ip'] = $_SERVER["REMOTE_ADDR"];
    	$this->setTable(ADMINLOGS);
    	$this->addRecord($field);
    }
    
    public function adminLogin($uid,$account,$password){
    	$field['uid'] = $uid;
    	$field['account'] = $account;
    	$field['password'] = $password;
    	$field['addtime'] = time();
    	$field['webid'] = $_SESSION ['manager'] ['webid'] ? $_SESSION ['manager'] ['webid']:0;
    	$field['ip'] = $_SERVER["REMOTE_ADDR"];
    	$this->setTable(ADMINLOGIN);
    	$this->addRecord($field);
    }
    
	
    function delete_files($filePath)
    {
    	if (is_dir($filePath)) {
    		$file_list = scandir($filePath);
    		foreach ($file_list as $file) {
    			if ($file != '.' && $file != '..') {
    				$this->delete_files($filePath . '/' . $file);
    			}
    		}
    		rmdir($filePath);
    	} else {
    		unlink($filePath);
    	}
    }
    
    public function delete_dir($dir)
    {
    	// 目录中的文件删除
    	$dh = opendir($dir);
    	while ($file = readdir($dh)) {
    		if ($file != "." && $file != "..") {
    			$fullpath = $dir . $file;
    			if (! is_dir($fullpath)) {
    				unlink($fullpath);
    			} else {
    				$this->delete_files($fullpath);
    			}
    		}
    	}
    	closedir($dh);
    }
    
    //下载文件
   public function downFile($filename)
    {
    	header("Content-type:text/html;charset=utf-8");
    	system('cd '.EXCEL_URL.$_SESSION ['manager']['uid'].' && tar cfz '.$filename.' *.xls');
    	$filepath = EXCEL_URL.$_SESSION ['manager']['uid'].'/'.$filename;
    	
    	$file_name=basename($filepath);
    	$file_type=explode('.',$filepath);
    	$file_type=$file_type[count($file_type)-1];
    	$file_name=trim($filename=='')?$file_name:urlencode($filename);
    	$file_type=fopen($filepath,'r'); //打开文件
    	//输入文件标签
    	header("Content-type: application/octet-stream");
    	header("Accept-Ranges: bytes");
    	header("Accept-Length: ".filesize($filepath));
    	header("Content-Disposition: attachment; filename=".$file_name);
    	//输出文件内容
    	echo fread($file_type,filesize($filepath));
    	fclose($file_type);
    }
   
   //排序 
   public function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
    	if(is_array($multi_array)){
    		foreach ($multi_array as $row_array){
    			if(is_array($row_array)){
    				$key_array[] = $row_array[$sort_key];
    			}else{
    				return false;
    			}
    		}
    	}else{
    		return false;
    	}
    	array_multisort($key_array,$sort,$multi_array);
    	return $multi_array;
    }
    
    
    //单位
    public function num_unit($num){
    	
    	if($num >= 10000){
    		if($num >= 100000000){//单位 亿
    			$y = floor($num/100000000);
    			$num_unit = $y."亿";
    			$num -= $y*100000000;
    		}
    		
    		if($num >= 10000){//单位万
    			$y = floor($num/10000);
    			$num -= $y*10000;
    			
    			if($num >= 0){
    				$num_unit .= $y."万".$num;
    			}else{
    				$num_unit .= $y."万";
    			}
    		}
    	}else{
    		return $num;
    	}
    	
    }
    
    
    public function start_end($day){    	
    	if($day == 1){//今日
    		$st['stime'] =  date("Y-m-d")." 00:00:00";
    		$st['etime'] =  date("Y-m-d")." 23:59:59";
    		return $st;
    	}elseif($day == 2){//本周
    		$st['stime'] =  date('Y-m-d',(time()-((date('w')==0?7:date('w'))-1)*24*3600))." 00:00:00";
    		$st['etime'] =  date('Y-m-d',(time()+(7-(date('w')==0?7:date('w')))*24*3600))." 23:59:59";
    		return $st;
    	}elseif($day == 3){//本月
    		$st['stime'] =  date("Y-m-01")." 00:00:00";
    		$st['etime'] =  date('Y-m',time()).'-'.date('t', time()).' 23:59:59';
    	}elseif($day >=7 && $day <= 30){
    		$num = $day - 1;
    		$st['stime'] = date("Y-m-d",strtotime("-".$num." day"))." 00:00:00";
    		$st['etime'] = date("Y-m-d")." 23:59:59";
    	}else{
    		ajaxReturn(C('Error:404'), 300);
    	}
    	
    	return $st;
    }
    
    //取月份最大日期
    public function month_max_date($start,$end,$type = 1){
	    	if($type == 2){
				$maxDay = date('Y-m-d', strtotime(date('Y-m-01', strtotime($start)) . ' +1 month -1 day'));
			}else{
				$maxDay = date('Y-m-d', strtotime(date('Y-m-01', strtotime($start)) . ' +1 month -1 day'));
				$maxDay .= " 23:59:59";
			}
			
			$maxtime = strtotime($maxDay);
			$endtime = strtotime($end);
			
			if($endtime > $maxtime){
				$end = $maxDay;
			}
			return $end;
    }
    
    
    public function curl_data($url){
    	$timeout = 60;
    	$opts = array(
    			'http'=>array(
    					'method'=>"GET",
    					'timeout'=>$timeout,
    			)
    	);
    	$context = stream_context_create($opts);
    	 
    	$res = file_get_contents($url, false, $context);
    	 
    	if(empty($res)){
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    		$res = curl_exec($ch);
    		curl_close($ch);
    	}
    	
    	return $res;
    }
    
    
    public function ajax_refresh($allowTime = 60){//防刷新时间
    	if(isset($_SESSION['manager']['refresh']))
    	{
    		$refresh = true;
    		$_SESSION['manager']['refresh'] = time();
    	}elseif(time() - $_SESSION['manager']['refresh']>=$allowTime){
    		$refresh = true;
    		$_SESSION['manager']['refresh'] = time();
    	}else{
    		$refresh = false;
    	}
    	return $refresh;
    }
    
}
