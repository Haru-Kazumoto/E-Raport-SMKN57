<?php
if (!isset($useAjaxMnuKiri)) $useAjaxMnuKiri=true;
if (!isset($mnuVer)) $mnuVer=1;
if (!isset($_SESSION['tgmenu'])) $_SESSION['tgmenu']='';
//$tgmenu=$_SESSION['tgmenu'];//targetmenu
$tgmenu='content-wrapper';//targetmenu
include_once $toroot."arrmenu.php";

if ($mnuVer==1) {
	include $um_path."mnu-kiri-def-v1.php";	
} else {
	echo 	showTA($aMenu);
	include $um_path."mnu-kiri-def-v2.php";
}