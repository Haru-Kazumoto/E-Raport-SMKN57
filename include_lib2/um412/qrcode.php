<?php    
	if (!isset($kode)) $kode="1234abcd";
    if (!isset($lib_path)) $lib_path="../";
	//if (!isset($temp_path)) $temp_path=dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
	if (!isset($temp_path)) $temp_path='temp'.DIRECTORY_SEPARATOR;
	//set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR =$temp_path;// dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = str_replace(DIRECTORY_SEPARATOR,"/",$PNG_TEMP_DIR); 
    include_once $lib_path."phpqrcode/qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))    mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'test.png';
    $errorCorrectionLevel = 'L';
    $matrixPointSize = 4;
    
    
        //it's very important!
        if (trim($kode) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        if (!isset($nfQRCode)) $nfQRCode = $PNG_TEMP_DIR."_".date("ymd_his")."_".rand(123,312121).'.png';
        QRcode::png($kode, $nfQRCode, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    
    //display generated file
    //echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
     
    $nfQR=$PNG_WEB_DIR.basename($nfQRCode);