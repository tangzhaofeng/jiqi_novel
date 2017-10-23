<?php

class Controller extends JieqiObject
{
    public $template_name = NULL;
    public $theme_dir = NULL;
    public $caching = JIEQI_ENABLE_CACHE;
    public $cachetime = JIEQI_CACHE_LIFETIME;
    public $cacheid = '';
    public static $tpl = NULL;

    public function __construct()
    {
        if (!in_array(JIEQI_MODULE_NAME, array('3g', 'wap', '3gwap'))) {
            if (is_mobile()) {
                $jump_array = array(
                    'system' => array('home', 'register', 'setpass'),
                    'article' => array('home', 'articleinfo', 'reader', 'index'),
                    'wenxue' => array('home', 'articleinfo', 'reader', 'index'),
                    'mm' => array('home', 'articleinfo', 'reader', 'index')
                );
                if (isset($jump_array[JIEQI_MODULE_NAME])) {
                    if (in_array(Application::$_HLM_RUN_CONTROLLER, $jump_array[JIEQI_MODULE_NAME])) {
                        header('location: ' . str_replace(array('http://www.', 'http://mm.', 'http://wenxue.'), 'http://3g.', JIEQI_CURRENT_URL));
                        exit;
                    }
                }
            }
        } else {
        }
        $template = $this->load('template');
        $this->tpl = $template;
        $this->setTpl();
        $this->assign('adminprefix', $this->getAdminurl($_REQUEST['controller'], '', JIEQI_MODULE_NAME));
    }

    public function main($params = array())
    {
        $this->display();
    }

    final protected function model($model = '', $module = '', $dir = '')
    {
        $model_name = $model . 'Model';
        if (!class_exists($model_name)) {
            if ($module == 'system') {
                $model_file = include_once HLM_ROOT_PATH . ($dir ? '/' . $dir : '') . '/model/' . $model_name . '.php';
            } elseif ($module) {
                global $jieqiModules;
                $model_file = include_once $jieqiModules[$module]['path'] . ($dir ? '/' . $dir : '') . '/model/' . $model_name . '.php';
            } else {
                $model_file = include_once Application::$_HLM_MODEL_PATH . '/' . $model_name . '.php';
            }
        }
        return new $model_name;
    }

    final protected function getCacheid()
    {
        return $this->cacheid;
    }

    final protected function setCacheid($cacheid = '')
    {
        $this->cacheid = $cacheid;
    }

    final protected function setCaching($num = 0)
    {
        $this->caching = $num;
    }

    final protected function getCaching()
    {
        return $this->caching;
    }

    final protected function getCacheTime()
    {
        return $this->cachetime;
    }

    final protected function setCacheTime($num = 0)
    {
        $this->cachetime = $num;
    }

    final protected function is_cached($template_name = '', $return_value = false)
    {
        global $jieqiCache;
        if ($template_name) $this->setTpl($template_name);
        if (!$this->template_name || !$this->getCaching()) return false;
        $this->tpl->setCaching($this->getCaching());
        return $this->tpl->is_cached($this->template_name, $this->getCacheid(), $this->tpl->getCompileid(), $this->getCacheTime(), NULL, $return_value);
    }

    final protected function assign($tpl_var, $value = NULL)
    {
        self::$this->tpl->assign($tpl_var, $value);
    }

    final protected function setTpl($template_name = '')
    {
        if ($template_name !== '') $this->template_name = $template_name;
        if (!$this->template_name) {
            $template_name = Application::$_HLM_RUN_CONTROLLER;
            if (Application::$_HLM_RUN_METHOD != HLM_DEFAULT_ACTION && Application::$_HLM_RUN_METHOD) {
                if (is_file(Application::$_HLM_VIEW_PATH . '/' . $template_name . '_' . Application::$_HLM_RUN_METHOD . HLM_TPL_SUFFIX)) {
                    $template_name = $template_name . '_' . Application::$_HLM_RUN_METHOD;
                }
            }
            if (is_file(Application::$_HLM_VIEW_PATH . '/' . $template_name . HLM_TPL_SUFFIX)) $this->template_name = $template_name;
            else {
                $this->template_name = HLM_SYSTEM_PATH . '/404';
            }
        }
    }

    final function display($data = array(), $template_name = '', $caching = '', $cacheid = '', $cachetime = '')
    {
        global $_SGLOBAL, $_SCONFIG, $_SN, $_TPL, $jieqiTset, $jieqiModules, $jieqiTpl, $_PAGE, $_OBJ, $jieqi_pagetitle, $jieqiConfigs, $jieqiBlocks;
        global $jieqi_blockside, $jieqi_showblock, $jieqi_pageblocks, $jieqi_showlblock, $jieqi_lblocks, $jieqi_showrblock, $jieqi_showtblock, $jieqi_showbblock, $jieqi_bblocks;
        global $jieqi_showcblock, $jieqi_showcblock, $jieqi_clblocks, $jieqi_crblocks, $jieqi_ctblocks, $jieqi_cmblocks, $jieqi_cbblocks, $jieqi_showrblock, $jieqi_rblocks, $jieqi_tblocks;
        if ($template_name) $this->setTpl($template_name);
        if ($caching !== '') $this->setCaching($caching);
        $this->tpl->setCaching($this->getCaching());
        if ($this->getCaching()) {
            if ($cacheid !== '') $this->setCacheid($cacheid);
            if ($this->getCacheid()) {
                $this->tpl->setCacheid($this->getCacheid());
            }
            if ($cachetime !== '') $this->setCacheTime($cachetime);
            if ($this->getCacheTime()) {
                $this->tpl->setCacheTime($this->getCacheTime());
            }
        }
        if ($this->template_name) {
            if ($this->template_name == HLM_SYSTEM_PATH . '/404') $this->theme_dir = false;
            $this->tpl->init($this->template_name, $data);
            $jieqiTset['jieqi_contents_template'] = $this->tpl->get_tpl_dir();
            $jieqiTset['jieqi_contents_cacheid'] = $this->tpl->getCacheid();
            $jieqiTset['jieqi_contents_compileid'] = $this->tpl->getCompileid();
        }
        if ($this->theme_dir !== NULL) {
            if ($this->theme_dir === false) {
                $jieqiTset['jieqi_page_template'] = HLM_SYSTEM_PATH . 'empty.html';
            } else {
                if (!is_file($this->theme_dir)) {
                    if (substr($this->theme_dir, 0, 1) != '/') $jieqiTset['jieqi_page_template'] = Application::$_HLM_VIEW_PATH . '/' . $this->theme_dir . HLM_TPL_SUFFIX;
                    else $jieqiTset['jieqi_page_template'] = HLM_ROOT_PATH . $this->theme_dir . HLM_TPL_SUFFIX;
                } else {
                    $jieqiTset['jieqi_page_template'] = $this->theme_dir;
                }
            }
        }
        if (Application::$_DISPLAY) {
            include_once(JIEQI_ROOT_PATH_APP. '/footer.php');
        } else {
            if ($jieqiTset['jieqi_contents_template'][0] != '/' && $jieqiTset['jieqi_contents_template'][1] != ':') $jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH . '/' . $jieqiTset['jieqi_contents_template'];
            return $jieqiTpl->fetch($jieqiTset['jieqi_contents_template']);
        }
    }
}
