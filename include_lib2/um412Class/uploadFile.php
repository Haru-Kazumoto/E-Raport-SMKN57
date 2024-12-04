<?php

class uploadFile {
	public $nmVar='uploaded';
	public $folderTarget='';
	public $typeFile='';
	public $maxFileSize=0;
	public $nmFileTarget='';
	public $showPes;
	public $overwrite=true;
	
	
	function upload() {
		//$nmvar='uploaded',$folderTarget="",$tipe="all",$maxfs=0,$nfonly=0,$nmfTarget='',$showPes=0,$overwrite=true){
		global $pes; //echo $nmfTarget;
		global $toroot;
		global $isTest;
		global $id;
		
		//echo "nf:".($_FILES[$nmvar]['name']);
		$pes="";
		
		$folderTarget=str_replace("\\","/",$folderTarget."/");=
		$folderTarget=str_replace("////","/",$folderTarget);
		if (isset($id)) {
			$folderTarget=str_replace("{id}",$id,$folderTarget);
		}
		//$folderTarget=$toroot.$folderTarget;
		
		if(!isset($_FILES[$nmvar]['name'])) {
			if ($isTest) echo "file $nmvar kosong ";
			return "";//echo "g ada file $nmvar diupload";		
		}
		if($_FILES[$nmvar]['error']) {		//File upload error encountered
			$pes=upload_errors($_FILES[$nmvar]['error']);
			if ($isTest) echo $pes;
			return "";
		}
		
		$FileName			= strtolower($_FILES[$nmvar]['name']); //uploaded file name
		$ImageExt			= substr($FileName, strrpos($FileName, '.')); //file extension
		$FileType=$tipe		= $_FILES[$nmvar]['type']; //file type
		$FileSize=$ukuran	= $_FILES[$nmvar]['size']; //file size
		$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
		$uploaded_date		= date("Y-m-d H:i:s");
		$tmpfile=$_FILES[$nmvar]["tmp_name"];
		$extasli = pathinfo($FileName, PATHINFO_EXTENSION);


		$nmfile=basename($_FILES[$nmvar]['name']);
		//khusus file gambar
		if ($tipe=='gambar') $tipe="jpg,gif,png,bmp";
		if ($tipe=='doc') $tipe="doc,xls,txt";

		$tpx="";
		switch(strtolower($FileType)) {		//allowed file types
			case 'application/x-javascript': //.js
				$pes='Unsupported File ('.strtolower($FileType) .') !';
				return "";//output error
				break;
			case 'image/png': //png file
				$tpx="png";
			case 'image/gif': //gif file 
				$tpx="gif";
			case 'image/jpeg': //jpeg file
				$tpx="jpg";
			case 'application/pdf': //PDF file
				$tpx="pdf";
			case 'application/msword': //ms word file
				$tpx="doc";
			case 'application/vnd.ms-excel': //ms excel file
				$tpx="xls";
			case 'application/octet-stream': //ZIP
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': //xlsx
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'://docx
		
			case 'application/x-zip-compressed': //zip file
				$tpx="zip";
			case 'text/plain': //text file
				$tpx="txt";
			case 'text/html': //html file
				$tpx="html";
				break;
			default:
				$pes.='File type :'.strtolower($FileType) .'<br>';
		}
		//$pes.=strtolower($FileType)."";
		if ($maxfs>0) { 
			if ($ukuran> $maxfs) { 
				$pes.="<br>File Size is not allowed, maxs file size: $maxfs ";
				//return "";
			}
		}
		
		
		if ($nmfTarget=='') {
			//$target = $folderTarget.$nmfil;
			$target = str_replace("'","~",$folderTarget.$nmfile);
		} else {
			$ext = pathinfo($nmfile, PATHINFO_EXTENSION);
			//$target = str_replace("'","~",$folderTarget.$nmfTarget.".$ext");	
			$target = str_replace("'","~",$folderTarget.$nmfTarget);	
			$nmfile=str_replace("'","~",$nmfTarget);	
			
		}
		
		$target = str_replace("#nmfile#",$nmfile,$target);	
		$target = str_replace("#nfasli#",$nmfile,$target);	
		$target = str_replace("#ext#",$extasli,$target);	
		$target = str_replace(".ext",".$extasli",$target);//mengubah extensi sesuai file yng diupoad
		$target = str_replace("//","/",$target);
		
		$ada=true;
		$idx=0;
		$dir = pathinfo($target, PATHINFO_DIRNAME);
		$nfonly = pathinfo($target, PATHINFO_FILENAME);
		$ext = pathinfo($target, PATHINFO_EXTENSION);
		$nmfile = pathinfo($target, PATHINFO_BASENAME);
		
		 //cekking file, jika ada apakah menindih atau mmbuat index	
		while ($ada ) {
			$targetlama=$target;
			$idx++;	
			if ($overwrite)
				$ada=false;//dianggap g ada, sehingga keluar dari while
			elseif (!file_exists($target)) {
				$ada=false;
			} else  {
				$ada=true;
				$nfonly=str_replace("[0]","",$nfonly);
				$target="$dir/$nfonly"."[$idx].$ext";				
			}			
			//echo "<br>$targetlama ".($ada?" sudah ada":"belum ada....");
		}
	//	echo "<br>selesai....";

		createFolder($dir);
		
		//echo "curr folder:".getcwd()."<br>";
		$pes.= "<br>Temporary:$tmpfile<br>Target:$target<br>";
		//echo $pes;
		move_uploaded_file($tmpfile,  $target);
		
		//if (move_uploaded_file($tmpfile,  $target)) {
		if (file_exists($target)) {
		//	$pes.="<br>$nmfile uploaded succesfully to ".getcwd()."/$target<br>";
			$pes.="<br>$nmfile uploaded succesfully to $target<br>";
			//echo $pes;
			return ($nfonly==0?$nmfile:$target);
		} else {
			$pes.="<br>Cannot upload $nmfile ... <br>tmp:$tmpfile<br>target:$target"; 
			echo $pes;
			return "";
		} //berhasil atau tidak
		if ($showpes==1||$isTest)  echo $pes;
		return "";
	}

	//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
	function upload_errors($err_code) {
		switch ($err_code) { 
			case UPLOAD_ERR_INI_SIZE: 
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
			case UPLOAD_ERR_FORM_SIZE: 
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
			case UPLOAD_ERR_PARTIAL: 
				return 'The uploaded file was only partially uploaded'; 
			case UPLOAD_ERR_NO_FILE: 
				return 'No file was uploaded'; 
			case UPLOAD_ERR_NO_TMP_DIR: 
				return 'Missing a temporary folder'; 
			case UPLOAD_ERR_CANT_WRITE: 
				return 'Failed to write file to disk'; 
			case UPLOAD_ERR_EXTENSION: 
				return 'File upload stopped by extension'; 
			default: 
				return 'Unknown upload error'; 
		} 
	} 
}
