<?php 
/** 
 * 系统管理->授权信息 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class licenseModel extends Model{
        public function main(){
		    global $jieqi_license_ary;
			$ret = array();
			$ret['license_domain'] = str_replace('|', ' ', $jieqi_license_ary[1]);
			if(defined('JIEQI_VERSION_TYPE') && defined('LANG_VERSION_'.strtoupper(JIEQI_VERSION_TYPE))) $ret['license_domain'] = constant('LANG_VERSION_'.strtoupper(JIEQI_VERSION_TYPE));
			else $ret['license_domain'] = '';
			
			$jieqiModary=array();
			$this->db->init('modules','mid','system');
			$this->db->setCriteria(new Criteria('publish', 1));
			$this->db->criteria->setSort('weight');
			$this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$modules=array();
			while($v = $this->db->getObject()){
				$jieqiModary[$v->getVar('name','n')] = array('name' => $v->getVar('name','n'), 'caption' => $v->getVar('caption', 'n'), 'description' => $v->getVar('description', 'n'), 'version'=>sprintf("%0.2f", intval($v->getVar('version', 'n'))/100), 'vtype'=>$v->getVar('vtype', 'n'), 'publish' => $v->getVar('publish', 'n'));
			}
			//print_r($jieqiModary);
			if(!isset($license_ary)) $license_ary=jieqi_strtosary($jieqi_license_ary[2], '=', '|');
			$licenses=array();
			$i=0;
			foreach($jieqiModary as $k=>$v){
				$licenses[$i]['modname']=jieqi_htmlstr($jieqiModary[$k]['caption']);
				$licenses[$i]['modversion']=jieqi_htmlstr($jieqiModary[$k]['version']);
				if(isset($license_ary[$k])) $vtype=$license_ary[$k];
				else $vtype='Free';
				if(defined('LANG_VERSION_'.strtoupper($vtype))) $licenses[$i]['modvtype']=constant('LANG_VERSION_'.strtoupper($vtype));
				else $licenses[$i]['modvtype']='';
				$i++;
			}
			$ret['licenses'] = $licenses;
			$ret['uid'] = $_SESSION['jieqiUserId'];
			return $ret;
		}
} 
?>