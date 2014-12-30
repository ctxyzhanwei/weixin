/*
 * com.clanmo.gallery v1.1
 *
 * Copyright © 2013 SapientNitro GmbH
 * All rights reserved.
 *
 */
function Gallery(options){var defaults={id:"gallery",type:"roundabout",leftClass:"scrollLeft",rightClass:"scrollRight",orientationSupport:true,slideSupport:true,touchable:false,maxForce:400,offset:0};var options=$.extend({},defaults,options);var supportsOrientationChange="onorientationchange"in window,orientationEvent=supportsOrientationChange?"orientationchange":"resize";var TYPE_ROUNDABOUT="roundabout";var TYPE_STAGE="stage";var CURRENT="current_"+options.id;var START="start";var SELECTED="selected";var ATTR_START="data-start";var ATTR_SELECTED="data-selected";var ATTR_TOUCHABLE="data-touchable";var ATTR_HREF="data-href";var ATTR_JSFUNCTION="data-jsfunction";var ATTR_CURRENT="data-current";var ATTR_ITEMID="data-itemid";var ATTR_MAXBULLETS="data-maxBullets";var STATE_STATIC="static";var STATE_SLIDE="slide";var TYPE_TEXT_NODE=3;var type;var gallery;var gallery_width;var gallery_left;var gallery_right;var indicator;var belt;var orgItems;var unOrgItems;var computedItems;var orgWidth;var computedWidth;var offset;var state;gallery=$("#"+options.id);gallery_width=gallery.outerWidth(true);gallery_left=gallery.offset().left;gallery_right=gallery_left+gallery_width;belt=gallery.children("ul");orgItems=unOrgItems=getItems(belt);indicator=$("#"+options.indicator_id);var startPos=getStartPos(orgItems);orgItems=sort(orgItems,startPos);this.id=options.id;this.orgItems=orgItems;this.ATTR_CURRENT=ATTR_CURRENT;this.CURRENT=CURRENT;switch(options.type){case TYPE_ROUNDABOUT:orgWidth=getWidth(orgItems);computedItems=compute(orgItems,5,orgItems.length);setStart(computedItems[orgItems.length*2]);if(isStatic(gallery_width,orgWidth)){state=STATE_STATIC;initStatic()}else{state=STATE_SLIDE;initSlide(touchStartRoundabout,touchMove,touchEnd)}
if(options.orientationSupport){window.addEventListener(orientationEvent,orientationChangeRoundabout,false)}
break;case TYPE_STAGE:orgWidth=getWidth(orgItems);if(orgItems.length<2){state=STATE_STATIC;computedItems=orgItems;setItemWidth(orgItems);initStatic()}else{if(orgItems.length<5){state=STATE_SLIDE;computedItems=compute(orgItems,2,orgItems.length);setStart(computedItems[orgItems.length]);setCurrent(computedItems[orgItems.length]);setItemWidth(computedItems);initSlide(touchStartStage,touchMove,touchEnd)}else{state=STATE_SLIDE;computedItems=compute(orgItems,1,Math.round(orgItems.length/2));setStart(computedItems[Math.floor(computedItems.length/2)]);setCurrent(computedItems[Math.floor(computedItems.length/2)]);setItemWidth(computedItems);initSlide(touchStartStage,touchMove,touchEnd)}}
if(options.orientationSupport){window.addEventListener(orientationEvent,orientationChangeStage,false)}
if(indicator.length){setIndicatorPosition()}
break}
function initSlide(touchStart,touchMove,touchEnd){computedWidth=getWidth(computedItems);setItems(belt,computedItems);offset=getOffset();offset=offset+options.offset;belt.css("left",offset+"px");if(options.slideSupport){bindTouchEvents(belt,touchStart,touchMove,touchEnd)}else{bindImgClickEvents(computedItems)}
if(typeof(options.leftClass)!="undefined"){var btn=$("."+options.leftClass);bindClickEvent(btn,function(){clickButton("left")});btn.show()}
if(typeof(options.rightClass)!="undefined"){var btn=$("."+options.rightClass);bindClickEvent(btn,function(){clickButton("right")});btn.show()}
unbindClickEvents(orgItems)}
function initStatic(){setItems(belt,unOrgItems);belt.css("left","0px");unbindTouchEvents(belt);if(typeof(options.leftClass)!="undefined"){var btn=$("."+options.leftClass);unbindClickEvent(btn);btn.hide()}
if(typeof(options.rightClass)!="undefined"){var btn=$("."+options.rightClass);unbindClickEvent(btn);btn.hide()}
unbindClickEvents(unOrgItems);bindImgClickEvents(unOrgItems)}
function isStatic(gallery_width,rw){return gallery_width>rw}
function getWidth(items){var result=0;for(var i=0;i<items.length;i++){result+=items[i].outerWidth(true)}
return result}
function getItems(elem){var result=[];var childs=elem.children();$.each(childs,function(i){result[i]=$(this)});return result}
function sort(items,pos){return items.slice(pos).concat(items.slice(0,pos))}
function compute(items,f,offset){var result=[];var item_length=items.length;var result_length=item_length*f;for(var i=0;i<result_length;i++){result[i]=items[(i+offset)%item_length].clone()}
return result}
function setItems(elem,items){elem.empty();$.each(items,function(i){elem.append($(this))})}
function getStartPos(items){for(var i=0;i<items.length;i++){if(items[i].attr(ATTR_START)){items[i].removeAttr(ATTR_START);return i}}
return 0}
function getStart(){return $("#"+gallery.attr("id")+" li["+ATTR_START+"="+START+"]")}
function getOffset(){var selected=getStart();return selected.position().left*-1}
function setStart(node){node.attr(ATTR_START,START)}
function bindImgClickEvents(items){bindClickEvents(items,function(){var url=$(this).find(".item").attr(ATTR_HREF);if(!isUndefined(url)){window.location.href=url}})}
function bindTouchEvents(obj,touchStart,touchMove,touchEnd){var isTouchable=("ontouchend"in document);if(isTouchable){unbindTouchEvents(obj);obj.bind("touchstart",touchStart,false);obj.bind("touchmove",touchMove,false);obj.bind("touchend",touchEnd,false);obj.bind("touchcancel",galleryTouchCancel,false)}else{unbindClickEvents(computedItems);bindClickEvents(computedItems,function(){var url=$(this).find(".item").attr(ATTR_HREF);if(!isUndefined(url)){window.location.href=url}})}}
function unbindTouchEvents(obj){var isTouchable=("ontouchend"in document);if(isTouchable){obj.unbind("touchstart");obj.unbind("touchmove");obj.unbind("touchend");obj.unbind("touchcancel")}else{unbindClickEvents(computedItems)}}
function bindClickEvent(obj,funct){obj.unbind("click");obj.bind("click",funct,false)}
function unbindClickEvent(obj){obj.unbind("click")}
function unbindClickEvents(items){for(var i=0;i<items.length;i++){unbindClickEvent(items[i])}}
function bindClickEvents(items,funct){for(var i=0;i<items.length;i++){items[i].unbind("click");items[i].bind("click",funct,false)}}
var origX=0;var origY=0;var finalX=0;var finalY=0;var movedX=0;var movedY=0;var movedComplete=0;var target;var animated=false;var clickable=false;var determineSwipe=true;var swipe=false;var recompute=false;var startTime=0;var currDistAnimate=0;var t=0;var down=false;function touchStartRoundabout(jEvent){var event=jEvent.originalEvent;movedComplete=0;if(animated){belt.stop();clickable=false;animated=false;recomputeBelt(currDistAnimate)}else{clickable=true}
target=preventTextNode(event.touches[0].target);origX=event.touches[0].pageX;origY=event.touches[0].pageY}
function touchStartStage(jEvent){if(down){galleryTouchCancel();}
down=true;var event=jEvent.originalEvent;if(animated){clickable=false}else{clickable=true}
target=preventTextNode(event.touches[0].target);origX=event.touches[0].pageX;origY=event.touches[0].pageY}
function touchMove(jEvent){var event=jEvent.originalEvent;finalX=event.touches[0].pageX;finalY=event.touches[0].pageY;movedX=origX-finalX;movedY=origY-finalY;movedComplete+=movedX;if(determineSwipe&&Math.abs(movedY)<Math.abs(movedX)&&isTouchble(target)){swipe=true}
determineSwipe=false;if(swipe&&!recompute){startTime=(new Date).getTime();event.preventDefault();var currentLeft=parseInt(belt.css("left"));var newX=currentLeft-movedX;if(options.type==TYPE_ROUNDABOUT){do{var firstChild=belt.children(":first");var lastChild=belt.children(":last");var firstWidth=firstChild.outerWidth(true);var lastWidth=-lastChild.outerWidth(true);var currentLeft=parseInt(belt.css("left"));if(movedComplete>firstWidth){lastChild.after(firstChild);movedComplete-=firstWidth;newX+=firstWidth;belt.css("left",(currentLeft+firstWidth)+"px")}else{if(movedComplete<lastWidth){firstChild.before(lastChild);movedComplete-=lastWidth;newX+=lastWidth;belt.css("left",(currentLeft+lastWidth)+"px")}else{break}}}while(true)}
belt.css("left",newX+"px")}
origX=finalX;origY=finalY}
function touchEnd(jEvent){var event=jEvent.originalEvent;if(finalX==0&&!animated&&clickable){var url=$(target).attr(ATTR_HREF);if(!isUndefined(url)){window.location.href=url}
if(!isUndefined(options.jsFunction)){options.jsFunction.call()}}else{if(!animated&&swipe&&isTouchble(target)){event.preventDefault();animated=true;swipe=false;switch(options.type){case TYPE_ROUNDABOUT:doSwipe();break;case TYPE_STAGE:doSnap();break}}}
origX=0;origY=0;finalX=0;finalY=0;movedX=0;movedY=0;down=false;determineSwipe=true}
function galleryTouchCancel(){origX=0;origY=0;finalX=0;finalY=0;movedX=0;movedY=0;determineSwipe=true;down=false;}
function isTouchble(node){if(options.touchable&&isUndefined($(node).attr(ATTR_TOUCHABLE))){return false}
return true}
function orientationChangeRoundabout(){if(animated){belt.stop();animated=false;recomputeBelt(currDistAnimate)}
gallery_width=gallery.outerWidth(true);gallery_left=gallery.offset().left;gallery_right=gallery_left+gallery_width;if(state==STATE_SLIDE&&isStatic(gallery_width,orgWidth)){state=STATE_STATIC;initStatic()}else{if(state==STATE_STATIC&&!isStatic(gallery_width,orgWidth)){state=STATE_SLIDE;initSlide(touchStartRoundabout,touchMove,touchEnd)}}}
function orientationChangeStage(){gallery_width=gallery.outerWidth(true);gallery_left=gallery.offset().left;gallery_right=gallery_left+gallery_width;if(state==STATE_SLIDE){setItemWidth(computedItems);var currleft=getCurrent().position().left;belt.css("left",-currleft+"px")}else{setItemWidth(orgItems)}
if(indicator.length){setIndicatorPosition()}}
function getCurrent(){return belt.find("li["+ATTR_CURRENT+"]")}
function setCurrent(node){getCurrent().removeAttr(ATTR_CURRENT);$(node).attr(ATTR_CURRENT,CURRENT)}
function clickButton(direction){if(!animated){animated=true;var durationAnimation=1000;var distToAnimate;switch(options.type){case TYPE_ROUNDABOUT:distToAnimate=gallery_width*2/3;break;case TYPE_STAGE:distToAnimate=gallery_width;break}
if(direction=="left"){distToAnimate=distToAnimate*-1;setCurrent(getCurrent().prev())}else{if(direction=="right"){setCurrent(getCurrent().next())}}
belt.animate({left:"-="+distToAnimate+"px"},{duration:parseInt(durationAnimation),easing:"easeOutSine",step:function(now,fx){currDistAnimate=fx.start-fx.now},complete:function(){recomputeBelt(distToAnimate);if(indicator.length){moveIndicator()}
animated=false}})}}
function setIndicatorPosition(){var indicatorPosition=(getCurrent().width()/2)-(indicator.outerWidth()/2);$(indicator).css("left",indicatorPosition+"px")}
function moveIndicator(){var itemid=getCurrent().attr(ATTR_ITEMID);if(orgItems.length>indicator.attr(ATTR_MAXBULLETS)){indicator.children(":first").text(itemid)}else{indicator.children().removeClass("ind_a");indicator.children().addClass("ind_i");indicator.find(":nth-child("+itemid+")").removeClass("ind_i");indicator.find(":nth-child("+itemid+")").addClass("ind_a")}}
function doSnap(){var durationAnimation=500;var item=$(target).closest("li");var tLeft=$(item).offset().left;var tRight=tLeft+$(item).outerWidth(true);var gLeft=gallery.offset().left;var f=gallery_width/$(item).offset().left;var distToAnimate;var firstChild=belt.children(":first");var lastChild=belt.children(":last");var firstWidth=firstChild.outerWidth(true);var lastWidth=-lastChild.outerWidth(true);var currentLeft=parseInt(belt.css("left"));if(f>0){if(Math.abs(f)>3){distToAnimate=tLeft-gLeft}else{distToAnimate=-(gallery_right-tLeft);setCurrent(getCurrent().prev());firstChild.before(lastChild);belt.css("left",(currentLeft+lastWidth)+"px");if(indicator.length){moveIndicator()}}}else{if(Math.abs(f)>3){distToAnimate=tLeft-gLeft}else{distToAnimate=tRight-gLeft;setCurrent(getCurrent().next());lastChild.after(firstChild);belt.css("left",(currentLeft+firstWidth)+"px");if(indicator.length){moveIndicator()}}}
belt.animate({left:"-="+distToAnimate+"px"},{duration:parseInt(durationAnimation),easing:"easeOutSine",complete:function(){animated=false}})}
function doSwipe(){if(!recompute){t=(((new Date).getTime())-startTime);var v=movedX/t;var durationAnimation=Math.abs(v)*options.maxForce;var distToAnimate=(gallery_width/3)*v;belt.animate({left:"-="+distToAnimate+"px"},{duration:parseInt(durationAnimation),easing:"easeOutSine",step:function(now,fx){currDistAnimate=fx.start-fx.now},complete:function(){recomputeBelt(distToAnimate);animated=false}})}}
function recomputeBelt(x){if(!recompute){recompute=true;do{var firstChild=belt.children(":first");var lastChild=belt.children(":last");var firstWidth=firstChild.outerWidth(true);var lastWidth=-lastChild.outerWidth(true);var currentLeft=parseInt(belt.css("left"));if(x>firstWidth){lastChild.after(firstChild);x-=firstWidth;belt.css("left",(currentLeft+firstWidth)+"px")}else{if(x<lastWidth){firstChild.before(lastChild);x-=lastWidth;belt.css("left",(currentLeft+lastWidth)+"px")}else{if(offset>currentLeft+firstWidth-x){lastChild.after(firstChild);belt.css("left",(currentLeft+firstWidth)+"px")}else{if(offset<currentLeft+lastWidth-x){firstChild.before(lastChild);belt.css("left",(currentLeft+lastWidth)+"px")}}
recompute=false;break}}}while(true)}else{console.log("could not recompute...")}}
function setItemWidth(items){if(items.length>0){for(var i=0;i<items.length;i++){items[i].css("width",gallery_width+"px")}}}
function isUndefined(elem){return typeof(elem)=="undefined"}
function preventTextNode(node){if(node.nodeType==TYPE_TEXT_NODE){return node.parentNode}
return node}}
Gallery.prototype.select=function(selectid,selectattr,selectclazz,unselectclazz){var gallery=$("#"+this.id);var belt=gallery.children("ul");var items=belt.children();for(var i=0;i<items.length;i++){$(items[i]).removeClass(selectclazz);$(items[i]).addClass(unselectclazz)}
$("*["+selectattr+"="+selectid+"]").addClass(selectclazz);$("*["+selectattr+"="+selectid+"]").removeClass(unselectclazz)};Gallery.prototype.jumpToChild=function(selectid,selectattr){var gallery=$("#"+this.id);var belt=gallery.children("ul");var items=belt.children();var selected;var element=belt.find("li["+this.ATTR_CURRENT+"]");var cIndex=$(element).index();for(var i=cIndex-1;i<items.length-1;i++){if(element.attr(selectattr)==selectid){selected=element;break}
element=element.next()}
var element=belt.find("li:first");if(typeof(selected)=="undefined"){for(var i=0;i<items.length-1;i++){if(element.attr(selectattr)==selectid){selected=element;break}
element=element.next()}}
var sIndex=selected.index();if(sIndex<cIndex){for(var i=0;i<cIndex-sIndex;i++){var firstChild=belt.children(":first");var lastChild=belt.children(":last");var firstWidth=firstChild.outerWidth(true);var currentLeft=parseInt(belt.css("left"));firstChild.before(lastChild)}}else{if(sIndex>cIndex){for(var i=0;i<sIndex-cIndex;i++){var firstChild=belt.children(":first");var lastChild=belt.children(":last");var lastWidth=-lastChild.outerWidth(true);var currentLeft=parseInt(belt.css("left"));lastChild.after(firstChild)}}}
if(sIndex!=cIndex){for(var i=0;i<items.length;i++){$(items[i]).removeAttr(this.ATTR_CURRENT)}
$(selected).attr(this.ATTR_CURRENT,this.CURRENT)}};Gallery.prototype.shutdown=function(){var gallery=$("#"+this.id);var belt=gallery.children("ul");belt.empty();$.each(this.orgItems,function(i){belt.append($(this))})};