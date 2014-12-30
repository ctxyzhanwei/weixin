var form_pics = (function(){
	var fp = function(){
		this.length = 1;
		this.videoLength = 0;
	}

	fp.prototype = {
		addImg: function(thi){
			if(thi.files && thi.files[0]){
				var img = thi.nextSibling;
				
				if(thi.files[0].type.indexOf("video")==-1 ){
					var URL = window.URL||webkitURL;
					var url = URL.createObjectURL(thi.files[0]);
					img.src = url;
					thi.parentNode.setAttribute("type","image");
				}else{
					if(this.videoLength>=1){
						alert("只能上传一个视频", 1000);
						return this;
					}
					img.src = 'imgs/3.png';
					this.videoLength +=1;
					thi.parentNode.setAttribute("type","video");
				}
				
				this.createImgFile(thi);
				this.length ++;
				thi.setAttribute("style","display:none;");
				return this;
			}
		},
		removeImg: function(thi){
			var type = $(thi).closest("dd").remove().attr("type");
			if("video" == type ){
				this.videoLength -=1;
			}
			this.createImgFile(thi);
			this.length --;
			return this;
		},
		createImgFile: function(thi){
			if(this.length>8){
				this.length = 8;
				return this;
			}
			var TPL = '<dd><input type="file" accept="image/jpg, image/jpeg, image/png, video/*"  onchange="form_pics.addImg(this);" name="pics[]" /><img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" />\
							<span onclick="form_pics.removeImg(this);">&nbsp;</span></dd><dt>\
								<label>最多可上传8张图片</label>\
							</dt>';
			$(thi).closest("dl").append($(TPL) );
			return this;
		}
	}

	return new fp();
})();