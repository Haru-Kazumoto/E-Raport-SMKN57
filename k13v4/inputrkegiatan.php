<?php
$useJS=2;
include_once "conf.php";
cekVar("semester,op,cari,nis");
$isiPredikat='Sangat Baik,Baik,Cukup,Kurang';

if (($semester=='')  && (isset($_SESSION['semester']))) $semester=$_SESSION['semester'];
$_SESSION['semester']=$semester;

$sy=" nis='$nis' and semester='$semester'";
$sqrkegiatan="Select * from nilai_rkegiatan where $sy";


function cekrkegiatan() {	
	global $nama;
	global $kelas;
	global $semester;
	global $nis;
	global $sakit,$izin,$alpha,$catatan,$pernyataan,$mode_kenaikan_kelas,$kenaikan_kelas;
	global $spengembangan,$keputusan,$catatan_mapel,$catatan,$sdudi,$sprestasi,$tanggapanortu;
	global $isiPredikat;
	global $sy;
	global $rnd;
	global $sqrkegiatan;
	$sty="";
	$t="";
	//gantiSiswaKelas(274290,'rkegiatan')
	$idForm="rkegiatan_$rnd";
	$nfAction="inputrkegiatan.php?&op=simpan&cari=cari&nis=$nis&semester=$semester&newrnd=$rnd";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','','selesaiEdit($rnd)',2);return false;\" ";
	$t.="<div id=ts"."$idForm ></div>";
	$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	$t.="";
	

	extractRecord($sqrkegiatan);
	$tbArn='';
	$arnilai=getArrNilaiKeg();
	$tbArn=getTbArn($arnilai);
	$tdJudulArn ="";
	foreach($arnilai as $ar) {
		$tdJudulArn.="<td style='width:40px;text-align:center' >$ar[0]</td>";
	}
	
	$jrn=count($arnilai);
	
	/*
	$t.="<br><br>";
	$t.=$tbArn;
	$t.="<br><br>";
	*/
	//tbrekap
	
	$sq="select * from tbgkegiatan";
	$dtg=sqltoarray2($sq);
	$sq="select * from tbkegiatan";
	$dtkeg=sqltoarray2($sq);
	
	
	/*
	$tb2="<table width='100%' border=1 cellpadding=5px><tr>";
	
	
	$tb2.="<tr>";
	$tb2.="<td>Kegiatan</td>";
	foreach($dtg as $r2) {
		$tb2.="<td align=center>$r2[kelompok]</td>";
	}
	$tb2.="</tr>";
	foreach($dtkeg as $r) {
		$tb2.="<tr>";
		$tb2.="<td>$r[kegiatan]</td>";
		foreach($dtg as $r2) {
			$nk='';
			$tb2.="<td align=center>$nk</td>";
		
		}
		$tb2.="</tr>";
	}
	$tb2.="</table>";
	$t.=$tb2;
	*/
	
	$jj=$jrn+1;			
	$tb3="";
	$i=1;
	
	foreach($dtkeg as $r) {
		$idkegiatan=$r['id'];
		$tb3.="<br><table width='100%' border=1 cellpadding=5 >";
		$tb3.="<tr>
		<td >Kegiatan $i : $r[kegiatan]</td>
		$tdJudulArn
		</tr>";
		
		
		foreach($dtg as $r2) {
			$idg=$r2['id'];
			$nk=$r2['kelompok'];
			$tb3.="<tr><td colspan=$jj >$nk</td></tr>";			
				
			$sq3="select * from tbsubkegiatan where idkegiatan='$idkegiatan' and idgkegiatan='$idg'";
			$dts=sqltoarray2($sq3);
			foreach($dts as $r3) {
				$idsk=$r3['id'];
				$tb3.="<tr>
					<td >$r3[deskripsi]</td>					
					";			
				$opt="<input type=hidden name=idsk[] value='$idsk'>";
				$c=carifield("select nilai from nilai_rkegiatan where nis='$nis' and semester='$semester' and idsk='$idsk'");
			
				for($j=0;$j<$jrn;$j++) {
					$arn=$arnilai[$j];
				
					//$tb3.="<td ><input size=4 type=text></td>";
					$ck="";
					if ($c==$arn[0]) $ck="checked";
					$opt.="<input type=radio name=nil$idsk"." value='$arn[0]' $ck > $arn[0] ";
				}
				$tb3.="<td colspan='$jrn' align=center >$opt</td>";
				
				$tb3.="</tr>";
			}
		
		}
		
		$tb3.="</table>";
		$i++;
	}
	$t.=$tb3;

	
	
	
	$t.="
	<br>
	<br>
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
	$aidsk=$_REQUEST['idsk'];
	$t="";
	$lengkap=true;
	$ssq="";
	foreach($aidsk as $idsk){
		if (isset($_REQUEST["nil".$idsk])) {
			$nilai=$_REQUEST["nil".$idsk];
			//$t.="<br>Nilai $nis id $idsk adalah $nilai";
			$c=carifield("select id from nilai_rkegiatan where nis='$nis' and semester='$semester' and idsk='$idsk'");
			if ($c=="") {
				$ssq.="insert into nilai_rkegiatan(nis,semester,idsk,nilai) values('$nis','$semester','$idsk','$nilai');";
			}
			else{
				$ssq.="update nilai_rkegiatan set nilai='$nilai' where id='$c';";
			}
		} else {
			
			$lengkap=false;
		}
	}
	
	if (!$lengkap) {
		echo "<div class='flash alert alert-warning'>data belum lengkap ....</div>";
		exit;
	} else {
		echo $t;
		//echo $ssq;
		querysql($ssq);
		/*
		$sq="update rkegiatan set sakit='$sakit',izin='$izin',alpha='$alpha',catatan='$catatan',sprestasi='$sprestasi',tanggapanortu='$tanggapanortu',keputusan='$keputusan',
					spengembangan='$spengembangan',sdudi='$sdudi'
					where $sy";
		mysql_query($sq);	
		//echo $sq;
		*/	
		"<div class='flash alert alert-success'>Data berhasil disimpan....</div>";
		
	}
	echo fae("$('#rkegiatan_$rnd').show();");	
	exit;
}

if ($cari!=''){
	echo cekrkegiatan();
	exit;
}
	extractRecord("select siswa.nama,kode_kelas,kelas.nama as kelas from siswa left join kelas on siswa.kode_kelas=kelas.kode where nis='$nis'" );
?> 
<div id=tbrkegiatan class=tbrkegiatan >
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
	echo um412_isiCombo5("$sm",'semester','','','-Pilih-',$semester,"gantiComboRKegiatan('$nis',$rnd)"); 
	
?>
  </div></td> 
</tr>
</table>
</div>
<div id=<?="trkegiatan_$rnd" ?> > <?php if ($semester!='') cekrkegiatan() ?></div>