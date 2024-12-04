<?php
include $inc_path."mnu_atas.php";
$_SESSION['session_time'] = time(); //got the login time for user in second

$hm="class=fly href=#  ";
$sm="class=fly href=#  ";
$ge="class=fly ";
$rf="return false;";
//<em></em>echo "userID ".$userType."<br><br>";
if ($userType!='siswa') {
	$beranda="inputsekolah.php?det=sekolah&op=its&id=1&showresult=1";
	
	if ($userType=='Admin') {
		$cmenu  = array( 
			array('BERANDA',$beranda),
			array('MASTER',$beranda),
			/*array('DATA SISWA',$beranda),*/
			array('MATA PELAJARAN',$beranda),
			array('PENILAIAN',$beranda),
			array('CETAK',$beranda),
			array('SETTING',$beranda),
		//	array('Logout','adm1/usr-login.php?op=logouet')
		);  
	} elseif ($userType=='Kaprog') {
		$cmenu  = array( 
			array('BERANDA',$beranda),
			/*array('MASTER',$beranda),
			array('DATA SISWA',$beranda),*/
			array('MATA PELAJARAN',$beranda),
			array('PENILAIAN',$beranda),
			array('CETAK',$beranda),
			//array('SETTING',$beranda),
		//	array('Logout','adm1/usr-login.php?op=logout')
		);  
	} else {
			$cmenu  = array( 
			array('BERANDA',$beranda),
			//array('MASTER',$beranda),
			/*array('DATA SISWA',$beranda),*/
			array('MATA PELAJARAN',$beranda),
			array('PENILAIAN',$beranda),
			array('CETAK',$beranda),
			//array('SETTING',$beranda),
		//	array('Logout','adm1/usr-login.php?op=logout')
		);
		}
	$submenu=array ("","","","","","","","","","","","");
	$i=0;
	
	if ($userType=='Admin') {
		$i++;
		$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=paket+keahlian');$rf\" >Daftar Kompetensi Keahlian</a></li>";
		$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=guru');$rf\" >Daftar Guru</a></li>";
		$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=kelas');$rf\" >Daftar Kelas</a></li>";
		$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=ekskul');$rf\" >Daftar Ekstrakurikuler</a></li>";
	}
	/*
	$i++;
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=siswa&tbop=spindah');$rf\" >Catatan Pindah Sekolah</a></li>";
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=siswa&tbop=cpres');$rf\" >Catatan Prestasi</a></li>";
	*/
	
	$i++;
	if (strstr('Admin,Kaprog',$userType)!='') $submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=mata+pelajaran');$rf\" >Daftar Mata Pelajaran</a></li>";
	if  ($jPenilaian=='perkd') {
		$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=kompetensi');$rf\" >Daftar Kompetensi Dasar</a></li>";
	}else {
		$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=kompetensi');$rf\" >Daftar Deskripsi Mata Pelajaran</a></li>";
	}
	
	//if (strstr('Admin,Kaprog',$userType)!='')  $submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=map',0,'bukaMapMP()');$rf\" >Pemetaan Mata Pelajaran</a></li>";
	if (strstr('Admin,Kaprog',$userType)!='')  $submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputmap.php?op=form',0,'bukaMapMP()');$rf\" >Pemetaan Mata Pelajaran</a></li>";
	
	$i++;
	//$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputnilaisikap.php?');$rf\" >Sikap dan Spiritual</a></li>"; 
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputnilai.php?');$rf\" >Input Nilai</a></li>"; 
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=siswa&tbop=cas');$rf\" >Catatan Wali Kelas</a></li>";
	$i++;
	//$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputraport2.php?jcetak=KHS&jcetak2=siswa');$rf\" >KHS Persiswa</a></li>";  
	//$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputraport2.php?jcetak=KHS&jcetak2=kelas');$rf\" >KHS Perkelas</a></li>";  
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputledger.php?jki=1');$rf\" >Ledger Pengetahuan</a></li>"; 
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputledger.php?jki=2');$rf\" >Ledger Keterampilan</a></li>"; 
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputledger.php?jki=3');$rf\" >Ledger Sikap dan Spiritual</a></li>"; 
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputledger.php?jki=4');$rf\" >Ledger Total</a></li>";   
//	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputraport2.php?jcetak=Raport&jcetak2=siswa&jmid=1');$rf\" >Raport Tengah Semester</a></li>"; 
    $submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputraport2.php?jcetak=Raport&jcetak2=siswa');$rf\" >Raport Akhir Semester</a></li>"; 
    $submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputraport2.php?jcetak=CHB&jcetak2=siswa');$rf\" >CHB Siswa</a></li>"; 
//	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputraport2.php?jcetak=Raport&jcetak2=kelas');$rf\" >Raport Perkelas</a></li>"; 
	//$submenu[$i].="<li><hr></li>"; 
	//$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputcetak.php?jcetak=spindah');$rf\" >Pindah Sekolah</a></li>"; 
	//$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputcetak.php?jcetak=cpres');$rf\" >Catatan Prestasi</a></li>"; 
	$submenu[$i].="<li><hr></li>"; 
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','inputcetak.php?jcetak=dsiswa');$rf\" >Data Siswa</a></li>"; 
	
	 
	$i++;
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=userku');$rf\" >User & Password</a></li>";  
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','index2.php?det=backuprestore');$rf\" >Backup & Restore </a></li>";  
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','input.php?det=pembobotan');$rf\" >Pembobotan Nilai </a></li>";  
}else {
	//user siswa
	$beranda="inputsekolah.php?det=sekolah&op=its&id=1&showresult=1";
	$m_ckhs="index2.php?det=cetakkhs";
	
	$cmenu  = array( 
	//	array('BERANDA',$beranda),
	//	array('INPUT NILAI',$beranda),
	//	array('CETAK KHS',$m_ckhs) 
		//array('Logout','adm1/usr-login.php?op=logout')
	);  
	$submenu=array ("","","","","","","","","","","","");
	$i=0;
	
	$i++;
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','index2.php?det=inputnilaisikap&jpx=2');$rf\" >Penilaian Antar Peserta Didik</a></li>"	;
	$submenu[$i].="<li><a $hm onclick=\"bukaAjax('content0','index2.php?det=inputnilaisikap&jpx=1');$rf\" >Penilaian Diri</a></li>";
	
	}

echo "<ul id=navatas >";
$jmenu=count($cmenu);
for ($ci=0;$ci<$jmenu;$ci++) {
	echo "<li class=top >";
	$ba="bukaAjax('content0','".$cmenu[$ci][1]."'); ";
//	echo "<a href='".$cmenu[$ci][1]."' onclick=\"$ba\" class='top_link' >";
	echo "<a href='#' onclick=\"$ba return false;\" class='top_link' >";
	echo "<span > ".$cmenu[$ci][0]." </span> </a>";
 	if ($submenu[$ci]!='')	echo " <ul class=sub >\n $submenu[$ci] \n </ul> ";
	echo "</li>";
}
	echo "<li class=top > <a href='index2.php?det=login&op=logout'   class='top_link' ><span > LOGOUT</span> </a></li>";
	
	echo "<li class=top ><a href='#' class='top_link' onclick='return false;'><span>User Aktif : 
$userID &nbsp;&nbsp;&nbsp;&nbsp;Tahun : $thpl4</span></a></li>";
echo "</ul>";


?>