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
$addJS.=",$js_path"."um412-jsreport-v2.1.js";
$addJS.=",$js_path"."jquery/jquery.maskMoney.js";
		echo "
		<script>
			jinput='$jinput';
			jPenilaian='$jPenilaian';
		</script>";
		//if ($userID=='siswa')
	//	$addJS.=",inc1/addjs-siswa.js";
		//else
		
		$addJS.=",$toroot"."inc1/addjs.js";
		$addJS.=",$toroot"."inc1/addjs-admin.js";
			
		$ajs=explode(",",$addJS);
		
		foreach($ajs as $jsx) {
			if ($jsx=='') continue;
			echo "\n<script src='$jsx'></script>";
		}
		include $js_path."um412-jsfunc-v2.1.php";
		
//echo "hohoiiiiiii";
		
?>
