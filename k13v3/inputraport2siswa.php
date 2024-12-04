<?php
$useJS=2;
include_once "conf.php";
cekvar("cetak,jcetak,semester");
$jcetak2='siswa';
if (isset($_SESSION['nis'])) {
	extractRecord("select nis,nama,kode_kelas  from siswa where nis='".$_SESSION['nis']."'");
	}
else exit;
		
$kelas=cariField("select nama from kelas where kode='$kode_kelas'");

if ($cetak=='pdf') {
	extractRecord("select nama as b_keahlian from bidang_keahlian where kode='$kode_bidangkeahlian'");
	$xkelas=$kelas;
	$ketJMP=array("","",$b_keahlian,$p_keahlian,$k_keahlian);	
	$xsemester=($semester==1?"1 (Satu)":($semester==2?"2 (Dua)":($semester==3?"3 (Tiga)":($semester==4?"4 (Empat)":($semester==5?"5 (Lima)":($semester==6?"6 (Enam)":""))))));
	
	 
	$nmFilePDF=strtoupper($jcetak).'_SEM.'.$semester.'_'.str_replace(" ","",$nis).'_'.str_replace(" ","_",$nama).'.pdf';
	$nocetak=0;	 
	//echo "he";
	//include "raport2-mid.php";
	$jcetak="Raport";
	$_REQUEST['pcetak'][3]='on';
	if ($jc==1)
		include "raport2-mid-siswa.php";
	else
		include "raport2-mid-siswakd.php";
	$pdf->Output($nmFilePDF, 'D');
	 exit;
}
 


$idForm="fraport_".rand(1231,2317);
$nfAction="inputraport2siswa.php?cetak=pdf&jcetak2=siswa";

$asf="";
//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\" ";
$t="";
$t.="<div id=ts"."$idForm ></div>";
$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput target=_blank >";
$t.="<input type=hidden name=jcetak2 value='$jcetak2'>";

echo $t;	

?> 

 
<table width='450' >
<tr  $sty >
	<td colspan=3 class=tdjudul style='font-size:16px'>
   CETAK LEMBAR HASIL BELAJAR 
</td>
</tr>
<tr  $sty >
	<td colspan=3 >&nbsp;</td>
</tr>
<?php
if (!isset($_SESSION['nis'])){
	?>
    <tr class=troddform2 $sty >
        <td class=tdcaption >Kelas </td>
        <td>: <span id=tkelas><?=um412_isiCombo5('select * from kelas','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboRaport('kelas')");?></span></td> 
    </tr>
    <tr class=troddform2 $sty <?=($jcetak2=='kelas'?"style='display:none'":'') ?> >
            <td class=tdcaption >Siswa</td>
            <td>: <span id=tsiswa>-</span></td> 
    </tr>
    <tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td>: <span id=tsemester>-</span></td> 
</tr>
	<?php 
} else {
	?>
	    <tr class=troddform2 $sty >
            <td class=tdcaption >Nis</td>
            <td>: <span id=tsiswa><?=$nis?></span></td> 
    </tr>
	
	    <tr class=troddform2 $sty >
            <td class=tdcaption >Nama</td>
            <td>: <span id=tsiswa><?=$nama?></span></td> 
    </tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td>: <span id=tsemester><?
    $kelas=cariField("select nama from kelas where kode='$kode_kelas'");
	$sm=(strpos("-".$kelas,"XII")>0?"5,6":(strpos("-".$kelas,"XI")>0?"3,4":"1,2"));
	echo um412_isiCombo5("$sm",'semester','','','-Pilih-',$semester,"gantiComboNilai('semester')"); 
	?></span></td> 
</tr>
	<?php
	
}//siswa


?>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tahun Pelajaran</td>
	<td>: <?php
    echo "$thpl";
	?></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tanggal <?=$jcetak?></td>
	<td>: <input type=text name=tglcetak id=tglcetak onmousemove=bukaTgl(this.name)></td> 
</tr>
<?php if ($jPenilaian=='perkd') { ?>
<tr class=troddform2 $sty >
	<td class=tdcaption >Pilihan Cetak  </td>
	<td>: 
    <input type=radio name=jc value=1/>Permatapelajaran 
    <input type=radio name=jc value=2 checked="checked" />PerKompetensi Dasar
    </td> 
</tr>
<?php } else  echo " <input type=hidden name=jc value=1  />";
?>
 <tr class=troddform2 $sty >
	<td class=tdcaption ></td>
	<td>
    <?php    
	echo "<input type=submit value='Cetak' class='btn btn-success btn-sm'>";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=button value='Logout' onclick=location.href='index2.php?det=login&op=logout' >";
	?>
    </td> 
   </tr>
</table>
</form>
<br>
<div id=treport></div>

