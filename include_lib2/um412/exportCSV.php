<?php
$connectOnly=1;
include_once "conf.php";
$output = "";
//echo "$sqTabel\n";
if (!isset($sFieldCaption)) $sFieldCaption=$sField;
if (!isset($sFieldCSV)) {	
	$sFieldCSV=$sField;
	$sFieldCaptionCSV=$sFieldCaption;
}

if (!isset($sqFilterTable)) $sqFilterTable='';
//if (!isset($sqTabelCSV)) 

//jika diawali dengan xx, maka diganti menjadi '' as xx
$sf=str_replace('xx',"'' as xx",$sFieldCSV);
$sqTabelCSV="select $sf from $nmTabel ".$sqFilterTable;
//echo $nmTabel ;
//echo  $sqTabelCSV.">>";

if (!isset($sFieldCaptionCSV)) $sFieldCaptionCSV=$sFieldCSV;

$aFieldCaptionCSV=explode(",",$sFieldCaptionCSV);
$aFieldCSV=explode(",",$sFieldCSV);

if (!isset($formatonly)) $formatonly=1;
$hs = mysql_query2($sqTabelCSV);
//echo $sqlTabel;
$columns_total = count($aFieldCSV);//mysql_num_fields($sql);

//judul
$judul="";
 
for ($i = 0; $i < $columns_total; $i++) {
	//jika
	//echo "<br>$i>";
	//echo $aUpdateFieldInInput[$i];
	//if ($aUpdateFieldInInput[$i]=='0') continue;
	//$heading = mysql_field_name($sql, $i);
	//$judul .= ($judul==''?'':',').'"'.strtoupper($heading).'"';
	
	$jd=str_replace('XX','',$aFieldCaptionCSV[$i]);
	$judul .= ($judul==''?'':',').'"'.$jd.'"';
}
$output .=$judul."\n";
 
//echo "<br><br><br>000>$sqlTabel export:".$sqlall;exit;
//isi
if ($formatonly==0) {
	while ($row = mysql_fetch_array($hs)) {
		$baris="";
		for ($i = 0; $i < $columns_total; $i++) {
			//if ($aUpdateFieldInInput[$i]=='0') continue;
			$nmf=$aFieldCSV[$i];
			$baris .=($baris==''?'':',').'"'.$row[$nmf].'"';
		}
		$output .=$baris."\n";
	}
}

if (!isset($filename)) $filename = "myFile.csv";
header('Content-Disposition: attachment; filename='.$filename);
header("Content-type: text/csv");
header("Pragma: no-cache");
header("Expires: 0");
echo $output; 
exit;

?>