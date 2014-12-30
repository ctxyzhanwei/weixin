<?php include($this->showManageTpl('header','manage'));?>
<div style="padding:20px;">
<?php
if ($channels){
	foreach($channels as $k=>$v) {
		echo '<a href="?m=article&c=m_article&a=articleSet&channelid='.$v['id'].'&site='.$v['specialid'].'" target="_parent">'.$v['name'].'&nbsp;&nbsp;</a>';
	}
}
?>
</div>
<?php include($this->showManageTpl('footer','manage'));?>