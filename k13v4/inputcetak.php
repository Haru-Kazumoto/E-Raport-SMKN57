<?php
$useJS=2;
include_once "conf.php";
extractRequest();

$kelas=cariField("select nama from kelas where kode='$kode_kelas'");

if ($_REQUEST['cetak']=='pdf') {
	$xkelas=$kelas;
	
	extractRecord("select nis,nama,nisn,wali as wali_nama,agama from siswa where nis='$nis'");
	extractRecord("select kelas.nama as kelas,guru.nama as walikelas_nama,guru.nip as walikelas_nip from kelas left join guru on kelas.kode_guru=guru.kode where kelas.kode='$kode_kelas'");
	extractRecord("select kode_kompetensikeahlian from kelas where kode='$kode_kelas'");
	extractRecord("select kode_programkeahlian,nama as k_keahlian from kompetensi_keahlian where kode='$kode_kompetensikeahlian'");
	extractRecord("select kode_bidangkeahlian,nama as p_keahlian from program_keahlian where kode='$kode_programkeahlian'");
	extractRecord("select nama as b_keahlian from bidang_keahlian where kode='$kode_bidangkeahlian'");
	
	//--opindah
	if ($jcetak=='spindah') {
		$nmFilePDF="CATATAN_PINDAH_SEKOLAH_NIS_".$nis."_".str_replace(" ","",$nama).'.pdf';
		$w=array(70,70,240,180);
		$w=hitungSkala($w,620);
		$html='<div style="text-align:center;font-size:14px"><b>KETERANGAN PINDAH SEKOLAH<br>NAMA PESERTA DIDIK : '.$nama.'</b></div>
		<p>&nbsp;</p>
		<table width="620" border="1" cellpadding="5" cellspacing="0">
		  <tr>
			<td width="620" colspan="4" align="center">KELUAR</td>
			</tr>
		  <tr>
			<td width="'.$w[0].'" align="center"><b>Tanggal</b></td>
			<td width="'.$w[1].'" align="center"><b>Kelas yang Ditinggalkan</b></td>
			<td width="'.$w[2].'" align="center"><b>Sebab-sebab Keluar atau Atas Permentaan (Tertulis)</b></td>
			<td width="'.$w[3].'" align="center"><b>Tanda Tangan Kepala Sekolah, 
			<br>Stempel Sekolah, 
			<br>dan Tanda Tangan Orangtua/Wali</b></td>
		  </tr>';
	  $sq="select * from siswa_pindah where nis='$nis'";
		$hq	=mysql_query($sq);
		 $r=mysql_fetch_array($hq); 
		 
		 for ($x=0;$x<=1;$x++) {
			if ($x!=0) {
				$r=array();	 
				$webmasterCity="............................";
				$kepsek_nip   ="";
				$kepsek_nama  =".....................................";
				
			}
		  $html.='
		  <tr>
			<td  align="center">'.SQLtotgl($r[tgl]).'</td>
			<td  align="center">'.$r[p_kelas].'</td>
			<td>'.$r[catatan].'</td>
			<td><p>'.$webmasterCity.', '.($r[tgl]==''?'..........................':SQLtotgl($r[tgl])).'
			<br>Kepala Sekolah,</p>
			<p style="font-size:45px"></p>
		<p>
		<br>'.$kepsek_nama.'
		<br>NIP.'.$kepsek_nip.'</p>
		<p></p>
		<p>Orangtua/Wali,</p>
			<p style="font-size:45px"></p>
		<p><br>'.$wali_nama.'<br>
			</p></td>
		  </tr>
		  ';
		  
	  }
	  $html.='</table>';
	  $html='<div style="font-size:11px">'.$html.'</div>';
	} elseif ($jcetak=='cpres') {
		$ckur=$cekstra=$ckhusus="";	
		extractRecord("select cpres_kurikuler as ckur,cpres_ekstra as cekstra,cpres_khusus as ckhusus from siswa where nis='$nis'");
		$akur=explode("|",$ckur."|||||");
		$aekstra=explode("|",$cekstra."|||||");
		$akhusus=explode("|",$ckhusus."|||||");
		$ckur=$cekstra=$ckhusus="";
		for ($i=0;$i<=4;$i++) {
			if ($i>0) {
				$ckur.="<p></p>";
				$cekstra.="<p></p>";
				$ckhusus.="<p></p>";
			}
			$ckur.="<p>$akur[$i]</p>";
			$cekstra.="<p>$aekstra[$i]</p>";
			$ckhusus.="<p>$akhusus[$i]</p>";			
		}
		$ckur.="<p></p>";
		$cekstra.="<p></p>";
		$ckhusus.="<p></p>";
		//--ocatatan prestasi
		$nmFilePDF="CATATAN_PRESTASI_NIS_".$nis."_".str_replace(" ","",$nama).'.pdf';
		$w=array(2,10,30);
		$w=hitungSkala($w,620);
		$html='
		<div style="text-align:center;font-size:14px"><b>CATATAN PRESTASI YANG PERNAH DICAPAI<br>NAMA PESERTA DIDIK : '.$nama.'</b></div>
		<p>&nbsp;</p>
		
			<br>
		<table width="620" border="1" cellpadding="5" cellspacing=0>
		  <tr>
			<td width="'.$w[0].'" valign="top" align="center"><b>NO</b></td>
			<td width="'.$w[1].'" valign="top" align="center"><b>Prestasi yang Pernah Dicapai</b></td>
			<td width="'.$w[2].'" valign="top" align="center"><b>Keterangan</b></td>
		  </tr>
		  <tr>
			<td  valign="top" align="center">1</td>
			<td valign="top" >Kurikuler</td>
			<td valign="top" >'.$ckur.'</td>
		  </tr>
		  <tr>
			<td valign="top" align="center">2</td>
			<td valign="top" >Ekstra Kurikuler</td>
			<td valign="top" >'.$cekstra.'</td>
		  </tr>
		  <tr>
			<td valign="top" align="center">3</td>
			<td valign="top" >Catatan Khusus Lain</td>
			<td valign="top" >'.$ckhusus.'</td>
		  </tr>
		</table>
		';
		$html='<div style="font-size:11px">'.$html.'</div>';
	} elseif ($jcetak=='dsiswa') {
		$nmFilePDF="DATA_SISWA_KELAS_".str_replace(" ","",$kelas).'.pdf';
		$po="L";
		$aMargin=array(10,10,12,10);
		$w=array(2,3,13,2,9,4,9,13,15);
		$w=hitungSkala($w,960);
		$showPDFFooter=$showPDFHeader=false;

		$html='<div style="font-size:14px;font-weight:bold;text-align:center">
		<br />'.$judulLap[0].'
		<br />'.$judulLap[1].'
		<br />'.$judulLap[2].'
		<br />
		<br />DATA SISWA KELAS '.$kelas.'
		</div><br />
			<br />
	
		<table width="950" border="1" cellpadding="5" cellspacing="0">
		  <tr>
			<td width="'.$w[0].'" align="center" ><strong>NO</strong></td>
			<td width="'.$w[1].'" align="center" ><strong>NIS</strong></td>
			<td width="'.$w[2].'" align="center" ><strong>NAMA</strong></td>
			<td width="'.$w[3].'" align="center" ><strong>L/P</strong></td>
			<td width="'.$w[4].'" align="center" ><strong>Tempat &amp; Tgl. Lahir</strong></td>
			<td width="'.$w[5].'" align="center" ><strong>Agama</strong></td>
			<td width="'.$w[6].'" align="center" ><strong>Nama Orangtua/Wali</strong></td>
			<td width="'.$w[7].'" align="center" ><strong>No. STTB SMP</strong></td>
			<td width="'.$w[8].'" align="center" ><strong>Alamat Siswa/Orangtua</strong></td>
		  </tr>';
		 $hq=mysql_query("select * from siswa where kode_kelas='$kode_kelas' ");
		 
		 $br=0;
		 while ($r=mysql_fetch_array($hq)) {
			 $br++;
			 $xagama=$aAgama[$r[agama]];
			 $html.= '
			  <tr>
				<td align="center" >'.$br.'</td>
				<td align="center" >'.$r[nis].'</td>
				<td>'.$r[nama].'</td>
				<td align="center" >'.($r[gender]==0?'L':'P').'</td>
				<td>'.$r[kota_lahir].', '.SQLtotgl($r[tanggal_lahir]).'</td>
				<td>'.$xagama.'</td>
				<td>'.$r[wali].'</td>
				<td>'.$r[sttb_sekolah_asal].'</td>
				<td>'.$r[alamat_ortu].'</td>
			  </tr>';
		 }
		  $html.='</table>
		  <br />
		  <br />
		  <table width="950" border="0">
		  <tr><td width="700"></td>
		  <td>'.$webmasterCity.', '.date('d M Y').'</td></tr>
		  <tr><td width="700"></td>
		  <td>Kepala Sekolah,</td></tr>
		  <tr><td width="700"><br /><br /><br /><br /><br /></td>
		  <td></td></tr>
		  <tr><td width="700"></td>
		  <td><u>'.$kepsek_nama.'</u></td></tr>
		  <tr><td width="700"></td>
		  <td>NIP.'.$kepsek_nip.'</td></tr>
		  </table>
		  ';
		$html='<div style="font-size:10px">'.$html.'</div>';
	}
	
	require_once($lib_path.'tcpdf/tcpdf_include.php');
	class MYPDF extends TCPDF {
		public function Header() {
			$yy=26.8;
			$this->Line(15, $yy, 201.3,$yy,$arrStyle);	
		}
	
		public function Footer() {
			$this->SetY(-25);
			$this->SetFont('helvetica', 'I', 8);
			$this->Cell(190, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(),0, false, 'R', 0, '', 0, false, 'T', 'M');
			$arrStyle=array('width' => 0.3, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0,0,0));
			//$yy=272;
			//$this->Line(15, $yy, 201.3,$yy,$arrStyle);
			$yy=272;
			$this->Line(15, $yy, 201.3,$yy,$arrStyle);
		}
	}
	
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	if (isset($showPDFHeader)) 
		$pdf->setPrintHeader($showPDFHeader);
	else
		$pdf->setPrintHeader(false);
	
	if (isset($showPDFFooter)) 
		$pdf->setPrintFooter($showPDFFooter);
	else
		$pdf->setPrintFooter(false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor($namaSekolahSingkat);
	$pdf->SetTitle($nmFilePDF);
	if (is_array($aMargin)) {
		$pdf->SetMargins($aMargin[3], $aMargin[0],$aMargin[1]);
		$pdf->SetAutoPageBreak(TRUE, $aMargin[2]);
	}else {
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	}
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	
	if ($po!='') $pdf->setPageOrientation($po);
	$pdf->SetFont('helvetica', '', 12);
	$pdf->AddPage();
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->Output($nmFilePDF, 'D');
	 exit;
}
 


$idForm="fcetak_".rand(1231,2317);
$nfAction="inputcetak.php?cetak=pdf&jcetak=$jcetak";
//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\" ";
$t="";
$t.="<div id=ts"."$idForm ></div>";
$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
$t.="<input type=hidden name=jcetak2 value='$jcetak2'>";


echo $t;	
$judul=($jcetak=='spindah'?"Catatan Pindah Sekolah":($jcetak=='cpres'?"Catatan Prestasi":"Data Siswa "));
?> 
 
<table >
<tr class=troddform2 $sty >
	<td colspan=2><div class=titlepage>Cetak <?=$judul?></div></td>
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas </td>
	<td>: <span id=tkelas><?=um412_isiCombo5('select * from kelas','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboCetak('kelas')");?></span></td> 
</tr>

<tr class=troddform2 $sty <?=($jcetak=='dsiswa'?"style='display:none'":'') ?> >
		<td class=tdcaption >Siswa</td>
		<td>: <span id=tsiswa>-</span></td> 
	</tr>

<tr class=troddform2 $sty >
	<td class=tdcaption ></td>
	<td><?    
	
	echo "<input type=submit value='Cetak ' class='btn btn-success btn-sm' >";
	
	?></td> 
    
    
</tr>
</table>
</form>
<br>
<div id=treport></div>

