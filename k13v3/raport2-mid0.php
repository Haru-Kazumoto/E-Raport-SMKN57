<?php
function cekPB($jbr=0){
	global $jdes1,$jdes2,$tbhead,$charperbr;
	$t="";
	if ($jbr>0) {//add
		$jdes1+=$charperbr*$jbr;
		$jdes2+=$charperbr*$jbr;
	}
	if(($jdes1>=2000)||($jdes2>=2000))		{
		if ($tbhead!='') $t.="</table>";
		$t.="</div><div class=page-landscape >".$tbhead;
		$jdes1=0;
		$jdes2=0;
	} 
	//else $t.="$jdes1 $jdes2 >";
	return $t;
	
}

$shtml="";
$jcn=1; //jenis penilaian 1:murni 2:konversi
$vsemester=$semester;

if (!isset($tglcetak)) 
	$tglcetak='';
else	
	$tglcetak=tglindo(tgltosql($tglcetak));
if ($nocetak==0) {
	$printpage1=$printpage2=$printpage3=$printpage3b=$printpage4=$printpage5=$printpage5b=$printpage6=false;
	if ($jcetak=='Raport'){
		if ($jmid!=1) {//mid semester
			
			if ($dicetak=='Sampul') {
				$printpage1=$printpage2=true;
			}
			if ($dicetak=='Biodata') {
			$printpage3b=true;
			}
			if ($dicetak=='Nilai') {
			$printpage4=$printpage6=true;
			}
			/*
			$printpage1=(isset($_REQUEST['pcetak_0'])?true:false);//sampul
			$printpage2=(isset($_REQUEST['pcetak_0'])?true:false);//identitas sekolahtr			
			$printpage3b=(isset($_REQUEST['pcetak_2'])?true:false);//biodata
			$printpage4=(isset($_REQUEST['pcetak_3'])?true:false);//capaian
			$printpage6=(isset($_REQUEST['pcetak_3'])?true:false);//cas
			$printpage3=(isset($_REQUEST['pcetak_1'])?true:false);//ketentuan
			$printpage5=(isset($_REQUEST['pcetak_3'])?true:false);//deskripsi		
			*/
//			$printpage8=(isset($_REQUEST['pcetak_8'])?true:false);//cas
			$printpage5b=false;//khs
		} else {
			$printpage4=true;
		}
	}
	else {
		$printpage5b=true;//khs
	}
}
$aAbjad=("ABCDEFGHIJKLMNOPQRSTUVWXYZ");

extractRecord("select nis,nama,nisn,wali as wali_nama,agama from siswa where nis='$nis'" );
$xAgama=$aAgama[$agama*1];
//echo "Agama $xAgama ";
extractRecord("select kelas.nama as kelas,guru.nama as walikelas_nama,guru.nip as walikelas_nip from kelas left join guru on kelas.kode_guru=guru.kode where kelas.kode='$kode_kelas'");
extractRecord("select kode_kompetensikeahlian from kelas where kode='$kode_kelas'");
//extractRecord("select kode_programkeahlian,nama as k_keahlian from kompetensi_keahlian where kode='$kode_kompetensikeahlian'");
//extractRecord("select kode_bidangkeahlian,nama as p_keahlian from program_keahlian where kode='$kode_programkeahlian'");
//extractRecord("select nama as b_keahlian from bidang_keahlian where kode='$kode_bidangkeahlian'");
 
$xkelas=$kelas;



//cari bobot pengetahuan, keterampilan dan sikap
extractRecord("select bobotpg,bobotsk,bobotkt  from tbconfig1");
$aBobotPg=explode("#",$bobotpg);$jBobotPg=$aBobotPg[0]+$aBobotPg[1]+$aBobotPg[2];
$aBobotKt=explode("#",$bobotkt);$jBobotKt=$aBobotKt[0]+$aBobotKt[1]+$aBobotKt[2];
$aBobotSk=explode("#",$bobotsk);$jBobotSk=$aBobotSk[0]+$aBobotSk[1]+$aBobotSk[2]+$aBobotSk[3];

//perhitungan nilaimid
//untuk cara penilaian berbasis mp, khusus pengetahuan, n3 tidak dihitung
//untuk cara penilaian berbasis kd, semuanya dihitung
/*
if (($jmid==1) && ($jPenilaian!='perkd'))
	$dinilaiPg="(n1*$aBobotPg[0]+n2*$aBobotPg[1])/($jBobotPg-$aBobotPg[2])";
else
	$dinilaiPg="(n1*$aBobotPg[0]+n2*$aBobotPg[1]+n3*$aBobotPg[2])/$jBobotPg";
$dinilaiKt="(n1*$aBobotKt[0]+n2*$aBobotKt[1]+n3*$aBobotKt[2])/$jBobotKt";
$dinilaiSk="(n1*$aBobotSk[0]+n2*$aBobotSk[1]+n3*$aBobotSk[2]+n4*$aBobotSk[3])/$jBobotSk";
*/
$dinilaiPg=$dinilaiKt=$dinilaiSk="nilai";
//$ketJMP=array("","",$b_keahlian,$p_keahlian,$k_keahlian);
$ketJMP=array("","","","","");

$xsemester=($vsemester==1?"1 (Satu)":($vsemester==2?"2 (Dua)":($vsemester==3?"3 (Tiga)":($vsemester==4?"4 (Empat)":($vsemester==5?"5 (Lima)":($vsemester==6?"6 (Enam)":""))))));

//$sq="select matapelajaran from map_matapelajaran_kelas where kode_kelas='$kode_kelas' and semester='$vsemester'";
//$csq=cariField($sq);
//cari array kodemp pada semester
extractMapMP("kode_kelas='$kode_kelas' and semester='$vsemester'",$xAgama);
//echo $sKDMP;
$aW=array(100,300,80,100);
$tbl_head1='
<div style="font-size:11px">
<table width="100%" border=0 cellspacing="0" cellpadding="0">
  <tr>
    <td width="'.$aW[0].'">Nama Sekolah</td>
    <td width="'.$aW[1].'">: '.$namaSekolahSingkat.'</td>
    <td width="'.$aW[2].'">Kelas</td>
    <td width="'.$aW[3].'">: '.$kelas.'</td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td>: '.$alamatSekolahSingkat.'</td>

    <td>Semester</td>
    <td>: '.$xsemester.'</td>
  </tr>
  <tr>
    <td>Nama</td>
    <td>: '.$nama.'</td>
 
    <td>Tahun Ajaran</td>
    <td>: '.$thpl.'</td>
  </tr>
  <tr>
    <td>Nomor Induk/NISN</td>
    <td>: '.$nis.'</td>
   
    <td></td>
    <td></td>
  </tr>
    
  </table>
  </div>
  ';
 
 
$tt[0]=explode("#","Orang Tua/Wali#$wali_nama##Wali Kelas#<u>$walikelas_nama</u>#NIP: $walikelas_nip") ;
$tt[1]=explode("#","Orang Tua/Wali#$wali_nama##Kepala Sekolah#<u>$kepsek_nama</u>#NIP: $kepsek_nip") ;

for ($x=0;$x<=1;$x++){
$tbl_foot[$x]=' 
		<br>
		<table width="100%" border=0 cellpadding="0" cellspacing="0">
		<tr>
		  <td  width="160" >Mengetahui:</td>
		  <td   >&nbsp;</td>
		  <td  width="160"  valign="top" >'.$webmasterCity.', '.$tglcetak.'</td>
		</tr>
		<tr>
		  <td>'.$tt[$x][0].'</td>
		  <td>&nbsp;</td>
		  <td valign="top">'.$tt[$x][3].'</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td valign="top">&nbsp;</td>
		</tr>
		<tr>
		  <td>'.$tt[$x][1].'</td>
		  <td>&nbsp;</td>
		  <td valign="top">'.$tt[$x][4].'</td>
		</tr>
		<tr>
		  <td>'.$tt[$x][2].'</td>
		  <td>&nbsp;</td>
		  <td valign="top">'.$tt[$x][5].'</td>
		</tr>
	  </table>
	  ';
}

$tbl_foot[2]="
<center>
<div style='width:200px;text-align:left'>
		<br>
		Kepala Sekolah,
		<br><br><br>
	  <br><u>$kepsek_nama</u>
	  <br>NIP. $kepsek_nip</br>
	  </div>
	</center>
	";
	
$judulRaport="
	RAPOR SISWA
	<br>SEKOLAH MENENGAH KEJURUAN
	<BR>(SMK)
	";
	
if ($printpage1) {
//================================hal 1
	//$pdf->AddPage();
	
	$jarak=130;
	$html = '
	<div align="center" >
	<p style="font-size:20px;font-weight:bold;margin-top:110px"> '.$judulRaport.'</p>
	<div style="margin-top:'.$jarak.'px"></div>
	<img src="img/logo_diknas.png" style="width:150px"> <br><br>
		<p style="font-size:80px;margin-top:'.$jarak.'px"> </p> 
	   <p style="font-size:12pt;font-weight:bold;align=center">
		Nama Siswa
		<br>
		'.$nama.'
		<br>
		<br>
		NISN
		<br>
		'.$nisn.'
		</p>
	   <p style="font-size:16pt;font-weight:bold;margin-top:'.$jarak.'px">KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN<BR>REPUBLIK INDONESIA</p>
	   </div>';
	//$pdf->writeHTML($html, true, false, true, false, '');
	
	$shtml.="<div class=page>$html</div>";	
}



if ($printpage2=='x') {
//================================hal 2
	extractRecord("select * from sekolah");
	$aField=array($nama,$nisn,$nss,$alamat,$telepon,$fax,$kelurahan,$kecamatan,$kota,$provinsi,$website,$email);
	$sFieldCaption='Nama Sekolah,NPSN,NIS/NSS/NDS,Alamat Sekolah,Telepon,Fax,Kelurahan,Kecamatan,Kabupaten/Kota,Provinsi,Website,E-mail';
	
	$sField=$nis;
	$aFieldCaption=explode(",",$sFieldCaption);
	$jlhField=count($aFieldCaption);

	//$pdf->AddPage();
	$html= '<p align="center" style="font-size:20px;font-weight:bold;margin-top:30px">
	'.$judulRaport.'</p><br><br><br>';
	
	$aw=array(200,10,400);
	$tbl = '<div style="font-size:17px">
		<table border=0 cellspacing="5">';
	for ($i=0;$i<$jlhField;$i++) {
	$tbl .= '<tr >
			<td width="40" height="30" >&nbsp;</td>
			<td width="'.$aw[0].'" height="30" >'.$aFieldCaption[$i].'</td>
			<td width="'.$aw[1].'" >'.($aFieldCaption[$i]==''?'':':').'</td>
			<td width="'.$aw[2].'" >'.$aField[$i].'</td>
		</tr>';
	}
	$tbl.='</table></div>';
	/*
	
	$pdf->SetFont('helvetica', '', 12);
	$pdf->writeHTML($html, true, false, false, false, '');  
	$pdf->writeHTML($tbl, true, false, false, false, '');
	

//	$shtml.=$html;
//	$shtml.=$tbl;
*/
	$shtml.="<div class='page f14px'>$html $tbl</div>";	
	
}


$printpage3=false; //tidak digunakan
if ($printpage3) {
	//================================hal 3
	
	$html = '
	<p style="font-size:14px;font-weight:bold">PETUNJUK PENGGUNAAN</p><br>
	
	<div style="text-align:justify;line-height:2px">
			<table cellspacing="2" cellpadding="1" border=0>
			<tr>
			<td width="30">1</td>
			<td width="600">Buku Laporan Pencapaian Kompetensi ini digunakan selama Siswa mengikuti pembelajaran di Sekolah Menengah Kejuruan.</td>
			</tr>
			<tr>
			<td>2</td>
			<td>Apabila siswa didik pindah sekolah, buku Laporan Pencapaian Kompetensi dibawa oleh Siswa yang bersangkutan sebagai bukti pencapaian kompetensi.</td>
			</tr>
			<tr>
			<td>3</td>
			<td>Apabila buku Laporan Pencapaian Kompetensi Siswa hilang, dapat digantidengan buku Laporan Pencapaian Kompetensi Pengganti dan diisi dengan nilai-nilai yang dikutip dari Buku Induk Sekolah asal Siswa dan disahkan oleh Kepala Sekolah yang bersangkutan.</td>
			</tr>
			<tr>
			<td>4</td>
			<td>Buku Laporan Pencapaian Kompetensi Siswa ini harus dilengkapi dengan pas foto terbaru ukuran 3 x 4 cm, dan pengisiannya dilakukan oleh wali kelas.</td>
			</tr>
			</table>
			<p style="font-size:14px;font-weight:bold">KETERANGAN NILAI KUANTITATIF DAN KUALITATIF</p>
			<br>
			</div>
			<div style="text-align:justify;">
			Nilai Kuantitatif dengan skala 1 - 4 digunakan untuk Nilai Pengetahuan (Kl 3) dan Nilai Keterampilan (Kl 4). 
			Sedangkan nilai kualitatif digunakan untuk Nilai Sikap Spiritual (Kl 1), Sikap Sosial (Kl 2), dan Kegiatan Ekstra Kurikuler, 
			dengan kualifikasi SB (Sangat Baik), B (Baik), C (Cukup), dan K (Kurang).
			</div> 
			<br>
			';
			
			$w=array(120,180,180,150);
			$sdata="3,85#4,00#A#SB<br>(Sangat Baik)#2;
			3,51#3,84#A-#SB<br>(Sangat Baik)#0;
			3,18#3,50#B+#B<br>(Baik)#3;
			2,85#3,17#B#B<br>(Baik)#0;
			2,51#2,84#B-#B<br>(Baik)#0;
			2,18#2,50#C+#C<br>(Cukup)#3;
			1,85#2,17#C#C<br>(Cukup)#0;
			1,51#1,84#C-#C<br>(Cukup)#0;
			1,18#1,50#D+#K<br>(Kurang)#2;
			1,00#1,17#D#K<br>(Kurang)#0";
			$sdbr=explode(";",$sdata);
			

			
			$spc="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			$html.='<table   border=1 class="tbcetakbergaris"  cellspacing="0" cellpadding="2" >
	  <tr>
		<td rowspan="2" align="center" valign="midle" width="'.$w[0].'">PREDIKAT</td>
		<td colspan="3" align="center"  width="'.($w[1]+$w[2]+$w[3]).'">NILAI KOMPETENSI</td>
	  </tr>
	  <tr align="center">
		<td width="'.($w[1]).'">PENGETAHUAN</td>
		<td width="'.($w[2]).'">KETERAMPILAN</td>
		<td width="'.($w[3]).'">SIKAP</td>
	  </tr>';
	 	
		foreach ($sdbr  as $sd) {
			$ad=explode("#",$sd);
			$html.='
			 <tr align="center">
		<td align="center">'.$ad[2].'</td>
		<td align="center">'.$ad[0].' - '.$ad[1].'</td>
		<td align="center">'.$ad[0].' - '.$ad[1].'</td>';
	  $html.='</tr>
	 ';
			}
	
	$html.='</table>';
	/*
	$pdf->setPrintFooter(false);
	//$pdf->AddPage();
	$pdf->SetFont('helvetica', '', 11);
	$pdf->writeHTML($html, true, false, true, false, '');
	$shtml.=$html;
	*/
	$shtml.="<div class='page f14px'>$html</div>";	

}
if ($printpage3b) {
//================================hal 3b
 	//$pdf->AddPage();
//	$pdf->SetFont('helvetica', '', 11);
	extractRecord("select * from siswa where nis='$nis' ");
	
	$xAgama=$aAgama[$agama];	
	$xGender=$aGender[$gender];
	
	
  $w=array(35,200,20,309);
$html='
<br>
	<h2 align=center>KETERANGAN TENTANG DIRI SISWA</h2>
	<br>
	<br>
	<p style="font-size:8px"> </p>
	<table cellspacing="2" cellpadding="1" border=0 width=100% >
	  <tr>
		<td align="center" width="'.$w[0].'">1</td>
		<td  width="'.$w[1].'">Nama Siswa (lengkap)</td>
		<td  width="'.$w[2].'">:</td>
		<td  >'.$nama.'</td>
	  </tr>
	  <tr>
		<td align="center">2</td>
		<td>Nomor Induk/NISN</td>
		<td>:</td>
		<td>'.$nisn.'</td>
	  </tr>
	  <tr>
		<td align="center">3</td>
		<td>Tempat, Tanggal Lahir</td>
		<td>:</td>
		<td>'.$kota_lahir.','.SQLtotgl($tanggal_lahir).'</td>
	  </tr>
	  <tr>
		<td align="center">4</td>
		<td>Jenis Kelamin</td>
		<td>:</td>
		<td>'.$xGender.'</td>
	  </tr>
	  <tr>
		<td align="center">5</td>
		<td>Agama</td>
		<td>:</td>
		<td>'.$xAgama.'</td>
	  </tr>
	  <tr>
		<td align="center">6</td>
		<td>Status dalam Keluarga</td>
		<td>:</td>
		<td>'.$stat_keluarga.'</td>
	  </tr>
	  <tr>
		<td align="center">7</td>
		<td>Anak ke</td>
		<td>:</td>
		<td>'.$anakke.'</td>
	  </tr>
	  <tr>
		<td align="center">8</td>
		<td>Alamat Siswa</td>
		<td>:</td>
		<td>'.$alamat.'</td>
	  </tr>
	  <tr>
		<td align="center">9</td>
		<td>Nomor Telepon Rumah</td>
		<td>:</td>
		<td>'.$telepon.'</td>
	  </tr>
	  <tr>
		<td align="center">10</td>
		<td>Sekolah Asal</td>
		<td>:</td>
		<td>'.$sekolah_asal.'</td>
	  </tr>
	  <tr>
		<td align="center">11</td>
		<td>Diterima di Sekolah ini</td>
		<td></td>
		<td></td>
	  </tr>
	  <tr>
		<td></td>
		<td>Di Kelas</td>
		<td>:</td>
		<td>'.namaKelas($kelas_terima).'</td>
	  </tr>
	  <tr>
		<td></td>
		<td>Pada Tanggal</td>
		<td>:</td>
		<td>'.SQLtotgl($tanggal_terima).'</td>
	  </tr>
	  <tr>
		<td align="center">12</td>
		<td>Nama Orangtua</td>
		<td></td>
		<td></td>
	  </tr>
	  <tr>
		<td></td>
		<td>a. Ayah</td>
		<td>:</td>
		<td>'.$ayah.'</td>
	  </tr>
	  <tr>
		<td></td>
		<td>b. Ibu</td>
		<td>:</td>
		<td>'.$ibu.'</td>
	  </tr>
	  <tr>
		<td align="center">13</td>
		<td>Alamat Orangtua</td>
		<td>:</td>
		<td>'.$alamat_ortu.'</td>
	  </tr>
	  <tr>
		<td align="center">14</td>
		<td>Nomor Telepon Rumah</td>
		<td>:</td>
		<td>'.$telp_ortu.'</td>
	  </tr>
	  <tr>
		<td align="center">15</td>
		<td>Pekerjaan Orangtua</td>
		<td></td>
		<td></td>
	  </tr>
	  <tr>
		<td></td>
		<td>a. Ayah</td>
		<td>:</td>
		<td>'.$pek_ayah.'</td>
	  </tr>
	  <tr>
		<td></td>
		<td>b. Ibu</td>
		<td>:</td>
		<td>'.$pek_ibu.'</td>
	  </tr>
	  <tr>
		<td align="center">16</td>
		<td>Nama Wali Siswa</td>
		<td>:</td>
		<td>'.$wali.'</td>
	  </tr>
	  <tr>
		<td align="center">17</td>
		<td>Alamat Wali Siswa</td>
		<td>:</td>
		<td>'.$alamat_wali.'</td>
	  </tr>
	  <tr>
		<td align="center">18</td>
		<td>Nomor Telepon Rumah</td>
		<td>:</td>
		<td>'.$telp_wali.'</td>
	  </tr>
	  <tr>
		<td align="center">19</td>
		<td>Pekerjaan Wali Siswa</td>
		<td>:</td>
		<td>'.$pek_wali.'</td>
	  </tr>
	  </table>
	  <br>
	  <br>
	  <br>
	  <br>
	  <table border=0 width=100%>
	  <tr>
		<td  width=70% rowspan=7>';
		$nf="foto/siswa/foto-$nis.jpg";
		if (file_exists($nf)) {
			$html.='
			<img style="width:90px;height:120px;border:2px solid #000;margin-left:50px" src="'.$nf.'">';
		}
		//SQLtotgl($tanggal_terima)
		$html.='</td>
		<td>'.$webmasterCity.", ".$tglcetak.'</td>
	  </tr>
	  <tr>
		<td>Kepala Sekolah,</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td>&nbsp;</td>
	  </tr>
	  <tr>
		<td><u>'.$kepsek_nama.'</u></td>
	  </tr>
	  <tr>
		<td>NIP. '.$kepsek_nip.'</td>
	  </tr>
	</table>
	';
	//$pdf->writeHTML($html, true, false, true, false, '');
	//$shtml.=$html;
	$shtml.="<div class='page f14px'>$html</div>";	

}
if ($printpage4) {
	//======================================hal deskripsi	
	$ctt_des1=$ctt_des2=$ctt_des3=array();
	//$w=array(30,210,50,50,50,50,85,135);
	$w=array(10,200,25,200);
	$tbl_capaian="";
	$tbl_capaian.="<div class='page-landscape'>";

	if ($jmid==0) {
	//	$w=array(30,260,60,60,60,60,105,0);
		//$w=array(30,260,70,70,70,70,70,0);
		$jdl="CAPAIAN HASIL BELAJAR AKHIR SEMESTER";
		$batasbrakhir=20;
	} else {
		$jdl="CAPAIAN HASIL BELAJAR TENGAH SEMESTER";
		$batasbrakhir=21;
	}
	
	$tbl_capaian.='<h2 align=center>'.$jdl.'</h2>';
	
	if ($jmid!=1) {
		$catatan_mapel=cariField("select catatan_mapel from cas where nis='$nis' and semester='$vsemester'");
		
		if ($jPenilaian=='perkd') {
			//if (trim($catatan_mapel)=='') $catatan_mapel=cekCatatanMapel($nis,$vsemester,0);
		}
		$komentar_am=$catatan_mapel;
	}
	
	$sub=0;
	$tbl_capaian.=''.$tbl_head1;
	//SIKAP
	//if ($jmid!=1) {
		$xsikap=carifield("select snilai from nilai_sikap where nis='$nis' and semester='$vsemester'");
		$tbl_capaian.='
		<h3>'.$aAbjad[$sub].'. SIKAP</h3>
			<div style="width:100%;padding:10px 10px 20px 10px ;border:1px solid #000;">
			Deskripsi:
			<br>
			<br>'.$xsikap.'
			</div>
				';
			$sub++;
	//}
	//$tbl_capaian='<p style="font-size:18px;text-align:center;font-weight:bold"><b>CAPAIAN KOMPETENSI'.($jmid==1?' TENGAH SEMESTER':'').'</b></p>';

	
	$tbhead='
	<table  border=1 class="tbcetakbergaris"  cellpadding="5" cellspacing="0" width=100% >
		<tr>
		  <td colspan="2" rowspan="2" align="center"   style="width:'.($w[0]+$w[1]).'px" valign=center >MATA PELAJARAN</td>
		  <td colspan="4"   align="center" style="width:"'.($w[2]*3+$w[3]).'px" >Pengetahuan</td>
		  <td colspan="4"   align="center" width="'.($w[2]*3+$w[3]).'">Keterampilan</td>
		</tr>
		<tr>';
		for ($n=1;$n<=2;$n++) {
			$tbhead.='
			<td align="center" style="width:'.$w[2].'px">KB</td>
		  <td align="center" style="width:'.$w[2].'px">1</td>
		  <td align="center" style="width:'.$w[2].'px">2</td>
		  <td align="center" style="width:'.$w[2].'px">3</td>
		  ';
}
		 $tbhead.=' </tr>
		  ';
	$tbl_capaian.='
	<h3>'.$aAbjad[$sub].'. PENGETAHUAN DAN KETERAMPILAN</h3>'.$tbhead;
	
	$isiCam='';//($jmid!=1?'<td rowspan="#jmp#" valign="top">#camap#</td>':'');//'.$komentar_am.'
		
	$jenisMP="";
	$jml=0;$nojmp=0;$nomp=0;
	$jumlahns=0;
	$jbaris=0;
	
	//menghitung jumlah huruf deskripsi
	$charperbr=50;
	$jdes1=$jdes2=10*$charperbr;
	
	foreach ($aKDMP as $kdmp) {
		$nomp++;
		$tambahanbrkelompok="";
		
		//pindah halaman
		//if ($nomp%4==0) {
		
		$mp=cariField("select nama from matapelajaran where kode='$kdmp'");
		$mpg=$mp;//."<br>Nama guru: ".(str_replace("|",",",$aGuruMP[$nomp-1]));
		$jenis_mp=cariField("select jenis from matapelajaran where kode='$kdmp'");
		
		//kkm
		$kb1=carifield("select kbp"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		$kb2=carifield("select kbk"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		$jknp1=carifield("select jp"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		$jknp2=carifield("select jk"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		
		//Pengetahuan--------------------------------------------------------------------------------------------------------------------------------------
		$aKdAwal=explode("P","K");
		
		$kdAwal="P";
		$synilai="from nilai_kompetensi_siswa inner join kompetensi on  nilai_kompetensi_siswa.kode_kompetensi=kompetensi.kode 
		where nilai_kompetensi_siswa.kode_kompetensi like '$kdmp"."$kdAwal%' and nis='$nis' and kompetensi.semester=$vsemester";
		
		$jnp=cariField("select sum($dinilaiPg) $synilai  ");
		$jknp=max(1,cariField("select count(kode) from kompetensi where kode like '$kdmp"."$kdAwal%' and semester='$vsemester' ")*1);
		$np=$jnp/$jknp;
		//if ($jmid==1) {
			//mencari jumlah kd 
			$jknpIsi=max(1,cariField("select count(distinct(kode_kompetensi)) $synilai"));	// and nilai <>'' 	
			$jknpIsi=$jknp1;
			
			$np=$jnp/$jknpIsi; //jika nilai dihitung dari seluruh mp
		//}
		
		
		//echo "<br>nilai $kdmp: $jnp/$jknpIsi=$np";
		
		$desx=$dx1=$dx2="";$i=0;
		$akk=getArray("select kode_kompetensi $synilai ");
		$ank=getArray("select $dinilaiPg $synilai  ");
		if ($akk) {
			$keymax=mmmr($ank, $output = 'maxmode');
			$keymin=mmmr($ank, $output = 'minmode');
			$ismin=$ismax=false;
			
			$dx1=$dx2="";
			foreach($akk as $kk){
				//hanya yang min atau max saja
			if ( (($ank[$i]==$keymin) and (!$ismin)) or  (($ank[$i]==$keymax) and (!$ismax)) ) {
				//	if (($ank[$i]==$keymin) or ($ank[$i]==$keymax)) {
				 $desn=($desx==""?"":", ");	
				 $kn1=konversiNilai($ank[$i],'predikat');
				 //$ckn1=(strpos(" ".$kn1,"A")>0?"Sangat ":(strpos(" ".$kn1,"B")>0?"":(strpos(" ".$kn1,"C")>0?"Cukup ":"Kurang ")))."Baik pada kompetensi ";
				 $ckn1=(strpos(" ".$kn1,"A")>0?"Sangat Baik " :(strpos(" ".$kn1,"B")>0?"Baik ":"Perlu ditingkatkan "))." ";
				 $desn.="$ank[$i] <b>$ckn1</b> pada kompetensi ";
				 $desn.=cariField("select kd from kompetensi where kode ='$kk' ");
				 if ($ank[$i]==$keymax) $dx1.=$desn;
				 if (($ank[$i]==$keymin) && ($keymin!=$keymax)) $dx2.=$desn;
				 //$desx.=$desn;//semua
				 
				}
				 $i++;
			}
		}
		
		$kn0=konversiNilai($np,'pengetahuan');
		$kn0x=($jcn==1?round($np,0):$kn0);
		$kn1=konversiNilai($kn0x,'predikat');
		
		$des1="$dx1".($dx2==''?'':", $dx2"); //min dan max saja			
		//untuk versi baru des1 diambil dari mata pelajaran
		if ($jinput=='perkd') {
			$fld="des$kdAwal"."$semester";
			$des1="";			
			$des1.="<b>".($kn0x>=92?"Sangat Mampu ":($kn0x>=83?"Mampu ":($kn0x>=75?"Cukup Mampu ":($kn0x>0?"Kurang Mampu ":""))))."</b>";
			$des1.=carifield(" select $fld from matapelajaran where kode='$kdmp'");
			
		}
		
		//k
		$kdAwal="K";
		$synilai="from nilai_kompetensi_siswa inner join kompetensi on  nilai_kompetensi_siswa.kode_kompetensi=kompetensi.kode 
		where nilai_kompetensi_siswa.kode_kompetensi like '$kdmp"."$kdAwal%' and nis='$nis' and kompetensi.semester=$vsemester";
		
		$jnp=cariField("select sum($dinilaiKt) $synilai  ");
	
		$jknp=max(1,cariField("select count(kode) from kompetensi where kode like '$kdmp"."$kdAwal%' and semester='$vsemester' ")*1);
		$np=$jnp/$jknp;
		//nilai dihitung dari yang sudah terisi aja
		//if ($jmid==1) {
			$jknpIsi=max(1,cariField("select count(distinct(kode_kompetensi)) $synilai "));		//and nilai <>'' ;
			$jknpIsi=$jknp2;
			$np=$jnp/$jknpIsi; //jika nilai dihitung dari seluruh mp
		//}
		$desx=$dx1=$dx2="";$i=0;
		$akk=getArray("select kode_kompetensi $synilai ");
		$ank=getArray("select $dinilaiKt $synilai  ");
		if ($akk){			
			$keymax=mmmr($ank, $output = 'maxmode');
			$keymin=mmmr($ank, $output = 'minmode');
			$dx1=$dx2="";
			$ismin=$ismax=false;
			foreach($akk as $kk){
		 		//hanya yang min atau max saja
				if ( (($ank[$i]==$keymin) and (!$ismin)) or  (($ank[$i]==$keymax) and (!$ismax)) ) {
				//if (($ank[$i]==$keymin) or ($ank[$i]==$keymax)) {
				 $desn=($desx==""?"":", ");	
				 $kn3=konversiNilai($ank[$i],'predikat')." ";
				 //$ckn3=(strpos(" ".$kn3,"A")>0?"Sangat ":(strpos(" ".$kn3,"B")>0?"":(strpos(" ".$kn3,"C")>0?"Cukup ":"Kurang ")))."Baik pada kompetensi ";
				 $ckn3=(strpos(" ".$kn1,"A")>0?"Sangat Baik " :(strpos(" ".$kn1,"B")>0?"Baik ":"Perlu ditingkatkan "))."";

				 $desn.="<b>$ckn3</b> pada kompetensi ";
				 $desn.=cariField("select kd from kompetensi where kode ='$kk' ");
				 if ($ank[$i]==$keymax) $dx1.=$desn;
				 if (($ank[$i]==$keymin) && ($keymin!=$keymax)) $dx2.=$desn;
				 //$desx.=$desn;//semua				 
				}
			 $i++;
			}
		}
		
	//	$des2=$desx;
		$des2="$dx1".($dx2==''?'':", $dx2"); //min dan max saja			
		
		
		$kn2=konversiNilai($np,'keterampilan');
		$kn2x=($jcn==1?round($np,0):$kn2);
		$kn3=konversiNilai($kn2x,'predikat');

		
		if ($jinput=='perkd') {
			$fld="des$kdAwal"."$semester";
			$des2="";			
			$des2.="<b>".($kn2x>=92?"Sangat Terampil ":($kn2x>=83?"Terampil ":($kn2x>=75?"Cukup Terampil ":($kn2x>0?"Kurang Terampil ":""))))."</b>";
			$des2.=carifield(" select $fld from matapelajaran where kode='$kdmp'");
		}
		
		
		//s
		$kdAwal="S";
		$synilai="from nilai_kompetensi_siswa inner join kompetensi on  nilai_kompetensi_siswa.kode_kompetensi=kompetensi.kode where nilai_kompetensi_siswa.kode_kompetensi like '$kdmp"."$kdAwal%' and nis='$nis' and kompetensi.semester=$vsemester ";
		
		$jnp=cariField("select sum($dinilaiSk) $synilai  ");

		$sq="select sum($dinilaiSk) $synilai  ";
		//echo $sq;
		$jnp=cariField($sq);
		$jknp=max(1,cariField("select count(kode) from kompetensi where kode like '$kdmp"."$kdAwal%' and semester='$vsemester' ")*1);
		$np=$jnp/$jknp;
		//nilai dihitung dari yang sudah terisi aja
		//if ($jmid==1) {
			$jknpIsi=max(1,cariField("select count(distinct(kode_kompetensi)) $synilai and nilai <>'' "));		
			$np=$jnp/$jknpIsi; //jika nilai dihitung dari seluruh mp
			//echo "nilai: $np: $jnp $jknpIsi select count(distinct(kode_kompetensi)) $synilai <br>";
		//}
		$desx="";$i=0;
		$akk=getArray("select kode_kompetensi $synilai ");
		$ank=getArray("select $dinilaiSk $synilai  ");		
		if ($akk) {
			foreach($akk as $kk){		
				 $desx.=($desx==""?"":", ");	
	 			 $kn4=konversiNilai($ank[$i],'sikap')." ";
				 $ckn4=(strpos(" ".$kn4,"SB")>0?"<b>Sangat Baik</b> dalam ":(strpos(" ".$kn4,"B")>0?"<b>Baik</b> dalam ":(strpos(" ".$kn4,"C")>0?"<b>Cukup</b> ":"<b>Kurang</b> ")));			
				 $desx.=$ckn4;
				 $desx.=cariField("select kd from kompetensi where kode ='$kk' ");
				 $i++;
			}
		}
		$des3=$desx;
		
		$kn4="".konversiNilai($np,'sikap');
		//if ($jmid==1) $kn4=" ";
		$jumlahns+=$np;
		if ($jenis_mp!=$jenisMP) {
			$jenisMP=$jenis_mp;
			$nojmp++;
			//menganggap 1baris 51 huruf
			
			
			//mencari bidang,program dan paket
			$kjmp=$ketJMP[$nojmp-1];
			
			if ($kjmp!="") $kjmp=" : ".$kjmp;
				if ($jenis_mp=='C1 (Dasar Bidang Keahlian)') {
						$jbaris++;
						$jdes1+=($charperbr*1.5);
						$jdes2+=($charperbr*1.5);;
						$tambahanbrkelompok.='<tr><td colspan="10">Kelompok C : '.$kompetensi_keahlian.'</td>'.($nojmp==1?$isiCam:'').'</tr>';	
					}
				$jbaris++;
				$jdes1+=($charperbr*1.5);;
				$jdes2+=($charperbr*1.5);;
				$tambahanbrkelompok.='<tr><td colspan="10">'.$jenis_mp.'</td>'.($nojmp==1?$isiCam:'').'</tr>';
				/*
				if ($jbaris>=$batasbrakhir) {
					$tbl_capaian.="</table>";
					$tbl_capaian=str_replace("#camap#",$komentar_am,$tbl_capaian);	
					$tbl_capaian=str_replace("#jmp#",$jbaris,$tbl_capaian);
					$jbaris=0;
					/*
					$pdf->setPrintHeader(false);
					$pdf->setPrintFooter(false);
					//$pdf->AddPage();
					$pdf->SetFont('helvetica', '', 8);
					$pdf->writeHTML($tbl_capaian, true, false, false, false, '');
					$shtml.=$tbl_capaian;
					* /
					$shtml.="<div class=page-landscape >$tbl_capaian</div>";
					
					$tbl_capaian='<br><br><table  border=1 class="tbcetakbergaris"  cellpadding="5" cellspacing="0">'.$tbhead;
					$tbl_capaian='<br><br><table  border=1 class="tbcetakbergaris"  cellpadding="5" cellspacing="0">'.$tbhead;
				}
				*/
			}

		$jbaris++;
		
	//	if (($nomp==4) ||($nomp==9)||($nomp==14)||($nomp==19)||($nomp==24) ) {
		
		$jdes1+=strlen($des1);
		$jdes2+=strlen($des2);
		
		
		$tbl_capaian.=cekPB();
		if ($jdes1==0) {
			$jdes1+=strlen($des1);
			$jdes2+=strlen($des2);		
		}
		
		$tbl_capaian.=$tambahanbrkelompok;
		$tbl_capaian.='
		
		<tr>
		  <td align="center" width="'.$w[0].'" >'.$nomp.'</td>
		  <td width="'.$w[1].'" >'.$mpg.'</td>
		  <td align="center">'.$kb1.'&nbsp;</td>
		  <td align="center">'.$kn0x.'</td>
		  <td align="center">'.$kn1.'</td>
		  <td align="left" >'.$des1.'&nbsp;</td>
		  <td align="center" >'.$kb2.'&nbsp;</td>
		  <td align="center" >'.$kn2x.'</td>
		  <td align="center" >'.$kn3.'</td>
		  <td align="left" >'.$des2.'&nbsp;</td>
		</tr>';
		//menambah array 
		array_push($ctt_des1,$des1);
		array_push($ctt_des2,$des2);
		array_push($ctt_des3,$des3);
	}
	
	$tbl_capaian.="</table>";
	extractRecord("select * from cas where nis='$nis' and semester='$vsemester'");	
	$tbl_capaian.=cekPB();
	$tbhead="";
	$tbl_capaian.=cekPB(3);
		
	$tbl_capaian.="";
	
	if ($jmid==1) {
		$tbl_capaian.='<br><br>Capaian kompetensi ini adalah hasil capaian Siswa sampai dengan pertengahan semester. Capaian ini tidak menggambarkan hasil dari seluruh kompetensi dasar yang ada pada setiap mata pelajaran, melainkan hanya sebagian kompetensi dasar yang sudah diajarkan.';
		
	} else {
		$sub++;
		$w=array(100,100,20,100);
		$tbl_capaian.='
	
	<h3>'.$aAbjad[$sub].'. PRAKTEK KERJA LAPANGAN</h3>
		<table width="100%" border=1 cellspacing="0" cellpadding="3" class=tbcetakbergaris>
	  <tr>
		<td width="'.$w[0].'" align="center" ><b>Mitra DU/DI</b></td>
		<td width="'.$w[1].'" align="center" ><b>Lokasi</b></td>
		<td width="'.$w[2].'" align="center" ><b>Lama<br>(Bulan)</b></td>
		<td width="'.$w[3].'" align="center" ><b>Keterangan</b></td>
	  </tr>';
	  
	   $adudi=explode("#",$sdudi."#####");
	   for ($i=0;$i<=1;$i++) {
			$apg=explode("|",$adudi[$i]."|||||");
			//$apg[2]=cariField("select deskripsi from ekskul where ekskul='$apg[0]'");		   
		   $tbl_capaian.='<tr>
				<td>'.$apg[0].' &nbsp;</td>
				<td align="center">'.$apg[1].' &nbsp;</td>
				<td align="center">'.$apg[2].' &nbsp;</td>
				<td align="center">'.$apg[3].' &nbsp;</td>
			  </tr>';
	   }
	$tbl_capaian.='</table>';
	$tbl_capaian.=cekPB(7);
		
	$sub++;
	$w=array(70,430);
	
	$tbl_capaian.='
	<h3>'.$aAbjad[$sub].'. EKSTRA KURIKULER</h3>
	<table width="100%" border=1 cellspacing="0" cellpadding="3" class=tbcetakbergaris>
	  <tr>
		<td width="'.$w[0].'" align="center" ><b>Kegiatan Ekstra Kurikuler</b></td>
		<td width="'.$w[1].'"  ><b>Keterangan</b></td>
	  </tr>';
	   $apengembangan=explode("#",$spengembangan."#####");
	   for ($i=0;$i<=1;$i++) {
			$apg=explode("|",$apengembangan[$i]."|||||");
			$apg[2]=cariField("select deskripsi from ekskul where ekskul='$apg[0]'");		   
		   $tbl_capaian.='<tr>
				<td  >'.$apg[0].' &nbsp;</td>
				<td >'.($apg[0]!=''?'Mengikuti kegiatan ekstrakurikuler '.$apg[0].' dengan <b>'.$apg[1]:'&nbsp;').'</b></td>
			  </tr>';
	   }
	$tbl_capaian.='</table>';
	$tbl_capaian.=cekPB(8);
	
	$sub++;
	$w=array(70,430);
	$tbl_capaian.='
	<h3>'.$aAbjad[$sub].'. PRESTASI</h3>
	<table width="100%" border=1 cellspacing="0" cellpadding="3" class=tbcetakbergaris>
	  <tr>
		<td width="'.$w[0].'" align="center" ><b>Jenis Prestasi</b></td>
		<td width="'.$w[1].'"   ><b>Keterangan</b></td>
	  </tr>';
	   $aprestasi=explode("#",$sprestasi."#####");
	   for ($i=0;$i<=2;$i++) {
			$apg=explode("|",$aprestasi[$i]."|||||");
			$apg[2]=cariField("select deskripsi from ekskul where ekskul='$apg[0]'");		   
		   $tbl_capaian.='<tr>
				<td>'.$apg[0].' &nbsp;</td>
				<td  >'.$apg[1].' &nbsp;</td>
			  </tr>';
	   }
		$tbl_capaian.='</table>';
	} //jmid
	
	$tbl_capaian.=cekPB(8);
	$sub++;
	$w=array(200,120);
	$tbl_capaian.='
	<h3>'.$aAbjad[$sub].'. KETIDAKHADIRAN </h3>
	<table width="300" border=1 class="tbcetakbergaris"  cellspacing="0" cellpadding="2">
	  <tr>
		<td width="'.($w[0]+$w[1]).'" colspan="2" align="center" ><b>Ketidakhadiran</b></td>
	  </tr>
	  <tr>
		<td width="'.$w[0].'">Sakit</td>
		<td width="'.$w[1].'" align="center">'.($sakit*1).' Hari</td>
	  </tr>
	  <tr>
		<td>Izin</td>
		<td align="center">'.($izin*1).' Hari</td>
	  </tr>
	  <tr>
		<td>Tanpa Keterangan</td>
		<td align="center">'.($alpha*1).' Hari</td>
	  </tr>
	</table>
	<br>';
	
	$tbl_capaian.=cekPB(7);
	
	if ($jmid==0) {
		$sub++;
	$tbl_capaian.='
	<h3>'.$aAbjad[$sub].'. CATATAN WALI KELAS</h3>
	<table width="100%" border=1 class="tbcetakbergaris"  cellspacing="0" cellpadding="2">
	  <tr>
		<td style="padding:10px"  >'.$catatan.'&nbsp;</td>
	  </tr>
	</table>';
	
	$tbl_capaian.=cekPB(5);
	$sub++;
	$tbl_capaian.='
	<h3>'.$aAbjad[$sub].'. TANGGAPAN ORANGTUA/WALI</h3>
	<table width="100%" border=1 class="tbcetakbergaris"  cellspacing="0" cellpadding="2">
	  <tr>
		<td style="padding:10px" >'.$tanggapanortu.'&nbsp;</td>
	  </tr>
	</table>
	';
	}
	
	if (($vsemester%2==0) &&($jmid==0))  {
		$sub++;
		$tbl_capaian.='
		<h3>'.$aAbjad[$sub].'. KEPUTUSAN</h3>
		<table width="100%" border=0 cellspacing="0" cellpadding="10">
		  <tr>
			<td valign="top"  style="border:1px solid #000;padding:5px;">
				Berdasarkan hasil yang dicapai pada semester '.($vsemester-1).' dan '.$vsemester.', Siswa dinyatakan <b>'.$keputusan.'</b>. 
			</td>
		  </tr>
		</table>
		';
	}
	
	 //if ($jmid!=1) 
	$tbl_capaian.=$tbl_foot[0]."";
	
	if ($jmid==0)  {
		$tbl_capaian.=$tbl_foot[2];
	}
	$tbl_capaian.="</div>";

	
	/*
	//echo $tbl_capaian;
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	//$pdf->AddPage();
	$pdf->SetFont('helvetica', '', 8);
	//echo $tbl_capaian;
	$pdf->writeHTML($tbl_capaian, true, false, false, false, '');
	$shtml.=$tbl_capaian;
	*/
	//$shtml.="<div class=page-landscape >$tbl_capaian</div>";
	$shtml.="$tbl_capaian";

//}

$tbl_deskripsi="";
//if ($printpage5) {
	//======hal5=========================================================================================== 
	if ($jmid!=1) {
		$w=array(30,180,150,300); 

		//$tbl_deskripsi.='<p style="font-size:18px;text-align:center;font-weight:bold"><b>DESKRIPSI KOMPETENSI</b></p>';
		$tbl_deskripsi.=''.$tbl_head1.'
		<h3>DESKRIPSI</h3>
		<table width="100%" border=1 class="tbcetakbergaris"  cellpadding="5" cellspacing="0">
			 
			<tr>
			  <td align="center" width="'.($w[0]+$w[1]).'" colspan=2 >MATA PELAJARAN</td>
			  <td align="center" width="'.$w[2].'" >KOMPETENSI INTI</td>
			  <td align="center" width="'.$w[3].'"  >CATATAN</td>
		  </tr>
			 ';	  
		
		  
		$jenisMP="";
		$jml=0;$nojmp=0;$nomp=0;
		$jumlahns=0;
		foreach ($aKDMP as $kdmp) {
			$nomp++;
			$mp=cariField("select nama from matapelajaran where kode='$kdmp'");
			$mpg=$mp."<br>Nama guru: ".(str_replace("|","/",$aGuruMP[$nomp-1]));
		
			$jenis_mp=cariField("select jenis from matapelajaran where kode='$kdmp'");
			if ($jenis_mp!=$jenisMP) {
				$jenisMP=$jenis_mp;
				$nojmp++;
				
				$kjmp=$ketJMP[$nojmp-1];
				if ($kjmp!="") $kjmp=" : ".$kjmp;
				$tbl_deskripsi.='<tr><td colspan="4">'.$jenis_mp.$kjmp.'</td></tr>';
				}
				// rowspan="3" 
			$tbl_deskripsi.='
		<tr> <td width="'.$w[0].'" rowspan=2>'.$nomp.'</td>
			  <td   width="'.$w[1].'"  rowspan=2>'.$mp.'</td>
			  <td align="left" width="'.$w[2].'">Pengetahuan</td>
			  <td align="left" width="'.$w[3].'"><p align="justify">'.$ctt_des1[$nomp-1].'</p></td>
			 </tr>
			 <tr>
			 <td align="left">Keterampilan</td>
			  <td align="left"><p align="justify">'.$ctt_des2[$nomp-1].'</p></td>
			   </tr>
			   ';
			   /*
			   			 <tr>
			 <td align="left">Sikap Spiritual dan Sosial</td>
			  <td align="left"><p align="justify">'.$ctt_des3[$nomp-1].'</p></td>
			   </tr>
		*/
		}
		
		  
		$tbl_deskripsi.='</table>';
		$tbl_deskripsi.=$tbl_foot[0];
		
	 /*
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
	
		//$pdf->AddPage(); 
		$pdf->SetFont('helvetica', '', 8);
		$pdf->writeHTML($tbl_deskripsi, true, false, false, false, '');
		$shtml.=$tbl_capaian;
		*/
		
	//$shtml.="<div class=page-landscape >$tbl_deskripsi</div>"; 

	}
}
//cetak khs


if ($printpage5b) {
	//======hal khs=========================================================================================== 
	//AMBIL DATA
	
	//$w=array(30,180,150,300);
	$w=array(50,120,400,60,100); 
	$tbl_deskripsi='';
	
	if ($jinput!='perkd') {
		$csp=7;//colspan
		$sjd='	  <td align="center" width="'.$w[0].'" >KODE</td>		  
		  <td align="center" width="'.$w[2].'" >KOMPETENSI DASAR</td>';
	}
	else {
		$csp=6;
	$sjd='<td align="center" width="'.$w[0].'" >KD</td>';
	}
	$tbl_deskripsi.='<p style="font-size:18px;text-align:center;font-weight:bold"><b>NILAI PER KD</b></p><br>'.$tbl_head1.'
	<br><table width="100%" border=1 class="tbcetakbergaris"  cellpadding="5" cellspacing="0">
		<tr>
		  <td align="center" width="'.$w[1].'" >KOMPETENSI<BR>INTI</td>	
		  '.$sjd.'	  
		  <td align="center" width="'.$w[3].'" >NILAI KD</td>
		  <td align="center" width="'.$w[3].'" >NILAI RAPOR</td>
		  <td align="center" width="'.$w[3].'" >KB/KKM</td>
		  <td align="center" width="'.$w[4].'" >KETERANGAN</td>
	  </tr>
		 ';	  
	
	  $jenisMP="";
	$jml=0;$nojmp=0;$nomp=0;
	$jumlahns=0;$mpLama="";
	
	foreach ($aKDMP as $kdmp) {
		$nomp++;
		extractRecord("select nama as mp, jenis as jenis_mp from matapelajaran where kode='$kdmp' order by jenis");
		//==
		if ($jenis_mp!=$jenisMP) {
			$jenisMP=$jenis_mp;
			$nojmp++;
			$kjmp=$ketJMP[$nojmp-1];
			if ($kjmp!="") $kjmp=" : ".$kjmp;
			$tbl_deskripsi.='<tr><td colspan="'.$csp.'" >'.$jenis_mp.$kjmp.'</td></tr>';
		} //jenismp g sama
		
		 
		if ($mp!=$mpLama) {
			$mpLama=$mp;
			$nomp++;
			$tbl_deskripsi.='<tr><td colspan="'.$csp.'" >'.$mp.'</td></tr>';		 
		} //jenismp g sama
		
		 
		//$kdAwal=$aKdAwal[$ii];
		$desx="";$i=0;
		//kompetensi dasar===================================================================
		$skd="select * from kompetensi where semester='$vsemester' and kode_matapelajaran='$kdmp' order by ki desc ";
		$hkd=mysql_query($skd);
		$i=0;
		$rsp=1;$jlhNilai=0;$kdAwalLama="";
		while ($rkd=mysql_fetch_array($hkd)) {		
			$kdAwal=substr($rkd["ki"],0,1);
			$nilai=0;
			$nnn=($kdAwal=="P"?$dinilaiPg:($kdAwal=="K"?$dinilaiKt:$dinilaiSk));
			$sqn="select $nnn from nilai_kompetensi_siswa where nis='$nis' and kode_kompetensi='$rkd[kode]'";
			extractRecord($sqn);$nilai=round($nilai,0);
			
			$skala=konversiNilai($nilai,'pengetahuan'); //berlaku untuk penget, ketram dan sikap
			$predikat=($kdAwal=='S'?konversiNilai($nilai,'sikap'):konversiNilai($nilai,'predikat'));
			
			$ki=($kdAwal=="P"?"Pengetahuan":($kdAwal=="K"?"Keterampilan":"Sikap"));

			//colspan dan nilai raport
			 
			if ($kdAwalLama==$kdAwal) {
				$rsp++;
				
				if ($jinput!='perkd') {
					$sjd="<td align=center >$rkd[kode]</td><td ><p align=justify>$rkd[kd]</p> </td>";
				}
				else
						$sjd="<td align=center >KD$rsp</td>";
					
				$tbl_deskripsi.="
					<tr>
						 ".$sjd."
						 <td align=center >$nilai </td> 
					 </tr>
				";
			} else {
				if ($kdAwalLama=='') $kdAwalLama=$kdAwal;
				//echo "<br>select kb".$kdAwalLama.$vsemester." from matapelajaran where kode='$kdmp'";
				$kkm=carifield("select kb".$kdAwalLama.$vsemester." from matapelajaran where kode='$kdmp'");

				$sqkd="select count(kode) from kompetensi where kode_matapelajaran='$kdmp' and ki='$ki' and semester='$semester'";
				$jrsp=carifield($sqkd);
				
				//echo "<br>$sqkd -> $jrsp ;";

				//$sqn="select avg($nnn) as nraport from nilai_kompetensi_siswa where nis='$nis' and kode_kompetensi like '$kdmp"."$kdAwalLama%' ";
				$sqn="select avg($nnn) as nraport from nilai_kompetensi_siswa where nis='$nis' and kode_kompetensi like '$kdmp"."$kdAwal%' ";
				
				//echo "<br>$sqn";
				$nraport=0;
				extractRecord($sqn);$nraport=round($nraport,0);
				//echo "<br>$sqn -> $nraport ;";

				$kett=($nraport>=$kkm?"Tuntas":"Belum Tuntas");
				
				$xrsp=($jrsp<=1?"":" rowspan='$jrsp' ");
				$rsp=1;
				$kdAwalLama=$kdAwal;
				
				if ($jinput!='perkd') {
					$sjd="<td align=center >$rkd[kode]</td><td ><p align=justify>$rkd[kd]</p> </td>";
				}
				else
						$sjd="<td align=center >KD$rsp</td>";
				$tbl_deskripsi.="
					<tr>
						 <td    align=center $xrsp > $ki </td>
						 $sjd
						 <td align=center >$nilai </td> 
						 
				<td   align=center $xrsp> $nraport</td>
				<td   align=center $xrsp> $kkm</td>
				<td   align=center $xrsp> $kett</td>
					 </tr>
				";
			}
			$i++;		 
		} //akhir while
	} //akhir mp
	
	  
	$tbl_deskripsi.='</table> ';
	$tbl_deskripsi.=$tbl_foot[0];
	/*
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
 	//$pdf->AddPage(); 
	$pdf->SetFont('helvetica', '', 8);
	$pdf->writeHTML($tbl_deskripsi, true, false, false, false, '');
	$shtml.=$tbl_deskripsi; 
	*/
		$shtml.="<div class=page-landscape >$tbl_deskripsi</div>";

}
  

 

//if (strstr($kelas,'XII')!='') echo $shtml;

//=================================================================================================
//Close and output PDF document
//$pdf->Output($nmFilePDF, 'D');

//============================================================+
// END OF FILE
//============================================================+
?>