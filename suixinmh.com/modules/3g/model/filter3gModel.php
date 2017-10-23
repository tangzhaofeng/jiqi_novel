<?php
/**
 * 业务逻辑模型：不对应任何数据库
 * @author liuxiangbin
 * @create 2015-04-02 14:19:06
 */
class filter3gModel extends Model {
	
	// 默认选择条件
//	protected $default_v = 0;
	
	private $size = array(
					0=>array('text'=>'全部'),
					1=>array('text'=>'30万以下'),
					2=>array('text'=>'30万-50万'),
					3=>array('text'=>'50万-100万'),
					4=>array('text'=>'100万-200万'),
					5=>array('text'=>'200万以上'),
			);
	private $operate = array(
					0=>array('text'=>'默认'),
					1=>array('text'=>'字数'),
//					2=>array('text'=>'周点击'),
//					3=>array('text'=>'月点击'),
					4=>array('text'=>'点击'),
//					5=>array('text'=>'周收藏'),
//					6=>array('text'=>'月收藏'),
					7=>array('text'=>'收藏'),
					8=>array('text'=>'销量')
			);
	private $free = array(
					0=>array('text'=>'不限'),
					1=>array('text'=>'只看免费'),
					2=>array('text'=>'只看VIP')
			);

	/**
	 * 返回3g自有的排序方式
	 */
	public function getOperate() {
		return $this->operate;
	}
}
