window.onload = function() {
	var MainManager = {
		dom: {
			imgBox: ".choose-content",
			imgShadow: ".choose-content-bg",
			btnChoose: ".btn-choose",
			identifier: ".choose-content-identifier"
		},
		init: function() {
			window.CMER.DomQuery(this.dom);
			var cssCreate = new window.CMER.CSSCreate();
			cssCreate.add(".choose-content li", "width:" + window.innerWidth + "px;");
			cssCreate.produce();

			this.sound = window.CMER.Browser.versions.android ? SoundManager : createjs.Sound;
			this.loading = new window.CMER.Loading();

			var me = this;
			var imgList = this.dom.imgBox.querySelector("ul");
			var width = this.dom.imgBox.clientWidth + 50;
			var imgChildren = imgList.querySelectorAll("li");
			imgList.style.width = imgList.childElementCount * width + "px";
			this.iscroll = new iScroll(this.dom.imgBox, {
				vScroll: false,
				snap: "li",
				hScrollbar: false,
				momentum: false,
				onTouchEnd: function() {
					me.changeIdentifier(me.iscroll.currPageX);
				}
			});
			this.createIdentifier();
			this.preloadSound();
			FormManager.changePrize(imgChildren[0].dataset.id);

			this.dom.imgBox.addEventListener("click", this.playAnim.bind(this), false);
			this.dom.btnChoose.addEventListener("click", FormManager.showPanel.bind(FormManager), false);
			window.addEventListener('devicemotion', this.shake.bind(MainManager), false);
		},
		preloadSound: function() {
			var manifest = [];
			var num = 0;
			var me = this;
			var totalNum = 5;

			for (var i = 1; i <= totalNum; i++) {
				manifest.push({
					src: i + ".mp3",
					id: "sound" + i
				});
			}

			this.sound.registerManifest(manifest, window.config_custom.PATH.MUSIC);
			if (window.CMER.Browser.versions.android) {
				this.loading.show();
				this.sound.addEventListener("fileload", function() {
					num++;
					me.loading.progress("正在加载资源：" + num * 100 / totalNum + "%");
					if (num == totalNum)
						me.loading.hide();
				}); // call handleLoad when each sound loads
			}

		},
		currentSound: null,
		playSound: function() {
			if (!this.currentSound || this.currentSound.playState == "playFailed" || (this.currentSound.myAudio && this.currentSound.myAudio.ended)) {
				var childrenLi = this.dom.imgBox.querySelectorAll("li");
				var li = childrenLi[this.iscroll.currPageX];
				// this.sound.setVolume(1);
				this.currentSound = this.sound.play("sound" + li.dataset.music);
				this.currentSound.addEventListener("complete", createjs.proxy(this.completeSound, this));
				// this.currentSound.addEventListener("ended", this.completeSound.bind(this));
			}
		},
		playAnim: function() {
			this.playSound();
			this.imgAnim();
			this.imgShadowAnim();
		},
		completeSound: function() {
			console.log("completeSound");
			this.currentSound = null;
		},
		shake: (function() {
			var SHAKE_THRESHOLD = 800;
			var lastUpdate = 0;
			var x, y, z, last_x, last_y, last_z;
			return function(eventData) {
				var acceleration = eventData.accelerationIncludingGravity;
				var curTime = Date.now();
				if ((curTime - lastUpdate) > 100) {
					var diffTime = curTime - lastUpdate;
					lastUpdate = curTime;
					x = acceleration.x;
					y = acceleration.y;
					z = acceleration.z;
					var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
					if (speed > SHAKE_THRESHOLD) {
						MainManager.playAnim();
					}
					last_x = x;
					last_y = y;
					last_z = z;
				}
			}
		})(),
		createIdentifier: function() {
			var imgList = this.dom.imgBox.querySelector("ul");
			var childrenNum = imgList.childElementCount;
			var fragment = document.createDocumentFragment();
			for (var i = 0; i < childrenNum; i++) {
				var li = document.createElement("li");
				li.className = (i === 0 ? "current" : "");
				fragment.appendChild(li);
			}
			this.dom.identifier.appendChild(fragment);
		},
		imgAnim: function() {
			var me = this;
			var childrenLi = this.dom.imgBox.querySelectorAll("li");
			var imgObj = childrenLi[this.iscroll.currPageX];

			imgObj.classList.add("anim");
			imgObj.addEventListener("webkitAnimationEnd", function() {
				this.removeEventListener("webkitAnimationEnd", me.imgAnim);
				this.classList.remove("anim");
			}, false);
		},
		imgShadowAnim: function() {
			var me = this;

			this.dom.imgShadow.classList.add("anim");
			this.dom.imgShadow.addEventListener("webkitAnimationEnd", function() {
				this.removeEventListener("webkitAnimationEnd", me.imgShadowAnim);
				this.classList.remove("anim");
			}, false);
		},
		changeIdentifier: function(index) {
			var children = this.dom.identifier.querySelectorAll("li");
			var childrenLi = this.dom.imgBox.querySelectorAll("li");
			for (var i = 0; children[i]; i++) {
				children[i].className = (i == index ? "current" : "");
			}
			this.imgShadowAnim();
			FormManager.changePrize(childrenLi[index].dataset.id);
		},
		destroy: function() {
			window.removeEventListener('devicemotion', this.shake);
			this.sound.removeAllEventListeners();
			this.sound.removeAllSounds();
			this.sound = null;
			this.iscroll.destroy();
			this.iscroll = null;
		}
	}

	var CDManager = function(totalCD) {
		var _totalCD = totalCD;
		var _startTime = 0;
		var _currentCD = totalCD;
		var _timeHandler = 0;
		var _progressCallback = null;
		var _endCallback = null;
		var main = {
			start: function() {
				_startTime = Date.now();
				_currentCD = _totalCD;
				_timeHandler = setInterval(tick, 1000);
				return this;
			},
			progress: function(progressCallback) {
				_progressCallback = progressCallback;
				return this;
			},
			end: function(endCallback) {
				_endCallback = endCallback;
				return this;
			},
			destory: function() {
				destory();
			}
		}

		function destory() {
			clearInterval(_timeHandler);
			_timeHandler = 0;
			_currentCD = 0;
		}

		function end() {
			destory();
			(typeof _endCallback === "function") && _endCallback();
		}

		function tick() {
			_currentCD -= (Date.now() - _startTime);
			_startTime = Date.now();
			(typeof _progressCallback === "function") && _progressCallback(Math.round(_currentCD / 1000) * 1000);
			if (_currentCD <= 0)
				end();
		}

		return main;
	}

	var FormManager = {
		dom: {
			panel: ".panel-register",
			form: "[data-role='info-form']",
			btnPass: ".btn-pass",
			btnClose: ".btn-close",
			inputName: "[name='info-name']",
			inputTel: "[name='info-tel']",
			inputPass: "[name='info-pass']",
			inputPrize: "[name='info-prize']",
			container: ".container",
			tip: ".submit-tip"
		},
		canSubmit: true,
		init: function() {
			window.CMER.DomQuery(this.dom);

			var me = this;
			this.iscroll = new iScroll(this.dom.form);
			this.loading = new window.CMER.Loading();

			this.dom.panel.style.height = this.dom.container.offsetHeight + "px";

			if (this.dom.btnPass) {
				this.dom.btnPass.canClick = true;
				this.cdTimer = new CDManager(60000);
				this.cdTimer.progress(function(time) {
					me.dom.btnPass.canClick = false;
					me.dom.btnPass.textContent = parseInt(time / 1000) + "秒";
				}).end(function() {
					me.dom.btnPass.canClick = true;
					me.dom.btnPass.textContent = "重新获取";
				});
				this.dom.btnPass.addEventListener("click", this.getPass.bind(this), false);
			}

			this.dom.btnClose.addEventListener("click", this.closePanel.bind(this), false);
			this.dom.form.addEventListener("submit", this.checkForm.bind(this), false);

			[this.dom.inputName, this.dom.inputTel, this.dom.inputPass].forEach(function(dom) {
				dom && dom.addEventListener("click", this.hideTip.bind(this), false);
			}.bind(this));
		},
		showPanel: function() {
			(this.dom.btnPass) && (this.dom.btnPass.textContent = "获取验证码");
			if (window.config_custom.NEEDREGISTER) {
				this.dom.panel.classList.add("show");
				this.iscroll.refresh();
			} else {
				this.checkForm();
			}

			if (!this.cssNew) {
				this.cssNew = new window.CMER.CSSCreate();
				var panelWidth = this.dom.form.offsetWidth;
				var labels = this.dom.form.querySelectorAll("label");
				var maxWidth = 0;
				for (var i = 0; labels[i]; i++) {
					if (maxWidth < labels[i].scrollWidth)
						maxWidth = labels[i].scrollWidth;
				}
				this.cssNew.add(".panel-register label", "width:" + maxWidth + "px;");
				this.cssNew.add(".panel-register input[type='text'], .panel-register input[type='tel']", "margin-left:6px;width:" + (panelWidth - maxWidth - 6) + "px;");
				this.cssNew.produce();
				this.iscroll.refresh();
			}
		},
		changePrize: function(num) {
			this.dom.inputPrize.value = num;
		},
		getPass: function() {
			if (!this.dom.btnPass.canClick)
				return;

			var me = this;
			var tel = this.dom.inputTel.value;
			if ($.trim(tel) == "") {
				me.showTip("请填写您的手机号");
				return;
			}

			this.dom.btnPass.canClick = false;
			this.loading.show();
			$.post(window.config_custom.AJAX.PASS, {
				pass: tel
			}, function(response) {
				var data = JSON.parse(response);
				if (data.errno === window.CMER.AJAX.SUCCESS) {
					me.cdTimer.start();
				} else {
					me.showTip(data.error);
				}
				me.loading.hide();
			});
		},
		showTip: function(msg) {
			this.dom.tip.innerText = msg;
		},
		hideTip: function() {
			this.dom.tip.innerText = "";
		},
		checkForm: function(e) {
			var me = this;
			e && e.preventDefault();
			if (!this.canSubmit)
				return;
			
			this.canSubmit = false;
			this.loading.hide();
			$.post(this.dom.form.action, $(this.dom.form).serialize(), function(response) {
				var data = JSON.parse(response);
				if (data.errno === window.CMER.AJAX.SUCCESS)
					window.location.href = data.path;
				else
					me.showTip(data.error);

				me.loading.hide();
			});
		},
		closePanel: function() {
			if (this.cdTimer)
				this.cdTimer.destory();

			this.dom.panel.classList.remove("show");
		},
		destory: function() {
			if (this.cdTimer)
				this.cdTimer.destory();
		}
	}

	FormManager.init();
	MainManager.init();

	window.onunload = function() {
		MainManager.destroy();
		FormManager.destory();
	}
}