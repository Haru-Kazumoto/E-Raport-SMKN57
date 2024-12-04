<?php
$sqd=str_replace("#id#",$id,$sqTabelD);
if (!isset($classtb)) {
	//koversi dari input-std-itb-detail1
	$classtb="c$det";
	$capTbD=$nmCaptionTabel;
	$idtb="$classtb-$rnd";
	if (!isset($sLebarFldD)) $sSzFldD=$sLebarFldD;
}
if (!isset($opcari)) $opcari="cari2";


/*contoh
	$classtb="tbresep";
	$capTbD="Resep";
	$idtb="$classtb-$rnd";
	$sFldDCap="No,Kode,Nama Obat,Harga,Jumlah,Satuan,Aturan Pakai,&nbsp;";
	$sFldD="no,kdobat,nmobat,hrgjual,jlh,satuan,aturanpakai,&nbsp;";
	$sSzFldD="4,6,40,10,7,12,12,50";//ukuran input
	$sLebarFldD="50,100,300,100,100,100,200,50";
	$sClassFldD=",rp,,,,,,,,,,,,,,,,,";
	$sJenisFldD=",,,,i,,i,,,,,,,,,,,,";
	$sFuncFldD=",,,hitung(),,,,,,,,,,";
	$funcEvalD="hitung()"
	$detCari="obat";
	//$fldCari='kdobat';
	$fldCari="kdobat,nmobat,hrgjual,satuan";
	
	$sqd="select pdd.id,pdd.kode as kdobat,ob.nmobat,pdd.hrg as hrgjual,pdd.jlh,ob.satuan,pdd.aturanpakai
	from tbpendaftaran pd inner join tbpendaftarand pdd on pd.id=pdd.idpendaftaran
	inner join tbobat ob on pdd.kode=ob.kdobat and pdd.grp='obat' and pd.id='$idpd'";
	$adt=sqlToArray($sqd,0);
	include $um_path."input-std-itb-detail2.php";
		
*/

	if (!isset($capTbD)) $capTbD='';
	if (!isset($jTampilanD)) $jTampilanD='form';
	if ($jTampilanD!="form") {
		$ro="readonly";
	} else $ro="";
	
	$headTb="<div border='1' cellpadding='1' cellspacing='1' class='tb-$classtb' align=center >";
	
	//tambah kolom no dan aksi
	$aFldDCap=explode(",","No,".$sFldDCap.",Aksi");
	$aLebarClsKol=explode(",","4,".$sLebarFldD.",7");
	$aFldD=explode(",","xxno,".$sFldD.",xx");
	$aSzFldD=explode(",","0,".$sSzFldD.",0");
	$aClassFldD=explode(",",",".$sClassFldD.",");
	$aJenisFldD=explode(",","N,".$sJenisFldD.",");//i:input v:view
	$aFuncFldD=explode(",",",".$sFuncFldD.",");
		
	if (!isset($addFuncTbBaris[$idtb])) {
		if (isset($funcEvalD))
			$addFuncTbBaris[$idtb]=$funcEvalD;
		else 
			$addFuncTbBaris[$idtb]="";
	}
	$addFuncTbBaris[$idtb].="resizeInputTb('$classtb');";
	$jtb=$sClsKol=$sSample="";
	$jlhKol=count($aFldDCap);
	//ubah lebar kolom ke %
	
	$w=[];
	$k=0;
	foreach ($aFldDCap as $judulKol) {
	  if (substr($judulKol,0,2)=="xx") {
		  $lebar=0;
	  } else {
		  $lebar=$aLebarClsKol[$k];
	  }
	  $w[$k]=$lebar*1;
	  $k++;
	}
	
	//var_dump($w);
	$w=hitungskala($w,100,3);
	$k=0;
	foreach ($aFldDCap as $judulKol) {
		$nmFldD=$aFldD[$k];
		if (substr($nmFldD,0,2)=="xx") $nmFldD=substr($nmFldD,2,100);
		$szFldD=$aSzFldD[$k];
		$clsFldD=$aClassFldD[$k];
		$jenisFld=$aJenisFldD[$k];
		$xFunc=$func=$aFuncFldD[$k];
		if ($func!='') {
			$xFunc="onchange='$func' onkeyup='$func'";
		}
 		$wKol="width:".$w[$k]."%";
		
		$addst="";
		if ((substr($judulKol,0,2)=="xx") || ($w[$k]==0)) $addst="display:none";
		
		$jtb.="<div class='coldet coldet-$k tdjudul' >
			<div class=tisicoldet0>
			$judulKol
			</div>
		</div>";
		
		$sClsKol.="
		.$classtb .coldet-$k { $wKol; text-align:center; overflow:hidden;$addst }			";
		
		$sSample.="<div class='coldet coldet-$k' >
			<div class=tisicoldet0>
			<div id='$idtb-#br#-$k' class='tisicoldet'>
			";
		
		if ($k==0) 	
			$sSample.="<div class='brku tisicoldet'>
							<div class=tisicoldet0>
								#br#
							</div>
						</div>";
		elseif ($k==$jlhKol-1) 	{
			$sSample.="		
				<a href='#' class='tbhpdet btndet btn btn-danger btn-sm' 
				id='tbhpdet_$rnd"."_#br#'
				confirm='1'
				onclick=\"
				xcf=$(this).attr('confirm');
				if (xcf==1)
					cf=confirm('Yakin akan menghapus baris ini?');
				else
					cf=1;
				
				if (cf) {
					$('#$idtb-#br#').remove();
					
					//urutkan ulang nomor
					
					b=0;
					$('#tisi-$idtb .brku').each(function(){
						b++;
						$(this).html(b);
					}).promise().done(function(){ 
						".$addFuncTbBaris[$idtb]."
					}) ;
					
				}
				return false;
				\"
				
				><i class='fa fa-fw fa-trash'></i></a>
			
			";
		}		
		else { 	
			//if ($jenisFld=='i')
				//size='$szFldD'
				$sSample.="<input tipe=text class='$clsFldD' 
			name='d_$nmFldD"."[]' 
			id='$classtb-$nmFldD-$rnd-#br#' idx='#br#' valuex='#v$nmFldD#'   $ro $xFunc >";
			//else
			//	$sSample.="#v$nmFldD#";
				
		}
		$sSample.="</div></div></div>";
		
		$k++;
		
	}
	$judulTb="<div class=rowdet >$jtb</div>	";
	
	$footTb="</div>";//table
	
	
	if (!isset($footTbD)) $footTbD="";
	
	//tambahan sample
	
	
	$sampleIsiTb="
	<div id='$idtb-#br#' >
	<input type=hidden class='' name='d_id[]' id='$classtb-id-$rnd-#br#' valuex='#vid#' >
	$headTb
	<tr>$sSample</tr>
	$footTb		
	</div>
	";

if (!isset($funcTbBaris[$idtb])) {
	$funcTbBaris[$idtb]="	
	//proses menambahkan baris
	h=0;
	shasil=$('#thasil-$idtb').val();
	if (shasil=='') return;
	ahasil=shasil.split(';');
	ahasil.forEach(function(ahh){
		hasil=ahasil[h];
		if (hasil!='') {
			s=$('#tsample-$idtb').val();
			br=$('#jlhrowdet-$idtb').val()*1+1;
			$('#jlhrowdet-$idtb').val(br);
			
			s=s.replaceAll('#br#',br);
			$('#tisi-$idtb').append(s);
			
			//extract fldcari
			afc='$fldCari'.split(',');
			ahs=hasil.split('|');
			j=0;
			afc.forEach(function(fc) {	
				v=ahs[j];
				obj=$('#$classtb-'+fc+'-$rnd-'+br);
				cls=obj.attr('class');
				if (cls=='N') 
					v=maskRp(v,0,0);
				else if (cls=='C1') 
					v=maskRp(v,0,1);
				else if (cls=='C2') 
					v=maskRp(v,0,2);
				else {
					try {
						if (cls.indexOf('C')>=0) 
						v=maskRp(v);
					} catch(e) {}
				}
					$(obj).val(v);
				j++;
			});
			b=0;
			$('#tisi-$idtb .brku').each(function(){
				b++;
				$(this).html(b);
			});
			reBindStdClass();
		}
		h++;
	});
	$('#thasil-$idtb').val('');
	".$addFuncTbBaris[$idtb].";
	";
} 

$tbTambah="<div class='ttambahdet'>";
if ($capTbD!='') $tbTambah.="<h4 class='pull-left capTbDetail2'>$capTbD</h4>";

if ($jTampilanD=='form') {
	if (!isset($addParamDetCari)) $addParamDetCari="";
	$tbTambah.="
	<input id='thasil-$idtb' value='' size=0 type=hidden onclick=\" ".$funcTbBaris[$idtb]." \">
	<span class='pull-right tbtambah' >
	<a xstyle='margin-top:10px;padding-top:8px'
	href='' class='btn btn-primary btn-sm'
	onclick=\"
	tdialog='tdialog-$idtb';
	thasil='thasil-$idtb';
	$('#'+thasil).val('');
	bukaAjaxD(tdialog,'index.php?det=$detCari&op=$opcari".$addParamDetCari."&contentOnly=1&tdialog='+tdialog+'&thasil='+thasil,'width:900','');
	return false;\"  
	title='Tambah Data $capTbD'
	><i class='fa fa-plus'></i></a>
	</span>";
}
$tbTambah.="	</div>";

$jlhbr=0;
$isiTb="";
//menambah existing data

/*
$sqd="select pdd.id,pdd.kode as kdobat,ob.nmobat,pdd.hrg as hrgjual,pdd.jlh,ob.satuan,pdd.aturanpakai
from tbpendaftaran pd inner join tbpendaftarand pdd on pd.id=pdd.idpendaftaran
inner join tbobat ob on pdd.kode=ob.kdobat and pdd.grp='obat' and pd.id='$idpd'";
*/

$adt=sqlToArray($sqd,0);
$jlhbr=count($adt);
if ($jlhbr>0) {
	$br=1;
	foreach($adt as $rd) {
		$stb=$sampleIsiTb;
		//mengganti data
		$stb=str_replace('#br#',$br,$stb);
		$stb=str_replace("valuex='#vid#'","value='$rd[id]'",$stb);
		$i=0;
		foreach ($aFldDCap as $judulKol) {
			$nmFldD=$aFldD[$i];
			if (substr($nmFldD,0,2)=="xx") $nmFldD=substr($nmFldD,2,100);
			if (($nmFldD=='no')||(trim($nmFldD)=='')||(trim($nmFldD)=='&nbsp;')) { $i++;continue; }
			$vv=$rd[$nmFldD];
			$vv=changeValueByClass($vv,$aClassFldD[$i]);	
			$stb=str_replace("valuex='#v$nmFldD#'","value='$vv'",$stb);
			$i++;	
		}
		$isiTb.=$stb;
		$br++;
	}
}

$isiDetail="
<div id='$classtb"."_$rnd' class='$classtb'> 
	<div style='display:nonex'>
	<div id='tdialog-$idtb'></div>
	
	<input type=hidden name=jlhrowdet id=jlhrowdet-$idtb value='$jlhbr'>
	<textarea id='tsample-$idtb' style='display:none' disabled>$sampleIsiTb</textarea>
	</div>
	$tbTambah
	$headTb
	$judulTb
	$footTb
	<div id='tisi-$idtb'>
	$isiTb
	</div>

	$headTb
	$footTbD
	$footTb
</div>

<style>
.ttambahdet {
	display: inline-block;
	width: 100%;
	margin-bottom:10px;
}

.btndet {
	padding:2px 4px;
}
.tb-$classtb,
.$classtb {
	clear:both;
	width:100%;
	display:inline-block;
}
.$classtb .tdjudul {
	background:#e3e0e0;
	color:#000;
	padding: 5px 0px;
	}
.$classtb .rowdet:before {
	clear:both;
}

.$classtb td,
.$classtb .coldet {
	border-top:1px solid #f4f4f4;
	border-bottom:1px solid #f4f4f4;
	float:left;
}
.$classtb .tisicoldet0 {
	/*margin:0px 3px;*/
}

.$classtb .tisicoldet {
	width:100%;
	height: 30px;
	vertical-align: middle;
}
.$classtb .coldet input {
	width:100%;
	margin: 0px;
	box-shadow:none;
	transition:none;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
}

$sClsKol

.$classtb>.tbtambah {margin-bottom:5px} 
</style>
";

$tDetail=$isiDetail;
?>