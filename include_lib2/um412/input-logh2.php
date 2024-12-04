<?php
$useJS=2;
include_once 'conf.php';
if (!usertype("sa")) exit;
$det="logh2";
$nmTabel='tbh_logh2';
$nmTabelAlias='lg';
$nmCaptionTabel="Data Logs";
$nmFieldID='id';

$showNoInTable=true; 
$showOpr=1;
//$jpperpage=50;
$stb=true;
$showFrmCari=$stb;
$showTbHapus=$stb;
$showTbView=$stb;
$showTbUbah=$stb;
$showTbPrint=$stb;
$showTbTambah=$stb; 

$showExportDB=false; 
$showTbFilter=false;

$showTbUnduh=false;
$showTbUnggah=false;
$defOrderDT="[1, 'desc']";
$configFrmInput="width:wMax-100,title: \'$nmCaptionTabel\'";
$isTest=false; 

$sqTabel="select * from (
select xlg.*,li.id as idip from tbh_logh2 xlg left join tbh_logip li on xlg.ip=li.ip
) as  lg ";


include $um_path."input-std0.php";

/*
if ($isSekolah) {
	addFilterTb("m.kdsekolah='admin'");
	$kdsekolah=$userid;
	addSaveTb("kdsekolah");
	$addInputNote="kode sekolah secara otomatis akan ditambahkan di field kode mapel";
	cekVar("kdmp");
	if (strstr($kdmp,$kdsekolah."-")=="") {
		setVar("kdmp","$kdsekolah-$kdmp");
	} 
}

*/

$sAllField='';
$i=0;$sAllField.="0|id|ID|11|0|0|0|50|C|I-4,U|0|0";
$i++; $sAllField.="#1|tgl|TGL|40|1|1|1|30|C|S-0|1|1";
//$i++; $sAllField.="#2|user|USER|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#10|ip|IP|40|1|1|1|30|C|S-0|1|1";
$gFieldLink[$i]="logip,id,idip,view,ajaxd,width:1000";//det,fldkey,fldkeyval
$i++; $sAllField.="#9|jenislog|JENISLOG|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#3|ket|KET|40|1|1|1|30|C|TA-0|1|1";
$gFieldView[$i]="=potong(strip_tags(-{ket}-),50);";

//$i++; $sAllField.="#4|created_by|CREATED_BY|40|1|1|1|30|C|S-0|1|1";
//$i++; $sAllField.="#5|created_time|CREATED_TIME|40|1|1|1|30|C|S-0|1|1";
//$i++; $sAllField.="#6|modified_by|MODIFIED_BY|40|1|1|1|30|C|S-0|1|1";
//$i++; $sAllField.="#7|modified_time|MODIFIED_TIME|40|1|1|1|30|C|S-0|1|1";
//$i++; $sAllField.="#8|idtrans|IDTRANS|40|1|1|1|30|C|S-0|1|1";



//$idimport=rand(123101,98766661);
//$sFieldIdImport='idimport'
$formatTglCSV='dmy';
$capImport='Import Data Logs';//caption tombol import
$sFieldCSV=strtolower('id,tgl,user,ket,created_by,created_time,modified_by,modified_time,idtrans,jenislog,ip');
$sFieldCaptionCSV= strtolower('ID,TGL,USER,KET,CREATED_BY,CREATED_TIME,MODIFIED_BY,MODIFIED_TIME,IDTRANS,JENISLOG,IP');
$nfCSV='import_Data_Logs.csv';
/*
$sFieldCsvAdd=',kdsekolah';
$sFieldCsvAddValue=",'$defKdSekolah'";
$syImport="
	carifield(\"select kdkelas  from tbkelas where kdsekolah='K0104019' and kdkelas='-#kdkelas#-' \")!='';
	carifield(\"select nisn  from tbsiswa where kdsekolah='K0104019' and nisn='-#nisn#-' \")=='';
	
	";
*/
include $um_path."input-std.php";


/*
catatan2

$tPosDetail=11;//untuk menentukan posisi tabel detail setelah field apa

if ($opcek==1) {//untuk menambah validasi
	$s=unmaskrp($byangkut)-unmaskrp($byangkuttunai);
	if ($s<0) $addCek.="<br>Bon Supir tidak boleh melebihi biaya angkut....";
}

*/




?>
