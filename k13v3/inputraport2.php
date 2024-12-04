<?php
//$useJS=2;
include_once "conf.php";
cekVar("kode_kelas,cetak,asf,jmid,nis,jcetak2,jcetak,media,media2,semester,orientasi");
//echo $o="orientasi : $orientasi ... >";
if ($jcetak2=='') $jcetak2='siswa';
if (isset($_SESSION['nis'])) { 
	extractRecord("select nama,kode_kelas  from siswa where nis='".$_SESSION['nis']."'");
} 
	
extractRecord("select kelas.nama as kelas,kompetensi_keahlian.nama as kompetensi_keahlian from kelas left join kompetensi_keahlian on kelas.kode_kompetensikeahlian=kompetensi_keahlian.kode where kelas.kode='$kode_kelas'");

if ($kode_kelas!='') {
	$pes="";
	$det=$op="";
	if ($kode_kelas=='') $pes.="* Kelas harus dipilih";
	if ($semester=='') $pes.="* Semester harus dipilih";
	if ($nis=='') $pes.="* Siswa harus dipilih";
	
	if ($pes!='') {
		$pes=str_replace("*","<br> ",$pes);
		echo $pes;
		exit;
	}
	
	

	/*
	extractRecord("select nama as b_keahlian from bidang_keahlian where kode='$kode_bidangkeahlian'");
	$ketJMP=array("","",$b_keahlian,$p_keahlian,$k_keahlian);	
	*/
	extractRecord("select nis as siswa_nis,nama as siswa_nama,kode_kelas as siswa_kodekelas from siswa where nis='$nis'");	
    extractRecord("select nama as siswa_kelas,tingkat as siswa_tingkat from kelas where kode='$siswa_kodekelas'");	
	$nama=$siswa_nama;
   
	$ketJMP=array("","","","","","","");
	$xkelas=$kelas;
	$xsemester=($semester==1?"1 (Satu)":($semester==2?"2 (Dua)":($semester==3?"3 (Tiga)":($semester==4?"4 (Empat)":($semester==5?"5 (Lima)":($semester==6?"6 (Enam)":""))))));
	
	$jnm=($jmid==1?"MID":"SEM");
	
	$nfRaport="raport2-mid-portrait.php";
	if ($jmid==1) $nfRaport="raport2-mid0-portrait.php";
	
	$nmFilePDF=strtoupper($jcetak).'_'.$jnm.$semester.'_'.str_replace(" ","",$kelas)."_".str_replace(" ","",$nama).'';	
	if ($jcetak2=='siswa') { //pencetakan 1 siswa	
		
		$nocetak=0;	 
		include $nfRaport;
		//menghilangkan header footer
		if (substr($shtml,0,4)=="#pb#") $shtml=substr($shtml,4,strlen($shtml)-4);
		if ($media2=='pdf') {
			$html=$shtmlStyle.trim($shtml);
			//echo "<textarea style='width:90%;height:500px'>$html</textarea>";
			$orientation="P";
			$papersize="F4";

			include $um_path."head-pdf.php";
		}
		else {
			$shtml=str_replace("#pb#","</div><div class='page f14px'>","<div class='page f14px'>$shtml</div>");
			if ($media2=='doc') {
				header("Content-Type: application/vnd.ms-word");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("content-disposition: attachment;filename=$nmFilePDF.doc");
				//ganti gambar ke dir local
				$dir= str_replace('\\', '/', getcwd()."/");
				$shtml=str_replace('img src="','img src="'.$dir,$shtml);
				echo $shtml;
				//exit;
			} else { 
				$thead='';
				$showResultHead=false;
				include $um_path."cetak-head.php";
				$t=$thead.$shtmlStyle.$shtml."</div>";
				//echo "ini.....<textarea style='width:90%;height:500px'>$t</textarea>";
				echo $t;
			}
		}
		
	 } else {
		 
		 //perkelas
		$nocetak=0;	 
		$aNis=getArray("select nis from siswa where kode_kelas='$kode_kelas' ");
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		foreach($aNis as $nis ){

			include $nfRaport;
			$nocetak++;
		}
		//$pdf->Output($nmFilePDF, 'D');
		//menghilangkan header footer
		echo $shtml;
		//ini_set('max_execution_time', 3000);
	}
	 
	 exit;
}
 


$idForm="fraport_$rnd";
//$nfAction="inputraport2.php?media=print&jcetak=$jcetak";
$nfAction="inputraport2.php?media=&jcetak=$jcetak";

//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','','raport');return false;\" ";
//$asf="onsubmit=\"cekValidasiForm('raport','$idForm',$rnd);return false;\" ";
$addf='';
$funcAfterEdit="";
$t="";
$t.="<div id=ts"."$idForm ></div>";
$t.="<div name='tfuncAfterEdit' id='tfae"."$rnd' style='display:none' >$funcAfterEdit</div>";
$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput target=_blank>";
$t.="
<input type=hidden name=jcetak2 value='$jcetak2'>
<input type=hidden name=useJS id=useJS value='2'>
<input type=hidden name=contentOnly id=contentOnly value='2'>

";

echo $t;	

$judul=$jcetak;
if ($jcetak=='Raport') {
	$judul=($jmid==1?"Raport Tengah Semester":"Raport Akhir Semester");
	}
elseif ($jcetak=='chb') {
	$judul="Nilai Per KD";
	}
?> 

 
<table >
<tr class=troddform2 $sty >
	<td colspan=2><div class=titlepage>Cetak <?=$judul?></div>
	</td>
</tr>
<?php
if ($userType!='siswa') {
	?>
    <tr class=troddform2 $sty >
        <td class=tdcaption width=140 >Kelas </td>
        <td><span id=tkelas><?=um412_isiCombo5('select * from kelas order by tingkat,nama','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboRaport('kelas',$rnd)");?></span></td> 
    </tr>
    <tr class=troddform2 $sty <?=($jcetak2=='kelas'?"style='display:none'":'') ?> >
            <td class=tdcaption >Siswa</td>
            <td><span id=tsiswa_<?=$rnd?> >-</span></td> 
    </tr>
    <tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><span id=tsemester_<?=$rnd?> >-</span></td> 
</tr>
	<?php 
} else {
	extractRecord("select nama,kode_kelas  from siswa where nis='$nis'");
	echo "
	            <input type=hidden name='kode_kelas' id='kode_kelas_$rnd' value='$kode_kelas'>
            <input type=hidden name='nis' id='nis_$rnd' value='$nis' >
";
?>
	    <tr class=troddform2 $sty >
            <td class=tdcaption  width=140 >Nis</td>
            <td><span id=tnis_<?=$rnd?> > <?=$nis?>
            
            </span></td> 
    </tr>
	
	    <tr class=troddform2 $sty >
            <td class=tdcaption >Nama</td>
            <td><span id=tsiswa_<?=$rnd?> ><?=$nama?></span></td> 
    </tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><span id=tsemester_<?=$rnd?> ><?
    $kelas=cariField("select nama from kelas where kode='$kode_kelas'");
	$sm=(strpos("-".$kelas,"XII")>0?"5,6":(strpos("-".$kelas,"XI")>0?"3,4":"1,2"));
	echo um412_isiCombo5("$sm",'semester','','','-Pilih-',$semester,"gantiComboNilai('semester','',$rnd)"); 
	

	
	?></span></td> 
</tr>
	<?php
	
}//siswa


?>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tahun Pelajaran</td>
	<td><input type=hidden name=tahun id=tahun_<?=$rnd?> value='<?=$thpl?>'><?=$thpl?></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tanggal <?=$jcetak?></td>
	<td><input type=text name=tglcetak id=tglcetak_<?=$rnd?> onmousemove="$('#tglcetak_<?=$rnd?>').datepicker();" ></td> 
</tr>

<?php 

if (($jcetak=='Raport') &&($jmid!=1)) {
	?>
<tr class=troddform2 $sty >
	<td class=tdcaption >Pilihan Cetak  </td>
	<td>
    <?php
	 echo um412_isicombo5("R:Sampul,Biodata,Nilai","dicetak");
	 ?>
    </td> 
</tr>

<?php
}
//else echo "<input type=hidden name=tglcetak id=tglcetak_$rnd> ";
?>
<!--tr clatss=troddform2 $sty >
	<td class=tdcaption >Orienntasi Pecetakan  </td>
	<td>
    <?php
	 echo um412_isicombo5("R:Landscape;landscape,Portrait;","orientasi");
	 ?>
    </td> 
</tr-->
<input type='hidden' name=orientasi value=''>
<?php
$addf.="
$('#tglcetak_$rnd').datepicker();
";

?>
 <tr class=troddform2 $sty >
	<td class=tdcaption ></td>
	<td>
    <?php    
	$onc="$('#fraport_$rnd').submit();";
	$onc1="onclick=\"$('#media2').val('');$('#contentOnly').val('2');$('#useJS').val('');$onc;\" ";
	$onc2="onclick=\"$('#media2').val('doc');$onc;\" ";
	$onc3="onclick=\"$('#media2').val('pdf');$('#contentOnly').val('1');$('#useJS').val('2');$onc;\" ";
	echo "<input type=hidden id='jmid' name='jmid' value='$jmid'>";
	echo "<input type=hidden id='media2' name='media2' value=''>";
	echo "<input type=button value='Tampilkan $judul' target=_blank class='btn btn-success btn-sm' $onc1 >";
	echo "&nbsp; &nbsp; <input type=button value='Export ke DOC' target=_blank class='btn btn-primary btn-sm'$onc2 >";
	if ($jcetak!='chb') echo "&nbsp; &nbsp;<input type=button value='Export ke PDF' target=_blank class='btn btn-primary btn-sm'$onc3 >";
	
	?>
    </td> 
    
    
</tr>
</table>
</form>
<?php
$t="<div id=tfbe"."$rnd name='function before edit' style='display:none'>
		$addf
		</div>";
echo $t;
//cekCatatanMapel(1003,1,1);

?>
<br>
<div id=tnilai_<?=$rnd?> ></div>

