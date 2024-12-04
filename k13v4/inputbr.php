<?php
$useJS=2;
include_once "conf.php"; 

	$awdb=substr($dbku4,0,-8);
	$thdb=(substr($dbku4,-4)*1)-1;
	$dbnext=$awdb.($thdb+1).($thdb+2);
	//	echo "awdb:$awdb > thdb $thdb > dbnext $dbnext ";
if ($op!='') {
	cekVar("jns");
	/*
	define("DB_USER", $usr);
	define("DB_PASSWORD", $pss);
	define("DB_NAME", $dbku4);
	*/
	if ($jns=='cdb') { 
		$t="";
		//copy db ke db tahun berikutnya
		//tables1:semua tabel beserta datanya,tables2:hanya struktur tabelnya
		$tables1="ekskul,guru,kelas,kompetensi,kompetensi_keahlian,map_matapelajaran_kelas,matapelajaran,sekolah,tbconfig1,tbuser";
		$tables2="siswa,nilai_kompetensi_siswa,nilai_sikap,tbkonversi,tblog,tbnews,cas"; 
		//cek db, jika g ada, buat dbbaru
		$cekdb=mysql_query("use $dbnext ") ;
		if (!$cekdb) {
			mysql_query("create database $dbnext ");
			$t.="<br>Creating database $dbnext ..<br>";
			mysql_query("use $dbku4 ") ;
			$atb=explode(",",$tables1);
			foreach ($atb as $tb) {
				$t.="Creating and copying table $tb <br>";
				mysql_query("drop TABLE if exists $dbnext.$tb ");
				mysql_query("CREATE TABLE $dbnext.$tb like $tb");
				mysql_query("insert into $dbnext.$tb select * from $tb");	
			}
			
			$atb=explode(",",$tables2);
			foreach ($atb as $tb) {
				$t.="Creating structure table $tb <br>";
				mysql_query("drop TABLE if exists $dbnext.$tb ");
				mysql_query("CREATE TABLE $dbnext.$tb like $tb");
				
			}
			//mengkopy data siswa untuk kelas 10 dan 11
			$t.="Copying table siswa ...<br>";
			mysql_query("insert into $dbnext.siswa select s.* from siswa s left join kelas k on s.kode_kelas=k.kode where k.tingkat<=11");
			//menghilangkan kode_kelas
			mysql_query("update $dbnext.siswa set kode_kelas=''");
			
			$t.="Copying data Successfully Executed.";
			
			
		} else {
			$t.=um412_falr("Database $dbnext sudah ada, copy data tidak berhasil....");
		}
		$cekdb=mysql_query("use $dbku4 ") ;
		
		
		echo $t;
	//	define("TABLES", "ekskul,guru,kelas,kompetensi,kompetensi_keahlian,map_matapelajaran_kelas,matapelajaran,sekolah,tbconfig1,tbuser");
		exit;
		
	} else {
		$tables="*";
		
		//define("TABLES", "*");
	}
	//define("DB_HOST", 'localhost');
	$outputdir='backup';

	if ($op=='backup') {			
		include  $um_path."backupmydb.php";
		exit;
	} elseif ($op=='restore') {
		$varnf="nff";
		$_REQUEST['op']=$op="upload";
		include  $um_path."restoreDB.php";
		exit;
	}
	else if ($op=='kosongkandata') {
	/*
	truncate table ekskul;
	truncate table guru;
	truncate table kelas;
	truncate table kompetensi;
	truncate table kompetensi_keahlian;
	truncate table log;
	truncate table map_matapelajaran_kelas;
	truncate table matapelajaran;
	truncate table nilai_kompetensi_siswa;
	truncate table nilai_sikap;
	truncate table sekolah;
	truncate table siswa;
	*/
	}
} else {
		$idForm="fbr01";
		$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','','',false);return false;\" ";
		/*onchange=\"$"."('#fbr01')submit();*/
		
	
		echo "
		<div style='padding:5px'>
		<div class=titlepage >Backup dan Restore Database</div><br />

		File hasil backup akan secara otomatis tersimpan di root direktori C atau D pada folder AppServ/www/smk/nilai/backup/.<br /><br />
		
		<input class='btn btn-primary btn-sm' type=button onclick=\"bukaAjax('tbr','index2.php?det=backuprestore&op=backup')\" value='Backup Database'> 
		<input class='btn btn-success btn-sm' type=button onclick=\"bukaAjax('tbr','index2.php?det=backuprestore&op=backup&jns=cdb')\" value='Copy Data Ke Tahun Berikutnya'> 
		<input class='btn btn-danger btn-sm' type=button onclick=\"$"."('#tfinput').css('display','inline');\" value='Restore Database' >
		<br>
		<div id=tfinput style='display:none; '>
			<form id=fbr01 style='margin-top:20px;' action='index2.php?det=backuprestore&op=restore' enctype='multipart/form-data'
			$asf method=post target=_blank>
			<input class='btn btn-warning btn-sm' type=file name=nff id=nff style='width:300px'   >
			<div style='position:relative;margin:-31px 0px 0px 120px;text-align:right;width:250px '  >
				<input class='btn btn-info btn-sm' type=submit value='Restore' >
			</div>
			</form>
		</div>
		</div>
		
		<div id=tbr></div><div id=tsfbr01></div>
		";
}
 
?>