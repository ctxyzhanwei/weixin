/**
 *  全局函数处理
 *  -----------------------------
 *  准则：Zpote、字面量对象
 *********************************************************************************************/
/**
 *  相关插件的启动
 */
define(function __plugins(require, exports, module){
	// 插件的请求加载
	var $ = require('./zepto');
		$ = require('./ylMusic');
		$ = require('./weixin');
	var Lottery = require('./Lottery');

	// 加载其他模块
	var global = require('./global');
	var page = require('./page');
	var media = require('./media');
	var video = require('./video');

	var __plugin = {
		//插件启动函数
	 	init : function(){
			// 音符飘逸
			$('#coffee_flow').coffee({
				steams				: ["<img src='./tpl/static/live/images/audio_widget_01@2x.png' />","<img src='./tpl/static/live/images/audio_widget_01@2x.png' />"], 
				steamHeight			: 100,
				steamWidth			: 44 
			});

			// 声音初始化
			media.media_init();

			// 视频初始化
			video.video_init();

			// 微信分享
			var option_wx = {};

			if($('#r-wx-title').val()!='') option_wx.title = $('#r-wx-title').val();
			if($('#r-wx-img').val()!='') option_wx.img = $('#r-wx-img').val();
			if($('#r-wx-con').val()!='') option_wx.con = $('#r-wx-con').val();
			if($('#r-wx-link').val()!='') option_wx.link = $('#r-wx-link').val();
			if($('#r-wx-callback').val()!='') option_wx.callback = $('#r-wx-callback').val();

			if(global._weixin) $(document.body).wx(option_wx);

			// 判断引导页是否开启 0-关闭，1-开启
			var mengban = $('.translate-front').data('open');

			if (mengban == 1) {
				// 蒙板插件
				var node = $('#j-mengban')[0],
					url = './tpl/static/live/images/page_01_bg@2x.jpg',
					canvas_url = $('#r-cover').val(),
					type = 'image',
					w = 640,
					h = $(window).height(),
					callback = __plugin.start_callback;

				__plugin.cover_draw(node,url,canvas_url,type,w,h,callback);
			} else {
				__plugin.start_callback();
			}
	 	},

	 	// 蒙板插件初始化函数处理
	 	cover_draw : function(node,url,canvas_url,type,w,h,callback){
			if(node.style.display.indexOf('none')>-1) return;

			var lottery = new Lottery(node, canvas_url, type, w, h, callback);

			lottery.init();
		},

	 	// 蒙板插件回调函数处理
	 	start_callback : function(){
	 		var mengban = $('.translate-front').data('open');

	 		// 开启window的滚动
	 		// global._scrollStart();

	 		// 开启页面切换
			page.page_start();

			// 声音启动
			$(document).one("touchstart", function(){
	            media._audio.play();
	        });
	 		
	 		if (mengban == 1) {
	 			// 隐藏蒙板
		 		$('#j-mengban').removeClass('z-show');
		 		setTimeout(function(){
		 			$('#j-mengban').addClass('f-hide');
		 		},1500)

		 		// 箭头显示
				$('.u-arrow').removeClass('f-hide');

				// 播放声音
				if(!media._audio) return;

				media._audioNode.removeClass('f-hide');
				media._audio.play();
	 		} else {
	 			$('#j-mengban').removeClass('z-show').addClass('f-hide');
	 		}
	 	}
 	}

 	// 插件初始化--绑定在window-load事件
 	$(window).on('load',function(){
 		__plugin.init();
 	})
})

