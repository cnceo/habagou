/***
 * Created by sint on 2016/8/5.
 * BJPHP配套的前端库，包括：
 * 1. 前后台通讯机制(apicall,datacall)
 * 2. http相关工具函数(url修正、跳转)
 * 3. html相关工具函数(编码解码、事件、设备、js及css动态加载、消息框、控件取值赋值)
 *
 * 注：前端库中的提示框功能依赖于bootstrap样式库
 */

//全局配置
var bjui_config={
    'debug' : true,
    'vdname':'',
    'theme' :'default'
    //'error_report':function(){} 自定义异常处理
};

function bjuiconfig(obj)
{
    for( var v in obj) bjui_config[v] = obj[v];
}
//== js及ajax 扩充功能=======================================================
function fixurl(url) {
    if (url.indexOf('/') == 0) {
        vdname = bjui_config.vdname;
        if ('' == vdname) return url;
        else return '/' + vdname + url;
    }
    return url;
}
//字符串转数组
function bjui_string2array(str, splitstr) {

    if (undefined == splitstr) splitstr = "|";
    if (undefined == str || "" == "" + str) return [];
    if (str.indexOf(splitstr) >= 0)
        return str.split(splitstr);
    else
        return [str];
}
//数组转字符串
function bjui_array2string(arr, splitstr) {
	if(!( arr instanceof Array ) ) return '';
    if (undefined == splitstr) splitstr = "|";
    return arr.join(splitstr);
}
//类型转换 int
function bjui_toint(str) {
    if (!str) return 0;
    if (typeof str == "boolean" && str) return 1;
    return parseInt("" + str, 10);
}
//类型转换 string
function bjui_tostring(o) {
    if (!o) return '';
    return '' + o;
}
//遍历 fn返回true时表示停止遍历
function bjui_each(arr, fn) {
    var len = arr.length;
    for (var i = 0; i < len; i++) {
        var data = arr[i];
        if (fn(data)) break;
    }
}
//高性能字符串
function bstring() {
    this.data = [];

    if (typeof bstring.__itd == "undefined") {
        var $p = bstring.prototype;
        $p.Append = function (a) {
            this.data.push(a);
            return this;
        };
        $p.toString = function () {
            return this.data.join("");
        };
        bstring.__itd = true;
    }
}

//动态加载 js/css
function bpackage() {
    if (typeof bpackage.__itd == "undefined") {
        var $p = bpackage.prototype;
        $p.Find = function (k) {
            if (k in this) return this[k];
            else return undefined;
        };

        $p.Import = function (k, sp, iscss, sChar, fn) {
            var _this = this;
            if (_this.Find(k) === undefined) {
                var Num = "sp";
                for (var i = 0; i < 6; i++) {
                    Num += Math.floor(Math.random() * 10);
                }
                $.ajax({
                    type: 'GET',
                    url: sp + "?_" + Num + "=1",
                    cache: false,
                    async: true
                }).done(function () {
                    if (_this.Find(k) === undefined) {
                        if (iscss) {
                            $("<link>")
                                .attr({
                                    rel: "stylesheet",
                                    type: "text/css",
                                    href: sp + "?_" + Num + "=1"
                                })
                                .appendTo("head");
                        }
                        else {
                            $("<script>")
                                .attr({
                                    type: "text/javascript",
                                    charset: sChar,
                                    href: sp + "?_" + Num + "=1"
                                })
                                .appendTo("head");

                        }
                        _this[k] = sp;
                    }
                    if (typeof fn == 'function') fn();
                });


            }
            else {
                if (typeof fn == 'function') fn();
            }
        };
        bpackage.__itd = true;
    }
}

var $PACKAGES$ = new bpackage();

//前端日志
window.console = window.console || {
        log: function (x) {
        }
    };

function bjui_log(lx) {
    console.log(lx);
}

$(function () {
    //.ajaxError事件定位到document对象，文档内所有元素发生ajax请求异常，都将冒泡到document对象的ajaxError事件执行处理
    $(document).ajaxError(
        //所有ajax请求异常的统一处理函数，处理
        function (event, xhr, options, exc) {
            if (xhr.status == 'undefined' || 0 == xhr.status || 200 == bjui_toint(xhr.status)) {
                return;
            }
            __error_box('' + xhr.status + '错误', xhr.responseText);
        }
    );
});
//---------------- 提示框 --------------------
//信息提示框
function bjui_alert(sTitle, sText, fn) {
    __alert_box(HTMLEncode(sTitle), HTMLEncode(sText), fn);
}

//错误提示框
function bjui_error(sTitle, sText, fn) {
    __error_box(HTMLEncode(sTitle), HTMLEncode(sText), fn);
}

//警告框
function bjui_warnning(sTitle, sText, fn) {
    __warnning_box(HTMLEncode(sTitle), HTMLEncode(sText), fn);
}

//确认提示,点确定返回true,否则返回false
function bjui_confirm(sTitle, sText, fn, fnCancel) {
    __confirm_box(HTMLEncode(sTitle), HTMLEncode(sText), fn, fnCancel);
}


//== 前后台通讯 ========================================================================================
function bjui_call(src, param, fn, fnFail) {
    _bjui_call(src, param, false, fn, fnFail);
}
function bjui_async_call(src, param, fn, fnFail) {
    _bjui_call(src, param, true, fn, fnFail);
}
function _bjui_call(src, param, isasync, fn, fnFail) {
    var _hintsrc = src;

    $.ajax({
        type: 'POST',
        url: src,
        data: param,
        success: function (data) {
            //try
            //{
            var sdata = data.replace(/(^\s*)|(\s*$)/g, "");
            if (sdata.indexOf('{') != 0 || sdata.indexOf('<') == 0) {
                bjui_call_err_report(false, src, param, data, fnFail);
            }
            else {
                var jsonObject = JSON.parse(data);//eval("("+data+")");
                if (bjui_toint(jsonObject.status) > 0 || !('Data' in jsonObject) || (('message' in jsonObject) && jsonObject.message != "" )) {
                    bjui_call_err_report(bjui_toint(jsonObject.userexception), src, param, jsonObject.message.replace(/\n/g, "<br>"), fnFail);
                }
                else {
                    if (typeof fn === 'function') {
                        //try
                        {
                            fn(jsonObject.Data);
                        }
                        //catch(ex)
                        //{
                        //	bjui_call_err_report(false,src,param,"成功调用后台服务，但前台回调处理时出错:"+ex.message+"<br>"+data,fnFail);
                        //}
                    }
                }
            }
            //}
            //catch(ex)
            //{
            //bjui_call_err_report(false,src,param,"成功调用后台服务，但返回结果不正确，错误描述："+ex.message+"<br>返回结果：<br>"+data,fnFail);
            //}
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            bjui_call_err_report(false, src, param, XMLHttpRequest.responseText, fnFail);
        },
        dataType: "text",
        cache: false,
        async: isasync
    });
}
function bjui_call_err_report(userexception, src, param, msg, fnFail) {
	if( typeof bjui_config.error_report === "function" )
	{
		bjui_config.error_report(userexception,src,param,msg,fnFail);
		return;
	}
    if (userexception) {
        __error_box('错误', msg, fnFail);
    }
    else {
        var cs = src;
        if (typeof param == 'object') {
            for (var v in param) {
                var obj = param[v];
                if (typeof obj == 'object') {
                    var str = '';
                    for (var k in obj) {
                        str += '<p>/' + k + ':<b class="bj-text-info">' + obj[k] + "</b></p>";
                    }
                    if (str != '') cs += '{' + str + '}';
                }
                else
                    cs += '/' + v + '/<b class="bj-text-info">' + param[v] + "</b>";
            }
        }

        var pos1 = msg.indexOf('<body>');
        if( pos1 > 0 )
        {
            var pos2 = msg.indexOf('</body>');
            if( pos2 > pos1 ) msg = msg.substr(pos1+6,pos2-pos1-6);
        }

        var id = "cs_err" ;
        var html = "<div style='margin-bottom:5px' id='cs_message'>"+msg+"</div>";
        if( bjui_config.debug || bjui_config.debug == 'true' ) {
            html += "<a href=\"javascript:$('#" + id + "').toggle();\">点此查看调用信息 >> </a>";
            html += "<div id='" + id + "' style='display:none'>调用路径：<b>" + cs + "</b></div>";
        }
        __error_box('错误', html, fnFail);
    }
}
function BizCall(api_path, in_args, fn, fnFail) {
    var url = '';
    if( bjui_config.vdname != '' ) url = '/' + bjui_config.vdname;
    url += "/bizcall/" + api_path.replace(/\./g,'/');
    bjui_async_call(url, in_args, fn, fnFail);
}



//== 设备相关 =========================================================================================
function IsPC() {
    var userAgentInfo = navigator.userAgent;
    var Agents = ["Android", "iPhone",
        "SymbianOS", "Windows Phone",
        "iPad", "iPod"];
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}


//== HTML相关 =========================================================================================
function HTMLEncode(s) {
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(s));
    return div.innerHTML;
}
function HtmlDecode(s) {
    var div = document.createElement('div');
    div.innerHTML = s;
    return div.innerText || div.textContent;
}

function StopEvent() {
    if (window.event) {
        event.cancelBubble = true;//IE
        event.returnValue = false;
    } else {
        event.stopPropagation();// 其它浏览器下阻止冒泡
    }
    event.returnValue = false;
    return false;
}

function bjui_redirect(url) {
    window.location.href = url;
}
//---------------- 取值       --------------
function bjui_edit_getvalue(s)
{
    var str= $('#'+s).val();
    if( undefined == str ) return "";
    return ""+str;
}
function bjui_edit_setvalue(s,v)
{
    return $('#'+s).val(undefined == v ? "" : v);
}
function bjui_checkbox_getvalue(s)
{
    return $("#"+s).is(":checked") ? 1 : 0;
}
function bjui_checkbox_setvalue(s,v)
{
    if( v ) $("#"+s).attr("checked",'true');
    else $("#"+s).removeAttr("checked");
}
function bjui_combo_getvalue(id)
{
	return $('#'+id).val();
}
function bjui_combo_setvalue(id,v)
{
	return $('#'+id).val(v);
}

//== 弹出框 =======================================================================
var alert_config = {
    'fillcontent' : function (id,html) {
        var obj = $('#'+id);
        obj.html(html);
    }
};

//按钮点击前提示
//按钮需增加 data-confirm提示信息及 data-action确认后的动作
function bind_confirm(obj)
{
    if( obj.length )
    {
        obj.each(function(index,element){
            bind_confirm(element);
        });
    }
    else
    {
        var hint = $(obj).attr('data-confirm');
        var action = $(obj).attr('data-action');
        $(obj).bind('click', function () {
            bjui_confirm('询问', hint, function () {
                bjui_redirect(action);
            });
        });
    }
}

//信息提示框
function __alert_box(sTitle, sText, fn) {
    var obj = $("#g_alert");
    if (obj.length <= 0) {
        var html =
            "<div class=\"modal fade\" id=\"g_alert\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"g_alert_title\">" +
            "<div class=\"modal-dialog\" role=\"document\">" +
            "<div class=\"modal-content\">" +
            "<div class=\"modal-header\">" +
            "<h4 class=\"modal-title\" id=\"g_alert_title\">Modal title</h4>" +
            "</div>" +
            "<div class=\"modal-body\">" +
            "<div class=\"alert alert-info\" id=\"g_alert_hint\"></div>" +
            "</div>" +
            "<div class=\"modal-footer\">" +
            "<button type=\"button\" onclick=\"javascript:alert_config.__alert_ok();\" class=\"btn btn-primary\" data-dismiss=\"modal\">确定</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";

        $(document.body).append(html);
        obj = $("#g_alert");
    }
    alert_config.fillcontent('g_alert_title',sTitle);
    alert_config.fillcontent('g_alert_hint',sText);
    alert_config.alert_func = fn;
    obj.modal({show: true});
}
alert_config.__alert_ok = function () {
    $("#g_alert").modal("hide");
    if (typeof alert_config.alert_func == 'function') alert_config.alert_func();
};

//错误提示框

function __error_box(sTitle, sText, fn) {
    var obj = $("#g_error");
    if (obj.length <= 0) {
        var html =
            "<div class=\"modal fade\" id=\"g_error\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"g_error_title\">" +
            "<div class=\"modal-dialog\" role=\"document\">" +
            "<div class=\"modal-content\">" +
            "<div class=\"modal-header\">" +
            "<h4 class=\"modal-title\" id=\"g_error_title\" style=\"color:red\"></h4>" +
            "</div>" +
            "<div class=\"modal-body\">" +
            "<div class=\"alert alert-danger\" id=\"g_error_hint\"></div>" +
            "</div>" +
            "<div class=\"modal-footer\">" +
            "<button type=\"button\" onclick=\"javascript:alert_config.__error_ok();\" class=\"btn btn-primary\" data-dismiss=\"modal\">确定</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
        $(document.body).append(html);
        obj = $("#g_error");
    }
    alert_config.fillcontent('g_error_title',sTitle);
    alert_config.fillcontent('g_error_hint',sText);
    alert_config.error_func = fn;
    obj.modal({show: true});
}
alert_config.__error_ok = function () {
    $("#g_error").modal("hide");
    if (typeof alert_config.error_func == 'function') alert_config.error_func();
};

//警告框

function __warnning_box(sTitle, sText, fn) {
    var obj = $("#g_warnning");
    if (obj.length <= 0) {
        var html =
            "<div class=\"modal fade\" id=\"g_warnning\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"g_warnning_title\">" +
            "<div class=\"modal-dialog\" role=\"document\">" +
            "<div class=\"modal-content\">" +
            "<div class=\"modal-header\">" +
            "<h4 class=\"modal-title\" id=\"g_warnning_title\"></h4>" +
            "</div>" +
            "<div class=\"modal-body\">" +
            "<div class=\"alert alert-warning\" id=\"g_warnning_hint\"></div>" +
            "</div>" +
            "<div class=\"modal-footer\">" +
            "<button type=\"button\" onclick=\"javascript:alert_config.__warnning_ok();\" class=\"btn btn-primary\" data-dismiss=\"modal\">确定</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
        $(document.body).append(html);
        obj = $("#g_warnning");
    }
    alert_config.fillcontent('g_warnning_title',sTitle);
    alert_config.fillcontent('g_warnning_hint',sText);
    alert_config.warnning_func = fn;
    obj.modal({show: true});
}

alert_config.__warnning_ok = function () {
    $("#g_warnning").modal("hide");
    if (typeof alert_config.warnning_func == 'function') alert_config.warnning_func();
};


//确认提示,点确定返回true,否则返回false
function __confirm_box(sTitle, sText, fn, fnCancel) {
    var obj = $("#g_confirm");
    if (obj.length <= 0) {
        var html =
            "<div class=\"modal fade\" id=\"g_confirm\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"g_confirm_title\">" +
            "<div class=\"modal-dialog\" role=\"document\">" +
            "<div class=\"modal-content\">" +
            "<div class=\"modal-header\">" +
            "<h4 class=\"modal-title\" id=\"g_confirm_title\"></h4>" +
            "</div>" +
            "<div class=\"modal-body\">" +
            "<div class=\"alert alert-success\" id=\"g_confirm_hint\"></div>" +
            "</div>" +
            "<div class=\"modal-footer\">" +
            "<button type=\"button\" onclick=\"javascript:alert_config.__confirm_ok();\" class=\"btn btn-warning\" data-dismiss=\"modal\">确定</button>" +
            "<button type=\"button\" onclick=\"javascript:alert_config.__confirm_cancel();\" class=\"btn btn-primary blue\">取消</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
        $(document.body).append(html);
        obj = $("#g_confirm");
    }
    alert_config.fillcontent('g_confirm_title',sTitle);
    alert_config.fillcontent('g_confirm_hint',sText);
    alert_config.confirm_func = fn;
    alert_config.confirm_func_cancel = fnCancel;
    obj.modal({show: true});
}

alert_config.__confirm_ok = function () {
    $("#g_confirm").modal("hide");
    if (typeof alert_config.confirm_func == 'function') alert_config.confirm_func();
};

alert_config.__confirm_cancel = function () {
    $("#g_confirm").modal("hide");
    if (typeof alert_config.confirm_func_cancel == 'function') alert_config.confirm_func_cancel();
};