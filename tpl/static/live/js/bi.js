function UUID(){
	this.id=this.createUUID();
};
UUID.prototype.valueOf=function(){
	return this.id
};

UUID.prototype.toString=function(){
	return this.id
};

UUID.prototype.createUUID=function(){
	var c=new Date(1582,10,15,0,0,0,0);
	var f=new Date();
	var h=f.getTime()-c.getTime();
	var i=UUID.getIntegerBits(h,0,31);
	var g=UUID.getIntegerBits(h,32,47);
	var e=UUID.getIntegerBits(h,48,59)+"2";
	var b=UUID.getIntegerBits(UUID.rand(4095),0,7);
	var d=UUID.getIntegerBits(UUID.rand(4095),0,7);
	var a=UUID.getIntegerBits(UUID.rand(8191),0,7)+UUID.getIntegerBits(UUID.rand(8191),8,15)+UUID.getIntegerBits(UUID.rand(8191),0,7)+UUID.getIntegerBits(UUID.rand(8191),8,15)+UUID.getIntegerBits(UUID.rand(8191),0,15);
	return i+g+e+b+d+a;
};

UUID.getIntegerBits=function(f,g,b){
	var a=UUID.returnBase(f,16);
	var d=new Array();var e="";
	var c=0;for(c=0;c<a.length;c++){
		d.push(a.substring(c,c+1));
	}
	
	for(c=Math.floor(g/4);c<=Math.floor(b/4);c++){
		if(!d[c]||d[c]==""){
			e+="0";
		}else{
			e+=d[c];
		}
	}
	return e;
};
	
UUID.returnBase=function(c,d){
var e=["0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
	if(c<d){
		var b=e[c]
	}else{
			var f=""+Math.floor(c/d);
			var a=c-f*d;
			if(f>=d){
				var b=this.returnBase(f,d)+e[a];
			}else{
				var b=e[f]+e[a];
			}
	}
	return b;	
};

UUID.rand=function(a){
return Math.floor(Math.random()*a)
};


var _BiStat = {
	record: function(appId, appName, lng, lat){ // netType网络类型,lng经度,lat纬度
		var netType = _BiStat.getNetType();
		var nowDomain = new String(document.domain);
//		if(nowDomain == 'cgtest.lightapp.cn' || nowDomain == 'tt.lightapp.cn' || nowDomain == 't.lightapp.cn' || !nowDomain.match(/lightapp.cn/i) || !nowDomain.match(/liveapp.cn/i)){
//			domain = 'bitest.lightapp.cn';
//		}else{
//			domain = 'bi.lightapp.cn';
//		}

		domain = 'bi.lightapp.cn';
		
		if(!_BiStat.getCookie('key')){
			var d = new  UUID();
			_BiStat.addCookie('key',d,1);
		}
		var v = _BiStat.getCookie('key');
		var url = 'http://'+domain+'/api/welcome/statistics?activity_id='+appId+'&activity_name='+appName+
		"&uuid="+v+'&nettype='+netType+'&x='+lng+'&y='+lat;
		_BiStat.ajaxUrl(url);
	},
	
	// 取得网络类型
	getNetType: function(){
		var netType = '';
		var userAgent=window.navigator.userAgent;
		var reg=/nettype\/([^\s]*)/i;
		var $ms='';
		if($ms=userAgent.match(reg)){
			return $ms[1];
		}
		
		if(window.navigator.connection){
	   		var conns = window.navigator.connection;
	   		if( conns != '' && conns != undefined){
	   			var type = conns['type'];
	   			for(var props in conns){
	   				if(conns[props] == type && props != 'type' ){
	   					netType = props;
	   				}
	   			}
	   		}
		}
    		
    	return netType;
	},

	ajaxUrl: function(url){
		var JSONP  = document.createElement("script");  
	    JSONP.type = "text/javascript";  
	    JSONP.src  = url;  
	    document.getElementsByTagName("body")[0].appendChild(JSONP);
	},
	addCookie:function(objName,objValue,objHours){
		var str = objName+"="+escape(objValue);
		if(objHours>0){
			var date = new Date();
			var ms = objHours*3600*1000*365*24;
			date.setTime(date.getTime()+ms);
			str+=';expires='+date.toGMTString();		
		}
		document.cookie = str;
	},
	getCookie:function(name)  
	{  
		var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");  
		if(arr=document.cookie.match(reg)) 
			return unescape(arr[2]);  
		else 
			return null;  
	}  
}