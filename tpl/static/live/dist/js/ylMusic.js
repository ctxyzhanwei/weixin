/**
 *  音符漂浮插件
 *  -----------------------------
 *  准则：zepto
 ********************************************************************************************
 *  别人写的东西，重复利用
 *  
 * -----------保持队形------------------
 *  <div id='coffee'></div>
 *********************************************************************************************/
//     Zepto.js
//     (c) 2010-2014 Thomas Fuchs
//     Zepto.js may be freely distributed under the MIT license.
define(function (require, exports, module){
  var Zepto = require('./zepto');
      Zepto = require('./fx');
  module.exports = Zepto;

  ;(function($){
    // 音符的漂浮的插件制作--zpeto扩展
    // 利用zpeto的animate的动画-css3的动画-easing为了css3的easing(zpeto没有提供easing的扩展)
  	$.fn.coffee = function(option){
      // 动画定时器
      var __time_val=null;
      var __time_wind=null;
      var __flyFastSlow = 'cubic-bezier(.09,.64,.16,.94)';

      // 初始化函数体，生成对应的DOM节点
  		var $coffee = $(this);
  		var opts = $.extend({},$.fn.coffee.defaults,option);  // 继承传入的值

      // 漂浮的DOM
      var coffeeSteamBoxWidth = opts.steamWidth;
      var $coffeeSteamBox = $('<div class="coffee-steam-box"></div>')
        .css({
          'height'   : opts.steamHeight,
          'width'    : opts.steamWidth,
          'left'     : 60,
          'top'      : -50,
          'position' : 'absolute',
          'overflow' : 'hidden',
          'z-index'  : 0 
        })
        .appendTo($coffee);

      // 动画停止函数处理
      $.fn.coffee.stop = function(){
        clearInterval(__time_val);
        clearInterval(__time_wind);
      }

      // 动画开始函数处理
      $.fn.coffee.start = function(){
        __time_val = setInterval(function(){
          steam();
        }, rand( opts.steamInterval / 2 , opts.steamInterval * 2 ));

        __time_wind = setInterval(function(){
          wind();
        },rand( 100 , 1000 )+ rand( 1000 , 3000))
      }
  		return $coffee;
  		
      // 生成漂浮物
      function steam(){	
        // 设置飞行体的样式
  			var fontSize = rand( 8 , opts.steamMaxSize  ) ;     // 字体大小
        var steamsFontFamily = randoms( 1, opts.steamsFontFamily ); // 字体类型
        var color = '#'+ randoms(6 , '0123456789ABCDEF' );  // 字体颜色
  			var position = rand( 0, 44 );                       // 起初位置
  			var rotate = rand(-90,89);                          // 旋转角度
  			var scale = rand02(0.4,1);                          // 大小缩放
        var transform =  $.fx.cssPrefix+'transform';        // 设置音符的旋转角度和大小
            transform = transform+':rotate('+rotate+'deg) scale('+scale+');'

        // 生成fly飞行体
  			var $fly = $('<span class="coffee-steam">'+ randoms( 1, opts.steams ) +'</span>');
  			var left = rand( 0 , coffeeSteamBoxWidth - opts.steamWidth - fontSize );
  			if( left > position ) left = rand( 0 , position );
  			$fly
          .css({
            'position'     : 'absolute',
            'left'         : position,
            'top'          : opts.steamHeight,
            'font-size:'   : fontSize+'px',
            'color'        : color,
            'font-family'  : steamsFontFamily,
            'display'      : 'block',
            'opacity'      : 1
          })
          .attr('style',$fly.attr('style')+transform)
          .appendTo($coffeeSteamBox)
          .animate({
    				top		: rand(opts.steamHeight/2,0),
    				left	: left,
    				opacity	: 0
  			  },rand( opts.steamFlyTime / 2 , opts.steamFlyTime * 1.2 ),__flyFastSlow,function(){
  				  $fly.remove();
  				  $fly = null;			
  			 });
  		};
  		
      // 风行，可以让漂浮体，左右浮动
  		function wind(){
        // 左右浮动的范围值
        var left = rand( -10 , 10 );
        left += parseInt($coffeeSteamBox.css('left'));
        if(left>=54) left=54;
        else if(left<=34) left=34;

        // 移动的函数
        $coffeeSteamBox.animate({
          left  : left 
        } , rand( 1000 , 3000) ,__flyFastSlow);
  		};
  		
      // 随即一个值
      // 可以传入一个数组和一个字符串
      // 传入数组的话，随即获取一个数组的元素
      // 传入字符串的话，随即获取其中的length的字符
  		function randoms( length , chars ) {
  			length = length || 1 ;
  			var hash = '';                  // 
  			var maxNum = chars.length - 1;  // last-one
  			var num = 0;                    // fisrt-one
  			for( i = 0; i < length; i++ ) {
  				num = rand( 0 , maxNum - 1  );
  				hash += chars.slice( num , num + 1 );
  			}
  			return hash;
  		};

      // 随即一个数值的范围中的值--整数
  		function rand(mi,ma){   
  			var range = ma - mi;
  			var out = mi + Math.round( Math.random() * range) ;	
  			return parseInt(out);
  		};	

      // 随即一个数值的范围中的值--浮点
  		function rand02(mi,ma){   
  			var range = ma - mi;
  			var out = mi + Math.random() * range;	
  			return parseFloat(out);
  		};		
  	};

  	$.fn.coffee.defaults = {
  		steams				    : ['jQuery','HTML5','HTML6','CSS2','CSS3','JS','$.fn()','char','short','if','float','else','type','case','function','travel','return','array()','empty()','eval','C++','JAVA','PHP','JSP','.NET','while','this','$.find();','float','$.ajax()','addClass','width','height','Click','each','animate','cookie','bug','Design','Julying','$(this)','i++','Chrome','Firefox','Firebug','IE6','Guitar' ,'Music' ,'攻城师' ,'旅行' ,'王子墨','啤酒'], /*漂浮物的类型，种类*/
  		steamsFontFamily	: ['Verdana','Geneva','Comic Sans MS','MS Serif','Lucida Sans Unicode','Times New Roman','Trebuchet MS','Arial','Courier New','Georgia'],  /*漂浮物的字体类型*/
  		steamFlyTime		  : 5000 , /*Steam飞行的时间,单位 ms 。（决定steam飞行速度的快慢）*/
  		steamInterval	    : 500 ,  /*制造Steam时间间隔,单位 ms.*/
  		steamMaxSize		  : 30 ,   /*随即获取漂浮物的字体大小*/
  		steamHeight	  	  : 200,   /*飞行体的高度*/
  		steamWidth	      : 300    /*飞行体的宽度*/
  	};
  	$.fn.coffee.version = '2.0.0'; // 更新为音符的悬浮---重构的代码
  })(Zepto);
})

