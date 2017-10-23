<?php
/**
 * 控制包月书包的相关方法
 * @copyright Copyright(c) 2014
 * @author snowdiva
 * @version 1.0
 */
class Mybookpackage {
    
    public $db;
    
    /**
     * 构造函数使用单例创建db对象
     */
    function __construct() {
        if (! is_object ( $this->db )) {
            $this->db = Application::$_lib ['database'];
        }
    }
    
    /**
     * 判断是否有书包阅读权限
     * @param type $articleid   文章id
     */
    public function is_bpuser($articleid, $uid) {
        if (!$articleid || intval($articleid)==0)
            return false;
//        $auth = $this->getAuth();
        $nowTime = time();
        $this->db->init('bookpackagesale', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->setFields('*');
        $this->db->criteria->add(new Criteria('accountid', $uid, '='));
        $this->db->criteria->add(new Criteria('bookid', '%"articleid":"'.$articleid.'",%', 'LIKE'));
        $this->db->criteria->add(new Criteria('endtime', $nowTime, '>='));
        $this->db->criteria->setLimit(1);
        $result = $this->db->lists();
        if (empty($result)) {
            return false;
        } else {
            return $result[0];
        }
    }
    
    /**
     * 添加阅读记录
     * @param type $saleid      购买书包记录id
     * @param type $bpid        书包id
     * @param type $chapterid   章节id
     * @param type $uid         用户id
     */
    public function add_bpclick($saleid, $bpid, $articleid, $chapterid, $uid, $uname) {
        $this->db->init('bookpackagestat', 'id', 'article');
        $this->db->setCriteria();
        $this->db->criteria->add(new Criteria('bpid', $bpid, '='));
        $this->db->criteria->add(new Criteria('bpsaleid', $saleid, '='));
        $this->db->criteria->add(new Criteria('articleid', $articleid, '='));
        $this->db->criteria->add(new Criteria('accountid', $uid, '='));
        $this->db->criteria->add(new Criteria('chapterid', $chapterid, '='));
        $res = $this->db->lists();
        if (empty($res)) {
            $addData = array(
                'bpid'                  => $bpid,
                'articleid'             => $articleid,
                'bpsaleid'              => $saleid,
                'accountid'             => $uid,
                'accountname'           => $uname,
                'chapterid'             => $chapterid,
                'clicktime'             => time()
            );
            if ($this->db->add($addData))
                return true;
        }
    }
}