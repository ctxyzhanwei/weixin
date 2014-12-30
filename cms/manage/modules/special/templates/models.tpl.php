<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">专题模型管理</div>
    <div class="oper"><a href="?m=special&c=m_special&a=importModel" class="add">导入</a><a href="###" onclick="$('form').submit()" class="delete">删除</a></div>
    <form method="POST" id="form" action="?m=special&c=m_special&a=models">
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
		    <td>ID</td>
			<td>名称</td>
			<td>简介</td>
			<td>预览图</td>
		</tr>
<?php
if ($ms){
	foreach($ms as $k=>$v) {
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="id[]"></td>
        <td><?php echo $v['id']?></td>
		<td><?php echo $v['name']?></td>
		<td><?php echo $v['intro']?></td>
		<td><a href="/templates/specialModel/<? echo $v['enname']; ?>/logo.jpg" target="_blank">预览图</a></td>
    </tr>
<?php
	}
}
?>
</table>
<input type="hidden" name="doSumit" />
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>