var Users = {
    msgStyle: 'background-color:#333; color:#fff; text-align:center; border:none; font-size:20px; padding:10px;',
   
    /**
     * 注册会员_提交表单  
     * @param {[type]} form_id [description]
     */
    RegUserForm: function (form_id) {  //  
        Users.form=document.getElementById(form_id);
        Users.msg_err=false;
        Users.checkUser('username');
        Users.checkPassword('password');
        Users.checkCheckcode('checkcode');
        Users.checkMsgcode('msgcode');
        var q_sub=$("#btnSendSms");
 
        if( Users.msg_err !== false ){
            Users.ShowMsg();
            return false; 
        }
        q_sub.attr("disabled", "disabled");
        Users.SendForm();
        q_sub.removeAttr("disabled"); 
    },
      
    /**
     * 注册会员_发短信
     * @param {[type]} form_id [description]
     */
    RegSendSms: function (form_id) {
        var username,checkcode;
        Users.form=document.getElementById(form_id);
        Users.msg_err=false;
        Users.checkUser('username');
        Users.checkPassword('password');
        Users.checkCheckcode('checkcode');
        if( Users.msg_err !== false ){
            Users.ShowMsg();
            return false;
        }
        if (Users.se > 0)return false;

        $.ajax({ // jq  
            url:$(Users.form).attr('_m'), 
            type:'post',
            async: false,
            data:$(Users.form).serialize(),
            dataType:'json',
            success:function(msgs){ 
                // var q_img;
                if(msgs.status == 'OK'){ 
                    Users.se=60;
                    Users.q_btnv= $("#btnSendSms");
                    Users.q_btnv.attr("disabled", "disabled");
                    $('#checkcodeimg').click(); 
                    Users.RegSmsWait();
                }else{
                    Users.ShowMsg(msgs.msg); 
                }
            }
        });
    },



    /**
     * 修改密码_提交表单
     * @param {[type]} form_id [description]
     */
    GetPassSendSms: function (form_id) { 
        var q_btn,q_sub;
        Users.form=document.getElementById(form_id);
        Users.msg_err=false;
        Users.checkUser('username');
        Users.checkPassword('password');
        Users.checkCheckcode('checkcode');
        Users.checkMsgcode('msgcode');
             
        if( Users.msg_err !== false ){
            Users.ShowMsg();
            return false;
        }

        q_btn=$("#btnSendSms");
        q_sub=$("#submitg");
        q_btn.attr("disabled", "disabled");
        q_sub.attr("disabled", "disabled");
        Users.SendForm();
        q_btn.removeAttr("disabled");
        q_sub.removeAttr("disabled");

    },

    /**
     * 修改密码_发短信
     * @param {[type]} form_id [description]
     */
    GetSendSms: function (form_id) { 
        var username,checkcode;
        Users.form=document.getElementById(form_id);
        Users.msg_err=false;
        Users.checkUser('username');
        Users.checkPassword('password');
        Users.checkCheckcode('checkcode');
        if( Users.msg_err !== false ){
            Users.ShowMsg();
            return false;
        }
        if (Users.se > 0)return false;

        $.ajax({ // jq  
            url:$(Users.form).attr('_m'), 
            type:'post',
            async: false,
            data:$(Users.form).serialize(),
            dataType:'json',
            success:function(msgs){ 
                // var q_img;
                if(msgs.status == 'OK'){ 
                    Users.se=60;
                    Users.q_btnv= $("#btnSendSms");
                    Users.q_btnv.attr("disabled", "disabled");
                    // q_img=$('#checkcodeimg');
                    // q_img.prop('src',q_img.prop('src')+'?rand='+Math.random());
                    $('#checkcodeimg').click();
                    Users.RegSmsWait();
                }else{
                    Users.ShowMsg(msgs.msg); 
                }
            }
        });
    },



    /**
     * 绑定手机_提交表单
     * @param {[type]} form_id [description]
     */
    GetBindphoneForm: function (form_id) { 
        var q_btn,q_sub;
        Users.form=document.getElementById(form_id);
        Users.msg_err=false;
        Users.checkUser('username');
        Users.checkCheckcode('checkcode');
        Users.checkMsgcode('msgcode');
             
        if( Users.msg_err !== false ){
            Users.ShowMsg();
            return false;
        }

        q_btn=$("#btnSendSms");
        q_sub=$("#submitg");
        q_btn.attr("disabled", "disabled");
        q_sub.attr("disabled", "disabled");
        Users.SendForm(
            function(msgs){                 
                if(msgs.status =='OK'){
                    layer.open({
                        title: [
                          '绑定提示',
                          'background-color: #FF4351; color:#fff;'
                        ],
                        shadeClose: false,
                        // content: '绑定手机成功，您已获得<span style="color:red"> 100 </span>书券。<br />获取更多书券!。',
                        content: '绑定手机成功，请记住您的绑定手机。',
                        btn: ['返回个人中心'] ,
                        yes: function(index){
                            location.href='/user';
                            // location.href='/user/followWeChat';
                            layer.close(index);
                        },
                        close:function(index){
                            location.href=msgs.url;
                            layer.close(index);                                 
                        }
                    });

                    // <a class="layui-layer-ico layui-layer-close layui-layer-close1" href="javascript:;"></a>
                }else{
                    Users.ShowMsg(msgs.msg);
                }
            }
        );
        q_btn.removeAttr("disabled");
        q_sub.removeAttr("disabled");

    },
    /**
     * 绑定手机_发短信
     * @param {[type]} form_id [description]
     */
    GetBindPhoneSendSms: function (form_id) { 
        var username,checkcode;
        Users.form=document.getElementById(form_id);
        Users.msg_err=false;
        Users.checkUser('username');
        Users.checkCheckcode('checkcode');
        if( Users.msg_err !== false ){
            Users.ShowMsg();
            return false;
        }
        if (Users.se > 0)return false;

        $.ajax({ // jq  
            url:$(Users.form).attr('_m'),   
            type:'post',
            async: false,
            data:$(Users.form).serialize(),
            dataType:'json',
            success:function(msgs){ 
                // var q_img;
                if(msgs.status == 'OK'){ 
                    Users.se=60;
                    Users.q_btnv= $("#btnSendSms");
                    Users.q_btnv.attr("disabled", "disabled");
                    // q_img=$('#checkcodeimg');
                    // q_img.prop('src',q_img.prop('src')+'?rand='+Math.random());
                    $('#checkcodeimg').click();
                    Users.RegSmsWait();
                }else{
                    Users.ShowMsg(msgs.msg); 
                }
            }
        });
    },




    /**
     * 发送倒计时
     */
    RegSmsWait: function () {
        if (Users.se > 0) {
            Users.q_btnv.val("重新发送(" + Users.se + ")");
            Users.se--;
            setTimeout(Users.RegSmsWait, 1000);
        }else {
            Users.q_btnv.val("重新获取验证码");
            Users.q_btnv.removeAttr("disabled");
        }
    },


    /**
     * 表单提示
     * @param {[type]} msg [要提示的信息]
     */
    ShowMsg:function(msg){
        msg=msg ? msg:Users.msg_err;             
        layer.open({
            content: msg,
            style: Users.msgStyle,
            time: 2
        });
    },

    /**
     * 异步提交表单
     * @param {Function} fn [description]
     */
    SendForm: function (fn) {
        fn=fn ? fn:function(msgs){                 
            if(msgs.status =='OK'){
                location.href=Users.form.elements.jumpurl_u.value;
            }else{
                Users.ShowMsg(msgs.msg);
            }
        };
        $.ajax({ // jq  
            url:$(Users.form).prop('action'),
            type:'post',
            async: false,
            data:$(Users.form).serialize(),
            dataType:'json',
            success:fn
        });
    },



    checkUserName: function (name) {
        var v;
        if(Users.msg_err)return false;
        v  = Users.form.elements[name].value;
        if ( v.length < 2 ){
            Users.msg_err ='昵称小于2个字符！';
        }else if(v.length > 25){
            Users.msg_err ='昵称大于25个字符！';
        }
    },

    checkUser: function (name) {
        var v,reg_phone;
        if(Users.msg_err)return false;
        v  = Users.form.elements[name].value;
        reg_phone =/(^(13[0-9]|14[57]|15[012356789]|17[678]|18[0-9])\d{8}$)|(^170[059]\d{7}$)/;
        if ( v.length < 1){
            Users.msg_err ='请输入手机号码！';
        }else if(!(reg_phone.test(v))){
            Users.msg_err ='手机号码不正确！';
        }
    },
    checkPassword: function (name) {
        var v;
        if(Users.msg_err)return false;
        v  = Users.form.elements[name].value;
        if ( v.length < 2 ){
            Users.msg_err ='密码小于2位数！';
        }
    },

    checkCheckcode: function (name) {
        var v;
        if(Users.msg_err)return false;
        v = Users.form.elements[name].value;
        if ( v.length < 3 ){
            Users.msg_err ='验证码小于3位数！';
        }
    },
    checkMsgcode: function (name) {
        var v;
        if(Users.msg_err)return false;
        v = Users.form.elements[name].value;
        if ( v.length !== 6){
            Users.msg_err ='短信验证码位数不正确！';
        }else if(!(/^\d{6}$/.test(v))){
            Users.msg_err ='短信验证码不正确！';
        }
    }
 
};

if( readCookie('bindMobile') && $('meta[http-equiv=refresh]').size() === 0 ){
    // showBindMess();
    eraseCookie('bindMobile');
    eraseCookie('bindMobile',';domain='+location.host.replace(/(\w*\.)?(\w+\.\w+)/,'$2'));
}

if( readCookie('weHistoryChe') && $('meta[http-equiv=refresh]').size() === 0 ){
    weHistoryChe(); 
}

/**
 * weHistoryChe
 * @return {[type]} [description]
 */
function weHistoryChe(){
    $.ajax({      
        type:"POST",      
        dataType:"json",      
        url:'http://'+location.host+'/user/weHistoryChe',      
        timeout:4000,     
        data:{ajax_request:'1'},      
        success:function(data,textStatus){     
            if(data.status === 3 ){
                layer.open({
                    content: data.message,btn: ['立即查看','稍后查看'] ,yes: function(index){
                        location.href='http://'+location.host+'/user/';
                        layer.close(index);
                    }
                });
                eraseCookie('weHistoryChe');
                eraseCookie('weHistoryChe',';domain='+location.host.replace(/(\w*\.)?(\w+\.\w+)/,'$2'));
            }else if(data.status  === 1 ){
                weHistoryChe();
            }else{
                eraseCookie('weHistoryChe');
                eraseCookie('weHistoryChe',';domain='+location.host.replace(/(\w*\.)?(\w+\.\w+)/,'$2'));
            }
        },           
         error:function(XMLHttpRequest,textStatus,errorThrown){      
             if(textStatus=="timeout"){      
                weHistoryChe();
             }      
         }      
    });
}



/**
 * 提示会员绑定手机
 * @return {[type]} [description]
 */
function showBindMess(){
    layer.open({
        content: '您还没有绑定密码找回手机,<br /> 完成手机绑定后您将获得<span style="color:red">100</span>书卷,<br /> 现在绑定!',btn: ['现在绑定','下次再说'] ,yes: function(index){
            location.href='http://'+location.host+'/user/bindPhone';
            layer.close(index);
        }
    });
} 


/**
 * 创建一个cookie
 * @param  {[type]} name  [description]
 * @param  {[type]} value [description]
 * @param  {[type]} days  [description]
 * @return {[type]}       [description]
 */
function createCookie(name, value, days,domnai) {
    var expires,date;
    if (days) {
        date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }else{
        expires = "";
    }
    domnai=domnai||'';         
    document.cookie = name+"="+value+expires+domnai+";path=/";//domain=.chanel.com;path=/
} 
/**
 * 读取一个cookie
 * @param  {[type]} name [description]
 * @return {[type]}      [description]
 */
function readCookie(name) {
    var nameEQ,ca,i,c;
    nameEQ = name + "=";
    ca = document.cookie.split(';');
    for(i=0;i < ca.length;i++) {
        c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
//eraseCookie('compare');
//eraseCookie('selecook');
/**
 * 删除一个cookie
 * @param  {[type]} name [description]
 * @return {[type]}      [description]
 */ 
function eraseCookie(name,domnai) {
    domnai=domnai||'';
    createCookie(name, "", -1,domnai);
}