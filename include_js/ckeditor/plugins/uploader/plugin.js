CKEDITOR.plugins.add( 'uploader',{
	init:function(editor){
		var path = editor.config.uploadFolder?editor.config.uploadFolder:'/images/'; // путь до папки с фото
		editor.on( 'instanceReady', function(e) { 
			var div = document.createElement('div'),fake = document.createElement('div'),
				style =  'display:inline-block; margin-left:10px; height: 16px;width: 16px;position:relative;margin-top:5px;overflow:hidden;float:left;',
				style1 =  'background: url("'+CKEDITOR.basePath+'plugins/uploader/plus.png") no-repeat transparent;cursor: pointer;float: left;height: 16px; position: absolute;width: 16px;z-index: 1;'
			div.setAttribute('id','cke_uploader'+'_'+editor.name);
			div.setAttribute('style',style);
			fake.setAttribute('style',style1);
			div.innerHTML = '<input type="file" multiple="" name="image" style="position:absolute;cursor:pointer;opacity:0.0;z-index:9;" id="cke_uploader'+'_'+editor.name+'_input"/>';
			div.appendChild(fake);
			document.getElementById( 'cke_bottom_'+editor.name ).appendChild(div);
			var input = document.getElementById('cke_uploader'+'_'+editor.name+'_input');
			input.onchange = function(evn){
				var files = input.files,len = files.length,i=0;
				if(!len)return false;
				var form = new FormData(),i=0;
				for (; i < len; i++) {
					form.append("image[]", files[i]);
				}
				input.setAttribute('disabled','disabled');
				var xhr = new XMLHttpRequest();
				xhr.onload = function() {
					console.log("Отправка завершена");
					fake.style.backgroundImage = 'url("'+CKEDITOR.basePath+'plugins/uploader/plus.png")';
					input.removeAttribute('disabled');
					var upfiles = this.responseText.split(';');
					for(i=0; i < upfiles.length; i++){
						var upfile = upfiles[i].split('=');
						if(/^[a-f0-9]{32}\.(jpg|png|gif|bmp|jpeg)$/i.test(upfile[1])){
							editor.insertHtml('<img src="'+path+upfile[1]+'"/>'); 
						}
					}
				};
				xhr.open("post", CKEDITOR.basePath+'plugins/uploader/uploader.php', true);
				fake.style.backgroundImage = 'url("'+CKEDITOR.basePath+'plugins/uploader/progress.gif")';
				xhr.send(form);
			};
		});
	}
});
