function selectall(name) {
	var checkItems=document.getElements('input[name='+name+']');
	if ($("check_box").checked==false) {
		checkItems.each(function(el) {
			el.checked=false;
		});
	} else {
		checkItems.each(function(el) {
			el.checked=true;
		});
	}
}
function setlatlng(longitude,latitude,manageDir){
	art.dialog.data('longitude', longitude);
	art.dialog.data('latitude', latitude);
	// 此时 iframeA.html 页面可以使用 art.dialog.data('test') 获取到数据，如：
	// document.getElementById('aInput').value = art.dialog.data('test');
	art.dialog.open('?m=geo&c=widget&a=setLatlng',{lock:false,title:'设置经纬度',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.87});
}
function picUpload(domid,width,height,subfix){
	art.dialog.data('width', width);
	art.dialog.data('height', height);
	art.dialog.data('domid', domid);
	art.dialog.data('subfix', subfix);
	art.dialog.open('?m=manage&c=background&a=picUpload',{lock:false,title:'上传图片',width:400,height:270,yesText:'关闭',background: '#000',opacity: 0.87});
}
function upyunPicUpload(domid,width,height){
	art.dialog.data('width', width);
	art.dialog.data('height', height);
	art.dialog.data('domid', domid);
	art.dialog.open('?m=manage&c=background&a=upyunPicUpload&width='+width,{lock:false,title:'上传图片',width:400,height:200,yesText:'关闭',background: '#000',opacity: 0.87});
}
function contentPicUpload(domid,width,height,subfix,manageDir,channelid){
	art.dialog.data('width', width);
	art.dialog.data('height', height);
	art.dialog.data('domid', domid);
	art.dialog.data('subfix', subfix);
	art.dialog.data('channelid', channelid);
	art.dialog.open('?m=manage&c=background&a=picUpload',{lock:false,title:'上传图片',width:400,height:200,yesText:'关闭',background: '#000',opacity: 0.87});
}
function viewImg(domid,title){
	if($(domid).value){
		var html='<img src="'+$(domid).value+'" />';
	}else{
		var html='没有图片';
	}
	art.dialog({title:title,content:html});
}
function flashUpload(domid,subfix,manageDir){
	art.dialog.data('domid', domid);
	art.dialog.data('subfix', subfix);
	art.dialog.open('?m=manage&c=background&a=flashUpload',{lock:false,title:'上传Flash',width:400,height:200,yesText:'关闭',background: '#000',opacity: 0.87});
}
window.addEvent('domready',function(){
	if($$('.tabli')&&$$('.tabcontent')){
		$$('.tabli').each(function(tab){
			tab.addEvent('click',function(){
				var tabid=tab.id;
				tab.addClass('current');
				var tabindex=tabid.replace('tab','');
				$('tabcontent'+tabindex).setStyle('display','block');
				
				$$('.tabli').each(function(t){
					var othertabindex=t.id.replace('tab','');
					if(othertabindex!=tabindex){
						t.removeClass('current');
						$('tabcontent'+othertabindex).setStyle('display','none');
					}
				});
			})
		});
	}
})
var showElementByTab=function(id){
	var tabClass='tab';
	var contentClass='tabcontent';
	var tabid=tabClass+id;
	var contentid=contentClass+id;
	var selectedClass="current";
	$$('.'+tabClass).each(function(tab){
		tab.removeClass(selectedClass);
	});
	$(tabid).addClass(selectedClass);
	$$('.'+contentClass).each(function(c){
		c.setStyle('display','none');
	});
	//alert(contentid);
	$(contentid).setStyle('display','block');
}
function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('/index.php?g=User&m=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}