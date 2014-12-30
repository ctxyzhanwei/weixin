var FCAPP = FCAPP || {},
myScroll;
FCAPP.HOUSE = FCAPP.HOUSE || {};
FCAPP.HOUSE.Picroll = {
    CONFIG: {
        zoomTime: 200
    },
    RUNTIME: {
        findCategory: false
    },
    init: function() {
        var R = Picroll.RUNTIME;
        if (!R.binded) {
            R.binded = true;
            Picroll.initElements(R);
            R.detailState = 'hide';
            R.zoomed = false;
            //R.template = FCAPP.Common.escTpl($('#template').html());
            R.imgDom = [];
            R.w = document.documentElement.clientWidth;
            R.h = document.documentElement.clientHeight;
            window.shareData = window.shareData || {};
            R.isWeixin = /MicroMessenger/.test(navigator.userAgent);
        }
    },
    initElements: function(R) {
        R.picDetail = $('#picDetail');
        R.detailCav = $('#detailContainer');
        R.closeBtn = $('a.btn_show_close');
        R.zoomBtn = $('a.btn_zoom_out');
        R.popTips = $('#popTips');
        R.downBtn = $('a.btn_down');
        R.picTank = $('#picTank');
        R.zoomDiv = $('#zoomDiv');
    },
    switchDetail: function() {
        var R = Picroll.RUNTIME;
        if (R.detailState == 'hide') {
            R.detailState = 'show';
            R.detailCav.addClass('type_full');
            R.closeBtn.hide();
            R.zoomBtn.hide();
            R.downBtn.hide();
        } else {
            R.detailState = 'hide';
            R.detailCav.removeClass('type_full');
            if (!R.isWeixin) {
                R.closeBtn.show();
                R.zoomBtn.show();
                R.downBtn.show();
            }
        }
    },
    showRooms: function(res) {
        var R = Picroll.RUNTIME,
        data, idx = -1,
        find = false;
        FCAPP.Common.hideLoading();
        data = res.rooms;
        for (var i = 0, il = data.length; i < il; i++) {
            if (data[i].id == gQuery.houseid) {
                idx = i;
                break;
            }
        }
        if (idx == -1) {
            FCAPP.Common.msg(true, {
                msg: '没找到该户型'
            });
        } else {
            find = true;
            Picroll.renderPics(data[idx]);
        }
        R.findCategory = find;
        R.closeBtn.show();
    },
};
var Picroll = FCAPP.HOUSE.Picroll;
$(document).ready(Picroll.init);