<?php

$mainmenu="mmenu1";
$accd="accordion-toggle";
$clps="collapse";

if (usertype("admin")) {
	$aMenuLevel1=array(
	/*	"judul|href  |data-parent|collapse|class|target|icon|pull|actv", */
		"Administrasi|#|$mainmenu|$clps|$accd|-|icon-book|pull|",
		"Mata Pelajaran|#|$mainmenu|$clps|$accd|-|icon-tasks|pull|",
		"Penilaian|#|$mainmenu|$clps|$accd|-|icon-calendar|pull|",
		"Laporan|#|$mainmenu|$clps|$accd|-|icon-bar-chart|pull|",
		"System|#|$mainmenu|$clps|$accd|-|icon-cog|pull|"	
	);
	$i=0;
	$aSubMenuLevel1[$i]=array(
	"Data Sekolah|cls|sekolah|icn",
	"Kompetensi Keahlian|cls|paket keahlian|icn",
	"Daftar Kelas|cls|kelas|icn",
	"Daftar Guru|cls|guru|icn",
	"Daftar Siswa|cls|siswa|icn",
	"Daftar Kegiatan|cls|kegiatan|icn",
	"Daftar Kelompok Kegiatan|cls|gkegiatan|icn",
	"Daftar Sub. Kegiatan|cls|subkegiatan|icn"
	);

	$i++;
	$aSubMenuLevel1[$i]=array(
	"Mata Pelajaran|cls|mata pelajaran|icn",
	"KKM & Deskripsi Mapel|cls|kkm|icn",
	"Pemetaan Mapel|cls|map|icn"
	);
	//"Kompetensi Dasar|cls|kompetensi|icn",

	$i++;
	$aSubMenuLevel1[$i]=array(
	"Input Nilai|cls|inputnilai|icn",
	"Deskripsi Sikap|cls|inputsikap|icn",
	"Catatan Wali Kelas|cls|siswa&tbop=cas|icn",
	"Nilai Kegiatan|cls|siswa&tbop=rkegiatan|icn"
	);
	$i++;
	$aSubMenuLevel1[$i]=array(
	"Rapor Tengah Semester|cls|raport&jcetak=Raport&jmid=1|icn",
	"Rapor Akhir Semester|cls|raport&jcetak=Raport&jmid=0|icn|awalEdit($rnd)",
	"Rapor Kegiatan|cls|raport&jcetak=raportkeg|icn|awalEdit($rnd)",
	"Nilai Per KD|cls|raport&jcetak=chb|icn",
	"Ledger Pengetahuan|cls|inputledger&jki=1|icn",
	"Ledger Keterampilan|cls|inputledger&jki=2|icn",
	//"Ledger N. Sikap|cls|href|icn",
	"Ledger Total|cls|inputledger&jki=4|icn",
	"Daftar KB/KKM|cls|inputkkm&tbop=kkm|icn"
	//"Siswa Tidak Kompeten|cls|href|icn"
	//"Data Siswa|cls|href|icn"
	);
	$i++;
	$aSubMenuLevel1[$i]=array(
	"Config|cls|config|icn",
	"User|cls|userku|icn",
	//"Pembobotan|cls|href|icn",
	"Informasi|cls|news|icn",
	"Log system|cls|log|icn",
	"Backup & Restore|cls|backuprestore|icn", 
	"Upload Siswa & Update Kelas|cls|updkelas|icn" 
	);

	$i++;$aSubMenuLevel1[$i]=array();
	$i++;$aSubMenuLevel1[$i]=array();
	$i++;$aSubMenuLevel1[$i]=array();
	//<li class=""><a href="button.html"><i class="icon-angle-right"></i> Buttons </a></li>
	//sub menu
} elseif (usertype("guru,kaprog")) {
	extractRecord("select alloweditrkeg  from guru where kode='$vidusr'" );
	
	$aMenuLevel1=array(
	"Administrasi|#|$mainmenu|$clps|$accd|-|icon-book|pull|",
	"Mata Pelajaran|#|$mainmenu|$clps|$accd|-|icon-tasks|pull|",
	"Penilaian|#|$mainmenu|$clps|$accd|-|icon-calendar|pull|",
	"Laporan|#|$mainmenu|$clps|$accd|-|icon-bar-chart|pull|" 
	);
	$i=0;
	$aSubMenuLevel1[$i]=array(
	"Daftar Kelas|cls|kelas|icn",
	"Daftar Siswa|cls|siswa|icn"
	);
	if ($alloweditrkeg) {
		array_push($aSubMenuLevel1[$i],	"Daftar Kegiatan|cls|kegiatan|icn");
		array_push($aSubMenuLevel1[$i],"Daftar Kelompok Kegiatan|cls|gkegiatan|icn");
		array_push($aSubMenuLevel1[$i],"Daftar Sub. Kegiatan|cls|subkegiatan|icn");
	}
	$i++;
	$aSubMenuLevel1[$i]=array(
	"Mata Pelajaran|cls|mata pelajaran|icn",
	"KKM & Deskripsi Mapel|cls|kkm|icn"
	);
	
	
	if ($userType=='kaprog') {
		array_push($aSubMenuLevel1[$i],"Pemetaan Mapel|cls|map|icn");
	}

	$i++;
	$aSubMenuLevel1[$i]=array(
	"Input Nilai|cls|inputnilai|icn",
	"Deskripsi Sikap|cls|inputsikap|icn",
	"Catatan Wali Kelas|cls|siswa&tbop=cas|icn"
	);
	
	if ($alloweditrkeg) {
		array_push($aSubMenuLevel1[$i],"Input Nilai Kegiatan|cls|siswa&tbop=rkegiatan|icn");
	}

	
	$i++;
	$aSubMenuLevel1[$i]=array(
	"Rapor Tengah Semester|cls|raport&jcetak=Raport&jmid=1|icn",
	"Rapor Akhir Semester|cls|raport&jcetak=Raport&jmid=0|icn|awalEdit($rnd)",
	"Rapor Kegiatan|cls|raport&jcetak=raportkeg|icn|awalEdit($rnd)",
	"Nilai Per KD|cls|raport&jcetak=chb|icn",
	"Ledger Pengetahuan|cls|inputledger&jki=1|icn",
	"Ledger Keterampilan|cls|inputledger&jki=2|icn",
	//"Ledger N. Sikap|cls|href|icn",
	"Ledger Total|cls|inputledger&jki=4|icn",
	"Daftar KB/KKM|cls|inputkkm&tbop=kkm|icn"
	//"Siswa Tidak Kompeten|cls|href|icn"
	//"Data Siswa|cls|href|icn"
	);

	$i++;$aSubMenuLevel1[$i]=array();
	$i++;$aSubMenuLevel1[$i]=array();
	$i++;$aSubMenuLevel1[$i]=array();

} elseif (usertype("siswa")) {
	extractRecord("select allowopenreport1 as aor1,allowopenreport2 as aor2 from tbconfig1" );
	//title,det,icon,active
	$aMenuLevel1=array(
	/*	"judul|href  |data-parent|collapse|class|target|icon|pull|actv", */
		"Laporan|#|$mainmenu|$clps|$accd|-|icon-bar-chart|pull|" 
	);

	$asm=array();
	$asm[]="Nilai Per KD|cls|raport&jcetak=chb|icn";

	if ($aor1=='1') {
		$asm[]="Rapor Tengah Semester|cls|raport&jcetak=Raport&jmid=1|icn";
	}
	if ($aor2=='1') {
		$asm[]="Rapor Akhir Semester|cls|raport&jcetak=Raport&jmid=0|icn|awalEdit($rnd)";
	}
	$asm[]="Rapor Kegiatan|cls|raport&jcetak=raportkeg|icn|awalEdit($rnd)";
	$i=0;
	$aSubMenuLevel1[$i]=$asm;

	$i++;$aSubMenuLevel1[$i]=array();
	$i++;$aSubMenuLevel1[$i]=array();
	$i++;$aSubMenuLevel1[$i]=array();

}
?>