var SocketIp = "kf.pigcms.com";
var TimeInterval;
var getType = 1;
var ajaxAddress = "http://kf.pigcms.com/";

var Factory = {
    create: function () {
        return function () { this.init.apply(this, arguments); }
    }
}

var ChatFloatFactory = Factory.create();

ChatFloatFactory.prototype = {
    init: function () {
        return this;
    },
    setCookie: function (name, value) {
        var exp = new Date();
        exp.setTime(exp.getTime() + 600 * 1000);
        document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
    },
    getCookie: function (name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
        if (arr = document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    },
    touches: function (obj) {
        obj.addEventListener("touchmove", function () {
            event.preventDefault();
            obj.style.left = (event.targetTouches[0].pageX - -document.body.scrollLeft - 30) + "px";
            obj.style.top = (event.targetTouches[0].pageY - document.body.scrollTop - 30) + "px";
        }, false)
    },
    AjaxRight: function (t, options) {
        var e = this;
        var ajax = new Ajax(function (responseText, responseXML) {
            if (responseText) {
                var obj = eval('(' + responseText + ')');
                if (obj && obj.pigcmsid && obj.pigcmsid.length > 0) {
                    t.initialize(options, obj.pigcmsid, obj.msgcount, obj.socket);
                    e.setCookie('useright', '{"aid":' + options.AId + ',"pigcmsid":"' + obj.pigcmsid + '","socket":' + obj.socket + '}');
                }
            }
        }, function (status) { });
        var openid = options.openid ? options.openid : "";
        ajax.get(ajaxAddress + "Ajax/CustomerChatAjax.aspx?action=userright&AId=" + options.AId + "&openid=" + openid, true);
    },
    SocketMessage: function (AId, pigcmsid) {
        var e = this;
        var ajax = new Ajax(function (responseText, responseXML) {
            if (responseText) {
                e.PollingSuccess(responseText);
            }
        }, function (status) { });
        ajax.get(ajaxAddress + 'Ajax/CustomerChatAjax.aspx?action=GetFloatSocketMessage&pigcmsid=' + pigcmsid + "&AId=" + AId, true);
    },
    StartPolling: function (AId, pigcmsid) {
        getType = 2;
        clearInterval(TimeInterval);
        TimeInterval = setInterval(GetPolling, 10000);
        this.GetPolling(AId, pigcmsid);
    },
    GetPolling: function (AId, pigcmsid) {
        var e = this;
        var ajax = new Ajax(function (responseText, responseXML) {
            if (responseText) {
                this.PollingSuccess(responseText);
            }
        }, function (status) { });
        ajax.get(ajaxAddress + 'Ajax/CustomerChatAjax.aspx?action=GetFloatPolling&pigcmsid=' + pigcmsid + "&AId=" + AId, true);
    },
    PollingSuccess: function (count) {
        if (parseInt(count) > 0) {
            document.getElementById("CustomerChatFloat").innerHTML = "<span style='width:9px;height:9px;background-color:#e90707;border-radius: 20px;display: block;position: absolute;left: 46px;top: 15px;'></span>";
        }
    },
    loadjscssfile: function (filename, filetype, loadfunction) {
        var fileref;
        if (filetype == "js") {
            fileref = document.createElement('script');
            fileref.setAttribute("type", "text/javascript");
            fileref.setAttribute("src", filename);

        } else if (filetype == "css") {
            fileref = document.createElement('link');
            fileref.setAttribute("rel", "stylesheet");
            fileref.setAttribute("type", "text/css");
            fileref.setAttribute("href", filename);
        }
        if (typeof fileref != "undefined") {
            document.getElementsByTagName("head")[0].appendChild(fileref);
        }
        fileref.onload = fileref.onreadystatechange = function () {
            if (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete') {
                loadfunction();
            }
        }
    }
}

var Tools = new ChatFloatFactory();

window.ChatFloat = function (options) {
    var e = this;
    if (options && options.AId) {
        if (options.IsTest != undefined && options.IsTest) {
            SocketIp = "112.124.16.233";
            ajaxAddress = "http://112.124.16.233/";
        }
        //判断有没有显示的权限
        var strStoreData = Tools.getCookie("useright");
        if (strStoreData && strStoreData.length > 0) {
            var objStorage = eval('(' + strStoreData + ')');
            if (objStorage && objStorage.aid == options.AId && objStorage.pigcmsid) {
                var socket = 0;
                if (objStorage.socket) {
                    socket = objStorage.socket;
                }
                e.initialize(options, objStorage.pigcmsid, 0, socket);
                Tools.SocketMessage(options.AId, objStorage.pigcmsid);
            }
            else {
                Tools.AjaxRight(e, options);
            }
        }
        else {
            Tools.AjaxRight(e, options);
        }
    }
};

ChatFloat.prototype = {
    initialize: function (options, pigcmsid, msgcount, socket) {
        var e = this;
        var mydiv = window.document.createElement("a");
        var openid = options.openid ? options.openid : "";
        mydiv.href = ajaxAddress + "MobileTalking.aspx?aid=" + options.AId + "&openid=" + openid;
        mydiv.setAttribute("id", "CustomerChatFloat");
        mydiv.style.position = "fixed";
        var left = options.left != undefined ? options.left : -1;
        if (left >= 0) {
            mydiv.style.left = left + "px";
        }
        else {
            var right = options.right != undefined ? options.right : 0;
            if (right >= 0) {
                mydiv.style.right = right + "px";
            }
        }
        var top = options.top != undefined ? options.top : -1;
        if (top >= 0) {
            mydiv.style.top = top + "px";
        }
        else {
            var bottom = options.bottom != undefined ? options.bottom : 0;
            if (bottom >= 0) {
                mydiv.style.bottom = bottom + "px";
            }
        }
        mydiv.style.zIndex = 99999;
        mydiv.style.height = (options.height ? options.height : 70) + "px";
        mydiv.style.width = (options.width ? options.width : 65) + "px";
        mydiv.style.minWidth = (options.width ? options.width : 65) + "px";
        mydiv.style.background = "url('http://hs-net-img.oss-cn-hangzhou.aliyuncs.com/MobileChatFloat.png') no-repeat 0px 0px";
        mydiv.style.backgroundSize = (options.width ? options.width : 65) + "px auto";
        if (msgcount > 0) {
            mydiv.innerHTML = "<span style='width:9px;height:9px;background-color:#e90707;border-radius: 20px;display: block;position: absolute;left: 46px;top: 15px;'></span>";
        }
        window.document.body.appendChild(mydiv);
        if (socket == 1) {
            Tools.loadjscssfile("http://" + SocketIp + ":8889/socket.io/socket.io.js", "js", function () { e.socketconnect(options, pigcmsid); });
        }
        var draggable = options.draggable != undefined ? options.draggable : true;
        if (draggable) {
            Tools.touches(document.getElementById("CustomerChatFloat"));
        }
    },
    socketconnect: function (options, pigcmsid) {
        try {
            var socket = io.connect('http://' + SocketIp + ':8889/');
            //连接事件
            socket.on("connect", function () {
                clearInterval(TimeInterval);
                getType = 1;
                socket.emit('paramquery', {
                    aid: options.AId,
                    pigcmsid: pigcmsid
                });
                socket.on('dataChange', function (json) {
                    if (json) {
                        if (json.msgCount == 1) {
                            Tools.SocketMessage(options.AId, pigcmsid);
                        }
                    }
                });
            });
            //重新连接
            socket.on('reconnecting', function (data) {
                
            });
            //重新连接成功
            socket.on('reconnect', function (data) {
                
            });
            socket.on('disconnect', function () { });
        }
        catch (e) {
        }
    }
};

var Ajax = Factory.create();

Ajax.prototype = {
    init: function (successCallback, failureCallback) {
        this.xhr = this.createXMLHttpRequest();
        var xhrTemp = this.xhr;
        var successFunc = null;
        var failFunc = null;

        if (successCallback != null && typeof successCallback == "function") {
            successFunc = successCallback;
        }

        if (failureCallback != null && typeof failureCallback == "function") {
            failFunc = failureCallback;
        }

        this.xhr.onreadystatechange = function () {
            if (xhrTemp.readyState == 4) {
                if (xhrTemp.status == 200) {
                    if (successFunc != null) {
                        successFunc(xhrTemp.responseText, xhrTemp.responseXML);
                    }
                }
                else {
                    if (failFunc != null) {
                        failFunc(xhrTemp.status);
                    }
                }
            }
        }
    },
    get: function (url, async) {
        this.xhr.open("GET", url, async);
        this.xhr.send();
    },
    createXMLHttpRequest: function () {
        if (window.XMLHttpRequest) {
            return new XMLHttpRequest();
        }
        else {
            return new ActiveXObject("Microsoft.XMLHTTP");
        }

        throw new Error("Ajax is not supported by the browser!");
    },
    post: function (url, data, async) {
        this.xhr.open("POST", url, async);
        this.xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        this.xhr.send(data);
    }
}