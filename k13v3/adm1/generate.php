<?php
/* form opr daf xls */
include "conf.php";
$nmfile=$_REQUEST['nmfile'];
if ($_REQUEST["simpan"]=="generate") {
	$pilihopr=$_REQUEST["pilihopr"];
	if ($_REQUEST['db']) $mydb=$_REQUEST['db'];
	//koneksi($mydb);

	if ($_REQUEST["inputfield"]!="") {
		$letakfrom=strpos(trim($inputfield),'from');
		$inputfield=substr($_REQUEST["inputfield"],0,$letakfrom);
		//$nmtabel=substr($_REQUEST["inputfield"],$letakfrom+5,1000);
		$nmtabel=$_REQUEST["nmtabel"];
		$sql=$_REQUEST["inputfield"];
 	}
	else {
		$inputfield="*";
		$nmtabel=$_REQUEST["nmtabel"];
		$sql="select * from $nmtabel";
	}
	//echo "<br>Nama Tabel : $nmtabel";
	echo "<br>Field: $inputfield";
	$idt=substr($nmtabel,2,4);
	//mencari fields
	if ($_REQUEST["inputfield"]=="") {
		$fields = mysql_list_fields($db, $nmtabel);
$jlhfield = mysql_num_fields($fields);
		$strfield_lengkap="";
		for ($i = 0; $i < $jlhfield; $i++) {
			 $strfield_lengkap.=($i==0?"":",").mysql_field_name($fields,$i);
		}
    }
	else {
		$strfield_lengkap=$inputfield;
	}
	  
	$afield = split(",",$strfield_lengkap);
	$jlhfield = count($afield);
	$nmfield2=$afield[1];

	//menghilangkan spasi dan titik
	for ($i = 0; $i < $jlhfield; $i++) {
		$afield[$i]=trim($afield[$i]);
		//menghilangkan nama tabel
		if (strrpos($afield[$i], ".")>0) $afield[$i]=substr(strstr(trim($afield[$i]),"."),1,100);
		echo "<br>pos:$afield[$i]";
	}
	 
	$strfield=implode(",",$afield);
	echo "<br>Field: ".$strfield;
	//memberikan default untuk semua pilihan (form, opr, daf, pdf, xls)
	
	$defHtml_bak="
	//$"."sql='$sql';
	";
	$defHtml="
	//default
	$"."sq=\"select * from $nmtabel\";
	$"."strfield=\"$strfield\";
	$"."idtabel='$nmtabel.id';
	$"."nmtabel='$nmtabel';
	$"."jperpage=$"."record_per_page=50;
	$"."jpperpage=15;
	$"."tbsql= '';
	$"."aksi=$"."_REQUEST['aksi'];
	$"."orderby=' order by $nmtabel.id desc';

	$"."afield = split(',',$"."strfield);
	$"."jlhfield=count($"."afield);
	$"."jlhcol=$"."jlhfield+1;
	
	$"."aksi=$"."_REQUEST['aksi'];
	$"."rekap=$"."_REQUEST['rekap']*1;


	//cetak
	/*
	$"."tglcetak=tglIndo();
	$"."crx=explode(',' , 'B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF');
	$"."lastcol=$"."crx[$"."jlhfield-1];
	$"."scalaHtml=25;
	$"."scalaPDF=20;
	$"."scalaXLS=20;
	$"."w=array(1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
	$"."w_xls=$"."w_html=$"."w_pdf=$"."w;
	*/
";
	echo 	"<br>def:".$defHtml;
	//menberi default
	$defForm="";
	$defRequest="";
	$sqlInsert1="insert into $nmtabel(";
	$sqlInsert2=") values (";
	$sqlUpdate="update $nmtabel set ";
	$defType="";
	
	for ($i = 0; $i < $jlhfield; $i++) {
		$nmfield=$afield[$i];
 		$defForm.="\n$"."$nmfield=$"."row[\"$nmfield\"];";
		$defRequest.="\n$"."$nmfield=strip"."_tags($"."_REQUEST[\"$nmfield\"]);";
		if ($i!=0) {
			$sqlUpdate.=",";			
 			if ($i>1) {
			$sqlInsert1.=",";
			$sqlInsert2.=","; 
			}
		}
		$sqlUpdate.="$nmfield='$"."$nmfield'";
 		if ($i>=1) { //id tidak perlu
			$sqlInsert1.="$nmfield";
			$sqlInsert2.="'$"."$nmfield'";
			}
		}
		$sqlInsert2.=")";
		 
	$sqlUpdate.="where id='$"."id' ";
	$sqlInsert=$sqlInsert1.$sqlInsert2;
	echo "<br>Type: ".$defType;
	

	$htm="";
	$htm.="
<?php
include \"conf.php\";
kill_unauthorized_user();
$"."id=$"."_REQUEST[\"id\"]*1;
if ($"."id!=0){
	$"."opr=\"ed\";
	$"."sql=@mysql_query(\"select * from $nmtabel where id=\".$"."id);
	while ($"."row=mysql_fetch_array($"."sql)){
		$defForm
	}
} else $"."opr=\"tb\";
	?>
	<center>
	<div class=dialog1 style='width:750'>
	<div class=titledialog1>".strtoupper("Entri Data $nmtabel")."</div>
	<form action='<?=$"."um_path?>$nmfile"."-opr.php' enctype=\"multipart/form-data\" method=post>
	<table class=tbform2 align=center border=0>
      <tr><td colspan=3 class=tdspaceform1> &"."nbsp;</td></tr>
	  ";
	$bbr=0;
	for ($i = 0; $i < $jlhfield; $i++) {
		$bbr++;
		$troe=(($bbr%2)==0?'trevenform2':'troddform2');
		
		if ($inputfield=="*") {
			$nmfield=mysql_field_name($fields, $i);
			$type=mysql_field_type($fields, $i);
			$lebar=mysql_field_len ($fields, $i);
		} else {
			$nmfield=$afield[$i];
			$type='varchar';
			$lebar='50';
		}
		if ($lebar>50) $lebar=50;
		if ($type=='int') 
			$lebar=10;
		else if ($type=='date') 
			$lebar=10;
		
		if ($nmfield!="id") {
			$htm.="
			<tr class='$troe' ><td>$nmfield</td>
			<td>:</td><td valign=top>";
			if ($type!="blob")
			$htm.=" 
		 <input type=text name=$nmfield id=$nmfield size=$lebar value='<?=$"."$nmfield?>'>"; //hanya selain id
		else
		$htm.="< textarea name=$nmfield id=$nmfield cols=50 rows=5><?=$"."$nmfield?></ textarea>"; 
		$htm.="</td></tr>";
		}
	}
	$htm.="
	 <tr><td colspan=3 align=center>
		<input type=submit value=submit name=submit class=buttonform1>
	 </td></tr>
     <tr><td colspan=3 valign=middle class=tdspaceform2> &nbsp;</td></tr>
	 <tr><td colspan=3 valign=middle class=tdfooterform1> &nbsp;</td></tr>
	</table>
	<input type=hidden name=id value='<?=$"."id?>'>
	<input type=hidden value='<?=$"."opr?>' name=op>
	</form>
	</div>
	</center>
	";
	
	$htmForm=$htm;
	
//htmview==========	
	$htm="";
	
	$htm="";
	$htm.="
<?php
include \"conf.php\";
kill_unauthorized_user();
$"."id=$"."_REQUEST[\"id\"]*1;
if ($"."id!=0){
	$"."sql=@mysql_query(\"select $strfield_lengkap from $nmtabel where id=\".$"."id);
	while ($"."row=mysql_fetch_array($"."sql)){
		$defForm
	}
	?>
	<table class=tbform2 align=center border=0>
	  <tr><td colspan=3 class=tdtitleform1> ".strtoupper("Data $nmtabel")."</td></tr>
      <tr><td colspan=3 class=tdspaceform1> &"."nbsp;</td></tr>
	  ";
	for ($i = 0; $i < $jlhfield; $i++) {
		if ($inputfield=="*") 
			$nmfield=mysql_field_name($fields, $i);
			else 
			$nmfield=$afield[$i];
			$htm.="<tr><td>$nmfield</td>
			<td>:</td><td><?=$"."$nmfield?></td></tr>";
	}
	$htm.="
	 <tr><td colspan=3 align=center>
		<input type=submit value=submit name=submit class=buttonform1>
	 </td></tr>
     <tr><td colspan=3 valign=middle class=tdspaceform2> &nbsp;</td></tr>
	 <tr><td colspan=3 valign=middle class=tdfooterform1> &nbsp;</td></tr>
	</table>
	<input type=hidden name=id value='<?=$"."id?>'>
	<input type=hidden value='<?=$"."opr?>' name=op>
	</form>
	";
	$htmView=$htm;
//opr========================================================================================

	$htm=" 
<?php
include_once \"conf.php\";

$defRequest

$"."opr=$"."_REQUEST[\"op\"]; 
if (($"."opr==\"tb\") || ($"."opr==\"ed\")) {
	//kill_unauthorized_user();
	 
    if ($"."opr=='tb') 
		$"."sql=\"$sqlInsert\";
	else 	{
		$"."sql=\"$sqlUpdate\";
	}
} else if ($"."opr==\"hp\"){
		kill_unauthorized_user();

	$"."sql= \"delete from $nmtabel where id='$"."id'\";
} else  {
		kill_unauthorized_user();

}

if (($"."opr=='hp') || ($"."opr=='ed') || ($"."opr=='tb')){
	$"."hasil=mysql_query($"."sql);
	if (!$"."hasil)
			um412_falr(\"query tidak bisa dilaksanakan $"."sql \");
	else {
		echo \"Operasi berhasil dilakukan\";
		redirection($"."toroot.\"index.php?rep=daf_$nmtabel\",1);
 	}
}
?>
";
	$htmOpr=$htm;
	

//==pdf===========================================================================================

$htm=" 
<?php

if ($"."cetak=='pdf') {
	
	require_once('../lib/pdf/fpdf.php');
	$"."nmFilePDF=\"tes/$nmtabel.pdf\"; 
	$"."print_page_1 = true;
	$"."print_page_2 = true;
	$"."border = 0;
	$"."height = 5;
	$"."lebar_kertas = 297; // mm
	$"."top_margin = 20; // mm
	$"."left_margin = 15; // mm
	$"."right_margin = 15; // mm
	$"."inner_width = $"."lebar_kertas - ($"."left_margin + $"."right_margin);
	$"."debug = false;
	
	class PDF extends FPDF{
	function Footer(){
	  $"."this->SetFont('Arial','',9);
	  $"."this->SetXY(297 - 15, 10);
	  $"."this->Cell(10, 8, $"."this->PageNo().\"/{nb}\", 0, 0);
	}
	}
	
	$"."pdf = new PDF('L', 'mm', 'A4');
	$"."pdf->AliasNbPages();
	$"."pdf->SetTopMargin($"."top_margin);
	$"."pdf->SetLeftMargin($"."left_margin);
	$"."pdf->SetRightMargin($"."right_margin);
	
	//$ defHtml
	 
	//lebar 
	for ($"."i = 0; $"."i <= $"."jlhfield; $"."i++) {
	$"."w_pdf[$"."i]=$"."w[$"."i]*$"."scalaPDF;
	}
	 
	$"."pdf->AddPage();
	
	/* KOP SURAT */
	$"."pdf->SetFont('Arial', 'B', 13);
	$"."pdf->Cell($"."inner_width, 8, \"DAFTAR ".strtoupper($nmtabel)."\", $"."border, 1, 'C');
	$"."pdf->SetLineWidth(0.4);
	$"."pdf->Line($"."left_margin, $"."pdf->GetY(), $"."left_margin + $"."inner_width, $"."pdf->GetY());
	$"."pdf->SetLineWidth(0.2);
	 
	//$"."sqlall=\"select $strfield_lengkap from $nmtabel\";
	$"."h=mysql_query($"."sqlall);
	
	
	//judul 
	$"."pdf->Ln();
	$"."pdf->Ln();
	$"."pdf->Ln();
	$"."pdf->SetFont('Arial', 'B', 9);
	 
	$"."pdf->Cell(15, $"."height, 'NO', 1, 0, 'C');
	for ($"."i = 0; $"."i <= $"."jlhfield; $"."i++) {
	$"."pdf->Cell($"."w_pdf[$"."i], $"."height, \"$"."afield[$"."i]\", 1, 0, 'C');
	}
	 
	$"."jumlah=0;
	$"."br=0;
	while ($"."r=mysql_fetch_array($"."h)) {         
	  $"."br++;            
	  $"."pdf->Ln(); 
	  $"."pdf->Cell(15, $"."height, $"."br, 1, 0, 'C');
		for ($"."i = 0; $"."i <= $"."jlhfield; $"."i++) {
		$"."pdf->Cell($"."w_pdf[$"."i], $"."height, $"."r[$"."afield[$"."i]], 1, 0, 'C');
		}
	}
	$"."pdf->Ln(); 
	$"."pdf->Output($"."nmFilePDF, \"F\"); 
	
	
	//echo \"<br>File tersimpan di <a href=$"."nmFilePDF>$"."nmFilePDF</a>, ...<br>\";
	echo \"<br><a href=adm/$"."nmFilePDF>Download File</a>...<br>\";
	exit;

} //cetak pdf

?>
";
  
$htmlPDF=$htm;
//=============================================================================================
//membuat file xls
//=============================================================================================
$htm=" 
<?php

//pencetakan
if ($"."cetak=='xls') {
	$"."nmFileXLS=$"."temp_path.'Daftar_$nmfile_rec_'.$"."lima.'_sd_'.$"."limb.'.xls'; 
	
	if ($"."_REQUEST['showbutton']==1) {
		echo \"<div align=center class='frmreport' >
				<br>CETAK FILE KE FORMAT MICROSOFT EXCEL<br><br>\";
		for ($"."i=0;$"."i<$"."jlhpage;$"."i++) {
			$"."uxs=$"."i+1;
			$"."limb=$"."i*$"."record_per_page;		
			$"."tempat=\"cetak\".$"."uxs;
			$"."links=$"."hal2.\"&cetak=xls&lim=\".$"."limb;
			echo \"<span id=$"."tempat>\";
			echo \"<a class=button style='width:40px' 
					href=# onclick=javascript:bukaAjax('$"."tempat','$"."links');>Hal $"."uxs</a>\";
			echo '</span>';
		}
		echo '<br><br></div>';
		exit;
	}


	error_reporting(E_ALL);
	 
	//require_once  '../lib/PHPExcel.php';
	$"."objPHPExcel = new PHPExcel();
	$"."objPHPExcel->getProperties()->setCreator('um412')
							 ->setLastModifiedBy('um412')
							 ->setTitle('Office 2007 XLSX Test Document')
							 ->setSubject('Office 2007 XLSX Test Document')
							 ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
							 ->setKeywords('office 2007 openxml php')
							 ->setCategory('Test result file');

	
	$"."objPHPExcel->setActiveSheetIndex(0);
	$"."objPHPExcel->getActiveSheet()->setTitle('$nmtabel');
	$"."objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	$"."objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
	
	//$ defHtml 
	
	//lebar 
	for ($"."i = 0; $"."i <= $"."jlhfield; $"."i++) {
	$"."w_xls[$"."i]=$"."w[$"."i]*$"."scalaXLS;
	}

	// garis tepi
	$"."styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
 

	$"."objPHPExcel->getActiveSheet()->mergeCells('A1:'.$"."lastcol.'1');
	$"."objPHPExcel->getActiveSheet()->mergeCells('A2:'.$"."lastcol.'2');
	$"."objPHPExcel->getActiveSheet()->mergeCells('A3:'.$"."lastcol.'3');
		 
	$"."rx=1;//rowexcel
	$"."objPHPExcel->getActiveSheet()->setCellValue('A'.$"."rx, $"."judul1);$"."rx++;
	
	$"."objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('Arial');
	$"."objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(13);
	$"."objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$"."objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	 
 	$"."objPHPExcel->getActiveSheet()->setCellValue('A'.$"."rx, $"."judul2);$"."rx++; 
 	$"."objPHPExcel->getActiveSheet()->setCellValue('A'.$"."rx, $"."judul3a);$"."rx++; 
 	$"."objPHPExcel->getActiveSheet()->setCellValue('A'.$"."rx, $"."tglcetak);$"."rx++; 
 
	$"."rx++;
	$"."rx++;
	//SetFont('Arial', 'B', 9);	
	$"."row_heading=$"."rx;//baris heading	
	$"."range_heading='A'.$"."row_heading.':'.$"."lastcol.$"."row_heading;
	
	$"."objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
	$"."objPHPExcel->getActiveSheet()->setCellValue('A'.$"."rx, 'No'); 
	 
	 for ($"."i = 0; $"."i < $"."jlhfield; $"."i++) {
 	 	$"."objPHPExcel->getActiveSheet()->getColumnDimension(\"$"."crx[$"."i]\")->setWidth($"."w_xls[$"."i]);
	   	$"."objPHPExcel->getActiveSheet()->setCellValue(\"$"."crx[$"."i]\".$"."rx, strtoupper($"."afield[$"."i])); 	 }     
	 $"."rx++;
	
	//warna
	$"."objPHPExcel->getActiveSheet()->getStyle($"."range_heading)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	$"."objPHPExcel->getActiveSheet()->getStyle($"."range_heading)->getFill()->getStartColor()->setARGB('FF808080');
	
	//DAFTAR 
	//$"."sq=\"select $strfield_lengkap from $nmtabel\";
	$"."h=mysql_query($"."sql);
	$"."br=0;
	 while ($"."v= mysql_fetch_array($"."h)){
	  $"."br++;	 
	  $"."objPHPExcel->getActiveSheet()->setCellValue('A'.$"."rx, $"."br+$"."lim); 
	  for ($"."i = 0; $"."i<$"."jlhfield; $"."i++) {
		$"."nmfield=$"."afield[$"."i];
		 $"."objPHPExcel->getActiveSheet()->setCellValue(\"$"."crx[$"."i]\".$"."rx, trim($"."v[$"."nmfield]));		
	  } 
	  $"."rx++;
	}//persiswa
	
	$"."lastrow=$"."br+$"."row_heading;
	$"."range_content='A'.$"."row_heading.':'.$"."lastcol.$"."lastrow;
	$"."range_jumlah='A'.($"."lastrow+1).':'.$"."lastcol.($"."lastrow+1);
	 
	$"."objPHPExcel->getActiveSheet()->getStyle($"."range_content)->applyFromArray($"."styleThinBlackBorderOutline);
	$"."objPHPExcel->getActiveSheet()->getStyle($"."range_content)->getFont()->setSize(8);     
	
	$"."objPHPExcel->getActiveSheet()->getStyle($"."range_jumlah)->applyFromArray($"."styleThinBlackBorderOutline);
	$"."objPHPExcel->getActiveSheet()->getStyle($"."range_jumlah)->getFont()->setSize(8);     
	
	$"."objPHPExcel->setActiveSheetIndex(0);
	
	@unlink($"."nmFileXLS); //hapus file
	error_reporting(E_ALL);
	
	date_default_timezone_set('Europe/London');  
	$"."objWriter = PHPExcel_IOFactory::createWriter($"."objPHPExcel, 'Excel5');
	$"."objWriter->save(str_replace('.php', '.xls', $"."nmFileXLS));
	echo \"<br><a href=adm/$"."nmFileXLS>Download File</a>...<br>\";
	exit;
}

?>
";

$htmlXLS=$htm;

//daf-rep===========================================================================================
	
$htm="
<?php
include_once \"conf.php\";

$defHtml

$"."titlereport=(\"Daftar ".strtoupper($nmtabel)."\");
$"."isicombo=\"Nama;nama,No ID;$nmtabel.id\";

$"."admrep=\"$nmfile\";
$"."hal2=$"."um_path.\"$"."admrep-daf.php?\";


include $"."um_path.\"frmreport.php\";
 
 //cetak ke excel
?>$htmlXLS <?php

//cetak ke pdf
?>$htmlPDF<?php


//echo $"."sql;

	?> 
	  <div id=tcari>
	  <div id=cetak0></div>
     <?php
     if ($"."nr>0) {
	 ?>
     <p><center>
	 <a href=# onclick=\"bukaAjax('cetak0','<?=$"."hal2?>&showbutton=1&cetak=xls')\" class=button2>Cetak XLS </a>
	 <br><?=$"."lh?>
	 </center></p>
	 <table  border=0 class=tbreport align=center cellpadding=0 >
	 <tr><td  class=tdjudul>NO</td>
	 ";	
 
	for ($i = 0; $i < $jlhfield; $i++) {
	$w_html[$i]=$w[$i]*$scalaHtml; 
	$htm.="<td class=tdjudul>".strtoupper($afield[$i])."</td>
		"; 
	 } 	
	 $htm.="
	<td class=tdjudul>Operasi</td></tr>
	<tr>
	 
	 $tdJudul 
	 <?php
	  $"."jumlah=0;
	  $"."br=0;
	  while ($"."r=mysql_fetch_array($"."h)) {
		  $"."id=$"."r['id'];
		  $"."idt=\"$idt"."_\".$"."r['id'];
		  $"."br++;
		  $"."troe=(($"."br%2)==0?'trevenform2':'troddform2');
		  $"."jumlah=$"."jumlah+$"."jlh[0];
			?>
			<tr class='<?=$"."troe?>' id='<?=\"m_\".$"."idt?>'  >
			<td><?=($"."br+$"."lim)?></td>
	";
	
	for ($i = 0; $i <  $jlhfield; $i++) { 
	$htm.= "<td><?=$"."r[".$afield[$i]."]?></td>
		"; 
	 } 
	
	 $htm.="
	 <?php
	echo \"<td width=100>\";  
		echo tboperasi($"."um_path.\"$nmfile\",$"."id,'id',\"all\",\"$"."idt\",'100'); 
	echo \"</td></tr>\"; 
	
	echo \"</td></tr><tr><td colspan=".($jlhfield+1)."><div id='$"."idt' ></div></td></tr>\";

 	  }
	  ?> </table> <?php
	 }	 else { //jika tidak ketemu
		echo '<center><br>Data tidak ditemukan</center>';
		echo tboperasi($"."um_path.\"$nmfile\",$"."id,'id',\"all\",\"\",'100'); 
		
	}
?>
</div>
";
	$htmDaf=$htm;
	//koneksi();
}
?>
<p>UM412 PAGE GENERATOR V.2.0.1</p>
<form action="?" target="generate">
<input type=hidden name=page value='adm'>
<input type=hidden name=rep value='gen'>
  SQL
    <input name="inputfield" type="text" id="inputfield" value="<?=$inputfield?>" size="80" />
  atau<br />
  Tabel <input name="nmtabel" type="text" value="<?=$nmtabel?>" />
  Nama File Hasil<input name="nmfile" type="text" value="<?=$nmfile?>" />
   
  DB<input name="db" type="text" value="<?=$db?>" />
  <input type="submit" name="simpan" value="generate" />
</form>
<p>
  <input type="button" value="form" onclick="testHasil(1)" />
  <textarea name="hasil1" id="hasil1" cols="100" rows="5"><?=$htmForm?>
</textarea>
  <br />
  <input type="button" value="opr" onclick="testHasil(2)" />
  <textarea name="hasil2" id="hasil2" cols="100" rows="5"><?=$htmOpr?>
  </textarea>
  <br />
  <input type="button" value="daf" onclick="testHasil(3)" />
  <textarea name="hasil3" id="hasil3" cols="100" rows="5"><?=$htmDaf?>
  </textarea>
  <br />
  <input type="button" value="pdf" onclick="testHasil(3)" />
  <textarea name="hasil4" id="hasil4" cols="100" rows="5"><?=$htmlPDF?>
  </textarea>
  <br />
  <input type="button" value="xls" onclick="testHasil(4)" />
  <textarea name="hasil5" id="hasil5" cols="100" rows="5"><?=$htmlXLS?>
  </textarea>
  <br />
  <input type="button" value="view" onclick="testHasil(5)" />
  <textarea name="hasil6" id="hasil6" cols="100" rows="5"><?=$htmView?>
  </textarea>
  <br />
  <script>
function testHasil(i){
	
	newWindow = window.open("someDoc.html","subWind",
"status,menubar,height=400,width=300");
	htm=document.getElementById("hasil"+i).value;
	newWindow.document.write(htm);
	newWindow.focus( );

	}
  </script>
</p>
