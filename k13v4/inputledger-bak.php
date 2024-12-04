<?php
$useJS=2;
include_once "conf.php";
extractRequest();
cekVar("media,asf");
$kelas=cariField("select nama from kelas where kode='$kode_kelas'");
$ki=($jki==1?"Pengetahuan":($jki==2?"Keterampilan":($jki==3?"Sikap dan Spiritual":"Total")));

//update
mysql_query("
UPDATE map_matapelajaran_kelas
SET matApelajaran = REPLACE(matapelajaran, '#A06|', '')
WHERE matApelajaran LIKE '%#A06|%'");


$kdawal=($jki==1?"P":($jki==2?"K":($jki==3?"S":""))); //untuk ledger total tanpa kode awal
if ($media=='print') {
	//cari array kodemp pada semester
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
	//$addurl="op=showdata&cetak=pdf&useJS=2&kode_kompetensi=$kode_kompetensi&kode_kelas=$kode_kelas&ki=".urlencode($ki)."&semester=$semester&kode_matapelajaran=$kode_matapelajaran";
	
	$addurl="op=showdata&cetak=pdf&useJS=2&kode_kelas=$kode_kelas&ki=".urlencode($ki)."&semester=$semester";
	
	$w=array(2,10,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2,2);
	$aAlign=explode(",","C,L,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C,C"); 
	$jlhfield=count($aField);
	$jlhcol=$jlhfield+1; 
	
	/*
	extractRecord("select bobotpg,bobotsk,bobotkt  from tbconfig1");
	$aBobotPg=explode("#",$bobotpg);$jBobotPg=$aBobotPg[0]+$aBobotPg[1]+$aBobotPg[2];
	$aBobotKt=explode("#",$bobotkt);$jBobotKt=$aBobotKt[0]+$aBobotKt[1]+$aBobotKt[2];
	$aBobotSk=explode("#",$bobotsk);$jBobotSk=$aBobotSk[0]+$aBobotSk[1]+$aBobotSk[2]+$aBobotSk[3];
	
	$dinilaiPg="(n1*$aBobotPg[0]+n2*$aBobotPg[1]+n3*$aBobotPg[2])/$jBobotPg";
	$dinilaiKt="(n1*$aBobotKt[0]+n2*$aBobotKt[1]+n3*$aBobotKt[2])/$jBobotKt";
	$dinilaiSk="(n1*$aBobotSk[0]+n2*$aBobotSk[1]+n3*$aBobotSk[2]+n4*$aBobotSk[3])/$jBobotSk";
	$dinilai=($ki=='Pengetahuan'?$dinilaiPg:($ki=='Keterampilan'?$dinilaiKt:($ki=='Sikap dan Spiritual'?$dinilaiSk:"semua")));		
	*/
	$dinilai=$dinilaiPg=$dinilaiKt=$dinilaiSk="nilai";
	$t.= "<div class='judul2'>LEDGER NILAI KOMPETENSI ".strtoupper($ki)."</div>";
	$t.= "<div class='judul3'>KELAS : $kelas SEMESTER : $semester TAHUN AJARAN : $tahun</div><br>";
	
	$jdtb="";
	$jdtb.="<table width=100% border=1 cellspacing=0 cellpadding=0  class='tbcetakbergaris f11px arial' >";
	$jdtb.="<tr>";
	$jdtb.="<td align=center>NO</td>";
	for ($i = 0; $i <  $jlhfield-1; $i++) {
		$jdtb.="<td align=center> $aFieldCaption[$i]</td>";
	}
	$jdtb.="<td align=center> $aFieldCaption[$i]</td>";
	$jdtb.="</tr>";
	
	$t.=$jdtb;
	//$t.="</table>";
	$h=mysql_query("select nis,nama from siswa where kode_kelas='$kode_kelas' order by siswa.nama");  
	$jumlah=0;
	$br=0;
	$aJumlah=array();
	$aJumlahKol=array();
	while ($r=mysql_fetch_array($h)) {         
	  	if ($br==25) {
		  $t.="</table></div>
		  <div class=page-landscape> 
		  $jdtb";
		}
		  
	  $t.="<tr>"; 
	  $t.="<td align=center >".($br+1)."</td>";
	  $i=0;$t.="<td align=center > ".$r[$aField[$i]]."</td>";
	  $i++;$t.="<td > ".$r[$aField[$i]]."</td>";
		//semua mp
		$jml=0;
		$k=0;//kolom
		foreach ($aKDMP as $kdmp) {					
			//sengaja menggunakan sum bukan avg,karena terkadang nilai belum dimasukkan 
			//jika kodeawal<>"" maka harus diurai per ki
			$jnil=0;
			$jkom2=0;
			$sqfrom=" nilai_kompetensi_siswa inner join kompetensi on kompetensi.kode=nilai_kompetensi_siswa.kode_kompetensi ";
			if ($kdawal!='') {
				$syw=" kode_kompetensi like '$kdmp"."$kdawal%' and nis='$r[nis]' and semester='$semester' ";
				$sqq="select sum($dinilai) from $sqfrom where $syw ";
				$jnil+=cariField($sqq)*1;
				$jkom2+=carifield(" select count($dinilai) from $sqfrom where $syw ")*1;
			} else {
				$kda="P";
				$syw=" kode_kompetensi like '$kdmp"."$kda%' and nis='$r[nis]' and semester='$semester' ";
				$sqq="select sum($dinilaiPg) from $sqfrom  where $syw";
				$jnil+=cariField($sqq)*1;
				$jkom2+=carifield(" select count($dinilaiPg) from $sqfrom where $syw ")*1;
			
				$kda="K";
				$syw=" kode_kompetensi like '$kdmp"."$kda%' and nis='$r[nis]' and semester='$semester' ";
				$sqq="select sum($dinilaiKt) from $sqfrom where $syw ";
				$jnil+=cariField($sqq)*1;
				$jkom2+=carifield(" select count($dinilaiKt) from $sqfrom where $syw ")*1;
			/*
				$kda="S";
				$sqq="select sum($dinilaiSk) from nilai_kompetensi_siswa inner join kompetensi on kompetensi.kode=nilai_kompetensi_siswa.kode_kompetensi where kode_kompetensi like '$kdmp"."$kda%' and nis='$r[nis]' and semester='$semester'";
				$jnil+=cariField($sqq)*1;
				*/
			}			
			
			$sq2="select count(kode) from kompetensi where kode  like '$kdmp"."$kdawal%' and semester='$semester' ";
			$jkom=max(1,cariField($sq2)*1); 
			//jika dibagi dengan jumlah semua kode kompetensi
			$nilai=round($jnil/$jkom,2);
			
			//jika dibagi dengan jumlah yg diisi saja
			$jkom2=max(1,$jkom2); 
			$nilai=round($jnil/$jkom2,2);
			
			//echo "$jnil/$jkom=$nilai<br>";	
			
			if ($ki=='Sikap dan Spiritual')
				$predikat=konversiNilai($nilai,'sikap');
			else	
				$predikat=konversiNilai($nilai,'predikat');
			//nilai total juga menggunakan konversi predikat
			//$jnilai="Nilai Murni";
			
			$konversi=konversiNilai($nilai,'pengetahuan'); //pengetahuan dan ketrampilan sama
			$hasil=($jnilai=='Nilai Murni'?$nilai:($jnilai=='Predikat'?$predikat:$konversi));
			$dijumlah=($jnilai=='Nilai Murni'?$nilai:($jnilai=='Predikat'?$nilai:$konversi));
			$jml+=$dijumlah;
			
			$sty="";
			//jika rata<kb maka belum kompeten, diberi warna
			$sqkkm="select kb".$kdawal.$semester." from matapelajaran where kode='$kdmp'";
			//echo "<br> ".$sqkkm;
			
			$kkm=carifield($sqkkm)*1;
			if ($nilai<$kkm) $sty=" style='background:#ccc' ";

			if ($nilai==0) $hasil="&nbsp;";
			
			$i++;$t.="<td align=center $sty> $hasil</td>";
			
			if ($br==0) 
				array_push($aJumlahKol,$dijumlah);
			else {
				$aJumlahKol[$k]+=$dijumlah;
			}
			$k++;
		}
		
		//$rata=round($jml/$k,2);
		$rata=round($jml/($k-4),2); //-4 agama lain
		$sty="";
		
		
		if ($jnilai=='Predikat') {
			if ($ki=='Sikap dan Spiritual')
				$predikat=konversiNilai($rata,'sikap');
			else	
				$predikat=konversiNilai($rata,'predikat');

			$i++;$t.="<td align=center $sty >$predikat</td>";
		} else {
			if ($rata==0) $rata="&nbsp;";
			$i++;$t.="<td align=center $sty> $rata</td>";
		}
		
		array_push($aJumlah,$jml);
		$br++; 
	}
	
	//jumlah
	$t.="</tr>"; 
	$t.="<tr>"; 
	$i=0;$t.="<td >&nbsp; ".$r[$aField[$i]]."</td>";
	$i++;
	$t.="<td colspan=2> RERATA</td>";
	$k=0;	
	foreach ($aKDMP as $kdmp) {
		$rata=round($aJumlahKol[$k]/$br,2);
		if ($jnilai=='Predikat') {
			if ($ki=='Sikap dan Spiritual')
				$predikat=konversiNilai($rata,'sikap');
			else	
				$predikat=konversiNilai($rata,'predikat');

			$i++;$t.="<td align=center> $predikat</td>";
		} else {
			//$i++;$t.="<td > $aJumlahKol[$k]</td>";
			$i++;$t.="<td align=center > $rata</td>";
		}
		$k++;
	}
	$i++;
	$t.="<td >&nbsp; </td>";
	
	$t.="</tr>"; 
	$t.="</table>"; 
	$t.="<br>";
	$w1=$w3=40;
	//$t=3;
	//$t.="</div><div class='page-landscape'>";
	//daftar mata pelajaran
	//dibagi menjadi 2 atau3  kolom
	$jlhkdmp=count($aKDMP);
	

	$pembagi=($jlhkdmp>15?3:($jlhkdmp>10?2:1));
	//$pembagi=1;//biar 1kolom saja
	
	$brperkolom=round($jlhkdmp/$pembagi,0);
	
	$xtb1=$xtb2=$xtb3="";
	//$t.="<br>pembagi $pembagi , brperkolom $brperkolom ";
	
	$br=0;
	$kolom=1;
	foreach ($aKDMP as $kdmp) {         
	  $br++;            
	  $mp=cariField("select nama from matapelajaran where kode='$kdmp' ");
	  $baris="<tr>"; 
	  $baris.="<td > $kdmp</td>";;
	  $baris.="<td >  $mp</td>";         
	  $baris.="</tr>";
	  
	  if ($kolom==1) 
		  $xtb1.=$baris;
	  elseif ($kolom==2) 
		$xtb2.=$baris;
	  elseif ($kolom==3) 
		$xtb3.=$baris;
		
	  if ($br%($brperkolom+1)==0) $kolom++;
	}
	
	$t.="<table ><tr>";
	
	$jtb1="<table border=1 class=tbcetakbergaris cellspacing=0 cellpadding=0 width=100% >";
	$jtb2="</table>";
	$wsp="<td style='width:10px'>&nbsp;</td>";
	
	if ($xtb1!='') $t.="<td valign=top>$jtb1 $xtb1 $jtb2</td>";
	if ($xtb2!='') $t.="$wsp<td valign=top>$jtb1 $xtb2 $jtb2 </td>";
	if ($xtb3!='') $t.="$wsp<td valign=top>$jtb1 $xtb3 $jtb2 </td>";
	
	$t.="</tr></table>";
	
	$nmFilePDF='ledger_'.$semester.'_'.str_replace(" ","",$kelas);	
	
	//menghilangkan header footer
	if ($media2=='xls') {
		header("Content-Type: application/vnd.ms-excel");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("content-disposition: attachment;filename=$nmFilePDF.xls");
		echo $t;
	}
	else {
		echo "
		<div class='page-landscape'>$t</div>
		<style>
		@media print {
			.page-landscape {
				padding:1cm 2.3cm 0.5cm 1cm;
				height:18cm;
			}
			
		}
		</style>
		";
	}
	exit;	
}

 
$idForm="fledger_1";
//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\""; 
$nfAction="inputledger.php?media=print&jki=$jki";
echo "<div id=ts"."$idForm ></div><form id='$idForm' action='$nfAction' method=Post $asf class=formInput target=_blank>";
	?>

<div class=titlepage >Cetak Ledger <?=$ki?></div> 
<table>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tahun Pelajaran</td>
	<td><input type=hidden name=tahun id=tahun value='<?=$thpl4?>'><?=$thpl4?></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td><div id=tkelas_<?=$rnd?> ><?=um412_isiCombo5('select kode,nama from kelas order by tingkat,nama','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboLedger('kelas',$rnd)");?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><div id=tsemester_<?=$rnd?> ><? //=um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih-',$semester,'');?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Jenis Nilai</td>
	<td><?
	/*
	if ($ki=='Sikap dan Spiritual')    
		$jnx='Nilai Murni,Predikat';
	else
		$jnx='Nilai Murni,Nilai Konversi,Predikat';
*/
	$jnx='Nilai Murni,Predikat';
	echo um412_isiCombo5("R:$jnx",'jnilai');
	?></td> 
</tr>
<?php
/*
<tr class=troddform2 $sty >
	<td class=tdcaption >Urut Berdasarkan</td>
	<td><?=um412_isiCombo5('Nama,Jumlah','urutan','kode','nama','-Pilih-',$urutan,"");?></td> 
</tr>

*/
echo "<input type=hidden name=urutan value='Nama' >";
echo "<input type=hidden name=ki value='$ki' >";
?>
<tr class=troddform2 $sty >
	<td class=tdcaption >&nbsp;</td>
	<td>
	
	<?php
	$onc="$('#$idForm').submit();";
	$onc1="onclick=\"$('#media2').val('');$onc;\" ";
	$onc2="onclick=\"$('#media2').val('xls');$onc;\" ";
	
	echo "<input type=hidden id='media2' name='media2' value=''>";
	echo "<input type=button value='Buat Ledger' target=_blank class='btn btn-success btn-sm' $onc1 >&nbsp; &nbsp; ";
	echo "<input type=button value='Export ke XLS' target=_blank class='btn btn-primary btn-sm'$onc2 >";
	?>
	</td> 
</tr>
</table>
</form>
<br>
<div id=tnilai></div>