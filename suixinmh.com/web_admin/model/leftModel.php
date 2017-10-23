<?php 
/** 
 * ื๓ฒเฒหตฅ * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class leftModel extends Model{
        function getMenu(){
		     global $jieqiAdminmenu;
             $this->db->init('modules','mid','system');
			 $this->db->setCriteria(new Criteria('publish', "1"));
			 $this->db->criteria->setSort('weight');
	         $this->db->criteria->setOrder('ASC');
			 $this->db->queryObjects();
			 $jieqiModary=array();
			 while($v = $this->db->getObject()){
				 $jieqiModary[$v->getVar('name','n')] = array('name' => $v->getVar('name','n'), 'caption' => $v->getVar('caption', 'n'), 'description' => $v->getVar('description', 'n'), 'version'=>sprintf("%0.2f", intval($v->getVar('version', 'n'))/100), 'vtype'=>$v->getVar('vtype', 'n'), 'publish' => $v->getVar('publish', 'n'));
	             jieqi_getconfigs($v->getVar('name','n'), 'adminmenu');
			 }
			 jieqi_getconfigs('system', 'adminmenu');
			 return array(
				  'jieqimodules'=>$jieqiModary,
				  'adminmenus'=>$jieqiAdminmenu
			 );
        } 
} 

?>