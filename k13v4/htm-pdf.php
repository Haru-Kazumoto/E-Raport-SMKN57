<?php
include_once('conf.php');
include($lib_path."fpdf/Barcode.php");
include_once($lib_path."fpdf/fpdf.php");
	$nmFilePDF=$temp_path.$nmFileAttachment; 
	$print_page_1 = true;
	$print_page_2 = true;
	$border = 0;
	$height = 5;
	$lebar_kertas = 297; // mm
	$top_margin = 20; // mm
	$left_margin = 12; // mm
	$right_margin = 15; // mm
	$inner_width = $lebar_kertas - ($left_margin + $right_margin);
	$debug = false;
	 
	
	class PDF extends FPDF{
	function Footer(){
	  $this->SetFont('Arial','',9);
	  $this->SetXY(297 - 15, 10);
	  $this->Cell(10, 8, $this->PageNo()."/{nb}", 0, 0);
	}
	function gantiFontJudul1(){
		$this->SetFont('Arial', 'B', 12);
		 
		}
	function gantiFontJudul2(){
		$this->SetFont('Arial', 'IU', 11);
		 
		}
	function gantiFontNormal(){
		$this->SetFont('Arial', '', 10);	 
		}
	//pdf barcode plugin
	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0) {
		$font_angle+=90+$txt_angle;
		$txt_angle*=M_PI/180;
		$font_angle*=M_PI/180;

		$txt_dx=cos($txt_angle);
		$txt_dy=sin($txt_angle);
		$font_dx=cos($font_angle);
		$font_dy=sin($font_angle);

		$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		if ($this->ColorFlag)
			$s='q '.$this->TextColor.' '.$s.' Q';
		$this->_out($s);
	}

}
	
	$pdf = new PDF('P', 'mm', 'A4');
	
	$pdf->AliasNbPages();
	$pdf->SetTopMargin($top_margin);
	$pdf->SetLeftMargin($left_margin);
	$pdf->SetRightMargin($right_margin);
	
	#---[ HALAMAN 1]----------------------------------------------------------------
	 
		$scalaHtml=0.3;
		$w1=60;
		$w2=135;
		 
		$h=6;
	 	$border=0;
		$align='L';
	 
		$pdf->AddPage();
	 
		$pdf->SetLineWidth(0.4);
		$pdf->SetLink($link);
//		$pdf->Image('kop2.jpg',$left_margin,7,0,0,'','');
//		$pdf->Image('temp/tes.jpg',150,80,20,0,'','http://www.fpdf.org');
		$pdf->SetLeftMargin(15);
		$pdf->SetFontSize(12);/* KOP SURAT */
		$pdf->gantiFontNormal();
		$w1=10;
		
		$pdf->Ln(); 
		$spd=finishPdf($txtpdf);
		$aspd=split(";",$spd);
		foreach($aspd as $spdx) {
			echo $spdx.";";
			}
		$pdf->Footer();
	  
	  
	  $pdf->Output($nmFileAttachment, 'F');
	  $raw = phpversion();  
      list($v_Upper,$v_Major,$v_Minor) = explode(".",$raw);  
    
      if (($v_Upper == 4 && $v_Major <1) || $v_Upper <4) {   
          $_FILES = $HTTP_POST_FILES;  
          $_ENV = $HTTP_ENV_VARS;  
          $_GET = $HTTP_GET_VARS;  
          $_POST = $HTTP_POST_VARS; 
          $_COOKIE = $HTTP_COOKIE_VARS;  
          $_SERVER = $HTTP_SERVER_VARS;  
          $_SESSION = $HTTP_SESSION_VARS;  
          $_FILES = $HTTP_POST_FILES;  
      }
  
       
  if (!ini_get('register_globals')) {
      while(list($key,$value)=each($_FILES)) $GLOBALS[$key]=$value;
      while(list($key,$value)=each($_ENV)) $GLOBALS[$key]=$value;
      while(list($key,$value)=each($_GET)) $GLOBALS[$key]=$value;
      while(list($key,$value)=each($_POST)) $GLOBALS[$key]=$value;
      while(list($key,$value)=each($_COOKIE)) $GLOBALS[$key]=$value;
      while(list($key,$value)=each($_SERVER)) $GLOBALS[$key]=$value;
         while(list($key,$value)=@each($_SESSION)) $GLOBALS[$key]=$value;
          foreach($_FILES as $key => $value){
              $GLOBALS[$key]=$_FILES[$key]['tmp_name'];
              foreach($value as $ext => $value2){
                  $key2 = $key . '_' . $ext;
                  $GLOBALS[$key2] = $value2;
              }
          }
      }
?>