<?php
//fungsi tanggal

$sbulan=$sBulan="Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,November,Desember";
$sbulan2=$sBulan2="Januari;1,Februari;2,Maret;3,April;4,Mei;5,Juni;6,Juli;7,Agustus;8,September;9,Oktober;10,November;11,Desember;12";
$sIsiBulan="Januari;1,Februari;2,Maret;3,April;4,Mei;5,Juni;6,Juli;7,Agustus;8,September;9,Oktober;10,November;11,Desember;12";
$aBulan=explode(",",$sBulan);
$aIsiBulan=explode(",",$sIsiBulan);

$sTahun="";
$ts1=date("Y")*1;
for ($x=1;$x<=30;$x++) {
	$ts2=$ts1-7+$x;
	$sTahun.=($sTahun==""?"":",").$ts2;
}

function extractHari($awal,$akhir,$format=""){ //akhir dan awal teks berformat y/m/d atau Y-m-d
	if ($format=="") $format="Y-m-d";
	$r=array();
	$aw=strtotime($awal);
	$ak=strtotime($akhir);
	
	if ($ak<$aw) {
		$ak=$aw;
	}
	$h=24*60*60;
	$lama=round(($ak-$aw)/$h);
 
	for($i=0;$i<=$lama;$i++) {
		$w=$aw+$h*$i;
		$ir=array();
		$ir[0]=$w;
		$ir[1]=date($format,$w);
		$ir["d"]=date("d",$w);
		$ir["m"]=date("m",$w);
		$ir["y"]=date("Y",$w);
		$r[]=$ir;
	}
	return $r;
}


Function valRF($n) {
	return removeFormat($n)*1;
}

Function removeFormat($n) {
	$n = str_replace(",", "",$n."");
	return $n;
}

//cekoperasi
function op($sop){
	global $op;
	$aop=explode(",",strtolower($sop));
	$k=(in_array(strtolower($op), $aop)?true:false);
	return $k;
}

function userType($sop){
	global $userType;
	$aop=explode(",",strtolower($sop));
	$k=(in_array(strtolower($userType), $aop)?true:false);
	return $k;
}
 

function fetchSql($sql){
	$h=mysql_query2($sql) or die("Err fetchsql:".mysql_error()."<br>$sql");
	return mysql_fetch_array($h);
}

//$maskRp="0.000.000.000".($useDecimal==0?"":",00");
function unmaskRp($num,$showRp=0){
	global $decimalSeparator,$thousandSeparator;
	if ($decimalSeparator==",") {
		//indonesia
		 
		//$num=floatval($num);
	 
		$num=str_replace(".","",$num);
		$num=str_replace(",",".",$num);
	} else {
		$num=str_replace(",","",$num);
		
	}
	$num=str_replace("Rp. ","",$num);
	//$r=str_replace("",".",$r);	
	return $num;
}

//udc: coma -> 1000:mengikuti setting, 500:1,0:0,9999:flexible

function maskRp($n,$showRp=0,$udc=1000,$digitDec=6){
	global $useDecimal,$decimalSeparator,$thousandSeparator,$media;
	
	if ($udc==1000) 
		$udc=$useDecimal;
	elseif ($udc==500) 
		$udc=1;
	elseif ($udc==1500) 
		$udc=3;
	elseif ($udc==2000) 
		$udc=4;
	elseif ($udc==9999) 
		$udc=5;
	elseif ($udc==9) 
		$udc=5;
	elseif (($udc>=90) and ($udc<=99)) { 
		//echo "--".$udc."-";
		$digitDec=$udc%10;
		$udc=5;
	}
	if ($media=='xls') 
		return $n;
	else {
		//jika tidak ada desimal, ditambahkan .00
		if ($udc==5) {
			$bulat = floor($n);      // 1
			$pecah =$n - $bulat; 	
			$result= number_format($bulat,0,$decimalSeparator,$thousandSeparator);
			if (($digitDec>0) && ($pecah>0)) {
				$pc=substr($pecah."",2,$digitDec);
				$result.=$decimalSeparator.$pc;		
				
			}
		} else {
			$result= number_format($n*1,$udc,$decimalSeparator,$thousandSeparator);		
			/*
			if ($decimalSeparator==",") {
				$result=str_replace(",",">>",$result);
				$result=str_replace(".",",",$result);
				$result=str_replace(">>",".",$result);
			}
			*/
		}
		if ($showRp==1) $result="Rp. $result"; 
		
		return $result;
		
	}
}

 
/*
function killLevel($no) {
	global $levelOwner;
	if ($levelOwner<$no) die("Opps...");
}
*/

function getBulan($kdbl) {
	global $rnd,$sbulan;
	$aBulan=explode(",",$sbulan);
	//$a=array_search($bl,$aBulan );
	return $aBulan[$kdbl-1];
}

function getKdBulan($bl) {
	global $rnd,$sbulan;
	$aBulan=explode(",",$sbulan);
	$a=array_search($bl,$aBulan );
	return ($a+1);
}
function isicomboBulan($nm,$func="") {
	global $rnd,$sbulan2,$defbl;
	$ev="global $"."$nm;$"."defbl=$"."$nm;";
	eval($ev);
	if ($defbl=="") $defbl=date("m")*1;
	return um412_isicombo5("$sbulan2",$nm,"","","",$defbl,$func);
}

function isicomboTahun($nm,$func="") {
	global $rnd,$defth,$sTahun;
	$ev="global $"."$nm;$"."defth=$"."$nm;";
	return um412_isicombo5("$sTahun",$nm,"","","",$defth,$func);
}

function maskVar($var,$m="*"){
	$s="";
	for ($i=0;$i<strlen($var);$i++) {
		$s.=$m;
	}
	return $s;
}

//add 12-1-2010
function tglIndo($t="",$showTime=0,$singkat=0,$showHari=0) {
	$hs="d M Y";
	if ($singkat==1) $hs="d-m-y";
	if ($showTime==1) $hs.=" H:i:s";
	if ($showHari==1) $hs="w, $hs";
	return tglIndo2($t,$hs);
}

function tglIndo2($t="",$format="d M Y") {
	global $aHari,$aBulan,$aBulan2;
	
	/*
	$aHari=explode(",","Minggu,...
	$aBulan=explode(",","Januari,...
	$aBulan2=explode(",","Jan,...
	$aIsiCbBulan="Januari;1,...
	*/
	if (($t=="")||(strstr($t,'0000'))) {
		//$t=time(); 
		return "-";
	} else	$t=strtotime($t);
	$hari=date("w",$t);
	$tanggal=date("d",$t);
	$bulan=date("m",$t);
	$tahun=date("Y",$t);
	$xtahun=date("y",$t);
	$jam=date("H",$t);
	$xjam=date("h",$t);
	$menit=date("i",$t);
	$detik=date("s",$t);
	$hari=$aHari[$hari];
	
	$bulan=$bulan*1;
	$bulanx=$aBulan[$bulan-1];
	$bulanxs=$aBulan2[$bulan-1];
	 $format;
	if ($format=='short') {
		$format="d x Y";
	}
	$bulanxx=substr("0".$bulan,-2);
	$l=strlen($format);
	$hs="";
		for ($x=0;$x<$l;$x++) {
			$s=substr($format,$x,1);
			switch ($s) {
			case 'D':$tb=$hari;break;
			case 'd':$tb=$tanggal;break;
			case 'M':$tb=$bulanx;break;
			case 'm':$tb=$bulanxx;break;
			case 'x':$tb=$bulanxs;break;
			case 'Y':$tb=$tahun;break;
			case 'y':$tb=$xtahun;break;
			case 'H':$tb=$jam;break;
			case 'h':$tb=$jam;break;
			case 'i':$tb=$menit;break;
			case 's':$tb=$detik;break;
			case 'w':$tb=$hari;break;
			default:$tb=$s;
			}
			$hs.=$tb;		
		}
	return $hs;
}

function rangeTglIndo2($tgl,$tgl2,$format='d x'){
	$tglm=tglindo2($tgl,$format);	
	if ($tgl!=$tgl2) {
		if (tglindo2($tgl,'m')==tglindo2($tgl2,'m')) {
			$tglm=tglindo2($tgl,'d').'-'.tglindo2($tgl2,$format);
		} else {
			$tglm.='-'.tglindo2($tgl2,$format);
		}
	}
	return $tglm;
}

function awalBulan($tgl="",$format=""){
	global $formatTgl;
	if ($tgl=="") $tgl=date("Y-m-d");
	if ($format=="") $format=$formatTgl;
	
	$date = new DateTime($tgl);
	$date->modify('first day of this month');
	$firstday= $date->format($format);
	return $firstday;
	
	/*Last day of this month
	return date($format, strtotime('first day of this month'))   ; 
	*/
}
function akhirBulan($tgl="",$format=""){
	global $formatTgl;
	if ($tgl=="") $tgl=date("Y-m-d");
	if ($format=="") $format=$formatTgl;
	
	$date = new DateTime($tgl);
	$date->modify('last day of this month');
	$lastday= $date->format($format);
	return $lastday;
	
}


//mengubah tanggal indoke tgl sql
//selisih hari bisa diisi:awminggu,akminggu,awbulan,akbulan
function tgltoSQL($tgl,$selisih=0){
	global $formatTgl;
	global $lang;
	
	if ($formatTgl=='') $formatTgl='d-m-Y';
	if ($tgl=='') return '';
	if ($tgl=='now') $tgl=date($formatTgl);
	if (strstr($tgl,"/")!='') {
		$sep="/";
		
	} else {
		$sep="-";
		
	}
	$ftgl=strtolower(str_replace($sep,"",$tgl));
		
	$aa=explode($sep,$tgl.$sep.$sep);
	
	$a=$aa[0]*1;if ($a<10) $a="0".$a;
	$b=$aa[1]*1;if ($b<10) $b="0".$b;
	$c=$aa[2]*1;
	$ftgl=strtolower($formatTgl);
	$ftgl=str_replace("-","",$ftgl);
	$ftgl=str_replace(" ","",$ftgl);
	$ftgl=str_replace("/","",$ftgl);
	
	if ($ftgl=='dmy') {
		$hasil="$c-$b-$a";
		$hr=$a;
		$bl=$b;
		$th=$c;
		
	} else {
		$hasil="$c-$a-$b";
		$hr=$b;
		$bl=$a;
		$th=$c;
		
	}
	
	//echo "<br> formatTgl : hasil $tgl menjadi $hasil<br>";
	if ($selisih!=0) {
		$kali=24/60/60;
		$hasil=strtotime($hasil)+($selisih*24*60*60);
		$hasil=date("Y-m-d",$hasil);
	}
	/*
	else if ($selisih=='awtahun') {
		$hasil="Y-01-01";
	}else	if ($selisih=='aktahun') {
		$specifiedDate = new DateTime('$th-$bl-$hr');
		$date = lastDayOf('year', $specifiedDate);
		$hasil=date("Y-m-d",$date);
	}else	if ($selisih=='awminggu') {
		$specifiedDate = new DateTime('$th-$bl-$hr');
		$date = lastDayOf('week', $specifiedDate);
		$hasil=date("Y-m-d",$date);
		
	}elseif ($selisih=='awminggu') {
		$specifiedDate = new DateTime('$th-$bl-$hr');
		$date = firstDayOf('week', $specifiedDate);
		$hasil=date("Y-m-d",$date);
		
	} else if ($selisih=='awbulan') {
		$hasil="$th-$bl-01";
		
		$date = firstDayOf('month');
		$date = lastDayOf('month');
		$specifiedDate = new DateTime('2011-08-30');
		$date = firstDayOf('week', $specifiedDate);
		$date = lastDayOf('quarter', $specifiedDate);

	} else if ($selisih=='akbulan') {
		if ($bl<12) {
			$bl++;
		} else {
			$bl=1;
			$th++;
		}
		$hasil=date("Y-m-d",strtotime($hasil)-1);
	}
	*/
	return $hasil;
}


	
function tglIndoToSQL($tglindo){
	//echo $tglindo.">";
	$tglindo=trim(strtolower($tglindo));
	$tglindo=str_replace(" ","/",$tglindo);
	$atgl=explode("/",$tglindo."//");
	
	//mengganti bulan
	$ar=array(
			  array("jan","januari"),
			  array("feb","februari","pebruari"),
			  array("mar","maret"),
			  array("apr","april"),
			  array("mei","meil"),
			  array("jun","juni"),
			  array("jul","juli"),
			  array("agt","agustus"),
			  array("sep","september"),
			  array("okt","oktober"),
			  array("nov","nop","november","nopember"),
			  array("des","desember")
			  
			 );
	$bulan="";
	$i=0;$ketemu=false;
	foreach($ar as $bl) {
		foreach($bl as $b) {
			if ($atgl[1]==$b) {
				$bulan=$i+1;
				$ketemu=true;
				break;
			}
		}
		if ($ketemu) break;
	$i++;
	}
	if (!$ketemu) $bulan=$atgl[1];
	$tahun=($atgl[2]*1<100?$atgl[2]+2000:$atgl[2]);
	$h="$tahun-$bulan-$atgl[0]";
	echo $h;
	return $h;
}	

function dtToSQL($tglindo,$format='d-m-Y H:i:s'){
	$af=explode("-",$tglindo."---");
	$t=tgltosql($af[0])." ".$af[1];	
	return $t;
}

//mengubah tanggal sql ke tgl indo
function SQLtotgl($tgl, $ftgl='',$selisih=0){
	if (($tgl=='') ||(substr($tgl,0,4)=='0000')) return ''; 
	global $formatTgl;
	global $lang;
	if (($ftgl=='') && ($formatTgl!='')) $ftgl=$formatTgl;
	if ($ftgl=='') $ftgl=$lang;
	if ($ftgl=='id') 
		$ftgl='d/m/Y';
	else if ($ftgl=='en') 
		$ftgl='m/d/Y'; 
	$h=date($ftgl,strtotime($tgl)+$selisih*24*60*60);
	return $h;
}

function getTglInput($tgl='now')  {
	$be=tgltoSQL($tgl);  
	return strtotime($be);
}
  
function hitungHari($akhir,$awal){ //akhir dan awal teks berformat y/m/d atau Y-m-d
		return round((strtotime($akhir)-strtotime($awal))/24/60/60);
	}

function comboBulan(){
	global $bulan,$aIsiCbBulan;
	//$aIsiCbBulan="Januari;1,Februari;2...
	echo um412_isicombo5($aIsiCbBulan,"bulan","bulan","","",$bulan,""); 
}
function comboProvinsi($nmselect='provinsi',$def=''){
	return um412_isicombo5('select provinsi from tbprovinsi',$nmselect,"provinsi","provinsi","",$def); 
}
function comboJK($nmselect='jk',$def=''){
	return um412_isicombo5('Perempuan;P,Laki-laki;L',$nmselect,"provinsi","provinsi","",$def); 
}
function comboStatNikah($nmselect='statnikah',$def=''){
	return um412_isicombo5('Kawin,Belum Kawin',$nmselect,"statnikah","statnikah","",$def); 
}
function comboWN($nmselect='wn',$def=''){
	return um412_isicombo5('WNI,WNA',$nmselect,"wn","wn","",$def); 
}

function USDtoIDR($jumlah,$nomorkurs=1){
	/*
	$from="USD";
	$to="IDR";
	$string = $jumlah.$from."=?".$to;
	
	$google_url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
	$result = @file_get_contents($google_url);
	if ($result) {
		$c1="<div id=currency_converter_result>$amount $from = <span class=bld>";
		$c2="$to</span>";
		$pos= strpos($result,$c1);
		if ($pos) {
			$result=substr($result,$pos+strlen($c1),100);
			$pos= strpos($result,$c2);
			if ($pos) {
				$result=trim(substr($result,0,$pos))*$jumlah;
			}
		}
	} else  
	*/
	$result=0;
	if ($result==0) {	
		$result=getconfig($nomorkurs==2?"kurs2":"kurs")*$jumlah;
	}
	return round($result,0);
}

function IDRtoUSD($jumlah,$nomorkurs=1){
	/*
	$from="IDR";
	$to="USD";
	$string = $jumlah.$from."=?".$to;
	$google_url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
	$result = @file_get_contents($google_url);
	if ($result) {
		$c1="<div id=currency_converter_result>$amount $from = <span class=bld>";
		$c2="$to</span>";
		$pos= strpos($result,$c1);
		if ($pos) {
			$result=substr($result,$pos+strlen($c1),100);
			$pos= strpos($result,$c2);
			if ($pos) {
				$result=trim(substr($result,0,$pos))*$jumlah;
			}
		}
	} else  
	*/
	$result=0;
	if ($result==0) {	
		$kurs=getconfig($nomorkurs==2?"kurs2":"kurs")*1;
		if ($kurs==0) $kurs=1;
		$result=round($jumlah/$kurs,2);
	}
	return round($result,2);
}

function http_postmx($url,$param=[],$method="post") {
	$headers = array();
    $headers[] = "Content-Type: application/json";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:39.0) Gecko/20100101 Firefox/39.0");
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	if (($method=="post")||($param==[])) {
		$fields_string = json_encode($param, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	} else {
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);	
	}
	
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function getHTTPHeader(){
	$s="";
	$headers = apache_request_headers();

	foreach ($headers as $headx => $value) {
		$s.= "$headx: $value <br />\n";
	}
	return $s;
}

function table_exists($nmtb) {
	$val = mysql_query2('select 1 from  $nmtb LIMIT 1');
	if($val !== FALSE)	{
	   return true;
	   //DO SOMETHING! IT EXISTS!
	} 	else	{
		return false;
		//I can't find it...
	}
}

function getHttpHeader2(){
	$header = '';
	foreach ($_SERVER as $key => $value) {
		if (strpos($key, 'HTTP_') === 0) {
			$chunks = explode('_', $key);
			for ($i = 1; $y = sizeof($chunks) - 1, $i < $y; $i++) {
				$header .= ucfirst(strtolower($chunks[$i])).'-';
			}
			$header .= ucfirst(strtolower($chunks[$i])).': '.$value."<br>";
			
		}
	}
	return $header.'';
}
//jresult:text/array
function getHttpRequest2($jrequest="get,request",$jresult="text"){
	$jrequest=strtolower($jrequest);
	$h="";
	if (strstr($jrequest,"get")!="") {
		if (isset($_GET)) {
			$h.=json_encode($_GET);
			
			/*
			foreach ($_GET as $param_name => $param_val) {
				$h.="$param_name:$param_val<br />\n";
			}*/
		}
	}
	if (strstr($jrequest,"post")!="") {
		if (isset($_POST)) {
			$h.=json_encode($_POST);
			/*
			foreach ($_POST as $param_name => $param_val) {
				
				$h.="$param_name:$param_val<br />\n";
			}
			*/
		}
	}
	return $h;
}

//optAct:ILE :Index.php Logout Exit $xsaveLogSQLout=true,$xexit=true
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
	if ($s!='') mysql_query2($s);
	if (strstr($sOptAct,"L")!="") {
		echo "<script>alert('RH')</script>";
		saveLogSQLout();
		redirection("index.php?op=logout");
		redirection("index.php");
	} else if (strstr($sOptAct,"I")!="")  {
		redirection("index.php");
		exit;
	} else if (strstr($sOptAct,"E")!="")  {
		exit;
	} else if (strstr($sOptAct,"N")!="")  {
		//noaction
		//exit;
	} 

}
	
function reportHacked($pes="",$showpes=false){
	 return reportHacked2($jenislog='hacked',$xsaveLogSQLout=false,$xexit=true,$ket=$pes);
}

function hacked($pes="",$showpes=false){//deprecated
}

function registerIp(){
	global $ip,$userid,$idip,$svuid,$jlhip,$userType;
	if ($userid=="") return ;
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
	if ($s!='') mysql_query2($s);
 
}

function registerClick($susertype="",$aipAllowed=array()){
	global $ip,$userid,$idip,$svuid,$jlhip,$userType;
	//khusus type teretentu saja yg dicatat
	if ($susertype!="") {
		if (!userType($susertype)) return ;
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
	
	if ($s!='') mysql_query2($s);
	//exit;
}
function secureLogin($force=0) {
	global $isOnline,$aIpBlocked,$aIpAllowed,$ip,$userType,$sUserTypeRecorded,$sUserTypeAllowed;
	if (!isset($aIpBlocked)) $aIpBlocked=array();
	if (!isset($aIpAllowed)) $aIpAllowed=array();
	if (!isset($sUserTypeRecorded)) $sUserTypeRecorded="admin,sa,guru,content";//user yg akan direcord ipnya
	if (!isset($sUserTypeAllowed)) $sUserTypeAllowed="admin,content,sa";//user yang diperbolehkan dalam aipallowed
	
	if (($isOnline)||($force==1)) {
		registerIp();//untuk memasukkan ip ke tbl_logip
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
			reportHacked2('Ip Blocked');
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
			registerClick("sa,admin,guru,content",$aip2); 	//khusus user sa,admin,guru diregister ipnya 	 
		}
		//-akhir security
		
	} else {
		$isIPAllowed=true;	//jika offline semua ip boleh
	}
 
}
function xml2array($contents, $get_attributes=1, $priority = 'tag') {
    if(!$contents) return array();

    if(!function_exists('xml_parser_create')) {
        //print "'xml_parser_create()' function not found!";
        return array();
    }

    //Get the XML parser of PHP - PHP must have this module for the parser to work
    $parser = xml_parser_create('');
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);

    if(!$xml_values) return;//Hmm...

    //Initializations
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();

    $current = &$xml_array; //Refference

    //Go through the tags.
    $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
    foreach($xml_values as $data) {
        unset($attributes,$value);//Remove existing values, or there will be trouble

        //This command will extract these variables into the foreach scope
        // tag(string), type(string), level(int), attributes(array).
        extract($data);//We could use the array by itself, but this cooler.

        $result = array();
        $attributes_data = array();
        
        if(isset($value)) {
            if($priority == 'tag') $result = $value;
            else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
        }

        //Set the attributes too.
        if(isset($attributes) and $get_attributes) {
            foreach($attributes as $attr => $val) {
                if($priority == 'tag') $attributes_data[$attr] = $val;
                else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }

        //See tag status and do the needed.
        if($type == "open") {//The starting of the tag '<tag>'
            $parent[$level-1] = &$current;
            if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                $current[$tag] = $result;
                if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                $repeated_tag_index[$tag.'_'.$level] = 1;

                $current = &$current[$tag];

            } else { //There was another element with the same tag name

                if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    $repeated_tag_index[$tag.'_'.$level]++;
                } else {//This section will make the value an array if multiple tags with the same name appear together
                    $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                    $repeated_tag_index[$tag.'_'.$level] = 2;
                    
                    if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                        $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                        unset($current[$tag.'_attr']);
                    }

                }
                $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                $current = &$current[$tag][$last_item_index];
            }

        } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
            //See if the key is already taken.
            if(!isset($current[$tag])) { //New Key
                $current[$tag] = $result;
                $repeated_tag_index[$tag.'_'.$level] = 1;
                if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

            } else { //If taken, put all things inside a list(array)
                if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                    // ...push the new element into that array.
                    $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                    
                    if($priority == 'tag' and $get_attributes and $attributes_data) {
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag.'_'.$level]++;

                } else { //If it is not an array...
                    $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $get_attributes) {
                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }
                        
                        if($attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                }
            }

        } elseif($type == 'close') { //End of tag '</tag>'
            $current = &$parent[$level-1];
        }
    }
    
    return($xml_array);
} 

function field_exists($nmtabel,$fld,$paramAdd='') {
	 $sq="SHOW COLUMNS FROM `$nmtabel` LIKE '$fld'";
	$result = mysql_query2($sq); 
	if ($result) {
		$exists = (mysql_num_rows($result))?TRUE:FALSE;
		if (!$exists && ($paramAdd!='')) {
			mysql_query2("alter table $nmtabel add $fld $paramAdd");
		} 
		return $exists;
	} else return false;
   // do your stuff
}

function resetTimeoutSesi(){
	$_SESSION['LAST_ACTIVITY']=$_SERVER['REQUEST_TIME'];
}
function cekTimeoutSesi($redirect=true){
	global $timeoutSesi,$isLogin,$op,$page;
	if (!$isLogin) return;
	if (!isset($timeoutSesi)) $timeoutSesi=15;//default 15 menit
	$sto=$timeoutSesi*60;//dalam ddetik
	if (!isset($_SESSION['LAST_ACTIVITY'])) {
		$_SESSION['LAST_ACTIVITY']=strtotime(date("Y-m-d"))-10000;
//		$_SESSION['LAST_ACTIVITY']=strtotime(date("Y-m-d"));
	}
	
	$sesi_time = $_SERVER['REQUEST_TIME'];
	$timeout=false;
	if (isset($op)) 
		$opxx=strtolower($op);
	elseif (isset($page)) 
		$opxx=strtolower($page);
	else
	   $opxx="";
	if (($sto>0) && ($opxx!='login')) {
		$sisa=($sto-($sesi_time - $_SESSION['LAST_ACTIVITY']));
		//echo "sisa waktu:".date("i:s",$sisa)." atau ".($sisa/60);
		
		//if (isset($_SESSION['LAST_ACTIVITY']) && ($sisa < 0)) {
		if ($sisa < 0) {
			//echo "<script>alert('Session Timeout, Last Activity ".date("d M Y H:i:s",$_SESSION['LAST_ACTIVITY']).", op:$opxx')</script>";
			@session_destroy();
			@session_unset();
			@session_start();
			$timeout=true;			
			if ($redirect) redirection("index.php");
		} else {
			//echo "<script>document.title='Last Activity ".date("d M Y H:i:s",$_SESSION['LAST_ACTIVITY'])."';</script>";
			
		}
		
	}
	$_SESSION['LAST_ACTIVITY'] = $sesi_time;
	return $timeout;
}

function getMyIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function changeLetterAlign($chr0) {
	$chr=strtoupper($chr0."");
	return ($chr=="L"?"Left":($chr=="C"?"Center":($chr=="R"?"Right":chr0)));
}

function cekFileMVC($nf,$jenis="model",$directInclude=true) {
	//utamakan view local
	if (strstr($nf,'.php')=='') {
		$nf0=$nf;
		$nf.=".php";
	} else {
		$nf0=substr($nf,0,strpos($nf,'.php'));
	}
	//echo "nf0 $nf0";
	$nfr=$nf;
	global $lib_app_path;
	$nf1="protected/$jenis/$nf0-local.php";
	$nf2="protected/$jenis/$nf0.php";
	$nf3=$lib_app_path."protected/$jenis/$nf0.php";
	//echo "cek <br>$nf1 <br>$nf2 <br>$nf3";
	if (file_exists($nf1)) 
		$nfr= $nf1;
	else if (file_exists($nf2)) 
		$nfr= $nf2;
	else {		
		if ($lib_app_path!='') {
			if (file_exists($nf3)) $nfr= $nf3;
		}
	}
	
	if ($directInclude)	{
		if ($nfr!='')	 include_once $nfr;
	}
	return $nfr;
}

function cekFileControllerLocal($nf,$directInclude=false) { return cekFileMVC($nf,"controller",$directInclude);}
function cekFileViewLocal($nf,$directInclude=false) { return  cekFileMVC($nf,"view",$directInclude);}
function cekFileModelLocal($nf,$directInclude=false) { return  cekFileMVC($nf,"model",$directInclude);}


function ifFileExists($nf,$addParamPre=",",$addParamNext="") {
	$fl=explode("?",trim($nf))[0]; 
	$r=(file_exists($fl)?$addParamPre.$nf.$addParamNext:"");
	return $r;
}
//only usertype .... can continue
function secureUser($sutp="admin",$act="pes") {
	$sutp.=",Super Admin,SA";
	if (!usertype($sutp)) {
		global $useSecurityLog;
		if ($useSecurityLog) reportHacked2("secureuser");
		if ($act=="exit")
			exit;
		else { 
			global $userType;
			echo um412_falr("Unauthorized User - $userType","warning");
			exit;
		}
	}
}

//resulttype='P:pesan,B:boolean'
function execQuery($sq,$resultType="P",$sAddKet="Penyimpanan") {
	$aAddKet=explode("|",$sAddKet."|");
	$h=mysql_query2($sq);
	if ($resultType=="B") {
		return ($h?true:false);
	} else {
	if ($h) 
		return um412_falr("$aAddKet[0] berhasil. $aAddKet[1]","success");
	else
		return um412_falr("$aAddKet[0] tidak berhasil,<br>".mysql_error(),"warning");
	}
}

//format angka, jika kosong, tampilkan spasi kosong
function number_format_or_blank($n,$digit) {
	if ($n*1==0)
			return " ";
		else
			return number_format($n,1);
			
}

function showIconYoutube($url,$ident="",$showUrl=false) {
	global $stdn,$rndx;
	if ($ident=="") {
		$rndx=rand(1,8766);
		$ident="tpy$rndx";
	}
	$addcls=$ket='';
	if (getYoutubeId($url)=='') {
		$addcls=' text-red';
		$ket.=' (Link youtube bermasalah)';
	}

	$oncy="previewYT('tl$ident','$url');";
	$oncy="bukaAjaxD('tl$ident','index.php?det=previewYT&contentOnly=1&useJS=2&url='+encodeURI('$url')+'&newrnd=$rndx','width:670,heigth:400','awalEdit($rndx)');";
	$isipy="<span id=tl$ident $stdn></span>
	<a href='#' onclick=\"$oncy;return false;\" title='Preview $ket'>
		<i class='fa fa-desktop $addcls'></i>
	</a>";
	if ($showUrl) $isipy.="&nbsp;&nbsp;<a href='$url' target='_blank' title='Menuju halaman Youtube'><i class='fa fa-link'></i> ".potong($url,12)."</a>";
	return $isipy;						
}

function getYoutubeId($url){
	if ($url=="") return "";
	$url=str_replace('/youtu.be/',"/www.youtube.com/watch?v=",$url);
	$url=str_replace('youtu.be/',"youtube.com/watch?v=",$url);
	preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
	if (isset($match[1]))
		return $match[1];
	else
		return "";
}

function createIFrameYoutube($url,$idIFrame='player1',$sSize=320,$sOption=""){
	global $youtubeId,$youtubeStartAt; 
	//dengan script
	 
	$youtubeId=getYoutubeId($url);
	$wIFrame=$sSize;
	$hIFrame=(240/320)*$sSize;
	parse_str( parse_url($url, PHP_URL_QUERY), $arr );
	
	//posisi awal
	$pos=0;
	$scriptMulai=$ss='';
	if (isset($arr['start'])) 
		$pos=$arr['start']*1;
	elseif (isset($arr['t'])) 
		$pos=$arr['t']*1;
	$youtubeStartAt=$pos;
	$scriptMulai=($pos*1==0?"":"event.target.seekTo($pos);");
	//$ss=($pos*1==0?"":"startSeconds: $pos,");
	
	$iframe="<div id='$idIFrame'></div>";
	$script="
		
		// 2. This code loads the IFrame Player API code asynchronously.
		var tag = document.createElement('script');
		try {
			tag.src = 'https://www.youtube.com/iframe_api';
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

			// 3. This function creates an <iframe> (and YouTube player)
			//    after the API code downloads. s:390x640
			var $idIFrame;
			function onYouTubeIframeAPIReady() {
				$idIFrame = new YT.Player('$idIFrame', {
					  height: '$hIFrame', 
					  width: '$wIFrame', 
					  videoId: '$youtubeId',
					  $ss
					  events: {
						'onReady': onPlayerReady,
						'onStateChange': onPlayerStateChange
					  }
				});
			}

			// 4. The API will call this function when the video player is ready.
			function onPlayerReady(event) {
				$scriptMulai
				event.target.playVideo();
			}

			// 5. The API calls this function when the player's state changes.
			//    The function indicates that when playing a video (state=1),
			//    the player should play for six seconds and then stop.
			var done = false;
			function onPlayerStateChange(event) {
				if (event.data == YT.PlayerState.PLAYING && !done) {
				  //setTimeout(stopVideo, 16000);
				  done = true;
				}
			}
			function stopVideo() {
				$idIFrame.stopVideo();
			}
		} catch (e) {
			console.log('tidak bisa load script youtube....');
		}			
	 
	";
	return "$iframe <script>$script</script>";
}

function createIFrameYoutube2($sUrlYoutube,$xIdFrame='iframe',$sSize=640,$xSOption="",$clsFrame="ifyoutube"){
	global $addf;
	if (isset($addf)) $addf='';
	$hasil="";
	
	$aUrl=explode(",",$sUrlYoutube);
	$x=1;
	foreach($aUrl as $uy) {
		$urlYoutube=$uy;
		$idFrame=$xIdFrame.$x;
		//cek url youtube
		$idy=getYoutubeId($urlYoutube);
		if (strstr($urlYoutube,"/embed/")==""){		
			//$idy=getYoutubeId($urlYoutube);
			parse_str( parse_url($urlYoutube, PHP_URL_QUERY), $arr );
			//posisi awal
			$pos=0;
			$scriptMulai=$ss='';
			if (isset($arr['start'])) 
				$pos=$arr['start']*1;
			elseif (isset($arr['t'])) 
				$pos=$arr['t']*1;
				
			$urlYoutube="https://www.youtube.com/embed/$idy".($pos>0?"?start=$pos":"");


			/*
			//cari idy
			//https://www.youtube.com/watch?v=msjBsHghvG0 
			$idy=trim($urlYoutube);
			$idy=str_replace("https://youtu.be/","",$idy);
			$idy=str_replace("https://www.youtube.com/watch?v=","",$idy);
			$idy=trim(str_replace("?t=","?start=",$idy));
			$idy=str_replace("&feature=youtu.be","",$idy);
			$posdan=strpos($idy,"&");
			if ($posdan>0) $idy=substr($idy,0,$posdan);
			
			$addParam="";
			//$addParam=(strstr($idy,"?")!=''?"&":"?")."autoplay=1&enablejsapi=1&version=3&playerapiid=ytplayer";		
			$urlYoutube="https://www.youtube.com/embed/$idy";//.$addParam;
			*/
		} else {
			//$idy='';
		}
		
		//echo "ini urlnya :".$urlYoutube;
		if ($sSize==='100%') {
			$wIFrame=$sSize;
			$hIFrame=(400/640)*$sSize."%";
		} else {
			$wIFrame=$sSize;
			$hIFrame=(400/640)*$sSize;
		}
		
		$urlYoutube.=(strstr($urlYoutube,"?")==""?"?":"&")."rel=0";
	//	return "ini urlnya $wIFrame >>> :".$urlYoutube.exit;;
		$sOption="$xSOption width='$wIFrame'  height='$hIFrame'";
		$sty="width:$wIFrame"."px;max-width:100%;";
		if ($idy=="") {
			$hasil.= "<div class='text text-red'>
			Link Youtube bermasalah : $urlYoutube<br>
			Url Asli : <a href='$uy' onclick=\"bukaJendela('$uy');return false;\">$uy</a>
			</div>";
		} else {
			$hasil.= "<iframe class='ifyoutube' id='$idFrame' idy='$idy' $sOption src='$urlYoutube'
			frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen 
			style='$sty' >
			 </iframe> 
			";		
			$addf.="responsiveYTPlayer('idFrame');";
		}
		$x++;
	}
	
	
	return $hasil;
	
}
function execJS($script) {
	echo "<script>$script</script>";
}

function formatSQLString($ssq) {
	$ssq=str_replace(";","<br>",$ssq);
	return $ssq;
}
function formatSSQ($ssq) {
	return formatSQLString($ssq);
}

//ubah format xml menjadi susunan yang baik 
function formatXmlString($xml){
    $xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
    $token      = strtok($xml, "\n");
    $result     = '';
    $pad        = 0; 
    $matches    = array();
    while ($token !== false) : 
        if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
          $indent=0;
        elseif (preg_match('/^<\/\w/', $token, $matches)) :
          $pad--;
          $indent = 0;
        elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
          $indent=1;
        else :
          $indent = 0; 
        endif;
        $line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
        $result .= $line . "\n";
        $token   = strtok("\n");
        $pad    += $indent;
    endwhile; 
    return $result;
}

function xmlToArray($xml, $mainHeading = '') {
	try {
		//$deXml = simplexml_load_string($xml);
		$deXml = simplexml_load_string(utf8_encode($xml), "SimpleXMLElement", LIBXML_NOCDATA);
  
		$deJson = json_encode($deXml);
		$xml_array = json_decode($deJson, TRUE);
		if (!empty($mainHeading)) {
			$returned = $xml_array[$mainHeading];
			return $returned;
		} else {
			return $xml_array;
		}
	} catch (Exception $err) {
		return $err->getMessage();
	}
}
function getNewNoTrans($jt='JU',$nmTabel="0_gl",$digit=5,$cb=""){
	return getNewNoTrans2($jt,$nmTabel,$nmfield='notrans',$digit,$cb);
}

 
function getNewNoTrans2($jt='JU',$nmTabel="0_gl",$nmfield='notrans',$digit=5,$cb="",$formatA="ym-"){
	global $op,$opcek;
	$awalan=$jt.date($formatA)."$cb";
	$na=carifield("select noakhir  from tb1kode where awalan='$awalan'")*1+1;
	if ($na==1) {
		$sq="insert into tb1kode(awalan,noakhir,digit) values('$awalan',$na,$digit)";
		mysql_query2($sq);
	} 
	
	//cari ditabel sudah dipakai belum,jika sudah buat na baru 
	$ketemu=true;
	//if (op("itb")&&($opcek!=1)) {
		//$na=max(1,$na-5);//mengecek di 5 angka terakhir ,ini tidak bisa digunakan di jaringan, no bisa tabrakan
		while ($ketemu) {
			$noakhir="00000000".$na ;
			$notrans=$awalan."".substr($noakhir,strlen($noakhir)-$digit,$digit);
			$c=carifield("select $nmfield from $nmTabel where $nmfield = '$notrans' limit 0,1 ");
			$ketemu=($c==""?false:true);
			$na++;
		}	
		$sq="update tb1kode set noakhir='".($na-1)."'  where awalan='$awalan'";
		mysql_query2($sq);
	//}
	return $notrans;
}

function getNewNoTrans3($aw='JU',$nmTabel="0_gl",$nmfield='notrans',$digit=5,$formatA="ym"){
	return getNewNoTrans2($aw,$nmTabel,$nmfield,$digit,"",$formatA);
}

/*
output: s:carifile, jika ketemu tidak mencari di lib lain. 
	a:array, 
	koma(,):,
*/
function searchLibFile($nf,$output="s"){
	$anf=array();
	global $alib_app_path;
	$ketemu=false;
	foreach($alib_app_path as $lapth) {
		$xnf=$lapth.$nf;
		if (file_exists($xnf)) {
			$anf[]=$xnf;//"protected/controller/app-func.php";
			$ketemu=true;
			if ($output=="s") return $xnf;
		}
	}	
	if($output==",")
		return implode(",",$anf);
	else
		return $anf;
		
}

function recekLogin(){
	global $isLogin,$rnd,$adm_path;
	$needLogin=false;
	if (!$isLogin) 
		$needLogin=true;
	else {
		if (usertype("guest")) $needLogin=true;
	}	
	
	if ($needLogin) {
		//extractRequest("",1);
		if (isset($_REQUEST['useJS'])) {
			echo fbe("location.href='$adm_path"."index.php'");
			exit;
		} else { 
			return false;
			/*
			global $tppath;
			echo $tppath."login.php";
			include $tppath."login.php";
			*/
		}
		//include_once $tppath."login.php";
	}
	return true;
}

//menampilkan hasil dari variabel ke dalam textarea
function showTA($var) {
	// start output buffer 2
    if (is_array($var)) {
		ob_start();
		print_r($var);
		ob_end_flush();
		$isi = ob_get_contents();      
	} else
		$isi = $var;
		
	
	$t="<textarea style='width:100%;height:200px'>$isi</textarea>";
    
	return $t;
	
}

function genRnd($awal=1000,$akhir=9999,$useYmd=false){
	$add=$useYmd?"ymd":"";
	return "1".date($add.'His').rand($awal,$akhir)*1;
}

//opsiAjaxD:- buka ajax, else bukaajaxD
//$t.=linkBAJ($tempat,"index.php?det=soalg&kdpaket=$r[kdpaket]&newrnd=$rndx",$opsiAjaxD="width:wMax",$func="awalEdit($rndx)",$cap='',$cls='btn btn-xs btn-primary');
	
function linkBAJ($target='',$url='',$opsiAjaxD='',$func='',$cap='',$cls=''){
	global $rnd;
	if ($target=='') $target='content-wrapper';
	if ($cap=='') $cap="Klik di sini";
	if ($opsiAjaxD=='-') {
		$useDialog=false;
	} else {
		$useDialog=true;
		if ($opsiAjaxD=='') $opsiAjaxD='width:wMax';
	}
	if ($func=='') $func='awalEdit($rnd)';
	
	if ($useDialog) {
		$onc="bukaAjaxD('$target','$url','$opsiAjaxD','$func')";
	} else {
		$onc="bukaAjax('$target','$url',0,'$func')";		
	}
	
	$t="<a href=# onclick=\"$onc;return false\" class='$cls'>$cap</a>
		";
	return $t;
	
}

function sortArray($array, $sort_value, $reverse = false){
    $value = $result = array();
    
    foreach($array as $key => $rows){
        $value[$key] = strtolower($rows[$sort_value]);
    }
    
    if($reverse == true){
        arsort($value);
    }else{
        asort($value);
    }
    
    foreach($value as $keys => $null){
        $result[] = $array[$keys];
    }
    
    return $result;
}

function sortMultiArray($arrData,$kol,$jsort="asc"){
	
		$ad=(strtolower($jsort)=="desc"? SORT_DESC: SORT_ASC);
		$columns = array_column($arrData, $kol);
		array_multisort($columns,$ad, $arrData);
}

/*remove directory 
if error not found method rmrf
use:
if (is_dir($file)) {
$this->rmrf("$file/*");
rmdir($file);
}

or if use this method in another methos static
use:
if (is_dir($file)) {
self::rmrf("$file/*");
rmdir($file);
}
*/
function removeDir($dir) {
	$logs="";
    foreach (glob($dir) as $file) {
        if (is_dir($file)) { 
            $logs.="<br>Removing dir $file ";
			$logs.=removeDir("$file/*");
            rmdir($file);
        } else {
            $logs.="<br>Removing file $file ";
			unlink($file);
        }
    }
	return $logs;
}

function removeSpecialChar($string,$allowSpace=true){
	$string = preg_replace('/[^\p{L}\p{N}\s]/u', '', $string);
	if ($allowSpace)
			$string = preg_replace('/[^A-Za-z0-9\- ]/', '', $string); 
		else
			$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
	return $string;
}

function folderSize($dir){
	//echo "dir $dir > ";
    $size = 0;
    foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
        if (is_file($each)) {
			$ukuran=filesize($each);
		} else {
			$ukuran=folderSize($each);
		}
		$size+=$ukuran;
		//echo "<br>$each : $ukuran ";
    }
    return $size;
}

function formatFileSize($size){
   $sizes = ['B', 'KB', 'MB', 'GB'];
   $count=0;
   if ($size < 1024) {
    return $size . " " . $sizes[$count];
    } else{
     while ($size>1024){
        $size=round($size/1024,2);
        $count++;
    }
     return $size . " " . $sizes[$count];
   }
}
//require_once('PHPExcel/Classes/PHPExcel.php');
//convertXLStoCSV('input.xls','output.csv'); 
//You can use the same function to convert XLSX to CSV. You can  convert XLS to PDF, XLS to CSV , or XLS 
function convertXLStoCSV($infile,$outfile){
    $fileType = PHPExcel_IOFactory::identify($infile);
    $objReader = PHPExcel_IOFactory::createReader($fileType);
 
    $objReader->setReadDataOnly(true);   
    $objPHPExcel = $objReader->load($infile);    
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
    $objWriter->save($outfile);
}

function isDecimal($val) {
    return is_numeric( $val ) && floor( $val ) != $val;
}

function secureFolder($path) {
	$nftemp=$path."index.php";
	if (!file_exists($nftemp)) {
		$myfile = fopen($nftemp, "w");
	}
}

//result mysql_query2: if insert return mysql_insert_id, else return true/false 
function mysql_query2($xsq,$ket='') {
	$ysq=explode(' ',trim($xsq));
	$firstw=strtolower($ysq[0]);
	if (strstr(",select,alter,show,",",$firstw,")=='')  {
		// echo "<br>".$xsq;
	}
	
	global $isTest;
	if (isset($isTest)) {
		if ($isTest)  {
		}
	}
	$ha= mysql_query($xsq);
	if ($firstw=="insert") {
		$ha=mysql_insert_id();
	}
	
	if ($ket!="nosave") {
		global $saveLogSQL,$nmTabel,$op,$id,$ket;
		if (!isset($nmTabel)) $nmTabel='';
		if (!isset($op)) $op='';
		if (!isset($id)) $id='';
		if ($saveLogSQL) addActivityToLog2($nmTabel,$op,$id,$ket,$xsq);
	}
	return $ha;
}

/*
$sacc['defKdBranch']=$defKdBranch;
$sacc['jlhBranch']=$jlhBranch;
$_SESSION['acc']=$sacc;
*/
function extractSession($nmSesi,$show=0){
	$s=$_SESSION[$nmSesi];
	foreach($s as $key=>$value) {
		//echo "$key -> $value ";
		$v="global $$key;$$key='$value';";
		eval($v);
		if ($show==1) echo "<br>Sesi : $v";
	}
	
}
	