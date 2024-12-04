<?php
/*
uploader ckeditor
mr.um412@gmail.com
usage:
<script>

$(document).ready(function(){
	folderUpload='upload';
	url="<?=$toroot?>uploader-cke.php"; 
	CKEDITOR.replace('ta123', {
		filebrowserUploadUrl: url
	});
});

	
*/

if (!isset($pathUploadCKE)) $pathUploadCKE=$tohost."upload/cke/";
if (!isset($compressImage)) $compress=$compressImage=false;
if (!isset($maxImageSize)) $maxImageSize=200*1024;
//path upload auto replace:0 :cancel,1:replace,2:addrnd,
//prefict:
if (!isset($prefictNfUpload)) {
	$prefictNfUpload=$userType;
	if (isset($vidusr))	$prefictNfUpload.="-".$vidusr;
}

$nfasli=$_FILES["upload"]["name"];
$nf=substr($nfasli,0,17);
if (strlen($nfasli)>17) {
	$nf.=substr($nfasli,strlen($nfasli)-4,4);
}

$nf=str_replace(".",rand(123,898651).".",$nf);		
$nf=htmlentities($nf);
$nf=($prefictNfUpload==""?"":"$prefictNfUpload-").$nf;
$nfbody=substr($nf,0,strlen($nf)-4);//nama saja
$nftarget=$pathUploadCKE.$nf;

$h="";
buatFolder($pathUploadCKE);
//jika menggunakan upload biasa
/*
if (file_exists($nftarget)){
	$h='';
	$url='';
} else {
	move_uploaded_file($_FILES["upload"]["tmp_name"],$nftarget);
	$h=$nftarget;
	$url=$folderHost.$nftarget;
}
*/


$handle = new Upload($_FILES['upload']);
//$handle->image_convert         = 'png';
//$handle->png_compression       = 0;
	 
if ($compressImage) {
	$handle->image_convert         = 'jpg';
	$handle->jpeg_quality          = 80;
	if ($handle->file_src_size>$maxImageSize) {
		$handle->jpeg_size         = $maxImageSize;
	}
	//$handle->file_max_size=3072*10;
}
$msg="";
//$handle->file_dst_name=$nf;
//$handle->file_name_body_add=genRnd();
$handle->file_new_name_body=$nfbody;
if ($handle->uploaded) {
	
	// yes, the file is on the server
	// now, we start the upload 'process'. That is, to copy the uploaded file
	// from its temporary location to the wanted location
	// It could be something like $handle->process('/home/www/my_uploads/');
	$handle->process($pathUploadCKE);

	// we check if everything went OK
	if ($handle->processed) {
		$h=1;
		$nf=$handle->file_dst_name;
		//$url=$folderHost.$pathUploadCKE.$handle->file_dst_name; 	
		$url=$pathUploadCKE.$handle->file_dst_name; 	
		//$url=$folderHost.($handle->file_dst_name);
		// everything was fine !
		/*
		echo '<p class="result">';
		echo '  <b>File uploaded with success</b><br />';
		echo '  File: <a href="'.$dir_pics.'/' . $handle->file_dst_name . '">' . $handle->file_dst_name . '</a>';
		echo '   (' . round(filesize($handle->file_dst_pathname)/256)/4 . 'KB)';
		echo '</p>';
		*/
	} else {
		$h=0;
		$url='';
		// one error occured
		/*
		echo '<p class="result">';
		echo '  <b>File not uploaded to the wanted location</b><br />';
		echo '  Error: ' . $handle->error . '';
		echo '</p>';
		*/
		$msg=$handle->error;
	}

	// we delete the temporary files
	$handle-> clean();

} else {
	$h=0;
	$url='';
	$msg=$handle->error;
}


echo json_encode([
	'fileName' => $nf,     	    
	'uploaded' => $h,     	    
	'url' => $url,
 	    ]);
exit;
/*
echo json_encode([
	'fileName' => $nf,     	    
	'uploaded' => $h,     	    
	'url' => $url,
	'fnbody' => $nfbody,
	'msg' => $msg,
 	    ]);
*/