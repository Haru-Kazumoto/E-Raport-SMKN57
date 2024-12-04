<?php

//if ($det=='profile') {
if (($det=='profile') ||($userType!='admin') ){
	$mwFoto=150;//lebar foto
	
	$t="";
	$t.="<br>User Type : $userType ";
	$t.="<br>User ID : $userID";
	$addInfoUser=$t;
	 
	include $tppath."info-user.php";
	cekVar("op");
	
	?>
	<div class="panel panel-success">
		<div class="panel-heading">
			Profil Pengguna
		</div>
		<div class="panel-body">
			<p>
			<?=$infoUser?></p>
		</div>
		
	</div>
<?php
	//exit;
} else {
	include "inputsekolah.php";

	echo "
            <div class='well well-small'>
                $infoSekolah 
            </div>";
}

//menampilkan informasi berita
$t="
<style>
.isinews {
	margin:5 0 10px 0;
	}
</style>
";

$det="news";
//include "input.php";
 $hal="input.php?det=news";
 $sq="select * from tbnews order by id desc";
 $hn=mysql_query($sq);
 while ($rn=mysql_fetch_array($hn)) {
	$idnews=$rn["id"];
	$isi=$rn['isi'];
	$tbopr="";
	$rnd1=rand(123,2221212);
	$tbopr.="<div id='tv_$rnd1'></div>";
	/*
	$tbopr.="<a href=# onclick=\"bukaAjaxD('tv_$rnd1','$hal&op=view&id=$idnews&newrnd=$rnd1','width:1000,height:450','awalEdit($rnd1)');
	return false;\"><i class='icon-search'></i>&nbsp;</a>";
	*/
	$tbopr.="&nbsp;&nbsp;<button class='btn btn-sm btn-circle btn-primary'  href=# onclick=\"bukaAjaxD('tv_$rnd1','$hal&op=itb&id=$idnews&newrnd=$rnd1','width:1000,height:450','awalEdit($rnd1)');
	return false\"><i class='icon-edit'></i></button>";
	$tbopr.="&nbsp;&nbsp;<button class='btn btn-sm btn-circle btn-danger' href=# onclick=\"if (confirm('Yakin akan menghapus?')) { bukaAjax('content','$hal&op=del&id=$idnews&newrnd=$rnd1');
	return false; } \"   ><i class='icon-trash'></i></button>";
	 
	if ($userType=='admin') {
		$isi.="<div align=right>$tbopr</div>";		
	}


 	$t.="
	
	<div class='panel panel-info'>
		<div class='panel-heading'>
			$rn[judul]
		</div>
		<div class='panel-body'>
			<p>$isi</p>
		</div>
		
	</div>
	
	";
	
 }
if ($isAdmin){
	$t.="<button class='btn btn-sm btn-success'  
	href=# onclick=\"bukaAjax('content','input.php?det=news&op=itb&newrnd=$rnd',0,'awalEdit($rnd)');return false;\" ><i class='icon-plus'></i> Tambah Info</button><br>";
}
 echo $t;

 
?>

