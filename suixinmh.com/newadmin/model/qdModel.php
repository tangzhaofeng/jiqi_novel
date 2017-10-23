<?php 
/** 
 * 菜单 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class qdModel extends Model{

    function main($param){
        $auth=$this->getAuth();
        if ($param['action']=='del') {
            $id=intval($param['id']);
            if ($id) {
                $this->db->init("qdlist","id","system");
                $qdinfo=$this->db->get($id);
                if ($qdinfo['paymoney']) {
                    $this->fail("该渠道已经产生充值数据，不可删除");
                    exit();
                }
                if ($qdinfo['groupid']) {
                    $this->fail("该渠道属于打包渠道，请先修改或删除打包数据以后再删除本渠道");
                    exit();
                }
                if ($auth['groupid']!=JIEQI_GROUP_ADMIN && $qdinfo['cps_id']!=$auth['uid']) {
                    $this->fail("您无权操作不属于您的渠道信息");
                    exit();
                }
                $this->db->delete($id);
            }
        }

        $this->db->init("qdlist","id","system");
        $this->db->setCriteria(new Criteria('id', 0 , ">"));
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $this->db->criteria->add(new Criteria('cps_id', $auth['uid']));
        }
        if ($param['key']) {
            $this->db->criteria->add(new Criteria('qd', $param['key'].'%', "like"));
        }
        if(!$param['page']) $param['page'] = 1;
        $this->db->criteria->setSort('pdate');
        $this->db->criteria->setOrder('DESC');
        $pagesize=50;
        $this->db->criteria->setLimit($pagesize);
        $this->db->criteria->setStart(($param['page']-1) * $pagesize);

        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $result[]=$row;
        }

        include_once(HLM_ROOT_PATH.'/lib/html/page.php');
        $jumppage = new JieqiPage($this->db->getCount($this->db->criteria),$pagesize,$param['page']);
        $jumppage->setlink('', true, true);

        $regmod=$this->model("reg");
        $paymod=$this->model("pay");
        for($i=0;$i<count($result);$i++) {
            $result[$i]['name'] = iconv("GBK","utf-8",$result[$i]['name']);
            $result[$i]['articlename'] = iconv("GBK","utf-8",$result[$i]['articlename']);
            $qd=$result[$i]['qd'];
            $result[$i]['regusers']=$regmod->total_qd_reg_ext($qd);
            $payresult = $paymod->total_qd_pay_ext($qd);
            $result[$i]['paymoney'] = $payresult['smoney'];
            $result[$i]['paycount'] = $payresult['paycount'];
            $result[$i]['payusers'] = $payresult['payusers'];
            $pvresult=$regmod->total_qd_click_pv_ext($qd);
            $result[$i]['qdpv'] = $pvresult['qdpv'];
            $result[$i]['qdclick'] = $pvresult['qdclick'];
            if ($result[$i]['fee']) {
                $result[$i]['payx'] = round($result[$i]['paymoney']*10000/$result[$i]['fee'])/100 . '%';
            }
            else {
                $result[$i]['payx']='0%';
            }

            if ($result[$i]['pdate']) {
                if ($result[$i]['pdate']>='2016-01-01') {
                    $sec = time() - strtotime($result[$i]['pdate']);
                    $result[$i]['days'] = round($sec / 86400);
                }
                else {
                    $result[$i]['days'] = '/';
                }
            }
            else {
                $result[$i]['days']=0;
            }
        }

        $param['qd_rows'] = $result;
        $param['url_jumppage'] = $jumppage->whole_bar();
        return $param;
    }

    function addview($param) {
        $id=intval($param['id']);
        $auth=$this->getAuth();
        if ($id) {
            $this->db->init("qdlist","id","system");
            $qd_info = $this->db->get($id);
            if ($auth['groupid']!=JIEQI_GROUP_ADMIN && $qd_info['cps_id']!=$auth['uid']) {
                $this->fail("您无权操作不属于您的渠道信息");
                exit();
            }
            $qd=$qd_info['qd'];
            if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
                $x=explode('-',$qd);
                $qd=$x[1];
            }
            $param['qd'] = $qd;
            $param['name'] = iconv("GBK","UTF-8",$qd_info['name']);
            $param['fans'] = $qd_info['fans'];
            $param['url'] = $qd_info['url'];
            $param['wxh'] = iconv("GBK","UTF-8",$qd_info['wxh']);
            $param['fee'] = $qd_info['fee'];
            if ($param['groupid']>0)
                $param['fee'] = "打包渠道";
            else
                $param['fee'] = $qd_info['fee'];
            $param['action'] = "update";
        }
        else {
            $param['action'] = "insert";
        }
        return $param;
    }


    function add($param) {
        $action=$param['action'];
        $id=intval($param['id']);
        $fee=intval($param['fee']);
        $qd=trim($param['qd']);
        if (!ctype_alnum($qd)) {
            $this->fail("渠道编号只能是数字或者字母，不可包含其他字符");
            exit();
        }
        $auth=$this->getAuth();
        if ($auth['groupid']!=JIEQI_GROUP_ADMIN) {
            $qd=$auth['uid'].'-'.$qd;
            $cps_id=$auth['uid'];
        }
        else {
            $cps_id = 0;
        }
        $wxh=iconv("UTF-8","GBK",trim($param['wxh']));
        $fans=trim($param['fans']);
        $url=trim($param['url']);
        $name=iconv("UTF-8","GBK",trim($param['name']));

        $data_arr=array(
            "qd"=>$qd,
            'wxh'=>$wxh,
            'url'=>$url,
            'name'=>$name,
            'fee'=>$fee,
            'fans'=>$fans,
            'cps_id'=>$cps_id
        );



        $this->db->init("qdlist", "id", "system");
        if ($action == 'insert') {
            $this->db->setCriteria(new Criteria('qd', $qd , "="));
            //$this->db->criteria->add(new Criteria('qd', $qd, "="));
            if ($this->db->getCount()>0) {
                $this->fail("渠道编号已经存在，不可重复添加");
                exit();
            }
            $res = $this->db->add($data_arr);
        }
        else {
            $this->db->setCriteria(new Criteria('qd', $qd , "="));
            //$this->db->criteria->add(new Criteria('qd', $qd, "="));
            $this->db->criteria->add(new Criteria('id', $id, "<>"));
            if ($this->db->getCount()>0) {
                $this->fail("渠道编号(".$qd.")已经存在，不可重复");
                exit();
            }
            if ($this->db->edit($id,$data_arr)) {
                $res = $param['id'];
            }
            else {
                $res=false;
            }
        }
        if ($res) {
            $url="index.php?controller=qd";
            if ($action=="insert")
                $this->succ($url, '信息提示', '渠道信息添加成功');
            else
                $this->succ($url, '信息提示', '渠道信息修改成功');
            exit();
        }
        else {
            if ($action=="insert") {
                $this->fail("添加渠道打包信息失败");
            }
            else {
                $this->fail("编辑渠道打包信息失败");
            }
            exit();
        }
    }


    function addgroupview($param) {
        $groupid=intval($param['id']);
        if ($groupid) {
            $this->db->init("qdgroup","id","system");
            $group_info = $this->db->get($groupid);
            $param['qdlist'] = str_replace(",","\n",$group_info['qdlist']);
            $param['groupname'] = iconv("GBK","utf-8",$group_info['groupname']);
            $param['fee'] = $group_info['fee'];
            $param['action'] = "update";
        }
        else {
            $param['action'] = "insert";
        }
        return $param;
    }

    function addgroup($param) {
        $action=$param['action'];
        $qdlist=explode("\n",trim($param['qdlist']));
        if (count($qdlist)<2) {
            $this->fail("渠道号至少有2条");
            exit();
        }
        $fee=intval($param['fee']);
        if (!$fee) {
            $this->fail("必须填入正确的打包价格");
            exit();
        }
        for ($i=0;$i<count($qdlist);$i++) {
            $qdlist[$i] = trim($qdlist[$i]);
            if (!$qdlist[$i]) {
                $this->fail("请填写正确的渠道编号，必须是数字");
                exit();
            }
            $this->db->init('qdlist','id','system');
            $data=$this->db->get($qdlist[$i]);
            if (!$data) {
                $insertsqlarr[]=array("qd"=>$qdlist[$i]);
            }

            if ($data['groupid']>0 && $data['groupid']!=$param['id']) {
                $this->fail("渠道编号:".$qdlist[$i]."已经在其他打包列表中，不能重复添加");
                exit();
            }
        }

        $qdlistx=implode(",",$qdlist);
        $groupname=iconv("utf-8","gbk",trim($param['groupname']));
        $data_arr = array("groupname" => $groupname, "fee" => $fee, "qdlist" => $qdlistx);
        $this->db->init("qdgroup", "id", "system");
        if ($action == 'insert') {
            $res = $this->db->add($data_arr);
        }
        else {
            if ($this->db->edit($param['id'],$data_arr)) {
                $res = $param['id'];
            }
            else {
                $res=false;
            }
        }
        if ($res) {
            for ($i=0;$i<count($qdlist);$i++) {
                $qd=$qdlist[$i];
                $this->db->init('qdlist','id','system');
                if ($this->db->get($qdlist[$i])) {
                    $sql="update ".$this->dbprefix("system_qdlist")." set groupid=$res where qd='".$qdlist[$i]."'";
                }
                else {
                    $sql="insert into ".$this->dbprefix("system_qdlist")." (qd,groupid) values('$qd','$res')";
                }
                $this->db->query($sql);
            }
            $url="index.php?controller=qd&method=group";
            if ($action=="insert")
                $this->succ($url, '信息提示', '渠道打包信息添加成功');
            else
                $this->succ($url, '信息提示', '渠道打包信息修改成功');
            exit();
        }
        else {
            if ($action=="insert") {
                $this->fail("添加渠道打包信息失败");
            }
            else {
                $this->fail("编辑渠道打包信息失败");
            }
            exit();
        }
    }

    function delgroup($param) {
        $groupid=intval($param['delid']);
        $sql="delete from ".$this->dbprefix("system_qdgroup")." where id=".$groupid;
        if ($this->db->query($sql)) {
            $sql = "update " . $this->dbprefix("system_qdlist") . " set groupid=0 where groupid=$groupid";
            if ($this->db->query($sql)) {
                $url="index.php?controller=qd&method=group";
                $this->succ($url, '信息提示', '渠道打包删除成功');
            }
        }

        $this->fail("渠道打包信息删除失败");

    }

    function group_list($param){
        $this->db->init("qdgroup","id","system");
        $this->db->setCriteria(new Criteria('id', 0 , ">"));
        $res=$this->db->queryObjects();
        $result=array();
        while ($row=$this->db->getRow($res)) {
            $row['groupname']=iconv("GBK","UTF-8",$row['groupname']);
            $result[]=$row;
        }
        $param['qd_group_rows'] = $result;
        return $param;
    }

    function get_qdlist($qd1,$qd2) {
        $qdlist=array();
        $r=$this->db->query("select * from jieqi_system_qdlist where cps_id>0");
        while ($row=$this->db->getRow($r)) {
            if ($qd1 && $qd2) {
                $q=$row['qd'];
                $x=explode('-',$q);
                $n=$x[1];
                if ($qd1 && $qd2) {
                    if ($n>=$qd1 && $n<=$qd2) {
                        $qdlist[]=$q;
                    }
                }
                elseif ($qd1) {
                    if ($n==$qd1) {
                        $qdlist[]=$q;
                    }
                }
            }
        }
        return $qdlist;
    }




    function succ($url = '', $title = '', $content = '', $ext = array())
    {

        if (!$url) $url = JIEQI_REFER_URL;
        if (!$title) $title = LANG_NOTICE;
        if (!$content) $content = LANG_DO_SUCCESS;
        if (empty($_REQUEST['ajax_request'])) {
            if (JIEQI_VERSION_TYPE != '' && JIEQI_VERSION_TYPE != 'Free') {
                include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
                $url = jieqi_htmlstr($url);
                $title = jieqi_htmlstr($title);
                $jieqiTpl =& JieqiTpl::getInstance();
                $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'pagetitle' => $title, 'title' => $title, 'content' => $content, 'url' => $url, 'ext' => $ext));
                $jieqiTpl->setCaching(0);
                $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/jumppage_utf8.html');
            } else {
                echo '<html><head><meta http-equiv="content-type" content="text/html; charset=' . JIEQI_CHAR_SET . '" /><meta http-equiv="refresh" content="3; url=' . $url . '">
<title>' . jieqi_htmlstr($title) . '</title><link rel="stylesheet" type="text/css" media="all" href="' . JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style.css" /></head><body><div id="msgboard" style="position:absolute; left:210px; top:150px; width:350px; height:100px; z-index:1;"><table class="grid" width="100%" border="0" cellspacing="1" cellpadding="6" ><caption>' . jieqi_htmlstr($title) . '</caption><tr><td class="even"><br />' . $content . '<br /><br />如不能自动跳转，<a href="' . $url . '">点击这里直接进入！</a><br /><br />程序设计：<a href="#">QQ329222795</a><br /><br /></td></tr></table></div></body></html>';
            }
        } else {
            header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
            include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
            $data = array('status' => 'OK', 'msg' => jieqi_gb2utf8($content), 'jumpurl' => urldecode($url));
            if ($_REQUEST['CALLBACK']) {
                echo($_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');');
            } else echo(json_encode($data));
        }
        jieqi_freeresource();
        exit;
    }

    function fail($errorinfo = '', $ext = array())
    {
        if (!$errorinfo) $errorinfo = LANG_DO_FAILURE;
        if (defined('JIEQI_NOCONVERT_CHAR') && !empty($GLOBALS['charset_convert_out'])) @ob_start($GLOBALS['charset_convert_out']);
        $debuginfo = '';
        if (defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0) {
            $trace = debug_backtrace();
            $debuginfo = 'FILE: ' . jieqi_htmlstr($trace[0]['file']) . ' LINE:' . jieqi_htmlstr($trace[0]['line']);
        }
        if (!$errorinfo) $errorinfo = LANG_DO_FAILURE;
        if (empty($_REQUEST['ajax_request'])) {
            include_once(JIEQI_ROOT_PATH . '/lib/template/template.php');
            $jieqiTpl =& JieqiTpl::getInstance();
            $jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/', 'jieqi_themecss' => JIEQI_URL . '/themes/' . JIEQI_THEME_NAME . '/style/style.css', 'errorinfo' => $errorinfo, 'debuginfo' => $debuginfo, 'jieqi_sitename' => JIEQI_SITE_NAME, 'ext' => $ext));
            $jieqiTpl->setCaching(0);
            $jieqiTpl->display(JIEQI_ROOT_PATH . '/themes/' . JIEQI_THEME_NAME . '/msgerr_utf8.html');
        } else {
            header('Content-Type:text/html; charset=' . JIEQI_CHAR_SET);
            include_once(JIEQI_ROOT_PATH . '/include/changecode.php');
            $data = array('status' => 'NO', 'msg' => jieqi_gb2utf8($errorinfo), 'jumpurl' => '');
            if ($_REQUEST['CALLBACK']) {
                echo $_REQUEST['CALLBACK'] . '(' . json_encode($data) . ');';
            } else echo(json_encode($data));
        }
        jieqi_freeresource();
        exit;
    }

} 

?>