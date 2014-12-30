var setValueOpposite=function(imgObject,actionUrl){
	var oSrc=imgObject.src.toString();
	var oTitle=imgObject.title.toString();
	var oRef=imgObject.getAttribute('ref').toString();
	var oRev=imgObject.getAttribute('rev').toString();
	var oTitle=imgObject.title.toString();
	var oValue=imgObject.getAttribute('ret').toInt();
	//
	imgObject.src='/images/loading.gif';
	
	var req = new Request.HTML({url:actionUrl+oValue,
	onSuccess: function(html) {
		imgObject.src=oRev;
		imgObject.alt=oRef;
		imgObject.title=oRef;
		imgObject.rev=oSrc;
		imgObject.ref=oTitle;
		imgObject.ref=oTitle;
		imgObject.ret=(1-oValue).toInt();
	},
	onFailure: function() {
		alert('发生未知错误');
	}
	});
	req.send();
}
function itemCheck(id){
	var item=$('checkItem'+id)
	if(item.checked){
		item.checked=false;
	}else{
		item.checked=true;
	}
}