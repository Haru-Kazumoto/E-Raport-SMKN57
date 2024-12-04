<?php 
 	if (!isset($updateTrans)) $updateTrans=true;

	$sfldUpdate=$sfldInsert=$sfldInsertu="";
	$i=0;
	//$vvFieldId=$_REQUEST[$nmFieldID];
	foreach ($aField as $nmField) {
		$xCek=explode(",",$aCek[$i].",0,0,0,0,0,0,0,0");
		$jenisInput=$aJenisInput[$i];
		$willUpdate=false;
		$willUpdate=($aUpdateFieldInInput[$i]==1?true:false);
		$skip=false;
		if ($op=='ed') 
			if (($willUpdate) && ($aFieldEditable[$i])) $willUpdate=true;
		else
			if (($willUpdate) && ($aUpdateFieldInInput[$i])) $willUpdate=true;
		
		if ($nmField=='id') $willUpdate=false;
		
		if ($willUpdate) {
			if ($jenisInput!='F') {
				if (isset($_REQUEST[$nmField])) 
					$vv=validasiInput($_REQUEST[$nmField]);
				else {
					eval("$"."vv=$"."$nmField;");
				}
			}
			//------------------tanpa else
			
			if (($jenisInput=='C') ||($jenisInput=='N')) {
				
					$vv=unmaskRp($_REQUEST[$nmField]);
					 
			}
			elseif ((strpos(" ".$nmField,"tgl")>0)||(strpos(" ".$nmField,"tanggal")>0)||($jenisInput=='D'))  { 					
				if (($nmField=="tgl") && (isset($_REQUEST['tgl']))) {
					if ((substr($vv,2,1)=="/") ||(substr($vv,2,1)=="-")) {
						$vv=tgltoSQL($vv);
					}
				} else
				  $vv=tgltoSQL($vv);
					
				//$vv=$_REQUEST[$nmField]=tgltoSQL($_REQUEST[$nmField]);
				
			} elseif (($jenisInput=='F')) { //update &&($xCek[2]=='U')
				if (isset($_FILES[$nmField])) {
					//id belum terisi jika tambah
					$rndf=rand(123451,423423);
					$rndd=date("dmYhis");
					/*
					if ($xCek[4]==1) {
						$nmfTarget="";
					}
					*/
					if (!isset($gPathUpload[$i])) {
						$gPathUpload[$i]=$pathUpload;
					} else {
						if ($gPathUpload[$i]=="") $gPathUpload[$i]=$pathUpload;

					}		
					//ubah
					$gPathUpload[$i]=gff($gPathUpload[$i],$aField);
					
					if (isset($gDefNF[$i])) 
						$nmfTarget=$gDefNF[$i];
					else
						$nmfTarget="$nmField-#$nmFieldID#-$rndd-$rndf.ext";
					
					$nmfTarget=gff($nmfTarget,$aField);
					
					
					$vv=uploadFile($nmField,$folderTarget=$gPathUpload[$i],$tipe="all",$maxfs=0,$nfonly=1,$nmfTarget,$showPes=0,$overwrite=($xCek[3]==1?true:false));
					if ($isTest) {
						echo "<br>Cek Upload file: $nmField > hasil:$ff <br>
						<nt>uploadFile($nmField,$gPathUpload[$i],all,maxfs : $maxfs,nfonly:$nfonly,$nmfTarget,$showPes,$overwrite));
						<br>hasil:$vv 
						";
					}					
					
					if($vv!='') {
						if ($isTest)  echo "<br>Upload file: $vv ";
						 
						if ($sfldUpdate!='') $sfldUpdate.=",";
						if ($xCek[3]==1){ //overwrite
							$sfldUpdate.="$nmField='".$vv."'";	//jika bukan file
						} else {	
							//JIKA ## DIUBAH JADI #
							$sfldUpdate.="$nmField=replace(concat($nmField,if($nmField='','','#'),'".$vv."'),'##','#')";	//jika bukan file,2
						}
						if ($sfldInsert!='') {
							$sfldInsert.=",";
							$sfldInsertu.=",";
						}
						$sfldInsertu.="$nmField";	
						$sfldInsert.="'$vv'";
						
					}
				}
				else $vv='';

			} elseif (($jenisInput=='P')) {//password
				if ($vv!='') {//hanya update kalau tidak kosong
					$vv=md5($vv);
				} else {
					$sfldInsert="";
					$skip=true;
				}					
			}  elseif (($jenisInput=='T')||($jenisInput=='TA')||($jenisInput=='TB')) {//password
				//$vv = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $vv);//menghilangkan p
				//echo "checking....<textarea>$vv</textarea><br>";
				if (isset($gDeniedTagInput[$i])) {
					$vv=removeTags2($gDeniedTagInput[$i],$vv);
				}
				
			} 
			
			if (!$skip) {
				if (($jenisInput!='F')) { 				 
					$sfldUpdate.=($sfldUpdate==''?"":",")."$nmField='".$vv."'";	//jika bukan file
					if ($sfldInsert!='') {
						$sfldInsert.=",";
						$sfldInsertu.=",";
					}
					$sfldInsertu.="$aField[$i]";	
					
					$sfldInsert.="'".validasiInput($vv)."'";
				}	
			}
		}
		$i++;
	}

	$sfldInsertu.=$addSave1;
	$sfldInsert.=$addSave2;
	$sfldUpdate.=$addSave3;
	//update created by
	if ($useLog) {
		$sfldUpdate.=",modified_by='$userid'";
		$sfldInsertu.=",created_by ";
		$sfldInsert.=",'$userid'";
	}
	if ($op=='tb'){
		$sq=$sqinsert="insert into $nmTabel($sfldInsertu) values ($sfldInsert)";
		$h=mysql_query($sqinsert);
		if ($h) {
			$idRecord=$id=mysql_insert_id();
			if ($saveLogSQL) { addActivityToLog2($nmTabel,$op,$id,"",$sq);}
			//echo $id;
			$_SESSION["newid_$det"]=$id;	
		}
		if ($isTest) echo "<br> $sq ";
	} elseif ($op=='ed') {
		if ($sfldUpdate!='') {
			$sq="update $nmTabel set $sfldUpdate where $nmTabel.$nmFieldID='$id' $sqSecureUpdateTabel";// and $sySecureShowTable
			$idRecord=$id;
			if ($updateTrans) {
				$h=mysql_query2($sq);
				if($h) {
					//if ($saveLogSQL) addActivityToLog2($nmTabel,$op,$id,$ket="",$sq);
					if (!isset($komentarEd)) {
						$komentarEd="Update data $nmCaptionTabel Berhasil...";
						echo "<div class=titlepage>$komentarEd</div>";	
						
					}
					else{ 
						echo $id;
					}
					  
				} else {
					echo "<br>Err: ". mysql_error()."<br> $sq <br> ".mysql_error();
				}
				if ($isTest)	echo "<br> $sq ";
			}
		}	
	}  
	
if ($jInputD>0) {
	if ($isTest) echo "menggunakan input-std-ed-detail..";
	if ($opcek!=1) include $um_path."input-std-ed-detail.php";
}
	//
	//exit;
?>