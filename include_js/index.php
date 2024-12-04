<?php
$useJS=2;
$showHeader=2; 
include_once "conf.php";
error_reporting(0);
if (isset($_REQUEST['page1'])) 
	$page1=strip_tags($_REQUEST['page1']);  
if (!isset($page1)) $page1="";
if ($page1=='') {
	$page1=strip_tags($_REQUEST['page']);  
}
$nf="";
$umpath="../include_lib/um412/";
//$umpath=$um_path;
switch ($page1) {
case "gantiprovinsi":
	cekvar("provinsi,kota");
	echo um412_isicombo5("select * from master_kota where provinsi='$provinsi' order by kota",'kota',"kota","kota","- Pilih -",$kota,""); 
	exit;
case "cekSelfRereg":
		$tgl=date("Y-m-d 00:00:00");
		$jam=date("Y-m-d h:i:s");
		$idreg=trim(str_replace("*","",$idreg));
		
		//mencari nama
		$sq="select concat(nama,' ',gelarbelakang) as nama1,rs.id as idres,rs.* from registrasi rg left join  reservasi_kongres_workshop rs on rg.id=rs.id_registran  where rs.id_registran='$idreg' ".($jform=='selfcert'?" and jenis='symposium' ":"")." order by rs.jenis asc";
		extractRecord($sq);//mencari idres
			 
		if ($idres=="") {
			echo "<h2>Your Registration Number is Invalid or Canceled </h2>";
			exit;
		}
		
		//mencari
			$sq="select registrasi.id as idreg,registrasi.tipereg, concat(registrasi.nama,' ',registrasi.gelarbelakang) as nama,
			registrasi.access_key, registrasi.namasert,registrasi.hp as hp1, registrasi.chabstract, reservasi_kongres_workshop.id,
			reservasi_kongres_workshop.jenis,
			reservasi_kongres_workshop.sponsor,
			reservasi_kongres_workshop.id_registran,
			reservasi_kongres_workshop.*,
			master_data.title,master_data.skp,master_data.ket as bobot from 
			(reservasi_kongres_workshop inner join registrasi on reservasi_kongres_workshop.id_registran=registrasi.id )
			inner join master_data on reservasi_kongres_workshop.id_master_data=master_data.id
			where reservasi_kongres_workshop.id='$idres' ";
			
			$syStat="rs.id_registran='$idreg' and rs.status='paid' and ( rs.jenis like '%sympo%' or rs.jenis like '%workshop%' ) ";
			
			$sq="select rg.id as idreg,rg.tipereg, concat(rg.nama,' ',rg.gelarbelakang) as nama,
			rg.access_key, rg.namasert,rg.hp as hp1, rg.chabstract,  rs.id,
			rs.status as stat, 
			rs.*,
			md.title,md.skp,md.ket as bobot from 
			(reservasi_kongres_workshop rs inner join registrasi rg on rs.id_registran=rg.id )
			inner join master_data md on rs.id_master_data=md.id
			where $syStat order by rs.jenis desc";
			
			extractRecord($sq);
		
		if ($jform=='selfrereg') {
			if ($rereg_by=="") {
				if ($stat!='') {
					//$sq="update reservasi_kongres_workshop set rereg_by='self',rereg_date='$jam' where id='$idres'";
					//semua direreg yang statusnya paid
					$sq="update reservasi_kongres_workshop rs set rereg_by='self',rereg_date='$jam' where  $syStat" ;
					//echo "<br>".$sq;
					mysql_query($sq);
					
				} else {
					echo "<h3>Please Confirm your payment to Onsite Registration Counter</h3>";
					exit;
				}
				$flag=1;
			
			} else {
				echo "<br><h2>You are Already Reregistration. You are forbbidden to reregistration Twice.</h2>";
				//exit;
			}
		}elseif ($jform=='selfcert') {
			if ($cert_by=="") {
				if ($stat!='') {
					$sq="update reservasi_kongres_workshop set cert_by='self',cert_date='$jam' where id='$idres'";
					//echo "<br>".$sq;
					
					echo "<h2>Check your Name, save and print. </h2>";
					mysql_query($sq);
				} else {
					echo "<h3>Please Confirm your payment to Onsite Registration Counter</h3>";
					
					exit;
				}
				$flag=1;
			
			} else {
				echo "<br><h3><blink>Your certificate already Taken by: $cert_by ($cert_date). <br>You are forbidden to Print Certificate Twice.</blink></h3>";
				//echo "<div>Check your Name, save and print. </div>";//exit;
			}
		}
		//echo "  <input type='button' name=submit2 value='print nametag'  onclick='cetakKartu();' />";
		echo "<div id=trereg>";
		include $um_path."rereg_form.php";
		echo "</div>";
		break;
case "cekFooting":
		cekVar("no");
		if (($no=="reset")&&($userid=='sa')) {//reset menjadi nol semua
			//echo "$no";
			echo $tbtmp="tbkandidat_bak_".date("Ymd_His");
			querysql("
			drop table if exists $tbtmp;
			create table $tbtmp like tbkandidat;
			insert into $tbtmp select * from tbkandidat;
			update tbkandidat set jlhpoint=0;
			update tbkandidatd set jlhpoint=0;
			");
		}
		$awarna=explode(",","danger,success,warning,info,primary,default,success,warning,info,primary,default,success,warning,info,primary,default,success,warning,info,primary");
		$tgl=date("Y-m-d 00:00:00");
		$jam=date("Y-m-d h:i:s");
		$ucapan="";
		$id=$nama="";
		
		$pm="+";
		//jika minus, maka mengurangi
		if ($no<1) {
			$pm="-";
			$no=abs($no);
		}
		 $sq="select no,nama from tbkandidat where no='$no'";
		extractRecord($sq,1);
		if ($no=="") {
			$warna="danger";
			$txt="isi dulu<br>...";
		} else {
			$warna='success';$awarna[$no];
			//update di tbkandidat
			$sq="update tbkandidat set jlhpoint=jlhpoint ".$pm." 1 where no='$no' ";
			mysql_query($sq);
			$txt="$no <br> <div class='tflagnama'>$nama</div>";
			//update di tbkandidatd
			
			$idd=carifield("select id from  tbkandidatd where idkandidat='$no' and vuserid='$userid' ")*1;
			if ($idd==0) {
				$sq="insert into  tbkandidatd set jlhpoint=jlhpoint".$pm."1,idkandidat='$no',vuserid='$userid' ";
				
			} else {
				$sq="update tbkandidatd set jlhpoint=jlhpoint".$pm."1 where id=$idd";
				
			}
				mysql_query($sq);
		}
	 
	
		echo "
		<div style='padding:10px' class='btn-$warna'>
		<span id=tflagx title='' style='color:#cce' >$txt</span> 
		<span class='text text-primary'> </span>
		</div>	 
	";
	break;
case "cekHasilFooting":
	include_once $um_path."footing-hasil.php";
	break;
case "cekDetailKandidatFooting":
	include_once $um_path."footing-detail-kandidat.php";
	break;
case "cekAbsensi":
		$tgl=date("Y-m-d 00:00:00");
		$jam=date("Y-m-d h:i:s");
		$ucapan="";
		//include_once "conf.php";
		cekVar("nmruang,nmacara");
		$hasil=cariField("select concat(nama,' ',gelarbelakang) from registrasi where id='$idreg'");
		$hnoreg=$flag=$tglmasuk=$keterangan="";
		$sy1=" nmruang='$nmruang' and nmacara='$nmacara' and tglmasuk='$tgl' ";
		$sy="h_noreg='$idreg' and $sy1";
		$sq="select h_noreg as noreg,flag,tglmasuk,keterangan from t_absen where $sy";
		//echo "$sq<br>";
		extractRecord($sq);
		if ($noreg=="") {
			$sq="insert into t_absen(tglmasuk,jammasuk,  nmacara,	nmruang, H_Noreg, flag,jumljam)
					values ('$tgl','$jam','$nmacara','$nmruang','$idreg',1,   0) ";
		} else {
			$keterangan.=str_replace(date("Y-m-d"),'',$jam);//date(" h:i:s");
			$sq="update t_absen set flag=flag+1,jamkeluar='$jam',keterangan='$keterangan' where $sy ";
		}
		
		$ucapan="".($flag*1%2==0?"Welcome ":"Thank You")."";
		$warna="".($flag*1%2==0?"success":"danger");
		mysql_query($sq);
		//echo "$sq<br>";
		$jlh=cariField("select count(flag) from t_absen where $sy1");
		$jlh2=cariField("select count(flag) from t_absen where $sy1 and (flag%2)=0 ");
		echo "
		<div style='padding:10px' class='btn-$warna'>
		<span id=tflagx title='$flag' style='color:#cce' >$ucapan</span> 
		<span class='text text-primary'>$hasil</span>
		</div>
		";//."<br>$jlh - $jlh2";
		//exit;
		break;
case "pilihTempatAcara":
		$tglx=date("Y-m-d");
//		include_once "conf.php";
		//echo "id ".$_REQUEST['idreg'];
		if (!isset($tempatacara)) $tempatacara='';
		$t="";
		$t.="<center>";
		$t.= "TANGGAL : ".date("Y-m-d")."<BR>PILIH TEMPAT/ACARA<BR>";
		$t.=um412_isicombo5("select distinct(nmruang) from m_acara",'tempatacara','nmruang','nmruang','Pilih',$tempatacara,'pilihTempatAcara()');
		$t.="<table>";
		$t.="<tr>
		<td class=tdjudul >ACARA</td>
		<td class=tdjudul >RUANG/TEMPAT</td>
		<td class=tdjudul >TANGGAL MULAI</td>
		<td class=tdjudul >TANGGAL SELESAI</td>
		<td class=tdjudul >JAM MULAI</td>
		<td class=tdjudul >JAM SELEAI</td>
		</tr>";
		
		$sq="select id as idacara,nmacara,nmruang,jam_mulai,jam_selesai from m_acara where jam_mulai<='$tglx' and jam_selesai>='$tglx'";
		//if ($tempatacara!='') $sq.=" and nmruang='$tempatacara'";
		//echo $sq;
		$h=mysql_query($sq);
		//echo "000>".$sq;
		$br=0;
		while ($r=mysql_fetch_array($h)){
			$troe=($br%2==0?"trevenform2":"troddform2");
			$nmt=$r['nmruang'];
			$nma=$r['nmacara'];
			$jammulai=date('H:i:s',strtotime($r['jam_mulai']));
			$jamselesai=date('H:i:s',strtotime($r['jam_selesai']));
			$tgl=date("Y-m-d");
			$tgm=date('Y-m-d',strtotime($r['jam_mulai']));
			$tgs=date('Y-m-d',strtotime($r['jam_selesai']));
			$onc="onclick=\"gantiRuangAcara('$nmt','$nma','$tgl','$jammulai','$jamselesai');return false;\" ";
			$t.="<tr class='$troe' $onc >
			<td>$nma</td>
			<td>$nmt</td>
			<td>$tgm</td>
			<td>$tgs</td>
			<td>$jammulai</td>
			<td>$jamselesai</td>
			</tr>";
			$br++;
		}
		$t.="</table>";
		
		$t.="</center>";
		echo $t;
		//exit;
		break;
case "achotel":	
//	include_once "conf.php";	
	$nf=$umpath."ac_hotel.php";
	break;
case "lihatabsensi":	
//	include_once "conf.php";	
	echo "lihat absensi";
	break;
case "fpay":	
//	include_once "conf.php";
	$useFormTfPay=true;
		 
	$nf=$umpath."form_pay.php";
	//echo "$tfpay";
	break;
case "login":	
	//require_once "conf.php";
	$nf=$umpath."usr-login.php";
	break;
case "usdtoidr":	
	//require_once "conf.php";
	echo USDtoIDR(1);
	//exit;
	break;
case "usdtoidr2":
	$url="http://www.google.com/ig/calculator?hl=en&q=".$_REQUEST['jumlah']."USD=?IDR";
	$content=file_get_contents($url);
	echo $content;
	$page = mb_convert_encoding($content, 'UTF-8',mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
	$datad=explode('"', $page); //echo $datad[3]; //"$datad[3] =9803.92157 Million Indonesian rupiahs"
	$datad2=$datad=explode(' ', $datad[3]); 
	$hasil=str_replace("%C2%A0","",urlencode($datad[0]))*1;
	if ($datad2[1]=='million') $hasil=$hasil*1000000;//echo str_replace(" ","", $datad2[0])*1;
	//echo "hasil".$hasil;
	//echo USDtoIDR($_REQUEST['jumlah']);
	//exit;
	break;
case "kodepromosi":	
	//require_once "conf.php";
	$idpromosi="";
	$kuota=$terpakai=$sisa=0;
	extractRecord("select id as idpromosi,kuota,terpakai,kuota-terpakai as sisa from tbpromosi where kodepromosi='$kode'");
	if ($idpromosi=='') {
		echo "<a class=xicon href='#'>invalid code</a>";
	} elseif ($sisa<=0) {
		echo "<a class=xicon href='#'>no remaining quota for this code</a>";
	}else
		echo "<a class=vicon href='#'>verified</a>";
	echo "<input id=idpromosi name=idpromosi value='$idpromosi' type='hidden'>";
	break;
case "gantipaket":	
	//echo "ganti ya.......";
	$nf=$umpath."res-paket-form.php";
	break;
case "gantiharga":	
	$jhrg=1;
	$nf=$umpath."ac_harga_paket.php";
	break;
case "gantiHrgSympo":
	$jhrg=2;
	$nf=$umpath."ac_harga_paket.php";
	break;
case "gantihargapendamping":	
	$nf=$umpath."ac_harga_pendamping.php";
	break;
case "stf":
	$nf=$umpath."ac_transfer.php";
	if (!file_exists($nf)) echo "file $nf tidak ketemu";
	
	break;
case "pilihnama":	
	$nf=$umpath."pilihnama.php";
	break;
case "pilihnama2":	
	$nf=$umpath."pilihnama2.php";
	break;
case "cekkamar":	
	$nf=$umpath."res-hotel-cek.php";
	break;
case "infohotel":	
	//$nf=$umpath."ac_info_hotel.php";
	$nf=$umpath."res-hotel-infokamar.php";
	break;
case "getphonecode":	
	//require_once "conf.php";
	echo cariField("select pcode from master_negara where negara='$negara'");
	//exit;
	break;
case "login":	
	$nf=$umpath."usr-login.php";
	break;
case "---":	
	$nf=$umpath."ac_info_hotel.php";
	break;
default:
	//$nf="ac_harga_pendamping.php";
	//echo 'default....um412/index';
	break;
}
if ($nf!='') include $nf;
?>