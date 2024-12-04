<?php
//form pencarian
cekVar("tobio,txtcari3,thasil,tempat,jenis,no,kkunci");
$t="";
if (!isset($capPilih)) $capPilih='';
$no*=1;
/*
$capCari="Nama Barang";
if ($kkunci!='') $capCari.=" ($kkunci)";
if (!isset($langsungTampil)) 	$langsungTampil=false;
$sqcari="select kdbrg,nmbarang,satuan,defhrgbeli from tbpbarang where 1 ";
$fldhasil="kdbrg";
$fldcari="nmbarang";
$sfldtampil="kdbrg,nmbarang,satuan";
*/
//$addFCari="cekKdBrgTransBeliC($rnd,$no)";
if (!isset($_REQUEST['txtcari3'])) {	
	$t="";
	$t.="
	<div class=row style='text-align:center;margin:20px;'>
	$capPilih
	</div>
	<div class='pull-right'>
	$capCari : <input type='text' name=txtcari3 id=txtcari3_$rnd 
			onkeyup=\"bukaAjax('thcari3_$rnd','index.php?contentOnly=1&thasil=$thasil&no=$no&newrnd=$rnd&det=$det&op=$op&txtcari3='+this.value+'&kkunci=$kkunci');\"
			>
	</div>
			
			 
	 
		";//&nbsp; <input type=button value='Cari' class='btn btn-sm btn-primary'>
	$t.="
	</div>
	";
	
	if (!$langsungTampil) {
		$t.="<div id=thcari3_$rnd class=thcari3></div>";
		echo $t;
		exit;
	}
}
?>

 
<style>
a {color:#900}
</style>

<?php
$txtcari3=str_replace("'","''",$txtcari3);

$opsiWin="height=700,width=900,left=50,top=10,scrollbars=yes,location=no ";
$tbc="";
//"<div style='position:relative;left:115px;top:8px;overflow:inherit;height:1px;'><a href=# onclick=\"document.getElementById('$tempat').style.display='none';return false;\" style='display:block;width:18px;background:#222;color:#fff'>X</a></div>";	
$tbclose="
<div style='overflow:inherit;height:1px;width:20px'>
<a href=# onclick=\"document.getElementById('$tempat').style.display='none';return false;\" style='color:#fff;display:block'>X</a>
</div>
";
if  (!isset($sy)) $sy="";
if  (!isset($sqgroup)) $sqgroup=""; 
if (strlen($txtcari3)>-10) {
	if (!isset($addFCari)) $addFCari="";
	if ($txtcari3!='') $sy.="and $fldcari like '%$txtcari3%'";
	$xsqcari=$sqcari." $sy ".$sqgroup;
	
	//$afld=getArrayFieldName($sqcari,'array');
	$afld=explode(",",$sFldTampil);
	if (!isset($sFldCapTampil)) $sFldCapTampil=$sfldTampil;
	if (!isset($sFldFormatTampil)) $sFldFormatTampil=",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
	
	$afldCap=explode(",",$sFldCapTampil);
	$afldFormat=explode(",",$sFldFormatTampil);
	if ($isTest)  echo "0000>$xsqcari";
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
	$br=0;
	$tempatx="aa";
	while ($rn=mysql_fetch_array($hn)) {  
		$kol="";
		$i=0;
		foreach($afld as $fld) {
			$isi=$rn[$fld];
			if ($afldFormat[$i]=='C') {
				$isi=maskRp($rn[$fld]);
			
			}
			$kol.="<td align=center style='border-bottom:1px solid #fff'>$isi</td>";
			$i++;
		}
		$xhasil=$rn[$fldhasil];
		//$eg=evalGff("$addFCari");
		$eg="";
		//$addFCari="		$"."eg=\"alert('{notrans}')\";";
		$r=$rn;
		$eg=evalGFF($addFCari);
		//echo "<br>".$eg;
		if (isset($defFCari)) {
			$defFCari=evalGff("=\"$defFCari\";");
		} else {
			$defFCari="$('#"."$thasil').val('$xhasil')";
		}
		if (isset($tempat)) {
			if ($tempat!='') $defFCari.=";$('#$tempat').dialog('close');";
		}
		$onc="onclick=\"$defFCari;return false;\" ";
		$isitb.="<tr $onc   >
			 $kol
			</tr>";
	 
		$br++;
	}
	
	$tx=" 
		<div style='padding:3px;background:#ddd;max-height:300px;overflow:auto'>
		<!--table width=100% style='background:#000' class='table table-bordered tbcari'>
		<tr>
		<td align=center style='color:#FFF'>ISIFORM</td>
		<td valign=top align=center  style='width:25px;background:#555'> $tbclose</td>
		</tr>
		</table -->
		<table width=100% border=1 cellspacing=0 cellpadding=0 style='background:#fff' class='tbcari table table-bordered'>
			$isitb		 
		</table>";
	//}//nr
	
	
}
$isi="<center>$tx</center>";
if ($langsungTampil) {
	echo "
	<div id=thcari3_$rnd class=thcari3>
	$isi
	</div>
	<style>
	.tbcari tr:hover {
		background:#ceeff8;
	}
	</style>
	";
}else  echo $isi;
?>