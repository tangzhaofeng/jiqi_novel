<?php 
/** 
 * ฒโสิฤฃะอ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class homeModel extends Model{ 
    
        function getArticles(){ 
             $this->db->init('article','articleid','article');
			 //print_r($this->db->get(4));exit;
			 $this->db->setCriteria();
			 //$this->db->criteria->setTables('jieqi_article_chapter as t1 inner join');
			 //$this->db->criteria->add(new Criteria('initial', "D"));
			 $this->db->criteria->setSort('articleid');
	         $this->db->criteria->setOrder('DESC');
			 return array(
			     'data'=>$this->db->lists(20,$_REQUEST['page']),
				 'jumppage'=>$this->db->getPage()
			 );
        } 
} 

?>