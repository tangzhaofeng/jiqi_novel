<?php

class Model extends JieqiObject
{
    protected $db = null;

    final public function __construct()
    {
        $this->db = $this->load('database');
    }

    final protected function model($model = '', $module = '')
    {
        $model_name = $model . 'Model';
        if (!class_exists($model_name)) {
            if ($module == 'system') {
                $model_file = include_once HLM_ROOT_PATH . '/model/' . $model_name . '.php';
            } elseif ($module) {
                global $jieqiModules;
                $model_file = include_once $jieqiModules[$module]['path'] . '/model/' . $model_name . '.php';
            } else {
                $model_file = include_once Application::$_HLM_MODEL_PATH . '/' . $model_name . '.php';
            }
        }
        return new $model_name;
    }

    public function getDbPower($modname = 'system', $pname = '')
    {
        $this->db->init('power', 'pid', 'system');
        $this->db->setCriteria(new Criteria('modname', $modname, '='));
        $this->db->criteria->setSort('pid');
        $this->db->criteria->setOrder('ASC');
        $this->db->queryObjects();
        $power = array();
        while ($v = $this->db->getObject()) {
            $power[$v->getVar('pname', 'n')] = array('caption' => $v->getVar('ptitle'), 'groups' => unserialize($v->getVar('pgroups', 'n')), 'description' => $v->getVar('pdescription'));
            if ($pname && $v->getVar('pname', 'n') == $pname) {
                return $power[$v->getVar('pname', 'n')];
            }
        }
        if (!$pname) return $power;
        else return array();
    }
}
