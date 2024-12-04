<?php
$useJS=2;
include_once "conf.php";
cekVar("aksi,rekap");
 	//default
	$sq="select * from tbpengunjung";
	$strfield="ip,tanggal,hits,online";
	$idTabel='tbpengunjung.id';
	$nmTabel='tbpengunjung';
	$jperpage=$record_per_page=100;
	$jpperpage=15;
	$tbsql= '';
	$orderby=' order by tbpengunjung.id desc';

	$afield = explode(',',$strfield);
	$jlhfield=count($afield);
	$jlhcol=$jlhfield+1;
	
$titlereport=("DAFTAR PENGUNJUNG");
$isicombo="IP/Host;ip,No ID;tbpengunjung.id,Tanggal(YYYY-MM-DD);tbpengunjung.tanggal";

$admrep="pengunjung";
$hal2=$um_path."$admrep-daf.php?";

include $um_path."frmreport_v2.0.php";

if ($cetak=='xls') {
	$nmFileXLS=$temp_path.'Daftar_'.$lima.'_sd_'.$limb.'.xls'; 

	 
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
	$objPHPExcel->getActiveSheet()->setTitle('tbpengunjung');
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
 

	$objPHPExcel->getActiveSheet()->mergeCells('A1:'.$lastcol.'1');
	$objPHPExcel->getActiveSheet()->mergeCells('A2:'.$lastcol.'2');
	$objPHPExcel->getActiveSheet()->mergeCells('A3:'.$lastcol.'3');
		 
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
	   	$objPHPExcel->getActiveSheet()->setCellValue("$crx[$i]".$rx, strtoupper($afield[$i])); 	 }     
	 $rx++;
	
	//warna
	$objPHPExcel->getActiveSheet()->getStyle($range_heading)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$objPHPExcel->getActiveSheet()->getStyle($range_heading)->getFill()->getStartColor()->setARGB('FF808080');
	
	//DAFTAR 
	//$sq="select ip,tanggal,hits,online from tbpengunjung";
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

	date_default_timezone_set('Europe/London');  
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save(str_replace('.php', '.xls', $nmFileXLS));
	echo "<br><a href=adm/$nmFileXLS>Download File</a>...<br>";
	exit;
}

$tt=$ttawal;
if ($nr>0) {
$tt.="
	 <table  border=0 class=tbreport align=center cellpadding=0 >
	 <tr><td  class=tdjudul>NO</td>
	 <td class=tdjudul>IP</td>
		<td class=tdjudul>TANGGAL</td>
		<td class=tdjudul>HITS</td>
		<td class=tdjudul>ONLINE</td>
	<td class=tdjudul>OPERASI</td></tr>
	<tr>";
	
	  $jumlah=0;
	  $br=0;
	  while ($r=mysql_fetch_array($h)) {
		  $id=$r['id'];
		  $idt="peng_".$r['id'];
		  $br++;
		  $troe=(($br%2)==0?'trevenform2':'troddform2');
		  $jumlah=$jumlah+$jlh[0];
			$tt.="
			<tr class='$troe' id='m_"."$idt'  >
			<td>".($br+$lim)."</td>
	<td>$r[ip]</td>
		<td>$r[tanggal]</td>
		<td>$r[hits]</td>
		<td>".date('d M Y',$r['online'])."</td>
		<td width=100>";  
		$tt.= tboperasi($um_path."pengunjung",$id,'id',"all","$idt",'111'); 
	$tt.="</td></tr>"; 
	
	$tt.="</td></tr><tr><td colspan=5><div id='$idt' ></div></td></tr>";

 	  }
	  $tt.=" </table>";
}
$tt.=$ttakhir;
echo $tt;
?>
  