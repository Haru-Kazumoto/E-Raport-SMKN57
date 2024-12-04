<?php
//include_once "conf.php";
cekVar("signature2,nfgb,folderHost,subjectMail,bodyMail,showresult");
if (!isset($showresult)) $showresult=0;
if ($nfgb=='') $nfgb="$folderHost"."slider/01.jpg";
$bodyMail="
<style>
#footmail a {color:#fff}
</style>
<table id='u140' border='0' cellpadding='0' cellspacing='0' width='700'>
	<tr >
		<td style='padding:5px;font:Verdana, Arial, Helvetica, sans-serif 12px/18px;color:#FFF;font-weight:bold;font-size:13px;' 
		align='center' bgcolor='#005C9D' valign='top'> $subjectMail</td>
	</tr>
	<tr >
		<td style=\"font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;
	font-size:11px; padding:30px 27px;\">
	$bodyMail
	
	
	</td>
	</tr>
	<tr>
		<td style='padding:5px;font:Verdana, Arial, Helvetica, sans-serif 12px/18px;color:#FFF;font-weight:bold;font-size:13px;' 
	align='center' bgcolor='#005C9D' valign='top'><div id=footmail>$signature2</footmail></td>
	</tr>
 </table>  
 ";
if ($showresult==1) echo $bodyMail;

/*
Anda memperoleh email ini karena keanggotaan Anda pada ....
Harap jangan membalas email ini, karena email ini dikirimkan secara otomatis oleh sistem. 
*/
?>