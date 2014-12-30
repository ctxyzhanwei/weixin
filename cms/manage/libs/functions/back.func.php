<?php
function pages($total,$page,$pageSize,$url){
	$str='';
	if ($total>$pageSize){
		if ($total%$pageSize){
			$totalPage=intval($total/$pageSize)+1;
		}else {
			$totalPage=intval($total/$pageSize);
		}
		
		$str.='<div style="padding:10px;">';
		$str.='<table id="spContents" cellspacing="0" border="0" border="0" style="width:100%;border-collapse:collapse;"><tr style="height:25px;"><td valign="middle">';
		$nextPageNum=$page+1;
		$prePageNum=$page-1;
		$nextStr='&nbsp;&nbsp;<a href="'.$url.'page='.$nextPageNum.'" style="text-decoration:none;"><img title="下一页" src="image/arrow_next.gif" style="border-width:0px;" />&nbsp;下一页</a>&nbsp;&nbsp;<a href="'.$url.'page='.$totalPage.'" style="text-decoration:none;"><img title="末页" src="image/arrow_last.gif" style="border-width:0px;" />&nbsp;末页</a>';
		$preStr='<a href="'.$url.'page=1" style="text-decoration:none;"><img title="首页" src="image/arrow_first.gif" style="border-width:0px;" />&nbsp;首页</a>&nbsp;&nbsp;<a href="'.$url.'page='.$prePageNum.'" style="text-decoration:none;"><img title="上一页" src="image/arrow_prev.gif" style="border-width:0px;" />&nbsp;上一页</a>';
		if ($page<2){
			$preStr='<img title="首页" src="image/arrow_first_d.gif" style="border-width:0px;" />&nbsp;<span style="color:gray;">首页</span>&nbsp;&nbsp;<img title="上一页" src="image/arrow_prev_d.gif" style="border-width:0px;" />&nbsp;<span style="color:gray;">上一页</span>';
		}elseif ($page>$totalPage-1){
			$nextStr='&nbsp;&nbsp;<img title="下一页" src="image/arrow_next_d.gif" style="border-width:0px;" />&nbsp;<span style="color:gray;">下一页</span>&nbsp;&nbsp;<img title="末页" src="image/arrow_last_d.gif" style="border-width:0px;" />&nbsp;<span style="color:gray;">末页</span>';
		}

		$str.=$preStr.$nextStr.'</td><td align="right" valign="top">';
		$pageOption='';
		for ($i=1;$i<$totalPage+1;$i++){
			if ($i!=$page){
				$pageOption.='<option value="'.$i.'">'.$i.'</option>';
			}else {
				$pageOption.='<option value="'.$i.'" selected>'.$i.'</option>';
			}
		}
		$str.='共'.$total.'条记录&nbsp;&nbsp;当前页<select onchange="window.location.href=\''.$url.'page=\'+this.value+\'\'">'.$pageOption.'</select>';
		$str.='</table>';
		$str.='</div>';
	}else {
		$str.='<div style="padding:10px;text-align:right">共'.$total.'条记录</div>';
	}
	return $str;
}
/**
 * 提交转换提醒页面
 *
 * @param unknown_type $tip
 * @param unknown_type $url
 */
function showMessage($tip,$url='',$interval=2000){
	$seconds=$interval/1000;
	if ($url){
		$r='<div style="color:#888">'.$seconds.'秒后将自动跳转...如果您的浏览器不能跳转，<a href="'.$url.'">请点击这里</a></div>';
		$iconUrl='image/loading.gif';
	}else {
		$r='';
		$interval=100000000000000000;
		$iconUrl='image/success.png';
	}
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.CHARSET.'" />
<link href="style/style.css" type="text/css" rel="stylesheet">
<script src="js/mootools1.3.js"></script>
<script src="js/mootools-more.js"></script>
<title></title>
<script>
window.addEvent(\'domready\',function(){
	(function(){window.location.href=\''.$url.'\';}).delay('.$interval.');
})
</script>
</head>
<body id="body">
<DIV class="column">
<div class="columntitle">提示</div>
<table height="380" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td align="center" valign="middle" style="font-size:12px; line-height:220%">
	<div style="margin-bottom:10px;font-size:14px;"><img src = "'.$iconUrl.'" width="16" height="16" />&nbsp;&nbsp;'.$tip.'</div>'.$r.'</td>
  </tr>
</table>
</div>
</body>
</html>';
}
function mktimes($y,$m,$d,$h=0,$mimute=0){
	return mktime($h,$mimute,0,$m,$d,$y);
}
?>