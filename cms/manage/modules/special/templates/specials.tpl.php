<?php include($this->showManageTpl('header','manage'));?>
<script src="/js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="/js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script>
function addArticle(specialid){
	art.dialog.open('?m=special&c=m_special&a=selectChannel&specialid='+specialid,{lock:false,title:'选择栏目',width:400,height:200,yesText:'关闭',background: '#000',opacity: 0.87});
}
</script>
<div class="columntitle">专题管理</div>
    <div class="oper"><a href="?m=special&c=m_special&a=selectModel" class="add">创建专题</a><a href="###" onclick="$('form').submit()" class="taxis">排序(数值越大越靠前)</a><a href="###" onclick="$('form').setProperty('action','?m=special&c=m_special&a=action_deleteSpecial');$('form').submit()" class="delete">删除</a><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back">返回</a></div>
    <form method="POST" id="form" action="?m=special&c=m_special&a=specials">
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('cronid[]');"></td>
		    <td>ID</td>
			<td>[类别] 名称</td>
			<td align="center">顺序</td>
			<td align="center">操作</td>
			<td align="center">生成</td>
		</tr>
<?php
if ($specials){
	foreach($specials as $k=>$v) {
?>
    <tr>
		<td align="left"><input type="checkbox" value="<?php echo $v['id']?>" name="id[]"></td>
        <td><?php echo $v['id']?></td>
		<td>[<a href="?m=special&c=m_special&a=specials&catid=<?php echo $v['catid']?>"><?php echo $cats[$v['catid']]['name'];?></a>] <?php echo $v['name']?></td>
		<td align="center"><input type="text" style="width:30px;" name="taxis[<?php echo $v['id']; ?>]" class="colorblur" onfocus="this.className='colorfocus';" onblur="this.className='colorblur';" value="<?php echo $v['taxis']; ?>"></input></td>
		<td align="center"><a href="?m=special&c=m_special&a=specialSet&id=<?php echo $v['id']; ?>">修改</a> <a href="?m=special&c=m_special&a=catChannels&specialid=<?php echo $v['id']; ?>">栏目管理</a> <a href="###" onclick="addArticle(<?php echo $v['id']; ?>)">添加文章</a> <a href="<?php echo $v['url']; ?>" target="_blank">预览</a> <a href="?m=special&c=m_special&a=export&specialid=<?php echo $v['id']; ?>">导出为模型</a></td>
		<td align="center"><a href="?m=template&c=createHtml&a=createIndexPage&siteid=<?php echo $v['id']; ?>">生成首页</a> <a href="?m=special&c=m_special&a=createPages&type=channel&specialid=<?php echo $v['id']; ?>">生成栏目</a> <a href="?m=special&c=m_special&a=createPages&type=content&specialid=<?php echo $v['id']; ?>">生成内容</a></td>
    </tr>
<?php
	}
}
?>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>