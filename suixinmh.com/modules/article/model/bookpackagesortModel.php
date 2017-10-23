<?php
/**
 * 书包自定义配置项抽象模型：不涉及数据库，用作后期扩展
 * @auther by: liuxiangbin
 * @createtime : 2014-12-17
 */

class bookpackagesortModel extends Model {
    
    /**
     * 获得当前书包对应分类
     * @param type $params
     */
    public function get_bosort($params) {
        $this->addConfig('article', 'bookpackage');
        $bpSort = $this->getConfig('article', 'bookpackage');
//        $this->dump($bpSort);
        return $bpSort;
    }
}