<?php
/*
$subjectMail
$bodyMail
$fromName
$nmFileAttachment
$mailTo
$webmasterMail
$webmasterName

$systemMail="noreply@pitidismgdocquity.com";
$systemMailPss="noreply-tfc";
$mailPort= 587 ;
$mailHost="mail.pitidismgdocquity.com"; 
$mailSMTPSecure="TLS";

*/

$nfMailConf=$toroot."content/mail-conf.php";
if (file_exists($nfMailConf)) include_once $nfMailConf;

$komentar_email='';
$useJS=2;
if (!isset($useTemplateEmail)) $useTemplateEmail=true;
if ($useTemplateEmail) {
	$nf=$toroot."adm/template-email-local.php";
	$nf2=$um_path."template-email.php";
	if (file_exists($nf)) 
		include $nf;
	elseif (file_exists($nf2)) 
		include $nf2;
}

//if ($debugMode) echo "siap kirim email2.....";

$nfke=$toroot."adm/kirim-email-local.php";
if (file_exists($nfke)) {
	include $nfke;
} else {
	include_once($lib_path."mail/class.phpmailer.php");
	class Mailer extends PHPMailer {
		/**
		 * Save email to a folder (via IMAP)
		 *
		 * This function will open an IMAP stream using the email
		 * credentials previously specified, and will save the email
		 * to a specified folder. Parameter is the folder name (ie, Sent)
		 * if nothing was specified it will be saved in the inbox.
		 *
		 * @author David Tkachuk <http://davidrockin.com/>
		 */
		
		public function copyToFolder($folderPath = null) {
			$message = $this->MIMEHeader . $this->MIMEBody;
			$path = "INBOX" . (isset($folderPath) && !is_null($folderPath) ? ".".$folderPath : ""); // Location to save the email
			$imapStream = imap_open("{" . $this->Host . "/tls/novalidate-cert/norsh/service=imap/user=" . $this->Username . "}" . $path , $this->Username, $this->Password);
			imap_append($imapStream, "{" . $this->Host . "}" . $path, $message);
			imap_close($imapStream);
		}

	}
	
	$mail = new Mailer(true);
	//$mail = new PHPMailer(true);
	
	$mail->ClearAddresses();
	$mail->ClearCCs();
	$mail->ClearReplyTos();
	if (!isset($useSMTP))  $useSMTP=false;
	
	if ($useSMTP) {
		if (!isset($mailPort)) $mailPort= 587;
		if (!isset($mailHost)) $mailHost=$vDomain; 
		if (!isset($mailSMTPSecure)) $mailSMTPSecure= 'TLS';
		if (!isset($systemMailPss)) $systemMailPss="noreply-tfc";
		
		$mail->isSMTP();
		$mail->Host = $mailHost;  
		$mail->SMTPAuth = true;
		$mail->Username = $systemMail; 
		$mail->Password = $systemMailPss; 
		$mail->SMTPSecure =$mailSMTPSecure;
		$mail->Port = $mailPort;
		//$mail->SetFrom($webmasterMail,$webmasterName); 
		$mail->SetFrom($systemMail,$webmasterName); 
		if ($debugMode)	$mail->SMTPDebug = 2;
		
		/*
		$mail = new PHPMailer;
		// Konfigurasi SMTP
		
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'um412test@gmail.com';
		$mail->Password = 'testYa.Mas.um';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		$mail->setFrom('um412test@gmail.com', 'um412test');
		$mail->addReplyTo('um412test@gmail.com', 'test');
		$mail->SMTPDebug = 2;
		*/
		$komentar_email.="Menggunakan SMTP<br>";
		//global $debugMode;
		/*
		echo "
		
		
		<br>host $mail->Host  
		<br>smtpauth $mail->SMTPAuth  
		<br>user $mail->Username  
		<br>pws $mail->Password  
		<br>smtpsecure $mail->SMTPSecure  
		<br>port $mail->Port 
		<br>debug $mail->SMTPDebug
		<br>
		";
		*/
	}
//	ini_set('sendmail_from', $fromMail);
	ini_set('sendmail_from', $systemMail);
//	$fromMail= $mail->FromName = $webmasterMail;
	$fromName= $mail->FromName = $webmasterName;
	$fromMail= $mail->From = $systemMail ;
	
	
	$testMail="mr.um412@gmail.com";
	if (strstr($mailTo,$testMail)!='') {
		$mailTo=$testMail;
	}
	
	$amailto=explode(",",$mailTo);
	$stremail="";
	foreach ($amailto as $am) {
		$am=trim($am);
		if ($am!="") {//jika tidak kosong
			if (filter_var($am, FILTER_VALIDATE_EMAIL))  { //jika valid
				if (strstr(" $stremail "," $am ")=="") { //jika tidak dobel
					$mail->AddAddress($am);
					$stremail.=" $am";
				}
			}
		}
	}

	if (isset($mailCC)) {
	  $amailto=explode(",",$mailCC);
	  $stremail.="<br>cc : ";
	  foreach ($amailto as $am) {
		if ($am!="") {
			$mail->AddCC($am);
			$stremail.=" $am";
		}
	  }
	}

	if (isset($replyTo)) {
	  $amailto=explode(",",$replyTo);
	  $stremail.="<br>Reply To : ";
	  foreach ($amailto as $am) {
		if ($am!="") {
			$mail->AddReplyTo($am);
			$stremail.=" $am";
		}
	  }
	}

	//$mail->AddAddress($email, $email);
	$mail->Subject = strip_tags($subjectMail);
	$mail->Body = $bodyMail; 
	//$mail->FromName = $fromName;
	//$mail->From = $fromMail;
	//$mail->FromName=$webmasterMail;
	//$mail->From= $webmasterName; 

	/*
	$headers = 'From: My Site <accounts@mysite.com>' . "\r\n";
	$sendTo = "$email";
	$subject = "Welcome to My Site!";
	$headers .= "Reply-To: accounts@mysite.com\r\n";
	$headers .= "Return-Path: accounts@mysite.com";
	$headers .="-faccounts@mysite.com";
	$message = "Welcome to My Site! \n\nBla Blah Blah";
	mail($sendTo, $subject, $message, $headers); 
		  */
		  
	//$header="Content-Type: text/plain; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit";
	//if (!isset( $mailFrom)) $mailFrom="noreply@".$_SERVER['HTTP_HOST'];
	$mail->CharSet = 'UTF-8';
	$komentar_email.=" From :".($mail->From)." ($fromName) , SystemMail: $systemMail <br/>";

	//jika kirim banyak file
	if (isset($sNmFileAttachment)) {
		//echo "File Attachment : $sNmFileAttachment<br>";
		$anf=explode(",",$sNmFileAttachment);
		foreach ($anf as $nfa) {
			if ($nfa!='') $mail->AddAttachment($nfa);		
			//echo "<br>".$nfa;
		}
	}
	else if (isset($nmFileAttachment)) {
	  if ($nmFileAttachment!='') {
		  $mail->AddAttachment($nmFileAttachment);
		  $sNmFileAttachment=$nmFileAttachment;
	  }
	}
	
	$terkirim=false;
	$pe="";
	if ($isOnline) {
		try {
		  if ($mail->Send()) {
			  $terkirim=true;
			  $komentar_email.="Email Sent to $stremail !<br>";
			  //$mail->copyToFolder("Sent"); // Will save into Sent folder
		  }
		} catch (phpmailerException $e) {
			$pe.= $e->errorMessage(); //Pretty error messages from PHPMailer
			$errinfo=$mail->ErrorInfo;
			$pe.= $errinfo;
		} catch (Exception $e) {
			$pe=$e->getMessage(); //Boring error messages from anything else!	
		}
	}
	$berhasil_kirim=($terkirim?1:0);
			  
	if (!$terkirim)
		$komentar_email.="Cannot Sent to $stremail !
		<br>Error Message : $pe <br>";
		
	else {
		if (!$isOnline) {
			$komentar_email.= "<hr>
			<b>Trackking email untuk keperulan offline</b>
			<br> Email:$stremail";
			$komentar_email.= "Subject Email: ".strip_tags($subjectMail);
			if (isset($sNmFileAttachment) ) $komentar_email.= " <br>file Attachment: <br>* " .str_replace(",","<br>* ",$sNmFileAttachment);
		}
	}
}

$logMail= "
<pre>
Subject:
$subjectMail

Body:
$bodyMail

From:
$fromMail ($fromName)

nf Attachment:
$nmFileAttachment

To:
$mailTo

WebmasterMail:
$webmasterMail

WebMasterName
$webmasterName

Komentar:
$komentar_email
		
</pre>
";

if ($debugMode) {
	echo "Debug :";
	echo $logMail;
}
?>