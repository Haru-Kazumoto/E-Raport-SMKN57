<?php
if (!isset($opcari)) $opcari="cari";
	//umum
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
	
	$headTb="<table border='0' cellpadding='1' cellspacing='1' style='width:100%' class='tb-$classtb' align=center >";
	$aJudulKol=explode(",",$sFldDCap);
	$aLebarClsKol=explode(",",$sLebarFldD);
	$aFldD=explode(",",$sFldD);
	$aSzFldD=explode(",",$sSzFldD);
	$aClassFldD=explode(",",$sClassFldD);
	$aJenisFldD=explode(",",$sJenisFldD);
	$aFuncFldD=explode(",",$sFuncFldD);
	
	if (!isset($addFuncTbBaris[$idtb])) {
		if (isset($funcEvalD))
			$addFuncTbBaris[$idtb]=$funcEvalD;
		else 
			$addFuncTbBaris[$idtb]="";
	}

	$jtb=$sClsKol=$sSample="";
	$jlhKol=count($aJudulKol);
	//ubah lebar kolom ke %
	
	$w=[];
	$k=0;
	foreach ($aJudulKol as $judulKol) {
	  $lebar=$aLebarClsKol[$k];
	  $w[$k]=$lebar;
	  $k++;
	}
	
	$w=hitungskala($w);
	
	$k=1;
	foreach ($aJudulKol as $judulKol) {
		$nmFldD=$aFldD[$k-1];
		$szFldD=$aSzFldD[$k-1];
		$clsFldD=$aClassFldD[$k-1];
		$jenisFld=$aJenisFldD[$k-1];
		$xFunc=$func=$aFuncFldD[$k-1];
		if ($func!='') {
			$xFunc="onchange='$func' onkeyup='$func'";
		}
		
		//$wKol=($aLebarClsKol[$k-1]=="*"?"" :"width:".($aLebarClsKol[$k-1])."px");
		$wKol="width:".$w[$k-1]."%";
		
		$st="";
		if (substr($judulKol,0,2)=="xx") $st="display:none";
		$jtb.="<td class='col-$k tdjudul' style='$st' >$judulKol</td>";
		$sClsKol.="
		.$classtb .col-$k { $wKol; text-align:center; overflow:hidden }			";
		
		$sSample.="<td class='col-$k'style='$st' ><div id='$idtb-#br#-$k' class='$clsFldD'  >";
		if ($k==1) 	
			$sSample.="<div class='brku'>#br#</div>";
		elseif ($k==$jlhKol) 	{
			$sSample.="
				
				<a href='#' class='tbhpdet btn btn-danger btn-sm' 
				id='tbhp_$idtb-#br#' onclick=\"
				if (confirm('Yakin akan menghapus baris ini?')) {
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
				$sSample.="<input class='$clsFldD' name='$classtb"."_"."$nmFldD"."[]' id='$classtb-$nmFldD-$rnd-#br#' idx='#br#' valuex='#v$nmFldD#'  size='$szFldD' $ro $xFunc >";
			//else
			//	$sSample.="#v$nmFldD#";
				
		}
		$sSample.="</div></td>";
		
		$k++;
		
	}
	$judulTb="<tr>$jtb</tr>	";
	$footTb="</table>";
	$footTb2="
		<tr>
			<td colspan='".($k-3)."' class='text-center' >JUMLAH</td>
			<td class='col-".($k-3)."' class='text-right' >#subtot#</td>
			<td class='col-".($k-2)."' >&nbsp;</td>
			<td class='col-".($k-1)."' >&nbsp;</td>
		</tr>
		";
		
	$footTb2="";
	
	//tambahan sample
	
	
	$sampleIsiTb="
	<div id='$idtb-#br#' >
	<input type=hidden name='$classtb"."_id[]' id='$classtb-id-$rnd-#br#' valuex='#vid#' >
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
				s=$('#tsample-$idtb').html();
				br=$('#jlhbr-$idtb').val()*1+1;
				
				s=s.replaceAll('#br#',br);
				$('#tisi-$idtb').append(s);
				
				//extract fldcari
				afc='$fldCari'.split(',');
				ahs=hasil.split('|');
				j=0;
				afc.forEach(function(fc) {	
					v=ahs[j];
					obj=$('#$classtb-'+fc+'-$rnd-'+br);
					if (obj.attr('class')=='N') v=maskRp(v);
					$(obj).val(v);
					j++;
				});
				$('#jlhbr-$idtb').val(br);
				b=0;
				$('#tisi-$idtb .brku').each(function(){
					b++;
					$(this).html(b);
				});
			
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
	$tbTambah.="
	<input id='thasil-$idtb' value='' size=0 type=hidden onclick=\" ".$funcTbBaris[$idtb]." \">
	<span class='pull-right tbtambah' >
	<a 
	style='margin-top:10px;padding-top:8px'
	href='' class='btn btn-primary btn-sm'
	onclick=\"
	tdialog='tdialog-$idtb';
	thasil='thasil-$idtb';
	$('#'+thasil).val('');
	bukaAjaxD(tdialog,'index.php?det=$detCari&op=$opcari&contentOnly=1&tdialog='+tdialog+'&thasil='+thasil,'width:900','');
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
		foreach($aFldD as $fld) {
			//$clsFldD=$aClassFldD[$i];
		
			if (($fld=='no')||(trim($fld)=='')||(trim($fld)=='&nbsp;')) { $i++;continue; }
			$vv=$rd[$fld];
			$vv=changeValueByClass($vv,$aClassFldD[$i]);
			
			$stb=str_replace("valuex='#v$fld#'","value='$vv'",$stb);
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
	
	<input type=hidden id=jlhbr-$idtb value='$jlhbr'>
	<div id='tsample-$idtb' style='display:none'>$sampleIsiTb</div>
	</div>
	$tbTambah
	$headTb
	$judulTb
	$footTb

	<div id='tisi-$idtb'>
	$isiTb
	</div>

	$headTb
	$footTb2
	$footTb
</div>

<style>
.$classtb .tdjudul {background:#e3e0e0;color:#000}
.$classtb td {
	padding:5px;
	border-top:1px solid #f4f4f4;
	border-bottom:1px solid #f4f4f4;
}
$sClsKol

.$classtb>.tbtambah {margin-bottom:5px} 
</style>
";
?>