<?php
$modeInfo=1;
//include $tppath."info-user.php";
$modeInfo=2;

$mainmenu="mmenu1";
$accd="accordion-toggle";
$clps="collapse";

//title,det,icon,active
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
"Daftar Siswa|cls|siswa|icn"
);

$i++;
$aSubMenuLevel1[$i]=array(
"Mata Pelajaran|cls|mata pelajaran|icn",
"Kompetensi Dasar|cls|kompetensi|icn",
"Pemetaan Mapel|cls|map|icn"
);

$i++;
$aSubMenuLevel1[$i]=array(
"Input Nilai|cls|inputnilai|icn",
"Deskripsi Sikap|cls|inputsikap|icn",
"Catatan Wali Kelas|cls|siswa&tbop=cas|icn"
);
$i++;
$aSubMenuLevel1[$i]=array(
"Rapor Tengah Semester|cls|raport&jcetak=Raport&jmid=1|icn",
"Rapor Akhir Semester|cls|raport&jcetak=Raport&jmid=0|icn|awalEdit($rnd)",
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
"Backup & Restore|cls|backuprestore|icn" 
);

$i++;$aSubMenuLevel1[$i]=array();
$i++;$aSubMenuLevel1[$i]=array();
$i++;$aSubMenuLevel1[$i]=array();
//<li class=""><a href="button.html"><i class="icon-angle-right"></i> Buttons </a></li>

//sub menu
include "mnu-kiri-end.php";
?>


        