<?php
class JieqiSQLiteDatabase extends JieqiObject
{
var $conn;
function JieqiSQLiteDatabase($db=''){
$this->JieqiObject();
}
function connect($dbhost='',$dbuser='',$dbpass='',$dbname='',$selectdb = true){
if (JIEQI_DB_PCONNECT == 1) {
$this->conn = @sqlite_open($dbname,0666,$sqliteerror);
}else {
$this->conn = @sqlite_popen($dbname,0666,$sqliteerror);
}
if (!$this->conn)  return false;
else return true;
}
function genId($sequence=''){
return 0;
}
function fetchRow($result){
return @sqlite_fetch_array($result,SQLITE_NUM);
}
function fetchArray($result){
return @sqlite_fetch_array($result,SQLITE_ASSOC);
}
function getInsertId()
{
return sqlite_last_insert_rowid($this->conn);
}
function getRowsNum($result){
return @sqlite_num_rows($result);
}
function getAffectedRows(){
return sqlite_changes($this->conn);
}
function close(){
@sqlite_close($this->conn);
}
function freeRecordSet($result){
return true;
}
function error()
{
$errno=@sqlite_last_error($this->conn);
if(!empty($errno)) return @sqlite_error_string($errno);
else return '';
}
function errno(){
return @sqlite_last_error($this->conn);
}
function quoteString($str){
return "'".jieqi_dbslashes($str)."'";
}
function query($sql,$limit=0,$start=0,$nobuffer=false){
if ( !empty($limit) ) {
if (empty($start)) {
$start = 0;
}
$sql = $sql.' LIMIT '.(int)$start.', '.(int)$limit;
}
$sql=str_replace(array('\\\'','\"','\\\\'),array('\'\'','"','\\'),$sql);
if($nobuffer) $result = sqlite_unbuffered_query($sql,$this->conn);
else $result = sqlite_query($sql,$this->conn);
if ( $result ) {
if(!$result) $this->raiseError('SQL: '.$sql,JIEQI_ERROR_RETURN);
return $result;
}else {
$this->raiseError('SQL: '.$sql,JIEQI_ERROR_RETURN);
return false;
}
}
function list_tables(){
if (function_exists ('sqlite_list_tables')) {
return sqlite_list_tables();
}else{
$tables = array ();
$sql = "SELECT name FROM sqlite_master WHERE (type = 'table')";
if ($res = sqlite_query ($this->conn,$sql)) {
while (sqlite_has_more($res)) {
$tables[] = sqlite_fetch_single($res);
}
}
return $tables;
}
}
function table_exists($table){
if (function_exists ('sqlite_table_exists')) {
return sqlite_table_exists($this->conn,$table);
}else{
$sql = "SELECT count(name) FROM sqlite_master WHERE ((type = 'table') and (name = '$table'))";
if ($res = sqlite_query ($this->conn,$sql)) {
return sqlite_fetch_single($res)>0;
}else {
return false;
}
}
}
}
