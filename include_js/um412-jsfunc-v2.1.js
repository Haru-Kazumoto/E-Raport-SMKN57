var hasilForm="";
var wMax=window.screen.width;
var hMax=window.screen.height; 
var zIndexDialog=10000;
//$('#tstatus69858').dialog().parent('.ui-dialog').css('zIndex',999999);
//var imgWait='<span align=\'center\'><span style=\'width:150\' class=\'wait\'><br>Please Wait.....</span></span>';
var imgWait="<span class='overlay'><i class='fa fa-refresh fa-spin'></i></span>";
var curDet='';//diupdate setiap det berubah;
var rndDet=0;//diupdate setiap det berubah;

function createRequest(){ 
   var oAJAX = false;
	try {
	  oAJAX = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
	  try {
		 oAJAX = new ActiveXObject("Microsoft.XMLHTTP");
	  } catch (e2) {
		 oAJAX = false;
	  }
	}
	if (!oAJAX && typeof XMLHttpRequest != 'undefined') {
		oAJAX = new XMLHttpRequest();
	}
	if (!oAJAX){
	   alert("Error saat membuat XMLHttpRequest!");
	}        
	return oAJAX;
}

//tambahkan param useJS dan idtd
function urlBA(tempat,url) {
	tambah=""
	if (url.indexOf('useJS')<0) tambah+="useJS=2";
	url=url.replaceAll('&cO1','&contentOnly=1');
	//if (url.indexOf('showHeader')<0) url+="showHeader=2";
	//if (url.indexOf('openAjax')<0) url+="&openAjax=2";
	//if (url.indexOf('contentOnly')<0) url+="&contentOnly=1";
	if (tempat!='') {
		if (url.indexOf('idtd')<0) tambah+="&idtd="+tempat;
	}
	
	if (tambah!='') {
		url=url+((url.indexOf("?",0)>0)?"&":"?")+tambah;	
	}
	return url;
}

function bukaAjax(tempat,url,rnd,func){
	//console.log('bukaajax '+url);
	//rnd:0 tanpa tombol close,1;TOMBOL,return 2:VALUE; return 3:var	
	//if (tempat=='') rnd=3;;
	//jika tempat ='' dialihkan menggunakan fungsi ajax
	
	
	if (rnd==undefined) rnd=0;
	tbc="";
	if (rnd==1) {
		tbc="<p><div align=right ><a href=# onclick=\"document.getElementById('"+tempat+"').innerHTML='';";
		tbc+="return false;\" class=button2 > close </a></div></p>";
	}
	
	if (tempat=='') {
		url=urlBA(tempat,url);
		serializedData='';
		request = $.ajax({
			url: url,
			type: "post",
			data: serializedData
		}).done(function(data) {
			//func='alert(#response#);');

			if (func!='') {
				data=encodeURI(data);
				f=func.replaceAll("#response#","\""+data+"\"");
				eval(f);
			}
			return data;
		});
		
	} else {
		//if ((tempat=="")||(tempat==undefined)) tempat="maincontent";
		try {
			if (rnd<=1) {
				document.getElementById(tempat).innerHTML=imgWait;
			}
		} catch(e) {
			console.log('>'+tempat+ ' cannot be found....');
			return "";
		}
		
		//if (rnd!=3) document.getElementById(tempat).innerHTML='';
		//rnd2=Math.round(Math.random()*12341,0);
	}
		

	//alert(2);	
	
	url=urlBA(tempat,url);
	
	var oRequest  = new  createRequest();  
	pt=url.indexOf("?",0);
	var urlonly=url.substr(0,pt); //"lorem=ipsum&name=binny";
	var params =url.substr(pt+1,url.length); 
	
	oRequest.open("POST", urlonly, true);
	oRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
	//oRequest.setRequestHeader("Content-length", params.length);
	//oRequest.setRequestHeader("Connection", "close");
	
	
	var response='';
	oRequest.onreadystatechange = function () {    
		try  {
			if (oRequest.readyState == 4) { 
			  	response=  oRequest.responseText;
				if (rnd==2) {
					document.getElementById(tempat).value  = response ;    
				} else if (rnd<=1) { 
					 
				
					try {
						
						document.getElementById(tempat).innerHTML =tbc+response ;  
						
					} catch(e) {
						//alert("JS: "+tempat+" tidak diketahui");
					}
				} 
			
				if (func==undefined) func="";
				
				if (func!='fae') {
					try { 
						eval(func); 							
						fixLayout();
					} catch (e) { }
				} else  {
					
					//alert("fae>"+rnd);
					//alert("fnf>"+newfunc);
					try { 
						newfunc=$("#fae"+rnd).html();
						eval(newfunc); 
						fixLayout();
					
					} catch (e) { }
				}
			
				return false;
				
			}  
		} catch (e) { 
		
			 alert("error "+e);
			 oRequest.open("POST", urlonly, true);
			 oRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
			 oRequest.send(null);
		} //try{
	} //fungsi
  oRequest.send(params);
}
function bAjax(url){
 	return bukaAjax('maincontent',url);
	}
	
function cekReadyState(r){
	return r;
	}	

function fixDlgPosition(target){
	$("#"+target).parent().css('position', 'fixed'); 
}

//buka ajax dan buka dialog
function bukaAjaxD(tempat,url,opsidialog,func2,title){
	if (func2==undefined) func2="";
	if (title!=undefined) {
		$("#"+tempat).prop("title",title);
	}
	
	opsidialog=gantiOpsiDialog(opsidialog);
	func="";
	//func+="$('#"+tempat+"').dialog().dialog('close');";
	func+="$('#imgwait').dialog('close');";
	func+="$('#"+tempat+"').dialog("+opsidialog+");";
	//.position({my: 'top',at: 'top',of: window});";
	//func+="$('.dt').css('width','"+wDT+"px');$('.dd').css('width','"+wDD+"px');";

	func+=func2;  
	/*
	//func+="$('#"+tempat+"').dialog('close');";
	//menutup dialog yang ada 
	//$('#'+tempat).dialog().dialog("close");
	//$('#'+tempat).dialog({width:300});	
	url=urlBA(tempat,url);
	eval("$('#"+tempat+"').load(url).dialog("+opsidialog+");");
	*/
	$('#imgwait').html(imgWait);
	$('#imgwait').dialog({width:300});
	//$('#'+tempat).hide();
	//eval("$('#"+tempat+"').dialog("+opsidialog+");");
	//eval("$('#"+tempat+"').load(url).dialog("+opsidialog+");");
	if (url!='') {
 		bukaAjax(tempat,url,0,func);
	} else {		 
		console.log('>running function '+func+ ' ..');
		eval(func);
	}
	return false;
}

function bukaAjaxD2(tempat,url,opsidialog,func2,title){
	rndx=Math.floor((Math.random()*123423));
	url=url+rndx;
	func2="awalEdit("+rndx+");";
	return bukaAjaxD(tempat,url,opsidialog,func2,title);

}


//fixing dialog content
/*
$("#popOutMoreFolder").dialog({
       ...,
	   resizeStop: myResize
    });*/
function myResize(event, ui) {
	var sc=window.ScrollY;
	var dialog = $(event.target);
	dialog.css({
		top:sc,
		'max-height':'400px'
	});
	try { 
		if (umjsVer>=2) {
			var $content = dialog.closest(".ui-dialog-content");
			$content.css("width", "100%");
			$content.css("display", "block");
			$(this).parent().css('position', 'fixed'); 
		}
	} catch(e){}
	/*
	$(this).width($(this).prev('.ui-dialog-titlebar').width() + 2);
	$('.dt').css("width",wDT+'px');
	$('.dd').css("width",wDD+'px');
	*/
	//$(this).height($(this).parent().height() - $(this).prev('.ui-dialog-titlebar').height() - 30);
	
	
}


function jsCetak(url,w,h){
	  if (w==undefined) w=800;
	  if (h==undefined) h=500;
	  openWin(url, 'cetak', w, h, true); 	 
}
function openWin(strURL, strWinName, intWidth, intHeight, boolScroll) {
    // variables to determine the center position of window
    var xPos = (screen.width - intWidth)/2;
    var yPos = (screen.height - intHeight)/2;
    var withScrollbar = 'no';
    // if scrollbar allowed
    if (boolScroll) {
        withScrollbar = 'yes';
    }
    w=window.open(strURL, strWinName, 'height=' + intHeight + ',width=' + intWidth +
    ',menubar=no, scrollbars=' + withScrollbar + ', location=no, toolbar=no,' +
    'directories=no,resizable=no,screenY=' + yPos + ',screenX=' + xPos + ',top=' + yPos + ',left=' + xPos);
	return w;
}
function gantiOpsiDialog(opsidialog){
	addopsi="draggable: true, autoOpen: true,maxHeight:"+(hMax-200)+",maxWidth:"+(wMax-40)+", resizeStop: myResize,create:myResize";
	if ((opsidialog==undefined) ||   (opsidialog=="width:300"))
		opsidialog=addopsi;
	else
		opsidialog+=","+addopsi;
	
	
	
	opsidialog=opsidialog.replace("wMax",wMax);
//	opsidialog=opsidialog.replace("wMax","auto");
	opsidialog=opsidialog.replace("hMax",hMax);
	opsidialog=opsidialog.replace("-c-", "buttons: {Ok: function() {$( this ).dialog( 'close');}} ");
	opsidialog=opsidialog.replace("-m-","modal: true");	
	opsidialog="{"+opsidialog+"}";
	return opsidialog;
	
}
function bukaDialog(tempat,pes,opsi){
	//opsi
	if (opsi=='') opsi="-c-,width:300";
	opsi=gantiOpsiDialog(opsi);
	$("#"+tempat).html(pes);
	eval("$('#"+tempat+"').dialog("+opsi+");$('.ui-dialog-content').css('max-height','"+hMax+"px');");
}
//ckeditor
function gantiEditor(nama,noc){
	if (noc==undefined) noc=2;
	cke(nama,noc);
}

function CKUpdate(){
   try {
   	for ( instance in CKEDITOR.instances )  { CKEDITOR.instances[instance].updateElement(); }
   } catch (e) { return 0; }
}
  //buat autoscroll, otomatis scroll jika 
function autoScroll(tload,linknext){
	linknext=(linknext==undefined?'linknext':linknext);	   
	xlinknext="#"+linknext;
	if ($(xlinknext)==null) return false;
	if ($(xlinknext).offset()==null) return false;
	//if ($(linknext).offset().top!=null) {
		tload=(tload==undefined?'tload':tload);
		xtload="#"+tload;
		$(window).scroll(function(){
			tinggi=$(window).height();							  
			scrollx=$(window).scrollTop();
			try {
				c=$(xlinknext).offset().top;
				if ($(xlinknext).offset().top<=$(window).height()+scrollx) {
					myurl=$(xlinknext).attr('href');
					$(xlinknext).remove();
					$(xtload).append('<div id=twaitscroll>'+imgWait.replace('Please Wait','Preparing Next Page. Please Wait ... ')+'</div>');
					$.ajax({
						url: myurl
					}).done(function(data) {
						$('#twaitscroll').remove();
						$(xtload).append(data);
					});
					return false; 
				}
			} catch (e) {}
		});
	//}
}
//autoScroll();
function tengahkan(obj,w,h){
	var dia= document.getElementById(obj);	
	dia.style.position='absolute';	
	if (w==0)  
		w=dia.style.width;
	else if (w>0) //change 
		dia.style.width=w;
	if (h==0) 
		h=dia.style.height;
	 else if (h>0)
		dia.style.height=h;
	if (w>0) dia.style.left=(sw-w)/2;
	if (h>0) dia.style.top=(sh-h)/2;
	dia.style.display='';
}
function validasiEmail(email){
	var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
	if (filter.test(email))
	 return 1;
	else 
	 return 0;	
}	
function bukaJendela(url,nama,fokus,opsi,func){
	if ((opsi==undefined)||(opsi=='')) opsi= "location=1,status=1,scrollbars=1,width="+(screen.width-100)+",height="+(screen.height-100);
	if (fokus==undefined) fokus=1;
		//opsi="";
	if (url!='') {
		if (func!=undefined) {
			url=url+encodeURI("&olf="+func);
		}	
	}
	
	mywindow = window.open(url, nama,opsi);
    if (fokus==1) {
		mywindow.moveTo(0, 0);
		mywindow.focus();
	}
	return mywindow;
}
function bukaJendela2(url,func,nama,opsi){
	if (nama==undefined) nama="jb";
	if (opsi==undefined) opsi="";
	return bukaJendela(url,nama,fokus=1,opsi,func);
}
//mengubah urutan halaman
function orderPage(uop,nama){
	br=	document.getElementById('br').value;
	document.getElementById(nama).style.display='none';
	awal=document.getElementById("orderredpage").innerHTML;
	akhir=awal+"\n update tbpage set urutan="+br+" where pg='"+uop+"';";
	document.getElementById("orderredpage").innerHTML=akhir;
	br++;
	document.getElementById('br').value=br;
}
function previewHTML(target,obj){
  var inf = document.getElementById(obj).value;
	inf+= "<br><input type=button value='Close Preview' onclick=\"win.close()\";return false;\">";
  win = window.open(", ", 'popup', 'toolbar = no, status = no');
  win.document.write("" + inf + "");
  win.focus();
	//document.getElementById(target).innerHTML=html;
}
function bookmark()  {
	if (window.sidebar && window.sidebar.addPanel) { // Mozilla Firefox Bookmark
		window.sidebar.addPanel(document.title,window.location.href,'');
	} else if(window.external && ('AddFavorite' in window.external)) { // IE Favorite
		window.external.AddFavorite(location.href,document.title); 
	} else if(window.opera && window.print) { // Opera Hotlist
		this.title=document.title;
		return true;
	} else { // webkit - safari/chrome
		alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') +
																	' + D to bookmark this page.');
	}
}
function rupiah(angka,mu){
	if (decimalSeparator==",") {
		if (mu=='usd')
			return "USD "+(angka.formatMoney(0, '.', ','))+"";
		else
			return "Rp. "+(angka.formatMoney(0, '.', ','))+",-";
	} else {
		if (mu=='usd')
			return "USD "+(angka.formatMoney(0, ',', '.'))+"";
		else
			return "Rp. "+(angka.formatMoney(0, ',', '.'))+".-";
		
	}
}
function rupiah2(v){
 return v.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}
//add on jquery
function tampilDialog(url,w,h){
	var newDiv = document.createElement("div");
	newDiv.setAttribute('id','dialogxx');
	newDiv.setAttribute('style','position:absolute;z-index:10000');
	newDiv.innerHTML = "<a href=\"javascript:tutupDialog();\">tutup</a>";
	//newDiv.innerHTML=""; 
	var db=document.body;	
	db.appendChild(newDiv);
	bukaAjax('dialogxx',url);	
	var dia=document.getElementById('dialogxx');	
	//dia.style.background="#fff";
	dia.style.width=w;
	dia.style.height=h;
	tengahkan('dialogxx',w,h);
	window.scroll(0,0);
}
function tutupDialog(){
	var db = document.body;
	var oldDiv = document.getElementById("dialogxx");
	db.removeChild(oldDiv);	
}
function tutupDialog2(id){
	$('#'+id).closest('.ui-dialog-content').dialog('close');
	$('#'+id).parents('.ui-dialog-content').dialog('close');
	$('#'+id).html('');
}
function bukaTgl(nama){
	  $('#'+nama).datepicker({dateFormat : formatTgl});
	  $( "#"+nama).attr("onmouseover","");
}	
function bukaTgl2(){
	  $( ".hasDatepicker").datepicker({dateFormat:formatTgl});
	  $( ".datepicker").datepicker({dateFormat:formatTgl});
}	
//validasi upload belum dipakai
/*
function validasiUploadImage(){
	$('input[type=file]').fileValidator({
	  onValidation: function(files){      $(this).attr('class','');          },
	  onInvalid:    function(type, file){ $(this).addClass('invalid '+type); $(this).val(null); },
	  maxSize:      '1m'
	  type:        'image'
	});
}
*/

//ajaxsubmit

/*
function (progress) {
                    var total = Math.round((progress.loaded / progress.total) * 100);
                    $("#progress-bar-" + i).css({"width": total + "%"});
                    $("#progress-title-" + i).text(file.name + ' - ' + total + "%");
                }
				*/
				
function secondToHms(seconds) {
	var hh = Math.floor(seconds / 3600);
	var mm = Math.floor(seconds / 60) % 60;
	var ss = Math.floor(seconds) % 60;
	var hms=hh+":"+mm+":"+ss;
	return hms;
}
		//mss = ms % 1000;
var hasilAjax="";

function ajaxSubmitAllForm(idform,ttarget,nmvalidasi,fungsi,sembunyikanForm,jenishasil){
	if (jenishasil==undefined) jenishasil="html";
	if (imgWait==null) imgWait="Tunggu....";
	if (sembunyikanForm==undefined) sembunyikanForm=1;
	if (ttarget=="") ttarget="maincontent";
	//tprogress
	var tprogress="tpg"+idform;
	var tcek="tcek"+idform;
	var timeStarted=new Date();
	/*
	var timecontroller = setInterval(function(){
    timeElapsed = (new Date()) - timeStarted; // Assuming that timeStarted is a Date Object
    uploadSpeed = uploadedBytes / (timeElapsed/1000); // Upload speed in second

    // `callback` is the function that shows the time to user.
    // The only argument is the number of remaining seconds. 
    callback((totalBytes - uploadedBytes) / uploadSpeed); 
}, 1000)
*/
	
	nmForm="#"+idform;
	mp1="#"+ttarget;
	mr=Math.floor((Math.random()*123423));
	try { CKUpdate(); } catch(e) {}

	//sebelum submit
	//cek validasi menggunakan jqvalidator
	strerr="";
	ai=$("#"+idform+" input");
	$(ai.each(function(){
		o="#"+this.name+"-err";
		try {
			ob=$(o);
			a=$(o).text();
			if (a===undefined) { } else strerr+=a;
		} catch(e) {}
		
	}));
	
	if (strerr!='') {
		return false;
	} 
	
	if (!$('#'+tprogress).length) {
		ht1="<div class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%;' id='"+tprogress+"'><Span class ='sr-only'> 0% complete </ span></div>";
		ht1+="<div id='"+tcek+"' style='display:none'></div>";
		$(mp1).append(ht1);		
	}
	
	if ((nmvalidasi==undefined) || (nmvalidasi=='')) nmvalidasi='-';	
	$.post("validasi-local.php?contentOnly=1&useJS=2&form="+nmvalidasi, $("#" +idform).serialize() ).done(function( data ) {
		if (data.length>10) {
			w=(wMax<=500?wMax-5:500);
			zIndexDialog++;
			$(nmForm).show();
			$("#"+tcek).html(data);	
			$("#"+tcek).dialog({width:w});
			return false;
		} else {			
			if (jenishasil=='html') {
				$("#"+ttarget).html(imgWait);	
			}
			if (sembunyikanForm>=1) $(nmForm).hide();
			
			//$("#"+ttarget).css('display','block');
			
			$(nmForm).ajaxSubmit({
				target: mp1,
				beforeSubmit:function(){
					
				},
				success: function(h){
					hasilForm=h;
					$('#'+tprogress).remove();
					if ((fungsi!=undefined) && (fungsi!='')) {
						fungsi=fungsi.replaceAll("{data}",h);
						try{ eval(fungsi); } catch(e){}//setTimeout(fungsi,1);
					}
					if (sembunyikanForm==2) setTimeout("$("+nmForm+").fadeIn(1000);",2000);

				},
				
				/*
				uploadProgress: function(event, position, total, percentComplete) {
					console.log('percentComplete : ' + percentComplete);
					console.log('position: ' + position);
					console.log('total:' + total);
					var mbPosition = position / 1024 / 1024;
					var mbTotal = total / 1024 / 1024;
					var txtProcess =  Math.floor (100 * percentComplete) + '% | ' +  Math.floor (100 * mbPosition) + 'Mb/' +  Math.floor (100 * mbTotal) + 'Mb';
					var pVel = Math.floor (100 * percentComplete) + '%';
					$(mp1+" .process-status").html(txtProcess);

					//$(mp1+" .pr-bar").css('width', pVel);

				},

                    / * complete call back * /
                    complete: function(xhr) {
                        if (xhr.statusText == "OK") {
                            $('#upload-photo-process').modal('hide');
                            $('#upload-done').modal('show');
                        }

                    }
                    /*success: function(data_from_photo_upload) {

                    }*/

				xhr: function(){
					var xhr = $.ajaxSettings.xhr();
					if (xhr.upload) {
						xhr.upload.addEventListener("progress" , function(evt){
							try {
								var loaded = evt.loaded;
								var tot = evt.total;
								var per = Math.floor (100 * loaded / tot);
								var son =  document.getElementById(tprogress);//'progressBar'
								son.innerHTML = per+"%";
								son.style.width=per+"%";
							} catch(e) { console.log(e)};
							/*
							var timecontroller = setInterval(function(){
								timeElapsed = (new Date()) - timeStarted; // Assuming that timeStarted is a Date Object
								uploadSpeed = uploadedBytes / (timeElapsed/1000); // Upload speed in second

								// `callback` is the function that shows the time to user.
								// The only argument is the number of remaining seconds. 
								callback((totalBytes - uploadedBytes) / uploadSpeed); 
							}, 1000);
							try {
								timeElapsed = (new Date()) - timeStarted;
								uploadSpeed = uploaded / (timeElapsed/1000);  
								
								seconds = loaded* uploadSpeed;
								seconds2 = tot*uploadSpeed;
								var te=secondToHms(seconds);
								var hms=secondToHms(seconds2);					
								$(son).attr('title','Time Elapsed:'+te+' ,Time Est : '+hms);
							} catch(e) {}
							
							*/							
							
						}, false);
						return xhr;
					}
				},
				error: function(xhr, status, error){
					hasilAjax=xhr;
					$(mp1).html("");
					$('#'+tprogress).remove();
					alert(xhr.status);
				}
			}); //akhir proses simpan
		}
	});//fungsi pos
	return false;
}
/*
https://www.programmersought.com/article/99631999474/

<div class="progress" id="progressHide">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60"
		aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="tprogress">
			<Span class = "sr-only"> 40% complete </ span>
	</div>
</div>
 // This is a form form
<form class="form-horizontal" id="uploadTaskForm" action="uploadTask" enctype="multipart/form-data" method="post">
 
    <input type="file" name="taskFile"  class="form-control"  id="taskFile"/>
 
         <Button type = "button" class = "btn btn-default" onclick = "saveTask ()"> upload </ button>
 
</form>
*/

function removeTags(input, allowed) {
	allowed = (((allowed || '') + '')
    .toLowerCase()
    .match(/<[a-z][a-z0-9]*>/g) || [])
    .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return input.replace(commentsAndPhpTags, '')
    .replace(tags, function($0, $1) {
      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
    });
}
 //encode============================================================================================
var Base64 = {
   // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    // public method for encoding
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
    // public method for decoding
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        if (i != input.length) {
			messages.addMessage(BASE64_BROKEN);
			throw "error";
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            };
        };
        return utftext;
    },
    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while ( i < utftext.length ) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
};
//======cookies
/*
// if cookie enabled
if (navigator.cookieEnabled) {
   var tst = new Array(  );
   tst[0] = "hello"; tst[1]="there";
   setCookie("doc",document);
   alert(document.cookie);
   var sum = readCookie("sum");
   var iSum = 0;
   if (sum) {
      iSum = parseInt(sum) + 1;
      alert(iSum);
      if (iSum > 10) {
         eraseCookie("sum");
      } else {
         setCookie("sum",iSum);
      }
   } else {
      setCookie("sum", 0);
   }
}
*/
// set cookie expiration date in year 2010
function setCookie(key,value) {
   var dtNow=dtExp=new Date();
	dtExp.setHours(dtNow.getHours()+1*10); //exp 10 jam
   document.cookie=key + "=" + escape(value) + "; expires=" + dtExp.toGMTString(  ) + "; path=/";
}
// each cookie separated by semicolon;
function readCookie(key) {
  var cookie = document.cookie;
  var first = cookie.indexOf(key+"=");
  // cookie exists
  if (first >= 0) {
    var str = cookie.substring(first,cookie.length);
    var last = str.indexOf(";");
    // if last cookie
    if (last < 0) last = str.length;
    // get cookie value
    str = str.substring(0,last).split("=");
    return unescape(str[1]);
  } else {
    return null;
  }
}
// set cookie date to the past to erase
function eraseCookie (key) {
   var cookieDate = new Date();Date(2000,11,10,19,30,30);
   document.cookie=key + "= ; expires="+cookieDate.toGMTString(  )+"; path=/";
}

function potong(txt,w){
	r=txt;
	if (txt.length<=w)  
		return txt;
	else
		return txt.substr(0,w-3)+'...';  
	
	
}
//alert('mycookie');
function cekUpload(fld,jfile,idx){
	try {
		ukuran=$("#"+fld)[0].files[0].size;
		tipe=$("#"+fld)[0].files[0].type;
		nama=$("#"+fld)[0].files[0].name;
		
		ket=ukuran+","+tipe;
		$("#x"+fld).val(ket);
		$("#c"+fld).html(potong(nama,45));
		//preview
		if  (jfile=='image') {
			tempat='p'+tempat;
			previewImage('p'+tempat,tempat) ;
		}
	} catch(e) {}
}


function objExist(id) {
	return $("#"+id).length;
}
function cekUpload2(fld,jfile,rnd,multiple){
	xfld=fld+'_'+rnd+"_"+multiple;//id input
	yfld=fld+"_"+rnd;
	
	pr=maxsize0=$("#"+xfld).attr('maxsize');
	if (maxsize0==undefined) {
		pr=maxsize0=0;
	} else {
		pr=pr.replace("gb","*1024*1024*1024");
		pr=pr.replace("mb","*1024*1024");
		pr=pr.replace("kb","*1024");
	}
	eval("maxsize="+pr+";");				
	
	//try {
		$("#"+xfld)[0].files;
		
		jf=$("#"+xfld)[0].files.length;
		
		ukuran=0;
		tipe="";
		nama="";
		invalid=false;
		for (i=0;i<jf;i++) {
			uk=$("#"+xfld)[0].files[i].size;
			if ((uk>maxsize) && (maxsize>0)) {
				invalid=true;
			} 
			if (i>0) {
				ukuran+="|";
				tipe+="|";
				nama+="|";
			}
			ukuran+=$("#"+xfld)[0].files[i].size*1;
			tipe+=$("#"+xfld)[0].files[i].type;
			nama+=$("#"+xfld)[0].files[i].name;
		}
		
		if (invalid)  {
			$("#"+xfld).val(null);
			def=$("#c"+xfld).attr('def');
			$("#c"+xfld).html(def);
			
			alert("Ukuran file terlalu besar, max:"+maxsize0);
			return false;
		} 
		if (jf>1) {
			xnama=jf+" berkas dipilih";
		} else {
			xnama=nama;
		}
		/*
		ukuran=$("#"+xfld)[0].files[0].size;
		tipe=$("#"+xfld)[0].files[0].type;
		nama=$("#"+xfld)[0].files[0].name;
		*/
		ket=ukuran+","+tipe;
		$("#x"+xfld).val(ket);
		$("#c"+xfld).html(potong(xnama,45));
		//preview
		if  (jfile=='image') {
			tprev='tpreview_'+yfld;
			//previewImage('p'+tempat,fld) ;
		}
		//jika idx>0 allow multiple
		idx=multiple*1;
		if (idx>0) {
			idx2=idx+1;
			//jika dah ada gak perlu add lagi;
			fld2=xfld+'_'+rnd+"_"+idx2;
			ada=$("#"+fld2).length;
			if (!ada) { 
				sample=$("#tsample_"+yfld).html();
				addisi=sample.replaceAll("#idx#",idx2);
				$("#tupl_"+yfld).append(addisi);
			}
		}
		
		
	//} catch(e) {}
}
function hitungHari(date1,date2) {
	  // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24
    // Convert both dates to milliseconds
    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()
    // Calculate the difference in milliseconds
    var difference_ms = Math.abs(date1_ms - date2_ms)
    // Convert back to days and return
    return Math.round(difference_ms/ONE_DAY);
}
//penomoran
Number.prototype.formatMoney = function(c, d, t){
var n = this, c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 //mengganti semua string
String.prototype.replaceAll = function(str1, str2, ignore) 
{
    return this.replace(new RegExp(str1.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),(ignore?"gi":"g")),(typeof(str2)=="string")?str2.replace(/\$/g,"$$$$"):str2);
} 
//alert('mynum');
function removeAmp(str) {
	//function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = str;
	var newstr=textArea.value;
	newstr=newstr.replaceAll("&amp;","&");
	//newstr=newstr.replaceAll(" ","");
    return newstr;
	//newstr.replaceAll(" ","");
	//}
	//return str.replaceAll("&amp;","&");
}
function tglRealtime(id) {
	if (id==undefined) id="rttgl";
	sekarang = new Date;
	year = sekarang.getFullYear();
	month = sekarang.getMonth();
	months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
	d = sekarang.getDate();
	day = sekarang.getDay();
	days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	h = sekarang.getHours();
	if(h<10) { h = "0"+h; }
	m = sekarang.getMinutes();
	if(m<10) { m = "0"+m; }
	s = sekarang.getSeconds();
	if(s<10) { s = "0"+s; }
	result = ''+days[day]+' '+months[month]+' '+d+' '+year+' '+h+':'+m+':'+s;
	document.getElementById(id).innerHTML = result;
	setTimeout('tglRealtime("'+id+'");','1000');
	return true;
}
//tglRealtime('tgl2');
function inArray(arr,obj) {
    for(var i=0; i<arr.length; i++) {
        if (arr[i] == obj) return true;
    }
	return false;
}

function genRnd(basernd) {
	if (basernd==undefined) basernd=9187;
	return Math.round(Math.random()*basernd,0);
	
}
//browse data
//function changeTxtBrowse(tempat,tabelcari,fielddiambil){
function changeTxtBrowse(idtxt){
	tempat="t"+idtxt;
	rnd=genRnd(12341);
	tbrowse="tbrowse"+rnd;
	s="<span id='t2"+idtxt+"' style='margin-left:10px '></span><span id='"+tbrowse+"' style='display:none'></span>";
	$("#"+tempat).html("<input type=button value='...' onclick=\"browseData('"+tbrowse+"','"+idtxt+"');\">"+s);
	$("#"+idtxt).css("display","none");
}
function browseData(tbrowse,idtxt){	
	url="browse.php?op=browse&tbrowse="+tbrowse+"&idtxt="+idtxt+"";
	bukaAjaxD(tbrowse,url,'width:1000');
}
function aksiBrowseData(tbrowse,idtxt,hasil1,hasil2){	
	$("#"+tbrowse).html('');
	$("#"+tbrowse).dialog('close');
	$("#"+idtxt).val(hasil1);
//	alert(idtxt+' > '+$("#"+idtxt).val());
	$("#t2"+idtxt).html(hasil1+" - "+hasil2);
}

function getValueRadio(name){
	var hasil="";
	var allTags = document.getElementsByName(name);
	for (var i = 0, len = allTags.length; i < len; i++) {
		nama=allTags[i].id;
		ck=document.getElementById(nama).checked;
		nilai=document.getElementById(nama).value;
		if (ck) { hasil=nilai;}
	}	 
	tname=name.substring(2,name.length);
	document.getElementById(tname).value=hasil;
	
	return '';
}
function getValueCheckbox(name){
	var t="";
	var allTags = document.getElementsByName(name);
	for (var i = 0, len = allTags.length; i < len; i++) {
		nama=allTags[i].id;
		ck=document.getElementById(nama).checked;
		nilai=document.getElementById(nama).value;
		if (ck) { 
			t=t+(t==''?'':'|')+nilai;
		}
	}	
	tname=name.substring(2,name.length); 
	document.getElementById(tname).value=t;
	return t;
}

function getTextFromCombo(name,val) {
	r='';
		len=document.getElementById(name).length;
		for (var i = 0; i < len; i++) {
			if (val==document.getElementById(name).options[i].value ) {
				r=document.getElementById(name).options[i].text;	
				return r; 	
			}
		}
	return r;
}
function getValueSelect(name,add){
	if (add==undefined) add=false;
	var t="";
	t=document.getElementById(name).value;
	tname=name.substring(2,name.length); 
	tdaf="D_"+tname;
	if (add) {
		tlama=document.getElementById(tname).value;
		v=tlama+(tlama==''?'':'|')+t;
		document.getElementById(tname).value=v;
		//memasukan di daftar 
		daf='';
		av=v.split("|");
		for (var i = 0, len = av.length; i < len; i++) {
			vv=getTextFromCombo(name,av[i]);
			namali="li_"+tname+"_"+i;
			daf+='<li class=tdafselect id='+namali+' >'+vv+'</li>';
		}
		document.getElementById(tdaf).innerHTML="<ul>"+daf+"</ul>";
	} else
		document.getElementById(tname).value=t;
	return t;
}
function ucwords(str) {	
	str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
		return letter.toUpperCase();
	});
	return str;
}
function removeTagsOnly(tag) {
	var b = document.getElementsByTagName(tag);
	while(b.length) {
		var parent = b[ 0 ].parentNode;
		while( b[ 0 ].firstChild ) {
			parent.insertBefore(  b[ 0 ].firstChild, b[ 0 ] );
		}
		 parent.removeChild( b[ 0 ] );
	}
}
function refreshCombo(rnd) {
	ntkn=$("#tcab_"+rnd).html();
	try { $("#tbaa_"+rnd).dialog("close"); } catch(e){}
	//alert(hasilForm);
	url="content1.php?det=ganticombo&tkn="+ntkn+"&defcombo="+hasilForm;
	//alert(url);
	bukaAjax("tcaa_"+rnd,url);
}

function printDiv(elem,orient,closewindow) { 
	var myprint = window.open('', 'PRINT', 'height=400,width=600');
	myprint.document.write('<html><head><title>' + document.title  + '</title>');
	myprint.document.write("<link rel='stylesheet' type='text/css' href='"+js_path+"/style-cetak.css'></link>");
	//if (orient!=undefined) myprint.document.write("<link rel='stylesheet' type='text/css' href='"+js_path+"/style-cetak-landscape.css'></link>");
	myprint.document.write('</head><body >');
	$(".noprint").hide();
	myprint.document.write(document.getElementById(elem).innerHTML);
	myprint.document.write('</body></html>');
	//alert(browserName);
	if (browserName=='Chrome') {
		myprint.document.write('<script>window.print();</script>');
	} else {
		myprint.document.close(); // necessary for IE >= 10
		myprint.focus(); // necessary for IE >= 10*/
		myprint.print();
		if ((closewindow==undefined)||(closewindow==1)) {
			//myprint.close();
		}
	}
//	$(".noprint").show();
	return false;
}
 

function pdfDiv(elem,nfpdf,xformat,xorientasi) { 
	if (xformat==undefined) xformat='letter';
	if (xorientasi==undefined) xorientasi='portrait';
	var element = document.getElementById(elem);
	html2pdf(element, {
	  margin:       1,
	  filename:     nfpdf,
	  image:        { type: 'jpeg', quality: 0.98 },
	  html2canvas:  { dpi: 192, letterRendering: true },
	  jsPDF:        { unit: 'in', format:xformat, orientation: xorientasi }
	});
}

function xlsDiv(elem,nfxls){
	html=$('#'+elem).html();	
    html=html.replace(/<link[^>]*>.*<\/link>/gm, '');
	 file1 = new Blob([html], {type:"application/vnd.ms-excel"});
	try { 
		 url = URL.createObjectURL(file1);
	}
	catch(e) {
		 url = URL.createObjectURL("");
		
	}
	 a = $("<a />", {
	href: url,
	download: nfxls}).appendTo("body").get(0).click();
	//e.preventDefault();

}
function div2png(elem){
	html2canvas(document.getElementById(elem), {
    onrendered: function(canvas) {
		//document.body.appendChild(canvas);
		window.open(canvas.toDataURL("image/png"));
    }
	});
}
function div2pdf(elem,nfpdf,xformat,xorientasi) {
	return pdfDiv(elem,nfpdf,xformat,xorientasi);
}
function pdfDiv2(elem,nfpdf) { 
	if (nfpdf==undefined) nfpdf='file.pdf';
	var data = $('#'+elem).html();
	var docDefinition = {
		content: data
	};
	pdfMake.createPdf(docDefinition).download(nfpdf);
	return false;
}

function reBindStdClass() {
	try {$('.tgl,.D').datepicker({dateFormat:formatTgl});} catch(e) {}	
	try {
		//$('.dr,.DR').daterangepicker({ timePicker: false,  format:formatTgl  });
		$('.dtr,.DTR').daterangepicker({ timePicker: true, timePickerIncrement: 30, format:formatTgl+' h:mm A' });
		$('.dr,.DR').daterangepicker( );
		
		//spinner
		$('.spinner').spinner();
		$('.ui-spinner-button').click(function() { $(this).siblings('input').keyup(); });

		$('.dr2,.DR2').daterangepicker({
			ranges   : {
				  'Today'       : [moment(), moment()],
				  'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				  'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
				  'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				  'This Month'  : [moment().startOf('month'), moment().endOf('month')],
				  'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				},
				startDate: moment().subtract(29, 'days'),
				endDate  : moment()
			  },
			  function (start, end) {
				$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
			  }
		);
	} catch(e) {};
	
	maskAllMoney();
	try {$('.image-link').magnificPopup({type:'image'});} catch(e){};
	

}
//hubungan dengan edit
function awalEdit(rnd){
	reBindStdClass(rnd) 
	try {	
		h=$("#tfbe"+rnd).html();
		if (h===undefined) {
			
		} else {
			h=removeAmp(h);
			if (h!='') {
				eval(h);
			}
		}
		//$("#content").css("height","auto");
	} catch(e) {
		//console.log("e:"+h);
	}
	
		//$.getScript(tppath+"dist/js/adminlte.js");
}

function selesaiEdit(rnd){
	try {	
		
		h=$("#tfae"+rnd).html();
		h=removeAmp(h);
		if (h!='') {
			eval(h);
		}
	} catch(e) {}
}
function selesaiHapus(rnd){
	try {	
		h=$("#tfae"+rnd).html();
		h=removeAmp(h);
		if (h.indexOf("-ok-")>=0) {
			s=$("#idt"+rnd).html();
			alert(s);
			eval(s);
		} else {
			alert(h);
		}
	} catch(e) {}
}
function aktifkanDatePicker(){
	$(".dtpicker").datepicker({dateFormat:formatTgl});
}

function bukaFBayar(tempat,url,opt,func){
	if (wMax<=520) {
		opt="width:wMax";
		url+="&media=hp";
	}
	bukaAjaxD(tempat,url,opt,func);

}
function getValueArrayChecboxByName(name) {
	var a="";
	$('input[name='+name+']:checkbox:checked').each(function(){
		if (a!='') a+=",";
		a+=($(this).val());	
	});
	return a.trim();
}
function suggestFld(det,sfld,rnd,val) {
	sfld+="|"+sfld;
	afld=sfld.split("|");
	fld=afld[0];
	fldcari=afld[1];
	tinput=fld+"_"+rnd;
	var nmsug='#suggestions_'+fld+'_'+rnd;
	var nmsugC='#suggestionsClose_'+fld+'_'+rnd;
	var nminp='#'+fld+'_'+rnd;
	if(val.length == 0) {
		// Hide the suggestion box.
		$('#suggestions_'+fld+'_'+rnd).hide();
	} else {
		url="content1.php?&useJS=2&det="+det+"&newrnd="+rnd+"&op=suggestfld&fldinput="+fld+"&fldcari="+fldcari+"&def="+val;
		$.post(url,{
			coba: ""+val+"",
			ncoba:rnd 
			}, 
			function(data){
			if(data.length >0) {
				$(nmsug).show();
				$('#autoSuggestionsList_'+fld+'_'+rnd).html(data);
				w=$(nminp).css("width");
				tg=$(nminp).css("height");
				$(nmsug).css("position","absolute");
				$(nmsug).css("width",w);
				$(nmsug).position({
					my:        "left top",
					at:        "left bottom",
					of:        $(nminp),  
					collision: "fit"
				});
				//tombol close
				$(nmsugC).position({
					my:        "right top",
					at:        "right bottom",
					of:        $(nminp),  
					collision: "fit"
				});
			}
			else {
				$(nmsug).hide();
			}
		});
	}
} // 
function isiSuggestFld(fld,val,rnd) {
	tinput=fld+"_"+rnd;
	//isiSuggest('$tinput','$hasil',$rnd);
	$('#'+tinput).val(val);
	setTimeout("$('#suggestions_"+fld+'_'+rnd+"').hide();", 200); 
}
function isiSuggest(tinput,val,rnd) {
	//isiSuggest('$tinput','$hasil',$rnd);
	$('#'+tinput).val(val);
	setTimeout("$('#suggestions_"+rnd+"').hide();", 200);
	//$('#inputString').val(thisValue);setTimeout("$('#suggestions'+rnd).hide();", 200);
}

function cekTimeoutSesi() {
	if (timeoutSesi>0) {
		
		tos=timeoutSesi*60*1000;
		urlTimeout="index.php?page=cekTimeOut&det=cekTimeOut&useJS=2&contentOnly=1";
		htout= bukaAjax("",urlTimeout,3,"cekTMOut=setTimeout('cekTimeoutSesi()',"+tos+");") ;
		//alert("htime:"+htout);
	}
}
//cek timeout
if (timeoutSesi>0) {
	//$(document).ready(function(){ cekTimeoutSesi(); });
}

//fullscreen
function GoInFullscreen(element) {
	if(element.requestFullscreen)
		element.requestFullscreen();
	else if(element.mozRequestFullScreen)
		element.mozRequestFullScreen();
	else if(element.webkitRequestFullscreen)
		element.webkitRequestFullscreen();
	else if(element.msRequestFullscreen)
		element.msRequestFullscreen();
}

/* Get out of full screen */
function GoOutFullscreen() {
	if(document.exitFullscreen)
		document.exitFullscreen();
	else if(document.mozCancelFullScreen)
		document.mozCancelFullScreen();
	else if(document.webkitExitFullscreen)
		document.webkitExitFullscreen();
	else if(document.msExitFullscreen)
		document.msExitFullscreen();
}

/* Is currently in full screen or not */
function IsFullScreenCurrently() {
	var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
	
	// If no element is in full-screen
	if(full_screen_element === null)
		return false;
	else
		return true;
}

/*
$("#go-button").on('click', function() {
	if(IsFullScreenCurrently())
		GoOutFullscreen();
	else
		GoInFullscreen($("#element").get(0));
});
*/

$(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
	if(IsFullScreenCurrently()) {
		//$("#element span").text('Full Screen Mode Enabled');
		//$("#go-button").text('Disable Full Screen');
	}
	else {
		//$("#element span").text('Full Screen Mode Disabled');
		//$("#go-button").text('Enable Full Screen');
	}
});


/*
<img id="image-preview" alt="image preview"/>
<input type="file" id="image-source" onchange="previewImage();"/>
#image-preview{
    display:none;
    width : 250px;
    height : 300px;
}
*/	
function previewImage(tempat,idupload) {
	w=550;h=400;
	wimg=w-30;
    //document.getElementById(tempat).style.display = "block";
    var oFReader = new FileReader();
     oFReader.readAsDataURL(document.getElementById(idupload).files[0]);
 
    oFReader.onload = function(oFREvent) {
	  timg='imgprev'+parseInt(Math.random()*10000);
      document.getElementById(tempat).innerHTML = "<img id='"+timg+"' width="+wimg+" >";
	  document.getElementById(timg).src = oFREvent.target.result;
	  bukaAjaxD(tempat,'','width:'+w+',height:'+h+',title:\'Preview Gambar\'');
    };
  };
 


function maskAllMoney(){	
//thousandSeparator=",";
//decimalSeparator=".";
//useDecimal=1;

	$('.c,.C').maskMoney({prefix:'', allowNegative: true, thousands:thousandSeparator, decimal:decimalSeparator, precision: (useDecimal==0?0:2)});
	$('.c1,.C1').maskMoney({prefix:'', allowNegative: true, thousands:thousandSeparator, decimal:decimalSeparator, precision:1});
	$('.c2,.C2,cx,CX').maskMoney({prefix:'', allowNegative: true, thousands:thousandSeparator, decimal:decimalSeparator, precision:2});
	$('.c3,.C3').maskMoney({prefix:'', allowNegative: true, thousands:thousandSeparator, decimal:decimalSeparator, precision:3});
	$('.c4,.C4').maskMoney({prefix:'', allowNegative: true, thousands:thousandSeparator, decimal:decimalSeparator, precision:4});
	$('.n,.N').maskMoney({prefix:'', allowNegative: true, thousands:thousandSeparator, decimal:decimalSeparator, precision: 0});
	$('.tgl,.D,.d').datepicker({dateFormat:formatTgl});
	  
	
}