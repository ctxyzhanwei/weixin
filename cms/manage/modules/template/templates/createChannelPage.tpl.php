<?php include($this->showManageTpl('header','manage'));?>
<?php include_once(ABS_PATH.CMS_DIR.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'zh_cn.php');?>
<div id="urls" style="display:none"></div>
    <div class="columntitle"><?php if ($_GET['type']=='channel'){echo lang_createChannel();}else{echo lang_createContent();}; ?></div>
<TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="overflow:auto;background-color:#fff;padding:10px 20px;">
          <div id="status">
          <div style="padding:20px;"><div style="height:20px;width:404px;background:#eee;float:left;"><div style="width:2px;float:left;"><img src="image/vote/b2_l.gif"></img></div><div id="loading" rel="0" style="float:left;height:20px;background:url(image/vote/b2.gif);width:<?php echo $progressBarWidth;?>px"></div><div style="width:2px;float:left;"><img src="image/vote/b2_r.gif"></img></div></div></div>
          <div style="padding:10px 20px;"><div id="result"><?php echo $tip; ?></div></div>
           </div>
           <div id="error" style="border:1px solid #ffcc80; padding:10px; color:#f00; background:#fffaf2;display:none"></div>
          </TD>
        </TR>
      </TBODY>
    </TABLE>
<?php
if ($_GET['type']=='channel'){
	if ($nextI!=$count){
		echo '<script>window.location.href=\'?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['siteid'].'&type=channel&i='.$nextI.'\';</script>';
	}
}elseif ($_GET['type']=='content'){
	if ($nextI<$count){
		echo '<script>window.location.href=\'?m=template&c=createHtml&a=createChannelPage&siteid='.$_GET['siteid'].'&type=content&i='.$nextI.'\';</script>';
	}
}
?>
<?php include($this->showManageTpl('footer','manage'));?>