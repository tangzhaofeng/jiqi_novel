<?php 
/** 
 * 后台系统管理->系统定义模型 * @copyright   Copyright(c) 2014 
 * @author      chengyuan* @version     1.0 
 */
class urlModel extends Model{
		
		//列表
        public function main($mod){
			$map=array();
			global $jieqiModules;
			//jieqi_getconfigs(JIEQI_MODULE_NAME, 'modules');
			$map['modules'] = $jieqiModules;
			$_REQUEST = $this->getRequest();
            $this->db->init('url','id','system');
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('modname', $mod, '='));
			$this->db->criteria->setSort('controller');
	        $this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$v = $this->db->getObject();
			$i = 0;
			if($v){
				//保存的url
				$map['url_action'] = $this->getAdminurl('url','method=modify&mod='.$mod);
				do{
					$obj = array();
					$obj ['id'] = $v->getVar('id');
					$obj ['caption'] = $v->getVar('caption');
					$obj ['modname'] = $v->getVar('modname');
					$obj ['controller'] = $v->getVar('controller');
					$obj ['method'] = $v->getVar('method');
					$obj ['rule'] = $v->getVar('rule');
					$obj ['params'] = $v->getVar('params');
					$obj ['description'] = $v->getVar('description');
					$obj ['ishtml'] = $v->getVar('ishtml');//是否生成HTML
					$obj ['system'] = $v->getVar('system');//系统内置控制器不允许修改
					$map['urls'][$i] = $obj;
					$i++;
				}while($v = $this->db->getObject());
				return $map;
			}
        }
		//add form html
		public function addForm($mod){
//			global $jieqiLang;
			global $jieqiModules;
			if(empty($mod))$mod='system';
			$this->addLang('system', 'url');
			$jieqiLang['system'] = $this->getLang('system');
			include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
			$add_form = new JieqiThemeForm($jieqiLang['system']['add_url'], 'urlnew', $this->getAdminurl('url','method=add'));
			
			/*
			$modname=new JieqiFormSelect($jieqiLang['system']['modname'], 'modname', 'modname');
				foreach($jieqiModules as $val=>$cap){
					$modname->addOption($val,$cap['caption']);
				}*/
			$add_form->addElement(new JieqiFormLabel('模块',$jieqiModules[$mod]['caption']), false);
			
			
			$add_form->addElement(new JieqiFormText($jieqiLang['system']['caption'], 'caption', 30, 50, ''), true);
			$add_form->addElement(new JieqiFormText($jieqiLang['system']['rule'], 'rule', 30, 50, ''), false);

			$add_form->addElement(new JieqiFormText($jieqiLang['system']['controller'], 't_controller', 30, 50, ''), true);
			$add_form->addElement(new JieqiFormText($jieqiLang['system']['method'], 't_method', 30, 50, ''), false);
			
			$add_form->addElement(new JieqiFormText($jieqiLang['system']['params'], 'params', 30, 50, ''), false);
			$add_form->addElement(new JieqiFormTextArea($jieqiLang['system']['description'], 'description', '', 5, 50));
			$add_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_url'], 'submit'));
			
			$add_form->addElement(new JieqiFormHidden('mod', $mod));
			$addForm = "<br />".$add_form->render(JIEQI_FORM_MIDDLE)."<br />";	
			return $addForm;
		}
		//新增
		public function add($params){
			//载入语言
			$this->addLang('system', 'url');
			$jieqiLang['system'] = $this->getLang('system');
			if(empty($params['mod'])) jieqi_printfail($jieqiLang['system']['need_modname']);
			elseif(empty($params['caption'])) jieqi_printfail($jieqiLang['system']['need_caption']);
			elseif(empty($params['t_controller'])) jieqi_printfail($jieqiLang['system']['need_controller']);
			//elseif(empty($_REQUEST['rule'])) jieqi_printfail($this->getLang('system', 'need_rule'));
			elseif(empty($params['t_method'])) jieqi_printfail($jieqiLang['system']['need_method']);
			//elseif(empty($_REQUEST['params'])) jieqi_printfail($this->getLang('system', 'need_params'));
			else{
				$this->db->init('url','id','system');
				$data = array('modname'=>$params['mod'],
					'caption'=>$params['caption'],
					'rule'=>$params['rule'],
					'controller'=>$params['t_controller'],
					'method'=>$params['t_method'],
					'params'=>$params['params'],
					'description'=>$params['description']);
				if(!$this->db->add($data)) jieqi_printfail($jieqiLang['system']['add_url_failure']);
				else{
					$this->synchronization_url_config($params['mod']);
					jieqi_jumppage($this->getAdminurl('url','mod='.$params['mod']),LANG_DO_SUCCESS,$jieqiLang['system']['add_url_success']);
				} 
			}
		}
		//批量修改
		public function modify($params){
//			global $jieqiLang;
			$this->addLang('system', 'url');
			$jieqiLang['system'] = $this->getLang('system');
            $this->db->init('url','id','system');
			$this->db->setCriteria();
			$this->db->criteria->add(new Criteria('modname',$params['mod']));
			$this->db->criteria->setSort('id');
	        $this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$jieqiModary=array();
			$v = $this->db->getObject();
			if($v){
				do{
					$modifyarray=array();//需要更新的数
					$id=$v->getVar('id','n');
					
					foreach($params['urls'][$id] as $k=>$p){
						//变更的量
						//if(!isset($_REQUEST['urls'][$id][$k])) continue;
						if($p != $v->getVar($k,'n')){
							$modifyarray[$k] = $p;
						}
					}
					if(!isset($params['urls'][$id]['ishtml'])) $modifyarray['ishtml'] = 0;
					//if($id=='43'){ print_r($_REQUEST['urls'][$id]);exit($_REQUEST['urls'][$id]['ishtml'].'='.$v->getVar('ishtml','n'));}
					if(!empty($modifyarray)){
						$this->db->edit($id,$modifyarray);
					}
				}while($v = $this->db->getObject());
				$this->synchronization_url_config($params['mod']);
				jieqi_jumppage($this->getAdminurl('url','mod='.$params['mod']),LANG_DO_SUCCESS,$jieqiLang['system']['url_config_saved']);
			}else{
				jieqi_msgwin(LANG_NOTICE,$jieqiLang['system']['no_usage_config']);
			}
		}
	/** 
	 * 同步url数据库和配置文件 * @copyright   Copyright(c) 2014 
	 * @author      chengyuan* @version     1.0 
	 */
	public function	synchronization_url_config($mod){
		$this->db->init('url','id','system');
		$this->db->setCriteria();
		$this->db->criteria->add(new Criteria('modname', $mod, '='));
		$this->db->criteria->setSort('id');
		$this->db->criteria->setOrder('ASC');
		$this->cache_write($mod,'url',$this->db->lists(),'controller,method');
	}
} 
?>