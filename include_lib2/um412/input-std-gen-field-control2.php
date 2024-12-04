<?php
//generate field
$afld=array();
$sfld="";
$i=0;
$result="";
	
$result.="<?php
secureUser('admin,sa,Super Admin');
$"."det=\"$det\";
$"."nmTabel='$nmTabel';
$"."nmTabelAlias='$nmTabelAlias';
$"."nmCaptionTabel=\"$nmCaptionTabel\";
$"."nmFieldID='$nmFieldID';
$"."pathUpload=$"."toroot.\"upload/$det/\";

$"."showNoInTable=true; 
$"."showOpr=1;
//$"."jpperpage=50;
$"."stb=true;
$"."showFrmCari=$"."stb;
$"."showTbHapus=$"."stb;
$"."showTbView=$"."stb;
$"."showTbUbah=$"."stb;
$"."showTbPrint=$"."stb;
$"."showTbTambah=$"."stb; 

$"."showExportDB=false; 
$"."showTbFilter=false;

$"."showTbUnduh=false;
$"."showTbUnggah=false;
//$"."defOrderDT=\"[0, 'asc']\";
$"."configFrmInput=\"width:wMax-100,title: \'$"."nmCaptionTabel\'\";


$"."isTest=false; 

$"."sqTabel=\"select * from (
	select x$nmTabelAlias.* from 
	$nmTabel x$nmTabelAlias
) as  $nmTabelAlias \";


include $"."um_path.\"input-std0.php\";

/*
if ($"."isSekolah) {
	addFilterTb(\"m.kdsekolah='$userid'\");
	$"."kdsekolah=$"."userid;
	addSaveTb(\"kdsekolah\");
	$"."addInputNote=\"kode sekolah secara otomatis akan ditambahkan di field kode mapel\";
	cekVar(\"kdmp\");
	if (strstr($"."kdmp,$"."kdsekolah.\"-\")==\"\") {
		setVar(\"kdmp\",\"$"."kdsekolah-$"."kdmp\");
	} 
}
*/

";
		
	$h=mysql_query2("select * from $nmTabel where 1=2");
	$nmFld2=$srCek="";
	$strigger="";
	while ($i < mysql_num_fields($h)) {
		$meta = mysql_fetch_field($h);
		$nmfield=$meta->name;
		$type=$meta->type;
		$size=40;
		$afd[]=$nmfield;
		$sfld.=($sfld==''?'':',').$nmfield;
		$jn="S";//jenis atau lebar
		
		$xFieldSpecial="";
		$xField=$nmfield;
		$xFieldCaption=strtoupper($nmfield);
		$xLebarFieldInput=40;
		$xShowFieldInInput=1;
		$xUpdateFieldInInput=1;
		$xShowFieldInTable=1;
		$xLebarFieldTabel=30;
		$xRataFieldTabel="C";
		$xCek="$jn-0";
		$xShowFieldInView="1";
		$xAllowEdit="1";
		
		
		if ($nmfield=='catatan') {
			$jn="T";
		}elseif ( strstr($nmfield,"tgl")!='') {
			$jn="D";
		}elseif ( strstr($nmfield,"pass")!='') {
			$jn="P";
		}elseif ($type=='int') {
			$jn="I";
			$xLebarFieldInput=$xLebarFieldTabel=7;
		}elseif ($type=='string') {
			$jn="S";
			$xLebarFieldInput=min($size,70);
		}else  {
			$jn=$type;
		}
		
		if ($i==0) {
			$result.="$"."sAllField='';\n";
			$result.='$i=0;$sAllField.="'.($i>0?'#':'').$i.'|'.$nmfield.'|'.strtoupper($nmfield).'|11|0|0|0|50|C|'.$jn.'-4,U|0|0";'."\n";	
			$result.='//$gGroupInput[$i]=\''.$nmCaptionTabel.'\';';
			$result.="
			\n";
		} else {
			if ($i==1) $nmFld2=$nmfield;//menentukan field2
			
			//lb sho 
			$result.='$i++; $sAllField.="'.($i>0?'#':'')
			.$i.'|'
			.$xField.'|'
			.$xFieldCaption.'|'
			.$xLebarFieldInput.'|'
			.$xShowFieldInInput.'|'
			.$xUpdateFieldInInput.'|'
			.$xShowFieldInTable.'|'
			
			.$xLebarFieldTabel.'|'
			.$xRataFieldTabel.'|'
			
			.$xCek.'|'
			.$xShowFieldInView.'|'
			.$xAllowEdit.'";'."\n";	
			 
		}
		
		$srCek.="	if ($".$nmfield."=='') $"."pes.='*. ".strtoupper($nmfield)." tidak boleh kosong';\n";
		if(strstr(",modified_by,created_by,modified_time,created_time",$nmfield)!='') {
			$strigger.="
			if (OLD.$nmfield<>NEW.$nmfield) THEN 
				SET @changetype = concat(@changetype ,'<br>$nmfield: ',OLD.$nmfield,'->',NEW.$nmfield); 
			END IF; 
			";
		}
		$i++;
	}
	
$sfld=implode(",",$afd);
$sfldcap=strtoupper($sfld);
	
$result.="
/*
$"."gPathUpload[$"."i]=$toroot.\"upload/$det/\";
$"."gFieldInput[$"."i]=\"=um412_isicombo6('select * from tbsales','idsales');\";
$"."gFieldView[$"."i]=\"='Menu';\";
$"."gAddField[$"."i]=\"<input type=hidden name='kd_$"."rnd'><a class='btn btn-primary btn-sm' onclick=\\"."\"getDokter();return false\\"."\">show</a>\";
$"."gFieldLink[$"."i]=\"guru,id,kdguru\";//det,fldkey,fldkeyval

$"."gDefField[$"."i]=date($"."formatTgl);
$"."gFuncFld[$"."i]=\"suggestFld('$det','idperusahaan|nama_perusahaa',$rnd,this.value);\";

$"."gStrView[$"."i]= carifield(\"select concat (id,' - ',namabrg) from tbpenjualanb where id='$"."idpenjualanb' \");
addCekDuplicate('bulan,tahun,idpegawai');
if (1==2) {
	addcek.='<br>A TIDAK BOLEH SAMA DENGAN B';
}
//contoh untuk input hidden dan hanya menampilkan string tertentu (H2)
$"."i++; $"."sAllField.=\"#1|idpenjualanb|NAMA BARANG|7|1|1|namabrg|57|C|H2,0|1|1\";
$"."addInputAkhir=\"<div id=thitung_$"."rnd class='text text-alert'></div>\";

*/
//$"."isiComboFilterTabel=\"$nmFld2;$nmTabel.$nmFld2\"; 

/*
$"."addTbOpr1=\" 
<span  class='btn btn-primary btn-mini btn-sm' 
onclick=\\\"tbOpr('view|&op=view&custom=cetak1','$det|$det',$"."rnd,$"."rndInput,'$"."configFrmInput');\\\" value='Cetak' /><i class='fa fa-print'></i> Cetak Dokumen</span> \";

$"."aFilterTb=array(
		array('tingkat','$nmTabelAlias.tingkat|like','Tingkat :  '.um412_isicombo6(\"$"."sTingkat\",'xtingkat',\"#url#\"),'defXI'),
);


$"."addTbAtas=array(
	array(\"<i class='fa fa-save'></i>\",\"index.php?det=gv&op2=savetmp&jtrans=$"."jtrans\",\"Simpan Sebagai Template\",\"warning\"),
);
*/


 



$"."useInputD=0;
$"."showNoD=true;
//--------------------------detail

$"."detCari=\"barang\"; 
$"."funcEvalD=$"."adf=\"evalTr($rnd)\";
$"."sFuncFldD=\"$"."adf,$"."adf,$"."adf,$"."adf,,,,,,,,,\";
$"."opcari=\"carib2\";
$"."classtb=\"c$det\";
$"."nmTabelD='tbperseksid';
$"."nmTabelDAlias='d';
$"."fldKeyM='id';
$"."fldKeyForeign='idperseksi';
$"."fldKeyD='id';
$"."sFldD=\"deskripsi,jlh\";
$"."sFldDCap=\"Deskripsi,Jumlah\";
$"."sLebarFldD=\"220,70\";
$"."sSzFldD ='4,18,4,4,4,4,7,7';//ukuran input
$"."sClassFldD=\",rp,,,,,,,,,,,,,,,,,\";
$"."sAlignFldD=\",r,,,,,,,,,,,,,,,,,\";
$"."sAllowEditFldD=\",,,,,,,,,,,,,,,,,,\";
$"."nmCaptionTabelD=\"Rincian $"."nmCaptionTabel\";
$"."jlhDefRowAdd=1;
$"."gFieldViewD=explode(\",\", \",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,\");


/*
	//ver 2
	$"."sJenisFldD=',,,,,,i,,i,,,,,,,,,,,,,';
	$"."gFieldInputD=$"."gFieldViewD;
	$"."gFieldInputD[2]=\"=um412_isicombo5('select id,nama from tbppembantu order by nama','d_idpembantu[]','','','','#def#' );\";
	$"."gFieldInputD[$"."idxsales]=\"=um412_isicombo6('select id,nama from tbppegawai','d_idsales_$rnd"."_#no#|d_idsales[]','','','','#def#' );\";
*/

$"."footTbD=\"<td colspan=2>Jumlah</td><td align=right>rp(#jlhD1#)</td>\";
$"."showOprD=false;

$"."sqTabelD=\"select d.*  from 
($"."nmTabelD d inner join $"."nmTabel $"."nmTabelAlias on d.$"."fldKeyForeign=$"."nmTabelAlias.$"."fldKeyM)
where $"."nmTabelAlias.$"."nmFieldID='#id#' 
\";


/*
$"."gFieldViewD[2]=\"nmpelanggan\";
$"."gFieldViewD[$"."idxsales]=\"nmsales\";

$"."aFilterTb=array(
		array('kdkelas','kdkelas','Kelas :  '.um412_isicombo6(\"select kdkelas from tbkelas order by kdkelas\",'xkdkelas',\"#url#\").\"\"),
		array('jtampil','jtampil|none','Tampilan : '.um412_isicombo6(\"Global,Detail\",'xjtampil',\"#url#\")),
		array('nmbarang','kdbrg-nmbarang|like','Kode/Nama Brg. : #inp#'),
);

*/

//$"."idimport=rand(123101,98766661);
//$"."sFieldIdImport='idimport'
$"."formatTglCSV='dmy';
$"."capImport='Import ".$nmCaptionTabel."';//caption tombol import
$"."sFieldCSV=strtolower('".$sfld."');
$"."sFieldCaptionCSV= strtolower('".$sfldcap."');
$"."nfCSV='import_".str_replace(" ","_",$nmCaptionTabel).".csv';
/*
$"."sFieldCsvAdd=',kdsekolah';
$"."sFieldCsvAddValue=\",'$"."defKdSekolah'\";
$"."syImport=\"
	carifield(\\\"select kdkelas  from tbkelas where kdsekolah='$"."defKdSekolah' and kdkelas='-#kdkelas#-' \\\")!='';
	carifield(\\\"select nisn  from tbsiswa where kdsekolah='$"."defKdSekolah' and nisn='-#nisn#-' \\\")=='';
	
	\";
$"."addTxtInfoExim=\"<li>Pastikan nomor id peserta unique. nomor id yang sama akan dianggap sebagai update</li>\";
*/
include $"."um_path.\"input-std.php\";


/*
//catatan2
$"."tPosDetail=$i;//untuk menentukan posisi tabel detail setelah field apa

if ($"."opcek==1) {//untuk menambah validasi
	$"."s=unmaskrp($"."byangkut)-unmaskrp($"."byangkuttunai);
	if ($"."s<0) $"."addCek.=\"<br>Bon Supir tidak boleh melebihi biaya angkut....\";
}
*/


?>";

//buat file
$nfh=$toroot."input-$det.php";
$nfh2=$adm_path."protected/model/input-$det.php";
$nfh2gen=$adm_path."protected/model/input-$det-gen.php";

echo "<br>Periksa keberadaan file  file $nfh dan $nfh2";
if ((!file_exists($nfh)) &&(!file_exists($nfh2))) {
	echo "<br>File tidak ditemukan";
	$nf=$nfh2;
} else {
	echo "<br>File ditemukan";
	$nf=$nfh2gen;
}

$handle = fopen($nf,'w+');
if ($handle) {
	fwrite($handle, $result);
	fclose($handle);
	echo "<br>File ".__dir__.".$nf berhasil dibuat......";
}

	
$triggertb="

-- TRIGGER UPDATE --

DROP TRIGGER IF EXISTS `$nmTabel"."_after_update`;
DELIMITER $$
	CREATE TRIGGER `$nmTabel"."_after_update` AFTER UPDATE ON $nmTabel  
	FOR EACH ROW 
	
	BEGIN
		SET @changetype ='';
		$strigger
		if @changetype<>'' then		
			INSERT INTO tblog (jenislog,idtrans, ket,user,ip) VALUES ('$det',NEW.$nmFieldID, @changetype,NEW.modified_by,@ip);
		END IF;
	END
	
$$
DELIMITER ;

-- TRIGGER INSERT --
DROP TRIGGER IF EXISTS `$nmTabel"."_after_insert`;
DELIMITER $$
SELECT @ip:=host FROM information_schema.processlist WHERE id = connection_id();
CREATE TRIGGER `$nmTabel"."_after_insert` AFTER INSERT ON `$nmTabel` 
FOR EACH ROW 
BEGIN
	INSERT INTO tblog (jenislog,idtrans, ket,user) VALUES ('$det',NEW.$nmFieldID, 'tambahan baru',NEW.created_by );
END
$$
DELIMITER ;

-- TRIGGER INSERT --
DROP TRIGGER IF EXISTS `$nmTabel"."_before_delete`;
DELIMITER $$
	
CREATE TRIGGER `$nmTabel"."_before_delete` BEFORE DELETE ON `$nmTabel` 
FOR EACH ROW 
BEGIN
	INSERT INTO tblog (jenislog,idtrans, ket,user ) VALUES ('$det',OLD.$nmFieldID, 'Penghapusan Data',OLD.modified_by );
END
$$
DELIMITER ;

";

$r="<br><textarea cols=120 rows=10 style='background:#ffff99'>".str_replace("<br>","\n",$result)."</textarea>";
$r.="<br>
<div id=ttrigger style='display:none'><textarea cols=120 rows=10 style='background:#ffff99'>$triggertb</textarea>
</div>
<a href='#' onclick=\"$('#ttrigger').show();\">show trigger</a>
";

echo $r;

	// |40|1|1|1|50|C|4 : field|caption|lebarinput|showinput|update|showtable|lebartb|rata|cekking
	/*
	//cekking data
	if (strstr('|cek|tb|ed|','|$"."op|')!='') {<br>
		$"."pes='';<br>
		$srCek<br>
		echo $"."pes;<br>
		if (strstr('|cek|','|$op|')!='') exit;<br>
	}<br>
	*/
	echo "
	<div id=tcprog style='display:nonex;max-height:250px;overflow:auto'>";
	include $um_path."catatan-programmer.php";	
	echo "</div>";
	exit;
?>