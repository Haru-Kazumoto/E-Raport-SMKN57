<?php
//include_once("conf.php");

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$nfApp?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <?php
  include_once "script-css2.php";
  ?>

</head>
<body class="hold-transition skin-blue sidebar-mini">
  
  <header class="main-header">
    <!-- Logo -->
    <div class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"> </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?=$nfApp?></span>
    </div>
	<!-- Header Navbar: style can be found in header.less -->
<?php 
include_once $tppath."mnu-atas.php"; 
	
if (!isset($jamselesai)) $jamselesai="";

echo "<span id=tjamselesai style='display:none'>$jamselesai</span>
<span id=ttime style='display:none'></span>
";

?>
	
  </header>
  
<div class="wrapper">

  
  <!-- Left side column. contains the logo and sidebar -->
 
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='width:100%;margin-top:0px'>
   <?php 
   // include $nfc;//"content.php"; 
  include $nfc;
   
   ?>
  </div>
  <!-- /.content-wrapper -->
  
 
</div>
<!-- ./wrapper -->
<style>

</style>
<?php
//include_once "footer1.php";
/*
  <footer class="main-footer">
<?php include_once "footer.php"; ?>
  </footer>

  */
?>
</body>
</html>
