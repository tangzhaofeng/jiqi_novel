<?php
$this->_tpl_vars['jieqi_pagetitle'] = "�û���½-{$this->_tpl_vars['jieqi_sitename']}";
echo '<link rel="stylesheet" rev="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'style/login.css" type="text/css" media="all" />
<link rel="stylesheet" href="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.css" />
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/jquery.validator.js"></script>
<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/validator-0.7.0/local/zh_CN.js"></script>
<div class="box_mid fix">
  <div class="login">
   <h3>�û���¼</h3>
   <div id="result_14" class="tip-ok" style="display:none">��¼�ɹ�</div>
<form id="login_form" class="signup" action="'.$this->_tpl_vars['url_login'].'" method="post" autocomplete="off">
<fieldset>
    <div class="form-item">
        <div class="field-name">�û���:</div>
        <div class="field-input">
          <input type="text" maxlength="30" name="username" data-rule="�û���:required"/>
        </div>
    </div>
    <div class="form-item">
        <div class="field-name">����:</div>
        <div class="field-input">
          <input type="password" name="password" autocomplete="off" data-rule="����:required;length[3~]" />
        </div>
    </div>
    <div class="form-item">
        <div class="field-name">��֤��:</div>
        <div class="field-input">
          <input type="text" name="checkcode" class="yzm" maxlength="6" autocomplete=��off��/>
          <img src="'.$this->_tpl_vars['url_checkcode'].'" class="pic" id="checkcode" /><a id="recode" class="f_org2 pl10" href="javascript:;">��һ��</a>
          <p><input name="usecookie" type="checkbox" value="1" checked="checked" class="check" />��ס��(1�������¼)</p>
        </div>
    </div>
</fieldset>';
 $this->_tpl_vars['url'] = JIEQI_LOCAL_URL;  
echo '
    <input type="hidden" name="jumpurl"  id="jumpurl" value="'.urlencode($this->_tpl_vars['jumpurl']).'">
    <input type="hidden" name="formhash" value="';
echo form_hash(); 
echo '">
<button id="btn-submit" class="btn-submit2" type="submit">��¼</button>
<p class="snback f_blue"><br />�������룿���<a href="'.geturl('system','getpass').'" title="�һ�����">�һ�����</a></p>
</form>
<script type="text/javascript">
layer.ready(function(){
    $("#qqlogin").live("click",function(){
		otherlogin("'.$this->_tpl_vars['url'].'/qqlogin/?jumpurl="+$("#jumpurl").val());
	});
	$(\'#login_form\').bind(\'valid.form\', function(event){
		event.preventDefault();
		$("#btn-submit").attr("disabled", "disabled");
		$(\'#btn-submit\').html(\'���ڽ�����...\');
		  GPage.postForm(\'login_form\', this.action,
			   function(data){
					if(data.status==\'OK\'){
						jumpurl(data.jumpurl);
					}else{
					    $("#btn-submit").attr("disabled", false);
						$(\'#btn-submit\').html(\'��¼\');
						$(\'#result_14\').html(data.msg).fadeIn(300).delay(2000).fadeOut(1000);
						if(data.msg == \'�Բ���У�������\'){
							$("[name=\'checkcode\']").focus();
							$(\'#recode\').click();
						}else if(data.msg == \'���������ע����ĸ��Сд�Ƿ�������ȷ����\'){
							$("[name=\'password\']").focus();
						}else if(data.msg ==\'���û������ڣ���ע����ĸ��Сд�Ƿ�������ȷ��\'){
							$("[name=\'username\']").focus();
						}
					}
			   }
		  );
	});
});
$(\'#recode\').click(function(){
	$(\'#checkcode\').attr(\'src\',\''.$this->_tpl_vars['url_checkcode'].'?rand=\'+Math.random());
});
</script>
  </div>
  <div class="lother">
   <h3>�û�ע��</h3>
   ��û��'.$this->_tpl_vars['jieqi_sitename'].'�˺ţ�
   <a href="'.geturl('system','register').'"  title="����ע��" class="reg"></a>
   ��Ҳ������վ���˺ŵ�¼:
    <p class="o_login"><a href="javascript:;" id="qqlogin" title="��ѶQQ" class="qq"></a><!---<a href="javascript:alert(\'�����ڴ�...\')"  title="����΢��" class="sina"></a>--></p>
  </div>
</div>';
?>