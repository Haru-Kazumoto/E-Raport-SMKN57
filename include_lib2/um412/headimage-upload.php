<?

 
$nfAction=$toroot."adm/index.php?rep=headimage";
$targetPath = $toroot."images/";
$targetIdDOM="tsupload".rand();//target refresh
//$webFolder="http://".$_SERVER['HTTP_HOST']."/".$docroot.'soal/uploads/media/';
$showFolderContent=true;
$sJenisMedia="profile,lain";
if (!isset($sJenisMedia)) $sJenisMedia="img";
$useJS=2;
ini_set("upload_max_filesize","30M");
ini_set('max_execution_time', 60*20);
cekVar("op,contentOnly,addParam1,addParam2");
if($_SESSION['usrid']=='Guest') die('sorry....');
//konfigurasi
;


echo "
<div id='$targetIdDOM' >
Header Image: 
Recomended : 700px x 100px
<img src='$toroot"."images/kop2.gif?i=".rand()."'>
</div>
";
$path=$targetPath;
if ($op=='remove') {
	$fileName =$nf= $_REQUEST['nf'];
	$filePath = $path.$fileName;
	if ( file_exists($filePath) ) {
		unlink($filePath);
	}
	//mysql_query2("delete from tbalbumdet where id='$idad' ");
	//exit;
} elseif ($op=='upload') {
	//turn on php error reporting

	ini_set('display_errors', 1);
	
	if ($_SERVER['REQUEST_METHOD'] != 'POST') die("no data post....");
	
	$name     = $_FILES['nfmedia']['name'];
	$tmpName  = $_FILES['nfmedia']['tmp_name'];
	$error    = $_FILES['nfmedia']['error'];
	$size     = $_FILES['nfmedia']['size'];
	$ext	  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
	$infoFile=pathinfo($name);
  	$nameOnly	  =$infoFile["filename"];
	
	$response="";
	switch ($error) {
		case UPLOAD_ERR_OK:
			$valid = true;
			//validate file extensions
			/*
			if ( !in_array($ext, array('jpg','jpeg','png','gif')) ) {
				$valid = false;
				$response = 'Invalid file extension.';
			}
			*/
			//validate file size
			if ( $size/1024/1024 > 30 ) {
				$valid = false;
				$response = 'File size is exceeding maximum allowed size. (max:30mb) ';
			}
			//upload file
			if ($valid) {
				
				$nfbaru=$targetPath."kop2.gif";
				$nfasli=$name;
				$deskripsi="";
				if (file_exists($nfbaru)) unlink($nfbaru );
				move_uploaded_file($tmpName,$nfbaru); 
				echo "<br>$tmpName,$nfbaru";
				echo "<br>Upload File  $nfbaru successfully....";
			}
			break;
		case UPLOAD_ERR_INI_SIZE:
			$response = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
			break;
		case UPLOAD_ERR_PARTIAL:
			$response = 'The uploaded file was only partially uploaded.';
			break;
		case UPLOAD_ERR_NO_FILE:
			$response = 'No file was uploaded.';
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			$response = 'Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.';
			break;
		case UPLOAD_ERR_CANT_WRITE:
			$response = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
			break;
		default:
			$response = 'Unknown error';
		break;
	}
	echo $response;	
}

?>

<style>
.thumbnail {
	float:left;
	width:160px;
	height:200px;
	padding:5px;
	margin:5px;
}
.thumbnail img {
	width:150px;
	height:150px;
}
.caption {
	text-align:center;
	}
#tfupload {
	margin-top:20px;
	}
</style>
<? 

if (!isset($rnd)) $rnd=rand(12371,54351);
$idForm="fupl_$rnd";//,'selesaiEdit($rnd)',false
$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','$targetIdDOM','','',false);return false;\" ";
$t="";
//$t.="<div id='tdet_$rnd' style='display:none'>$det</div>";
$t.="
<form id='$idForm' action='$nfAction&op=upload' method=Post  $asf enctype='multipart/form-data'>
  <div class='form-group'>
  <table width='100%' border='0'>
	  <tr><td>File</td><td>: <input type='file' name='nfmedia'><input type='submit' class='btn btn-lg btn-primary' value='Upload'></td></tr> 
</table>
</form>
";
//	<p class='help-block'>Hanya file bertipe jpg,jpeg,png dan gif dengan ukuran maksimal 1mb yang bisa di upload.</p>
if ($op!='') $t='';
echo "<div id=tfupload >$t</div>";
echo "";


?>
