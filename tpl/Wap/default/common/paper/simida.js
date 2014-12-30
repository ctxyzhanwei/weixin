var imageContainer = document.getElementById("imageContainer");
var theImage = document.getElementById("showImage");
var leftTime = document.getElementById("leftTime");
var titleBar = document.getElementById("titleBar");
var productInfo = document.getElementById("productInfo");
var loading = document.getElementById("loading");
var vanish = document.getElementById("vanish");
var timer,maxTime=5,clockTime,showStart=false;
/*
 *系统时间和长按相关
 */
var systemTimer = setInterval("systemTimerHandle()",250);
var systemTime = 0;         //系统时间
var longTouchFlag =false;   //是否当前正在长按
var longTouchStartTime = 0; //长按开始时的秒数
clockTime=maxTime;

function systemTimerHandle(){
	systemTime ++;	
	if(true == longTouchFlag && (systemTime - longTouchStartTime > 2) && clockTime > 0){ //长按 500ms
		showImageHandle();
	}
}
function sTimer() 
{  
   leftTime.innerHTML=clockTime;  
   if(0 == clockTime)
   {
   		leftTime.style.display = "none";
		imageContainer.style.display="none";		
		titleBar.style.display="none";	
		clearInterval(timer);
		productInfo.style.display="block";
		vanish.innerHTML = "<p>时间到，秘密已销毁！</p>";
		vanish.style.display="block";
		return;
   }
	clockTime--;
}
function showImageHandle(){
	if(!showStart){   //第一次点击的时候
		showStart=true;
		timer = setInterval("sTimer()",1000);
		leftTime.style.display="inline";
	}
    leftTime.innerText=clockTime; 
	imageContainer.style.display="block";	
	titleBar.style.display="none";
}
//title touch ，至少按1s才会显示
function titleTouchStart(e){
	console.log("touch start");
	if(0 == clockTime)
		return;
	e.preventDefault();
	longTouchFlag = true;
	longTouchStartTime = systemTime;
}
function titleTouchMove(e){
	console.log("touch moving");
	if(0 == clockTime)
	return;

	e.preventDefault();
}
/*
 *  隐藏引导图
 */
function hideGuidView(){
	longTouchFlag = false;
	imageContainer.style.display="none";
	if(0 == clockTime){
		titleBar.style.display="none";			
	}else{
		titleBar.style.display="block";			
	}
}
function titleTouchEnd(e){ 
	console.log("touch end");
	if(0 == clockTime)
		return;
	e.preventDefault();	
	hideGuidView();
}

function titleTouchCancel(e){
	console.log("touch cancel");
	if(0 == clockTime)
	return;
	e.preventDefault();
	hideGuidView();
}

/*image touch*/
function setupEvents(){
	try{
		//长按title的touch事件绑定
		//因为mouseup的时候titlebar不存在，所以绑在container上
		var container = document.getElementById("content");
		if(container.addEventListener){
			container.addEventListener("touchstart",titleTouchStart,false);
			container.addEventListener("touchmove",titleTouchMove,false);
			container.addEventListener("touchend",titleTouchEnd,false);
			container.addEventListener("touchcancel",titleTouchCancel,false);
			//webkit
			container.addEventListener("mousedown",titleTouchStart,false);
			container.addEventListener("mouseup",titleTouchEnd,false);			
		}else if(container.attachEvent){
			//ie	
			container.attachEvent("mousedown",titleTouchStart);
			container.attachEvent("mouseup",titleTouchEnd);			
		}
	}catch(e){
		alert(e.message);
	}
}

function pageLoad(){
    var u = navigator.userAgent;
    var isMobile = !!u.match(/AppleWebKit.*Mobile.*/);
    if(!isMobile){
	document.body.style.backgroundSize = '720px auto';
    }else{
	document.body.style.backgroundSize = '100% auto';
    }
    if (msgStatus == "ok") {
    	if (dataType == "text") {
    		// 文字
    		touchArea.style.display = "block";
    		setupEvents();
    	}else{
    		// 图片
    		loading.style.display = "block";
    		var imgID = new Image();
	    	imgID.src = contentImage;
	   		imgID.onload = function(){
		    	loading.style.display = "none";
	 	  		setupEvents();
			   	touchArea.style.display = "block";
	    	}
    	}
    }else if(msgStatus == "read"){
    	// 已读
    	titleBar.style.display = "none";
    	vanish.style.display = "block";
    	productInfo.style.display = "block";
    }else{
    	// 无参数
    	titleBar.style.display = "none";
    	productInfo.style.display = "block";
    }
    
}

function orientationChange(){ 
	switch(window.orientation) { 
		case 0: // Portrait 
		
		case 180: // Upside-down Portrait 
		theImage.style.width="100%";
		theImage.style.height="auto";
		// Javascript to setup Portrait view 
		break; 
		case -90: // Landscape: turned 90 degrees counter-clockwise 
		case 90: // Landscape: turned 90 degrees clockwise 
		// Javascript to steup Landscape view 
		theImage.style.height="100%";
		theImage.style.width="auto";
		break; 
	} 
} 



(function(d) {

	var body = d.getElementsByTagName('body')[0],
		container = document.getElementById("touchArea"),

		fn = function() {
			var img = new Image();
			img.src = 'ana.html?' + (+new Date());
		};

	if ( container.addEventListener ) {
		container.addEventListener("touchstart",fn,false);

	} else {
		container.attachEvent("mouseup",fn);
	}

})(document);