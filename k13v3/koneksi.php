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
	$docroot='120-smkn57/k13v3';//$_SESSION['docroot']=($jPenilaian=='perkd'?'k13rev/':'k13rev/');
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

?>