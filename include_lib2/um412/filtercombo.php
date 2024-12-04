<?php
echo "";
cekVar("page,newrnd,jcombo");

if ($jcombo=="provinsi") {	
	cekvar("provinsi,kota");
	$sq="select * from tbkota where provinsi='$provinsi' order by kota";	
	echo um412_isicombo5($sq,'kota',"kota","kota","- Pilih -",$kota,""); 
}
?>