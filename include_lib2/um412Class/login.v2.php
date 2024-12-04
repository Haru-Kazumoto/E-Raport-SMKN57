<?php
//require_once $um_class."login.php";
/*		$aTbLogin=[
				[ "tb"=>"tbpembantu",
					"sy"=>"jpembantu='PL'",
					"fldid"=>"id",
					"flduid"=>"vuserid",
					"flduname"=>"nama",
					"fldpss"=>"vpass",
					"usrtype"=>"marketing",
					//"fldtype"=>"marketing",
					"usrlv"=>5,
				]
			];
*/
class login {
	public $aTbLogin;
	public $sql;
	public $op;
	public $isLogin=false;
	public $defNmSekolah="";
	public $tbLogin="registrasi";
	public $vidusr=0;
	public $vidgu=0;
	public $defUserType='tamu';
	public $defKdSekolah="";
	public $defKdKelas="";
	public $defTingkat=0;
	public $defKdGuru=0;
	public $defNmGuru="";
	public $useVerifikasi=false;
	
	public $nextLoad="index.php";
	public $pesLogin="";		
	public $autoClosePesLogin=true;
	public $timeoutPesLogin=4000;
	public $addTitle="";
	
	//field-field yang digunakan di register
	public $sFldRegister="nama,email,hp,pass";
	public $sFldUserid="email";

	public $userid="";
	public $userName="";
	public $userType="";
	public $levelOwner=0;
	
	public $loginPath="login/";
	public $urlLoginSuccess="index.php";
	public $urlLogin="index.php";
	public $urlActLogin="index.php?page=login&"; 
	
	public $nfApp="";
	public $nfAppB="";
	
	public $loginHeader="";		
	public $frmLogin="";
	public $frmRegister="";
	public $sesi=[];
	
	public $aIpBlocked=array();     
	public $aIpAllowed=array();
	public $sUserTypeRecorded="admin,sa,guru,content,tamu";//user yg akan direcord ipnya
	public $sUserTypeAllowed="admin,content,sa";//user yang diperbolehkan dalam aipallowed	
	public $needRegisterVerification=false;
	public $css;
	
	public function __construct() {
		global $aTbLogin;
		if (!isset($aTbLogin)) {
			$aTbLogin=[
			[ "tb"=>"tbuser",
				"sy"=>"",
				"fldid"=>"id",
				"flduid"=>"vuserid",
				"flduname"=>"vusername",
				"fldupss"=>"vpass",
				"fldutype"=>"vusertype",
				//"usrtype"=>"marketing",
				"ulv"=>10,
				],
				];
		
		}
		$this->aTbLogin=$aTbLogin;
		global $appId;
		if (isset($_SESSION[$appId])) $this->sesi=$_SESSION[$appId];
		echo showTA($this->sesi);exit;
		
		//echo "login dipanggil";exit;
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
	
	function clearCacheLogin(){
		unset($_COOKIE['ses']);
		setcookie('ses', '', time() - 3600, '/');
					
	}
	function setCacheLogin($ups){
		global $timeoutSesi;
		$bts=time()+$timeoutSesi*60;
		
		$vsesi=encod("$this->vidusr|$this->userid|$this->userName|$this->userType|$ups");
		setcookie("ses",$vsesi,$bts);
		$_COOKIE["ses"]=$vsesi;
		
	}
	function userLogout() {
		global $userid,$isLogin,$appId;
		$isLogin=false;
		$this->updateStatUser(0);
		//echo "$userid mau logout"; exit;
		//global $this->userid,$this->userid,$this->userName,$this->userType,$this->isLogin;
		$userid=$this->userid=$this->userid=$this->sesi["usrid"]="Guest";
		$userName=$this->userName=$this->sesi["usrnm"]="Guest";
		$userType=$this->userType=$this->sesi["usrtype"]="Guest";
		$vidusr=$this->sesi["vidusr"]=0;
		/*unset($this->sesi["usrid"]);
		unset($this->sesi["usrnm"]);
		unset($this->sesi["usrtype"]);
		unset($this->sesi["vidusr"]);
		*/
		$_SESSION[$appId]=$sesi;
		//$this->clearCacheLogin();
		$this->isLogin=false;
		
		global $newJS;
	
		/*
		//echo "logout berhasil....";
		if ($newJS)
			redirection($this->urlSuccess);
		else
			echo 1;
		
		*/
		//exit;
	}
	
	function logout($redirect=0) {
		$this->userLogout();
		if ($redirect) {
			redirection("index.php");
		}
	}
	
	//optcek: 0:result:true/false,1:showfrmlogin is false,2:result:peslogin
	function cekLogin($optcek=1) {
		global $levelOwner,$userid,$userName,$userType,$defKdGuru,$vidgu,$vidusr,$isLogin,$isTest,$appId;
		$newLogin=false;
		$uid=$ups="";
		$this->pesLogin="Userid/Email atau password salah";
		if (isset($_SESSION[$appId])) $this->sesi=$_SESSION[$appId];
		
		if (isset($_REQUEST["usrid"])) {
			$newLogin=true;
			$uid=$_REQUEST["usrid"];
			$ups=$_REQUEST["usrps"];
			//$jform=$_REQUEST["jform"];
			//$this->pesLogin="Userid/Email atau password salah";
			$this->isLogin=false;
			
			if (!$this->isLogin) {
				$jtb=0;
				foreach($this->aTbLogin as $xxtb) {
					if ($this->isLogin) continue;	
					$jtb++;
					$atb=(object)$xxtb;
					$tb=$atb->tb;
					if ($isTest) echo "<br>mencari di $tb";
					$fldid=$atb->fldid;
					$flduid=$atb->flduid;
					$fldupss=$atb->fldupss;
					$flduname=$atb->flduname;
					$ulv=$atb->ulv;
					$xsy=$atb->sy==""?"":"and ".$atb->sy ;
					$sqlusr="select * from $tb where $flduid='$uid' and $fldupss='".md5($ups)."' $xsy ";
					$hasilusr=mysql_query($sqlusr) or die("err:tbu");
					while ($rwusr=mysql_fetch_array($hasilusr)) {
						$this->vidusr=$rwusr[$fldid];
						$this->userid=$rwusr[$flduid];
						$this->userName=$rwusr[$flduname];
						$this->isLogin=true;
						$this->levelOwner=$ulv;
						if (isset($atb->fldutype)) {
							$fut=$atb->fldutype;
							$this->userType=$rwusr[$fut]; 
						} else {
							$this->userType=$atb->utype;							
						}
					}
				}
				//echo  "jtb $jtb > ".$sqlusr;exit;
					
			}
			
				
			if ($this->isLogin) {
				//unset($_REQUEST);
			//simpan sesi
				//@session_start();
				//echo "user password sdh bener";exit;
				
				$userid=$this->userid;
				$userName=$this->userName;
				$vidusr=$this->vidusr;
				$userType=$this->userType;
				$levelOwner=$this->levelOwner;
				
				$isLogin=true;
				resetTimeoutSesi();
				
				
				$this->sesi["vidusr"]=$this->vidusr;
				$this->sesi["usrid"]=$this->userid;
				$this->sesi["usrnm"]=$this->userName;
				$this->sesi["usrtype"]=$this->userType;
				$this->sesi["usrlo"]=$this->levelOwner;
				$_SESSION[$appId]=$this->sesi;
				//$this->sesi["usrstartedlogin"]=date('YmdHis');
				
				//jika remember
				if (isset($_REQUEST['usrremember'])) {
					$this->setCacheLogin($ups);
					/*
					setcookie('vidusr',$this->vidusr,$bts);
					setcookie("uid",$this->userid,$bts);
					setcookie("uname",$this->userName,$bts);
					setcookie("utp",$this->userType,$bts);
					setcookie("ups",encod($ups),$bts);
					setcookie("to",$timeoutSesi,$bts);
					$_COOKIE["vidusr"]=$this->vidusr;
					$_COOKIE["uid"]=$this->userid;
					$_COOKIE["uname"]=$this->userName;
					$_COOKIE["utp"]=$this->userType;
					$_COOKIE["ups"]=encod($ups);
					$_COOKIE["to"]=$timeoutSesi;
					*/
					
									
					
					
				//	exit;
				} else {
					/*
					unset($_COOKIE['vidusr']);
					setcookie('vidusr', '', time() - 3600, '/');
					unset($_COOKIE['usrid']);
					setcookie('usrid', '', time() - 3600, '/');
					unset($_COOKIE['usrnm']);
					setcookie('usrnm', '', time() - 3600, '/');
					unset($_COOKIE['usertype']);
					setcookie('usertype', '', time() - 3600, '/');
					*/
					$this->clearCacheLogin();
				
				}
				
				
				echo um412_falr("<center>".$this->pesLogin="Login Sukses"."</center>","success");
				$levelOwner=$this->levelOwner;
				
				echo "sampai sini lho $this->urlLoginSuccess ";
				exit;
				redirection($this->urlLoginSuccess,1);				
				exit;
				//redirection("index.php");
			} else {
				//$this->pesLogin="User atau Password <br>tidak dikenal ";
				if ($optcek==1)  echo $this->showFrmLogin();
				//$this->isLogin;
			}
			//return $this->isLogin;
		} 
		elseif (isset($_SESSION[$appId])) {
			$this->sesi=$_SESSION[$appId];			
			if (!isset($this->sesi["viduasr"])) {
				$this->logout(1);
			}
			if (!isset($this->sesi["usrid"])) {
				$this->logout(1);
				
			}
			$userid=$this->userid=$this->sesi["usrid"];
			$userName=$this->userName=$this->sesi["usrnm"];
			$vidusr=$this->vidusr=$this->sesi["vidusr"];
			$userType=$this->userType=$this->sesi["usrtype"];
			$levelOwner=$this->levelOwner=$this->sesi["usrlo"];
			$isLogin=$this->isLogin=($this->userid=='Guest'?false:true);
		} else {
			$isLogin=$this->isLogin=false;
			$uid='';
			$ups='';
		}	$this->pesLogin="";	
		
		//echo "islogin $isLogin $userid";

		
		$vidgu=$this->vidgu=0;
		if ($this->isLogin) {
			resetTimeoutSesi();
			
			$this->isAdmin=(usertype('admin')?true:false);
			/*
			if (usertype("siswa")) {
				$sq="select s.kdkelas as defKdKelas,tingkat as defTingkat,s.kdguru as vidgu from 
				tbsiswa s inner join tbguru g on s.kdguru=g.id  where s.vuserid='$this->userid'";
				extractRecord($sq);
			} 
			elseif (usertype("guru")) { 
				extractRecord("select id as defKdGuru,nama as defNmGuru from tbguru where vemail='$this->userid'");
				$vidgu=$this->vidgu=$defKdGuru;
			}
			*/
			$this->secureLogin(1);
			$this->updateStatUser(1);
			
			if (usertype("admin,sa")){
				$this->updateStatUser(2);	
			}
			
		}
		
		if ($optcek==2) 
			return 	$this->pesLogin;
		else
			return $this->isLogin;
	}
		
	function updateStatUser($stat=1){
	   if (count($this->aTbLogin)<=0) {
			global $aTbLogin;
			if (isset($aTbLogin)) $this->aTbLogin=$aTbLogin;
	   } else {
		   $this->aTbLogin=[];
	   }
	   
	   //echo "isi fungsi updateStatUser >>";	
	   	date_default_timezone_set('Asia/Bangkok');
		$tgls=date("Y-m-d H:i:s");
		
		if ($stat==2) {
			//autologout untuk semua
			$tglb=date("Y-m-d H:i:s",strtotime($tgls)-15*60);
			foreach ($this->aTbLogin as $xxtb) {
				$atb=(object)$xxtb;
				$tb=$atb->tb;
				$ssq= "update $tb set statuser='0' where lastclick<'$tglb' and statuser=1; ";
			}
			querysql($ssq);
			return "";
		}
		
		global $userid,$usertype;
		if ($stat==0) {
			if (!isset($userid)) {
				//$this->cekLogin();
				
				//$userid=$this->sesi["usrid"];
				//$usertype=$this->sesi["usrtype"];
				
			}
		}
		
		/*
		if (usertype("peserta")) {
			$tbuser="tbregistrasi";
			$nmfield="vemail";
			$sq="update $tbuser set statuser='$stat',lastclick='$tgls' where $nmfield='$userid'  ";
		} else {
			$tbuser="tbuser";
			$nmfield="vuserid";
			$sq="update $tbuser set statuser='$stat',lastclick='$tgls' where $nmfield='$userid' or vemail='$userid' ";
			//return false;
		}
		mysql_query($sq);
		*/
		//echo $sq;exit;
	}
	
	function showFrmForgot(){
		global $rnd,$em,$callCenter;
		$t="";
		$idform="flg$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		$ons="";
		$frm=new htmlForm;
		$frm->nmForm="frmL";
		$frm->useAjaxSubmit=true;
		$frm->nfAction=$this->urlActLogin;
		$frm->awalForm();
		//$frm->rowForm2("",$this->showTitle("Masukkan Alamat Email Anda"));
		$frm->rowForm("em","E-mail","email|60|5|CF|");
		
		$frm->rowForm2("","
		  <div class='form-group form-group-btn has-feedback ' >
			<button type='submit' class='btn-masuk btn btn-primary btn-flat'>Kirim</button>
			<input type='hidden' name='oplg' value='lfgp'>
			<input type='hidden' name='page' value='login'>
			
		  </div>");
		  
		
		$t="";
		$t.=$this->showTitle("
		Masukkan Alamat Email Anda.<br>Jika Anda mendaftar lewat Admin, <br>silahkan hubungi Call Center ($callCenter)");
		$t.="
		<div class='login-box' id='login-box'>
		<div class='login-box-body'>
			  <center>
				<p class='login-box-msg' style=''>$this->pesLogin </p>";
		
		

		
		$t.=$frm->show();
		
		if ($this->pesLogin!='') {
			$t.=fbe( "
			setTimeout(function() {
				$('.login-box-msg').hide('slow');
			},4000);
			");
		}


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
		
		$t.=$this->scriptCSS();
		
		return $t;

	}
	
	function showFrmLogin($showLink=true,$jform="guru"){
		$jform=strtolower($jform);
		//$this->setFrmLogin();//set ulang
		
		global $rnd;
		$t="";
		
		$idform="flg$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		//$ons="";
		
		$frm=new htmlForm;
		$frm->nmForm="frmL";
		$frm->nfAction=$this->urlActLogin;
		$frm->useAjaxSubmit=true;
		$frm->nmValidasi="login";
		$frm->awalForm();
		//@session_start();
		$defusrid=$defusrps=$defusrremember='';
		if (isset($_COOKIE['ses'])) {
			$vsesi=decod($_COOKIE['ses']);//"$this->vidusr|$this->userid|$this->userName|$this->userType|$ups");
			$avsesi=explode("|",$vsesi);
			$defusrid=$avsesi[1];
			$defusrps=$avsesi[4];
			$defusrremember="checked";
			/*
			$defusrid=(isset($_COOKIE['uid'])?$_COOKIE['uid']:'');
			$defusrps=(isset($_COOKIE['ups'])?decod($_COOKIE['ups']):'');
			$defusrremember=(isset($_COOKIE['uid'])?'checked':'');
			*/
		}
		
		//$frm->rowForm2("",$this->showTitle());
		$frm->rowForm2("usrid","
			  <div class='form-group has-feedback'>
					<input type='text' required minlength=2 class='form-control' 
					placeholder='E-mail/User ID' name='usrid' id='usrid$rnd' 
					title='Silahkan masukkan userid/email' value='$defusrid' >
					<div style='display:none' id='usrid-err' ></div>
					<span class='fa fa-user form-control-feedback'></span>
				  </div>
			");
		$frm->rowForm2("usrps","
		  <div class='form-group has-feedback'>
					<input type='password' required minlength=6  class='form-control' 
					placeholder='Password' name='usrps' id='usrps$rnd' value='$defusrps'
					title='Silahkan masukkan password, minimal 6 huruf'>
					<div style='display:none' id='usrps-err' ></div>
					<span class='fa fa-lock form-control-feedback'></span>
				  </div>
				");
		$frm->rowForm2("usrremember","
				<div class='form-group txtr'>
                        <span class='txt-remember'>
							<input type='checkbox' name='usrremember' $defusrremember > Ingatkan Saya
                        </span>
						<i class='pull-right togglesp fa fa-eye' id=tsp$rnd data='usrps$rnd' onclick=\"toggleSP('usrps$rnd','tsp$rnd');\" ></i>
		        </div>");
		global $clsLogin;
		$frm->rowForm2("","
			  <div class='form-group has-feedback' style='margin-bottom:0px'>
					<button type='submit' class='btn-masuk btn btn-$clsLogin btn-flat'>Masuk</button>
					<input type='hidden' name='oplg' value='lp'>
					<input type='hidden' name='page' value='login'>
					<input type='hidden' name='jform' value='$jform'>
				  </div>
			"); 
		$t="";
		$t.=$this->showTitle();
		$lbm='';
		if (strstr($this->pesLogin,"(1)")!='') {
			//pesan sukses
			$lbm='login-box-msg-success';
			$this->pesLogin=str_replace("(1)","",$this->pesLogin);
		}
		$t.="
		<div id='ts$idform' style='display:none'></div>
		<div class='login-box' id='login-box'>
			<div class='login-box-body'>
			  <center>
				<p class='login-box-msg $lbm' >$this->pesLogin</p>
			";

		$t.= $frm->show();
		$t.="	  
				</center>
			</div>
		</div>
		";
		
		if ($showLink) {
			//	<a href=\"$this->urlActLogin"."&oplg=lfg\"  >Lupa Password</a> |
			global $idtd;
			$t.="
			<div class='reg-pesn'>
				Belum pernah mendaftar? klik 
				<a href=# onclick=\"bukaAjax('$idtd',
				'$this->urlActLogin"."&oplg=lrg',0);return false;\" >Daftar</a>
			</div>";
		}
		if ($this->pesLogin!='') {
			if ($this->autoClosePesLogin) {
				$t.="
					<script>
					setTimeout(function() {
						$('.login-box-msg').hide('slow');
					},$this->timeoutPesLogin);
					</script>
				";
			}
		}
		
		
		$this->frmLogin=$t;
	
		return $this->frmLogin;
	}
	
	function showFrmRegister() {
		global $rnd;
		$rnama=$rusrem=$rusrhp='';
		
		//$sFldRegister
		cekVar("rnama,rusrem,rusrhp,rusrps,rusrps2,rprofesi");
		
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
		
		//$isiProfesi=um412_isiCombo6("RA1:Mahasiswa PKN STAN, Pegawai Kemenkeu, Pegawai KLP","rprofesi");
		$isiProfesi="
		<span id='gcbrprofesi_$rnd' 
		style='display:inline-block;height:130px !important;' 
		class='group-radio form-control'>
		
		<span class='cbradio-item'>
			<input type='radio' name='R_rprofesi_$rnd' id='R_rprofesi_$rnd"."_0' 
			value='Mhs.PKN STAN' onchange=\"getValueRadio('R_rprofesi_$rnd');\" 
			onkeyup=\"getValueRadio('R_rprofesi_$rnd');\"> 
			<span class='cbradio cbradio-rprofesi'>Mahasiswa PKN STAN</span> 
		</span>
		<br><span class='cbradio-item'>
			<input type='radio' name='R_rprofesi_$rnd' 
			id='R_rprofesi_$rnd"."_1' value='Peg. Kemenkeu' 
			onchange=\"getValueRadio('R_rprofesi_$rnd');\" 
			onkeyup=\"getValueRadio('R_rprofesi_$rnd');'\" > 
			<span class='cbradio cbradio-rprofesi'>Pegawai Kemenkeu</span> 
		</span>
		<br><span class='cbradio-item'>
			<input type='radio' name='R_rprofesi_$rnd' 
			id='R_rprofesi_$rnd"."_2' 
			value='Peg.KLP' 
			
			onchange=\"getValueRadio('R_rprofesi_$rnd');\" 
			onkeyup=\"getValueRadio('R_rprofesi_$rnd');\"> 
			<span class='cbradio cbradio-rprofesi'>Pegawai KLP</span> 
		</span>
		<br>
		<span class='cbradio-item'>
			<input type='radio' 
			name='R_rprofesi_$rnd' 
			id='R_rprofesi_$rnd"."_3' 
			value='' 
			checked='' 
			onchange=\"getValueRadio('R_rprofesi_$rnd','other');\" 
			onkeyup=\"getValueRadio('R_rprofesi_$rnd');\"
			> 
			<span class='cbradio cbradio-rprofesi','other'>Lain</span> 
		</span>
		<br>
		<input type='text' class='form-control'
			placeholder='Profesi Lain' name='rprofesi_other' 
		id='rprofesi_$rnd"."_other' value='' 
		onkeyup=\"v=$('#rprofesi_$rnd"."_other').val();
		$('#R_rprofesi_$rnd"."_3').val(v);
		$('#R_rprofesi_$rnd"."_3').attr('checked',true);
		$('#rprofesi_$rnd').val(v);
		
		//getValueRadio('r_rprofesi_$rnd');
		\">
		
		</span>
		<input type='hidden' class='form-control' minlength='3' required='' 
		placeholder='Profesi' name='rprofesi' id=rprofesi_$rnd value='' 
		title='Silahkan masukkan Profesi, minimal 3 huruf'>
						
						<div style='display:none' id='rprofesi-err'></div>
						<span class='fa fa-doctor form-control-feedback'></span> 
		";

		$idform="frg$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		$ons="";
		
		$frm=new htmlForm;
		$frm->useAjaxSubmit=true;
		$frm->nmForm="frmR";
		$frm->nmValidasi="registrasi";
		$frm->nfAction=$this->urlActLogin."&oplg=lrgp";
		
		$frm->awalForm();
		$frm->rowForm("rnama","Nama Lengkap","text|60|4|CF|fa-user");
		$frm->rowForm("rusrem","E-mail","email|60|6|CF|fa-envelope");
		$frm->rowForm("rusrhp","No. HP/WA","text|60|8|CF|fa-phone");
		//$frm->rowForm("rprofesi","Profesi","text|60|8|CF|fa-doctor");
		$frm->addTxtBody('
		<div class="form-group has-feedback">'.$isiProfesi.'
		</div>
		');
		
		//$frm->rowForm("rusrid","User ID","text|60|6|CF|fa-user");
		$frm->rowForm("rusrps","Password","password|30|6|CF|fa-lock");
		$frm->rowForm("rusrps2","Konfirmasi Password","password|30|6|CF|fa-lock");
		
		//<a href=\"$this->urlActLogin"."&oplg=lfg\"  >Lupa Password</a> |
		global $idtd;
		$frm->rowForm2("","			  
				  <div class='form-group has-feedback' style='margin-bottom:0px'>
					<button type='submit' class='btn-masuk btn btn-primary btn-flat'>Daftar</button>
					<input type='hidden' name='oplg' value='lrgp'>
				</div>
 
					 
					
		<div class='reg-pesn'>
			Sudah pernah mendaftar? klik 
			<a href=#
			
					<a href=# onclick=\"bukaAjax('$idtd',
				'$this->urlActLogin',0);return false;\" >Login</a>
		</div>
		");
		

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
	
	function resetPwd($tkn){
		//proses reset password
		global $rnd,$vid,$oplg,$usrps;
		cekvar("usrps");
		if ($vid==0) return;
		//echo "vid $vid password baru:$usrps";
		$sq="update tbguru set vpass=md5('$usrps') where id='$vid'";
		$h=mysql_query($sq);
		if ($h) {
			echo um412_falr("Password baru berhasil diset.","success");
			echo "<center>Silahkan <a href='$this->urlLogin' target=_blank>klik di sini</a> untuk login.</center><br><br>";
		} else {
			return "Err.App.ResetPwd";
		}
	}
	
	function showFrmReset($tkn){
		//$this->setFrmLogin();//set ulang
		global $rnd,$vid,$oplg;
		cekvar("op2");
		
		if ($vid==0) {
			echo um412_falr("Link tidak valid...");
			exit;
		} else {
			$sq="select id from tbguru where id='$vid'";
			$vid=carifield($sq)*1;
			if ($vid==0) {
				echo um412_falr("Link tidak valid..2.");
				exit;
			}
		}
		
		
		$t="";
		
		$idform="frs$rnd";
		$ons="onsubmit=\"ajaxSubmitAllForm('$idform','ts$idform','login');return false;\" ";
		$ons="";
		
		$frm=new htmlForm;
		$frm->nmForm="frmL";
		$frm->nfAction=$this->urlActLogin."&oplg=rstPwp";
		$frm->awalForm();
		
		$tkn=makeToken("r=".date("Ymd")."&vid=$vid");
		//$frm->rowForm2("",$this->showTitle("Silahkan masukkan password baru"));
		
		$frm->rowForm2("usrps","
		  <div class='form-group has-feedback'>
					<input type='password' required minlength=6  class='form-control' 
					placeholder='Password' name='usrps' id='usrps$rnd'
					title='Silahkan masukkan password, minimal 6 huruf'>
					<div style='display:none' id='usrps-err' ></div>
					<span class='fa fa-lock form-control-feedback'></span>
				  </div>
				");
		$frm->rowForm2("usrps2","
		  <div class='form-group has-feedback'>
					<input type='password' required minlength=6  class='form-control' 
					placeholder='Konfirmasi' name='usrps2' id='usrps2$rnd'
					title='Silahkan masukkan konfirmasi password, minimal 6 huruf,konfirmasi harus sama dengan password' 
					equalto='#usrps$rnd'>
					<div style='display:none' id='usrps2-err' ></div>
					<span class='fa fa-lock form-control-feedback'></span>
				  </div>
				");
		$frm->rowForm2("","
			  <div class='form-group has-feedback' style='margin-bottom:0px'>
					<button type='submit' class='btn-masuk btn btn-primary btn-flat'>Reset</button>
					<input type='hidden' name='tkn' value='$tkn'>
				  </div>
			"); 
			
		$t.=$this->showTitle("Silahkan masukkan password baru");
		
		$t.="
		
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
		$showLink=false;
		if ($showLink) {
			//	<a href=\"$this->urlActLogin"."&oplg=lfg\"  >Lupa Password</a> |
			$t.="
			<div class='login-pesn'>
				<a href=\"$this->urlActLogin"."&oplg=lrg\" >Mendaftar</a>
			</div>";
		}
		if ($this->pesLogin!='') {
			$t.="
				<script>
				setTimeout(function() {
					$('.login-box-msg').hide('slow');
				},4000);
				</script>
			";
		}
		
		return $t;
	}
	
	
	
	function scriptJS() {
		$t="";
		return $t;
	}
	
	function scriptCSS($css="") {
		if ($css=="")
			return $this->css;
		else {
			$this->css=$css;
			return $this->css;
		}
	}
	
	function cekFrmRegister(){
		global $rnama,$rusrem,$rusrhp,$rusrps,$rusrps2,$rusrid ,$rprofesi;
		cekVar("rnama,rusrem,rusrhp,rusrps,rusrps2,rprofesi");
		//var_dump($_REQUEST);
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
				if ($c!="")		
					$pes.="<br>Alamat Email Sudah digunakan ...";
				else {
					$c=carifield("select vemail from tbguru where vemail='$rusrem'");
					if ($c!="")	{
						$pes.="<br>Alamat Email Sudah digunakan ...";
					} 
				}
			}
		}
		
		if ($rusrhp=="") {
			$pes.="<br>Nomor HP Harus diisi";
		}
		
		if ($rprofesi=="") {
			$pes.="<br>Profesi harus diisi/dipilih";
		}

		/*
		if ($rusrid=="") {
			$pes.="<br>User ID Harus diisi";
		} else {
			if (!validasiUID($rusrid)) {
				$pes.="<br>User id tidak valid, harus mengandung : angka,huruf, tidak boleh menggunakan tanda baca.";
				
			} else {	
				$c=carifield("select vuserid from tbregistrasi where vuserid='$rusrid'");
				if ($c!="")	{
					$pes.="<br>User ID Sudah digunakan ...";
				} else {
					$c=carifield("select vuserid from tbguru  where vuserid='$rusrid'");
					if ($c!="")	$pes.="<br>User ID Sudah digunakan ...";
				}
			}
		}
		*/

		$vp=strip_tags(validasiPassword($rusrps,$rusrps2,$soption="W06",$separator='')); 
		if ($vp!='')$pes.="<br>$vp";
		global $opcek,$ip;
		if ($opcek==1) {
			return $pes;
		}
		
		if ($pes=="") {
			$sq="insert into tbregistrasi(vemail,vhp,vpass,nama,ip,profesi) 
			values('$rusrem','$rusrhp',md5('$rusrps'),'$rnama','$ip','$rprofesi');";
			$h=mysql_query($sq);
			if ($h) {
				$vid=mysql_insert_id();
				if ($this->useVerifikasi) {
					$jenisv="pendaftaran";	
					$t.=$this->kirimVerifikasi($jenisv,$vid,$rusrem);
				} else {
					$t.="Pendaftaran berhasil";
					$jenisv="pendaftaran";	
					
					
					
					$t.=$this->kirimNotifikasi($jenisv,$vid,$rusrem);
					return $t;
					
					//return json_encode([1,'Pendaftaran berhasil']);
				}
			} else {
				$t.= "Pendaftaran Tidak Berhasil...";
				return $t;
				//return json_encode([0,'Pendaftaran tidak berhasil']);
				
			}
		} else {
			$this->pesLogin=$pes;
			$t.=$this->showFrmRegister();
			return $pes;
			//return json_encode([0,$pes]);
		}
		return 0;//$t;
	}
	function cekVerifikasi(){
		global $tkn,$vid,$nama,$vpass,$stat,$jenisreg,$vemail,$vhp;
		//echo "<br>Melakukan verifikasi....<br>";
		$vid=0;
		cekVar("tkn");
		evalToken($tkn);
		if ($vid*1>0) {
			$tgls=date("Y-m-d H:i:s");
			extractRecord("select id as id2,nama,vuserid,vpass,stat,jenis as jenisreg,
			vemail,vhp
			from tbregistrasi where id=$vid  ");
			if ($id2=="") {
				//tidak ada di database
				
			} else {
				//jika ada 
				if ($stat=="unverified") {
					$sq="update tbregistrasi set stat='verified',tglverifikasi='$tgls' 
					where id='$vid'";	
					$h=mysql_query($sq);
					if ($h) {
						//jenisreg=guru
						$c=carifield("select id from tbguru where idreg='$vid'");
						if ($c=='') {
							echo $sq="insert into tbguru(vuserid,nama,vpass,vemail,vhp,idreg) 
							values('$vuserid','$nama','$vpass','$vemail','$vhp','$vid');";
							$h=mysql_query($sq);							
							$pes= "Verifikasi pendaftaran berhasil, silahkan lakukan login.<br>(1)";
						} else {
							$pes= "User sudah terverifikasi sebelumnya.<br>";
						}
						
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
		if ($userid=="") return ;
		$s="";
		extractRecord("select id as idip,svuid,jlhip  from tbh_logip where ip='$ip'");
		if ($idip=="") {
			$s="insert into tbh_logip(ip,svuid,jlhip) values('$ip','$userid/$userType',1);";
		} else {
			
			if (strstr($svuid, $userid)=="")
				$s="update  tbh_logip set svuid=concat(svuid,',','$userid/$userType'),jlhip=jlhip+1 where ip='$ip';";
			else {
				$s="";//nggak perlu update/insert
			}
		}
		if ($s!='') mysql_query($s);
	 
	}

	function registerClick($sUserType="",$aipAllowed=array()){
		global $pauseRegClick,$ip,$userid,$idip,$svuid,$jlhip,$userType,$det;
		if (!isset($pauseRegClick)) $pauseRegClick=false;
		if ($pauseRegClick) return ;
		
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
			$this->logout(1);
		} else if (strstr($sOptAct,"I")!="")  {
			redirection("index.php");
			exit;
		} else if (strstr($sOptAct,"E")!="")  {
			exit;
		} 

	}

	function secureLogin($force=0){
		global $userid,$userType,$isLogin;
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
				$this->registerClick("sa,admin,guru,content",$aip2); 	 
			}
			//-akhir security
			
		} else {
			$isIPAllowed=true;	//jika offline semua ip boleh
		} 
	}
	
	function kirimResetPwd($mailTo){
		global $folderHost,$judulWeb,$vDomain,$judulWeb,$webmasterMail,$webmasterName,$um_path,$isOnline;
		$t='';
		$subjectMail="Reset Password - $judulWeb";
		//$mailTo=$rusrem;
		$fromMail=$webmasterMail;
		$fromName=$judulWeb;
		$nmFileAttachment="";
		$sq="select id from tbguru where vemail='$mailTo' limit 0,1";
		$vid=carifield($sq)*1;	
		if ($vid==0) {
			$pes="Mohon maaf, Email tidak dikenal atau belum terdaftar";
			if ($isOnline) {
				return um412_falr($pes);
				exit;
			}
			//echo "nggak ketemu";
			$this->pesLogin=$pes;
			echo $this->ShowFrmForgot();
			exit;
		}
		$tkn=makeToken("r=".date("Ymd")."&vid=$vid&oplg=rstPw");
		//$urlVerifikasi="http://$vDomain/".$this->urlActLogin."&oplg=verifikasiEm&tkn=$tkn";
		$urlReset=$folderHost."index.php?page=login&tkn=$tkn";
			//kirim ulang
			$bodyMail="
			Reset Password.
			<br>Silahkan klik link berikut ini untuk melakukan reset password<br>
			<a href='$urlReset'>$urlReset</a>";

			$pesan2= "
			 
			<h2>Reset Password</h2>
			<br> 
			<br>Link reset password telah berhasil dikirim ke email. 
			<br>Silahkan cek email untuk melanjutkan reset password. 
			";
			
		
		global $toroot,$lib_path,$um_path,$nfgb,$signature2,$systemMail,$vDomain;
		include $um_path."kirim-email.php";
		
		$t.= "<section class='section' style='padding:20px;text-align:center'>$pesan2";
		if (!$isOnline) {
			//$t.="Cek Email Offline:". $bodyMail;
			$t.="<br><br>Status Email :<br> ".$komentar_email;
		}
		$t.= "</section>";
		
		return $t;
	}
	
	function createLinkVerifikasi($jenisv='pendaftaran',$vid){
		global $vDomain,$folderHost,$tkn;
		$tkn=makeToken("r=".date("Ymd")."&vid=$vid");
		//$urlVerifikasi="http://$vDomain/"."index.php?page=verifikasiEm&tkn=$tkn";
		$urlVerifikasi=$folderHost."index.php?page=login&oplg=verifikasiEm&tkn=$tkn";
		return $urlVerifikasi;
	}
	function kirimVerifikasi($jenisv='pendaftaran',$vid,$mailTo){
		global $callCenter,$folderHost,$vDomain,$judulWeb,$webmasterMail,$webmasterName,$um_path,$isOnline;
		global $debugMode;
		$t='';
		$subjectMail="Verifikasi Pendaftaran - $judulWeb";
		//$mailTo=$rusrem;
		$urlVerifikasi=$this->createLinkVerifikasi($jenisv,$vid);
		
		$fromMail=$webmasterMail;
		$fromName=$webmasterName;;
		$nmFileAttachment="";

		if ($jenisv=="pendaftaran") {
			$bodyMail="Pendaftaran berhasil.
			<br>Silahkan melakukan verifikasi pendaftaran dengan cara klik link di bawah ini<br>
			<a href='$urlVerifikasi'>$urlVerifikasi</a>";
			
			$pesan2= "
			<h2>Pendaftaran</h2>
			<br>Pendaftaran berhasil, kami telah mengirimkan email verifikasi pendaftaran ke alamat email $mailTo, 
			email terkirim dalam waktu 0-60 menit.
			<br><br>Silahkan cek email Anda dan lakukan verifikasi pendaftaran dengan cara klik verifikasi email di email Anda. 
			";

		} else {
			if ($vid==0) {
				$vid=carifield("select id from tbguru where vemail='$mailTo' limit 0,1")*1;
				if ($vid==0) {
					echo um412_falr("Email tidak dikenal","danger");
					exit;
				}
			}
			//kirim ulang
			$bodyMail="
			Verifikasi Pendaftaran.
			<br>Silahkan melakukan verifikasi pendaftaran dengan cara klik link di bawah ini<br>
			<a href='$urlVerifikasi'>$urlVerifikasi</a>
			
			<br>Anda mengalami kesulitan? Hubungi call center kami di $callCenter.
			";

			$pesan2= "
			 
			<h2>Verifikasi Email Pendaftaran</h2>
			<br> 
			<br>Pengiriman email verifikasi berhasil. 
			<br>Silahkan cek email untuk melakukan verifikasi. 
			";
			
		}
		
		
		global $terkirim,$toroot,$logMail,$lib_path,$um_path,$nfgb,$signature2,$systemMail,$vDomain,$komentar_email;
		
		include $um_path."kirim-email.php";
		if (!$terkirim) $pesan2="Pengiriman email tidak berhasil.<br> Pesan: $komentar_email"; 
		$t.= "<section class='section' style='padding:20px;text-align:center'>
		$pesan2
		<br>
		<br>Anda mengalami kesulitan? Hubungi call center kami di <b>$callCenter</b>.			
		";
		if (!$isOnline) {
			$t.= $bodyMail;
			$t.="<br><br>Status Email :<br> ".$komentar_email;
			$t.=$logMail;
		}
		
		$t.= "</section>";
		
		return $t;
	}
	
	function kirimNotifikasi($jenisv='pendaftaran',$vid,$mailTo){
		global $callCenter,$folderHost,$vDomain,$judulWeb,$webmasterMail,$webmasterName,$um_path,$isOnline;
		global $rnama,$rusrem,$rusrhp,$rusrps,$rusrps2,$rusrid,$rprofesi ;
		global $debugMode;
		$t='';
		$subjectMail="Pendaftaran Pengunjung $judulWeb";
		//$mailTo=$rusrem;
		//$urlVerifikasi=$this->createLinkVerifikasi($jenisv,$vid);
		
		$fromMail=$webmasterMail;
		$fromName=$webmasterName;;
		$nmFileAttachment="";

		$bodyMail="Pendaftaran berhasil.
		<br>
		<table>
		<tr><td>Nama Lengkap </td><td>: $rnama</td></tr>
		<tr><td>E-Mail </td><td>: $rusrem</td></tr>
		<tr><td>Nomor HP </td><td>: $rusrhp</td></tr>
		<tr><td>Profesi </td><td>: $rprofesi</td></tr>
		<tr><td>Password </td><td>: $rusrps</td></tr>
		</table>
		
		<br>Terima kasih telah melakukan pendaftaran di $judulWeb.
		<br>Gunakan Email dan Password Anda untuk Login di website <a href='$folderHost'>$folderHost</a>
		";
		
		$pesan2= "
		<h2>Pendaftaran</h2>
		<br>Pendaftaran berhasil, kami telah mengirimkan data registrasi Anda ke alamat email $mailTo, 
		Email terkirim dalam waktu 0-60 menit.
		 
		";
		
		global $terkirim,$toroot,$logMail,$lib_path,$um_path,$nfgb,$signature2,$systemMail,$vDomain,$komentar_email;
		global $debugMode;
		include $um_path."kirim-email.php";
		
		//if (!$isOnline) {
		$t.= $bodyMail;
		if ($debugMode) {
			$t.="<br><br>Status Email :<br> ".$komentar_email;
			$t.=$logMail;
		}
		
		//$t.= "</section>";
		
		return $t;
	}
	
	function showTitle($txt="") {
		if ($txt=="") $txt=$this->addTitle;
		if ($txt!='') {
		return "
		 <div class='txt-judul' > 
		$txt
		  </div>";
		}
	}

	
	function verifikasiEm(){
		global $tkn,$vid;
		$t='';
		$vid=0;
		cekVar("tkn");
		evalToken($tkn);
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
				
				/*
				//cari di data guru
				//$cg=carifield("select id from tbguru where vemail='$vemail'");
				$cg=carifield("select id from tbguru where vidreg ='$id2'");
				if ($cg=="") {
					$sq="insert into tbguru(nama,vuserid,vpass,vemail,vhp,vidreg) values('$nama','$vuserid','$vpass','$vemail','$vhp','$id2');";
					$h=mysql_query($sq);	
				} else {
					//aktifkan lagi
					$sq="update  tbguru set inactive=0 where vidreg='$id2'";
					$h=mysql_query($sq);	
					
				}
				*/
				
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

class myProfile {
	public $pathNFProfile;
	public $id,$nama,$nffoto,$nmguru,$vidgu,$email,$nmsekolah,$almsekolah;
	
	
	function init(){
		global $vidusr,$db,$vidgu;
		if (usertype("siswa")) {
			$pathNFProfile="upload/profile/siswa/";
			$sq="select s.id ,s.nama, s.nffoto,
			g.*,g.nama as nmguru,g.id as vidgu from tbsiswa s inner join tbguru g on s.kdguru=g.id where s.id='$vidusr'";
		}else if (usertype("guru")) {
			$pathNFProfile="upload/profile/guru/";
			$sq="select g.*,g.nffoto as nffoto,g.nama as nmguru from tbguru g where g.id='$vidusr'";
		} else {
			$pathNFProfile="upload/profile/user/";
			$sq="select *,'' as nmguru,'' as nffoto,0 as vidgu from tbuser where id='$vidusr'";
			
		}
		//echo $sq;
		$d=$db->query($sq)->fetch();
		if (!isset($d[0])) {			
			@header("parent.location:index.php?op=logout");
			echo um412_falr("Sesi Expired. <a href='index.php?op=logout'>klik disini </a> untuk melanjutkan...",4);
			exit;
		}
		$r=$d[0];
		
		
		$this->pathNFProfile=$pathNFProfile;
		$this->id=$vidusr;
		if (usertype("siswa")) {
			$this->nama=$r['nama'];
		} else {
			$this->nama=$r['nmguru'];
			
		}
			
		$this->nffoto=$r['nffoto'];
		$this->nmguru=$r['nmguru'];
		$this->email=(isset($r['vemail'])?$r['vemail']:'');
		
	}
	
	function showProfile () {
		global $rnd;
		/*
		if (usertype("siswa")) {
			$pathNFProfile="upload/profile/siswa/";
			extractRecord("select s.id,s.nama as nmsiswa,s.nffoto as nffotop,g.*,g.nama as nmguru from tbsiswa s inner join tbguru g on s.kdguru=g.id where s.id='$vidusr'");
		} else { 
			$pathNFProfile="upload/profile/guru/";
			extractRecord("select g.*,g.nffoto as nffotop from tbguru g where g.id='$vidusr'");
		}
		*/
		$tpro="<div style='display:block' class='profilegs'>";
		$rndx=$rnd+2121;
		$imgProfile=($this->nffoto==""?"":createThumb($this->pathNFProfile.$this->nffoto,"",$sukuran="120",$maxw=120));
		$tpro.="
		<div class='pull-right image' style='margin-bottom:-100px'>
				  <img src='$imgProfile' class='img-circle' alt='User Image' style='width:100px;height:100px;'>
				</div>
				";
		if (usertype("siswa")) {
			global $nmsiswa;
			$nmsiswa=$this->nama;
			$tpro.=rowView2("nmsiswa","Nama Siswa",false);
		} else {
			global $nmguru;
			$nmguru=$this->nmguru;
			$tpro.=rowView2("nmguru","Nama ",false);
			global $vemail;
			$vemail=$this->email;
			$tpro.=rowView2("vemail","Email/User ID",false);
		}
		global $nmsekolah;
		$nmsekolah=$this->nmsekolah;
		global $almsekolah;
		$almsekolah=$this->almsekolah;
		
		$tpro.=rowView2("nmsekolah","Nama Sekolah",false);
		$tpro.=rowView2("almsekolah","Alamat Sekolah",false);
		//$tpro.=rowView2("userid","Email/User ID",false);
		$tpro.="<span id='tept$rndx'></span><a href=# onclick=\"bukaAjaxD('tept$rndx',
			'index.php?op=editpro&newrnd=$rndx','width:600','awalEdit($rndx)');return false\" 
			class='pull-right'><i class='fa fa-edit'></i>
			</a>";
		$tpro.="</div>";
		return $tpro;		
	}
}

