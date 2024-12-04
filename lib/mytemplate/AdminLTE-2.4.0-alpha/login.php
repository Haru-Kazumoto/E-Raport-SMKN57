<?php
cekvar("uid,nfLogo");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$nfApp?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?=$tppath?>bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=$js_path?>bootstrap/font-awesome.min.css">
  <!-- Ionicons -->
  <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"-->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$tppath?>dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?=$tppath?>dist/css/custom.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=$tppath?>plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
<style>
body {
/* background: url("<?=$tppath?>dist/img/boxed-bg.jpg") no-repeat center center fixed #ccc; */
	background: #ccc;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
  
}

.login-box {
	position: absolute;
	top:0px;
	bottom: 0;
	left: 0px;
	right: 0;
	/*margin:auto auto;*/
	margin: 50px auto;
}
.login-box-msg, .register-box-msg {
    margin: 0px;
    text-align: center;
	padding: 0px 20px 0px 20px;
}

.login-title {
	font-size:16px;
	margin-top:5px;
}
.login-box-body {
    padding: 15px;
	background:#f5f5f5;
	box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.3);
	background:url(../img/bg-login.jpg) #FFF;
}

.form-group {
	margin-top:5px;
	margin-bottom:5px;
	
}
.login-page, .register-page {
    background: #d2d6de ;
	
}

.tlogin-btn {
	margin: 20px 0px 0px 0px;
}

.btn-masuk {
	width:100%;
	border-radius:3px;
}
.login-title {
	font-size: 34px;
	margin: 15px 0px 20px 0px;
}

.login-title1,
.login-title2 {
	margin-top:10px;
}

.login-title1 {
	font-weight:bold;
	line-height:13px;
}
.login-title2 {
	font-size: 16px;
	line-height:16px;
}

.logo-login img {
	width:100px;
	max-width:100%;
	
}

/*
.form-control-feedback {
    margin: -7px 0px;
}
*/

@media (max-width:420px) {
	body {
		background: url("template/AdminLTE-2.4.0-alpha/dist/img/boxed-bg2.jpg") no-repeat -80px center fixed;
		 -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
	}
}
</style>
</head>
<body class="login-pagex" style='display:nonezz'>
<div class="login-bg">

	<div class="login-box">

	  <!-- /.login-logo -->
	  <div class="login-box-body">
		<center>
		
			<div class=logo-login>
				<img class=logo-login src='<?=$nfLogo?>'>
			</div>
			<div class=login-title>
			<?php
			
			$jd1=$nfAppB;
			if (isset($nfAppC)) 
				$jd2=$nfAppC;
			else
				$jd2="";
			
			$sty1=$sty2="1";
			if (strlen($jd1)>10) $sty1=2;
			if (strlen($jd2)>10) $sty2=2;
			
			echo "<div class='login-title$sty1'>$jd1</div>";
			if (isset($nfAppC)) echo "<div class='login-title$sty2'>$jd2</div>";			
			
			?>
			</div>
		
		<div class="login-box-msg" style=''></div>
		</center>
		<form action="index.php" method="post">
		  <div class="form-group has-feedback">
		  <input type=hidden name=page value='login'>
		  <input type=hidden name=det value='login'>
		  <input type="text" class="form-control" placeholder="User ID" name=usrid value='<?=$uid?>' >
			<span class="fa fa-user form-control-feedback"></span>
		  </div>
		  <div class="form-group has-feedback">
			<input type="password" class="form-control" placeholder="Password" name=usrps >
			<span class="fa fa-lock form-control-feedback"></span>
		  </div>
		  <div class="form-group has-feedback tlogin-btn">
			<!--div class="col-xs-4">
			  <div class="checkbox icheck">
				<label style='display:none'>
				  <input type="checkbox"> Remember Me
				</label>
			  </div>
			</div-->
			<!-- /.col -->
	 
			  <!--button type="button" class="btn btn-primary btn-flat" onclick="location.href='index.php';">Home</button-->
			  <button type="submit" class="btn-masuk btn btn-primary btn-flat">Masuk</button>
			 
			<!-- /.col -->
		  </div>
		</form>
<?php

if (isset($addLinkLogin)) echo "<center>$addLinkLogin</center>";

?>
	  </div>	<!-- /.login-box-body -->
  </div><!-- /.login-box -->
  
</div><!-- /.login-bg -->


<?php
/*

<!-- jQuery 3.1.1 -->
<script src="<?=$tppath?>plugins/jQuery/jquery-3.1.1.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=$tppath?>bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?=$tppath?>plugins/iCheck/icheck.min.js"></script>

*/
?>
  
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
  $(document).ready(function(){
	//  	$("body").show();


  })
</script>
</body>
</html>	
