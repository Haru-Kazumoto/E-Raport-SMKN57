<?php
$isOnline=false ; 
if ($isOnline) {
	$pt="";
	$hst="localhost";
	$docroot=''; 
	$folderHost="http://localhost/smk57/k13v3/".$docroot;
	$usr="root";
	$pss="";//global
} else {
	$pt="../../";
	$hst="localhost";
	$docroot='120-smkn57/k13v4';//$_SESSION['docroot']=($jPenilaian=='perkd'?'k13rev/':'k13rev/');
	$folderHost="http://localhost/".$docroot;
	$usr="root";
	$pss="";//global
}

$koneksi =@mysql_connect($hst,$usr,$pss); 

if (!$koneksi) {	
	echo "<center><div style='border:2px #000 solid;width:400px'>";
	echo "<div style='background:#000;color:#fff;padding:10px;'>ATTENTION</div>";
	echo "<br>".($isOnline?"Online":"Offline")." server is bussy.....<br/>";
	echo "<br /><input type=button  value='Try Again Now' onclick=\"document.location.reload(true);\" >&nbsp;";
	echo "<input type=button  value=Back onclick=\"window.history.back()\">";
	echo "<script>document.write('<br/><br/>System will automatically reload page in second....<br/>');setTimeout('location.reload(true);',1000);</script>";
	echo "<br/><br/></div></center>";
	exit;
}
$_SESSION['docroot']=$docroot;

cekVar("thpl4");
if (isset($_REQUEST['thpl4'])) {	
	if ($_REQUEST['thpl4']!='') {
		$dbku4=$_SESSION['dbku4']="k13rev4_".str_replace("-","",$_REQUEST['thpl4']);
		$thpl4=$_SESSION['thpl4']=$_REQUEST['thpl4'];
	} else {
		$dbku4="";
	}
} elseif ((isset($_SESSION['dbku4'])) && ($dbku4==''))  {
	$dbku4=$_SESSION['dbku4'];
}
//echo "db:".$dbku;
if ((isset($_SESSION['thpl4'])) && ($thpl4==''))  $thpl4=$_SESSION['thpl4'];
if ((!isset($_SESSION['dbku4'])) && ($dbku4==''))  $_SESSION['dbku4']=$dbku4;
if ($dbku4!='') {
	@$koneksidb=mysql_select_db($dbku4);
	$mydb4=$dbku4;
	//echo "sampai lho.....$dbku4";

	include_once $um_class."db.php";
	$db=new db;
	$db->hst='localhost';
	$db->user=$usr;
	$db->password=$pss;
	$db->dbName=$mydb4;
	$db->connect();
	$db->query("select 1");

} else {
	$koneksidb=false;
	$pes="<center>Pilih Tahun Pelajaran terlebih dahulu</center>";
}


if  (!$koneksidb){
	if ($dbku4!='')  {
		$pes="<center>Database tahun $thpl4 tidak ditemukan....</center>";
	}
	//$_REQUEST['op']='';
	cekVar("media,media2");
	include "usr-login-local.php";
	exit;
}
?>