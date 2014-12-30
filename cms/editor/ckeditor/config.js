/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:

	config.toolbar = 'cms';
	config.toolbar_cms =
	[
	['Source','Save','NewPage','Preview','Templates'],
	['Cut','Copy','Paste','PasteText','PasteFromWord','Print'],
	['Undo','Redo','Find','Replace','SelectAll','RemoveFormat'],
	'/',
	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	['Link','Unlink','deleteExternalLink','Anchor'],
	['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','NextPage'],
	'/',
	['Styles','Format','Font','FontSize'],
	['TextColor','BGColor'],
	['Maximize', 'ShowBlocks'],
	['flvplayer','autoformat','autophotos']
	];
	
	config.toolbar_Basic =
	[
	['Source','Save','Preview'],
	['Cut','Copy','Paste','PasteText','PasteFromWord','Print'],
	['Undo','Redo','Find','Replace','SelectAll','RemoveFormat'],['Image','Flash','Table','HorizontalRule','SpecialChar'],
	'/',
	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	['Link','Unlink','deleteExternalLink','Anchor'],
	'/',
	['Styles','Format','Font','FontSize'],
	['TextColor','BGColor'],
	['Maximize', 'ShowBlocks'],
	['flvplayer','autoformat']
	];
	
	config.language = 'zh-cn';
	config.uiColor = '#ddd';
	config.width="700px";
	config.height="400px";
	config.border=400;
	config.disableNativeSpellChecker=true;
	config.scayt_autoStartup = false;
	//
	config.baseHref='';
	config.extraPlugins = 'nextpage';
	config.extraPlugins += ',iframedialog';
	config.extraPlugins += ',deleteExternalLink';
	config.extraPlugins += ',flvplayer';
	config.extraPlugins += ',autoformat';
};
