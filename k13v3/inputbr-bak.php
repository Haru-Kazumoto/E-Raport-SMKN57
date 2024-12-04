<?php
$useJS=2;
include_once "conf.php"; 

if ($op!='') {
	cekVar("jns");
	define("DB_USER", $usr);
	define("DB_PASSWORD", $pss);
	define("DB_NAME", $dbku);
	if ($jns=='cdb') 
		define("TABLES", "ekskul,guru,kelas,kompetensi,kompetensi_keahlian,map_matapelajaran_kelas,matapelajaran,sekolah,tbconfig1,tbuser");
	else 
		define("TABLES", "*");
	
	define("DB_HOST", 'localhost');
	define('OUTPUT_DIR','backup');

	if ($op=='backup') {			
		ini_set('max_execution_time', 0);
		$mem=ini_get('memory_limit');
		$mem=str_replace("M","",$mem)*1;
		ini_set('memory_limit', '200M');
	
		if (TABLES=='*')
			$nmfile='db-backup-'.DB_NAME.'-'.date("Ymd-His", time()).'.sql';
		else
			$nmfile='db-backup-'.DB_NAME.'-tables_'.$jns.'-'.date("Ymd-His", time()).'.sql';
		
		$nfbackup=$toroot.OUTPUT_DIR.'/'.$nmfile;
		$linknfbackup='backup/'.$nmfile;
		
		define('NFBACKUP',$nfbackup);
		
		include  $um_path."backupDB.php";
		$backupDatabase = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$status = $backupDatabase->backupTables(TABLES, OUTPUT_DIR) ? 'OK' : 'KO';
		echo "<br>Backup result : ".$status;
		echo "<br>Nama File Backup : <a href='$linknfbackup?a=1'  target=_blank  >$nfbackup</a>";
		exit;
	} elseif ($op=='restore') {
		//$nmFile="backup/".$nff;
		$tmpfile=$_FILES['nff']["tmp_name"];
		$fd=fopen($tmpfile,"r");
		$contents = fread ($fd, filesize ($tmpfile));
		fclose ($fd);
		
		//echo $contents;
		$br=1;
		$brbenar=$brsalah=0;
		$pes="";
		$aline=explode(";\n",$contents);
		foreach($aline as $line) {
			//echo $line."<br>";
			if(strlen($line)>5) {
				$m=mysql_query($line);
				if ($m) {
					$brbenar++;echo "";
				} else {
					$brsalah++;$pes.="$br:$line<br>";
				}
			}
			$br++;
		}
		
		echo "
		Restoring Data finished<br />
		Success :$brbenar<br />
		Error : $brsalah<br />
		<br />$pes
		";
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
			<input class='btn btn-warning btn-sm' type=file name=nff id=nff size=1  onchange=\"$"."('#fbr01')submit();\">
			</form>
		</div>
		</div>
		
		<div id=tbr></div><div id=tsfbr01></div>
		";
}
 
?>