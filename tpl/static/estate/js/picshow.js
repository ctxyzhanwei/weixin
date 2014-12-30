var FCAPP = FCAPP || {};
FCAPP.HOUSE = FCAPP.HOUSE || {};
FCAPP.HOUSE.PICSHOW = {
    CONFIG: {},
    RUNTIME: {
        opacity: 0
    },
    init: function() {
        var R = PICSHOW.RUNTIME;
        if (!R.binded) {
            R.binded = true;
            PICSHOW.initElements(R);
            PICSHOW.bindEvents(R);
            R.template1 = FCAPP.Common.escTpl($('#template1').html());
            R.template2 = FCAPP.Common.escTpl($('#template2').html());
            R.template3 = FCAPP.Common.escTpl($('#template3').html());
            R.w = document.documentElement.clientWidth;
            R.h = document.documentElement.clientHeight;
        }
        PICSHOW.loadData();
        var id = '';
        if (window.gQuery && gQuery.id) {
            id = gQuery.id;
        }
        window.sTo = PICSHOW.scrollTo;
        FCAPP.Common.loadShareData(id);
        FCAPP.Common.hideToolbar();
    },
    initElements: function(R) {
        R.scroller = $('#scroller');
        R.scrollList = $('#scrollList');
        R.scrollTips = $('#scrollTips');
        R.scroller1 = $('#scroller1');
        R.scrollPic = $('#scrollPic');
        R.scrollPicLi = $('#scrollPic li');
        R.closeBtn = $('#photoClick');
        R.picName = $('#picName');
        R.popMask = $('#popMask');
        R.scrollWidth = [];
        R.scrollTitle = [];
        R.scrollPagesX = [];
        R.picSize = [];
        R.imgDom = [];
        R.picIdx = 0;
        R.thubIdx = 0;
        R.reduceSize = 0;
        R.loadedThub = {};
        R.view = 'thub';
    },
    bindEvents: function(R) {
        $(window).resize(PICSHOW.resizeLayout);
        R.closeBtn.click(PICSHOW.closeSlidePics);
    },
    loadData: function() {
        window.showPics = PICSHOW.showPics;
        var datafile = window.gQuery && gQuery.id ? gQuery.id + '.': '',
        dt = new Date();
        datafile = datafile.replace(/[<>\'\"\/\\&#\?\s\r\n]+/gi, '');
        datafile += 'picshow.js?';
        $.ajax({
            //url: './tpl/static/estate/js/picdata',
            url: './index.php?g=Wap&m=Estate&a=load_album_pic&id='+PID+'&token='+TOKEN,
            dataType: 'jsonp',
            error: function() {
                FCAPP.Common.msg(true, {
                    msg: '无效的户型！'
                });
            }
        });
    },
    showPics: function(data) {
        var R = PICSHOW.RUNTIME;
        var width = 0,
        totalWidth = 0;
        for (var i = 0,
        il = data.length; i < il; i++) {
            width = PICSHOW.calcWidth(data[i], i);
            R.scrollWidth.push(width);
            R.scrollTitle.push(data[i].title);
            totalWidth += width;
            R.scrollPagesX.push(totalWidth);
        }
        R.lastGroup = data[i - 1];
        PICSHOW.renderPics(data);
        PICSHOW.resizeLayout();
    },
    resizeLayout: function() {
        var R = PICSHOW.RUNTIME,
        p = R.picSize,
        pages = R.scrollPagesX,
        num = 0;
        R.w = document.documentElement.clientWidth;
        R.h = document.documentElement.clientHeight;
        R.widthTime = window.innerWidth > window.innerHeight && R.h < 350 ? 2 : 1;
        if (R.view == 'thub') {
            if (R.widthTime > 1) {
                R.scroller.addClass('photo_wide');
            } else {
                R.scroller.removeClass('photo_wide');
            }
            num = R.widthTime == 2 ? R.reduceSize: pages[pages.length - 1] - 10;
            R.scrollList.css({
                width: num + 'px'
            });
            if (R.scrollerScroll) {
                var t = R.thubIdx,
                el = $('#picshow' + t);
                PICSHOW.showSlideText(Math.max(t - 1, 0));
                R.scrollerScroll.refresh();
                R.isRunning = true;
                if (el.length) {
                    try {
                        R.scrollerScroll.scrollToElement(el[0], 40);
                    } catch(e) {}
                }
                setTimeout(function() {
                    delete R.isRunning;
                },
                50);
            } else {
                PICSHOW.thubScrollCB();
            }
        } else {
            var s1 = R.scroller1Scroll;
            R.scrollPicLi.addClass('noLoading');
            R.scrollPicLi.css({
                width: R.w + 'px'
            });
            R.scrollPic.css({
                width: p.length * R.w + 'px',
                height: R.h + 'px'
            });
            s1.refresh();
            setTimeout(function() {
                s1.scrollToPage(s1.currPageX, 0);
            },
            100);
            $('img[load="false"]').css({
                width: R.w + 'px',
                height: R.h + 'px'
            });
            for (var i = 0,
            il = R.imgDom.length; i < il; i++) {
                if (R.imgDom[i]) {
                    PICSHOW.origImgLoad(R.imgDom[i]);
                }
            }
        }
    },
    slidePics: function(evt, idx) {
        evt = evt || window.event;
        var tar = evt.srcElement || evt.target,
        idx = !!idx ? idx: (tar && (tar.id || tar.idx) ? parseInt((tar.id || tar.idx).replace('thubImg', '')) : 0),
        idx = isNaN(idx) ? 0 : idx,
        R = PICSHOW.RUNTIME,
        img = $('#bImg' + idx),
        s = R.scroller1Scroll,
        p = R.picSize[idx];
        console.log(idx);
        R.view = 'big';
        R.scrollPic.show();
        R.scroller1.show();
        PICSHOW.resizeLayout();
        R.scrollPicLi.addClass('noLoading');
        R.popMask.show();
        s.refresh();
        if (img.length) {
            s.scrollToPage(idx);
            if (!p.loaded) {
                FCAPP.Common.loadImg(p.img, 'bImg' + idx, PICSHOW.origImgLoad);
            }
        }
        R.picName.html((idx + 1) + '/' + R.picSize.length + '  ' + p.name);
    },
    closeSlidePics: function() {
        var R = PICSHOW.RUNTIME,
        s = R.scrollerScroll,
        s1 = R.scroller1Scroll,
        p = R.picSize;
        R.scrollPic.hide();
        R.scroller1.hide();
        R.popMask.hide();
        R.view = 'thub';
        PICSHOW.resizeLayout();
        s.refresh();
        s.scrollToElement($('#thubImg' + p[s1.currPageX].idx)[0], 200);
    },
    renderPics: function(data) {
        var R = PICSHOW.RUNTIME;
        PICSHOW.showSlideText(0);
        R.scrollList.html($.template(R.template1, {
            data: data
        }));
        R.scrollPic.html($.template(R.template3, {
            data: R.picSize,
            R: R
        }));
        R.scrollPicLi = $('#scrollPic li');
        setTimeout(function() {
            PICSHOW.initScroll('scroller', PICSHOW.thubScrollCB, false, true);
            FCAPP.Common.hideLoading();
            R.opacityInterval = setInterval(PICSHOW.showThubGroup, 50);
        },
        100);
        setTimeout(function() {
            R.scrollPic.css({
                width: R.w * R.picSize.length + 'px'
            });
            PICSHOW.initScroll('scroller1', PICSHOW.origScrollCB, true, false);
            R.picName.html(R.picSize[0].name);
            PICSHOW.loadThubImg(0);
            PICSHOW.loadThubImg(1);
        },
        500);
    },
    calcWidth: function(part, dataIdx) {
        var R = PICSHOW.RUNTIME,
        width = {},
        titleIdx = -1,
        textIdx = -1,
        textLoc = 0,
        titleLoc = 0,
        str, len, cw, idx = -1;
        for (var i in part) {
            var data = part[i];
            if (! (data instanceof Array) || !('length' in data)) {
                continue;
            }
            width[i] = 0;
            for (var j = 0,
            jl = data.length; j < jl; j++) {
                if (data[j].type == 'img') {
                    cw = Math.floor(data[j].size[0] * (150 / data[j].size[1]));
                    idx = R.picIdx++;
                    part[i][j].idx = idx;
                    R.picSize[idx] = {
                        name: data[j].name,
                        img: data[j].img,
                        idx: idx,
                        group: dataIdx,
                        w: data[j].size[0],
                        h: data[j].size[1]
                    };
                } else if (data[j].type == 'text') {
                    str = data[j].content;
                    len = str.length;
                    cw = Math.ceil(len * 140 / 78) + 22;
                    if (data[j].size) {
                        cw = Math.floor(data[j].size[0] * (150 / data[j].size[1]));
                    }
                    data[j].width = cw - 10;
                    textLoc = j;
                } else if (data[j].type == 'title') {
                    str = data[j].title.replace(/[a-z0-9]+/gi, '');
                    len = str.length + Math.ceil((data[j].title.length - str.length) / 2);
                    cw = 150;
                    data[j].width = cw;
                }
                cw += (j == 0 ? 2 : 10);
                width[i] += cw;
            }
        }
        cw = width.ps2 - width.ps1;
        if (cw > 0) {
            width.ps1 += cw;
            part.ps1[titleLoc].width += cw;
        } else {
            width.ps2 -= cw;
            part.ps2[textLoc].width -= cw;
        }
        width.ps2 += 24;
        width.ps1 += 24;
        R.reduceSize += width.ps1 + width.ps2 - 12;
        part.width = width.ps2;
        return part.width;
    },
    thubScrollCB: function() {
        if (!PICSHOW.RUNTIME.scrollerScroll || PICSHOW.RUNTIME.isRunning) {
            return;
        }
        var R = PICSHOW.RUNTIME,
        scroll = R.scrollerScroll,
        x = Math.abs(scroll.x),
        p = R.scrollPagesX,
        w = R.scrollWidth,
        tmp = 0;
        for (var il = p.length,
        i = il - 1; i > -1; i--) {
            tmp = (p[i] - w[i]) * R.widthTime - R.w / 2;
            if (x > tmp) {
                R.thubIdx = i + 1;
                PICSHOW.loadThubImg(i);
                PICSHOW.showSlideText(i);
                PICSHOW.loadThubImg(i + 1);
                break;
            }
        }
    },
    showThubGroup: function() {
        var R = PICSHOW.RUNTIME;
        if (R.opacity >= 1) {
            clearInterval(R.opacityInterval);
        } else {
            R.opacity += 0.05;
            R.scroller.css('opacity', R.opacity);
        }
    },
    showSlideText: function(i) {
        var R = PICSHOW.RUNTIME,
        t = R.scrollTitle,
        il = R.scrollPagesX.length,
        idx = 0,
        end = 0;
        if (i < 2) {
            idx = i;
            i = 0;
            end = i + 3;
        } else {
            idx = 1;
            if (il - i < 3) {
                end = il;
                if (i == il - 1) {
                    idx = 2;
                }
                i = il - 3;
            } else {
                i -= 1;
                end = i + 3;
            }
        }
        var data = {
            data: t.slice(i, end),
            idx: idx,
            start: (i + 1)
        };
        R.scrollTips.html($.template(R.template2, data));
    },
    loadThubImg: function(idx) {
        var R = PICSHOW.RUNTIME,
        p = R.picSize;
        if (!R.loadedThub[idx] && idx < p.length) {
            R.loadedThub[idx] = true;
            for (var j = 0,
            jl = p.length; j < jl; j++) {
                if (p[j].group == idx) {
                    FCAPP.Common.loadImg(p[j].img, 'thubImg' + p[j].idx, PICSHOW.thubImgLoad);
                }
            }
        }
    },
    thubImgLoad: function(img, i) {
        var R = PICSHOW.RUNTIME,
        idx = (img.idx || img.id).replace(/[^\d]+/g, '');
        img.height = 150;
        img.width = Math.floor(R.picSize[idx].w * (150 / R.picSize[idx].h));
        img.id = img.idx;
        img.onclick = PICSHOW.slidePics;
    },
    origScrollCB: function() {
        var R = PICSHOW.RUNTIME,
        scroll = R.scroller1Scroll,
        idx = scroll.currPageX,
        p = R.picSize[idx];
        $('#bLi' + idx).removeClass('noLoading');
        R.picName.html((idx + 1) + '/' + R.picSize.length + '  ' + p.name);
        if (!p.loaded) {
            FCAPP.Common.loadImg(p.img, 'bImg' + idx, PICSHOW.origImgLoad);
        }
    },
    origImgLoad: function(img) {
        if (!img) {
            return;
        }
        var R = PICSHOW.RUNTIME,
        idx = (img.idx || img.id).replace(/[^\d]+/g, ''),
        p = R.picSize[idx],
        cssText = '',
        mg = 0,
        sw = R.w - 10,
        sh = R.h,
        fw = 0,
        fh = 0;
        if ((p.h / p.w) < (sh / sw)) {
            fw = sw;
            fh = Math.ceil(p.h * sw / p.w);
            mg = Math.ceil((sh - fh) / 2);
            cssText = 'margin:' + mg + "px 0";
        } else {
            fh = sh;
            fw = Math.ceil(p.w * fh / p.h);
            mg = Math.ceil((sw - fw) / 2);
            cssText = 'margin:0 ' + mg + 'px 0 ' + mg + 'px';
        }
        img.id = 'bImg' + idx;
        if (!img.idx) {
            img.idx = img.id;
        } else {
            R.picSize[idx].loaded = true;
            R.imgDom[idx] = img;
        }
        img.width = fw;
        img.height = fh;
        img.style.cssText = cssText;
    },
    initScroll: function(id, cb, snap, momentum) {
        var R = PICSHOW.RUNTIME;
        R[id + 'Scroll'] = new iScroll(id, {
            zoom: false,
            snap: !!snap,
            momentum: !!momentum,
            hScrollbar: false,
            vScrollbar: false,
            fixScrollBar: false,
            hScroll: true,
            onScrollEnd: cb ||
            function() {}
        });
    },
    scrollTo: function(idx) {
        var R = PICSHOW.RUNTIME;
        try {
            R.scrollerScroll.scrollToElement($('#picshow' + idx)[0], 300);
        } catch(e) {}
    }
};
var PICSHOW = FCAPP.HOUSE.PICSHOW;
$(document).ready(PICSHOW.init);