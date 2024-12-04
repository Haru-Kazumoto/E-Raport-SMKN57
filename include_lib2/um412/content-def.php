<?php
if (!isset($op)) cekVar("op");

if ($op=='genall') {
	include $um_path."mysql-create-trigger.php";
	exit;
}
elseif ($op=='deltrigger') {
	include $um_path."mysql-drop-trigger.php";
	exit;
} 
if ($det=='') $det=$ref;

if ($det!='') {
	
	switch ($det) {
		case "media":
			$torootMedia="../../";//dari media ke root
			$linkRefresh=$um_path."media-upload.php?ref=media&contentOnly=1";//refresh halaman setelah upload selesai....
			$nfAction="index.php?det=media&contentOnly=1&useJS=2";
			$targetPath = "../upload/media/";
			$targetIdDOM="tsupload";//target refresh
			$webFolder="http://".$_SERVER['HTTP_HOST']."/".$docroot.'upload/media/';
			/*
			$addParam1=",idunit,idpengcab,idpengda";
			$addParam2=",'$unit_id','$pengcab_id','$pengda_id'";
			if ($levelOwner==2) {
				$showFolderContent=false;
				$sJenisMedia="Profile Unit,Lain";
				$targetPath="../upload/media/unit/".$unit_id."/";
			}
			*/
			if ($op=='showtable') $op="";
		
			$nf=$um_path."media/media-upload.php";
			break;
		case "file";
			//echo $tkn;
			evalToken($tkn);
			if ($op=='del') {
				if ($uid==$vidusr) {
					//echo $nf;
					unlink($nf);
					if (file_exists($nf))
						echo "err:File tidak bisa dihapus";
					else
						echo 1;
				} else
					echo "Err Hapus File $uid $vidusr";
			} else {
				
			}
			exit;
			break;
		case "previewYT":
			$idIFrame="player1".genRnd();
			/*$idy=getYoutubeId($url);
			parse_str( parse_url($url, PHP_URL_QUERY), $arr );
			
			//posisi awal
			$pos=0;
			$scriptMulai=$ss='';
			if (isset($arr['start'])) 
				$pos=$arr['start']*1;
			elseif (isset($arr['t'])) 
				$pos=$arr['t']*1;
				
			$urlY="https://www.youtube.com/embed/$idy?start=$pos";
			$t=createIFrameYoutube2($urlY,$idIFrame);
			*/
			$t=createIFrameYoutube2($url,$idIFrame);
			echo $t;
			exit;
		
		case "ganticombo":
			//ganticombo();//gak bisa
				global $sql,$nms,$defcombo,$tkn,$func;
				cekVar("tkn,defcombo,func");
				evalToken($tkn);
				//if ($tkn!='') { evalToken($tkn);}
				//echo "$sql <br>$nms <br>$func <br>$defcombo";
				$sql=str_replace("samadengan","=",$sql);
				//echo " $sql,$nms,$defcombo,$tkn,$func;";
				
				echo um412_isicombo6($sql,$nms,$func,$defcombo);

			exit;
			break;
		case "ccprog":
			$nf=$um_path."catatan-programmer.php";
			break;
		case "mnukiri":
			include  $um_path."mnu-kiri-def.php";
			echo $htmlMenu;
			break;
		case "av":
			$nf=$um_path."audio-video.php";
			break;
		case "logclick":
			$nf=$um_path."input-logclick.php";
			break;
		case "logip":
			$nf=$um_path."input-logip.php";
			break;
		case "logh2":
			$nf=$um_path."input-logh2.php";
			break;
		case "pdf":
			$nf=$um_path."cetak-pdf.php";
			break;
		case "uti-csscolor":
			$nf=$um_path."um412-color-cssgen.php";
			//echo "<iframe src='$nf' style='width:100%;height:100%'></iframe>";
			//exit;
			break;
		case "cssgen":
			$nf=$um_path."um412-color-cssgen.php";
			//echo "<iframe src='$nf' style='width:100%;height:100%'></iframe>";
			//exit;
			break;
		case "backupmydb":
			cekVar("bypass");
			if ($bypass==1) $isAdmin=true;
			$nf=$um_path."backupmydb.php";
			break;
		case "restoremydb":
			$nf=$um_path."restoreDB.php";
			break;
		case "repairmydb":
			cekvar("tables");
			//echo $db->tables($tables)->repairDB();
			echo $db->table($tables)->repairDB();
			echo "<br>Repair & Optimize db finished";
			break;
		case "buatdb":
		
		//$hst,$usr,$pss
			mysql_query2("create database $db;");
			mysql_query2("uses $db;");
			$nfrestore="db/db.sql";
			$nf=$um_path."restoreDB.php";
			break;
		/*
		'case "mnguser";
			$nf="input-mnguser.php";
			break;
		*/
		case "po":
			$nf=$um_path."page-order.php";
			break;
		case "bukagambar":
			if (isset($_REQUEST['tkn'])) {
				evalToken($_REQUEST['tkn']);
			}
			
			$nfgb=decod($nfgb);
			$sty="";
			if (isset($maxw)) $sty.="max-width:$maxw"."px";
			echo "<img src=$nfgb  style='$sty'>";
			exit;
		case"buatgrafik":
			$nf=$um_path."grafik/buatgrafik-pie.php";
		case "login";
			/*
			if ($op=='logout')
				$nf=$um_path."usr-login.php";
			else
			*/
			$nf=$toroot."usr-cek-local.php";
			break;	
		case "logout";
			$nf=$um_path."usr-login.php";
			break;		 
		case "test";
			$nf=$toroot."test.php";
			break;
		case "cobs";
			$nf=$toroot."coba.php";
			break;
 
		default:
			$nfx=array();
			$nfx[]=$adm_path."protected/model/input-$det-local.php";
			$nfx[]=$adm_path."protected/model/input-$det.php";
			if ($lib_app_path!='') $nfx[]=$lib_app_path."protected/model/input-$det.php";
			$nfx[]=$toroot."model/input-$det.php";
			$nfx[]=$toroot."input-$det.php";
			$nfx[]=$lib_app_path."protected/controller/content-def.php";
			$nfx[]=$toroot."beranda.php";
			$nf="";
			$ketemu=false;
			foreach($nfx as $nfa){
				//echo "<br>cekking $nfa ";
				if ($ketemu) continue;
				
				if (file_exists($nfa)) {
					$ketemu=true;
					$nf=$nfa;
				}

				
							
			}
			//include_once $nf;			
			break;
	} //switch
} //else

//echo $nf;
?>