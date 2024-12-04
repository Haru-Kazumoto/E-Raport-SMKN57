<?php
@session_start();
$grafik=array();
$y1=implode(",",$astatistik);
$xlabel=implode(",",$ajudul);
$judul="GRAFIK SEBARAN NILAI - $soal";
echo ="
<center>
<img src='up/buatgrafik-pie.php?y1=$y1&xlabel=$xlabel&judul1=$judul' />
</center>
<br>
<br>
";

?>