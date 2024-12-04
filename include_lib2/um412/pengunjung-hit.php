<?php   
$ip   = $_SERVER['REMOTE_ADDR'];  
$tanggal = date("Ymd");  
$waktu  = time();  
$bln=date("m");  
$tglp=date("d");  
$blan=date("Y-m");  
$thn=date("Y");  
$ltimg1=$js_path."img/user";
$ltimg2=$js_path."img/icon";

$tglk="0".($tglp-1);
$kali=60 * 60 * 24;
$tglk= date("Y-m-d", time() - $kali);
/*
//sudah dimasukkan dipengunjung-cek.php

$s = mysql_query2("SELECT * FROM tbpengunjung WHERE ip='$ip' AND tanggal='$tanggal'");  
if(mysql_num_rows($s) == 0){  
mysql_query2("INSERT INTO tbpengunjung(ip, tanggal, hits, online) VALUES('$ip','$tanggal','1','$waktu')");  
}   
else{  
mysql_query2("UPDATE tbpengunjung SET hits=hits+1, online='$waktu' WHERE ip='$ip' AND tanggal='$tanggal'");  
}
*/
 $kemarin=mysql_query2("SELECT * FROM tbpengunjung WHERE tanggal='$tglk'");  
 $bulan=mysql_query2("SELECT * FROM tbpengunjung WHERE tanggal LIKE '%$blan%'");  
 $bulan1=mysql_num_rows($bulan);  
 $tahunini=mysql_query2("SELECT * FROM tbpengunjung WHERE tanggal LIKE '%$thn%'");  
 $tahunini1=mysql_num_rows($tahunini);  
		  $pengunjung       = mysql_num_rows(mysql_query2("SELECT * FROM tbpengunjung WHERE tanggal='$tanggal' GROUP BY ip"));  
		  $totalpengunjung  = mysql_result(mysql_query2("SELECT COUNT(hits) FROM tbpengunjung"), 0);   
		  $hits             = mysql_fetch_assoc(mysql_query2("SELECT SUM(hits) as hitstoday FROM tbpengunjung WHERE tanggal='$tanggal' GROUP BY tanggal"));   
		  $totalhits        = mysql_result(mysql_query2("SELECT SUM(hits) FROM tbpengunjung"), 0);   
		  $bataswaktu       = time() - 300;  
		  $pengunjungonline = mysql_num_rows(mysql_query2("SELECT * FROM tbpengunjung WHERE online > '$bataswaktu'"));  
 $kemarin1 = mysql_num_rows($kemarin);  
$stathit= "<br><table width='100%' border='0' class=tbstatpengunjung style='border:2px solid #000;border-bottom:5px solid #000;margin:5px;width:165px'>  
					 <tbody>
					<tr>  
					<td colspan='3' align='center' class=tdjudul >STATISTIK PENGUNJUNG</td></tr>  
					<tr>  
					  <td width='32' align='right' valign='middle'><img src='$ltimg1/pengunjung(5).png' width='16' height='16'></td>  
					  <td width='128' align='left' valign='middle'> Hari Ini</td>  
					  <td width='78' align='left' valign='middle'>:  
						  $pengunjung</td>  
					</tr>  
					<tr>  
					  <td align='right' valign='middle'><img src='$ltimg1/pengunjung(1).png' width='16' height='16'></td>  
					  <td align='left' valign='middle'>Kemarin</td>  
					  <td align='left' valign='middle'>:  
						$kemarin1</td>  
					</tr>  
					<tr>  
					  <td align='right' valign='middle'><img src='$ltimg1/pengunjung(2).png' width='16' height='16'></td>  
					  <td align='left' valign='middle'>Bulan ini </td>  
					  <td align='left' valign='middle'> :  
	 $bulan1</td>  
					</tr>  
					<tr>  
					  <td align='right' valign='middle'><img src='$ltimg1/pengunjung(3).png' width='16' height='16'></td>  
					  <td align='left' valign='middle'>Tahun ini </td>  
					  <td align='left' valign='middle'>:  
						  $tahunini1</td>  
					</tr>  
					 <tr>  
					  <td align='right' valign='middle'><img src='$ltimg2/chart.png' width='16' height='16'></td>  
					  <td width='98' align='left' valign='middle'>Total User</td>  
					  <td width='138' align='left' valign='middle'>:  
						  $totalpengunjung User</td>  
					</tr>  
					<tr>  
					  <td align='right' valign='middle'><img src='$ltimg2/chart.png' width='16' height='16'></td>  
					  <td width='98' align='left' valign='middle'>Total Hit</td>  
					  <td width='138' align='left' valign='middle'>:  
						  $totalhits</td>  
					</tr>  
					<tr>  
					  <td align='right' valign='middle'><img src='$ltimg2/chart.png' width='16' height='16'></td>  
					  <td align='left' valign='middle'>Hits Count </td>  
					  <td align='left' valign='middle'>:  
						  $hits[hitstoday]</td>  
					</tr>  
<tr>  
					  <td align='right' valign='middle'><img src='$ltimg1/pengunjung(4).png' width='16' height='16'></td>  
					  <td width='98' align='left' valign='middle'>Now Online</td>  
					  <td width='138' align='left' valign='middle'>:  
						  <b>$pengunjungonline</b> User</td>  
		  </tr>  
		  
		</tbody></table>";  
echo $stathit;
?>  