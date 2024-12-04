<?php

if (isset($_SESSION['timeout'])) { 
	if ($_SESSION['timeout']==1) {
	 
		echo "Sesi timeout, silahkan <a href=index.php>login</a> ulang....";
		unset($_SESSION['nis']);
		unset($_SESSION['nip']);
		unset($_SESSION['timeout']);
		redirection("index.php");
		exit;
	}
} 	

if (!isset($judulWeb)) $judulWeb=strip_tags("$nfAppA1 $nfAppA2");
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD --><head>
     <meta charset="UTF-8" />
    <title><?=$judulWeb?> | Login Page</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
     <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <!-- GLOBAL STYLES -->
     <!-- PAGE LEVEL STYLES -->
<link rel="stylesheet" href="<?=$tppath?>assets/plugins/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="<?=$toroot?>css/custom.css" />
    <!--link rel="stylesheet" href="<?=$tppath?>assets/plugins/magic/magic.css" /-->
     <!-- END PAGE LEVEL STYLES -->
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
    <!-- END HEAD -->
    
    <!-- BEGIN BODY -->
<body class='body-login'>

   <!-- PAGE CONTENT --> 
    <div class="container" >
    <div class="text-center" style='margin-bottom:10px'>
        <img src="<?=$toroot?>img/logo_diknas.png" id="logoimgx" alt=" Logo" style='width:90px'/>
    </div>
    <div class="tab-content">
        <div id="login" class="tab-pane active">
            <form action="<?=$toroot?>index.php" class="form-signin" method=post >
                <p class="title-login text-muted text-center btn-block btn btn-primary btn-rect">
                    <?php echo $nfAppA1.'<br>'.str_replace("-","<br>",$nfAppA2);?>
                </p>
<?php
if ($pes!='') {
    echo "
		<div id=idc class='text-danger'	>
		$pes
		</div>
"; 	
	echo "<script>setTimeout(\"$('#idc').fadeOut(1000);\",2000);</script>";	
}


?>
                <input name=usrid type="text" placeholder="Username" class="form-control" />
                <input name=usrps type="password" placeholder="Password" class="form-control" />
                <!--input name=thpl type="text" placeholder="Tahun Ajaran" class="form-control" /-->
                <center>
				<?php
				 $thplx=($thpl4!=""?$thpl4:date("Y")."-".(date("Y")+1));
				 //echo $thplx;
                 echo um412_isicombo5("2021-2022,2022-2023,2023-2024,2024-2025","thpl4,class='form-control' ","","","Pilih Tahun Pelajaran","$thplx");
				?>
                </center>
                <br>
                <div align=center>
				<input type=hidden name=page value='login'>
				<input type=hidden name=op value='login'>
                <button class="btn text-muted text-center btn-success" type="button" onClick="location.href='../index.php';">Kembali</button>
                <button class="btn text-muted text-center btn-primary" type="submit">Masuk</button>
                 

</div>
            </form>
        </div>
        <div id="forgot" class="tab-pane">
            <form action="index.html" class="form-signin">
                <p class="text-muted text-center btn-block btn btn-primary btn-rect">Enter your valid e-mail</p>
                <input type="email"  required="required" placeholder="Your E-mail"  class="form-control" />
                <br />
                <button class="btn text-muted text-center btn-success" type="submit">Recover Password</button>
            </form>
        </div>
        <div id="signup" class="tab-pane">
            <form action="index.html" class="form-signin">
                <p class="text-muted text-center btn-block btn btn-primary btn-rect">Please Fill Details To Register</p>
                 <input type="text" placeholder="First Name" class="form-control" />
                 <input type="text" placeholder="Last Name" class="form-control" />
                <input type="text" placeholder="Username" class="form-control" />
                <input type="email" placeholder="Your E-mail" class="form-control" />
                <input type="password" placeholder="password" class="form-control" />
                <input type="password" placeholder="Re type password" class="form-control" />
                <button class="btn text-muted text-center btn-success" type="submit">Register</button>
            </form>
        </div>
    </div>
    <!--div class="text-center" >
        <ul class="list-inline">
            <li><a class="text-muted" href="#login" data-toggle="tab">Login</a></li>
            <li><a class="text-muted" href="#forgot" data-toggle="tab">Forgot Password</a></li>
            <li><a class="text-muted" href="#signup" data-toggle="tab">Signup</a></li>
        </ul>
    </div-->


</div>

	  <!--END PAGE CONTENT -->     
	      
      <!-- PAGE LEVEL SCRIPTS -->
      <!--script src="<?=$tppath?>assets/plugins/jquery-2.0.3.min.js"></script-->
      <!--script src="<?=$tppath?>assets/plugins/bootstrap/js/bootstrap.js"></script-->
   <style>
body {
    padding-top: 40px;
    padding-bottom: 40px;
	
}

.container {
	position:relative;
	background:#FFF;
	padding:20px;
	width:400px;
	box-shadow:0 2px 10px rgba(0,0,0,0.3);
	-webkit-box-sizing: none;
	-moz-box-sizing: none;
	box-sizing: none;
}

.form-signin {
    max-width: 330px;
    padding: 0px 15px 15px 15px;
    margin: 0 auto;
    
    

}
.form-signin .form-signin-heading,
.form-signin .checkbox {
    margin-bottom: 10px;
}
.form-signin .checkbox {
    font-weight: normal;
}
.form-signin input[type="text"],
.form-signin input[type="password"],
.form-signin input[type="email"] {
    position: relative;
    font-size: 13px;
   height: 40px!important;
    height: auto;
    padding: 5px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    margin-top:10px!important;
}
.form-signin input[type="text"]:focus,
.form-signin input[type="password"]:focus,
.form-signin input[type="email"]:focus  {
    z-index: 2;
}
.form-signin input[type="text"] {
    margin-bottom: -1px;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}
.form-signin input[type="password"] {
    margin-bottom: 10px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.form-signin input[type="email"] {
    margin-bottom: -1px;
    border-radius: 0;
}

.list-inline a {
    color:#000000 !important;
    font-size:13px !important;
}

.list-inline a:hover {
    color:#24a2f6!important;
    text-decoration:none;
}
.btn-lg {
    margin:0px!important;
}
.form-signin p {
    /*background-color:#000;
    font-size:16px !important;*/
     -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    color:white;
    padding:8px 0px 8px 0px;
    border-radius:5px;
      -webkit-border-radius:5px;
    -moz-border-radius: 5px;

}
#login {
    margin-top:30px;
}

#forgot {
    margin-top:50px;
}

#signup {
    margin-top:30px;
}

#logoimg {
  width: 315px;
height: 51px;
padding-left: 33px
}
#idc {
	padding:10px;
	margin-top:10px;
}

.thpl {
	width:100%;
	text-align:center;
	padding: 7px 0px;
	background: #f4fbeb;
}
</style>
      <!--END PAGE LEVEL SCRIPTS -->

</body>
    <!-- END BODY -->
</html>
