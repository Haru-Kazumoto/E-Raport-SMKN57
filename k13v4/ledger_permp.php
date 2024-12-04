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
  <form action=ledger.php method=post>
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
$top_margin = 20; // mm
$left_margin = 15; // mm
$right_margin = 15; // mm
$inner_width = $lebar_kertas-($left_margin + $right_margin);
$debug = false;

//require_once('../library/pdf/fpdf.php');
require_once('pdf/fpdf.php');

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
  $tb="";
  
  if ($kode_mp=="") {
	  $map_mata_pelajaran = '';
	  $mapsx = mysql_fetch_array(
				mysql_query("select * from Map_Matapelajaran_Kelas where kode_kelas = '$kode_kelas' 
							and semester = '$semesnter' "));
	  $map_mata_pelajaran = $mapsx["matapelajaran"];
	  $daftar_mata_pelajaran = explode('#', $map_mata_pelajaran); 
  }
  else
  	$daftar_mata_pelajaran[]=$kode_mp;
  
  $jlhMP=count(daftar_mata_pelajaran);

 //Kompetensi per mata pelajaran
  $i=0; //kompetensi
  $j=0; //mp
  $jlhKompetensi=0;
  $judul_kompetensi=array();
  $daftar_kompetensi=array(); 
  
 foreach($daftar_mata_pelajaran as $kode_mata_pelajaran){	 
	 $hd=mysql_query("select kode from Kompetensi where kode_matapelajaran =
					'$kode_mata_pelajaran' and semester ='$semester'");
	 echo "select kode from Kompetensi where kode_matapelajaran =
					'$kode_mata_pelajaran' and semester ='$semester'";
	 $j++;
	 $jlhnilai=0;
	 $k=0;
	  
	 while ($daftar_kompetensi_temp = mysql_fetch_array($hd)){ 	 
	 	if (!$daftar_kompetensi_temp) die("<br>Mata pelajaran  tidak masuk di semester yang dipilih....");
	 	  $i++;
		  $daftar_kompetensi[$i]=$daftar_kompetensi_temp[0];
		  $judul_kompetensi[$i]=$daftar_kompetensi_temp[0];		  
		  $jlhnilai+=$daftar_kompetensi_temp[0]*1;
		  $k++;
	}
	 if ($k==0) $k=1;
	 
	 $rata=$jlhnilai/$k;
		 
	 $i++;
	 $daftar_kompetensi[$i]=$rata;
	 $judul_kompetensi[$i]="R";
  }
  $jlhKompetensi=$i;
 
 
 

//mencari kop
	$sekolah = mysql_fetch_array(mysql_query("select * from sekolah"));
	  $kop_1 = $sekolah["kop_1"];
	  $kop_2 = $sekolah["kop_2"];
	  $kop_3 = $sekolah["kop_3"];
	  $kop_4 = $sekolah["kop_4"];
	  
    $pdf->AddPage();
	

    /* KOP SURAT */
    $pdf->SetFont('Arial', 'B', 13);
    if($kop_1) $pdf->Cell($inner_width, 8, $kop_1, $border, 1, 'C');
    if($kop_2) $pdf->Cell($inner_width, 8, $kop_2, $border, 1, 'C');
    if($kop_3) $pdf->Cell($inner_width, 8, $kop_3, $border, 1, 'C');
    $pdf->SetFont('Arial', '', 13);
    if($kop_4) $pdf->Cell($inner_width, 8, $kop_4, $border, 1, 'C');
    $pdf->SetLineWidth(0.4);
    $pdf->Line($left_margin, $pdf->GetY(), $left_margin + $inner_width, $pdf->GetY());
    $pdf->SetLineWidth(0.2);
	
$kompetensi_per_page = 10;

    $w1 = 10;
    $w2 = 25;
    $w4 = 16;

    if($page == 0) $lebar_kolom_nama = $inner_width - ($w1+$w2+$w4 * $jlhKompetensi);
    $w3 = $lebar_kolom_nama;


    /* KOP SURAT */
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Ln();
    $pdf->Cell($inner_width, 8, 'LEDGER', $border, 0, 'C');
    $pdf->Ln();
    $pdf->Cell($inner_width, 8, 'Kelas : '.$kelas, $border, 0, 'C');
    $pdf->Ln();
    $pdf->Cell($inner_width, 8, 'Semester : '.$semester, $border, 0, 'C');
    $pdf->Ln();
    $pdf->Cell($inner_width, 12, 'Tahun Ajaran : '.$tahun, $border, 0, 'C');
    $pdf->Ln();
    $pdf->Ln();
  
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($w1, $height, 'No.', 1, 0, 'C');
    $pdf->Cell($w2, $height, 'NIS', 1, 0, 'C');
    $pdf->Cell($w3, $height, 'Nama', 1, 0, 'C');
	  foreach($judul_kompetensi as $kompetensi) { 
		$pdf->Cell($w4, $height, $kompetensi, 1, 0, 'C');
	  } //perkompetensi
      $pdf->Ln();

$h=mysql_query("select * from siswa where kode_kelas='$kode_kelas'");
 while ($v= mysql_fetch_array($h)){
			 
    
      $pdf->Cell($w1, $height, $i, 1, 0, 'C');
      $pdf->Cell($w2, $height, trim($v["nis"]), 1, 0, 'L');
      $pdf->Cell($w3, $height, trim($v["nama"]), 1, 0, 'L');
	  
	  //cek nilai perkompetensi
	  $i=0;
	  $jlhnilai=0;
	  foreach($judul_kompetensi as $kompetensi) { 
	  	
	  	$nilai=array();
		if ($kompetensi!="R") {//rata
			$hs=mysql_query("select nilai from Nilai_Kompetensi_Siswa where nis = '".$v["nis"].
											"' and kode_kompetensi = '".$kompetensi."'");
			
			$nilai =  mysql_fetch_array($hs);
			$pdf->Cell($w4, $height, $nilai[0] , 1, 0, 'C');
			
			//if ($nilai[0]>0) { //dirata2 jika nilai >0
				$i++;
				$jlhnilai+= $nilai[0];
			//}
		}  else {
			$rata= $jlhnilai/$i;
			$pdf->Cell($w4, $height, $rata , 1, 0, 'C');
			$i=0;$jlhnilai=0;
			}
		
	  } //perkompetensi
	  $pdf->Ln();
    }//persiswa


#-------------------------------------------------------------------------------

$pdf->Output("ledger.pdf", "F");
?>


