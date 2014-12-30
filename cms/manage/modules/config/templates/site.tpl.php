<?php include($this->showManageTpl('header','manage'));?>
<script src="js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.6/plugins/iframeTools.js"></script>

<div class="columntitle">网站配置</div>
   <form method="post" action="?m=config&c=config&a=site" id="myform">
     <table class="addTable">
      <tr style="display:none">
          <th>名称</th>
          <td><input type="text" name="info[name]" id="name" size="20" class="validate['required'] colorblur" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" value="<?php echo $homeConfig['title'];?>"> <span class="tdtip"></span></td>
        </tr>
       <tr>
          <th>logo</th>
          <td><input type="text" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" id="logourl" name="info[logourl]" size="60" class="colorblur" value="<?php echo $thisSite['logourl'];?>"> <a href="###" onclick="picUpload('logourl',0,0,'')">上传</a> <a href="###" onclick="viewImg('logourl','预览')">预览</a> <span class="tdtip">格式最好是png的，高度不超过35像素，宽度不超过380像素</span></td>
        </tr>
        <tr style="display:none">
          <th>回复图片</th>
          <td><input type="text" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" id="picurl" name="info[picurl]" size="60" class="colorblur" value="<?php echo $homeConfig['picurl'];?>"> <a href="###" onclick="picUpload('picurl',0,0,'')">上传</a> <a href="###" onclick="upyunPicUpload('picurl',400,300)">上传到云</a> <a href="###" onclick="viewImg('picurl','预览')">预览</a> <span class="tdtip"></span></td>
        </tr>
       <tr style="display:none">
          <th>简介</th>
          <td><textarea class="colorblur" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" onfocus="this.className=\'colorfocus\'" onblur="this.className=\'colorblur\'" name="info[intro]" style="width:460px;height:80px;font-size:12px;"><?php echo $homeConfig['info'];?></textarea></td>
        </tr>
        <tr>
          <th>第三方统计代码</th>
          <td><textarea class="colorblur" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" onfocus="this.className=\'colorfocus\'" onblur="this.className=\'colorblur\'" name="info[statisticcode]" style="width:460px;height:80px;font-size:12px;"><?php echo base64_decode($thisSite['statisticcode']);?></textarea></td>
        </tr>
        <tr>
            <td class="addName"></td>
            <td>
            <input type="hidden" value="<?php echo $_SESSION['token'];?>" name="info[token]" />
            <input type="submit" name="doSubmit" value="提交" class="button"/><input type="hidden" value="1" name="except" /></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>