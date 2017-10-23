<?php

class MyCachetable extends Database
{
    var $data = array();
    var $cachefile;
    var $cachedir = 'table';

    function MyCachetable()
    {
    }

    function init($table, $idfield, $module = '', $order = '')
    {
        global $_SGLOBAL;
        if ($module) $this->module = $module;
        else $this->module = JIEQI_MODULE_NAME;
        $this->table = $this->module . "_" . $table;
        $this->idfield = $idfield;
        $this->order = $order;
        if (!$this->cachefile) {
            if ($this->module != 'system') $this->cachefile = JIEQI_CACHE_PATH . '/' . $this->cachedir . '/' . $this->module . '/' . $this->table . '.php';
            else $this->cachefile = JIEQI_CACHE_PATH . '/' . $this->cachedir . '/' . $this->table . '.php';
        }
        if (is_file($this->cachefile)) {
            include_once($this->cachefile);
        } else {
            $this->cache();
        }
        $this->data = $_SGLOBAL[$table];
    }

    function get($posid)
    {
        global $_SGLOBAL, $pSetting;
        if ($this->module != 'system') $cachefile = JIEQI_CACHE_PATH . '/' . $this->cachedir . '/' . $this->module . "/{$this->table}_{$posid}_field.php";
        else $cachefile = JIEQI_CACHE_PATH . "/{$this->cachedir}/{$this->table}_{$posid}_field.php";
        if (!is_file($cachefile) || JIEQI_NOW_TIME - filemtime($cachefile) > JIEQI_CACHE_LIFETIME) {
            if (!$this->cacheOne($posid)) return false;
        }
        include_once($cachefile);
        $data = $_SGLOBAL["{$this->table}_" . $posid . '_field'][$posid];
        if ($data['setting'] && !$pSetting[$posid]) {
            eval('$pSetting[' . $posid . '] = ' . $data['setting'] . ';');
            $data['setting'] = $pSetting[$posid];
        } else {
            $data['setting'] = $pSetting[$posid];
        }
        return $data;
    }

    function getOne($posid)
    {
        global $pSetting;
        $where = " where {$this->idfield} = " . $posid;
        $data = $this->selectsql('select * from ' . $this->dbprefix("{$this->table}") . " {$where}");
        if (!$data) return false;
        $data = $data[0];
        if ($data['setting'] && !$pSetting[$posid]) {
            eval('$pSetting[' . $posid . '] = ' . $data['setting'] . ';');
            $data['setting'] = $pSetting[$posid];
        } else {
            $data['setting'] = $pSetting[$posid];
        }
        return $data;
    }

    function cacheOne($posid)
    {
        if ($data = $this->selectsql('select * from ' . $this->dbprefix($this->table) . " WHERE {$this->idfield}={$posid}")) {
            if ($this->module != 'system') $cachefile = JIEQI_CACHE_PATH . '/' . $this->cachedir . '/' . $this->module . "/{$this->table}_{$posid}_field.php";
            else $cachefile = JIEQI_CACHE_PATH . "/{$this->cachedir}/{$this->table}_{$posid}_field.php";
            $this->cache_write("{$this->table}_{$posid}_field", "_SGLOBAL['{$this->table}_{$posid}_field']", $data, $this->idfield, $cachefile);
            return true;
        } else return false;
    }

    function add($data, $ishtml = true)
    {
        if ($id = parent::add($data, $ishtml)) {
            $this->cacheOne($id);
            $this->cache();
            return $id;
        } else {
            return false;
        }
    }

    function delete($posid)
    {
        if (parent::delete($posid)) {
            if ($this->module != 'system') $cachefile = JIEQI_CACHE_PATH . '/' . $this->cachedir . '/' . $this->module . "/{$this->table}_{$posid}_field.php";
            else $cachefile = JIEQI_CACHE_PATH . "/{$this->cachedir}/{$this->table}_{$posid}_field.php";
            jieqi_delfile($cachefile);
            $this->cache();
            return true;
        } else {
            return false;
        }
    }

    function edit($id, $baseobj, $ishtml = true)
    {
        if (parent::edit($id, $baseobj, $ishtml)) {
            $this->cacheOne($id);
            $this->cache();
            return true;
        } else {
            return false;
        }
    }

    function cache()
    {
        global $_SGLOBAL;
        $_SGLOBAL[$this->table] = array();
        if ($this->order) $where = " order by " . $this->order . " ASC";
        else $where = '';
        $data = $this->selectsql('select * from ' . $this->dbprefix("{$this->table}") . " {$where}");
        $this->cache_write($this->table, "_SGLOBAL['" . $this->table . "']", $data, $this->idfield, $this->cachefile);
        include($this->cachefile);
    }
}
