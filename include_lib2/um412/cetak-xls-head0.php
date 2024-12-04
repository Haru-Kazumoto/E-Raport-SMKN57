<?php
cekVar('xlsOrientasi');
$ajc=explode(",",($addJCetak==''?'':",".$addJCetak));
$cxls="<center>	<span id='cetak0"."$rnd'></span>";
$ou="F";
foreach($ajc as $jc) {
	$jdlxls="Export ".($jc==''?'XLS':$jc);
	if($ou=='F'){
	$cxls.="
	<a href='#' onclick=\"bukaAjax('cetak0"."$rnd','$hal2&showbutton=1&cetak=xls&jcetak=$jc');return false;\" class=button2>$jdlxls</a>
	";
	} else {
	$cxls.="
	<a href='$hal2&showbutton=1&cetak=xls&jcetak=$jc' class=button2 target=_blank >$jdlxls</a>
	";
	}
}
$cxls.="</center>";

?>