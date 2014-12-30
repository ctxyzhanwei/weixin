<?php include($this->showManageTpl('header','manage'));?>
<script type="text/javascript" src="/js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="/js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="/js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<script type="text/javascript">
window.addEvent('domready', function(){
	new FormCheck('myform');
});
</script>
    <div class="columntitle">模板管理</div>
    <div class="oper">
           <a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="back">返回</a>
        </div>
        
  <form id="myform" action="?m=template&c=m_template&a=templateSet" method="POST">
       <table class="addTable">
       <tr><th style="width:60px;">模板名称:</th><td valign="middle"><input type="text" value="<?php echo $thisTemplate['name'];?>" name="info[name]" id="name" style="width:400px;" class="validate['required'] colorblur" onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');"></input></td>
       </tr>
       <tr style="display:none"><th>保存路径:</th><td><input type="text" name="info[path]" id="path" value="<?php echo $thisTemplate['path'];?>" style="width:400px;" class="validate['required'] colorblur"></input></td>
         </tr>
       <tr style="display:none"><th>生成路径:</th><td>
       <?php
       if (!isset($_GET['id'])){
       $defaultGPath='@/templates/';
       switch ($type){
       	case 2:
       		 $defaultGPath='@/channels/{channelIndex}/index.html';
       		break;
       	case 3:
       		$defaultGPath='@/contents/{channelIndex}/{year}/{month}/{day}/{contentID}.shtml';
       		break;
       	case 5:
       		$defaultGPath='@/{folder}/{catIndex}/{specialIndex}/index.html';
       		break;
       }
       $thisTemplate['generate_path']=$thisTemplate['generate_path']?$thisTemplate['generate_path']:$defaultGPath;
       }
       ?>
       <input type="text" name="info[generate_path]" value="<?php echo $thisTemplate['generate_path'];?>" id="generatePath" style="width:460px;" class="validate['required'] colorblur"></input>
       <?php
         if ($type!=5){
         ?>
          <div class="tdtip">栏目索引{channelIndex} 栏目ID{channelID} 内容ID{contentID} 年{year} 月{month} 日{day}</div>
       <?php
         }else {
         	echo '<div class="tdtip">{folder}专题存放文件夹名称 {catIndex}分类索引 {specialIndex}专题索引</div>';
         }
       ?>
       </td>
         </tr>
         <?php
         if (!isset($_GET['id'])){
         ?>
       <tr style="display:none"><th>模板类型:</th><td>
       <select name="info[type]" id="type" class="validate['required'] colorblur">
       <option value="0">请选择</option>
       <?php
          foreach ($this->cats as $k=>$cat){
          	if ($type==$k){
          		$selected=' selected';
          	}else {
          		$selected='';
          	}
          	echo '<option value="'.$k.'"'.$selected.'>'.$cat.'</option>';
          }
          ?></select>
       </td>
       </tr>
       <?php
         }else {
         	echo '<input type="hidden" name="info[type]" value="'.$thisTemplate['type'].'" />';
         }
       ?>
       <tr style="display:none"><th>默认模板:</td><td>
       <select name="info[isdefault]" id="isdefault">
       <option value="0"<?php if (!$thisTemplate['isdefault']){echo ' selected';}?>>否</option>
       <option value="1"<?php if ($thisTemplate['isdefault']){echo ' selected';}?>>是</option>
       </select>
       </td>
       </tr>
       <tr style="display:none"><th>生成静态:</th><td>
      <select name="info[createhtml]" id="createhtml">
       <option value="0"<?php if (!$thisTemplate['createhtml']){echo ' selected';}?>>否</option>
       <option value="1"<?php if ($thisTemplate['createhtml']){echo ' selected';}?>>是</option>
       </select> <span class="tdtip">仅当摸板类型为栏目模板和内容模板的时候有效，如不生成静态页，则只根据“生成路径”来更新栏目或内容的url地址</span>
       </td>
       </tr>
       <tr><th>模板代码:</th><td><textarea onfocus="this.removeClass('colorblur');this.addClass('colorfocus');" onblur="this.removeClass('colorfocus');this.addClass('colorblur');" class="colorblur" name="code" id="code" style="width:90%;font-size:12px;height:380px;overflow:auto;border:1px solid #86a1ba"><?php echo $thisTemplate['code'];?></textarea></td></tr>
       <tr><th></th><td><input type="submit" class="button" value="提交"></input></td></tr>
       </table>
       <input type="hidden" value="1" name="except" />
       <?php if (isset($_GET['id'])){
       	echo '<input type="hidden" name="id" value="'.$_GET['id'].'" />';
       }
       
       ?>
       <div style="text-align:center;" id="subButton"><input type="hidden" name="info[site]" value="<?php echo $_GET['siteid'];?>"></input></div></form>
<?php include($this->showManageTpl('footer','manage'));?>