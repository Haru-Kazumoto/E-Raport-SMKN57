<?php


	//require_once  '../lib/PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator('um412')
							 ->setLastModifiedBy('um412')
							 ->setTitle('Office 2007 XLSX Test Document')
							 ->setSubject('Office 2007 XLSX Test Document')
							 ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
							 ->setKeywords('office 2007 openxml php')
							 ->setCategory('Test result file');

	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('reservasi_kongres_workshop');
	if ($xlsOrientasi=='P')
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
	elseif ($xlsOrientasi=='P')
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	
	$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	
	//$ defHtml 
	
	
	//lebar 
	for ($i = 0; $i <= $jlhfield; $i++) {
	$w_xls[$i]=$w[$i]*$scalaXLS;
	}


	// garis tepi
	$styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
 

	/*
	$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$lastcol.'1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$lastcol.'2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$lastcol.'3');
	*/	 
	$rx=1;//rowexcel
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $judul1);$rx++;
	
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(13);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	 
 	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $judul2);$rx++; 
 	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $judul3a);$rx++; 
 	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $tglcetak);$rx++; 
 
	$rx++;
	$rx++;
	//SetFont('Arial', 'B', 9);	
	$row_heading=$rx;//baris heading	
	$range_heading='A'.$row_heading.':'.$lastcol.$row_heading;
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, 'No'); 
	 
	 for ($i = 0; $i < $jlhfield; $i++) {
 	 	$objPHPExcel->getActiveSheet()->getColumnDimension("$crx[$i]")->setWidth($w_xls[$i]);
		$jd=strtoupper($afield[$i]);
		if (substr($afield[$i],0,1)=='_')
			 $objPHPExcel->getActiveSheet()->setCellValue("$crx[$i]".$rx,substr($jd,1,strlen($jd)-1));		
		else
		   	$objPHPExcel->getActiveSheet()->setCellValue("$crx[$i]".$rx, $jd); 	 
	}     
	 $rx++;
	
	//warna
	$objPHPExcel->getActiveSheet()->getStyle($range_heading)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($range_heading)->getFill()->getStartColor()->setARGB('FF808080');
	
	//tinggi
	//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(100);
	//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	//objWorksheet->getRowDimension('1')->setRowHeight(-1);

?>