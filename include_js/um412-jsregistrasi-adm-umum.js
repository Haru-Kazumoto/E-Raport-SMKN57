//masalah hotel
function cekNama2(nama,rnd ) {
	if (nama=='-') {
		//nama=document.getElementById('rekanan'+rnd).value;
		nama=document.getElementById('rekanan1_'+rnd).value;
	}
	tempat='tpilihnama2_'+rnd;
	jenis='cn';
	ch1=(document.getElementById('ch1').checked?1:0) 
	if (nama.length>=2) {	
		bukaAjax(tempat,js_path+'index.php?page=pilihnama2&newrnd='+rnd+'&nama='+nama+'&ch1='+ch1+'&jenis='+jenis+'&tempat='+tempat);
	} else {
		$("#"+tempat).html("<center><br><br>ketik di nama minimal 2 huruf....</center>");
	}
}


function tampilnamarekanan(rnd){
	rek=document.getElementById('rekanan_'+rnd).value;
	url=um_path+"res-hotel-tampilnamarekan.php?aksiklik=hapus&newrnd="+rnd+"&rekanan="+encodeURI(rek);
	hw=window.innerHeight;
	bukaAjax('tnama2_'+rnd,url); //tnama2
}
	
function tampilnamarekananall(rnd){
	//menampilkan daftar nama
	idhotel=document.getElementById('idhotel_'+rnd).value;
	ttothtl=document.getElementById('tsubtot_hotel').innerHTML;
	rekanan=document.getElementById('rekanan_'+rnd).value;
	if ((idhotel*1==0)){ //|| (ttothtl*1==0)
		alert("Pilih Hotel,Kamar dan tentukan cekin dan cekout terlebih dahulu");
		return false;
	}
	url=um_path+'jregistran.php?idhotel='+idhotel+'&newrnd='+rnd+'&rekanan1='+rekanan;
	//nw=window.open(url,'caridata','height=470,width=370,left=250,top=140');
	//nw.focus();
	hw=window.innerHeight;
	
	ps="$('#tdafnama"+rnd+"').show();$('#tdafnama"+rnd+"').position({ my: 'right top', at: 'right-50 top+50', of:  $('#maincontent'),  collision: 'fit' });";
	
	bukaAjaxD('tdafnama'+rnd,url,'left:1000,width:500,height:'+hw); 
	//bukaAjax('tdafnama'+rnd,url,0,ps); 
	
	return false;
}	

function hapusnamarekanan(id,nama,rnd){
	rek=document.getElementById('rekanan_'+rnd).value;
	rek=rek.replace(id+",",'');
	document.getElementById('rekanan_'+rnd).value=rek;
	ket=document.getElementById('an').value;
	nr=nama+"("+id+"),";
	ket=ket.replace(nr,'');
	document.getElementById('an').value=ket;
	document.getElementById('ket').value=ket;
	$("#trrekanan"+id).html('');
	//tampilnamarekanan(rnd);
}

function tambahnamarekanan(id,nama,rnd){
	document.getElementById('rekanan_'+rnd).value=document.getElementById('rekanan_'+rnd).value+id+",";
	isilama=document.getElementById('an').value;
	hurufakhir=isilama.charAt(isilama.length - 1);
	tambahkoma=(((hurufakhir!=',') && (isilama!=''))?',':'')
	document.getElementById('an').value=isilama+tambahkoma+nama+"("+id+"),";
	$('tnama2_'+rnd).dialog("close");
	tampilnamarekanan(rnd);
}

function tambahnamarekanan_bak(id,nama){
	opener.document.getElementById('rekanan'+rnd).value=opener.document.getElementById('rekanan'+rnd).value+id+",";
	isilama=opener.document.getElementById('an').value;
	hurufakhir=isilama.charAt(isilama.length - 1);
	tambahkoma=(((hurufakhir!=',') && (isilama!=''))?',':'')
	opener.document.getElementById('an').value=isilama+tambahkoma+nama+"("+id+"),";
	//opener.document.getElementById('ket').value=opener.document.getElementById('ket').value+nama+"("+id+"),";
	opener.tampilnamarekanan();
	window.close();
	
	} 

function stf22(jenisx,idr,opr){
	pengenal=jenisx+"_"+idr;
	pengenal=pengenal.replace(" ","_");	
	tglt=encodeURI(document.getElementById("tgl_transfer_"+pengenal).value);
	jlht=encodeURI(document.getElementById("jlh_transfer_"+pengenal).value*1);
	cost=encodeURI(document.getElementById("cost2_"+pengenal).value*1);
	idres=encodeURI(document.getElementById("id_reservasi_"+pengenal).value*1);
	bp="bank_"+pengenal;
	var bank= encodeURI(document.getElementById(bp).options[document.getElementById(bp).selectedIndex].value)
	bp="idsponsor_"+pengenal;
	var idsponsor= encodeURI(document.getElementById(bp).options[document.getElementById(bp).selectedIndex].value);

	url="id_reservasi="+idres;
	url=url+"&tgl_transfer="+tglt; 
	url=url+"&jlh_transfer="+jlht;
	url=url+"&bank="+bank;
	url=url+"&cost="+cost;
	url=url+"&opr="+opr; 
	url=url+"&jenis="+encodeURI(document.getElementById("jenis_"+pengenal).value);
	url=url+"&id_registran="+encodeURI(document.getElementById("id_registran_"+pengenal).value);
	url=url+"&idreg="+encodeURI(document.getElementById("id_registran_"+pengenal).value);
	url=url+"&idsponsor="+idsponsor;
	url=url+"&email="+encodeURI(document.getElementById("email_"+pengenal).value);
	url=url+"&cp="+encodeURI(document.getElementById("cp_"+pengenal).value);
	url=url+"&hp="+encodeURI(document.getElementById("hp_"+pengenal).value);
	url=url+"&ref="+encodeURI(document.getElementById("ref_"+pengenal).value);
	url=url+"&disc="+encodeURI(document.getElementById("disc_"+pengenal).value);
	
	if (((bank=="pilih")||(bank=="")) && (opr=="confirm")) { //||(hp=="")||(cp=="")||(email=="")
			alert ("Please fill completely");
	} else if ((tglt=="") && ((bank!='GL') && (bank!='FOC') &&(bank!='INV') )&& (opr=="confirm") )  { 
		alert ("Please fill tranfer date correctly");
	} else if ((jlht=="0") && ((bank!="FOC")&&(bank!="GL")) && (opr=="confirm")) { 
			alert ("Please fill amount of bank transfer correctly...");
	} else   {
		url=js_path+"index.php?page=stf&useJS=2&"+url;
		if (opr=='del') {
			if (!confirm("Are you sure you wish to delete this item?"))	{
				return;
			} else {
				bukaAjax("tc2_"+pengenal,url);
				return;
			}
		}		
		bukaAjax("tc2_"+pengenal,url);
		return 1;
	}
	//
}

function reregnow(){
	nama=encodeURI(document.getElementById('nama').value);
	neg=encodeURI(document.getElementById('neg').value);
	namasert=encodeURI(document.getElementById('namasert').value);
	hp=encodeURI(document.getElementById('hp').value);
	rrb=encodeURI(document.getElementById('rrb').value);
	rrc=encodeURI(document.getElementById('rrc').value);
	idres=encodeURI(document.getElementById('idres').value);
	idreg=encodeURI(document.getElementById('idreg').value);
	stat=encodeURI(document.getElementById('stat').value);
	ket2=encodeURI(document.getElementById('ket2').value);
	jform=encodeURI(document.getElementById('jform').value);
	bank=document.getElementById('bank').value;
	statx=(document.getElementById('stat').value).toLowerCase();

	cb1=(document.getElementById('cb1').checked?1:0);
	cb2=(document.getElementById('cb2').checked?2:0);
	cb=cb1+cb2;

	rnd	=encodeURI(document.getElementById('rnd').value);
	rrp=encodeURI(document.getElementById('rrp_'+rnd).value);
	
	pes=''; 
	if (jform=='') {//jenis bukan self registration
		if (rrp=='') pes+='\nTempat harus dipilih';
		if (idres=='') pes+='\nID Invaid';
		if (hp=='') pes+='\nNomor telp tidak boleh kosong';
		if (rrb=='') pes+='\nRereg By tidak boleh kosong';	
		//if ((rrb!='ybs') && (rrc=='') ) pes+='\nCompany tidak boleh kosong';
		if ((cb1==0) && (cb2==0) ) pes+='\nPilih peserta atau peinjau';
	}
	
	strConfirm="";
	cau1=encodeURI(document.getElementById('caution1').innerHTML);
	if (cau1!='') strConfirm+='\nPeserta sudah reregistrasi';
	
	if ((statx=='under paid')&&(bank=='GL')) {
		//gl meskipun under paid tetep bisa
	} 	else {
		if (statx=='over paid') {
			strConfirm+="\nPembayaran Over Paid";
		} else {
			if ((stat!='Paid')&&(stat!='Validated')) pes+='\nAda masalah dengan pembayaran';
		}
	}
		//if (ket2=='') pes+='\nPD/PC harus diisi';
	
	if (pes!='')
		alert(pes);
	else {
//		if (needConfirm) {
		if (strConfirm!='') {
			if (!confirm(strConfirm+'\nYakin akan melanjutkan?')) return;
		}
		
		url='index.php?rep=rereg-opr&op=rereg&idres='+idres+'&idreg='+idreg+'&nama='+nama+'&namasert='+namasert+'&hp='+hp+'&rrb='+rrb+'&rrc='+rrc+'&rrp='+rrp+'&ket2='+ket2+'&neg='+neg+'&jform='+jform+'&cb='+cb;
		bukaAjax('trereg',url);
	}
	 	 
}

function certnow(addket){
	if (addket==undefined) addket='';
	nama=encodeURI(document.getElementById('nama').value);
	namasert=encodeURI(document.getElementById('namasert').value);
	hp=encodeURI(document.getElementById('hp').value);
	cb=encodeURI(document.getElementById('cb').value);
	cc=encodeURI(document.getElementById('cc').value);
	idres=encodeURI(document.getElementById('idres').value);
	idreg=encodeURI(document.getElementById('idreg').value);
	rrd=encodeURI(document.getElementById('rrd').value);//rereg date
	certno=encodeURI(document.getElementById('certno').value);//rereg date
	titlewks=encodeURI(document.getElementById('titlewks').value);
	title=encodeURI(document.getElementById('title').value); 
	jform=encodeURI(document.getElementById('jform').value);

	rnd	=encodeURI(document.getElementById('rnd').value);
	rrp=encodeURI(document.getElementById('rrp_'+rnd).value);
	
	pes=''; 
	if (rrd=='') pes+='\nPeserta belum reregistrasi';
	
	if (jform=='') {//jenis bukan self registration
		if (idres=='') pes+='\nID Invaid';
		if (hp=='') pes+='\nNomor telp tidak boleh kosong';
		if (cb=='') pes+='\nCert By tidak boleh kosong';
		if (rrp=='') pes+='\nTempat harus dipilih';
		//if ((cb!='ybs') && (cc=='') ) pes+='\nCompany tidak boleh kosong';
	}
	if (pes!='')
		alert(pes);
	else {
		
		
		url='index.php?rep=rereg-opr&op=cert&idres='+idres+'&idreg='+idreg+'&nama='+nama+'&namasert='+namasert+'&title='+title+'&hp='+hp+'&cb='+cb+'&cc='+cc+'&cpl='+rrp+'&certno='+certno+'&jform='+jform+'&addket='+encodeURI(addket);		
		bukaAjax('trereg',url);
	}
	 	 
}
//cetaknametag
function cetakKartu(jeniscetak){
	if (jeniscetak==undefined) jeniscetak=1;
 	nama=encodeURI(document.getElementById('nama').value);
	namasert=encodeURI(document.getElementById('namasert').value);
	hp=encodeURI(document.getElementById('hp').value);
	rrb=encodeURI(document.getElementById('rrb').value);//reregby
	rrc=encodeURI(document.getElementById('rrc').value);
	idres=encodeURI(document.getElementById('idres').value);
	idreg=encodeURI(document.getElementById('idreg').value);
	//titlewks=encodeURI(document.getElementById('titlewks').value);
	titlewks=document.getElementById('titlewks').value;
	title=encodeURI(document.getElementById('title').value);
	jenis=encodeURI(document.getElementById('jenis').value);
	rrd=encodeURI(document.getElementById('rrd').value);//reregdate
	useBarcode=(document.getElementById('useBarcode').checked?1:0);//reregdate
	//sebagai="";
	sebagai=encodeURI(document.getElementById('sebagai').value);
	reregopr=encodeURI(document.getElementById('reregopr').value);
	neg=encodeURI(document.getElementById('neg').value);
	
	
	pes='';
	if (rrd=='') pes+='\nPeserta belum reregistrasi';
	
	if (pes!='') {
	   alert(pes);
	} else {
		//+'&lib_path='+encodeURI('<?=$toroot.$lib_path?>')
	   nfrereg=(jeniscetak==1?"rereg-card.php":"rereg-card2.php"); 
	   w=openWin(um_path+nfrereg+'?idres='+idres+'&nama='+nama+'&idreg='+idreg+'&title='+title+'&sebagai='+encodeURI(sebagai)+'&titlewks='+titlewks+'&jenis='+jenis+'&useBarcode='+useBarcode+'&neg='+neg, 'kartu', 800, 500, true); 	 
	   w.focus(); 
	}
}

function cetakKartu2(nama,idreg,titlewks,jenis){
	nama=encodeURI(document.getElementById('nama_'+idreg+'').value);
	//+'&lib_path='+encodeURI('<?=$toroot.$lib_path?>')
	openWin(um_path+'rereg-card.php?nama='+nama+'&idreg='+idreg+'&titlewks='+titlewks+'&jenis='+jenis, 'kartu', 800, 500, true); 	 

}

function cetakSertifikat(no){
	title=document.getElementById('title').value;
	/*
	if ((title>'Paket C')&&(no==0)){ 
		alert('jangan... gila dong....,ini kan khusus paket A,B atau C?');
		return false;
	}
	*/
	title=encodeURI(document.getElementById('title').value);
	certno=" "+encodeURI(document.getElementById('certno').value)+" ";
        
	if (no==1){ 
		certno=certno.replace(" 140."," 141.");
	}
 	nama=encodeURI(document.getElementById('nama').value);
	namasert=encodeURI(document.getElementById('namasert').value);
	hp=encodeURI(document.getElementById('hp').value);
	rrb=encodeURI(document.getElementById('rrb').value);
	rrc=encodeURI(document.getElementById('rrc').value);
	idres=encodeURI(document.getElementById('idres').value);
	idreg=encodeURI(document.getElementById('idreg').value);
	titlewks=encodeURI(document.getElementById('titlewks').value);
	title=encodeURI(document.getElementById('title').value); 
	jenis=encodeURI(document.getElementById('jenis').value);
	rrd=encodeURI(document.getElementById('rrd').value);
	cd=encodeURI(document.getElementById('cd').value);
	certas=encodeURI(document.getElementById('certas').value);
	skp=encodeURI(document.getElementById('skp').value);
	pes='';
	if (rrd=='') pes+='\nPeserta belum reregistrasi';
	if (cd=='') pes+='\nKlik tombol simpan sertifikat dahulu....';
	
	if (pes!='') {
	   alert(pes);
	} else {
	   w=openWin(um_path+'rereg-cert.php?idres='+idres+'&nama='+namasert+'&idreg='+idreg+'&title='+title+'&titlewks='+titlewks+'&jenis='+jenis+'&certas='+certas+'&certno='+certno+'&no='+no+'&skp='+skp, 'kartu', 1000, 500, true); 	 
	   w.focus();
	}
}

function gantiCertAs(){
 	if (document.getElementById('rdcertas1').checked) 
		certas='P E S E R T A';
 	else if (document.getElementById('rdcertas2').checked) 
		certas='P E M B I C A R A';
 	else if (document.getElementById('rdcertas3').checked) 
		certas='PEMBICARA PODIUM PRESENTASI';
 	else if (document.getElementById('rdcertas4').checked) 
		certas='PEMBICARA POSTER PRESENTASI';
	else
		certas='';
	document.getElementById('certas').value=certas;
}

//absensi &rereg
var jlhreg=0;
var aidreg=[];
var anamareg=[];
var akotareg=[];
var aresreg=[];

var no_load=1;//load absen
var detik=0;
var detikset=0;
var namarereglama="";

function isiAlamatEmail(tempat,jpeserta,sy){
	
	bukaAjax(tempat,um_path+'mail-isialamatemail.php?jpeserta='+jpeserta+'&sy='+encodeURI(sy),2);
}

function cekNamaRereg2(nama) {
	//alert(detik);
	if (detik<3) return false;
	document.getElementById('tpilihnama2_'+rnd).innerHTML='ketik nama yang ingin dicari....';
   if (nama.length>=3)	bukaAjax('tpilihnama2_'+rnd,'index.php?rep=rereg_pilihnama&nama='+nama);
 }


function cekNamaRereg(nama) {
	if (namarereglama==nama) return false;
	namarereglama=nama;

	detik=0;
	if (detikset==0) {
		detikset=1;
		setInterval("detik++",100);
		}
	document.getElementById('tpilihnama2_'+rnd).innerHTML='ketik nama yang ingin dicari....';
   if (nama.length>=3)	setTimeout("cekNamaRereg2('"+nama+"')",300);
 }


function focusRereg(){
	document.getElementById('tvalid').innerHTML='';
	document.getElementById('trereg').innerHTML='';
	document.getElementById('tbiodata2').innerHTML='';
	document.getElementById('txtcari').value='';
}

function cacheRegistrasi(){
	v=$('#tcache').html();
	eval(v);	
}

function persiapanRereg(){
	cacheRegistrasi();
	//$('#txtcari').focus();
}

function cekRegistrasi() {
	cari=$('#txtcari').val();
	pes="";
	pesRefresh="<br><br>Jika data barusaja diinput, klik	<a href='index.php?page=rereg&header=1&reloadCache=1'>REFRESH</a>";
	pesNew="<br><br>Jika ingin menambah data baru  klik	<a href=# onclick=\"bukaJendela('index.php?page=registrasi');return false\">NEW</a>";
	
	if (cari.length<3) {
		pes="isikan data yang akan dicari (minimal 3 huruf)";
		pes+=pesRefresh;
		pes+=pesNew;
	} else {
		k=0;
		for (x=0;x<jlhreg;x++) {
			nam=anamareg[x].toLowerCase();
			if (nam!='-') {
				if  ((aidreg[x]==cari) || (nam.indexOf(cari.toLowerCase())>=0)){
					k++;
					troe=(k%2==0?'trevenform2':'troddform2');
					onc="onclick=\"bukaDetailReg("+aidreg[x]+") \";";
					pes+="<tr class='"+troe+"' "+onc+">";
					pes+="<td>"+aidreg[x]+"</td>";
					pes+="<td colspan=2 align=center>";
					pes+="<td width=300><b>"+anamareg[x]+"</b></td>";
					pes+="<td>"+akotareg[x]+"</td>";
					pes+="<td>"+aresreg[x]+"</td>";
					pes+="</tr>";
				}
			}
		}
		if (pes=='') {
			pes='Data tidak ditemukan.....';
			pes+=pesRefresh;
			pes+=pesNew;
		} else {
				pes="<table width=500 colspan=0 rowspan=0 border=0 border=1 align=center>"+pes+"</table>";
		}
	}
	$('#tnama2').html(pes);
}

function bukaDetailReg(idreg){
	bukaAjax('tbiodata2',"index.php?rep=rereg_ac&idreg="+idreg);	
	document.getElementById('trereg').innerHTML='';
	needClearRereg=true;
	return false;

};
//------------------------------------------------------------------------------footing

function cacheFooting(){
	v=$('#tcache').html();
	eval(v);	
}

function persiapanFooting(){
		cacheFooting();
		tglRealtime('ttgl2');
		t=window.wMax-290;	
		$("#ttgl2").css("left",t+"px");
		$("#mnuAdmin").hide(100);
		
}

//jenis hasil footing:0:tanpa urutan;1;dengan urutan point
function cekHasilFooting(jh,ds){
		if (jh==undefined) jh=0;
		if (ds==undefined) ds='';
		
		url=js_path+'index.php?page=cekHasilFooting&desk='+ds+'&jhasil='+jh+'&jhasil='+jh;
		//$('thasil').hide(100);
		bukaAjax('thasil2',url,0,"var hs=$('#thasil2').html();$('#thasil').html(hs);");
		//$('thasil').show(100);
}


var mhf=1;//jenis hasil:0:list; 1:list + foto
function monitorHasilFooting(mh,desk){	
	ds="";
	if (mh!=undefined) mhf=mh;
	if (desk!=undefined) ds=desk;
	
	cekHasilFooting(1,ds);
	if (mhf==1) setTimeout("monitorHasilFooting("+mh+",'"+ds+"')",3000);
}

function cekNamaFooting(no_load){
	nama=$('#tload'+no_load).html();
	nama=nama.replaceAll('<br>',' ');
	$('#tnama1').html(nama);
	//cekHasilFooting();
}

function cekFooting(m,desk) {
	no=$('#noreg').val();
	if (no=='test') {

	} else if (no*1=='0') {
		alert ('Isi Nomor Footing ');	
	} else {
		nama=anamaFooting[no*1];
		url=js_path+'index.php?page=cekFooting&no='+no+'&desk='+desk;
		bukaAjax('tload'+no_load,url,0,"cekNamaFooting('"+no_load+"')");	
		
		$('.tloadabsenaktif').attr('class','tloadabsen');
		$('#tload'+no_load).attr('class','tloadabsenaktif');
		
		no_load++;
		//$('#tload'+no_load).attr('class','tloadabsenakan');
		if (no_load==9) no_load=1;
		$('#tload'+no_load).attr('class','tloadabsenaktif');
	}
	$('#noreg').val('');
	
}


function cekDetailKandidatFooting(no,desk) {
	url=js_path+'index.php?page=cekDetailKandidatFooting&no='+no+'&desk='+desk;
	bukaAjaxD('tdetailkandidat'+no,url,'width:1250,height:600,top:10');
	
}
//---------------------------------------------------------------absensi
function persiapanAbsensi(){
		cacheRegistrasi();
		tglRealtime('ttgl2');
		/*
		$('#ttgl2').position({
			my:        "left bottom",
			at:        "left botom",
			of:        $('#maincontent'),  
			collision: "fit"
		});
		*/
		t=window.wMax-290;	
		$("#ttgl2").css("left",t+"px");
		$("#mnuAdmin").hide(100);
		
}

function cekAbsensi() {
	idreg=$('#noreg').val();
	idreg=idreg.replaceAll("*","");
	nmacara=$("#nmacara").val();
	//$('#tacara').html('');
	if ((nmacara=='')|| (nmacara=='-')) {
		alert ('Pilih Acara Terlebih dahulu');
		$('#noreg').val('');
		return false;
	}
	if (idreg=='test') {

	} else if (idreg*1=='0') {
		alert ('Isi nomor Registrasi Terlebih dahulu');	
	} else {
		nmruang=$("#ttempat").html(); 
		nmacara=$("#tacara").html(); 
		nama=anamareg[idreg*1];
		url=js_path+'index.php?page=cekAbsensi&idreg='+idreg+'&nmacara='+nmacara+'&nmruang='+nmruang;
		//alert(url);
		bukaAjax('tload'+no_load,url,0,"nama=$('#tload"+no_load+"').html();nama=nama.replaceAll('<br>',' ');$('#tnama1').html(nama);");	
		$('.tloadabsenaktif').attr('class','tloadabsen');
		$('#tload'+no_load).attr('class','tloadabsenaktif');
		no_load++;
		if (no_load==9) no_load=1;
	}
	$('#noreg').val('');
}

function lihatAbsensi() {
alert('a');
}

function pilihTempatAcara(rnd){
	tempat=$("#tempatacara_"+rnd).val();
	bukaAjaxD('tgtempat',js_path+'index.php?page=pilihTempatAcara&tempatacara='+tempat,'width:600,height:500','');
	h=screen.height-70;
	$("#maincontent").css("height",h+"px");
}

function gantiRuangAcara(nmt,nma,tgl,jammulai,jamselesai) {
	$("#ttempat").html(nmt);
	$("#tacara").html(nma);
	$("#tacara").html(nma);
	$("#ttgl1").html(' Date : '+tgl+' Time : '+jammulai+" - "+jamselesai);
	//$("#nmacara").val(nma);
	
	$('#tgtempat').dialog('close');
	$('#tgtempat').css('display','none');
}
//self reregistration
function cekSelfRereg(jform) {
	idreg=$('#noreg').val();
	if (idreg=='test') {
	} else if (idreg*1=='0') {
		alert ('Please Fill your registration number please...');	
	} else {
		nama=anamareg[idreg*1];
		$('#tnama1').html(nama);
		url=js_path+'index.php?page=cekSelfRereg&jform='+jform+'&idreg='+idreg;
		bukaAjax('tloadrereg',url,"");	

		/*
		bukaAjax('tload'+no_load,url,"");	
		$('.tloadabsenaktif').attr('class','tloadabsen');
		$('#tload'+no_load).attr('class','tloadabsenaktif');
		no_load++;
		if (no_load==9) no_load=1;
		*/
}
	$('#noreg').val('');
}