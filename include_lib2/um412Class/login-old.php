<?php
//require_once $um_class."login.php";
/*

			$atbuser=[
				[ "tb"=>"tbpembantu",
					"sy"=>"jpembantu='PL'",
					"fldid"=>"id",
					"flduid"=>"vuserid",
					"flduname"=>"nama",
					"fldpss"=>"vpass",
					"utype"=>"marketing",
					//"fldtype"=>"marketing",
					"ulv"=>5,
				]
			];

*/
class login {
	public $sql;
	public $op;
	public $isLogin=false;
	public $defNmSekolah="";
	public $vidusr=0;
	public $vidgu=0;
	public $defKdSekolah="";
	public $defKdKelas="";
	public $defTingkat=0;
	public $defKdGuru=0;
	public $defNmGuru="";
	public $nextLoad="index.php";
	public $pesLogin="";	
	public $atbuser=[];

	public $userid="";
	public $userName="";
	public $userType="";
	public $levelOwner=0;
	
	public $loginPath="login/";
	public $urlSuccess="index.php";
	public $urlActLogin="index.php?page=login"; 
	
	public $nfApp="";
	public $nfAppB="";
	
	public $loginHeader="";		
	public $frmLogin="";
	public $frmRegister="";
	
	public $aIpBlocked=array();
	public $aIpAllowed=array();
	public $sUserTypeRecorded="admin,sa,guru,content";//user yg akan direcord ipnya
	public $sUserTypeAllowed="admin,content,sa";//user yang diperbolehkan dalam aipallowed	
	
	function login(){
		echo "cls login dipanggil";exit;
	}

	function setLoginHeader(){
		$this->loginHeader="		
		<div class='login-header'>
			<div class='logo-login'>
				<img src='content/img/logo.png'>
			</div>
			
			<div class='login-title'>
				$this->nfApp<br>$this->nfAppB
			</div>
		</div >
		";
	}

	function userLogout() {
		//global $this->userid,$this->userid,$this->userName,$this->userType,$this->isLogin;
		$this->userid=$this->userid=$_SESSION["usrid"]="Guest";
		$this->userName=$_SESSION["usrnm"]="Guest";
		$this->userType=$_SESSION["usrtype"]="Guest";
		$_SESSION["vidusr"]=0;
		unset($_SESSION["usrid"]);
		unset($_SESSION["usrnm"]);
		unset($_SESSION["usrtype"]);
		unset($_SESSION["vidusr"]);
		
		$this->isLogin=false;
	}
	
	function logout(){
		$this->updateStatUser(0);
		$this->userLogout();
	}

	function cekLogin($showFrmLoginifFalse=false) {
		global $levelOwner,$userid,$userType,$defKdGuru,$vidgu;
		$uid=$ups="";
		
		if (isset($_REQUEST["usrid"])) {
			resetTimeoutSesi();
			
			$uid=$_REQUEST["usrid"];
			$ups=$_REQUEST["usrps"];
			$this->pesLogin="Userid/Email atau password salah";
			$this->isLogin=false;
			//cek di guru
			if (!$this->isLogin) {
				$sqlusr="select * from tbguru where (vemail='$uid' or vuserid='$uid') and vpass='".md5($ups)."'  ";
				$hasilusr=mysql_query($sqlusr) or die("err");
				while ($rwusr=mysql_fetch_array($hasilusr)) {
					$this->vidusr=$rwusr["id"];
					$this->userid=($rwusr["vemail"]);
					$this->userName=$rwusr["nama"];
					$this->userType="Guru"; 
					$this->isLogin=true;
					$this->levelOwner=7;
				}
			}
			
			
			//cek di siswa
			if (!$this->isLogin) {
				$sqlusr="select * from tbsiswa where vuserid='$uid'  and vpass='$ups'  ";
				$hasilusr=mysql_query($sqlusr) or die("err");
				while ($rwusr=mysql_fetch_array($hasilusr)) {
					$this->vidusr=$rwusr["id"];
					$this->userid=$this->userid=($rwusr["vuserid"]);
					$this->userName=$rwusr["nama"];
					$this->userType="Siswa"; 
					$this->isLogin=true;
					$this->levelOwner=5;
				}
			}
			
			//cari di admin
			if (!$this->isLogin) {
				$sqlusr="select * from tbuser where vuserid='$uid' and vpass='".md5($ups)."' ";
				$hasilusr=mysql_query($sqlusr) or die("err");
				while ($rwusr=mysql_fetch_array($hasilusr)) {
					$this->vidusr=1000000000;
					$this->userid=$this->userid=$rwusr["vuserid"];
					$this->userName=$rwusr["vusername"];
					$this->userType=$rwusr["vusertype"]; 
					$this->isLogin=true;
					$this->levelOwner=10;					
				}
			}
			
			/*
			//cari di registrasi
			if (!$this->isLogin) {
				$sqlusr="select * from tbregistrasi where vemail='$uid' and vpass='".md5($ups)."' ";
				$hasilusr=mysql_query($sqlusr) or die("err");
				while ($rwusr=mysql_fetch_array($hasilusr)) {
					//jika ada maka berarti belum verifikasi
					$this->pesLogin="Anda belum melakukan verifikasi email, silahkan buka kembali email verifikasi pendaftaran Anda dan klik link verifikasi.";
				}
			}
			

			*/
			echo "sampai....";exit;
			if (!$this->isLogin) {
				global $atbuser;exit;
				foreach($atbuser as $tbu) {
					echo "<br>cek di tabel lain...";
					if (isset($tbu->tb)) {
						echo "<br> $tb ";
						$tb=$tbu->tb;
						$fldid=$tbu->fldid;
						$flduid=$tbu->flduid;
						$flduname=$tbu->flduname;
						$fldpss=$tbu->fldpss;
						$sy=$tbu->sy;
						$sqlusr="select * from $tb where $flduid='$uid' and $fldups='".md5($ups)."' where 1 $sy ";
						$hasilusr=mysql_query($sqlusr) or die("err");
						while ($rwusr=mysql_fetch_array($hasilusr)) {
							$isLogin=true;
							$this->vidusr=$rwusr[$fldid];
							$this->userid=$rwusr[$flduid];
							$this->userName=$rwusr[$flduname];
							if (isset($tbu->fldutype)) {
								$this->userType=$rwusr[$fldutype]; 
							} else 
								$this->userType=$tbu->utype;
							$this->isLogin=true;
							$this->levelOwner=10;					
									
						
						}
						
						
					}
					if ($isLogin) continue;
					
					
				}
				
			
			}
			
			if ($this->isLogin) {
				//simpan sesi
				@session_start();
				$_SESSION["vidusr"]=$this->vidusr;
				$_SESSION["usrid"]=$this->userid;
				$_SESSION["usrnm"]=$this->userName;
				$_SESSION["usrps"]=$ups;
				$_SESSION["usrtype"]=$this->userType;
				$this->pesLogin="Login Sukses";
				//redirection($this->urlSuccess);				
				$levelOwner=$this->levelOwner;
				//exit;
			}	else {
				if ($showFrmLoginifFalse) echo $this->showFrmLogin();
				//$this->pesLogin="Email atau password salah";
				//$this->loginForm();
			}
			return $this->isLogin;
			
		} 
		elseif (isset($_SESSION["usrid"])) {	
			if (!isset($_SESSION["vidusr"])) {
				$this->logout();
				//redirection('index.php');
			} else 
			if (!isset($_SESSION["usrid"])) {
				$this->logout();
			} else {
				$userid=$this->userid=$_SESSION["usrid"];
				$userName=$this->userName=$_SESSION["usrnm"];
				$vidusr=$this->vidusr=$_SESSION["vidusr"];
				$userType=$this->userType=$_SESSION["usrtype"];
				$isLogin=$this->isLogin=($this->userid=='Guest'?false:true);
			}
		} else {
			$isLogin=$this->isLogin=false;
			$uid='';
			$ups='';
		}		
		
		$vidgu=$this->vidgu=0;
		if ($this->isLogin) {
			$this->isAdmin=(usertype('admin')?true:false);
			if (usertype("siswa")) {
				$sq="select s.kdkelas as defKdKelas,tingkat as defTingkat,s.kdguru as vidgu from 
				tbsiswa s inner join tbguru g on s.kdguru=g.id  where s.vuserid='$this->userid'";
				extractRecord($sq);
			} 
			elseif (usertype("guru")) { 
				extractRecord("select id as defKdGuru,nama as defNmGuru from tbguru where vemail='$this->userid'");
				$vidgu=$this->vidgu=$defKdGuru;
			} 
			$this->secureLogin(1);
			$this->updateStatUser(1);
		}
		//
		return $this->isLogin;
	}
		
	function updateStatUser($stat=1){
	   //echo "isi fungsi updateStatUser >>";	
	   	date_default_timezone_set('Asia/Bangkok');
		$tgls=date("Y-m-d H:i:s");
		if ($stat==2) {
			//autologout
			$tglb=date("Y-m-d H:i:s",strtotime($tgls)-15*60);
			 $sq= "update tbsiswa set statuser='0' where lastclick<'$tglb'  ";
			mysql_query($sq);
			return "";
		}

		global $userid;
		if (usertype("guru")) {
			$tbuser="tbguru";
			$nmfield="vuserid";
			
		} elseif (usertype("siswa")) {
			$tbuser="tbsiswa";
			$nmfield="nisn";
		} else {
			$tbuser="tbuser";
			$nmfield="vuserid";
			//return false;
		}
		$sq="update $tbuser set statuser='$stat',lastclick='$tgls' where $nmfield='$userid' ";
		mysql_query($sq);

	}
	
	function showFrmForgot(){
		global $rnd,$em;
		$t="";
		if ($this->pesLogin!='') {
			$t.= "
			<script>
			setTimeout(function() {
				$('.login-box-msg').hide('slow');
			},4000);
			</script>
			";
		}

		$idform="flg$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		$ons="";
		
		$frm=new htmlForm;
		$frm->nmForm="frmL";
		$frm->nfAction=$this->urlActLogin;
		$frm->awalForm();
		
		$frm->rowForm2("","		  <div class='form-group has-feedback'>
			<input type='text' class='form-control' placeholder='E-mail' name='em' value='$em'>
	
		  </div>
		  <div class='form-group has-feedback' style='margin-bottom:0px'>
			<button type='submit' class='btn-masuk btn btn-primary btn-flat'>Kirim</button>
			<input type='hidden' name='oplg' value='lfgp'>
			<input type='hidden' name='page' value='login'>
			
		  </div>");
		  
		
		$t="";
		$t.="
		<div class='login-box' id='login-box'>
		<div class='login-box-body'>
			  <center>
				<p class='login-box-msg' style=''>$this->pesLogin </p>";
		$t.=$frm->show();
		$t.="
		<div class='login-pesn'>
			<a href='$this->urlActLogin"."&oplg=f' >Login</a> |
			<a href='$this->urlActLogin"."&oplg=lrg' >Mendaftar</a>
		</div>
				";
		

		$t.="	  
				</center>
			</div>
		</div>
		";
		return $t;

	}
	
	function showFrmLogin(){
		//$this->setFrmLogin();//set ulang
		global $rnd;
		$t="";
		
		$idform="flg$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		$ons="";
		
		$frm=new htmlForm;
		$frm->nmForm="frmL";
		$frm->nfAction=$this->urlActLogin;
		$frm->awalForm();
		$frm->rowForm2("usrid","
			  <div class='form-group has-feedback'>
					<input type='text' required minlength=4 class='form-control' placeholder='E-mail/User ID' name='usrid' id='usrid$rnd' 
					title='Silahkan masukkan userid/email, minimal 4 huruf'>
					<div style='display:none' id='usrid-err' ></div>
					<span class='fa fa-user form-control-feedback'></span>
				  </div>
			");
		$frm->rowForm2("usrps","
		  <div class='form-group has-feedback'>
					<input type='password' required minlength=6  class='form-control' 
					placeholder='Password' name='usrps' id='usrps$rnd'
					title='Silahkan masukkan password, minimal 6 huruf'>
					<div style='display:none' id='usrps-err' ></div>
					<span class='fa fa-lock form-control-feedback'></span>
				  </div>
				");
		$frm->rowForm2("","
			  <div class='form-group has-feedback' style='margin-bottom:0px'>
					<button type='submit' class='btn-masuk btn btn-primary btn-flat'>Masuk</button>
					<input type='hidden' name='oplg' value='lp'>
					<input type='hidden' name='page' value='login'>
				  </div>
			"); 
		$t="
		<div id='ts$idform' style='display:none'></div>
		<div class='login-box' id='login-box'>
			<div class='login-box-body'>
			  <center>
				<p class='login-box-msg' >$this->pesLogin</p>
			";

		$t.= $frm->show();
		$t.="	  
				</center>
			</div>
		</div>
		";
		
		$t.="
		<div class='login-pesn'>
			<a href=\"$this->urlActLogin"."&oplg=lfg\"  >Lupa Password</a> |
			<a href=\"$this->urlActLogin"."&oplg=lrg\" >Mendaftar</a>
		</div>";
		
		if ($this->pesLogin!='') {
			$t.="
				<script>
				setTimeout(function() {
					$('.login-box-msg').hide('slow');
				},4000);
				</script>
			";
		}
		
		
		$this->frmLogin=$t;
	
		return $this->frmLogin;
	}
	
	function showFrmRegister() {
		global $rnd;
		$rnama=$rusrem=$rusrhp='';
		cekVar("rnama,rusrem,rusrhp,rusrps,rusrps2");
		
		$t="";
		if ($this->pesLogin!='') {
			$t.= "
			<script>
			setTimeout(function() {
				$('.login-box-msg').hide('slow');
			},4000);
			</script>
			";
		}

		$idform="frg$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		$ons="";
		
		$frm=new htmlForm;
		$frm->nmForm="frmR";
		$frm->nfAction=$this->urlActLogin."&oplg=lrgp";
		
		$frm->awalForm();
		$frm->rowForm("rnama","Nama Lengkap","text|60|5|CF|fa-user");
		$frm->rowForm("rusrem","E-mail","email|60|5|CF|fa-envelope");
		$frm->rowForm("rusrhp","No. HP/WA","text|60|8|CF|fa-phone");
		$frm->rowForm("rusrid","User ID","text|60|5|CF|fa-user");
		$frm->rowForm("rusrps","Password","password|30|5|CF|fa-lock");
		$frm->rowForm("rusrps2","Konfirmasi Password","password|30|5|CF|fa-lock");
		
		$frm->rowForm2("","			  
				  <div class='form-group has-feedback' style='margin-bottom:0px'>
					<button type='submit' class='btn-masuk btn btn-primary btn-flat'>Daftar</button>
					<input type='hidden' name='oplg' value='lrgp'>
 
					 
					
		<div class='reg-pesn'>
			<a href=\"$this->urlActLogin"."&oplg=lfg\"  >Lupa Password</a> |
			<a href=\"$this->urlActLogin\" >Login</a>
		</div>");
		

		$t.="
		<div class='login-box' id='login-box'>
		<div class='login-box-body'>
			  <center>
				<p class='login-box-msg' style=''>$this->pesLogin </p>";
		$t.=$frm->show();
 
		

		$t.="	  
				</center>
			</div>
		</div>
		";
		
		$this->frmRegister=$t;
		
		return $this->frmRegister;			
	}
		function setFrmLogin($t=""){
		$this->frmLogin=$t;
	}
	function scriptJS() {
		$t="";
		return $t;
	}
	
	function scriptCSS() {
		$t="
			<style>

		.login-page, .register-page {
		}

		.login-box {
			width:100%;
			max-width:280px;
			margin: 20px auto;
			/*	
			max-height:345px;	
			box-shadow:0 2px 2px 2px rgba(190, 190, 236, 0.63);
			background:#f5f5f5;
			*/
		}
		.login-box-msg, .register-box-msg {
			margin: 0px;
			text-align: center;
			padding: 0px 20px 0px 20px;
			color: #f00;
			background: #fffeb39e;
		}

		.login-title {
			font-size:16px;
			font-weight:bold;
			margin-top:5px;
			line-height: 18px;
		}
		.login-box-body {
			padding: 15px;
		}

		.form-group {
			margin-top:5px;
			margin-bottom:5px;
			
		}
		.btn-masuk {
			width:100%;
			border-radius:0px;
			padding:7px;

		}

		.logo-login img {
			width:30%;
			height:90px;
		}

		.login-pesn {
			margin: 18px 0px;
			text-align:center;
		}
		.form-control-feedback {
			top: 0px;
			position: absolute;
			right: 0;
			z-index: 2;
			display: block;
			width: 34px;
			height: 34px;
			line-height: 34px;
			text-align: center;
			pointer-events: none;
		}

		#login-box .form-control {
				display: block;
				width: 100%;
				height: 34px;
				padding: 6px 12px; 
				font-size: 14px;
				line-height: 1.42857143;
				color: #555;
				background-color: #fff;
				background-image: none;
				border: 1px solid #ccc; 
				border-radius: 4px;
				-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
				box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
				-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
				-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
				transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		}
			
		#login-box	.form-group {
				margin: 0px;
				padding: 0px 0px;
		}	
			
		@media (max-width:420px) {
		}
		</style>";
		return $t;
	}
	
	function cekFrmRegister(){
		global $rnama,$rusrem,$rusrhp,$rusrps,$rusrps2;
		cekVar("rnama,rusrem,rusrhp,rusrps,rusrps2");
		$pes=$t="";
		if ($rnama=="") {
			$pes.="<br>Nama Harus diisi";
		}

		if ($rusrem=="") {
			$pes.="<br>Email Harus diisi";
		} else {
			if (!validasiEmail($rusrem)) {
				$pes.="<br>Email tidak valid";
			} else{
				$c=carifield("select id from tbregistrasi where vemail='$rusrem'");
				if ($c!="")		$pes.="<br>Alamat Email Sudah digunakan ...";
			}
		}
		
		if ($rusrhp=="") {
			$pes.="<br>Nomor HP Harus diisi";
		}
		
		if ($rusrid=="") {
			$pes.="<br>User ID Harus diisi";
		} else {
			if (!validasiUID($rusrid)) {
				$pes.="<br>User id tidak valid, harus mengandung : angka,huruf, tidak boleh menggunakan tanda baca.";
				
			} else {	
				$c=carifield("select vuserid from tbregistrasi where vuserid='$rusrid'");
				if ($c!="")	$pes.="<br>User ID Sudah digunakan ...";
			}
		}
		

		$vp=strip_tags(validasiPassword($rusrps,$rusrps2,$soption="W06",$separator='')); 
		if ($vp!='')$pes.="<br>$vp";

		if ($pes=="") {
			$sq="insert into tbregistrasi(vuserid,vemail,vhp,vpass,nama) values('$rusrid','$rusrem','$rusrhp',md5('$rusrps'),'$rnama');";
			$h=mysql_query($sq);
			if ($h) {
				$idv=mysql_insert_id();
				$jenisv="pendaftaran";
				$t.=$this->kirimVerifikasi($jenisv,$idv,$rusrem);
		 
			} else {
				$t.= "Pendaftaran Tidak Berhasil,....";
			}
		} else {
			$this->pesLogin=$pes;
			$t.=$this->showFrmRegister();
		}
		return $t;

	}
	function cekVerifikasi(){
		global $tkn,$vid,$nama,$vpass,$stat,$jenisreg,$vemail;
		//echo "<br>Melakukan verifikasi....<br>";
		$vid=0;
		cekVar("tkn");
		//getToken3($tkn);
		evalToken3($tkn);
		if ($vid*1>0) {
			$tgls=date("Y-m-d H:i:s");
			extractRecord("select id as id2,nama,vuserid,vpass,stat,jenis as jenisreg,vemail  from tbregistrasi where id=$vid  ");
			if ($id2=="") {
				//tidak ada di database
				
			} else {
				//jika ada 
				if ($stat=="unverified") {
					$sq="update tbregistrasi set stat='verified',tglverifikasi='$tgls' where id='$vid'";	
					$h=mysql_query($sq);
					if ($h) {
						//jenisreg=guru
						$sq="insert into tbguru(vuserid,nama,vpass,vemail,vhp,vidreg) values('$vuserid','$nama','$vpass','$vemail','$id2');";
						$h=mysql_query($sq);	
						$pes= "Verifikasi pendaftaran berhasil, silahkan lakukan login.<br>";
					} else {
						
					}
				} else {
					$pes= "User sudah terverifikasi.[stat:$stat],  silahkan lakukan login<br>";
				}
				echo $this->showFrmLogin();
				
				//include $pathLogin."login-form.php";
				//include $pathLogin."login-script.php";
			}
		} else {
			echo "Mohon maaf, token tidak valid....";
		}
	}
	
	
	function registerIp(){
		global $ip,$userid,$idip,$svuid,$jlhip,$userType;
		//$userid=$this->userid;
		//$userType=$this->userType;
		//if ($userid=="") return ;
		$s="";
		extractRecord("select id as idip,svuid,jlhip  from tbh_logip where ip='$ip'");
		if ($idip=="") {
			$s="insert into tbh_logip(ip,svuid,jlhip) values('$ip','$userid/$userType',1);";
		} else {
			if (strstr($svuid,$userid)=="")
				$s="update  tbh_logip set svuid=concat(svuid,',','$userid/$userType'),jlhip=jlhip+1 where ip='$ip';";
			else {
				$s="";//nggak perlu update/insert
			}
		}
		if ($s!='') mysql_query($s);
	 
	}

	function registerClick($sUserType="",$aipAllowed=array()){
		global $ip,$userid,$idip,$svuid,$jlhip,$userType,$det;
		cekvar("det");
		if (usertype("sa")) {
			if ($det=="logclick") return;
			if ($det=="logh2") return;
		}
		//khusus type teretentu saja yg dicatat
		if ($sUserType!="") {
			if (!userType($sUserType)) return ;
		}
		//khusus selain ip yang diperbolehkan
		if (count($aipAllowed)>0) {
			if (array_search($ip,$aipAllowed)!="") return;
		}
		$url="";
		$s="";
		$ket="";
		$ket.= "[HEADER]<br>".getHttpHeader2()."<br>";
		
		$h=getHttpRequest2("get");
		if ($h!='') $ket.= "[GET]<br>".$h."<br>";

		$h=getHttpRequest2("post");;
		if ($h!='') $ket.= "[POST]<br>".$h."<br>";
		
		$ket=str_replace("'","/'",$ket);

		$s="insert into tbh_logclick(ip,vuid,ket) values('$ip','$userid/$userType','$ket');";
		
		if ($s!='') mysql_query($s);
		//exit;
	}
	
	//optAct:ILE :Index.php Logout Exit $xforcelogout=true,$xexit=true
	function reportHacked2($jenislog='hacked',$sOptAct="I",$ket=""){
		global $ip,$userid,$userType,$userPs;
		if ($ket!="")$ket.="/";
		if (isset($userid)) $ket.="uid:$userid/";
		if (isset($userType)) $ket.="utp:$userType/";
		if (isset($userPs)) $ket.="ups:$userPs/";
		
		$ket="";
		$ket.= "[HEADER]<br>".getHttpHeader2()."<br>";

		$h=getHttpRequest2("get");
		if ($h!='') $ket.= "[GET]<br>".$h."<br>";
		$h=getHttpRequest2("post");;
		if ($h!='') $ket.= "[POST]<br>".$h."<br>";	
		$ket=str_replace("'","/'",$ket);
		
		$s="INSERT INTO tbh_logh2 (jenislog,user,ip,ket,idtrans) VALUES ('$jenislog','$userid/$userType','$ip','$ket','');";		
		if ($s!='') mysql_query($s);
		
		if (strstr($sOptAct,"L")!="") {
			forceLogout();
			redirection("index.php");
		} else if (strstr($sOptAct,"I")!="")  {
			redirection("index.php");
			exit;
		} else if (strstr($sOptAct,"E")!="")  {
			exit;
		} 

	}

	function secureLogin($force=0){
		global $isOnline,$aIpBlocked,$aIpAllowed,$ip,$userType,$sUserTypeRecorded,$sUserTypeAllowed;
		if (!isset($aIpBlocked)) $aIpBlocked=array();
		if (!isset($aIpAllowed)) $aIpAllowed=array();
		if (!isset($sUserTypeRecorded)) $sUserTypeRecorded="admin,sa,guru,content";//user yg akan direcord ipnya
		if (!isset($sUserTypeAllowed)) $sUserTypeAllowed="admin,content,sa";//user yang diperbolehkan dalam aipallowed
		
		if (($isOnline)||($force==1)) {
			$this->registerIp();//untuk memasukkan ip ke tbl_logip
			$aip1=$aIpAllowed;	
			$blocked=false;
			//jika ada di array blocked
			if (!empty( $aIpBlocked)) {
				if (array_search($ip,$aIpBlocked)!="") $blocked=true; 
			}
			
			//jika terblok di tabel logip
			if (!$blocked) {
				$aipblocked2=getArray("select ip from tbh_logip where mark='Blocked'" );
				if (array_search($ip,$aipblocked2)!="") $blocked=true;
			}
			if ($blocked) {
				//echo "blocked";
				$this->reportHacked2('Ip Blocked');
			}
			
			if (usertype($sUserTypeRecorded)) {
				//$temp_sip=trim(carifield("select sipallowed from tbsekolah where kdsekolah='$defKdSekolah'"));
				$temp_sip='';
				if ($temp_sip=='') {
					$aip2=$aip1;
				} else {
					$aip2=array_merge($aip1,explode(",",$temp_sip));					
					if (array_search($ip,$aip1)===false) {
					}
				}  
				
				$isIPAllowed=true;	
				if (count($aip2)>0) {
					if (array_search($ip,$aip2)===false) {
						if (usertype($sUserTypeAllowed)) {
							reportHacked2('Unauthorized IP-$userType');
							$isIPAllowed=false;	
						}
					}
				}
				$this->registerClick("sa,admin,guru,content",$aip2); 	//khusus user sa,admin,guru diregister ipnya 	 
			}
			//-akhir security
			
		} else {
			$isIPAllowed=true;	//jika offline semua ip boleh
		} 
	}
	
	function kirimVerifikasi($jenisv='pendaftaran',$idv,$mailTo){
		global $folderHost,$vDomain,$judulWeb,$webmasterMail,$webmasterName,$um_path,$isOnline;
		$t='';
		$tkn=makeToken3("r=".date("Ymd")."&vid=$idv");
		//$urlVerifikasi="http://$vDomain/".$this->urlActLogin."&oplg=verifikasiEm&tkn=$tkn";
		$urlVerifikasi="http://$vDomain/"."index.php?page=verifikasiEm&tkn=$tkn";
		$subjectMail="Verifikasi Pendaftaran - $judulWeb";
		//$mailTo=$rusrem;
		$fromMail=$webmasterMail;
		$fromName="SCI Media Online";
		$nmFileAttachment="";

		if ($jenisv=="pendaftaran") {
			$bodyMail="Pendaftaran berhasil.
			<br>Silahkan melakukan verifikasi pendaftaran dengan cara klik link di bawah ini<br>
			<a href='$urlVerifikasi'>$urlVerifikasi</a>";
			
			$pesan2= "
			<h2>Pendaftaran</h2>
			<br>Pendaftaran berhasil, kami telah mengirimkan email verifikasi pendaftaran ke alamat email $mailTo, email terkirim dalam waktu 0-60 menit.
			<br><br>Silahkan cek email Anda dan lakukan verifikasi pendaftaran dengan cara klik verifikasi email di email Anda. 
			 
			";

		} else {
			//kirim ulang
			$bodyMail="
			Verifikasi Pendaftaran.
			<br>Silahkan melakukan verifikasi pendaftaran dengan cara klik link di bawah ini<br>
			<a href='$urlVerifikasi'>$urlVerifikasi</a>";

			$pesan2= "
			 
			<h2>Verifikasi Email Pendaftaran</h2>
			<br> 
			<br>Pengiriman email verifikasi berhasil. 
			<br>Silahkan cek email untuk melakukan verifikasi. 
			";
			
		}
		
		global $toroot,$lib_path,$um_path,$nfgb,$signature2,$systemMail,$vDomain;
		include $um_path."kirim-email.php";
		
		$t.= "<section class='section' style='padding:20px;text-align:center'>$pesan2";
		if (!$isOnline) $t.= $bodyMail;
		$t.="<br><br>Status Email :<br> ".$komentar_email;
		$t.= "</section>";
		
		return $t;
	}
	
	function verifikasiEm(){
		global $tkn,$vid;
		$t='';
		$vid=0;
		cekVar("tkn");
		evalToken3($tkn);
		if ($vid*1>0) {
			$tgls=date("Y-m-d H:i:s");
			global $id2,$vpass,$nama,$jenisreg,$vemail,$stat,$vhp;
			extractRecord("select id as id2,nama,vpass,stat,jenis as jenisreg,vhp ,vemail  from tbregistrasi where id=$vid  ");
			if ($id2=="") {
				//tidak ada di database 
			} else {
				//jika ada 
				if ($stat=="unverified") {
					$sq="update tbregistrasi set stat='verified',tglverifikasi='$tgls' where id='$vid'";	
					$h=mysql_query($sq);
					if ($h) {
						//jenisreg=guru
						$pes= "Verifikasi pendaftaran berhasil, silahkan lakukan login.<br>";
					} else {
						
					}
				} else {
					$pes= "User sudah terverifikasi.[stat:$stat],  silahkan lakukan login<br>";
				}
				//cari di data guru
				$cg=carifield("select id from tbguru where vemail='$vemail'");
				if ($cg=="") {
					$sq="insert into tbguru(nama,vpass,vemail,vhp,vidreg) values('$nama','$vpass','$vemail','$vhp','$id2');";
					$h=mysql_query($sq);	
				}
			
				
				$this->pesLogin=$pes;
				$t.=$this->showFrmLogin();
				//$t.=$this->scriptJS;
				//$t.=$this->scriptCSS;
				return $t;
			}
		} else {
			$t.= "Mohon maaf, token tidak valid....";
			return $t;
		}
	}
	
}
?>