<?php
$arrResult = array();
$strPenggantiTikom="~.tk.~";//pengganti titik koma ;
$strPenggantiKom="~.k.~";//pengganti pengganti koma ;

//mengatur sfield
	ini_set('max_execution_time',60*3);//3menit
	ini_set('memory_limit', '200M');
if (!isset($sFieldCSV)) {
	$sFieldCSV=$sField;
}

if (!isset($sFieldKey)) $sFieldKey="xxx";
if (!isset($sFieldKeyType)) $sFieldKeyType="txt";
if (!isset($sFieldIdImport)) $sFieldIdImport="";
if (!isset($formatTglCSV)) $formatTglCSV="ymd";
if (!isset($syImport))  $syImport="";
if (!isset($sySkipImport))  $sySkipImport="";//skip import jika kondisi tertentu tanpa harus cancel
if (!isset($sFieldCsvAdd)) $sFieldCsvAdd='';
if (!isset($sFieldCsvAddValue)) $sFieldCsvAddValue="";
$aFieldKey=explode(",",$sFieldKey);
$sqBatch="";
//echo"fk:$sFieldKey";
$aFieldCSV=explode(",",$sFieldCSV);
$aFieldType=getFieldType($sFieldCSV,$nmTabel);
//echo "<br>".implode(",",$aFieldType);
//echo "<br>".implode(",",$aFieldCSV);

echo $jlhKol = count($aFieldCSV);

$sFieldImport="";

$dSkip="";
for ($i = 0; $i < $jlhKol; $i++) {
//	if ($aUpdateFieldInInput[$i]=='0') continue;
	if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') continue;//skip jika nama field diawali xx
	$sFieldImport .=($sFieldImport==''?'':',').$aFieldCSV[$i];
}

$handle = fopen($csvfile, "r");
$lines = 0; $queries = ""; $linearray = array();
$ji=$br=0;
$jrSkip=$jrSukses=$jrUpdate=$jrInsert=0;
$qsalah="";
//baris pertama tidak digunakan

if( $handle ) {
	//cek data dahulu jika g ada yang skip baru dimasukkkan
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$brData=implode(",",$data);
		$arrResult[] = $data;
		$syimp=$syImport;
		$sySkipImp=$sySkipImport;
		
		$skip2=false;//skip disengaja karena syarat tertentu
		if ($ji>0) {//baris  pertama judul
			$i=0;
			//menghapus kolom yang tidak digunakan
			$newdata=array();
			$ketemu=false;
			$sfUpdate=$syKunci="";//update data
			for ($i = 0; $i <$jlhKol; $i++) {
			//foreach($data as $dd) {
				if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') {
					continue;//skip jika nama field diawali xx
				}
				//$data[$i]=str_replace("'","\'",$data[$i]);
				$data[$i]=str_replace("'","\'",$data[$i]);
				$isi=$data[$i];
				if (isset($aFieldFuncCSV[$i])){
					$func=$aFieldFuncCSV[$i];
					$func=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$func);
				
					eval("$"."isi=$func;");
				}
					
				//konversi tgl jika formatimport tidak ymd, jika namafield tgl
				//if (strstr(strtolower($aFieldCSV[$i]),"tgl")!='') {
				if ($aFieldType[$i]=="date") {
					//$oldisi=$newisi=$isi;//def
					$newisi=konversiFormatTgl($isi,$formatTglCSV,"ymd");
					$isi=$newisi;
					//echo "<br>konversi $aFieldCSV[$i] dari $formatTglCSV ke y-m-d dari $oldisi menjadi $newisi";
				}
				elseif ($aFieldType[$i]=="int") {
					$isi=str_replace(",","",$isi);//hilangkan tanda koma pada ribuan
				}
				elseif ($aFieldType[$i]=="real") {
					$isi=str_replace(",","",$isi);//hilangkan tanda koma pada ribuan
					
				}
				
				$ketemukunci=false;
				$newdata[]=$isi;
				foreach ($aFieldKey as $fk) {
					//echo "<br>cekking $fk ";
					if ($aFieldCSV[$i]==$fk) {
						$ketemukunci=true;
						if (strstr("string,date",$aFieldType[$i])!='')
							$syKunci.=($syKunci==''?'':' and ')." $fk='$isi' ";		
						else
							$syKunci.=($syKunci==''?'':' and ')." $fk=$isi ";	
					}
				}
				if (!$ketemukunci) $sfUpdate.=($sfUpdate==''?'':',')." $aFieldCSV[$i]='$isi'";
					
				//mengubah syImport
				$syimp=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$syimp);
				$sySkipImp=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$sySkipImp);
				
				//$i++;
			}
	
			$linemysql = "'".implode("','",$newdata)."'";  
			//cek syarat import----------------------------------------------------------------------------------
			//$syImport
			$ketSkip="";
			$skip=false;
			if ($syImport!='') {
				$asy=explode(";",$syimp);
				foreach ($asy as $xsy1) {
					$axsy1=explode(">>",$xsy1.">>");
					$xsy=$axsy1[0];
					if (trim($xsy)=="") continue;
					
					//$s=false;
					//$xsy=str_replace("csv[",$aFieldCSV[]);
					$ev="$"."s=($xsy?true:false);";
					$xeval=eval($ev);
					if (!$s) {
						$skip=true;
						$ketSkip.=($ketSkip==''?:", ")." SKIP $xsy $axsy1[1] ";
						//echo "<br>$ev ->$skip ... ";
					}
					 
				}
			}
			if ($skip) {
				$jrSkip++;
				$dSkip.="<br>Data:".$brData."->".$ketSkip;
			} else   {
				$skip2=false;
				if ($sySkipImport!='') {
					$asy=explode(";",$sySkipImp);
					foreach ($asy as $xsy1) {
						$axsy1=explode(">>",$xsy1.">>");
						$xsy=$axsy1[0];
						if (trim($xsy)=="") continue;
						//$s=false;
						//$xsy=str_replace("csv[",$aFieldCSV[]);
						
						$ev="$"."s=($xsy?true:false);";
						$xeval=eval($ev);
						//if (!$xeval) echo "<br>error $ev";
						//echo "<br>skip2 $ev ->$skip hasil: $s ";
						if ($s) {
							$skip2=true;
							//$ketSkip.="$axsy1[1] ";
							//echo "<br>berhasilskip $ev ->$skip ..hasil: $s ";
						}
						 
					}
				}
				if (!$skip2) {
					if ($sFieldKey!='xxx') {
						//cari data yang dah ad
						$sqk="select $sFieldKey from $nmTabel where $syKunci ";
						//echo "<br>".$sqk;
						$c=carifield($sqk);
						if ($c!='') $ketemu=true;
					}
					if (!$ketemu){
						if ($sFieldCsvAdd!='') {
							$query = "insert into $nmTabel ($sFieldImport,$sFieldCsvAdd) values ($linemysql,$sFieldCsvAddValue)"; 
						} else {
							$query = "insert into $nmTabel ($sFieldImport) values ($linemysql)"; 
							
						}
					} else {
						if ($sFieldIdImport!='') {
							$sfUpdate.=",$sFieldIdImport=$idimport";
						}
						
						if ($sFieldCsvAdd!='') {
							$afc1=explode(",",$sFieldCsvAdd);
							$afc2=explode(",",$sFieldCsvAddValue);
							$ia=0;
							foreach ($afc1 as $af) {
								$sfUpdate.=",$af=$afc2[$ia]";
								$ia++;
							}
							
						}
						
						$query = "update  $nmTabel set $sfUpdate where $syKunci"; 
						
					}
					
					$idd="er".rand(123412121,943412751);
					$infoq="<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($ji-1)."</a>  
						<span id=$idd style='display:none'>$query</span> ";
					//ubah tanda ; dahulu
					$newq=str_replace(";",$strPenggantiTikom,$query).";";
					//$newq=str_replace(",",$strPenggantiKom,$query);
					//echo "<br>$newq";
					$sqBatch.=$newq;
					 
					if ($ketemu)
						$jrUpdate++;
					else	
						$jrInsert++;
				}
			}
			// echo "<br>".$query;
			//echo $infoq;
		}
		$ji++;
	}
	
	if ($dSkip=='') {
		//hanya masukk jika tidak ada yang skip
		$sqBatchCR="";
		$asq=explode(";",$sqBatch); 
		foreach ($asq as $sq) {
			$query=str_replace($strPenggantiTikom,";",$sq);
			$sqBatchCR.=$query."<br>";
			//kembalikan ;
			
			if ($query=='') continue;
			$h=mysql_query2($query) ;
			
			if (!$h) {
				$infoq.=" tidak bisa diimport";
				$qsalah.="<br>".$query;
			} else {
				$jrSukses++;
				//$infoq="";
			}
		}
	}
	
	if ($jrSkip>0) {
		//echo "<br>Jumlah record diabaikan : $jrSkip";
		echo "
		<div class='callout callout-danger'>
		Import tidak berhasil, karena terdapat $jrSkip record data tidak valid sbb: 
		
		$qsalah;
		</div>
		<div  class='text text-warning' style='max-height:100px;width:95%;overflow:auto'>
		$dSkip
		</div>
		";
	} else {
		echo "
		<div class='callout callout-success'>
		Data Berhasil masuk sebanyak $jrSukses record,<br>terdiri dari  $jrInsert record baru, $jrUpdate record update.
		</div>
		
		<br>
		<br>
		";
		$tfbe="";
		$tfbe="reloadPage('$det',1000);";
		if ($isTest)	{
			echo "<div  class='text text-warning' style='max-height:100px;width:95%;overflow:auto'>
				$sqBatchCR
			</div>";
			
		}
	}
	if (trim($qsalah)!='') {
		echo "
		<div class='callout callout-warning'>
		Tidak Bisa Query : <div  class='text text-warning' style='max-height:100px;width:95%;overflow:auto'> <br>$qsalah</div>
		</div>";
		 
	}
	
	fclose($handle);
}
$jRecord=$ji;


?>