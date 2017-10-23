<?php 
/**
 * 采集配置
 * @author zhangxue  2014-9-12
 *
 */
class collectsetModel extends Model{
	public function main($params = array()){
		global $jieqiModules;
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		
		$updateconfig=false;
		if(isset($params['action']) && $params['action']=='del' && !empty($params['config'])){
			foreach($jieqiCollectsite as $k=>$v){
				if($v['config']==$params['config']){
					unset($jieqiCollectsite[$k]);
					$updateconfig=true;
					break;
				}
			}
		}
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$data = array();
		$data ['siterows'] = $jieqiCollectsite;
		$data ['article_static_url'] = $article_static_url;
		$data ['article_dynamic_url'] = $article_dynamic_url;
		
		if($updateconfig){
			jieqi_setconfigs('collectsite', 'jieqiCollectsite', $jieqiCollectsite, 'article');
			if(file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) jieqi_delfile(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php');
		}
		
		return $data;
	}
	//单篇采集规则 编辑
	public function collectedit($params = array()){
		global $jieqiModules;
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$this->addLang('article','manage');
		$jieqiLang['article'] = $this->getLang('article');//print_r($jieqiLang);
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		if(empty($params['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) $this->printfail($jieqiLang['article']['rule_not_exists']);
		include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php');
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
		
		$editCollect=array();
		$editCollect['sitename']=trim($params['sitename']);  //站名
		$editCollect['siteurl']=trim($params['siteurl']); //网址
		if(is_numeric(str_replace(array('<{articleid}>', '<{chapterid}>', 'ceil', 'floor', 'round', 'substr', 'intval', 'is_numeric', '+', '-', '*', '/', '%', ',', '?', '=', '>', '<', ':', '(', ')', ' '), '', $params['subarticleid']))) $editCollect['subarticleid']=str_replace(array('<{articleid}>', '<{chapterid}>'), array('$articleid', '$chapterid'), trim($params['subarticleid'])); //文章子序号运算方式
		else $editCollect['subarticleid']='';
		if(is_numeric(str_replace(array('<{articleid}>', '<{chapterid}>', 'ceil', 'floor', 'round', 'substr', 'intval', 'is_numeric', '+', '-', '*', '/', '%', ',', '?', '=', '>', '<', ':', '(', ')', ' '), '', $params['subchapterid']))) $editCollect['subchapterid']=str_replace(array('<{articleid}>', '<{chapterid}>'), array('$articleid', '$chapterid'), trim($params['subchapterid'])); //子序号运算方式
		else $editCollect['subchapterid']='';
		//代理服务器地址
		$editCollect['proxy_host']=trim($params['proxy_host']);
		//代理服务器端口
		$editCollect['proxy_port']=trim($params['proxy_port']);
		//代理服务器帐号
		//$editCollect['proxy_user']=trim($params['proxy_user']);
		//代理服务器密码
		//$editCollect['proxy_pass']=trim($params['proxy_pass']);
		
		//章节无法对应是是否自动清空重新采集
		$editCollect['autoclear']=trim($params['autoclear']);
		//是否默认全本
		$editCollect['defaultfull']=trim($params['defaultfull']);
		//启用referer
		$editCollect['referer']=trim($params['referer']);
		//启用autochaptercollect
		$editCollect['autochaptercollect']=trim($params['autochaptercollect']);
		//启用chapteridorder
		$editCollect['chapteridorder']=trim($params['chapteridorder']);
		//启用cleantargetsiterepeatchapter
		$editCollect['cleantargetsiterepeatchapter']=trim($params['cleantargetsiterepeatchapter']);
		//启用cleansiterepeatchapter
		$editCollect['cleansiterepeatchapter']=trim($params['cleansiterepeatchapter']);
		//启用cleansiterepeatarticle
		$editCollect['cleansiterepeatarticle']=trim($params['cleansiterepeatarticle']);

		//启用wget
		$editCollect['wget']=trim($params['wget']);
		//采集时生成
		$editCollect['makeset']=@implode(',',$params['makeset']);
		//网页编码
		$editCollect['pagecharset']=trim($params['pagecharset']);
		//首发状态
		$editCollect['firstflag']=trim($params['firstflag']);
		//文章信息页面
		$editCollect['urlarticle']=trim($params['urlarticle']);
		//文章标题
		$editCollect['articletitle']=jieqi_collectptos($params['articletitle']);
		//作者
		$editCollect['author']=jieqi_collectptos($params['author']);
		//类型
		$editCollect['sort']=jieqi_collectptos($params['sort']);
		//子类型
		$editCollect['type']=jieqi_collectptos($params['type']);
		//关键字
		$editCollect['keyword']=jieqi_collectptos($params['keyword']);
		//简介
		$editCollect['intro']=jieqi_collectptos($params['intro']);
		//封面
		$editCollect['articleimage']=jieqi_collectptos($params['articleimage']);
		//过滤封面
		$editCollect['filterimage']=trim($params['filterimage']);
		//目录页链接
		$editCollect['indexlink']=jieqi_collectptos($params['indexlink']);
		//全文标记
		$editCollect['fullarticle']=jieqi_collectptos($params['fullarticle']);
		//VIP标记
		$editCollect['vipstart']=jieqi_collectptos($params['vipstart']);
		//是否VIP
		$editCollect['isvip']=jieqi_collectptos($params['isvip']);

		//文章状态
		$editCollect['display']=jieqi_collectptos($params['display']);
		//文章类型对应id
		$sortary=explode('||', trim($params['sortid']));
		$editCollect['sortid']=array();
		foreach($sortary as $v){
			$tmpary=explode('=>', trim($v));
			if(count($tmpary)==2){
				$sname=trim($tmpary[0]);
				$sid=trim($tmpary[1]);
				if(is_numeric($sid)) $editCollect['sortid'][$sname]=$sid;
			}
		}

		//文章子类型对应id
		$typeary=explode('||', trim($params['typeid']));
		$editCollect['typeid']=array();
		foreach($typeary as $v){
			$btmpary=explode('=>', trim($v));
			if(count($btmpary)==2){
				$sname=trim($btmpary[0]);
				$smallary=explode(',', trim($btmpary[1]));
				if(count($smallary)>0){
					foreach($smallary as $k1=>$v1){
						$strary=explode('=', trim($v1));
						$sid=trim($strary[1]);
						if(is_numeric($k1)) $editCollect['typeid'][$sname][$strary[0]]=$sid;
					}
				}
			}
		}
		
		$editCollect['urlindex']=trim($params['urlindex']); //文章目录页面
		//分卷名称
		$editCollect['volume']=jieqi_collectptos($params['volume']);
		//章节名称
		$editCollect['chapter']=jieqi_collectptos($params['chapter']);
		//章节序号
		$editCollect['chapterid']=jieqi_collectptos($params['chapterid']);
		//章节名称过滤
		$editCollect['chapterfilter']=trim($params['chapterfilter']);
		//章节名称替换
		$editCollect['chapterreplace']=trim($params['chapterreplace']);
		//过滤章节
		$editCollect['cleanchapter']=jieqi_collectptos($params['cleanchapter']);
		$editCollect['urlchapter']=trim($params['urlchapter']); //章节内容页面
		//章节内容
		$editCollect['content']=jieqi_collectptos($params['content']);
		//章节发布时间
		$editCollect['postdate']=jieqi_collectptos($params['postdate']);
		//章节内容过滤
		$editCollect['contentfilter']=trim($params['contentfilter']);
		//章节内容替换
		$editCollect['contentreplace']=trim($params['contentreplace']);
		//是否采集图片
		$editCollect['collectimage']=trim($params['collectimage']);
		//是否允许图片处理
		$editCollect['imagetranslate']=trim($params['imagetranslate']);
		//是否加水印
		$editCollect['addimagewater']=trim($params['addimagewater']);
		//图片背景色
		$editCollect['imagebgcolor']=trim($params['imagebgcolor']);
		//按区域清理
		$editCollect['imageareaclean']=trim($params['imageareaclean']);
		//按颜色清理
		$editCollect['imagecolorclean']=trim($params['imagecolorclean']);

		$editCollect['listcollect']=$jieqiCollect['listcollect'];
		$configstr="<?php\n".jieqi_extractvars('jieqiCollect', $editCollect)."\n?>";
		jieqi_writefile(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php', $configstr);
		$siteid=-1;
		reset($jieqiCollectsite);
		while(list($k, $v) = each($jieqiCollectsite)) {
			if($v['config']==$params['config']){
				$siteid=$k;
				break;
			}
		}
		if($siteid>=0) $jieqiCollectsite[$siteid]=array('name'=>$editCollect['sitename'], 'config'=>$params['config'], 'url'=>$editCollect['siteurl'], 'subarticleid'=>$editCollect['subarticleid'], 'enable'=>'1');
		else $jieqiCollectsite[]=array('name'=>$editCollect['sitename'], 'config'=>$params['config'], 'url'=>$editCollect['siteurl'], 'subarticleid'=>$editCollect['subarticleid'], 'enable'=>'1');
		jieqi_setconfigs('collectsite', 'jieqiCollectsite', $jieqiCollectsite, 'article');
		$this->jumppage($article_static_url.'/web_admin/?controller=collectset', LANG_DO_SUCCESS, $jieqiLang['article']['rule_edit_success']);
	}
	//单篇采集规则 编辑视图
	public function collecteditview($params = array()){
		global $jieqiModules;
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
//		jieqi_loadlang('collect', 'article');
		$this->addLang('article','collect');
		$this->addLang('article','manage');
		$jieqiLang['article'] = $this->getLang('article');
//		include_once($jieqiModules['article']['path'].'/lang/lang_collect.php');
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		if(empty($params['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) $this->printfail($jieqiLang['article']['rule_not_exists']);
		include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php');
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');

		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['rule_edit'], 'collectedit', $article_static_url.'/web_admin/?controller=collectset&method=collectedit');
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_basic']));
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['rule_site_id'], $params['config']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_name'], 'sitename', 60, 50, htmlspecialchars($jieqiCollect['sitename'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_url'], 'siteurl', 60, 100, htmlspecialchars($jieqiCollect['siteurl'], ENT_QUOTES)), true);
		$tmpstr=str_replace(array('$articleid', '$chapterid', '$'),array('<{articleid}>', '<{chapterid}>', ''),$jieqiCollect['subarticleid']);
		$subarticleid=new JieqiFormText($jieqiLang['article']['rule_subarticleid'], 'subarticleid', 60, 100, htmlspecialchars($tmpstr, ENT_QUOTES));
		$subarticleid->setDescription($jieqiLang['article']['rule_operation_note']);
		$collect_form->addElement($subarticleid);
		
		$tmpstr=str_replace(array('$articleid', '$chapterid', '$'),array('<{articleid}>', '<{chapterid}>', ''),$jieqiCollect['subchapterid']);
		$subchapterid=new JieqiFormText($jieqiLang['article']['rule_subchapterid'], 'subchapterid', 60, 100, htmlspecialchars($tmpstr, ENT_QUOTES));
		$subchapterid->setDescription($jieqiLang['article']['rule_operation_note']);
		$collect_form->addElement($subchapterid);
		
		$proxy_host=new JieqiFormText($jieqiLang['article']['rule_proxy_host'], 'proxy_host', 20, 100, $jieqiCollect['proxy_host']);
		$proxy_host->setDescription($jieqiLang['article']['rule_proxyhost_note']);
		$collect_form->addElement($proxy_host);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_proxy_port'], 'proxy_port', 20, 20, $jieqiCollect['proxy_port']));
		//$proxy_user=new JieqiFormText($jieqiLang['article']['rule_proxy_user'], 'proxy_user', 20, 100, $jieqiCollect['proxy_user']);
		//$proxy_user->setDescription($jieqiLang['article']['rule_proxyuser_note']);
		//$collect_form->addElement($proxy_user);
		//$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_proxy_pass'], 'proxy_pass', 20, 100, $jieqiCollect['proxy_pass']));
		
		$autoclear=new JieqiFormRadio($jieqiLang['article']['rule_auto_clean'], 'autoclear', $jieqiCollect['autoclear']);
		$autoclear->addOption('1', LANG_YES);
		$autoclear->addOption('0', LANG_NO);
		$collect_form->addElement($autoclear);
		$defaultfull=new JieqiFormRadio($jieqiLang['article']['rule_default_full'], 'defaultfull', $jieqiCollect['defaultfull']);
		$defaultfull->addOption('1', LANG_YES);
		$defaultfull->addOption('0', LANG_NO);
		$collect_form->addElement($defaultfull);
		
		$referer=new JieqiFormRadio($jieqiLang['article']['rule_send_referer'], 'referer', $jieqiCollect['referer']);
		$referer->addOption('1', LANG_YES);
		$referer->addOption('0', LANG_NO);
		$collect_form->addElement($referer);
		
		//定制版、豪华版允许
		if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM', 'DELUXE'))){
//			if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
			//20091214增加wget突破防采集
			$wget=new JieqiFormRadio($jieqiLang['article']['rule_send_wget'], 'wget', $jieqiCollect['wget']);
			$wget->setDescription($jieqiLang['article']['rule_sendwget_note']);
			$wget->addOption('1', LANG_YES);
			$wget->addOption('0', LANG_NO);
			$collect_form->addElement($wget);
			
			//定制采集时生成项
			$makeset=new JieqiFormCheckBox($jieqiLang['article']['rule_make_set'], 'makeset',@explode(",", $jieqiCollect['makeset']));
			$makeset->setDescription($jieqiLang['article']['rule_makeset_note']);
			$makeset->addOption('makehtml', $jieqiLang['article']['repack_html']);
			$makeset->addOption('makezip', $jieqiLang['article']['repack_zip']);
			$makeset->addOption('maketxtfull', $jieqiLang['article']['repack_txtfullpage']);
			$makeset->addOption('makeumd', $jieqiLang['article']['repack_umdpage']);
			$makeset->addOption('makejar', $jieqiLang['article']['repack_jarpage']);
			$collect_form->addElement($makeset);	
		}

		$autochaptercollect=new JieqiFormRadio('章节高级智能采集', 'autochaptercollect', $jieqiCollect['autochaptercollect']);
		$autochaptercollect->addOption('1', LANG_YES);
		$autochaptercollect->addOption('0', LANG_NO);
		$autochaptercollect->setDescription('建议批量采集文章的时候不要开启此功能，以防章节采集发生混乱');
		$collect_form->addElement($autochaptercollect);
		
		$chapteridorder=new JieqiFormRadio('章节ID号排序', 'chapteridorder', $jieqiCollect['chapteridorder']);
		$chapteridorder->addOption('1', LANG_YES);
		$chapteridorder->addOption('0', LANG_NO);
		$chapteridorder->setDescription('目标网站章节按ID号从小往大排序，用于解决目标网站的防采集设置！');
		$collect_form->addElement($chapteridorder);
		
		$cleantargetsiterepeatchapter=new JieqiFormRadio('过滤目标网站重复章节', 'cleantargetsiterepeatchapter', $jieqiCollect['cleantargetsiterepeatchapter']);
		$cleantargetsiterepeatchapter->addOption('1', LANG_YES);
		$cleantargetsiterepeatchapter->addOption('0', LANG_NO);
		$cleantargetsiterepeatchapter->setDescription('开采此功能能有效防止采集重复章节，但也有可能造成章节混乱！');
		$collect_form->addElement($cleantargetsiterepeatchapter);

		if(empty($jieqiCollect['cleansiterepeatchapter'])) $jieqiCollect['cleansiterepeatchapter']='0';
		$cleansiterepeatchapter=new JieqiFormSelect('过滤本站重复章节', 'cleansiterepeatchapter', $jieqiCollect['cleansiterepeatchapter']);
		$cleansiterepeatchapter->addOption('0', '不开启过滤');
		$cleansiterepeatchapter->addOption('1', '开启过滤重复章节');
		$cleansiterepeatchapter->addOption('2', '开启过滤并删除重复章节');
		$cleansiterepeatchapter->setDescription('在文章有重复章节的情况下，开启此选项能有效避免再次采集重复章节！');
		$collect_form->addElement($cleansiterepeatchapter);
		
		if(empty($jieqiCollect['cleansiterepeatarticle'])) $jieqiCollect['cleansiterepeatarticle']='0';
		$cleansiterepeatarticle=new JieqiFormSelect('采集时判断重复书名', 'cleansiterepeatarticle', $jieqiCollect['cleansiterepeatarticle']);
		$cleansiterepeatarticle->addOption('0', '按书名判断重复');
		$cleansiterepeatarticle->addOption('1', '按书名+作者判断重复');
		$collect_form->addElement($cleansiterepeatarticle);
		
		if(empty($jieqiCollect['pagecharset'])) $jieqiCollect['pagecharset']='auto';
		$pagecharset=new JieqiFormSelect($jieqiLang['article']['rule_page_charset'], 'pagecharset', $jieqiCollect['pagecharset']);
		$pagecharset->addOption('auto', $jieqiLang['article']['rule_charset_auto']);
		$pagecharset->addOption('gbk', $jieqiLang['article']['rule_charset_gb']);
		$pagecharset->addOption('utf8', $jieqiLang['article']['rule_charset_utf8']);
		$pagecharset->addOption('big5', $jieqiLang['article']['rule_charset_big5']);
		$pagecharset->setDescription($jieqiLang['article']['rule_charset_note']);
		$collect_form->addElement($pagecharset);
		
		//可选项配置
		$this->addConfig('article','option');
		$jieqiOption['article'] = $this->getConfig('article','option');
		$items = $jieqiOption['article']['firstflag']['items'];
		$firstflag = new JieqiFormSelect('首发网站', 'firstflag', $jieqiCollect['firstflag']);
		foreach($items as $k=>$v){
			$firstflag->addOption($k, $v);
		}
		$collect_form->addElement($firstflag);

		if(empty($jieqiCollect['display'])) $jieqiCollect['display']='0';
		$displayarticle=new JieqiFormRadio('采集的文章默认设置为', 'display', $jieqiCollect['display']);
		$displayarticle->addOption('0', '显示');
		$displayarticle->addOption('1', '待审');
		$collect_form->addElement($displayarticle);
		
		
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_articleinfo']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_articleinfo_url'], 'urlarticle', 60, 250, htmlspecialchars($jieqiCollect['urlarticle'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_title'], 'articletitle', htmlspecialchars(jieqi_collectstop($jieqiCollect['articletitle']), ENT_QUOTES), 5, 60), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_author'], 'author', htmlspecialchars(jieqi_collectstop($jieqiCollect['author']), ENT_QUOTES), 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_sort'], 'sort', htmlspecialchars(jieqi_collectstop($jieqiCollect['sort']), ENT_QUOTES), 5, 60));
		if(!is_array($jieqiCollect['sortid'])) $jieqiCollect['sortid']=array();
		$tmpstr='';
		foreach($jieqiCollect['sortid'] as $k=>$v){
			if(!empty($tmpstr)) $tmpstr.='||';
			$tmpstr.=$k.'=>'.$v;
		}
		$sortelement=new JieqiFormText($jieqiLang['article']['rule_sort_relation'], 'sortid', 60, 10000, htmlspecialchars($tmpstr, ENT_QUOTES));
		$sortelement->setIntro($jieqiLang['article']['rule_sort_note']);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');
		$sortstr='';
		foreach($jieqiSort['article'] as $k=>$v){
			if(!empty($sortstr)) $sortstr.='||';
			$sortstr.=$v['caption'].'=>'.$k;
		}
		$sortelement->setDescription(sprintf($jieqiLang['article']['rule_sort_guide'], $sortstr));
		$collect_form->addElement($sortelement);
		
		//定制版允许二级分类采集
		if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))){
			//if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
			$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_typeid'], 'type', htmlspecialchars(jieqi_collectstop($jieqiCollect['type']), ENT_QUOTES), 5, 60));
			if(!is_array($jieqiCollect['typeid'])) $jieqiCollect['typeid']=array();
			$tmpstr='';
			foreach($jieqiCollect['typeid'] as $k=>$v){
				$i =0;
				$tmpstr.=$k.'=>';
				//if(!empty($tmpstr)) $tmpstr.='||';
				foreach($v as $tk=>$tv){
				   $i++;
				   $tmpstr.=$tk.'='.$tv;
				   if($i!=count($v)) $tmpstr.=','; else $tmpstr.='||';
				}
			}
			$tmpstr = substr($tmpstr,0,strlen($tmpstr)-2);
			$typeelement=new JieqiFormText($jieqiLang['article']['rule_typeid_relation'], 'typeid', 60, 10000, htmlspecialchars($tmpstr, ENT_QUOTES));
			$typeelement->setIntro($jieqiLang['article']['rule_typeid_note']);
			$sortstr='';
			foreach($jieqiSort['article'] as $k=>$v){
				if(is_array($v['types'])){
				   $sortstr.=$v['caption'].$k.'=array(';
				   $i =0;
				   foreach($v['types'] as $tk=>$tv){
					  $i++;
					  if($tk){
						  $sortstr.=$tv.'=>'.$tk;
						  if($i==count($v['types'])) $sortstr.=')'; else $sortstr.=',';
					  }
				   }
				   $sortstr.='<br>';
				}
			}
			$typeelement->setDescription(sprintf($jieqiLang['article']['rule_typeid_guide'], $sortstr));
			$collect_form->addElement($typeelement);
		}
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_keywords'], 'keyword', htmlspecialchars(jieqi_collectstop($jieqiCollect['keyword']), ENT_QUOTES), 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_intro'], 'intro', htmlspecialchars(jieqi_collectstop($jieqiCollect['intro']), ENT_QUOTES), 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_image'], 'articleimage', htmlspecialchars(jieqi_collectstop($jieqiCollect['articleimage']), ENT_QUOTES), 5, 60));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_articleimage_filter'], 'filterimage', 60, 250, htmlspecialchars($jieqiCollect['filterimage'], ENT_QUOTES)));
		
		$indexelement=new JieqiFormTextArea($jieqiLang['article']['rule_articleindex_url'], 'indexlink', htmlspecialchars(jieqi_collectstop($jieqiCollect['indexlink']), ENT_QUOTES), 5, 60);
		$indexelement->setIntro($jieqiLang['article']['rule_articleindex_note']);
		$collect_form->addElement($indexelement);
		
		$fullelement=new JieqiFormTextArea($jieqiLang['article']['rule_article_full'], 'fullarticle', htmlspecialchars(jieqi_collectstop($jieqiCollect['fullarticle']), ENT_QUOTES), 5, 60);
		$fullelement->setIntro($jieqiLang['article']['rule_articlefull_note']);
		$collect_form->addElement($fullelement);
        
		$collect_form->addElement(new JieqiFormTextArea('VIP的开始章节位置', 'vipstart', htmlspecialchars(jieqi_collectstop($jieqiCollect['vipstart']), ENT_QUOTES), 5, 60));

		$collect_form->addElement(new JieqiFormTextArea('章节是否VIP', 'isvip', htmlspecialchars(jieqi_collectstop($jieqiCollect['isvip']), ENT_QUOTES), 5, 60));

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_index']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_index_url'], 'urlindex', 60, 250, htmlspecialchars($jieqiCollect['urlindex'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_volume_name'], 'volume', htmlspecialchars(jieqi_collectstop($jieqiCollect['volume']), ENT_QUOTES), 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_name'], 'chapter', htmlspecialchars(jieqi_collectstop($jieqiCollect['chapter']), ENT_QUOTES), 5, 60), true);
	
		$chapterfilter=new JieqiFormTextArea(str_replace('内容','名称',$jieqiLang['article']['rule_chapter_filter']), 'chapterfilter', htmlspecialchars(jieqi_collectstop($jieqiCollect['chapterfilter']), ENT_QUOTES), 5, 60);
		$chapterfilter->setIntro($jieqiLang['article']['rule_chapterfilter_note']);
		$collect_form->addElement($chapterfilter);
		
		$chapterreplace=new JieqiFormTextArea(str_replace('内容','名称',$jieqiLang['article']['rule_chapter_replace']), 'chapterreplace', htmlspecialchars(jieqi_collectstop($jieqiCollect['chapterreplace']), ENT_QUOTES), 5, 60);
		$chapterreplace->setIntro($jieqiLang['article']['rule_chapterreplace_note']);
		$collect_form->addElement($chapterreplace);

		$cleanchapter=new JieqiFormText('过滤章节', 'cleanchapter', 60, 100, htmlspecialchars(jieqi_collectstop($jieqiCollect['cleanchapter']), ENT_QUOTES));
		$cleanchapter->setDescription('如果目标网站章节中包含所设置的关键词则自动过滤！');
		$collect_form->addElement($cleanchapter);

		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_id'], 'chapterid', htmlspecialchars(jieqi_collectstop($jieqiCollect['chapterid']), ENT_QUOTES), 5, 60), true);
		
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_chapter']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_chapter_url'], 'urlchapter', 60, 250, htmlspecialchars($jieqiCollect['urlchapter'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_content'], 'content', htmlspecialchars(jieqi_collectstop($jieqiCollect['content']), ENT_QUOTES), 5, 60), true);
		
		$filterelement=new JieqiFormTextArea($jieqiLang['article']['rule_chapter_filter'], 'contentfilter', htmlspecialchars(jieqi_collectstop($jieqiCollect['contentfilter']), ENT_QUOTES), 5, 60);
		$filterelement->setIntro($jieqiLang['article']['rule_chapterfilter_note']);
		$collect_form->addElement($filterelement);
		
		$replaceelement=new JieqiFormTextArea($jieqiLang['article']['rule_chapter_replace'], 'contentreplace', htmlspecialchars(jieqi_collectstop($jieqiCollect['contentreplace']), ENT_QUOTES), 5, 60);
		$replaceelement->setIntro($jieqiLang['article']['rule_chapterreplace_note']);
		$collect_form->addElement($replaceelement);
		$collect_form->addElement(new JieqiFormTextArea(str_replace('内容','发布时间',$jieqiLang['article']['rule_chapter_content']), 'postdate', htmlspecialchars(jieqi_collectstop($jieqiCollect['postdate']), ENT_QUOTES), 5, 60));
		$collectimage=new JieqiFormRadio($jieqiLang['article']['rule_or_articleimage'], 'collectimage', $jieqiCollect['collectimage']);
		$collectimage->addOption('1', LANG_YES);
		$collectimage->addOption('0', LANG_NO);
		$collect_form->addElement($collectimage);
		
		//图片处理
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_imagetranslate']));
		
		$imagetranslate=new JieqiFormRadio($jieqiLang['article']['rule_or_imagetranslate'], 'imagetranslate', intval($jieqiCollect['imagetranslate']));
		$imagetranslate->addOption('1', LANG_YES);
		$imagetranslate->addOption('0', LANG_NO);
		$imagetranslate->setDescription($jieqiLang['article']['rule_or_imagetranslatedec']);
		$collect_form->addElement($imagetranslate);
		
		$addimagewater=new JieqiFormRadio($jieqiLang['article']['rule_or_imagewater'], 'addimagewater', intval($jieqiCollect['addimagewater']));
		$addimagewater->addOption('1', LANG_YES);
		$addimagewater->addOption('0', LANG_NO);
		$addimagewater->setDescription($jieqiLang['article']['rule_or_imagewaterdec']);
		$collect_form->addElement($addimagewater);
		
		$imagebgcolor=new JieqiFormText($jieqiLang['article']['rule_image_bgcolor'], 'imagebgcolor', 60, 20, htmlspecialchars($jieqiCollect['imagebgcolor'], ENT_QUOTES));
		$imagebgcolor->setDescription($jieqiLang['article']['rule_image_bgcolordec']);
		$collect_form->addElement($imagebgcolor);
		
		$imageareaclean=new JieqiFormText($jieqiLang['article']['rule_image_areaclean'], 'imageareaclean', 60, 1000, htmlspecialchars($jieqiCollect['imageareaclean'], ENT_QUOTES));
		$imageareaclean->setDescription($jieqiLang['article']['rule_image_areacleandec']);
		$collect_form->addElement($imageareaclean);
		
		$imagecolorclean=new JieqiFormText($jieqiLang['article']['rule_image_colorclean'], 'imagecolorclean', 60, 1000, htmlspecialchars($jieqiCollect['imagecolorclean'], ENT_QUOTES));
		$imagecolorclean->setDescription($jieqiLang['article']['rule_image_colorcleandec']);
		$collect_form->addElement($imagecolorclean);


		$collect_form->addElement(new JieqiFormHidden('action', 'edit'));
		$collect_form->addElement(new JieqiFormHidden('config', htmlspecialchars($params['config'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_save_edit'], 'submit'));
		
		$data = array();
		$data ['jieqi_contents'] = '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />';
		$data ['article_static_url'] = $article_static_url;
		$data ['article_dynamic_url'] = $article_dynamic_url;
		
		return $data;
	}
	//批量采集规则 视图
	public function collectpage($params = array()){
		global $jieqiModules;
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$jieqiLang['article'] = $this->getLang('article');//print_r($jieqiLang);//exit();
		
		if(empty($params['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) $this->printfail($jieqiLang['article']['rule_not_exists']);
		include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php');
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
		switch($params['action']){
			case 'new':
				$tmpary=array();
				$tmpary['title']=trim($params['title']); //采集规则名称
				$tmpary['urlpage']=trim($params['urlpage']); //采集地址
				$tmpary['articleid']=jieqi_collectptos($params['articleid']);  //获取文章id规则
				$tmpary['startpageid']=trim($params['startpageid']);  //第一页变量
				$tmpary['nextpageid']=jieqi_collectptos($params['nextpageid']); //获取下一页变量
				$params['maxpagenum']=trim($params['maxpagenum']);
				if(is_numeric($params['maxpagenum'])) $tmpary['maxpagenum']=intval($params['maxpagenum']);  //最多采集几页
				else $tmpary['maxpagenum']='';
				$jieqiCollect['listcollect'][]=$tmpary;
				jieqi_setconfigs('site_'.$params['config'], 'jieqiCollect', $jieqiCollect, 'article');
				break;
			case 'del':
				if(isset($params['cid']) && isset($jieqiCollect['listcollect'][$params['cid']])){
					unset($jieqiCollect['listcollect'][$params['cid']]);
					jieqi_setconfigs('site_'.$params['config'], 'jieqiCollect', $jieqiCollect, 'article');
				}
				break;
		}
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		$data = array();
		$data ['article_static_url'] = $article_static_url;
		$data ['article_dynamic_url'] = $article_dynamic_url;
		$data ['sitename'] = $jieqiCollect['sitename'];
		$data ['config'] = $params['config'];
		$data ['collectrows'] = $jieqiCollect['listcollect'];
		
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['add_batch_collectrule'], 'collectnew', $article_static_url.'/web_admin/?controller=collectset&method=collectpage');
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_name'], 'title', 60, 60, ''), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_url'], 'urlpage', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['collect_rule_articleid'], 'articleid', '', 5, 60), true);
		$nextpageid=new JieqiFormTextArea($jieqiLang['article']['rule_next_pageid'], 'nextpageid', '', 5, 60);
		$nextpageid->setDescription($jieqiLang['article']['rule_nextpage_note']);
		$collect_form->addElement($nextpageid);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_start_pageid'], 'startpageid', 60, 60, ''));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_max_pagenum'], 'maxpagenum', 60, 10, ''));
		
		$collect_form->addElement(new JieqiFormHidden('config', htmlspecialchars($params['config'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormHidden('action', 'new'));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_add_new'], 'submit'));
		$data ['addnewtable'] = $collect_form->render(JIEQI_FORM_MIDDLE);
		
		return $data;
	}
	//批量采集规则 编辑视图
	public function collectpeditview($params = array()){
		global $jieqiModules;
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$jieqiLang['article'] = $this->getLang('article');
		
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		$data = array();
		$data ['article_static_url'] = $article_static_url;
		$data ['article_dynamic_url'] = $article_dynamic_url;
		
		if(empty($params['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) $this->printfail($jieqiLang['article']['no_site_collectrule']);
		include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php');
		if(!isset($params['cid']) || !isset($jieqiCollect['listcollect'][$params['cid']])) $this->printfail($jieqiLang['article']['no_batch_collectrule']);
		
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['batchcollect_edit'], 'collectedit', $article_static_url.'/web_admin/?controller=collectset&method=collectpedit');
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_siteid'], $jieqiCollect['sitename']));

		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_name'], 'title', 60, 60, htmlspecialchars($jieqiCollect['listcollect'][$params['cid']]['title'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_url'], 'urlpage', 60, 250, htmlspecialchars($jieqiCollect['listcollect'][$params['cid']]['urlpage'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['collect_rule_articleid'], 'articleid', htmlspecialchars(jieqi_collectstop($jieqiCollect['listcollect'][$params['cid']]['articleid']), ENT_QUOTES), 5, 60), true);
		$nextpageid=new JieqiFormTextArea($jieqiLang['article']['rule_next_pageid'], 'nextpageid', htmlspecialchars(jieqi_collectstop($jieqiCollect['listcollect'][$params['cid']]['nextpageid']), ENT_QUOTES), 5, 60);
		$nextpageid->setDescription($jieqiLang['article']['rule_nextpage_note']);
		$collect_form->addElement($nextpageid);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_start_pageid'], 'startpageid', 60, 60, htmlspecialchars($jieqiCollect['listcollect'][$params['cid']]['startpageid'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_max_pagenum'], 'maxpagenum', 60, 10, htmlspecialchars($jieqiCollect['listcollect'][$params['cid']]['maxpagenum'], ENT_QUOTES)));

		$collect_form->addElement(new JieqiFormHidden('config', htmlspecialchars($params['config'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormHidden('cid', htmlspecialchars($params['cid'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormHidden('action', 'edit'));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_save_edit'], 'submit'));

		$data['jieqi_contents'] = '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />';
		return $data;
	}
	//批量采集规则 编辑
	public function collectpedit($params = array()){
		global $jieqiModules;
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$jieqiLang['article'] = $this->getLang('article','collect');
		
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		$data = array();
		$data ['article_static_url'] = $article_static_url;
		$data ['article_dynamic_url'] = $article_dynamic_url;
		
		if(empty($params['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) $this->printfail($jieqiLang['article']['no_site_collectrule']);
		include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php');
		if(!isset($params['cid']) || !isset($jieqiCollect['listcollect'][$params['cid']])) jieqi_printfail($jieqiLang['article']['no_batch_collectrule']);
		
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
		$tmpary=array();
		$tmpary['title']=trim($params['title']); //采集规则名称
		$tmpary['urlpage']=trim($params['urlpage']); //采集地址
		$tmpary['articleid']=jieqi_collectptos($params['articleid']);  //获取文章id规则
		$tmpary['startpageid']=trim($params['startpageid']);  //第一页变量
		$tmpary['nextpageid']=jieqi_collectptos($params['nextpageid']); //获取下一页变量
		$params['maxpagenum']=trim($params['maxpagenum']);
		if(is_numeric($params['maxpagenum'])) $tmpary['maxpagenum']=intval($params['maxpagenum']);  //最多采集几页
		else $tmpary['maxpagenum']='';
		$jieqiCollect['listcollect'][$params['cid']]=$tmpary;
		$configstr="<?php\n".jieqi_extractvars('jieqiCollect', $jieqiCollect)."\n?>";
		jieqi_writefile(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php', $configstr);
		
		$this->jumppage($jieqiModules['article']['url'].'/web_admin/?controller=collectset&method=collectpage&config='.$params['config'], LANG_DO_SUCCESS, $jieqiLang['article']['batchcollect_edit_success']);
	}
	//新建规则
	public function collectnew($params = array()){
		global $jieqiModules;
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$jieqiLang['article'] = $this->getLang('article','collect');
		
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
		$params['config']=trim($params['config']);
		$errtext='';
		if(empty($params['config'])) $errtext .= $jieqiLang['article']['rule_need_siteid'].'<br />';
		elseif(file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php')) $errtext .= $jieqiLang['article']['rule_siteid_exists'].'<br />';
		if(!empty($errtext)) jieqi_printfail($errtext);

		
		$newCollect=array();
		$newCollect['sitename']=trim($params['sitename']);  //站名
		$newCollect['siteurl']=trim($params['siteurl']); //网址
		if(is_numeric(str_replace(array('<{articleid}>', '<{chapterid}>', 'ceil', 'floor', 'round', 'substr', 'intval', 'is_numeric', '+', '-', '*', '/', '%', ',', '?', '=', '>', '<', ':', '(', ')', ' '), '', $params['subarticleid']))) $newCollect['subarticleid']=str_replace(array('<{articleid}>', '<{chapterid}>'), array('$articleid', '$chapterid'), trim($params['subarticleid'])); //文章子序号运算方式
		else $newCollect['subarticleid']='';
		if(is_numeric(str_replace(array('<{articleid}>', '<{chapterid}>', 'ceil', 'floor', 'round', 'substr', 'intval', 'is_numeric', '+', '-', '*', '/', '%', ',', '?', '=', '>', '<', ':', '(', ')', ' '), '', $params['subchapterid']))) $newCollect['subchapterid']=str_replace(array('<{articleid}>', '<{chapterid}>'), array('$articleid', '$chapterid'), trim($params['subchapterid'])); //子序号运算方式
		else $newCollect['subchapterid']='';
		//代理服务器地址
		$newCollect['proxy_host']=trim($params['proxy_host']);
		//代理服务器端口
		$newCollect['proxy_port']=trim($params['proxy_port']);
		//代理服务器帐号
		//$newCollect['proxy_user']=trim($params['proxy_user']);
		//代理服务器密码
		//$newCollect['proxy_pass']=trim($params['proxy_pass']);
		
		//章节无法对应是是否自动清空重新采集
		$newCollect['autoclear']=trim($params['autoclear']);
		//是否默认全本
		$newCollect['defaultfull']=trim($params['defaultfull']);
		//启用referer
		$newCollect['referer']=trim($params['referer']);
		//启用autochaptercollect
		$newCollect['autochaptercollect']=trim($params['autochaptercollect']);
		//启用chapteridorder
		$newCollect['chapteridorder']=trim($params['chapteridorder']);
		//启用cleantargetsiterepeatchapter
		$newCollect['cleantargetsiterepeatchapter']=trim($params['cleantargetsiterepeatchapter']);
		//启用cleansiterepeatchapter
		$newCollect['cleansiterepeatchapter']=trim($params['cleansiterepeatchapter']);
		//启用wget
		$newCollect['wget']=trim($params['wget']);
		//采集时生成
		$newCollect['makeset']=@implode(',',$params['makeset']);
		//网页编码
		$newCollect['pagecharset']=trim($params['pagecharset']);
		//首发状态
		$newCollect['firstflag']=trim($params['firstflag']);
		//文章信息页面
		$newCollect['urlarticle']=trim($params['urlarticle']);
		//文章标题
		$newCollect['articletitle']=jieqi_collectptos($params['articletitle']);
		//作者
		$newCollect['author']=jieqi_collectptos($params['author']);
		//类型
		$newCollect['sort']=jieqi_collectptos($params['sort']);
		//子类型
		$newCollect['type']=jieqi_collectptos($params['type']);
		//关键字
		$newCollect['keyword']=jieqi_collectptos($params['keyword']);
		//简介
		$newCollect['intro']=jieqi_collectptos($params['intro']);
		//封面
		$newCollect['articleimage']=jieqi_collectptos($params['articleimage']);
		//过滤封面
		$newCollect['filterimage']=trim($params['filterimage']);
		//目录页链接
		$newCollect['indexlink']=jieqi_collectptos($params['indexlink']);
		//全文标记
		$newCollect['fullarticle']=jieqi_collectptos($params['fullarticle']);
		
		//文章类型对应id
		$sortary=explode('||', trim($params['sortid']));
		$newCollect['sortid']=array();
		foreach($sortary as $v){
			$tmpary=explode('=>', trim($v));
			if(count($tmpary)==2){
				$sname=trim($tmpary[0]);
				$sid=trim($tmpary[1]);
				if(is_numeric($sid)) $newCollect['sortid'][$sname]=$sid;
			}
		}
		
		//文章子类型对应id
		$typeary=explode('||', trim($params['typeid']));
		$newCollect['typeid']=array();
		foreach($typeary as $v){
			$btmpary=explode('=>', trim($v));
			if(count($btmpary)==2){
				$sname=trim($btmpary[0]);
				$smallary=explode(',', trim($btmpary[1]));
				if(count($smallary)>0){
					foreach($smallary as $k1=>$v1){
					    $strary=explode('=', trim($v1));
						$sid=trim($strary[1]);
						if(is_numeric($k1)) $newCollect['typeid'][$sname][$strary[0]]=$sid;
					}
				}
			}
		}
		
		$newCollect['urlindex']=trim($params['urlindex']); //文章目录页面
		//分卷名称
		$newCollect['volume']=jieqi_collectptos($params['volume']);
		//章节名称
		$newCollect['chapter']=jieqi_collectptos($params['chapter']);
		//章节序号
		$newCollect['chapterid']=jieqi_collectptos($params['chapterid']);
		//章节名称过滤
		$newCollect['chapterfilter']=trim($params['chapterfilter']);
		//章节名称替换
		$newCollect['chapterreplace']=trim($params['chapterreplace']);
		//过滤章节
		$newCollect['cleanchapter']=jieqi_collectptos($params['cleanchapter']);
		$newCollect['urlchapter']=trim($params['urlchapter']); //章节内容页面
		//章节内容
		$newCollect['content']=jieqi_collectptos($params['content']);
		//章节内容1
		//$newCollect['content1']=jieqi_collectptos($params['content1']);
		//章节内容过滤
		$newCollect['contentfilter']=trim($params['contentfilter']);
		//章节内容替换
		$newCollect['contentreplace']=trim($params['contentreplace']);
		//是否采集图片
		$newCollect['collectimage']=trim($params['collectimage']);
		
		//是否允许图片处理
		$newCollect['imagetranslate']=trim($params['imagetranslate']);
		//是否加水印
		$newCollect['addimagewater']=trim($params['addimagewater']);
		//图片背景色
		$newCollect['imagebgcolor']=trim($params['imagebgcolor']);
		//按区域清理
		$newCollect['imageareaclean']=trim($params['imageareaclean']);
		//按颜色清理
		$newCollect['imagecolorclean']=trim($params['imagecolorclean']);

		
		$configstr="<?php\n".jieqi_extractvars('jieqiCollect', $newCollect)."\n?>";
		jieqi_writefile(JIEQI_ROOT_PATH.'/configs/article/site_'.$params['config'].'.php', $configstr);
		$siteid = -1;
		$maxid = 0;
		if(!isset($jieqiCollectsite) || !is_array($jieqiCollectsite)) $jieqiCollectsite=array();
		else reset($jieqiCollectsite);
		while(list($k, $v) = each($jieqiCollectsite)) {
			if($k > $maxid) $maxid = $k;
			if($v['config']==$params['config']){
				$siteid=$k;
				break;
			}
		}
		$maxid++;
		if($siteid >= 0) $jieqiCollectsite[$siteid]=array('name'=>$newCollect['sitename'], 'config'=>$params['config'], 'url'=>$newCollect['siteurl'], 'subarticleid'=>$newCollect['subarticleid'], 'enable'=>'1');
		else $jieqiCollectsite[$maxid]=array('name'=>$newCollect['sitename'], 'config'=>$params['config'], 'url'=>$newCollect['siteurl'], 'subarticleid'=>$newCollect['subarticleid'], 'enable'=>'1');
		jieqi_setconfigs('collectsite', 'jieqiCollectsite', $jieqiCollectsite, 'article');
		$this->jumppage($article_static_url.'/web_admin/?controller=collectset', LANG_DO_SUCCESS, $jieqiLang['article']['rule_edit_success']);
	}
	//新建规则 视图
	public function collectnewview($params = array()){
		global $jieqiModules;
		//加载采集配置
		$this->addConfig('article','collectsite');
		$jieqiCollectsite = $this->getConfig('article','collectsite');
		//加载语言包
		$this->addLang('article','collect');
		$this->addLang('article','manage');
		$jieqiLang['article'] = $this->getLang('article');//print_r($jieqiLang);
		
		//加载文章配置
		$this->addConfig('article','configs');
		$jieqiConfigs['article'] = $this->getConfig('article','configs');
		
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		
		$data = array();
		$data ['article_static_url'] = $article_static_url;
		$data ['article_dynamic_url'] = $article_dynamic_url;
		
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['rule_add_new'], 'collectnew', $article_static_url.'/web_admin/?controller=collectset&method=collectnew');
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_basic']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_id'], 'config', 60, 20, ''), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_name'], 'sitename', 60, 50, ''), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_url'], 'siteurl', 60, 100, ''), true);
		$subarticleid=new JieqiFormText($jieqiLang['article']['rule_subarticleid'], 'subarticleid', 60, 100, '');
		$subarticleid->setDescription($jieqiLang['article']['rule_operation_note']);
		$collect_form->addElement($subarticleid);
		$subchapterid=new JieqiFormText($jieqiLang['article']['rule_subchapterid'], 'subchapterid', 60, 100, '');
		$subchapterid->setDescription($jieqiLang['article']['rule_operation_note']);
		$collect_form->addElement($subchapterid);
	
		$proxy_host=new JieqiFormText($jieqiLang['article']['rule_proxy_host'], 'proxy_host', 20, 100, '');
		$proxy_host->setDescription($jieqiLang['article']['rule_proxyhost_note']);
		$collect_form->addElement($proxy_host);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_proxy_port'], 'proxy_port', 20, 20, ''));
		//$proxy_user=new JieqiFormText($jieqiLang['article']['rule_proxy_user'], 'proxy_user', 20, 100, '');
		//$proxy_user->setDescription($jieqiLang['article']['rule_proxyuser_note']);
		//$collect_form->addElement($proxy_user);
		//$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_proxy_pass'], 'proxy_pass', 20, 100, ''));

		$autoclear=new JieqiFormRadio($jieqiLang['article']['rule_auto_clean'], 'autoclear', 0);
		$autoclear->addOption('1', LANG_YES);
		$autoclear->addOption('0', LANG_NO);
		$collect_form->addElement($autoclear);
		$defaultfull=new JieqiFormRadio($jieqiLang['article']['rule_default_full'], 'defaultfull', 0);
		$defaultfull->addOption('1', LANG_YES);
		$defaultfull->addOption('0', LANG_NO);
		$collect_form->addElement($defaultfull);
		
		$referer=new JieqiFormRadio($jieqiLang['article']['rule_send_referer'], 'referer', 0);
		$referer->addOption('1', LANG_YES);
		$referer->addOption('0', LANG_NO);
		$collect_form->addElement($referer);
		
		//定制版、豪华版允许
		if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM', 'DELUXE'))){
		    //if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
			//20091214增加wget突破防采集
			$wget=new JieqiFormRadio($jieqiLang['article']['rule_send_wget'], 'wget', 0);
			$wget->setDescription($jieqiLang['article']['rule_sendwget_note']);
			$wget->addOption('1', LANG_YES);
			$wget->addOption('0', LANG_NO);
			$collect_form->addElement($wget);
			
			//定制采集时生成项
			$makeset=new JieqiFormCheckBox($jieqiLang['article']['rule_make_set'], 'makeset', 0);
			$makeset->setDescription($jieqiLang['article']['rule_makeset_note']);
			$makeset->addOption('makehtml', $jieqiLang['article']['repack_html']);
			$makeset->addOption('makezip', $jieqiLang['article']['repack_zip']);
			$makeset->addOption('maketxtfull', $jieqiLang['article']['repack_txtfullpage']);
			$makeset->addOption('makeumd', $jieqiLang['article']['repack_umdpage']);
			$makeset->addOption('makejar', $jieqiLang['article']['repack_jarpage']);
			$collect_form->addElement($makeset);		
		}
		$pagecharset=new JieqiFormSelect($jieqiLang['article']['rule_page_charset'], 'pagecharset', 'auto');
		
		$autochaptercollect=new JieqiFormRadio('章节高级智能采集', 'autochaptercollect', 0);
		$autochaptercollect->addOption('1', LANG_YES);
		$autochaptercollect->addOption('0', LANG_NO);
		$autochaptercollect->setDescription('建议批量采集文章的时候不要开启此功能，以防章节采集发生混乱');
		$collect_form->addElement($autochaptercollect);
		

		$chapteridorder=new JieqiFormRadio('章节ID号排序', 'chapteridorder', 0);
		$chapteridorder->addOption('1', LANG_YES);
		$chapteridorder->addOption('0', LANG_NO);
		$chapteridorder->setDescription('目标网站章节按ID号从小往大排序，用于解决目标网站的防采集设置！');
		$collect_form->addElement($chapteridorder);
		
		$cleantargetsiterepeatchapter=new JieqiFormRadio('过滤目标网站重复章节', 'cleantargetsiterepeatchapter', 0);
		$cleantargetsiterepeatchapter->addOption('1', LANG_YES);
		$cleantargetsiterepeatchapter->addOption('0', LANG_NO);
		$cleantargetsiterepeatchapter->setDescription('开采此功能能有效防止采集重复章节，但也有可能造成章节混乱！');
		$collect_form->addElement($cleantargetsiterepeatchapter);

		$cleansiterepeatchapter=new JieqiFormSelect('过滤本站重复章节', 'cleansiterepeatchapter', 0);
		$cleansiterepeatchapter->addOption('0', '不开启过滤');
		$cleansiterepeatchapter->addOption('1', '开启过滤重复章节');
		$cleansiterepeatchapter->addOption('2', '开启过滤并删除重复章节');
		$cleansiterepeatchapter->setDescription('在文章有重复章节的情况下，开启此选项能有效避免再次采集重复章节！');
		$collect_form->addElement($cleansiterepeatchapter);
		
		if(empty($jieqiCollect['pagecharset'])) $jieqiCollect['pagecharset']='auto';
		$pagecharset=new JieqiFormSelect($jieqiLang['article']['rule_page_charset'], 'pagecharset', 'auto');
		$pagecharset->addOption('auto', $jieqiLang['article']['rule_charset_auto']);
		$pagecharset->addOption('gbk', $jieqiLang['article']['rule_charset_gb']);
		$pagecharset->addOption('utf8', $jieqiLang['article']['rule_charset_utf8']);
		$pagecharset->addOption('big5', $jieqiLang['article']['rule_charset_big5']);
		$pagecharset->setDescription($jieqiLang['article']['rule_charset_note']);
		$collect_form->addElement($pagecharset);

		//可选项配置
		$this->addConfig('article','option');
		$jieqiOption['article'] = $this->getConfig('article','option');
		$items = $jieqiOption['article']['firstflag']['items'];
		$firstflag = new JieqiFormSelect('首发网站', 'firstflag', $jieqiOption['article']['firstflag']['default']);
		foreach($items as $k=>$v){
			$firstflag->addOption($k, $v);
		}
		$collect_form->addElement($firstflag);

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_articleinfo']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_articleinfo_url'], 'urlarticle', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_title'], 'articletitle', '', 5, 60), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_author'], 'author', '', 5, 60));
		
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_sort'], 'sort', '', 5, 60));
		$sortelement=new JieqiFormText($jieqiLang['article']['rule_sort_relation'], 'sortid', 60, 10000, '');
		$sortelement->setIntro($jieqiLang['article']['rule_sort_note']);
		$this->addConfig('article','sort');
		$jieqiSort['article'] = $this->getConfig('article','sort');
		$sortstr='';
		foreach($jieqiSort['article'] as $k=>$v){
			if(!empty($sortstr)) $sortstr.='||';
			$sortstr.=$v['caption'].'=>'.$k;
		}
		$sortelement->setDescription(sprintf($jieqiLang['article']['rule_sort_guide'], $sortstr));
		$collect_form->addElement($sortelement);
		
		//定制版允许二级分类采集
		if(in_array(strtoupper(JIEQI_MODULE_VTYPE), array('CUSTOM'))){
			//if(!isset($Version160)) exit('PHP:SYSTEM CODE ERROR!');
			$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_typeid'], 'type', '', 5, 60));
			$typeelement=new JieqiFormText($jieqiLang['article']['rule_typeid_relation'], 'typeid', 60, 10000, '');
			$typeelement->setIntro($jieqiLang['article']['rule_typeid_note']);
			$sortstr='';
			foreach($jieqiSort['article'] as $k=>$v){
				if(is_array($v['types'])){
				   $sortstr.=$v['caption'].$k.'=array(';
				   $i =0;
				   foreach($v['types'] as $tk=>$tv){
					  $i++;
					  if($tk){
						  $sortstr.=$tv.'=>'.$tk;
						  if($i==count($v['types'])) $sortstr.=')'; else $sortstr.=',';
					  }
				   }
				   $sortstr.='<br>';
				}
			}
			$typeelement->setDescription(sprintf($jieqiLang['article']['rule_typeid_guide'], $sortstr));
			$collect_form->addElement($typeelement);
		}
		
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_keywords'], 'keyword', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_intro'], 'intro', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_image'], 'articleimage', '', 5, 60));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_articleimage_filter'], 'filterimage', 60, 250, ''));
		$indexelement=new JieqiFormTextArea($jieqiLang['article']['rule_articleindex_url'], 'indexlink', '', 5, 60);
		$indexelement->setIntro($jieqiLang['article']['rule_articleindex_note']);
		$collect_form->addElement($indexelement);
		
		$fullelement=new JieqiFormTextArea($jieqiLang['article']['rule_article_full'], 'fullarticle', '', 5, 60);
		$fullelement->setIntro($jieqiLang['article']['rule_articlefull_note']);
		$collect_form->addElement($fullelement);

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_index']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_index_url'], 'urlindex', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_volume_name'], 'volume', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_name'], 'chapter', '', 5, 60), true);
			
		$chapterfilter=new JieqiFormTextArea(str_replace('内容','名称',$jieqiLang['article']['rule_chapter_filter']), 'chapterfilter', '', 5, 60);
		$chapterfilter->setIntro($jieqiLang['article']['rule_chapterfilter_note']);
		$collect_form->addElement($chapterfilter);
		
		$chapterreplace=new JieqiFormTextArea(str_replace('内容','名称',$jieqiLang['article']['rule_chapter_replace']), 'chapterreplace', '', 5, 60);
		$chapterreplace->setIntro($jieqiLang['article']['rule_chapterreplace_note']);
		$collect_form->addElement($chapterreplace);

		$cleanchapter=new JieqiFormText('过滤章节', 'cleanchapter', 60, 100, '');
		$cleanchapter->setDescription('如果目标网站章节中包含所设置的关键词则自动过滤！');
		$collect_form->addElement($cleanchapter);
		
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_id'], 'chapterid', '', 5, 60), true);

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_chapter']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_chapter_url'], 'urlchapter', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_content'], 'content', '', 5, 60), true);
		//$content1element=new JieqiFormTextArea('章节内容备用采集规则', 'content1', '', 5, 60);
		//$content1element->setIntro('当前面的规则无法采集时候程序将尝试用备用规则采集');
		//$collect_form->addElement($content1element);
		
		$filterelement=new JieqiFormTextArea($jieqiLang['article']['rule_chapter_filter'], 'contentfilter', '', 5, 60);
		$filterelement->setIntro($jieqiLang['article']['rule_chapterfilter_note']);
		$collect_form->addElement($filterelement);
		
		$replaceelement=new JieqiFormTextArea($jieqiLang['article']['rule_chapter_replace'], 'contentreplace', '', 5, 60);
		$replaceelement->setIntro($jieqiLang['article']['rule_chapterreplace_note']);
		$collect_form->addElement($replaceelement);
		
		$collectimage=new JieqiFormRadio($jieqiLang['article']['rule_or_articleimage'], 'collectimage', 1);
		$collectimage->addOption('1', LANG_YES);
		$collectimage->addOption('0', LANG_NO);
		$collect_form->addElement($collectimage);
		
		//图片处理
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_imagetranslate']));
		
		$imagetranslate=new JieqiFormRadio($jieqiLang['article']['rule_or_imagetranslate'], 'imagetranslate', 0);
		$imagetranslate->addOption('1', LANG_YES);
		$imagetranslate->addOption('0', LANG_NO);
		$imagetranslate->setDescription($jieqiLang['article']['rule_or_imagetranslatedec']);
		$collect_form->addElement($imagetranslate);
		
		$addimagewater=new JieqiFormRadio($jieqiLang['article']['rule_or_imagewater'], 'addimagewater', 0);
		$addimagewater->addOption('1', LANG_YES);
		$addimagewater->addOption('0', LANG_NO);
		$addimagewater->setDescription($jieqiLang['article']['rule_or_imagewaterdec']);
		$collect_form->addElement($addimagewater);
		
		$imagebgcolor=new JieqiFormText($jieqiLang['article']['rule_image_bgcolor'], 'imagebgcolor', 60, 20, '');
		$imagebgcolor->setDescription($jieqiLang['article']['rule_image_bgcolordec']);
		$collect_form->addElement($imagebgcolor);
		
		$imageareaclean=new JieqiFormText($jieqiLang['article']['rule_image_areaclean'], 'imageareaclean', 60, 1000, '');
		$imageareaclean->setDescription($jieqiLang['article']['rule_image_areacleandec']);
		$collect_form->addElement($imageareaclean);
		
		$imagecolorclean=new JieqiFormText($jieqiLang['article']['rule_image_colorclean'], 'imagecolorclean', 60, 1000, '');
		$imagecolorclean->setDescription($jieqiLang['article']['rule_image_colorcleandec']);
		$collect_form->addElement($imagecolorclean);


		$collect_form->addElement(new JieqiFormHidden('action', 'new'));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_add_new'], 'submit'));

		$data ['jieqi_contents'] = '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />';
		return $data;
	}
} 
?>