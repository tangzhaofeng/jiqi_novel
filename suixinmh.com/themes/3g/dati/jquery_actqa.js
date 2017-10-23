var isChecked = false; var isBlur = false;
$(function () {
    $(".grab_body").css("min-height", document.documentElement.clientHeight + "px");
    $("#btngetcode").on('click', function () {
        if (PhoneCheck(document.all.tel.value) == false) {
            $(".phone_gth").show();
            $("#teltips").text("手机号码输入不正确");
        }
        else {
            $(this).attr("disabled", "disabled");
            $.fn.WsAjax({
                url: "active.aspx",
                data: { "action": "VerifyPhone", "telphone": document.all.tel.value },
                success: function (data) {
                    if (data[0].Success) {
                        $(".phone_gth").show();
                        $("#teltips").text("手机号码验证通过");

                        $.fn.WsAjax({
                            url: "active.aspx",
                            data: { "action": "getIdentifyingCode", "telphone": document.all.tel.value },
                            success: function (data) {
                                if (data[0].Success) {
                                    $("#btngetcode").val(data[0].PK / 1000 + "秒后重新获取");
                                    var timeSecond = data[0].PK / 1000;
                                    var djsInt = setInterval(function () {
                                        if (timeSecond > 0) {
                                            timeSecond = timeSecond - 1;
                                            $("#btngetcode").val(timeSecond + "秒后重新获取");
                                        }
                                        else {
                                            $("#btngetcode").removeAttr("disabled");
                                            $("#btngetcode").val("获取验证码");
                                            clearInterval(djsInt);
                                        }
                                    }, 1000);
                                    //setTimeout(function () {
                                    //    $("#btngetcode").removeAttr("disabled");
                                    //    $("#btngetcode").val("获取验证码");
                                    //}, data[0].PK);
                                }
                            },
                            error: function (error) {
                                alert("数据访问出错！");
                            }
                        });
                    }
                    else {
                        $(".phone_gth").show();
                        $("#teltips").text("该手机号已绑定");
                    }
                },
                error: function (error) {
                    alert("数据访问出错！");
                }
            });
        }
    });

    $("#btnyzmsubmit").on('click', function () {
        $.fn.WsAjax({
            url: "active.aspx",
            data: { "action": "bindTelphone", "telphone": document.all.tel.value, "yzm": $("#yzm").val() },
            success: function (data) {
                if (data[0].Success) {
                    var sucesshtml = "<p class='sucessbind'>恭喜您，手机号绑定成功！</p>";
                    $(".phone_btn").empty().append(sucesshtml);
                }
                else
                    alert(data[0].Error);
            },
            error: function (error) {
                alert("数据访问出错！");
            }
        });
    })
});

function PhoneCheck(s) {
    if (!$("input[name='tel']").is(":hidden")) {
        var str = s;
        var reg = /^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/
        if (!reg.test(str)) {
            return false;
        }
    }
}