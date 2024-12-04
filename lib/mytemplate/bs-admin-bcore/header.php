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
	   

		include $toroot."arrmenu.php";
		include $tppath."mnu-kiri-end.php";
	  ?>
	  </div>
	  <!--END MENU SECTION -->

        <!--PAGE CONTENT -->
        <div id="content">
		<div class='well well-small' id='content0'>
		<?php 
