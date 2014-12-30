window.onload = function() {

	var MainManager = {
		dom: {
			transformBody: ".body",
			imgBox: ".choose-content",
			row: ".layer-row",
			inputPrize: "[name='info-prize']",
			inputPrize2: "[name='info-prize2']",
			textNum: ".text-num",
			textNumHave: ".text-num-have",
			textNumRest: ".text-num-rest",
			identifier: ".choose-content-identifier",
			progressCurrent: ".progress-num-current",
			progress: ".progress-num",
			btnOpen: ".btn-open",
			btnHelp: ".btn-help",
			btnAgain2: ".btn-again2",
			btnAgain: ".btn-again",
			btnSee: ".btn-see",
			shareLayer: ".share-layer"
		},
		rewriteCSS: function() {
			var cssCreate = new window.CMER.CSSCreate();
			cssCreate.add(".choose-content li", "width:" + window.innerWidth + "px;");
			if (window.config_custom.IMG.BG)
				cssCreate.add(".container", "background-image: url(" + window.config_custom.IMG.BG + ")");
			cssCreate.produce();
		},
		init: function() {
			window.CMER.DomQuery(this.dom);
			this.rewriteCSS();

			var me = this;
			var lis = this.dom.imgBox.querySelectorAll("li");
			this.imgIndexMax = lis.length - 1;
			this.maxHeight = this.dom.row.clientHeight - 50;
			this.loading = new window.CMER.Loading();
			this.weiboUrl = window.shareData && (window.shareData.weiboLink || window.shareData.timeLineLink || window.shareData.sendFriendLink);
			this.createIdentifier();
			this.changeBodyContent();

			if (window.config_custom.SLIDEVERTICAL) {
				new window.CMER.EventTouch(this.dom.imgBox, {
					checkX: true,
					onEnd: this.bindTouchEnd.bind(this)
				});
			} else {
				this.dom.imgBox.classList.add("horizontal");
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
						me.imgIndex = me.iscroll.currPageX;
						me.changeIdentifier(me.iscroll.currPageX);
						me.changeBodyContent();
					}
				});
			}

			this.canMove = lis.length >= 2 ? true : false;

			var data = window.config_custom.prize[this.imgIndex];
			if (!window.config_custom.ISPLATFORM)
				this.dom.row.style.height = (this.maxHeight * data.r / (data.h + data.r) + 50) + "px";
			this.dom.btnHelp.addEventListener("click", this.checkForm.bind(this), false);
			this.dom.shareLayer.addEventListener("click", function() {
				this.classList.remove("show");
			}, false);
		},
		imgIndex: 0,
		imgIndexMax: 0,
		canMove: false,
		maxHeight: 0,
		changeWeiboUrl: function(id) {
			window.shareData = window.shareData || {};
			window.shareData.weiboLink = window.shareData.timeLineLink = window.shareData.sendFriendLink = (this.weiboUrl + "&bid=" + id);
		},
		checkForm: function(e) {
			this.dom.shareLayer.classList.add("show");
		},
		bindTouchEnd: function(direction) {
			if (!this.canMove)
				return;

			var me = this;
			if (direction < 0) {
				me.imgIndex++;
				me.imgIndex = (me.imgIndex > me.imgIndexMax) ? 0 : me.imgIndex;
			} else {
				me.imgIndex--;
				me.imgIndex = (me.imgIndex < 0) ? me.imgIndexMax : me.imgIndex;
			}

			this.bodyAnim();
			setTimeout(function() {
				me.changeIdentifier(me.imgIndex);
				me.changeBodyContent();
			}, 250);
		},
		createIdentifier: function() {
			var imgList = this.dom.imgBox.querySelector("ul");
			var childrenNum = imgList.childElementCount;
			if (childrenNum <= 1)
				return;

			var fragment = document.createDocumentFragment();
			for (var i = 0; i < childrenNum; i++) {
				var li = document.createElement("li");
				li.className = (i === 0 ? "current" : "");
				fragment.appendChild(li);
			}
			this.dom.identifier.appendChild(fragment);
		},
		changeBodyContent: function() {
			var data = window.config_custom.prize[this.imgIndex];
			var childrenLi = this.dom.imgBox.querySelectorAll("li");

			this.dom.textNumHave.textContent = data.h;
			this.dom.textNumRest.textContent = data.r;
			this.dom.progressCurrent.style.width = data.h * 100 / (data.h + data.r) + "%";

			this.dom.btnAgain.classList.remove("hide");
			if (data.rc == true) { //被领完了
				this.dom.progress.classList.add("hide");
				this.dom.btnOpen.classList.remove("show");
				this.dom.btnHelp.classList.remove("show");
				this.dom.btnAgain2.classList.add("hide");
				this.dom.btnSee.classList.remove("show");
				this.dom.textNum.classList.add("rechoose");
				if (window.config_custom.ABOVEMAX)
					this.dom.btnAgain.classList.add("hide");

			} else if (data.r == 0) { //可以打开礼盒了
				this.dom.progress.classList.add("hide");
				this.dom.textNum.classList.add("full");
				this.dom.btnOpen.classList.add("show");
				this.dom.btnHelp.classList.remove("show");
				this.dom.btnAgain2.classList.add("hide");
				this.dom.btnAgain.classList.add("hide");
				this.dom.textNum.classList.remove("rechoose");
				if (data.i == true) { //i:true=>打开过 false=>未打开过
					this.dom.btnSee.classList.add("show");
					this.dom.btnOpen.classList.remove("show");
				} else {
					this.dom.btnSee.classList.remove("show");
					this.dom.btnOpen.classList.add("show");
				}
			} else {
				this.dom.progress.classList.remove("hide");
				this.dom.textNum.classList.remove("full");
				this.dom.btnOpen.classList.remove("show");
				this.dom.btnHelp.classList.add("show");
				this.dom.btnAgain.classList.add("hide");
				this.dom.btnSee.classList.remove("show");
				this.dom.textNum.classList.remove("rechoose");
				if (window.config_custom.ABOVEMAX)
					this.dom.btnAgain2.classList.add("hide");
				else
					this.dom.btnAgain2.classList.remove("hide");
			}
			var imgBox = childrenLi[this.imgIndex];
			for (var i = 0; childrenLi[i]; i++) {
				if (i == this.imgIndex)
					childrenLi[i].classList.add("show");
				else
					childrenLi[i].classList.remove("show");
			}
			this.dom.inputPrize.value = imgBox.dataset.id;
			this.dom.inputPrize2.value = imgBox.dataset.id;
			this.changeWeiboUrl(imgBox.dataset.id);
		},
		changeIdentifier: function(index) {
			var children = this.dom.identifier.querySelectorAll("li");
			var childrenLi = this.dom.imgBox.querySelectorAll("li");
			for (var i = 0; children[i]; i++) {
				children[i].className = (i == index ? "current" : "");
			}
		},
		bodyAnim: function() {
			var me = this;
			this.canMove = false;
			this.dom.row.style["height"] = "800px";
			setTimeout(function() {
				me.bodyAnimUnbind();
			}, 500);
			console.log("bodyAnim");
		},
		bodyAnimUnbind: function(e) {
			var data = window.config_custom.prize[this.imgIndex];
			this.canMove = true;
			if (window.config_custom.ISPLATFORM) {
				this.dom.row.style.height = (this.maxHeight + 50) + "px";
			} else {
				this.dom.row.style.height = (this.maxHeight * data.r / (data.h + data.r) + 50) + "px";
			}
		}
	}

	MainManager.init();
}