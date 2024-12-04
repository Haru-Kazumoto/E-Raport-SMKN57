<?php
if (!isset($userName)) {
	//$userName="test";
	//exit;
}

?>

	<nav class="navbar navbar-static-top">
		  <!-- Sidebar toggle button-->
		  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>	
		  </a>
<div class="navbar-custom-menu">
<ul class="nav navbar-nav">
	 <!-- User Account: style can be found in dropdown.less -->
<?php
if (usertype("guru,siswa")) {
	
	$isinotif="";
	/*
	<li>
						<a href="#">
						  <i class="fa fa-users text-aqua"></i> 5 new members joined today
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
						  page and may cause design problems
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-users text-red"></i> 5 new members joined
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-shopping-cart text-green"></i> 25 sales made
						</a>
					  </li>
					  <li>
						<a href="#">
						  <i class="fa fa-user text-red"></i> You changed your username
						</a>
					  </li>
				
						

		*/
	
	
	echo '
	<li class="dropdown notifications-menu">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" id="tnotif_1">
		  <i class="fa fa-bell-o"></i>
		  <span class="label label-danger" id="jlhnotif_1"></span>
		</a>
		<ul class="dropdown-menu">
		  <!--li class="header">You have 10 notifications</li-->
		  <li>
			<!-- inner menu: contains the actual data -->
			<ul class="menu" id=isinotif_1>
			'.$isinotif.'			
			</ul>
		  </li>
		  <!--li class="footer"><a href="#">View all</a></li-->
		</ul>
	</li>';
}
?>
	<li>
		<a href="#" data-toggle="control-sidebar"  >Hi <?=$userName?> </a>
	</li>
  <!-- Control Sidebar Toggle Button -->
  <li>
	<!--button href="#" data-toggle="control-sidebar" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" >
	<i class="fa fa-sign-out"></i> Keluar</button-->
	<button href="#"  onclick="location.href='<?=$adm_path?>index.php?op=logout';" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" ><i class="fa fa-sign-out"></i> Keluar</button>
  </li>
</ul>
</div>
</nav>