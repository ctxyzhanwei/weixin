/**
author : zhupinglei||344184416@qq.com
desc : zAlert
**/

(function($){
	var defaultOpts = {
		width : 250,	//宽度
		title : '消息提示框',		//标题
		content : '点击确定关闭弹框',	//内容
		mask : true,	//是否有遮罩
		radius : 5, 	//是否有圆角
		zIndex : 999999,	//z-index
		closed : true,	//是否显示关闭按扭
		closedCallback : null,	//关闭按扭回调函数
		btn : '确定',	//.Alert方法 按扭文本
		callback : function(){},	//.Alert方法 回调函数
		sureTxt : '确定',	//.Confirm方法 按扭文本
		sureCallback : function(){},	//.Confirm方法 回调函数
		cancelTxt : '确定',	//.Confirm方法 按扭文本
		cancelCallback : function(){}	//.Confirm方法 回调函数
	};

	function setDom(opts,callback){
		$('#zAlert').size() ? $('#zAlert').remove() : '';
		var wrapStr = '<div id="zAlert"><div id="zAlertWrap"><div class="zAlertTit">'+opts.title+'<a href="javascript:void(0);" class="Closed">&times;</a></div><div class="zAlertCon">'+opts.content+'</div><div class="zAlertBtn"></div></div><div id="zAlertMask"></div></div>';
		$(wrapStr).appendTo('body');
		var $zAlertWrap = $('#zAlertWrap'),
			$zAlertMask = $('#zAlertMask'),
			$zAlertTit = $('.zAlertTit',$zAlertWrap),
			$zAlertCon = $('.zAlertCon',$zAlertWrap),
			$zAlertBtn = $('.zAlertBtn',$zAlertWrap);

		$('#zAlert').css({'z-index':opts.zIndex});

		if( opts.mask ){
			$zAlertMask.show();
		}

		if( opts.closed ){
			$zAlertTit.find('a.Closed').show().on('click',function(){
				if( typeof opts.closedCallback == 'function' ){
					opts.closedCallback();
				}else{
					$('#zAlert').remove();
				}
			});
		}

		function setSize(){
			var winWidth = $(window).width(),
				winHeight = $('#zAlertMask').height(),
				awidth = opts.width,
				aheight = $zAlertWrap.height();
			$zAlertWrap.css({
				width : awidth+'px',
				left : (winWidth-awidth)/2+'px',
				top : (winHeight-aheight)/2+'px',
				'border-radius' : opts.radius+'px'
			})
		}
		setSize();
		$(window).resize(function(){setSize();});
		callback();
	}

	//Info
	var _Info = function(opts){
		setDom(opts,function(){
			$('#zAlertWrap .zAlertBtn').remove();
		});
	}

	//Alert
	var _Alert = function(opts){
		var btns = '<a href="javascript:void(0);" class="zBtn">'+opts.btn+'</a>';
		setDom(opts,function(){
			$('#zAlertWrap .zAlertBtn').html(btns);
			$('#zAlertWrap .zAlertBtn a.zBtn').on('click',function(){
				opts.callback();
			})
		});
	}

	//Confirm
	var _Confirm = function(opts){
		var btns = 	'<a href="javascript:void(0);" class="zBtn cancelBtn">'+opts.cancelTxt+'</a><a href="javascript:void(0);" class="zBtn sureBtn">'+opts.sureTxt+'</a>';
		setDom(opts,function(){
			$('#zAlertWrap .zAlertBtn').html(btns);
			$('#zAlertWrap .zAlertBtn a.zBtn').on('click',function(){
				if( $(this).hasClass('sureBtn') ){
					opts.sureCallback();
				}else if( $(this).hasClass('cancelBtn') ){
					opts.cancelCallback();
				}
			})
		});
	}

	//Control Model
	$.fn.zAlert = zAlert = {
		Info : function(opts){
			opts = $.extend({},defaultOpts,opts);
			return new _Info(opts);
		},
		Alert : function(opts){
			var options = {
				btn : '确定',
				callback : function(){}
			}
			opts = $.extend({},defaultOpts,options,opts);
			return new _Alert(opts);
		},
		Confirm : function(opts){
			var options = {
				sureTxt : '确定',
				sureCallback : function(){},
				cancelTxt : '取消',
				cancelCallback : function(){}
			}
			opts = $.extend({},defaultOpts,options,opts);
			return new _Confirm(opts);
		},
		Close : function(){
			$('#zAlert').remove();
		},
		Hide : function(){
			$('#zAlert').hide();
		},
		Show : function(){
			$('#zAlert').show();
		}
	}

})(jQuery);