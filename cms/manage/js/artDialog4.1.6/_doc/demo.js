document.title += ' ' + art.dialog.fn.version;

// 杩愯浠ｇ爜
$.fn.runCode = function () {
	var getText = function(elems) {
		var ret = "", elem;

		for ( var i = 0; elems[i]; i++ ) {
			elem = elems[i];
			if ( elem.nodeType === 3 || elem.nodeType === 4 ) {
				ret += elem.nodeValue;
			} else if ( elem.nodeType !== 8 ) {
				ret += getText( elem.childNodes );
			};
		};

		return ret;
	};
	
	var code = getText(this);
	new Function(code).call(window);
	
	return this;
};

$(function(){
	// 鎸夐挳瑙﹀彂浠ｇ爜杩愯
	$(document).bind('click', function(event){
		var target = event.target,
			$target = $(target);

		if ($target.hasClass('runCode')) {
			$('#' + target.name).runCode();
		};
	});
	
	// 璺宠浆鍒板ご閮?
	var $footer = $('#footer');
	if (!$footer[0]) return;
	$footer.bind('click', function () {
		window.scrollTo(0, 0);
		return false;
	}).css('cursor', 'pointer')[0].title = '鍥炲埌椤靛ご';
});

// 鐨偆閫夋嫨	
window._demoSkin = function () {
	art.dialog({
		id: 'demoSkin',
		padding: '15px',
		title: 'artDialog鐨偆灞曠ず',
		content: _demoSkin.tmpl
	});
};
_demoSkin.tmpl = function (data) {
	var html = ['<table class="zebra" style="width:480px"><tbody>'];
	for (var i = 0, length = data.length; i < length; i ++) {
		html.push('<tr class="');
		html.push(i%2 ? 'odd' : '');
		html.push('"><th style="width:7em"><a href="?demoSkin=');
		html.push(data[i].name);
		html.push('">');
		html.push(data[i].name);
		html.push('</a></th><td>');
		html.push(data[i].about);
		html.push('</td></tr>');
	};
	html.push('</tbody></table>');
	return html.join('');
}([
	{name: 'default', about: 'artDialog榛樿鐨偆锛岀畝娲侊紝绾疌SS璁捐锛屾棤鍥剧墖锛岄噰鐢╟ss3娓愯繘澧炲己'},
	{name: 'aero', about: 'artDialog 2+鏍囧織鎬х殑鐨偆锛寃indows7姣涚幓鐠冮鏍笺€傛彁渚汸SD婧愭枃浠?<a href="http://code.google.com/p/artdialog/downloads/detail?name=aero.psd&can=2&q=" target="_blank">涓嬭浇</a>'},
	{name: 'chrome', about: 'chrome娴忚鍣?xp)椋庢牸'},
	{name: 'opera', about: 'opera 11娴忚鍣ㄥ唴缃璇濇椋庢牸'},
	{name: 'simple', about: '绠€鍗曢鏍硷紝鏃犲浘鐗囷紝涓嶆樉绀烘爣棰?},
	{name: 'idialog', about: '鑻规灉椋庢牸锛宨Pad Safari鎴朚ac Safari鍏抽棴鎸夐挳灏嗗湪宸﹁竟鏄剧ず'},
	{name: 'twitter', about: 'twitter椋庢牸锛屾棤鍥剧墖'},
	{name: 'blue', about: '钃濊壊椋庢牸'},
	{name: 'black', about: '榛戣壊椋庢牸'},
	{name: 'green', about: '缁胯壊椋庢牸'}
]);

$(function () {
	var $skin = $('#nav-skin');
	if (!$skin[0]) return;
	
	$skin.bind('click', function () {
		_demoSkin();
		return false;
	});
	
	// 鐐逛寒瀵艰埅
	var links = $('#nav')[0].getElementsByTagName("a"),
		URL = document.URL.split('#')[0],
		last = URL.charAt(URL.length - 1);
		
	if (last === '/') {
		links[0].className += ' select';
	} else {
		for (var i=0; i<links.length; i++) {
			if (URL.toLowerCase().indexOf(links[i].href.toLowerCase()) !== -1) {
				links[i].className += ' select';
			};
		};
	};	
});



// firebug
(function () {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = 'https://getfirebug.com/firebug-lite.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

// google-analytics
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-19823759-2']);
_gaq.push(['_setDomainName', '.planeart.cn']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
