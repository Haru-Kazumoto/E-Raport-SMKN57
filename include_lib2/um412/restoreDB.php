<?php
//$mysqli = new mysqli('localhost', 'hobodave', 'p4ssw3rd', 'test');
//$nfrestore="test.sql.zip";
//jika file zip,maka diextract dulu

	$idForm="rst".rand(1211,22221);
	$ntkn=makeToken("op=restore");
	$asf="";
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','restore',false);return false;\" ";
	$frm="
	<div>
	<div style='margin:5px;padding:10px; '><h2>Restore Database</h2>
	<form action='".$toroot."content1.php?det=$det&useJS=2&op=upload' enctype='multipart/form-data' method=post $asf id=$idForm >
	<table>
	<tr><td>File  </td><td> : <input type=file  name=nfmedia  id=nfrestore value=''  ></td>
	<td><input type=submit value='Submit' class='btn btn-success btn-sm btn-block' style='margin:.5px 5px 0px 10px'></td>
	</tr>
	</table>
	</form>
	<div id=ts$idForm></div>
	</div>
	</div>"; 

if ($op!='upload') {
	echo $frm;	
} else {
	//echo $frm;
	if  (!isset($varnf)) $varnf="nfmedia";
	
	ini_set('max_execution_time', 0);
	$mem=ini_get('memory_limit');
	$mem=str_replace("M","",$mem)*1;
	
	//jika file restore bukan dari input, tapi ditentukan
	if (isset($nfbuatdb)) {
		$nfrestore    =$nfbuatdb;
	} else {
		$nfrestore    = $_FILES[$varnf]['name'];
		$file=$_FILES[$varnf]['tmp_name'];
	}
	
	if (substr($nfrestore,-3,3)=='zip') {
		//echo " tmp ".$file;
		$dir=pathinfo($file,PATHINFO_DIRNAME);
		$zip = new ZipArchive;
		if ($zip->open($file) === TRUE) {
			$zip->extractTo($dir);
			$zip->close();
		}
		$nfrestore=$dir."/".substr($nfrestore,0,strlen($nfrestore)-4);
		if (!file_exists($nfrestore)){
			$nfrestore=substr($nfrestore,0,strlen($nfrestore)-4);	
		}
	}
	$s=	$t="";
	$t.="<br>Restoring database <br>nfrestore : $nfrestore";
	$aline = file($nfrestore);
	$strSql="";
	$i=0;
	$strE="";
	foreach($aline as $line) {
		$line=trim($line);
		//$t.="<br>line $i $line ";
	   $skip=false;
	   //jika diawali dengan -- atau kosong maka skip
	   if (substr($line,0,2)=='--') $skip=true;
	   if ($line=='') $skip=true;
	   $s.=$line;
	   //$t.="".substr($line,strlen($line)-1,1);
	   if (substr($line,-1,1)==';') {
		   $strSql.=$s;
		   //$t.="\n".$s;
		   $h=mysql_query2($s); 
		   if (!$h) $strE.=$s;
		   $s='';
	   }
	   $i++;
	}
	//$t.="$strSql";
	if ($strE!='') {
		$t="<textarea cols=120 rows=10>";
		$t.="Error: ". mysql_error()."<br> $strE";
		$t.="</textarea>";
	} else {
		$t.="
		<br>Database berhasil dibuat/direstore....
		<br><a href=index.php>Klik di sini </a> untuk refresh halaman";
	}
	echo $t;
}
?>