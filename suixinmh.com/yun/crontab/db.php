<?php
class db{
	
	private $host;
	private $user;
	private $pwd;
	private $data;
	private $con;
	
	function __construct($host,$user,$pwd,$data){
		$this->host=$host;
		$this->user=$user;
		$this->pwd=$pwd;
		$this->data=$data;
		$this->connect();
	}
	
	private function connect(){	
		try {
			$this->con = new PDO('mysql:host='.$this->host.';port=3306;dbname='.$this->data, $this->user, $this->pwd,array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}	
	}
	
	function query($sql){
		return $this->con->query($sql);
	}
	
	function insert($sql){
		$this->con->query($sql);
		return $this->con->lastInsertId(); 
	}
	
	/**
	 * 执行 SQL 语句, 返回结果的第一条记录(是一个对象).
	 */
	 function fetchOne($sql){
		$result = $this->query($sql);
        if($row = $result->fetch()){
            return $row;
        }else{
            return false;
        }
	}
	
	/**
	 * 返回查询结果集, 以 key 为键组织成关联数组, 每一个元素是一个对象.
	 * 如果 key 为空, 则将结果组织成普通的数组.
	 */
	 function fetchAll($sql, $key=null){
		$data = array();
        $result = $this->query($sql);
        while($row = $result->fetch()){
            if(!empty($key)){
                $data[$row->{$key}] = $row;
            }else{
                $data[] = $row;
            }
        }
        return $data;
	}
	
	function fetchCount($sql){
		$total = $this->query($sql);
     	if($row = $total->fetch()){
     		return (int)$row[0];
        }else{
            return 0;
        }
	}
	
	/**
     * 写入数据库.
     */
    function write($sql){
    	return $this->con->exec($sql);
    }
	
	//关闭数据库
	function close(){
		$this->con = NUll;
	}
	
	function curl_data($url){
		$opts = array(
				'http'=>array(
						'method'=>"GET",
						'timeout'=>3,
				)
		);
		$context = stream_context_create($opts);
		$res = @file_get_contents($url, false, $context);
		if(empty($res)){
			$ch = curl_init();
			$timeout = 3;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$res = curl_exec($ch);
			curl_close($ch);
		}
		return $res;
	}
	
	//把组合的字符串解析为数组
	function parse_query_string($str)
	{
		header ( 'Content-Type:text/html; charset=utf-8' );
		$op = array();
		$pairs = explode("&", $str);
		foreach ($pairs as $pair)
		{
			list($k, $v) = explode("=", $pair);
			$op[$k] = urldecode($v);
		}
		return $op;
	}
	
	
	function check_input($value)
	{
		// 去除斜杠
		if (get_magic_quotes_gpc()){
		  $value = stripslashes($value);
		}
		// 如果不是数字则加引号
		if (!is_numeric($value)){
		  $value = "'" . $value . "'";
		}
		return $value;
	}
	
}


?>

