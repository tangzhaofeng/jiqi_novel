<?php

class JieqiMySQLDatabase extends JieqiObject
{
    var $conn;

    function JieqiMySQLDatabase($db = '')
    {
        $this->JieqiObject();
    }

    function connect($dbhost = '', $dbuser = '', $dbpass = '', $dbname = '', $selectdb = true)
    {
        if (JIEQI_DB_PCONNECT == 1) $this->conn = @mysql_pconnect($dbhost, $dbuser, $dbpass);
        else $this->conn = @mysql_connect($dbhost, $dbuser, $dbpass);
        if (!$this->conn) return false;
        $this->connectcharset();
        if ($selectdb != false) {
            if (!mysql_select_db($dbname)) return false;
        }
        return true;
    }

    function reconnect()
    {
        $ret = mysql_ping($this->conn);
        $this->connectcharset();
        return $ret;
    }

    function connectcharset()
    {
        $mysql_version = mysql_get_server_info();
        if ($mysql_version > '4.1') {
            if (defined('JIEQI_DB_CHARSET')) {
                if (JIEQI_DB_CHARSET != 'default') @mysql_query("SET character_set_connection=" . JIEQI_DB_CHARSET . ", character_set_results=" . JIEQI_DB_CHARSET . ", character_set_client=binary", $this->conn);
            } else {
                @mysql_query("SET character_set_connection=" . JIEQI_SYSTEM_CHARSET . ", character_set_results=" . JIEQI_SYSTEM_CHARSET . ", character_set_client=binary", $this->conn);
            }
        }
        if ($mysql_version > '5.0') @mysql_query("SET sql_mode=''", $this->conn);
    }

    function genId($sequence = '')
    {
        return 0;
    }

    function fetchRow($result)
    {
        return @mysql_fetch_row($result);
    }

    function fetchArray($result)
    {
        return @mysql_fetch_array($result, MYSQL_ASSOC);
    }

    function getInsertId()
    {
        return mysql_insert_id($this->conn);
    }

    function getRowsNum($result)
    {
        return @mysql_num_rows($result);
    }

    function getAffectedRows()
    {
        return mysql_affected_rows($this->conn);
    }

    function close()
    {
        @mysql_close();
    }

    function freeRecordSet($result)
    {
        return mysql_free_result($result);
    }

    function error()
    {
        return @mysql_error();
    }

    function errno()
    {
        return @mysql_errno();
    }

    function quoteString($str)
    {
        return "'" . jieqi_dbslashes($str) . "'";
    }

    function sqllog($do = 'add', $sql = '')
    {
        static $sqllog = array();
        switch ($do) {
            case 'add':
                if (!empty($sql)) $sqllog[] = $sql;
                break;
            case 'ret':
                return $sqllog;
                break;
            case 'count':
                return count($sqllog);
                break;
            case 'show':
                echo '<br />queries: ' . count($sqllog);
                foreach ($sqllog as $sql) echo '<br />' . jieqi_htmlstr($sql);
                break;
        }
    }

    function query($sql, $limit = 0, $start = 0, $nobuffer = false)
    {
        if (!empty($limit)) {
            if (empty($start)) $start = 0;
            $sql .= ' LIMIT ' . (int)$start . ', ' . (int)$limit;
        }
        if (defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) $this->sqllog('add', $sql);
        if ($nobuffer) $result = mysql_unbuffered_query($sql, $this->conn);
        else $result = mysql_query($sql, $this->conn);
        if ($result) return $result;
        else {
            if (mysql_errno($this->conn) == 2013) {
                $this->reconnect();
                if ($nobuffer) $result = mysql_unbuffered_query($sql, $this->conn);
                else $result = mysql_query($sql, $this->conn);
                if ($result) return $result;
            }
            if (defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) {
                jieqi_printfail('SQL: ' . jieqi_htmlstr($sql) . '<br /><br />ERROR: ' . mysql_error($this->conn) . '(' . mysql_errno($this->conn) . ')');
            }
            return false;
        }
    }
}
