/**
 *  全局函数处理
 *  -----------------------------
 *  准则：Zpote、sea.js
 *********************************************************************************************/
/**
 * media资源管理模块
 */
define(function __media(require, exports, module){
	var $ = require('./zepto');
	var global = require('./global');

	var __media = {
		_audioNode		: $('.u-audio'),						// 声音模块
		_audio			: null,									// 声音对象
		_audio_val		: true,									// 声音是否开启控制

		_videoNode		: $('.j-video'),						// 视频DOM

		// 声音初始化
	 	audio_init : function(){
	 		if (__media._audioNode.length <= 0) {
	 			return;
	 		}

	 		// media资源的加载
			var options_audio = {
				loop: true,
	            preload: "auto",
	            src: this._audioNode.attr('data-src')
			}
			
	        this._audio = new Audio(); 

	        for(var key in options_audio){
	            if(options_audio.hasOwnProperty(key) && (key in this._audio)){
	                this._audio[key] = options_audio[key];
	            }
	        }
	        this._audio.load();
	 	},

	 	// 声音事件绑定
	 	audio_addEvent : function(){
	 		if(this._audioNode.length<=0) return;

	 		// 声音按钮点击事件
	 		var txt = this._audioNode.find('.txt_audio'),
	 			time_txt = null;

	 		// 声音打开事件
	 		$(this._audio).on('play',function(){
	 			__media._audio_val = false;

	 			audio_txt(txt,true,time_txt);

	 			// 播放事件
	    		global._handleEvent('audio_play');
	 		})

	 		// 声音关闭事件
	 		$(this._audio).on('pause',function(){
				__media._audio_val = true;

	 			audio_txt(txt,false,time_txt)

	 			// 停止事件
	    		global._handleEvent('audio_pause');
	 		})

	 		function audio_txt(txt,val,time_txt){
	 			if(val) txt.text('打开');
	 			else txt.text('关闭');

	 			if(time_txt) clearTimeout(time_txt);

	 			txt.removeClass('z-move z-hide');
	 			time_txt = setTimeout(function(){
	 				txt.addClass('z-move').addClass('z-hide');
	 			},1000)
	 		}
	 	},

	 	// 声音控制函数
	 	audio_contorl : function(){
	 		if(!__media._audio_val){
	 			__media.audio_stop();
	 		}else{
	 			__media.audio_play();
	 		}
	 	},	

	 	// 声音播放
	 	audio_play : function(){
	 		__media._audio_val = false;
	 		if(__media._audio) __media._audio.play();
	 	},

	 	// 声音停止
	 	audio_stop	: function(){
	 		__media._audio_val = true;
	 		if(__media._audio) __media._audio.pause(); 
	 	},

	 	// 视频初始化
	 	video_init : function(){
	 		// 视频
	        this._videoNode.each(function(){
	        	var option_video = {
	        		controls: 'controls',
	        		preload : 'none',
	        		// poster : $(this).attr('data-poster'),
	        		width : $(this).attr('data-width'),
	        		height : $(this).attr('data-height'),
	        		src : $(this).attr('data-src')
	        	}

	        	var video = $('<video class="f-hide"></video>')[0];
	        	var img = $(this).find('.img');

	        	for(var key in option_video){
	                if(option_video.hasOwnProperty(key) && (key in video)){
	                    video[key] = option_video[key];
	                }
	                this.appendChild(video);
	            }

	            $(video).on('play',function(){
	            	img.hide();
	            	$(video).removeClass('f-hide');
	            });

	            $(video).on('pause',function(){
	            	img.show();
	            	$(video).addClass('f-hide');
	            });
	        })
	 	},

	 	//处理声音和动画的切换
		media_control : function(){
			if(!this._audio) return;
			if($('video').length<=0) return;

			$(this._audio).on('play', function(){
				$('video').each(function(){
					if(!this.paused){
						this.pause();
					}
				});	
			});

			$('video').on('play', function(){
				if(!__media._audio_val){
					__media.audio_contorl();			
				}
			});

			$('video').on('pause', function(){
				if(__media._audio_val){
					__media.audio_contorl();			
				}
			});
		},

		// media管理初始化
		media_init : function(){
			// 声音初始化
			this.audio_init();

	        // 视频初始化
	        this.video_init();

			// 绑定音乐加载事件
			this.audio_addEvent();

			// 音频切换
			this.media_control();
		}
	}

	// 将media初始化绑定在window-load事件
	$(window).on('load',function(){
		__media._audioNode.find('.btn_audio').on('click',__media.audio_contorl);
	})

	return __media;
})

