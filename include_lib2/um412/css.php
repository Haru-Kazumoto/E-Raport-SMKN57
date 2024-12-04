<?php
if (!isset($addCSS)) $addCSS="";
if ($cetak=='') {
	//$addInclude.=",$inc_path"."um412-jsplugins.php";  
}

//$addCSSAfter.=",$js_path"."style-cetak.css";

$addCSSAfter.=",$js_path"."um412-jsreport-v2.1.css?$addRJS";
$addCSSAfter.=",$js_path"."um412-color.css?$addRJS";
$addCSSAfter.=",$js_path"."um412-css-func-v2.1.css?$addRJS";  
$addCSSAfter.=",$template_path"."css/style.css";  
if ($isAdmin){
	$addCSSAfter.=",$template_path"."css/style2.css";  
}



if (strstr($sJSSrc,'jqui')!='') 
	$addCSS.=",$js_path"."jquery/ui/jquery-ui.css";
//$addCSS.=",$js_path"."jquery/themes/base/jquery.ui.all.css";
//$addCSS.=",$js_path"."jquery/themes/ui-lightness/jquery.ui.all.css";

$addCSS.=",$template_path"."css/main.css";
$addCSS.=",$toroot"."css/sl-slide.css";

if (strstr($sJSSrc,'jqdt')!='') {
	$addCSS.=",$js_path"."datatable/css/jquery.dataTables.min.css";	
	$addCSS.=",$js_path"."datatable/css/jquery.dataTables.add.css";	
	$addCSS.=",$js_path"."datatable/css/responsive.dataTables.min.css";	
	$addCSS.=",$js_path"."datatable/css/rowReorder.dataTables.min.css";		
}

if (strstr($sJSSrc,'jqvalidator')!='') 
	$addCSS.=",$js_path"."jquery/validator/validator-screen.css";		

if (strstr($sJSSrc,'dtpicker')!='') {
	$addCSS.=",$js_path"."jquery/plugins/dtpicker/jquery.datetimepicker.min.css";			
	$addCSS.=",$js_path"."jquery/plugins/daterangepicker/daterangepicker.css";			
}
//ztree
if (strstr($sJSSrc,'ztree')!='') 
	$addCSS.=",$js_path"."jquery/plugins/zTree/css/zTreeStyle/zTreeStyle.css";

	 
$addCSSFile=$template_path."css/style.css?$addRJS";

//$addCSS.=",$js_path"."um412-cssreg.css";  
if (strstr($sJSSrc,'magnific-popup')!='') 
	$addCSS.=",$js_path"."jquery/plugins/magnify-popup/jquery.magnific-popup.css";			

if ($lib_app_path!='') $addCSSAfter.=$lib_app_path."css/custom.css";

if ($isAdmPath){
	$addCSSAfter.=",$template_path"."css/style2.css?$addRJS";  
	$addCSSAfter.=",$adm_path"."css/custom.css?$addRJS";
} else {
	$addCSSAfter.=",$tohost"."css/custom.css?$addRJS";
	$addCSSAfter.=",$tohost"."content/page/css/custom-fp.css?$addRJS";
	//echo "hoooooooooooooooooooooooooooooooooooooooooooooooo $addCSSAfter";exit;
}

?>