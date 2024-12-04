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
		$pdf->Image('kop2.jpg',$left_margin,7,0,0,'','');
//		$pdf->Image('temp/tes.jpg',150,80,20,0,'','http://www.fpdf.org');
		$pdf->SetLeftMargin(15);
		$pdf->SetFontSize(12);/* KOP SURAT */
		$pdf->gantiFontNormal();
		$pdf->Cell($w1, $h, " ", 0, 0, $align);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		
		$nourut=$rv['id'];
		
		$pdf->Ln(); 
		$pdf->gantiFontJudul1();
		$pdf->Cell($w1+$w2, $h, "BUKTI REGISTRASI RAKERNAS IBI 2011", $border, 0, "C");
		$pdf->gantiFontNormal();
		$pdf->Ln();
		$pdf->Line($left_margin ,$pdf->GetY(), $w1 + $w2, $pdf->GetY());
		$pdf->Ln();
		$pdf->gantiFontJudul2();
		$pdf->Cell($w1+$w2, $h, "CONTACT DETAILS", $border, 0, $align);
		$pdf->Ln();
		$pdf->gantiFontNormal();
		$pdf->Cell($w1, $h, "No. Registrasi", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[id]", $border, 0, $align);		
		$pdf->Ln();
 		$pdf->Cell($w1, $h, "Tanggal Daftar", $border, 0, $align);
		$pdf->Cell($w2, $h, ": ".tglIndo($rv[tgl]), $border, 0, $align);		
		
		$nmlengkap=$rv[nama];
		if ($rv[gelardepan]!='') $nmlengkap=$rv[gelardepan].". ".$nmlengkap;
		if ($rv[gelarbelakang]!='') $nmlengkap=$nmlengkap.", ".$rv[gelarbelakang];
		 
		$pdf->Ln();
 		$pdf->Cell($w1, $h, "Nama", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $nmlengkap", $border, 0, $align);		
		$pdf->Ln();
 		$pdf->Cell($w1, $h, "Nama Pada Sertifikat", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[namasert]", $border, 0, $align);		
		$pdf->Ln();
 		$pdf->Cell($w1, $h, "Cabang", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[cab]", $border, 0, $align);		
		$pdf->Ln();
		$pdf->Cell($w1, $h, "Alamat", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[alamat]", $border, 0, $align);		
		$pdf->Ln();
		$pdf->Cell($w1, $h, "Kota/Provinsi", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[kota] / $rv[provinsi]", $border, 0, $align);		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "No. Telp./HP", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[telp] / $rv[hp]", $border, 0, $align);		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "Email", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[email]", $border, 0, $align);		
		$pdf->Ln(); 
		$pdf->Ln(); 
		$pdf->gantiFontJudul2();
 		$pdf->Cell($w1+$w2, $h, "PILIHAN PAKET", $border, 0, $align);
		$pdf->gantiFontNormal();		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "Pilihan Paket", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[paket]", $border, 0, $align);		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "Biaya", $border, 0, $align);
		$pdf->Cell($w2, $h, ": ".rupiah($rv[cost]), $border, 0, $align);		
		$pdf->Ln(); 
		$pdf->Cell($w1, $h, "Deskripsi Paket", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[ketpaket]", $border, 0, $align);		
		$pdf->Ln();
		$pdf->Cell($w1, $h, "Pilihan Hotel", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[hotel]", $border, 0, $align);		
		$pdf->Ln();
		$pdf->Ln(); 
		$pdf->gantiFontJudul2();
 		$pdf->Cell($w1+$w2, $h, "DATA PEMBAYARAN", $border, 0, $align);
		$pdf->gantiFontNormal();		
		$pdf->Ln(); 
		$pdf->Cell($w1, $h, "Total Biaya", $border, 0, $align);
		$pdf->Cell($w2, $h, ": ".rupiah($rv[cost]), $border, 0, $align);		
		$pdf->Ln(); 
		$pdf->Cell($w1, $h, "Total Transfer", $border, 0, $align);
		$pdf->Cell($w2, $h, ": ".rupiah($rv[cost]), $border, 0, $align);		
		$pdf->Ln(); 
		$pdf->Cell($w1, $h, "Tanggal Konfirm", $border, 0, $align);
		$pdf->Cell($w2, $h, ": ".tglIndo($rv[t_tglkonfirm]), $border, 0, $align);		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "Tanggal Transfer", $border, 0, $align);
		$pdf->Cell($w2, $h, ": ".tglIndo($rv[t_tgltransfer]), $border, 0, $align);		
		$pdf->Ln();
 		$pdf->Cell($w1, $h, "Cara Pembayaran", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[bank]", $border, 0, $align);		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "Sponsor", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[sponsor]", $border, 0, $align);		
		$pdf->Ln(); 
 		$pdf->Cell($w1, $h, "CP/HP Sponsor", $border, 0, $align);
		$pdf->Cell($w2, $h, ": $rv[t_cp]/$rv[t_hp]", $border, 0, $align);		
		$pdf->Ln();  	
		$pdf->Ln();
 		$pdf->Ln();  	
		$pdf->Ln();
 		$pdf->Cell($w1+$w2, $h, "Batam, ".tglIndo($tglvalidasi), $border, 0, $align);
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell($w1+$w2, $h, "Panitia", $border, 0, $align);		
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Cell($w1+$w2, $h, "*) Bawa bukti ini pada saat reregistrasi", $border, 0, $align);		
		
		$pdf->Image('../img/logotrans.jpg',155,235,30,30,'','');
		$pdf->Footer();
		//====
	  //--------------buat barcode
	      $fontSize = 10;
		  $marge    = 10;   // between barcode and hri in pixel
		  $x        = 180;  // barcode center
		  $y        = 70;  // barcode center
		  $height   = 8;   //50 barcode height in 1D ; module size in 2D
		  $width    = 2/8;    //2 barcode height in 1D ; not use in 2D
		  $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
		  
		  $code     = $nourut;//'123456789012'; //$rv[id]; barcode, of course ;)
		  $type     = 'code128';//'ean13';
		  $color    = '000000'; // color in hexa
 	      $data = Barcode::fpdf($pdf, $color, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
		  $pdf->SetFont('Arial','B',$fontSize);
		  $pdf->SetTextColor(0, 0, 0);
		  $len = $pdf->GetStringWidth($data['hri']);
		  Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		  //$pdf->TextWithRotation($x + $xt, $y + $yt, $data['hri'], $angle);
//-buat barcode
	  
	  
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