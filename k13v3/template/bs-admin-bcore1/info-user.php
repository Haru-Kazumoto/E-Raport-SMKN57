<?php
$fotoUser="foto/foto-default.gif";
cekVar("op");
$infoUser='';
$clsfoto=" style='max-width:170px' class='media-object img-thumbnail user-img'";

if (!isset ($mwFoto)) $mwFoto=50;//lebar foto
if  (strstr("kaprog,guru",$userType)!='')  {
	$pathFoto="foto/guru/";
	$kdguru=carifield("select kode from guru where uidg='$userID'");
	$nf=$pathFoto."foto-$kdguru.jpg";
	if (file_exists($nf)) { $fotoUser=$nf;	} 
	//if (($det=="profile")||($det=='')) {
//	if ($det=="profile")  {
		extractRecord("select * from guru where uidg='$userID'");
		$smp="<ul><li>".cariSfield('select nama from matapelajaran ',"$skdmapel",'kode','</li><li>')."</li><ul>";
		$infoUser="
			<table width='100%' border=0><tbody>
			<tr class='troddform2'>
			<td class='tdcaption' width=200 >Nama</td>
			<td>: $nama</td>
			<td rowspan=7 valign=top  style='width:120px' align=right><img src='$fotoUser' $clsfoto ></td>
			</tr>
			
			<tr class='troddform2'><td class='tdcaption' >NIP</td><td>: $nip</td></tr>
			<tr class='troddform2'><td class='tdcaption' >User ID</td><td>: $uidg</td></tr>
			<tr class='troddform2'><td class='tdcaption' >Mata Pelajaran yang diampu</td><td>: </td></tr>
			<tr class='troddform2'><td class='tdcaption'  colspan=2> $smp</td></tr>
			</tbody>
			</table>
		";
		if ($op=='edit') {
			$showLinkLihat=false;
			$op=$_REQUEST["op"]="itb";
			$det=$_REQUEST['det']=($userType=='siswa'?'siswa':"guru");
			$nexturl="index.php";
			include "input.php";
		} 
		//echo $infoUser;

	//	exit;
	//}
} elseif ($userType=='siswa') {
	$pathFoto="foto/siswa/";
	$nf=$pathFoto."foto-$nis.jpg";
	if (file_exists($nf)) {
		$fotoUser=$nf;
	} 
//	if ($det=="profile") {
		extractRecord("select s.*,k.nama as kelas from siswa s left join kelas k on s.kode_kelas=k.kode where nis='$nis'");
			$t="
			<h3>Profil User</h3>
			<table ><tbody>
			<tr><td rowspan=7 valign=top width=110><img src='$fotoUser' $clsfoto></td></tr>
			<tr class='troddform2'><td class='tdcaption' >Nama</td><td>: $nama</td></tr>
			<tr class='troddform2'><td class='tdcaption' >NIS</td><td>: $nis</td></tr>
			<tr class='troddform2'><td class='tdcaption' >Kelas</td><td>: $kelas</td></tr>
			</tbody>
			</table>
";
	if ($op=='edit') {
		$showLinkLihat=false;
		$op=$_REQUEST["op"]="itb";
		$det=($userType=='siswa'?'siswa':"guru");
		include "input.php";
	} 
		//echo $t;

		//exit;
//	}
} else {
	
		$infoUser="
	<div class=''>
		<br>
			<img class='media-object img-thumbnail user-img' alt='User Picture' src='$fotoUser'  align=left style='margin-right:20px;width:$mwFoto"."px'/>
		<div class='media-body'>
		<table>
		<tr><td>Nama Guru</td><td>: $userName </td></tr>
		<tr><td>Nama User</td><td>: $userName </td></tr>
		<tr><td>User Type </td><td>: $userType </td></tr>
		</table>
		</div>
	</div>
	";

}
?>