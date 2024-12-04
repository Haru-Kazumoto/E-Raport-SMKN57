CKEDITOR.editorConfig = function( config ) {


	config.toolbar_MyBasic=[
		 { name: 'document', items : ['Source','NewPage','Preview'] },
		 { name: 'basicstyles', items : ['Bold','Italic','Strike','-','RemoveFormat'] },
		 { name: 'clipboard', items : ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'] },
		 { name: 'editing', items : ['Find','Replace','-','SelectAll','-','Scayt'] },
		 '/',
		 { name: 'styles', items : ['Styles','Format'] },
		 { name: 'paragraph', items : ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote'] },
		 { name: 'insert', items :['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'] },
		 { name: 'links', items : ['Link','Unlink','Anchor'] } 
     ];
	
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] }
	];
	

	config.toolbar_MyFull = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		{ name: 'links', groups: [ 'links' ] },
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] }
	];

	//config.removeButtons = 'About,Language,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt,Templates';

	//config.extraPlugins = 'uploader'; // add plugin
	
	//config.uploadFolder = '/images/ck/'; // report plugin which will fill
	config.removeButtons = 'About,Language,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Scayt,Templates';
	config.removePlugins = 'easyimage, cloudservices';
	config.removeDialogFromTabs = 'image:advanced,link:advanced'; 
	config.fileBrowserUploadUrl = '/uploader/uploader.php'; 
	
	

	
};