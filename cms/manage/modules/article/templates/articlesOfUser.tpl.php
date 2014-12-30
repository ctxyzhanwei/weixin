<?php include($this->showManageTpl('header','manage'));?>
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="/js/calendar.js"></script>
<script>
function search(){
	location.href='?m=article&c=article&a=articlesOfUser&uid='+$('uid').value+'&startdate='+$('startdate').value+'&enddate='+$('enddate').value;
}
window.addEvent('domready',function(){
	$('uid').value=<?php echo intval($_GET['uid']); ?>;
})
</script>
<div class="columntitle">按管理员查看文章</div>
    <div class="oper">
    选择管理员：<select id="uid">
    <option value="0">全部</option>
    <?php
    if ($admins){
    	foreach ($admins as $admin){
    		echo '<option value='.$admin['uid'].'>'.$admin['username'].'('.$admin['realname'].')</option>';
    	}
    }
    ?>
    </select>
    截止日期(包含选择的日期)：<input name="startdate" value="<?php echo $startDate; ?>" id="startdate" style="height:18px;font-size:12px;width:70px;" class="colorblur" rel="calendar"></input> - <input name="enddate" value="<?php echo $endDate; ?>" id="enddate" style="height:18px;font-size:12px;width:70px;" class="colorblur" rel="calendar"></input><div id="calendarDiv"></div> <input class="button" type="button" style="cursor:pointer" onclick="search()" value="查询" />
    </div>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="center">ID</td>
			<td>标题</td>
			<td align="center">时间</td>
			<td align="center">操作</td>
		</tr>
<?php
	foreach($articles as $v) {
?>
    <tr>
		<td align="center"><?php echo $v['id'];?></td>
		<td><?php echo $v['title'];?></td>
		<td align="center"><?php echo date('Y-m-d H:i',$v['time']);?></td>
		<td align="center"><a href="<?php echo $v['link'];?>" target="_blank">访问</a></td>
    </tr>
<?php
	}
?>
</table>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>