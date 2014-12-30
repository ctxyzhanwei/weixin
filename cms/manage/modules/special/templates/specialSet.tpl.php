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
<style>
#autos{padding:5px 10px;display:none;border:1px solid #ddd;background:#f3f3f3}
#autos p{font-weight:bold;line-height:26px;padding:0;margin:0;}
#autos label{float:left;width:180px;height:23px;overflow:hidden; margin-right:20px;}
</style>
<script type="text/javascript">
window.addEvent('domready', function(){
	new FormCheck('myform');
});
function existCheck(){
	el=$('specialindex');
	var req = new Request.HTML({url:'/<?php echo MANAGE_DIR;?>/admin.php?m=special&c=m_special&a=isIndexExist&index='+el.value.trim()+'&id=<?php echo intval($thisRow['id']);?>',onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
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
function serieidChange(){
	var serieid=$('serieid').value;
	if(serieid){
		var g4_list = g4autos[serieid].split(',');
		var str='';
		for (loop = 0; loop < g4_list.length; loop += 2) {
			str+='<label><input class="autoCB" name="info[autoids][]" type="checkbox" value="'+g4_list[loop]+'"<?php if ($config['childAutoSelectType']){echo '';}else {echo ' checked';} ?> /> '+g4_list[loop + 1]+'</label>';
		}
		//
		$('autos').setStyle('display','block');
		var selectTitle="<?php if ($config['childAutoSelectType']){echo '请排除掉不团购的车型';}else {echo '请选择团购的车型';} ?>";
		$('autos').set('html','<p>'+selectTitle+'： <a href="javascript:selectOp()">点击反选</a></p>'+str+'<div style="clear:both"></div>');
	}else{
		$('autos').setStyle('display','none');
		$('autos').set('html','');
	}
}
function selectOp(){
	var checkboxs=$$('.autoCB');
	checkboxs.each(function(acb){
		if(acb.checked){
			acb.checked=false;
		}else{
			acb.checked=true;
		}
	})
}
function selectStore(){
	art.dialog.open('?m=store&c=widget_store&a=editor_insertStore&referer=special',{lock:false,title:'选择经销商',width:600,height:300,yesText:'关闭',background: '#000',opacity: 0.87});
}
function deleteStore(id){
	var storeids=$('storeids').value.split(',');
	var str='';
	var comma='';
	storeids.each(function(sid){
		if(sid!=id){
			str+=comma+sid;
			comma=',';
		}
	})
	$('storeids').value=str;
	$('store'+id).dispose();
}
function catChange(){
	var catid=$('catid').value;
	if(catid){
		var optionDomID='catOption'+catid;
		if($(optionDomID)){
			var catIndex=$(optionDomID).getProperty('rel').toString();
			var optionTrs=$$('.optionTr');
			optionTrs.each(function(tr){
				var rel=tr.getProperty('rel').toString();
				if(rel.contains(catIndex)){
					tr.setStyle('display','');
				}else{
					tr.setStyle('display','none');
				}
			})
		}
	}
}
</script>
<div class="columntitle">专题<?php if (!isset($_GET['id'])){echo '添加';}else{echo '修改';}?></div>
   <form method="post" action="?m=special&c=m_special&a=specialSet" id="myform">
            <table class="addTable">
        <tr>
          <th>类别</th>
          <td><select name="info[catid]" class="validate['required']" onchange="catChange()" id="catid" value="<?php echo $thisRow['name'];?>"><option value="0">请选择</option><?php if ($cats){foreach ($cats as $cat){$selected='';if ($cat['id']==$thisRow['catid']){$selected=' selected';}echo '<option id="catOption'.$cat['id'].'" rel="'.$cat['enname'].'" value="'.$cat['id'].'"'.$selected.'>'.$cat['name'].'</option>';}}?></select> </td>
        </tr>
        <tr>
          <th>标题</th>
          <td><input type="text"  name="info[name]" size="60" class="validate['required'] colorblur" value="<?php echo $thisRow['name'];?>"> </td>
        </tr>
        <tr><th>索引</th><td><input type="text" name="info[specialindex]" id="specialindex" class="validate['required','alphanum'] colorblur" onblur="existCheck()" value="<?php echo $thisRow['specialindex']; ?>" style="width:200px;"></input><input type="text" style="height:0px;width:0px;border:0" class="validate['%isIndexLegal']" value="1" id="isIndexLegal" /></td></tr>
        <!--相关车系车型 start-->
        <tr class="optionTr" rel="xinche" style="<?php if ($thisCat['enname']!='xinche'){echo 'display:none';}?>">
          <th valign="top" width="80">选择相关车系</th>
          <td valign="top">
          <!--auto selects start-->
          <input type="hidden" id="brandid_text" value="<?php echo $thisRow['brandid'];?>" />
          <input type="hidden" id="serieid_text" value="<?php echo $thisRow['serieid'];?>" />
          <select name="info[brandid]" id="brandid" onchange="g3selectLoad('brandid','serieid')"></select> <select name="info[serieid]" id="serieid" onchange="serieidChange()"></select>
          <script>brandSelectLoad('brandid','serieid');</script>
          <!--auto selects end-->
          
          <!--autos selects start-->
          <div id="autos"<?php if (isset($_GET['id'])){echo ' style="display:block"';}else {echo ' style="display:none"';}?>>
          <div style="font-weight:bold">请选择车型 <a href="javascript:selectOp()">点击反选</a></div>
          <?php
          if (isset($_GET['id'])){
          	if ($childAutos){
          		foreach ($childAutos as $a){
          			$checked='';
          			if (in_array($a['id'],$autoids)){
          				$checked=' checked';
          			}
          			echo '<label><input class="autoCB" name="info[autoids][]" type="checkbox" value="'.$a['id'].'"'.$checked.' /> '.$a['name'].'</label>';
          		}
          	}
          	echo '<div style="clear:both"></div>';
          }
          ?>
          </div>
          <!--autos selects end-->
          
           <span class="tdtip">适用于新车类专题</span>
          </td>
        </tr>
        <!--相关车系车型 end-->
        <!--竞争车系 start-->
        <tr class="optionTr" rel="xinche" style="<?php if ($thisCat['enname']!='xinche'){echo 'display:none';}?>">
          <th>竞争车系</th>
          <td><input type="hidden" name="info[competeautoids]" id="autoid" value="<?php echo $thisRow['competeautoids'];?>" /> <select name="g1cfs" id="g1cfs" onchange="g3selectLoad()"></select> <select name="g3cfs" id="g3cfs"></select><script>brandSelectLoad('g1cfs','g3cfs');</script> <input type="button" value="添加" onclick="addArticleRelateAuto()" /> <span class="tdtip">适用于新车类专题</span><div id="autoSpan">
    <?php
    if ($competeautos){
    	foreach ($competeautos as $a){
    		echo '<span id="as'.$a['id'].'">'.$a['name'].'&nbsp;<img src="image/cross.png" onclick="deleteRelateAutoCf('.$a['id'].')" style="cursor:pointer;" align="absmiddle">&nbsp;&nbsp;&nbsp;</span>';
    	}
    }
    ?>
    </div> <input type="hidden" id="keywords" /></td>
        </tr>
        <!--竞争车系 end-->
        <!--相关经销商 start-->
        <tr class="optionTr" rel="xinche,chezhan" style="<?php if (!in_array($thisCat['enname'],array('xinche','chezhan'))){echo 'display:none';}?>">
          <th>相关经销商</th>
          <td><span id="stores"><?php
    if ($stores){
    	foreach ($stores as $s){
    		echo '<span id="store'.$s['id'].'"><a href="'.$s['url'].'" target="_blank">'.$s['shortname'].'</a> <a href="###" style="color:#999" onclick="deleteStore('.$s['id'].')">删除</a>&nbsp; &nbsp;</span>';
    	}
    }
    ?></span><input type="hidden" id="storeids" name="info[storeids]" value="<?php echo $thisRow['storeids'];?>" /> <a href="###" onclick="selectStore()">选择</a></td>
        </tr>
        <!--相关经销商 end-->
        <!--车展属性 start-->
        <tr class="optionTr" rel="chezhan" style="<?php if ($thisCat['enname']!='chezhan'){echo 'display:none';}?>">
          <th>展览时间</th>
          <td><input type="text"  name="ext[zhanlanshijian]" size="60" class="colorblur" value="<?php echo $thisRow['zhanlanshijian'];?>"> </td>
        </tr>
        <tr class="optionTr" rel="chezhan" style="<?php if ($thisCat['enname']!='chezhan'){echo 'display:none';}?>">
          <th>开幕式</th>
          <td><input type="text"  name="ext[kaimushi]" size="60" class="colorblur" value="<?php echo $thisRow['kaimushi'];?>"> </td>
        </tr>
        <tr class="optionTr" rel="chezhan" style="<?php if ($thisCat['enname']!='chezhan'){echo 'display:none';}?>">
          <th>票价</th>
          <td><input type="text"  name="ext[piaojia]" size="60" class="colorblur" value="<?php echo $thisRow['piaojia'];?>"> </td>
        </tr>
        <tr class="optionTr" rel="chezhan" style="<?php if ($thisCat['enname']!='chezhan'){echo 'display:none';}?>">
          <th>地点</th>
          <td><input type="text"  name="ext[didian]" size="60" class="colorblur" value="<?php echo $thisRow['didian'];?>"> </td>
        </tr>
        <tr class="optionTr" rel="chezhan" style="<?php if ($thisCat['enname']!='chezhan'){echo 'display:none';}?>">
          <th>经纬度</th>
          <td>经度 <input id="longitude" name="ext[longitude]" size="14" class="colorblur" value="<?php echo $thisRow['longitude'];?>" type="text"> 纬度 <input name="ext[latitude]" size="14" id="latitude" class="colorblur" value="<?php echo $thisRow['latitude'];?>" type="text"> <a href="###" onclick="setlatlng($('longitude').value,$('latitude').value,'<?php echo MANAGE_DIR;?>')">在地图中查看/设置</a></td>
        </tr>
        <!--车展属性 end-->
        <tr>
          <th>meta Title</th>
          <td><input type="text"  name="info[metatitle]" size="60" class="colorblur" value="<?php echo $thisRow['metatitle'];?>"> </td>
        </tr>
        <tr>
          <th>meta Keywords</th>
          <td><input type="text"  name="info[metakeywords]" size="60" class="colorblur" value="<?php echo $thisRow['metakeywords'];?>"> </td>
        </tr>
        <tr>
          <th>meta Description</th>
          <td><textarea name="info[metadescription]" class="colorblur" style="font-size:12px;height:60px;width:80%"><?php echo $thisRow['metadescription'];?></textarea> </td>
        </tr>
        <tr style="display:none">
          <th>导读</th>
          <td><textarea name="info[daodu]" class="colorblur" style="height:60px;font-size:12px;width:80%"><?php echo $thisRow['daodu'];?></textarea> </td>
        </tr>
        <tr>
          <th>横幅</th>
          <td><input type="text" id="banner" name="info[banner]" size="30" class="colorblur" value="<?php echo $thisRow['banner'];?>" /> <a href="###" onclick="picUpload('banner','0','0','','<?php echo MANAGE_DIR;?>')">上传</a> <a href="###" onclick="viewImg('banner','横幅预览')">预览</a></td>
        </tr>
        <?php
        if (!intval($_GET['modelid'])){
        ?>
        
        <tr><th>模板</th><td><select name="info[templateid]">
        <option value="0">请选择模板</option>
        <?php
        if ($templates){
        	foreach ($templates as $t){
        		$selected='';
        		if ($thisRow['templateid']==$t['id']){
        			$selected=' selected';
        		}
        		echo '<option value="'.$t['id'].'"'.$selected.'>'.$t['name'].'</option>';
        	}
        }
        ?>
        </select></td></tr>
        <?php
        }
        ?>
        <tr>
          <th>首页地址构造</th>
          <td><input type="text" name="info[urlformat]" style="width:40%" class="colorblur" value="<?php echo $thisRow['urlformat'];?>">  <span class="tdtip">{domainName}网站域名 {folder}专题存放文件夹名称 {catIndex}类别索引 {specialIndex}专题索引</span></td>
        </tr>
          <tr>
            <td class="addName"></td>
            <td>
            <?php
            if (isset($_GET['id'])){
            	echo '<input type="hidden" value="'.$thisRow['id'].'" name="id" />';
            }
            echo '<input type="hidden" value="'.$_SERVER['HTTP_REFERER'].'" name="referer" />';
            echo '<input type="hidden" value="'.intval($_GET['modelid']).'" name="modelid" />';
          ?>
            <input type="submit" name="doSubmit" value="提交" class="button"/></td>
          </tr>
         
        </table>
        
</form>
<?php include($this->showManageTpl('footer','manage'));?>