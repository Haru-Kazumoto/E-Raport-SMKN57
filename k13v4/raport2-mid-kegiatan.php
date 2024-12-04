<?php
$useJS=2;
include_once "conf.php";
$shtmlStyle ="";
$shtml="";
$maxjbr=52;//52
	
function cekPBKD($add=1,$useTb=true){
	global $jdes1,$jbr,$tbhead,$charperbr,$clspage,$maxjbr;
	$t="";
	
	if ($jbr>=$maxjbr) {
		if ($useTb) $t.="</table>";
		$t.="#pb#";
		if ($useTb) $t.=$tbhead;
		$jbr=2;
	} elseif (($jbr+$add>=$maxjbr)) {
		if ($useTb) $t.="</table>";
		$t.="#pb#";
		if ($useTb) $t.=$tbhead;
		$jbr=2+$add;
	} else {
		$jbr+=$add;
	} 
	//return "oo ".$jbr.$t;
	return $t;
}


$wt=812;
if ($media2=='pdf') {
	$wt=640;
}
elseif ($media2=='doc') {
	$wt=640;
}

cekVar("semester,op,cari,nis");
if (($semester=='')  && (isset($_SESSION['semester']))) $semester=$_SESSION['semester'];
$_SESSION['semester']=$semester;

$sy=" nis='$nis' and semester='$semester'";
$sqrkegiatan="Select * from nilai_rkegiatan where $sy";
$sty="";
$t="";
$tingkat=carifield("select tingkat from siswa s inner join kelas k on s.kode_kelas=k.kode where s.nis='$nis' ");

$fase=($tingkat==10?"E":($tingkat==11?"F":"G"));
//header
$aW=hitungSkala([80,130,70,70],$wt);
//

$header='
<div style="font-size:11px">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:'.$aW[0].'">Nama Sekolah</td>
    <td style="width:'.$aW[1].'">: '.$namaSekolahSingkat.'</td>
    <td style="width:'.$aW[2].'">Kelas</td>
    <td style="width:'.$aW[3].'">: '.$kelas.'</td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td>: '.$alamatSekolahSingkat.'</td>

    <td>Fase</td>
    <td>: '.$fase.'</td>
  </tr>
  <tr>
    <td>Nama</td>
    <td>: '.$nama.'</td>
 
    <td>Tahun Pelajaran</td>
    <td>: '.$thpl4.'</td>
  </tr>
  <tr>
    <td>Nomor Induk/NISN</td>
    <td>: '.$nis.'</td>
   
    <td></td>
    <td></td>
  </tr>
    
  </table>
</div>
  ';

$t.=$header;
$jbr=5;

extractRecord($sqrkegiatan);
$arnilai=getArrNilaiKeg();
$jrn=count($arnilai);

$styArn="width:40px;text-align:center";
$lbjudulArn=$jrn*40;
$tdjudulArn="";
foreach($arnilai as $ar) {
	$tdjudulArn.="<td style='$styArn'>$ar[0]</td>";
}
$t.="<br>";
$t.=getTbArn($arnilai);
$t.="<br><br>";
$t.=cekPBKD(9,0);
 
//tbrekap
$sq="select * from tbkegiatan";
$dtkeg=sqltoarray2($sq);

$sq="select * from tbgkegiatan";
$dtg=sqltoarray2($sq);

$lbdtg=count($dtg)*100;
$w0=$wt-$lbdtg;

$tbhead="<table width='$wt' border='1' cellpadding='5' cellspacing='0' >";
$t.=$tbhead;
$t.="<tr>";
$t.="<td width='$w0'>Kegiatan</td>";
foreach($dtg as $r2) {
	$t.="<td align='center' style='width:100px'>$r2[kelompok]</td>";
}
$t.="</tr>";
$t.=cekPBKD(1);

foreach($dtkeg as $r) {
	$idk=$r['id'];
	$t.="<tr>";
	$t.="<td width='$w0' >$r[kegiatan]</td>";
	foreach($dtg as $r2) {
		$idg=$r2['id'];
		$nilai=getNilaiKeg($nis,$semester,$idk,$idg,'icn');		
		$t.="<td align='center' width='100' >$nilai</td>";
	}
	$t.="</tr>";
	$t.=cekPBKD(1);
}
$t.="</table><br>";

$t.=cekPBKD(2);

$jj=$jrn+1;			

$i=1;

$w0=$wt-$lbjudulArn;
foreach($dtkeg as $r) {
	$idkegiatan=$r['id'];
	$tbhead="<table width='$wt' border='1' cellpadding='5' cellspacing='0' style='width:100%'>";
	$tbhead.="<tr>
	<td width='$w0'> Kegiatan $i : $r[kegiatan]</td>
	$tdjudulArn
	</tr>";
	$t.="<br>".$tbhead;
	$t.=cekPBKD(2);

	
	foreach($dtg as $r2) {
		$idg=$r2['id'];
		$nk=$r2['kelompok'];
		$t.="<tr><td colspan='$jj' width='$wt' >$nk</td></tr>";			
		$sq3="select * from tbsubkegiatan where idkegiatan='$idkegiatan' and idgkegiatan='$idg'";
		$dts=sqltoarray2($sq3);
		foreach($dts as $r3) {
			$idsk=$r3['id'];
			$deskripsi=$r3['deskripsi'];
			$t.="<tr>
				<td width='$w0'>$deskripsi</td>					
				";			
			$c=carifield("select nilai from nilai_rkegiatan where nis='$nis' and semester='$semester' and idsk='$idsk'");
		
			for($j=0;$j<$jrn;$j++) {
				$arn=$arnilai[$j];
			
				$ck="";
				if ($c==$arn[0]) 
					$ck="V";
				else
					$ck=" ";
					//$opt.="<input type=radio name=nil$idsk"." value='$arn[0]' $ck > $arn[0] ";
				$t.="<td width='40' style='$styArn' >$ck</td>";
				
			}
		
			
			//$t.="<td colspan='$jrn' align=center >$opt</td>";
			
			$t.="</tr>";
			$addbr=ceil(strlen($deskripsi)/50);
			$t.=cekPBKD($addbr);
		}
		$t.=cekPBKD();
			
	}
	
	$t.="</table>";
	$i++;
}

$t=str_replace("'",'"',$t);

$shtml=$t;
//echo showta($shtml);
