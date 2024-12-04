<?php
$showHeader=0;
$tmpcolor='brown';
  
include_once "conf.php";

?>

<div class=header1 id=header1>  <!--url's used in the movie-->
	<?php echo "<div class=text_header1>$nmProgram</div>"; ?>
	<?php echo "<div class=text_header2>$judulWeb2</div>"; ?>
      <?php echo "<div class=text_header3>$deskripsiWeb</div>"; ?>
      <br>
		<marquee id=textjalan1 scrollamount="3">
        SELAMAT DATANG DI SISTEM INFORMASI PENILAIAN SISWA KURIKULUM 2013, Versi 3.0
        </marquee>
        <br>
</div>
<?php	
//if ($_SESSION['nis']=='')	
include "mnu-atas1.php"; 
?>

<div class=container1 id=container1>
    <table  border="0" class=content1 id=content1 align="center" valign=top>
      <tr>
        <td valign="top"  id=leftmenu class=tdleft style='display:-'>
        <div align=center  style='display:-'>

</div></td>
<td valign=top><br>
<div align=center id=rightmenu style='display:none'><a href=#  class=button onclick=hideMenu(0)>show menu</a></div>
<div id="maincontent" >
