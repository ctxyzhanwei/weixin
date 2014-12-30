<?php include($this->showManageTpl('header','manage'));?>
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="/js/calendar.js"></script>
<script>
function search(){
	location.href='?m=article&c=article&a=statistics&startdate='+$('startdate').value+'&enddate='+$('enddate').value;
}
</script>
<div class="columntitle">编辑发布文章统计(点击数量可以查看具体的文章列表)</div>
    <div class="oper">
    截止日期(包含选择的日期)：<input name="startdate" value="<?php echo $startDate; ?>" id="startdate" style="height:18px;font-size:12px;width:70px;" class="colorblur" rel="calendar"></input> - <input name="enddate" value="<?php echo $endDate; ?>" id="enddate" style="height:18px;font-size:12px;width:70px;" class="colorblur" rel="calendar"></input><div id="calendarDiv"></div> <input class="button" type="button" style="cursor:pointer" onclick="search()" value="查询" />
    </div>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="center">UID</td>
			<td>姓名</td>
			<td style="display:none">用户名</td>
			<td align="center">文章数量</td>
			<td align="center">经销商文章数量</td>
		</tr>
<?php
	foreach($admins as $v) {
?>
    <tr>
		<td align="center"><?php echo $v['uid'];?></td>
		<td><?php echo $v['realname'];?></td>
		<td style="display:none"><?php echo $v['username'];?></td>
		<td align="center"><a href="?m=article&c=article&a=articlesOfUser&uid=<?php echo $v['uid'];?>&startdate=<?php echo $startDate; ?>&enddate=<?php echo $endDate; ?>"><?php echo $v['articleCount']?></a></td>
		<td align="center"><a href="?m=storeContent&c=storeContent&a=contentsOfUser&uid=<?php echo $v['uid'];?>&startdate=<?php echo $startDate; ?>&enddate=<?php echo $endDate; ?>"><?php echo $v['storeContentCount'];?></a></td>
    </tr>
<?php
	}
?>
</table>
<?php include($this->showManageTpl('footer','manage'));?>