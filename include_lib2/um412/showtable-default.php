<?php

//echo "heh";
cekVar("addtbopr,addtbview,id,cari,op3"); 
//$rnd=rand(123,983792398);
$currnd=$rnd;

	//hq sudah dijalankan sebelumnya
	/*
	$hq=@mysql_query($sq);
	if (!$hq) die($sq); 233
	
	//$nr=mysql_num_rows($hq);
	
	*/
	//if ($op3!='json') 
		//echo "<br>showtable-default-ooo> $sq";exit;				
	
	$br=0;
	$idtd=$identitasRec;
	$hal.="&tdialog=$idtd";
	
	$awalT="<div id=tout$rnd class='countainer' >
			<table style='".(isset($wTabel)?"$wTabel":'')."' cellspacing='0' cellpadding='5' border='1' 
			class='tbumum   ".($useDataTable?'table table-striped table-bordered ':'')."'
			id='tbumum"."$rnd' >
			";
	$akhirT="</table>
	</div>";
	$skipQueryTable=false;//tanpa menjalankan while
	if (($op3!='json') && ($useDataTable) && ($op=='showtable')) {
				
			$skipQueryTable=true;
			$t.=$awalT;
			$t.=$jdlTabel;
			$t.=$footTabel;	
			$t.=$akhirT;
			$nr=0;
	} 
	
	if (!$skipQueryTable) {
		if ($nr<=0) {
			if ($op3=='json') {
				//echo "<br>".$sqlall;
			//$t.="<br>".$sqlall;
				$totalData= $nrall;
				$totalFiltered=$nrall;
				$dataJSON=array();
				$requestData= $_REQUEST;
				//$requestData['draw']=1;
				$json_data = array(
				"haltkn"            => "$thaltkn",   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => 0, // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $dataJSON  // total data array
				);
				echo json_encode($json_data);
				exit;
			}
			else 
			$t.="tidak ada data";
		
		} else  {
			//echo "1:$op 3:$op3";
	//		$t.='tidak ada data...' ; 
			//$t.=$lh;
			if (isset($wTabel))
				if ($media!='') 	$wTabel="100%";
			else $wTabel='';
			$isiTabel="";
			$tidtd='';
			while ($r=mysql_fetch_array($hq)) {
			//	$id=$r[strtolower($nmFieldID)];
				$id=$r[$nmFieldID];
				$br++;
				$idt=$identitasRec.$br;
				$rnd2=$rndRec=rand(123,983792398);
				//$idtd=$identitasRec.$br."d";
				$idtd=$idt.$rnd2;
				$lines=array();
				$troe=($br%2==0?"troddform2":"trevenform2");
				
				$isiTabel.="<tr id="."$idt class='$troe' >";
				$isIdtdPaced=false;
				if ($media!='xls')	$tidtd="<span id=$idtd style='display:none'></span>";//meletakkan idtd
			
				if ($showNoInTable) {
					$isiTabel.="<td align='center'>".($lim+$br)."</td>";
					$lines[]=''.($lim+$br).'';
				}
				$tbopr=$tbView='';
				
				if ($showOpr==1) {
					$addtbo=$addtbopr;
					if ($addtbo!='') {
						$addtbo=str_replace("idtd",$idtd,$addtbo);
						$addtbo=str_replace("-{id}-",$id,$addtbo);
					}
					$tbopr.=$addtbo;
					
					$ntkn=makeToken("op=itb&id=$id&newrnd=$rnd2&currnd=$rnd&idtd=$idtd");		
					$linkEdit="bukaAjaxD('$idtd','$hal&tkn=$ntkn','$configFrmInput','awalEdit($rnd2)'),'';";
					$ntkn=makeToken("op=view&id=$id&newrnd=$rnd2&currnd=$rnd&idtd=$idtd");
					$linkView="bukaAjaxD('$idtd','$hal&tkn=$ntkn','$configFrmInput'),'';";
					$ntkn=makeToken("op=del&id=$id&newrnd=$rnd2&currnd=$rnd&idtd=$idtd&cari=cari");
					$url="$('#thal_$rnd').html()+'&cari=cari'";
					$funcAfterDel="bukaAjax('tcari_"."$rnd',removeAmp($url));";
					$linkDel="if (confirm('yakin akan menghapus?')) {bukaAjax('$idt','$hal&idt=$idt&tkn=$ntkn' );} ; ";

					if ($showTbUbah) {
						$tbopr.="&nbsp;<a href=# onclick=\"$linkEdit return false;\">
						<img src='$js_path"."img/icon/edit1.png' title='Edit Data'></a>";
					}
					
					if ($showTbView) {
						$tbopr.="&nbsp;<a href=# onclick=\"$linkView return false;\">
						<img src='$js_path"."img/icon/folder1.png' title='Lihat Data'></a>";
					}
					
					if ($showTbHapus) {
						$tbopr.="&nbsp;<a href=# onclick=\"$linkDel return false\">
						<img src='$js_path"."img/icon/del1.png' title='Hapus Data'></a>";						
					}
					
					if ($tbOprPos==2) {
						$isiTabel.="<td align=center  >$tbopr</td>";
					} else {
						$tbopr="$id";
						//$tbopr="<center><input id=chb"."$det"."$br name=chb"."$det type=checkbox  value='$id' > </center>";
						$isiTabel.="<td align=center style='text-align:center' >$tbopr</td>";
					}
					if ($op3=='json') $lines[]=$tbopr;
				}
				 
				
				for($y=0;$y<$jlhField;$y++) {
					$xCek=explode(",",$aCek[$y].",0,0,0,0,0,0,0,0");
					//$nmf=strtolower($aField[$y]);
					$nmf=$aField[$y];
					$nmFieldInput=$nmf."_$rnd";
					$vv=$addvv="";
					$xCek=explode(",",$aCek[$y].",0,0,0,0");
					$jenisInput=$aJenisInput[$y];
					$minInput=$aMinInput[$y];
					
					if (!isset($gPathUpload[$y])) {
						$gPathUpload[$y]=$pathUpload;
					} else {
						if ($gPathUpload[$y]=="") $gPathUpload[$y]=$pathUpload;
					}
					$vPathUpload=gff($gPathUpload[$y],$aField);
					
					if ($aShowFieldInTable[$y]=="0") {
						continue;
					} else if ($jenisInput=='V') { //view detail
						$addvv="";
						if ($gAddDetail[$y]!="") {
							$vv=evalGFF($gAddDetail[$y]);
						}
						
						$anmf=explode(",",$nmf);
						$nmfldK=$anmf[3];
						$nrnd=rand(123,3331);
						
						//pecah kunci filter
						$ft="";
						$anft=explode(" ",$anmf[2]);
						$akft=explode(" ",$nmfldK);
						$it=0;
						foreach ($anft as $nft) {
							$k=$akft[$it];
							$fldK=$r[$k];//&$anmf[2]=$fldK
							$ft.="&ft_"."$nft=$fldK";
							$it++;
						}
						
						
						$nfunc="awalEdit($nrnd)";
						$url="index.php?det=$anmf[1]"."$ft&newrnd=$nrnd&isDetail=1";
						$addvv.=" <a href=# class='btn btn-primary btn-mini btn-xs' onclick=\"bukaJendela('$url','$nfunc');return false;\">Detail</a> ";				 
					} else if ($gFieldView[$y]=='') {
						if ($aShowFieldInTable[$y]!="1") {
							$vv=evalFieldView($y);
						} else  { 
							$isi=$r[$nmf];
							
							$xvv=changeFormat3($isi,$jenisInput,true);
							if ($xvv!='blank') {
								$vv=$xvv;
							} else {
								if (($jenisInput=='T'  )||($jenisInput=='TA'  )){ //textrea
									$vv=potong($r[$nmf],50);					
								} else {
							
								if ($gFieldView[$y]!="") {
									$vv=evalGFF($gFieldView[$y]);
								}
								
								if ((strpos(" ".$nmf,"foto")>0) && ($isi!='')) {
									$tgb=$idtd.'foto';
									$encgbr=encod($pathUpload2.$isi);
									$isi="
									<img src=".$toroot.$pathUpload2.$isi." style='max-width:100px'>";
								}
								$vv=$isi;
								}
							}
						}
						
						
					} else  { //menggunakan gfieldview
						if (substr($nmf,0,4)=='menu') {
							//$isi="='<a href=\'content1.php?det=$det&op=viewmenu&id={id}\' target=_blank >Menu</a>';";
							$acap=explode(">",$aFieldCaption[$y].">menu");
							$isi="='<a href=# onclick=\"bukaAjax(\'content\',\'content1.php?det=$det&op=viewmenu&id={id}\');\"   >$acap[1]</a>';";
							$vv=evalGFF($isi);
						
						//} else if (strstr($gFieldView[$y],"createLink")!="") {
						//	eval("$"."vv=".$gFieldView[$y].";"); 
							//$vv=$gFieldView[$y];
							
						} else if (substr($gFieldView[$y],0,1)=='=') {
							$vv=evalGFF($gFieldView[$y]); 
						} else {
							$vv=evalFieldView($y);
						}
						
					}//akhir setiap field
					
					$vv.=$addvv;
					
					if ($gFieldLink[$y]!='') {
						//det,$fldkey,$fldkeyval,
						$xl=explode(",",$gFieldLink[$y].",showtable,_blank,width:wMax-20");
						$xdet=$xl[0];
						$xfldkey=$xl[1];
						$xfldval=$r[$xl[2]];					
						$jtarget=$xl[4];	
						$jtg="";
						$url="index.php?det=$xdet&$xfldkey=$xfldval&op=$xl[3]";
						if ($jtarget=='_blank') {
							$jtg="target=_blank";
						} elseif ($jtarget=='ajaxd') {
							$configAjaxD=str_replace("&",",",$xl[5]);	
							$jtg="onclick=\"bukaAjaxD('tinput_$rnd','$url&contentOnly=1','$configAjaxD');return false;\" ";
						}
						$vv="<a $jtg href='$url'>$vv</a>";
						
					}
					
					if ($aShowFieldInTable[$y]!="0") {
						
						$isiTabel.="<td align='$aRataFieldTabel[$y]' > $vv $tidtd &nbsp;</td>";
						
						$lines[]="".$vv." ".$tidtd;
						//$lines[]=removetag($vv." ".$tidtd);
						if (!$isIdtdPaced) {
							$isIdtdPaced=true;
							$tidtd='';
						}
						
					}
					
					
				
					
				}
				$arrTable[]=$lines;
				$isiTabel.="</tr>";
				
			} //akhir while
		
			if ($op3=='json') {
				$xdraw=isset($requestData['draw'])?$requestData['draw']:0;
				$totalData= $nrall;
				$totalFiltered=$nrall;
				$dataJSON=$arrTable;
				$requestData= $_REQUEST;
				//$requestData['draw']=1;
				$json_data = array(
				"haltkn"            => "$thaltkn",  
				"sql"			  => ($isTest?$sqljson:"-"),
				"draw"            => intval( $xdraw ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
				"recordsTotal"    => intval( $totalData ),  // total number of records
				"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
				"data"            => $dataJSON,  // total data array
				
				);

				echo json_encode($json_data);
				exit;
				//include $um_path."showtable-default-json.php";
			} else {
				$t.=$awalT;
				$t.=$jdlTabel;
				if (!$useDataTable) $t.=$isiTabel;
				$t.=$footTabel;	
				$t.=$akhirT; 
			}
		} //jika nr>0
	}  //skip query table
	

?>