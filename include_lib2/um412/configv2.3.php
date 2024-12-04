<?php 
@session_start();

if (!isset($tohost)) $tohost=$toroot;

@include_once $tohost."content/default-var.php";

if (!isset($timeoutSesi)) $timeoutSesi=60;//otomatis logout 1 jam 

#Session timeout, 2628000 sec = 1 month, 604800 = 1 week, 57600 = 16 hours, 86400 = 1 day
@ini_set('session.save_path', '/home/sv/.folderA_sessionsA');
// server should keep session data for AT LEAST 1 hour
@ini_set('session.gc_maxlifetime', $timeoutSesi*60); 
// each client should remember their session id for EXACTLY 1 hour
//session_set_cookie_params( timeoutSesi*60);
@ini_set('session.cookie_lifetime', $timeoutSesi*60);
@ini_set('session.cache_expire', $timeoutSesi*60);
@ini_set('session.name', 'MyDomainA');


const um412_ver="4.1";
date_default_timezone_set('Asia/Jakarta');

if (!isset($addScriptJS)) $addScriptJS='';
if (!isset($addJSMiddle)) $addJSMiddle='';
if (!isset($addJSBefore)) $addJSBefore='';
if (!isset($addJSAfter)) $addJSAfter='';
if (!isset($addHeaderAfter)) $addHeaderAfter='';

if (!isset($addCSSMiddle)) $addCSSMiddle='';
if (!isset($addCSSBefore)) $addCSSBefore='';
if (!isset($addCSSAfter)) $addCSSAfter='';
if (!isset($addCSS)) $addCSS='';

if (!isset($verToken)) $verToken=1;
if (!isset($loginVer)) $loginVer=1;
if (!isset($tokenVer)) $tokenVer=$verToken;
if (!isset($tohost)) $tohost=$toroot;

if (!isset($nfLogo)) $nfLogo=$toroot."content/img/logo.png";
if (!file_exists($nfLogo)) $nfLogo=$tohost."content/img/logo.png"; 
if (!file_exists($nfLogo)) $nfLogo=$toroot."content/img/logo.png"; 

if (!isset($aDefDLDT)) $aDefDLDT=array(3,9);
$ip=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';

if (!isset($debugMode)) $debugMode=false;//1:perpectual;2:periodik
if (!isset($ipDebug)) $ipDebug="23.106.249.34";//1:ip authority untuk debug
if (($ipDebug==$ip) || !$isOnline) $debugMode=true;
if (!isset($traceDebug)) $traceDebug=$debugMode;
if (!isset($urlLogout)) $urlLogout='index.php?op=logout';//otomatis logout 
if (!isset($docroot)) $docroot='';
if (!isset($isTest)) $isTest=false;



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
cekVar("media,nfxls,det");
$phpVersion= phpversion();
if ($det=='myip') { echo $ip; exit;}
if ($det=='phpver') { echo $phpVersion; exit;}

cekVar('useJS,useHeader,showHeader,jenisPRG,page,nmacara,op,opr,aksi,newrnd,namaorg,cetak,target,tkn');

//cekVar('addMeta,addCSS,addJS,addInclude');
//cekVar('addInclude');
if (isset($topt)) $pt=$topt;
if (!isset($isTest)) $isTest=false;

$temp_path=$pt."temp/";
$inc_path=$toroot."inc1/"; //js
$js_path=$pt."include_js/";
$lib_path=$pt."include_lib2/";
$um_path=$pt."include_lib2/um412/";
$um_class=$pt."include_lib2/um412Class/";

if (!isset($adm_path)) {
	if (isset($admpath)) 
		$adm_path=$admpath;  //php
	else
		$adm_path=$toroot;  //php
}
if (!isset($systemOnly)) $systemOnly=true;//1:perpectual;2:periodik

if ($systemOnly) 
	//jika selalu menggunakan
	$isAdmPath=true;
else {
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
}
//$currentUrl=$_SERVER['HTTP_HOST'];
//echo "admpath : $isAdmPath ";exit;

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
if (!isset($useSecurityLog))$useSecurityLog=false;
if (!isset($tohost)) $tohost=$toroot;


$thousandSeparator=($decimalSeparator==","?".":",");
$aColorTemplate=explode(",",$sColorTemplate);
$stdn="stype='display:none'";
$infoUser='';

//style admin
if (!isset($tpStyle)) $tpStyle="blue";
if (!isset($tppath)) {
	$template_path=$tppath=$pt.($templateName==''?'':"lib/mytemplate/$templateName/");
}
//if (!isset($tppath)) $template_path=$tppath=$toroot.($templateName==''?'':"template/$templateName/");

//style front
if (!isset($tppath2)) {
	$tpStyle2="pink2";
	$template_path2=$tppath2=$pt.($templateName2==''?'':"lib/mytemplate/$templateName2/");
	$tppath2a=$pt."lib/mytemplate/$templateName2/assets/$tpStyle2/";
}

/*
	$aTbLogin=[ "tb"=>"tbuser",
		"sy"=>"",
		"fldid"=>"id",
		"flduid"=>"vuserid",
		"flduname"=>"vusername",
		"fldpss"=>"vpass",
		"fldtype"=>"vusertype",
		//"usrtype"=>"marketing",
		"usrlv"=>10,
	];
*/

if ((substr($phpVersion,0,1))*1>=7) {
	include_once $um_path."MYSQLforPHP7.php";
}

include_once $um_path."lang-detect.php";
if (!isset($hst)) $hst="localhost";
if (!isset($mydb)) $mydb=$db;
//echo "$hst $usr $pss $mydb"; 
include_once $um_class."db.php";
include_once $um_class."exportImportCSV.php";

$db=new db;
$db->hst='localhost';
$db->user=$usr;
$db->password=$pss;
$db->dbName=$mydb;
$db->connect();
//$db->connect($hst="localhost",$usr,$pss,$mydb);


if ($det=="hj"){
	$useJS=2;
	$contentOnly=1;
	include_once $um_class."htmlJSON.php";	
	include_once $adm_path."protected/mode/input-hj.php";
	//htmljson
	exit;
}elseif ($media=="xls") {
	if ($nfxls=="") {
		$rndx5=rand(423111,9993242);
		$nfxls="$det"."_$rndx5";
		if ($det=="") 		$nfxls="export"."_$rndx5";

	}
	$useJS=2;
	$contentOnly=1;
	header ( "Content-type: application/vnd.ms-excel" );
	header ( "Content-Disposition: attachment; filename=$nfxls.xls" );
} 

include_once $um_path."um412_func_inc_v.03.php";
include_once $um_path."um412_func_inc_v.03_add2.php";
include_once $um_path."um412_func_inc_v.03_add3.php";
include_once $um_path."um412_func_inc_v.03_page.php";
include_once $um_path."um412_func_inc_v.03_form.php";
include_once $um_path."um412_updatedb.php";

@include_once $tohost."content/default-var2.php";


//penggunaan class
//use $um_path."class";
include_once $um_class."htmlTable.php";
include_once $um_class."htmlJSON.php";
include_once $um_class."htmlForm.php";
include_once $um_class."htmlPrint.php";
include_once $um_class."htmlMenu.php";	
if ($tokenVer!=3)include_once $um_class."Urlcrypt.php";
include_once $lib_path."uploader/class.upload.php";//upload file

if ($tkn!='') evalToken($tkn);
if (!isset($nfClassLogin)) {
	
	if ($loginVer==2)
		$nfClassLogin=$um_class."login.v2.php";
	else
		$nfClassLogin=$um_class."login.php";

}
include_once $nfClassLogin;

if ($lib_app_path!='')  {
	include_once $lib_app_path."protected/controller/app-func.php";
	include_once $lib_app_path."protected/controller/updatedb.php";
}


if ($det=="buatdb") {
	if ($isOnline) {
		echo "unauthorized online operation";
	}
	else {
		$nfbuatdb=$toroot."installation/db/db.sql";		 
		mysql_query2("create database $mydb;");
		mysql_query2("uses $mydb;");
		$koneksidb=mysql_select_db($mydb);
		$op="upload";
		$nf=$um_path."restoreDB.php";
		include $nf;		 
		exit;
	}	
} 
 
/*
date_default_timezone_set('Asia/Bangkok');
echo date("d-m-Y H:i:s > ");
if (date_default_timezone_get()) {
    echo 'date_default_timezone_set: ' . date_default_timezone_get() . '<br />';
}
*/
//pengamban request
//unsetvar("isSA,isAdmin,isMarketing,userType,userid,userID","r");//request
$isLogin=false;
if (!isset($needCekLogin)) $needCekLogin=true;

$anf=array();
$anf[]=	$toroot."um412-local-func.php";
if ($needCekLogin)	$anf[]=$toroot."usr-cek-local.php";


$anf[]=$adm_path."protected/controller/app-func.php";
$anf[]=$adm_path."protected/controller/app-optimizedb.php";
$anf[]=$lib_app_path."protected/controller/app-func.php";
$anf[]=$template_path."default-var.php";
//$anf[]=$toroot."default-var.php";
$xx=0;
foreach ($anf as $nfi) { 
	if (file_exists($nfi)) {
		include_once $nfi;
	}

}

extractRequest();
if (!isset($userid)) {
	$userid="Guest";
	$isLogin=false;
}

//js dan css
$sJSSrcDef="jquery,dtpicker,jqform,jqui,jqvalidator,cke,jqdt,jqdtresponsive,slimscroll,ztree,magnific-popup,
chartjs,clipboard,jqlightbox,jsevent,maskmoney";//chartjs/morrischart


if (!isset($sJSSrc1)) $sJSSrc1=$sJSSrcDef;//admin
if (!isset($sJSSrc2)) $sJSSrc2=$sJSSrcDef;//front
$sJSSrc=($isAdmPath?$sJSSrc1:$sJSSrc2);

if (!isset($useDataTable)) $useDataTable=(strstr($sJSSrc,'jqdt')!='')?true:false;


$isSA=($userid=='sa'?true:(userType("sa")?true:false));
$isAdmin=($userid=='admin'?true:(userType("sa,admin")?true:false));
$isMarketing=($userType=="Marketing"?true:false);
 
if (isset($_REQUEST['subAdmin'])) {
	$_SESSION['subAdmin']=$subAdmin=strip_tags($_REQUEST['subAdmin']);
} else if (isset($_SESSION['subAdmin'])) $subAdmin=$_SESSION['subAdmin'];

$h=mysql_query2("select * from tbconfig");
@$r=mysql_fetch_array($h);
$judulWeb=$r['judulweb'];
$deskripsiWeb=$r['deskripsiweb'];
$siteKeyword=$googleVerivication='';
$webmasterName=$r['judulweb'];
$webmasterMail=(isset($r['emailevent'])?$r['emailevent']:$r['email']);
$rndjs=$r['rndjs'];

/*
//$headerWeb=$r['headerweb'];
$siteKeyword=$judulWeb.$r['keyword'];
$namaorg=$r['organisasi'];
$alamatFB=$r['alamatfb'];  
$alamatTwitter=$r['alamattwitter'];  
$useFB=($alamatFB==''?false:true); 
*/

if (!isset($lang)) $lang=$r['bahasa'];
if (!isset($formatTgl)) $formatTgl=($lang=='en'?"m/d/Y":"d/m/Y");//format PHP
if (!isset($formatTglLap)) $formatTglLap=$formatTgl;
$tglsekarang=date('d-m-Y');

if ($useJS==2) {
	$showHeader=2;
	$contentOnly=1;
	$newJS=false;
} else {
	if (!isset($addJS)) $addJS='';
	

	cekvar("refreshjs");
	if ($refreshjs!='') {
		$rndjs=genRnd();
		saveConfig("rndjs",$rndjs);
		//echo "<script>alert($rndjs);</script>";
		header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");//agar expired,diberi tanggal lama
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	} 	
	$addRJS="r=$rndjs";
	$umjsVer=2;
	include_once $um_path."um412-jsfunc-v2.1.php";
	include $um_path."js.php";
	if (isset($_REQUEST['showHeader'])) $showHeader=strip_tags($_REQUEST['showHeader']);
	$newJS=true;
}

$addInclude="";
if  ($showHeader!=2)  {
	if (!isset($addMeta)) $addMeta='';
	if (!isset($addHeader)) $addHeader='';
	
	$addHeader='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">';
	
	$addHeader.="
	<!--[if lt IE 7]>      <html class=\"no-js lt-ie9 lt-ie8 lt-ie7\"> <![endif]-->
	<!--[if IE 7]>         <html class=\"no-js lt-ie9 lt-ie8\"> <![endif]-->
	<!--[if IE 8]>         <html class=\"no-js lt-ie9\"> <![endif]-->
	<!--[if gt IE 8]><!--> <html class=\"no-js\"> <!--<![endif]-->
	";
	
	if (!isset($namaPsh)) $namaPsh='namaPsh';
	//<head>
	$addMeta.="
	<meta http-equiv='Content-Type' content='text/html; charset=windows-1252' >
	<meta name='Descryption' content='$deskripsiWeb' >".
	($googleVerivication!=''?"<meta name='google-site-verification' content='$googleVerivication' >":"").
	"<meta name='keywords' content='$siteKeyword,$namaPsh' > 
	<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>
    <meta charset='utf-8'>
	<div id='imgloader' style='display:none'></div>
    <div id='imgwait' style='display:none'></div>
    ";

	if (!isset($favicon)) {
		$favicon=$tohost."content/img/favicon.png";
	}
	if (file_exists($favicon)) $addMeta.=" <link rel='icon' type='image/png' href='$favicon'>";
	
	
	if (!isset($isAdmin)) $isAdmin=false;
	if (!isset($titlePage)) {
		if ($isAdmin ) {
			$jw="$nfApp - $nfAppB";		
			if ($userid=='Guest') {
				$jw=str_replace('<sup>','',$judulWeb."-".$deskripsiWeb);
				$jw=str_replace('</sup>','',$jw);
				$jw=str_replace('<br>','',$jw);
			}
			$titlePage=$jw;
		} else {
			$titlePage=strip_tags($judulWeb);
			
		}
	}
	$addMeta.="<title>$titlePage</title>";
	
	if (($cetak=='')&&($media=='')) {
		include_once $um_path."css.php";
	}
}


if ($showHeader!=2) {

	echo extractHeader();	
	//exit;
	cekVar('olf,media') ;//onload function
	if ($olf!='' && $media=='')  echo " 	<script>$( document ).ready(function() { $olf </script> ";
}

$aAddI=explode(",",$addInclude);

foreach ($aAddI as $flinc ) {
	if (file_exists($flinc)) 
	include_once $flinc;
}

cekTimeoutSesi();

//if (!$isLogin) {
if (!usertype("admin,sa")) {
	if (!isset($recordPengunjung)) $recordPengunjung=false;
	if ($recordPengunjung) {
		if ($cetak=='') include_once $um_path."pengunjung-cek.php";
	}
}