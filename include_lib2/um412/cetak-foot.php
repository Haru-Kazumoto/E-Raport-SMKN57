<?php
if ($media=='') {	
	//<table\s+.*?\s+id="dgVeriler"
	//$t="<a hh>aku</a>";
	//$t=preg_replace('<a\s+.*?\s','<a\s+.*?\s+class="pdf" ',$t);
	/*
	$t= preg_replace('<table\s+.*?\s+class=\'>', 'table class=\'aa ', $t);
	
	echo "<textarea  cols=170 rows=30>$t</textarea>";
	exit;
	//$html=preg_replace('<table\s+.*?\s+class="','<table\s+.*?\s+class="pdf ',$html);
	
	*/
	
	$clspage="page";//class: page/page-landscape
	$t=str_replace("#pb#","</div><div class='$clspage'>",$t);
	$t=str_replace("#cekpb#","",$t);
	
	echo "<div class='$clspage'>";
	echo $t;
	echo "</div>";
	echo "</div>";//tout
} else {
	if (!isset($nfpdf)) $nfpdf="hasil_$rnd.pdf";
	$html=$t;
	
	$html=str_replace("'",'"',$html);
	include $um_path."head-pdf.php";
	//echo $html;
 }
 
 
?>