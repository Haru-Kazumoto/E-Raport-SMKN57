<?php
$showLinkTambah=false;
$showLinkOpr=false;
$aFieldCaption=explode(",",$sFieldCaption);
$aField=explode(",",$sField);
$aFieldShowInTable=explode(",",$sFieldShowInTable);
$aFieldShowInInput=explode(",",$sFieldShowInInput);
$aFieldWillUpdate=explode(",",$sFieldWillUpdate);
$aLebar=explode(",",$sLebar);
$aAlign=explode(",",$sAlign);
if (!isset($showAction)) $showAction=true;
$jField=0;$jLebar=0; 
foreach ($aFieldCaption as $jFld) {
	if ($aFieldShowInTable[$jField]=='') $jLebar+=($aLebar[$jField])*1;
	$jField++;
}

//judul tabel
//$skalaLebar=1/$jLebar*625;
$skalaLebar=1/$jLebar*975;
//echo "Jlebar:$jLebar Skala:$skalaLebar";
$jdlTabel="<tr>";
if ($showNo) $jdlTabel.="<td class=tdjudul style='width:30px' width=30 >No.</td>";
$br=0;
foreach ($aFieldCaption as $jFld) {
	$lb=round($aLebar[$br]*$skalaLebar,0);
	if ($aFieldShowInTable[$br]=='') $jdlTabel.="<td class=tdJudul width='$lb' style='width:$lb"."px;overflow:hidden'>$jFld</td>";
	$br++;
}

if (($showAction) && ($tbOprPos==2)) $jdlTabel.="<td class=tdJudul style='width:70px' width=70>Action</td>";
$jdlTabel.="</tr>";
//operasi

	$sq="Select * from $nmTabel ";
	include "frmreport_v2.0.php";
	$tt=$ttawal;
	
	$br=0;
	while ($r=mysql_fetch_array($h)) {
		$id=$hasil1=$r[strtolower($nmFieldID)];
		$hasil2=$r[strtolower($nmField2)];
		$br++;
		
		$idt="rec".$br;
		$troe=($br%2==0?"troddform2":"trevenform2");
		$tt.="<tr id="."$idt class=$troe onclick=\"aksiBrowseData('$tbrowse','$idtxt','$hasil1','$hasil2')\" >";
		if ($showNo) $tt.="<td align=center>$br</td>";
		for($y=0;$y<$jField;$y++) {
			$nmField=$aField[$y];
			 //echo $aFieldShowInTable[$y];
			if ($aFieldShowInTable[$y]=='') {
			 	$isi=$r[strtolower($nmField)];
				if ((strpos(" ".strtolower($nmField),"foto")>0) && ($isi!='')) {
					$onc="onclick=\"bukaAjaxD('tinput123','input.php?det=gb&gb=$isi&w=500','width:500');return false;\" "; 
					$isi="<center><a href='$isi' target=_blank $onc >lihat</a></center>";
				}
				if (($det=='mata kuliah') &&  (($nmField=='kddosen')||($nmField=='kddosen2')) ) {
					if ($isi!='') {
						if ($nmField=='kddosen')
						$isi=$r["dosen1"];
						else
						$isi=cariField(" select nama from tbdosen where  id='$isi' ");
					}
				}
				$tt.="<td $aAlign[$y]>$isi</td>";
			}
		}
		
		$tbopr="";
		if ($showLinkOpr) {
				$tbopr="<a href=# onclick=\"bukaAjaxD('tinput123','$hal&op=itb&$nmFieldID=".$r[$nmFieldID].$addParamAdd."','width:1000','bukaTgl2()');
				return false\" class=tbedit ></a>";
				$tbopr.="&nbsp;&nbsp;<a href=# onclick=\"if (confirm('Yakin akan menghapus?')) { 
				bukaAjaxD('tinput123','$hal&op=del&$nmFieldID=".$r[$nmFieldID]."','width:100','selesaiEdit()');
				return false; } \"   class=tbdel></a>";
		}
		if ($showAction) $tt.="<td align=center>$tbopr</td>";
		$tt.="</tr>";
			
		}
	$tt.="</table>";

	if ($cari=='') {
		$tt.=$ttakhir;
	}
	echo $tt;	
//echo "Hasil: <div id=thasilbrowse></div>";


?>