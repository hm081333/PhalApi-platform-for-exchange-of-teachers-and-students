var href_id = "";
var href_data = {};
var href_step = getStep();
var href_step_data = getData();
var href_step_index = href_step.length - 1;//当前页面历史下标 初始下标为-1，有一个页面的时候应该为0
var obj = {};
/*追加页面对象的写法*/
obj['index'] = function () {
    // var data = getData({service: 'Default.Main'});
    pageInit({service: 'Default.Main'});
};
obj['topic_list'] = function () {
    pageInit({service: 'Topic.Topic_List'});
};
obj['topic_info'] = function () {
    pageInit({service: 'Topic.Topic'});
};
obj['user_info'] = function () {
    pageInit({service: 'User.User_Info'});
};
obj['edit_member'] = function () {
    pageInit({service: 'User.Edit_Member'});
};
obj['create_topic'] = function () {
    $("svg").remove();
    pageInit({service: 'Topic.Create_Topic'});
    sendFormAjax("#create_topic form", function (d) {
        location.href = '#topic_info?topic_id=' + d.data['topic_id'];
        page_href_id();
    });
};
obj['delivery_list'] = function () {
    pageInit({service: 'Default.DeliveryList'});
    bindClick("#delivery_list a.delivery_id", function () {
        sendButtomAjax($(this), function (d) {
            if (d.ret == 200) {
                $('#delivery').html(d.data);
                $('.collapsible').collapsible();
                $('.collapsible').collapsible('open', 0);
                $('#delivery').modal('open');
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        });
    });
    bindClick("#delivery_list a.delete", function () {//触发点击事件
        $this_tr = $(this).parent().parent();
        sendButtomAjax($(this), function (d) {
            if (d.ret == 200) {
                SuccessMsg(d);
                $this_tr.remove();
            } else {
                Materialize.toast(d.msg, 2000, 'rounded');
            }
        });
    });
    sendFormAjax("#delivery_list #addDelivery form", function () {
        $('#delivery_list #addDelivery').modal('close');
        pageReload();//重新加载当前页面
    });
};
obj['login'] = function () {
    pageInit({service: 'User.Login'});
    sendFormAjax("#login form");
};


$(document).ready(function () {
    bindClick("header nav #language a.lang", function () {
        sendButtomAjax($(this), function (d) {
            if (d.ret == 200) {
                SuccessMsg(d, function () {
                    location.reload();
                });
            } else {
                alertMsg(d.msg);
            }
        });
    });

    // page_href_id(); //第一次访问时加载首页
    window.addEventListener('popstate', function (event) {

        console.log('location: ' + document.location);

        console.log('state: ' + JSON.stringify(event.state));

    });

    $("body").on("click", "a", function (event) {//监听body下所有a标签的点击事件
        $click = $(event.currentTarget);//当前点击的对象
        if ($click.is("a.btn-link")) {
            event.preventDefault(); //阻止默认操作 - 跳转href地址
            var href = $click.attr('href');
            var data_obj = $click.data();
            data = JSON.stringify(data_obj);
            console.log(data);
            $.ajax({
                type: 'POST',
                url: href,
                data: data,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (d) {
                    console.log(history);
                    if (parseInt(d.ret) === 200) {
                        history.pushState(data_obj, 'page 2', href);
                        var html = d.data;
                        var $html = $(html);
                        var page_id = $html.attr('id');
                        if (Boolean($html.hasClass('page')) === false) {
                            $html.addClass('page')
                        }
                        var new_page_selector = '#Content #' + page_id;
                        if (Boolean($(new_page_selector).length)) {
                            $(new_page_selector).html($html.html());
                        } else {
                            $('#Content').append(html);
                        }
                        $('.page-current').removeClass('page-current');
                        if (Boolean($(new_page_selector).hasClass('page-current')) === false) {
                            $(new_page_selector).addClass('page-current');
                        }
                    } else {
                        alertMsg(d.msg);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alertMsg(textStatus)
                }
            });
            return false;
        } else if ($click.is("a.btn-back")) {
            event.preventDefault(); //阻止默认操作 - 跳转href地址
            backLastStep();
            return false;
        }
    });

});

function diy_href() {
    // old_data = getData();
    // if (JSON.stringify(old_data) !== '{}') {
    //     href_data = old_data;
    //     href_id = href_data['href_id'];
    //     return;
    // }
    var href = window.location.href;
    var hrefs = href.split('#');
    var page_req = hrefs[1];
    if (page_req) {
        var reqs = page_req.split('?');
        href_id = reqs[0];
        if (reqs[1]) {
            param_words = reqs[1].split('&');
            var params = {};
            for (i = 0; i < param_words.length; i++) {
                param = param_words[i].split('=');
                params[param[0]] = param[1];
            }
        }
        href_data = params;
        href_data['href_id'] = href_id;
        history.pushState({}, '', hrefs[0]);
    } else if (href_step_index >= 0 && href_id === '') {
        href_id = href_step[href_step_index];
        href_data = href_step_data[href_id];
    } else {
        if (href_id == "") {
            href_id = 'index';
        }
    }

}

/*调起页面对象函数*/
function page_href_id() {
    diy_href();
    var obj_func_name = 'obj.' + href_id;
    console.log(obj_func_name);
    if (typeof(eval(obj_func_name)) === "function") {
        eval(obj_func_name + '()');
    } else {
        // 函数不存在
    }
}

/**
 * 页面重载 - 重载当前页面
 */
function pageReload() {
    Ajax(href_data, function (d) {
        if ($('#' + href_id).length > 0) {
            $('#' + href_id).empty();
            $('#' + href_id).html(d.data);
        } else {
            var html = "";
            html += '<div class="page page-inited" id="' + href_id + '">';
            html += d.data;
            html += '</div>';
            $('#Content').append(html);
        }
        afterPageLoad();//初始化所有必要的框架初始化
        backBtn();
        goNextStep();
        $('.page-current').removeClass('page-current');
        $('#' + href_id).addClass('page-current');
        console.log(href_step, href_step_data, href_step_index);
    });
}

function pageInit(data, func) {
    $.extend(data, href_data);
    href_data = data;
    href_data['href_id'] = href_id;
    Ajax(data, function (d) {
        if ($('#' + href_id).length > 0) {
            $('#' + href_id).empty();
            $('#' + href_id).html(d.data);
        } else {
            var html = "";
            html += '<div class="page page-inited" id="' + href_id + '">';
            html += d.data;
            html += '</div>';
            $('#Content').append(html);
        }
        afterPageLoad();//初始化所有必要的框架初始化
        backBtn();
        goNextStep();
        $('.page-current').removeClass('page-current');
        $('#' + href_id).addClass('page-current');
        console.log(href_step, href_step_data, href_step_index);
    });
}

function pageReInit() {
    afterPageLoad();//初始化所有必要的框架初始化
    backBtn();
    $('.page-current').removeClass('page-current');
    $('#' + href_id).addClass('page-current');
    console.log(href_step, href_step_data, href_step_index);
}

function backBtn() {
    if (href_id === "index") {
        $('header nav a.btn-back').addClass('hide');
    } else {
        $('header nav a.btn-back').removeClass('hide');
    }
}

/**
 * 传入传值数组，存入localStorage本地存储
 * @param data
 */
function setData(data) {
    // localStorage.setItem("href_step_data", JSON.stringify(data));//转化为JSON字符串 存储 localStorage - H5本地存储
    sessionStorage.setItem("href_step_data", JSON.stringify(data));//转化为JSON字符串 存储 sessionStorage - H5本地存储
}

/**
 * 传入api地址，读取localStorage本地存储数组，合并成一个完成的请求数组
 * @param data
 * @returns array
 */
function getData(data) {
    // var href_data = JSON.parse(localStorage.getItem("href_step_data")); //读取本地存储
    var href_data = JSON.parse(sessionStorage.getItem("href_step_data")) || {}; //读取本地存储
    return $.extend(data, href_data);
}

/**
 * 设置页面步进
 * @param data
 */
function setStep(data) {
    // localStorage.setItem("href_step", JSON.stringify(data));//转化为JSON字符串 存储 localStorage - H5本地存储
    sessionStorage.setItem("href_step", JSON.stringify(data));//转化为JSON字符串 存储 sessionStorage - H5本地存储
}

/**
 * 获取页面步进
 * @returns array
 */
function getStep() {
    // return JSON.parse(localStorage.getItem("href_step")); //读取本地存储
    return JSON.parse(sessionStorage.getItem("href_step")) || []; //读取本地存储
}

/**
 * 跳转新页面时候步进添加新页面id
 */
function goNextStep() {
    if (href_id === 'index') {
        href_step_index = 0;
        href_step = ['index'];
        href_step_data = {};
        href_step_data['index'] = href_data;
    } else {
        if (parseInt(href_step_index) >= 0) {
            var now_page = href_step[href_step_index];//现在所在页面的id
            if (now_page === href_id) {//就是当前页面，不添加
                return false;
            }
        }
        href_step[href_step_index + 1] = href_id;
        href_step_data[href_id] = href_data;
        href_step_index += 1;
    }
    setStep(href_step);
    setData(href_step_data);
}

/**
 * 返回上一个页面时候的步进
 */
function backLastStep() {
    if (parseInt(href_step_index) === 0) {
        return false;
    }
    var last_index = href_step_index - 1;//上一个页面id的index
    href_id = href_step[last_index];//上一个页面的id
    href_data = href_step_data[href_id];//上一个页面的参数
    href_step.pop();//删除最后一个加载的页面
    href_step_index -= 1;
    setStep(href_step);
    setData(href_step_data);
    if ($('#' + href_id).length > 0) {
        pageReInit();//重载页面
    } else {
        console.log(href_id, href_data);
        pageReload();
    }
}

