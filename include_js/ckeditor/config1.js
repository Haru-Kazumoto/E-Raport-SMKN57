/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	config.toolbarGroups = [
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		{ name: 'forms', groups: [ 'forms' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'Subscript,Superscript,Cut,Scayt,HorizontalRule,SpecialChar,About,Blockquote,RemoveFormat,Strike,Source,PasteFromWord,PasteText,Paste,Copy,Unlink,Anchor,Undo,Redo,Find,Replace,SelectAll,Flash,Smiley,PageBreak,Iframe,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Save,NewPage,ExportPdf,Preview,Print,Templates,CopyFormatting,CreateDiv,BidiLtr,BidiRtl,Language';
	config.extraPlugins = 'textindent,liststyle';
	config.indentation = "30px";
	config.indentationKey = "tab";
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	
};