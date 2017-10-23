<?php 
/** 
 * 后台系统管理->系统定义模型 * @copyright   Copyright(c) 2014 
 * @author      huliming* @version     1.0 
 */ 
class configsModel extends Model{
		
        public function main($params = array()){
			global $jieqiModules;
//			$_REQUEST = $this->getRequest();
			if(empty($params['mod'])) $params['mod']='system';
			elseif(!isset($jieqiModules[$params['mod']])) jieqi_printfail(LANG_ERROR_PARAMETER);
			//载入语言
			$this->addLang('system', 'configs');
			$jieqiLang['system'] = $this->getLang('system');
			//取得设置
			if(!isset($params['define']) || $params['define'] != 1){
				$params['define']=0;
			}
            $this->db->init('configs','cid','system');
			$this->db->setCriteria(new Criteria('modname', $params['mod'], "="));
			$this->db->criteria->add(new Criteria('cdefine', $params['define'], '='));
			$this->db->criteria->setSort('catorder ASC, cid');
	        $this->db->criteria->setOrder('ASC');
			$this->db->queryObjects();
			$jieqiModary=array();
			$v = $this->db->getObject();
			if($v){
				if(isset($params['action']) && $params['action']=='update'){
					//更新参数
					$cfgarray=array(); //数组变量
					$cfgdefine='';  //定义变量
		
					do{
						$tmpkey=$v->getVar('cname','n');
						switch($v->getVar('ctype')){
							case JIEQI_TYPE_TXTBOX:
							case JIEQI_TYPE_TXTAREA:
							case JIEQI_TYPE_HIDDEN:
								if(!isset($params[$tmpkey])) $tmpval=$v->getVar('cvalue');
								else $tmpval=$params[$tmpkey];
								break;
							case JIEQI_TYPE_INT:
								if(!isset($params[$tmpkey]) || !is_numeric($params[$tmpkey])) $tmpval=$v->getVar('cvalue');
								else $tmpval=$params[$tmpkey];
								$tmpval = intval($tmpval);
								break;
							case JIEQI_TYPE_NUM:
								if(!isset($params[$tmpkey]) || !is_numeric($params[$tmpkey])) $tmpval=$v->getVar('cvalue');
								else $tmpval=$params[$tmpkey];
								break;
							case JIEQI_TYPE_PASSWORD:
								if(!isset($params[$tmpkey]) || strlen($params[$tmpkey])==0) $tmpval=$v->getVar('cvalue');
								else $tmpval=$params[$tmpkey];
								break;
							case JIEQI_TYPE_SELECT:
							case JIEQI_TYPE_RADIO:
								$selectary=@unserialize($v->getVar('options', 'n'));
								if(!is_array($selectary)) $selectary=array();
								if(!isset($params[$tmpkey]) || !isset($selectary[$params[$tmpkey]])) $tmpval=$v->getVar('cvalue');
								else $tmpval=$params[$tmpkey];
								break;
							case JIEQI_TYPE_MULSELECT:
							case JIEQI_TYPE_CHECKBOX:
								$selectary=@unserialize($v->getVar('options', 'n'));
								if(!is_array($selectary)) $selectary=array();
								$tmparray = is_array($params[$tmpkey]) ? $params[$tmpkey] : array();
								$tmpval = 0;
								foreach($tmparray as $tmpv){
									if(isset($selectary[$tmpv])) $tmpval = $tmpval | intval($tmpv);
								}
								break;
							default:
								if(!isset($params[$tmpkey])) $tmpval=$v->getVar('cvalue');
								else $tmpval=$params[$tmpkey];
								break;
								break;
						}
						//参数改变了，需要改变数据库
						if($tmpval != $v->getVar('cvalue','n')){
							$this->db->edit($v->getVar('cid','n'), array('cvalue'=>$tmpval));
						}

						if($v->getVar('cdefine')=='1'){
							$cfgdefine.="@define('".$tmpkey."','".jieqi_setslashes($tmpval, '"')."');\n";
						}else{
							$cfgarray[$params['mod']][$tmpkey]=$tmpval;
						}
					}while($v = $this->db->getObject());
					if(count($cfgarray)>0) jieqi_setconfigs('configs', 'jieqiConfigs', $cfgarray, $params['mod']);
					if(!empty($cfgdefine)){
						$dir=JIEQI_ROOT_PATH.'/configs';
						if(!file_exists($dir)) @mkdir($dir, 0777);
						@chmod($dir, 0777);
						if($params['mod'] != 'system'){
							$dir.='/'.$params['mod'];
							if(!file_exists($dir)) @mkdir($dir, 0777);
							@chmod($dir, 0777);
						}
						$dir.='/system.php';
						if(file_exists($dir)) @chmod($dir, 0777);
						$cfgdefine="<?php\n".$cfgdefine."\n?>";
						jieqi_writefile($dir, $cfgdefine);
						$publicdata=str_replace('?><?php', '', $cfgdefine.jieqi_readfile(JIEQI_ROOT_PATH.'/lang/lang_system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/configs/groups.php'));
						jieqi_writefile(JIEQI_ROOT_PATH.'/configs/define.php', $publicdata);
					}
					jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['edit_config_success']);
				}else{
					//显示参数
					include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
					$config_form = new JieqiThemeForm($jieqiLang['system']['edit_config'], 'config', $this->getAdminurl('configs'));
					$catname='';
					$catorder=0;
					$catlink='';
					do{
						$tmpvar=$v->getVar('catname');
						if($catname != $tmpvar){
							$catname=$tmpvar;
							$catorder++;
							${'catele'.$catorder}=new JieqiFormLabel('', '<a name="catorder'.$catorder.'">'.$catname.'</a>');
							$config_form->addElement(${'catele'.$catorder}, false);
							if(!empty($catlink)) $catlink.='&nbsp;&nbsp;';
							$catlink.='[<a href="#catorder'.$catorder.'">'.$catname.'</a>]';
						}
						switch($v->getVar('ctype')){
							case JIEQI_TYPE_INT:
							case JIEQI_TYPE_NUM:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormText($v->getVar('ctitle'), $v->getVar('cname'), 25, 100, $v->getVar('cvalue','e'));
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_TXTAREA:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormTextArea($v->getVar('ctitle'), $v->getVar('cname'), $v->getVar('cvalue','e'), 5, 50);
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_SELECT:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormSelect($v->getVar('ctitle'), $v->getVar('cname'), $v->getVar('cvalue','e'));
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$selectary=@unserialize($v->getVar('options', 'n'));
							if(!is_array($selectary)) $selectary=array();
							foreach($selectary as $val=>$cap){
								${$tmpvar}->addOption($val,$cap);
							}
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_RADIO:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormRadio($v->getVar('ctitle'), $v->getVar('cname'), $v->getVar('cvalue','e'));
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$selectary=@unserialize($v->getVar('options', 'n'));
							if(!is_array($selectary)) $selectary=array();
							foreach($selectary as $val=>$cap){
								${$tmpvar}->addOption($val,$cap);
							}
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_CHECKBOX:
							$tmpvar=$v->getVar('cname');
							$tmpvalue = decbin(intval($v->getVar('cvalue','n')));
							$tmplen = strlen($tmpvalue);
							$tmparray = array();
							$tmpnum = 1;
							for($p=$tmplen-1; $p>=0; $p--){
								if($tmpvalue[$p] == '1') $tmparray[] = $tmpnum;
								$tmpnum *= 2;
							}
							${$tmpvar}=new JieqiFormCheckBox($v->getVar('ctitle'), $v->getVar('cname'), $tmparray);
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$selectary=@unserialize($v->getVar('options', 'n'));
							if(!is_array($selectary)) $selectary=array();
							foreach($selectary as $val=>$cap){
								${$tmpvar}->addOption($val,$cap);
							}
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_LABEL:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormLabel($v->getVar('ctitle'),  $v->getVar('cvalue'));
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_PASSWORD:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormPassword($v->getVar('ctitle'), $v->getVar('cname'), 25, 30, '');
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$config_form->addElement(${$tmpvar}, false);
							break;
							case JIEQI_TYPE_TXTBOX:
							default:
							$tmpvar=$v->getVar('cname');
							${$tmpvar}=new JieqiFormText($v->getVar('ctitle'), $v->getVar('cname'), 25, 100, $v->getVar('cvalue','e'));
							${$tmpvar}->setDescription($v->getVar('cdescription'));
							$config_form->addElement(${$tmpvar}, false);
							break;
						}
					}while($v = $this->db->getObject());
					$config_form->addElement(new JieqiFormHidden('mod', $params['mod']));
					$config_form->addElement(new JieqiFormHidden('define', $params['define']));
					$config_form->addElement(new JieqiFormHidden('action', 'update'));
					$config_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_config'], 'submit'));
					$jieqi_contents = '<div style="text-align:center;"><span style="line-height:200%">'.$catlink.'</span></div>'.$config_form->render(JIEQI_FORM_MIDDLE).'<br />';
					return $jieqi_contents;
				}
			}else{
				jieqi_msgwin(LANG_NOTICE, $jieqiLang['system']['no_usage_config']);
			}
        }
} 
?>