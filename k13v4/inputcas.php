<?php
$useJS=2;
include_once "conf.php";
extractRequest();
cekVar("semester,op,cari,nis");
$isiPredikat='Sangat Baik,Baik,Cukup,Kurang';

if (($semester=='')  && (isset($_SESSION['semester']))) $semester=$_SESSION['semester'];
$_SESSION['semester']=$semester;

$sy=" nis='$nis' and semester='$semester'";
$sqcas="Select * from cas where $sy";


function cekCas() {	
	global $nama;
	global $kelas;
	global $semester;
	global $nis;
	global $sakit,$izin,$alpha,$catatan,$pernyataan,$mode_kenaikan_kelas,$kenaikan_kelas;
	global $spengembangan,$keputusan,$catatan_mapel,$catatan,$sdudi,$sprestasi,$tanggapanortu;
	global $isiPredikat;
	global $sy;
	global $rnd;
	global $sqcas;
	$sty="";
	$t="";
	//gantiSiswaKelas(274290,'cas')
	$idForm="cas_$rnd";
	$nfAction="inputcas.php?&op=simpan&cari=cari&nis=$nis&semester=$semester";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','','tutupTbCas()');return false;\" ";
	$t.="<div id=ts"."$idForm ></div>";
	$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	$t.="  

	";
	if ($semester==6) {
		$jdKeputusan="Kelulusan";
		$sKenaikan="Lulus,Tidak Lulus";
	} else {
		$jdKeputusan="Kenaikan";
		$sKenaikan="Naik Kelas,Tinggal Kelas";
	}

	extractRecord($sqcas);
	 
	$t.=" <div id=tdudi>";
	
	$adudi=explode("#",$sdudi."#####");
	$t.="<div class=subtitleform2>Praktek Kerja Lapangan</div>
	<table width=300 cellpadding=0 cellspacing=0 border=0>
	<tr class=troddform2 $sty >
		<td class=tdjudul>Mitra DU/DI</td>
		<td class=tdjudul>Lokasi</td>
		<td class=tdjudul>Lama</td>
		<td class=tdjudul>Keterangan</td>
	</tr>";
	
	for ($i=0;$i<=2;$i++) {
		$apg=explode("|",$adudi[$i]."|||||");
		$t.= "<tr class=troddform2 $sty >
			<td align=center><input type=text name=dudi_$i size=40 value='$apg[0]'></td>
			<td align=center><input type=text name=lokasi_$i size=20  value='$apg[1]'></td>
			<td align=center><input type=text name=lamadudi_$i size=10  value='$apg[2]'></td>
			<td  align=center> ".um412_isiCombo5($isiPredikat,"ketdudi_$i",'','','-Pilih-',$apg[3],"")."</td>			
		</tr>";
	}
	$t.="</table>
	</div>";

	//extra kurikuler
	$apengembangan=explode("#",$spengembangan."#####");
	$t.="<div class=subtitleform2>Kegiatan Ekstrakurikuler</div>
	<table width=300 cellpadding=0 cellspacing=0 border=0>
	<tr class=troddform2 $sty >
		<td class=tdjudul>Kegiatan</td>
		<td class=tdjudul>Predikat</td>
		
	</tr>";
	
	for ($i=0;$i<=2;$i++) {
		$apg=explode("|",$apengembangan[$i]."|||||");
		$t.= "<tr class=troddform2 $sty >
			<td align=center>".um412_isiCombo5("select * from ekskul ","pg_kegiatan_$i",'ekskul','ekskul','-Pilih-',$apg[0],"")."</td>
			<td  align=center> ".um412_isiCombo5($isiPredikat,"pg_predikat_$i",'','','-Pilih-',$apg[1],"")."</td>
			
		</tr>";
	}
	$t.="</table>
	</div>";
	
	//prestasi
	$t.=" <div id=tpres>";
	$aprestasi=explode("#",$sprestasi."#####");
	$t.="<div class=subtitleform2>Prestasi</div>
	<table width=300 cellpadding=0 cellspacing=0 border=0>
	<tr class=troddform2 $sty >
		<td class=tdjudul>Jenis</td>
		<td class=tdjudul>Prestasi</td>
	</tr>";
	
	for ($i=0;$i<=2;$i++) {
		$apg=explode("|",$aprestasi[$i]."|||||");
		$t.= "<tr class=troddform2 $sty >
			<td align=center><input type=text name=jprestasi_$i size=40 value='$apg[0]'></td>
			<td align=center><input type=text name=prestasi_$i size=40  value='$apg[1]'></td>
		</tr>";
	}
	$t.="</table>
	</div>";

	$t.="<div class=subtitleform2>Ketidakhadiran</div>
	<table>
	<tr class=troddform2 $sty >
		<tr><td width=60 >Sakit </td><td><input type=text name=sakit id=sakit size=3 value='$sakit'></td></tr>
		<tr><td >Izin</td><td><input type=text name=izin id=izin size=3 value='$izin'></td></tr>
		<tr><td >Alpha </td><td><input type=text name=alpha id=alpha size=3 value='$alpha'></td> </tr>
	</tr>
	
	</table>";
	
	$t.="<br /><br />
	<span class=subtitleform2 style='width:150px'>Catatan Wali Kelas</span><br />
	<textarea  name=catatan id=catatan cols=80 rows=5 >$catatan</textarea> 
	
	<br><br />
	<span class=subtitleform2 style='width:150px'>Tanggapan Orang Tua Wali</span><br />
	<textarea  name=tanggapanortu id=tanggapanortu cols=80 rows=5 >$tanggapanortu</textarea> 
	
	<div style='display:".($semester%2==0?'inline-table':'none').";width:500px' >
		<br />
	<span class=subtitleform2 >Keputusan $jdKeputusan :</span>".
	um412_isicombo5("R:".$sKenaikan,"keputusan")."
	</div>
	
	<br />
	<br />
	<input type=submit class='btn btn-primary btn-sm'  value='Simpan Data'>
	<table border=0>
	<tr class=troddform2 $sty >
		<td align=center colspan=2></td> 
	</tr>
	</table>
	
	</form>";
	
	return $t;
}

if ($op=='simpan') {
	extractRequest();
	if (cariField($sqcas)=='') {//nambah
		mysql_query("insert into cas(nis,semester) values('$nis','$semester')");
	}
	//update pengembangan	
	$s="";$i=0;
	for ($i=0;$i<=2;$i++) {
		$keg=$_REQUEST['pg_kegiatan_'.$i]; 
		if ($keg!='') {
			$predikat=$_REQUEST['pg_predikat_'.$i]; 
			$deskripsi='';//$_REQUEST['pg_des_'.$i]; 
			if ($s!='') $s.="#";
			$s.=$keg."|".$predikat."|".$deskripsi;		
		}
	}
	$spengembangan=$s;

	//dudi
	$s="";$i=0;
	for ($i=0;$i<=2;$i++) {
		$dudi=$_REQUEST['dudi_'.$i]; 
		if ($dudi!='') {
			$lokasi=$_REQUEST['lokasi_'.$i]; 
			$lama=$_REQUEST['lamadudi_'.$i]; 
			$ket=$_REQUEST['ketdudi_'.$i]; 
			if ($s!='') $s.="#";
			$s.=$dudi."|".$lokasi."|".$lama."|".$ket;		
		}
	}
	$sdudi=$s;
	
	//prestasi
	$s="";$i=0;
	for ($i=0;$i<=2;$i++) {
		$jprestasi=$_REQUEST['jprestasi_'.$i]; 
		if ($jprestasi!='') {
			$prestasi=$_REQUEST['prestasi_'.$i]; 
			if ($s!='') $s.="#";
			$s.=$jprestasi."|".$prestasi;		
		}
	}
	$sprestasi=$s;
	
	$sq="update cas set sakit='$sakit',izin='$izin',alpha='$alpha',catatan='$catatan',sprestasi='$sprestasi',tanggapanortu='$tanggapanortu',keputusan='$keputusan',
				spengembangan='$spengembangan',sdudi='$sdudi'
				where $sy";
	mysql_query($sq);	
	//echo $sq;
		
	echo "<div class='flash alert alert-success'>Data berhasil disimpan....</div>";
	
	
	//exit;
	$cari=$_REQUEST['cari']="";
	$det=$_REQUEST['det']="siswa";
	$tbop=$_REQUEST['tbop']="cas";
	$kode_kelas=$_REQUEST['kode_kelas']=carifield("select kode_kelas from siswa where nis='$nis'");
	$op=$_REQUEST['op']="showtable";
	include "input.php";
	echo "test....";
	exit;
}

if ($cari!=''){
	echo cekCas();
	exit;
}
	extractRecord("select siswa.nama,kode_kelas,kelas.nama as kelas from siswa left join kelas on siswa.kode_kelas=kelas.kode where nis='$nis'" );
?> 
<div id=tbcas class=tbcas >
<table >	 
<tr class=troddform2 $sty >
    <td class=tdcaption >Nama Siswa</td>
    <td>: <?=$nama?></td> 
</tr>
<tr class=troddform2 $sty >
    <td class=tdcaption >Kelas</td>
    <td>: <?=$kelas?></td> 
</tr>
<tr class=troddform2 $sty >
    <td class=tdcaption >Semester</td>
    <td><div id=tsemester>
      <?php
      	$kelas=cariField("select nama from kelas where kode='$kode_kelas'");
	$sm=(strpos("-".$kelas,"XII")>0?"5,6":(strpos("-".$kelas,"XI")>0?"3,4":"1,2"));
	echo um412_isiCombo5("$sm",'semester','','','-Pilih-',$semester,"gantiComboCas('$nis',$rnd)"); 
	
?>
  </div></td> 
</tr>
</table>
</div>
<div id=<?="tcas_$rnd" ?> > <?php if ($semester!='') cekCas() ?></div>