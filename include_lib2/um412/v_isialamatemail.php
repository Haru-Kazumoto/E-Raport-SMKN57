<?php
$useJS=2;
include "conf.php";
kill_unauthorized_user();

$sql=mysql_query("select * from registrasi order by email");
$kpd="";
$x=0;
$reAkhir="";
while ($row=mysql_fetch_array($sql)){
	$x++;
	if ($r[email]!="") {
		if ($reAkhir<>$$r[email]){
			if ($kpd!="") $kpd.=", ";
			$kpd.="$row[nama] <$row[email]>";
			$reAkhir=$$r[email];
		}
	}
}
echo $kpd;
?>