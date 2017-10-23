<?php
class  base extends JieqiObject{
	/**
	 * 是否第一次输出，第一次逐行输出填充缓冲区
	 * @var unknown
	 */
	private $first_out = true;
	/**
	 * echo刷新输出
	 * @param unknown $msg	输出内容
	 * @param string $br	是否带<br/>
	 * 2014-9-9 上午11:36:25
	 */
	 public function out_msg($msg,$br=true) {
		if($br)$msg = $msg.'<br>';
		$this->out($msg);
	}
	/**
	 * 错误或异常输出红色信息
	 *
	 * @param unknown $msg
	 *        	2014-7-17 上午10:05:18
	 */
	public function out_msg_err($msg) {
		$msg =  '<font color=red>' . $msg . '</font><br>';
		$this->out($msg);
	}
	/**
	 * 底层逐行输出，会根据服务器的不同自动填充缓冲区。
	 * @param unknown $msg
	 */
	private function out($msg){
		$sapi = php_sapi_name();
		if($sapi == 'cgi-fcgi'){
			echo str_pad($msg,1024*64);
		}else{
			if($this->first_out){
				echo str_repeat(' ',4096);
				$this->first_out = false;
			}
			echo $msg;
		}
		ob_flush();
		flush();
	}
	/**
	 * 初始化DB，如果需要访问数据库，请在子类的构造函数中调用，以保证子类可以访问数据库操作
	 * 2014-7-1 下午4:22:15
	 */
 	protected function initDB(){
		if (! is_object ( $this->db )) {
			$this->db = Application::$_lib ['database'];
		}
	}
	/**
	 * 安全xml
	 * <p>
	 * \v（垂直换行符，ASCII=11）之类的引起xml无法解析
	 * <p>
	 *  xml里面的<![CDATA[ ]]>，虽然可以放各种各样的特殊字符，但还是有些字符放不进去，
	 *  因为xml允许的字符范围是"#x9 | #xA | #xD | [#x20-#xD7FF] | [#xE000-#xFFFD] | [#x10000-#x10FFFF]"，
	 *  也就是说\x00-\x08,\x0b-\x0c,\x0e-\x1f这三组字符是不允许出现的
	 * @param unknown $xml
	 * @return unknown
	 */
	protected function saleXml($xml){
		$xml = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/","",$xml);
		return $xml;
	}
}
?>