/**
 *  微信相关功能插件
 *  -----------------------------
 *  时间：2014-03-21
 *  准则：Zepto
 *********************************************************************************************
 *  这是别人写的东西，我只是重复利用，微调了下--努力努力 ^-^||
 *********************************************************************************************/ 
// 微信相关功能插件--zpeto扩展
define(function (require, exports, module){
    var Zepto = require('./zepto');
    module.exports = Zepto;

    ;(function($){
        $.fn.wx = function(option){
            // 初始化函数体
            var $wx = $(this);
            var opts = $.extend({},$.fn.wx.defaults,option);  // 继承传入的值

            // 确定微信是否准备好
            document.addEventListener("WeixinJSBridgeReady", function(){
                window.G_WEIXIN_READY = true;
            }, false);

            // 回到函数循环执行
            function CallWeiXinAPI(fn, count){
                var total = 2000;   //30s     
                count = count || 0;
                
                if(true === window.G_WEIXIN_READY || ("WeixinJSBridge" in window)){
                    fn.apply(null, []);
                }else{
                    if(count <= total){
                        setTimeout(function(){
                            CallWeiXinAPI(fn, count++);
                        }, 15);
                    }
                }
            }

            var _unit = {
                /**
                 * 执行回调
                 * @param Object handler {Function callback, Array args, Object context, int delay}
                 */
                 execHandler : function(handler){
                    if(handler && handler instanceof Object){
                        var callback = handler.callback || null;
                        var args = handler.args || [];
                        var context = handler.context || null;
                        var delay = handler.delay || -1;

                        if(callback && callback instanceof Function){
                            if(typeof(delay) == "number" && delay >= 0){
                                setTimeout(function(){
                                    callback.apply(context, args);
                                }, delay);
                            }else{
                                callback.apply(context, args);
                            }
                        }
                    }
                },

                /**
                 * 合并参数后执行回调
                 * @param Object handler {Function callback, Array args, Object context, int delay}
                 * @param Array args 参数
                 */
                execAfterMergerHandler : function(handler, _args){
                    if(handler && handler instanceof Object){
                        var args = handler.args || [];

                        handler.args = _args.concat(args);
                    }
                    
                    this.execHandler(handler);
                }
            }

            // 微信的接口
            var _api = {
                Share : {
                    /**
                     * 分享到微博
                     * @param Object options {String content, String url}
                     * @param Object handler
                     */
                    "weibo" : function(options, handler){
                        CallWeiXinAPI(function(){
                            WeixinJSBridge.on("menu:share:weibo",function(argv){
                                WeixinJSBridge.invoke('shareWeibo', options, function(res){
                                    _unit.execAfterMergerHandler(handler, [res]);
                                });
                            });
                        });
                    },
                    /**
                     * 分享到微博
                     * @param Object options {
                     *                  String img_url, 
                     *                  Number img_width, 
                     *                  Number img_height, 
                     *                  String link, 
                     *                  String desc, 
                     *                  String title
                     * }
                     * @param Object handler
                     */
                    "timeline" : function(options, handler){
                        CallWeiXinAPI(function(){
                            WeixinJSBridge.on("menu:share:timeline",function(argv){
                                WeixinJSBridge.invoke('shareTimeline', options, function(res){
                                    _unit.execAfterMergerHandler(handler, [res]);
                                });
                            });
                        });
                    },
                    /**
                     * 分享到微博
                     * @param Object options {
                     *                  String appid, 
                     *                  String img_url, 
                     *                  Number img_width, 
                     *                  Number img_height, 
                     *                  String link, 
                     *                  String desc, 
                     *                  String title
                     * }
                     * @param Object handler
                     */
                    "message" : function(options, handler){
                        CallWeiXinAPI(function(){
                            WeixinJSBridge.on("menu:share:appmessage",function(argv){
                                WeixinJSBridge.invoke('sendAppMessage', options, function(res){
                                    _unit.execAfterMergerHandler(handler, [res]);
                                });
                            });
                        });
                    }
                },
                /**
                 * 设置底栏
                 * @param boolean visible 是否显示
                 * @param Object handler
                 */
                "setToolbar" : function(visible, handler){
                    CallWeiXinAPI(function(){
                        if(true === visible){
                            WeixinJSBridge.call('showToolbar');
                        }else{
                            WeixinJSBridge.call('hideToolbar');
                        }
                        _unit.execAfterMergerHandler(handler, [visible]);
                    });
                },
                /**
                 * 设置右上角选项菜单
                 * @param boolean visible 是否显示
                 * @param Object handler
                 */
                "setOptionMenu" : function(visible, handler){
                    CallWeiXinAPI(function(){
                        if(true === visible){
                            WeixinJSBridge.call('showOptionMenu');
                        }else{
                            WeixinJSBridge.call('hideOptionMenu');
                        }
                        _unit.execAfterMergerHandler(handler, [visible]);
                    });
                },
                /**
                 * 调用微信支付
                 * @param Object data 微信支付参数
                 * @param Object handlerMap 回调句柄 {Handler success, Handler fail, Handler cancel, Handler error}
                 */
                "pay" : function(data, handlerMap){
                    CallWeiXinAPI(function(){
                        var requireData = {"appId":"","timeStamp":"","nonceStr":"","package":"","signType":"","paySign":""};
                        var map = handlerMap || {};
                        var handler = null;
                        var args = [data];

                        for(var key in requireData){
                            if(requireData.hasOwnProperty(key)){
                                requireData[key] = data[key]||"";
                                console.info(key + " = " + requireData[key]);
                            }
                        }

                        WeixinJSBridge.invoke('getBrandWCPayRequest', requireData, function(response){
                            var key = "get_brand_wcpay_request:";
                            switch(response.err_msg){
                                case key + "ok":
                                    handler = map.success;
                                    break;
                                case key + "fail":
                                    handler = map.fail || map.error;
                                    break;
                                case key + "cancel":
                                    handler = map.cancel || map.error;
                                    break;
                                default:
                                    handler = map.error;
                                    break;
                            }

                            _unit.execAfterMergerHandler(handler, args);
                        });
                    });                
                }
            };
/*
            var opt1 = {
                "content" : opts.con
            };

            var opt2 = {
                "img_url" : opts.img,
                "img_width" : 180,
                "img_height" : 180,
                "link" : opts.link,
                "desc" : opts.con,
                "title" : opts.title
            };

            // var opt3 = $.extend({"appid":"wx21f7e6a981efd178"}, opt2);

            handler = {
                callback : function(){
                    
                }
            }

            // 执行函数
            _api.Share.timeline(opt2,handler);
            _api.Share.message(opt2,handler);
            _api.Share.weibo(opt1,handler);

            return $wx;*/
        }
        /*
        $.fn.wx.defaults = {
            title : '云来轻APP-创新作品1号，仅限内测体验', 
            con : '创新1号仅限内部小伙伴们尽情体验！！',
            link : document.URL, 
            img  : location.protocol + "//" + location.hostname + ':' + location.port +'/tpl/static/live/images/wx_img_01@2x.jpg?time=1'
        };*/
        $.fn.wx.version = '1.0.0';
    })(Zepto);
})


