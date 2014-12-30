<?php
if (!function_exists('pages')){
function pages(){
	
}
}
function mobilePages($total,$currentPage,$pageSize,$prefix='',$suffix='',$firstPageUrl=''){
	if ($pageSize){
		if ($total%$pageSize){
			$totalPage=intval($total/$pageSize)+1;
		}else {
			$totalPage=intval($total/$pageSize);
		}
	}
	if ($totalPage){
		$prePage=$currentPage-1;
		$nextPage=$currentPage+1;
		if ($currentPage==1||$totalPage==1){
			$prePageStr='<span class="up disable">上一页</span>';
		}else {
			$prePageStr='<a class="up" href="'.$prefix.$prePage.$suffix.'">上一页</a>';
		}
		if ($currentPage==$totalPage||$totalPage==1){
			$nextPageStr='<span class="down disable">下一页</span>';
		}else {
			$nextPageStr='<a class="down" href="'.$prefix.$nextPage.$suffix.'">下一页</a>';
		}
		return '<div id="divPager" class="page"><div class="page_hand">'.$prePageStr.'<span class="page_count"><b>'.$currentPage.'</b>/'.$totalPage.'页</span>'.$nextPageStr.'</div></div>';
	}
}
if (!function_exists('foregroundPage')){
function foregroundPage($total,$currentPage,$pageSize,$prefix='',$suffix='',$firstPageUrl=''){
	if ($pageSize){
		if ($total%$pageSize){
			$totalPage=intval($total/$pageSize)+1;
		}else {
			$totalPage=intval($total/$pageSize);
		}
	}
	$option='';
	for ($i=1;$i<$totalPage+1;$i++){
		if (intval($currentPage)!=$i){
			$option.='<option value="'.$i.'">第'.$i.'页</option>';
		}else {
			$option.='<option value="'.$i.'" selected>第'.$i.'页</option>';
		}
	}
	$prePage=$currentPage-1;
	$nextPage=$currentPage+1;
	if ($firstPageUrl){
		$first='<a href="'.$firstPageUrl.'">首页</a>';
	}else {
		$first='<a href="'.$prefix.'1'.$suffix.'">首页</a>';
	}
	$pre='<a href="'.$prefix.$prePage.$suffix.'">上一页</a>';
	$next='<a href="'.$prefix.$nextPage.$suffix.'">下一页</a>';
	$last='<a href="'.$prefix.$totalPage.$suffix.'">尾页</a>';
	if ($currentPage==1){
		$first='';
		$pre='';
	}
	if ($currentPage==$totalPage){
		$next='';
		$last='';
	}
	if ($totalPage>1){
		return '<div id="page"><span>转到&nbsp;<select onchange="window.location.href=\''.$prefix.'\'+this.value+\''.$suffix.'\'" size="1">'.$option.'</select></span>共'.$total.'条 <font color="#FF0000">'.$currentPage.'</font>/'.$totalPage.'页　　'.$first.' '.$pre.' '.$next.' '.$last.' </div>';
	}else {
		return '';
	}
}
}
/**
 * 前台提交转换提醒页面
 *
 * @param unknown_type $tip
 * @param unknown_type $url
 */
if (!function_exists('showMessage')){
function showMessage($tip,$url,$interval=2000,$titleTip=1,$errorTip=0){
	//如果要自定义，请在同文件夹的extention.func.php写一个fgShowMessage_ext函数
	if (!function_exists('fgShowMessage_ext')){
		$seconds=$interval/1000;
		if ($titleTip){
			$metaTitle=$tip.'_'.SITE_NAME;
		}else {
			$metaTitle=SITE_NAME;
		}
		if (!$errorTip){
			$iconUrl=JS_URL_ROOT.'/artDialog4.1.6/skins/icons/succeed.png';
		}else {
			$iconUrl=JS_URL_ROOT.'/artDialog4.1.6/skins/icons/error.png';
		}
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<script src="'.JS_URL_ROOT.'/mootools1.3.js"></script>
<script src="'.JS_URL_ROOT.'/mootools-more.js"></script>
<title>'.$metaTitle.'</title>
<script>
window.addEvent(\'domready\',function(){
	(function(){window.location.href=\''.$url.'\';}).delay('.$interval.');
})
</script>
</head>
<body id="body">
<style>
body {margin:0;padding:0;background:#f8f8f8}
div { font-size:12px;}
a:link {COLOR: #0a4173; text-decoration:none;}
a:visited {COLOR: #0a4173; text-decoration:none;}
a:hover {COLOR: #1274ba; text-decoration:none;}
a:active {COLOR: #1274ba; text-decoration:none;}
</style>
<div style="background:#fff;font-size:14px;width:600px; margin:60px auto; line-height:48px;height:48px;text-align:center;padding:60px 30px;border:5px solid #f3f3f3">
	<img src = "'.$iconUrl.'" align="absmiddle" />&nbsp;&nbsp;'.$tip.' <span style="font-size:12px;color:#999">'.$seconds.'秒后将自动跳转，如果您的浏览器不能跳转</span> <a style="font-size:12px;" href="'.$url.'">请点击</a></span>
</div>
</body>
</html>';
	}else {
		fgShowMessage_ext($tip,$url,$interval);
	}
}
}
?>