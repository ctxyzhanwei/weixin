<?php include($this->showManageTpl('header','manage'));?>
<script type="text/javascript" src="/js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="/js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="/js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
    window.addEvent('domready', function(){
        new FormCheck('myform');
    });
</script>
<div class="columntitle">新浪微博API配置</div>
   <form method="post" action="?m=sina&c=m_sina&a=init" id="myform">
            <table class="addTable">
        <tr>
          <th>开启微博API</th>
          <td><select name="info[open]"><option value="0"<?php if (!loadConfig('sina','open')){echo 'selected';}?>>关闭</option><option value="1"<?php if (loadConfig('sina','open')){echo 'selected';}?>>打开</option></select></td>
        </tr>
        <tr>
          <th>App Key</th>
          <td><input type="text"  name="info[appKey]" size="20" class="validate['required'] colorblur" value="<?php echo loadConfig('sina','appKey');?>"> <a href="http://open.t.sina.com.cn/wiki/index.php/%E8%BF%9E%E6%8E%A5%E5%BE%AE%E5%8D%9A" target="_blank">注册</a></td>
        </tr>
        <tr>
          <th>App Secret</th>
          <td><input type="text"  name="info[appSecret]" size="60" class="validate['required'] colorblur" value="<?php echo loadConfig('sina','appSecret');?>"> </td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td><input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>