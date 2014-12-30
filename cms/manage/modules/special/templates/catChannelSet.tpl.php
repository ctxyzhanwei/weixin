<?php include($this->showManageTpl('header','manage'));?>
<script type="text/javascript" src="/js/formCheck/lang/cn.js"> </script>
<script type="text/javascript" src="/js/formCheck/formcheck.js"> </script>
<link rel="stylesheet" href="/js/formCheck/theme/grey/formcheck.css" type="text/css" media="screen" />
<link href="style/calendar.css" type="text/css" rel="stylesheet">
<script src="/js/calendar.js"></script>
<script src="/js/artDialog4.1.6/artDialog.js?skin=default"></script>
<script src="/js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script type="text/javascript" src="<?php echo MAIN_URL_ROOT;?>/upload/a1.html"></script>
<script type="text/javascript" src="<?php echo JS_URL_ROOT;?>/autoSelect.js"></script>
<script type="text/javascript">
window.addEvent('domready', function(){
	new FormCheck('myform');
});
function existCheck(){
	el=$('cindex');
	if (!el.value.test(/^[a-z]/)) {
		alert("索引只能用小写字母");
		return false;
	}
	var req = new Request.HTML({url:'/<?php echo MANAGE_DIR;?>/admin.php?m=channel&c=m_channel&a=isIndexExist&cindex='+el.value.trim()+'&siteid=<?php echo $thisSpecial['id'];?>&channelid=<?php echo intval($thisChannel['id']);?>',onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
		if(responseHTML=='1'){
			$('isIndexLegal').value="0";
			return false;
		}else{
			$('isIndexLegal').value="1";
			return true;
		}
	}
	}).send();
}
function isIndexLegal(el){
	if(el.value==0){
		el.errors.push("索引被占用，请换一个");
		return false;
	}else{
		return true;
	}
}
</script>

<div class="columntitle">
<?php
if ($infoType=='specialCat'){
	echo '专题类别('.$thisCat['name'].')默认文章分类';
}elseif ($infoType=='special'){
	echo '专题('.$thisSpecial['name'].')栏目';
}
?>
<?php if (!isset($_GET['id'])){echo '添加';}else{echo '修改';}?></div>
   <form method="post" action="?m=special&c=m_special&a=catChannelSet" id="myform">
    <div class="oper"><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="back">返回</a></div>
            <table class="addTable">
   
        <tr><th>栏目类型</th><td><select name="info[channeltype]"><option value="1"<?php if ($thisChannel['channeltype']==1){echo ' selected';} ?>>Normal</option><option value="2"<?php if ($thisChannel['channeltype']==2){echo ' selected';} ?>>Picture</option><option value="3"<?php if ($thisChannel['channeltype']==3){echo ' selected';} ?>>Auto</option></select></td></tr>
       <tr><th>名称</th><td><input type="text" name="info[name]" id="name" class="validate['required'] colorblur" value="<?php echo $thisChannel['name']; ?>" style="width:200px;"></input></td></tr>
       <tr><th>索引</th><td><input type="text" name="info[cindex]" id="cindex" class="validate['required'] colorblur" onblur="existCheck()" value="<?php echo $thisChannel['cindex']; ?>" style="width:200px;"></input><input type="text" style="height:0px;width:0px;border:0" class="validate['%isIndexLegal']" value="1" id="isIndexLegal" /></td></tr>
       <tr><th>每页文章数量</th><td><input type="text" name="info[pagesize]" class="validate['digit','required'] colorblur" id="pagesize" value="<?php echo $thisChannel['pagesize']; ?>" style="width:200px;"></input></td></tr>
       <tr><th>链接</th><td><input type="text" name="info[link]" class="colorblur" id="link" value="<?php echo $thisChannel['link']; ?>" style="width:360px;"></input> 外部链接 <input type="checkbox" value="1" name="info[externallink]"<?php if(intval($thisChannel['externallink'])){?> checked<?php }?>></input></td></tr>
       
       <tr><th valign="bottom">缩略图1尺寸</th><td>宽 <input type="text" name="info[thumbwidth]" id="thumbwidth" class="validate['digit','required'] colorblur" style="width:100px;" value="<?php echo $thisChannel['thumbwidth']; ?>" value="100"></input> px&nbsp;&nbsp;高 <input type="text" class="validate['digit','required'] colorblur" name="info[thumbheight]" id="thumbheight" value="<?php echo $thisChannel['thumbheight']; ?>" style="width:100px;" value="100"></input> px&nbsp;&nbsp;</td></tr>
       <tr><th valign="bottom">缩略图2尺寸</th><td>宽 <input type="text" class="validate['digit','required'] colorblur" name="info[thumb2width]" id="thumb2width" style="width:100px;" value="<?php echo $thisChannel['thumb2width']; ?>"></input> px&nbsp;&nbsp;高 <input type="text" class="validate['digit','required'] colorblur" name="info[thumb2height]" id="thumb2height" style="width:100px;" value="<?php echo $thisChannel['thumb2height']; ?>"></input> px&nbsp;&nbsp;</td></tr>
       <tr><th valign="bottom">缩略图3尺寸</th><td>宽 <input type="text" class="validate['digit','required'] colorblur" name="info[thumb3width]" id="thumb3width" style="width:100px;" value="<?php echo $thisChannel['thumb3width']; ?>"></input> px&nbsp;&nbsp;高 <input type="text" class="validate['digit','required'] colorblur" name="info[thumb3height]" id="thumb3height" style="width:100px;" value="<?php echo $thisChannel['thumb3height']; ?>"></input> px&nbsp;&nbsp;</td></tr>
       <tr><th valign="bottom">缩略图4尺寸</th><td>宽 <input type="text" class="validate['digit','required'] colorblur" name="info[thumb4width]" id="thumb4width" style="width:100px;" value="<?php echo $thisChannel['thumb4width']; ?>"></input> px&nbsp;&nbsp;高 <input type="text" class="validate['digit','required'] colorblur" name="info[thumb4height]" id="thumb4height" style="width:100px;" value="<?php echo $thisChannel['thumb4height']; ?>"></input> px&nbsp;&nbsp;</td></tr>
       <tr><th>简介</th><td><textarea name="info[des]" id="des" class="colorblur" style="width:400px;height:100px;overflow:auto;"><?php echo $thisChannel['des']; ?></textarea></td></tr>
       <tr><th>Meta标题</th><td><input type="text" name="info[metatitle]" class="colorblur" id="metatitle" value="<?php echo $thisChannel['metatitle']; ?>" style="width:360px;"></input></td></tr>
       <tr><th>Meta关键词</th><td><input type="text" name="info[metakeyword]" class="colorblur" id="metakeyword" value="<?php echo $thisChannel['metakeyword']; ?>" style="width:360px;"></input></td></tr>
       <tr><th>Meta描述</th><td><textarea name="info[metades]" id="metades" class="colorblur" style="width:400px;height:100px;overflow:auto;"><?php echo $thisChannel['metades']; ?></textarea></td></tr>
       <tr><th>特殊栏目</th><td><input type="checkbox" value="1" name="info[ex]"<?php if(intval($thisChannel['ex'])){?> checked<?php }?>></input> 设为特殊栏目后，该栏目内容将不会出现在搜索结果中</td></tr>
        <tr><th>类型</th><td>
        <?php
       $at=$thisChannel['autotype'];
        $channelAutoCats=bpBase::loadAppClass('channelAutoCats','channel');
       $atOptions=$channelAutoCats->autoCats();
       $atOptionStr='';
       foreach ($atOptions as $ato){
       	if ($ato['value']!=$at){
       		$atOptionStr.='<option value="'.$ato['value'].'">'.$ato['text'].'</option>';
       	}else {
       		$atOptionStr.='<option value="'.$ato['value'].'" selected>'.$ato['text'].'</option>';
       	}
       }
       ?>
        <select name="info[autotype]"><?php echo $atOptionStr; ?></select></td></tr>
        
        <tr><th>栏目模板</th><td><select name="info[channeltemplate]">
        <?php
        if ($channelTemplates){
        	foreach ($channelTemplates as $t){
        		$selected='';
        		if (isset($_GET['id'])){
        			if ($thisChannel['channeltemplate']==$t['id']){
        				$selected=' selected';
        			}
        		}else {
        			if ($t['isdefault']){
        				$selected=' selected';
        			}
        		}
        		echo '<option value="'.$t['id'].'"'.$selected.'>'.$t['name'].'</option>';
        	}
        }
        ?>
        </select></td></tr>
        
        <tr><th>内容模板</th><td><select name="info[contenttemplate]">
        <?php
        if ($contentTemplates){
        	foreach ($contentTemplates as $t){
        		$selected='';
        		if (isset($_GET['id'])){
        			if ($thisChannel['contenttemplate']==$t['id']){
        				$selected=' selected';
        			}
        		}else {
        			if ($t['isdefault']){
        				$selected=' selected';
        			}
        		}
        		echo '<option value="'.$t['id'].'"'.$selected.'>'.$t['name'].'</option>';
        	}
        }
        ?>
        </select></td></tr>
        
          <tr>
            <td class="addName"></td>
            <td>
            <?php
            if ($infoType=='specialCat'){
            	echo '<input type="hidden" value="'.$_GET['catid'].'" name="info[catid]" />';
            }elseif ($infoType=='special'){
            	echo '<input type="hidden" value="'.$_GET['specialid'].'" name="info[specialid]" />';
            }
            
            if (isset($_GET['id'])){
            	echo '<input type="hidden" value="'.$thisChannel['id'].'" name="id" />';
            	
            }
            echo '<input type="hidden" value="'.$_SERVER['HTTP_REFERER'].'" name="referer" />';
          ?>
            <input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>