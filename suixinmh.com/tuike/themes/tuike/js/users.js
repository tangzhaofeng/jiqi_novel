
window.isChromeApp = window.chrome && window.chrome.storage ? !0 : !1;
window.Store = function() {var t = {}; if (isChromeApp) {e = !1; return {set: function(i, r) {t[i] = r, e || (e = !0, setTimeout(function() {e = !1; try {chrome.storage.sync.set(t, function() {}); } catch(i) {console.log("Failed to save :("); } }, 3e3)); }, get: function(e, i) {return t[e] ? t[e] : void chrome.storage.sync.get(e, function(e) {for (var r in e) t[r] = e[r]; i && i(); }); }, remove: function(e) {delete t[e], chrome.storage.sync.remove(e, function(t) {}); } }; } i = function() {try {return "localStorage" in window && null !== window.localStorage; } catch(t) {return ! 1; } } (); return i ? {set: function(e, i) {t[e] = i; try {localStorage.setItem(e, i); } catch(r) {} }, get: function(e, i) {return i && i(), t[e] ? t[e] : localStorage.getItem(e); }, remove: function(t) {return localStorage.removeItem(t); } }: {set: function() {}, get: function(t, e) {e && e(); }, remove: function() {} }; } ();

/**
 * 获取路径
 * @param  {[type]} arr [description]
 * @return {[type]}     [description]
 */
function getUrl(arr,start,end){  // js
  var ar=location.pathname.split('/');
  start=start || 1;
  end=end || 2;
  ar= ar.slice(start, end);
  Array.prototype.push.apply(ar, arr); 
  return 'http://'+location.host+'/'+ar.join("/");
}


var Users = {
  msgStyle: 'background-color:#333; color:#fff; text-align:center; border:none; font-size:20px; padding:10px;',
   
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
  ShowMsg:function(msg,t){
    t=t || 2;
    msg=msg ? msg:Users.msg_err;            
    layer.open({
      content: msg,
      style: Users.msgStyle,
      time: t
    });
  },

  // 提交表单
  FormSubmit:function(e){
    var da=$(this).data("da");
    e.preventDefault();
    Users.form=this;
    Users.msg_err=false;
    if(da.fn_f)da.fn_f();
    if( Users.checkeError()) return false;
    da.fn=da.fn || function(msg){                 
      if(msg.status === 'OK'){
        location.href=msg.jumpurl;
      }else{
        Users.ShowMsg(msg.msg);
      }
    };
    Users.SendForm(da.fn);
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

  checkeError: function () {
     if( Users.msg_err !== false ){
        Users.ShowMsg();
        return true;
      }
      return false;
  },

  checkempty: function (name,msg) {
    var v;
    if(Users.msg_err)return false;
    msg=msg || '请输入内容！';
    v  = Users.form.elements[name].value;
    if ( v.length < 2 ){
      Users.msg_err =msg;
    }

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
    reg_phone =/(^(13[0-9]|14[57]|15[012356789]|17[\d]|18[0-9])\d{8}$)|(^170[059]\d{7}$)/;
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

  checkCheckcode: function (name,l) {
    var v;
    l=l||3;
    if(Users.msg_err)return false;
    v = Users.form.elements[name].value;
    if ( v.length < l ){
      Users.msg_err ='验证码小于'+l+'位数！';
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


/*---列表页js 开始---------------------------------------------------------------------------*/
$(function(){
  if(typeof q_main==="undefined" )return false;
  if(typeof GLO_D==="undefined"  )window.GLO_D={"filter":{}};
  // 修改内容
  q_main.find('.fieldRev').live('click',fieldRevFn);
  q_main.find('.fieldRev').each(function(){$(this).prop('title','点击修改内容'); });

});

// 修改文字
function fieldRevFn(){
  var nu=25;
  var q_input=$("<input type='text'></allEles>"), q_this=$(this),o_v,v,url,q_par;
  q_input.css({
    'width':q_this.width()+nu,
    'height':q_this.height(),
    'padding':'9px 0',
    'font-weight':q_this.css('font-weight')
  });
  o_v=q_this.html();
  q_par=q_this.parent();
  q_par.css('width',parseInt(q_par.css('width'))+1);
  q_this.after(q_input).hide();
  q_input.val((o_v==='(无)'?'':o_v)).select().blur(function(){
    v=q_input.val();
    q_par.css('width','auto');
    q_input.remove();
    if(v.length > 1 && v != o_v && v != '(无)'){
      q_this.html(v).show();
      GLO_D.filter.field_v=v; 
      post_fieldRev(q_this,function(data){
        if(data.status !== 'OK'){
          q_this.html(o_v);
          Users.ShowMsg(data.msg);
        }
      });
    }else{
      q_this.show();
    }
  });
}

// 提交修改和处理
function post_fieldRev(q_this,fun){
  GLO_D.filter.field_y=q_this.attr('_y');
  GLO_D.filter.field_i=q_this.parents('tr').attr('_i');
  $.post(GLO_D.fieldRev_url,$.extend(GLO_D.filter,{ac:'setPa','ajax_request':1}),fun,'json'); // jq
}



/*----元旦活动----------------------------------------------------------------------------------------------------------------------*/
 

if( !Store.get("new_ear_day_show") ){
  $(function(){
    if( location.href.search(/\/login/i) !== -1 )return false;
    html=' <div class="hide_box" id="new_ear_day_box" style="display: none; margin: 0px;width: 350px;"> <h4 style="margin:0;"><a href="javascript:void(0)" title="x" id="new_ear_day_close">×</a>【坐享分成】年度返利活动正式上线!</h4><a href="/help/notes?id=3"><img id="new_ear_day_img" style="max-width: 350px;" src="/themes/tuike/img/new_ear_day.jpg" alt=""></a></div>';
    $('body').append(html);
    easyDialog.open({
      "container": 'new_ear_day_box',
    });

    showFunc();
    function showFunc(){ // jq
      if( $('#new_ear_day_img:visible').size() ===0 ){             
        setTimeout(showFunc,10);
        return false;
      }

      var h=document.documentElement.clientHeight;//
      var h_img=$('#new_ear_day_img').height();

      if( h-h_img < 23 ){
        $('#new_ear_day_img').css('maxHeight',h-23);
        $('#new_ear_day_box').css('maxWidth',$('#new_ear_day_img').width());
      }
      var h1=-$('#dialog_box').outerHeight(true)/2;
      var h2=-$('#dialog_box').height()/2;
      h=h1<h2?h1:h2;
      $('#dialog_box').css('marginTop',h );

      $('#new_ear_day_close').click(function() {             
        easyDialog.close();
      });
    }
  });
  Store.set("new_ear_day_show", 1 );
}

/*----元旦活动----------------------------------------------------------------------------------------------------------------------*/
 


/*----春节通知----------------------------------------------------------------------------------------------------------------------*/
if( !Store.get("new_year_day_show") ){
    $(function(){
      if( location.href.search(/\/login/i) !== -1 )return false;
      var str='<div class="hide_box" id="to_alert" style="display: none; margin: 0px;box-shadow: 0 0 5px #ddd;">'+
            '<h4 style="text-align: center;height: 40px;line-height: 40px;font-size: 20px;"><a href="javascript:void(0)" title="x" id="close_alert">×</a>春节长假优阅云提现公告</h4>'+
            '<p style="padding-bottom: 0;">尊敬的客户：</p>'+
            '<p style=" padding-bottom: 0; text-indent: 2em;">您好!</p>'+
            '<p style=" text-indent: 2em;">自2017年01月23日起优阅云分成平台推广收益提现时间因传统春节长假关系暂停提现服务，2月6日开始恢复提现。平台其余功能正常运转。给您带来的不便，敬请谅解！优阅云向各位合作伙伴拜个早年！预祝2017赚盆满钵满！</p>'+
            '<p >以上敬请知悉！ 谢谢！</p>'+
            '<p style="text-align: right;">优阅网络科技股份有限公司</p>'+
            '<p style="text-align: right;padding-right: 37px;">2017年01月17日</p>'+
            '<div style="margin: 10px auto;width: 13%;">'+
            '<button class="u-btn u-btn-primary" id="btn-know">知道了</button>'+ 
            '</div>'+
        '</div>';
      $('body').append(str);
      easyDialog.open({container: 'to_alert'});
      $('#close_alert').click(function() {
        easyDialog.close();
      });
      $('#btn-know').click(function() {
       easyDialog.close();
      });
      $('#overlay').live('click',function(){
       easyDialog.close();
      });
  });
  Store.set("new_year_day_show", 1 );
}
/*----春节通知----------------------------------------------------------------------------------------------------------------------*/


 
/*----春节通知-小说网活动-----------------------------------------------------------------------------------------------------------*/
if( !Store.get("new_year_day_show_w") ){
    $(function(){
      if( location.href.search(/\/login/i) !== -1 )return false;
      var str='<div class="hide_box" id="to_alert" style="display: none; margin: 0px;box-shadow: 0 0 5px #ddd;">'+
            '<h4 style="text-align: center;height: 40px;line-height: 40px;font-size: 20px;"><a href="javascript:void(0)" title="x" id="close_alert">×</a>优阅小说充值活动上线通知</h4>'+
            '<p style="padding-bottom: 0;">尊敬的客户：</p>'+
            '<p style=" padding-bottom: 0; text-indent: 2em;">您好!</p>'+
            '<p style=" text-indent: 2em;">各位流量主知悉，优阅小说网将在春节期间上线粉丝看小说充值优惠活动，于除夕至初六总共7天的活动时间（活动详情到公告处详看）。据往常活动期间时充值收入会大大提升，推广收益大故提前通知，建议各位合作伙伴适时安排推广，收入翻倍！</p>'+
            '<p style="text-align: right;">优阅网络科技股份有限公司</p>'+
            '<p style="text-align: right;padding-right: 37px;">2017年01月20日</p>'+
            '<div style="margin: 10px auto;width: 13%;">'+
            '<button class="u-btn u-btn-primary" id="btn-know">知道了</button>'+ 
            '</div>'+
        '</div>';
      $('body').append(str);
      easyDialog.open({container: 'to_alert'});
      $('#close_alert').click(function() {
        easyDialog.close();
      });
      $('#btn-know').click(function() {
       easyDialog.close();
      });
      $('#overlay').live('click',function(){
       easyDialog.close();
      });
  });
  Store.set("new_year_day_show_w", 1 );
}
/*----春节通知-小说网活动-----------------------------------------------------------------------------------------------------------*/
 
 


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