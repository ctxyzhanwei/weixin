<?php
include($this->showManageTpl('header','manage'));
$config=loadConfig('sitemap');
$articleCount=$config['articleCount']?$config['articleCount']:500;
$ucarCount=$config['ucarCount']?$config['ucarCount']:500;
?>
<script type="text/javascript" src="js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
    });
</script>
<div class="columntitle">sitemap配置信息</div>
   <form method="post" action="?m=ucar&c=m_ucar&a=config" id="myform">
            <table class="addTable">
            <tr style="line-height:38px"><td class="STYLE2" style="text-align: right;">前多少条新闻&nbsp;</td><td><input type="text" value="<?php echo $articleCount;?>" class="validate['required','digit'] colorblur" onfocus="this.className=\'colorfocus\'" onblur="this.className=\'colorblur\'" name="info[articleCount]" tyle="width:260px;height:20px;font-size:14px;" /> <span class="tdtip">sitemap中包含最新的多少条新闻</span></td></tr>
            <tr style="line-height:38px"><td class="STYLE2" style="text-align: right;">前多少条二手车&nbsp;</td><td><input type="text" value="<?php echo $ucarCount;?>" class="validate['required','digit'] colorblur" onfocus="this.className=\'colorfocus\'" onblur="this.className=\'colorblur\'" name="info[ucarCount]" style="width:260px;height:20px;font-size:14px;" /> <span class="tdtip">sitemap中包含最新的多少条二手车</span></td></tr>
          <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>