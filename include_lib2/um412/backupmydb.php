<?php
//echo "Toroot:$toroot<br>";
 

/*
if (strtolower($userType)!='admin') {
	echo "<br>unauthorized...";
	exit;
}
*/
$useJS=2;
if (!isset($silent)) $silent=false;
if (!isset($showBackupLog)) $showBackupLog=true;
if (!isset($dataOnly)) $dataOnly='false';//only data, not drop and create  table
if ($dataOnly=='') $dataOnly='false';
/*
$defTbBackup="tbsiswa,tbujian,tbsiswaujian,tbsiswaujiand,tbkelas,tbmapel,tbpaketsoal"; 

*/

/*
$db =$_SESSION["db"]; 
$usr=$_SESSION["usr"]; 
$pss=$_SESSION["pss"]; 
*/
//echo "db $db usr $usr ps $pss";
if (!isset($tables)) {
	if (!isset($_REQUEST['tables'])) {
		$tables="*";
		if (isset($defTbBackup)) $tables=$defTbBackup;		
	} else {
		$tables=$_REQUEST['tables'];
	}
}

if (!isset($tablealias)) {
	if (!isset($_REQUEST['tablealias'])) {
		$tablealias=$tables;
		if (isset($defTbBackup)) $tables=$defTbBackup;		
	} else {
		$tablealias=$_REQUEST['tablealias'];
	}
}
//echo "<br>awal : $tables | $tablealias > <br>";
//echo "$tables $tablealias > <br>";

include "backupDB.php";
	
ini_set('max_execution_time', 0);
$mem=ini_get('memory_limit');
$mem=str_replace("M","",$mem)*1;
$jdb=1;
cekVar("nmdb,det");
//echo "nmdb= $nmdb -> $db";
cekVar("startdb");
$startdb*=1;

if ($nmdb=="all") {
	$sdb="";
	$sq="show databases";
	$hq=mysql_query2($sq) or die(mysql_error());
	//echo "db ---><br>";
	$idb=0;
	while ($r=mysql_fetch_array($hq)) {
		if ($idb>=$startdb) $sdb.=($sdb==""?'':",").$r[0];
		$idb++;
	}
	
	 
} else {
	
	if (isset($dbname))
		$sdb=$dbname;
	elseif (isset($mydb))
		$sdb=$mydb;
	elseif(isset($db4))
		$sdb=$db4;
	else 
		$sdb=$db;

}
echo "sdb $sdb ";
$adb=explode(",",$sdb);
$jdb=count($adb);

$t="";
$outputdir=$toroot.'content/backup/';
buatFolder($outputdir);
if (!isset($tablealias))$tablealias="";

define("DB_USER", $usr);
define("DB_PASSWORD", $pss);
define("TABLES", $tables);
define("TABLEALIAS", $tablealias);

define("DB_HOST", 'localhost');
define('OUTPUT_DIR',$outputdir);

for ($idb=0;$idb<$jdb;$idb++) {
	$nomordb=$idb+$startdb;
	$db=$adb[$idb];
	
	ini_set('memory_limit', '200M');
	//define("DB_NAME", $db);
	
	if ($tables=='*')
		$nmfile='db-backup-'.$db.'-'.date("Ymd-His", time()).'.sql';
	else
		$nmfile='db-backup-'.$db.'-tables_'.$tables.'-'.date("Ymd-His", time()).'.sql';
	
	$nmfile=str_replace(",","",$nmfile);
	if (isset($defNfBackup)) $nmfile=$defNfBackup;
	
	$nfbackup=$outputdir.$nmfile;
	if (!isset($linknfbackup)) $linknfbackup=$outputdir.$nmfile;
	$se="
	$"."backupDatabase"."$idb = new Backup_Database(DB_HOST, DB_USER, DB_PASSWORD, '$db','$nfbackup');
	
	mysql_query2('use $db;');
	$"."isi= $"."backupDatabase"."$idb ->backupTables('$tables', OUTPUT_DIR,$dataOnly,'$tablealias');
	$"."sqlResult=$"."backupDatabase"."$idb ->getSQL();
	";
	eval($se);
	
	
	$t.=" 	<h3>Backup Database.</h3> ";
	
	if($showBackupLog) {
		$t.="
			<br>Database Name : $db
			<br>Backup result : 
		<pre style='max-height:200px ;overflow:auto'>$isi
		</pre><br>";
		$t.="Nama File Backup (SQL Format) : <a href='$linknfbackup?a=1'>$linknfbackup</a><br>";
		$t.= "Nama File Backup (Zip Format) : <a href='$linknfbackup.zip?a=1'>$linknfbackup.zip</a><br>";
	}
	
	
	$t.= "<br><a href='$linknfbackup.zip?a=1' class='btn btn-primary btn-mini btn-sm' >download </a>";
}

if ($det=='delmydb'){
	$sq="truncate table ".$_REQUEST['tables'];
	$h=mysql_query2($sq);
	if ($h) 
		echo "<br>Penghapusan tabel  $_REQUEST[tables] berhasil....";
	else 	
		echo "Proses penghapusan $_REQUEST[tables] tidak berhasil....<br>$sq ";
}

if (!$silent) {
	echo "
	&nbsp;
	<div style='	margin:20px'>
	$t
	</div>";
}
//	exit;	
?>