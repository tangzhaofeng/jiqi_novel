<?php
/**
 * 目录业务模型
 * @author chengyuan  2014-8-6
 *
 */
class catalogModel extends Model {
	/**
	 * 获取目录列表
	 * @param unknown $articleid
	 * @param string $obj
	 * 2014-8-6 下午3:26:28
	 */
	public function catalogList($articleid,$order,$page) {
		$package = $this->load ( 'articleWap', '3g' );
		$data = $package->article_vars($v);//article表字段的格式化
		if (!$package->loadOPF ( $articleid )) {
			$this->addLang ( 'article', 'article' );
			$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
			$this->printfail ( $jieqiLang ['article'] ['article_not_exists'] );
		}
		$data = $package->getCatalog ($order,$page);
		return $data;
// 		return $package->showIndex ( $obj );
	}
	
	/**
	 * 详情页使用的目录列表
	 */
	public function getLists($articleid, $pageCounts = 20, $order = 'asc') {
		$package = $this->load ( 'articleWap', '3g' );
		$data = $package->article_vars($v);//article表字段的格式化
		if (!$package->loadOPF ( $articleid )) {
			$this->addLang ( 'article', 'article' );
			$jieqiLang ['article'] = $this->getLang ( 'article' ); // 所有语言包配置赋值
			$this->printfail ( $jieqiLang ['article'] ['article_not_exists'] );
		}
		$data = $package->getCatalog ($order, 1, $pageCounts);
		return $data;
	}
}

?>