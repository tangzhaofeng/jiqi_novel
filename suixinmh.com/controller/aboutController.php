<?php
/*关于我们的控制器*/ 
class aboutController extends Controller {
	//默认模板
	public $template_name = 'about';
	public $caching = false;
	
	public function index(){//关于书海
		$this->display();
	}
	public function business(){
		$this->display();
	}
	public function partner(){
		$this->display();
	}
	public function accession(){
		$this->display();
	}
	public function contact(){
		$this->display();
	}
	public function friendly(){
		if(strpos(JIEQI_CURRENT_URL,'about/friendly')){
			header("HTTP/1.1 301 Moved Permanently");
			header('location:'.str_replace('about/friendly','link.html',JIEQI_CURRENT_URL));exit(0);
		}
		$this->display();
	}
} 
?>
