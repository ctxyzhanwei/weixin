/**
 *  全局函数处理
 *  -----------------------------
 *  时间：2014-03-29
 *  准则：Zpote、sea.js
 *********************************************************************************************/
/**
 * 地图管理模块
 */
define(function __map(require, exports, module){
	var $ = require('./zepto');
		$ = require('./ylMap');

	var global = require('./global');
	var page = require('./page');
	var media = require('./media');

	var __map = {
		_map 			: $('.ylmap'),							// 地图DOM对象
		_mapValue		: null,									// 地图打开时，存储最近打开的一个地图
		_mapIndex		: null,									// 开启地图的坐标位置

		// 自定义绑定事件
		mapAddEventHandler	 : function(obj,eventType,fn,option){
		    var fnHandler = fn;
		    if(!global._isOwnEmpty(option)){
		        fnHandler = function(e){
		            fn.call(this, option);  //继承监听函数,并传入参数以初始化;
		        }
		    }
		    obj.each(function(){
		  	  $(this).on(eventType,fnHandler);
		    })
		},

		//点击地图按钮显示地图
		mapShow : function(option){
			// 获取各自地图的资源信息
			var str_data = $(this).attr('data-detal');
			option.detal = str_data != '' ? eval('('+str_data+')') : '';
			option.latitude = $(this).attr('data-latitude');
			option.longitude = $(this).attr('data-longitude');

			// 地图添加
			var detal		= option.detal,
				latitude	= option.latitude,
				longitude	= option.longitude,
			 	fnOpen		= option.fnOpen,
				fnClose		= option.fnClose;

			global._scrollStop();
			__map._map.addClass('show');
			$(document.body).animate({scrollTop: 0}, 0);
			
			//判断开启地图的位置是否是当前的
			if($(this).attr('data-mapIndex')!=__map._mapIndex){
				__map._map.html($('<div class="bk"><span class="css_sprite01 s-bg-map-logo"></span></div>'));
				__map._mapValue = false;
				__map._mapIndex = $(this).attr('data-mapIndex');

			}else{
				__map._mapValue = true;	
			} 

			setTimeout(function(){
				//将地图显示出来
				if(__map._map.find('div').length>=1){
					__map._map.addClass("mapOpen");
					page.page_stop();
					global._scrollStop();
					media._audioNode.addClass('z-low');
					// 设置层级关系-z-index
					page._page.eq(page._pageNow).css('z-index',15);

					setTimeout(function(){
						//如果开启地图的位置不一样则，创建新的地图
						if(!__map._mapValue) __map.addMap(detal,latitude,longitude,fnOpen,fnClose);
					},500)
				}else return;
			},100)
		},	
		
		//地图关闭，将里面的内容清空（优化DON结构）
		mapSave	: function(){
			$(window).on('webkitTransitionEnd transitionend',mapClose);
			if(page) page.page_start();
			global._scrollStart();
			__map._map.removeClass("mapOpen");
			if(media) media._audioNode.removeClass('z-low');

			if(!__map._mapValue) __map._mapValue = true;

			function mapClose(){
				__map._map.removeClass('show');
				$(window).off('webkitTransitionEnd transitionend');
			}
		},

		//地图函数传值，创建地图
		addMap	: function (detal,latitude,longitude,fnOpen,fnClose){
			var detal		= detal,
				latitude	= Number(latitude),
				longitude	= Number(longitude);

			var fnOpen		= typeof(fnOpen)==='function'? fnOpen : '',
				fnClose		= typeof(fnClose)==='function'? fnClose : '';

			//默认值设定
			var a = {sign_name:'',contact_tel:'',address:'天安门'};

			//检测传值是否为空，设置传值
			global._isOwnEmpty(detal)	? detal=a:detal=detal;
			!latitude? latitude=39.915:latitude=latitude;
			!longitude? longitude=116.404:longitude=longitude;
			
			//创建地图
			__map._map.ylmap({
				/*参数传递，默认为天安门坐标*/
				//需要执行的函数（回调）
				detal		: detal,		//地址值
				latitude	: latitude,		//纬度
				longitude	: longitude,	//经度
				fnOpen		: fnOpen,		//回调函数，地图开启前
				fnClose		: fnClose		//回调函数，地图关闭后
			});	
		}
	}

	return __map
});
 	



