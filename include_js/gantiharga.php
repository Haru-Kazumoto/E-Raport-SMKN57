<?php
$useJS=2;
require_once "conf.php";
//killUnregistered();
$imd=strip_tags($_REQUEST["imd"])*1; 
$idreg=strip_tags($_REQUEST["idreg"])*1; 
$idres=strip_tags($_REQUEST["idres"])*1; 
$pengenal=strip_tags($_REQUEST["pengenal"]); 
$jenis=$jns=strip_tags($_REQUEST["jenis"]); 

$tgl_transfer=strip_tags($_REQUEST["tgl_transfer"]); 
$tt=getTglInput($tgl_transfer);
$be=getTglInput($batasEar);
$j=mysql_query("select cost,lat,onsite from master_data where id='$imd'");
$r=mysql_fetch_array($j); 
$cost=(($tt/100>=$be/100 )?$r['lat']:$r['cost']);
if ($notampil=="") {
	echo "<input type=hidden name=cost_".$pengenal." id=cost_".$pengenal." value='$cost'><font color=red><blink>".rupiah($cost)."</blink></font>";
	$jPaid=cariField("select bank from konfirmasi_transfer where id_reservasi='$idres' and jenis='".$jns."' ");
	if ($jPaid=='FOC') {
		$totpaid=$rwt["cost"];
		$sisa=0;
	} else {
		$totpaid=(cariField("select sum(jlh_transfer) from konfirmasi_transfer where id_reservasi='$idres' and jenis='".$jns."' ")*1);
		$sisa=$cost-$totpaid;
	}
	echo "<br /><br />Not Paided Yet:<br /><font color=red ><input type=hidden id='sisa_$pengenal' value='$sisa'>".rupiah($sisa)."</font>";
}
?>
 