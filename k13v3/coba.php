<?php
include_once "conf.php";
ini_set("upload_max_filesize","30M");
ini_set('max_execution_time', 60*20); 
$s="";
$skdmp="A01,A02,A03,A04,A05,A07,A08,A09,A10,A11,B01,B02,B03,B04,C101,C103,C104,C105,C106,C107,C108,C109,C201,C202,C203,C204,C205,C207,C208,C210,C212,C214,C215,C216,C217,C221,C222,C223,C224,C225,C301,C302,C303,C304,C305,C306,C307,C309,C310,C311,C312,C313,C314,C315,C317,C318,C319,C320,C321,C322,C323,C324,C325,C326,ML01,ML02,ML03";
$akdmp=explode(",",$skdmp);
$akpt=array();
foreach ($akdmp as $kdmp){
	//cek apakah sudah ada atau belum di data mp,jika belum dibuat
	$c=carifield("select  kode from matapelajaran where kode='$kdmp'");
	//echo "$c<br>";
	if ($c=="") {
		$ss="insert into matapelajaran(kode,jp1,jp2,jp3,jp4,jp5,jp6,jk1,jk2,jk3,jk4,jk5,jk6) values('$kdmp',3,3,3,3,3,3,3,3,3,3,3,3)";
		mysql_query($ss);
		//echo "<br>$ss";
	}
	for ($smt=1;$smt<=6;$smt++) {
		$kodep=$kdmp."P".$smt;
		$kodek=$kdmp."K".$smt;
	
		$akpt[$kodep]=max(carifield("select jp$smt from matapelajaran where kode='$kdmp'")*1,1);
		$akpt[$kodek]=max(carifield("select jk$smt from matapelajaran where kode='$kdmp'")*1,1);
	
	}
}


$s="";
$s.="ALTER TABLE nilai_kompetensi_siswa DROP INDEX nis_idx;<br>";
$s.="ALTER TABLE nilai_kompetensi_siswa DROP INDEX kode_kompetensi_idx;<br>";
$sq="select * from tbcoba   ";
$hq=mysql_query($sq);
while ($r=mysql_fetch_array($hq)) {
	
	foreach ($akdmp as $kdmp){
		$nilai=$r[$kdmp];
		if ($nilai>0) {
			$kdkp=$kdmp.$r["pk"].$r["smt"];			
			$jlhkp=$akpt[$kdkp];
			//echo "$kdkp:$jlhkp ";
			for ($i=1;$i<=$jlhkp;$i++) {
				$kd=$kdkp."000$i";
				$ck=carifield("select kode_kompetensi from nilai_kompetensi_siswa where kode_kompetensi='$kd' and nis=$r[nis]");
				if ($ck!='') {
					//$s.="delete from nilai_kompetensi_siswa where kode_kompetensi='$kd' and nis=$r[nis];<br>";
					$sqi="update nilai_kompetensi_siswa set nilai=$nilai where kode_kompetensi='$kd' and nis=$r[nis];<br>	 ";
				} else
					$sqi="insert into nilai_kompetensi_siswa(kode_kompetensi,nis,nilai) values('$kd',$r[nis],$nilai);<br>	 ";
				$s.=$sqi."";
				//echo $sqi."<br>";
			}
		}
	}
	//echo "<br>";
	//kode_kompetensi 	nis 	nilai
	//A01P30001 	7254 	80.00 	
	
}
echo $s;
var_dump($akpt);

?>