<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<meta name="robots" content="noindex,nofollow" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<script src="js/mootools1.3.js"></script>
<script src="js/mootools-1.2-plugin.js"></script>
<script src="js/a.js"></script>
<script src="js/tree.js"></script>
<title></title>
</head>

<body>
<style>
a,a:link,a:hover,a:visited{color:#555555}
</style>
<div class="e a_Tree" style="margin-left:2px;">
<div class="leftMiddleTop" style="margin-top:10px;"><?php if ($_REQUEST['type']=='channel'){echo '栏目管理';}else{echo '内容管理';} ?></div>
<div style="padding:0 0 0 10px; margin-top:-10px;">
<?php
echo $str;
?>
</div>

</div>
</body>
</html>