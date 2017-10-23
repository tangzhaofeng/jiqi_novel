<?php

class JieqiMySQLDatabase extends JieqiObject
{
    var $conn;
    var $dsn;
    var $affected_rows;

    function JieqiMySQLDatabase($db = '')
    {
        $this->JieqiObject();
    }

    function connect($dbhost = '', $dbuser = '', $dbpass = '', $dbname = '', $selectdb = true, $charset='GBK')
    {
        if ($dbhost && $dbuser && $dbpass) {
            $this->dsn = "mysql:host=$dbhost;dbname=$dbname;charset=$charset";
        }
        try {
            if (JIEQI_DB_PCONNECT == 1) {
                $this->conn = new PDO($this->dsn, $dbuser, $dbpass, PDO::ATTR_PERSISTENT);
            }
            else {
                $this->conn = new PDO($this->dsn, $dbuser, $dbpass);
            }
        }catch (PDOException $e) {
            print "Error: " . $e->getMessage() . "<br/>";
            die();
        }

        if (!$this->conn)
            return false;
        else
            return true;
    }

    function reconnect()
    {
        return $this->connect();
    }

    function genId($sequence = '')
    {
        return 0;
    }

    function fetchRow($result)
    {
        return $result->fetch();
    }

    function fetchArray($result)
    {
        return $result->fetchAll();
    }

    function getInsertId()
    {
        return $this->conn->lastInsertId();
    }

    function getRowsNum($result)
    {
        return $result->rowCount();
    }

    function getAffectedRows()
    {
        return $this->affected_rows;
    }

    function close()
    {
        $this->conn->close();
    }

    function freeRecordSet($result)
    {
        unset($result);
    }

    function error()
    {
        return $this->conn->errorInfo();
    }

    function errno()
    {
        return $this->conn->errorCode();
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
        $result = $this->conn->query($sql);
        if ($result) return $result;
        else {
            if ($this->conn->errorCode() == 2013) {
                $this->reconnect();
                $result = $this->conn->query($sql);
                if ($result) return $result;
            }
            if (defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) {
                jieqi_printfail('SQL: ' . jieqi_htmlstr($sql) . '<br /><br />ERROR: ' . $this->conn->errorInfo() . '(' . $this->conn->errorCode() . ')');
            }
            return false;
        }
    }
}
