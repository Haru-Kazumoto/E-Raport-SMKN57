<?php

$arr=[
['A08','B20'],
['A11','B21'],
['A09','B22'],
['A12','B23'],
['B01','A20'],
['B02','A21'],
['B03','A22'],
];

$ssq="";
foreach($arr as $ar) {
	$ssq.="update matapelajaran set kode='$ar[1]' where kode='$ar[0]';\n";
	$ssq.="update map_matapelajaran_kelas set matapelajaran=replace(matapelajaran,'#$ar[0]|','#$ar[1]|');\n";
	$ssq.="update kompetensi set kode=replace(kode,'$ar[0]','$ar[1]'),kode_matapelajaran='$ar[1]' where kode_matapelajaran='$ar[0]';\n";
	$ssq.="update nilai_kompetensi_siswa set kode_kompetensi=replace(kode_kompetensi,'$ar[0]','$ar[1]') where kode_kompetensi like '$ar[0]%';\n";
	
}

echo "<pre>$ssq</pre>";