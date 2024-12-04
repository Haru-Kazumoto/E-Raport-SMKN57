<?php
//form pencarian
cekVar("tobio,txtcari3,thasil,tdialog,tfldhasil,tempat,jenis,no,rndhasil,idtb");

/*
$capCari="Nama Obat";
$sqcari="select kdobat,nmobat,satuan,hrgjual from tbobat where 1 ";
$fldhasil="kdobat,nmobat,hrgjual,satuan";
$fldcari="nmobat";
$sFldTampil = "kdobat,nmobat,satuan,hrgjual";
*/
 
$tx='';
$t="";
$no*=1;

if ($thasil=="") $thasil="thasil-$idtb";
if ($tfldhasil=="") $tfldhasil="tfldhasil-$idtb";
if ($tdialog=="") $tdialog="tdialog-$idtb";
if (!isset($capPilih)) $capPilih='';
if (!isset($rndhasil)) $rndhasil=$rnd;
if (!isset($ketFoot)) $ketFoot='';
if  (!isset($sy)) $sy="";
if  (!isset($syCari)) 
	$syCari=$sy;
else {
//echo "hoho....$syCari";
	
} 
if  (!isset($sqgroup)) $sqgroup=""; 
if  (!isset($sqorder)) $sqorder=""; 
if (!isset($langsungTampil)) $langsungTampil=true;
if (!isset($maxBrCari)) $maxBrCari=15;
if (!isset($isTest)) $isTest=false;
if (!isset($actAfterClick)) $actAfterClick="";
if (!isset($addParamCari)) $addParamCari="";
if (!isset($sFldCapTampil)) $sFldCapTampil=$sFldTampil;
if (!isset($sFldFormatTampil)) $sFldFormatTampil=",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
	$tampilkan=true;
//$addFCari="cekKdBrgTransBeliC($rnd,$no)";
if (!isset($_REQUEST['txtcari3'])) {	

	$t="";
	//$url="index.php?contentOnly=1&tdialog=$tdialog&thasil=$thasil&rndhasil=$rndhasil&no=$no&rndhasil=$rndhasil&newrnd=$rnd&det=$det&op=$op"."$addParamCari&txtcari3='+$('#txtcari3_$rnd').val()+'";
	$url="index.php?contentOnly=1&idtb=$idtb&no=$no&newrnd=$rnd&det=$det&op=$op"."$addParamCari&rndhasil=$rndhasil&txtcari3='+$('#txtcari3_$rnd').val()+'";
	$t.="
	<textarea id=urlcari_$rnd style='display:none'>$url</textarea>
	";
	if ($capPilih!='') $t.="<div class=row style='text-align:center;margin:20px;font-size:20px'>
	$capPilih
	</div>";
	$t.="
	<div class=row style='margin:0 10px 10px 0;'>
		<div class='row pull-right'>
		$capCari : <input type='text' name=txtcari3 id=txtcari3_$rnd onkeyup=\"cekCari2($rnd);\">
		</div>
	</div>
	

		";//&nbsp; <input type=button value='Cari' class='btn btn-sm btn-primary'>
	if (isset($addInpCari)) {
		$t.=$addInpCari;//=str_replace("#url#",$url,$addInpCari);
	}
	$t.="
	
	</div>
	";
	
	if (!$langsungTampil) {
		$t.="
		<div id='thcari3-$rnd' class='thcari3 row'></div>";
		echo $t;
		exit;
	}
} else {
	//$tampilkan=(strlen($txtcari3)>=3?true:false);

	
}

$txtcari3=str_replace("'","''",$txtcari3);

$opsiWin="height=700,width=900,left=50,top=10,scrollbars=yes,location=no ";
$tbc="";
//"<div style='position:relative;left:115px;top:8px;overflow:inherit;height:1px;'><a href=# onclick=\"document.getElementById('$tdialog').style.display='none';return false;\" style='display:block;width:18px;background:#222;color:#fff'>X</a></div>";	
$tbclose="
<div style='overflow:inherit;height:1px;width:20px'>
<a href=# onclick=\"$('#$tdialog').hide();return false;\" style='color:#fff;display:block'>X</a>
</div>
";


if ($tampilkan) {
	if (!isset($addFCari)) $addFCari="";
	if ($txtcari3!='') {	
	//	$sy.=" and (" .changeParamSySql($fldcari,","," like '%$txtcari3%'",$operasi='or').")"	;
		$syCari.=" and concat($fldcari,'') like '%$txtcari3%'";
	}
	 $xsqcari=$sqcari." $syCari $sqgroup $sqorder limit 0,$maxBrCari";
	
	//$afld=getArrayFieldName($sqcari,'array');
	$afld=explode(",",$sFldTampil);
	
	$afldCap=explode(",",$sFldCapTampil);
	$afldFormat=explode(",",strtoupper($sFldFormatTampil));
	
	if ($isTest) echo "0000>$xsqcari";
	$hn=mysql_query2($xsqcari);
	$nr=mysql_num_rows($hn);
	//if ($nr>0) {
	$isitb="";
	//judul
	$kol="";
	foreach($afldCap as $fld) {	 
		//$fldCap=$fld;
		$kol.="<td align=center  class='tdjudul'>$fld</td>";
	}	
	$isitb.="<tr >$kol</tr>";
	$br=1;

	while ($rn=mysql_fetch_array($hn)) {  
		$r=$rn;
		$kol="";
		$i=0;
		$rndx=rand(123,312199);
		$idtr="trc-$i-$rndx";
		foreach($afld as $fld) {
			$isi=$rn[$fld];
			
			
			$xhasil="";
			$afh=explode(",",$fldhasil);
			$j=0;
			foreach ($afh as $a) {
				$xhasil.=($j==0?"":"|").$rn[$a];
				$j++;
			}
			
			$xvv=changeFormat3($isi,$afldFormat[$i],true);
			if ($xvv!='blank') {
				$isi=$xvv;
			} 
			
			$kol.="<td align=center style='border-bottom:1px solid #fff'>$isi</td>";
			$i++;
		}
		//$eg=evalGff("$addFCari");
		$eg="";
		//$addFCari="		$"."eg=\"alert('{notrans}')\";";
		$r=$rn;
		$eg=evalGFF($addFCari);
		//echo "<br>".$eg;
		if (isset($defFCari)) {
			
			$xDefFCari=evalGff("=\"$defFCari\";");
			$xDefFCari=str_replace("#fldhasil#",$xhasil,$xDefFCari); 
			$xDefFCari=str_replace("#rndhasil#",$rndhasil,$xDefFCari); 
			$xDefFCari=str_replace("#currnd#",$rnd,$xDefFCari); 
			$xDefFCari=str_replace("#br#",$br,$xDefFCari); 
			$xDefFCari.="$('#thasilrow$rnd').html('$br');";
		} else {
			if ($actAfterClick=="rr") {
				$xDefFCari="
				$('#"."$tfldhasil').val('$fldhasil');
				told=$('#"."$thasil').val();
				told+='~$xhasil';
				$('#"."$thasil').val(told).change().click();
				";
				
			}
			 else {
				$xDefFCari="
				$('#"."$tfldhasil').val('$fldhasil');
				$('#"."$thasil').val('$xhasil').change().click();
				";
			 }
			 
			 
		}
		
		
		$tdia="";
		if (isset($tdialog)) 
			$tdia=$tdialog;
		elseif (isset($tempat)){
			$tdia=$tempat;
		}
		
		if ($actAfterClick!="") {
			if ($actAfterClick=="rr") {
				$xDefFCari.="$('#$idtr').hide();";
			}
		} else {
			if ($tdia!='') $xDefFCari.=";$('#$tdia').dialog('close');";
			
		}

		$onc="onclick=\"$xDefFCari;return false;\" ";
		$isitb.="<tr $onc id=$idtr  >
			 $kol
			 <input type=hidden id='h-$br-$rnd' value='$xhasil'>
			</tr>";
	 
		$br++;
	}
	
	$tx=" 
		<div style='max-height:300px;overflow:auto'>
		<!--table width=100% style='background:#000' class='table table-bordered tbcari'>
		<tr>
		<td align=center style='color:#FFF'>ISIFORM</td>
		<td valign=top align=center  style='width:25px;background:#555'> $tbclose</td>
		</tr>
		</table -->
		<table width=100% border=1 cellspacing=0 cellpadding=0 style='background:#fff' class='tbcari table table-bordered'>
			$isitb		 
		</table>
		<div style='display:none'>
		<div id=thasil$rnd></div>
		<div id=thasilrow$rnd></div>
		</div>";
	//}//nr
	
	
}
$isi="<center>$tx</center>";
if ($langsungTampil) {
	echo "
	$t
	<div id=thcari3-$rnd class='thcari3 row'>
	$isi
	</div>
	<style>
	.thcari3 {
		margin:0px;
	}
	.tbcari tr:hover {
		background:#ceeff8;
	}
	a {color:#900}
	
	</style>
	";
} else  echo $isi;
?>