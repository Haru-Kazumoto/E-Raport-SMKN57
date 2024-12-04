<?php
@session_start;
//$toroot="";
$lang="id";
$useLog=true;//menggunakan log
$maxdes=400;
$tinggiDes=240;//250 raport
$jPenilaian="perkd";
//$jPenilaian="permp";
$jinput="perkd";//pern 
$aAgama=explode(",","Islam,Kristen,Katholik,Hindu,Budha,Kepercayaan");
$aGender=explode(",","Perempuan,Laki-laki");
$jPenilaianSikapDet=$jPenilaianPD=$jPenilaianAPD= $jPenilaianObservasi="Spiritual,Jujur,Gotong Royong,Toleransi,Santun,Percaya Diri,Tanggung Jawab,Disiplin";
$judulLap[0]="KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN";
$addRJS='';
$isLogin=false;

$timeoutSesi=60;		  
                
if (!isset($formatTgl)) $formatTgl=($lang=='en'?"m/d/Y":"d/m/Y");//format PHP
if (!isset($timeoutSesi)) $timeoutSesi=60;//otomatis logout 1 jam 
if (!isset($useTransportation)) $useTransportation=2;
if (!isset($templateName)) $templateName='AdminLTE-2.4.0-alpha';
if (!isset($templateName2)) $templateName2='impression2t';
if (!isset($sColorTemplate)) $sColorTemplate="#000000,#45484d,#21324c,#606060,#d3d3d3,#eaeaea";
if (!isset($allowOverPayment)) $allowOverPayment=false;
if (!isset($useResHotel)) $useResHotel=2;
if (!isset($useGroupRegistration)) $useGroupRegistration=2;
if (!isset($showOptPrize)) $showOptPrize=2;//tampilkan semua harga:early,regular,on
if (!isset($useExtrabed)) $useExtrabed=false;
if (!isset($useRichTextarea)) $useRichTextarea=false;
if (!isset($tmaincontent)) $tmaincontent="maincontent";
if (!isset($lib_app_path)) $lib_app_path="";
if (!isset($useDecimal)) $useDecimal=2;
if (!isset($decimalSeparator)) $decimalSeparator=",";
if (!isset($accMethod)) $accMethod=1;//1:perpectual;2:periodik
if (!isset($systemOnly)) $systemOnly=true;//1:perpectual;2:periodik
if (!isset($useSecurityLog))$useSecurityLog=false;
if (!isset($tohost)) $tohost=$toroot;



if (!isset($addScriptJS)) $addScriptJS='';
if (!isset($addJSMiddle)) $addJSMiddle='';
if (!isset($addJSBefore)) $addJSBefore='';
if (!isset($addJSAfter)) $addJSAfter='';
if (!isset($addHeaderAfter)) $addHeaderAfter='';

if (!isset($addCSSMiddle)) $addCSSMiddle='';
if (!isset($addCSSBefore)) $addCSSBefore='';
if (!isset($addCSSAfter)) $addCSSAfter='';

if (!isset($verToken)) $verToken=1;
if (!isset($tokenVer)) $tokenVer=$verToken;

if (!isset($nfLogo)) $nfLogo=$toroot."content/img/logo.png";

if (!isset($aDefDLDT)) $aDefDLDT=array(3,9);
$ipDebug=$ip=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';

if (!isset($debugMode)) $debugMode=false;//1:perpectual;2:periodik
if (!isset($ipDebug)) $ipDebug="23.106.249.34";//1:ip authority untuk debug
if (($ipDebug==$ip) || !$isOnline) $debugMode=true;
if (!isset($traceDebug)) $traceDebug=$debugMode;
if (!isset($urlLogout)) $urlLogout='index.php?op=logout';//otomatis logout 


$dM=$debugMode;
if ($debugMode) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
} else {
	error_reporting(0);
}
//mengamankan request
if (isset($_REQUEST)) {
	foreach($_REQUEST as $nmvar => $val) {
		//@$_REQUEST[$nmvar]=mysql_real_escape_string($_REQUEST[$nmvar]);
		if (strstr(",orderredpage,sq,",",$nmvar,")=="") {
			$_REQUEST[$nmvar]=str_replace("'",'`',$_REQUEST[$nmvar]);
			$_REQUEST[$nmvar]=str_replace(" or ",' -or- ',$_REQUEST[$nmvar]);
		}
	}
}


//if (!function_exists('cekVar')) {
	//cekvar("a,b,c=2");//default c=2;
	//cekking variabel apakah sudah di define atau belum, c
	function cekVar($snmvar,$norequest=0,$stag=1,$ceksesi=false){
		//echo $snmvar."<br><br>";
		// mysql_real_escape_string
		//penggunaan cekvar(nama variabel, mengunakan request atau tidak, menghilangkan tag atau tidak);
		$anmvar=explode(",",$snmvar);
		foreach($anmvar as $xnmvar) {
			$anf=explode("=",$xnmvar."=");
			$nmvar=$anf[0];$def=$anf[1];
			$h="global $"."$nmvar; ";
			//mengamankan request
			$addSesi="(isset($"."$nmvar)?$"."$nmvar:'$def')";
			//htmlspecialchars($_POST['username']);
			if ($ceksesi)
				$addSesi="(isset($"."_SESSION['"."$nmvar'])?$"."_SESSION['"."$nmvar']:$addSesi)";
			
			if ($norequest==0) {
				if ($stag==1) 					
					//$h.="$"."$nmvar=(isset($"."_REQUEST['"."$nmvar'])?htmlspecialchars(strip_tags($"."_REQUEST['$nmvar'])):$addSesi);";
					$h.="$"."$nmvar=(isset($"."_REQUEST['"."$nmvar'])?(strip_tags($"."_REQUEST['$nmvar'])):$addSesi);";
				else {
					$h.="$"."$nmvar=(isset($"."_REQUEST['"."$nmvar'])?$"."_REQUEST['$nmvar']:$addSesi);";
				}
			} else {
				$h.="$"."$nmvar=((isset($"."$nmvar)?$"."$nmvar:'$def'));";
			}
			if ($ceksesi){
//				$h.="if (isset($"."_REQUEST['"."$nmvar'])) $"."_SESSION['"."$nmvar']=htmlspecialchars($"."_REQUEST['"."$nmvar']);";
				$h.="if (isset($"."_REQUEST['"."$nmvar'])) $"."_SESSION['"."$nmvar']=($"."_REQUEST['"."$nmvar']);";
			}
			//if ($nmvar=='sidnew')	echo $h;
			@eval($h);
		}
	}
	
	function cekVar2($snmvar,$ceksesi=false,$defVar=''){
		return cekVar($snmvar,$norequest=0,$stag=1,$ceksesi);
	}
	
//}
cekVar("media,nfxls,det,media,page");
$phpVersion= phpversion();

if ($det=='myip') { echo $ip; exit;}
if ($det=='phpver') { echo $phpVersion; exit;}


cekVar("dbku,useJS,showHeader,page,media,media2,cetak,thpl,newrnd");

 

if (!isset($adm_path)) $adm_path=$toroot;  //php
if (!isset($isAdmPath)) {
	$admp=str_replace("../","",$adm_path);
	if ($admp=='') {
		$isAdmPath=false;
	} else {
		//require_once(dirname(__FILE__).'/tcpdf_autoconfig.php');
		//echo getcwd();//E:\xampp\htdocs\133-klinikaba\adm 
		//echo dirname(__FILE__);//E:\xampp\htdocs\include_lib2\um412 
		//echo realpath(__FILE__);//E:\xampp\htdocs\include_lib2\um412\configv2.3.php 
		$isAdmPath= (strstr($_SERVER['PHP_SELF'],$admp)==''?false:true);
		//$isAdm=(=$toroot."";
	}	
} //php

cekvar("op,opr");
$umjsVer=1; 
//testing target ke file
//$target=$_REQUEST['target']='file';
if ($newrnd!='') 
	$rnd=$newrnd;
else if (!isset($rnd)) 
	$rnd=rand(1211,998911);
//$newrnd=rand(1211,998911);
if ($opr=='') $opr=$op;

if (!isset($useTransportation)) $useTransportation=2;
if (!isset($templateName)) $templateName='AdminLTE-2.4.0-alpha';
if (!isset($templateName2)) $templateName2='impression2t';
if (!isset($sColorTemplate)) $sColorTemplate="#000000,#45484d,#21324c,#606060,#d3d3d3,#eaeaea";
if (!isset($allowOverPayment)) $allowOverPayment=false;
if (!isset($useResHotel)) $useResHotel=2;
if (!isset($useGroupRegistration)) $useGroupRegistration=2;
if (!isset($showOptPrize)) $showOptPrize=2;//tampilkan semua harga:early,regular,on
if (!isset($useExtrabed)) $useExtrabed=false;
if (!isset($useRichTextarea)) $useRichTextarea=false;
if (!isset($tmaincontent)) $tmaincontent="maincontent";
if (!isset($lib_app_path)) $lib_app_path="";
if (!isset($useDecimal)) $useDecimal=2;
if (!isset($decimalSeparator)) $decimalSeparator=",";
if (!isset($accMethod)) $accMethod=1;//1:perpectual;2:periodik
if (!isset($systemOnly)) $systemOnly=true;//1:perpectual;2:periodik
if (!isset($useSecurityLog))$useSecurityLog=false;
if (!isset($tohost)) $tohost=$toroot;


$thousandSeparator=($decimalSeparator==","?".":",");
$aColorTemplate=explode(",",$sColorTemplate);
$stdn="stype='display:none'";
$infoUser='';


$useDataTable=false;
$nfAppA1=$nfAppA2=$nfAppB="";
//$aCStyle=explode(",","#006600,#558ed5,#b7e9b7,#d0d8e8,#CFC");
$aCStyle=explode(",","#1983C6,#2F8FCC,#67ADDA,#B1D5EC,#D5E8F4,#F4F9FC");//biru muda

$lib_path= $toroot.$pt."include_lib2/";
$um_path= $lib_path."um412/";
$um_class= $lib_path."um412class/";
$js_path=$toroot.$pt."include_js/";
$css_path= $toroot.$pt."include_js/";
$temp_path=$toroot.$pt."temp/";
$inc_path=$toroot."inc1/"; //js
$adm_path=$toroot."adm1/";  //php
$templateName="bs-admin-bcore1";
//$tppath=$toroot.$pt."lib/mytemplate/$templateName/";
$tppath=$toroot."template/$templateName/";

include_once $um_path."um412_func_inc_v.03.php";
include_once $um_path."um412_func_inc_v.03_add2.php";
include_once $um_path."um412_func_inc_v.03_add3.php";
include_once $um_path."um412_func_inc_v.03_form.php";
 
include_once $toroot."um412-local-func.php";
include_once $toroot."koneksi.php";

$sJenisMP="Kelompok A (Wajib),Kelompok B (Wajib),C1 (Dasar Bidang Keahlian),C2 (Dasar Program Keahlian),C3 (Paket Keahlian),Mulok";
$sJenisKdMP="Kelompok A;A,Kelompok B;B,C1 (Dasar Bidang Keahlian);C1,C2 (Dasar Program Keahlian);C2,C3 (Paket Keahlian);C3,Mulok;M";

//extractRequest();

$enck="this is my website";


$headerWeb=$judulWeb1=$judulWeb=$nmProgram="Sistem Informasi Penilaian Siswa";
$googleVerivication="";
$siteKeyword=$judulWeb."";

cekVar("thpl");
if (isset($_REQUEST['thpl'])) {	
	if ($_REQUEST['thpl']!='') {
		$dbku=$_SESSION['dbku']="k13rev_".str_replace("-","",$_REQUEST['thpl']);
		$thpl=$_SESSION['thpl']=$_REQUEST['thpl'];
	} else {
		$dbku="";
	}
} elseif ((isset($_SESSION['dbku'])) && ($dbku==''))  {
	$dbku=$_SESSION['dbku'];
}
//echo "db:".$dbku;
if ((isset($_SESSION['thpl'])) && ($thpl==''))  $thpl=$_SESSION['thpl'];
if ((!isset($_SESSION['dbku'])) && ($dbku==''))  $_SESSION['dbku']=$dbku;

if ($dbku!='') {
	@$koneksidb=mysql_select_db($dbku);
} else {
	$koneksidb=false;
	$pes="<center>Pilih Tahun Pelajaran terlebih dahulu</center>";
}


if  (!$koneksidb){
	if ($dbku!='')  {
		$pes="<center>Database tahun $thpl tidak ditemukan....<br>Nama DB: $dbku</center>";
	}
	//$_REQUEST['op']='';
	cekVar("media,media2");
	if ($media2!='xls') {
		include "inc1/template-css.php";
		include "js.php";
	}
	include "usr-login-local.php";
	exit;
}

include_once  "usr-cek-local.php";

// include_once $lib_path."web3backup/backup.php";
//$ketpgx=($jPenilaian!='perkd'?"Nilai Harian#UTS#UAS":"Tulis#Lisan#Tugas");
//mysql_query("UPDATE `tbconfig1` SET `ketpg` = '$ketpgx' where `ketpg` <> '$ketpgx'");
$db=$dbku;
//include $um_path."configv2.3.php";

//custom perbaikan
$qc="select * from sekolah";
$hqc=mysql_query($qc);
$rqc=mysql_fetch_array($hqc);
$judulWeb2=$namaSekolah=$namaSekolahSingkat=$rqc['nama'];
$alamatSekolah=$alamatSekolahSingkat=$rqc['alamat'];
$alamatSekolahSingkat1=$rqc['alamat']." ".$rqc['kelurahan']." ".$rqc['kecamatan']." ".$rqc['kota']." ".$rqc['provinsi'];
$alamatSekolahSingkat2=" Telp. ".$rqc['telepon']." Fax ".$rqc['fax'];
$alamatSekolah=$deskripsiWeb=$rqc['alamat']." ".$rqc['kelurahan']." ".$rqc['kecamatan']." ".$rqc['kota']." ".$rqc['provinsi']." Telp. ".$rqc['telepon']." Fax ".$rqc['fax'];
$webmasterMail="noreply@yahoo.com";
$webmasterName="administrator rs";
$adminName="administrator rs";
$webmasterCity=$rqc['kota'];
$kepsek_nama=$rqc['kepsek_nama'];
$kepsek_nip=$rqc['kepsek_nip'];
$kepsek_tt=$rqc['kepsek_tt'];


$judulLap[1]=$judulWeb2;//"SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 57 JAKARTA";
$judulLap[2]=$deskripsiWeb;//"Jl. Taman Margasatwa No.38 B Jatipadang, Pasar Minggu, Jakarta Selatan";
//$judulLap[3]="Telp.021 7805396 Fax.021 7806249";
$heading_laporan="
<center>
<div class=titlepage>
<br>$judulLap[0]
<br>$judulLap[1]
<br>$judulLap[2]
</div>
<br><br></center>
";
if (!isset($formatTgl)) $formatTgl=($lang=='en'?"m/d/Y":"d/m/Y");//format PHP

if ($media2=='doc') {
	$useJS=2;
	
}

if (!isset($connectOnly)) {
	if (isset($_REQUEST['showHeader'])) $showHeader=strip_tags($_REQUEST['showHeader']);
	
	if ($useJS==2) $showHeader=2;
	if  ($showHeader!=2)  {
		$showHeader=2;
		$jw=$judulWeb."-".$judulWeb2;
		if (!$isAdmin) {
			$jw=str_replace('<sup>','',$jw);
			$jw=str_replace('</sup>','',$jw);
		}
		?><head>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd" />
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
	 	<title><?=$jw?></title>
		</head>
		<?php
		//echo "<FONT SIZE=7>HEAD</FONT>";
		echo "<body>";
		
		cekvar("media2");
		if ($media2!='xls') {
			echo "\n<link type='text/css' href='$js_path"."jquery/themes/base/jquery.ui.all.css' rel='stylesheet' />";
			echo '<link rel="stylesheet" href="'.$tppath.'assets/plugins/switch/static/stylesheets/bootstrap-switch.css" />';
			include $tppath."css.php";
			//if ($isAdmin ) echo "<link rel='stylesheet' type='text/css' href='".$js_path."um412-cssreg2.css' />";  
			 include_once "inc1/template-css.php";
		}
	}
		
	if ($cetak=='xls') {
		//ini_set('memory_limit', '528M');
		include_once $lib_path.'PHPExcel.php';
		include_once $lib_path.'PHPExcel/IOFactory.php';
	}
	
	if ($useJS=="")  {
		include_once $lib_path.'PHPExcel.php';
		
		$useJS=2;
		
		include_once  "js.php";	
	}
} //connectonly



$developer="slamet@smkdki.net";
//filter mata pelajaran
$gsmp=$combogsmp="";
$combogsmp="select kode,nama from matapelajaran ";
if (strstr("guru,kaprog",$userType)) {
	$gsmp=carifield("select skdmapel from guru where uidg='$userID' ");
	$gsmp=str_replace("|","' or matapelajaran.kode='","(matapelajaran.kode='$gsmp')");
	$combogsmp.=($gsmp==''?'':" where $gsmp ");
	//echo $gs;
}


cekVar("media,orientasi");

if ($media=='print') {
	cekvar("xtitle");
	if ($xtitle=='') $xtitle=$namaSekolah;
	
	
	if (isset($media2)) {
		
		if ($media2=='')  echo "\n<link type='text/css' href='".$js_path."style-cetak.css' rel='stylesheet' />";
	
	}
	else {
		
		echo "<html moznomarginboxes mozdisallowselectionprint>";
		echo "<title>$xtitle</title>";	
		echo "\n<link type='text/css' href='".$js_path."style-cetak.css' rel='stylesheet' />";
		
	}
	
	if ($orientasi=='landscape'){
		echo "\n<link type='text/css' href='".$js_path."style-cetak-landscape.css' rel='stylesheet' />";
	}
}
extractRequest();
include_once "updatedb.php";

?>