<?php
cekVar("isDetail,cetak,op2,order,jlhrecord,inputcari,ktg,addOutput,addInputFilterTable,t,isiComboFilterTabel,media,op3");
//echo "us $useDataTable >";

/*
note:
refresh:
$('#tbumum$rnd').DataTable().columns.adjust().draw();

*/
ini_set('date.timezone', 'Asia/Jakarta'); 
//if (!isset($showNoInTable)) 
$showNoInTable=false; //jika true blm sempurna

if (!isset($showOpr)) $showOpr=1;
if (!isset($jperpage)) $jperpage=$record_per_page=20;
if (!isset($jpperpage))$jpperpage=50;
if (!isset($showFrmCari)) $showFrmCari=true;
if (!isset($showTbRefresh)) $showTbRefresh=true;
if (!isset($showTbHapus)) $showTbHapus=true;
if (!isset($showTbView)) $showTbView=true;
if (!isset($showTbUbah)) $showTbUbah=true;
if (!isset($showTbPrint)) $showTbPrint=true;
if (!isset($showTbTambah)) $showTbTambah=true;//showTbTambah
if (!isset($showTbFilter)) $showTbFilter=false; 
if (!isset($showExportDB)) $showExportDB=false;//export database
if (!isset($showDT1Page)) $showDT1Page=false;//export database
if (!isset($tbOprPos)) $tbOprPos=1;

if (!isset($showresult)) $showresult=true; 
if (!isset($showLinkAdvance)) $showLinkAdvance=false; 
if (!isset($useDatatable)) $useDatatable=false; 
if (!isset($sqGroupTabel)) $sqGroupTabel=""; 
if (!isset($sqOrderTabel)) $sqOrderTabel=""; 

if (!isset($addTbTb)) $addTbTb=""; 
if (!isset($isTest)) $isTest=false; 
if (!isset($paramOpr)) $paramOpr=""; //tambahanparameter


if (!isset($responsiveDT)) $responsiveDT=true; 

if (!isset($tbEximPos)) $tbEximPos='right';
if (!isset($showTbUnduh)) $showTbUnduh=true;
if (!isset($showTbUnggah)) $showTbUnggah=false;
if (!isset($showEximCSV)) {
	if (isset($nfCsv))
		$showEximCSV=($nfCSV==''?false:true);
	else
		$showEximCSV=true;
}
if (!isset($levelOwner)) $levelOwner=10;
if (!isset($showButtonDT)) $showButtonDT=false;
if (!isset($addInpImport)) $addInpImport='';
if (!isset($addTbOpr2)) $addTbOpr2='';
if (!isset($showCheckboxDT)) $showCheckboxDT=true;
if (!isset($posTitleDT)) $posTitleDT=1;

if (!isset($sqFilterTabel)) $sqFilterTabel=""; 
if (!isset($sqOrderTabel))  $sqOrderTabel="";



if (!isset($showDetailInNewTab))  $showDetailInNewTab=false;
$sdint=($showDetailInNewTab?'true':'false');

if ($op3=='json'){
	ini_set('max_execution_time',60*3);//3menit
	ini_set('memory_limit', '200M');
}
	
//if ($useDataTable) $showNoInTable=false;//tanpa nomor
$nmtbexim="tbexim$rnd"; //nama tombol exim
$sqlLimit='';
$requestData= $_REQUEST;//json
//jika menamplkan dari sql langsung.....
if (!isset($aField)) {
	if (!isset($showOpr)) $showOpr=1;
	if (!isset($hal)) $hal="index.php?det=$det";
	$sqTabel=strtolower($sqTabel);
	$sAllField = getArrayFieldName($sqTabel,"#");

	//echo $sqTabel;
	
	//extractisisallfield
	$aAllField=explode("#",$sAllField);
	$jlhField=count($aAllField);
	
	$gAddDetail=$gFieldInput=$gFieldInputCap=$gFieldView=$gFieldTabel=$gGroupInput=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

	$aField=$aFieldCaption=$aFieldEditable=$aFieldEditableX=$aLebarFieldInput=$aShowFieldInInput=$aShowFieldInView=array();
	$aFieldSpecial=$aUpdateFieldInInput=$aShowFieldInTable=$aLebarFieldTabel=$aRataFieldTabel=$aCek=array();
	$i=0;
	$jLebar=0;
	
	foreach($aAllField as $aa){
		//echo"$aa >";
		$aDetField=explode("|","-|".strtolower($aa)."|".strtoupper($aa)."|40|1|1|1|50|C|4|||");
		array_push($aFieldSpecial,trim($aDetField[0]));
		array_push($aField,trim($aDetField[1]));
		array_push($aFieldCaption,trim($aDetField[2]));
		array_push($aLebarFieldInput,trim($aDetField[3]));
		array_push($aShowFieldInInput,trim($aDetField[4]));
		array_push($aUpdateFieldInInput,trim($aDetField[5])*1);
		array_push($aShowFieldInTable,trim($aDetField[6]));
		array_push($aLebarFieldTabel,trim($aDetField[7])*1);
		array_push($aRataFieldTabel,(trim($aDetField[8])=='C'?"center":(trim($aDetField[8])=='R'?"right":"left")));
		array_push($aCek,trim($aDetField[9]));
		array_push($aShowFieldInView,trim($aDetField[10])==''?1:trim($aDetField[10]));
		//mengatur siapa yang boleh edit
		$cafe=trim($aDetField[11]);

		if ($cafe=="n")
			$ae=false;
		else if (($cafe=='') || ($cafe=='1'))
			$ae=true;
		else if (($cafe=='noedit'))
			$ae=($id==0?true:false);
		else if ($cafe*1==0)
			$ae=true;
		else if ($cafe*1<=$levelOwner)
			$ae=true;

		else
			$ae=false;
		
		array_push($aFieldEditable,$ae);
		array_push($aFieldEditableX,$cafe);
		
		if ($aShowFieldInTable[$i]!=0) $jLebar+=$aLebarFieldTabel[$i];
		$i++;
	}
	if (!isset($nmFieldID)) $nmFieldID =$aField[0];
	
	$addParamAdd ="";
	$configFrmInput ="";
	if (!isset($cari)) $cari="";
	//$showTbTambah =false;
	$sHFilterTabel ="";
	$addfbe="";
	$fbe="";
	$identitasRec="trc".$det;
}

if ($isDetail==1) addParamOpr("isDetail",$isDetail);
if ($contentOnly==1) addParamOpr("contentOnly",$contentOnly);
	

if (!isset($fbe)) $fbe="";
if (!isset($addfbe)) $addfbe="";

cekVar("media");
if ($media!='') {
	$showOpr=0;
	$showFrmCari=false;
	$showTbTambah=false;
	$showTbFilter=false;
	$showEximCSV=false;
	
	$showTbUnggah=false;
	$showTbUnduh=false;
	
}
if ($id!='') { 
	addFilterTb(" $nmTabelAlias.$nmFieldID='$id'");
	$hal.="&id=$id"; 
}
	
	
$gqs=getQueryString('+,op2,op3');

if ($useDataTable) {
	$useTextboxSearch=false ;
	//$halDT=$thisFile."&op3=json&cari=cari&newrnd=$rnd";
	//$halDT=$hal."&op3=json&newrnd=$rnd";
	$url=$hal."&newrnd=$rnd&".$gqs;
	$halDT="index.php?contentOnly=1&op3=json&useJS=2&".getQueryString("",$url);
}
	
	
//filter tabel
/*
		array('tingkat','$nmTabelAlias.tingkat|like','Tingkat :  '.um412_isicombo6(\"$"."sTingkat\",'xtingkat',\"#url#\"),'defXI'),
*/
if (isset($aFilterTb)) {
	//$s='global $"."det;$"."addTbOpr2;';
	$urlR0="'index.php?det=$det'";
	$s="";
	$sesdet=array();
	foreach ($aFilterTb as $sf) {
		$fld=$sf[0];
		$strInp=$sf[2];
		//$def=isset($sf[3])? $sf[3]:'';
		//eval("if (isset($"."_REQUEST['"."x$fld']))  $"."_SESSION['$det"."_$fld']=$"."_REQUEST['"."x$fld'];");
		$def=(isset($_SESSION["$det"."_$fld"])?$_SESSION["$det"."_$fld"]:(isset($sf[3])? $sf[3]:''));
		
		//echo "def --------------$def >>";
		$ofld=explode("|",$sf[1]."|=");
		$fldtb=$ofld[0];
		$banding=$ofld[1];
		//sesi
		$sBanding="";
		if ((strstr($fld,"button")!='')||(strstr($fld,"checkbox")!='')) {
			/*
			$ofa=explode("|",$fld);
			$fld=$ofa[0];			
			$s.="	
			if ($"."x$fld!='') {
				addParamOpr('$fld',$"."x$fld); 
			}
			";
			if ($ofa[1]=="button") 
				$urlR0.="+'&x$fld='+encodeURI($('#x$fld"."_$rnd').val())";	
			elseif ($ofa[1]=="checkbox") 
				$urlR0.="+'&x$fld='+$('#x$fld"."_$rnd').attr('checked')";	
			$addTbOpr2.=$strInp;	
			*/
		} else {
		
			if ($banding=='like') 
				$sBanding="addFilterTb(\"$fldtb like '%$"."x$fld%'\");";
			elseif($banding=='none') 
				$sBanding="";//"addFilterTb(\"$fldtb='$"."x$fld'\");";
			elseif($banding=='d') 
				$sBanding="addFilterTb(\"$fldtb = '\".tgltosql($"."x$fld).\"'\");";
			else
				$sBanding="addFilterTb(\"$fldtb='$"."x$fld'\");";
			
			$strInp=$sf[2];
			//$s.="global $"."x$fld;";
			$s.="cekvar('x$fld');";
			if ($def!='') {
				$s.="if ($"."x$fld=='')  $"."x$fld='$def';";
			}
			/*
			$s.="
			if ($"."x$fld!='') {
				$"."_SESSION['$"."det']['$fld']=$"."x$fld;	
			} else {
				if (isset($"."_SESSION['$"."det']['$fld'])) $"."x$fld=$"."_SESSION['$"."det']['$fld'];
			}";
			*/
			$s.="	
			if ($"."x$fld!='') {
				$sBanding
				addParamOpr('$fld',$"."x$fld); 
			}
			";
			$urlR0.="+'&x$fld='+encodeURI($('#x$fld"."_$rnd').val())";	
			$addTbOpr2.=$strInp;	
		}
	}
	eval($s);
	//$addTbOpr2=str_replace("#url#","location.href=$urlR0;",$addTbOpr2);
	//$addTbOpr2=str_replace("#onc#","onchange=\"location.href=$urlR0;\" ",$addTbOpr2);
	
	$urlR0.="+'&contentOnly=1&op3=json3'";
	$addTbOpr2=str_replace("#url#","bukaAjax('tcari_$rnd',$urlR0);",$addTbOpr2);
	$addTbOpr2=str_replace("#onc#","onchange=bukaAjax('tcari_$rnd',$urlR0);\" ",$addTbOpr2);
	//$('#tbumum$rnd').DataTable().columns.adjust().draw();
}

	
	
$xhal="";
$xhal=str_replace("&useJS=2","",$hal)."&op=$op&newrnd=$rnd";
$xhal=str_replace("index.php","content1.php",$xhal);
$xhal=str_replace("cari=cari","cari=",$xhal);

$halPrint=$xhal."&media=print";
$halXls=$xhal."&op=exportxls&media=xls&useJS=2&jexport=db";
$idtd="tinput_$rnd";
$judul3a="";
$arrTable=array();

//judul table
$jdlTabel="<thead><tr>";
$footTabel="<tfoot><tr>";
$lines=array();
if ($showNoInTable) {
	$jdlTabel.="<th class=tdjudul style='width:30px' >No.</th>";
	$footTabel.="<th>0</th>";
	$lines[]="No";
}

//if (($levelOwner>=1) && ($showOpr==1)) {
if ($showOpr==1) {
	$WA=($tbOprPos==2?70:40);
	$jd=($tbOprPos==2?'Aksi':" ");
		$jdlTabel.="<th class=tdjudul>$jd</th>";
		$footTabel.="<th style='text-align:center' ><div id='chall_$rnd'></div></th>";
	$lines[]=$jd; 
}


//if (!isset($needRescale)) $aLebarFieldTabel=hitungSkala($aLebarFieldTabel,$maxUkuran=900);
$pathUpload2=substr($pathUpload,3,strlen($pathUpload));
$pathUpload2=$pathUpload;
//$sFld="";
$jLebar=0;

$columns=array();
$nocolumn=array();
$columns[0]=$aField[0];//digunakan untuk json
$nocolumn[0]=0;
$noc=1;//noc:nomor kolom sesuai yang tampil saja
$defICSearchDT="";	



for	($i=0;$i<$jlhField;$i++) {
	$nmfield=$aField[$i];
	//memberikan filter sesuai dengan field untuk showtable
	if (isset($_REQUEST["$nmfield"] )) {
		$v=$_REQUEST[$nmfield];
		addFilterTb(" $nmTabelAlias.$nmfield='$v' ");
		$hal.="&$nmfield=$v";
	}
	
	$xCek=explode(",",$aCek[$i].",0,0,0,0,0,0,0,0");
	if (($xCek[0]=='F')&&($media!='')) continue;
	if ($aShowFieldInTable[$i]<>'0'){			
		$columns[$noc]=$aField[$i];//digunakan untuk json
		$nocolumn[$noc]=$i;//digunakan untuk json
		
		$jLebar+=$aLebarFieldTabel[$i];
		$acap=explode(">",$aFieldCaption[$i]);
		$acap[0]=str_replace("_"," ",$acap[0]);
		
		$jdlTabel.="<th class='tdjudul wcol_$noc' align=center  >$acap[0]</th>";//
		$lines[]=$acap[0];
		
		$test=$filterDtField[$i];
		
		if (($filterDtField[$i]=='0')||($gKolDT[$i]=='0')||(substr($aShowFieldInTable[$i],0,2)=='xx')||(substr($aField[$i],0,2)=='xx'))
			$isif="0";
		elseif ($filterDtField[$i]=='')
			$isif="$aLebarFieldTabel[$i]";
		else
			$isif="$filterDtField[$i]";
		//$footTabel.="<th style='text-align:center'>$isif|1</th>";
		cekVar("cari_$noc");
		eval("$"."defcari=$"."cari_$noc;");
		if ($defcari!="") {
			//jika dijalankan langsung 
			$defICSearchDT.="dataTable$rnd.columns($noc).search('$defcari').draw();";
		}
		$vth=($aNeedSearchInDT[$i]==0?0:$defcari);
		$footTabel.="<th style='text-align:center' def='$defcari'>$vth</th>";
		
		$noc++;
	}
}

//echo implode(",",$column);

$jdlTabel.="</tr></thead>";
$footTabel.="</tr></tfoot>";

if($op3!='json') $arrTable[]=$lines;
 

if ($order!='') {
	$aktg=explode(" ",$order);
	$nsy="";
	$lcr=count($aktg);
	if ($lcr==1) {
		$nsy="$order ";		
	} else {
		$nsy="";
		foreach  ($aktg as $iktg ) {
			$nsy.= ($nsy==''?'concat(':",' ', ")."$iktg";
		}
		$nsy=$nsy.")";
	}	
	$orderby=" order by ".$nsy;		
}

$sq=($sqTabel==''?"Select * from $nmTabel":$sqTabel);

 
if ($op3=='json') {
	$kolOrder=$columns[$requestData['order'][0]['column']];
	if (strstr($kolOrder,"<br>")!='') {
			$kolOrder="concat(".str_replace("<br>",",",$kolOrder).")";
	}
	$kolOrder2=$requestData['order'][0]['dir'];
	if (strstr($kolOrder2,"<br>")!='') {
			$kolOrder2="concat(".str_replace("<br>",",",$kolOrder2).")";
	}
	
	//if ($columns[$requestData['order'][0]['column']!='') {
	$sqOrderTabel=" ORDER BY $nmTabelAlias.". $kolOrder;
	$sqOrderTabel.=" ".$kolOrder2;
	//echo $sqOrderTabel;
	//}
	$lim=0;
	if (isset($requestData['start'])) {
		if (($requestData['length']*1)==-1) {
			$sqlLimit="  ";
			
		} else { 
			$sqlLimit="  LIMIT ".($requestData['start']*1)." ,".($requestData['length']*1)."   ";
			$lim=$requestData['start']*1;
		}
	}
	

	//pencarian dengan multiple column
		$sqf="";
		$akolom=$columns;
		
		$jkl=count($akolom);
		for ($i=1;$i<=$jkl;$i++) {
			if ( !empty($requestData['columns'][$i]['search']['value']) ) {  
				$fld=$akolom[$i];
				$v=$requestData['columns'][$i]['search']['value'];
				$reali=$nocolumn[$i];
				//echo "<br>$i -> fld:$fld, reali=$reali " ;
				//echo "gkoldt: $gKolDT[$reali] / reali $reali ";
				if ($gKolDT[$reali]=='') {					
					if (strlen($aShowFieldInTable[$reali])>2)
					$gKolDT[$reali]=$aShowFieldInTable[$reali];
					else
					$gKolDT[$reali]="$nmTabelAlias.$fld";
				} else if (($aShowFieldInTable[$i]!="0") && ($aShowFieldInTable[$i]!="1") ){
					//$gKolDT[$reali]=evalFieldView($i,'fld');
				}
				
				$kolRealy=$gKolDT[$reali];
				if (strstr($kolRealy,"<br>")!='') {
						$kolRealy="concat(".str_replace("<br>",",",$kolRealy).")";
				}
				elseif (strstr($kolRealy," ")!='') {
						$kolRealy="concat(".str_replace(" ",",' ',",$kolRealy).")";
				}
				if ($v=='kosong') 
					$sqf.=($sqf==''?'':' and ')."  $kolRealy ='' ";
				else {
					$aw2=substr($v,0,2);
					$aw1=substr($v,0,1);
					if (strstr('>=,<=',$aw2)!='') {
						$vs=substr($v,2,strlen($v)-2);
						$sqf.=($sqf==''?'':' and ')." $kolRealy $aw2 '$vs' ";    
						
					}
					elseif (strstr('<>',$aw2)!='') {
						$vs=substr($v,1,strlen($v)-1);
						$sqf.=($sqf==''?'':' and ')." $kolRealy $aw2 '$vs' ";    
					} elseif (strstr('=',$aw1)!='') {
						$vs=substr($v,1,strlen($v)-1);
						$sqf.=($sqf==''?'':' and ')." $kolRealy $aw1 '$vs' ";    
					}else {
						$sqf.=($sqf==''?'':' and ')." $kolRealy LIKE '%$v%' ";   
					}						
				}
			}
		}
		if ($sqf!='') 	addFilterTb($sqf);
}

 
$sqlall=$sq." ".$sqFilterTabel." ".$sqGroupTabel." ".$sqOrderTabel; 
if (($isTest)&&($op3!='json'))	echo "000>".$sqlall;
//cek media
//echo "<br>".$sqlall;


$jppXls=22000;
if (($cetak=='xls')||($media!='')) {	
	ini_set('max_execution_time',60*5);
	ini_set('memory_limit', '1064M');
	$jperpage=$record_per_page=$jppXls;
}
if ($isTest) $jperpage=100;
if (!isset($lim)) $lim=0;
$urltkn="index.php?$gqs";
//echo $urltkn."<br>";
//$thaltkn=$urltkn;
//$thaltkn=makeToken("linkback=".urlencode($urltkn)."");
$thaltkn=makeToken("linkback=$urltkn");

if ($useDataTable) {
	$useTextboxSearch=false ;
	/*
	$url=$hal."&newrnd=$rnd&".$gqs;
	$halDT="index.php?contentOnly=1&op3=json&useJS=2&".getQueryString("",$url);
	*/
	
	if ($showButtonDT || isset($addButtonDT)) {
		if (!isset($addButtonDT)) $addButtonDT=''; 		
		if (isset($showButtonDTCSV))	$addButtonDT.=($addButtonDT==""?"":",")."csv";
		if (isset($showButtonDTPrint))	$addButtonDT.=($addButtonDT==""?"":",")."print";
		
		$buttonDT="
			buttons: [ 
					$addButtonDT
				],			
			select: true,
			";
	} else 	{
		$buttonDT="
			buttons: [], 
		";
	
	}
	
	
	$nmtbl0="tbumum"."$rnd";
	$nmtbl="#$nmtbl0";
	$nmtbdt="tbdt$rnd";//nama id  button di datatable
	$nmtbsc="tbsc$rnd";
	if (!isset($dtdom)) {
		//$dtdom="lBptipr";//<\"#$nmtbsc\">
		$dtdom="lBpt";//<\"#$nmtbsc\">
		if ($showDT1Page) {
			$dtdom="Bt";//<\"#$nmtbsc\">
			$posTitleDT=3;
			$lengthMenuDT="[1000,-1], [1000,'All']";
		}
	}
	$clsTitleDT=($posTitleDT==1?"titleDT":"titleDT$posTitleDT");
	
	if (!isset($lengthMenuDT)) {
		if (isset($defLengthMenuDT))
			$lengthMenuDT=$defLengthMenuDT;
		else if ($isOnline) {
			$lengthMenuDT="[10, 25, 50,100,300], [10, 25, 50,100,300]";	
		} else {
			$lengthMenuDT="[10, 25, 50,100,300,1000,-1], [10, 25, 50,100,300,1000,'All']";
		}
	}
	$jlhf=$jlhField+1;
	if (isset($wTabel)) {
		$addfbe.="$('#tbumum"."$rnd').css('width','$wTabel')";
	}
	
	if(isset($defDTOrder ))  {
		$defOrderDT="[".$defDTOrder."]";
	}
	
	$addOrderDT="";
	if(isset($defOrderDT))  {
	//	$defOrderDT="[0, 'asc']";
		$addOrderDT="order: [$defOrderDT],";
	}
	
	if(!isset($addFnDT ))  { //fungsi baris
		$addFnDT="";
	}
	
	
	$pindahkantb="
	v=$('#$nmtbdt').html();
	 //$('#$nmtbl0"."_filter').html(v);
	 //$('#$nmtbdt').dialog();
	
	";
	
	//alignment table
	$colDef="";
	$targetL=$targetR=$targetC=$colW="";
	$i=0;
	$no=0;//($showNoInTable?1:0);
	//hitung lebar kolom
	$arrLebar=array();
	//$arrLebar[]=30;
	if ($showOpr==1) {
		$targetC.=($targetC==""?"":",").$no;
		$arrLebar[]=10;
		$no++;
		 
	}
	//colDef : mengatur perataan
	for($i=0;$i<count($aField);$i++){
		if ($aShowFieldInTable[$i]!="0") {
			if ($aRataFieldTabel[$i]=='left') {
				$targetL.=($targetL==""?"":",").$no;
			}
			elseif ($aRataFieldTabel[$i]=='center') {
				$targetC.=($targetC==""?"":",").$no;
			}
			elseif ($aRataFieldTabel[$i]=='right') {
				$targetR.=($targetR==""?"":",").$no;
			}
			//kolom
			$arrLebar[$no]=$aLebarFieldTabel[$i];
			$no++;
		}
	}
	
	if ($targetL!=""){
		$colDef.=($colDef==""?"":",")."{ className: 'text-left', 'targets': [$targetL]  }";
	}
	if ($targetC!=""){
		$colDef.=($colDef==""?"":",")."{ className: 'text-center', 'targets': [$targetC] }";
	}
	if ($targetR!=""){
		$colDef.=($colDef==""?"":",")."{ className: 'text-right', 'targets': [$targetR] }";
	}
	
	//echo $colDef;
	
	//atur kolom, lebar dll,lebar sengaja dipisahkan karena harus hitung skala dahulu
	$arrLebar=hitungSkala($arrLebar,100);
	for($i=0;$i<count($arrLebar);$i++){
		$colW.=($colW==""?"":",")."
		{ name: 'col$i', sortable: true, width: '$arrLebar[$i]%' }";
	}

	//var_dump($arrLebar);
	
	//$colW="";
	$columnW=($colW==""?"":"'columns':[
	$colW
	],");			
	
/*
//penggunaan
$aCssRow=array(
	array("data[3]=='0'",'bg-red'),
	array("data[4]=='0'",'func',"$(row).css('color','red')"),
	array("data[5]=='0'",'css','color','red'),
	array("unmaskRp(data[4])>0",'func',"  $(row).find('td:eq(4)').css('color', 'red');"),
);

if(data[4]==0) $(row).addClass('bg-red');
*/
	//memberikan warna baris jika....
	
	$createRow="";
	if (isset ($aCssRow)) {
		$createRow="
		'rowCallback': function( row, data, dataIndex){
		";
		foreach($aCssRow as $acr) {
			if ($acr[1]=='css') {
				$isi="$(row).css('$acr[2]','$acr[3]');";
			} elseif ($acr[1]=='func') {
				$isi="$acr[2];";
			} else {
				$isi="$(row).addClass('$acr[1]');";

			}
			$createRow.="
			if ($acr[0]) {
				$isi
			}  
			";
		}
		$createRow.="
		},
		";
		
	}
	//echo $createRow;
	
	//echo "hoho..................".$createRow;
	$addfbe.="
		var noc=0;
		stitle='';";
	if ($showOpr==1) {
	$addfbe.="
		var v='<input type=checkbox checked name=chall$det id=chall$det"."_$rnd onclick=tbCheckAll2(\"$det\",$rnd,this.checked) >';
		$('#chall_$rnd').html(v);";
	}
	
	//adminLTE
	$addHeightDT=($isDetail==1?154:315);
	if (strstr($tppath,'grap')) 
		$addHeightDT+=38;
	else
		$addHeightDT+=38;
	
	$nocmin=($showOpr==1?0:-1);
	$addfbe.="
		var noc=0;
		stitle='';
		
		$('$nmtbl tfoot th').each(function() {
			var title = $(this).text();
			var sv=$(this).text();
			stitle+=title;
			//alert(sv);
			av=(sv+'|2').split('|');
			v=av[0];
			mls=av[1];
			
			if (noc>$nocmin) {  
				if (v=='zoom') { 
					var  v=\"<i class='fa fa-search' style='margin-top:6px'></i>\";
					$(this).html(v);				
				
				} else if (v=='0') { 
					$(this).html(''); 
				} else   {//jika kosong, tidak perlu ada select
					lebar=10;
					$(this).html(\"<input type=text name='cari_\"+noc+\"' id='cariDT_\"+noc+\"' placeholder='cari \"+v+\"' size='\"+lebar+\"' value='\"+title+\"' mls='\"+mls+\"'/>\");
				}
			}
			noc++;
		}); 
		
		var lastval='';
		dataTable$rnd = $('$nmtbl').DataTable( {
			'autoWidth': false,
			$addOrderDT
			dom: '$dtdom',
			$buttonDT 
			$addFnDT 
			$columnW
			rowReorder: {
				selector: 'td:nth-child(2)'
			},
			".($responsiveDT?"responsive: $responsiveDT,":"")."
			'columnDefs': [
			$colDef
			],
			/*fixedColumns: {leftColumns: 1},*/
			'deferRender': true,
			'processing': true,
			'serverSide': true,
			'orderCellsTop': true ,
			'scrollY' :'300px',
			'scrollX' : true,
			scrollCollapse: true,
			'lengthMenu': [$lengthMenuDT],
			$createRow
			'search': {
				'caseInsensitive': false
			},	
		   oLanguage: {
			   sLengthMenu: '_MENU_',
			   'sEmptyTable': 'Tidak ada data yg ditampilkan',
				'sInfo': 'Tampilkan _START_ sd _END_ dari _TOTAL_ ',
				'sInfoEmpty': 'Tidak ada data yang ditampilkan',
				'sInfoFiltered': '(terfilter dari _MAX_ )',
				'sInfoPostFix': '',
				'sDecimal': '',
				'sThousands': ',',
				'sLoadingRecords': 'Loading...',
				'sProcessing': 'Processing...',
				
			},
			'ajax':{
				url :'$halDT', // json datasource
				type: 'post',  // method  , by default get
				error: function(xhr, textStatus, error){  // error handling
					$('.$nmtbl0-error').html('');
					$('$nmtbl').append(\"<tbody class='$nmtbl0-error'><tr><th colspan='$jlhf'>Err : Tidak bisa load data,  msg: \"+error+\"</th></tr></tbody>\");
					$('$nmtbl"."_processing').css('display','none');
					
				}
			},
			'drawCallback': function( settings) {
				var api = this.api();
				var json = api.ajax.json();
				
				 //$('#thaltkn_$rnd').html(json.haltkn);//belum jalan
				changeColDT('$det',$rnd,$tbOprPos);
				
			},
			initComplete: function() {
				//mengubah thal
				var api = this.api();
				var lastsearch=new Date();
				$pindahkantb
				
				// Apply the search
				api.columns().every(function() {
					var that = this;
					var lastKeyup=new Date();
					var intv=0;
					var src='';
					
					function redrawDT(){
						if (that.search() !== src) {
						  lastKeyup=now;
						  that
						  .search(src)
						  .draw();
						}
						//document.title='interval '+intv;
					}
						
					$('input', this.footer()).on('keyup change', function() {
						if ((this.value.length>0)|| (this.value.length==0)){
							now=new Date();
							intv=now-lastKeyup;
							src=this.value;
							redrawDT();
							//v=setTimeout('redrawDT()',100); 
						}
					});
				}); //api
				
				customDTHeight();
			} //INITcomplete
		} );
		
		//mengaturtingggi
		
		function customDTHeight(rnd) {
			
			h=window.hMax;
			hhead=$('.dataTables_scrollHead').outerHeight();
			hhead2=$('.dataTables_scrollHead').height();
			
			if (h>400) {
				hbody=h-hhead-$addHeightDT;
			} else {
				hbody=h-hhead;	
			}
			
			$('.dataTables_scrollBody').height( hbody );		
			$('.dataTables_scrollBody').css( 'max-height',hbody );		
			$('.dataTables_scrollBody').css('max-height',hbody);
		}
		
		customDTHeight($rnd);
		
		$('#tout$rnd').css('height','100%');
		//$('#tout$rnd').css('padding','5px');
		$('.dataTables_scrollFoot').css('width','100%');
		
		
		$('.tbumum').css('width','100%');
		$('table.dataTable').css('margin-top','5px !important;');
		$('.dataTables_scrollHeadInner').css('width','100%');
		
		//lebar tabel tombol operasi
		//wdi=$('#tout$rnd').width()-$('.sidebar-menu').width(); 
		//$('#ttbopr$rnd').css('width',wdi); 
	
		$('tfoot tr th').css('padding','0px');
		$('tfoot tr th input').css('width','100%');
		$('tfoot tr th input').css('text-align','center');
		//$('.dataTables_length').css('padding-left','5px');
		
		
		/*
		$('.dataTables_length select').css('height','24px');
		$('.dataTables_length select').css('padding','0px');
		$('.pagination>li>a').css('padding','1.5px 10px');
		$('.pagination li a').css('padding','1.5px 10px');
		
		$('a.dt-button').css('padding','3px 10px');
		$('a.dt-button').css('height','24px');
		
		$('a.page-link').css('padding','1.5px 10px !important');
		$('.paginate_button').css('padding','1.5px 10px');
		*/
	 
		//appy default search
		$defICSearchDT
		
		//dijalankan saat klik halaman
		dataTable$rnd.on( 'page.dt', function () {
			var info = dataTable$rnd.page.info();
			//$('#pageInfo').html( 'Showing page: '+info.page+' of '+info.pages );
			console.log('rnd $rnd page'+info.page);
		} );
		

	";
	
	/*
	$addfbe.="
	$('#tbumum$rnd tbody').on( 'click', 'tr', function () {
        $(this).toggleClass('selected');
		
    });
	";
	*/
	
} else {
	$hasilall=mysql_query2($sqlall) or die($sqlall);
	if (!$hasilall) echo "err: ". mysql_error()."<br> $sqlall";
	$jlhrecord=$nrall=mysql_num_rows($hasilall);

	$jlhpage=max(ceil($jlhrecord/$jperpage),1);
	$curpage=(int)($lim/$jperpage+1);
	$limx=($curpage-1)*$jperpage;
	$lima=$limx+1;
	$limb=$limx+$jperpage;
	if ($jlhrecord<$limb) $limb=$jlhrecord;//min($limx+$jperpage,$jlhrecord);

	if ($sqlLimit=='') $sqlLimit=" limit $lim,$jperpage"; 

	$sql=$sqlall.$sqlLimit; 
	//if ($isAdmin) echo $sql."<br>";
	$h=mysql_query2($sql);
	$nr=mysql_num_rows($h);

	$tglcetak=tglIndo();
	$crx=explode(',' ,'B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z'.
				 ',AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,AS,AT,AU,AV,AW,AX,AY,AZ'.
				 ',BA,BB,BC,BD,BE,BF,BG,BH,BI,BJ,BK,BL,BM,BN,BO,BP,BQ,BR,BS,BT,BU,BV,BW,BX,BY,BZ');
	$jlhfield=count($aField);
	$jlhcol=$jlhfield+1;
	$lastcol=$crx[$jlhfield-1];
	$scalaHtml=25;
	$scalaPDF=20;
	$scalaXLS=20;
	$w=explode(",","1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1".
			   ",1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1");
	$w_xls=$w_html=$w_pdf=$w; 

	$lh="";
	//$judul1=$nmCaptionTabel;
	//$judul2="Jumlah Record : $jlhrecord ";

	$lh=buatlinkPage3("tcari_$rnd",$sqlall,$hal."&noth=1",$jpperpage,$jperpage,'lim',$lim);
	$judul3a="Halaman $curpage dari $jlhpage halaman | Record $lima sd $limb dari $jlhrecord record"; 
}

 if ((($useDataTable) && (($op3=='json')||($op=='exportxls')))||(!$useDataTable) )	{
	$halx=str_replace("&useJS=2&contentOnly=1","",$hal);
	$_SESSION["hal"]=$halx;
	 
	$posfrom=strpos(strtolower($sqlall),"from");
	$sql="select 1 ".substr($sqlall,$posfrom,strlen($sqlall)-$posfrom);
	
	$hq=@mysql_query2($sql);
	if (!$hq) die("error : ". mysql_error()."<br> $sql"); 
	$nrall=mysql_num_rows($hq);
	
	//if ($op3!='json')  echo "eksekusi ...";
	$sql=$sqlall." ".$sqlLimit;
	
	$hq=@mysql_query2($sql);
	
	if (!$hq) die("error : ". mysql_error()."<br> $sql"); 
	$nr=mysql_num_rows($hq);
	$sq=$sql;
	
}
//echo $sq;
//-----------------------------------------------------------------------------------------------------------------export import
$formEximCSV="";
if (isset($addTbExim)) $formEximCSV.=$addTbExim;
//if ($showEximCSV) {
	$rndx=rand(1231,2317);
	//tombol hanya tampil jika nfcsv diisi.
	
//	if ($nfCSV!='') {
		$linkTbUnduh=$linkTbUnggah='';
		$linkUnduh="$hal&op=unduhformat&outputto=csv&newrnd=$rndx";
		if ($showTbUnggah) {
			if (!isset($capTbUnggah)) $capTbUnggah="Unggah Data";
			$linkTbUnggah.=createLinkTbUnggah($capTbUnggah);
		}
		
		//tbunduh belum fix
		//$showTbUnduh=false;
		if ($showTbUnduh) {
			$linkTbUnduh="
			<a class='btn btn-success btn-mini btn-sm' 
			href='$linkUnduh&formatOnly=0'  
			title='Unduh Data ".str_replace("Daftar","",$nmCaptionTabel)."'> 
			&nbsp;&nbsp;<i class='fa fa-download'></i> Unduh Data</a> 
			";
			/*
			$halExp=str_replace("op3=","op3x=","$halDT&op=exportxls");
			$linkTbUnduh="<a type=button href=# onclick=\"
			var dataTableU$rnd = $('$nmtbl').dataTable( {
			'processing': true,
			'serverSide': true,
			'bDestroy': true,
			'ajax':{url :'$halExp', type: 'post'}
			});
			\"   
			class='btn btn-mini btn-sm btn-primary' ><i class='fa fa-download'></i> Export</a> ";
			//bukaAjaxD('$idtd','$halXls','width:300,height:200','awalEdit($rnd)');	
			*/
			
		}
		

		$formEximCSV.=$linkTbUnduh.$linkTbUnggah;
		//exportdb
		 
		if (($userid=='admin') && ($showExportDB)) {
			
			$posfrom=strpos(strtolower($sqlall),"from");
			$sqx="select count(1) ".substr($sqlall,$posfrom,strlen($sqlall)-$posfrom);
			$nrx=carifield($sqx);
			$func="awalEdit($rnd)";
			$func="";
				
			if (($nrx>$jperpage) && ($useDataTable)) {
				$idttb="edb".$rnd;
				$xt="
				<a type=button href=# onclick=\"$('#$idttb').dialog({width:300});\"   
				class='btn btn-mini btn-sm btn-warning' ><i class='fa fa-download'></i> Export DB</a>
				<div id='$idttb' style='display:none;text-align:center'>";
				$jt=floor($nrx/$jppXls);
				for ($i=0;$i<=$jt;$i++) {
					$lm=$i*$jppXls;
					$pg=$i+1;
					$idtbx=$idttb.$i;
					$xt.=" 
					<span id=$idtbx>
					<a type=button href=# onclick=\"bukaAjax('$idtbx','$halXls&lim=$lm','width:300,height:200','$func');\"   
					class='btn btn-mini btn-sm btn-warning' ><i class='fa fa-download'></i> Page $pg</a></span> ";	
				}
				$xt.="</div>";
				$formEximCSV.=$xt;
			} else {
				$formEximCSV.=" <a type=button href=# onclick=\"bukaAjaxD('$idtd','$halXls','width:300,height:200','$func');\"   
				class='btn btn-mini btn-sm btn-warning' ><i class='fa fa-download'></i> Export DB</a> ";			
			}	
		}
		
	//}//import		 
//}
/*
if ($op2!='') {
	include "module/m$det/custom-$det.php";
	exit;
}
*/



$linkTambah=$linkOpr1="";

//refresh
//$onc="location.href='index.php?".getQueryString('+,op2,op3,newrnd') ."';";
$onc="$('#tbumum$rnd').DataTable().columns.adjust().draw();";

if ($showTbRefresh) {
	$linkOpr1.="
	<span  class='btn btn-primary btn-mini btn-sm ' id=tbrefesh$rnd 
	onclick=\"$onc\" /><i class='fa fa-refresh'></i> Refresh
	</span>
	";
}
if ($showTbTambah) {
	if (!isset($nmCaptionTambahData)) $nmCaptionTambahData="Tambah";
	//&idtd=$idtd
	//$onc="bukaAjaxD('$idtd','$hal&op=itb&newrnd=$rnd2&currnd=$rnd&contentOnly=1&tknlinkback=$thaltkn"."$addParamAdd','$configFrmInput','awalEdit($rnd2);');";
	$onc="tbOpr('tb','$det',$rnd,$rndInput,'$configFrmInput','0','awalEdit($rndInput);',$sdint);";
	$linkTambah="
	<span  class='btn btn-success btn-mini btn-sm '  
	onclick=\"$onc\"
	 value='$nmCaptionTambahData' /><i class='fa fa-plus'></i> $nmCaptionTambahData
	</span>
	";
	//
	
	$linkOpr1.=$linkTambah;
}
if ($tbOprPos==1) {
	if ($showTbUbah) $linkOpr1.="<span id=tbubah_$rnd class='btn btn-warning btn-mini btn-sm' onclick=\"tbOpr('ed','$det',$rnd,$rndInput,'$configFrmInput','awalEdit($rndInput)');\" value='Ubah' /><i class='fa fa-edit'></i> Ubah </span> ";
	if ($showTbView) $linkOpr1.="<span id=tbview_$rnd class='btn btn-info btn-mini btn-sm' onclick=\"tbOpr('view','$det',$rnd,$rndInput,'width:1200');\" value='Tampil' /><i class='fa fa-sticky-note-o'></i> Tampil</span> ";
	if ($showTbFilter) $linkOpr1.="<span id=tbfilter_$rnd  class='btn btn-primary btn-mini btn-sm' onclick=\"tbOpr('filter','$det',$rnd,$rndInput,'width:1200,height:350');\" value='Filter' /><i class='fa fa-filter'></i> Filter</span> ";
	if ($showTbHapus) $linkOpr1.="<span id=tbhapus_$rnd class='btn btn-danger btn-mini btn-sm' onclick=\"tbOpr('del','$det',$rnd,$rndInput,'$configFrmInput');\" value='Hapus' /><i class='fa fa-trash fa fa-recycle'></i> Hapus</span> ";
	 
	if (isset($addTbOpr)) $linkOpr1.=$addTbOpr;
	if (isset($addTbOpr1)) $linkOpr1.=$addTbOpr1;
	
	if (!isset($showEximCSV)) $showEximCSV=false;
	if (!$showEximCSV) {
		$formEximCSV="";
	}
	
	$linkOpr2=$addTbOpr2;
	if ($tbEximPos=="left") {
		$linkOpr1.=$formEximCSV;
	} else {
		$linkOpr2.=$formEximCSV;		
	}
	
	//
	$linkOpr="
	<div class='titleDT titleDT4'>$nmCaptionTabel</div>
	
	
	<table id='ttbopr$rnd' class='tbopr' width='100%' >
		<tr>
			<td >$linkOpr1</td>
			<td align=right><div id=$nmtbexim>$linkOpr2 </div></td>
		</tr>
	</table>
	";
	
	 
}

if (isset($judul))
	$judulTabel="<div class='titlepage'>$judul</div>";
else
	$judulTabel="<div class='titlepage'>Daftar $nmCaptionTabel </div>";

if (($cari=='')&&(!$useDataTable)) {
//if ($isiComboFilterTabel!='') {
	if ($showFrmCari) {
			
		$idForm="fcr_$rnd";
		$nfAct="$hal&newrnd=$rnd&cari=cari";//&valid=$valid
		$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','tcari_$rnd','','selesaiEdit($rnd)',false);return false;\" ";
		$link1a="<a href=# onclick=\"$"."('#taddinput').css('display','inline');
				$"."('#tbfad').css('display','none');\" id='tbfad' class=arrowright >Advance</a>";
		$link1b="<a href=# onclick=\"$"."('#taddinput').css('display','none');
			$"."('#tbfad').css('display','inline');\" id='tbfadb' class=arrowleft ></a>&nbsp;";
	
		$sHFilterTabel.="
		<form id='$idForm' action='$nfAct' $asf method=post class='frmcari'>
		<input type=hidden name=aid id=aid$rnd>
		<input type=hidden name=opx id=opx$rnd>
		";
		
		if ($isiComboFilterTabel!='') {
			$sHFilterTabel.="
			Cari : <input type=text tabindex=0 name=inputcari id=inputcari_$rnd value='$inputcari'>
			Di : ".um412_isicombo5($isiComboFilterTabel,'ktg');
			
		}	
		 
		if ($addInputFilterTable!='') {
			if ($showLinkAdvance) {
				$sHFilterTabel.="
				$link1a 
				<span id=taddinput style='display:none'> $addInputFilterTable $link1b</span>
				";
			} else{
			$sHFilterTabel.="<span id=taddinput> $addInputFilterTable  </span>";
			}
		}
		//<input type=submit  class='btn btn-primary btn-mini' value='CARI' name=cari> 
		
		$sHFilterTabel.="
		<a type=submit  class='btn btn-primary btn-mini btn-sm ' value='CARI' name=cari onclick=\"$('#$idForm').submit();\"><i class='fa fa-search'></i> Cari</a>	
		</form>
		";
	}
}

if ($showOpr!=0) {
	$sHFilterTabel.=($linkOpr!=''?"<span >$linkOpr</span>":"");
} else 
	$sHFilterTabel.="&nbsp;";
		
	//}
//	else 
if (!isset($yy))$yy=1; else $yy++;


if (strlen($sHFilterTabel)>10) {
		$sHFilterTabel=" 
	<div class=breadcrumb2 id=bc_$rnd >$sHFilterTabel</div>
	<div id='$identitasRec' style='display:none'></div>
	";
}	

if (($levelOwner>0) &&($op!='json')) {
	$t.=$sHFilterTabel;
}

 
$t.=" 
<div style='display:none'>
<span id=thal_$rnd  >$hal"."$addParamAdd</span>
<span id=thaldt_$rnd  >gqs: $gqs 
haldt: $halDT</span>
<div id=thaltkn_$rnd >$thaltkn</div>
<textarea id=tParamOpr_$rnd >$paramOpr</textarea>
</div>
";
if (($cari=='')||($isDetail==1)) {
	$fbe.=$addfbe;
	$tit="title='$nmCaptionTabel'";
	$t.="
	<span style='display:none'>
		<span id=tinput_$rnd $tit  class='tinput' style='max-height:300px;overflow:auto;' data='$configFrmInput'></span>
		<span id=tinput2_$rnd $tit  class='tinput' style='max-height:300px;overflow:auto;' data='$configFrmInput'></span>
		<span id=tinput_$rndInput $tit  style='max-height:300px' data='$configFrmInput'></span>
		<span id=tinput2_$rndInput $tit  style='max-height:300px' data='$configFrmInput'></span>
	</span>
	<div id='tcari_$rnd' class='tcari' $tit style='border:2px solid #fff' >
	";
	/*
	<div id=tfbe"."$rnd style='display:none'>$fbe</div>
	*/
	
	cekVar("pesan");
	if ($pesan!='') 
	$t.="<div id=tpesan_$rnd class='comment1 text-alert'>$pesan</div>";
}

if ($media!='print') {		
	$addTbTb=str_replace("#xhal#","$xhal",$addTbTb);
	/*
	<a type=button href='#' onclick=\"$"."('#tbumum"."$rnd').tableExport({type:'excel',escape:'false'});return false;\" target=_blank class='btn btn-sm btn-mini btn-primary' >
	<i class='fa fa-download'></i> Export This Page</a>
	*/
	$tbprint="<span id=tprint_$rnd class='noprint'>
	<a type=button href='$halPrint' target=_blank class='btn btn-mini btn-sm  btn-primary' ><i class='fa fa-print'></i> Cetak</a>
	<a type=button href=# onclick=\"bukaAjaxD('$idtd','$halXls','width:300,height:200');\"   class='btn btn-mini btn-sm btn-primary' >
	<i class='fa fa-download'></i> Export</a>
	
	<span id='tlx$rnd'></span>
	</span>
	";
	if (!$useDataTable) {
		$t.="
		<div id=tfbe"."$rnd style='display:none'>$fbe</div>
		";
		$t.="
		<div id=thal_$rnd style='display:none'>$hal&newrnd=$rnd"."$addParamAdd</div>
		
		<table width='100%' border='0' cellspacing='0' cellpadding='0' class='tbpage'>
		<tr>
		<td>$judulTabel </td>
		<td>&nbsp;</td>
		<td align=right>$addTbTb $tbprint $lh</td>
		</tr>
		</table>
		";
	}
} else {
	$nfc="style-cetak.css";
	echo "  <link rel='stylesheet' type='text/css' media='all' href='$js_path"."style-cetak.css' />";
}

$sqlTabel=$sqlall;//untuk keperluan export

?>