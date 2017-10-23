<?php 
/** 
 * 文章活动 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */
class huodongController extends chief_controller
{

	public $template_name = 'huodong';
	public $caching = false;
	public $theme_dir = false;

	public function __construct()
	{
		parent::__construct();
		//检查登陆
		$this->checklogin(false, false, JIEQI_MODULE_NAME);
	}

	//推荐
	public function vote($params = array())
	{
		$dataObj = $this->model('huodong', 'article');
		$params['nosubmitcheck'] = true;

        $result = $dataObj->vote($params);
        if ($result['stat']) {
            $result['msg'] = iconv("gbk","utf-8",$result['msg']);
            echo json_encode($result);
        } else {
            $this->display($result);
        }
	}

	//打赏
	public function reward($params = array())
	{
		$dataObj = $this->model('huodong', 'article');
		$params['nosubmitcheck'] = true;
		$data = $dataObj->reward($params);
		//print_r($data);
		$this->display($data);
	}

	//月票
	public function vipvote($params = array())
	{
		$dataObj = $this->model('huodong', 'article');
		$params['nosubmitcheck'] = true;
		$result = $dataObj->vipvote($params);
		if ($result['stat']) {
            $result['msg'] = iconv("gbk","utf-8",$result['msg']);
			echo json_encode($result);
		} else {
			$this->display($dataObj->vipvote($params));
		}
	}

	//加入书架
	function addBookCase($params = array())
	{
		$dataObj = $this->model('huodong', 'article');
		$dataObj->addBookCase($params);
	}

	function lunpan($params) {
        $this->printfail('目前没有活动');
		$dataObj = $this->model('huodong', 'article');
        $this->display($dataObj->lunpan($params),'turntable');

//        if ($params['action']) {
//            echo json_encode(array(
//                "stat" => "succ",
//                "num" => rand(1, 8),
//				"times" => 9,
//                "msg" => iconv("gbk", "utf-8", "恭喜您中奖了")
//            ));
//            exit();
//        }
//        else {
//			$params['times'] = 10;
//			$params['list']=array(
//				array('info'=>'张三中了1888书券'),
//				array('info'=>'李四中了888书券'),
//				array('info'=>'王五中了168书券')
//			);
//            $this->display($params,'turntable');
//
//        }
	}

    function newyear($params) {
		$this->printfail('目前没有活动');
        $this->display($params,'active');
    }

    function dati($params) {
        $this->printfail('目前没有活动');
        $dataObj = $this->model('huodong', 'article');
        $this->display($dataObj->dati($params),'dati');
        $this->display(array(),'dati');
    }

//    function paytest($params) {
//        $auth=$this->getAuth();
//        $mod=$this->model('pay');
//        $mod->insert_activity(array(
//            'uid'=>$auth['uid'],
//            'money'=>5000
//        ));
//        echo 'ok';
//    }
} 
?>