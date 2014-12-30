
var tvp=window.tvp||{};var TenVideoPlayer=window.tvp;tvp.lastModify="$Date: 2013-11-18 14:17:01 +0800 (一, 2013-11-18) $";tvp.ver="$Rev: 37429 $";tvp.lastChangedBy="$LastChangedBy: popotang $";tvp.path={base:"http://qzs.qq.com/tencentvideo_v1/js/tvp/",hls_video_cgi:"http://vv.video.qq.com/gethls?otype=json&format=2",html5_video_cgi:"http://vv.video.qq.com/geturl?otype=json"}
tvp.log=function(msg){if(window.console){window.console.log("["+(tvp.log.debugid++)+"] "+msg);}}
tvp.debug=function(msg){if(tvp.log.isDebug===-1){tvp.log.isDebug=tvp.$.getUrlParam("debug")=="true"?1:0;}
if(!!tvp.log.isDebug){tvp.log(msg);}}
tvp.log.isDebug=-1;tvp.log.debugid=1;tvp.$=function(selector){return new tvp.$.fn.init(selector);};tvp.$.fn=tvp.$.prototype={elements:[],init:function(selector){if(!selector){return this;}
if(selector.nodeType){this.elements[0]=selector;return this;}
if(tvp.$.isString(selector)&&selector.length>0){if(tvp.$.isFunction(document.querySelectorAll)){this.elements=document.querySelectorAll(selector);return this;}
if(selector.charAt(0)=='#'){this.elements[0]=document.getElementById(selector.replace("#",""));return this;}
if(/[a-z]+/ig.test(selector)){this.elements=document.getElementsByTagName(selector);return this;}
tvp.$.error("暂不支持复杂查询");}
this.element=null;return this;},selector:null,_tvp$:true,ver:"1.0"}
tvp.$.fn.init.prototype=tvp.$.fn;tvp.$.extend=tvp.$.fn.extend=function(){var options,name,src,copy,copyIsArray,clone,target=arguments[0]||{},i=1,length=arguments.length,deep=false;if(typeof target==="boolean"){deep=target;target=arguments[1]||{};i=2;}
if(typeof target!=="object"&&!tvp.$.isFunction(target)){target={};}
if(length===i){target=this;--i;}
for(;i<length;i++){if((options=arguments[i])!=null){for(name in options){src=target[name];copy=options[name];if(target===copy){continue;}
if(deep&&copy&&(tvp.$.isPlainObject(copy)||(copyIsArray=tvp.$.isArray(copy)))){if(copyIsArray){copyIsArray=false;clone=src&&tvp.$.isArray(src)?src:[];}
else{clone=src&&tvp.$.isPlainObject(src)?src:{};}
target[name]=tvp.$.extend(deep,clone,copy);}
else if(copy!==undefined){target[name]=copy;}}}}
return target;};tvp.$.extend({get:function(id){return document.getElementById(id);},error:function(msg){throw new Error(msg);},getType:function(obj){return obj===null?'null':(obj===undefined?'undefined':Object.prototype.toString.call(obj).slice(8,-1).toLowerCase());},isString:function(o){return tvp.$.getType(o)=="string";},isArray:function(o){return tvp.$.getType(o)=="array";},isFunction:function(o){return tvp.$.getType(o)=="function";},isUndefined:function(o){return tvp.$.getType(o)=="undefined";},isNull:function(o){return tvp.$.getType(o)=="null";},isWindow:function(obj){return obj&&typeof obj==="object"&&"setInterval"in obj;},isEmptyObject:function(obj){for(var name in obj){return false;}
return true;},isPlainObject:function(obj){if(!obj||tvp.$.getType(obj)!=="object"||obj.nodeType||tvp.$.isWindow(obj)){return false;}
try{if(obj.constructor&&!hasOwn.call(obj,"constructor")&&!hasOwn.call(obj.constructor.prototype,"isPrototypeOf")){return false;}}
catch(e){return false;}
var key;for(key in obj){}
return key===undefined||hasOwn.call(obj,key);},inArray:function(elem,array,i){var len;if(array){if(array.indexOf){return Array.prototype.indexOf.call(array,elem,i);}
len=array.length;i=i?i<0?Math.max(0,len+i):i:0;for(;i<len;i++){if(i in array&&array[i]===elem){return i;}}}
return-1;},bind:function(obj,fn){var args=Array.prototype.slice.call(arguments,2);return function(){var _obj=obj||this,_args=args.concat(Array.prototype.slice.call(arguments,0));if(typeof(fn)=="string"){if(_obj[fn]){return _obj[fn].apply(_obj,_args);}}
else{return fn.apply(_obj,_args);}}},each:function(object,callback,args){var name,i=0,length=object.length,isObj=length===undefined||tvp.$.isFunction(object);if(args){if(isObj){for(name in object){if(callback.apply(object[name],args)===false){break;}}}
else{for(;i<length;){if(callback.apply(object[i++],args)===false){break;}}}}
else{if(isObj){for(name in object){if(callback.call(object[name],name,object[name])===false){break;}}}
else{for(;i<length;){if(callback.call(object[i],i,object[i++])===false){break;}}}}
return object;},noop:function(){},now:function(){return new Date().getTime();},getISOTimeFormat:function(){var date=new Date();var y=date.getFullYear(),m=date.getMonth()+1,d=date.getDate(),h=date.getHours(),M=date.getMinutes(),s=date.getSeconds();return[[y,m<10?"0"+m:m,d<10?"0"+d:d].join("-"),[h<10?"0"+h:h,M<10?"0"+M:M,s<10?"0"+s:s].join(":")].join(" ");},getHost:function(){var _host=window.location.hostname||window.location.host;var _sarray=location.host.split(".");if(_sarray.length>1){_host=_sarray.slice(_sarray.length-2).join(".");}
return _host;},getUrlParam:function(p,u){u=u||document.location.toString();var reg=new RegExp("(^|&|\\\\?)"+p+"=([^&]*)(&|$|#)");var r;if(r=u.match(reg))
return r[2];return"";},filterXSS:function(str){if(!tvp.$.isString(str))
return str;return str.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\"/g,"&quot;").replace(/\'/g,"&apos;");},escString:function(str){var t={bsls:/\\/g,nl:/\n/g,rt:/\r/g,tab:/\t/g},h={re_amp:/&/g,re_lt:/</g,re_gt:/>/g,re_apos:/\x27/g,re_quot:/\x22/g};var obj={'\\\\':t.bsls,'\\n':t.nl,'':t.rt,'\\t':t.tab,'\\\'':h.re_apos,'\\"':h.re_quot};tvp.$.each(obj,function(k,v){str=str.replace(k,v);});return str;},createGUID:function(len){len=len||32;var guid="";for(var i=1;i<=len;i++){var n=Math.floor(Math.random()*16.0).toString(16);guid+=n;}
return guid;}});tvp.$.fn.extend({each:function(callback,args){return tvp.$.each(this.elements,callback,args);},isEmpty:function(){return this.size()==0;},bind:function(evtType,fn){if(!tvp.$.isFunction(fn)){return false;}
this.each(function(){tvp.$.event.add(this,evtType,fn);});return this;},unbind:function(evtType,fn){this.each(function(){tvp.$.event[fn?'removeEvent':'purgeEvent'](this,evtType,fn);});return this;},size:function(){return this.elements.length;},html:function(str){if(!tvp.$.isUndefined(str)){this.attr("innerHTML",str);return this;}
else{return this.attr("innerHTML");}},attr:function(k,v){if(!tvp.$.isUndefined(v)){this.each(function(){this[k]=v;});return this;}
var el=this.elements[0];return!!el?(el[k]||el.getAttribute(k)):null;},removeAttr:function(k){this.each(function(){this.setAttribute(k,"");if(this.nodeType==1){this.removeAttribute(k);}});return this;}});tvp.$.dom={removeElement:function(el){if(typeof(el)=="string"){el=tvp.$.get(el);}
if(!el){return;}
if(el.removeNode){el.removeNode(true);}
else{if(el.parentNode){el.parentNode.removeChild(el);}}
el=null;return null;}}
tvp.$.browser=tvp.$.userAgent=(function(){var t,vie,vff,vopera,vsf,vawk,vair,vchrome,winver,wintype,mactype,isBeta,isIPad,isIPhone,vAndroid,discerned,is64,_ua=navigator.userAgent,_nv=navigator.appVersion,vffRE=/(?:Firefox|GranParadiso|Iceweasel|Minefield).(\d+\.\d+)/i,vwebkitRE=/AppleWebKit.(\d+\.\d+)/i,vchromeRE=/Chrome.(\d+\.\d+)/i,vsafariRE=/Version.(\d+\.\d+)/i,vwinRE=/Windows.+?(\d+\.\d+)/,vie=vff=vopera=vsf=vawk=vair=vchrome=winver=vAndroid=NaN;wintype=mactype=isBeta=isIPad=discerned=is64=false;if(window.ActiveXObject){vie=9-((_nv.indexOf("Trident\/5.0")>-1)?0:1)-(window.XDomainRequest?0:1)-(window.XMLHttpRequest?0:1);t=navigator.appMinorVersion;if(vie>7&&t&&t.toLowerCase().indexOf("beta")>-1){isBeta=true;}}
else if(t=_ua.match(vffRE)){vff=parseFloat((t&&t[1])||"3.3",10);}
else if(!navigator.taintEnabled){t=_ua.match(vwebkitRE);vawk=(t&&t.length>1)?parseFloat(t[1],10):(!!document.evaluate?(!!document.querySelector?525:420):419);if((t=_nv.match(vchromeRE))||window.chrome){if(!t){t=_ua.match(vchromeRE);}
vchrome=parseFloat((t&&t[1])||"2.0",10);}
if((t=_nv.match(vsafariRE))&&!window.chrome){if(!t){t=_ua.match(vsafariRE);}
vsf=parseFloat((t&&t[1])||"3.3",10);}}
else if(window.opera){vopera=parseFloat(_nv,10);}
else{vie=6;}
if(_ua.indexOf("AdobeAIR")>-1){vair=1;}
if(_ua.indexOf("iPad")>-1){isIPad=true;}
if(_ua.indexOf("iPhone")>-1){isIPhone=true;}
if(t=_ua.match(/Android( ((\d\.\d)(\.\d)?))?/i)){vAndroid=parseFloat((t&&t[2])||"2.1",10);}
if(_ua.indexOf("Windows")>-1){wintype=true;t=_ua.match(vwinRE);winver=parseFloat((t&&t[1])||"5.1",10);}
else if(_ua.indexOf("Mac OS X")>-1){mactype=true;}
if(typeof navigator.platform!="undefined"&&navigator.platform.toLowerCase()=="win64"){is64=true;}
return{beta:isBeta,firefox:vff,ie:vie,msie:vie,opera:vopera,air:vair,safari:vsf,safariV:vsf,webkit:vawk,chrome:vchrome,windows:winver||wintype,isiPad:isIPad,isiPhone:isIPhone,macs:mactype,android:vAndroid,is64:is64,isFFCanOcx:function(){if(!!vff&&vff>=3.0){return true;}
return false;},isCanOcx:function(){return(tvp.$.userAgent.windows&&(!!vie||(!!vff&&vff>=3.0)||!!vawk));},isNotIESupport:function(){return(tvp.$.userAgent.windows&&(vawk||tvp.$.userAgent.isFFCanOcx()));}}})();tvp.$.event={KEYS:{BACKSPACE:8,TAB:9,RETURN:13,ESC:27,SPACE:32,LEFT:37,UP:38,RIGHT:39,DOWN:40,DELETE:46},extendType:/(click|mousedown|mouseover|mouseout|mouseup|mousemove|scroll|contextmenu|resize)/i,_eventListDictionary:{},_fnSeqUID:0,_objSeqUID:0,add:function(obj,eventType,fn,argArray){var cfn=fn,res=false,l;if(!obj){return res;}
if(!obj.eventsListUID){obj.eventsListUID="e"+(++tvp.$.event._objSeqUID);}
if(!(l=tvp.$.event._eventListDictionary[obj.eventsListUID])){l=tvp.$.event._eventListDictionary[obj.eventsListUID]={};}
if(!fn.__elUID){fn.__elUID="e"+(++tvp.$.event._fnSeqUID)+obj.eventsListUID;}
if(!l[eventType]){l[eventType]={};}
if(typeof(l[eventType][fn.__elUID])=='function'){return false;}
if(tvp.$.event.extendType.test(eventType)){argArray=argArray||[];}
cfn=function(e){return fn.apply(obj,([tvp.$.event.getEvent(e)]).concat(argArray));};if(obj.addEventListener){obj.addEventListener(eventType,cfn,false);res=true;}
else if(obj.attachEvent){res=obj.attachEvent("on"+eventType,cfn);}
else{res=false;}
if(res){l[eventType][fn.__elUID]=cfn;}
return res;},remove:function(obj,eventType,fn){var cfn=fn,res=false,l;if(!obj){return res;}
if(!cfn){return tvp.$.event.purgeEvent(obj,eventType);}
if(!obj.eventsListUID){obj.eventsListUID="e"+(++tvp.$.event._objSeqUID);}
if(!(l=tvp.$.event._eventListDictionary[obj.eventsListUID])){l=tvp.$.event._eventListDictionary[obj.eventsListUID]={};}
if(!fn.__elUID){fn.__elUID="e"+(++tvp.$.event._fnSeqUID)+obj.eventsListUID;}
if(!l[eventType]){l[eventType]={};}
if(tvp.$.event.extendType.test(eventType)&&l[eventType]&&l[eventType][fn.__elUID]){cfn=l[eventType][fn.__elUID];}
if(obj.removeEventListener){obj.removeEventListener(eventType,cfn,false);res=true;}
else if(obj.detachEvent){obj.detachEvent("on"+eventType,cfn);res=true;}
else{return false;}
if(res&&l[eventType]){delete l[eventType][fn.__elUID];}
return res;},purgeEvent:function(obj,type){var l;if(obj.eventsListUID&&(l=tvp.$.event._eventListDictionary[obj.eventsListUID])&&l[type]){for(var k in l[type]){if(obj.removeEventListener){obj.removeEventListener(type,l[type][k],false);}
else if(obj.detachEvent){obj.detachEvent('on'+type,l[type][k]);}}}
if(obj['on'+type]){obj['on'+type]=null;}
if(l){l[type]=null;delete l[type];}
return true;},getEvent:function(evt){var evt=evt||window.event,c,cnt;if(!evt&&!tvp.$.userAgent.ie){c=tvp.$.event.getEvent.caller,cnt=1;while(c){evt=c.arguments[0];if(evt&&Event==evt.constructor){break;}
else if(cnt>32){break;}
c=c.caller;cnt++;}}
return evt;},getButton:function(evt){var e=tvp.$.event.getEvent(evt);if(!e){return-1}
if(tvp.$.userAgent.ie){return e.button-Math.ceil(e.button/2);}
else{return e.button;}},getTarget:function(evt){var e=tvp.$.event.getEvent(evt);if(e){return e.srcElement||e.target;}
else{return null;}},getCurrentTarget:function(evt){var e=tvp.$.event.getEvent(evt);if(e){return e.currentTarget||document.activeElement;}
else{return null;}},cancelBubble:function(evt){evt=tvp.$.event.getEvent(evt);if(!evt){return false}
if(evt.stopPropagation){evt.stopPropagation();}
else{if(!evt.cancelBubble){evt.cancelBubble=true;}}},preventDefault:function(evt){evt=tvp.$.event.getEvent(evt);if(!evt){return false}
if(evt.preventDefault){evt.preventDefault();}
else{evt.returnValue=false;}},mouseX:function(evt){evt=tvp.$.event.getEvent(evt);return evt.pageX||(evt.clientX+(document.documentElement.scrollLeft||document.body.scrollLeft));},mouseY:function(evt){evt=tvp.$.event.getEvent(evt);return evt.pageY||(evt.clientY+(document.documentElement.scrollTop||document.body.scrollTop));},getRelatedTarget:function(ev){ev=tvp.$.event.getEvent(ev);var t=ev.relatedTarget;if(!t){if(ev.type=="mouseout"){t=ev.toElement;}
else if(ev.type=="mouseover"){t=ev.fromElement;}
else{}}
return t;}};tvp.$.cookie={set:function(name,value,domain,path,hour){if(hour){var today=new Date();var expire=new Date();expire.setTime(today.getTime()+3600000*hour);}
document.cookie=name+"="+value+"; "+(hour?("expires="+expire.toGMTString()+"; "):"")+(path?("path="+path+"; "):"path=/; ")+(domain?("domain="+domain+";"):("domain="+window.location.host+";"));return true;},get:function(name){var r=new RegExp("(?:^|;+|\\s+)"+name+"=([^;]*)");var m=document.cookie.match(r);return(!m?"":m[1]);},del:function(name,domain,path){var exp=new Date();exp.setTime(exp.getTime()-1);document.cookie=name+"=; expires="+exp.toGMTString()+";"+(path?("path="+path+"; "):"path=/; ")+(domain?("domain="+domain+";"):("domain="+window.location.host+";"));}};tvp.report=(function(){var isFree=true;var reportObj=null;var urlList=[];function errorHandle(){if(urlList.length==0){isFree=true;reportObj=null;return;}
this.src=urlList.splice(0,1);isFree=false;}
function reportUrl(url){if(!url||!/^(?:ht|f)tp(?:s)?\:\/\/(?:[\w\-\.]+)\.\w+/i.test(url)){return;}
if(reportObj==null){reportObj=document.createElement("img");reportObj.src=url;reportObj.onerror=errorHandle;isFree=false;return;}
if(isFree){reportObj.src=url;isFree=false;return;}
else{urlList.push(url);}}
return function(param){if(tvp.$.isString(param)){reportUrl(param);return;}
if(tvp.$.getType(param)=="object"){var r=[];for(var i in param){r.push(i+"="+encodeURIComponent(""+param[i]));}
var url="http://rcgi.video.qq.com/web_report?";reportUrl(url+r.join("&"));}}})()
tvp.$.ajax=(function(){var jsonpObj,gcGet,paramToStr,createFunName,callError,callSuccess,callComplete;gcGet=function(callbackName,script){script.parentNode.removeChild(script);window[callbackName]=undefined;try{delete window[callbackName];}
catch(e){}};paramToStr=function(parameters,encodeURI){var str="",key,parameter;for(key in parameters){if(parameters.hasOwnProperty(key)){key=encodeURI?encodeURIComponent(key):key;parameter=encodeURI?encodeURIComponent(parameters[key]):parameters[key];str+=key+"="+parameter+"&";}}
return str.replace(/&$/,"");};createFunName=function(){return"cb_"+tvp.$.createGUID(16);};callError=function(callback,errorMsg){if(typeof(callback)!=='undefined'){callback(errorMsg);}};callSuccess=function(callback,data){if(typeof(callback)!=='undefined'){callback(data);}};callComplete=function(callback){if(typeof(callback)!=='undefined'){callback();}};jsonpObj={};jsonpObj.init=function(options){var key;for(key in options){if(options.hasOwnProperty(key)){jsonpObj.options[key]=options[key];}}
return true;};jsonpObj.get=function(options){options=options||{};var url=options.url,callbackParameter=options.callbackParameter||'callback',parameters=options.data||{},script=document.createElement('script'),callbackName=createFunName(),prefix="?";if(!url){return;}
parameters[callbackParameter]=callbackName;if(url.indexOf("?")>=0){prefix="&";}
url+=prefix+paramToStr(parameters,true);window[callbackName]=function(data){if(typeof(data)==='undefined'){callError(options.error,'Invalid JSON data returned');}
else{callSuccess(options.success,data);}
gcGet(callbackName,script);callComplete(options.complete);};script.setAttribute('src',url);document.getElementsByTagName('head')[0].appendChild(script);tvp.$.event.add(script,'error',function(){gcGet(callbackName,script);callComplete(options.complete);callError(options.error,'Error while trying to access the URL');});};return jsonpObj.get})();tvp=tvp||{};tvp.common={isUseHtml5:function(){var ua=navigator.userAgent,av=tvp.$.userAgent.android,m=null
if(/ipad|ipod|iphone|lepad_hls|IEMobile/ig.test(ua)){return true;}
if(m=ua.match(/MQQBrowser\/(\d+\.\d+)/i)){if(parseFloat(m&&m[1]?m[1]:"3.0",10)>=4.2){return true;}}
if(av>=4){if(ua.indexOf("MI-ONE")!=-1){return true;}
if(m=ua.match(/MicroMessenger\/((\d+)\.(\d+))\.(\d+)/)){if(m[1]>=4.2){return true;}}
return tvp.common.isSupportMP4();}
return false;},isLiveUseHTML5:function(){if(/ipad|ipod|iphone/ig.test(navigator.userAgent)){return true;}
var ua=navigator.userAgent,m=null;if(m=ua.match(/MQQBrowser\/(\d+\.\d+)/i)){if(parseFloat(m&&m[1]?m[1]:"3.0",10)>=4.3){return true;}}
return false;},isSupportMP4:function(){var video=document.createElement("video");if(typeof video.canPlayType=="function"&&video.canPlayType('video/mp4; codecs="avc1.42E01E"')=="probably"){return true;}
return false;},isEnforceMP4:function(){var av=tvp.$.userAgent.android,ua=navigator.userAgent,m=null;if(!!av){if(tvp.$.userAgent.firefox){return true;}
if(av>=4.0&&(m=ua.match(/MQQBrowser\/(\d+\.\d+)/i))){if(parseFloat(m&&m[1]?m[1]:"3.0",10)<4.0){return true;}}}
return false;},getUin:function(isLeak){var skey=tvp.$.cookie.get("skey"),lskey=tvp.$.cookie.get("lskey"),suin="",uin=0,useLeak=false;isLeak=typeof(isLeak)=="undefined"?false:true;useLeak=!!isLeak&&lskey!="";if(!useLeak&&skey==""){return 0;}
suin=tvp.$.cookie.get("uin");if(suin==""){if(!!useLeak){suin=tvp.$.cookie.get("luin");}}
uin=parseInt(suin.replace(/^o0*/g,""),10);if(isNaN(uin)||uin<=10000){return 0;}
return uin;},getSKey:function(isLeak){var skey=tvp.$.cookie.get("skey"),lskey=tvp.$.cookie.get("lskey"),key="";if(!!isLeak){if(skey!=""&&lskey!=""){key=[skey,lskey].join(";");}else{key=skey||lskey;}}else{key=skey;}
return key;},openLogin:function(){},getVideoSnap:function(lpszVID,idx){var szPic;var uin;var hash_bucket=10000*10000;var object=lpszVID;if(lpszVID.indexOf("_")>0){var arr=lpszVID.split("_");lpszVID=arr[0];idx=parseInt(arr[1]);}
var uint_max=0x00ffffffff+1;var hash_bucket=10000*10000;var tot=0;for(var inx=0;inx<lpszVID.length;inx++){var nchar=lpszVID.charCodeAt(inx);tot=(tot*32)+tot+nchar;if(tot>=uint_max)
tot=tot%uint_max;}
uin=tot%hash_bucket;if(idx==undefined)
idx=0;if(idx==0){szPic=["http://vpic.video.qq.com/",uin,"/",lpszVID,"_160_90_3.jpg"].join("");}else{szPic=["http://vpic.video.qq.com/",uin,"/",lpszVID,"_","160_90_",idx,"_1.jpg"].join("");}
return szPic;}};tvp.version=(function(){var vOcx="0.0.0.0",vflash="0.0.0",actObj;function changeVerToString(nVer){if(checkVerFormatValid(nVer)){return nVer;}
if(/\d+/i.test(nVer)){var nMain=parseInt(nVer/10000/100,10);var nSub=parseInt(nVer/10000,10)-nMain*100;var nReleaseNO=parseInt(nVer,10)-(nMain*100*10000+nSub*10000);strVer=nMain+"."+nSub+"."+nReleaseNO;return strVer;}
return nVer;}
function checkVerFormatValid(version){return(/^(\d+\.){2}\d+(\.\d+)?$/.test(version));};return{getOcx:function(enableCache){if(tvp.$.isUndefined(enableCache)){enableCache=true;}
if(!!enableCache&&vOcx!="0.0.0.0"){return vOcx;}
if(!!tvp.$.userAgent.ie){try{actObj=new ActiveXObject(QQLive.config.PROGID_QQLIVE_INSTALLER);if(typeof actObj.getVersion!="undefined"){vOcx=actObj.GetVersionByClsid(QQLiveSetup.config.OCX_CLSID);}}catch(err){}}else if(tvp.$.userAgent.isNotIESupport()){var plugs=navigator.plugins,plug;if(!tvp.$.isUndefined(plugs.namedItem)){plug=plugs.namedItem("腾讯视频");}
if(!plug){for(var i=0,len=plugs.length;i<len;i++){if(plugs[i]&&plugs[i].name=="腾讯视频"||plugs[i].filename=="npQQLive.dll"){plug=plugs[i];break;}}}
if(!!plug){if(!tvp.$.isUndefined(plug.version)){vOcx=plug.version;}else{var r;var desc=plug.description;if(r=desc.match(/version:((\d+\.){3}(\d+)?)/)){vOcx=r[1];}}}}
vOcx=changeVerToString(vOcx);return vOcx;},getFlash:function(){if(vflash!="0.0.0"){return vflash;}
var swf=null,ab=null,ag=[],S="Shockwave Flash",t=navigator,q="application/x-shockwave-flash",R="SWFObjectExprInst"
if(!!tvp.$.userAgent.ie){try{swf=new ActiveXObject('ShockwaveFlash.ShockwaveFlash');if(swf){ab=swf.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)]}}}catch(exp){}}else if(!tvp.$.isUndefined(t.plugins)&&tvp.$.getType(t.plugins[S])=="plugin"){ab=t.plugins[S].description;if(ab&&!(!tvp.$.isUndefined(t.mimeTypes)&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0;}}
vflash=ag.join(".");return vflash;},getFlashMain:function(){return parseInt(tvp.version.getFlash().split(".")[0],10);}}})();tvp.emptyFn=function(){};tvp.VideoInfo=function(){var _vid="",_vidlist="",_vidCnt=0,_idx=0,_origvid="",_channelId="",$me=this;var data={prefix:0,tail:0,tagStart:0,tagEnd:0,duration:"",historyStart:0,pay:0,coverId:"",title:"",isLookBack:0,tstart:0,CDNType:0,vFormat:"",LiveReTime:""}
function getFirstVid(vid){if(vid.indexOf("|")<0)
return vid;return vid.substring(0,vid.indexOf("|"));};function getRealVid(vid){if(vid.indexOf("_")<0)
return vid;return vid.split("_")[0];};function getIdx(vid){if(vid.indexOf("_")<0)
return 0;return parseInt(vid.split("_")[1]);};function getRealVidList(vidlist){var arr=[];var origarr=vidlist.split("|");for(var i=0;i<origarr.length;i++){arr.push(getRealVid(origarr[i]));}
return arr.join("|");};for(var k in data){new function(){var p=k.charAt(0).toUpperCase()+k.substr(1),_k=k;$me["set"+p]=function(v){$me.setDataVal(_k,v);}}}
for(var p in data){new function(){var k=p;$me["get"+k.charAt(0).toUpperCase()+k.substr(1)]=function(){return $me.getDataVal(k);}}}
this.getData=function(){return data;}
this.getDataVal=function(v){return data[v];}
this.setDataVal=function(k,v){data[k]=v;}
this.setVid=function(vid){if(!tvp.$.isString(vid)){return;}
_origvid=vid;if(vid.indexOf("|")<0){var id=getRealVid(vid)
_vid=id;_idx=getIdx(vid);_vidlist=id;}
else{var arr=vid.split("|");_vid=getRealVid(arr[0]);_idx=getIdx(arr[0]);_vidlist=getRealVidList(vid);}
_vid=tvp.$.filterXSS(_vid);_vidlist=tvp.$.filterXSS(_vidlist);};this.getVid=function(){return _vid;};this.getVidList=function(){return _vidlist;}
this.getIdx=function(){return _idx;}
this.getTimelong=function(){if(!data.duration){return 0;}
var arrDur=data.duration.split("|");var sec=0;for(var i=0,len=arrDur.length;i<len;i++){sec+=parseInt(arrDur[i]);}
return sec;}
this.getEndOffset=function(){return this.getTimelong()-this.getTail();}
this.setChannelId=function(cnlid){if(!tvp.$.isString(cnlid)){return;}
_channelId=cnlid;}
this.getChannelId=function(cnlid){return _channelId;}
this.getFullVid=function(){if(this.getIdx()==0){return getRealVid(this.getVid());}
return(getRealVid(this.getVid())+"_"+this.getIdx());}
this.clear=function(){_vid="";_vidlist="";_vidCnt=0;_idx=0;_channelId="";for(var k in data){if(typeof data[k]=="string"){data[k]="";}
else{data[k]=0;}}};this.clone=function(obj){obj.setVid(_origvid);obj.setChannelId(_channelId);for(var k in data){var n=k.charAt(0).toUpperCase()+k.substr(1);obj["set"+n](this["get"+n]());}}
this.getVideoSnap=function(){var img=[];img[0]=tvp.common.getVideoSnap(_vid,_idx);img[1]=img[0].replace("_160_90_3","_1");img[2]=img[1].replace("_1.jpg",".png");return img;}
this.getMP4Url=function(onSuccess,onError){function error(errcode){if(tvp.$.isFunction(onError)){onError(errcode);}}
tvp.$.ajax({"url":tvp.path.html5_video_cgi,"data":{"vid":_vid,"charge":data.pay>0?1:0},"dataType":"jsonp","success":function(json){var url="";if(!json||!json.s){error(50);return;}
else if(json.s!="o"){error(json.em||50);return;}
else if(!json.vd||!json.vd.vi||!tvp.$.isArray(json.vd.vi)){error(68);return;}
else if(json.vd.vi.length>0){for(var i=0;i<json.vd.vi.length;i++){if(json.vd.vi[i].st!=2)
continue;url=json.vd.vi[i].url;if(url.indexOf(".mp4")<0)
continue;if(!!url&&url.length>0)
break;}}
if(tvp.$.isFunction(onSuccess)){onSuccess(url);}},"error":function(){error(50)}});}};tvp.PLAYTYPE={LIVE:"1",VOD:"2"}
tvp.BasePlayer=function(){this.eventList=["inited","playing","ended","allended","pause","timeupdate","getnext","error","stop","fullscreen","change","write","flashpopup","getnextenable","msg","liveerror"];this.mapToShellFun=["log"];this.params={};this.hijackFun=["getPlayer","getCurVideo","showPlayer","hidePlayer","pause","getPlaytime","getPlayerType"];(function(me){var arr=["init","setCurVideo","addParam","write","getPreVid","callback","setPlayerReady"];arr=arr.concat(me.hijackFun);for(var i=0,len=arr.length;i<len;i++){me[arr[i]]=tvp.emptyFn;}
for(var i=0,len=me.eventList.length;i<len;i++){me["on"+me.eventList[i]]=tvp.emptyFn;}
for(var i=0,len=me.mapToShellFun.length;i<len;i++){me[me.mapToShellFun[i]]=tvp.emptyFn;}})(this);this.write=function(id){tvp.$("#"+id).html("");}
this.log=function(msg){if(window.console){window.console.log(msg);}}
this.addParam=function(k,v){this.params[k]=v;}}
tvp.BaseFlash=function(){var $me=this;this.swfPathRoot="http://imgcache.qq.com/tencentvideo_v1/player/";this.playerid="";this.getFlashVar=function(){return"";}
this.getFlashHTML=function(){var flashvar=this.getFlashVar();var swfurl="";if(tvp.$.isString(this.params.swfurl)&&this.params.swfurl.length>0){swfurl=this.params.swfurl;}else{swfurl=this.swfPathRoot+this.params.swftype.replace(/[^\w+]/ig,"")+".swf";swfurl+="?max_age=86400&v=20130507"
var ua=navigator.userAgent;if(ua.indexOf("Maxthon")>0||ua.indexOf("TencentTraveler")>0){swfurl+="&_="+tvp.$.now();}}
swfurl=tvp.$.filterXSS(swfurl);this.playerid=tvp.$.filterXSS(this.params.playerid);if(!this.playerid){this.playerid="tenvideo_flash_player_"+new Date().getTime();}
var propStr="";propStr+='    <param name="allowScriptAccess" value="always" />\n';propStr+='    <param name="movie" value="'+swfurl+'" />\n';propStr+='    <param name="quality" value="high" />\n';propStr+='    <param name="allowFullScreen" value="true"/>\n';propStr+='    <param name="play" value="true" />\n';propStr+='    <param name="wmode" value="'+tvp.$.filterXSS(this.params.wmode)+'" />\n';propStr+='    <param name="flashvars" value="'+flashvar+'"/>\n';propStr+='    <param name="type" value="application/x-shockwave-flash" />\n';propStr+='    <param name="pluginspage" value="http://get.adobe.com/cn/flashplayer/" />\n';var str="";if(!!tvp.$.browser.msie||!!tvp.$.userAgent.android){str+='<object type="application/x-shockwave-flash" data="'+swfurl+'" width="'+this.width+'" height="'+this.height+'" id="'+this.playerid+'" align="middle">\n';str+=propStr;str+=' <div class="tvp_player_noswf">未检测到flash插件或者您的设备暂时不支持flash播放</div>';str+='</object>'}else{str+='<embed wmode="'+tvp.$.filterXSS(this.params.wmode)+'" flashvars="'+flashvar+'" src="'+swfurl+'" quality="high" name="'+this.playerid+'" id="'+this.playerid+'" bgcolor="#000000" width="'+this.width+'" height="'+this.height+'" align="middle" allowScriptAccess="always" allowFullScreen="true"  type="application/x-shockwave-flash" pluginspage="http://get.adobe.com/cn/flashplayer/" />';}
return str;}}
if(typeof tvp.BaseFlash.maxId!="number"){tvp.BaseFlash.maxId=0;}
tvp.BaseFlash.prototype=new tvp.BasePlayer();var preplay=tvp.emptyFn,nextplay=tvp.emptyFn,attrationstop=tvp.emptyFn,thisplay=tvp.emptyFn,playerInit=tvp.emptyFn;tvp.FlashPlayer=function(vWidth,vHeight){var $me=this,curVideo=new tvp.VideoInfo(),flashobj=null,playerid,pauseTime=-1;tvp.BaseFlash.maxId++;var pauseCheckTimer=null;var flashvarskey={"adplay":1,"oid":"","cid":"","showcfg":1,"showend":"","tpid":0,"searchbar":"","loadingswf":"","pic":"","share":"1"}
var replaceFlashKey={"flashskin":"","flashshownext":""}
this.params={"swfurl":"","swftype":"TPout","wmode":"window","listtype":2,"autoplay":1,"playerid":"","extvars":{},"ispay":0,"starttips":0};this.params=tvp.$.extend(this.params,flashvarskey);this.params=tvp.$.extend(this.params,replaceFlashKey);this.hijackFun=this.hijackFun.concat(["getFlashVar"]);this.getPlayerType=function(){return"flash";}
this.getFlashVar=function(){var flashvar='';flashvar+='vid='+curVideo.getVidList();flashvar+='&autoplay='+tvp.$.filterXSS($me.params["autoplay"]);flashvar+=$me.params.listtype!=0?('&list='+$me.params.listtype):'';if(curVideo.getIdx()>0&&curVideo.getTagEnd()-curVideo.getTagStart()>0){flashvar+="&attstart="+tvp.$.filterXSS(curVideo.getTagStart());flashvar+="&attend="+tvp.$.filterXSS(curVideo.getTagEnd());}
if(curVideo.getTimelong()>0){flashvar+='&duration='+(curVideo.getTimelong()||"");}
if(curVideo.getHistoryStart()>0){flashvar+="&history="+tvp.$.filterXSS(curVideo.getHistoryStart());}
if(curVideo.getTstart()>0){flashvar+="&t="+tvp.$.filterXSS(curVideo.getTstart());}
if(curVideo.getIdx()==0&&(curVideo.getPrefix()>0||curVideo.getTail()>0)){var _piantou=curVideo.getPrefix(),_endoffset=curVideo.getEndOffset();if(_piantou>0||_endoffset){flashvar+="&vstart="+tvp.$.filterXSS(_piantou);flashvar+="&vend="+tvp.$.filterXSS(_endoffset);}}
tvp.$.each(flashvarskey,function(el){if(!tvp.$.isUndefined($me.params[el])&&((tvp.$.isString($me.params[el])&&$me.params[el].length>0)||typeof $me.params[el]=="number")){flashvar+="&"+el+"="+tvp.$.filterXSS($me.params[el]);}});tvp.$.each(replaceFlashKey,function(el){if(!tvp.$.isUndefined($me.params[el])&&((tvp.$.isString($me.params[el])&&$me.params[el].length>0)||typeof $me.params[el]=="number")){flashvar+="&"+el.replace("flash","")+"="+tvp.$.filterXSS($me.params[el]);}});if(curVideo.getPay()>0){flashvar+="&pay="+tvp.$.filterXSS(curVideo.getPay());}
if(curVideo.getTitle().length>0){flashvar+="&title="+encodeURIComponent(curVideo.getTitle());}
if(!!curVideo.getIdx()){flashvar+="&exid=k"+tvp.$.filterXSS(curVideo.getIdx());}
if(curVideo.getCDNType()>0){flashvar+="&cdntype="+curVideo.getCDNType();}
for(var p in this.params.extvars){flashvar+=["&",tvp.$.filterXSS(p),"=",encodeURIComponent(this.params.extvars[p])].join("");}
return flashvar;};this.width=tvp.$.filterXSS(vWidth);this.height=tvp.$.filterXSS(vHeight);this.setCurVideo=function(videoinfo){if(videoinfo instanceof tvp.VideoInfo){videoinfo.clone(curVideo);}};this.getCurVideo=function(){return curVideo;}
this.getCurVid=function(){return(curVideo instanceof tvp.VideoInfo)?curVideo.getVid():"";}
this.getCurVidList=function(){return(curVideo instanceof tvp.VideoInfo)?curVideo.getVidList():"";}
this.write=function(id){var el=tvp.$.get(id);if(!el)
return;var str=this.getFlashHTML();el.innerHTML=str;flashobj=tvp.$.browser.ie?document.getElementById(this.playerid):document.embeds[this.playerid];$me.onwrite(curVideo.getVid());if(document.domain==""&&document.location.href.substr(0,5)=="file:"&&!!window.console&&(typeof window.console.warn=="function")){window.console.warn("本页面包含腾讯视频统一播放器，如果您希望调用统一播放器API接口，请使用http形式打开，本地双击html文件可能会导致API失效");}};this.getPlayer=function(){return flashobj;}
this.play=function(video){if(!flashobj){throw new Error("未找到视频播放器对象，请确认flash播放器是否存在");}
if(tvp.$.isUndefined(video)){if(tvp.$.isFunction(flashobj.setPlaytime)){flashobj.setPlaytime(pauseTime);pauseTime=-1;}
return;}
if(!(video instanceof tvp.VideoInfo)){return;}
var vstart=0,vend=0,tagstart=0,tagend=0;if(video.getIdx()==0){vstart=video.getPrefix()||0;vend=video.getEndOffset()||0;}else{tagstart=video.getTagStart();tagend=video.getTagEnd();}
var extid=video.getIdx()==0?0:("k"+video.getIdx());if(curVideo.getVidList()!=video.getVidList()||video.getIdx()==0){var videoInfo={vid:video.getVidList()||video.getIdx(),duration:video.getTimelong()||"",start:tagstart,end:tagend,history:video.getHistoryStart()||0,vstart:vstart,vend:vend,title:video.getTitle()||"",exid:extid,pay:video.getPay(),cdntype:curVideo.getCDNType()};if($me.params["starttips"]==0){videoInfo["t"]=video.getHistoryStart()||0;}
if(typeof flashobj.loadAndPlayVideoV2=='function'){flashobj.loadAndPlayVideoV2(videoInfo);}}else if(video.getTagEnd()-video.getTagStart()>0){flashobj.attractionUpdate(video.getTagStart(),video.getTagEnd(),extid);}
$me.setCurVideo(video);$me.onchange(video.getFullVid());if(typeof flashobj.setNextEnable=="function"){flashobj.setNextEnable($me.ongetnextenable(curVideo.getFullVid())?1:0);}};this.showPlayer=function(){if(!flashobj)
return;var width=""+$me.width,height=""+$me.height;if(width.indexOf("px")<0){width=parseInt(width)+"px";}
if(height.indexOf("px")<0){height=parseInt(height)+"px";}
flashobj.style.width=width;flashobj.style.height=height;}
this.hidePlayer=function(){if(!flashobj)
return;flashobj.style.width="1px";flashobj.style.height="1px";}
this.pause=function(){if(!!flashobj&&!tvp.$.isUndefined(flashobj.getPlaytime)&&!tvp.$.isUndefined(flashobj.pauseVideo)){pauseTime=flashobj.getPlaytime();flashobj.pauseVideo();pauseCheckTimer=setInterval(function(){try{if(!flashobj||tvp.$.isUndefined(flashobj.getPlaytime)){clearInterval(pauseCheckTimer);pauseCheckTimer=null;}else if(flashobj.getPlaytime()!=pauseTime){clearInterval(pauseCheckTimer);pauseCheckTimer=null;}}catch(err){if(!!pauseCheckTimer){clearInterval(pauseCheckTimer);pauseCheckTimer=null;}}},50);}}
this.getPlaytime=function(){if(!!flashobj&&tvp.$.isFunction(flashobj.getPlaytime)){return flashobj.getPlaytime();}
return-1;}
window.thisplay=function(){$me.onplaying($me.getCurVid());}
window.playerInit=function(){if(typeof flashobj.setNextEnable=="function"){flashobj.setNextEnable($me.ongetnextenable(curVideo.getFullVid())?1:0);}
$me.oninited();}
window.attrationstop=window.nextplay=function(vid){$me.onended(vid);var video=$me.ongetnext(vid);if(!video){$me.onallended();return;}
$me.play(video);}
window.__flashplayer_ismax=function(ismax){$me.onfullscreen(ismax);};window.__tenplay_popwin=function(){if(tvp.$.isFunction($me.onflashpopup)){$me.onflashpopup();}}
window._showPlayer=function(){$me.showPlayer();}
window._hidePlayer=function(){$me.hidePlayer();}}
tvp.FlashPlayer.prototype=new tvp.BaseFlash();tvp.Html5Player=function(vWidth,vHeight){var $me=this,curVideo=new tvp.VideoInfo(),videoObj=null,$videoTag,useHLS=false,curVid,isPlayerHiding=false,videodata={"vurl":"","vt":0,"format":2,"duration":0,"bt":0,"platform":getPlatform()},reportRid="",reportPid="",lastReportPlayEndVid="",playerid="",historyTimer=null,reportHistoryTimer=1e4;var __testCnt=1;var __testNumLimit=5;function TimerObject(){this.start=tvp.$.now();this.end=0;};TimerObject.prototype={getTimelong:function(){this.end=tvp.$.now();if(this.end<=0||this.start<=0)
return 0;var a=this.end-this.start;return(a<=0?0:a);},getSeconds:function(){return parseInt(this.getTimelong()/1000,10);}};var reportTimer={};function initReportParam(){reportTimer["step_5"]=null;reportTimer["step_5"]=new TimerObject();reportTimer["step_31_cnt"]=0;}
this.width=tvp.$.filterXSS(vWidth);this.height=tvp.$.filterXSS(vHeight);this.params={"autobuffer":"","controls":"","preload":"metadata","autoplay":"","x-webkit-airplay":"","playerid":"","pic":"disabled"};this.mapToShellFun=["log","isUseHLS"];this.eventList=this.eventList.concat(["notpay","notlogin"]);this.getPlayerType=function(){return"html5";}
function getMp4Key(){if(tvp.$.userAgent.isiPhone||/ipod/.test(navigator.userAgent))
return"v3010";if(tvp.$.userAgent.isiPad)
return"v4010";if(!!tvp.$.userAgent.android){if(/Tablet/.test(navigator.userAgent)||screen.width>=600){return"v6010";}
return"v5010"}
if(/IEMobile/.test(navigator.userAgent)){return"v7010";}
return"v1010";}
function getBusinessId(){if(/MicroMessenger/i.test(navigator.userAgent)){return 6;}
var host="";if(document.location.href.indexOf("http://v.qq.com/iframe/")>=0&&window!=top){var l=document.referrer;if(l!=""){var link=document.createElement("a");link.href=l;host=link.hostname;link=null;delete link;}}
if(host==""){host=document.location.hostname||document.location.host;}
keys=[{r:/(\w+\.)?weixin\.qq\.com$/i,v:6},{r:/^(v|film)\.qq\.com$/i,v:1},{r:/^news\.qq\.com$/i,v:2},{r:/(\w+\.)?qzone\.qq\.com$/i,v:3},{r:/(\w+\.)?t\.qq\.com$/i,v:5},{r:/^3g\.v\.qq\.com$/i,v:8},{r:/^m\.v\.qq\.com$/i,v:10}];host=host.toLowerCase();for(var i=0,len=keys.length;i<len;i++){var key=keys[i];if(key.r.test(host)){return key.v;}}
return 7;}
function getDeviceId(){var u=tvp.$.userAgent,ua=navigator.userAgent;if(!!u.isiPad)return 1;if(!!u.windows){if(/Touch/i.test(ua))return 8;if(/Phone/i.test(ua))return 7;return 2;}
if(!!u.android){if(/Tablet/.test(ua))return 5;return 3;}
if(!!u.isiPhone)return 4;if(!!u.macs)return 9;return 10;}
function getPlatform(){var bussId=getBusinessId(),deviceId=getDeviceId();return bussId*10000+deviceId*100+1;}
function report(step,val,extData){if(isNaN(val)||val<=0||val>9000000){return;}
var url="http://rcgi.video.qq.com/report/play?";var r=[],pa={"step":step,"val":val,"ptag":tvp.$.cookie.get("ptag"),"version":"TenPlayerHTML5V1.1","ctime":tvp.$.getISOTimeFormat(),"vid":curVid,"rid":reportRid,"pid":reportPid};if(typeof extData=="object"){tvp.$.extend(pa,extData);}
tvp.$.extend(pa,videodata);for(var p in pa){var v=pa[p];if(isNaN(v)){v=encodeURIComponent(""+v);}
r.push(p+"="+v);}
url+=r.join("&");tvp.report(url);}
function getGetKeyFormat(cfg,fi){for(var i=0,len=fi.length;i<len;i++){if(fi[i].name==cfg.fmt){return fi[i].id;}}
for(var i=0,len=fi.length;i<len;i++){if(fi[i].name=="msd"){return fi[i].id;}}
for(var i=0,len=fi.length;i<len;i++){if(fi[i].name=="mp4"){return fi[i].id;}}
return-1;};function getVideoUrlByVid(vid,hls,callbacks){var globalCfg={isPay:curVideo.getPay()>0?1:0,vid:"",fmt:"mp4"};var reportData={val1:0,val2:0},ajaxTimelong=0,requirePlatForm=11001;reportRid=tvp.$.createGUID(48);reportPid=tvp.$.createGUID(48);function error(errCode){if(hls){getVideoUrlByVid(vid,false,callbacks);}else if(tvp.$.getType(callbacks)=="object"&&tvp.$.isFunction(callbacks.onError)){callbacks.onError(errCode);}
videodata.duration=0;videodata.vt=0;videodata.vurl="";videodata.bt=0;reportData["val1"]=errCode==500?2:(errCode==50?4:3);reportData["val2"]=errCode==50?601:errCode;}
function loadVideoURLFromGetURL(cfg){var s={};tvp.$.extend(tvp.$.extend(s,globalCfg),cfg);tvp.$.ajax({"url":(hls&&!s.isPay)?tvp.path.hls_video_cgi:tvp.path.html5_video_cgi,"data":{"vid":s.vid,"charge":s.isPay},"dataType":"jsonp","success":function(json){ajaxTimelong=reportTimer["step_1011"].getTimelong();if(!json||!json.s){error(50);return;}else if(json.s!="o"){error(json.em||50);return;}else if(!json.vd||!json.vd.vi||!tvp.$.isArray(json.vd.vi)){error(68);return;}
var videourl=[],charge=-2;for(var i=0;i<json.vd.vi.length;i++){charge=json.vd.vi[i].ch;if(json.vd.vi[i].st!=2)
continue;var url=json.vd.vi[i].url.toLowerCase();if(url.indexOf(".mp4")<0&&url.indexOf(".m3u8")<0)
continue;if(!!json.vd.vi[i].url){var d=json.vd.vi[i];videourl.push(d.url);try{videodata.duration=parseInt(d.dur);videodata.vt=d.vt;videodata.vurl=d.url;videodata.bt=curVideo.getTimelong()||videodata.duration;}catch(e){}
break;}}
if(videourl.length==0){$me.onerror(charge);return;}
curVid=vid;reportData["val1"]=1;report(1010,ajaxTimelong,reportData);if(tvp.$.getType(callbacks)=="object"&&tvp.$.isFunction(callbacks.onSuccess)){callbacks.onSuccess(json,videourl);}},"error":function(){ajaxTimelong=reportTimer["step_1011"].getTimelong();error(500);}});};function loadVideoURLFromGetInfo(cfg){var s={},infoData={};tvp.$.extend(tvp.$.extend(s,globalCfg),cfg);tvp.$.ajax({url:"http://vv.video.qq.com/getinfo",data:{"vids":s.vid,"platform":requirePlatForm,"charge":s.isPay?1:0,"otype":"json"},dataType:"jsonp",success:function(infojson){if(!infojson||!infojson.s){error(50);return;}
if(infojson.s!="o"){error(infojson.em||50);return;}
if(!infojson.vl||!infojson.vl.vi||!tvp.$.isArray(infojson.vl.vi)||infojson.vl.cnt==0){error(68)
return;}
var vi=infojson.vl.vi[0];if(vi.fst!=5||!vi.ul||!tvp.$.isArray(vi.ul.ui)||vi.ul.ui.length==0){error(62);return;}
if(vi.st!=2){if(vi.st!=8){error(62);return;}
error(83,vi.ch);}
var ui=vi.ul.ui[0];infoData["br"]=vi.br;infoData["path"]=ui.url;infoData["fn"]=vi.fn;infoData["td"]=vi.td;infoData["fiid"]=getGetKeyFormat(s,infojson.fl.fi);infoData["vt"]=ui.vt;tvp.$.ajax({url:"http://vv.video.qq.com/getkey",data:{"otype":"json","vid":s.vid,"format":infoData.fiid,"filename":infoData.fn,"platform":requirePlatForm,"vt":infoData.vt,"charge":s.isPay?1:0},dataType:"jsonp",success:function(keyjson){ajaxTimelong=reportTimer["step_1011"].getTimelong();if(!keyjson||!keyjson.s){error(50);return;}
if(keyjson.s!="o"){error(keyjson.em||50);return;}
var videourl=[],charge=-2;videourl=infoData["path"]+infoData["fn"]+"?vkey="+keyjson.key+"&br="+infoData["br"]+"&platform=2&fmt="+s.fmt+"&level="+keyjson.level+"&sdtfrom="+getMp4Key();if(tvp.$.isString(keyjson.sha)&&keyjson.sha.length>0){videourl+="&sha="+keyjson.sha;}
videodata.duration=parseInt(infoData.td);videodata.vt=infoData.vt;videodata.vurl=videourl;videodata.bt=curVideo.getTimelong()||videodata.duration;curVid=vid;reportData["val1"]=1;report(1011,ajaxTimelong,reportData);if(tvp.$.getType(callbacks)=="object"&&tvp.$.isFunction(callbacks.onSuccess)){callbacks.onSuccess(infojson,videourl);}},error:function(){ajaxTimelong=reportTimer["step_1011"].getTimelong();error(500);}});},error:function(){ajaxTimelong=reportTimer["step_1011"].getTimelong();error(500);}});};if(!tvp.$.isString(vid))
return;reportTimer["step_1011"]=new TimerObject();if(hls){loadVideoURLFromGetURL({vid:vid});}else{loadVideoURLFromGetInfo({vid:vid});}};function getVideoTagHtml(){playerid=$me.params.playerid;if(!playerid){playerid="tenvideo_video_player_"+(tvp.$("video").size());}
var str=['<video id="',playerid,'" width="','1px','" height="','1px','"'].join("");for(var p in $me.params){if(p!="playerid"&&$me.params[p]!="disabled"&&$me.params[p]!="0"){str+=" "+(p=="pic"?"poster":p);if($me.params[p]!=""){str+='="'+$me.params[p]+'"';}}}
str+="></video>";return str;}
function getNextVid(){var vidArr=curVideo.getVidList().split("|");var vidIndexOf=tvp.$.inArray(curVid,vidArr);if(vidIndexOf<vidArr.length-1){return vidArr[vidIndexOf+1];}
return"";};function setCurrentTime(v){if(isNaN(v))
return;try{if(!!videoObj){videoObj.currentTime=v;}}catch(e){setTimeout(function(){setCurrentTime(v)},50);}}
function reportPlayEnd(val1){var val=0;if(!!reportTimer&&!!reportTimer["step_5"]){val=reportTimer["step_5"].getSeconds();}
report(5,val,{"val1":val1});}
this.getPlayer=function(){return videoObj;}
this.isUseHLS=function(){return false;}
this.setCurVideo=function(videoinfo){if(videoinfo instanceof tvp.VideoInfo){videoinfo.clone(curVideo);}};this.getCurVideo=function(){return curVideo;};this.playVideoByVid=function(vid){getVideoUrlByVid(vid,useHLS,{"onSuccess":function(json,videourl){$me.playVideoUrl(videourl);report(4,1);},"onError":function(errCode){$me.onerror(errCode);}})}
this.playVideoUrl=function(videoUrl){if(!videoObj)
return;if($videoTag.size()==0)
return;var str="",hls=false;if(tvp.$.isArray(videoUrl)){tvp.$.each(videoUrl,function(i,el){if(i==0&&el.indexOf(".m3u8")>0){hls=true;}
el+=(el.indexOf("?")>0?"&":"?")+"sdtfrom="+getMp4Key();str+=['<source src="',el,'"></source>'].join("");});}else if(tvp.$.isString(videoUrl)){if(videoUrl.indexOf(".m3u8")>0){hls=true;}
videoUrl+=(videoUrl.indexOf("?")>0?"&":"?")+"sdtfrom="+getMp4Key();str+=['<source src="',videoUrl,'"></source>'].join("");}
$videoTag.bind("canplaythrough",function(e){var offset=curVideo.getTagStart()||curVideo.getHistoryStart()||0;if(offset>0){if(hls){setTimeout(function(){setCurrentTime(offset);},500);}else{setCurrentTime(offset);}}
$videoTag.unbind("canplaythrough");});try{videoObj.pause();}catch(e){};videoObj.innerHTML=str;tvp.$("#"+playerid+" > source").unbind("error");tvp.$("#"+playerid+" > source").bind("error",function(){if(!reportTimer["step_6"])
return;var tl=reportTimer["step_6"].getTimelong();report(30,tl,{"val1":0,"val2":useHLS?3:2});reportPlayEnd(3);});if(!isPlayerHiding){this.showPlayer();}
try{videoObj.load()
if($me.params.autoplay!="disabled"&&$me.params.autoplay!="0"){videoObj.play();}}catch(e){}
initReportParam();};this.write=function(id){var el=tvp.$.get(id);if(!el)
return;useHLS=this.isUseHLS();el.innerHTML=getVideoTagHtml();videoObj=tvp.$.get(playerid);$videoTag=tvp.$(videoObj);reportTimer["step_31_cnt"]=0;$videoTag.bind("ended",function(){lastReportPlayEndVid=curVid;reportPlayEnd(1);var nextvid=getNextVid();if(tvp.$.isString(nextvid)&&nextvid.length>0){setCurrentTime(0);$me.playVideoByVid(nextvid);}else{$me.onended(curVid);var nextVideo=$me.ongetnext(curVid);if(!!nextVideo&&nextVideo instanceof tvp.VideoInfo){$me.play(nextVideo);}}
if(typeof _flash_view_history=="function"){clearInterval(historyTimer);if(!!curVideo.getFullVid()||curVideo.getIdx()==0){_flash_view_history(-2,curVideo.getTimelong(),curVideo.getTimelong());}}}).bind("play",function(){reportTimer["step_6"]=new TimerObject();}).bind("canplay",function(){if(!reportTimer["step_6"])
return;var tl=reportTimer["step_6"].getTimelong();report(6,tl,{"val1":1});report(30,tl,{"val1":0,"val2":useHLS?3:2});}).bind("pause",function(){$me.onpause();reportTimer["step_31_cnt"]=0;}).bind("stalled",function(){if(!!reportTimer["step_31"]){reportTimer["step_31"]=null;delete reportTimer["step_31"];}
reportTimer["step_31"]=new TimerObject();}).bind("playing",function(){$me.onplaying(curVid);if(!!reportTimer["step_31"]){var tl=reportTimer["step_31"].getTimelong();report(31,Math.min(10000,tl),{"val1":tl>10000?1:0,"val2":useHLS?3:2,"ptime ":videoObj.currentTime});reportTimer["step_31"]=null;delete reportTimer["step_31"];}});$me.play(curVideo,false);$me.onwrite(curVideo.getFullVid());$me.oninited();};this.play=function(video,isOnChange){if(tvp.$.isUndefined(isOnChange)){isOnChange=true;}
if(!videoObj){throw new Error("未找到视频播放器对象，请确认<video>标签是否存在");}
if(tvp.$.isUndefined(video)){videoObj.play();return;}
if(!video instanceof tvp.VideoInfo){throw new Error("传入的对象不是tvp.VideoInfo的实例");}
if(lastReportPlayEndVid!=curVid&&video.getFullVid()!=curVideo.getFullVid()){reportPlayEnd(2)
lastReportPlayEndVid="";}
video=video||curVideo;if(!!curVid&&curVid==video.getVid()&&video.getIdx()>0&&video.getTagStart()>0){setCurrentTime(video.getTagStart());}else{$me.playVideoByVid(video.getVid());}
if(typeof _flash_view_history=="function"){if(curVideo.getIdx()>0){_flash_view_history(0,0,curVideo.getTimelong());}else{_flash_view_history(-1,0,curVideo.getTimelong());}
clearInterval(historyTimer);historyTimer=setInterval(function(){try{if(!curVideo.getFullVid()||curVideo.getIdx()>0){return;}
var index=tvp.$.inArray(curVid,curVideo.getVidList().split("|"));var arrDur=curVideo.getDuration().split("|");var sec=0;for(var i=0;i<index;i++){sec+=parseInt(arrDur[i]);}
_flash_view_history(-3,parseInt(videoObj.currentTime)+sec,curVideo.getTimelong());}catch(e){}},reportHistoryTimer);};initReportParam();$me.setCurVideo(video);if(!!isOnChange){$me.onchange(video.getFullVid());}};this.showPlayer=function(){if(!videoObj)
return;var widStr=$me.width+"";if(widStr.indexOf("%")>-1||widStr.indexOf("px")>-1){videoObj.style.width=$me.width;}else{videoObj.style.width=$me.width+"px";}
var hiStr=$me.height+"";if(hiStr.indexOf("%")>-1||hiStr.indexOf("px")>-1){videoObj.style.height=$me.height;}else{videoObj.style.height=$me.height+"px";}
isPlayerHiding=!!0;}
this.hidePlayer=function(){if(!videoObj)
return;videoObj.style.width="1px";videoObj.style.height="1px";isPlayerHiding=!!1;}
this.pause=function(){if(!!videoObj){videoObj.pause();}};this.getPlaytime=function(){if(!videoObj)
return-1;return videoObj.currentTime;}}
tvp.Html5Player.maxId=0;tvp.Html5Player.prototype=new tvp.BasePlayer();tvp.MP4Link=function(vWidth,vHeight){var $me=this,curVideo=new tvp.VideoInfo();this.width=tvp.$.filterXSS(vWidth);this.height=tvp.$.filterXSS(vHeight);this.setCurVideo=function(videoinfo){if(videoinfo instanceof tvp.VideoInfo){videoinfo.clone(curVideo);}};this.write=function(id){var el=tvp.$.get(id);if(!el){return;}
curVideo.getMP4Url(function(url){var imgsrc="http://i.gtimg.cn/qqlive/images/20121119/i1353305744_1.jpg";var str='<div style="width:'+$me.width+"px"+';height:'+$me.height+'px; background:#000000 url(';str+=imgsrc;str+=') center center no-repeat;">'
str+='<a href="'+url+'" style="width:100%;height:100%;display:block"></a>';str+='</div>';el.innerHTML=str;$me.onwrite(curVideo.getFullVid());$me.oninited();},function(errCode){$me.onerror(errCode);$me.onwrite(curVideo.getFullVid());$me.oninited();});}}
tvp.MP4Link.prototype=new tvp.BasePlayer();tvp.Player=function(vWidth,vHeight){var $me=this,shellParam={"player":"auto"},commonParam={"type":tvp.PLAYTYPE.VOD},playerParam={};var player=null,width=vWidth,height=vHeight,containerId="",curVideo=null,playerClass=null;this.addParam=function(k,v){if(k=="vid"&&tvp.$.isString(v)){curVideo=new tvp.VideoInfo();curVideo.setVid(v);}else if(k in shellParam){shellParam[k]=v;}else if(k in commonParam){shellParam[k]=v;playerParam[k]=v;}else{playerParam[k]=v;}}
this.setCurVideo=function(video){if(video instanceof tvp.VideoInfo)curVideo=video;}
function showPlayer(playerClass,autoplay){player=null;player=new playerClass(width,height);for(var p in playerParam){if(p in player.params){player.addParam(p,playerParam[p]);}}
if(!tvp.$.isUndefined(autoplay)){player.addParam("autoplay",autoplay);}
tvp.$.each(player.eventList,function(i,n){player["on"+n]=$me["on"+n]||tvp.emptyFn;});tvp.$.each(player.mapToShellFun,function(i,n){if(tvp.$.isFunction($me[n])){player[n]=$me[n];}});tvp.$.each(player.hijackFun,function(i,n){$me[n]=player[n];});player.setCurVideo(curVideo);player.write(containerId);}
function checkUseWhatLivePlayer(video,callback){video=video||curVideo;useDefaultLivePlayer();var isUseJsDefinePlayer=(playerClass==tvp.Html5Live),isError=false;if(!!video.getChannelId()){var checker=new tvp.livehub.FlashChecker();checker.cnlId=video.getChannelId();checker.onGetCnlId=function(cnlid,isLookBack){video.setChannelId(cnlid);video.setIsLookBack(!!isLookBack);}
checker.onCanFlash=function(cnlid){if(!isUseJsDefinePlayer)playerClass=tvp.FlashLivePlayer;}
checker.onCanHTML5=function(){if(!isUseJsDefinePlayer)playerClass=tvp.Html5Live;}
checker.onCanOCX=function(){if(!isUseJsDefinePlayer)playerClass=tvp.OcxPlayer;}
checker.onError=function(errcode){if(tvp.$.isFunction($me.onliveerror)&&!($me.onliveerror===tvp.emptyFn)){$me.onliveerror(errcode);isError=true;return;}else{useDefaultLivePlayer();}}
checker.onComplete=function(){if(isError)return;useParamLivePlayer();if(tvp.$.isFunction(callback)){callback.call($me);}}
checker.send();}else{useParamLivePlayer();if(tvp.$.isFunction(callback)){callback.call($me);}}}
function useParamLivePlayer(){switch(shellParam["player"]){case"flash":{playerClass=tvp.FlashLivePlayer;break;}
case"html5":{playerClass=tvp.Html5Live;break;}
case"ocx":{playerClass=tvp.OcxPlayer;break;}
case"mp4":{playerClass=tvp.MP4Link;}}}
function useDefaultLivePlayer(){if(tvp.common.isLiveUseHTML5()){playerClass=tvp.Html5Live;}else if(!!tvp.$.userAgent.android){playerClass=tvp.FlashLivePlayer;}else{playerClass=tvp.FlashLivePlayer;}}
function useDefaultVodPlayer(){if(tvp.common.isEnforceMP4()){playerClass=tvp.MP4Link;return;}
if(tvp.common.isUseHtml5()){playerClass=tvp.Html5Player;}else if(tvp.$.userAgent.android>=4){playerClass=tvp.MP4Link;}else{playerClass=tvp.FlashPlayer;}}
function reportTJ(){var h=document.location.host,playername="flash",supportMP4=tvp.common.isSupportMP4();if(playerClass===tvp.Html5Player){playername="html5player";}else if(playerClass===tvp.FlashPlayer){playername="flashplayer";}else if(playerClass===tvp.OcxPlayer){playername="ocx";}else if(playerClass===tvp.Html5Live){playername="html5live";}else if(playerClass===tvp.FlashLivePlayer){playername="flashlive";}
if(Math.random()<0.1){tvp.report({"cmd":"2551","type":1,"host":h,"url":document.location.href,"ua":navigator.userAgent,"ver":"$Rev: 37429 $","str1":playername,"int1":supportMP4?1:0});}}
function render(id){if(shellParam.type==tvp.PLAYTYPE.LIVE){checkUseWhatLivePlayer(curVideo,function(){showPlayer(playerClass)});}else{switch(shellParam["player"]){case"flash":{playerClass=tvp.FlashPlayer;break;}
case"html5":{playerClass=tvp.Html5Player;break;}
case"ocx":{playerClass=tvp.OcxPlayer;break;}
case"mp4":{playerClass=tvp.MP4Link;break;}
default:{useDefaultVodPlayer();break;}}
showPlayer(playerClass);}}
this.write=function(id){if(!id)return;containerId=id;render(id);try{reportTJ();}catch(err){}}
this.switchPlayer=function(playerType){if(playerType=="ocx"){if(player instanceof tvp.OcxPlayer)return;showPlayer(tvp.OcxPlayer,true);}else if(playerType=="flash"){if(player instanceof tvp.FlashLivePlayer)return;showPlayer(tvp.FlashLivePlayer,true);}};this.switchToOcx=function(){this.switchPlayer("ocx");}
this.switchToFlash=function(){this.switchPlayer("flash");}
this.play=function(video){if(shellParam.type==tvp.PLAYTYPE.LIVE){checkUseWhatLivePlayer(video,function(){if(player instanceof playerClass){player.play(video);}else{showPlayer(playerClass,true);}});}else{player.play(video);}}
window.TenVideo_FlashLive_SwitchPlayer=function(){$me.switchToOcx();}}
tvp.Player.prototype=new tvp.BasePlayer();tvp.Player.instance=[];/*  |xGv00|6eec9a949ec06c0f6128d82aa603e21c */