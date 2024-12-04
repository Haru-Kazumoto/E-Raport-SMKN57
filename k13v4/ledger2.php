<?php
include_once "um412_func.php";

function angkaromawi($angka)    {
 $romawi = array (1 =>   'I', 'II', 'III', 'IV', 'V',
						'VI', 'VII', 'VIII', 'IX', 'X',
						'XI', 'XII','XIII', 'XIV', 'XV');
 return $romawi[$angka];
}


if ($_REQUEST['cetak']!='cetak') {
?>
  <form action=? method=post>
<br><br>
<br><br>
<table align=center width=800 border=0>
<tr><td>Kelas</td>
	<td>: <?php echo um412_isicombo5("select * from kelas","kode_kelas","kode","tingkat,nama",$all="",$default="",$aksi="");?>    </td>
	</tr>
<tr><td>Semenster</td>
<td>: <?php echo um412_isicombo5("1,2","semester","semester","kode",$all="",$default="",$aksi="");?>
     </td></tr>
<tr><td>Tahun Ajaran</td><td>: <input name=tahun value='2010/2011'></td></tr>
<tr><td>Mata Pelajaran</td><td>:
<?php echo um412_isicombo4("select * from matapelajaran","kode_mp","kode","nama",$all="Mata Pelajaran",$default="",$aksi="");?> </td>
</tr>
 <tr><td colspan=2 align="center"><input type=submit value=cetak name=cetak></td></tr>
</table>
</form>
<?php
exit;
}
?>
<?php
//pencetakan
$jenis_ledger="isi";
$kode_kelas=$_REQUEST["kode_kelas"];
$semester=$_REQUEST["semester"];
$tahun=$_REQUEST["tahun"];
$kode_mp=$_REQUEST["kode_mp"];

//mencari kelas
  $kls= mysql_fetch_array(
				mysql_query("select * from kelas where kode = '$kode_kelas' "));
  $kelas=angkaromawi($kls["tingkat"])." ".$kls["nama"];
#Config
$print_page_1 = true;
$print_page_2 = true;
$border = 0;
$height = 5;
$lebar_kertas = 297; // mm
$top_margin = 10; // mm
$left_margin = 10; // mm
$right_margin = 10; // mm
$inner_width = $lebar_kertas - ($left_margin + $right_margin);
$debug = false;
 
require_once($lib_path.'fpdf/fpdf.php');

class PDF extends FPDF{
function Footer(){
  $this->SetFont('Arial','',9);
  $this->SetXY(297 - 15, 10);
  $this->Cell(10, 8, $this->PageNo()."/{nb}", 0, 0);
}
}


$pdf = new PDF('L', 'mm', 'A4');

$pdf->AliasNbPages();
$pdf->SetTopMargin($top_margin);
$pdf->SetLeftMargin($left_margin);
$pdf->SetRightMargin($right_margin);

//mengetahui daftar kompetensi masing masing
  // Map Mata Pelajaran
  $daftar_mata_pelajaran=array();
  $judul_mp=array();
   
  $tb="";
  
  if ($kode_mp=="") {
	  $map_mp = '';
	  $mapsx = mysql_fetch_array(
				mysql_query("select matapelajaran from Map_Matapelajaran_Kelas where kode_kelas = '$kode_kelas' 
							and semester = '$semesnter' "));
	  $map_mp = $mapsx["matapelajaran"];
	  $daftar_mp = explode('#', $map_mp); 
  }
  else
  	$daftar_mp[]=$kode_mp;


 //Kompetensi per mata pelajaran
  $i=0; //kompetensi
  $jlhmp=0; //mp
  

 foreach($daftar_mp as $kode_mp){	
 	$jlhmp++; 
	 $k=0; 
	 $judul_mp[$jlhmp]=$kode_mp;
	 echo "<br>$jlhmp . $kode_mp  ";
	 }

//mencari kop
	$sekolah = mysql_fetch_array(mysql_query("select * from sekolah"));
	  $kop_1 = $sekolah["kop_1"];
	  $kop_2 = $sekolah["kop_2"];
	  $kop_3 = $sekolah["kop_3"];
	  $kop_4 = $sekolah["kop_4"];
	  
    $pdf->AddPage();
	

    /* KOP SURAT */
    $pdf->SetFont('Arial', 'B', 13);
    //if($kop_1) $pdf->Cell($inner_width, 8, $kop_1, $border, 1, 'C');
    if($kop_2) $pdf->Cell($inner_width, 8, $kop_2, $border, 1, 'C');
    //if($kop_3) $pdf->Cell($inner_width, 8, $kop_3, $border, 1, 'C');
    //$pdf->SetFont('Arial', '', 13);
    //if($kop_4) $pdf->Cell($inner_width, 8, $kop_4, $border, 1, 'C');
    //$pdf->SetLineWidth(0.4);
    //$pdf->Line($left_margin, $pdf->GetY(), $left_margin + $inner_width, $pdf->GetY());
    $pdf->SetLineWidth(0.2);
	
$kompetensi_per_page = 10;

    $w1 = 10;
    $w2 = 10;
    $w4 = 10;

    if($page == 0) $lebar_kolom_nama = $inner_width - ($w1+$w2+$w4 * $jlhmp);
    $w3 = $lebar_kolom_nama;


    /* KOP SURAT */
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Ln();
    $pdf->Cell($inner_width, 8, 'LEDGER', $border, 0, 'C');
    $pdf->Ln();
    $pdf->Cell($inner_width, 8, 'Kelas : '.$kelas, $border, 0, 'C');
    $pdf->Ln();
    $pdf->Cell($inner_width, 8, 'Semester : '.$semester.' Tahun Ajaran : '.$tahun, $border, 0, 'C');
    $pdf->Ln();
 //   $pdf->Cell($inner_width, 8, , $border, 0, 'C');
  //  $pdf->Ln();
  
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($w1, $height, 'No.', 1, 0, 'C');
    $pdf->Cell($w2, $height, 'NIS', 1, 0, 'C');
    $pdf->Cell($w3, $height, 'Nama', 1, 0, 'C');
	  foreach($judul_mp as $jmp) { 
		$pdf->Cell($w4, $height, $jmp, 1, 0, 'C');
	  } //perkompetensi
      $pdf->Ln();

$h=mysql_query("select * from siswa where kode_kelas='$kode_kelas'");
$i=0;
 while ($v= mysql_fetch_array($h)){
  $i++;		 
  $pdf->Cell($w1, $height, $i, 1, 0, 'C');
  $pdf->Cell($w2, $height, trim($v["nis"]), 1, 0, 'L');
  $pdf->Cell($w3, $height, trim($v["nama"]), 1, 0, 'L');	  
  //cek nilai perkompetensi	 
  foreach($judul_mp as $judulmp) { 	 
	$sn1="select avg(nilai) from Nilai_Kompetensi_Siswa inner join kompetensi
					on nilai_kompetensi_siswa.kode_kompetensi = kompetensi.kode where nilai_kompetensi_siswa.nis = '".$v["nis"]."' and kompetensi.kode_matapelajaran='$judulmp' and kompetensi.semester='$semester' group by kompetensi.kode_matapelajaran";	
					
	$hs=mysql_fetch_row(mysql_query($sn1));	 
	$rata=ceil($hs[0]);
	$pdf->Cell($w4, $height, $rata , 1, 0, 'C');
  } //permp
  $pdf->Ln();
}//persiswa

#-------------------------------------------------------------------------------
$nmfile="rekap_nilai_per_mp.pdf";
$pdf->Output("$nmfile", "F");
echo "<a href=$nmfile>download hasil</a>";
?>


