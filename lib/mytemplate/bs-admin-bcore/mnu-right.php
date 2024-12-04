<?php
include_once "conf.php";
extractRequest();
//guru
$nmTabel="Kelas";
$sField=$sFieldCaption="Nama";
$nfAction="index1.php?page=input&det=guru";
$hal="input.php";

$aFieldCaption=explode(",",$sFieldCaption);
$aField=explode(",",$sField);

$jField=0;$jdlTabel="<tr>";
foreach ($aFieldCaption as $jFld) {
	$jField++;
	$jdlTabel.="<td class=tdJudul>$jFld</td>";
}
//$jdlTabel.="<td class=tdJudul>Action</td>";
$jdlTabel.="</tr>";
$jdlTabel='';

	//tabel
$t="
<style>
#menu2 {
    position: relative;
    overflow: hidden;
    border-right: 1px solid #D8D7D7;
    border-left: 1px solid #D8D7D7;
    background-color: #F8F8F8;
    border-bottom: 1px solid rgba(0, 0, 0, 0.3);
    box-shadow: 0px 1px 0px rgba(255, 255, 255, 0.05);
	top:12px;
	
}  

#menu2, #menu2 ul {
    padding: 0px;
    margin: 0px;
    list-style: outside none none;
}

#menu2.collapse {
    display: inherit !important;
}
#menu2 > li {
	padding:2.5px 5px;
	}
#menu2 > li.active > a {
    position: relative;
	
}
#menu2 > li.active > a {
    color: #FFF;
    background-color: #428BCA;
}
#menu2 > li.active,
#menu2 > li:hover, 
#menu2 > li:focus {
    text-decoration: none;
    background-color: #428BCA;
    outline: medium none;
}

#menu2 > li a:visited {
	color: #00f	;
	} 

#menu2 > li.active a, 
#menu2 > li.active a:hover, 
#menu2 > li.active a:focus,
#menu2 > li a:hover, 
#menu2 > li a:focus {
	color: #FFF;
    }
</style>

	<ul id=menu2 >
	<li class='active'><a href=#><i class='icon-home'></i>&nbsp; Kelas</a></li>
	";
	
	$sq="Select * from kelas ".($userType=='siswa'?" where 1=2":"")." order by tingkat,nama ";
	
	$hq=mysql_query($sq);
	//echo "nr:".mysql_num_rows($hq);
	$br=0;
	while ($r=mysql_fetch_array($hq)) {
		$id=$r['kode'];
		$idt="rec".$br;
		$troe=($br%2==0?"troddform2":"trevenform2");
		$t.="<li id="."$idt  class='cls'>";
		for($y=0;$y<$jField;$y++) {
			$nmField=$aField[$y];
			$t.="<a href=# onclick=bukaAjax('content','input.php?det=siswa&kode_kelas=$r[kode]');>".$r[strtolower($nmField)]."</a>";
		}  
		$t.="</li>";
		$br++;	
		}
	$t.="</ul>"; 
	$t.="<br>";
	$infoKelas=$t;
	echo $infoKelas;
?>