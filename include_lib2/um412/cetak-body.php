<?php
$t="";
cekVar("cari,addfae");
if (!isset($addTbCetak)) $addTbCetak="";
//tombol atas
if ($cari=="") {
	$t.="
	<div id=tfbe$rnd style='display:none'>
	$addfae
	$('.tgl').datepicker({dateFormat:formatTgl});
	</div>
	";
	
	$t.="
	<div class='row tbviewatas noprint'style='background:#fff;padding:2px;margin:0px 8px -11px 8px;border-bottom:2px solid #000;' >
		<div style='margin:10px 10px 10px 0;text-align:right;background:#fff; ' >
		<div style='float:left'>
			$addTbCetak	
		</div>
		<div style='float:right'>
			<button class='btn btn-success btn-sm' onclick=\"printDiv('tview_$rnd',2)\">Cetak</button>
		</div>
		</div>
	</div>
	";

	$t.="<div class='tview' id='tview_$rnd'>";
}


$clc='class=center';
cekVar("orientation");
$t.="<link rel='stylesheet' type='text/css' href='$js_path"."style-cetak.css' >";
$t.="<link rel='stylesheet' type='text/css' href='include_js/style-cetak.css' >";


if  ($orientation=="landscape") {
	$t.=  "<link rel='stylesheet' type='text/css' href='$js_path"."style-cetak-landscape.css' >";
	$t.=  "<link rel='stylesheet' type='text/css' href='include_js/style-cetak-landscape.css' >";
}

$t.="
<style>
.page,
.page-landscape {
	font-family:arial;
}
.tbkecil td ,.tbkecil th {
	font-size:9px;
}

.tbhead {
	margin-bottom:10px;
	margin-top:12px;
}
.tbhead0 td {
	font-size:15px;
}

.tbhead td {
	height:24px;
	font-size:13px;
}
.tbfoot1 {
	 
	margin-right:20px;
	margin-bottom:20px;
}
.tbfoot1 td {
	height:24px; 
}
.tbfoot2 {
 
}
.trhead {
	background:#CCCCCC;
	
}
.trhead th  {
	text-align:center;	
	
}

.tbcetakbergaris td,
.tbcetakbergaris   th,
.page2-landscape table tr td,
.page2-landscape table tr th,
 {
		font-size:10px;
		font-family:arial;
		padding:5px;
}
".(isset($addStyle)?$addStyle:"")."
@media print {
	

}
</style>
";


$lastbr=0;
$bytot=0;
$lastidpeg='xx';
$halbaru=false;
$rsp1=$rsp2="";

$br=1;
$hq=mysql_query2($sq);
while ($r=mysql_fetch_array($hq)) {
	
	$batasbr=$br-1-$lastbr-$brperpage;
	if (($br==1)||($batasbr==0)) {
		if ($br!=1)  {
			$t.="</table>";
			$t.="</div>";
			$hal++;
			$brperpage=$brperpage2;
			$lastbr=$br;
		}
		$t.=$awalP;
		if ($br==1) 
			$t.=$judulP;	
		else {
			$t.="<br><br>";
		}
		$t.=$judulT;
		$halbaru=true;
	}
	$t.=cetakBaris();
	$br++;
}
$t.="</table>";

$t.="
	</div>
";
if ($cari==''){
	$t.="</div>";
	
}
?>