var FCAPP = FCAPP || {};
FCAPP.Index = FCAPP.Index || {
    RUNTIME: {},
    init: function() {
        var R = Index.RUNTIME;
        if (!window.gQuery && !gQuery.id) {
            setTimeout(arguments.callee, 200);
            return;
        }
        Index.initElements();
        Index.loadIndexData();
        var id = '';
        if (window.gQuery && gQuery.id) {
            id = gQuery.id;
        }
        FCAPP.Common.loadShareData(id);
        $(window).resize(function() {
            Index.resizeMapImg(R);
            FCAPP.Common.resizeLayout(R.popTips);
        });
        FCAPP.Common.hideToolbar();
    },
    resizeMapImg: function(R) {
        var w = document.documentElement.clientWidth;
        if (!R.mapImg) {
            return;
        }
        R.mapImg.width = w - 30;
    },
    initElements: function() {
        var R = Index.RUNTIME;
        if (!R.template) {
            R.container = $('#container');
            R.template = FCAPP.Common.escTpl($('#template').html());
            R.popTips = $('div.pop_tips');
        }
    },
    loadIndexData: function() {
        window.renderData = Index.renderData;
        var datafile = window.gQuery && gQuery.id ? gQuery.id + '.': '',
        dt = new Date();
        datafile = datafile.replace(/[<>\'\"\/\\&#\?\s\r\n]+/gi, '');
        datafile += 'index.js?';

    },
    renderData: function(data) {
        var R = Index.RUNTIME,
        id = window.gQuery && gQuery.id ? gQuery.id: '';
        FCAPP.Common.hideLoading();
        data.id = id;
        R.container.html($.template(R.template, {
            data: data
        }));
        if (data.video && data.video.vid) {
            setTimeout(function() {
                Index.showVideo(data.video);
            },
            500);
        } else {
            $('#videoDiv').parent().hide();
        }
        setTimeout(function() {
            R.mapImg = $('#mapImg')[0];
        },
        500);
        if (data.banner) {
            FCAPP.Common.loadImg(data.banner, 'bannerPic',
            function(img) {
                img.width = 720;
                img.height = 175;
                img.id = img.idx;
                $('#actTop').show();
            });
        }
    },
    showVideo: function(info) {
        var video = new tvp.VideoInfo(),
        player = new tvp.Player(info.width, info.height),
        type = typeof(navigator.plugins['Shockwave Flash']) == 'undefined' ? "html5": 'auto',
        agent = navigator.userAgent;
        if (/MI [2-9]/.test(agent) && typeof(navigator.plugins['Shockwave Flash']) != 'undefined') {
            type = "flash";
        }
        if (type != 'auto') {
            player.addParam("player", type);
        }
        video.setVid(info.vid);
        if (info.pic) {
            player.addParam("pic", info.pic);
        }
        if (video.title) {
            video.setTitle(info.title);
        }
        player.addParam("showcfg", "0");
        player.addParam("searchbar", "0");
        player.addParam("showend", "0");
        player.addParam("autoplay", "0");
        player.setCurVideo(video);
        player.write("videoDiv");
        window.videoPlayer = player;
    },
    showDetail: function() {},
    switchIndex: function(obj) {
        var box = obj.parentNode;
        if (box.className.indexOf("box_up") != -1) {
            box.className = "box";
            obj.innerHTML = '<span>收起</span>';
        } else {
            box.className = "box box_up";
            obj.innerHTML = '<span>更多</span>';
        }
    },
    goMap: function(id) {
        location.href = MAPURL;
        return;

        id = id || '';
        FCAPP.Common.jumpTo('map.html', {
            id: id,
            '#wechat_webview_type': 1
        },
        true);
    }
};
var Index = FCAPP.Index;
$(document).ready(Index.init);