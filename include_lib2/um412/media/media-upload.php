<?php
@session_start();
/*
$torootMedia="../../";
$linkRefresh="media-upload.php?ref=media&contentOnly=1";//refresh halaman setelah upload selesai....
$nfAction="media-upload.php?ref=media&contentOnly=1";
$targetPath = "../uploads/media/";
$targetIdDOM="tsupload";//target refresh
$webFolder="http://".$_SERVER['HTTP_HOST']."/".$docroot.'soal/uploads/media/';
$showFolderContent=true;
$sJenisMedia="profile,lain";
*/

if (!isset($sFolderList)) $sFolderList=$targetPath;
if (!isset($ukuranIconW)) $ukuranIconW=160;
if (!isset($ukuranIconH)) $ukuranIconH=200;

if (!isset($ukuranIcon)) $ukuranIcon=160;
if (!isset($useJudulMedia)) $useJudulMedia=false;
if (!isset($useDeskripsiMedia)) $useDeskripsiMedia=false;
if (!isset($useJenisMedia)) $useJenisMedia=false;
if (!isset($inputMediaToDb)) $inputMediaToDb=false;
if (!isset($useAjax)) $useAjax=true;
	
$paddingIcon=10;

$aFolderList=explode(",",$sFolderList);
	
if (!isset($sJenisMedia)) $sJenisMedia="img";
if (!isset($jenis)) $jenis="img";
$useJS=2;
ini_set("upload_max_filesize","30M");
ini_set('max_execution_time', 60*20);
//include_once $torootMedia."conf2.php";
//cekVar("op,contentOnly,addParam1,addParam2");

if (!isset($showFolderContent)) $showFolderContent=true;

//if($_SESSION['usrid']=='Guest') die('sorry....');
//konfigurasi



$path=$targetPath;
if (!is_dir($path)) {
	buatFolder($path,'0777',"");
	//mkdir($path,0777);
	//trigger_error('MY ERROR');
	echo "creating folder $path<br>";
//	exit;
}


$op='';
if (isset($_REQUEST['op'])) $op=$_REQUEST['op'];



if ($op=='remove') {
	$nomorfolder=$_REQUEST["nomorfolder"];
	$path=$aFolderList[$nomorfolder];
	$fileName =$nf= $_REQUEST['nf'];
	$filePath = $path.$fileName;
	if ( file_exists($filePath) ) {
		unlink($filePath);
	}
	//mysql_query2("delete from tbalbumdet where id='$idad' ");
	//exit;
} elseif ($op=='upload') {
	//turn on php error reporting
	$pesUpload="";
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
				//$targetPath =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR. 'uploads' . DIRECTORY_SEPARATOR. $name;
				//$idad=carifield("select max(id) from tbalbumdet ")*1+1;
				//$idadx=substr("00000000".$idad,strlen("00000000".$idad)-9,8);
				//$nfbaru=$name;
				$nfbaru=date('YmdHis');
				if ($useJenisMedia) $nfbaru.=$idalbum."_".str_replace($jenis," ","_")."_";
				$nfbaru.=$nameOnly."_".rand(1111,9999).".".$ext;
				$nfbaru=urldecode(strtolower($nfbaru));
				$nfasli=$name;
				$deskripsi="";
				if ($inputMediaToDb) {			
					mysql_query2("insert into tbmedia(nfmedia,judul,deskripsi,nfasli,jenis $addParam1) 
								values('$nfbaru','$judul','$deskripsi','$nfasli','$jenis' $addParam2)");
				}
				
				move_uploaded_file($tmpName,$targetPath.$nfbaru); 
				//header( "Location: $thisFile" ) ;
				//exit;
				$pesUpload= "Upload File $targetPath"."$nfbaru berhasil....";
				echo $pesUpload;
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


$t="";

//	<p class='help-block'>Hanya file bertipe jpg,jpeg,png dan gif dengan ukuran maksimal 1mb yang bisa di upload.</p>
//if ($op=='media') $op='';
if ($op!='') $t='';
if (!isset($isi)) $isi='';
if (!isset($showFormUpload)) $showFormUpload=true;
if (!isset($rnd)) $rnd=rand(12371,54351);

$isi.="
<style>
.thumbnail {
	float:left;
	width:$ukuranIconW"."px;
	height:$ukuranIconH"."px;
	padding:5px;
	margin:5px;
}
.thumbnail img {
	width:".($ukuranIconW-10)."px;
	height:".($ukuranIconW-10)."px;
}
.caption {
	text-align:center;
	}
#tfupload {
	margin-top:20px;
	}

.preview2 {
	float:left;
	margin:5px;
	width: $ukuranIcon"."px;
	height: $ukuranIcon"."px;
	overflow: hidden;
	border: 1px solid #DEDEDE;
	padding: 10px
}
.preview2 img {
	padding:0px;
	}


.previewimg, 
.previewmovie {
	width:".($ukuranIcon-$paddingIcon)."px;
	}
</style> 

";
$showResult=false;
//	$dir=$targetPath;
$ix=0;
foreach ( $aFolderList as $dir) {
	$targetPath=$dir;
	$t='';
	if ($op=='')
	if ($showFormUpload) {
		$nomorfolder=$ix; 
		$idForm="fupl_$rnd"."_$nomorfolder";//,'selesaiEdit($rnd)',false
		$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','$targetIdDOM','','',false);return false;\" ";
		$t.="
		<form id='$idForm' 
		action='$nfAction&op=upload&targetIdDOM=$targetIdDOM' 
		method=Post  $asf enctype='multipart/form-data'>
		  <div class='form-group'>
		  <table width='100%' border='0'>";

		if ($inputMediaToDb) {
			$t.="
			  <tr><td>File</td><td>: <input type='file' name='nfmedia'></td></tr>";
			if ($useJudulMedia) $t.="<tr><td>Judul</td><td>:  <input type='text' name='judul' size=60></td></tr>";
			if ($useDeskripsiMedia) $t.="<tr><td>Deskripsi</td><td>: <textarea name='deskripsi' cols=60 rows=3 ></textarea></td></tr>";
			//$t.="<tr><td>Jenis File Media</td><td>: <input type='hidden' name='jmedia' size=60 value='gambar'>Gambar</td></tr>";
			if ($useJenisMedia) $t.="<tr><td>Jenis Media</td><td>: ".um412_isicombo5($sJenisMedia,'jenis')."</td></tr>";
			$t.="<tr><td>Folder Penyimpanan</td><td>:  $targetPath <input type='hidden' name='pathmedia' size=60 value='$targetPath'></td></tr>";
			$t.="<tr>
				<td>&nbsp;</td>
				<td> <input type='submit' class='btn btn-sm btn-primary' value='Upload'></td>
			  </tr>";
		} else {
				$t.="
			  <tr><td>File : <input type='file' name='nfmedia'> <input type='submit' class='btn btn-sm btn-primary' value='Upload'></td></tr>";

		}

		$t.="	  
		</table>
		</form>
		";
		

	$isi.=tpBoxInput("<div id=tfupload_$nomorfolder >$t</div>","Upload Media");
	}
	$isi.="<div id='$targetIdDOM' >";

	if ($showFolderContent) {
		$showResult=false;
		include "media-list.php";

		$isi.="
		<div class=row style='margin:10px'>
			<div class='panel panel-default panel-small'>
				<div class='panel-heading'>Folder : $dir </div>
				<div class='panel-body' style='max-height:250px;overflow:auto'>
				$isiml
				</div>
			</div>
		</div>";
		
	}
	$isi.="</div>";
	$ix++;	
}
echo $isi;

?>