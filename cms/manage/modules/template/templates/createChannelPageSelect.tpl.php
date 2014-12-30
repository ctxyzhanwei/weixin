<?php include($this->showManageTpl('header','manage'));?>
<div id="urls" style="display:none"></div>
    <div class="columntitle"><?php if ($_GET['type']=='channel'){echo '生成栏目页';}else{echo '生成内容页';}; ?></div>
<TABLE width="100%" border="0" align="center" cellPadding="0" cellSpacing="0">
      <TBODY>
        <TR class="summary-title" align="left">
          <TD align="left" valign="middle" style="overflow:auto;background-color:#fff;padding:10px 20px;">

          <form id="createp" action="" method="POST">
          <select multiple="multiple" id="channels" name="channels[]" style="height:300px;">
          <?php
          echo $selectOptionStr;
          ?>
          </select>
          <div style="padding:10px 0;" id="divSubmit">
          <div style="padding:0 20px;"><input name="doSubmit" type="submit" class="button" id="btnSubmit" value="<?php echo '生成'; ?>"></input></div>
          </div>
          </form>

           <div id="error" style="border:1px solid #ffcc80; padding:10px; color:#f00; background:#fffaf2;display:none"></div>
          </TD>
        </TR>
      </TBODY>
    </TABLE>
<?php include($this->showManageTpl('footer','manage'));?>