<?php
$useJS=2;
$showHeader=2;

include_once "conf.php";

if (!isset($admpath))$admpath="adm/"; 
cekVar("page1,form");

 
switch ($form) {
case "user":
	$op="cek";
	$nf=$um_path."usr-login.php";
	//echo $nf;
	break; 
default:
	//echo 'nama form validasi belum diisi......';
	echo "";
	exit;
	break;
}
include $nf;

?>
 