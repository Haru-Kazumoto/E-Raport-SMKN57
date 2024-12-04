<?php
$useJS=2;
include_once("conf.php");
cekVar("kode_kelas,semester,op,op2,showresult");
$aJenisMP=explode(",",",,,,,,,,,,,,,,,,,,,,,");
$det='map';
if (($op=="itb")||($op=="showtable")) $op="form";
if ($op!='form') {	
	if ($op=='addguru') {
		$idg="tg".$rk;
		$t="";
		$v="";
		$t.="Nama Guru : ".um412_isicombo5("select nama from guru","pilihguru","nama","nama","-pilih-",$v,"tambahMapGuru('$rk',$rnd);$('#taddguru').dialog('close');");
		$t.="<a href=# onclick=\"tambahMapGuru('$rk',$rnd);return false\"> + </a>";	
		echo "<center>$t</center>";
		exit;	
	} elseif ($op=='savemap') {
		//cekVar("matapelajaran");
		$sq="update map_matapelajaran_kelas set matapelajaran='$matapelajaran' where kode_kelas='$kode_kelas' and semester='$semester'";
		mysql_query($sq); 
		echo "<br><br>Penyimpanan data berhasil...<br><a href='#' onclick='gantiMap($rnd);return false'>klik di sini</a> untuk refresh.";
	}
	
	if (($kode_kelas=='') ||($semester=='')) {
		echo "Pilih Kelas dan semester terlebih dahulu....";
		exit;
	} else {
		//cekking map
		cekVar("idmap,matapelajaran");
		extractRecord("select id as idmap,matapelajaran from map_matapelajaran_kelas where kode_kelas='$kode_kelas' and semester='$semester'");
		if ($idmap=='') {
			mysql_query("insert into map_matapelajaran_kelas (kode_kelas,semester,matapelajaran) values('$kode_kelas','$semester','') ");
			echo "Menambah Map...";
		}
		
		$aAwalan=explode(",","A,B,C1,C2,C3,ML");
		/*
		perlakuan khusus untuk agama harus dicentang,
		A01,A07,A08,A09,A10
		
		*/
		//$t="<br><b>Map Mata Pelajaran:</b><br><br><div id='tabs1' style='display:none;width:700px'><ul>";
		
		/*
		<div class="panel-heading">
                            Basic Tabs
                        </div>
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#home" data-toggle="tab">Home</a>
                                </li>
                                <li><a href="#profile" data-toggle="tab">Profile</a>
                                </li>
                                <li><a href="#messages" data-toggle="tab">Messages</a>
                                </li>
                                <li><a href="#settings" data-toggle="tab">Settings</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home">
                                    <h4>Home Tab</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                                <div class="tab-pane fade" id="profile">
                                    <h4>Profile Tab</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                                <div class="tab-pane fade" id="messages">
                                    <h4>Messages Tab</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                                <div class="tab-pane fade" id="settings">
                                    <h4>Settings Tab</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                                </div>
                            </div>
                        </div>
                    
		*/
		
		
		$t="
		<br><b>Map Mata Pelajaran:</b><br><br>
		<div id='tabs1' style='width:100%'>
		<ul class='nav nav-tabs'>";
		
		for ($x=0;$x<=5;$x++) {
			$t.="<li ".($x==0?"class='active'":"")." ><a href='#tabs-"."$x' data-toggle='tab'>$aAwalan[$x]</a></li>";
		}
		$t.="</ul>
		<div class='tab-content'>";
		for ($x=0;$x<=5;$x++) {
			$t.= "
			<div class='tab-pane fade".($x==0?" in active":"")."' id='tabs-"."$x' >
			<div style=';margin-bottom:18px;'>$aJenisMP[$x]</div> ";
			$sq="select * from matapelajaran where kode like '$aAwalan[$x]%' order by kode";
			$hq=mysql_query($sq);
			$pj=strlen($matapelajaran);
			while ($r=mysql_fetch_array($hq)){
				$rk=$r['kode'];
				$isitg="";
				$cari1="#$rk|";
				$pos=strpos(" ".$matapelajaran."#",$cari1);
				if ($pos>0) { //jika matapelajaran dicek
					$cc="checked";
					$pot1=substr($matapelajaran."#",$pos+strlen($cari1)-1,$pj);
					$pos2=strpos($pot1,'#');
					//echo "$pot1 <br> ";
					if ($pos2>0) {
						$pot2=substr($pot1,0,strpos($pot1,'#'));
						//memecah data guru
						$aguru=explode("|",$pot2);
						$y=0;
						foreach ($aguru as $pilih) {
							if ($pilih!='') { 
								if ($y>0) $isitg.="|";
								$a='';
								$idg="tg".$rk;
								$idpil=$idg.rand(12345179641,92345179641);
								$a.="<span id='".$idpil."' class='idpil'>";
								$a.="<a href=# onclick=\"hapusMapGuru('$idg','$idpil');return false;\" >$pilih</a>";
								$a.="</span>";
								$isitg.=$a;
								$y++;
							}
						}
					} 
				} else $cc="";
				$v="";
				//nama pengasuh
				
				$t.="<div style='line-height:20px'>
						<div style='width:90%;'><input type=checkbox id='$rk' $cc onclick=changeMapMP()> $rk : $r[nama]</div>
						<div style='float:left;display:none'>
							<span id=tg".$rk.">$isitg</span>
							<a href='#' onclick=\"$('#$rk').attr('checked','checked');
							bukaAjaxD('taddguru','inputmap.php?op=addguru&rk=".$rk."','width:600,height:90');return false;\"> + </a>
						</div><br>
					</div>";
					
				}
		
			$t.="<br><br></div>";
		}
		$t.="</div>";
		$t.="</div>";
	}	
	
	$nfAction="inputmap.php?op=savemap";
	$idForm="fmap_".rand(1231,2317);
	$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','$det','');return false;\" ";
	$t.="<form id='$idForm' action='$nfAction' method=Post $asf class=formInput >";
	$t.="
	<div style='display:none'>
	Kode_kelas<input type=text id=kkmap name='kode_kelas' value='$kode_kelas'><br />		
	Semester<input type=text id=smmap name='semester' value='$semester'><br />		
	Mp:<textarea cols=100 rows=5 type=text id=matapelajaran name='matapelajaran' >$matapelajaran</textarea><br />		
	</div><br /><br /><input class='btn btn-primary btn-sm' type=submit value='Simpan Map Matapelajaran'>
	</form>
	";
	$t.="<div id=ts"."$idForm ></div>";
	if ($showresult==1) echo $t;
	exit;
}
?>  
<div class=titlepage >Input Map Matapelajaran</div> 
<table>

<tr class=troddform2 $sty >
	<td class=tdcaption >Kelas</td>
	<td><div id=tkelas><?=um412_isiCombo5('select * from kelas order by nama ','kode_kelas','kode','nama','-Pilih-',$kode_kelas,"gantiMap($rnd)");?></div></td> 
</tr>
<tr class=troddform2 $sty >
	<td class=tdcaption >Semester</td>
	<td><div id=tsemester_<?=$rnd?> ><?=um412_isiCombo5('1,2,3,4,5,6','semester','','','-Pilih-',$semester,"gantiMap($rnd)");?></div></td> 
</tr>
</table>
</form>
<br>
<div id=tmap></div>
<div id=taddguru></div>
