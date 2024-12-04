<?php
$useJS=2;
include_once "conf.php";
cekVar("kode_matapelajaran,semester,ki,kode_kelas,op,jenisMP,cetak");

if (!isset($_REQUEST['kode_kompetensi'])) {
	$sq="select kode from kompetensi where kode_matapelajaran='$kode_matapelajaran' and  semester='$semester' and ki='$ki' ";
	$kode_kompetensi=cariField($sq);
}
$kelas=cariField("select nama from kelas where kode='$kode_kelas'");
$kd=cariField("select kd from kompetensi where kode='$kode_kompetensi'");
$mp=cariField("select matapelajaran.nama from matapelajaran inner join kompetensi on matapelajaran.kode=kompetensi.kode_matapelajaran  where kompetensi.kode='$kode_kompetensi'");


//$semester=cariField("select semester from kompetensi where kode='$kode_kompetensi'");

//$ki=($det=="ss"?"Sikap & Spiritual":($det=="p"?"Pengetahuan":"Keterampilan"));
$sJenisKI="Pengetahuan,Keterampilan";//,Sikap Sosial dan Spiritual

	
if ($op=='simpan') {
	//update bobot
	$squ="update kompetensi set bn1='$bn1',bn2='$bn2',bn3='$bn3'   where kode='$kode_kompetensi'";
	mysql_query($squ);
	
	$br=0;
	foreach ($_REQUEST["nis"] as $n1x) {
		$nis=$_REQUEST["nis"][$br];
		$n1=$_REQUEST["n1"][$br];
		$n2=$_REQUEST["n2"][$br];
		$n3=$_REQUEST["n3"][$br];
		//$n4=$_REQUEST["n4"][$br];
		$nilai=$_REQUEST["nilai"][$br];
		$sqc="select id from nilai_kompetensi_siswa where nis='$nis' and kode_kompetensi='$kode_kompetensi' ";
		//echo $sqc."<br>";
		$idc=cariField($sqc);
		if ($idc=='') {
			$squ="insert into nilai_kompetensi_siswa(nis,kode_kompetensi,nilai,n1,n2,n3) values('$nis','$kode_kompetensi','$nilai','$n1','$n2','$3')";
			}
		else  {
			$squ="update nilai_kompetensi_siswa set  n1='$n1',n2='$n2',n3='$n3',nilai='$nilai' where id='$idc'";
			}
			mysql_query($squ);
			//echo $squ."<br>";
		$br++;
		}
	//echo "<div style=comment1><center>Data Tersimpan .....<br></center></div>";
	$op="showdata"; //langsung menampilkan data
}

if ($op=='showdata') {
	cekVar("jf,t,id");

	if ($kode_kompetensi=='') {
		if ($ki=='')
			exit;
		else
			echo "Pilih kompetensi dasar..";
		exit;
	}
	
	$idForm="fnilai_1";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\""; 
	$nfAction="inputnilai.php?op=simpan&kode_kompetensi=$kode_kompetensi";
	//deteksi semua nis, jika belum ada, insert
	$sqd="select nis from siswa where kode_kelas='$kode_kelas'";
	$hqd=mysql_query($sqd);
	while ($rd=mysql_fetch_array($hqd)){
		$nis=cariField("select nis from nilai_kompetensi_siswa where nis='$rd[nis]'  and kode_kompetensi='$kode_kompetensi'");
		if ($nis=='') mysql_query("insert into nilai_kompetensi_siswa(nis,kode_kompetensi) values('$rd[nis]','$kode_kompetensi') ");
	}

	
	$sqTabel="Select kode_kompetensi,siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama,n1,n2,n3,n4,nilai  from nilai_kompetensi_siswa inner join siswa on nilai_kompetensi_siswa.nis=siswa.nis where siswa.kode_kelas='$kode_kelas' and nilai_kompetensi_siswa.kode_kompetensi='$kode_kompetensi' 
	order by siswa.nis";
	
	if ($jf=='form') {
	$sqTabel="Select kode_kompetensi,siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama,'' as n1,'' as n2,'' as n3,'' as n4,'' as nilai  from nilai_kompetensi_siswa inner join siswa on nilai_kompetensi_siswa.nis=siswa.nis where siswa.kode_kelas='$kode_kelas' and nilai_kompetensi_siswa.kode_kompetensi='$kode_kompetensi' 
	order by siswa.nis";
		}
	$nmCaptionTabel="Nilai Siswa $kelas";
	
	
	//cari bbobot
	$ambilbobot="kd";//def,kd,1 
	//jika bobot dari default
	$bn=array(0,0,0,0,0);
	if ($ambilbobot=='def') {
		extractRecord("select bobotsk,bobotpg,bobotkt,ketpg,ketkt,ketsk from tbconfig1 ");
		$bobot=($ki=="Pengetahuan"?$bobotpg:($ki=="Keterampilan"?$bobotkt:($ki=="Sikap Sosial dan Spiritual"?$bobotsk:"")));
		$sket=($ki=="Pengetahuan"?$ketpg:($ki=="Keterampilan"?$ketkt:($ki=="Sikap Sosial dan Spiritual"?$ketsk:"")));
		$bn=explode("#",$bobot."####");
	} elseif ($ambilbobot=='1') {
		//jika bobot angka 1
		$bn=explode("#","1#1#1#1#1");//rata2 tanpa bobot
	}elseif ($ambilbobot=='kd') {
		//jika bobot dari kd
		extractRecord("select bn1,bn2,bn3,bn4 from kompetensi where kode='$kode_kompetensi'");
		$bn=array($bn1,$bn2,$bn3,$bn4);
	}
	$jlhbobot=0;foreach($bn as $bb) {$jlhbobot+=$bb*1;}
	//$aketbobot=explode("#",$sket);

		
	if ($media=='print') {
		extractRecord("select matapelajaran.nama as mp,kompetensi.kd from kompetensi 
		left join matapelajaran on kompetensi.kode_matapelajaran=matapelajaran.kode 
		where kompetensi.kode='$kode_kompetensi'");
		$t.="<div class=page >";
		$t.="
		<div class=judul2 align=center>DAFTAR NILAI KOMPETENSI DASAR</div>
		<br>
		<table width=100%>
		<tr><td width=120>Mata Pelajaran </td><td width=200>: $mp</td></tr>
		<tr><td width=100>Kelas/Semester </td><td>: $kelas/$semester</td></tr>
		<tr>
		<td>Kompetensi Inti </td><td>: $ki</td>
		</tr>
		<tr>
		<td>Kompetensi Dasar </td><td >: $kd</td>
		</tr>
		</table>
		<br>
		";
		$t.="<table width=100% border=1 class=tbcetakbergaris >";
		$t.="<tr>";
		$rsp=" rowspan=2 ";
		$rsp="";
		$t.="<td class=tdjudul $rsp align=center >No</td>";
		$t.="<td class=tdjudul $rsp align=center >NIS</td><td class=tdjudul $rsp >Nama</td>";
			
		$chx="";
		for ($n=1;$n<=3;$n++) {
			$t.="<td class=tdjudul style='width:70px' align=center >N"."$n</td>";	
		}
		$t.="<td class=tdjudul $rsp align=center >NA KD</td>";	
		$t.="</tr>";
		
		$hq=mysql_query($sqTabel);
		$br=0;
		$rdis="";
		while ($r=mysql_fetch_array($hq)) {
			$idt="rec".$br;
			$troe=($br%2==0?"troddform2":"trevenform2");
			$t.="<tr id="."$idt class=$troe >";
			$t.="<td align=center>".($br+1)."</td>";
			$t.="<td align=center >$r[nis]</td><td>$r[nama]</td>";
		
			for ($n=1;$n<=3;$n++) {
				eval("$"."vv=$"."r['n".$n."'];$"."bnx=$"."bn".$n.";");
				$t.="<td align=center >$vv</td>";
			}
			$t.="<td align=center  >$r[nilai]</td>";
			$t.="</tr>";

			$br++;
		}
		$jlhsiswa=$br;
		
		$t.="</table>";

		$t.="</div>";
		echo $t;
		exit;
	}
	$t.="<div id=ts"."$idForm ></div><form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	
	//legen
	$spasi="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	if ($jinput!='perkd') {
		if ($ki=='Pengetahuan') {
			$kket="Keterangan : $spasi N1 : Penugasan $spasi N2 : Ulangan Harian $spasi N3 : UTS/UAS";
			} else {
			
			$kket="Keterangan :	$spasi N1 :Praktek $spasi N2 :Proyek $spasi N3 :Produk		";
		}

		$t.="
		<div style='background:#C9EDED;padding:5px'>
		$kket
		</div>	<br>
		";		
	}
	
	$t.="
	<table width=100%>";
	$t.="<tr>";
	$rsp=" rowspan=2 ";
	$rsp="";
	$t.="<td class=tdjudul $rsp >No</td>";
	$t.="<td class=tdjudul $rsp >NIS</td><td class=tdjudul $rsp >Nama</td>";

	$chx="";
	for ($n=1;$n<=3;$n++) {
		$nx=$n-1;
		$vbn=($bn[$nx]>0?"checked":"");
		$chx="<input type=checkbox id=chbn"."$n name=chbn"."$n onclick='cekDisableNilai($n);' $vbn style='display:none'> ";
		
		$ocf="onchange='hitungNA()' onkeyup='hitungNA()' onfocus=\"cekFocus(this.id,'N')\" onblur=\"cekBlur(this.id,'N')\" onchange=\"hitungNA()\" ";
		$chx.="<br>
		<div style='background:#C9EDED;padding:3px'>
		<span style='color:#074B84;'>BOBOT</span>
		<br>
		<input type=text id=bn"."$n name=bn"."$n  value='$bn[$nx]' size=2 $ocf style='text-align:center' >
		</div>
		";
		$t.="<td class=tdjudul style='width:70px'>N"."$n $chx </td>";	
	}
	$t.="<td class=tdjudul $rsp >NA KD</td>";	
	$t.="</tr>";
	/*
	$t.="<tr>";
	
	$ocf="onchange=hitungNA() onfocus=\"cekFocus(this.id,'N')\" onblur=\"cekBlur(this.id,'N')\" onchange=\"hitungNA()\" ";

	$t.="<td class=tdjudul ><input type=hidden id=bn1 name=bn1  value='$bn[0]' size=4 $ocf></td>";	
	$t.="<td class=tdjudul ><input type=hidden id=bn2 name=bn2  value='$bn[1]' size=4 $ocf></td>";	
	$t.="<td class=tdjudul ><input type=hidden id=bn3 name=bn3  value='$bn[2]' size=4 $ocf></td>";	
	$t.="<td class=tdjudul style='display:none'><input type=text id=bn4 name=bn4  value='$bn[3]' size=4 ></td>";	
//	$t.="<td class=tdjudul ><input type=text id=bn4 name=bn4  value='$bn[3]'></td>";	
	$t.="</tr>";
*/

	//$rdis=($ki=="Sikap Sosial dan Spiritual"?"readonly='true' title='Untuk mengisi nilai sikap dan spiritual, gunakan menu Input Nilai Sikap Dan Spiritual'  ":"");
	$hq=mysql_query($sqTabel);
	$br=0;
	$rdis="";
	while ($r=mysql_fetch_array($hq)) {
		$idt="rec".$br;
		$troe=($br%2==0?"troddform2":"trevenform2");
		$t.="<tr id="."$idt class=$troe >";
		$t.="<td align=center>".($br+1)."</td>";
		$ocf="onchange=hitungNA($br) onfocus=\"cekFocus(this.id,'N')\" onblur=\"cekBlur(this.id,'N')\" onchange=\"hitungNA($br)\" ";
		$t.="<td align=center>$r[nis]<input type=hidden name=nis[$br] value='$r[nis]'></td><td>$r[nama]</td>";
	
		$ocf="onchange=hitungNA($br) onfocus=\"cekFocus(this.id,'N')\" onblur=\"cekBlur(this.id,'N')\" onchange=\"hitungNA($br)\" ";
		for ($n=1;$n<=3;$n++) {
			eval("$"."vv=$"."r['n".$n."'];$"."bnx=$"."bn".$n.";");
			$disp=($bnx==0?"style='display:none'":"");
			$t.="<td align=center ><input type=text size=4 name=n".$n."[$br] id=n".$n."[$br] value='$vv' $ocf $disp style='text-align:center' > </td>";
		}
		$t.="<td align=center  >
		<input type=hidden size=4 name=nilai[$br] id=nilai[$br] value='$r[nilai]' style='text-align:center' >
		<input type=text size=4 name=nilaix[$br] id=nilaix[$br] value='$r[nilai]' style='text-align:center' disabled >
		</td>";
		$t.="</tr>";
		$br++;
	}
	$jlhsiswa=$br;
	
	$t.="</table>";
	if ($media=='print') {
		$t.="</div >";//akhir page
	} else {
		$addurl="op=showdata&media=print&useJS=2&kode_kompetensi=$kode_kompetensi&kode_kelas=$kode_kelas&ki=".urlencode($ki)."&semester=$semester&kode_matapelajaran=$kode_matapelajaran";
	
		$t.="<div align=center style='margin-top:15px'>
		<div id=tcetak></div>
		 
		<input type=button class='btn btn-success btn-sm' value='Cetak Form'  onclick=\"window.open('inputnilai.php?jf=form&$addurl','_blank');\" target=_blank > 
		<input type=button class='btn btn-success btn-sm' value='Cetak Nilai' onclick=\"window.open('inputnilai.php?jf=nilai&$addurl','_blank');\" > 
		<input type=submit class='btn btn-primary btn-sm' value='Simpan'></center>
		";
		$t.="<input type=hidden name=jlhsiswa id=jlhsiswa value='$jlhsiswa'>";
		 
		$t.="<input type=hidden name=kode_kelas value='$kode_kelas'>";
		$t.="<input type=hidden name=kode_kompetensi value='$kode_kompetensi'>";
		$t.="<input type=hidden name=ki value='$ki'>";
		$t.="</form>";
	}
	echo $t;
	exit;

}

?>

<div class=titlepage >Input Nilai Siswa</div> 
<table>

<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td><div id=tkelas_<?=$rnd?> ><?=um412_isiCombo5('select * from kelas order by tingkat,nama','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiComboNilai('kelas','',$rnd)");?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><div id=tsemester_<?=$rnd?> ><? //=um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih-',$semester,'');?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelompok</td>
	<td><div id=tjenisMP_<?=$rnd?> ><?=um412_isiCombo5("$sJenisKdMP",'jenisMP','kode','nama','-Pilih-',$jenisMP,"gantiComboNilai('jenisMP','',$rnd)");?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Mata Pelajaran</td>
	<td><div id=tmp_<?=$rnd?> ><? //=um412_isiCombo5('select kode,nama from matapelajaran','kode_matapelajaran','kode','nama','-Pilih-',$kode_matapelajaran,"filterTabelInput('$hal')");?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kompetensi Inti</td>
	<td><div id=tki_<?=$rnd?> ><?=um412_isiCombo5("$sJenisKI",'ki','','','-Pilih-',$ki,"gantiComboNilai('ki','',$rnd)");?></div></td> 
</tr>

<tr class=troddform2 $sty style='<? echo ($jPenilaian=='perkd'?'':'display:none'); ?>' >
	<td class=tdcaption >Kompetensi Dasar</td>
	<td>
	<?php if ($jinput!='perkd') 
	{ 
	?>
	<div id=tkompetensi_<?=$rnd?> >-</div></td> 
	<?php } else echo "<div id=tkompetensi_$rnd style='display:nonex'>-</div>" ?>
</tr>
</table>
</form>
<br>
<div id=tnilai_<?=$rnd?> ></div>
