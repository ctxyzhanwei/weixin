function wxReady(e) {
    if (window.WeixinJSBridge) e && e();
    else {
        var i = setTimeout(function() {
            window.WeixinJSBridge && e && e()
        },
        1e3);
        document.addEventListener("WeixinJSBridgeReady",
        function() {
            clearTimeout(i),
            e && e()
        })
    }
} !

function(e, i, n) {
    "use strict";
    function o(i) {
        this.settings = e.extend({},
        s, i),
        this._defaults = s,
        this._name = t,
        this.init()
    }
    var t = "Fullguide",
    s = {
        followSelector: "#js-follow-guide",
        favSelector: "#js-fav-guide",
        shareSelector: "#js-share-guide",
        followTpl: '<div id="js-follow-guide" class="js-fullguide fullscreen-guide follow-guide hide"><span class="js-close-guide guide-close">&times;</span><div class="guide-inner"><div class="step step-2"></div><div class="wxid"><strong><%=mp_weixin %></strong></div><div class="step step-3"></div></div></div>',
        goodsFollowTpl: '<div id="js-follow-guide" class="js-fullguide fullscreen-guide follow-guide hide"><span class="js-close-guide guide-close">&times;</span><div class="guide-inner"><h3 class="guide-inner-title">你需要关注后才能购买</h3><div class="step step-2"></div><div class="wxid"><strong><%=mp_weixin %></strong></div><div class="step step-3"></div></div></div>',
        goodsQuickSubscribeTpl: '<div id="js-follow-guide" class="js-fullguide fullscreen-guide follow-guide hide"><div class="quick-subscribe js-quick-subscribe"><h2>你需要关注我，才能购买！</h2><div><a class="btn" href="<%= quick_subscribe_url %>">去关注</a></div></div></div>',
        favTpl: '<div id="js-fav-guide" class="js-fullguide fullscreen-guide fav-guide hide"><span class="guide-close">&times;</span><span class="guide-arrow"></span><div class="guide-inner"><div class="step step-1"></div><div class="step step-2"></div></div></div>',
        shareTpl: '<div id="js-share-guide" class="js-fullguide fullscreen-guide share-guide hide"><span class="js-close-guide guide-close">&times;</span><span class="guide-arrow"></span><div class="guide-inner">请点击右上角<br/>通过【发送给朋友】功能<br>或【分享到朋友圈】功能<br>把消息告诉小伙伴哟～</div></div>'
    },
    l = i._global,
    a = i._;
    o.prototype = {
        init: function() {
            var e = this;
            e.setConfig(),
            e._init()
        },
        _init: function() {
            var i = this;
            i.canQuickSubscribe = e("html").hasClass("wx_mobile") && l.mp_data && +l.mp_data.quick_subscribe,
            i.initTemplates(),
            i.bindUIActions()
        },
        setConfig: function(i) {
            var n = this;
            n.config = e.extend({},
            n.settings, i)
        },
        bindUIActions: function() {
            var i = this;
            i.followActions(),
            i.favActions(),
            i.shareActions();
            var o = e(n.documentElement);
            o.on("click", ".js-fullguide",
            function() {
                e(this).addClass("hide")
            }),
            o.on("fullguide:show",
            function(e, n) {
                i.show(n)
            }),
            o.on("click", ".js-quick-subscribe",
            function(e) {
                e.stopPropagation()
            })
        },
        followActions: function() {
            var i = this,
            o = e(n.documentElement);
            o.on("click", ".js-open-follow",
            function(e) {
                e.preventDefault(),
                i.show("follow")
            }),
            o.on("click", ".wxid",
            function(e) {
                e.stopPropagation()
            })
        },
        favActions: function() {
            var i = this,
            o = e(n.documentElement);
            o.on("click", ".js-open-fav",
            function(e) {
                e.preventDefault(),
                i.show("fav")
            })
        },
        shareActions: function() {
            var i = this,
            o = e(n.documentElement);
            o.on("click", ".js-open-share",
            function(e) {
                e.preventDefault(),
                i.show("share")
            })
        },
        initTemplates: function() {
            var e = this;
            e.followTemplate = a.template(l && "Showcase_Goods_Controller" === l.controller ? e.canQuickSubscribe ? e.config.goodsQuickSubscribeTpl: e.config.goodsFollowTpl: e.config.followTpl),
            e.favTemplate = a.template(e.config.favTpl),
            e.shareTemplate = a.template(e.config.shareTpl)
        },
        followRender: function(i) {
            i = i || {};
            var n, o = this,
            t = l.mp_data;
            return t ? !i.goods && o.canQuickSubscribe ? void o.goToQuickSubscribePage() : (e(o.config.followSelector).length ? o.followEle = e(o.config.followSelector) : (n = o.followTemplate(t), o.followEle = e(n).appendTo("body")), o.followEle) : void 0
        },
        favRender: function() {
            var i, n = this;
            return e(n.config.favSelector).length ? n.favEle = e(n.config.favSelector) : (i = n.favTemplate(), n.favEle = e(i).appendTo("body")),
            n.favEle
        },
        shareRender: function() {
            var i, n = this;
            return e(n.config.shareSelector).length ? n.shareEle = e(n.config.shareSelector) : (i = n.shareTemplate(), n.shareEle = e(i).appendTo("body")),
            n.shareEle
        },
        show: function(e, i) {
            var n = this,
            o = n[e + "Ele"];
            o || (o = n[e + "Render"](i)),
            o.removeClass("hide")
        },
        goToQuickSubscribePage: function() {
            i.location.href = l.mp_data.quick_subscribe_url
        }
    },
    i.fullguide = i.fullguide || new o
} (window.$, window, document),
function() {
    var e = window._global.share || {},
    i = function(e) {
        return 0 == e.indexOf("http://imgqn.koudaitong.com") || 0 == e.indexOf("http://imgqntest.koudaitong.com") ? e.replace(/(\![0-9]+x[0-9]+.+)/g, "") + "!200x200.jpg": e
    },
    n = function() {
        var e = "http://static.koudaitong.com/v2/image/wap/logo.png",
        n = $("#wxcover"),
        o = null;
        return n && n.length > 0 ? (o = n.data("wxcover"), o && 0 != o.length || (o = n.css("background-image"), o && "none" != o ? (o = /^url\((['"]?)(.*)\1\)$/.exec(o), o = o ? o[2] : null) : o = null)) : (n = $(".content img")[0], n && (o = n.getAttribute("src") || n.getAttribute("data-src"))),
        i(o || (window._global.mp_data || {}).logo || e)
    },
    o = function() {
        var e = window._global.current_page_link || document.documentURI,
        i = Number(window._global.kdt_id) || 0,
        n = "shop" + (192168 + i);
        if ( - 1 !== e.indexOf("open.weixin.qq.com")) {
            e = decodeURIComponent(e);
            var o = e.match(/redirect_uri\=(.*?)[\&|\$]/i);
            o.length > 1 && -1 === o[1].indexOf("/x/") && (o = e.match(/state=(.*?)[\#|\&|\$]/i)),
            e = o.length > 1 ? o[1] : !1
        }
        return i > 0 && (e = e.replace("://wap.", "://" + n + ".")),
        e
    },
	
    t = function() {
        var i = e.title || window._global.title || document.title,
        t = e.link || o(),
        s = e.cover || n();
        return function() {
            var n = $(".time-line-title");
            if (n.length > 0) var o = n.val() || n.text();
            var l = (e.desc || $("#wxdesc").val() || $("#wxdesc").text() || $(".custom-richtext").text() || $(".content-body").text() || i || "").replace(/\s*/g, "");
            return {
                title: i,
                link: t,
                img_url: s,
                desc: l.substring(0, 80),
                img_width: "200",
                img_height: "200",
                timeLineTitle: (o || "").trim()
            }
        }
    } (); 
    wxReady(function() {
        WeixinJSBridge.call(e.notShare ? "hideOptionMenu": "showOptionMenu"),
        WeixinJSBridge.call(window._global.js.hide_wx_nav ? "hideToolbar": "showToolbar"),
        WeixinJSBridge.on("menu:share:timeline",
        function() {
            if (!e.notShare) {
                window.doWhileShare && window.doWhileShare();
                var i = t();
                i.timeLineTitle && (i.title = i.timeLineTitle),
                WeixinJSBridge.invoke("shareTimeline", i,
                function() {})
            }
        }),
        WeixinJSBridge.on("menu:share:facebook",
        function() {
            e.notShare || WeixinJSBridge.invoke("shareTimeline", t(),
            function() {})
        }),
        WeixinJSBridge.on("menu:share:appmessage",
        function() {
            e.notShare || (window.doWhileShare && window.doWhileShare(), WeixinJSBridge.invoke("sendAppMessage", t(),
            function() {}))
        })
    })
} ();