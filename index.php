<?php
@session_start();
unset($_SESSION['dbku']);

?>
<style>
body {background:#CCC}

 a.a1,a.a2,a.a3,a.a4 {
	font-size:20px;
	width:300px;
	display:block;
	background:#39C;
	color:#FFF;
	padding:20px;
	text-decoration:none;
	}
	
 a.a2 {
	background:#D2830C;
	}
 a.a3 {	 
	background:#16810F;	 
	}	
 a.a4 {	 
	background:#5f1e84;	 
	}	
.container {
    position: relative;
    background: none repeat scroll 0% 0% #FFF;
    padding: 20px;
    width: 400px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
	margin:auto;
}
.container {
    max-width: 1170px;
}
.container {
    max-width: 970px;
}

</style>
<center>

<br><br><br>
<div class='container'>
<h1>PILIH KURIKULUM</h1>
<br>
<a class=a4 href='k13v4/index.php'>KURIKULUM SMK PUSAT KEUNGGULAN & PROFIL PELAJAR PANCASILA</a> 
  <br>
 <a class=a1 href='k13v3/index.php'>KURIKULUM 2013 V.3 <br> Mulai Tahun 2016/2017</a> 
  <br>
 <a class=a2 href='k13v2/index.php'>KURIKULUM 2013 V.2<br> (Tahun 2014/2015, 2015/2016)</a> 
  <br>
 <a class=a3 href='k13v1/index.php'>KURIKULUM 2013 V.1 <br>(Tahun 2013/2014)</a> 
  <br>

 
<br>
</div >
</center>