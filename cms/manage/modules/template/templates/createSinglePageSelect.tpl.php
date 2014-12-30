<?php include($this->showManageTpl('header','manage'));?>
<?php include_once(ABS_PATH.CMS_DIR.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'zh_cn.php');?>
<script>
function submitCheck(){
	if(!$('singlePages').value){
		alert('请选择要生成的单页');
		return false;
	}else{
		return true;
	}
}
</script>

    <div class="columntitle"><?php echo lang_createSinglePage(); ?></div>
    
<TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="overflow:auto;background-color:#fff;padding:10px 20px;">

          <div class="tip" style="padding:10px;">按住shift拖动鼠标可以多选，按住ctrl点击也可以多选</div>
          <form id="createp" action="" method="POST" onsubmit="return submitCheck()">
          <select multiple="multiple" id="singlePages" name="singlePages[]" style="height:300px;">
          <?php
         echo $optionStr;
          ?>
          </select>
          <div style="padding:10px 0;" id="divSubmit">
          <div style="padding:0 20px;"><input type="submit" name="doSubmit" id="btnSubmit" value="<?php echo lang_create(); ?>"></input></div>
          </div>
          </form>
 
          
           <div id="error" style="border:1px solid #ffcc80; padding:10px; color:#f00; background:#fffaf2;display:none"></div>
          </TD>
        </TR>
      </TBODY>
    </TABLE>

<?php include($this->showManageTpl('footer','manage'));?>