<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">
<?php
echo '栏目管理';
$addUrl='?m=channel&c=m_channel&a=channelSet&parentid='.$parentChannelID.'&siteid='.$siteid;
?>
</div>

<div style="border:1px solid #E9EFF7;margin-top:10px;">
    <div class="oper" style="border:none"><a href="<?php echo $addUrl;?>" class="add">添加</a><a href="###" onclick="$('form').submit()" class="taxis">排序(数值越小越靠前)</a><a href="###" onclick="$('form').setProperty('action','?m=channel&c=m_channel&a=action_deleteChannel');$('form').submit()" class="delete">删除</a><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back">返回</a></div>
    <form method="POST" id="form" action="">
    <?php
   echo '<input type="hidden" value="'.$siteid.'" name="siteid" />';
    ?>
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
		    <td align="center">ID</td>
			<td>名称</td>
			<td>索引</td>
			<td align="center">导航显示</td>
			<td align="center">首页显示</td>
			<td align="center">顺序</td>
			<td align="center">操作</td>
		</tr>
<?php
if ($channels){
	foreach($channels as $k=>$v) {
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="id[]"></td>
        <td align="center"><?php echo $v['id']?></td>
		<td><?php echo $v['name']?></td>
		<td><?php echo $v['cindex']?></td>
		<td align="center"><?php if($v['isnav']){echo '<img src="image/default.gif" />';}?></td>
		<td align="center"><?php if($v['homepicturechannel']){echo '<img title="首页图片栏目" src="image/picture.png" />';}elseif($v['hometextchannel']){echo '<img title="首页文字栏目" src="image/items.gif" />';}?></td>
		<td align="center"><input type="text" style="width:30px;" name="taxis[<?php echo $v['id']; ?>]" class="colorblur" onfocus="this.className='colorfocus';" onblur="this.className='colorblur';" value="<?php echo $v['taxis']; ?>"></input></td>
		<td align="center"><a href="?m=channel&c=m_channel&a=channelSet&parentid=<?php echo $v['parentid']; ?>&siteid=<?php echo $siteid; ?>&id=<?php echo $v['id']; ?>">修改</a> <a href="?m=article&c=m_article&a=articles&id=<?php echo $v['id'];?>&site=<?php echo $siteid;?>">文章管理</a></td>
    </tr>
<?php
	}
}
?>
</table>
</form>
</div>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>