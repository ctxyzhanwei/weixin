<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">升级执行文件</div>
<?php
if ($logs){
?>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td>描述</td>
			<td>操作</td>
		</tr>
<?php
	foreach($logs as $k=>$v) {
?>
    <tr>
		<td><?php echo $v['des']?></td>
		
		<td>
		<a href="?m=update&c=updateTask&a=<?php echo $v['file']?>">访问执行</a></td>
    </tr>
<?php
	}
?>
</table>
<?php
	}else {
		echo '<div style="padding:20px;">没有升级执行文件</div>';
	}
?>
<?php include($this->showManageTpl('footer','manage'));?>