var page_id = "";
var page_url = "";
var page_data = {};
var page_history_ids = [];
var page_history_urls = [];

$(document).ready(function () {
    page_id = $('.page-current').attr('id');
    page_history_ids = [page_id];
    page_url = window.location.href;
    page_history_urls = [page_url];
    initForm();


    eventBind("header nav #language a.lang", "click", function () {
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


    window.addEventListener('popstate', function (event) {
        // document.location.reload()
        // page_url = document.location.href;
        page_data = event.state;

        page_history_ids.pop();
        page_history_urls.pop();
        page_id = page_history_ids.pop();
        page_url = page_history_urls.pop();
        // console.log(page_id);
        // console.log(page_url);
        pageInit();


    });

    $("body").on("click", "a", function (event) {//监听body下所有a标签的点击事件
        $click = $(event.currentTarget);//当前点击的对象
        if ($click.is("a.btn-link")) {
            event.preventDefault(); //阻止默认操作 - 跳转href地址
            page_url = $click.attr('href');
            page_data = $click.data();
            pageInit();

            return false;
        } else if ($click.is("a.btn-back")) {
            event.preventDefault(); //阻止默认操作 - 跳转href地址
            history.back();
            return false;
        }
    });

});

function pageInit() {
    $.ajax({
        type: 'POST',
        url: page_url,
        data: page_data,
        dataType: 'json',
        success: function (d) {
            console.log(history);
            if (parseInt(d.ret) === 200) {
                var html = d.data;
                var $html = $(html);
                if ($html.length !== 1) {
                    page_id = new Date().getTime();
                    html = '<div class="page page-current" id="' + page_id + '">' + html + '</div>';
                    $html = $(html);
                } else {
                    page_id = $html.attr('id');
                }

                if (!page_id) {
                    page_id = new Date().getTime();
                    $html.attr('id', page_id);
                }

                if (Boolean($html.hasClass('page')) === false) {
                    $html.addClass('page')
                }
                var new_page_selector = '#Content #' + page_id;
                if (Boolean($(new_page_selector).length)) {
                    $(new_page_selector).html($html.html());
                } else {
                    $('#Content').append($html[0]);
                }
                var $current_page = $('.page-current');
                var $pre_page = $($current_page[0]);
                var pre_page_id = $pre_page.attr('id');
                if (isNaN(pre_page_id) === false || !pre_page_id) {
                    historyRemove(pre_page_id);
                    $pre_page.remove();
                } else if (page_id != pre_page_id) {
                    $pre_page.removeClass('page-current');
                }
                afterPageLoad();//初始化所有必要的框架初始化
                historyAppend();// 追加历史纪录
                backBtn();
                // goNextStep();
                if (Boolean($(new_page_selector).hasClass('page-current')) === false) {
                    $(new_page_selector).addClass('page-current');
                }
                console.log(page_history_ids);
                console.log(page_history_urls);
            } else {
                alertMsg(d.msg);
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alertMsg(textStatus)
        }
    });
}

function historyAppend() {
    var index = page_history_ids.indexOf(page_id);
    if (index <= -1) {
        page_history_ids.push(page_id);
        page_history_urls.push(page_url);
        history.pushState(page_data, null, page_url);
    } else {
        page_history_urls.splice(index, 1, page_url);
        history.replaceState(page_data, null, page_url)
    }
}

function historyRemove(val) {
    var index = page_history_ids.indexOf(val);
    if (index > -1) {
        page_history_ids.splice(index, 1);
        page_history_urls.splice(index, 1);
    }
}

function backBtn() {
    if (page_history_ids.length > 1) {
        $('header nav a.btn-back').removeClass('hide');
    } else {
        $('header nav a.btn-back').addClass('hide');
    }
}

/**
 * 设置页面步进
 * @param data
 */
function setHistory(data) {
    sessionStorage.setItem("page_history", JSON.stringify(data));//转化为JSON字符串 存储 sessionStorage - H5本地存储
}

/**
 * 获取页面步进
 * @returns array
 */
function getHistory() {
    return JSON.parse(sessionStorage.getItem("page_history")) || []; //读取本地存储
}

/**
 * 跳转新页面时候步进添加新页面id
 */
function goNextStep() {
    history.pushState(page_data, null, page_url);
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

