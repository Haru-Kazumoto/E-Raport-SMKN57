<?php
cekVar("addtbopr,addtbview,id,cari,op3"); 
//$rnd=rand(123,983792398);
//echo $sqlall;
$hq=mysql_query2($sqlall);
$currnd=$rnd;
$isiTabel="";
$tidtd='';
//$awalT=$akhirT="";
$clspage="page-landscape";
$maxbr1=20;
$maxbr=20;



//judul tabel\
$jdlTabel="<thead><tr>";
if ($showNoInTable) {
	$jdlTabel.="<th class=tdjudul style='width:30px' width=30 >No.</th>";
}
if ($showOpr==1) {
	$WA=($tbOprPos==2?70:40);
	$jd=($tbOprPos==2?'Aksi':'Pilih');
	$jdlTabel.="<th class=tdjudul style='min-width:$WA"."px' width=70>$jd</th>";
	$lines[]=$jd; 
}

$columns=array();
$nocolumn=array();
$columns[0]=$aField[0];//digunakan untuk json
$nocolumn[0]=0;
$noc=1;//noc:nomor kolom sesuai yang tampil saja
$defICSearchDT="";	
for	($i=0;$i<$jlhField;$i++) {
	$xCek=explode(",",$aCek[$i].",0,0,0,0,0,0,0,0");
	if (($xCek[0]=='F')&&($media!='')) continue;
	if ($aShowFieldInTable[$i]<>'0'){			
		$columns[$noc]=$aField[$i];//digunakan untuk json
		$nocolumn[$noc]=$i;//digunakan untuk json
		
		$jLebar+=$aLebarFieldTabel[$i];
		$acap=explode(">",$aFieldCaption[$i]);		
		$jdlTabel.="<th class='tdjudul wcol_$noc' align=center  >$acap[0]</th>";//style='width:".($aLebarFieldTabel[$i]*30)."px' 

		if (($filterDtField[$i]=='0')||($gKolDT[$i]=='0')||(substr($aShowFieldInTable[$i],0,2)=='xx')||(substr($aField[$i],0,2)=='xx'))
			$isif="0";
		elseif ($filterDtField[$i]=='')
			$isif="$aLebarFieldTabel[$i]";
		else
			$isif="$filterDtField[$i]";
		$noc++;
	}
}

//echo implode(",",$column);

$jdlTabel.="</tr></thead>";


$awalT="<table align=center style='".(isset($wTabel)?"$wTabel":'')."' cellspacing='0' cellpadding='5' border='1' 
		class='tbumum tbcetakbergaris  ".($useDataTable?'table table-striped table-bordered ':'')."'
		id='tbumum"."$rnd' >
		";
$akhirT="</table>";

$br=0;
while ($r=mysql_fetch_array($hq)) {
	$id=$r[$nmFieldID];
	$br++;
	$idt=$identitasRec.$br;
	$rnd2=$rndRec=rand(123,983792398);
	$idtd=$idt.$rnd2;
	$lines=array();
	$troe=($br%2==0?"troddform2":"trevenform2");
	$isiTabel.="<tr id="."$idt class='$troe' >";
	$isIdtdPaced=false;

	if ($showNoInTable) {
		$isiTabel.="<td align='center'>".($lim+$br)."</td>";
		$lines[]=''.($lim+$br).'';
	}
	$tbopr=$tbView='';
	
	 
	 
	
	for($y=0;$y<$jlhField;$y++) {
		$xCek=explode(",",$aCek[$y].",0,0,0,0,0,0,0,0");
		//$nmf=strtolower($aField[$y]);
		$nmf=$aField[$y];
		$vv=$addvv="";
		$xCek=explode(",",$aCek[$y].",0,0,0,0");
		$jenisInput=$aJenisInput[$y];
		$minInput=$aMinInput[$y];
		//if ($br<=1) echo "<br> > $aShowFieldInTable[$y]";
		if ($jenisInput=='V') { //view detail
		
			$addvv="";
			if ($gAddDetail[$y]!="") { 
				$vv=evalGFF($gAddDetail[$y]);
			}
			
			$anmf=explode(",",$nmf);
			$nmfldK=$anmf[3];
			
			
			$nrnd=rand(123,3331);

			$ft="";
			$anft=explode(" ",$anmf[2]);
			$akft=explode(" ",$nmfldK);
			$it=0;
			foreach ($anft as $nft) {
				$k=$akft[$it];
				$fldK=$r[$k];//&$anmf[2]=$fldK
				$ft.="&ft_"."$nft=$fldK";
				$it++;
			}
			
			
			$nfunc="awalEdit($nrnd)";
			$url="index.php?det=$anmf[1]"."$ft&newrnd=$nrnd&isDetail=1";
			$addvv.=" <a href=# class='btn btn-primary btn-mini btn-xs' onclick=\"bukaJendela('$url','$nfunc');return false;\">Detail</a> ";
	 
		} else if (($aShowFieldInTable[$y]!="0") && ($aShowFieldInTable[$y]!="1") ){
			$vv=evalFieldView($y);
		} else if ($aShowFieldInTable[$y]=="0") {
			continue;
		} else { 
			if (($gFieldView[$y]!='') && ((strstr($gFieldView[$y],'createLinkDet')==''))) {
				$vv=evalGFF($gFieldView[$y]); 
			}
			else
			if (substr($nmf,0,4)=='menu') {
				//$isi="='<a href=\'content1.php?det=$det&op=viewmenu&id={id}\' target=_blank >Menu</a>';";
				$acap=explode(">",$aFieldCaption[$y].">menu");
				$isi="='<a href=# onclick=\"bukaAjax(\'content\',\'content1.php?det=$det&op=viewmenu&id={id}\');\"   >$acap[1]</a>';";
				$vv=evalGFF($isi);
			} else 	if ($jenisInput=='F') {//file
				if ($media!='') continue;
				$sf="";
				$sf=createLinkFile($r[$nmf],$pathUpload,$xCek[2]);
				$vv="<center>$sf</center>";		
			} else 	if (($jenisInput=='D') ||($jenisInput=='DT') || (strpos(" ".$nmf,"tgl")>0) ) { 
				$isi=$r[$nmf];
				if ($isi!='') {
					if (strstr($isi,"0000")=='') {
						$isix=$isi;
						if ($jenisInput=='D')
							$isi=date("d M Y",strtotime($isi));
						else
							$isi=date("d M Y h:i:s",strtotime($isi));
						if ($op3=='json')
							$isi="<div style='display:none'>$isix</div> ".$isi;
					} else
						$isi="";
				}
				$vv=$isi ;
			} else 	if ($jenisInput=='C'  ) { 
				$vv=maskRp($r[$nmf]);					
			} else 	if ( ($jenisInput=='T'  )||($jenisInput=='TA'  ) ){ //textrea
				$vv=potong($r[$nmf],50);					
			} else {
				$vv=$r[$nmf];
				$isi=$r[$nmf];
				if ((strpos(" ".$nmf,"foto")>0) && ($isi!='')) {
					$tgb=$idtd.'foto';
					$encgbr=encod($pathUpload2.$isi);
					$isi="
					<img src=".$toroot.$pathUpload2.$isi." style='max-width:100px'>";
				}
				$vv=$isi;
				
			} 
			//vv dievaluasi lagi jika menggunakan createlink...
			if (strstr($gFieldView[$y],'createLinkDet')!='') {	
				eval("$"."vv=$gFieldView[$y];");
				//echo $gFieldView[$y]." <br>";
			}
			
		}//akhir setiap field
		
		$vv.=$addvv;
		if ($aShowFieldInTable[$y]!="0") {			
			$isiTabel.="<td align='$aRataFieldTabel[$y]' >$vv $tidtd &nbsp;</td>";
			
			$lines[]=$vv." ".$tidtd;
			//$lines[]=removetag($vv." ".$tidtd);
			if (!$isIdtdPaced) {
				$isIdtdPaced=true;
				$tidtd='';
			}
		}
	}
	$arrTable[]=$lines;
	$isiTabel.="</tr>";	
} //akhir while

$t.= tampilheadLap($uf=12,$showxls=true,$align="center");

$t.="<div class='$clspage'>";
$t.=$awalT;
$t.=$jdlTabel;
$t.=$isiTabel;
$t.=$akhirT; 
$t.="</div>";
$t.= tampilfootLap();
 

   

if ($showresult) echo $t;

if ($op=='exportxls') {
	//echo "op------------------$op i";
	include $um_path."sql2xls.php";
	exit;
}


include_once "cetak-print-foot.php";


exit;
?>