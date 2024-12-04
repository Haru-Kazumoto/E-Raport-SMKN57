<?php
$useJS=2;
include_once "conf.php";	
cekVar("semester,jenisMP,kode_matapelajaran,nis,jkom,kode_kompetensi");

$sJenisKI="Pengetahuan,Keterampilan";//,Sikap Sosial dan Spiritual
$idForm="fnilai_".rand(1231,2317);
$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\""; 

if ($combo=='mp') {
	$adds="";
	$sq=$combogsmp.($gsmp==''?' where ':' and ')." jenis like '$jenismp%' order by nama ";
	
	$t=um412_isiCombo5($sq,'kode_matapelajaran','kode','nama','-Pilih-','',"gantiComboKKM('isi',$rnd)");
}

if (!isset($dsresultcombo)) 
	echo $t;
else
	$result=$t;
?>