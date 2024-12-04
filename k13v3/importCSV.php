<?php
$arrResult = array();
$handle = fopen($csvfile, "r");
$lines = 0; $queries = ""; $linearray = array();
$ji=$br=0;
$jrSukses=0;
//baris pertama tidak digunakan
if( $handle ) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$arrResult[] = $data;
		if ($ji>0) {//baris  pertama judul
			$i=0;
			foreach($data as $dd) {
				$data[$i]=str_replace("'","\'",$data[$i]);
				$i++;
			}
	
			$linemysql = "'".implode("','",$data)."'";  
			$query = "insert into $nmTabel ($sField) values ($linemysql);"; 
			$h=mysql_query($query) ;
			if (!$h) {
				$idd="er".rand(123412121,943412751);
				echo "<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($lines-1)."</a> tidak bisa diimport 
				<span id=$idd style='display:none'>$query</span> ";
			} else
				$jrSukses++;
		}
		$ji++;
	}
	fclose($handle);
}
		
$jRecord=$ji;
?>