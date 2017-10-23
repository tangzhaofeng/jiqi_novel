<?php
 /**================================
  * 数据库操作工具类
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 ==================================*/
class Mysql{
    private $conn;
    private $conn2;

    public function __construct($c,$c2){
        $this->conn = new PDO('mysql:host='.$c['host'].';port='.$c['port'].';dbname='.$c['dbname'], $c['username'], $c['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"))
         or die('connect db error');
        
        $this->conn2 = new PDO('mysql:host='.$c2['host'].';port='.$c2['port'].';dbname='.$c2['dbname'], $c2['username'], $c2['password'],array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"))
         or die('connect db error');
    }


    public function query($sql){
        $result = $this->conn ->query($sql);
        return $result;
    }
	
    public function read($sql){
    	$result = $this->conn2 ->query($sql);
    	return $result;
    }
    
    /**
     * 写入数据库.
     */
    public function write($sql){
    	$result = $this->conn->exec($sql);
    	return $result;
    }
    
    public function edit($sql){
    	$result = $this->conn2->exec($sql);
    	return $result;
    }
    
    /**
     * 执行 SQL 语句, 返回结果的第一条记录(是一个对象).
     */
    public function get($sql){
        $result = $this->query($sql);//echo $sql;
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
    public function find($sql, $key=null){
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

    
    /**第二数据库操作
     * 执行 mysql_query 读数据库.
     */
    public function fetch($sql){
    	$result = $this->conn2->query($sql);
    	return $result;
    }
    
    /**
     * 执行 SQL 语句, 返回结果的第一条记录(是一个对象).
     */
    public function fetchOne($sql){
    	$result = $this->fetch($sql);//echo $sql;
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
    public function fetchAll($sql, $key=null){
    	$data = array();
    	$result = $this->fetch($sql);
    	while($row = $result->fetch()){
    		if(!empty($key)){
    			$data[$row->{$key}] = $row;
    		}else{
    			$data[] = $row;
    		}
    	}
    	return $data;
    }
    
    
    public function fetchCount($sql){
     	$total = $this->fetch($sql);
     	if($row = $total->fetch()){
     		return (int)$row[0];
     	}else{
     		return false;
     	}
    }
    
    /*第二个数据库方法完毕*/
    
    public function last_insert_id(){
        return $this->conn->lastInsertId(); 
    }

    /**
     * 执行一条带有结果集计数的 count SQL 语句, 并返该计数.
     */
    public function count($sql){
        $total = $this->query($sql);
    	if($row = $total->fetch()){
     		return (int)$row[0];
     	}else{
     		return false;
     	}
    }

    /**
     * 获取指定编号的记录.
     * @param int $id 要获取的记录的编号.
     * @param string $field 字段名, 默认为'id'.
     */
    public function load($table, $id, $field='id'){
        $sql = "SELECT * FROM `{$table}` WHERE `{$field}`='{$id}'";
        $row = $this->get($sql);
        return $row;
    }

    /**
     * 保存一条记录
     * @param object $row
     */
    public function save($table, &$row){
        $sqlA = '';
        foreach($row as $k=>$v){
            $sqlA .= "`$k` = '$v',";
        }
        $sqlA = substr($sqlA, 0, -1);
        $sql  = "INSERT INTO `{$table}` SET $sqlA";
        if($this->write($sql)){
            return $this->last_insert_id();
        }else{
            return false;
        }
    }

    /**
     * 更新$arr[id]所指定的记录.
     * @param array $row 要更新的记录, 键名为id的数组项的值指示了所要更新的记录.
     * @return int 影响的行数.
     * @param string $field 字段名, 默认为'id'.
     */
    public function update($table, &$row, $field='id'){
        $sqlF = '';
        foreach($row as $k=>$v){
            $sqlF .= "`$k` = '$v',";
        }
        $sqlF = substr($sqlF, 0, -1);
        if(is_object($row)){
            $id = $row->{$field};
        }else{
            $id = $row[$field];
        }
        $sql  = "UPDATE `{$table}` SET $sqlF WHERE `{$field}`='$id'";
        return $this->write($sql);
    }

    /**
     * 删除一条记录.
     * @param int $id 要删除的记录编号.
     * @return int 影响的行数.
     * @param string $field 字段名, 默认为'id'.
     */
    public function remove($table, $id, $field='id'){
        $sql  = "DELETE FROM `{$table}` WHERE `{$field}`='{$id}'";
        return $this->write($sql);
    }

    /*开始一个事务.*/
    public function begin(){
        $this->conn->beginTransaction();
    }
    /* 提交一个事务.*/
    public function commit(){
        $this->conn->commit();
    }
    /*回滚一个事务.*/
    public function rollback(){
        $this->conn->rollBack();
    }
    public function __destruct(){     //析构函数释放连接资源
       $this->conn = null;
       $this->conn2 = null;
    }

}