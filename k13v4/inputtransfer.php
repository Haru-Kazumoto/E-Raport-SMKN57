<?php
$useJS=2;
include_once "conf.php";
extractRequest();

$nama=cariField("select nama from siswa where nis='$id'");
$kode_kelas=cariField("select kode_kelas from siswa where nis='$id'");
$kelas=cariField("select nama from kelas where kode='$kode_kelas'");
 

?> 
<form>
<table>
 
<tr class=troddform2 $sty >
	<td class=tdcaption >Nama Siswa</td>
	<td>: <?=$nama?></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td>: <?=$kelas?></td> 
</tr>
 

</table>
 
<br>
<div id=tcas>
<form>

<br><table>
<tr class=troddform2 $sty >
	<td colspan=2> Pindah Kelas Alumni Drop Out</td>
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tanggal</td>
	<td>: <input type=text name=n id=id></td> 
</tr> 
</table>
<div id=tpin[0]>
<table>
<tr class=troddform2 $sty >
	<td class=tdcaption >Pindah ke Kelas</td>
	<td>: <input type=text name=n id=id></td> 
</tr>
</table>
</div >
<div id=tpin[1]>

<table>
<tr class=troddform2 $sty >
	<td class=tdcaption >Tahun</td>
	<td>: <input type=text name=n id=id size=4> Melanjutkan ke : <input type=text name=n id=id></td> 
</tr>
</table>
</div >
<div id=tpin[2]>
 
</div>
<table>
<tr class=troddform2 $sty >
	<td class=tdcaption >Keterangan</td>
	<td>: <input type=text name=n id=id></td> 
</tr>

<tr class=troddform2 $sty >
	<td class=tdcaption > </td>
	<td><input type=submit value='Simpan Data'></td> 
</tr>
</table>
</form>