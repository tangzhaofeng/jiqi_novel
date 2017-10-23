<?php
jieqi_includedb();

class Dbquery extends JieqiQueryHandler
{
  public function connect()
  {
    if (!defined('JIEQI_DBCLASS_INCLUDE') && !JIEQI_DBCLASS_INCLUDE || !is_object($this->db)) {
      $this->JieqiQueryHandler();
    }
  }

  public function version()
  {
    $this->connect();
    $version = @mysql_get_server_info();
    $version = explode('-', $version);
    return $version[0];
  }

  public function inserttable($tablename, $insertsqlarr, $returnid = 0, $replace = false, $nobuffer = 0)
  {
    $this->connect();
    $insertkeysql = $insertvaluesql = $comma = '';
    foreach ($insertsqlarr as $insert_key => $insert_value) {
      $insertkeysql .= $comma . '`' . $insert_key . '`';
      $insertvaluesql .= $comma . '\'' . $this->getFormat($insert_value, 'q') . '\'';
      $comma = ', ';
    }
    $method = $replace ? 'REPLACE' : 'INSERT';
    if (!$query = $this->query($method . ' INTO ' . jieqi_dbprefix($tablename) . ' (' . $insertkeysql . ') VALUES (' . $insertvaluesql . ')', 0, 0, $nobuffer)) return false;
    else {
      if ($returnid && !$replace) {
        $returnid = $this->getInsertId();
        $ret = $returnid ? $returnid : true;
        return $ret;
      } else return $query;
    }
  }

  public function updatetable($tablename, $setsqlarr, $wheresqlarr)
  {
    $this->connect();
    $setsql = $comma = '';
    foreach ($setsqlarr as $set_key => $set_value) {
      if ($set_value === '++') {
        $setsql .= $comma . '`' . $set_key . '`' . '=' . $set_key . '+1';
      } elseif ($set_value === '--') {
        $setsql .= $comma . '`' . $set_key . '`' . '=' . $set_key . '-1';
      } else {
        $setsql .= $comma . '`' . $set_key . '`' . '=\'' . $this->getFormat($set_value, 'q') . '\'';
      }
      $comma = ', ';
    }
    $where = $comma = '';
    if (empty($wheresqlarr)) {
      $where = '1';
    } elseif (is_array($wheresqlarr)) {
      foreach ($wheresqlarr as $key => $value) {
        $where .= $comma . '`' . $key . '`' . '=\'' . $this->getFormat($value, 'q') . '\'';
        $comma = ' AND ';
      }
    } else {
      $where = $wheresqlarr;
    }
    return $this->query('UPDATE ' . jieqi_dbprefix($tablename) . ' SET ' . $setsql . ' WHERE ' . $where);
  }

  public function selectsql($sql, $limit = 0, $start = 0, $nobuffer = false)
  {
    $this->connect();
    $query = $this->query($sql, $limit, $start, $nobuffer);
    while ($v = $this->fetchArray($query)) {
      $rows[] = $v;
    }
    return $rows;
  }

  public function deletesql($tablename, $wheresqlarr)
  {
    $this->connect();
    $where = $comma = '';
    if (empty($wheresqlarr)) {
      $where = '1';
    } elseif (is_array($wheresqlarr)) {
      foreach ($wheresqlarr as $key => $value) {
        $where .= $comma . '`' . $key . '`' . '=' . $this->getFormat($value, 'q') . '';
        $comma = ' AND ';
      }
    } else {
      $where = $wheresqlarr;
    }
    return $this->query('DELETE FROM ' . jieqi_dbprefix($tablename) . ' WHERE ' . $where);
  }

  public function query($sql)
  {
    $this->connect();
    return $this->db->query($sql);
  }

  public function getInsertId()
  {
    return $this->db->getInsertId();
  }

  function fetchArray($result = '')
  {
    return $this->db->fetchArray($result);
  }

  function getRowsNum($result)
  {
    return $this->db->getRowsNum($result);
  }

  function getAffectedRows()
  {
    return $this->db->getAffectedRows($this->db->conn);
  }

  function close()
  {
    $this->db->close();
  }

  function freeRecordSet($result)
  {
    return $this->db->freeRecordSet($result);
  }

  function error()
  {
    return $this->db->error();
  }

  function errno()
  {
    return $this->db->errno();
  }

  function quoteString($str)
  {
    return $this->db->quoteString($str);
  }

  function sqllog($do = 'add', $sql = '')
  {
    return $this->db->sqllog($do, $sql);
  }

  function fetchRow($result)
  {
    return $this->db->fetchRow($result);
  }
}

class Database extends Dbquery
{
  public $module;
  public $table;
  public $idfield;
  public $criteria;
  public $jumppage;

  public function Database()
  {
  }

  public function init($table, $idfield, $module = '')
  {
    parent::connect();
    if ($module) $this->module = $module;
    else $this->module = JIEQI_MODULE_NAME;
    $this->table = $this->module . "_" . $table;
    $this->idfield = $idfield;
    $clonedb = clone $this;
    return $clonedb;
  }

  function get($criteria = 0)
  {
    if (!is_object($criteria)) {
      $criteria = $this->getFormat($criteria, 'q');
      $where = " where {$this->idfield} = '{$criteria}'";
      if (!$data = $this->selectsql('select * from ' . jieqi_dbprefix("{$this->table}") . " {$where}")) return false;
      return $data[0];
    } elseif (is_subclass_of($criteria, 'criteriaelement')) {
      return $this->getObject($this->queryObjects($criteria));
    } else return false;
  }

  function add($data, $ishtml = true)
  {
    if (!is_array($data)) return false;
    if (!$ishtml) $data = htmlspecialchars_array($data);
    if ($id = $this->inserttable($this->table, $data, true)) {
      return $id;
    } else {
      return false;
    }
  }

  function getField($result = '')
  {
    if ($result === '') $result = $this->sqlres;
    if (!$result) return false;
    $rowobj = $this->db->fetchrow($result);
    if (!$rowobj)return false;
    return $rowobj[0];
  }



  function delete($criteria = 0)
  {
    if (!is_object($criteria)) {
      if ($this->deletesql($this->table, array("{$this->idfield}" => "{$criteria}"))) {
        return true;
      } else {
        return false;
      }
    } elseif (is_subclass_of($criteria, 'criteriaelement')) {
      $tmpstr = $criteria->renderWhere();
      $sql = 'DELETE FROM ' . $this->dbprefix($this->table) . ' ' . $tmpstr;
      return $this->query($sql);
    } else return false;
  }

  function edit($id, $baseobj, $ishtml = true)
  {
    if (!$baseobj) return false;
    $id = $this->getFormat($id, 'q');
    if (!is_object($baseobj)) {
      $data = $baseobj;
    } else {
      $start = true;
      $data = array();
      foreach ($baseobj->vars as $k => $v) {
        if ($k != $this->idfield && $v['isdirty']) {
          $data[$k] = $v['value'];
        }
      }
      if (!$data) return true;
    }
    if (!$ishtml) $data = htmlspecialchars_array($data);
    if ($this->updatetable($this->table, $data, "{$this->idfield}='{$id}'")) {
      return true;
    } else {
      return false;
    }
  }

  public function order($order_field, $order_array)
  {
    if (!is_array($order_array)) return false;
    foreach ($order_array as $id => $value) {
      $value = intval($value);
      $this->edit($id, array($order_field => $value));
    }
    return true;
  }

  function setCriteria($OBJ = '')
  {
    $this->connect();
    $this->criteria = new CriteriaCompo($OBJ);
    $this->criteria->setTables(jieqi_dbprefix($this->table));
    $this->dbname = $this->table;
    return $this->criteria;
  }

  function lists($pagenum = 0, $page = 0, $custompage = '', $emptyonepage = false)
  {
    if ($pagenum) {
      $this->criteria->setLimit($pagenum);
      if (!$page) $page = 1;
      $this->criteria->setStart(($page - 1) * $pagenum);
    }
    $this->queryObjects($this->criteria);
    $rows = array();
    $k = 0;
    while ($v = $this->getObject()) {
      $ret = array();
      foreach ($v->vars as $i => $j) {
        $ret[$i] = $v->getVar($i, 'n');
      }
      $rows[$k] = $ret;
      $k++;
    }
    if ($page) {
      $this->setVar('totalcount', $this->getCount($this->criteria));
      if (!$custompage) {
        include_once(HLM_ROOT_PATH . '/lib/html/page.php');
        $this->jumppage = new JieqiPage($this->getVar('totalcount'), $pagenum, $page);
      } else {
        $this->jumppage = new GlobalPage($custompage, $this->getVar('totalcount'), $pagenum, $page);
        $this->jumppage->emptyonepage = $emptyonepage;
        if ($custompage) $this->setVar('custompage', $custompage);
      }
    } else {
      $this->setVar('totalcount', count($rows));
    }
    return $rows;
  }

  function getPage($setlink = '')
  {
    if (!$this->getVar('custompage')) {
      $this->jumppage->setlink($setlink, true, true);
      return $this->jumppage->whole_bar();
    } else return $this->jumppage->getPage($setlink);
  }
}

class GlobalPage extends JieqiObject
{
  var $pagestr;
  var $linkhead;
  var $pagevar;
  var $emptyonepage = false;

  function GlobalPage($pagestr, $totalcount, $pagesize, $page = 1, $pagevar = 'page', $pageajax = 0)
  {
    $this->pagestr =& $pagestr;
    if (!$this->pagestr) return false;
    $this->setVar('totalcount', $totalcount);
    $this->setVar('pagesize', $pagesize);
    $this->setVar('page', $page);
    $totalpage = @ceil($totalcount / $pagesize);
    if ($totalpage <= 1) $totalpage = 1;
    $this->setVar('totalpage', $totalpage);
    $this->pagevar = $pagevar;
    if ($pageajax > 0 || (defined('JIEQI_AJAX_PAGE') && JIEQI_AJAX_PAGE > 0)) $this->useajax = 1;
    else $this->useajax = 0;
  }

  function setlink($link = '', $addget = true, $addpost = true)
  {
    if (!empty($link)) {
      $this->linkhead = $link;
    } else {
      $this->linkhead = jieqi_addurlvars(array($this->pagevar => ''), $addget, $addpost);
      $this->linkempty = true;
    }
  }

  function pageurl($page)
  {
    $linkhead = $this->linkhead;
    if ($page < 2) {
      if ($this->emptyonepage === true) $linkhead = str_replace(basename($linkhead), '', $linkhead);
      elseif ($this->emptyonepage !== false) $linkhead = str_replace(basename($linkhead), '', $linkhead) . $this->emptyonepage;
    }
    if (strpos($linkhead, '<{$page') === false && $this->linkempty) $url = $linkhead . $page;
    else $url = str_replace(array('<{$page|subdirectory}>', '<{$page}>'), array(jieqi_getsubdir($page), $page), $linkhead);
    if ($this->useajax == 1) $url = 'javascript:Ajax.Update(\'' . urldecode($url) . '\',' . $this->ajax_parm . ');';
    return $url;
  }

  function pagelink($page)
  {
    if ($page == 1 && $this->getVar('firstpage')) $link = $this->getVar('firstpage');
    else $link = $this->pageurl($page);
    return $link;
  }

  function firstpage()
  {
    if ($this->getVar('page') < 2) {
      if ($firststr = $this->exechars('[firstpage]****[/firstpage]', $this->pagestr)) {
        $this->pagestr = str_replace("[firstpage]{$firststr}[/firstpage]", '', $this->pagestr);
      } else {
        $ret = $this->pagelink(1);
      }
    } else {
      if ($firststr = $this->exechars('[firstpage]****[/firstpage]', $this->pagestr)) {
        $this->pagestr = str_replace("[firstpage]{$firststr}[/firstpage]", $firststr, $this->pagestr);
      }
      $ret = $this->pagelink(1);
    }
    return $ret;
  }

  function prepage()
  {
    $ret = '';
    if ($this->getVar('page') < 2) {
      if ($prestr = $this->exechars('[prepage]****[/prepage]', $this->pagestr)) {
        $this->pagestr = str_replace("[prepage]{$prestr}[/prepage]", '', $this->pagestr);
      } else {
        $ret = $this->pagelink(1);
      }
    } else {
      if ($prestr = $this->exechars('[prepage]****[/prepage]', $this->pagestr)) {
        $this->pagestr = str_replace("[prepage]{$prestr}[/prepage]", $prestr, $this->pagestr);
      }
      $ret = $this->pagelink($this->getVar('page') - 1);
    }
    return $ret;
  }

  function nextpage()
  {
    $ret = '';
    if ($this->getVar('page') < $this->getVar('totalpage')) {
      if ($nextstr = $this->exechars('[nextpage]****[/nextpage]', $this->pagestr)) {
        $this->pagestr = str_replace("[nextpage]{$nextstr}[/nextpage]", $nextstr, $this->pagestr);
      }
      $ret = $this->pagelink($this->getVar('page') + 1);
    } else {
      if ($nextstr = $this->exechars('[nextpage]****[/nextpage]', $this->pagestr)) {
        $this->pagestr = str_replace("[nextpage]{$nextstr}[/nextpage]", '', $this->pagestr);
      } else {
        $ret = $this->pagelink($this->getVar('totalpage'));
      }
    }
    return $ret;
  }

  function lastpage()
  {
    if ($this->getVar('page') >= $this->getVar('totalpage')) {
      if ($laststr = $this->exechars('[lastpage]****[/lastpage]', $this->pagestr)) {
        $this->pagestr = str_replace("[lastpage]{$laststr}[/lastpage]", '', $this->pagestr);
      } else {
        $ret = $this->pagelink($this->getVar('totalpage'));
      }
    } else {
      if ($laststr = $this->exechars('[lastpage]****[/lastpage]', $this->pagestr)) {
        $this->pagestr = str_replace("[lastpage]{$laststr}[/lastpage]", $laststr, $this->pagestr);
      }
      $ret = $this->pagelink($this->getVar('totalpage'));
    }
    return $ret;
  }

  function select()
  {
    $page = $this->getVar('page');
    $linkbar = $linkchar = '';
    if ($select = $this->exechars('[select]****[/select]', $this->pagestr)) {
      $this->pagestr = str_replace("[select]{$select}[/select]", $select, $this->pagestr);
    }
    if (!$optionstr = $this->exechars('[option]****[/option]', $this->pagestr)) $optionstr = "<option value='$option'>$option</option>";
    else $this->pagestr = str_replace("[option]{$optionstr}[/option]", '', $this->pagestr);
    $totalpage =& $this->getVar('totalpage');
    for ($option = 1; $option <= $totalpage; $option++) {
      $optionurl = $this->pagelink($option);
      eval('$linkchar = "' . addslashes_array($optionstr) . '";');
      if ($option == $page) {
        $linkchar = preg_replace("/\<option/i", "<option selected", $linkchar);
      }
      $linkbar .= $linkchar;
    }
    return $linkbar;
  }

  function pages()
  {
    $page = $this->getVar('page');
    if ($this->getVar('totalpage') > 1) {
      if ($pages = $this->exechars('[pages]****[/pages]', $this->pagestr)) {
        $this->pagestr = str_replace("[pages]{$pages}[/pages]", $pages, $this->pagestr);
        if (!$pnum = $this->exechars('[pnum]$$$$[/pnum]', $pages)) $pnum = 5;
        else $this->pagestr = str_replace("[pnum]{$pnum}[/pnum]", '', $this->pagestr);
        if (!$pnumchar = $this->exechars('[pnumchar]****[/pnumchar]', $pages)) $pnumchar = "<strong>{$this->getVar('page')}</strong>";
        else {
          $this->pagestr = str_replace("[pnumchar]{$pnumchar}[/pnumchar]", '', $this->pagestr);
          eval('$pnumchar = "' . addslashes_array($pnumchar) . '";');
        }
        if (!$pnumurlchar = $this->exechars('[pnumurl]****[/pnumurl]', $pages)) $pnumurlchar = "<A href='{$pnumurl}'>[{$pagenum}]</A>";
        else $this->pagestr = str_replace("[pnumurl]{$pnumurlchar}[/pnumurl]", '', $this->pagestr);
        $num = $pnum;
        $mid = floor($num / 2);
        $last = $num - 1;
        $totalpage =& $this->getVar('totalpage');
        $linkhead =& $this->linkhead;
        $minpage = ($page - $mid) < 1 ? 1 : $page - $mid;
        $maxpage = $minpage + $last;
        if ($maxpage > $totalpage) {
          $maxpage =& $totalpage;
          $minpage = $maxpage - $last;
          $minpage = $minpage < 1 ? 1 : $minpage;
        }
        $linkbar = '';
        for ($i = $minpage; $i <= $maxpage; $i++) {
          $char = $i;
          if ($i == $page) {
            $linkchar = $pnumchar;
          } else {
            $pnumurl = $this->pagelink($i);
            $pagenum = $i;
            eval('$linkchar = "' . addslashes_array($pnumurlchar) . '";');
          }
          $linkbar .= $linkchar;
        }
      }
    } else {
      if ($pages = $this->exechars('[pages]****[/pages]', $this->pagestr)) {
        $this->pagestr = str_replace("[pages]{$pages}[/pages]", '', $this->pagestr);
      }
    }
    return $linkbar;
  }

  function getPage($link = '')
  {
    if ($link || !$this->linkhead) $this->setlink($link);
    if (strpos($this->pagestr, '$firstpage')) {
      if ($firstpage = $this->firstpage()) $this->setVar('firstpage', $firstpage);
    }
    if (strpos($this->pagestr, '$prepage')) {
      if ($prepage = $this->prepage()) $this->setVar('prepage', $prepage);
    }
    if (strpos($this->pagestr, '$nextpage')) {
      if ($nextpage = $this->nextpage()) $this->setVar('nextpage', $nextpage);
    }
    if (strpos($this->pagestr, '$lastpage')) {
      if ($lastpage = $this->lastpage()) $this->setVar('lastpage', $lastpage);
    }
    if (strpos($this->pagestr, '$select')) {
      if ($select = $this->select()) $this->setVar('select', $select);
    }
    if (strpos($this->pagestr, '$pages')) {
      if ($pages = $this->pages()) $this->setVar('pages', $pages);
    }
    if ($vars = $this->getVars()) extract($vars);
    eval('$this->pagestr = "' . addslashes_array($this->pagestr) . '";');
    return stripslashes($this->pagestr);
  }
}
