<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信营销系统 3G网站管理平台</title>
<script src="js/mootools1.3.js"></script>
<script src="js/mootools-more.js"></script>
<SCRIPT>
window.addEvent('domready',function(){
	//(function(){$('rightFrame').src='admin.php?m=manage&c=background&a=home';}).delay(1000);
})
function changeDisplayMode(){
	if($("bottomframes").cols=="181,7,*"){
		$("bottomframes").cols="0,7,*"; 
		$("separator").contentWindow.document.getElementById('ImgArrow').src="image/separator2.gif"
	}else{
		$("bottomframes").cols="181,7,*"
		$("separator").contentWindow.document.getElementById('ImgArrow').src="image/separator1.gif"
	}
}
</SCRIPT>
</head>

<FRAMESET 
id=mainframes border=false frameSpacing=0 rows=84,* frameBorder=0 
scrolling="yes"><FRAME name=top 
src="admin.php?m=manage&c=background&a=frameTop" scrolling=no><FRAMESET 
id=bottomframes border=false frameSpacing=0 frameBorder=0 cols=181,7,* 
scrolling="yes"><FRAME name=left marginWidth=0 marginHeight=0 
src="admin.php?m=manage&c=background&a=frameLeft" 
noResize><FRAME id=separator name=separator
src="frameSeparator.htm" noResize 
scrolling=no>
<FRAME id="rightFrame" name=right src="admin.php?m=manage&c=background&a=home"></FRAMESET></FRAMESET><noframes></noframes>
</html>
