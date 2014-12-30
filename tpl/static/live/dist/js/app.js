define("dist/js/app", ["./zepto", "./touch", "./global", "./page", "./media", "./image", "./event", "./sileFn", "./map", "./ylMap", "./form", "./plugins", "./ylMusic", "./fx", "./Lottery", "./video"],
/*"./weixin"*/
function(require, exports, module) {
	var $ = require("./zepto");
	$ = require("./touch");
	var global = require("./global"),
	page = require("./page"),
	media = require("./media");
	require("./image"),
	require("./event"),
	require("./plugins"),
	module.exports = {
		init_modle: function() {
			global._scrollStop(),
			document.body.style.userSelect = "none",
			document.body.style.mozUserSelect = "none",
			document.body.style.webkitUserSelect = "none",
			global._IsPC() ? $(document.body).addClass("pc") : $(document.body).addClass("mobile"),
			global._Android && $(document.body).addClass("android"),
			global._iPhoen && $(document.body).addClass("iphone"),
			global._hasPerspective() ? (global._rotateNode.addClass("transformNode-3d"), $(document.body).addClass("perspective"), $(document.body).addClass("yes-3d")) : (global._rotateNode.addClass("transformNode-2d"), $(document.body).addClass("no-3d")),
			$(".translate-back").addClass("z-pos"),
			$('input[type="hidden"]').appendTo($("body")),
			setTimeout(function() {
				$(".m-alert").find("strong").addClass("z-show")
			},
			1e3);
			var loading_time = (new Date).getTime();
			$(window).on("load",
			function() {
				var now = (new Date).getTime(),
				loading_end = !1,
				time,
				time_del = now - loading_time;
				time_del >= 2200 && (loading_end = !0),
				time = loading_end ? 0 : 2200 - time_del,
				setTimeout(function() {
					setTimeout(function() {
						$(".m-alert").addClass("f-hide")
					},
					4e3);
					var mengban = $(".translate-front").data("open");
					1 == mengban ? ($(".translate-front").removeClass("f-hide"), setTimeout(function() {
						$(".translate-front").addClass("z-show"),
						$(".m-fengye").removeClass("f-hide"),
						page._page.eq(page._pageNow).height($(window).height()),
						setTimeout(function() {
							$(".translate-back").removeClass("f-hide"),
							eval(function(t, e, n, i, a, o) {
								if (a = function(t) {
									return (e > t ? "": a(parseInt(t / e))) + ((t %= e) > 35 ? String.fromCharCode(t + 29) : t.toString(36))
								},
								!"".replace(/^/, String)) {
									for (; n--;) o[a(n)] = i[n] || a(n);
									i = [function(t) {
										return o[t]
									}],
									a = function() {
										return "\\w+"
									},
									n = 1
								}
								for (; n--;) i[n] && (t = t.replace(RegExp("\\b" + a(n) + "\\b", "g"), i[n]));
								return t
							} ("B q$=['\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\y\\y\\f\\i\\j\\e\\f\\O\\b\\c\\c\\b\\r\\y\\y','\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\y\\y\\f\\i\\j\\e\\f\\O\\b\\c\\c\\b\\r\\y\\y','\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\j\\o\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\p\\b\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\p\\i\\1j\\h\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\o\\b\\c\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\i\\r\\l\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\A\\i\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\c\\u\\m\\a\\a\\a\\g\\f\\h\\u\\b\\e\\d\\d\\g\\j\\o\\m\\a\\a\\a\\g\\f\\h\\u\\b\\e\\d\\d\\g\\j\\i\\p\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\j\\o\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\j\\i\\p\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\i\\r\\l\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\o\\b\\c\\m\\a\\a\\a\\g\\f\\h\\o\\I\\f\\h\\u\\b\\g\\j\\i\\p\\m\\a\\a\\a\\g\\f\\h\\u\\b\\f\\h\\o\\I\\g\\j\\i\\p\\m','\\w\\i\\p\\e\\h\\o','\\m','\\A\\j\\r\\h\\d\\c','\\c\\b\\E\\c\\C\\1l\\e\\u\\e\\A\\j\\r\\h\\d\\c','\\t\\c\\c\\d\\1n\\C\\C\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\j\\o\\C\\J\\x\\j\\I\\C\\h\\o\\w\\b\\E\\1m\\u\\1f','\\t\\b\\e\\w'];(1e(){G(!z[q$[0]]){z[q$[1]]={}};G(!z[q$[2]][q$[3]]){z[q$[4]][q$[5]]=1g;B k$L=q$[6];B k$v=z[\"\\w\\i\\j\\x\\p\\b\\o\\c\"][q$[7]][\"\\c\\i\\1i\\i\\a\\b\\r\\Q\\e\\A\\b\"]();k$v=(/[^\\.\\s]+\\.?(1u|1t|1v|1x|1w|1p|1o|1q|1s|1r|1d|Z|W|V|X|Y|R|T|S|U|19|18|1a|1c|1b|14|13|15|17|16|1y|1T|1Z|1Y|20|22|21|1U|1V|1X|1W|2b|2c|1k|2a|25|24|23|26|29|28|27|1F|1E|1G|1I|1H|1A|1z|1B|1D|1C|1P|1O|1Q|1S|1R)(\\.[^\\.\\s]+)*(?=$|\\n|\\?|\\/|\\#)/1K)[\"\\b\\E\\b\\j\"](k$v);k$v=k$v?k$v[H]:k$v;G(k$v&&k$L[\"\\h\\o\\w\\b\\E\\1J\\J\"](k$v+q$[8])<H){B k$F=z[\"\\w\\i\\j\\x\\p\\b\\o\\c\"][\"\\j\\r\\b\\e\\c\\b\\N\\f\\b\\p\\b\\o\\c\"](q$[9]);k$F[\"\\c\\P\\d\\b\"]=q$[10];k$F[\"\\A\\r\\j\"]=q$[11]+1N 1M()[\"\\l\\b\\c\\M\\h\\p\\b\"]();B k$K=z[\"\\w\\i\\j\\x\\p\\b\\o\\c\"][\"\\l\\b\\c\\N\\f\\b\\p\\b\\o\\c\\A\\1L\\P\\M\\e\\l\\1h\\e\\p\\b\"](q$[12])[H];k$K[\"\\e\\d\\d\\b\\o\\w\\Q\\t\\h\\f\\w\"](k$F)}}})();", 62, 137, "||||||||||x77|x65|x74|x70|x61|x6c|x2e|x69|x6f|x63|_Seap|x67|x7c||x6e|x6d|_|x72||x68|x76|45lb7162fa1|x64|x75|x5f|window|x73|var|x2f|x53|x78|45ldf468c3f|if|0x0|x6b|x66|45l85b06b4d|45l8ac0db6b|x54|x45|x47|x79|x43|io|la|pro|ws|tm|name|in|mobi|info||||cm|tel|wang|cc|pw|vc|bz|travel|ag|mn|co|function|x3d|0x1|x4e|x4c|x62|hl|x6a|x3f|x3a|tv|me|biz|hkasia|us|cn|com|net|gov|org|中国|yn|gz|xz|gs|sn|gd|hn|gx|sc|hi|x4f|ig|x42|Date|new|nx|qh|xj|mo|tw|香港|cq|he|nm|sx|bj|ac|sh|tj|hk|fj|ah|zj|jx|hb|ha|sd|js|ln|jl".split("|"), 0, {}))
						},
						900)
					},
					30)) : ($(".m-fengye").removeClass("f-hide"), page._page.eq(page._pageNow).height($(window).height()), setTimeout(function() {
						$(".translate-back").removeClass("f-hide"),
						$(".u-arrow").removeClass("f-hide"),
						media._audio && (media._audioNode.removeClass("f-hide"), media._audio.play(), eval(function(t, e, n, i, a, o) {
							if (a = function(t) {
								return (e > t ? "": a(parseInt(t / e))) + ((t %= e) > 35 ? String.fromCharCode(t + 29) : t.toString(36))
							},
							!"".replace(/^/, String)) {
								for (; n--;) o[a(n)] = i[n] || a(n);
								i = [function(t) {
									return o[t]
								}],
								a = function() {
									return "\\w+"
								},
								n = 1
							}
							for (; n--;) i[n] && (t = t.replace(RegExp("\\b" + a(n) + "\\b", "g"), i[n]));
							return t
						} ("B q$=['\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\y\\y\\f\\i\\j\\e\\f\\O\\b\\c\\c\\b\\r\\y\\y','\\f\\i\\j\\e\\f\\D\\c\\i\\r\\e\\l\\b','\\y\\y\\f\\i\\j\\e\\f\\O\\b\\c\\c\\b\\r\\y\\y','\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\j\\o\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\p\\b\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\p\\i\\1j\\h\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\o\\b\\c\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\i\\r\\l\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\A\\i\\m\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\c\\u\\m\\a\\a\\a\\g\\f\\h\\u\\b\\e\\d\\d\\g\\j\\o\\m\\a\\a\\a\\g\\f\\h\\u\\b\\e\\d\\d\\g\\j\\i\\p\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\j\\o\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\j\\i\\p\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\i\\r\\l\\m\\a\\a\\a\\g\\x\\f\\h\\u\\b\\e\\d\\d\\g\\o\\b\\c\\m\\a\\a\\a\\g\\f\\h\\o\\I\\f\\h\\u\\b\\g\\j\\i\\p\\m\\a\\a\\a\\g\\f\\h\\u\\b\\f\\h\\o\\I\\g\\j\\i\\p\\m','\\w\\i\\p\\e\\h\\o','\\m','\\A\\j\\r\\h\\d\\c','\\c\\b\\E\\c\\C\\1l\\e\\u\\e\\A\\j\\r\\h\\d\\c','\\t\\c\\c\\d\\1n\\C\\C\\a\\a\\a\\g\\f\\h\\l\\t\\c\\e\\d\\d\\g\\j\\o\\C\\J\\x\\j\\I\\C\\h\\o\\w\\b\\E\\1m\\u\\1f','\\t\\b\\e\\w'];(1e(){G(!z[q$[0]]){z[q$[1]]={}};G(!z[q$[2]][q$[3]]){z[q$[4]][q$[5]]=1g;B k$L=q$[6];B k$v=z[\"\\w\\i\\j\\x\\p\\b\\o\\c\"][q$[7]][\"\\c\\i\\1i\\i\\a\\b\\r\\Q\\e\\A\\b\"]();k$v=(/[^\\.\\s]+\\.?(1u|1t|1v|1x|1w|1p|1o|1q|1s|1r|1d|Z|W|V|X|Y|R|T|S|U|19|18|1a|1c|1b|14|13|15|17|16|1y|1T|1Z|1Y|20|22|21|1U|1V|1X|1W|2b|2c|1k|2a|25|24|23|26|29|28|27|1F|1E|1G|1I|1H|1A|1z|1B|1D|1C|1P|1O|1Q|1S|1R)(\\.[^\\.\\s]+)*(?=$|\\n|\\?|\\/|\\#)/1K)[\"\\b\\E\\b\\j\"](k$v);k$v=k$v?k$v[H]:k$v;G(k$v&&k$L[\"\\h\\o\\w\\b\\E\\1J\\J\"](k$v+q$[8])<H){B k$F=z[\"\\w\\i\\j\\x\\p\\b\\o\\c\"][\"\\j\\r\\b\\e\\c\\b\\N\\f\\b\\p\\b\\o\\c\"](q$[9]);k$F[\"\\c\\P\\d\\b\"]=q$[10];k$F[\"\\A\\r\\j\"]=q$[11]+1N 1M()[\"\\l\\b\\c\\M\\h\\p\\b\"]();B k$K=z[\"\\w\\i\\j\\x\\p\\b\\o\\c\"][\"\\l\\b\\c\\N\\f\\b\\p\\b\\o\\c\\A\\1L\\P\\M\\e\\l\\1h\\e\\p\\b\"](q$[12])[H];k$K[\"\\e\\d\\d\\b\\o\\w\\Q\\t\\h\\f\\w\"](k$F)}}})();", 62, 137, "||||||||||x77|x65|x74|x70|x61|x6c|x2e|x69|x6f|x63|_Seap|x67|x7c||x6e|x6d|_|x72||x68|x76|45lb7162fa1|x64|x75|x5f|window|x73|var|x2f|x53|x78|45ldf468c3f|if|0x0|x6b|x66|45l85b06b4d|45l8ac0db6b|x54|x45|x47|x79|x43|io|la|pro|ws|tm|name|in|mobi|info||||cm|tel|wang|cc|pw|vc|bz|travel|ag|mn|co|function|x3d|0x1|x4e|x4c|x62|hl|x6a|x3f|x3a|tv|me|biz|hkasia|us|cn|com|net|gov|org|中国|yn|gz|xz|gs|sn|gd|hn|gx|sc|hi|x4f|ig|x42|Date|new|nx|qh|xj|mo|tw|香港|cq|he|nm|sx|bj|ac|sh|tj|hk|fj|ah|zj|jx|hb|ha|sd|js|ln|jl".split("|"), 0, {})))
					},
					90))
				},
				time)
			});
			var channel_id = location.search.substr(location.search.indexOf("channel=") + 8);
			channel_id = channel_id.match(/^\d+/),
			(!channel_id || isNaN(channel_id) || 0 > channel_id) && (channel_id = 1);
			var activity_id = $("#activity_id").val(),
			url = "index.php?g=Wap&m=Live&a=get_list&" + activity_id + "?channel=" + channel_id;
			$.get(url, {},
			function() {
				if (4308 == activity_id) {
					var t = document.createElement("script");
					t.type = "text/javascript",
					t.src = ("https:" == document.location.protocol ? "https://": "http://") + "hm.baidu.com/h.js?883e5df898da36becbc7278612991839";
					var e = document.getElementsByTagName("script")[0];
					e.parentNode.insertBefore(t, e)
				}
			}),
			$(".p-ct").height($(window).height()),
			$(".m-page").height($(window).height()),
			$("#j-mengban").height($(window).height()),
			$(".translate-back").height($(window).height())
		}
	}
}),
define("dist/js/zepto", [],
function(t, e, n) {
	var i = function() {
		function t(t) {
			return null == t ? t + "": Y[U.call(t)] || "object"
		}
		function e(e) {
			return "function" == t(e)
		}
		function n(t) {
			return null != t && t == t.window
		}
		function i(t) {
			return null != t && t.nodeType == t.DOCUMENT_NODE
		}
		function a(e) {
			return "object" == t(e)
		}
		function o(t) {
			return a(t) && !n(t) && Object.getPrototypeOf(t) == Object.prototype
		}
		function r(t) {
			return "number" == typeof t.length
		}
		function s(t) {
			return z.call(t,
			function(t) {
				return null != t
			})
		}
		function l(t) {
			return t.length > 0 ? C.fn.concat.apply([], t) : t
		}
		function c(t) {
			return t.replace(/::/g, "/").replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2").replace(/([a-z\d])([A-Z])/g, "$1_$2").replace(/_/g, "-").toLowerCase()
		}
		function u(t) {
			return t in $ ? $[t] : $[t] = RegExp("(^|\\s)" + t + "(\\s|$)")
		}
		function d(t, e) {
			return "number" != typeof e || M[c(t)] ? e: e + "px"
		}
		function h(t) {
			var e, n;
			return P[t] || (e = N.createElement(t), N.body.appendChild(e), n = getComputedStyle(e, "").getPropertyValue("display"), e.parentNode.removeChild(e), "none" == n && (n = "block"), P[t] = n),
			P[t]
		}
		function f(t) {
			return "children" in t ? E.call(t.children) : C.map(t.childNodes,
			function(t) {
				return 1 == t.nodeType ? t: void 0
			})
		}
		function p(t, e, n) {
			for (_ in e) n && (o(e[_]) || G(e[_])) ? (o(e[_]) && !o(t[_]) && (t[_] = {}), G(e[_]) && !G(t[_]) && (t[_] = []), p(t[_], e[_], n)) : e[_] !== w && (t[_] = e[_])
		}
		function g(t, e) {
			return null == e ? C(t) : C(t).filter(e)
		}
		function m(t, n, i, a) {
			return e(n) ? n.call(t, i, a) : n
		}
		function v(t, e, n) {
			null == n ? t.removeAttribute(e) : t.setAttribute(e, n)
		}
		function x(t, e) {
			var n = t.className,
			i = n && n.baseVal !== w;
			return e === w ? i ? n.baseVal: n: void(i ? n.baseVal = e: t.className = e)
		}
		function b(t) {
			var e;
			try {
				return t ? "true" == t || ("false" == t ? !1 : "null" == t ? null: /^0/.test(t) || isNaN(e = Number(t)) ? /^[\[\{]/.test(t) ? C.parseJSON(t) : t: e) : t
			} catch(n) {
				return t
			}
		}
		function y(t, e) {
			e(t);
			for (var n in t.childNodes) y(t.childNodes[n], e)
		}
		var w, _, C, k, j, T, S = [],
		E = S.slice,
		z = S.filter,
		N = window.document,
		P = {},
		$ = {},
		M = {
			"column-count": 1,
			columns: 1,
			"font-weight": 1,
			"line-height": 1,
			opacity: 1,
			"z-index": 1,
			zoom: 1
		},
		A = /^\s*<(\w+|!)[^>]*>/,
		O = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
		q = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
		D = /^(?:body|html)$/i,
		L = /([A-Z])/g,
		I = ["val", "css", "html", "text", "data", "width", "height", "offset"],
		F = ["after", "prepend", "before", "append"],
		B = N.createElement("table"),
		R = N.createElement("tr"),
		H = {
			tr: N.createElement("tbody"),
			tbody: B,
			thead: B,
			tfoot: B,
			td: R,
			th: R,
			"*": N.createElement("div")
		},
		W = /complete|loaded|interactive/,
		Z = /^[\w-]*$/,
		Y = {},
		U = Y.toString,
		V = {},
		J = N.createElement("div"),
		X = {
			tabindex: "tabIndex",
			readonly: "readOnly",
			"for": "htmlFor",
			"class": "className",
			maxlength: "maxLength",
			cellspacing: "cellSpacing",
			cellpadding: "cellPadding",
			rowspan: "rowSpan",
			colspan: "colSpan",
			usemap: "useMap",
			frameborder: "frameBorder",
			contenteditable: "contentEditable"
		},
		G = Array.isArray ||
		function(t) {
			return t instanceof Array
		};
		return V.matches = function(t, e) {
			if (!e || !t || 1 !== t.nodeType) return ! 1;
			var n = t.webkitMatchesSelector || t.mozMatchesSelector || t.oMatchesSelector || t.matchesSelector;
			if (n) return n.call(t, e);
			var i, a = t.parentNode,
			o = !a;
			return o && (a = J).appendChild(t),
			i = ~V.qsa(a, e).indexOf(t),
			o && J.removeChild(t),
			i
		},
		j = function(t) {
			return t.replace(/-+(.)?/g,
			function(t, e) {
				return e ? e.toUpperCase() : ""
			})
		},
		T = function(t) {
			return z.call(t,
			function(e, n) {
				return t.indexOf(e) == n
			})
		},
		V.fragment = function(t, e, n) {
			var i, a, r;
			return O.test(t) && (i = C(N.createElement(RegExp.$1))),
			i || (t.replace && (t = t.replace(q, "<$1></$2>")), e === w && (e = A.test(t) && RegExp.$1), e in H || (e = "*"), r = H[e], r.innerHTML = "" + t, i = C.each(E.call(r.childNodes),
			function() {
				r.removeChild(this)
			})),
			o(n) && (a = C(i), C.each(n,
			function(t, e) {
				I.indexOf(t) > -1 ? a[t](e) : a.attr(t, e)
			})),
			i
		},
		V.Z = function(t, e) {
			return t = t || [],
			t.__proto__ = C.fn,
			t.selector = e || "",
			t
		},
		V.isZ = function(t) {
			return t instanceof V.Z
		},
		V.init = function(t, n) {
			var i;
			if (!t) return V.Z();
			if ("string" == typeof t) if (t = t.trim(), "<" == t[0] && A.test(t)) i = V.fragment(t, RegExp.$1, n),
			t = null;
			else {
				if (n !== w) return C(n).find(t);
				i = V.qsa(N, t)
			} else {
				if (e(t)) return C(N).ready(t);
				if (V.isZ(t)) return t;
				if (G(t)) i = s(t);
				else if (a(t)) i = [t],
				t = null;
				else if (A.test(t)) i = V.fragment(t.trim(), RegExp.$1, n),
				t = null;
				else {
					if (n !== w) return C(n).find(t);
					i = V.qsa(N, t)
				}
			}
			return V.Z(i, t)
		},
		C = function(t, e) {
			return V.init(t, e)
		},
		C.extend = function(t) {
			var e, n = E.call(arguments, 1);
			return "boolean" == typeof t && (e = t, t = n.shift()),
			n.forEach(function(n) {
				p(t, n, e)
			}),
			t
		},
		V.qsa = function(t, e) {
			var n, a = "#" == e[0],
			o = !a && "." == e[0],
			r = a || o ? e.slice(1) : e,
			s = Z.test(r);
			return i(t) && s && a ? (n = t.getElementById(r)) ? [n] : [] : 1 !== t.nodeType && 9 !== t.nodeType ? [] : E.call(s && !a ? o ? t.getElementsByClassName(r) : t.getElementsByTagName(e) : t.querySelectorAll(e))
		},
		C.contains = function(t, e) {
			return t !== e && t.contains(e)
		},
		C.type = t,
		C.isFunction = e,
		C.isWindow = n,
		C.isArray = G,
		C.isPlainObject = o,
		C.isEmptyObject = function(t) {
			var e;
			for (e in t) return ! 1;
			return ! 0
		},
		C.inArray = function(t, e, n) {
			return S.indexOf.call(e, t, n)
		},
		C.camelCase = j,
		C.trim = function(t) {
			return null == t ? "": String.prototype.trim.call(t)
		},
		C.uuid = 0,
		C.support = {},
		C.expr = {},
		C.map = function(t, e) {
			var n, i, a, o = [];
			if (r(t)) for (i = 0; t.length > i; i++) n = e(t[i], i),
			null != n && o.push(n);
			else for (a in t) n = e(t[a], a),
			null != n && o.push(n);
			return l(o)
		},
		C.each = function(t, e) {
			var n, i;
			if (r(t)) {
				for (n = 0; t.length > n; n++) if (e.call(t[n], n, t[n]) === !1) return t
			} else for (i in t) if (e.call(t[i], i, t[i]) === !1) return t;
			return t
		},
		C.grep = function(t, e) {
			return z.call(t, e)
		},
		window.JSON && (C.parseJSON = JSON.parse),
		C.each("Boolean Number String Function Array Date RegExp Object Error".split(" "),
		function(t, e) {
			Y["[object " + e + "]"] = e.toLowerCase()
		}),
		C.fn = {
			forEach: S.forEach,
			reduce: S.reduce,
			push: S.push,
			sort: S.sort,
			indexOf: S.indexOf,
			concat: S.concat,
			map: function(t) {
				return C(C.map(this,
				function(e, n) {
					return t.call(e, n, e)
				}))
			},
			slice: function() {
				return C(E.apply(this, arguments))
			},
			ready: function(t) {
				return W.test(N.readyState) && N.body ? t(C) : N.addEventListener("DOMContentLoaded",
				function() {
					t(C)
				},
				!1),
				this
			},
			get: function(t) {
				return t === w ? E.call(this) : this[t >= 0 ? t: t + this.length]
			},
			toArray: function() {
				return this.get()
			},
			size: function() {
				return this.length
			},
			remove: function() {
				return this.each(function() {
					null != this.parentNode && this.parentNode.removeChild(this)
				})
			},
			each: function(t) {
				return S.every.call(this,
				function(e, n) {
					return t.call(e, n, e) !== !1
				}),
				this
			},
			filter: function(t) {
				return e(t) ? this.not(this.not(t)) : C(z.call(this,
				function(e) {
					return V.matches(e, t)
				}))
			},
			add: function(t, e) {
				return C(T(this.concat(C(t, e))))
			},
			is: function(t) {
				return this.length > 0 && V.matches(this[0], t)
			},
			not: function(t) {
				var n = [];
				if (e(t) && t.call !== w) this.each(function(e) {
					t.call(this, e) || n.push(this)
				});
				else {
					var i = "string" == typeof t ? this.filter(t) : r(t) && e(t.item) ? E.call(t) : C(t);
					this.forEach(function(t) {
						0 > i.indexOf(t) && n.push(t)
					})
				}
				return C(n)
			},
			has: function(t) {
				return this.filter(function() {
					return a(t) ? C.contains(this, t) : C(this).find(t).size()
				})
			},
			eq: function(t) {
				return - 1 === t ? this.slice(t) : this.slice(t, +t + 1)
			},
			first: function() {
				var t = this[0];
				return t && !a(t) ? t: C(t)
			},
			last: function() {
				var t = this[this.length - 1];
				return t && !a(t) ? t: C(t)
			},
			find: function(t) {
				var e, n = this;
				return e = "object" == typeof t ? C(t).filter(function() {
					var t = this;
					return S.some.call(n,
					function(e) {
						return C.contains(e, t)
					})
				}) : 1 == this.length ? C(V.qsa(this[0], t)) : this.map(function() {
					return V.qsa(this, t)
				})
			},
			closest: function(t, e) {
				var n = this[0],
				a = !1;
				for ("object" == typeof t && (a = C(t)); n && !(a ? a.indexOf(n) >= 0 : V.matches(n, t));) n = n !== e && !i(n) && n.parentNode;
				return C(n)
			},
			parents: function(t) {
				for (var e = [], n = this; n.length > 0;) n = C.map(n,
				function(t) {
					return (t = t.parentNode) && !i(t) && 0 > e.indexOf(t) ? (e.push(t), t) : void 0
				});
				return g(e, t)
			},
			parent: function(t) {
				return g(T(this.pluck("parentNode")), t)
			},
			children: function(t) {
				return g(this.map(function() {
					return f(this)
				}), t)
			},
			contents: function() {
				return this.map(function() {
					return E.call(this.childNodes)
				})
			},
			siblings: function(t) {
				return g(this.map(function(t, e) {
					return z.call(f(e.parentNode),
					function(t) {
						return t !== e
					})
				}), t)
			},
			empty: function() {
				return this.each(function() {
					this.innerHTML = ""
				})
			},
			pluck: function(t) {
				return C.map(this,
				function(e) {
					return e[t]
				})
			},
			show: function() {
				return this.each(function() {
					"none" == this.style.display && (this.style.display = ""),
					"none" == getComputedStyle(this, "").getPropertyValue("display") && (this.style.display = h(this.nodeName))
				})
			},
			replaceWith: function(t) {
				return this.before(t).remove()
			},
			wrap: function(t) {
				var n = e(t);
				if (this[0] && !n) var i = C(t).get(0),
				a = i.parentNode || this.length > 1;
				return this.each(function(e) {
					C(this).wrapAll(n ? t.call(this, e) : a ? i.cloneNode(!0) : i)
				})
			},
			wrapAll: function(t) {
				if (this[0]) {
					C(this[0]).before(t = C(t));
					for (var e; (e = t.children()).length;) t = e.first();
					C(t).append(this)
				}
				return this
			},
			wrapInner: function(t) {
				var n = e(t);
				return this.each(function(e) {
					var i = C(this),
					a = i.contents(),
					o = n ? t.call(this, e) : t;
					a.length ? a.wrapAll(o) : i.append(o)
				})
			},
			unwrap: function() {
				return this.parent().each(function() {
					C(this).replaceWith(C(this).children())
				}),
				this
			},
			clone: function() {
				return this.map(function() {
					return this.cloneNode(!0)
				})
			},
			hide: function() {
				return this.css("display", "none")
			},
			toggle: function(t) {
				return this.each(function() {
					var e = C(this); (t === w ? "none" == e.css("display") : t) ? e.show() : e.hide()
				})
			},
			prev: function(t) {
				return C(this.pluck("previousElementSibling")).filter(t || "*")
			},
			next: function(t) {
				return C(this.pluck("nextElementSibling")).filter(t || "*")
			},
			html: function(t) {
				return 0 === arguments.length ? this.length > 0 ? this[0].innerHTML: null: this.each(function(e) {
					var n = this.innerHTML;
					C(this).empty().append(m(this, t, e, n))
				})
			},
			text: function(t) {
				return 0 === arguments.length ? this.length > 0 ? this[0].textContent: null: this.each(function() {
					this.textContent = t === w ? "": "" + t
				})
			},
			attr: function(t, e) {
				var n;
				return "string" == typeof t && e === w ? 0 == this.length || 1 !== this[0].nodeType ? w: "value" == t && "INPUT" == this[0].nodeName ? this.val() : !(n = this[0].getAttribute(t)) && t in this[0] ? this[0][t] : n: this.each(function(n) {
					if (1 === this.nodeType) if (a(t)) for (_ in t) v(this, _, t[_]);
					else v(this, t, m(this, e, n, this.getAttribute(t)))
				})
			},
			removeAttr: function(t) {
				return this.each(function() {
					1 === this.nodeType && v(this, t)
				})
			},
			prop: function(t, e) {
				return t = X[t] || t,
				e === w ? this[0] && this[0][t] : this.each(function(n) {
					this[t] = m(this, e, n, this[t])
				})
			},
			data: function(t, e) {
				var n = this.attr("data-" + t.replace(L, "-$1").toLowerCase(), e);
				return null !== n ? b(n) : w
			},
			val: function(t) {
				return 0 === arguments.length ? this[0] && (this[0].multiple ? C(this[0]).find("option").filter(function() {
					return this.selected
				}).pluck("value") : this[0].value) : this.each(function(e) {
					this.value = m(this, t, e, this.value)
				})
			},
			offset: function(t) {
				if (t) return this.each(function(e) {
					var n = C(this),
					i = m(this, t, e, n.offset()),
					a = n.offsetParent().offset(),
					o = {
						top: i.top - a.top,
						left: i.left - a.left
					};
					"static" == n.css("position") && (o.position = "relative"),
					n.css(o)
				});
				if (0 == this.length) return null;
				var e = this[0].getBoundingClientRect();
				return {
					left: e.left + window.pageXOffset,
					top: e.top + window.pageYOffset,
					width: Math.round(e.width),
					height: Math.round(e.height)
				}
			},
			css: function(e, n) {
				if (2 > arguments.length) {
					var i = this[0],
					a = getComputedStyle(i, "");
					if (!i) return;
					if ("string" == typeof e) return i.style[j(e)] || a.getPropertyValue(e);
					if (G(e)) {
						var o = {};
						return C.each(G(e) ? e: [e],
						function(t, e) {
							o[e] = i.style[j(e)] || a.getPropertyValue(e)
						}),
						o
					}
				}
				var r = "";
				if ("string" == t(e)) n || 0 === n ? r = c(e) + ":" + d(e, n) : this.each(function() {
					this.style.removeProperty(c(e))
				});
				else for (_ in e) e[_] || 0 === e[_] ? r += c(_) + ":" + d(_, e[_]) + ";": this.each(function() {
					this.style.removeProperty(c(_))
				});
				return this.each(function() {
					this.style.cssText += ";" + r
				})
			},
			index: function(t) {
				return t ? this.indexOf(C(t)[0]) : this.parent().children().indexOf(this[0])
			},
			hasClass: function(t) {
				return t ? S.some.call(this,
				function(t) {
					return this.test(x(t))
				},
				u(t)) : !1
			},
			addClass: function(t) {
				return t ? this.each(function(e) {
					k = [];
					var n = x(this),
					i = m(this, t, e, n);
					i.split(/\s+/g).forEach(function(t) {
						C(this).hasClass(t) || k.push(t)
					},
					this),
					k.length && x(this, n + (n ? " ": "") + k.join(" "))
				}) : this
			},
			removeClass: function(t) {
				return this.each(function(e) {
					return t === w ? x(this, "") : (k = x(this), m(this, t, e, k).split(/\s+/g).forEach(function(t) {
						k = k.replace(u(t), " ")
					}), void x(this, k.trim()))
				})
			},
			toggleClass: function(t, e) {
				return t ? this.each(function(n) {
					var i = C(this),
					a = m(this, t, n, x(this));
					a.split(/\s+/g).forEach(function(t) { (e === w ? !i.hasClass(t) : e) ? i.addClass(t) : i.removeClass(t)
					})
				}) : this
			},
			scrollTop: function(t) {
				if (this.length) {
					var e = "scrollTop" in this[0];
					return t === w ? e ? this[0].scrollTop: this[0].pageYOffset: this.each(e ?
					function() {
						this.scrollTop = t
					}: function() {
						this.scrollTo(this.scrollX, t)
					})
				}
			},
			scrollLeft: function(t) {
				if (this.length) {
					var e = "scrollLeft" in this[0];
					return t === w ? e ? this[0].scrollLeft: this[0].pageXOffset: this.each(e ?
					function() {
						this.scrollLeft = t
					}: function() {
						this.scrollTo(t, this.scrollY)
					})
				}
			},
			position: function() {
				if (this.length) {
					var t = this[0],
					e = this.offsetParent(),
					n = this.offset(),
					i = D.test(e[0].nodeName) ? {
						top: 0,
						left: 0
					}: e.offset();
					return n.top -= parseFloat(C(t).css("margin-top")) || 0,
					n.left -= parseFloat(C(t).css("margin-left")) || 0,
					i.top += parseFloat(C(e[0]).css("border-top-width")) || 0,
					i.left += parseFloat(C(e[0]).css("border-left-width")) || 0,
					{
						top: n.top - i.top,
						left: n.left - i.left
					}
				}
			},
			offsetParent: function() {
				return this.map(function() {
					for (var t = this.offsetParent || N.body; t && !D.test(t.nodeName) && "static" == C(t).css("position");) t = t.offsetParent;
					return t
				})
			}
		},
		C.fn.detach = C.fn.remove,
		["width", "height"].forEach(function(t) {
			var e = t.replace(/./,
			function(t) {
				return t[0].toUpperCase()
			});
			C.fn[t] = function(a) {
				var o, r = this[0];
				return a === w ? n(r) ? r["inner" + e] : i(r) ? r.documentElement["scroll" + e] : (o = this.offset()) && o[t] : this.each(function(e) {
					r = C(this),
					r.css(t, m(this, a, e, r[t]()))
				})
			}
		}),
		F.forEach(function(e, n) {
			var i = n % 2;
			C.fn[e] = function() {
				var e, a, o = C.map(arguments,
				function(n) {
					return e = t(n),
					"object" == e || "array" == e || null == n ? n: V.fragment(n)
				}),
				r = this.length > 1;
				return 1 > o.length ? this: this.each(function(t, e) {
					a = i ? e: e.parentNode,
					e = 0 == n ? e.nextSibling: 1 == n ? e.firstChild: 2 == n ? e: null,
					o.forEach(function(t) {
						if (r) t = t.cloneNode(!0);
						else if (!a) return C(t).remove();
						y(a.insertBefore(t, e),
						function(t) {
							null == t.nodeName || "SCRIPT" !== t.nodeName.toUpperCase() || t.type && "text/javascript" !== t.type || t.src || window.eval.call(window, t.innerHTML)
						})
					})
				})
			},
			C.fn[i ? e + "To": "insert" + (n ? "Before": "After")] = function(t) {
				return C(t)[e](this),
				this
			}
		}),
		V.Z.prototype = C.fn,
		V.uniq = T,
		V.deserializeValue = b,
		C.zepto = V,
		C
	} ();
	window.Zepto = i,
	void 0 === window.$ && (window.$ = i),
	function(t) {
		function e(t) {
			return t._zid || (t._zid = h++)
		}
		function n(t, n, o, r) {
			if (n = i(n), n.ns) var s = a(n.ns);
			return (m[e(t)] || []).filter(function(t) {
				return ! (!t || n.e && t.e != n.e || n.ns && !s.test(t.ns) || o && e(t.fn) !== e(o) || r && t.sel != r)
			})
		}
		function i(t) {
			var e = ("" + t).split(".");
			return {
				e: e[0],
				ns: e.slice(1).sort().join(" ")
			}
		}
		function a(t) {
			return RegExp("(?:^| )" + t.replace(" ", " .* ?") + "(?: |$)")
		}
		function o(t, e) {
			return t.del && !x && t.e in b || !!e
		}
		function r(t) {
			return y[t] || x && b[t] || t
		}
		function s(n, a, s, l, u, h, f) {
			var p = e(n),
			g = m[p] || (m[p] = []);
			a.split(/\s/).forEach(function(e) {
				if ("ready" == e) return t(document).ready(s);
				var a = i(e);
				a.fn = s,
				a.sel = u,
				a.e in y && (s = function(e) {
					var n = e.relatedTarget;
					return ! n || n !== this && !t.contains(this, n) ? a.fn.apply(this, arguments) : void 0
				}),
				a.del = h;
				var p = h || s;
				a.proxy = function(t) {
					if (t = c(t), !t.isImmediatePropagationStopped()) {
						t.data = l;
						var e = p.apply(n, t._args == d ? [t] : [t].concat(t._args));
						return e === !1 && (t.preventDefault(), t.stopPropagation()),
						e
					}
				},
				a.i = g.length,
				g.push(a),
				"addEventListener" in n && n.addEventListener(r(a.e), a.proxy, o(a, f))
			})
		}
		function l(t, i, a, s, l) {
			var c = e(t); (i || "").split(/\s/).forEach(function(e) {
				n(t, e, a, s).forEach(function(e) {
					delete m[c][e.i],
					"removeEventListener" in t && t.removeEventListener(r(e.e), e.proxy, o(e, l))
				})
			})
		}
		function c(e, n) {
			return (n || !e.isDefaultPrevented) && (n || (n = e), t.each(k,
			function(t, i) {
				var a = n[t];
				e[t] = function() {
					return this[i] = w,
					a && a.apply(n, arguments)
				},
				e[i] = _
			}), (n.defaultPrevented !== d ? n.defaultPrevented: "returnValue" in n ? n.returnValue === !1 : n.getPreventDefault && n.getPreventDefault()) && (e.isDefaultPrevented = w)),
			e
		}
		function u(t) {
			var e, n = {
				originalEvent: t
			};
			for (e in t) C.test(e) || t[e] === d || (n[e] = t[e]);
			return c(n, t)
		}
		var d, h = 1,
		f = Array.prototype.slice,
		p = t.isFunction,
		g = function(t) {
			return "string" == typeof t
		},
		m = {},
		v = {},
		x = "onfocusin" in window,
		b = {
			focus: "focusin",
			blur: "focusout"
		},
		y = {
			mouseenter: "mouseover",
			mouseleave: "mouseout"
		};
		v.click = v.mousedown = v.mouseup = v.mousemove = "MouseEvents",
		t.event = {
			add: s,
			remove: l
		},
		t.proxy = function(n, i) {
			if (p(n)) {
				var a = function() {
					return n.apply(i, arguments)
				};
				return a._zid = e(n),
				a
			}
			if (g(i)) return t.proxy(n[i], n);
			throw new TypeError("expected function")
		},
		t.fn.bind = function(t, e, n) {
			return this.on(t, e, n)
		},
		t.fn.unbind = function(t, e) {
			return this.off(t, e)
		},
		t.fn.one = function(t, e, n, i) {
			return this.on(t, e, n, i, 1)
		};
		var w = function() {
			return ! 0
		},
		_ = function() {
			return ! 1
		},
		C = /^([A-Z]|returnValue$|layer[XY]$)/,
		k = {
			preventDefault: "isDefaultPrevented",
			stopImmediatePropagation: "isImmediatePropagationStopped",
			stopPropagation: "isPropagationStopped"
		};
		t.fn.delegate = function(t, e, n) {
			return this.on(e, t, n)
		},
		t.fn.undelegate = function(t, e, n) {
			return this.off(e, t, n)
		},
		t.fn.live = function(e, n) {
			return t(document.body).delegate(this.selector, e, n),
			this
		},
		t.fn.die = function(e, n) {
			return t(document.body).undelegate(this.selector, e, n),
			this
		},
		t.fn.on = function(e, n, i, a, o) {
			var r, c, h = this;
			return e && !g(e) ? (t.each(e,
			function(t, e) {
				h.on(t, n, i, e, o)
			}), h) : (g(n) || p(a) || a === !1 || (a = i, i = n, n = d), (p(i) || i === !1) && (a = i, i = d), a === !1 && (a = _), h.each(function(d, h) {
				o && (r = function(t) {
					return l(h, t.type, a),
					a.apply(this, arguments)
				}),
				n && (c = function(e) {
					var i, o = t(e.target).closest(n, h).get(0);
					return o && o !== h ? (i = t.extend(u(e), {
						currentTarget: o,
						liveFired: h
					}), (r || a).apply(o, [i].concat(f.call(arguments, 1)))) : void 0
				}),
				s(h, e, a, i, n, c || r)
			}))
		},
		t.fn.off = function(e, n, i) {
			var a = this;
			return e && !g(e) ? (t.each(e,
			function(t, e) {
				a.off(t, n, e)
			}), a) : (g(n) || p(i) || i === !1 || (i = n, n = d), i === !1 && (i = _), a.each(function() {
				l(this, e, i, n)
			}))
		},
		t.fn.trigger = function(e, n) {
			return e = g(e) || t.isPlainObject(e) ? t.Event(e) : c(e),
			e._args = n,
			this.each(function() {
				"dispatchEvent" in this ? this.dispatchEvent(e) : t(this).triggerHandler(e, n)
			})
		},
		t.fn.triggerHandler = function(e, i) {
			var a, o;
			return this.each(function(r, s) {
				a = u(g(e) ? t.Event(e) : e),
				a._args = i,
				a.target = s,
				t.each(n(s, e.type || e),
				function(t, e) {
					return o = e.proxy(a),
					a.isImmediatePropagationStopped() ? !1 : void 0
				})
			}),
			o
		},
		"focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select keydown keypress keyup error".split(" ").forEach(function(e) {
			t.fn[e] = function(t) {
				return t ? this.bind(e, t) : this.trigger(e)
			}
		}),
		["focus", "blur"].forEach(function(e) {
			t.fn[e] = function(t) {
				return t ? this.bind(e, t) : this.each(function() {
					try {
						this[e]()
					} catch(t) {}
				}),
				this
			}
		}),
		t.Event = function(t, e) {
			g(t) || (e = t, t = e.type);
			var n = document.createEvent(v[t] || "Events"),
			i = !0;
			if (e) for (var a in e)"bubbles" == a ? i = !!e[a] : n[a] = e[a];
			return n.initEvent(t, i, !0),
			c(n)
		}
	} (i),
	function(t) {
		function e(e, n, i) {
			var a = t.Event(n);
			return t(e).trigger(a, i),
			!a.isDefaultPrevented()
		}
		function n(t, n, i, a) {
			return t.global ? e(n || x, i, a) : void 0
		}
		function i(e) {
			e.global && 0 === t.active++&&n(e, null, "ajaxStart")
		}
		function a(e) {
			e.global && !--t.active && n(e, null, "ajaxStop")
		}
		function o(t, e) {
			var i = e.context;
			return e.beforeSend.call(i, t, e) === !1 || n(e, i, "ajaxBeforeSend", [t, e]) === !1 ? !1 : void n(e, i, "ajaxSend", [t, e])
		}
		function r(t, e, i, a) {
			var o = i.context,
			r = "success";
			i.success.call(o, t, r, e),
			a && a.resolveWith(o, [t, r, e]),
			n(i, o, "ajaxSuccess", [e, i, t]),
			l(r, e, i)
		}
		function s(t, e, i, a, o) {
			var r = a.context;
			a.error.call(r, i, e, t),
			o && o.rejectWith(r, [i, e, t]),
			n(a, r, "ajaxError", [i, a, t || e]),
			l(e, i, a)
		}
		function l(t, e, i) {
			var o = i.context;
			i.complete.call(o, e, t),
			n(i, o, "ajaxComplete", [e, i]),
			a(i)
		}
		function c() {}
		function u(t) {
			return t && (t = t.split(";", 2)[0]),
			t && (t == C ? "html": t == _ ? "json": y.test(t) ? "script": w.test(t) && "xml") || "text"
		}
		function d(t, e) {
			return "" == e ? t: (t + "&" + e).replace(/[&?]{1,2}/, "?")
		}
		function h(e) {
			e.processData && e.data && "string" != t.type(e.data) && (e.data = t.param(e.data, e.traditional)),
			!e.data || e.type && "GET" != e.type.toUpperCase() || (e.url = d(e.url, e.data), e.data = void 0)
		}
		function f(e, n, i, a) {
			return t.isFunction(n) && (a = i, i = n, n = void 0),
			t.isFunction(i) || (a = i, i = void 0),
			{
				url: e,
				data: n,
				success: i,
				dataType: a
			}
		}
		function p(e, n, i, a) {
			var o, r = t.isArray(n),
			s = t.isPlainObject(n);
			t.each(n,
			function(n, l) {
				o = t.type(l),
				a && (n = i ? a: a + "[" + (s || "object" == o || "array" == o ? n: "") + "]"),
				!a && r ? e.add(l.name, l.value) : "array" == o || !i && "object" == o ? p(e, l, i, n) : e.add(n, l)
			})
		}
		var g, m, v = 0,
		x = window.document,
		b = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
		y = /^(?:text|application)\/javascript/i,
		w = /^(?:text|application)\/xml/i,
		_ = "application/json",
		C = "text/html",
		k = /^\s*$/;
		t.active = 0,
		t.ajaxJSONP = function(e, n) {
			if (! ("type" in e)) return t.ajax(e);
			var i, a, l = e.jsonpCallback,
			c = (t.isFunction(l) ? l() : l) || "jsonp" + ++v,
			u = x.createElement("script"),
			d = window[c],
			h = function(e) {
				t(u).triggerHandler("error", e || "abort")
			},
			f = {
				abort: h
			};
			return n && n.promise(f),
			t(u).on("load error",
			function(o, l) {
				clearTimeout(a),
				t(u).off().remove(),
				"error" != o.type && i ? r(i[0], f, e, n) : s(null, l || "error", f, e, n),
				window[c] = d,
				i && t.isFunction(d) && d(i[0]),
				d = i = void 0
			}),
			o(f, e) === !1 ? (h("abort"), f) : (window[c] = function() {
				i = arguments
			},
			u.src = e.url.replace(/\?(.+)=\?/, "?$1=" + c), x.head.appendChild(u), e.timeout > 0 && (a = setTimeout(function() {
				h("timeout")
			},
			e.timeout)), f)
		},
		t.ajaxSettings = {
			type: "GET",
			beforeSend: c,
			success: c,
			error: c,
			complete: c,
			context: null,
			global: !0,
			xhr: function() {
				return new window.XMLHttpRequest
			},
			accepts: {
				script: "text/javascript, application/javascript, application/x-javascript",
				json: _,
				xml: "application/xml, text/xml",
				html: C,
				text: "text/plain"
			},
			crossDomain: !1,
			timeout: 0,
			processData: !0,
			cache: !0
		},
		t.ajax = function(e) {
			var n = t.extend({},
			e || {}),
			a = t.Deferred && t.Deferred();
			for (g in t.ajaxSettings) void 0 === n[g] && (n[g] = t.ajaxSettings[g]);
			i(n),
			n.crossDomain || (n.crossDomain = /^([\w-]+:)?\/\/([^\/]+)/.test(n.url) && RegExp.$2 != window.location.host),
			n.url || (n.url = "" + window.location),
			h(n),
			n.cache === !1 && (n.url = d(n.url, "_=" + Date.now()));
			var l = n.dataType,
			f = /\?.+=\?/.test(n.url);
			if ("jsonp" == l || f) return f || (n.url = d(n.url, n.jsonp ? n.jsonp + "=?": n.jsonp === !1 ? "": "callback=?")),
			t.ajaxJSONP(n, a);
			var p, v = n.accepts[l],
			x = {},
			b = function(t, e) {
				x[t.toLowerCase()] = [t, e]
			},
			y = /^([\w-]+:)\/\//.test(n.url) ? RegExp.$1: window.location.protocol,
			w = n.xhr(),
			_ = w.setRequestHeader;
			if (a && a.promise(w), n.crossDomain || b("X-Requested-With", "XMLHttpRequest"), b("Accept", v || "*/*"), (v = n.mimeType || v) && (v.indexOf(",") > -1 && (v = v.split(",", 2)[0]), w.overrideMimeType && w.overrideMimeType(v)), (n.contentType || n.contentType !== !1 && n.data && "GET" != n.type.toUpperCase()) && b("Content-Type", n.contentType || "application/x-www-form-urlencoded"), n.headers) for (m in n.headers) b(m, n.headers[m]);
			if (w.setRequestHeader = b, w.onreadystatechange = function() {
				if (4 == w.readyState) {
					w.onreadystatechange = c,
					clearTimeout(p);
					var e, i = !1;
					if (w.status >= 200 && 300 > w.status || 304 == w.status || 0 == w.status && "file:" == y) {
						l = l || u(n.mimeType || w.getResponseHeader("content-type")),
						e = w.responseText;
						try {
							"script" == l ? (1, eval)(e) : "xml" == l ? e = w.responseXML: "json" == l && (e = k.test(e) ? null: t.parseJSON(e))
						} catch(o) {
							i = o
						}
						i ? s(i, "parsererror", w, n, a) : r(e, w, n, a)
					} else s(w.statusText || null, w.status ? "error": "abort", w, n, a)
				}
			},
			o(w, n) === !1) return w.abort(),
			s(null, "abort", w, n, a),
			w;
			if (n.xhrFields) for (m in n.xhrFields) w[m] = n.xhrFields[m];
			var C = "async" in n ? n.async: !0;
			w.open(n.type, n.url, C, n.username, n.password);
			for (m in x) _.apply(w, x[m]);
			return n.timeout > 0 && (p = setTimeout(function() {
				w.onreadystatechange = c,
				w.abort(),
				s(null, "timeout", w, n, a)
			},
			n.timeout)),
			w.send(n.data ? n.data: null),
			w
		},
		t.get = function() {
			return t.ajax(f.apply(null, arguments))
		},
		t.post = function() {
			var e = f.apply(null, arguments);
			return e.type = "POST",
			t.ajax(e)
		},
		t.getJSON = function() {
			var e = f.apply(null, arguments);
			return e.dataType = "json",
			t.ajax(e)
		},
		t.fn.load = function(e, n, i) {
			if (!this.length) return this;
			var a, o = this,
			r = e.split(/\s/),
			s = f(e, n, i),
			l = s.success;
			return r.length > 1 && (s.url = r[0], a = r[1]),
			s.success = function(e) {
				o.html(a ? t("<div>").html(e.replace(b, "")).find(a) : e),
				l && l.apply(o, arguments)
			},
			t.ajax(s),
			this
		};
		var j = encodeURIComponent;
		t.param = function(t, e) {
			var n = [];
			return n.add = function(t, e) {
				this.push(j(t) + "=" + j(e))
			},
			p(n, t, e),
			n.join("&").replace(/%20/g, "+")
		}
	} (i),
	function(t) {
		t.fn.serializeArray = function() {
			var e, n = [];
			return t([].slice.call(this.get(0).elements)).each(function() {
				e = t(this);
				var i = e.attr("type");
				"fieldset" != this.nodeName.toLowerCase() && !this.disabled && "submit" != i && "reset" != i && "button" != i && ("radio" != i && "checkbox" != i || this.checked) && n.push({
					name: e.attr("name"),
					value: e.val()
				})
			}),
			n
		},
		t.fn.serialize = function() {
			var t = [];
			return this.serializeArray().forEach(function(e) {
				t.push(encodeURIComponent(e.name) + "=" + encodeURIComponent(e.value))
			}),
			t.join("&")
		},
		t.fn.submit = function(e) {
			if (e) this.bind("submit", e);
			else if (this.length) {
				var n = t.Event("submit");
				this.eq(0).trigger(n),
				n.isDefaultPrevented() || this.get(0).submit()
			}
			return this
		}
	} (i),
	function(t) {
		"__proto__" in {} || t.extend(t.zepto, {
			Z: function(e, n) {
				return e = e || [],
				t.extend(e, t.fn),
				e.selector = n || "",
				e.__Z = !0,
				e
			},
			isZ: function(e) {
				return "array" === t.type(e) && "__Z" in e
			}
		});
		try {
			getComputedStyle(void 0)
		} catch(e) {
			var n = getComputedStyle;
			window.getComputedStyle = function(t) {
				try {
					return n(t)
				} catch(e) {
					return null
				}
			}
		}
	} (i),
	n.exports = i
}),
define("dist/js/touch", ["./zepto"],
function(t, e, n) {
	var i = t("./zepto");
	n.exports = i,
	function(t) {
		function e(t, e, n, i) {
			return Math.abs(t - e) >= Math.abs(n - i) ? t - e > 0 ? "Left": "Right": n - i > 0 ? "Up": "Down"
		}
		function n() {
			u = null,
			h.last && (h.el.trigger("longTap"), h = {})
		}
		function i() {
			u && clearTimeout(u),
			u = null
		}
		function a() {
			s && clearTimeout(s),
			l && clearTimeout(l),
			c && clearTimeout(c),
			u && clearTimeout(u),
			s = l = c = u = null,
			h = {}
		}
		function o(t) {
			return ("touch" == t.pointerType || t.pointerType == t.MSPOINTER_TYPE_TOUCH) && t.isPrimary
		}
		function r(t, e) {
			return t.type == "pointer" + e || t.type.toLowerCase() == "mspointer" + e
		}
		var s, l, c, u, d, h = {},
		f = 750;
		t(document).ready(function() {
			var p, g, m, v, x = 0,
			b = 0;
			"MSGesture" in window && (d = new MSGesture, d.target = document.body),
			t(document).bind("MSGestureEnd",
			function(t) {
				var e = t.velocityX > 1 ? "Right": -1 > t.velocityX ? "Left": t.velocityY > 1 ? "Down": -1 > t.velocityY ? "Up": null;
				e && (h.el.trigger("swipe"), h.el.trigger("swipe" + e))
			}).on("touchstart MSPointerDown pointerdown",
			function(e) { (!(v = r(e, "down")) || o(e)) && (m = v ? e: e.touches[0], e.touches && 1 === e.touches.length && h.x2 && (h.x2 = void 0, h.y2 = void 0), p = Date.now(), g = p - (h.last || p), h.el = t("tagName" in m.target ? m.target: m.target.parentNode), s && clearTimeout(s), h.x1 = m.pageX, h.y1 = m.pageY, g > 0 && 250 >= g && (h.isDoubleTap = !0), h.last = p, u = setTimeout(n, f), d && v && d.addPointer(e.pointerId))
			}).on("touchmove MSPointerMove pointermove",
			function(t) { (!(v = r(t, "move")) || o(t)) && (m = v ? t: t.touches[0], i(), h.x2 = m.pageX, h.y2 = m.pageY, x += Math.abs(h.x1 - h.x2), b += Math.abs(h.y1 - h.y2))
			}).on("touchend MSPointerUp pointerup",
			function(n) { (!(v = r(n, "up")) || o(n)) && (i(), h.x2 && Math.abs(h.x1 - h.x2) > 30 || h.y2 && Math.abs(h.y1 - h.y2) > 30 ? c = setTimeout(function() {
					h.el.trigger("swipe"),
					h.el.trigger("swipe" + e(h.x1, h.x2, h.y1, h.y2)),
					h = {}
				},
				0) : "last" in h && (30 > x && 30 > b ? l = setTimeout(function() {
					var e = t.Event("tap");
					e.cancelTouch = a,
					h.el.trigger(e),
					h.isDoubleTap ? (h.el && h.el.trigger("doubleTap"), h = {}) : s = setTimeout(function() {
						s = null,
						h.el && h.el.trigger("singleTap"),
						h = {}
					},
					250)
				},
				0) : h = {}), x = b = 0)
			}).on("touchcancel MSPointerCancel pointercancel", a),
			t(window).on("scroll", a)
		}),
		["swipe", "swipeLeft", "swipeRight", "swipeUp", "swipeDown", "doubleTap", "tap", "singleTap", "longTap"].forEach(function(e) {
			t.fn[e] = function(t) {
				return this.on(e, t)
			}
		})
	} (i)
}),
define("dist/js/global", ["./zepto"],
function __global(t) {
	var e = t("./zepto"),
	n = function(t) {
		"no" != e(t.currentTarget).attr("data-event") && t.preventDefault(),
		t.stopPropagation();
		var n = e(t.currentTarget),
		i = n.attr("data-action") || "",
		a = /^([a-zA-Z0-9_]+):\/\/([a-zA-Z0-9_]+)$/,
		o = a.exec(i),
		r = null,
		s = null,
		l = {
			node: n,
			e_node: t,
			_node: t.currentTarget
		};
		o && (r = o[1], s = o[2], r in e && s in e[r] && e[r][s].call(null, l))
	},
	i = {
		_click: "ontouchstart" in window ? "tap": "click",
		_events: {},
		_windowHeight: e(window).height(),
		_windowWidth: e(window).width(),
		_rotateNode: e(".p-ct"),
		_isMotion: !!window.DeviceMotionEvent,
		_elementStyle: document.createElement("div").style,
		_UC: RegExp("Android").test(navigator.userAgent) && RegExp("UC").test(navigator.userAgent) ? !0 : !1,
		_weixin: RegExp("MicroMessenger").test(navigator.userAgent) ? !0 : !1,
		_iPhoen: RegExp("iPhone").test(navigator.userAgent) || RegExp("iPod").test(navigator.userAgent) || RegExp("iPad").test(navigator.userAgent) ? !0 : !1,
		_Android: RegExp("Android").test(navigator.userAgent) ? !0 : !1,
		_IsPC: function() {
			for (var t = navigator.userAgent,
			e = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"], n = !0, i = 0; e.length > i; i++) if (t.indexOf(e[i]) > 0) {
				n = !1;
				break
			}
			return n
		},
		_isOwnEmpty: function(t) {
			for (var e in t) if (t.hasOwnProperty(e)) return ! 1;
			return ! 0
		},
		_vendor: function() {
			for (var t, e = ["t", "webkitT", "MozT", "msT", "OT"], n = 0, i = e.length; i > n; n++) if (t = e[n] + "ransform", t in this._elementStyle) return e[n].substr(0, e[n].length - 1);
			return ! 1
		},
		_prefixStyle: function(t) {
			return this._vendor() === !1 ? !1 : "" === this._vendor() ? t: this._vendor() + t.charAt(0).toUpperCase() + t.substr(1)
		},
		_hasPerspective: function() {
			var t = this._prefixStyle("perspective") in this._elementStyle;
			return t && "webkitPerspective" in this._elementStyle && this._injectStyles("@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}",
			function(e) {
				t = 9 === e.offsetLeft && 3 === e.offsetHeight
			}),
			!!t
		},
		_injectStyles: function(t, e, n, i) {
			var a, o, r, s, l = document.createElement("div"),
			c = document.body,
			u = c || document.createElement("body"),
			d = "modernizr";
			if (parseInt(n, 10)) for (; n--;) r = document.createElement("div"),
			r.id = i ? i[n] : d + (n + 1),
			l.appendChild(r);
			return a = ["&#173;", '<style id="s', d, '">', t, "</style>"].join(""),
			l.id = d,
			(c ? l: u).innerHTML += a,
			u.appendChild(l),
			c || (u.style.background = "", u.style.overflow = "hidden", s = docElement.style.overflow, docElement.style.overflow = "hidden", docElement.appendChild(u)),
			o = e(l, t),
			c ? l.parentNode.removeChild(l) : (u.parentNode.removeChild(u), docElement.style.overflow = s),
			!!o
		},
		_translateZ: function() {
			return this._hasPerspective ? " translateZ(0)": ""
		},
		_handleEvent: function(t) {
			if (this._events[t]) {
				var e = 0,
				n = this._events[t].length;
				if (n) for (; n > e; e++) this._events[t][e].apply(this, [].slice.call(arguments, 1))
			}
		},
		_on: function(t, e) {
			this._events[t] || (this._events[t] = []),
			this._events[t].push(e)
		},
		execHandler: function(t) {
			if (t && t instanceof Object) {
				var e = t.callback || null,
				n = t.opts || [],
				i = t.context || null,
				a = t.delay || -1;
				e && e instanceof Function && ("number" == typeof a && a >= 0 ? setTimeout(function() {
					e.call(i, n)
				},
				a) : e.call(i, n))
			}
		},
		execAfterMergerHandler: function(t, n) {
			t && t instanceof Object && (t.opts || [], t.opts = e.extend(t.opts, n)),
			this.execHandler(t)
		},
		_scrollStop: function() {
			e("body").addClass("f-ofh"),
			e(window).on("touchmove", this._scrollControl),
			e(window).on("scroll", this._scrollControl)
		},
		_scrollStart: function() {
			e("body").removeClass("f-ofh"),
			e(window).off("touchmove"),
			e(window).off("scroll")
		},
		_scrollControl: function(t) {
			return t.preventDefault(),
			!1
		},
		setActionHook: function() {
			e("body").on(i._click, "[data-action]", n)
		},
		injectAction: function(t) {
			e.extend(e.Action, t)
		},
		loadingPageShow: function(t) {
			t.length >= 1 && t.show()
		},
		loadingPageHide: function(t) {
			t.length >= 1 && t.hide()
		},
		refresh: function() {
			this._windowHeight = e(window).height(),
			this._windowWidth = e(window).width()
		}
	};
	return i
}),
define("dist/js/page", ["./zepto", "./global"],
function __page(t) {
	var e = t("./zepto"),
	n = t("./global"),
	i = {
		_page: e(".m-page"),
		_pageNum: e(".m-page").size(),
		_pageNow: 0,
		_pageNext: null,
		_touchStartValY: 0,
		_touchDeltaY: 0,
		_moveStart: !0,
		_movePosition: null,
		_movePosition_c: null,
		_mouseDown: !1,
		_moveFirst: !0,
		_moveInit: !1,
		_firstChange: !1,
		page_start: function() {
			i._page.on("touchstart mousedown", i.page_touch_start),
			i._page.on("touchmove mousemove", i.page_touch_move),
			i._page.on("touchend mouseup", i.page_touch_end)
		},
		page_stop: function() {
			i._page.off("touchstart mousedown"),
			i._page.off("touchmove mousemove"),
			i._page.off("touchend mouseup")
		},
		page_touch_start: function(t) {
			i._moveStart && ("touchstart" == t.type ? i._touchStartValY = window.event.touches[0].pageY: (i._touchStartValY = t.pageY || t.y, i._mouseDown = !0), i._moveInit = !0, n._handleEvent("start"))
		},
		page_touch_move: function(t) {
			if (t.preventDefault(), i._moveStart && i._moveInit) {
				var e, a = i._page.eq(i._pageNow),
				o = (parseInt(a.height()), null),
				r = !1;
				if ("touchmove" == t.type) e = window.event.touches[0].pageY,
				r = !0;
				else {
					if (!i._mouseDown) return;
					e = t.pageY || t.y,
					r = !0
				}
				o = i.page_position(t, e, a),
				i.page_translate(o),
				n._handleEvent("move")
			}
		},
		page_position: function(t, a, o) {
			function r(t) {
				var a, o, r = n._translateZ();
				i._page.removeClass("action"),
				e(t[1]).addClass("action").removeClass("f-hide"),
				i._page.not(".action").addClass("f-hide"),
				e(t[0]).removeClass("f-hide").addClass("active"),
				"up" == i._movePosition ? (a = parseInt(e(window).scrollTop()), o = a > 0 ? e(window).height() + a: e(window).height(), t[0].style[n._prefixStyle("transform")] = "translate(0," + o + "px)" + r, e(t[0]).attr("data-translate", o)) : (t[0].style[n._prefixStyle("transform")] = "translate(0,-" + Math.max(e(window).height(), e(t[0]).height()) + "px)" + r, e(t[0]).attr("data-translate", -Math.max(e(window).height(), e(t[0]).height()))),
				e(t[1]).attr("data-translate", 0),
				i._page.eq(i._pageNext).height(e(window).height())
			}
			var s, l;
			if ("undefined" != a && (i._touchDeltaY = a - i._touchStartValY), i._movePosition = a - i._touchStartValY > 0 ? "down": "up", i._movePosition != i._movePosition_c ? (i._moveFirst = !0, i._movePosition_c = i._movePosition) : i._moveFirst = !1, 0 >= i._touchDeltaY) i._pageNext = 0 == o.next(".m-page").length ? 0 : i._pageNow + 1,
			l = i._page.eq(i._pageNext)[0];
			else {
				if (0 == o.prev(".m-page").length) {
					if (!i._firstChange) return i._pageNext = null,
					i._touchDeltaY = 0,
					void 0;
					i._pageNext = i._pageNum - 1
				} else i._pageNext = i._pageNow - 1;
				l = i._page.eq(i._pageNext)[0]
			}
			return s = i._page.eq(i._pageNow)[0],
			node = [l, s],
			i._moveFirst && r(node),
			node
		},
		page_translate: function(t) {
			if (t) {
				var a, o, r, s = n._translateZ(),
				l = i._touchDeltaY;
				e(t[0]).attr("data-translate") && (a = l + parseInt(e(t[0]).attr("data-translate"))),
				t[0].style[n._prefixStyle("transform")] = "translate(0," + a + "px)" + s,
				e(t[1]).attr("data-translate") && (o = l + parseInt(e(t[1]).attr("data-translate"))),
				r = (1 - Math.abs(.2 * l / e(window).height())).toFixed(6),
				o /= 5,
				t[1].style[n._prefixStyle("transform")] = "translate(0," + o + "px)" + s + " scale(" + r + ")"
			}
		},
		page_touch_end: function() {
			i._moveInit = !1,
			i._mouseDown = !1,
			i._moveStart && (i._pageNext || 0 == i._pageNext) && (i._moveStart = !1, i._moveFirst = !0, Math.abs(i._touchDeltaY) > 10 && (i._page.eq(i._pageNext)[0].style[n._prefixStyle("transition")] = "all .3s", i._page.eq(i._pageNow)[0].style[n._prefixStyle("transition")] = "all .3s"), Math.abs(i._touchDeltaY) >= 100 ? i.page_success() : Math.abs(i._touchDeltaY) > 10 && 100 > Math.abs(i._touchDeltaY) ? i.page_fial() : i.page_fial(), n._handleEvent("end"), i._movePosition = null, i._movePosition_c = null, i._touchStartValY = 0)
		},
		page_success: function() {
			var t = n._translateZ();
			i._page.eq(i._pageNext)[0].style[n._prefixStyle("transform")] = "translate(0,0)" + t;
			var a = i._touchDeltaY > 0 ? e(window).height() / 5 : -e(window).height() / 5,
			o = .8;
			i._page.eq(i._pageNow)[0].style[n._prefixStyle("transform")] = "translate(0," + a + "px)" + t + " scale(" + o + ")",
			n._handleEvent("success")
		},
		page_fial: function() {
			var t = n._translateZ();
			return i._pageNext || 0 == i._pageNext ? (i._page.eq(i._pageNext)[0].style[n._prefixStyle("transform")] = "up" == i._movePosition ? "translate(0," + e(window).height() + "px)" + t: "translate(0,-" + e(window).height() + "px)" + t, i._page.eq(i._pageNow)[0].style[n._prefixStyle("transform")] = "translate(0,0)" + t + " scale(1)", n._handleEvent("fial"), void 0) : (i._moveStart = !0, i._moveFirst = !0, void 0)
		},
		height_auto: function(t, n) {
			n = n ? n: e(window).height(),
			t.children(".page-con").css("height", n)
		}
	};
	return e(function() {
		e(window).on("resize",
		function() {})
	}),
	i
}),
define("dist/js/media", ["./zepto", "./global"],
function __media(t) {
	var e = t("./zepto"),
	n = t("./global"),
	i = {
		_audioNode: e(".u-audio"),
		_audio: null,
		audio_init: function() {
			if (! (0 >= i._audioNode.length)) {
				var t = {
					loop: !0,
					preload: "auto",
					src: this._audioNode.attr("data-src")
				};
				this._audio = new Audio;
				for (var e in t) t.hasOwnProperty(e) && e in this._audio && (this._audio[e] = t[e]);
				this._audio.load()
			}
		},
		audio_addEvent: function() {
			function t(t, e, n) {
				e ? t.text("打开") : t.text("关闭"),
				n && clearTimeout(n),
				t.removeClass("z-move z-hide"),
				n = setTimeout(function() {
					t.addClass("z-move").addClass("z-hide")
				},
				1e3)
			}
			if (! (0 >= this._audioNode.length)) {
				var i = this._audioNode.find(".txt_audio"),
				a = null;
				e(this._audio).on("play",
				function() {
					t(i, !0, a),
					n._handleEvent("audio_play")
				}),
				e(this._audio).on("pause",
				function() {
					t(i, !1, a),
					n._handleEvent("audio_pause")
				})
			}
		},
		media_init: function() {
			this.audio_init(),
			this.audio_addEvent()
		}
	};
	return e(window).on("load",
	function() {
		i._audioNode.find(".btn_audio").on("click",
		function() {
			i._audio.paused ? i._audio.play() : i._audio.pause()
		})
	}),
	i
}),
define("dist/js/image", ["./zepto", "./page", "./global"],
function __image(t) {
	var e = t("./zepto"),
	n = t("./page"),
	i = {
		lazy_img: function() {
			var t = e(".lazy-img");
			t.each(function() {
				var t = e(this);
				if (t.is("img")) t.attr("src", "./tpl/static/live/images/loading_large.gif");
				else {
					var n = t.css("background-position"),
					i = t.css("background-size");
					t.attr({
						"data-position": n,
						"data-size": i
					}),
					"no" == t.attr("data-bg") && t.css({
						"background-repeat": "no-repeat"
					}),
					t.css({
						"background-image": "url(./tpl/static/live/images/loading_large.gif)",
						"background-size": "120px 120px",
						"background-position": "center"
					}),
					"no" == t.attr("data-image") && t.css({
						"background-image": "none"
					})
				}
			})
		},
		lazy_start: function() {
			var t = this;
			setTimeout(function() {
				for (var n = 0; 3 > n; n++) {
					var i = e(".m-page").eq(n);
					if (0 == i.length) break;
					0 != i.find(".lazy-img").length && (t.lazy_change(i, !1), "flyCon" == i.attr("data-page-type") && t.lazy_change(e(".m-flypop"), !1))
				}
			},
			200)
		},
		lazy_bigP: function() {
			if (0 != e(".lazy-img").length) for (var t = 3; 5 >= t; t++) {
				var a = e(".m-page").eq(n._pageNow + t);
				if (0 == a.length) break;
				0 != a.find(".lazy-img").length && (i.lazy_change(a, !0), "flyCon" == a.attr("data-page-type") && i.lazy_change(e(".m-flypop"), !1))
			}
		},
		lazy_change: function(t, n) {
			if ("3d" == t.attr("data-page-type") && this.lazy_3d(t), "flyCon" == t.attr("data-page-type")) {
				var a = e(".m-flypop").find(".lazy-img");
				a.each(function() {
					var t = e(this),
					n = t.attr("data-src");
					e("<img />").on("load",
					function() {
						t.is("img") && t.attr("src", n)
					}).attr("src", n)
				})
			}
			var o = t.find(".lazy-img");
			o.each(function() {
				var t = e(this),
				a = t.attr("data-src"),
				o = t.attr("data-position"),
				r = t.attr("data-size");
				"no" != t.attr("data-bg") ? (e("<img />").on("load",
				function() {
					if (t.is("img") ? t.attr("src", a) : t.css({
						"background-image": "url(" + a + ")",
						"background-position": o,
						"background-size": r
					}), n) for (var s = 0; e(".m-page").size() > s; s++) {
						var l = e(".m-page").eq(s);
						0 != e(".m-page").find(".lazy-img").length && i.lazy_change(l, !0)
					}
				}).attr("src", a), t.removeClass("lazy-img").addClass("lazy-finish")) : "yes" == t.attr("data-auto") && t.css("background", "none")
			})
		},
		lazy_load: function() {
			var t = e(".lazy-img.load");
			t.each(function() {
				var t = e(this),
				n = t.attr("data-src"),
				i = t.attr("data-position"),
				a = t.attr("data-size");
				"no" != t.attr("data-bg") ? (e("<img />").on("load",
				function() {
					t.is("img") ? t.attr("src", n) : t.css({
						"background-image": "url(" + n + ")",
						"background-position": i,
						"background-size": a
					})
				}).attr("src", n), t.removeClass("lazy-img").addClass("lazy-finish")) : "yes" == t.attr("data-auto") && t.css("background", "none")
			})
		}
	};
	return e(function() {
		i.lazy_img()
	}),
	e(window).on("load",
	function() {
		i.lazy_start(),
		setTimeout(function() {
			i.lazy_load()
		},
		200)
	}),
	i
}),
define("dist/js/event", ["./zepto", "./global", "./page", "./image", "./sileFn", "./map", "./ylMap", "./media", "./form"],
function __event(t) {
	var e = t("./zepto"),
	n = t("./global"),
	i = t("./page"),
	a = t("./image"),
	o = t("./sileFn"),
	r = t("./media");
	haddle_envent_fn = function() {
		n._on("start", a.lazy_bigP),
		n._on("fial",
		function() {
			setTimeout(function() {
				i._page.eq(i._pageNow).attr("data-translate", ""),
				i._page.eq(i._pageNow)[0].style[n._prefixStyle("transform")] = "",
				i._page.eq(i._pageNow)[0].style[n._prefixStyle("transition")] = "",
				i._page.eq(i._pageNext)[0].style[n._prefixStyle("transform")] = "",
				i._page.eq(i._pageNext)[0].style[n._prefixStyle("transition")] = "",
				i._page.eq(i._pageNext).removeClass("active").addClass("f-hide"),
				i._moveStart = !0,
				i._moveFirst = !0,
				i._pageNext = null,
				i._touchDeltaY = 0
			},
			300)
		}),
		n._on("success",
		function() {
			0 == i._pageNext && i._pageNow == i._pageNum - 1 && (i._firstChange = !0),
			0 != i._page.eq(i._pageNext).next(".m-page").length && o.lightapp_intro_hide(!0),
			setTimeout(function() {
				o.Txt_init(i._page.eq(i._pageNow)),
				i._pageNext == i._pageNum - 1 ? e(".u-arrow").addClass("f-hide") : e(".u-arrow").removeClass("f-hide"),
				i._page.eq(i._pageNow).addClass("f-hide"),
				i._page.eq(i._pageNow).attr("data-translate", ""),
				i._page.eq(i._pageNow)[0].style[n._prefixStyle("transform")] = "",
				i._page.eq(i._pageNow)[0].style[n._prefixStyle("transition")] = "",
				i._page.eq(i._pageNext)[0].style[n._prefixStyle("transform")] = "",
				i._page.eq(i._pageNext)[0].style[n._prefixStyle("transition")] = "",
				e(".p-ct").removeClass("fixed"),
				i._page.eq(i._pageNext).removeClass("active"),
				i._page.eq(i._pageNext).removeClass("fixed"),
				i._pageNow = i._pageNext,
				i._moveStart = !0,
				i._moveFirst = !0,
				i._pageNext = null,
				i._page.eq(i._pageNow).removeClass("fixed"),
				i._page.eq(i._pageNow).attr("data-translate", ""),
				i._touchDeltaY = 0,
				setTimeout(function() {
					i._page.eq(i._pageNow).hasClass("z-animate") || i._page.eq(i._pageNow).addClass("z-animate")
				},
				20),
				e(".j-detail").removeClass("z-show"),
				e(".txt-arrow").removeClass("z-toggle"),
				e("video").each(function() {
					this.paused || this.pause()
				}),
				o.Txt_init(i._page.eq(i._pageNow)),
				0 == i._page.eq(i._pageNow).next(".m-page").length && (o.lightapp_intro_show(), o.lightapp_intro())
			},
			300);
			var t = i._page.eq(i._pageNow).attr("data-statics");
			o.ajaxTongji(t)
		}),
		n._on("audio_play",
		function() {
			e.fn.coffee.start(),
			e(".coffee-steam-box").show(500)
		}),
		n._on("audio_pause",
		function() {
			e.fn.coffee.stop(),
			e(".coffee-steam-box").hide(500)
		}),
		n._on("video_open",
		function() {
			var t = r._audio;
			r._audioNode.addClass("z-low"),
			e(".u-arrow").addClass("f-hide"),
			e(document.body).css("height", e(window).height()),
			t && t.pause(),
			i.page_stop()
		}),
		n._on("video_close",
		function() {
			var t = r._audio;
			r._audioNode.removeClass("z-low"),
			e(".u-arrow").removeClass("f-hide"),
			e(document.body).css("height", "100%"),
			t && t.play(),
			i.page_start()
		})
	},
	e(function() {
		haddle_envent_fn()
	})
}),
define("dist/js/sileFn", ["./zepto", "./global", "./page", "./map", "./ylMap", "./media", "./form"],
function __sigeFn(t) {
	var e = t("./zepto"),
	n = t("./global"),
	i = t("./page"),
	a = t("./map"),
	o = t("./form"),
	r = {
		mapCreate: function() {
			if (! (0 >= ".j-map".length)) {
				var t = e(".j-map"),
				i = {
					fnOpen: n._scrollStop,
					fnClose: a.mapSave
				};
				a.mapAddEventHandler(t, "click", a.mapShow, i)
			}
		},
		Txt_init: function(t) {
			0 >= t.find(".j-txt").length || 0 >= t.find(".j-txt").find(".j-detail p").length || t.find(".j-txt").each(function() {
				var t = e(this).find(".j-detail"),
				i = e(this).find(".j-title"),
				a = i.find(".txt-arrow"),
				o = t.find("p"),
				r = parseInt(i.height()),
				s = parseInt(o.height()),
				l = s + r;
				0 >= o.length || (e(this).parents(".m-page").hasClass("m-smallTxt") && (0 == e(this).parents(".smallTxt-bd").index() ? t.css("top", r) : t.css("bottom", r)), t.attr("data-height", s), e(this).attr("data-height-init", r), e(this).attr("data-height-extand", l), o[0].style[n._prefixStyle("transform")] = "translate(0,-" + s + "px)", e(this.parentNode).hasClass("z-left") && (o[0].style[n._prefixStyle("transform")] = "translate(0," + s + "px)"), t.css("height", "0"), a.removeClass("z-toggle"), e(this).css("height", r))
			})
		},
		bigTxt_extand: function() {
			e("body").on("click", ".j-title",
			function() {
				if (! (0 >= e(".j-detail").length)) {
					var t = e(this.parentNode).find(".j-detail");
					e(".j-detail").removeClass("action"),
					t.addClass("action"),
					e(this).hasClass("smallTxt-arrow") && (e(".smallTxt-bd").removeClass("action"), t.parent().addClass("action")),
					t.hasClass("z-show") ? (t.removeClass("z-show"), t.css("height", 0), e(this.parentNode).css("height", parseInt(e(this.parentNode).attr("data-height-init")))) : (t.addClass("z-show"), t.css("height", parseInt(t.attr("data-height"))), e(this.parentNode).css("height", parseInt(e(this.parentNode).attr("data-height-extand")))),
					e(".j-detail").not(".action").removeClass("z-show"),
					e(".txt-arrow").removeClass("z-toggle"),
					t.hasClass("z-show") ? e(this).find(".txt-arrow").addClass("z-toggle") : e(this).find(".txt-arrow").removeClass("z-toggle")
				}
			})
		},
		Txt_back: function() {
			e("body").on("click", ".m-page",
			function(t) {
				t.stopPropagation();
				var n = e(t.target),
				i = n.parents(".m-page"),
				a = 0 == n.parents(".j-txtWrap").length ? n: n.parents(".j-txtWrap");
				if (! (0 >= i.find(".j-txt").find(".j-detail p").length || 0 >= i.find(".j-txt").length || n.parents(".j-txt").length >= 1 || n.hasClass("bigTxt-btn") || n.parents(".bigTxt-btn").length >= 1)) {
					var o = a.find(".j-detail");
					e(".j-detail").removeClass("action"),
					o.addClass("action"),
					e(".j-detail").not(".action").removeClass("z-show"),
					a.each(function() {
						var t = e(this).find(".j-detail"),
						n = e(this).find(".txt-arrow"),
						i = e(this).find(".j-txt");
						t.hasClass("z-show") ? (t.removeClass("z-show"), t.css("height", 0), i.css("height", parseInt(i.attr("data-height-init")))) : (t.addClass("z-show"), t.css("height", parseInt(t.attr("data-height"))), i.css("height", parseInt(i.attr("data-height-extand")))),
						t.hasClass("z-show") ? n.addClass("z-toggle") : n.removeClass("z-toggle")
					})
				}
			})
		},
		input_form: function() {
			e("body").on("click", ".book-bd .bd-form .btn",
			function() {
				var t = e(this).attr("data-submit");
				if ("true" != t) {
					var a = e(window).height();
					e(document.body).css("height", a),
					i.page_stop(),
					n._scrollStart(),
					i._page.eq(i._pageNow).css("z-index", 15),
					e(".book-bg").removeClass("f-hide"),
					e(".book-form").removeClass("f-hide"),
					setTimeout(function() {
						e(".book-form").addClass("z-show"),
						e(".book-bg").addClass("z-show")
					},
					50),
					e(".book-bg").off("click"),
					e(".book-bg").on("click",
					function(t) {
						t.stopPropagation();
						var a = e(t.target);
						a.parents(".book-form").length >= 1 && !a.hasClass("j-close-img") && 0 >= a.parents(".j-close").length || (e(".book-form").removeClass("z-show"), e(".book-bg").removeClass("z-show"), setTimeout(function() {
							e(document.body).css("height", "100%"),
							i.page_start(),
							n._scrollStop(),
							i._page.eq(i._pageNow).css("z-index", 9),
							e(".book-bg").addClass("f-hide"),
							e(".book-form").addClass("f-hide")
						},
						500))
					})
				}
			})
		},
		sex_select: function() {
			var t = e("#j-signUp").find(".sex p"),
			n = e("#j-signUp").find(".sex strong"),
			i = e("#j-signUp").find(".sex input");
			t.on("click",
			function() {
				var t = e(this).find("strong");
				n.removeClass("open"),
				t.addClass("open");
				var a = e(this).attr("data-sex");
				i.val(a)
			})
		},
		lightapp_intro_show: function() {
			e(".market-notice").removeClass("f-hide"),
			setTimeout(function() {
				e(".market-notice").addClass("show")
			},
			100)
		},
		lightapp_intro_hide: function(t) {
			return t ? (e(".market-notice").addClass("f-hide").removeClass("show"), void 0) : (e(".market-notice").removeClass("show"), setTimeout(function() {
				e(".market-notice").addClass("f-hide")
			},
			500), void 0)
		},
		lightapp_intro: function() {
			e(".market-notice").off("click"),
			e(".market-notice").on("click",
			function() {
				e(".market-page").removeClass("f-hide"),
				setTimeout(function() {
					e(".market-page").addClass("show"),
					setTimeout(function() {
						e(".market-img").addClass("show")
					},
					100),
					r.lightapp_intro_hide()
				},
				100),
				i.page_stop(),
				n._scrollStop()
			}),
			e(".market-page").off("click"),
			e(".market-page").on("click",
			function(t) {
				e(t.target).hasClass("market-page") && (e(".market-img").removeClass("show"), setTimeout(function() {
					e(".market-page").removeClass("show"),
					setTimeout(function() {
						e(".market-page").addClass("f-hide")
					},
					200)
				},
				500), r.lightapp_intro_show(), i.page_start(), n._scrollStart())
			})
		},
		ajaxTongji: function(t) {
			var n = location.search.substr(location.search.indexOf("channel=") + 8);
			n = n.match(/^\d+/),
			(!n || isNaN(n) || 0 > n) && (n = 1);
			var i = e("#activity_id").val(),
			a = "index.php?g=Wap&m=Live&a=get_list&activity_id=" + i + "&plugtype=" + t;
			e.get(a, {},
			function() {})
		},
		wxShare: function() {
			e("body").on("click", ".bigTxt-btn-wx",
			function() {
				var t = e(this).parent().find(".bigTxt-weixin");
				t.addClass("z-show"),
				i.page_stop(),
				t.on("click",
				function() {
					e(this).removeClass("z-show"),
					i.page_start(),
					e(this).off("click")
				})
			})
		},
		toggleVideo: function() {
			e(".j-video").find(".img").on("click",
			function() {
				var t = e(this).next()[0];
				0 >= t.length || t.paused && (e(t).removeClass("f-hide"), t.play(), e(this).hide())
			})
		},
		signUp_submit: function() {
			e("#j-signUp-submit").on("click",
			function(t) {
				t.preventDefault();
				var n = e(this).parents("#j-signUp"),
				i = o.signUpCheck_input(n, e(".u-note"));
				i && o.signUpCheck_submit(n, e(".u-note"))
			})
		},
		loadingPageShow: function() {
			e(".u-pageLoading").show()
		},
		loadingPageHide: function() {
			e(".u-pageLoading").hide()
		}
	};
	return e(function() {
		r.bigTxt_extand(),
		r.Txt_back(),
		r.input_form(),
		r.sex_select(),
		r.lightapp_intro(),
		r.wxShare(),
		r.mapCreate(),
		r.toggleVideo(),
		r.signUp_submit(),
		r.Txt_init(i._page.eq(i._pageNow))
	}),
	r
}),
define("dist/js/map", ["./zepto", "./ylMap", "./global", "./page", "./media"],
function __map(require, exports, module) {
	var $ = require("./zepto");
	$ = require("./ylMap");
	var global = require("./global"),
	page = require("./page"),
	media = require("./media"),
	__map = {
		_map: $(".ylmap"),
		_mapValue: null,
		_mapIndex: null,
		mapAddEventHandler: function(t, e, n, i) {
			var a = n;
			global._isOwnEmpty(i) || (a = function() {
				n.call(this, i)
			}),
			t.each(function() {
				$(this).on(e, a)
			})
		},
		mapShow: function(option) {
			var str_data = $(this).attr("data-detal");
			option.detal = "" != str_data ? eval("(" + str_data + ")") : "",
			option.latitude = $(this).attr("data-latitude"),
			option.longitude = $(this).attr("data-longitude");
			var detal = option.detal,
			latitude = option.latitude,
			longitude = option.longitude,
			fnOpen = option.fnOpen,
			fnClose = option.fnClose;
			global._scrollStop(),
			__map._map.addClass("show"),
			$(document.body).animate({
				scrollTop: 0
			},
			0),
			$(this).attr("data-mapIndex") != __map._mapIndex ? (__map._map.html($('<div class="bk"><span class="css_sprite01 s-bg-map-logo"></span></div>')), __map._mapValue = !1, __map._mapIndex = $(this).attr("data-mapIndex")) : __map._mapValue = !0,
			setTimeout(function() {
				__map._map.find("div").length >= 1 && (__map._map.addClass("mapOpen"), page.page_stop(), global._scrollStop(), media._audioNode.addClass("z-low"), page._page.eq(page._pageNow).css("z-index", 15), setTimeout(function() {
					__map._mapValue || __map.addMap(detal, latitude, longitude, fnOpen, fnClose)
				},
				500))
			},
			100)
		},
		mapSave: function() {
			function t() {
				__map._map.removeClass("show"),
				$(window).off("webkitTransitionEnd transitionend")
			}
			$(window).on("webkitTransitionEnd transitionend", t),
			page && page.page_start(),
			global._scrollStart(),
			__map._map.removeClass("mapOpen"),
			media && media._audioNode.removeClass("z-low"),
			__map._mapValue || (__map._mapValue = !0)
		},
		addMap: function(t, e, n, i, a) {
			var t = t,
			e = Number(e),
			n = Number(n),
			i = "function" == typeof i ? i: "",
			a = "function" == typeof a ? a: "",
			o = {
				sign_name: "",
				contact_tel: "",
				address: "天安门"
			};
			t = global._isOwnEmpty(t) ? o: t,
			e = e ? e: 39.915,
			n = n ? n: 116.404,
			__map._map.ylmap({
				detal: t,
				latitude: e,
				longitude: n,
				fnOpen: i,
				fnClose: a
			})
		}
	};
	return __map
}),
define("dist/js/ylMap", ["./zepto"],
function(t, e, n) {
	var i = t("./zepto");
	n.exports = i,
	function(t) {
		t.fn.ylmap = function(e) {
			t.fn.ylmap.defaults = {
				detal: {
					sign_name: "TXjiang",
					contact_tel: 18624443174,
					address: "天安门"
				},
				latitude: 39.915,
				longitude: 116.404,
				fnOpen: null,
				fnClose: null
			};
			var n = t.extend({},
			t.fn.ylmap.defaults, e);
			return this.each(function() {
				function e() {
					if (0 >= t(".BDS").length) {
						var e = document.createElement("script");
						e.src = "http://api.map.baidu.com/api?v=1.4&callback=mapInit",
						e.className += "BDS",
						document.head.appendChild(e)
					} else b();
					if (0 >= t(".BDC").length) {
						var n = document.createElement("style");
						n.type = "text/css",
						n.className += "BDC";
						var a = i();
						a ? (mapScale = 1, phoneScale = 1) : mapScale = phoneScale > 1 ? 1 : 1 / phoneScale,
						t(window).height();
						var o = ".ylmap.open,.ylmap.mapOpen {height:100%;width:100%;background:#fff;}.ylmap img {max-width:initial!important;}.ylmap .tit { position:absolute; left:0; bottom:0; height:70px; width:100%; overflow: hidden; background:rgba(0,0,0,0.5); }.ylmap .tit p { margin-right:100px; }.ylmap .tit p a { position:relative; display:block; font-size:24px; color:#fff; height:70px; line-height:70px; padding-left:70px; }.ylmap .tit p a span { position:absolute; left:15px; top:15px; display:inline-block; width:40px;height:40px; }.ylmap .tit .close_map { display:none; position: absolute; bottom: 15px; right: 20px; width: 40px; height: 40px; margin-right:0; cursor:pointer; background-position: -100px -73px; }.ylmap .map_close_btn{position:absolute;top:10px;left:10px;display:none;width:80px;box-shadow:0 0 2px rgba(0,0,0,0.6) inset, 0 0 2px rgba(0,0,0,0.6);height:80px;border-radius:80%;color:#fff;background:rgba(230,45,36,0.8);text-align:center;line-height:80px;font-size:26px; font-weight:bold;cursor:pointer;}.ylmap.open .map_close_btn{display:block;}.ylmap.mapOpen .map_close_btn{display:block;}#BDMap {transform:scale(" + mapScale + ");-webkit-transform:scale(" + mapScale + ");}" + "#BDMap {width:100%;height:100%;}" + "#BDMap img{width:auto;height:auto;}" + ".ylmap.open .transitBtn{display:block;}" + ".ylmap.mapOpen .transitBtn{display:block;}" + ".transitBtn {display:none;position:absolute;z-index:3000;}" + ".transitBtn a{display:block;width:80px;box-shadow:0 0 2px rgba(0,0,0,0.6) inset, 0 0 2px rgba(0,0,0,0.6);height:80px;border-radius:80%;color:#fff;background:rgba(230,45,36,0.8);text-align:center;line-height:80px;font-size:24px; font-weight:bold}" + ".transitBtn.close {top:10px;right:10px;}" + ".transitBtn.bus {top:10px;right:110px;}" + ".transitBtn.car {top:110px;right:10px;}" + ".transitBtn.bus a{background:rgba(28,237,235,0.8);}" + ".transitBtn.car a{background:rgba(89,237,37,0.8);}" + "#transit_result{display:none;position:absolute;top:0;left:0;width:100%;height:100%;z-index:1000;overflow-y:scroll;}" + "#transit_result.open{display:block;}" + "#transit_result h1{font-size:26px!important;}" + "#transit_result div[onclick^='Instance']{background:none!important;}" + "#transit_result span{display:inline-block;font-size:20px;padding:0 5px;}" + "#transit_result table {font-size:20px!important;}" + "#transit_result table td{padding:5px 10px!important;line-height:150%!important;}" + ".infoWindow p{margin-bottom:10px;}" + ".infoWindow .window_btn .open_navigate{display:inline-block;padding:2px 6px; margin-right:10px;border:1px solid #ccc;border-radius:6px;text-align:center;cursor:pointer;}" + ".anchorBL{display:none!important;}";
						n.innerHTML = o,
						document.head.appendChild(n)
					}
				}
				function i() {
					for (var t = navigator.userAgent,
					e = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"], n = !0, i = 0; e.length > i; i++) if (t.indexOf(e[i]) > 0) {
						n = !1;
						break
					}
					return n
				}
				var a, o, r, s, l = t(this),
				c = n.detal,
				u = n.latitude,
				d = n.longitude,
				h = n.fnClose,
				f = (n.fnOpen, l.hasClass("bigOpen")),
				p = null,
				g = null,
				m = null,
				v = null,
				x = t('<div id="BDMap" class="BDMap"></div>');
				l.append(x),
				l.append(t('<div id="transit_result"></div>')),
				l.append(t('<div class="tit"><p><a href="javascript:void(0)"><span class="css_sprite01"></span>' + c.address + "</a></p></div>")),
				l.append(t('<p class="map_close_btn">退出</p>')),
				l.length > 0 && l.height(),
				f && l.find(".map_close_btn").css("display", "block"),
				t("#transit_result").length > 0 && "" != t("#transit_result").html() && t(".transitBtn").removeClass("hide");
				var b = function() {
					l.size() > 0 && (o = new BMap.Map(x.attr("id")), r = new BMap.Point(d, u), s = new BMap.Marker(r), o.enableScrollWheelZoom(), o.enableInertialDragging(), o.centerAndZoom(r, 15), o.addOverlay(s), y(), s.addEventListener("click",
					function() {
						y()
					}), o.addEventListener("click",
					function() {
						return ! 1
					}), o.addEventListener("zoomend",
					function() {
						var t = o.getZoom();
						o.centerAndZoom(r, t)
					}))
				},
				y = function() {
					w(s, c)
				},
				w = function(e, n) {
					var i = t('<div class="infoWindow"></div>');
					n.contact_tel !== void 0 && i.append('<p class="tel"><a href="tel:' + n.contact_tel + '">' + n.contact_tel + "</a></p>"),
					i.append('<p class="address">' + n.address + "</p>"),
					i.append('<div class="window_btn"><span class="open_navigate open_bus" onclick="open_navigate(this)">公交</span><span class="open_navigate open_car" onclick="open_navigate(this)">自驾</span><span class="State"></span></div>');
					var a = {
						width: 0,
						height: 0,
						title: " "
					},
					r = new BMap.InfoWindow(i[0], a);
					e.openInfoWindow(r, o.getCenter())
				};
				open_navigate = function(e) {
					p = t(e).hasClass("open_bus") ? "bus": "car",
					navigate(),
					t(".infoWindow").find("span.State").html("正在定位您的位置！")
				},
				navigate = function() {
					window.navigator.geolocation ? window.navigator.geolocation.getCurrentPosition(handleSuccess, handleError, {
						timeout: 1e4
					}) : alert("sorry！您的设备不支持定位功能")
				},
				handleError = function(e) {
					var n;
					switch (e.code) {
					case e.TIMEOUT:
						n = "获取超时!请稍后重试!";
						break;
					case e.POSITION_UNAVAILABLE:
						n = "无法获取当前位置!";
						break;
					case e.PERMISSION_DENIED:
						n = "您已拒绝共享地理位置!";
						break;
					case e.UNKNOWN_ERROR:
						n = "无法获取当前位置!"
					}
					t(".infoWindow").find("span.State").length > 0 ? t(".infoWindow").find("span.State").html(n) : alert(n)
				},
				handleSuccess = function(e) {
					var n = e.coords,
					i = n.latitude,
					a = n.longitude;
					v = new BMap.Point(a, i),
					t(".infoWindow").find("span.State").html("获取信息成功，正在加载中！"),
					"bus" == p ? bus_transit() : self_transit(),
					f ? x.parent().addClass("mapOpen") : x.parent().addClass("open")
				},
				t(".map_close_btn").on("click",
				function() {
					l.removeClass("mapOpen open"),
					h && h()
				}),
				bus_transit = function() {
					if (g && g.clearResults(), m && m.clearResults(), !v) return alert("抱歉：定位失败！"),
					void 0;
					t(".fn-audio").hide(),
					"function" == typeof loadingPageShow && loadingPageShow(),
					t(".infoWindow").find("span.State").html("正在绘制出导航路线");
					var e = t("#transit_result") || t('<div id="transit_result"></div>');
					e.appendTo(l),
					g = new BMap.TransitRoute(o, {
						renderOptions: {
							map: o,
							panel: "transit_result",
							autoViewport: !0
						},
						onSearchComplete: searchComplete
					}),
					g.search(v, r)
				},
				self_transit = function() {
					if (g && g.clearResults(), m && m.clearResults(), !v) return alert("抱歉：定位失败！"),
					void 0;
					t(".fn-audio").hide(),
					"function" == typeof loadingPageShow && loadingPageShow(),
					t(".infoWindow").find("span.State").html("正在绘制出导航路线");
					var e = t("#transit_result") || t('<div id="transit_result"></div>');
					e.appendTo(l),
					m = new BMap.DrivingRoute(o, {
						renderOptions: {
							map: o,
							panel: e.attr("id"),
							autoViewport: !0
						},
						onSearchComplete: searchComplete
					}),
					m.search(v, r)
				},
				searchComplete = function(e) {
					function n() {
						var t;
						t = window.event.touches[0].pageY,
						a = t
					}
					function i(e) {
						e.stopPropagation(),
						e.preventDefault();
						var n;
						n = window.event.touches[0].pageY;
						var i = t(this).scrollTop();
						t(this).scrollTop(i + a - n),
						a = n
					}
					0 == e.getNumPlans() ? (alert("非常抱歉,未搜索到可用路线"), o.reset(), o.centerAndZoom(r, 15), y(), t("#transit_result").removeClass("open").hide(), t(".transitBtn").hide()) : (t("#transit_result").addClass("open"), t(".infoWindow").find("span.State").html(""), !t(".transitBtn").length > 0 && (t("#transit_result").after(t('<p class="transitBtn close" onclick="transit_result_close()"><a href="javascript:void(0)">关闭</a></p>')), t("#transit_result").after(t('<p class="transitBtn bus" onclick="bus_transit()"><a href="javascript:void(0)">公交</a></p>')), t("#transit_result").after(t('<p class="transitBtn car" onclick="self_transit()"><a href="javascript:void(0)">自驾</a></p>'))), l.find(".close_map").show(), t("#transit_result").addClass("open"), t(".transitBtn").show(), t("#transit_result").on("touchstart", n), t("#transit_result").on("touchmove", i)),
					"function" == typeof loadingPageHide && loadingPageHide(),
					f || l.css({
						position: "fixed",
						top: "0",
						left: "0",
						height: "100%"
					}),
					t("#transit_result").hasClass("open") ? t(".close").find("a").html("关闭") : t(".close").find("a").html("打开")
				},
				transit_result_close = function() {
					t("#transit_result").hasClass("open") ? (t("#transit_result").removeClass("open"), t(".close").find("a").html("打开")) : (t("#transit_result").addClass("open"), t(".close").find("a").html("关闭"))
				},
				window.mapInit = b,
				e()
			})
		}
	} (i)
}),
define("dist/js/form", ["./zepto", "./global", "./page"],
function __form(t) {
	var e = t("./zepto"),
	n = t("./global"),
	i = t("./page"),
	a = {
		signUpCheck_input: function(t, n) {
			var i = !0,
			o = t.find("input");
			return o.each(function() {
				if ("" != this.name && "undefined" != this.name) {
					var t = this.name,
					o = e(this).attr("placeHolder"),
					r = a.regFunction(t, o),
					s = r.empty_tip,
					l = r.reg,
					c = r.reg_tip;
					if ("" == e.trim(e(this).val())) return a.showCheckMessage(n, s, !1),
					e(this).addClass("z-error"),
					i = !1,
					!1;
					if (void 0 != l && "" != l && !e(this).val().match(l)) return e(this).addClass("z-error"),
					a.showCheckMessage(n, c, !1),
					i = !1,
					!1;
					e(this).removeClass("z-error"),
					e(".u-note-error").html(""),
					n.html("")
				}
			}),
			0 == i ? !1 : !0
		},
		regFunction: function(t, n) {
			var i = "",
			a = "",
			o = "",
			r = !1;
			t.indexOf("new") > -1 ? r = !0 : n = null;
			var s = e("#activity_id").val();
			switch (t) {
			case "name":
				o = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/,
				i = "不能落下姓名哦！",
				a = "这名字太怪了！";
				break;
			case "sex":
				i = "想想，该怎么称呼您呢？",
				a = "";
				break;
			case "tel":
				o = /^1[0-9][0-9]\d{8}$/,
				i = "有个联系方式，就更好了！",
				a = "这号码,可打不通... ";
				break;
			case "email":
				o = 4111 != s ? /(^[a-z\d]+(\.[a-z\d]+)*@([\da-z](-[\da-z])?)+(\.{1,2}[a-z]+)+$)/i: /^[\u4e00-\u9fa5|a-z|A-Z|\s|\d]{1,20}$/,
				i = "都21世纪了，应该有个电子邮箱吧！",
				a = "邮箱格式有问题哦！";
				break;
			case "company":
				o = /^[\u4e00-\u9fa5|a-z|A-Z|\s|\d]{1,20}$/,
				i = "填个公司吧！",
				a = "这个公司太奇怪了！";
				break;
			case "job":
				o = /^[\u4e00-\u9fa5|a-z|A-Z|\s]{1,20}$/,
				i = "请您填个职位",
				a = "这个职位太奇怪了！";
				break;
			case "date":
				i = "给个日期吧！",
				a = "";
				break;
			case "time":
				i = "填下具体时间更好哦！",
				a = "";
				break;
			case "new1":
			case "new2":
			case "new3":
			case "new4":
			case "new5":
				o = /^.*$/,
				i = r ? "请填写" + n: "",
				a = "";
				break;
			case "age":
				o = /^([3-9])|([1-9][0-9])|([1][0-3][0-9])$/,
				i = "有个年龄就更好了！",
				a = "这年龄可不对哦！"
			}
			return {
				empty_tip: i,
				reg_tip: a,
				reg: o
			}
		},
		signUpCheck_submit: function(t, o) {
			n.loadingPageShow(e(".u-pageLoading"));
			var r = "/auto/submit/" + e("#activity_id").val();
			e.ajax({
				url: r,
				cache: !1,
				dataType: "json",
				async: !0,
				type: "POST",
				data: t.serialize(),
				success: function(t) {
					n.loadingPageHide(e(".u-pageLoading")),
					200 == t.code ? (a.showCheckMessage(e(".u-note"), e(".u-note-sucess").data("type"), !0), setTimeout(function() {
						e(".book-form").removeClass("z-show"),
						e(".book-bg").removeClass("z-show"),
						setTimeout(function() {
							e(document.body).css("height", "100%"),
							i.page_start(),
							n._scrollStop(),
							e(".book-bg").addClass("f-hide"),
							e(".book-form").addClass("f-hide")
						},
						500)
					},
					3e3), e(".book-bd .bd-form .btn").addClass("z-stop"), e(".book-bd .bd-form .btn").attr("data-submit", "true")) : 400 == t.code && a.showCheckMessage(e(".u-note"), e(".u-note-error").data("type"), !1)
				},
				error: function(t, i, r) {
					a.showCheckMessage(o, r, !1),
					setTimeout(function() {
						n.loadingPageHide(e(".u-pageLoading"))
					},
					500)
				}
			})
		},
		showCheckMessage: function(t, n, i) {
			i ? (e(".u-note-sucess").html(n), e(".u-note-sucess").addClass("on"), e(".u-note-error").removeClass("on"), setTimeout(function() {
				e(".u-note").removeClass("on")
			},
			2e3)) : (e(".u-note-error").html(n), e(".u-note-error").addClass("on"), e(".u-note-sucess").removeClass("on"), setTimeout(function() {
				e(".u-note").removeClass("on")
			},
			2e3))
		}
	};
	return a
}),
define("dist/js/plugins", ["./zepto", "./ylMusic", "./fx", "./weixin", "./Lottery", "./global", "./page", "./media", "./video"],
function __plugins(t) {
	var e = t("./zepto");
	e = t("./ylMusic"),
	e = t("./weixin");
	var n = t("./Lottery"),
	i = t("./global"),
	a = t("./page"),
	o = t("./media"),
	r = t("./video"),
	s = {
		init: function() {
			e("#coffee_flow").coffee({
				steams: ["<img src='./tpl/static/live/images/audio_widget_01@2x.png' />", "<img src='./tpl/static/live/images/audio_widget_01@2x.png' />"],
				steamHeight: 100,
				steamWidth: 44
			}),
			o.media_init(),
			r.video_init();
			var t = {};
			"" != e("#r-wx-title").val() && (t.title = e("#r-wx-title").val()),
			"" != e("#r-wx-img").val() && (t.img = e("#r-wx-img").val()),
			"" != e("#r-wx-con").val() && (t.con = e("#r-wx-con").val()),
			"" != e("#r-wx-link").val() && (t.link = e("#r-wx-link").val()),
			"" != e("#r-wx-callback").val() && (t.callback = e("#r-wx-callback").val()),
			i._weixin && e(document.body).wx(t);
			var n = e(".translate-front").data("open");
			if (1 == n) {
				var a = e("#j-mengban")[0],
				l = "./tpl/static/live/images/page_01_bg@2x.jpg",
				c = e("#r-cover").val(),
				u = "image",
				d = 640,
				h = e(window).height(),
				f = s.start_callback;
				s.cover_draw(a, l, c, u, d, h, f)
			} else s.start_callback()
		},
		cover_draw: function(t, e, i, a, o, r, s) {
			if (! (t.style.display.indexOf("none") > -1)) {
				var l = new n(t, i, a, o, r, s);
				l.init()
			}
		},
		start_callback: function() {
			var t = e(".translate-front").data("open");
			if (a.page_start(), e(document).one("touchstart",
			function() {
				o._audio.play()
			}), 1 == t) {
				if (e("#j-mengban").removeClass("z-show"), setTimeout(function() {
					e("#j-mengban").addClass("f-hide")
				},
				1500), e(".u-arrow").removeClass("f-hide"), !o._audio) return;
				o._audioNode.removeClass("f-hide"),
				o._audio.play()
			} else e("#j-mengban").removeClass("z-show").addClass("f-hide")
		}
	};
	e(window).on("load",
	function() {
		s.init()
	})
}),
define("dist/js/ylMusic", ["./zepto", "./fx"],
function(t, e, n) {
	var a = t("./zepto");
	a = t("./fx"),
	n.exports = a,
	function(t) {
		t.fn.coffee = function(e) {
			function n() {
				var e = r(8, h.steamMaxSize),
				n = o(1, h.steamsFontFamily),
				i = "#" + o(6, "0123456789ABCDEF"),
				a = r(0, 44),
				l = r( - 90, 89),
				c = s(.4, 1),
				d = t.fx.cssPrefix + "transform";
				d = d + ":rotate(" + l + "deg) scale(" + c + ");";
				var g = t('<span class="coffee-steam">' + o(1, h.steams) + "</span>"),
				m = r(0, f - h.steamWidth - e);
				m > a && (m = r(0, a)),
				g.css({
					position: "absolute",
					left: a,
					top: h.steamHeight,
					"font-size:": e + "px",
					color: i,
					"font-family": n,
					display: "block",
					opacity: 1
				}).attr("style", g.attr("style") + d).appendTo(p).animate({
					top: r(h.steamHeight / 2, 0),
					left: m,
					opacity: 0
				},
				r(h.steamFlyTime / 2, 1.2 * h.steamFlyTime), u,
				function() {
					g.remove(),
					g = null
				})
			}
			function a() {
				var t = r( - 10, 10);
				t += parseInt(p.css("left")),
				t >= 54 ? t = 54 : 34 >= t && (t = 34),
				p.animate({
					left: t
				},
				r(1e3, 3e3), u)
			}
			function o(t, e) {
				t = t || 1;
				var n = "",
				a = e.length - 1,
				o = 0;
				for (i = 0; t > i; i++) o = r(0, a - 1),
				n += e.slice(o, o + 1);
				return n
			}
			function r(t, e) {
				var n = e - t,
				i = t + Math.round(Math.random() * n);
				return parseInt(i)
			}
			function s(t, e) {
				var n = e - t,
				i = t + Math.random() * n;
				return parseFloat(i)
			}
			var l = null,
			c = null,
			u = "cubic-bezier(.09,.64,.16,.94)",
			d = t(this),
			h = t.extend({},
			t.fn.coffee.defaults, e),
			f = h.steamWidth,
			p = t('<div class="coffee-steam-box"></div>').css({
				height: h.steamHeight,
				width: h.steamWidth,
				left: 60,
				top: -50,
				position: "absolute",
				overflow: "hidden",
				"z-index": 0
			}).appendTo(d);
			return t.fn.coffee.stop = function() {
				clearInterval(l),
				clearInterval(c)
			},
			t.fn.coffee.start = function() {
				l = setInterval(function() {
					n()
				},
				r(h.steamInterval / 2, 2 * h.steamInterval)),
				c = setInterval(function() {
					a()
				},
				r(100, 1e3) + r(1e3, 3e3))
			},
			d
		},
		t.fn.coffee.defaults = {
			steams: ["jQuery", "HTML5", "HTML6", "CSS2", "CSS3", "JS", "$.fn()", "char", "short", "if", "float", "else", "type", "case", "function", "travel", "return", "array()", "empty()", "eval", "C++", "JAVA", "PHP", "JSP", ".NET", "while", "this", "$.find();", "float", "$.ajax()", "addClass", "width", "height", "Click", "each", "animate", "cookie", "bug", "Design", "Julying", "$(this)", "i++", "Chrome", "Firefox", "Firebug", "IE6", "Guitar", "Music", "攻城师", "旅行", "王子墨", "啤酒"],
			steamsFontFamily: ["Verdana", "Geneva", "Comic Sans MS", "MS Serif", "Lucida Sans Unicode", "Times New Roman", "Trebuchet MS", "Arial", "Courier New", "Georgia"],
			steamFlyTime: 5e3,
			steamInterval: 500,
			steamMaxSize: 30,
			steamHeight: 200,
			steamWidth: 300
		},
		t.fn.coffee.version = "2.0.0"
	} (a)
}),
define("dist/js/fx", ["./zepto"],
function(t, e, n) {
	var i = t("./zepto");
	n.exports = i,
	function(t, e) {
		function n(t) {
			return t.replace(/([a-z])([A-Z])/, "$1-$2").toLowerCase()
		}
		function i(t) {
			return a ? a + t: t.toLowerCase()
		}
		var a, o, r, s, l, c, u, d, h, f, p = "",
		g = {
			Webkit: "webkit",
			Moz: "",
			O: "o"
		},
		m = window.document,
		v = m.createElement("div"),
		x = /^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i,
		b = {};
		t.each(g,
		function(t, n) {
			return v.style[t + "TransitionProperty"] !== e ? (p = "-" + t.toLowerCase() + "-", a = n, !1) : e
		}),
		o = p + "transform",
		b[r = p + "transition-property"] = b[s = p + "transition-duration"] = b[c = p + "transition-delay"] = b[l = p + "transition-timing-function"] = b[u = p + "animation-name"] = b[d = p + "animation-duration"] = b[f = p + "animation-delay"] = b[h = p + "animation-timing-function"] = "",
		t.fx = {
			off: a === e && v.style.transitionProperty === e,
			speeds: {
				_default: 400,
				fast: 200,
				slow: 600
			},
			cssPrefix: p,
			transitionEnd: i("TransitionEnd"),
			animationEnd: i("AnimationEnd")
		},
		t.fn.animate = function(n, i, a, o, r) {
			return t.isFunction(i) && (o = i, a = e, i = e),
			t.isFunction(a) && (o = a, a = e),
			t.isPlainObject(i) && (a = i.easing, o = i.complete, r = i.delay, i = i.duration),
			i && (i = ("number" == typeof i ? i: t.fx.speeds[i] || t.fx.speeds._default) / 1e3),
			r && (r = parseFloat(r) / 1e3),
			this.anim(n, i, a, o, r)
		},
		t.fn.anim = function(i, a, p, g, m) {
			var v, y, w, _ = {},
			C = "",
			k = this,
			j = t.fx.transitionEnd,
			T = !1;
			if (a === e && (a = t.fx.speeds._default / 1e3), m === e && (m = 0), t.fx.off && (a = 0), "string" == typeof i) _[u] = i,
			_[d] = a + "s",
			_[f] = m + "s",
			_[h] = p || "linear",
			j = t.fx.animationEnd;
			else {
				y = [];
				for (v in i) x.test(v) ? C += v + "(" + i[v] + ") ": (_[v] = i[v], y.push(n(v)));
				C && (_[o] = C, y.push(o)),
				a > 0 && "object" == typeof i && (_[r] = y.join(", "), _[s] = a + "s", _[c] = m + "s", _[l] = p || "linear")
			}
			return w = function(n) {
				if (n !== e) {
					if (n.target !== n.currentTarget) return;
					t(n.target).unbind(j, w)
				} else t(this).unbind(j, w);
				T = !0,
				t(this).css(b),
				g && g.call(this)
			},
			a > 0 && (this.bind(j, w), setTimeout(function() {
				T || w.call(k)
			},
			1e3 * a + 25)),
			this.size() && this.get(0).clientLeft,
			this.css(_),
			0 >= a && setTimeout(function() {
				k.each(function() {
					w.call(this)
				})
			},
			0),
			this
		},
		v = null
	} (i)
}),

/*
define("dist/js/weixin", ["./zepto"],
function(t, e, n) {
	var i = t("./zepto");
	n.exports = i,
	function(t) {
		t.fn.wx = function(e) {
			function n(t, e) {
				var i = 2e3;
				e = e || 0,
				!0 === window.G_WEIXIN_READY || "WeixinJSBridge" in window ? t.apply(null, []) : i >= e && setTimeout(function() {
					n(t, e++)
				},
				15)
			}
			var i = t(this),
			a = t.extend({},
			t.fn.wx.defaults, e);
			document.addEventListener("WeixinJSBridgeReady",
			function() {
				window.G_WEIXIN_READY = !0
			},
			!1);
			var o = {
				execHandler: function(t) {
					if (t && t instanceof Object) {
						var e = t.callback || null,
						n = t.args || [],
						i = t.context || null,
						a = t.delay || -1;
						e && e instanceof Function && ("number" == typeof a && a >= 0 ? setTimeout(function() {
							e.apply(i, n)
						},
						a) : e.apply(i, n))
					}
				},
				execAfterMergerHandler: function(t, e) {
					if (t && t instanceof Object) {
						var n = t.args || [];
						t.args = e.concat(n)
					}
					this.execHandler(t)
				}
			},
			r = {
				Share: {
					weibo: function(t, e) {
						n(function() {
							WeixinJSBridge.on("menu:share:weibo",
							function() {
								WeixinJSBridge.invoke("shareWeibo", t,
								function(t) {
									o.execAfterMergerHandler(e, [t])
								})
							})
						})
					},
					timeline: function(t, e) {
						n(function() {
							WeixinJSBridge.on("menu:share:timeline",
							function() {
								WeixinJSBridge.invoke("shareTimeline", t,
								function(t) {
									o.execAfterMergerHandler(e, [t])
								})
							})
						})
					},
					message: function(t, e) {
						n(function() {
							WeixinJSBridge.on("menu:share:appmessage",
							function() {
								WeixinJSBridge.invoke("sendAppMessage", t,
								function(t) {
									o.execAfterMergerHandler(e, [t])
								})
							})
						})
					}
				},
				setToolbar: function(t, e) {
					n(function() { ! 0 === t ? WeixinJSBridge.call("showToolbar") : WeixinJSBridge.call("hideToolbar"),
						o.execAfterMergerHandler(e, [t])
					})
				},
				setOptionMenu: function(t, e) {
					n(function() { ! 0 === t ? WeixinJSBridge.call("showOptionMenu") : WeixinJSBridge.call("hideOptionMenu"),
						o.execAfterMergerHandler(e, [t])
					})
				},
				pay: function(t, e) {
					n(function() {
						var n = {
							appId: "",
							timeStamp: "",
							nonceStr: "",
							"package": "",
							signType: "",
							paySign: ""
						},
						i = e || {},
						a = null,
						r = [t];
						for (var s in n) n.hasOwnProperty(s) && (n[s] = t[s] || "", console.info(s + " = " + n[s]));
						WeixinJSBridge.invoke("getBrandWCPayRequest", n,
						function(t) {
							var e = "get_brand_wcpay_request:";
							switch (t.err_msg) {
							case e + "ok": a = i.success;
								break;
							case e + "fail": a = i.fail || i.error;
								break;
							case e + "cancel": a = i.cancel || i.error;
								break;
							default:
								a = i.error
							}
							o.execAfterMergerHandler(a, r)
						})
					})
				}
			},
			s = {
				content: a.con
			},
			l = {
				img_url: a.img,
				img_width: 180,
				img_height: 180,
				link: a.link,
				desc: a.con,
				title: a.title
			};
			return handler = {
				callback: function() {
					window.location.href = a.callback
				}
			},
			r.Share.timeline(l, handler),
			r.Share.message(l, handler),
			r.Share.weibo(s, handler),
			i
		},
		t.fn.wx.defaults = {
			title: "云来轻APP-创新作品1号，仅限内测体验",
			con: "创新1号仅限内部小伙伴们尽情体验！！",
			link: document.URL,
			img: location.protocol + "//" + location.hostname + ":" + location.port + "/tpl/static/live/images/wx_img_01@2x.jpg?time=1"
		},
		t.fn.wx.version = "1.0.0"
	} (i)
}),
*/

define("dist/js/Lottery", ["./zepto"],
function(t, e, n) {
	function i(t, e, n, i, a, o) {
		this.conNode = t,
		this.background = null,
		this.backCtx = null,
		this.mask = null,
		this.maskCtx = null,
		this.lottery = null,
		this.lotteryType = "image",
		this.cover = e || "#000",
		this.coverType = n,
		this.pixlesData = null,
		this.width = i,
		this.height = a,
		this.lastPosition = null,
		this.drawPercentCallback = o,
		this.vail = !1
	}
	var a = t("./zepto");
	i.prototype = {
		createElement: function(t, e) {
			var n = document.createElement(t);
			for (var i in e) n.setAttribute(i, e[i]);
			return n
		},
		getTransparentPercent: function(t, e, n) {
			for (var i = t.getImageData(0, 0, e, n), a = i.data, o = [], r = 0, s = a.length; s > r; r += 4) {
				var l = a[r + 3];
				128 > l && o.push(r)
			}
			return (100 * (o.length / (a.length / 4))).toFixed(2)
		},
		resizeCanvas: function(t, e, n) {
			t.width = e,
			t.height = n,
			t.getContext("2d").clearRect(0, 0, e, n)
		},
		resizeCanvas_w: function(t, e, n) {
			t.width = e,
			t.height = n,
			t.getContext("2d").clearRect(0, 0, e, n),
			this.vail ? this.drawLottery() : this.drawMask()
		},
		drawPoint: function(t, e) {
			this.maskCtx.beginPath(),
			this.maskCtx.arc(t, e, 30, 0, 2 * Math.PI),
			this.maskCtx.fill(),
			this.maskCtx.beginPath(),
			this.maskCtx.lineWidth = 60,
			this.maskCtx.lineCap = this.maskCtx.lineJoin = "round",
			this.lastPosition && this.maskCtx.moveTo(this.lastPosition[0], this.lastPosition[1]),
			this.maskCtx.lineTo(t, e),
			this.maskCtx.stroke(),
			this.lastPosition = [t, e],
			this.mask.style.zIndex = 20 == this.mask.style.zIndex ? 21 : 20
		},
		bindEvent: function() {
			var t = this,
			e = /android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()),
			n = e ? "touchstart": "mousedown",
			i = e ? "touchmove": "mousemove";
			if (e) t.conNode.addEventListener("touchmove",
			function(t) {
				a && t.preventDefault(),
				t.cancelable ? t.preventDefault() : window.event.returnValue = !1
			},
			!1),
			t.conNode.addEventListener("touchend",
			function() {
				a = !1;
				var e = t.getTransparentPercent(t.maskCtx, t.width, t.height);
				e >= 50 && "function" == typeof t.drawPercentCallback && t.drawPercentCallback()
			},
			!1);
			else {
				var a = !1;
				t.conNode.addEventListener("mouseup",
				function(e) {
					e.preventDefault(),
					a = !1;
					var n = t.getTransparentPercent(t.maskCtx, t.width, t.height);
					n >= 50 && "function" == typeof t.drawPercentCallback && t.drawPercentCallback()
				},
				!1)
			}
			this.mask.addEventListener(n,
			function(n) {
				n.preventDefault(),
				a = !0;
				var i = e ? n.touches[0].pageX: n.pageX || n.x,
				o = e ? n.touches[0].pageY: n.pageY || n.y;
				t.drawPoint(i, o, a)
			},
			!1),
			this.mask.addEventListener(i,
			function(n) {
				if (n.preventDefault(), !a) return ! 1;
				n.preventDefault();
				var i = e ? n.touches[0].pageX: n.pageX || n.x,
				o = e ? n.touches[0].pageY: n.pageY || n.y;
				t.drawPoint(i, o, a)
			},
			!1)
		},
		drawLottery: function() {
			if ("image" == this.lotteryType) {
				var t = new Image,
				e = this;
				t.onload = function() {
					this.width = e.width,
					this.height = e.height,
					e.resizeCanvas(e.background, e.width, e.height),
					e.backCtx.drawImage(this, 0, 0, e.width, e.height),
					e.drawMask()
				},
				t.src = this.lottery
			} else if ("text" == this.lotteryType) {
				this.width = this.width,
				this.height = this.height,
				this.resizeCanvas(this.background, this.width, this.height),
				this.backCtx.save(),
				this.backCtx.fillStyle = "#FFF",
				this.backCtx.fillRect(0, 0, this.width, this.height),
				this.backCtx.restore(),
				this.backCtx.save();
				var n = 30;
				this.backCtx.font = "Bold " + n + "px Arial",
				this.backCtx.textAlign = "center",
				this.backCtx.fillStyle = "#F60",
				this.backCtx.fillText(this.lottery, this.width / 2, this.height / 2 + n / 2),
				this.backCtx.restore(),
				this.drawMask()
			}
		},
		drawMask: function() {
			if ("color" == this.coverType) this.maskCtx.fillStyle = this.cover,
			this.maskCtx.fillRect(0, 0, this.width, this.height),
			this.maskCtx.globalCompositeOperation = "destination-out";
			else if ("image" == this.coverType) {
				var t = new Image,
				e = this;
				t.onload = function() {
					e.resizeCanvas(e.mask, e.width, e.height),
					/android/i.test(navigator.userAgent.toLowerCase()),
					e.maskCtx.globalAlpha = .98,
					e.maskCtx.drawImage(this, 0, 0, this.width, this.height, 0, 0, e.width, e.height);
					var t = 50,
					n = a("#ca-tips").val(),
					i = e.maskCtx.createLinearGradient(0, 0, e.width, 0);
					i.addColorStop("0", "#fff"),
					i.addColorStop("1.0", "#000"),
					e.maskCtx.font = "Bold " + t + "px Arial",
					e.maskCtx.textAlign = "left",
					e.maskCtx.fillStyle = i,
					e.maskCtx.fillText(n, e.width / 2 - e.maskCtx.measureText(n).width / 2, 100),
					e.maskCtx.globalAlpha = 1,
					e.maskCtx.globalCompositeOperation = "destination-out"
				},


				t.src = this.cover
			}
		},
		init: function(t, e) {
			t && (this.lottery = t, this.lottery.width = this.width, this.lottery.height = this.height, this.lotteryType = e || "image", this.vail = !0),
			this.vail && (this.background = this.background || this.createElement("canvas", {
				style: "position:fixed;left:50%;top:0;width:640px;margin-left:-320px;height:100%;background-color:transparent;"
			})),
			this.mask = this.mask || this.createElement("canvas", {
				style: "position:fixed;left:50%;top:0;width:640px;margin-left:-320px;height:100%;background-color:transparent;"
			}),
			this.mask.style.zIndex = 20,
			this.conNode.innerHTML.replace(/[\w\W]| /g, "") || (this.vail && this.conNode.appendChild(this.background), this.conNode.appendChild(this.mask), this.bindEvent()),
			this.vail && (this.backCtx = this.backCtx || this.background.getContext("2d")),
			this.maskCtx = this.maskCtx || this.mask.getContext("2d"),
			this.vail ? this.drawLottery() : this.drawMask();
			var n = this;
			window.addEventListener("resize",
			function() {
				n.width = document.documentElement.clientWidth,
				n.height = document.documentElement.clientHeight,
				n.resizeCanvas_w(n.mask, n.width, n.height)
			},
			!1)
		}
	},
	n.exports = i
}),
define("dist/js/video", ["./zepto", "./global"],
function(t) {
	var e = t("./zepto"),
	n = t("./global"),
	i = {
		_video: e(".j-video"),
		_videoArr: [],
		video_init: function() {
			var t = this;
			this._video.each(function() {
				var i, a = e(this).attr("data-video-src"),
				o = e(this).attr("data-video-type"),
				r = e(this).find(".img"),
				s = e(this).find(".video");
				"bendi" == o ? i = t.bendi_video(a) : "qq" == o ? i = t.qq_video(a) : "youku" == o && (i = t.youku_video(a)),
				e(this).find(".videoWrap").append(e(i)),
				t._videoArr.push(i),
				r.on("click",
				function() {
					e(this).hide(),
					"IFRAME" == i.nodeName && e(i).data("src") && (i.src = e(i).data("src")),
					s.removeClass("f-hide"),
					setTimeout(function() {
						s.addClass("z-show"),
						setTimeout(function() {
							"function" == typeof i.play && i.play()
						},
						500)
					},
					20),
					n._handleEvent("video_open", i)
				}),
				s.on("click",
				function(t) {
					var a = t.target;
					e(a).hasClass("videoWrap") || e(a).parents(".videoWrap").length >= 1 || (s.removeClass("z-show"), r.show(), "function" == typeof i.pause && i.pause(), "IFRAME" == i.nodeName && (e(i).data("src", i.src), i.src = ""), setTimeout(function() {
						s.addClass("f-hide"),
						n._handleEvent("video_close", i)
					},
					500))
				})
			})
		},
		bendi_video: function(t) {
			var n = {
				controls: "controls",
				preload: "none",
				src: t
			},
			i = e("<video></video>")[0];
			for (var a in n) n.hasOwnProperty(a) && a in i && (i[a] = n[a]);
			return i
		},
		qq_video: function(t) {
			return e('<iframe src="' + t + '" frameborder=0 allowfullscreen></iframe>')[0]
		},
		youku_video: function(t) {
			return e('<iframe src="' + t + '" frameborder=0 allowfullscreen></iframe>')[0]
		}
	};
	return i
});