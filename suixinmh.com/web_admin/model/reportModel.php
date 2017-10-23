<?php 
/** 
 * 系统管理->用户日志 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 

class reportModel extends Model{
	public function main($params = array()){
		global $jieqiRsort, $jieqiConfigs;
		$this->addLang('system', 'report');
		$jieqiLang['system'] = $this->getLang('system');
		jieqi_getconfigs('system', 'configs');
		jieqi_getconfigs('system', 'rsort', 'jieqiRsort');
		//页码
		if (empty($params['page']) || !is_numeric($params['page'])) $params['page']=1;
		
		//处理批量删除
		if(isset($params['checkaction']) && $params['checkaction'] == 1 && is_array($params['checkid']) && count($params['checkid'])>0){
			$where='';
			foreach($params['checkid'] as $v){
				if(is_numeric($v)){
					$v=intval($v);
					if(!empty($where)) $where.=', ';
					$where.=$v;
				}
			}
			if(!empty($where)){
				$sql='DELETE FROM '.jieqi_dbprefix('system_report').' WHERE reportid IN ('.$where.')';
				$this->db->query($sql);
			}
			$params['checkaction']=0;
		}
		
		if(isset($_GET['checkaction'])) unset($_GET['checkaction']);
		if(isset($_POST['checkaction'])) unset($_POST['checkaction']);
		
		$checkall = '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">';
		$reportrows=array();
		$this->db->init('report','reportid','system');
		$this->db->setCriteria();
		
		//$criteria=new CriteriaCompo();
		if(!empty($params['keyword']) && !empty($params['keytype'])){
			switch($params['keytype']){
				case 'reportname':
					$this->db->criteria->add(new Criteria('reportname', $params['keyword']));
					break;
				case 'authname':
					$this->db->criteria->add(new Criteria('authname', $params['keyword']));
					break;
				case 'reporttitle':
					$this->db->criteria->add(new Criteria('reporttitle', '%'.$params['keyword'].'%', 'LIKE'));
					break;
			}
			$_GET['keyword']=$params['keyword'];
			$_GET['keytype']=$params['keytype'];
		}
		$this->db->criteria->setSort('reportid');
		$this->db->criteria->setOrder('DESC');
		$this->db->criteria->setLimit($jieqiConfigs['system']['messagepnum']);
		$this->db->criteria->setStart(($params['page']-1) * $jieqiConfigs['system']['messagepnum']);
		$this->db->queryObjects();
		$k=0;
		while($v = $this->db->getObject()){
			$reportrows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('reportid').'">';
		
			$reportrows[$k]['reportid']=$v->getVar('reportid');
			$reportrows[$k]['reporttime']=$v->getVar('reporttime');
			$reportrows[$k]['reportuid']=$v->getVar('reportuid');
			$reportrows[$k]['reportname']=$v->getVar('reportname');
			$reportrows[$k]['authtime']=$v->getVar('authtime');
			$reportrows[$k]['authuid']=$v->getVar('authuid');
			$reportrows[$k]['authname']=$v->getVar('authname');
			$reportrows[$k]['reporttitle']=$v->getVar('reporttitle');
			$reportrows[$k]['reporttext']=$v->getVar('reporttext');
			$reportrows[$k]['reportsize']=$v->getVar('reportsize');
			$reportrows[$k]['reportfield']=$v->getVar('reportfield');
			$reportrows[$k]['authnote']=$v->getVar('authnote');
			$reportrows[$k]['reportsort']=intval($v->getVar('reportsort'));
			$reportrows[$k]['reporttype']=intval($v->getVar('reporttype'));
			$reportrows[$k]['authflag']=$v->getVar('authflag');
			$reportrows[$k]['sortname']=$jieqiRsort[$reportrows[$k]['reportsort']]['caption'];
			if(isset($jieqiRsort[$reportrows[$k]['reportsort']]['types'][$reportrows[$k]['reporttype']])) $reportrows[$k]['typename']=$jieqiRsort[$reportrows[$k]['reportsort']]['types'][$reportrows[$k]['reporttype']];
			else $reportrows[$k]['typename']=$jieqiLang['system']['report_type_other'];
			
			$k++;
		}
		$reportrows = $reportrows;
		$rsortrows = $jieqiRsort;
		//处理页面跳转
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($this->db->getCount($criteria),$jieqiConfigs['system']['messagepnum'],$params['page']);
		return array('checkall'=>$logrows,
			'reportrows'=>$reportrows,
			'rsortrows'=>$rsortrows,
			'url_jumppage'=>$jumppage->whole_bar(),
		);		
	}
	
	
	/** 
	 * 系统管理->用户报告->详细信息 * @copyright   Copyright(c) 2014 
	 * para      void     
	 * return array
	 */ 	
	public function detail($params = array()){
		global $jieqiPower, $jieqiRsort, $jieqiLang, $jieqiConfigs;
		//检查权限
		$this->db->init('power','pid','system');
		$this->db->setCriteria(new Criteria('modname', 'system', "="));
		$this->db->criteria->setSort('pid');
		$this->db->criteria->setOrder('ASC');
		$this->db->queryObjects();
		while($v = $this->db->getObject()){
			$jieqiPower[system][$v->getVar('pname','n')]=array('caption'=>$v->getVar('ptitle'), 'groups'=>unserialize($v->getVar('pgroups','n')), 'description'=>$v->getVar('pdescription'));	        
		}
		$this->addLang('system', 'report');
		if(empty($params['id'])) jieqi_printfail($jieqiLang['system']['report_no_exists']);
		$params['id'] = intval($params['id']);
		
		$this->db->init('report','reportid','system');
		$report = $this->db->get($params['id']);
		if(!$report) jieqi_printfail($jieqiLang['system']['report_no_exists']);
		jieqi_getconfigs('system', 'rsort', 'jieqiRsort');
		
		$data = array();
		$data['reportid'] = $params['id'];
		$data['siteid'] =$report['siteid'];
		$data['reporttime'] =$report['reporttime'];
		$data['reportuid'] =$report['reportuid'];
		$data['reportname'] =$report['reportname'];
		$data['authtime'] =$report['authtime'];
		$data['authuid'] =$report['authuid'];
		$data['authname'] =$report['authname'];
		$data['reporttitle'] =$report['reporttitle'];
		include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
		$ts=TextConvert::getInstance('TextConvert');
		$data['reporttext'] = $ts->makeClickable($report['reporttext']);
		$data['reportsize'] =$report['reportsize'];
		$data['reportfield'] =$report['reportfield'];
		$data['authnote'] =$report['authnote'];
		$data['reportsort'] =$report['reportsort'];
		$data['reporttype'] =$report['reporttype'];
		$data['authflag'] =$report['authflag'];
		
		$data['sortname'] =$jieqiRsort[$report['reportsort']]['caption'];
		if(isset($jieqiRsort[$report['reportsort']]['types'][$report['reporttype']])) $data['typename'] = $jieqiRsort[$report['reportsort']]['types'][$report['reporttype']];
		else $reportrows[$k]['typename'] = $data['typename'] = $jieqiLang['system']['report_type_other'];
				
		//设置已读标志
		if($report['authflag'] == 0){
			$report['authflag'] = '1';
			$this->db->edit($params['id'], $report);
		}
		
		return $data;
		
		
	}
} 
?>