(function () {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame']
                                   || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function (callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () { callback(currTime + timeToCall); },
              timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
}());

(function () {
    window.GameTimer = function (fn, timeout) {
        this.__fn = fn;
        this.__timeout = timeout;
        this.__running = false;
        this.__lastTime = Date.now();
        this.__stopcallback = null;
    };

    window.GameTimer.prototype.__runer = function () {
        if (Date.now() - this.__lastTime >= this.__timeout) {
            this.__lastTime = Date.now();
            this.__fn.call(this);
        }
        if (this.__running) {
            window.requestAnimationFrame(this.__runer.bind(this));
        }
        else {
            if (typeof this.__stopcallback === 'function') {
                window.setTimeout(this.__stopcallback,100);
            }
        }
    };

    window.GameTimer.prototype.start = function () {
        this.__running = true;
        this.__runer();
    };
    window.GameTimer.prototype.stop = function (callback) {
        this.__running = false;
        this.__stopcallback = callback;
    };

})();



$(function () {
    var url_rndprize = '/index.php?g=Wap&m=LuckyFruit&a=getajax';
    var url_getprize = '兑奖地址';
    var itemPositions = [
        0, //苹果
        100,//芒果
        200,//布林
        300,//香蕉
        400,//草莓
        500,//梨
        600,//桔子
        700,//青苹果
        800//樱桃
    ];

    //游戏开始
    var gameStart = function () {
        lightFlicker.stop();
        lightRandom.stop();
        lightCycle.start();

        //
        var marketing_id = $('.tigerslot').attr('activity_id');
        var token = $('.tigerslot').attr('data-token');
        var wechat_id = $('.tigerslot').attr('wechat_id');
        var rid = $('.tigerslot').attr('rid');
        $.post(url_rndprize, {
            id: marketing_id,
            token: token,
            wechat_id:wechat_id,
            rid:rid
        }, function (result) {
        	if(result.error){
        		alert(result.msg);
        		return;
        	}
        	if(result.success){
        		boxCycle.start(result.data);
        	}
        },'json');

    };

    //游戏结束
    var gameOver = function (resultData) {
        lightFlicker.start();
        lightRandom.stop();
        lightCycle.stop();

        //
        if(resultData.type == 0){
        	alert(resultData.prize_type);
        	$('.machine .gamebutton').removeClass('disabled');
        }else{
        	$('.machine .gamebutton').addClass('disabled');
        	$("#sncode").text(resultData.sn);
            $("#prize").text(resultData.prize);
            $("#result").slideDown(500);            
        }
		var rest_chance = parseInt($('#rest_chance').text()) - 1;
		rest_chance = rest_chance<0 ? 0 : rest_chance;
		$('#rest_chance').text(rest_chance);		
    };

    //准备兑奖
    var getprize = function (listid, prizeid, code) {
        var tel=prompt('获奖纪录id:' + listid + ' ,奖品ID:' + prizeid + ' ,兑奖编码：' + code +'\n请输入手机号码兑奖：');
        if ($.trim(tel)) {
            /*
            $.post(url_getprize, {
                listid: listid, prizeid: prizeid, code: code
            }, function (result) {
                //操作成功,
                //setPrizeList(listid);
            });
            */
          
        }
        else {
            return false;
        }
    };
    
    //
    var setPrizeList = function (listid) {
        console.log($prizelist);
        var p = $prizelist.find('li[prizelist_id="' + listid + '"]');
        p.addClass('hasGetPrize');
    };

    var $machine = $('.machine');
    var $slotBox = $('.tigerslot .box');
    var light_html = '';
    for (var i = 0; i < 21; i++) {
        light_html += '<div class="light l'+ i +'"></div>';
    }
    var $lights = $(light_html).appendTo($machine);
    var $result = $('#result').on('click', '.close-btn', function(){
    	$result.slideUp();
    	
        var submitData = {
                marketing_id: $('.tigerslot').attr('activity_id'),
                sn: $.trim($("#sncode").text()),
                wxid: $('.tigerslot').attr('data-token')
            };
        $.post('###', 
        		submitData,
        		function(data) {
					if (data.error == 1) {
						alert(data.msg);
						return;
					}        	
		            if (data.success == 1) {
		    			//window.location.reload();
		            	$('#result #prize').empty();
		            	$('#result #sncode').empty();
		            	$('.machine .gamebutton').removeClass('disabled');
		                return;
		            } else {
		
		            }
        		});   	
    });
    var $request_reward = $('#request-reward').on('click', '.close-btn', function(){
    	$request_reward.slideUp();
    })
    
    var $gameButton = $('.machine .gamebutton').tap(function () {
        var $this = $(this);
        if (!$this.hasClass('disabled')) {
            $this.addClass('disabled');
            $this.toggleClass(function (index, classname) {
                if (classname.indexOf('stop') > -1) {
                    boxCycle.stop(function (resultData) {
                        gameOver(resultData);
                        //$this.removeClass('disabled');
                    });
                } else {
                    gameStart();
                    window.setTimeout(function () {
                        $this.removeClass('disabled');
                    },1500);
                }
                return 'stop';
            });
        }
    });

    var $prizelist = $('.part.prizelist').on('tap', '.getprize', function () {
        var $this = $(this), $parent = $this.parent();
        var code = $parent.find('.code').html();
        $('#sn').val(code);
        $("#request-reward").slideToggle(500);

        return false;
    });
    
    //提交手机号码
    $('.part').on('tap', '#submit-btn', function () {
        var tel = $("#tel").val();
    	//var telreg = '/^1[3|4|5|8][0-9]\d{4,8}$/';
        if (tel == '') {
            alert("请认真输入有效资料");
            return
        }
        var submitData = {
        	lid: $('.tigerslot').attr('activity_id'),
        	sncode: $("#sncode").text(),
        	tel: tel,
        	wxname: '',
        	wechaid:$('.tigerslot').attr('wechat_id'),
        	rid:0,
        	action: "add"
        };
        $.post('/index.php?g=Wap&m=Lottery&a=add', submitData,
        		function(data) {
        			if (data.error == 1) {
        				alert(data.msg);
        				return;
        			}
            		if (data.success == true) {
            			alert('恭喜您，提交成功!');
		                setTimeout(function(){
		    				window.location.reload();
		    			},2000);
		                return;
            		}
        },'json')
        return false;
    });
    
    //提交验证码    
    $('.part').on('tap', '#ver-btn', function () {
        var ver_code = $("#password").val();
        var sn = $('#sn').val();
        if (ver_code == '') {
            alert("请输入密码");
            return;
        }
        	
        var submitData = {
            id: $('.tigerslot').attr('activity_id'),
            parssword: ver_code,
            rid: $('.tigerslot').attr('rid')
        };
        $.post('/index.php?g=Wap&m=Lottery&a=exchange', submitData,
        		function(data) {		            
	        		if (data.success == true) {
		                alert('恭喜，验证成功!');
		                setTimeout(function(){
		    				window.location.reload();
		    			},2000);
		            } else {
		            	alert(data.msg);
		            }
	        	}
        ,'json')    	
    });
    
    var lightCycle = new function () {
        var currIndex = 0, maxIndex = $lights.length - 1;
        $('.l0').addClass('on');
        var tmr = new GameTimer(function () {
            $lights.each(function(){
                var $this = $(this);
                if($this.hasClass('on')){
                    currIndex++;
                    if (currIndex > maxIndex) {
                        currIndex = 0;
                    }
                    $this.removeClass('on');
                    $('.l' + currIndex).addClass('on');
                    return false;
                }
            });
        }, 100);
        this.start = function () {
            tmr.start();
        };
        this.stop = function () {
            tmr.stop();
        };
    };
    var lightRandom = new function () {
        var tmr = new GameTimer(function () {
            $lights.each(function () {
                var r = Math.random() * 1000;
                if (r < 400) {
                    $(this).addClass('on');
                } else {
                    $(this).removeClass('on');
                }
            });
        }, 100);
        this.start = function () {
            tmr.start();
        };
        this.stop = function () {
            tmr.stop();
        };
    };

    var lightFlicker = new function () {
        $lights.each(function (index) {
            if ((index >> 1) == index / 2) {
                $(this).addClass('on');
            } else {
                $(this).removeClass('on');
            }
        });
        var tmr = new GameTimer(function () {
            $lights.toggleClass('on');
        }, 100);
        this.start = function () {
            tmr.start();
        };
        this.stop = function () {
            tmr.stop();
        };
    };


    var boxCycle = new function () {

        var speed_left = 0, speed_middle = 0, speed_right = 0, maxSpeed = 25;
        var running = false, toStop = false, toStopCount = 0;
        var boxPos_left = 0, boxPos_middle = 0, boxPos_right = 0;
        var toLeftIndex = 0, toMiddleIndex = 0, toRightIndex = 0;
        var resultData;
        
        var $box = $('.tigerslot .box'), $box_left = $('.tigerslot .strip.left .box'), $box_middle = $('.tigerslot .strip.middle .box'), $box_right = $('.tigerslot .strip.right .box');

        var fn_stop_callback = null;

        var tmr = new GameTimer(function () {
            if (toStop) {
                toStopCount--;
                speed_left = 0;
                boxPos_left = -itemPositions[toLeftIndex];
                if (toStopCount < 25) {
                    speed_middle = 0;
                    boxPos_middle = -itemPositions[toMiddleIndex];
                }
                if (toStopCount < 0) {
                    speed_right = 0;
                    boxPos_right = -itemPositions[toRightIndex];
                }


            } else {
                speed_left += 1;
                speed_middle += 1;
                speed_right += 1;
                if (speed_left > maxSpeed) {
                    speed_left = maxSpeed;
                }
                if (speed_middle > maxSpeed) {
                    speed_middle = maxSpeed;
                }
                if (speed_right > maxSpeed) {
                    speed_right = maxSpeed;
                }
            }

            boxPos_left += speed_left;
            boxPos_middle += speed_middle;
            boxPos_right += speed_right;

            $box_left.css('background-position', '0 ' + boxPos_left + 'px')
            $box_middle.css('background-position', '0 ' + boxPos_middle + 'px')
            $box_right.css('background-position', '0 ' + boxPos_right + 'px')

            if (speed_left == 0 && speed_middle == 0 && speed_right == 0) {
                tmr.stop(fn_stop_callback.bind(this, resultData));
            }
            
        }, 33);

        this.start = function (data) {
            toLeftIndex = data.left; toMiddleIndex = data.middle; toRightIndex = data.right;
            running = true; toStop = false;
            resultData = data;
            tmr.start();
        };

        this.stop = function (fn) {
            fn_stop_callback = fn;
            toStop = true;
            toStopCount = 50;
        };


        this.reset = function () {
            $box_left.css('background-position', '0 ' + itemPositions[0] + 'px');
            $box_middle.css('background-position', '0 ' + itemPositions[0] + 'px');
            $box_right.css('background-position', '0 ' + itemPositions[0] + 'px');
        };
        this.reset();
    };

    //顶部滚动中奖信息
	AutoScrollHeader = (function(obj){
		$(obj).find("ul:first").animate({
			marginTop:"-15px"
		},500,function(){
			$(this).css({marginTop:"0px"}).find("li:first").appendTo(this);
		});
	});
	if($('.scroll-reward-info li').length >1){
	   setInterval('AutoScroll(".scroll-reward-info")',4000);
	}
	
	//手机号码格式判断
	function istel(value) {
	    var regxEny = /^[0-9]*$/;
	    return regxEny.test(value);
	}
	

    lightFlicker.start();
    window.setTimeout(function () {
        lightFlicker.stop();
    }, 2000)

});