<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
	<span class="sr-only">Toggle navigation</span>	
  </a>
  <?php
  if (!isset($titleIdx )) $titleIdx ='';
  if ($debugMode) $titleIdx.="[dm]"; 
  echo "  <a href='#' class='logoX' style='color:#fff;    height: 50px;font-size: 20px;line-height: 50px;' >$titleIdx</a>";
?>
<div class="navbar-custom-menu">
	<ul class="nav navbar-nav">
		 <!-- User Account: style can be found in dropdown.less -->
	<?php
	if (!isset($showNotif)) $showNotif=false;
	$showNotif=true;
	if ($showNotif) {
		if (usertype("guru,siswa")) {
			$isiNotif='';
			$idNotif=1;
			
			echo "
				<li class='dropdown notifications-menu' onclick=\"$('#mnotif_$idNotif').toggleClass('open');return false;\" id=mnotif_$idNotif>
					<a href='#' class='dropdown-toggle' 
					data-toggle='dropdown' aria-expanded='true' 
					id='tnotif_$idNotif'>
					  <i class='fa fa-bell-o'></i>
					  <span class='label label-danger' id='jlhnotif_$idNotif'></span>
					</a>
					
					<ul class='dropdown-menu'>
					  <li class='header'>Pesan Masuk</li>
					  <li>
						<ul class='menu' id='isinotif_$idNotif'>
							<li>$isiNotif</li>
						</ul>
					  </li>
					  <!--li class='footer'><a href='#'>View all</a></li-->
					</ul>
			</li>
			";
			
			/*
			$isiNotif='
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
				  </li>';
				  */
		}
	}
	?>
		<li class='dropdown mnuatas2'>
			<a href="#" data-toggle="control-sidebar"  >Hi <?=explode(" ",$userName)[0]?> </a>
		</li>
	  <!-- Control Sidebar Toggle Button -->
	  <li>
		<!--button href="#" data-toggle="control-sidebar" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" >
		<i class="fa fa-sign-out"></i> Keluar</button-->
		
		<!--button href="#"  onclick="location.href='<?=$adm_path?>index.php?op=logout';" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" ><i class="fa fa-sign-out"></i> Keluar</button-->
	  
		<button href="#"  onclick="logoutUser2();" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" ><i class="fa fa-sign-out"></i> Keluar</button>
	  </li>
	</ul>
</div>
</nav>