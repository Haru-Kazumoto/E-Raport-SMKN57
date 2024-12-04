<?php
if (!isset($fldpass)) $fldpass="pass";
if (!isset($fldpase)) $fldpasse='';
cekVar("op2");
extractRecord("select $nmFieldID as c".($fldpasse!=''?",email":"")." from $nmTabel where $nmFieldID='$id'");

if ($c=='') {
	echo "Maaf, Link Salah atau mungkin sudah tidak berlaku.....";
	exit;
}
	$idForm="cps".rand(1211,22221);
	$ntkn=makeToken("op2=cpassp&id=$id");
	$asf="";
	//$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','',' ');return false;\" ";
	$t= "
	<form action='".$toroot."index.php?det=$det&op=cpass&op2=cpassp' method=post $asf id=$idForm >
		<table style='width:100%;max-width:400px'>
		".($fldpasse!=''?"<tr><td>Email</td><td>: $email</td></tr>":"").
		"<tr><td>Password Lama  </td>			<td>: <input type=password name=opass  value='' size=20></td></tr>
		<tr><td>Password Baru </td>				<td>: <input type=password name=npass1 value='' size=20></td></tr>
		<tr><td>Konfirmasi Password Baru </td>	<td>: <input type=password name=npass2 value='' size=20></td></tr>
		</table> 
	<input type=hidden name=tkn value='$ntkn' >
	<br><input type=submit value='Submit' >
	</form>

	";
	
	$frm="";
	$frm.=tpTitlePage('Perubahan Password',$nobread=true);
	$frm.=tpBoxInput($t,'nobread');
	
if ($op2=='') {
	echo $frm;
	exit;
}

if ($op2=='cpassp') {
	$t="";
	//echo "$id $opass $npass1 $npass2";
	$sqcp="select $fldpass from $nmTabel where $fldpass='$opass' and  $nmFieldID='$id'";
	$t.=$sqcp;
	$pss=cariField($sqcp);
	if ($pss!='') {
		if ($npass1==$npass2) {
			mysql_query2(" update $nmTabel set $fldPass='$npass1' where id='$c' ");
			$bodyMail="";
			$sy="";//antisipasi hack
			//extractRecord("select id as idt,email,acck,unit as nama,'' as namab from $nmTabel where id='$id' $sy");
			//if ($idt=='') include "report-hack.php";
			
			$bodyMail.="
				<h2>Perubahan Password</h2>
				Perubahan berhasil dilakukan.....<br><br>
				<h3>Informasi Perubahan Password</h3>
				<div>
				<table width=200>".
				($fldpasse==''?'':"<tr><td>Email</td><td>: $email</td></tr>")."
				<tr><td>Password Lama Anda </td><td>: <b>$opass</b></td></tr>
				<tr><td>Password Baru Anda </td><td>: <b>$npass1</b></td></tr>
				</table>
				</div><br>
				Klik <a href='$folderHost'>di sini</a> untuk kembali ke halaman utama...
				<br>	
			";
			
			if ($fldpasse!='') {
				$file = "";
				$fromName=$webmasterName;
				$fromMail=$webmasterMail;
				$mailTo="$email";
				$subjectMail="Perubahan Password ";
				
				include $um_path."kirim-email.php";
			}
			$t.="
				<h2>Perubahan Password</h2>
				Perubahan berhasil dilakukan.....<br><br>
				";
			if ($fldpasse!='') {
				$t.="Info Perubahan Email Terkirim ke $email<br>Info Email: $komentar_email <br>
				$linkBack";
			}
		} else 	{
			 $t.= "			
			 <h2>Perubahan Password</h2>
			 Perubahan Password tidak berhasil, password baru dengan konfirmasi password baru tidak sama 
			 $linkBack";
		}
	} else {
		$t.="						
		<h2>Perubahan Password</h2>
		Perubahan Password tidak berhasil, password lama tidak sama 
		$linkBack";
	}
	echo $t;
	exit;
}
?>