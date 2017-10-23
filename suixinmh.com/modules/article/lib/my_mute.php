<?php
include_once (JIEQI_ROOT_PATH . '/lib/my_cachetable.php');//缓存整张数据表处理类
/**
 * jieqi_article_mute表的数据库访问类缓存类
 * @author chengyuan 2015-5-19 下午1:59:33
 */
class MyMute extends Mycachetable{
	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function MyMute(){
	     $this->init('mute', 'muteid', 'article');
	}
}
?>