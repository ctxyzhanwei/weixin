/**
 *  全局函数处理
 *  -----------------------------
 *  准则：Zpote、sea.js
 *********************************************************************************************/
/**
 * handle-event--自定义事件处理
 */
define(function __event(require, exports, module){
	// 加载其他模块
	var $ = require('./zepto');
	var global = require('./global');
	var page   = require('./page');
	var image  = require('./image');
	var sigeFn = require('./sileFn');
	var media = require('./media');

	haddle_envent_fn = function(){
 		// 当前页面移动，延迟加载以后的图片
		global._on('start',image.lazy_bigP);

		// 切换失败事件
		global._on('fial',function(){
			setTimeout(function(){
				page._page.eq(page._pageNow).attr('data-translate','');
 				page._page.eq(page._pageNow)[0].style[global._prefixStyle('transform')] = '';
 				page._page.eq(page._pageNow)[0].style[global._prefixStyle('transition')] = '';
 				page._page.eq(page._pageNext)[0].style[global._prefixStyle('transform')] = '';
	 			page._page.eq(page._pageNext)[0].style[global._prefixStyle('transition')] = '';

	 			page._page.eq(page._pageNext).removeClass('active').addClass('f-hide');
				page._moveStart = true;
				page._moveFirst = true;
				page._pageNext = null;
				page._touchDeltaY = 0;
	 		},300)
		})

		// 切换成功事件
		global._on('success',function(){
			// 判断最后一页让，开启循环切换
			if (page._pageNext == 0 && page._pageNow == page._pageNum -1) {
				page._firstChange = true;
			}

			// 判断是否是最后一页，让轻APP关联页面隐藏
 			if(page._page.eq(page._pageNext).next('.m-page').length != 0){
 				sigeFn.lightapp_intro_hide(true);
 			}
			setTimeout(function(){
				// 设置富文本的高度
				sigeFn.Txt_init(page._page.eq(page._pageNow));

				// 判断是否为最后一页，显示或者隐藏箭头
				if(page._pageNext == page._pageNum-1 ) $('.u-arrow').addClass('f-hide');
				else  $('.u-arrow').removeClass('f-hide');

	 			page._page.eq(page._pageNow).addClass('f-hide');

				page._page.eq(page._pageNow).attr('data-translate','');
 				page._page.eq(page._pageNow)[0].style[global._prefixStyle('transform')] = '';
 				page._page.eq(page._pageNow)[0].style[global._prefixStyle('transition')] = '';
 				page._page.eq(page._pageNext)[0].style[global._prefixStyle('transform')] = '';
	 			page._page.eq(page._pageNext)[0].style[global._prefixStyle('transition')] = '';

	 			// 初始化切换的相关控制值
	 			$('.p-ct').removeClass('fixed');
	 			page._page.eq(page._pageNext).removeClass('active');
				page._page.eq(page._pageNext).removeClass('fixed');
				page._pageNow = page._pageNext;
				page._moveStart = true;
				page._moveFirst = true;
				page._pageNext = null;
				page._page.eq(page._pageNow).removeClass('fixed');
				page._page.eq(page._pageNow).attr('data-translate','');
				page._touchDeltaY = 0;

				// 切换成功后，执行当前页面的动画---延迟200ms
				setTimeout(function(){
					if(page._page.eq(page._pageNow).hasClass('z-animate')) return;
					page._page.eq(page._pageNow).addClass('z-animate');
				},20)

				// 隐藏图文组件的文本
				$('.j-detail').removeClass('z-show');
				$('.txt-arrow').removeClass('z-toggle');

				// 切换停止视频的播放
				$('video').each(function(){
					if(!this.paused) this.pause();
				})

				// 设置富文本的高度
				sigeFn.Txt_init(page._page.eq(page._pageNow));

				// 判断是否滑动最后一页，并让轻APP介绍关联页面贤淑
	 			if(page._page.eq(page._pageNow).next('.m-page').length == 0){
	 				sigeFn.lightapp_intro_show();
	 				sigeFn.lightapp_intro();
	 			}
	 		},300)

			// 切换成功后，发送统计
			var laytouType = page._page.eq(page._pageNow).attr('data-statics');
			
			sigeFn.ajaxTongji(laytouType);
		})

		// 声音播放事件
		global._on('audio_play',function(){
			// 开启音符冒泡
 			$.fn.coffee.start();
 			$('.coffee-steam-box').show(500);
		});

		// 声音停止事件
		global._on('audio_pause',function(){
			// 关闭音符冒泡
 			$.fn.coffee.stop();
 			$('.coffee-steam-box').hide(500);
		});

		// 视频打开
		global._on('video_open',function(video){
			var audio = media._audio;

			media._audioNode.addClass('z-low');
			$('.u-arrow').addClass('f-hide');
			$(document.body).css('height', $(window).height());

			if (audio) {
				audio.pause();
			}
			page.page_stop();
		});

		// 视频关闭
		global._on('video_close',function(video){
			var audio = media._audio;

			media._audioNode.removeClass('z-low');
			$('.u-arrow').removeClass('f-hide');
			$(document.body).css('height', '100%');

			if (audio) {
				audio.play();
			}
			page.page_start();
		});
 	}

 	// 加载直接执行事件绑定函数
 	$(function(){
 		haddle_envent_fn();
 	})
})
