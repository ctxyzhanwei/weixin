window.CMER = {
	AJAX: {
		SUCCESS: 0,
		FAIL: 1,
	},
	Browser: {
		versions: function() {
			var u = navigator.userAgent;
			return { //移动终端浏览器版本信息
				mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
				ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
				android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或uc浏览器
			};
		}(),
	},
	DomQuery: function(objs) {
		for (var i in objs) {
			if (objs[i]) {
				objs[i] = document.querySelector(objs[i]);
			}
		}
	},
	Loading: function(_p) {
		var loadingBox = null;
		var main = {
			show: function() {
				loadingBox.classList.add("show");
			},
			hide: function() {
				loadingBox.classList.remove("show");
			},
			progress: function(progress) {
				loadingBox.innerHTML = progress;
			}
		}

		function create(_p) {
			loadingBox = document.createElement("div");
			loadingBox.className = "loading-box";
			loadingBox.style.lineHeight = (window.innerHeight + 60) + 'px';
			_p = _p || document.body;
			_p.appendChild(loadingBox);

			loadingBox.addEventListener("touchmove",function(e){
				e.preventDefault();
			},false);
		}
		create(_p);
		return main;
	},
	CSSCreate: function() {
		var style;

		function create() {
			style = document.createElement("style");
			style.type = "text/css";
		}

		var main = {
			produce: function() {
				document.body.appendChild(style);
			},
			add: function(name, content) {
				style.innerHTML += name + "{" + content + "}";
			}
		}
		create();
		return main;
	},
	EventTouch: function(dom, option) {
		var touchStartCallback = null;
		var touchMoveCallback = null;
		var touchEndCallback = null;
		var touchMovePX = 20;
		var checkY = true;

		var touchOperation = {
			startTouch: false,
			startPos: {
				x: 0,
				y: 0
			},
			currentPos: {
				x: 0,
				y: 0
			},

			startTouch: function(e) {
				var touch = e.touches ? e.touches[0] : e;
				this.startPos.y = touch.pageY;
				this.startPos.x = touch.pageX;

				this.startTouch = true;

				(typeof touchStartCallback === "function") && touchStartCallback();
			},

			moveTouch: function(e) {
				e.preventDefault();

				if (!this.startTouch)
					return;

				var touch = e.touches ? e.touches[0] : e;
				this.currentPos.y = touch.pageY;
				this.currentPos.x = touch.pageX;

				(typeof touchMoveCallback === "function") && touchMoveCallback();
			},

			endTouch: function(e) {
				this.startTouch = false;

				if (checkY && this.currentPos.y - this.startPos.y >= touchMovePX)
					(typeof touchEndCallback === "function") && touchEndCallback();
				else if (Math.abs(this.currentPos.x - this.startPos.x) >= touchMovePX) {
					var direction = this.currentPos.x - this.startPos.x > 0 ? 1 : -1;
					(typeof touchEndCallback === "function") && touchEndCallback(direction);
				}
			}
		}
		if ("checkX" in option)
			checkY = false;
		if ("onStart" in option)
			touchStartCallback = option["onStart"];
		if ("onMove" in option)
			touchMoveCallback = option["onMove"];
		if ("onEnd" in option)
			touchEndCallback = option["onEnd"];

		var isMobile = "ontouchstart" in document.documentElement ? true : false;

		dom.addEventListener(isMobile ? "touchstart" : "mousedown", touchOperation.startTouch.bind(touchOperation), false);
		dom.addEventListener(isMobile ? "touchmove" : "mousemove", touchOperation.moveTouch.bind(touchOperation), false);
		dom.addEventListener(isMobile ? "touchend" : "mouseup", touchOperation.endTouch.bind(touchOperation), false);
	}
}