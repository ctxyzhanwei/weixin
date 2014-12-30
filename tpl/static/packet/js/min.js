var A_MONTH = 2592000000;
Function.prototype.method = function(b, a) {
	if (!this.prototype[b]) {
		this.prototype[b] = a;
	} else {
	}
	return this;
};
String.method("trim", function() {
	return this.replace(/^\s+|\s$/g, "");
});
var diff_platform = function(b) {
	function a(c) {
		if (typeof c === "function") {
			c();
		}
	}
	if ((navigator.userAgent.match(/(Android)/i))) {
		a(b.android);
	} else {
		if ((navigator.userAgent.match(/(iPhone|iPod|ios|iPad)/i))) {
			a(b.ios);
		} else {
			if ((navigator.userAgent.match(/(Windows phone)/i))) {
				a(b.wp);
			} else {
				a(b.others);
			}
		}
	}
};
var asyncLoadJS = function(a, c) {
	var b = document.createElement("script");
	b.type = "text/javascript";
	b.src = a;
	document.getElementsByTagName("head")[0].appendChild(b);
	b.onload = b.onreadystatechange = function() {
		if (!this.readyState || this.readyState == "loaded"
				|| this.readyState == "complete") {
			if (typeof c === "function") {
				c();
			}
		}
	};
};
var touch = function(b, c, a) {
	if (!a) {
		a = false;
	}
	if (b && c) {
		b.addEventListener("touchstart", function(d) {
			d.target.focus();
			d.stopPropagation();
		}, a);
		b.addEventListener("touchmove", function(d) {
			d.target.setAttribute("moved", "true");
		}, a);
		b.addEventListener("touchend", function(d) {
			d.target.blur();
			if (d.target.getAttribute("moved") !== "true") {
				c(d);
			} else {
				d.target.setAttribute("moved", "false");
			}
		}, a);
	}
};
var getElesByKlsName = function(f, e) {
	f = f ? f : document.body;
	if (f.getElementsByClassName) {
		return f.getElementsByClassName(e);
	} else {
		var d = [];
		var b = f.getElementsByTagName("*");
		for (var c = 0, a = b.length; c < a; c++) {
			if (b[c].getAttribute) {
				if (b[c].getAttribute("className").indexOf(e) !== -1) {
					d.push(b[c]);
				} else {
				}
			} else {
			}
		}
		return d;
	}
};
var getQuerySting = function() {
	var h = (location.search.length) ? location.search.substring(1) : "";
	var d = {};
	var b = h.split("&");
	var f = null, c = null, g = null;
	if (b) {
		for (var e = 0, a = b.length; e < a; e++) {
			f = b[e].split("=");
			c = decodeURIComponent(f[0]);
			g = decodeURIComponent(f[1]);
			d[c] = g;
		}
	} else {
	}
	return d;
};
var txtToJson = function(txt) {
	var j = {};
	if (txt) {
		try {
			j = JSON.parse(txt);
		} catch (e) {
			try {
				j = eval("(" + txt + ")");
			} catch (ee) {
			}
		}
	} else {
	}
	return j;
};
var ajax = function(f) {
	var e = {
		createXhr : function() {
			var j;
			if (window.XMLHttpRequest) {
				j = new XMLHttpRequest();
			} else {
				try {
					j = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (i) {
					try {
						j = new ActiveXObject("Msxml2.XMLHTTP");
					} catch (h) {
					}
				}
			}
			return j;
		},
		obj2Body : function(j) {
			var h = "";
			if (j) {
				for ( var i in j) {
					if (j.hasOwnProperty(i)) {
						h += "&" + i + "=" + j[i] + "";
					} else {
					}
				}
			} else {
			}
			return h.replace(/^\&/, "");
		},
		abortReq : function(h) {
			if (h) {
				h.abort();
			}
		}
	};
	var g = e.createXhr();
	var d = null;
	if (g) {
		g.open(f.method, f.url, true);
		g.onreadystatechange = function() {
			if (g.readyState === 4) {
				if (d) {
					clearTimeout(d);
				}
				if (g.status === 200) {
					f.succFunc(g.responseText);
				} else {
					f.failFunc(g.responseText);
				}
				if (f.dialogFlag) {
					var i = document.getElementById("d_wall"), h = document
							.getElementById("d_wrap");
					if (i) {
						i.style.display = "none";
					}
					if (h) {
						h.style.display = "none";
					}
				}
			} else {
				if (g.readyState === 3) {
				} else {
				}
			}
		};
		if (f.method.toUpperCase() === "GET") {
			g.send(null);
		} else {
			if (f.method.toUpperCase() === "POST") {
				var b = f.data ? e.obj2Body(f.data) : "";
				g.setRequestHeader("Content-type",
						"application/x-www-form-urlencoded");
				g.send(b);
			} else {
			}
		}
		if (f.timeout) {
			var c = f.timeout.millisecond || 30000, a = f.timeout.callback
					|| function() {
					};
			d = setTimeout(function() {
				e.abortReq(g);
				a();
			}, c);
		}
	} else {
	}
};
var collectLog = function(b, a, c) {
	ajax({
		method : "GET",
		url : "/api/v2/weixinapi/collect_log?openid=" + b + "&phone=" + a + c
	});
};
var setCookie = function(a, b, c) {
	var d = new Date();
	if (!c) {
		d.setTime(d.getTime() + A_MONTH);
	} else {
		d.setTime(d.getTime() + c);
	}
	document.cookie = a + "=" + escape(b) + ";expires=" + d.toGMTString();
};
var getCookie = function(b) {
	var a, c = new RegExp("(^| )" + b + "=([^;]*)(;|$)");
	a = document.cookie.match(c);
	if (a) {
		return unescape(a[2]);
	} else {
		return null;
	}
};
var delCookie = function(a) {
	var b = getCookie(a);
	if (b) {
		document.cookie = a + "=" + b + ";expires=" + new Date(0).toGMTString();
	} else {
	}
};
var clearCookies = function() {
	var b = document.cookie.match(/[^ =;]+(?=\=)/g);
	if (b) {
		for (var a = b.length; a--;) {
			document.cookie = b[a] + "=0;expires=" + new Date(0).toGMTString();
		}
	}
};
(function(h, d) {
	var p = h.didi || {};
	if (p.dialog) {
		return p.dialog;
	} else {
		h.didi = p;
	}
	var l = h.document, q = l.documentElement, i = l.body, j = i
			.getElementsByTagName("script")[0];
	var b = i.scrollHeight, e = i.scrollWidth;
	var m = q.scrollTop, s = q.scrollLeft;
	var g = q.clientHeight, f = q.clientWidth;
	var n = null, c = null, a = null;
	var o = function(t) {
		if (!(this instanceof o)) {
			a = new o(t);
			return a;
		} else {
			new o.fn.init(t);
		}
	};
	var r = function(u) {
		var t = false;
		if (typeof Array.isArray) {
			t = Array.isArray(u);
		} else {
			t = (Object.prototype.toString.call(u) === "[object Array]");
		}
		return t;
	};
	var k = function(t) {
		if (j) {
			i.insertBefore(t, j);
		} else {
			i.appendChild(t);
		}
	};
	o.fn = o.prototype = {
		constructor : o,
		init : function(J) {
			if (!J) {
				return;
			}
			var L = l.createElement("div");
			L.id = "d_wall";
			L.style.backgroundColor = J.bgcolor || "black";
			L.style.opacity = J.opacity || "0.2";
			L.style.filter = J.opacity ? "alpha(opacity=" + (J.opacity * 100)
					+ ")" : "alpha(opacity=20)";
			L.className = "didi-dialog-wall";
			var u = l.createElement("div");
			u.id = "d_wrap";
			u.style.width = J.width || "260px";
			u.style.height = J.height || "210px";
			u.style.backgroundColor = J.d_bgcolor || "#e8e7e6";
			u.style.opacity = J.d_opacity ? J.d_opacity : "";
			u.style.filter = J.d_opacity ? "alpha(opacity="
					+ (J.d_opacity * 100) + ")" : "alpha(opacity=20)";
			if (J.type === "loading") {
				u.style.padding = "0px";
			}
			u.className = "didi-dialog-wrap";
			var E = "";
			if (J.bar !== false) {
				E += "<p class='didi-dialog-bar'></p>";
			}
			E += "<div style='"
					+ (J.type === "loading" ? "padding:0px;"
							: "padding: 0px 20px;") + "'>";
			if (J.dom && J.dom.nodeType === 1) {
				E += J.dom.outerHTML;
			} else {
				if (J.html && typeof J.html === "string") {
					E += J.html;
				} else {
					if (J.domId && J.domId.length) {
						var U = l.getElementById(J.domId);
						if (U) {
							E += U.outerHTML;
						}
					} else {
					}
				}
			}
			if (J.icon) {
				var x = J.icon.url, D = J.icon.width || "61px", T = J.icon.height
						|| "61px";
				if (x) {
					var G = "";
					if (J.type === "loading") {
						G = "margin:36px 0px 10px 0";
					} else {
						G = "margin:20px 0px 12px 0";
					}
					E += '<p class="didi_dialog_icon" style="' + G
							+ '"><span style="display: inline-block; width:'
							+ D + ";height:" + T + "; background:url(" + x
							+ ") no-repeat; background-size:" + D + " " + T
							+ ';"></span></p>';
				}
			}
			if (J.title && r(J.title)) {
				for (var M = 0, S = J.title.length; M < S; M++) {
					var W = J.title[M];
					if (W) {
						var P = W.color || "#ff8a01", K = W.fontSize || "1.4em";
						E += '<p class="didi-dialog-title" style="color:' + P
								+ ";font-size:" + K + ';">' + W.txt + "</p>";
					}
				}
			}
			if (J.tips && r(J.tips)) {
				for (var R = 0, N = J.tips.length; R < N; R++) {
					var I = J.tips[R];
					if (I) {
						var z = I.color || "#666666", v = I.fontSize || "1.0em";
						E += '<p class="didi-dialog-p" style="color:' + z
								+ ";font-size:" + v + ';">' + I.txt + "</p>";
					}
				}
			}
			if (J.btns && r(J.btns)) {
				E += '<div id="d_dialog_footer" class="didi-dialog-footer">';
				for (var Q = 0, V = J.btns.length; Q < V; Q++) {
					var A = J.btns[Q];
					if (A) {
						if (J.type === "confirm") {
							E += '<a class="'
									+ A.klsName
									+ '" id="'
									+ A.id
									+ '" style="width: 44%; height: 35px; line-height: 35px; margin:0 3%;">'
									+ A.txt + "</a>";
						} else {
							E += '<a class="' + A.klsName + '" id="' + A.id
									+ '" style="width: 88%;">' + A.txt + "</a>";
						}
					}
				}
				E += "</div>";
			}
			E += "</div>";
			u.innerHTML = E;
			tmp_d_wall = l.getElementById("d_wall");
			tmp_d_wrap = l.getElementById("d_wrap");
			if (!tmp_d_wall) {
				k(L);
			} else {
				i.removeChild(tmp_d_wall);
				k(L);
			}
			if (!tmp_d_wrap) {
				k(u);
			} else {
				i.removeChild(tmp_d_wrap);
				k(u);
			}
			n = l.getElementById("d_wall");
			c = l.getElementById("d_wrap");
			if (J.btns && J.btns.length && r(J.btns)) {
				for (var O = 0, C = J.btns.length; O < C; O++) {
					var H = J.btns[O];
					if (H) {
						var t = H.id, B = H.eventType || "click", y = H.callback, F = l
								.getElementById(t);
						if (F && !F["on" + B]) {
							F.addEventListener(B, y, false);
						} else {
						}
					}
				}
			}
		},
		_dialogPosi : function() {
			var y = q.scrollTop, v = q.scrollLeft;
			var u = q.clientHeight, x = q.clientWidth;
			var w = c.style.height.replace("px", ""), t = c.style.width
					.replace("px", "");
			var A = y + (u - w - 20) / 2, z = v + (x - t) / 2;
			return {
				top : A,
				left : z
			};
		}
	};
	o.fn.show = function() {
		var v = this;
		if (n && c) {
			var t = q.clientHeight, u = q.clientWidth;
			n.style.width = u + "px";
			n.style.height = t + "px";
			n.style.display = "block";
			var w = this._dialogPosi();
			c.style.top = w.top + "px";
			c.style.left = w.left + "px";
			c.style.display = "block";
			h.addEventListener("resize", function() {
				v.reset.call(v);
			}, false);
			h.addEventListener("scroll", function() {
				v.reset.call(v);
			}, false);
		}
	};
	o.fn.hide = function() {
		if (n && c) {
			n.style.display = "none";
			c.style.display = "none";
		}
	};
	o.fn.reset = function() {
		var w = n.style.display;
		if (w === "block" && n && c) {
			var t = l.documentElement.clientHeight, u = l.documentElement.clientWidth;
			n.style.width = u + "px";
			n.style.height = t + "px";
			var v = this._dialogPosi();
			c.style.top = v.top + "px";
			c.style.left = v.left + "px";
		}
	};
	p.alert = function(u, v) {
		var t = {
			bgcolor : "black",
			tips : [ {
				txt : u,
				fontSize : "1.1em"
			} ],
			icon : {
				url : "/static/webapp/images/i-alert.png",
				width : "61px",
				height : "61px"
			},
			btns : [ {
				id : "btn_close",
				txt : "我知道了",
				klsName : "btn-orange",
				eventType : "click",
				callback : function(w) {
					a.hide();
					if (v) {
						v();
					}
				}
			} ]
		};
		a = new o(t);
		a.show();
	};
	p.confirm = function(v, w, t, u) {
		a = new o({
			type : "confirm",
			icon : {
				url : "/static/webapp/images/i-alert.png",
				width : "60px",
				height : "60px"
			},
			tips : [ {
				txt : v || "确定执行此操作吗？",
				color : "#666666",
				fontSize : "1.04em"
			} ],
			btns : [ {
				id : "btn-ok",
				txt : "确定",
				klsName : "btn-orange",
				eventType : "click",
				callback : function(x) {
					a.hide();
					if (typeof w === "function") {
						w();
					}
				}
			}, {
				id : "btn-cancel",
				txt : "取消",
				klsName : "btn-white",
				eventType : "click",
				callback : function(x) {
					a.hide();
					if (typeof t === "function") {
						t();
					}
				}
			} ]
		});
		a.show();
		if (u === true) {
			return false;
		}
	};
	p.loading = function(v, u, w) {
		var t = {
			type : "loading",
			bgcolor : "#ffffff",
			bar : false,
			d_bgcolor : "black",
			d_opacity : "0.7",
			width : "125px",
			height : "125px",
			icon : {
				url : "/static/webapp/images/loading_2.gif",
				width : "30px",
				height : "30px"
			},
			tips : [ {
				txt : v || "正在加载...",
				color : "#FFFFFF",
				fontSize : "13px"
			} ]
		};
		a = new o(t);
		a.show();
		if (u === null || u === d) {
			u = 10000;
		}
		if (u) {
			h.setTimeout(function() {
				a.hide();
			}, u);
		}
		if (w) {
			w();
		}
	};
	h.didi.dialog = o;
})(window);