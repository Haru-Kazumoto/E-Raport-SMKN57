<?php
if (!isset($isLogin)) $isLogin=false;
if (!isset($docroot)) $docroot='';

$addHeaderAfter.="
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

//echo "smpai....".$addHeaderAfter;
$addJSAfter.=",$js_path"."js-browserdetect.js";
$addJSAfter.=",$js_path"."um412-jsfunc-v2.1.js?$addRJS";
$addJSAfter.=",$js_path"."um412-jsfunc-v2.1-add.js?$addRJS";
$addJSAfter.=",$js_path"."um412-jsfunc-tgl.js?$addRJS";
$addJSAfter.=",$js_path"."um412-jsreport-v2.1.js?$addRJS";

$aWarnaBack=array(
		array("lightgreen","#2AD829"),
		array("red","#1AB4BE"),
		array("blueegg","#CD3D22"),
		array("gray","#9D9797"),
		array("blueegg2","#00C0EF"),
		array("green","#B2CD20"),
		array("lightred","#ED1B23")
);

$addHeaderAfter.="<style>";

foreach ($aWarnaBack as $wrn) {
	$addHeaderAfter.="
	.cl"."$wrn[0] { background:$wrn[1]; }
	";
}
$addHeaderAfter.="</style>";
