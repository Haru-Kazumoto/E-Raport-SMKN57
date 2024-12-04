<?
/*
$sField				="sid,idmember,nama,namaalias,pengenal,nopengenal,alamat,kota,provinsi,telpkantor,telprumah,fax,hp,pinbb,email,tglgabung,warningbeli,tglbeli,totpoint,tothadiah,ket,tgllahir,cab,foto";
$sFieldCaption="Id,No. Barcode,Nama,Nama Alias,Tanda Pengenal,No. Pengenal,Alamat,Kota,Provinsi,Telp. Kantor,Telp. Rumah,Fax,HP,Pin BB,Email,Tgl. Gabung,Warning Pembelian,Tgl Beli Terakhir,Point Belum Terpakai,Hadiah belum Diambil,Catatan,Tgl Lahir,Outlet,Foto";
$sFieldShowInTable	="0,  ,    ,0        ,0       ,         0,      ,    ,0       ,0         ,0        ,0  ,   ,    					,0    ,0        ,     ,       ,,,,,,,,";
$sLebar				="0,30     ,  60,       60,      10,        40,    60,  30,      30,   15,   15, 15     , 15,15,60,      10,       2,14,12,12,60,12,12,12,12";
$sFieldWillUpdate	=
$sFieldShowInInput	="0,       ,    ,         ,        ,          ,      ,    ,        ,           ,        ,   ,  ,  ,  ,  ,  ,0,0,0,,,,,,";
*/

//function gabungkanSField(){
	global $sField;
    $gSField="";
	$sdiv="";
	$tt="<table cellpadding=3 border=1>";
	
	$srr="aField,aFieldCaption,aFieldShowInTable,aFieldShowInInput,aFieldWillUpdate,aLebarInput,aLebarTabel";
	$arr=explode(",",$srr);
	
	$br=0;
	foreach ($arr as $rr) {
		$i=0;
		eval("$"."vv=$"."$rr;");
		$sdiv.="<div id=sdiv".$br." ></div>";
		
		$tt.="<div id=tab""$rr ><table cellpadding=3 border=1>";
		$tt.="<tr><td>$rr</td>";
		foreach($vv as $fld) {	
			//$tt.= "<td>$fld</td>";			
			$tt.= "<td><input id=ddf"."$br[$i] value='$fld' size=12 onchange='cekRekapDDF($br);return false;'></td>";	
			$i++;
		}
		$tt.="<td>$"."$rr=<div id=rddf[$br] ></div></td>";
		$tt.="</tr>";
		$tt.="</table></div>";
		$br++;		
	}
	
	$tt.="</table>";
	$tt.=$sdiv;
	echo $tt;
    
    
    
//}

?>
