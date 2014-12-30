<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">后台登录日志</div>
<div class="oper"><a href="?m=manage&c=background&a=action_deleteLoginLog" class="delete">删除30天前的日志</a></div>
    <form method="POST" id="form" action="">
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
		    <td>用户名</td>
			<td>密码</td>
			<td>是否成功</td>
			<td>时间</td>
			<td>IP</td>
			<td>来源</td>
		</tr>
<?php
	foreach($logs as $k=>$v) {
?>
    <tr>
        <td><?php echo $v['username']?></td>
		<td><?php if (!$v['success']){echo $v['password'];}?></td>
		<td><img src="image/<?php if ($v['success']){echo 'tick.gif';}else{echo 'cross.png';}?>" /></td>
		<td><?php echo date ( "Y-m-d H:i:s", $v ['time'] );?></td>
		<td><?php
          $ipAddress=@getIPInfo($v['ip']);
          echo '<a href="javascript:void(0)" title="'.$v['ip'].'">'.$ipAddress.'</a>';
          ?></td>
		<td><a href="<?php echo $v['referer']?>" target="_blank"><?php echo $v['referer']?></a></td>
    </tr>
<?php
	}
?>
</table>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>