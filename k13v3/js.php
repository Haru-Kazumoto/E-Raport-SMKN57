<?php

echo "\n<script type='text/javascript' src='$js_path"."jquery/jquery2.js'></script>";
echo "\n<script type='text/javascript' src='$js_path"."ckeditor/ckeditor.js'></script>"; 
echo "\n<script type='text/javascript' src='$js_path"."jquery/ui/jquery-ui.js'></script>";
echo "<script src='$tppath"."assets/plugins/switch/static/js/bootstrap-switch.min.js'></script>";
echo "\n<script type='text/javascript' src='$js_path"."jquery/jquery.form.js'></script>";
	
?>
<script src="<?=$tppath?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=$tppath?>assets/plugins/modernizr-2.6.2-respond-1.1.0.min.js"></script>

<script type='text/javascript' src='<?=$js_path?>jquery/ui/jquery.ui.datepicker-id.js'></script>
	<?php
	$addJS="";
/*	
	
	$addJS.=",$js_path"."jquery/jquery.js"; 
	$addJS.=",$js_path"."jquery/jquery-migrate-1.2.1.min.js";
	$addJS.=",$js_path"."jquery/jquery.form.js";//ajaxsubmit
	//$addJS.=",js/custom.js";
	//$addJS.=",$js_path"."ckeditor/ckeditor.js";	
	$addJS.=",$js_path"."jquery/ui/jquery-ui.min.js";
	//$addJS.=",$js_path"."jquery/ui/jquery-ui.js";
	$addJS.=",$js_path"."jquery/ui/jquery.ui.widget.js";
	
*/		
		echo "
		<script>
			jinput='$jinput';
			jPenilaian='$jPenilaian';
		</script>
		";
		echo "
<script>
var js_path='$js_path';
var docroot='$docroot';
var sw=window.screen.width-40;
var sh=window.screen.height-200;
var isOnline=".($isOnline?'true':'false').";
var isad=".(usertype('admin')?'true':'false').";
var useDecimal=".(isset($useDecimal)?$useDecimal*1:1).";
var decimalSeparator='$decimalSeparator';
var thousandSeparator='$thousandSeparator';
var wDT=".(isset($wDT)?$wDT:200).";
var formatTgl='".($formatTgl=='d/m/Y'?'dd/mm/yy':'yy/mm/dd')."';
var isL='$isLogin';
var utpL='".strtolower($userType)."';
var wDD=sw-wDT;
var tppath='".$tppath."';
var timeoutSesi=$timeoutSesi;
var urlLogout='$urlLogout';
var umjsVer='$umjsVer';

//var rnd=parseInt(Math.random(12809)*28983);

$addScriptJS
</script>";

//if ($userID=='siswa')
//	$addJS.=",inc1/addjs-siswa.js";
//else

$addJS.=",$js_path"."js-browserdetect.js?$addRJS";
$addJS.=",$js_path"."um412-jsfunc-v2.1.js?$addRJS";
$addJS.=",$js_path"."um412-jsfunc-v2.1-add.js?$addRJS";
$addJS.=",$js_path"."um412-jsfunc-tgl.js?$addRJS";		
$addJS.=",$toroot"."inc1/addjs-admin.js";
$addJS.=",$js_path"."um412-jsreport-v2.1.js";
$addJS.=",$js_path"."jquery/jquery.maskMoney.js";
	
$ajs=explode(",",$addJS);

foreach($ajs as $jsx) {
	if ($jsx=='') continue;
	echo "\n<script src='$jsx'></script>";
}
//include $um_path."um412-jsfunc-v2.1.php";

?>
