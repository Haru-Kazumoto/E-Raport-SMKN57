<?php
$nfc=$tppath."beranda.php";
if (!isset($titleIdx)) $titleIdx=$nfApp;
//include_once $adm_path."usr-cek-local.php";
cekVar('contentOnly');

if ($det=='') {
	$det="beranda";
}

if ($det=="login") {
	//ceklogin
	
}
if (!recekLogin()) {	
	$nflogin=$adm_path."custom/login.php";
	if (!file_exists($nflogin))
		$nflogin=$tppath."login.php";
	include $nflogin;	
	exit;
}	


if  ($det!='') {
	$nfc=$adm_path."content1.php";
}
//echo "islogin $isLogin $det";exit;

if ($contentOnly==1) {
	include $nfc;
	exit;
}




cekvar("isDetail");
if ($isDetail==1) {
	echo " <div class='content-wrapperx' id='content-wrapperx'>";
	
	include $nfc;
	echo "</div>";
	include_once $tppath."script-css1.php";
    include_once $tppath."script-js2.php";
} else {

	?>
	<!DOCTYPE html>
	<html>
	<head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <title><?=$nfApp?> | Dashboard</title>
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <?php

	  include_once $tppath."script-css1.php";
	  ?>
	</head>
	<body class="hold-transition skin-<?=$tpStyle?> sidebar-mini">
	<div id='imgloader' style='display:none'></div>

	<div class="wrapper">

	  <header class="main-header">
			<!-- Logo -->
		<a href="index.php" class="logo">
		  <!-- mini logo for sidebar mini 50x50 pixels -->
		  <span class="logo-mini">
		  	

		  </span>
		  <!-- logo for regular state and mobile devices -->
		  <span class="logo-lg"><?php  echo "<img src='$nfLogo' style='height:30px'>";?> <?=$nfApp?></span>
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<?php 
		/*
	
		*/
		
		
	//		echo "  <a href='#' class='logoX' style='color:#fff;    height: 50px;font-size: 20px;line-height: 50px;' >$titleIdx</a>";
// ".strtoupper($nmDomain)."
			include_once $tppath."mnu-atas.php";
		
	 ?>
	  </header>
	  <!-- Left side column. contains the logo and sidebar -->
	  <aside class="main-sidebar" id='tmmenu'>
		<?php  
			include  $um_path."mnu-kiri-def.php";
			echo $htmlMenu;
		?>
	  </aside>
	  <!-- Content Wrapper. Contains page content -->
	  <div class="content-wrapper" id='content-wrapper'>
	   <?php 
	   include $nfc;//"content.php"; ?>
	  </div>
	  <!-- /.content-wrapper -->
	  <!--footer class="main-footer">
	<?php //include_once "footer.php"; ?>
	  </footer-->

	  <!-- Control Sidebar -->
	  <aside class="control-sidebar control-sidebar-dark"> 
	  <?php
	  $nf=$toroot."profile.php";
	  if (file_exists($nf)) include $nf;
	  ?>
	  
	  </aside>
	 
	  <div class="control-sidebar-bg"></div>
	</div>
	<!-- ./wrapper -->
	<?php
	include_once $tppath."script-js2.php";
	?>
	</body>
	</html>
	<?php
} //isdetail