jumlahrp=0;
function logoutUser(referer) {
	myurl="index.php?page=konfirmasi&aksi=logout&output=js&contentOnly=1";
	if (!confirm("Are you sure to logged out?")) return false;
	$.ajax({
		url: myurl
	}).done(function(data) {
		data=data.trim();
		if (data=="1"){
			location.href=referer;
		} else {
			alert(data);
		}
			
	});
	
}
function awalEdit(rnd){
	try {	
		h=$("#tfbe"+rnd).html();
		if (h!='') {
			eval(h);
		}
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
function selesaiHapus2(rnd){
	try {	
		h=$("#thp"+rnd).html();
		
		if (h.indexOf("Operasi berhasil dilakukan")>=0) {
			trhp=$("#trhp"+rnd).html();
			$("#"+trhp).remove();
			$("#thp"+rnd).remove();
			alert(h);
		} else {
			//alert(h);
		}
	} catch(e) {}
}
jumlahrp=0;
basket="";
profesi="";
function gantiPaket(rnd,fld){

 resetPilRes();
 negara="";
 tgllahir="";
 noagg="";
 tipereg="";
 mu="";
 cat='';
 jregis='';
 
 try { tgllahir=document.getElementById('tgllahir'+'_'+rnd).value; } catch (e) {}
 if ((fld=='tgllahir') && (tgllahir.length!=10)) return ;
 
 profesi=document.getElementById('profesi_'+rnd).value; 
// try { negara=document.getElementById('negara_'+rnd).value; } catch (e) {}
 try { mu=document.getElementById('matauang_'+rnd).value; } catch (e) {}
 try { negara=document.getElementById('negara_'+rnd).value; } catch (e) {}
 try { noagg=document.getElementById('noagg_'+rnd).value; } catch (e) {}
 try { tipereg=document.getElementById('tipereg_'+rnd).value; } catch (e) {}
 try { cat=document.getElementById('cat_'+rnd).value; } catch (e) {}
 try { jregis=document.getElementById('jregis_'+rnd).value; } catch (e) {}
 
 
 url=toroot+'adm/index.php?page=gantipaket&mu='+mu+'&profesi='+encodeURI(profesi)+'&noagg='+encodeURI(noagg)+"&negara="+encodeURI(negara)+'&newrnd='+rnd;
 url+="&tgllahir="+encodeURI(tgllahir);
 url+="&tipereg="+encodeURI(tipereg);
 url+="&cat="+encodeURI(cat);
 url+="&jregis="+encodeURI(jregis);
 
 bukaAjax('tpaket',url,0,'fae');	
 $("#ttotalrp").html("Rp. 0");
 
}
function pilihProfesi(rnd,fld){
	gantiPaket(rnd,fld);
}

function gantiProfesi(rnd,fld){
	try {
		profesi=$("#profesi_"+rnd).val();
		sp=(profesi.indexOf("Spesialis")>=0?true:false);
		ket=(sp?"Dengan Gelar":"Tanpa Gelar");
		$("#ketnama_"+rnd).html(ket);
		ket2=(sp?"":"dr. ");
		$("#ketdr_"+rnd).html(ket2);		
	} catch (e) {
		
	}
	gantiPaket(rnd,fld);
}
function hideSponsor(idtpsponsor,sp) { 
	//document.getElementById(idtpsponsor).style.display=((sp*1)=='0'?'none':'');
	document.getElementById(idtpsponsor+"1").style.display=((sp*1)=='0'?'none':'');
	document.getElementById(idtpsponsor+"2").style.display=((sp*1)=='0'?'none':'');
	//$(".tdetsponsor").style.display=((sp*1)=='0'?'none':'');
}

function hidePendaftar(rnd) { 
	ymd=$("#ymd_"+rnd).val();
	d=(ymd==0?"none":"");
	$("#tymd2_"+rnd).css('display',d);
	
	
}

function cekSponsor(rnd) {
	//cek spother
	sp=$("#sp_company_"+rnd).val();
	if (sp==2) {
		$("#spother_"+rnd).show();
	$("#sp_company_"+rnd).css('margin-top','-7px');
	} else {
		$("#spother_"+rnd).hide();
	$("#sp_company_"+rnd).css('margin-top','0px');
	}
}
function cekSponsorPay(pengenal) {
	//cek spother
	sp=$("#idsponsor"+pengenal).val();
	if (sp==2) {
		$("#spother_"+pengenal).show();
		$("#idsponsor"+pengenal).hide();
	} else {
		$("#spother_"+pengenal).hide();
		$("#idsponsor"+pengenal).show();
	}
}

function pilihJAbstract() {	
	jenis=$("#jenis").val()*1;
	if (jenis==2)	$("#topic").val("");
	ds=(jenis==2?"none":"inherit");
	ds2=(jenis!=2?"none":"inherit");
	$("#ttopic").css("display",ds);
	$("#ttopic2").css("display",ds2);
	
}
function gantiHarga(jenis,pengenal,imd,torp){
	pengenal=pengenal.replace(" ","_");
	syarat1=((jenis!='symposium') && (jenis!='simposium') && (jenis!='workshop')&& (jenis!='social program'));
	//syarat=((jenis!='symposium') && (jenis!='simposium')) ;//amams
 	if ((syarat1)||(imd==0)) return false;
	if (torp==undefined) torp="";
	tglt=encodeURI(document.getElementById("tgl_transfer_"+pengenal).value);	 
	url=toroot+"adm/index.php?page=gantiharga&imd="+imd+"&tgl_transfer="+tglt+"&pengenal="+pengenal+"&torp="+torp;
//	url=url+"&idres="+encodeURI(document.getElementById("id_reservasi_"+pengenal).value);
	url=url+"&idres="+encodeURI(document.getElementById("idres_"+pengenal).value);
	//url=url+"&bank="+encodeURI(document.getElementById("bank_"+pengenal).value);
	url=url+"&jenis="+encodeURI(document.getElementById("jenis_"+pengenal).value);
	bukaAjax("tcost2_"+pengenal,url);		
}
function gantiBankTransfer_old(idtpbank,pengenal,tglSekarang) {
	//sudah diganti di fungsi gantiBankTransferD
	pengenal=pengenal.replace(' ','_');
	bp='bank_'+pengenal;
	var bank= encodeURI(document.getElementById(bp).options[document.getElementById(bp).selectedIndex].value);
	var dp='';
	
	usedisc=document.getElementById('usedisc_'+pengenal).value ;
		
	if ((bank=='FOC')||(bank=='')){ //||(bank=='GL') 
		dp='none';
		document.getElementById('tgl_transfer_'+pengenal).value=tglSekarang;
		document.getElementById('jlh_transfer_'+pengenal).value='0';
		//document.getElementById('disp_'+pengenal).value=document.getElementById('cost_'+pengenal).value;
	} else {
		sisa=document.getElementById('sisa_'+pengenal).value;
		disc=document.getElementById('disc_'+pengenal).value;
		if (sisa==0) sisa=document.getElementById('cost2_'+pengenal).value;
		if (usedisc==1) {
		} else document.getElementById('jlh_transfer_'+pengenal).value=sisa-disc*1;
		
	}
	document.getElementById(idtpbank+'1').style.display=dp;
	document.getElementById(idtpbank+'2').style.display=dp;

}

function gantiBankTransferD(pengenal,tglSekarang) {
	pengenal=pengenal.replace(' ','_');
	bp='bank_'+pengenal;
	var usedisc=document.getElementById('usedisc_'+pengenal).value;
	
	var bank= encodeURI(document.getElementById(bp).options[document.getElementById(bp).selectedIndex].value);
	var dp='';
	if ((bank=='FOC')||(bank=='')){ //||(bank=='GL') 
		dp='none';
		document.getElementById('tgl_transfer_'+pengenal).value=tglSekarang;
		document.getElementById('jlh_transfer_'+pengenal).value='0';
		//document.getElementById('disp_'+pengenal).value=document.getElementById('cost_'+pengenal).value;
	} else {
		sisa=document.getElementById('sisa_'+pengenal).value;
		if (usedisc!=1) document.getElementById('jlh_transfer_'+pengenal).value=sisa;
		
	}
//	document.getElementById("tpbank1_"+pengenal).style.display=dp;
//	document.getElementById("tpbank2_"+pengenal).style.display=dp;
	$(".tpbank1_"+pengenal).css('display',dp);
	$(".tpbank2_"+pengenal).css('display',dp);
	
}
		
	function cekJlhHarusBayarD(pengenal){
	v=document.getElementById('jlh_transfer_'+pengenal).value;
	if(v!=0) return false; 
	//document.getElementById('jlh_transfer_'+pengenal).value=document.getElementById('sisa_'+pengenal).value*1;
		sisa=document.getElementById('sisa_'+pengenal).value;
		disc=document.getElementById('disc_'+pengenal).value;
		if (sisa==0) sisa=document.getElementById('cost2_'+pengenal).value;
	 document.getElementById('jlh_transfer_'+pengenal).value=sisa-disc*1;
	}
function gantiPilihan(i){
	document.getElementById("id_dipilih").value=i; 
	}
function cekNama(nama ) {
 	return cekAllNama(nama,'reg');
}
function cekAllNama(nama,jenis) {
	tempat='tnama_'+jenis.replace(" ","_");;
	if (nama.length>=3) {	
		bukaAjax(tempat,'index.php?tobio='+encodeURI(js_path+'')+'&page=pilihnama&contentOnly=1&useJS=2&nama='+nama+'&jenis='+encodeURI(jenis)+'&tempat='+tempat);
	}
   	document.getElementById(tempat).style.display=((document.getElementById(tempat).innerHTML="" )? "none":"");// .innerHTML="";
}
 
function autoFillNamaCert(rnd){
	var a="";
	var b="";
	var c="";
	var isi1 =document.getElementById('namasert').value;
	 if (isi1=="") {
		try { a=document.getElementById('gelardepan_'+rnd).value; } catch (e){a="";}  
		try { c=document.getElementById('gelarbelakang_'+rnd).value; } catch (e){c="";}  
		
		a=a.replaceAll("|",", ");
		b=document.getElementById('nama').value;
		 if (autoChangeCaseNamaReg) { 
		b=ucwords(b);
		document.getElementById('nama').value=b;
	   }  
		namasert=a+(a==""?"":" ")+b+(c==""?"":" ")+c;
		
		document.getElementById('namasert').value=namasert;
	}
}
  
if (isOnline) {
 
	function testIsiReg(){
		document.getElementById('nama').value='Umar '+Math.round(Math.random()*5995959,0);
		document.getElementById('hp').value=Math.round(Math.random()*10000,0);
		document.getElementById('email').value='um412@yahoo.com';
		document.getElementById('kota').value='Yogyakarta';
		};
	
}
function gantiHari2(rnd){	
	hari1=$("#hotel_hari1").val();
	hari2=$("#hotel_hari2").val();
	h1=new Array();
	h2=new Array();
	h1=hari1.split("/");
	h2=hari2.split("/");
	kali=24*60*60*1000;
	if (lang=='id') {
		bbTgl=new Date(h1[2]+"-"+h1[1]+"-"+h1[0])-kali;
		baTgl=new Date(h2[2]+"-"+h2[1]+"-"+h2[0])-0;
	} else {
		bbTgl=new Date(h1[2]+"-"+h1[0]+"-"+h1[1])-kali;
		baTgl=new Date(h2[2]+"-"+h2[0]+"-"+h2[1])-0;
	}
	jlhHari=Math.round((baTgl-bbTgl)/kali,0);
	if (jlhHari<=0) {
		si=$("#hotel_hari1")[0].selectedIndex;
		$("#hotel_hari2")[0].selectedIndex=si;
	}	
	hitungHargaHotel(rnd);
}
function cekKetersediaanKamar(rnd,loadbasket){
	baskethtl="";
	try {
		cek= document.getElementById('thotelcek').innerHTML;
		if (cek.length>5)  {
			document.getElementById('thotelcek').style.display='';
		}
	} catch (e) { return false; }
	
		
	idkamar=document.getElementById('idkamar').value*1;
	hargaKamar=document.getElementById('hrgkamar').value*1;
	
	try {
		mu=document.getElementById('matauang_'+rnd).value;
	} catch(e) {
		mu='IDR'; 
	}
	console.log("idkamar "+idkamar+" hrg"+hargaKamar+" mu "+mu);
	useUSD=(mu=="USD"?1:0);
 	//useUSD=(hargaKamar>10000?1:0);
		
	hargaExtra=document.getElementById('hrgextra').value*1;
	jumkamar=$('#jumkamar_'+rnd).val()*1;
	jumextra=document.getElementById('jumextra').value*1;
	hari1=document.getElementById('hotel_hari1').value;
	hari2=document.getElementById('hotel_hari2').value;
	h1=new Array();
	h2=new Array();
	h1=hari1.split("/");
	h2=hari2.split("/");
		
	//if (lang=='id') {
	if (formatTgl=='dd/mm/yy') {
		bbTgl=new Date(h1[2]+"-"+h1[1]+"-"+h1[0]);
		baTgl=new Date(h2[2]+"-"+h2[1]+"-"+h2[0]);
	} else { // m/d/Y
		bbTgl=new Date(h1[2]+"-"+h1[0]+"-"+h1[1]);
		baTgl=new Date(h2[2]+"-"+h2[0]+"-"+h2[1]);
	}
	
	kali=24*60*60*1000;
	jlhHari=Math.round((baTgl-bbTgl)/kali,0);
	
	if (jlhHari<0) {
		si=document.getElementById('hotel_hari1').selectedIndex;
		document.getElementById('hotel_hari2').selectedIndex=si;
		//hari1=document.getElementById('hotel_hari1').value;
		hari2=document.getElementById('hotel_hari2').value;
	//	baskethtl=cekKetersediaanKamar(rnd);
		//
		$('#submithtl'+rnd).attr("disable","disable");
		return;
	}
	
	$('#submithtl'+rnd).attr("disable","");
	hrg=(hargaKamar*jumkamar+hargaExtra*jumextra);
	
//discHari=1;
	disch=(jlhHari<discHari?jlhHari:discHari);
	
	jumlahDisc=(hrg*disch);
	
	xdisc=(useUSD==0?rupiah(jumlahDisc):"USD "+jumlahDisc+"");
	document.getElementById('dischari_'+rnd).value=disch;
	
	tot=(hrg*(jlhHari-disch));
	xtot=(useUSD==0?rupiah(tot):"USD "+tot+""); 
	//basket
	//nmhotel=document.getElementById('idhotel_'+rnd).text;
	//baskethtl=nmhotel+"-"+nmkamar+","+ttot+",1,"+ttot;
	nmkamar=document.getElementById('nmkamar').value;
	nmhotel=$("#idhotel_"+rnd+" option:selected").text();
	usd2=kurs2;
	if ((tot>0)||(jumlahDisc>0)) {
		title=nmkamar+'-'+nmhotel+" ("+hari1+" sd "+hari2+")";
		ytot=tot;
		if (useUSD==1) {
			title+=" (USD "+tot+")";
			ytot=tot*usd2;
		} 
		jumlahrp+=ytot*1;
		baskethtl=title+","+ytot+",1,"+ytot+","+idkamar+","+tot+",hotel";
		
	} else
		baskethtl='';
	
	
	document.getElementById('ttotdays').innerHTML=jlhHari;
	//potonganhari
	document.getElementById('tdisc_hotel').innerHTML=xdisc;
	
	document.getElementById('ttot_hotel').innerHTML=xtot;
	document.getElementById('tsubtot_hotel').innerHTML=tot;

	//try { hitungHargaTotal(); } catch(e) { }
	//khusus kalau ada byr
	if (isad) { 
		 try { 
			pengenal="hotel_"+(document.getElementById('idreshotel').value*0);
			sisa=document.getElementById('sisa_'+pengenal).value;
			t="Fee: "+ttot+"<input type=hidden name=cost2_"+pengenal+" id=cost2_"+pengenal+" value='"+tot+"'>";
			t+="<input type=hidden name=sisa_"+pengenal+" id=sisa_"+pengenal+" value='"+sisa+"'>";
			document.getElementById('tcost2_'+pengenal).innerHTML=t;
		 } catch(e) { }
	}  
	try {
		$("#tbaskethtl").html(baskethtl);
		if (loadbasket==undefined) tampilkanBasket(rnd,'htl');
	} catch (e) {}
}
function hitungHargaHotel(rnd){
	//try {
		document.getElementById('thotelcek').style.display='none';
		idkamar=document.getElementById('idkamar').value*1;
		hargaKamar=document.getElementById('hrgkamar').value*1;
		hargaExtra=document.getElementById('hrgextra').value*1;
		jumkamar=$('#jumkamar_'+rnd).val()*1;
	 
		jumextra=document.getElementById('jumextra').value*1;
		hari1=document.getElementById('hotel_hari1').value;
		hari2=document.getElementById('hotel_hari2').value;
		url=toroot+'adm/index.php?page=cekkamar&idkamar='+idkamar+"&hari1="+hari1+"&hari2="+hari2+"&jumkamar="+jumkamar;
		
		func='b=cekKetersediaanKamar('+rnd+');';
		//bukaAjax('thotelcek',url,0,func);

		$.ajax({
			url: url
		}).done(function(data) {
			cekKetersediaanKamar(rnd);				
		});
}

function setHargaKamar(idkamar,sethrg,kk,rnd,nmkamar){
	document.getElementById('hrgkamar').value=sethrg;  
	document.getElementById('hrgextra').value=kk;  
	document.getElementById('idkamar').value=idkamar;  
	document.getElementById('nmkamar').value=nmkamar;  
	hitungHargaHotel(rnd);
}
function gantiHotel(idhotel,idkamar,rnd,mu){
	$('#hrgkamar,#hrgextra,#idkamar').val(0);
	
	
	if (mu=='') {
		mu=$("#matauang_"+rnd).val();
	} 
	bukaAjax('tinfohotel',toroot+"adm/index.php?page=infohotel&contentOnly=1&useJS=2&mu="+mu+"&idhotel="+idhotel+"&idkamar="+idkamar+"&newrnd="+rnd,0,"hitungHargaHotel("+rnd+");"); 
}
function showInstitution(){
	d=document.getElementById('institusi').value;
	document.getElementById('tdinstitusi').style.display=(d==''?'none':'');
	}
function showSponsorship(rnd){
	
	d=document.getElementById('sp_company_'+rnd).value;
	document.getElementById('tdsponsor').style.display=(d==''?'none':'');
	}
  
function stf(jenisx,idr,opr,namadia){
	pengenal=jenisx+"_"+idr;
	pengenal=pengenal.replace(" ","_");	
	
	if (opr=='confirm') {
		tglt=encodeURI(document.getElementById("tgl_transfer_"+pengenal).value);
		jlht=encodeURI(document.getElementById("jlh_transfer_"+pengenal).value*1);
		cost=encodeURI(document.getElementById("cost2_"+pengenal).value*1);
		idres=encodeURI(document.getElementById("id_reservasi_"+pengenal).value*1);
		tw="tw"+pengenal;
		bp="bank_"+pengenal;
		var bank= encodeURI(document.getElementById(bp).options[document.getElementById(bp).selectedIndex].value)
		bp="idsponsor_"+pengenal;
		var idsponsor= encodeURI(document.getElementById(bp).options[document.getElementById(bp).selectedIndex].value);
		
		if ((bank=="pilih")||(bank=="-Choose One-")||(bank=="")  ) { //||(hp=="")||(cp=="")||(email=="")
			alert ("Please fill completely");
			return 0;
		} else if ( (tglt=="") && (bank!='GL') && (bank!='FOC') && (bank!='INV') )   { 
			alert ("Please fill tranfer date correctly");
			return 0;
		} else if ((jlht=="0") && (bank!="FOC")&&(bank!="GL")  ) { 
			alert ("Please fill amount of bank transfer correctly...");
			return 0;
		}
	}
	if (opr=='del') {
		if (!confirm("Are you sure you wish to delete this item?"))	return 0;
		document.getElementById("opr_"+pengenal).value="del";	
	}	
	$('#'+tw).html(imgWait);
	nmForm="#form_"+pengenal;
	$(nmForm).hide();
	$(""+nmForm).ajaxSubmit({
		target: "#tc2_"+pengenal,
		success: function(){ 
			if (namadia!=undefined) $('#'+namadia).dialog('close');
			return 1;
		}
	});		
	return 1;
}
//hotel------------------------------------------------------------------------
function ajaxSubmitFormHotel(pengenal){
	nmForm="#form_"+pengenal;
	mp2="#"+pengenal;
	tst="tstatus"+Math.floor((Math.random()*123423))+"";	
	$(mp2).append("<div id="+tst+"></div>")
	$("#"+tst).html('please wait.....');	
	$(nmForm).ajaxSubmit({
		target: mp2,
		success: function(){ 
			//$(mp2).html('Finish....');
		}
	});
}
 
function bukaDialogSTF(nama,jenisx,idr){ 
	$("#"+nama).dialog({
			stackfix: true,
			height: 400, 
			width: 500,
			overlay: {
				backgroundColor: '#000',
				opacity: 0.5
			},
			buttons: {
				'Ok': function() {
					r=stf(jenisx,idr,"confirm",nama);
				},
				Cancel: function() {
					$(this).dialog('close');
				}
			}
	});
//	$("#"+nama).show();
}
 
function gantiTelp(no,rnd) {
	pcd=$('#addtelp'+no+'_'+rnd).val();
	$('#addfax'+no+'_'+rnd).val(pcd);
	$('#addhp'+no+'_'+rnd).val(pcd);
	try {
		$('#addtelp3'+'_'+rnd).val(pcd);
		$('#addfax3'+'_'+rnd).val(pcd);
		$('#addhp3'+'_'+rnd).val(pcd);
	} catch (e) { c=1;}
}
function gantiNegara(no,rnd) {
	negara=encodeURI(document.getElementById('negara'+'_'+rnd).value);
	bukaAjax("addtelp"+no+'_'+rnd,toroot+"adm/index.php?page=getphonecode&negara="+negara,2,"gantiTelp('"+no+"')");							
}
 
function resetPilRes() {
	nilaiterakhir=new Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	namaterakhir=new Array("","","","","","","","","","","","","","","","","","","","","","","","","","","");
	namaRadio="";
}
resetPilRes();

function hitungFP(idp,rnd,useUSD) {
	//family program
	q=$('#q_'+idp+"_"+rnd).val();
	h=$('#hrg_'+idp+"_"+rnd).val();
	subtot=h*q;
	//$('#subtot_'+idp).val(subtot);
	if (useUSD==1)
		$('#subtotx_'+idp).html("USD "+rupiah2(subtot));
	else
		$('#subtotx_'+idp).html("Rp. "+rupiah2(subtot));
	tampilkanBasket(rnd,'-');	
}

function cekRadioPaket(rnd,nopaket,noradio){
	//nopaket,nojenis,harga,noradio,jenis,cat,idp,rnd,neg
	mu=document.getElementById('matauang_'+rnd).value;
	
	useUSD=(mu=="USD"?1:0);
	
	if (nopaket==undefined) {
		jumlah=jumlahrp=0;
		try{
			document.getElementById('ttotalrp').innerHTML=(useUSD==1?"USD ":"IDR ")+jumlahrp;
		} catch(e) {}
		return;
	}
	
	namaradio="radio"+noradio+"["+nopaket+"]";
	vrd=document.getElementById(namaradio).value;
	ard=vrd.split(',');//$nojenis,$harga[$i],$jn,$idp,$cat,$i,$rnd,$neg
	harga=ard[1];
	jenis=ard[2];
	idp=ard[3];
	cat=ard[4];
	tit=document.getElementById("stitle["+nopaket+"]").innerHTML;
				
	//menghilangkan titik jika yang terakhir sama dengan sekarang paketsekarang=namaradio;
	//nilaisekarang=document.getElementById(namaradio).checked;
	
	namasekarang=namaradio;
	//alert(namasekarang+" - "+namaterakhir[nopaket]);
	if (namasekarang==namaterakhir[nopaket]) {
		document.getElementById(namaradio).checked=(!nilaiterakhir[nopaket]);
		//tampilkan sembunyikan tabeldetail family program	
	}	
 
				
	if ((jenis=="pendamping") ||(tit.toLowerCase().indexOf("dinner")>0))  {
	 
		$('#hrg_'+idp+"_"+rnd).val(harga);
		
		hitungFP(idp,rnd,useUSD)
	 	if (document.getElementById(namaradio).checked)
			$('#tdetfp_'+idp).show();
		else
			$('#tdetfp_'+idp).hide();
	}
	
	nilaiterakhir[nopaket]=document.getElementById(namaradio).checked;
	namaterakhir[nopaket]=namaradio;
	//menghilangkan titik jika jenis=symp
	jlhpaket=document.getElementById('jlhpaket').value;
	jlhjenis=document.getElementById('jlhjenis').value;
	for (x=1;x<=jlhpaket;x++) {
			jlhharga=document.getElementById('jlhharga_'+x+'').value;
			for (y=1;y<=jlhharga;y++) { 
				rd="radio"+y+"["+x+"]";
				vrd=document.getElementById(rd).value;
				ardi=vrd.split(",");
				vcat=ardi[4];
				vjenis=ardi[2];
				
				//menghapus check jika chedked dan jenis sympo
				if (document.getElementById(rd).checked){
					if ((namaradio!=rd) && (!document.getElementById(rd).disabled)) {
						//alert(vrd+':'+vjenis+'/'+jenis+'/');
						dc=false;
						if ((vcat==cat) && (cat!='') ) 
							dc=true;
						else if ((namaradio!=rd) && (( (vcat.indexOf(cat)>=0)&& (cat!='')) ||((cat.indexOf(vcat)>=0)&& (vcat!='')) )  ) 
							dc=true;
						else if ((vjenis==jenis) && ((vjenis.toLowerCase()=='symposium')||(vjenis.toLowerCase()=='simposium')) ) 
							dc=true;
		
						if (dc) document.getElementById(rd).checked=false;
					}
				}		
			}
	}
	tampilkanBasket(rnd,'-');			
}

function hitungHargaFix(rnd,ver){
	jlhpaket=document.getElementById('jlhpaket').value;
	jlhjenis=document.getElementById('jlhjenis').value;

	mu=document.getElementById('matauang_'+rnd).value;
	try {
		pmfr=document.getElementById('pmethod1000').checked?1:0;
	} catch(e) { 
		pmfr=0; 
	}
	
	usd=kurs;
	useUSD=(mu=="USD"?1:0);
	b="";
	subtot=new Array(0,0,0,0,0,0);
					
	jumlah=0;
	jumlahUSD=0;
	for (x=1;x<=jlhpaket;x++) {
		jlhharga=document.getElementById('jlhharga_'+x+'').value;
		for (y=1;y<=jlhharga;y++) { 
			rd="radio"+y+"["+x+"]";
			vrd=document.getElementById(rd).value;
			//alert(document.getElementById(rd).checked);
			if (document.getElementById(rd).checked) {
				ard=vrd.split(",");
				idp=ard[3];
				q=$("#q_"+idp+"_"+rnd).val()*1;
				if (pmfr==1) 
					hrg=0;
				else
					hrg=ard[1];
				
				nojenis=ard[0];
				jen=ard[2];
				subtot[nojenis]+=(hrg*q);	
				
				
				//if (hrg<1000) {
				tit=document.getElementById("stitle["+x+"]").innerHTML;
				tit=tit.replaceAll(",","#koma#");//title
				hrgawal=hrg;
				if (useUSD=='1') {
					//useUSD=true;
					//alert(hrg);
					hrg*=usd;
					jumlahUSD+=hrg*1*q;
					if (q==1)
						tit+="(@USD "+hrgawal+")";
					else {
						xq=q;
						if ((jen=="pendamping")||(tit.toLowerCase().indexOf("tour")>0)) xq+=" Persons";
						tit+="("+xq+" @USD "+hrgawal+")";
					}
				}
				
				cc=hrg*q;//*(useUSD=='1'?usd:1);
				
				jumlah+=cc;				
				if (b!='') b+=";";
				//title,hrg,qty,subtot,hrgusd,idpaket
				b+=tit +","+hrg+","+q+","+cc+","+idp+","+hrgawal+","+jen;
			}
		}
	}
	//}	
	
	jumlahrp+=jumlah;//*usd;	//menggunkan coinmill.com
	 jx="";
	 jx+="Rp. "+jumlahrp;
	 jx+=(useUSD=='1'?' (USD '+jumlahUSD+')':'') ;
	 //b=b.replaceAll("#koma#",",");
	$("#tbasketpaket").html(b+' ');
 	return;
}

function tampilkanBasket(rnd,t,ver,page){
	if (ver==undefined) ver=2;
	if (page==undefined) page='reg';
	
	//basket=...
	/*
	if (t=='htl') {
		hitungHargaFix(rnd);
	}else if (t=='paket') {
		cekKetersediaanKamar(rnd);
	}else  {
		hitungHargaFix(rnd);
		cekKetersediaanKamar(rnd);
	}
	*/
	jumlahrp=0;
	if (page=='reg') {
		hitungHargaFix(rnd,ver);
		cekKetersediaanKamar(rnd,0);
	} else {
		//editreg
		jumlahrp=$("#vkk_"+rnd).val();
	}

	if (ver==3) {
		bpaket=$("#tbasketpaket_"+rnd).html();
		bhotel=$("#tbaskethtl_"+rnd).html();
	} else {
		bpaket=$("#tbasketpaket").html();
		bhotel=$("#tbaskethtl").html();
		
	}
	b='';
	if (bpaket!='') b=b+(b!=''?';':'') +bpaket;
	if (bhotel!='') b=b+(b!=''?';':'') +bhotel;
	basket=b;
	//alert("b> "+basket);
	hitungTambahanBasket(rnd,ver);
 	s=konversiBasket(rnd,ver);
	//alert(s);
	if (ver==3) {
		$("#tcartc_"+rnd).html(s);
		$("#tcartc_"+rnd).css("top",screen.height);
		$("#tttotfeex_"+rnd).html(s);
		
	} else {
		$("#tcartc").html(s);
		$("#tcartc").css("top",screen.height);
		$("#tttotfeex").html(s);
	}
}
function hitungTambahanBasket(rnd,ver){
	totawal=jumlahrp;
	//menghitung total+tambahan basket dari pymentmethod;
	 //	alert("jumlahrp di akhir:" +jumlahrp);

	var optpm=$(".optpmethod");
	$.each(optpm,function(idx,value){
		v=this.value;
		pm=(ver==3?"#pmethod_"+rnd+"_"+v:"#pmethod"+v);
		if ($(pm).attr("checked")=="checked") {
			
			if (ver==3) {
				b=$("#taddbasketpayment_"+rnd+"_"+v).html();
				
			} else {
				b=$("#taddbasketpayment"+v).html();
			}
			$("#addbasketpayment_"+rnd).val(b);
			
			//if (ver==3)  alert(" hoho 	"+$("#addbasketpayment_"+rnd).val());
			
			ab=b.split(";");
			$.each(ab,function(idx,value){ 
				av=value.split(",");
				//total awal ditambah fee adm doku digunakan dasar untuk cari prosentase 
				sdet="";
				$.each(av, function( id, vdet ) {
					if ((id==1)||(id==3)){
						if (vdet.indexOf("tot")>0) {
							vdet0=vdet.replace("tot",totawal);
							eval("vdet=Math.round("+vdet0+",0);");
							//alert(vdet0+' > '+vdet);
						} else {
							vdet=Math.round(vdet*1,0);
					
						}
						if (id==1) jumlahrp+=Math.round(vdet,0)*1;
					}
					if (id<4) {
						sdet+=(sdet==""?"":",")+vdet;
					}
				});
				if (sdet!="") basket+=";"+sdet; 
			});
		}
	});		
	//alert(basket);
}
//Dokter Spesialis,4000000,1,4000000;Manajemen Update Infeksi Dalam Kehamilan &amp; Rasionalisasi Penggunaan Antimikroba,4000000,1,4000000;Smart Room-Amaris Pemuda,465000,1,465000
function konversiBasket(rnd,ver){
	b=basket;
	
	mu=document.getElementById('matauang_'+rnd).value;
	b=b.replaceAll("&amp;","&");
	jrpfix=0;	
	//alert(b);
	ab=b.split(";");
	//<td class=tdjudul>Prize</td><td class=tdjudul>Qty</td>
	if (lang=='en')
		s="<table border=1 width=100% class=tcartc><tr><td class='tdjudul col1'>No</td><td  class='tdjudul col2'>Item</td><td class='tdjudul col3'>Sub Total</td></tr>";
	else
		s="<table border=1 width=100% class=tcartc><tr><td class='tdjudul col1'>No</td><td  class='tdjudul col2'>Deskripsi</td><td class='tdjudul col3'>Sub Total</td>	</tr>";
	
	
	br=1;
	$.each(ab, function( index, value ) {		
		if (value.length>3) {
			sdet="<td align='center'>"+br+"</td>";
			av=value.split(",");
			//id : kolom
			$.each(av, function( id, val ) {
				alg="";
				if (id==0) 
					alg=" align=left ";
				else if (id>1) 
					alg=" align=right ";
				if (val=='NaN') val="1";
				valx=val.replaceAll("#koma#",",");
				//title
				//if ((id==0) && (mu=="USD")) 	valx+="(USD "+av[1]+")"
				
						
				if ((id==1) || (id==3)) {
					valx='';
					//if (mu=='USD') 	valx+=rupiah2(val*usd);
				
					valx+=rupiah2(val);
					
					//.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
				}
				
				//prize dan q tidak ditampilkan
				 
				if ((id==0) || (id==3) ) {
					sdet+="<td "+alg+">"+valx+"</td>";
				}
				
				if (id==3) {
					
					jrpfix+=val*1;//*(mu=='USD'?usd:1);sudah dikonversi
					
				}
			});

			s+="<tr>"+sdet+"<tr>";
			
			br++;
		}
	});
	//jrp=(useUSD=='1'?'USD ':'IDR ')+rupiah2(jumlahrp);
	//jrp=(useUSD=='1'?'USD ':'IDR ')+rupiah2(jrpfix);
	jrp='IDR '+rupiah2(jrpfix);
	
	s+="<tr><td class='tdjudul' colspan=2><center>Total</center></td><td class=tdjudul style='text-align:right'>"+jrp+"</td>	</tr>";
	
	s+="</table>";
	s="<input id='mxtot_"+rnd +"' type=hidden value='"+jrpfix+"'>"+s;
		 
	try {
		akhirBasket(rnd,jrpfix);
	} catch(e) {
		
	}
	return s;
}
/*
function cekSubmitReg(idform,ttarget,nmvalidasi,fungsi,sembunyikanForm){
	if (imgWait==null) imgWait="Tunggu....";
	if (sembunyikanForm==undefined) sembunyikanForm=true;
	if (ttarget=="") ttarget="maincontent";
	nmForm="#"+idform;
	mp1="#"+ttarget;
	mr=Math.floor((Math.random()*123423));
	tst="tstatus"+mr+"";
	$(mp1).append("<div id="+tst+" class='pleasewait' ><br>Please Wait.....</div>");
	//$(nmForm).append("<input type=hidden id='useJS"+mr+"' name=useJS value=2>");
	$("#"+tst).html(imgWait);	
	if (sembunyikanForm) $(nmForm).css('display','none');
	$("#"+tst).css('display','block');
	try { CKUpdate(); } catch(e) {}
	
	if ((nmvalidasi!=undefined) && (nmvalidasi!='')) {				
		$.post("validasi-local.php?contentOnly=1&useJS=2&drc="+encodeURI(docroot)+"&form="+nmvalidasi, $("#" +idform).serialize() ).done(function( data ) {
				if (data.length>10) {
					zIndexDialog++;
					$(nmForm).css('display','block');
					$("#"+tst).dialog({width:500}).parent('.ui-dialog').css('zIndex',999999);
					$("#"+tst).html(data);	
					return false;
				} else { //jika valid 
						$(nmForm).attr('onSubmit','');
						
						$(nmForm).submit();
						/*
						$(nmForm).ajaxSubmit({
							target: mp1,
							success: function(){
								if ((fungsi!=undefined) && (fungsi!='')) {
									eval(fungsi);//setTimeout(fungsi,1);
								}
							} //fungsi sukses
						}); 
						* /
					} //valid
			});//fungsi pos
	} //jika kosong
	
	return false;
}
*/

function showInfoBayar2(no,rnd,page){
	//alert(no);
	$(".tinfobayar").css('display','none');
	$("#tinfobayar"+no).css('display','block');
	$("#pmethod"+no).attr('checked','checked');
	os=$('#reg_'+rnd).attr("onsubmit");
	if ((no==1)||(no==1321)||(no==1532)) {
		$('#tsubmit').val('Submit');
		os=os.replace("cekSubmitReg","ajaxSubmitAllForm");
	} else	{
		$('#tsubmit').val((lang=='id'?'Lanjut ke Halaman Pembayaran':'Continue'));
		//os=os.replace("ajaxSubmitAllForm","cekSubmitReg");
	}
	$('#reg_'+rnd).attr("onsubmit",os);
	tampilkanBasket(rnd,'-');
	
	return false;
}

//versi 3
function showInfoBayar3(no,rnd,page){
	ver=3;
	//alert(no);
	iden="_"+rnd+"_"+no;
	$(".tinfobayar").css('display','none');
	$("#tinfobayar"+iden).css('display','block');
	$("#pmethod"+iden).attr('checked','checked');
	os=$('#reg_'+rnd).attr("onsubmit");
	if ((no==1)||(no==1321)||(no==1532)) {
		$('#tsubmit_'+rnd).val('Submit');
		os=os.replace("cekSubmitReg","ajaxSubmitAllForm");
	} else	{
		$('#tsubmit_'+rnd).val((lang=='id'?'Lanjut ke Halaman Pembayaran':'Continue'));
		//os=os.replace("ajaxSubmitAllForm","cekSubmitReg");
	}
	$('#reg_'+rnd).attr("onsubmit",os);
	tampilkanBasket(rnd,'-',ver,page);
	
	return false;
}


function gantiProvinsi(rnd){
	provinsi=$("#provinsi_"+rnd).val();
	kota=$("#kota_"+rnd).val();
	
	//url=js_path+"index.php?contentOnly=1&page=gantiprovinsi&newrnd="+rnd+"&provinsi="+encodeURI(provinsi)+"&kota="+encodeURI(kota);
	url="index.php?contentOnly=1&page=gantiprovinsi&newrnd="+rnd+"&provinsi="+encodeURI(provinsi)+"&kota="+encodeURI(kota);
	bukaAjax("tkota_"+rnd,url);
}

//dari checkbox ke radio
function klikRadioPaket(rnd,jns,ch,objval){
	val=document.getElementById(objval).checked;
	var obj= $("input[name="+ch+"][cls2=pos]");
	id=$(obj).attr("id");
	csekarang=document.getElementById(id).checked;
	if (val!=csekarang) {
		document.getElementById(id).checked=val;
	}

	hitungHargaFix(rnd);
	tampilkanBasket(rnd); 
}

function absenLive(){
 $.ajax(function(){
	 url:'index.php?contentOnly=1&useJS=2&rep=absen-daf&op=absenlive'
 }).done(function(data){
	$('tabsenlive').html(data);
	setTimeout('absenLive()',3000);
	 
 });		 
}
