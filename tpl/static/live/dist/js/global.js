/**
 *  全局函数处理
 *  -----------------------------
 *  准则：Zpote、sea.js
 *********************************************************************************************/
/**
 * 全局模块
 */
define(function __global(require, exports, module){
	var $ = require('./zepto');

    var __onAction = function(e){
    	if ($(e.currentTarget).attr('data-event') != 'no') {
    		e.preventDefault();
    	}
        e.stopPropagation();

        var cur = $(e.currentTarget);
        var action = cur.attr("data-action") || "";
        var pattern = /^([a-zA-Z0-9_]+):\/\/([a-zA-Z0-9_]+)$/;
        var result = pattern.exec(action);
        var _class = null;
        var _method = null;
        var _opts = {
        	node : cur,
        	e_node : e,
        	_node : e.currentTarget
        }

        if(result){
            _class = result[1];            // 预定义名字
            _method = result[2];           // 预定义执行的方法

            if((_class in $) && (_method in $[_class])){
                $[_class][_method].call(null, _opts);
            }
        }
    }

	var __global = {
		/*************************
		 *  = 对象变量，判断函数
		 *************************/
		_click 			: ("ontouchstart" in window) ? "tap" : "click",
		_events 		: {},									// 自定义事件---this._execEvent('scrollStart');
		_windowHeight	: $(window).height(),					// 设备屏幕高度
		_windowWidth 	: $(window).width(),

		_rotateNode		: $('.p-ct'),							// 旋转体

		_isMotion	 	: !!window.DeviceMotionEvent,			// 是否支持重力感应
		
		_elementStyle	: document.createElement('div').style,	// css属性保存对象

		_UC 			: RegExp("Android").test(navigator.userAgent)&&RegExp("UC").test(navigator.userAgent)? true : false,
		_weixin			: RegExp("MicroMessenger").test(navigator.userAgent)? true : false,
		_iPhoen			: RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)? true : false,
		_Android		: RegExp("Android").test(navigator.userAgent)? true : false,
		_IsPC			: function(){ 
							var userAgentInfo = navigator.userAgent; 
							var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
							var flag = true; 
							for (var v = 0; v < Agents.length; v++) { 
								if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; } 
							} 
							return flag; 
						} ,

		/***********************
		 *  = gobal通用函数
		 ***********************/
	 	// 判断函数是否是null空值
		_isOwnEmpty	: function (obj) { 
			for(var name in obj) { 
				if(obj.hasOwnProperty(name)) { 
					return false; 
				} 
			} 
			return true; 
		},

		// 判断浏览器内核类型
		_vendor	: function () {
			var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
				transform,
				i = 0,
				l = vendors.length;

			for ( ; i < l; i++ ) {
				transform = vendors[i] + 'ransform';
				if ( transform in this._elementStyle ) return vendors[i].substr(0, vendors[i].length-1);
			}
			return false;
		},

		// 判断浏览器来适配css属性值
		_prefixStyle : function (style) {
			if ( this._vendor() === false ) return false;
			if ( this._vendor() === '' ) return style;
			return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
		},

		// 判断是否支持css transform-3d（需要测试下面属性支持）
		_hasPerspective	: function(){
			var ret = this._prefixStyle('perspective') in this._elementStyle;
			if ( ret && 'webkitPerspective' in this._elementStyle ) {
				this._injectStyles('@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}', function( node, rule ) {
					ret = node.offsetLeft === 9 && node.offsetHeight === 3;
				});
			}
			return !!ret;
		},

		// 判断属性支持是否
		_injectStyles : function( rule, callback, nodes, testnames ) {
			var style, ret, node, docOverflow,
				div = document.createElement('div'),
				body = document.body,
				fakeBody = body || document.createElement('body'),
				mod = 'modernizr';

			if ( parseInt(nodes, 10) ) {
				while ( nodes-- ) {
					node = document.createElement('div');
					node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
					div.appendChild(node);
					}
			}

			style = ['&#173;','<style id="s', mod, '">', rule, '</style>'].join('');
			div.id = mod;
			(body ? div : fakeBody).innerHTML += style;
			fakeBody.appendChild(div);
			if ( !body ) {
				fakeBody.style.background = '';
				fakeBody.style.overflow = 'hidden';
				docOverflow = docElement.style.overflow;
				docElement.style.overflow = 'hidden';
				docElement.appendChild(fakeBody);
			}

			ret = callback(div, rule);
			if ( !body ) {
				fakeBody.parentNode.removeChild(fakeBody);
				docElement.style.overflow = docOverflow;
			} else {
				div.parentNode.removeChild(div);
			}

			return !!ret;
		},

		// 开启3D加速
		_translateZ : function(){
			if(this._hasPerspective){
				return ' translateZ(0)';
			}else{
				return '';
			}
		},

		// 自定义事件操作
		_handleEvent : function (type) {
			if ( !this._events[type] ) {
				return;
			}

			var i = 0,
				l = this._events[type].length;

			if ( !l ) {
				return;
			}

			for ( ; i < l; i++ ) {
				this._events[type][i].apply(this, [].slice.call(arguments, 1));	
			}
		},

		// 给自定义事件绑定函数
		_on : function (type, fn) {
			if ( !this._events[type] ) {
				this._events[type] = [];
			}

			this._events[type].push(fn);
		},

		// 执行回调
        execHandler : function(handler){
            if(handler && handler instanceof Object){
                var callback = handler.callback || null;
                var opts = handler.opts || [];
                var context = handler.context || null;
                var delay = handler.delay || -1;

                if(callback && callback instanceof Function){
                    if(typeof(delay) == "number" && delay >= 0){
                        setTimeout(function(){
                            callback.call(context, opts);
                        }, delay);
                    }else{
                        callback.call(context, opts);
                    }
                }
            }
        },

        // 合并参数后执行回调
        execAfterMergerHandler : function(handler, _opts){
            if(handler && handler instanceof Object){
                var opts = handler.opts || [];

                handler.opts = $.extend(handler.opts, _opts);
            }
            
            this.execHandler(handler);
        },

		// 禁止滚动条
		_scrollStop : function(){
			$('body').addClass('f-ofh');
			//禁止滚动
			$(window).on('touchmove',this._scrollControl);
			$(window).on('scroll',this._scrollControl);
		},

		//启动滚动条
		_scrollStart : function(){	
			$('body').removeClass('f-ofh');	
			$(window).off('touchmove');
			$(window).off('scroll');
		},

		// 滚动条控制事件
		_scrollControl : function(e){e.preventDefault();return false;},
		
		// 执行挂载函数
		setActionHook : function(){
            $("body").on(__global._click,'[data-action]', __onAction);
        },

        // 继承挂载函数
        injectAction : function(action){
            $.extend($.Action, action);
        },

        // loading显示
		loadingPageShow : function(node){
			if (node.length>=1) { 
				node.show();
			}
		},
		
		// loading隐藏
		loadingPageHide : function (node){
			if (node.length>=1) { 
				node.hide();
			}
		},

		// 对象私有变量刷新
		refresh	: function(){
			this._windowHeight = $(window).height();
			this._windowWidth = $(window).width();
		}
	}

	return __global;
});