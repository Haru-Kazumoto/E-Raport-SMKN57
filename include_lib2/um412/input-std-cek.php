<?php
	$pes="";$i=0; 

	foreach ($aField as $nmf) {
	//	if (($aUpdateFieldInInput[$i]==0) || (!$aFieldEditable[$i])) { $i++; continue;}
		if ( $aUpdateFieldInInput[$i]==0)  { $i++; continue;}
		$nmfield=$nmf;
		
		$xCek=explode(",",$aCek[$i].",0,0,0,0");
		$jenisInput=$aJenisInput[$i];
		$minInput=$aMinInput[$i];
		$maxInput=$aMaxInput[$i];
		
		$capfld=$aFieldCaption[$i];
		//echo "<br>$nmfield :$minInput".$jenisInput;
		if (isset($_REQUEST[$nmf])) {
			$vv=$_REQUEST[$nmf];
			 
		} else {
			eval("$"."vv=$"."$nmf;");
		}
		 
		//echo "<br>acek $nmfield:$aCek[$i] -> jenis:$jenisInput,min:$minInput,max:$maxInput, vv:$vv, op:$op";
		//if (($jenisInput=='0')||($xCek[1]=='0')) {
		
		//echo "<br>vv:".$capfld."->".strlen($vv)." $xCek[1]";
		//jika field kunci atau field lain kosong
		
		/*
		if (( ($minInput>0) ||($nmfield==$nmFieldID)  ) && (strlen($vv)==0)){
			$pes.="*. ".$capfld." tidak boleh kosong";
		}
		*/
		//$pes.=$jenisInput;
		if (op("tb,ed,itb")) {
			if (strstr("|N0|N|C|C1|C2|C3|C4|CX|C91|C92|C93|C94|C95|C96|C97|C98","|$jenisInput|")!='')  {//ANGKA NUMERIK DAN CURRENCY
				$vv=unmaskrp($vv);
				if ($maxInput>1) {
					if (($vv*1<$minInput) || ($vv*1>$maxInput)) {
						$pes.="*. $capfld harus diisi antara $minInput sd $maxInput, $vv";
					}
				} else {
					if (($vv*1<$minInput) &&($minInput>0)) $pes.="*. $capfld harus diisi";
					
				}
			} else if ($jenisInput=='H1') {
				//string
				if ($vv=="")  {
					if ($minInput>0) {
						$pes.="*. $capfld harus diisi ";
					}
				}
			} else if (($jenisInput=='S') ||($jenisInput=='UID')) {//string
				if ($vv=="")  {
					if ($minInput>0) {
						$pes.="*. $capfld harus diisi ";
					}
				} else {
					if ($jenisInput=='UID') {
						if (!validasiUID($vv)) {
							$pes.="*. $capfld tidak boleh menggunakan karakter khusus";
						} else {							
							if ((strlen($vv)<$minInput)&&($minInput>0)) 
							$pes.="*. $capfld minimal $minInput huruf";
						}
					} else {
						if ((strlen($vv)<$minInput)&&($minInput>0)) 
						$pes.="*. $capfld minimal $minInput huruf";
					}
				}
			} else if ($jenisInput=='E') {//email
				if ($vv=="")  {
					if ($minInput>0) {
						$pes.="*. $capfld harus diisi ";
					}
				}
				elseif (!validasiEmail($vv)) $pes.="*. Format $capfld Salah";
			} else if ($jenisInput=='P') {//password
				$vv=$_REQUEST[$nmf];
				//$vv2=$_REQUEST[$nmf."x"];
				$vv2=$_REQUEST[$nmf];
				if (($vv2!='')||($op=='tb')) { 
					$vp=validasiPassword($vv,$vv2,$pmin=$minInput,$separator='*. ');
					$pes.=$vp;
				}
			} else if ($jenisInput=='D') {//password
					if ($vv=="")  {
						if ($minInput>0) {
							$pes.="*. $capfld harus diisi ";
						}
					}
			} else if ($jenisInput=='F') {//file
				if ($id*1==0) {
					if ($_REQUEST["x".$nmf]=='') {
						if ($minInput>0) $pes.="*. ".$capfld." harus diisi";
					} else {
						$batasukuran=$xCek[2];
						//$pes.="<br> batas $batasukuran";
						if ($batasukuran!=0) { //batas ukuran
							$aketfile=explode(",",$_REQUEST["x".$nmf]);
							$ukuran=$aketfile[0]*1/1024; //dalam kb
							//$pes.=" - ukuran $ukuran <br>";
							if ($ukuran>$batasukuran) {
								$tinfo="info".rand(12341,542411);
								$pes.="*. Ukuran ".str_replace("Upload ","",$capfld)." > $batasukuran kb.  
								<span id='$tinfo' style='display:none'></span>Info Resize: 
								<a href=# onclick=\"bukaAjaxD('$tinfo','index2.php?det=page&nohead=1&idpage=79&contentOnly=1&showlatest=2',
								'width:900'); return false\">klik di sini</a> ";
							}
						}
						//elseif (($nmf=="sertifikat_nf") && ($_REQUEST["x".$nmf]=='') &&($sabuk!='Putih')) {
						//	$pes.="*. ".$capfld." tidak boleh kosong";
						//}
					}	
				}
			}//jenisinput 
		
			if (($xCek[1]=='U') && (strlen($vv)>0)) {//cek unique selain field 
				if (($nmfield!=$nmFieldID)||($op=='tb')) {
					 $sqc="select $nmfield from $nmTabel where $nmfield='$vv' ";
					if ($op!='tb') $sqc.=" and $nmFieldID<>'$id'";
					//if ($id!='') $sqc.=" and $nmFieldID<>'$id'";
					//echo '--'.$sqc;
					$c=carifield($sqc);
					if ($c!=''){
						$pes.="*. ".$capfld." tidak tersedia atau sudah ada yang dipakai";
						//echo "<br>$op: $sqc ";
					}
				}
			
			}
			
		} //OP
		 
		
		$i++;
	}
	
	if ($addCek!='') $pes.=$addCek;
	$pesCek=str_replace("*. ","<br><span class='important icon-remove red'>&nbsp;</span>&nbsp;",$pes);
	$isValid=($pesCek==''?true:false); 
	
?>