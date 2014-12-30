
CKEDITOR.plugins.add( 'deleteExternalLink',
{
	init : function( editor )
	{
		// Register the command.
		editor.addCommand( 'deleteExternalLink',{
			exec : function( editor )
			{
				var isIE6= !!window.ActiveXObject&&!window.XMLHttpRequest;
				if(isIE6){
					alert('浏览器版本过低，不支持该功能');
					return false;
				}
				// Create the element that represents a print break.
				var htmlStr=editor.document.getBody().getHtml();
				var re=/<a[^>]+>(.*?)<\/a>/gi;
				//document.domain
				htmlStr=htmlStr.replace(re,"$1");
				editor.setData(htmlStr)
			}
		});
		// Register the toolbar button.
		editor.ui.addButton( 'deleteExternalLink',
		{
			label : '清除全部链接',
			command : 'deleteExternalLink',
			icon: this.path + 'images/link_break.png'
		});
	},
	requires : [ 'fakeobjects' ]
});
