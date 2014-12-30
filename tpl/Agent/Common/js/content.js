moopha.initThumbUpload=function(){
	$$('.moophaThumbUpload').addEvent('click',function(e){
		var actionUrl='action.php?type=thumbUpload';
		if($('cmsDir')){
			var actionUrl='/'+$('cmsDir').value+'/action.php?type=thumbUpload';
		}
		showDialog('上传缩略图','<form enctype="multipart/form-data" action="'+actionUrl+'" id="thumbForm" method="POST"><div id="thumbtip" style="color:#f00;"></div><div style="padding:3px 0;">大小:宽 <input type="text" name="thumbwidth" id="thumbwidth" style="width:100px;" value="'+$('thumbwidth').value+'" class="colorblur" onfocus="this.className=\'colorfocus\';" onblur="this.className=\'colorblur\';"></input> px&nbsp;&nbsp;高 <input type="text" name="thumbheight" id="thumbheight" style="width:100px;" value="'+$('thumbheight').value+'" class="colorblur" onfocus="this.className=\'colorfocus\';" onblur="this.className=\'colorblur\';"></input> px</div><div style="padding:3px 0;">选择:<input type="file" style="width:80%;" name="thumb"></input></div><div style="padding:3px 0;text-align:center;"><input type="hidden" name="sitedir" value="'+$('sitedir').value+'"></input><input type="hidden" value="'+$('channelid').value+'" name="channelid" /><input id="submitbtn" type="submit" class="button" value="上传"></input></div></form>');
		window.addEvent('domready',function(){
			$('thumbForm').addEvent('submit',function(e){
				$('submitbtn').setStyle('display','none');
				var loading=new Element('span',{'id':'loading'}).inject($('submitbtn'),'before');
				$('loading').set('html','<img src="image/loading.gif"/>');
				this.upload().addEvent('onFinish',function(){
					$('loading').dispose();
					$('submitbtn').setStyle('display','inline');
					if(this.responseText.contains("upload")){
						if($('tip')){
							$('tip').dispose();
						}
						$('thumbForm').set('html','<img src="image/success.png" align="absmiddle"> 上传成功');
						setOverlay();
						$('thumb').value=this.responseText;
						$('thumbSpan').set('html','<img src="'+this.responseText+'" />');
						$('dialog','overlay').fade();
						(function(){closeDialog();}).delay(1000);
					}else{
						$('thumbtip').set('html',this.responseText);
					}
				});
				return false;
			});
		});
	});
}
var attachementUpload=function(attributeid,attributeName,actionUrl){
	showDialog('附件上传','<form enctype="multipart/form-data" action="'+actionUrl+'" id="attachementForm" method="POST"><div id="tip" class="inputError"></div><div style="padding:3px 0;">请选择本地文件:<input type="file" style="width:80%;" name="attachement"></input></div><div style="padding:3px 0;text-align:center;"><input type="hidden" name="attributeid" value="'+attributeid+'" /><input id="submitbtn" type="submit" class="button" value="上传"></input></div></form>');
	window.addEvent('domready',function(){
		$('attachementForm').addEvent('submit',function(e){
			$('submitbtn').setStyle('display','none');
			var loading=new Element('span',{'id':'loading'}).inject($('submitbtn'),'before');
			$('loading').set('html','<img src="images/loading.gif"/>');
			this.upload().addEvent('onFinish',function(){
				$('loading').dispose();
				$('submitbtn').setStyle('display','inline');
				if(this.responseText.contains("upload")){
					if($('tip')){
						$('tip').dispose();
					}
					$('attachementForm').set('html','<img src="images/success.png" align="absmiddle"> '+lang('lang_thumbFields','success'));
					setOverlay();
					$(attributeName).value=this.responseText;
					var spanid=attributeName+'FileUrl';
					$(spanid).set('html','<a href="'+this.responseText+'" target="_blank">'+this.responseText+'</a>');
					$('dialog','overlay').fade();
					(function(){closeDialog();}).delay(1000);
				}else{
					$('tip').setStyle('display','block');
					$('tip').set('html',this.responseText);
				}
			});
			return false;
		});
	});
}
moopha.initThumbSelect=function(){
	$$('.moophaThumbSelect').addEvent('click',function(){
		//var path=this.getProperty
		showDialog(null);
		openThumbPath('');
	})
}
var openThumbPath=function(path){
	var jr = new Request.JSON({url: 'json.php?type=dir&path='+path, urlEncoded:true, async: false, onComplete: function(j){
		var files=j.files;
		var str='';
		files.each(function(file){
			if(file.isDir){
				if(path.length){
					url=path+'/'+file.name;
				}else{
					url=file.name;
				}
				str+='<div style="text-align:center;width:50px;padding:0 9px 6px 9px;float:left;"><img src="image/large_directory.gif" style="cursor:pointer;" onclick="openThumbPath(\''+url+'\')"></img><br>'+file.name+'</div>';
			}else{
				if($('path')){
					var imgUrl='/upload/'+path+'/'+file.name;
				}else{
					var imgUrl='/upload/'+file.name;
				}
				str+='<div style="text-align:center;width:50px;height:50px;padding:8px 9px 6px 12px;float:left;"><img onclick="setThumbSelected(this)" style="width:50px;height:50px;cursor:pointer;" src="'+imgUrl+'"></img></div>';
			}
		});
		var dirArr=path.split('/');
		var upPath='';
		dirArrCount=dirArr.length;
		for(i=0;i<dirArrCount-1;i++){
			upPath+=dirArr[i]+'/';
		}
		upPath=upPath.substr(0,upPath.length-1);
		$('dialog').set('html',dialogCloseStr(lang('lang_thumbFields','choose'))+'<div style="padding:0 10px;margin:0;"><table><tr><td valign="middle"><img onclick="openThumbPath(\''+upPath+'\')" src="image/up.gif" style="cursor:pointer;"></img></td><td valign="middle"> '+lang('lang_thumbFields','current_floder')+':<span id="path">/upload</span></td></tr></table><div class="colorblur" style="padding:5px;overflow:auto;height:400px;">'+str+'</div></div>');
		if($('path')){
			$('path').set('html','/upload/'+path);
		}
		setOverlay();
	}}).get();
}
var setThumbSelected=function(el){
	var thumbUrl=el.src;
	thumbUrl=thumbUrl.substr(thumbUrl.indexOf('/upload'));
	$('thumb').value=thumbUrl;
	$('thumbSpan').set('html','<img src="'+thumbUrl+'" />');
	closeDialog();
}
moopha.initContentAdd=function(){
	$('form').addEvent('submit',function(e){
		return checkArticleInput();	
	});
}
var checkArticleInput=function(){
	var title=$('title').value.trim();
	if($('subtitle')){
		var subtitle=$('subtitle').value.trim();
	}else{
		var subtitle='';
	}
	if($('link')){
		var link=$('link').value.trim();
	}else{
		var link='';
	}
	var thumb=$('thumb').value.trim();
	var author=$('author').value.trim();
	var source=$('source').value.trim();
	var datePattern=/^[1|2][0-9]{3}-[0-1][0-9]-[0-9]{2}$/;
	var timePattern=/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/;
	
	if(subtitle.length>200){
		showErrorsTip($('form'),lang('lang_contentTips','subtitle_error'));
		return false;
	}else if(link.length>300){
		showErrorsTip($('form'),lang('lang_contentTips','link_error'));
		return false;
	}else if(thumb.length>100){
		showErrorsTip($('form'),lang('lang_contentTips','thumb_error'));
		return false;
	}else if(author.length>50){
		showErrorsTip($('form'),lang('lang_contentTips','author_error'));
		return false;
	}else if(source.length>100){
		showErrorsTip($('form'),lang('lang_contentTips','source_error'));
		return false;
	}else if(!datePattern.exec($('adddate').value.trim())||!timePattern.exec($('addtime').value.trim())){
		showErrorsTip($('form'),lang('lang_contentTips','addtime_error'));
		return false;
	}else{
		return true;
	}
}
var contentDelete=function(id){
	var elID='tr'+id;
	var url='action.php?type=contentDelete&id='+id;
	ajaxNoConfirmDelete($(elID),url);
}
var contentsDelete=function(ids){
	var url='action.php?type=contentsDelete&ids='+ids;

	var req = new Request.HTML({url:url,
	onSuccess: function(html) {
		if(!this.response.text||!this.response.text.length){
			var idArr=ids.split(',');
			idArr.each(function(id){
				var elID='tr'+id;
				var el=$(elID);
				//setTimeout(function(){el.dispose();}, 500);
				if(el){
					el.dispose();
				}
			});
		}else{
			window.location.href=this.response.text;
		}
	},
	onFailure: function() {
	}
	});
	req.send();

	//window.location.href=url;
}
var contentViewTimeAdd=function(id){
	var req = new Request.HTML({url:'/script/php/viewTimeAdd.php?id='+id,
	onSuccess: function(html) {
	},
	onFailure: function() {
	}
	});
	req.send();
}
var externalLinkChecked=function(){
	window.addEvent('domready',function(){
		if($('externallink0')&&$('trlink')){
			$('externallink0').addEvent('click',function(){
				if(this.checked){
					var inputTip=new Element('span',{id:'tips',html:'请在“链接”输入框中输入外部链接地址',style:'color:red'}).inject($('externallink0label'),'after');
					$('trlink').removeProperty('style');
				}else{
					if($('tips')){
						$('tips').dispose();
					}
					$('trlink').setStyle('display','none');
				}
			});
		}
	});
}

var checkValueWithRegularExpression=function(value, AlertId, RegularExpression, AlertMessage) {
    if (!RegularExpression.test(value)) {
    	$(AlertId).setProperty('class','errorInput');
        $(AlertId).set('html','<img src="images/cancel.gif" align="absmiddle" />&nbsp;'+AlertMessage);
        return false;
    }else {
    	$(AlertId).set('html','<img src="images/tick.png" align="absmiddle" />');
        $(AlertId).setProperty('class','');
        return true;
    }
}

var valueUniqueValid=function(tableid,colname,value,AlertId,AlertMessage){
	if(value.length>0){
		if (isAttributeValueExist(tableid,colname,value)) {
			$(AlertId).setProperty('class','errorInput');
			$(AlertId).set('html','<img src="images/cancel.gif" align="absmiddle" />&nbsp;已存在');
			return false;
		}else {
			$(AlertId).set('html','<img src="images/tick.png" align="absmiddle" />');
			$(AlertId).setProperty('class','');
			return true;
		}
	}else{
		$(AlertId).setProperty('class','errorInput');
		$(AlertId).set('html','<img src="images/cancel.gif" align="absmiddle" />&nbsp;请输入');
		return false;
	}
}
var isAttributeValueExist=function(tableid,colname,value){
	var url='/script/php/json.php?type=isAttributeValueExist&tableid='+tableid+'&colname='+colname+'&value='+value;
	var req = new Request.HTML({url:url,async:false,onComplete: function(responseTree, responseElements, responseHTML, responseJavaScript) {
		if(responseHTML.toInt()>0){
			flag=1;
		}else{
			flag=0;
		}
	}
	}).send();
	return flag;
}


/******************内容分页**************/
function addContentInput(){
	//每个tab样式设为普通
	var contentTabs=$$('.contentTab');
	contentTabs.each(function(contentTab){
		contentTab.setProperty('class','contentTab');
	})
	//每个删除页面的button设为显示
	var contentTabDeleteButtons=$$('.tabdelete');
	contentTabDeleteButtons.each(function(ctdb){
		ctdb.setStyle('display','block');
	})
	var newIndex=contentTabs[contentTabs.length-1].getProperty('id').toString().replace('contentTab','').toInt()+1;
	//添加新的tab
	var tab=new Element('li',{'class':'active contentTab','id':'contentTab'+newIndex+'','html':'<a href="###" onclick="showContent('+newIndex+')">第<i>'+newIndex+'</i>页</a><span style="" rel="'+newIndex+'" id="tabDelete'+newIndex+'" class="tabdelete" onclick="deleteContentInput('+newIndex+')" onmouseover="this.className=\'tabdelete_hover\'" onmouseout="this.className=\'tabdelete\'"></span>'}).inject($('tabAdd'),'before');
	//隐藏所有editor
	var editorAreas=$$('.content_a');
	editorAreas.each(function(ea){
		ea.setStyle('display','none');
	})
	//添加新的editor
	var newEditorArea=new Element('div',{'id':'content_a'+newIndex,'class':'content_a','html':'<div id="pContent_a'+newIndex+'">分页标题 <input type="text" style="width:300px" name="pageTitle[]" class="colorblur" />&nbsp;&nbsp;顺序 <input style="width:30px;" type="text" name="order[]" class="colorblur" value="'+newIndex+'" /></div><textarea name="content[]" id="content'+newIndex+'">&nbsp;</textarea>'}).inject($('contentArea'),'bottom');
	var editor = CKEDITOR.replace("content"+newIndex,{width:"96%"});CKFinder.setupCKEditor(editor,"/editor/ckfinder/") ;
	
}
function deleteContentInput(i){
	var contentTabs=$$('.contentTab');
	if(contentTabs.length<2){
		alert('至少得保留一页内容');
		return false;
	}
	var rt=confirm('确定删除该页内容吗？');
	if(rt){
		var deleteTabItem=$('contentTab'+i);
		var deleteContentItem=$('content_a'+i);
		//判断被删除的目前是不是active状态
		var classStatus=deleteTabItem.getProperty('class').toString();
		deleteTabItem.dispose();
		deleteContentItem.dispose();
		if(classStatus=='contentTab'){//非active状态
		}else{//active状态，设置最后一页为active状态
			var editorAreas=$$('.content_a');
			var id=0;
			editorAreas.each(function(ea){
				id=ea.getProperty('id').toString().replace('content_a','').toInt();
			})
			//
			$('contentTab'+id).setProperty('class','active contentTab');
			$('content_a'+id).setStyle('display','');
			
		}
		//重新编页码
		/*
		var is=$('contentTabs').getElementsByTagName('i');
		is.each(function(isItem){
			isItem.set('txt',isItem.get('txt').toInt()+1);
		})
		*/
	}
}
//tab切换
function showContent(i){
	//其他tab
	var contentTabs=$$('.contentTab');
	contentTabs.each(function(ct){
		ct.setProperty('class','contentTab');
	})
	$('contentTab'+i).setProperty('class','active contentTab');
	//隐藏其他editor
	var editorAreas=$$('.content_a');
	editorAreas.each(function(ea){
		var id=ea.getProperty('id').toString().replace('content_a','').toInt();
		if(id!=i){
			ea.setStyle('display','none');
		}
	})
	$('content_a'+i).setStyle('display','');
}