<?php
//input-std-tb-detail
if (!isset($jlhDefRowAdd)) $jlhDefRowAdd=0;
if (!isset($showTbAddRow)) $showTbAddRow=true;
if (!isset($sAllowEditFldD)) $aAllowEditFldD=explode(",",$sAllowEditFldD);
if (!isset($gFieldInputD)) $gFieldInputD=explode(",", ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

/*
$nmTabelD='tbbayard';
$nmTabelDAlias='d';
$fldKeyM='notrans';
$fldKeyForeign='notrans';
$fldKeyD='id';
$sFldD="kdjbayar,jlhuang,ket";
$sFldDCap="Jenis Pembayaran,Jumlah,Keterangan";
$sLebarFldD="70,70,220,70";
$sClassFldD=",rp,,,,,,,,,,,,,,,,,";
$sAlignFldD=",,,,,,,,,,,,,,,,,,";
$sAllowEditFldD=",,,,,,,,,,,,,,,,,,";
$jlhDefRowAdd=1;

$sqTabelD="select $nmTabelDAlias.* from $nmTabelD $nmTabelDAlias 
inner join $nmTabel $nmTabelAlias on $nmTabelDAlias.$fldKeyD=$nmTabelAlias.$fldKeyM 
where $nmTabelAlias.$nmFieldID='$id'";
*/
$rndid=rand(1234,4121);
$tid="tid_$rndid";//tinputdetail

$sqTabelD=str_replace("#id#","$id",$sqTabelD);
$jdTbD="<tr id='tsdet_$rnd"."_0' >\n";
$i=0;
foreach ($aFldDCap as $jdl) {
	$dn="";
	if (substr($aFldDCap[$i],0,2)=="xx") $dn="display:none";
	$jdTbD.="<th class='tdjudul' style='$dn'>$jdl</th>\n";
	$i++;
}

if ($showOprD) $jdTbD.="<th class='tdjudul'>AKSI</th>\n";
$jdTbD.="</tr>\n";

$sampleRow='';
$rw="";
$avv=array();
$vvidd="<input type=hidden name=d_id[] id=d_id_#rnd#_#no# value='#id#'>";
$vvidd.="<div style='display=none' id=tdd#rndx# ></div>";

$jfldD=count($aFldD);
$i=0;
//echo "update lho";
//var_dump($aFldD);
foreach ($aFldD as $nmf) {		
	$ae1=($aAllowEditFldD[$i]==='0'?'disabled':""); //mutlak tidak boleh
	$ae2=(($aAllowEditFldD[$i]==='2') && ($isEdit))?'disabled2':""; //hanya tidak boleh saat edit, saat tambah boleh
	
	if (isset($gFieldFuncD[$i])) $gFuncFldD[$i]=$gFieldFuncD[$i];
	$fncf=$gFuncFldD[$i];
		
	if ($gFieldInputD[$i]!='') {
		$vv=$gFieldInputD[$i]; 
		$vv=evalGFF($vv); 
	} else {		
		if ($ae1!='') {
			
			$vv=" <input type=text id=dv_$nmf"."_#rnd#_#no#  name=dv_$nmf"."[] style='width:$aLebarFldD[$i]"."px' value='#def#' class='$aClassFldD[$i]' disabled >";
			$vv.="<input type=hidden id=d_$nmf"."_#rnd#_#no#  name=d_$nmf"."[] style='width:$aLebarFldD[$i]"."px' value='#def#' class='$aClassFldD[$i]' >";				
		}elseif ($ae2!='') {
			$vv=" <input type=text id=dv_$nmf"."_#rnd#_#no#  name=dv_$nmf"."[] style='width:$aLebarFldD[$i]"."px' value='#def#' class='$aClassFldD[$i] disabled2#no#' disabled>";
			$vv.="<input type=hidden id=d_$nmf"."_#rnd#_#no#  name=d_$nmf"."[] style='width:$aLebarFldD[$i]"."px' value='#def#' class='$aClassFldD[$i] notdisabled2#no#' >";				
		} else {
			$vv="<input type=text id=d_$nmf"."_#rnd#_#no#  name=d_$nmf"."[] style='width:$aLebarFldD[$i]"."px' value='#def#' class='$aClassFldD[$i]' #ae# ".($fncf==""?"":" onKeyup='$fncf' ")." >";
			
		}
	}
	 
	if ($i==($jfldD-1)) {
		//jika id tidak adadalam deretan field, maka diinput hhiddenkan, jikadah ada gak peru
		$vv=$vvidd.$vv;
		 
	}
	$avv[$i]=$vv;

	$align=str_replace("r","right",strtolower($aAlignFldD[$i]));
	$align=str_replace("c","center",$align);
	//sample row
	$sr=$vv;
	$sr=str_replace("#def#","",$sr);
	$sr=str_replace("#id#","",$sr); 
	
	$dn="";
	if (substr($aFldDCap[$i],0,2)=="xx") $dn="display:none";
	$sampleRow.="<td style='text-align:$align;$dn' id=t$nmf"."_$rnd"."_#no# >".$sr."</td>\n";
	$i++;
}

if ($showOprD) $sampleRow.="<td>&nbsp;</td>\n";

$def="";
$isiTbD="";
$brd=0;
//$jlhDefRowAdd=0;
if ($isTest) echo $sqTabelD;
$hd=mysql_query2($sqTabelD);
while  ( ($rd=mysql_fetch_array($hd))||($brd<$jlhDefRowAdd))  {
	$r=$rd;
	$no=$brd;
	$rw="";
	if ($target=='file') $rw.="\n\n";
	$rw.="<tr id='trdet_$rnd"."_$brd' >";
	
	$rndx=rand(123,81881);
	$idd=$r[$fldKeyD];
	$tidd="tidd$rndx";
	
	
	$zz=str_replace("#def#","$def",$addfD);
	$zz=str_replace("#rnd#",$rnd,$zz);
	$zz=str_replace("#no#",$no,$zz);
	$addf.=$zz;
	
	//if (!isset($r[$nmf])) $r[$nmf]='';
	
	//if (!isset($r['xxaksi'])) $r['xxaksi']=''; $def=$d_xxaksi=$xxaksi=$r['xxaksi'];
	$j=0;
	foreach ($aFldD as $nmf) {
		
		$def="";
		//jika field diawali xx
		//if (substr($nmf,0,2)!='xx') {
		//if $nmf=='xx') {
		//}
		//@$def=$r[$nmf];
		
		$def="";
		if (isset($r[$nmf])) 
			$def=$r[$nmf];
		elseif (isset($gDefFldD[$j])) { 
			$gdf=$gDefFldD[$j];
			if (isset($gdf[$no]))
			$def=$gdf[$no];
		}
			
		$align=str_replace("r","right",strtolower($aAlignFldD[$j]));
		
		$align=str_replace("c","center",$align);
		$def=changeValueByClass($def,$aClassFldD[$j]);
		eval("$"."d_$nmf=$"."$nmf=$"."def; ");
			
		if ($gFieldInputD[$j]!='') {
			$vv=evalGFF($gFieldInputD[$j],'','',"rd");
			if ($j==($jfldD-1)) $vv.=evalGFF($vvidd);
		} else {
			//echo "<br>$def";
			$vv=evalGFF($avv[$j]);
			//$vv=$avv[$j];//"<input type=text name=d_$nmf"."[] style='width:$aLebarFldD[$j]"."px' value='#def#' class='$aClassFldD[$j]' >";
		}
		//if ($j==0) $vv.=str_replace("value=''","value='$r[$fldKeyD]'",$vvidd);
		//if ($j==$jfldD-1) {
		if ($j==0) {
			//jika id tidak ada dalam deretan field, maka diinput hhiddenkan, jikadah ada gak peru
		
			$vv=str_replace("value='#id#'","value='$r[$fldKeyD]'",$vv);
			if (isset($r['ket'])) $vv=str_replace("value='#ket#'","value='$r[ket]'",$vv);
		//	$vv=str_replace("#rndx#","$rndx",$vv);
			
		 
			//$vv=$vvidd.$vv;
		}
		//if ($nmf=="jlhuang") echo " > ". $def;
	
		if ($op=='view') {
			if ($gFieldViewD[$j]!='') { $nmf=$gFieldViewD[$j];$def=$r[$nmf]; };
			$rw.="<td style='text-align:$align' >$def</td>\n";
		} else {
			//jika caption=xx, disembuyikan
			$dn="";
			if (substr($aFldDCap[$j],0,2)=="xx") $dn="display:none;";
			$zz=str_replace("#def#","$def",$vv);
			$xx="<td  style='text-align:$align;$dn' id=t$nmf"."_$rnd"."_#no#  >$zz</td>\n";
			$xx=str_replace("#id#",$idd,$xx);
			$xx=str_replace("#no#",$no,$xx);
			//$xx=str_replace("#tidd#",$tidd,$xx);
			//$xx=str_replace("#rndx#",$rndx,$xx);
			
			$rw.=$xx;
			
		}
		
		
		$j++;
	}
	
	if ($showOprD) {
		$xx=$yy="";
		$rw.="<td>$xx</td>";
		if ($brd==0) $sampleRow.="<td>$yy</td>\n";
	}
	
	 
	$rw.="</tr>";
	$isiTbD.=$rw;	
	$brd++;
}

if (!isset($sumRowD)) {
	if (isset($footTbD)) 
		$sumRowD=$footTbD;
	else
		$sumRowD="";
}
$h="
<style>
max-height: 20000px;
overflow: auto;
</style>
<div class='tdetailx' ".($op=='view'?'':"style='max-height:none'").">
<div id=$tid style='display:none;overflow:auto;height:500px'></div>

<table style='width:100%' class='tbdetail tbcetakbergaris ' id='tbdet_$rnd'   >
	<thead>
		$jdTbD
	</thead> 
	<tbody id='tbody_tbdet_$rnd'>
	$isiTbD
	
	</tbody>
	<tfoot>
	$sumRowD
	</tfoot>
</table>
</div>
";

if($op=='itb') {
	$h.="
	<input  type=hidden id=jlhrowdet_$rnd name=jlhrowdet value='$brd'>
	<textarea id='tsdet_$rnd' style='display:none'>
	<tr id='trdet_$rnd"."_#no#'  class='trdet_$rnd' no='#no#' >
	$sampleRow
	</tr>
	</textarea>
	";
	if (!isset($capAddRow)) $capAddRow="Tambah Baris";
	if (!isset($addFAddRow)) $addFAddRow="";
	$addFAddRow.="
	
	
	";
	

	$tbAddRow="
	<textarea style='display:none' id=addfd_$rnd>
	$addfD
	</textarea>
	<div style='' >
	<button id='btaddrow_$rnd' class='btn btn-success btn-xs' onclick=\"
	rndx=Math.round(Math.random()*2341,0);
	no=$('#jlhrowdet_$rnd').val()*1;
	
	$('#jlhrowdet_$rnd').val(no+1);
	r=$('#tsdet_$rnd').val(); 
	r=r.replaceAll('#rndx#',rndx);
	r=r.replaceAll('#rnd#',$rnd);
	r=r.replaceAll('#no#',no);
	
	$('#tbody_tbdet_$rnd').append(r);
	
	$('.disabled2'+no).hide();
	$('.notdisabled2'+no).prop('type','text');
	
	
	r=$('#addfd_$rnd').val();
	r=r.replaceAll('#rndx#',rndx);
	r=r.replaceAll('#rnd#',$rnd);
	r=r.replaceAll('#no#',no);
	eval(r);
	maskAllMoney();
	$addFAddRow
	return false;
	
	
	\">
	<i class='fa fa-plus-circle'></i> $capAddRow</button>
	</div>
	";
	
	$h.= "<div style='".($showTbAddRow?"margin:15px 0px -30px 0px":"display:none")."' > $tbAddRow </div>";
}
$h.="

";

$tDetail="<div id=tmd$det style='margin:10px 0 30px 0'>$h</div>";
?>