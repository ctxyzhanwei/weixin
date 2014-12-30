<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">
<?php
if ($infoType=='specialCat'){
	echo '专题类别('.$thisCat['name'].')默认文章分类';
	$addUrl='?m=special&c=m_special&a=catChannelSet&catid='.$thisCat['id'];
}elseif ($infoType=='special'){
	echo '专题('.$thisSpecial['name'].')栏目管理';
	$addUrl='?m=special&c=m_special&a=catChannelSet&specialid='.$thisSpecial['id'];
}
?>
</div>
    <div class="oper"><a href="<?php echo $addUrl;?>" class="add">添加</a><a href="###" onclick="$('form').submit()" class="taxis">排序(数值越小越靠前)</a><a href="###" onclick="$('form').setProperty('action','?m=special&c=m_special&a=action_deleteChannel');$('form').submit()" class="delete">删除</a><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back">返回</a></div>
    <form method="POST" id="form" action="">
    <?php
    if ($infoType=='specialCat'){
    	echo '<input type="hidden" value="'.$thisCat['id'].'" name="catid" />';
    }elseif ($infoType=='special'){
    	echo '<input type="hidden" value="'.$thisSpecial['id'].'" name="specialid" />';
    }
    
    ?>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
		    <td>ID</td>
			<td>名称</td>
			<td>索引</td>
			<td>顺序</td>
			<td>操作</td>
		</tr>
<?php
if ($channels){
	foreach($channels as $k=>$v) {
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="id[]"></td>
        <td><?php echo $v['id']?></td>
		<td><?php echo $v['name']?></td>
		<td><?php echo $v['cindex']?></td>
		<td><input type="text" style="width:30px;" name="taxis[<?php echo $v['id']; ?>]" class="colorblur" onfocus="this.className='colorfocus';" onblur="this.className='colorblur';" value="<?php echo $v['taxis']; ?>"></input></td>
		<td><a href="?m=special&c=m_special&a=catChannelSet&<?php if ($infoType=='special'){echo 'specialid='.$thisSpecial['id'];}elseif ($infoType=='specialCat'){echo 'catid='.$thisCat['id'];} ?>&id=<? echo $v['id']; ?>">修改</a> <a href="?m=article&c=m_article&a=articles&id=<?php echo $v['id'];?>&site=<?php echo $thisSpecial['id'];?>">文章管理</a></td>
    </tr>
<?php
	}
}
?>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>