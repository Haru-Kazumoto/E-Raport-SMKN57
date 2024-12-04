<?php
if (!isset($tmargin)) $tmargin=15;
if (!isset($bmargin)) $bmargin=15;
if (!isset($orientation)) $orientation="P";
if (!isset($papersize)) $papersize="A4";
if (!isset($titlePdf)) {
	if (isset($nmCaptionTabel)) 
		$titlePdf=$nmCaptionTabel;
	else
		$titlePdf='Cetak Pdf';
}

$html="
<style>

.h2k { 
	font-weight:bold;
	font-size:13px;
}


table tr td { padding:3px 5px; }
td,th { page-break-inside: avoid; }
</style>
".$html;

$html=str_replace("<<tr ",'--tr> ',$html);
$html=str_replace("<tr ",'<tr nobr="true" ',$html);
$html=str_replace("--tr> ",'<tr ',$html);
//$html=str_replace("<table ",'#cekpb#<table ',$html);
//$html=str_replace("<table",'<table cellpadding="2" ',$html);

// Include the main TCPDF library (search for installation path).
require_once($lib_path.'tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		
		// Logo
		/*
		$image_file = K_PATH_IMAGES.'logo_example.jpg';
		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		*/
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		//$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		global $footerpdf;
		
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', '', 7);
		//$this->SetFont('helvetica', 'I', 7);
		
		// Page number
		if (isset($footerpdf)) {
			$af=explode('|',$footerpdf.'||||');
			$i=0;
			foreach ($af as $xf) {
				$af[$i]=str_replace('[page]',$this->getAliasNumPage(),$af[$i]);
				$af[$i]=str_replace('[pageofpage]',$this->getAliasNumPage().' of '.$this->getAliasNbPages(),$af[$i]);
				$i++;
			}
			
			$this->SetY(-15);
			$this->Cell(0, 10, $af[0], 0, false, 'L', 0, '', 0, false, 'T', 'M');
			$this->SetY(-15);
			$this->Cell(0, 10, $af[1], 0, false, 'C', 0, '', 0, false, 'T', 'M');
			$this->SetY(-15);
			$this->Cell(0, 10, $af[2], 0, false, 'R', 0, '', 0, false, 'T', 'M');
		
		} else {
			$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		}
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle($titlePdf);
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font


$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, $tmargin, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, $bmargin);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------


/*
$style6 = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 128, 0));
$pdf->Circle(25, 105, 10, 270, 360, 'C', $style6);
*/
// add a page
$pdf->SetFont('helvetica', '', 8);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

$ahtml=explode("#pb#",$html);

foreach ($ahtml as $sdhtml) {
	if (trim($sdhtml)=='') continue;
	$pdf->AddPage($orientation, $papersize);
	$adhtml=explode("#cekpb#",$sdhtml);
	foreach ($adhtml as $shtm) {
		if (trim($shtm)=='') continue;
		$baris=$pdf->getY();
		if ($baris>=270) 	{
			$pdf->AddPage($orientation, $papersize);
			//$shtm=$baris.$shtm;
		}
		$nexthtm="";
		 
		$pdf->writeHTML($shtm , true, false, true, false, '');
	/*	
		$pdf->startTransaction(); 
		$start_page = $pdf->getPage();                       
		$pdf->writeHTML($shtm, true, false, true, false, '');
		//$pdf->writeHTMLCell( 0, 0, '', '', $shtm, 0, 1, false, true, 'C'  );
		$end_page = $pdf->getPage();
		if  ($end_page != $start_page) {
			$pdf->rollbackTransaction(true); // don't forget the true
			$pdf->AddPage();
			$pdf->writeHTML($shtm, true, false, true, false, '');
		//$pdf->writeHTMLCell( 0, 0, '', '', $shtm, 0, 1, false, true, 'C'  );
		}else{
			$pdf->commitTransaction();     
		} 
*/
		
	}
	//$pdf->writeHTML(" akhir : ".$pdf->getY());
		
}

if (!isset($nfpdf)) $nfpdf=$toroot."temp/pdf-".rand(1238981,9212127).".pdf";
/*
$this->startTransaction(); 
$start_page = $this->getPage();                       
$this->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true, 'C'  );
$end_page = $this->getPage();
if  ($end_page != $start_page) {
    $this->rollbackTransaction(true); // don't forget the true
    $this->AddPage();
    $this->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true, 'C'  );
}else{
    $this->commitTransaction();     
} 
*/

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);



//$pdf->writeHTML($html, true, false, false, false, '');

// -----------------------------------------------------------------------------

//Close and output PDF document
//$pdf->Output('example_048.pdf', 'I');



$pdf->Output($nfpdf, 'I');

//============================================================+
// END OF FILE
//============================================================+
?>