var $PlayeSeed, lineHeight;
var size;
var yuni;
var rankTopTen = [];
var tmr_cutdown_start;
var tmr_slogan;
	/*查询参与人数*/
	 function getConnectNum(){
	 	$.ajax({
	 		type: "post",
	 		url : "/index.php?g=User&m=Scene&a=getConnectNum",
	 		dataType:'text',
	 		data: {'id':id,'sceneid':sceneid},
	 		success: function(data){
	 			$("#connectcount").html(data);
	 		}

	 	});
	 }


	/*点击游戏开始*/
	$(".button-start").click(function(){
		var html = '';
		for (var i = 1; i <= diff; i++) {
			html+='<div class="trackline leftfadein"><div class="track-start" >'+i+'</div><div class="track-end" ></div></div>'
		};
		$(".Panel.Track .tracklist").append(html);

		$('.round-welcome').css('display','none');
		$('.Track').css('display','block');
		clearInterval(yuni);
			cutdown_start();
			resize();
	});


/*倒计时并开启游戏*/

function cutdown_start() {
	var a = $(".cutdown-start"),
	b = (a.html())* 1 + 1;
	a.html("").show().css({
		"margin-left":-a.width() / 2 + "px",
		"margin-top": -a.height() / 2 + "px",
		"font-size":   a.height() * 0.7 + "px",
		"line-height": a.height() + "px"
	}).addClass("cutdownan-imation");
	
	tmr_cutdown_start = window.setInterval(function() {
		b--;
		if (b == 0){
			$.getJSON("/index.php?g=User&m=Scene&a=startShake", {
				id: id		
			},function(c) {
				if (c.err == 0) {
					a.html("GO!");
					gameRun();
					hideSlogan();
				} else {
					zAlert.Alert({
						closed: false,
						content: c.info,
						callback: function() {
							zAlert.Close();
							window.location.reload()
						}
					})
				}

			});

		} else {
			if (b < 0) {
				window.clearInterval(tmr_cutdown_start);
				a.hide();
				//gameTimeRun();
				showSlogan()
			} else {
				//audio_CutdownPlayer.play();
				a.html(b)
			}
		}
	},
	1000)
};  	




function resize() {
	var b = $(".Panel.Track"),
	a = b.find(".tracklist").children();
	size = lineHeight = b.height() / diff;
	var c = b.find(".runlist");
	roundLength = $(".Panel.Track .tracklist").width() - size;
	a.each(function() {
		$(this).css({
			height: size,
			"line-height": size + "px",
			"font-size": size * 3 / 5 + "px"
		}).find(".track-start,.track-end,player,.head").css({
			width: size + "px",
			height: size + "px",
			lineHeight:size + "px"
		})

		$(this).find(".nickname").css({
			height: size + "px",
			lineHeight:size + "px"
		});


		$PlayeSeed = $('<div class="player"><div class="head"></div><div class="nickname"></div></div>').css({
			width:  size - diff * 2,
			height: size - diff * 2
		})

	});
	c.css({width:b.find(".tracklist").width()-size*2,margin:'0 '+size+'px'});
};



/*获取用户数据*/
function gameRun(){
	$.getJSON("/index.php?g=User&m=Scene&a=shakeRun", {
		id: id,time:new Date().getTime(),sceneid:sceneid
	},
	function(d) {
		if (d) {
			if (d.status == 0) {
				tmr_GameDataLoad = window.setTimeout(gameRun,showtime);
				if(d.res){
					rankTopTen = d.res.slice(0, 2);
					var temp 	= '';
					for (var i = d.res.length - 1; i >= 0; i--) {
						temp += '<div class="player" uid="'+d.res[i].id+'" style="top: '+size*i+'px;left:'+d.res[i].mLeft+';"><div class="head shake" style="height:'+size+'px;width:'+size+'px;background-image: url('+d.res[i].portrait+');"></div><div class="nickname" style="line-height:'+size+'px;height:'+size+'px;">'+d.res[i].nickname+'</div></div>';
					};
				}
				$(".Panel.Track .runlist").html(temp);
			} else {
				if(d.res){
					window.setTimeout(function() {
						showGameResult(d.res);
						hideSlogan()
					},
					660)
				}else{	
					showGameResult(d.res);
					hideSlogan()
					zAlert.Alert({
						closed: false,
						content: '没有人参与摇一摇活动',
						callback: function() {
							zAlert.Close();
						}
					})
				}
			}
		} else {
			zAlert.Alert({
				content: "无法获得游戏数据，与游戏服务器断开，请刷新重试！",
				callback: function() {
					zAlert.Close()
				}
			})
		}
	})
}


function showGameResult(res) {
	var b = $(".result-layer").show();
	var d = $(".result-label", b).show().addClass("pulse");
	var a = $(".result-cup", b).hide();
	var c = starttime;
	/*
	if (audio_Gameover) {
		audio_Gameover.play()
	}*/
	window.setTimeout(function() {
		d.fadeOut(function() {
			a.show(function() {
				if (c >= 1 && res[0]) {
					window.setTimeout(function() {
						var e = $PlayeSeed.clone().addClass("result").css({
							left: "50%",
							"margin-left": "-65px",
							width: "160px",
							height: "160px",
							bottom: "150px"
						});
						e.find(".head").css({
							"background-image": "url(" + res[0]["portrait"] + ")"
						}).addClass("shake");
						e.find(".nickname").html(res[0]["nickname"]);
						e.appendTo(a).addClass("bounce")
					},
					800)
				}
				if (c >= 2 && res[1]) {
					window.setTimeout(function() {
						var e = $PlayeSeed.clone().addClass("result").css({
							left: "40px",
							width: "100px",
							height: "100px",
							bottom: "120px"
						});
						e.find(".head").css({
							"background-image": "url(" + res[1]["portrait"] + ")"
						}).addClass("shake");
						e.find(".nickname").html(res[1]["nickname"]);
						e.appendTo(a).addClass("bounce")
					},
					1800)
				}
				if (c >= 3 && res[2]) {
					window.setTimeout(function() {
						var e = $PlayeSeed.clone().addClass("result").css({
							right: "30px",
							width: "70px",
							height: "70px",
							bottom: "100px"
						});
						e.find(".head").css({
							"background-image": "url(" + res[2]["portrait"] + ")"
						}).addClass("shake");
						e.find(".nickname").html(res[2]["nickname"]);
						e.appendTo(a).addClass("bounce")
					},
					2800)
				}
			})
		}).removeClass("pulse")
	},
	1000)
}
/*开始游戏后修改窗口*/
function showSlogan() {
	$(".Panel.Top").css({
		top: "-" + $(".Panel.Top").height() + "px"
	});
	$(".Panel.Bottom").css({
		bottom: "-" + $(".Panel.Bottom").height() + "px"
	});
	var c = SLOGANS;
	var a = c.length;
	var b = $(".Panel.SloganList").css({
		top: "-15%"
	}).show();
	b.css({
		top: 0,
		"line-height": b.height() + "px"
	});
	tmr_slogan = window.setInterval(function() {
		b.html(c[Math.floor(Math.random() * a)])
	},
	1000)
}

/*游戏结束还原窗口*/
function hideSlogan() {
	window.clearInterval(tmr_slogan);
	$(".Panel.SloganList").hide();
	$(".Panel.Top").css({
		top: 0
	});
	$(".Panel.Bottom").css({
		bottom: 0
	})
};



	yuni=setInterval(getConnectNum,1500);

	$(window).resize(resize);

	$('.reset').click(function(){
		window.location.reload()
	});