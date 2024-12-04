<?php
$useJS=2;
include_once "conf.php";
extractRequest();
//guru
$nmTabel="Kelas";
$sField=$sFieldCaption="Nama";
$nfAction="index1.php?page=input&det=guru";
$hal="input.php";

$aFieldCaption=explode(",",$sFieldCaption);
$aField=explode(",",$sField);

$jField=0;$jdlTabel="<tr>";
foreach ($aFieldCaption as $jFld) {
	$jField++;
	$jdlTabel.="<td class=tdJudul>$jFld</td>";
}
//$jdlTabel.="<td class=tdJudul>Action</td>";
$jdlTabel.="</tr>";
$jdlTabel='';


 
 
	//tabel
	$t="<div class=titlepage >Daftar Kelas</div><hr > <table width=300>$jdlTabel";
	
	$sq="Select * from $nmTabel order by tingkat,nama";
	
	$hq=mysql_query($sq);
	//echo "nr:".mysql_num_rows($hq);
	$br=0;
	while ($r=mysql_fetch_array($hq)) {
		$id=$r['kode'];
		$br++;
		$idt="rec".$br;
		$t.="<tr id="."$idt>";
		for($y=0;$y<$jField;$y++) {
			$nmField=$aField[$y];
			$t.="<td align=left class=tdoddform2 ><a href=# onclick=bukaAjax('content','input.php?det=siswa&kode_kelas=$r[kode]');>".$r[strtolower($nmField)]."</a></td>";
		}
		//$tbopr="<a href=# onclick=\"bukaAjax('$idt','$hal?op=edit&id=$id');return false\"   ><i class='icon-edit'></i>&nbsp;</a>";
		//$tbopr.="<a href=# onclick=\"if confirm('hapus data ini?') { bukaAjax('$idt','$hal?op=del&id=$id'); return false } \"   ><i class='icon-trash'></i>&nbsp; </a>";
		//$t.="<td align=center>$tbopr</td>";
		$t.="</tr>";
			
		}
	$t.="</table>";
	//$t.="<a href=# onclick=\"bukaAjax('content','$hal?op=itb');return false\"   ><i class='icon-edit'></i>&nbsp;Tambah data $nmTabel</a>";
	$infoKelas=$t;
 
?>
