<?php

class exportImportCSV {
	public $title="";
	public $arrResult=array();
	public $strPenggantiTikom="~.tk.~";//pengganti titik koma ;
	public $strPenggantiKom="~.k.~";//pengganti pengganti koma ;
	public $sFieldCSV="";
	public $sFieldCSVCaption="";
	public $delimiter=",";
	
	public $sFieldKey="xx";
	public $sFieldKeyType="txt";
	public $sFieldIdImport="id";
	public $formatTglCSV="ymd";
	public $syImport="";
	public $pesSyImport="";
	public $sySkipImport="";//skip import jika kondisi tertentu tanpa harus cancel
	public $sFieldCsvAdd='';
	public $sFieldCsvAddValue="";
	public $nfImport="";//file csv
	public $aFieldFuncCSV=array();
	public $log;
	public $logErr="";
	public $nfCSVImport;
	public $sSqlAfterImport='';
	
	public $sRowCSVFunc="";//fungsi yg dijalan di setiap baris
	//result
	public $result=array();
	
	public $sSql="";
	public $sSqlErr="";
	
	public $jrSkip=0;
	public $jrSukses=0;
	public $jrUpdate=0;
	public $jrInsert=0;
	public $pes="";
	public $arrSQ=array();
	public $arrSkip=array(); //menyimpan baris mana saja yg skip
	public $arrDT1=array(); //menyimpan semua baris ke array
	public $arrDT2=array(); //menyimpan semua baris ke array yang kolomnya dipakai saja
	public $arrPrevMID=array(0);//array mysql_insert_id of previous insert
	public $arrMID=array(0);//array mysql_insert_id
	
	//untuk export
	public $formatOnly=1;
	public $nfExport="myFile.csv";//file hasil csv
	public $sql;
	public $colCount=0;
	public $sampleRow="";
	
	
	//frmimport
	public $nfActionFrmImport="";
	public $nfFormatFrmImport="";
	//public $cancelIfError=true;
	function frmImport(){
		global $det,$rnd;
		global $addInpImport;
		$rndx=rand(1123,9847);
		if ($this->nfActionFrmImport=="") 
			$this->nfActionFrmImport="index.php?det=$det&useJS=2&contentOnly=1&op=importcsv&newrnd=$rnd";
		if ($this->nfFormatFrmImport=='') 
			$this->nfFormatFrmImport="index.php?det=danggaran&useJS=2&contentOnly=1&op2=&op=unduhformat&outputto=csv&newrnd=$rnd";
		/*
		$exim=new exportImportCSV();
		$exim->nfActionFrmImport="index.php?det=danggaran&useJS=2&contentOnly=1&op2=&op=importcsv&newrnd=$rnd";
		$exim->nfFormatFrmImport="index.php?det=danggaran&useJS=2&contentOnly=1&op2=&op=unduhformat&outputto=csv&newrnd=$rnd";
		$frmExim=$exim->frmImport();
		*/
		$frmExim.="
			<a href=# onclick=\"bukaAjaxD('timp$rnd','','width:700')\" class='btn btn-success btn-sm'>Import</a>
				<div id=timp$rnd style='display:none'>
				<div id='tsfimp_$rnd' align='center'></div>
					<form id='fimp_$rnd' 
					action='$this->nfActionFrmImport' 
					onsubmit=\"uploadImportCSV($rnd);\"
					method='Post' >
						<table style='margin-top:0px' align='center'>						
							<tbody>
								<tr>
									<td>
										<div style='display:none' id='tfae$rnd2'></div>	
										<input type='button' value='Unduh Format' class='btn btn-primary btn-mini btn-sm ' onclick=\"bukaJendela('$this->nfFormatFrmImport');\" >
										<input type='file' name='nff' id='nff$rnd' onchange=\"a=$('#nff$rnd')[0].files[0].name;$('#nffx$rnd').html(a); \" >
										<span id='nffx$rnd' style='display:none'></span>
									</td>
									<td style='width:100px;' align='left'>
										<input type='submit' value='Import' class='btn btn-warning btn-mini btn-sm btn-block'
 style='margin-left:10px;margin-top:2px'>						
									</td>
								</tr>
								<tr>
									<td colspan='2'><br>
										Catatan : <br>
										<li>Gunakan format CSV, silahkan klik unduh format </li>						
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					</div>
					
			</div>
				";				
			return $frmExim;
	}
	
	function execExport() {
		global $nmTabel,$sqFilterTable;		
		if (!isset($sqFilterTable)) $sqFilterTable='';
		if ($this->sFieldCSVCaption=="") $this->sFieldCSVCaption=$this->sFieldCSV;
	//	$aFieldCaptionCSV=explode($this->delimiter,$this->sFieldCSVCaption);
	//	$aFieldCSV=explode($this->delimiter,$this->sFieldCSV);
		$aFieldCaptionCSV=explode(",",$this->sFieldCSVCaption);
		$aFieldCSV=explode(",",$this->sFieldCSV);
		$jkol = count($aFieldCSV);//mysql_num_fields($sql);
		$this->colCount=$jkol;
		$output="";
		$judul="";
		for ($i = 0; $i < $jkol; $i++) {
			//jika
			//echo $aUpdateFieldInInput[$i];
			//if ($aUpdateFieldInInput[$i]=='0') continue;
			//$heading = mysql_field_name($sql, $i);
			//$judul .= ($judul==''?'':',').'"'.strtoupper($heading).'"';
			
			$jd=str_replace('XX','',$aFieldCaptionCSV[$i]);
			$judul .= ($judul==''?'':$this->delimiter).$jd;
			//$output.= "<br>$i>$jd";
		}
		$output .=$judul."\n";
		
		 
		if ($this->formatOnly==1) {
			if ($this->sampleRow!='') {
				if ($this->delimiter==',')
					$output .=$this->sampleRow."\n";
				else
					$output .=str_replace(",",$this->delimiter,$this->sampleRow)."\n";
			}
		} else {
			$sf=str_replace('xx',"'' as xx",$this->sFieldCSV);
			if ($this->sql=='') {
				$sqTabelCSV="select $sf from $nmTabel ".$sqFilterTable;
				$this->sql=$sqTabelCSV;
			} else {
				$sqTabelCSV=$this->sql;
			}
			
			$hs = mysql_query($sqTabelCSV);
			while ($row = mysql_fetch_array($hs)) {
				$baris="";
				for ($i = 0; $i < $jkol; $i++) {
					$nmf=$aFieldCSV[$i];
					$baris .=($baris==''?'':$this->delimiter).'"'.$row[$nmf].'"';
				}
				$output .=$baris."\n";
			}
	
		}

		$filename = $this->nfExport;
		header('Content-Disposition: attachment; filename='.$filename);
		header("Content-type: text/csv");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $output; 
	}
	
	function execImport() {
		global $nmTabel,$sField;
		global $nfImport,$det,$isTest;
		$resultId=array();
		
		//mengatur sfield
		ini_set('max_execution_time',60*3);//3menit
		ini_set('memory_limit', '200M');
		/*
		if (!isset($sFieldCSV)) {
			$sFieldCSV=$sField;
		}
		*/
		//echo "<br><br>sfieldKey=$this->sFieldKey <br>";
		$aFieldKey=explode(',',$this->sFieldKey);
		$sqBatch="";
		$aFieldCSV=explode(',',$this->sFieldCSV);
		$aFieldType=getFieldType($this->sFieldCSV,$nmTabel);
		if ($this->sFieldCsvAdd!="") $aFieldTypeAdd=getFieldType($this->sFieldCsvAdd,$nmTabel);
		$columns_total = count($aFieldCSV);

		$sFieldImport="";

		$this->pesSkip="";
		for ($i = 0; $i < $columns_total; $i++) {
			if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') continue;//skip jika nama field diawali xx
			$sFieldImport .=($sFieldImport==''?'':',').$aFieldCSV[$i];
		}

		$handle = fopen($this->nfCSVImport, "r");
		$lines = 0; $queries = ""; $linearray = array();
		$br=0;
		
		$this->sSqlErr="";

		if( $handle ) {
			//cek data dahulu jika g ada yang skip baru dimasukkkan
			$arrId=array();
			$br=0;
			//echo "coltotal $columns_total";
			while (($xdata = fgetcsv($handle, 1000, $this->delimiter)) !== FALSE) {
				$data=$xdata;
				$this->arrSQ[$br]="";
				$skip=false;
				$brData=implode($this->delimiter,$data);//echo $brData;
				$this->arrDT1[$br] = $data;
				if ($br==0) { //baris  pertama judul
					$dt=array();
					for ($i = 0; $i <$columns_total; $i++) {
						if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') continue;//skip jika nama field diawali xx
						$dt[$br]=$data[$i];
					}
					$this->arrDT2[$br] = $dt;					
				} else {
					//eval function 
					/*
					if ($this->$sRowCSVFunc!='') {
						//eval($this->$sRowCSVFunc);
					}
					*/
					$i=0;
					
					$syImp=$this->syImport;
					$sySkipImp=$this->sySkipImport;
					$skip2=false;//skip disengaja karena syarat tertentu
					//menghapus kolom yang tidak digunakan
					$newdata=array();
					$ketemu=false;
					$sfUpdate=$syKunci="";//update data
					for ($i = 0; $i <$columns_total; $i++) {
						if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') {
							continue;//skip jika nama field diawali xx
						}
						
						//$data[$i]=str_replace("'","\'",$data[$i]);
						$data[$i]=str_replace("'","\'",$data[$i]);	
						$isi=$data[$i];
						
						if (isset($this->aFieldFuncCSV[$i])){
							$func=$this->aFieldFuncCSV[$i];
							$func=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$func);
							eval("$"."isi=$func;");
						}
							
						//konversi tgl jika formatimport tidak ymd, jika namafield tgl
						//if (strstr(strtolower($aFieldCSV[$i]),"tgl")!='') {
						if ($aFieldType[$i]=="date") {
							$newisi=konversiFormatTgl($isi,$this->formatTglCSV,"ymd");
							$isi=$newisi;
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
						$syImp=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$syImp);
						$sySkipImp=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$sySkipImp);
						//$i++;
						
					} 
					//cari kunci di tambahan field
					if ($this->sFieldCsvAdd!='') {
						foreach ($aFieldKey as $fk) {
							$afc1=explode(",",$this->sFieldCsvAdd);
							$afc2=explode(",",$this->sFieldCsvAddValue);
							$ia=0;
							foreach ($afc1 as $af) {
								//$sfUpdate.=",$af=$afc2[$ia]";
								
								if ($af==$fk) {
									$isi=$afc2[$ia];
									$syKunci.=($syKunci==''?'':' and ')." $fk=$isi ";	
								}
								$ia++;
							}						
						}								
					}
					
					global $isTest;
					$this->arrDT2[$br] = $newdata;
					$linemysql = "'".implode("','",$newdata)."'";
					//cek syarat import----------------------------------------------------------------------------------
					$ketSkip="";
					$skip1=$skip2=false;
					if ($this->syImport!='') {
						$asy=explode(";",$syImp);
						foreach ($asy as $xsy1) {
							$axsy1=explode(">>",$xsy1.">>data tidak sesuai");
							$xsy=$axsy1[0];
							if (trim($xsy)=="") continue;
							
							//$s=false;
							//$xsy=str_replace("csv[",$aFieldCSV[$br]);
							$ev="$"."s=($xsy?true:false);";
							$xeval=eval($ev);
							if ($isTest) echo "<br>$ev, result:$xeval s=$s > ";
							if (!$s) {
								$skip1=true;
								$ketSkip.=($ketSkip==''?:", ")." Diabaikan(skip) karena $axsy1[1] ";
							}
						}
					}
					
					if (!$skip1) {
						if ($this->sySkipImport!='') {
							$asy=explode(";",$sySkipImp);
							foreach ($asy as $xsy1) {
								$axsy1=explode(">>",$xsy1.">>");
								$xsy=$axsy1[0];
								if (trim($xsy)=="") continue;
								$ev="$"."s=($xsy?true:false);";
								$xeval=eval($ev);
								if ($s) {
									$skip2=true;
									$this->log.= "<br>berhasilskip $ev ->$skip ..hasil: $s ";
								}	 
							}
						}
						
						if (!$skip2) {
							$this->arrSkip[$br]=false;
							if ($this->sFieldKey!='xx') {
								//cari data yang dah ada
								$sqk="select $this->sFieldKey from $nmTabel where $syKunci ";
								$c=carifield($sqk);
								if ($c!='') $ketemu=true;
							}
							
							if (!$ketemu){
								$svv=$this->sFieldCsvAddValue;
								if (strstr($svv,"#arrPrevMID#")!='') {
									$svv=str_replace("#arrPrevMID#","'".$this->arrPrevMID[$br]."'",$svv);
								}
								
								//echo "add: $this->sFieldCsvAdd " .$svv."<br>";
								$query = "insert into $nmTabel 
								($sFieldImport ".($this->sFieldCsvAdd==''?"":",".$this->sFieldCsvAdd).") 
								values ($linemysql ".($this->sFieldCsvAdd==''?"":",$svv").")"; 								
								$this->arrJenisSQ[$br]=1;//insert
								$this->jrInsert++;	
							} else {
								$this->jrUpdate++;
								$this->arrJenisSQ[$br]=2;//update
								$this->arrMID[$br]=$c;
								/*
								if ($this->sFieldIdImport!='') {
									$sfUpdate.=",$this->sFieldIdImport=$idimport";
								}
								*/
								
								if ($this->sFieldCsvAdd!='') {
									$afc1=explode(",",$this->sFieldCsvAdd);
									$afc2=explode(",",$this->sFieldCsvAddValue);
									$ia=0;
									foreach ($afc1 as $af) {
										$vv=$afc2[$ia];
										echo "csvadd $vv<br><>";
										if ($vv=="#arrPrevMID#") {
											$vv="'".$this->arrPrevMID[$br]."'";
										}
										$sfUpdate.=",$af=$vv";
										$ia++;
									}									
								}
								$query = "update  $nmTabel set $sfUpdate where $syKunci"; 
							}
							
							$idd="er".rand(123412121,943412751);
							$this->log.="<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($br-1)."</a>  
								<span id=$idd style='display:none'>$query</span> ";
							
							//$newq=str_replace(";",$this->strPenggantiTikom,$query).";";
							//$sqBatch.=$newq;
							$this->arrSQ[$br]=$query;
							
						}//tidak skip2
					} //tidak skip1	
					
					if ($skip1 || $skip2) {
						$skip=true;
						$this->jrSkip++;
						$this->pesSkip.="<br>Data:".$brData."->".$ketSkip;
						$this->arrSkip[$br]=true;
					}					
				} //br>0
				$this->arrSkip[$br]=$skip;
				$br++;
			} //akhir while
			
			
		 
			//if ($this->pesSkip=='') {
				//hanya masukk jika tidak ada yang skip
			$br--;
			$this->jlhBr=$br;
			$this->pes.="<br>Jumlah Baris $br";
			$i=0;
			for ($i=0;$i<=$br;$i++) {
			//foreach ($asq as $sq) {
				//$query=str_replace($this->strPenggantiTikom,";",$sq);i
				$query=trim($this->arrSQ[$i]);
				//$sqBatchCR.=$query."<br>";
				//kembalikan ;
				if ($query=='') {
					
				} else {
					$h=mysql_query($query) ;
					if (!$h) {
						$this->log.="baris $br > tidak bisa diimport";
						$this->sSqlErr.="<br>".$query;
					} else {	
						$this->log.="baris $br > berhasil diimport, jenis :  ".$this->arrJenisSQ[$i];
						$this->jrSukses++;
					}
					if ($this->arrJenisSQ[$i]==1) {
						$this->arrMID[$i]=mysql_insert_id();
						//echo "<br>mid $i ".$this->arrMID[$i];
					} 
				}
			}
			//}
			//$this->arrSQ=$arrSQ;
			
			//if ($this->jrSkip>0) {
			if ($this->jrSukses>0) {
				if ($this->sSqlAfterImport!='') 
					$this->log.=querysql($this->sSqlAfterImport);
				/*
				//echo "<br>Jumlah record diabaikan : $jrSkip";
					$this->pes="
					<div class='callout callout-danger'>
					Terdapat $this->jrSkip record data tidak valid sbb: 
					
					$this->sSqlErr;
					</div>
					<div  class='text text-warning' style='max-height:100px;width:95%;overflow:auto'>
					$this->pesSkip
					</div>
					";
				} else {
				*/
				$this->pes.="
				<div class='callout callout-success'>
				Data Berhasil masuk sebanyak $this->jrSukses record,<br>
				terdiri dari  $this->jrInsert record baru, $this->jrUpdate record update.
				</div>
				<br>
				";
			}
			
			if ($this->pesSkip!='') {
				$this->pes.='<br>Catatan :<br>'.showTA($this->pesSkip)."<br>
				<br>".um412_falr("Silahkan lakukan perbaikan data dan import ulang untuk data-data yang diabaikan(skip) jika dikehendaki.","warning");
			}			
			if (trim($this->sSqlErr)!='') {
				$this->pes.="
				<div class='callout callout-warning'>
				Tidak Bisa Query : <div  class='text text-warning' style='max-height:100px;width:95%;overflow:auto'> <br>$this->sSqlErr</div>
				</div>";
				 
			}				
			
			if ($isTest)	{
				/*
				$this->pes.=" <div  class='text text-warning' style='max-height:100px;width:95%;overflow:auto'>
					$sqBatchCR
				</div>";
				*/
			}			 
			fclose($handle);
		} else {
			$this->pes.="File $nfCSVImport bermasalah....";
		}
		
		$jRecord=$br;
	}
}
?>