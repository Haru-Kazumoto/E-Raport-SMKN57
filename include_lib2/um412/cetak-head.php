<?php

if (!isset($t))$t="";
if (!isset($id))$id="";
if (!isset($showResultHead)) $showResultHead=true;

cekVar("media,op");
if ($media!='xls') {
	if (!isset($cetakpdf))$cetakpdf=false;
	$wtable="100%";
	$wtable50="100%";
	
	if (!isset($addInputHeadCetak)) $addInputHeadCetak="";
	$thisfile="content1.php?det=$det&op=$op&id=$id";
  
	if (!isset($addhal)) $addhal=""; 
	$tbpdf="";
	if ($cetakpdf) $tbpdf="<input id=tbcetakpdf type=button class='btn btn-sm btn-success' value='PDF' onclick=\"location.href='$thisfile&media=pdf$addhal'\" >";
	
	//posbc digunakan untuk posisi theadcetak. jika di layar tersendiri/newwindow,posbc=1, 
	//jika di dalam content (menggunakan menu kiri),posbc=2
	if (!isset($posbc)) $posbc=1;
	if ($posbc==1) {
		$addposbc="position:fixed;left:0;top:0;background:#d9f5f7;border-bottom:2px solid #000;";
		$addpostout="margin-top:48px";
		$tbtutup="
		&nbsp;<a href=# onclick=\"window.close();\" id='tbcetaktutup' class='btn btn-sm btn-warning' > 
		<i class=' icon-signout'></i>Tutup</a>
		";
	} else {
		$addposbc="";
		$addpostout="";
		$tbtutup="";
		
	}
	$theadcetak="<div style='padding:10px;width:100%;z-index:1000;$addposbc'   
	class='breadcrumb2'> ";
	if ($addInputHeadCetak!='') {
		$theadcetak.="
		<span style='float:left'> 
		$addInputHeadCetak
		</span>"; 
	}
	if (!isset($addKet)) $addKet='';
	$theadcetak.="
 
	<form id=fc_$rnd action='content1.php?det=pdf' target=_blank method=post >
	<center>
	$addKet
	<a href=#  class='btn btn-sm btn-success' id='tbcetakcetak' onclick=\"printDiv('tout$rnd');\" >
	<span>Cetak</span></a>
	$tbtutup 
	</center>
	</form>
 
	 </div>"; //$tbpdf
	$tout="<div id='tout$rnd' class=tout style='$addpostout'>";
	$tout.="<link rel='stylesheet' type='text/css' href='$js_path"."style-cetak.css' >";
	//if (isset($orientation)) $tout.= "<link rel='stylesheet' type='text/css' href='$js_path"."style-cetak-landscape.css' >";
	$tout.="
	<style>
	 
	
	#tbcetakcetak,#tbcetaktutup,#tbcetakpdf {
		background-color: #00acd6;
		color: #fff;
		background-color: #31b0d5;
		border-color: #269abc;
		border-radius: 3px;
		padding: 5px 10px;
		font-size: 12px;
		line-height: 1.5;
		text-decoration:none;
	}
	
	#tbcetaktutup {
		background-color: #d73925;
		border-color: #ac2925;
	}

	.tfotok {
		position:relative;
		border:#006 2px solid;
		border-radius:2px;
		width:130px;
		height:180px;
		margin:20px 20px -100px 20px;
	}

	.a4portrait {
		border:#ddd 2px solid;
		width:1000px;
		margin:auto;
		padding:20px;
		background-color: #FFF;
		height: 45.7923em;
		width: 70.4206em;
		display: table;
		margin: 10px auto;
		box-shadow: 0px 0px 0.5cm rgba(0, 0, 0, 0.5);
	}

	.h2k {
		font-size:16px;
		text-decoration:underline;
		margin-top:15px;
		margin-bottom:10px;
		font-weight:bold;
		}

	.tbdetail ,
	.tbdetail2 {
		border-collapse: collapse;
		border-spacing: 0px;
		margin:20px 5px;
		box-shadow: 2px 2px 5px #CCC;
	}

	.tbcetak1a ,
	.tbcetak1b {
		margin:10px;
		width:97%;
		}

	.tbcetak1b th,
	.tbcetak1b td {
		font-weight:bold;
		border:#000 1px solid;
		}


	.tbdetail td {
		font-size: 15px;
		padding: 8px 5px;
		border-style: solid;
		border-width: 2px;
		overflow: hidden;
		word-break: normal;
		text-align: center;
	}
	</style>";
	$thead=$theadcetak.$tout;
	if ($showResultHead) {
		echo $thead;
	} 
} else {
	$wtable=630;
	$wtable50=$wtable/2;
	$t.="<style>";
	$t.= file_get_contents("$js_path"."style-cetak.css");
	if (isset($orientation)) $t.= file_get_contents("$js_path"."style-cetak-landscape.css");
	$t.="</style>";
}

if (!isset($margincetak)) $margincetak="2.5cm";

//custom margin
$t.="
<style>
.page,
.page-landscape {
		padding:$margincetak;
	}
@media print {
	.page,
	.page-landscape {
		padding:$margincetak;
	}
}
</style>";
 

?>