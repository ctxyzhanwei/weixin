/**
 *  全局函数处理
 *  -----------------------------
 *  准则：Zpote、字面量对象
 *********************************************************************************************/
/**
 * 单个函数处理模块
 */
define(function __sigeFn(require, exports, module){
	var $ = require('./zepto');
	var global = require('./global');
	var page = require('./page');
	var map = require('./map');
	var form = require('./form');

	var __sigeFn = {
		//绑定地图出现函数
		mapCreate	: function(){
			if('.j-map'.length<=0) return;

			var node = $('.j-map');

			//option地图函数的参数
			var option ={
				fnOpen	: global._scrollStop,
				fnClose	: map.mapSave
			};
			map.mapAddEventHandler(node,'click',map.mapShow,option);
		},

		// 富文本的设置
		Txt_init : function(node){
			if (node.find('.j-txt').length<=0) {
				return;
			}
			if (node.find('.j-txt').find('.j-detail p').length<=0) {
				return;
			}

			node.find('.j-txt').each(function(){
				var txt = $(this).find('.j-detail'),
					title = $(this).find('.j-title'),
					arrow = title.find('.txt-arrow'),
					p = txt.find('p'),
					height_t = parseInt(title.height()),
					height_p = parseInt(p.height()),
					height_a = height_p+height_t;

				if (p.length <= 0) {
					return;
				}

				if ($(this).parents('.m-page').hasClass('m-smallTxt')) {
					if ($(this).parents('.smallTxt-bd').index() == 0) {
						txt.css('top',height_t);
					} else {
						txt.css('bottom',height_t);
					}
				}

				txt.attr('data-height',height_p);
				$(this).attr('data-height-init',height_t);
				$(this).attr('data-height-extand',height_a);

				p[0].style[global._prefixStyle('transform')] = 'translate(0,-'+height_p+'px)';
				if($(this.parentNode).hasClass('z-left')) p[0].style[global._prefixStyle('transform')] = 'translate(0,'+height_p+'px)';

				txt.css('height','0');
				arrow.removeClass('z-toggle');
				$(this).css('height',height_t);
			})
		},

		// 富文本组件点击展开详细内容
		bigTxt_extand : function(){
			$('body').on('click','.j-title',function(){
				if($('.j-detail').length<=0) return;

				// 定位
				var detail = $(this.parentNode).find('.j-detail');
				$('.j-detail').removeClass('action');
				detail.addClass('action');
				if($(this).hasClass('smallTxt-arrow')){
					$('.smallTxt-bd').removeClass('action');
					detail.parent().addClass('action');
				}

				// 设置
				if(detail.hasClass('z-show')){
					detail.removeClass('z-show');
					detail.css('height',0);
					$(this.parentNode).css('height',parseInt($(this.parentNode).attr('data-height-init')));
				}
				else{
					detail.addClass('z-show');
					detail.css('height',parseInt(detail.attr('data-height')));
					$(this.parentNode).css('height',parseInt($(this.parentNode).attr('data-height-extand')));
				}

				$('.j-detail').not('.action').removeClass('z-show');
				$('.txt-arrow').removeClass('z-toggle');

				detail.hasClass('z-show') ? ($(this).find('.txt-arrow').addClass('z-toggle')) : ($(this).find('.txt-arrow').removeClass('z-toggle'))
			})
		},

		// 文本点击其他地方收起
		Txt_back : function(){
			$('body').on('click','.m-page',function(e){
				e.stopPropagation();

				// 判断
				var node = $(e.target);
				var page = node.parents('.m-page');
				var txtWrap = node.parents('.j-txtWrap').length==0 ? node : node.parents('.j-txtWrap');
				if(page.find('.j-txt').find('.j-detail p').length<=0) return;
				if(page.find('.j-txt').length<=0||node.parents('.j-txt').length>=1 || node.hasClass('bigTxt-btn') || node.parents('.bigTxt-btn').length>=1) return;

				// 定位
				var detail = txtWrap.find('.j-detail');
				$('.j-detail').removeClass('action');
				detail.addClass('action');
				$('.j-detail').not('.action').removeClass('z-show');

				// 设置
				txtWrap.each(function(){
					var detail = $(this).find('.j-detail');
					var arrow = $(this).find('.txt-arrow');
					var txt = $(this).find('.j-txt');

					if(detail.hasClass('z-show')){
						detail.removeClass('z-show');
						detail.css('height',0);
						txt.css('height',parseInt(txt.attr('data-height-init')));
					}else{
						detail.addClass('z-show');
						detail.css('height',parseInt(detail.attr('data-height')));
						txt.css('height',parseInt(txt.attr('data-height-extand')));
					}

					detail.hasClass('z-show') ? (arrow.addClass('z-toggle')) : (arrow.removeClass('z-toggle'));
				})
			})
		},

		// 表单显示，输入
		input_form : function(){
			$('body').on('click','.book-bd .bd-form .btn',function(){
				var type_show = $(this).attr("data-submit");
				if (type_show == 'true') {
					return;
				}

				var heigt = $(window).height();

				$(document.body).css('height',heigt);
				page.page_stop();
				global._scrollStart();
				// 设置层级关系-z-index
				page._page.eq(page._pageNow).css('z-index',15);

				$('.book-bg').removeClass('f-hide');
				$('.book-form').removeClass('f-hide');
				setTimeout(function(){
					$('.book-form').addClass('z-show');
					$('.book-bg').addClass('z-show');
				},50)

				$('.book-bg').off('click');
				$('.book-bg').on('click',function(e){
					e.stopPropagation();

					var node = $(e.target);

					if(node.parents('.book-form').length>=1 && !node.hasClass('j-close-img') && node.parents('.j-close').length<=0) return;

					$('.book-form').removeClass('z-show');
					$('.book-bg').removeClass('z-show');
					setTimeout(function(){
						$(document.body).css('height','100%');
						page.page_start();
						global._scrollStop();
						// 设置层级关系-z-index
						page._page.eq(page._pageNow).css('z-index',9);
						
						$('.book-bg').addClass('f-hide');
						$('.book-form').addClass('f-hide');
					},500)
				})
			})
		},

		sex_select : function(){
			var btn = $('#j-signUp').find('.sex p');
			var strongs = $('#j-signUp').find('.sex strong');
			var input = $('#j-signUp').find('.sex input');

			btn.on('click',function(){
				var strong = $(this).find('strong');
				strongs.removeClass('open');
				strong.addClass('open');

				var value = $(this).attr('data-sex');
				input.val(value);
			})
		},

		// 显示轻APP按钮
		lightapp_intro_show : function(){
			$('.market-notice').removeClass('f-hide');
			setTimeout(function(){
				$('.market-notice').addClass('show');
			},100)
		},

		// 隐藏轻APP按钮
		lightapp_intro_hide : function(val){
			if(val){
				$('.market-notice').addClass('f-hide').removeClass('show');
				return;
			} 

			$('.market-notice').removeClass('show');
			setTimeout(function(){
				$('.market-notice').addClass('f-hide')
			},500)
		},

		// 轻APP介绍弹窗关联
		lightapp_intro : function(){
			// 点击按钮显示内容
			$('.market-notice').off('click');
			$('.market-notice').on('click',function(){
				$('.market-page').removeClass('f-hide');
				setTimeout(function(){
					$('.market-page').addClass('show');
					setTimeout(function(){
						$('.market-img').addClass('show');
					},100)
					__sigeFn.lightapp_intro_hide();
				},100)

				// 禁止滑动
				page.page_stop();
				global._scrollStop();
			});

			// 点击窗口让内容隐藏
			$('.market-page').off('click');
			$('.market-page').on('click',function(e){
				if($(e.target).hasClass('market-page')){
					$('.market-img').removeClass('show');
					setTimeout(function(){
						$('.market-page').removeClass('show');
						setTimeout(function(){
							$('.market-page').addClass('f-hide');
						},200)
					},500)
					__sigeFn.lightapp_intro_show();

					// 禁止滑动
					page.page_start();
					global._scrollStart();
				}
			});
		},

		//统计函数处理
	 	ajaxTongji	: function(laytouType){
			var channel_id = location.search.substr(location.search.indexOf("channel=") + 8);
			channel_id= channel_id.match(/^\d+/) ; 
			if (!channel_id || isNaN(channel_id) || channel_id<0) {
			channel_id = 1;
		}
	 	 	var activity_id = $('#activity_id').val();
	 	 	var url = "/analyseplugin/plugin?activity_id="+activity_id + "&plugtype="+laytouType;
			 //报名统计请求
		 	$.get(url,{},function(){});
	 	},

	 	// 微信的分享提示
	 	wxShare : function(){
	 		$('body').on('click','.bigTxt-btn-wx',function(){
	 			var img_wx = $(this).parent().find('.bigTxt-weixin');
	 			
	 			img_wx.addClass('z-show');
	 			page.page_stop();

	 			img_wx.on('click',function(){
	 				$(this).removeClass('z-show');
	 				page.page_start();

	 				$(this).off('click');
	 			})
	 		})
	 	},

	 	// video播放切换
		toggleVideo : function(){
			$('.j-video').find('.img').on('click',function(){
				var video = $(this).next()[0];

				if (video.length<=0) {
					return;
				}

	        	if (video.paused) {
	        		$(video).removeClass('f-hide');
	        		video.play();
	        		$(this).hide();
	        	}
			})
		},

		signUp_submit 	: function(){
	 		$('#j-signUp-submit').on('click',function(e){
		 		e.preventDefault();
				var _form = $(this).parents('#j-signUp');
		 		var valid = form.signUpCheck_input(_form,$('.u-note'));
		 		if(valid) {
		 			form.signUpCheck_submit(_form,$('.u-note'));
		 		}
		 		else return;
		 	})
	 	},

	 	// loading显示
		loadingPageShow : function(){
			$('.u-pageLoading').show();
		},
		
		// loading隐藏
		loadingPageHide : function (){
			$('.u-pageLoading').hide();	
		}
	}

	// 页面初始化--函数执行
	$(function(){
		__sigeFn.bigTxt_extand();
		__sigeFn.Txt_back();
		__sigeFn.input_form();
		__sigeFn.sex_select();
		__sigeFn.lightapp_intro();
		__sigeFn.wxShare();
		__sigeFn.mapCreate();
		__sigeFn.toggleVideo();
		__sigeFn.signUp_submit();

		// 设置富文本的高度
		__sigeFn.Txt_init(page._page.eq(page._pageNow));
	})

	return __sigeFn;
})

