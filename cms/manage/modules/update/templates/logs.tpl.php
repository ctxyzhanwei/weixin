<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">更新记录</div>
    <div class="oper"><?php if ($shouldUpdate){ ?><a href="?m=update&c=update&a=update" class="add">点击这里进行升级</a><?php }?><?php if ($updateObj->taskShouldEx()){ ?><a href="?m=update&c=update&a=task" class="add">有升级文件需要执行</a><?php }?></div>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
		    <td>更新时间</td>
			<td>描述</td>
		</tr>
<?php
	foreach($logs as $k=>$v) {
?>
    <tr>
        <td><?php echo date('Y-m-d G:i',$v['logtime']); ?></td>
		<td><?php echo $v['des']?></td>
    </tr>
<?php
	}
?>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>