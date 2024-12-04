<?php
$useJS=2;
include_once "conf.php";
cekVar("kode_matapelajaran,semester,ki,kode_kelas,op,jenisMP,cetak");

//cek kode kompetensi,jika belum ada insert
function cekKK($kode_matapelajaran,$ki,$semester){	
	$kd="";//judul kd	
	global $jkdp,$jkdk;
	//echo "chekking kode kompetensi";
	if (($kode_matapelajaran=="")||($ki=="")||($semester=="")) {
		echo "chekking $kode_matapelajaran,$ki,$semester";
		exit;
	}
	if ($ki!='') {
		$sqk="select jp$semester as jkdp,jk$semester as jkdk from matapelajaran where kode='$kode_matapelajaran' ";
		extractrecord($sqk);
		$jn=$jkd=($ki=='Pengetahuan'?$jkdp:$jkdk);
	} else {
		echo "masukkan ki";
		exit;
	}
	
	$ttb="";
	for ($n=1;$n<=$jn;$n++) { 
		$ttb.="<td class=tdjudul style='width:70px'>KD$n</td>";	
		$dg="00000000".$n;
		$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
		$sqc="select kode from kompetensi where kode='$kk'";
		$cek=carifield($sqc);
		if ($cek=='') {
			$sqs="insert into kompetensi (kode,kode_matapelajaran,kd,semester,ki)
			values ('$kk','$kode_matapelajaran','$kd','$semester','$ki');
			";
			//echo "<br>$sqs";
			mysql_query($sqs);				
		}
	}

}
function getNilaiKompetensi() {
	global $xnis,$kode_matapelajaran,$ki,$n,$kk,$semester;
	$dg="00000000".$n;
	$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
	
	$nilai="";
	$sqcek="select nilai from nilai_kompetensi_siswa where nis='$xnis' and kode_kompetensi='$kk' ";
	extractRecord($sqcek);
	//echo "<br>$sqcek";
	return $nilai;	
}


function setNilaiKompetensi($opx='set') {
	global $xnis,$kode_matapelajaran,$ki,$n,$kk,$nilai,$jkd,$semester;
	
	if ($opx=='del') {
		//menghapus kompetensi yang nggak digunakan
		for ($n=$jkd+1;$n<=$jkd+4;$n++) {
			$dg="00000000".$n;
			$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
			
			/*
			$sqin="delete from nilai_kompetensi_siswa where kode_kompetensi='$kk' ";
			mysql_query($sqin);			
			*/
			$sqin="update nilai_kompetensi_siswa set kode_kompetensi='x-$kk' where kode_kompetensi='$kk' ";
			mysql_query($sqin);			
			
			$sqin="delete from kompetensi where kode='$kk'";
			mysql_query($sqin);			
		}
	
	}  else {	
		$dg="00000000".$n;
		$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
		$syin="nis='$xnis' and kode_kompetensi='$kk'  ";
		$sqcek="select count(nilai)  from nilai_kompetensi_siswa where $syin";
		$c=carifield($sqcek);
		$sqin="";
		if ($c==0) {
			$sqin="insert into nilai_kompetensi_siswa(nis,kode_kompetensi, nilai) values('$xnis','$kk' ,'$nilai')";
		}
		else {
			$sqin="update nilai_kompetensi_siswa set nilai='$nilai' where nis='$xnis' and kode_kompetensi='$kk'  ";
		}
		//echo "<br>".$sqin;
		mysql_query($sqin);
	}
}


if ($op!='') {
	$jn=0;
	if ($jinput!="perkd")
		$jn=$jkd=3;
	else {
		if ($ki!='') {
			$sqk="select jp$semester as jkdp,jk$semester as jkdk from matapelajaran where kode='$kode_matapelajaran' ";
			//echo "$sqk";
			extractrecord($sqk);
			$jn=$jkd=($ki=='Pengetahuan'?$jkdp:$jkdk);
		}
		 
	}
	//if ($jn==0) $jn=$jkd=0;
	//echo "jn $jn";
}

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
	//update boboter
	if ($jinput!='perkd') {
		$squ="update kompetensi set bn1='$bn1',bn2='$bn2',bn3='$bn3' where kode='$kode_kompetensi'";
		mysql_query($squ);
		
		$br=0;
		foreach ($_REQUEST["nis"] as $n1x) {
			$nis=$_REQUEST["nis"][$br];
			$n1=$_REQUEST["n1"][$br];
			$n2=$_REQUEST["n2"][$br];
			$n3=$_REQUEST["n3"][$br];
			//$n4=$_REQUEST["n4"][$br];
			$nilai=$_REQUEST["nilai"][$br];
			
			$sqc="select id from nilai_kompetensi_siswa where nis='$nis' 
			and kode_kompetensi='$kode_kompetensi'  ";
			//echo $sqc."<br>";
			$idc=cariField($sqc);
			if ($idc=='') {
				$squ="insert into nilai_kompetensi_siswa(nis,kode_kompetensi,semester,nilai,n1,n2,n3) values('$nis','$kode_kompetensi','$semester','$nilai','$n1','$n2','$3')";
			} else  {
				$squ="update nilai_kompetensi_siswa set  n1='$n1',n2='$n2',n3='$n3',nilai='$nilai' where id='$idc'";
			}
			mysql_query($squ);
			//echo $squ."<br>";
		$br++;
		}
		
	}
	else {
		
		//echo "jkd $jkd";
	 
		$br=0;
		foreach ($_REQUEST["nis"] as $nis) {
			for ($n=1;$n<=$jkd;$n++) {
				$dg="00000000".$n;
				$xnis=$nis;
				$nilai=$_REQUEST["kd".$n][$br];
				setNilaiKompetensi();	
			}
			//echo $squ."<br>";
			$br++;
		}
			
		
		//menghapus yg nggak digunakan
		setNilaiKompetensi('del');		
	}
	echo "
	<div id=tpes$rnd style=comment1>".um412_falr("Data berhasil disimpan","success")."
	</div>
	".fbe("
	$('#fn$currnd').show();
	setTimeout(\"$('#tpes$rnd').hide(1000);\",2000);
	")."
	";
	$op="showdata"; //langsung menampilkan data
	
	exit;
}

if ($op=='import') {
	cekVar("op2");
	if ($op2=='') {
		$rndx=genRnd();
		$addurl0="newrnd=$rndx&currnd=$rnd&semester=$semester&kode_kompetensi=$kode_kompetensi&kode_kelas=$kode_kelas&ki=".urlencode($ki)."&semester=$semester&kode_matapelajaran=$kode_matapelajaran";
		$addurl="op=import&media=print&useJS=2&$addurl0";
		$idForm="fu$rnd";
		$nfAct="inputnilai.php?op2=unggah&$addurl";
		$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','','awalEdit($rndx)');return false;\""; 
		echo "
		<div style='margin:20px'>
		<div id=ts$idForm></div>
		<form id=$idForm $asf action='$nfAct' method=post  enctype='multipart/form-data' target=_blank>  
		<table align=center ><tr>
		<td>
		<a href='inputnilai.php?op2=unduh&jf=form&$addurl0&op=import&useJS=2' target=_blank style='color:#fff'><input  class='btn btn-warning btn-sm' type=button value='Unduh format'></a>
		</td><td>
		<input type=file class='btn btn-warning btn-sm'   
			onchange=\"$('#$idForm').submit();\" name=upload >  
		</td></tr></table>
		<!--input type=hidden name=backurl value='inputnilai.php?op=showdata&$addurl0'-->
		</form>
		</div>
			";
		exit;
	} elseif ($op2=='unduh') {
		
		$op="showdata";
		//$jf="form";
		$media="csv";
		$sqTabel="Select siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama  from siswa   where kode_kelas='$kode_kelas' order by siswa.nama"; 		
		
		$hq=mysql_query($sqTabel);
		$br=0;
		$rdis="";
		$csv="";
		$csv.="NIS,NAMA";
		for ($n=1;$n<=$jn;$n++){$csv.=",KD$n";}
		
		//$csv.=",NA";
		$csv.="\n";
		
		while ($r=mysql_fetch_array($hq)) {
			$idt="rec".$br;
			
			
			$csv.="$r[nis],$r[nama]";
			$ra=0;
			for ($n=1;$n<=$jn;$n++) {
				 
				if ($jinput!="perkd") {
					eval("$"."vv=$"."r['n".$n."'];$"."bnx=$"."bn".$n.";");
				} else {
					
					if ($jf!='form') {
						//mencari nilai
						$nis=$r['nis'];
						//$vv=getNilaiKompetensi();
						$dg="00000000".$n;
						//$kk=$kode_matapelajaran.substr($ki,0,1).substr($dg,-4);
						$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
						$nilai="";
						$sqcek="select nilai from nilai_kompetensi_siswa where nis='$r[nis]' and kode_kompetensi='$kk' ";
						//echo "<br>$sqlcek";						
						extractRecord($sqcek);
						$ra+=$nilai*1;
					}
				}
				$vv=($jf=='form'?'':$nilai);
				$csv.=",$vv";
			}
			
			if ($jf=='form') 
				$ra='';
			else {
				if ($jinput=='perkd'){
					$ra=round($ra/$jn,2);
				} else {
					$ra=$r["nilai"];
				}
			}
			//$csv.=",$ra"; //NA TIDAK PERLU DI UNDUH/UNGGAH
			$csv.="\n";

			
			$br++;
		}
		$jlhsiswa=$br;
		
		
		$nf="format_nilai_$mp"."_$ki"."_sm_$semester"."_".date("Y-m-d").".csv";
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"$nf\"");
			header("Content-Transfer-Encoding: binary");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $csv;
			
		exit;
		
	} elseif ($op2=='unggah') {
		$fileName=$_FILES['upload']['tmp_name'];
		/*
		$csvData = file_get_contents($fileName);
		$lines = explode(PHP_EOL, $csvData);
		$array = array();
		foreach ($lines as $line) {
			$array[] = str_getcsv($line);
		}
		print_r($array);
		*/
		$t="<html><body><table border=1>\n\n";
		$f = fopen($fileName, "r");
		$br=0;
		$tb=array();
		$akunci=array();
		while (($line = fgetcsv($f)) !== false) {
			$row=array();
			$t.="<tr>";
			if ($br==0) {
				foreach ($line as $cell) {
					$akunci[]=strtolower(htmlspecialchars($cell));
					$t.="<td>" . htmlspecialchars($cell) . "</td>";
				}
				
			} else {
				$k=0;
				foreach ($line as $cell) {
					$kunci=$akunci[$k];
					$row[$kunci]=htmlspecialchars($cell);
					$t.="<td>" . htmlspecialchars($cell) . "</td>";
					$k++;
				}
				$tb[]=$row;
			}
			$t.="</tr>\n";
			$br++;
		}
		$t.= "\n</table></body></html>";
		
		fclose($f);
		
		//memproses data
		//cek  kolom
		 
		//No	NIS	Nama	KD1	KD2	KD3	KD4	KD5	KD6	NA
		//echo "jkd $jkd<br>";
		
		foreach ($tb as $row) {
		//	echo "<br>";//.$row['nis'];
			$xnis=$row['nis'];
			$na=0;
			for ($n=1;$n<=$jkd;$n++) {
				//getNilaiKompetensi();
				$nilai=$row["kd".$n];
				$na+=$nilai;
				setNilaiKompetensi();
			}
			$ra=$na/$jkd;
		}
		$pes="Unggah data selesai";
	//, silahkan klik <a href=# onclick=\"bukaAjax('tnilai_$rnd','$backurl')\" >refresh</a>	
		echo "
	<div id=tpes$rnd >".um412_falr("$pes","success")."
	</div>
	".fbe("
	//$('#fn$currnd').show();
	setTimeout(\"gantiComboNilai('ki','',$currnd);\",1000);
	")."";
	
		//echo $t;
		exit;
	}
}  
if ($op=='showdata') {
	cekVar("jf,t,id,ki");
	//echo "$kode_matapelajaran,$ki,$semester,";
	cekKK($kode_matapelajaran,$ki,$semester);

	if ($ki=='') exit;
	if ($jn==0) {
		echo "Jumlah KD belum ada, ";
		echo "<a href=# onclick=bukaAjax('content0','input.php?det=mata+pelajaran&op=itb&id=$kode_matapelajaran');>Klik di sini</a> untuk memasukkan
		";
		exit;
	}
	//if (($kode_kompetensi=='' ) &&  ($jinput!='perkd')){
	if ($kode_kompetensi=='' ) {
		echo "Pilih kompetensi dasar terlebih dahulu..";
		exit;
	}
	
	$newr=genRnd();
	$idForm="fn$rnd";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','','awalEdit($newr)');return false;\""; 
	$nfAction="inputnilai.php?op=simpan&semester=$semester&kode_kompetensi=$kode_kompetensi&newrnd=$newr&currnd=$rnd";
	//deteksi semua nis, jika belum ada, insert
	$sqd="select nis from siswa where kode_kelas='$kode_kelas'";
	$hqd=mysql_query($sqd);
	if ($jinput!='perkd') {
		while ($rd=mysql_fetch_array($hqd)){
			$nis=cariField("select nis from nilai_kompetensi_siswa where nis='$rd[nis]'  and kode_kompetensi='$kode_kompetensi'  ");
			if ($nis=='') mysql_query("insert into nilai_kompetensi_siswa(nis,kode_kompetensi ) values('$rd[nis]','$kode_kompetensi' ) ");
		}
		$sqTabel="Select kode_kompetensi,siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama,n1,n2,n3,n4,nilai  
		from nilai_kompetensi_siswa inner join siswa on nilai_kompetensi_siswa.nis=siswa.nis 
		where siswa.kode_kelas='$kode_kelas' and nilai_kompetensi_siswa.kode_kompetensi='$kode_kompetensi' and nilai_kompetensi_siswa.semester='$semester' 
		order by siswa.nama";
	} else {
		$sqTabel="Select siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama  from siswa   where kode_kelas='$kode_kelas' order by siswa.nama"; 		
	}

	if ($jf=='form') {
		/*
		$sqTabel="Select kode_kompetensi,siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama,'' as n1,'' as n2,'' as n3,'' as n4,'' as nilai  
		from nilai_kompetensi_siswa inner join siswa on nilai_kompetensi_siswa.nis=siswa.nis 
		where siswa.kode_kelas='$kode_kelas' 
		order by siswa.nama";
		*/
		$sqTabel="Select siswa.nis,if(siswa.gender='0','L','P') as gender,siswa.nama,'' as n1,'' as n2,'' as n3,'' as n4,'' as nilai  
		from  siswa  
		where siswa.kode_kelas='$kode_kelas' 
		order by siswa.nama";
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
	
	$ps=strtolower($ki[0].$semester);
	extractRecord("select 
	matapelajaran.kode,
	matapelajaran.nama as mp,
	matapelajaran.kb$ps as kkm,
	matapelajaran.j$ps as jlhkd,kompetensi.kd from kompetensi 
	left join matapelajaran on kompetensi.kode_matapelajaran=matapelajaran.kode 
	where kompetensi.kode='$kode_kompetensi'" );
	$jn=$jlhkd*1;
	if ($jn<=0) {
		echo "Jumlah KD (Kode kompetensi $kode_kompetensi) belum diisi";
		exit;
	}
	//echo "Jumlah KD untuk mapel $mp semester $semester ki $ki :$jlhkd ($jn)<br>";
	
	$head="";	
	if (($media=='print')	|| ($media=='csv')) {
		$adds="";
		if ($jinput!='perkd') {
			$adds="<tr>
			<td>Kompetensi Dasar </td><td >: $kd</td>
			</tr>";
		}
		extractRecord("select matapelajaran.nama as mp,kompetensi.kd from kompetensi 
		left join matapelajaran on kompetensi.kode_matapelajaran=matapelajaran.kode 
		where kompetensi.kode='$kode_kompetensi'");
		
		$head.="
		<div class=judul2 align=center>DAFTAR NILAI KOMPETENSI DASAR</div>
		<br>
		<table width=100%>
		<tr><td width=120>Mata Pelajaran </td><td width=200>: $mp</td></tr>
		<tr><td width=100>Kelas/Semester </td><td>: $kelas/$semester</td></tr>
		<tr>
		<td>Kompetensi Inti </td><td>: $ki</td>
		</tr>
		$adds
		</table>
		<br>
		";
		
		if ($media=='print') {
			$t.="<div class=page >";
			$t.=$head;
		}
		$t.="<table width=100% border=1 class=tbcetakbergaris >";
		$t.="<tr>";
		$rsp=" rowspan=2 ";
		$rsp="";
		$t.="<td class=tdjudul $rsp align=center >No</td>";
		$t.="<td class=tdjudul $rsp align=center >NIS</td><td class=tdjudul $rsp >Nama</td>";
			
		$chx="";
		$w=($jn>=6?40:50)."px";
		for ($n=1;$n<=$jn;$n++) {
			$t.="<td class=tdjudul style='width:$w' align=center >".($jinput=='perkd'?"KD":"N")."$n</td>";	
		}
		
		$t.="<td class=tdjudul $rsp align=center style='width:$w'>NA".($jinput=='perkd'?'':' KD')."</td>";	
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
			$ra=0;
			for ($n=1;$n<=$jn;$n++) {
				 
				if ($jinput!="perkd") {
					eval("$"."vv=$"."r['n".$n."'];$"."bnx=$"."bn".$n.";");
				} else {
					
					if ($jf!='form') {
						//mencari nilai
						$nis=$r['nis'];
						//$vv=getNilaiKompetensi();
						$dg="00000000".$n;
						//$kk=$kode_matapelajaran.substr($ki,0,1).substr($dg,-4);
						$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
						$nilai="";
						$sqcek="select nilai from nilai_kompetensi_siswa where nis='$r[nis]' and kode_kompetensi='$kk'  ";
						// echo "<br>".$sqcek;
						extractRecord($sqcek);
						$ra+=$nilai*1;
					}
				}
				$vv=($jf=='form'?'':$nilai);
				$t.="<td align=center >$vv</td>";
			}
			
			if ($jf=='form') 
				$xra='';
			else {
				if ($jinput=='perkd'){
					$xra=round($ra/$jn,2);
					
				} else {
					$xra=$r["nilai"];
				}
			}
			$t.="<td align=center  >$xra</td>";
			$t.="</tr>";

			$br++;
		}
		$jlhsiswa=$br;
		
		$t.="</table>";

		if ($media=='print') {
			$t.="</div>";
			echo $t;
		} else {
			$nf="format_nilai_$mp"."_$ki"."_sm_$semester"."_".date("Y-m-d").".csv";
			header("Content-Type: application/force-download");
			header("Content-Type: application/octet-stream");
			header("Content-Type: application/download");
			header("Content-Disposition: attachment; filename=\"$nf\"");
			header("Content-Transfer-Encoding: binary");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $t;
			
		}
			
		exit;
	}
	$t.="<div id=ts"."$idForm ></div>
	<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	
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
		
		$ttb="";
		for ($n=1;$n<=$jn;$n++) {
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
			
			$ttb.="<td class=tdjudul style='width:70px'>N"."$n $chx </td>";	
		}
	} else {
		//mencari jumlah kd
		/*
		$sq="select kode from kompetensi where kode_matapelajaran='$kode_matapelajaran' and ki='$ki'  	 ";
		$skd=getstring($sq);
		$akd=explode(",",$skd);
		$jn=$jkd=count($akd);
		//echo "jkd:$jkd skd: $skd";
		*/
		cekKK($kode_matapelajaran,$ki,$semester,$jn);

		$ttb="";
		for ($n=1;$n<=$jn;$n++) { 
			$ttb.="<td class=tdjudul style='width:70px'>KD$n</td>";	
			//cek kd di tabel kd
			//kode,kode_matapelajaran,kd,semester,ki
			$dg="00000000".$n;
			//$kk=$kode_matapelajaran.substr($ki,0,1).substr($dg,-4);
			$kk=$kode_matapelajaran.substr($ki,0,1).$semester.substr($dg,-4);
			$sqc="select kode from kompetensi where kode='$kk'";
			$cek=carifield($sqc);
			
			if ($cek=='') {
				$sqs="insert into kompetensi (kode,kode_matapelajaran,kd,semester,ki)
				values ('$kk','$kode_matapelajaran','$kd','$semester','$ki');
				";
				mysql_query($sqs);				
			}
		}
	}
	$t.="
	<table width=100%>";
	$t.="<tr>";
	$rsp=" rowspan=2 ";
	$rsp="";
	$t.="<td class=tdjudul $rsp >No</td>";
	$t.="<td class=tdjudul $rsp >NIS</td><td class=tdjudul $rsp >Nama</td>";
	$t.=$ttb;
	if ($jinput!="perkd") {
		$t.="<td class=tdjudul $rsp >NA KD</td>";	
	}
	else	{
		$t.="
		<td class=tdjudul $rsp >NA 
		<input type=hidden id=jkd name=jkd value='$jkd'>
		<input type=hidden id=semesterx name=semester value='$semester'>
		<input type=hidden id=kode_matapelajaranx name=kode_matapelajaran value='$kode_matapelajaran'>
		</td>";	
	}
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
		$hna=($jinput=="perkd"?"hitungNA2($br)":"hitungNA($br)");
		$ocf="  onfocus=\"cekFocus(this.id,'N')\" onblur=\"cekBlur(this.id,'N')\" onchange=\"$hna\" ";
		
		$t.="<td align=center>$r[nis]<input type=hidden name=nis[$br] value='$r[nis]'></td><td>$r[nama]</td>";
		$nilai="";
		$ra=0;
		for ($n=1;$n<=$jn;$n++) {
			if ($jinput!='perkd') {
				eval("$"."vv=$"."r['n".$n."'];$"."bnx=$"."bn".$n.";");
				$disp=($bnx==0?"style='display:none'":"");
				$t.="<td align=center ><input type=text size=4 name=n".$n."[$br] id=n".$n."[$br] value='$vv' $ocf $disp style='text-align:center' > </td>";
			}
			else {
				//cari nilainya dulu
				$xnis=$r['nis'];
				getNilaiKompetensi();
			 
				$ra+=$nilai*1;
				$vv=$nilai;
				$disp="";
				$cls=($nilai==0?"class=input-blank":"class=input-noblank");
				$t.="<td align=center ><input type=text size=4 $cls name=kd".$n."[$br] id=kd".$n."[$br] value='$vv' $ocf $disp style='text-align:center' > </td>";
				
			}
		}
		
		if ($jinput!='perkd') {
			$ra=$r['nilai'];
		} else 
			$ra=round($ra/$jn,2);
	//if ($jinput!="perkd") {
		$t.="<td align=center  >
		<input type=hidden size=4 name=nilai[$br] id=nilai[$br] value='$ra' style='text-align:center' >
		<input type=text size=4 name=nilaix[$br] id=nilaix[$br] value='$ra' style='text-align:center' disabled >
		</td>";
		//}
		$t.="</tr>";
		$br++;
	}
	$jlhsiswa=$br;
	
	$t.="</table>";
	if ($media=='print') {
		$t.="</div >";//akhir page
	} else {
		$addurl0="newrnd=$rnd&semester=$semester&jkd=$jkd&kode_kompetensi=$kode_kompetensi&kode_kelas=$kode_kelas&ki=".urlencode($ki)."&semester=$semester&kode_matapelajaran=$kode_matapelajaran";
		$addurl="media=print&useJS=2&$addurl0";
	
		$t.="<input type=hidden name=jlhsiswa id=jlhsiswa value='$jlhsiswa'>"; 
		$t.="<input type=hidden name=kode_kelas value='$kode_kelas'>";
		$t.="<input type=hidden name=kode_kompetensi value='$kode_kompetensi'>";
		$t.="<input type=hidden name=ki value='$ki'>";
		$t.="
		
		
		<div align=center style='margin-top:15px'>
		<input type=button class='btn btn-warning btn-sm' value='Import'  
		onclick=\"bukaAjax('tcetak','inputnilai.php?op=import&$addurl0');\" > 
		<input type=button class='btn btn-info btn-sm' value='Export'  
		onclick=\"window.open('inputnilai.php?op=import&op2=unduh&jf=unduh&$addurl0');\" > 
		 
		<input type=button class='btn btn-success btn-sm' value='Cetak Form'  onclick=\"window.open('inputnilai.php?jf=form&op=showdata&$addurl','_blank');\" target=_blank > 
		<input type=button class='btn btn-success btn-sm' value='Cetak Nilai' onclick=\"window.open('inputnilai.php?jf=nilai&op=showdata&$addurl','_blank');\" > 
		<input type=submit class='btn btn-primary btn-sm' value='Simpan'></center>
		</div>
		
		
		</form>
		
		
		<div id=tcetak></div> 
		";
	
		
		
	}
	echo $t;
	exit;
}

?>
<div class=titlepage >Input Nilai Siswa</div> 
<table>

<tr class=troddform2 $sty >
	<td class=tdcaption width=140 >Kelas</td>
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
<?php if ($jinput!='perkd') { ?>
<tr class=troddform2 $sty style='<? echo ($jPenilaian=='perkd'?'':'display:none'); ?>' >
	<td class=tdcaption >Kompetensi Dasar</td>
	<td>
	<div id=tkompetensi_<?=$rnd?> >-</div></td> 
</tr>
<?php } else echo "<div id=tkompetensi_$rnd style='display:nonex'></div>" ?>
</table>
</form>
<br>
<div id=tnilai_<?=$rnd?> ></div>
