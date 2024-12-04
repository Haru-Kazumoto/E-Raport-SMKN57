<?php 
$t="";
if (!isset($ntknitb)) $ntknitb="sss=1";//jika form dibuka menggunakan dialog, maka setelah sukses dialog ditutup
$rh=$r;
$thatas=$thbawah="";
//$thatas.=($addInput0==""?"":$addInput0); 
for ($i=0;$i<$jlhField;$i++) {
	$y=$i;
	$cap=$aFieldCaption[$i];
	$nmf= ($aField[$i]);
	if (substr($nmf,0,2)=="xx") $nmf=substr($nmf,2,100);
	$skip=false;
	if (strstr("|aksi|foto|password|","|".strtolower($cap)."|")!='') $skip=true;
	if ($aShowFieldInView[$i]=='0') $skip=true;
	
	$vvfile='$r[\''.$nmf.'\']';
		
	$sty='';
	if ($skip) {
		$sty="style='display:none'";
		continue;
	}
	elseif (($aShowFieldInTable[$i]!='1') and ($aShowFieldInTable[$i]!='0')) {
		$y=$i;
		$vv=$vvfile="";
		$separator=" ";
		if (strstr($aShowFieldInTable[$y],"/")!='') 
			$separator="/";
		elseif (strstr($aShowFieldInTable[$y],"<br>")!='') 
			$separator="<br>";
		
		$sNmf=$aShowFieldInTable[$y];
		$aNmf=explode($separator,$sNmf);
		foreach($aNmf as $nm) {
			$vv.=($vv==""?"":$separator).$r[$nm];
			$vvfile.=($vvfile==""?"":$separator).'$r["'.$nm.'"]';
		}
		
		
		//echo "<br>vv $vv ";
	} else { //1
		$nmField=$aField[$i];
		//$nmf= ($aField[$i]);
		
		$nmFieldInput=$nmField."_".$rnd;
			
		$special=$aFieldSpecial[$i];
		$xLebar=explode(",",$aLebarFieldInput[$i]);
		$xCek=explode(",",$aCek[$i].",0,0,0,0,0,0,0,0");
		$jenisInput=$aJenisInput[$i];
		if ($target!='file') {
			if ($jenisInput=='V') {
				continue;
			}
			elseif ($nmField=='menu') {
				continue;
			}
		}
		
		eval('@$vv=$'.$nmField.';');
	
		$sty="";
		if ($gFieldView[$i]=='') {
			if (substr($cap,0,1)=='-') 
				$cap=substr($cap,1,100);		
			elseif (($jenisInput=='D')||(strpos(" ".$nmf,"tgl")>0)||(strpos(" ".$nmf,"tanggal")>0))  {
				$vv=tglIndo($vv);
				$vvfile='tglIndo($r[\''.$nmf.'\'])';
			}
			elseif ($jenisInput=='C') {
				$vv=maskRp($vv);
				$vvfile='maskRp($r[\''.$nmf.'\'])';
				
			}elseif ($jenisInput=='F') {
					if (!isset($gPathUpload[$y])) {
						$gPathUpload[$y]=$pathUpload;
					} else {
						if ($gPathUpload[$y]=="") $gPathUpload[$y]=$pathUpload;
					}
					$gPathUpload[$y]=gff($gPathUpload[$y],$aField);
					if ($media!='') continue;
					$cap='';
					if (((strpos(" ".$nmf,"foto")>0) || (strpos($aCek[$y],"I")>0)) && ($r[$nmf]!='')) {
						$tgb=$idtd.'foto';
						$nmfile=$gPathUpload[$y].$r[$nmf]; 
						$nfThumb=createThumb($nmfile) ;
						$cap="<img src='$nfThumb' style='max-width:100px'>";
					} 
					$sf=createLinkFile($snmfile=$r[$nmf],$gPathUpload[$y],$sft=$xCek[2],$allowDelete=false,$cap,$cls='');
					$vv="<center>$sf</center>";		 
			
			}
		} else {
			if (substr($gFieldView[$i],0,1)=='=') {
				$vv=evalGFF($gFieldView[$i]); 
			} else {
				$vv=evalFieldView($i);
			}
		
		}
		
		if ($gGroupInput[$i]!='') {
			if ($target=='file') {
				$t.="\n<?=groupRowView($"."gGroupInput[$"."i],(isset($"."usetd)?1:''));?>";
			} else
				$t.=groupRowView($gGroupInput[$i],(isset($usetd)?1:""));	
		}
		
		$addstylecap="";
		if (strstr($special,"cap")!='') {
			$scap=strstr($special,"cap");
			$scl=strpos($scap,",")-2;
			$addstylecap="style='".substr($scap,3,$scl-1)."'";
		} //id='tritb[".$i."]' 
	}
	
	//if ($aShowFieldInView[$i]!=0) {
	if (!$skip) {
		if ($target=='file') {
			$thr="\n".".rowView('$cap',".$vvfile.(isset($usetd)?",1":"").")";
		} else
			$thr=rowView($cap,$vv,(isset($usetd)?1:""));
		if ($i<=$tPosDetail) {
			$thatas.=$thr;
		} else {
			$thbawah.=$thr;//"$"."t1=$thr;";			
		}
		
	}
	//$t.="\n</div>";
}//for



$t0=$t1="";

$ev='
$clspage="page";
$tbk="
<div class=\'tbviewatas noprint\' style=\'padding:5px 0 5px 0;text-align:right\' >
	<button class=\'btn btn-success btn-sm\' onclick=\"printDiv(\'$idview\')\">
	<i class=\'fa fa-print\'></i> Cetak</button>
</div>
";

$awTb1="<table border=\'0\' class=\'tbform2xx tbcetaktanpagaris overview-table\' width=100% >";';

if ($target=='file') {
	
	$t0.="<?php
	$"."rh=$"."r; //rheader
	$"."idview=\"tview_$"."rnd\";
	$ev
	$"."t0=\"
	$"."tbk
	<div class='tview2' id='$"."idview'>
	<link rel=\'stylesheet\' type=\'text/css\' href=\'$"."js_path/style-cetak.css\' >
	
	<div class='$"."clspage' >
		<h2 class=titleview>
		$"."nmCaptionTabel
		</h2>
		<hr>
		$"."awTb1
		\"$thatas.\"
		</table>
	
	\";
	
	echo $"."t0;
	?>
	";
	if ($thbawah!='') {
		$t1="
		<?php
		$"."r=$"."rh;//kembalikan rheader
		
		//rowView('cap|width|col-sm-3','isi|width|right|col-sm-8');
			$"."t1=$"."awTb1
			$thbawah;
			$"."t1=\"</table>\";
		echo $"."t1;
		?>
		";
	}
	
	
} else {
	eval($ev);
	
	$t0.=$tbk;
	$t0.="<div class='tview' id='$idview'>
	<link rel='stylesheet' type='text/css' href='$js_path"."style-cetak.css' >
		
	<div class=page>
	<h2 class=titleview>
	$nmCaptionTabel
	</h2>
	<hr>
	$awTb1
	$thatas 
	</table>
	";
	
	if ($thbawah!='') {
		$t1="
		$awTb1
		$thbawah 
		</table>
		";
	}
	
}

$h=$tDetail="";
if ($jInputD==1)
	include $um_path."input-std-view-detail.php";
else if ($jInputD==3) {
	include $um_path."input-std-view-detail-v3.php";
}


$t="$t0 
	$tDetail
	$t1
	</div>
</div>
";
		
if ($target=='file') {
	createGenFile($nfOutput,$isi=$t);
	exit;
} else 
	echo $t;
?>