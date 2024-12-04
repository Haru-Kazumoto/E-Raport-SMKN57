<?php
$useJS=2;
include_once "conf.php";
extractRequest();
$kelas=cariField("select nama from kelas where kode='$kode_kelas'");
$kd=cariField("select kd from kompetensi where kode='$kode_kompetensi'");
$mp=cariField("select matapelajaran.nama from matapelajaran inner join kompetensi on matapelajaran.kode=kompetensi.kode_matapelajaran  where kompetensi.kode='$kode_kompetensi'");
//$semester=cariField("select semester from kompetensi where kode='$kode_kompetensi'");
//$ki=($det=="ss"?"Sikap & Spiritual":($det=="p"?"Pengetahuan":"Keterampilan"));
//$sJenisKI="Sikap Sosial dan Spiritual,Pengetahuan,Keterampilan";
$sJenisKI="Sikap Sosial dan Spiritual";

	
$jPenilaian="Penilaian Observasi;OBSERVASI,Penilaian Diri;PD,Penilaian Antar Peserta Didik;APD,Jurnal;JURNAL";
$jPenilaianJurnal="Jurnal";
//conf2 $jPenilaianPD=$jPenilaianAPD= $jPenilaianObservasi="Spiritual,Jujur,Gotong Royong,Toleransi,Santun,Percaya Diri,Tanggung Jawab,Disiplin";


if ($_REQUEST["jpx"]) {
	$_SESSION['jpx']=$jpx;
} else {
	$jpx=$_SESSION['jpx'];	
}
	$jenis=($jpx==2?"APD":"PD");
	$jenisP=($jpx==2?"Penilaian Antar Peserta Didik":"Penilaian Diri");

//mendeteksi nis jika apd
	
if ($op!='') {
	extractRecord("select kkm as jaraknis,nama as mp from matapelajaran where kode='$kode_matapelajaran'");
	if ($kode_matapelajaran=='') die("");
	$jaraknis=cariField("select kkm from matapelajaran where kode='$kode_matapelajaran'");
	$anis=getArray("select nis from siswa where kode_kelas='$siswa_kodekelas'");
	
	if (is_integer(strpos(strtolower($mp),"agama"))){
		$agm=cariField("select agama from siswa where nis='$siswa_nis'");
		$anis=getArray("select nis from siswa where kode_kelas='$siswa_kodekelas' and agama='$agm'");
		 
	}
	$jsiswa=count($anis);

	if ($jenis=='PD') {
		$nisdinilai=$nis;
		$cek=cariField("select id from nilai_sikap where kode_matapelajaran='$kode_matapelajaran' and semester='$semester' and penilai='$nis'");
		 
		if (($cek=='') && ($jsiswa>1)) {
			echo "<br><div class=comment1>Anda <b>belum bisa</b> memasukkan penilaian diri untuk mata pelajaran <b>$mp</b> <br />
			sebelum melakukan penilaian antar peserta didik. <a href=# onclick=bukaAjax('content0','index2.php?det=inputnilaisikap&jpx=2')>Klik di sini </a> untuk memasukkan nilai antar peserta didik.
			</div>";
			exit;
			}
		//cek apakah sudah isi apd belum
		
	} else if ($jenis=='APD') {
		
		$nispenilai=$nis=$_SESSION['nis'];

		$jaraknis=($jaraknis%$jsiswa)+1;
		if ($jaraknis==0) $jaraknis=1;
		//echo "jn 	$jaraknis";
		$addKet="";
		if ($jsiswa==1) {
			$addKet="Jumlah siswa yang mengikuti mata pelajaran ini hanya ada 1 orang,<br>siswa tidak perlu melakukan penilaian antar peserta didik.";
			echo "<div style='border:2px solid #000; background-color:#fc9;padding:10px;margin:0px 5px 12px 0'>$addKet</div>";
			exit;
			}
		$i=0;
		
		foreach ($anis as $ns) {  if ($ns==$nispenilai) { $pospenilai=$i; } $i++;}
		//0+-7 = -7
		
		$posdinilai=($pospenilai+$jaraknis);
		if ($posdinilai<=0) 
			$posdinilai=$jsiswa+$posdinilai;
		elseif ($posdinilai>=$jsiswa) 
			$posdinilai=$posdinilai-$jsiswa;
			
		if (($posdinilai==$pospenilai) ||($posdinilai>=$jsiswa)) $posdinilai=$pospenilai+1;
		
		$nisdinilai=$anis[$posdinilai];
		extractRecord("select nama as namadinilai from siswa where nis='$nisdinilai'");
	}
	
	$cek=cariField("select id from nilai_sikap where kode_matapelajaran='$kode_matapelajaran' and semester='$semester' and nis='$nisdinilai' and jenis='$jenis' ");
	$isreadonly=($cek==''?false:true);
}
//echo "op : $op ...";
if ($op=='savedata') {
	//simpan data
	$i=$tnilai=0;
	$t=$kel=$kellama="";
	$akel=array();$nkel=0;$ajkel=array();$jikel=0;
	foreach ($_REQUEST['idparam'] as $idparam) {
		$nilai=($_REQUEST['nilaiparam'][$i])*1;
		if ($nilai>4) 
			die("Error Maxsimal nilai 4");
		elseif ($nilai<0) $nilai=0;
		$kel=$_REQUEST['kelparam'][$i];
		$kelbaru=$_REQUEST['kelparam'][$i+1];
		
		if (($kel!=$kelbaru) ) {
				//echo "n: $nilai ";
				$rkel=($nkel+$nilai)/($jikel+1);
				array_push($akel,$rkel);//nilai dimasukkan
				$nkel=0;
				$jikel=0; //jumlah item dalam kelompok 
				//echo "$kel : $rkel <br>";
		} else {
			//echo "n: $nilai ";
			$nkel+=$nilai;
			$jikel++;
		}
			
		$i++;
		$snilai.=($snilai==''?'':'|')."$idparam#$kel#$nilai";
		$tnilai+=$nilai;
		//echo " ".$nilai;
	}
	
	if ($jenis=='JURNAL') {	$snilai.="#jurnal:$jurnal";}
	$sy="nis='$nisdinilai' and jenis='$jenis' and kode_matapelajaran='$kode_matapelajaran' and semester='$semester'";
	if (carifield("select id from nilai_sikap where $sy ")=='')  
		$sq="insert into nilai_sikap(nis,jenis,kode_matapelajaran,semester,snilai,penilai)
			values('$nisdinilai','$jenis','$kode_matapelajaran','$semester','$snilai','$nispenilai');";
	else
		$sq="update nilai_sikap set snilai='$snilai',penilai='$nispenilai' where $sy";
		
	mysql_query($sq);
	//echo "<br> $sq";
	//update ke data nilai
	//echo "$rnilai=($tnilai/$i)*25;";
	$fldnilai=($jenis=='PD'?"N2":($jenis=='APD'?"N3":($jenis=='OBSERVASI'?"N1":"N4")));
	
	//$rnilai=($tnilai/$i)*25;
	//echo "<br>jumlah :";
	$rnilai=0;$i=0;
	foreach($akel as $j){ 
		$rnilai+=$j;
		$i++; //echo "$j - ";
	} 
	$rnilai=$rnilai/$i*25;
	$sq="select kode from kompetensi where semester='$semester' and kode_matapelajaran='$kode_matapelajaran' and ki='Sikap Sosial dan Spiritual'";
	//echo $sq;
	$h=mysql_query($sq);
	while ($r=mysql_fetch_array($h)) {
		$sy2=" nis=$nisdinilai and kode_kompetensi='$r[kode]'";
		if (carifield("select id from nilai_kompetensi_siswa where $sy2 ")=='')  
			$sq="insert into nilai_kompetensi_siswa(nis,kode_kompetensi,$fldnilai) values('$nisdinilai','$r[kode]','$rnilai');";
		else
			$sq="update nilai_kompetensi_siswa set $fldnilai='$rnilai'    where $sy2";
		//echo "<br>".$sq;
		mysql_query($sq);
		mysql_query("update nilai_kompetensi_siswa set nilai= (2*N1 + N2 + N3 + N4) / 5 where $sy2");
	}

	$t.="simpan data nilai sikap ( $jenis ) $nisdinilai berhasil .....<br>";
	echo $t;
	exit;
	$op='showdata'; 
}

if ($op=='showdata') {
	
			
	$ket="<div class=subtitleform2>Penilai : $siswa_nama ($siswa_nis)<br
	<br>Siswa yang Dinilai: <b>$namadinilai ($nisdinilai)</b></div>";
	//$ket.="<br>Jumlah Siswa kelas $siswa_kelas : $jsiswa ";
	//$ket.="<br>Jarak  : $jaraknis Posisi Penilai: $pospenilai Posisi Dinilai $posdinilai";
	echo $ket;

	echo "<div style='border:2px solid #000; background-color:#CFC;padding:10px;margin:0px 5px 12px 0'> <b>Keterangan:</b><br />
1. Penilaian dilakukan secara objectif.<br />
2. Perubahan setelah penyimpanan tidak dapat dilakukan.
	$addKet</div>";
	
	if ($jenis=='') $jenis="PD";
	
		//extract nilai awal dari data nilai
		$sy="nis='$nisdinilai' and jenis='$jenis' and kode_matapelajaran='$kode_matapelajaran' and semester='$semester'";
		$snilai=" |".cariField("select snilai from nilai_sikap where $sy ");
		
		//echo " $sy ";
		//echo "".$snilai;
		if ($jenis=="PD")
			$ajp=explode(",",$jPenilaianPD);
		elseif ($jenis=="APD")
			$ajp=explode(",",$jPenilaianAPD);
		elseif ($jenis=="OBSERVASI")
			$ajp=explode(",",$jPenilaianObservasi);
		elseif ($jenis=="JURNAL")
			$ajp=explode(",",$jPenilaianJurnal);
		
		$alegend=array();
		$aparam=$aidparam=$abobot=array();
		//$t="Penilaian: $jenis<br><br>";
		$t="<div id='tabs1' style='width:700px'><ul>";
		for ($x=0;$x<count($ajp);$x++) {
			$t.="<li><a href='#tabs-"."$x'>$ajp[$x]</a></li>";
			$sqlg="select concat(isi,'|', ket,'|',bobot) from sikap_legend where kelompok='$ajp[$x]' and jenis='$jenis' order by urutan asc ";
			$g=getArray($sqlg);
			array_push($alegend,$g);
			
			$sqlg="select concat(id,'|',deskripsi,'|',np) as pr from sikap_paramnilai where kelompok='$ajp[$x]' and jenis='$jenis' order by urutan ";

			$g=getArray($sqlg);
			array_push($aparam,$g);
		}
		$t.="</ul>";
		$jitem=0;
		for ($x=0;$x<count($ajp);$x++) {
			$t.= "<div id='tabs-"."$x' >";
			if (is_array($aparam[$x])) {
				$t.="<table border=1 cellspacing=0 cellpadding=0 width=675>";
				$br=0;
				$jlegend=count($alegend[$x]);
				$judul1="<td class=tdjudul rowspan=2>NO</td><td class=tdjudul  rowspan=2>DESKRIPSI</td>
				<td  colspan=$jlegend class=tdjudul>NILAI</td>";
				$addJudul1="";
				foreach($aparam[$x] as $spr) {
					$apr=explode("|",$spr);
					$aprket=explode("#",$apr[1]);//keterangan
					if ($aprket[1]!='') $addJudul1="<td rowspan=2 class=tdjudul>DESKRIPSI</td>";
				}
				$judul1.=$addJudul1;

				//$j3=explode("|",$alegend[$x][0]."|");
				//if ($j3[1]!='') $judul1.="<td rowspan=2 class=tdjudul>DESKRIPSI</td>";
				
				$judul2=$judul3="";
				foreach($alegend[$x] as $slg) {
					$alg=explode("|",$slg);
					$judul2.="<td width=30 class=tdjudul>$alg[0]</td>";
				}
				$t.="<tr>$judul1</tr><tr>$judul2</tr>";
	
	
				foreach($aparam[$x] as $spr) {
					$troe=($br%2==0?"troddform2":"trevenform2");
					$apr=explode("|",$spr);
					$aprket=explode("#",$apr[1]);//keterangan
					$addn=$addn2="";
					$io=0;
					//$nilai=cariField("select nilai from nilai_sikap where nis='$nis' and idparam='$apr[0]' ")*1;
					//echo "#$apr[0]#$ajp[$x]# ->";
					$nilai=0;
					$scari="|$apr[0]#$ajp[$x]#";
					$pos=strpos($snilai,$scari);
					//echo "<br>cari: $scari pos:$pos ";
					if (is_integer($pos)) {
						$nilai=substr($snilai,$pos+strlen($scari),1)*1;	
						//echo "p:$scari n:$nilai ";	
					} else $def="";

					foreach($alegend[$x] as $slg) {
						$io++;
						$alg=explode("|",$slg);
						$def=($nilai==$alg[2]?"checked":"");
						if (($isreadonly)&&($def=='')) $def.=" disabled='disabled' readonly='readonly' ";
		
						$addn.="<td width=30>
						<input type=radio name='radio[$jitem]' id='radio[$jitem][$io]' onclick=hitungNSikap($jitem,$io,$alg[2]) $def ></td>";
					}
					if ($aprket[1]!='')  $addn2="<td>$aprket[1]</td>";
					$t.="<tr class=$troe ><td width=30>".($br+1)."</td>
						<td>$aprket[0]
						<span style='display:none'>
						<input type=text name=idparam[$jitem] id=idparam[$jitem]  value='$apr[0]' size=3> 
						<input type=text name=npparam[$jitem] id=npparam[$jitem] value='$apr[2]' size=1> 
						<input type=text name=jlgparam[$jitem] id=jlgparam[$jitem]  value='$io' size=1> 
						<input type=text name=kelparam[$jitem] id=kelparam[$jitem]  value='$ajp[$x]' size=10> 
						<input type=text name=nilaiparam[$jitem] id=nilaiparam[$jitem] value='$nilai' size=1> 
						</span></td>
						$addn $addn2</tr>";
					$br++;
					$jitem++;
				}
				
				$t.="</table>";
			} //item per group
			if (is_array($alegend[$x])) {
				$t.="<br>Keterangan :";
				foreach($alegend[$x] as $slg) {
					$alg=explode("|",$slg);
					$t.="<br>$alg[0] : $alg[1]";
				}
				if ($jenis=='JURNAL') {
					$scari="#jurnal:";
					$pos=strpos($snilai,$scari);
					//echo "<br>cari: $scari pos:$pos ";
					if (is_integer($pos)) {
						$def=substr($snilai,$pos+strlen($scari),strlen($snilai));	
						//echo "p:$scari n:$nilai ";	
					} else $def="";

				$t.="<BR><br>Catatan :";
				$t.="<br><textarea cols=92 rows=5 name=jurnal id=jurnal_sik>$def</textarea>";
					
					}
			}
			$t.="</div>";
			
		} //for
		$t.="</div>";
		$nfAction="inputnilaisiksis.php";
		$idForm="insik_".rand(1231,2317);
		$asf="onsubmit=\"submitNS('$idForm','ts"."$idForm','sikap'); return false; \" ";
		$tx="";
		$tx.="<div id=ts"."$idForm ></div>";
		$tx.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
		//$tx.="<div class=titlepage >Data $nmCaptionTabel</div>";
		$tx.="</td>";
		$tx.=$t;
		$tx.="
		<div style='display:none'>
		<br>Nilai<input type=text name=totnilai id=totnilai value='$totnilai'>
		<br>Jumlah Item<input type=text name=jitem id=jitem value='$jitem'>
		</div>
		<br>
		<input type=hidden name=op id=op value='savedata'> 
		<input type=hidden name=kode_matapelajaran id=kdmp_sik value='$kode_matapelajaran'> 
		<input type=hidden name=semester id=smt_sik value='$semester'> 
		<input type=hidden name=jenis id=jenis_sik value='$jenis'>";
		if (!$isreadonly) $tx.="<br><input type=submit value='Simpan Data'>";
		
		$tx.="	</form>";
		echo $tx;
	
	
	exit;
}
//if ($userID=='siswa') {
	//echo "jpx $jpx";

	$combo="semester";
	$kode_kelas=$siswa_kodekelas;
	$dsresultcombo=0;
	include "filtercombo.php";
	echo "<div style='display:none'>
	</div>";
	$isiSemester=$result;
	
//}
?>

<div class=titlepage >Input Nilai Sikap dan Spiritual
(<?=$jenisP?>)
</div> 

<table>
<?php if ($jenis=='PD') {?>
<tr class=troddform2 $sty >
	<td class=tdcaption >Nama Siswa</td>
	<td><div id=tkelas>: <?=$siswa_nama?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Nis</td>
	<td><div id=tkelas>: <?=$siswa_nis?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td><div id=tkelas>: <?=$siswa_kelas?></div></td> 
</tr>
<?php } ?>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td>: <span id=tsemester><?=$isiSemester?></span></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelompok</td>
	<td>: <span id=tjenisMP><?=um412_isiCombo5("$sJenisKdMP",'jenisMP','kode','nama','-Pilih-',$jenisMP,"gantiComboNilai('jenisMP','sikap')");?></span></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Mata Pelajaran</td>
	<td>: <span id=tmp><? //=um412_isiCombo5('select kode,nama from matapelajaran','kode_matapelajaran','kode','nama','-Pilih-',$kode_matapelajaran,"filterTabelInput('$hal')");?></span></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Penilaian</td>
	<td>: <?=$jenisP?>
    </td> 
</tr>
</table>
<input type=hidden name='ki' value='<?=$sJenisKI?>'>
<input type=hidden name='kode_kelas' id='kode_kelas' value='<?=$kode_kelas?>'>
</form>
<div id=tnilai></div>

