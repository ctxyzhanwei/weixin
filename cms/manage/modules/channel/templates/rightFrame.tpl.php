<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="robots" content="noindex,nofollow" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<script src="/js/mootools1.3.js"></script>
<script src="/js/mootools-1.2-plugin.js"></script>
<SCRIPT>
window.addEvent('domready',function(){
	(function(){if($('srightFrame')){$('srightFrame').src='intro.php';}}).delay(1000);
})

</SCRIPT>
<title></title>
</head>
<FRAMESET id=bottomframes border=false frameSpacing=0 frameBorder=0 cols=180,* scrolling="yes">
<FRAME name=sleft marginWidth=0 marginHeight=0 src="?m=channel&c=m_channel&a=channelTree&siteid=<?php echo $_GET['siteid'];?>&type=<?php echo $_GET['type'];?>" noResize>
<?php
if ($_GET['type']=='channel'){
?>
<FRAME name=sright id="sright" src="?m=channel&c=m_channel&a=channels&id=<?php echo $homeChannel['id'];?>&siteid=<?php echo $_GET['siteid'];?>"></FRAMESET><noframes></noframes></FRAMESET>
<?php
}else {
?>
<FRAME id="srightFrame" name=sright src="?m=manage&c=background&a=home"></FRAMESET></FRAMESET>
<?php	
}
?>

</html>