<?php
cekVar("id,target,aid,custom,parentrnd,newop");
if (!isset($allowGuest)) $allowGuest=false;
if (!isset($funcAfterEdit)) $funcAfterEdit='';
if (!isset($updateTrans)) $updateTrans=true;
if (!isset($useInputD)) $useInputD=0;
if (!isset($jInputD)) $jInputD=$useInputD;
//if (!isset($tPosDetail)) $tPosDetail=1000;

$folderModul=$adm_path."protected/view/$det";
$folderView=$adm_path."protected/view/$det";
$nfCustom="";
$linkBack="<br><br><a class='btn btn-primary readmore' href='index.php'>Klik disini</a> untuk kembali.....";
if (!isset($newop)) $newop=(op("itb,tb,ed")?($id==''?'tb':'ed'):"");

/*
contoh generate file
http://localhost/099-donor/index.php?det=pendonor&op=view&custom=perwilayah&target=file
*/ 

		

//evaluasi filter
//ft_tabel-field,fte_field,ftl_, fta_field,ftb_field
/*
ft_alamat : alamat='txt'
ftb_alamat : almat like 'txt%'
ftx_alamat : alamat like '%txt%'
ftmin_usia : usia >='txt'
ftmax_usia : usia<='txt'


//-or- jika menggunakan or, nmf bisapakai concate
ftx_alamat1-or-alamat2 -> concat(alamat1,alamat2) like '%fld%'

*/

$evNew="";
extractRequest(1,0,"ft_");
$i=0;
foreach ($nmVar as $nmv) {
	$v=$vNmVar[$i];
	$hal.="&$nmv=$v";
	//$addParamAdd="&$nmv=$v";
	
	$postst=strpos($nmv,"_");
	if ($postst) {
		$aw=substr($nmv,0,$postst);
		$nmf=substr($nmv,$postst+1,100);
		
		
		$xnmf="";
		if  (strstr($nmf,'-or-')!='') {
			$xnmf=str_replace("-or-",",",$nmf);
			$nmf=" concat($xnmf) ";
		}
		
		
		$nmf=str_replace("-",".",$nmf);
		
		$sy="";
		//echo ">$aw $nmf ";
		if (($aw=="ft")||($aw=="fte") )
			$sy="$nmf='$v' ";
		elseif ($aw=="fta" )
			$sy="$nmf like '$v%' ";
		elseif ($aw=="ftmin" )
			$sy="$nmf >= '$v%' ";
		elseif ($aw=="ftmax" )
			$sy="$nmf <= '$v%' ";
		elseif ($aw=="ftc" ) {//checkbox
			$av=explode("|",$v);
			$sx="";
			foreach ($av as $xv){
				$sx.=($sx==''?"":" or ")." $nmf='$xv' " ;
			}
			$sy=" ($sx) ";
		} elseif ($aw=="ftb" )
			$sy="$nmf='$v' ";
		else
			$sy="$nmf like '%$v%' ";
		
		$sqFilterTabel.= ($sqFilterTabel==''?' where ':' and ').$sy;
		addParamOpr("ft_$nmf",$v);
		
		
		//memberi nilai awal
		if ($op=='itb') {
			$postitik=strpos($nmf,".");	
			$nmfasli=($postitik?substr($nmf,$postitik+1,100):$nmf);
			$ev="$"."$nmfasli='$v';";
			$evNew.=$ev;
			eval($ev); 
		}
	}
	$i++;
}

if ($op=='cpass') {
	include $um_path."input-std-cpass.php";
}


elseif ($op=='resetpwd') {
	cekvar("fldpass"); 
	$defPwdReset="12345678";
	if ($fldpass=="") $fldpass="vpass";
	secureuser("admin,sa,content");
	$sq="update $nmTabel set $fldpass=md5('$defPwdReset') where id=$id ";
	echo execQuery($sq,"p","Reset password|Password default:$defPwdReset ");
	exit;
}
elseif (op("view,viewdaf,viewlap,itb,ed,tb,filter)")) {
	//menghindari field yang umum digunakan


	if (isset($_REQUEST['tgl'])) $tgl=$_REQUEST['tgl'];
	$nfC=$nfCustom=cekNFCustom();
	if ($nfC=="") {
		$yview=(op("itb")?"form":"view");
		$nfC=$adm_path."protected/view/$det/$yview-$det-$custom.php";
	}
	
	$nfC=str_replace("-.php",".php",$nfC);//file output hasil generate 
	if (!isset($usegen)) $usegen=true;
	if ($usegen)	$nfC=str_replace(".php","-gen.php",$nfC);//file output hasil generate 
	$nfOutput=$nfC;
}
if ($target=='file') 	createFolder($nfCustom,false);
if ($id=='') if ($aid!='') $id=$aid;

$nfadd=$adm_path."protected/view/$det/custom-$det.php"; 
if (file_exists($nfadd)) 	include_once $nfadd;

if ($op=='viewmenu') {
	$nf="$folderModul/berkas-$det.php"; 
	if (file_exists($nf)) {
		include $nf;
		exit;
	}
	else {
		die("File $nf tidak ditemukan....");
	}
}
if ($op=='suggestfld') {
	//untuk menampilkan list fld pada suggest
//$gFuncFld[$i]="suggestFld('$det','idperusahaan|nama_perusahaan',$rnd,this.value);";
	$t='';
	
	//$tinput=$_POST['tinput'];
	//$rnd=$_POST['newrnd'];
	if(strlen($def) >0) {
		$sq="SELECT distinct($fldcari) FROM $nmTabel where $fldcari like '%$def%' order by $fldcari LIMIT 0,10";
		$hq=mysql_query2($sq);
		if($hq) {
			// While there are results loop through them - fetching an Object (i like PHP5 btw!).
			while ($r =mysql_fetch_array($hq)) {
				$v=$r[$fldcari];
				$vcap=str_replace($def,"<b>$def</b>",$v);
				 
				$t.= "<li onClick=\"isiSuggestFld('$fldinput','$v',$rnd);\">$vcap</li>";
			}
		} else {
			$t.= 'ERROR: There was a problem with the query.';
		}
		
	}
	//$t.="<li>ketemu....</li>";
	echo $t;
	exit;
} elseif ($op=='delgb'){
	//op=delgb&$aid=$id&fld=$nmf&gb=$fl
	cekVar("nmf,gb,pathup");
	$sq="select $nmf from $nmTabel where $nmFieldID='$id'";
	$sisi=carifield($sq);
	//misal a.jpg#b.jpg jadi #a.jpg#b.jpg
	$newsisi=str_replace($gb,"",$sisi);
	$newsisi=str_replace("##","#",$newsisi);
	//if (strlen($newsisi)>1) 
	if (substr($newsisi,0,1)=="#") $newsisi=substr($newsisi,1,strlen($newsisi)-1);
	if (substr($newsisi,-1)=="#") $newsisi=substr($newsisi,0,strlen($newsisi)-1);
	//echo "<br>fl:$gb <br> isi:$sisi<br>newisi:$newsisi ";
	//echo $sq;
	$pes='';
	echo $sq2="update $nmTabel set $nmf='$newsisi' where $nmFieldID='$id'";
	//$pes.="gb $gb ni ".$newisi;
	$h=mysql_query2($sq2);
	if ($h) {
		
		$fl=$pathup.$gb;
		
		if (file_exists($fl)) 	unlink($fl);
		$pes.= "Penghapusan File $fl Berhasil";
		//if ($saveLogSQL) addActivityToLog2($nmTabel,$op,$id,"Penghapusan file pada field $nmf di tabel $nmTabel  ");
			
		$f="$('#$tgb').remove();alert('$pes');";
		
	} else {
		$pes= "Penghapusan tidak berhasil <br>Pesan : ".mysql_error();
		$f="alert(\"$pes\");";
	}
	
	echo "
	<textarea id=tfae$rnd style='display:none'>$f</textarea>
	";
	exit;
} elseif (strstr(",itb,ed,tb,filter,",",$op,")!='') {
	cekVar('addfae,tdialog,tkn');
	
		
	$funcAfterEdit=''; 
	if ($tkn!='') 	evalToken($tkn); 

	if ($addfae=='') {
		if (!isset($currnd))$currnd=$rnd;
		$prevrnd=$currnd;
		$url="$('#thal_$prevrnd').html()+'&cari=cari'";
	} else {
	 
		$funcAfterEdit.="$addfae;";
	}
	
	
	if (!$isTest) {	
		if (isset($idtd)){
			$funcAfterEdit.="
			tutupDialog2('$idtd');
			";
		}
	}	
		
}



if ($op=='genall') {
	include $um_path."mysql-create-trigger.php";
} elseif ($op=='gen') {
	cekVar("op2");
	if ($op2=='') {
		$sm="Basic,FormInput,View";
		$am=array(
			array('Input Awal','gen&op2=model'),
			array('Form Input','itb&target=file'),
			array('Form View','view&target=file'),
			array('Laporan','gen&op2=lap&target=file'),
		);
		
		$det=$_REQUEST['det'];
		$t="";
		foreach($am as $xmn) {
			$t.=" <a href='index.php?det=$det&op=$xmn[1]' class='btn btn-primary btn-xs'  target=_blank>Generate $xmn[0]</a>";
		}
		echo "
		<div>&nbsp;
		<div style='margin:20px'>$t</div>
		</div>";
		$styta=" style='width:100%;min-height:300px;overflow:auto'";
		echo "<textarea $styta>";
		include_once $um_path."catatan-programmer.php";
		echo "</textarea>
		<textarea $styta>";
		include_once $um_path."catatan-programmer2.php";
		echo "</textarea>";
		
		exit;
	}
	
	if ($op2=='model') {
		include $um_path."input-std-gen-field-control2.php";
	}elseif ($op2=='lap') {
		
		$nf1= "input-default.php";
		$isinf1 = file_get_contents($nf1);
		$nf2= $um_path."input-std-gen-lap.php";
		$isinf2 = file_get_contents($nf2);
		$h=$isinf1.$isinf2;
		
		$nfHasil=$adm_path."protected/view/lap/lap-$det.php";
		file_put_contents($nfHasil, $h);
		
		//include $um_path."input-std-gen-lap.php";
		exit;
	}
}

//$nmTabel="tbmerchant";
$aAllField=explode("#",$sAllField);
$jlhField=count($aAllField);
if (!isset($tPosDetail)) $tPosDetail=$jlhField-1;

$aField=$aFieldCaption=$aFieldCaption2=$aFieldEditable=$aFieldEditableX=$aLebarFieldInput=$aShowFieldInInput=$aShowFieldInView=array();
$aFieldSpecial=$aUpdateFieldInInput=$aShowFieldInTable=$aLebarFieldTabel=$aRataFieldTabel=$aCek=$aNeedSearchInDT=array();
$aJenisInput=$aMinInput=$aMaxInput=array();
$i=0;
$jLebar=0;
//$i++; $sAllField.="#17|paidtoday|Pembayaran Tunai|13|1|1|0|7|C|N-0|1|1";
/*
penjelasan

*/

$aInpField=[];
foreach($aAllField as $aa){
	$aDetField=explode("|",$aa."|1|1|1|1|1|1||||||||||||");
	$nmfield=trim($aDetField[1]);
	
	//jenis input contoh:F-1,I file gambar, tidak boleh kosong
	$cekField=trim($aDetField[9]);	
	$xCek=explode(",",$cekField.",0,0,0,0");
	$ajiu=explode("-",$xCek[0]."-1-0");
	//jika field kunci tidak boleh kosong
	if (($nmfield==$nmFieldID)&& ($ajiu[1]==0)) {
		$ajiu[1]=1;
	}

	$needUpdateF=trim($aDetField[5])*1;
	if (($nmfield==$nmFieldID)&& ($op=='ed')) {
		$needUpdateF=0;//jika edit, field kunci g perlu diupdate
	}
	
	//mengatur siapa yang boleh edit
	$cafe=trim($aDetField[11]);
	if ($cafe=="n") {
		$ae=false;
	} else if ($cafe*1==2) {
		//hanya boleh edit saat tambah baru saja saja
		$ae=(op('tb')||($newop=='tb')?true:false);
	} else if (($cafe=='noedit')) {
		//$ae=($id==0?true:false);
		$ae=(op('tb')||($newop=='tb')?true:false);
	} else if (($cafe=='') || ($cafe=='1')) {
		$ae=true;
	}else if ($cafe*1==0) {
		$ae=false;
	} else if ($cafe*1<=$levelOwner) {
		$ae=true;
	} else
		$ae=false;
	
	if (!$ae) $needUpdateF=0;
	if (!isset($gFieldJudulImport[$i])) $gFieldJudulImport[$i]=$aFieldCaption[$i];
	
	
	$adf2=explode("~",$aDetField[2]."~");
	$ratatb=(trim($aDetField[8])=='C'?"center":(trim($aDetField[8])=='R'?"right":"left"));
	$tampiltb=trim($aDetField[10])==''?1:trim($aDetField[10]);//tampil di view dan tb
	$needSearch=trim($aDetField[12])==''?1:trim($aDetField[12]);
	$capFld=trim($adf2[0]);
	$capImport=$gFieldJudulImport[$i];
	
	array_push($aFieldSpecial,trim($aDetField[0]));
	array_push($aField,$nmfield);
	array_push($aFieldCaption,trim($adf2[0]));	
	array_push($aFieldCaption2,trim($adf2[1]));	
	array_push($aLebarFieldInput,trim($aDetField[3]));
	array_push($aShowFieldInInput,trim($aDetField[4]));
	array_push($aUpdateFieldInInput,$needUpdateF);	
	array_push($aShowFieldInTable,trim($aDetField[6]));
	array_push($aLebarFieldTabel,trim($aDetField[7])*1);
	array_push($aRataFieldTabel,$ratatb);
	array_push($aCek,$cekField);
	array_push($aShowFieldInView,$tampiltb);
	array_push($aNeedSearchInDT,$needSearch);
	
	array_push($aJenisInput,$ajiu[0]);
	array_push($aMinInput,$ajiu[1]);
	array_push($aMaxInput,$ajiu[2]);
	
	array_push($aFieldEditable,$ae);
	array_push($aFieldEditableX,$cafe);
	
	
	if (trim($aDetField[6])=="0") $jLebar+=($aLebarFieldTabel[$i]*1);
	
	$aInpField[$i]=array(
		'idx'=>$i,
		'name'=>$nmfield,
		'cap'=>trim($adf2[0]),
		'cap2'=>trim($adf2[1]),
		'size'=>trim($aDetField[3]),
		'showInInput'=>trim($aDetField[4]),
		'UpdateInInput'=>$needUpdateF,
		'sizeInTable'=>trim($aDetField[7])*1,
		'alignInTable'=>$ratatb,
		'cek'=>$cekField,
		'showInView'=>$tampiltb,
		'needSearch'=>$needSearch,
		'type'=>$ajiu[0],
		'minInput'=>$ajiu[1],
		'maxInput'=>$ajiu[2],
		'allowEdit'=>$ae,
		'allowEditX'=>$cafe,
		'capImport'=>$capImport,
	);
	
	$i++;	
}
$sField=implode(",",$aField);
$sFieldCaption=implode(",",$aFieldCaption);

//detail
if ($jInputD>0) {
	if (!isset($sFldDCap)) $sFldDCap=strtoupper($sFldD);
	if (!isset($sAlignFldD ))  $sAlignFldD =",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
	$aFldD=explode(",",$sFldD);
	$aFldDCap=explode(",",$sFldDCap);
	$aLebarFldD=explode(",",$sLebarFldD);
	$aClassFldD=explode(",",$sClassFldD);
	$aAllowEditFldD=explode(",",$sAllowEditFldD);
	$aAlignFldD=explode(",",$sAlignFldD);
}
	
if ($op=='showdef'){
	echo "
	<pre>
	$"."sFieldCSV=\"$sField\";\n
	$"."sFieldCaptionCSV=\"$sFieldCaption\";\n
	</pre>
	";
}

if ($opcek==1) {	
	include $um_path."input-std-cek.php";	
	echo $pesCek;
	exit;
} elseif (op('tb,ed')) {
	if ($isTest) echo "opcek---->$opcek";
	if ($updateTrans) include $um_path."input-std-ed.php";
} elseif (op('viewdaf,viewlap')) {
	//$useDataTable=false;
	$showOpr=0;
	$cari=$_REQUEST['cari']='cari';
	$showEximCSV=false;
	if (op("viewdaf")) $op="showtable"; 
 } elseif ($op=='filter') {
	include $um_path."input-std-filter.php";	
}else if ($op=='view') {
	
	$showOprD=false;
	if (!isset($showNoD)) $showNoD=false;
	if (!isset($addfD)) $addfD="";
	if (!isset($sAlignFldD)) $sAlignFldD=",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,";
	$sAlignFldD=str_replace(array("r","l","c"),array("right","left","center"),strtolower($sAlignFldD));
	$aAlignFldD=explode(",",$sAlignFldD);
 
	if ($target=="file") {
		$idview="tview_<?=$"."rnd?>";	
		
	}else {
		$idview="tview_$rnd";	
	}
	if (!isset($judulInput))	$judulInput= "Data $nmCaptionTabel";
	//$sq="Select * from $nmTabel ";
	if ($id==""){
		if (isset($_SESSION["newid_$det"])) $id=$_SESSION["newid_$det"];		
	}	
	
	$sq=$sqTabel;
	if ($id!='') {
		$sq.=" where $nmTabelAlias.$nmFieldID='$id' ";
	} else {
		$sq.=" where 1=2 ";
		
	}
	$r=array();
	
	//$sq;
	extractRecord($sq);
	$r=$row;
	if ($target!='file') {	
		if ($nfCustom!="") {			
			include $nfCustom;
			exit;
		} 
	} 
	include $um_path."input-std-view.php";
} elseif ($op=='del') {
	/*
	if ($addfae=='') {
		if (!isset($currnd))$currnd=$rnd;
		$prevrnd=$currnd;
		$url="$('#thal_$prevrnd').html()+'&cari=cari'";
		if ($useDataTable) {
			$funcAfterEdit.="location.href=removeAmp('$linkback');";
		} else {
		 	$funcAfterEdit.="bukaAjax('tcari_"."$prevrnd',removeAmp($linkback),0,awalEdit($prevrnd));";
		}
	} else {
		$funcAfterEdit.="$addfae;";
	}
	*/
	$ssq="";
	if ($id=='') {
		echo "tidak ada data yg dihapus";
		exit;
	}
	
	$xid=explode(",",$id);
	foreach ($xid as $ids ) {
		if ($useLog) {
			mysql_query2("update $nmTabel set modified_by='$userid' where $nmTabelAlias.$nmFieldID='$ids'",
			"Peghapusan data $nmTabel dengan  $nmFieldID:$ids");
		}
		$sq="delete from $nmTabel  where  $nmFieldID='$ids' $sqSecureUpdateTabel";
		$h=mysql_query2($sq);
		$ssq.="<br>$sq";
		
		//ini belum fix
		if ($nmTabelDet!='') {
			$sq="delete from $nmTabelDet where  $nmFieldIDDet='$id' ";
			$ssq.="<br>$sq";
			$h=mysql_query2($sq);
		}	//echo $sq;
	}
	
	if ($komentarHp=='') {
		$komentarHp="<div style='display:none'>-ok-</div>Penghapusan data $nmCaptionTabel Berhasil...";
	}
	$pesan=$komentarHp;
	echo "";
}
elseif (($op=='itb')||($op=='itbd')) {
	if ($target!='file') {
		if (file_exists($nfCustom)) {
			include $nfCustom;
			exit;
		} else {
			include $um_path."input-std-itb.php";
			echo $hasil;
				 
		}
	} else {
		include $um_path."input-std-itb.php";
		echo "buat file $nfOutput ";
		createGenFile($nfOutput,$isi=$hasil);
		exit;
	}
	
}


if (op("showtable,unduhformat,importcsv,importxls,exportcsv,exportxls,viewlap")!='') {
	if (($userid=='Guest') && ($op=='showtable')) {
		if (!isset($showOpr)) $showOpr=0;
		$showLinkTambah=false;
		if (!$allowGuest) {
			reportHacked2();
			die();
		}	
	}

	cekvar("formatOnly");
	if (!isset($sFieldCSV)) {
		$sFieldCSV="";
		$i=0;
		foreach ($aField as $fld) {
			if ($aShowFieldInInput[$i]==1) $sFieldCSV.=($sFieldCSV==""?"":",").$fld;
			$i++;
		};
	}
	if (!isset($sFieldCaptionCSV)) $sFieldCaptionCSV=strtoupper($sField);
	if (!isset($sFieldCaptionCSV)) $sFieldCaptionCSV=strtoupper($sField);
	
	if (op("unduhformat")) {
		cekVar("delim");
		if ($formatOnly=="") $formatOnly=1;
		require_once $um_class."exportImportCSV.php";	
		$im=new exportImportCSV;
		$im->sql=$sqTabel;
		$im->capImport="Import $nmCaptionTabel";//caption tombol import
		$im->sFieldCSV=$sFieldCSV;//"id,nama,kontingen,kelas,g1semi,link1semi,g2semi,link2semi,g1final,lingk1final,g2final,link2final";
		if ($delim!="") $im->delimiter=($delim=='TK'?';':',');
		$im->sFieldCSVCaption=$sFieldCaptionCSV;//"id,nama,klub,kelas,g1semi,link1semi,g2semi,link2semi,g1final,lingk1final,g2final,link2final";
		$im->nfExport="format_$det.csv";	
		$im->formatOnly=$formatOnly;	
		
		$im->sampleRow="";//"contoh:1,andi,JAGUAR,kelas/kategori,TGK 1,http://www.youtube.com/v=9999";	
		$im->execExport();
		exit;
	} elseif (op("importcsv")) {
		require_once $um_class."exportImportCSV.php";	
		$pes="";
		$im=new exportImportCSV;
		$im->nfCSVImport =$_FILES['nff']["tmp_name"];
		$im->formatTglCSV='dmy';
		$im->sFieldKey=(isset($sFieldIdImport)?$sFieldIdImport:$nmFieldID);
		$im->title="Import ".$nmCaptionTabel;//caption tombol import
		
		$im->sFieldCSV=$sFieldCSV;//"id,nama,kontingen,xxkelas,xxg1semi,xxlink1semi,xxg2semi,xxlink2semi,xxg1final,xxlingk1final,xxg2final,xxlink2final";
		$im->sFieldCSVCaption=$sFieldCaptionCSV;//"id,nama,klub,kelas,g1semi,link1semi,g2semi,link2semi,g1final,lingk1final,g2final,link2final";
		if (isset($aFieldFuncCSV)) $im->aFieldFuncCSV=$aFieldFuncCSV;
		
		if (isset($sFieldCsvAdd)) {
			$im->sFieldCsvAdd=$sFieldCsvAdd;
			$im->sFieldCsvAddValue=$sFieldCsvAddValue;
		}
		$im->syImport=(isset($syImport)?$syImport:"");
		//if (isset($sRowCSVFunc)) $im->sRowCSVFunc=$sRowCSVFunc;
		//if (isset($aFieldFuncCSV)) $im->aFieldFuncCSV=$aFieldFuncCSV;

		
		if (isset($sSqlAfterImport)) $im->sSqlAfterImport=$sSqlAfterImport;
			//"carifield(\"select kelas  from tbkelas where kelas='-#kelas#-' \")!='';";
		$im->execImport();
		
		$pes.=$im->pes;
		
		echo "<div style='max-height:300px;overflow:auto;padding:5px'>";
		echo $pes;
		echo "</div>";
		if ($isTest) {
			echo "sql:<br>";
			var_dump($im->arrSQ);
			echo $im->log;
		}
		//exit;
		$addf.="$('#tbumum$parentrnd').DataTable().columns.adjust().draw();";
		echo fbe($addf);
	}
	elseif ($op=='importxls') {
		//belum selesai coding.... untuk format tanggal	
		//import  data ke $arrTable
		$startRow=4;//mulai baris 4
		include $um_path."importXLSX.php";
		include $um_path."importXLSXtoDB.php";
		//exit; 
	}
	elseif ($op=='exportxls') {
		$showresult=false;
		cekVar("jexport");
	}

	//ada tambahan filter

	$nf1=$adm_path."filterreport.php";
	$nf2=$um_path."frmreport_v3.0.php";
	if (file_exists($nf1))
		$nf0=$nf1;
	elseif (file_exists($nf2))
		$nf0=$nf2;


	include $nf0;
	if ($op3=='json') $_SESSION['data'][$nmTabel]['sql']=$sql;
	
	if (!op("importcsv,importxls,viewlap")) {
		
		if ($media=='print') {
			$nfc="style-cetak.css";
			echo "  <link rel='stylesheet' type='text/css' media='all' href='$js_path"."style-cetak.css' />";
			echo "<div class=page-landscape >";
		}

		
		$nfr=$um_path."showtable-default.php";		
		if ($nfReport=='') $nfReport=$toroot."adm/m".$det."/showtable.php";
		if (file_exists($nfReport)) {
			$nfr=$nfReport;
		}
		include $nfr;
		$t.="
			<textarea id=tfbe"."$rnd style='display:none'>
				$addfbe
			</textarea>
			";//
		 
		$t.="
		<script>
			
			$(document).ready(function(){
				$addfbe
				});
			</script>
			";	
		 
		
		//$linkNext="$thisFile&noth=1&lim=".($lim+$jperpage )."";
		
		/*
		$linkNext="$nfrep&noth=1&lim=".($lim+$jperpage )."";
		if ($br>=$jperpage) $t.="<div id='tload' ><a href='$linkNext&cari=cari' onclick=\"bAjax('$linkNext');return false\" style='position:relative' id='linknext'>Next Page</a></div>";
		*/
		
		
		if (($isDetail==1)||($cari=='')){
			$t.="</div>"; //akhir tcari
		//	if ($isDetail==1) $t.="<br>".$linkTambah; //akhircari
			
		}
		
		
		if ($showresult) {
			echo "<div id=twrapper$rnd style='overflow: hidden;height: 100%;'>";
			echo $t;
			echo "</div>";
		}
		if ($op=='exportxls') {
			//echo "op------------------$op i";
			include $um_path."sql2xls.php";
			exit;
		}


	include_once "cetak-print-foot.php";
		
	}
	elseif (op("viewlap")) {
		$nfr=$um_path."input-std-viewlap.php";		
		include $nfr;	
	}
}

?>