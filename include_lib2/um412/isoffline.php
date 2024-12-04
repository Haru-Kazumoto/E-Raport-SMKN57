<?php
$useJS=2;
include "conf.php";
//if ($isAdmin) {
//	if ($_REQUEST['closereg']!='') $_SESSION['closeReg']=$_REQUEST['closereg'];
	$lockdb= cariField('select lockdb from tbconfig')*1;
	
//}
//$closeReg=$_SESSION['closeReg'];
$p=getConfig("pesadmin");
if ($p!='') { 
	echo "<div class=comment1 style='width:96%;margin-bottom:10px;'><center>$p</center></div>";	
}elseif ($lockdb!=0) { 
	if (($isOnline) && ($lockdb==1)) {
	tampilDialog("<br><br><br><div class='att'  ><blink>Registrasi Telah dialihkan ke offline..... <br> untuk informasi lebih lanjut, hubungi panitia.......</blink></div><br><br><br>");
	exit;
	}
	elseif ((!$isOnline) && ($lockdb==2))  {
	tampilDialog("<br><br><br><div class='att'  ><blink>Registrasi Telah dialihkan ke online..... <br> untuk informasi lebih lanjut, hubungi panitia.......</blink></div><br><br><br>");
	exit;
	}
}
?>