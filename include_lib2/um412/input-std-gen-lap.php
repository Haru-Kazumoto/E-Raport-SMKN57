<?php
//generate field
$afld=array();
$sfld="";
$i=0;
$result="";

$sty="
 <style>
.tbhead {
	margin-bottom:10px;
	margin-top:12px;
}
.tbhead0 td {
	font-size:15px;
}

.tbhead td {
	height:24px;
	font-size:13px;
}
.tbfoot1 {	 
	margin-right:20px;
	margin-bottom:20px;
}
.tbfoot1 td {
	height:24px; 
}
.tbfoot2 {
 
}
.trhead {
	background:#CCCCCC;
	
}
.trhead th  {
	text-align:center;	
	
}
@media print {
	.page {
		padding:2.1cm;
		
	}
}
</style>
"; 
 
$adds=$addj="";
$adds1=$addj1="";

/* 
if ($kdkelas==''){
	echo um412_falr("Pilih  kelas terlebih dahulu....");
	exit;//
}
$adds.=" and s.kdkelas='$kdkelas'";
*/
	

$t="<link href='$js_path"."style-cetak.css' rel='stylesheet'>";
echo $sqc=$sqLap;

$cekQuery = mysql_query2($sqc);
//$maxBrPerPage = 22;
 
$jumlahData = mysql_num_rows($cekQuery);
$jlhPage = ceil($jumlahData/$maxBrPerPage);
$no = 1;
$clspage="page-landscape";

for($i=1;$i<=$jlhPage;$i++){ 
	$t.="<div class=$clsPage >";
	$t.=$tbJudul;
	$mulai = $i-1;
	$batas = ($mulai*$maxBrPerPage);
	$startawal = $batas;
	$batasakhir = $batas+$maxBrPerPage;
	$s = $i-1;
	$tb=new htmlTable();
	$tb->sJudul=$sJudulTb;//"NIS,NAMA";
	$tb->sql=$sqLap."  limit $batas,$batasakhir";
	$tb->sAlign=$sAlignTb;
	$t.=$tb->show();


/*  if ($i==$n) {
	//$jlhorang=$jlhdatang+$jlhabsen;
	echo "<tr  >
		<td align=center>&nbsp;</td>
		<td align=center colspan=2 >JUMLAH</td>";
	for ($h=1;$h<=$jh;$h++) {
		 echo "  <td align='center' style='text-align:center' width='$wh'  >$ajlh[$h]</td>";
	}
	echo "</tr>";
  }
*/
  

	$t.= "</div>";  //page
     
 } //akhir jumlah hal persesi

$t.= "
<style>
.page td,
.page th,
.page-landscape th,
.page-landscape td {
	font-size:10px;
	font-family:arial;
	line-height: normal;
}
@media print {
	* {
		font-size:10px;
	}
}
</style>

</div>";//akhir printarea
echo $t;
?>