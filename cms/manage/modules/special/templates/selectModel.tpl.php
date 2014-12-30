<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">选择专题模型</div>
<div class="oper"><a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="back">返回</a> <a href="?m=special&c=m_special&a=specialSet&modelid=0" style="color:#f00" class="taxis">不使用任何模型，直接创建专题</a></div>
<div class="ftip">使用模型创建专题，可以直接把模型中的模板和栏目直接导入，节省您的时间</div>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
		    <td>ID</td>
			<td>名称</td>
			<td>简介</td>
			<td>预览图</td>
			<td>选择</td>
		</tr>
<?php
if ($ms){
	foreach($ms as $k=>$v) {
?>
    <tr>
        <td><?php echo $v['id']?></td>
		<td><?php echo $v['name']?></td>
		<td><?php echo $v['intro']?></td>
		<td><a href="/templates/specialModel/<? echo $v['enname']; ?>/logo.jpg" target="_blank">预览图</a></td>
		<td><a href="?m=special&c=m_special&a=specialSet&modelid=<?php echo $v['id'];?>">使用该模型创建专题</a></td>
    </tr>
<?php
	}
}
?>
</table>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>