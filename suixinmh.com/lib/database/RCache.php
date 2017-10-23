<?php

/**
 * Created by PhpStorm.
 * User: ludianjun
 * Date: 16/1/14
 * Time: 下午2:46
 */
class DataCache extends JieqiObject
{
    function __construct()
    {
        $this->JieqiObject();
    }

    function &retInstance()
    {
        static $instance = array();
        return $instance;
    }

    function &getInstance($host = '', $port = '', $getnew = false)
    {
        $instance =& DataCache::retInstance();
        if (empty($host)) $host = JIEQI_REDIS_HOST;
        if (empty($port)) $port = JIEQI_REDIS_PORT;
        $inskey = md5($host . ',' . $host);
        $getnew = ($host == JIEQI_REDIS_HOST && $port == JIEQI_REDIS_PORT) ? false : true;
        if (!isset($instance[$inskey]) || $getnew) {
            $redis=new Redis;


            if ($getnew) {
                if (!$redis->connect($host, $port)) {
                    jieqi_printfail('Can not connect to redis server!');
                    return false;
                } else {
                    return $redis;
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
class RCache extends JieqiObject
{
    var $redis;
    function __construct() {
        if (! is_object ( $this->db )) {
            $this->db = Application::$_lib ['database'];
        }

        if (! is_object ( $this->redis )) {
            $this->redis = new Redis();
            $this->redis->connect(JIEQI_REDIS_HOST,JIEQI_REDIS_PORT);
        }
    }

    public function get_article($aid) {
        $data = $this->redis->hGet('article',$aid);
        if (!$data) {
            $dataObj = $this->model('articleinfo');
            $data = $dataObj->articleinfoView($params = array("aid" => $aid));
            $this->redis->hSet('article', $aid, $data);
            return $data;
        }
        else {
            return $data;
        }
    }

    public function del_article($aid) {
        $this->redis->hDel('article',$aid);
    }



}