<?php
/**
 * 书库控制器
 * @author huliming  2014-6-10
 *
 */
class shukuController extends Controller
{
	public $caching = false;
	/**
	 * 书库查询，默认入口
	 * @param unknown $params
	 */
	public function main($params = array()){
	    //header('Cache-Control:max-age='.JIEQI_CACHE_LIFETIME);
		header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		//验证数据有效性
		$dataObj = $this->model('shuku');
		$data = $dataObj->query($params);//print_r($data);
		$this->display($data);
	}
}
?>