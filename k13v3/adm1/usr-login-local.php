<?php
//$useJS=2;
//include_once 'conf.php';
//cekVar("op");
global $pes;
$opr=(isset($_REQUEST['op'])?$_REQUEST['op']:"");
if ($opr=="logout"){
     if (isset($userID)) {
	mysql_query ("insert into tblog(idmember,ket) values('$userID','Logout')");
	 unset($_SESSION["s_usrid"]);
	 unset($_SESSION["s_usrnm"]);
	 unset($_SESSION['nis']);
	 unset($_SESSION['nip']);
	 unset($_SESSION['dbku']);
	 
	 unset($_SESSION["usrid"]);
	unset($_SESSION["usrps"]);
	unset($_SESSION["usrnm"]);
	unset($_SESSION["usrtype"]);
	 $pes="user berhasil keluar.....";
	 }
	 include_once "conf.php";
	 //redirection('index.php');
	 //exit;
	 
} elseif ($opr=="login") {	
	$op=$_REQUEST['op']=""; 
	if ($koneksidb) {
		user_cek();
		//$_REQUEST["usrid"],$_REQUEST["usrps"]);
		if ($userID=='Guest') {
			$pes.="user tidak dikenal....";
		}
	}
}
global $tppath;
include $tppath."login.php";

 exit;
 ?> 
