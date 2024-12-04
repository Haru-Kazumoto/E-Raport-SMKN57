<?php
//membuat file teks
function createGenFile($nfOutput,$t){
	global $rnd,$usegen;
	buatFolder($nfOutput,false);
	$f = fopen($nfOutput, 'w');
	if(!$f) {
		echo 'Can\'t write to file '.$file;
	} else {
		$t=str_replace($rnd,'<?=$rnd?>',$t);
		fwrite($f, $t, strlen($t));
		fclose($f);
		echo "<br>$nfOutput saved...";
		if ($usegen) echo "<br>Please rename file by removing string '-gen' on filename. ";
	}
}

function tbPrint($div,$cap="Cetak"){
	return "<button class='btn btn-success btn-sm noprint' onclick=\"
	$('.noprint').hide();
	
	printDiv('$div');
	$('.noprint').show();
	\">$cap</button>
			";
}

function tampilheadLap($uf=12,$showxls=true,$align="center"){
	global $rnd,$js_path,$media;
	if ($media!="") return "";
	$uf2=$uf+2;
	$t="";
	$urlxls="index.php?".$_SERVER['QUERY_STRING']."&media=xls&contentOnly=1";
	$t.="<link rel='stylesheet' type='text/css' href='$js_path"."style-cetak.css' >";	
	$t.="<div style='padding:0px 0px 10px 0px;text-align:$align'> 
	<input type=button class='btn btn-sm btn-mini btn-success' value='Cetak'   onclick=\"printDiv('tout_$rnd');\" > ";
	if ($showxls) $t.=" <input type=button class='btn btn-sm btn-mini btn-warning' value='XLS'   onclick=\"location.href='$urlxls';\" > ";
	$t.="
	</div>
	<div id='tout_$rnd' class=tout>
	 <style>
		td {
			font-size:$uf"."px  
		} 
		body, #wrapper, .wrapper {
			overflow-y: hidden;
			font-family: Arial, Helvetica, sans-serif;
			font-weight: 400;
			overflow-x: hidden;
			overflow-y: auto;
		}
		
		.page, 
		.page-landscape {
			padding:2cm 1.5cm;
			height:17cm;
		}
		.page-landscape {
			padding:2cm 1.5cm 1.5cm 1.5cm;
		}
		@media print {
			td {
				font-size:$uf2"."px  
			} 
		}
		</style>
	";
	return $t;

}

function tampilFootLap(){
	global $media;
	if ($media!="") return "";
	return "</div>";
}	
	
function addFilterTb($str,$utp="",$addsecureupdtb=0){
	global $sqFilterTabel,$sqSecureUpdateTabel,$userType;
	global $xkdlokasi;
	if (($utp=="")||($userType==$utp)) {
		$sqFilterTabel=$sqFilterTabel.($sqFilterTabel==""?" where ":" and ").$str;
		
		if ($addsecureupdtb==1) {
			$sqSecureUpdateTabel=$sqSecureUpdateTabel." and ".$str;			
		}
		
	}
	//addFilterTb2($str,"userType",$utp=""){
}

function addFilterTb2($str,$jfilter="xx"){
	global $sqFilterTabel;
	if (($jfilter!="")&&("-$jfilter-"!="-0-")) $sqFilterTabel=$sqFilterTabel.($sqFilterTabel==""?" where ":" and ").$str;
		
	//	echo $sqFilterTabel;
}


function gff($g,$aField){
	if (strstr($g,"#")=='') return $g;
	global $r;
	
	//echo "<br> G: $g -> ";
	foreach($aField as $nmf) {
		//if ($nmf
		$ev="
		
		global $"."$nmf;
		if (isset($"."r['$nmf'])) {
			$"."g=str_replace(\"#$"."nmf#\",$"."r"."['$nmf'],$"."g);
		} else
		if (isset($"."$nmf)) {
			$"."g=str_replace(\"#$"."nmf#\",$"."$nmf,$"."g);
		}
		";
		//echo "<br>".$ev;
		eval($ev);
		
	}
	return $g;	
}
function evalGFF($isi,$format='',$nox='',$varRow="r"){
	//validasi untuk tampilan dan show-table
	global $r;
	global $rd;
	global $op;
	global $nmFieldID;
	global $rnd;
	global $rndRec;
	global $idtd;
	global $inp;
	global $nmf;
	global $i;
	global $no;
	global $def;
	//	if (op("itb")) echo "<br>".$isi;
	//ubah  default di itb 
	global $def,$ae,$tid,$rndx,$rndid ;
//	echo "<br>isi $isi ".$r['bytimbang'];
	if (isset($r)) {
		if (is_array($r)) {
			foreach($r as $key=>$value) {
				if (isset($r[$key])) {
					$isi=str_replace("#$key#",$value,$isi);
				}	
			}
		}
	}
	$isi=str_replace("#def#",$def,$isi);
	$isi=str_replace("#$nmf#",$def,$isi);
	$isi=str_replace("#ae#",$ae,$isi);	
	$isi=str_replace("#tid#",$tid,$isi);	
	$isi=str_replace("#rndx#",$rndx,$isi);	 
	$isi=str_replace("#rnd#",$rnd,$isi);	
	$isi=str_replace("#rndRec#",$rndRec,$isi);	
	//$isi=str_replace("#no#",$no,$isi);	
	$isi=str_replace("#rndid#",$rndid,$isi);	
	$isi=str_replace("{"."rnd}",$rnd,$isi);
 
	
	if ($op=='itb') {
		if ($nmf!='')		{
			if (isset($r[$nmf])) 
				$xvv=explode("#",$r[$nmf]."######");
			else 
				$xvv=explode("#","#####");
		}
	}
	if ($nmFieldID!='') {
		if (isset($r[$nmFieldID])) $isi=str_replace("{"."id}",$r[$nmFieldID],$isi);
	}
	
	if (isset($r[$nmf])) {
		$isi=str_replace("#"."$nmf"."#",$r[$nmf],$isi);	
		$isi=str_replace("-{"."$nmf}-","'$r[$nmf]'",$isi);	
		$isi=str_replace("{"."$nmf}",$r[$nmf],$isi);	
	}
	//{nama} -> $r[nama]
	//tanpa petik	
	$rrd="$".$varRow."[" ;
	
	$isi=str_replace("{-",$rrd,$isi);
	$isi=str_replace("-}","]",$isi);
	
	//dengan petik
	$isi=str_replace("-{","$rrd'",$isi);
	$isi=str_replace("}-","']",$isi);

	$isi=str_replace("_{","_<_",$isi);
	$isi=str_replace("}_","_>_",$isi);
	
	$isi=str_replace("{",$rrd,$isi);
	$isi=str_replace("}","]",$isi);
	
	$isi=str_replace("_<_","{",$isi);
	$isi=str_replace("_>_","}",$isi);
	

//	$isi=str_replace("\'",'"' ,$isi);
	//echo "--".$isi ." >";	
	if ($format=='sql') {
		eval("$"."isi2=\"$isi\";");
		$inp=$isi2;
		return $isi2;
	}
	
	 
	if (substr($isi,0,1)=='=') {//jika diawali = 
		$ev="$"."isi"."$isi;";
		//echo "<textarea>$ev</textarea>";
		eval ($ev);
		$inp=$isi;
	} else {
	 
		if (substr($isi,0,4)=='$inp') {//jika diawali = 	
			//echo $isi;
			eval ($isi);
			$isi=$inp;
		} else {
			$isi=str_replace('"',"\\".'"',$isi);
			//$ev='$isi='.$isi.';';
			$ev='$isi='.$isi.';';
			
			$inp=$isi;
			//eval ($ev);
		}
	 }
	return $isi;	
}

//$gFieldView
function evalFieldView($y,$output="value"){
	global $aShowFieldInTable;
	global $gFieldView;
	global $r;
	global $nmf;
	$snmf=$nmf;
						
	$vv="";
	$sfi=$aShowFieldInTable[$y];
	if ($sfi=="1") {
		$vv=$r[$nmf];
	}else if (substr($sfi,0,1)=='.') {
		$vv=$r[$nmf]." ".substr($sfi,1,strlen($sfi));
	}else if (substr($sfi,0,1)=='=') {
		//fungsi
		$vv=evalGFF($sfi);
	} else {
		
		$separator=" ";
		if (strstr($aShowFieldInTable[$y],"/")!='') 
			$separator="/";
		elseif (strstr($aShowFieldInTable[$y],"<br>")!='') 
			$separator="<br>";	
		$aNmf=explode($separator,$aShowFieldInTable[$y]);
		if (count($aNmf)>1) {
			$sfld="";
			foreach($aNmf as $nm) {
				//jika menggunakan nama tabel, misal tbuser.nama
				$anmx=explode(".","$nm.$nm");
				$nmx=$anmx[1];
				$vv.=($vv==""?"":$separator).$r[$nmx];
				$sfld.=($vv==""?"":",'$separator',").$nmx;
			}
			$sfld="concat($sfld)";
		} else {
			$vv=$r[$sfi];	
		}
	}
	
	if ($output=='value') 
		return $vv;
	else
		return $sfld;
}
//baris input
function rowITB($nmField,$scap,$isi,$def='',$stytr='',$addstylecap=''){
	global $rnd,$aDefDLDT,$useDTDD;
	$nmFieldInput=$nmField."_".$rnd;
	$capp=explode("~",$scap."~");
	$cap=$capp[0];
	$cap2=$capp[1];
	
	$isi=str_replace("#def#",$def,$isi);
	
	return " 
	\n<div  id='tr$nmFieldInput'  class='dl form-group'  ".($stytr==''?'':"style='$stytr'")." >
	\n	
	<div class='col-xs-5 col-sm-$aDefDLDT[0]' id='dt$nmFieldInput'$addstylecap>$cap</div>
	<div class='col-xs-12 col-sm-$aDefDLDT[1]' id='dd$nmFieldInput' >
		\n
		$isi".($cap2==""?"":" $cap2")."
		\n</div>
	\n</div>
	";
}

//rowitb3("nama_reg~Nama Reg.#email_reg~Email");
function rowITB3($sFldCap,$lebar=35,$param="~",$jenisITB=""){
	global $scolsm_rowitb,$jenisITB3,$aDefDLDT,$rnd;
	if ($jenisITB!="") $jenisITB3=$jenisITB;
	if (!isset($scolsm_rowitb)) {
		$scolsm_rowitb="4,8";
		$acolsm=explode(",",$scolsm_rowitb);
	} else {
		$acolsm=$aDefDLDT;		
	}
	$acolsm=explode(",",$scolsm_rowitb);
	if ($sFldCap=="") return "";
 
	$cap=$isi="";
	$afc=explode("#",$sFldCap);
	$i=0;
	$nmFieldInput="";
	foreach($afc as $fc) {
		$afd=explode("|",$fc."|||||");
		$anmf=explode($param,$afd[0]);
		$nmFieldAsli=$nmField=$anmf[0];
		if (!isset($anmf[1])) {
			//group
			$cap=$nmFieldAsli;
			$isi="";
			
		} else { 
			
			if ($i==0) 
				$cap=$anmf[1];
			else
				$isi.=" $anmf[1] : ";
			$lb=(!isset($anmf[2])?$lebar:$anmf[2]);
					
			if (strstr($nmField," ")!='') {
				$isi.=$nmFieldAsli;
			} else {
					
				$def="";
				$nmFieldInput=$nmField."_".$rnd;
		 
				if ($i==0) {
					//$cap.=$afd[1];
				} else {
					//$isi.=" $afd[1] : ";
					
				}
				if ($nmField!='') {
					//$lb=($afd[2]==""?$lebar:$afd[2]);
					global $$nmFieldAsli;
					if ((strstr($nmField," ")!='')||($jenisITB3=='view')) {
						
						if (isset ($$nmFieldAsli))
							$isi.=$$nmFieldAsli;
						else 
							$isi.=$nmFieldAsli;
					} else {
						//echo ">".$nmField."<br>";
						if ($nmField!='') {
							eval("global $"."$nmField; $"."def=$"."$nmField;");
							$nmFieldInput=$nmField."_".$rnd;
							$isi.="<input type=text name='$nmField' id='$nmField"."_$rnd' value='$def' size='$lb'>";
						}
					}
				}
				if ($afd[3]!='') $isi.=" ".$afd[3];
			}
		}
		$i++;
	}
	
	if ($isi=="") {
		$t='
			<div class="form-group dl">
				<div class="col-sm-12 subtitleform2" valign=top>'.$cap.'</div>
			</div>
		';
		
	} else {
		$t='
			<div class="form-group dl">
				<div class="col-xs-5 col-sm-'.$acolsm[0].' dt" valign=top>'.$cap.'</div>
				<div class="col-xs-7 col-sm-'.$acolsm[1].' dd tdform2x" valign=top  id="dd'.$nmFieldInput.'" >'.$isi.'</div>
			</div>
		';
	}
	return $t;	
}

function rowITB3_old($sFldCap,$lebar=35,$param="~",$jenisITB=""){
	global $scolsm_rowitb,$jenisITB3,$aDefDLDT,$rnd;
	if ($jenisITB!="") $jenisITB3=$jenisITB;
	if (!isset($scolsm_rowitb)) {
		$scolsm_rowitb="3,9";
		$acolsm=explode(",",$scolsm_rowitb);
	} else {
		$acolsm=$aDefDLDT;		
	}
	$acolsm=explode(",",$scolsm_rowitb);
	if ($sFldCap=="") return "";
 
	$cap=$isi="";
	$afc=explode("#",$sFldCap);
	$i=0;
	foreach($afc as $fc) {
		$afd=explode("|",$fc."|||||");
		$anmf=explode($param,$afd[0]);
		$nmFieldAsli=$nmField=$anmf[0];
		$cap=$anmf[1];
		
		//if (strstr($nmField," ")!='') $nmField=substr($nmField,0,strpos($nmField," "));
		$def="";
		$nmFieldInput=$nmField."_".$rnd;
 
		if ($i==0) {
			$cap.=$afd[1];
		} else {
			$isi.=" $afd[1] : ";
		}
		if ($afd[0]!='') {
			$lb=($afd[2]==""?$lebar:$afd[2]);
			
			if ((strstr($nmField," ")!='')||($jenisITB3=='view')) {
				$isi.=$nmFieldAsli;
			} else {
				eval("global $"."$nmField; $"."def=$"."$nmField;");
				$nmFieldInput=$nmField."_".$rnd;
				$isi.="<input type=text name='$nmField' id='$nmField"."_$rnd' value='$def' size='$lb'>";
			}
		}
		if ($afd[3]!='') $isi.=" ".$afd[3];
		$i++;
	}
	$t='
		<div class="form-group dl">
			<div class="col-xs-5 col-sm-'.$acolsm[0].' dt" valign=top>'.$cap.'</div>
			<div class="col-xs-7 col-sm-'.$acolsm[1].' dd tdform2x" valign=top  id="dd'.$nmFieldInput.'" >'.$isi.'</div>
		</div>
	';
	return $t;	
}

function rowITB2($nmField,$cap,$lebarOrJnsInput=20){
	global $scolsm_rowitb,$aDefDLDT;
	
	if (isset($aDefDLDT))
		$acolsm=$aDefDLDT;		
	else {
		if (!isset($scolsm_rowitb)) {
			$scolsm_rowitb="3,9";
		}
		$acolsm=explode(",",$scolsm_rowitb);
	}
	global $rnd;
	$t="";
	if ($cap=="-") {
		$t.='
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$nmField.'</div>
		</div>
			';
	}elseif ($nmField=="-") {
		$t.='
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$cap.'</div>
		</div>
			';
	} else {
		if (strstr($nmField," ")=='') {
			eval("global $"."$nmField; $"."def=$"."$nmField;");
			$nmFieldInput=$nmField."_".$rnd;
			if ($lebarOrJnsInput=="TA")
				$isi="<textarea name='$nmField' id='$nmField"."_$rnd' rows=3 style='width:100%'>$def</textarea>";
			else
			$isi="<input type=text name='$nmField' id='$nmField"."_$rnd' value='$def' size='$lebarOrJnsInput'>";
		} else {
			$ai=explode("|",$nmField."|xx");
			$isi=$ai[0];
			$nmFieldInput=$ai[1];
		}
		$t.='
		<div class="form-group">
			<div class="col-xs-5 col-sm-'.$acolsm[0].'" valign=top>'.$cap.'</div>
			<div class="col-xs-7 col-sm-'.$acolsm[1].' tdform2x " valign=top  id="dd'.$nmFieldInput.'" >'.$isi.'</div>
		</div>
			';
		
	}
	return $t;	
}

//rowView('cap|width|col-sm-3','isi|width|right|col-sm-8');
function rowView($scap,$sisi,$border=0){
	global $cls1,$cls2,$media,$wtable,$judult,$wcap,$wisi,$noBrPDF;
	
	if (!isset($noBrPDF)) $noBrPDF=true;
	$acap=explode("|",$scap."|||");
	$cap=$acap[0];
	if ($acap[1]!='') $wcap=$acap[1];
	if ($acap[2]!='') 
		$cls1=$acap[2];
	elseif (!isset($cls1))
		$cls1="col-sm-3";
	
	
	
	//class=tdjudul 
	if (!isset($judult)||($judult=="")) 
	$judult="<table border='$border' width='$wtable' >";
	
	$aisi=explode("|",$sisi."||||");
	$isi=$aisi[0];
	if ($aisi[1]!='') $wisi=$aisi[1];
	if ($aisi[3]!='') 
		$cls2=$aisi[3];
	elseif (!isset($cls2))
		$cls2="col-sm-8";
	
	$align=$aisi[2];
	$tt="";
	if ($media=='pdf') {
		//$tt.="#cekpbtb#";
	//	$tt.="</table>$judult";
	}
	$tt.="
	\n".($media=='pdf'&&!$noBrPDF?"<":"")."
		<tr class='troddform2'>
	\n	<td valign='top' ".($wcap==""?"":"width='$wcap'")." class='$cls1' >$cap</td>";
	
	$addT="";
	
	if ($border==0) $addT=":";
	if ($border.$align=='0right') {
		$tt.="<td>:</td>";
		$addT="";
		
	}
	
	$xisi=($addT==":"?"<div style='float:right;width:96%'>$isi</div><div  style='float:right'>$addT</div>":$isi);
	
	$tt.="
	\n	<td valign='top' ".($wisi==""?"":"width='$wisi'")."  align='$align' class='$cls2'  >
	$xisi</td>
	\n</tr>
	";
	
	return $tt;
}

/*
$scolsm_rowitb="3,9";
rowView2("Faktur","-");,$salign="left,left");
rowView2("","Faktur");
rowView2("nofaktur","No. Faktur",$salign="left,left",$usedtdd=false);

*/
function rowView2($nmField,$cap,$salign="left,left",$usedtdd=false){
	global $scolsm_rowitb,$aDefDLDT;
	if (!isset($scolsm_rowitb)) $scolsm_rowitb="3,9";
	$acolsm=explode(",",$scolsm_rowitb);
	$alg=explode(",",strtolower("$salign,$salign"));
	if (isset($aDefDLDT)) {
		$acolsm=$aDefDLDT;		
	}
	
	$aDefDLDT=$acolsm;
	global $rnd;
	$t="";
	if ($cap=="-") {
		$t.='
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$nmField.'</div>
		</div>
			';
	}elseif ($nmField=="") {
		$t.='
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$cap.'</div>
		</div>
			';
	} else {
		eval("global $"."$nmField; $"."def=$"."$nmField;");
		$nmFieldInput=$nmField."_".$rnd;
		$isi=$def;
	 	$t.='
		<div class="form-group">
			<div align="'.$alg[0].'" class="'.($usedtdd?"":"").'col-xs-5 col-sm-'.$acolsm[0].'" valign=top>'.$cap.'</div>
			<div align="'.$alg[1].'" class="'.($usedtdd?"":"").'col-xs-7 col-sm-'.$acolsm[1].' tdform2x " valign=top  id="dd'.$nmFieldInput.'" >'.$isi.'</div>
		</div>';
		
	}
	return $t;	
}
function extractOptInpFile($sOption) {
	$xCek=explode("|",$sOption."|||||||||||||||");
	$ket=$inp=$inpf="";
	
	$jfile=$jfile2=$xCek[2];
	if ($xCek[2]=='I') {
		$jfile="image";
		$inpf.=" data-type='image' accept='$xCek[4]' ";
	}elseif ($xCek[2]=='V') {
		$jfile="movie";
		$inpf.=" data-type='movie' accept='$xCek[4]' ";
	
	}elseif ($xCek[2]=='D') {
		$jfile="document";
		$inpf.=" accept='$xCek[4]' ";
	}
	
	$cap=$xCek[5];
	$multiple=($xCek[6]=="multiple"?true:false);
	
	if ($xCek[3]*1>0) {
		$inpf.=" maxsize='$xCek[3]' ";//data-max-size
		$ket.="Ukuran file maksimal :$xCek[3]";
		$cap.="(<$xCek[3])";
	}
	
	if ($xCek[4]!='') {
		$ket.="File bertipe $xCek[4]. ";
	}
	
	$opt=[
		'jfile'=>$jfile,
		'jfile2'=>$jfile2,
		'maxsize'=>$xCek[3],
		'accept'=>$xCek[4],
		'multiple'=>$multiple,
		
	];
	return $opt;
}

function inpFile($nmField="nffoto",$cap="File",$defNF="",$sOption="||D|2|PDF,PPT,DOCX,DOC,XLS,XLSX|Pilih File",$opr="replace",$allowEdit=true,$jenisTampilan=2){	
	global $rnd,$addrec,$addinpf,$stytr;
	$rndx=genRnd();
	if (!isset($addrec)) $addrec="";
	if (!isset($addinpf)) $addinpf="";
	if (!isset($stytr)) $stytr="";
	$fldrnd=$nmFieldInput=$nmField.$rnd;
	$xCek=explode("|",$sOption."|||||||||||||||");
	$ket=$inp=$inpf="";
	
	$jfile=$xCek[2];
	if ($xCek[2]=='I') {
		$inpf.=" data-type='image' accept='$xCek[4]' ";
		$jfile="image";
	}elseif ($xCek[2]=='V') {
		$inpf.=" data-type='movie' accept='$xCek[4]' ";
	}elseif ($xCek[2]=='D') {
		$inpf.=" accept='$xCek[4]' ";
	}
	
	$cap=$xCek[5];
	$multiple=($xCek[6]=="multiple"?true:false);
	
	if ($xCek[3]*1>0) {
		$inpf.=" maxsize='$xCek[3]' ";//data-max-size
		$ket.="Ukuran file maksimal :$xCek[3]";
		$cap.="(<$xCek[3])";
	}
	
	if ($xCek[4]!='') {
		$ket.="File bertipe $xCek[4]. ";
	}
	
	$rnd2=rand(128831,138381);
	$ct="";
	if ($defNF!='') {
		$ct=createLinkFile($defNF,$pathUpload='',$sft="$xCek[2],50,800",$opr,$cap='',$cls='',$float='',$ket);
		$stytr.="height:55px; overflow:auto; ";
	}

	$addinpf="";
	//$inp.="so $sOption multiple $multiple >";
	if ($allowEdit) {
		//$jenisTampilan=2;
		if ($jenisTampilan==1) {
			$rnd2=rand(128831,138381);                                                    
			$inp.="
			<div style='float:none'>
				$ct
				<span id='t".$rnd2."$nmFieldInput'  style='display:none'>
					<input type=file name=$nmField id=$nmFieldInput  
					onblur=cekUpload('$nmFieldInput') 
					$inpf class='$addrec' >
					<input type=hidden name=x"."$nmField id=x"."$nmFieldInput >						
					
				</span>
				
				<a href='#' onclick=\"t".$rnd2."$nmFieldInput.style.display='inline';
				l".$rnd2."$nmFieldInput.style.display='none';return false;\" 
				id=l".$rnd2."$nmFieldInput >Ubah</a>
				<div style='display:none' id='$nmField"."-err' ></div>
			</div>";

		} else {
			$opc=0;
			//$nmFieldInput
			
			$nmf=$nmField.($multiple>0?"[]":"");
			$yfld=$nmField."_$rndx";
			$nmfInput=$yfld."_#idx#";
			$contoh="
			<div id=t$nmfInput style='position:relative;margin-bottom:3px'>				
				<input type=file 
				name='$nmf' 
				id='$nmfInput'
				class='fileup-inp $addrec' 
				$inpf 
				onchange=cekUpload2('$nmField','$jfile','$rndx','#idx#') 
				title='$ket'
				multiple=$multiple
				>
					
				<input type=hidden name=x$nmf id=x$nmfInput >
				
				<div id=cap$nmfInput class='fileup-cover'
					onclick=\"$('#$nmfInput').click()\">
					<i class='fa fa-folder pull-right' ></i>
					<span id='c$nmfInput' class='pull-left cap' def='$cap' >$cap</span>
				</div>
				
				<div style='display:nonex' id='$nmfInput-err' ></div>
				 
			</div>				
			";
			
			if ($multiple) {
				$inp.="
				<div style='display:none' id=tsample_$yfld >$contoh</div>";
			}
			$inp.="<div id='tupl_$yfld' >".str_replace("#idx#",1,$contoh)."</div>";
			
			
			
		}
	}

	return $inp;
}

//$isi=inpFile($nmField,$cap,$defNF,$sOption);
function inpFile_old($nmField="nffoto",$cap="File",$defNF="",$sOption="||D|2|PDF,PPT,DOCX,DOC,XLS,XLSX",$opr="replace",$allowEdit=true){	
	global $rnd,$addrec,$addinpf,$stytr;
	if (!isset($addrec)) $addrec="";
	if (!isset($addinpf)) $addinpf="";
	if (!isset($stytr)) $stytr="";
	$fldrnd=$nmFieldInput=$nmField.$rnd;
	$xCek=explode("|",$sOption."|||||||||||||||");
	$ket="";
	$inpf="";
	if ($xCek[2]=='I') {
		$inpf.=" data-type='image' accept='image/*' ";
	}
	if ($xCek[3]*1>0) {
		$inpf.=" data-max-size='$xCek[3]' ";
		$ket.="Ukuran file maksimal :$xCek[3] mb";
	}
	if ($xCek[4]!='') {
		$ket.="File bertipe $xCek[4]. ";
	}
	
	$rnd2=rand(128831,138381);
	$ct="";
	if ($defNF!='') {
		$ct=createLinkFile($defNF,$pathUpload='',$sft="$xCek[2],50,800",$opr,$cap='',$cls='');
		$stytr.="height:55px; overflow:auto; ";
	}
	
	$addinpf="";
	if ($allowEdit) {
		$inp="<input type=file name=$nmField id=$nmFieldInput  onblur=cekUpload('$nmFieldInput') 
		$inpf class='$addrec' >
		<input type=hidden name=x"."$nmField id=x"."$nmFieldInput >
		".'<div style="display:none" id="'.$nmField.'-err" ></div>';
		$rnd2=rand(128831,138381);                                                    
		$addinpf="
		<span id=t".$rnd2."$nmFieldInput style='display:none'>$inp</span>
		
		<a href='#' onclick=\"t".$rnd2."$nmFieldInput.style.display='inline';
		l".$rnd2."$nmFieldInput.style.display='none';return false;\" id=l".$rnd2."$nmFieldInput >Ubah</a>
		";
	}
	$inp="<div style='float:none'>$ct $addinpf </div>";
	
	return $inp;
}

//rowITB2File($nmField="nffoto",$cap="File Foto Dokumentasi",$defNF,$sOption="||I|2|GIF,JPG,PNG")	
function rowITB2File($nmField="nffoto",$cap="File Foto Dokumentasi",$defNF="",$sOption="",$replace=true,$allowEdit=true){
	global $scolsm_rowitb,$aDefDLDT;
	if ($sOption=="") $sOption="||I|2|GIF,JPG,PNG";
	if (isset($aDefDLDT))
		$acolsm=$aDefDLDT;		
	else {
		if (!isset($scolsm_rowitb)) {
			$scolsm_rowitb="3,9";
		}
		$acolsm=explode(",",$scolsm_rowitb);
	}
	global $rnd;
	$nmFieldInput=$nmField.$rnd;
	$isi=inpFile($nmField,$cap,$defNF,$sOption,$replace,$allowEdit);
	$t= 	'
		<div class="form-group">
			<div class="col-sm-'.$acolsm[0].'" valign=top>'.$cap.'</div>
			<div class="col-sm-'.$acolsm[1].' tdform2x " valign=top  
			id="dd'.$nmFieldInput.'" >'.$isi.'</div>
		</div>
			';
	return $t;
}	 	

function inpFile2($nmField="nffoto",$cap="File",$defNF="",$sOption="||D|2|PDF,PPT,DOCX,DOC,XLS,XLSX|Pilih file",$opr="replace",$allowEdit=true){	
	global $rnd,$addrec,$addinpf,$stytr;
	if (!isset($addrec)) $addrec="";
	if (!isset($addinpf)) $addinpf="";
	if (!isset($stytr)) $stytr="";
	$fldrnd=$nmFieldInput=$nmField.$rnd;
	$xCek=explode("|",$sOption."|||||||||||||||");
	$ket=$xmax="";
	$inpf="";
	if ($xCek[2]=='I') {
		$inpf.=" data-type='image' accept='$xCek[4]' ";
	}elseif ($xCek[2]=='V') {
		$inpf.=" data-type='movie' accept='$xCek[4]' ";
	}elseif ($xCek[2]=='D') {
		$inpf.=" data-type='movie' accept='$xCek[4]' ";
	}
	if ($xCek[3]*1>0) {
		$xmax=" (max=$xCek[3] mb)";
		$inpf.=" data-max-size='$xCek[3]' ";
		$ket.="Ukuran file maksimal :$xCek[3] mb";
	}
	if ($xCek[4]!='') {
		$ket.="File bertipe $xCek[4]. ";
	}
	$cap=$xCek[5];
	
	$rnd2=rand(128831,138381);
	$ct="";
	if ($defNF!='') {
		$ct=createLinkFile($defNF,$pathUpload='',$sft="$xCek[2],50,800",$opr,$cap='',$cls='');
		$stytr.="height:55px; overflow:auto; ";
	}
	
	$addinpf="";
	if ($allowEdit) {
		$inp="
		<input type=file name=$nmField id=$nmFieldInput  onblur=cekUpload('$nmFieldInput') 
		$inpf class='$addrec' style='position:relative;width:100%;
		-moz-opacity:0,
		filter:alpha(opacity:0);
		opacity:0;
		z-index:2;'>
		
		<input type=hidden name=x"."$nmField id=x"."$nmFieldInput >
		".'<div style="display:none" id="'.$nmField.'-err" ></div>';
		$rnd2=rand(128831,138381);                                                    
		$addinpf="
		<div id=t".$rnd2."$nmFieldInput style='display:nonex'>
		$inp
		<div  style='position:absolute;border:1px solid #ccc;
		width:100%;max-width:350px;
		padding:5px;top:0;left:0;z-index:1;'>
		$cap ($ketmax)
		<i class='fa fa-folder pull-right'></i>
		</div>
		</div>
		
		";
	}
	$inp="<div style='float:none'>$ct $addinpf </div>";
	
	return $inp;
}
	
//rowITB2File2($nmField="nffoto",$cap="File Foto Dokumentasi",$defNF,$sOption="||I|2|GIF,JPG,PNG")	
function rowITB2File2($nmField="nffoto",$cap="File Foto Dokumentasi",$defNF="",$sOption="",$replace=true,$allowEdit=true){
	global $scolsm_rowitb,$aDefDLDT;
	if ($sOption=="") $sOption="||I|2|GIF,JPG,PNG";
	if (isset($aDefDLDT))
		$acolsm=$aDefDLDT;		
	else {
		if (!isset($scolsm_rowitb)) {
			$scolsm_rowitb="3,9";
		}
		$acolsm=explode(",",$scolsm_rowitb);
	}
	global $rnd;
	$nmFieldInput=$nmField.$rnd;
	$isi1=inpFile2($nmField."_0",$cap,$defNF,$sOption="||I|2|image/*|Pilih File Gambar",$replace,$allowEdit);
	$isi2=inpFile2($nmField."_1",$cap,$defNF,$sOption="||V|2|video/*|Pilih File Video",$replace,$allowEdit);
	$isi3=inpFile2($nmField."_2",$cap,$defNF,$sOption="||D|2|application/pdf,text/plain|Pilih File Dokumen",$replace,$allowEdit);
	$t= 	'
	<div class="form-group">
		<div class="col-sm-12" valign=top><b>Tambah Lampiran</b></div>
	</div>
	<div style="padding-left:20px">
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$isi1.'</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$isi2.'</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12" valign=top>'.$isi3.'</div>
		</div>
	</div>
			';
	return $t;
}	 	

//membuat baris suatu tabel rowTB("no#C|nama|nilai#R"
function rowTB($scap,$defAlign="C"){
	
	//class=tdjudul 
	$t="
	\n<tr>";
	$acap=explode("|",$scap);
	for($x=1;$x<=count($acap);$x++) { $defAlign.=",$defAlign"; }
	$ada=explode(",",$defAlign);
	$i=0;
	foreach($acap as $cap) {
		$acc=explode("#","$cap#$ada[$i]");
		$align=($acc[1]=='C'?'center':($acc[1]=='R'?'right':'left'));
		$t.="\n	<td align='$align' >$acc[0]</td>";
		$i++;
	}
	$t.="\n</tr>
	";
	return $t;
}
function groupRowView($cap,$border=0){
	global $i;
	global $gGroupInput ;//$gGroupInput[$i]
	return "
	\n<tr><td colspan=2>
	\n<div id=gri[$i] class=groupinput><h3>$cap</h3></div>
	\n</td></tr>";
}

//function before edit
function addfbe($af,$sop=""){
	global $addfbe;
	$skip=false;
	if ($sop!="") {
		$skip=op($sop)?false:true;
	}
	if (!$skip) $addfbe.=$af.";";
}
function fbe($isi="",$rndx=0,$show=false){
	global $rnd,$newJS,$isTest;
	if ($isTest) $show=true;
	//if ($rndx==0) $rndx=$rnd;
	$sty=" style='display:".($show?'':'none')."'";
	if ($newJS) {
		return "
		<script id=tfbe$rndx $sty>
		 
		$isi
		</script>
		";
		
	} else {
		return "
		<textarea id=tfbe$rnd $sty>
		$isi
		</textarea>
		";
	}
	
}

function fae($isi="",$rndx=0,$show=false){
	global $rnd;
	if ($rndx==0) $rndx=$rnd;
	return "
	<textarea id=tfae$rndx style='display:".($show?'':'none')."'>
	$isi
	</textarea>
	";
	
}
//menambah variabel penyimpanan saat operasi tb atau ed
function addSaveTb($sfld,$sop="tb,ed"){
	global $op,$addSave1,$addSave2,$addSave3;
	if (op($sop)){
		$afld=explode(",",$sfld);
		foreach ($afld as $fld) {
			$s="
			
			global $"."$fld;
			$"."addSave1.=\",$fld\";
			$"."addSave2.=\",'$"."$fld'\";
			$"."addSave3.=\",$fld='$"."$fld'\";
			";
			eval($s);
			
		}
	}
}

function addSave($svar,$sval,$sop="tb,ed"){
	global $addSave1,$addSave2,$addSave3,$op;
	$avar=explode(",",$svar);
	$aval=explode(",",$sval);
	$i=0;
	foreach($avar as $var) {
		if (strstr(",$sop,",",tb,")!='') {
			$addSave1.=",$var";
			$addSave2.=",'$aval[$i]'";
		} 
		if (strstr(",$sop,",",ed,")!='') {
			$addSave3.=",$var='$aval[$i]'";
		}
		$i++;
	}
}
function addSaveD($sfld,$sval,$sop="tb,ed"){
	global $op,$addSaveD1,$addSaveD2,$addSaveD3;
	if (op($sop)){
		
		$afld=explode(",",$sfld);
		$av=explode(",",$sval);
		$i=0;
		foreach ($afld as $fld) {
			$addSaveD1.=",$fld";
			$addSaveD2.=",'$av[$i]'";
			$addSaveD3.=",$fld='$av[$i]'";
			
			$i++;
		}
	}
}
function cekNFCustom(){
	global $det,$op,$custom;
	global $lib_app_path,$toroot,$adm_path;
	$nfC=($op=="itb"?"form":$op);
	$subfd="view";
	$addDet=($custom==""?"$det":"$det-$custom");
	 $nfCustom1=$adm_path."protected/$subfd/$det/$nfC-$addDet.php";	
	$nfCustom2= $lib_app_path."protected/$subfd/$det/$nfC-$addDet.php";	
	//echo $nfCustom2;
	if (file_exists($nfCustom1)) {
		return $nfCustom1;
	}elseif (file_exists($nfCustom2)) {
		return $nfCustom2;
	} else 
		return "";
	
	
}

function tampilTT($sarr1,$infotgl=""){
	$aa=explode("|",$sarr1);	
	$aa1=explode("#",$aa[0]);
	
	$t="<table style='width:100%;margin-top:20px'>";
	if (count($aa)==1) {
		$t.="<tr><td style='width:70%'>&nbsp;</td><td>$infotgl</td></tr>";
		$t.="<tr><td>&nbsp;</td><td>$aa1[0],</td></tr>";
		$t.="<tr><td style='height:50px'>&nbsp;</td><td></td></tr>";
		$t.="<tr><td>&nbsp;</td><td>$aa1[1]</td></tr>";
	} else {
		$aa2=explode("#",$aa[1]);
		$t.="<tr><td style='width:30%'>&nbsp;</td><td style='width:40%'>&nbsp;</td><td style='width:30%'>$infotgl</td></tr>";
		$t.="<tr><td>$aa1[0]</td><td>&nbsp;</td><td>$aa2[0],</td></tr>";
		$t.="<tr><td>&nbsp;</td><td style='height:50px'>&nbsp;</td><td></td></tr>";
		$t.="<tr><td>$aa1[1]</td><td>&nbsp;</td><td>$aa2[1]</td></tr>";
	
	}
	$t.="</table>";
	return $t;
}

function addParamOpr($var,$nilai){
	global $paramOpr,$op,$addCek;
	if ($op!=="xxshowtable") {
		$paramOpr.="&$var=$nilai";
	}
}

function addCekDuplicate($sfld,$ket=""){
	global $nmTabel,$op,$id,$nmFieldID,$addCek;
	$afld=explode(",",$sfld);
	$xs="";
	$xsc="";
	foreach ($afld as $fld) { 
		$ev="global $"."$fld;
		$"."xs.=($"."xs==''?'':'-').$"."$fld;
		$"."xsc.=($"."xsc==''?'':\",'-',\").'$fld';
		
		";
		eval($ev); 
	}
	//if (op("ed,tb")) {
		$sy=(op("tb")?"":" and $nmFieldID<>'$id'");
		$sq="select $nmFieldID from $nmTabel where concat($xsc)='$xs' $sy";
		//$addCek.="<br>".$sq;
		$c=carifield($sq);
		if ($c!='') $addCek.=($ket!=""?"*. $ket":"*. Terdapat duplicate entry untuk field $sfld");
	//}
	
}

//buat tombol pencarian
function showTbCari($fld,$pengenal=''){
	global $rnd;
	eval("global $$fld;");
	
	$t.="<span id='tcari".$pengenal."_$rnd' style='display:none' > $fld </span>
		<span id='tkd".$pengenal."_$rnd'> $fld </span>
		<input type=hidden name=$fld id='kd".$pengenal."_$rnd' value='$fld' > 
		<a class='btn btn-primary btn-sm' onclick=\"tampilDafICD('tcariicd_$rnd',$rnd)\"><i class='fa fa-search'></i></a>
		<span id='ticd_$rnd'>".getICD($diagnosa)."</span>";

	return $t;
}

function getFrmUploader($j="frm"){
	/*
		<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>
		<link rel='stylesheet' href='css/bootstrap.min.css'>
		<link rel='stylesheet' href='css/style.css'>
	*/
	
	$t.="	
	<div class='container' style='width:100%;' align='center'>  
        <div class='file_drag'>  
                Drop Files Here  
        </div>  
    </div>
    <div class='container'>
        <div id='uploaded_file'></div>
    </div>
	
    ";
	
	
	/*
	<script src='js/jquery-2.2.4.min.js'></script>
    <script src='js/bootstrap.min.js'></script>
    <script>
		$(document).ready(function() {
		$('.file_drag').on('dragover', function() {
			$(this).addClass('file_drag_over');
			return false;
		});

		$('.file_drag').on('dragleave', function() {
			$(this).removeClass('file_drag_over');
			return false;
		});

		$('.file_drag').on('drop', function(e) {
			e.preventDefault();
			$(this).removeClass('file_drag_over');

			var formData = new FormData();
			var files_list = e.originalEvent.dataTransfer.files;

			for (var i = 0; i < files_list.length; i++) {
				formData.append('file[]', files_list[i]);
			}

			$.ajax({
				type: 'POST',
				url: 'upload.php',
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				success: function(data) {
					$('#uploaded_file').html(data);
				}
			});
		});

		$('#uploaded_file').on('click', '#del-image', function(e) {
			var fileName = $(this).attr('data-info');

			if ($(this).closest('div .col-md-3').remove()) {
				$.ajax({
					type: 'POST',
					url: 'delete.php',
					data: { file_name: fileName },
					success: function(data) {}
				});
			}
		});
	});
	</script>
	<style>
		.file_drag {
			width: 600px;
			height: 400px;
			border: 2px dashed #ccc;
			line-height: 400px;
			text-align: center;
			font-size: 24px;
		}

		.file_drag_over {
			color: #000;
			border-color: #000;
		}

		#uploaded_file {
			margin-top: 20px;
		}

		.none-border {
			border: 0px solid #ddd;
		}

		#del-image {
			margin-top: 5px;
		}
	</style>
	
	*/
	return $t;
}


function changeValueByClass($def,$cls){
	if ($cls=='tgl') {
		$def=sqltotgl($def);
	} elseif (($cls=='C') ) {
		$def=maskRp($def);
	} elseif (($cls=='C1') ) {
		$def=maskRp($def,0,500);//1 digit
	} elseif (($cls=='C2') ) {
		$def=maskRp($def,0,1000);//2 digit
	} elseif (($cls=='C3') ) {
		$def=maskRp($def,0,1500);//3 digit
	} elseif (($cls=='C4') ) {
		$def=maskRp($def,0,2000);//4 digit
	} elseif (($cls=='CX') ) {
		$def=maskRp($def,0,9999);//flexible digit
	} elseif (($cls=='N') ) {
		$def=maskRp($def,0,0);
		 
	} elseif (($cls=='rp') ) {
		$def=maskRp($def); 
	} else {
		
	}
	//echo "<br> $cls : $def";
	return $def;
	
}

//membuat tampilan link atau thumb dari field bertipe file 
// createLinkFile($snmfile,$pathUpload='',$sft='I,50',$opr="replace",$cap="",$cls='F') 
function createLinkFile($snmfile,$pathUpload='',$sft='',$opr="replace",$cap="",$cls='',$float='',$addket=''){
	global $det,$nmf,$id,$rnd;
	
	if ($cls=='') $cls='btn btn-info btn-xs';
	$aft=explode("|",$sft."|||");
	$filetype=$aft[0];
	$sf='';
	if ($snmfile!='') {			
		$param="/";
		$afile=explode("#",$snmfile);
		
		$ff=1;
		foreach ($afile as $fl){ 
			if ($fl=='') continue;
			$xfl=$pathUpload.$fl;
			
			$span="";
			//jika document
			$linknf='';
			if ($filetype=='I') {
				$linknf=showThumb($xfl,$ukuran=20,$maxw=500);
				
				$param="";
			} else {
				if ($cap=='') $cap="file".$ff;
				$linknf="<a href='$xfl' target='_blank' 
				title='Klik di sini untuk download' 
				class='linkdownload ld_$nmf $cls'>$cap</a>";
				//$linknf.=" $filetype  ";
			}
			
			$rnd2=rand(12371,898772);
			$idgb="idgb".$rnd2;
				
			if (strstr($opr,"delete")!=''){
				 
				$span.="
				<a style='background:#f00;color:#fff;' class='btn btn-danger btn-xs' href=# onclick=\"
				if (confirm('Yakin akan hapus file $fl?')) {
					bukaAjax('x$idgb','index.php?det=$det&op=delgb&aid=$id&nmf=$nmf&gb=$fl&newrnd=$rnd2&tgb=$idgb&pathup=$pathUpload','width:300','selesaiEdit($rnd2)');
				}
				\" ><i class='fa fa-trash-o'>&nbsp;</i>Hapus</a>";
				
			}
			
			$sf.=($sf==''?'':$param)."
			<span id=x$idgb style='display:none'></span>
			<div  class='btn-group' id='$idgb' style='background:#dee;margin:2px;padding:1px;border:1px solid #CCC;$float '>
				$linknf $span 
			</div>";
			
			$ff++;
		}
	}
	return $sf;
}
 
function tpTitlePage($judul,$txtright=false){
	$adds="<ol class='breadcrumb'>
			<!--li><a href='index.php'><i class='fa fa-dashboard'></i> Beranda</a></li-->
			<li class='active'>$txtright</li>
		  </ol>";
		  
	if (strlen($txtright)<2) $adds='';
		  $t="<section class='content-header'>
		  <h1>
			$judul
			<!--small>Preview</small-->
		  </h1>
		  $adds
		</section>";
	return $t;
 
}


function awalForm($nfAct,$nmform="form",$nrnd=0,$detValidasi="" ,$showHide=true) {
	if ($nrnd==0) $nrnd=rand(1232313,9232313);
	$idform="$nmform"."_".$nrnd;
	$nfAct.="&newrnd=$nrnd&contentOnly=1&useJS=2";
	$t="";
	$t.="<div id='ts$idform' style='display:none'></div>
		<form id='$idform' action='$nfAct' method='Post' 
		onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','$detValidasi','selesaiEdit($nrnd);',$showHide);return false;\" 
				style='padding:0px;display:nonex'>
				";
	return $t;
}

function akhirForm($capSubmit="",$cls='primary',$txtTambahan="") {
	$t="";
	if ($capSubmit!="") {
		$t.='
		<div class="form-group">
			<div class="col-sm-3 col-xs-5" valign=top>&nbsp;</div>
			<div class="col-sm-9 col-xs-7" valign=top  id="ddsubmit" >
			<input type=submit value="'.$capSubmit.'" class="btn btn-'.$cls.'">
			</div>
		</div>
			';
	}

	$t.="$txtTambahan</form>";
	return $t;
}


function createLinkDet($sfd){
	global $r;
	global $idtd,$rnd; 
	$rndx=rand(123,93712121);
	$asfd=explode("|",$sfd);
	eval("$"."id=$"."r['$asfd[2]'];$"."cap=$"."r['$asfd[3]'];");
	$url="index.php?det=$asfd[0]&aid=$id&id=$id&op=$asfd[1]&newrnd=$rndx";
	$t="<a href='#' onclick=\"bukaAjaxD('$idtd','$url','width:1000',awalEdit($rndx));return false;\">$cap</a>";
	return $t;
}

//$gFieldView[$i]="createLinkDet2(\"perusahaan|id|idperusahaan|nama_perusahaan|cls|tbperusahaan\");";	
function createLinkDet2($sfd){
	global $r,$vv;
	global $idtd,$rnd; 
	$rndx=rand(123,93712121);
	$asfd=explode("|",$sfd."|||||||||||||||");
	eval("$"."id=$"."r['$asfd[2]'];");
	if (substr($asfd[3],0,1)=="-") {
		$cap=substr($asfd[3],1);
	} else {
		$sc=$asfd[3];
		
		if (isset($r[$sc])) {
			$cap=$r[$sc];
			//eval("$"."cap=($"."asfd[3]=='vv'?$"."vv:$"."r['$sc']);");
		} else {
			$cap=$sc;	
		}
	}
	
	
	$cls=($asfd[4]==""?"":"class='$asfd[4]'");
	if ($asfd[5]!="") {
		$nmf=$asfd[1];
		$inf=$asfd[2];		
		$isi=$r[$inf];
		//if ($isi=="") return "";
		
		$sq="select count(1) from $asfd[5] where $nmf='$isi' ";
		$c=carifield($sq)*1;
		if ($c==0) return "";		
	} 
	
	$url="index.php?det=$asfd[0]&ft_$asfd[1]=$id&newrnd=$rndx";//&op=viewdaf
	$t="<a href='#' $cls onclick=\"$('#$idtd').attr('title','$asfd[4]');
	bukaAjaxD('$idtd','$url','width:wMax,height:hMax','awalEdit($rndx)');return false;\">$cap</a>";
	
	/*
	$url="index.php?det=$asfd[0]&ft_$asfd[1]=$id&newrnd=$rndx&isDetail=1";//&op=viewdaf
	$t="<a href='#' onclick=\"$('#$idtd').attr('title','$asfd[4]');bukaJendela2('$url','awalEdit($rndx)');return false;\">$cap</a>";
	*/
	return $t;
}

function validasiInput($st,$changeSpecialChar=true){	
	$st=str_replace("'","''",$st);
	if ($changeSpecialChar) {
	 $st=htmlspecialchars_decode($st);	
	}
	return $st;
}
 
function validasiInput2($svar,$devalidasi=0){
	$avar=explode(",",$svar);
	$s="";
	foreach ($avar as $var) {
		$s.="global $"."$var;";
		if ($devalidasi==0) {
			$s.="$"."$var=str_replace(\"'\",\"''\",$"."$var);";
			$s.="$"."$var=str_replace(\"'\",\"''\",$"."$var);";
			$s.="$"."$var=str_replace(\"“\",'\"',$"."$var);";
			$s.="$"."$var=str_replace(\"”\",'\"',$"."$var);";
			
		/*
		
			$s.="$"."$var=word2utf($"."$var);";
			$s.="	$"."$var = utf8_encode($"."$var);
					$"."$var = htmlspecialchars_decode($"."$var);
					$"."$var = html_entity_decode($"."$var, ENT_QUOTES, 'UTF-8');
					$"."$var = utf8_decode($"."$var);
				";
		*/
		}	else
			$s.="$"."$var=str_replace(\"''\",\"'\",$"."$var);";
		
	}
	//echo $s."----------------->";
	eval($s);
		
}
function devalidasiInput2($svar){
	validasiInput2($svar,$devalidasi=1);
	
}

//soption=3 (password minimal 3 huruf)
/*
soption="CW03"
 C:harus ada huruf besar dan kecil
 W03: minimal 03 huruf
validasiPassword($v1,$v2,$soption="CW03",$separator='*. ') ;
*/

function validasiPassword($v1,$v2='',$soption="3",$separator='*. ') {
	$pes="";
	
	//if ($v2=='') $v2=$v1;
	if ($v1!=$v2) 
		$pes.="<div>$separator"."Konfirmasi Password tidak sama</div>";
	else {
		$pmin=(($soption*1).""==$soption?$soption:0);//cara lama
		if (strstr($soption,"W")!='') {
			$pos=strpos($soption,"W");
			$pmin=substr($soption,$pos+1,2)*1;
		}
		if (strlen($v1)<$pmin*1) $pes.="<div>$separator"."Password minimal $pmin huruf</div>";
		if ((strstr($soption,"C")!='') &&((strtoupper($v1)==$v1)||((strtolower($v1)==$v1)))) $pes.="<div>$separator"."Password terdiri dari Huruf Besar Kecil</div>";
		
	}
	return $pes;
}	


function evalBrLap($jlhLembarPerLap=1){
	global $br,$maxbr,$maxbr1,$maxbr2,$media,$isi,$clspage,$kop,$jdl,$page;
	
	$maxbr=($page==1?$maxbr1:$maxbr2);
	if ($br>=$maxbr) {
		if ($jlhLembarPerLap==1) {
				$isi.="</table></div>
				".($media=='pdf'?"#pbpdf#":"")."
				<div class='$clspage'>
				$kop
				".$jdl;
		} else {
			for ($l=1;$l<=$jlhLembarPerLap;$l++) {
				$ev="
				global $"."jdl$l,$"."isi$l;
				
				$"."isi$l.=\"</table></div>
				\".($"."media=='pdf'?'#pbpdf#':'').'
				<div class=$clspage>';
				
				$"."isi$l.=$"."kop;				
				$"."isi$l.=$"."jdl$l;
				";
				//echo "<textarea>$ev</textarea>";
				eval($ev);				
			}
		}
		$br=0;
		$page++;
	}
	$br++;
}


function updateStatUser2($stat){
	date_default_timezone_set('Asia/Bangkok');
	$tgls=date("Y-m-d H:i:s");
	if ($stat==2) {
		//autologout
		$tglb=date("Y-m-d H:i:s",strtotime($tgls)-15*60);
		$sq= "update tbsiswa set statuser='0' where lastclick<'$tglb'  ";
		mysql_query2($sq);
		return "";
	}
	
	global $userid;
	if (usertype("guru")) {
		$tbuser="tbguru";
		$nmfield="vuserid";
		
	} elseif (usertype("siswa")) {
		$tbuser="tbsiswa";
		$nmfield="nisn";
	} else {
		$tbuser="tbuser";
		$nmfield="vuserid";
		return false;
	}
	$sq="update $tbuser set statuser='$stat',lastclick='$tgls' where $nmfield='$userid' ";
	mysql_query2($sq);
}


/*adminlte*/
function tpBoxTitle($judul="",$txtright="") {
	return tpTitlePage($judul,$txtright);
}

function tpBoxInput($isi,$judul='',$option="danger",$txtright='-',$cls='box-input1'){
	$t='';
	if (($judul!='') && ($judul!='nobread')){
		$t.=tpTitlePage($judul,$txtright);
	}
	$t.="<section class='content $cls'>
	<div class='box box-$option'>
				<!--div class='box-header'>
				  <h3 class='box-title'>Input </h3>
				</div-->
				<div class='box-body'>
				$isi
				</div>
				<!-- /.box-body -->
			  </div>
			  </section>";
	return $t; 
}

class tpBoxInput2 {
	public $id='';
	public $addCls='';
	
	public $clIcon='';
	public $clBody='';
	public $background='';//image
	public $bgColor='';
	public $icn='ion ion-ios-gear-outline';
	public $title='CPU Traffic';
	public $body="";
	public $link="";
	public $target='content-wrapper';
	public $sconfig="width:1000";
	public $useAjaxD=false;
	public $moreinfo="More Info";
	public $boxTools="";	
	public $img="";
	public $jenisBox=3;
	public $colmd=4;
	
	/*contoh
	
		$a=new tpBoxInput2();
		$a->init("$jud","Pembelajaran","$nficon","","blue");
		$a->link=$link2;
		$a->jenisBox=4;//2-4
		$a->col=4;//col-sm-4
		$a->jenisBox=3;//jenis tampilan
		$t2.= $a->show();

	*/
	
	function init($body="",$title="",$icn="",$clIcon="",$clBody="") {
		if ($title!='') $this->title=$title;
		if ($icn!='') $this->icn=$icn;
		if ($clBody!='') {
			$this->clBody=$clBody;
		} else {
			$this->clBody=($this->jenisBox==2?"":"aqua");
		}
		if ($clIcon!='') { 
			$this->clIcon=$clIcon;
		} else {
			$this->clIcon=($this->jenisBox==2?"aqua":"");	
		}
		if ($body!='') $this->body=$body;
		
	}
	
	function show() {
		global $rnd;
		if ($this->id=="") $this->id="tpb2_".rand(1231,423317);
		$body=$this->body;
		$clIcon=$this->clIcon;
		$clBody=$this->clBody;
		$title=$this->title;
		$jlhkol=$this->colmd;
		if ($this->img=='') {
			$icn="<i class='$this->icn'></i>";
			$padding=22;
		} else {
			//$thumb=createThumb($this->img);
			$thumb=$this->img;
			$w=($this->jenisBox==2?60:80);
			$icn="<img src='$thumb' width=$w class='img-circle' style='background:#fff;padding:5px'/>";
			$padding=10;
		}
		$adds="";
		$addcls=$this->addCls;
		
		if ($this->link!="") {
			$this->link.="&newrnd=$rnd";
			$func="awalEdit($rnd)";
			if ($this->target!='') {
				if ($this->useAjaxD) {
					$tt=removeSpecialChar($title);
					$adds.=" onclick=\"bukaAjaxD('$this->target','$this->link','$this->sconfig,title:\'$tt\'','$func');\" ";	
				} else {
					$adds.=" onclick=\"bukaAjax('$this->target','$this->link',0,'$func');\" ";
				}
			} else {
				$adds.=" onclick=\"location.href='$this->link;'\" ";
			}
			$addcls.=' pointer';
		}
		
		$addsty='';
		if ($this->background!='') {
			$addsty.="
			background:url($this->background) $this->bgColor;
			background-size: cover;
			background-position:top right
			";
			$icn='';
		}
		elseif ($this->bgColor!='') {
			$addsty.="
			background:$this->bgColor;";
		}

		$sty='';
		if ($addsty!='') {
			$sty="style=\"$addsty\" ";
		
		}

		if ($this->jenisBox==0) {
			//equivalen dengan tpboxinput(
			$t.="<section class='content $addcls '>
				<div class='box box-$clBody'>
				<!--div class='box-header'><h3 class='box-title'>Input </h3></div-->
				<div class='box-body'>
				$body
				</div> <!-- /.box-body -->
			  </div>
			  </section>";
			return $t; 
		
		} elseif ($this->jenisBox==1) {
			$adds='';
			/*
			$adds="<ol class='breadcrumb'>
			<!--li><a href='index.php'><i class='fa fa-dashboard'></i> Beranda</a></li-->
			<li class='active'>$txtright</li>
		  </ol>";
			if ($txtright=='-') $adds='';
			*/
			$t="<section class='content-header title-$addcls'>
				  <h1>
					$title
					<!--small>Preview</small-->
				  </h1>
				  $adds
				</section>
			";

			$t.="
			<div class='box box-$clBody $addcls' $sty id='$this->id'>
				<div class='box-header'>
				  <h3 class='box-title'>$title </h3>
				</div>
				<div class='box-body'>
					$body
				</div>
			  </div>
				";
			return $t; 
		} elseif ($this->jenisBox==2) {
			$t="
			<div class='col-md-$jlhkol col-sm-6 col-xs-12 $addcls' $adds $sty id='$this->id' >
			  <div class='info-box bg-$clBody'>
				<span class='info-box-icon bg-$clIcon' style='padding:$padding"."px'>
					$icn
				</span>

				<div class='info-box-content'>
				  <span class='info-box-text'>$title</span>
				  <span class='info-box-number'>$body</span>
				</div>
				<!-- /.info-box-content -->
			  </div>
			  <!-- /.info-box -->
			</div>
			";
		} elseif ($this->jenisBox==3) {
			if (strstr($title,"|")!='') {
				$atitle=explode("|",$title."|");
				$h="<h4>$atitle[0]</h4><h3>$atitle[1]</h3>";              
			} else {
				$h="<h3>$title</h3>";
			}			
			$t="
			<div class='col-lg-$jlhkol col-xs-12 $addcls' $adds id='$this->id'  >
			  <!-- small box -->
			  <div class='small-box bg-$clBody' $sty >
				<div class='inner'> 
				  $h
				  <p>$body</p>
				</div>
				<div class='icon bg-$clIcon' style='top: 10px;'>
				  $icn
				</div>
				<a href='#' class='small-box-footer'>
				  $this->moreinfo <i class='fa fa-arrow-circle-right'></i>
				</a>
			  </div>
			</div>";
			
		} elseif ($this->jenisBox==4) {
			$h=$title;
			$t="
			<div class='info-box  $addcls' id='$this->id'  >
            <span class='info-box-icon bg-$clIcon'>
				<i class='$icn'></i>
			</span>
		
            <div class='info-box-content'>
              <span class='info-box-text'>$h</span>
              <span class='info-box-number'>$body</span>
            </div>
            <!-- /.info-box-content -->
          </div>
		  ";
		} else  {//($this->jenisBox==5)	
			$h=$title;
			//$clBody="success";
			if ($this->boxTools=="remove") {
				$this->boxTools="<button type='button' class='btn btn-box-tool' data-widget='remove'>
						<i class='fa fa-times'></i></button>
						";
			}
			
			$t="
			<div class='box box-$clBody box-solid $addcls ' style='margin:5px 0px;$addsty' id='$this->id'  >
				<div class='box-header with-border'>
					<h3 class='box-title' $adds  >$h</h3>

					<div class='box-tools pull-right'>
						$this->boxTools
					</div>
		
				</div>
				<!-- /.box-header -->
				<div class='box-body' $adds >
				  $body
				</div>
				<!-- /.box-body -->
			  </div>
		  ";
		}
		return $t;	
	}	//function 
		
}

function capTpFile($tpf) {
	$t=$tpf;
	$t=str_replace("G","Gambar ",$t);
	$t=str_replace("I","Gambar ",$t);
	$t=str_replace("V","Video ",$t);
	$t=str_replace("D","Dokumen ",$t);
	$t=str_replace("A","Audio ",$t);
	$t=str_replace(" ","/",trim($t));
	$t=str_replace("|","/",trim($t));
	$t=str_replace("//","/",trim($t));
	return $t;	
}

/*
menampilkan file upload dengan tipe file2, urutan:gambar"i,video:v,dokumen:d
tampilan:i:icon,t:teks,b:button,o:original file
*/
//pasangan dari inpfile
function showNFFile2($nmfld,$path="upload/kreag/",$cap="Tambahan Materi",$jfile='d',$allowDel=false,$tampilan='o'){
	global $toroot;
	$jfile=strtolower($jfile);
	$t="";
	$ada=false;
	$anfa=explode(",",$nmfld);
	$jlhfile=count($anfa);
	$ticn=$torigin=$tteks=$tteksbtn="";
	if ($jfile=='btn')
		$cls="class='btn btn-primary btn-xs'";
	else
		$cls='';
	
	$b=$jfile;
	$i=0;
	foreach($anfa as $nfm) {
		//$t.="<br>file $nfm ";
		$tx="";
		$ketemu=false;
		if (trim($nfm)=='') 
			$ketemu=false;
		else {
			$nf=$path.$nfm;
			if (file_exists($nf)) $ketemu=true;
		}
		
		if ($ketemu) {
			if ($jfile=="i") {
				//gambar
				if ($tampilan=='o')
					$tx.="<div class='imgup'> <img src='$nf' style='max-width:100%'></div>";
				else {
					$tx=showThumb($nf,200,1200);
					//$tx.="<div class='imgup'> <img src='$nf' style='max-width:100%'></div>";
				}
			}
			elseif ($jfile=="v") {
				//video
				//$t.="<br><br>File 2 (video):<br> 
				$tx.="
				<div class='videoup'>
				<video width='320' height='240' style='width:100%' controls>
				  <source src='$nf' type='video/mp4'>
				Your browser does not support the video tag.
				</video> 
				</div>
				";

			} else {
				$jfile='d';
				$xcap="Download".($jlhfile>1?" ".($i+1) :"");
				$tx.="<a href='$nf' target=_blank class='btn btn-primary btn-xs ' >$xcap</a>";
				
			}
			
			$ada=true;
			//$allowDel=true;
			if ($allowDel) {
				$rndx=genRnd();
				$idf='idf'.$rndx;
				global $vidusr;
				$tkn=makeToken("nf=$nf&op=del&nr=$rndx&uid=$vidusr");
				$icn="<i class='fa fa-trash' title='Hapus File' ></i>";
				//style='position:relative;z-index:5;left:-20px;'
				if ($jfile=='d') {
					$cls='btn btn-danger btn-xs tbdel-fileup-dok';
					
				} else 
					$cls='tbdel-fileup pull-right bg-red img-circle';
				
				$tbdel="
				<a class='$cls' href='#' onclick=\"if (confirm('Yakin akn hapus file ini?')) { bukaAjax('','index.php?det=file&cO1&tkn=$tkn&newrnd=$rndx&a=1',0,'evalHapusFile(#response#,$rndx);');};return false;\">
				$icn
				</a>";
				
				if ($jfile=='d') {
					
					$tx="
					<span id='$idf'>
						$tx $tbdel 
					</span>";
				} else {
						$tx="
					<span id='$idf'>
						$tbdel <span class='fileup' >$tx</span>
					</span>";
				}
			}
			$torigin.=$tx;
		}
		$i++;
	}
	
	/*
	if (!$ada){
		if (usertype('guru')) $t.="<br>File $cap bisa ditambahkan jika diinginkan.";	 
	}
	*/
	if ($ada){
		$t.="<div class='form-group'><div class='capfile capfile$jfile ' >$cap :</div><div class='isifile'> $torigin</div></div> ";	 
	} else {
		$t.=$torigin;
	}
	
	return $t;
}

function createBtn($cap='Hapus',$cls='danger btn-xs pull-right',$onc='') {
	$tb="<a href='#' onclick=\"$onc;return false \" class='btn btn-$cls'>$cap</a>";
	return $tb;
}

//$url=det=$det&idtd=$idtd&jsconfirm=1&jspilih=1&newrnd=$rnd&rndinput=$rndinput&jsmode=window
function createBtnOpr($det,$url,$cap="test",$cls='btn btn-sm btn-warning',$conf='width:wMax'){
	global $rnd,$rndInput;
	$title='';
	//tbopr2($rnd,'det=$det&idtd=$idtd&jsconfirm=1&jspilih=1&newrnd=$rnd&rndinput=$rndinput&jsmode=window','width:wMax');

	$onc="tbOpr2($rnd,'$url','$conf');";
	return 	createBtn($cap,$cls,$onc); 
}

//$isi=changeFormat3($isi,'N');
function changeFormat3($isi,$format,$blankIfNotInCriteria=false){
	global $vPathUpload,$media,$nmf,$xCek,$nmFieldInput,$aCek,$y,$op3,$idtd;
	$jenisInput=$format;
	$vv=$isi;
	if ($jenisInput=='L') {//link url
		$vv="<center><a href='$isi' target='_blank' >$isi</a></center>";		 	
	} else if ($jenisInput=='Y') {//link youtube
		$isipy=showIconYoutube($isi,$nmFieldInput,true);
		$vv="<center>$isipy</center>";		 
	} else if ($jenisInput=='F') {//file
		if ($media!='') {
			$vv="<center>ini media : $media</center>";	
		} else {
			$cap='';
			$isImage=false;
			if (((strpos(" ".$nmf,"foto")>0) || (strpos($aCek[$y],"I")>0)) && ($isi!='')) {
				$isImage=true;
			}
			
			if ($isImage) {
				$tgb=$idtd.'foto';
				$nmfile=$vPathUpload.$isi; 
				$nfThumb=createThumb($nmfile) ;
				$cap="<img src='$nfThumb' style='max-width:100px'>";
				
			} 
			//// createLinkFile($snmfile,$pathUpload='',$sft='I,50',$opr="replace",$cap="",$cls='F') 
			$sft=($isImage?"I":"");
			//$sf=createLinkFile($snmfile=$isi,$vPathUpload,$sft=$xCek[2],$allowDelete=false,$cap,$cls='');
			$sf=createLinkFile($snmfile=$isi,$vPathUpload,$sft,$allowDelete=false,$cap,$cls='');
			$vv="<center>$sf</center>";		 
		}
		
	} else 	if (($jenisInput=='D') ||($jenisInput=='DT') || (strpos(" ".$nmf,"tgl")>0) ) { 
		if ($isi!='') {
			if (strstr($isi,"0000")=='') {
				$isix=$isi;
				if ($jenisInput=='D')
					$isi=date("d M Y",strtotime($isi));
				else
					$isi=date("d M Y H:i:s",strtotime($isi));
				if ($op3=='json')
					$isi="<div style='display:none'>$isix</div> ".$isi;
			} else
				$isi="";
		}
		$vv=$isi ;
	} else 	if ($jenisInput=='C'  ) { 
		$vv=maskRp($isi,0,2);					
	} else 	if ($jenisInput=='C1'  ) { 
		$vv=maskRp($isi,0,1);					
	} else 	if ($jenisInput=='C2'  ) { 
		$vv=maskRp($isi,0,2);					
	} else 	if ($jenisInput=='CX'  ) { 
		$vv=maskRp($isi,0,9999);					
	} else 	if ($jenisInput=='N'  ) { 
		$vv=maskRp($isi,0,0);					
	} else 	if ( ($jenisInput=='T'  )||($jenisInput=='TA'  ) ){ //textrea
		$vv=potong($isi,50);					
	
	} else {
		//tidak masuk kriteria
		if ($blankIfNotInCriteria) $vv='blank';
		
	}
	return $vv;
}


function buatBtn($arr) {
	//	return "<a href='#' class='btn btn-xs btn-mini btn-primary' title='Hitung Ulang Stock' onclick='return false;'>Hitung Ulang</a>";
	if (!isset($arr['cap'])) $arr['cap']="OK";
	if (!isset($arr['cls'])) $arr['cls']="btn btn-primary";
	if (!isset($arr['addcls'])) $arr['addcls']="";
	if (!isset($arr['url'])) $arr['url']="index.php";
	if (!isset($arr['target'])) $arr['target']="content-wrapper";
	if (!isset($arr['dialog'])) $arr['dialog']=true;
	if (!isset($arr['opsi'])) $arr['opsi']='';
	if (!isset($arr['func'])) $arr['func']='';
	
	if ($arr['dialog']) {
		$funcD="bukaAjaxD";
		if ($arr['opsi']=="") $arr['opsi']='width:wMax';
	} else {
		$funcD="bukaAjax";
		if ($arr['opsi']=="") $arr['opsi']='0';
	}
	if ($arr['target']=='_blank') {
		$href=$arr['url'];
		$onc='';
		$t="<a href='$href' target='_blank' ";
	} else {
		$href='#';
		$url="$arr[url]&contentOnly=1&useJS=2";
		$onc=" onclick=\"$funcD"."('$arr[target]','$url','$arr[opsi]','$arr[func]');return false;\" ";
		$t="<a href='$href' target='_blank' $onc ";
	}	
	$t.=" class='$arr[cls] $arr[addcls]' ";
	$t.=">$arr[cap]</a>";
	return $t;
}

function createLinkTbUnggah($capTbUnggah='Import') {
	$rndx=genRnd();
	global $addTxtInfoExim,$hal,$rnd,$addInpImport,$det,$nmCaptionTabel;
	
	if ($addInpImport!='')  {
		$addInpImport="
		<tr>
			<td> 
				$addInpImport
			</td>
		</tr>
		";		
	}	
	
	$fae='';
	$linkUnduh="$hal&op=unduhformat&outputto=csv&newrnd=$rndx";	
	$linkUnduhFormat=$linkUnduh;
	$idForm="fimp_$rndx";
	$nfAction="$hal&op=importcsv&newrnd=$rndx";
	if (!isset ($nfCSV)) $nfCSV="import_$det";

	//if (!$isTest) $fae="selesaiEdit($rnd);"; 
	//tutupDialog2(\"tfinput$rnd\");

	$fae='';
	$asf="onsubmit=\"uploadImportCSV($rndx);return false;\" ";
	$urlformat="$linkUnduhFormat&delim='+encodeURI($('#delim_$rnd').val()+'";
	
	$linkTbUnggah="	
	<a class='btn btn-success btn-mini btn-sm' 
	href=# onclick=\"$"."('#tfinput$rndx').css('display','block');$"."('#tfinput$rndx').dialog({width:600});return false;\" 
	value='Import Data ".str_replace("Daftar","",$nmCaptionTabel)."'> 
	&nbsp;&nbsp;<i class='fa fa-upload'></i> $capTbUnggah </a> 
	<div id='tfinput$rndx' style='display:none;padding:10px'>
	<div id=ts"."$idForm align=center></div>
	<form id='$idForm' action='$nfAction' method=Post $asf  >
		<table align=center style='margin-top:0px'>
		"."
		<!--tr><td>
		Delimiter : ".um412_isicombo6("R:Koma,Titik Koma","delim")."
		</td></tr-->
		<input type=hidden name=delim value='R'>
		$addInpImport
		
		<tr>
			<td>
			<div style='display:none' id=tfae$rnd>bukaAjax('tcari_$rnd','index.php?ref=$det&det=$det&contentOnly=1&useJS=2&delim='+encodeURI($('#delim_$rnd').val()),0);</div>	
			<input type=button value='Unduh Format' class='btn btn-primary btn-mini btn-sm ' onclick=\"bukaJendela('$urlformat'));\" >
			<!--input  type=file name=nff id=nff$rnd   
			onchange=\"a=$"."('#nff')[0].files[0].name;$"."('#nffx$rndx').html(a); \"  -->
			<span id=nffx$rnd style='display:none'></span>
				<input type=file name=nff id=tbnff$rnd size=1 class='btn-unggah-dt btn btn-info btn-mini btn-sm'  
					onchange=\"a=$"."('#nff')[0].files[0].name;$"."('#nffx$rnd').html(a);\">
				<span id=nffx$rnd ></span>
			</td>
			<td style='width:100px;' align=left>
				<input type=submit value='$capTbUnggah' class='btn btn-warning btn-mini btn-sm btn-block ' style='margin-left:10px;margin-top:2px' >						
			</td>
		</tr>
		<tr>
			<td colspan=2><br>
				Catatan : <br>
				<li>Gunakan format CSV, silahkan klik unduh format </li>
				".(isset($addTxtInfoExim)?$addTxtInfoExim:'')."
			</td>
		</tr>
		</table>
	</form>
	</div>
	
	<span id=tbr></span>
			";
	return $linkTbUnggah;
}

function setRefreshTb($tb) {	
	$rnd=date('mdHi');
	if (carifield("select awalan from tb1kode where awalan='$tb'")=="") {
		 $sq="insert into tb1kode set noakhir='$rnd' ,awalan='$tb' ";
			
	} else {
	 $sq="update tb1kode set noakhir='$rnd' where awalan='$tb' ";
	}
	mysql_query2($sq);
	return $rnd;
}
function getRefreshTb($tb) {
		return carifield("select noakhir from tb1kode where awalan='$tb' ")*1;
}