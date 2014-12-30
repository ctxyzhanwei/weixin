var showDialog = function(div) {
	if($('dialog')!=null) return;
	var overlay  = new Element('div', {id: 'overlay'});
	var dialog  = new Element('div', {id: 'dialog'});
	overlay.inject($('body'),'bottom');
	dialog.inject($('body'),'bottom');
	var overlay  = new Element('div', {id: 'all'}).inject($('body'),'bottom');
	if(div != null){
		$('dialog').set('html',div);
	}else{
		$('dialog').set('html',"<div class='loadpop'>正在载入，请稍后...</div>");
	}
	setOverlay();
}

var dialogCloseStr=function(title){
	return "<div id='closelink'><span class=\"title\" style='float:left;text-align:left;width:95%;'>"+title+"</span><span onclick='closeDialog();' style='float:right;width:2%;'><img src='image/close.gif'></img></span></div><br>";
}
var setOverlay = function(){
	var oheight = (Browser.Engine.trident?-1:16);
	$('overlay').setStyle('height',$('dialog').offsetHeight+oheight);
}

var closeDialog = function(){
	$('dialog').dispose();
	$('overlay').dispose();
	$('all').dispose();
	return false;
}