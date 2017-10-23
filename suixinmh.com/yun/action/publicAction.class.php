<?php
/**================================
 * 公共操作处理类（不进行权限验证的操作模块:public）
  * @author Listen
  * @email listen828@vip.qq.com
  * @version: 1.0 data
  * @package 后台管理系统
 */
ACCESS_GRANT != true && exit ( "forbiden!" );
class publicAction extends Action {
	
	// 加载登录页面内容
	public function login() {
		if (isAjax ())
			ajaxReturn ( C ( 'Error:LoginRequestModeError' ), 300 );
		if ($this->adm ['uid'] > 0)
			header ( "Location: " . URL_INDEX );
	}
	// 加载异步登录界面
	public function ajaxLogin() {
		if (isAjax ()) {
			if ($mUser ['uid'] > 0)
				ajaxReturn ( $lang ['tips'] ['LoginOk'] );
		} else {
			toUrl ( 'ERROR:404' );
		}
	}
	// 加载验证码
	public function loadVerify() {
		$len = isset ( $_GET ['plen'] ) ? $_GET ['plen'] : 4;
		$mode = isset ( $_GET ['mode'] ) ? $_GET ['mode'] : 1;
		$type = isset ( $_GET ['type'] ) ? $_GET ['type'] : 'gif';
		require_once (CLASS_DIR . 'Image.class.php');
		Image::buildImageVerify ( $len, $mode, $type );
	}
	// 验证登录
	public function loginIn() {
		$pData = $_POST;
		$flag = 1; // 登陆状态标记
		$this->model->setTable ( ADMIN );
		$this->model->setKey ( 'account' );
		$uInfo = $this->model->getOneRecordByKey ( $pData ['username'] );
		$flag == 1 && ! ($uInfo && $uInfo ['password'] == sha1( $pData ['password'] .$uInfo['createtime'])) && $flag = 3;
		if ($flag == 1) { // 登录成功
			$_SESSION ['manager'] = array (
					'uid' => $uInfo ['uid'],
					'account' => $uInfo ['account'],
					'name' => $uInfo ['name'],
					'groupid' => $uInfo ['groupid'],
					'webid' => $uInfo ['webid']
			);
			$field ['uid'] = $uInfo ['uid'];
			$field ['logintime'] = date ( "Y-m-d H:i:s", time () );
			$field ['loginip'] = $_SERVER["REMOTE_ADDR"];
			$field ['state'] = 1;
			
			$this->model->setTable ( ADMIN );
			$this->model->setKey ( 'uid' );
			$this->model->upRecordByKey ( $field );
			$this->model->adminLogin($uInfo ['uid'],$uInfo ['account'],$uInfo ['name']);
			toUrl ( 'INDEX' );
			exit;
		} else {
			$this->model->adminLogin(0,$pData['username'],'');
			toUrl ( 'LOGIN' );
			exit;
		}
	}
	// 退出登录
	public function loginOut() {
		$field ['uid'] = $_SESSION ['manager'] ['uid'];
		$field ['state'] = 0;
		$this->model->setTable ( ADMIN );
		$this->model->setKey ( 'uid' );
		$this->model->upRecordByKey ( $field );
		$_SESSION = array ();
		session_destroy ();
		toUrl ( 'LOGIN' );
	}
	// 不存在的页面跳转处理
	public function error404() {
		global $mUser, $smarty;
		
		$this->smarty = $smarty;
		
		$mUser ['module'] = $_REQUEST ['action'];
		$mUser ['option'] = $_REQUEST ['opt'];
		
		$this->smarty->assign ( 'request', $_REQUEST );
		$this->smarty->assign ( 'mUser', $mUser );
	}
	
	// private函数
	private function writeLog($str, $file) {
		if (WRITELOG)
			file_put_contents ( $file, $str, FILE_APPEND );
	}
}