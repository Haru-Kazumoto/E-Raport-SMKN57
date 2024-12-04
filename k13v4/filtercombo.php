<?php
//if (!isset($dsresultcombo)) {
	$useJS=2;
	include_once "conf.php";	
	cekVar("semester,jenisMP,kode_matapelajaran,nis,jkom,kode_kompetensi,combo,kode_kelas,jcetak");
//}

//$ki=($det=="ss"?"Sikap & Spiritual":($det=="p"?"Pengetahuan":"Keterampilan"));
$sJenisKI="Pengetahuan,Keterampilan";//,Sikap Sosial dan Spiritual
$idForm="fnilai_".rand(1231,2317);
$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\""; 


if ($combo=='semester') {
	cekVar("func");
	$kelas=cariField("select nama from kelas where kode='$kode_kelas'");
	$sm=(strpos("-".$kelas,"XII")>0?"5,6":(strpos("-".$kelas,"XI")>0?"3,4":"1,2"));
	
	$funcx="gantiComboNilai('semester','$jkom',$rnd)";
	if ($func=="-") $funcx="";
	$t=um412_isiCombo5("$sm",'semester','','','-Pilih-',$semester,$funcx); 
} elseif ($combo=='jenisMP') {
	$t=um412_isiCombo5("$sJenisKdMP",'jenisMP','kode','nama','-Pilih-',$jenisMP,"gantiComboNilai('jenisMP','$jkom',$rnd)");
} elseif ($combo=='siswa') {
	$t=um412_isiCombo5("select nis,nama from siswa where kode_kelas='$kode_kelas' order by nama",'nis','nis','nama','-Pilih-',$nis,"gantiComboRaport('siswa',$rnd)");
} elseif ($combo=='siswa2') {
	$t=um412_isiCombo5("select nis,nama from siswa where kode_kelas='$kode_kelas' order by nama",'nis','nis','nama','-Pilih-',$nis,"gantiComboNilai('nilai','$jkom',$rnd)");
} elseif ($combo=='mp') {
	 
	if ($usemap==1) {
		
		$smp=cariField("Select matapelajaran from map_matapelajaran_kelas where semester='$semester' and kode_kelas='$kode_kelas'  ");		
		if ($smp=='') {
			$sykode="1=2";
		} else { 
			$amp=explode("#",$smp);
			$sykode="";
			foreach($amp as $kdmp) {
				$sykode.=($sykode==""?"":" or ")."kode like '$kdmp%' ";
				}
		}
		$sq=$combogsmp.($gsmp==''?' where ':' and ')." kode like '$jenisMP%' order by nama ";
		$t=um412_isiCombo5($sq,'kode_matapelajaran','kode','nama','-Pilih-',$kode_matapelajaran,"gantiComboNilai('ki','$jkom',$rnd)");	
	}
	else
	$t=um412_isiCombo5($combogsmp.($gsmp==''?'':' and ')." kode like '$jenisMP%' ".($gsmp==''?'':' and $gsmp')." order by nama",'kode_matapelajaran','kode','nama','-Pilih-',$kode_matapelajaran,"gantiComboNilai('ki','$jkom',$rnd)");
} elseif ($combo=='ki') {
	$t=um412_isiCombo5("$sJenisKI",'ki','','','-Pilih-',$ki,"gantiComboNilai('kikuk','$jkom',$rnd)");
} elseif ($combo=='kompetensi') {
	$sq="select kode,if(length(kd)>98,concat(substr(kd,1,93),'.....'),kd) as kd from kompetensi where kode_matapelajaran='$kode_matapelajaran' and ki='$ki' and semester='$semester'";
	$t=um412_isiCombo5($sq,'kode_kompetensi','kode','kd','-Pilih-',$kode_kompetensi,"gantiComboNilai('nilai','$jkom',$rnd)");
}
if (!isset($dsresultcombo)) 
	echo $t;
else
	$result=$t;
?>