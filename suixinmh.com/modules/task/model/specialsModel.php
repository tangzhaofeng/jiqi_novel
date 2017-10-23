<?php
/**
 * 任务操作模型：前台用户操作模型
 * @auther by: zhangxue
 * @createtime : 2015-02-11
 */

class specialsModel extends Model {
	
	public function getLuckydraw($params = array()) {
		$package = $this->load('questtask', 'task');
		$package->judge($params); 
	}
}
