	<table class=tbform2 align=center border=0 cellspacing="0" >
      <tr><td colspan=3 class=tdspaceform1>  </td></tr>
	  	<tr class=troddform2 ><td width="120" >subyek</td>
			<td width='5' >:</td><td valign=top> 
		 <input type=text name=subyek id=subyek size=60 value='<?=$subyek?>'>
         <input type="checkbox" value="1" name=pecah id=pecah  checked="checked" />
Private Email </td></tr>
			<tr class=trevenform2  ><td>kepada</td>
			<td>:</td><td valign=top><textarea name=kepada id=kepada cols=60 style="width:99%"  rows=2><?=$kepada?>
			</textarea>
  <br><a href='#' onclick="isiAlamatEmail('kepada')" >isi alamat</a></td></tr>
			<tr class=troddform2 ><td>cc</td>
			<td>:</td><td valign=top><input type=text name=cc id=cc size=40 rows=1 value='<?=$cc?>' > 
            bcc: <input type=text name=bcc id=bcc size=40 rows=1 value='<?=$bcc?>' ></td></tr>
			<tr class=trevenform2  ><td>Reply to</td>
			<td>:</td><td valign=top><input type=text name=replyto id=replyto size=60 rows=1 value='<?=$replyto?>'></td></tr>
			<tr class=troddform2 ><td colspan=3>isi:<?php $nme="editormail".rand(1111,3333); ?>
			<br><textarea name=isi  cols=90  style="width:99%" rows=9 id="<?=$nme?>"  onfocus=gantiEditor('<?=$nme?>') ><?=$isi?></textarea ></td></tr>
		 	<tr class=trevenform2  ><td>ket</td>
			<td>:</td><td valign=top> 
		 <input type=text name=ket id=ket size=50 value='<?=$ket?>'></td></tr>
 	 <tr class=troddform2 > <td colspan=3 align=center>
	 </td></tr>
     <tr><td colspan=3 valign=middle class=tdspaceform2>  </td></tr>
	 <tr><td colspan=3 valign=middle class=tdfooterform1>  </td></tr>
	</table>
<input type=hidden name=id value='<?=$id?>'>
	<input type=hidden value='<?=$opr?>' name=op>
	 