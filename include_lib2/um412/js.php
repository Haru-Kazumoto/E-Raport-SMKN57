<?php
$useJS=2;

//echo "<script>try { a=document.getElementById('useJSX').value(); } catch (e) { alert('already loaded'); } < /script>";

/*
if (isset($jquery))
	$addJS=$jquery;
else
*/	
//$addJS.=",$js_path"."jquery/jquery.base64.js";//ajaxsubmit
//$addJS.=",$js_path"."jquery/tableExport.js";//ajaxsubmit 
//$addJS.=",$js_path"."jquery/interface.js";

if (strstr($sJSSrc,'jquery')!='') {
	$addJS.=",$js_path"."jquery/jquery.js"; 
	$addJS.=",$js_path"."jquery/jquery.axios.js"; 
	//if (strstr($sJSSrc,'jqui')!='') {
	$addJS.=",$js_path"."jquery/ui/jquery-ui.min.js";
	$addJS.=",$js_path"."jquery/ui/jquery.ui.combobox.js";
	$addJS.=",$js_path"."jquery/jquery.maskMoney.js?$addRJS";

//}

}

if (strstr($sJSSrc,'jqform')!='') 
	$addJS.=",$js_path"."jquery/jquery.form.js";//ajaxsubmit


if (strstr($sJSSrc,'jqvalidator')!='') {
	$addJS.=",$js_path"."jquery/plugins/validator/jquery.validate.js";
	$addJS.=",$js_path"."jquery/plugins/validator/jquery.validate.additional-methods.js";
}
if (strstr($sJSSrc,'jquery')!='') 	
	$addJS.=",$js_path"."jquery/jquery-migrate-1.2.1.min.js";
if (strstr($sJSSrc,'dtpicker')!='') {

	$addJS.=",$js_path"."jquery/plugins/dtpicker/jquery.datetimepicker.full.min.js";
	$addJS.=",$js_path"."jquery/plugins/daterangepicker/moment.min.js";
	$addJS.=",$js_path"."jquery/plugins/daterangepicker/daterangepicker.js";
	$addJS.=",$js_path"."jquery/ui/jquery.ui.datepicker-id.js";

}	

if (strstr($sJSSrc,'bootstrap5')!='') {
	$addJS.=",$js_path"."bootstrap5/assets/dist/js/bootstrap.bundle.min.js";
	$addCSS.=",$js_path"."bootstrap5/assets/dist/css/bootstrap.min.css";

}elseif (strstr($sJSSrc,'bootstrap')!='') {
	$addCSS.=",$js_path"."bootstrap/bootstrap-responsive.min.css";
	$addCSS.=",$toroot"."css/font-awesome.min.css";

}


if ((!$systemOnly) || $isAdmPath) {	
	if ($isAdmPath) {
		if (strstr($sJSSrc,'cke')!='') 
			$addJS.=",$js_path"."ckeditor/ckeditor.js";	
		
		if (strstr($sJSSrc,'jqdt')!='') {
			$addJS.=",$js_path"."datatable/js/jquery.dataTables.min.js";
			$addJS.=",$js_path"."datatable/js/dataTables.bootstrap4.min.js";
			$addJS.=",$js_path"."datatable/js/dataTables.buttons.min.js";
			$addJS.=",$js_path"."datatable/js/dataTables.select.min.js";
			$addJS.=",$js_path"."datatable/js/buttons.jqueryui.min.js";
			$addJS.=",$js_path"."datatable/js/buttons.print.min.js"; 
			$addJS.=",$js_path"."datatable/js/buttons.html5.min.js"; 
			$addJS.=",$js_path"."datatable/js/buttons.colVis.min.js"; 
			$addJS.=",$js_path"."datatable/js/dataTables.fixedColumns.min.js"; 
	//	$addJS.=",$js_path"."datatable/js/dataTables.rowReorder.min.js"; 
			//if (!isset($useDTResponsive))$useDTResponsive=true;
			//if ((strstr($sJSSrc,'jqdtresponsive')!='')||($useDTResponsive)) {
				$addJS.=",$js_path"."datatable/js/dataTables.responsive.min.js"; 
			//}
		}	
		
	}

	if (strstr($sJSSrc,'slimscroll')!='') 
		$addJS.=",$js_path"."jquery/jquery.slimscroll.js";
	if (strstr($sJSSrc,'ztree')!='') 
		$addJS.=",$js_path"."jquery/plugins/zTree/js/jquery.ztree.core.js";

	//$addJS.=",$js_path"."jquery/jquery.terbilang.js";
	//buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
	/*
	$addJS.=",$js_path"."pdfmake/pdfmake.min.js";
	$addJS.=",$js_path"."pdfmake/vfs_fonts.js"; 
	$addJS.=",$js_path"."jszip/jszip.min.js";
	$addJS.=",$js_path"."datatable/js/buttons.html5.min.js"; 
	*/
}

//if (strstr($sJSSrc,'maskmoney')!='') 
	$addJS.=",$js_path"."jquery/jquery.maskMoney.js&$addRJS";

if (strstr($sJSSrc,'magnific-popup')!='') {
	$addJS.=",$js_path"."jquery/plugins/magnific-popup/jquery.magnific-popup.js";
	$addCSSAfter.=",$js_path"."jquery/plugins/magnific-popup/jquery.magnific-popup2.css";
}
//$addJS.=",$js_path"."jquery/ui/jquery.ui.widget.js";
//$addJS.=",$js_path"."jquery/ui/jquery.ui.dialogOptions.js";

//if (!isset($useChart)) $useChart="chartjs";

if (strstr($sJSSrc,'chart')!='') {
//	echo "sjssrc".$sJSSrc;
	if (strstr($sJSSrc,'morris')!='') {
		$addJS.=",$js_path"."morris.js/raphael.min.js";
		$addJS.=",$js_path"."morris.js/morris.min.js";
		$addCSS.=",$js_path"."morris.js/morris.css"; 
	}
	if (strstr($sJSSrc,'chartjs')!='') {
		$addJS.=",$js_path"."chartjs/Chart.min.js";
		$addJS.=",$js_path"."chartjs/chartjs-plugin-labels.js";
	}
	
	//cetak grafike ke img
	$addJS.=",$js_path"."jquery/plugins/canvas/canvas2image.js";
	$addJS.=",$js_path"."jquery/plugins/canvas/html2canvas.min.js";
	
	//agar skala grafik tercetak
	$addScriptJS.="	
	var old;
	window.onbeforeprint = function() {
		$('canvas').each(function(instance){
			old = $(this).width();
			$(this).width('98vw');
		})
	}
	window.onafterprint = function(){
		$('canvas').each(function(instance){
			$(this).width(old);
		})
	}
	";
	  
}

//linenumber
//$addJS.=",$js_path"."jquery/plugins/lineline/jquery.lineline.js";

if (!isset($disableBackBtn)) $disableBackBtn=false;
if ($disableBackBtn) {
	$addScriptJS.="	
		//menghindari tombol back
		history.pushState(null, null, document.URL);
		window.addEventListener('popstate', function () {
			history.pushState(null, null, document.URL);
			/*
			//belum fungsi
			if (confirm('Yakin akan keluar')) {
				window.close();
			} else {
				return false;
			}
			*/
		});
	";
}

if (strstr($sJSSrc,'clipboard')!='') 
	$addJS.=",$js_path"."clipboard/clipboard.min.js";	

if (strstr($sJSSrc,'jqlightbox')!='') 
	$addJS.=",$js_path"."jquery/plugins/lightbox/jquery.lightbox-0.5.js";
//$addJS.=",$js_path"."accounting.js";

//cek tambahan script di template
if (isset($addJSMiddle)) {
	$addJSMiddle=str_replace('#jspath#',$js_path,$addJSMiddle);
	$addJS.=$addJSMiddle;
}


if ($isAdmPath) {
	$nf=$tppath."script-js.php";
	if (file_exists($nf)) include_once $nf;
	
	if ($adm_path!=$toroot) {
		$addJS.=",$adm_path"."js/custom.js&$addRJS"; 
	}
}

if (strstr($sJSSrc,'jsevent')!='') 
	$addJSAfter.=",$js_path"."um412-jsevent.js?$addRJS";

if ($lib_app_path!='') $addJS.=",$lib_app_path"."js/custom.js?$addRJS";

$addJS.=",$toroot"."js/custom.js?$addRJS";
if ($isAdmPath) {

} else{
	$addJSAfter.=",$template_path2"."js/custom.js?$addRJS";
	$addJSAfter.=",$toroot"."content/page/js/custom-fp.js?$addRJS";
}

//echo "-> $addJS";
