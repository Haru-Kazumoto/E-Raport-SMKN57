

function closeFlash() {
	$(".flash").hide(500);
	setTimeout('closeFlash()',2000);
}
jQuery(function($) {
	closeFlash();
});

function cekValidasiForm(frm,idfrm,rnd) {
hasil=true;
pes="";
if (frm=='raport') {
	kelas=$("kode_kelas_"+rnd).val();
	if (kelas=='') pes+="\nKelas harus dipilih";
}
if (pes!='') 
alert(pes);
else $('#'+idfrm).submit();
}

function awalEdit(rnd){
	//alert("tfbe"+rnd);
	try {	
		h=$("#tfbe"+rnd).html();
		if (h!='') {
			eval(h);
		}
		$("#content").css("height","auto");
	} catch(e) {}
}
function selesaiEdit(rnd){
	try {	
		h=$("#tfae"+rnd).html();
		if (h!='') {
			eval(h);
		}
	} catch(e) {}
}

function selesaiHapus(rnd){
	try {	
		h=$("#tfae"+rnd).html();
		if (h.indexOf("-ok-")>=0) {
			s=$("#idt"+rnd).html();
			alert(s);
			eval(s);
		} else {
			alert(h);
		}
	} catch(e) {}
}

function tutupTbCas(){
	$('#tbcas').html('');
}

function cekAbsen(){	 
	 kode_kelas=document.getElementById("kode_kelas").value;
	 tgl=document.getElementById("tgl").value;
	 bulan=document.getElementById("bulan").value;
	 tahun=document.getElementById("tahun").value;
	 url="/absen/index1.php?page=absen-inp&aksi=cari&kode_kelas="+kode_kelas+"&tgl="+tgl+"&bulan="+bulan+"&tahun="+tahun;
	  bukaAjax('tabsen',url);
	 //alert(url);
	 return false;
}

function cobaNS(){
	var allTags = document.getElementById('tnilai').getElementsByTagName("input");
	for (var i = 0, len = allTags.length; i < len; i++) {
		nama=allTags[i].id;
		if (nama.indexOf("nilaiparam")>-1) {
			rr=Math.round(Math.random()*4,0);
			if (rr==0) rr=3;
			document.getElementById(nama).value=rr;
		}
	}	
}

function submitNS(aa,bb,cc) {
	var allTags = document.getElementById('tnilai').getElementsByTagName("input");
	valid=true;
	for (var i = 0, len = allTags.length; i < len; i++) {
		nama=allTags[i].id;
		if (nama.indexOf("nilaiparam")>-1) {
			if (allTags[i].value*1==0)  valid=false;
		}
	}
	if (!valid) 
		alert("Pengisian belum lengkap, silahkan cek kembali ...");
	else {
		ajaxSubmitAllForm(aa,bb,cc,'bukaMapMP()');
//		$(nmForm).css('display','block');
	}
	return false;
}
 
 
function bukaMapMP(){
	try {
		$("#tabs1").tabs();
		$("#tabs1").show();
		return false;
	}
	catch(e) { return false; }
}
function hitungNSikap(noitem,io,nilai){
	jlg="jlgparam["+noitem+"]";
	jlgparam=document.getElementById(jlg).value*1;
	npparam=document.getElementById("npparam["+noitem+"]").value*1;//negatif positif
	
	document.getElementById("nilaiparam["+noitem+"]").value=nilai;
	
	jitem=document.getElementById("jitem").value;
	totnilai=0;
	for (x=0;x<jitem;x++) {
		totnilai+=document.getElementById("nilaiparam["+x+"]").value*1;
	}
	document.getElementById("totnilai").value=(totnilai/jitem)*25;

}

 