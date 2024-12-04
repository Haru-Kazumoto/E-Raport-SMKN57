<?php

@session_start();
$nextLoad="";
$isLogin=false;
$strLog="";
$timeOut=false;
if (!isset($lewat)) 
	$lewat=1;
else
	$lewat++;
	
//echo "lewat....$lewat";

if (isset($_REQUEST["usrid"])) {
	$uid=$_REQUEST["usrid"];
	$ups=$_REQUEST["usrps"];

	/*
	if ($uid=='') {
		$uid="admin";
		$ups="14081987";
	}
	*/
	$nextLoad="index.php";
	$strLog.="$uid Mencoba Login dengan password $ups ";
	
} else {	
	$uid=$userid=$userID=$_SESSION["usrid"];
	if (isset($_SESSION["usrps"])) 
		$ups=$userName=$_SESSION["usrps"];
	else
		$ups=$userName="";
	
}

if (isset($_SESSION['dbku']) ) {
	if (!$koneksi) {
		die("nggak konek...");
	}
}
$sqlusr="select * from tbuser where userid='$uid' and pass='".md5($ups)."' ";
$hasilusr=@mysql_query($sqlusr);
if (mysql_num_rows($hasilusr)>0) {
	$rwusr=mysql_fetch_array($hasilusr);
	$userID=$userid=$rwusr["userid"];
	$userName=$rwusr["username"];
	$userType=$rwusr["usertype"] ;
	$isLogin=true;
	$strLog="$userid berhasil login....";
}
if (!$isLogin) { //mencari di data siswa
	$sqlusr="select * from siswa where nis='$ups' ";
	$hasilusr=@mysql_query($sqlusr);
	if (mysql_num_rows($hasilusr)>0) {
		$rwusr=mysql_fetch_array($hasilusr);
		$userID=$userid=$_SESSION["s_usrid"]=$rwusr["nis"];
		$nis=$rwusr["nis"];
		$userName=$rwusr["nama"];
		$userType="siswa";
		$isLogin=true;
		$strLog="$userName ($userid) berhasil login sebagai siswa ....";
	}
}
if (!$isLogin) { //mencari di data guru
	$sqlusr="select * from guru where (uidg='$uid' and uidg<>'') and ((nip='$ups' and upwdg='') or (upwdg='$ups' and upwdg<>'') )";
	//echo $sqlusr;
	$hasilusr=@mysql_query($sqlusr);
	if (mysql_num_rows($hasilusr)>0) {
		$rwusr=mysql_fetch_array($hasilusr);
		$userID=$userid=$_SESSION["s_usrid"]=$rwusr["uidg"];
		$nip=$rwusr["nip"];
		$userName=$rwusr["nama"];
		$userLevel=$rwusr["ulevelg"];
		$userType=($rwusr["ulevelg"]==''?"guru":$rwusr["ulevelg"]);
		$isLogin=true;
		$strLog="$userName ($userid) berhasil login sebagai guru ....";
		
	}
}


if ($isLogin) {
//if (isset($_REQUEST["usrid"])) { 
	$_SESSION["usrid"]=$userid;
	$_SESSION["usrps"]=$ups ;
	$_SESSION["usrnm"]=$userName;
	$_SESSION["usrtype"]=$userType;
	if ($userType!='siswa') unset($_SESSION['nis']);
	if ($userType!='guru') unset($_SESSION['nip']);
	$levelOwner=($userType=='admin'?10:($userType=='kaprog'?3:($userType=='guru'?2:($userType=='siswa'?1:0))));			//}
	
	if (!isset($_SESSION['timestamp'])) $_SESSION['timestamp']=time();
	
	$sisawkt=15*60*10-(time() - $_SESSION['timestamp']) ;//15menit
	//echo "sisawaktu :".$sisawkt." ts:".time()." ts2:". $_SESSION['timestamp'];
	//$sisawkt=0;//15menit
	
	if($sisawkt<=0) { //subtract new timestamp from the old one
		$timeOut=true;
	    $_SESSION['timeout'] = 1; 
		unset($_SESSION['timestamp']);
		$isLogin=false;
   } else {
	   	$_SESSION['timeout'] = 0; 
	    $_SESSION['timestamp'] = time(); 
   }
}

if (!$isLogin){
	$userID=$userid=$_SESSION["usrid"]="Guest";
	$userName=$_SESSION["usrnm"]="Guest";
	$userType=$_SESSION["usrtype"]="Guest";
	unset($_SESSION['nis']);
	unset($_SESSION['nip']);

}

$isAdmin=($userType=='admin');



if ($strLog!='') {	
	mysql_query("insert into tblog(idmember,ket) values('$uid','$strLog');");
}

if ($nextLoad!='') {
	header("location:index.php");
}

//echo "cek user...$userID";

//echo "usrcek:usertype $userType";
?>