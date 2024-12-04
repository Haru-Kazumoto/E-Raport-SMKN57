<?php
eval("$"."idm=$$fldKeyM;");//menyimpan id master

if (!isset($sFldDCap)) $sFldDCap=strtoupper($sFldD);
if (!isset($addSaveD1)) $addSaveD1="";
if (!isset($addSaveD2)) $addSaveD2="";
if (!isset($addSaveD3)) $addSaveD3="";  
if (!isset($addEvSaveD)) $addEvSaveD="";  

$aFldD=explode(",",$sFldD);
$jlhFldD=count($aFldD);
		
$ssql="";

$ketsqlD="";
$br=0;

if (isset($_REQUEST["d_id"])) {
	foreach ($_REQUEST["d_id"] as $idd) {
		if ($isTest) echo "<br>idd:$idd<br>";		
		$sql="";
		$skip=false;
		$sqdi1=$sqdi2=$sqdu="";
		for($i=0;$i<$jlhFldD;$i++) {
			$nmfld=$aFldD[$i];
			
			if ($isTest) echo "<br>...cek $nmfld ";
			$isi='';
			if (substr($nmfld,0,2)!="xx") {
				$isi=$_REQUEST["d_$nmfld"][$br];
				echo "tidak xx: d_$nmfld > isi: $isi ";
			}
			//jika field pertama kosong,maka akan diskip/dihapus
			if (substr($nmfld,0,2)=="xx") {
			
			} else if (($i==0) && ($isi=='') && ($idd=='')) {
				$skip=true;	
				
			} else {
				if ($aClassFldD[$i]=='tgl') {
					//echo "<br>ganti $isi ";
					$isi=tgltosql($isi);
					//echo " menjadi $isi" ;
				}else if (($aClassFldD[$i]=='CX')||($aClassFldD[$i]=='C') || ($aClassFldD[$i]=='N')|| ($aClassFldD[$i]=='C1')|| ($aClassFldD[$i]=='C2')) {			 
						//echo "<br>$nmfld isi lama $isi";
						$isi=unmaskRp($isi);
						//echo " isi baru $isi ";
				 }
				
				if ($addEvSaveD!="") {
					
				}
				
				
				if ($sqdu!=""){
					$sqdi1.=",";
					$sqdi2.=",";
					$sqdu.=",";
				}
				$sqdi1.="$nmfld";
				$sqdi2.="'$isi'";
				$sqdu.="$nmfld='$isi'";
			}
			
			//jika skip, maka loncat keluar
			if ($skip) $i=$jlhFldD;
		}
		
		//$h.=$sqdu;
		//jika tidak skip, tapi field tertentu ada yang kosong maka skip diperbarui
		
		if (!$skip) {
			if ($addSaveD3!="")	$sqdu.=(substr($addSaveD3,0,1)==","?"":",")."$addSaveD3";
			if ($addSaveD1!="")	{
				$sqdi1.=(substr($addSaveD1,0,1)==","?"":",")."$addSaveD1";
				$sqdi2.=(substr($addSaveD2,0,1)==","?"":",")."$addSaveD2";
			}
			$c=$syu="";
			if ($idd=="") {
				$isInsert=true;
			} else {			
				$syu=" $fldKeyD='$idd' and $fldKeyForeign='$idm'";
				$sqCari="select $fldKeyD from $nmTabelD where $syu ";
				$ketsqlD.="<br>$sqCari";
				$c=carifield($sqCari);
				$isInsert=($c==''?true:false);
			}
			
			//senggaja dibuat tersendiri karena ada pembaharuan isInsert
			if ($isInsert) {
				$sql="insert into $nmTabelD ($fldKeyForeign,$sqdi1) values('$idm',$sqdi2); ";
			} else {
				$sql="update $nmTabelD set $sqdu where $syu ; ";
			}
			//if ($isTest) echo "<br><div class='text text-red'>Insert lho $sql </div>";
			mysql_query2($sql);
		} else {
			$sql="";
			//jika diinginkan mengapus baris
			/*
			if (!$isInsert) {
				$sql="delete from  $nmTabelD  where $syu ; ";
				mysql_query2($sql);
			}
			*/
		}
		if ($sql!='') $ketsqlD.="<br>$sql";
		$br++;
	}
} 
if ($isTest) {
	echo "---------->input-std-ed-detail>";
	echo $ketsqlD;
}
?>