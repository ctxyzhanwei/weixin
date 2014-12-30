
/*
window.onerror = function(evt){
	console.log(evt);
	return true;
}
*/

function alert(text, time, fn, fn2){
	var d = new iDialog();
	var args = {
		classList: "default alert",
		title:"",
		close:"",
		content:text
	};
	var timer = null;
	if(fn){
		args.btns = [
			{id:"", name:"确定", onclick:"fn.call();", fn: function(self){
				!fn.call(self)&&self.die();
				time&&clearTimeout(timer);
			}}
		];
	}
	if(fn2){
		args.btns.push(
			{id:"", name:"取消", onclick:"fn.call();", fn: function(self){
				!fn2.call(self)&&self.die();
			}}
		);
	}
	d.open(args);
	if(time){
		timer = setTimeout(function(){
			d.die();
			clearTimeout(timer);
		}, time);
	}
}


function confirm(text, fn1, fn2){
	var d = new iDialog();
	var args = {
		classList: "waiting confirm",
		title:"",
		close:"",
		content:text
	};
	args.btns = [
		{id:"", name:"确定", onclick:"fn.call();", fn: function(self){
			fn1&&fn1.call(this);
			self.die();
		}}
	];
	fn2&&args.btns.push({id:"", name:"取消", onclick:"fn.call();", fn: function(self){
			fn2&&fn2.call(this);
			self.die();
		}});
	d.open(args);
}


function loading(type){
	if(type){
		window.loader = new iDialog();
		window.loader.open({
			classList: "loading",
			title:"",
			close:"",
			content:''
		});
	}else{
		//setTimeout(function(){
			window.loader&&window.loader.die();
			delete window.loader;
		//}, 100);
	}
	
}

function tip(text, time){
	var d = new iDialog();
	d.open({
		classList: "default tip",
		title:"",
		close:"",
		content:text,
		btns:[
			{id:"", name:"确定", onclick:"fn.call();", fn: function(self){
				console.log("queding");
			}},
			{id:"", name:"取消", onclick:"fn.call();", fn: function(self){
				self.die();
			}}
		]
	});
}



var my = (function(){
	_my = function(){

	}
	_my.prototype = {
		changeImg: function(thi, evt, _req){
			var that = this;
			if(thi.files.length<1){
				return that;
			}
			// if(thi.files[0].size>=1024*1024*1 && !confirm("(文件过大，建议编辑后上传。)\n是否继续?")){
			// 	thi.setAttribute("data-upload-state", "0%");
			// 	return that;
			// }
			setTimeout(function(){
				that.uploadImg(thi, _req);
			}, 500);
			return that;
		},
		uploadImg: function(thi, _req){
			var that = this;
			var req = {
				id:6,
				username:"webAdd",
				header_img_id : thi.files[0]||{},
				form_action:"http://115.28.20.245:3000/api/uploadHeader",
				base_url: "http://115.28.20.245:3000/headers/"
			}
			for(var k in _req){
				req[k] = _req[k];
			}
			var xhr = window.ajax2(req.form_action, {
		    	type:"POST",
		    	async: true,
		    	data: req,
		    	timeout:10000*6,
		    	callback: function(res){
		    		if(0 == res.result){
		    			alert(res.message);
		    		}else{
		    			thi.parentNode.querySelectorAll("img")[0].src = req.base_url + res.data.header_img_id;
		    		}
		    		setTimeout(function(){
		    			thi.parentNode.removeAttribute("data-upload-state");
		    		}, 500);
		    	}
		    });
			xhr.onprogress = function(p){
				thi.parentNode.setAttribute("data-upload-state", Math.floor(p)+"%");
			};
			return that;
		}
	}

	return new _my();
})();

window.preViewImg = (function(){
	var imgsSrc = {};
	function reviewImage(dsrc, gid) {
	    if (typeof window.WeixinJSBridge != 'undefined') {
	        WeixinJSBridge.invoke('imagePreview', {
	            'current' : dsrc,
	            'urls' : imgsSrc[gid]
	        });
	    }else{
	    	alert("请在微信中查看", null, function(){});
	    }
	}
	function init(thi, evt){
		var dsrc = thi.getAttribute("data-src");
		var gid = thi.getAttribute("data-gid");

		if(dsrc && gid){
			imgsSrc[gid] = imgsSrc[gid]||[];
			imgsSrc[gid].push(dsrc);
			thi.addEventListener("click", function(){
				reviewImage(dsrc, gid);
			}, false);
		}
	}
	return init;
})();
