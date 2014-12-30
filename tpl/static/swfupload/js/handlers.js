function fileQueueError(file, errorCode, message){
    try {
        var imageName = window.path+"/images/error.gif";
        var errorName = "";
        if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
            errorName = "您上传的文件过多！";
        }
        
        if (errorName !== "") {
            alert(errorName);
            return;
        }
        
        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                imageName = window.path+"/images/zerobyte.gif";
                break;
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                imageName = window.path+"/images/toobig.gif";
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
            default:
                alert(message);
                break;
        }
        
        addImage(imageName);
        
    } 
    catch (ex) {
        this.debug(ex);
    }
    
}

function fileDialogComplete(numFilesSelected, numFilesQueued){
    try {
        if (numFilesQueued > 0) {
            this.startUpload();
        }
    } 
    catch (ex) {
        this.debug(ex);
    }
}

function uploadProgress(file, bytesLoaded){

    try {
        var percent = Math.ceil((bytesLoaded / file.size) * 100);
        
        var progress = new FileProgress(file, this.customSettings.upload_target);
        progress.setProgress(percent);
        if (percent === 100) {
            progress.setStatus("创建缩略图中");
            progress.toggleCancel(false, this);
        }
        else {
            progress.setStatus("上传中");
            progress.toggleCancel(true, this);
        }
    } 
    catch (ex) {
        this.debug(ex);
    }
}


function uploadSuccess(file, serverData){
	addImage(serverData);
	var $svalue=$('form>input[name=s]').val();
	if($svalue==''){
		$('form>input[name=s]').val(serverData);
	}else{
		$('form>input[name=s]').val($svalue+"|"+serverData);
	}
	
}

function uploadComplete(file){
    try {

        if (this.getStats().files_queued > 0) {
            this.startUpload();
        }
        else {
            var progress = new FileProgress(file, this.customSettings.upload_target);
            progress.setComplete();
            progress.setStatus("所有图片上传成功！");
            progress.toggleCancel(false);
        }
    } 
    catch (ex) {
        this.debug(ex);
    }
}

function uploadError(file, errorCode, message){
    var imageName = window.path+"/images/error.gif";
    var progress;
    try {
        switch (errorCode) {
            case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                try {
                    progress = new FileProgress(file, this.customSettings.upload_target);
                    progress.setCancelled();
                    progress.setStatus("取消");
                    progress.toggleCancel(false);
                } 
                catch (ex1) {
                    this.debug(ex1);
                }
                break;
            case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                try {
                    progress = new FileProgress(file, this.customSettings.upload_target);
                    progress.setCancelled();
                    progress.setStatus("停止");
                    progress.toggleCancel(true);
                } 
                catch (ex2) {
                    this.debug(ex2);
                }
            case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                imageName = window.path+"/images/uploadlimit.gif";
                break;
            default:
                alert(message);
                break;
        }
        
        addImage(imageName);
        
    } 
    catch (ex3) {
        this.debug(ex3);
    }
    
}
var addnum = 0;
function addImage(src){
	
	if(upload_type == 'upyun'){
		var obj = eval('(' + src + ')');
		src = 'http://'+upyunSite+obj.url;
	}
    var newElement = '<tr><td><input type="text" placeholder="为这张图片起个名字" name="title_'+addnum+'" value="" class="px"/></td><td><input type="text" name="sort_'+addnum+'" value="0" class="px" style="width:50px;" /></td><td><input class="px" style="width:250px;" type="text" name="picurl_'+addnum+'" id="pic_'+addnum+'" value="'+src+'" /> &nbsp; <a href="javascript:viewImg('+"'"+'pic_'+addnum+"'"+')">预览</a></td><td><input class="px" type="text" style="width:250px;" placeholder="简单介绍一下这张图片" name="info_'+addnum+'" value="" /></td><td><input class="checkbox" type="checkbox" name="status_'+addnum+'" value="1" checked /></td><td class="norightborder"><a href="javascript:;" id="delBtn" >删除</a></td></tr>';
    $("#pic_list").prepend(newElement);
    $("#delBtn").bind("click", del);
	addnum++;
    
}

var del = function(){

    $(this).parent().parent().remove();
}

function fadeIn(element, opacity){
    var reduceOpacityBy = 5;
    var rate = 30; // 15 fps
    if (opacity < 100) {
        opacity += reduceOpacityBy;
        if (opacity > 100) {
            opacity = 100;
        }
        
        if (element.filters) {
            try {
                element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
            } 
            catch (e) {
                element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
            }
        }
        else {
            element.style.opacity = opacity / 100;
        }
    }
    
    if (opacity < 100) {
        setTimeout(function(){
            fadeIn(element, opacity);
        }, rate);
    }
}

function FileProgress(file, targetID){
    this.fileProgressID = "divFileProgress";
    
    this.fileProgressWrapper = document.getElementById(this.fileProgressID);
    if (!this.fileProgressWrapper) {
        this.fileProgressWrapper = document.createElement("div");
        this.fileProgressWrapper.className = "progressWrapper";
        this.fileProgressWrapper.id = this.fileProgressID;
        
        this.fileProgressElement = document.createElement("div");
        this.fileProgressElement.className = "progressContainer";
        
        var progressCancel = document.createElement("a");
        progressCancel.className = "progressCancel";
        progressCancel.href = "#";
        progressCancel.style.visibility = "hidden";
        progressCancel.appendChild(document.createTextNode(" "));
        
        var progressText = document.createElement("div");
        progressText.className = "progressName";
        progressText.appendChild(document.createTextNode(file.name));
        
        var progressBar = document.createElement("div");
        progressBar.className = "progressBarInProgress";
        
        var progressStatus = document.createElement("div");
        progressStatus.className = "progressBarStatus";
        progressStatus.innerHTML = "&nbsp;";
        
        this.fileProgressElement.appendChild(progressCancel);
        this.fileProgressElement.appendChild(progressText);
        this.fileProgressElement.appendChild(progressStatus);
        this.fileProgressElement.appendChild(progressBar);
        
        this.fileProgressWrapper.appendChild(this.fileProgressElement);
        
        document.getElementById(targetID).appendChild(this.fileProgressWrapper);
        fadeIn(this.fileProgressWrapper, 0);
        
    }
    else {
        this.fileProgressElement = this.fileProgressWrapper.firstChild;
        this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
    }
    
    this.height = this.fileProgressWrapper.offsetHeight;
    
}

FileProgress.prototype.setProgress = function(percentage){
    this.fileProgressElement.className = "progressContainer green";
    this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
    this.fileProgressElement.childNodes[3].style.width = percentage + "%";
};
FileProgress.prototype.setComplete = function(){
    this.fileProgressElement.className = "progressContainer blue";
    this.fileProgressElement.childNodes[3].className = "progressBarComplete";
    this.fileProgressElement.childNodes[3].style.width = "";
    
};
FileProgress.prototype.setError = function(){
    this.fileProgressElement.className = "progressContainer red";
    this.fileProgressElement.childNodes[3].className = "progressBarError";
    this.fileProgressElement.childNodes[3].style.width = "";
    
};
FileProgress.prototype.setCancelled = function(){
    this.fileProgressElement.className = "progressContainer";
    this.fileProgressElement.childNodes[3].className = "progressBarError";
    this.fileProgressElement.childNodes[3].style.width = "";
    
};
FileProgress.prototype.setStatus = function(status){
    this.fileProgressElement.childNodes[2].innerHTML = status;
};


FileProgress.prototype.toggleCancel = function(show, swfuploadInstance){
    this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
    if (swfuploadInstance) {
        var fileID = this.fileProgressID;
        this.fileProgressElement.childNodes[0].onclick = function(){
            swfuploadInstance.cancelUpload(fileID);
            return false;
        };
    }
};
