<?php

class JieqiDatabase extends JieqiObject
{
    function JieqiDatabase()
    {
        $this->JieqiObject();
    }

    function &retInstance()
    {
        static $instance = array();
        return $instance;
    }

    function close($db = NULL)
    {
        if (is_object($db)) {
            $db->close();
        } else {
            $instance =& JieqiDatabase::retInstance();
            if (!empty($instance)) {
                foreach ($instance as $db) {
                    $db->close();
                }
            }
        }
    }

    function &getInstance($dbtype = '', $dbhost = '', $dbuser = '', $dbpass = '', $dbname = '', $getnew = false)
    {
        $instance =& JieqiDatabase::retInstance();
        if (empty($dbtype)) $dbtype = JIEQI_DB_TYPE;
        if (empty($dbhost)) $dbhost = JIEQI_DB_HOST;
        if (empty($dbuser)) $dbuser = JIEQI_DB_USER;
        if (empty($dbpass)) $dbpass = JIEQI_DB_PASS;
        if (empty($dbname)) $dbname = JIEQI_DB_NAME;
        $inskey = md5($dbtype . ',' . $dbhost . ',' . $dbuser . ',' . $dbpass . ',' . $dbname);
        $getnew = ($dbtype == JIEQI_DB_TYPE && $dbhost == JIEQI_DB_HOST && $dbuser == JIEQI_DB_USER && $dbpass == JIEQI_DB_PASS && $dbname == JIEQI_DB_NAME) ? false : true;
        if (!isset($instance[$inskey]) || $getnew) {
            switch ($dbtype) {
                case 'pdo-mysql':
                    require_once('pdo-mysql/db.php');
                    if ($getnew) $db = new JieqiMySQLDatabase();
                    else $instance[$inskey] = new JieqiMySQLDatabase();
                    break;
                case 'mysql':
                    require_once('mysql/db.php');
                    if ($getnew) $db = new JieqiMySQLDatabase();
                    else $instance[$inskey] = new JieqiMySQLDatabase();
                    break;
                case 'sqlite':
                    require_once('sqlite/db.php');
                    if ($getnew) $db = new JieqiSQLiteDatabase();
                    else $instance[$inskey] = new JieqiSQLiteDatabase();
                    break;
                default:
                    jieqi_printfail('The database type (' . $dbtype . ') is not exists!');
                    return false;
            }
            if ($getnew) {
                if (!$db->connect($dbhost, $dbuser, $dbpass, $dbname)) {
                    jieqi_printfail('Can not connect to database!<br /><br />error: ' . $db->error());
                    return false;
                } else {
                    return $db;
                }
            } else {
                if (!$instance[$inskey]->connect($dbhost, $dbuser, $dbpass, $dbname)) {
                    jieqi_printfail('Can not connect to database!<br /><br />error: ' . $instance[$inskey]->error());
                    return false;
                }
            }
        }
        if (!defined('JIEQI_DB_CONNECTED')) @define('JIEQI_DB_CONNECTED', true);
        return $instance[$inskey];
    }
}

class JieqiObjectData extends JieqiObject
{
    var $_isNew = false;

    function JieqiObjectData()
    {
        $this->JieqiObject();
    }

    function setNew()
    {
        $this->_isNew = true;
    }

    function unsetNew()
    {
        $this->_isNew = false;
    }

    function isNew()
    {
        return $this->_isNew;
    }

    function initVar($key, $type, $value = NULL, $caption = '', $required = false, $maxlength = NULL, $isdirty = false)
    {
        $this->vars[$key] = array('type' => $type, 'value' => $value, 'caption' => $caption, 'required' => $required, 'maxlength' => $maxlength, 'isdirty' => $isdirty, 'default' => '', 'options' => '');
    }

    function setOptions($key, $options)
    {
        $this->vars[$key]['options'] = $options;
    }

    function setVar($key, $value, $isdirty = true)
    {
        if (!empty($key) && isset($value)) {
            if (!isset($this->vars[$key])) {
                $this->initVar($key, JIEQI_TYPE_TXTBOX);
            }
            $this->vars[$key]['value'] = $value;
            $this->vars[$key]['isdirty'] = $isdirty;
        }
    }

    function setVars($var_arr, $isdirty = false)
    {
        if (is_array($var_arr)) {
            foreach ($var_arr as $key => $value) {
                $this->setVar($key, $value, $isdirty);
            }
        }
    }

    function getVars($format = '')
    {
        if (in_array($format, array('s', 'e', 'q', 't', 'o', 'n'))) {
            $ret = array();
            foreach ($this->vars as $k => $v) {
                $ret[$k] = $this->getVar($k, $fotmat);
            }
            return $ret;
        } else {
            return $this->vars;
        }
    }

    function getVar($key, $format = 's')
    {
        if (isset($this->vars[$key]['value'])) {
            if (is_string($this->vars[$key]['value'])) {
                switch (strtolower($format)) {
                    case 's':
                        return jieqi_htmlstr($this->vars[$key]['value']);
                    case 'e':
                        return preg_replace("/&amp;#(\d+);/isU", "&#\\1;", htmlspecialchars($this->vars[$key]['value'], ENT_QUOTES));
                    case 'q':
                        return jieqi_dbslashes($this->vars[$key]['value']);
                    case 't':
                        return $this->vars[$key]['caption'];
                    case 'o':
                        return !empty($this->vars[$key]['options'][$this->vars[$key]['value']]) ? $this->vars[$key]['options'][$this->vars[$key]['value']] : '';
                    case 'n':
                    default:
                        return $this->vars[$key]['value'];
                }
            } else return $this->vars[$key]['value'];
        } else {
            return false;
        }
    }
}

class JieqiQueryHandler extends JieqiObject
{
    var $db;
    var $sqlres;

    function JieqiQueryHandler($db = '')
    {
        $this->JieqiObject();
        if (empty($db) || !is_object($db)) {
            $this->db =& JieqiDatabase::getInstance();
        } else {
            $this->db = &$db;
        }
    }

    function setdb($db)
    {
        $this->db = &$db;
    }

    function getdb()
    {
        return $this->db;
    }

    function execute($criteria = NULL, $full = false, $nobuffer = false)
    {
        $criteria = $criteria ? $criteria : $this->criteria;
        if (is_object($criteria)) {
            $sql = $criteria->getSql();
            if (!$full) $sql .= ' ' . $criteria->renderWhere();
            $this->sqlres = $this->db->query($sql, 0, 0, $nobuffer);
            return $this->sqlres;
        } elseif (!empty($criteria)) {
            $this->sqlres = $this->db->query($criteria, 0, 0, $nobuffer);
            return $this->sqlres;
        }
        return false;
    }

    function queryObjects($criteria = NULL, $nobuffer = false)
    {
        $criteria = $criteria ? $criteria : $this->criteria;
        $limit = $start = 0;
        $sql = 'SELECT ' . $criteria->getFields() . ' FROM ' . $criteria->getTables() . ' ' . $criteria->renderWhere();
        if ($criteria->getGroupby() != '') {
            $sql .= ' GROUP BY ' . $criteria->getGroupby();
        }
        if ($criteria->getSort() != '') {
            $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
        }
        $limit = $criteria->getLimit();
        $start = $criteria->getStart();
        $this->sqlres = $this->db->query($sql, $limit, $start, $nobuffer);
        return $this->sqlres;
    }

    function returnsql($criteria = NULL, $nobuffer = false)
    {
        $limit = $start = 0;
        $sql = 'SELECT ' . $criteria->getFields() . ' FROM ' . $criteria->getTables() . ' ' . $criteria->renderWhere();
        if ($criteria->getGroupby() != '') {
            $sql .= ' GROUP BY ' . $criteria->getGroupby();
        }
        if ($criteria->getSort() != '') {
            $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
        }
        return $sql;
    }

    function getObject($result = '')
    {
        if ($result == '') $result = $this->sqlres;
        if (!$result) return false;
        else {
            $myrow = $this->db->fetchArray($result);
            if (!$myrow) return false;
            else {
                $dbrowobj = new JieqiObjectData();
                $dbrowobj->setVars($myrow);
                return $dbrowobj;
            }
        }
    }

    function getRow($result = '')
    {
        if ($result == '') $result = $this->sqlres;
        if (!$result) return false;
        else {
            $myrow = $this->db->fetchArray($result);
            if (!$myrow) return false;
            else return $myrow;
        }
    }

    function getCount($criteria = NULL)
    {
        $criteria = $criteria ? $criteria : $this->criteria;
        if (is_object($criteria)) {
            if ($criteria->getGroupby() == '') {
                $sql = 'SELECT COUNT(*) FROM ' . $criteria->getTables() . ' ' . $criteria->renderWhere();
                $nobuffer = true;
            } else {
                $sql = 'SELECT COUNT(' . $criteria->getGroupby() . ') FROM ' . $criteria->getTables() . ' ' . $criteria->renderWhere() . ' GROUP BY ' . $criteria->getGroupby();
                $nobuffer = false;
            }
            $result = $this->db->query($sql, 0, 0, $nobuffer);
            if (!$result) return 0;
            if ($criteria->getGroupby() == '') {
                list($count) = $this->db->fetchRow($result);
            } else {
                $count = $this->db->getRowsNum($result);
            }
            return $count;
        }
        return 0;
    }

    function getsum($fieldname, $criteria = NULL)
    {
        $criteria = $criteria ? $criteria : $this->criteria;
        $sql = "SELECT SUM(" . $fieldname . ") FROM " . jieqi_dbprefix($this->dbname, $this->fullname);
        if (isset($criteria) && is_subclass_of($criteria, "criteriaelement")) {
            $sql .= " " . $criteria->renderwhere();
        }
        $query = $this->db->query($sql, 0, 0, true);
        if (!$query) {
            return 0;
        }
        $rowobj = $this->db->fetchrow($query);
        if (empty($rowobj[0])) {
            $rowobj[0] = 0;
        }
        return $rowobj[0];
    }

    function updatefields($table, $fields, $criteria = NULL)
    {
        $criteria = $criteria ? $criteria : $this->criteria;
        $sql = 'UPDATE ' . $table . ' SET ';
        $start = true;
        if (is_array($fields)) {
            foreach ($fields as $k => $v) {
                if (!$start) {
                    $sql .= ', ';
                } else {
                    $start = false;
                }
                if (is_numeric($v)) {
                    $sql .= $k . '=' . $this->db->quoteString($v);
                } else {
                    $sql .= $k . '=' . $this->db->quoteString($v);
                }
            }
        } else {
            $sql .= $fields;
        }
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }
}

class JieqiObjectHandler extends JieqiQueryHandler
{
    var $basename;
    var $autoid;
    var $dbname;
    var $fullname = false;

    function JieqiObjectHandler($db = '')
    {
        $this->JieqiQueryHandler($db);
    }

    function create($isNew = true)
    {
        $tmpvar = 'Jieqi' . ucfirst($this->basename);
        ${$this->basename} = new $tmpvar();
        if ($isNew) {
            ${$this->basename}->setNew();
        }
        return ${$this->basename};
    }

    function get($id)
    {
        if (is_numeric($id) && intval($id) > 0) {
            $id = intval($id);
            $sql = 'SELECT * FROM ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' WHERE ' . $this->autoid . '=' . $id;
            if (!$result = $this->db->query($sql, 0, 0, true)) {
                return false;
            }
            $datarow = $this->db->fetchArray($result);
            if (is_array($datarow)) {
                $tmpvar = 'Jieqi' . ucfirst($this->basename);
                ${$this->basename} = new $tmpvar();
                ${$this->basename}->setVars($datarow);
                return ${$this->basename};
            }
        }
        return false;
    }

    function insert(&$baseobj)
    {
        if (strcasecmp(get_class($baseobj), 'jieqi' . $this->basename) != 0) {
            return false;
        }
        if ($baseobj->isNew()) {
            if (is_numeric($baseobj->getVar($this->autoid, 'n'))) {
                ${$this->autoid} = intval($baseobj->getVar($this->autoid, 'n'));
            } else {
                ${$this->autoid} = $this->db->genId($this->dbname . '_' . $this->autoid . '_seq');
            }
            $sql = 'INSERT INTO ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' (';
            $values = ') VALUES (';
            $start = true;
            foreach ($baseobj->vars as $k => $v) {
                if (!$start) {
                    $sql .= ', ';
                    $values .= ', ';
                } else {
                    $start = false;
                }
                $sql .= $k;
                if ($v['type'] == JIEQI_TYPE_INT) {
                    if ($k != $this->autoid) {
                        $values .= $this->db->quoteString($v['value']);
                    } else {
                        $values .= ${$this->autoid};
                    }
                } else {
                    $values .= $this->db->quoteString($v['value']);
                }
            }
            $sql .= $values . ')';
            unset($values);
        } else {
            $sql = 'UPDATE ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' SET ';
            $start = true;
            foreach ($baseobj->vars as $k => $v) {
                if ($k != $this->autoid && $v['isdirty']) {
                    if (!$start) {
                        $sql .= ', ';
                    } else {
                        $start = false;
                    }
                    if ($v['type'] == JIEQI_TYPE_INT) {
                        $sql .= $k . '=' . $this->db->quoteString($v['value']);
                    } else {
                        $sql .= $k . '=' . $this->db->quoteString($v['value']);
                    }
                }
            }
            if ($start) return true;
            $sql .= ' WHERE ' . $this->autoid . '=' . intval($baseobj->vars[$this->autoid]['value']);
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        }
        if ($baseobj->isNew()) {
            $baseobj->setVar($this->autoid, $this->db->getInsertId());
        }
        return true;
    }

    function delete($criteria = 0)
    {
        $sql = '';
        if (is_numeric($criteria)) {
            $criteria = intval($criteria);
            $sql = 'DELETE FROM ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' WHERE ' . $this->autoid . '=' . $criteria;
        } elseif (is_object($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $tmpstr = $criteria->renderWhere();
            if (!empty($tmpstr)) $sql = 'DELETE FROM ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' ' . $tmpstr;
        }
        if (empty($sql)) return false;
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        }
        return true;
    }

    function queryObjects($criteria = NULL, $nobuffer = false)
    {
        $limit = $start = 0;
        $sql = 'SELECT ' . $criteria->getFields() . ' FROM ' . jieqi_dbprefix($this->dbname, $this->fullname);
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getGroupby() != '') {
                $sql .= ' GROUP BY ' . $criteria->getGroupby();
            }
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $this->sqlres = $this->db->query($sql, $limit, $start, $nobuffer);
        return $this->sqlres;
    }

    function getObject($result = '')
    {
        if ($result == '') $result = $this->sqlres;
        if (!$result) return false;
        else {
            $tmpvar = 'Jieqi' . ucfirst($this->basename);
            $myrow = $this->db->fetchArray($result);
            if (!$myrow) return false;
            else {
                $dbrowobj = new $tmpvar();
                $dbrowobj->setVars($myrow);
                return $dbrowobj;
            }
        }
    }

    function getCount($criteria = NULL)
    {
        $sql = 'SELECT COUNT(*) FROM ' . jieqi_dbprefix($this->dbname, $this->fullname);
        $nobuffer = true;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getGroupby() != '') {
                $sql = 'SELECT COUNT(' . $criteria->getGroupby() . ') FROM ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' ' . $criteria->renderWhere() . ' GROUP BY ' . $criteria->getGroupby();
                $nobuffer = false;
            }
        }
        $result = $this->db->query($sql, 0, 0, $nobuffer);
        if (!$result) return 0;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement') && $criteria->getGroupby() != '') {
            $count = $this->db->getRowsNum($result);
        } else {
            list($count) = $this->db->fetchRow($result);
        }
        return $count;
    }

    function updatefields($fields, $criteria = NULL)
    {
        $sql = 'UPDATE ' . jieqi_dbprefix($this->dbname, $this->fullname) . ' SET ';
        $start = true;
        if (is_array($fields)) {
            foreach ($fields as $k => $v) {
                if (!$start) {
                    $sql .= ', ';
                } else {
                    $start = false;
                }
                if (is_numeric($v)) {
                    $sql .= $k . '=' . $this->db->quoteString($v);
                } else {
                    $sql .= $k . '=' . $this->db->quoteString($v);
                }
            }
        } else {
            $sql .= $fields;
        }
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }
}

class CriteriaElement extends JieqiObject
{
    var $order = 'ASC';
    var $sort = '';
    var $limit = 0;
    var $start = 0;
    var $groupby = '';
    var $sql = '';
    var $fields = '*';
    var $tables = '';

    function CriteriaElement()
    {
        $this->JieqiObject();
    }

    function setSql($sql)
    {
        $this->sql = $sql;
    }

    function getSql()
    {
        return $this->sql;
    }

    function setFields($fields)
    {
        $this->fields = $fields;
    }

    function getFields()
    {
        return $this->fields;
    }

    function setTables($tables)
    {
        $this->tables = $tables;
    }

    function getTables()
    {
        return $this->tables;
    }

    function setSort($sort)
    {
        $this->sort = $sort;
    }

    function getSort()
    {
        return $this->sort;
    }

    function setOrder($order)
    {
        if ('DESC' == strtoupper($order)) {
            $this->order = 'DESC';
        }
    }

    function getOrder()
    {
        return $this->order;
    }

    function setLimit($limit = 0)
    {
        if (isset($limit) && is_numeric($limit)) $this->limit = intval($limit);
        else $this->limit = 1;
    }

    function getLimit()
    {
        return $this->limit;
    }

    function setStart($start = 0)
    {
        $this->start = intval($start);
    }

    function getStart()
    {
        return $this->start;
    }

    function setGroupby($group)
    {
        $this->groupby = $group;
    }

    function getGroupby()
    {
        return $this->groupby;
    }
}

class CriteriaCompo extends CriteriaElement
{
    var $criteriaElements = array();
    var $conditions = array();

    function CriteriaCompo($ele = NULL, $condition = 'AND')
    {
        if (isset($ele) && is_object($ele)) {
            $this->add($ele, $condition);
        }
    }

    function add(&$criteriaElement, $condition = 'AND')
    {
        $this->criteriaElements[] =& $criteriaElement;
        $this->conditions[] = $condition;
        return $this;
    }

    function render()
    {
        $ret = '';
        $count = count($this->criteriaElements);
        if ($count > 0) {
            $ret = '(' . $this->criteriaElements[0]->render();
            for ($i = 1; $i < $count; $i++) {
                $ret .= ' ' . $this->conditions[$i] . ' ' . $this->criteriaElements[$i]->render();
            }
            $ret .= ')';
        }
        return $ret;
    }

    function renderWhere()
    {
        $ret = $this->render();
        $ret = ($ret != '') ? 'WHERE ' . $ret : $ret;
        return $ret;
    }
}

class Criteria extends CriteriaElement
{
    var $column;
    var $operator;
    var $value;

    function Criteria($column, $value = '', $operator = '=')
    {
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
    }

    function render()
    {
        if (!empty($this->column)) $clause = $this->column . ' ' . $this->operator;
        else $clause = '';
        if (isset($this->value)) {
            if ($this->column == '' && $this->operator == '') {
                $clause .= " " . trim($this->value);
            } elseif (strtoupper($this->operator) == 'IN') {
                $clause .= ' ' . $this->value;
            } else {
                if ($this->value !== '') {
                    $clause .= " '" . jieqi_dbslashes(trim($this->value)) . "'";
                }
            }
        }
        return $clause;
    }

    function renderWhere()
    {
        $ret = $this->render();
        $ret = ($ret != '') ? 'WHERE ' . $ret : $ret;
        return $ret;
    }
}
