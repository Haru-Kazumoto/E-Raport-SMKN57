<?php
//echo "cekking $form"; 

cekVar("det,form,op2");
/*uploader*/
if ($form=='cke') {
	include_once $um_path."uploader-cke.php";
	exit;
}



setVar("opcek",1);
if ($det=='') $det=$form;



$aPath= array(
	$adm_path."protected/model/",
	$lib_app_path."protected/model/",
	$toroot
);
$nf1=$aPath[0]."input-$det.php";
$nf2=$aPath[1]."input-$det.php";
$nf3=$aPath[2]."input-$det.php";

if (file_exists($nf1)) {
	$nf= $nf1;	
}elseif (file_exists($nf2)) {
	$nf= $nf2;	
}elseif (file_exists($nf3)) {
	$nf= $nf3;	
} else $nf="";  


if ($nf!="") include $nf;	

//tambahan validasi local 
$nf3=$toroot."input-$det.php";


	
?>