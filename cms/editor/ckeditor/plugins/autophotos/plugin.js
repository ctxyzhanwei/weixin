CKEDITOR.plugins.add('autophotos', {
	requires: ['iframedialog'],
	init: function(editor){
		editor.addCommand( 'autophotos', new CKEDITOR.dialogCommand( 'autophotos' ) );
		editor.ui.addButton('autophotos', {
			label: '插入汽车图片',
			command: 'autophotos',
			icon: this.path + 'images/photos.png'
		});
		CKEDITOR.dialog.addIframe( 'autophotos', '插入汽车图片', '/index.php?m=auto&c=photo&a=selectPhoto&autoid=0&editor=1', 700, 400);
	}
});