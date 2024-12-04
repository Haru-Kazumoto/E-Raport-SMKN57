<?php
$useJS=2;
include_once 'conf.php';
if (!usertype("sa")) exit;

$det="logip";
$nmTabel='tbh_logip';
$nmTabelAlias='li';
$nmCaptionTabel="Data Log IP";
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
$defOrderDT="[3, 'desc']";
$configFrmInput="width:wMax-100,title: \'$nmCaptionTabel\'";


$isTest=false; 

$sqTabel="select * from (
select xli.*,xli.ip as xip from tbh_logip xli
) as  li ";


include $um_path."input-std0.php";

 
//$isTest=true;
$sAllField='';
$i=0;$sAllField.="0|id|ID|11|0|0|0|50|C|I-4,U|0|0";
//$gGroupInput[$i]='Data Log IP';
			
$i++; $sAllField.="#1|xip|IP|40|1|0|1|30|C|S-0|1|1";
if (op("ed,tb")) {
	setvar("ip",$xip);
	addsavetb("ip","ed,tb");
}
$i++; $sAllField.="#2|svuid|SVUID|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#3|jlhip|JLHIP|7|1|1|1|7|C|S-0|1|1";
$i++; $sAllField.="#4|ket|KET|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#5|mark|MARK|7|1|1|1|7|C|S-0|1|1";


/*
$i++; $sAllField.="#5|created_time|CT|7|0|0|1|7|C|S-0|1|1";
$i++; $sAllField.="#5|modified_time|MT|7|0|0|1|7|C|S-0|1|1";

$gFieldInput[$i]="=um412_isicombo5('select * from tbsales','idsales');";
$gFieldView[$i]="='Menu';";
$gAddField[$i]="<input type=hidden name='kd_$rnd'><a class='btn btn-primary btn-sm' onclick=\"getDokter();return false\">show</a>";
$gFieldLink[$i]="guru,id,kdguru";//det,fldkey,fldkeyval

$gDefField[$i]=date($formatTgl);
$gFuncFld[$i]="suggestFld('logip','idperusahaan|nama_perusahaa',238183,this.value);";

$gStrView[$i]= carifield("select concat (id,' - ',namabrg) from tbpenjualanb where id='$idpenjualanb' ");
addCekDuplicate('bulan,tahun,idpegawai');
if (1==2) {
	addcek.='<br>A TIDAK BOLEH SAMA DENGAN B';
}
//contoh untuk input hidden dan hanya menampilkan string tertentu (H2)
$i++; $sAllField.="#1|idpenjualanb|NAMA BARANG|7|1|1|namabrg|57|C|H2,0|1|1";
$addInputAkhir="<div id=thitung_$rnd class='text text-alert'></div>";

*/
//$isiComboFilterTabel="ip;tbh_logip.ip"; 

/*
$addTbOpr1=" 
<span  class='btn btn-primary btn-mini btn-sm' 
onclick=\"tbOpr('view|&op=view&custom=cetak1','logip|logip',$rnd,$rndInput,'$configFrmInput');\" value='Cetak' /><i class='fa fa-print'></i> Cetak Dokumen</span> ";
*/

 
$aFilterTb=array(
		array('mark','mark|like','Mark :  '.um412_isicombo6("Blocked,-",'xmark',"#url#")),
);


//$idimport=rand(123101,98766661);
//$sFieldIdImport='idimport'
$formatTglCSV='dmy';
$capImport='Import Data Log IP';//caption tombol import
$sFieldCSV=strtolower('id,ip,svuid,jlhip,ket,mark');
$sFieldCaptionCSV= strtolower('ID,IP,SVUID,JLHIP,KET,MARK');
$nfCSV='import_Data_Log_IP.csv';
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

$tPosDetail=6;//untuk menentukan posisi tabel detail setelah field apa

if ($opcek==1) {//untuk menambah validasi
	$s=unmaskrp($byangkut)-unmaskrp($byangkuttunai);
	if ($s<0) $addCek.="<br>Bon Supir tidak boleh melebihi biaya angkut....";
}

*/




?>
