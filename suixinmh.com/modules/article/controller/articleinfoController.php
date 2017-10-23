<?php 
/** 
 * 书籍详情控制器 * @copyright   Copyright(c) 2014 
 * @author      liujilei* @version     1.0 
 */ 
class articleinfoController extends Controller
 { 
  	public $template_name = 'book';// articleInfo book
	public $theme_dir = false;
	
	public function main($params = array())
	{    
	     header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
		 $this->setCacheid($params['aid']);
		 if(!$this->is_cached()){
		 	//echo ' 执行了缓存中的数据查询代码';
			$dataObj = $this->model('articleinfo');
			$data = $dataObj->articleinfoView($params);
		}
		//测试点击量
		$dataObj = $this->model('article');
		$dataObj->statisticsVisit($params['aid']);
		$this->display($data); 
	}
    public function downlink($params = array()){
	    $params['aid']=intval($params['aid']);
		$url = $this->geturl('article', 'articleinfo', 'aid='.$params['aid']);
		$articlename = urldecode($params['articlename']);
        $articlename = trim($articlename);
        $articlename = strip_tags($articlename,""); //清除HTML等代码
        $articlename = ereg_replace("\t","",$articlename); //去掉制表符号
        $articlename = ereg_replace("\r\n","",$articlename); //去掉回车换行符号
        $articlename = ereg_replace("\r","",$articlename); //去掉回车
        $articlename = ereg_replace("\n","",$articlename); //去掉换行
        $articlename = ereg_replace(" ","",$articlename); //去掉空格
        $articlename = ereg_replace("'","",$articlename); //去掉单引号

		$Shortcut = "[InternetShortcut] 
		URL=$url
		IDList= 
		[{000214A0-0000-0000-C000-000000000046}] 
		Prop3=19,2 
		"; 
		Header("Content-type: application/octet-stream"); 
		header("Content-Disposition: attachment; filename=$articlename.url;"); 
		echo $Shortcut; 
		exit();
	}
	
	public function vipjump($params = array()){
		$dataObj = $this->model('articleinfo');
		$data = $dataObj->vipjump($params);
		$this->display($data);
	} 
		
	public function guessLike($params = array()){
		$dataObj = $this->model('articleinfo');
		$data = $dataObj->articleinfoView($params);
		$this->display($data,'guesslike');
	}
	
	
 }
?>