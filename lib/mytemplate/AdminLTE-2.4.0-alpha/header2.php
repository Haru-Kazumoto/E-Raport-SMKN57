<?php 
//include_once "../sekolah/".$tppath."header.php" ;
//if (!isset($nfHeader)) 
	$nfHeader=$tppath."header1.php" ;
include ($nfHeader);

if (!isset($jamselesai)) $jamselesai="";

echo "<span id=tjamselesai style='display:nonex'>$jamselesai</span>
<span id=ttime style='display:nonex'></span>
";
				
?>

<style>
header {
	height:60px;
	background-color:#ED9D10;
}
.navbar {
    background-color: #3C8DBC;
 
	color:#fff;
}
.navbar img {
	height:46px;
	float:left;
	margin-right:20px;
}
#judulp1 {
	font-size:30px;
	margin: -7px 0px -7px 0px;
}
#judulp2 {
	font-size:12px
}

.login-box-header {
	background:#E6E6E6;
	color:#000;
	font-size:24px;
	padding:10px;
}
.infouser {
	background:#000;
	padding:10px;
}

.content-wrapper {
	margin-top: 10px;
	padding: 20px;
	background:#fff;

}
</style>