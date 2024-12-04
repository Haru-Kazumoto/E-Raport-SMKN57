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
$sAllTrigger="";
foreach ($atb as $nmTabel) {
	if ($nmTabel=='tblog') continue;
	$strigger="";
	$strigger0="";

	//generate field
	$nmCaptionTabel=$nmTabel;
	$det=str_replace("tb","",$nmTabel);
	$afd=array();
	$sfd="";
	$i=0;
	$triggertb='';
	$h=mysql_query2("select * from $nmTabel where 1=2");
	$nmFld2=$srCek="";
	$result.="
	<hr><br>
	";
	while ($i < mysql_num_fields($h)) {$meta = mysql_fetch_field($h);
		$nmfield=$meta->name;
		if ($i==0) $nmFieldID =$nmfield;
		$afd[]=$nmfield;
		$sfd.=($sfd==''?'':',').$nmfield;
		$jn="4";//jenis atau lebar
		if ($nmfield=='catatan') {
			$jn="T";
		}elseif ( strstr($nmfield,"tgl")!='') {
			$jn="D";
		}
		
		if ($i==0) {
			$result.="$"."sAllField='';";
			$result.='$i=0;$sAllField.="'.($i>0?'#':'').$i.'|'.$nmfield.'|'.strtoupper($nmfield).'|11|0|0|0|50|C|'.$jn.'";<br>';	
			$result.='  $gGroupInput[$i]=\''.$nmCaptionTabel.'\';<br>';
		} else {
			if ($i==1) $nmFld2=$nmfield;//menentukan field2
			$result.='$i++;	$sAllField.="'.($i>0?'#':'').$i.'|'.$nmfield.'|'.strtoupper($nmfield).'|40|1|1|1|50|C|'.$jn.'";<br>';	
		}
		
		$srCek.="	if ($".$nmfield."=='') $"."pes.='*. ".strtoupper($nmfield)." tidak boleh kosong'; <br>";
		 
		if(strstr(",modified_by,created_by,modified_time,created_time",$nmfield)=='') {
			$strigger.="
			if (OLD.$nmfield<>NEW.$nmfield) THEN 
				SET @changetype = concat(@changetype ,'<br>$nmfield: ',OLD.$nmfield,'->',NEW.$nmfield); 
			END IF; 
			";
			$strigger0.="
			SET @changetype = concat(@changetype ,' $nmfield: ',NEW.$nmfield); 
			";
		}
	 

		$i++;
	}
	
	$result.="
	$"."isiComboFilterTabel=\"$nmFld2;$nmTabel.$nmFld2\";<br>
	$"."identitasRec='rc$nmTabel';<br>
	$"."configFrmInput='width:800,height:600';<br>
	$"."folderModul='m"."$det';<br>
	$"."nfReport=\"$"."folderModul/showtable.php\";<br>
	<br>
	//include \"$"."folderModul/custom-$det.php\"; 

	";
	
	$triggertb.="
	
 
	DROP TRIGGER IF EXISTS `$nmTabel"."_after_update`;
	DELIMITER $$
		CREATE TRIGGER `$nmTabel"."_after_update` AFTER UPDATE ON $nmTabel  
		FOR EACH ROW 
		
		BEGIN
			SET @changetype ='';
			$strigger
			if @changetype<>'' then
				INSERT INTO tblog (tb,jenislog,idtrans, ket,user ) VALUES ('$det','update',NEW.$nmFieldID, @changetype,NEW.modified_by);
			END IF;
    	END
		
	$$
	DELIMITER ;
	
 
	DROP TRIGGER IF EXISTS `$nmTabel"."_after_insert`;
	DELIMITER $$
	
	CREATE TRIGGER `$nmTabel"."_after_insert` AFTER INSERT ON `$nmTabel` 
	FOR EACH ROW 
	BEGIN
		SET @changetype ='';
		$strigger0
		INSERT INTO tblog (tb,jenislog,idtrans, ket,user) VALUES ('$det','insert',NEW.$nmFieldID, @changetype,NEW.created_by );
	END
	$$
	DELIMITER ;
 
	
	DROP TRIGGER IF EXISTS `$nmTabel"."_before_delete`;
	DELIMITER $$
	 	
	CREATE TRIGGER `$nmTabel"."_before_delete` BEFORE DELETE ON `$nmTabel` 
	FOR EACH ROW 
	BEGIN
		INSERT INTO tblog (tb,jenislog,idtrans, ket,user ) VALUES ('$det','delete',OLD.$nmFieldID, 'Penghapusan Data',OLD.modified_by );
	END
	$$
	DELIMITER ;
	
	";
$result.="<br><textarea cols=120 rows=10 style='background:#ffff99'>$triggertb</textarea>";
	
$sAllTrigger.=$triggertb;	
}
//$result.="<br><textarea cols=120 rows=10 style='background:#ffff99'>$triggertb</textarea>";
echo $result;
echo "<br><textarea cols=120 rows=10 style='background:#ffff99'>$sAllTrigger</textarea>";
 

	 ?>