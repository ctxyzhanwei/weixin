<?php include($this->showManageTpl('header','manage'));?>
<div class="columntitle">服务器信息</div>
<div style="border:1px solid #E9EFF7;">
<div style="padding:10px 20px;">
<?php
echo '服务器信息：'.$_SERVER['SERVER_SOFTWARE'];
?>
</div>
</div>
<?php include($this->showManageTpl('footer','manage'));?>