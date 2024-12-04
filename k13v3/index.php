<?php
//index utama
include_once   "conf.php";
cekVar("det");
if ($det=="uti") {
	include_once "uti/updatenilai.php";
	exit;
}
if (($userID!='Guest')&&($userID!='')) {
	//echo "nis ".$_SESSION['nis'];
	//if ($userType!='siswa') {
		include $tppath."index.php";
		include_once  "foot1.php";
	
	//}
	//else {
		
		/*
		include_once  "head1.php";
		include "inputsekolah.php";
		include "kelas.php";
		echo '<table align=center><tr><td valign="top"><div id=content>'.$infoSekolah.'</div>
		</td><td valign="top"><div id=rightcontent>'.$infoKelas.'</div></td></tr></table>';
		*/
		/*
		echo '<table align=center style="margin-top:170px;width:300px;background:#fff;border:2px #060 solid;" ><tr><td valign="top">
		<div style="padding:40px 70px;">';
		include "inputraport2.php";
		echo '<div></td></tr></table>';
		*/
	//}
}
else {	
	include $toroot."usr-login-local.php";
}
 ?>