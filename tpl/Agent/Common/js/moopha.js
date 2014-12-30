var moophaCMS=new Class({
	initialize:function(){
    },
    submitLoading:function(form){
    	$submitBtns=form.getElements('input[type=submit]');
    	$submitBtns.each(function(btn){
    		btn.setStyle('display','none');
    		if(!$('loading')){
    			var loading=new Element('span',{'id':'loading'}).inject(btn,'before');
    		}
    		$('loading').set('html','处理中...');
    	});
    },
    stopSubmitLoading:function(form){
    	$submitBtns=form.getElements('input[type=submit]');
    	$submitBtns.each(function(btn){
    		btn.setStyle('display','inline');
    		if($('loading')){
    			$('loading').dispose();
    		}
    	});
    }
});



var focusControl=function(e){
	e.className="colorfocus";
}
var blurControl=function(e){
	e.className="colorblur";
}
var moopha=new Object();
window.addEvent('domready',function(){
	//init
	var re = /moopha(\w+)/;
	var i=0;
	$$('.ax').each(function(i){
		var m = re.exec(i.className);
        if (m) {
            eval("moopha.init"+m[1]+"();");
        }
        i++;
	});
	prettifyControl();
});
var prettifyControl=function (){
	var forms=document.forms;
	formCount=forms.length;
	//set input textarea submit styles
	for(var i=0;i<formCount;i++){
		var formID=forms[i].id;
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
			textarea.className="colorblur";
			textarea.addEvent('focus',function(){textarea.removeClass("colorblur");textarea.addClass("colorfocus");});
			textarea.addEvent('blur',function(){textarea.removeClass("colorfocus");textarea.addClass("colorblur");});
		});
	}
	//set delete link confirm
	var deleteLinks=document.getElements('a[rel^=delete]');
	deleteLinks.each(function(deleteLink){
		var confirmString=deleteLink.getProperty('rel').replace('delete','');
		if(confirmString==''){
			confirmString=lang('lang_deleteConfirm');
		}else{
			confirmString=confirmString.replace('|','');
		}
		deleteLink.addEvent('click', function(e){
			return confirm(confirmString);
		});
	});
}
moopha.initCheckAll=function(){
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
	var rt=confirm(lang('lang_deleteConfirm'));
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
var aDelete=function(el,url){
	var rt=confirm(lang('lang_deleteConfirm'));
	if(rt){
		var req = new Request.HTML({url:url,
		onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
			if(responseHTML.toInt()>0){
				el.fade();
				setTimeout(function(){el.dispose();}, 1000);
			}
		},
		onFailure: function() {
		}
		});
		req.send();
	}
}
var aDeleteWithConfirm=function(el,url,confirmStr){
	var rt=confirm(confirmStr);
	if(rt){
		var req = new Request.HTML({url:url,
		onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
			if(responseHTML.toInt()>0){
				el.fade();
				setTimeout(function(){el.dispose();}, 1000);
			}
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

var playFlash=function(sFile,sWidth,sHeight){
	document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="'+ sWidth +'" height="'+ sHeight +'">  ');
	document.write(' <param name="movie" value="'+ sFile +'">  ');
	document.write(' <param name="quality" value="high">  ');
	document.write(' <param name="wmode" value="transparent">  ');
	document.write(' <embed src="'+ sFile +'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="'+ sWidth +'" height="'+ sHeight +'"></embed>  ');
	document.write('</object> ');
}
var getMultipleSelectValue=function(el){
	var values = [];
	var optionCount=el.options.length;
	for(i=0;i<optionCount;i++){
		if(el.options[i].selected){
			values.push(el.options[i].value);
		}
	}
	return values;
}
var progressBar=function(barLength,pCount,cProgress){
	var progress;
	if(pCount>barLength){
		var unitMove=(pCount/barLength).toInt()+1;
		if(cProgress!=pCount-1){//not last
			if(cProgress%unitMove==0){
				var nowLength=$('loading').getProperty('rel').toInt();
				var newLength=nowLength+1;
			}else{
				var newLength=$('loading').getProperty('rel').toInt();
			}
			progress=(100*cProgress/pCount).toInt();
		}else{//last
			var newLength=barLength;
			progress=100;
		}
	}else{
		var unitLength=barLength/pCount;
		if(cProgress!=pCount-1){
			var nowLength=$('loading').getProperty('rel').toInt();
			var newLength=nowLength+unitLength;
			progress=(100*cProgress/pCount).toInt();
		}else{
			var newLength=barLength;
			progress=100;
		}
	}
	$('progress').set('html',progress+'% ('+(cProgress+1)+'/'+pCount+')');
	$('loading').setStyle('width',newLength+'px');
	$('loading').setProperty('rel',newLength);
}
var showErrorTip=function(el,html){
	if(!$('tip')){
		var tipEl=new Element('div',{'id':'tip'}).inject(el,'before');
	}
	$('tip').fade();
	$('tip').fade('in');
	$('tip').setStyle('display','block');
	$('tip').set('html',html);
}
var showErrorsTip=function(el,html){
	if(!$('tip')){
		var tipEl=new Element('div',{'id':'tip'}).inject(el,'before');
	}
	$('tip').fade();
	$('tip').fade('in');
	$('tip').setStyle('display','block');
	$('tip').set('html',html);
}
var hideErrorTip=function(){
	if($('tip')){
		$('tip').dispose();
	}
}
var closeDialogWithSuccessMsg = function(el,msg){
	el.set('html','<div id="success">'+msg+'</div>');
	if($('dialog')){
	(function(){$('dialog').fade()}).delay(600);
	(function(){$('dialog').dispose();$('overlay').dispose();$('all').dispose();}).delay(1200);
	}
	return false;
}
var diabledSubmit=function(){
	var submit=document.getElements('input[type=submit]');
	var span=new Element('span',{id:'loadSpan'});
	//span.inject(submit,'after');
	//$('loadSpan').set('html','<img src="image/loading.gif" />');
	submit.setStyle('display','none');
}

function copyToClipBoard(txt) {
    if (window.clipboardData) {
        window.clipboardData.clearData();
        window.clipboardData.setData("Text", txt);
    } else if (navigator.userAgent.indexOf("Opera") != -1) {
        //do nothing      
    } else if (window.netscape) {
        try {
            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
        } catch (e) {
            alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将 'signed.applets.codebase_principal_support'设置为'true'");
        }
        var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip)   return;
        var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if (!trans) return;
        trans.addDataFlavor('text/unicode');
        var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext = txt;
        str.data = copytext;
        trans.setTransferData("text/unicode", str, copytext.length * 2);
        var clipid = Components.interfaces.nsIClipboard;
        if (!clip)   return false;
        clip.setData(trans, null, clipid.kGlobalClipboard);
    }
    alert("复制成功");
}