<?php 
/**
 * ��ƪ�ɼ�
 * @author huliming  2014-9-12
 *
 */
class collectController extends Admin_controller {
    public $theme_dir = false;
	public $template_name = '/templates/admin/main';
	public function __construct() { 
		  parent::__construct();
		  $this->checkpower('manageallarticle');//Ȩ����֤
	} 
	
	//��ƪ�ɼ�����ͼ
	public function main($params = array()) {
		/*$f = $this->load('postbook','article');
		$f->init(JIEQI_ROOT_PATH.'/files/1.txt');
        $this->dump($f->getArticles());
	    exit('h');*/
		$dataObj = $this->model('collect');
		$this->display($dataObj->main($params));
	}
}

?>