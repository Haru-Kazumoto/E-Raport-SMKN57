<?php
if (!$isAdmin) {
	$ip   = $_SERVER['REMOTE_ADDR'];  
	$tanggal = date("Y-m-d");  
	$waktu  = date("H:i");  
	$bln=date("m");  
	$tglp=date("d");  
	$blan=date("Y-m");  
	$thn=date("Y");  
	$tglk=$tglp-1;  
	
	$s = mysql_query2("SELECT * FROM tbpengunjung WHERE ip='$ip' AND tanggal='$tanggal'");  
	if(mysql_num_rows($s) == 0){  
		$sq="INSERT INTO tbpengunjung(ip, tanggal, hits, online) VALUES('$ip','$tanggal','1','$waktu')";  
	}   
	else{  
		$sq="UPDATE tbpengunjung SET hits=hits+1, online='$waktu' WHERE ip='$ip' AND tanggal='$tanggal'";  
	} 
	//echo $sq; 
	mysql_query2($sq);
	
}
?>