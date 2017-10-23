<?php
/**
 * article模块base控制器
 * @copyright   Copyright(c) 2014
 * @author      chengyuan *
 * @version     1.0
 */
class chief_controller extends Controller {
	//默认模板
	public $caching = false;
	/**
	 * article根控制器，統一處理登陸后臺管理等的權限驗證
	 * <table border=1 >
	 * <tr><td rowspan=2>方法</td><td rowspan=2>说明</td><td colspan=2>权限类别</td></tr>
	 * <tr><td>登陆</td><td>作者面板</td></tr>
	 * <tr><td>appwV</td><td>申请作者视图</td><td>√</td><td>X</td></tr>
	 * <tr><td>appw</td><td>申请作者提交</td><td>√</td><td>X</td></tr>
	 * <tr><td>synchronousMakePack</td><td>重新生成opf等静态文件</td><td>X</td><td>X</td></tr>
	 * <tr><td>regularAudits</td><td>定时审核</td><td>X</td><td>X</td></tr>
	 * <tr><td>bcView</td><td>书架视图</td><td>√</td><td>X</td></tr>
	 * <tr><td>bcBatch</td><td>书架操作</td><td>√</td><td>X</td></tr>
	 * <tr><td>Others</td><td>其他方法</td><td>√</td><td>√</td></tr>
	 * </table>
	 */
	public function __construct() {
	    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
		parent::__construct ();
		//检查登陆
		if($this->getRequest('method') != 'synchronousMakePack' && $this->getRequest('method') != 'regularAudits'){
			$this->checklogin();
		}
		//检查文章管理权限，直接提示
		$this->addConfig('article','power');
		//申请作者不进行authorpanel权限判断
		if($this->getRequest('method') != 'appwV' &&  $this->getRequest('method') != 'bcView' &&  $this->getRequest('method') != 'bcBatch' &&  $this->getRequest('method') != 'appw' && $this->getRequest('method') != 'synchronousMakePack' && $this->getRequest('method') != 'regularAudits'){
			if(!$this->checkpower ($this->getConfig('article','power','authorpanel'), $this->getUsersStatus (), $this->getUsersGroup (), true )){
			     header('location:'.$this->geturl('article','article','SYS=method=appwV'));
			}
		}
		$iswriter = $this->checkpower ($this->getConfig('article','power','newarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true);
		$delown = $this->checkpower ($this->getConfig('article','power','delmyarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true);
		$delall = $this->checkpower ($this->getConfig('article','power','delallarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true);
		$mangall=$this->checkpower($this->getConfig('article','power','manageallarticle'), $this->getUsersStatus (), $this->getUsersGroup (), true);
// 		if($iswriter){
// 			//作者有没有创建过书
// 			$article =  $this->model('article','article');
// 			$this->assign('hasCreate', $article->createArticle());
// 		}
		$this->assign('iswriter',$iswriter);
		$this->assign('delown',$delown);
		$this->assign('delall',$delall);
		$this->assign('mangall',$mangall);
	}
}
?>