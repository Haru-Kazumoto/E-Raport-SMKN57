<?php
$useJS=2;
include_once "conf.php";

$nfFilterTb=$toroot."filtertb.php";
if (file_exists($nfFilterTb)) include $nfFilterTb;

$statDB=cekLockDB();
if ($statDB=='Locked') {
	echo "Database Locked....";
	exit;
}

cekVar("id,nmTabelOwner,cari,aksi,ktg,inputcari,valid,cr,idunit,unit,idpengda,pengda,idpengcab");
cekVar("pengcab,pelaksana,judulInput,isTest,namaPelaksana,op,op2,isDetail");
cekVar("newrnd,media,nfCSV,custom");
cekVar("owner,tkn");
cekVar("opcek");//cek data


if (!isset($showEximCSV))$showEximCSV=true;
if (!isset($showresult))$showresult=true;
if (!isset($paramOpr))$paramOpr="";
if (!isset($showLinkTambah)) $showLinkTambah=true;
if (!isset($nfReport)) $nfReport="";
if (!isset($nmFieldID)) $nmFieldID="id";
if (!isset($addParamAdd))$addParamAdd="";
if (!isset($jperpage)) $jperpage=10;
if (!isset($pathUpload)) $pathUpload='../images/';
if (!isset($nmTabelAlias)) $nmTabelAlias=$nmTabel;
if ($tkn!='') { evalToken($tkn);}
if (!isset($defOp)) $defOp="showtable";
if ($op=='') $op=$defOp;
if (!isset($sqTabel)) $sqTabel="select * from $nmTabel $nmTabelAlias ";
if (!isset($useLog)) $useLog=false;
if (!isset($saveLogSQL)) $saveLogSQL=true;
if (!isset($addfae)) $addfae='';
if (!isset($addfbe)) $addfbe="";
if (!isset($addf)) $addf='';
if (!isset($sqFilterTabel)) $sqFilterTabel='';
if (!isset($sqSecureUpdateTabel)) $sqSecureUpdateTabel="";

if (isset($sySecureShowTable)) {
	addFilterTb($sySecureShowTable);
}
if (!isset($sqTabel))  $sqTabel="select $nmTabelAlias.* from $nmTabel $nmTabelAlias ";
if (!isset($thisFile)) $thisFile=$nfref="index.php?det=$det&useJS=2&contentOnly=1";//&valid=1
if (!isset($tbsql)) $tbsql="";
if (!isset($isiComboFilterTabel)) $isiComboFilterTabel="";//"kdbrg;tbpbarang.kdbrg"; 

if (!isset($configFrmInput)) $configFrmInput="width:700,title:\'$nmCaptionTabel\' "; 
if (!isset($sqOrderTabel)) $sqOrderTabel="order by $nmTabelAlias.$nmFieldID desc";


if (!isset($addTbOpr2 )) $addTbOpr2="";

cekIsset("addSave1,addSave2,addSave3","");


if (!isset($addTbOpr2 )) $addTbOpr2="";
 

//detail
if (!isset($gFuncFldD)) $gFuncFldD=explode(",", ",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
if (!isset($addfD)) $addfD="";

$addfPokok=" ";

$addfD.=$addfPokok;
$addf.=$addfPokok;


//is opr 
if (($id=="")&& isset($aid)) $id=$aid;
$isCek=($opcek==1?true:false);
$isAddItb=(((($op=='itb') && ($id=='')) && (!$isCek))?1:0);
$isAdd=(((($op=='itb') && ($id==''))||($op=='tb')) && (!$isCek)?1:0);
$isEditItb=(((($op=='itb')&& ($id!='')))&& (!$isCek)?1:0);
$isEdit=(((($op=='itb')&& ($id!=''))||($op=='ed'))&& (!$isCek)?1:0);
$isInput=(($op=='itb')?1:0);

$identitasRec="trc$nmTabel"; 
//$nfReport="$folderModul/showtable.php"; 

$rnd2=rand(1231,8767);
$rndInput=rand(1231,8767);
$sfd=getArrayFieldName("$sqTabel where 1=2",",");
cekVar($sfd);

$confDInput='width=911;height=511';

$hal=$nfAction=$thisFile."&op2=$op2";
if (!isset($linkback)) $linkback=($isAdmin?"<br ><a href=# onclick=\"bukaAjax('content','$thisFile&op=showtable');\">klik di sini</a> untuk kembali...":"");
if ($newrnd=='')
	$rnd=rand(123451,923451);
else
	$rnd=$newrnd;

if ($det=='') $det=$_SESSION['det']; else  $_SESSION['det']=$det; 
$arrko=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

$gFuncImportCSV=array();
$gFieldLink=$gKolDT=$gFuncFld=$filterDtField=$gFieldJudulImport=
$gAddDetail=$gFieldInput=$gFieldInputCap=$gFieldView=$gFieldView3=$gFieldTabel=
$gGroupInput=$arrko;
if (!isset($gDefField)) $gDefField=$arrko;
$sHFilterTabel=""; 
$komentarHp=$komentarTb=$komentarEd=""; 
$targetWin="maincontent";
$infoInput1=$infoInput2=""; 
//tambahan parameter untuk tombol add
$addCek=$addView=$addInput0=$addInput=$addFuncAdd=$addFuncEdit="";
$jpperpage=15;

$idForm="form_".$rnd;
$idRecord=$id;
$isowner=false;

$aCek=array();
$nmTabelDet="";

if ($op=='') {
	cekVar('opx');
	if ($opx!='')
		$op=$opx;
	else
		$op=$defOp;
}

//pengamanan tampilan dan update

//echo "user $userType";
if ($nmTabelOwner=='') $nmTabelOwner=$nmTabel;

 

if (($op=='itb') &&($id!=0)||($op=='view') ){
	$sqe="select * from $nmTabel where $nmTabelAlias.$nmFieldID='$id' ";
	if (isset($sqTabel)) 	$sqe=$sqTabel." where $nmTabelAlias.$nmFieldID='$id'  ";
	extractRecord($sqe);	
}


$now=date("Y-m-d h:i:s");

$strget=$strget2="";
getQueryString();

/*
//ambil linkback
if (isset($tknlinkback)) {
	evalToken($tknlinkback);
	$linkback=urldecode($linkback);
} else {
	$linkback="index.php?det=$det";	
}
*/

if ($isEdit) {
	if  ($op=='itb') { 
		$sq="$sqTabel where $nmTabelAlias.$nmFieldID='$id'";
		extractRecord($sq);
 
	} else {
		
	}
 
}

$nmf=$adm_path."protected/";	
$nmf.=",".$adm_path."protected/controller/";
$nmf.=",".$adm_path."protected/model/";
$nmf.=",".$adm_path."protected/view/";
//$nmf.=",".$adm_path."protected/view/$det/";
folder_exists($nmf,1);
//controller
$nfco= cekFileControllerLocal("controller.php",false);

if (file_exists($nfco)) include_once $nfco;
