var FCAPP = FCAPP || {};
FCAPP.REVIEW = FCAPP.REVIEW || {
    RUNTIME: {},
    init: function() {
        var R = REVIEW.RUNTIME;

        if (!R.switchBtns) {             
            R.switchBtns = $('ul.nav_top li a');
            R.switchPanels = [$('#impress'), $('#professional')];
            R.popTips = $('#popTips');
            R.noticeDiv = $('#noticeDiv');
            R.tipsMsg = $('#tipsMsg');
            R.tipsTitle = $('#tipsTitle');
            R.noticeBtn = $('#noticeBtn');
            R.popConfirmBtn = $('#popConfirmBtn');
            R.popMask = $('#popMask');
            R.inputImpress = $('#inputImpress');
            R.reviewId = $('#reviewId');
            R.floatTips = $('div.pop_tips');
            R.navTop = $('#navTop');
            R.noticeBtn.click(function() {
                REVIEW.showTips(false);
            });
            R.inputImpress.keyup(function() {
                try {
                    clearTimeout(window.tout);
                } catch(e) {}
                window.tout = setTimeout(REVIEW.checkLen, 800);
            });
        }

        //REVIEW.loadReviewList();
        var id = '';
        if (window.gQuery && gQuery.id) {
            id = gQuery.id;
        }
        FCAPP.Common.loadShareData(id);
        $(window).resize(REVIEW.resizeLayout);
        REVIEW.resizeLayout();
        FCAPP.Common.hideToolbar();
    },
    resizeLayout: function() {
        var R = REVIEW.RUNTIME,
        w = window.innerWidth,
        h = window.innerHeight;
        REVIEW.checkLen();
        FCAPP.Common.resizeLayout(R.floatTips);
    },
    checkLen: function() {
        var R = REVIEW.RUNTIME;
        R.popConfirmBtn.unbind('click', REVIEW.sendReview);
        if (R.inputImpress.val().length == 4) {
            R.popConfirmBtn.removeClass('out');
            R.popConfirmBtn.bind('click', REVIEW.sendReview);
        } else {
            R.popConfirmBtn.addClass('out');
        }
    },
    switchPanel: function(idx) {
        var R = REVIEW.RUNTIME;
        R.switchBtns.removeClass('current');
        R.switchBtns[idx].className = 'current';
        if (idx == 0) {
            R.switchPanels[1].hide();
            R.switchPanels[0].show();
        } else {

            
            R.switchPanels[0].hide();
            R.switchPanels[1].show();
        }
    },

    addReview: function(id, content, cls) {
        var R = REVIEW.RUNTIME;
        if ($('.my_in .is_review').length == 1) {
            REVIEW.showTips(true, {
                msg: '你已经添加过印象了哦~'
            });
            return;
        }

        if (id > 0) {
            R.popConfirmBtn.removeClass('out');
            //R.popConfirmBtn.bind('click', REVIEW.sendReview);
        }
        try {
            if (cls != 'my_in') {
                var bcolor = $('#id_' + id).css('background-color'),
                fcolor = $('#id_' + id+' a span').css('color');
                R.inputImpress.css({
                    'background-color': bcolor,
                    'border': 'none',
                    color: fcolor
                });
                R.inputImpress.parent().css({
                    'background-color': bcolor
                });
            } else {
                R.inputImpress.css({
                    'background-color': '#fff',
                    border: '2px solid #eee',
                    color: '#383838'
                });
                R.inputImpress.parent().css({
                    'background-color': '#fff'
                });
            }
        } catch(e) {}
        if (id != 0) {
            R.inputImpress.val(content);
            R.reviewId.val(id);
            R.inputImpress.addClass("input_impress_focus");
            R.inputImpress.attr('readonly', true);
        } else {
            if (R.lastContent) {
                R.inputImpress.val(R.lastContent.substr(0, 4));
            } else {
                R.inputImpress.val('');
            }
            R.inputImpress.removeClass("input_impress_focus");
            R.inputImpress.removeAttr('readonly');
            R.reviewId.val(id);
        }
        R.popTips.show();
        R.popMask.show();
    },
    sendReview: function() {

        var R = REVIEW.RUNTIME;
        id = parseInt(R.reviewId.val());
        review = R.inputImpress.val();

        ///isNaN(id) || review.length != 4 || [^\u4e00-\u9FFF]/g.test(review)
        if (isNaN(id) || review.length != 4) {
            REVIEW.showTips(true, {
                msg: '请输入您的楼盘印象'
            });
            R.popTips.hide();
            R.popMask.show();
            R.noticeBtn.one('click',
            function() {
                R.popMask.show();
                R.popTips.show();
            });
            return;
        } else {
            var imp_user = $('#inputImpress').val();
            $.getJSON('./index.php?g=Wap&m=Estate&a=impress_add', {'token':TOKEN,'wecha_id':WECHA_ID,'id':PID,'imp_id':id,'imp_user':imp_user},function(data){

    var R = REVIEW.RUNTIME,
        msg = '',
        count = 1;
        R.popTips.hide();

        if (data.errno == 1) {
            R.popMask.hide();
            msg = "印象添加成功！";

            if (data.res.comment < 1) {
                $('#userReview').html('<div>印象“'+data.res.name +'”很独特哦，极少数人和你一样</div>');
            } else {
                $('#userReview').html('<div class="is_review">我的楼盘印象<i>“' + data.res.name + '”</i>与' + data.res
                    .comment + '房友相同</div>');
            }
            window.location.reload();
        } else {
            var msg = data.msg;
        }
        REVIEW.showTips(true, {
            msg: msg
        });

            });
        
        }
    },
    closePop: function() {
        var R = REVIEW.RUNTIME;
        R.popTips.hide();
        R.popMask.hide();
        R.reviewId.val(0);

    },
    showTips: function(boo, opt) {
        var R = REVIEW.RUNTIME;
        if (opt && opt.msg) {
            R.tipsMsg.html(opt.msg);
        }
        if (opt && opt.title) {
            R.tipsTitle.html(opt.title);
        }
        if (boo) {
            R.noticeDiv.show();
            R.popMask.show();
        } else {
            R.noticeDiv.hide();
            R.popMask.hide();
        }
    },
    renderProList: function(data) {
        var R = REVIEW.RUNTIME;
        R.switchPanels[1].html($.template(R.template, {
            data: data
        }));
        R.navTop.css('display', '-webkit-box');
    },
    proListError: function() {
        var R = REVIEW.RUNTIME;
        R.navTop.hide();
    }
};
var REVIEW = FCAPP.REVIEW;
$(document).ready(REVIEW.init);