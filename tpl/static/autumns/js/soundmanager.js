var SoundManager = (function() {
	var soundCache = {};
	var soundCurrent = null;

	var main = {
		removeAllSounds: function() {
			for (var i in soundCache) {
				if (soundCache[i]) {
					soundCache[i].release();
				}
			}
		},
		removeAllEventListeners: function() {

		},
		registerSound: function(data) {
			soundCache[data.id] = new Sound(data.src);
		},
		registerManifest: function(data, path) {
			for (var i = 0; data[i]; i++) {
				data[i].src = (path || "") + data[i].src;
				this.registerSound(data[i]);
			}
		},
		addEventListener: function(type, fn) {
			switch (type) {
				case "fileload":
					type = "canplaythrough";
					break;
			}
			for (var i in soundCache) {
				if (soundCache[i]) {
					soundCache[i].addEventListener(type, fn);
				}
			}
		},
		play: function(id, option) {
			option = option || {};
			var loopTimes = option.loop || 0;
			var loop = loopTimes < 0 ? true : false;
			var canplaythrough = option.canplaythrough;
			if (typeof canplaythrough === "function") {
				soundCache[id].addEventListener("canplaythrough", function() {
					soundCache[id].play(loop);
				});
			} else {
				if (soundCurrent && soundCurrent != soundCache[id]) {
					soundCache[id].stop();
				}
				soundCache[id].play(loop);
			}
			soundCurrent = soundCache[id];
			return soundCache[id];
		}
	};
	return main;
})();