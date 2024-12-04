<?php
if (!isset($addFrmFilter)) $addFrmFilter="";
$nfc=$adm_path."protected/view/$det/filter-$det.php";
if (file_exists($nfc)) {
	include $nfc;
}

$t="";
$asf="";
$nfAction="index.php?det=$det&op=showtable";
$t.="
	<div id=ts"."$idForm ></div>
	<div id=tinputdialog2_$rnd></div>
	<form id='$idForm' action='$nfAction' method='Post' $asf style='padding:0px;margin:0 0 5px 0;' > 
	<div style='max-height:400px;overflow:auto;padding:0px 10px'>
	
	";

$t.=$addFrmFilter;
$t.="</div>
	<div align=right style='margin:10px 0px;0px 10px;background:#D2D2D2;padding:10px'><input type=submit class='btn btn-sm btn-primary' value='Filter'>
	</div>";
$t.="</form>";

echo $t;
?>