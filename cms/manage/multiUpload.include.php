<!--multi upload start-->
<link href="style/multiUpload.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../library/fancyUpload/source/Swiff.Uploader.js"></script>
<script type="text/javascript" src="../library/fancyUpload/source/Fx.ProgressBar.js"></script>
<script type="text/javascript" src="../library/fancyUpload/source/FancyUpload2.js"></script>
<script src="js/multiUpload.js"></script>
<?php
//access
$randNum=rand(0,9999).time();
$_SESSION['multiUploadRandNum']=$randNum;
?>
<div id="multiUpload">
<div id="oper"><div style="float:right;width:20%;text-align:right;"><a href="javascript:void(0);" onclick="$('multiUpload').setStyle('display','none')">关闭</a></div><div style="float:left;width:78%;"><a href="javascript:void(0);" id="folderMP"><span id="status">收起批量上传窗口</span></a></div><div style="clear:both"></div></div>
<div id="multiUploadDiv">
<form action="?m=article&c=article&a=picUpload&randNum=<?php echo $randNum;?>" method="post" enctype="multipart/form-data" id="form-demo">
    <TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="padding-left:10px;">
          <a href="javascript:void(0)" id="demo-browse"><img style="MARGIN-RIGHT: 3px" src="image/add.png" align="absmiddle" />选取文件(双击)</a> 
          <a href="#" id="demo-upload"><img style="MARGIN-RIGHT: 3px" src="image/success.png" align="absmiddle" />开始上传</a>
			<a href="#" id="demo-clear"><img style="MARGIN-RIGHT: 3px" src="image/cancel.gif" align="absmiddle" />清除</a> 
            
          </TD>
        </TR>
      </TBODY>
    </TABLE>
    

	<fieldset id="demo-fallback" style="display:none">
		<legend></legend>

		<label for="demo-photoupload">
			<input type="file" name="Filedata" />
		</label>
	</fieldset>

	<div id="demo-status" class="hide" style="margin:5px;">
		<div>
			<div class="overall-title"></div>
			<img src="../library/fancyUpload/assets/progress-bar/bar.gif" width="220" class="progress overall-progress" />
		</div>
		<div>
			<div class="current-title"></div>
			<img src="../library/fancyUpload/assets/progress-bar/bar.gif" width="220" class="progress current-progress" />
		</div>
		<div class="current-text"></div>
	</div>

	<ul id="demo-list" style="margin:10px 0;"></ul>

</form>
</div>
</div>
<!--multi upload end-->