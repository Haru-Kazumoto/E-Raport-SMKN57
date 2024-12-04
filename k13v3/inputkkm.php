<?php
$useJS=2;
include_once "conf.php";
extractRequest();
cekVar("media,asf,semester");

if ($media=='print') {
	if ($semester=='') {
		echo "isi semester terlebih dahulu.....";
		exit;
	}
	$kelas=carifield("select nama from kelas where kode='$kode_kelas'");
	$sq="select matapelajaran from map_matapelajaran_kelas where kode_kelas='$kode_kelas' and semester='$semester'";
	$csq=cariField($sq);
	$t="";	
	$ampguru=explode('#',$csq);
	$sKDMP="";
	foreach ($ampguru as $smpg) {
		$ampg=explode("|",$smpg);
		$sKDMP.=($sKDMP==""?"":",").$ampg[0];
	}
	
	$aKDMP=explode(",",$sKDMP);	
	$jMP=count($aKDMP);
	$sFieldku="nis,nama,$sKDMP,RERATA";
	$aField=explode(",",$sFieldku);
	$aFieldCaption=explode(",",strtoupper($sFieldku));
	
	
	$t="";	
	$xkdmp=" (kode='".str_replace(",","' or kode='",$sKDMP)."') ";
	$fld1="kbp"."$semester";
	$fld2="kbk"."$semester";
	$sq="select kode,nama,$fld1,$fld2  from matapelajaran where $xkdmp";
	
	$hq=mysql_query($sq);
	while ($r=mysql_fetch_array($hq)) {
		$t.="<tr>
		<td align=center>$r[kode]</td>
		<td>$r[nama]</td>
		<td align=center>$r[$fld1]</td>
		<td align=center>$r[$fld2]</td>
			</tr>";
	}
	$t="
	<div class=judul2 align=center>DAFTAR KB/KKM </div>
	<div class=judul3 align=center>KELAS $kelas,  SEMESTER $semester </div>
	<div class=judul3 align=center>TAHUN AJARAN $thpl </div>
	<br>
	<br>
	<table width=100% border=1 cellspacing=0 cellpadding=0 class=tbcetakbergaris>
	<tr>
		<td valign=center align=center>KODE</td>
		<td valign=center align=center>MATA PELAJARARAN</td>
		<td align=center>KB/KKM <br>PENGETAHUAN</td>
		<td align=center>KB/KKM <br>KETERAMPILAN</td>
	</tr>
	$t</table>";
	echo "<div class=page>$t</div>";
	exit;
	
}
$idForm="fledger_1";
//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\""; 
$nfAction="inputkkm.php?media=print";
echo "<div id=ts"."$idForm ></div><form id='$idForm' action='$nfAction' method=Post $asf class=formInput target=_blank>";
?>

<div class=titlepage >Cetak KB/KKM</div> 
<table>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tahun Pelajaran</td>
	<td><input type=hidden name=tahun id=tahun value='<?=$thpl?>'><?=$thpl?></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td><div id=tkelas_<?=$rnd?> >
	<?php 
	echo um412_isiCombo5('select kode,nama from kelas order by tingkat,nama','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboLedger('kelas',$rnd)");
	?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><div id=tsemester_<?=$rnd?> >
	<?php //echo um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih-',$semester,'');?>
	</div></td> 
</tr> 
<?php 
echo "<input type=hidden name=urutan value='Nama' >";
?>
<tr class=troddform2 $sty >
	<td class=tdcaption >&nbsp;</td>
	<td><input type=submit value='Cetak KKM' class='btn btn-success btn-sm'></td> 
</tr>
</table>
</form>
<br>
<div id=tnilai></div>

