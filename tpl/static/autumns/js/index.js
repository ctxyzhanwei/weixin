window.onload = function() {
    var PanelManager = {
        dom: {
            layer: ".panel-box-layer",
            box: ".panel-box",
            title: ".panel-title",
            content: ".panel-content",
            btnList: ".btn-list",
            btnRule: ".btn-rule"
        },
        init: function() {
            window.CMER.DomQuery(this.dom);
            var me = this;
            this.loading = new window.CMER.Loading();
            this.needLoadMusic = (window.config_custom.MUSICBG && window.config_custom.MUSICBG != "");
            this.preloadSound();

            this.dom.layer.addEventListener("touchmove", function(e) {
                e.preventDefault();
            }, false);
            this.dom.box.addEventListener("touchmove", function(e) {
                e.preventDefault();
            }, false);
            this.dom.layer.addEventListener("click", this.hide.bind(this), false);
            this.dom.btnList.addEventListener("click", this.show.bind(this, 0), false);
            this.dom.btnRule.addEventListener("click", this.show.bind(this, 1), false);

            var contents = this.dom.content.querySelectorAll(".panel-content-child");
            this.iscroll1 = new iScroll(contents[0]);
            this.iscroll2 = new iScroll(contents[1]);

            window.addEventListener("touchstart", this.playSound.bind(PanelManager), false);
        },
        needLoadMusic: false,
        panelIndex: 0,
        preloadSound: function() {
            if (!this.needLoadMusic)
                return;

            this.sound = new Sound(window.config_custom.MUSICBG);
        },
        destroy: function() {
            // this.iscroll.destroy();
            // this.iscroll = null;
            // this.sound.removeAllSounds();
        },
        needPlay: false,
        playSound: function() {
            if (!this.needLoadMusic)
                return;

            if (!this.needPlay) {
                this.needPlay = true;
                this.sound.play(true);
            }
        },
        show: function(index) {
            var titles = this.dom.title.querySelectorAll("a");
            var contents = this.dom.content.querySelectorAll(".panel-content-child");
            var me = this;
            this.panelIndex = index;
            for (var i = 0; titles[i]; i++) {
                if (index == i) {
                    titles[i].classList.add("current");
                    contents[i].classList.add("current");
                } else {
                    titles[i].classList.remove("current");
                    contents[i].classList.remove("current");
                }
            }
            this.dom.box.classList.remove("hide");
            this.dom.box.classList.add("show");
            this.dom.layer.classList.add("show");
            if (window.innerHeight < this.dom.box.offsetHeight) {
                this.dom.box.classList.add("small");
            }

            setTimeout(function() {
                if (index == 0) {
                    me.iscroll1.refresh();
                    me.iscroll1.scrollTo(0, 0, 0);
                } else {
                    me.iscroll2.refresh();
                    me.iscroll2.scrollTo(0, 0, 0);
                }
            }, 100);
        },
        hide: function() {
            var me = this;
            this.dom.box.classList.remove("show");
            this.dom.box.classList.add("hide");
            setTimeout(function() {
                me.dom.layer.classList.remove("show");
            }, 300);
        }
    }

    var MainManager = {
        dom: {
            imgListBox: ".img-list",
            btnList: ".btn-a.list",
            btnRule: ".btn-a.rule",
            form: "[ data-role='form']",
            shareLayer: ".share-layer",
            adLayer: ".ads",
            btnCloseAd: ".btn-close-ad"
        },
        defaultData: {
            animTime: 4
        },
        imgIndex: 0,
        timeHandler: 0,
        totalImgsNum: 0,
        init: function() {
            window.CMER.DomQuery(this.dom);
            this.rewriteCSS();
            this.checkSlide();
            this.dom.btnList.addEventListener("click", PanelManager.show.bind(PanelManager, 0), false);
            this.dom.btnRule.addEventListener("click", PanelManager.show.bind(PanelManager, 1), false);
            this.dom.form.addEventListener("submit", this.checkForm.bind(this), false);
            this.dom.shareLayer.addEventListener("click", function() {
                this.classList.remove("show");
            }, false);

            if (this.dom.adLayer) {
                this.dom.btnCloseAd.addEventListener("click", function() {
                    MainManager.dom.adLayer.classList.remove("show");
                }, false);
            }
        },
        rewriteCSS: function() {
            var cssCreate = new window.CMER.CSSCreate();
            if (window.config_custom.IMG.BG)
                cssCreate.add(".container", "background-image: url(" + window.config_custom.IMG.BG + ")");
            cssCreate.produce();
        },
        checkSlide: function() {
            if (window.config_custom.OPENSLIDE) {
                this.dom.imgBoxs = this.dom.imgListBox.querySelectorAll("li");
                this.totalImgsNum = this.dom.imgBoxs.length;
                this.animImgs();
            }else{
                this.dom.imgListBox.style.display = "none";
            }
        },
        checkForm: function(e) {
            if (window.config_custom.NEEDADD) {
                e.preventDefault();
                this.dom.shareLayer.classList.add("show");
            }
        },
        animImgs: function() {
            var me = this;

            for (var i = 0, imgBox; imgBox = this.dom.imgBoxs[i]; i++) {
                if (i == this.imgIndex)
                    imgBox.classList.add("anim");
                else
                    imgBox.classList.remove("anim");
            }

            

            this.timeHandler = setTimeout(function() {
                me.imgIndex++;
                me.imgIndex = (me.imgIndex >= me.totalImgsNum) ? 0 : me.imgIndex;

                me.animImgs();
            }, this.defaultData.animTime * 1000);
        },
        destroy: function() {
            clearTimeout(this.timeHandler);
            this.timeHandler = 0;
        }
    };

    MainManager.init();
    PanelManager.init();

    window.onunload = function() {
        MainManager.destroy();
    };
};