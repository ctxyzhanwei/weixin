CKEDITOR.plugins.add( 'multiPic',
{
	init : function( editor )
	{
		// Register the command.
		editor.addCommand( 'multiPic',{
			exec : function( editor )
			{
				var mpicDiv = document.getElementById("multiUpload");
				mpicDiv.style.display='block';
			}
		});
		// Register the toolbar button.
		editor.ui.addButton( 'multiPic',
            {
                label : '批量传图',
                command : 'multiPic',
                icon: this.path + 'images/images.png'
            });
	},
	requires : [ 'fakeobjects' ]
});