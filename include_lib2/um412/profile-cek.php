<?php
$useJS=2;
include_once  "conf.php";

extractRequest();
$pes="";
if ($nama=='') $pes.="<br>Contact Person cannot be empty";
if ($alamat=='') $pes.="<br>Address cannot be empty";
if ($hp=='') $pes.="<br>Mobile Phone cannot be empty";


if ($email=='') 
		 $pes.="<br>Email cannot be empty";
else if (!validasiEmail($email))
		 $pes.="\nEmail invalid ";
		
if (($pass=='') ||($pass2==''))
	 $pes.="<br>Password cannot be empty";
else if (($pass!=$pass2))
	 $pes.="<br>Password and Confirm Password do not match ";
else if ((strlen($pass)<6))
	 $pes.="<br>Password Password length to sort, minimal password length 6 character";
		
	//if ($pertanyaan=='') $pes.="\nHint Question cannot be empty";
	//if ($jawaban=='') $pes.="\nHint Answer cannot be empty";

if ($_REQUEST['email']!='') $email=$_REQUEST['email'];
$cari=cariField("select email from tbprofile where email='$email'");
if ($cari!=''){   $pes.="<br>Email $email already registered.....";}

echo "$pes";
if ($pes!='') exit;
?>