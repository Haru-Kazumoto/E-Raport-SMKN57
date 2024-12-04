<?php
$arrResult = array();
//mengatur sfield

if (!isset($sFieldCSV)) {
	$sFieldCSV=$sField;
}

if (!isset($sFieldKey)) $sFieldKey="xxx";
if (!isset($sFieldKeyType)) $sFieldKeyType="txt";
if (!isset($sFieldIdImport)) $sFieldIdImport="";


//echo"fk:$sFieldKey";
$aFieldCSV=explode(",",$sFieldCSV);

$columns_total = count($aFieldCSV);

$sFieldImport="";
for ($i = 0; $i < $columns_total; $i++) {
//	if ($aUpdateFieldInInput[$i]=='0') continue;
	if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') continue;//skip jika nama field diawali xx
	$sFieldImport .=($sFieldImport==''?'':',').$aFieldCSV[$i];
}


$lines = 0; $queries = ""; $linearray = array();
$ji=$br=0;
$jrSukses=$jrUpdate=$jrInsert=0;
$qsalah="";
//baris pertama tidak digunakan

	foreach($arrTable as $data) {
		$arrResult[] = $data;
		
		//if ($ji>=$startRow) {//baris  pertama judul
			$i=0;
			//menghapus kolom yang tidak digunakan
			$newdata=array();
			$ketemu=false;
			$sfUpdate=$syKunci="";//update data
			for ($i = 0; $i <$columns_total; $i++) {
			//foreach($data as $dd) {
				if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') {
					continue;//skip jika nama field diawali xx
				}
				//$data[$i]=str_replace("'","\'",$data[$i]);
				$data[$i]=str_replace("'","\'",$data[$i]);
				$newdata[]=$data[$i];
				
				if ($aFieldCSV[$i]!=$sFieldKey) 
					$sfUpdate.=($sfUpdate==''?'':',')." $aFieldCSV[$i]='$data[$i]'";
				else {
					if ($sFieldKeyType=='txt')
					$syKunci.=($syKunci==''?'':' and ')." $sFieldKey='$data[$i]' ";		
					else
					$syKunci.=($syKunci==''?'':' and ')." $sFieldKey=$data[$i] ";
				}
				//$i++;
			}
	
			$linemysql = "'".implode("','",$newdata)."'";  
			
			if ($sFieldKey!='xxx') {
				//cari data yang dah ada
				$c=carifield("select $sFieldKey from $nmTabel where $syKunci ");
				if ($c!='') $ketemu=true;
			}
			if (!$ketemu){
				$query = "insert into $nmTabel ($sFieldImport $sFieldCsvAdd) values ($linemysql $sFieldCsvAddValue);"; 
			} else {
				if ($sFieldIdImport!='') {
					$sfUpdate.=",$sFieldIdImport=$idimport";
				}
				$query = "update  $nmTabel set $sfUpdate where $syKunci;"; 
			}
			$h=mysql_query2($query) ;
			
			$idd="er".rand(123412121,943412751);
			//format tanggal $tmp = unpack("ddouble", "9999", 8));
			echo "<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($ji-1)."</a> tidak bisa diimport 
				<span id=$idd style='display:nonex'>$query</span> ";
			
			if (!$h) {
				$qsalah.="<br>".$query;
				
			} else
				$jrSukses++;
				if ($ketemu)
					$jrUpdate++;
				else	
					$jrInsert++;
		//}
		$ji++;
	}
	if ($qsalah!='') echo $qsalah;
	
$jRecord=$ji;

?>