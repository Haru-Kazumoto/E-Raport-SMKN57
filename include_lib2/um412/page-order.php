<style>

.kotak {
	background:#ccc;
	border:#000 2px solid;
	padding:10px;
	margin-right:15px;
	}
.tombol {
	width:400px;
	display:block;
	}
</style>
<div  class=kotak > 
<center>
<?php
//include_once 'conf.php' ;
cekVar('aksi,sq,sbenar,ssalah');
ku();
//$aksi=$_REQUEST["aksi"];
$strsql=str_replace("\\","",$sq);
if ($aksi=='simpan') {
	
	$asql=explode(";",$strsql.";");
	$br=sizeof($asql)-1;
	$jbenar=$jsalah=0;
	$sbenar=$ssalah="";
	for ($i=0;$i<$br;$i++) {
		if (trim($asql[$i])!='') {
			$h=@mysql_query($asql[$i]);
			if ($h) {
				$jbenar++;
				$sbenar.="<br>".$asql[$i].";";
			}else {
				$jsalah++;
				$ssalah.="<br>".$asql[$i].";";	
			}
		}
	}
	$jbaris=$jbenar+$jsalah;
	echo "
	 <div class=kotak>
	 <div class=titledialog1>info</div>
	 Jumlah baris : $br | Berhasil :  $jbenar | Salah : $jsalah 
	<p align=left>Baris Berhasil :<br>$sbenar";
	if ($jsalah>0) echo "<br>Baris Salah :<br>$ssalah";
	echo "</p></div>";

}elseif ($aksi=='phpinfo') {
	echo phpinfo();
}
?>
<div style="background:#ccc;padding:10px">
<form action="index.php?rep=po&det=po&aksi=simpan" method=post >
<textarea id=orderredpage  name=sq rows=20 cols=150><?=str_replace("<br>","",$sbenar."\n\n".$ssalah)?></textarea>
<br /><br /><input type=submit value=simpan>
</form>
</div>
<?php
/*
$br=0;
$op="";
$uop="";
$ss=mysql_query2("select pg,judul,urutan from tbpage  order by urutan");
 while ($r=mysql_fetch_row($ss)){
	 $br++;
	 $uop.="
	 <input type=button  class=tombol name=bt_$br id=bt_$br value='$r[0]' 
	 onclick=\"orderPage('$r[0]',this.name);\" >";
	 } 
<div id=unorderredpage ><br>Mulai urutan<input type=text name=br id=br value='1'><?=$uop?></div>

	 */
 ?>
</center>
</div>
