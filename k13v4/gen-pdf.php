<?php
if ($_REQUEST['aksi']=='convert') {
	echo $_REQUEST['aksi'];	 
	$htmPDF=$_REQUEST["t1"];
	ubah("<","[-");
	ubah(">","-]");
	ubah("[-td-]","$ _pdf->Cell($ _w,$ _h,'");
	//ubah("$_","$");
	ubah("[-/td-]","',$ _border,0,'C');");
	ubah("[-br-]","$ _pdf->Ln;");
	ubah("[-br /-]","$ _pdf->Ln;");
}

function ubah($a,$b){
	global $htmPDF;
	$htmPDF=str_replace($a,$b,$htmPDF);
};

?>
<form action=? method=post>
<input type=submit name=aksi value=convert>
<textarea cols="80" rows="20" name=t1 id=t1><?=$t1?></textarea>
<textarea cols="80" rows="20" name=t2 id=t2><?=$htmPDF?></textarea>
</form>
