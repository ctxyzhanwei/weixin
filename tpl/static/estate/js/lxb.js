window.lxb = window.lxb || {};
lxb.instance = lxb.instance || 0;
lxb.instance++; (function() {
    var a = {};
    lxb.add = lxb.add ||
    function(b, d) {
        var c = a[b];
        if (!c) {
            c = a[b] = {};
            d.call(null, c)
        }
    };
    lxb.use = lxb.use ||
    function(b) {
        if (typeof b == "string") {
            if (!a[b]) {
                throw "no module: " + b
            } else {
                return a[b]
            }
        } else {
            b.call(null, a)
        }
    }
})();
lxb.add("util",
function(e) {
    var c = null;
    e.init = function(l) {
        c = l
    };
    e.css = function(m, l, n) {
        if (!m) {
            return
        }
        if (n !== undefined) {
            m.style[l] = n
        } else {
            try {
                if (window.getComputedStyle) {
                    return window.getComputedStyle(m)[l]
                } else {
                    if (m.currentStyle) {
                        return m.currentStyle[l]
                    }
                }
            } catch(o) {}
        }
    };
    e.isStandard = function() {
        return c.styleType == 1
    };
    e.isCustom = function() {
        return c.styleType == 2
    };
    e.isHorizon = function() {
        if (c.styleType == 1) {
            return c.style < 1000
        }
        return c.windowLayout == 1
    };
    e.isVertical = function() {
        if (c.styleType == 1) {
            return c.style > 1000
        }
        return c.windowLayout == 2
    };
    e.isLeft = function() {
        return c.position > 0
    };
    e.isRight = function() {
        return c.position < 0
    };
    e.displayGroup = function() {
        return c.ifGroup && (c.windowLayout == 2)
    };
    e.display400 = function() {
        return c.float_window == 1 || c.float_window == 3
    };
    e.displayCallback = function() {
        return c.float_window == 2 || c.float_window == 3
    };
    e.displayLink = function() {
        return c.inviteInfo.linkStatus == 1
    };
    e.isVisible = function(l) {
        return l.style.visibility == "visible"
    };
    var a = null;
    var b = null;
    e.visitorLog = function(r, l) {
        var n = 512;
        var o = lxb.use("config");
        if (!o.lxbvt) {
            return
        }
        var m = "http://lxbjs.baidu.com/vt/lxb.gif";
        var s = (document.title || "").substr(0, n);
        var u = (document.referrer || "").substr(0, n);
        var v = (document.URL || "").substr(0, n);
        var w = o.bdcbid;
        var q = [];
        q.push(encodeURIComponent(r || ""));
        q.push(encodeURIComponent(s || ""));
        q.push(encodeURIComponent(u || ""));
        q.push(encodeURIComponent(v || ""));
        q.push( + f());
        var t = g(q.join(","));
        var p = function() {
            if (!a) {
                a = document.createElement("div");
                a.style.display = "none"
            }
            a.innerHTML = '<form action="' + m + '" method="post" target="lxbHideIframe"><input name="p" value="' + t + '"/><input name="sid" value="' + l + '"/><input name="bdcbid" value="' + w + '"/><input name="t" value="' + (new Date()).valueOf() + '"/></form><iframe id="lxbHideIframe" name="lxbHideIframe" src="about:blank"></iframe>';
            if (document.body) {
                document.body.appendChild(a);
                b = a.getElementsByTagName("form")[0];
                b.submit()
            }
        };
        if (!document.body) {
            window.onload = p
        } else {
            p()
        }
    };
    var j = e.getDomain = function(l) {
        l = l.replace(/^https?:\/\//, "").split("/");
        return l[0].replace(/:.*$/, "")
    };
    var d = function(m) {
        m = m.replace(/\r\n/g, "\n");
        var l = "";
        for (var p = 0; p < m.length; p++) {
            var o = m.charCodeAt(p);
            if (o < 128) {
                l += String.fromCharCode(o)
            } else {
                if ((o > 127) && (o < 2048)) {
                    l += String.fromCharCode((o >> 6) | 192);
                    l += String.fromCharCode((o & 63) | 128)
                } else {
                    l += String.fromCharCode((o >> 12) | 224);
                    l += String.fromCharCode(((o >> 6) & 63) | 128);
                    l += String.fromCharCode((o & 63) | 128)
                }
            }
        }
        return l
    };
    _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var g = e.base64 = function(n) {
        var l = "";
        var u, s, q, t, r, p, o;
        var m = 0;
        n = d(n);
        while (m < n.length) {
            u = n.charCodeAt(m++);
            s = n.charCodeAt(m++);
            q = n.charCodeAt(m++);
            t = u >> 2;
            r = ((u & 3) << 4) | (s >> 4);
            p = ((s & 15) << 2) | (q >> 6);
            o = q & 63;
            if (isNaN(s)) {
                p = o = 64
            } else {
                if (isNaN(q)) {
                    o = 64
                }
            }
            l = l + _keyStr.charAt(t) + _keyStr.charAt(r) + _keyStr.charAt(p) + _keyStr.charAt(o)
        }
        return l
    };
    var i = function(n, o, p, l) {
        var q = n + "=" + o;
        if (p) {
            q += "; path=" + p
        }
        if (l) {
            var m = new Date();
            m.setTime(m.getTime() + l * 24 * 3600 * 1000);
            q += "; expires=" + m.toGMTString()
        }
        document.cookie = q
    };
    var h = function(m) {
        var n = new RegExp("(^| )" + m + "=([^;]*)(;|\x24)");
        var l = n.exec(document.cookie);
        if (l) {
            return l[2] || null
        } else {
            return null
        }
    };
    var k = -1;
    var f = e.isLoadPage = function() {
        if (k !== -1) {
            return k
        }
        var l = j(window.location.href);
        var m = j(document.referrer);
        if (document.referrer) {
            if (l === m) {
                k = false;
                return k
            } else {
                k = true;
                return k
            }
        } else {
            if (h("isLoadPage") === "loaded") {
                k = false;
                return k
            } else {
                i("isLoadPage", "loaded", "/");
                k = true;
                return k
            }
        }
    }
});
lxb.add("base",
function(d) {
    var c = /msie (\d+\.\d+)/i.test(navigator.userAgent) ? (document.documentMode || +RegExp["\x241"]) : undefined;
    d.ie = c;
    d.g = function(e) {
        return document.getElementById(e)
    };
    var m = {};
    if (c < 8) {
        m["class"] = "className";
        m.maxlength = "maxLength"
    } else {
        m.className = "class";
        m.maxLength = "maxlength"
    }
    d.create = function(r, q) {
        var t = document.createElement(r);
        var e;
        if (q) {
            for (var s in q) {
                if (q.hasOwnProperty(s)) {
                    e = m[s] || s;
                    t.setAttribute(e, q[s])
                }
            }
        }
        return t
    };
    d.jsonToQuery = function(e) {
        var r = [];
        for (var q in e) {
            if (e.hasOwnProperty(q)) {
                r.push(q + "=" + encodeURIComponent(e[q]))
            }
        }
        return r.join("&")
    };
    d.extend = function(r, q) {
        for (var e in q) {
            if (q.hasOwnProperty(e)) {
                r[e] = q[e]
            }
        }
        return r
    };
    d.encodeHTML = function(e) {
        return e.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;")
    };
    var g = new RegExp("(^[\\s\\t\\xa0\\u3000]+)|([\\u3000\\xa0\\s\\t]+\x24)", "g");
    d.trim = function(e) {
        return e.replace(g, "")
    };
    d.filter = function(e) {
        e = e.replace(/\s+/, "");
        if (/^http:\/\/|^https:\/\//i.test(e)) {
            return e
        } else {
            e = e.replace(/^[^:]+:/gi, "")
        }
        return e
    };
    d.queryToJSON = function(s) {
        var q = {};
        s = s.split("&");
        for (var e = 0,
        r; r = s[e]; e++) {
            r = r.split("=");
            if (r[0]) {
                q[r[0]] = r[1]
            }
        }
        return q
    };
    d.setCookie = function(r, s, t, e) {
        var u = r + "=" + s;
        if (t) {
            u += "; path=" + t
        }
        if (e) {
            var q = new Date();
            q.setTime(q.getTime() + e * 24 * 3600 * 1000);
            u += "; expires=" + q.toGMTString()
        }
        document.cookie = u
    };
    d.getCookie = function(q) {
        var r = new RegExp("(^| )" + q + "=([^;]*)(;|\x24)");
        var e = r.exec(document.cookie);
        if (e) {
            return e[2] || null
        } else {
            return null
        }
    };
    var p = [];
    var l;
    var k = false;
    function n() {
        if (!k) {
            k = true;
            for (var q = 0,
            e = p.length; q < e; q++) {
                p[q]()
            }
        }
    }
    function f() {
        try {
            document.documentElement.doScroll("left")
        } catch(q) {
            setTimeout(f, 1);
            return
        }
        n()
    }
    if (document.addEventListener) {
        l = function() {
            document.removeEventListener("DOMContentLoaded", l, false);
            n()
        }
    } else {
        if (document.attachEvent) {
            l = function() {
                if (document.readyState === "complete") {
                    document.detachEvent("onreadystatechange", l);
                    n()
                }
            }
        }
    }
    if (document.readyState === "complete") {
        k = true
    } else {
        if (document.addEventListener) {
            document.addEventListener("DOMContentLoaded", l, false);
            window.addEventListener("load", n, false)
        } else {
            if (document.attachEvent) {
                document.attachEvent("onreadystatechange", l);
                window.attachEvent("onload", n);
                var o = false;
                try {
                    o = window.frameElement == null
                } catch(h) {}
                if (document.documentElement.doScroll && o) {
                    f()
                }
            }
        }
    }
    d.ready = function(e) {
        k ? e() : p.push(e)
    };
    var b = ["", "4-3-3", "3-4-3", "3-3-4"];
    d.formatTel = function(e, t) {
        var u = b[parseInt(t, 10)];
        var r = [];
        if (!u) {
            return e
        }
        e = e.split("");
        u = u.split("-");
        for (var q = 0,
        s; s = u[q]; q++) {
            r.push(e.splice(0, parseInt(s, 10)).join(""))
        }
        return r.join("-")
    };
    var a = [];
    function j() {
        for (var e = 0,
        q; q = a[e]; e++) {
            i(q)
        }
    }
    function i(q) {
        var e = document.compatMode == "CSS1Compat" ? document.documentElement: document.body;
        var r = q.ele;
        var t;
        var s;
        if (q.top === t) {
            s = r.style.top || r.currentStyle.top;
            if (!s || s == "auto") {
                s = r.style.bottom || r.currentStyle.bottom;
                if (s && s.indexOf("%") >= 0) {
                    s = e.clientHeight * (100 - parseInt(s, 10)) / 100 - r.offsetHeight
                } else {
                    if (s == "auto") {
                        s = t
                    } else {
                        if (s) {
                            s = e.clientHeight - r.offsetHeight - parseInt(s, 10)
                        }
                    }
                }
            }
            if (s) {
                if (typeof s == "string" && s.indexOf("%") >= 0) {
                    s = e.clientHeight * parseInt(s, 10) / 100
                } else {
                    s = parseInt(s, 10)
                }
                q.top = s
            } else {
                q.top = t
            }
        }
        if (q.top !== t) {
            r.style.top = e.scrollTop + q.top + "px"
        }
    }
    d.setFixed = function(e) {
        if (a.length <= 0) {
            window.attachEvent("onscroll", j);
            window.attachEvent("onresize", j)
        }
        e.style.position = "absolute";
        a.push({
            ele: e
        });
        i(a[a.length - 1])
    };
    d.addClass = function(q, e) {
        var s = q.className;
        if (!q) {
            return
        }
        var r = new RegExp(e);
        if (!r.test(q.className)) {
            s = q.className + " " + e
        }
        q.className = s.replace(/\s+/, " ").replace(/^\s|\s$/, "")
    };
    d.removeClass = function(q, e) {
        var s = q.className;
        if (!q) {
            return
        }
        var r = new RegExp(e);
        if (r.test(q.className)) {
            s = q.className.replace(r, "")
        }
        q.className = s.replace(/\s+/, " ").replace(/^\s|\s$/, "")
    };
    d.q = function(t, s) {
        var q = [],
        e,
        r,
        v,
        u;
        if (! (t = t.replace(/\s+/, ""))) {
            return q
        }
        if ("undefined" == typeof s) {
            s = document
        }
        if (s.getElementsByClassName) {
            v = s.getElementsByClassName(t);
            e = v.length;
            for (r = 0; r < e; r++) {
                u = v[r];
                q[q.length] = u
            }
        } else {
            t = new RegExp("(^|\\s)" + t + "(\\s|\x24)");
            v = s.all || s.getElementsByTagName("*");
            e = v.length;
            for (r = 0; r < e; r++) {
                u = v[r];
                t.test(u.className) && (q[q.length] = u)
            }
        }
        return q
    };
    d.viewportSize = function() {
        if (document.compatMode == "BackCompat") {
            return {
                width: document.body.clientWidth,
                height: document.body.clientHeight
            }
        } else {
            return {
                width: document.documentElement.clientWidth,
                height: document.documentElement.clientHeight
            }
        }
    }
});
lxb.add("config",
function(a) {
    var c = lxb.use("base");
    var e = {
        BDCBID: '658ef0e8-f770-4e1c-936c-0922687a4437',
        LXBVT: 0,
        TEMPSITEID: '3049852',
        TEMPPORT: 'lxbjs.baidu.com/',
        TEMPFILEROOT: 'float'
    };
    var b = {
        BDCBID: "bdcbid",
        LXBVT: 1,
        TEMPSITEID: "siteid",
        TEMPPORT: "localhost:7710",
        TEMPFILEROOT: "/float"
    };
    c.extend(b, e);
    var d = location.href.indexOf("https://") === 0 ? "https://": "http://";
    a.SiteId = b.TEMPSITEID;
    a.Root = d + b.TEMPPORT + b.TEMPFILEROOT;
    a.lxbvt = b.LXBVT;
    a.bdcbid = b.BDCBID;
    a.Lang = {
        TRAN: "\u8f6c",
        WE: "\u6211\u4eec",
        CB_INPUT_TIP_1: "\u624b\u673a\u8bf7\u76f4\u63a5\u8f93\u5165\uff1a\u59821860086xxxx",
        CB_INPUT_TIP_2: "\u5ea7\u673a\u524d\u52a0\u533a\u53f7\uff1a\u59820105992xxxx",
        CB_INPUT_TIP_3: "\u8f93\u5165\u60a8\u7684\u7535\u8bdd\u53f7\u7801\uff0c\u70b9\u51fb\u901a\u8bdd\uff0c\u7a0d\u540e\u60a8\u5c06\u63a5\u5230\u6211\u4eec\u7684\u7535\u8bdd\uff0c\u8be5\u901a\u8bdd\u5bf9\u60a8<em>\u5b8c\u5168\u514d\u8d39</em>\uff0c\u8bf7\u653e\u5fc3\u63a5\u542c\uff01",
        CB_INPUT_TIP_HOLDER: "\u8bf7\u8f93\u5165\u60a8\u7684\u7535\u8bdd\u53f7\u7801",
        INVITE_INPUT_TIP_HOLDER: "\u8F93\u5165\u53F7\u7801\u540E\u70B9\u51FB\u4E0B\u5217\u6309\u94AE\uFF0C\u514D\u8D39\u56DE\u7535",
        CB_SUCCESS_TIP_1: "\u7a0d\u540e\u60a8\u5c06\u63a5\u5230",
        CB_SUCCESS_TIP_2: "\u7684\u7535\u8bdd\uff0c<br />\u8be5\u901a\u8bdd\u5bf9\u60a8<em>\u5b8c\u5168\u514d\u8d39</em>\uff0c<br />\u8bf7\u653e\u5fc3\u63a5\u542c\uff01",
        ERROR_CB_PHONE: "\u8bf7\u60a8\u8f93\u5165\u6b63\u786e\u7684\u53f7\u7801\uff0c\u624b\u673a\u53f7\u7801\u8bf7\u76f4\u63a5\u8f93\u5165\uff0c\u5ea7\u673a\u8bf7\u52a0\u533a\u53f7",
        ERROR_CB_FAIL: "\u7cfb\u7edf\u7e41\u5fd9\uff0c\u8bf7\u7a0d\u540e\u91cd\u8bd5",
        LOADING_CB_TIP: "\u62e8\u53f7\u4e2d\uff0c\u8bf7\u7a0d\u5019",
        CB_SUCCESS_TIP_TXT_0: "\u7a0d\u540e\u60a8\u5c06\u63a5\u5230\u6211\u4eec\u7684\u6765\u7535",
        CB_SUCCESS_TIP_TXT_1: "\u6b63\u5728\u547c\u53eb\u60a8\u7684\u7535\u8bdd",
        CB_SUCCESS_TIP_TXT_2: "\u8bf7\u51c6\u5907\u63a5\u542c",
        CB_INFO_TIP_CLOSE: "\u2573"
    };
    a.ClassName = {
        MAIN: "lxb-container",
        TL_PHONE: "lxb-tl-phone",
        TL_PHONE_EM: "lxb-tl-phone-em",
        CB_ICON: "lxb-cb-icon",
        CB_INPUT: "lxb-cb-input",
        CB_INPUT_BTN: "lxb-cb-input-btn",
        CB_INPUT_TIP: "lxb-cb-input-tip",
        CB_INPUT_TIP_CURSOR: "lxb-cb-input-tip-cursor",
        CB_SUCCESS_TIP: "lxb-cb-success-tip",
        CB_LOADING_TIP: "lxb-cb-loading-tip",
        CB_INFO_TIP: "lxb-cb-info-tip",
        INVITE_LINK_CON: "lxb-invite-link-con",
        INVITE_LINK_TEXT: "lxb-invite-link-txt",
        INVITE_LINK_BTN: "lxb-invite-link-btn",
        Position: {
            HOR: ["lxb-pos-left", "lxb-pos-right"],
            VER: ["lxb-pos-top", "lxb-pos-middle", "lxb-pos-bottom"]
        },
        PositionFix: {
            HOR: ["-left", "-right"],
            VER: ["-top", "-middle", "-bottom"]
        }
    };
    a.ID = {
        MAIN: "LXB_CONTAINER",
        SHOW: "LXB_CONTAINER_SHOW",
        IMG_PREV: "LXB_IMG_PREV_",
        COOKIE_DBCLKID: "LXB_DBCLICKID",
        COOKIE_REFER: "LXB_REFER"
    };
    a.TPL = {
        CB_INPUT_TIP_1: '<ins class="lxb-cb-input-tip-mobile">' + a.Lang.CB_INPUT_TIP_1 + '</ins><ins class="lxb-cb-input-tip-tel">' + a.Lang.CB_INPUT_TIP_2 + '</ins><ins class="lxb-cb-input-tip-em">' + a.Lang.CB_INPUT_TIP_3 + '</ins><ins class="lxb-cb-input-tip-cursor"></ins>',
        CB_SUCCESS_TIP_1: '<ins class="lxb-cb-success-tip-inner">',
        CB_SUCCESS_TIP_2: "</ins>",
        CB_LOADING_TIP: '<ins class="lxb-cb-loading-tip-img"></ins><ins class="lxb-cb-loading-tip-txt">' + a.Lang.LOADING_CB_TIP + "</ins>",
        CB_INFO_TIP_MAIN: '<ins class="lxb-cb-info-tip-con"></ins><ins class="lxb-cb-info-tip-arrow"></ins><ins class="lxb-cb-info-tip-close">' + a.Lang.CB_INFO_TIP_CLOSE + "</ins>",
        CB_SUCCESS_TIP_IMG: '<ins class="lxb-cb-success-tip-img"></ins>',
        CB_SUCCESS_TIP_PHONE: '<ins class="lxb-cb-success-tip-phone">',
        CB_SUCCESS_TIP_PHONE_END: "</ins>",
        CB_SUCCESS_TIP_TXT: '<ins class="lxb-cb-success-tip-txt">' + a.Lang.CB_SUCCESS_TIP_TXT_1 + '</ins><ins class="lxb-cb-success-tip-txt">' + a.Lang.CB_SUCCESS_TIP_TXT_2 + "</ins>",
        CB_SUCCESS_TIP_TXT_1: '<ins class="lxb-cb-success-tip-txt">' + a.Lang.CB_SUCCESS_TIP_TXT_0 + '</ins><ins class="lxb-cb-success-tip-txt">' + a.Lang.CB_SUCCESS_TIP_TXT_2 + "</ins>",
        CB_ERROR_TIP_S: '<ins class="lxb-cb-error-tip">',
        CB_ERROR_TIP_E: "</ins>"
    }
});
lxb.add("net",
function(a) {
    var d = lxb.use("base");
    function e(f, g) {
        return function(h) {
            g.call(null, h);
            setTimeout(function() {
                var i = lxb.use("base").g(f);
                i.parentNode.removeChild(i)
            },
            0)
        }
    }
    function b(g, i) {
        var h = document.getElementsByTagName("head")[0];
        var f = d.create("script", {
            type: "text/javascript",
            src: g,
            id: i || "",
            charset: "utf-8"
        });
        h.appendChild(f)
    }
    function c(f) {
        var h = document.getElementsByTagName("head")[0];
        var g = d.create("link", {
            rel: "stylesheet",
            href: f,
            type: "text/css",
            charset: "utf-8"
        });
        h.appendChild(g)
    }
    a.send = function(f, h, j) {
        var g = "_lxb_jsonp_" + new Date().getTime().toString(36) + "_";
        var i = ["t=" + (new Date().getTime())];
        i.push("callback=" + g);
        window[g] = e(g, j);
        h = h || "";
        if (typeof h !== "string") {
            h = d.jsonToQuery(h)
        }
        h += (h ? "&": "") + i.join("&");
        f += (f.indexOf("?") >= 0 ? "&": "?") + h;
        b(f, g)
    };
    a.loadCSS = c;
    a.log = function(f, g) {
        if (window.console && console.log) {
            console.log("[" + f + "]" + g)
        }
    }
});
lxb.add("tip",
function(d) {
    var g = lxb.use("base").ie;
    var f = {};
    var j = preHeight = preTop = preLeft = preTipHeight = 0;
    var k = {};
    var a = function(n, m) {
        for (var l in n) {
            if ((n.hasOwnProperty && n.hasOwnProperty(l)) || (!n.hasOwnProperty)) {
                if (! (l in ["event"])) {
                    m[l] = n[l]
                }
            }
        }
    };
    var i = function() {
        k = {
            arrow: null,
            close: null,
            con: null,
            tipEle: null,
            value: 10
        }
    };
    var b = function() {
        if (document.compatMode == "BackCompat") {
            return {
                width: document.body.clientWidth,
                height: document.body.clientHeight
            }
        } else {
            return {
                width: document.documentElement.clientWidth,
                height: document.documentElement.clientHeight
            }
        }
    };
    var h = function(l) {
        return {
            width: l.offsetWidth,
            height: l.offsetHeight,
            top: l.offsetTop,
            left: l.offsetLeft
        }
    };
    var e = function(l) {
        return {
            width: l.offsetWidth,
            height: l.offsetHeight,
            top: l.offsetTop,
            left: l.offsetLeft
        }
    };
    var c = function() {
        f.body = b();
        f.con = h(k.con);
        f.tip = e(k.tipEle)
    };
    d.init = function(l) {
        i();
        a(l, k)
    };
    d.show = function() {
        k.tipEle.style.display = "";
        c();
        if (preTipHeight != f.tip.height || j != f.con.width || preHeight != f.con.height || preTop != f.con.top || preLeft != f.con.left) {
            d.resetLoc();
            j = f.con.width;
            preHeight = f.con.height;
            preTop = f.con.top;
            preLeft = f.con.left;
            preTipHeight = f.tip.height
        }
    };
    d.resetLoc = function() {
        var l = (f.con.height <= f.tip.height) ? true: false;
        if (f.tip.width > f.con.left) {
            if (f.tip.height > f.con.top) {
                k.arrow.className = "arrow-left-t";
                k.arrow.style.top = "6px";
                k.tipEle.style.top = l ? "22px": "10px"
            } else {
                k.arrow.className = "arrow-left-b";
                k.tipEle.style.top = l ? (f.con.height - f.tip.height - 10) + "px": "0px"
            }
            k.tipEle.style.left = (f.con.left + f.con.width + 7 + k.value) + "px"
        } else {
            if (f.tip.height > f.con.top) {
                k.arrow.className = "arrow-right-t";
                k.arrow.style.top = "6px";
                k.tipEle.style.top = l ? "22px": "10px"
            } else {
                k.arrow.className = "arrow-right-b";
                k.tipEle.style.top = l ? (f.con.height - f.tip.height - 10) + "px": "0px"
            }
            if (document.compatMode == "BackCompat" && g) {
                k.arrow.style.left = "302px"
            }
            k.tipEle.style.left = ( - f.tip.width - 7 - 10) + "px"
        }
    };
    d.hide = function() {
        k.tipEle.style.display = "none"
    }
});
lxb.add("business.replacer",
function(b) {
    function e(j, h, l) {
        var g;
        for (var f = 0,
        k; k = h[f]; f++) {
            g = j.nodeValue.replace(k, l);
            if (g != j.nodeValue) {
                j.nodeValue = g
            }
        }
    }
    function a(h) {
        var g = [];
        if (typeof h == "string") {
            h = [h]
        }
        for (var f = 0,
        j; j = h[f]; f++) {
            if (j.indexOf("@REG:") == 0) {
                g.push(new RegExp(j.substring(5), "g"))
            } else {
                g.push(j)
            }
        }
        return g
    }
    function d(h) {
        var f = [];
        if (h.nodeType == 3) {
            f.push(h)
        } else {
            if (h.nodeType == 1 && (h.className || h.className === "") && h.className.indexOf && h.className.indexOf("lxb-") < 0) {
                for (var g = h.firstChild; g != null; g = g.nextSibling) {
                    f = f.concat(d(g))
                }
            }
        }
        return f
    }
    function c(j, k) {
        var f = d(document.body || document.getElementsByTagName("body")[0]);
        var j = a(j);
        for (var g = 0,
        h; h = f[g]; g++) {
            e(h, j, k)
        }
    }
    b.run = function(j, f, g, k) {
        var i = lxb.use("base");
        var h = lxb.use("config").Lang;
        var l = i.formatTel(f, k) + (g ? h.TRAN + g: "");
        if (!j || j.length <= 0 || !l) {
            return
        }
        i.ready(function() {
            c(j, l)
        })
    }
});
lxb.add("business.container",
function(e) {
    var c = lxb.use("base");
    var h = lxb.use("config").ClassName;
    var i = lxb.use("config").ID;
    var g = lxb.use("util");
    var a = function() {};
    var f = {};
    var k = {};
    var j = 0;
    function l() {
        var n = k.main;
        var o = k.mask;
        var m = n.style.height || (n.currentStyle ? n.currentStyle.height: "");
        if (!m || m == "auto") {
            setTimeout(l, 300)
        } else {
            o.style.height = m;
            o.style.width = n.style.width || (n.currentStyle ? n.currentStyle.width: "100%");
            n.style.zoom = 1
        }
    }
    function b() {
        var w = h.MAIN;
        var s = c.create("ins", {
            id: i.MAIN
        });
        var q = k.main = c.create("ins");
        s.appendChild(q);
        q.style.visibility = "hidden";
        if (f.floatColor) {
            q.style.backgroundColor = f.floatColor
        }
        if (f.imagePath) {
            q.style.backgroundImage = 'url("' + f.imagePath + '")'
        }
        var x = h.MAIN + "-" + f.style + "-" + f.type;
        var m = 0;
        var t = 0;
        if (f.position > 0) {
            m = 1
        }
        var u = "";
        if (g.isHorizon()) {
            u = "lxb-vertical"
        } else {
            if (g.isVertical()) {
                u = "lxb-horizen"
            }
        }
        t = Math.abs(f.position);
        var v = h.Position.HOR[m];
        x += " " + x + h.PositionFix.HOR[m];
        q.className = w + " " + x + " " + v + " " + u;
        if (t <= 45) {
            q.className += " " + h.Position.VER[0]
        } else {
            q.className += " " + h.Position.VER[1]
        }
        var p = k.btnHide = c.create("ins", {
            className: h.MAIN + "-btn-hide"
        });
        q.appendChild(p);
        p = k.btnShow = c.create("ins");
        w = h.MAIN + "-btn-show";
        w += " " + h.MAIN + "-btn-show-" + f.style;
        w += " " + v;
        w += " " + h.MAIN + "-btn-show-" + f.style + h.PositionFix.HOR[m];
        p.className = w;
        var o = c.create("ins", {
            id: i.SHOW
        });
        o.appendChild(p);
        p.style.display = "none";
        btnBg = k.btnShowBg = c.create("ins", {
            className: h.MAIN + "-btn-show-bg"
        });
        if (! (g.isStandard() && g.isHorizon())) {
            if (f.floatColor) {
                p.style.backgroundColor = f.floatColor
            }
            if (f.imagePath) {
                p.style.backgroundImage = 'url("' + f.imagePath + '")'
            }
        }
        p.appendChild(btnBg);
        var r = function(z) {
            var y = g.css(q, "zIndex");
            if (y > 0) {
                z.call(null)
            } else {
                setTimeout(function() {
                    r(z)
                },
                30)
            }
        };
        var n = c.viewportSize();
        r(function() {
            j = parseInt(g.css(q, "height"));
            if (g.isVertical()) {
                if (g.displayGroup()) {
                    j = 190 + 25 * f.groupDetail.length
                } else {
                    if (g.isCustom()) {
                        j = 220
                    }
                }
            }
            if (g.isCustom() && g.isHorizon()) {
                j = 90
            }
            q.style.height = j + "px";
            t = (t == 1 ? 0 : t);
            k.btnShow.style.top = q.style.top = (t / 100 * (n.height - j)) + "px";
            q.style.visibility = "visible";
            a(j);
            if (c.ie <= 6 || (c.ie == 7 && document.compatMode != "CSS1Compat")) {
                c.setFixed(p);
                p.style.display = "none";
                c.setFixed(q)
            }
        });
        c.ready(function() {
            if (c.ie && c.ie <= 6) {
                k.mask = c.create("iframe", {
                    frameBorder: 0,
                    className: h.MAIN + "-mask"
                });
                q.appendChild(k.mask);
                p.appendChild(k.btnShowMask = c.create("iframe", {
                    frameBorder: 0,
                    className: h.MAIN + "-mask"
                }))
            }
            document.body.appendChild(o);
            document.body.appendChild(s);
            if (!c.ie) {
                return
            }
            if (c.ie <= 6) {
                l();
                k.btnShowMask.contentWindow.document.open();
                k.btnShowMask.contentWindow.document.write('<html><head></head><body style="padding:0px;margin:0px;height:100%;width:100%"></body></html>');
                k.btnShowMask.contentWindow.document.close()
            }
        })
    }
    function d() {
        k.main.onkeypress = k.main.onkeydown = k.main.onkeyup = k.main.onmousedown = k.main.onmouseup = k.main.onclick = function(n) {
            n = n || window.event;
            if (n.stopPropagation) {
                n.stopPropagation()
            } else {
                n.cancelBubble = true
            }
        };
        k.btnHide.onclick = function() {
            k.main.style.display = "none";
            k.btnShow.style.display = ""
        };
        function m() {
            k.btnShow.style.display = "none";
            k.main.style.display = ""
        }
        if (k.btnShowMask) {
            k.btnShowMask.contentWindow.document.onclick = m
        }
        k.btnShow.onclick = m
    }
    e.init = function(m, n) {
        a = n;
        c.extend(f, m);
        b();
        d();
        return {
            main: k.main,
            btnShow: k.btnShow
        }
    }
});
lxb.add("business.custom",
function(a) {
    var b = lxb.use("config").ClassName;
    a.init = function(d) {
        var c = d.main;
        var f = d.btnShow;
        var e = b.MAIN + "-custom";
        e += " " + b.MAIN + "-custom-" + d.type + "-" + (d.windowLayout == 1 ? "h": "v");
        e += " " + b.MAIN + "-custom-" + (d.windowLayout == 1 ? "h": "v") + (Math.abs(d.position) == 50 ? b.PositionFix.VER[1] : "");
        c.className += " " + e
    }
});
lxb.add("business.tel",
function(a) {
    var e = lxb.use("base");
    var f = lxb.use("config").ClassName;
    var d = lxb.use("config").Lang;
    var b = {};
    function c() {
        var h = e.create("ins", {
            className: f.TL_PHONE
        });
        var i = e.formatTel(e.encodeHTML(b.phone), b.format);
        if (b.mode == 1 && b.ext) {
            i += ' <em class="' + f.TL_PHONE_EM + '">' + d.TRAN + "</em>" + e.encodeHTML(b.ext)
        }
        h.innerHTML = i;
        var g = h.getElementsByTagName("em")[0];
        if (b.telFontcolor) {
            h.style.color = b.telFontcolor
        }
        if (b.telFontfamily !== undefined && b.styleType != 1) {
            switch (b.telFontfamily - 0) {
            case 0:
                h.style.fontFamily = "\u5b8b\u4f53";
                if (g) {
                    g.style.fontFamily = "\u5b8b\u4f53"
                }
                break;
            case 1:
                h.style.fontFamily = "\u9ed1\u4f53";
                if (g) {
                    g.style.fontFamily = "\u9ed1\u4f53"
                }
                break;
            case 2:
                h.style.fontFamily = "\u5fae\u8f6f\u96c5\u9ed1";
                if (g) {
                    g.style.fontFamily = "\u5fae\u8f6f\u96c5\u9ed1"
                }
                break;
            default:
                h.style.fontFamily = "\u5b8b\u4f53";
                if (g) {
                    g.style.fontFamily = "\u5b8b\u4f53"
                }
            }
        }
        if (b.telFontsize) {
            h.style.fontSize = b.telFontsize + "px";
            if (g) {
                g.style.fontSize = b.telFontsize + "px"
            }
        }
        if (b.telFontcolor) {
            h.style.color = b.telFontcolor;
            if (g) {
                g.style.color = b.telFontcolor
            }
        }
        b.main.appendChild(h)
    }
    a.init = function(g) {
        e.extend(b, g);
        c()
    }
});
lxb.add("business.callback",
function(q) {
    var i = lxb.use("base");
    var h = lxb.use("config").Lang;
    var d = lxb.use("config").TPL;
    var f = lxb.use("config").ClassName;
    var o = lxb.use("config").SiteId;
    var b = lxb.use("util");
    var g = {};
    var k = {};
    var l = "";
    function r() {
        l = h.CB_INPUT_TIP_HOLDER;
        if (b.displayGroup()) {
            l = h.INVITE_INPUT_TIP_HOLDER
        }
        var u = k.input = i.create("input", {
            type: "text",
            name: "phone",
            className: f.CB_INPUT,
            maxlength: 12,
            value: l
        });
        var z = k.cbCon = i.create("ins", {
            className: "lxb-callback-container"
        });
        k.cbCon.appendChild(u);
        var w = k.btn = i.create("ins", {
            className: f.CB_INPUT_BTN
        });
        w.innerHTML = i.encodeHTML(g.btnFontContent || "");
        if (! (b.isStandard() && b.isHorizon())) {
            w.style.color = g.btnfontColor;
            w.style.backgroundColor = g.btnColor
        }
        k.cbCon.appendChild(w);
        if (b.displayGroup()) {
            w.style.display = "none";
            var y = k.groupContainer = i.create("ins", {
                className: "lxb-group-container"
            });
            k.cbCon.appendChild(y);
            for (var v = 0; v < g.groupDetail.length; v++) {
                var A = i.create("ins", {
                    groupid: g.groupDetail[v].groupid,
                    title: "\u514D\u8D39\u56DE\u7535",
                    className: "lxb-group-btn"
                });
                A.innerHTML = i.encodeHTML(g.groupDetail[v].groupname);
                A.style.color = g.btnfontColor;
                A.style.backgroundColor = g.btnColor;
                y.appendChild(A)
            }
        }
        var x = k.tip = i.create("ins", {
            className: f.CB_INPUT_TIP
        });
        var t = lxb.use("config").TPL;
        x.style.display = "none";
        x.innerHTML = t.CB_INPUT_TIP_1;
        if (i.ie && i.ie <= 6) {
            x.appendChild(i.create("iframe", {
                className: f.MAIN + "-mask",
                frameBorder: 0
            }))
        }
        g.main.appendChild(z);
        g.main.appendChild(x)
    }
    var e = lxb.use("tip");
    function p() {
        var u = k.infoLayer;
        u = k.infoLayer = i.create("ins", {
            className: f.CB_INFO_TIP
        });
        u.innerHTML = d.CB_INFO_TIP_MAIN;
        var t = u.getElementsByTagName("ins");
        k.tipCon = t[0];
        k.tipOpt = {
            arrow: t[1],
            close: t[2],
            con: g.main,
            tipEle: u
        };
        t[2].onclick = function() {
            e.hide();
            clearTimeout(g.successTimer)
        };
        g.main.appendChild(u)
    }
    function n(t) {
        if (!k.infoLayer) {
            p()
        }
        k.tipCon.innerHTML = d.CB_ERROR_TIP_S + i.encodeHTML(t) + d.CB_ERROR_TIP_E;
        e.init(k.tipOpt);
        e.show()
    }
    function s(t) {
        if (!k.infoLayer) {
            p()
        }
        if (t.order == "0") {
            k.tipCon.innerHTML = d.CB_SUCCESS_TIP_IMG + d.CB_SUCCESS_TIP_PHONE + i.encodeHTML(t.cbPhone) + d.CB_SUCCESS_TIP_PHONE_END + d.CB_SUCCESS_TIP_TXT
        } else {
            k.tipCon.innerHTML = d.CB_SUCCESS_TIP_IMG + d.CB_SUCCESS_TIP_TXT_1
        }
        e.init(k.tipOpt);
        e.show();
        if (t.order == "0") {
            g.successTimer = setTimeout(function() {
                e.hide()
            },
            5000)
        } else {
            g.successTimer = setTimeout(function() {
                e.hide()
            },
            30000)
        }
    }
    function c() {
        var t = function(u) {
            k.input.blur();
            if (g.successTimer) {
                e.hide();
                clearTimeout(g.successTimer)
            }
            var v = k.input.value = i.trim(k.input.value);
            if (!m(v)) {
                return
            }
            j(v, u)
        };
        if (b.displayGroup()) {
            k.groupContainer.onclick = function(w) {
                w = w || window.event;
                var v = w.srcElement || w.target;
                if (/lxb\-group\-btn/.test(v.className.toLowerCase())) {
                    var u = v.getAttribute("groupid");
                    t(u)
                }
            }
        } else {
            k.btn.onclick = function() {
                t()
            }
        }
        k.input.onfocus = function() {
            k.tip.style.display = "";
            if (this.value == l) {
                this.value = ""
            }
            k.loadingLayer && (k.loadingLayer.style.display = "none");
            if (g.successTimer) {
                e.hide();
                clearTimeout(g.successTimer)
            }
        };
        k.input.onblur = function() {
            k.tip.style.display = "none";
            if (i.trim(this.value) == "") {
                this.value = l
            }
        };
        if (!b.displayGroup()) {
            k.input.onkeyup = function(u) {
                u = u || window.event;
                if (u.keyCode == 13) {
                    k.btn.onclick()
                }
            }
        }
    }
    function a() {
        var t = k.loadingLayer;
        if (!t) {
            t = k.loadingLayer = i.create("ins", {
                className: f.CB_LOADING_TIP
            });
            t.style.display = "none";
            g.main.appendChild(t)
        }
        t.innerHTML = d.CB_LOADING_TIP;
        t.style.display = ""
    }
    function m(u) {
        var t = true;
        if (!/^\d{11,12}$/.test(u)) {
            t = false;
            n(h.ERROR_CB_PHONE)
        }
        return t
    }
    function j(w, v) {
        var x = lxb.use("net");
        var u = lxb.use("config").Root + "/_c.js";
        var t = lxb.use("config").bdcbid;
        if (g.submitTimer) {
            return
        }
        a();
        g.submitTimer = setTimeout(function() {
            g.submitTimer = null
        },
        5000);
        var y = {
            vtel: w,
            siteid: g.siteid,
            bdcbid: t,
            code: g.code
        };
        if (v) {
            y.g = v
        }
        x.send(u, y,
        function(z) {
            k.loadingLayer.style.display = "none";
            if ( !! z.status) {
                var B = z.msg || h.ERROR_CB_FAIL;
                n(B + " ( code: " + z.status + " )")
            } else {
                var A = {};
                A.order = z.order;
                A.cbPhone = z.cbPhone;
                s(A);
                k.input.value = l
            }
            if (g.submitTimer) {
                clearTimeout(g.submitTimer);
                g.submitTimer = null
            }
        });
        b.visitorLog(2, o)
    }
    q.init = function(t) {
        i.extend(g, t);
        r();
        c()
    }
});
lxb.add("business.invite",
function(d) {
    var b = lxb.use("config");
    var a = lxb.use("base");
    var f = lxb.use("util");
    var i = null;
    var e = null;
    var j = [];
    var h = 0;
    var k = function() {
        this.transList = []
    };
    k.prototype = {
        begin: function() {
            this.transList.push("begin")
        },
        add: function(l) {
            this.transList.push(l)
        },
        commit: function() {
            var l = this.transList.pop();
            while (l !== "begin") {
                l = this.transList.pop()
            }
        },
        rollback: function() {
            var l = this.transList.pop();
            while (l && (l !== "begin")) {
                if (typeof l === "function") {
                    l.call(null)
                }
                l = this.transList.pop()
            }
        },
        addClass: function(m, l) {
            a.addClass(m, l);
            this.add(function() {
                a.removeClass(m, l)
            })
        },
        addElement: function(m, l) {
            if (!m || !l) {
                return
            }
            l.appendChild(m);
            this.add(function() {
                l.removeChild(m)
            })
        },
        addElementToFirst: function(n, m) {
            if (!n || !m) {
                return
            }
            var l = m.getElementsByTagName("*")[0];
            if (l) {
                m.insertBefore(n, l)
            } else {
                m.appendChild(n)
            }
            this.add(function() {
                m.removeChild(n)
            })
        },
        changeText: function(l, n) {
            var m = l.innerHTML;
            l.innerHTML = n;
            this.add(function() {
                l.innerHTML = m
            })
        },
        setStyle: function(m, l, n) {
            if (n === undefined) {
                return
            }
            if (l == "float") {
                try {
                    if (m.style[l]) {
                        l = "float"
                    } else {
                        l = "cssFloat"
                    }
                    if (a.ie) {
                        l = "styleFloat"
                    }
                } catch(o) {}
            }
            var p = m.style[l];
            m.style[l] = n;
            this.add(function() {
                m.style[l] = p
            })
        },
        setStyleFromOptions: function(l, o) {
            for (var p in l) {
                if (!l.hasOwnProperty(p)) {
                    continue
                }
                var r = l[p];
                var n = a.q(p, o);
                for (var m = 0; m < n.length; m++) {
                    for (var q in r) {
                        if (!r.hasOwnProperty(q)) {
                            continue
                        }
                        this.setStyle(n[m], q, r[q])
                    }
                }
            }
        }
    };
    k.prototype.constructor = k;
    var c = new k();
    d.init = function(n) {
        i = n.main;
        e = n;
        e.vertical = Math.abs(e.vertical);
        e.vertical = (e.vertical == 1 ? 0 : e.vertical);
        var m = a.q("lxb-cb-input")[0];
        if (!m) {
            return
        }
        var o = a.q("lxb-cb-input-btn")[0];
        var l = function() {
            a.setCookie("isCalled", "called", "/");
            j = []
        };
        var p = function(q) {
            q = q || window.event;
            if (q.keycode === 13) {
                l()
            }
            h = (new Date()).valueOf()
        };
        if (document.addEventListener) {
            m.addEventListener("keydown", p, false);
            m.addEventListener("mousedown", p, false);
            o.addEventListener("click", l, false)
        } else {
            if (document.attachEvent) {
                m.attachEvent("onkeydown", p);
                m.attachEvent("onmousedown", p);
                o.attachEvent("onclick", l)
            }
        }
        if (a.getCookie("isCalled") === "called") {
            return
        }
        if (e.status === 0) {
            return
        } else {
            if (e.status === 1) {
                if (e.ifStartPage === 0) {
                    d.schedule();
                    return
                } else {
                    if (e.ifStartPage === 1) {
                        if (f.isLoadPage()) {
                            d.schedule();
                            return
                        }
                    }
                }
            }
        }
    };
    var g = function() {
        var l = j.shift();
        if (!l) {
            return
        }
        var m = function() {
            var n = (new Date()).valueOf();
            if (n - h < 3000) {
                setTimeout(m, 3000)
            } else {
                l.callback.call(null)
            }
        };
        setTimeout(m, l.delay * 1000)
    };
    d.schedule = function() {
        var l = e.stayTime;
        var p = e.inviteTimes;
        var n = e.inviteInterval;
        var m = e.closeTime || 99999;
        j.push({
            delay: l,
            callback: function() {
                d.invite()
            }
        });
        j.push({
            delay: m,
            callback: function() {
                d.minimize()
            }
        });
        p--;
        for (var o = 0; o < p; o++) {
            j.push({
                delay: n,
                callback: function() {
                    d.invite()
                }
            });
            j.push({
                delay: m,
                callback: function() {
                    d.minimize()
                }
            })
        }
        g()
    };
    d.getOptions = function() {
        var l = {
            "lxb-invite": {
                marginTop: (function() {
                    var m = 0;
                    var n = 0;
                    if (e.background == 1) {
                        m = e.height;
                        n = e.width
                    } else {
                        m = e.imgHeight;
                        n = e.imgWidth
                    }
                    switch (e.position - 0) {
                    case 0:
                        return "-" + m / 2 + "px";
                    case 1:
                        return "0px";
                    case 2:
                        return "0px"
                    }
                })(),
                marginBottom: (function() {
                    var m = 0;
                    var n = 0;
                    if (e.background == 1) {
                        m = e.height;
                        n = e.width
                    } else {
                        m = e.imgHeight;
                        n = e.imgWidth
                    }
                    switch (e.position - 0) {
                    case 0:
                        return "-" + m / 2 + "px";
                    case 1:
                        return "0px";
                    case 2:
                        return "0px"
                    }
                })(),
                marginLeft: (function() {
                    var m = 0;
                    var n = 0;
                    if (e.background == 1) {
                        m = e.height;
                        n = e.width
                    } else {
                        m = e.imgHeight;
                        n = e.imgWidth
                    }
                    switch (e.position - 0) {
                    case 0:
                        return "-" + n / 2 + "px";
                    case 1:
                        return "0px";
                    case 2:
                        return "0px"
                    }
                })(),
                marginRight: (function() {
                    var m = 0;
                    var n = 0;
                    if (e.background == 1) {
                        m = e.height;
                        n = e.width
                    } else {
                        m = e.imgHeight;
                        n = e.imgWidth
                    }
                    switch (e.position - 0) {
                    case 0:
                        return "-" + n / 2 + "px";
                    case 1:
                        return "0px";
                    case 2:
                        return "0px"
                    }
                })(),
                left: (function() {
                    switch (e.position - 0) {
                    case 0:
                        return "50%";
                    case 1:
                        return "0px";
                    case 2:
                        return "auto"
                    }
                })(),
                right: (function() {
                    switch (e.position - 0) {
                    case 0:
                        return "auto";
                    case 1:
                        return "auto";
                    case 2:
                        return "0px"
                    }
                })(),
                top: (function() {
                    switch (e.position - 0) {
                    case 0:
                        return "50%";
                    case 1:
                        return "auto";
                    case 2:
                        return "auto"
                    }
                })(),
                bottom: (function() {
                    switch (e.position - 0) {
                    case 0:
                        return "auto";
                    case 1:
                        return "0px";
                    case 2:
                        return "0px"
                    }
                })(),
                border: "none",
                textAlign: "left",
                width: (function() {
                    var m = 0;
                    if (e.background == 1) {
                        m = e.width
                    } else {
                        m = e.imgWidth
                    }
                    return m + "px"
                })(),
                height: (function() {
                    var m = 0;
                    if (e.background == 1) {
                        m = e.height
                    } else {
                        m = e.imgHeight
                    }
                    return m + "px"
                })(),
                backgroundImage: (function() {
                    if (e.background === 2) {
                        return 'url("' + e.backgroundImg + '")'
                    } else {
                        return "none"
                    }
                })(),
                backgroundColor: (function() {
                    if (e.background === 1) {
                        return e.backgroundColor
                    } else {
                        return "transparent"
                    }
                })(),
                color: "#000",
                borderRadius: "3px",
                mozBorderRadius: "3px",
                webkitBorderRadius: "3px"
            },
            "lxb-tl-phone": {
                width: "auto",
                fontFamily: (function() {
                    switch (e.telFont - 0) {
                    case 0:
                        return "\u5b8b\u4f53";
                    case 1:
                        return "\u9ed1\u4f53";
                    case 2:
                        return "\u5fae\u8f6f\u96c5\u9ed1";
                    default:
                        return "\u5b8b\u4f53"
                    }
                })(),
                textAlign: "center",
                position: "static",
                margin: "10px 10px 0 10px",
                color: e.telColor,
                lineHeight: "1.2em",
                fontSize: e.telSize + "px"
            },
            "lxb-tl-phone-em": {
                fontFamily: (function() {
                    switch (e.telFont - 0) {
                    case 0:
                        return "\u5b8b\u4f53";
                    case 1:
                        return "\u9ed1\u4f53";
                    case 2:
                        return "\u5fae\u8f6f\u96c5\u9ed1";
                    default:
                        return "\u5b8b\u4f53"
                    }
                })(),
                color: e.telColor,
                lineHeight: "1.2em",
                fontSize: e.telSize + "px"
            },
            "lxb-callback-container": {
                textAlign: "center",
                paddingBottom: (function() {
                    if (f.displayGroup()) {
                        if (f.displayLink()) {
                            return "30px"
                        } else {
                            return "3px"
                        }
                    } else {
                        if (f.displayLink()) {
                            return "40px"
                        } else {
                            return "15px"
                        }
                    }
                })()
            },
            "lxb-cb-input": {
                width: "130px",
                height: "22px",
                display: "inline-block",
                _display: "inline",
                _zoom: "1",
                color: "#000",
                margin: 0,
                marginRight: "5px",
                lineHeight: "17px",
                top: "auto",
                left: "50%",
                bottom: "15px",
                position: "static",
                verticalAlign: "middle",
                borderRadius: "0px",
                mozBorderRadius: "0px",
                webkitBorderRadius: "0px",
                backgroundColor: "#fff"
            },
            "lxb-cb-input-btn": {
                display: (function() {
                    if (f.displayGroup()) {
                        return "none"
                    } else {
                        return "inline-block"
                    }
                })(),
                _display: (function() {
                    if (f.displayGroup()) {
                        return "none"
                    } else {
                        return "inline"
                    }
                })(),
                position: "static",
                _zoom: "1",
                verticalAlign: "middle",
                lineHeight: "22px",
                height: "22px",
                border: "none",
                backgroundImage: "none",
                color: e.btnFontColor,
                backgroundColor: e.btnColor,
                margin: "0",
                fontSize: "12px",
                paddingLeft: "5px",
                paddingRight: "5px",
                width: "auto",
                borderRadius: "0px",
                mozBorderRadius: "0px",
                webkitBorderRadius: "0px",
                top: "auto",
                left: "50%",
                bottom: "15px",
                textAlign: "center",
                fontWeight: "normal"
            },
            "lxb-group-btn": {
                "float": "left",
                width: "107px",
                height: "25px",
                lineHeight: "25px",
                margin: "3px",
                color: e.btnFontColor,
                backgroundColor: e.btnColor
            },
            "lxb-container-btn-hide": {
                display: "none"
            },
            "lxb-cb-input-tip": {
                right: "auto",
                left: "0",
                top: "-100px"
            }
        };
        return l
    };
    d.minimize = function() {
        c.rollback();
        g();
        if (a.ie <= 6) {
            var l = a.viewportSize();
            i.style.top = (e.vertical / 100 * (l.height - e.conHeight)) + "px";
            a.setFixed(i)
        }
    };
    d.invite = function() {
        if (a.getCookie("isCalled") === "called") {
            return
        }
        var o = a.q("lxb-cb-input-btn")[0];
        var q = document.createElement("ins");
        q.innerHTML = a.encodeHTML(e.content);
        q.style.fontSize = e.fontSize + "px";
        q.style.lineHeight = "1.2em";
        q.style.fontFamily = (function() {
            switch (e.font - 0) {
            case 0:
                return "\u5b8b\u4f53";
            case 1:
                return "\u9ed1\u4f53";
            case 2:
                return "\u5fae\u8f6f\u96c5\u9ed1";
            default:
                return "\u5b8b\u4f53"
            }
        })();
        q.style.color = e.fontColor;
        q.style.fontWeight = "bold";
        q.style.position = "static";
        q.style.margin = "20px 10px 0 10px";
        var p = document.createElement("ins");
        p.innerHTML = "\u2573";
        p.style.fontSize = "12px";
        p.style.lineHeight = "1.2em";
        p.style.position = "absolute";
        p.style.lineHeight = "1.2em";
        p.style.height = "12px";
        p.style.right = "5px";
        p.style.top = "5px";
        p.style.fontWeight = "bold";
        p.style.fontFamily = "\u5b8b\u4f53";
        p.style.cursor = "pointer";
        p.onclick = function() {
            d.minimize()
        };
        if (f.displayLink()) {
            var r = document.createElement("ins");
            r.className = b.ClassName.INVITE_LINK_CON;
            var n = document.createElement("ins");
            n.className = b.ClassName.INVITE_LINK_TEXT;
            n.innerHTML = a.encodeHTML(e.linkTextContent);
            n.style.color = e.linkTextColor;
            n.style.fontFamily = (function() {
                switch (e.linkTextFont - 0) {
                case 0:
                    return "\u5b8b\u4f53";
                case 1:
                    return "\u9ed1\u4f53";
                case 2:
                    return "\u5fae\u8f6f\u96c5\u9ed1";
                default:
                    return "\u5b8b\u4f53"
                }
            })();
            var l = document.createElement("a");
            l.innerHTML = a.encodeHTML(e.linkBtnContent);
            l.className = b.ClassName.INVITE_LINK_BTN;
            l.style.color = e.linkBtnFontColor;
            l.style.backgroundColor = e.linkBtnBgColor;
            l.href = a.filter(e.linkURL);
            l.target = "_blank";
            r.appendChild(n);
            r.appendChild(l)
        }
        c.begin();
        c.addElementToFirst(q, i);
        c.addElement(p, i);
        f.displayLink() && c.addElement(r, i);
        c.addClass(i, "lxb-invite");
        var m = d.getOptions();
        c.setStyleFromOptions(m);
        if (a.ie <= 6) {
            a.setFixed(i)
        }
        g()
    }
}); (function() {
    var j = lxb.use("util");
    function i(s) {
        var e = {};
        var r = s.float_window;
        var t = 0;
        if (s.inviteInfo) {
            t = s.inviteInfo.status
        }
        if (!r || r == "0") {
            return {}
        }
        e.base = {
            position: s.position,
            groupDetail: s.groupDetail,
            windowLayout: s.windowLayout,
            style: s.style,
            type: r
        };
        if (j.isStandard()) {
            if (j.isVertical()) {
                e.base.floatColor = s.floatColor
            }
        }
        if (j.isCustom()) {
            e.custom = {
                url: s.imagePath,
                windowLayout: s.windowLayout,
                position: s.position,
                type: r
            };
            e.base.style = "custom";
            e.base.imagePath = s.imagePath
        }
        if (j.display400()) {
            e.tel = {
                phone: s.phone,
                mode: s.mode,
                format: s.format,
                ext: s.ext
            };
            if (j.isCustom()) {
                e.tel.telFontcolor = s.telFontcolor;
                e.tel.telFontfamily = s.telFontfamily;
                e.tel.telFontsize = s.telFontsize;
                e.tel.telFontcolor = s.telFontcolor
            }
        }
        if (j.displayCallback()) {
            e.callback = {
                callPhone: s.cbPhone || "",
                style: s.style,
                btnFontContent: s.btnFontContent,
                siteid: s.siteid,
                btnfontColor: s.btnfontColor,
                btnColor: s.btnColor,
                styleType: s.styleType,
                windowLayout: s.windowLayout,
                ifGroup: s.ifGroup,
                groupDetail: s.groupDetail,
                code: s.code
            }
        }
        if (t !== 0) {
            s.inviteInfo = s.inviteInfo || {};
            s.inviteWay = s.inviteWay || {};
            e.invite = {
                ifGroup: s.ifGroup,
                vertical: s.position,
                windowLayout: s.windowLayout,
                status: s.inviteInfo.status || 0,
                content: s.inviteInfo.content || "",
                font: s.inviteInfo.font || 0,
                fontSize: s.inviteInfo.fontSize || 16,
                fontColor: s.inviteInfo.fontColor || "#000000",
                background: s.inviteInfo.background || 1,
                backgroundColor: s.inviteInfo.backgroundColor || "rgb(197, 232, 251)",
                backgroundImg: s.inviteInfo.backgroundImg || "",
                btnColor: s.inviteInfo.btnColor || "rgb(132, 133, 134)",
                btnFontColor: s.inviteInfo.btnFontColor || "#ffffff",
                telFont: s.inviteInfo.telFont || 0,
                telSize: s.inviteInfo.telSize || 18,
                telColor: s.inviteInfo.telColor || "#000000",
                height: s.inviteInfo.height || 140,
                width: s.inviteInfo.width || 230,
                imgHeight: s.inviteInfo.imgHeight || 140,
                imgWidth: s.inviteInfo.imgWidth || 230,
                position: s.inviteInfo.position || 0,
                linkStatus: s.inviteInfo.linkStatus || 0,
                linkURL: s.inviteInfo.linkURL,
                linkTextContent: s.inviteInfo.linkTextContent || "",
                linkTextColor: s.inviteInfo.linkTextColor || "#000000",
                linkTextFont: s.inviteInfo.linkTextFont || 0,
                linkBtnContent: s.inviteInfo.linkBtnContent || "在线交谈",
                linkBtnBgColor: s.inviteInfo.linkBtnBgColor || "#000000",
                linkBtnFontColor: s.inviteInfo.linkBtnFontColor || "#ffffff",
                ifStartPage: s.inviteWay.ifStartPage || 0,
                stayTime: s.inviteWay.stayTime || 0,
                inviteTimes: s.inviteWay.inviteTimes || 1,
                inviteInterval: s.inviteWay.inviteInterval || 0,
                closeTime: s.inviteWay.closeTime || 0,
                siteId: s.inviteWay.siteId
            }
        }
        return e
    }
    function d(x) {
        var w = lxb.use("net");
        var r = lxb.use("util");
        if ( !! x.status) {
            w.log("error", "init");
            return
        }
        var u = x.data;
        r.init(u);
        if (u.replace && u.phone) {
            try {
                var t = u.mode == 1 ? u.ext: "";
                lxb.use("business.replacer").run(u.replace, u.phone, t || "", u.format || "1")
            } catch(v) {
                w.log("error", "replace")
            }
        }
        if (u.style <= 5 && u.float_window != 1) {
            return
        }
        u = i(u);
        var y;
        if (u.base) {
            var s = lxb.use("config").Root + "/asset/" + u.base.style + ".css";
            w.loadCSS(s);
            y = lxb.use("business.container").init(u.base,
            function(z) {
                if (u.custom) {
                    u.custom.main = y.main;
                    u.custom.btnShow = y.btnShow;
                    lxb.use("business.custom").init(u.custom)
                }
                if (u.tel) {
                    u.tel.main = y.main;
                    lxb.use("business.tel").init(u.tel)
                }
                if (u.callback) {
                    u.callback.main = y.main;
                    lxb.use("business.callback").init(u.callback)
                }
                if (u.invite) {
                    u.invite.main = y.main;
                    u.invite.conHeight = z;
                    var e = lxb.use("business.invite");
                    e.init(u.invite)
                }
            })
        }
    }
    function l() {
        var e = location.search ? location.search.substring(1) : "";
        e = c.queryToJSON(e);
        return e.bdclkid
    }
    function f() {
        var e = document.referrer;
        e = e.replace(/^https?:\/\//, "").split("/");
        return e[0].replace(/:.*$/, "")
    }
    function o(t) {
        var e = "";
        var r = [".com", ".co", ".cn", ".info", ".net", ".org", ".me", ".mobi", ".us", ".biz", ".xxx", ".ca", ".co.jp", ".com.cn", ".net.cn", ".org.cn", ".gov.cn", ".mx", ".tv", ".ws", ".ag", ".com.ag", ".net.ag", ".org.ag", ".am", ".asia", ".at", ".be", ".com.br", ".net.br", ".bz", ".com.bz", ".net.bz", ".cc", ".com.co", ".net.co", ".nom.co", ".de", ".es", ".com.es", ".nom.es", ".org.es", ".eu", ".fm", ".fr", ".gs", ".in", ".co.in", ".firm.in", ".gen.in", ".ind.in", ".net.in", ".org.in", ".it", ".jobs", ".jp", ".ms", ".com.mx", ".nl", ".nu", ".co.nz", ".net.nz", ".org.nz", ".se", ".tc", ".tk", ".tw", ".com.tw", ".com.hk", ".idv.tw", ".org.tw", ".hk", ".co.uk", ".me.uk", ".org.uk", ".vg", ".name"];
        r = r.join("|").replace(".", "\\.");
        var s = new RegExp("\\.?([^.]+(" + r + "))$");
        t.replace(s,
        function(v, u) {
            e = u
        });
        return e
    }
    if (window.top != window) {
        try {
            if (window.parent.document.getElementsByTagName("frameset")[0]) {} else {
                lxb.instance++
            }
        } catch(k) {}
    }
    if (lxb.instance > 1) {
        return
    }
    var g = lxb.use("config");
    var m = lxb.use("net");
    var c = lxb.use("base");
    var a = g.Root + "/_l.js";
    var n = l();
    if (!n) {
        n = c.getCookie(g.ID.COOKIE_DBCLKID)
    } else {
        c.setCookie(g.ID.COOKIE_DBCLKID, n)
    }
    var q = g.bdcbid;
    var p = f();
    if (!p || o(p) == o(location.hostname)) {
        p = c.getCookie(g.ID.COOKIE_REFER)
    } else {
        var b = p + "; path=/";
        if (location.hostname.indexOf("baidu.com") < 0) {
            b += "; domain=." + o(location.hostname)
        }
        c.setCookie(g.ID.COOKIE_REFER, b)
    }
    var h = {
        siteid: g.SiteId,
        bdclickid: n || "",
        bdcbid: q || "",
        refer_domain: p || ""
    };
    m.send(a, h, d);
    j.visitorLog(1, g.SiteId)
})();