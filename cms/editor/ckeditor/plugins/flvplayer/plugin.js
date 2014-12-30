CKEDITOR.plugins.add('flvplayer', {
	requires: ['dialog'],
	init: function(editor){
		//editor.addCommand( 'flvplayer', new CKEDITOR.dialogCommand( 'flvplayer' ) );
		var pluginName='flvplayer';
		editor.ui.addButton('flvplayer', {
			label: '插入flv视频',
			command: pluginName,
			icon: this.path + 'images/flv.png'
		});
		CKEDITOR.dialog.add(pluginName, this.path + 'dialogs/flvplayer.js');
		editor.addCommand(pluginName, new CKEDITOR.dialogCommand(pluginName));
	}
});