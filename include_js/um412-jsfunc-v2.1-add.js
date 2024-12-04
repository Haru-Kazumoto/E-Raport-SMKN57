//----add text while copy
function addLink() {
/*
	var body_element = document.getElementsByTagName('body')[0];
	var selection;
	selection = window.getSelection();
	var pagelink = "<br /><br /> Read more at: <a href='"+document.location.href+"'>"+document.location.href+"</a><br />Copyright &copy; c.um412"; // change this if you want
	var copytext = selection + pagelink;
	var newdiv = document.createElement('div');
	newdiv.style.position='absolute';
	newdiv.style.left='-99999px';
	body_element.appendChild(newdiv);
	newdiv.innerHTML = copytext;
	selection.selectAllChildren(newdiv);
	window.setTimeout(function() {
		body_element.removeChild(newdiv);
	},0);
*/
}
document.oncopy = addLink;
//end
/*
function addLink(event) {
	event.preventDefault();
	var pagelink = '\n\n Read more at: ' + document.location.href,
		copytext =  window.getSelection() + pagelink;
	if (window.clipboardData) {
		window.clipboardData.setData('Text', copytext);
	}
}
document.addEventListener('copy', addLink);
*/
function coUrl(myurl,rnd){
	url=myurl+"&useJS=2&contentOnly=1";
	if (rnd!=undefined) url+='&newrnd='+rnd;
	return url;
	
	
}

function gantiProvinsi2(rnd){
	provinsi=$("#provinsi_"+rnd).val();
	kota=$("#kota_"+rnd).val();
	
	url="index.php?contentOnly=1&page=combo&jcombo=provinsi&newrnd="+rnd+"&provinsi="+encodeURI(provinsi)+"&kota="+encodeURI(kota);
	bukaAjax("tkota_"+rnd,url);
}
function randomString(STRlen) {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = STRlen;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}


function unmaskRp(r){
	if (r==undefined) {
		console.log("nan");
		return 0;
	}
	r+="";
	if (decimalSeparator==",") {
		r=r.replaceAll(".","");
		r=r.replaceAll(",",".");
	} else {
		r=r.replaceAll(",","");
		
	}
 
	return r*1;
}

function maskRp(r,a,b){
	r*=1;
	if (b==undefined) 
		b=useDecimal;
	else if (b==9){
		//flexible coma
		for (i==2;i>0;i--) {
			//80.21 dengan 80.2
			v1=r.toFixed(i);
			v2=r.toFixed(i-1);
			//console.log("v "+i+" :"+v1+", v"+(i-1)+":"+v2);
			if (v1*1==v2*1)
				b=i-1;
			else {
				b=i;
				i=0;
			}
			
		}
	} 
	
	return (r.formatMoney((b==0?0:b),  decimalSeparator,thousandSeparator));
	
}

function hitungKata(input,tempatjlh,rnd,maxjlh){
	//console.log("input='"+input+"';tempatjlh='"+tempatjlh+"';rnd='"+rnd+"';hitungKata(input,tempatjlh,rnd);");
	k=$('#'+input).val();
	akata=k.trim().split(" ");
	jlh=akata.length;
	$('#'+tempatjlh).html(jlh);
	return (jlh<=maxjlh*1?true:false);
}

function hitungKata2(txt){
	txt=txt.trim();
	if (txt=='') return 0;
	akata=txt.split(" ");
	jlh=akata.length;
	return jlh;
}


function inputTags(idinput){
	var input = document.querySelector('input[id='+idinput+']'),
    // init Tagify script on the above inputs
    tagify = new Tagify(input, {
        whitelist : ["A# .NET", "A# (Axiom)", "A-0 System", "A+", "A++", "ABAP", "ABC", "ABC ALGOL", "ABSET", "ABSYS", "ACC", "Accent", "Ace DASL", "ACL2", "Avicsoft", "ACT-III", "Action!", "ActionScript", "Ada", "Adenine", "Agda", "Agilent VEE", "Agora", "AIMMS", "Alef", "ALF", "ALGOL 58", "ALGOL 60", "ALGOL 68", "ALGOL W", "Alice", "Alma-0", "AmbientTalk", "Amiga E", "AMOS", "AMPL", "Apex (Salesforce.com)", "APL", "AppleScript", "Arc", "ARexx", "Argus", "AspectJ", "Assembly language", "ATS", "Ateji PX", "AutoHotkey", "Autocoder", "AutoIt", "AutoLISP / Visual LISP", "Averest", "AWK", "Axum", "Active Server Pages", "ASP.NET", "B", "Babbage", "Bash", "BASIC", "bc", "BCPL", "BeanShell", "Batch (Windows/Dos)", "Bertrand", "BETA", "Bigwig", "Bistro", "BitC", "BLISS", "Blockly", "BlooP", "Blue", "Boo", "Boomerang", "Bourne shell (including bash and ksh)", "BREW", "BPEL", "B", "C--", "C++ – ISO/IEC 14882", "C# – ISO/IEC 23270", "C/AL", "Caché ObjectScript", "C Shell", "Caml", "Cayenne", "CDuce", "Cecil", "Cesil", "Céu", "Ceylon", "CFEngine", "CFML", "Cg", "Ch", "Chapel", "Charity", "Charm", "Chef", "CHILL", "CHIP-8", "chomski", "ChucK", "CICS", "Cilk", "Citrine (programming language)", "CL (IBM)", "Claire", "Clarion", "Clean", "Clipper", "CLIPS", "CLIST", "Clojure", "CLU", "CMS-2", "COBOL – ISO/IEC 1989", "CobolScript – COBOL Scripting language", "Cobra", "CODE", "CoffeeScript", "ColdFusion", "COMAL", "Combined Programming Language (CPL)", "COMIT", "Common Intermediate Language (CIL)", "Common Lisp (also known as CL)", "COMPASS", "Component Pascal", "Constraint Handling Rules (CHR)", "COMTRAN", "Converge", "Cool", "Coq", "Coral 66", "Corn", "CorVision", "COWSEL", "CPL", "CPL", "Cryptol", "csh", "Csound", "CSP", "CUDA", "Curl", "Curry", "Cybil", "Cyclone", "Cython", "M2001", "M4", "M#", "Machine code", "MAD (Michigan Algorithm Decoder)", "MAD/I", "Magik", "Magma", "make", "Maple", "MAPPER now part of BIS", "MARK-IV now VISION:BUILDER", "Mary", "MASM Microsoft Assembly x86", "MATH-MATIC", "Mathematica", "MATLAB", "Maxima (see also Macsyma)", "Max (Max Msp – Graphical Programming Environment)", "Maya (MEL)", "MDL", "Mercury", "Mesa", "Metafont", "Microcode", "MicroScript", "MIIS", "Milk (programming language)", "MIMIC", "Mirah", "Miranda", "MIVA Script", "ML", "Model 204", "Modelica", "Modula", "Modula-2", "Modula-3", "Mohol", "MOO", "Mortran", "Mouse", "MPD", "Mathcad", "MSIL – deprecated name for CIL", "MSL", "MUMPS", "Mystic Programming L"],
    //  blacklist : [".NET", "PHP"] // <-- passed as an attribute in this demo
    });

	// "remove all tags" button event listener
	document.querySelector('.tags--removeAllBtn')
		.addEventListener('click', tagify.removeAllTags.bind(tagify))

	// Chainable event listeners
	tagify.on('add', onAddTag)
		  .on('remove', onRemoveTag)
		  .on('input', onInput)
		  .on('invalid', onInvalidTag)
		  .on('click', onTagClick);

	// tag added callback
	function onAddTag(e){
		console.log(e.detail);
		console.log("original input value: ", input.value)
		tagify.off('add', onAddTag) // exmaple of removing a custom Tagify event
	}

	// tag remvoed callback
	function onRemoveTag(e){
		console.log(e.detail);
		console.log("tagify instance value:", tagify.value)
	}

	// on character(s) added/removed (user is typing/deleting)
	function onInput(e){
		console.log(e.detail);
	}

	// invalid tag added callback
	function onInvalidTag(e){
		console.log(e.detail);
	}

	// invalid tag added callback
	function onTagClick(e){
		console.log(e.detail);
	}
}

function submitForm(namaForm,namaDivHasil) {
	$("#"+namaForm).submit(function(event){
		// Prevent default posting of form - put here to work in case of errors
		event.preventDefault();

		// Abort any pending request
		/*
		if (request) {
			request.abort();
		}
		*/
		// setup some local variables
		var $form = $(this);

		// Let's select and cache all the fields
		var $inputs = $form.find("input, select, button, textarea");

		// Serialize the data in the form
		var serializedData = $form.serialize();

		// Let's disable the inputs for the duration of the Ajax request.
		// Note: we disable elements AFTER the form data has been serialized.
		// Disabled form elements will not be serialized.
		$inputs.prop("disabled", true);

		// Fire off the request to /form.php
		request = $.ajax({
			url: "/form.php",
			type: "post",
			data: serializedData
		});

		// Callback handler that will be called on success
		request.done(function (response, textStatus, jqXHR){
			// Log a message to the console
			$("#"+namaDivHasil).html(response);
			console.log("Hooray, it worked!"+response);
		});

		// Callback handler that will be called on failure
		request.fail(function (jqXHR, textStatus, errorThrown){
			// Log the error to the console
			console.error(
				"The following error occurred: "+
				textStatus, errorThrown
			);
		});

		// Callback handler that will be called regardless
		// if the request failed or succeeded
		request.always(function () {
			// Reenable the inputs
			$inputs.prop("disabled", false);
		});

	});
}

//select and copy to clipboard
function copyToClipboard(element) {
	var range = document.createRange();
    range.selectNode(document.getElementById(element));
    window.getSelection().addRange(range);
    document.execCommand("copy");
	window.getSelection().removeAllRanges();
    alert("Data berhasil dicopy");
}

//print div to image
function printCanvasToImage(nm) {
	var canvas = document.getElementById(nm);
	var win = window.open();
	win.document.write("<br><img src='" + canvas.toDataURL() + "'/>");
	win.print();

}

//monthpicker
function monthPicker(selector) {
	$(selector).datepicker({ 
		dateFormat: 'mm-yy',
		changeMonth: true,
	    changeYear: true,
	    showButtonPanel: true,

	    onClose: function(dateText, inst) {  
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
            $(this).datepicker('setDate', new Date(year, month, 1)); 
        }
	});
	
	$(selector).focus(function () {
		$(".ui-datepicker-calendar").hide();
		$("#ui-datepicker-div").position({
			  my: "center top",
			  at: "center bottom",
			  of: $(this)
			});
		
	});
}

function terbilang(n){
    var bilangan=n+"";//document.getElementById("nominal").value;
    var kalimat="";
    var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
    var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
    var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');
    var panjang_bilangan = bilangan.length;
     
    /* pengujian panjang bilangan */
    if(panjang_bilangan > 15){
        kalimat = "Diluar Batas";
    }else{
        /* mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array */
        for(i = 1; i <= panjang_bilangan; i++) {
            angka[i] = bilangan.substr(-(i),1);
        }
         
        var i = 1;
        var j = 0;
         
        /* mulai proses iterasi terhadap array angka */
        while(i <= panjang_bilangan){
            subkalimat = "";
            kata1 = "";
            kata2 = "";
            kata3 = "";
             
            /* untuk Ratusan */
            if(angka[i+2] != "0"){
                if(angka[i+2] == "1"){
                    kata1 = "Seratus";
                }else{
                    kata1 = kata[angka[i+2]] + " Ratus";
                }
            }
             
            /* untuk Puluhan atau Belasan */
            if(angka[i+1] != "0"){
                if(angka[i+1] == "1"){
                    if(angka[i] == "0"){
                        kata2 = "Sepuluh";
                    }else if(angka[i] == "1"){
                        kata2 = "Sebelas";
                    }else{
                        kata2 = kata[angka[i]] + " Belas";
                    }
                }else{
                    kata2 = kata[angka[i+1]] + " Puluh";
                }
            }
             
            /* untuk Satuan */
            if (angka[i] != "0"){
                if (angka[i+1] != "1"){
                    kata3 = kata[angka[i]];
                }
            }
             
            /* pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat */
            if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")){
                subkalimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
            }
             
            /* gabungkan variabe sub kalimat (untuk Satu blok 3 angka) ke variabel kalimat */
            kalimat = subkalimat + kalimat;
            i = i + 3;
            j = j + 1;
        }
         
        /* mengganti Satu Ribu jadi Seribu jika diperlukan */
        if ((angka[5] == "0") && (angka[6] == "0")){
            kalimat = kalimat.replace("Satu Ribu","Seribu");
        }
    }
  return (kalimat.trim());
}

function toggleShow(id1,id2){
	
	sid2='#'+id2.replaceAll(",",",#");
	sid1='#'+id1.replaceAll(",",",#");
	
	sid2=sid2.replaceAll("#.",".");
	sid1=sid1.replaceAll("#.",".");

	$(sid2).show();
	$(sid1).hide();
	
}
function toggleAttr(id1,attr,attr1,attr2){
	va=$('#'+id1).attr(attr);
	attrNew=(va==attr1?attr2:attr1);
	$('#'+id1).attr(attr,attrNew);
}
function toggleCSS(id1,attr,attr1,attr2){
	va=$('#'+id1).css(attr);
	attrNew=(va==attr1?attr2:attr1);
	$('#'+id1).css(attr,attrNew);
}

function ValidateInputUID(vinput) {
	var username = document.getElementById(vinput).value;
	//var lblError = document.getElementById("lblError");
	lblError.innerHTML = "";
	var expr = /^[a-zA-Z0-9._]*$/;
	if (!expr.test(username)) {
		//lblError.innerHTML = "Only Alphabets, Numbers, Dot and Underscore allowed in Username.";
		return false;
	} else {
		return true;
	}
}


// getValueURL(http://www.google.com?s=1&b=1,"s","a") 
function getValueURL(url,key,jresult) {
	match = url.match('[?&]' + key + '=([^&]+)');
	hasil="";
	try {
		h=match[1];
		hasil=match;
	} catch(e) {
		hasil=['','']
	}
	if (jresult==undefined) jresult="v";
	if (jresult=="a")
		return hasil;	
	else
		return hasil[1];
}

function ckeold(target,useConfig){
	if (useConfig==undefined) useConfig=2;	
	nfconfig="config"+useConfig+".js";
	nfc=nfconfig;//
	//nfc=js_path+'ckeditor/'+nfconfig;
	//alert(nfc);
	prefict="";
	folderUpload='uploaded';
	url="validasi-local.php?form=cke&pu="+folderUpload+"&mr=2"; 
	url+="&prefict="+prefict;
	CKEDITOR.replace(target, {
		customConfig:"config-minim1.js",
		filebrowserUploadUrl: url,
	});
}

function cke(target,useConfig) {
	if (useConfig==undefined) useConfig=2;	
	nfconfig="config"+useConfig+".js";
	nfc=nfconfig;
	
	prefict="";
	folderUpload='uploaded';
	url="validasi-local.php?form=cke&pu="+folderUpload+"&mr=2"; 
	url+="&prefict="+prefict;
	//alert(useConfig);
	if (useConfig=="2") { 
		//lengkap
		CKEDITOR.config.toolbarGroups = [
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

		CKEDITOR.config.removeButtons = 'Subscript,Superscript,Scayt,HorizontalRule,SpecialChar,RemoveFormat,Strike,Source,About,Language';
		CKEDITOR.replace(target, {
			customConfig:nfc,
			filebrowserUploadUrl: url,
		});
		
	} else if (useConfig=="3") { 
		//lengkap
		/*
		CKEDITOR.config.toolbarGroups = [
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

		CKEDITOR.config.removeButtons = 'Subscript,Superscript,Scayt,HorizontalRule,SpecialChar,RemoveFormat,Strike,Source,About,Language';
		
		*/
		CKEDITOR.replace(target, {
			customConfig:nfc,
			filebrowserUploadUrl: url,
		});
		
	}  else {
		var editor = CKEDITOR.instances.myEditor;
		if (editor) { editor.destroy(true); }
		//CKEDITOR.config.forcePasteAsPlainText = false;
		//CKEDITOR.config.width = "1000";
		//CKEDITOR.config.height = 300;
		target=CKEDITOR.replace(target,{
			startupFocus: true,
			toolbar: toolbar = [
				{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
				{ name: 'insert', items: [ 'Image'] },
				{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
				{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'CopyFormatting' ] },
				{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',  '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
				{ name: 'colors', items: [ 'TextColor', 'BGColor' ] }
			],
			filebrowserUploadUrl: url,
		});
		
	
	
	}
	
}

function cekCari2(rnd) {
	url=$('#urlcari_'+rnd).val();
	eval("url='"+url+"';");
	bukaAjax('thcari3-'+rnd,url);
	return false;
}

function toggleSP(objUps,objIcon) {
  var x = document.getElementById(objUps);
  var y = document.getElementById(objIcon);
  if (x.type === "password") {
    x.type = "text";
	$(y).removeClass('fa-eye');
	$(y).addClass('fa-eye-slash');
	
  } else {
    x.type = "password";
	$(y).removeClass('fa-eye-slash');
	$(y).addClass('fa-eye');
  }
}


function responsiveYTPlayer(id) {
		if (id.substr(0,1)!='.') 
			selector='#'+id
		else
			selector=id
			
		//responsive
		$(selector).css('max-width','700px');
		$(selector).css('width','100%');
		wp=$(selector).width();
		hp=2/3.2*wp;//h seharusnya
		
		hs=window.innerHeight;
		ws=window.innerWidth;
		hh=$('header').innerHeight();
		
		hmax=hs-hh-120;
			
		$(selector).height(hp);
		$(selector).css('max-height',hmax+'px');
		//alert(id+' responsive');
}

function evalHapusFile(response,rndx) {
	response=decodeURI(response)
	if (response=="1") {
		try { 
		$('#idf'+rndx).remove();
		} catch(e) {}
		//alert ("peghapusan file berhasil");
	} else {
		alert(response);
		//alert ("peghapusan file tidak berhasil");
	}
}

function logoutUser2(showConfirm){
	if (showConfirm==undefined) showConfirm=true;
	referer="index.php";
	myurl="index.php?page=login&op=logout";
	if (showConfirm) {
		if (!confirm("Yakin akan keluar dari system?")) return false;
	}
	showLoading();	
	$.ajax({
		url: myurl
	}).done(function(data) {
		data=data.trim();
		location.href=referer;
		
		if (data=="1"){
			//location.href=referer;
		} else {
			//alert(data);
			
		}
		hideLoading();
			
	});	
}



function showLoading(){
	try { 
		document.getElementById('imgloader').style.display="block"; 
	} catch(e) {}
	
}
function hideLoading(){
	try { 
		document.getElementById('imgloader').style.display="none";
	} catch(e) {}
	
}

function centerObject(obj1,obj2,pos1,pos2) {
	if (pos1==undefined) pos1="center center";
	if (pos2==undefined) pos2="center center";
	if (obj2==undefined) {
		xobj2=window;
	} else {
		xobj2="#"+obj2;
	}
	$("#"+obj1).position({
		  my: pos1,
		  at: pos2,		  
		  of: $(xobj2)
		});
			
}



document.onreadystatechange = function () {
  var state = document.readyState
  if (state == 'interactive') {
       showLoading();
  } else if (state == 'complete') {
      setTimeout(function(){
		  hideLoading();
      },1000);
  }
}

function genRnd(v){
	if (v==undefined) v=10000;
	return Math.round(Math.random(92398891)*10000,0);
}

//fungsi untuk dialog modal
function loadPopupModal(opt) {    
	
	if (opt==undefined) {
		showCloseBtn=true;
		url='';
		data='';
		useIFrame=false;
		width=0;
    } else {
		showCloseBtn=(opt.showCloseBtn==undefined?true:opt.btnClose); 
		useIFrame= (opt.iframe==undefined?false:opt.iframe); 
		url= (opt.url==undefined?"":opt.url); 
		useUrl= (opt.url==undefined?false:true); 
		data=(opt.data==undefined?'':opt.data); 
		width=(opt.width==undefined?0:opt.width); 
	}
	var idpm="pm"+genRnd();
    var idcover="coverpm"+idpm;
	
	var cover=document.createElement("div");
	cover.setAttribute('class', 'tmdialog-cover');
    cover.setAttribute('id', idcover);
    cover.setAttribute('onclick', "$(this).fadeOut('slow');$('#"+idpm+"').fadeOut('slow');");
	
	var pm = document.createElement("div");
	pm.setAttribute('class', 'tmdialog');
    pm.setAttribute('id', idpm);
	
	body= '<div class="tmdialog-body">\n ';
	body+=data;
	if (useUrl) {
		body+='<iframe src="' + url + '"></iframe>';	
	}
	body+='</div>\n ';
    if (showCloseBtn) {
		body+='<div class="tmdialog-footer"><button class="tmdialog-btn-close" onclick="$(\'.tmdialog , .tmdialog-cover\').fadeOut(\'slow\');">CLOSE</button></div>';
	}
	body+='\n</div>';
	
	pm.innerHTML=body;
	document.body.appendChild(cover);
	document.getElementById(idcover).style.display = "block";
	document.body.appendChild(pm);
	document.getElementById(idpm).style.display = "block";
	
	if (width!=0) document.getElementById(idpm).style.width = width+'px';
	$("#"+idpm).center();
	
	//alert(idpm);
}
//bukaAjakModal
function bukaAjaxM(url,opt) {
	if (opt==undefined) opt={};
	myurl= url + "?useJS=2&contentOnly=1";
	$.ajax({
		url: myurl
	}).done(function(data) {
		opt.data=data;
		loadPopupModal(opt);     
		
	});

	
	/*
	if (useIFrame) {
		alert(url);
		body+='<iframe src="' + url + '"></iframe>';
	} else {
	}
	*/
}

//get count line of div , usage:alert(countLines('page1'));
function countLines(id) {
   var el = document.getElementById(id);
	var style = window.getComputedStyle(el, null);
	var height = parseInt(style.getPropertyValue("height"));
	var font_size = parseInt(style.getPropertyValue("font-size"));
	var line_height = parseInt(style.getPropertyValue("line-height"));
	var box_sizing = style.getPropertyValue("box-sizing");

	if(isNaN(line_height)) line_height = font_size * 1.2;

	if(box_sizing=='border-box')	{
		var padding_top = parseInt(style.getPropertyValue("padding-top"));
		var padding_bottom = parseInt(style.getPropertyValue("padding-bottom"));
		var border_top = parseInt(style.getPropertyValue("border-top-width"));
		var border_bottom = parseInt(style.getPropertyValue("border-bottom-width"));
		height = height - padding_top - padding_bottom - border_top - border_bottom
	}
	var lines = Math.ceil(height / line_height);
	//alert("Lines: " + lines);
	return lines;
} 

jQuery.fn.center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
    return this;
}

$(document).ready(function(){	
	$('.tmdialog-btn-open').click(function(){
		$('.tmdialog , .tmdialog-cover').fadeIn("slow");
	});
	$('.tmdialog-btn-close').click(function(){
		$('.tmdialog , .tmdialog-cover').fadeOut("slow");
	});
	$('.tmdialog-cover').click(function(){
		alert(1);
		$('.tmdialog , .tmdialog-cover').fadeOut("slow");
	});
});
