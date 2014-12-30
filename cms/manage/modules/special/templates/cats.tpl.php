<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">专题类别</div>
    <div class="oper"><a href="?m=special&c=m_special&a=catSet" class="add">添加</a><a href="###" onclick="$('form').submit()" class="taxis">排序</a></div>
    <form method="POST" id="form" action="?m=special&c=m_special&a=cats">
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('cronid[]');"></td>
		    <td>ID</td>
			<td>名称</td>
			<td>顺序</td>
			<td>操作</td>
		</tr>
<?php
if ($cats){
	foreach($cats as $k=>$v) {
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="cronid[]"></td>
        <td><?php echo $v['id']?></td>
		<td><?php echo $v['name']?></td>
		<td><input type="text" style="width:30px;" name="taxis[<?php echo $v['id']; ?>]" class="colorblur" onfocus="this.className='colorfocus';" onblur="this.className='colorblur';" value="<?php echo $v['taxis']; ?>"></input></td>
		<td><a href="?m=special&c=m_special&a=catSet&id=<? echo $v['id']; ?>">修改</a> <a href="?m=special&c=m_special&a=catChannels&catid=<? echo $v['id']; ?>" style="display:none">默认文章分类</a><?php if (!$v['syscat']){ ?> <a href="?m=special&c=m_special&a=action_catDelete&id=<? echo $v['id']; ?>">删除</a><?php }?></td>
    </tr>
<?php
	}
}
?>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>