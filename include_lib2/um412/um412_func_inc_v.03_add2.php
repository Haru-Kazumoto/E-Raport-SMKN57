<?php
 //fungsi2 sql
function cariNoOtomatis($nmField,$nmTabel,$aw,$digit){
	/*global $xCek;
	$aw=$xCek[1];
	$digit=$xCek[2]*1;
	*/
	$sq="select $nmField from $nmTabel where $nmField like '$aw%' order by $nmField desc limit 0,1";
	$lastnorq=substr(carifield($sq),strlen($aw),$digit)*1+1;
	$lastnorqx="000000".$lastnorq;
	$nodef=$aw.substr($lastnorqx,strlen($lastnorqx)-$digit,$digit);
	return $nodef;
}

function cekIsset($sVar,$defVal="") {
	$aVar=explode(",",$sVar);
	$t="";
	foreach($aVar as $var ){
		$t.="global $"."$var;
		if (!isset($"."$var)) $"."$var='$defVal';";
	}
	eval($t);
}
//rs=REQUEST,SESSION
function setVar($var,$isi,$reqses="r") {
	//global $_REQUEST,$_SESSION;
	
	$adds="";
	if (strstr($reqses,"r")!="") {
		$adds="=$"."_REQUEST['$var']";
	}
	
	if (strstr($reqses,"s")!="") {
		$adds="=$"."_SESSION['$var']";
	}
	$s="global $"."$var;";
	if (is_array($isi)) {
		$s.="$"."$var $adds=$"."isi;";		
	} else {
		$s.="$"."$var $adds='$isi';";
	}
	eval($s);
}
function unsetVar($svar,$jenisvar='') {
	return releaseVar($svar,$jenisvar);
}
function releaseVar($svar,$jenisvar='rsv'){
	//$op=$_REQUEST['op']="";
	$avar=explode(",",$svar);
	foreach($avar as $v) {
		$e="global $$v;$$v='';";
		if (strstr($jenisvar,"v")!='') $e.="if (isset($$v)) unset($$v);";
		if (strstr($jenisvar,"r")!='') $e.="if (isset($"."_REQUEST['$v'])) unset($"."_REQUEST['$v']);";
		if (strstr($jenisvar,"s")!='') $e.="if (isset($"."_SESSION['$v'])) unset($"."_SESSION['$v']);";
		//echo "<br>$e";
		eval($e);
		
	}
}

function removeFromArray($array,$val) {
	foreach (array_keys($array,$val) as $key) {
		unset($array[$key]);
	}
	return $array;
}


/*
cekValidasiInput("nama,hp|Nomor HP","R","tbsiswa");
cekValidasiInput("nffoto","I-2mb"); I:image,Doc:doc

*/
function cekValidasiInput($svar="",$jvalidasi="R",$nmTabel="") {
	$pes="";
	$avar=explode(",",$svar);
	foreach($avar as $vr) {
		$avd=explode("|","$vr|".ucwords($vr));
		$var=$avd[0];$vcap=$avd[1];
		$seval="global $$var;";
		
		if (strstr($jvalidasi,"R")!='') {
			$seval.="if ($$var=='') $"."pes.='<div>*. $vcap tidak boleh kosong</div>';";
		}
		if (strstr($jvalidasi,"E")!='') {
			$seval.="if (!validasiEmail($$var)) $"."pes.='<div>*. $vcap tidak valid</div>';";
		}
		if (strstr($jvalidasi,"U")!='') {//unique			
			$seval.="if (carifield(\"select $var from $nmTabel where $var='$$var'\")!='')   $"."pes.='<div>*. $vcap tidak tersedia/sudah digunakan orang lain</div>';";
		}
		if (strstr($jvalidasi,"P")!='') {//unique			
			$seval.="$"."pes.=validasiPassword($$var,$$var,5);";
		}
		if ((strstr($jvalidasi,"I")!='')||(strstr($jvalidasi,"Doc")!='')) {//unique
			$strip=strpos($jvalidasi,"-")+1;
			$kb=strpos($jvalidasi,"kb");
			$mb=strpos($jvalidasi,"mb");	
			$u=0;
			if ($kb>0) {
				$kali=1024;
				$sat="kb";
				$u=substr($jvalidasi,$strip,$kb-$strip);
			} elseif ($mb>0) {
				$kali=1024*1024;
				$sat="mb";
				$u=substr($jvalidasi,$strip,$mb-strip);
				
			} else 
				$kali=0;
				
			$batasUkuran=$kali*$u;//2mb
			//echo "u $vr :$u> $batasUkuran>>>".  $_FILES[$vr]['size'];;
			if(isset($_FILES[$vr])){
			  $errors= array();
			  $file_name = $_FILES[$vr]['name'];
			  $file_size = $_FILES[$vr]['size'];
			  $file_tmp = $_FILES[$vr]['tmp_name'];
			  $file_type = $_FILES[$vr]['type'];
			  $file_ext=  pathinfo($_FILES[$vr]["name"], PATHINFO_EXTENSION);
			  if (strstr($jvalidasi,"Doc")) {
				$expensions= explode(",","doc,docx,xls,xlsx,pdf,txt");
				  if(in_array($file_ext,$expensions)=== false){
					 $seval.="$"."pes.='<div>*. Silahkan pilih file bertipe DOC/XLS/PDF/TXT</div>';"; 
				  }
			  } else {
				$expensions= explode(",","jpeg,jpg,png,gif");
				  if(in_array($file_ext,$expensions)=== false){
					 $seval.="$"."pes.='<div>*. Silahkan pilih file bertipe JPEG/PNG/GIF</div>';"; 
				  }
			  }
		 
			  
			  if($file_size > $batasUkuran) {
				 $seval.="$"."pes.='<div>*. Ukuran file maksimal $u $sat</div>';"; 
				 
			  } 
			}
		}
		//$pes.=$seval;
		eval($seval);
	}
	
	return $pes;
}


function trimEachArrayItem($nmvar) { 
	$ev="global $"."$nmvar;
		$"."i=0;foreach ($"."$nmvar as $"."nv) {
			@$"."$nmvar"."[$"."i]=trim($"."$nmvar"."[$"."i]);
			$"."i++; 
		}
	";
	//echo $ev;
	eval($ev);

}


//changeParamSySql2($sArray,$fld="id",$param=",",$oprx="=","or")
function changeParamSySql2($strArray,$fld,$param=",",$oprx=" ",$ao="or",$skipIfBlank=true){
	if ($skipIfBlank){
		$arr=explode($param,$strArray);
		$strArray="";
		foreach ($arr as $a) {
			if (($a===0)||($a=="")) continue;
			$strArray.=($strArray==""?"":",").$a;
		}
	}
	
	
	$t="";
	$t.=" ($fld $oprx '". str_replace($param,"' $ao $fld $oprx '",$strArray)."') ";
	return $t;	
}
//changeParamSySql($string," "," like '%$aff%'",$operasi='and/or def:or');
function changeParamSySql($strArray,$param=" ",$strrepl=" ",$ao="or"){
	$t="";
	$t.=str_replace($param," $strrepl $ao ",$strArray)." $strrepl";
	return $t;	
}

//1,2,3 menjadi (imd='1' or imd='2' or imd='3')
//xvar=id separator=, aps="'"
//gantiSeparator($sim,"imd")
function gantiSeparator($var,$xvar="id",$separator=",",$aps="'") {
	$v=" $xvar=$aps". str_replace($separator,"$aps or $xvar=$aps",$var)."$aps ";
	if (strstr($var,$separator)!="") $v="($v)";
	return $v;//$strawal.$v.$strakhir;
}


//menambah string
function addString($nmVar,$strAdd,$jenis='tableFilter'){
	$st=("global $$nmVar;$$nmVar.=($$nmVar==''?'where':'and').\" $strAdd\";");	
	//echo($st);
	eval($st);
}

//belum fix csvtosql
function csvtosql($nmTabel,$datacsv,$lb="\n",$includehead=1) {		
	$arrResult = array();
	$strPenggantiTikom="~.tk.~";//pengganti ;
	$strPenggantiKom="~.k.~";//pengganti ;

	//mengatur sfield
		ini_set('max_execution_time',60*3);//3menit
		ini_set('memory_limit', '200M');
	/*
	if (!isset($sFieldCSV)) {
		$sFieldCSV=$sField;
	}
*/
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
//	$aFieldCSV=explode(",",$sFieldCSV);
//	$aFieldType=getFieldType($sFieldCSV,$nmTabel);
	//echo "<br>".implode(",",$aFieldType);
	//echo "<br>".implode(",",$aFieldCSV);

	//$columns_total = count($aFieldCSV);

	$sFieldImport="";

	/*
	$dSkip="";
	for ($i = 0; $i < $columns_total; $i++) {
	//	if ($aUpdateFieldInInput[$i]=='0') continue;
		if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') continue;//skip jika nama field diawali xx
		$sFieldImport .=($sFieldImport==''?'':',').$aFieldCSV[$i];
	}
	*/
	//cek data dahulu jika g ada yang skip baru dimasukkkan
	$ji=0;
	$adata=explode($lb,$datacsv);
	//while (($data = fgetcsv($datacsv, 1000, ",")) !== FALSE) {
	$syimp="";
	$sySkipImp="";
	$jrInsert=0;	
	foreach($adata as $dt){		
		if ($dt=="") continue;
		$brData=$dt;//implode(",",$data);
		$data=explode(",",$dt);
		$arrResult[] = $data;
		$skip2=false;//skip disengaja karena syarat tertentu
		if ($ji==0) {//baris  pertama judul
			$sFieldCSV=$brData;
			$columns_total=count($data);
			$aFieldCSV=explode(",",$sFieldCSV);
			$aFieldType=getFieldType($sFieldCSV,$nmTabel); 
			$sFieldImport=$sFieldCSV;
			
			$aFieldKey=array("id");
			$ji++;
			continue;
		} 
		//menghapus kolom yang tidak digunakan
		$newdata=array();
		$ketemu=false;
		$sfUpdate=$syKunci="";//update data
		$i=0;
		for ($i = 0; $i <$columns_total; $i++) {
		//foreach($data as $dd) {
			if (substr(strtolower($aFieldCSV[$i]),0,2)=='xx') {
				continue;//skip jika nama field diawali xx
			}
		
			//$data[$i]=str_replace("'","\'",$data[$i]);
			$data[$i]=str_replace("'","\'",$data[$i]);
			$isi=$data[$i];
			//echo $isi."<br>";
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
			$newdata[]=$isi;
			
			/*
			$ketemukunci=false;
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
			*/
			//mengubah syImport
			$syimp=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$syimp);
			$sySkipImp=str_replace("-#".$aFieldCSV[$i]."#-",$isi,$sySkipImp);
			
			//$i++;
		}

		//cek syarat import----------------------------------------------------------------------------------
		$linemysql = "'".implode("','",$newdata)."'";  
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
				//echo "<br>$xsy > $s ";
				//if (!$xeval) echo "<br>error $ev";
				if (!$s) {
					$skip=true;
					$ketSkip.=($ketSkip==''?'':", ")."$axsy1[1] ";
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
				//$newq=str_replace(",",$strPenggantiKom,$query).";";
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
	$ji++;
	}
	
	
	//if ($dSkip=='') {
	//hanya masukk jika tidak ada yang skip
	$sqBatchCR="";
	$asq=explode(";",$sqBatch); 
	foreach ($asq as $sq) {
		$query=str_replace($strPenggantiTikom,";",$sq);
		$sqBatchCR.=$query.";";
		//kembalikan ;
		
		if ($query=='') continue;
		/*
		$h=mysql_query2($query) ;
		
		if (!$h) {
			$infoq.=" tidak bisa diimport";
			$qsalah.="<br>".$query;
		} else {
			$jrSukses++;
			//$infoq="";
		}
		*/
	}
	//}
	/*
	if ($jrSkip>0) {
		//echo "<br>Jumlah record diabaikan : $jrSkip";
		echo "
		<div class='callout callout-danger'>
		Import tidak berhasil, karena terdapat $jrSkip record data tidak valid sbb: 
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
	*/
	return $sqBatchCR;
}
		
function sqlToCsv($sql,$lb="\r\n",$includehead=1) {
	$strPenggantiTikom="~.tk.~";//pengganti ;
	$strPenggantiKom="~.k.~";//pengganti ;	
	$as= sqlToArray($sql,$includehead);
	$s="";
	foreach ($as as $lineas) {
		$line="";
		$c=0;
		foreach ($lineas as $dt) {
			$dt=str_replace(",",$strPenggantiKom,$dt);
			$dt=str_replace(";",$strPenggantiTikom,$dt);
			$line.=($c===0?"":",")."$dt";
			$c++;
		}
		$line.=$lb;
		$s.=$line;
	}
	return $s;
}

function sqlToTxt($sql,$param="||",$includehead=true){
	$arrTabel= sqlToArray($sql,$includehead);
	
	$s="";
	foreach($arrTabel as $r) {
		$sbr="";
		$j=0;
		foreach ($r as $xr) {
			if (strstr($xr,$param)!="") $xr=str_replace($param,'_param_',$xr	);
			$sbr.=($j==0?"":$param).$xr;
			$j++;
		}
		$s.="$sbr\n";
	}
	return $s;
}

function txttosql($tb,$param="||"){
	/*
	$arrTabel= sqlToArray($sql,$includehead);
	
	$s="";
	foreach($arrTabel as $r) {
		$sbr="";
		$j=0;
		foreach ($r as $xr) {
			if (strstr($xr,$param)!="") $xr=str_replace($param,'_param_',$xr	);
			$sbr.=($j==0?"":$param).$xr;
			$j++;
		}
		$s.="$sbr\n";
	}
	return $s;
	*/
	echo "dalam proses fungsi txttosql";
}

//includehead:0, tidak pakai,1:pakai,2:tidak pakai dan dataonly
function sqlToArray($sql,$includehead=1,$result='array') {	
	global $arrFldTable;
	$arrTable=array();
	$sql=str_replace("<br>","",$sql);
	$arrFldTable=getArrayFieldName($sql,"array");
	$jlhfld=count($arrFldTable);
	$h=mysql_query2($sql) or die("<br>err sqltoArray : ". mysql_error()."<br>sql: ".$sql) ;
	
	if ($result!='JSon') {
		if ($includehead==1) $arrTable[]=$arrFldTable;
		while($r=mysql_fetch_array($h)) {
			
			//dataonly
			if ($includehead==2) {
				$line=array();
				for ($i=0;$i<$jlhfld;$i++) {
					$line[]=$r[$i];
				}
				$arrTable[]=$line;
			} else {			
				$arrTable[]=$r;	
			}			
		}
		return $arrTable;
		
		
	} else {
		$arrTable['fld']=$arrFldTable;
		$data=array();
		while($r=mysql_fetch_array($h)) {
			$line=array();
			for ($i=0;$i<$jlhfld;$i++) {
				$line[]=$r[$i];
			}
			$data[]=$line;		
		}
		$arrTable['data']=$data;
		return json_encode($arrTable);
	}
}

function sqlToJSon($sql) {
	return sqlToArray($sql,0,'JSon'); 
}


function sqlToArray2($sql) {
	return sqlToArray($sql,0); 
}

function sqlToHtmlTable($ssql,$sAddAttr="", $sJudul="",$sAlignX="",$outputas='html',$showJlh="-" ,$pAddEv='',$includehead=1) {
	$ssql=str_replace("<br>","",$ssql);
	global $showNo;
	global $addEv;
	global $sAlign;
	if ($sAlignX!='') $sAlign=$sAlignX;
	
	/*
	global $sClass;
	if ($sClass!='') $sAlign=$sAlignX;
	*/
	if ($pAddEv!=='') $addEv=$pAddEv;
	global $brPerPage,$headPage,$footPage;//akan ditambahkan setiap br=brperpage
	//if ($brPerPage==0) $brPerPage=40;
	$t="";
	/*
		$brPerPage=40;
		$headPage="<div class=page>";
		$footPage="</iv>";
	*/
	$asql=explode("|","$ssql|$ssql");
	
	$sql=str_replace("xx","",$asql[0]);
	
	//mencari sfield
	$sfld=str_replace("select ","",$asql[1]);
	$sfld=str_replace("xx","",$sfld);
	$posfrom=strpos($sfld," from ");
	$sfld=substr($sfld,0,$posfrom);
	$afld=explode(",",$sfld);
	$i=0;
	
	foreach ($afld as $fld) {
		$posas=strpos($sfld," as ");
		if ($posas>0){
			$afld[$i]=substr($afld[$i],$posas,strlen($afld[$i]));
		}
		$i++;
	}
	
	
	$asql=explode("|","$ssql|$ssql");
	
	//$aKol=getArrayFieldName($sql);
	//salign=C,-,C,L... -:tidak ditampilkan
	if ($sAddAttr=='') $sAddAttr="border=1 width=98% align=center cellspacing=1 cellpadding=5 ||";
	$arrTable=array();
	global $r;
	$h=mysql_query2($sql) or die("<br>err sqltohtmltbl : ". mysql_error()."<br>sql: ".$sql) ;
	
	$arrKolJlh=array();
	if ($showJlh!='-') {
		$arrKolJlh=explode(",",$showJlh);
	}
		
	//kolom yang akan dijumlah diisi nilai 0
	$aKol=$aKolNew=array();
	$i=0; 
	if ($showNo) {
		$aKol[]="NO"; 
		$aKolNew[]="NO"; 
		$sJudul.=($i>0?",":"");
		$sAlign.=($i>0?",C":"C");		
	}
	 
	
	while ($i < mysql_num_fields($h)) { 
		$meta = mysql_fetch_field($h, $i);
		$aKol[]=$meta->name; 
		$aKolNew[]=$meta->name; 
		$sJudul.=",";
		$sAlign.=",C";
		
		if (is_array($addEv)) {
			foreach ($addEv as $ev) {
				if ($ev[1]==$i) {
					$aKolNew[]=$ev[2]; 
				}
			}
		}
		$i = $i + 1; 
	}
	if ($includehead==1)  {
		$arrTable[]=$aKol;
	}
	$aJdlKol=explode(",",$sJudul);
	$aAlign=explode(",",$sAlign);
	
	//echo $sql;
	//memmecah attr
	$addAttr=explode("|",$sAddAttr."||");	
	$headTb=$headPage."<table $addAttr[0] >";
	$headTb.="<tr $addAttr[1]>";
	$i=0;
	foreach($aKol as $kol) {
		$jdl=($aJdlKol[$i]==""?$kol:$aJdlKol[$i]);
		$jdl=str_replace("_"," ",$jdl);
		if ($aAlign[$i]!='-') $headTb.="<th style='text-align:center'>$jdl</th>";
		
		if (is_array($addEv)) {
			foreach ($addEv as $ev) {
				if ($ev[1]==$i) {
					$headTb.="<th style='text-align:center'>$ev[2]</th>";
				}
			}
		}
		$i++;
	}
	
	$headTb.="</tr>";
	
	$t="";
	$t.=$headTb;
	
	$br=0;
	$arrJlh=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	while($r=mysql_fetch_array($h)) {
		$troe=($br%2==0?$addAttr[1]:$addAttr[2]);
		$t.="<tr class='$troe'>";
		$i=$j=0;
		$columns=$colNew=$jlhC=array();
		
		foreach($aKol as $kol) {
			$align=($aAlign[$i]=="C"?"center":($aAlign[$i]=='L'?"left":($aAlign[$i]=='R'?"right":$aAlign[$i])));
			if ($kol=="NO") {
				$isi=$columns[]=$colNew[]=$br+1;
			} else {
				$isi=$r[$kol];
				
				if (strstr($isi,"#")!='') { //evaluasi jika ada data #nmfield#
					foreach($aKol as $k) {
						$isi=str_replace("#$k#",$r[$k],$isi);
					}
				}
				
				//$isi=strtoupper(str_replace("_"," ",$isi));
				$columns[]=$r[$kol];
				$colNew[]=$r[$kol];
			}
			
			
			if (in_array($i,$arrKolJlh)) {
				$arrJlh[$j]+=$r[$kol]*1;
				$j++;
			}
				
			if ($aAlign[$i]!='-') {
				$t.="<td align='$align'>".$isi."</td>";
			}
			if (is_array($addEv)) {
				foreach ($addEv as $ev) {
					if ($ev[1]==$i) {
						$vv=evalGFF($ev[0]);
						$colNew[]=$vv;
						$t.="<td>$vv</td>";	
						if ($ev[3]==1) {
							$arrJlh[$j]+=$vv*1;
						} 
						$j++;
					}
				}
			}
			
			$i++;
		}
		$arrTable[]=$colNew;	
		$t.="</tr>";
		
		$br++;
		if ($brPerPage>0){
			if ($br%$brPerPage==0) {
				$t.="</table> $footPage  $headTb";
			}
		}
	}
	
	if ($showJlh!='-') {
		$i=$j=0;
		$t.="<tr>";
		foreach($aKol as $kol) {
			
			if (in_array($i,$arrKolJlh)) {
				$vv=$arrJlh[$j];
				$j++;
			} else $vv=" ";
			
			$align=$aAlign[$i];
			if ($aAlign[$i]!='-') $t.="<td align='$align'>$vv</td>";

			if (is_array($addEv)) {
				foreach ($addEv as $ev) {
					if ($ev[1]==$i) {		
						if ($ev[3]==1) {		
							$vv=$arrJlh[$j];
						} else $vv="";
						$j++;
						$t.="<td align='$align'>$vv</td>";
					}  
					
				}
			}
			
			$i++;
		}
		$t.="</tr>";
	}
	
	$t.="</table>";
	if ($outputas=='html')
		return $t;
	else
		return $arrTable;
}

function generateSqlCrosstab($fldKol="jk",$fldBar="ras",$nmTable="tbpasien",$showTotal=true,$order="",$fldsum="1") {
	$sqK="select distinct($fldKol) from $nmTable";
	$aKol=getArray($sqK,"Kolom");
	$sql="";
	$sSum=$sTot=$sFldKol="";
	$jlhKol=0;
	foreach($aKol as $kol) {
		$kolCaption=str_replace(" ","_",$kol);
		$kolCaption=str_replace("-","_",$kolCaption);
		$kolCaption=str_replace("(","_",$kolCaption);
		$kolCaption=str_replace(")","",$kolCaption);
		$sSum.=($sSum==''?"":",")."<br> sum(case when $fldKol='$kol' then $fldsum else 0 end) as $kolCaption";
		//SUM(IF(proyek = 'desktop',durasi,0)) AS desktop,
		$sTot.=($sTot==''?"":"+")."$kolCaption";
		$sFldKol.=($sFldKol==''?"":",")."$kolCaption";
		$jlhKol++;
	}
	if ($jlhKol==0) {
		$sFldKol='1';
		$sSum="1";
	}
	
	$fldBarCap=$fldBar;
	$fldBarCap=str_replace(" ","_",$fldBarCap);
	$fldBarCap=str_replace("-","_",$fldBarCap);
	$fldBarCap=str_replace("(","_",$fldBarCap);
	$fldBarCap=str_replace(")","",$fldBarCap);

	$sTot="($sTot) as Tot";
	$sql="
	<br> SELECT
		<br> IFNULL($fldBar, 'Total') AS $fldBarCap,
		<br> $sFldKol ".($showTotal?",$sTot":"")."
	 FROM (
		<br>SELECT
			$fldBar,
			$sSum
		<br>FROM
			$nmTable
		GROUP BY $fldBar WITH ROLLUP
	 ) 
	 <br>AS tempTable $order";
	return $sql;
}

//kdbrg,januari,feb,maret,april,mei...
//$xsq=generateSqlCrosstab($fldTgl="tgl",$fldBar="kdbrg",$nmTable="tbpbelid d inner join tbpbeli h on d.notrans=h.notrans ",$showTotal=true,$order="",$fldsum="1",$th=0,$sy="") ;
		
function generateSqlTrendBulanan($fldTgl="tgl",$fldBar="kdbrg",$nmTable="tbpbarang",$showTotal=true,$order="",$fldsum="1",$th=0,$addSy="") {
	global $aBulan,$aBulan2;
	if ($th==0) $th=(date("Y"))*1;
	
	$sql="";
	$sSum=$sTot=$sFldKol="";
	$jlhKol=12;
	$b=1;
	foreach($aBulan2 as $bl) {
		$sSum.=($sSum==''?"":",")."<br> sum(case when month($fldTgl)='$b' then $fldsum else 0 end) as $bl";
		$sTot.=($sTot==''?"":"+")."$bl";
		$sFldKol.=($sFldKol==''?"":",")."$bl";
		$b++;
	}

	$fldBarCap=$fldBar;
	$fldBarCap=str_replace(" ","_",$fldBarCap);
	$fldBarCap=str_replace("-","_",$fldBarCap);
	$fldBarCap=str_replace("(","_",$fldBarCap);
	$fldBarCap=str_replace(")","",$fldBarCap);
	$fldBarCap=str_replace(".","_",$fldBarCap);
	
	
	$sTot="($sTot) as tot";
	$sy=($addSy==""?"":"where $addSy");
	$sql="
	<br> SELECT
		<br> IFNULL($fldBarCap, 'Total') AS $fldBarCap,
		<br> $sFldKol ".($showTotal?",$sTot":"")."
	 FROM (
		<br>SELECT
			$fldBar as $fldBarCap,
			$sSum
		<br>FROM
			$nmTable
		$sy
		GROUP BY $fldBar WITH ROLLUP
	 ) 
	 <br>AS tempTable $order";
	return $sql;
}

//get first image from text/blog
function getFirstImage($txt) {
	preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $txt, $matches);
	$first_img = $matches [1] [0];
}


function showThumb($nf,$sukuran="50",$maxw=500) {	
	$au=explode(",","$sukuran,$sukuran");
	$ukuranw=$au[0];$ukuranh=$au[1];
	
	$nfthumb=createThumb($nf, $dest="", $ukuranw);

	$tim="timg_"."_".rand(1232,93311);
	$t="";
	$rndnf=encod($nf);
	$ww=$maxw+40;
	$tkn=makeToken("nfgb=$rndnf&maxw=$maxw");
	$t.="
	<span id='$tim' style='display:none'></span>
	<a href=# onclick=\"bukaAjaxD('$tim','index.php?det=bukagambar&tkn=$tkn','width:$ww');return false\"  >
		<img src='$nfthumb' width='$ukuranw'> 
	</a>";
	return $t;						
}

function createThumb($src, $dest="", $desired_width=50,$overwrite=false) {
	//cekking type
	if (!file_exists($src)) return "";
	$ext=pathinfo($src,PATHINFO_EXTENSION);
	if ($dest=='') {		
		$dir = pathinfo($src, PATHINFO_DIRNAME);
		$nfonly = pathinfo($src, PATHINFO_FILENAME);
		$ext = pathinfo($src, PATHINFO_EXTENSION);
		$nmfile = pathinfo($src, PATHINFO_BASENAME);
		$dest="$dir/$nfonly"."-thumb-".$desired_width.".$ext";
	//	echo "dest-$dest >> $nmfile 	";
	}

	if (!file_exists($src)) {
		//echo $src." not found<br>";
		return "";
	}
	
	/* read the source image */
	if ((!$overwrite)&& (file_exists($dest)) ) return $dest;
	//echo "ext src :$src -> $ext ";
	if ($ext=='gif') 
		$source_image = @imagecreatefromgif($src);
	else if ($ext=='png') 
		$source_image = @imagecreatefrompng($src);
	else
		$source_image = @imagecreatefromjpeg($src);
	
	if (!$source_image)	return "";
	$width = imagesx($source_image);
	$height = imagesy($source_image);
	
	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));
	
	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);
	
	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	
	/* create the physical thumbnail image to its destination */
	if ($ext=='gif')  
		imagegif($virtual_image, $dest); 
	else if ($ext=='png')  
		imagepng($virtual_image, $dest); 
	else
		imagejpeg($virtual_image, $dest); 
	return $dest;
}

//Created by : Mohammad Dayyan Mds_soft@yahoo.com 1387/5/15
//modifield by um412@yahoo.com
function createThumbAll($nfImage, $new_width=50, $new_height='auto', $save_path="",$ignoreIfExists=true) {
	
	if ($nfImage=='') return '';
	if (!file_exists($nfImage)) return '';
	$imgInfo = getimagesize($nfImage);
	$imgExtension = "";

    switch ($imgInfo[2])    {
	    case 1:
	    	$imgExtension = 'gif';
	   		break;

	    case 2:
	    	$imgExtension = 'jpg';
	    	break;

	    case 3:
	    	$imgExtension = 'png';
	    	break;
    }
	if ($new_height=='auto') {
		list($width, $height) = getimagesize($nfImage);
		$new_height=round($height*$new_width/$width);
	}
		
	
	if ($save_path=="") {
		$url_info = parse_url($nfImage);
		$full_path = $url_info['path'];
		$path_info = pathinfo($full_path);
		//$save_path = str_replace($nfImage,"",$url_info['path'])."/".$path_info['filename'] . '-t.' . $path_info['extension'];		
		$save_path = $path_info['dirname']."/".$path_info['filename']."_".$new_width."x$new_height.$imgExtension";//gb_100x100.jpg
	}
	//echo "nfi:$nfImage $save_path";exit;
	if (file_exists($save_path)) return $save_path;
	// Get new dimensions
	

	// Resample
	$imageResample = imagecreatetruecolor($new_width, $new_height);
	if ( $imgExtension == "jpg" )	{
		$image = imagecreatefromjpeg($nfImage);
	}	else if ( $imgExtension == "gif" ){
		$image = imagecreatefromgif($nfImage);
	} else if ( $imgExtension == "png" ){
		$image = imagecreatefrompng($nfImage);
	}

	imagecopyresampled($imageResample, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

	if ( $imgExtension == "jpg" ) {
		imagejpeg($imageResample, $save_path);
	} else if ( $imgExtension == "gif" ){
		imagegif($imageResample, $save_path);
	} else if ( $imgExtension == "png" )	{
		imagepng($imageResample, $save_path);
	}	
	return $save_path;
}


function fbLink($lnk,$judul='',$showFace=false) {
	//create fb share and like button
	global $isOnline;
	$t="<div id=tsocial>";
	if ($isOnline) {
			$t.="<div class='fb-like' data-href='$lnk' data-layout='standard' data-send='true' data-action='like' data-show-faces='$showFace' 
			data-share='true'></div>";
			$t.="<div class='fb-send' data-href='$lnk'></div>";
			//$t.="<div class='fb-comments' data-href='$lnk' data-numposts='5'></div>";
			$t.='<a href="https://twitter.com/share" class="twitter-share-button" data-url="'.$lnk.'" data-text="'.$judul.'">Tweet</a> ';

			
			
	}
	$t.="</div>";
	
	return $t;
}

function fbComment($lnk,$judul='',$showFace=false) {
	//create fb share and like button
	global $isOnline;
	$t="<div id=tsocialcomment>";
	if ($isOnline) {
			$t.="<div class='fb-comments' data-href='$lnk' data-numposts='5'></div>";
	}
	$t.="</div>";
	
	return $t;
}

function gViewer($nf,$w='100%',$h='100%') {
	/*
	google pdf viewer
	$nf="http://www.apa.com/test.pdf";
	$w=700;
	$h=600;
	*/
	return "<iframe src='http://docs.google.com/viewer?url=$nf&embedded=true' width='$w' height='$h' style='border: none;'></iframe>";

}

//optjs=0:all, 1:header,2:after,4:cssAfterOnly
function extractHeader($optJS=0,$force=false){
	global $isTest;
	
	global $addHeader;
	global $addMeta;
	global $addCSS;
	global $addTxtCSS;
	global $addInclude;
	global $addJS;
	global $lang;
	global $namaorg;
	global $showHeader;
	global $template_path;
	global $js_path;
	global $addHeaderAfter;
	//echo "dikerjakan $addHeaderAfter";exit;
	
	global $addJSBefore;
	global $addCSSBefore;
	global $addJSAfter;
	global $addCSSAfter;
	
	if ($optJS<=1) {
		if (($showHeader==2) && (!$force)) return "";
		$showHeader=2;
	}
	
	$t="";
	$hasil="";
	$err="";
	if ($optJS<4) {	
		if ($optJS==0) {
			$hasil.=$addMeta;
			$sJs="$addJSBefore,$addJS,$addJSAfter";
			$addCSS="$addCSSBefore,$addCSS";
		} elseif ($optJS==1) {
			$hasil.=$addMeta;
			$sJs="$addJSBefore,$addJS";
			$addCSS="$addCSSBefore,$addCSS";
		} elseif ($optJS==2)
			$sJs="$addJSAfter";
		
		$sJs=str_replace("#template_path#",$template_path,$sJs);
		
		$aJs=explode(",",$sJs);
		foreach ($aJs as $fljs ) {
				$fljs=str_replace("#js_path#",$js_path,trim($fljs));
				$fl=explode("?",trim($fljs))[0]; 
				if ($fl!='') {
					if (file_exists($fl))  
						$hasil.="\n		<script src=\"$fljs\"></script> ";
					else 
						$err.="<br>$fl tidak ditemuan";
				}
		}
	
	} else {
		$addCSS="$addCSSAfter";

	}
	
	if ($optJS<=1) {
		$addCSS=str_replace("#template_path#",$template_path,trim($addCSS));
		$aAddCSS=explode(",",$addCSS);
		foreach ($aAddCSS as $flcss ) {
			$fl=explode("?",trim($flcss))[0]; 
			if ($fl!='') {
				if (file_exists($fl)) {
					$hasil.="\n		<link rel='stylesheet' type='text/css' href='$flcss' >";  
					//echo "<br>$flcss";
				}
				else 
					$err.="<br>$fl tidak ditemuan";
			}
		}
		$t.= $addHeader;
		$t.= "<head>\n";
		$t.= $addHeaderAfter;
		$t.= $hasil;
		if (isset($addTxtCSS)) $t.="<style>$addTxtCSS</style>";
		$t.= "\n</head>\n";
	} else {
		$t.= $hasil;
	}
	
	if ($isTest) echo $err;
	
	//echo showta($t);
	return $t;
}

function add_click($nmtabel,$idtabel="id",$idrecord,$fld="jlhclick"){
	//include_once();
	$sql="update $nmtabel set $fld=$fld+1 where $idtabel='$idrecord'";
	mysql_query2($sql);
};
 
function getlLevelOwner(){
	global $userType;
	global $isiJUser;
	global $sLevelOwner;	 
}

	
function validasiSRow($srow){
	if (substr($srow, -1)==";") $srow=substr($srow,0,strlen($srow)-1);
	$v=str_replace(";","-tikom-",$srow);
	return $v.";";
}
function devalidasiSRow($srow){
	$v=str_replace("-tikom-",";",$srow);
	return $v;
}

function notEmpty($var) {
	return strlen(trim($var))>0?true:false;
}
function removeEmptyArr($arr) {
	return array_filter($arr, 'notEmpty');
} 
function querysql($s,$cancelIfError=false,$showPes=true,$saveLog=true){
	$ketSaveSQL=($saveLog?"":"no save");
	$aline2= removeEmptyArr(explode(";",$s));

	$jsq=count($aline2);
	if ($jsq>1) mysql_query2("START TRANSACTION",$ketSaveSQL); 
		
	$strSql="";
	
	$i=0;
	$strE='';
	$pes="";
	foreach($aline2 as $line) {
		$line=devalidasiSRow($line);
		$line=trim($line);
		$skip=false;
	   if (substr($line,0,2)=='--') $skip=true;
	   if ($line=='') $skip=true;
	   if (!$skip) {
			$s=$line;
			$strSql.=$s;
			//echo "baris > ".showTA($line);
			//echo "<br>$cancelIfError - menjalankan $s <br>";
			$h=mysql_query2($s,$ketSaveSQL);
			if (!$h) {
				 $strE.=$s;
				 $pes.= "<br>err querysql :".mysql_error()."<span style='display:nonex'>$s".strlen($s)."<span>";
			}
			$s='';
	   }
	   $i++;
	}
	
	if ($cancelIfError && ($strE!='')) {
		if ($jsq>1) mysql_query2("ROLLBACK",$ketSaveSQL);	
	} else 
		if ($jsq>1) mysql_query2("COMMIT",$ketSaveSQL);
	
	if ($showPes) echo $pes;
}

function backupTable($table,$sy="",$dataOnly=false) {
	$sql='';
	$comment='';
	//foreach($tables as $table)  {
		$comment.="<br>Backing up ".$table." table...";

		$sq='SELECT * FROM '.$table.' '.$sy;
		$result = mysql_query2($sq);
		
		$numFields = mysql_num_fields($result);

		if (!$dataOnly) {
			$row2 = mysql_fetch_row(mysql_query2('SHOW CREATE TABLE '.$table));
			$sql.= 'DROP TABLE IF EXISTS '.$table.';';
			$sql.= "\n\n".$row2[1].";\n\n";
		}

		for ($i = 0; $i < $numFields; $i++)  {
			while($row = mysql_fetch_row($result)) {
				$sql .= 'INSERT INTO '.$table.' VALUES(';
				for($j=0; $j<$numFields; $j++)  {
					$row[$j] = addslashes($row[$j]);
					//$row[$j] = preg_replace("\n","\\n",$row[$j]);
					$row[$j] = str_replace("\n","\\n",$row[$j]);
					if (isset($row[$j])){
						$v=str_replace(";",":",$row[$j]);
						$sql .= '"'.$v.'"' ;
					} else {
						$sql.= '""';
					}

					if ($j < ($numFields-1))
					{
						$sql .= ',';
					}
				}

				$sql.= ");\n";
			}
		}

		$sql.="\n\n\n";

		$comment.=" OK" . "";
		 
		return $sql;
	//}
}

function getArrayLinkInURL($url,$isFolder=true){
	//digunakan untuk menampilkan array link dari suatu web
	//isfolder: jika true ,difungsikan untuk melihat link daftarfile, 
    // Create a new DOM Document to hold our webpage structure
    $xml = new DOMDocument();
    
    // Load the url's contents into the DOM (the @ supresses any errors from invalid XML)
    @$xml->loadHTMLFile($url);
    
    // Empty array to hold all links to return
    $links = array();
    
    //Loop through each  and  tag in the dom and add it to the link array
    foreach($xml->getElementsByTagName('a') as $link) {
    //    $links[] = array('url' => $link->getAttribute('href'), 'text' => $link->nodeValue);
		$skip=false;
		$href=$link->getAttribute('href');
		$textContent=$link->textContent;
		if ($isFolder)	 {	
			if (strstr($textContent,'Parent Directry')!='') $skip=true; //link ke parent dir
			if (strstr($href,'/')!='') $skip=true;//link ke . dan .. dan folder
			if (strstr($href,'?')!='') $skip=true;//link sort file
		}
		if (!$skip) $links[] =$link->getAttribute('href');
    }
    
    //Return the links
    return $links;
}

//menggunakan ziparchive 
function createZipFile($snfawal,$nfziphasil,$incudeDir=false){
	$zip = new ZipArchive();
	$filename =$nfziphasil;
	
	if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
		exit("cannot open <$filename>\n");
	}
	$anf=explode(",",$snfawal);
	foreach ($anf as $nf) {
		if ($incudeDir) 
			$zip->addFile($nf);
		else{
			$nfonly = pathinfo($nf, PATHINFO_FILENAME);
			$zip->addFile($nf,$nfonly);
		}
//		$zip->addFromString($nf . time(), "#1 This is a test string added as testfilephp.txt.\n");
	}
	//$zip->addFromString("testfilephp2.txt" . time(), "#2 This is a test string added as testfilephp2.txt.\n");
	//$zip->addFile($thisdir . "/too.php","/testfromfile.php");
	//echo "numfiles: " . $zip->numFiles . "\n";
	//echo "status:" . $zip->status . "\n";
	if ($zip->status) echo "error creating zip file...";
	$zip->close();
}

//menggunakan ziparchive atau pcl
function createZipFile_new($snfawal,$nfziphasil,$incudeDir=false){
	global $lib_path;
	include_once $lib_path."zip/Zip.php";
	
	//$zip = new ZipArchive();
	
	$filename =$nfziphasil;

	$zip= new Zip();
    $zip->zip_start($filename);//1:path tanpa folder
    /*
	$zip->zip_add($directory);
    $zip->zip_add($arr);
    $zip->zip_add($file1);
    $zip->zip_add($file2);
    $zip->zip_end(1);
	*/
	
	
	$anf=explode(",",$snfawal);
	foreach ($anf as $nf) {
		if ($incudeDir) 
			 $zip->zip_add($nf);
    		//$zip->addFile($nf);
		else{
			
			$nfonly = pathinfo($nf, PATHINFO_FILENAME);
			 $zip->zip_add($nf,$nfonly);
    		//$zip->addFile($nf,$nfonly);
		}
//		$zip->addFromString($nf . time(), "#1 This is a test string added as testfilephp.txt.\n");
	}
	//if ($zip->status) echo "error creating zip file...";
	//$zip->close();
	$zip->zip_end(2);
}

function restoreDB($nfrestore){
	$nfrestore="test.sql.zip";
	//jika file zip,maka diextract dulu
	if (substr($nfrestore,-3,3)=='zip') {
		$zip = new ZipArchive;
		if ($zip->open($nfrestore) === TRUE) {
			$zip->extractTo(pathinfo($nfrestore,PATHINFO_DIRNAME));
			$zip->close();
		}
		$nfrestore=substr($nfrestore,-4);
	}
	
	$aline = file($nfrestore);
		$strSql="";
		foreach($aline as $line) {
		   $s="";
		   $skip=false;
		   //jika diawali dengan -- atau kosong maka skip
		   if (substr($line,0,2)=='--') $skip=true;
		   if ($line='') $skip=true;
		   $s.=$line;
		   if (substr($line,-1,1)==';') {
			   $strSql.=$s;
			   $s='';
		   }
		}
		return $strSql;
}

function foreachrecord($sq,$ssqa,$showsql=0){
	global $r; 
	$t="";
	$hq=mysql_query2($sq) or die("err : ". mysql_error()."<br> $sq");
	while ($r=mysql_fetch_array($hq)) {
	
		/*
		$asq=explode(";",$ssqa);
		foreach ($asq as $sqa) {
			if (trim($sqa)=='') continue;
			$sq1=evalGFF($sqa,"sql");
			$mq=mysql_query2($sq1);
			if ($showsql==1) $t.= "<br>$sq1";
			if (!$mq) $t.= "<br>Err : ". mysql_error()."<br> $sq1";
			
		}*/
	$ssqa=str_replace("{","$"."r[",$ssqa);
	$ssqa=str_replace("}","]",$ssqa);
	//	echo "<BR>$ssqa";
	
		eval($ssqa);
		
	}
	return $t;
}

//mencari type field dari sql atau sfield 
function getFieldType($sFld,$nmTabel="") {
	
	$aField=explode(",",$sFld);
	$sfld="";
	$i=0;
	$sNoField="";
		$aType=array();

	$realy=array();
	foreach ($aField  as $fld) {
		$nomorField[$fld]=$i;
		//jika diawali xx, makatidak usah dicari tipenya
		if (substr(strtolower($fld),0,2)=='xx') {
			$aType[$i]="xx";
		} else {
			$aType[$i]="";
			$sfld.=($sfld==""?"":",").$fld;
			$sNoField.=($sNoField==""?"":",").$i;//nomor index dari field keseluruhan
			
		}
		$i++;
	}
	$sq="select $sfld from $nmTabel";
	
	$result = mysql_query2($sq) or die("err getFieldType: ". mysql_error()."<br> $sq");
	$fields = mysql_num_fields($result);
	$rows   = mysql_num_rows($result);
	$table  = mysql_field_table($result, 0);
	//echo "Your '" . $table . "' table has " . $fields . " fields and " . $rows . " record(s)\n";
	//echo "The table has the following fields:\n";

	$anf=explode(",",$sNoField);
	/*
	for ($i=0; $i < count($aField); $i++) {
		$nomorfield=$anf[$i];
		if ($aType[$i]=='') {
			$type  = mysql_field_type($result, $nomorfield);
		}
		$aType[$nomorfield]=$type;
	}
	*/
	$jlhFieldNotXX=count($anf);
	for ($i=0; $i <  $jlhFieldNotXX; $i++) {
		$nomorfield=$anf[$i];
		if ($aType[$nomorfield]=='') {
			$type  = mysql_field_type($result, $i);
			$aType[$nomorfield]=$type;
			//echo "<br>tipe $nomorfield :$type";
		}
	}
	
	//echo implode(",",$aType);
	return $aType;
}

function konversiFormatTgl($isi,$formatlama="ymd",$formatbaru="ymd") {
	if ($formatlama==$formatbaru) return $isi;
	
		//misal d/m/y
		//cari parameter pake / atau -
	$oldisi=str_replace("/","-","$isi//");
	$aisitgl=explode("-",$oldisi);
	//$ak=array("y","m","d");
	$ak=array($formatbaru[0],$formatbaru[1],$formatbaru[2]);
	$newisi="";
	for($f=0;$f<=2;$f++){
		if ($f>0) $newisi.="-";
		$pos=strpos($formatlama,$ak[$f]);
		$newisi.=$aisitgl[$pos];
	}
	$isi=$newisi; 
	return $newisi;
}

//exclude bisa ditambah dengan +op2,
function getQueryString($exclude="",$url=""){	
	global $strget,$strget2;
	$sKey="ref,showHeader,openAjax,op,op2,op3,useJS,contentOnly,idtd";
	if (substr($exclude,0,1)=="+") {
		$sKey.=str_replace("+","",$exclude.",");
	}else {
		if ($exclude!="") 	$sKey=$exclude;
	}
	$sKey=str_replace(" ","",$sKey);//hilangkan spasi	
	$arrRq=array();
	$i=0;
	
	if ($url=='') {
		//ambil darirequest
		foreach ($_REQUEST as $key => $value)  {
			$skip=false;
			if (is_array($value)) 	continue;
			if (array_key_exists($key,$arrRq)) $skip=true;
			if (!$skip) {
				//echo "<br>$i.$key=$value;";
				$arrRq[$key]=$value;
			}
			$i++;
		}
	} else {//jika diisi urlnya
		
		//menghilangkan sebelum ?
		$arq=explode("&",$url);
		foreach ($arq as $sr)  {
			$asr=explode("=",$sr."=");
			$key=$asr[0];
			$value=$asr[1];
			$skip=false;
			if ($asr[1]=='') $skip=true;
			if (strstr($asr[0],"?")!=''){
				$key=substr($asr[0],strpos($asr[0],"?")+1);
			}			
			if ($key=='') $skip=true; 
			if (array_key_exists($key,$arrRq)) $skip=true; 
			
			if (!$skip) { 
				$arrRq[$key]=$value;
			}
			$i++;
		}
	}
	$strget2="";
	//foreach ($_REQUEST as $key => $value)  {
	foreach ($arrRq as $key => $value)  {
		/*if (is_array($value)) {//jika array, diskip dulu 
			continue;
		}
		else {
			*/
			$skip=false;
			$value = mysql_real_escape_string( $value );  
			$value = addslashes($value);  
			$value = strip_tags($value);  	 
			$value = trim($value);  	 
			//echo ">".$key. ' : '.$value.'';  
			$key=trim($key);
			if ($key=='useJS') 
				$skip=true;
			elseif ($key=='') 
				$skip=true;
			elseif  (strstr(",$sKey,",",$key,")!='') 
				$skip=true;
			
			//echo "<br>$key=$value strstr:(".strstr(",$sKey,",",$key,").") skip:$skip";
		
			if (!$skip) {
				//(strstr(",$sKey,",",$key,")=='') &&($key!='useJS')) {
				//if ((strstr(",$exclude,",",$key,")=='') ) {
				$strget2.="&$key=$value";
			}
			//$strget.="&$key=$value";
		//}
		
	}
	//echo "hasil:".$strget2;
	return $strget2;
}

function dirToArray($dir,$includeSubfolder=false) {
	$myDirectory = opendir($dir);
	while($entryName = readdir($myDirectory)) {
		$dirArray[] = $entryName;
	}
	closedir($myDirectory); // close directory

	$indexCount	= count($dirArray);//jumlah file & folder
	sort($dirArray);

	$anf=array();
	for($index=0; $index < $indexCount; $index++) {
		$item="$dir/".$dirArray[$index];
		//$itemweb="$webFolder".$dirArray[$index];
		if ((filetype($item)=='dir') && (!$includeSubfolder)) continue;
		
		$nf=$dirArray[$index];
		$tp=filetype($item);
		$ukuran=filesize($item);
		$anf[]=$nf;
	 }
	 return $anf;

}

//"content1.php?det=ganticombo&tkn="+ntkn+"&defcombo="+hasilForm
//content1.php?det=ganticombo&tkn=WkZjd01FMVVTa0ZsVjBadllqSTRkVmt5T1hSaWJURjZVRmMxZEdOSGJHcEtiazU0WWtReGVscFhlR3haTTFGblltMUdkRmxUUW0xamJUbDBTVWhTYVZwSVFteGpibFo2V1Zkb2FGbFhOR2RrTW1oc1kyMVZaMkZYVW5kYVdFb3hZekpHYjFsWFJuVlFWR005
function gantiCombo(){
	global $sql,$nms,$defcombo,$tkn,$func;
	
	cekVar("tkn,defcombo,func");
	evalToken($tkn);
	//if ($tkn!='') { evalToken($tkn);}
	echo "$sql <br>$nms <br>$func <br>$defcombo";
	echo um412_isicombo6($sql,$nms,$func,$defcombo);

}

function executeSqlfile($nf) {
	$t="";
	$templine = '';
	$filename=$nf;
	$lines = file($filename);
	//echo "hoho.......$lines";
	// Loop through each line
	foreach ($lines as $line) {
		//echo "<br>>$line ....";
		if (substr($line, 0, 2) == '--' || $line == '')		continue;
		
		$templine .= $line;
		if (substr(trim($line), -1, 1) == ';') {
			$t.="<br>$templine";
			mysql_query2($templine);// or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
			$templine = '';
		}
	}
	return $t;
}

function securePwd($pass,$pass2) {
	$pes="";	
	if ($pass!=$pass2) 
		$pes.='Password dan konfirmasi password tidak sama<br>';
	elseif ($pass=='') 
		$pes.='Password tidak boleh kosong<br>';
	else {	
		if (strlen($pass)<8) 
			$pes.='Password minimal 8 huruf<br>';
		
	}
	return $pes;
}


function toRomawi($input_arabic_numeral){
	 if ($input_arabic_numeral == '') { $input_arabic_numeral = date("Y"); } // DEFAULT OUTPUT: THIS YEAR
    $arabic_numeral            = intval($input_arabic_numeral);
    $arabic_numeral_text    = "$arabic_numeral";
    $arabic_numeral_length    = strlen($arabic_numeral_text);

    /*
	if (!ereg('[0-9]', $arabic_numeral_text)) {
return false; }
*/
    if ($arabic_numeral > 4999) {return false; }

    if ($arabic_numeral < 1) {return false; }

    if ($arabic_numeral_length > 4) {return false; }

    $roman_numeral_units    = $roman_numeral_tens        = $roman_numeral_hundreds        = $roman_numeral_thousands        = array();
    $roman_numeral_units[0]    = $roman_numeral_tens[0]    = $roman_numeral_hundreds[0]    = $roman_numeral_thousands[0]    = ''; // NO ZEROS IN ROMAN NUMERALS

    $roman_numeral_units[1]='I';
    $roman_numeral_units[2]='II';
    $roman_numeral_units[3]='III';
    $roman_numeral_units[4]='IV';
    $roman_numeral_units[5]='V';
    $roman_numeral_units[6]='VI';
    $roman_numeral_units[7]='VII';
    $roman_numeral_units[8]='VIII';
    $roman_numeral_units[9]='IX';

    $roman_numeral_tens[1]='X';
    $roman_numeral_tens[2]='XX';
    $roman_numeral_tens[3]='XXX';
    $roman_numeral_tens[4]='XL';
    $roman_numeral_tens[5]='L';
    $roman_numeral_tens[6]='LX';
    $roman_numeral_tens[7]='LXX';
    $roman_numeral_tens[8]='LXXX';
    $roman_numeral_tens[9]='XC';

    $roman_numeral_hundreds[1]='C';
    $roman_numeral_hundreds[2]='CC';
    $roman_numeral_hundreds[3]='CCC';
    $roman_numeral_hundreds[4]='CD';
    $roman_numeral_hundreds[5]='D';
    $roman_numeral_hundreds[6]='DC';
    $roman_numeral_hundreds[7]='DCC';
    $roman_numeral_hundreds[8]='DCCC';
    $roman_numeral_hundreds[9]='CM';

    $roman_numeral_thousands[1]='M';
    $roman_numeral_thousands[2]='MM';
    $roman_numeral_thousands[3]='MMM';
    $roman_numeral_thousands[4]='MMMM';

    if ($arabic_numeral_length == 3) { $arabic_numeral_text = "0" . $arabic_numeral_text; }
    if ($arabic_numeral_length == 2) { $arabic_numeral_text = "00" . $arabic_numeral_text; }
    if ($arabic_numeral_length == 1) { $arabic_numeral_text = "000" . $arabic_numeral_text; }

    $anu = substr($arabic_numeral_text, 3, 1);
    $anx = substr($arabic_numeral_text, 2, 1);
    $anc = substr($arabic_numeral_text, 1, 1);
    $anm = substr($arabic_numeral_text, 0, 1);

    $roman_numeral_text = $roman_numeral_thousands[$anm] . $roman_numeral_hundreds[$anc] . $roman_numeral_tens[$anx] . $roman_numeral_units[$anu];
return ($roman_numeral_text); 
}

function makeToken($v) {
	global $tokenKey;
	global $tokenVer;
	if ($tokenKey=='') $tokenKey="um412@yahoo.com";
	if ($tokenVer==3) {
		$h= urlencode(encod($tokenKey.$v));
	} else {
		$c=new Urlcrypt;
		$h=$c->encode($v);
		//echo "maketoken v4 : $v -> $h ";
	}
	return $h;
}

function makeToken3($v) {
	global $tokenKey;if ($tokenKey=='') $tokenKey="um412@yahoo.com";
	return urlencode(encod3($tokenKey.$v));
}

function getToken($tkn) {
	global $tokenKey;
	global $tokenVer;
	if ($tokenKey=='') $tokenKey="um412@yahoo.com";
	
	if ($tokenVer==3) {
		$ltk=strlen($tokenKey);
		$newtkn=decod($tkn);
		$newtkn=str_replace("#","$",$newtkn);		
		$ltkn=strlen($newtkn);
		$h=substr($newtkn,$ltk,$ltkn-$ltk);
	} else {
		$c=new Urlcrypt;
		$h=$c->decode($tkn);
		//echo "<br>gettoken v4 : $tkn -> $h";
	}
	
	return $h;
}

function getToken3($tkn) {
	global $tokenKey;if ($tokenKey=='') $tokenKey="um412@yahoo.com";
	$ltk=strlen($tokenKey);
	$newtkn=decod3($tkn);
	$newtkn=str_replace("#","$",$newtkn);		
	$ltkn=strlen($newtkn);
	$h=substr($newtkn,$ltk,$ltkn-$ltk);
	return $h;
}

//a=5&b=6 diubah menjadi $a='5';$b='6';
function evalToken($tkn,$showResult=0) {
	global $isOnline;
	$gt=getToken($tkn);
	$h="".$gt.'";';
	$h=str_replace("&",'";$',$h);
	$h=str_replace("=",'="',$h);
	$h="$".$h;
	//globalkan setiap variable
	//$a=4;$b=6; -> global $a;$a=4;global $b;$b=6....
	$avar=explode(";",$h);
	$s="";
	foreach($avar as $var) {
		if ($var!='') {
			$poss=strpos($var,"=");
			$xvar=substr($var,0,$poss);
			$xvar0=substr($var,1,$poss-1);
			$s=$s.="global $xvar;";
			//$s=$s.="<br>$"."_REQUEST['$xvar0']=$h;";
		}
	}
	$h=$s.$h; 
	//echo "eval token $tkn >> $h ";
	$sukses=@eval($h);
	//if ((!$sukses) &&(!$isOnline)) echo " error   $h";
	if ($showResult==1) echo "<br>".$h;
	return $h;
	//return $sukses;
	
}

//a=5&b=6 diubah menjadi $a='5';$b='6';
function evalToken3($tkn,$showResult=0) {
	global $isOnline;
	$gt=getToken3($tkn);
	$h="".$gt.'";';
	$h=str_replace("&",'";$',$h);
	$h=str_replace("=",'="',$h);
	$h="$".$h;
	//globalkan setiap variable
	//$a=4;$b=6; -> global $a;$a=4;global $b;$b=6....
	$avar=explode(";",$h);
	$s="";
	foreach($avar as $var) {
		if ($var!='') {
			$poss=strpos($var,"=");
			
			$xvar=substr($var,0,$poss);
			$xvar0=substr($var,1,$poss-1);
			$s=$s.="global $xvar;";
			//$s=$s.="<br>$"."_REQUEST['$xvar0']=$h;";
		}
	}
	$h=$s.$h;
	$sukses=eval($h);
	//if ((!$sukses) &&(!$isOnline)) echo " error   $h";
	if ($showResult==1) echo "<br>".$h;
	return $h;
	//return $sukses;
	
}

//encod & decod3---------------------------------------------------------------------------------------------------
function validateKeyEncod3(){
	global $keyEncod3;
	if (!isset($keyEncod3)) $keyEncod3="um412@yahoo.com";
	$str="aTuDp%5Fy(KwLm}M2cD3]nNhH8iI9jobBd4e[Eft6g@G7J0kUO_PlAqQr)RsSvVWxXYzZ1";
	
	$keyNew="";
	for ($i=0;$i<strlen($keyEncod3);$i++) {
		$v=substr($keyEncod3,$i,1);
		$v=($i%2==0?strtoupper($v):strtolower($v));
		if (strpos($keyNew,$v)>0) continue;
		$keyNew.=$v;
		$str=str_replace($v,"",$str);
		
	}
	//return $keyNew;
	
	$str=$keyNew.$str;
	return $str;
}


function hex2custom($var,$enc=1){
	$str=validateKeyEncod3();	
	$t="";
	$var.="";
	$length=strlen($var);
	$s="";
	for($i=0;$i<$length;$i++) { 
		$v=substr($var,$i,1);
		if ($enc==1) {
			if ($v=="a") 
				$v1=10;
			elseif ($v=="b") 
				$v1=11;
			elseif ($v=="c") 
				$v1=12;
			elseif ($v=="d") 
				$v1=13;
			elseif ($v=="e") 
				$v1=14;
			elseif ($v=="f") 
				$v1=15;
			else {
				$v1=($v*1);
			}
			$s.=substr($str,$v1,1);
			//if ($i%3==0) 
			$s.=substr($str,40-$v1,1);
			
		} else {
			//decod
			$v1=strpos($str,$v);
			$v1=($v1==10?"a":($v1==11?"b":($v1==12?"c":($v1==13?"d":($v1==14?"e":($v1==15?"f":$v1))))));
			$s.=$v1;
			
		}
	 
	} 
	return $s;
}

function encod3($var) {
	$length=strlen($var);
	$s="";
	for($i=0;$i<$length;$i++) {
		$v1=substr($var,$i,1);
		$v1=ord($v1);
		$v1=dechex($v1);
		$v1=hex2custom($v1,1);
		$s.=($s==""?"":"").$v1;
	} 
	return $s;
}

function decod3($var){
	$length=strlen($var)/4;
	$s="";
	for($i=0;$i<$length;$i++) {
		$v1=substr($var,$i*4,1).substr($var,($i)*4+2,1);//y2m1->ym//
		$v1=hex2custom($v1,2);
		$v1=hexdec($v1);
		$v1=chr($v1);
		$s.=($s==""?"":"").$v1;
	} 
	return $s;
}


//-----------------------UTILITY DB
function changeEngineTable($dbname,$from="INNODB",$to="MyISAM"){
	//SELECT CONCAT('ALTER TABLE ',TABLE_NAME,' ENGINE=MyISAM;') FROM INFORMATION_SCHEMA.TABLES WHERE ENGINE='INNODB' AND table_schema = 'dbacc2_mmm' 
	
	 $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = '$dbname' 
        AND ENGINE = '$from'";

    $rs = mysql_query2($sql);
	$ssql="";
    while($row = mysql_fetch_array($rs))    {
        $tbl = $row[0];
        $ssql.= "ALTER TABLE `$tbl` ENGINE=$to; ";
    }
	//echo $ssql;
	querysql($ssql);
}

function repairTable($tables="*") {
	if ($tables=="*") {
		$tables=array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result)) { $tables[] = $row[0]; }
	}	else {
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	$sql="";			$t="";
	$maxrow=30;	//maxr row per isert				
	$t="";		
	foreach($tables as $table)  {
		$t.="<br>Optimizing & repairing table ".$table."...  ";
		$ok1=mysql_query2('OPTIMIZE TABLE '.$table);
		$ok2=mysql_query2('REPAIR TABLE '.$table);
		$t.=($ok1?"ok":"fail")."/".($ok2?"ok":"fail");
	}
	return $t;
}

function changeCollation($collation="utf8_general_ci",$tables="*") {
	if ($tables=="*") {
		$tables=array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result)) { $tables[] = $row[0]; }
	}	else {
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	//	"ALTER DATABASE dbname CHARACTER SET utf8 COLLATE utf8_general_ci;";

	$sql="";			$t="";
	$maxrow=30;	//maxr row per isert				
	$t="";	
	$t.="<pre>";
	foreach($tables as $table)  {
		$t.="<br>";
		$sq="ALTER TABLE $table CONVERT TO CHARACTER SET utf8 COLLATE $collation;";
        mysql_query2($sq);
		$t.=$sq;
	/*	
	}}
		$h=mysql_query2("select * from $table limit 0,1");
		$i=0;
		while ($i < mysql_num_fields($h)) { 
			$meta = mysql_fetch_field($h, $i);
			$t.="<br>Field: $meta->name";
			echo "<pre>";
			var_dump($meta);
			echo "</pre>";
			$t.="<br>";
			
			$i = $i + 1; 
		}
	*/
	}
	$t.="</pre>";	
	return $t;
}
 
function changeTableType($engine="InnoDB",$tables="*"){
	if ($tables=="*") {
		$tables=array();
		$result = mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result)) { $tables[] = $row[0]; }
	}	else {
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	/* $sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = '$dbname' 
        AND ENGINE <> '$engine'";
	 $rs = mysql_query2($sql);
    while($row = mysql_fetch_array($rs)) {        
	$tbl = $row[0];
	}
	 */
	$t="";
    foreach($tables as $tbl) {
        $sq = "ALTER TABLE `$tbl` ENGINE=$engine;";
        $t.="<br> ".$sq;
		mysql_query2($sq);
    }	
	return $t;
} 