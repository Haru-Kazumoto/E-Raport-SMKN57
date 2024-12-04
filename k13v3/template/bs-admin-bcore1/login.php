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
    <link rel="stylesheet" href="<?=$tppath?>assets/css/login.css" />
    <link rel="stylesheet" href="<?=$tppath?>assets/plugins/magic/magic.css" />
     <!-- END PAGE LEVEL STYLES -->
   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
    <!-- END HEAD -->
    
<style>
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
	</style>

    <!-- BEGIN BODY -->
<body style='background:#ccc'>

   <!-- PAGE CONTENT --> 
    <div class="container" >
    <div class="text-center" style='margin-bottom:-10px'>
        <img src="<?=$toroot?>img/logo_diknas.png" id="logoimgx" alt=" Logo" style='width:90px'/>
<?php
			
if ($pes!='') {
    echo "
		<div id=idc class='text-danger'	>

		<br>
		<br>$pes
</div>
"; 	
	echo "<script>setTimeout(\"$('#idc').hide();\",1500);</script>";	
}
?>
    </div>
    <div class="tab-content">
        <div id="login" class="tab-pane active">
            <form action="<?=$toroot?>index.php?page=login&op=login" class="form-signin" method=post >
                <p class="text-muted text-center btn-block btn btn-primary btn-rect">
                    SISTEM INFORMASI PENILAIAN<BR>
SEKOLAH MENENGAH KEJURUAN<BR>
KURIKULUM 2013 REVISI 
                </p>
                <input name=usrid type="text" placeholder="Username" class="form-control" />
                <input name=usrps type="password" placeholder="Password" class="form-control" />
                <!--input name=thpl type="text" placeholder="Tahun Ajaran" class="form-control" /-->
                <?php
				 $thplx=($thpl!=""?$thpl:date("Y")."-".(date("Y")+1));
				 //echo $thplx;
                 echo um412_isicombo5("2016-2017,2017-2018,2018-2019,2019-2020,2020-2021,2021-2022","thpl,class='form-control' ","","","Pilih Tahun Pelajaran","$thplx");
				?>
                
                <br>
                <div align=center>
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
      <script src="<?=$tppath?>assets/plugins/jquery-2.0.3.min.js"></script>
      <script src="<?=$tppath?>assets/plugins/bootstrap/js/bootstrap.js"></script>
   <script src="<?=$tppath?>assets/js/login.js"></script>
      <!--END PAGE LEVEL SCRIPTS -->

</body>
    <!-- END BODY -->
</html>
