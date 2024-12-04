  <header class="main-header">
    <!-- Logo -->
    <div class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"> 
	  <?php
	  echo "<img src='$nfLogo' >";
	  ?>
	  
	  </span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?=$nfApp?></span>
    </div>
	<!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
	  <a href="#"  class=" " style="font-size: 20px;
line-height: 1.42857143;font-weight:bold;
color: #fff;line-height: 48px;">
<?=$defNmSekolah?> 
      </a>
	  
    <?php include_once $tppath."mnu-atas.php" ?>
	</nav>
  </header>