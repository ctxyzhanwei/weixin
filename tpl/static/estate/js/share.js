if (!window.jq || typeof (jq) !== "function") {
    var jq = (function (window) {
        var undefined, document = window.document, emptyArray = [], slice = emptyArray.slice, classCache = {}, eventHandlers = [], _eventID = 1, jsonPHandlers = [], _jsonPID = 1, fragementRE = /^\s*<(\w+)[^>]*>/, _attrCache = {}, _propCache = {}; function _insertFragments(jqm, container, insert) {
            var frag = document.createDocumentFragment(); if (insert) {
                for (var j = jqm.length - 1; j >= 0; j--)
                { frag.insertBefore(jqm[j], frag.firstChild); }
                container.insertBefore(frag, container.firstChild);
            }
            else {
                for (var j = 0; j < jqm.length; j++)
                    frag.appendChild(jqm[j]); container.appendChild(frag);
            }
            frag = null;
        }
        function classRE(name) { return name in classCache ? classCache[name] : (classCache[name] = new RegExp('(^|\\s)' + name + '(\\s|$)')); }
        function unique(arr) {
            for (var i = 0; i < arr.length; i++) { if (arr.indexOf(arr[i]) != i) { arr.splice(i, 1); i--; } }
            return arr;
        }
        function siblings(nodes, element) {
            var elems = []; if (nodes == undefined)
                return elems; for (; nodes; nodes = nodes.nextSibling) { if (nodes.nodeType == 1 && nodes !== element) { elems.push(nodes); } }
            return elems;
        }
        var $jqm = function (toSelect, what) {
            this.length = 0; if (!toSelect) { return this; } else if (toSelect instanceof $jqm && what == undefined) { return toSelect; } else if ($.isFunction(toSelect)) { return $(document).ready(toSelect); } else if ($.isArray(toSelect) && toSelect.length != undefined) {
                for (var i = 0; i < toSelect.length; i++)
                    this[this.length++] = toSelect[i]; return this;
            } else if ($.isObject(toSelect) && $.isObject(what)) {
                if (toSelect.length == undefined) {
                    if (toSelect.parentNode == what)
                        this[this.length++] = toSelect;
                } else {
                    for (var i = 0; i < toSelect.length; i++)
                        if (toSelect[i].parentNode == what)
                            this[this.length++] = toSelect[i];
                }
                return this;
            } else if ($.isObject(toSelect) && what == undefined) { this[this.length++] = toSelect; return this; } else if (what !== undefined) { if (what instanceof $jqm) { return what.find(toSelect); } } else { what = document; }
            return this.selector(toSelect, what);
        }; var $ = function (selector, what) { return new $jqm(selector, what); }; function _selectorAll(selector, what) { try { return what.querySelectorAll(selector); } catch (e) { return []; } }; function _selector(selector, what) {
            selector = selector.trim(); if (selector[0] === "#" && selector.indexOf(".") == -1 && selector.indexOf(" ") === -1 && selector.indexOf(">") === -1) {
                if (what == document)
                    _shimNodes(what.getElementById(selector.replace("#", "")), this); else
                    _shimNodes(_selectorAll(selector, what), this);
            } else if (selector[0] === "<" && selector[selector.length - 1] === ">")
            { var tmp = document.createElement("div"); tmp.innerHTML = selector.trim(); _shimNodes(tmp.childNodes, this); } else { _shimNodes((_selectorAll(selector, what)), this); }
            return this;
        }
        function _shimNodes(nodes, obj) {
            if (!nodes)
                return; if (nodes.nodeType)
                    return obj[obj.length++] = nodes; for (var i = 0, iz = nodes.length; i < iz; i++)
                        obj[obj.length++] = nodes[i];
        }
        $.is$ = function (obj) { return obj instanceof $jqm; }
        $.map = function (elements, callback) {
            var value, values = [], i, key; if ($.isArray(elements))
                for (i = 0; i < elements.length; i++) {
                    value = callback(elements[i], i); if (value !== undefined)
                        values.push(value);
                }
            else if ($.isObject(elements))
                for (key in elements) {
                    if (!elements.hasOwnProperty(key))
                        continue; value = callback(elements[key], key); if (value !== undefined)
                            values.push(value);
                }
            return $([values]);
        }; $.each = function (elements, callback) {
            var i, key; if ($.isArray(elements))
                for (i = 0; i < elements.length; i++) {
                    if (callback(i, elements[i]) === false)
                        return elements;
                }
            else if ($.isObject(elements))
                for (key in elements) {
                    if (!elements.hasOwnProperty(key))
                        continue; if (callback(key, elements[key]) === false)
                            return elements;
                }
            return elements;
        }; $.extend = function (target) {
            if (target == undefined)
                target = this; if (arguments.length === 1) {
                    for (var key in target)
                        this[key] = target[key]; return this;
                } else {
                    slice.call(arguments, 1).forEach(function (source) {
                        for (var key in source)
                            target[key] = source[key];
                    });
                }
            return target;
        }; $.isArray = function (obj) { return obj instanceof Array && obj['push'] != undefined; }; $.isFunction = function (obj) { return typeof obj === "function" && !(obj instanceof RegExp); }; $.isObject = function (obj) { return typeof obj === "object"; }; $.fn = $jqm.prototype = {
            constructor: $jqm, forEach: emptyArray.forEach, reduce: emptyArray.reduce, push: emptyArray.push, indexOf: emptyArray.indexOf, concat: emptyArray.concat, selector: _selector, oldElement: undefined, slice: emptyArray.slice, setupOld: function (params) {
                if (params == undefined)
                    return $(); params.oldElement = this; return params;
            }, map: function (fn) {
                var value, values = [], i; for (i = 0; i < this.length; i++) {
                    value = fn(i, this[i]); if (value !== undefined)
                        values.push(value);
                }
                return $([values]);
            }, each: function (callback) { this.forEach(function (el, idx) { callback.call(el, idx, el); }); return this; }, ready: function (callback) {
                if (document.readyState === "complete" || document.readyState === "loaded" || (!$.os.ie && document.readyState === "interactive"))
                    callback(); else
                    document.addEventListener("DOMContentLoaded", callback, false); return this;
            }, find: function (sel) {
                if (this.length === 0)
                    return this; var elems = []; var tmpElems; for (var i = 0; i < this.length; i++) { tmpElems = ($(sel, this[i])); for (var j = 0; j < tmpElems.length; j++) { elems.push(tmpElems[j]); } }
                return $(unique(elems));
            }, html: function (html, cleanup) {
                if (this.length === 0)
                    return this; if (html === undefined)
                        return this[0].innerHTML; for (var i = 0; i < this.length; i++) {
                            if (cleanup !== false)
                                $.cleanUpContent(this[i], false, true); this[i].innerHTML = html;
                        }
                return this;
            }, text: function (text) {
                if (this.length === 0)
                    return this; if (text === undefined)
                        return this[0].textContent; for (var i = 0; i < this.length; i++) { this[i].textContent = text; }
                return this;
            }, css: function (attribute, value, obj) {
                var toAct = obj != undefined ? obj : this[0]; if (this.length === 0)
                    return this; if (value == undefined && typeof (attribute) === "string") { var styles = window.getComputedStyle(toAct); return toAct.style[attribute] ? toAct.style[attribute] : window.getComputedStyle(toAct)[attribute]; }
                for (var i = 0; i < this.length; i++) { if ($.isObject(attribute)) { for (var j in attribute) { this[i].style[j] = attribute[j]; } } else { this[i].style[attribute] = value; } }
                return this;
            }, vendorCss: function (attribute, value, obj) { return this.css($.feat.cssPrefix + attribute, value, obj); }, empty: function () {
                for (var i = 0; i < this.length; i++) { $.cleanUpContent(this[i], false, true); this[i].innerHTML = ''; }
                return this;
            }, hide: function () {
                if (this.length === 0)
                    return this; for (var i = 0; i < this.length; i++) { if (this.css("display", null, this[i]) != "none") { this[i].setAttribute("jqmOldStyle", this.css("display", null, this[i])); this[i].style.display = "none"; } }
                return this;
            }, show: function () {
                if (this.length === 0)
                    return this; for (var i = 0; i < this.length; i++) { if (this.css("display", null, this[i]) == "none") { this[i].style.display = this[i].getAttribute("jqmOldStyle") ? this[i].getAttribute("jqmOldStyle") : 'block'; this[i].removeAttribute("jqmOldStyle"); } }
                return this;
            }, toggle: function (show) {
                var show2 = show === true ? true : false; for (var i = 0; i < this.length; i++) {
                    if (window.getComputedStyle(this[i])['display'] !== "none" || (show !== undefined && show2 === false)) {
                        this[i].setAttribute("jqmOldStyle", this[i].style.display)
                        this[i].style.display = "none";
                    } else { this[i].style.display = this[i].getAttribute("jqmOldStyle") != undefined ? this[i].getAttribute("jqmOldStyle") : 'block'; this[i].removeAttribute("jqmOldStyle"); }
                }
                return this;
            }, val: function (value) {
                if (this.length === 0)
                    return (value === undefined) ? undefined : this; if (value == undefined)
                        return this[0].value; for (var i = 0; i < this.length; i++) { this[i].value = value; }
                return this;
            }, attr: function (attr, value) {
                if (this.length === 0)
                    return (value === undefined) ? undefined : this; if (value === undefined && !$.isObject(attr)) { var val = (this[0].jqmCacheId && _attrCache[this[0].jqmCacheId][attr]) ? (this[0].jqmCacheId && _attrCache[this[0].jqmCacheId][attr]) : this[0].getAttribute(attr); return val; }
                for (var i = 0; i < this.length; i++) {
                    if ($.isObject(attr)) { for (var key in attr) { $(this[i]).attr(key, attr[key]); } }
                    else if ($.isArray(value) || $.isObject(value) || $.isFunction(value)) {
                        if (!this[i].jqmCacheId)
                            this[i].jqmCacheId = $.uuid(); if (!_attrCache[this[i].jqmCacheId])
                                _attrCache[this[i].jqmCacheId] = {}
                        _attrCache[this[i].jqmCacheId][attr] = value;
                    }
                    else if (value == null && value !== undefined) {
                        this[i].removeAttribute(attr); if (this[i].jqmCacheId && _attrCache[this[i].jqmCacheId][attr])
                            delete _attrCache[this[i].jqmCacheId][attr];
                    }
                    else { this[i].setAttribute(attr, value); }
                }
                return this;
            }, removeAttr: function (attr) {
                var that = this; for (var i = 0; i < this.length; i++) {
                    attr.split(/\s+/g).forEach(function (param) {
                        that[i].removeAttribute(param); if (that[i].jqmCacheId && _attrCache[that[i].jqmCacheId][attr])
                            delete _attrCache[that[i].jqmCacheId][attr];
                    });
                }
                return this;
            }, prop: function (prop, value) {
                if (this.length === 0)
                    return (value === undefined) ? undefined : this; if (value === undefined && !$.isObject(prop)) { var res; var val = (this[0].jqmCacheId && _propCache[this[0].jqmCacheId][prop]) ? (this[0].jqmCacheId && _propCache[this[0].jqmCacheId][prop]) : !(res = this[0][prop]) && prop in this[0] ? this[0][prop] : res; return val; }
                for (var i = 0; i < this.length; i++) {
                    if ($.isObject(prop)) { for (var key in prop) { $(this[i]).prop(key, prop[key]); } }
                    else if ($.isArray(value) || $.isObject(value) || $.isFunction(value)) {
                        if (!this[i].jqmCacheId)
                            this[i].jqmCacheId = $.uuid(); if (!_propCache[this[i].jqmCacheId])
                                _propCache[this[i].jqmCacheId] = {}
                        _propCache[this[i].jqmCacheId][prop] = value;
                    }
                    else if (value == null && value !== undefined)
                    { $(this[i]).removeProp(prop); }
                    else { this[i][prop] = value; }
                }
                return this;
            }, removeProp: function (prop) {
                var that = this; for (var i = 0; i < this.length; i++) {
                    prop.split(/\s+/g).forEach(function (param) {
                        if (that[i][param])
                            delete that[i][param]; if (that[i].jqmCacheId && _propCache[that[i].jqmCacheId][prop]) { delete _propCache[that[i].jqmCacheId][prop]; }
                    });
                }
                return this;
            }, remove: function (selector) {
                var elems = $(this).filter(selector); if (elems == undefined)
                    return this; for (var i = 0; i < elems.length; i++) { $.cleanUpContent(elems[i], true, true); elems[i].parentNode.removeChild(elems[i]); }
                return this;
            }, addClass: function (name) {
                for (var i = 0; i < this.length; i++) {
                    var cls = this[i].className; var classList = []; var that = this; name.split(/\s+/g).forEach(function (cname) {
                        if (!that.hasClass(cname, that[i]))
                            classList.push(cname);
                    }); this[i].className += (cls ? " " : "") + classList.join(" "); this[i].className = this[i].className.trim();
                }
                return this;
            }, removeClass: function (name) {
                for (var i = 0; i < this.length; i++) {
                    if (name == undefined) { this[i].className = ''; return this; }
                    var classList = this[i].className; name.split(/\s+/g).forEach(function (cname) { classList = classList.replace(classRE(cname), " "); }); if (classList.length > 0)
                        this[i].className = classList.trim(); else
                        this[i].className = "";
                }
                return this;
            }, replaceClass: function (name, newName) {
                for (var i = 0; i < this.length; i++) {
                    if (name == undefined) { this[i].className = newName; continue; }
                    var classList = this[i].className; name.split(/\s+/g).concat(newName.split(/\s+/g)).forEach(function (cname) { classList = classList.replace(classRE(cname), " "); }); classList = classList.trim(); if (classList.length > 0) { this[i].className = (classList + " " + newName).trim(); } else
                        this[i].className = newName;
                }
                return this;
            }, hasClass: function (name, element) {
                if (this.length === 0)
                    return false; if (!element)
                        element = this[0]; return classRE(name).test(element.className);
            }, append: function (element, insert) {
                if (element && element.length != undefined && element.length === 0)
                    return this; if ($.isArray(element) || $.isObject(element))
                        element = $(element); var i; for (i = 0; i < this.length; i++) {
                            if (element.length && typeof element != "string") { element = $(element); _insertFragments(element, this[i], insert); } else {
                                var obj = fragementRE.test(element) ? $(element) : undefined; if (obj == undefined || obj.length == 0) { obj = document.createTextNode(element); }
                                if (obj.nodeName != undefined && obj.nodeName.toLowerCase() == "script" && (!obj.type || obj.type.toLowerCase() === 'text/javascript')) { window.eval(obj.innerHTML); } else if (obj instanceof $jqm) { _insertFragments(obj, this[i], insert); }
                                else { insert != undefined ? this[i].insertBefore(obj, this[i].firstChild) : this[i].appendChild(obj); }
                            }
                        }
                return this;
            }, appendTo: function (selector, insert) { var tmp = $(selector); tmp.append(this); return this; }, prependTo: function (selector) { var tmp = $(selector); tmp.append(this, true); return this; }, prepend: function (element) { return this.append(element, 1); }, insertBefore: function (target, after) {
                if (this.length == 0)
                    return this; target = $(target).get(0); if (!target)
                        return this; for (var i = 0; i < this.length; i++)
                { after ? target.parentNode.insertBefore(this[i], target.nextSibling) : target.parentNode.insertBefore(this[i], target); }
                return this;
            }, insertAfter: function (target) { this.insertBefore(target, true); }, get: function (index) {
                index = index == undefined ? 0 : index; if (index < 0)
                    index += this.length; return (this[index]) ? this[index] : undefined;
            }, offset: function () {
                if (this.length === 0)
                    return this; if (this[0] == window)
                        return { left: 0, top: 0, right: 0, bottom: 0, width: window.innerWidth, height: window.innerHeight }
                    else
                        var obj = this[0].getBoundingClientRect(); return { left: obj.left + window.pageXOffset, top: obj.top + window.pageYOffset, right: obj.right + window.pageXOffset, bottom: obj.bottom + window.pageYOffset, width: obj.right - obj.left, height: obj.bottom - obj.top };
            }, height: function (val) {
                if (this.length === 0)
                    return this; if (val != undefined)
                        return this.css("height", val); if (this[0] == this[0].window)
                            return window.innerHeight; if (this[0].nodeType == this[0].DOCUMENT_NODE)
                                return this[0].documentElement['offsetheight']; else {
                                var tmpVal = this.css("height").replace("px", ""); if (tmpVal)
                                    return tmpVal
                                else
                                    return this.offset().height;
                            }
            }, width: function (val) {
                if (this.length === 0)
                    return this; if (val != undefined)
                        return this.css("width", val); if (this[0] == this[0].window)
                            return window.innerWidth; if (this[0].nodeType == this[0].DOCUMENT_NODE)
                                return this[0].documentElement['offsetwidth']; else {
                                var tmpVal = this.css("width").replace("px", ""); if (tmpVal)
                                    return tmpVal
                                else
                                    return this.offset().width;
                            }
            }, parent: function (selector, recursive) {
                if (this.length == 0)
                    return this; var elems = []; for (var i = 0; i < this.length; i++) {
                        var tmp = this[i]; while (tmp.parentNode && tmp.parentNode != document) {
                            elems.push(tmp.parentNode); if (tmp.parentNode)
                                tmp = tmp.parentNode; if (!recursive)
                                    break;
                        }
                    }
                return this.setupOld($(unique(elems)).filter(selector));
            }, parents: function (selector) { return this.parent(selector, true); }, children: function (selector) {
                if (this.length == 0)
                    return this; var elems = []; for (var i = 0; i < this.length; i++) { elems = elems.concat(siblings(this[i].firstChild)); }
                return this.setupOld($((elems)).filter(selector));
            }, siblings: function (selector) {
                if (this.length == 0)
                    return this; var elems = []; for (var i = 0; i < this.length; i++) {
                        if (this[i].parentNode)
                            elems = elems.concat(siblings(this[i].parentNode.firstChild, this[i]));
                    }
                return this.setupOld($(elems).filter(selector));
            }, closest: function (selector, context) {
                if (this.length == 0)
                    return this; var elems = [], cur = this[0]; var start = $(selector, context); if (start.length == 0)
                        return $(); while (cur && start.indexOf(cur) == -1) { cur = cur !== context && cur !== document && cur.parentNode; }
                return $(cur);
            }, filter: function (selector) {
                if (this.length == 0)
                    return this; if (selector == undefined)
                        return this; var elems = []; for (var i = 0; i < this.length; i++) {
                            var val = this[i]; if (val.parentNode && $(selector, val.parentNode).indexOf(val) >= 0)
                                elems.push(val);
                        }
                return this.setupOld($(unique(elems)));
            }, not: function (selector) {
                if (this.length == 0)
                    return this; var elems = []; for (var i = 0; i < this.length; i++) {
                        var val = this[i]; if (val.parentNode && $(selector, val.parentNode).indexOf(val) == -1)
                            elems.push(val);
                    }
                return this.setupOld($(unique(elems)));
            }, data: function (key, value) { return this.attr('data-' + key, value); }, end: function () { return this.oldElement != undefined ? this.oldElement : $(); }, clone: function (deep) {
                deep = deep === false ? false : true; if (this.length == 0)
                    return this; var elems = []; for (var i = 0; i < this.length; i++) { elems.push(this[i].cloneNode(deep)); }
                return $(elems);
            }, size: function () { return this.length; }, serialize: function () {
                if (this.length == 0)
                    return ""; var params = []; for (var i = 0; i < this.length; i++) {
                        this.slice.call(this[i].elements).forEach(function (elem) {
                            var type = elem.getAttribute("type"); if (elem.nodeName.toLowerCase() != "fieldset" && !elem.disabled && type != "submit" && type != "reset" && type != "button" && ((type != "radio" && type != "checkbox") || elem.checked)) {
                                if (elem.getAttribute("name")) {
                                    if (elem.type == "select-multiple") {
                                        for (var j = 0; j < elem.options.length; j++) {
                                            if (elem.options[j].selected)
                                                params.push(elem.getAttribute("name") + "=" + encodeURIComponent(elem.options[j].value))
                                        }
                                    }
                                    else
                                        params.push(elem.getAttribute("name") + "=" + encodeURIComponent(elem.value))
                                }
                            }
                        });
                    }
                return params.join("&");
            }, eq: function (ind) { return $(this.get(ind)); }, index: function (elem) { return elem ? this.indexOf($(elem)[0]) : this.parent().children().indexOf(this[0]); }, is: function (selector) { return !!selector && this.filter(selector).length > 0; }
        }; function empty() { }
        var ajaxSettings = { type: 'GET', beforeSend: empty, success: empty, error: empty, complete: empty, context: undefined, timeout: 0, crossDomain: null }; $.jsonP = function (options) {
            var callbackName = 'jsonp_callback' + (++_jsonPID); var abortTimeout = "", context; var script = document.createElement("script"); var abort = function () {
                $(script).remove(); if (window[callbackName])
                    window[callbackName] = empty;
            }; window[callbackName] = function (data) { clearTimeout(abortTimeout); $(script).remove(); delete window[callbackName]; options.success.call(context, data); }; script.src = options.url.replace(/=\?/, '=' + callbackName); if (options.error)
            { script.onerror = function () { clearTimeout(abortTimeout); options.error.call(context, "", 'error'); } }
            $('head').append(script); if (options.timeout > 0)
                abortTimeout = setTimeout(function () { options.error.call(context, "", 'timeout'); }, options.timeout); return {};
        }; $.ajax = function (opts) {
            var xhr; try {
                var settings = opts || {}; for (var key in ajaxSettings) {
                    if (typeof (settings[key]) == 'undefined')
                        settings[key] = ajaxSettings[key];
                }
                if (!settings.url)
                    settings.url = window.location; if (!settings.contentType)
                        settings.contentType = "application/x-www-form-urlencoded"; if (!settings.headers)
                            settings.headers = {}; if (!('async' in settings) || settings.async !== false)
                                settings.async = true; if (!settings.dataType)
                                    settings.dataType = "text/html"; else { switch (settings.dataType) { case "script": settings.dataType = 'text/javascript, application/javascript'; break; case "json": settings.dataType = 'application/json'; break; case "xml": settings.dataType = 'application/xml, text/xml'; break; case "html": settings.dataType = 'text/html'; break; case "text": settings.dataType = 'text/plain'; break; default: settings.dataType = "text/html"; break; case "jsonp": return $.jsonP(opts); break; } }
                if ($.isObject(settings.data))
                    settings.data = $.param(settings.data); if (settings.type.toLowerCase() === "get" && settings.data) {
                        if (settings.url.indexOf("?") === -1)
                            settings.url += "?" + settings.data; else
                            settings.url += "&" + settings.data;
                    }
                if (/=\?/.test(settings.url)) { return $.jsonP(settings); }
                if (settings.crossDomain === null) settings.crossDomain = /^([\w-]+:)?\/\/([^\/]+)/.test(settings.url) && RegExp.$2 != window.location.host; if (!settings.crossDomain)
                    settings.headers = $.extend({ 'X-Requested-With': 'XMLHttpRequest' }, settings.headers); var abortTimeout; var context = settings.context; var protocol = /^([\w-]+:)\/\//.test(settings.url) ? RegExp.$1 : window.location.protocol; xhr = new window.XMLHttpRequest(); xhr.onreadystatechange = function () {
                        var mime = settings.dataType; if (xhr.readyState === 4) {
                            clearTimeout(abortTimeout); var result, error = false; if ((xhr.status >= 200 && xhr.status < 300) || xhr.status === 0 && protocol == 'file:') {
                                if (mime === 'application/json' && !(/^\s*$/.test(xhr.responseText))) { try { result = JSON.parse(xhr.responseText); } catch (e) { error = e; } } else if (mime === 'application/xml, text/xml') { result = xhr.responseXML; }
                                else if (mime == "text/html") { result = xhr.responseText; $.parseJS(result); }
                                else
                                    result = xhr.responseText; if (xhr.status === 0 && result.length === 0)
                                        error = true; if (error)
                                            settings.error.call(context, xhr, 'parsererror', error); else { settings.success.call(context, result, 'success', xhr); }
                            } else { error = true; settings.error.call(context, xhr, 'error'); }
                            settings.complete.call(context, xhr, error ? 'error' : 'success');
                        }
                    }; xhr.open(settings.type, settings.url, settings.async); if (settings.withCredentials) xhr.withCredentials = true; if (settings.contentType)
                        settings.headers['Content-Type'] = settings.contentType; for (var name in settings.headers)
                            xhr.setRequestHeader(name, settings.headers[name]); if (settings.beforeSend.call(context, xhr, settings) === false) { xhr.abort(); return false; }
                if (settings.timeout > 0)
                    abortTimeout = setTimeout(function () { xhr.onreadystatechange = empty; xhr.abort(); settings.error.call(context, xhr, 'timeout'); }, settings.timeout); xhr.send(settings.data);
            } catch (e) { console.log(e); settings.error.call(context, xhr, 'error', e); }
            return xhr;
        }; $.get = function (url, success) { return this.ajax({ url: url, success: success }); }; $.post = function (url, data, success, dataType) {
            if (typeof (data) === "function") { success = data; data = {}; }
            if (dataType === undefined)
                dataType = "html"; return this.ajax({ url: url, type: "POST", data: data, dataType: dataType, success: success });
        }; $.getJSON = function (url, data, success) {
            if (typeof (data) === "function") { success = data; data = {}; }
            return this.ajax({ url: url, data: data, success: success, dataType: "json" });
        }; $.param = function (obj, prefix) {
            var str = []; if (obj instanceof $jqm) { obj.each(function () { var k = prefix ? prefix + "[]" : this.id, v = this.value; str.push((k) + "=" + encodeURIComponent(v)); }); } else { for (var p in obj) { var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p]; str.push($.isObject(v) ? $.param(v, k) : (k) + "=" + encodeURIComponent(v)); } }
            return str.join("&");
        }; $.parseJSON = function (string) { return JSON.parse(string); }; $.parseXML = function (string) { return (new DOMParser).parseFromString(string, "text/xml"); }; function detectUA($, userAgent) {
            $.os = {}; $.os.webkit = userAgent.match(/WebKit\/([\d.]+)/) ? true : false; $.os.android = userAgent.match(/(Android)\s+([\d.]+)/) || userAgent.match(/Silk-Accelerated/) ? true : false; $.os.androidICS = $.os.android && userAgent.match(/(Android)\s4/) ? true : false; $.os.ipad = userAgent.match(/(iPad).*OS\s([\d_]+)/) ? true : false; $.os.iphone = !$.os.ipad && userAgent.match(/(iPhone\sOS)\s([\d_]+)/) ? true : false; $.os.webos = userAgent.match(/(webOS|hpwOS)[\s\/]([\d.]+)/) ? true : false; $.os.touchpad = $.os.webos && userAgent.match(/TouchPad/) ? true : false; $.os.ios = $.os.ipad || $.os.iphone; $.os.playbook = userAgent.match(/PlayBook/) ? true : false; $.os.blackberry = $.os.playbook || userAgent.match(/BlackBerry/) ? true : false; $.os.blackberry10 = $.os.blackberry && userAgent.match(/Safari\/536/) ? true : false; $.os.chrome = userAgent.match(/Chrome/) ? true : false; $.os.opera = userAgent.match(/Opera/) ? true : false; $.os.fennec = userAgent.match(/fennec/i) ? true : userAgent.match(/Firefox/) ? true : false; $.os.ie = userAgent.match(/MSIE 10.0/i) ? true : false; $.os.ieTouch = $.os.ie && userAgent.toLowerCase().match(/touch/i) ? true : false; $.os.supportsTouch = ((window.DocumentTouch && document instanceof window.DocumentTouch) || 'ontouchstart' in window); $.feat = {}; var head = document.documentElement.getElementsByTagName("head")[0]; $.feat.nativeTouchScroll = typeof (head.style["-webkit-overflow-scrolling"]) !== "undefined" && $.os.ios; $.feat.cssPrefix = $.os.webkit ? "Webkit" : $.os.fennec ? "Moz" : $.os.ie ? "ms" : $.os.opera ? "O" : ""; $.feat.cssTransformStart = !$.os.opera ? "3d(" : "("; $.feat.cssTransformEnd = !$.os.opera ? ",0)" : ")"; if ($.os.android && !$.os.webkit)
                $.os.android = false;
        }
        detectUA($, navigator.userAgent); $.__detectUA = detectUA; if (typeof String.prototype.trim !== 'function') { String.prototype.trim = function () { this.replace(/(\r\n|\n|\r)/gm, "").replace(/^\s+|\s+$/, ''); return this }; }
        $.uuid = function () {
            var S4 = function () { return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1); }
            return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
        }; $.getCssMatrix = function (ele) {
            if (ele == undefined) return window.WebKitCSSMatrix || window.MSCSSMatrix || { a: 0, b: 0, c: 0, d: 0, e: 0, f: 0 }; try {
                if (window.WebKitCSSMatrix)
                    return new WebKitCSSMatrix(window.getComputedStyle(ele).webkitTransform)
                else if (window.MSCSSMatrix)
                    return new MSCSSMatrix(window.getComputedStyle(ele).transform); else { var mat = window.getComputedStyle(ele)[$.feat.cssPrefix + 'Transform'].replace(/[^0-9\-.,]/g, '').split(','); return { a: +mat[0], b: +mat[1], c: +mat[2], d: +mat[3], e: +mat[4], f: +mat[5] }; }
            }
            catch (e) { return { a: 0, b: 0, c: 0, d: 0, e: 0, f: 0 }; }
        }
        var handlers = {}, _jqmid = 1; function jqmid(element) { return element._jqmid || (element._jqmid = _jqmid++); }
        function findHandlers(element, event, fn, selector) {
            event = parse(event); if (event.ns)
                var matcher = matcherFor(event.ns); return (handlers[jqmid(element)] || []).filter(function (handler) { return handler && (!event.e || handler.e == event.e) && (!event.ns || matcher.test(handler.ns)) && (!fn || handler.fn == fn || (typeof handler.fn === 'function' && typeof fn === 'function' && "" + handler.fn === "" + fn)) && (!selector || handler.sel == selector); });
        }
        function parse(event) { var parts = ('' + event).split('.'); return { e: parts[0], ns: parts.slice(1).sort().join(' ') }; }
        function matcherFor(ns) { return new RegExp('(?:^| )' + ns.replace(' ', ' .* ?') + '(?: |$)'); }
        function eachEvent(events, fn, iterator) {
            if ($.isObject(events))
                $.each(events, iterator); else
                events.split(/\s/).forEach(function (type) { iterator(type, fn) });
        }
        function add(element, events, fn, selector, getDelegate) {
            var id = jqmid(element), set = (handlers[id] || (handlers[id] = [])); eachEvent(events, fn, function (event, fn) {
                var delegate = getDelegate && getDelegate(fn, event), callback = delegate || fn; var proxyfn = function (event) {
                    var result = callback.apply(element, [event].concat(event.data)); if (result === false)
                        event.preventDefault(); return result;
                }; var handler = $.extend(parse(event), { fn: fn, proxy: proxyfn, sel: selector, del: delegate, i: set.length }); set.push(handler); element.addEventListener(handler.e, proxyfn, false);
            });
        }
        function remove(element, events, fn, selector) { var id = jqmid(element); eachEvent(events || '', fn, function (event, fn) { findHandlers(element, event, fn, selector).forEach(function (handler) { delete handlers[id][handler.i]; element.removeEventListener(handler.e, handler.proxy, false); }); }); }
        $.event = { add: add, remove: remove }
        $.fn.bind = function (event, callback) {
            for (var i = 0; i < this.length; i++) { add(this[i], event, callback); }
            return this;
        }; $.fn.unbind = function (event, callback) {
            for (var i = 0; i < this.length; i++) { remove(this[i], event, callback); }
            return this;
        }; $.fn.one = function (event, callback) { return this.each(function (i, element) { add(this, event, callback, null, function (fn, type) { return function () { var result = fn.apply(element, arguments); remove(element, type, fn); return result; } }); }); }; var returnTrue = function () { return true }, returnFalse = function () { return false }, eventMethods = { preventDefault: 'isDefaultPrevented', stopImmediatePropagation: 'isImmediatePropagationStopped', stopPropagation: 'isPropagationStopped' }; function createProxy(event) {
            var proxy = $.extend({ originalEvent: event }, event); $.each(eventMethods, function (name, predicate) {
                proxy[name] = function () {
                    this[predicate] = returnTrue; if (name == "stopImmediatePropagation" || name == "stopPropagation") {
                        event.cancelBubble = true; if (!event[name])
                            return;
                    }
                    return event[name].apply(event, arguments);
                }; proxy[predicate] = returnFalse;
            })
            return proxy;
        }
        $.fn.delegate = function (selector, event, callback) {
            for (var i = 0; i < this.length; i++) { var element = this[i]; add(element, event, callback, selector, function (fn) { return function (e) { var evt, match = $(e.target).closest(selector, element).get(0); if (match) { evt = $.extend(createProxy(e), { currentTarget: match, liveFired: element }); return fn.apply(match, [evt].concat([].slice.call(arguments, 1))); } } }); }
            return this;
        }; $.fn.undelegate = function (selector, event, callback) {
            for (var i = 0; i < this.length; i++) { remove(this[i], event, callback, selector); }
            return this;
        }
        $.fn.on = function (event, selector, callback) { return selector === undefined || $.isFunction(selector) ? this.bind(event, selector) : this.delegate(selector, event, callback); }; $.fn.off = function (event, selector, callback) { return selector === undefined || $.isFunction(selector) ? this.unbind(event, selector) : this.undelegate(selector, event, callback); }; $.fn.trigger = function (event, data, props) {
            if (typeof event == 'string')
                event = $.Event(event, props); event.data = data; for (var i = 0; i < this.length; i++) { this[i].dispatchEvent(event) }
            return this;
        }; $.Event = function (type, props) {
            var event = document.createEvent('Events'), bubbles = true; if (props)
                for (var name in props)
                    (name == 'bubbles') ? (bubbles = !!props[name]) : (event[name] = props[name]); event.initEvent(type, bubbles, true, null, null, null, null, null, null, null, null, null, null, null, null); return event;
        }; $.bind = function (obj, ev, f) { if (!obj.__events) obj.__events = {}; if (!$.isArray(ev)) ev = [ev]; for (var i = 0; i < ev.length; i++) { if (!obj.__events[ev[i]]) obj.__events[ev[i]] = []; obj.__events[ev[i]].push(f); } }; $.trigger = function (obj, ev, args) {
            var ret = true; if (!obj.__events) return ret; if (!$.isArray(ev)) ev = [ev]; if (!$.isArray(args)) args = []; for (var i = 0; i < ev.length; i++) {
                if (obj.__events[ev[i]]) {
                    var evts = obj.__events[ev[i]]; for (var j = 0; j < evts.length; j++)
                        if ($.isFunction(evts[j]) && evts[j].apply(obj, args) === false)
                            ret = false;
                }
            }
            return ret;
        }; $.unbind = function (obj, ev, f) {
            if (!obj.__events) return; if (!$.isArray(ev)) ev = [ev]; for (var i = 0; i < ev.length; i++) {
                if (obj.__events[ev[i]]) {
                    var evts = obj.__events[ev[i]]; for (var j = 0; j < evts.length; j++) {
                        if (f == undefined)
                            delete evts[j]; if (evts[j] == f) { evts.splice(j, 1); break; }
                    }
                }
            }
        }; $.proxy = function (f, c, args) { return function () { if (args) return f.apply(c, args); return f.apply(c, arguments); } }
        function cleanUpNode(node, kill) {
            if (kill && node.dispatchEvent) { var e = $.Event('destroy', { bubbles: false }); node.dispatchEvent(e); }
            var id = jqmid(node); if (id && handlers[id]) {
                for (var key in handlers[id])
                    node.removeEventListener(handlers[id][key].e, handlers[id][key].proxy, false); delete handlers[id];
            }
        }
        function cleanUpContent(node, kill) {
            if (!node) return; var children = node.childNodes; if (children && children.length > 0)
                for (var child in children)
                    cleanUpContent(children[child], kill); cleanUpNode(node, kill);
        }
        var cleanUpAsap = function (els, kill) { for (var i = 0; i < els.length; i++) { cleanUpContent(els[i], kill); } }
        $.cleanUpContent = function (node, itself, kill) {
            if (!node) return; var cn = node.childNodes; if (cn && cn.length > 0) { $.asap(cleanUpAsap, {}, [slice.apply(cn, [0]), kill]); }
            if (itself) cleanUpNode(node, kill);
        }
        var timeouts = []; var contexts = []; var params = []; $.asap = function (fn, context, args) { if (!$.isFunction(fn)) throw "$.asap - argument is not a valid function"; timeouts.push(fn); contexts.push(context ? context : {}); params.push(args ? args : []); window.postMessage("jqm-asap", "*"); }
        window.addEventListener("message", function (event) { if (event.source == window && event.data == "jqm-asap") { event.stopPropagation(); if (timeouts.length > 0) { (timeouts.shift()).apply(contexts.shift(), params.shift()); } } }, true); var remoteJSPages = {}; $.parseJS = function (div) {
            if (!div)
                return; if (typeof (div) == "string") { var elem = document.createElement("div"); elem.innerHTML = div; div = elem; }
            var scripts = div.getElementsByTagName("script"); div = null; for (var i = 0; i < scripts.length; i++) { if (scripts[i].src.length > 0 && !remoteJSPages[scripts[i].src]) { var doc = document.createElement("script"); doc.type = scripts[i].type; doc.src = scripts[i].src; document.getElementsByTagName('head')[0].appendChild(doc); remoteJSPages[scripts[i].src] = 1; doc = null; } else { window.eval(scripts[i].innerHTML); } }
        };["click", "keydown", "keyup", "keypress", "submit", "load", "resize", "change", "select", "error"].forEach(function (event) { $.fn[event] = function (cb) { return cb ? this.bind(event, cb) : this.trigger(event); } }); return $;
    })(window); '$' in window || (window.$ = jq); if (!window.numOnly) {
        window.numOnly = function numOnly(val) {
            if (val === undefined || val === '') return 0; if (isNaN(parseFloat(val))) { if (val.replace) { val = val.replace(/[^0-9.-]/, ""); } else return 0; }
            return parseFloat(val);
        }
    }
}
(function ($) {
    $["template"] = function (tmpl, data) { return (template(tmpl, data)); }; $["tmpl"] = function (tmpl, data) { return $(template(tmpl, data)); }; var template = function (str, data) {
        if (!data)
            data = {}; return tmpl(str, data);
    }; (function () { var cache = {}; this.tmpl = function tmpl(str, data) { var fn = !/\W/.test(str) || /.js$/.test(str) ? cache[str] = cache[str] || tmpl(document.getElementById(str).innerHTML) : new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};" + "with(obj){p.push('" + str.replace(/[\r\t\n]/g, " ").replace(/'(?=[^%]*%>)/g, "\t").split("'").join("\\'").split("\t").join("'").replace(/<%=(.+?)%>/g, "',$1,'").split("<%").join("');").split("%>").join("p.push('") + "');}return p.join('');"); return data ? fn(data) : fn; }; })();
})(jq);
(function (window, doc) {
    var m = Math, dummyStyle = doc.createElement('div').style, vendor = (function () {
        var vendors = 't,webkitT,MozT,msT,OT'.split(','), t, i = 0, l = vendors.length; for (; i < l; i++) { t = vendors[i] + 'ransform'; if (t in dummyStyle) { return vendors[i].substr(0, vendors[i].length - 1); } }
        return false;
    })(), cssVendor = vendor ? '-' + vendor.toLowerCase() + '-' : '', transform = prefixStyle('transform'), transitionProperty = prefixStyle('transitionProperty'), transitionDuration = prefixStyle('transitionDuration'), transformOrigin = prefixStyle('transformOrigin'), transitionTimingFunction = prefixStyle('transitionTimingFunction'), transitionDelay = prefixStyle('transitionDelay'), isAndroid = (/android/gi).test(navigator.appVersion), isIDevice = (/iphone|ipad/gi).test(navigator.appVersion), isTouchPad = (/hp-tablet/gi).test(navigator.appVersion), has3d = prefixStyle('perspective') in dummyStyle, hasTouch = 'ontouchstart' in window && !isTouchPad, hasTransform = vendor !== false, hasTransitionEnd = prefixStyle('transition') in dummyStyle, RESIZE_EV = 'onorientationchange' in window ? 'orientationchange' : 'resize', START_EV = hasTouch ? 'touchstart' : 'mousedown', MOVE_EV = hasTouch ? 'touchmove' : 'mousemove', END_EV = hasTouch ? 'touchend' : 'mouseup', CANCEL_EV = hasTouch ? 'touchcancel' : 'mouseup', TRNEND_EV = (function () { if (vendor === false) return false; var transitionEnd = { '': 'transitionend', 'webkit': 'webkitTransitionEnd', 'Moz': 'transitionend', 'O': 'otransitionend', 'ms': 'MSTransitionEnd' }; return transitionEnd[vendor]; })(), nextFrame = (function () { return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || function (callback) { return setTimeout(callback, 1); }; })(), cancelFrame = (function () { return window.cancelRequestAnimationFrame || window.webkitCancelAnimationFrame || window.webkitCancelRequestAnimationFrame || window.mozCancelRequestAnimationFrame || window.oCancelRequestAnimationFrame || window.msCancelRequestAnimationFrame || clearTimeout; })(), translateZ = has3d ? ' translateZ(0)' : '', iScroll = function (el, options) {
        var that = this, i; that.wrapper = typeof el == 'object' ? el : doc.getElementById(el); that.wrapper.style.overflow = 'hidden'; that.scroller = that.wrapper.children[0]; that.options = { hScroll: true, vScroll: true, x: 0, y: 0, bounce: true, bounceLock: false, momentum: true, lockDirection: true, useTransform: true, useTransition: false, topOffset: 0, checkDOMChanges: false, handleClick: true, hScrollbar: true, vScrollbar: true, fixedScrollbar: isAndroid, hideScrollbar: isIDevice, fadeScrollbar: isIDevice && has3d, scrollbarClass: '', zoom: false, zoomMin: 1, zoomMax: 4, doubleTapZoom: 2, wheelAction: 'scroll', snap: false, snapThreshold: 1, onRefresh: null, onBeforeScrollStart: function (e) { e.preventDefault(); }, onScrollStart: null, onBeforeScrollMove: null, onScrollMove: null, onBeforeScrollEnd: null, onScrollEnd: null, onTouchEnd: null, onDestroy: null, onZoomStart: null, onZoom: null, onZoomEnd: null }; for (i in options) that.options[i] = options[i]; that.x = that.options.x; that.y = that.options.y; that.options.useTransform = hasTransform && that.options.useTransform; that.options.hScrollbar = that.options.hScroll && that.options.hScrollbar; that.options.vScrollbar = that.options.vScroll && that.options.vScrollbar; that.options.zoom = that.options.useTransform && that.options.zoom; that.options.useTransition = hasTransitionEnd && that.options.useTransition; if (that.options.zoom && isAndroid) { translateZ = ''; }
        that.scroller.style[transitionProperty] = that.options.useTransform ? cssVendor + 'transform' : 'top left'; that.scroller.style[transitionDuration] = '0'; that.scroller.style[transformOrigin] = '0 0'; if (that.options.useTransition) that.scroller.style[transitionTimingFunction] = 'cubic-bezier(0.33,0.66,0.66,1)'; if (that.options.useTransform) that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px)' + translateZ; else that.scroller.style.cssText += ';position:absolute;top:' + that.y + 'px;left:' + that.x + 'px'; if (that.options.useTransition) that.options.fixedScrollbar = true; that.refresh(); that._bind(RESIZE_EV, window); that._bind(START_EV); if (!hasTouch) { if (that.options.wheelAction != 'none') { that._bind('DOMMouseScroll'); that._bind('mousewheel'); } }
        if (that.options.checkDOMChanges) that.checkDOMTime = setInterval(function () { that._checkDOMChanges(); }, 500);
    }; iScroll.prototype = {
        enabled: true, x: 0, y: 0, steps: [], scale: 1, currPageX: 0, currPageY: 0, pagesX: [], pagesY: [], aniTime: null, wheelZoomCount: 0, handleEvent: function (e) { var that = this; switch (e.type) { case START_EV: if (!hasTouch && e.button !== 0) return; that._start(e); break; case MOVE_EV: that._move(e); break; case END_EV: case CANCEL_EV: that._end(e); break; case RESIZE_EV: that._resize(); break; case 'DOMMouseScroll': case 'mousewheel': that._wheel(e); break; case TRNEND_EV: that._transitionEnd(e); break; } }, _checkDOMChanges: function () { if (this.moved || this.zoomed || this.animating || (this.scrollerW == this.scroller.offsetWidth * this.scale && this.scrollerH == this.scroller.offsetHeight * this.scale)) return; this.refresh(); }, _scrollbar: function (dir) {
            var that = this, bar; if (!that[dir + 'Scrollbar']) {
                if (that[dir + 'ScrollbarWrapper']) { if (hasTransform) that[dir + 'ScrollbarIndicator'].style[transform] = ''; that[dir + 'ScrollbarWrapper'].parentNode.removeChild(that[dir + 'ScrollbarWrapper']); that[dir + 'ScrollbarWrapper'] = null; that[dir + 'ScrollbarIndicator'] = null; }
                return;
            }
            if (!that[dir + 'ScrollbarWrapper']) {
                bar = doc.createElement('div'); if (that.options.scrollbarClass) bar.className = that.options.scrollbarClass + dir.toUpperCase(); else bar.style.cssText = 'position:absolute;z-index:100;' + (dir == 'h' ? 'height:7px;bottom:1px;left:2px;right:' + (that.vScrollbar ? '7' : '2') + 'px' : 'width:7px;bottom:' + (that.hScrollbar ? '7' : '2') + 'px;top:2px;right:1px'); bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:opacity;' + cssVendor + 'transition-duration:' + (that.options.fadeScrollbar ? '350ms' : '0') + ';overflow:hidden;opacity:' + (that.options.hideScrollbar ? '0' : '1'); that.wrapper.appendChild(bar); that[dir + 'ScrollbarWrapper'] = bar; bar = doc.createElement('div'); if (!that.options.scrollbarClass) { bar.style.cssText = 'position:absolute;z-index:100;background:rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.9);' + cssVendor + 'background-clip:padding-box;' + cssVendor + 'box-sizing:border-box;' + (dir == 'h' ? 'height:100%' : 'width:100%') + ';' + cssVendor + 'border-radius:3px;border-radius:3px'; }
                bar.style.cssText += ';pointer-events:none;' + cssVendor + 'transition-property:' + cssVendor + 'transform;' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1);' + cssVendor + 'transition-duration:0;' + cssVendor + 'transform: translate(0,0)' + translateZ; if (that.options.useTransition) bar.style.cssText += ';' + cssVendor + 'transition-timing-function:cubic-bezier(0.33,0.66,0.66,1)'; that[dir + 'ScrollbarWrapper'].appendChild(bar); that[dir + 'ScrollbarIndicator'] = bar;
            }
            if (dir == 'h') { that.hScrollbarSize = that.hScrollbarWrapper.clientWidth; that.hScrollbarIndicatorSize = m.max(m.round(that.hScrollbarSize * that.hScrollbarSize / that.scrollerW), 8); that.hScrollbarIndicator.style.width = that.hScrollbarIndicatorSize + 'px'; that.hScrollbarMaxScroll = that.hScrollbarSize - that.hScrollbarIndicatorSize; that.hScrollbarProp = that.hScrollbarMaxScroll / that.maxScrollX; } else { that.vScrollbarSize = that.vScrollbarWrapper.clientHeight; that.vScrollbarIndicatorSize = m.max(m.round(that.vScrollbarSize * that.vScrollbarSize / that.scrollerH), 8); that.vScrollbarIndicator.style.height = that.vScrollbarIndicatorSize + 'px'; that.vScrollbarMaxScroll = that.vScrollbarSize - that.vScrollbarIndicatorSize; that.vScrollbarProp = that.vScrollbarMaxScroll / that.maxScrollY; }
            that._scrollbarPos(dir, true);
        }, _resize: function () { var that = this; setTimeout(function () { that.refresh(); }, isAndroid ? 200 : 0); }, _pos: function (x, y) {
            if (this.zoomed) return; x = this.hScroll ? x : 0; y = this.vScroll ? y : 0; if (this.options.useTransform) { this.scroller.style[transform] = 'translate(' + x + 'px,' + y + 'px) scale(' + this.scale + ')' + translateZ; } else { x = m.round(x); y = m.round(y); this.scroller.style.left = x + 'px'; this.scroller.style.top = y + 'px'; }
            this.x = x; this.y = y; this._scrollbarPos('h'); this._scrollbarPos('v');
        }, _scrollbarPos: function (dir, hidden) {
            var that = this, pos = dir == 'h' ? that.x : that.y, size; if (!that[dir + 'Scrollbar']) return; pos = that[dir + 'ScrollbarProp'] * pos; if (pos < 0) {
                if (!that.options.fixedScrollbar) { size = that[dir + 'ScrollbarIndicatorSize'] + m.round(pos * 3); if (size < 8) size = 8; that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px'; }
                pos = 0;
            } else if (pos > that[dir + 'ScrollbarMaxScroll']) { if (!that.options.fixedScrollbar) { size = that[dir + 'ScrollbarIndicatorSize'] - m.round((pos - that[dir + 'ScrollbarMaxScroll']) * 3); if (size < 8) size = 8; that[dir + 'ScrollbarIndicator'].style[dir == 'h' ? 'width' : 'height'] = size + 'px'; pos = that[dir + 'ScrollbarMaxScroll'] + (that[dir + 'ScrollbarIndicatorSize'] - size); } else { pos = that[dir + 'ScrollbarMaxScroll']; } }
            that[dir + 'ScrollbarWrapper'].style[transitionDelay] = '0'; that[dir + 'ScrollbarWrapper'].style.opacity = hidden && that.options.hideScrollbar ? '0' : '1'; that[dir + 'ScrollbarIndicator'].style[transform] = 'translate(' + (dir == 'h' ? pos + 'px,0)' : '0,' + pos + 'px)') + translateZ;
        }, _start: function (e) {
            var that = this, point = hasTouch ? e.touches[0] : e, matrix, x, y, c1, c2; if (!that.enabled) return; if (that.options.onBeforeScrollStart) that.options.onBeforeScrollStart.call(that, e); if (that.options.useTransition || that.options.zoom) that._transitionTime(0); that.moved = false; that.animating = false; that.zoomed = false; that.distX = 0; that.distY = 0; that.absDistX = 0; that.absDistY = 0; that.dirX = 0; that.dirY = 0; if (that.options.zoom && hasTouch && e.touches.length > 1) { c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX); c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY); that.touchesDistStart = m.sqrt(c1 * c1 + c2 * c2); that.originX = m.abs(e.touches[0].pageX + e.touches[1].pageX - that.wrapperOffsetLeft * 2) / 2 - that.x; that.originY = m.abs(e.touches[0].pageY + e.touches[1].pageY - that.wrapperOffsetTop * 2) / 2 - that.y; if (that.options.onZoomStart) that.options.onZoomStart.call(that, e); }
            if (that.options.momentum) {
                if (that.options.useTransform) { matrix = getComputedStyle(that.scroller, null)[transform].replace(/[^0-9\-.,]/g, '').split(','); x = +(matrix[12] || matrix[4]); y = +(matrix[13] || matrix[5]); } else { x = +getComputedStyle(that.scroller, null).left.replace(/[^0-9-]/g, ''); y = +getComputedStyle(that.scroller, null).top.replace(/[^0-9-]/g, ''); }
                if (x != that.x || y != that.y) { if (that.options.useTransition) that._unbind(TRNEND_EV); else cancelFrame(that.aniTime); that.steps = []; that._pos(x, y); if (that.options.onScrollEnd) that.options.onScrollEnd.call(that); }
            }
            that.absStartX = that.x; that.absStartY = that.y; that.startX = that.x; that.startY = that.y; that.pointX = point.pageX; that.pointY = point.pageY; that.startTime = e.timeStamp || Date.now(); if (that.options.onScrollStart) that.options.onScrollStart.call(that, e); that._bind(MOVE_EV, window); that._bind(END_EV, window); that._bind(CANCEL_EV, window);
        }, _move: function (e) {
            var that = this, point = hasTouch ? e.touches[0] : e, deltaX = point.pageX - that.pointX, deltaY = point.pageY - that.pointY, newX = that.x + deltaX, newY = that.y + deltaY, c1, c2, scale, timestamp = e.timeStamp || Date.now(); if (that.options.onBeforeScrollMove) that.options.onBeforeScrollMove.call(that, e); if (that.options.zoom && hasTouch && e.touches.length > 1) { c1 = m.abs(e.touches[0].pageX - e.touches[1].pageX); c2 = m.abs(e.touches[0].pageY - e.touches[1].pageY); that.touchesDist = m.sqrt(c1 * c1 + c2 * c2); that.zoomed = true; scale = 1 / that.touchesDistStart * that.touchesDist * this.scale; if (scale < that.options.zoomMin) scale = 0.5 * that.options.zoomMin * Math.pow(2.0, scale / that.options.zoomMin); else if (scale > that.options.zoomMax) scale = 2.0 * that.options.zoomMax * Math.pow(0.5, that.options.zoomMax / scale); that.lastScale = scale / this.scale; newX = this.originX - this.originX * that.lastScale + this.x; newY = this.originY - this.originY * that.lastScale + this.y; this.scroller.style[transform] = 'translate(' + newX + 'px,' + newY + 'px) scale(' + scale + ')' + translateZ; if (that.options.onZoom) that.options.onZoom.call(that, e); return; }
            that.pointX = point.pageX; that.pointY = point.pageY; if (newX > 0 || newX < that.maxScrollX) { newX = that.options.bounce ? that.x + (deltaX / 2) : newX >= 0 || that.maxScrollX >= 0 ? 0 : that.maxScrollX; }
            if (newY > that.minScrollY || newY < that.maxScrollY) { newY = that.options.bounce ? that.y + (deltaY / 2) : newY >= that.minScrollY || that.maxScrollY >= 0 ? that.minScrollY : that.maxScrollY; }
            that.distX += deltaX; that.distY += deltaY; that.absDistX = m.abs(that.distX); that.absDistY = m.abs(that.distY); if (that.absDistX < 6 && that.absDistY < 6) { return; }
            if (that.options.lockDirection) { if (that.absDistX > that.absDistY + 5) { newY = that.y; deltaY = 0; } else if (that.absDistY > that.absDistX + 5) { newX = that.x; deltaX = 0; } }
            that.moved = true; that._pos(newX, newY); that.dirX = deltaX > 0 ? -1 : deltaX < 0 ? 1 : 0; that.dirY = deltaY > 0 ? -1 : deltaY < 0 ? 1 : 0; if (timestamp - that.startTime > 300) { that.startTime = timestamp; that.startX = that.x; that.startY = that.y; }
            if (that.options.onScrollMove) that.options.onScrollMove.call(that, e);
        }, _end: function (e) {
            if (hasTouch && e.touches.length !== 0) return; var that = this, point = hasTouch ? e.changedTouches[0] : e, target, ev, momentumX = { dist: 0, time: 0 }, momentumY = { dist: 0, time: 0 }, duration = (e.timeStamp || Date.now()) - that.startTime, newPosX = that.x, newPosY = that.y, distX, distY, newDuration, snap, scale; that._unbind(MOVE_EV, window); that._unbind(END_EV, window); that._unbind(CANCEL_EV, window); if (that.options.onBeforeScrollEnd) that.options.onBeforeScrollEnd.call(that, e); if (that.zoomed) { scale = that.scale * that.lastScale; scale = Math.max(that.options.zoomMin, scale); scale = Math.min(that.options.zoomMax, scale); that.lastScale = scale / that.scale; that.scale = scale; that.x = that.originX - that.originX * that.lastScale + that.x; that.y = that.originY - that.originY * that.lastScale + that.y; that.scroller.style[transitionDuration] = '200ms'; that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + that.scale + ')' + translateZ; that.zoomed = false; that.refresh(); if (that.options.onZoomEnd) that.options.onZoomEnd.call(that, e); return; }
            if (!that.moved) {
                if (hasTouch) { if (that.doubleTapTimer && that.options.zoom) { clearTimeout(that.doubleTapTimer); that.doubleTapTimer = null; if (that.options.onZoomStart) that.options.onZoomStart.call(that, e); that.zoom(that.pointX, that.pointY, that.scale == 1 ? that.options.doubleTapZoom : 1); if (that.options.onZoomEnd) { setTimeout(function () { that.options.onZoomEnd.call(that, e); }, 200); } } else if (this.options.handleClick) { that.doubleTapTimer = setTimeout(function () { that.doubleTapTimer = null; target = point.target; while (target.nodeType != 1) target = target.parentNode; if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA') { ev = doc.createEvent('MouseEvents'); ev.initMouseEvent('click', true, true, e.view, 1, point.screenX, point.screenY, point.clientX, point.clientY, e.ctrlKey, e.altKey, e.shiftKey, e.metaKey, 0, null); ev._fake = true; target.dispatchEvent(ev); } }, that.options.zoom ? 250 : 0); } }
                that._resetPos(400); if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e); return;
            }
            if (duration < 300 && that.options.momentum) { momentumX = newPosX ? that._momentum(newPosX - that.startX, duration, -that.x, that.scrollerW - that.wrapperW + that.x, that.options.bounce ? that.wrapperW : 0) : momentumX; momentumY = newPosY ? that._momentum(newPosY - that.startY, duration, -that.y, (that.maxScrollY < 0 ? that.scrollerH - that.wrapperH + that.y - that.minScrollY : 0), that.options.bounce ? that.wrapperH : 0) : momentumY; newPosX = that.x + momentumX.dist; newPosY = that.y + momentumY.dist; if ((that.x > 0 && newPosX > 0) || (that.x < that.maxScrollX && newPosX < that.maxScrollX)) momentumX = { dist: 0, time: 0 }; if ((that.y > that.minScrollY && newPosY > that.minScrollY) || (that.y < that.maxScrollY && newPosY < that.maxScrollY)) momentumY = { dist: 0, time: 0 }; }
            if (momentumX.dist || momentumY.dist) {
                newDuration = m.max(m.max(momentumX.time, momentumY.time), 10); if (that.options.snap) {
                    distX = newPosX - that.absStartX; distY = newPosY - that.absStartY; if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) { that.scrollTo(that.absStartX, that.absStartY, 200); }
                    else { snap = that._snap(newPosX, newPosY); newPosX = snap.x; newPosY = snap.y; newDuration = m.max(snap.time, newDuration); }
                }
                that.scrollTo(m.round(newPosX), m.round(newPosY), newDuration); if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e); return;
            }
            if (that.options.snap) {
                distX = newPosX - that.absStartX; distY = newPosY - that.absStartY; if (m.abs(distX) < that.options.snapThreshold && m.abs(distY) < that.options.snapThreshold) that.scrollTo(that.absStartX, that.absStartY, 200); else { snap = that._snap(that.x, that.y); if (snap.x != that.x || snap.y != that.y) that.scrollTo(snap.x, snap.y, snap.time); }
                if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e); return;
            }
            that._resetPos(200); if (that.options.onTouchEnd) that.options.onTouchEnd.call(that, e);
        }, _resetPos: function (time) {
            var that = this, resetX = that.x >= 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x, resetY = that.y >= that.minScrollY || that.maxScrollY > 0 ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y; if (resetX == that.x && resetY == that.y) {
                if (that.moved) { that.moved = false; if (that.options.onScrollEnd) that.options.onScrollEnd.call(that); }
                if (that.hScrollbar && that.options.hideScrollbar) { if (vendor == 'webkit') that.hScrollbarWrapper.style[transitionDelay] = '300ms'; that.hScrollbarWrapper.style.opacity = '0'; }
                if (that.vScrollbar && that.options.hideScrollbar) { if (vendor == 'webkit') that.vScrollbarWrapper.style[transitionDelay] = '300ms'; that.vScrollbarWrapper.style.opacity = '0'; }
                return;
            }
            that.scrollTo(resetX, resetY, time || 0);
        }, _wheel: function (e) {
            var that = this, wheelDeltaX, wheelDeltaY, deltaX, deltaY, deltaScale; if ('wheelDeltaX' in e) { wheelDeltaX = e.wheelDeltaX / 12; wheelDeltaY = e.wheelDeltaY / 12; } else if ('wheelDelta' in e) { wheelDeltaX = wheelDeltaY = e.wheelDelta / 12; } else if ('detail' in e) { wheelDeltaX = wheelDeltaY = -e.detail * 3; } else { return; }
            if (that.options.wheelAction == 'zoom') {
                deltaScale = that.scale * Math.pow(2, 1 / 3 * (wheelDeltaY ? wheelDeltaY / Math.abs(wheelDeltaY) : 0)); if (deltaScale < that.options.zoomMin) deltaScale = that.options.zoomMin; if (deltaScale > that.options.zoomMax) deltaScale = that.options.zoomMax; if (deltaScale != that.scale) { if (!that.wheelZoomCount && that.options.onZoomStart) that.options.onZoomStart.call(that, e); that.wheelZoomCount++; that.zoom(e.pageX, e.pageY, deltaScale, 400); setTimeout(function () { that.wheelZoomCount--; if (!that.wheelZoomCount && that.options.onZoomEnd) that.options.onZoomEnd.call(that, e); }, 400); }
                return;
            }
            deltaX = that.x + wheelDeltaX; deltaY = that.y + wheelDeltaY; if (deltaX > 0) deltaX = 0; else if (deltaX < that.maxScrollX) deltaX = that.maxScrollX; if (deltaY > that.minScrollY) deltaY = that.minScrollY; else if (deltaY < that.maxScrollY) deltaY = that.maxScrollY; if (that.maxScrollY < 0) { that.scrollTo(deltaX, deltaY, 0); }
        }, _transitionEnd: function (e) { var that = this; if (e.target != that.scroller) return; that._unbind(TRNEND_EV); that._startAni(); }, _startAni: function () {
            var that = this, startX = that.x, startY = that.y, startTime = Date.now(), step, easeOut, animate; if (that.animating) return; if (!that.steps.length) { that._resetPos(400); return; }
            step = that.steps.shift(); if (step.x == startX && step.y == startY) step.time = 0; that.animating = true; that.moved = true; if (that.options.useTransition) { that._transitionTime(step.time); that._pos(step.x, step.y); that.animating = false; if (step.time) that._bind(TRNEND_EV); else that._resetPos(0); return; }
            animate = function () {
                var now = Date.now(), newX, newY; if (now >= startTime + step.time) { that._pos(step.x, step.y); that.animating = false; if (that.options.onAnimationEnd) that.options.onAnimationEnd.call(that); that._startAni(); return; }
                now = (now - startTime) / step.time - 1; easeOut = m.sqrt(1 - now * now); newX = (step.x - startX) * easeOut + startX; newY = (step.y - startY) * easeOut + startY; that._pos(newX, newY); if (that.animating) that.aniTime = nextFrame(animate);
            }; animate();
        }, _transitionTime: function (time) { time += 'ms'; this.scroller.style[transitionDuration] = time; if (this.hScrollbar) this.hScrollbarIndicator.style[transitionDuration] = time; if (this.vScrollbar) this.vScrollbarIndicator.style[transitionDuration] = time; }, _momentum: function (dist, time, maxDistUpper, maxDistLower, size) {
            var deceleration = 0.0006, speed = m.abs(dist) / time, newDist = (speed * speed) / (2 * deceleration), newTime = 0, outsideDist = 0; if (dist > 0 && newDist > maxDistUpper) { outsideDist = size / (6 / (newDist / speed * deceleration)); maxDistUpper = maxDistUpper + outsideDist; speed = speed * maxDistUpper / newDist; newDist = maxDistUpper; } else if (dist < 0 && newDist > maxDistLower) { outsideDist = size / (6 / (newDist / speed * deceleration)); maxDistLower = maxDistLower + outsideDist; speed = speed * maxDistLower / newDist; newDist = maxDistLower; }
            newDist = newDist * (dist < 0 ? -1 : 1); newTime = speed / deceleration; return { dist: newDist, time: m.round(newTime) };
        }, _offset: function (el) {
            var left = -el.offsetLeft, top = -el.offsetTop; while (el = el.offsetParent) { left -= el.offsetLeft; top -= el.offsetTop; }
            if (el != this.wrapper) { left *= this.scale; top *= this.scale; }
            return { left: left, top: top };
        }, _snap: function (x, y) {
            var that = this, i, l, page, time, sizeX, sizeY; page = that.pagesX.length - 1; for (i = 0, l = that.pagesX.length; i < l; i++) { if (x >= that.pagesX[i]) { page = i; break; } }
            if (page == that.currPageX && page > 0 && that.dirX < 0) page--; x = that.pagesX[page]; sizeX = m.abs(x - that.pagesX[that.currPageX]); sizeX = sizeX ? m.abs(that.x - x) / sizeX * 500 : 0; that.currPageX = page; page = that.pagesY.length - 1; for (i = 0; i < page; i++) { if (y >= that.pagesY[i]) { page = i; break; } }
            if (page == that.currPageY && page > 0 && that.dirY < 0) page--; y = that.pagesY[page]; sizeY = m.abs(y - that.pagesY[that.currPageY]); sizeY = sizeY ? m.abs(that.y - y) / sizeY * 500 : 0; that.currPageY = page; time = m.round(m.max(sizeX, sizeY)) || 200; return { x: x, y: y, time: time };
        }, _bind: function (type, el, bubble) { (el || this.scroller).addEventListener(type, this, !!bubble); }, _unbind: function (type, el, bubble) { (el || this.scroller).removeEventListener(type, this, !!bubble); }, destroy: function () {
            var that = this; that.scroller.style[transform] = ''; that.hScrollbar = false; that.vScrollbar = false; that._scrollbar('h'); that._scrollbar('v'); that._unbind(RESIZE_EV, window); that._unbind(START_EV); that._unbind(MOVE_EV, window); that._unbind(END_EV, window); that._unbind(CANCEL_EV, window); if (!that.options.hasTouch) { that._unbind('DOMMouseScroll'); that._unbind('mousewheel'); }
            if (that.options.useTransition) that._unbind(TRNEND_EV); if (that.options.checkDOMChanges) clearInterval(that.checkDOMTime); if (that.options.onDestroy) that.options.onDestroy.call(that);
        }, refresh: function () {
            var that = this, offset, i, l, els, pos = 0, page = 0; if (that.scale < that.options.zoomMin) that.scale = that.options.zoomMin; that.wrapperW = that.wrapper.clientWidth || 1; that.wrapperH = that.wrapper.clientHeight || 1; that.minScrollY = -that.options.topOffset || 0; that.scrollerW = m.round(that.scroller.offsetWidth * that.scale); that.scrollerH = m.round((that.scroller.offsetHeight + that.minScrollY) * that.scale); that.maxScrollX = that.wrapperW - that.scrollerW; that.maxScrollY = that.wrapperH - that.scrollerH + that.minScrollY; that.dirX = 0; that.dirY = 0; if (that.options.onRefresh) that.options.onRefresh.call(that); that.hScroll = that.options.hScroll && that.maxScrollX < 0; that.vScroll = that.options.vScroll && (!that.options.bounceLock && !that.hScroll || that.scrollerH > that.wrapperH); that.hScrollbar = that.hScroll && that.options.hScrollbar; that.vScrollbar = that.vScroll && that.options.vScrollbar && that.scrollerH > that.wrapperH; offset = that._offset(that.wrapper); that.wrapperOffsetLeft = -offset.left; that.wrapperOffsetTop = -offset.top; if (typeof that.options.snap == 'string') { that.pagesX = []; that.pagesY = []; els = that.scroller.querySelectorAll(that.options.snap); for (i = 0, l = els.length; i < l; i++) { pos = that._offset(els[i]); pos.left += that.wrapperOffsetLeft; pos.top += that.wrapperOffsetTop; that.pagesX[i] = pos.left < that.maxScrollX ? that.maxScrollX : pos.left * that.scale; that.pagesY[i] = pos.top < that.maxScrollY ? that.maxScrollY : pos.top * that.scale; } } else if (that.options.snap) {
                that.pagesX = []; while (pos >= that.maxScrollX) { that.pagesX[page] = pos; pos = pos - that.wrapperW; page++; }
                if (that.maxScrollX % that.wrapperW) that.pagesX[that.pagesX.length] = that.maxScrollX - that.pagesX[that.pagesX.length - 1] + that.pagesX[that.pagesX.length - 1]; pos = 0; page = 0; that.pagesY = []; while (pos >= that.maxScrollY) { that.pagesY[page] = pos; pos = pos - that.wrapperH; page++; }
                if (that.maxScrollY % that.wrapperH) that.pagesY[that.pagesY.length] = that.maxScrollY - that.pagesY[that.pagesY.length - 1] + that.pagesY[that.pagesY.length - 1];
            }
            that._scrollbar('h'); that._scrollbar('v'); if (!that.zoomed) { that.scroller.style[transitionDuration] = '0'; that._resetPos(400); }
        }, scrollTo: function (x, y, time, relative) {
            var that = this, step = x, i, l; that.stop(); if (!step.length) step = [{ x: x, y: y, time: time, relative: relative }]; for (i = 0, l = step.length; i < l; i++) {
                if (step[i].relative) { step[i].x = that.x - step[i].x; step[i].y = that.y - step[i].y; }
                that.steps.push({ x: step[i].x, y: step[i].y, time: step[i].time || 0 });
            }
            that._startAni();
        }, scrollToElement: function (el, time) { var that = this, pos; el = el.nodeType ? el : that.scroller.querySelector(el); if (!el) return; pos = that._offset(el); pos.left += that.wrapperOffsetLeft; pos.top += that.wrapperOffsetTop; pos.left = pos.left > 0 ? 0 : pos.left < that.maxScrollX ? that.maxScrollX : pos.left; pos.top = pos.top > that.minScrollY ? that.minScrollY : pos.top < that.maxScrollY ? that.maxScrollY : pos.top; time = time === undefined ? m.max(m.abs(pos.left) * 2, m.abs(pos.top) * 2) : time; that.scrollTo(pos.left, pos.top, time); }, scrollToPage: function (pageX, pageY, time) {
            var that = this, x, y; time = time === undefined ? 400 : time; if (that.options.onScrollStart) that.options.onScrollStart.call(that); if (that.options.snap) { pageX = pageX == 'next' ? that.currPageX + 1 : pageX == 'prev' ? that.currPageX - 1 : pageX; pageY = pageY == 'next' ? that.currPageY + 1 : pageY == 'prev' ? that.currPageY - 1 : pageY; pageX = pageX < 0 ? 0 : pageX > that.pagesX.length - 1 ? that.pagesX.length - 1 : pageX; pageY = pageY < 0 ? 0 : pageY > that.pagesY.length - 1 ? that.pagesY.length - 1 : pageY; that.currPageX = pageX; that.currPageY = pageY; x = that.pagesX[pageX]; y = that.pagesY[pageY]; } else { x = -that.wrapperW * pageX; y = -that.wrapperH * pageY; if (x < that.maxScrollX) x = that.maxScrollX; if (y < that.maxScrollY) y = that.maxScrollY; }
            that.scrollTo(x, y, time);
        }, disable: function () { this.stop(); this._resetPos(0); this.enabled = false; this._unbind(MOVE_EV, window); this._unbind(END_EV, window); this._unbind(CANCEL_EV, window); }, enable: function () { this.enabled = true; }, stop: function () { if (this.options.useTransition) this._unbind(TRNEND_EV); else cancelFrame(this.aniTime); this.steps = []; this.moved = false; this.animating = false; }, zoom: function (x, y, scale, time) { var that = this, relScale = scale / that.scale; if (!that.options.useTransform) return; that.zoomed = true; time = time === undefined ? 200 : time; x = x - that.wrapperOffsetLeft - that.x; y = y - that.wrapperOffsetTop - that.y; that.x = x - x * relScale + that.x; that.y = y - y * relScale + that.y; that.scale = scale; that.refresh(); that.x = that.x > 0 ? 0 : that.x < that.maxScrollX ? that.maxScrollX : that.x; that.y = that.y > that.minScrollY ? that.minScrollY : that.y < that.maxScrollY ? that.maxScrollY : that.y; that.scroller.style[transitionDuration] = time + 'ms'; that.scroller.style[transform] = 'translate(' + that.x + 'px,' + that.y + 'px) scale(' + scale + ')' + translateZ; that.zoomed = false; }, isReady: function () { return !this.moved && !this.zoomed && !this.animating; }
    }; function prefixStyle(style) { if (vendor === '') return style; style = style.charAt(0).toUpperCase() + style.substr(1); return vendor + style; }
    dummyStyle = null; if (typeof exports !== 'undefined') exports.iScroll = iScroll; else window.iScroll = iScroll;
})(window, document);