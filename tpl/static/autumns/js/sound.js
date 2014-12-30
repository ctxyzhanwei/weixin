var Sound = function(srcfile, isshort) {
    this.srcfile = null;
    this.myAudio = null;
    this.isloop = false;
    this.init(srcfile, isshort);
};
Sound.prototype = {
    playState: "playSucceeded",
    init: function(srcfile, isshort) {
        this.srcfile = srcfile;
        this.myAudio = new Audio(this.srcfile);
    },
    play: function(isloop) {
        this.isloop = isloop;
        if (this.myAudio.currentTime > 0)
            this.myAudio.currentTime = 0;
        
        this.setVolume(1);
        this.myAudio.play();
        if (isloop) {
            var me = this;
            this.myAudio.addEventListener('ended', function() {
                if (me.isloop) {
                    this.currentTime = 0;
                    this.play();
                }
            }, false);
        }
    },
    addEventListener: function(type, fn) {
        this.myAudio.addEventListener(type, fn, false);
    },
    setVolume: function(num) {
        this.myAudio.volume = num;
    },
    pause: function() {
        this.myAudio.pause();
    },
    resume: function() {
        this.myAudio.play();
    },
    stop: function() {
        this.isloop = false;
        this.myAudio.pause();
    },
    release: function() {
        this.stop();
        this.myAudio = null;
    }
}