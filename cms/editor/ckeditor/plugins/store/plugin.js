CKEDITOR.plugins.add('store', {
	requires: ['iframedialog'],
	init: function(editor){
		editor.addCommand( 'store', new CKEDITOR.dialogCommand( 'store' ) );
		editor.ui.addButton('store', {
			label: '插入经销商',
			command: 'store',
			icon: this.path + 'images/vcard.png'
		});
		CKEDITOR.dialog.addIframe( 'store', '插入经销商', '/index.php?m=store&c=widget_store&a=editor_insertStore', 600, 400);
	}
});