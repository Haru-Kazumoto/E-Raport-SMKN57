<?php
$nfx=$tppath."beranda.php";
cekVar("contentOnly,det");
switch($det) {
case "siswa";
	$nfx="input.php";
	break;

}
if ($contentOnly==1) {
	include $nfx;
	exit;
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8" />
    <title><?=$judulWeb?> | Dashboard </title>
     <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
     <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <!-- GLOBAL STYLES -->
   <?php
   
   include $tppath."css.php";
   ?>
    
    <!--END GLOBAL STYLES -->

    <!-- PAGE LEVEL STYLES -->
    <link href="<?=$tppath?>assets/css/layout2.css" rel="stylesheet" />
       <link href="<?=$tppath?>assets/plugins/flot/examples/examples.css" rel="stylesheet" />
       <link rel="stylesheet" href="<?=$tppath?>assets/plugins/timeline/timeline.css" />
    <!-- END PAGE LEVEL  STYLES -->
     <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

    <!-- END HEAD -->

    <!-- BEGIN BODY -->
<body class="padTop53 " >
<?php 
include_once "$toroot"."inc1/template-css.php";
    ?>
    <!-- MAIN WRAPPER -->
    <div id="wrap" >
        

        <!-- HEADER SECTION -->
      <?php 
	  include $tppath."mnu-atas.php"; 
	  ?>
        <!-- END HEADER SECTION -->
        <!-- MENU SECTION -->
      <div id="left" >
	  <?php 
	  //echo "isi menu kiri";
	   
	   $mnukiri=$tppath."mnu-kiri.php"; 
	   if (strstr("guru,kaprog",$userType)!='') {
		   $mnukiri=$tppath."mnu-kiri-guru.php"; 
		}
		elseif (strstr("siswa",$userType)!='') {
		   $mnukiri=$tppath."mnu-kiri-siswa.php"; 
		}
	  include $mnukiri;
	  ?>
	  </div>
	  <!--END MENU SECTION -->

        <!--PAGE CONTENT -->
        <div id="content">
		<?php 
			  include $nfx;

			  // include $tppath."content.php"; 
			  ?>
		</div>
        <!--END PAGE CONTENT -->

         <!-- RIGHT STRIP  SECTION -->
        <?php 
		/*
		if ($userType!='siswa') {
			echo '<div id="right">';
			include $tppath."mnu-right.php"; 
			echo '   </div>';
		}
		*/
     
	?>
         <!-- END RIGHT STRIP  SECTION -->
    </div>

    <!--END MAIN WRAPPER -->

    <!-- FOOTER -->
    <div id="footer">
        <p>Copyright &copy;  <?=$namaSekolah  ?> &nbsp;2016, Developed: <?=$developer?></p>
    </div>
    <!--END FOOTER -->



    <!-- PAGE LEVEL SCRIPTS -->
    <!--script src="<?=$tppath?>assets/plugins/flot/jquery.flot.js"></script>
    <script src="<?=$tppath?>assets/plugins/flot/jquery.flot.resize.js"></script>
    <script src="<?=$tppath?>assets/plugins/flot/jquery.flot.time.js"></script>
     <script  src="<?=$tppath?>assets/plugins/flot/jquery.flot.stack.js"></script>
    <script src="<?=$tppath?>assets/js/for_index.js"></script-->
   
    <!-- END PAGE LEVEL SCRIPTS -->


</body>

    <!-- END BODY -->
</html>
