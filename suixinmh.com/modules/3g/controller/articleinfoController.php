<?php
/**
 * 书库控制器
 * @author liujilei  2014-7-18
 *
 */
 
class articleinfoController extends chief_controller {

	//public $caching = false;
	public $theme_dir = false;
	//public $cacheid = 'fff';
	//public $cachetime=5;
        public $caching = true;
	public function main($params = array()) {
	    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate'); 
		$this->setCacheid($params['aid']);
		 if(!$this->is_cached()){
			$dataObj = $this->model('articleinfo','article');
			$data = $dataObj->articleinfoView($params);
			
			$catalogObj = $this->model('catalog','3g');
			$dat = $catalogObj->getLists($params['aid'], 5);
			$datDesc = $catalogObj->getLists($params['aid'], 5, 'desc');
			$data['indexrows'] = $dat['indexrows'];
			$data['indexrows_d'] = $datDesc['indexrows'];
			
			$modelObj = $this->model('reviews3g','3g');
			$data1 = $modelObj->main($params, 6);
			$data['reviewrows'] = $data1;
			
			//点击量
			//$dataObj = $this->model('article','article');
			//$dataObj->statisticsVisit($params['aid']);
			
			//查询打赏次数
			$articleinfoObj = $this->model('articleinfo','article');
			$data['votenum'] = $articleinfoObj->votenum($params['aid']);

			 $auth=$this->getAuth();
			 if ($auth['uid']) {
				 $hmod = $this->model("huodong", "article");
				 $vipvote = $hmod->vipvote($params);
				 $vote = $hmod->vote($params);

				 $data['egolds'] = $vote['egolds'];
				 $data['maxvote'] = $vote['maxvote'];
				 $data['getscore'] = $vote['getscore'];

				 $data['vip_maxvote'] = $vipvote['maxvote'];
				 $data['vip_getscore'] = $vipvote['getscore'];
                 $data['login'] = 1;
			 }
			 else {
				 $data['egolds'] = 0;
				 $data['maxvote'] = 0;
				 $data['getscore'] = 0;

				 $data['vip_maxvote'] = 0;
				 $data['vip_getscore'] = 0;

         $data['login'] = 0;
			 }
		}
	
		// 点击量过万处理
		$data['visit_w'] = sprintf("%.2f", $data['visit']/10000);
		$data['visit_qw'] = sprintf("%.2f", $data['visit']/10000000);
		//点击量
		$dataObj = $this->model('article','article');
		$dataObj->statisticsVisit($params['aid']);
		$this->display($data,'articleinfo',false);
	}


	public function vipjump($params = array()){
		$this->display();
	}

}

?>