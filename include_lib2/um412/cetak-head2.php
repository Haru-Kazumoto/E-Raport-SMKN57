<?php
//yang akan dicetak $isi=''
if (!isset($t)) $t="";
if (!isset($id)) $id="";
if (!isset($margincetak)) $margincetak="2.5cm";
if (!isset($clspage)) $clspage="page";
if (!isset($aligntb)) $aligntb="right";
if (!isset($addtb)) $addtb="";
if (!isset($useFormatCetak)) $useFormatCetak=false;

cekVar("media");
if ($media=='') {  
	$t="<div class='breadcrumb2'>
			<div align='$aligntb' style='margin-right:20px;margin-top:5px'>
			$addtb
			<input type=button class='btn btn-sm btn-mini btn-success' value='Cetak' onclick=\"printDiv('tout_$rnd',2);\" >   
			</div>
		</div>
	 <div id='tinput_$rnd'></div>
	 <div id='tout_$rnd' class=tout>
	 ";
	
	//class: page/page-landscape
	if ($useFormatCetak) {
		$isi=str_replace("#pb#","</div><div class='$clspage'>",$isi);
		$isi=str_replace("#cekpb#","",$isi);
		$t.= "<div class='$clspage'>";
		$t.= $isi;
		$t.= "</div>";
	}
	else $t.= $isi;
	$t.= "</div>";
	$t.="
	<style>
	.page,
	.page-landscape,
	.page2,
	.page-landscape2 {
			padding:$margincetak;
		}
	@media print {
		.page,
		.page-landscape,
		.page2,
		.page-landscape2 {
			padding:$margincetak;
		}
	}
	</style>
	";	
} else { //cetak pdf
	$wtable=630;
	$wtable50=$wtable/2;
	$t.="<style>";
	$t.= file_get_contents("$js_path"."style-cetak.css");
	$t.="</style>";
	if (!isset($nfpdf)) $nfpdf="hasil_$rnd.pdf";
	$html=$t;
	$html=str_replace("'",'"',$html);
	include $um_path."head-pdf.php";
	
}



  
?>