<?php
$useJS=2;
include_once "conf.php";
cekVar("id,op,det,jcetak");

if ($op=='genall') {
//	include $um_path."mysql-create-trigger.php";
	include $um_path."mysql-drop-trigger.php";
	exit;
} elseif ($op=='hapusfoto') {
	cekvar("nf,idtd");
	//echo $nf;
	if (file_exists($nf)) {
		unlink($nf);
		
		if (!file_exists($nf)) { 
			echo "File berhasil dihapus";
			echo fbe("
			$('#x$idtd').attr('src','');
			tutupDialog2('$idtd');
			");
		} else
			echo "File $nf tidak bisa dihapus, silahkan hapus manual via windows explorer";
	} else 
		echo "File tidak ditemukan";
	exit;
} 

if ($userID=='Guest'){
	include "index.php";
	exit;
}
if (!function_exists('evalGFInput')) {
	function evalGFInput($gv,$isifield=''){
		global $r;
		global $inp;
		
		if ($gv!='') {
			$gv=str_replace("-{","$"."r[",$gv);
			$gv=str_replace("}-","]",$gv);
			//$gv=str_replace('\\','\\'.'\\',$gv);
			//$gv=str_replace('"','\\"',$gv);
					
			if (substr($gv,0,4)=="inb>") { //if not blank 
				//if ($isifield=='') return '';
				$gv=substr($gv,4,strlen($gv));
				
			}
			
			if (substr($gv,0,4)=="$"."inp") 
				$ev=$gv;
			else {
				$ev='$inp="'.$gv.'";';
				//$ev=$gFieldInput[$i];
			}
			//echo $ev;
			eval($ev);
		}
		//return $inp;
	}
	
	function mb_html_entity_decode($string)		{
		if (extension_loaded('mbstring') === true)
		{
			mb_language('Neutral');
			mb_internal_encoding('UTF-8');
			mb_detect_order(array('UTF-8', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));
	
			return mb_convert_encoding($string, 'UTF-8', 'HTML-ENTITIES');
		}
	
		return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
	}
	
	function mb_ord($string) {
		if (extension_loaded('mbstring') === true)
		{
			mb_language('Neutral');
			mb_internal_encoding('UTF-8');
			mb_detect_order(array('UTF-8', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));
	
			$result = unpack('N', mb_convert_encoding($string, 'UCS-4BE', 'UTF-8'));
	
			if (is_array($result) === true)
			{
				return $result[1];
			}
		}
	
		return ord($string);
	}
	
	function mb_chr($string){
		return mb_html_entity_decode('&#' . intval($string) . ';');
	}
	
	//var_dump(hexdec('010F'));
	
	//var_dump(mb_ord('ó')); // 243
	//var_dump(mb_chr(243)); // ó
}

cekVar("cari,op,sqOrderTabel,tbop,nfCSV,kode_kelas,outputto");
$showNo=false;
$showLinkOpr=$showLinkTambah=true;
$pathFoto="img/";
$addf=$addDel=$funcAfterEdit='';

if (strstr("admin,kaprog",$userType)=='') {
	$boleh="siswa,kompetensi";
	$showLinkTambah=(strstr($boleh,$det)==''?false:true); 		
	$showLinkOpr=(strstr($boleh,$det)==''?false:true); 		
}
if ($det=='') {
	$det=$_SESSION['det'];
	}
else {
	$_SESSION['det']=$det;
	}
if ($det=='inputnilai') {
	include "inputnilai.php";
	exit;
}
elseif ($det=='inputsikap') {
	include "inputnilaisikap.php";
	exit;
}
elseif ($det=='inputledger') {
	include "inputledger.php";
	exit;
}
elseif ($det=='raport') {
	include "inputraport2.php";
	exit;
}
elseif ($det=='inputkkm') {
	include "inputkkm.php";
	exit;
}elseif ($det=="backuprestore") {
	include "inputbr.php";
	exit;
}elseif ($det=="updkelas") {
	include "input-updkelas.php";
	exit;
} elseif ($det=='sekolah') {
	include "inputsekolah.php";
	echo "
	<div class='well well-small'>
		$infoSekolah 
	</div>";

	exit;
}
//penggantian Field text
$gGroup=$gField=$gFieldView=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
$sFieldShowInTable=$sFieldShowInInput=$sAlign=$sFieldWillUpdate=",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
$sHFilterTabel="";
$sqFilterTabel="";
$sOrderTabel=$sqlTabel="";
if (!isset($nextOp)) $nextOp="";
if (!isset($nexturl)) $nexturl="";
$addParamAdd="";//tambahan parameter untuk tombol add
$sLebar="1,1,1,1,1,1,1,1,1,1,1,1,1,1";
$nfAction="input.php?det=$det";
$hal="input.php?det=$det";
$addInput0=$addInput=$addFuncAdd=$addFuncEdit=$addFuncView=$addParamView="";

$sAwalan="A,B,C1,C2,C3,ML";
$aJenisMP=explode(",",$sJenisMP);
$aAwalan=explode(",",$sAwalan);
if (!isset($showLinkLihat)) $showLinkLihat=true;		
$addTbBawah="";//tambahan tombol

if ($cari!='') { //filter pencarian
	foreach($_REQUEST as $nm=>$value) {
		if (substr($nm,0,2)=='ft') {
			$var=substr($nm,3,100);
			$sqFilterTabel.=($sqFilterTabel==''?" where ":" and ");
			$sqFilterTabel.="$var = '$value'";
			$addParamAdd.="&$var=$value";
		}
	}
}

if ($det=='paket keahlian') {  
	$showLinkOpr=true;
	$nmTabel="kompetensi_keahlian";
	$nmCaptionTabel="Daftar Kompetensi Keahlian";
	$nmFieldID="kode";
	$sField="Nama";
	$sLebar="60,60,60";
	$sFieldCaption="Nama Kompetensi";
	$sqlTabel="select * from $nmTabel";
	//$gField[0]="$"."inp=um412_isiCombo5('select * from program_keahlian','kode_programkeahlian','kode','nama','-Pilih-',$"."r['kode_programkeahlian'],'');";	
}elseif ($det=='config') {  
	$nmTabel="tbconfig1";
	$nmCaptionTabel="Konfigurasi";
	$nmFieldID="kode";
	$sField="alloweditmp,kna,knb,knc,knd,allowopenreport1,allowopenreport2,showttd";
	$sLebar="20,10,10,10,10,10,10,10,10,10,1o";
	$sFieldCaption="Mode Edit MP,Batas Nilai Min A,Batas Nilai Min B,Batas Nilai Min C,Batas Nilai Min D,Mode Buka Raport Mid,Mode Buka Raport Akhir,Mode Tandatangan";
	$sqlTabel="select kode,
	if(alloweditmp=1, 'Terbuka', 'Tertutup') as al,
	if(allowopenreport1=1, 'Terbuka', 'Tertutup') as a2,
	if(allowopenreport2=1, 'Terbuka', 'Tertutup') as a3,
	if(showttd=1, 'Tampilkan', 'Sembunyikan') as a4,
	kna,knb,knc,knd,allowopenreport1,allowopenreport2 from tbconfig1 ";
	$sFieldShowInTable="al,,,,,a2,a3,a4,";
	$gField[0]="$"."inp=um412_isiCombo5('R:Buka Akses;1,Tutup Akses;0','alloweditmp');";
	$gField[5]="$"."inp=um412_isiCombo5('R:Buka Akses;1,Tutup Akses;0','allowopenreport1');";
	$gField[6]="$"."inp=um412_isiCombo5('R:Buka Akses;1,Tutup Akses;0','allowopenreport2');";
	$gField[7]="$"."inp=um412_isiCombo5('R:Tampilkan;1,Sembunyikan;0','showttd');";
	
	$gFieldView[0]="$"."inp=-{'al'}-;";
	$gFieldView[5]="$"."inp=-{'a2'}-;";
	$gFieldView[6]="$"."inp=-{'a3'}-;";
	$gFieldView[7]="$"."inp=-{'a4'}-;";
	
	$showLinkTambah=false;
	$sAlign="align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,align=center,,,,,,,,,,,,,,,,,,,,,,,,,";
	
//$gField[0]="$"."inp=um412_isiCombo5('select * from program_keahlian','kode_programkeahlian','kode','nama','-Pilih-',$"."r['kode_programkeahlian'],'');";	
} elseif ($det=='userku') {  
	$nfCSV="";
	$nmTabel="tbuser";
	$nmCaptionTabel="User";
	$nmFieldID="id";
	$sField="userid,username,usertype,pass,catatan";
	$sLebar="30,30,30,0,120";
	$sFieldCaption="User ID,User Name,User Type,Password,Keterangan";
	cekVar("usertype");
	$gField[2]="$"."inp=\"".um412_isiCombo5("admin,kaprog,guru,siswa","usertype","","","$usertype")."\"; ";
	$sFieldShowInTable=",,0,,,,";
	if (strstr(",ed,tb,",",$op,")!='')
		if (($op=='ed') && ($pass==''))
			$_REQUEST['pass']=$pass=carifield("select pass from tbuser where id='$id'");
		else
			$_REQUEST['pass']=$pass=md5($_REQUEST['pass']);
	elseif ($op=='itb') {
		$gField[3]="$"."inp=\"<input type=password size=20 name=pass>".($id>0?" *) Biarkan password kosong jika tidak ingin diubah":"")." \"; ";
		$tipe=carifield("select usertype from tbuser where id='$id'");
		if ($tipe=='Siswa') {
			echo "User siswa adalah default user untuk setiap siswa. user ini tidak bisa diubah dan dihapus";
			$op='showtable';
		}			
	}
} elseif ($det=='news') { //guru
	$nfCSV="data_news_".str_replace("-","",$thpl).".csv";
	$nmTabel="tbnews";
	$nmCaptionTabel="Informasi";
	$nmFieldID="id";
	$pathFoto="images/news/";

	$sField="id,author,tgl,kategori,judul,isi";
	$sFieldCaption="Id,Author,Tanggal,Kategori,Judul,Isi";
	$sqlTabel="select * from tbnews order by id desc";
	$sLebar="20,20,20,60,20,20,30";
	$sFieldShowInTable="0,,,0,,,,,";
	$sFieldShowInInput="0,,0,0,,,,,";
	$gField[3]="$"."inp=um412_isiCombo5('C:berita','kategori');";
	if (($op=="itb") && ($id=="")) $author=$userID;
	$gField[5]="$"."inp=\"<textarea name=isi id=isi_$rnd rows=10 cols=70>\".$"."r['isi'].\"</textarea>\";";
	$showLinkOpr=true;
		$nexturl="index.php";
	if ($op=='itb') {
		$addf.="CKEDITOR.replace('isi_$rnd');";
	}
	if ($userType!='admin') {
		$showLinkOpr=false;
	}
	
} elseif ($det=='log') { 
	$nfCSV="";
	$nmTabel="tblog";
	$nmCaptionTabel="Log System";
	$nmFieldID="id";
	$addTbBawah="<br><button class='btn btn-danger btn-sm' href=# onclick=\"bukaAjax('content','input.php?det=log&op=clearlog');\" onclick><i class='icon-trash'></i>&nbsp; Hapus Log</button>";
	
	if ($op=='clearlog') {
		mysql_query("delete from tblog");
		$op="";
	}

	$sField		  ="id,tgl,user,jenislog,idtrans,ket";
	$sFieldCaption="Id,Tanggal,User ID,Jenis,Id Trans,Keterangan";
	$sqlTabel="select * from tblog order by id desc";
	$sLebar="20,20,20,10,10,200,20,20,20,30";
	$sFieldShowInTable="0,,,,,,,,";
	$sFieldShowInInput="0,,,,,,,,";
	$sAlign=",align=center,align=center,,,,,,,,,,,,,,,,,,,,,,,,,";
//$gField[3]="$"."inp=um412_isiCombo5('C:berita','kategori');";
	if (($op=="itb") && ($id=="")) $author=$userID;
	//$gField[5]="$"."inp=\"<textarea name=isi id=isi rows=10 cols=70>\".$"."r['isi'].\"</textarea>\";";
	$showLinkOpr=false;	
	$showLinkTambah=false;
	
} elseif ($det=='guru') { //guru
	
	$nfCSV="data_guru_".str_replace("-","",$thpl).".csv";
	$nmTabel="guru";
	$nmCaptionTabel="Daftar Guru";
	$nmFieldID="kode";
	$pathFoto="foto/guru/";

	$sField="nama,nip,foto,fotott,uidg,upwdg";
	$sFieldCaption="Nama,NIP,Foto,Tanda Tangan,User ID,User Password";
	$sLebar=		"30,8,10,10,20,20,20,20,60,20,20";
	$gField[5]="$"."inp='<input type=password id=upwdg name=upwdg value=\"'.-{'upwdg'}-.'\">';";		

	$i=5;
	$allowEditMP=carifield("select alloweditmp from tbconfig1");
	if (($allowEditMP==1)||($userType=="admin" )) {		
		$i++;
		$sField.=",skdmapel";
		$sFieldCaption.=",Mata Pelajaran";
		$gField[$i]="$"."inp='<div style=\"height:300px;width:470px;overflow:auto;border:1px solid #ccc;margin-bottom:10px;padding:5px;\">'.um412_isiCombo5('CBR:select kode,nama from matapelajaran','skdmapel').'</div>';";
		$gFieldView[$i]="$"."inp=cariSfield('select nama from matapelajaran ',\"-{skdmapel}-\",'kode',', ');";
	}
	if ($userType=="admin" ) {
		$i++;
		$sField.=",ulevelg";
		$gField[$i]="$"."inp=um412_isiCombo5('R:admin,guru,kaprog','ulevelg');";		
		$sFieldCaption.=",Level User";
	}
	$sLebar="30,8,10,10,20,20,70,10,60,20,20";
	
	$sqlTabel="select * from guru order by nama";
	
	$sFieldShowInTable=",,0,0,0,0,,0,0,,,,,,,,,,,,,,";
	$showLinkOpr=true;
	
	if (strstr("guru,kaprog",$userType)!='') {
		$showLinkLihat=false;
		$id=carifield(" select kode from guru where uidg='$userID'");
		$nextOp="view";
		if (($op=='')||($op=='showtable')) $op="view";
	}
	
} elseif ($det=='pembobotan') { //guru
	//$nfCSV="data_guru_".str_replace("-","",$thpl).".csv";
	$nmTabel="tbconfig1";
	$nmCaptionTabel="Setting Pembobotan Nilai";
	$nmFieldID="kode";
	$sField="bobotpg,bobotkt,bobotsk";
	$sFieldCaption="Pengetahuan,Keterampilan,Sikap dan Spiritual";
	$sqlTabel="select * from $nmTabel";
	$sLebar="60,60,60";
	$showLinkLihat=false;
	if (($op=='showtable') ||($op=='')) $op="itb";		
	//biar tidak bisa hapus
	$showLinkTambah=false;
	if ($op=='itb') {
		extractRecord("select bobotsk,bobotpg,bobotkt,ketpg,ketkt,ketsk from tbconfig1 ");
		$bnpg=split("#",$bobotpg);
		$bnkt=split("#",$bobotkt);
		$bnsk=split("#",$bobotsk);		
		$aketpg=split("#",$ketpg);
		$aketkt=split("#",$ketkt);
		$aketsk=split("#",$ketsk);		
	} elseif (($op=='ed')|| ($op=='tb')) {
		$bobotpg=$_REQUEST['bobotpg']="$n1pg#$n2pg#$n3pg";
		$bobotkt=$_REQUEST['bobotkt']="$n1kt#$n2kt#$n3kt";
		$bobotsk=$_REQUEST['bobotsk']="$n1sk#$n2sk#$n3sk#$n4sk";
		mysql_query("update tbconfig1 set bobotsk='$bobotsk',bobotkt='$bobotkt',bobotpg='$bobotpg' ");
		//$op="showtable";
		$op="itb";
		echo "<br>Setting berhasil diupdate. <a href='#' onclick=\"bukaAjax('content','$hal&op=itb"."$addParamAdd');return false;\">Klik di sini</a> untuk refresh ";
	exit;
	}
	$addInput0="
	<style>
	.stic { float:left; margin:0 5px; }
	.sti0,
	.sti1,
	.sti2,
	.sti3 {
		float:left;
		text-align:right;
	}
	.sti0 { width:70px}
	.sti1 { width:80px}
	.sti2 { width:170px}
	.sti3 { width:50px}
	
	</style>
	";
	$ginpg=$ginsk=$ginkt="";
	for ($x=0;$x<=3;$x++) { 
		if ($x<3) {
			$ginpg.="<span class='sti"."$x' >$aketpg[$x] </span>
			<span class='stic'><input type=text size=2 name=n".($x+1)."pg id=n".($x+1)."pg value='$bnpg[$x]' ></span>";	
			$ginkt.="<span class='sti"."$x' >$aketkt[$x] </span>
			<span class='stic'><input type=text size=2 name=n".($x+1)."kt id=n".($x+1)."kt value='$bnkt[$x]' ></span>";	
		}
		$ginsk.="<span class='sti"."$x' >$aketsk[$x] </span>
		<span class='stic'><input class='form-control' type=text size=2 name=n".($x+1)."sk id=n".($x+1)."sk value='$bnsk[$x]' ></span>";	
	}
	
	$gField[0]="$"."inp=\"$ginpg\";";
	$gField[1]="$"."inp=\"$ginkt\";";
	$gField[2]="$"."inp=\"$ginsk\";";
	$addInput="&nbsp;";
	
}elseif ($det=='siswa') { 
	extractRecord("select nama as kelas from kelas where kode='$kode_kelas'  order by nama");
	$nfCSV="kelas_".(str_replace(" ","",$kelas))."_kodekelas_".$kode_kelas."_".str_replace("-","",$thpl).".csv";
	$nmTabel="siswa";
	$nmCaptionTabel="Daftar Siswa";
	$nmFieldID="nis";
	$sqOrderTabel=" order by nama";
	$pathFoto="foto/siswa/";
	$sField="foto,kode_kelas,nis,nama,nisn,kota_lahir,tanggal_lahir,gender,agama,anakke,stat_keluarga,alamat,telepon,sekolah_asal,kelas_terima,tanggal_terima,ayah,ibu,alamat_ortu,telp_ortu,pek_ayah,pek_ibu,wali,alamat_wali,telp_wali,pek_wali";
	$sFieldCaption="Foto,Kelas,NIS,Nama,NISN,Kota Lahir,Tanggal Lahir,Jenis Kelamin,Agama,Anak Ke,Status Keluarga,Alamat,Telepon,-Sekolah Asal,Diterima di Kelas ,Tanggal Terima,-Nama Ayah,Nama Ibu,Alamat,Telepon/HP,Pekerjaan Ayah,Pekerjaan Ibu,-Nama Wali,Alamat,Telepon/HP,Pekerjaan";
	$sLebar="0,20,10,60,15,30,12,12,15,4,20,110,20,70,10,10,40,40,110,20,25,25,40,110,20,35";
	if ((strstr(",importcsv,exportcsv,unduhformat,",",$op,")!='') ||($outputto=='csv')) {
		$sField=str_replace("foto,","",$sField);					 
		$sFieldCaption=str_replace("foto,","",$sFieldCaption);					 
	}
	
	$sFieldShowInInput=",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
	$sFieldShowInTable="0,0,,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";
	$sAlign=",,align=center,,,,,,,,,,,,,,,,,,,,,,,,,,,";

	$sqlTabel="select siswa.* from siswa where siswa.kode_kelas='$kode_kelas'";
	
	if (($op=='unduhformat')||($op=='showtable'))
		$sqlTabel="select $sField from siswa where siswa.kode_kelas='$kode_kelas'";
	
	//else 
		//$sqlTabel="select siswa.*,siswa_pindah.id as idp from siswa left join siswa_pindah on siswa.nis=siswa_pindah.nis where siswa.kode_kelas='$kode_kelas'";
	if ($op!='itb') {
		$pilkelas=um412_isicombo5("select kode,nama from kelas order by nama","kode_kelas,class='form-control' style='width:100px'","kode","nama","","$kode_kelas","gantiSiswaKelas($rnd,'$tbop')");
		$nmCaptionTabel="<table style='margin-bottom:20px'><tr><td>Kelas : </td><td> $pilkelas</td></tr></table>";
	} else {
		
		$pilkelas=um412_isicombo5("select kode,nama from kelas order by nama","kode_kelas,class='form-control' style='width:100px'","kode","nama","","$kode_kelas","");
	}
	
	if ($kode_kelas=='') {
		$showLinkTambah=false;
		$nfCSV='';
	} else {
		$kelas=cariField("select nama from kelas where kode='$kode_kelas'  order by nama");
		$nmCaptionTambah="Siswa Kelas $kelas";
		$addParamAdd.="&kode_kelas=$kode_kelas";
	}
	//$gField[0]="$"."inp=\"<input type=file name=foto id=foto>\";";
	$gField[1]="$"."inp=\"
	<input class='form-control' type=hidden name=kode_kelasx id=kode_kelas value='$kode_kelas'>
	<span class=input>$pilkelas</span> \";";
	$gField[6]="$"."inp=\"<input class='form-control' type=text id=tanggal_lahir name=tanggal_lahir onmouseover=bukaTgl(this.name) value=\".SQLtotgl($"."r['tanggal_lahir']).\">\";";
	$gField[7]="$"."inp=um412_isiCombo5('Laki-laki;1,Perempuan;0','gender','','','-Pilih-',$"."r['gender'],'');";
	$gField[8]="$"."inp=um412_isiCombo5('Islam;0,Kristen;1,Katholik;2,Hindu;3,Budha;4,Kepercayaan;5','agama','','','-Pilih-',$"."r['agama'],'');";
	$gField[14]="$"."inp=um412_isiCombo5('select * from kelas  order by nama','kelas_terima','kode','nama','-Pilih-',$"."r['kelas_terima'],'');";
	$gField[15]="$"."inp=\"<input type=text id=tanggal_terima name=tanggal_terima onmouseover=bukaTgl(this.name) value=\".SQLtotgl($"."r['tanggal_terima']).\" >\";";
	//$gField[15]="$"."inp=um412_isiCombo5('1,2,3,4,5,6','semester_terima','','','-Pilih-',$"."r['semester_terima'],'');";
	$isiJKerjaan="PNS,TNI,POLRI,Karyawan,Wiraswasta,Petani,Buruh,Nelayan,Profesional,Lain-lain";
	$gField[20]="$"."inp=um412_isiCombo5('$isiJKerjaan','pek_ayah','','','-Pilih-',$"."r['pek_ayah'],'');";
	$gField[21]="$"."inp=um412_isiCombo5('$isiJKerjaan','pek_ibu','','','-Pilih-',$"."r['pek_ibu'],'');";
	$gField[25]="$"."inp=um412_isiCombo5('$isiJKerjaan','pek_wali','','','-Pilih-',$"."r['pek_wali'],'');";
	
	if ($userType=='siswa') {
		$showLinkLihat=false;
		$id=$nis;
		$nextOp="view";
		if (($op=='')||($op=='showtable')) $op="view";
	}
}
elseif ($det=='ekskul') { //guru
	$nmTabel="ekskul";
	$nmCaptionTabel="Daftar Ekstrakurikuler";
	$nmFieldID="id";
	$sField="ekskul,deskripsi";
	$sFieldCaption="Ekstrakurikuler,Deskripsi";
	$sFieldShowInTable=",,,,,,,,,,,,,,,,,,,,";
	$sLebar="30,120";
	//penggantian field
	$sqlTabel="select * from ekskul ";
	$sqOrderTabel=" order by ekskul";
	//$gField[0];
	$gField[1]="$"."inp=\"<textarea name=deskripsi id=deskripsi rows=3 cols=70>\".$"."r['deskripsi'].\"</textarea>\";";
}
 
elseif ($det=='kelas') { 
	$nmTabel="kelas";
	$nmCaptionTabel="Daftar Kelas";
	$nfCSV="data_kelas_".str_replace("-","",$thpl).".csv";
	$nmFieldID="kode";
	
	$sField="kode_kompetensikeahlian,Tingkat,Nama,kode_guru";
	$sFieldCaption="Kompetensi Keahlian,Tingkat,Kelas,Wali Kelas";
	$sFieldShowInTable="0,0,,,,,,,,,,,,,,,,,,";
	$sqlTabel="select if(kelas.tingkat=10,'X',if(kelas.tingkat=11,'XI','XII')) as tingkat,kelas.nama, guru.nama as kode_guru,kelas.kode from kelas left join guru on kelas.kode_guru=guru.kode ";
	$sqOrderTabel=" order by tingkat,nama";
	if ((strstr(",importcsv,exportcsv,unduhformat,",",$op,")!='') ||($outputto=='csv')) {
		$sField="kelas.kode,tingkat,kelas.nama, kode_guru,kode_kompetensikeahlian";
		$sFieldCaption="kode,tingkat,nama kelas,kode_guru,kode_kompetensikeahlian";
		$sqlTabel="select kelas.kode,tingkat,kelas.nama, kode_guru,kode_kompetensikeahlian from kelas left join guru on kelas.kode_guru=guru.kode ";
		$sFieldShowInTable=",,,,,,,,,,,,,,,,,,,";
		
	}
	
	$sLebar="10,20,10,60,60";
	$sAlign=",,align=center,,,,,,,,,,,,,,,,,,,,,,,,";
	//penggantian field
	$gField[0]="$"."inp=um412_isiCombo5('select kode,nama from kompetensi_keahlian','kode_kompetensikeahlian','kode','nama','Pilih',$"."r['kode_kompetensikeahlian'],'');";
	$gField[1]="$"."inp=um412_isiCombo5('Tingkat X;10,Tingkat XI;11,Tingkat XII;12','Tingkat','','','',$"."r['tingkat'],'');";
	$gField[3]="$"."inp=um412_isiCombo5('select kode,nama,nip from guru','kode_guru','kode','nama,nip','',$"."r['kode_guru'],'');";
	//$gField[0];
	
}
elseif ($det=='mata pelajaran') { //guru
	$nfCSV="data_mata_pelajaran_".str_replace("-","",$thpl).".csv";
	$nmTabel="matapelajaran";
	$nmCaptionTabel="Daftar Mata Pelajaran";
	$nmFieldID="kode";
	$sLebar="10,40,100,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5,5";
	$sFieldShowInInput="0,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
	$sFieldShowInTable=",,,0,0,0,0,0,0,0,0,0,0,0,0,0,0,,,,,,,,,,,,,,,";
	$sAlign="align=center,align=center,,,,,,,,,,,,,,,,,,,,,,,,";
	$showLinkOpr=true;
	$sField="kode,jenis,nama";
	$sFieldCaption="Kode,Jenis,Mata Pelajaran";

	$addInput="";
	//include "inputkkm2-form.php";
	//$addInput="=\"$ai\"";
	
	
	$sqlTabel="select $sField from $nmTabel ";
	if ($gsmp!='') $sqFilterTabel.=($sqFilterTabel==''?' where':' and ').$gsmp;
	//filter
	
	$sqOrderTabel.="order by kode asc";
	$gField[1]="$"."inp=um412_isiCombo5('$sJenisMP','jenis','','','',$"."r['jenis'],'');";
	
	//nomor baru 
	if ($op=='tb') {
		$no=0;$kode="";
		foreach ($aJenisMP as $jmp) {
			if ($_REQUEST['jenis']==$jmp) $aw=$aAwalan[$no]; 
			$no++;
		}
		$_REQUEST['kode']=noTerakhir($nmTabel,$nmfield='kode',$aw,$digit=2); 
	}
	/*
	if ($op!="sitb") {
		echo "<style>textarea { width:85px }</style>";
	}
	*/
	
}
elseif ($det=='kkm') { 
	include "inputkkm2.php";
	exit;
	
	
} elseif ($det=='kompetensi') { 
	cekVar("kode_matapelajaran,semester,id");
	$nmTabel="kompetensi";
	$nmFieldID="kode";
	$sField="kode,kode_matapelajaran,semester,ki,kd";
	$sLebar="8,15,8,15,70,20,20,20,20,20,20,20,20,20,20";
	$sFieldShowInInput="0,,,,,,,,,,,,,,,,,,,,,,,";
	$sAlign="align=center,,align=center,align=center,,,,,,,,,,,,,,,,,,,,,,,";
	
	if ($jPenilaian=="perkd") {
		$nfCSV="data_kometensi_dasar_".str_replace("-","",$thpl).".csv";
		$nmCaptionTabel="Kompetensi Dasar";	
		$sFieldCaption="Kode,Mata Pelajaran,Semester,Kompetensi Inti,Kompetensi Dasar";


	} else {
		$nfCSV="data_deskripsi_kompetensi_".str_replace("-","",$thpl).".csv";
		$nmCaptionTabel="Deskripsi Mata Pelajaran";	
		$sFieldCaption="Kode,Mata Pelajaran,Smt,Kompetensi Inti,Deskripsi";
	}
	
	if (strstr("guru,kaprog",$userType)) {
		$sqFilterTabel.=($sqFilterTabel==''?' where ':' and ').$gsmp;
	}
	
	
	$sJenisKI="Pengetahuan,Keterampilan";//Sikap Sosial dan Spiritual,
	$aJenisKI=explode(",",$sJenisMP);
	
	$gField[1]="$"."inp=um412_isiCombo5(\"$combogsmp\",'kode_matapelajaran'); ";
	$gField[2]="$"."inp=um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih-',$"."r['semester'],'');";
	$gField[3]="$"."inp=um412_isiCombo5('R:$sJenisKI','ki','','','-Pilih-',$"."r['ki'],'');";
	$gField[4]="$"."inp=\"<textarea name=kd id=kd rows=5 cols=70>\".$"."r['kd'].\"</textarea>\";";
	$sqlTabel="select kompetensi.kode,matapelajaran.nama as kode_matapelajaran,semester,ki,kd from $nmTabel left join matapelajaran on $nmTabel.kode_matapelajaran=matapelajaran.kode ";
	//filter
	
	$sqOrderTabel=" order by $nmTabel.kode asc,matapelajaran.nama  asc,$nmTabel.semester,ki"; 
	
	//filter
	cekVar("ki");
	$sHFilterTabel.="Mata Pelajaran :&nbsp; ".um412_isiCombo5("select kode,nama from matapelajaran ".($gsmp==''?'':"where $gsmp"),'ft_kode_matapelajaran','kode','nama','-Pilih-',$kode_matapelajaran,"filterTabelInput('$hal')");
	$sHFilterTabel.="  Kompetensi Inti :&nbsp; ".um412_isiCombo5("Pengetahuan,Keterampilan",'ft_ki','ki','nama','-Pilih-',$ki,"filterTabelInput('$hal')");
	//$addParamAdd.="&ki=$ki&kode_matapelajaran=&$kode_matapelajaran";
	$sHFilterTabel.="&nbsp;&nbsp;&nbsp; Semester :&nbsp; ".um412_isiCombo5('1,2,3,4,5,6','ft_semester','','','-Pilih-',$semester,"filterTabelInput('$hal')");

	$addDel="mysql_query(\"delete from nilai_kompetensi_siswa where kode_kompetensi='$id'\");";
	
	//nomor baru
	if ($op=='tb'){
		//cek dulu apakah data sudah ada yang masuk atau belum , jika sudah ada, maka tidak boleh 
		$kodekp=$matapelajaran="";
		
		$sy="kode_matapelajaran='$kode_matapelajaran' and semester='$semester' and ki='$ki'";
		$sqc="select kompetensi.kode as kodekp,matapelajaran.nama as mp,ki from kompetensi inner join matapelajaran on kompetensi.kode_matapelajaran=matapelajaran.kode where $sy";
		extractRecord($sqc);
		if (($kodekp!='') && ($jPenilaian!='perkd')) {
			echo "<br><font  color='red'>Deskripsi kompetensi inti sudah dimasukkan sebelumnya dengan kode $kodekp.<br> data tidak bisa ditambahkan....</font> ";
			$_REQUEST['kode']=$kodekp;
			//mysql_query("update kompetensi set kd='$kd' where $sy");
			$op=$_REQUEST['op']='showtable';
		} else {
			//$aw=$_REQUEST['kode_matapelajaran'].substr($_REQUEST['ki'],0,1);
			$aw=$_REQUEST['kode_matapelajaran'].substr($_REQUEST['ki'],0,1).$semester;
			$_REQUEST['kode']=$kode=noTerakhir($nmTabel,$nmfield='kode',$aw,$digit=4);
		}
	}
}

elseif ($det=='map') { //guru
$nmTabel="map_matapelajaran_kelas";
	$nmCaptionTabel="Daftar Map Matapelajaran";
	$nmFieldID="id";
	$sField="kode_kelas,semester,matapelajaran";
	$sLebar="30,20,100";
	$sFieldCaption="Kelas,Semester,Map Mata Pelajaran";
	$sFieldShowInTable=",,0,,,,,,,,,,,,,,,,,";
	$sFieldShowInInput=",,0,,,,,,,,,,,,,,,,,";
	//$sFieldShowInInput=",,,,,,,,,,,,,,,,,,,";
	$sAlign=",align=center,,,,,,,,,,,,,,,,,";
	if (($op=='')||($op=='showtable')) $op="itb";
	cekVar("op2");
	if ($op2=='addguru') {
		$idg="tg".$rk;
		$t="";
		$t.="Nama Guru : ".um412_isicombo5("select nama from guru","pilihguru","nama","nama","-pilih-",$v,"tambahMapGuru('$rk');$('#taddguru').dialog('close');");
		$t.="<a href=# onclick=\"tambahMapGuru('$rk');return false\"> <i class= icon-plus-sign '></i> </a>";	
		echo "<center>$t</center>";
		exit;	
	}
	
	$sqlTabel="select map_matapelajaran_kelas.id,kelas.nama as kode_kelas, semester from map_matapelajaran_kelas left join kelas on map_matapelajaran_kelas.kode_kelas=kelas.kode  ";
	$gField[0]="$"."inp=um412_isiCombo5('select kode,nama from kelas  order by nama','kode_kelas','kode','nama','-Pilih Kelas-',$"."r['kode_kelas'],'gantiMap()');";
	$gField[1]="$"."inp=um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih Semester -',$"."r['semester'],'gantiMap()');";
		
	$sqOrderTabel=" order by $nmTabel.kode_kelas, $nmTabel.semester"; 
	
	if ($id!=''){
		$sq="Select * from $nmTabel where $nmFieldID='$id' ";
		extractRecord($sq); 
	}
	
	include "inputmap.php";
	$t="";
	$addInput="<div id=tmap>$t</div><div id=taddguru></div><br>";//nomor baru
	
	//if ($op=='tb') $_REQUEST['Kode']=noTerakhir($nmTabel,$nmfield='kode',$aw=$_REQUEST['kode_matapelajaran'],$digit=4);
	$addFuncAdd=$addFuncEdit="bukaMapMP()";
	exit;
}elseif ($det=="uti") {
	include_once "uti/updatenilai.php";
	exit;
}

$aFieldCaption=explode(",",$sFieldCaption);
$aField=explode(",",$sField);
$aFieldShowInTable=explode(",",$sFieldShowInTable);
$aFieldShowInInput=explode(",",$sFieldShowInInput);
$aFieldWillUpdate=explode(",",$sFieldWillUpdate);
$aLebar=explode(",",$sLebar);
$aAlign=explode(",",$sAlign);


$jField=0;$jLebar=0; 
foreach ($aFieldCaption as $jFld) {
	if ($aFieldShowInTable[$jField]!='0') $jLebar+=($aLebar[$jField])*1;
	$jField++;
}

//judul tabel
$skalaLebar=1/$jLebar*625;
//echo "Jlebar:$jLebar Skala:$skalaLebar";
$jdlTabel="<tr>";
if ($showNo) $jdlTabel.="<td class=tdjudul style='width:30px' width=30 >No.</td>";
$br=0;
foreach ($aFieldCaption as $jFld) {
	$lb=round($aLebar[$br]*$skalaLebar,0);
	if ($aFieldShowInTable[$br]!='0') $jdlTabel.="<td class=tdJudul width='$lb' style='width:$lb"."px;overflow:hidden'>$jFld</td>";
	$br++;
}


if ($showLinkOpr) $jdlTabel.="<td class=tdJudul style='width:70px' width=70>Action</td>";
$jdlTabel.="</tr>";


//operasi
if ($op=='gen') {
	//generate field
	$afd=array();
	$sfd="";
	$i=0;
	$result="";
	$h=mysql_query("select * from $nmTabel where 1=2");
	$nmFld2=$srCek="";
	$strigger="";
	while ($i < mysql_num_fields($h)) {$meta = mysql_fetch_field($h);
		$nmfield=$meta->name;
		$afd[]=$nmfield;
		$sfd.=($sfd==''?'':',').$nmfield;
		$jn="4";//jenis atau lebar
		if ($nmfield=='catatan') {
			$jn="T";
		}elseif ( strstr($nmfield,"tgl")!='') {
			$jn="D";
		}
		
		if ($i==0) {
			$result.="$"."sAllField='';";
			$result.='$i=0;$sAllField.="'.($i>0?'#':'').$i.'|'.$nmfield.'|'.strtoupper($nmfield).'|11|0|0|0|50|C|'.$jn.'";<br>';	
			$result.='  $gGroupInput[$i]=\''.$nmCaptionTabel.'\';<br>';
		} else {
			if ($i==1) $nmFld2=$nmfield;//menentukan field2
			$result.='$i++;	$sAllField.="'.($i>0?'#':'').$i.'|'.$nmfield.'|'.strtoupper($nmfield).'|40|1|1|1|50|C|'.$jn.'";<br>';	
		}
		
		$srCek.="	if ($".$nmfield."=='') $"."pes.='*. ".strtoupper($nmfield)." tidak boleh kosong'; <br>";
		if(strstr(",modified_by,created_by,modified_time,created_time",$nmfield)=='') {
			$strigger.="
			if (OLD.$nmfield<>NEW.$nmfield) THEN 
				SET @changetype = concat(@changetype ,'<br>$nmfield: ',OLD.$nmfield,'->',NEW.$nmfield); 
			END IF; 
			";
		}
	 

		$i++;
	}
	
	$result.="
	$"."isiComboFilterTabel=\"$nmFld2;$nmTabel.$nmFld2\";<br>
	$"."identitasRec='rc$nmTabel';<br>
	$"."configFrmInput='width:800,height:600';<br>
	$"."folderModul='m"."$det';<br>
	$"."nfReport=\"$"."folderModul/showtable.php\";<br>
	<br>
	//include \"$"."folderModul/custom-$det.php\"; 

	";
	
//SELECT @ip:=host FROM information_schema.processlist WHERE id = connection_id();
//SELECT @ip:=host FROM information_schema.processlist WHERE id = connection_id();
	
$triggertb="

-- TRIGGER UPDATE
SELECT @ip:=host FROM information_schema.processlist WHERE id = connection_id();
DROP TRIGGER IF EXISTS `$nmTabel"."_after_update`;
DELIMITER $$
	CREATE TRIGGER `$nmTabel"."_after_update` AFTER UPDATE ON $nmTabel  
	FOR EACH ROW 
	
	BEGIN
		SET @changetype ='';
		$strigger
		INSERT INTO tblog (jenislog,idtrans, ket,user,ip) VALUES ('$det',NEW.$nmFieldID, @changetype,NEW.modified_by,@ip);
	END
	
$$
DELIMITER ;

-- TRIGGER INSERT
DROP TRIGGER IF EXISTS `$nmTabel"."_after_insert`;
DELIMITER $$

CREATE TRIGGER `$nmTabel"."_after_insert` AFTER INSERT ON `$nmTabel` 
FOR EACH ROW 
BEGIN
	INSERT INTO tblog (jenislog,idtrans, ket,user ) VALUES ('$det',NEW.$nmFieldID, 'tambahan baru',NEW.created_by );
END
$$
DELIMITER ;

-- TRIGGER INSERT
DROP TRIGGER IF EXISTS `$nmTabel"."_before_delete`;
DELIMITER $$
	
CREATE TRIGGER `$nmTabel"."_before_delete` BEFORE DELETE ON `$nmTabel` 
FOR EACH ROW 
BEGIN
	INSERT INTO tblog (jenislog,idtrans, ket,user,ip) VALUES ('$det',OLD.$nmFieldID, 'Penghapusan Data',OLD.modified_by,@ip);
END
$$
DELIMITER ;

";

	$result.="<br><textarea cols=120 rows=10 style='background:#ffff99'>$triggertb</textarea>";
	echo $result;
	
	// |40|1|1|1|50|C|4 : field|caption|lebarinput|showinput|update|showtable|lebartb|rata|cekking
	/*
	//cekking data
	if (strstr('|cek|tb|ed|','|$"."op|')!='') {<br>
		$"."pes='';<br>
		$srCek<br>
		echo $"."pes;<br>
		if (strstr('|cek|','|$op|')!='') exit;<br>
	}<br>
	*/

	exit;
}
else
if ($op=='tb') {
	cekVar("Jenis");
	//khusus mp, kode autoincremen
	$jns=$Jenis;
	
	$sfld=$sfldup="";
	$i=0;
	foreach ($aField as $jFld) {
		if ($aFieldWillUpdate[$i]!='0') {
			if ($sfld!='') {
				$sfld.=",";		
				$sfldup.=",";		
			}
			if ((strpos(" ".$jFld,"tgl")>0)||(strpos(" ".$jFld,"tanggal")>0))  {
				$_REQUEST[$jFld]=tgltoSQL($_REQUEST[$jFld]);
				}
			$sfld.="'".$_REQUEST[$jFld]."'";	
			$sfldup.=$jFld;	
		}
		$i++;
	}
	
	//update created by
	$sfldup.=",created_by ";
	$sfld.=",'$userid'";
	
	$sq="insert into $nmTabel($sfldup) values ($sfld)";
	//echo $sq;
	
	$h=mysql_query($sq);
	 if ($useLog) addActivityToLog2($nmTabel,$op,$id,$ket="Penambahan data $nmTabel");

	//echo "Menambah data $nmCaptionTabel berhasil....";
	//khusus siswa ada foto
	echo "<div class='flash alert alert-success'>Data berhasil disimpan....</div>";
	
 
 	if ($det=='guru') {
		$nf=uploadFile($nmvar='foto',$pathFoto,$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget="$nmvar-$id.jpg");		
		$nftt=uploadFile($nmvar='fotott',$pathFoto,$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget="$nmvar-$id.jpg");		
		}
	elseif ($det=='kompetensi') {
		/*
		//kompetensi, jika pilihan sikap, maka nilai juga akan ditambah 
		$kk=cariField("select kode from kompetensi where semester='$semester' and kode_matapelajaran='$kode_matapelajaran' and ki='Sikap Sosial dan Spiritual'");
		if ($kk!='') {
			$sq="insert into nilai_kompetensi_siswa (kode_kompetensi,nis,nilai,n1,n2,n3,n4 ) select '$kode' as kode_kompetensi,nis,nilai,n1,n2,n3,n4 from nilai_kompetensi_siswa where kode_kompetensi='$kk'";
			$h=mysql_query($sq);
		}
		*/
	}
	if ($nexturl!='') {
		redirection($nexturl);
		exit;
	}
}
elseif ($op=='ed') {
	$sfld="";$i=0;
	foreach ($aField as $jFld) {
		if ($aFieldWillUpdate[$i]=='') {
			if ($jFld=='foto') {
				//jika foto
				$nf=uploadFile($nmvar=$jFld,$pathFoto,$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget="$jFld-$id.jpg");		
				//echo $nf;
		
			}elseif ($jFld=='fotott') {
				//jika foto
				$nftt=uploadFile($nmvar=$jFld,$pathFoto,$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget="$jFld-$id.jpg");		
				//echo $nf;
				if ($nftt!='') $sfld.=($sfld==''?"":",")."$jFld='$nftt'";
			} else {
			 
				if ((strpos(" ".$jFld,"tgl")>0)||(strpos(" ".$jFld,"tanggal")>0))  {
					$_REQUEST[$jFld]=tgltoSQL($_REQUEST[$jFld]);		
					}
				
				 $sfld.=($sfld==''?"":",")."$jFld='".$_REQUEST[$jFld]."'"; 
			}
		}
		$i++;
	} 
	
	$sfld.=",modified_by='$userid'";
	 $sq="update $nmTabel set $sfld where $nmFieldID='$id'";
	$h=mysql_query($sq);
	if ($useLog) addActivityToLog2($nmTabel,$op,$id,$ket="Perubahan data $nmTabel");
//echo "$sq <br>Edit data $nmCaptionTabel berhasil....";exit;
	if ($det=='siswa') {
		//uploadFile($nmvar='foto',$pathFoto,$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget="$nis.jpg");		
	}
	echo "<div class='flash alert alert-success'>Data berhasil disimpan....</div>";
	if ($nextOp!='') $op=$nextOp;
	if ($nexturl!='') {
		redirection($nexturl);
		exit;
	}
}

elseif ($op=='del') {
	$sq="delete from $nmTabel where $nmFieldID='$id'";
	$h=mysql_query($sq);
	if ($addDel!='') {
		eval($addDel);		
		}
	echo "<div class='flash alert alert-success'>Data berhasil dihapus....</div>";
	if ($useLog) addActivityToLog2($nmTabel,$op,$id,$ket="Penghapusan data $nmTabel");

	//echo "Penghapusan data di $nmCaptionTabel berhasil....";
} elseif ($op=='importcsv') {
	//$csvfile = $nff;
	$skip_first_row=true;
	$csvfile =$_FILES['nff']["tmp_name"];
	//echo $csv_file;
	include "importCSV.php";
	//$op='showtable';
	echo "<div class='alert alert-success'>";
	echo "Data Berhasil masuk sebanyak $jrSukses record. <a href='#' onclick=\"bukaAjax('content','$hal&op=showtable"."$addParamAdd');return false;\">Klik di sini</a> untuk refresh ";
	echo "</div>";
	exit;
	
}
elseif ($op=='unduhformat') {
	$sqTable="select $sField from $nmTabel";
	$filename=$nfCSV;	
	include "exportCSV.php";
	exit;
	
}
elseif ($op=='itb') {
	//input
	cekVar("id");
	$newop=($id==''?'tb':'ed');
	if ($id!='') {
		$sy=" where $nmFieldID='$id' ";
	} else $sy=" where 1=2 ";
	$sq="Select * from $nmTabel $sy";
	//echo "000>$sq";
	$hq=mysql_query($sq);
	//$r=mysql_fetch_array($hq);
	extractRecord($sq,false,false,0);
	$r=$row;
		
	$idForm=$nmTabel."_".rand(1231,2317);
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','$det');return false;\" ";
	$t="".$addInput0;
	$t.="<div id=ts"."$idForm ></div>";
	$t.="<div name='tfuncAfterEdit' id='tfae"."$rnd' style='display:none' >$funcAfterEdit</div>";
	$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	$t.="<div class=titlepage >Data ".str_replace("Daftar ","",$nmCaptionTabel)."</div>";
	$t.="<input type=hidden name=op id=op value='$newop'> </td>";
	$t.="<input type=hidden name=nexturl id=nexturl value='$nexturl'> </td>";
	$t.="<input type=hidden name='id' id='id' value='$id' > </td>";
	$t.="<table  >";
	for ($i=0;$i<$jField;$i++) {
		$sty="";
		if ($aFieldShowInInput[$i]!='') $sty="style='display:none'";
			$nmField=strtolower($aField[$i]);
			$nmFieldInput=$nmField.'_'.$rnd;
			$cap=$aFieldCaption[$i];
			$troe=($i%2==0?"troddform2":"treventform2");
			$gg="";//ggroup
			if ($gGroup[$i]!='') {
				$gg.="<div style='margin-top:10px;font-weight:bold;text-decoration:underline'>$gGroup[$i]</div>";
				}
			if (substr($cap,0,1)=='-') {
				$gg.="<hr style='width:700px'>";//style='width:700px'
				$cap=substr($cap,1,100);
			}
			if ($gg!='') {
				$t.="<tr class=troddform2 ><td colspan=2>$gg</td></tr>";
			}
			
			$vv=$r[strtolower($aField[$i])];
			 
			$t.="<tr class=troddform2 $sty >";
			$t.="<td class=tdcaption valign=top i='$i'>$cap</td>";
			$inp="<input type=text name=$aField[$i] id=$aField[$i] value='".$vv."' size='$aLebar[$i]' > ";
			
			if ($gField[$i]!='') {
				evalGFInput($gField[$i]);
				$t.="<td>$inp</td>";
			}
			elseif (strstr($aField[$i],'foto')!="") {
				$inp="<input type=file name=$aField[$i] id=$aField[$i] > ";
				$nf=$pathFoto.$aField[$i]."-$id.jpg";
				if (file_exists($nf)) {
					$rndx=genRnd();
					$tvp="tv".$aField[$i].$rndx;
					$inp.="<br>
					<img src='$nf' id=x$tvp style='max-width:50px' 
					onclick=\"$('#$tvp').dialog({width:540});\" />
						<span id=$tvp style='display:none;' >
							<img src='$nf'  width=500 />
						</span>
						<a href=# onclick=\"if (confirm('Yakin akan hapus foto ini?')) { bukaAjaxD('$tvp','index.php?det=$det&op=hapusfoto&idtd=$tvp&nf=$nf&newrnd=$rndx','width:300','awalEdit($rndx)');} \">Hapus</a>
						
						";
				} 
				$t.="<td>$inp</td>";

			}
			elseif (strstr(strtolower($aFieldCaption[$i]),'deskripsi')!='') {
					$inp="<textarea cols=100 rows=2 name=$aField[$i] id=$aField[$i] >$vv</textarea> ";
					$nf=$pathFoto.$aField[$i]."-$id.jpg";
					if (file_exists($nf)) {
						$inp.="<br><img src='$nf' style='max-width:50px' />";
					} 
					$t.="<td>$inp</td>";
			}
			elseif ((strpos(" ".strtolower($aField[$i]),"tgl")>0)||(strpos(" ".strtolower($aField[$i]),"tanggal")>0))  {
				$vv=SQLtotgl($r[strtolower($aField[$i])]);
				$inp="<input type=text name=$nmField id='$nmFieldInput' value='".$vv."' size='10'  > dd/mm/yyyy ";
				$addf.="$('#$nmFieldInput').datepicker();";
				$t.="<td>$inp</td>";
			}
			else
			$t.="<td>$inp</td>";			
			
			$t.="</tr>";
			
		}
	
	$tblihat='';
	if ($showLinkLihat) {
	$tblihat=" 
	<button href=# class='btn btn-warning btn-sm' onclick=\"bukaAjax('content','$hal".$addParamAdd."');return false;\">
	 &nbsp;Lihat $nmCaptionTabel</button>
	 ";
	}
	
	if ($addInput=='') {	
		$t.="<td align=left>&nbsp;</td>
		<td align=left>
		<br><input type=submit value='Simpan Data' class='btn btn-primary btn-sm'>
		$tblihat
		</td>";
	}
	$t.="</table>";
	if ($addInput!='') {
		$addInput=evalGFF($addInput);
		$t.="$addInput";
		$t.="<br><center><input type=submit value='Simpan Data' class='btn btn-primary btn-sm'> $tblihat</center>";
	}
	 
	$t.="</form>";
	$t.="<div id=tfbe"."$rnd name='function before edit' style='display:none'>
		$addf
		</div>";
	echo $t;
	exit;
}

//tanpa else
if ($op=='view') {
	$newop=($id==''?'tb':'ed');
	if ($id!='') {
		$sy=" where $nmFieldID='$id' ";
	} else $sy=" where 1=2 ";
	$sq="Select * from $nmTabel $sy";
	$hq=mysql_query($sq);
	$r=mysql_fetch_array($hq);
	extractRecord($sq);
	$r=$row;
	
	$t="<table  >";
	for ($i=0;$i<$jField;$i++) {
		$sty="";
		if ($aFieldShowInInput[$i]!='') $sty="style='display:none'";
			$cap=$aFieldCaption[$i];
			$troe=($i%2==0?"troddform2":"treventform2");
			if (substr($cap,0,1)=='-') {
				$t.="<tr class=troddform2 ><td colspan=2><hr style='width:700px'></td></tr>";
				$cap=substr($cap,1,100);
				}
			
			$vv=$r[strtolower($aField[$i])];
			
			
			if ((strpos(" ".strtolower($aField[$i]),"tgl")>0)||(strpos(" ".strtolower($aField[$i]),"tanggal")>0))  {
				$vv=SQLtotgl($r[strtolower($aField[$i])]);
			}
			
			$t.="<tr class=troddform2 $sty >";
			$t.="<td class=tdcaption valign=top i='$i'>$cap</td>";
			$inp=$vv;
			/*
			if ($gField[$i]!='') {
				eval($gField[$i]);
				$t.="<td>$inp</td>";
			}
			else
			*/
			if ($gFieldView[$i]!='') {
				
				evalGFInput($gFieldView[$i]);
			//	eval("$"."inp=$gFieldView[$i];");
			} else {
				if ($aField[$i]=='foto') {
					$nf=$pathFoto.$aField[$i]."-$id.jpg";
					if (file_exists($nf)) {
						$inp="<img src='$nf' style='max-width:300px' />";
					} 
				}
			}
			
			$t.="<td>$inp</td>";			
			$t.="</tr>";
			
		}
	
	//if ($addInput=='') $t.="<td align=left>&nbsp;</td><td align=left><br><input type=submit value='Simpan Data' ></td>";
	$t.="</table>";
	echo $t;	
	exit;
}
//if ($op=='showtable') {

//include $um_path."input-std.php";

$sq="Select * from $nmTabel ";
if ($sqlTabel!='')	$sq=$sqlTabel." ".$sqFilterTabel." ".$sqOrderTabel;
	//mengatur limit
cekVar("lim,jperpage,jpperpage");
if ($lim=='') $lim=0;
//if ($jperpage=='') 
$jperpage=50;
$jpperpage=10;

$sqlall=$sq;
$sql=$sqlall." limit $lim,$jperpage"; 
//echo "0000>$sql";
$h=mysql_query($sql);
$hasilall=mysql_query($sqlall);
if (!$hasilall) echo "err:$sqlall";
$nr=mysql_num_rows($h);
$jlhrecord=$nrall=mysql_num_rows($hasilall);
$nfrep="input.php?det=$det&cari=cari";
$lh=buatlinkPage3("tcari",$sqlall,$nfrep."&noth=1",$jpperpage,$jperpage,'lim',$lim);


	$t="";
	if ($cari=='') {
		$t.="
		<div class=titlepage>$nmCaptionTabel </div>
		<div align=right style='margin-top:-30px'> ".str_replace("btn-mini","btn-xs",$lh)."</div>
		";
		if ($sHFilterTabel!='') $t.="<div id=tFilter>$sHFilterTabel<br></div><br>";
		$t.="<div id=tcari >";
	}
	$t.="<br>";
	
	$t.="<table style='width:100%;' align=center>$jdlTabel";
	
		
	/*
	if ($outputto=='csv') {
		$filename=$nfCSV;	
		$sqlTabel=$sq;
		$formatonly=0;
		include "exportCSV.php";
		exit;
		}
*/
	$hq=mysql_query($sql);
	//echo "ooo>$sq";	
	$br=0;
	while ($r=mysql_fetch_array($hq)) {
		
		$id=$r[strtolower($nmFieldID)];
		$br++;
		$idt="rec".$br;
		$troe=($br%2==0?"troddform2":"trevenform2");
		$t.="<tr id="."$idt class=$troe >";
		if ($showNo) $t.="<td align=center>$br</td>";
		for($y=0;$y<$jField;$y++) {
			$nmField=$aField[$y];
			 //echo $aFieldShowInTable[$y];
			if ($aFieldShowInTable[$y]!='0') {
				if ($gFieldView[$y]!='') { 
					evalGFInput($gFieldView[$y]);
					$vv=$inp;
				} else
					$vv=$r[strtolower($nmField)];
				
				$t.="<td $aAlign[$y]>".$vv."</td>";
			}
		}
		
		$tbopr="";
		if ($showLinkOpr) {
			$tbopr.="<div id='tv_$rnd'></div>";
			if ($tbop=='') {
				/*
				$tbopr.="<a href=# onclick=\"bukaAjaxD('tv_$rnd','$hal&op=view&id=$id&newrnd=$rnd".$addParamView."','width:1000,height:450','$addFuncView');
				return false;\"><i class='icon-search'></i>&nbsp;</a>";
				*/
				$tbopr.="&nbsp;&nbsp;
				<button class='btn btn-info btn-circle'
				href=# onclick=\"bukaAjax('content','$hal&newrnd=$rnd&op=itb&id=$id".$addParamAdd."',0,'awalEdit($rnd);$addFuncEdit');
				return false\"><i class='icon-edit'></i></button>";
				$tbopr.="&nbsp;&nbsp;
				<button class='btn btn-danger btn-circle' href=# onclick=\"if (confirm('Yakin akan menghapus?')) { bukaAjax('content','$hal&op=del&id=$id&newrnd=$rnd"."$addParamAdd');
				return false; } \"   ><i class='icon-trash'></i>&nbsp; </button>";
			}  elseif ($tbop=='cas') {//tv_$rnd
				/*
				$tbopr.="<button class='btn btn-danger btn-circle' href=# onclick=\"bukaAjaxD('tv_$rnd','inputcas.php?&nis=$id&newrnd=$rnd".$addParamAdd."','width:1000,height:500');return false\">
				<i class='icon-edit'></i></button>";
				*/
				$tbopr.="<button class='btn btn-danger btn-circle' href=# onclick=\"bukaAjax('content','inputcas.php?&nis=$id&newrnd=$rnd".$addParamAdd."','');return false\">
				<i class='icon-edit'></i></button>";
			}  elseif ($tbop=='cpres') {
				$tbopr.="&nbsp;&nbsp;<a href=# onclick=\"bukaAjax('content','input.php?det=cpres&kode_kelas$kode_kelas&nis=$r[nis]&op=itb&id=$r[nis]".$addParamAdd."',0,'');
				return false\"><img src='img/icon/edit.png' title='Catatan Prestasi'></a>";
			}  elseif ($tbop=='spindah') {
		//	$tbopr.="&nbsp;&nbsp;<a href=# onclick=\"bukaAjax('content','inputpindah.php?&op=ipindah&id=$id".$addParamAdd."',0,'');
			//	return false\"><img src='img/icon/move.png' title='Pindah Sekolah'></a>";
				$tbopr="&nbsp;&nbsp;<a href=# onclick=\"bukaAjax('content','input.php?det=spindah&kode_kelas$kode_kelas&nis=$id&op=itb&id=$r[idp]".$addParamAdd."',0,'');
				return false\"><img src='img/icon/move.png' title='Pindah Sekolah'></a>";
				//$tbopr="-";
			}
		}
		if ($showLinkOpr) $t.="<td align=center>$tbopr</td>";
		$t.="</tr>";	
		}
	$t.="</table>";

//	if ($cari=='') {
	$tbtambah="";
		if ($tbop=='') {
			if ($showLinkTambah) {
				if (!isset($nmCaptionTambah)) $nmCaptionTambah=$nmCaptionTabel;
				$tbtambah="<br><button class='btn btn-success btn-sm '  href=# onclick=\"bukaAjax('content','$hal&op=itb".$addParamAdd."',0,'$addFuncAdd');return false\"   >
				<i class= icon-plus-sign '></i>&nbsp; Tambah Data ".str_replace("Daftar","",$nmCaptionTambah)."</button>
				";
			}
			
			$idForm="fimp_".rand(1231,2317);
			$nfAction="$hal&op=importcsv";
			$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','$det','',false);return false;\" ";
			
			$t.="";
			//tombol hanya tampil jija nfcsv diisi.
			if($userType!='admin') $nfCSV='';
			if ($nfCSV!='') {
				////<div id=tfinput style='display:none'>
				
				$t.="
				$tbtambah
				&nbsp; 
				<button class='btn btn-primary btn-sm ' 
				onclick=\"bukaJendela('$hal&op=unduhformat&outputto=csv&formatonly=0".$addParamAdd."');\" target=_blank> 
				<i class='icon-arrow-down'></i>&nbsp;Unduh Format</button>
				&nbsp; 
				<button class='btn btn-warning btn-sm '  href=# onclick=\"$"."('#$idForm').show();return false;\" 
					value='Import Data ".str_replace("Daftar","",strip_tags($nmCaptionTabel))."'> 
					<i class='icon-arrow-up'></i>&nbsp; Unggah Data 
				</button>
				<form id='$idForm' action='$nfAction' method=Post $asf   style='display:none;margin-top:20;position:relative;'> 
				 <input  type=file name=nff class='btn btn-warning btn-sm'id=nffm  onchange=\"$('#$idForm').submit();\">
				<input type=hidden name=kode_kelas value='$kode_kelas'>
				</form>
				<div id=ts"."$idForm ></div>	<span id=tbr></span>
				";
				//</div>
				
			} else {
				$t.=$tbtambah;	
			}//import		
			$t.=$addTbBawah;
		}
	//}
	$t.="</div>";
	echo $t;
	exit;
//			<a href='#' onclick=\"bukaAjax('tbr','$hal&op=unduhformat".$addParamAdd."');return false;\">Unduh Format</a>

// } //op showtable
?>