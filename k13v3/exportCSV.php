<?php
$connectOnly=1;
include_once "conf.php";

$output = "";
if (!isset($sFieldCaption)) $sFieldCaption=$sField;

$aFieldCaption=explode(",",$sFieldCaption);
$aField=explode(",",$sField);

if (!isset($formatonly)) $formatonly=1;
$sql = mysql_query($sqlTabel);
//echo $sqlTabel;
$columns_total = mysql_num_fields($sql);

for ($i = 0; $i < $columns_total; $i++) {
	$heading = mysql_field_name($sql, $i);
	$output .= '"'.strtoupper($heading).'",';
}
$output .="\n";

if ($formatonly==0) {
	while ($row = mysql_fetch_array($sql)) {
		for ($i = 0; $i < $columns_total; $i++) {
			$output .='"'.$row["$i"].'",';
		}
		$output .="\n";
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