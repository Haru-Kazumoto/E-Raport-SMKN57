<pre>
Catatan Framework
==================


perintah input
=====================
$i++; $sAllField.="#12 |catatan  |Catatan|40   |1			    |1			  |1 			    |50			       |C			 |T";
					   |nmfield  |caption|lebar|tampil di input | akan update | tampil di tabel |lebar kolom tabel | align tabel |opsiinput

nmfield: field yang ada di tabel

- jika mengandung kata tglatau jenis input=D, maka otomatis menjadi tanggal 
- jika mengandung kata detail, maka akan membuat link detail tabel
	> contoh:
      $i++; $sAllField.="#12 |Detail Request,rqdkandidat,tbrqdkandidat-norq,norq |Detail Request|40|0|0|1 |50|C|V";
	> untuk menampilkan tabel detail, bisa menggunakan fungsi
    $gAddDetail[$i]="=showDetailRQ({norq})";
- jika nama field="menu" maka tidak akan ditampilkan saat operasi view
- jika nama field menggunakan fungsi link detail maka tidak akan ditampilkan link

	$gFieldView[$i]="=createLinkDet2(\"perusahaan|id|idperusahaan|nama_perusahaan\");";
- jika ada default nilai untuk input baru
	$gDefField[$i]=date($formatTgl);

tampil di input :0,1,2,3
- jika 0 atau 2, tidak tampil
- jika 1, tampil
- jika 3, tampil sebagai view/tidak bisa edit, misal untuk subtotal, *)untuk update diperlukan script js



tampil ditabel: 0,1, atau nama field
- jika 0 tidak tampil, jika 1 tampil, jika selain itu, menampilkan sesuai dengan field yg ditulis
	contoh:
	$i++; $sAllField.="#4|lama|Lama|3|0|0|.Hari|50|C|4";
	
	lama	: menampilkan field lama
	.hari	: menampilkan field yang bersangkutan, ditambah kata "Hari"

- jika ada customview, maka yang digunakan customview
  contoh custimview
  $gFieldView[$i]="='<a href=\'content1.php?det=$det&op=viewmenu&id={id}\' target=_blank >Menu</a>';";

opsiinput
format: jenis-lebar,unique
contoh: S-5,U :string,minimal 5 huruf, unique 
- blank : textbox
- T:textarea,TA:textarea+ckeditor, D :tgl, P:password, F:file
contoh: 
	T,50,2,1 -> textarea cols=10 rows=2 
	TA,50,2,1 -> richtextarea cols=10 rows=2 1->use caption
	F,2000,I,1,upload/tugas -> file,max 2000kb, gambar,overwrite,path:upload/tugas
	A,$awNoRQ,4 : nomor otomatis, dengan awalan RQ2017-0001 (4 DIGIT) , $awNoRQ='RQ'.date('y')."-"; //nomor otomatis
	N-1000-5000,U:numeric, minimal 100 maksimal 5000

operasi input
=============
- itb : menampilkan form input
- cek : cek validasi
- tb : menambah record
- ed : edit record
- hp : hapus record
- view : menampilkan record 
- viewmenu : menampilkan menu detail jika ada file bernama $fldrmodul/$det/berkas-det.php 
- showtable : menampilkan daftar semua record
- exportxls :export ke xls
- exportcsv
- importcsv
- custom : mengcustom operasi , dengan memberikan perintah di menu $fldrmodul/$det/custom-det.php 


CUSTOM FIELD
=============
digunakan untuk fungsi2 tambahan berhubungan dengan field

$gFuncFld : menambahkan fungsi jika field input diubah
	contoh: 
	menambahkan fungsi suggestion saat mengisi field (depreciate,diganti dengan CB)
	$gFuncFld[$i]="suggestFld('$det','idperusahaan|nama_perusahaa',$rnd,this.value);";

$gGroupInput : menambahkan group input


$gFieldJudulImport=$gAddDetail=$gFieldInput=$gFieldInputCap=$gFieldView=$gFieldTabel=

$gFieldInput : digunakan untuk mengganti input dengan tampilan lain
	contoh : 
	bentuk input pake CB, jika ingin autocomplete
	$i++; $sAllField.="#1|idcabang|CABANG|40|1|1|1|1|C|CB|1|1";
	
	diganti dengan pilihan dari tabel lain
	 $gFieldInput[$i]="$"."inp=um412_isicombo5('select * from tbsales','idsales');";
	
	diganti dengan rpilihan dari tabel lain dan bisa menambah jika belum ada
	$gFieldInput[$i]="$"."inp=isiComboAA('select zona from tbzona','area_perusahaan','content1.php?det=zona&op=itb','tambah Area');";
	 
	diganti dengan pilihan yang sudah ditentukan
	 $gFieldInput[$i]="$"."inp=um412_isicombo5('Laki-laki,Perempuan','jk');";
	 $gFieldInput[$i]="$"."inp=um412_isicombo5('Laki-laki;L,Perempuan;P','jk');";
	jika menggunakan radio
	 $gFieldInput[$i]="$"."inp=um412_isicombo5('R:Laki-laki,Perempuan','jk');";
	jika menggunakan checkbox
	 $gFieldInput[$i]="$"."inp=um412_isicombo5('C:A1,A2,A3','kategori');";

khusus T dan TA, untuk membatasi tag apa saja yg akan dihilangkan, gunakan
gDeniedTagInput[$i]="html,title";


custom field berhubungan dengan datatable
------------------------------------------
$gKolDT : menentukan kolom mana sebagai kolom pencarian ,jika ditampilkan text filter, 
	jika diisi 0 maka g ada filter, jika diisi field tertentu, maka saat textfilter diisi, pencarian ke kolom tersebut.
$filterDtField : -
$gKolDT[$i]="s5.nama";

--> menyembunykan filter
$gKolDT[$i]=0; 
atau dengan cara memberikan namafield yang ditampilkan di tabel dengan awalan 'xx'
misal:
$i++; $sAllField.="#12 |pic |PIC|40|0|0|xxpicp|50|L|V";
$i++; $sAllField.="#12 |xxpic |PIC|40|0|0|1|50|L|V";

Penggunaan detail untuk output tabel
-----------------------------------------
$i++; $sAllField.="#12 |Detail PIC,dperusahaan,idperusahaan,id |PIC|40|0|0|picp|50|L|V";
$gAddDetail[$i]="=showDetailPIC('{id}');";

IMPORT DATA
---------------------------------------------------------------------

//field2 yang akan diimport
$sFieldCSV=strtolower("IDPEGAWAI,NO_TRIP,TUJUAN,KEPERLUAN,FI_DOC_NUMBER,FI_POST_DATE,FI_FISCAL_YEAR,TGLBERANGKAT,TGLPULANG,TGLTBERANGKAT,TGLTPULANG,TGLPENGAJUAN,TGLAPPROVE1,TGLAPPROVE2,JDIKLAT,BYLUMPSUM");
$sFieldCaptionCSV=strtolower("ID PEGAWAI,NO_TRIP,TUJUAN,KEPERLUAN,FI DOC_NUMBER,FI POST_DATE,FI FISCAL YEAR,TGL BERANGKAT,TGL PULANG,TGL TIKET BERANGKAT,TGL TIKET PULANG,TGL PENGAJUAN,TGL APPROVE1,TGL APPROVE2,JENIS DIKLAT,BIAYA LUMPSUM");

$capImport="Import Lumpsum";//caption tombol import
//nama file 
$nfCSV="import_lumpsump.csv";

//field kunci, untuk menentukan update (jika sudah ada) atau insert(jika belum ada)
$sFieldKey="no_trip"; 
$sFieldKeyType="C";

//field tambahan untuk insert
$tglimport=date('Y-m-d H:I:s'); //field tanda import untuk membedakan mana yang diimport saat ini

//identitas import
$idimport=rand(123101,98766661);
$sFieldIdImport='idimport';

$formatTglCSV="dmy";

//field tambahan jika insert
$sFieldCsvAdd=",tgli_lumpsum,stat,idimport";
$sFieldCsvAddValue=",'$tglimport','Lumpsum','$idimport'";

//memberikan syarat import
$syImport="
carifield(\"select idpegawai  from tbpegawai where idpegawai='-#idpegawai#-'\")!='';
'-#TGLBERANGKAT#-'!=''
";



fungsi-fungsi yang sering digunakan
===================================
function tbOpr(sop,sdet,rnd,rndInput,conf);
tbOpr('ed','pengiriman',277764,3229,'width:1000,height:700','awalEdit(3229)');


$tPosDetail=7;//untuk menentukan posisi tabel detail setelah field apa

if ($opcek==1) {//untuk menambah validasi
	$s=unmaskrp($byangkut)-unmaskrp($byangkuttunai);
	if ($s<0) $addCek.="<br>Bon Supir tidak boleh melebihi biaya angkut....";
}


</pre>