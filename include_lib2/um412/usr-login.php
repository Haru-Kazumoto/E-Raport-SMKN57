<?php
$useJS=2;
//include_once ("conf.php");
$pes="";
if (!isset($opr))	$opr="";
if (isset($_REQUEST["op"]))	$opr=$_REQUEST["op"];

if (($opr=='') &&(isset($_REQUEST["rep"]))) $opr=$_REQUEST["rep"];

if ($opr!='') {
	if ($opr=="logout"){
		 unset($_SESSION["usrid"]);
		 unset($_SESSION["usrnm"]);
		 unset($_SESSION["usrtype"]);
		 unset($_SESSION["viewAs"]);
		 unset($_SESSION["subAdmin"]);		 
		 $pes="user berhasil keluar.....";
		 $nf=$toroot.'adm/index.php';
		 $nf2=$toroot.'index.php';
		 if (file_exists($nf)) $nf2=$nf;
		 redirection($nf2,0);
		 exit;
	 } elseif (($opr=="login")||($opr=='cek')) {
		 if ($_REQUEST["usrid"]==''){
			$pes="<div class=comment1>user tidak boleh kosong....</div>";
			echo $pes;
			exit;
		 } else {
			user_cek();
			if ($userID=='Guest'){
				$pes="<div class=comment1>user tidak dikenal....</div>";
				echo $pes;
				exit;
		 	} else if ($opr!='cek') { //sukses
				  $pes="<div class=comment1>User berhasil masuk<br><a href=index.php>klik di sini </a> untuk melanjutkan</div>";
				  echo $pes;
				  if (isset($_SESSION['redirection'])) {
					 $redir=$_SESSION['redirection']; 
				     unset($_SESSION['redirection']); 
				  }
				  if (!isset($redir)) $redir="index.php";
				  if ($redir!='') redirection($redir,0);
				 // echo "redir $redir rep $rep";exit;
				  exit;
			 }
		}
	 } elseif (($opr=="tb") || ($opr=="ed")) {
		kill_unauthorized_user();
		$uid=$_REQUEST["usrid"];
		$idrecord=$_REQUEST["id"]*1;
		$username=$_REQUEST["usrname"];
		$email=$_REQUEST["email"];
		$pass1=$_REQUEST["pass1"];
		$pass2=$_REQUEST["pass2"];
		$telp=$_REQUEST["telp"];
		$alamat=$_REQUEST["alamat"];
		$identitas=$_REQUEST["identitas"];
		//$captcha1=$_REQUEST["captcha"]*1;
		//$captcha2=$_SESSION["um412_captcha_user"]*1;

		if ($pass1!=$pass2){
			um412_falr("Password tidak sama<br>");		
			exit;
		}
		$idm=um412_carifield("select id,userid from tbuser  where userid='$uid'");
		$idm=$idm*1;
		if (($idm!=0)&&($idm!=$idrecord)){
			um412_falr("user id sudah dipakai oleh orang lain");
			include "usr-inp.php";
			exit;
		}
	
		if ($opr=='tb')
			$sql="INSERT INTO tbuser(userid,pass)  VALUES ( '$uid',md5('$pass1'))";
		else 	{
			$sql="update tbuser  set pass =md5('$pass1') where userid='$uid'";
		}
	} else if ($opr=="hp"){
			kill_unauthorized_user();
	
		$sql= "delete from tbuser where id='$idrecord'";
	} else  {
			kill_unauthorized_user();
	
	}
}

if (($opr=='hp') || ($opr=='ed') || ($opr=='tb')){
	$hasil=mysql_query2($sql);
	if (!$hasil)
			um412_falr("query tidak bisa dilaksanakan $sql ");
	else {
	echo "Operasi berhasil dilakukan";
	redirection($js_path."index.php",1);
 	}
} 

$nfAction=$toroot."adm/index.php?rep=login";
$idForm="usr_".(rand(2345,322333));
$t="<div id='ts"."$idForm' ></div>";
$aform="method='post' enctype='multipart/form-data'  name='$idForm' id='$idForm' 
		onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','user');return false;\" ";
$t.="  <form action='$nfAction' $aform  >";
echo $t;
if ($pes!='') {
	echo "<center> <div class=comment1><center>$pes</center></div><br><br></center>";
	}
 ?>
 <center>
 <div class=dialog1 id=dialoglogin style="margin-top:150px" > 
 <div class=titledialog1>USER LOGIN</div> 
 <div class=tooltips>&nbsp; </div>
<table width=250 align=center cellpadding=0 cellspacing=0 border=0>
	<tr class="troddform2"> <td style='width:75px' >User ID </td> <td  >:</td><td> <input name=usrid type=text id=usrid size=20 /></td></tr>
	<tr class="troddform2"> <td  > Password </td><td  >:</td><td><input name=usrps type=password id=usrps size=20 /></td></tr>
	<tr><td colspan=3  align=center><br><center> 
    <input name=HOME type=button  value=HOME class=button onclick="location.href='<?=$toroot?>index.php';"/>
    <input name=submit type=submit  value=LOGIN class=button /></center></td></tr>
	<tr><td  align=center  colspan=3>&nbsp;</td></tr>
</table>
<input type=hidden  name=op value=login >
 </DIV>
 </center>
 </form>
 