<?php
$useJS=2;
include_once "conf.php"; 

	$awdb=substr($dbku4,0,-8);
	$thdb=(substr($dbku4,-4)*1)-1;
	$dbnext=$awdb.($thdb+1).($thdb+2);
	//	echo "awdb:$awdb > thdb $thdb > dbnext $dbnext ";
if ($op!='') {
	echo "op:$op";
	if ($op=='upd') {			
		//include  $um_path."backupmydb.php";
		$skip_first_row=true;
		$csvfile =$_FILES['nff']["tmp_name"];		
		$arrResult = array();
		$handle = fopen($csvfile, "r");
		$lines = 0; $queries = ""; $linearray = array();
		$ji=$br=0;
		$jrSukses=0;
		//baris pertama tidak digunakan
		//mysql_query("update siswa set kode_kelas=kode_kelas+1000 ");
		if( $handle ) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$arrResult[] = $data;
				$idd="er".rand(123412121,943412751);
							
					if ($ji>0) {//baris  pertama judul
					$i=0;
					foreach($data as $dd) {
						$data[$i]=str_replace("'","\'",$data[$i]);
						$i++;
					}
			
					//$linemysql = "'".implode("','",$data)."'";  
					$nis=$data[0];
					$kelas=trim($data[1]);
					$kk=carifield("select kode from kelas where nama='$kelas'");
					$query = "update siswa set kode_kelas='$kk'  where nis='$nis';"; 
					if ($kk=='') {
						echo "<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($lines-1)."</a>, kelas tidak ditemukan 
							<span id=$idd style='display:none'>$data[0] $data[1]</span> ";
					} else {				
						$h=mysql_query($query) ;
						if (!$h) {
							echo "<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($lines-1)."</a> tidak bisa diimport 
							<span id=$idd style='display:none'>$query</span> ";
						} else
							$jrSukses++;
					}	
				}
				$ji++;
			}
			fclose($handle);
		}
				
		$jRecord=$ji;
		echo "ok";
		exit;
	}
	elseif ($op=='uploadk10') {			
		$skip_first_row=true;
		$csvfile =$_FILES['nff2']["tmp_name"];		
		$arrResult = array();
		$handle = fopen($csvfile, "r");
		$lines = 0; $queries = ""; $linearray = array();
		$ji=$br=0;
		$jrSukses=0;
		//baris pertama tidak digunakan
		//mysql_query("update siswa set kode_kelas=kode_kelas+1000 ");
		if( $handle ) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$arrResult[] = $data;
				$idd="er".rand(123412121,943412751);
							
					if ($ji>0) {//baris  pertama judul
					$i=0;
					foreach($data as $dd) {
						$data[$i]=str_replace("'","\'",$data[$i]);
						$i++;
					}
			
					//$linemysql = "'".implode("','",$data)."'";  
					$nis=$data[0];
					$nama=$data[1];
					$jk=($data[2]=='L'?1:0);
					$kelas=trim($data[3]);
					if ($kelas=='') continue;
					
					$kk=carifield("select kode from kelas where nama='$kelas'");
					if ($kk=='') {
						echo "<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($lines-1)."</a>, kelas tidak ditemukan 
							<span id=$idd style='display:none'>$data[0] $data[1]</span> ";
					} else {				
						$qinsert = "insert into siswa (kode_kelas,nama,gender,nis) values('$kk','$nama','$jk','$nis');"; 
						$qupdate = "update siswa set kode_kelas='$kk',nama='$nama',gender='$jk'  where nis='$nis';"; 
						$ketemu=carifield("select nis from siswa where nis='$nis'");
						$query=($ketemu==''?$qinsert:$qupdate);
						$h=mysql_query($query) ;
						if (!$h) {
							echo "<br>Baris <a href='#' onclick=\"$('#$idd').show();return false;\" >".($lines-1)."</a> tidak bisa diimport 
							<span id=$idd style='display:none'>$query</span> ";
							 
						} else {
							//echo "$query<br>";
							$jrSukses++;
						}
					}	
				}
				$ji++;
			}
			fclose($handle);
		}
				
		$jRecord=$ji;
		echo "Jumlah sukses : $jrSukses";
		exit;
	}
} else {
		$idForm="fbr01";
		$asf="onsubmit=\"ajaxSubmitAllForm('$idForm','ts"."$idForm','','',false);return false;\" ";
		
		$idForm2="fbr02";
		$asf2="onsubmit=\"ajaxSubmitAllForm('$idForm2','ts"."$idForm2','','',false);return false;\" ";
		/*onchange=\"$"."('#fbr01')submit();*/
		echo "
		<div style='padding:5px'>
			<table width=100%>
			<tr>
			<td VALIGN=TOP style='padding:15px'>
				<div class=titlepage >Update Kelas XI dan XII</div><br />

				Update kelas digunakan untuk mengupdate data kelas dari siswa pada tahun sebelumnya ke tahun saat ini (khusus siswa kelas XI dan XII).<br>
				Format : CSV<br>
				Contoh :<br>
				<table style='margin-right:20px' class='table table-bordered' border=1>
				<tr><th>NIS</th><th>KELAS</th><tr>
				<tr><td>1000</td><td>X APH 1</td><tr>
				</table>
				<div id=tfinput style='display:nonex; '>
					<form id=fbr01 style='margin-top:20px;' action='index2.php?det=$det&op=upd' enctype='multipart/form-data'
					$asf method=post target=_blank>
					
					<input class='btn btn-warning btn-sm' type=file name=nff id=nff style='width:300px'   >
					
					<div style='position:relative;margin:-31px 0px 0px 120px;text-align:right;width:250px '  >
						<input class='btn btn-warning btn-sm' type=submit value='Update' >
					</div>
					</form>
				</div>
				<div id=tsfbr01></div>
			</td>
			<td VALIGN=TOP style='padding:15px'>
			
			<div class=titlepage >Upload Data Siswa Kelas X</div><br />
			Update data siswa kelas X hanya digunakan untuk upload cepat data siswa kelas X pada awal periode.<br>
			Format : CSV<br>
			Contoh :<br>
			<table style='margin-right:20px' class='table table-bordered' border=1>
				<tr><th>NIS</tg><th>NAMA</th><th>JK</th><th>KELAS</th><tr>
				<tr><td>1000</td><td>ANDI</td><td>L</td><td>X APH 1</td><tr>
			</table>
					
			<form id=fbr02 style='margin-top:20px;' action='index2.php?det=$det&op=uploadk10' enctype='multipart/form-data'
			$asf2 method=post target=_blank>
			
			<input class='btn btn-info btn-sm' type=file name=nff2 id=nff2 style='width:300px'   >
			
			<div style='position:relative;margin:-31px 0px 0px 120px;text-align:right;width:250px '  >
				<input class='btn btn-info btn-sm' type=submit value='Update' >
			</div>
			</form>
				<div id=tsfbr02></div>
			</td>
			</tr>
			</table>
		</div>
		
		<div id=tbr></div>
		";
}
 
?>