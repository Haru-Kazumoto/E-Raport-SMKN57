<?php

$tc="text-align:center";
$rsp="rowspan=2";$csp="colspan=2";$u="%";

$sw=100;
	$w=array(4,10,10,10,10,10,50,50);
	$w=hitungSkala($w,100);
	$alc="align=center valign=top";
	$ai="";
	$ai.="<table width=$sw style='width:$sw"."%' border=0 style='margin-top:15px'>";
	$ai.="
	<tr class=tdjudul>
		<td $rsp style='width:$w[0]"."$u'>SEMESTER</td>
		<td $rsp style='width:$w[1]"."$u'>&nbsp;</td>
		<td $rsp style='width:$w[2]"."$u'>KOMPETENSI</td>
		<td $rsp style='width:$w[3]"."$u'>JLH KD<br>HINGGA UTS</td>
		<td $rsp style='width:$w[4]"."$u'>JLH KD<br>HINGGA UAS</td>
		<td $rsp style='width:$w[5]"."$u'>KB/KKM</td>
		<td $csp style='$tc'>DESKRIPSI</td>
	</tr>
	<tr class=tdjudul>
		<td style='width:$w[6]"."$u'>UMUM</td>
		<td style='width:$w[7]"."$u'>INKLUSI</td>
	</tr>";
	$ai.="<tr style='height:1px'><td colspan=5>&nbsp;</td></tr>";
	$aki=array("Pengetahuan","Keterampilan");
	
	for ($i=1;$i<=6;$i++) {
		if (($op=='tb')||($op=='ed')){
			$sField.=",kbp$i,jp$i"."a,jp$i,desp$i,desp2$i";
			$sField.=",kbk$i,jk$i"."a,jk$i,desk$i,desk2$i";
			
		}
		
		$ai.="<tr>";
		
		
		$ai.="<td rowspan=2 $alc ><b>$i</b></td>";
		$ai.="
		
			<td $alc>&nbsp;</td>
		<td $alc>Pengetahuan</td>
		<td $alc><input type=text name=jp$i"."a value='{jp$i"."a}' size=2></td>
		<td $alc><input type=text name=jp$i value='{jp$i}' size=2></td>
		<td $alc><input type=text name=kbp$i value='{kbp$i}' size=2></td>
		<td $alc>
			<textarea name=desp$i rows=6 cols=50 maxlength='$maxdes' >{desp$i}</textarea>
		</td>
		<td $alc>
			<textarea name=desp2$i rows=6 cols=50 maxlength='$maxdes' >{desp2$i}</textarea>
		</td>
		
		";
		$ai.="</tr>";
		$ai.="<tr>";
		
		$ai.="
		
		<td $alc>&nbsp;</td>
		<td  $alc>Keterampilan</td>
		<td $alc><input type=text name=jk$i"."a  value='{jk$i"."a}' size=2 ></td>
		<td $alc><input type=text name=jk$i  value='{jk$i}' size=2 ></td>
		<td  $alc ><input type=text name=kbk$i  value='{kbk$i}' size=2></td>
		<td $alc>
			<textarea name=desk$i rows=6 cols=50>{desk$i}</textarea>
		</td>
		<td $alc>
			<textarea name=desk2$i rows=6 cols=50>{desk2$i}</textarea>
		</td>
		";
	
		$ai.="</tr>";
		$ai.="<tr style='height:1px'><td colspan=7><hr></td></tr>";
	}
	$ai.="</table>
	";
	
?>