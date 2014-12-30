<?php include($this->showManageTpl('header','manage'));?>
<?php include_once(ABS_PATH.CMS_DIR.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'zh_cn.php');?>
    <div class="columntitle"><?php echo lang_createIndex(); ?>（<?php echo $thisSite->name;?>）</div>
<TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="overflow:auto;background-color:#fff;">
           <div style="padding:20px;"><div style="height:20px;width:404px;background:#eee;float:left;"><div style="width:2px;float:left;"><img src="image/vote/b2_l.gif"></img></div><div id="loading" rel="0" style="float:left;height:20px;width:400px;background:url(image/vote/b2.gif);"></div><div style="width:2px;float:left;"><img src="image/vote/b2_r.gif"></img></div></div></div>
           <div style="padding:10px 20px;" id="result">生成完成，<a href="<?php if ($thisSite->id>1){echo $thisSite->url;}else{echo 'http://'.$_SERVER['HTTP_HOST'];}?>" target="_blank">点击浏览<?php echo $thisSite->name;?>首页</a></div>
           
          </TD>
        </TR>
      </TBODY>
    </TABLE>
<?php include($this->showManageTpl('footer','manage'));?>