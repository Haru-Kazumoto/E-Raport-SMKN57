/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbarGroups = [
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'styles', groups: [ 'styles' ] },
	];
//		{ name: 'about', groups: [ 'about' ] }
//{ name: 'forms', groups: [ 'forms' ] },
	config.removeButtons = 'Subscript,Superscript,Cut,Scayt,Table,HorizontalRule,SpecialChar,About,Blockquote,RemoveFormat,Strike,Source,PasteFromWord,PasteText,Paste,Copy,Unlink,Anchor,Undo,Redo,Styles,Format,Font,FontSize,Find,Replace,SelectAll,Flash,Smiley,PageBreak,Iframe,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Maximize,ShowBlocks,Save,NewPage,ExportPdf,Preview,Print,Templates,CopyFormatting,CreateDiv,BidiLtr,BidiRtl,Language';
	config.extraPlugins = 'textindent,liststyle';
	config.indentation = "30px";
	config.indentationKey = "tab";
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	
};
