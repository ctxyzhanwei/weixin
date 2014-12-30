(function (window,$) {
    var onBridgeReady = function () {

        $(document).trigger('bridgeready');

        var $body = $('body'), appId = '',
            title = $body.attr('title'),
            imgUrl = $body.attr('icon'),
            link = $body.attr('link'),
            desc = $body.attr('desc') || link;
        if (!setForward()) {
            $(document).bind('weibachanged', function () {
                setForward();
            });
        }
    };
    if (document.addEventListener) {
        document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
    } else if (document.attachEvent) {
        document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
        document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
    }

    function setForward() {
        var $body = $('body'), appId = '',
            title = $body.attr('title'),
            imgUrl = $body.attr('icon'),
            link = $body.attr('link'),
            desc = $body.attr('desc') || link;
        if (title && link) {
            WeixinJSBridge.on('menu:share:appmessage', function (argv) {
                WeixinJSBridge.invoke('sendAppMessage', {
                    //'appid': 'kczxs88',
                    'img_url': imgUrl?imgUrl:undefined,
                    'link': link,
                    'desc': desc?desc:undefined,
                    'title': title
                }, function (res) {
                    if (res && res['err_msg'] && res['err_msg'].indexOf('confirm') > -1) {
                        $(document).trigger('wx_sendmessage_confirm');
                    }
                });
            });
            WeixinJSBridge.on('menu:share:timeline', function (argv) {
                $(document).trigger('wx_timeline_before');

                WeixinJSBridge.invoke('shareTimeline', {
                    'img_url': imgUrl?imgUrl:undefined,
                    'link': link,
                    'desc': desc?desc:undefined,
                    'title': title
                }, function (res) {
                    //貌似目前没有简报
                });
            });
            /*
             WeixinJSBridge.on('menu:share:weibo', function (argv) {
             WeixinJSBridge.invoke('shareWeibo', {
             'content': title + desc,
             'url': link
             }, function (res) {

             });
             });
             */

            return true;
        }
        else {
            return false;
        }
    }

})(window,jQuery);

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideToolbar');
});