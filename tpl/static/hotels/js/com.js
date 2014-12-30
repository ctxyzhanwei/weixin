define("cCoreInherit", ["libs"],
function(e) {
    var t = [].slice,
    n = function() {};
    return n.Class = function() {
        function r() {
            this.__propertys__(),
            this.initialize.apply(this, arguments)
        }
        if (arguments.length == 0 || arguments.length > 2) throw "参数错误";
        var e = null,
        n = t.call(arguments);
        typeof n[0] == "function" && (e = n.shift()),
        n = n[0],
        r.superclass = e,
        r.subclasses = [];
        var i = function() {},
        s = n.__propertys__ ||
        function() {};
        if (e) {
            e.prototype.__propertys__ && (i = e.prototype.__propertys__);
            var o = function() {};
            o.prototype = e.prototype,
            r.prototype = new o,
            e.subclasses.push(r)
        }
        var u = r.superclass && r.superclass.prototype;
        for (var a in n) {
            var f = n[a];
            if (u && typeof f == "function") {
                var l = /^\s*function\s*\(([^\(\)]*?)\)\s*?\{/i.exec(f.toString())[1].replace(/\s/i, "").split(",");
                l[0] === "$super" && u[a] && (f = function(e, n) {
                    return function() {
                        var r = this,
                        i = [function() {
                            return u[e].apply(r, arguments)
                        }];
                        return n.apply(this, i.concat(t.call(arguments)))
                    }
                } (a, f))
            }
            r.prototype[a] = f
        }
        r.prototype.initialize || (r.prototype.initialize = function() {}),
        r.prototype.__propertys__ = function() {
            i.call(this),
            s.call(this)
        };
        for (key in e) e.hasOwnProperty(key) && key !== "prototype" && key !== "superclass" && (r[key] = e[key]);
        return r.prototype.constructor = r,
        r
    },
    n.extend = function() {
        var e = t.call(arguments),
        n = e.shift() || {};
        if (!n) return ! 1;
        for (var r = 0,
        i = e.length; r < i; r++) if (typeof e[r] == "object") for (var s in e[r]) n[s] = e[r][s];
        return n
    },
    n.implement = function(e, t) {
        if (typeof e != "function") return ! 1;
        for (var n in t) e.prototype[n] = t[n];
        return e
    },
    n
}),
define("cUtilityHybrid", [],
function() {
    var e = {};
    return e.isInApp = function() {
        var e = window.navigator.userAgent;
        if (e.indexOf("CtripWireless") > -1) return ! 0;
        var t = window.localStorage.getItem("isInApp");
        if (t) return t == "1" ? !0 : !1;
        var n = window.localStorage.getItem("ISINAPP");
        if (n) return n == "1" ? !0 : !1
    },
    e.isInWeichat = function() {
        var e = window.navigator.userAgent;
        return e.indexOf("MicroMessenger") > -1 ? !0 : !1
    },
    e.isPreProduction = function() {
        return window.localStorage.getItem("isPreProduction")
    },
    e.getAppSys = function() {
        var e = navigator.userAgent,
        t = /.+_(\w+)_CtripWireless_/,
        n = t.exec(e);
        return n && n[1] ? n[1].toLowerCase() : null
    },
    e.isLite = function() {
        return this.getAppSys() == "ctriplite"
    },
    e
}),
define("cUtilityHash", ["cCoreInherit"],
function(e) {
    var t = function(e, t) {
        if (!t) return - 1;
        if (t.indexOf) return t.indexOf(e);
        for (var n = 0; n < t.length; n++) if (t[n] === e) return n;
        return - 1
    },
    n = {},
    r = {};
    return r.__propertys__ = function() {
        this.keys = [],
        this.values = []
    },
    r.initialize = function(e) {
        typeof e != "object" && (e = {});
        for (var t in e) e.hasOwnProperty(t) && (this.keys.push(t), this.values.push(e[t]));
        var n = ""
    },
    r.length = function() {
        return this.keys.length
    },
    r.getItem = function(e) {
        var n = t(e, this.keys);
        return n < 0 ? null: this.values[n]
    },
    r.getKey = function(e) {
        return this.keys[e]
    },
    r.index = function(e) {
        return this.values[e]
    },
    r.push = function(e, n, r) {
        if (typeof e == "object" && !n) for (var i in e) e.hasOwnProperty(i) && that.push(i, e[i], r);
        else {
            var s = t(e, this.keys);
            s < 0 || r ? (r && this.del(k), this.keys.push(e), this.values.push(n)) : this.values[s] = n
        }
        return this
    },
    r.add = function(e, t) {
        return this.push(e, t)
    },
    r.del = function(e) {
        var n = t(e, this.keys);
        return n < 0 ? this: (this.keys.splice(n, 1), this.values.splice(n, 1), this)
    },
    r.delByIndex = function(e) {
        return e < 0 ? this: (this.keys.splice(e, 1), this.values.splice(e, 1), this)
    },
    r.pop = function() {
        return this.keys.length ? (this.keys.pop(), this.values.pop()) : null
    },
    r.indexOf = function(e) {
        var n = t(e, this.values);
        return n >= 0 ? this.keys[n] : -1
    },
    r.shift = function() {
        return this.keys.length ? (this.keys.shift(), this.values.shift()) : null
    },
    r.unshift = function(e, n, r) {
        if (typeof e == "object" && !n) for (var i in e) e.hasOwnProperty(i) && this.unshift(i, e[i]);
        else {
            var s = t(e, this.keys);
            s < 0 || r ? (r && this.del(e), this.keys.unshift(e), this.values.unshift(n)) : this.values[s] = n
        }
        return this
    },
    r.slice = function(e, t) {
        var n = this.keys.slice(e, t || null),
        r = this.values.slice(e, t || null),
        i = {};
        for (var s = 0; s < n.length; s++) i[n[s]] = r[s];
        return i
    },
    r.splice = function(e, t) {
        var n = this.keys.splice(e, t || null),
        r = this.values.splice(e, t || null),
        i = {};
        for (var s = 0,
        o = n.length; s < o; s++) i[n[s]] = r[s];
        return i
    },
    r.filter = function(e) {
        var t = {};
        if (typeof e != "function") return null;
        for (var n = 0; n < this.keys.length; n++) e.call(this.values[n], this.values[n], this.keys[n]) && (t[this.keys[n]] = this.values[n]);
        return t
    },
    r.each = function(e) {
        var t = {};
        if (typeof e != "function") return null;
        for (var n = 0; n < this.keys.length; n++) e.call(this.values[n], this.values[n], this.keys[n], n)
    },
    r.valueOf = function() {
        var e = {};
        for (var t = 0; t < this.keys.length; t++) e[this.keys[t]] = this.values[t];
        return e
    },
    r.sortBy = function(e) {
        var t = _.sortBy(this.values, e),
        n = [];
        for (var r = 0; r < t.length; r++) {
            var i = this.indexOf(t[r]);
            n[r] = i
        }
        this.values = t,
        this.keys = n
    },
    r.toString = function() {
        return typeof JSON != "undefined" && JSON.stringify ? JSON.stringify(this.valueOf()) : typeof this.values
    },
    n.Hash = new e.Class(r),
    n
}),
define("cUtilityServertime", ["cUtilityHybrid"],
function(e) {
    var t = {};
    return t.getServerDate = function(t) {
        var n = new Date,
        r = function(e) {
            return typeof t == "function" ? t(e) : e
        },
        i = function() {
            var e = window.localStorage.getItem("SERVERDATE");
            if (!e) return r(n);
            try {
                e = JSON.parse(e);
                if (e && e.server && e.local) {
                    var t = window.parseInt(e.server),
                    i = window.parseInt(e.local),
                    s = (new Date).getTime(),
                    o = new Date(t + s - i);
                    return r(o)
                }
                return r(n)
            } catch(u) {
                return r(n)
            }
        },
        s = function() {
            if (location.pathname.match(/^\/?html5/i)) return r(n);
            if (typeof __SERVERDATE__ == "undefined" || !__SERVERDATE__.server) return 0,
            r(n);
            var e = new Date(__SERVERDATE__.server.valueOf() + ((new Date).valueOf() - __SERVERDATE__.local.valueOf()));
            return r(e)
        };
        return e.isInApp() ? i() : s()
    },
    t
}),
define("cUtilityDate", ["cCoreInherit", "cUtilityServertime"],
function(e, t) {
    var n = {};
    return n.Date = new e.Class({
        initialize: function(e) {
            e = e || new Date,
            this.date = new Date(e)
        },
        addDay: function(e) {
            return e = e || 0,
            this.date.setDate(this.date.getDate() + e),
            this
        },
        addMonth: function(e) {
            return e = e || 0,
            this.date.setMonth(this.date.getMonth() + e),
            this
        },
        addHours: function(e) {
            return e = e || 0,
            this.date.setHours(this.date.getHours() + e),
            this
        },
        addMinutes: function(e) {
            return e = e || 0,
            this.date.setMinutes(this.date.getMinutes() + e),
            this
        },
        addSeconds: function(e) {
            return e = e || 0,
            this.date.setSeconds(this.date.getSeconds() + e),
            this
        },
        addYear: function(e) {
            return e = e || 0,
            this.date.setYear(this.date.getFullYear() + e),
            this
        },
        setHours: function() {
            return this.date.setHours.apply(this.date, arguments),
            this
        },
        valueOf: function() {
            return this.date
        },
        getTime: function() {
            return this.date.valueOf()
        },
        toString: function() {
            return this.date.toString()
        },
        format: function(e) {
            typeof e != "string" && (e = "");
            for (var t in this._MAPS) e = this._MAPS[t].call(this, e, this.date, t);
            return e
        },
        diffMonth: function(e) {
            var t = parseInt(this.format("Y")),
            r = parseInt(this.format("m")),
            i = new n.Date(e),
            s = parseInt(i.format("Y")),
            o = parseInt(i.format("m"));
            return (s - t) * 12 + (o - r)
        },
        _DAY1: ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
        _DAY2: ["星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"],
        _MAPS: {
            d: function(e, t, n) {
                var r = t.getDate().toString();
                return r.length < 2 && (r = "0" + r),
                e.replace(new RegExp(n, "mg"), r)
            },
            j: function(e, t, n) {
                return e.replace(new RegExp(n, "mg"), t.getDate())
            },
            N: function(e, t, n) {
                var r = t.getDay();
                return r === 0 && (r = 7),
                e.replace(new RegExp(n, "mg"), r)
            },
            w: function(e, t, n) {
                var r = t.getDay(),
                i = this._DAY1[r];
                return e.replace(new RegExp(n, "mg"), i)
            },
            W: function(e, t, n) {
                var r = t.getDay(),
                i = this._DAY2[r];
                return e.replace(new RegExp(n, "mg"), i)
            },
            m: function(e, t, n) {
                var r = (t.getMonth() + 1).toString();
                return r.length < 2 && (r = "0" + r),
                e.replace(new RegExp(n, "mg"), r)
            },
            n: function(e, t, n) {
                return e.replace(n, t.getMonth() + 1)
            },
            Y: function(e, t, n) {
                return e.replace(new RegExp(n, "mg"), t.getFullYear())
            },
            y: function(e, t, n) {
                return e.replace(new RegExp(n, "mg"), t.getYear())
            },
            g: function(e, t, n) {
                var r = t.getHours();
                return r >= 12 && (r -= 12),
                e.replace(new RegExp(n, "mg"), r)
            },
            G: function(e, t, n) {
                return e.replace(new RegExp(n, "mg"), t.getHours())
            },
            h: function(e, t, n) {
                var r = t.getHours();
                return r >= 12 && (r -= 12),
                r += "",
                r.length < 2 && (r = "0" + r),
                e.replace(new RegExp(n, "mg"), r)
            },
            H: function(e, t, n) {
                var r = t.getHours().toString();
                return r.length < 2 && (r = "0" + r),
                e.replace(new RegExp(n, "mg"), r)
            },
            i: function(e, t, n) {
                var r = t.getMinutes().toString();
                return r.length < 2 && (r = "0" + r),
                e.replace(new RegExp(n, "mg"), r)
            },
            s: function(e, t, n) {
                var r = t.getSeconds().toString();
                return r.length < 2 && (r = "0" + r),
                e.replace(new RegExp(n, "mg"), r)
            },
            I: function(e, t, n) {
                var r = t.getMinutes().toString();
                return e.replace(new RegExp(n, "mg"), r)
            },
            S: function(e, t, n) {
                var r = t.getSeconds().toString();
                return e.replace(new RegExp(n, "mg"), r)
            },
            D: function(e, n, r) {
                var i = t.getServerDate();
                i.setHours(0, 0, 0, 0),
                n = new Date(n.valueOf()),
                n.setHours(0, 0, 0, 0);
                var s = 864e5,
                o = "",
                u = n - i;
                return u >= 0 && (u < s ? o = "今天": u < 2 * s ? o = "明天": u < 3 * s && (o = "后天")),
                e.replace(new RegExp(r, "mg"), o)
            }
        }
    }),
    e.extend(n.Date, {
        parse: function(e, t) {
            if (typeof e == "undefined") return new Date;
            if (typeof e == "string") {
                e = e || "";
                var r = /^(\d{4})\-?(\d{1,2})\-?(\d{1,2})/i;
                e.match(r) && (e = e.replace(r, "$2/$3/$1"));
                var i = Date.parse(e),
                s = new Date(i || new Date);
                return t ? s: new n.Date(s)
            }
            return typeof e == "number" ? new Date(e) : new Date
        },
        getHM: function(e) {
            var t = this._getDate(e),
            n = t.getHours(),
            r = t.getMinutes();
            return (n < 10 ? "0" + n: "" + n) + ":" + (r < 10 ? "0" + r: "" + r)
        },
        getIntervalDay: function(e, t) {
            var n = this._getDate(e),
            r = this._getDate(t);
            return n.setHours(0, 0, 0, 0),
            r.setHours(0, 0, 0, 0),
            parseInt((r - n) / 864e5)
        },
        m2H: function(e) {
            var t = Math.floor(e / 60),
            n = e % 60;
            return (t > 0 ? t + "小时": "") + (n > 0 ? n + "分钟": "")
        },
        _getDate: function(e) {
            var t = n.Date.parse(e, !0),
            r = new Date;
            return r.setTime(t),
            r
        },
        format: function(e, t) {
            return (new n.Date(e)).format(t)
        },
        weekday: function(e) {
            var t = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"],
            n = new Date(e);
            return t[n.getDay()]
        },
        diffMonth: function(e, t) {
            return e = new n.Date(e),
            e.diffMonth(t)
        }
    }),
    n.Date
}),
define("Validate", [],
function() {
    var e = {};
    _toString = Object.prototype.toString,
    $.each("String Function Boolean RegExp Number Date Object Null Undefined".split(" "),
    function(t, n) {
        var r;
        switch (n) {
        case "Null":
            r = function(e) {
                return e === null
            };
            break;
        case "Undefined":
            r = function(e) {
                return e === undefined
            };
            break;
        default:
            r = function(e) {
                return (new RegExp(n + "]", "i")).test(_toString.call(e))
            }
        }
        e["is" + n] = r
    });
    var t = {
        isEmail: function(e) {
            var t = /^(?:\w+\.?)*\w+@(?:\w+\.?)*\w+$/;
            return t.test(e)
        },
        isPassword: function(e) {
            var t = /^[a-zA-Z0-9]{6,20}$/;
            return t.test(e)
        },
        isMobile: function(e) {
            var t = /^(1[3-8][0-9])\d{8}$/;
            return t.test(e)
        },
        isChinese: function(e) {
            var t = /^[\u4e00-\u9fff]{0,}$/;
            return t.test(e)
        },
        isEnglish: function(e) {
            var t = /^[A-Za-z]+$/;
            return t.test(e)
        },
        isZip: function(e) {
            var t = /^\d{6}$/;
            return t.test(e)
        },
        isDate: function(e) {
            var t = /^(([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})(((0[13578]|1[02])(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)(0[1-9]|[12][0-9]|30))|(02(0[1-9]|[1][0-9]|2[0-8]))))|((([0-9]{2})(0[48]|[2468][048]|[13579][26])|((0[48]|[2468][048]|[3579][26])00))0229)$/;
            return t.test(e) ? !0 : !1
        },
        isNum: function(e) {
            var t = /^\d+$/;
            return t.test(e)
        },
        isCellPhone: function(e) {
            var t = /(^0{0,1}1[3|4|5|6|7|8][0-9]{9}$)/;
            return t.test(e)
        },
        isIDCardNo: function(e) {
            var t = /^[A-Za-z0-9]+$/;
            return t.test(e)
        },
        isEnglishAndSpace: function(e) {
            var t = /^([a-zA-Z ]+|[\u4e00-\u9fa5]+)$/;
            return t.test(e)
        },
        isTraditional: function(e) {
            var t = "萬與醜專業叢東絲兩嚴喪個爿豐臨為麗舉麼義烏樂喬習鄉書買亂爭於虧雲亙亞產畝親褻嚲億僅從侖倉儀們價眾優夥會傴傘偉傳傷倀倫傖偽佇體餘傭僉俠侶僥偵側僑儈儕儂俁儔儼倆儷儉債傾傯僂僨償儻儐儲儺兒兌兗黨蘭關興茲養獸囅內岡冊寫軍農塚馮衝決況凍淨淒涼淩減湊凜幾鳳鳧憑凱擊氹鑿芻劃劉則剛創刪別剗剄劊劌剴劑剮劍剝劇勸辦務勱動勵勁勞勢勳勩勻匭匱區醫華協單賣盧鹵臥衛卻巹廠廳曆厲壓厭厙廁廂厴廈廚廄廝縣參靉靆雙發變敘疊葉號歎嘰籲後嚇呂嗎唚噸聽啟吳嘸囈嘔嚦唄員咼嗆嗚詠哢嚨嚀噝吒噅鹹呱響啞噠嘵嗶噦嘩噲嚌噥喲嘜嗊嘮啢嗩唕喚嘖嗇囀齧囉嘽嘯噴嘍嚳囁嗬噯噓嚶囑嚕劈囂謔團園囪圍圇國圖圓聖壙場壞塊堅壇壢壩塢墳墜壟壟壚壘墾堊墊埡墶壋塏堖塒塤堝墊垵塹墮壪牆壯聲殼壺壼處備複夠頭誇夾奪奩奐奮獎奧妝婦媽嫵嫗媯姍薑婁婭嬈嬌孌娛媧嫻嫿嬰嬋嬸媼嬡嬪嬙嬤孫學孿寧寶實寵審憲宮寬賓寢對尋導壽將爾塵堯尷屍盡層屭屜屆屬屢屨嶼歲豈嶇崗峴嶴嵐島嶺嶽崠巋嶨嶧峽嶢嶠崢巒嶗崍嶮嶄嶸嶔崳嶁脊巔鞏巰幣帥師幃帳簾幟帶幀幫幬幘幗冪襆幹並廣莊慶廬廡庫應廟龐廢廎廩開異棄張彌弳彎彈強歸當錄彠彥徹徑徠禦憶懺憂愾懷態慫憮慪悵愴憐總懟懌戀懇惡慟懨愷惻惱惲悅愨懸慳憫驚懼慘懲憊愜慚憚慣湣慍憤憒願懾憖懣懶懍戇戔戲戧戰戩戶紮撲扡執擴捫掃揚擾撫摶摳掄搶護報擔擬攏揀擁攔擰撥擇掛摯攣掗撾撻挾撓擋撟掙擠揮撏撈損撿換搗據撚擄摑擲撣摻摜摣攬撳攙擱摟攪攜攝攄擺搖擯攤攖撐攆擷擼攛擻攢敵斂數齋斕鬥斬斷無舊時曠暘曇晝曨顯晉曬曉曄暈暉暫曖劄術樸機殺雜權條來楊榪傑極構樅樞棗櫪梘棖槍楓梟櫃檸檉梔柵標棧櫛櫳棟櫨櫟欄樹棲樣欒棬椏橈楨檔榿橋樺檜槳樁夢檮棶檢欞槨櫝槧欏橢樓欖櫬櫚櫸檟檻檳櫧橫檣櫻櫫櫥櫓櫞簷檁歡歟歐殲歿殤殘殞殮殫殯毆毀轂畢斃氈毿氌氣氫氬氳彙漢汙湯洶遝溝沒灃漚瀝淪滄渢溈滬濔濘淚澩瀧瀘濼瀉潑澤涇潔灑窪浹淺漿澆湞溮濁測澮濟瀏滻渾滸濃潯濜塗湧濤澇淶漣潿渦溳渙滌潤澗漲澀澱淵淥漬瀆漸澠漁瀋滲溫遊灣濕潰濺漵漊潷滾滯灩灄滿瀅濾濫灤濱灘澦濫瀠瀟瀲濰潛瀦瀾瀨瀕灝滅燈靈災燦煬爐燉煒熗點煉熾爍爛烴燭煙煩燒燁燴燙燼熱煥燜燾煆溜愛爺牘犛牽犧犢強狀獷獁猶狽麅獮獰獨狹獅獪猙獄猻獫獵獼玀豬貓蝟獻獺璣璵瑒瑪瑋環現瑲璽瑉玨琺瓏璫琿璡璉瑣瓊瑤璦璿瓔瓚甕甌電畫暢疇癤療瘧癘瘍鬁瘡瘋皰屙癰痙癢瘂癆瘓癇癡癉瘮瘞瘺癟癱癮癭癩癬癲臒皚皺皸盞鹽監蓋盜盤瞘眥矓睜睞瞼瞞矚矯磯礬礦碭碼磚硨硯碸礪礱礫礎硜碩硤磽磑礄確鹼礙磧磣堿镟滾禮禕禰禎禱禍稟祿禪離禿稈種積稱穢穠穭稅穌穩穡窮竊竅窯竄窩窺竇窶豎競篤筍筆筧箋籠籩築篳篩簹箏籌簽簡籙簀篋籜籮簞簫簣簍籃籬籪籟糴類秈糶糲粵糞糧糝餱緊縶糸糾紆紅紂纖紇約級紈纊紀紉緯紜紘純紕紗綱納紝縱綸紛紙紋紡紵紖紐紓線紺絏紱練組紳細織終縐絆紼絀紹繹經紿綁絨結絝繞絰絎繪給絢絳絡絕絞統綆綃絹繡綌綏絛繼綈績緒綾緓續綺緋綽緔緄繩維綿綬繃綢綯綹綣綜綻綰綠綴緇緙緗緘緬纜緹緲緝縕繢緦綞緞緶線緱縋緩締縷編緡緣縉縛縟縝縫縗縞纏縭縊縑繽縹縵縲纓縮繆繅纈繚繕繒韁繾繰繯繳纘罌網羅罰罷羆羈羥羨翹翽翬耮耬聳恥聶聾職聹聯聵聰肅腸膚膁腎腫脹脅膽勝朧腖臚脛膠脈膾髒臍腦膿臠腳脫腡臉臘醃膕齶膩靦膃騰臏臢輿艤艦艙艫艱豔艸藝節羋薌蕪蘆蓯葦藶莧萇蒼苧蘇檾蘋莖蘢蔦塋煢繭荊薦薘莢蕘蓽蕎薈薺蕩榮葷滎犖熒蕁藎蓀蔭蕒葒葤藥蒞蓧萊蓮蒔萵薟獲蕕瑩鶯蓴蘀蘿螢營縈蕭薩蔥蕆蕢蔣蔞藍薊蘺蕷鎣驀薔蘞藺藹蘄蘊藪槁蘚虜慮虛蟲虯蟣雖蝦蠆蝕蟻螞蠶蠔蜆蠱蠣蟶蠻蟄蛺蟯螄蠐蛻蝸蠟蠅蟈蟬蠍螻蠑螿蟎蠨釁銜補襯袞襖嫋褘襪襲襏裝襠褌褳襝褲襇褸襤繈襴見觀覎規覓視覘覽覺覬覡覿覥覦覯覲覷觴觸觶讋譽謄訁計訂訃認譏訐訌討讓訕訖訓議訊記訒講諱謳詎訝訥許訛論訩訟諷設訪訣證詁訶評詛識詗詐訴診詆謅詞詘詔詖譯詒誆誄試詿詩詰詼誠誅詵話誕詬詮詭詢詣諍該詳詫諢詡譸誡誣語誚誤誥誘誨誑說誦誒請諸諏諾讀諑誹課諉諛誰諗調諂諒諄誶談誼謀諶諜謊諫諧謔謁謂諤諭諼讒諮諳諺諦謎諞諝謨讜謖謝謠謗諡謙謐謹謾謫譾謬譚譖譙讕譜譎讞譴譫讖穀豶貝貞負貟貢財責賢敗賬貨質販貪貧貶購貯貫貳賤賁貰貼貴貺貸貿費賀貽賊贄賈賄貲賃賂贓資賅贐賕賑賚賒賦賭齎贖賞賜贔賙賡賠賧賴賵贅賻賺賽賾贗讚贇贈贍贏贛赬趙趕趨趲躉躍蹌蹠躒踐躂蹺蹕躚躋踴躊蹤躓躑躡蹣躕躥躪躦軀車軋軌軒軑軔轉軛輪軟轟軲軻轤軸軹軼軤軫轢軺輕軾載輊轎輈輇輅較輒輔輛輦輩輝輥輞輬輟輜輳輻輯轀輸轡轅轄輾轆轍轔辭辯辮邊遼達遷過邁運還這進遠違連遲邇逕跡適選遜遞邐邏遺遙鄧鄺鄔郵鄒鄴鄰鬱郟鄶鄭鄆酈鄖鄲醞醱醬釅釃釀釋裏钜鑒鑾鏨釓釔針釘釗釙釕釷釺釧釤鈒釩釣鍆釹鍚釵鈃鈣鈈鈦鈍鈔鍾鈉鋇鋼鈑鈐鑰欽鈞鎢鉤鈧鈁鈥鈄鈕鈀鈺錢鉦鉗鈷缽鈳鉕鈽鈸鉞鑽鉬鉭鉀鈿鈾鐵鉑鈴鑠鉛鉚鈰鉉鉈鉍鈹鐸鉶銬銠鉺銪鋏鋣鐃銍鐺銅鋁銱銦鎧鍘銖銑鋌銩銛鏵銓鉿銚鉻銘錚銫鉸銥鏟銃鐋銨銀銣鑄鐒鋪鋙錸鋱鏈鏗銷鎖鋰鋥鋤鍋鋯鋨鏽銼鋝鋒鋅鋶鐦鐧銳銻鋃鋟鋦錒錆鍺錯錨錡錁錕錩錫錮鑼錘錐錦鍁錈錇錟錠鍵鋸錳錙鍥鍈鍇鏘鍶鍔鍤鍬鍾鍛鎪鍠鍰鎄鍍鎂鏤鎡鏌鎮鎛鎘鑷鐫鎳鎿鎦鎬鎊鎰鎔鏢鏜鏍鏰鏞鏡鏑鏃鏇鏐鐔钁鐐鏷鑥鐓鑭鐠鑹鏹鐙鑊鐳鐶鐲鐮鐿鑔鑣鑞鑲長門閂閃閆閈閉問闖閏闈閑閎間閔閌悶閘鬧閨聞闥閩閭闓閥閣閡閫鬮閱閬闍閾閹閶鬩閿閽閻閼闡闌闃闠闊闋闔闐闒闕闞闤隊陽陰陣階際陸隴陳陘陝隉隕險隨隱隸雋難雛讎靂霧霽黴靄靚靜靨韃鞽韉韝韋韌韍韓韙韞韜韻頁頂頃頇項順須頊頑顧頓頎頒頌頏預顱領頗頸頡頰頲頜潁熲頦頤頻頮頹頷頴穎顆題顒顎顓顏額顳顢顛顙顥纇顫顬顰顴風颺颭颮颯颶颸颼颻飀飄飆飆飛饗饜飣饑飥餳飩餼飪飫飭飯飲餞飾飽飼飿飴餌饒餉餄餎餃餏餅餑餖餓餘餒餕餜餛餡館餷饋餶餿饞饁饃餺餾饈饉饅饊饌饢馬馭馱馴馳驅馹駁驢駔駛駟駙駒騶駐駝駑駕驛駘驍罵駰驕驊駱駭駢驫驪騁驗騂駸駿騏騎騍騅騌驌驂騙騭騤騷騖驁騮騫騸驃騾驄驏驟驥驦驤髏髖髕鬢魘魎魚魛魢魷魨魯魴魺鮁鮃鯰鱸鮋鮓鮒鮊鮑鱟鮍鮐鮭鮚鮳鮪鮞鮦鰂鮜鱠鱭鮫鮮鮺鯗鱘鯁鱺鰱鰹鯉鰣鰷鯀鯊鯇鮶鯽鯒鯖鯪鯕鯫鯡鯤鯧鯝鯢鯰鯛鯨鯵鯴鯔鱝鰈鰏鱨鯷鰮鰃鰓鱷鰍鰒鰉鰁鱂鯿鰠鼇鰭鰨鰥鰩鰟鰜鰳鰾鱈鱉鰻鰵鱅鰼鱖鱔鱗鱒鱯鱤鱧鱣鳥鳩雞鳶鳴鳲鷗鴉鶬鴇鴆鴣鶇鸕鴨鴞鴦鴒鴟鴝鴛鴬鴕鷥鷙鴯鴰鵂鴴鵃鴿鸞鴻鵐鵓鸝鵑鵠鵝鵒鷳鵜鵡鵲鶓鵪鶤鵯鵬鵮鶉鶊鵷鷫鶘鶡鶚鶻鶿鶥鶩鷊鷂鶲鶹鶺鷁鶼鶴鷖鸚鷓鷚鷯鷦鷲鷸鷺鸇鷹鸌鸏鸛鸘鹺麥麩黃黌黶黷黲黽黿鼂鼉鞀鼴齇齊齏齒齔齕齗齟齡齙齠齜齦齬齪齲齷龍龔龕龜誌製谘隻裡係範鬆冇嚐嘗鬨麵準鐘彆閒儘臟拚";
            for (var n = 0; n < e.length; n++) {
                var r = e.charAt(n);
                if (t.indexOf(r) > -1) return ! 0
            }
            return ! 1
        },
        isIdCard: function(e) {
            var t = e.toLowerCase().match(/\w/g);
            if (e.match(/^\d{17}[\dx]$/i)) {
                var n = 0,
                r = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
                for (var i = 0; i < 17; i++) n += parseInt(t[i], 10) * r[i];
                return "10x98765432".charAt(n % 11) != t[17] ? !1 : !!e.replace(/^\d{6}(\d{4})(\d{2})(\d{2}).+$/, "$1-$2-$3")
            }
            return e.match(/^\d{15}$/) ? !!e.replace(/^\d{6}(\d{2})(\d{2})(\d{2}).+$/, "19$1-$2-$3") : !1
        },
        isIdCard_bak: function(e) {
            var t = new Array(!0, !1, !1, !1, !1),
            n = {
                11 : "北京",
                12 : "天津",
                13 : "河北",
                14 : "山西",
                15 : "内蒙古",
                21 : "辽宁",
                22 : "吉林",
                23 : "黑龙江",
                31 : "上海",
                32 : "江苏",
                33 : "浙江",
                34 : "安徽",
                35 : "福建",
                36 : "江西",
                37 : "山东",
                41 : "河南",
                42 : "湖北",
                43 : "湖南",
                44 : "广东",
                45 : "广西",
                46 : "海南",
                50 : "重庆",
                51 : "四川",
                52 : "贵州",
                53 : "云南",
                54 : "西藏",
                61 : "陕西",
                62 : "甘肃",
                63 : "青海",
                64 : "宁夏",
                65 : "xinjiang",
                71 : "台湾",
                81 : "香港",
                82 : "澳门",
                91 : "国外"
            },
            r,
            i,
            s,
            o,
            u = [];
            u = e.split("");
            if (n[parseInt(e.substr(0, 2))] === null) return t[4];
            switch (e.length) {
            case 18:
                return parseInt(e.substr(6, 4)) % 4 === 0 || parseInt(e.substr(6, 4)) % 100 === 0 && parseInt(e.substr(6, 4)) % 4 === 0 ? ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/: ereg = /^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/,
                ereg.test(e) ? (s = (parseInt(u[0]) + parseInt(u[10])) * 7 + (parseInt(u[1]) + parseInt(u[11])) * 9 + (parseInt(u[2]) + parseInt(u[12])) * 10 + (parseInt(u[3]) + parseInt(u[13])) * 5 + (parseInt(u[4]) + parseInt(u[14])) * 8 + (parseInt(u[5]) + parseInt(u[15])) * 4 + (parseInt(u[6]) + parseInt(u[16])) * 2 + parseInt(u[7]) * 1 + parseInt(u[8]) * 6 + parseInt(u[9]) * 3, r = s % 11, o = "F", i = "10X98765432", o = i.substr(r, 1), o.toUpperCase() == u[17].toUpperCase() ? t[0] : t[3]) : t[2];
            default:
                return t[1]
            }
        },
        isRightVerifycode: function(e) {
            var t = /^[a-z0-9]{4,30}$/;
            return t.test(e)
        },
        isAllowSetTradingPass: function(e) {
            var t = !0,
            n = !0,
            r = !0,
            i = !0;
            for (var s = 0; s < e.length - 1; s++) {
                var o = parseInt(e[s]),
                u = parseInt(e[s + 1]);
                t = t && o == u,
                n = n && u == o + 1;
                var a = s < e.length + 1 ? parseInt(e[s + 3]) : -1,
                f = parseInt(e[5 - s]);
                s < e.length / 2 - 1 ? (r = r && o == a && u == o + 1, i = i && o == f && u == o + 1) : s == e.length / 2 - 1 && (r = r && o == a, i = i && o == f);
                if (s + 2 == e.length && e.length == 6 && (t || n || r || i)) return ! 1
            }
            return ! 0
        },
        isQq: function(e) {
            return /^[1-9]\d{4,}$/.test(e)
        },
        isPhone: function(e) {
            return /^[0-9]{3,4}-[0-9]{7,8}$/.test(e)
        },
        isUrl: function(e) {
            return /^http(s)?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\:+!]*([^<>])*$/.test(e)
        },
        isIP: function(t) {
            if (!t || e.isNull(t)) return ! 1;
            var n = /^(\d+)\.(\d+)\.(\d+)\.(\d+)$/g;
            return n.test(t) && RegExp.$1 < 256 && RegExp.$2 < 256 && RegExp.$3 < 256 && RegExp.$4 < 256 ? !0 : !1
        },
        isEmptyObject: function(e) {
            for (var t in e) return ! 1;
            return ! 0
        },
        isCharsLenWithinRange: function(t, n) {
            if (!e.isString(t)) return ! 1;
            var r = t.match(/\W/g),
            i = r == null ? t.length: t.length + r.length,
            s = i >= 0 && i <= n;
            return s ? (this.cutLen = t.length, !0) : !1
        },
        isContactName: function(t) {
            return e.isString(t) ? this.isCharsLenWithinRange.call(this, t, 26) : !1
        },
        isBookPS: function(t) {
            return e.isString(t) ? this.isCharsLenWithinRange.call(this, t, 100) : !1
        },
        isInvTitle: function(t) {
            return e.isString(t) ? this.isCharsLenWithinRange.call(this, t, 100) : !1
        },
        isBoardTitle: function(t) {
            return e.isString(t) ? this.isCharsLenWithinRange.call(this, t, 40) : !1
        },
        isAreaTitle: function(t) {
            return e.isString(t) ? this.isCharsLenWithinRange.call(this, t, 80) : !1
        },
        isMobileNumber: function(t) {
            if (!e.isString(t)) return ! 1;
            var n = 11;
            return t.length == n && /^(\d| )+$/g.test(t)
        },
        isFlightNumber: function(t) {
            if (!e.isString(t)) return ! 1;
            var n = 3,
            r = 7;
            return t.length >= n && t.length <= r && /^(\d|\w)+$/g.test(t)
        }
    };
    return t.isPostcode = t.isZip,
    t
}),
define("cUtility", ["cUtilityHybrid", "cUtilityHash", "cUtilityDate", "cUtilityServertime", "Validate"],
function(e, t, n, r, i) {
    var s = function(e) {
        return Object.prototype.toString.call(e)
    },
    o = {};
    return $.extend(o, e),
    o.Date = n,
    o.Hash = t.Hash,
    o.trim = function(e) {
        return e.replace(/(^[\s\u3000]*)|([\s\u3000]*$)/g, "")
    },
    o.stripTags = function(e) {
        return (e || "").replace(/<[^>]+>/g, "")
    },
    o.mix = function(e, t) {
        return _.extend(e, t)
    },
    o.indexOf = function(e, t) {
        return _.indexOf(t, e)
    },
    o.each = _.each,
    o.grep = _.filter,
    o.getServerDate = r.getServerDate,
    o.getGuid = function() {
        function e() {
            return ((1 + Math.random()) * 65536 | 0).toString(16).substring(1)
        }
        function t() {
            return e() + e() + "-" + e() + "-" + e() + "-" + e() + "-" + e() + e() + e()
        }
        var n = window.localStorage.GUID || "";
        if (!n) {
            n = t();
            try {
                window.localStorage.setItem("GUID", n)
            } catch(r) {}
        }
        return n
    },
    o.Object = {},
    o.Object.set = function(e, t, n) {
        if (!t) return null;
        var r = t.split(".");
        e = e || {};
        for (var i = 0,
        s = r.length,
        o = Math.max(s - 1, 0); i < s; i++) i < o ? e = e[r[i]] = e[r[i]] || {}: e[r[i]] = n;
        return e
    },
    o.Object.get = function(e, t) {
        if (!e || !t) return null;
        var n = t.split(".");
        e = e || {};
        for (var r = 0,
        i = n.length,
        s = Math.max(i - 1, 0); r < i; r++) {
            e = e[n[r]];
            if (e === null || typeof e == "undefined") return null
        }
        return e
    },
    o.SimpleQueue = function() {
        this.initialize()
    },
    o.SimpleQueue.prototype = {
        initialize: function() {
            this.index = 0,
            this.handlers = [],
            this.isStart = !1
        },
        add: function(e) {
            this.handlers.push(e),
            this.isStart || (this.isStart = !0, this._next())
        },
        _next: function(e) {
            var t = this.handlers.shift();
            t && t.call(this, this, e)
        },
        next: function() {
            this._next.apply(this, arguments),
            this.stop()
        },
        stop: function() {
            this.isStart = !1
        }
    },
    o.tryUrl = function(e) {
        var t = document.createElement("iframe");
        t.height = 1,
        t.width = 1,
        t.frameBorder = 0,
        t.style.position = "absolute",
        t.style.left = "-9999px",
        t.style.top = "-9999px",
        document.body.appendChild(t),
        o.tryUrl = function(e) {
            t.src = e
        },
        U.tryUrl(e)
    },
    o.validate = i,
    o.JsonArrayToObject = function(e) {
        if (!e) return [];
        var t = e.shift(),
        n = [],
        r = null;
        for (var i = 0,
        o = e.length; i < o; i++) {
            r = {};
            for (var u = 0,
            a = e[i].length; u < a; u++) switch (s(e[i][u])) {
            case "[object Array]":
                r[t[u]] = U.JsonArrayToObject(e[i][u]);
                break;
            default:
                r[t[u]] = e[i][u]
            }
            n.push(r)
        }
        return n
    },
    o.dateParse = function(e) {
        var t = new RegExp("^\\d+(\\-|\\/)\\d+(\\-|\\/)\\d+$");
        if ("string" == typeof e) {
            if (t.test(e) || isNaN(Date.parse(e))) {
                var n = e.split(/ |T/),
                r = n.length > 1 ? n[1].split(/[^\d]/) : [0, 0, 0],
                i = n[0].split(/[^\d]/);
                return new Date(i[0] - 0, i[1] - 1, i[2] - 0, r[0] - 0, r[1] - 0, r[2] - 0)
            }
            return new Date(e)
        }
        return new Date
    },
    o.deleteValue = function(e, t) {
        var n = U.indexOf(e, t);
        return n > -1 ? t.splice(n, 1) : null
    },
    o
}),
define("cBase", ["libs", "cCoreInherit", "cUtility"],
function(e, t, n) {
    typeof console == "undefined" && (console = {
        log: function() {},
        error: function() {}
    });
    var r = {};
    r.isInApp = n.isInApp,
    r.Class = t.Class,
    r.extend = t.extend,
    r.implement = t.implement;
    var i = [].slice,
    s = function(e, t, n) {
        return n = n || [],
        function() {
            e.apply(t, n.concat(i.call(arguments)))
        }
    },
    o = function(e) {
        return Object.prototype.toString.call(e)
    };
    r.Object = new r.Class({});
    var u = {
        keys: function(e) {
            var t = [];
            if (typeof e == "object") if (typeof Object.keys == "function") Object.keys(e);
            else for (var n in e) e.hasOwnProperty(n) && t.push(n);
            return t
        }
    };
    return r.extend(r.Object, u),
    r.Date = n.Date,
    r.Hash = n.Hash,
    r.getInstance = function() {
        return this.instance || new this
    },
    r.getServerDate = n.getServerDate,
    r
}),
define("cUIAnimation", [],
function() {
    return {
        slideleft: function(e, t, n, r) {
            $("body").addClass("hiddenx"),
            e.addClass("animatestart"),
            e.addClass("sliderightin"),
            e.__show();
            var i = this;
            return setTimeout(function() {
                $("body").removeClass("hiddenx"),
                e.removeClass("animatestart"),
                e.removeClass("sliderightin"),
                t && t.__hide(e.viewname),
                n && n.call(r, e, t)
            },
            340)
        },
        slideright: function(e, t, n, r) {
            $("body").addClass("hiddenx"),
            t && (t.addClass("animatestart"), t.addClass("sliderightout")),
            e.__show();
            var i = this;
            return setTimeout(function() {
                $("body").removeClass("hiddenx"),
                t && (t.removeClass("animatestart"), t.removeClass("sliderightout"), t.__hide(e.viewname)),
                n && n.call(r, e, t)
            },
            340)
        },
        noAnimate: function(e, t, n, r) {
            t && t.__hide(e.viewname),
            e.__show(),
            n && n.call(r, e, t)
        }
    }
}),
define("cLog", ["cUtilityServertime"],
function(e) {
    var t = {
        serverTime: e.getServerDate().getTime(),
        event: {
            DOMREADY: "JS.Lizard.Domready",
            ONLOAD: "JS.Lizard.OnLoad",
            AJAXREADY: "JS.Lizard.AjaxReady"
        }
    };
    return t.applog = t.appLog = function(e, t) {},
    t.onDomReady = function(e) {
        this.sendCommonTrack(this.event.DOMREADY, e)
    },
    t.onLoad = function(e) {
        this.sendCommonTrack(this.event.ONLOAD, e)
    },
    t.ajaxReady = function(e, t, n) {
        n || (n = this.getNow());
        var r = n - t,
        i = this._createExtParam();
        i.url = e,
        i.distribution = this._chooseTimeZone(r),
        this.sendTrack(this.event.AJAXREADY, i, r)
    },
    t.sendCommonTrack = function(e, t) {
        var n = t ? t: this.localTime,
        r = this.getNow(),
        i = this._createExtParam();
        this.sendTrack(e, i, r - n)
    },
    t.sendTrack = function(e, t, n) {
        window.__bfi || (window.__bfi = []);
        var r = this.serverTime + (this.getNow() - this.localTime);
        0,
        window.__bfi.push(["_trackMatrix", e, t, n, r])
    },
    t._createExtParam = function(e, t) {
        var n = {
            version: "1.1"
        };
        return n
    },
    t.getNow = function() {
        return (new Date).getTime()
    },
    t._chooseTimeZone = function(e) {
        var t = "[2000,--]";
        return e >= 2e3 ? t = "[2000,--]": e >= 1e3 ? t = "[1000,2000]": e >= 500 ? t = "[500,1000]": e >= 250 ? t = "[250,500]": e >= 0 && (t = "[0,250]"),
        t
    },
    t.localTime = typeof __SERVERDATE__ != "undefined" && __SERVERDATE__.local ? __SERVERDATE__.local.getTime() : t.getNow(),
    t
}),
define("AbstractAPP", ["libs", "cBase", "cUIAnimation", "cLog"],
function(e, t, n, r) {
    var i = new t.Class({
        __propertys__: function() {
            this.webroot = "/#hotelsearch",
            this.viewRootPath = "app/views/",
            this.defaultView = "index",
            this.request,
            this.viewpath,
            this.mainframe,
            this.viewport,
            this.statedom,
            this.views = new t.Hash,
            this.curView,
            this.lastView,
            this.inteface = {
                loadView: _.bind(this.loadView, this),
                forward: _.bind(this.forward, this),
                back: _.bind(this.back, this)
            },
            this.isCreate = !1,
            this.history = [],
            this.stopListening = !1,
            this.timeoutres,
            this.lastHash = "",
            this.lashFullHash = "",
            this.isChangeHash = !1,
            this.animations = n,
            this.isAnimat = !0,
            this.animatSwitch = !1,
            t.isInApp() && (this.animatSwitch = !0),
            this.animForwardName = "slideleft",
            this.animBackwardName = "slideright",
            this.animNoName = "noAnimate",
            this.animatName = this.animNoName,
            this.path = [],
            this.query = {},
            this.viewMapping = {}
        },
        initialize: function(e) {
            this.setOption(e),
            this.buildEvent()
        },
        setOption: function(e) {
            e = e || {};
            for (var t in e) this[t] = e[t]
        },
        buildEvent: function() {
            var e = this;
            requirejs.onError = function(e) {
                if (e && e.requireModules) for (var t = 0; t < e.requireModules.length; t++) {
                    0;
                    break
                }
            },
            $(window).bind("hashchange", _.bind(this.onHashChange, this)),
            this.onHashChange(),
            this.pushHistory()
        },
        onHashChange: function() {
            if (!this.stopListening) {
                var e = decodeURIComponent(location.href).replace(/^[^#]+(#(.+))?/g, "$2").toLowerCase();
                this._onHashChange(e)
            }
        },
        _onHashChange: function(e, t) {
            e = e.replace(/^#+/i, "");
            if (e == "bd=baidu_map") e = "";
            else if (e == "rd") return;
            var n = this.parseHash(e);
            this.localObserver(n, t)
        },
        parseHash: function(e) {
            function o(e) {
                var e = e.split("://"),
                t = /([^&=?]+)=([^&]+)/g,
                n = {},
                r,
                i,
                s;
                while (r = t.exec(e[0])) name = r[1],
                i = r[2],
                n[name] = i;
                if (e[1]) {
                    var o = 0;
                    s = _.size(n),
                    _.each(n,
                    function(t, r) {++o == s && (n[r] += "://" + e[1])
                    })
                }
                return n
            }
            var t = e,
            e = e.replace(/([^\|]*)(?:\|.*)?$/img, "$1"),
            n = /^([^?&|]*)(.*)?$/i.exec(e),
            r = n[1] ? n[1].split("!") : [],
            i = (r.shift() || "").replace(/(^\/+|\/+$)/i, ""),
            s = r.length ? r.join("!").replace(/(^\/+|\/+$)/i, "").split("/") : this.path;
            return this.isChangeHash = !this.lastHash && t === this.lashFullHash || !!this.lastHash && this.lastHash !== e,
            location.hash.indexOf("cui-") != -1 && (this.isChangeHash = !1),
            this.lastHash = e,
            this.lashFullHash = t,
            {
                viewpath: i,
                path: s,
                query: o(t),
                root: location.pathname + location.search,
                fullhash: t
            }
        },
        localObserver: function(e, t) {
            this.animatName = t ? this.animForwardName: this.animBackwardName,
            this.request = e,
            this.viewpath = this.request.viewpath || this.defaultView,
            this.request.viewpath = this.viewpath,
            this.switchView(this.viewpath)
        },
        switchView: function(e) {
            var t = e,
            n = this.views.getItem(t),
            i = this.curView;
            i && i != n && (this.lastView = i);
            if (n) {
                if (n == this.curView && this.isChangeHash == 0) return;
                n.request = this.request,
                this.curView = n;
                var s = (i || n).viewname;
                n.lostLoadTime = r.getNow(),
                this.curView.__load(s)
            } else {
                var o = r.getNow();
                this.history.length < 2 && (o = r.localTime),
                this.loadView(e,
                function(e) {
                    if ($('[page-url="' + t + '"]').length > 0) return;
                    n = new e(this.request, this.inteface, t),
                    n.lostLoadTime = o,
                    n.isLoaded = !1,
                    r.onDomReady(n.lostLoadTime),
                    this.views.push(t, n),
                    n.turning = _.bind(function() {
                        this.createViewPort(),
                        n.isLoaded || (r.onLoad(n.lostLoadTime), n.isLoaded = !0),
                        n.viewport = this.viewport,
                        this.startAnimation(function(e, t) {
                            $(".sub-viewport").hide(),
                            e.$el.show()
                        })
                    },
                    this),
                    this.curView = n;
                    var s = typeof i != "undefined" ? i.viewname: null;
                    this.curView.__load(s)
                })
            }
        },
        startAnimation: function(e) {
            var t = this.curView,
            n = this.lastView;
            n && (n.scrollPos = {
                x: window.scrollX,
                y: window.scrollY
            }),
            this.animatSwitch || (this.isAnimat = !1),
            this.isAnimat || (this.animatName = this.animNoName),
            this.timeoutres = this.animations[this.animatName] && this.animations[this.animatName].call(this, t, n, e, this),
            this.isAnimat = !0
        },
        loadView: function(e, t) {
            var n = this;
            requirejs([this.buildUrl(e)],
            function(e) {
                t && t.call(n, e)
            })
        },
        buildUrl: function(e) {
            var t = this.viewMapping[e];
            return t ? t: this.viewRootPath + e
        },
        createViewPort: function() {
            if (this.isCreate) return;
            var e = ['<div class="main-frame">', '<div class="main-viewport"></div>', '<div class="main-state"></div>', "</div>"].join("");
            this.mainframe = $(e),
            this.viewport = this.mainframe.find(".main-viewport"),
            this.statedom = this.mainframe.find(".main-state");
            var t = $("#main");
            t.empty(),
            t.append(this.mainframe),
            this.isCreate = !0
        },
        lastUrl: function() {
            return this.history.length < 2 ? document.referrer: this.history[this.history.length - 2]
        },
        startObserver: function() {
            this.stopListening = !1
        },
        endObserver: function() {
            this.stopListening = !0
        },
        forward: function(e, t, n) {
            e = e.toLowerCase(),
            n && (this.isAnimat = !1),
            this.endObserver(),
            t ? window.location.replace(("#" + e).replace(/^#+/, "#")) : window.location.href = ("#" + e).replace(/^#+/, "#"),
            this.pushHistory(),
            this._onHashChange(e, !0),
            setTimeout(_.bind(this.startObserver, this), 1)
        },
        back: function(e, t) {
            t && (this.isAnimat = !1);
            var n = this.lastUrl();
            n && this.history.pop(),
            e && (!n || n.indexOf(e) !== 0) ? window.location.href = ("#" + e).replace(/^#+/, "#") : (e = this.request.query.refer, e ? window.location.href = e: history.back())
        },
        pushHistory: function() {
            var e = window.location.href;
            this.history.push(e)
        }
    });
    return i
}),
define("cAbstractStorage", ["cBase"],
function(e) {
    var t = window.JSON,
    n = e.Date,
    r = new e.Class({
        __propertys__: function() {
            this.proxy = null
        },
        initialize: function($super, e) {},
        _buildStorageObj: function(e, t, n, r, i) {
            return {
                value: e,
                oldvalue: i || null,
                timeout: t,
                tag: n,
                savedate: r
            }
        },
        set: function(e, r, i, s, o, u) {
            o = o || (new n).format("Y/m/d H:i:s"),
            i = i ? new n(i) : (new n).addDay(30);
            var a = this._buildStorageObj(r, i.format("Y/m/d H:i:s"), s, o, u);
            try {
                return this.proxy.setItem(e, t.stringify(a)),
                !0
            } catch(f) {
                console && 0
            }
            return ! 1
        },
        get: function(e, r, i) {
            var s, o = null;
            try {
                s = this.proxy.getItem(e),
                s && (s = t.parse(s), n.parse(s.timeout, !0) >= new Date && (r ? r === s.tag && (o = i ? s.oldvalue: s.value) : o = i ? s.oldvalue: s.value))
            } catch(u) {
                console && 0
            }
            return o
        },
        getTag: function(e) {
            var n, r = null,
            i = null;
            try {
                n = this.proxy.getItem(e),
                n && (n = t.parse(n), i = n && n.tag)
            } catch(s) {
                console && 0
            }
            return i
        },
        getSaveDate: function(e, r) {
            var i, s = null;
            try {
                i = this.proxy.getItem(e),
                i && (i = t.parse(i), i.savedate && (s = n.parse(i.savedate), r || (s = s.valueOf())))
            } catch(o) {
                console && 0
            }
            return s
        },
        getExpireTime: function(e) {
            var n = null,
            r = null;
            try {
                n = this.proxy.getItem(e),
                n && (n = t.parse(n), r = Date.parse(n.timeout))
            } catch(i) {
                console && 0
            }
            return r
        },
        remove: function(e) {
            return this.proxy.removeItem(e)
        },
        getAll: function() {
            var e = this.proxy.length,
            t = [];
            for (var n = 0; n < e; n++) {
                var r = this.proxy.key(n),
                i = {
                    key: r,
                    value: this.get(r)
                };
                t.push(i)
            }
            return t
        },
        clear: function() {
            this.proxy.clear()
        },
        gc: function() {
            var e = this.proxy,
            t = this.proxy.length;
            for (var n = 0; n < t; n++) {
                var r = e.key(n);
                if (r == "GUID") break;
                try {
                    this.get(r) || this.remove(r)
                } catch(i) {}
            }
        }
    });
    return r
}),
define("cStorage", ["cBase", "cAbstractStorage"],
function(e, t) {
    var n = new e.Class(t, {
        __propertys__: function() {},
        initialize: function($super, e) {
            this.proxy = window.localStorage,
            $super(e)
        },
        oldGet: function(t) {
            var n = localStorage.getItem(t),
            r = n ? JSON.parse(n) : null;
            if (r && r.timeout) {
                var i = new Date,
                s = e.Date.parse(r.timeout).valueOf();
                if (r.timeby) {
                    if (s - i >= 0) return r
                } else if (s - e.Date.parse(e.Date.format(i, "Y-m-d")).valueOf() >= 0) return r;
                return localStorage.removeItem(t),
                null
            }
            return r
        },
        oldSet: function(e, t) {
            localStorage.setItem(e, t)
        },
        getExpireTime: function(t) {
            var n = localStorage.getItem(t),
            r = n ? JSON.parse(n) : null;
            return r && r.timeout ? r.timeout: (new e.Date(e.getServerDate())).addDay(2).format("Y-m-d")
        },
        oldRemove: function(e) {
            localStorage.removeItem(e)
        }
    });
    return n.getInstance = function() {
        return this.instance ? this.instance: this.instance = new this
    },
    n.localStorage = n.getInstance(),
    n
}),
define("cWidgetFactory", ["libs"],
function(e) {
    var t = t || {};
    return t.products = {},
    t.hasWidget = function(e) {
        return !! t.products[e]
    },
    t.register = function(e) {
        if (! (e && e.name && e.fn)) throw "WidgetFactory: widget is lack of necessary infomation.";
        if (t.products[e.name]) throw "WidgetFactory: widget has been register in WidgetFactory";
        t.products[e.name] = e.fn
    },
    t.create = function(e) {
        return t.products[e]
    },
    t
}),
define("cAbstractStore", ["cBase", "cStorage", "cUtility"],
function(e, t, n) {
    CDate = e.Date,
    HObject = n.Object;
    var r = new e.Class({
        __propertys__: function() {
            this.NULL = {},
            this.key = this.NULL,
            this.lifeTime = "30M",
            this.useServerTime = !1,
            this.defaultData = null,
            this.rollbackEnabled = !1,
            this.sProxy = this.NULL
        },
        initialize: function(e) {
            for (var t in e) this[t] = e[t];
            this.assert()
        },
        assert: function() {
            if (this.key === this.NULL) throw "not override key property"
        },
        set: function(e, t, n) {
            var r = this._getNowTime();
            r.addSeconds(this._getLifeTime()),
            this.rollbackEnabled && !n && (n = e),
            this.sProxy.set(this.key, e, r, t, null, n)
        },
        setLifeTime: function(e, t) {
            this.lifeTime = e;
            var n = this.getTag(),
            r = this.get(),
            i;
            t ? i = this._getNowTime() : i = this.sProxy.getSaveDate(this.key, !0) || this._getNowTime();
            var s = (new CDate(i.valueOf())).format("Y/m/d H:i:s");
            i.addSeconds(this._getLifeTime()),
            this.sProxy.set(this.key, r, i, n, s)
        },
        setAttr: function(e, t, n) {
            if (_.isObject(e)) {
                for (var r in e) e.hasOwnProperty(r) && this.setAttr(r, e[r], t);
                return
            }
            n = n || this.getTag();
            var i = this.get(n) || {},
            s = {};
            if (i) {
                if (this.rollbackEnabled) {
                    s = this.get(n, !0);
                    var o = HObject.get(i, e);
                    HObject.set(s, e, o)
                }
                return HObject.set(i, e, t),
                this.set(i, n, s)
            }
            return ! 1
        },
        get: function(t, n) {
            var r = null,
            i = !0;
            Object.prototype.toString.call(this.defaultData) === "[object Array]" ? r = this.defaultData.slice(0) : this.defaultData && (r = _.clone(this.defaultData));
            var s = this.sProxy.get(this.key, t, n),
            o = typeof s;
            if ({
                string: !0,
                number: !0,
                "boolean": !0
            } [o]) return s;
            if (s) if (Object.prototype.toString.call(s) == "[object Array]") {
                r = [];
                for (var u = 0,
                a = s.length; u < a; u++) r[u] = s[u]
            } else s && !r && (r = {}),
            e.extend(r, s);
            for (var f in r) {
                i = !1;
                break
            }
            return i ? null: r
        },
        getAttr: function(e, t) {
            var n = this.get(t),
            r = null;
            return n && (r = HObject.get(n, e)),
            r
        },
        getTag: function() {
            return this.sProxy.getTag(this.key)
        },
        remove: function() {
            this.sProxy.remove(this.key)
        },
        removeAttr: function(e) {
            var t = this.get() || {};
            t[e] && delete t[e],
            this.set(t)
        },
        getExpireTime: function() {
            var e = null;
            try {
                e = this.sProxy.getExpireTime(this.key)
            } catch(t) {
                console && 0
            }
            return e
        },
        setExpireTime: function(e) {
            var t = this.get(),
            n = new CDate(e);
            this.sProxy.set(this.key, t, n)
        },
        _getNowTime: function() {
            return this.useServerTime ? new CDate(e.getServerDate()) : new CDate
        },
        _getLifeTime: function() {
            var e = 0,
            t = this.lifeTime + "",
            n = t.charAt(t.length - 1),
            r = +t.substring(0, t.length - 1);
            return typeof n == "number" ? n = "M": n = n.toUpperCase(),
            n == "D" ? e = r * 24 * 60 * 60 : n == "H" ? e = r * 60 * 60 : n == "M" ? e = r * 60 : n == "S" ? e = r: e = r * 60,
            e
        },
        rollback: function(e) {
            if (this.rollbackEnabled) {
                var t = this.getTag(),
                n = this.sProxy.get(this.key, t),
                r = this.sProxy.get(this.key, t, !0);
                if (e && e instanceof Array) for (var i in e) {
                    var s = e[i],
                    o = r[s];
                    typeof o != "undefined" && (n[s] = o)
                } else n = r,
                r = {};
                this.set(n, t, r)
            }
        }
    });
    return r.getInstance = function() {
        return this.instance ? this.instance: this.instance = new this
    },
    r
}),
define("cStore", ["cBase", "cAbstractStore", "cStorage"],
function(e, t, n) {
    var r = new e.Class(t, {
        __propertys__: function() {
            this.sProxy = n.getInstance()
        },
        initialize: function($super, e) {
            $super(e)
        }
    });
    return r
}),
define("CommonStore", ["cBase", "cStore", "cStorage", "cUtility"],
function(e, t, n, r) {
    var i = {};
    return i.UserStore = new e.Class(t, {
        __propertys__: function() {
            this.key = "USER",
            this.lifeTime = "1D"
        },
        initialize: function($super, e) {
            $super(e)
        },
        getUser: function() {
            var e = n.localStorage.oldGet("USERINFO");
            return e = e && e.data || null,
            this.set(e),
            e
        },
        setUser: function(e) {
            var t = n.localStorage.getExpireTime("USERINFO"),
            r = {
                data: e,
                timeout: t
            };
            n.localStorage.oldSet("USERINFO", JSON.stringify(r)),
            this.set(e)
        },
        removeUser: function() {
            n.localStorage.oldRemove("USERINFO"),
            this.set(null)
        },
        isNonUser: function() {
            var e = this.getUser();
            return e && !!e.IsNonUser
        },
        isLogin: function() {
            var e = this.getUser();
            return e && !!e.Auth && !e.IsNonUser
        },
        getUserName: function() {
            var e = this.getUser();
            return e.UserName
        },
        getUserId: function() {
            var e = this.getUser() || {};
            return e.UserID || r.getGuid()
        },
        getAuth: function() {
            var e = i.HeadStore.getInstance(),
            t = this.getUser();
            return t && t.Auth && e.setAttr("auth", t.Auth),
            e.getAttr("auth")
        },
        setAuth: function(e) {
            var t = this.isLogin(),
            n = this.getUser() || {};
            n.Auth = e,
            n.IsNonUser = t ? !1 : !0,
            this.setUser(n)
        },
        setNonUser: function(e) {
            var t = n.localStorage.oldGet("USERINFO"),
            r = i.HeadStore.getInstance(),
            s = t && t.data || {};
            s.Auth = e,
            s.IsNonUser = !0,
            this.setUser(s),
            r.setAttr("auth", e)
        }
    }),
    i.HeadStore = new e.Class(t, {
        userStore: i.UserStore.getInstance(),
        __propertys__: function() {
            this.key = "HEADSTORE",
            this.lifeTime = "15D",
            this.defaultData = {
                cid: r.getGuid(),
                ctok: "351858059049938",
                cver: "1.0",
                lang: "01",
                sid: "8888",
                syscode: "09",
                auth: ""
            };
            var e = this.get;
            this.get = function() {
                var t = e.apply(this, arguments),
                n = this.userStore.getUser(),
                s = i.SalesObjectStore.getInstance().get();
                return r.isInApp() || (s && s.sid ? t.sid = s.sid: t.sid = "8888"),
                n && n.Auth ? t.auth = n.Auth: t.auth = "",
                this.set(t),
                t
            }
        },
        initialize: function($super, e) {
            $super(e)
        },
        setAuth: function(e) {
            var t = i.UserStore.getInstance();
            t.setAuth(e),
            this.setAttr("auth", e)
        }
    }),
    i.UnionStore = new e.Class(t, {
        __propertys__: function() {
            this.key = "UNION",
            this.lifeTime = "7D",
            this.store = n.localStorage
        },
        initialize: function($super, e) {
            $super(e)
        },
        get: function() {
            var e = this.store.oldGet(this.key);
            return e && e.data || null
        },
        set: function(t, n) {
            n || (n = new e.Date(r.getServerDate()), n.addSeconds(this._getLifeTime()));
            var i = {
                data: t,
                timeout: n.format("Y/m/d H:i:s")
            };
            this.store.oldSet(this.key, JSON.stringify(i))
        }
    }),
    i.SalesStore = new e.Class(t, {
        __propertys__: function() {
            this.key = "SALES",
            this.lifeTime = "30D",
            this.store = n.localStorage
        },
        initialize: function($super, e) {
            $super(e)
        },
        get: function() {
            var e = this.store.oldGet(this.key);
            return e && e.data || null
        },
        set: function(t, n) {
            n || (n = new e.Date(r.getServerDate()), n.addSeconds(this._getLifeTime()));
            var i = {
                data: t,
                timeout: n.format("Y/m/d H:i:s")
            };
            this.store.oldSet(this.key, JSON.stringify(i))
        }
    }),
    i.SalesObjectStore = new e.Class(t, {
        __propertys__: function() {
            this.key = "SALES_OBJECT",
            this.lifeTime = "30D"
        },
        initialize: function($super, e) {
            $super(e)
        }
    }),
    i.UnionStore.getInstance = i.SalesStore.getInstance = e.getInstance,
    i
}),
define("cHybridFacade", ["libs", "CommonStore", "cUtility"],
function(libs, CommonStore, cUtility) {
    var Facade = Facade || {};
    Facade.METHOD_ENTRY = "METHOD_ENTRY",
    Facade.METHOD_MEMBER_LOGIN = "METHOD_MEMBER_LOGIN",
    Facade.METHOD_NON_MEMBER_LOGIN = "METHOD_NON_MEMBER_LOGIN",
    Facade.METHOD_AUTO_LOGIN = "METHOD_AUTO_LOGIN",
    Facade.METHOD_LOCATE = "METHOD_LOCATE",
    Facade.METHOD_REFRESH_NAV_BAR = "METHOD_REFRESH_NAV_BAR",
    Facade.METHOD_CALL_PHONE = "METHOD_CALL_PHONE",
    Facade.METHOD_BACK_TO_HOME = "METHOD_BACK_TO_HOME",
    Facade.METHOD_BACK_TO_BOOK_CAR = "METHOD_BACK_TO_BOOK_CAR",
    Facade.METHOD_BACK = "METHOD_BACK",
    Facade.METHOD_COMMIT = "METHOD_COMMIT",
    Facade.METHOD_CITY_CHOOSE = "METHOD_CITY_CHOOSE",
    Facade.METHOD_REGISTER = "METHOD_REGISTER",
    Facade.METHOD_LOG_EVENT = "METHOD_LOG_EVENT",
    Facade.METHOD_INIT = "METHOD_INIT",
    Facade.METHOD_CALL_SERVICE_CENTER = "METHOD_CALL_SERVICE_CENTER",
    Facade.METHOD_BACK_TO_LAST_PAGE = "METHOD_BACK_TO_LAST_PAGE",
    Facade.METHOD_GO_TO_BOOK_CAR_FINISHED_PAGE = "METHOD_GO_TO_BOOK_CAR_FINISHED_PAGE",
    Facade.METHOD_GO_TO_HOTEL_DETAIL = "METHOD_GO_TO_HOTEL_DETAIL",
    Facade.METHOD_OPEN_URL = "METHOD_OPEN_URL",
    Facade.METHOD_CHECK_UPDATE = "METHOD_CHECK_UPDATE",
    Facade.METHOD_RECOMMEND_APP_TO_FRIEND = "METHOD_RECOMMEND_APP_TO_FRIEND",
    Facade.METHOD_ADD_WEIXIN_FRIEND = "METHOD_ADD_WEIXIN_FRIEND",
    Facade.METHOD_SHOW_NEWEST_INTRODUCTION = "METHOD_SHOW_NEWEST_INTRODUCTION",
    Facade.METHOD_BECOME_ACTIVE = "METHOD_BECOME_ACTIVE",
    Facade.METHOD_WEB_VIEW_FINISHED_LOAD = "METHOD_WEB_VIEW_FINISHED_LOAD",
    Facade.METHOD_CROSS_DOMAIN_HREF = "METHOD_CROSS_DOMAIN_HREF",
    Facade.METHOD_CHECK_APP_INSTALL = "METHOD_CHECK_APP_INSTALL",
    Facade.METHOD_CROSS_JUMP = "METHOD_CROSS_JUMP",
    Facade.METHOD_REFRESH_NATIVE = "METHOD_REFRESH_NATIVE",
    Facade.METHOD_H5_NEED_REFRESH = "METHOD_H5_NEED_REFRESH",
    Facade.METHOD_READ_FROM_CLIPBOARD = "METHOD_READ_FROM_CLIPBOARD",
    Facade.METHOD_COPY_TO_CLIPBOARD = "METHOD_COPY_TO_CLIPBOARD",
    Facade.METHOD_SHARE_TO_VENDOR = "METHOD_SHARE_TO_VENDOR",
    Facade.METHOD_DOWNLOAD_DATA = "METHOD_DOWNLOAD_DATA",
    Facade.METHOD_NATIVE_LOG = "METHOD_NATIVE_LOG",
    Facade.METHOD_SEND_H5_PIPE_REQUEST = "METHOD_SEND_H5_PIPE_REQUEST",
    Facade.METHOD_SEND_HTTP_PIPE_REQUEST = "METHOD_SEND_HTTP_PIPE_REQUEST",
    Facade.METHOD_CHECK_PAY_APP_INSTALL_STATUS = "METHOD_CHECK_PAY_APP_INSTALL_STATUS",
    Facade.METHOD_OPEN_PAY_APP_BY_URL = "METHOD_OPEN_PAY_APP_BY_URL",
    Facade.METHOD_SET_NAVBAR_HIDDEN = "METHOD_SET_NAVBAR_HIDDEN",
    Facade.METHOD_SET_TOOLBAR_HIDDEN = "METHOD_SET_TOOLBAR_HIDDEN",
    Facade.METHOD_CHECK_FILE_EXIST = "METHOD_CHECK_FILE_EXIST",
    Facade.METHOD_DELETE_FILE = "METHOD_DELETE_FILE",
    Facade.METHOD_GET_CURRENT_SANDBOX_NAME = "METHOD_GET_CURRENT_SANDBOX_NAME",
    Facade.METHOD_GET_FILE_SIZE = "METHOD_GET_FILE_SIZE",
    Facade.METHOD_MAKE_DIR = "METHOD_MAKE_DIR",
    Facade.METHOD_READ_TEXT_FROM_FILE = "METHOD_READ_TEXT_FROM_FILE",
    Facade.METHOD_WRITE_TEXT_TO_FILE = "METHOD_WRITE_TEXT_TO_FILE",
    Facade.METHOD_ABORT_HTTP_PIPE_REQUEST = "METHOD_ABORT_HTTP_PIPE_REQUEST",
    Facade.METHOD_OPEN_ADV_PAGE = "METHOD_OPEN_ADV_PAGE",
    Facade.METHOD_WEB_VEW_DID_APPEAR = "METHOD_WEB_VEW_DID_APPEAR",
    Facade.METHOD_SHOW_MAP = "METHOD_SHOW_MAP",
    Facade.METHOD_ENCRYPT_BASE64 = "METHOD_ENCRYPT_BASE64",
    Facade.METHOD_ENCRYPT_CTRIP = "METHOD_ENCRYPT_CTRIP",
    Facade.METHOD_APP_CHOOSE_INVOICE_TITLE = "METHOD_APP_CHOOSE_INVOICE_TITLE",
    Facade.METHOD_APP_GET_DEVICE_INFO = "METHOD_APP_GET_DEVICE_INFO",
    Facade.METHOD_APP_SHOW_VOICE_SEARCH = "METHOD_APP_SHOW_VOICE_SEARCH",
    Facade.METHOD_APP_CHOOSE_PHOTO = "METHOD_APP_CHOOSE_PHOTO",
    Facade.METHOD_APP_FINISHED_REGISTER = "METHOD_APP_FINISHED_REGISTER",
    Facade.METHOD_APP_CALL_SYSTEM_SHARE = "METHOD_APP_CALL_SYSTEM_SHARE";
    var METHOD_ENTRY = "h5_init_finished",
    METHOD_MEMBER_LOGIN = "member_login",
    METHOD_NON_MEMBER_LOGIN = "non_member_login",
    METHOD_AUTO_LOGIN = "member_auto_login",
    METHOD_LOCATE = "locate",
    METHOD_REFRESH_NAV_BAR = "refresh_nav_bar",
    METHOD_BACK = "back",
    METHOD_COMMIT = "commit",
    METHOD_CITY_CHOOSE = "cityChoose",
    METHOD_REGISTER = "member_register",
    METHOD_INIT = "init_member_H5_info",
    METHOD_BECOME_ACTIVE = "become_active",
    METHOD_WEB_VIEW_FINISHED_LOAD = "web_view_finished_load",
    METHOD_CHECK_APP_INSTALL = "check_app_install_status",
    METHOD_H5_NEED_REFRESH = "app_h5_need_refresh",
    METHOD_READ_FROM_CLIPBOARD = "read_copied_string_from_clipboard",
    METHOD_DOWNLOAD_DATA = "download_data",
    METHOD_SEND_H5_PIPE_REQUEST = "send_h5_pipe_request",
    METHOD_SEND_HTTP_PIPE_REQUEST = "send_http_pipe_request",
    METHOD_CHECK_PAY_APP_INSTALL_STATUS = "check_pay_app_install_status",
    METHOD_CHECK_FILE_EXIST = "check_file_exist",
    METHOD_DELETE_FILE = "delete_file",
    METHOD_GET_CURRENT_SANDBOX_NAME = "get_current_sandbox_name",
    METHOD_GET_FILE_SIZE = "get_file_size",
    METHOD_MAKE_DIR = "make_dir",
    METHOD_READ_TEXT_FROM_FILE = "read_text_from_file",
    METHOD_WRITE_TEXT_TO_FILE = "write_text_to_file",
    METHOD_WEB_VEW_DID_APPEAR = "web_view_did_appear",
    METHOD_ENCRYPT_BASE64 = "base64_encode",
    METHOD_ENCRYPT_CTRIP = "ctrip_encrypt",
    METHOD_APP_CHOOSE_INVOICE_TITLE = "choose_invoice_title",
    METHOD_APP_GET_DEVICE_INFO = "get_device_info",
    METHOD_APP_CHOOSE_PHOTO = "choose_photo",
    isYouth = cUtility.getAppSys() == "youth",
    appLock = !1,
    defaultRegisterHandler = {},
    defaultCallback = {},
    defaultHandler = {},
    loginMethods = [METHOD_NON_MEMBER_LOGIN, METHOD_MEMBER_LOGIN, METHOD_AUTO_LOGIN, METHOD_REGISTER, METHOD_INIT];
    for (var p in Facade) if (p.indexOf("METHOD_") == 0) try {
        defaultRegisterHandler[p] = function(options) {
            var methoName = eval(options.tagname);
            defaultCallback[methoName] = function(e) {
                e && typeof e == "string" && (e = JSON.parse(e));
                if (_.indexOf(loginMethods, methoName) >= 0 && e && e.data) {
                    var t = CommonStore.UserStore.getInstance(),
                    n = t.getUser();
                    t.setUser(e.data);
                    var r = CommonStore.HeadStore.getInstance(),
                    i = r.get();
                    i.auth = e.data.Auth,
                    r.set(i)
                }
                if (methoName == METHOD_INIT) {
                    if (e && e.device) {
                        var s = {
                            device: e.device
                        };
                        window.localStorage.setItem("DEVICEINFO", JSON.stringify(s))
                    }
                    if (e && e.appId) {
                        var o = {
                            version: e.version,
                            appId: e.appId,
                            serverVersion: e.serverVersion,
                            platform: e.platform
                        };
                        window.localStorage.setItem("APPINFO", JSON.stringify(o))
                    }
                    e && e.timestamp && window.localStorage.setItem("SERVERDATE", e.timestamp),
                    e && e.sourceId && window.localStorage.setItem("SOURCEID", e.sourceId),
                    e && e.isPreProduction && window.localStorage.setItem("isPreProduction", e.isPreProduction)
                }
                if (methoName == METHOD_LOCATE) try {
                    options.success(e)
                } catch(u) {
                    options.error(!0, "定位失败")
                } else options && typeof options.callback == "function" && options.callback(e)
            },
            defaultHandler[methoName] = function(e) {
                typeof defaultCallback[methoName] == "function" && defaultCallback[methoName](e)
            }
        }
    } catch(e) {}
    var defaultFn = {
        callback: function(e) {
            if (appLock) return;
            var t = e;
            if (typeof e == "string") try {
                t = JSON.parse(window.decodeURIComponent(e))
            } catch(n) {
                setTimeout(function() {
                    0
                },
                0)
            }
            if (typeof defaultHandler[t.tagname] == "function") return defaultHandler[t.tagname](t.param),
            !0
        }
    },
    _registerFn = function(e) {
        for (var t in defaultFn) e[t] = e[t] || defaultFn[t]
    };
    return Facade.init = function() {
        var e = window.app = {};
        _registerFn(e)
    },
    Facade.register = function(e) {
        typeof defaultRegisterHandler[e.tagname] == "function" && defaultRegisterHandler[e.tagname](e)
    },
    Facade.unregister = function(e) {
        Facade.register({
            tagname: e,
            callback: function() {}
        })
    },
    Facade.request = function(e) {
        var t = {
            METHOD_INIT: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_INIT,
                    callback: e.callback
                }),
                CtripUtil.app_init_member_H5_info()
            },
            METHOD_ENTRY: function(e) {
                return
            },
            METHOD_MEMBER_LOGIN: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_MEMBER_LOGIN,
                    callback: e.callback
                }),
                CtripUser.app_member_login(e.isShowNonMemberLogin)
            },
            METHOD_NON_MEMBER_LOGIN: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_NON_MEMBER_LOGIN,
                    callback: e.callback
                }),
                CtripUser.app_non_member_login()
            },
            METHOD_AUTO_LOGIN: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_AUTO_LOGIN,
                    callback: e.callback
                }),
                CtripUser.app_member_auto_login()
            },
            METHOD_REGISTER: function(e) {
                Facade.register({
                    tagname: Facade.ETHOD_REGISTER,
                    callback: e.callback
                }),
                CtripUser.app_member_register()
            },
            METHOD_LOCATE: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_LOCATE,
                    success: e.success,
                    error: e.error
                });
                var t = !0;
                e.isAsync && (t = e.isAsync),
                CtripMap.app_locate(t)
            },
            METHOD_REFRESH_NAV_BAR: function(e) {
                CtripBar.app_refresh_nav_bar(e.config)
            },
            METHOD_CALL_PHONE: function(e) {
                CtripUtil.app_call_phone(e.tel)
            },
            METHOD_BACK_TO_HOME: function(e) {
                CtripUtil.app_back_to_home()
            },
            METHOD_BACK_TO_BOOK_CAR: function(e) {
                app_back_to_book_car()
            },
            METHOD_LOG_EVENT: function(e) {
                CtripUtil.app_log_event(e.event_name)
            },
            METHOD_CALL_SERVICE_CENTER: function() {
                CtripUtil.app_call_phone()
            },
            METHOD_BACK_TO_LAST_PAGE: function(e) {
                var t = e.param || "";
                CtripUtil.app_back_to_last_page(t)
            },
            METHOD_GO_TO_BOOK_CAR_FINISHED_PAGE: function(e) {
                CtripUtil.app_go_to_book_car_finished_page(e.url)
            },
            METHOD_GO_TO_HOTEL_DETAIL: function(e) {
                CtripUtil.app_go_to_hotel_detail(e.hotelId, e.hotelName, e.cityId, e.isOverSea)
            },
            METHOD_OPEN_URL: function(e) {
                var t = e.title || "",
                n = e.pageName || "";
                CtripUtil.app_open_url(e.openUrl, e.targetMode, t, n)
            },
            METHOD_CHECK_UPDATE: function(e) {
                CtripUtil.app_check_update()
            },
            METHOD_RECOMMEND_APP_TO_FRIEND: function() {
                CtripUtil.app_recommend_app_to_friends()
            },
            METHOD_ADD_WEIXIN_FRIEND: function() {
                CtripUtil.app_add_weixin_friend()
            },
            METHOD_CROSS_DOMAIN_HREF: function(e) {
                CtripUtil.app_cross_domain_href(e.moduleType, e.anchor, e.param)
            },
            METHOD_SHOW_NEWEST_INTRODUCTION: function(e) {
                CtripUtil.app_show_newest_introduction()
            },
            METHOD_CHECK_APP_INSTALL: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_CHECK_APP_INSTALL,
                    callback: e.callback
                }),
                CtripUtil.app_check_app_install_status(e.url, e.package)
            },
            METHOD_CROSS_JUMP: function(e) {
                CtripUtil.app_cross_package_href(e.path, e.param)
            },
            METHOD_REFRESH_NATIVE: function(e) {
                CtripUtil.app_refresh_native_page(e.package, e.json)
            },
            METHOD_READ_FROM_CLIPBOARD: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_READ_FROM_CLIPBOARD,
                    callback: e.callback
                }),
                CtripUtil.app_read_copied_string_from_clipboard()
            },
            METHOD_COPY_TO_CLIPBOARD: function(e) {
                CtripUtil.app_copy_string_to_clipboard(e.content)
            },
            METHOD_SHARE_TO_VENDOR: function(e) {
                var t = e.title || "",
                n = e.linkUrl || "",
                r = e.isIOSSystemShare || !1;
                CtripUtil.app_call_system_share(e.imgUrl, e.text, t, n, r)
            },
            METHOD_DOWNLOAD_DATA: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_DOWNLOAD_DATA,
                    callback: e.callback
                }),
                CtripUtil.app_download_data(e.url, e.suffix)
            },
            METHOD_NATIVE_LOG: function(e) {
                var t = window.localStorage.getItem("isPreProduction");
                t && t !== "" && CtripTool.app_log("@[Wireless H5] " + e.log, e.result)
            },
            METHOD_SEND_H5_PIPE_REQUEST: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_SEND_H5_PIPE_REQUEST,
                    callback: e.callback
                });
                var t = e.pipeType || "";
                CtripPipe.app_send_H5_pipe_request(e.serviceCode, e.header, e.data, e.sequenceId, t)
            },
            METHOD_SEND_HTTP_PIPE_REQUEST: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_SEND_HTTP_PIPE_REQUEST,
                    callback: e.callback
                }),
                CtripPipe.app_send_HTTP_pipe_request(e.target, e.methods, e.header, e.queryData, e.retryInfo, e.sequenceId)
            },
            METHOD_ABORT_HTTP_PIPE_REQUEST: function(e) {
                CtripPipe.app_abort_HTTP_pipe_request(e.sequenceId)
            },
            METHOD_CHECK_PAY_APP_INSTALL_STATUS: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_CHECK_PAY_APP_INSTALL_STATUS,
                    callback: e.callback
                }),
                CtripPay.app_check_pay_app_install_status()
            },
            METHOD_OPEN_PAY_APP_BY_URL: function(e) {
                CtripPay.app_open_pay_app_by_url(e.payAppName, e.payURL, e.successRelativeURL, e.detailRelativeURL)
            },
            METHOD_SET_NAVBAR_HIDDEN: function(e) {
                CtripBar.app_set_navbar_hidden(e.isNeedHidden)
            },
            METHOD_SET_TOOLBAR_HIDDEN: function(e) {
                CtripBar.app_set_toolbar_hidden(e.isNeedHidden)
            },
            METHOD_CHECK_FILE_EXIST: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_CHECK_FILE_EXIST,
                    callback: e.callback
                }),
                CtripFile.app_check_file_exist(e.fileName, e.relativeFilePath)
            },
            METHOD_DELETE_FILE: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_DELETE_FILE,
                    callback: e.callback
                }),
                CtripFile.app_delete_file(e.fileName, e.relativeFilePath)
            },
            METHOD_GET_CURRENT_SANDBOX_NAME: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_GET_CURRENT_SANDBOX_NAME,
                    callback: e.callback
                }),
                CtripFile.app_get_current_sandbox_name()
            },
            METHOD_GET_FILE_SIZE: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_GET_FILE_SIZE,
                    callback: callback
                }),
                CtripFile.app_get_file_size(e.fileName, e.relativeFilePath)
            },
            METHOD_MAKE_DIR: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_MAKE_DIR,
                    callback: callback
                }),
                CtripFile.app_make_dir(e.dirname, e.relativeFilePath)
            },
            METHOD_READ_TEXT_FROM_FILE: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_READ_TEXT_FROM_FILE,
                    callback: callback
                }),
                CtripFile.app_read_text_from_file(e.fileName, e.relativeFilePath)
            },
            METHOD_WRITE_TEXT_TO_FILE: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_WRITE_TEXT_TO_FILE,
                    callback: callback
                }),
                CtripFile.app_write_text_to_file(e.text, e.fileName, e.relativeFilePath, e.isAppend)
            },
            METHOD_OPEN_ADV_PAGE: function(e) {
                CtripUtil.app_open_adv_page(e.url)
            },
            METHOD_SHOW_MAP: function(e) {
                CtripMap.app_show_map(e.latitude, e.longitude, e.title, e.subtitle)
            },
            METHOD_ENCRYPT_BASE64: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_ENCRYPT_BASE64,
                    callback: e.callback
                }),
                CtripEncrypt.app_base64_encode(e.info)
            },
            METHOD_ENCRYPT_CTRIP: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_ENCRYPT_CTRIP,
                    callback: e.callback
                }),
                CtripEncrypt.app_ctrip_encrypt(e.inString, e.encType)
            },
            METHOD_APP_CHOOSE_INVOICE_TITLE: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_APP_CHOOSE_INVOICE_TITLE,
                    callback: e.callback
                }),
                CtripBusiness.app_choose_invoice_title(e.title)
            },
            METHOD_APP_GET_DEVICE_INFO: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_APP_GET_DEVICE_INFO,
                    callback: e.callback
                }),
                CtripBusiness.app_get_device_info()
            },
            METHOD_APP_SHOW_VOICE_SEARCH: function(e) {
                CtripBusiness.app_show_voice_search(e.bussinessType)
            },
            METHOD_APP_CHOOSE_PHOTO: function(e) {
                Facade.register({
                    tagname: Facade.METHOD_APP_CHOOSE_PHOTO,
                    callback: e.callback
                });
                var t = e.maxFileSize || 200,
                n = e.maxPhotoCount || 1;
                CtripUtil.app_choose_photo(t, n)
            },
            METHOD_APP_FINISHED_REGISTER: function(e) {
                CtripUser.app_finished_register(e.userInfo)
            },
            METHOD_APP_CALL_SYSTEM_SHARE: function(e) {
                CtripUtil.app_call_system_share(e.imageRelativePath, e.text, e.title, e.linkUrl, e.isIOSSystemShare)
            }
        };
        t[e.name](e)
    },
    Facade.getOpenUrl = function(e) {
        var t = isYouth ? "ctripyouth": "ctrip",
        n = t + "://wireless/" + e.module + "?";
        return _.each(e.param,
        function(e, t, r) {
            n += t + "=" + e + "&"
        }),
        n[n.length - 1] === "&" && (n = n.slice(0, n.length - 1)),
        n
    },
    Facade
}),
define("cWidgetGuider", ["cUtilityHybrid", "cWidgetFactory", "cHybridFacade"],
function(e, t, n) {
    var r = "Guider",
    i = {
        jump: function(e) {
            var t = {
                refresh: function() {
                    n.request({
                        name: n.METHOD_OPEN_URL,
                        targetMode: 0,
                        title: e.title,
                        pageName: e.pageName
                    })
                },
                app: function() {
                    if (e && e.module) {
                        var t = n.getOpenUrl(e);
                        n.request({
                            name: n.METHOD_OPEN_URL,
                            openUrl: t,
                            targetMode: 1,
                            title: e.title,
                            pageName: e.pageName
                        })
                    } else e && e.url && n.request({
                        name: n.METHOD_OPEN_URL,
                        openUrl: e.url,
                        targetMode: 1,
                        title: e.title,
                        pageName: e.pageName
                    })
                },
                h5: function() {
                    e && e.url && n.request({
                        name: n.METHOD_OPEN_URL,
                        openUrl: e.url,
                        targetMode: 2,
                        title: e.title,
                        pageName: e.pageName
                    })
                },
                browser: function() {
                    e && e.url && n.request({
                        name: n.METHOD_OPEN_URL,
                        openUrl: e.url,
                        targetMode: 3,
                        title: e.title,
                        pageName: e.pageName
                    })
                },
                open: function() {
                    e && e.url && n.request({
                        name: n.METHOD_OPEN_URL,
                        openUrl: e.url,
                        targetMode: 4,
                        title: e.title,
                        pageName: e.pageName
                    })
                }
            };
            typeof t[e.targetModel] == "function" && t[e.targetModel]()
        },
        apply: function(e) {
            _.isObject(e) && _.isFunction(e.hybridCallback) && e.hybridCallback()
        },
        call: function(e) {
            return ! 1
        },
        init: function(e) {
            e && window.parseFloat(e.version) < 5.2 ? n.request({
                name: n.METHOD_ENTRY,
                callback: e.callback
            }) : n.request({
                name: n.METHOD_INIT,
                callback: e.callback
            })
        },
        log: function(e) {
            n.request({
                name: n.METHOD_LOG_EVENT,
                event_name: e.name
            })
        },
        print: function(e) {
            n.request({
                name: n.METHOD_NATIVE_LOG,
                log: e.log,
                result: e.result
            })
        },
        callService: function() {
            n.request({
                name: n.METHOD_CALL_SERVICE_CENTER
            })
        },
        backToLastPage: function(e) {
            var t = e ? e.param: "";
            n.request({
                name: n.METHOD_BACK_TO_LAST_PAGE,
                param: t
            })
        },
        checkUpdate: function() {
            n.request({
                name: n.METHOD_CHECK_UPDATE
            })
        },
        recommend: function() {
            n.request({
                name: n.METHOD_RECOMMEND_APP_TO_FRIEND
            })
        },
        addWeixinFriend: function() {
            n.request({
                name: n.METHOD_ADD_WEIXIN_FRIEND
            })
        },
        showNewestIntroduction: function() {
            n.request({
                name: n.METHOD_SHOW_NEWEST_INTRODUCTION
            })
        },
        register: function(e) {
            e && e.tagname && e.callback && n.register({
                tagname: e.tagname,
                callback: e.callback
            })
        },
        create: function() {
            n.init()
        },
        home: function() {
            n.request({
                name: n.METHOD_BACK_TO_HOME
            })
        },
        jumpHotel: function(e) {
            n.request({
                name: n.METHOD_GO_TO_HOTEL_DETAIL,
                hotelId: e.hotelId,
                hotelName: e.name,
                cityId: e.cityId,
                isOverSea: e.isOverSea
            })
        },
        injectUbt: function() {
            return ! 1
        },
        checkAppInstall: function(e) {
            n.request({
                name: n.METHOD_CHECK_APP_INSTALL,
                url: e.url,
                "package": e.package,
                callback: e.callback
            })
        },
        callPhone: function(e) {
            n.request({
                name: n.METHOD_CALL_PHONE,
                tel: e.tel
            })
        },
        cross: function(e) {
            n.request({
                name: n.METHOD_CROSS_JUMP,
                param: e.param,
                path: e.path
            })
        },
        refreshNative: function(e) {
            n.request({
                name: n.METHOD_REFRESH_NATIVE,
                "package": e.package,
                json: e.json
            })
        },
        copyToClipboard: function(e) {
            n.request({
                name: n.METHOD_COPY_TO_CLIPBOARD,
                content: e.content
            })
        },
        readFromClipboard: function(e) {
            n.request({
                name: n.METHOD_READ_FROM_CLIPBOARD,
                callback: e.callback
            })
        },
        shareToVendor: function(e) {
            n.request({
                name: n.METHOD_SHARE_TO_VENDOR,
                imgUrl: e.imgUrl,
                text: e.text,
                title: e.title,
                linkUrl: e.linkUrl,
                isIOSSystemShare: e.isIOSSystemShare
            })
        },
        downloadData: function(e) {
            n.request({
                name: n.METHOD_DOWNLOAD_DATA,
                url: e.url,
                callback: e.callback,
                suffix: e.suffix
            })
        },
        encode: function(e) {
            e && e.mode === "base64" && n.request({
                name: n.METHOD_ENCRYPT_BASE64,
                callback: e.callback,
                info: e.info
            })
        },
        choose_invoice_title: function(e) {
            n.request({
                name: n.METHOD_APP_CHOOSE_INVOICE_TITLE,
                callback: e.callback,
                title: e.title
            })
        },
        get_device_info: function(e) {
            n.request({
                name: n.METHOD_APP_GET_DEVICE_INFO,
                callback: e.callback
            })
        },
        show_voice_search: function(e) {
            n.request({
                name: n.METHOD_APP_SHOW_VOICE_SEARCH,
                bussinessType: e.bussinessType
            })
        },
        choose_photo: function(e) {
            n.request({
                name: n.METHOD_APP_CHOOSE_PHOTO,
                maxFileSize: e.maxFileSize,
                maxPhotoCount: e.maxPhotoCount,
                callback: e.callback
            })
        },
        finished_register: function(e) {
            n.request({
                name: n.METHOD_APP_FINISHED_REGISTER,
                userInfo: e.userInfo
            })
        },
        app_call_system_share: function(e) {
            n.request({
                name: n.METHOD_APP_FINISHED_REGISTER,
                imageRelativePath: e.imageRelativePath,
                text: e.text,
                title: e.title,
                linkUrl: e.linkUrl,
                isIOSSystemShare: e.isIOSSystemShare
            })
        }
    };
    i.file = {
        isFileExist: function(e) {
            n.request({
                name: n.METHOD_CHECK_FILE_EXIST,
                callback: e.callback,
                fileName: e.fileName,
                relativeFilePath: e.relativeFilePath
            })
        },
        deleteFile: function(e) {
            n.request({
                name: n.METHOD_DELETE_FILE,
                callback: e.callback,
                fileName: e.fileName,
                relativeFilePath: e.relativeFilePath
            })
        },
        getCurrentSandboxName: function(e) {
            n.request({
                name: n.METHOD_GET_CURRENT_SANDBOX_NAME,
                callback: e.callback
            })
        },
        getFileSize: function(e) {
            n.request({
                name: n.METHOD_GET_FILE_SIZE,
                callback: e.callback,
                fileName: e.fileName,
                relativeFilePath: e.relativeFilePath
            })
        },
        makeDir: function(e) {
            n.request({
                name: n.METHOD_MAKE_DIR,
                callback: e.callback,
                dirname: e.dirname,
                relativeFilePath: e.relativeFilePath
            })
        },
        readTextFromFile: function(e) {
            n.request({
                name: n.METHOD_READ_TEXT_FROM_FILE,
                callback: e.callback,
                fileName: e.fileName,
                relativeFilePath: e.relativeFilePath
            })
        },
        writeTextToFile: function(e) {
            n.request({
                name: n.METHOD_WRITE_TEXT_TO_FILE,
                callback: e.callback,
                text: e.text,
                isAppend: e.isAppend,
                fileName: e.fileName,
                relativeFilePath: e.relativeFilePath
            })
        }
    },
    i.pipe = {
        socketRequest: function(e) {
            n.request({
                name: n.METHOD_SEND_H5_PIPE_REQUEST,
                callback: e.callback,
                serviceCode: e.serviceCode,
                header: e.header,
                data: e.data,
                sequenceId: Date.now(),
                pipeType: e.pipeType
            })
        },
        httpRequest: function(e) {
            var t = Date.now();
            return n.request({
                name: n.METHOD_SEND_HTTP_PIPE_REQUEST,
                callback: e.callback,
                target: e.url,
                method: e.method,
                header: e.header,
                queryData: e.param,
                retryInfo: e.retry,
                sequenceId: t
            }),
            t
        },
        abortRequest: function(e) {
            n.request({
                name: n.METHOD_ABORT_HTTP_PIPE_REQUEST,
                sequenceId: e.id
            })
        }
    },
    i.pay = {
        checkStatus: function(e) {
            n.request({
                name: n.METHOD_CHECK_PAY_APP_INSTALL_STATUS,
                callback: e.callback
            })
        },
        payOut: function(e) {
            n.request({
                name: n.METHOD_OPEN_PAY_APP_BY_URL,
                payAppName: e.payAppName,
                payURL: e.payURL,
                successRelativeURL: e.successRelativeURL,
                detailRelativeURL: e.detailRelativeURL
            })
        }
    },
    i.encrypt = {
        ctrip_encrypt: function(e) {
            n.request({
                name: n.METHOD_ENCRYPT_CTRIP,
                callback: e.callback,
                inString: e.inStr,
                encType: 1
            })
        },
        ctrip_decrypt: function(e) {
            n.request({
                name: n.METHOD_ENCRYPT_CTRIP,
                callback: e.callback,
                inString: e.inStr,
                encType: 2
            })
        }
    };
    var s = {
        jump: function(e) {
            e && e.url && typeof e.url == "string" && (window.location.href = e.url)
        },
        apply: function(e) {
            e && e.callback && typeof e.callback == "function" && e.callback()
        },
        call: function(e) {
            var t = document.getElementById("h5-hybrid-caller");
            if (!e || !e.url || !typeof e.url === "string") return ! 1;
            t && t.src == e.url ? t.contentDocument.location.reload() : t && t.src != e.url ? t.src = e.url: (t = document.createElement("iframe"), t.id = "h5-hybrid-caller", t.src = e.url, t.style.display = "none", document.body.appendChild(t))
        },
        init: function() {
            return ! 1
        },
        log: function(e) {
            window.console && window.console.log(e.name)
        },
        print: function(e) {
            return 0
        },
        callService: function() {
            window.location.href = "tel:4000086666"
        },
        backToLastPage: function() {
            window.location.href = document.referrer
        },
        checkUpdate: function() {
            return ! 1
        },
        recommend: function() {
            return ! 1
        },
        addWeixinFriend: function() {
            return ! 1
        },
        showNewestIntroduction: function() {
            return ! 1
        },
        register: function() {
            return ! 1
        },
        create: function() {
            return ! 1
        },
        home: function() {
            window.location.href = "/"
        },
        jumpHotel: function() {
            return ! 1
        },
        injectUbt: function() {
            return ! 1
        },
        checkAppInstall: function() {
            return ! 1
        },
        callPhone: function() {
            return ! 1
        },
        cross: function() {
            return ! 1
        },
        refreshNative: function() {
            return ! 1
        },
        copyToClipboard: function(e) {
            return ! 1
        },
        readFromClipboard: function(e) {
            return ! 1
        },
        shareToVendor: function(e) {
            return ! 1
        },
        downloadData: function(e) {
            return ! 1
        },
        encode: function(e) {
            return ! 1
        }
    };
    s.file = {
        isFileExist: function(e) {
            return ! 1
        },
        deleteFile: function(e) {
            return ! 1
        },
        getCurrentSandboxName: function(e) {
            return ! 1
        },
        getFileSize: function(e) {
            return ! 1
        },
        makeDir: function(e) {
            return ! 1
        },
        readTextFromFile: function(e) {
            return ! 1
        },
        writeTextToFile: function(e) {
            return ! 1
        }
    },
    s.pipe = {
        socketRequest: function() {
            return ! 1
        },
        httpRequest: function() {
            return ! 1
        },
        abortRequest: function() {
            return ! 1
        }
    },
    s.pay = {
        checkStatus: function() {
            return ! 1
        },
        payOut: function() {
            return ! 1
        }
    },
    s.encrypt = {
        ctrip_encrypt: function() {
            return ! 1
        },
        ctrip_decrypt: function() {
            return ! 1
        }
    },
    t.register({
        name: r,
        fn: e.isInApp() ? i: s
    })
}),
define("App", ["libs", "cBase", "AbstractAPP", "cStorage", "cWidgetFactory", "cWidgetGuider"],
function(e, t, n, r, i) { (function() {
        function s(e, t, n, r, i) {
            var s = Math.abs(e - t),
            o = Math.abs(n - r),
            u = s >= o ? e - t > 0 ? "left": "right": n - r > 0 ? "up": "down";
            return i && (u == "left" || u == "right" ? o / s > i && (u = "") : (u == "up" || u == "down") && s / o > i && (u = "")),
            u
        }
        function o(o, u, a, f, l) {
            if (!o) return;
            o.on(n,
            function(e) {
                var n = e.touches && e.touches[0] || e;
                t.x1 = n.pageX,
                t.y1 = n.pageY
            }).on(r,
            function(e) {
                var n = e.touches && e.touches[0] || e;
                t.x2 = n.pageX,
                t.y2 = n.pageY,
                f || e.preventDefault()
            }).on(i,
            function(n) {
                if (t.x2 && Math.abs(t.x1 - t.x2) > e || t.y2 && Math.abs(t.y1 - t.y2) > e) {
                    var r = s(t.x1, t.x2, t.y1, t.y2, l);
                    u === r && typeof a == "function" && a()
                } else u === "tap" && typeof a == "function" && a()
            })
        }
        function u(e) {
            if (!e) return;
            e.off(n).off(r).off(i)
        }
        var e = 20,
        t = {},
        n = "touchstart",
        r = "touchmove",
        i = "touchend";
        "ontouchstart" in window || (n = "mousedown", r = "mousemove", i = "mouseup"),
        $.flip = o,
        $.flipDestroy = u
    })();
    var s = new t.Class(n, {
        __propertys__: function() {},
        cleanCache: function() {
            var e = ["FLIGHT_SEARCH", "FLIGHT_SEARCH_SUBJOIN", "FLTINTL_SEARCH", "FLIGHT_LIST", "FLIGHT_INTER_CITY_LIST", "FLIGHT_CITY_LIST", "zqInCityInfo", "zqInCityDateStore", "LastInCitySelectDateTime", "LastzqInAirportSelectDateTime", "zqInAirportInfo", "zqInAirportDateStore", "zqInAirportDateAndAddressStore", "zqInCityDateAndAddressStore", "zqInCitySelectStore", "zqInAirportSelectStore", "FLIGHT_DETAILS", "FLIGHT_DETAILS_PARAM", "FLIGHT_ORDERINFO", "USER_FLIGHT_ORDERLIST", "USER_FLIGHT_ORDERDETAIL", "USER_FLIGHT_ORDERPARAM", "FLIGHT_RETURNPAGE", "FLIGHT_SELECTED_INFO", "FLIGHT_PICK_TICKET_SELECT", "FLIGHT_AIRLINE", "FLIGHT_AIRCTRAFT", "FLIGHT_ENUM_TAKETIME", "FLIGHT_ENUM_CABINS", "FLIGHT_LIST_FILTER", "FLIGHT_PICK_TICKET", "FLIGHT_PICK_TICKET_PARAM", "FLIGHT_AD_TIMEOUT", "P_FLIGHT_TicketList", "U_FLIGHT_ORDERLIST", "U_FLIGHT_ORDERDETAIL"],
            t = {
                flight: e
            },
            n = t[this.channel];
            if (Array.isArray(n)) for (var r in n) window.localStorage.removeItem(r)
        },
        initialize: function($super, e) {
            $super(e);
            var t = i.create("Guider");
            t.create(),
            $.bindFastClick && $.bindFastClick(),
            navigator.userAgent.indexOf("Android 2") > 0 && $.unbindFastClick && $.unbindFastClick();
            try {} catch(n) {}
            this.cleanCache()
        }
    });
    return s
}),
function() {
    var e = window.localStorage;
    e && e.removeItem("isPreProduction")
} (),
define("cUIBase", [],
function() {
    var e = {};
    return e.config = {
        prefix: "cui-"
    },
    e.setConfig = function(t, n) {
        e.config[t] = n
    },
    e.getElementPos = function(e) {
        var t = 0,
        n = 0;
        do t += e.offsetTop,
        n += e.offsetLeft;
        while (e = e.offsetParent);
        return {
            top: t,
            left: n
        }
    },
    e.getCreateId = function() {
        var t = (new Date).getTime();
        return function() {
            return e.config.prefix + ++t
        }
    } (),
    e.getBiggerzIndex = function() {
        var e = 3e3;
        return function() {
            return++e
        }
    } (),
    e.getCurStyleOfEl = function(e, t) {
        if (document.defaultView && document.defaultView.getComputedStyle) return document.defaultView.getComputedStyle(e).getPropertyValue(t);
        if (e.currentStyle) {
            var n = t.split("-"),
            r = [],
            i;
            for (var s = 0; s < n.length; s++) s == 0 ? r.push(n[s]) : (i = n[s].split(""), i[0] = i[0].toUpperCase(), r.push(i.join("")));
            return r = r.join(""),
            e.currentStyle[r]
        }
    },
    e.bindthis = function(e, t) {
        return function() {
            e.apply(t, arguments)
        }
    },
    e.strToNum = function(e) {
        var t = parseInt(e.replace(/[a-z]/i, ""));
        return isNaN(t) ? 0 : t
    },
    e.getElementRealSize = function(e) {
        var t = $(e);
        return {
            width: t.width(),
            height: t.height()
        }
    },
    e.getPageSize = function() {
        var e = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth),
        t = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
        return {
            width: e,
            height: t
        }
    },
    e.getPageScrollPos = function() {
        var e = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft),
        t = Math.max(document.documentElement.scrollTop, document.body.scrollTop),
        n = Math.min(document.documentElement.clientHeight, document.body.clientHeight),
        r = Math.min(document.documentElement.clientWidth, document.body.clientWidth),
        i = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth),
        s = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
        return {
            top: t,
            left: e,
            height: n,
            width: r,
            pageWidth: i,
            pageHeight: s
        }
    },
    e.getMousePos = function(e) {
        var t = Math.max(document.body.scrollTop, document.documentElement.scrollTop),
        n = Math.max(document.body.scrollLeft, document.documentElement.scrollLeft);
        return {
            top: t + e.clientY,
            left: n + e.clientX
        }
    },
    e.getMousePosOfElement = function(t, n) {
        var r = e.getMousePos(t),
        i = e.getElementPos(n),
        s = n.clientWidth,
        o = n.clientHeight,
        u = r.left - i.left,
        a = r.top - i.top;
        return u = u < 0 ? 0 : u > s ? s: u,
        a = a < 0 ? 0 : a > o ? o: a,
        {
            x: u,
            y: a
        }
    },
    e.createElement = function(e, t) {
        var n = document.createElement(e),
        r,
        i;
        if (t) for (r in t) switch (r) {
        case "attr":
            if (typeof t[r] == "object") for (i in t[r]) t[r][i] != null && n.setAttribute(i, t[r][i]);
            break;
        case "styles":
            if (typeof t[r] == "object") for (i in t[r]) t[r][i] != null && (n.style[i] = t[r][i]);
            break;
        case "id":
            n.id = t[r];
            break;
        case "class":
            n.className = t[r];
            break;
        case "html":
            n.innerHTML = t[r]
        }
        return n
    },
    e
}),
define("cHistory", ["cUIBase", "libs"],
function(e, t) {
    var n = function(t) {
        this.element,
        this.clazz = [e.config.prefix + "history"],
        this.maskName = "maskName",
        this.style = {},
        this.size = !1,
        this.listSize = 6,
        this.itemClickFun = null,
        this.focusFun = null,
        this.blursFun = null,
        this.inputFun = null,
        this._id = e.getCreateId(),
        this._boxDom,
        this._borderDom,
        this._contDom,
        this._clearButton,
        this.clearButtonTitle = "清除搜索历史",
        this.notHistoryButtonTitle = "无搜索历史",
        this.historyStore = null,
        this.dataSource = [],
        this._autoLocResoure,
        this._bodyDom,
        this.rootBox,
        this._oneShow = !1;
        var n = this;
        this.event_focus = function() {
            n.Open(),
            typeof n.focusFun == "function" && n.focusFun()
        },
        this.event_blur = function() {
            typeof n.blurFun == "function" && n.blurFun()
        },
        this.event_input = function() {
            n.element.val() == "" && (n._init(), n.Open()),
            n.inputFun(n.element.val())
        },
        this._setOption(t),
        this._init()
    };
    return n.prototype = {
        _setOption: function(e) {
            for (var t in e) switch (t) {
            case "element":
            case "maskName":
            case "clearButtonTitle":
            case "style":
            case "dataSource":
            case "historyStore":
            case "itmeClickFun":
            case "focusFun":
            case "blurFun":
            case "inputFun":
            case "size":
            case "listSize":
            case "rootBox":
                this[t] = e[t];
                break;
            case "clazz":
                isArray(e[t]) && (this.clazz = this.clazz.concat(e[t])),
                isString(e[t]) && this.clazz.push(e[t])
            }
        },
        _init: function() {
            this._contDom && (this._contDom.find("li.item").unbind("click"), this._contDom.remove()),
            this._boxDom && this._boxDom.remove(),
            this._CreateDom(),
            this._BuildEvent()
        },
        _CreateDom: function() {
            var t = e.createElement;
            this._bodyDom = this.rootBox || $("body"),
            this.element = $(this.element),
            this._boxDom = $(t("div", {
                id: this._id,
                "class": this.clazz.join(" ")
            })),
            this._boxDom.css({
                position: "absolute",
                display: "none"
            }),
            this._borderDom = $(t("div", {
                "class": e.config.prefix + "history-border"
            }));
            var n = [];
            this.element.val() == "" ? n = this._getHistory() : n = this._getSubList(this.dataSource, this.listSize),
            this._contDom = $(t("ul", {
                "class": e.config.prefix + "history-list"
            }));
            for (var r in n) this._contDom.append('<li class="item" data_id="' + n[r].id + '">' + n[r].name + "</li>");
            this.element.val() == "" && (this._clearButton = $(t("li", {
                "class": [e.config.prefix + "clear-history clearbutton"]
            })), n.length > 0 ? this._clearButton.html(this.clearButtonTitle) : this._clearButton.html(this.notHistoryButtonTitle), this._contDom.append(this._clearButton)),
            this._borderDom.append(this._contDom),
            this._boxDom.append(this._borderDom),
            this._bodyDom.append(this._boxDom)
        },
        _Location: function() {
            this._boxDom.css({
                height: "auto",
                width: "auto"
            });
            var t = e.getPageSize(),
            n = e.getElementPos(this.element[0]),
            r = this.style.left ? this.style.left: (this.size && this.size.left ? this.size.left + n.left: n.left) + "px",
            i = this.style.top ? this.style.top: (this.size && this.size.top ? this.size.top + (n.top + this.element.height()) : n.top + this.element.height()) + "px",
            s = this.style.width ? this.style.width: this.element.width() + "px",
            o = this.size && this.size.height ? t.height + this.size.height + "px": "auto";
            this._boxDom.css({
                left: r,
                top: i,
                width: s,
                height: o
            })
        },
        _AutoLocation: function() {
            this._Location();
            var e = this;
            this._autoLocResoure = function() {
                e._Location()
            },
            $(window).unbind("resize", this._autoLocResoure),
            $(window).bind("resize", this._autoLocResoure)
        },
        _UnAutoLocation: function() {
            $(window).unbind("resize", this._autoLocResoure)
        },
        _BuildEvent: function() {
            var e = this;
            this._contDom.find("li.item").unbind("click").bind("click",
            function() {
                var t = $(this);
                e.element.val(t.text()),
                e.Close();
                if (typeof e.itmeClickFun == "function") {
                    var n = {
                        id: t.attr("data_id"),
                        name: t.text()
                    };
                    e.itmeClickFun(n)
                }
            }),
            this.element.unbind("focus", this.event_focus),
            this.element.unbind("blur", this.event_blur),
            this.element.unbind("input", this.event_input),
            this.element.bind({
                focus: this.event_focus,
                blur: this.event_blur,
                input: this.event_input
            }),
            this.element.val() == "" && this._getHistory().length > 0 && this._clearButton.bind("click",
            function() {
                e.historyStore.remove(),
                e.Close(),
                e._init()
            })
        },
        setOpen: function() {
            this._oneShow = !0
        },
        Open: function() {
            this._boxDom.css("z-index", e.getBiggerzIndex()),
            this._boxDom.show(),
            this._AutoLocation()
        },
        Close: function() {
            this._boxDom.hide(),
            this._UnAutoLocation()
        },
        setDataSource: function(e) {
            this.dataSource = e,
            this.Close(),
            this._init(),
            this.Open()
        },
        addHistory: function(e) {
            var t = this.historyStore.get() || [];
            e.id || (e.id = 0);
            var n = -1;
            for (var r = 0,
            i = t.length; r < i; r++) if (t[r].name == e.name) {
                n = r;
                break
            }
            n > -1 && t.splice(n, 1),
            t.unshift(e),
            this.historyStore.set(t),
            this._init()
        },
        reset: function() {
            this._init()
        },
        _getHistory: function() {
            var e = this.historyStore.get() || [];
            return this._getSubList(e, this.listSize)
        },
        _getSubList: function(e, t) {
            var n = e.length;
            return n <= t ? e: e.slice(0, t)
        }
    },
    n
}),
define("cUIAbstractView", ["libs", "cBase", "cUIBase"],
function(e, t, n) {
    var r = Array.prototype.slice,
    i = Array.prototype.push,
    s = Object.prototype.toString,
    o = "notCreate",
    u = "onCreate",
    a = "onShow",
    f = "onHide",
    l = {};
    return l.__propertys__ = function() {
        this.allowEvents = {
            onCreate: !0,
            onShow: !0,
            onHide: !0
        },
        this.allowsPush = {
            classNames: !0
        },
        this.allowsConfig = {
            rootBox: !0
        },
        this.events = {
            onCreate: [],
            onShow: [],
            onHide: []
        },
        this.status = o,
        this.setOptionHander = [],
        this.rootBox,
        this.id = n.getCreateId(),
        this.classNames = [n.config.prefix + "view"],
        this.root,
        this.isCreate = !1
    },
    l.initialize = function(e) {
        this.setOption(function(e, t) {
            switch (!0) {
            case this.allowEvents[e]:
                this.addEvent(e, t);
                break;
            case this.allowsPush[e]:
                s.call(t) === "[object Array]" ? i.apply(this[e], t) : this[e].push(t);
                break;
            case this.allowsConfig[e]:
                this[e] = t
            }
        }),
        this.readOption(e)
    },
    l.readOption = function(e) {
        e = e || {};
        var t = this;
        $.each(e,
        function(e, n) {
            $.each(t.setOptionHander,
            function(r, i) {
                typeof i == "function" && i.call(t, e, n)
            })
        })
    },
    l.setOption = function(e) {
        this.setOptionHander.push(e)
    },
    l.createRoot = function() {
        var e = document.createElement("div");
        return e.className = this.classNames.join(" "),
        e.id = this.id,
        $(e)
    },
    l.addClass = function(e) {
        this.classNames.push(e);
        if (!this.root) return;
        if (typeof e == "array") for (var t in e) this.root.addClass(e[t]);
        else typeof e == "string" && this.root.addClass(e)
    },
    l.removeClass = function(e) {
        if (typeof e == "array") for (var t in e) this.root.removeClass(e[t]);
        else typeof e == "string" && this.root.removeClass(e)
    },
    l.createHtml = function() {
        throw new Error("未定义createHtml方法")
    },
    l.setRootHtml = function(e) {
        this.root && (this.root.empty(), this.root.append(e))
    },
    l.getRoot = function() {
        return this.root
    },
    l.addEventType = function(e) {
        this.allowEvents[e] = !0,
        this.events[e] = []
    },
    l.addEvent = function(e, t) {
        if (!this.allowEvents[e]) return ! 1;
        this.events[e] && this.events[e].push(t)
    },
    l.removeEvent = function(e, t) {
        this.events[e] && (t ? this.events[e] = _.without(this.events[e], t) : this.events[e] = [])
    },
    l.remove = function() {
        this.hide(),
        this.root.remove()
    },
    l.trigger = function(e) {
        var t = r.call(arguments, 1),
        n = this.events,
        i = [],
        s,
        o;
        if (this.events[e]) for (s = 0, o = n[e].length; s < o; s++) i[i.length] = n[e][s].apply(this, t);
        return i
    },
    l.create = function() { ! this.isCreate && this.status !== u && (this.rootBox = this.rootBox || $("body"), this.root = this.createRoot(), this.root.hide(), this.rootBox.append(this.root), this.root.append(this.createHtml()), this.trigger("onCreate"), this.status = u, this.isCreate = !0)
    },
    l.template = function(e) {
        return _.template(e)
    },
    l.showAction = function(e) {
        this.root.show(),
        typeof e == "function" && e()
    },
    l.hideAction = function(e) {
        this.root.hide(),
        typeof e == "function" && e()
    },
    l.setzIndexTop = function(e) {
        e = typeof e != "number" ? 0 : e,
        this.root.css("z-index", n.getBiggerzIndex() + e)
    },
    l.isNotCreate = function() {
        return this.status === o
    },
    l.isShow = function() {
        return this.status === a
    },
    l.isHide = function() {
        return this.status === f
    },
    l.show = function(e) {
        if (this.status === a) return;
        this.create(),
        this.showAction($.proxy(function() {
            this.trigger("onShow"),
            this.status = a,
            e && e.call(this)
        },
        this))
    },
    l.hide = function(e) {
        if (!this.root || this.status === f) return;
        this.hideAction($.proxy(function() {
            this.trigger("onHide"),
            this.status = f,
            e && e.call(this)
        },
        this))
    },
    l.reposition = function() {
        this.root.css({
            "margin-left": -($(this.root).width() / 2) + "px",
            "margin-top": -($(this.root).height() / 2) + "px"
        })
    },
    t.Class(l)
}),
define("cUIMask", ["libs", "cBase", "cUIAbstractView"],
function(e, t, n) {
    var r = {},
    i = {
        prefix: "cui-"
    };
    return r.__propertys__ = function() {},
    r.initialize = function($super, e) {
        this.bindEvent(),
        this.addClass(i.prefix + "mask"),
        $super(e)
    },
    r.bindEvent = function() {
        this.addEvent("onCreate",
        function() {
            this.setRootStyle(),
            this.onResize = $.proxy(function() {
                this.resize()
            },
            this),
            this.onResize()
        }),
        this.addEvent("onShow",
        function() {
            this.setzIndexTop( - 1),
            $(window).bind("resize", this.onResize),
            this.root.bind("touchmove",
            function(e) {
                e.preventDefault()
            }),
            this.onResize()
        }),
        this.addEvent("onHide",
        function() {
            $(window).unbind("resize", this.onResize),
            this.root.unbind("touchmove")
        })
    },
    r.setRootStyle = function() {
        this.root.css({
            position: "absolute",
            left: "0px",
            top: "0px"
        })
    },
    r.createHtml = function() {
        return "<div></div>"
    },
    r.resize = function() {
        var e = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth),
        t = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight);
        this.root.css({
            width: "100%",
            height: t + "px"
        })
    },
    new t.Class(n, r)
}),
define("cUILayer", ["libs", "cBase", "cUIAbstractView", "cUIMask"],
function(e, t, n, r) {
    var i = {},
    s = {
        prefix: "cui-"
    };
    return i.__propertys__ = function() {
        this.tpl = this.template(['<div class="' + s.prefix + 'layer-padding">', '<div class="' + s.prefix + 'layer-content"><%=content%></div>', "</div>"].join("")),
        this.content = "",
        this.contentDom,
        this.mask = new r({
            classNames: [s.prefix + "opacitymask"]
        }),
        this.addClass(s.prefix + "layer"),
        this.viewdata = {},
        this.windowResizeHander,
        this.setIntervalResource,
        this.setIntervalTotal = 0
    },
    i.initialize = function($super, e) {
        var t = {
            content: !0
        };
        this.setOption(function(e, n) {
            switch (!0) {
            case t[e]:
                this[e] = n;
                break;
            case "class" === e: this.addClass(n)
            }
        }),
        this.bindEvent(),
        $super(e),
        this.loadViewData()
    },
    i.loadViewData = function() {
        this.viewdata.content = this.content
    },
    i.setViewData = function(e) {
        this.viewdata = cUtility.mix(this.viewdata, e),
        this.setRootHtml(this.createHtml())
    },
    i.bindEvent = function() {
        this.addEvent("onCreate",
        function() {
            this.windowResizeHander = $.proxy(this.reposition, this),
            this.contentDom = this.root.find("." + s.prefix + "layer-content")
        }),
        this.addEvent("onShow",
        function() {
            this.mask.show(),
            $(window).bind("resize", this.windowResizeHander),
            this.reposition(),
            this.setIntervalResource = setInterval($.proxy(function() {
                this.setIntervalTotal < 10 ? this.windowResizeHander() : (this.setIntervalTotal = 0, this.root.css("visibility", "visible"), clearInterval(this.setIntervalResource)),
                this.setIntervalTotal++
            },
            this), 1),
            this.setzIndexTop()
        }),
        this.addEvent("onHide",
        function() {
            $(window).unbind("resize", this.windowResizeHander),
            clearInterval(this.setIntervalResource),
            this.root.css("visibility", "visible"),
            this.mask.hide()
        })
    },
    i.createHtml = function() {
        return this.tpl(this.viewdata)
    },
    i.maskToHide = function(e) {
        this.mask.root.on("click", $.proxy(function() {
            this.hide(),
            typeof e == "function" && e(),
            this.mask.root.off("click")
        },
        this)),
        this.mask.addEvent("onHide",
        function() {
            this.root.off("click"),
            this.root.remove()
        })
    },
    new t.Class(n, i)
}),
define("cUIAlert", ["libs", "cBase", "cUILayer"],
function(e, t, n) {
    var r = Object.prototype.toString,
    i = "confirm",
    s = "cancel",
    o = {};
    o.onCreate = function() {
        this.loadButtons()
    };
    var u = {},
    a = {
        prefix: "cui-"
    };
    u.__propertys__ = function() {
        this.tpl = this.template(['<div class="cui-pop-box">', "<%if(showTitle) {%>", '<div class="cui-hd">', '<div class="cui-text-center"><%=title%></div>', "</div>", "<% } %>", '<div class="cui-bd">', '<div class="cui-error-tips"><%=message%></div>', '<div class="cui-roller-btns">', "</div>", "</div>", "</div>"].join("")),
        this.title = "",
        this.message = "",
        this.buttons = [{
            text: "确定",
            type: "confirm",
            click: function() {
                this.hide()
            }
        }],
        this.viewdata = {
            title: "",
            message: "",
            showTitle: !1
        }
    },
    u.initialize = function($super, e) {
        var t = {
            title: !0,
            message: !0,
            buttons: !0,
            showTitle: !0
        };
        this.setOption(function(e, n) {
            switch (!0) {
            case t[e]:
                this[e] = n
            }
        }),
        this.addClass(a.prefix + "alert"),
        $super($.extend(o, e)),
        this.buildViewData()
    },
    u.buildViewData = function() {
        this.viewdata.title = this.title,
        this.viewdata.message = this.message,
        this.viewdata.showTitle = this.showTitle
    },
    u.setViewData = function(e) {
        e.title && (this.title = e.title),
        e.message && (this.message = e.message),
        e.showTitle && (this.showTitle = this.showTitle),
        e.buttons && (this.buttons = e.buttons),
        this.buildViewData(),
        this.root || (this.root = this.createRoot()),
        this.setRootHtml(this.createHtml()),
        this.loadButtons()
    },
    u.loadButtons = function() {
        this.root || this.create();
        var e = this.root.find(".cui-roller-btns"),
        t = this.createButtons();
        e.empty(),
        $.each(t,
        function(t, n) {
            e.append(n)
        })
    },
    u.createButtons = function() {
        var e = [],
        t = r.call(this.buttons) === "[object Array]",
        n = 0,
        o = this;
        return $.each(this.buttons,
        function(r, u) {
            var a = "",
            f = [],
            l = function() {};
            if (t) {
                a = u.text,
                u.cls && f.push(u.cls),
                u.type = u.type ? u.type: a == "取消" ? s: i;
                switch (u.type) {
                case s:
                    f.push("cui-btns-cancel");
                    break;
                case i:
                    f.push("cui-btns-sure")
                }
                u.click && (l = u.click)
            } else a = r,
            typeof u == "function" && (l = u);
            e[n] = $('<div class="cui-flexbd ' + f.join(" ") + '">' + a + "</div>"),
            e[n].addClass(f.join(" ")),
            e[n].bind("click", $.proxy(l, o)),
            n++
        }),
        e
    },
    u.createHtml = function() {
        return this.tpl(this.viewdata)
    };
    var f = new t.Class(n, u);
    return f.STYLE_CONFIRM = i,
    f.STYLE_CANCEL = s,
    f
}),
define("cUIWarning", ["libs", "cBase", "cUILayer", "cUIMask"],
function(e, t, n, r) {
    var i = {},
    s = {
        prefix: "cui-"
    },
    o = new r({
        classNames: [s.prefix + "warning-mask"]
    }),
    u = function() {},
    a = {};
    return a["class"] = s.prefix + "warning",
    a.onCreate = function() {
        this.contentDom.html('<div class="' + s.prefix + 'warning"><div class="blank"></div><p class="blanktxt">' + this.warningtitle + "</p></div>"),
        this.warningDom = this.contentDom.find(".blanktxt"),
        this.root.bind("click", $.proxy(function() {
            this.callback && this.callback()
        },
        this)),
        o.create(),
        o.root.bind("click", $.proxy(function() {
            this.callback && this.callback()
        },
        this))
    },
    a.onShow = function() {
        o.show()
    },
    a.onHide = function() {
        o.hide()
    },
    a.setTitle = function(e, t) {
        e && (this.create(), this.warningDom.html(e), this.warningtitle = e),
        t ? this.callback = t: this.callback = function() {}
    },
    a.getTitle = function() {
        return this.warningtitle
    },
    i.__propertys__ = function() {
        this.warningDom,
        this.warningtitle = "",
        this.callback = function() {}
    },
    i.initialize = function($super, e) {
        this.setOption(function(e, t) {
            switch (e) {
            case "title":
                this.warningtitle = t
            }
        }),
        $super($.extend(a, e))
    },
    new t.Class(n, i)
}),
define("cUIHashObserve", ["libs", "cBase"],
function(e, t) {
    var n = {},
    r = {
        prefix: "cui-"
    };
    return n.__propertys__ = function() {
        this.hash,
        this.callback,
        this._hashchange = $.proxy(function() {
            this.hashchange()
        },
        this),
        this.isend = !0,
        this.scope
    },
    n.initialize = function(e) {
        this.setOption(e)
    },
    n.setOption = function(e) {
        var t = {
            hash: !0,
            callback: !0,
            scope: !0
        };
        for (var n in e) switch (!0) {
        case t[n]:
            this[n] = e[n]
        }
    },
    n.start = function() {
        this.isend = !1,
        window.location.hash += "|" + this.hash,
        $(window).bind("hashchange", this._hashchange)
    },
    n.end = function() {
        $(window).unbind("hashchange", this._hashchange),
        this.isend || (this.isend = !0, window.history.go( - 1))
    },
    n.hashchange = function() {
        var e = window.location.hash;
        e.match(new RegExp("\\b" + this.hash + "\\b", "ig")) || (this.isend = !0, this.callback.call(this.scope || this), this.end())
    },
    new t.Class(n)
}),
define("cUIPageview", ["libs", "cBase", "cUIAbstractView", "cUIMask", "cUIHashObserve"],
function(e, t, n, r, i) {
    var s = {},
    o = {
        prefix: "cui-"
    };
    return s.__propertys__ = function() {
        this.mask = new r({
            classNames: [o.prefix + "warning-mask"]
        }),
        this.hashObserve = new i({
            hash: this.id,
            scope: this,
            callback: function() {
                this.hide()
            }
        })
    },
    s.initialize = function($super, e) {
        this.addClass(o.prefix + "pageview"),
        this.addEvent("onCreate",
        function() {
            this.mask.create(),
            this.mask.root.css({
                background: "url(data:image/gif;base64,R0lGODlhAQABAIAAAPX19QAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==) repeat"
            }),
            this.root.css({
                position: "absolute",
                left: "0px",
                top: "0px"
            })
        }),
        this.addEvent("onShow",
        function() {
            this.mask.show(),
            this.hashObserve.start(),
            this.root.bind("touchmove",
            function(e) {
                e.preventDefault()
            })
        }),
        this.addEvent("onHide",
        function() {
            this.mask.hide(),
            setTimeout($.proxy(function() {
                this.hashObserve.end()
            },
            this), 10)
        }),
        $super(e)
    },
    s.createHtml = function() {
        return ""
    },
    new t.Class(n, s)
}),
define("cUIHeadWarning", ["libs", "cBase", "cUIPageview"],
function(e, t, n) {
    var r = {},
    i = {
        prefix: "cui-"
    },
    s = {};
    return s["class"] = i.prefix + "warning",
    s.onCreate = function() {
        this.root.html(['<div class="head-warning">', '<div class="head-warning-padding">', '<div class="head-warning-header">', '<div class="head-warning-header-backbtu"><span class="returnico"></span></div>', '<div class="head-warning-header-title"></div>', "</div>", '<div class="head-warning-content">', '<div class="head-warning-content-icon"><div class="cui-load-error"><div class="cui-i cui-wifi cui-exclam"></div></div></div>', '<div class="head-warning-content-title"></div>', "</div>", "</div>", "</div>"].join("")),
        this.addClass("head-warning-top"),
        this.warningtitleDom = this.root.find(".head-warning-header-title"),
        this.warningcontentDom = this.root.find(".head-warning-content-title"),
        this.warningleftbtuDom = this.root.find(".head-warning-header-backbtu"),
        this.warningleftbtuDom.bind("click", $.proxy(function() {
            this.callback && this.callback()
        },
        this))
    },
    s.onShow = function() {
        this.setzIndexTop(),
        window.scrollTo(0, 0)
    },
    r.__propertys__ = function() {
        this.warningtitleDom,
        this.warningcontentDom,
        this.warningtitle = "",
        this.warningcontent = "",
        this.callback = function() {}
    },
    r.initialize = function($super, e) {
        $super($.extend(s, e))
    },
    r.setTitle = function(e, t, n) {
        e && (this.create(), this.warningtitleDom.html(e), this.warningcontentDom.html(t)),
        n && (this.callback = n)
    },
    new t.Class(n, r)
}),
define("cUIWarning404", ["libs", "cBase", "cUIPageview", "cWidgetFactory", "cWidgetGuider"],
function(e, t, n, r) {
    var i = r.create("Guider"),
    s = {},
    o = {
        prefix: "cui-"
    },
    u = function() {},
    a = {};
    a["class"] = o.prefix + "warning",
    a.onCreate = function() {
        this.options()
    },
    a.onShow = function() {
        this.mask.root.css({
            "z-index": "1000"
        }),
        this.root.css({
            "z-index": "1001"
        }),
        window.scrollTo(0, 0)
    },
    a.onHide = function() {
        this.options({})
    },
    s.__propertys__ = function() {
        this.retryDom,
        this.tel = "4000086666",
        this.callback = function() {},
        this.hashObserve = {
            start: function() {},
            end: function() {}
        }
    },
    s.initialize = function($super, e) {
        $super(a, e)
    },
    s.retryClick = function(e) {
        e && (this.callback = e)
    };
    var f = {
        loadFailCls: "cui-wifi cui-fail-icon",
        loadFail: "加载失败，请稍后再试试吧",
        telText: "或者拨打携程客服电话",
        tryAgain: "重试",
        contact: "联系客服",
        showContact: !0
    },
    l = ['<div class="head-warning">', '<div class="head-warning-padding">', '<div class="head-warning-content">', '<div class="cui-load-fail cui-text-center">', '<div class="cui-load-error">', '<div class="cui-i <%= loadFailCls %>">', "</div>", "</div>", '<p class="cui-gray"><%= loadFail %></p>', '<button type="button" class="cui-btns-retry"><%= tryAgain %></button>', "<% if (showContact) { %>", '<div class="cui-404-tel">', '<div class="cui-glines"></div>', '<p class="cui-grayc"><%= telText %></p>', '<span id="telBtn" class="cui-btns-tel"><span class="cui-i cui-warning-kf"></span><%= contact %></span>', "</div>", "<% } %>", "</div>", "</div>", "</div>", "</div>"].join("");
    return s.options = function(e) {
        e = _.extend({},
        f, e || {}),
        this.root.html(_.template(l, e)),
        this.addClass("head-warning-top"),
        this.retryDom = this.root.find(".cui-btns-retry"),
        this.retryDom.on("click", $.proxy(function() {
            this.callback && this.callback()
        },
        this));
        var t = this;
        e.showContact && this.root.find("#telBtn").click(function() {
            i.apply({
                hybridCallback: function() {
                    i.callService()
                },
                callback: function() {
                    window.location.href = "tel:" + t.tel
                }
            })
        })
    },
    new t.Class(n, s)
}),
define("cUIToast", ["libs", "cBase", "cUILayer"],
function(e, t, n) {
    var r = {},
    i = {
        prefix: "cui-",
        sleep: 2
    },
    s = null,
    o = null,
    u = null,
    a = function(e) {
        this.hide(),
        e && typeof e == "function" && e.call(this),
        $(".cui-opacitymask").unbind("click"),
        $(".cui-toast").unbind("click")
    },
    f = function(e, t) {
        var n = this,
        r = function() {
            $(".cui-opacitymask").unbind("click").bind("click",
            function() {
                a.call(n, t)
            }),
            $(".cui-toast").unbind("click").bind("click",
            function() {
                a.call(n, t)
            })
        };
        e && setTimeout(r, 400)
    },
    l = function(e, t, n, r) {
        var u = this;
        this.setContent(e),
        typeof o == "function" && o.call(this);
        var l = function() {
            a.call(u, n)
        },
        c = (t || i.sleep) * 1e3;
        s = setTimeout(l, c),
        f.call(this, r, n),
        this.focusPosition = setInterval($.proxy(function() {
            var e = document.activeElement;
            if ($.needFocus(e)) {
                this.focusPosition || (this.focusPosition = !0);
                var t = parseInt($(e).offset().top) + 30;
                this.root.css({
                    top: t + "px",
                    position: "absolute"
                })
            }
        },
        this), 20)
    },
    c = function() {
        clearTimeout(s),
        this.focusPosition && (clearInterval(this.focusPosition), this.root.css({
            top: "50%",
            position: "fixed"
        })),
        typeof u == "function" && u.call(this)
    };
    r.__propertys__ = function() {
        o = this.show,
        u = this.hide,
        this.show = l,
        this.hide = c
    },
    r.initialize = function($super, e) {
        this.addClass([i.prefix + "toast"]),
        $super(e)
    },
    r.setContent = function(e) {
        this.create(),
        this.contentDom.html(e)
    };
    var h = new t.Class(n, r);
    return h
}),
define("cAjax", ["libs", "cLog"],
function(e, t) {
    var n = {
        json: "application/json; charset=utf-8",
        jsonp: "application/json"
    },
    r = function(e) {
        return e && (e = n[e] ? n[e] : e),
        e
    },
    i = function(e) {
        function n(e, t, n, r) {
            var i = l(e, t, n, r);
            return i.type = "GET",
            a(i)
        }
        function i(e, t, n, i) {
            var s = t.contentType;
            t = JSON.stringify(t);
            var o = l(e, t, n, i);
            return o.type = "POST",
            o.dataType = "json",
            o.timeout = 3e4,
            o.contentType = r(s) || "application/json",
            a(o)
        }
        function s(e, t, n, r) {
            var i = l(e, t, n, r);
            return i.type = "GET",
            i.dataType = "jsonp",
            i.crossDomain = !0,
            a(i)
        }
        function o(e, t, n, i, s) {
            var o = n.contentType;
            t.toLowerCase() !== "get" && (n = JSON.stringify(n));
            var u = l(e, n, i, s);
            return u.type = t,
            u.dataType = "json",
            u.crossDomain = !0,
            u.data = n,
            u.contentType = r(o) || "application/json",
            a(u)
        }
        function u(t, n, r, i) {
            var s = null,
            o = "";
            typeof n == "string" ? s = e("#" + n) : s = e(n),
            s && s.length > 0 && (o = s.serialize());
            var u = l(t, o, r, i);
            return a(u)
        }
        function a(n) {
            var r = (new Date).getTime(),
            i = {
                url: n.url,
                type: n.type,
                dataType: n.dataType,
                data: n.data,
                contentType: n.contentType,
                timeout: n.timeout || 5e4,
                success: function(e) {
                    t.ajaxReady(n.url, r),
                    n.callback(e)
                },
                error: function(e) {
                    t.ajaxReady(n.url, r),
                    n.error && n.error(e)
                }
            };
            return n.url.indexOf(window.location.host) === -1 && (i.crossDomain = !!n.crossDomain),
            e.ajax(i)
        }
        function f(t) {
            if (window.XDomainRequest) {
                var n = new XDomainRequest;
                if (n) {
                    t.error && typeof t.error == "function" && (n.onerror = function() {
                        t.error()
                    }),
                    t.timeout && typeof t.timeout == "function" && (n.ontimeout = function() {
                        t.timeout()
                    }),
                    t.success && typeof t.success == "function" && (n.onload = function() {
                        t.dataType ? t.dataType.toLowerCase() == "json" && t.callback(JSON.parse(n.responseText)) : t.callback(n.responseText)
                    });
                    var r = "";
                    t.type == "POST" ? r = t.data: r = e.param(t.data),
                    n.open(t.type, t.url),
                    n.send(r)
                }
            }
        }
        function l(e, t, n, r) {
            return {
                url: e,
                data: t,
                callback: n,
                error: r
            }
        }
        return {
            get: n,
            post: i,
            jsonp: s,
            cros: o,
            form: u
        }
    } ($);
    return i
}),
define("cAbstractModel", ["libs", "cBase", "cAjax", "cLog"],
function(e, t, n, r) {
    var i = new t.Class({
        __propertys__: function() {
            this.url = null,
            this.param = null,
            this.dataformat = null,
            this.validates = [],
            this.debug = !1,
            this.protocol = "http",
            this.contentType = i.CONTENT_TYPE_JSON,
            this.method = "POST",
            this.ajax,
            this.isAbort = !1,
            this.onBeforeCompleteCallback = null
        },
        initialize: function(e) {
            this.assert();
            for (var t in e) this[t] = e[t]
        },
        assert: function() {
            if (this.url === null) throw "not override url property";
            if (this.param === null) throw "not override param property"
        },
        pushValidates: function(e) {
            typeof e == "function" && this.validates.push($.proxy(e, this))
        },
        setParam: function(e, t) {
            typeof e == "object" && !t ? this.param = e: this.param[e] = t
        },
        getParam: function() {
            return this.param
        },
        buildurl: function() {
            throw "[ERROR]abstract method:buildurl, must be override"
        },
        execute: function(e, t, r, s, o) {
            this.isAbort = !1;
            var u = this.buildurl(),
            a = this,
            f = $.proxy(function(n) {
                if (this.validates && this.validates.length > 0) for (var i = 0,
                s = this.validates.length; i < s; i++) if (!this.validates[i](n)) return typeof t == "function" ? t.call(r || this, n) : !1;
                var o = typeof this.dataformat == "function" ? this.dataformat(n) : n;
                typeof this.onBeforeCompleteCallback == "function" && this.onBeforeCompleteCallback(o),
                typeof e == "function" && e.call(r || this, o, n)
            },
            this),
            l = $.proxy(function(e) {
                if (a.isAbort) return a.isAbort = !1,
                typeof s == "function" ? s.call(r || this, e) : !1;
                typeof t == "function" && t.call(r || this, e)
            },
            this),
            o = o || _.clone(this.getParam() || {});
            return o.contentType = this.contentType,
            this.contentType === i.CONTENT_TYPE_JSON ? this.ajax = n.cros(u, this.method, o, f, l) : this.contentType === i.CONTENT_TYPE_JSONP ? this.ajax = n.jsonp(u, o, f, l) : this.ajax = n.post(u, o, f, l)
        },
        abort: function() {
            this.isAbort = !0,
            this.ajax && this.ajax.abort && this.ajax.abort()
        }
    });
    return i.getInstance = function() {
        return this.instance instanceof this ? this.instance: this.instance = new this
    },
    i.baseurl = function() {
        throw "[ERROR]abstract method:baseurl, must be override"
    },
    i.CONTENT_TYPE_JSON = "json",
    i.CONTENT_TYPE_FORM = "form",
    i.CONTENT_TYPE_JSONP = "jsonp",
    i
}),
define("cModel", ["libs", "cBase", "cStore", "cUtility", "CommonStore", "cAbstractModel", "cStorage"],
function(e, t, n, r, i, s, o) {
    var u = r.Object,
    a = new t.Class(s, {
        __propertys__: function() {
            this.usehead = !0,
            this.head = i.HeadStore.getInstance(),
            this.result = null,
            this.headinfo = null,
            this.ajaxOnly = !1,
            this.isUserData = !1
        },
        initialize: function($super, e) {
            $super(e),
            this.baseurl || (this.baseurl = a.baseurl.call(this, this.protocol))
        },
        setHead: function(e) {
            if (!e instanceof n) throw "Set head is not a store";
            this.head = e
        },
        getHead: function() {
            return this.head
        },
        getParamStore: function() {
            return this.param
        },
        setParamStore: function(e) {
            if (typeof e != "object") throw "Set param is not a store";
            this.param = e
        },
        getResultStore: function() {
            return this.result
        },
        clearResult: function() {
            this.result && typeof this.result.remove == "function" && this.result.remove()
        },
        setParam: function(e, t) {
            var r = {};
            typeof e == "object" && !t ? r = e: r[e] = t;
            for (var i in r) this.param instanceof n ? this.param.setAttr(i, r[i]) : u.set(this.param, i, r[i])
        },
        getParam: function() {
            return this.param instanceof n ? this.param.get() : this.param
        },
        buildurl: function() {
            var e = o.localStorage.get("H5_CFG"),
            t;
            e && e.path && (t = e.path);
            var n = this.baseurl,
            r = [];
            t && !location.host.match(/^m\.ctrip\.com/i) && (this.protocol === "http" ? r = t.http && t.http.split("/") : r = t.https && t.https.split("/"));
            var i = this.protocol + "://" + (r[0] || n.domain) + "/" + (r[1] || n.path) + (typeof this.url == "function" ? this.url() : this.url);
            return i
        },
        getTag: function() {
            var e = _.clone(this.getParam() || {});
            if (this.isUserData && !e.cid) {
                var t = this.head.userStore;
                e.cid = t.getUserId()
            }
            return JSON.stringify(e)
        },
        excute: function(e, t, r, i, s) {
            var o = _.clone(this.getParam() || {});
            this.pushValidates(function(e) {
                var t = this.head.get(),
                n = this._getResponseHead(e);
                return this.contentType !== a.CONTENT_TYPE_JSONP && this.usehead && n.auth && n.auth !== t.auth && this.head.setAuth(n.auth),
                n.success
            });
            var u = this.getTag(),
            f = this.result && this.result.get(u); ! f || this.ajaxOnly || r ? (this.method.toLowerCase() !== "get" && this.usehead && this.contentType !== a.CONTENT_TYPE_JSONP ? o.head = this.head.get() : this.method.toLowerCase() !== "get" && !this.usehead && this.contentType !== a.CONTENT_TYPE_JSONP && this.headinfo && (o.head = this.headinfo), this.onBeforeCompleteCallback = function(e) {
                if (this.result instanceof n) {
                    try {} catch(t) {}
                    this.result.set(e, u)
                }
            },
            this.execute(e, t, i, s, o)) : typeof e == "function" && e.call(i || this, f)
        },
        _getResponseHead: function(e) {
            var t = !!e.ResponseStatus,
            n = t ? e.ResponseStatus: e.head,
            r = "",
            i = !1;
            if (t && n) {
                var s = n.Extension;
                for (var o in s) {
                    var u = s[o];
                    if (u.id == "auth") {
                        r = u.value;
                        break
                    }
                }
                i = n.Ack === "Success" || n.Ack == "0"
            } else r = n.auth,
            i = n && n.errcode === 0;
            return {
                auth: r,
                success: i
            }
        }
    });
    return a.baseurl = function(e) {
        var t = location.host,
        n = "m.ctrip.com",
        i = "restapi";
        return r.isInApp() ? r.isPreProduction() == "1" ? e == "https" ? n = "wpg.ctrip.com": n = "10.8.14.28:8080": r.isPreProduction() == "0" ? e == "https" ? n = "secure.fws.qa.nt.ctripcorp.com": n = "m.fat19.qa.nt.ctripcorp.com": r.isPreProduction() == "2" ? e == "https" ? n = "restful.m.uat.qa.ctripcorp.com": n = "m.uat.qa.ctripcorp.com": e == "https" ? n = "wpg.ctrip.com": n = "m.ctrip.com": t.match(/^m\.ctrip\.com/i) || t.match(/^secure\.ctrip\.com/i) ? a.isHttps(e) ? n = "wpg.ctrip.com": n = "m.ctrip.com": t.match(/^m\.uat\.qa/i) ? a.isHttps(e) ? n = "restful.m.uat.qa.ctripcorp.com": n = "m.uat.qa.ctripcorp.com": t.match(/^(localhost|172\.16|127\.0)/i) ? a.isHttps(e) ? n = "secure.fws.qa.nt.ctripcorp.com": n = "m.fat19.qa.nt.ctripcorp.com": t.match(/^10\.8\.2\.111/i) ? n = "10.8.14.28:8080": t.match(/^m.fat/i) ? a.isHttps(e) ? n = "secure.fws.qa.nt.ctripcorp.com": n = t: n = "m.ctrip.com",
        {
            domain: n,
            path: i
        }
    },
    a.isHttps = function(e) {
        return location.protocol == "https" || e == "https"
    },
    a
}),
define("cSales", ["cBase", "cStorage", "libs", "CommonStore", "cModel"],
function(e, t, n, r, i) {
    function x(e) {
        var t = ".__hreftel__",
        n = ".__conttel__",
        r = ".__appaddress__";
        $(e[0]).find(t).each(function() {
            this.href = w(this.href)
        }),
        $(e[0]).find(n).each(function() {
            var e = $(this);
            e.html(w(e.html()))
        }),
        $(r).each(function() {
            var e = S();
            if (!e) switch (!0) {
            case g:
                e = $(this).attr("data-ios-app");
                break;
            case y:
                e = $(this).attr("data-android-app");
                break;
            case b:
                e = $(this).attr("data-win-app")
            }
            e && $(this).attr("href", e)
        })
    }
    function T(e) {
        var n = e.$el,
        r = function(e) {
            var t = document.location.href || "",
            n = new RegExp("(\\?|&)" + e + "=([^&]+)(&|$)", "i"),
            r = t.match(n);
            return r ? r[2] : ""
        };
        e.getQuery || (e.getQuery = Lizard.P),
        e.getUrlParam ? undefined: e.getUrlParam = r;
        var i = e.getUrlParam("sourceid"),
        s = e.getUrlParam("sales");
        if ((!s || s.length <= 0) && (!i || i.length <= 0)) {
            var o = location.host,
            u = document.referrer,
            f = "";
            if (o) {
                u = u.replace("http://", "").replace("https://", "").split("/")[0].toLowerCase(),
                u.indexOf("baidu") > -1 && (f = "SEO_BAIDU"),
                u.indexOf("google") > -1 && (f = "SEO_GOOGLE"),
                u.indexOf("soso.com") > -1 && (f = "SEO_SOSO"),
                u.indexOf("sogou") > -1 && (f = "SEO_SOGOU"),
                u.indexOf("so.com") > -1 && (f = "SEO_SO"),
                u.indexOf("so.360") > -1 && (f = "SEO_360SO"),
                u.indexOf("bing.com") > -1 && (f = "SEO_BING"),
                u.indexOf("yahoo") > -1 && (f = "SEO_YAHOO");
                if (u.indexOf("youdao") > -1 || u.indexOf("sm.cn") > -1) f = "SEO_YOUDAO";
                if (u.indexOf("jike.com") > -1 || u.indexOf("babylon.com") > -1 || u.indexOf("ask.com") > -1 || u.indexOf("avg.com") > -1 || u.indexOf("easou.com") > -1 || u.indexOf("panguso.com") > -1 || u.indexOf("yandex.com") > -1) f = "SEO_360SO"
            }
        }
        f && f.length > 0 && (s = f),
        (i && +i > 0 || s && s.length > 0) && t.localStorage.oldRemove("APP_DOWNLOAD"),
        i || s ? (s && l(s), i && c(i), a(s || i, $.proxy(function(t) {
            if (!t.appurl || t.appurl.length <= 0) if (e && e.footer && e.footer.rootBox) {
                var r = e.footer.rootBox.find("#dl_app");
                r && r.length > 0 && r.hide()
            }
            e.warning404.tel = t && t.tel ? t.tel: "4000086666",
            x(n)
        },
        e))) : (s && l(s), setTimeout(function() {
            x(n)
        },
        100))
    }
    var s = null,
    o = r.SalesObjectStore.getInstance(),
    u = function(e) {
        return i.baseurl(e)
    },
    a = function(n, r, i) {
        var a = o.get(n);
        if (a) s = a,
        !a.appurl || a.appurl.length <= 0 ? $("#dl_app").hide() : $("#dl_app").show(),
        r && r(a);
        else {
            var f = u(),
            l = "/html5/ClientData/GetSalesInfo/" + n;
            $.ajax({
                url: l,
                type: "POST",
                dataType: "json",
                success: $.proxy(function(u) {
                    var a = {};
                    if (u.ServerCode == 1) {
                        if (u.Data) {
                            for (var f in u.Data) a[f.toLowerCase()] = u.Data[f];
                            u.Data = a,
                            o.set(u.Data, n);
                            var l = 30;
                            u.Data && (u.Data.sales === "ydwxcs" || u.Data.sales === "1622") && (l = 5),
                            !u.Data.appurl || u.Data.appurl.length <= 0 ? $("#dl_app").hide() : $("#dl_app").show(),
                            t.localStorage.oldSet("SALESOBJ", JSON.stringify({
                                data: u.Data,
                                timeout: (new e.Date(e.getServerDate())).addDay(l).format("Y/m/d H:i:s")
                            }))
                        }
                        s = u.Data,
                        r && r(u.Data)
                    } else i && i(u)
                },
                this),
                error: $.proxy(function(e) {
                    i && i(e)
                },
                this)
            })
        }
    },
    f = function() {
        return s
    },
    l = function(e) {
        r.SalesStore.getInstance().set({
            sales: e
        })
    },
    c = function(e) {
        r.SalesStore.getInstance().set({
            sourceid: e
        })
    },
    h = function(e) {
        r.UnionStore.getInstance().set(e)
    },
    p = /400\d{3}\d{4}/i,
    d = /400\s+\d{3}\s+\d{4}/i,
    v = /400-\d{3}-\d{4}/i,
    m = navigator.userAgent,
    g = !!m.match(/(ipad|iphone)/i),
    y = !!m.match(/android/i),
    b = !!m.match(/MSIE/i),
    w = window.replaceStrTel = function(e) {
        var t = f();
        return typeof e == "string" && t && t.tel && (e = e.replace(p, t.tel), e = e.replace(d, t.teltitle), t.teltitle && (e = e.replace(v, t.teltitle.split(" ").join("-")))),
        e
    },
    E = function() {
        var e = null;
        return g ? e = "ios-app": y ? e = "andreod-app": b && (e = "win-app"),
        e
    },
    S = function(e) {
        var t = f();
        if (t) {
            t.isseo && $(".module").show();
            if (t.appurl) return t.appurl;
            var e = t.sid ? t.sid: t.sales;
            return "/market/download.aspx?from=" + e
        }
        return null
    };
    return {
        replaceContent: x,
        replaceStrTel: w,
        setSales: l,
        getSales: f,
        getSalesObject: a,
        setUnion: h,
        setSourceId: c,
        updateSales: T
    }
}),
function() {
    var e = {
        t: 600,
        hasApp: !1,
        key: "HAS_CTRIP_APP",
        appProtocol: "ctrip://wireless",
        openApp: function(t, n, r) {
            var i = e.getAppData(),
            s = Date.now();
            if (i && i != "") return i.hasApp ? typeof t == "function" ? t() && r && r.length > 0 && (window.location = r) : r && r.length > 0 && (window.location = r) : typeof n == "function" && n(),
            ""; (!r || r.length <= 0) && typeof n == "function" && n();
            var o = navigator.userAgent ? navigator.userAgent.toLocaleLowerCase() : "",
            u = o.indexOf("android", 0) != -1 || o.indexOf("adr", 0) != -1 ? 1 : 0,
            a = u && o.indexOf("chrome", 0) != -1 && o.indexOf("nexus", 0) == -1,
            f = $('<iframe style="display: none;"></iframe>');
            f.attr("src", r),
            $("body").append(f),
            a && (r && r.length > 0 && (window.location = r), setTimeout(function() {
                typeof n == "function" && n()
            },
            1)),
            setTimeout(function() {
                e.testApp(s)
            },
            e.t),
            e.setTestResult(t, n)
        },
        testApp: function(t) {
            var n = Date.now();
            n - t < e.t + 200 ? e.hasApp = !1 : e.hasApp = !0
        },
        setTestResult: function(t, n) {
            setTimeout(function() {
                e.hasApp ? typeof t == "function" && t() : typeof n == "function" && n();
                var r = new Date;
                r.setHours(r.getHours() + 1);
                var i = {
                    value: {
                        hasApp: e.hasApp
                    },
                    timeout: r.toUTCString()
                };
                window.localStorage.setItem(e.key, JSON.stringify(i)),
                window.hasApp = e.hasApp
            },
            e.t + 1e3)
        },
        getAppData: function() {
            return "";
            var t, n
        }
    };
    window.AppUtility = e
} ();
var adOptions = adOptions || {};
adOptions.__propertys__ = function() {},
adOptions.initialize = function($super, e) {
    this.data = e || {},
    this.storeKey = "APP_DOWNLOAD",
    $super(e)
},
adOptions.update = function(e) {
    this.isInFooter && (this.remove(), this.isCreate = !1),
    this.rootBox = e.rootBox,
    this.root || (this.root = this.rootBox),
    this.isInFooter = !!this.rootBox.hasClass("js_in_Footer"),
    this.addEvent && (this.removeEvent("onShow"), this.addEvent("onShow", this.onShow))
},
adOptions.createHtml = function() {
    var e = adOptions.getUrlParam("sourceid"),
    t = adOptions.getUrlParam("sales"),
    n = adOptions.getUrlParam("allianceid"),
    r = adOptions.getUrlParam("sid"),
    i = this.isInFooter ? "": "fix_bottom",
    s = "/market/download.aspx?from=H5",
    o = adOptions._get("SALES_OBJECT"),
    u = adOptions._get("UNION"),
    a = adOptions._getCookie("UNION"),
    f = "";
    n && n.length > 0 && r && r.length > 0 && (f = "display:none;");
    if (u || a) f = "display:none;";
    if (o && o.sid && +o.sid > 0) {
        if (!o.appurl || o.appurl.length <= 0) f = "display:none;";
        s = o.appurl ? o.appurl: "/market/download.aspx?from=" + o.sid
    }
    this.checkDeviceSupport() == 0 && (f = "display:none;");
    if (f.length > 0) return $("footer") && $("footer").removeClass("pb85"),
    $('div[data-role="footer"]') && $('div[data-role="footer"]').removeClass("pb85"),
    $("#panel-box") && $("#panel-box").removeClass("pb85"),
    $(".f_list") && $(".f_list").removeClass("pb85"),
    adOptions.saveExpire(1),
    "";
    var l = this.setAppUrl();
    return ['<div data-appurl="' + l + '" id="dl_app" style="' + f + '" class="', i, '"> <div id="icon_text" class="txt_middle"><img src="http://res.m.ctrip.com/html5/content/images/icon_text_s6_1.png"/></div>', ' <a href="' + s + '" id="app_link" class="txt_middle __appaddress__"><img src="http://res.m.ctrip.com/html5/content/images/icon_open_s6.png"/></a>', '<div id="close_icon"></div>', "</div>"].join("")
},
adOptions.getUrlParam = function(e) {
    var t = document.location.href || "",
    n = new RegExp("(\\?|&)" + e + "=([^&]+)(&|$)", "i"),
    r = t.match(n);
    return r ? r[2] : ""
},
adOptions.setAppUrl = function() {
    var e = adOptions._get("SALES"),
    t = AppUtility.appProtocol,
    n = null,
    r = null,
    i = null,
    s = null,
    o = null,
    u = null,
    a = null,
    f = null,
    l = null,
    c = null,
    h = null,
    p = null,
    d = null,
    v = $("#page_id").val();
    0;
    var m = new RegExp("-", "g"),
    g = new RegExp("/", "g");
    if (v && +v > 0) {
        if ( + v == 212092 || +v == 212093 || +v == 212094 || +v == 210090) {
            n = +v == 212092 ? "hotel_inquire": +v == 212093 || +v == 210090 ? "hotel_inland_list": +v == 212094 ? "InlandHotel": "",
            r = window.localStorage ? window.localStorage.getItem("HOTELSEARCHINFO") : null;
            if (r) {
                r = JSON.parse(r),
                +v == 212092 && (r.data && (i = r.data.CheckInDate.replace(m, ""), s = r.data.CheckOutDate.replace(m, ""), o = r.data.CheckInCityID, u = r.data.DistrictId ? +r.data.DistrictId <= 0 ? "": r.data.DistrictId: "", a = r.data.BrandId, f = r.data.BrandName, l = 0), n += "?c1=" + (i || "") + "&c2=" + (s || "") + "&c3=" + (o || "") + "&c4=" + (u || "") + "&c5=" + (a || "") + "&c6=" + (f || "") + "&c7=" + (l || ""));
                if ( + v == 212093 || +v == 210090) r.data && (i = r.data.CheckInDate.replace(m, ""), s = r.data.CheckOutDate.replace(m, ""), o = r.data.CheckInCityID, u = r.data.DistrictId ? +r.data.DistrictId <= 0 ? "": r.data.DistrictId: "", a = 0, f = r.data.BrandId ? +r.data.BrandId <= 0 ? "": r.data.BrandId: "", l = r.data.BrandName || "", c = 0, h = 1, p = "", d = "", +v == 210090 && (h = 2, p = r.data.Latitude, d = r.data.Longitude)),
                n += "?c1=" + (i || "") + "&c2=" + (s || "") + "&c3=" + (o || "") + "&c4=" + (u || "") + "&c5=" + (a || "") + "&c6=" + (f || "") + "&c7=" + (l || "") + "&c8=" + (c || "") + "&c9=" + (h || "") + "&c10=" + (p || "") + "&c11=" + (d || "");
                if ( + v == 212094) {
                    r.data && (i = r.data.CheckInDate.replace(m, ""), s = r.data.CheckOutDate.replace(m, ""), o = r.data.CheckInCityID);
                    var y = window.localStorage ? window.localStorage.getItem("HOTELDETAIL") : null;
                    y && (y = JSON.parse(y), y && y.data && (u = y.data.HotelID)),
                    n += "?checkInDate=" + (i || "") + "&checkOutDate=" + (s || "") + "&cityId=" + (o || "") + "&hotelId=" + (u || "")
                }
            }
        }
        if ( + v == 212001 || +v == 214008) n = +v == 212001 ? "hotel_groupon_list": +v == 214008 ? "hotel_groupon_detail": "",
        +v == 212001 && (r = window.localStorage ? window.localStorage.getItem("TUAN_SEARCH") : null, r = r ? JSON.parse(r) : null, i = r && r.value ? r.value.ctyId: "2", n += "?c1=" + (i || "2")),
        +v == 214008 && (r = window.localStorage ? window.localStorage.getItem("TUAN_DETAILS") : null, r = r ? JSON.parse(r) : null, i = r && r.value ? r.value.id: null, n += "?c1=" + (i || ""));
        if ( + v == 212003 || +v == 212004 || +v == 212009 || +v == 214019 || +v == 214209 || +v == 212042) {
            r = window.localStorage ? window.localStorage.getItem("S_FLIGHT_AirTicket") : null,
            r = r ? JSON.parse(r) : null;
            if (r && r.value && r.value._items && r.value._items.length > 0) {
                i = r.value.tripType,
                s = r.value._items[0].dCtyId,
                o = r.value._items[0].aCtyId,
                u = r.value._items[0].date.replace(g, ""),
                i && +i > 1 && r.value._items.length > 1 && (a = r.value._items[1].date.replace(g, ""));
                var b = window.localStorage ? window.localStorage.getItem("S_FLIGHT_SUBJOIN") : null;
                if ( + v == 214019 || +v == 214209) b = window.localStorage ? window.localStorage.getItem("S_FLIGHT_INTLAirTicket") : null;
                b = b ? JSON.parse(b) : null,
                f = "",
                l = "",
                c = "",
                h = "",
                p = "",
                d = "",
                b && b.value && ( + v == 214019 || +v == 214209 ? (f = 1, +b.value["class"] == 0 && (l = "1"), +b.value["class"] == 1 && (l = "2"), +b.value["class"] == 2 && (l = "3"), +b.value["class"] == 3 && (l = "4"), +b.value.sortRule == 2 && ( + b.value.sortType == 2 && (c = "1"), +b.value.sortType == 1 && (c = "2")), +b.value.sortRule == 1 && ( + b.value.sortType == 2 && (c = "3"), +b.value.sortType == 1 && (c = "4")), +v == 214209 && (l = c = h = "")) : (b.value["departfilter-type"] == "1" && (b.value["departfilter-value"] == "3" && (f = "5"), b.value["departfilter-value"] == "0" && (f = "1")), b.value["depart-sorttype"] == "price" && (b.value["depart-orderby"] == "asc" && (l = "3"), b.value["depart-orderby"] == "desc" && (l = "4")), b.value["depart-sorttype"] == "time" && (b.value["depart-orderby"] == "asc" && (l = "1"), b.value["depart-orderby"] == "desc" && (l = "2")), b.value["departfilter-type"] == "0" && (h = b.value["departfilter-value"] || "", h = h.replace("-", "|"), h = h.replace(":", ""), h = h.replace(":", "")), b.value["departfilter-type"] == "2" && (d = b.value["departfilter-value"] || ""), +v == 212009 && (f = l = c = h = d = "")))
            }
            r = !r && window.localStorage ? window.localStorage.getItem("AIRSTATE_DETAIL_PARAM") : null,
            r = r ? JSON.parse(r) : null,
            r && r.data && (i = r.data.fdate || "", s = r.data.fNo || "", o = r.data.dPort || "", u = r.data.aPort || ""),
            +v == 212003 && (n = "flight_inquire");
            if ( + v == 212009 || +v == 212004) n = i && +i > 1 ? "flight_inland_tolist": "flight_inland_singlelist";
            if ( + v == 214019 || +v == 214209) n = i && +i > 1 ? "flight_int_tolist": "flight_int_singlelist"; + v == 212042 && (n = "flight_board_detail"),
            n += "?c1=" + (i || "") + "&c2=" + (s || "") + "&c3=" + (o || "") + "&c4=" + (u || "") + "&c5=" + (a || "") + "&c6=" + (f || "") + "&c7=" + (l || "") + "&c8=" + (c || "") + "&c9=" + (h || "") + "&c10=" + (p || "") + "&c11=" + (d || "")
        }
        if ( + v == 212071 || +v == 212072) n = +v == 212071 ? "train_inquire": "train_list",
        r = window.localStorage ? window.localStorage.getItem("TRAINSSEARCHINFO") : null,
        r = r ? JSON.parse(r) : null,
        r && r.data && (i = r.data.DepartCityId || "", s = r.data.ArriveCityId || "", o = r.data.DepartDate || "", o = o.replace(m, "")),
        n += "?c1=" + (i || "") + "&c2=" + (s || "") + "&c3=" + (o || "");
        if ( + v == 214040 || +v == 214045 || +v == 214046 || +v == 214041 || +v == 214345 || +v == 214346 || +v == 214042 || +v == 214353 || +v == 214354) {
            i = s = o = u = a = f = "",
            r = window.localStorage ? window.localStorage.getItem("VACATIONS_PRODUCT_LIST_PARAM") : null,
            r = r ? JSON.parse(r) : null,
            +v == 214040 && (n = "vacation_home");
            if ( + v == 214045) {
                n = "vacation_weekend_list";
                if (r && r.value) {
                    i = r.value.dCtyId;
                    if (r.value.qparams || r.value.qparams.length > 0) for (var w in r.value.qparams) {
                        var E = r.value.qparams[w];
                        E && E.type && ( + E.type == 3 && (s = E.val), +E.type == 2 && (o = E.val), +E.type == 6 && (a = E.val), +E.type == 7 && (f = E.val))
                    }
                }
                n += "?cityId=" + (i || "") + "&districtId=" + (s || "") + "&travelDaysId=" + (o || "") + "&levelId=" + (u || "") + "&isSelfProudct=" + (a || "") + "&isDiscount=" + (f || "")
            } + v == 214046 && (n = "vacation_nearby_detail", i = s = "", r = window.localStorage ? window.localStorage.getItem("VACATIONS_PRODUCT_DETAIL_PARAM") : null, r = r ? JSON.parse(r) : null, r && r.value && (i = r.value.dCtyId || "", s = r.value.pid || ""), n += "?departCityId=" + (i || "") + "&productId=" + (s || "")),
            +v == 214041 && (n = "vacation_group_inquire", i = s = o = u = "", r = window.localStorage ? window.localStorage.getItem("VACATIONS_GROUP_SEARCH_PARAM") : null, r = r ? JSON.parse(r) : null, r && r.value && (i = r.value.dCtyId, s = r.value.destKwd), n += "?departCityId=" + (i || "") + "&arriveName=" + (s || "") + "&travelDaysId=" + (o || "") + "&levelId=" + (u || ""));
            if ( + v == 214345) {
                n = "vacation_group_list",
                i = s = o = u = a = f = l = "",
                r = window.localStorage ? window.localStorage.getItem("VACATIONS_PRODUCT_LIST_PARAM") : null,
                r = r ? JSON.parse(r) : null;
                if (r && r.value) {
                    i = r.value.dCtyId,
                    s = r.value.destKwd;
                    if (r.value.qparams || r.value.qparams.length > 0) for (var w in r.value.qparams) {
                        var E = r.value.qparams[w];
                        E && E.type && ( + E.type == 3 && (o = E.val), +E.type == 2 && (u = E.val), +E.type == 6 && (f = E.val), +E.type == 7 && (l = E.val))
                    }
                }
                n += "?departCityId=" + (i || "") + "&arriveName=" + (s || "") + "&districtId=" + (o || "") + "&travelDaysId=" + (u || "") + "&levelId=" + (a || "") + "&isSelfProduct=" + (f || "") + "&isDiscount=" + (l || "")
            } + v == 214346 && (n = "vacation_group_detail", i = s = "", r = window.localStorage ? window.localStorage.getItem("VACATIONS_PRODUCT_DETAIL_PARAM") : null, r = r ? JSON.parse(r) : null, r && r.value && (i = r.value.dCtyId || "", s = r.value.pid || ""), n += "?departCityId=" + (i || "") + "&productId=" + (s || ""));
            if ( + v == 214042) {
                n = "vacation_cruises_inquire",
                r = window.localStorage ? window.localStorage.getItem("VACATIONS_CRUISE_SEARCH_PARAM") : null,
                r = r ? JSON.parse(r) : null,
                i = s = "";
                if (r && r.value) {
                    i = r.value.dCtyId || "";
                    if (r.value.qparams || r.value.qparams.length > 0) for (var w in r.value.qparams) {
                        var E = r.value.qparams[w]; + E.type == 14 && (s = E.val)
                    }
                }
                n += "?departCityId=" + (i || "") + "&routeId=" + (s || "")
            }
            if ( + v == 214353) {
                n = "vacation_cruises_list",
                r = window.localStorage ? window.localStorage.getItem("VACATIONS_PRODUCT_LIST_PARAM") : null,
                r = r ? JSON.parse(r) : null,
                i = s = o = u = a = f = l = "";
                if (r && r.value) {
                    i = r.value.dCtyId;
                    if (r.value.qparams || r.value.qparams.length > 0) for (var w in r.value.qparams) {
                        var E = r.value.qparams[w];
                        E && ( + E.type == 14 && (s = E.val), +E.type == 10 && (o = E.val), +E.type == 11 && (u = E.val), +E.type == 12 && (a = E.val), +E.type == 6 && (f = E.val), +E.type == 7 && (l = E.val))
                    }
                }
                n += "?departCityId=" + (i || "") + "&routeId=" + (s || "") + "&companyId=" + (o || "") + "&productFormId=" + (u || "") + "&portDepartId=" + (a || "") + "&isSelfProduct=" + (f || "") + "&isDiscount=" + (l || "")
            } + v == 214354 && (n = "vacation_cruises_detail", r = window.localStorage ? window.localStorage.getItem("VACATIONS_PRODUCT_DETAIL_PARAM") : null, r = r ? JSON.parse(r) : null, i = s = "", r && r.value && (i = r.value.dCtyId || "", s = r.value.pid || ""), n += "?departCityId=" + (i || "") + "&productId=" + (s || ""))
        }
    }
    t += n ? "/" + n: "";
    var S = this.getCurrentView();
    return S && S.getAppUrl && (t = AppUtility.appProtocol + S.getAppUrl()),
    t.indexOf("?") <= -1 && (t += "?v=2"),
    e && e.sourceid && +e.sourceid > 0 ? t += "&extendSourceID=" + e.sourceid: t += "&extendSourceID=8888",
    0,
    t
},
adOptions.onShow = function() {
    this.root.off("click"),
    this.root.find("#close_icon").on("click", $.proxy(function() {
        this.saveExpire(1),
        this.hide(),
        $("footer") && $("footer").removeClass("pb85"),
        $("#panel-box") && $("#panel-box").removeClass("pb85"),
        $('div[data-role="footer"]') && $('div[data-role="footer"]').removeClass("pb85"),
        $(".f_list") && $(".f_list").removeClass("pb85")
    },
    this));
    var e = this;
    this.root.find("#app_link").off("click").on("click",
    function(t) {
        t.preventDefault();
        var n = $(this).attr("href"),
        r = e.setAppUrl(),
        i = $("#page_id").val(),
        s = navigator.userAgent ? navigator.userAgent.toLocaleLowerCase() : "";
        0;
        var o = s.indexOf("mac", 0) != -1 || navigator.userAgent.indexOf("ios", 0) != -1 ? 1 : 0;
        return o ? (window.location = r, setTimeout(function() {
            window.location = "itms-apps://itunes.apple.com/cn/app/id379395415?mt=8"
        },
        30)) : AppUtility.openApp(function() {
            return e.saveExpire(),
            !0
        },
        function() {
            window.location = n
        },
        r),
        !1
    }),
    this.checkDeviceSupport() == 0 && (this.root.attr("id") == "dl_app" && this.root.hide(), $("footer") && $("footer").removeClass("pb85"), $('div[data-role="footer"]') && $('div[data-role="footer"]').removeClass("pb85"), $("#panel-box") && $("#panel-box").removeClass("pb85"), $(".f_list") && $(".f_list").removeClass("pb85"))
},
adOptions.checkDeviceSupport = function() {
    var e = navigator.userAgent ? navigator.userAgent.toLocaleLowerCase() : "",
    t = e.indexOf("mac", 0) != -1 || navigator.userAgent.indexOf("ios", 0) != -1 ? 1 : 0,
    n = e.indexOf("android", 0) != -1 || e.indexOf("adr", 0) != -1 ? 1 : 0;
    return t == 0 && n == 0 ? !1 : !0
},
adOptions.saveExpire = function(e) {
    var t = {
        isExpire: 1
    },
    n = new Date;
    e && (t.isClose = e),
    n.setDate(n.getDate() + 1),
    this.storeKey || (this.storeKey = "APP_DOWNLOAD"),
    this._set(this.storeKey, t, n.toUTCString())
},
adOptions.saveAutoDown = function(e) {
    var t = {
        isAutoDown: 1,
        sid: e
    },
    n = new Date;
    n.setDate(n.getDate() + 1),
    this._set("APP_AUTODOWNLOAD", t, n.toUTCString())
},
adOptions.appDownload = function() {
    var e = this,
    t = adOptions._get("SALES_OBJECT"),
    n = AppUtility.appProtocol;
    t && t.sid && +t.sid > 0 ? n += "?extendSourceID=" + t.sid: n += "?extendSourceID=8888";
    var r = navigator.userAgent ? navigator.userAgent.toLocaleLowerCase() : "",
    i = r.indexOf("mac", 0) != -1 || navigator.userAgent.indexOf("ios", 0) != -1 ? 1 : 0;
    i ? (window.location = n, setTimeout(function() {
        window.location = "itms-apps://itunes.apple.com/cn/app/id379395415?mt=8"
    },
    30)) : AppUtility.openApp(function() {
        return ! 0
    },
    function() {
        var e = "http://m.ctrip.com/market/download.aspx?from=H5";
        t && (t.appurl && t.appurl.length > 0 ? e = t.appurl: t.sid && +t.sid > 0 && (e = "http://m.ctrip.com/market/download.aspx?from=" + t.sid)),
        window.location.href = e
    },
    n)
},
adOptions.checkForceDownload = function(e) {},
adOptions.checkAutoDownload = function() {
    var e = this,
    t = this.getUrlParam("sourceid"),
    n = this.getUrlParam("openapp"),
    r = this.getUrlParam("downapp");
    if (!t || t.length <= 0 || +t <= 0) return;
    var i = 0,
    s = 0,
    o = 0,
    u = 0,
    a = navigator.userAgent ? navigator.userAgent.toLocaleLowerCase() : "",
    f = a.indexOf("mac", 0) != -1 || navigator.userAgent.indexOf("ios", 0) != -1 ? 1 : 0,
    l = a.indexOf("android", 0) != -1 || a.indexOf("adr", 0) != -1 ? 1 : 0;
    r && (f && ( + r == 2 || +r == 3) && (i = 1), l && ( + r == 1 || +r == 3) && (s = 1));
    var c = null;
    n && (f && ( + n == 2 || +n == 3 ? u = 1 : (u = 0, c = null)), l && ( + n == 1 || +n == 3 ? o = 1 : (o = 0, c = null)));
    if (o || u) c = this.setAppUrl();
    var h = adOptions._get("SALES_OBJECT");
    if (f) {
        0;
        if (c && c.length > 0) {
            $(".iOpenApp").remove();
            var p = $('<iframe name="iOpen" class="iOpenApp" frameborder="0" style="display:none;"></iframe>');
            p.attr("src", c),
            $("body").append(p)
        }
        adOptions.isAutoDown(t) || !i || +i != 1 ? (adOptions.saveExpire(0), adOptions.saveAutoDown(t)) : (adOptions.saveExpire(0), adOptions.saveAutoDown(t), setTimeout(function() {
            window.location = "itms-apps://itunes.apple.com/cn/app/id379395415?mt=8"
        },
        30))
    } else AppUtility.openApp(function() {
        return adOptions.saveExpire(0),
        adOptions.saveAutoDown(t),
        !0
    },
    function() {
        if (adOptions.isAutoDown(t)) return adOptions.saveExpire(0),
        adOptions.saveAutoDown(t),
        !0;
        if (navigator.connection && navigator.connection.type != navigator.connection.WIFI) return adOptions.saveExpire(0),
        adOptions.saveAutoDown(t),
        !0;
        var e = "http://m.ctrip.com/market/download.aspx?from=" + t;
        if (l) {
            if (!s || +s != 1) return ! 0;
            e += "&App=3"
        }
        f && (e += "&App=1"),
        h && h.sid && +h.sid > 0 && +h.sid == +t && h.appurl && h.appurl.length > 0 && (e = h.appurl),
        adOptions.saveExpire(0),
        adOptions.saveAutoDown(t),
        window.location.href = e
    },
    c)
},
adOptions.create = function() {
    $("body").find("iframe").remove();
    if (!this.isCreate && !this.isExpire() && this.status !== this.STATE_ONCREATE) {
        var e = this.createHtml();
        e && e.length > 0 ? (this.root = $(e), this.rootBox.append(this.root), this.trigger("onCreate")) : ($("footer") && $("footer").removeClass("pb85"), $('div[data-role="footer"]') && $('div[data-role="footer"]').removeClass("pb85"), $("#panel-box") && $("#panel-box").removeClass("pb85"), $(".f_list") && $(".f_list").removeClass("pb85")),
        this.isCreate = !0
    } else $("footer") && $("footer").removeClass("pb85"),
    $("#panel-box") && $("#panel-box").removeClass("pb85"),
    $(".f_list") && $(".f_list").removeClass("pb85"),
    $('div[data-role="footer"]') && $('div[data-role="footer"]').removeClass("pb85");
    var t = adOptions.createHtml(),
    n = this;
    setTimeout(function() {
        adOptions.checkAutoDownload.call(n)
    },
    2e3)
},
adOptions.isExpire = function() {
    var e = this._get(this.storeKey);
    return e && e.isClose ? !0 : !1
},
adOptions.isAutoDown = function(e) {
    var t = this._get("APP_AUTODOWNLOAD");
    return t && t.isAutoDown ? !0 : !1
},
adOptions._getCookie = function(e) {
    var t = null;
    if (e) {
        var n = new RegExp("\\b" + e + "=([^;]*)\\b"),
        r = n.exec(document.cookie);
        t = r && unescape(r[1])
    } else {
        var i = document.cookie.split(";"),
        s,
        o;
        t = {};
        for (s = 0, len = i.length; s < len; s++) o = i[s].split("="),
        t[o[0]] = unescape(o[2])
    }
    return t
},
adOptions.setCurrentView = function(e) {
    this.curView = e
},
adOptions.getCurrentView = function() {
    return this.curView
},
adOptions._get = function(e) {
    var t = window.localStorage.getItem(e);
    if (t) {
        t = JSON.parse(t);
        if (Date.parse(t.timeout) >= new Date) return t.value || t.data
    }
    return ""
},
adOptions._set = function(e, t, n) {
    var r = {
        value: t,
        timeout: n
    };
    window.localStorage.setItem(e, JSON.stringify(r))
};
if (window.location.pathname.indexOf("webapp") > -1 || window.localStorage.getItem("isInApp")) define("cAdView", ["cBase", "cUIAbstractView", "libs", "cStore"],
function(e, t, n, r) {
    var i = new e.Class(t, adOptions);
    return i.getInstance = function() {
        return this.instance ? this.instance: this.instance = new this
    },
    i
});
else {
    adOptions.show = function() {
        this.status = "",
        this.create(),
        this.onShow()
    },
    adOptions.hide = function() {
        this.root.hide()
    },
    adOptions.trigger = function() {},
    adOptions.remove = function() {
        $("#dl_app").remove()
    };
    var config = {
        rootBox: $("#footer")
    };
    setTimeout(function() {
        adOptions.initialize(function() {},
        config);
        var e = adOptions;
        e.update(config),
        e.show()
    },
    800)
}
adOptions.autoOpenDownApp = function(e, t, n) {
    var r = t || 3,
    i = n || 3,
    s = 0,
    o = 0,
    u = 0,
    a = 0,
    f = navigator.userAgent ? navigator.userAgent.toLocaleLowerCase() : "",
    l = f.indexOf("mac", 0) != -1 || navigator.userAgent.indexOf("ios", 0) != -1 ? 1 : 0,
    c = f.indexOf("android", 0) != -1 || f.indexOf("adr", 0) != -1 ? 1 : 0;
    l && (s = 1),
    c && (o = 1);
    var h = null;
    l && (a = 1),
    c && (u = 1);
    try {
        h = this.setAppUrl()
    } catch(p) {}
    var d = adOptions._get("SALES_OBJECT");
    l ? (h && h.length > 0 && (window.location = h), setTimeout(function() {
        window.location = "itms-apps://itunes.apple.com/cn/app/id379395415?mt=8"
    },
    30)) : AppUtility.openApp(function() {
        return ! 0
    },
    function() {
        var t = "http://m.ctrip.com/market/download.aspx?from=" + e;
        c && (t += "&App=3"),
        l && (t += "&App=1"),
        d && d.sid && +d.sid > 0 && +d.sid == +e && d.appurl && d.appurl.length > 0 && (t = d.appurl),
        window.location.href = t
    },
    h)
},
adOptions.popupPromo = function() {
    var e = function(e, t) {
        t = t || "data";
        var n = window.localStorage ? window.localStorage.getItem(e) : null;
        if (n) {
            n = JSON.parse(n);
            if (n && n[t]) return n[t]
        }
        return {}
    },
    t = !1,
    n,
    r,
    i;
    if (adOptions._getCookie("Union")) {
        var s = {};
        adOptions._getCookie("Union").replace(/([^&=]+)=([^&]*)/g,
        function(e, t, n) {
            s[t] = n
        }),
        r = s.AllianceID
    }
    n = adOptions.getUrlParam("sourceid") || e("SALES").sourceid || e("SALES_OBJECT", "value").sid,
    i = adOptions.getUrlParam("sid") || e("UNION").SID;
    if (!n) return;
    r = adOptions.getUrlParam("allianceid") || e("UNION").AllianceID || r,
    keywords = ["baidu.com", "google.com", "soso.com", "so.com", "bing.com", "yahoo", "youdao.com", "sogou.com", "so.360.cn", "jike.com", "babylon.com", "ask.com", "avg.com", "easou.com", "panguso.com", "yandex.com", "sm.cn"],
    sourceids = ["1825", "1826", "1827", "1828", "1829", "1831", "1832", "1833", "1830"],
    sIds = [130028, 130029, 409197, 353693, 130026, 135366, 297877, 130033, 130034, 131044, 110603, 353694, 130678, 135371, 353696, 130701, 135374, 110611, 353698, 130709, 135376, 110614, 426566, 426568, 353701, 130727, 135379, 139029, 110620, 353703, 130761, 135383, 353704, 130788, 135388, 110630, 353699, 353700, 189318, 135390, 130860, 130875, 303055, 156043, 130862, 130863, 130876, 130859, 240799, 159295, 442174, 176275, 240801, 231208, 278782, 326416, 353680, 295517, 130999, 130907, 112563, 176220, 110647, 3752, 125344, 144532, 120414, 171210, 86710, 110276, 447459],
    allianceids = ["4897", "4899", "4900", "4901", "4902", "4903", "4904", "5376", "5377", "3052", "13964", "13963", "18887"],
    matchPopup = function() {
        var e = !1,
        t = !1;
        for (var n = 0,
        r = keywords.length; n < r; n++) if (document.referrer.match(keywords[n])) {
            e = !0;
            break
        }
        for (var n = 0,
        r = sIds.length; n < r; n++) if ( + i === sIds[n]) {
            t = !0;
            break
        }
        return e && t
    },
    0;
    if ((adOptions.getUrlParam("sepopup") == 1 || matchPopup()) && document.referrer.indexOf("sepopup=1") < 0 && document.getElementById("se-popup") === null) {
        var o = e("SALES_OBJECT", "value"),
        u = o.tel || "4000086666",
        a = ['<div class="se-popup" id="se-popup">', '<div class="se-main" style="width:240px;height:329px;margin-top:-165px;margin-left:-120px;position:fixed;top:50%;left:50%;z-index:10000;">', '<img src="http://res.m.ctrip.com/market/images/popup.png" width="100%" />', '<a class="se-close" href="javascript:void(0)" style="position:absolute;width:19px;height:19px;top:9px;right:9px;"></a>', '<a class="se-openapp __appaddress__" href="/market/download.aspx?from=MPopup" style="position:absolute;width:154px;height:43px;bottom:97px;right:43px;"></a>', '<a class="se-phone __hreftel__" href="tel:' + u + '" style="position:absolute;width:154px;height:30px;bottom:60px;right:43px;"></a>', '<a class="se-continue" href="javascript:void(0)" style="position:absolute;width:154px;height:30px;bottom:21px;right:43px;"></a>', "</div>", '<div class="se-mask" style="position:fixed;left:0px;top:0px;width:100%;height:100%;z-index:9999;background:rgba(0,0,0,.2);"></div>', "</div>"].join("");
        $(a).appendTo($(document.body)),
        $(".se-close").on("click",
        function() {
            $(".se-popup").css("display", "none")
        }),
        $(".se-continue").on("click",
        function() {
            $(".se-popup").css("display", "none")
        }),
        $(".se-openapp").on("click",
        function() {
            return adOptions.autoOpenDownApp(n, 3, 3),
            !1
        })
    }
},
setTimeout(function() {
    adOptions.popupPromo()
},
2e3),
define("cUILoading", ["libs", "cBase", "cUILayer"],
function(e, t, n) {
    var r = {},
    i = {
        prefix: "cui-"
    },
    s = {};
    return s["class"] = i.prefix + "loading",
    s.onCreate = function() {},
    s.onShow = function() {
        this.contentDom.html('<div class="cui-breaking-load"><div class="cui-i cui-w-loading"></div><div class="cui-i cui-m-logo"></div></div>'),
        this.reposition()
    },
    r.__propertys__ = function() {
        this.contentDom,
        this.loadHtml = ""
    },
    r.initialize = function($super) {
        $super(s)
    },
    r.setHtml = function(e) {
        this.loadHtml = e
    },
    new t.Class(n, r)
}),
define("cUIBubbleLayer", ["cBase", "cUIAbstractView"],
function(e, t) {
    var n = {};
    return n.__propertys__ = function() {
        this.itemTemplate = !1,
        this.triggerEl = null,
        this.click = function() {}
    },
    n.initialize = function($super, e) {
        for (var t in e) this[t] = e[t];
        $super(e),
        this.bindEvent(),
        this.show(),
        this.hide()
    },
    n.showMenu = function(e) {
        for (var t in e) this[t] = e[t];
        e.data && this.init(),
        e.dir && (this.el.removeClass("f-layer-before"), this.el.removeClass("f-layer-after"), e.dir == "up" ? this.el.addClass("f-layer-before") : this.el.addClass("f-layer-after")),
        this.adjustEl(),
        this.show()
    },
    n.bindEvent = function() {
        this.addEvent("onHide",
        function() {
            this.root.off("click"),
            this.clsLayer && document.removeEventListener("click", this.clsLayer, !0)
        }),
        this.addEvent("onShow",
        function() {
            this.init(),
            this.adjustEl(),
            this.setzIndexTop(),
            this.root.on("click", $.proxy(function(e) {
                var t = $(e.target),
                n = !1;
                for (;;) {
                    if (t.attr("id") == this.id) break;
                    if (t.attr("data-flag") == "c") {
                        n = !0;
                        break
                    }
                    t = t.parent()
                }
                if (!n) return;
                typeof this.click == "function" && this.click.call(this, this.data[t.attr("data-index")])
            },
            this)),
            this.clsLayer = $.proxy(function(e) {
                var t = $(e.target),
                n = !1;
                for (;;) {
                    if (t.attr("id") == this.id) {
                        n = !0;
                        break
                    }
                    if (!t[0]) break;
                    t = t.parent()
                }
                n == 0 && this.hide()
            },
            this),
            document.addEventListener("click", this.clsLayer, !0)
        })
    },
    n.adjustEl = function() {
        if (!this.triggerEl) return;
        var e = this.triggerEl.offset();
        this.el.css({
            width: "",
            transform: ""
        });
        var t = 6;
        this.dir == "up" ? this.el.css({
            width: e.width - t,
            "-webkit-transform": "translate(" + (e.left + 2) + "px, " + (e.top + e.height + 8) + "px) translateZ(0px)"
        }) : this.el.css({
            width: e.width - t,
            "-webkit-transform": "translate(" + (e.left + 2) + "px, " + (e.top - this.el.offset().height - 8) + "px) translateZ(0px)"
        })
    },
    n.init = function() {
        if (!this.data) return;
        this.tmpt = _.template(['<ul class="cui-f-layer ' + (this.dir ? this.dir == "up" ? "f-layer-before": "f-layer-after": "") + '" style="position: absolute; top: 0; left: 0; ">', "<% for(var i = 0, len = data.length; i < len; i++) { %>", "<% var itemData = data[i]; %>", '<li data-index="<%=i%>" data-flag="c"  >' + (this.itemTemplate ? this.itemTemplate: "<%=itemData.name %>") + "</li>", "<% } %>", "</ul>"].join(""));
        var e = this.tmpt({
            data: this.data
        });
        this.root.html(e),
        this.el = this.root.find(".cui-f-layer")
    },
    n.createHtml = function() {
        return ""
    },
    new e.Class(t, n)
}),
window.onunload = function() {},
define("cUIView", ["libs", "cUIAlert", "cUIWarning", "cUIHeadWarning", "cUIWarning404", "cUIToast", "cSales", "cStorage", "cBase", "CommonStore", "cUtility", "cAdView", "cUILoading", "cUIBubbleLayer"],
function(e, t, n, r, i, s, o, u, a, f, l, c, h, p) {
    function d(e, t, n) {
        e && e.originalEvent && alert(e.originalEvent.message + " " + t + " " + n)
    }
    function k() {
        return ! 1;
        var e
    }
    var v = u.localStorage;
    document.body && (document.body.tabIndex = 1e4);
    var m = new t({
        title: "提示信息",
        message: "",
        buttons: [{
            text: "知道了",
            click: function() {
                this.hide()
            }
        }]
    }),
    g = new t({
        title: "提示信息",
        message: "您的订单还未完成，是否确定要离开当前页面？",
        buttons: [{
            text: "取消",
            click: function() {
                this.hide()
            },
            type: t.STYLE_CANCEL
        },
        {
            text: "确定",
            click: function() {
                this.hide()
            },
            type: t.STYLE_CONFIRM
        }]
    }),
    y = new n({
        title: ""
    }),
    b = new r({
        title: ""
    }),
    w = new i,
    E = new h,
    S = new s,
    x = new p,
    T = [".fix_bottom", ".fix_b", "header", ".order_btnbox"],
    N = ".cont_blue , .cont_blue1",
    C = null;
    return Backbone.View.extend({
        ENUM_STATE_NOCREATE: 0,
        ENUM_STATE_CREATE: 1,
        ENUM_STATE_LOAD: 2,
        ENUM_STATE_SHOW: 3,
        ENUM_STATE_HIDE: 4,
        pageid: 0,
        hpageid: 0,
        scrollPos: {
            x: 0,
            y: 0
        },
        header: null,
        footer: null,
        cSales: o,
        warning: null,
        alert: null,
        onCreate: function() {},
        viewInitialize: function() {},
        initialize: function(e, t, n) {
            this.$el.addClass("sub-viewport"),
            this.id = _.uniqueId("viewport"),
            this.$el.attr("id", "id_" + this.id),
            this.viewname = n,
            this.pageid && this.$el.attr("page-id", this.pageid),
            this.viewdata = {},
            this.appliction = t,
            this.request = e,
            this.$el.attr("page-url", this.request.viewpath),
            this.state = this.ENUM_STATE_CREATE,
            this.alert = m,
            this.warning = y,
            this.headwarning = b,
            this.warning404 = w,
            this.loading = E,
            this.toast = S,
            this.bubbleLayer = x,
            this.confirm = g,
            _.isArray(this.css) && this.appendCss(this.css),
            c && (this.footer = c.getInstance()),
            this.debug();
            try {
                this.onCreate()
            } catch(r) {}
        },
        _initializeHeader: function() {
            var e = this;
            this.header.backUrl && this.$el.on("click", "#js_return",
            function() {
                e.back(e.header.backUrl)
            }),
            this.header.home && this.$el.delegate("#js_home", "click",
            function() {
                e.home()
            }),
            this.header.phone && this.$el.find("#js_phone").attr("href", "tel:" + this.header.phone),
            this.header.title && this.$el.find("header h1").text(this.header.title),
            this.header.subtitle && this.$el.find("header p").text(this.header.subtitle),
            this.header.rightAction && this.$el.delegate("header div", "click", this.header.rightAction)
        },
        _initializeFooter: function() {
            if (l.isInApp()) return;
            if (this.footer) {
                this.footer.hide(),
                this.footer.setCurrentView(this);
                if (this.hasAd && !this.footer.isExpire()) {
                    var e = this.adContainer ? this.$el.find("#" + this.adContainer) : $("#footer"),
                    t = this.footer.rootBox;
                    t && t.attr("id") != e.attr("id") && (this.footer.remove(), this.footer.isCreate = !1),
                    this.footer.update({
                        rootBox: e
                    }),
                    this.footer.show()
                }
            }
        },
        __onLoad: function(e) {
            if (location.href.indexOf("ugly_andriod2") > 0) {
                window.location = "http://m.ctrip.com/html5/";
                return
            }
            this.TEST_ANDRIOD_STORAGE = 1,
            v.set("TEST_ANDRIOD_STORAGE", 1),
            document.activeElement && document.activeElement.blur(),
            this.getServerDate(),
            this.disposeChannel(),
            this.header = this._getDefaultHeader(),
            this.state = this.ENUM_STATE_LOAD,
            this.onLoad && this.onLoad(e)
        },
        __onShow: function() {
            document.activeElement && document.activeElement.blur(),
            document.activeElement.blur,
            this.state = this.ENUM_STATE_SHOW,
            window.scrollTo(0, 0);
            try {
                this.onShow && this.onShow()
            } catch(e) {}
            this._sendUbt(),
            this._initializeHeader(),
            this._initializeFooter(),
            o.updateSales(this),
            this.onBottomPull && (this._onWidnowScroll = $.proxy(this.onWidnowScroll, this), this.addScrollListener()),
            this._sendGa(),
            this._sendKenshoo(),
            this._sendMarin(),
            this.resetViewMinHeight(),
            this.FixedInput || (this.$("input").on("focus",
            function(e) {
                if (e.target.type == "tel" || e.target.type == "text") C || (k(), C = setInterval(function() {
                    k()
                },
                500))
            }), this.FixedInput = !0);
            if (!v.get("TEST_ANDRIOD_STORAGE") || v.get("TEST_ANDRIOD_STORAGE") != this.TEST_ANDRIOD_STORAGE) {
                if (location.href.indexOf("ugly_andriod1") > 0) {
                    window.location.search = "ugly_andriod2=" + Math.random();
                    return
                }
                window.location.search = "ugly_andriod1=" + Math.random();
                return
            }
        },
        resetViewMinHeight: function() {},
        __onHide: function(e) {
            this.state = this.ENUM_STATE_HIDE,
            this.onHide && this.onHide(e),
            this.hideHeadWarning(),
            this.hideWarning(),
            this.hideLoading(),
            this.hideWarning404(),
            this.hideToast(),
            this.hideConfirm(),
            this.hideMessage(),
            this.onBottomPull && this.removeScrollListener()
        },
        showLoading: function() {
            this.loading.show(),
            this.loading.firer = this
        },
        hideLoading: function() { (!this.loading.firer || this.loading.firer == this) && this.loading.hide()
        },
        forward: function(e, t) {
            this.appliction.forward.apply(null, arguments)
        },
        back: function(e) {
            this.appliction.back.apply(null, arguments)
        },
        jump: function(e, t) {
            l.isInApp() ? (e = e.replace(window.BASEURL, ""), this.forward(e)) : t ? window.location.replace(e) : window.location.href = e
        },
        home: function() {
            this.appliction.forward("")
        },
        setTitle: function(e) {
            this.appliction.setTitle("携程旅行网-" + e)
        },
        restoreScrollPos: function() {
            window.scrollTo(this.scrollPos.x, this.scrollPos.y)
        },
        getQuery: function(e) {
            return this.request.query[e] || null
        },
        getPath: function(e) {
            return this.request.path[e] || null
        },
        getRoot: function() {
            return this.request.root || null
        },
        showMessage: function(e, t) {
            this.alert.setViewData({
                message: e,
                title: t
            }),
            this.alert.show()
        },
        hideMessage: function() {
            this.alert.hide()
        },
        showConfirm: function(e, n, r, i, s, o) {
            typeof e == "object" && e.message ? this.confirm.setViewData(e) : this.confirm.setViewData({
                message: e,
                title: n,
                buttons: [{
                    text: o || "取消",
                    click: function() {
                        typeof i == "function" && i(),
                        this.hide()
                    },
                    type: t.STYLE_CANCEL
                },
                {
                    text: s || "确定",
                    click: function() {
                        typeof r == "function" && r(),
                        this.hide()
                    },
                    type: t.STYLE_CONFIRM
                }]
            }),
            this.confirm.show()
        },
        hideConfirm: function() {
            this.confirm.hide()
        },
        showWarning: function(e, t) {
            e && this.warning.setTitle(e, t),
            this.warning.show()
        },
        hideWarning: function() {
            this.warning.hide()
        },
        showHeadWarning: function(e, t, n) {
            e && this.headwarning.setTitle(e, t, n),
            this.headwarning.show()
        },
        hideHeadWarning: function() {
            this.headwarning.hide()
        },
        showBubbleLayer: function(e) {
            this.bubbleLayer.showMenu(e)
        },
        hideBubbleLayer: function() {
            this.bubbleLayer.hide()
        },
        showWarning404: function(e, t) {
            e && this.warning404.retryClick(e),
            this.warning404.show(),
            t && this.warning404.options(t),
            this.warning404.firer = this
        },
        hideWarning404: function() { (!this.warning404.firer || this.warning404.firer === this) && this.warning404.hide()
        },
        showNoHeadWarning: function(e, t) {
            e && this.NoHeadWarning.setContent(e, t),
            this.NoHeadWarning.show()
        },
        showToast: function(e, t, n, r) {
            if (this.toast.isShow()) return;
            r = typeof r != "undefined" ? r: !0,
            this.toast.show(e, t, n, r),
            this.toast.firer = this
        },
        hideToast: function() { (!this.toast.firer || this.toast.firer == this) && this.toast.hide()
        },
        updateHeader: function(e) {
            for (var t in e) this.header[t] = e[t];
            this._initializeHeader()
        },
        _getDefaultHeader: function() {
            return {
                backUrl: null,
                home: !1,
                phone: null,
                title: null,
                subtitle: null,
                rightAction: null
            }
        },
        getServerDate: function(e) {
            return l.getServerDate(e)
        },
        now: function() {
            return l.getServerDate()
        },
        debug: function() {
            var e = this.request.query.debug || v.get("DEBUG");
            e == "yes" ? ($(window).unbind("error", d), $(window).bind("error", d), v.set("DEBUG", e, (new a.Date(a.getServerDate())).addDay(1).valueOf())) : e == "no" && ($(window).unbind("error", d), v.remove("DEBUG"))
        },
        _sendUbt: function() {
            if (window.$_bf && window.$_bf.loaded == 1) {
                var e = this.request.query,
                t = $("#page_id"),
                n = $("#bf_ubt_orderid"),
                r,
                i,
                s = "";
                if (l.isInApp()) {
                    if (this.hpageid == 0) return;
                    r = "http://hybridm.ctrip.com/" + location.pathname + location.hash,
                    i = this.hpageid
                } else {
                    if (this.pageid == 0) return;
                    r = location.href,
                    i = this.pageid
                }
                e && e.orderid && (s = e.orderid),
                t.length == 1 && t.val(i),
                n.length == 1 && n.val(s),
                window.$_bf.asynRefresh({
                    page_id: i,
                    orderid: s,
                    url: r
                })
            } else l.isInApp() || setTimeout($.proxy(this._sendUbt, this), 300)
        },
        _sendGa: function() {
            if (typeof _gaq != "undefined") {
                var e = this._getAurl();
                _gaq.push(["_trackPageview", e])
            } else setTimeout($.proxy(this._sendGa, this), 300)
        },
        _sendKenshoo: function() {
            var e = this.request.query;
            if (e && e.orderid) {
                var t = "https://2113.xg4ken.com/media/redir.php?track=1&token=8515ce29-9946-4d41-9edc-2907d0a92490&promoCode=&valueCurrency=CNY&GCID=&kw=&product=";
                t += "&val=" + e.val || e.price + "&orderId=" + e.orderid + "&type=" + e.type;
                var n = "<img style='position: absolute;' width='1' height='1' src='" + t + "'/>";
                $("body").append(n)
            }
        },
        _sendMarin: function() {
            var e = this.request.query;
            if (e && e.orderid) {
                var t = "https://tracker.marinsm.com/tp?act=2&cid=6484iki26001&script=no";
                t += "&price=" + e.val || e.price + "&orderid=" + e.orderid + "&convtype=" + e.type;
                var n = "<img style='position: absolute;' width='1' height='1' src='" + t + "'/>";
                $("body").append(n)
            }
        },
        _getAurl: function() {
            var e = this.request.root,
            t;
            return this.request.viewpath && (e += "#" + this.request.viewpath),
            this.request.path.length > 0 && (t = $.param(this.request.query), e += "!" + this.request.path.join("/") + (t.length ? "?" + t: "")),
            e
        },
        disposeChannel: function() {
            var e = this.getQuery("allianceid"),
            t = this.getQuery("sid"),
            n = this.getQuery("ouid"),
            r = !1,
            i = ["baidu.com", "google.com", "soso.com", "so.com", "bing.com", "yahoo", "youdao.com", "sogou.com", "so.360.cn", "jike.com", "babylon.com", "ask.com", "avg.com", "easou.com", "panguso.com", "yandex.com", "sm.cn"];
            if (!e || !t) {
                for (var s = 0,
                o = i.length; s < o; s++) if (document.referrer.match(i[s])) {
                    r = !0;
                    break
                }
            } else r = !0;
            if (r && this.footer && this.footer.rootBox) {
                var u = this.footer.rootBox.find("#dl_app");
                u.length > 0 && u.hide()
            }
        },
        getGuid: function() {
            return l.getGuid()
        },
        setTitle: function(e) {
            document.title = e
        },
        appendCss: function(e) {
            if (!e) return;
            for (var t = 0,
            n = e.length; t < n; t++) this.css[e[t]] || (this.head.append($('<link rel="stylesheet" type="text/css" href="' + e[t] + '" />')), this.css[e[t]] = !0)
        },
        addClass: function(e) {
            this.$el.addClass(e)
        },
        removeClass: function(e) {
            this.$el.removeClass(e)
        },
        __load: function(e) {
            this.__onLoad(e)
        },
        __show: function() {
            if (!this.viewport) return;
            this.viewport.find("#id_" + this.id).length || this.viewport.append(this.$el),
            this.$el.show(),
            this.__onShow()
        },
        __hide: function(e) {
            this.$el.hide(),
            this.__onHide(e)
        }
    })
}),
define("cView", ["cBase", "cUIView", "CommonStore", "cSales", "cUtility", "cStorage"],
function(e, t, n, r, i, s) {
    return t.extend({})
}),
define("cDataSource", ["libs", "cBase"],
function(e, t) {
    var n = new t.Class({
        __propertys__: function() {
            this.data,
            this.filters = [],
            this.group = {},
            this.isUpdate = !0
        },
        initialize: function(e) {
            this.setOption(e)
        },
        setOption: function(e) {
            e = e || {};
            for (var t in e) switch (t) {
            case "data":
                this.setData(data)
            }
        },
        setData: function(e) {
            this.data = e,
            this.isUpdate = !0
        },
        filter: function(e, t) {
            if (typeof e != "function") throw "Screening function did not fill in";
            var n = function(t, n) {
                return e(n, t)
            };
            return this.filters = _.filter(this.data, n),
            this.filters = this.filters || [],
            typeof t == "function" ? this.filters.sort(t) : this.filters
        },
        groupBy: function(e, t) {
            return this.group = _.filter(this.data, t),
            this.group = _.groupBy(this.group, e),
            this.group
        }
    });
    return n
}),
define("cUICitylist", ["libs", "cBase", "cUIBase"],
function(e, t, n) {
    var r = new t.Class({
        __propertys__: function() {
            this.element = null,
            this.groupOpenClass = "cityListClick",
            this.selectedCityClass = "citylistcrt",
            this.autoLocCity = null,
            this.selectedCity = null,
            this.defaultData = "inland",
            this.itemClickFun = null,
            this.data = null,
            this.autoLoc = !!navigator.geolocation,
            this.listType = this.defaultData
        },
        initialize: function(e) {
            this.setOption(e),
            this.assert(),
            this._init()
        },
        _init: function() {
            this.renderCityGroup(),
            this.data && (this.renderData = this.data[this.defaultData] || [], this.bindClickEvent())
        },
        setOption: function(e) {
            for (var t in e) switch (t) {
            case "groupOpenClass":
            case "selectedCityClass":
            case "selectedCity":
            case "itemClickFun":
            case "defaultData":
            case "autoLoc":
            case "autoLocCity":
            case "data":
                this[t] = e[t];
                break;
            case "element":
                this[t] = $(e[t])
            }
        },
        assert: function() {
            if (!this.element && this.element.length == 0) throw "not override element property"
        },
        renderCityGroup: function() {
            var e = [];
            this.autoLocCity && this.autoLocCity.listType == this.listType && this.autoLocCity.name && (e.push('<li id="' + n.config.prefix + 'curCity" data-ruler="item"'), !this.selectedCity || this.autoLocCity.name == this.selectedCity.name ? e.push(' class="' + this.selectedCityClass + '" ') : e.push(' class="noCrt"'), e.push(' data-value="' + this.autoLocCity.name + '"'), e.push(">当前城市</li>")),
            e.push('<li id="hotCity" data-ruler="group" data-group="hotCity" class="' + this.groupOpenClass + '" >热门城市</li>');
            var t = "ABCDEFGHJKLMNOPQRSTWXYZ".split("");
            for (var r in t) e.push('<li data-ruler="group" data-group="' + t[r] + '" id="' + t[r] + '">' + t[r] + "</li>");
            this.element.html(e.join(""))
        },
        groupClickHandler: function(e, t) {
            var e = $(e),
            r = e.attr("data-group") || e.attr("id");
            if (e.children().length == 0) {
                var i = [];
                try {
                    i = this.renderData[r]
                } catch(s) {
                    0;
                    return
                }
                var o = [];
                o.push("<ul>");
                for (var u = 0,
                a = i.length; u < a; u++) {
                    var f = i[u];
                    o.push('<li class data-ruler="item" data-id="' + f.id + '"'),
                    o.push(">" + f.name + "</li>")
                }
                o.push("</ul>"),
                e.append(o.join(""))
            }
            var l = e.attr("class");
            t ? e.addClass(this.groupOpenClass) : l && $.inArray(this.groupOpenClass, l) ? e.removeClass(this.groupOpenClass) : (this.element.find("." + this.groupOpenClass).removeClass(this.groupOpenClass), e.addClass(this.groupOpenClass));
            var c = n.getElementPos(e[0]);
            c && e.attr("id") != "hotCity" && $(window).scrollTop(c.top - 60),
            this.setSelectedCity(this.selectedCity)
        },
        bindClickEvent: function() {
            var e = this;
            this.element.delegate("li", "click",
            function(t) {
                var n = $(this).attr("data-ruler");
                if (n == "group") e.groupClickHandler(this);
                else if (n == "item" && e.itemClickFun && typeof e.itemClickFun == "function") {
                    var r = {
                        id: $(this).attr("data-id"),
                        name: $(this).attr("data-value") || $(this).html(),
                        listType: e.listType
                    };
                    e.itemClickFun(r)
                }
            })
        },
        switchData: function(e) {
            var t = this.data[e];
            t && (this.listType = e, this.element.undelegate("li", "click"), this.element.html(""), this.renderCityGroup(), this.renderData = t, this.groupClickHandler(this.element.find("#hotCity"), !0), this.setSelectedCity(this.selectedCity), this.bindClickEvent())
        },
        setSelectedCity: function(e) {
            var t = this;
            if (e && this.listType == e.listType && e.name) {
                var r = this.element.find("#" + n.config.prefix + "curCity");
                if (r.length > 0) r.removeClass(this.selectedCityClass),
                r.addClass("noCrt");
                else if (t.autoLocCity && t.autoLocCity.listType == this.listType && t.autoLocCity.name) {
                    var i = [];
                    i.push('<li id="' + n.config.prefix + 'curCity"'),
                    i.push('data-value="' + e.name + '" data-ruler="item">当前城市</li>'),
                    this.element.prepend(i.join())
                }
                this.element.find("li").each(function(n) {
                    var r = $(this);
                    r.html() == e.name || r.attr("data-value") == e.name ? (r.removeClass("noCrt"), r.addClass(t.selectedCityClass)) : r.removeClass(t.selectedCityClass)
                }),
                this.selectedCity = e
            }
        },
        setData: function(e) {
            this.element.html(""),
            this.data = e,
            this._init()
        },
        openHotCity: function(e) {
            var t = this.element.find("#hotCity");
            t.length > 0 && this.groupClickHandler(t, !!e)
        }
    });
    return r
}),
define("cUIInputClear", ["libs"],
function(e) {
    var t = function() {
        var e = "placeholder" in document.createElement("input"),
        t = function(t, n, r, i, s) {
            n || (n = ""),
            i = i || {};
            var o = typeof t == "string" ? $(t) : t;
            o.each(function() {
                var t = $('<a class="clear-input ' + n + '" href="javascript:;"><span></span></a>'),
                o = $(this);
                s && (t = $('<span class="cui-focus-close ' + n + '">×</span>')),
                i.left && t.css({
                    left: i.left + "px",
                    right: "auto"
                }),
                i.top && t.css({
                    top: i.top + "px",
                    bottom: "auto"
                }),
                i.right && t.css({
                    right: i.right + "px",
                    left: "auto"
                }),
                i.bottom && t.css({
                    bottom: i.bottom + "px",
                    top: "auto"
                }),
                o.parent().addClass("clear-input-box");
                if (!e) var u = o.attr("placeholder"),
                a = $('<span class="placeholder-title' + (n ? " placeholder-" + n: "") + '">' + u + "</span>");
                t.hide(),
                o.bind({
                    focus: function() {
                        var e = $.trim(o.val());
                        e != "" && t.show()
                    },
                    input: function() {
                        window.setTimeout(function() {
                            var n = o.val();
                            n == "" ? t.hide() : t.show(),
                            e || (n == "" ? a.show() : a.hide())
                        },
                        10)
                    },
                    blur: function() {
                        var n = $.trim(o.val());
                        e || (n == "" ? a.show() : a.hide()),
                        setTimeout(function() {
                            t.hide()
                        },
                        200)
                    }
                }),
                t.bind("click",
                function() {
                    o.val(""),
                    o.keyup(),
                    t.hide(),
                    o.focus(),
                    o.trigger("input"),
                    typeof r == "function" && r.call(this)
                }),
                o.after(t),
                e || (o.after(a), a.bind("click",
                function() {
                    o.focus()
                })),
                o.blur()
            })
        };
        return t
    } ();
    return t
}),
define("cUILoadingLayer", ["libs", "cBase", "cUILayer"],
function(e, t, n) {
    var r = {
        prefix: "cui-"
    },
    i = {};
    i["class"] = r.prefix + "loading",
    i.onShow = function() {
        this.contentDom.html(['<div class="cui-grayload-text">', '<div class="cui-i cui-w-loading"></div>', '<div class="cui-i cui-m-logo"></div>', '<div class="cui-grayload-close"></div>', '<div class="cui-grayload-bfont">' + this.text + "</div>", "</div>"].join("")),
        this.root.find(".cui-grayload-close").off("click").on("click", $.proxy(function() {
            this.callback && this.callback(),
            this.hide()
        },
        this)),
        this.reposition()
    };
    var s = {};
    return s.__propertys__ = function() {
        this.contentDom,
        this.callback = function() {},
        this.text = "发送中..."
    },
    s.initialize = function($super, e, t) {
        this.callback = e ||
        function() {},
        this.text = t || "发送中...",
        $super(i)
    },
    new t.Class(n, s)
}),
define("cUIScrollList", ["cBase"],
function(e) {
    window.initTap = function() {
        var e = $("#forTap");
        return e[0] || (e = $('<div id="forTap" style="color: White; display: none; border-radius: 60px; position: absolute; z-index: 99999; width: 60px; height: 60px"></div>'), $("body").append(e)),
        e
    },
    window.showMaskTap = function(e, t) {
        var n = initTap();
        n[0] && n.css({
            top: t + "px",
            left: e + "px"
        }),
        n.show(),
        setTimeout(function() {
            n.hide()
        },
        350)
    };
    var t = function(e) {
        e = e || {},
        this._checkEventCompatibility(),
        this._setBaseParam(e),
        this._initBaseDom(e),
        this._setDisItemNum(e),
        this._setSelectedIndex(e),
        this.init()
    };
    return t.prototype = {
        constructor: t,
        _checkEventCompatibility: function() {
            var e = "ontouchstart" in document.documentElement;
            this.start = e ? "touchstart": "mousedown",
            this.move = e ? "touchmove": "mousemove",
            this.end = e ? "touchend": "mouseup",
            this.startFn,
            this.moveFn,
            this.endFn
        },
        _setBaseParam: function(e) {
            this.setHeight = 0,
            this.itemHeight = 0,
            this.dragHeight = 0,
            this.dragTop = 0,
            this.timeGap = 0,
            this.touchTime = 0,
            this.moveAble = !1,
            this.moveState = "up",
            this.oTop = 0,
            this.curTop = 0,
            this.mouseY = 0,
            this.cooling = !1,
            this.animateParam = e.animateParam || [50, 40, 30, 25, 20, 15, 10, 8, 6, 4, 2],
            this.animateParam = e.animateParam || [10, 8, 6, 5, 4, 3, 2, 1, 0, 0, 0],
            this.data = e.data || [],
            this.dataK = {},
            this.size = this.getValidSize(this.data),
            this._changed = e.changed || null
        },
        getValidSize: function(e) {
            return _.filter(e,
            function(e) {
                return typeof e.key == "undefined" && (e.key = e.id),
                typeof e.val == "undefined" && (e.val = e.name),
                e.val || e.key
            }).length
        },
        _initBaseDom: function(e) {
            this.wrapper = e.wrapper || $(document),
            this.type = e.type || "list",
            this.id = e.id || "id_" + (new Date).getTime(),
            this.className = e.className || "cui-roller-bd",
            this._setScrollClass(e),
            this._initDom(),
            this.wrapper.append(this.body)
        },
        _setScrollClass: function(e) {
            var t;
            this.type == "list" ? t = "cui-select-view": this.type == "radio" && (t = "ul-list"),
            t = e.scrollClass || t,
            this.scrollClass = t
        },
        _initDom: function() {
            this.body = $(['<div class="' + this.className + '" style="overflow: hidden; position: relative; " id="' + this.id + '" >', "</div>"].join("")),
            this.dragEl = $(['<ul class="' + this.scrollClass + '" style="position: absolute; width: 100%;">', "</ul>"].join(""))
        },
        _setDisItemNum: function(e) {
            this.disItemNum = this.data.length,
            this.disItemNum = this.disItemNum > 5 ? 5 : this.disItemNum,
            this.type == "radio" && (this.disItemNum = 5),
            this.disItemNum = e.disItemNum || this.disItemNum,
            this.type == "radio" && (this.disItemNum = this.disItemNum % 2 == 0 ? this.disItemNum + 1 : this.disItemNum);
            if (this.data.length < this.disItemNum) if (this.type == "radio") {
                for (var t = 0,
                n = this.disItemNum - this.data.length; t < n; t++) this.data.push({
                    key: "",
                    val: "",
                    disabled: !1
                });
                this.size = this.disItemNum
            } else this.disItemNum = this.data.length
        },
        _setSelectedIndex: function(e) {
            this.selectedIndex = parseInt(this.disItemNum / 2),
            this.type == "list" && (this.selectedIndex = -1),
            this.selectedIndex = e.index != undefined ? e.index: this.selectedIndex,
            this.selectedIndex = this.selectedIndex > this.data.length ? 0 : this.selectedIndex,
            this._checkSelected()
        },
        _checkSelected: function(e) {
            e = e || "down";
            var t = !1,
            n = this.selectedIndex;
            this.data[n] && (typeof this.data[n].disabled == "undefined" || this.data[n].disabled == 0) && (e == "down" ? (this.selectedIndex = this._checkSelectedDown(n), typeof this.selectedIndex != "number" && (this.selectedIndex = this._checkSelectedUp(n))) : (this.selectedIndex = this._checkSelectedUp(n), typeof this.selectedIndex != "number" && (this.selectedIndex = this._checkSelectedDown(n)))),
            typeof this.selectedIndex != "number" && (this.selectedIndex = n);
            var r = ""
        },
        _checkSelectedUp: function(e) {
            var t = !1;
            for (var n = e; n != -1; n--) if (this.data[n] && typeof this.data[n].disabled == "undefined" || this.data[n].disabled == 1) {
                e = n,
                t = !0;
                break
            }
            return t ? e: null
        },
        _checkSelectedDown: function(e) {
            var t = !1;
            for (var n = e,
            r = this.data.length; n < r; n++) if (this.data[n] && typeof this.data[n].disabled == "undefined" || this.data[n].disabled == 1) {
                e = n,
                t = !0;
                break
            }
            return t ? e: null
        },
        init: function() {
            this._addItem(),
            this._initEventParam(),
            this._addEvent(),
            this._initScrollBar(),
            this.setIndex(this.selectedIndex, !0)
        },
        _addItem: function() {
            var e, t, n, r, i;
            for (var n in this.data) t = this.data[n],
            t.index = n,
            typeof t.key == "undefined" && (t.key = t.id),
            typeof t.val == "undefined" && (t.val = t.name),
            i = t.val || t.key,
            this.dataK[t.key] = t,
            e = $("<li>" + i + "</li>"),
            e.attr("data-index", n),
            typeof t.disabled != "undefined" && t.disabled == 0 && e.css("color", "gray"),
            this.dragEl.append(e);
            this.body.append(this.dragEl)
        },
        _initEventParam: function() {
            if (this.data.constructor != Array || this.data.length == 0) return ! 1;
            var e = this.dragEl.offset(),
            t = this.dragEl.find("li").eq(0),
            n = t.offset();
            this.itemHeight = n.height,
            this.setHeight = this.itemHeight * this.disItemNum,
            this.body.css("height", this.setHeight),
            this.dragTop = e.top,
            this.dragHeight = this.itemHeight * this.size;
            var r = ""
        },
        _addEvent: function() {
            var e = this;
            this.startFn = function(t) {
                e._touchStart.call(e, t)
            },
            this.moveFn = function(t) {
                e._touchMove.call(e, t)
            },
            this.endFn = function(t) {
                e._touchEnd.call(e, t)
            },
            this.dragEl[0].addEventListener(this.start, this.startFn, !1),
            this.dragEl[0].addEventListener(this.move, this.moveFn, !1),
            this.dragEl[0].addEventListener(this.end, this.endFn, !1)
        },
        removeEvent: function() {
            this.dragEl[0].removeEventListener(this.start, this.startFn),
            this.dragEl[0].removeEventListener(this.move, this.moveFn),
            this.dragEl[0].removeEventListener(this.end, this.endFn)
        },
        _initScrollBar: function() {
            if (this.type != "list") return;
            this.scrollProportion = this.setHeight / this.dragHeight,
            this.isNeedScrollBar = !0;
            if (this.scrollProportion >= 1) return this.isNeedScrollBar = !1,
            !1;
            this.scrollBar = $('<div style="background-color: rgba(0, 0, 0, 0.498039);border: 1px solid rgba(255, 255, 255, 0.901961); width: 5px; border-radius: 3px;  position: absolute; right: 1px;  opacity: 0.2;  "></div>'),
            this.body.append(this.scrollBar),
            this.scrollHeight = parseInt(this.scrollProportion * this.setHeight),
            this.scrollBar.css("height", this.scrollHeight)
        },
        _setScrollTop: function(e, t) {
            if (this.isNeedScrollBar) {
                e = this._getResetData(e).top,
                e = e < 0 ? e + 10 : e;
                var n = e * -1;
                if (typeof t == "number") {
                    var r = parseInt(n * this.scrollProportion) + "px";
                    this.scrollBar.animate({
                        top: r,
                        right: "1px"
                    },
                    t, "linear")
                } else this.scrollBar.css("top", parseInt(n * this.scrollProportion) + "px");
                this.scrollBar.css("opacity", "0.8")
            }
        },
        _hideScroll: function() {
            this.isNeedScrollBar && this.scrollBar.animate({
                opacity: "0.2"
            })
        },
        _touchStart: function(e) {
            e.preventDefault();
            var t = this;
            if (this.cooling) return setTimeout(function() {
                t.cooling = !1
            },
            50),
            e.preventDefault(),
            !1;
            var n = $(e.target).parent(),
            r;
            this.isMoved = !1;
            if (n.hasClass(this.scrollClass)) {
                this.touchTime = e.timeStamp,
                r = this.getMousePos(e.changedTouches && e.changedTouches[0] || e);
                var i = parseFloat(this.dragEl.css("top")) || 0;
                this.mouseY = r.top - i,
                this.moveAble = !0
            }
        },
        _touchMove: function(e) {
            e.preventDefault();
            if (!this.moveAble) return ! 1;
            var t = this.getMousePos(e.changedTouches && e.changedTouches[0] || e);
            this.curTop = t.top - this.mouseY;
            var n = this._cheakListBound(this.curTop);
            n != 0 && (this.curTop = n.top),
            this.isMoved = !0,
            this.dragEl.css("top", this.curTop + "px"),
            this._setScrollTop(this.curTop),
            e.preventDefault()
        },
        _cheakListBound: function(e) {
            var t = parseInt(this.dragHeight) - parseInt(this.setHeight),
            n = !1;
            if (this.type == "radio") {
                var r = parseInt(this.disItemNum / 2);
                e > this.itemHeight * r ? (e = this.itemHeight * r, n = !0) : e < t * -1 - this.itemHeight * r && (e = t * -1 - this.itemHeight * r, n = !0)
            } else e > this.itemHeight ? (e = this.itemHeight, n = !0) : e < t * -1 - this.itemHeight && (e = t * -1 - this.itemHeight, n = !0);
            return n ? (this.isBound = !0, {
                speed: 1,
                top: e
            }) : (this.isBound = !1, !1)
        },
        _getAnimateData: function(e) {
            this.timeGap = e.timeStamp - this.touchTime;
            var t = this.oTop <= this.curTop ? 1 : -1,
            n = this.curTop > 0 ? 1 : -1;
            this.moveState = t > 0 ? "up": "down";
            var r = parseFloat(this.itemHeight),
            i = r / 2,
            s = Math.abs(this.curTop),
            o = s % r;
            s = (parseInt(s / r) * r + (o > i ? r: 0)) * n;
            var u = parseInt(this.timeGap / 10 - 10);
            u = u > 0 ? u: 0;
            var a = this.animateParam[u] || 0,
            f = a * r * t;
            return s += f,
            {
                top: s,
                speed: a
            }
        },
        _touchEnd: function(e) {
            var t = this;
            if (this.isBound === !0 && this.isMoved === !0) return t.reset.call(t, this.curTop),
            this.moveAble = !1,
            !1;
            if (!this.moveAble) return ! 1;
            this.cooling = !0;
            var n = this._getAnimateData(e),
            r = n.top,
            i = n.speed,
            s = this._cheakListBound(r);
            s != 0 && (r = s.top, i = s.speed);
            if (this.oTop != this.curTop && this.curTop != r) this.dragEl.animate({
                top: r + "px"
            },
            100 + i * 20, "linear",
            function() {
                t.reset.call(t, r)
            }),
            t._setScrollTop(r, 100 + i * 20);
            else {
                return;
                var o
            }
            this._hideScroll(),
            this.moveAble = !1
        },
        _getResetData: function(e) {
            var t = parseInt(this.type == "list" ? 0 : this.disItemNum / 2),
            n = e,
            r = !1,
            i = this.type == "list" ? 0 : parseFloat(this.itemHeight) * t,
            s = this.type == "list" ? this.setHeight: parseFloat(this.itemHeight) * (t + 1),
            o = this.dragHeight;
            return e >= 0 && (e > i ? (n = i, r = !0) : o <= i && (n = parseInt(this.dragEl.css("top")) / this.itemHeight * this.itemHeight, r = !0)),
            e < 0 && e + this.dragHeight <= s && (r = !0, n = (this.dragHeight - s) * -1),
            e == n && (r = !1),
            {
                top: n,
                needReset: r
            }
        },
        reset: function(e) {
            var t = this,
            n = this._getResetData(e).needReset,
            r = this._getResetData(e).top;
            n ? t.dragEl.animate({
                top: r + "px"
            },
            50, "linear",
            function() {
                t._reset(r)
            }) : t._reset(e),
            this._hideScroll()
        },
        _reset: function(e) {
            this.oTop = e,
            this.curTop = e,
            this.type == "radio" && this.onTouchEnd(),
            this.cooling = !1
        },
        onTouchEnd: function(e) {
            e = e || this;
            var t, n, r, i, s, o = this._changed,
            u = parseInt(this.type == "list" ? 0 : this.disItemNum / 2);
            r = this.data.length,
            this.type == "radio" ? (n = parseInt((this.curTop - this.itemHeight * u) / parseFloat(this.itemHeight)), this.selectedIndex = Math.abs(n), t = this.data[this.selectedIndex]) : t = this.data[this.selectedIndex],
            s = !1,
            t && typeof t.disabled != "undefined" && t.disabled == 0 && (i = this.selectedIndex, this.type == "radio" && this._checkSelected(this.moveState), i != this.selectedIndex && (s = !0));
            if (s) this.setIndex(this.selectedIndex);
            else {
                var o = this._changed;
                o && typeof o == "function" && t && t.disabled != 0 && o.call(e, t),
                this.dragEl.find("li").removeClass("current"),
                this.type == "radio" && this.dragEl.find("li").eq(this.selectedIndex).addClass("current")
            }
        },
        reload: function(e) {
            this.data = e,
            this.dragEl.html(""),
            e.constructor == Array && e.length > 0 && (this.selectedIndex = parseInt(this.disItemNum / 2), this.selectedIndex = this.selectedIndex > this.data.length ? this.data.length - 1 : this.selectedIndex, this._checkSelected("down"), this._addItem(), this._initEventParam(), this.cooling = !1, this.setIndex(this.selectedIndex, !0), this.dragEl.find("li").removeClass("current"), this.type == "radio" && this.dragEl.find("li").eq(this.selectedIndex).addClass("current")),
            this.size = this.getValidSize(e),
            this.dragHeight = this.itemHeight * this.size
        },
        setKey: function(e) {
            if (e == undefined || e == null) return ! 1;
            var t = this.dataK[e] && this.dataK[e].index;
            this.setIndex(t)
        },
        setIndex: function(e, t) {
            if (e == undefined || e < 0) return ! 1;
            var n = this,
            r = parseInt(n.disItemNum / 2);
            n.type == "list" && (this.data.length == n.disItemNum ? r = e: (r = e == 0 ? 0 : 1, this.size - e < this.disItemNum && (r = -1 * parseInt(this.size) + parseInt(this.disItemNum) + parseInt(e))));
            var e = parseInt(e),
            i;
            if (e < 0) return ! 1;
            e >= this.data.length && (e = this.data.length - 1),
            this.selectedIndex = e,
            i = this.itemHeight * (r - e),
            n.oTop = i,
            n.curTop = i,
            n.cooling = !1,
            n.dragEl.animate({
                top: i + "px"
            },
            50, "linear"),
            this._setScrollTop(i, 50);
            if (n.type == "list") {
                var s = n.dragEl.find("li");
                s.removeClass("current"),
                s.eq(e).addClass("current")
            }
            t || n.onTouchEnd()
        },
        getSelected: function() {
            return this.data[this.selectedIndex]
        },
        getByKey: function(e) {
            var t = this.dataK[e] && this.dataK[e].index;
            return t != null && t != undefined ? this.data[t] : null
        },
        getMousePos: function(e) {
            var t, n;
            return t = Math.max(document.body.scrollTop, document.documentElement.scrollTop),
            n = Math.max(document.body.scrollLeft, document.documentElement.scrollLeft),
            {
                top: t + e.clientY,
                left: n + e.clientX
            }
        }
    },
    t
}),
define("cUIScrollRadio", ["libs", "cBase", "cUILayer", "cUIScrollList"],
function(e, t, n, r) {
    var i = {},
    s = {
        prefix: "cui-"
    },
    o = {};
    o["class"] = s.prefix + "warning",
    o.onCreate = function() {
        this.root.html(['<div class="cui-pop-box" >', '<div class="cui-hd">', '<div class="cui-text-center">', "" + this.title + "</div>", "</div>", '<div class="cui-bd ">', '<div class="cui-roller scrollWrapper">', "</div>", '<p class="cui-roller-tips">', "" + this.tips + "</p>", '<div class="cui-roller-btns">', '<div class="cui-btns-cancel cui-flexbd">' + this.cancel + '</div> <div class="cui-btns-sure cui-flexbd" >', "" + this.ok + "</div>", "</div>", "</div>", "</div>"].join("")),
        this.title = this.root.find(".cui-text-center"),
        this.tips = this.root.find(".cui-roller-tips"),
        this.btCancel = this.root.find(".cui-btns-cancel"),
        this.btOk = this.root.find(".cui-btns-sure"),
        this.line = $('<div class="cui-mask-gray"></div><div class="cui-lines">&nbsp;</div>'),
        this.wrapper = this.root.find(".scrollWrapper")
    },
    o.onShow = function() {
        var e = this;
        this.maskToHide(function() {
            e.hide()
        });
        if (!this.data || this.data.length == 0) return ! 1;
        for (var t = 0,
        n = this.data.length; t < n; t++) {
            var i = {
                wrapper: this.wrapper,
                data: this.data[t],
                type: "radio",
                disItemNum: this.disItemNum,
                changed: function(t) {
                    return function(n) {
                        var r = e.changed[t];
                        typeof r == "function" && r.call(e, n)
                    }
                } (t)
            };
            t == 0 && n == 3 && (i.className = "cui-roller-bd  cui-flex2");
            var s = new r(i);
            this.scroll.push(s)
        }
        for (var t = 0,
        n = this.data.length; t < n; t++) this.scroll[t].setIndex(this.index[t]),
        this.scroll[t].setKey(this.key[t]);
        this.wrapper.append(this.line),
        this.btOk.on("click",
        function() {
            var t = [];
            for (var n = 0,
            r = e.scroll.length; n < r; n++) t.push(e.scroll[n].getSelected());
            e.okClick.call(e, t),
            e.hide()
        }),
        this.btCancel.on("click",
        function() {
            var t = [];
            for (var n = 0,
            r = e.scroll.length; n < r; n++) t.push(e.scroll[n].getSelected());
            e.cancelClick.call(e, t),
            e.hide()
        }),
        this.setzIndexTop(),
        this.root.bind("touchmove",
        function(e) {
            e.preventDefault()
        }),
        this.onHashChange = function() {
            this.hide()
        },
        $(window).on("hashchange", $.proxy(this.onHashChange, this))
    },
    o.onHide = function() {
        for (var e = 0,
        t = this.scroll.length; e < t; e++) this.scroll[e].removeEvent();
        this.btOk.off("click"),
        this.btCancel.off("click"),
        this.root.unbind("touchmove"),
        this.root.remove(),
        $(window).off("hashchange", $.proxy(this.onHashChange, this))
    },
    i.__propertys__ = function() {
        var e = this;
        this.changed = [],
        this.scroll = [],
        this.data = [],
        this.index = [],
        this.key = [],
        this.disItemNum = 5,
        this.tips = "",
        this.btCancel,
        this.btOk,
        this.cancel = "取消",
        this.ok = "确定",
        this.cancelClick = function() {
            e.hide()
        },
        this.okClick = function() {
            e.hide()
        }
    },
    i.initialize = function($super, e) {
        this.setOption(function(e, t) {
            this[e] = t
        }),
        $super($.extend(o, e))
    },
    i.setTips = function(e) {
        this.tips.html(e)
    };
    var u = new t.Class(n, i);
    return u
}),
define("cUIScroll", ["cBase"],
function(e) {
    function n(e) {
        this.$wrapper = typeof e.wrapper == "string" ? $(e.wrapper) : e.wrapper,
        e.scroller ? this.scroller = typeof e.scroller == "string" ? $(e.scroller) : e.scroller: this.scroller = this.$wrapper.children().eq(0),
        this.wrapper = this.$wrapper[0],
        this.scroller = this.scroller[0];
        var n = this.wrapper.iscrollInstance;
        if (n) return n;
        this.scrollerStyle = this.scroller.style,
        this.options = {
            scrollbars: !0,
            startX: 0,
            startY: 0,
            bounceTime: 600,
            bounceEasing: t.ease.circular,
            preventDefaultException: /^(input|textarea|button|select)$/i,
            bounce: !0,
            scrollY: !0,
            scrollX: !1,
            bindToWrapper: !0,
            resizePolling: 60
        },
        this._events = {};
        for (var r in e) this.options[r] = e[r],
        (r == "scrollStart" || r == "scrollEnd") && this.on(r, _.bind(e[r], this));
        this.translateZ = " translateZ(0)",
        this.x = 0,
        this.y = 0,
        this._init(),
        this.refresh(),
        this.scrollTo(this.options.startX, this.options.startY),
        this.enable(),
        this.wrapper.iscrollInstance = this
    }
    function r(e) {
        var t = $("<div>"),
        n = $("<div>"),
        r = {
            position: "absolute",
            zIndex: 9999,
            overflow: "hidden"
        },
        i = {
            boxSizing: "border-box",
            position: "absolute",
            background: "rgba(0, 0, 0, .5)",
            border: "1px solid rgba(255, 255, 255, .9)"
        };
        return e === "h" ? (_.extend(r, {
            height: 7,
            left: 2,
            right: 2,
            bottom: 0
        }), _.extend(i, {
            height: "100%"
        })) : (_.extend(r, {
            width: 7,
            bottom: 2,
            top: 2,
            right: 1
        }), _.extend(i, {
            width: "100%"
        })),
        t.css(r),
        n.css(i),
        t.append(n),
        t[0]
    }
    function i(e, n) {
        this.wrapper = typeof n.el == "string" ? document.querySelector(n.el) : n.el,
        this.indicator = this.wrapper.children[0],
        this.wrapperStyle = this.wrapper.style,
        this.indicatorStyle = this.indicator.style,
        this.scroller = e,
        this.sizeRatioX = 1,
        this.sizeRatioY = 1,
        this.maxPosX = 0,
        this.maxPosY = 0,
        this.options = {
            scrollX: !1
        },
        _.extend(this.options, n),
        this.wrapperStyle[t.style.transform] = this.scroller.translateZ,
        this.wrapperStyle[t.style.transitionDuration] = "0ms"
    }
    var t = function() {
        function r(e) {
            return n === !1 ? !1 : n === "" ? e: n + e.charAt(0).toUpperCase() + e.substr(1)
        }
        var e = {},
        t = document.createElement("div").style,
        n = function() {
            var e = ["t", "webkitT", "MozT", "msT", "OT"],
            n,
            r = 0,
            i = e.length;
            for (; r < i; r++) {
                n = e[r] + "ransform";
                if (n in t) return e[r].substr(0, e[r].length - 1)
            }
            return ! 1
        } ();
        return e.getTime = Date.now ||
        function() {
            return (new Date).getTime()
        },
        e.addEvent = function(e, t, n, r) {
            e[0] && (e = e[0]),
            e.addEventListener(t, n, !!r)
        },
        e.removeEvent = function(e, t, n, r) {
            e[0] && (e = e[0]),
            e.removeEventListener(t, n, !!r)
        },
        e.momentum = function(e, t, n, r, i) {
            var s = e - t,
            o = Math.abs(s) / n,
            u,
            a,
            f = 6e-4;
            return u = e + o * o / (2 * f) * (s < 0 ? -1 : 1),
            a = o / f,
            u < r ? (u = i ? r - i / 2.5 * (o / 8) : r, s = Math.abs(u - e), a = s / o) : u > 0 && (u = i ? i / 2.5 * (o / 8) : 0, s = Math.abs(e) + u, a = s / o),
            {
                destination: Math.round(u),
                duration: a
            }
        },
        $.extend(e, {
            hasTouch: "ontouchstart" in window
        }),
        e.isBadAndroid = /Android /.test(window.navigator.appVersion) && !/Chrome\/\d/.test(window.navigator.appVersion),
        $.extend(e.style = {},
        {
            transform: r("transform"),
            transitionTimingFunction: r("transitionTimingFunction"),
            transitionDuration: r("transitionDuration"),
            transitionDelay: r("transitionDelay"),
            transformOrigin: r("transformOrigin")
        }),
        $.extend(e.eventType = {},
        {
            touchstart: 1,
            touchmove: 1,
            touchend: 1,
            mousedown: 2,
            mousemove: 2,
            mouseup: 2
        }),
        $.extend(e.ease = {},
        {
            quadratic: {
                style: "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
                fn: function(e) {
                    return e * (2 - e)
                }
            },
            circular: {
                style: "cubic-bezier(0.1, 0.57, 0.1, 1)",
                fn: function(e) {
                    return Math.sqrt(1 - --e * e)
                }
            },
            back: {
                style: "cubic-bezier(0.175, 0.885, 0.32, 1.275)",
                fn: function(e) {
                    var t = 4;
                    return (e -= 1) * e * ((t + 1) * e + t) + 1
                }
            },
            bounce: {
                style: "",
                fn: function(e) {
                    return (e /= 1) < 1 / 2.75 ? 7.5625 * e * e: e < 2 / 2.75 ? 7.5625 * (e -= 1.5 / 2.75) * e + .75 : e < 2.5 / 2.75 ? 7.5625 * (e -= 2.25 / 2.75) * e + .9375 : 7.5625 * (e -= 2.625 / 2.75) * e + .984375
                }
            },
            elastic: {
                style: "",
                fn: function(e) {
                    var t = .22,
                    n = .4;
                    return e === 0 ? 0 : e == 1 ? 1 : n * Math.pow(2, -10 * e) * Math.sin((e - t / 4) * 2 * Math.PI / t) + 1
                }
            }
        }),
        e
    } ();
    return n.prototype = {
        _init: function() {
            this._initEvents(),
            this.options.scrollbars && this._initIndicator()
        },
        refresh: function() {
            var e = this.wrapper.offsetHeight,
            t = this.options;
            this.wrapperWidth = this.wrapper.clientWidth,
            this.scrollerWidth = this.scroller.offsetWidth,
            this.maxScrollX = this.wrapperWidth - this.scrollerWidth,
            this.wrapperHeight = this.wrapper.clientHeight,
            this.scrollerHeight = this.scroller.offsetHeight,
            this.maxScrollY = this.wrapperHeight - this.scrollerHeight,
            this.indicator && (t.scrollY && this.maxScrollY >= 0 && (this.indicator.wrapperStyle.display = "none"), t.scrollX && this.maxScrollX >= 0 && (this.indicator.wrapperStyle.display = "none"));
            if (t.scrollX || this.maxScrollY > 0) this.maxScrollY = 0;
            if (t.scrllY || this.maxScrollX > 0) this.maxScrollX = 0;
            this.endTime = 0,
            this._execEvent("refresh"),
            this.resetPosition()
        },
        _initEvents: function(e) {
            var n = e ? t.removeEvent: t.addEvent,
            r = this.options.bindToWrapper ? this.wrapper: window;
            n(window, "orientationchange", this),
            n(window, "resize", this),
            t.hasTouch ? (n(this.wrapper, "touchstart", this), n(r, "touchmove", this), n(r, "touchcancel", this), n(r, "touchend", this)) : (n(this.wrapper, "mousedown", this), n(r, "mousemove", this), n(r, "mousecancel", this), n(r, "mouseup", this)),
            n(this.scroller, "transitionend", this),
            n(this.scroller, "webkitTransitionEnd", this),
            n(this.scroller, "oTransitionEnd", this),
            n(this.scroller, "MSTransitionEnd", this)
        },
        _start: function(e) {
            var n = this.options;
            n.scrollX && (this._isMovedChecked = !1, this.enabled = !0);
            if (!this.enabled || this.initiated && t.eventType[e.type] !== this.initiated) return; ! t.isBadAndroid && n.preventDefaultException.test(e.target.tagName) && e.preventDefault();
            var r = e.touches ? e.touches[0] : e,
            i;
            this.initiated = t.eventType[e.type],
            this.moved = !1,
            this.distY = 0,
            this.distX = 0,
            this._transitionTime(),
            this.startTime = t.getTime();
            if (this.isInTransition) {
                this.isInTransition = !1,
                i = this.getComputedPosition();
                var s = Math.round(i.x),
                o = Math.round(i.y);
                o < 0 && o > this.maxScrollY && n.adjustXY && (o = n.adjustXY.call(this, s, o).y),
                this._translate(s, o),
                this._execEvent("scrollEnd")
            }
            this.startX = this.x,
            this.startY = this.y,
            this.absStartX = this.x,
            this.absStartY = this.y,
            this.pointX = r.pageX,
            this.pointY = r.pageY,
            this._execEvent("beforeScrollStart")
        },
        _moveCheck: function(e) {
            var t = this.options;
            if (t.scrollX && !this._isMovedChecked) {
                var n = e.touches ? e.touches[0] : e,
                r = Math.abs(n.pageX - this.pointX),
                i = Math.abs(n.pageY - this.pointY);
                i > r && this.disable()
            }
            this._isMovedChecked = !0
        },
        _move: function(e) {
            if (!this.enabled || t.eventType[e.type] !== this.initiated) return;
            e.preventDefault();
            var n = this.options,
            r = e.touches ? e.touches[0] : e,
            i = r.pageX - this.pointX,
            s = r.pageY - this.pointY,
            o = t.getTime(),
            u,
            a,
            f,
            l;
            this.pointX = r.pageX,
            this.pointY = r.pageY,
            this.distX += i,
            this.distY += s,
            f = Math.abs(this.distX),
            l = Math.abs(this.distY);
            if (o - this.endTime > 300 && f < 10 && l < 10) return;
            n.scrollX && (s = 0),
            n.scrollY && (i = 0),
            u = this.x + i,
            a = this.y + s;
            if (u > 0 || u < this.maxScrollX) u = this.options.bounce ? this.x + i / 3 : u > 0 ? 0 : this.maxScrollX;
            if (a > 0 || a < this.maxScrollY) a = n.bounce ? this.y + s / 3 : a > 0 ? 0 : this.maxScrollY;
            this.moved || this._execEvent("scrollStart"),
            this.moved = !0,
            this._translate(u, a),
            o - this.startTime > 300 && (this.startTime = o, this.startX = this.x, this.startY = this.y)
        },
        _end: function(e) {
            if (!this.enabled || t.eventType[e.type] !== this.initiated) return;
            var n = this.options,
            r = e.changedTouches ? e.changedTouches[0] : e,
            i,
            s = t.getTime() - this.startTime,
            o = Math.round(this.x),
            u = Math.round(this.y),
            a = Math.abs(o - this.startX),
            f = Math.abs(u - this.startY),
            l = 0,
            c = "";
            this.isInTransition = 0,
            this.initiated = 0,
            this.endTime = t.getTime();
            if (this.resetPosition(n.bounceTime)) return;
            this.scrollTo(o, u);
            if (!this.moved) {
                this._execEvent("scrollCancel");
                return
            }
            s < 300 && (momentumX = t.momentum(this.x, this.startX, s, this.maxScrollX, n.bounce ? this.wrapperWidth: 0), i = t.momentum(this.y, this.startY, s, this.maxScrollY, n.bounce ? this.wrapperHeight: 0), o = momentumX.destination, u = i.destination, l = Math.max(momentumX.duration, i.duration), this.isInTransition = 1);
            if (o != this.x || u != this.y) {
                if (o > 0 || o < this.maxScrollX || u > 0 || u < this.maxScrollY) c = t.ease.quadratic;
                this.scrollTo(o, u, l, c);
                return
            }
            this._execEvent("scrollEnd")
        },
        _resize: function() {
            var e = this;
            clearTimeout(this.resizeTimeout),
            this.resizeTimeout = setTimeout(function() {
                e.refresh()
            },
            this.options.resizePolling)
        },
        _transitionTimingFunction: function(e) {
            this.scrollerStyle[t.style.transitionTimingFunction] = e,
            this.indicator && this.indicator.transitionTimingFunction(e)
        },
        _transitionTime: function(e) {
            e = e || 0,
            this.scrollerStyle[t.style.transitionDuration] = e + "ms",
            this.indicator && this.indicator.transitionTime(e)
        },
        getComputedPosition: function() {
            var e = window.getComputedStyle(this.scroller, null),
            n,
            r;
            return e = e[t.style.transform].split(")")[0].split(", "),
            n = +(e[12] || e[4]),
            r = +(e[13] || e[5]),
            {
                x: n,
                y: r
            }
        },
        _initIndicator: function() {
            var e, t = this.options.scrollX;
            t ? e = r("h") : e = r(),
            this.wrapper.appendChild(e),
            this.indicator = new i(this, {
                el: e,
                scrollX: t
            }),
            this.$wrapper.css("position", "relative"),
            this.on("scrollEnd",
            function() {
                this.indicator.fade()
            });
            var n = this;
            this.on("scrollCancel",
            function() {
                n.indicator.fade()
            }),
            this.on("scrollStart",
            function() {
                n.indicator.fade(1)
            }),
            this.on("beforeScrollStart",
            function() {
                n.indicator.fade(1, !0)
            }),
            this.on("refresh",
            function() {
                n.indicator.refresh()
            })
        },
        _translate: function(e, n) {
            this.scrollerStyle[t.style.transform] = "translate(" + e + "px," + n + "px)" + this.translateZ,
            this.x = e,
            this.y = n,
            this.options.scrollbars && this.indicator.updatePosition()
        },
        resetPosition: function(e) {
            var t = this.x,
            n = this.y,
            r = this.options;
            return e = e || 0,
            !r.scrollX || this.x > 0 ? t = 0 : this.x < this.maxScrollX && (t = this.maxScrollX),
            !r.scrollY || this.y > 0 ? n = 0 : this.y < this.maxScrollY && (n = this.maxScrollY),
            t == this.x && n == this.y ? !1 : (this.scrollTo(t, n, e, this.options.bounceEasing), !0)
        },
        scrollTo: function(e, n, r, i) {
            i = i || t.ease.circular,
            this.isInTransition = r > 0;
            if (!r || i.style) this._transitionTimingFunction(i.style),
            this._transitionTime(r),
            this._translate(e, n)
        },
        disable: function() {
            this.enabled = !1
        },
        enable: function() {
            this.enabled = !0
        },
        on: function(e, t) {
            this._events[e] || (this._events[e] = []),
            this._events[e].push(t)
        },
        _execEvent: function(e) {
            if (!this._events[e]) return;
            var t = 0,
            n = this._events[e].length;
            if (!n) return;
            for (; t < n; t++) this._events[e][t].call(this)
        },
        destroy: function() {
            this._initEvents(!0),
            this._execEvent("destroy"),
            this.indicator && this.indicator.destroy()
        },
        _transitionEnd: function(e) {
            if (e.target != this.scroller || !this.isInTransition) return;
            this._transitionTime(),
            this.resetPosition(this.options.bounceTime) || (this.isInTransition = !1, this._execEvent("scrollEnd"))
        },
        handleEvent: function(e) {
            switch (e.type) {
            case "touchstart":
            case "mousedown":
                this._start(e);
                break;
            case "touchmove":
            case "mousemove":
                this._moveCheck(e),
                this._move(e);
                break;
            case "touchend":
            case "mouseup":
            case "touchcancel":
            case "mousecancel":
                this._end(e);
                break;
            case "orientationchange":
            case "resize":
                this._resize();
                break;
            case "transitionend":
            case "webkitTransitionEnd":
            case "oTransitionEnd":
            case "MSTransitionEnd":
                this._transitionEnd(e)
            }
        }
    },
    i.prototype = {
        transitionTime: function(e) {
            e = e || 0,
            this.indicatorStyle[t.style.transitionDuration] = e + "ms"
        },
        transitionTimingFunction: function(e) {
            this.indicatorStyle[t.style.transitionTimingFunction] = e
        },
        refresh: function() {
            this.transitionTime();
            var e = this.wrapper.offsetHeight,
            t = this.wrapper.clientHeight,
            n = this.wrapper.clientWidth,
            r, i;
            this.options.scrollX ? (i = Math.max(Math.round(n * n / (this.scroller.scrollerWidth || n || 1)), 8), this.indicatorStyle.width = i + "px", this.maxPosX = n - i, this.sizeRatioX = this.scroller.maxScrollX && this.maxPosX / this.scroller.maxScrollX) : (r = Math.max(Math.round(t * t / (this.scroller.scrollerHeight || t || 1)), 8), this.indicatorStyle.height = r + "px", this.maxPosY = t - r, this.sizeRatioY = this.scroller.maxScrollY && this.maxPosY / this.scroller.maxScrollY),
            this.updatePosition()
        },
        destroy: function() {
            $(this.wrapper).remove()
        },
        updatePosition: function() {
            var e = Math.round(this.sizeRatioY * this.scroller.y) || 0,
            n = Math.round(this.sizeRatioX * this.scroller.x) || 0,
            r;
            this.y = e,
            this.x = n,
            this.options.scrollX ? r = "translate(" + n + "px, 0)": r = "translate(0," + e + "px)",
            this.indicatorStyle[t.style.transform] = r + this.scroller.translateZ
        },
        fade: function(e, n) {
            if (n && !this.visible) return;
            clearTimeout(this.fadeTimeout),
            this.fadeTimeout = null;
            var r = e ? 250 : 500,
            i = e ? 0 : 300;
            e = e ? "1": "0",
            this.wrapperStyle[t.style.transitionDuration] = r + "ms",
            this.fadeTimeout = setTimeout($.proxy(function(e) {
                this.wrapperStyle.opacity = e,
                this.visible = +e
            },
            this), i)
        }
    },
    n.utils = t,
    n
}),
define("cUIScrollRadioList", ["cBase", "cUILayer", "cUIScroll"],
function(e, t, n) {
    var r = {},
    i = {
        prefix: "cui-"
    },
    s = {};
    s["class"] = i.prefix + "warning",
    s.onCreate = function() {
        this.root.html(['<div class="cui-pop-box" lazyTap="true">', '<div class="cui-hd"><div class="cui-text-center">' + this.title + "</div></div>", '<div class="cui-bd cui-roller-bd" style="overflow: hidden; position: relative; ">', "</div>", "</div>"].join("")),
        this.title = this.root.find(".cui-text-center"),
        this.content = this.root.find(".cui-bd")
    },
    s.onShow = function() {
        var e = this;
        this.maskToHide(function() {
            e.hide()
        });
        var t = $('<ul class="cui-select-view " style="position: absolute; width: 100%; "></ul>');
        this.dataK = {};
        for (var r in this.data) {
            _data = this.data[r],
            _data.index = r,
            typeof _data.key == "undefined" && (_data.key = _data.id),
            typeof _data.val == "undefined" && (_data.val = _data.name);
            var i = _data.val || _data.key,
            s = $("<li>" + i + "</li>");
            s.attr("data-index", r),
            typeof _data.disabled != "undefined" && _data.disabled == 0 && s.css("color", "gray"),
            r == this.index && s.addClass("current"),
            this.dataK[_data.key] = _data,
            t.append(s)
        }
        this.content.append(t);
        var o = this.data.length;
        this.disItemNum > o && (this.disItemNum = o);
        var u = t.height() / o,
        a = u * this.disItemNum;
        this.content.css("height", a),
        this.scroll = new n({
            wrapper: this.content,
            scroller: t
        });
        var f = 0,
        l = this.index;
        this.key && (l = this.dataK[this.key].index),
        o - l < this.disItemNum && (l = o - this.disItemNum);
        var c = u * l * -1;
        this.scroll.scrollTo(0, c);
        var e = this;
        t.on("click", $.proxy(function(t) {
            var n = $(t.target);
            if (n && n.attr("data-index") !== null) {
                var r = this.data[n.attr("data-index")];
                this.itemClick.call(e, r),
                this.hide()
            }
        },
        this)),
        this.scroller = t,
        this.setzIndexTop(),
        this.root.bind("touchmove",
        function(e) {
            e.preventDefault()
        }),
        this.onHashChange = function() {
            this.hide()
        },
        $(window).on("hashchange", $.proxy(this.onHashChange, this))
    },
    s.onHide = function() {
        this.scroll.destroy(),
        this.root.unbind("touchmove"),
        this.root.remove(),
        $(window).off("hashchange", $.proxy(this.onHashChange, this)),
        this.scroller && this.scroller.off("click")
    },
    r.__propertys__ = function() {
        this.title,
        this.content,
        this.itemClick = function() {},
        this.scroll = null,
        this.data = [],
        this.index = -1,
        this.key = null,
        this.disItemNum = 5
    },
    r.initialize = function($super, e) {
        this.setOption(function(e, t) {
            this[e] = t
        }),
        $super($.extend(s, e))
    };
    var o = new e.Class(t, r);
    return o
}),
define("cUIEventListener", ["libs", "cBase"],
function(e, t) {
    var n = {};
    return n.__propertys__ = function() {
        this.__events__ = {}
    },
    n.initialize = function(e) {},
    n.addEvent = function(e, t) {
        if (!e || !t) throw "addEvent Parameter is not complete!";
        var n = this.__events__[e] || [];
        n.push(t),
        this.__events__[e] = n
    },
    n.removeEvent = function(e, t) {
        if (!e) throw "removeEvent parameters must be at least specify the type!";
        var n = this.__events__[e],
        r;
        if (!n) return;
        if (t) for (var i = Math.max(n.length - 1, 0); i >= 0; i--) n[i] === t && n.splice(i, 1);
        else delete n[e]
    },
    n.trigger = function(e, t, n) {
        var r = this.__events__[e];
        if (r) for (var i = 0,
        s = r.length; i < s; i++) typeof r[i] == "function" && r[i].apply(n || this, t)
    },
    new t.Class(n)
}),
define("cUISwitch", ["cBase", "cUIAbstractView"],
function(e, t) {
    var n = {},
    r = {
        prefix: "cui-"
    };
    return n.__propertys__ = function() {
        this.mouseData = {
            sX: 0,
            eX: 0,
            sY: 0,
            eY: 0
        },
        this.checkedFlag = !1
    },
    n.initialize = function($super, e) {
        this.bindEvent(),
        this.allowsConfig.changed = !0,
        this.checkedFlag = e.checked,
        $super(e),
        this.show()
    },
    n.bindEvent = function() {
        this.addEvent("onShow",
        function() {
            var e = this;
            this.el = this.root.find(".cui-switch"),
            this.switchBar = this.el.find(".cui-switch-bg"),
            $.flip(this.root, "left", $.proxy(function() {
                this.unChecked()
            },
            this)),
            $.flip(this.root, "right", $.proxy(function() {
                this.checked()
            },
            this)),
            $.flip(this.root, "tap", $.proxy(function() {
                this.el.hasClass("current") ? this.unChecked() : this.checked();
                return
            },
            this))
        }),
        this.addEvent("onHide",
        function() {
            var e = this;
            $.flipDestroy(this.el),
            this.root.remove()
        })
    },
    n.createHtml = function() {
        var e = this.checkedFlag ? "current": "";
        return ['<div class="cui-switch ' + e + '">', '<div class="cui-switch-bg ' + e + '"></div>', '<div class="cui-switch-scroll"></div>', "</div>"].join("")
    },
    n._getLRDir = function() {
        if (this.mouseData.eX - this.mouseData.sX > 0) return "right";
        if (this.mouseData.eX - this.mouseData.sX < 0) return "left"
    },
    n.unChecked = function() {
        if (!this.getStatus()) return;
        this.el.removeClass("current"),
        this.switchBar.removeClass("current"),
        this._triggerChanged()
    },
    n.checked = function() {
        if (this.getStatus()) return;
        this.el.addClass("current"),
        this.switchBar.addClass("current"),
        this._triggerChanged()
    },
    n._triggerChanged = function() {
        typeof this.changed == "function" && this.changed.call(this)
    },
    n.getStatus = function() {
        return this.el.hasClass("current")
    },
    n.setStatus = function(e) {
        e ? (this.el.addClass("current"), this.switchBar.addClass("current")) : (this.el.removeClass("current"), this.switchBar.removeClass("current"))
    },
    new e.Class(t, n)
}),
define("cUINum", ["cBase", "cUIAbstractView"],
function(e, t) {
    var n = {},
    r = {
        prefix: "cui-"
    };
    return n.__propertys__ = function() {
        this.min = 1,
        this.max = 9,
        this.curNum = 1,
        this.needText = !0,
        this.addClass = "num-add",
        this.minusClass = "num-minus",
        this.curClass = "num-value-txt",
        this.invalid = "num-invalid",
        this.minAble = !0,
        this.maxAble = !0,
        this.unit = "",
        this.minDom = null,
        this.maxDom = null,
        this.curDom = null,
        this.hasBindEvent = !1,
        this.changed = function() {},
        this.changeAble = function() {}
    },
    n.initialize = function($super, e) {
        for (var t in e) this[t] = e[t];
        $super(e),
        this.bindEvent(),
        this.show()
    },
    n.bindEvent = function() {
        this.addEvent("onHide",
        function() {
            this.root.off("click"),
            this.curDom.off("focus"),
            this.curDom.off("blur"),
            this.root.remove()
        }),
        this.addEvent("onShow",
        function() {
            var e = this;
            this.maxDom = this.root.find("." + this.addClass),
            this.minDom = this.root.find("." + this.minusClass),
            this.curDom = this.root.find("." + this.curClass),
            this.resetNum(),
            this.needText == 0 && this.curDom.attr("disabled", "disabled"),
            this.hasBindEvent == 0 && (this.root.on("click", $.proxy(function(e) {
                var t = $(e.target);
                if (t.hasClass(this.curClass)) return;
                t.hasClass(this.addClass) && this.maxAble && this.setVal(this.curNum + 1),
                t.hasClass(this.minusClass) && this.minAble && this.setVal(this.curNum - 1),
                e.preventDefault()
            },
            this)), this.needText && (this.curDom.on("focus", $.proxy(function(e) {
                this.curDom.val("")
            },
            this)), this.curDom.on("blur", $.proxy(function() {
                this.setVal(this.curDom.val())
            },
            this))), this.hasBindEvent = !0)
        })
    },
    n.resetNum = function(e) {
        this.curDom.attr("data-key", this.curNum),
        this.curDom.val(this.getText()),
        typeof this.changed == "function" && !e && this.changed.call(this, this.curNum),
        this.testValid()
    },
    n.getVal = function() {
        return this.curNum
    },
    n.setVal = function(e) {
        if (typeof this.changeAble == "function" && this.changeAble.call(this, e) == 0) return this.resetNum(!0),
        !1;
        var t = !0;
        e == parseInt(e) && (t = this.curNum == e, e = parseInt(e), this.curNum = e, e < this.min && (this.curNum = this.min), e > this.max && (this.curNum = this.max)),
        this.resetNum(t)
    },
    n.testValid = function() {
        this.curNum == this.min ? (this.deactiveItem(this.minDom), this.minAble = !1) : this.minAble == 0 && (this.acticeItem(this.minDom), this.minAble = !0),
        this.curNum == this.max ? (this.deactiveItem(this.maxDom), this.maxAble = !1) : this.maxAble == 0 && (this.acticeItem(this.maxDom), this.maxAble = !0)
    },
    n.deactiveItem = function(e) {
        e && e.addClass(this.invalid)
    },
    n.acticeItem = function(e) {
        e && e.removeClass(this.invalid)
    },
    n.getText = function() {
        return this.curNum + this.unit
    },
    n.createHtml = function() {
        return ['<span class="cui-number-ma">', '<i class="' + this.minusClass + '"></i>', '<input type="tel"  class="' + this.curClass + '" >', '<i class="' + this.addClass + '"></i>', "</span>"].join("")
    },
    new e.Class(t, n)
}),
define("cUIGroupList", ["cBase", "cUIAbstractView"],
function(e, t) {
    var n = {};
    return n.__propertys__ = function() {
        this.data = [],
        this.needFold = !1,
        this.foldAll = !1,
        this.unFoldOne = !1,
        this.selectedKey = null,
        this.el = null,
        this.filter = "",
        this.click = function() {},
        this.OnClick = function() {},
        this.isCreated = !1,
        this.itemTemplate = !1
    },
    n.initialize = function($super, e) {
        for (var t in e) this[t] = e[t];
        $super(e),
        this.paramFormat(),
        this.bindEvent(),
        this.show()
    },
    n.paramFormat = function() {
        var e = this.filter && this.filter.split(",");
        typeof e != "object" && (e = {}),
        this.filter = e
    },
    n.bindEvent = function() {
        this.addEvent("onShow",
        function() {
            if (this.isCreated) return;
            this.isCreated = !0;
            var e = this;
            this.init(),
            this.root.on("click", $.proxy(function(e) {
                var t = $(e.target),
                n = !1;
                for (;;) {
                    if (t.attr("id") == this.id) break;
                    if (t.attr("data-flag") == "c") {
                        n = !0;
                        break
                    }
                    t = t.parent()
                }
                if (n) {
                    this.setSelected(t.attr("data-key"));
                    return
                }
                if (this.needFold == 0) return;
                if (!t.hasClass("cui-city-t")) return;
                var r = t.parent();
                r.hasClass("cui-arrow-close") ? (this.unFoldOne && this.root.find(".cui-city-itmes > li").attr("class", "cui-arrow-close"), r.attr("class", "cui-arrow-open")) : (this.unFoldOne && this.root.find(".cui-city-itmes > li").attr("class", "cui-arrow-close"), r.attr("class", "cui-arrow-close"))
            },
            this))
        })
    },
    n.destroy = function() {
        this.root.off("click"),
        this.root.remove()
    },
    n.init = function() {
        this.foldAll == 1 && (this.needFold = !0),
        this.tmpt = _.template(['<ul class="cui-city-itmes">', "<%for(var i = 0, len = data.length; i < len; i++) { %>", '<li data-groupindex="<%=i%>" data-key="<%=data[i].id %>"  ' + (this.needFold ? '<%if(foldAll && ((typeof data[i].unFold == "undefined") || data[i].unFold != true)) {%> class="cui-arrow-close" <%} else {%> class="cui-arrow-open" <%}%>': "") + ">", '<span class="cui-city-t" ><%=data[i].name %></span>', "<%var item = data[i].data; %>", '<ul class="cui-city-n">', "<%for(var j = 0, len1 = item.length; j < len1; j++) { %>", "<% var itemData = item[j]; %>", '<% var _f = ""; for(var k in filter) { _f += (itemData[filter[k]] ? itemData[filter[k]] : "").toLowerCase() + " ";  } %>', '<li data-skey="item_<%=itemData.id%>" ' + (typeof this.groupFlag != "undefined" ? 'data-groupflag="' + this.groupFlag + '"': "") + ' data-filter="<%=_f%>" data-key="<%=itemData.id%>" data-index="<%=i%>,<%=j%>" data-flag="c" <%if(itemData.id == selectedKey){%> class="current" <%}%> > ' + (this.itemTemplate ? this.itemTemplate: "<%=itemData.name %>") + " </li>", "<%} %>", "</ul>", "</li>", "<%} %>", "</ul>"].join(""));
        var e = this.tmpt({
            data: this.data,
            foldAll: this.foldAll,
            selectedKey: this.selectedKey,
            filter: this.filter
        });
        this.root.html(e)
    },
    n.updateItem = function(e, t, n) {},
    n.updateGroup = function(e, t) {
        var n = this.data[e];
        if (!n) return;
        this.data[e] = t;
        var r = this.root.find('li[data-groupindex="' + e + '"]');
        this.tmpt = _.template(['<li data-groupindex="<%=i%>" data-key="<%=data.id %>"  ' + (this.needFold ? '<%if(foldAll && ((typeof data.unFold == "undefined") || data.unFold != true)) {%> class="cui-arrow-close" <%} else {%> class="cui-arrow-open" <%}%>': "") + ">", '<span class="cui-city-t" ><%=data.name %></span>', "<%var item = data.data; %>", '<ul class="cui-city-n">', "<%for(var j = 0, len1 = item.length; j < len1; j++) { %>", '<% var _f = ""; for(var k in filter) { _f += (item[j][filter[k]] ? item[j][filter[k]] : "").toLowerCase() + " ";  } %>', '<li data-skey="item_<%=item[j].id%>" ' + (typeof this.groupFlag != "undefined" ? 'data-groupflag="' + this.groupFlag + '"': "") + ' data-filter="<%=_f%>" data-key="<%=item[j].id%>" data-index="<%=i%>,<%=j%>" data-flag="c" <%if(item[j].id == selectedKey){%> class="current" <%}%> ><%=item[j].name %></li>', "<%} %>", "</ul>", "</li>"].join(""));
        var i = this.tmpt({
            data: t,
            foldAll: this.foldAll,
            selectedKey: this.selectedKey,
            filter: this.filter,
            i: e
        }),
        s = $(i);
        r.before(s),
        r.remove()
    },
    n.reload = function(e) {
        e && (this.data = e),
        this.root.html(""),
        this.init()
    },
    n.setSelected = function(e, t) {
        this.selectedKey = e;
        var n = this.getSelected();
        this.root.find(".cui-city-n li").removeClass("current"),
        this.el.addClass("current"),
        typeof this.onClick == "function" && this.onClick.call(this, n),
        typeof this.click == "function" && !t && this.click.call(this, n)
    },
    n.getSelected = function() {
        this.el = this.root.find('li[data-skey="item_' + (this.selectedKey || "") + '"]');
        if (typeof this.el.attr("data-index") != "string") return null;
        var e = this.el.attr("data-index").split(",");
        return e.length != 2 ? null: this.data[parseInt(e[0])].data[parseInt(e[1])]
    },
    n.createHtml = function() {
        return ""
    },
    new e.Class(t, n)
}),
define("cUIBusinessGroupList", ["cBase", "cUIAbstractView", "cUIGroupList"],
function(e, t, n) {
    var r = {};
    return r.__propertys__ = function() {
        this.needTab = !0,
        this.groupIndex = 0,
        this.groupObj = [],
        this.isCreated = !1,
        this.selectedKey = null,
        this.CLICK_RES = null,
        this.filter = "",
        this.needFold = !1,
        this.foldAll = !1,
        this.unFoldOne = !1,
        this.showFnBtn = !1,
        this.fnBtnTxt = "清除历史记录",
        this.fnBtnCallback = function() {},
        this.itemTemplate = !1,
        this.isAjax = !1,
        this.ajaxCallBack = function() {},
        this.lastKeyword = "",
        this.ajaxData = []
    },
    r.initialize = function($super, e) {
        for (var t in e) this[t] = e[t];
        $super(e),
        this.isAjax && (this.needFold = !1, this.foldAll = !1, this.filter = "", this.needTab = !1),
        this.bindEvent(),
        this.show()
    },
    r.bindEvent = function() {
        this.addEvent("onHide",
        function() {
            this.destroyItemInstance(),
            this.unBindTabEvent(),
            this.unBindSeachEvent(),
            this.unBindSeachItemEvent(),
            this.unBindCancelEvent(),
            this.unBindFnBtn(),
            this.root.remove()
        }),
        this.addEvent("onShow",
        function() {
            if (this.isCreated) return;
            this.isCreated = !0;
            var e = this;
            this.init(),
            this.bindTabEvent(),
            this.bindSeachEvent(),
            this.bindSeachItemEvent(),
            this.bindCancelEvent(),
            this.bindFnBtn()
        })
    },
    r.bindFnBtn = function() {
        if (!this.showFnBtn) return;
        this.root.find(".cui-btn-history").on("click", $.proxy(function() {
            this.fnBtnCallback.call(this)
        },
        this))
    },
    r.unBindFnBtn = function() {
        if (!this.showFnBtn) return;
        this.root.find(".cui-btn-history").off("click")
    },
    r.destroyItemInstance = function() {
        for (var e = 0,
        t = this.groupObj.length; e < t; e++) this.groupObj[e].instance.destroy()
    },
    r.unBindTabEvent = function() {
        var e = this.tabWrapper.find("li");
        e.off("click")
    },
    r.bindTabEvent = function() {
        var e = this.tabWrapper.find("li");
        e.on("click", $.proxy(function(t) {
            var n = $(t.target);
            e.removeClass("cui-tab-current"),
            n.addClass("cui-tab-current");
            var r = n.attr("data-index");
            this.groupObj[this.groupIndex].instance.hide(),
            this.groupObj[this.groupIndex].instance.selectedKey && (this.selectedKey = this.groupObj[this.groupIndex].instance.selectedKey),
            this.selectedKey && this.groupObj[r].instance.setSelected(this.selectedKey, !0),
            this.groupObj[r].instance.show(),
            this.groupIndex = r
        },
        this))
    },
    r.getSelected = function() {
        return this.selectedKey
    },
    r.unBindSeachEvent = function() {
        this.searchBox.off("focus")
    },
    r.bindSeachEvent = function() {
        this.searchBox.on("focus", $.proxy(function() {
            this.openSeach()
        },
        this))
    },
    r.unBindSeachItemEvent = function() {
        this.seachList.off("click")
    },
    r.bindSeachItemEvent = function() {
        this.seachList.on("click", $.proxy(function(e) {
            var t = $(e.target),
            n = !1;
            for (;;) {
                if (t.hasClass("seach-list")) break;
                if (t.attr("data-flag") == "c") {
                    n = !0;
                    break
                }
                t = t.parent()
            }
            if (this.isAjax) {
                typeof this.click == "function" && (this.click.call(this, this.ajaxData[t.attr("data-index")]), this.closeSeach());
                return
            }
            this.groupIndex = t.attr("data-groupflag"),
            this.selectedKey = t.attr("data-key"),
            this.groupObj[this.groupIndex].instance.setSelected(this.selectedKey),
            this.closeSeach()
        },
        this))
    },
    r.unBindCancelEvent = function() {
        this.cancel.off("click")
    },
    r.bindCancelEvent = function() {
        this.cancel.on("click", $.proxy(function() {
            this.closeSeach()
        },
        this))
    },
    r.openSeach = function() {
        this.CLICK_RES || (this.CLICK_RES = setInterval($.proxy(function() { (document.activeElement.nodeName != "INPUT" || document.activeElement.type != "text") && this.CLICK_RES && (clearInterval(this.CLICK_RES), this.CLICK_RES = null);
            var e = this.searchBox.val().toLowerCase();
            if (e == "") {
                setTimeout($.proxy(function() { (document.activeElement.nodeName != "INPUT" || document.activeElement.type != "text") && this.closeSeach()
                },
                this), 500);
                return
            }
            if (this.lastKeyword == e) return ! 0;
            this.lastKeyword = e,
            this.wrapper.addClass("cui-input-focus"),
            this.root.find(".cui-btn-history").hide(),
            this.listWrapper.hide(),
            this.tabWrapper.hide(),
            this.seachList.show(),
            this.seachList.html(""),
            this.noData.hide();
            if (this.isAjax) {
                this.ajaxCallBack.call(this, e),
                this.LOADINGSRC = setTimeout($.proxy(function() {
                    this.loading.show()
                },
                this), 200);
                return
            }
            var t = this.listWrapper.find('li[data-filter*="' + e + '"]'),
            n = [];
            for (var r = 0,
            i = t.length; r < i; r++) {
                var s = !1;
                for (var o in n) if ($(n[o]).attr("data-key") == $(t[r]).attr("data-key")) {
                    s = !0;
                    break
                }
                s == 0 && n.push(t[r])
            }
            if (n.length == 0) {
                this.noData.show();
                return
            }
            this.seachList.append($(n).clone(!0))
        },
        this), 500))
    },
    r.ajaxDataHandle = function(e) {
        this.ajaxData = e;
        var t = ["<% for(var i = 0, len = data.length; i < len; i++ ){ %>", "<% itemData = data[i]; %>", '<li data-key="<%=itemData.id%>" data-index="<%=i%>" data-flag="c" ' + (this.itemTemplate ? this.itemTemplate: "<%=itemData.name %>") + " </li>", "<% } %>"].join(""),
        n = _.template(t)({
            data: e
        });
        this.LOADINGSRC && clearTimeout(this.LOADINGSRC),
        this.loading.hide(),
        this.seachList.html(n)
    },
    r.closeSeach = function() {
        this.CLICK_RES && (clearInterval(this.CLICK_RES), this.CLICK_RES = null),
        this.wrapper.removeClass("cui-input-focus"),
        this.noData.hide(),
        this.searchBox.val(""),
        this.needTab && this.tabWrapper.show(),
        this.seachList.hide(),
        this.listWrapper.show(),
        this.lastKeyword = "",
        this.root.find(".cui-btn-history").show()
    },
    r.init = function() {
        this.tabWrapper = this.root.find(".cui-tab-mod"),
        this.tabBar = this.tabWrapper.find(".cui-tab-scrollbar"),
        this.noData = this.root.find(".cui-city-novalue"),
        this.wrapper = this.root.find(".cui-citys-hd"),
        this.listWrapper = this.root.find(".cui-citys-bd"),
        this.searchBox = this.root.find(".cui-input-box"),
        this.seachList = this.root.find(".seach-list"),
        this.cancel = this.root.find(".cui-btn-cancle"),
        this.loading = this.root.find(".cui-pro-load"),
        this.initTab(),
        this.initGroupList(),
        this.bindEvent()
    },
    r.initTab = function() {
        for (var e = 0,
        t = this.groupObj.length; e < t; e++) {
            var n = '<li data-index="' + e + '" ' + (e == this.groupIndex ? 'class="cui-tab-current"': "") + " >" + this.groupObj[e].name + "</li>";
            this.tabBar.before(n)
        }
    },
    r.initGroupList = function() {
        var e = this;
        for (var t = 0,
        r = this.groupObj.length; t < r; t++) {
            var i = this.groupObj[t].data;
            this.groupObj[t].instance = new n({
                rootBox: this.listWrapper,
                data: i,
                filter: this.filter,
                click: this.click,
                foldAll: this.foldAll,
                needFold: this.needFold,
                itemTemplate: this.itemTemplate,
                unFoldOne: this.unFoldOne,
                groupFlag: t,
                onClick: function() {
                    e.selectedKey = this.selectedKey
                }
            }),
            this.groupObj[t].instance.hide()
        }
        this.groupObj[this.groupIndex].instance.show(),
        this.groupObj[this.groupIndex].instance.setSelected(this.selectedKey, !0)
    },
    r.reload = function(e) {
        e && (this.groupObj = e),
        this.destroyItemInstance(),
        this.listWrapper.empty(),
        this.initGroupList()
    },
    r.updateTab = function(e, t) {},
    r.updateTabGroup = function(e, t, n) {},
    r.updateAllTabGroup = function(e, t) {
        for (var n = 0,
        r = this.groupObj.length; n < r; n++) this.groupObj[n].instance.updateGroup(e, t)
    },
    r.updateTabGroupItem = function(e, t, n, r) {},
    r.createHtml = function() {
        return ['<section class="cui-citys-hd ">', '<div class="cui-input-bd">', '<input type="text" class="cui-input-box" placeholder="中文/拼音/首字母">', '<span class="cui-pro-load" style="display: none;">', '<span class="cui-pro-radius"></span>', '<span class="cui-i cui-pro-logo"></span>', "</span>", "</div>", '<button type="button" class="cui-btn-cancle">', "取消</button>", "</section>", '<p class="cui-city-novalue" style="display: none;">没有结果</p>', '<ul class="cui-tab-mod" ' + (this.needTab ? "": 'style="display: none"') + " >", '<i class="cui-tab-scrollbar cui-tabnum' + this.groupObj.length + '"></i>', "</ul>", '<ul class="cui-city-associate seach-list"></ul>', '<section  class="cui-citys-bd">', "</section>", this.showFnBtn ? '<button type="button" class="cui-btn-history" >' + this.fnBtnTxt + "</button>": ""].join("")
    },
    new e.Class(t, r)
}),
define("cUITab", ["cBase", "cUIAbstractView"],
function(e, t) {
    var n = {},
    r = {
        prefix: "cui-"
    };
    return n.__propertys__ = function() {
        this.selectedKey = null,
        this.data = [],
        this.changed = function() {},
        this.changeAble = function() {},
        this.curEl = null
    },
    n.initialize = function($super, e) {
        for (var t in e) this[t] = e[t];
        $super(e),
        this.bindEvent(),
        this.show()
    },
    n.bindEvent = function() {
        this.addEvent("onShow",
        function() {
            this.init(),
            this.root.on("click", $.proxy(function(e) {
                var t = $(e.target);
                if (t[0].tagName.toLowerCase() != "li") return;
                this.setVal(t.attr("data-key"))
            },
            this))
        }),
        this.addEvent("onHide",
        function() {
            this.root.off("click"),
            this.root.remove()
        })
    },
    n.getVal = function() {
        return this.selectedKey
    },
    n.setVal = function(e) {
        this.curEl = this.root.find('li[data-key="' + e + '"]');
        var t = this.curEl.attr("data-index"),
        n = this.data[t];
        if (typeof this.changeAble == "function" && this.changeAble.call(this, n) == 0) return ! 1;
        if (!n) {
            0;
            return
        }
        this._tab = this.root.find(".cui-tab-scrollbar");
        var r = this.selectedKey == e;
        this.selectedKey = e,
        this.tabs.removeClass("cui-tab-current"),
        this.curEl && this.curEl.addClass("cui-tab-current");
        if (navigator.userAgent.toLowerCase().indexOf("android") > -1) {
            var i = this._tab.css("width");
            setTimeout($.proxy(function() {
                this._tab.css("width", i)
            },
            this), 0)
        }
        r == 0 && typeof this.changed == "function" && this.changed.call(this, n)
    },
    n.setIndex = function(e) {
        if (e < 0 || e > this.data.length - 1) return;
        this.setVal(this.data[e].id)
    },
    n.getIndex = function() {
        for (var e = 0,
        t = this.data.length; e < t; e++) if (this.getVal() == this.data[e].id) return e;
        return - 1
    },
    n.init = function() {
        this.tmpt = _.template(['<ul class="cui-tab-mod">', "<%for(var i = 0, len = data.length; i < len; i++) { %>", '<li data-key="<%=data[i].id %>" data-index="<%=i%>" <% if(data[i].id == selectedKey) { %>class="cui-tab-current"<%} %> ><%=data[i].name %></li>', "<%} %>", '<i class="cui-tab-scrollbar cui-tabnum<%=len %>"></i>', "</ul>"].join(""));
        var e = this.tmpt({
            data: this.data,
            selectedKey: this.selectedKey
        });
        this.root.html(e),
        this.tabs = this.root.find("li"),
        this.curEl = this.root.find(".cui-tab-current")
    },
    n.createHtml = function() {
        return ""
    },
    new e.Class(t, n)
}),
define("cUIImageSlider", ["cBase", "cUIBase", "libs"],
function(e, t) {
    return new e.Class({
        __propertys__: function() {
            this.ENUM = {
                DIR: {
                    LEFT: "LEFT",
                    RIGHT: "RIGHT"
                }
            },
            this.images = [],
            this.autoPlay = !0,
            this.index = 0,
            this.delay = 3e3,
            this.dir = this.ENUM.DIR.LEFT,
            this.errorImage = "",
            this.lodingImage = "",
            this.onChange,
            this.onImageClick,
            this.container,
            this.onChanged,
            this.scope,
            this.showNav = !0,
            this.autoHeight = !1,
            this.defaultImageUrl,
            this.defaultHeight,
            this.imageSize,
            this.loop = !1,
            this.errorImageUrl = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAYCAMAAAAGTsm6AAAAMFBMVEX6+vr+/v7+/v7+/v7+/v7+/v7+/v7y8vLv7+/+/v7+/v7+/v729vYAAADt7e3+/v6PQhf9AAAADnRSTlOtVTOIZpki1+wRd0TCAMJ8iPYAAAKfSURBVHjaxZWLjqMwDEXJO7FN7v//7WITOgQ6pVpptbdSecTxiW3iLOt/0g3cirj3po1ZL5lcuw8Sud8QgegJbALy+laMZb/UdY2n9Xj9F/wKFnwLXu/KRiSLG7ISwk+s6Fm9n9PgZUjNKvJ34LLeRThECl4L4ssDyMKak7NLTRvKc41z28Bia15dEVWxwkYiShAicgb26QiwAY6ZC1gV4wCTXdXUjey09gFMOCSvVSv46u2kiEnyMm3DNGGIP4GLCLpIgQyOmD266MsqKnuQPnJbkWhTBalSeIEFycAdZi3in2osOjHM4CqmO5i0gvcaG9ipr4gyDT+BaQKbUom3VLc6xqXewaymBfQdOOu3QFew14HSbGPVLYRCediPBHZ5D2b0bOt6BBvvBsZLoqtvGIEGCWRCJVOQOIEFVpMqfwleaEFajLImNId0dKWb6AXWqrQg8NvN8ggmJRHcBNZiJu1OwyIclY8UWZUAxyoinsBqHtcMegSnHcwzOJfKXODUrwfVa8lq7z+uZ7Dboo3gR3CB0q/gcs5kB+I8KSKlnmdwrTu4AXnpj706j213ATvtlQU8knIJ2HdkRjmfoLkplL0ikJAewYTwDnw0icxRbeRyhCPoaGpTEiiGdpwY+RFcDSSbJVu76vacmYlS31PN1nfpRfHVdnjuqPHcWfS919ttoD2BCXWcyRPYYVcgzr533ki1DwLBuJZw9ERsD0mPCUH3+nr7xc9ghu4jEvT18nGFyHvfcx3eTjGbyaEDMuLJgvG+JRTz3pmA1ApQQ/4d3KpOaxraRhhgd65xC0C0gOyiHs/7iBcrZ65HElKFuQkAPp1OLTUFiNoysU7lfKr/usa6L4TS0Vfm8uVoo8q17Y/KdseyXFP9T9VOn9s88gcLTIlzurC4gAAAAABJRU5ErkJggg==",
            this.loadImageUrl = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAYCAMAAAAGTsm6AAAAMFBMVEX6+vr+/v7+/v7+/v7+/v7+/v7+/v7+/v7y8vLv7+/+/v729vb+/v4AAADt7e3+/v6c4gmbAAAADnRSTlOtM4hmVZkiEdfsRMJ3AHDRYrYAAAGwSURBVHjaxdbZktsgEAVQLSxNL9z//9vQgvFYUjKpkqnkPhgJbI4bJMuL/af8DGeS8IcRZm+Shnwb21U/hQ1Ivx9gLL0pDbqMKbYJsN2TDlGPuiF3RzGhYrJ7FF9Rh42wT4VTbrD40WqBxEM89lAjpL2GA15j7h/gdIIzp0fwd2FijB62ER6TO/zdd40+g0kEVYQgw5EDbn3eWcRznEhFh6t4yhgrz+BXQYztDBfpucNyWmr9GNYTfCTSflrqyXDyO0Wv8OoDlH3cCpmRptmwe3cYr0jrsgzwv4EXXRAX2VTVInJAnL7UCvaXcNnjXGI7zuMdG/gLLuoRSG+fw7HDfIYTFWZCUGVboaXMvY89BNevML1PXIH9BRN7ImJvH8MJ5NwVDqqBCTwWpdjIftnjXfaHsGK7wyMCS9wmVtdmP50K+CCS8fFzVY/zxKwaa19qBlqn5qmwooxn8gkO6NmU01org3KpM2GG30cqqHa5uLad27F/h4q1OcAyEc7Fp8teWhMGHN73OG/9io7evGX78K9PjtkBcZLVa105ve2/2V5CLzHaewTyAJ6QlVf7a34BIRiNTVIrVfEAAAAASUVORK5CYII=",
            this._loadingNode,
            this._errorNode,
            this._isloadingImage = !1,
            this._pfix = "slider",
            this._changing = !1,
            this._containerNode,
            this._rootNode,
            this._imageNode,
            this._navNode,
            this._imageLoaderNode,
            this._handerStartPos = {
                x: 0,
                y: 0
            },
            this._moveValue = 50,
            this._imageCount = 0,
            this._played = !1,
            this._size = {
                height: 0,
                width: 0
            },
            this._lastSize = {
                height: 0,
                width: 0
            },
            this._displayImage,
            this._nextImage,
            this._loadingImage = new Image,
            this._changeCompletedEvents = [],
            this._autoPlayTimeout,
            this._loadMsg = "加载中...",
            this.firstLoad = !0,
            this._defaultSize = {
                width: 0,
                height: 0
            },
            this._loadingImage = new Image,
            this._errorImage = new Image
        },
        initialize: function(e) {
            for (var t in e) switch (t) {
            case "images":
            case "autoPlay":
            case "delay":
            case "dir":
            case "index":
            case "onChange":
            case "autoPlay":
            case "onImageClick":
            case "scope":
            case "onChanged":
            case "errorImageUrl":
            case "loadImageUrl":
            case "loop":
            case "showNav":
            case "defaultImageUrl":
            case "defaultHeight":
            case "imageSize":
                this[t] = e[t];
                break;
            case "container":
                this._containerNode = typeof e[t] == "string" ? $(e[t]) : e[t],
                this[t] = e[t]
            }
            this._validArgs(),
            this._correctArgs(),
            this._imageCount = this.images.length,
            this._loadingImage.src = this.loadImageUrl,
            this._errorImage.src = this.errorImageUrl,
            this.imageSize && this.imageSize.width && this.imageSize.height ? this.autoHeight = !1 : this.autoHeight = !0
        },
        play: function() {
            this._played || (this._played = !0, this._injectHTML(), this._bindEvents()),
            this.rePlay()
        },
        stop: function() {
            this._clearAutoPlay()
        },
        rePlay: function() {
            this.autoPlay && this._autoPlay()
        },
        _autoPlay: function() {
            this._autoPlayTimeout = setTimeout($.proxy(function() {
                this._isloadingImage || this._play()
            },
            this), this.delay)
        },
        next: function() {
            this._changing || this._play()
        },
        pre: function() {
            if (this._changing) return;
            if (this.dir === this.ENUM.DIR.RIGHT) if (this.index >= this._imageCount - 1) {
                if (!this.loop) return;
                this.index = 0
            } else this.index++;
            else if (this.index <= 0) {
                if (!this.loop) return;
                this.index = this._imageCount - 1
            } else this.index--;
            this.goto(this.index)
        },
        "goto": function(e) {
            this.index = e,
            this._changeImage()
        },
        _play: function() {
            this.dir === this.ENUM.DIR.RIGHT ? this._imageToRight() : this._imageToLeft()
        },
        _clearAutoPlay: function() {
            clearTimeout(this._autoPlayTimeout)
        },
        _validArgs: function() {
            if (!this.container || !this._containerNode) throw "[c.widget.imageSlider]:no container!"
        },
        _correctArgs: function() {
            this.delay <= 500 && (this.delay = 2e3)
        },
        _createHTML: function() {
            return ['<div class="cui-sliderContainer" style="width:100%;position:relative;">', '<div class="cui-imageContainer" style="width:100%;">', "</div>", '<div class="cui-navContainer" style="color:#1491c5;position:absolute;"></div>', '<div class="cui-imageLoader">', "</div>"].join("")
        },
        _createNav: function() {
            var e = [];
            for (var t = 0; t < this._imageCount; t++) {
                var n = t == this.index ? "cui-slide-nav-item-current": "";
                e.push('<span class="cui-slide-nav-item ' + n + '"></span>')
            }
            this._navNode.empty().html(e.join(" "))
        },
        _injectHTML: function() {
            this._rootNode = $(this._createHTML()),
            this._containerNode.html(this._rootNode),
            this._imageNode = this._rootNode.find(".cui-imageContainer"),
            this._navNode = this._rootNode.find(".cui-navContainer"),
            this.showNav || this._navNode.css("display", "none"),
            this._imageNode.empty(),
            this._createLoading(),
            this._imageCount > 0 ? this._createImageItem(this.index, $.proxy(function(e, t) {
                this._createNav(),
                this._setSize(t.height, t.width),
                this._displayImage = e,
                this._createImageContainer()
            },
            this)) : (this._createDefault(), this._loadingNode.css("display", "none"))
        },
        _onImageClick: function() {
            var e = this.images[this.index];
            if (e && e.onClick) {
                e.onClick.call(this.scope || this, e);
                return
            }
            this.onImageClick && this.onImageClick.call(this.scope || this)
        },
        _createImageItem: function(e, t) {
            this._isloadingImage = !0,
            !e && (e = 0);
            var n = this._getImageInfo(e),
            r = new Image;
            n.src && (r.src = n.src),
            n.alt && (r.alt = n.alt);
            var i = this;
            r.onload = function() {
                n.orgImage = r,
                i.autoHeight || (i._defaultSize.width = r.width, i._defaultSize.height = r.height),
                i._isloadingImage = !1,
                t.call(i, n, r)
            },
            r.onerror = function() {
                n.loadError = !0,
                i._errorImage = new Image,
                i._errorImage.src = i.errorImageUrl,
                i._isloadingImage = !1,
                i._errorImage.onload = function() {
                    n.orgImage = i._errorImage,
                    t.call(i, n, i._errorImage)
                }
            }
        },
        _getImageInfo: function(e) { ! e && (e = 0);
            for (var t = 0,
            n = this.images.length; t < n; t++) if (e === t) return this.images[t];
            throw new Error("[c.ui.imageSlider]:image index is " + e + ",but images.length is " + n)
        },
        _bindEvents: function() {
            this._containerNode.bind("touchmove", $.proxy(this._touchmove, this)),
            this._containerNode.bind("touchstart", $.proxy(this._touchstart, this)),
            this._containerNode.bind("touchend", $.proxy(this._touchend, this)),
            $(window).on("resize", $.proxy(this._resize, this)),
            this._navNode.bind("click", $.proxy(this._switchImage, this)),
            this._imageNode.bind("click", $.proxy(this._onImageClick, this))
        },
        _switchImage: function(e) {
            var t = e.targetElement || e.srcElement,
            n = $(t).data("index");
            if (n !== 0 && !n) return;
            if (this.index === +n) return;
            this.index = n,
            this._changeImage()
        },
        _imageToRight: function() {
            if (this.index <= 0) {
                if (!this.loop) return;
                this.index = this._imageCount - 1
            } else this.index--;
            this._changeImage(this.ENUM.DIR.LEFT)
        },
        _imageToLeft: function() {
            if (this.index >= this._imageCount - 1) {
                if (!this.loop) return;
                this.index = 0
            } else this.index++;
            this._changeImage(this.ENUM.DIR.RIGHT)
        },
        _changeImage: function(e) {
            if (this._imageCount <= 1) return;
            this._clearAutoPlay(),
            this._changing = !0,
            !e && (e = this.dir);
            var t = this.images[this.index];
            t.node ? (this._nextImage = t, this._showSliderImage(e)) : (this._nextImage = {
                node: this._loadingNode,
                orgImage: this._loadingImage
            },
            this._showLoading(), this._createImageItem(this.index, $.proxy(function(t, n) {
                this._createImageContainer(),
                this._nextImage = t,
                this._showSliderImage(e)
            },
            this)))
        },
        _showSliderImage: function(e, t) {
            var n = 0,
            r = 0,
            i = 0,
            s = 0;
            e === this.ENUM.DIR.LEFT ? (n = -1 * this._size.width, r = 0, i = 0, s = this._size.width) : (n = this._size.width, r = 0, i = 0, s = -1 * this._size.width),
            this._displayImage.node.css("left", r),
            this._nextImage.node.css("left", n).css("display", ""),
            this._nextImage.node.animate({
                left: i
            },
            500, "ease-out", $.proxy(function() {
                this._changing = !1,
                this._changeCompeted(),
                this.rePlay()
            },
            this)),
            this._displayImage.node.animate({
                left: s
            },
            500, "ease-out", $.proxy(function() {
                this._displayImage.node.css("display", "none").css("left", 0),
                this._displayImage = this._nextImage
            },
            this))
        },
        _touchmove: function(e) {
            if (this._isMoved) return;
            var n = t.getMousePosOfElement(e.targetTouches[0], e.currentTarget);
            if (!this._isMovedChecked) {
                var r = Math.abs(n.x - this._handerStartPos.x),
                i = Math.abs(n.y - this._handerStartPos.y);
                if (i > r) {
                    this._isMoved = !0;
                    return
                }
            }
            this._isMovedChecked = !0,
            e.preventDefault();
            if (this._changing) return;
            var s = n.x - this._handerStartPos.x;
            s > 0 && s > this._moveValue ? this._imageToRight() : s < 0 && Math.abs(s) > this._moveValue && this._imageToLeft()
        },
        _touchstart: function(e) {
            this._isMoved = !1,
            this._isMovedChecked = !1;
            var n = t.getMousePosOfElement(e.targetTouches[0], e.currentTarget);
            this._handerStartPos = {
                x: n.x,
                y: n.y
            }
        },
        _touchend: function(e) {
            e.preventDefault()
        },
        _setSize: function(e, t) {
            this._size.width = Math.ceil($(window).width()),
            this._size.height = Math.ceil(e * (this._size.width / t)),
            this._size.height < 100 && (this._size.height = 100, this._size.width = t * (this._size.height / e)),
            this._rootNode.css("width", this._size.width).css("height", this._size.height),
            this._imageNode.find("div").find("img").css("width", this._size.width).css("height", this._size.height),
            this.showNav && this._setNavPos()
        },
        _setNavPos: function() {
            var e = (this._size.width - 2 * this._imageCount * 10) / 2,
            t = this._size.height - 30;
            this._navNode.css("left", e).css("top", t)
        },
        _resize: function() {
            this._lastSize.width = this._size.width,
            this._lastSize.height = this._size.height,
            this.imageSize && this.imageSize.height && this.imageSize.width ? this._setSize(this.imageSize.height, this.imageSize.width) : this._displayImage && !this._displayImage.loadError && this._setSize(this._displayImage.orgImage.height, this._displayImage.orgImage.width)
        },
        _changeCompeted: function() {
            for (var e in this._changeCompletedEvents) this._changeCompletedEvents[e]();
            this._changeCompletedEvents.length = 0,
            this._changeNav(),
            this.autoHeight && this._resize(),
            this.onChanged && this.onChanged.call(this.scope || this, this.images[this.index], this.index)
        },
        _changeNav: function() {
            this.showNav && (this._navNode.find("span").removeClass("cui-slide-nav-item-current"), $(this._navNode.find("span")[this.index]).addClass("cui-slide-nav-item-current"))
        },
        _createImageContainer: function() {
            var e = this.images[this.index];
            this._loadingNode.css("display", "none");
            if (!e.node) {
                var n = t.getElementPos(this._rootNode[0]).top - 48;
                e.loadError ? e.node = $(this._createImageHtml(this.errorImageUrl, e.alt)) : e.node = $(this._createImageHtml(e.src, e.alt)),
                this._imageNode.append(e.node),
                e.node.css("position", "absolute").css("left", 0),
                e.node.bind("click",
                function(e) {
                    e.preventDefault()
                })
            }
            this.autoHeight && this._resize()
        },
        _createLoading: function() {
            if (this.firstLoad) {
                this._loadingNode = $(this._createImageHtml(this.loadImageUrl));
                var e = ['<div class="cui-breaking-load">', '<div class="cui-i cui-m-logo">', '</div> <div class="cui-i cui-w-loading">', "</div></div>"];
                this._loadingNode.html(e.join(" ")),
                this.autoHeight || (this._resize(), this._setLoadingPos()),
                this._imageNode.append(this._loadingNode),
                this.firstLoad = !1
            }
        },
        _setLoadingPos: function() {
            this._loadingNode.css("position", "absolute").css("height", this._size.height).css("width", this._size.width);
            if (this._size.height) {
                var e = (this._size.height - 70) / 2;
                this._loadingNode.find(".cui-breaking-load").css("top", e)
            }
        },
        _showLoading: function() {
            this._loadingNode.css("display", ""),
            this._setLoadingPos()
        },
        _createDefault: function() {
            if (this.defaultImageUrl) {
                var e = new Image;
                e.src = this.defaultImageUrl;
                var t = this;
                e.onload = function() {
                    var n = $(t._createImageHtml(t.defaultImageUrl));
                    t._imageNode.append(n),
                    t._displayImage = e,
                    t.autoHeight ? t._setSize(e.height, e.width) : t._setSize(t.imageSize.height, t.imageSize.width)
                }
            }
        },
        _createImageHtml: function(e, t) {
            return '<div class="image-node slider-imageContainerNode"><img style="width:' + this._size.width + "px;height:" + this._size.height + 'px" src="' + e + '" alt="' + (t ? t: "") + '"></div>'
        }
    })
}),
define("cUICore", ["cHistory", "cView", "cDataSource", "cUIBase", "cUIAbstractView", "cAdView", "cUIAlert", "cUIAnimation", "cUICitylist", "cUIHeadWarning", "cUIInputClear", "cUILayer", "cUILoading", "cUILoadingLayer", "cUIMask", "cUIPageview", "cUIScrollRadio", "cUIScrollRadioList", "cUIScrollList", "cUIToast", "cUIWarning", "cUIWarning404", "cUIHashObserve", "cUIEventListener", "cUISwitch", "cUINum", "cUIGroupList", "cUIBusinessGroupList", "cUITab", "cUIImageSlider", "cUIBubbleLayer"],
function(e, t, n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y, b, w, E, S, x, T, N, C, k, L, A, O) {
    var M = {
        prefix: "cui-"
    },
    _ = {
        History: e,
        View: t,
        DataSource: n,
        Tools: r,
        config: M,
        AbstractView: i,
        AdView: s,
        Alert: o,
        Animation: u,
        CityList: a,
        HeadWarning: f,
        InputClear: l,
        Layer: c,
        Loading: h,
        LoadingLayer: p,
        Mask: d,
        PageView: v,
        ScrollRadio: m,
        ScrollRadioList: g,
        ScrollList: y,
        Toast: b,
        Warning: w,
        HashObserve: S,
        EventListener: x,
        cuiSwitch: T,
        cuiNum: N,
        cUIGroupList: C,
        cUIBusinessGroupList: k,
        cUITab: L,
        cUIImageSlider: A,
        cUIBubbleLayer: O
    };
    return _
}),
define("cUI", ["cUICore"],
function(e) {
    return e
}),
define("cLazyload", ["libs"],
function(e) {
    var t = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAYCAMAAAAGTsm6AAAAMFBMVEXy8vL6+vr+/v7+/v7+/v7v7+/+/v7+/v7+/v7+/v7+/v729vb+/v4AAADt7e3+/v673auIAAAADnRSTlPXrYhVZuwimTMRRMJ3AKjyf0QAAAH3SURBVHjaxdbZkqowGEXhzMM/ZL//2x6IEYh0Cd1addaFUhbJZyKKRv9TZ1iSHirpU6Gme3BB888h3kXwj8PLePZCc0F0lNn1Z0Irl3AQYVhZYnVYY/Zj+Nxze/Aa64ghYyRfrHieZ4Gz398qRV6KiNyL24AsS06e/RGuKqBKpJrWI31NIDo1Tsrt+foR9tfwLresBLcerYvplUu4oMlamGH6BWwXjREZIMGIruBq9jP/Co8pmLmsc9IC1ao6TXaCM0xfttUZnqO3MAkVgjwGaofXklDPQGgUaMA1Q0zW0kx9gQ0fK29hTUQCzuwqzA4TzslzxeJWtbszTPd/uTweNQ4C2eFiZc0hCoP7cQ6HqzpZwPoZbkj34RpZHmRlpAHveeT5+upwChlNpKFZKTsM6CU8Rxz6KvrGT59MRjjDDDRXlnNdQ6xHWLbSDZg4yxa7+UuOeoZTDrU22OUx+H2rCw7RHRiRt8A6KVbP8INxETJ9xgRDI3MPPk48wamBfoZJimqYr2rBtlv82YqrAesGU647nIHoyvxWI/yH8O62tMOCdvw6iQHsESag7vAHW+3buJ9LB0qE3eAe5XAcEZH1Cyv2jO52auSf9+OpMSID6RtwALiOLbdY4/DuH0jq1/gnWx3HgXTnnH2dE20M0F/DQvrlClU99Q/TJ4uko/HGBAAAAABJRU5ErkJggg==",
    n = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAYCAMAAAAGTsm6AAAAMFBMVEX6+vr+/v7+/v7+/v7+/v7+/v7+/v7+/v7y8vLv7+/+/v729vb+/v4AAADt7e3+/v6c4gmbAAAADnRSTlOtM4hmVZkiEdfsRMJ3AHDRYrYAAAGwSURBVHjaxdbZktsgEAVQLSxNL9z//9vQgvFYUjKpkqnkPhgJbI4bJMuL/af8DGeS8IcRZm+Shnwb21U/hQ1Ivx9gLL0pDbqMKbYJsN2TDlGPuiF3RzGhYrJ7FF9Rh42wT4VTbrD40WqBxEM89lAjpL2GA15j7h/gdIIzp0fwd2FijB62ER6TO/zdd40+g0kEVYQgw5EDbn3eWcRznEhFh6t4yhgrz+BXQYztDBfpucNyWmr9GNYTfCTSflrqyXDyO0Wv8OoDlH3cCpmRptmwe3cYr0jrsgzwv4EXXRAX2VTVInJAnL7UCvaXcNnjXGI7zuMdG/gLLuoRSG+fw7HDfIYTFWZCUGVboaXMvY89BNevML1PXIH9BRN7ImJvH8MJ5NwVDqqBCTwWpdjIftnjXfaHsGK7wyMCS9wmVtdmP50K+CCS8fFzVY/zxKwaa19qBlqn5qmwooxn8gkO6NmU01org3KpM2GG30cqqHa5uLad27F/h4q1OcAyEc7Fp8teWhMGHN73OG/9io7evGX78K9PjtkBcZLVa105ve2/2V5CLzHaewTyAJ6QlVf7a34BIRiNTVIrVfEAAAAASUVORK5CYII=",
    r = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHgAAAAYCAMAAAAGTsm6AAAAMFBMVEX6+vr+/v7+/v7+/v7+/v7+/v7+/v7y8vLv7+/+/v7+/v7+/v729vYAAADt7e3+/v6PQhf9AAAADnRSTlOtVTOIZpki1+wRd0TCAMJ8iPYAAAKfSURBVHjaxZWLjqMwDEXJO7FN7v//7WITOgQ6pVpptbdSecTxiW3iLOt/0g3cirj3po1ZL5lcuw8Sud8QgegJbALy+laMZb/UdY2n9Xj9F/wKFnwLXu/KRiSLG7ISwk+s6Fm9n9PgZUjNKvJ34LLeRThECl4L4ssDyMKak7NLTRvKc41z28Bia15dEVWxwkYiShAicgb26QiwAY6ZC1gV4wCTXdXUjey09gFMOCSvVSv46u2kiEnyMm3DNGGIP4GLCLpIgQyOmD266MsqKnuQPnJbkWhTBalSeIEFycAdZi3in2osOjHM4CqmO5i0gvcaG9ipr4gyDT+BaQKbUom3VLc6xqXewaymBfQdOOu3QFew14HSbGPVLYRCediPBHZ5D2b0bOt6BBvvBsZLoqtvGIEGCWRCJVOQOIEFVpMqfwleaEFajLImNId0dKWb6AXWqrQg8NvN8ggmJRHcBNZiJu1OwyIclY8UWZUAxyoinsBqHtcMegSnHcwzOJfKXODUrwfVa8lq7z+uZ7Dboo3gR3CB0q/gcs5kB+I8KSKlnmdwrTu4AXnpj706j213ATvtlQU8knIJ2HdkRjmfoLkplL0ikJAewYTwDnw0icxRbeRyhCPoaGpTEiiGdpwY+RFcDSSbJVu76vacmYlS31PN1nfpRfHVdnjuqPHcWfS919ttoD2BCXWcyRPYYVcgzr533ki1DwLBuJZw9ERsD0mPCUH3+nr7xc9ghu4jEvT18nGFyHvfcx3eTjGbyaEDMuLJgvG+JRTz3pmA1ApQQ/4d3KpOaxraRhhgd65xC0C0gOyiHs/7iBcrZ65HElKFuQkAPp1OLTUFiNoysU7lfKr/usa6L4TS0Vfm8uVoo8q17Y/KdseyXFP9T9VOn9s88gcLTIlzurC4gAAAAABJRU5ErkJggg==",
    i = {};
    return i.lazyload = function(e, i, s, o) {
        var u = function() {
            var e = $(this),
            u = $(new Image),
            a = e.attr("src");
            e.addClass("cui-item-imgbg");
            if (typeof a == "string" && a.length > 0) {
                e.attr("src", n),
                typeof i == "function" && i.call(this, this);
                var f = function() {
                    e.attr("src", r)
                };
                f = s || f;
                var l = function() {
                    e.attr("src", a),
                    e.removeClass("cui-item-imgbg")
                };
                l = o || l,
                u.bind("error", f).bind("load", l).attr("src", a)
            } else e.attr("src", t)
        };
        $(e).each(u)
    },
    i
}),
define("c", ["cBase", "cUI", "cUtility", "cView", "cModel", "cStore", "cStorage", "cAjax", "CommonStore", "cLazyload", "cLog"],
function(e, t, n, r, i, s, o, u, a, f, l) {
    var c = {
        base: e,
        ui: t,
        view: r,
        utility: n,
        store: s,
        storage: o,
        model: i,
        ajax: u,
        log: l,
        commonStore: a,
        lazyload: f
    };
    return c
}),
define("cMultipleDate", ["libs", "c"],
function(e, t) {
    var n = t.base,
    r = new n.Class({
        __propertys__: function() {
            this.models = new n.Hash,
            this.index = 0,
            this.results = {}
        },
        initialize: function(e) {
            this.setOption(e)
        },
        setOption: function(e) {
            for (var t in e) switch (t) {
            case "models":
                this.addModels(e[t])
            }
        },
        addModel: function(e, t) {
            this.models.add(e, t)
        },
        addModels: function(e) {
            for (var t in e) e.hasOwnProperty(t) && this.models.add(t, e[t])
        },
        removeModelByName: function(e) {
            this.models.del(e)
        },
        removeModelByIndex: function(e) {
            this.models.delByIndex(e)
        },
        excute: function(e, t, n, r, i) {
            if (!this.models.length()) throw "No model";
            this.index = 0,
            this._request(e, t, n, r, i)
        },
        _request: function(e, t, n, r, i) {
            var s = this.models.index(this.index),
            o = this;
            s.excute(function(s) {
                o.results[o.models.getKey(o.index)] = s,
                o.index++;
                if (o.index >= o.models.length()) {
                    e && e.call(this, o.results),
                    o.results = {};
                    return
                }
                o._request(e, t, n, r, i)
            },
            function(e) {
                t && t.call(this, e)
            },
            n, r, i)
        }
    });
    return r
}),
define("cImgLazyload", ["libs", "cBase"],
function(e, t) {
    var n = new t.Class({
        __propertys__: function() {
            this.isError = !1
        },
        initialize: function(e) {
            this.handleOpts(e),
            this.checkWrapperDisplay(),
            this.init()
        },
        checkWrapperDisplay: function() {
            if (this.isError) return;
            this.TIMERRES && clearInterval(this.TIMERRES),
            $(this.imgs[0]).offset().top == 0 && (this.isError = !0, this.TIMERRES = setInterval($.proxy(function() {
                0,
                $(this.imgs[0]).offset().top > 0 && (this.TIMERRES && clearInterval(this.TIMERRES), 0, this.isError = !1, this.refresh())
            },
            this), 100))
        },
        handleOpts: function(e) {
            this.isError = !1;
            if (!e || !e.imgs || !e.imgs.length) {
                this.isError = !0;
                return
            }
            this.imgs = e.imgs,
            this.container = e.container || $(window),
            this.width = e.width,
            this.height = e.height,
            this.loadingImg = e.loadingImg || "http://pic.c-ctrip.com/vacation_v2/h5/group_travel/no_product_pic.png",
            this.loadingBg = e.loadingBg || "#ebebeb",
            this.needWrapper = !1;
            if (this.width || this.height) this.needWrapper = !0;
            this.wrapper = e.wrapper || '<div class="cui_lazyload_wrapper" style="text-align: center; vertical-align: middle; "></div>',
            this.imgContainer = {}
        },
        init: function() {
            if (this.isError) return;
            this.initImgContainer(),
            this.lazyLoad(),
            this.bindEvents()
        },
        refresh: function(e) {
            e && this.handleOpts(e),
            this.init()
        },
        bindEvents: function() {
            if (this.isError) return;
            this.destroy(),
            this.container.on("scroll.imglazyload", $.proxy(function() {
                this.lazyLoad()
            },
            this)),
            $(window).on("resize.imglazyload", $.proxy(function() {
                this.initImgContainer()
            },
            this))
        },
        initImgContainer: function() {
            if (this.isError) return;
            var e, t, n, r;
            for (t = 0, n = this.imgs.length; t < n; t++) {
                e = $(this.imgs[t]);
                if (!e.attr("data-src") || e.attr("data-src") == "" || e.attr("data-load") == "1") continue;
                r = e.offset(),
                this.imgContainer[r.top] || (this.imgContainer[r.top] = []),
                this.imgContainer[r.top].push(e)
            }
        },
        _handleImg: function(e) {
            var t, n, r;
            e.attr("data-src") && e.attr("data-src") != "" && (this.needWrapper && (n = $(this.wrapper), n.css({
                width: this.width + "px",
                height: this.height + "px",
                "background-color": this.loadingBg
            }), n.insertBefore(e), n.append(e)), t = $(new Image), e.attr("src") || e.attr("src", this.loadingImg), t.on("error",
            function() {
                e.attr("src", this.loadingImg)
            }).on("load",
            function() {
                e.attr("src", e.attr("data-src")),
                e.attr("data-load", "1"),
                setTimeout(function() {
                    n && n[0] && (e.insertBefore(n), n.remove())
                },
                1)
            }).attr("src", e.attr("data-src")))
        },
        lazyLoad: function() {
            if (this.isError) return;
            var e = this.container.height(),
            t = this.container.scrollTop(),
            n,
            r,
            i,
            s,
            o;
            for (n in this.imgContainer) if (parseInt(n) < t + e) {
                r = this.imgContainer[n];
                for (s = 0, o = r.length; s < o; s++) i = $(r[s]),
                this._handleImg(i);
                delete this.imgContainer[n]
            }
        },
        destroy: function() {
            if (this.isError) return;
            this.TIMERRES && clearInterval(this.TIMERRES),
            this.container.off(".imglazyload"),
            $(window).off(".imglazyload")
        }
    });
    return n
}),
define("cListAdapter", ["libs", "cBase"],
function(e, t) {
    var n = n || {},
    r = function(e) {
        var t = "",
        n = function() {
            var e = Math.floor(Math.random() * 62);
            return e < 10 ? e: e < 36 ? String.fromCharCode(e + 55) : String.fromCharCode(e + 61)
        };
        while (t.length < e) t += n();
        return t
    },
    i = function(e, t) {
        var n = r(8);
        if (!e.getItem(n)) return e.add(n, t);
        i(e, t)
    },
    s = function(e, t) {
        for (var n = 0; n < t.length; n++) i(e, t[n])
    };
    n.__propertys__ = function() {
        this.observers = []
    },
    n.initialize = function(e) {
        this.setAdapter(e.data)
    },
    n.add = function(e) {
        if (e instanceof Array) for (var t = 0; t < e.length; t++) i(this.map, e[t]),
        this.list.push(e[t]);
        else i(this.map, e),
        this.list.push(e);
        this.notifyDataChanged()
    },
    n.shift = function() {
        return this.map.shift(),
        this.notifyDataChanged(),
        this.list.shift()
    },
    n.pop = function() {
        return this.map.pop(),
        this.notifyDataChanged(),
        this.list.pop()
    },
    n.remove = function(e) {
        for (var t = 0; t < e; t++) this.map.pop(),
        this.list.pop();
        this.notifyDataChanged()
    },
    n.sortBy = function(e) {
        this.map.sortBy(e),
        this.list = _.sortBy(this.list, e),
        this.notifyDataChanged()
    },
    n.setAdapter = function(e) {
        e = e && e instanceof Array ? e: [],
        this.list = $.extend(!0, [], e),
        this.map = new t.Hash,
        s(this.map, e),
        this.notifyDataChanged()
    },
    n.regiseterObserver = function(e) {
        this.observers.indexOf(e) === -1 && this.observers.push(e)
    },
    n.unregiseterObserver = function(e) {
        var t = this.observers.indexOf(e);
        t && this.observers.splice(t, 1)
    },
    n.notifyDataChanged = function() {
        for (var e = 0; e < this.observers.length; e++) this.observers[e].update()
    };
    var o = new t.Class(n);
    return o
}),
define("cWidgetHeaderView", ["cBase", "cUICore", "cWidgetFactory", "cUtility", "cSales", "cHybridFacade"],
function(e, t, n, r, i, s) {
    function c(n) {
        if (c.instance) return c.instance.reset(n),
        c.instance;
        var r = new e.Class(t.AbstractView, f);
        return c.instance = new r(n)
    }
    var o = "HeaderView";
    if (n.hasWidget(o)) return;
    var u = function() {
        var e = this.templateFactory(this.data);
        return $(e)
    },
    a = function(e) {
        this.html = "";
        if (e && e.data) {
            e.data.customtitle && this.htmlMap.title ? delete this.htmlMap.title: this.htmlMap.title = "<h1><%=title %></h1>";
            for (var t in this.htmlMap) e.data[t] && (this.html += this.htmlMap[t]);
            var n = "",
            r = e.data.style;
            r && (n = ' style="' + r + '"'),
            this.html = "<header" + n + ">" + this.html + "</header>"
        }
    },
    f = f || {};
    f.__propertys__ = function() {},
    f.initialize = function($super, e) {
        e ? (this.rootBox = e.container || ($("#headerview").length > 0 ? $("#headerview") : $('<div id="headerview"></div>')), this.data = e.data, this.html = e.html || this.html, this.data && (this.bindEvents = e.data.bindEvents ||
        function() {}), a.call(this, e), e.onShow = this.onShow) : (this.rootBox = $("#headerview").length > 0 ? $("#headerview") : $('<div id="headerview"></div>'), e = {
            onShow: this.onShow
        }),
        $super(e)
    },
    f.createHtml = function() {
        if (this.html) return this.templateFactory = this.template(this.html),
        u.call(this);
        return
    },
    f.onShow = function() {
        this.rootBox.off("click"),
        this.data && this.data.btn && typeof this.bindEvents == "function" && this.bindEvents(this.getView()),
        this.data && this.data.events && this.delegateEvents(this.data.events, this.data.view);
        if (this.data && this.data.commit) {
            var e = this.data.commit,
            t = this.getView(),
            n = this,
            r = function() {
                e.callback.call(n.view)
            };
            t.find("#" + e.id).on("click", r),
            s.register({
                tagname: s.METHOD_COMMIT,
                callback: r
            })
        }
    },
    f.set = function(e) {
        e && (this.htmlMap && !this.htmlMap.title && (this.htmlMap.title = "<h1><%=title %></h1>"), this.data = e, a.call(this, {
            data: e
        }), this.bindEvents = this.data.bindEvents, this.isCreate = !1, this.hide())
    },
    f.reset = function(e) {
        e && (this.set(e), this.trigger("onShow"))
    },
    f.delegateEvents = function(e, t) {
        if (e) {
            l.call(this, "#c-ui-header-home", "click", e.homeHandler, t),
            l.call(this, "#c-ui-header-return", "click", e.returnHandler, t),
            this.rootBox.find("header").append($("#c-ui-header-return"));
            var n = this,
            r = function() {
                e.returnHandler.call(t || n)
            };
            s.register({
                tagname: s.METHOD_BACK,
                callback: r
            });
            if (e.citybtnHandler) {
                var i = function() {
                    e.citybtnHandler.call(t || n)
                };
                s.register({
                    tagname: s.METHOD_CITY_CHOOSE,
                    callback: i
                })
            }
        }
    };
    var l = function(e, t, n, r) {
        this.rootBox.find(e).on(t,
        function() {
            n.call(r || this)
        })
    };
    f.getView = function() {
        return this.rootBox
    },
    f.updateHeader = function(e, t) {
        this.data[e] = t,
        this.set(this.data),
        this.show()
    },
    f.html = null,
    f.htmlMap = {
        home: '<i class="icon_home i_bef" id="c-ui-header-home"></i>',
        tel: '<a href="tel:<%=tel.number||4000086666 %>" class="icon_phone i_bef __hreftel__" id="c-ui-header-tel"></a>',
        customtitle: "<div><%=customtitle %></div>",
        title: "<h1><%=title %></h1>",
        back: '<i id="c-ui-header-return" class="returnico i_bef"></i>',
        btn: '<i id="<%=btn.id%>" class="<%=btn.classname%>"><%=btn.title %></i>',
        custom: "<%=custom %>"
    },
    f.create = function() { ! this.isCreate && this.status !== t.AbstractView.STATE_ONCREATE && (this.rootBox = this.rootBox || $("body"), this.rootBox.empty(), this.root = $(this.createHtml()), this.rootBox.append(this.root), setTimeout($.proxy(function() {
            i.replaceContent(this.root)
        },
        this), 200), r.isInApp() || this.rootBox.css("height", this.root.css("height")), this.trigger("onCreate"), this.status = t.AbstractView.STATE_ONCREATE, this.isCreate = !0)
    },
    f.hideHandler = function($super) {
        r.isInApp() ? this.isHidden || (s.request({
            name: s.METHOD_SET_NAVBAR_HIDDEN,
            isNeedHidden: !0
        }), this.isHidden = !0) : this.hide()
    },
    f.showAction = function(e) {
        r.isInApp() ? (this.rootBox.hide(), this.isHidden && (s.request({
            name: s.METHOD_SET_NAVBAR_HIDDEN,
            isNeedHidden: !1
        }), this.isHidden = !1), this.saveHead()) : this.root.show(),
        e()
    },
    f.saveHead = function() {
        var e = {
            left: [],
            center: [],
            centerButtons: [],
            right: []
        },
        t = this.data;
        t.back && e.left.push({
            tagname: "back"
        }),
        t.title && e.center.push({
            tagname: "title",
            value: t.title
        }),
        t.subtitle && e.center.push({
            tagname: "subtitle",
            value: t.subtitle
        }),
        t.btn && e.right.push({
            tagname: "commit",
            value: t.btn.title
        }),
        t.tel && e.right.push({
            tagname: "call"
        }),
        t.home && e.right.push({
            tagname: "home"
        }),
        t.citybtn && e.centerButtons.push({
            tagname: "cityChoose",
            value: t.citybtn,
            a_icon: "icon_arrowx",
            i_icon: "icon_arrowx.png"
        });
        try {
            var n = JSON.stringify(e);
            s.request({
                name: s.METHOD_REFRESH_NAV_BAR,
                config: n
            })
        } catch(r) {}
    },
    n.register({
        name: o,
        fn: c
    })
}),
define("cBasePageView", ["libs", "c", "cWidgetFactory", "cWidgetHeaderView", "cWidgetGuider"],
function(e, t, n) {
    var r = n.create("Guider"),
    i = i || {};
    i.injectHeaderView = function(e) {
        var t = n.create("HeaderView");
        this.headerview = new t(e),
        $("#main").before(this.headerview.getView())
    },
    i._initializeHeader = function() {},
    i._getDefaultHeader = function() {},
    i.hybridBridgeRender = function() {
        var e = this,
        t = $.proxy(e.showView, e),
        n = $.proxy(e.showView, e);
        r.apply({
            hybridCallback: t,
            callback: n
        })
    },
    i.registerCallback = function(e) {},
    i.callAppInit = function(e) {
        var t = 0;
        if (window.localStorage) {
            var n = window.localStorage.getItem("APPINFO");
            n = JSON.parse(n),
            n && (t = n.version)
        }
        r.init({
            version: t,
            callback: e
        })
    };
    var s = t.view.extend(i);
    return s.ONCLICK = "click",
    s
}),
define("cCommonPageFactory", ["libs"],
function(e) {
    var t = t || {};
    return t.products = {},
    t.hasPage = function(e) {
        return !! t.products[e]
    },
    t.register = function(e) {
        if (! (e && e.name && e.fn)) throw "CommonPageFactory: factory is lack of necessary infomation.";
        if (t.products[e.name]) throw "CommonPageFactory: factory has been register in CommonPageFactory";
        t.products[e.name] = e.fn
    },
    t.create = function(e) {
        return t.products[e]
    },
    t
}),
function() {
    var e = document.location.protocol;
    e != "https:" && (e = "http:");
    var t = e + "//webresource.c-ctrip.com/code/lizard/1.1/web/";
    require.config({
        shim: {
            _: {
                exports: "_"
            },
            B: {
                deps: ["_"],
                exports: "Backbone"
            },
            cBase: {
                exports: "cBase"
            },
            cAjax: {
                exports: "cAjax"
            },
            cView: {
                deps: ["B"],
                exports: "cView"
            }
        },
        paths: {
            text: t + "3rdlibs/require.text",
            AbstractAPP: t + "c.abstract.app",
            App: t + "business/c.business.app",
            rsa: t + "business/c.business.rsa",
            cView: t + "business/c.business.view",
            c: t + "common/c",
            cBase: t + "common/c.base",
            cLog: t + "common/c.log",
            cAjax: t + "common/c.ajax",
            cSales: t + "common/c.sales",
            cLazyload: t + "common/c.lazyload",
            cListAdapter: t + "common/c.common.listadapter",
            cGeoService: t + "common/c.geo.service",
            cImgLazyload: t + "common/c.img.lazyload",
            cUtilityHybrid: t + "util/c.utility.hybrid",
            cUtilityHash: t + "util/c.utility.hash",
            cUtilityDate: t + "util/c.utility.date",
            cUtilityServertime: t + "util/c.utility.servertime",
            cUtilityCrypt: t + "util/c.utility.crypt",
            cUtility: t + "util/c.utility",
            Validate: t + "util/c.validate",
            cCoreInherit: t + "core/c.core.inherit",
            cAbstractStore: t + "store/c.abstract.store",
            cAbstractStorage: t + "store/c.abstract.storage",
            cStore: t + "store/c.local.store",
            cStorage: t + "store/c.local.storage",
            cSessionStore: t + "store/c.session.store",
            cSessionStorage: t + "store/c.session.storage",
            memStore: t + "store/c.memorystore",
            CommonStore: t + "store/c.common.store",
            PageStore: t + "store/c.store.package",
            cAbstractModel: t + "model/c.abstract.model",
            cModel: t + "model/c.model",
            cUserModel: t + "model/c.user.model",
            cMultipleDate: t + "model/c.multiple.data",
            cUI: t + "ui/c.ui",
            cUICore: t + "ui/c.ui.core",
            cHistory: t + "ui/c.ui.history",
            cUIView: t + "ui/c.ui.view",
            cDataSource: t + "ui/c.ui.datasource",
            cUIBase: t + "ui/c.ui.base",
            cUIAbstractView: t + "ui/c.ui.abstract.view",
            cAdView: t + "ui/c.ui.ad",
            cUIAlert: t + "ui/c.ui.alert",
            cUIAnimation: t + "ui/c.ui.animation",
            cUICitylist: t + "ui/c.ui.citylist",
            cUIHeadWarning: t + "ui/c.ui.head.warning",
            cUIInputClear: t + "ui/c.ui.input.clear",
            cUILayer: t + "ui/c.ui.layer",
            cUILoading: t + "ui/c.ui.loading",
            cUILoadingLayer: t + "ui/c.ui.loading.layer",
            cUIMask: t + "ui/c.ui.mask",
            cUIPageview: t + "ui/c.ui.page.view",
            cUIScrollRadio: t + "ui/c.ui.scroll.radio",
            cUIScrollRadioList: t + "ui/c.ui.scroll.radio.list",
            cUIScrollList: t + "ui/c.ui.scrolllist",
            cUIToast: t + "ui/c.ui.toast",
            cUIWarning: t + "ui/c.ui.warning",
            cUIWarning404: t + "ui/c.ui.warning404",
            cUIHashObserve: t + "ui/c.ui.hash.observe",
            cUIEventListener: t + "ui/c.ui.event.listener",
            cUISwitch: t + "ui/c.ui.switch",
            cUIScroll: t + "ui/c.ui.scroll",
            cUINum: t + "ui/c.ui.num",
            cUIGroupList: t + "ui/c.ui.group.list",
            cUIBusinessGroupList: t + "ui/c.ui.business.group.list",
            cUITab: t + "ui/c.ui.tab",
            cUIImageSlider: t + "ui/c.ui.imageSlider",
            cUIBubbleLayer: t + "ui/c.ui.bubble.layer",
            cWidgetFactory: t + "widget/c.widget.factory",
            cWidgetHeaderView: t + "widget/c.widget.headerview",
            cWidgetListView: t + "widget/c.widget.listview",
            cWidgetTipslayer: t + "widget/c.widget.tipslayer",
            cWidgetInputValidator: t + "widget/c.widget.inputValidator",
            cWidgetPublisher: t + "widget/c.widget.publisher",
            cWidgetGeolocation: t + "widget/c.widget.geolocation",
            cWidgetAbstractCalendar: t + "widget/c.widget.abstract.calendar",
            cWidgetCalendar: t + "widget/c.widget.calendar",
            cWidgetCalendarPrice: t + "widget/c.widget.calendar.price",
            cWidgetSlide: t + "widget/c.widget.slide",
            cWidgetMember: t + "widget/c.widget.member",
            cWidgetGuider: t + "widget/c.widget.guider",
            cWidgetCaptcha: t + "widget/c.widget.captcha",
            cCalendar: t + "widget/c.calendar",
            cHolidayCalendar: t + "widget/c.holiday.calendar",
            cHolidayPriceCalendar: t + "widget/c.holiday.price.calendar",
            cBasePageView: t + "page/c.page.base",
            cCommonPageFactory: t + "page/c.page.factory",
            cCommonListPage: t + "page/c.page.common.list",
            cHybridFacade: t + "hybrid/c.hybrid.facade"
        }
    });
    var n = window.localStorage;
    n && n.removeItem("isPreProduction")
} (),
define("common_r",
function() {});