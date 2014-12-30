/**
 *  全局函数处理
 *  -----------------------------
 *  时间：2014-03-29
 *  准则：Zpote、sea.js
 *********************************************************************************************/
/**
 *  页面切换模块
 */
define(function __page(require, exports, module){
    // 加载其他模块
    var $ = require('./zepto');
    var global = require('./global');

    var page = {
        /**
         * 切换过程的变量控制
         */
        _page           : $('.m-page'),                         // 模版页面切换的页面集合
        _pageNum        : $('.m-page').size(),                  // 模版页面的个数
        _pageNow        : 0,                                    // 页面当前的index数
        _pageNext       : null,                                 // 页面下一个的index数

        _touchStartValY : 0,                                    // 触摸开始获取的第一个值
        _touchDeltaY    : 0,                                    // 滑动的距离

        _moveStart      : true,                                 // 触摸移动是否开始
        _movePosition   : null,                                 // 触摸移动的方向（上、下）
        _movePosition_c : null,                                 // 触摸移动的方向的控制
        _mouseDown      : false,                                // 判断鼠标是否按下
        _moveFirst      : true,
        _moveInit       : false,

        _firstChange    : false,

        page_start      : function(){
            page._page.on('touchstart mousedown',page.page_touch_start);
            page._page.on('touchmove mousemove',page.page_touch_move);
            page._page.on('touchend mouseup',page.page_touch_end);
        },

        // 页面切换停止
        page_stop       : function(){
            page._page.off('touchstart mousedown');
            page._page.off('touchmove mousemove');
            page._page.off('touchend mouseup');
        },

        // page触摸移动start
        page_touch_start: function(e){
            if(!page._moveStart) return;

            if(e.type == "touchstart"){
                page._touchStartValY = window.event.touches[0].pageY;
            }else{
                page._touchStartValY = e.pageY||e.y;
                page._mouseDown = true;
            }

            page._moveInit = true;

            // start事件
            global._handleEvent('start');
        },

        // page触摸移动move
        page_touch_move : function(e){
            e.preventDefault();

            if(!page._moveStart) return;
            if(!page._moveInit) return;

            // 设置变量值
            var $self = page._page.eq(page._pageNow),
                h = parseInt($self.height()),
                moveP,
                scrollTop,
                node=null,
                move=false;

            // 获取移动的值
            if(e.type == "touchmove"){
                moveP = window.event.touches[0].pageY;
                move = true;
            }else{
                if(page._mouseDown){
                    moveP = e.pageY||e.y;
                    move = true;
                }else return;
            }

            // 获取下次活动的page
            node = page.page_position(e,moveP,$self);

            // page页面移动         
            page.page_translate(node);

            // move事件
            global._handleEvent('move');
        },

        // page触摸移动判断方向
        page_position   : function(e,moveP,$self){      
            var now,next;
        
            // 设置移动的距离
            if(moveP!='undefined') page._touchDeltaY = moveP - page._touchStartValY;

            // 设置移动方向
            page._movePosition = moveP - page._touchStartValY >0 ? 'down' : 'up';
            if(page._movePosition!=page._movePosition_c){
                page._moveFirst = true;
                page._movePosition_c = page._movePosition;
            }else{
                page._moveFirst = false;
            }

            // 设置下一页面的显示和位置        
            if(page._touchDeltaY<=0){
                if($self.next('.m-page').length == 0){
                    page._pageNext = 0;
                } else {
                    page._pageNext = page._pageNow+1;   
                }
                
                next = page._page.eq(page._pageNext)[0];
            }else{
                if($self.prev('.m-page').length == 0 ) {
                    if (page._firstChange) {
                        page._pageNext = page._pageNum - 1;
                    } else {
                        page._pageNext = null;
                        page._touchDeltaY = 0;
                        return;
                    }
                } else {
                    page._pageNext = page._pageNow-1;   
                }
                
                next = page._page.eq(page._pageNext)[0];
            }

            now = page._page.eq(page._pageNow)[0];
            node = [next,now];

            // move阶段根据方向设置页面的初始化位置--执行一次
            if(page._moveFirst) init_next(node);

            function init_next(node){
                var s,l,_translateZ = global._translateZ();

                page._page.removeClass('action');
                $(node[1]).addClass('action').removeClass('f-hide');
                page._page.not('.action').addClass('f-hide');
                
                // 模版高度适配函数处理
                page.height_auto(page._page.eq(page._pageNext),'false');

                // 显示对应移动的page
                $(node[0]).removeClass('f-hide').addClass('active'); 

                // 设置下一页面的显示和位置        
                if(page._movePosition=='up'){
                    s = parseInt($(window).scrollTop());
                    if(s>0) l = $(window).height()+s;
                    else l = $(window).height();
                    node[0].style[global._prefixStyle('transform')] = 'translate(0,'+l+'px)'+_translateZ;
                    $(node[0]).attr('data-translate',l);

                    $(node[1]).attr('data-translate',0);
                }else{
                    node[0].style[global._prefixStyle('transform')] = 'translate(0,-'+Math.max($(window).height(),$(node[0]).height())+'px)'+_translateZ;
                    $(node[0]).attr('data-translate',-Math.max($(window).height(),$(node[0]).height()));

                    $(node[1]).attr('data-translate',0);
                }
                $(node[1]).attr('data-translate',0);

                // 模版高度适配函数处理
                // page.height_auto(page._page.eq(page._pageNext),'false');
                page._page.eq(page._pageNext).height($(window).height());
            }
            
            return node;
        },

        // page触摸移动设置函数
        page_translate  : function(node){
            // 没有传值返回
            if(!node) return;
            
            var _translateZ = global._translateZ(),
                y_1,y_2,scale,
                y = page._touchDeltaY;

            // 切换的页面移动
            if($(node[0]).attr('data-translate')) y_1 = y + parseInt($(node[0]).attr('data-translate'));
            node[0].style[global._prefixStyle('transform')] = 'translate(0,'+y_1+'px)'+_translateZ;
            
            // 当前的页面移动
            if($(node[1]).attr('data-translate')) y_2 = y + parseInt($(node[1]).attr('data-translate'));
            scale = (1 - Math.abs(y*0.2/$(window).height())).toFixed(6);
            y_2 = y_2/5;
            node[1].style[global._prefixStyle('transform')] = 'translate(0,'+y_2+'px)'+_translateZ+' scale('+scale+')';
        },

        // page触摸移动end
        page_touch_end  : function(e){
            page._moveInit = false;
            page._mouseDown = false;
            if(!page._moveStart) return;
            if(!page._pageNext&&page._pageNext!=0) return;

            page._moveStart = false;
            page._moveFirst = true;

            // 确保移动了
            if(Math.abs(page._touchDeltaY)>10){
                page._page.eq(page._pageNext)[0].style[global._prefixStyle('transition')] = 'all .3s';
                page._page.eq(page._pageNow)[0].style[global._prefixStyle('transition')] = 'all .3s';
            }
                
            // 页面切换
            if(Math.abs(page._touchDeltaY)>=100){       // 切换成功
                page.page_success();
            }else if(Math.abs(page._touchDeltaY)>10&&Math.abs(page._touchDeltaY)<100){  // 切换失败     
                page.page_fial();
            }else{                                  // 没有切换
                page.page_fial();
            }

            // end事件
            global._handleEvent('end');

            // 注销控制值
            page._movePosition = null;
            page._movePosition_c = null;
            page._touchStartValY = 0;
        },

        // 切换成功
        page_success    : function(){
            var _translateZ = global._translateZ();

            // 下一个页面的移动
            page._page.eq(page._pageNext)[0].style[global._prefixStyle('transform')] = 'translate(0,0)'+_translateZ;

            // 当前页面变小的移动
            var y = page._touchDeltaY > 0 ? $(window).height()/5 : -$(window).height()/5;
            var scale = 0.8;
            page._page.eq(page._pageNow)[0].style[global._prefixStyle('transform')] = 'translate(0,'+y+'px)'+_translateZ+' scale('+scale+')';

            // 成功事件
            global._handleEvent('success');
        },

        // 切换失败
        page_fial   : function(){
            var _translateZ = global._translateZ();

            // 判断是否移动了
            if(!page._pageNext&&page._pageNext!=0) {
                page._moveStart = true;
                page._moveFirst = true;
                return;
            }

            if(page._movePosition=='up'){
                page._page.eq(page._pageNext)[0].style[global._prefixStyle('transform')] = 'translate(0,'+$(window).height()+'px)'+_translateZ;
            }else{
                page._page.eq(page._pageNext)[0].style[global._prefixStyle('transform')] = 'translate(0,-'+$(window).height()+'px)'+_translateZ;
            }

            page._page.eq(page._pageNow)[0].style[global._prefixStyle('transform')] = 'translate(0,0)'+_translateZ+' scale(1)';

            // fial事件
            global._handleEvent('fial');
        },

        height_auto : function(ele, height){
            height = height ? height : $(window).height();
            ele.children('.page-con').css('height', height);
        }
    }

    $(function(){
        $(window).on('resize', function(){
            // var height = $(window).height();

            // if (height < 600) {
            //     height = height * 2;
            // } else {
            //     height = height;
            // }

            // $(window).height(height);
            // $(document.body).height(height);
            // page.height_auto(page._page.eq(page._pageNow), height);
        })
    })

    return page;
});


