<?php 
/** 
 * 测试模型 * @copyright   Copyright(c) 2014 
 * @author      gaoli* @version     1.0 
 */ 
class setavatarModel extends Model{
	
	/**
	 * false版，保存头像
	 */
	public function saveAvatar($param){
		global $jieqiConfigs, $jieqiLang, $jieqi_image_type;
		jieqi_getconfigs('system', 'configs');
		$this->addLang('system', 'users');
		$auth = $this->getAuth ();
		$users_handler = $this->getUserObject ();
		$jieqiUsers = $users_handler->get ( $auth ['uid'] );
		$basedir=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system').jieqi_getsubdir($auth ['uid']);
		//检查目录
// 		$rs['status'] = '-2';
		if(jieqi_checkdir($basedir,true)){
// 			$savePath = './';  //图片存储路径
// 			$savePicName = time();  //图片存储名称
			// 		$file_src = $savePath.$savePicName."_src.jpg";
			// 		$filename162 = $savePath.$savePicName."_162.jpg";
			// 		$filename48 = $savePath.$savePicName."_48.jpg";
			// 		$filename20 = $savePath.$savePicName."_20.jpg";
			//原图,限制
// 			$len = strlen(base64_decode($param['pic']));
// 			$rs['temp'] = $len;
// 			$rs['aa'] = $jieqiConfigs['system']['avatarsize']*1024;
// 			$rs['status'] = '1';
			//检查原图大小，
			$rs['picUrl'] = '';
			if(strlen(base64_decode($param['pic'])) > (intval($jieqiConfigs['system']['avatarsize']) * 1024)){
				$rs['status'] = '-1';//原图过大
				$rs['tip'] = sprintf($jieqiLang['system']['avatar_filesize_toolarge'],$jieqiConfigs['system']['avatarsize']);
			}else{
				//四种图，原图,162,48,20
				$file=$basedir.'/'.$auth ['uid'].'.jpg';
				$file1=$basedir.'/'.$auth ['uid'].'l.jpg';
				$file2=$basedir.'/'.$auth ['uid'].'m.jpg';
				$file3=$basedir.'/'.$auth ['uid'].'s.jpg';
				$src=base64_decode($param['pic']);
				$pic1=base64_decode($param['pic1']);
				$pic2=base64_decode($param['pic2']);
				$pic3=base64_decode($param['pic3']);
				if($src) {
					jieqi_writefile($file,$src);
				}
				jieqi_writefile($file1,$pic1);
				jieqi_writefile($file2,$pic2);
				jieqi_writefile($file3,$pic3);
					
				$rs['status'] = '1';
				//原图路径
				$rs['picUrl'] = jieqi_uploadurl($jieqiConfigs['system']['avatardir'], $jieqiConfigs['system']['avatarurl'], 'system').jieqi_getsubdir($auth ['uid']).'/'.$auth ['uid'];
				//更新avatar字段
				$jieqiUsers->unsetNew();
				$jieqiUsers->setVar('avatar',2);
				if (!$users_handler->insert($jieqiUsers)){
					$rs['tip'] = $jieqiLang['system']['avatar_set_failure'];
				}
				$_SESSION['jieqiUserAvatar'] = 2;
			}
			// 		print json_encode($rs);
		}
		return $rs;
	}
	public function cutsave(){
		global $jieqiConfigs, $jieqiLang, $jieqi_image_type;
		$_REQUEST = $this->getRequest();
		jieqi_checklogin();
		$this->addLang('system', 'users');
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
		if(!$jieqiUsers) $this->printfail(LANG_NO_USER);
		jieqi_getconfigs('system', 'configs');
		
		$jieqiConfigs['system']['avatardt'] = '.jpg';
		$jieqiConfigs['system']['avatardw'] = '120';
		$jieqiConfigs['system']['avatardh'] = '120';
		$jieqiConfigs['system']['avatarsw'] = '48';
		$jieqiConfigs['system']['avatarsh'] = '48';
		$jieqiConfigs['system']['avatariw'] = '16';
		$jieqiConfigs['system']['avatarih'] = '16';
		
		$old_avatar=$jieqiUsers->getVar('avatar','n');
		$basedir=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system').jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
		$newfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').$jieqiConfigs['system']['avatardt'];
		$smallfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').'s'.$jieqiConfigs['system']['avatardt'];
		$iconfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').'i'.$jieqiConfigs['system']['avatardt'];
		//$oldfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').'_tmp'.$jieqiConfigs['system']['avatardt'];
		$uptmp = (strlen(ini_get('upload_tmp_dir')) > 0) ? ini_get('upload_tmp_dir') : ((strlen($_ENV['TEMP']) > 0) ? $_ENV['TEMP'] : ((strlen($_ENV['TMP']) > 0) ? $_ENV['TMP'] : ((strtolower(substr(PHP_OS, 0, 3)) == 'win') ? 'C:/WINDOWS/TEMP' : '/tmp')));
		$oldfile=$uptmp.'/'.$_SESSION['jieqiUserId'].'_tmp'.$jieqiConfigs['system']['avatardt'];

		if(is_file($oldfile)){
			if($old_avatar > 0 && isset($jieqi_image_type[$old_avatar])){
				$old_imagefile=$basedir.'/'.$jieqiUsers->getVar('uid','n').$jieqi_image_type[$old_avatar];
				jieqi_delfile($old_imagefile);
			}
			$posary=explode(',', $_REQUEST['cut_pos']);
			foreach($posary as $k=>$v) $posary[$k]=intval($v);
			include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
			$imgresize = new ImageResize();
			$imgresize->load($oldfile);
			if($posary[2]>0 && $posary[3]>0) $imgresize->resize($posary[2], $posary[3]);
			$imgresize->cut($jieqiConfigs['system']['avatardw'], $jieqiConfigs['system']['avatardh'], intval($posary[0]), intval($posary[1]));
			$tmp_save = $uptmp.'/'.$_SESSION['jieqiUserId'].$jieqiConfigs['system']['avatardt'];
			$imgresize->save($tmp_save);
			jieqi_copyfile($tmp_save, $newfile, 0777, true);
			
			$imgresize->resize($jieqiConfigs['system']['avatarsw'], $jieqiConfigs['system']['avatarsh']);
			$tmp_save = $uptmp.'/'.$_SESSION['jieqiUserId'].'s'.$jieqiConfigs['system']['avatardt'];
			$imgresize->save($tmp_save);
			jieqi_copyfile($tmp_save, $smallfile, 0777, true);
			
			$imgresize->resize($jieqiConfigs['system']['avatariw'], $jieqiConfigs['system']['avatarih']);
			$tmp_save = $uptmp.'/'.$_SESSION['jieqiUserId'].'i'.$jieqiConfigs['system']['avatardt'];
			$imgresize->save($tmp_save, true);
			jieqi_copyfile($tmp_save, $iconfile, 0777, true);
			
			jieqi_delfile($oldfile);

			$image_type=0;
			$image_postfix=$jieqiConfigs['system']['avatardt'];
			foreach($jieqi_image_type as $k=>$v){
				if($image_postfix == $v){
					$image_type=$k;
					break;
				}
			}
			$old_avatar=$jieqiUsers->getVar('avatar','n');
			$jieqiUsers->unsetNew();
			$jieqiUsers->setVar('avatar',$image_type);
			if (!$users_handler->insert($jieqiUsers)) $this->printfail($jieqiLang['system']['avatar_set_failure']);
			else jieqi_jumppage(JIEQI_URL.'/setavatar.php', LANG_DO_SUCCESS, $jieqiLang['system']['avatar_set_success']);

		}else{
			$this->printfail($jieqiLang['system']['avatar_set_failure']);
		}
	}
	
	public function cutavatar(){
		jieqi_getconfigs('system', 'userblocks', 'jieqiBlocks');
		//$base_avatar = jieqi_uploadurl($jieqiConfigs['system']['avatardir'], $jieqiConfigs['system']['avatarurl'], 'system').jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
		//$url_avatar=$base_avatar.'/'.$jieqiUsers->getVar('uid','n').'_tmp'.$jieqiConfigs['system']['avatardt'].'?'.JIEQI_NOW_TIME;
		//$jieqiTpl->assign('base_avatar', $base_avatar);
		//$jieqiTpl->assign('url_avatar', $url_avatar);

		$jieqiTpl->assign('url_avatar', JIEQI_URL.'/tmpavatar.php?time='.JIEQI_NOW_TIME);
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/cutavatar.html';
	}
	
	
	/*上传图片处理*/
	public function upload(){
		global $jieqiConfigs, $jieqiLang, $jieqi_image_type;
		$_REQUEST = $this->getRequest();
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='upload'){
			jieqi_checklogin();
			$this->addLang('system', 'users');
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
			if(!$jieqiUsers) $this->printfail(LANG_NO_USER);
			jieqi_getconfigs('system', 'configs');
			
			$jieqiConfigs['system']['avatardt'] = '.jpg';
			$jieqiConfigs['system']['avatardw'] = '120';
			$jieqiConfigs['system']['avatardh'] = '120';
			$jieqiConfigs['system']['avatarsw'] = '48';
			$jieqiConfigs['system']['avatarsh'] = '48';
			$jieqiConfigs['system']['avatariw'] = '16';
			$jieqiConfigs['system']['avatarih'] = '16';
			
			//上传头像
			$errtext='';
			if (empty($_FILES['avatarimage']['name'])) $errtext .= $jieqiLang['system']['need_avatar_image'].'<br />';
	
			//检查格式及大小
			$image_postfix='';
			if (!empty($_FILES['avatarimage']['name'])){
				if($_FILES['avatarimage']['error'] > 0) $errtext = $jieqiLang['system']['avatar_upload_failure'];
				else{
					$image_postfix = strrchr(trim(strtolower($_FILES['avatarimage']['name'])),".");
					if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['avatarimage']['name'])){
						$typeary=explode(' ',trim($jieqiConfigs['system']['avatartype']));
						foreach($typeary as $k=>$v){
							if(substr($v,0,1) != '.') $typeary[$k]='.'.$typeary[$k];
						}
						if(!in_array($image_postfix, $typeary)) $errtext .= sprintf($jieqiLang['system']['avatar_type_error'], $jieqiConfigs['system']['avatartype']).'<br />';
	
						if($_FILES['avatarimage']['size'] > (intval($jieqiConfigs['system']['avatarsize']) * 1024)) $errtext .=sprintf($jieqiLang['system']['avatar_filesize_toolarge'], intval($jieqiConfigs['system']['avatarsize'])).'<br />';
	
					}else{
						$errtext .= sprintf($jieqiLang['system']['avatar_not_image'], $_FILES['avatarimage']['name']).'<br />';
					}
					if(!empty($errtext)) jieqi_delfile($_FILES['avatarimage']['tmp_name']);
				}
			}else{
				$errtext = $jieqiLang['system']['avatar_need_upload'];
			}
			//更新头像
			if(empty($errtext)) {$jieqiConfigs['system']['avatarcut'] = 0;
				if(function_exists('gd_info') && $jieqiConfigs['system']['avatarcut']){
					//上传后需要裁剪
					//保存临时图片
					if (!empty($_FILES['avatarimage']['name'])){
						//$imagefile=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system');
						//if (!file_exists($retdir)) jieqi_createdir($imagefile);
						//$imagefile.=jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
						//if (!file_exists($retdir)) jieqi_createdir($imagefile);
	
						$tmpfile = dirname($_FILES['avatarimage']['tmp_name']).'/tmp_'.$_FILES['avatarimage']['name'];
	
						//$tmpfile=$imagefile.'/tmp_'.$_FILES['avatarimage']['name'];
						@move_uploaded_file($_FILES['avatarimage']['tmp_name'], $tmpfile);
						//默认转换成jpg
						$imagefile=dirname($_FILES['avatarimage']['tmp_name']).'/'.$jieqiUsers->getVar('uid','n').'_tmp'.$jieqiConfigs['system']['avatardt'];
						include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
						$imgresize = new ImageResize();
						$imgresize->load($tmpfile);//echo $imagefile;exit;
						$imgresize->save($imagefile, true, substr(strrchr(trim(strtolower($imagefile)),"."), 1));
						@chmod($imagefile, 0777);
						jieqi_delfile($tmpfile);
					}
					header('Location: '.$this->geturl('system', 'setavatar'));
				}else{
					//直接保存
					$image_type=0;
					foreach($jieqi_image_type as $k=>$v){
						if($image_postfix == $v){
							$image_type=$k;
							break;
						}
					}
					$old_avatar=$jieqiUsers->getVar('avatar','n');
					$jieqiUsers->unsetNew();
					$jieqiUsers->setVar('avatar',$image_type);
					if (!$users_handler->insert($jieqiUsers)) $this->printfail($jieqiLang['system']['avatar_set_failure']);
					else {
						//<!--jieqi insert license check-->
						//保存图片
						if (!empty($_FILES['avatarimage']['name'])){
							$imagefile=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system');
							if (!file_exists($retdir)) jieqi_createdir($imagefile);
							$imagefile.=jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
							if (!file_exists($retdir)) jieqi_createdir($imagefile);
							if($old_avatar > 0 && isset($jieqi_image_type[$old_avatar])){
								$old_imagefile=$imagefile.'/'.$jieqiUsers->getVar('uid','n').$jieqi_image_type[$old_avatar];
								if(is_file($old_imagefile)) jieqi_delfile($old_imagefile);
							}
							$imagefile.='/'.$jieqiUsers->getVar('uid','n').$image_postfix;
							jieqi_copyfile($_FILES['avatarimage']['tmp_name'], $imagefile, 0777, true);
						}
						jieqi_jumppage($this->geturl('system', 'setavatar'), LANG_DO_SUCCESS, $jieqiLang['system']['avatar_set_success']);
					}
				}
			} else {
				$this->printfail($errtext);
			}
		}
	}
	
	/**
	 * 上传头像视图
	 * @return multitype:NULL number unknown
	 */
	public function setavatarView(){
		$auth = $this->getAuth ();
		$users_handler = $this->getUserObject ();
		$jieqiUsers = $users_handler->get ( $auth ['uid'] );
// 		global $jieqiConfigs, $jieqiLang, $jieqi_image_type;
// 		$this->addLang('system', 'users');
		jieqi_getconfigs('system', 'configs');
		//new avatar
		
// 		$jieqiConfigs['system']['avatardt'] = '.jpg';
// 		$jieqiConfigs['system']['avatardw'] = '120';
// 		$jieqiConfigs['system']['avatardh'] = '120';
// 		$jieqiConfigs['system']['avatarsw'] = '48';
// 		$jieqiConfigs['system']['avatarsh'] = '48';
// 		$jieqiConfigs['system']['avatariw'] = '16';
// 		$jieqiConfigs['system']['avatarih'] = '16';

		
		$data = array();
		//显示头像状态，包含区块参数(定制区块)
// 		jieqi_getconfigs('system', 'userblocks', 'jieqiBlocks');

		$avatar=intval($jieqiUsers->getVar('avatar','n'));//是否设置了头像 1设置 0未设置
// 		$avatarimg='';
// 		if(isset($jieqi_image_type[$avatartype])){
// 			//$urls = $this->geturl('system','avatar','method=articleManage','id='.$id);
// 			$urls = jieqi_geturl('system', 'avatar', $jieqiUsers->getVar('uid','n'), 'a', $avatartype);
// 			if(is_array($urls)){
// 				$data['base_avatar'] = $urls['d'];
// 				$data['url_avatar'] = $urls['l'];
// 				$data['url_avatars'] = $urls['s'];
// 				$data['url_avatari'] = $urls['i'];
// 			}
// 		}
// 		$data['avatartype'] = $avatartype;
// 		$data['need_imagetype'] = $jieqiConfigs['system']['avatartype'];
// 		$data['max_imagesize'] = $jieqiConfigs['system']['avatarsize'];
// // 		$data['avatartype'] = $avatartype;

// 		if(function_exists('gd_info') && $jieqiConfigs['system']['avatarcut']) $data['avatarcut'] = 1;
// 		else $data['avatarcut'] = 0;
		//如果用户设置了头像，默认头像使用此头像
		//没有设置则使用默认头像
		if($avatar){
			$data['avatardefault'] =jieqi_geturl('system','avatar',$auth['uid'],'o',2).'?p='.jieqi_random(3);
		}else{
			$data['avatardefault'] = JIEQI_LOCAL_URL.'/images/noavatarl.jpg';
		}
		$data['avatarswf'] = JIEQI_LOCAL_URL.'/images/avatar.swf';
		
		return $data;
	}
} 
?>