<?php

class Template extends JieqiObject
{
    public $template_name = null;
    public $cacheid = '';
    public $compileid = '';
    public $out_put = null;
    public static $tpl = null;

    public function __construct()
    {
        global $jieqiTpl;
        if (!is_object(self::$tpl) && !is_object($jieqiTpl)) {
            self::$tpl =& JieqiTpl::getInstance();
        } else self::$tpl = $jieqiTpl;
    }

    public function init($template_name, $data = array())
    {
        $this->template_name = $template_name;
        if ($data) {
            foreach ($data as $tpl_var => $value) {
                $this->assign($tpl_var, $value);
            }
        }
    }

    public function fetch($template_name = '')
    {
        $tpl_dir = $this->get_tpl_dir($template_name);
        if (self::$tpl->template_exists($tpl_dir)) {
            $this->out_put = self::$tpl->fetch($tpl_dir, $this->getCacheid(), $this->getCompileid());
            return $this->out_put;
        } else {
            trigger_error('加载 ' . $tpl_dir . ' 模板不存在');
        }
    }

    public function is_cached($template_name, $cache_id = NULL, $compile_id = NULL, $cache_time = NULL, $over_time = NULL, $return_value = false)
    {
        global $jieqiCache;
        $tpl_file = $this->get_tpl_dir($template_name);
        return self::$tpl->is_cached($tpl_file, $cache_id, $compile_id, $cache_time, $over_time, $return_value);
    }

    public function assign($tpl_var, $value = NULL)
    {
        self::$tpl->assign($tpl_var, $value);
    }

    public function assign_by_ref($tpl_var, &$value)
    {
        self::$tpl->assign_by_ref($tpl_var, $value);
    }

    public function getCacheid()
    {
        return $this->cacheid;
    }

    public function setCacheid($cacheid = '')
    {
        $this->cacheid = $cacheid;
    }

    public function getCompileid()
    {
        return $this->compileid;
    }

    public function setCompileid($compileid = '')
    {
        $this->compileid = $compileid;
    }

    public function setCaching($num = 0)
    {
        self::$tpl->setCaching($num);
    }

    public function getCacheTime()
    {
        return self::$tpl->getCacheTime();
    }

    public function setCacheTime($num = 0)
    {
        self::$tpl->setCacheTime($num);
    }

    public function getOverTime()
    {
        return self::$tpl->getOverTime();
    }

    public function setOverTime($num = 0)
    {
        self::$tpl->setOverTime($num);
    }

    public function get_Tpl_dir($template_name = '')
    {
        if ($template_name) $this->template_name = $template_name;
        if (!is_file($this->template_name . HLM_TPL_SUFFIX)) {
            if (substr($this->template_name, 0, 1) != '/') $view_file = Application::$_HLM_VIEW_PATH . '/' . $this->template_name . HLM_TPL_SUFFIX;
            else $view_file = HLM_ROOT_PATH . $this->template_name . HLM_TPL_SUFFIX;
        } else {
            $view_file = $this->template_name . HLM_TPL_SUFFIX;
        }
        return $view_file;
    }

    public function outPut()
    {
        echo $this->out_put;
    }
}
