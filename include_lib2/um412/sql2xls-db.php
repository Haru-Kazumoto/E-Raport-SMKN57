<?php	 
	$nmFileXLS='export.xls';//'tes/Daftar_Peserta_rec_'.$lima.'_sd_'.$limb.'.xls'; =
	$nmFileXLS=$temp_path.'export.xls';//'tes/Daftar_Peserta_rec_'.$lima.'_sd_'.$limb.'.xls'; =
	// $sql=$sqlTabel;
	//	echo $sqlall;
	$afield=explode("#",getArrayFieldName($sql,"#"));
	//echo $afield;
	 $jlhfield=count($afield);
	 $lastcol=$crx[$jlhfield-1];

	if (!isset($judul1)) $judul1='';
	if (!isset($judul2)) $judul2='';	
	if (!isset($outputto)) $outputto='F';
	
	/*
	if ($_REQUEST['showbutton']==1) {
		echo "<div align=center style='border:1px solid #000;background:#ddd;'>
				<br>CETAK FILE KE FORMAT MICROSOFT EXCEL<br><br>";
		for ($i=0;$i<$jlhpage;$i++) {
			$uxs=$i+1;
			$limb=$i*$record_per_page;		
			$tempat="cetak".$uxs;
			$links=$hal2."&cetak=xls&lim=".$limb;
			echo "<span id=$tempat>";
			echo "<a class=button style='width:40px' 
					href=# onclick=javascript:bukaAjax('$tempat','$links');>Hal $uxs</a>";
			echo '</span>';
		}
		echo '<br><br></div>';
		exit;
	}
*/


	 
	require_once  $lib_path.'PHPExcel.php';
	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()->setCreator('um412')
							 ->setLastModifiedBy('um412')
							 ->setTitle('Office 2007 XLSX Test Document')
							 ->setSubject('Office 2007 XLSX Test Document')
							 ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
							 ->setKeywords('office 2007 openxml php')
							 ->setCategory('Test result file');

	
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('tbpage');
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
	/*
	if ($judul1!='') { $objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $judul1);$rx++;}
 	if ($judul2!='') { $objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $judul2);$rx++; }
 	if ($judul3a!='') $objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $judul3a);$rx++; //halaman x dari
 	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $tglcetak);$rx++; 
	$rx++;
	$rx++;
	*/
	
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Arial');
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(13);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
 

	//SetFont('Arial', 'B', 9);	
	$row_heading=$rx;//baris heading	
	$range_heading='A'.$row_heading.':'.$lastcol.$row_heading;
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, 'No'); 
	 
	 for ($i = 0; $i < $jlhfield; $i++) {
 	 	$objPHPExcel->getActiveSheet()->getColumnDimension("$crx[$i]")->setWidth($w_xls[$i]);
	   	$objPHPExcel->getActiveSheet()->setCellValue("$crx[$i]".$rx, strtoupper($afield[$i])); 	 }     
	 $rx++;
	
	//warna
	$objPHPExcel->getActiveSheet()->getStyle($range_heading)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($range_heading)->getFill()->getStartColor()->setARGB('FF808080');
	
	//DAFTAR 
	//$sql="select id,pg,judul,isi,katakunci,gambar,ket,cat,lang,urutan,link from tbpage";
	$h=mysql_query2($sql);
	$br=0;
	 while ($v= mysql_fetch_array($h)){
	  $br++;	 
	  $objPHPExcel->getActiveSheet()->setCellValue('A'.$rx, $br+$lim); 
	  for ($i = 0; $i<$jlhfield; $i++) {
		$nmfield=$afield[$i];
		 $objPHPExcel->getActiveSheet()->setCellValue("$crx[$i]".$rx, trim($v[$nmfield]));		
	  } 
	  $rx++;
	}//persiswa
	
	$lastrow=$br+$row_heading;
	$range_content='A'.$row_heading.':'.$lastcol.$lastrow;
	$range_jumlah='A'.($lastrow+1).':'.$lastcol.($lastrow+1);
	 
	$objPHPExcel->getActiveSheet()->getStyle($range_content)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle($range_content)->getFont()->setSize(8);     
	
	$objPHPExcel->getActiveSheet()->getStyle($range_jumlah)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle($range_jumlah)->getFont()->setSize(8);     
	
	$objPHPExcel->setActiveSheetIndex(0);
	
	@unlink($nmFileXLS); //hapus file
	require_once $lib_path.'PHPExcel/IOFactory.php';

	
	date_default_timezone_set('Europe/London');  
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	if ($outputto=='D') {
		header('Content-type: application/vnd.ms-excel');

		header('Content-Disposition: attachment; filename="file.xlsx"');
		
		header('Cache-Control: max-age=0');
		
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header ('Cache-Control: cache, must-revalidate');
		header ('Pragma: public');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');		
		$objWriter->save('php://output');
	} else {
		$objWriter->save(str_replace('.php', '.xls', $nmFileXLS));
		//echo date('H:i:s') . ' Memori digunakan: ' . (memory_get_peak_usage(true) / 1024 / 1024) . ' MB';
		//echo "<br>File tersimpan di <a href=$nmFileXLS>$nmFileXLS</a>, ...<br>";
		echo "
		<br><center><a href=$nmFileXLS class='btn btn-success' id=tbdownxls_$rnd >Download File</a></center><br>
		<div style='display:none' id=tfbe$rnd >
		$('#tbdownxls_$rnd')[0].click();
		
		</div>
		";
	}
	//ob_end_clean();	
	
	exit;

?>