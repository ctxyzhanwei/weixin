function toEditor(url,randnum){
	//editors
	var editorCount=$$('.contentTab').length;
	var thisEditorID=1;
	for(i=1;i<editorCount+1;i++){
		var tabid='contentTab'+i;
		if($(tabid).hasClass('active')){
			thisEditorID=i;
			break;
		}
	}
	var picTitle=$('picTitle_'+randnum).value;
	var picHTML = '<img src="'+url+'" alt="'+picTitle+'" title="'+picTitle+'"/>';
	var editorID='content'+thisEditorID;
	CKEDITOR.instances[editorID].insertHtml(picHTML);
}
window.addEvent('domready', function() { 
	
          	

	// our uploader instance 
	
	var up = new FancyUpload2($('demo-status'), $('demo-list'), { // options object
		// we console.log infos, remove that in production!!
		verbose: true,
		
		// url is read from the form, so you just have to change one place
		url: $('form-demo').action,
		
		// path to the SWF file
		path: '../library/fancyUpload/source/Swiff.Uploader.swf',
		
		// remove that line to select all files, or edit it, add more items
		typeFilter: {
			'Images (*.jpg, *.jpeg, *.gif, *.png)': '*.jpg; *.jpeg; *.gif; *.png'
		},
		
		// this is our browse button, *target* is overlayed with the Flash movie
		target: 'demo-browse',
		
		// graceful degradation, onLoad is only called if all went well with Flash
		onLoad: function() {
			$('demo-status').removeClass('hide'); // we show the actual UI
			$('demo-fallback').destroy(); // ... and hide the plain form
			
			// We relay the interactions with the overlayed flash to the link
			this.target.addEvents({
				click: function() {
					return false;
				},
				mouseenter: function() {
					this.addClass('hover');
				},
				mouseleave: function() {
					this.removeClass('hover');
					this.blur();
				},
				mousedown: function() {
					this.focus();
				}
			});

			// Interactions for the 2 other buttons
			
			$('demo-clear').addEvent('click', function() {
				up.remove(); // remove all files
				return false;
			});

			$('demo-upload').addEvent('click', function() {
				up.start(); // start upload
				return false;
			});
		},
		
		// Edit the following lines, it is your custom event handling
		
		/**
		 * Is called when files were not added, "files" is an array of invalid File classes.
		 * 
		 * This example creates a list of error elements directly in the file list, which
		 * hide on click.
		 */ 
		onSelectFail: function(files) {
			files.each(function(file) {
				new Element('li', {
					'class': 'validation-error',
					html: file.validationErrorMessage || file.validationError,
					title: MooTools.lang.get('FancyUpload', 'removeTitle'),
					events: {
						click: function() {
							this.destroy();
						}
					}
				}).inject(this.list, 'top');
			}, this);
		},
		
		/**
		 * This one was directly in FancyUpload2 before, the event makes it
		 * easier for you, to add your own response handling (you probably want
		 * to send something else than JSON or different items).
		 */
		onFileSuccess: function(file, response) {
			var json = new Hash(JSON.decode(response, true) || {});
			
			if (json.get('status') == '1') {
				file.element.addClass('file-success');
				file.info.set('html','<p style="text-align:center"><img style="margin:0 0 5px 0;cursor:pointer" width="90" src="'+json.get('url')+'" onclick="toEditor(\''+json.get('url')+'\','+json.get('randnum')+')" /><br>title属性 <input type="text" class="colorblur" id="picTitle_'+json.get('randnum')+'" style="width:120px;" /></p>');
			} else {
				file.element.addClass('file-failed');
				file.info.set('html','发生错误' + (json.get('error') ? (json.get('error') + ' #' + json.get('code')) : response));
				//file.info.set('html', '<strong>An error occured:</strong> ' + (json.get('error') ? (json.get('error') + ' #' + json.get('code')) : response));
			}
		},
		
		/**
		 * onFail is called when the Flash movie got bashed by some browser plugin
		 * like Adblock or Flashblock.
		 */
		onFail: function(error) {
			switch (error) {
				case 'hidden': // works after enabling the movie and clicking refresh
					alert('To enable the embedded uploader, unblock it in your browser and refresh (see Adblock).');
					break;
				case 'blocked': // This no *full* fail, it works after the user clicks the button
					alert('To enable the embedded uploader, enable the blocked Flash movie (see Flashblock).');
					break;
				case 'empty': // Oh oh, wrong path
					alert('A required file was not found, please be patient and we fix this.');
					break;
				case 'flash': // no flash 9+ :(
					alert('To enable the embedded uploader, install the latest Adobe Flash plugin.')
			}
		}
		
	});
	
	var status = {'true': '展开批量上传窗口','false': '收起批量上传窗口'};
          	var slide=new Fx.Slide('multiUploadDiv');
          	$('folderMP').addEvent('click',function(e){
          		e.stop();
          		slide.toggle();
          		$('status').set('html',status[slide.open]);
          	});
});

//跟随浏览器滚动
function SetFloatDiv() {
	var floatDiv = $("multiUpload");
	var wtop = document.documentElement.scrollTop;

	if (wtop == 0) {
		wtop = document.body.scrollTop;
	} else {
		wtop = wtop - 50-4;
	}

	if (navigator.appName == 'Netscape') {  //firefox
		wtop = wtop + 103-4;
	}
	else {   //IE
		wtop = wtop - 236-4;
	}

		if (!!window.ActiveXObject && !window.XMLHttpRequest) {
			floatDiv.style.top = (wtop + 50+236+8) + "px";
		}
}
window.setInterval("SetFloatDiv()", 100);