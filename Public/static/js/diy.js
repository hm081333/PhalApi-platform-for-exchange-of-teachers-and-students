//返回顶部按钮
//页面DOM加载后
jQuery(document).ready(function ($) {
    var offset = 150,
        offset_opacity = 1200,
        scroll_top_duration = 700,
        $back_to_top = $('.cd-top');
    $(window).scroll(function () {
        ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {
            $back_to_top.addClass('cd-fade-out');
        }
    });
    $back_to_top.on('click', function (event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
        }, scroll_top_duration);
    });

    afterPageLoad();
    var ip = returnCitySN['cip'];
    $('#ip').html(ip);
    $('#ip').attr('href', 'http://www.ip138.com/ips138.asp?ip=' + ip);
    // var ip = $('#ip').html();
    Ajax({service: 'Public.ip', ip: ip}, function (d) {
        if (d.ret == 200) {
            $('#ip_address').text(d.data.country + ' ' + d.data.area + ' ' + d.data.region + ' ' + d.data.city + ' ' + d.data.isp)
        } else {
            $('#ip_address').text(d.msg)
        }
    });
});

/**
 * 消除之前的js框架初始化
 */
function beginLoadNewPage() {
    $('body,html').animate({//滚动条回到顶端
        scrollTop: 0
    }, 0);
    $('.button-collapse').sideNav('hide');//隐藏侧滑导航
    $('.dropdown-button').dropdown('close');
    $(".material-tooltip").remove();
    $(".hiddendiv").remove();
}

/**
 * js框架初始化
 */
function afterPageLoad() {
    beginLoadNewPage();
    $(".button-collapse").sideNav();
    $('.modal').modal();
    $('.datepicker').pickadate({
        selectMonths: true, // 创建一个下拉菜单来控制月份
        selectYears: 30 // 创建一个30年的下拉菜单来控制年份
    });
    $('select').material_select();
    $('.collapsible').collapsible();
    $('.materialboxed').materialbox();
    $('ul.tabs').tabs({
        // swipeable: true
    });
    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false, // 不改变宽度
            hover: false, // hover时激活
            gutter: 0, // 边缘间隔
            belowOrigin: false, // 显示在按钮下面
            alignment: 'left', // 对齐方式
            stopPropagation: true // 停止事件传播
        }
    );
    $('.tooltipped').tooltip({
        delay: 50
    });
}

//上传图片预览
function preview(file) {
    var prevDiv = document.getElementById('preview');
    if (file.files && file.files[0]) {
        var reader = new FileReader();
        reader.onload = function (evt) {
            prevDiv.innerHTML = '<img src="' + evt.target.result + '" />';
        };
        reader.readAsDataURL(file.files[0]);
    } else {
        prevDiv.innerHTML = '<img style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'">';
    }
}


function SuccessMsg(data, SuccessCallBack, FailCallBack) {
    var msg = data.msg || data || '';
    var back = data.back || false;
    var url = data.data ? data.data.url : null;
    var fuc = SuccessCallBack && (typeof(SuccessCallBack) == "object" || typeof(SuccessCallBack) == "function") ? SuccessCallBack : function () {
        if (url) {
            location.href = url
        } else if (back) {
            history.back();
        }
        /* else {
                    location.reload();
                }*/
    };
    var failFuc = FailCallBack && (typeof(FailCallBack) == "object" || typeof(FailCallBack) == "function") ? FailCallBack : function () {
    };
    if (parseInt(data.ret) === 200) {
        alertMsg(msg, fuc(data));
    } else {
        console.log(failFuc);
        alertMsg(msg, failFuc(data));
    }
}

function alertMsg(msg, CallBack) {
    var fun = CallBack && (typeof(CallBack) == "object" || typeof(CallBack) == "function") ? CallBack : null;
    Materialize.toast(msg, 4000, 'rounded', fun);
}

function Ajax(data, SuccessCallback, file) {
    var fuc = SuccessCallback || SuccessMsg;
    if (file) {
        $.ajax({
            type: 'POST',
            url: window.NOW_WEB_SITE,
            data: data,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: fuc,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alertMsg(textStatus)
            }
        });
    } else {
        $.ajax({
            type: 'POST',
            url: window.NOW_WEB_SITE,
            data: data,
            dataType: 'json',
            success: fuc,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alertMsg(textStatus)
            }
        });
    }
}

$(".get_modal").click(function () {
    var $this = $(this);
    var data = $this.data();
    var modal_id = data.modal_id;
    Ajax(data, function (d) {
        if (d.ret == 200) {
            var html = d.data.html;
            console.log(html);
            $(modal_id + ' .modal-content').html(html);
            $(modal_id).modal('open');
        } else {
            Materialize.toast(d.msg, 2000, 'rounded');
        }
    });
    // $(modal_id).modal('open');
});

function sign_tieba(tieba_id) {
    Ajax({service: 'Tieba.DoSignByTiebaId', tieba_id: tieba_id})
}

function sign_baiduid(baidu_id) {
    Ajax({service: 'Tieba.DoSignByBaiduId', baidu_id: baidu_id})
}

function refresh_tieba(baidu_id) {
    Ajax({service: 'Tieba.RefreshTieba', baidu_id: baidu_id})
}

function no_sign(tieba_id) {
    var no_status = $('#no_sign' + tieba_id)[0]['checked'];
    Ajax({service: 'Tieba.NoSignTieba', tieba_id: tieba_id, no: no_status}, function (d) {
        if (d.ret == 200) {
            Materialize.toast(d.msg, 2000, 'rounded');
        } else {
            Materialize.toast(d.msg, 2000, 'rounded');
        }
    })
}

// 帖子操作
function stick_topic(topic_id) {
    Ajax({service: 'Topic.stick_Topic', topic_id: topic_id});
}

function unstick_topic(topic_id) {
    Ajax({service: 'Topic.unstick_Topic', topic_id: topic_id});
}

function delete_topic(topic_id) {
    Ajax({service: 'Topic.delete_Topic', topic_id: topic_id});
}

// 会员操作
function update_user(user_id) {
    var auth = $('#auth' + user_id)[0]['checked'];
    if (auth == true) {
        auth = 1;
    } else {
        auth = 0;
    }
    var email = $('#email' + user_id).val();
    var real_name = $('#real_name' + user_id).val();
    var password = $('#password' + user_id).val();
    $.ajax({
        type: 'POST',
        data: {
            dataType: 'json',
            service: 'User.edit_Member', action: 'post', user_id: user_id, auth: auth, email: email,
            real_name: real_name,
            password: password
        },
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

function logoff() {
    $.ajax({
        type: 'POST',
        data: {service: 'User.logoff'},
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

function update_admin(admin_id) {
    var auth = $('#auth' + admin_id)[0]['checked'];
    if (auth == true) {
        auth = 1;
    } else {
        auth = 0;
    }
    var password = $('#password' + admin_id).val();
    $.ajax({
        type: 'POST',
        data: {service: 'User.admin_list', action: 'post', admin_id: admin_id, auth: auth, password: password},
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

function delete_admin(admin_id) {
    $.ajax({
        type: 'POST',
        data: {service: 'User.delete_Admin', admin_id: admin_id},
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

function update_Class(class_id) {
    var name = $('#name' + class_id).val();
    var tips = $('#tips' + class_id).val();
    $.ajax({
        type: 'POST',
        data: {service: 'Class.update_Class', action: 'post', name: name, tips: tips, class_id: class_id},
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

function delete_Class(class_id) {
    $.ajax({
        type: 'POST',
        data: {service: 'Class.delete_Class', class_id: class_id},
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

function set_language(language) {
    Ajax({service: 'Public.setLanguage', language: language});
}

function check_google_auth(language) {
    var code = $('input[name="code"]').val();
    var secret = $('input[name="secret"]').val();
    $.ajax({
        type: 'POST',
        data: {service: 'Public.verify_Google_Auth_Code', code: code, secret: secret},
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    $('input[name="check"]').attr('value', 1);
                    $('#google_Auth').modal('close');
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
}

$('#Register').submit(function ()//提交表单
{
    Ajax($("#Register").serialize());
});

function sendFormAjax(selector, callback, file) {
    $("body").delegate(selector, "submit", function (event) {
        event.preventDefault();
        var $this = $(this);
        var $form_button = $this.find("button");
        var form_disable = $form_button.hasClass("disabled");
        if (form_disable === true) {
            return false;
        }
        $form_button.addClass("disabled");
        Ajax($this.serialize(), function (d) {
            $form_button.removeClass("disabled");
            SuccessMsg(d, callback);
        }, file);
    });
}

function initDeleteButton() {
    bindClick("a.delete", function (event) {//触发点击事件
        sendButtomAjax($(this), function (d) {
            SuccessMsg(d, callback);
        });
    });
}


// 谷歌身份认证登录
$('#forget').submit(function ()//提交表单
{
    $.ajax({
        type: 'POST',
        data: $("#forget").serialize(),
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    history.back();
                    // location.href='?service=User.login';
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
});

$('#add_Class').submit(function ()//提交表单
{
    Ajax($("#add_Class").serialize());
});

$('#Create_Topic').submit(function ()//提交表单
{
    Ajax(new FormData($('#Create_Topic')[0]), function (d) {
        if (d.ret == 200) {
            Materialize.toast(d.msg, 2000, 'rounded', function () {
                location.href = '?service=Topic.topic&topic_id=' + d.data['topic_id'];
            });
        } else {
            Materialize.toast(d.msg, 2000, 'rounded');
        }
    }, true);
});

$('#Reply_Topic').submit(function ()//提交表单
{
    Ajax(new FormData($('#Reply_Topic')[0]), false, true);
});

$('#edit_member').submit(function ()//提交表单
{
    Ajax($("#edit_member").serialize());
});

$('#search').submit(function ()//提交表单
{
    $.ajax({
        type: 'POST',
        data: $("#search").serialize(),
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    // location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
});

$('#config').submit(function ()//提交表单
{
    Ajax($("#config").serialize());
});

function sendButtomAjax(element, callback) {
    var data = element.data();
    element.addClass("disabled");
    Ajax(data, callback);
    element.removeClass("disabled");
}


function bindClick(selector, func) {
    $("body").delegate(selector, "click", func);
}

$('#Reset').submit(function ()//提交表单
{
    Ajax($(this).serialize());
});

$('#BackupModal form').submit(function ()//提交表单
{
    $.ajax({
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
});

$('a[restore]').click(function (event) {
    var file_name = $(this).data();
    $('#RestoreModal input[name="name"]').val(file_name['name']);
    $('#RestoreModal').modal('open');
});

$('#RestoreModal form').submit(function ()//提交表单
{
    $.ajax({
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (d) {
            if (d.ret == 200) {
                Materialize.toast(d.msg, 2000, 'rounded', function () {
                    location.reload();
                });
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        }
    });
});




