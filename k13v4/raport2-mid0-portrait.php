<?php
$isTest=false;
//echo "sampai...";exit;
function cekDesPDF($ds){
	global $media2,$isTest;
	if ( $isTest) return "test";
	return $ds;	
}

$potongDes=false;
cekVar("tglcetak,dicetak");
$webmasterCity="Jakarta";
$shtml="";
$strdst="";
$charperbr=50;
$u_agent= ($_SERVER['HTTP_USER_AGENT']);
$thpl4=str_replace("-","/",$thpl4);
 
if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
	$bname = 'Internet Explorer'; 
	$ub = "MSIE"; 
} 
elseif(preg_match('/Firefox/i',$u_agent)) { 
	$bname = 'Mozilla Firefox'; 
	$ub = "Firefox"; 
}
elseif(preg_match('/OPR/i',$u_agent)) { 
	$bname = 'Opera'; 
	$ub = "Opera"; 
} 
elseif(preg_match('/Chrome/i',$u_agent)) { 
	$bname = 'Google Chrome'; 
	$ub = "Chrome"; 
} 
elseif(preg_match('/Safari/i',$u_agent)) { 
	$bname = 'Apple Safari'; 
	$ub = "Safari"; 
} 
elseif(preg_match('/Netscape/i',$u_agent)) { 
	$bname = 'Netscape'; 
	$ub = "Netscape"; 
} 


$shtmlStyle="
<style>
.tout {
	background:#ccc;
	padding:1cm;
	margin:0px;
}
.breadcrumb2 {
	text-align:center;
	background:#000;
	
}
.page,  .page div, .page td, .page p {
    font-family: Times new roman,Helvetica,sans-serif;
    font-size: 10pt;
	background:#fff;
	margin:20px auto;
	
}
.biodata-sekolah,
.biodata-sekolah td {
	font-size: 16pt;
	vertical-align: top;
}
.biodata-siswa,
.biodata-siswa td {
	font-size: 12pt;
	vertical-align: top;
}

.page {
	".
	($ub=="Firefox"?
	"	padding:1.7cm 1.3cm 1cm 1.7cm;"
	:
	"padding:0.5cm;"
	)
	."
	width: 22.5cm;
}
.page h3 {
	margin-top:20px;
}

.imgtt {
	margin:-15px 0px -14px 0px;
	width:200px;
}
@media print {
	@page {
	    size: A4;
		margin:0px;	
	}
	@page-landscape {
	     
			margin:0 0 0 0;
	}
	.page {
		width: 21cm;
		padding:1.7cm 1cm 1cm 0.1cm;
		margin:0px;
	 
	}
}
</style>";


//$tinggiDes=220;
//$maxdes=400;//500;//conf2 : maksimal deskripsi mapel, jika lebih dari 600 bisa menyebabkan teks numpuk saat cetak
if ($orientasi=='landscape'){
	$clspage=" class='page-landscape' ";
	$maxjdes=1300;//($jmid==1?1800:1700);//2000
	$maxjdesSS=1800;//2050;
	$lebartt=160;
	$maxjbr=32;
	
} else {
	$clspage=" class='page' ";
	$maxjdes=1600;//2050;
	$maxjdesSS=2050;//2050;
	$lebartt=200;
	$maxjbr=52;
	$addp=4;
//	$aPindahPG=array(4*1,4*2,4*3,4*4,4*5);
	$aPindahPG=array(20,30);
}
//  font-family: calibri,Arial,Helvetica,sans-serif;
//font-family: Times new roman,Arial,Helvetica,sans-serif;
      

function cekPB($jbr=0,$force=false){
	global $jdes1,$jdes2,$tbhead,$charperbr,$clspage,$maxjdes,$des1,$des2,$maxjdesSS;
	$t="";
	$jdescoba1=$jdes1+$charperbr*$jbr+strlen($des1);
	$jdescoba2=$jdes2+$charperbr*$jbr+strlen($des2);
	
	$sy1=((($jdes1>=$maxjdes)||($jdes2>=$maxjdes))?true:false);
	
	$sy2=((($jdescoba1>$maxjdesSS)||($jdescoba2>$maxjdesSS))?true:false);
	$sy3=((($jdescoba1>$maxjdesSS+200)||($jdescoba2>$maxjdesSS+200))?true:false);
	//$sy2=($des1.$des2==''?true:$sy2);
	
	//if(($jdes1>=$maxjdes)||($jdes2>=$maxjdes))		{
	if(	($sy1 && $sy2&&($tbhead!=''))
	||($sy1 && $sy3&&($tbhead==''))||$force
	)	{
		if ($tbhead!='') $t.="</table>";
		$t.="#pb#".$tbhead;
		//$t.="$jdes1 $jdes2 maxjdes $maxjdes > $jdescoba1 $jdescoba2 > maxjdess $maxjdesSS force $force ";
		$jdes1=$jdescoba1-$jdes1+($tbhead==''?0:$charperbr*3);
		$jdes2=$jdescoba2-$jdes2+($tbhead==''?0:$charperbr*3);
		
	} else {
		$jdes1=$jdescoba1;
		$jdes2=$jdescoba2;
	}
	//else 
	return $t;
	
}
function cekPBKD(){
	global $jdes1,$jbr,$tbhead,$charperbr,$clspage,$maxjbr;
	$t="";

	 
	if($jbr>=$maxjbr)		{
		$t.="</table>";
		$t.="#pb#".$tbhead;
		$jbr=2;
	} 
	 
	return $t;
	
}

$jcn=1; //jenis penilaian 1:murni 2:konversi
$vsemester=$semester;


extractRecord("select showttd from tbconfig1");

if (!isset($tglcetak)) 
	$tglcetak=tglIndo2('','d M Y');
else	
	$tglcetak=tglIndo2(tgltosql($tglcetak),'d M Y');
if ($nocetak==0) {
	$printpage1=$printpage2=$printpage3=$printpage3b=$printpage4=$printpage5=$printpage5b=$printpage6=false;
	if ($jcetak=='Raport'){
		if ($jmid!=1) {//mid semester
			
			if ($dicetak=='Sampul') {
				$printpage1=$printpage2=true;
			}
			else if ($dicetak=='Biodata') {
			$printpage3b=true;
			}
			else if ($dicetak=='Nilai') {
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
	else {//chb
		$printpage5b=true;//khs
	}
}
$aAbjad=("ABCDEFGHIJKLMNOPQRSTUVWXYZ");

extractRecord("select nis,nama,nisn,wali as wali_nama,agama from siswa where nis='$nis'" );
$xAgama=$aAgama[$agama*1];
//echo "Agama $xAgama ";
extractRecord("select kelas.nama as kelas,
guru.nama as walikelas_nama,
kk.nama as kompetensiKeahlian,
guru.nip as walikelas_nip,
guru.fotott as walikelas_tt from kelas 
left join guru on kelas.kode_guru=guru.kode 
left join kompetensi_keahlian kk on kelas.kode_kompetensikeahlian=kk.kode
where kelas.kode='$kode_kelas'");
extractRecord("select kode_kompetensikeahlian from kelas where kode='$kode_kelas'");
//extractRecord("select kode_programkeahlian,nama as k_keahlian from kompetensi_keahlian where kode='$kode_kompetensikeahlian'");
//extractRecord("select kode_bidangkeahlian,nama as p_keahlian from program_keahlian where kode='$kode_programkeahlian'");
//extractRecord("select nama as b_keahlian from bidang_keahlian where kode='$kode_bidangkeahlian'");
 
$xkelas=$kelas;
$kdInklusi=(strstr(strtolower("kelas"),"inklusi")==""?"":"2");

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
$ketJMP=array("","","","","","");

$xsemester=($vsemester==1?"1 (Satu)":($vsemester==2?"2 (Dua)":($vsemester==3?"3 (Tiga)":($vsemester==4?"4 (Empat)":($vsemester==5?"5 (Lima)":($vsemester==6?"6 (Enam)":""))))));

//$sq="select matapelajaran from map_matapelajaran_kelas where kode_kelas='$kode_kelas' and semester='$vsemester'";
//$csq=cariField($sq);
//cari array kodemp pada semester
extractMapMP("kode_kelas='$kode_kelas' and semester='$vsemester'",$xAgama);
//echo $sKDMP;
$aW=array(130,350,100,120);
$tbl_head1='
<div style="font-size:11px">
<table width="100%" border=0 cellspacing="0" cellpadding="0">
  <tr>
    <td width="'.$aW[0].'">Nama Siswa</td>
    <td width="'.$aW[1].'">: '.$nama.'</td>
    <td width="'.$aW[2].'">Kelas</td>
    <td width="'.$aW[3].'">: '.$kelas.'</td>
  </tr>
  <tr>
    <td>NISN/NIS</td>
    <td>: '.$nis.'</td>
    <td>Semester</td>
    <td>: '.$xsemester.'</td>
  </tr>
  <tr>
    <td>Program Keahlian</td>
    <td>: '.$kompetensiKeahlian.'</td>   
    <td></td>
    <td></td>
  </tr>
  </table>
  </div>
  ';
$ttkosong="foto/tt_kosong.png";
$wali_tt='<img src="'.$ttkosong.'"  class="imgtt" width="200" style="margin:-15px 0px -14px 0px;" />';

$nf=$ttkosong;
if (($kepsek_tt!='')&&($showttd==1)) {
	$nf="foto/$kepsek_tt";
	if (!file_exists($nf)) $nf=$ttkosong;
}
$kepsek_tt='<img src="'.$nf.'?a='.$rnd.'"  class="imgtt" width="200" style="margin:-15px 0px -14px 0px;" />';


$nf=$ttkosong;
if (($walikelas_tt!='') &&($showttd==1)){
	$nf="foto/guru/$walikelas_tt";
	if (!file_exists($nf)) 	$nf=$ttkosong;
}
$walikelas_tt='<img src="'.$nf.'?a='.$rnd.'" class="imgtt" width="200" style="margin:-15px 0px -14px 0px;" />';

 //0:mid,1:akhir
//$tt[0]=explode("#","Orang Tua/Wali#$wali_nama##Wali Kelas#$walikelas_nama#".($walikelas_nip==""?" ":"NIP: $walikelas_nip")."#$walikelas_tt") ;
$tt[0]=explode("#","###Wali Kelas#$walikelas_nama#".($walikelas_nip==""?" ":"NIP: $walikelas_nip")."#$walikelas_tt") ;
$tt[1]=explode("#","Orang Tua/Wali#$wali_nama##Kepala Sekolah#$kepsek_nama#NIP: $kepsek_nip#$kepsek_tt") ;

for ($x=0;$x<=1;$x++){
	$tbl_foot[$x]=' 
		<br />
		<br />
		<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
		  <td  width="25%" valign="top" align="center">
		  <br />'.$tt[$x][0].'	
		 <br />'.$wali_tt.'	
		    <br />'.$tt[$x][1].'
		  '.$tt[$x][2].'
		  </td>
		  <td  width="50%">&nbsp;</td>
		  <td  width="25%"  valign="top"  align="center" >
		  '.$webmasterCity.', '.$tglcetak.'
		  <br>'.$tt[$x][3].'
		  <br>'.$tt[$x][6].'
		<br>'.$tt[$x][4].'
		  <br>'.$tt[$x][5].'
		  </td>
		</tr>

		 
	  </table>
	  ';
}

$tbl_foot[2]='
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
		<td align="center">
		Kepala Sekolah,'
		.'<br />'.$kepsek_tt
		."<br /><u>".($kepsek_nama).'</u>
		<br />NIP '.$kepsek_nip.'
	  </td>
	  </tr>
	</table>';
	
$judulRaport="
	RAPOR SISWA
	<br>SEKOLAH MENENGAH KEJURUAN
	<BR>(SMK)
	";
	
 
if ($printpage4) {
	//======================================hal deskripsi	
	$ctt_des1=$ctt_des2=$ctt_des3=array();	
	$w=array(20,150,25,200);
	$tbl_capaian="";
	//$tbl_capaian.="<div ".$clspage." >";
	$tbl_capaian.="#pb#";

	$jdl="
	SEKOLAH MENENGAH KEJURUAN (SMK) NEGERI 57 JAKARTA<BR>
	LAPORAN HASIL BELAJAR TENGAH SEMESTER";
	$batasbrakhir=21;
	
	$tbl_capaian.='<h3 align="center" style="font-size:20px">'.$jdl.'</h3><br>';
	if ($jmid!=1) {
		$catatan_mapel=cekDesPDF(cariField("select catatan_mapel from cas where nis='$nis' and semester='$vsemester'"));
		$komentar_am=$catatan_mapel;
	}
	
	$sub=0;
	$tbl_capaian.=''.$tbl_head1;
	//SIKAP
	$xsikap=cekDesPDF(carifield("select snilai from nilai_sikap where nis='$nis' and semester='$vsemester'"));
	$tbl_capaian.='<div style="width:100%;padding:10px 10px 20px 10px ;border:1px solid #000;">';
	if ($media2=='pdf') $tbl_capaian.='<br>&nbsp;&nbsp;&nbsp;';
	$tbl_capaian.='<b>Deskripsi Sikap </b>: '.$xsikap;
	if ($media2=='pdf') $tbl_capaian.='<br>';
	$tbl_capaian.='</div>';
	if ($media2=='pdf') $tbl_capaian.='<br>';
	$sub++;
	
	$wt=812;
	if ($media2=='pdf') {
		$wt=640;
	}
	elseif ($media2=='doc') {
		$wt=640;
	}
	$w=hitungSkala(array(6,35,5,5,5,5,5,5,5,5,5),$wt,0);
	$wk1=$w[2]*4;
	$wk2=$w[2]*4;
	
	$tbhead='
	<table  border="1" class="tbcetakbergaris"  cellpadding="5" cellspacing="0" width="'.$wt.'" >
		<tr>
		  <td rowspan="2" colspan="2" align="center" width="'.($w[0]+$w[1]).'" >MATA PELAJARAN</td>
		  <td rowspan="2" colspan="1" align="center" width="'.$w[2].'" >KKB</td>
		  <td rowspan="1" colspan="4" align="center" width="'.$wk1.'" >Pengetahuan</td>
		  <td rowspan="1" colspan="5" align="center" width="'.$wk2.'" >Keterampilan</td>
		</tr>
		
		<tr>
<td align="center" width="'.$w[3].'">1</td>
<td align="center" width="'.$w[4].'">2</td>
<td align="center" width="'.$w[5].'">3</td>
<td align="center" width="'.$w[6].'">4</td>
<td align="center" width="'.$w[3].'">1</td>
<td align="center" width="'.$w[4].'">2</td>
<td align="center" width="'.$w[5].'">3</td>
<td align="center" width="'.$w[6].'">4</td>
		</tr>';
	$tbl_capaian.= $tbhead;
	
	$jenisMP="";
	$jml=0;$nojmp=0;$nomp=0;
	$jumlahns=0;
	$jbaris=0;
	
	//menghitung jumlah huruf deskripsi
	$jdes1=$jdes2=10*$charperbr;
	foreach ($aKDMP as $kdmp) {
		$nomp++;
		$tambahanbrkelompok="";
		$mp=cariField("select nama from matapelajaran where kode='$kdmp'");
		$mpg=$mp;//."<br>Nama guru: ".(str_replace("|",",",$aGuruMP[$nomp-1]));
		$jenis_mp=cariField("select jenis from matapelajaran where kode='$kdmp'");
		$kb1=carifield("select kbp"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		$kb2=carifield("select kbk"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		$jknp1=carifield("select jp"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		$jknp2=carifield("select jk"."$vsemester from matapelajaran where kode='$kdmp' ")*1;
		
		if ($jknp1==0) {
			echo um412_falr("Mata pelajaran $mpg (pengetahuan) jumlah kd belum diisi....");
			exit;
		}
		if ($jknp2==0) {
			echo um412_falr("Mata pelajaran $mpg (keterampilan) jumlah kd belum diisi....");
			exit;
		}
		
		//Pengetahuan--------------------------------------------------------------------------------------------------------------------------------------
		$kdAwal="P";
		$synilai="from nilai_kompetensi_siswa inner join kompetensi on  nilai_kompetensi_siswa.kode_kompetensi=kompetensi.kode 
		where nilai_kompetensi_siswa.kode_kompetensi like '$kdmp"."$kdAwal"."$vsemester%' and nis='$nis' and kompetensi.semester=$vsemester";
		$sqnilai="select sum($dinilaiPg) $synilai  ";
		$jnp=cariField($sqnilai);
		$sqjkom="select count(kode) from kompetensi where kode like '$kdmp"."$kdAwal"."$vsemester%' and semester='$vsemester' ";
		$jknp=max(1,cariField($sqjkom)*1);
		$np=$jnp/$jknp;
		$sqd="select count(distinct(kode_kompetensi)) $synilai";
		$jknpIsi=max(1,cariField($sqd));	// and nilai <>'' 	
		$jknpIsi=$jknp1;
		
		$np=$jnp/$jknpIsi; //jika nilai dihitung dari seluruh mp
		if ($np>100) {
			//echo "<br>$sqnilai -> $jnp <br>"."select jp"."$vsemester from matapelajaran where kode='$kdmp' "." ->$jknp1";
			$squpd="update nilai_kompetensi_siswa set `kode_kompetensi`=concat('x',`kode_kompetensi`) where `kode_kompetensi`>'$kdmp"."$kdAwal"."$semester"."000$jknp1'   and `kode_kompetensi` like '$kdmp"."$kdAwal"."$vsemester"."000%'";
			//echo "<br>".$squpd;
			mysql_query($squpd);
			echo "silahkan tekan tombol F5 untuk refresh halaman";
			exit;
		}
		$desx=$dx1=$dx2="";$i=0;
		$akk=getArray("select kode_kompetensi $synilai ");
		$ank=getArray("select $dinilaiPg $synilai  ");
		if ($akk) {
			$keymax=mmmr($ank, $output = 'maxmode');
			$keymin=mmmr($ank, $output = 'minmode');
			$ismin=$ismax=false;
			$dx1=$dx2="";
			foreach($akk as $kk){
			if ( (($ank[$i]==$keymin) and (!$ismin)) or  (($ank[$i]==$keymax) and (!$ismax)) ) {
				 $desn=($desx==""?"":", ");	
				 
				 $kn1=konversiNilai($ank[$i],'predikat');
				 $ckn1=(strpos(" ".$kn1,"A")>0?"Sangat Baik " :(strpos(" ".$kn1,"B")>0?"Baik ":"Perlu ditingkatkan "))." ";
				 $desn.="$ank[$i] <b>$ckn1</b> pada kompetensi ";
				 $desn.=cekDesPDF(cariField("select kd from kompetensi where kode ='$kk' "));
				 if ($ank[$i]==$keymax) $dx1.=$desn;
				 if (($ank[$i]==$keymin) && ($keymin!=$keymax)) $dx2.=$desn;				 
				}
				 $i++;
			}
		}
		//pengetahuan
		$kn0=konversiNilai($np,'pengetahuan');
		$kn0x=($jcn==1?round($np,0):$kn0);
		$kn1=konversiNilai($kn0x,'predikat');
		$des1="$dx1".($dx2==''?'':", $dx2"); //min dan max saja			
		//untuk versi baru des1 diambil dari mata pelajaran
		if ($jinput=='perkd') {
			$fld="des$kdAwal$kdInklusi"."$semester";
			$des1="";			
			$des1.="<b>".($kn0x>=92?"Sangat Mampu ":($kn0x>=83?"Mampu ":($kn0x>=75?"Cukup Mampu ":($kn0x>0?"Kurang Mampu ":""))))."</b>";
			$des1.=cekDesPDF(carifield(" select $fld from matapelajaran where kode='$kdmp'"));
			if ($potongDes) $des1=potong($des1,$maxdes,true,$strdst);//maks 500 huruf
		}
		
		//k
		$kdAwal="K";
		$synilai="from nilai_kompetensi_siswa inner join kompetensi on  nilai_kompetensi_siswa.kode_kompetensi=kompetensi.kode 
		where nilai_kompetensi_siswa.kode_kompetensi like '$kdmp"."$kdAwal%' and nis='$nis' and kompetensi.semester=$vsemester";
		$jnp=cariField("select sum($dinilaiKt) $synilai  ");	
		$jknp=max(1,cariField("select count(kode) from kompetensi where kode like '$kdmp"."$kdAwal%' and semester='$vsemester' ")*1);
		$np=$jnp/$jknp;
		$jknpIsi=max(1,cariField("select count(distinct(kode_kompetensi)) $synilai "));		//and nilai <>'' ;
		$jknpIsi=$jknp2;
		$np=$jnp/$jknpIsi; //jika nilai dihitung dari seluruh mp
		if ($np>100) {
			//echo "<br>$sqnilai -> $jnp <br>"." select jk"."$vsemester from matapelajaran where kode='$kdmp' "." ->$jknp1";
			//HAPUS NILAI YANG DILUAR JUMLAH KD
			$squpd="update nilai_kompetensi_siswa set `kode_kompetensi`=concat('x',`kode_kompetensi`) where `kode_kompetensi`>'$kdmp"."$kdAwal"."$semester"."000$jknp2'   and `kode_kompetensi` like '$kdmp"."$kdAwal"."$vsemester"."000%'";
			//echo "<br>".$squpd;
			mysql_query($squpd);
			echo "silahkan tekan tombol F5 untuk refresh halaman";
			exit;
		}
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
				 $desn=($desx==""?"":", ");	
				 $kn3=konversiNilai($ank[$i],'predikat')." ";
				 //$ckn3=(strpos(" ".$kn3,"A")>0?"Sangat ":(strpos(" ".$kn3,"B")>0?"":(strpos(" ".$kn3,"C")>0?"Cukup ":"Kurang ")))."Baik pada kompetensi ";
				 $ckn3=(strpos(" ".$kn1,"A")>0?"Sangat Baik " :(strpos(" ".$kn1,"B")>0?"Baik ":"Perlu ditingkatkan "))."";

				 $desn.="<b>$ckn3</b> pada kompetensi ";
				 $desn.=cekDesPDF(cariField("select kd from kompetensi where kode ='$kk' "));
				 if ($ank[$i]==$keymax) $dx1.=$desn;
				 if (($ank[$i]==$keymin) && ($keymin!=$keymax)) $dx2.=$desn;
				}
			 $i++;
			}
		}
		$des2="$dx1".($dx2==''?'':", $dx2"); //min dan max saja			
		$kn2=konversiNilai($np,'keterampilan');
		$kn2x=($jcn==1?round($np,0):$kn2);
		$kn3=konversiNilai($kn2x,'predikat');

		if ($jinput=='perkd') {
			$fld="des$kdAwal$kdInklusi"."$semester";
			$des2="";			
			$des2.="<b>".($kn2x>=92?"Sangat Terampil ":($kn2x>=83?"Terampil ":($kn2x>=75?"Cukup Terampil ":($kn2x>0?"Kurang Terampil ":""))))."</b>";
			$des2.=cekDesPDF(carifield(" select $fld from matapelajaran where kode='$kdmp'"));
			if ($potongDes) $des2=potong($des2,$maxdes,true,$strdst); 
			
		}
		
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
					$xkk=$kompetensi_keahlian;
					$xkk=str_replace("Mulok","Muatan Lokal",$xkk);
					//$tambahanbrkelompok.='<tr><td colspan="10">C1 : '.$xkk.'</td>'.($nojmp==1?$isiCam:'').'</tr>';	
				}
				$jbaris++;
				$jdes1+=($charperbr*1.5);;
				$jdes2+=($charperbr*1.5);
				$xjmp=getXJenisMP($jenis_mp);
				/*
				$xjmp=$jenis_mp;
				$xjmp=str_replace("Mulok","Muatan Lokal",$xjmp);
				*/
				$tambahanbrkelompok.='<tr><td colspan="10">'.$xjmp.'</td></tr>';
			}

		$jbaris++;
		if (in_array($nomp,$aPindahPG )  ) {
			$tbl_capaian.=cekPB(0,true);
			//$tbl_capaian.=cekPB(0);
		}
		
		$addkn[0]='<td align="center">'.$kb1.'&nbsp;</td>';
		$addkn[1]='';//<td align="center">'.$kb2.'&nbsp;</td>';
		$apk="PK";
		$akb=array($kb1,$kb2);
		
		extractRecord("select jp$semester"."a as maxj1,jk$semester"."a as maxj2 from matapelajaran where kode='$kdmp'");
		$aMaxJ=array($maxj1,$maxj2);
		for ($j=0;$j<=3;$j++){			
			for ($k=0;$k<=1;$k++) {
				$n1=0;
				$kn1="";
				$kn2="";
				$kk=$kdmp.$apk[$k].$semester.substr("000".($j+1),-4);
				//yang ditampilkan hanya sampai tengah semester
				if ($j<$aMaxJ[$k]) {
					$n1=round(carifield("select nilai  from nilai_kompetensi_siswa 
					where nis='$nis' and kode_kompetensi='$kk'  ")*1,0);
				}
				$addcls="";
				if ($n1==0) {
					$n1="&nbsp;";
					$addcls='style="background:#ccc"';
				} 
				$addkn[$k].='<td align="center" '.$addcls.'>'.$n1.'</td>';
			}		  
		}
		$des1xx=strip_tags($des1);
		$des2xx=strip_tags($des2);
		$tbl_capaian.=$tambahanbrkelompok;//height="200" style="height:200px"
		$tbl_capaian.='
		<tr  style="overflow:hide"  >
		  <td align="center" width="'.$w[0].'" >'.$nomp.'</td>
		  <td width="'.$w[1].'" >'.$mpg.'</td>'.$addkn[0].$addkn[1].'
		</tr>';
		//menambah array 
		array_push($ctt_des1,cekDesPDF($des1));
		array_push($ctt_des2,cekDesPDF($des2));
	}
	
	$tbl_capaian.="</table>";
	extractRecord("select * from cas where nis='$nis' and semester='$vsemester'");	
	$tbhead="";
	$des1=$des2="";
	$tbl_capaian.='
	<br><div style="padding-top:10px">
	<br>Capaian kompetensi ini adalah hasil capaian Siswa sampai dengan pertengahan semester. Capaian ini tidak menggambarkan hasil dari seluruh kompetensi dasar yang ada pada setiap mata pelajaran, melainkan hanya sebagian kompetensi dasar yang sudah diajarkan.
	<br></div>
	<br>';
	//$tbl_capaian.=cekPB(0,true);
		
	$sub++;
	$w=array(200,120);
	$tsub='
	<table width="300" border="1" class="tbcetakbergaris"  cellspacing="0" cellpadding="2">
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
	';	
	$tbl_capaian.=cekPB(4);
	
	$tbl_capaian.=$tsub;
	$tbl_capaian.=$tbl_foot[0]."";
	$tbl_capaian.="</div>";
	$shtml.="$tbl_capaian";

}


?>