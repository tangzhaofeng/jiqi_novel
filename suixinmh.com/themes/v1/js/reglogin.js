//验证初始化
$('#signup_form').validator({ 
    theme: 'yellow_right_effect',
    stopOnError:true,
    timely: 2,
    //自定义规则（PS：建议尽量在全局配置中定义规则，统一管理）
    rules: {
        username: [/^[a-zA-Z0-9]+$/, '用户名无效! 仅支持字母与数字。']
    },
    fields: {
        "user[name]": {
            rule: "required",
            tip: "输入你的名字与姓氏。",
            ok: "名字很棒。",
            msg: {required: "全名必填!"}
        },
        "user[email]": {
            rule: "email;remote[check/email.php]",
            tip: "你的邮件地址是什么?",
            ok: "我们将会给你发送确认邮件。",
            msg: {
                required: "电子邮箱地址必填!",
                email: "不像是有效的电子邮箱。"
            }
        },
        "user[user_password]": {
            rule: "required;length[6~];password;strength",
            tip: "6个或更多字符! 要复杂些。",
            ok: "",
            msg: {
                required: "密码不能为空!",
                length: "密码最少为6位。"
            }
        },
        "user[screen_name]": {
            rule: "required;username;remote[check/user.php]",
            tip: "别担心,你可以稍后进行修改。",
            ok: "用户名可以使用。<br>你可以稍后进行修改。",
            msg: {required: "用户名必填!<br>你可以稍后进行修改。"}
        }
    },
    //验证成功
    valid: function(form) {
        $.ajax({
            url: 'results.php',
            type: 'POST',
            data: $(form).serialize(),
            success: function(d){
                $('#result').fadeIn(300).delay(2000).fadeOut(500);
            }
        });
    }
});