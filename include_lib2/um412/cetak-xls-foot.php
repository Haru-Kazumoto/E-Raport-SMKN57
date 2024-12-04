<?php
	if (!isset($fontSize)) $fontSize=8;
	
	$lastrow=$br+$row_heading;
	$range_content='A'.$row_heading.':'.$lastcol.$lastrow;
	$range_jumlah='A'.($lastrow+1).':'.$lastcol.($lastrow+1);
	 
	$objPHPExcel->getActiveSheet()->getStyle($range_content)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle($range_content)->getFont()->setSize($fontSize);     
	
	$objPHPExcel->getActiveSheet()->getStyle($range_jumlah)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle($range_jumlah)->getFont()->setSize($fontSize);     
	
	
	$objPHPExcel->getActiveSheet()->getStyle($range_content)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	
	foreach($objPHPExcel->getActiveSheet()->getRowDimensions() as $rd) { 
    	$rd->setRowHeight(40); 
	}
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	@unlink($nmFileXLS); //hapus file

	
	date_default_timezone_set('Europe/London');  
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$ou='F';
	$objWriter->save(str_replace('.php', '.xls', $nmFileXLS));
	
	if ($ou=='F') {
		echo "<div ><a href='$toroot"."adm/$nmFileXLS' class='btn btn-primary button'>Download File</a><div><br><br>";
	}
	else {
//		require_once 'PHPExcel/IOFactory.php';
		$objWriter= PHPExcel_IOFactory::load($nmFileXLS);
	}
	exit;

?>