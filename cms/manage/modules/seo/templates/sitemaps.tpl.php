<?php include($this->showManageTpl('header','manage'));?>
<script src="js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.6/plugins/iframeTools.js"></script>
<div class="columntitle">sitemap管理</div>
<div class="oper"><a href="?m=seo&c=seo&a=createAllSitemap" class="taxis">生成全部</a></div>
    <form method="POST" id="form" action="?m=seo&c=seo&a=sitemaps">
    <input type="hidden" name="doSubmit" value="1" />
  <table cellspacing="1" cellpadding="1" class="listtable">
		<tr class="summary-title">
			<td style="display:none" align="left" width="20"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></td>
		    <td>名称</td>
			<td>操作</td>
		</tr>
<?php
if ($this->sitemapTypes){
	foreach($this->sitemapTypes as $k=>$v) {
?>
    <tr>
		<td style="display:none" align="left"><input type="checkbox" value="<?php echo $v['type'];?>" name="id[]"></td>
		<td><?php echo $v['name']?></td>
		<td><a href="?m=seo&c=seo&a=createSitemap&type=<?php echo $v['type'];?>">生成</a> <a href="/sitemap/<?php echo $v['type'];?>_sitemap.xml" target="_blank">预览</a></td>
    </tr>
<?php
	}
}
?>
</table>
</form>
<div class="pages"><?php echo $pages;?></div>
<?php include($this->showManageTpl('footer','manage'));?>