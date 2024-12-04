<?php
$isLogin=false;
if (isset($dbku4) && ($dbku4!='')) {	
	$nextLoad="";
	$isLogin=false;
	$strLog="";
	$timeOut=false;
	if (!isset($lewat)) 
		$lewat=1;
	else
		$lewat++;
		
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
		 $_SESSION['timestamp']=time();
	} else {	
	
		$uid=$userid=$userId=$_SESSION["usrid"];
		if (isset($_SESSION["usrps"])) 
			$ups=$userName=$_SESSION["usrps"];
		else
			$ups=$userName="";
	
	}

	if (isset($_SESSION['dbku4']) ) {
		if (!$koneksi) {
			die("nggak konek...");
		}
		 
	}

	 $sqlusr="select * from tbuser where userid='$uid' and pass='".md5($ups)."' ";
	$hasilusr=@mysql_query($sqlusr);
	if (mysql_num_rows($hasilusr)>0) {
		$rwusr=mysql_fetch_array($hasilusr);
		$vidusr=9999;
		$userID=$userid=$rwusr["userid"];
		$userName=$rwusr["username"];
		$userType=$rwusr["usertype"] ;
		$isLogin=true;
		 $strLog="$userid berhasil login....";
	} else {
	//	echo "$dbku4 user tidak";
	}
	
	if (!$isLogin) { //mencari di data siswa
		$sqlusr="select * from siswa where nis='$ups' and nis<>'' ";
		$hasilusr=@mysql_query($sqlusr);
		if (mysql_num_rows($hasilusr)>0) {
			$rwusr=mysql_fetch_array($hasilusr);
			
			$vidusr=$rwusr['nis'];
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
			$vidusr=$rwusr['kode'];
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
		$_SESSION["vidusr"]=$vidusr;
		$_SESSION["usrid"]=$userid;
		$_SESSION["usrps"]=$ups ;
		$_SESSION["usrnm"]=$userName;
		$_SESSION["usrtype"]=$userType;
		
		if ($userType!='siswa') unset($_SESSION['nis']);
		if ($userType!='guru') unset($_SESSION['nip']);
		$levelOwner=($userType=='admin'?10:($userType=='kaprog'?3:($userType=='guru'?2:($userType=='siswa'?1:0))));			//}
		resetTimeoutSesi();
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
		//header("location:index.php");
	}
}
//echo "cek user...$userID";
//echo "usrcek:usertype $userType";