/**
 *  全局函数处理
 *  -----------------------------
 *  时间：2014-03-29
 *  准则：Zpote、sea.js
 *********************************************************************************************/
/**
 * 图片延迟加载资源管理模块
 */
define(function __image(require, exports, module){
	var $ = require('./zepto');
	var page = require('./page');

	var __lazyImg = {
		// 延迟图片管理替换
		lazy_img : function(){
			var lazyNode = $('.lazy-img');
			lazyNode.each(function(){
				var self = $(this);
				if(self.is('img')){
					self.attr('src','./tpl/static/live/images/loading_large.gif');
				}else{
					// 把原来的图片预先保存下来
					var position = self.css('background-position'),
						size = self.css('background-size');

					self.attr({
						'data-position' : position,
						'data-size'	: size
					});

					if(self.attr('data-bg')=='no'){
						self.css({
							'background-repeat'	: 'no-repeat'
						})
					}

					self.css({
						'background-image'	: 'url(./tpl/static/live/images/loading_large.gif)',
						'background-size'	: '120px 120px',
						'background-position': 'center'
					})

					if(self.attr('data-image')=='no'){
						self.css({
							'background-image'	: 'none'
						})
					}
				}
			})
		},

		// 开始加载前三个页面
		lazy_start : function(){
			var _this = this;
			// 前三个页面的图片延迟加载
			setTimeout(function(){
				for(var i=0;i<3;i++){
					var node = $(".m-page").eq(i);
					if(node.length==0) break;
					if(node.find('.lazy-img').length!=0){
						_this.lazy_change(node,false);
						// 飞出窗口的延迟
						if(node.attr('data-page-type')=='flyCon'){
							_this.lazy_change($('.m-flypop'),false);
						}
					}else continue;
				}
			},5000)
		},
		
		// 加载当前后面第三个
		lazy_bigP : function(){
			if($('.lazy-img').length==0) return;

			for(var i=3;i<=5;i++){
				var node = $(".m-page").eq(page._pageNow+i);
				if(node.length==0) break;
				if(node.find('.lazy-img').length!=0){
					__lazyImg.lazy_change(node,true);
					// 飞出窗口的延迟
					if(node.attr('data-page-type')=='flyCon'){
						__lazyImg.lazy_change($('.m-flypop'),false);
					}
				}else continue;
			}
		},

		// 图片延迟替换函数
		lazy_change : function(node,goon){
			// 3d图片的延迟加载
			if(node.attr('data-page-type')=='3d') this.lazy_3d(node);

			// 飞出窗口的延迟
			if(node.attr('data-page-type')=='flyCon'){
				var img = $('.m-flypop').find('.lazy-img');
				img.each(function(){
					var self = $(this),
						srcImg = self.attr('data-src');

					$('<img />')
						.on('load',function(){
							if(self.is('img')){
								self.attr('src',srcImg)
							}
						})
						.attr("src",srcImg);
				})
			}

			// 其他图片的延迟加载
			var lazy = node.find('.lazy-img');
			lazy.each(function(){
				var self = $(this),
					srcImg = self.attr('data-src'),
					position = self.attr('data-position'),
					size = self.attr('data-size');
				alert(srcImg);
				if(self.attr('data-bg')!='no'){
					$('<img />')
						.on('load',function(){
							if(self.is('img')){
								self.attr('src',srcImg)
							}else{
								self.css({
									'background-image'	: 'url('+srcImg+')',
									'background-position'	: position,
									'background-size' : size
								})
							}

							// 判断下面页面进行加载
							if(goon){
								for(var i =0;i<$(".m-page").size();i++){
									var page = $(".m-page").eq(i);
									if($(".m-page").find('.lazy-img').length==0) continue
									else{
										__lazyImg.lazy_change(page,true);
									}
								}
							}
						})
						.attr("src",srcImg);

					self.removeClass('lazy-img').addClass('lazy-finish');
				}else{
					if(self.attr('data-auto')=='yes') self.css('background','none');
				}
			})	
		},

		// load事件触发来预加载图片
		lazy_load : function(){
			var lazy = $('.lazy-img.load');
			lazy.each(function(){
				var self = $(this),
					srcImg = self.attr('data-src'),
					position = self.attr('data-position'),
					size = self.attr('data-size');

				if(self.attr('data-bg')!='no'){
					$('<img />')
						.on('load',function(){
							if(self.is('img')){
								self.attr('src',srcImg)
							}else{
								self.css({
									'background-image'	: 'url('+srcImg+')',
									'background-position'	: position,
									'background-size' : size
								})
							}
						})
						.attr("src",srcImg);

					self.removeClass('lazy-img').addClass('lazy-finish');
				}else{
					if(self.attr('data-auto')=='yes') self.css('background','none');
				}
			})
		}
	}

	// 图片初始化替换
	$(function(){
		__lazyImg.lazy_img();
	})

	// 图片加载--绑定是在window-load事件执行（加载前三个page的图片）
	$(window).on('load',function(){
		__lazyImg.lazy_start();
		setTimeout(function(){__lazyImg.lazy_load();},200);
	})

	return __lazyImg;
});

