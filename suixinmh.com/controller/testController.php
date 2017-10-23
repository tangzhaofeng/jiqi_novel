<?php 
/** 
 * 测试控制器 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class testController extends Controller { 
		public $template_name = 'test'; 
		public $caching = true;
		public $theme_dir = false;
		public $cachetime=5;
        public function main() { //print_r(Application::$_config);
		    //header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
			echo getenv("HTTP_CLIENT_IP").'='.time();
		   	/*$booktxt = JIEQI_ROOT_PATH.'/booktxt.txt';
		    if($ip_arrays = @file($booktxt)){
			    $ip_arrays = array_unique($ip_arrays);
			    $a_tmp = array();
			    foreach($ip_arrays as $v){
				    echo "update jieqi_article_article set display=1 where articlename='".trim($v)."';<br>";
				}
			}*/
			exit;
			/*if(isset($_REQUEST['page'])) $page = $_REQUEST['page'];
		        else $page= 1;
				$this->addConfig('system', 'configs');
				$this->addConfig('system', 'blocks');
				$this->setCacheid($page);
				$M = $this->model('test');
//				print_r($M->testDatabases());
				$C = $this->load('test',false);
				if(!$this->is_cached()){
					echo ' 执行了缓存中的数据查询代码';
					//print_r($this->getConfig('system', 'blocks','2'));
					//echo "test"; exit;
					//$data['text'] = "这里是陕西ggggggggggggggg"; 
					$data = array(
						'title'=>'这是我的第'.$page.'个网页',
						'list'=>array(
							1=>'第1条新闻_'.$page,
							2=>'第2条新闻_'.$page,
							3=>'第3条新闻_'.$page,
							4=>'第4条新闻_'.$page,
							5=>'第5条新闻_'.$page,
							6=>'第6条新闻_'.$page,
							7=>'第7条新闻_'.$page,
							8=>'第8条新闻_'.$page
						),
					);
				}
                $this->display($data); */
        } 

} 
?>