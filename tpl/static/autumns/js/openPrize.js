window.onload = function() {
	var MainManager = {
		dom: {
			textNum: ".text-num",
			textNumRest: ".text-num .rest",
			textNumHave: ".text-num .have",
			btnOpenOther: ".btn-open-other",
			btnHelpOther: ".btn-help-other",
			btnAlertOther: ".btn-alert-other",
			shareLayer: ".share-layer"
		},
		init: function() {
			window.CMER.DomQuery(this.dom);
			// this.showNum();
			this.dom.btnOpenOther && this.dom.btnOpenOther.addEventListener("click", this.bindOpen.bind(this), false);
			this.dom.btnHelpOther && this.dom.btnHelpOther.addEventListener("click", this.checkForm.bind(this), false);
			this.dom.btnAlertOther && this.dom.btnAlertOther.addEventListener("click", this.checkForm.bind(this), false);
			this.dom.shareLayer.addEventListener("click", function() {
				this.classList.remove("show");
			}, false);
		},
		checkForm:function(){
			this.dom.shareLayer.classList.add("show");
		},
		bindOpen: function() {

		}
		// showNum: function() {
		// 	var numData = window.config_custom.OPENPRIZE;
		// 	if (numData.h === 0) {
		// 		this.dom.textNum.classList.add("first");
		// 	} else if (numData.r === 1) {
		// 		this.dom.textNum.classList.add("last");
		// 	} else {
		// 		this.dom.textNumRest.textContent = numData.r;
		// 	}
		// },
		// showNumAfterShare: function() {
		// 	var numData = window.config_custom.OPENPRIZE;
		// 	numData.r--;
		// 	numData.h++;
		// 	this.dom.textNum.classList.remove("first");
		// 	this.dom.textNum.classList.remove("last");
		// 	if (numData.r === 0) {
		// 		this.dom.textNum.classList.add("sharelast");
		// 	} else {
		// 		this.dom.textNum.classList.add("share");
		// 		this.dom.textNumRest.textContent = numData.r;
		// 		this.dom.textNumRest.textNumHave = numData.h;
		// 	}
		// }
	};
	MainManager.init();
}