<?php 
/** 
 * 测试模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class testModel extends Model{ 
    
        function getArticles(){ 
             $this->db->init('article','articleid','article');
			 //print_r($this->db->get(4));exit;
			 $this->db->setCriteria();
			 //$this->db->criteria->setTables('jieqi_article_article');
			 //$this->db->criteria->add(new Criteria('initial', "D"));
			 $this->db->criteria->setSort('articleid');
	         $this->db->criteria->setOrder('DESC');
			 return array(
			     'data'=>$this->db->lists(20,$_REQUEST['page']),
				 'jumppage'=>$this->db->getPage()
			 );
        } 
		//查询
		function queryArticle($name){
		
			$data = array();
			//$this->db->init('users','uid','system');
			$this->db->init('article','articleid','article');
			 //print_r($this->db->get(4));exit;
			$this->db->setCriteria(new Criteria('articlename','%'.$name.'%','like'));
			$this->db->criteria->add(new Criteria('author','大盗','='),'or');
			$this->db->criteria->setFields('articlename,author');
			$this->db->criteria->setSort('articleid');
	        $this->db->criteria->setOrder('DESC');
			$data['articlerows'] = $this->db->lists(20,$_REQUEST['page']);
			//print_r($this->db->lists(20,$_REQUEST['page']));
			//exit;
			$data['url_jumppage'] = $this->db->getPage();
			// return array(
//			     'data'=>$this->db->lists(20,$_REQUEST['page']),
//				 'jumppage'=>$this->db->getPage()
//			 );
			return $data;
		}
} 

?>