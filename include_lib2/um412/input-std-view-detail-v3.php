<?php
$idtbd="tbdet_$rnd";

if ($target=='file') $idtbd="tbdet_<?=$"."rnd?>";

	
$jdTbD="<tr>\n";
if ($showNoD)  $jdTbD.="<th class='tdjudul'>No.</th>\n";
$i=0;
foreach ($aFldDCap as $jdl) {
	if (!isset($aFldDCap[$i])) $aFldDCap[$i]=$nmf;
	if (substr($aFldDCap[$i],0,2)=='xx') $gFieldViewD[$i]='0';//kalo diawali dengan xx maka g usah tampil
	if (substr($nmf,0,2)=="xx") $gFieldViewD[$i]='0';
	if ($gFieldViewD[$i]!='0') {
		$jdTbD.="<th class='tdjudul' align='$aAlignFldD[$i]' >$jdl</th>\n";
	}
	$i++;
}

if ($showOprD) $jdTbD.="<th class='tdjudul' align='center'>AKSI</th>\n";
$jdTbD.="</tr>\n";

$rw="";


$isiTbD="";
$brd=0;
$sqTabelDX=str_replace("#id#","$"."id",$sqTabelD);//untuk detail
$sqTabelD=str_replace("#id#","$id",$sqTabelD);
$isik1=$isik2='';//isi kolom di file target

if ($target=='file') {
	//$isiTbD.='$arrColumn=array();'."\n";
	if($showNoD){
		//$isiTbD.='$arrColumn[]=\'$br\';'."\n";
		$isik1.="<td>$"."br</td>\n";
		
		
	}
}

//echo $sqTabelD;
$hd=mysql_query2($sqTabelD);
$jlhD=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$arrTabel=array();
while ($r=mysql_fetch_array($hd)) {
	$rw="<tr>";
	$br=$brd+1;
	$ae=($aAllowEditFldD[$i]=='0'?'enabled ':'');
	$rndx=rand(123,81881);
	$arrColumn=array();
	if ($showNoD) {
		$rw.="<td>$br</td>\n";
		$arrColumn[]="$"."xx=$br;";
		//$isik2.="<td>$"."br</td>\n";
		
		
	}
	$align="";
	$i=0;
	foreach ($aFldD as $nmf) {
		if (substr($nmf,0,2)=="xx") $nmf=substr($nmf,2,100);
			
		if ($gFieldViewD[$i]!='0')  {
			if ($gFieldViewD[$i]!='') {
				$nmf=$gFieldViewD[$i];
			}
			
			$isi='$r["'.$nmf.'"]';
			$align=str_replace("c","center",str_replace("r","right",strtolower($aAlignFldD[$i])));
			
			if ($aClassFldD[$i]=='tgl') {
				$vv='sqltotgl('.$isi.')';
			}
			elseif ($aClassFldD[$i]=='rp') {
				$vv='rupiah('.$isi.')';
				$align="right";
			} else 
				$vv=$isi;
		
			//$rw.="<td $align >".eval($vv.';')."</td>\n";
			if ($nmf=="") {
				$xx='';
			}elseif (isset($r[$nmf])){
				eval('$xx='.$vv.';');
				$jlhD[$i]+=$r[$nmf]*1;
		
			}elseif (substr($nmf,0,2)=="xx")
				$xx='';
			else {
				eval('$xx='.$vv.';');
				$jlhD[$i]+=$r[$nmf]*1;
			}
			$rw.="<td style='text-align:$align' >$xx</td>\n";
			$arrColumn[]=$vv;
			
			if(($target==='file')&&($br==1)){
				//$isiTbD.='$arrColumn[]=\''.$vv.'\';'."\n";
				$isik1.="<td style='text-align:$align' >\".$"."r['$nmf'].\"</td>\n";
				$isik2.='$jlhD['.$i.']+=$r[\''.$nmf.'\'];'."\n";
			}
		} else {
			$arrColumn[]='not shown';
			
		}
		$i++;
	}
	
	if ($showOprD) {
		$rw.="<td> </td>";
		$arrColumn[]='$xx=" ";';
	}
	
	$arrTabel[]=$arrColumn;
	$rw.="</tr>";
	
	if ($target!="file") {
		$isiTbD.=$rw;			
	}
	$brd++;
}
if ($target=="file"){
	$isiTbD.='
	
	$sqTabelD="'.$sqTabelDX.'";
	
	$isiTbD="";
	$brd=0;
	$hd=mysql_query2($sqTabelD);
	$jlhD=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	$arrTabel=array();
	while ($r=mysql_fetch_array($hd)) {
		$br=$brd+1;
		$isiTbD.="<tr>";
		$i=0;
		'.$isik2.
		'
		$isiTbD.="'.$isik1.'";
		
		$isiTbD.="</tr>";
		$brd++;
	}
	echo $isiTbD;
	';
	
}


//footTbD
if (!isset($footTbD)) 
	$footTbD="";
else {
	$i=0;
	foreach ($aFldD as $nmf) {
		if ($target=='file') {
			$footTbD=str_replace("rp(#jlhD$i#)",'<?=rupiah($jlhD['.$i.'])?>',$footTbD);
			$footTbD=str_replace("#jlhD$i#",'<?=$jlhD['.$i.']?>',$footTbD);
			
		} else {
			$footTbD=str_replace("rp(#jlhD$i#)",rupiah($jlhD[$i]),$footTbD);
			$footTbD=str_replace("#jlhD$i#",$jlhD[$i],$footTbD);
		}
		$i++;
	}
}

if ($target=='file') $isiTbD=
	"<?php
	$isiTbD
	?>
	";

$tDetail="
<style>
max-height: 20000px;
overflow: auto;
</style>
<h4 class='titleview'>$nmCaptionTabelD</h4>
<div class='tdetail' ".($op=='view'?'':"style='max-height:none'").">
<table style='width:100%' class='tbdetail tbcetakbergaris ' id='$idtbd'   >
	<thead>
		$jdTbD
	</thead> 
	<tbody id='tbody_$idtbd'>
	$isiTbD
	</tbody>
	<tfoot>
		$footTbD
	</tfoot> 
</table>
</div>
";

$addf.=$addfD;

?>