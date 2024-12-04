<?php
$useJS=2;
include_once 'conf.php';
secureUser("sa");
$det="logclick";
$nmTabel='tbh_logclick';
$nmTabelAlias='lc';
$nmCaptionTabel="Data Log Click";
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
$defOrderDT="[0, 'desc']";
$configFrmInput="width:wMax-100,title: \'$nmCaptionTabel\'";


$isTest=true; 

$sqTabel="select * from (
select xlc.*,li.id as idip  from tbh_logclick xlc left join  tbh_logip li on xlc.ip=li.ip
) as  lc ";

include $um_path."input-std0.php";

/*
if ($isSekolah) {
	addFilterTb("m.kdsekolah='sa'");
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
//$gGroupInput[$i]='Data Log Click';
			
//$i++; $sAllField.="#1|vurl|VURL|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#3|tglclick|TGL CLICK|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#2|vuid|VUID|40|1|1|1|30|C|S-0|1|1";
$i++; $sAllField.="#5|ip|IP|40|1|1|1|30|C|S-0|1|1";
$gFieldLink[$i]="logip,id,idip,view,ajaxd,width:1000";//det,fldkey,fldkeyval
$i++; $sAllField.="#6|ket|KET|40|1|1|1|30|C|TA-0|1|1";
$gFieldView[$i]="=potong(strip_tags(-{ket}-),50);";
$i++; $sAllField.="#4|mark|MARK|40|1|1|1|30|C|S-0|1|1";



/*
$gFieldInput[$i]="=um412_isicombo5('select * from tbsales','idsales');";
$gFieldView[$i]="='Menu';";
$gAddField[$i]="<input type=hidden name='kd_$rnd'><a class='btn btn-primary btn-sm' onclick=\"getDokter();return false\">show</a>";
$gFieldLink[$i]="guru,id,kdguru";//det,fldkey,fldkeyval

$gDefField[$i]=date($formatTgl);
$gFuncFld[$i]="suggestFld('logclick','idperusahaan|nama_perusahaa',592560,this.value);";

$gStrView[$i]= carifield("select concat (id,' - ',namabrg) from tbpenjualanb where id='$idpenjualanb' ");
addCekDuplicate('bulan,tahun,idpegawai');
if (1==2) {
	addcek.='<br>A TIDAK BOLEH SAMA DENGAN B';
}
//contoh untuk input hidden dan hanya menampilkan string tertentu (H2)
$i++; $sAllField.="#1|idpenjualanb|NAMA BARANG|7|1|1|namabrg|57|C|H2,0|1|1";
$addInputAkhir="<div id=thitung_$rnd class='text text-alert'></div>";

*/
//$isiComboFilterTabel="vurl;tbh_logclick.vurl"; 

/*
$addTbOpr1=" 
<span  class='btn btn-primary btn-mini btn-sm' 
onclick=\"tbOpr('view|&op=view&custom=cetak1','logclick|logclick',$rnd,$rndInput,'$configFrmInput');\" value='Cetak' /><i class='fa fa-print'></i> Cetak Dokumen</span> ";
*/


$aFilterTb=array(
		array('tingkat','lc.tingkat|like','Tingkat :  '.um412_isicombo6("$sTingkat",'xtingkat',"#url#")),
);


//$idimport=rand(123101,98766661);
//$sFieldIdImport='idimport'
$formatTglCSV='dmy';
$capImport='Import Data Log Click';//caption tombol import
$sFieldCSV=strtolower('id,vurl,vuid,tglclick,mark,ip,ket');
$sFieldCaptionCSV= strtolower('ID,VURL,VUID,TGLCLICK,MARK,IP,KET');
$nfCSV='import_Data_Log_Click.csv';
/*
$sFieldCsvAdd=',kdsekolah';
$sFieldCsvAddValue=",'$defKdSekolah'";
$syImport="
	carifield(\"select kdkelas  from tbkelas where kdsekolah='K0104019' and kdkelas='-#kdkelas#-' \")!='';
	carifield(\"select nisn  from tbsiswa where kdsekolah='K0104019' and nisn='-#nisn#-' \")=='';
	
	";
*/
include $um_path."input-std.php";

 



?>
