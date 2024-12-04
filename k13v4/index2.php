<?php
include_once "conf.php";
cekVar("det");
switch ($det) {
case "login":
	$nf=$toroot."usr-login-local.php";
	break;
case "cetakkhs":
	$nf="inputraport2siswa.php";
	break;
case "inputnilaisikap":
	$nf="inputnilaisiksis.php";
	break;
case "backuprestore":
	$nf="inputbr.php";
	break;
case "updkelas":
	$nf="input-updkelas.php";
	break;
default:
	$nf="inputsekolah.php";
	break;
//index2
}
include $nf;
?>
