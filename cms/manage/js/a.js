var focusControl=function(e){
	e.className="colorfocus";
}
var blurControl=function(e){
	e.className="colorblur";
}
var mooAutoObject=new Object();
window.addEvent('domready',function(){
	//init
	var re = /a_(\w+)/;
	$$('.e').each(function(i){
		var m = re.exec(i.className);
        if (m) {
            eval("mooAutoObject.init"+m[1]+"();");
        }
	});
	prettifyControl();
});
var prettifyControl=function (){
	var forms=document.forms;
	formCount=forms.length;
	//set input textarea submit styles
	for(var i=0;i<formCount;i++){
		var formID=forms[i].id;
		if(formID){
		if($(formID)&&(!$(formID).getProperty('rel')||$(formID).getProperty('rel').toString()!='o')){
			var inputControls=$(formID).getElements('input[type=text]');
			inputControls.each(function(inputControl){
				inputControl.className="colorblur";
				inputControl.addEvent('focus',function(){focusControl(inputControl)});
				inputControl.addEvent('blur',function(){blurControl(inputControl)});
			});
			var inputControl1s=$(formID).getElements('input[type=password]');
			inputControl1s.each(function(inputControl1){
				inputControl1.className="colorblur";
				inputControl1.addEvent('focus',function(){focusControl(inputControl1)});
				inputControl1.addEvent('blur',function(){blurControl(inputControl1)});
			});
			var textareas=$(formID).getElements('textarea');
			textareas.each(function(textarea){
				textarea.className="input_blur txt_area";
				textarea.addEvent('focus',function(){textarea.removeClass("input_blur");textarea.addClass("input_focus");});
				textarea.addEvent('blur',function(){textarea.removeClass("input_focus");textarea.addClass("input_blur");});
			});
		}
		}
	}
	//set delete link confirm
	var deleteLinks=document.getElements('a[rel^=delete]');
	deleteLinks.each(function(deleteLink){
		var confirmString=deleteLink.getProperty('rel').replace('delete','');
		if(confirmString==''){
			confirmString="确认删除吗？";
		}else{
			confirmString=confirmString.replace('|','');
		}
		deleteLink.addEvent('click', function(e){
			return confirm(confirmString);
		});
	});
}
mooAutoObject.initCheckAll=function(){
	var checkItems=document.getElements('input[name=checkItem]');
	$('checkHeader').addEvent('click',function(){
		var headerChecked=$('checkHeader').checked;
		checkItems.each(function(checkItem){
			if(headerChecked){
				checkItem.checked=true;
			}else{
				checkItem.checked=false;
			}
		});
	});
}

var ajaxDelete=function(el,url){
	var rt=confirm('确定要删除吗？');
	if(el){
		var tds=el.getElements('td');
		if(tds){
			var tdFirst=tds[0];
			tdFirst.set('html','<img src="image/loading.gif" align="absmiddle" /> 正在删除...');
		}else{
			el.set('html','<img src="image/loading.gif" align="absmiddle" /> 正在删除...');
		}
	}
	if(rt){
		var req = new Request.HTML({url:url,
		onSuccess: function(html) {
			el.fade();
			setTimeout(function(){el.dispose();}, 1000);
		},
		onFailure: function() {
		}
		});
		req.send();
	}
}
var ajaxNoConfirmDelete=function(el,url){
	var req = new Request.HTML({url:url,
	onSuccess: function(html) {
		el.fade();
		setTimeout(function(){el.dispose();}, 1000);
	},
	onFailure: function() {
	}
	});
	req.send();
}
var mustInputElError=function(el){
	if(el.value.trim()==''){
		if(!$(el.getProperty('id')+'span')){
			var newSpan=new Element('span',{'id':el.getProperty('id')+'span','style':'color:#f00'}).inject(el,'after');
			newSpan.set('html','&nbsp;*');
		}
		return false;
	}else {
		if($(el.getProperty('id')+'span')){
			$(el.getProperty('id')+'span').dispose();
		}
		return true;
	}
}
var showErrorTips=function(html){
	if(!$('frinedtip')){
		var tipEl=new Element('div',{'id':'frinedtip','style':'z-index:1001;width:100%;text-align:center;position:fixed;top:50%;margin-top:-23px;left:0;'});
		tipEl.inject(document.body,'bottom');
	}
	$('frinedtip').set('html','<p style="display:inline-block;padding:13px 24px;border:solid #d6d482 1px;background:#f5f4c5;font-size:16px;color:#8f772f;line-height:18px;border-radius:3px;">'+html+'</p>');
	$('frinedtip').fade(0);
	(function(){$('frinedtip').fade(1);}).delay(500);
	(function(){$('frinedtip').dispose();}).delay(2500);
}
var showErrorTip=function(html){
	if(!$('tip')){
		var tipEl=new Element('div',{'id':'tip'});
		tipEl.inject($('autoForm'),'before');
	}
	$('tip').setStyle('display','block');
	$('tip').set('html',html);
	$('tip').fade(0);
	(function(){$('tip').fade(1);}).delay(500);
}
var hideErrorTip=function(){
	if($('tip')){
		$('tip').dispose();
	}
}
var validForm=function(id){
	$(id).removeProperty('onsubmit');
}
var submitLoading=function(formid){
	$submitBtns=$(formid).getElements('input[type=submit]');
	$submitBtns.each(function(btn){
		btn.setStyle('display','none');
		var loading=new Element('span',{'id':'loading'}).inject(btn,'before');
		$('loading').set('html','处理中...')
	});
}
var stopSubmitLoading=function(formid){
	$submitBtns=$(formid).getElements('input[type=submit]');
	$submitBtns.each(function(btn){
		btn.setStyle('display','inline');
		if($('loading')){
			$('loading').dispose();
		}
	});
}
var showSuccessTips=function(html){
	if(!$('success')){
		var tipEl=new Element('div',{'id':'success'});
		tipEl.inject($('autoForm'),'before');
	}
	$('success').setStyle('display','block');
	$('success').set('html','<span style="color:orange">'+html+'</span>');
	(function(){$('success').fade(0);}).delay(500);
	(function(){$('success').dispose();}).delay(1000);
}
var stopLoading=function(){
	if($('loading')){
		$('loading').set('html','');
	}
	if($('submit')){
		$('submit').setStyle('display','inline');
	}
}
var changeVC=function(){
	var req = new Request.HTML({url:'/validcode.php',
	onComplete: function() {
		$('vcImg').setProperty('src','/images/vc.png?'+$time());
	}
	});
	req.send();
}
var checkVC=function(formid){
	var reqVC = new Request.HTML({url:'/action.php?type=checkValidCode&validcode='+$('validcode').value,
	onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
		if(responseHTML.toInt()!=1){
			$('vctip').set('html','验证码不正确');
			$(formid).setProperty('onsubmit','return false');
		}else{
			$('vctip').set('html','');
			if($('submitjs')){
				$(formid).setProperty('onsubmit',$('submitjs').get('html'));
			}else{
				$(formid).removeProperty('onsubmit');
			}
		}
	}
	});
	reqVC.send();
}
var showFoldContent=function(elid){
	$('foldHideMore_'+elid).setStyle('display','');
	$('foldShowMore_'+elid).setStyle('display','none');
	$(elid).setStyle('display','');
}
var hideFoldContent=function(elid){
	$('foldHideMore_'+elid).setStyle('display','none');
	$('foldShowMore_'+elid).setStyle('display','');
	$(elid).setStyle('display','none');
}
var showElementByTab=function(elementSubfix,id,selectedClass){
	var tabClass='tab'+elementSubfix;
	var contentClass='content'+elementSubfix;
	var tabid=tabClass+id;
	var contentid=contentClass+id;
	
	$$('.'+tabClass).each(function(tab){
		tab.removeClass(selectedClass);
	});
	$(tabid).addClass(selectedClass);
	$$('.'+contentClass).each(function(c){
		c.setStyle('display','none');
	});
	//alert(contentid);
	$(contentid).setStyle('display','block');
}
var toPhpFile=function(){

}