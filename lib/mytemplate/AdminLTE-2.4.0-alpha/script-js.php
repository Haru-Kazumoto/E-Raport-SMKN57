<?php
if (!isset($addJSAfter)) $addJSAfter='';

//$addJSAfter.=",#jspath#jquery/ui/jquery-ui.min.js";
$addJSAfter.=",$tppath"."plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js";
//$addJSAfter.=",$tppath"."bootstrap/js/bootstrap.js";
$addJSAfter.=",$tppath"."dist/js/adminlte.js";
$addJSAfter.=",$tppath"."dist/js/pages/dashboard.js";
$addJSAfter.=",$tppath"."dist/js/custom.js";
//$addJSAfter.=",$tppath"."dist/js/app.js";
//$addJSAfter=",$tppath"."plugins/slimScroll/jquery.slimscroll.min.js";
$addJSAfter.=",$tppath"."dist/js/demo.js?rj=123";

//$addJSAfter.=",$toroot"."js/script-chat.js";

?>