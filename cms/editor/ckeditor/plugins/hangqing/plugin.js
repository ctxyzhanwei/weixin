CKEDITOR.plugins.add('hangqing', {
	lang:['zh-cn'],
	requires: ['dialog'],
	init: function(editor){
		editor.addCommand( 'hangqing', new CKEDITOR.dialogCommand( 'hangqing' ) );
		editor.ui.addButton('hangqing', {
			label: '插入行情',
			command: 'hangqing',
			icon: this.path + 'images/chart_line.png'
		});
		CKEDITOR.dialog.addIframe( 'hangqing','插入行情', '/editor/plugins/hangqing.php', 600, 400);
	}
});