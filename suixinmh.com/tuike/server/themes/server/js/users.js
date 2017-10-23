/*


 */


/**
 * 原型：字符串格式化
 * @param args 格式化参数值
 */
String.prototype.replaceAll = function (exp, newStr) {
    return this.replace(new RegExp(exp, "gm"), newStr);
};
String.prototype.format = function(args) {
    var result = this;
    if (arguments.length < 1){
        return result;
    }
    var data = arguments; // 如果模板参数是数组
    if (arguments.length == 1 && typeof (args) == "object"){
        // 如果模板参数是对象
        data = args;
    }
    for ( var key in data) {
        var value = data[key];
        if (undefined !== value){
            result = result.replaceAll("\\{" + key + "\\}", value);
        }
    }
    return result;
};


var Users = {
    msgStyle: 'background-color:#333; color:#fff; text-align:center; border:none; font-size:20px; padding:10px;',
    /**
     * 发送倒计时
     */
    RegSmsWait: function () {
        if (Users.se > 0){
            Users.q_btnv.val("重新发送(" + Users.se + ")");
            Users.se--;
            setTimeout(Users.RegSmsWait, 1000);
        }else{
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
    checkemptyAr: function (name,msg,fn) {
        var v,tmp,i;
        fn=fn ? fn:function(v){   
          return v.length < 2?false:true;
        };
        if(Users.msg_err)return false;
        msg=msg || '请输入内容！';
        tmp=Users.form.elements[name];
        if( !tmp.length )tmp=[tmp];
        for (i = tmp.length - 1; i >= 0; i--) {
          if ( !fn(tmp[i].value) ){
            Users.msg_err =msg;
            break;
          }
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

// 统一的异步提示
$(document).ajaxSend(function(evt, request, settings){
  $("#loading").html('<img src="'+baseImgUrl+'/loading.gif" />');
});
$(document).ajaxComplete(function(evt, request, settings){
  $("#loading").empty();
});

/**
 * 初始化和设置时间
 * @param  {[type]} q_this [description]
 * @return {[type]}        [description]
 */
function initDateFormat(q_this,start,end,fn,text,show){
  text=text || '{0} 至 {1}';

  if ( !q_this.data('daterangepicker') ){
    q_this.daterangepicker({
      "startDate":start ,
      "endDate": end ,
      locale: {
        format: 'YYYY-MM-DD',
        applyLabel: '确定',
        cancelLabel: '取消',
        fromLabel: '起始时间',
        toLabel: '结束时间',
        customRangeLabel: '自定义',
        daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
        monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
        firstDay: 1
      },
    }).on('hide.daterangepicker',function(ev, picker) {                 
      var stf =picker.startDate.format('YYYY-MM-DD');
      var endf = picker.endDate.format('YYYY-MM-DD');
      var q_this=$(this);
           
      if(typeof fn === "function" )fn({"t1":stf,"t2":endf,"q_this":q_this});
      if( q_this.prop('id') === 'config-table' ){
        getAjaxWechatDay({t1:stf,t2:endf});
      }else if( q_this.prop('id') === 'config-list' ){
        getWechatListShow({t1:stf,t2:endf});
      }
      q_this.html( text.format(stf,endf) );
    });
  }
  if( show!==false ){
    q_this.data('daterangepicker').setStartDate(start);
    q_this.data('daterangepicker').setEndDate(end);
    q_this.html( text.format(start,end) );
  }
}


/*---列表页js 开始---------------------------------------------------------------------------*/
$(function(){
  if(typeof(q_main) == "undefined" )return false;
  show_sort_order_img();
  // 异步分页
  q_main.find('.m-page a').live('click',function(){
    var stL,href=$(this).attr('href');
    if( href.length < 8 )return false;
    stL=href.indexOf('page=')+5;
    filter.page=parseInt(href.slice(stL,stL+4));
    get_content(location.href);
    return false;
  });
  // 触发排序和执行
  q_main.find('.sortOr').live('click',function(){
    var q_this=$(this),z=q_this.attr('_z');
    filter.sort=z!==filter.order?'ASC': (filter.sort === 'ASC'?'DESC':'ASC');
    filter.order=z;
    filter.page=1;
    get_content(location.href);
  });
  // 修改内容
  q_main.find('.fieldRev').live('click',function(){
    var nu=25;
    var q_input=$("<input type='text'></allEles>"), q_this=$(this),o_v,v,url,q_par;
    q_input.css({
      'width':q_this.width()+nu,
      'height':q_this.height(),
      'font-size':q_this.css('font-size'),
      'font-weight':q_this.css('font-weight'),
      'color':q_this.css('color')
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
        filter.field_v=v; 
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
  });

});




/**
 * 与指定时间相隔num天的日期
 * @param  {[type]} num    [description]
 * @param  {[type]} format [description]
 * @param  {[type]} date   [description]
 * @return {[type]}        [description]
 */
function getFormatDateAuto(num,format,date){
  if( !date )date = new Date();
  date.setDate(date.getDate() + num);
  return formatDate(format,date);
}

/**
 * 格式化日期
 * @param  {[type]} format [description]
 * @param  {[type]} date   [description]
 * @return {[type]}        [description]
 */
function formatDate(format,date){
  if( !date )date = new Date();
  var paddNum = function(num){
  return num>9?num:'0'+num;
  };
  //指定格式字符
  var cfg = {
  yyyy : date.getFullYear(), //年 : 4位
  yy : date.getFullYear().toString().substring(2),//年 : 2位
  M  : date.getMonth() + 1,  //月 : 如果1位的时候不补0
  MM : paddNum(date.getMonth() + 1), //月 : 如果1位的时候补0
  d  : date.getDate(),   //日 : 如果1位的时候不补0
  dd : paddNum(date.getDate()),//日 : 如果1位的时候补0
  hh : date.getHours(),  //时
  mm : date.getMinutes(), //分
  ss : date.getSeconds(), //秒
  };
  if( !format )format = "yyyy-MM-dd hh:mm:ss";
  return format.replace(/([a-z])(\1)*/ig,function(m){return cfg[m];});
} 



// 提交修改和处理
function post_fieldRev(q_this,fun){
  filter.field_y=q_this.attr('_y');
  filter.field_i=q_this.parents('tr').attr('_i');
  $.post('http://'+location.host+'/manager/manuser/ajax',
        $.extend(filter,{ac:'setPa','ajax_request':1}),fun,'json'); // jq
}

// 获取内容和处理
function get_content(url){
  filter.ajax_request=1;
  $.post(url,filter,function(html){
    var obj,ar=html.split("aj||ax");         
    obj=$.parseJSON(ar['0']);              
    if(obj.status==='OK'){
      filter=obj.filter;
      q_main.html( ar['1'] );
      show_sort_order_img();
    }else{
      Users.ShowMsg(obj.msg);
    }
  },'html');
}
// 显示当前排序的图片,循环显示状态
function show_sort_order_img(){
  $('#sortOrImg').remove();
  q_main.find('.sortOr').each(function(){
    var q_this=$(this);
    if( q_this.attr('_z') === filter.order){
      q_this.after('<img id="sortOrImg" src="'+baseImgUrl+'sort_'+
        filter.sort+'.gif" />');
    }
  });
  q_main.find('.static_img').each(function(){
    var q_this=$(this),img=q_this.attr('_v') === '1'?'yes.gif':'no.gif';
    q_this.prop('src',baseImgUrl+img).prop('title','点击修改状态').show();
  });
  q_main.find('.fieldRev').each(function(){$(this).prop('title','点击修改内容'); });
}
/*---列表页js 结束---------------------------------------------------------------------------*/


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




/**
 * 格式化日期
 * @param  {[type]} format [description]
 * @param  {[type]} date   [description]
 * @return {[type]}        [description]
 */
function formatDate(format,date){
  if( !date )date = new Date();
  var paddNum = function(num){
    return num>9?num:'0'+num;
  };
  //指定格式字符
  var cfg = {
    yyyy : date.getFullYear(), //年 : 4位
    yy : date.getFullYear().toString().substring(2),//年 : 2位
    M  : date.getMonth() + 1,  //月 : 如果1位的时候不补0
    MM : paddNum(date.getMonth() + 1), //月 : 如果1位的时候补0
    d  : date.getDate(),   //日 : 如果1位的时候不补0
    dd : paddNum(date.getDate()),//日 : 如果1位的时候补0
    hh : date.getHours(),  //时
    mm : date.getMinutes(), //分
    ss : date.getSeconds(), //秒
  };
  if( !format )format = "yyyy-MM-dd hh:mm:ss";
  return format.replace(/([a-z])(\1)*/ig,function(m){return cfg[m];});
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