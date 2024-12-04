<?php
//index utama
	
include_once   "conf.php";

cekVar("det,page,op");
	
if ($isLogin) {
	//echo "goo $userid>>";exit;
	include $tppath."index.php";
	//include_once  "foot1.php";
}
else {	

	include $toroot."usr-login-local.php";
}
?>