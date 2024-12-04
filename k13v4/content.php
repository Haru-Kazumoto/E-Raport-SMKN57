<?php
$nfx=$toroot."beranda.php";

cekVar("contentOnly,det");
switch($det) {
	case "siswa";
		$nfx=$toroot."input.php";
		break;

}
?>