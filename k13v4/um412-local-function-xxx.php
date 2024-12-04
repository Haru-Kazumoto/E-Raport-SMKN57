<?php

function extractMapMP_old($sy,$xAgama='Islam') {	
	global $sKDMP,$aKDMP,$sGuruMP,$aGuruMP;	
	$sq="select matapelajaran from map_matapelajaran_kelas where $sy";
	$csq=cariField($sq);
	$ampguru=explode('#',$csq);
	$sKDMP="";
	$sGuruMP="";
	//contoh:#A01|#A02|Dra. Ida Mariyati|Dra. Dawimah|Rohmat Muktini, S.Pd#A03|Suwarti Azwar, S.Pd#A07|#A08|Drs. Jasmadi#A09|#A10|Dra. Alimar
	$arr=array();
	foreach($ampguru as $smpg) {
		
		if ($sKDMP!='') {
			$sKDMP.="#";
			$sGuruMP.="#";
		}
		$pos=strpos($smpg,"|");
		//echo substr($smpg,0,$pos)." ";
		$xkdmp=substr($smpg,0,$pos);
		$sKDMP.=$xkdmp;
		$xg=substr($smpg,$pos+1,strlen($smpg)-$pos+1);
		$sGuruMP.=$xg;
		
		$arr[]=[
			'kdmp'=>$xkdmp,
			'guru'=>$xg,
		];
	
		/*
			substr($smpg,$pos+1,strlen($smpg)-$pos+1)
			$pot1=substr($matapelajaran."#",$pos+strlen($cari1)-1,$pj);
					$pos2=strpos($pot1,'#');
					//echo "$pot1 <br> ";
					if ($pos2>0) {
						$pot2=substr($pot1,0,strpos($pot1,'#'));
					
		*/
		
	}
	$aKDMP=explode("#",$sKDMP);	
	$aGuruMP=explode("#",$sGuruMP);
	
	//echo showTA($newArr);exit;
	//echo $sKDMP."<br>";
	//echo $sGuruMP;
	/*
	echo "Awal";
	$i=0;foreach($aKDMP as $kdmp){
		echo "| ".$kdmp.">".$aGuruMP[$i];$i++;
	}
	echo "<br>Agama $xAgama<br>";
	*/
	//menghapus yang agama tidak sama
	$aKdMpAgm=explode(",","test,A01,A02,A03,A04,A05,A06");
	
	if ($xAgama=='Islam') 
		$kdAgm="A01";
	elseif ($xAgama=='Katholik') 
		$kdAgm="A02";	
	elseif ($xAgama=='Kristen') 
		$kdAgm="A03";	
	elseif ($xAgama=='Hindu') 
		$kdAgm="A04";	
	elseif ($xAgama=='Budha') 
		$kdAgm="A05";	
	else  
		$kdAgm="A06";	
	
	
	$i=0;
	foreach($aKdMpAgm as $kd){
		if ($kd!=$kdAgm) {
			$no=array_search($kd, $aKDMP);
			$nob=$no.".";//tambah titik agar ketahuan ketemu atau tidak
			//echo "$nob";
			if (strlen($nob)>1) {
				unset($aKDMP[$no]);
				unset($aGuruMP[$no]);
			} 
		}
		$i++;
	}
	
	$sKDMP=implode("#",$aKDMP); //set ulang
	$sGuruMP=implode("#",$aGuruMP);//set ulang
	$aGuruMP=explode("#",$sGuruMP);//dibalik ulang
	
	//diurutkan
	
	$i=0;
	foreach ($aKDMP as $xkdmp) {
		$arr[]=[$xkdmp,$xg];
		$i++;
	}
	
	//sorting
	$newArr=array_multisort($arr[0], SORT_ASC, SORT_STRING);
	//$ar[1], SORT_NUMERIC, SORT_DESC
	$aKdMP=array_column($newArr,"kdmp");
	$aGuruMP=array_column($newArr,"kdmp");
	$sKdMP=implode("#",$aKDMP);
	$sGuruMP=implode("#",$aGuruMP);

}


function extractMapMP($sy,$xAgama='Islam') {	
	global $sKDMP,$aKDMP,$sGuruMP,$aGuruMP;	
	$sq="select matapelajaran from map_matapelajaran_kelas where $sy";
	$csq=cariField($sq);
	$ampguru=explode('#',$csq);
	$sKDMP="";
	$sGuruMP="";
	//contoh:#A01|#A02|Dra. Ida Mariyati|Dra. Dawimah|Rohmat Muktini, S.Pd#A03|Suwarti Azwar, S.Pd#A07|#A08|Drs. Jasmadi#A09|#A10|Dra. Alimar
	$arr=array();
	foreach($ampguru as $smpg) {
		
		if ($sKDMP!='') {
			$sKDMP.="#";
			$sGuruMP.="#";
		}
		$pos=strpos($smpg,"|");
		//echo substr($smpg,0,$pos)." ";
		$xkdmp=substr($smpg,0,$pos);
		$sKDMP.=$xkdmp;
		$xg=substr($smpg,$pos+1,strlen($smpg)-$pos+1);
		$sGuruMP.=$xg;
		
		$arr[]=[
			'kdmp'=>$xkdmp,
			'guru'=>$xg,
		];
	
		/*
			substr($smpg,$pos+1,strlen($smpg)-$pos+1)
			$pot1=substr($matapelajaran."#",$pos+strlen($cari1)-1,$pj);
					$pos2=strpos($pot1,'#');
					//echo "$pot1 <br> ";
					if ($pos2>0) {
						$pot2=substr($pot1,0,strpos($pot1,'#'));
					
		*/
		
	}
	$aKDMP=explode("#",$sKDMP);	
	$aGuruMP=explode("#",$sGuruMP);
	
	//echo showTA($newArr);exit;
	//echo $sKDMP."<br>";
	//echo $sGuruMP;
	/*
	echo "Awal";
	$i=0;foreach($aKDMP as $kdmp){
		echo "| ".$kdmp.">".$aGuruMP[$i];$i++;
	}
	echo "<br>Agama $xAgama<br>";
	*/
	//menghapus yang agama tidak sama
	$aKdMpAgm=explode(",","test,A01,A02,A03,A04,A05,A06");
	
	if ($xAgama=='Islam') 
		$kdAgm="A01";
	elseif ($xAgama=='Katholik') 
		$kdAgm="A02";	
	elseif ($xAgama=='Kristen') 
		$kdAgm="A03";	
	elseif ($xAgama=='Hindu') 
		$kdAgm="A04";	
	elseif ($xAgama=='Budha') 
		$kdAgm="A05";	
	else  
		$kdAgm="A06";	
	
	
	$i=0;
	foreach($aKdMpAgm as $kd){
		if ($kd!=$kdAgm) {
			$no=array_search($kd, $aKDMP);
			$nob=$no.".";//tambah titik agar ketahuan ketemu atau tidak
			//echo "$nob";
			if (strlen($nob)>1) {
				unset($aKDMP[$no]);
				unset($aGuruMP[$no]);
			} 
		}
		$i++;
	}
	
	$sKDMP=implode("#",$aKDMP); //set ulang
	$sGuruMP=implode("#",$aGuruMP);//set ulang
	$aGuruMP=explode("#",$sGuruMP);//dibalik ulang
	
	//diurutkan
	
	$i=0;
	foreach ($aKDMP as $xkdmp) {
		$arr[]=[$xkdmp,$xg];
		$i++;
	}
	
	//sorting
	$newArr=array_multisort($arr[0], SORT_ASC, SORT_STRING);
	//$ar[1], SORT_NUMERIC, SORT_DESC
	$aKdMP=array_column($newArr,"kdmp");
	$aGuruMP=array_column($newArr,"kdmp");
	$sKdMP=implode("#",$aKDMP);
	$sGuruMP=implode("#",$aGuruMP);
	
}
//contoh extractMapMP("kode_kelas='1' and semester='1'",'xagama);
	
function konversiNilai($nilai,$jtujuan="predikat"){
	//terbaru
	global $kna,$knb,$knc,$knd;
	extractRecord("select kna,knb,knc,knd from tbconfig1 ");
	$hasil=($nilai>=$kna?"A":($nilai>=$knb?"B":($nilai>=$knc?"C":"D")));
	return $hasil;
}
 
function namaKelas($kode_kelas){
	return cariField("select nama from kelas where kode='$kode_kelas'");
} 
	
function cekCatatanMapellama($nis,$semester) {
		//proses mencari catataan antar mapel
		global $jPenilaianSikapDet;
		$aNilSik=getArray(" select snilai from nilai_sikap where nis='$nis' and semester='$semester' ");
		$aJPSikap=explode(",",$jPenilaianSikapDet);
		$snilai="";foreach ($aNilSik as $sn) { $snilai.="|".$sn;} //gabungkan semua nilai 1 smt
		echo " select snilai from nilai_sikap where nis='$nis' and semester='$semester' ";

		$s=0;
		$aNSikap=array();
		$aJNSikap=array();//jumlah nilai
		$aNNSikap=array();//count nilai
		$result="";
		foreach($aJPSikap as $jps) {
			$asxn=explode("#".$jps."#",$snilai);
			$jsxn=count($asxn);
			$i=0;
			echo $snilai;
			exit;
			//$nSikapDet=array();
			$xnilai="";
			$jumlah=0;
			foreach ($asxn as $asx) {
				if ($i>0) {
					$nilai=substr($asx,0,1);
					echo "<br>Nilai :".$nilai;
					$jumlah+=$nilai;
					//array_push($nSikapDet,$nilai*1);
					$xnilai.=",$nilai";
				}
				$i++;
			}
			$i--;//mengurangi 1, karena pertama tidak dipakai
			$rata=round(($jumlah/(max(1,$i)*25)),2);
			$xjsp=str_replace("Jujur","Kejujuran",$jps);
			$xjsp=str_replace("Disiplin","Kedisiplinan",$xjsp);
			$xjsp=str_replace("Santun","Sopan Santun",$xjsp);
			$kosi=konversiNilai($rata,'sikap');
			$result.=($result==''?"":", ")."<b>$kosi</b> dalam aspek ".$xjsp;
			//echo "<br>Nilai $ajps :N: $i R:$rata  A:$xnilai";
			
			array_push($aNSikap,$nSikapDet);
			array_push($aNNSikap,$nSikapDet);
			$s++;	
		}//ajps
		//echo $result;
		return $result;
	
}	
function cekCatatanMapel($nis,$semester,$showResultInTabel=0) {
		//proses mencari catataan antar mapel
		global $jPenilaianSikapDet;
		$sq=" select snilai from nilai_sikap where nis='$nis' and semester='$semester' and kode_matapelajaran<>''";
		$aNilSik=getArray($sq);
		//echo $sq;
		//mencari jumlah kompetensi
		//rata2 jika menggunakan semua param
		$kode_kelas=cariField("select kode_kelas from siswa where nis='$nis'");
		
		
		$smp=cariField("select matapelajaran from map_matapelajaran_kelas where semester='$semester' and kode_kelas='$kode_kelas' ");
		$amp=explode("#",$smp);
		$jjj=count($amp);
		/*
		$j=0;
		foreach($amp as $kdmp) { 
			$j+=(cariField("select count(kode) from kompetensi where semester='$semester' and kode like '$kdmp"."S%' ")*1);
		}
		$jjj=$j;
		 */
		 
		 
		$aJPSikap=explode(",",$jPenilaianSikapDet); //jenis2 peniilaian sikap		
		$snilai="";
		foreach ($aNilSik as $sn) { $snilai.="|".$sn;} //gabungkan semua nilai dalam 1 smt
		//contoh: 119#Spiritual#1|120#Spiritual#2|121#Spiritual#2|122#Spiritual#2|123#Spiritual#1|124#Spiritual#2|125#Spiritual#1|...
		$s=0;
		$aNSikap=array();
		$aJNSikap=array();//jumlah nilai
		$aNNSikap=array();//count nilai
		$result="";
		$srtab="";
		foreach($aJPSikap as $jps) { 
			$asxn=explode("#".$jps."#",$snilai);//memecah nilai sesuai dengan jenis
			$jsxn=count($asxn);
			$i=0;
			$xnilai="";
			$jumlah=0;
			$srtab.="<tr><td>$jps</td>";
			foreach ($asxn as $asx) {
				if ($i>0) {
					$nilai=substr($asx,0,1);
					$jumlah+=$nilai;
					$xnilai.=",$nilai";
					$srtab.=" <td>$nilai</td>";
				}
				$i++;
			}
			$i--;//mengurangi 1, karena pertama tidak dipakai
			
			//rata2 jika menggunakan real
			$rata=round(($jumlah/max(1,$i)*25),2);
			//echo $jumlah." $snilai ".$jjj;
			//exit;
				
			//rata2 jika menggunakan jumlah semua param x jkompetensi x jmp
			$jpr=(cariField("select count(id) from sikap_paramnilai	 where kelompok='$jps' ")*1);//jparam per kodekompetensi			
			$rata=round(($jumlah/max(1,$jjj*$jpr)*25),2);
				
			
			$xjsp=str_replace("Jujur","Kejujuran",$jps);
			$xjsp=str_replace("Disiplin","Kedisiplinan",$xjsp);
			$xjsp=str_replace("Santun","Sopan Santun",$xjsp);
			$kosi=konversiNilai($rata,'sikap');
			$kosix=($kosi=='SB'?'Sangat Baik':($kosi=='B'?'Baik':($kosi=='C'?'Cukup':($kosi=='K'?'Kurang':'?'))));
			
			$prd="<b>$kosix</b> dalam aspek ".$xjsp;
			$result.=($result==''?"":", ").$prd;
			$srtab.="<td>j $jumlah, rata: $rata, select count(id) from sikap_paramnilai	 where kelompok='$jps'</td><td>$prd</td>";
			//$result.=($result==''?"":", ")."$i:$rata<b>".konversiNilai($rata,'sikap')."</b> dalam aspek ".$xjsp;
			//echo "<br>Nilai $ajps $jps :N: $i R:$rata  A:$xnilai h: ".konversiNilai($rata,'sikap');		
			array_push($aNSikap,$nSikapDet);
			array_push($aNNSikap,$nSikapDet);
			$s++;	
			$srtab.="</tr>";
		}//ajps
		
		$srtab="<table border=1>$srtab</table>";

		
		//echo $result;
		//exit;
		if ($showResultInTabel==1) echo $srtab;
		return $result;	
		
}	
?>