<?php
define('HLM_DS', DIRECTORY_SEPARATOR);
defined('HLM_SYSTEM_PATH') or define('HLM_SYSTEM_PATH', dirname(__FILE__) . HLM_DS);
defined('HLM_ROOT_PATH') or define('HLM_ROOT_PATH', JIEQI_ROOT_PATH);
defined('HLM_SYS_LIB_PATH') or define('HLM_SYS_LIB_PATH', HLM_SYSTEM_PATH . 'lib' . HLM_DS);
defined('HLM_SYS_CORE_PATH') or define('HLM_SYS_CORE_PATH', HLM_SYSTEM_PATH . 'core' . HLM_DS);
defined('HLM_SYS_FUNC_PATH') or define('HLM_SYS_FUNC_PATH', HLM_SYSTEM_PATH . 'include' . HLM_DS);
if (!defined('HLM_RUN_PATH')) {
    if (defined('JIEQI_MODULE_NAME') && JIEQI_MODULE_NAME != 'system') {
        define('HLM_RUN_PATH', $jieqiModules[JIEQI_MODULE_NAME]['path']);
    } else {
        define('HLM_RUN_PATH', HLM_ROOT_PATH);
    }
}
defined('HLM_APP_LIB_PATH') or define('HLM_APP_LIB_PATH', HLM_RUN_PATH . HLM_DS . 'lib');
defined('HLM_APP_FUNC_PATH') or define('HLM_APP_FUNC_PATH', HLM_RUN_PATH . 'include');
defined('HLM_CONTROLLER_PATH') or define('HLM_CONTROLLER_PATH', HLM_RUN_PATH . HLM_DS . 'controller');
defined('HLM_MODEL_PATH') or define('HLM_MODEL_PATH', HLM_RUN_PATH . HLM_DS . 'model');
defined('HLM_VIEW_PATH') or define('HLM_VIEW_PATH', HLM_RUN_PATH . HLM_DS . 'templates');
defined('HLM_URL_TYPE') or define('HLM_URL_TYPE', '1');
defined('HLM_CUSTOM_LIB_PREFIX') or define('HLM_CUSTOM_LIB_PREFIX', 'my');
defined('HLM_DEFAULT_CONTROLLER') or define('HLM_DEFAULT_CONTROLLER', 'home');
defined('HLM_DEFAULT_ACTION') or define('HLM_DEFAULT_ACTION', 'main');
defined('HLM_TPL_SUFFIX') or define('HLM_TPL_SUFFIX', '.html');
if (!is_object($jieqiTpl)) include_once JIEQI_ROOT_PATH . '/header.php';

final class Application extends JieqiObject
{
    public static $_lib = null;
    public static $_DISPLAY = true;
    public static $_HLM_RUN_PATH = NULL;
    public static $_HLM_APP_LIB_PATH = NULL;
    public static $_HLM_CONTROLLER_PATH = NULL;
    public static $_HLM_MODEL_PATH = NULL;
    public static $_HLM_VIEW_PATH = NULL;
    public static $_HLM_RUN_CONTROLLER = HLM_DEFAULT_CONTROLLER;
    public static $_HLM_RUN_METHOD = HLM_DEFAULT_ACTION;

    public static function init()
    {
        self::start();
        self::setAutoLibs();
        require_once HLM_SYS_FUNC_PATH . 'functions.php';
        require_once HLM_SYS_CORE_PATH . 'model.php';
        require_once HLM_SYS_CORE_PATH . 'controller.php';
        if (is_file(HLM_CONTROLLER_PATH . HLM_DS . 'mod_controller.php')) {
            require_once HLM_CONTROLLER_PATH . HLM_DS . 'mod_controller.php';
        }
    }

    public static function start($config = array())
    {
        if (!$config) {
            self::$_HLM_RUN_PATH = HLM_RUN_PATH;
            self::$_HLM_APP_LIB_PATH = HLM_APP_LIB_PATH;
            self::$_HLM_CONTROLLER_PATH = HLM_CONTROLLER_PATH;
            self::$_HLM_MODEL_PATH = HLM_MODEL_PATH;
            self::$_HLM_VIEW_PATH = HLM_VIEW_PATH;
        } else {
            global $jieqiModules;
            if (!$config['run_path']) {
                if ($config['module'] && $config['module'] != 'system') {
                    self::$_HLM_RUN_PATH = $jieqiModules[$config['module']]['path'];
                } else {
                    self::$_HLM_RUN_PATH = HLM_ROOT_PATH;
                }
            } else self::$_HLM_RUN_PATH = $config['run_path'];
            self::$_HLM_APP_LIB_PATH = self::$_HLM_RUN_PATH . HLM_DS . 'lib';
            self::$_HLM_CONTROLLER_PATH = self::$_HLM_RUN_PATH . HLM_DS . 'controller';
            self::$_HLM_MODEL_PATH = self::$_HLM_RUN_PATH . HLM_DS . 'model';
            if (!$config['view_path']) self::$_HLM_VIEW_PATH = self::$_HLM_RUN_PATH . HLM_DS . 'templates';
            else self::$_HLM_VIEW_PATH = $config['view_path'];
        }
    }

    public static function run($config = array())
    {
        self::init();
        self::autoload();
        self::$_lib['route']->setUrlType(HLM_URL_TYPE);
        $url_array = self::$_lib['route']->getUrlArray();
        self::routeToCm($url_array);
    }

    public static function autoload()
    {
        foreach (self::$_lib as $key => $value) {
            require_once(self::$_lib[$key]);
            $lib = ucfirst($key);
            self::$_lib[$key] = new $lib;
        }
    }

    public static function newLib($class_name, $module = 'system', $dir = '')
    {
        $class_file = '';
        if ($module === FALSE) {
            $class_file = self::$_HLM_APP_LIB_PATH . HLM_DS . HLM_CUSTOM_LIB_PREFIX . '_' . $class_name . '.php';
        } elseif ($module == 'core') {
            $class_file = HLM_SYS_LIB_PATH . 'lib_' . $class_name . '.php';
        } elseif ($module == 'system') {
            $class_file = HLM_ROOT_PATH . HLM_DS . ($dir ? $dir . HLM_DS : '') . 'lib' . HLM_DS . HLM_CUSTOM_LIB_PREFIX . '_' . $class_name . '.php';
        } else {
            global $jieqiModules;
            $class_file = $jieqiModules[$module]['path'] . HLM_DS . ($dir ? $dir : '') . 'lib' . HLM_DS . HLM_CUSTOM_LIB_PREFIX . '_' . $class_name . '.php';
        }
        if (file_exists($class_file)) {
            if ($module === FALSE) {
                include_once($class_file);
                $class_name = ucfirst(HLM_CUSTOM_LIB_PREFIX) . ucfirst($class_name);
                return new $class_name;
            } elseif ($module == 'core') {
                include_once($class_file);
                return self::$_lib['$class_name'] = new $class_name;
            } else {
                include_once($class_file);
                $class_name = ucfirst(HLM_CUSTOM_LIB_PREFIX) . ucfirst($class_name);
                return new $class_name;
            }
        } else {
            trigger_error('加载 ' . $class_name . ' 类库[' . $class_file . ']不存在');
        }
    }

    public static function setAutoLibs()
    {
        self::$_lib = array(
            'route' => HLM_SYS_LIB_PATH . 'lib_route.php',
            'template' => HLM_SYS_LIB_PATH . 'lib_template.php',
            'database' => HLM_SYS_LIB_PATH . 'lib_database.php'
        );
    }

    public static function routeToCm($url_array = array())
    {
        $app = '';
        $controller = '';
        $method = '';
        $model = '';
        $params = '';
        if (isset($url_array['app'])) {
            $app = $url_array['app'];
        }
        if (is_file(HLM_APP_FUNC_PATH . HLM_DS . 'functions.php')) {
            require_once HLM_APP_FUNC_PATH . HLM_DS . 'functions.php';
        }
        if (isset($url_array['controller'])) {
            $controller = $model = $url_array['controller'];
            if ($app) {
                $controller_file = self::$_HLM_CONTROLLER_PATH . HLM_DS . $app . HLM_DS . $controller . 'Controller.php';
                $model_file = self::$_HLM_MODEL_PATH . HLM_DS . $app . HLM_DS . $model . 'Model.php';
            } else {
                $controller_file = self::$_HLM_CONTROLLER_PATH . HLM_DS . $controller . 'Controller.php';
                $model_file = self::$_HLM_MODEL_PATH . HLM_DS . $model . 'Model.php';
            }
        } else {
            $controller = $model = HLM_DEFAULT_CONTROLLER;
            if ($app) {
                $controller_file = self::$_HLM_CONTROLLER_PATH . HLM_DS . $app . HLM_DS . HLM_DEFAULT_CONTROLLER . 'Controller.php';
                $model_file = self::$_HLM_MODEL_PATH . HLM_DS . $app . HLM_DS . HLM_DEFAULT_CONTROLLER . 'Model.php';
            } else {
                $controller_file = self::$_HLM_CONTROLLER_PATH . HLM_DS . HLM_DEFAULT_CONTROLLER . 'Controller.php';
                $model_file = self::$_HLM_MODEL_PATH . HLM_DS . HLM_DEFAULT_CONTROLLER . 'Model.php';
            }
        }
        if (isset($url_array['method'])) {
            $method = $url_array['method'] ? $url_array['method'] : HLM_DEFAULT_ACTION;
        } else {
            $method = HLM_DEFAULT_ACTION;
        }
        if (isset($url_array['params'])) {
            $params = $url_array['params'];
        }
        self::$_HLM_RUN_CONTROLLER = $controller;
        if (file_exists($controller_file)) {
            if (file_exists($model_file)) {
                include_once $model_file;
            }
            if ($method) {
                self::$_HLM_RUN_METHOD = $method;
                include_once $controller_file;
                $controller_name = $controller . 'Controller';
                $controller = new $controller_name;
                if (method_exists($controller, $method)) {
                    if (self::$_DISPLAY) {
                        isset($params) ? $controller->$method($params) : $controller->$method();
                    } else {
                        if (isset($params)) {
                            return $controller->$method($params);
                        } else $controller->$method();
                    }
                } else {
                    die('控制器方法不存在');
                }
            } else {
                die('控制器方法不存在');
            }
        } else {
            $controller_name = 'Controller';
            $method = HLM_DEFAULT_ACTION;
            $controller = new $controller_name;
            $controller->$method($params);
        }
    }

    public static function getPage($url_array = array(), $display = true)
    {
        if (!$url_array) {
            $route = new Route();
            $route->setUrlType(HLM_URL_TYPE);
            $url_array = $route->getUrlArray();
        }
        $P['params'] = $url_array;
        unset($P['params']['app']);
        $url_array['params'] = $P['params'];
        if ($display) self::routeToCm($url_array);
        else {
            self::$_DISPLAY = false;
            $C = self::routeToCm($url_array);
            self::$_DISPLAY = true;
            return $C;
        }
    }
}
