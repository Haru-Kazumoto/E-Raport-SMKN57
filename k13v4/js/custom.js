$(document).ready(function(){
	w=$(window).width();
	w2=$('#left').width();
	w3=w-w2-20;
	$('#content').width(w3);
	
});

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

	function tambahMapGuru(rk,rnd){
			pilih=$('#pilihguru_'+rnd).val();
			if (pilih=='') {
				alert("Anda belum memilih nama guru");
				return false;
				}
			idg="tg"+rk;
			aawal=$('#'+idg).html();
			a=aawal;
			if (aawal!='') a+='|';
			
			idpil=idg+Math.round(Math.random()*12345179641,0);
			a+="<span id='"+idpil+"' class='idpil'>";
			a+="<a href=# onclick=\"hapusMapGuru('"+idg+"','"+idpil+"');return false;\" >"+pilih+"</a>";
			a+="</span>";
			$('#'+idg).html(a);
			changeMapMP(rnd);
			return false;
		}
		
		function hapusMapGuru(idg,idpil,rnd){
			if (confirm('Yakin akan hapus nama guru di pelajaran ini?')) { 
				$('#'+idpil).remove();
				a=$('#'+idg).html();
				p=a.indexOf("<");
				if (p>0) {
					a=a.substring(p,a.length);
					a=a.replace("||","|");
					a=a.replace("||","|");
					$('#'+idg).html(a);
				}
				else if (p<0) $('#'+idg).html('');
				
				changeMapMP(rnd); 
			}
			return false;
		}
		 
		function filterTabelInput(hal){
			hal+="&cari=cari";
			
			var allTags = document.getElementById('tFilter').getElementsByTagName("select");
			for (var i = 0, len = allTags.length; i < len; i++) {
				if (allTags[i].value!='') hal+="&"+allTags[i].name+"="+encodeURI(allTags[i].value);
			}
			bukaAjax('tcari',hal);
		} 
		
		function changeMapMP(rnd){
			var allTags = document.getElementById('tabs1').getElementsByTagName("input");
			t="";
			for (var i = 0, len = allTags.length; i < len; i++) {
				if (allTags[i].checked) {
					//t+=(t==''?'':'#')+allTags[i].id+"|";
					t+='#'+allTags[i].id+"|";
					t+=removeTags($("#tg"+allTags[i].id).html());//nama guru
				}
			}
			document.getElementById('matapelajaran').value=t;
			
		}
		
		function gantiComboKKM(cb,rnd){
			//jkom="";
			//jkom: sikap
			if (cb=='jenismp') {
				//jenismp=document.getElementById('jenisMP_'+rnd).value; 
				jenismp=$("#jenisMP_"+rnd+" option:selected").text();
				document.getElementById('tisi').innerHTML=''; 
				bukaAjax("tmp_"+rnd,"filtercombo-kkm.php?newrnd="+rnd+"&combo=mp&jenismp="+jenismp);
				
			} else if (cb=='isi') {
				kdmp=jenismp=$("#kode_matapelajaran_"+rnd).val();
				bukaAjax("tisi","inputkkm2.php?newrnd="+rnd+"&op=showdata&kdmp="+kdmp);
				
			}
		}
		function gantiComboNilai(cb,jkom,rnd){
			//jkom="";
			//jkom: sikap
			document.getElementById('tnilai_'+rnd).innerHTML='';
			if (cb=='kelas') {
				document.getElementById('tsemester_'+rnd).innerHTML=''; 
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				bukaAjax("tsemester_"+rnd,"filtercombo.php?newrnd="+rnd+"&jkom="+jkom+"&combo=semester&kode_kelas="+kode_kelas);
				if (jkom=='sikap') {
				} else {
					document.getElementById('tmp_'+rnd).innerHTML=''; 
					bukaAjax("tjenisMP_"+rnd,"filtercombo.php?newrnd="+rnd+"&jkom="+jkom+"&combo=jenisMP");
					
					/*
					document.getElementById('tkompetensi_'+rnd).innerHTML='';
					bukaAjax("tki_"+rnd,"filtercombo.php?newrnd="+rnd+"&jkom="+jkom+"&combo=ki&kode_kelas="+val);
					*/
				}
				
			}
			else if ((cb=='jenisMP')||(cb=='semester')) { 
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				semester=document.getElementById('semester_'+rnd).value;
				if (jkom!='sikap') {
					document.getElementById('tkompetensi_'+rnd).innerHTML='';
					document.getElementById('tmp_'+rnd).innerHTML='';
					jenisMP=document.getElementById('jenisMP_'+rnd).value;
					if (jenisMP=='') return;					
					url="filtercombo.php?newrnd="+rnd+"&jkom="+jkom+"&combo=mp&semester="+semester+"&jenisMP="+jenisMP+"&kode_kelas="+kode_kelas+"&usemap=1";
					bukaAjax('tmp_'+rnd,url);	
				} else {
					url="inputnilaisikap.php?newrnd="+rnd+"&op=showdata&kode_kelas="+kode_kelas+"&semester="+semester+"&jkom="+jkom;
					bukaAjax('tnilai_'+rnd,url,0,'bukaMapMP()');
				
				}

			}
			else if ((cb=='mp')||(cb=='ki')) { 
				semester=document.getElementById('semester_'+rnd).value;
		
				jenisMP=document.getElementById('jenisMP_'+rnd).value;
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				kode_matapelajaran=document.getElementById('kode_matapelajaran_'+rnd).value;
				ki=document.getElementById('ki_'+rnd).value;
				
				if (jPenilaian!='perkd') {  
					url="inputnilai.php?newrnd="+rnd+"&op=showdata&kode_kelas="+kode_kelas+"&semester="+semester;
					url+="&jenisMP="+jenisMP+"&kode_matapelajaran="+kode_matapelajaran+"&ki="+ki;
					ki=encodeURI(document.getElementById('ki_'+rnd).value);
					bukaAjax('tnilai_'+rnd,url);
				 } else {  
				
				
					if (jkom=='sikap') {
						bukaAjax("tsiswa_"+rnd,"filtercombo.php?newrnd="+rnd+"&jkom="+jkom+"&combo=siswa2&kode_kelas="+kode_kelas);
					} else {
						if (jinput=='perkd') {
							url="inputnilai.php?newrnd="+rnd+"&op=showdata&kode_kelas="+kode_kelas+"&semester="+semester;
							url+="&jenisMP="+jenisMP+"&kode_matapelajaran="+kode_matapelajaran+"&ki="+ki;
							ki=encodeURI(document.getElementById('ki_'+rnd).value);
							bukaAjax('tnilai_'+rnd,url);
						} else {
							ki=document.getElementById('ki_'+rnd).value;
							url="filtercombo.php?newrnd="+rnd+"&jkom="+jkom+"&combo=kompetensi&semester="+semester+"&jenisMP="+jenisMP+"&kode_kelas="+kode_kelas+"&kode_matapelajaran="+kode_matapelajaran+"&ki="+ki; 
							bukaAjax("tkompetensi_"+rnd,url);
						}
					}
				} 
			} else if (cb=='nilai') {			 
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				if (jkom=='sikap') {
					jenis=document.getElementById('jenis_'+rnd).value;
					semester=document.getElementById('semester_'+rnd).value;
					kode_matapelajaran=document.getElementById('kode_matapelajaran_'+rnd).value; 
					nis=document.getElementById('nis_'+rnd).value;
					if (jenis!='') {
						bukaAjax('tnilai'+rnd,"inputnilaisikap.php?newrnd="+rnd+"&op=showdata&kode_kelas="+kode_kelas+"&semester="+semester+"&kode_matapelajaran="+kode_matapelajaran+"&nis="+nis+"&jenis="+jenis,0,'bukaMapMP()');
					} else $("#tnilai"+rnd).html("");
				} else 
					kode_kompetensi=encodeURI(document.getElementById('kode_kompetensi_'+rnd).value);
					ki=encodeURI(document.getElementById('ki_'+rnd).value);
					url="inputnilai.php?newrnd="+rnd+"&op=showdata&kode_kelas="+kode_kelas+"&semester="+semester;
					url+="&jenisMP="+jenisMP+"&kode_matapelajaran="+kode_matapelajaran+"&ki="+ki+"&kode_kompetensi="+kode_kompetensi;
					bukaAjax('tnilai_'+rnd,url);
			}

		}
		
		function gantiComboLedger(cb,rnd){
			kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
			bukaAjax("tsemester_"+rnd,"filtercombo.php?newrnd="+rnd+"&combo=semester&kode_kelas="+kode_kelas+"&func=-");		 
		}
		
		function gantiSiswaKelas(rnd,tbop){
			kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
			bukaAjax("tcari","input.php?newrnd="+rnd+"&det=siswa&cari=cari&tbop="+tbop+"&kode_kelas="+kode_kelas);
			}
		function gantiComboCas(nis,rnd){
			semester=document.getElementById('semester_'+rnd).value;
			url="inputcas.php?newrnd="+rnd+"&cari=cari&semester="+semester+"&nis="+nis;
			bukaAjax("tcas_"+rnd,url);		 
			
		}
		function gantiComboRKegiatan(nis,rnd){
			semester=document.getElementById('semester_'+rnd).value;
			url="inputrkegiatan.php?newrnd="+rnd+"&cari=cari&semester="+semester+"&nis="+nis;
			bukaAjax("trkegiatan_"+rnd,url);		 
			
		}
		
		function gantiComboRaport(cb,rnd){
			if (cb=='kelas') {
				document.getElementById('tsemester_'+rnd).innerHTML='-'; 
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				bukaAjax("tsemester_"+rnd,"filtercombo.php?newrnd="+rnd+"&combo=semester&func=-&kode_kelas="+kode_kelas);
				document.getElementById('tsiswa_'+rnd).innerHTML='-'; 
				bukaAjax("tsiswa_"+rnd,"filtercombo.php?newrnd="+rnd+"&combo=siswa&func=-&kode_kelas="+kode_kelas);
			}
		}
		function gantiComboCetak(cb,rnd){
			if (cb=='kelas') {
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				bukaAjax("tsiswa_"+rnd,"filtercombo.php?newrnd="+rnd+"&combo=siswa&kode_kelas="+kode_kelas);
			}
		}
		
		function cekFocus(ide,tipe) {
			if (tipe=='N') {//numeric
				v=document.getElementById(ide).value;
				if (v==0) {
					document.getElementById(ide).value="";
					
				}
			}
		}
		
		function cekBlur(ide,tipe) {
			if (tipe=='N') {//numeric
				v=document.getElementById(ide).value;
				idq=ide;
				idq=idq.replace("[","\\[");
				idq=idq.replace("]","\\]");
				
				if (v=='') {
					document.getElementById(ide).value="0";
					$("#"+idq).attr("class","input-blank");
				} else {
					$("#"+idq).attr("class","input-noblank");
				}
			}
		}
		
		function cekDisableNilai(n) {
				v=($("#chbn"+n).prop("checked")?1:0);
				$("#bn"+n).val(v);
			
			hitungNA();
		}
		function hitungNA2(br) {
			//hitung nilai untuk jinput=perkd
			jkd=document.getElementById('jkd').value;
			jna=0;
			for (i=1;i<=jkd;i++) {
				jna+=document.getElementById('kd'+i+"["+br+"]").value*1;
			}
			
				na=Math.round(jna/jkd*100)/100; //bulatkan 2angka
				document.getElementById('nilai['+br+']').value=na;
				document.getElementById('nilaix['+br+']').value=na;
			
		}
		function hitungNA(baris) {
			
			bn1=$("#bn1").val()*1;
			bn2=$("#bn2").val()*1;
			bn3=$("#bn3").val()*1;
			if (baris==undefined) {
				//jika semua
				awal=0;
				akhir=$("#jlhsiswa").val()*1-1;				
				//deteksi bn, jika bn=0, maka semua disable
				for (n=1;n<=3;n++) {
					bnx=$("#bn"+n).val()*1;
					for (br=awal;br<=akhir;br++) {
						document.getElementById('n'+n+'['+br+']').style.display=(bnx==0?'none':'');
						//$('#n'+n+'['+br+']').prop("disabled",(bnx==0?"true":"false"));
					}
				}
			}
			else {
				awal=akhir=baris;
			}
			for (br=awal;br<=akhir;br++) {
				ki='';//document.getElementById('ki').value;
				n1=document.getElementById('n1['+br+']').value;
				n2=document.getElementById('n2['+br+']').value;
				n3=document.getElementById('n3['+br+']').value;
				if (ki=='Sikap Sosial dan Spiritual') {
					bn4=$("#bn4").val()*1;
					n4=document.getElementById('n4['+br+']').value;
				} else {
					bn4=0;
					n4=0;
				}
				jlh=bn1+bn2+bn3+bn4;
				na=Math.round((bn1*n1+bn2*n2+bn3*n3+bn4*n4)/jlh*100)/100; //bulatkan 2angka
				document.getElementById('nilai['+br+']').value=na;
				document.getElementById('nilaix['+br+']').value=na;
			}
			//document.getElementById('tna['+br+']').value=na;
		}
		
		
		
		function gantiMap(rnd){
			//return false;
			kode_kelas = $("#kode_kelas_"+rnd).val();
			semester = $("#semester_"+rnd).val();
			bukaAjax('tmap','inputmap.php?newrnd='+rnd+'&showresult=1&kode_kelas='+kode_kelas+'&semester='+semester,0,"bukaMapMP()");
		}  