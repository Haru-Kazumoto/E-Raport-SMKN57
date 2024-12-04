<?php
if (isset($idsis)) {
	if (($idsis!='')&& ($det!="siswa")) $sqFilterTabel.=($sqFilterTabel==''?' where ':' and ')."$nmTabel.idsiswa='$idsis'";
}
if (isset($id)) {
	if ($id!='') $sqFilterTabel.=($sqFilterTabel==''?' where ':' and ')."$nmTabel.id='$id'";
}
include $um_path."frmreport_v3.0.php";
//echo 	$sqFilterTabel;	
?>