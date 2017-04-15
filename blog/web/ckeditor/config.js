/**
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.plugins.basePath = '/ckeditor/plugins/';

CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'bidi', 'paragraph' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
	];

    config.extraPlugins = 'autogrow';
    config.skin = 'bootstrapck,/ckeditor/skins/bootstrapck/';
	config.removeButtons = 'Styles,TextColor,BGColor,ShowBlocks,Maximize,About,Format,Font,FontSize,CreateDiv,Save,NewPage,Preview,Print,Source,SelectAll,Find,Replace,Cut,Copy,Paste,PasteFromWord,Templates,Flash,Table,HorizontalRule,Smiley,PageBreak,Iframe,Anchor,BidiLtr,BidiRtl,Language,Paste';
	config.autoGrow_minHeight = 250;
	config.height = 250;
	config.resize_enabled = false;
    config.contentsCss = '/css/ckeditor.css';
    config.removePlugins = 'elementspath';
    config.autoGrow_onStartup = true;
    // config.filebrowserBrowseUrl = '/elfinder/elfinder.html';
    // config.filebrowserUploadUrl = '/imgs/uploaded/';
};
