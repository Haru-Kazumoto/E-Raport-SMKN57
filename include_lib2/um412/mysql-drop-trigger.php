<?php
if (!isset ($nmTabel)) {
	$nmdb="";
	if (isset($db))
	$nmdb=$db;
	elseif (isset($dbku))
	$nmdb=$dbku; 
	
	 $sql = "SHOW TABLES FROM $nmdb";
	 $hs = mysql_query2($sql);
	 $arrayCount = 0;
	 while ($row = mysql_fetch_row($hs)) {
	  $tableNames[$arrayCount] = $row[0];
	  $arrayCount++; //only do this to make sure it starts at index 0
	 }
	 $atb=[];
	 foreach ($tableNames as &$name) {
		$atb[]=$name;	
	 }
	 $sNmTabel=implode(",",$atb);
}
else {
	$atb=explode(",",$nmTabel);
}

 //trigger untuk  semuatabel
$result="";
$triggertb ="";
$triggerx="";
foreach ($atb as $nmTabel) {
	if ($nmTabel=='tblog') continue;

	$triggerx.="
	ALTER TABLE `$nmTabel` ADD `created_by` VARCHAR(40) NOT NULL, 
	ADD `created_time` TIMESTAMP NOT NULL AFTER `created_by`, 
	ADD `modified_by` VARCHAR(40) NOT NULL AFTER `created_time`, 
	ADD `modified_time` TIMESTAMP NOT NULL AFTER `modified_by`;
	 
	
	";
	
	$triggertb.="
	DROP TRIGGER IF EXISTS `$nmTabel"."_after_update`;
	DROP TRIGGER IF EXISTS `$nmTabel"."_after_insert`;
	DROP TRIGGER IF EXISTS `$nmTabel"."_before_delete`;
	";


}
//$result.="<br><textarea cols=120 rows=10 style='background:#ffff99'>$triggertb</textarea>";
$result.="<br><textarea cols=120 rows=10 style='background:#ffff99'>$triggertb</textarea>";
//echo $result;

$result.="<br><textarea cols=120 rows=10 style='background:#ffff99'>$triggerx</textarea>";
echo $result;


	 ?>