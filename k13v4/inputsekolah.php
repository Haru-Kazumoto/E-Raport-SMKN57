<?php
$useJS=2;
include_once "conf.php";
extractRequest();
$det=$_SESSION['det']="sekolah";
	$isTest=true;
$showNo=false;
$id=1;
cekVar("cari,showresult,op");
//penggantian Field text
$gField=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
$sFieldShowInTable=$sFieldShowInInput=$sAlign=",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
$sHFilterTabel="";
$sqFilterTabel="";
$sOrderTabel="";
$addParamAdd="";//tambahan parameter untuk tombol add
$sLebar="1,1,1,1,1,1,1,1,1,1,1,1,1,1";
$nfAction="inputsekolah.php?det=$det";
$pathFoto="foto/";

//$nfAction="index.php?det=$det";
$hal="inputsekolah.php?det=$det";
$addInput=$addFuncAdd=$addFuncEdit="";

$sAwalan="A,B,C1,C2,C3,ML";

$aAwalan=explode(",",$sAwalan);
		
	if ($cari!='') { //filter pencarian
	foreach($_REQUEST as $nm=>$value) {
		if (substr($nm,0,2)=='ft') {
			$var=substr($nm,3,100);
			$sqFilterTabel.=($sqFilterTabel==''?" where ":" and ");
			$sqFilterTabel.="$var = '$value'";
		}
	}
}

if ($det=='sekolah') { //guru
	$nmTabel="sekolah";
	$nmCaptionTabel="sekolah";
	$nmFieldID="kode";
	$sField="nama,nisn,nss,alamat,telepon,fax,kelurahan,kecamatan,kota,provinsi,website,email,kepsek_nama,kepsek_nip,kepsek_tt";
	$sLebar="110,50,50,110,20,20,30,30,30,30,70,60,50,30,30	";
	$sFieldCaption="Nama,NPSN,NIS/NSS/NDS,Alamat Sekolah,Telepon,Fax,Kelurahan,Kecamatan,Kabupaten/Kota,Provinsi,Website,E-mail,Kepala Sekolah,NIP,Tanda Tangan";
	$sqTabel="select program_keahlian.nama  as kode_programkeahlian,$nmTabel.nama, $nmTabel.kode from $nmTabel left join program_keahlian on $nmTabel.kode_programkeahlian=program_keahlian.kode ";
}
 
//$nfAction="index1.php?page=input&det=$det";

$aFieldCaption=explode(",",$sFieldCaption);
$aField=explode(",",$sField);
$aFieldShowInTable=explode(",",$sFieldShowInTable);
$aFieldShowInInput=explode(",",$sFieldShowInInput);
$aLebar=explode(",",$sLebar);
$aAlign=explode(",",$sAlign);


$jField=0;$jLebar=0; 
foreach ($aFieldCaption as $jFld) {
	if ($aFieldShowInTable[$jField]=='') $jLebar+=($aLebar[$jField])*1;
	$jField++;
}

//judul tabel
$skalaLebar=1/$jLebar*700;
//echo "Jlebar:$jLebar Skala:$skalaLebar";
$jdlTabel="<tr>";
if ($showNo) $jdlTabel.="<td class=tdjudul style='width:30px' width=30 >No.</td>";
$br=0;
foreach ($aFieldCaption as $jFld) {
	if ($aFieldShowInTable[$br]=='') $jdlTabel.="<td class=tdJudul width='".round($aLebar[$br]*$skalaLebar,0)."'>$jFld</td>";
	$br++;
}


$jdlTabel.="<td class=tdJudul style='width:70px' width=70>Action</td>";
$jdlTabel.="</tr>";
//operasi
if ($op=='tb') {
	//khusus mp, kode autoincremen
	$jns=$_REQUEST['Jenis'];
	
	
	$sfld="";
	foreach ($aField as $jFld) {
		if ($sfld!='') $sfld.=",";
		
		if ((strpos(" ".$jFld,"tgl")>0)||(strpos(" ".$jFld,"tanggal")>0))  {
			$_REQUEST[$jFld]=tgltoSQL($_REQUEST[$jFld]);
			}
			
		$sfld.="'".$_REQUEST[$jFld]."'";	
	}

	$sq="insert into $nmTabel($sField) values ($sfld)";
	//echo $sq;
	
	$h=mysql_query($sq); 
}
elseif ($op=='ed') {
	 
	$sfld="";
	foreach ($aField as $jFld) {
		if ($sfld!='') $sfld.=",";
		//eval("$".$jFld."='$"."$jFld"."';");
		//jika tanggal
		//echo $jFld." ->".strpos(" aaa","a")."<br>";
		$skip=false;
		if ((strpos(" ".$jFld,"tgl")>0)||(strpos(" ".$jFld,"tanggal")>0))  {
			$_REQUEST[$jFld]=tgltoSQL($_REQUEST[$jFld]);
		}
		elseif ($jFld=="kepsek_tt")  {
			$nf=uploadFile($nmvar='kepsek_tt',$folderTarget="foto",$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget="kepsek_tt_".$thpl4.".ext");		
			
			if ($nf!='') {
				$sfld.="$jFld='$nf'";
			}
			$skip=true;
		}
		if (!$skip) $sfld.="$jFld='".$_REQUEST[$jFld]."'";	
	}
 
	  $sq="update $nmTabel set $sfld where $nmFieldID='$id'";
 	//echo $sq;
	redirection("index.php");
	//exit;
	$h=mysql_query($sq);
	//echo "Edit data $nmCaptionTabel berhasil....";
}

elseif ($op=='del') {
	$sq="delete from $nmTabel where $nmFieldID='$id'";
	$h=mysql_query($sq);
	//echo "Penghapusan data di $nmCaptionTabel berhasil....";
}

 elseif ($op=='itb') {
	//input
	$newop=($id==''?'tb':'ed');
	if ($id!='') {
		$sq="Select * from $nmTabel where $nmFieldID='$id' ";
		$hq=mysql_query($sq);
		$r=mysql_fetch_array($hq);
		extractRecord($sq);
	}
	$idForm=$nmTabel."_".rand(1231,2317);
	$asf="";
	//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','');return false;\" ";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','$det');return false;\" ";
	$t="";
	$t.="<div id=ts"."$idForm ></div>";
	$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	$t.="<div class=titlepage >Data $nmCaptionTabel</div>";
	$t.="<input type=hidden name=op id=op value='$newop'> </td>";
	$t.="<input type=hidden name='id' id='id' value='$id' > </td>";
	$t.="<table>";
	for ($i=0;$i<$jField;$i++) {
		$sty="";
		if ($aFieldShowInInput[$i]!='') $sty="style='display:none'";
			$cap=$aFieldCaption[$i];
			if (substr($cap,0,1)=='-') {
				$t.="<tr class=troddform2><td colspan=2><hr style='width:770px'></td></tr>";
				$cap=substr($cap,1,100);
				}
			
			$vv=$r[strtolower($aField[$i])];
			
			
			if ((strpos(" ".strtolower($aField[$i]),"tgl")>0)||(strpos(" ".strtolower($aField[$i]),"tanggal")>0))  {
				$vv=SQLtotgl($r[strtolower($aField[$i])]);
			}
			
			$t.="<tr class=troddform2 $sty >";
			$t.="<td class=tdcaption  >$cap</td>";
			$inp="<input type=text name=$aField[$i] id=$aField[$i] value='".$vv."' size='$aLebar[$i]' > ";
			 if (strstr($aField[$i],'kepsek_tt')!="") {
					$inp="<input type=file name=$aField[$i] id=$aField[$i] > ";
					if ($vv!="") {
						$nf=$pathFoto.$vv;
						if (file_exists($nf)) {
							$tvp="tv".$aField[$i].rand(222,122121);
							$inp.="<br>
							<img src='$nf' style='max-width:50px' onclick=\"$('#$tvp').dialog({width:540});\" />
								<span id=$tvp style='display:none;' >
								<img src='$nf'  width=500 />
								</span>";
						} 
					}
					 $t.="<td>$inp</td>";
			}
			
			if ($gField[$i]!='') {
				eval($gField[$i]);
				$t.="<td>$inp</td>";
			}
			else
			$t.="<td>$inp</td>";			
			$t.="</tr>";
			
		}
	
	if ($addInput=='') $t.="<td align=left>&nbsp;</td><td align=left><br><input type=submit value='Simpan Data' class='btn btn-primary btn-sm'  ></td>";
	$t.="</table>";
	if ($addInput!='') {
		$t.="<br>$addInput";
		$t.="<br><br><input type=submit value='Simpan Data' class='btn btn-primary btn-sm' ><br>";
	}
	
	//$t.="<br><a href=# onclick=\"bukaAjax('content0','$hal".$addParamAdd."');return false;\">Lihat Daftar $nmCaptionTabel</a>";
	$t.="</form>";
	echo $t;
	exit;
}
$op='its';
if ($op=='its') {
	//input
	$newop=($id==''?'tb':'ed');
	if ($id!='') {
		$sq="Select * from $nmTabel where $nmFieldID='$id' ";
	 	//echo $sq;
		$hq=mysql_query($sq);
		$r=mysql_fetch_array($hq);
		extractRecord($sq);
	}
	//echo $sq;
	$t="";
	$t.="<div class=titlepage >Data $nmCaptionTabel</div>";
	$t.="<table class=formInput>";
	for ($i=0;$i<$jField;$i++) {
		$sty="";
		if ($aFieldShowInInput[$i]!='') $sty="style='display:none'";
			$cap=$aFieldCaption[$i];
			if (substr($cap,0,1)=='-') {
				$t.="<tr class=troddform2><td colspan=2><hr style='width:770px'></td></tr>";
				$cap=substr($cap,1,100);
				}
			
			$vv=$r[strtolower($aField[$i])];
			
			
			if ((strpos(" ".strtolower($aField[$i]),"tgl")>0)||(strpos(" ".strtolower($aField[$i]),"tanggal")>0))  {
				$vv=SQLtotgl($r[strtolower($aField[$i])]);
			}
			
			$t.="<tr class=troddform2 $sty   >";
			$t.="<td class=tdcaption style='width:150px' >$cap</td>";
			$t.="<td>: $vv</td>";			
			$t.="</tr>";
		}
	
		if ($addInput=='') $t.="<td align=left>&nbsp;</td><td align=left><br></td>";
		$t.="</table>";
		if ($addInput!='') {
			$t.="<br>$addInput";
		}
	if ($userType=='admin') {
		$t.="<a href=# onclick=\"bukaAjax('content0','$hal&op=itb&id=$id".$addParamAdd."',0,'$addFuncEdit');
			return false\"><i class='icon-edit'></i>&nbsp;Edit Data $nmCaptionTabel</a>";	
	}
	$infoSekolah=$t;
	if ($showresult==1) echo $t; 
}
 
?>