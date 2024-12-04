<div class="navbar-custom-menu">
<ul class="nav navbar-nav">
	 <!-- User Account: style can be found in dropdown.less -->
<li class='info-uid'><a><?php echo "User ID  : ".ucwords($userID); ?></a></li>
<li class='info-utp'><a><?php echo "User Type : ".ucwords($userType); //echo "-$ip-".$isIPAllowed."-"; ?></a></li>


<li class='info-unm'>
	<a href="#" data-toggle="control-sidebar"  >Hi <?=$userName?></a>
</li>

  <!-- Control Sidebar Toggle Button -->
  <li>
	<!--button href="#" data-toggle="control-sidebar" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" >
	<i class="fa fa-sign-out"></i> Keluar</button-->
	<button href="#"  onclick="location.href='<?=$tohost?>index.php?page=login&op=logout';" class='btn btn-danger btn-sm' style="margin:10px 10px 0 0" ><i class="fa fa-sign-out"></i> Keluar</button>
  </li>
</ul>
</div>

  