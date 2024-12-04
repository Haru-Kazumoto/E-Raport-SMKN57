<?php
	$addKetInput="";
	$tMaster="";
	if (!isset($judulInput)) $judulInput= "Input Data $nmCaptionTabel";
	if (!isset($capTbSubmit)) $capTbSubmit="Simpan";
	if (!isset($capTbSubmit2)) $capTbSubmit2="Simpan & Tambah";
	if (!isset($addfae)) $addfae="";
	
	$csm1="col-sm-3 col-xs-4";
	$csm2="col-sm-9 col-xs-8";
	$aDefDLDT=[$csm1,$csm2];
	//echo $addf;
	//$tgl="";
	if ($id=='') {
		cekVar("$sField");
	}
	
	$sq="Select * from $nmTabel $nmTabelAlias ";
	
	if (($newop!='tb')&& ($id!='') && ($id!='0')) {
		$newop="ed";
		$sy=" where $nmTabelAlias.$nmFieldID='$id' ";
		$sq.=$sy;
		//echo "$sqTabel $sy";
		$r=extractRecord($sq);
		 
			@$r2=sqlToArray2($sqTabel.$sy)[0];
		 
	} else {	
		$newop="tb";
		$sq.=" where 1=2 ";
		extractRecord($sq);
		if (isset($evNew)) {
			//default dari ft_
			eval($evNew);
		}
		//$r2=sqlToArray2($sq)[0];
		//$r=extractRecord($sq);
		
	}	
	
	if ($target=="file") {
		$tMaster.= "
			$"."sq=\"Select * from $"."nmTabel $"."nmTabelAlias \";
		
			if ($"."id!='') {
				$"."sy=\" where $"."nmTabelAlias.$"."nmFieldID='$"."id' \";
				$"."r=extractRecord($"."sq,false);
				$"."r2=sqlToArray2($"."sqTabel.$"."sy)[0];
			} else {
				$"."sq.=\" where 1=2 \";
				$"."r=extractRecord($"."sq,false,false);
				
			}
		echo ''	
		";

	}

	if ($isTest) 	echo $sq;
	if (isset($row)) $r=$row;
	
	//jika form dibuka menggunakan dialog, maka setelah sukses dialog ditutup
	$funcE=($isTest?"":"selesaiEdit($rnd)");
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','$det','$funcE');return false;\" ";
	if (!isset($ntknitb)) $ntknitb="sss=1";
	$ntknitb.="&newop=$newop&id=$id";//operation aftercek
	
	
	if  (!isset($tPosDetail)) $tPosDetail=max(1,$jlhField-2); //posisi tdetail
	 //var_dump($gDefField);

	$arrInput=array();
	$avv=array();
	for ($i=0;$i<$jlhField;$i++) {
		$nmf=$nmField=$aField[$i];
		$nmFieldInput=$nmField."_".$rnd;
		$inp=$addrec=$addasteric="";;		
		//$nmf=strtolower($aField[$i]);
		$xCek=explode(",",$aCek[$i].",0,0,0,0,0,0,0,0");
		$jenisInput=$aJenisInput[$i];
		$minInput=$aMinInput[$i];
		
		$vv=$vIsi=$def=$stytr=$addstylecap="";//memberikan value
		
		@eval("@$"."vv=$".$nmf.";@$"."vvasli=$".$nmf.";");
		if (isset($gDefField[$nmf])) {
			$gDefField[$i]=$gDefField[$nmf];
		}
		if (!isset($gDefField[$i])) $gDefField[$i]="";
		if ($gDefField[$i]!='' && $newop=='tb' && $vv=='') {//
			
			if (substr($gDefField[$i],0,1)=='=') {
				
				$vv=evalGFF($gDefField[$i]);
			} else {
				

				eval("@$"."vv=$".$nmf."='$gDefField[$i]';");	
			}
			 
		} else {
			//echo "<br>newop $newop def $gDefField[$i] vv $vv";	
		}
		//echo "<br>$i>".$gDefField[$i]."> $vv > ";
				
		$addInputFld="";		
		//fungsi tambahan saat input berubah/onchange
		if ($gFuncFld[$i]!='') {
			$ff=evalGFF($gFuncFld[$i]);
			$funcFld="
			onkeyup=\"$ff\" 
			onchange=\"$ff\" 
			";
		} else $funcFld="";
		
		
		if (substr($gFuncFld[$i],0,7)=='suggest') {
			$addInputFld="
			<span class=suggestionsBox id='suggestions_$nmf"."_$rnd' style='display: none;'>
				<div class=suggestionsClose id='suggestionsClose_$nmf"."_$rnd' onclick=\"$('#suggestions_$nmf"."_$rnd').hide();\"
				style='position:absolute;margin:0px 0px;background:#f00;color:#fff;width:20px;text-align:center ;margin:5px'>[x]</div>
				
				<div class=suggestionList id='autoSuggestionsList_$nmf"."_$rnd'>
					&nbsp;
				</div>
			</span>";
		}
		
		//khusus field kunci tidak boleh diubah
		//echo "vv : $vv - $nmField ";
		if (($aShowFieldInInput[$i]==0) || ($aShowFieldInInput[$i]==2) ) 
			$stytr="display:none;";
		 else
			$stytr="";
		
		$cap=$aFieldCaption[$i];
		$cap2=$aFieldCaption2[$i];
		$special=$aFieldSpecial[$i];
		$xLebar=explode(",",$aLebarFieldInput[$i]);
		
		//jika cap diawali -, maka ada tambahan garis 
		if (substr($cap,0,1)=='-') {
			$cap=substr($cap,1,100);
		}			
		
		$vCap=($gFieldInputCap[$i]=='-'?'':"$cap~$cap2");
		
		if (($jenisInput=='F') && ($xCek[1]!='0')){ 
			$addrec="require";
		} elseif ($jenisInput!='0'){
			$addrec="require";
		} else {
			$addrec=$addasteric="";
		}	
		$addrec.=" $jenisInput";
	 

		if ($nmf=='menu') {
			$arrInput[]=array($vCap,$vIsi,$def,$stytr,$addstylecap);
			continue;
		}else if (!$aFieldEditable[$i]) { 
			$inp="$vv<input type=hidden class='' name=$nmField id=$nmFieldInput  value='$vv' >";
		} else if ($gFieldInput[$i]!='' ){ 
			if ($target=="file")
			$inp=$gFieldInput[$i];
			else
			$inp=evalGFF($gFieldInput[$i]);
		} elseif ($jenisInput=='A') {//nomor otomatis
			if ($id*1==0){
				$vv=cariNoOtomatis($nmField,$nmTabel,$aw=$xCek[1],$digit=$xCek[2]);
			}
			$inp="\n<input type=hidden class='' name=$nmField id=$nmFieldInput  value='#def#' >#def#";
			
		} elseif (($jenisInput=='T') ||($jenisInput=='TA') ||($jenisInput=='TA1')  ||($jenisInput=='TA2')||($jenisInput=='TB') ){//textarea
			$cl=min($xCek[2]*1,50);//cols 
			$rw=min($xCek[3]*1,2);//rows	 
			
			if ($jenisInput=='T')
				$inp="<textarea name=$nmField id=$nmFieldInput cols=$cl rows=$rw style='width:100%' $funcFld >#def#</textarea> ";		
			else {
				$inp="<br><textarea name=$nmField id=$nmFieldInput cols=$cl rows=$rw style='width:100%' $funcFld >#def#</textarea> ";		
				$gFieldInputCap[$i]="-"; 
				if (($jenisInput=='TB')||($jenisInput=='TA')) {
					$useConfig=2;
					//CKEDITOR.replace('$nmFieldInput');";
				} else {
					$useConfig=1;					
					/*
					CKEDITOR.replace('$nmFieldInput', { 
						customConfig:'$js_path"."ckeditor/config2.js?rnd=$rnd',
					});
					*/
				}
				$addf.="
					cke('$nmFieldInput','$useConfig');
				";
			}			
		} elseif ($jenisInput=='P') { //password
			// Retype <input type=password name=$nmField"."x id=$nmFieldInput"."x  class='$addrec' >	
			$def=($newop=='tb'?" value='#def#' ":'');
			$inp=" <input type=password name=$nmField id=$nmFieldInput  
			class='$addrec' $funcFld  $def autocomplete='off' >
			";
			$addKetInput=($newop=='ed'?"<br>*) Biarkan password kosong jika tidak ingin diubah":"");
		} elseif (($jenisInput=='CB')||($jenisInput=='CBA')) { //combo //comboautosearch
			$scb=explode("#",$aLebarFieldInput[$i]."#####");
			if ($scb[1]=='') $scb[1]=$nmf;
			$inp=um412_isiCombo5($scb[0],$scb[1]);
		} elseif ($jenisInput=='F') {
			if (!isset($gPathUpload[$i])) {
				$gPathUpload[$i]=$pathUpload;
			} else {
				if ($gPathUpload[$i]=="") $gPathUpload[$y]=$pathUpload;
			}
			
			$gPathUpload[$i]=gff($gPathUpload[$i],$aField);
			$inpf=$inptp=$funcp="";
			$funcp="onblur=\"cekUpload('$nmFieldInput','$inptp');\"  ";
			if ($xCek[1]=='I') {//1 atau 2?
				$inpf.=" data-type='image' ";
				$inptp='image';
				$funcp.=" onchange=\"previewImage('p$nmFieldInput','$nmFieldInput'); \" ";
			}
			if ($xCek[3]!='0') $inpf.=" data-max-size='$xCek[3]' ";
			$inp="<input type=file name=$nmField id=$nmFieldInput  $funcp
			$inpf class='$addrec' >
			
			<input type=hidden name=x"."$nmField id=x"."$nmFieldInput >
			<span name=p"."$nmField id=p"."$nmFieldInput style='display:none'></span>
			";
			if ($newop=='ed') {
				$rnd2=rand(128831,138381);
				$ct=createLinkFile($r[$nmf],$gPathUpload[$i],"$xCek[2],43","replace,delete");
				$aif="<a href='#' onclick=\"t".$rnd2."$nmFieldInput.style.display='inline';
				l".$rnd2."$nmFieldInput.style.display='none';return false;\" id=l".$rnd2."$nmFieldInput >Tambah/Ubah?</a>
				<span id=t".$rnd2."$nmFieldInput style='display:none'>$inp</span>";
				$inp="<div style='float:none'>$aif $ct</div>";
				if ($r[$nmf]!='') $stytr.="height:55px; overflow:auto; ";
			}
		} 
		elseif ($jenisInput=='DT')  {
			if ($gDefField[$i]!='' && $newop=='tb' ) { 
				@eval("@$"."vv=$".$nmf."='$gDefField[$i]';");	
			} else {
				$formatTgl2=str_replace("M","m",$formatTgl);
				$formatTgl2=str_replace(" ","/",$formatTgl2);
				$vv=SQLtotgl($vv,$formatTgl2." H:i:s");
			}
			$inp="<input type=text name=$nmField id='$nmFieldInput' value='#def#' size='18' class='$addrec' $funcFld > dd/mm/yyyy hh:ii:ss";
			 
		
		}  elseif ((strpos(" ".$nmf,"tgl")>0)||(strpos(" ".$nmf,"tanggal")>0)||($jenisInput=='D'))  {
			
			if ($gDefField[$i]!='' && $newop=='tb' ) {
				@eval("@$"."vv=$".$nmf."='$gDefField[$i]';");	
			} else {
				$formatTgl2=str_replace("M","m",$formatTgl);
				$formatTgl2=str_replace(" ","/",$formatTgl2);
				$vv=SQLtotgl($vv,$formatTgl2);
			}
			$inp="<input type=text name=$nmField id='$nmFieldInput' value='#def#' size='10' class='$addrec'' $funcFld > dd/mm/yyyy ";
			//$addf.="$('#$nmFieldInput').datepicker();";
		} 
		else {	
			
			if (($jenisInput=='H1') &&($isEdit)) {
		 
			} else {
				//$vv=$r[$nmf];
				//if (($vv=='') && ($isTest)) $vv="t"."$nmField";
				$inp="<input type=text name=$nmField id=$nmFieldInput value='#def#' size='$aLebarFieldInput[$i]'  class='$addrec' $funcFld > ";		
			}
		}
		
		if ((($jenisInput=='H1') &&($isEdit)) || (!$aFieldEditable[$i])) {
			$ff="#def#";//.$aShowFieldInTable[$i];
			if (isset($r2)) {
				if ($aShowFieldInTable[$i]!=1) $ff="".$r2[$aShowFieldInTable[$i]];
			}
			$inp="\n<input type=hidden name=$nmField id=$nmFieldInput  value='#def#' >$ff ";//$ff
		
		}
		elseif (($jenisInput=='H2')) {
			$ff=" >".$aShowFieldInTable[$i];
			if ($aShowFieldInTable[$i]!=1) $ff="#def# - ".$gStrView[$i];
			$inp="\n<input type=hidden name=$nmField id=$nmFieldInput  value='#def#' >$ff ";//$ff
		}//autosearch
		if (($jenisInput=='CA')||($jenisInput=='CB')) {  //comboautosearch
			$addf.="$('#$nmFieldInput').combobox();";
		}
		if (($jenisInput=='L')||($jenisInput=='Y')) {  //comboautosearch
			$inp="\n<input type=text name=$nmField id=$nmFieldInput  value='#def#' size=70>
				<span id=tl$nmFieldInput $stdn></span>
				&nbsp;&nbsp;<a href='#def#' onclick=\"window.open(encodeURI($('#$nmFieldInput').val()));return false;\" target='_blank' ><i class='fa fa-link'></i></a>
				&nbsp;&nbsp;<a href='#' onclick=\"previewYT('tl$nmFieldInput',$('#$nmFieldInput').val());return false;\" title='Preview'><i class='fa fa-desktop'></i></a>
				";
		}
		
		if ($aShowFieldInInput[$i]==3) {
			$xdef="#def#";
			if ($gFieldView3[$i]!='') {
				$xdef=evalGFF("=".$gFieldView3[$i]);
			}
			$inp="\n<input type=hidden name=$nmField id=$nmFieldInput  value='#def#' ><span id='x$nmFieldInput'>$xdef</span>";	
		}
		
		if (isset($gAddField[$i])) {
			$inpold=$inp;
			$addInputFld.=" ".evalGFF($gAddField[$i]);
			$inp=$inpold;
		}
		$addstylecap="";
		if (strstr($special,"cap")!='') {
			$scap=strstr($special,"cap");
			$scl=strpos($scap,",")-2;
			$addstylecap="style='".substr($scap,3,$scl-1)."'";
		} //id='tritb[".$i."]' 
		if($aFieldEditable[$i] || $levelOwner>=6||$id=='') {
			$vIsi="$inp $addasteric $addInputFld";
		} else {	
			$vIsi="$vv $addInputFld";
			$ntknitb.="&$nmField=".urlencode($vv);
		}
		
		
		if ($jenisInput=='C') {
			$vv=maskRp($vv);
		}	 
	 	elseif ($jenisInput=='C1') {
			$vv=maskRp($vv,0,500);
		}
	 	elseif ($jenisInput=='C2') {
			$vv=maskRp($vv,0,1000);
		}
	 	elseif ($jenisInput=='C3') {
			$vv=maskRp($vv,0,1500);
		}
	 	elseif ($jenisInput=='C4') {
			$vv=maskRp($vv,0,2000);
		}
	 	elseif ($jenisInput=='N') {
			$vv=maskRp($vv,0,0);
		}		
		$arrInput[]=array($vCap,$vIsi,$vv,$stytr,$addstylecap);
		//echo "<br> array( vCap : $vCap,vIsi : $vIsi,vv : $vv,stytr : $stytr,addstycap : $addstylecap); ";
		$avv[$nmf]=$vv;
	}
	
	$t="";
	$awal='
	
	<div id=ts"."$idForm ></div>
	<div id=tinputdialog2_$rnd></div>
	<form id=\'$idForm\' action=\'$nfAction&op=$newop\' method=\'Post\' $asf style=\'padding:0px;margin:0 0 5px 0;\' >';
	
	//border:2px solid #000;
	
	if ($target=='file') {
		$t.="<?php\n 
		
			extractRecord(\"select * from $nmTabel where $nmFieldID='$"."id'\");	

			$"."t='';
			$"."newop=($"."id==''?'tb':'ed');
			$"."addKetInput='';
			$"."asf=\"".str_replace("$rnd",'$rnd',str_replace('"','\\"',$asf))."\";
			
			$"."t.=\"$awal\";

			echo $"."t;
			
			?>
			";
			
	} else {
		eval("$"."t.=\"$awal\";");
	}
	
	
	if ($infoInput1!='') $tMaster.='$infoInput1<br>';
	if ($addInput0!='') $tMaster.=$addInput0; 
	
	
	for ($i=0;$i<$jlhField;$i++) {
		$nmf=$nmField=$aField[$i];
		$ai=$arrInput[$i];
		$vCap=$ai[0];
		$vIsi=$ai[1];
		$def=$arrInput[$i][2]; 
		$stytr=$ai[3];
		$addstylecap=$ai[4]; 
		$jenisInput=$aJenisInput[$i];
		
		if (substr($cap,0,1)=='-') {
			if ($target!='file'){
				$tMaster.="<tr class=troddform2><td colspan=2><hr style='width:100%;max-width:770px'></td></tr>";
			} else {
				$tMaster.="$"."tMaster.=\"<tr class=troddform2><td colspan=2><hr style='width:100%;max-width:770px'></td></tr>\";";
				
			}
		}
			
		if ($gGroupInput[$i]!='') {
			if ($target!='file'){
				$tMaster.="<div id=gri_$i"."_$rnd class='groupinput' >$gGroupInput[$i]</div>";
			} else {
				$tMaster.="$"."tMaster.=\"<div id=gri_$i"."_$rnd class='groupinput' >$gGroupInput[$i]</div>\";";				
			}
//			echo "ada $gGroupInput[$i]";
		}			
		if ($inp!='-') {
			if ($target!='file'){
				//eval("$"."def=$"."$nmField;");
				//echo "<br>$nmf=$def";
				$tMaster.=rowITB($nmField,$vCap,$vIsi,$def,$stytr,$addstylecap );
			} else {
				$vi=$vIsi;
				if ($gFieldInput[$i]!='' ){ 
					$vi="evalGFF(\"$vi\")";
				}
				else {
					$vi=str_replace('"','\"',$vi);
					$vi="\"$vi\" ";
				}
				
				$vi=str_replace('#def#',"$"."$nmf",$vi);
				$tMaster.=str_replace("$rnd",'$rnd',"\n.rowITB(\"$nmField\",\"$vCap\",$vi,\"$"."$nmField\",\"$stytr\",\"$addstylecap\")");
			}
		}
		if ($i==$tPosDetail) $tMaster.="#tdetail#";
	}
	
	if ($addInput!='') {
		evalGFF($addInput);
		if ($target!='file'){
			$tMaster.="<br>$inp<br><br>";
		} else {
			$tMaster.=".\"<br>$inp<br><br>\"";
			
		}
	}
	
	//memposisikan tdetail
	$rMaster=$r;
	$tDetail="";
	if ($jInputD==1)
		include $um_path."input-std-itb-detail-v1.php";
	elseif ($jInputD==3) {
		include $um_path."input-std-itb-detail-v3.php";
		
	}
	if (isset($addfbe)) $addf.=$addfbe;//diletakkan di akhir
	
	if ($target=="file") {
		$tMaster=str_replace("#tdetail#","?>\n\n$tDetail<?php \n echo''",$tMaster);
		$tMaster="
		<?php 
		$tMaster 
		?>";
	} else {
		$tMaster=str_replace("#tdetail#",$tDetail,$tMaster);
	
	}
	
	$t.="
		<span class='pull-right-container'>
		  <i class='fa fa-angle-down pull-right' id='tgm$det' 
		  onclick=\"
			v=$( '#tm$det' ).css('display');
			if (v=='none') {
				$( '#tm$det' ).show();
				$('#tgm$det').css('transform','rotate(0deg)');
				
			} else {
				$( '#tm$det' ).hide();
				$('#tgm$det').css('transform','rotate(-90deg)');
				
			}
			
		  \"></i>
		</span>
		<div id='tm$det' v='1' class='tmscroll' >
			<div style='clear:both; '>
				$tMaster
			</div>
			<div style='clear:both; '>
			$addKetInput
			</div>
		</div>
	";
	
	if (isset($komentarAddForm2)) $t.=$komentarAddForm2;
	
	//<p style='clear:both;margin-top:5px;text-align:right;margin-right:5px'>
	
	$tbSubmit="
	<input type=submit value='$capTbSubmit' id=tbsimpan_$rnd 
	class='button btn btn-primary btn-sm'
	onclick=\"
	v=$('#tfae$rnd').html();
	w=$('#tfae2$rnd').val();
	$('#tfae$rnd').html(v+w);
	\"
	>";
	
	$curUrl="index.php?det=$det&op=$op&parentrnd=$parentrnd";//.$strget
	/*
	$addTbSimpan=array(
		array('simpan dan cetak','op=view&aid=#id#')
		);
	*/
	if (isset($addTbSimpan)) {
		$x=3;
		foreach( $addTbSimpan as $atb) {
			$tfaex="tfae4".$rnd.$x;
			$cap=$atb[0];
			$addAct=$atb[1];
			$addAct=str_replace("#id#",$id,$addAct);
			$stytb=(isset($atb[2])?$atb[2]:'submit');
			if ($stytb=='submit') {
				$tbSubmit.="
				<textarea id=\"$tfaex\" style='display:none'>
					bukaAjaxD('$idtd','$curUrl&newrnd=$rnd2&$addAct','$configFrmInput','awalEdit($rnd2)');
				</textarea>
				<input type=submit name=submit id=tbsimpan_$rnd value='$cap' 
				class='btn btn-primary btn-sm' onclick=\" 
				y=$('#$tfaex').val();
				$('#tfae$rnd').html(y);
				\"
				>";
			} else {
				$tbSubmit.="
				<textarea id=\"$tfaex\" style='display:none'>
					bukaAjaxD('$idtd','$curUrl&newrnd=$rnd2&$addAct','$configFrmInput','awalEdit($rnd2)');
				</textarea>
				<a href=# class='btn btn-primary btn-sm' onclick=\" 
					bukaAjaxD('$idtd','$curUrl&newrnd=$rnd2&$addAct','$configFrmInput','awalEdit($rnd2)');
				\"
				>$cap</a>";
			}
			$x++;
		}
	}
	
	if ($newop=='tb') {
		if (!isset($showTbSimpanDanTambah)) $showTbSimpanDanTambah=true;
		if ($showTbSimpanDanTambah) {
			$tbSubmit.="
			<input type=submit name=submit id=tbsimpantambah_$rnd value='$capTbSubmit2' class='btn btn-primary btn-sm' 
			onclick=\" 
			y=$('#tfae3$rnd').val();
			$('#tfae$rnd').html(y);
			\"
			>";
		}
	}
	
	//$tbSubmit.="</p>";
	if (!isset($addInputAkhir)) $addInputAkhir="";
	if ($target=='file') {

		$t.="<?php
			$"."ntknitb=makeToken('$ntknitb');	

		echo \"
				<br>
		<input type=hidden name=op id=opx_$"."rnd value='$"."newop'> 
		<input type=hidden name='rndinput' id='rndinput' value='$"."rnd' >
		<input type=hidden value='$"."ntknitb' id=tkn_"."$"."rnd name=tkn>\";
		
		?>";
	} else {		
		eval("$"."ntknitb=\"$ntknitb\";");
		$ntkn=makeToken($ntknitb);	
		$t.="
		<br>
		<input type=hidden name=op id=opx_$rnd value='$newop'> 
		<input type=hidden name='rndinput' id='rndinput' value='$rnd' >
		<input type=hidden value='$ntkn' id=tkn_"."$rnd name=tkn>";
	}
	$letakTbSubmit=1;
	if (!isset($letakTbSubmit)) {
	$t.="
		<div  id='trsm$rnd'  >
		$addInputAkhir
		$tbSubmit 
		</div>
	";
	} else {
	
	$t.="<div id='trsm$rnd' class='dl form-group'>
			<div class='$csm1' id='dtsubmit_$rnd'>&nbsp;</div>
			<div class='$csm2' id='ddsubmit_$rnd'>
			$addInputAkhir
			<div style='text-align:right'>$tbSubmit</div>
			</div>
		
		</div>"	;
			
	} 
	
	$t.="</p>";
	$t.="</form>
	";
	
	
	if (!isset($refreshAfterInput)) $refreshAfterInput=($defOp=="showtable"?true:false);
	
	if (!$refreshAfterInput) {
		$funcAfterEdit="";
		$funcAfterEdit2="";
	} else {
		if ($op2=='') {
			$funcAfterEdit2="$('#tbumum$parentrnd').DataTable().columns.adjust().draw();";
		
		} else
			$funcAfterEdit2="dataTable".$currnd.".ajax.reload(true,true);";// null, false ); // user paging is not reset on reload
	}
	
	$funcAfterEdit.=$addfae;
	
	$funcAfterEdit.="$('#$idtd').dialog('close');";	
	//currnd=$rnd
	//jika setelah nambah tidak ingin refresh
	
	//echo "refreshafteri:$refreshAfterInput > ";
	if (!isset($idtd)) $idtd="maincontent";
	$rnd2=rand(123,31211);
	$t.="
	<div style='display:none' >
		<textarea  name='tfuncAfterEdit2' id='tfae2"."$rnd'>
		 $funcAfterEdit2</textarea> 
		<textarea name='tfuncAfterEdit3' id='tfae3"."$rnd' >
		 refreshDT($parentrnd);
		 bukaAjaxD('$idtd','$curUrl&newrnd=$rnd2','$configFrmInput','awalEdit($rnd2)');</textarea>
		<div id='tfae"."$rnd' name='tfuncAfterEdit' >
	 $funcAfterEdit
		</div>
		<div id=tfbe"."$rnd name='function before edit'>
			$(function(){
				$addf
			})
		</div>
		<div id=tcari3_$rnd style='display:none'></div>

	</div>
	";
	
	$tbt='';
	if (isset($addTbAtas)) {
		
		$rndx=$rnd.'74';
		$tempat="tdiaA-$rnd";
		$tbt.="
		<span id=$tempat style='display:none'></span>
		<style>
		.btn-dialog-atas {
			padding:2px;
		}	
		</style>
			
		";
	
		foreach( $addTbAtas as $atb) {
			//$tfaex="tfae4".$rnd.$x;
			$cap=$atb[0];
			 
			$title=$atb[2];
			$clsbta=$atb[3];
			$cnffi=str_replace("Input Data",$title,$configFrmInput);
			$addAct=str_replace("#id#",$id,$addAct);
			$url=str_replace('#url#',$curUrl,$atb[1]);
			$tbt.=" <a href=# title='$title' class='btn btn-$clsbta btn-sm btn-dialog-atas' onclick=\" 
				callTbDialogAtas($rnd,'$url&newrnd=$rndx','$cnffi','awalEdit($rndx)');return false;
			\"
			>$cap</a>
			
			";
		$x++;
		}
		$tbt="<div class='pull-right'>$tbt</div>";
	}
	
	$hasil="";
	if (function_exists('tpTitlePage')) {
		$hasil.=tpTitlePage($nmCaptionTabel.$tbt ,$nobread=true);
	} else {
		$hasil.="<h2>Input $nmCaptionTabel $tbt</h2>";
	}
	
	//menambah text note
	if (isset($footNoteInp)) $t.=$footNoteInp;
	
	if (function_exists('tpBoxInput')) {
		$hasil.=tpBoxInput($t,'nobread');
	} else {
		$hasil.=" 
			<table border='0' width='100%' class='table'>
			<tr><td valign=top >$t</td></tr>
			</table>
		";
	}
	
	//evaluasi ##
	for ($i=0;$i<$jlhField;$i++) {
		$nmf=$nmField=$aField[$i];
		if (strstr($hasil,"#$nmf#")) {
			
			$hasil=str_replace("#$nmf#",$avv[$nmf],$hasil);
		}
	}
	
?>