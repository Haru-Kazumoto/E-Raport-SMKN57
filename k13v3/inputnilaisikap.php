<?php
$useJS=2;
include_once "conf.php";
extractRequest();
$kelas=cariField("select nama from kelas where kode='$kode_kelas'");


if ($op=='savedata') {
	//simpan data
	$i=$tnilai=0;
	$t=$kel=$kellama="";
	$akel=array();$nkel=0;$ajkel=array();$jikel=0;
	foreach ($_REQUEST['nis'] as $idn) {
		$nis=($_REQUEST['nis'][$i])*1;
		$snilai=($_REQUEST['snilai'][$i]);

		$sy="nis='$nis' and semester='$semester' ";
		if (carifield("select id from nilai_sikap where $sy ")=='')  
			$sq="insert into nilai_sikap(nis,semester,snilai) values('$nis','$semester','$snilai');";
		else
			$sq="update nilai_sikap set snilai='$snilai' where $sy";
			
		mysql_query($sq);
		$i++;
	}
	


	$t.="simpan data deskripsi sikap berhasil <br>";
	//echo $t;
	//exit;
	$op='showdata'; 
}

if ($op=='showdata') {
	
	$idForm="fnilai_1";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\""; 
	$nfAction="inputnilaisikap.php?op=savedata&semester=$semester";
	//deteksi semua nis, jika belum ada, insert
	$sqTabel="select nis,nama from siswa where kode_kelas='$kode_kelas'";
	 
	$nmCaptionTabel="Deskripsi sikap $kelas";
	
	
	cekVar("jf,t,id,semester");
	if ($semester=='') {
		echo "Pilih semester terlebih dahulu";
		exit;
	}
	
	if ($media=='print') {
		//extractRecord("select matapelajaran.nama as mp,kompetensi.kd from kompetensi left join matapelajaran on kompetensi.kode_matapelajaran=matapelajaran.kode where kompetensi.kode='$kode_kompetensi'");
		
		$t.="<div class=page >";
		$t.="
		<div class=judul2 align=center>DAFTAR DESKRIPSI SIKAP</div>
		<br>
		<table width=100%>
		<tr><td width=100>Kelas/Semester </td><td>: $kelas/$semester</td></tr>
		</table>
		<br>
		";
		$t.="<table width=100% border=1 class=tbcetakbergaris >";
		$t.="<tr>";
		$rsp=" rowspan=2 ";
		$rsp="";
		$t.="<td class=tdjudul $rsp align=center >No</td>";
		$t.="<td class=tdjudul $rsp align=center >NIS</td><td class=tdjudul $rsp >Nama</td>";
			
		$t.="<td class=tdjudul style='width:65%' align=center >Deskripsi Sikap</td>";	
		$t.="</tr>";
		
		$hq=mysql_query($sqTabel);
		$br=0;
		$rdis="";
		while ($r=mysql_fetch_array($hq)) {
			$snilai=carifield("select snilai from nilai_sikap where nis='$r[nis]' and semester='$semester' ");
			if ($jf=="form") $snilai="";
			$idt="rec".$br;
			$troe=($br%2==0?"troddform2":"trevenform2");
			$t.="<tr id="."$idt class=$troe >";
			$t.="<td align=center>".($br+1)."</td>";
			$t.="<td align=center >$r[nis]</td><td>$r[nama]</td>";
			$t.="<td   >$snilai</td>";
			$t.="</tr>";
			$br++;
		}
		$jlhsiswa=$br;
		
		$t.="</table>";
	
			$t.="</div>";
			echo $t;
			exit;
	}


	
	$t.="<div id=ts"."$idForm ></div><form id='$idForm' action='$nfAction' method=Post $asf  >";
	$t.="<table width=100%>";
	$t.="<tr>";
	$t.="<td class=tdjudul >No</td>";
	$t.="<td class=tdjudul >NIS</td><td class=tdjudul>Nama</td>";
	$t.="<td class=tdjudul >Deskripsi Sikap</td>";	
	$t.="</tr>";
	$hq=mysql_query($sqTabel);
	$br=0;
	//$rdis=($ki=="Sikap Sosial dan Spiritual"?"readonly='true' title='Untuk mengisi nilai sikap dan spiritual, gunakan menu Input Nilai Sikap Dan Spiritual'  ":"");
	$rdis="";
	while ($r=mysql_fetch_array($hq)) {
		//$id=$r['id'];
		$snilai=carifield("select snilai from nilai_sikap where nis='$r[nis]' and semester='$semester' ");
		$idt="rec".$br;
		$troe=($br%2==0?"troddform2":"trevenform2");
		$t.="<tr id="."$idt class=$troe >";
		
		$t.="<td align=center>".($br+1)."</td>";
		$t.="<td>$r[nis]<input type=hidden name=nis[$br] value='$r[nis]'></td><td>$r[nama]</td>";
	
		$t.="<td align=center >
		<input type=text size=90 name=snilai[$br] id=snilai[$br] value='$snilai'  >
		</td>";
		$t.="</tr>";
		$br++;
	}
	$t.="</table>";
	
	$addurl="op=showdata&media=print&useJS=2&kode_kelas=$kode_kelas&semester=$semester";
	$t.="<div align=center style='margin-top:15px'>
	<div id=tcetak></div>
	 
	<input type=button class='btn btn-success btn-sm' value='Cetak Form'  onclick=\"window.open('inputnilaisikap.php?jf=form&$addurl','_blank');\" target=_blank > 
		<input type=button class='btn btn-success btn-sm' value='Cetak Nilai' onclick=\"window.open('inputnilaisikap.php?jf=nilai&$addurl','_blank');\" > 
		<input type=submit class='btn btn-primary btn-sm' value='Simpan'></center>
		
	";
	 
	$t.="<input type=hidden name=kode_kelas value='$kode_kelas'>";
	$t.="</form>";
	
	echo $t;
	exit;
}

?>

<div class=titlepage >Input Nilai Sikap</div> 

<table>

<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td><div id=tkelas><?=um412_isiCombo5('select * from kelas order by tingkat,nama','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboNilai('kelas','sikap',$rnd)");?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><div id=tsemester_<?=$rnd?> ><? //=um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih-',$semester,'');?></div></td> 
</tr>
</table>
  
</form>
<br>
<div id=tnilai_<?=$rnd?> ></div>

