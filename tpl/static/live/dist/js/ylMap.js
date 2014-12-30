/*  
 *  地图插件
 *  -----------------------------
 *  时间：2014-03-12
 *  准则：Zepto插件
 ******************************************************************************************
 *
 *	这是个半成品
 * -----------保持队形------------------
 *  <div class="ylmap bigOpen">
		<!--地图插件元素-->
		<div class='bk'><span class='css_sprite01 s-bg-map-logo'></span></div>
	</div>
* --------通过js传值，来创建地图---------
* 	见99_main.js---map篇 - -||
*********************************************************************************************/
define(function (require, exports, module){
	var Zepto = require('./zepto');
  	module.exports = Zepto;

  	;(function($){
		/*
		** ylmap插件函数
		*/
		$.fn.ylmap = function(options){
			// 默认参数
			$.fn.ylmap.defaults = {
				detal			: {sign_name:'TXjiang',contact_tel:18624443174,address:'天安门'},		//地址值
				latitude		: 39.915,															//纬度
				longitude		: 116.404,															//经度
				fnOpen			: null,																//打开地图回调函数
				fnClose			: null																//关闭地图回调函数
			};
			
			/* 初始值继承 */
			var opts = $.extend({},$.fn.ylmap.defaults, options);
			
			return this.each(function(){
				/*
				**  地图参数和控制值
				*/
				var mapBoxPaent		= $(this),				//地图盒子
					mapVal			= opts.detal,			//获取到存放数据的Jquery对象
					latTarget		= opts.latitude,		//获取目标纬度坐标
					lngTarget		= opts.longitude,		//获取目标经度坐标
					fnClose			= opts.fnClose,			//关闭地图回调函数
					fnOpen			= opts.fnOpen,			//打开地图的回调函数
					
					bigOpen			= mapBoxPaent.hasClass('bigOpen'),		//判断是不是全屏打开
					way				= null,					//导航的方式
					transit 		= null,					//公交车导航
					driving 		= null,					//自驾导航
					location_point 	= null;					//设备当前的point对象

				var movestart,map,point,marker;

				/*
				** 生成地图的对象和以及其他对象
				*/
				var mapBox = $('<div id="BDMap" class="BDMap"></div>');			//地图容器Jquery对象
					mapBoxPaent.append(mapBox);
					mapBoxPaent.append($('<div id="transit_result"></div>'));
					mapBoxPaent.append($('<div class="tit"><p><a href="javascript:void(0)"><span class="css_sprite01"></span>'+mapVal.address+'</a></p></div>'));
					mapBoxPaent.append($('<p class="map_close_btn">退出</p>'));
			
				if(mapBoxPaent.length>0) var mapH = mapBoxPaent.height();
				if(bigOpen) mapBoxPaent.find(".map_close_btn").css('display','block');

				/*
				** 地图初始化
				** _marker标识全局变量
				** _map地图全局变量
				*/
				/*创建新的地图，之前清除原来已有的地图*/
				// if(typeof(map)!=='undefined') mapBoxPaent.html('');
				if($('#transit_result').length>0&&$('#transit_result').html()!=''){
					$(".transitBtn").removeClass('hide');
				}

				var mapInit = function() {
					if(mapBoxPaent.size()>0){
						map 	= new BMap.Map(mapBox.attr("id")),				//创建Map实例--容器是当前jquery对象
						point	= new BMap.Point(lngTarget,latTarget),   		//全局变量
						marker	= new BMap.Marker(point);     					//全局变量地图标注

						map.enableScrollWheelZoom();        		  			//启用滚轮放大缩小
						map.enableInertialDragging();         					//启用地图拖曳
						map.centerAndZoom(point,15);							//将目标定位在地图的中心
						map.addOverlay(marker);              					//将标注添加到地图中
						mapOpenInfo();
						marker.addEventListener("click", function(e){    		//marker点击重新打开窗口
							mapOpenInfo();
						});
						map.addEventListener("click", function(e){    			//地图点击窗口不关闭
							return false;
						}); 
						// 重新回到中心
						map.addEventListener('zoomend',function(e){
							var zoom = map.getZoom();
							map.centerAndZoom(point,zoom);
						});
					}
				},
				
				/*
				**点击地图浮层文本调用信息窗口出现
				*/
				mapOpenInfo = function(){
					//var data = eval('('+mapVal+')');						//json对象，存放目标对象信息
					mapAddInfo(marker,mapVal);								//并且打开info窗口显示目标信息
				},
			
				/**
				 **创建目标信息窗口
				 * @param marker  百度地图marker标注
				 * @param data    数据表plugin_store记录
				 * @text 		  作为Jquery对象传入
				 */
				mapAddInfo = function(marker_,data) {
					/*
					**动态加载信息元素
					*/
					var content_infoWindow = $('<div class="infoWindow"></div>');
					/*
					content_infoWindow.append('<h4>'+data.sign_name+'</h4>')
					*/
					if(typeof(data.contact_tel)!='undefined'){
						content_infoWindow.append('<p class="tel"><a href="tel:'+data.contact_tel+'">'+data.contact_tel+'</a></p>')
					}
					content_infoWindow.append('<p class="address">'+data.address+'</p>')
					content_infoWindow.append('<div class="window_btn"><span class="open_navigate open_bus" onclick="open_navigate(this)">公交</span><span class="open_navigate open_car" onclick="open_navigate(this)">自驾</span><span class="State"></span></div>')
			
					/*
					**打开信息窗口
					*/
					var opts = {    
						width : 0,    	 // 信息窗口宽度 0为auto   
						height: 0,  		 // 信息窗口高度 0为auto  
						title:' '
					}  
			
					var info = new BMap.InfoWindow(content_infoWindow[0],opts);
					marker_.openInfoWindow(info,map.getCenter());
			
				};
			
				open_navigate = function(obj){
					$(obj).hasClass("open_bus") ? way = 'bus' : way = 'car';				 //打开导航窗口
					navigate();
					$('.infoWindow').find('span.State').html('正在定位您的位置！');	
				
				},
			
			
				//获取设备当前的坐标并且存放到location_point中
				/**************************************************************************************************************
				 * 设备定位获取location坐标值
				 */
				navigate = function(){
					if (window.navigator.geolocation) {
						window.navigator.geolocation.getCurrentPosition(handleSuccess, handleError, {timeout: 10000});
					}else{
						alert('sorry！您的设备不支持定位功能')
					}
				},
			
				/**
				 * 定位失败
				 */
				handleError = function(error){
					var msg;
					switch(error.code) {
						case error.TIMEOUT:
							msg = "获取超时!请稍后重试!";
							break;
						case error.POSITION_UNAVAILABLE:
							msg = '无法获取当前位置!';
							break;
						case error.PERMISSION_DENIED:
							msg = '您已拒绝共享地理位置!';
							break;
						case error.UNKNOWN_ERROR:
							msg = '无法获取当前位置!';
							break;
					}
					if ($('.infoWindow').find('span.State').length>0) {
						$('.infoWindow').find('span.State').html(msg);
					} else {
						alert(msg);
					}
				},
				
				/**
				 * 获得当前手机位置信息
				 */
				handleSuccess = function(position){
					// 获取到当前位置经纬度 
					var coords = position.coords;
					var lat = coords.latitude;
					var lng = coords.longitude;
					location_point = new BMap.Point(lng,lat);
					$('.infoWindow').find('span.State').html('获取信息成功，正在加载中！');
					//选择导航方式
					if(way=="bus")	bus_transit();
					else self_transit();
					//展开地图窗口
					if(!bigOpen) mapBox.parent().addClass("open");
					else mapBox.parent().addClass("mapOpen");
					
					//绑定取消事件
					// mapBoxPaent.find(".close_map").on('click',function(){
					// 	if(bigOpen) $(this).css('position','absolute');
						
					// 	//关闭地图
					// 	if(!bigOpen){ 
					// 		mapBoxPaent.removeClass("open");
					// 		if(fnClose) fnClose();
					// 	}else {
					// 		mapBoxPaent.removeClass("mapOpen");
					// 		if(fnClose) fnClose();
					// 	}
			
					// 	//清楚路线
					// 	if(transit)	transit.clearResults();
					// 	if(driving)	driving.clearResults();
						
					// 	//返回中心定位点
					// 	map.reset();
					// 	map.centerAndZoom(point,15);
					// 	mapBoxPaent.find(".close_map").hide();
						
					// 	//弹出信息窗口
					// 	mapOpenInfo();
			
					// 	//关闭导航文字和切换按钮
					// 	$('#transit_result').removeClass("open");
					// 	$(".transitBtn").hide();
						
					// 	//地图返回状态
					// 	if(!bigOpen) {
					// 		mapBoxPaent.css({
					// 			'position':'relative',
					// 			'top':'auto',
					// 			'left':'auto',
					// 			'height':mapH,
					// 		});
					// 	}
					// });
				};

				/*
				**打开导航
				*/
				// mapBoxPaent.find(".tit p").on('click',function(){
				// 	if(!bigOpen) mapBoxPaent.toggleClass("open");
				// 	else{
				// 		mapBoxPaent.toggleClass("mapOpen");
				// 	}
				// 	if(!mapBoxPaent.hasClass('open')||!mapBoxPaent.hasClass('mapOpen')){
				// 		if(fnClose) fnClose();	
				// 	}
				// });
				
				$('.map_close_btn').on('click',function(){
					mapBoxPaent.removeClass("mapOpen open");
					if(fnClose) fnClose();
				});
			
				/************************************************************************************************************/
				/**
				 * 画公交路线
					*/
				bus_transit = function(){
					//清楚路线
					if(transit)	transit.clearResults();
					if(driving)	driving.clearResults();
			
					
					if(!location_point){
						alert('抱歉：定位失败！');
						return;
					}
					$('.fn-audio').hide();
					if(typeof(loadingPageShow)=="function") loadingPageShow();
					$('.infoWindow').find('span.State').html('正在绘制出导航路线');
					var transit_result = $("#transit_result") || $('<div id="transit_result"></div>');
					transit_result.appendTo(mapBoxPaent);
					transit = new BMap.TransitRoute(map, {
						renderOptions: {map: map,panel: "transit_result",autoViewport: true },onSearchComplete :searchComplete
					});
					transit.search(location_point, point);
				},
			
				/**
				 * 画自驾路线
					*/
				self_transit = function(){
					//清楚路线
					if(transit)	transit.clearResults();
					if(driving)	driving.clearResults();
					
					if(!location_point){
						alert('抱歉：定位失败！');
						return;
					}
					$('.fn-audio').hide();
					if(typeof(loadingPageShow)=="function") loadingPageShow();
					$('.infoWindow').find('span.State').html('正在绘制出导航路线');
					var transit_result = $("#transit_result") || $('<div id="transit_result"></div>');
					transit_result.appendTo(mapBoxPaent);
					driving = new BMap.DrivingRoute(map, {
							renderOptions: {map: map,panel: transit_result.attr('id'),autoViewport: true },onSearchComplete :searchComplete
						});
					driving.search(location_point, point);
				},
				/**
				 * @param result
				 * 线路搜索回调
				 */
				searchComplete = function(result) {
					if (result.getNumPlans() == 0) {
						
						alert('非常抱歉,未搜索到可用路线');
						//重置地图
						map.reset();
						map.centerAndZoom(point,15);
						
						mapOpenInfo();
						$('#transit_result').removeClass("open").hide();
						$(".transitBtn").hide();
						
					}else{
						$('#transit_result').addClass("open");	
						$('.infoWindow').find('span.State').html('');
						if(!$('.transitBtn').length>0){
							$('#transit_result').after($('<p class="transitBtn close" onclick="transit_result_close()"><a href="javascript:void(0)">关闭</a></p>'));
							$('#transit_result').after($('<p class="transitBtn bus" onclick="bus_transit()"><a href="javascript:void(0)">公交</a></p>'));
							$('#transit_result').after($('<p class="transitBtn car" onclick="self_transit()"><a href="javascript:void(0)">自驾</a></p>'));
						}
						mapBoxPaent.find(".close_map").show();
						//打开导航文字和切换按钮
						$("#transit_result").addClass("open");
						$(".transitBtn").show();

						//导航页面绑定滑动事件
						$("#transit_result").on('touchstart',start)
						$("#transit_result").on('touchmove',move)

						function start(e){
							var startP;
							startP = window.event.touches[0].pageY;
							movestart = startP;	
						}
						
						//滚动条控制事件
						function move(e){
							e.stopPropagation();	
							e.preventDefault();
							var moveP;
		
							moveP = window.event.touches[0].pageY;
							var scrollTop = $(this).scrollTop();
							$(this).scrollTop(scrollTop+movestart-moveP);
							movestart = moveP;
						}

					}
					if(typeof(loadingPageHide)=="function") loadingPageHide();
					
					//展开地图
					if(!bigOpen){
						mapBoxPaent.css({
								'position':'fixed',
								'top':'0',
								'left':'0',
								'height':'100%',
							});
					}
					if($("#transit_result").hasClass("open")){
						$(".close").find("a").html("关闭");
					}
					else{
						$(".close").find("a").html("打开");
					}
					
				};
				
				/*
				**transit_result导航窗口切换
				*/
				transit_result_close = function(){
					if($("#transit_result").hasClass("open")){
						$('#transit_result').removeClass("open");
						$(".close").find("a").html("打开");
					}
					else{
						$('#transit_result').addClass("open");
						$(".close").find("a").html("关闭");
					}
				};
			
				/*
				**异步创建加载一个脚本--map
				*/
				window.mapInit = mapInit;	//将初始化函数提升为全局函数 可以调用动态API地图
					
				function loadfunction() { 
					if ($('.BDS').length<=0){
						/*加载百度地图API*/
						var script = document.createElement("script");  
						script.src = "http://api.map.baidu.com/api?v=1.4&callback=mapInit";
						script.className += 'BDS';

						document.head.appendChild(script);
					} else {
						mapInit();
					}
				
					if($('.BDC').length<=0){
						/*加载百度地图样式--插件样式*/
						var Style = document.createElement("style");  
						Style.type = "text/css";
						Style.className += 'BDC'
						
						var isPC = IsPC();
						if(isPC) {
							mapScale = 1;
							phoneScale = 1;
						}
						else{
							if(phoneScale>1)
								mapScale = 1;
							else
								mapScale = 1/phoneScale;
						}
						
						var height = $(window).height();

						var style_map =
								 ".ylmap.open,.ylmap.mapOpen {height:100%;width:100%;background:#fff;}"+
								 ".ylmap img {max-width:initial!important;}"+
								 ".ylmap .tit { position:absolute; left:0; bottom:0; height:70px; width:100%; overflow: hidden; background:rgba(0,0,0,0.5); }"+
								 ".ylmap .tit p { margin-right:100px; }"+
								 ".ylmap .tit p a { position:relative; display:block; font-size:24px; color:#fff; height:70px; line-height:70px; padding-left:70px; }"+
								 ".ylmap .tit p a span { position:absolute; left:15px; top:15px; display:inline-block; width:40px;height:40px; }"+
								 ".ylmap .tit .close_map { display:none; position: absolute; bottom: 15px; right: 20px; width: 40px; height: 40px; margin-right:0; cursor:pointer; background-position: -100px -73px; }"+
								 ".ylmap .map_close_btn{position:absolute;top:10px;left:10px;display:none;width:80px;box-shadow:0 0 2px rgba(0,0,0,0.6) inset, 0 0 2px rgba(0,0,0,0.6);height:80px;border-radius:80%;color:#fff;background:rgba(230,45,36,0.8);text-align:center;line-height:80px;font-size:26px; font-weight:bold;cursor:pointer;}"+
								 ".ylmap.open .map_close_btn{display:block;}"+
								 ".ylmap.mapOpen .map_close_btn{display:block;}"+
								 "#BDMap {transform:scale("+mapScale+");-webkit-transform:scale("+mapScale+");}"+
								 "#BDMap {width:100%;height:100%;}"+
								 "#BDMap img{width:auto;height:auto;}"+
								 ".ylmap.open .transitBtn{display:block;}"+
								 ".ylmap.mapOpen .transitBtn{display:block;}"+
								 ".transitBtn {display:none;position:absolute;z-index:3000;}"+
								 ".transitBtn a{display:block;width:80px;box-shadow:0 0 2px rgba(0,0,0,0.6) inset, 0 0 2px rgba(0,0,0,0.6);height:80px;border-radius:80%;color:#fff;background:rgba(230,45,36,0.8);text-align:center;line-height:80px;font-size:24px; font-weight:bold}"+
								 ".transitBtn.close {top:10px;right:10px;}"+
								 ".transitBtn.bus {top:10px;right:110px;}"+
								 ".transitBtn.car {top:110px;right:10px;}"+
								 ".transitBtn.bus a{background:rgba(28,237,235,0.8);}"+
								 ".transitBtn.car a{background:rgba(89,237,37,0.8);}"+
								 "#transit_result{display:none;position:absolute;top:0;left:0;width:100%;height:100%;z-index:1000;overflow-y:scroll;}"+
								 "#transit_result.open{display:block;}"+
								 "#transit_result h1{font-size:26px!important;}"+
								 "#transit_result div[onclick^='Instance']{background:none!important;}"+
								 "#transit_result span{display:inline-block;font-size:20px;padding:0 5px;}"+
								 "#transit_result table {font-size:20px!important;}"+
								 "#transit_result table td{padding:5px 10px!important;line-height:150%!important;}"+
								 ".infoWindow p{margin-bottom:10px;}"+
								 ".infoWindow .window_btn .open_navigate{display:inline-block;padding:2px 6px; margin-right:10px;border:1px solid #ccc;border-radius:6px;text-align:center;cursor:pointer;}"+
								 ".anchorBL{display:none!important;}";
						Style.innerHTML = style_map ;
						document.head.appendChild(Style);
					}
				}

				function IsPC(){ 
					var userAgentInfo = navigator.userAgent; 
					var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
					var flag = true; 
					for (var v = 0; v < Agents.length; v++) { 
						if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; } 
					} 
					return flag; 
				}

				loadfunction();
			})
		}
	})(Zepto);
})
